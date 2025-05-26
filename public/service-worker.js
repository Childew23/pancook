// Nom de la cache
const CACHE_NAME = 'pancook-cache-v1';

// Liste des ressources à mettre en cache lors de l'installation
const urlsToCache = [
  '.',
  '/',
  '/pancook/css/styles.css',
  '/app.js',
  '/pancook/assets/',
  '/pancook/assets/favicon/android-chrome-192x192.png',
  '/pancook/assets/favicon/android-chrome-512x512.png',
  '/pancook/assets/favicon/favicon.ico'
];

// Événement d'installation : mise en cache des ressources
self.addEventListener('install', event => {
  console.log('[Service Worker] Installation...');
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(cache => {
        console.log('[Service Worker] Mise en cache des fichiers');
        return cache.addAll(urlsToCache);
      })
      .catch(err => console.error('[Service Worker] Erreur lors du cache', err))
  );
});

// Événement d'activation : nettoyage des anciennes caches
self.addEventListener('activate', event => {
  console.log('[Service Worker] Activation...');
  event.waitUntil(
    caches.keys().then(cacheNames => {
      return Promise.all(
        cacheNames.map(name => {
          if (name !== CACHE_NAME) {
            console.log('[Service Worker] Suppression de la cache', name);
            return caches.delete(name);
          }
        })
      );
    })
  );
});

// Événement fetch : interception des requêtes réseau
self.addEventListener('fetch', event => {
  console.log('[Service Worker] Interception de la requête:', event.request.url);
  event.respondWith(
    caches.match(event.request)
      .then(response => {
        // Renvoie la réponse en cache si elle existe, sinon la requête réseau
        return response || fetch(event.request);
      })
  );
});
