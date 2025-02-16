@extends('layouts.app')

@section('override-flash-messages', true)

@section('content')
<div class="container auth-page">
    <div style="width: 100%; max-width: 400px; margin: 40px auto;">
        <h1 class="text-center mb-4">パスワード再設定</h1>
        <hr class="mb-4">

        <p class="text-center">
            ご登録中のメールアドレスを入力してください。<br>パスワード再設定用のURLをお送りします。
        </p>

        {{-- フラッシュメッセージ --}}
        <div class="flash-message-container">
            @if (session('status'))
                <div class="alert alert-success" role="alert">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            {{-- メッセージがない時も高さを維持するための空タグ --}}
            @if (!session('success') && !$errors->any())
                <span class="d-block">&nbsp;</span>
            @endif
        </div>

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="form-group mb-3">
                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="メールアドレス" autofocus>
            </div>

            <div class="form-group d-flex justify-content-center mb-4">
                <button type="submit" class="btn text-white shadow-sm w-100 kodomolog-btn">送信</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
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
@endpush
