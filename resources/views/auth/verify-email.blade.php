@extends('layouts.app')

{{-- グローバルのフラッシュメッセージを無効化 --}}
@section('override-flash-messages', true)

@section('content')
<div class="container auth-page">
    <div style="width: 100%; max-width: 400px; margin: 40px auto;">
        <h1 class="mb-4 text-center">会員登録を完了してください</h1>
        <hr class="mb-4">

        {{-- フラッシュメッセージ --}}
        <div class="flash-message-container">
            @if (session('resent'))
                <div class="alert alert-success text-center" role="alert">
                    <p class="mb-0">新しいURLをあなたのメールアドレスに送信しました。</p>
                </div>
            @endif
            {{-- メッセージがない時も高さを維持するための空タグ --}}
            @if (!session('success') && !$errors->any())
                <span class="d-block">&nbsp;</span>
            @endif
        </div>

        <p class="text-center">
            現在、仮会員の状態です。メールに記載されている<br>「メールアドレス確認」ボタンをクリックして<br>会員登録の手続きを完了してください。
        </p>

        <p class="text-center">
            もしメールが届いていない場合は、以下のボタンをクリックしてメールを再送信してください。
        </p>

        <form class="text-center" method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn text-white kodomolog-btn shadow-sm">確認メールを再送信する</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const flashMessageContainer = document.querySelector(".flash-message-container");

    if (flashMessageContainer && flashMessageContainer.innerText.trim() === "") {
        flashMessageContainer.style.minHeight = "0";
        flashMessageContainer.style.marginBottom = "0";
    } else {
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
