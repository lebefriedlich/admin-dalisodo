@extends('layouts.app')

@section('title', 'Preview - ' . $item->judul)

@section('content')
    <style>
        .news-detail {
            padding: 40px 0;
        }

        .news-header {
            position: relative;
            margin-bottom: 30px;
        }

        .news-image {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .news-meta {
            margin: 20px 0;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .news-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 20px 0;
            color: #2c3e50;
        }

        .news-content {
            font-size: 1.1rem;
            line-height: 1.8;
            color: #34495e;
            text-align: justify;
        }

        .share-buttons {
            margin: 30px 0;
            padding: 20px 0;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }

        .share-buttons a {
            margin-right: 15px;
            color: #2c3e50;
            transition: color 0.3s;
        }

        .share-buttons a:hover {
            color: #3498db;
        }

        .back-button {
            padding: 10px 25px;
            border-radius: 25px;
            transition: all 0.3s;
            background: #3498db;
            border: none;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.2);
        }

        .back-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(52, 152, 219, 0.3);
            background: #2980b9;
        }

        .date-badge {
            position: absolute;
            top: 20px;
            left: 20px;
            background: rgba(255, 255, 255, 0.9);
            padding: 10px 20px;
            border-radius: 25px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .reading-time {
            display: inline-block;
            padding: 5px 15px;
            background: #f8f9fa;
            border-radius: 15px;
            font-size: 0.9rem;
            margin-left: 15px;
        }

        .copy-url {
            margin-right: 15px;
            color: #2c3e50;
            cursor: pointer;
            transition: color 0.3s;
        }

        .copy-url:hover {
            color: #3498db;
        }

        h3 {
            font-size: 1.2em;
            line-height: 1.5;
            color: #333;
            font-weight: 400;
            margin-bottom: 1em;
        }
    </style>

    <div class="news-detail">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <article>
                        <div class="news-header">
                            @if ($item->media->tipe_media == 'Gambar')
                                <img src="{{ $item->media->file_id }}" class="news-image" alt="Gambar Potensi">
                            @elseif($item->media->tipe_media == 'Youtube')
                                <a href="https://www.youtube.com/watch?v={{ $item->media->youtube_id }}" target="_blank">
                                    <img src="https://img.youtube.com/vi/{{ $item->media->youtube_id }}/hqdefault.jpg"
                                        alt="YouTube Thumbnail" class="news-image">
                                </a>
                            @endif
                            @if ($item->type == 'berita')
                                <div class="date-badge">
                                    <i class="far fa-calendar-alt"></i>
                                    {{ \Carbon\Carbon::parse($item->tanggal)->locale('id')->translatedFormat('d F Y') }}
                                </div>
                            @endif
                        </div>

                        <div class="news-meta">
                            <i class="far fa-user"></i> By Admin
                        </div>

                        <h1 class="news-title">{{ $item->judul }}</h1>

                        <div class="news-content">
                            {!! $item->type == 'berita' ? $item->konten : $item->deskripsi !!}
                        </div>

                        <a href="{{ url()->previous() }}" class="btn back-button">
                            <i class="fas fa-arrow-left mr-2"></i> Back to Dashboard
                        </a>
                    </article>
                </div>
            </div>
        </div>
    </div>
    <script>
        function copyToClipboard(url) {
            navigator.clipboard.writeText(url).then(function() {
                alert('URL copied to clipboard');
            }, function(err) {
                console.error('Could not copy text: ', err);
            });
        }
    </script>
@endsection
