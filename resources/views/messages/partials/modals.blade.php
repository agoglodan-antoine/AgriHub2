<!-- Modal de prévisualisation d'image -->
<div id="imagePreviewModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm"
     onclick="if(event.target === this) closeImagePreview()">
    <div class="relative max-w-4xl w-full mx-4">
        <button onclick="closeImagePreview()" 
                class="absolute -top-12 right-0 text-white hover:text-gray-300 transition text-2xl">
            <i class="fas fa-times"></i>
        </button>
        <div class="bg-white rounded-xl overflow-hidden shadow-2xl">
            <div class="p-3 border-b" style="border-color: var(--color-nav-border);">
                <h3 class="text-sm font-semibold" id="previewTitle" style="color: var(--color-nav-text);">Aperçu</h3>
            </div>
            <div class="p-4 flex items-center justify-center max-h-[70vh]">
                <img id="previewImage" src="" alt="Aperçu" class="max-w-full max-h-[60vh] object-contain">
            </div>
            <div class="p-3 border-t flex justify-end" style="border-color: var(--color-nav-border);">
                <a id="previewDownloadLink" href="#" download 
                   class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 text-white"
                   style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));"
                   onmouseover="this.style.background='linear-gradient(135deg, var(--color-primary-dark), var(--color-secondary))'"
                   onmouseout="this.style.background='linear-gradient(135deg, var(--color-primary), var(--color-primary-dark))'">
                    <i class="fas fa-download mr-2"></i>
                    Télécharger
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'enregistrement audio -->
<div id="audioRecorderModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm">
    <div class="relative max-w-md w-full mx-4">
        <div class="bg-white rounded-xl shadow-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold" style="color: var(--color-nav-text);">
                    <i class="fas fa-microphone mr-2" style="color: var(--color-primary);"></i>
                    Enregistrement audio
                </h3>
                <button onclick="closeAudioRecorder()" class="text-gray-500 hover:text-gray-700 transition text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div class="space-y-4">
                <div class="relative">
                    <div class="h-2 rounded-full overflow-hidden" style="background-color: var(--color-secondary-light);">
                        <div id="audioProgress" class="h-full rounded-full transition-all duration-300" 
                             style="width: 0%; background: linear-gradient(90deg, var(--color-primary), var(--color-primary-dark));"></div>
                    </div>
                    <div class="flex justify-between text-xs mt-1" style="color: var(--color-nav-text); opacity: 0.6;">
                        <span id="audioTimeCurrent">00:00</span>
                        <span id="audioTimeMax">00:30</span>
                    </div>
                </div>
                
                <div class="flex items-center justify-center gap-1 h-16">
                    <div class="audio-bar" style="height: 10px;"></div>
                    <div class="audio-bar" style="height: 15px;"></div>
                    <div class="audio-bar" style="height: 25px;"></div>
                    <div class="audio-bar" style="height: 40px;"></div>
                    <div class="audio-bar" style="height: 30px;"></div>
                    <div class="audio-bar" style="height: 20px;"></div>
                    <div class="audio-bar" style="height: 12px;"></div>
                    <div class="audio-bar" style="height: 8px;"></div>
                </div>
                
                <div class="flex justify-center gap-4">
                    <button id="audioStartStop" onclick="toggleAudioRecording()"
                            class="w-16 h-16 rounded-full flex items-center justify-center text-white transition-all duration-300"
                            style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                        <i class="fas fa-microphone text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal d'enregistrement vidéo -->
