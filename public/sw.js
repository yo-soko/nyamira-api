const CACHE_NAME = 'attendance-v1';
const SOUND_FILES = [
  '/sounds/morning_initial.mp3',
  '/sounds/morning_reminder.mp3',
  '/sounds/afternoon_initial.mp3',
  '/sounds/afternoon_reminder.mp3'
];

// 🔧 Cache essential sound files on install
self.addEventListener('install', (event) => {
  console.log('[ServiceWorker] Installing and caching sounds...');
  event.waitUntil(
    caches.open(CACHE_NAME).then((cache) => cache.addAll(SOUND_FILES))
  );
  self.skipWaiting();
});

// ♻️ Clean up old caches
self.addEventListener('activate', (event) => {
  console.log('[ServiceWorker] Activated');
  event.waitUntil(
    caches.keys().then(keys =>
      Promise.all(
        keys.filter(key => key !== CACHE_NAME)
            .map(key => caches.delete(key))
      )
    )
  );
  self.clients.claim();
});

// 🎧 Serve cached sounds
self.addEventListener('fetch', (event) => {
  if (event.request.url.includes('/sounds/')) {
    event.respondWith(
      caches.match(event.request).then(response =>
        response || fetch(event.request)
      )
    );
  }
});

// 🔁 Background Sync: periodic check
self.addEventListener('sync', (event) => {
  if (event.tag === 'check-attendance') {
    console.log('[ServiceWorker] Background Sync: check-attendance');
    event.waitUntil(checkAttendanceTime());
  }
});

// 📩 Handle message from NotificationHandler
self.addEventListener('message', (event) => {
  if (event.data?.type === 'check-attendance-now') {
    console.log('[ServiceWorker] Manual trigger: check-attendance-now');
    event.waitUntil(checkAttendanceTime());
  }
});

// 🕒 Logic for time-based check and reminder
async function checkAttendanceTime() {
  const now = new Date();
  const hours = now.getHours();
  const minutes = now.getMinutes();

  if (hours >= 8 && hours < 12 && minutes % 10 === 0) {
    await triggerNotification('morning');
  } else if (hours >= 14 && hours < 18 && minutes % 10 === 0) {
    await triggerNotification('afternoon');
  }
}

// 🔔 Show notification and play associated sound
async function triggerNotification(session) {
  const type = Date.now() % 2 === 0 ? 'initial' : 'reminder';
  const soundFile = `/sounds/${session}_${type}.mp3`;

  try {
    const audio = new Audio(soundFile);
    audio.volume = 0.8;
    await audio.play();
  } catch (err) {
    console.error('[ServiceWorker] Failed to play sound:', err);
  }

  self.registration.showNotification('Attendance Reminder', {
    body: `Please mark ${session} attendance now.`,
    icon: '/icons/icon-192.png',
    vibrate: [300, 100, 300],
    tag: `attendance-${session}`,
    renotify: true,
    data: {
      url: `/attendance/mark?session=${session}`
    }
  });
}

// 🔄 Focus or open tab when notification clicked
self.addEventListener('notificationclick', (event) => {
  event.notification.close();
  event.waitUntil(
    clients.matchAll({ type: 'window', includeUncontrolled: true }).then(clientList => {
      for (const client of clientList) {
        if (client.url.includes('/attendance/mark')) {
          return client.focus();
        }
      }
      return clients.openWindow(event.notification.data.url);
    })
  );
});
