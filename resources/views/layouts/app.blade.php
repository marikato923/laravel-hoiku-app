<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>こどもログ</title>
    {{-- CSRFトークン --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('/css/style.css') }}">
    {{-- Google Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kaisei+Opti&family=Zen+Maru+Gothic&display=swap" rel="stylesheet">
    {{-- material icons --}}
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&icon_names=kid_star" />
</head>


<body>
    {{-- ナビゲーションバー --}}
    <x-user-navbar />

    <div class="container mt-3">
        {{-- パンくずリスト --}}
        @yield('breadcrumbs')
    
        {{-- フラッシュメッセージ --}}
        @if (!View::hasSection('override-flash-messages'))
        <div class="flash-message-container">
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
        </div>
        @endif
    </div>

    {{-- メインコンテンツ --}}
    <main class="flex-fill">
        <div class="container mt-4">
            @yield('content')
        </div>
    </main>

    {{-- フッター --}}
    @include('components.user-footer')

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery  -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    {{-- app.js の読み込み --}}
    @vite(['resources/js/app.js', 'resources/css/app.css'])
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const flashMessageContainer = document.querySelector(".flash-message-container");

        if (flashMessageContainer) {
            setTimeout(function () {
                flashMessageContainer.classList.add("fade-out");

                setTimeout(function () {
                    flashMessageContainer.remove(); 
                }, 1000);
            }, 2000);
        }
    });
    </script>  
    @stack('scripts')
</body>
</html>

