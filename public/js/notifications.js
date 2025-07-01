class NotificationHandler {
    constructor() {
        this.audioElements = {};
        this.initServiceWorker();
        this.deferPermissionRequest(); // ⬅️ New: smarter permission strategy
        this.startMobileMonitoring();
    }

    initServiceWorker() {
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js')
                .then(reg => {
                    console.log('✅ Service Worker registered');
                    reg.sync.register('attendance-reminders').catch(console.warn);
                });
        }
    }

    deferPermissionRequest() {
        // Only request if not already granted or denied
        if (Notification.permission === 'default') {
            document.addEventListener('click', () => {
                Notification.requestPermission().then(permission => {
                    console.log('🔐 Notification permission:', permission);
                });
            }, { once: true });
        }
    }

    startMobileMonitoring() {
        if ('serviceWorker' in navigator && 'periodicSync' in Registration.prototype) {
            navigator.serviceWorker.ready.then(reg => {
                reg.periodicSync.register('check-attendance', {
                    minInterval: 60 * 60 * 1000 // 1 hour
                }).catch(console.error);

                this.checkAttendanceTimeManually(); // immediate check
            });
        } else {
            console.warn('⚠️ periodicSync not supported. Falling back to manual polling.');
            this.checkNotifications();
            setInterval(() => this.checkNotifications(), 30000);
        }
    }

    checkAttendanceTimeManually() {
        const now = new Date();
        const hours = now.getHours();

        if ((hours >= 8 && hours < 12) || (hours >= 14 && hours < 18)) {
            if (navigator.serviceWorker.controller) {
                navigator.serviceWorker.controller.postMessage({
                    type: 'check-attendance-now'
                });
            } else {
                this.checkNotifications();
            }
        }
    }

    async checkNotifications() {
        try {
            const response = await fetch('/api/notifications/unread');
            const notifications = await response.json();
            notifications.forEach(notification => this.handleNotification(notification));
        } catch (error) {
            console.error('🚫 Notification polling failed:', error);
        }
    }

    handleNotification(notification) {
        this.showVisualNotification(notification);
        this.playAudioNotification(notification);
        this.vibrateDevice(notification);
        this.markAsRead(notification.id);
    }

    showVisualNotification(notification) {
        if (Notification.permission === 'granted') {
            const notif = new Notification(notification.data.title, {
                body: notification.data.message,
                icon: notification.data.icon,
                vibrate: notification.data.vibration_pattern,
                data: { url: notification.data.action_url }
            });

            notif.onclick = () => {
                window.focus();
                window.location.href = notification.data.action_url;
            };
        } else {
            this.showInAppAlert(notification);
        }
    }

    playAudioNotification(notification) {
        if (notification.data.voice_enabled) {
            const soundFile = notification.data.sound_file;
            if (!this.audioElements[soundFile]) {
                this.audioElements[soundFile] = new Audio(`/sounds/${soundFile}`);
                this.audioElements[soundFile].volume = 0.8;
            }

            this.audioElements[soundFile].play().catch(e => {
                console.error('🎧 Audio playback failed:', e);
            });
        }
    }

    vibrateDevice(notification) {
        if ('vibrate' in navigator) {
            navigator.vibrate(notification.data.vibration_pattern);
        }
    }

    async markAsRead(notificationId) {
        try {
            await fetch('/api/notifications/mark-read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ id: notificationId })
            });
        } catch (error) {
            console.error('❌ Failed to mark notification as read:', error);
        }
    }

    showInAppAlert(notification) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'fixed bottom-4 right-4 bg-white p-4 shadow-lg rounded-lg max-w-xs z-50';
        alertDiv.innerHTML = `
            <h3 class="font-bold">${notification.data.title}</h3>
            <p class="my-2">${notification.data.message}</p>
            <button class="text-blue-500 underline" onclick="window.location.href='${notification.data.action_url}'">
                Mark Attendance
            </button>
        `;
        document.body.appendChild(alertDiv);
        setTimeout(() => alertDiv.remove(), 7000);
    }
}

// ✅ Initialize after DOM load
document.addEventListener('DOMContentLoaded', () => {
    new NotificationHandler();
});
