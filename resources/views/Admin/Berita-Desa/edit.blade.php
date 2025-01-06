@extends('Admin.partials.master')
@section('title', 'Edit Berita Desa')
@section('css')
    <style>
        #konten .ql-editor {
            color: #000000 !important;
            /* Warna teks hitam */
            background-color: #ffffff !important;
            /* Latar belakang putih */
        }

        #konten .ql-editor::before {
            color: rgba(0, 0, 0, 0.5) !important;
            /* Warna placeholder abu-abu */
            background-color: transparent !important;
            /* Hindari warna latar placeholder */
        }
    </style>
@endsection

@section('content')
    @if ($errors->any())
        <div class="position-fixed" style="top: 100px; right: 20px; z-index: 1050;">
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Error:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li><i class="bi bi-exclamation-diamond"></i><strong> {{ $error }}</strong></li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif

    <div class="content-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Edit Berita Desa</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('update-berita-desa', $berita->id_berita) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="form-group">
                                    <label for="judul">Judul</label>
                                    <input type="text" class="form-control" id="judul" name="judul"
                                        value="{{ $berita->judul }}" placeholder="Masukkan Judul Berita">
                                </div>

                                <div class="form-group">
                                    <label for="konten">Konten</label>
                                    <div id="konten">{!! old('konten') !!}</div>
                                    <input type="hidden" id="konten_hidden" name="konten"
                                        value="{{ old('konten', $berita->konten ?? '') }}">
                                </div>

                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input type="datetime-local" name="tanggal" id="tanggal" class="form-control"
                                        value="{{ $berita->tanggal }}">
                                </div>

                                <div class="form-group">
                                    <label for="tipe_media">Tipe Media</label>
                                    <select class="form-control" id="tipe_media" name="tipe_media">
                                        <option value="Gambar"
                                            {{ $berita->media->tipe_media == 'Gambar' ? 'selected' : '' }}>
                                            Gambar</option>
                                        <option value="Youtube"
                                            {{ $berita->media->tipe_media == 'Youtube' ? 'selected' : '' }}>Youtube
                                        </option>
                                    </select>
                                </div>

                                <div class="form-group" id="media_input_container">
                                    @if ($berita->media->tipe_media == 'Gambar')
                                        <img src="{{ $berita->media->file_id }}" style="width: 320px; height: 240px"
                                            alt="{{ $berita->judul }}">
                                        <input type="file" class="form-control" id="media_image" name="media_image">
                                    @elseif ($berita->media->tipe_media == 'Youtube')
                                        <a href="https://www.youtube.com/watch?v={{ $berita->media->youtube_id }}"
                                            target="_blank">
                                            <img src="https://img.youtube.com/vi/{{ $berita->media->youtube_id }}/hqdefault.jpg"
                                                alt="YouTube Thumbnail"
                                                style="width: 320px; height: 240px; margin-bottom: 15px; border-radius: 8px;">
                                        </a>
                                        <input type="url" class="form-control" id="url_media" name="url_media"
                                            placeholder="Masukkan URL Youtube"
                                            value="https://www.youtube.com/watch?v={{ $berita->media->youtube_id }}">
                                    @endif
                                </div>

                                <a href="{{ route('list-berita-desa') }}" class="btn btn-success">Close</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        document.getElementById('tipe_media').addEventListener('change', function() {
            const container = document.getElementById('media_input_container');
            container.innerHTML = ''; // Hapus input sebelumnya

            if (this.value === 'Gambar') {
                const input = document.createElement('input');
                input.type = 'file';
                input.className = 'form-control';
                input.id = 'media_image';
                input.name = 'media_image';
                container.appendChild(input);
            } else if (this.value === 'Youtube') {
                const input = document.createElement('input');
                input.type = 'url';
                input.className = 'form-control';
                input.id = 'url_media';
                input.name = 'url_media';
                input.placeholder = 'Masukkan URL Youtube';
                container.appendChild(input);
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const quill = new Quill('#konten', {
                theme: 'snow',
                placeholder: 'Masukkan Konten Berita',
            });

            const hiddenInput = document.getElementById('konten_hidden');

            // Ambil data lama dan masukkan ke Quill editor
            const oldContent = `{!! old('konten', $berita->konten ?? '') !!}`;
            if (oldContent) {
                quill.clipboard.dangerouslyPasteHTML(oldContent); // Memasukkan konten ke Quill
            }

            // Sinkronkan Quill dengan hidden input
            quill.on('text-change', function() {
                hiddenInput.value = quill.root.innerHTML; // Simpan konten Quill ke hidden input
            });

            // Pastikan input hidden diperbarui saat form dikirim
            document.querySelector('form').addEventListener('submit', function() {
                hiddenInput.value = quill.root.innerHTML;
            });
        });
    </script>
@endsection
