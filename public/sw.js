self.addEventListener('push', function(event) {
    console.log('Push event received:', event);
    const data = event.data ? event.data.json() : {};
    console.log('Notification Data:', data);

    self.registration.showNotification(data.title || '通知タイトル', {
        body: data.body || '通知内容',
        icon: '/icon.png',
    });
});
