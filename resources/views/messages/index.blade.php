@extends('layouts.app')

@section('content')

    <div id="message-list">
        <!-- メッセージがここに表示されます -->
    </div>

    <!-- メッセージ送信フォーム -->
    <form id="message-form" action="{{ route('messages.store') }}" method="POST">
        @csrf
        <textarea id="message-input" name="content" rows="3" placeholder="メッセージを入力してください..."></textarea><br>
        
        <!-- 送信先管理者を選択 -->
        <label for="receiver-id">送信先:</label>
        <select id="receiver-id" name="receiver_id">
            @foreach ($admins as $admin)
                <option value="{{ $admin->id }}">{{ $admin->last_name }} {{ $admin->first_name }}</option>
            @endforeach
        </select><br>
        
        <button type="submit">送信</button>
    </form>

    <script>
        // メッセージ送信フォームの送信処理
        document.getElementById('message-form').addEventListener('submit', function(event) {
            event.preventDefault();
            let message = document.getElementById('message-input').value;
            let receiverId = document.getElementById('receiver-id').value; // ユーザーが選択した管理者ID

            // メッセージ内容のバリデーション（空でないことを確認）
            if (message.trim() === "") {
                alert("メッセージは空にできません！");
                return;
            }

            // CSRFトークンの取得
            let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // メッセージ送信リクエスト
            fetch("{{ route('messages.store') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({ receiver_id: receiverId, content: message })
            }).then(response => response.json())
              .then(data => {
                  if (data.message) {
                      console.log(data.message);
                      // メッセージを送信したらフォームをリセット
                      document.getElementById('message-input').value = '';
                  } else {
                      alert("メッセージ送信に失敗しました。");
                  }
              })
              .catch(error => {
                  console.error('Error:', error);
                  alert("メッセージ送信中にエラーが発生しました。");
              });
        });

        // チャネル「chat」の購読
        Echo.channel('chat')
            .listen('message.sent', (event) => {
                // メッセージが送信されたときに実行される
                const messageElement = document.createElement('div');
                messageElement.classList.add('message');
                messageElement.textContent = `${event.user}: ${event.message}`;

                // 新しいメッセージを先頭に追加
                const messageList = document.getElementById('message-list');
                messageList.insertBefore(messageElement, messageList.firstChild);
        });
    </script>
@endsection