<div id="videoRecorderModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/80 backdrop-blur-sm">
    <div class="relative max-w-2xl w-full mx-4">
        <div class="bg-white rounded-xl shadow-2xl overflow-hidden">
            <div class="p-4 border-b" style="border-color: var(--color-nav-border);">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold" style="color: var(--color-nav-text);">
                        <i class="fas fa-video mr-2" style="color: var(--color-primary);"></i>
                        Enregistrement vidéo
                    </h3>
                    <button onclick="closeVideoRecorder()" class="text-gray-500 hover:text-gray-700 transition text-xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            
            <div class="p-4 space-y-4">
                <div class="relative rounded-lg overflow-hidden bg-black aspect-video">
                    <video id="videoPreview" autoplay muted playsinline class="w-full h-full object-cover"></video>
                    <div id="videoRecordingOverlay" class="absolute inset-0 flex items-center justify-center bg-black/30 hidden">
                        <div class="text-white text-center">
                            <i class="fas fa-circle text-red-500 animate-pulse text-3xl"></i>
                            <p class="mt-2 text-sm">Enregistrement en cours...</p>
                        </div>
                    </div>
                </div>
                
                <div class="relative">
                    <div class="h-2 rounded-full overflow-hidden" style="background-color: var(--color-secondary-light);">
                        <div id="videoProgress" class="h-full rounded-full transition-all duration-300" 
                             style="width: 0%; background: linear-gradient(90deg, var(--color-primary), var(--color-primary-dark));"></div>
                    </div>
                    <div class="flex justify-between text-xs mt-1" style="color: var(--color-nav-text); opacity: 0.6;">
                        <span id="videoTimeCurrent">00:00</span>
                        <span id="videoTimeMax">01:00</span>
                    </div>
                </div>
                
                <div class="flex justify-center gap-4">
                    <button id="videoStartStop" onclick="toggleVideoRecording()"
                            class="w-16 h-16 rounded-full flex items-center justify-center text-white transition-all duration-300"
                            style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                        <i class="fas fa-video text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL DE COMMANDE -->
<!-- ============================================ -->
<div id="commandeModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm"
     onclick="if(event.target === this) fermerFormulaireCommande()">
    <div class="relative max-w-md w-full mx-4">
        <div class="bg-white rounded-xl shadow-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold" style="color: var(--color-nav-text);">
                    <i class="fas fa-shopping-cart mr-2" style="color: var(--color-primary);"></i>
                    Passer commande
                </h3>
                <button onclick="fermerFormulaireCommande()" class="text-gray-500 hover:text-gray-700 transition text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="commandeModalContent">
                <!-- Contenu chargé dynamiquement via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL DE PAIEMENT -->
<!-- ============================================ -->
<div id="paiementModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm"
     onclick="if(event.target === this) fermerPaiementModal()">
    <div class="relative max-w-md w-full mx-4">
        <div class="bg-white rounded-xl shadow-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold" style="color: var(--color-nav-text);">
                    <i class="fas fa-credit-card mr-2" style="color: var(--color-primary);"></i>
                    Confirmer le paiement
                </h3>
                <button onclick="fermerPaiementModal()" class="text-gray-500 hover:text-gray-700 transition text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="paiementModalContent">
                <!-- Contenu chargé dynamiquement via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL D'AJUSTEMENT DU PAIEMENT -->
<!-- ============================================ -->
<div id="ajustementModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm"
     onclick="if(event.target === this) fermerAjustementModal()">
    <div class="relative max-w-md w-full mx-4">
        <div class="bg-white rounded-xl shadow-2xl p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold" style="color: var(--color-nav-text);">
                    <i class="fas fa-edit mr-2" style="color: var(--color-primary);"></i>
                    Ajuster le paiement
                </h3>
                <button onclick="fermerAjustementModal()" class="text-gray-500 hover:text-gray-700 transition text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <div id="ajustementModalContent">
                <!-- Contenu chargé dynamiquement via JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- MODAL DE CONTACT -->
