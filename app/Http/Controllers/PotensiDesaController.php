<?php

namespace App\Http\Controllers;

use App\Models\MediaModel;
use App\Models\PotensiModel;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PotensiDesaController extends Controller
{
    public function index()
    {

        $datas = PotensiModel::with('media')->orderBy('created_at', 'desc')->get();

        return view('Admin.Potensi-Desa.index', compact('datas'));
    }

    public function create()
    {
        return view('Admin.Potensi-Desa.add');
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => ':attribute wajib diisi',
            'unique' => ':attribute sudah ada',
            'min' => ':attribute minimal :min karakter',
            'in' => ':attribute harus berupa :values',
            'image' => ':attribute harus berupa gambar',
            'mimes' => ':attribute harus berupa file dengan tipe: :values',
            'max' => ':attribute maksimal :max KB',
            'required_if' => ':attribute wajib diisi jika :other adalah :value',
            'url' => ':attribute harus berupa URL',
        ];

        $validator = Validator::make($request->all(), [
            'judul' => 'required|unique:potensi_desa,judul',
            'deskripsi' => 'required|min:100',
            'kategori' => 'required|in:Wisata,UMKM',
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

        $potensi = PotensiModel::create([
            'judul' => $request->judul,
            'slug' => $request->slug,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
        ]);

        if ($request->tipe_media == 'Youtube') {
            $youtubeId = $this->getYouTubeId($request->url_media);

            MediaModel::create([
                'id_potensi' => $potensi->id_potensi,
                'tipe_media' => $request->tipe_media,
                'youtube_id' => $youtubeId,
            ]);
        } else if ($request->tipe_media == 'Gambar' && $request->hasFile('media_image')) {
            $file = $request->file('media_image');
            $originalFileName = $file->getClientOriginalName();
            $uniqueId = Str::random(6);
            $fileName = Carbon::now()->format('d_m_Y') . ' - ' . $uniqueId . ' - Dalisodo - ' . $originalFileName;

            try {
                $folder = 'Web Profil Desa/Potensi';
                $filePath = $file->storeAs($folder, $fileName, 'google');
                $fileId = 'Web Profil Desa/Potensi/' . basename($filePath);

                MediaModel::create([
                    'id_potensi' => $potensi->id_potensi,
                    'tipe_media' => $request->tipe_media,
                    'file_id' => $fileId,
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal mengunggah file: ' . $e->getMessage());
            }
        }

        return redirect()->route('list-potensi-desa')->with('success', 'Berhasil menambahkan potensi desa');
    }

    public function show(string $uuid)
    {
        $potensi = PotensiModel::with('media')->find($uuid);

        return view('Admin.Potensi-Desa.edit', compact('potensi'));
    }

    public function update(Request $request, $uuid)
    {
        $messages = [
            'required' => ':attribute wajib diisi',
            'unique' => ':attribute sudah ada',
            'min' => ':attribute minimal :min karakter',
            'in' => ':attribute harus berupa :values',
            'image' => ':attribute harus berupa gambar',
            'mimes' => ':attribute harus berupa file dengan tipe: :values',
            'max' => ':attribute maksimal :max KB',
            'required_if' => ':attribute wajib diisi jika :other adalah :value',
            'url' => ':attribute harus berupa URL',
        ];

        $validator = Validator::make($request->all(), [
            'judul' => 'required|unique:potensi_desa,judul,' . $uuid . ',id_potensi',
            'deskripsi' => 'required|min:100',
            'kategori' => 'required|in:Wisata,UMKM',
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

        $potensi = PotensiModel::findOrFail($uuid);
        $potensi->update([
            'judul' => $request->judul,
            'slug' => $request->slug,
            'deskripsi' => $request->deskripsi,
            'kategori' => $request->kategori,
        ]);

        $media = MediaModel::where('id_potensi', $uuid)->first();

        if($request->media_image) {
            if ($media->file_id) {
                Storage::disk('google')->delete($media->file_id);
            }

            $file = $request->file('media_image');
            $originalFileName = $file->getClientOriginalName();
            $uniqueId = Str::random(6);
            $fileName = Carbon::now()->format('d_m_Y') . ' - ' . $uniqueId . ' - Dalisodo - ' . $originalFileName;

            try {
                $folder = 'Web Profil Desa/Potensi';
                $filePath = $file->storeAs($folder, $fileName, 'google');
                $fileId = 'Web Profil Desa/Potensi/' . basename($filePath);

                $media->update([
                    'tipe_media' => $request->tipe_media,
                    'file_id' => $fileId,
                    'youtube_id' => null,
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Gagal mengunggah file: ' . $e->getMessage());
            }
        } else if($request->url_media && $request->tipe_media == 'Youtube') {
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

        return redirect()->route('list-potensi-desa')->with('success', 'Berhasil memperbarui potensi desa');
    }

    public function destroy(string $uuid)
    {
        $potensi = PotensiModel::find($uuid);
        $media = MediaModel::where('id_potensi', $uuid)->first();

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
        $item = PotensiModel::with('media')->find($uuid);
        $item->type = 'potensi';

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
