<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>こどもログ</title>
    {{-- Bootstrap CDN --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kaisei+Opti&family=Zen+Maru+Gothic&display=swap" rel="stylesheet">
     {{-- material icons --}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    {{-- ナビゲーションバー --}}
    <x-admin-navbar />

    {{-- パンくずリスト --}}
    <div class="container mt-3">
        @yield('breadcrumbs')
    </div>

    {{-- フラッシュメッセージ --}}
    @if(!View::hasSection('override-flash-messages'))
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endif

    {{-- メインコンテンツ --}}
    <main class="flex-fill">
        <div class="container mt-4">
            @yield('content')
        </div>
    </main>

    {{-- フッター --}}
    @include('components.admin-footer')

    <!-- Bootstrap JS, Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const flashMessages = document.querySelectorAll(".alert");
        
        flashMessages.forEach(function (message) {
            setTimeout(function () {
                message.style.transition = "opacity 1s ease-out, transform 1s ease-out, margin-bottom 0.5s ease-out";
                message.style.opacity = "0";
                message.style.transform = "translateY(-10px)";
                message.style.marginBottom = "0px"; 

                setTimeout(function () {
                    message.style.display = "none"; 
                }, 1000); 
            }, 3000); 
        });
    });
    </script>   
    @stack('scripts')   
</body>
</html>