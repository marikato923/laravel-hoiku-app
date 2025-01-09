import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

import Pusher from 'pusher-js';

Pusher.logToConsole = true;

const pusher = new Pusher('your-app-key', {
    cluster: 'your-app-cluster',
});

const channel = pusher.subscribe('chat');
channel.bind('message.sent', function(data) {
    console.log('Message received:', data);
    alert(`${data.user}: ${data.message}`);
});