<!-- ============================================ -->
<div id="contactModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm"
     onclick="if(event.target === this) closeContactModal()">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 p-6 transform transition-all duration-300 scale-95" id="modalContent">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold" style="color: var(--color-nav-text);">
                <i class="fas fa-comment mr-2" style="color: var(--color-primary);"></i>
                Contacter le vendeur
            </h3>
            <button onclick="closeContactModal()" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <p class="text-sm mb-4" style="color: var(--color-nav-text); opacity: 0.7;">
            Envoyez un message au vendeur concernant cette annonce.
        </p>
        
        <form action="{{ route('messagerie.start-from-annonce') }}" method="POST">
            @csrf
            <input type="hidden" name="id_annonce" id="annonceId" value="">
            
            <div class="mb-4">
                <label for="contenu" class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                    Votre message
                </label>
                <textarea name="contenu" id="contenu" rows="4" 
                          class="w-full px-4 py-3 rounded-xl border focus:outline-none focus:ring-2 transition resize-none"
                          style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                          onfocus="this.style.borderColor='var(--color-primary)'; this.style.ringColor='var(--color-primary)'"
                          onblur="this.style.borderColor='var(--color-nav-border)'"
                          placeholder="Bonjour, je suis intéressé par votre annonce..." required></textarea>
            </div>
            
            <div class="flex gap-3">
                <button type="button" onclick="closeContactModal()" 
                        class="flex-1 py-2.5 rounded-xl font-medium transition-all duration-300"
                        style="background-color: var(--color-secondary-light); color: var(--color-primary-dark);"
                        onmouseover="this.style.backgroundColor='var(--color-secondary)'"
                        onmouseout="this.style.backgroundColor='var(--color-secondary-light)'">
                    Annuler
                </button>
                <button type="submit" 
                        class="flex-1 py-2.5 rounded-xl font-semibold transition-all duration-300 text-white"
                        style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));"
                        onmouseover="this.style.background='linear-gradient(135deg, var(--color-primary-dark), var(--color-secondary))'; this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.background='linear-gradient(135deg, var(--color-primary), var(--color-primary-dark))'; this.style.transform='translateY(0)'">
                    <i class="fas fa-paper-plane mr-2"></i>
                    Envoyer
                </button>
            </div>
        </form>
    </div>
</div>

