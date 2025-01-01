@extends('Admin.partials.master')
@section('title', 'Tambah Potensi Desa')
@section('css')
    <style>
        #deskripsi .ql-editor {
            color: #000000 !important;
            /* Warna teks hitam */
            background-color: #ffffff !important;
            /* Latar belakang putih */
        }

        #deskripsi .ql-editor::before {
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
                @if (is_array($errors->getMessages()))
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li><i class="bi bi-exclamation-diamond"></i><strong> {{ $error }}</strong></li>
                        @endforeach
                    </ul>
                @else
                    <strong>{{ $errors->first() }}</strong>
                @endif
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
                            <h4 class="card-title">Tambah Potensi Desa</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('add-potensi-desa') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <label for="judul">Judul</label>
                                    <input type="text" class="form-control" id="judul" name="judul"
                                        placeholder="Masukkan Judul Potensi" value="{{ old('judul') }}">
                                </div>
                                <div class="form-group">
                                    <label for="deskripsi">Deskripsi</label>
                                    <div id="deskripsi">{!! old('deskripsi') !!}</div>
                                    <input type="hidden" id="deskripsi_hidden" name="deskripsi" value="{{ old('deskripsi') }}">
                                </div>
                                <div class="form-group">
                                    <label for="kategori">Kategori</label>
                                    <select class="form-control" id="kategori" name="kategori">
                                        <option value="">Pilih Kategori Potensi</option>
                                        <option value="Wisata" {{ old('kategori') == 'Wisata' ? 'selected' : '' }}>Wisata
                                        </option>
                                        <option value="UMKM" {{ old('kategori') == 'UMKM' ? 'selected' : '' }}>UMKM
                                        </option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="tipe_media">Tipe Media</label>
                                    <select class="form-control" id="tipe_media" name="tipe_media">
                                        <option value="">Pilih Tipe Media</option>
                                        <option value="Gambar" {{ old('tipe_media') == 'Gambar' ? 'selected' : '' }}>Gambar
                                        </option>
                                        <option value="Youtube" {{ old('tipe_media') == 'Youtube' ? 'selected' : '' }}>
                                            Youtube</option>
                                    </select>
                                </div>
                                <div class="form-group" id="media_input_container">
                                    <!-- Input tambahan akan muncul di sini -->
                                </div>
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
            const quill = new Quill('#deskripsi', {
                theme: 'snow',
                placeholder: 'Masukkan Deskripsi Berita',
            });

            const hiddenInput = document.getElementById('deskripsi_hidden');
            quill.on('text-change', function() {
                hiddenInput.value = quill.root.innerHTML; // Simpan deskripsi Quill ke input hidden
            });

            // Pastikan input hidden terisi saat form dikirim
            document.querySelector('form').addEventListener('submit', function() {
                hiddenInput.value = quill.root.innerHTML;
            });
        });
    </script>
@endsection
