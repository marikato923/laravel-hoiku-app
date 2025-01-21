@extends('layouts.app')

@section('content')
<body>
    <div id="app" data-room-id="{{ $roomId }}" data-user-id="{{ $userId }}" data-is-room-user="{{ $isRoomUser }}">
        <h1 class="room-title">ルームID: {{ $roomId }}</h1>

        <div class="chat-area">
            <div v-for="message in messages" :key="message.id" :class="{'my-message': message.user_id === userId}">
                <div class="message-text">@{{ message.message }}</div>
                <div class="message-sender">@{{ message.user_name }}</div>
            </div>
        </div>

        <form class="text-box" v-if="isRoomUser">
            <textarea v-model="messageData.message" rows="4" cols="30" placeholder="メッセージを入力..."></textarea>
            <button type="button" @click.prevent="send">送信</button>
        </form>
    </div>
</body>
@endsection