<style>
/* Animations pour les modals */
#commandeModal {
    animation: fadeIn 0.3s ease-out;
}
#commandeModal > div {
    animation: slideUp 0.3s ease-out;
}
#paiementModal {
    animation: fadeIn 0.3s ease-out;
}
#paiementModal > div {
    animation: slideUp 0.3s ease-out;
}
#ajustementModal {
    animation: fadeIn 0.3s ease-out;
}
#ajustementModal > div {
    animation: slideUp 0.3s ease-out;
}
#contactModal {
    animation: fadeIn 0.3s ease-out;
}
#contactModal > div {
    animation: slideUp 0.3s ease-out;
}
#contactModal .scale-95 { transform: scale(0.95); }
#contactModal .scale-100 { transform: scale(1); }

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}
@keyframes slideUp {
    from { transform: translateY(20px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>

<script>
// ============================================
// FONCTIONS DU MODAL DE COMMANDE
// ============================================

function ouvrirFormulaireCommande(annonceId, messageId) {
    const modal = document.getElementById('commandeModal');
    const content = document.getElementById('commandeModalContent');
    
    content.innerHTML = `
        <div class="text-center py-8" style="color: var(--color-nav-text); opacity: 0.5;">
            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
            <p>Chargement des informations...</p>
        </div>
    `;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    fetch(`/annonces/${annonceId}/info`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors du chargement des informations');
            }
            return response.json();
        })
        .then(data => {
            let caracteristiquesHtml = '';
            if (data.caracteristiques && data.caracteristiques.length > 0) {
                caracteristiquesHtml = `
                    <div class="grid grid-cols-2 gap-1 mt-2 text-xs" style="color: var(--color-nav-text);">
                        ${data.caracteristiques.map(carac => `
                            <div class="flex items-center gap-1">
                                <span style="opacity: 0.6;">${carac.label}:</span>
                                <span class="font-medium">${carac.value}</span>
                            </div>
                        `).join('')}
                    </div>
                `;
            }
            
            content.innerHTML = `
                <div class="space-y-4">
                    <!-- Annonce -->
                    <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: var(--color-secondary-light);">
                        <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0">
                            ${data.image 
                                ? `<img src="${data.image}" class="w-full h-full object-cover">` 
                                : `<div class="w-full h-full flex items-center justify-center" style="background-color: var(--color-bg-gray);">
                                    <i class="fas fa-tag text-xl" style="color: var(--color-primary);"></i>
                                   </div>`
                            }
                        </div>
                        <div>
                            <p class="font-semibold text-sm" style="color: var(--color-nav-text);">${data.titre}</p>
                            <p class="text-sm font-bold" style="color: var(--color-primary);">
                                ${data.prix} FCFA
                            </p>
                            <span class="text-xs px-2 py-0.5 rounded-full" style="background: var(--color-secondary-light); color: var(--color-nav-text);">
                                ${data.type}
                            </span>
                        </div>
                    </div>
                    
                    ${caracteristiquesHtml}
                    
                    <!-- Quantité -->
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                            Quantité
                        </label>
                        <div class="flex items-center gap-2">
                            <button onclick="changerQuantite(-1)" 
                                    class="w-8 h-8 rounded-lg flex items-center justify-center transition"
                                    style="background-color: var(--color-secondary-light); color: var(--color-nav-text);"
                                    onmouseover="this.style.backgroundColor='var(--color-secondary)'"
                                    onmouseout="this.style.backgroundColor='var(--color-secondary-light)'">
                                <i class="fas fa-minus text-xs"></i>
                            </button>
                            <input type="number" id="quantiteCommande" min="1" value="1" 
                                   class="w-full px-3 py-2 rounded-lg border text-center focus:outline-none focus:ring-2"
                                   style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                   onchange="calculerTotal()">
                            <button onclick="changerQuantite(1)" 
                                    class="w-8 h-8 rounded-lg flex items-center justify-center transition"
                                    style="background-color: var(--color-secondary-light); color: var(--color-nav-text);"
                                    onmouseover="this.style.backgroundColor='var(--color-secondary)'"
                                    onmouseout="this.style.backgroundColor='var(--color-secondary-light)'">
                                <i class="fas fa-plus text-xs"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Total -->
                    <div class="p-3 rounded-lg" style="background-color: var(--color-secondary-light);">
                        <div class="flex justify-between text-sm">
                            <span style="color: var(--color-nav-text);">Total à payer</span>
                            <span class="font-bold" style="color: var(--color-primary);" id="totalPrix">
                                ${data.prix} FCFA
                            </span>
                        </div>
                    </div>
                    
                    <button onclick="confirmerCommande(${annonceId}, ${messageId})" 
                            class="w-full px-4 py-2.5 rounded-lg text-white font-bold transition"
                            style="background: linear-gradient(135deg, #4CAF50, #2E7D32);"
                            onmouseover="this.style.background='linear-gradient(135deg, #2E7D32, #1B5E20)'; this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.background='linear-gradient(135deg, #4CAF50, #2E7D32)'; this.style.transform='translateY(0)'">
                        <i class="fas fa-check mr-2"></i> Confirmer la commande
                    </button>
                </div>
            `;
            
            content.dataset.prix = data.prix_raw;
        })
        .catch(error => {
            console.error('Erreur:', error);
            content.innerHTML = `
                <div class="text-center py-8" style="color: #DC2626;">
                    <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
                    <p>Erreur lors du chargement des informations.</p>
                    <button onclick="fermerFormulaireCommande()" 
                            class="mt-4 px-4 py-2 rounded-lg text-white"
                            style="background: linear-gradient(135deg, #DC2626, #B91C1C);">
                        Fermer
                    </button>
                </div>
            `;
        });
}

function fermerFormulaireCommande() {
    const modal = document.getElementById('commandeModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function changerQuantite(delta) {
    const input = document.getElementById('quantiteCommande');
    let value = parseInt(input.value) || 1;
    value = Math.max(1, value + delta);
    input.value = value;
    calculerTotal();
}

function calculerTotal() {
    const content = document.getElementById('commandeModalContent');
    const prix = parseFloat(content.dataset.prix) || 0;
    const quantite = parseInt(document.getElementById('quantiteCommande').value) || 1;
    const total = prix * quantite;
    const totalElement = document.getElementById('totalPrix');
    if (totalElement) {
        totalElement.textContent = total.toLocaleString('fr-FR') + ' FCFA';
    }
}

function confirmerCommande(annonceId, messageId) {
    const quantite = document.getElementById('quantiteCommande').value;
    
    if (!quantite || quantite < 1) {
        showNotification('Veuillez saisir une quantité valide.', 'error');
        return;
    }
    
    const btn = document.querySelector('#commandeModalContent button:last-child');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Création...';
    
    fetch('{{ route("messagerie.creer-commande") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            id_message: messageId,
            quantite: quantite
        })
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        
        if (data.success) {
            showNotification('Commande créée avec succès !');
            fermerFormulaireCommande();
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Erreur lors de la création.', 'error');
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        console.error('Erreur:', error);
        showNotification('Erreur de connexion.', 'error');
    });
}

