<x-mail::message>

{{-- Google Fonts 読み込み（ロゴにのみ適用） --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kaisei+Opti&family=Zen+Maru+Gothic&display=swap" rel="stylesheet">

{{-- スタイル設定 --}}
<style>
    /* メール全体のデフォルトフォント */
    body {
        font-family: 'Arial', sans-serif !important;
        background-color: #FFF8E8 !important;
        color: #4D4D4D !important;
        margin: 0;
        padding: 20px;
    }
    /* ロゴ部分にのみ適用 */
    .logo {
        font-family: 'Zen Maru Gothic', serif !important;
        font-weight: 700 !important;
        font-size: 2em !important;
        color: rgb(255, 175, 175) !important;
        text-decoration: none !important;
        letter-spacing: -0.05em !important;
        transition: color 0.3s ease !important;
    }
    /* ヘッダー */
    .header {
        text-align: center;
        padding: 10px 0;
    }
    /* アクションボタン */
    .button {
        background-color: rgb(255, 175, 175) !important;
        border: none !important;
        font-weight: bold !important;
        padding: 12px;
        border-radius: 8px;
        display: inline-block;
        text-align: center;
        color: white !important;
    }
    /* フッター */
    .footer {
        text-align: center;
        font-size: 12px;
        color: #6C757D;
        margin-top: 20px;
    }
</style>

@php
    // 変数のデフォルト値設定（未定義エラー回避）
    $level = $level ?? 'info';
    $greeting = $greeting ?? __('【重要】メール認証のお願い');
    $actionText = $actionText ?? __('こちらをクリック');
    $actionUrl = $actionUrl ?? config('app.url');
    $displayableActionUrl = $displayableActionUrl ?? $actionUrl;
@endphp

{{-- ヘッダー（ロゴ表示） --}}
<div class="header">
    <a href="{{ config('app.url') }}" class="logo">こどもログ</a>
</div>

{{-- 挨拶文 --}}
@if (! empty($greeting))
# {{ $greeting }}
@endif

{{-- 本文（説明部分） --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- 認証ボタン（修正済み） --}}
<x-mail::button :url="$actionUrl" :color="$level === 'error' ? 'red' : 'pink'" class="button">
    📩 {{ $actionText }}
</x-mail::button>

{{-- 追加メッセージ --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- 署名 --}}
@if (! empty($salutation))
{{ $salutation }}
@else
@lang('何かご不明な点がございましたら、お気軽にお問い合わせください。')  
@lang('今後とも「こどもログ」をよろしくお願いいたします。')  

**「こどもログ」**
@endif

{{-- サブコピー（URLをコピーする場合） --}}
<x-slot:subcopy>
@lang(
    "もしボタンをクリックできない場合は、以下のURLをコピーしてブラウザのアドレスバーに貼り付けてください。",
    ['actionText' => $actionText]
)  
<span class="break-all">[{{ $displayableActionUrl }}]({{ $actionUrl }})</span>
</x-slot:subcopy>

{{-- フッター --}}
<div class="footer">
    &copy; {{ date('Y') }} こどもログ. All rights reserved.
</div>

</x-mail::message>
