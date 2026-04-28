if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        const swUrl = APP_URL.endsWith('/') ? APP_URL + 'sw.js' : APP_URL + '/sw.js';
        navigator.serviceWorker.register(swUrl)
            .then((reg) => {
                // Check for updates
                reg.onupdatefound = () => {
                    const installingWorker = reg.installing;
                    installingWorker.onstatechange = () => {
                        if (installingWorker.state === 'installed') {
                            if (navigator.serviceWorker.controller) {
                                // New content is available; please refresh.
                                console.log('New content available, refreshing...');
                                window.location.reload();
                            }
                        }
                    };
                };
            })
            .catch((error) => {
                console.log('Daily Vision SW failed:', error);
            });
    });
}