// ============================================
// FONCTIONS DU MODAL DE PAIEMENT
// ============================================

function ouvrirPaiementModal(commandeId) {
    const modal = document.getElementById('paiementModal');
    const content = document.getElementById('paiementModalContent');
    
    content.innerHTML = `
        <div class="text-center py-8" style="color: var(--color-nav-text); opacity: 0.5;">
            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
            <p>Chargement des informations de paiement...</p>
        </div>
    `;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    fetch(`/commandes/${commandeId}/info`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors du chargement');
            }
            return response.json();
        })
        .then(data => {
            content.innerHTML = `
                <div class="space-y-4">
                    <!-- Infos commande -->
                    <div class="p-3 rounded-lg" style="background-color: var(--color-secondary-light);">
                        <h4 class="font-semibold text-sm mb-2" style="color: var(--color-nav-text);">
                            <i class="fas fa-shopping-cart mr-2" style="color: var(--color-primary);"></i>
                            Commande #${data.commande.id}
                        </h4>
                        <div class="grid grid-cols-2 gap-1 text-sm" style="color: var(--color-nav-text);">
                            <span>Quantité:</span>
                            <span class="font-medium">${data.commande.quantite}</span>
                            <span>Prix unitaire:</span>
                            <span class="font-medium">${data.commande.prix_unitaire} FCFA</span>
                            ${data.commande.reduction > 0 ? `
                                <span>Réduction:</span>
                                <span class="font-medium" style="color: #4CAF50;">-${data.commande.reduction} FCFA</span>
                            ` : ''}
                            <span class="font-bold">Total:</span>
                            <span class="font-bold" style="color: var(--color-primary);">${data.commande.montant_ajuste} FCFA</span>
                        </div>
                    </div>
                    
                    <!-- Annonce -->
                    <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: var(--color-secondary-light);">
                        <div class="w-16 h-16 rounded-lg overflow-hidden flex-shrink-0">
                            ${data.annonce.image 
                                ? `<img src="${data.annonce.image}" class="w-full h-full object-cover">` 
                                : `<div class="w-full h-full flex items-center justify-center" style="background-color: var(--color-bg-gray);">
                                    <i class="fas fa-tag text-xl" style="color: var(--color-primary);"></i>
                                   </div>`
                            }
                        </div>
                        <div>
                            <p class="font-semibold text-sm" style="color: var(--color-nav-text);">${data.annonce.titre}</p>
                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.7;">
                                ${data.annonce.type}
                            </p>
                        </div>
                    </div>
                    
                    <button onclick="confirmerPaiement(${commandeId})" 
                            class="w-full px-4 py-2.5 rounded-lg text-white font-bold transition"
                            style="background: linear-gradient(135deg, #f44336, #c62828);"
                            onmouseover="this.style.background='linear-gradient(135deg, #c62828, #b71C1C)'; this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.background='linear-gradient(135deg, #f44336, #c62828)'; this.style.transform='translateY(0)'">
                        <i class="fas fa-credit-card mr-2"></i> Confirmer le paiement
                    </button>
                </div>
            `;
        })
        .catch(error => {
            console.error('Erreur:', error);
            content.innerHTML = `
                <div class="text-center py-8" style="color: #DC2626;">
                    <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
                    <p>Erreur lors du chargement des informations.</p>
                    <button onclick="fermerPaiementModal()" 
                            class="mt-4 px-4 py-2 rounded-lg text-white"
                            style="background: linear-gradient(135deg, #DC2626, #B91C1C);">
                        Fermer
                    </button>
                </div>
            `;
        });
}

