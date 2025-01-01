<?php

namespace App\Http\Controllers;

use App\Models\BeritaModel;
use App\Models\MediaModel;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class BeritaDesaController extends Controller
{
    public function index()
    {
        $datas = BeritaModel::with('media')->orderBy('tanggal', 'desc')->get();

        return view('Admin.Berita-Desa.index', compact('datas'));
    }

    public function create()
    {
        return view('Admin.Berita-Desa.add');
    }

    public function store(Request $request)
    {
        $request->merge([
            'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d H:i:s')
        ]);

        $messages = [
            'required' => ':attribute wajib diisi',
            'unique' => ':attribute sudah ada',
            'min' => ':attribute minimal :min karakter',
            'date_format' => ':attribute harus berupa tanggal dan waktu',
            'image' => ':attribute harus berupa gambar',
            'mimes' => ':attribute harus berupa file dengan tipe: :values',
            'max' => ':attribute maksimal :max KB',
            'required_if' => ':attribute wajib diisi jika :other adalah :value',
            'url' => ':attribute harus berupa URL',
        ];

        $validator = Validator::make($request->all(), [
            'judul' => 'required|unique:berita_desa,judul',
            'konten' => 'required|min:100',
            'tanggal' => 'required|date_format:Y-m-d H:i:s',
            'tipe_media' => 'required|in:Gambar,Video,Youtube',
            'media_image' => 'required_if:tipe_media,Gambar|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'url_media' => 'required_if:tipe_media,Youtube|url',
        ], $messages);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request->merge([
            'slug' => Str::slug($request->judul, '-'),
        ]);

        $berita = BeritaModel::create([
            'judul' => $request->judul,
            'slug' => $request->slug,
            'konten' => $request->konten,
            'tanggal' => $request->tanggal,
        ]);

        if ($request->tipe_media == 'Youtube') {
            $youtubeId = $this->getYouTubeId($request->url_media);

            MediaModel::create([
                'id_berita' => $berita->id_berita,
                'tipe_media' => $request->tipe_media,
                'youtube_id' => $youtubeId,
            ]);
        } else if ($request->tipe_media == 'Gambar' && $request->hasFile('media_image')) {
            $file = $request->file('media_image');
            $originalFileName = $file->getClientOriginalName();
            $uniqueId = Str::random(6);
            $fileName = Carbon::now()->format('d_m_Y') . ' - ' . $uniqueId . ' - Dalisodo - ' . $originalFileName;

            try {
                $folder = 'Web Profil Desa/Berita';
                $filePath = $file->storeAs($folder, $fileName, 'google');
                $fileId = 'Web Profil Desa/Berita/' . basename($filePath);

                MediaModel::create([
                    'id_berita' => $berita->id_berita,
                    'tipe_media' => $request->tipe_media,
                    'file_id' => $fileId,
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal mengunggah file: ' . $e->getMessage());
            }
        }

        return redirect()->route('list-berita-desa')->with('success', 'Berhasil menambahkan potensi desa');
    }

    public function show(string $uuid)
    {
        $berita = BeritaModel::with('media')->find($uuid);

        return view('Admin.Berita-Desa.edit', compact('berita'));
    }

    public function update(Request $request, $uuid)
    {
        $request->merge(
            [
                'tanggal' => Carbon::parse($request->tanggal)->format('Y-m-d H:i:s')
            ]
        );

        $messages = [
            'required' => ':attribute wajib diisi',
            'unique' => ':attribute sudah ada',
            'min' => ':attribute minimal :min karakter',
            'date_format' => ':attribute harus berupa tanggal dan waktu',
            'image' => ':attribute harus berupa gambar',
            'mimes' => ':attribute harus berupa file dengan tipe: :values',
            'max' => ':attribute maksimal :max KB',
            'url' => ':attribute harus berupa URL',
        ];

        $validator = Validator::make($request->all(), [
            'judul' => 'required|unique:berita_desa,judul,' . $uuid . ',id_berita',
            'konten' => 'required|min:100',
            'tanggal' => 'required|date_format:Y-m-d H:i:s',
            'tipe_media' => 'required|in:Gambar,Video,Youtube',
            'media_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'url_media' => 'nullable|url',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $request->merge([
            'slug' => Str::slug($request->judul, '-'),
        ]);

        $berita = BeritaModel::findOrFail($uuid);
        $berita->update([
            'judul' => $request->judul,
            'slug' => $request->slug,
            'konten' => $request->konten,
            'tanggal' => $request->tanggal,
        ]);

        $media = MediaModel::where('id_berita', $uuid)->first();

        if ($request->media_image) {
            if ($media->file_id) {
                Storage::disk('google')->delete($media->file_id);
            }

            $file = $request->file('media_image');
            $originalFileName = $file->getClientOriginalName();
            $uniqueId = Str::random(6);
            $fileName = Carbon::now()->format('d_m_Y') . ' - ' . $uniqueId . ' - Dalisodo - ' . $originalFileName;

            try {
                $folder = 'Web Profil Desa/Berita';
                $filePath = $file->storeAs($folder, $fileName, 'google');
                $fileId = 'Web Profil Desa/Berita/' . basename($filePath);

                $media->update([
                    'tipe_media' => $request->tipe_media,
                    'file_id' => $fileId,
                    'youtube_id' => null,
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal mengunggah file: ' . $e->getMessage());
            }
        } else if ($request->url_media && $request->tipe_media == 'Youtube') {
            if ($media->file_id) {
                Storage::disk('google')->delete($media->file_id);
            }

            $youtubeId = $this->getYouTubeId($request->url_media);

            $media->update([
                'tipe_media' => $request->tipe_media,
                'file_id' => null,
                'youtube_id' => $youtubeId,
            ]);
        }

        return redirect()->route('list-berita-desa')->with('success', 'Berhasil memperbarui potensi desa');
    }

    public function destroy(string $uuid)
    {
        $potensi = BeritaModel::find($uuid);
        $media = MediaModel::where('id_berita', $uuid)->first();

        if ($media) {
            if ($media->file_id) {
                Storage::disk('google')->delete($media->file_id);
            }

            $media->delete();
        }

        $potensi->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function preview(string $uuid)
    {
        $item = BeritaModel::with('media')->find($uuid);
        $item->type = 'berita';
        
        return view('detail', compact('item'));
    }

    function getYouTubeId($url) {
        $pattern = '/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1]; // Mengembalikan video ID
        }
        return null; // Jika tidak cocok
    }
}
