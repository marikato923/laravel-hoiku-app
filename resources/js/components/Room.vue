<template>
    <div>
      <h1 class="room_title">キーワード:{{ room.keyword }}</h1>
      <div class="chat_area">
        <div v-for="message in messages" :key="message.id" :class="{'my_message': message.user_id == userId}">
          <div>{{ message.message }}</div>
          <div>{{ message.user_name }}</div>
        </div>
      </div>
      <form class="text_box" v-if="isRoomUser">
        <textarea rows="4" cols="30" v-model="messageData.message"></textarea>
        <button type="button" @click.prevent="send">送信</button>
      </form>
    </div>
  </template>
  
  <script>
  import { OK, CREATED, UNPROCESSABLE_ENTITY } from '../util'
  
  export default {
    props: {
      id: {
        type: String,
        required: true
      }
    },
    data () {
      return {
        room: null,
        messages: [],
        messageData: {
          user_id: this.$store.getters['auth/userId'],
          room_id: this.id, // ルームIDを設定
          message: ''
        }
      }
    },
    computed: {
      userId () {
        return this.$store.getters['auth/userId']
      },
      isRoomUser () {
        return this.room.room_users && this.room.room_users.find(user => user.id === this.userId);
      }
    },
    methods: {
      async fetchRoom () {
        const response = await axios.get(/api/room/${this.id})
        if (response.status !== OK) {
          this.$store.commit('setCode', response.status)
          return false
        }
  
        this.room = response.data
      },
      async fetchMessages () {
        const response = await axios.get(/api/message/${this.id})
        if (response.status !== OK) {
          this.$store.commit('setCode', response.status);
          return false;
        }
  
        this.messages = response.data
      },
      async send () {
        let response;
        if (this.isRoomUser) {
          response = await axios.post('api/messages/user', this.messageData);
        } else {
          response = await axios.post('api/messages/admin', this.messageData);
        }
  
        if (response.status !== CREATED) {
          this.$store.commit('setCode', response.status);
          return false;
        }
  
        this.messageData.message = '' // メッセージ送信後に入力フィールドをクリア
      },
      connectChannel() {
        Echo.channel(${this.id}).listen("MessageReceived", e => {
          this.messages.push(e.message)
        })
      }
    },
    mounted () {
      if (!this.userId) {
          this.$router.push('/login'); // 未ログイン時のリダイレクト
          return;
      }
      this.connectChannel();
    },
    watch: {
      $route: {
        async handler () {
          if (!this.room) {
          await this.fetchRoom();
          }
          await this.fetchMessages();
        },
        immediate: true,
      }
    }
  }
  </script>