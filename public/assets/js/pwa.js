if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        const swUrl = APP_URL.endsWith('/') ? APP_URL + 'sw.js' : APP_URL + '/sw.js';
        navigator.serviceWorker.register(swUrl)
            .then((registration) => {
                console.log('Daily Vision SW registered:', registration);
            })
            .catch((error) => {
                console.log('Daily Vision SW failed:', error);
            });
    });
}
