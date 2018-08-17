
self.addEventListener('install', function(event) {
  console.log('[Service Worker] Installing Service Worker ...', event);
  event.waitUntil(
    caches.open('static')
      .then(function(cache) {
        console.log('[Service Worker] Precaching App Shell');
        cache.addAll([
          /*'/',
          '/index.php',
          '/src/js/app.js',
          '/src/js/feed.js',
          '/src/js/material.min.js',
          '/src/css/mostrai.css',
          '/src/images/main-image.jpg',
          '/src/css/material.indigo-pink.min.css'*/
        ]);
      })
  )
});

self.addEventListener('activate', function(event) {
  console.log('[Service Worker] Ativando ServiceWorker', event);
  return self.clients.claim();
});

self.addEventListener('fetch', function(event) {
  event.respondWith(
    caches.match(event.request)
      .then(function(response) {
        if (response) {
          return response;
        } else {
          return fetch(event.request);
        }
      })
  );
});

self.addEventListener('notificationclick', function(event) {
  var notification = event.notification;
  var action = event.action;

  console.log(notification);

  if (action === 'confirm') {
    console.log('Foi confirmado!');
    notification.close();
  } else {
    console.log(action);
    notification.close();
  }
});

self.addEventListener('notificationclose', function(event) {
  console.log('Notificação foi fechada', event);
});
