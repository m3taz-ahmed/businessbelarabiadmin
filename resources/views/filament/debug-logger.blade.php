<script>
document.addEventListener('livewire:init', () => {
    // #region agent log
    const sendDebugLog = (payload) => {
        fetch('http://127.0.0.1:7242/ingest/da4ad83b-39db-4018-9858-92321e5ab041', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                sessionId: 'debug-session',
                runId: 'pre-fix',
                timestamp: Date.now(),
                ...payload,
            }),
        }).catch(() => {});
    };
    // #endregion

    // #region agent log
    sendDebugLog({
        hypothesisId: 'H2',
        location: 'filament/debug-logger.blade.php:livewire-presence',
        message: 'livewire presence check',
        data: {
            hasLivewire: !!window.Livewire,
            hasAlpine: !!window.Alpine,
            scripts: Array.from(document.querySelectorAll('script[src]'))
                .filter((s) => s.src.includes('livewire') || s.src.includes('filament'))
                .map((s) => s.src.slice(-80)),
        },
    });
    // #endregion

    // #region agent log
    setTimeout(() => {
        sendDebugLog({
            hypothesisId: 'H3',
            location: 'filament/debug-logger.blade.php:livewire-timeout-3s',
            message: 'livewire status after 3s',
            data: {
                hasLivewire: !!window.Livewire,
                hasAlpine: !!window.Alpine,
                livewireStarted: !!window.Livewire?.started,
            },
        });
    }, 3000);
    // #endregion

    // #region agent log
    sendDebugLog({
        hypothesisId: 'H2',
        location: 'filament/debug-logger.blade.php:livewire-init',
        message: 'livewire initialized',
        data: { url: window.location.pathname },
    });
    // #endregion

    let sentLogged = false;
    let processedLogged = false;

    Livewire.hook('message.sent', (message, component) => {
        if (sentLogged) return;
        sentLogged = true;

        // #region agent log
        sendDebugLog({
            hypothesisId: 'H2',
            location: 'filament/debug-logger.blade.php:message.sent',
            message: 'first livewire message sent',
            data: {
                url: window.location.pathname,
                component: component?.name ?? component?.id ?? null,
                fingerprint: message?.fingerprint?.name ?? null,
                updatesCount: message?.updateQueue?.length ?? null,
            },
        });
        // #endregion
    });

    Livewire.hook('message.processed', (message, component) => {
        if (processedLogged) return;
        processedLogged = true;

        // #region agent log
        sendDebugLog({
            hypothesisId: 'H2',
            location: 'filament/debug-logger.blade.php:message.processed',
            message: 'first livewire message processed',
            data: {
                url: window.location.pathname,
                component: component?.name ?? component?.id ?? null,
                updatesCount: message?.updateQueue?.length ?? null,
            },
        });
        // #endregion
    });

    Livewire.hook('message.failed', (message, component) => {
        // #region agent log
        sendDebugLog({
            hypothesisId: 'H3',
            location: 'filament/debug-logger.blade.php:message.failed',
            message: 'livewire message failed',
            data: {
                url: window.location.pathname,
                component: component?.name ?? component?.id ?? null,
                updatesCount: message?.updateQueue?.length ?? null,
            },
        });
        // #endregion
    });

    if (typeof Livewire.onError === 'function') {
        Livewire.onError((error) => {
            // #region agent log
            sendDebugLog({
                hypothesisId: 'H3',
                location: 'filament/debug-logger.blade.php:onError',
                message: 'livewire encountered error',
                data: {
                    url: window.location.pathname,
                    message: error?.message ?? null,
                    stack: (error?.stack || '').split('\n').slice(0, 3),
                },
            });
            // #endregion
            return false;
        });
    } else {
        // #region agent log
        sendDebugLog({
            hypothesisId: 'H3',
            location: 'filament/debug-logger.blade.php:onError-missing',
            message: 'Livewire.onError not available',
            data: {
                hasLivewire: !!window.Livewire,
                livewireKeys: Object.keys(window.Livewire || {}),
            },
        });
        // #endregion
    }
});

window.addEventListener('error', (event) => {
    // #region agent log
    fetch('http://127.0.0.1:7242/ingest/da4ad83b-39db-4018-9858-92321e5ab041', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            sessionId: 'debug-session',
            runId: 'pre-fix',
            timestamp: Date.now(),
            hypothesisId: 'H3',
            location: 'filament/debug-logger.blade.php:window-error',
            message: 'window error captured',
            data: {
                message: event?.message ?? null,
                source: event?.filename ?? null,
                lineno: event?.lineno ?? null,
                colno: event?.colno ?? null,
            },
        }),
    }).catch(() => {});
    // #endregion
}, true);
</script>

