import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

// ✅ Configuration Reverb
const config = {
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY || 'local',
    wsHost: import.meta.env.VITE_REVERB_HOST || 'localhost',
    wsPort: parseInt(import.meta.env.VITE_REVERB_PORT || 8080),
    wssPort: parseInt(import.meta.env.VITE_REVERB_PORT || 8080),
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME || 'http') === 'https',
    enabledTransports: ['ws', 'wss'],
    reconnectAfter: function (attempts) {
        return Math.min(1000 * Math.pow(2, attempts), 30000);
    },
    logToConsole: true
};

console.log('🔧 Configuration Echo:', config);

window.Echo = new Echo(config);

// ✅ Suivi de la connexion
window.Echo.connector.pusher.connection.bind('connecting', function() {
    console.log('🔌 WebSocket: Connexion en cours...');
});

window.Echo.connector.pusher.connection.bind('connected', function() {
    console.log('✅ WebSocket: Connecté avec succès');
    document.dispatchEvent(new CustomEvent('websocket:connected'));
});

window.Echo.connector.pusher.connection.bind('disconnected', function() {
    console.warn('⚠️ WebSocket: Déconnecté');
    document.dispatchEvent(new CustomEvent('websocket:disconnected'));
});

window.Echo.connector.pusher.connection.bind('failed', function(error) {
    console.error('❌ WebSocket: Échec de connexion', error);
    document.dispatchEvent(new CustomEvent('websocket:failed'));
});

window.Echo.connector.pusher.connection.bind('state_change', function(states) {
    console.log(`📡 WebSocket: ${states.previous} → ${states.current}`);
});

// ✅ Émettre l'événement d'initialisation
document.dispatchEvent(new CustomEvent('echo:initialized', { 
    detail: { echo: window.Echo } 
}));

console.log('✅ Echo initialisé avec Reverb');