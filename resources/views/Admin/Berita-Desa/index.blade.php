@extends('Admin.partials.master')
@section('title', 'Berita Desa')
@section('css')
    <style>
        th,
        td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .content-body ul,
        .content-body ol {
            padding-left: 20px !important;
        }

        .content-body ul li {
            list-style-type: disc !important;
        }

        .content-body ol li {
            list-style-type: decimal !important;
        }

        .sidebar ul,
        .sidebar ol {
            padding-left: 0 !important;
            list-style-type: none !important;
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
                            <h4 class="card-title">Berita Desa</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="example" class="table table-striped-columns table-hover"
                                    style="min-width: 845px">
                                    <thead class="thead-light">
                                        <tr>
                                            <th class="text-center">No. </th>
                                            <th class="text-center">Judul</th>
                                            <th class="text-center">Konten</th>
                                            <th class="text-center">Tanggal</th>
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
                                                    $data->konten = strip_tags(
                                                        $data->konten,
                                                        '<strong><em><u><ul><ol><li><a>',
                                                    );
                                                    $data->konten =
                                                        strlen($data->konten) > 300
                                                            ? substr($data->konten, 0, 300) . '...'
                                                            : $data->konten;
                                                @endphp
                                                <td class="text-dark">{!! $data->konten !!}</td>
                                                <td class="text-center text-dark">
                                                    {{ \Carbon\Carbon::parse($data->tanggal)->locale('id')->translatedFormat('d F Y H:i:s') }}
                                                </td>
                                                <td class="text-center text-dark">
                                                    @if ($data->media->tipe_media == 'Gambar')
                                                        <img src="{{ $data->media->file_id }}"
                                                            style="width: 320px; height: 240px" alt="">
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
                                                    <a href="{{ route('edit-berita-desa', $data->id_berita) }}"
                                                        class="btn btn-success" style="margin-bottom: 20px"><i
                                                            class="bi bi-pencil-square"></i> Edit</a>
                                                    <button class="btn btn-danger" data-toggle="modal"
                                                        data-target="#HapusModal{{ $data->id_berita }}"
                                                        style="margin-bottom: 20px"><i class="bi bi-trash3"></i>
                                                        Hapus</button>
                                                    <a href="{{ route('preview-berita-desa', $data->id_berita) }}"
                                                        class="btn btn-info"><i class="bi bi-eye"></i> Preview</a>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="HapusModal{{ $data->id_berita }}" tabindex="-1"
                                                role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Hapus Data
                                                                Berita Desa</h5>
                                                            <button class="close" type="button" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">×</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body text-left text-dark">Apakah anda yakin
                                                            menghapus
                                                            data berita desa tersebut?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-warning" type="button"
                                                                data-dismiss="modal">Tidak</button>
                                                            <form
                                                                action="{{ route('delete-berita-desa', $data->id_berita) }}"
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
