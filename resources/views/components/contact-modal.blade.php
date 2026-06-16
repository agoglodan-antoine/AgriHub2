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

<script>
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
    
    // Fermer avec Echap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeContactModal();
        }
    });
</script>