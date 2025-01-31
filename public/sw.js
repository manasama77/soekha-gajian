const CACHE_NAME = "hybonapp-v1.0.0";

const cacheAssets = ["/favicon.ico"];

self.addEventListener("beforeinstallprompt", (event) => {
	event.preventDefault();
	let deferredPrompt = event;

	// Show the install prompt
	const showInstallPromotion = () => {
		// Logic to show your custom install promotion UI
		console.log("Show install promotion");
	};

	// Listen for the user to trigger the install prompt
	document.getElementById("installButton").addEventListener("click", () => {
		deferredPrompt.prompt();
		deferredPrompt.userChoice.then((choiceResult) => {
			if (choiceResult.outcome === "accepted") {
				console.log("User accepted the install prompt");
			} else {
				console.log("User dismissed the install prompt");
			}
			deferredPrompt = null;
		});
	});

	showInstallPromotion();
});

self.addEventListener("install", (event) => {
	event.waitUntil(
		caches.open(CACHE_NAME).then((cache) => {
			cache.addAll(cacheAssets);
		})
	);
});

self.addEventListener("activate", (event) => {
	event.waitUntil(
		caches.keys().then((keyList) => {
			return Promise.all(
				keyList.map((key) => {
					if (key !== CACHE_NAME) {
						return caches.delete(key);
					}
				})
			);
		})
	);
});

self.addEventListener("fetch", (event) => {
	event.respondWith(
		caches.open(CACHE_NAME).then((cache) => {
			return cache.match(event.request).then((response) => {
				return response || fetch(event.request);
			});
		})
	);
});