function fermerPaiementModal() {
    const modal = document.getElementById('paiementModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function confirmerPaiement(commandeId) {
    if (!confirm('Confirmez-vous le paiement de cette commande ?')) {
        return;
    }
    
    const btn = document.querySelector('#paiementModalContent button:last-child');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement...';
    
    fetch(`/paiement/${commandeId}/process`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        
        if (data.success) {
            showNotification('Paiement effectué avec succès !');
            fermerPaiementModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Erreur lors du paiement.', 'error');
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        console.error('Erreur:', error);
        showNotification('Erreur de connexion.', 'error');
    });
}

// ============================================
// FONCTIONS D'AJUSTEMENT DU PAIEMENT
// ============================================

function ajusterPaiement(commandeId) {
    const modal = document.getElementById('ajustementModal');
    const content = document.getElementById('ajustementModalContent');
    
    content.innerHTML = `
        <div class="text-center py-8" style="color: var(--color-nav-text); opacity: 0.5;">
            <i class="fas fa-spinner fa-spin text-2xl mb-2"></i>
            <p>Chargement des informations...</p>
        </div>
    `;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    
    fetch(`/commandes/${commandeId}/info`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors du chargement');
            }
            return response.json();
        })
        .then(data => {
            const montantTotal = data.commande.montant_total;
            const reductionActuelle = data.commande.reduction || 0;
            const montantActuel = data.commande.montant_ajuste || montantTotal;
            
            content.innerHTML = `
                <div class="space-y-4">
                    <!-- Résumé de la commande -->
                    <div class="p-3 rounded-lg" style="background-color: var(--color-secondary-light);">
                        <div class="flex justify-between text-sm">
                            <span style="color: var(--color-nav-text);">Commande #${data.commande.id}</span>
                            <span style="color: var(--color-nav-text);">Qté: ${data.commande.quantite}</span>
                        </div>
                        <div class="flex justify-between text-sm mt-1">
                            <span style="color: var(--color-nav-text);">Prix unitaire</span>
                            <span class="font-medium" style="color: var(--color-nav-text);">${data.commande.prix_unitaire} FCFA</span>
                        </div>
                        <div class="flex justify-between text-sm mt-1">
                            <span style="color: var(--color-nav-text);">Montant total</span>
                            <span class="font-bold" style="color: var(--color-primary);">${montantTotal.toLocaleString('fr-FR')} FCFA</span>
                        </div>
                        <div class="flex justify-between text-sm mt-1" style="border-top: 1px dashed var(--color-nav-border); padding-top: 8px;">
                            <span style="color: var(--color-nav-text);">Réduction actuelle</span>
                            <span style="color: #4CAF50;">-${reductionActuelle.toLocaleString('fr-FR')} FCFA</span>
                        </div>
                        <div class="flex justify-between text-sm mt-1 font-bold">
                            <span style="color: var(--color-nav-text);">Montant à payer</span>
                            <span style="color: var(--color-primary);">${montantActuel.toLocaleString('fr-FR')} FCFA</span>
                        </div>
                    </div>
                    
                    <!-- Annonce -->
                    <div class="flex items-center gap-3 p-3 rounded-lg" style="background-color: var(--color-secondary-light);">
                        <div class="w-12 h-12 rounded-lg overflow-hidden flex-shrink-0">
                            ${data.annonce.image 
                                ? `<img src="${data.annonce.image}" class="w-full h-full object-cover">` 
                                : `<div class="w-full h-full flex items-center justify-center" style="background-color: var(--color-bg-gray);">
                                    <i class="fas fa-tag text-lg" style="color: var(--color-primary);"></i>
                                   </div>`
                            }
                        </div>
                        <div>
                            <p class="font-semibold text-sm" style="color: var(--color-nav-text);">${data.annonce.titre}</p>
                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.7;">${data.annonce.type}</p>
                        </div>
                    </div>
                    
                    <!-- Champ réduction -->
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--color-nav-text);">
                            Montant de la réduction (FCFA)
                        </label>
                        <div class="flex items-center gap-2">
                            <input type="number" id="montantReduction" min="0" max="${montantTotal}" value="${reductionActuelle}" 
                                   class="w-full px-3 py-2 rounded-lg border focus:outline-none focus:ring-2"
                                   style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray); color: var(--color-nav-text);"
                                   oninput="calculerNouveauTotal(${montantTotal})">
                            <span class="text-sm" style="color: var(--color-nav-text); opacity: 0.6;">FCFA</span>
                        </div>
                        <div class="flex justify-between text-xs mt-1" style="color: var(--color-nav-text); opacity: 0.5;">
                            <span>Min: 0</span>
                            <span>Max: ${montantTotal.toLocaleString('fr-FR')} FCFA</span>
                        </div>
                    </div>
                    
                    <!-- Nouveau total -->
                    <div class="p-3 rounded-lg" style="background-color: var(--color-secondary-light);">
                        <div class="flex justify-between text-sm">
                            <span style="color: var(--color-nav-text);">Nouveau montant à payer</span>
                            <span class="font-bold" style="color: var(--color-primary);" id="nouveauTotal">
                                ${montantActuel.toLocaleString('fr-FR')} FCFA
                            </span>
                        </div>
                    </div>
                    
                    <div class="flex gap-3">
                        <button onclick="fermerAjustementModal()" 
                                class="flex-1 py-2.5 rounded-lg font-medium transition-all duration-300"
                                style="background-color: var(--color-secondary-light); color: var(--color-primary-dark);">
                            Annuler
                        </button>
                        <button onclick="confirmerAjustement(${commandeId})" 
                                class="flex-1 py-2.5 rounded-lg font-semibold transition-all duration-300 text-white"
                                style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                            <i class="fas fa-check mr-2"></i>
                            Appliquer
                        </button>
                    </div>
                </div>
            `;
            
            content.dataset.montantTotal = montantTotal;
            content.dataset.reductionActuelle = reductionActuelle;
        })
        .catch(error => {
            console.error('Erreur:', error);
            content.innerHTML = `
                <div class="text-center py-8" style="color: #DC2626;">
                    <i class="fas fa-exclamation-circle text-2xl mb-2"></i>
                    <p>Erreur lors du chargement des informations.</p>
                    <button onclick="fermerAjustementModal()" 
                            class="mt-4 px-4 py-2 rounded-lg text-white"
                            style="background: linear-gradient(135deg, #DC2626, #B91C1C);">
                        Fermer
                    </button>
                </div>
            `;
        });
}

