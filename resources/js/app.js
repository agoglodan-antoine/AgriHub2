import Alpine from 'alpinejs';
import './echo';
import collapse from '@alpinejs/collapse';

Alpine.plugin(collapse);
window.Alpine = Alpine;
Alpine.start();

// ============================================
// FONCTIONS GLOBALES POUR LA MESSAGERIE
// ============================================

// ✅ Wrapper autour de fetch() qui ajoute automatiquement le header
// X-Socket-Id afin que broadcast(...)->toOthers() puisse correctement
// exclure le socket de l'expéditeur (sans ce header, fetch() natif ne
// transmet jamais cette info, contrairement à axios que Echo intercepte
// automatiquement).
window.fetchWithSocket = function(url, options = {}) {
    options.headers = {
        ...(options.headers || {}),
        'X-Socket-Id': (window.Echo && typeof window.Echo.socketId === 'function')
            ? (window.Echo.socketId() || '')
            : ''
    };
    return fetch(url, options);
};

// Mettre à jour le compteur de messages non lus
window.updateUnreadCount = function() {
    fetch('/messagerie/unread-count', {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) throw new Error('Erreur HTTP: ' + response.status);
        return response.json();
    })
    .then(data => {
        // Mettre à jour tous les badges
        const badges = document.querySelectorAll('.unread-badge');
        badges.forEach(badge => {
            if (data.unread > 0) {
                badge.textContent = data.unread > 9 ? '9+' : data.unread;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        });
        
        // Mettre à jour le badge du layout par ID
        const layoutBadge = document.getElementById('unread-badge-layout');
        if (layoutBadge) {
            if (data.unread > 0) {
                layoutBadge.textContent = data.unread > 9 ? '9+' : data.unread;
                layoutBadge.classList.remove('hidden');
            } else {
                layoutBadge.classList.add('hidden');
            }
        }
    })
    .catch(error => console.error('❌ Erreur compteur non lus:', error));
};

// ✅ FONCTION PRINCIPALE - avec point devant les événements
window.initConversationListener = function(conversationId) {
    if (!window.Echo) {
        console.warn('⚠️ Echo non initialisé, attente...');
        setTimeout(() => window.initConversationListener(conversationId), 1000);
        return;
    }
    
    // ✅ Vérifier si on écoute déjà ce canal
    const channelName = 'conversation.' + conversationId;
    if (window._activeListener === channelName) {
        console.log(`ℹ️ Déjà en écoute sur ${channelName}`);
        return;
    }
    
    console.log(`🔐 Tentative d'écoute du canal: ${channelName}`);
    
    // Quitter l'ancien canal
    if (window._activeListener) {
        try {
            window.Echo.leaveChannel(window._activeListener);
            console.log(`👋 Quitté le canal: ${window._activeListener}`);
        } catch (e) {
            console.warn('Erreur en quittant le canal:', e);
        }
    }
    
    window._activeListener = channelName;
    
    // ✅ CRUCIAL: LE POINT DEVANT LES ÉVÉNEMENTS
    const channel = window.Echo.channel(channelName);
    
    // ✅ Écoute de l'événement NewMessageEvent avec le point
    channel.listen('.NewMessageEvent', (e) => {
        console.log('📨 Nouveau message reçu via WebSocket:', e);
        
        const container = document.getElementById('messages-container');
        if (!container) {
            console.warn('⚠️ Container #messages-container non trouvé');
            return;
        }
        
        // Supprimer le message vide
        const emptyMsg = container.querySelector('#empty-message');
        if (emptyMsg) emptyMsg.remove();
        
        // Ajouter le message
        if (e.html) {
            container.insertAdjacentHTML('beforeend', e.html);
            container.scrollTop = container.scrollHeight;
            
            // Réinitialiser le menu contextuel
            if (typeof initContextMenu === 'function') {
                setTimeout(initContextMenu, 200);
            }
            
            console.log('✅ Message ajouté au DOM');
            
            // ✅ Mettre à jour le compteur immédiatement
            if (typeof updateUnreadCount === 'function') {
                setTimeout(updateUnreadCount, 300);
            }
        } else {
            console.warn('⚠️ Pas de HTML dans l\'événement:', e);
        }
    });
    
    // ✅ Écoute de MessageUpdatedEvent
    channel.listen('.MessageUpdatedEvent', (e) => {
        console.log('✏️ Message modifié:', e);
        const messageElement = document.querySelector(`.message-item[data-message-id="${e.message_id}"]`);
        if (messageElement) {
            const contentDiv = messageElement.querySelector('.message-content');
            if (contentDiv && e.new_content) {
                contentDiv.innerHTML = `<p class="text-sm message-text" id="message-text-${e.message_id}">${e.new_content}</p>`;
            }
            const dateSpan = messageElement.querySelector('.text-\\[10px\\]');
            if (dateSpan && !dateSpan.innerHTML.includes('(modifié)')) {
                dateSpan.innerHTML += ' <span class="text-[8px] opacity-40 ml-1">(modifié)</span>';
            }
        }
    });
    
    // ✅ Écoute de MessageDeletedEvent
    channel.listen('.MessageDeletedEvent', (e) => {
        console.log('🗑️ Message supprimé:', e);
        const messageElement = document.querySelector(`.message-item[data-message-id="${e.message_id}"]`);
        if (messageElement) {
            messageElement.style.opacity = '0';
            messageElement.style.transition = 'opacity 0.3s';
            setTimeout(() => {
                if (messageElement.parentNode) {
                    messageElement.remove();
                }
            }, 300);
        }
    });
    
    // ✅ Gestion des erreurs du canal
    channel.error((error) => {
        console.error('❌ Erreur sur le canal ' + channelName + ':', error);
    });
    
    // ✅ Écouter l'événement "subscribed" pour confirmer
    channel.subscribed(() => {
        console.log(`✅ Canal ${channelName} souscrit avec succès`);
    });
    
    console.log(`📡 Écoute active sur: ${channelName}`);
    
    // ✅ Vérification que l'écoute fonctionne
    setTimeout(() => {
        console.log(`🔍 Vérification: canal ${channelName} actif`);
    }, 2000);
};

