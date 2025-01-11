<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ページ</title>
    <link rel="stylesheet" href="{{ asset('/css/styles.css') }}">
    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kaisei+Opti&family=Zen+Maru+Gothic&display=swap" rel="stylesheet">
</head>
<body>

    {{-- ナビゲーションバー --}}
    <x-admin-navbar />

    {{-- フラッシュメッセージ --}}
    @if (session('success'))
        <div class="alert alert-success"> 
            {{ session('success')}}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger"> 
            {{ session('error')}}
        </div>
    @elseif (session('warning'))
        <div class="alert alert-warning"> 
            {{ session('warning')}}
        </div>
    @elseif(session('info'))
        <div class="alert alert-info">
            {{ session('info') }}
        </div>
    @endif

    {{-- メインコンテンツ --}}
    <div class="container mt-4">
        @yield('content')
    </div>

    {{-- フッター --}}
    @include('components.footer')

    <!-- Bootstrap JS, Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>