function fermerAjustementModal() {
    const modal = document.getElementById('ajustementModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function calculerNouveauTotal(montantTotal) {
    const input = document.getElementById('montantReduction');
    const nouveauTotalSpan = document.getElementById('nouveauTotal');
    
    let reduction = parseFloat(input.value) || 0;
    
    if (reduction < 0) {
        reduction = 0;
        input.value = 0;
    }
    if (reduction > montantTotal) {
        reduction = montantTotal;
        input.value = montantTotal;
    }
    
    const nouveauTotal = montantTotal - reduction;
    nouveauTotalSpan.textContent = nouveauTotal.toLocaleString('fr-FR') + ' FCFA';
    
    if (reduction > 0) {
        nouveauTotalSpan.style.color = '#4CAF50';
    } else {
        nouveauTotalSpan.style.color = 'var(--color-primary)';
    }
}

function confirmerAjustement(commandeId) {
    const input = document.getElementById('montantReduction');
    const reduction = parseFloat(input.value) || 0;
    const content = document.getElementById('ajustementModalContent');
    const montantTotal = parseFloat(content.dataset.montantTotal) || 0;
    
    if (reduction < 0 || reduction > montantTotal) {
        showNotification('Le montant de la réduction est invalide.', 'error');
        return;
    }
    
    const btn = document.querySelector('#ajustementModalContent button:last-child');
    const originalText = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Traitement...';
    
    fetch(`/commandes/${commandeId}/ajuster`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            reduction: reduction
        })
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        
        if (data.success) {
            showNotification('Réduction appliquée avec succès !');
            fermerAjustementModal();
            setTimeout(() => location.reload(), 1000);
        } else {
            showNotification(data.message || 'Erreur lors de l\'ajustement.', 'error');
        }
    })
    .catch(error => {
        btn.disabled = false;
        btn.innerHTML = originalText;
        console.error('Erreur:', error);
        showNotification('Erreur de connexion.', 'error');
    });
}