// ✅ Fonction de test pour vérifier la diffusion
window.testBroadcast = function(conversationId, message) {
    if (!window.Echo) {
        console.error('❌ Echo non disponible');
        return;
    }
    
    console.log('🧪 Test de diffusion sur conversation.' + conversationId);
    
    // Écouter brièvement pour le test
    const channel = window.Echo.channel('conversation.' + conversationId);
    channel.listen('.NewMessageEvent', (e) => {
        console.log('🧪 TEST: Message reçu!', e);
    });
};

// ============================================
// INITIALISATION
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Initialisation de la messagerie');
    
    // Mettre à jour le compteur
    window.updateUnreadCount();
    
    // Vérifier si on est sur une page de conversation
    const container = document.getElementById('messages-container');
    if (container) {
        const conversationId = container.dataset.conversationId;
        
        if (conversationId) {
            console.log(`📌 Conversation ID trouvé: ${conversationId}`);
            window._currentConversationId = conversationId;
            
            // Attendre qu'Echo soit prêt
            if (window.Echo) {
                setTimeout(() => window.initConversationListener(conversationId), 500);
            } else {
                // Écouter l'initialisation d'Echo
                document.addEventListener('echo:initialized', function() {
                    console.log('✅ Echo initialisé, démarrage de l\'écoute');
                    setTimeout(() => window.initConversationListener(conversationId), 500);
                });
                
                // Fallback
                setTimeout(() => {
                    if (!window._activeListener) {
                        console.warn('⚠️ Echo non initialisé après 5s, tentative forcée');
                        import('./echo').then(() => {
                            setTimeout(() => window.initConversationListener(conversationId), 500);
                        });
                    }
                }, 5000);
            }
        } else {
            console.log('ℹ️ Pas d\'ID de conversation (page index)');
        }
    } else {
        console.log('ℹ️ Pas sur une page de conversation');
    }
});

// Mise à jour périodique du compteur (10 secondes)
setInterval(window.updateUnreadCount, 10000);

// Exposer les fonctions pour le débogage
window.testBroadcast = testBroadcast;