@extends('Admin.partials.master')
@section('title', 'Potensi Desa')
@section('css')
    <style>
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        /* Apply styles only to lists inside the content-body */
        .content-body ul,
        .content-body ol {
            padding-left: 20px !important;
            /* Adds padding for bullet points or numbers */
        }

        .content-body ul li {
            list-style-type: disc !important;
            /* Bullets for unordered lists */
        }

        .content-body ol li {
            list-style-type: decimal !important;
            /* Numbered list for ordered lists */
        }

        /* Don't apply any list styles to the sidebar */
        .sidebar ul,
        .sidebar ol {
            padding-left: 0 !important;
            /* Remove padding from sidebar lists */
            list-style-type: none !important;
            /* Remove bullets or numbers in the sidebar */
        }
    </style>
@endsection

@section('content')
    @if (session('success'))
        <div class="position-fixed" style="top: 100px; right: 20px; z-index: 1050;">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('success') }}</strong>
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
                            <h4 class="card-title">Potensi Desa</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table table-striped-columns table-hover"
                                    style="min-width: 845px">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">No. </th>
                                            <th class="text-center">Judul</th>
                                            <th class="text-center">Deskripsi</th>
                                            <th class="text-center">Kategori</th>
                                            <th class="text-center">Media</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datas as $data)
                                            <tr>
                                                <td class="text-center text-dark">{{ $loop->iteration }}</td>
                                                <td class="text-center text-dark">{{ $data->judul }}</td>
                                                @php
                                                    $data->deskripsi = strip_tags(
                                                        $data->deskripsi,
                                                        '<strong><em><u><ul><ol><li><a>',
                                                    );
                                                    $data->deskripsi =
                                                        strlen($data->deskripsi) > 300
                                                            ? substr($data->deskripsi, 0, 300) . '...'
                                                            : $data->deskripsi;
                                                @endphp
                                                <td class="text-dark">{!! $data->deskripsi !!}</td>
                                                <td class="text-center text-dark">{{ $data->kategori }}</td>
                                                <td class="text-center text-dark">
                                                    @if ($data->media->tipe_media == 'Gambar')
                                                        @php
                                                            $gambar = Yaza\LaravelGoogleDriveStorage\Gdrive::get(
                                                                $data->media->file_id,
                                                            );
                                                            $base64Gambar = base64_encode($gambar->file);
                                                        @endphp

                                                        <img src="data:image/jpeg;base64,{{ $base64Gambar }}"
                                                            style="width: 320px; height: 240px" alt="Gambar Potensi">
                                                    @elseif($data->media->tipe_media == 'Youtube')
                                                        <a href="https://www.youtube.com/watch?v={{ $data->media->youtube_id }}"
                                                            target="_blank">
                                                            <img src="https://img.youtube.com/vi/{{ $data->media->youtube_id }}/hqdefault.jpg"
                                                                alt="YouTube Thumbnail"
                                                                style="width: 320px; height: 180px; border-radius: 8px;">
                                                        </a>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('edit-potensi-desa', $data->id_potensi) }}"
                                                        class="btn btn-success" style="margin-bottom: 20px"><i
                                                            class="bi bi-pencil-square"></i> Edit</a>
                                                    <button class="btn btn-danger" data-toggle="modal"
                                                        data-target="#HapusModal{{ $data->id_potensi }}"
                                                        style="margin-bottom: 20px"><i class="bi bi-trash3"></i>
                                                        Hapus</button>
                                                    <a href="{{ route('preview-potensi-desa', $data->id_potensi) }}"
                                                        class="btn btn-info"><i class="bi bi-eye"></i> Preview</a>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="HapusModal{{ $data->id_potensi }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Hapus Data
                                                                Potensi Desa</h5>
                                                            <button class="close" type="button" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-left text-dark">Apakah anda yakin
                                                            menghapus
                                                            data potensi desa tersebut?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-warning" type="button"
                                                                data-dismiss="modal">Tidak</button>
                                                            <form
                                                                action="{{ route('delete-potensi-desa', $data->id_potensi) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">
                                                                    Iya
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