// ============================================
// FONCTIONS DU MODAL DE CONTACT
// ============================================

function openContactModal(annonceId) {
    document.getElementById('annonceId').value = annonceId;
    document.getElementById('contactModal').classList.remove('hidden');
    document.getElementById('contactModal').classList.add('flex');
    setTimeout(() => {
        document.getElementById('modalContent').classList.remove('scale-95');
        document.getElementById('modalContent').classList.add('scale-100');
    }, 50);
}

function closeContactModal() {
    document.getElementById('modalContent').classList.remove('scale-100');
    document.getElementById('modalContent').classList.add('scale-95');
    setTimeout(() => {
        document.getElementById('contactModal').classList.add('hidden');
        document.getElementById('contactModal').classList.remove('flex');
    }, 300);
}

// ============================================
// FONCTIONS DE NOTIFICATION - PARTAGÉES
// ============================================
// La fonction showNotification est définie dans show.blade.php
// On vérifie si elle n'existe pas avant de la définir
if (typeof showNotification === 'undefined') {
    function showNotification(message, type = 'success') {
        const colors = {
            success: 'linear-gradient(135deg, #10B981, #059669)',
            error: 'linear-gradient(135deg, #EF4444, #DC2626)',
            warning: 'linear-gradient(135deg, #F59E0B, #D97706)',
            info: 'linear-gradient(135deg, #3B82F6, #2563EB)'
        };
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        
        const notification = document.createElement('div');
        notification.className = 'fixed bottom-4 right-4 px-4 py-3 rounded-lg shadow-lg text-white z-[9999] max-w-md';
        notification.style.background = colors[type] || colors.success;
        notification.style.zIndex = '9999';
        notification.style.animation = 'slideInRight 0.3s ease-out';
        notification.style.wordBreak = 'break-word';
        notification.innerHTML = `<i class="fas ${icons[type] || icons.success} mr-2"></i>${message}`;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.5s';
            setTimeout(() => notification.remove(), 500);
        }, 4000);
    }
}

// ============================================
// FERMETURE AVEC ÉCHAP
// ============================================
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        fermerFormulaireCommande();
        fermerPaiementModal();
        fermerAjustementModal();
        closeContactModal();
        if (typeof closeImagePreview === 'function') {
            closeImagePreview();
        }
        if (typeof closeAudioRecorder === 'function') {
            closeAudioRecorder();
        }
        if (typeof closeVideoRecorder === 'function') {
            closeVideoRecorder();
        }
    }
});
</script>