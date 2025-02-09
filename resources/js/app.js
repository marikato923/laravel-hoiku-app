import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// 通知の実装 
document.addEventListener('DOMContentLoaded', async function () {
    console.log("DOM fully loaded and parsed");

    if ('serviceWorker' in navigator && 'PushManager' in window) {
        console.log("Service Worker and PushManager are supported");

        try {
            const registration = await navigator.serviceWorker.register('/sw.js');
            console.log("Service Worker registered:", registration);

            const permission = await Notification.requestPermission();
            console.log("Notification permission status:", permission);

            if (permission === 'granted') {
                console.log("Permission granted, subscribing to push notifications...");
                await subscribeUserToPush(registration);
            } else {
                console.warn("Notification permission denied");
            }
        } catch (error) {
            console.error("Error registering service worker or getting notification permission:", error);
        }
    } else {
        console.warn("Push messaging is not supported in this browser.");
    }
});

async function subscribeUserToPush(registration) {
    console.log("Attempting to subscribe user to push notifications...");

    try {
        const subscription = await registration.pushManager.subscribe({
            userVisibleOnly: true,
            applicationServerKey: "BEIDYfJMwbsFjfJPm3eH8AULTah8lwTBbEn9CaehEsFAG4UdHHSovfsGjGQwrOZLSB6pVqoYveQMY2jHpdXx3gs"
        });

        console.log("Push Subscription obtained:", subscription);

        // CSRF トークンを取得
        const csrfToken = document.head.querySelector('meta[name="csrf-token"]').content;

        if (!csrfToken) {
            console.error("CSRF token not found in meta tag.");
            return;
        }

        // Laravelに登録
        const response = await fetch('/user/subscribe', {
            method: 'POST',
            body: JSON.stringify(subscription),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            }
        });

        const responseData = await response.json();
        console.log("Push Subscription response:", responseData);

        if (!response.ok) {
            console.error("Failed to subscribe to push notifications:", responseData);
        }
    } catch (error) {
        console.error("Error subscribing to push notifications:", error);
    }
}
