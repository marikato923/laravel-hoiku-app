<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>【こどもログ】お迎えリマインダー</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Kaisei+Opti&family=Zen+Maru+Gothic&display=swap" rel="stylesheet">
    <style>
        /* 全体のスタイル */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #FFF8E8; /* 優しいクリーム色 */
            text-align: center;
            padding: 20px;
        }
        .container {
            background: white;
            max-width: 500px;
            margin: auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        /* ロゴ */
        .logo {
            font-family: 'Zen Maru Gothic', serif;
            font-weight: 700;
            font-size: 2em;
            color: rgb(255, 175, 175);
            text-decoration: none;
            letter-spacing: -0.05em;
        }
        /* タイトル */
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 15px;
        }
        /* 時間表示 */
        .time {
            font-size: 24px;
            font-weight: bold;
            color: #d9534f;
            margin-top: 10px;
            margin-bottom: 15px;
        }
        /* ボタン */
        .button {
            display: inline-block;
            background-color: rgb(255, 175, 175);
            color: white;
            padding: 12px 20px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 15px;
        }
        .button:hover {
            background-color: rgb(255, 140, 140);
        }
        /* フッター */
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>

    <div class="container">
        <!-- ロゴ -->
        <a href="{{ config('app.url') }}" class="logo">こどもログ</a>

        <p class="title">【お迎えリマインダー】</p>
        <p>{{ $child->last_name }} {{ $child->first_name }} さんのお迎え予定時間は</p>
        <p class="time">
            {{ \Carbon\Carbon::parse($pickupTime)->format('H時i分') ?? 'お迎え時間未設定' }}
        </p>
        <p>お気をつけてお越しください。</p>

        <div class="footer">
            <p>こどもログ</p>
            <p>このメールは自動送信されています。</p>
        </div>
    </div>

</body>
</html>
