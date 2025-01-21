@extends('layouts.admin')

@section('content')
    <div id="app" data-room-id="{{ $roomId }}">
        <h1 class="room-title">ルームID: {{ $roomId }}</h1>

        <div class="chat-area">
            <div v-for="message in messages" :key="message.id" :class="{'admin-message': message.admin_id === adminId}">
                <div class="message-text">@{{ message.message }}</div>
                <div class="message-sender">@{{ message.user_name || '管理者' }}</div>
            </div>
        </div>

        <form class="text-box">
            <textarea v-model="messageData.message" rows="4" cols="30" placeholder="メッセージを入力..."></textarea>
            <button type="button" @click.prevent="send">送信</button>
        </form>
    </div>
@endsection