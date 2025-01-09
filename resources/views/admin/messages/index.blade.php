@extends('layouts.admin')

@section('content')

    <div id="message-list">
        <!-- メッセージがここに表示されます -->
    </div>

    <!-- メッセージ送信フォーム -->
    <form id="message-form">
        @csrf
        <textarea id="message-input" rows="3" placeholder="メッセージを入力してください..."></textarea><br>
        <button type="submit">送信</button>
    </form>

    <script>
        // メッセージ送信フォームの送信処理
        document.getElementById('message-form').addEventListener('submit', function(event) {
            event.preventDefault();
            let message = document.getElementById('message-input').value;
            let receiverId = 1;  // 送信先のID（例: 管理者や他のユーザーIDをここに設定）

            // CSRFトークンの取得
            let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // メッセージ送信リクエスト
            fetch('/messages', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ receiver_id: receiverId, content: message })
            }).then(response => response.json())
              .then(data => {
                  console.log(data);
                  // メッセージを送信したらフォームをリセット
                  document.getElementById('message-input').value = '';
              });
        });

        // チャネル「chat」の購読
        Echo.channel('chat')
            .listen('message.sent', (event) => {
                // メッセージが送信されたときに実行される
                const messageElement = document.createElement('div');
                messageElement.classList.add('message');
                messageElement.textContent = `${event.user}: ${event.message}`;

                // メッセージを表示
                document.getElementById('message-list').appendChild(messageElement);
        });
    </script>
@endsection