<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>お迎えリマインダー</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
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
        }
        .title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
        }
        .time {
            font-size: 24px;
            font-weight: bold;
            color: #d9534f;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #666;
        }
    </style>
</head>
<body>

    <div class="container">
        <p class="title">お迎えリマインダー</p>
        <p>{{ $child->last_name }} {{ $child->first_name }} さんのお迎え予定時間は</p>
        <p class="time">{{ \Carbon\Carbon::parse($pickupTime)->format('H時i分') ?? 'お迎え時間未設定' }}</p>
        <p>お気をつけてお越しください。</p>

        <div class="footer">
            <p>こどもログ</p>
            <p>このメールは自動送信されています。</p>
        </div>
    </div>

</body>
</html>
