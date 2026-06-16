<x-app-layout>
    <x-slot name="header">
        <div class="py-8" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4">
                    <a href="{{ route('messagerie.index') }}" class="text-white hover:text-secondary-light transition">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center text-white font-bold text-lg flex-shrink-0"
                             style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark)); border: 2px solid rgba(255,255,255,0.3);">
                            {{ strtoupper(substr($otherUser->prenom ?? '', 0, 1) . substr($otherUser->nom ?? '', 0, 1)) }}
                        </div>
                        <div class="min-w-0">
                            <h1 class="text-xl font-bold text-white truncate">
                                {{ $otherUser->prenom ?? '' }} {{ $otherUser->nom ?? 'Utilisateur' }}
                            </h1>
                            <p class="text-white/80 text-sm truncate">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                {{ $otherUser->commune ?? 'Bénin' }}
                            </p>
                        </div>
                    </div>
                    @if($annonce)
                        @php
                            $annonceRoute = match($annonce->type) {
                                'animal' => route('annonces.animaux.show', $annonce->id),
                                'nourriture' => route('annonces.aliments.show', $annonce->id),
                                'accessoire' => route('annonces.accessoires.show', $annonce->id),
                                'escrement' => route('annonces.escrements.show', $annonce->id),
                                default => route('annonces.show', $annonce->id),
                            };
                        @endphp
                        <a href="{{ $annonceRoute }}" 
                           class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-300 text-white flex-shrink-0"
                           style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);"
                           onmouseover="this.style.background='rgba(255,255,255,0.35)'; this.style.transform='scale(1.05)'"
                           onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='scale(1)'">
                            <i class="fas fa-tag mr-1"></i>
                            Voir l'annonce
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6" style="background-color: var(--color-bg-body);">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Annonce associée -->
            @if($annonce)
                @php
                    $annonceRoute = match($annonce->type) {
                        'animal' => route('annonces.animaux.show', $annonce->id),
                        'nourriture' => route('annonces.aliments.show', $annonce->id),
                        'accessoire' => route('annonces.accessoires.show', $annonce->id),
                        'escrement' => route('annonces.escrements.show', $annonce->id),
                        default => route('annonces.show', $annonce->id),
                    };
                @endphp
                <div class="rounded-xl overflow-hidden shadow-md mb-6 transition hover:shadow-lg cursor-pointer"
                     style="background-color: var(--color-bg-white); border: 1px solid var(--color-nav-border);"
                     onclick="window.location='{{ $annonceRoute }}'">
                    <div class="flex items-center gap-4 p-4">
                        <div class="w-20 h-20 rounded-lg overflow-hidden flex-shrink-0">
                            @php
                                $img = $annonce->piecesJointes->where('est_principale', true)->first() ?? $annonce->piecesJointes->first();
                                $imageExists = $img && $img->chemin_stockage && Storage::disk('public')->exists($img->chemin_stockage);
                            @endphp
                            @if($imageExists)
                                <img src="{{ asset('storage/' . $img->chemin_stockage) }}" alt="{{ $annonce->titre }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="background-color: var(--color-secondary-light);">
                                    <i class="fas fa-tag text-2xl" style="color: var(--color-primary);"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold truncate" style="color: var(--color-nav-text);">{{ $annonce->titre }}</h4>
                            <p class="text-sm font-bold" style="color: var(--color-primary);">
                                {{ number_format($annonce->prix, 0, ',', ' ') }} FCFA
                            </p>
                            <p class="text-xs" style="color: var(--color-nav-text); opacity: 0.6;">
                                <i class="fas fa-user mr-1"></i>
                                Vendeur: {{ $annonce->auteur->prenom ?? '' }} {{ $annonce->auteur->nom ?? '' }}
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-chevron-right" style="color: var(--color-nav-text); opacity: 0.3;"></i>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Messages -->
            <div class="rounded-xl shadow-lg overflow-hidden" style="background-color: var(--color-bg-white);">
                <div class="p-4 space-y-4 max-h-[500px] overflow-y-auto" id="messages-container">
                    @forelse($messages as $message)
                        <!-- Message parent (si c'est une réponse) -->
                        @if($message->est_reponse && $message->reponseA)
                            <div class="flex {{ $message->id_expediteur === Auth::id() ? 'justify-end' : 'justify-start' }} relative">
                                <div class="max-w-[80%] {{ $message->id_expediteur === Auth::id() ? 'order-2' : 'order-1' }}">
                                    <div class="text-[10px] mb-1 px-3 py-1 rounded-lg cursor-pointer hover:opacity-80 transition parent-message"
                                         style="background-color: var(--color-secondary-light); color: var(--color-primary-dark); border-left: 3px solid var(--color-primary);"
                                         onclick="scrollToMessage({{ $message->reponseA->id }})">
                                        <i class="fas fa-reply mr-1"></i>
                                        Réponse à <strong>{{ $message->reponseA->expediteur->prenom ?? 'Utilisateur' }}</strong>:
                                        <span class="opacity-70">{{ Str::limit($message->reponseA->contenu, 50) }}</span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Message -->
                        @include('messages.partials.message-item', ['message' => $message])
                    @empty
                        <div class="text-center py-8" id="empty-message">
                            <p style="color: var(--color-nav-text); opacity: 0.7;">Aucun message. Commencez la discussion !</p>
                        </div>
                    @endforelse
                </div>

                <!-- Formulaire d'envoi -->
                <div class="border-t p-4" style="border-color: var(--color-nav-border);">
                    <form id="messageForm" class="space-y-3">
                        @csrf
                        <input type="hidden" name="id_destinataire" value="{{ $otherUser->id }}">
                        <input type="hidden" name="id_annonce" value="{{ $annonce->id ?? '' }}">
                        <input type="hidden" name="reponse_a_id" id="reponse_a_id" value="">
                        <input type="hidden" name="audio_data" id="audio_data" value="">
                        <input type="hidden" name="video_data" id="video_data" value="">
                        
                        <!-- Fichiers sélectionnés -->
                        <div id="file-preview-container" class="flex flex-wrap gap-2 pb-2" style="display: none;">
                            <!-- Les fichiers apparaîtront ici -->
                        </div>
                        
                        <!-- Erreurs -->
                        <div id="form-errors" class="hidden p-3 rounded-lg text-sm" style="background-color: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5;">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span id="error-text"></span>
                        </div>
                        
                        <!-- Champ de message -->
                        <div class="flex gap-3 items-end">
                            <div class="flex-1 relative">
                                <!-- Indicateur de réponse -->
                                <div id="reply-indicator" class="hidden mb-2 p-2 rounded-lg flex items-center justify-between"
                                     style="background-color: var(--color-secondary-light); border-left: 3px solid var(--color-primary);">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-reply" style="color: var(--color-primary);"></i>
                                        <span class="text-xs" style="color: var(--color-nav-text);">
                                            Réponse à <strong id="reply-to-name"></strong>
                                        </span>
                                    </div>
                                    <button type="button" onclick="cancelReply()" class="text-xs hover:text-red-500 transition" style="color: var(--color-nav-text); opacity: 0.6;">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                
                                <div class="flex items-center border rounded-xl overflow-hidden focus-within:ring-2 transition"
                                     style="border-color: var(--color-nav-border); background-color: var(--color-bg-gray);">
                                    
                                    <label for="file-input" class="px-2 py-2 cursor-pointer transition hover:scale-110 flex-shrink-0"
                                           style="color: var(--color-primary);" title="Joindre des fichiers (Max 5, 5MB images, 10MB vidéos)">
                                        <i class="fas fa-paperclip text-sm"></i>
                                        <input type="file" 
                                               id="file-input"
                                               name="pieces_jointes[]" 
                                               multiple 
                                               accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.zip,.rar"
                                               class="hidden"
                                               onchange="handleFiles(this)">
                                    </label>
                                    
                                    <button type="button" 
                                            id="audio-record-btn"
                                            class="px-2 py-2 cursor-pointer transition hover:scale-110 flex-shrink-0"
                                            style="color: var(--color-primary);"
                                            title="Enregistrer un audio">
                                        <i class="fas fa-microphone text-sm"></i>
                                    </button>
                                    
                                    <button type="button" 
                                            id="video-record-btn"
                                            class="px-2 py-2 cursor-pointer transition hover:scale-110 flex-shrink-0"
                                            style="color: var(--color-primary);"
                                            title="Enregistrer une vidéo">
                                        <i class="fas fa-video text-sm"></i>
                                    </button>
                                    
                                    <textarea name="contenu" 
                                              id="message-input"
                                              rows="1"
                                              placeholder="Écrivez votre message..."
                                              class="flex-1 px-2 py-2.5 bg-transparent focus:outline-none resize-none text-sm"
                                              style="color: var(--color-nav-text); min-height: 40px; max-height: 120px;"
                                              oninput="autoResize(this)"
                                              onkeydown="if(event.key === 'Enter' && !event.shiftKey) { event.preventDefault(); sendMessage(); }">{{ old('contenu') }}</textarea>
                                </div>
                            </div>
                            
                            <button type="button" 
                                    id="submit-btn"
                                    onclick="sendMessage()"
                                    class="px-5 py-2.5 rounded-xl font-semibold transition-all duration-300 text-white flex-shrink-0"
                                    style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));"
                                    onmouseover="this.style.background='linear-gradient(135deg, var(--color-primary-dark), var(--color-secondary))'; this.style.transform='translateY(-2px)'"
                                    onmouseout="this.style.background='linear-gradient(135deg, var(--color-primary), var(--color-primary-dark))'; this.style.transform='translateY(0)'">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modals -->
    @include('messages.partials.modals')

    <!-- Menu contextuel -->
    <div id="contextMenu" class="fixed z-50 hidden rounded-xl shadow-2xl py-1 min-w-[200px]"
         style="background-color: var(--color-bg-white); border: 1px solid var(--color-nav-border);">
        @auth
            <button onclick="contextMenuAction('edit')" 
                    class="flex items-center gap-3 w-full px-4 py-2 text-sm transition-colors"
                    style="color: var(--color-nav-text);"
                    onmouseover="this.style.backgroundColor='var(--color-secondary-light)'"
                    onmouseout="this.style.backgroundColor='transparent'">
                <i class="fas fa-edit" style="color: var(--color-primary);"></i>
                Modifier
            </button>
            <button onclick="contextMenuAction('delete')" 
                    class="flex items-center gap-3 w-full px-4 py-2 text-sm transition-colors"
                    style="color: #dc2626;"
                    onmouseover="this.style.backgroundColor='#FEE2E2'"
                    onmouseout="this.style.backgroundColor='transparent'">
                <i class="fas fa-trash"></i>
                Supprimer
            </button>
        @endauth
    </div>

    <style>
        /* Styles de base */
        .scrollbar-thin::-webkit-scrollbar { height: 4px; }
        .scrollbar-thin::-webkit-scrollbar-track { background: var(--color-nav-border); border-radius: 10px; }
        .scrollbar-thin::-webkit-scrollbar-thumb { background: var(--color-primary); border-radius: 10px; }
        
        #messages-container { scroll-behavior: smooth; }
        #messages-container::-webkit-scrollbar { width: 4px; }
        #messages-container::-webkit-scrollbar-track { background: var(--color-nav-border); border-radius: 10px; }
        #messages-container::-webkit-scrollbar-thumb { background: var(--color-primary); border-radius: 10px; }
        
        .file-tag {
            display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.35rem 0.75rem;
            border-radius: 9999px; font-size: 0.75rem; white-space: nowrap;
            background: var(--color-secondary-light); color: var(--color-primary-dark);
            flex-shrink: 0; border: 1px solid var(--color-primary);
        }
        .file-tag .remove-file { cursor: pointer; opacity: 0.5; transition: opacity 0.2s; }
        .file-tag .remove-file:hover { opacity: 1; color: #dc2626; }
        
        #reply-indicator { animation: slideDown 0.2s ease-out; }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .parent-message { cursor: pointer; transition: all 0.2s; }
        .parent-message:hover { opacity: 0.8; }
        
        .message-item { 
            opacity: 0; 
            animation: fadeInUp 0.3s ease forwards; 
            cursor: default;
            user-select: none;
            -webkit-user-select: none;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .audio-bar {
            width: 8px;
            background: linear-gradient(to top, var(--color-primary), var(--color-primary-dark));
            border-radius: 4px;
            transition: height 0.1s ease;
            animation: audioPulse 1.5s ease-in-out infinite;
        }
        .audio-bar:nth-child(2) { animation-delay: 0.1s; }
        .audio-bar:nth-child(3) { animation-delay: 0.2s; }
        .audio-bar:nth-child(4) { animation-delay: 0.3s; }
        .audio-bar:nth-child(5) { animation-delay: 0.4s; }
        .audio-bar:nth-child(6) { animation-delay: 0.5s; }
        .audio-bar:nth-child(7) { animation-delay: 0.6s; }
        .audio-bar:nth-child(8) { animation-delay: 0.7s; }
        @keyframes audioPulse {
            0%, 100% { height: 10px; }
            50% { height: 40px; }
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        .animate-pulse { animation: pulse 1s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        /* Menu contextuel */
        #contextMenu {
            animation: contextMenuAppear 0.15s ease-out;
        }
        @keyframes contextMenuAppear {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-5px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
        
        /* Highlight du message sélectionné */
        .message-item.context-active {
            background-color: rgba(212, 175, 55, 0.08);
            border-radius: 8px;
            padding: 4px 8px;
            margin: -4px -8px;
        }
        
        /* Style d'édition */
        .message-item.editing .message-bubble {
            border: 2px solid var(--color-primary);
            box-shadow: 0 0 0 4px rgba(212, 175, 55, 0.1);
        }
        
        .edit-textarea {
            width: 100%;
            min-height: 60px;
            padding: 8px 12px;
            border-radius: 8px;
            font-size: 0.875rem;
            background-color: var(--color-bg-gray);
            border: 1px solid var(--color-nav-border);
            color: var(--color-nav-text);
            resize: vertical;
        }
        .edit-textarea:focus {
            outline: none;
            border-color: var(--color-primary);
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
        }
        
        /* ✅ CORRECTION : Amélioration de l'affichage des images et vidéos */
        .message-bubble img {
            display: block;
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
        .message-bubble video {
            display: block;
            max-width: 100%;
            height: auto;
            background: #000;
            border-radius: 8px;
        }
        
        /* ✅ AJOUT : Style pour les fichiers dans les messages */
        .file-attachment {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .file-attachment:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .file-attachment .file-icon {
            font-size: 1.25rem;
            width: 2rem;
            text-align: center;
        }
        .file-attachment .file-info {
            flex: 1;
            min-width: 0;
        }
        .file-attachment .file-name {
            font-size: 0.8rem;
            font-weight: 500;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .file-attachment .file-size {
            font-size: 0.7rem;
            opacity: 0.6;
        }
        .file-attachment .file-download {
            flex-shrink: 0;
            opacity: 0.6;
            transition: opacity 0.2s;
        }
        .file-attachment .file-download:hover {
            opacity: 1;
        }
    </style>

    <!-- Scripts JavaScript -->
    <script>
    // ============================================
    // CONFIGURATION ET VARIABLES
    // ============================================
    let selectedFiles = [];
    let replyToId = null;
    let isAudioRecording = false;
    let isVideoRecording = false;
    let audioRecorder = null;
    let audioStream = null;
    let audioChunks = [];
    let audioTimer = null;
    let audioStartTime = null;
    let isAudioRecorded = false;
    const AUDIO_MAX_DURATION = 30;
    let videoRecorder = null;
    let videoStream = null;
    let videoChunks = [];
    let videoTimer = null;
    let videoStartTime = null;
    let isVideoRecorded = false;
    const VIDEO_MAX_DURATION = 60;
    let editingMessageId = null;

    // ============================================
    // ENVOI DU MESSAGE VIA AJAX
    // ============================================
    function sendMessage() {
        const submitBtn = document.getElementById('submit-btn');
        const messageInput = document.getElementById('message-input');
        
        // Vérifier les enregistrements en cours
        if (isAudioRecording) {
            alert('Veuillez arrêter l\'enregistrement audio avant d\'envoyer.');
            return;
        }
        if (isVideoRecording) {
            alert('Veuillez arrêter l\'enregistrement vidéo avant d\'envoyer.');
            return;
        }
        
        // Vérifier le contenu
        const hasContent = messageInput.value.trim().length > 0;
        const hasAudio = document.getElementById('audio_data').value.length > 0;
        const hasVideo = document.getElementById('video_data').value.length > 0;
        
        // Vérifier les fichiers correctement
        let hasFiles = selectedFiles.length > 0;
        const fileInput = document.getElementById('file-input');
        if (!hasFiles && fileInput.files.length > 0) {
            for (let i = 0; i < fileInput.files.length; i++) {
                if (fileInput.files[i].size > 0) {
                    hasFiles = true;
                    break;
                }
            }
        }
        
        if (!hasContent && !hasAudio && !hasVideo && !hasFiles) {
            showError('Veuillez saisir un message, joindre un fichier, ou enregistrer un audio/vidéo.');
            return;
        }
        
        // Si selectedFiles est vide mais fileInput a des fichiers, les synchroniser
        if (selectedFiles.length === 0 && fileInput.files.length > 0) {
            const files = Array.from(fileInput.files);
            const validFiles = files.filter(f => f.size > 0);
            if (validFiles.length > 0) {
                selectedFiles = validFiles;
                updateFilePreview();
            }
        }
        
        // Créer le FormData
        const formData = new FormData();
        
        formData.append('_token', document.querySelector('input[name="_token"]').value);
        formData.append('id_destinataire', document.querySelector('input[name="id_destinataire"]').value);
        
        const idAnnonce = document.querySelector('input[name="id_annonce"]');
        if (idAnnonce && idAnnonce.value) {
            formData.append('id_annonce', idAnnonce.value);
        }
        
        const reponseAId = document.getElementById('reponse_a_id');
        if (reponseAId && reponseAId.value) {
            formData.append('reponse_a_id', reponseAId.value);
        }
        
        const audioData = document.getElementById('audio_data');
        if (audioData && audioData.value) {
            formData.append('audio_data', audioData.value);
        }
        
        const videoData = document.getElementById('video_data');
        if (videoData && videoData.value) {
            formData.append('video_data', videoData.value);
        }
        
        if (hasContent) {
            formData.append('contenu', messageInput.value.trim());
        }
        
        // Ajouter les fichiers - utiliser selectedFiles
        if (selectedFiles.length > 0) {
            for (let i = 0; i < selectedFiles.length; i++) {
                if (selectedFiles[i].size > 0) {
                    formData.append('pieces_jointes[]', selectedFiles[i]);
                }
            }
        } else if (fileInput.files.length > 0) {
            for (let i = 0; i < fileInput.files.length; i++) {
                if (fileInput.files[i].size > 0) {
                    formData.append('pieces_jointes[]', fileInput.files[i]);
                }
            }
        }
        
        // Debug
        console.log('📦 Envoi des données:');
        for (let [key, value] of formData.entries()) {
            if (value instanceof File) {
                console.log(`  ${key}: ${value.name} (${value.size} bytes)`);
            } else {
                console.log(`  ${key}: ${value}`);
            }
        }
        
        // Désactiver le bouton
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Envoi...';
        hideError();
        
        // Envoyer la requête
        fetch('{{ route("messagerie.send") }}', {
            method: 'POST',
            headers: {
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.message || 'Erreur serveur');
                });
            }
            return response.json();
        })
        .then(data => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            
            if (data.success) {
                const container = document.getElementById('messages-container');
                container.insertAdjacentHTML('beforeend', data.html);
                container.scrollTop = container.scrollHeight;
                
                // Réinitialiser le formulaire
                messageInput.value = '';
                messageInput.style.height = 'auto';
                selectedFiles = [];
                updateFilePreview();
                document.getElementById('file-input').value = '';
                document.getElementById('audio_data').value = '';
                document.getElementById('video_data').value = '';
                document.getElementById('audio-record-btn').innerHTML = '<i class="fas fa-microphone text-sm"></i>';
                document.getElementById('audio-record-btn').style.color = 'var(--color-primary)';
                document.getElementById('video-record-btn').innerHTML = '<i class="fas fa-video text-sm"></i>';
                document.getElementById('video-record-btn').style.color = 'var(--color-primary)';
                cancelReply();
                
                const emptyMessage = container.querySelector('#empty-message');
                if (emptyMessage) emptyMessage.remove();
                
                setTimeout(initContextMenu, 100);
                
                showNotification('Message envoyé avec succès !');
            } else {
                if (data.errors) {
                    let errors = Object.values(data.errors).flat().join('\n');
                    showError(errors);
                } else {
                    showError(data.message || 'Erreur lors de l\'envoi du message.');
                }
            }
        })
        .catch(error => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i>';
            console.error('Erreur:', error);
            showError('Erreur: ' + error.message);
        });
    }

    // ============================================
    // GESTION DES FICHIERS
    // ============================================
    function handleFiles(input) {
        const files = Array.from(input.files);
        
        if (selectedFiles.length + files.length > 5) {
            alert('Vous ne pouvez joindre que 5 fichiers maximum.');
            input.value = '';
            return;
        }
        
        for (const file of files) {
            const extension = file.name.split('.').pop().toLowerCase();
            const size = file.size;
            
            if (['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'bmp', 'ico'].includes(extension)) {
                if (size > 5 * 1024 * 1024) {
                    alert(`L'image "${file.name}" dépasse la limite de 5MB.`);
                    input.value = '';
                    return;
                }
            } else if (['mp4', 'avi', 'mov', 'wmv', 'flv', 'mkv', 'webm', '3gp', 'm4v', 'mpeg'].includes(extension)) {
                if (size > 10 * 1024 * 1024) {
                    alert(`La vidéo "${file.name}" dépasse la limite de 10MB.`);
                    input.value = '';
                    return;
                }
            } else if (['mp3', 'wav', 'ogg', 'aac', 'flac', 'opus'].includes(extension)) {
                if (size > 10 * 1024 * 1024) {
                    alert(`L'audio "${file.name}" dépasse la limite de 10MB.`);
                    input.value = '';
                    return;
                }
            } else {
                if (size > 5 * 1024 * 1024) {
                    alert(`Le fichier "${file.name}" dépasse la limite de 5MB.`);
                    input.value = '';
                    return;
                }
            }
        }
        
        selectedFiles = [...selectedFiles, ...files];
        updateFileInput();
        updateFilePreview();
        input.value = '';
        
        console.log('📁 Fichiers sélectionnés:', selectedFiles.length);
    }

    function updateFileInput() {
        const input = document.getElementById('file-input');
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        input.files = dataTransfer.files;
    }

    function updateFilePreview() {
        const container = document.getElementById('file-preview-container');
        if (selectedFiles.length === 0) {
            container.style.display = 'none';
            container.innerHTML = '';
            return;
        }
        
        container.style.display = 'flex';
        container.innerHTML = selectedFiles.map((file, index) => {
            const icon = file.type.startsWith('image/') ? 'image' : 
                        file.type.startsWith('video/') ? 'video' : 
                        file.type.startsWith('audio/') ? 'music' : 'file';
            const size = (file.size / 1024 / 1024).toFixed(2);
            return `
                <span class="file-tag">
                    <i class="fas fa-${icon}"></i>
                    ${file.name.length > 20 ? file.name.substring(0, 20) + '...' : file.name}
                    <span class="text-[10px] opacity-60">(${size}MB)</span>
                    <span class="remove-file" onclick="removeFile(${index})">
                        <i class="fas fa-times"></i>
                    </span>
                </span>
            `;
        }).join('');
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1);
        updateFileInput();
        updateFilePreview();
    }

    // ============================================
    // RÉPONDRE À UN MESSAGE
    // ============================================
    function replyToMessage(messageId, senderName) {
        replyToId = messageId;
        document.getElementById('reponse_a_id').value = messageId;
        document.getElementById('reply-to-name').textContent = senderName || 'Utilisateur';
        document.getElementById('reply-indicator').classList.remove('hidden');
        document.getElementById('message-input').focus();
    }

    function cancelReply() {
        replyToId = null;
        document.getElementById('reponse_a_id').value = '';
        document.getElementById('reply-indicator').classList.add('hidden');
    }

    // ============================================
    // SCROLLER VERS UN MESSAGE
    // ============================================
    function scrollToMessage(messageId) {
        const element = document.querySelector(`.message-item[data-message-id="${messageId}"]`);
        if (element) {
            const container = document.getElementById('messages-container');
            container.scrollTo({ top: element.offsetTop - container.offsetTop - 20, behavior: 'smooth' });
            element.style.transition = 'background-color 0.3s';
            element.style.backgroundColor = 'rgba(212, 175, 55, 0.15)';
            setTimeout(() => element.style.backgroundColor = 'transparent', 2000);
        }
    }

    // ============================================
    // AUTO-RESIZE TEXTAREA
    // ============================================
    function autoResize(textarea) {
        textarea.style.height = 'auto';
        textarea.style.height = Math.min(textarea.scrollHeight, 120) + 'px';
    }

    // ============================================
    // MENU CONTEXTUEL (Appui long)
    // ============================================
    let contextMenuTarget = null;
    let longPressTimer = null;
    let isLongPress = false;
    let isContextMenuOpen = false;

    function initContextMenu() {
        const messageItems = document.querySelectorAll('.message-item');
        
        messageItems.forEach(item => {
            item.removeEventListener('mousedown', handleMouseDown);
            item.removeEventListener('mouseup', handleMouseUp);
            item.removeEventListener('mouseleave', handleMouseLeave);
            item.removeEventListener('touchstart', handleTouchStart);
            item.removeEventListener('touchend', handleTouchEnd);
            item.removeEventListener('touchmove', handleTouchMove);
            
            item.addEventListener('mousedown', handleMouseDown);
            item.addEventListener('mouseup', handleMouseUp);
            item.addEventListener('mouseleave', handleMouseLeave);
            item.addEventListener('touchstart', handleTouchStart);
            item.addEventListener('touchend', handleTouchEnd);
            item.addEventListener('touchmove', handleTouchMove);
        });
    }

    function handleMouseDown(e) {
        if (e.button === 0) {
            isLongPress = false;
            contextMenuTarget = this;
            longPressTimer = setTimeout(() => {
                isLongPress = true;
                isContextMenuOpen = true;
                showContextMenu(e.clientX, e.clientY, this);
            }, 500);
        }
    }

    function handleMouseUp(e) {
        clearTimeout(longPressTimer);
    }

    function handleMouseLeave(e) {
        clearTimeout(longPressTimer);
    }

    function handleTouchStart(e) {
        isLongPress = false;
        contextMenuTarget = this;
        const touch = e.touches[0];
        longPressTimer = setTimeout(() => {
            isLongPress = true;
            isContextMenuOpen = true;
            showContextMenu(touch.clientX, touch.clientY, this);
            document.body.style.overflow = 'hidden';
        }, 500);
    }

    function handleTouchEnd(e) {
        clearTimeout(longPressTimer);
        document.body.style.overflow = '';
        if (isLongPress) {
            e.preventDefault();
            isLongPress = false;
        }
    }

    function handleTouchMove(e) {
        clearTimeout(longPressTimer);
        document.body.style.overflow = '';
    }

    function showContextMenu(x, y, element) {
        const menu = document.getElementById('contextMenu');
        const messageId = element.dataset.messageId;
        const senderId = element.dataset.senderId;
        const currentUserId = {{ Auth::id() }};
        
        const isOwner = senderId == currentUserId;
        
        menu.dataset.messageId = messageId;
        menu.dataset.senderId = senderId;
        
        const editBtn = menu.querySelector('[onclick*="edit"]');
        const deleteBtn = menu.querySelector('[onclick*="delete"]');
        
        if (editBtn) editBtn.style.display = isOwner ? 'flex' : 'none';
        if (deleteBtn) deleteBtn.style.display = isOwner ? 'flex' : 'none';
        
        const menuWidth = 200;
        const menuHeight = 100;
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;
        
        let left = x;
        let top = y;
        
        if (left + menuWidth > viewportWidth) {
            left = viewportWidth - menuWidth - 10;
        }
        if (top + menuHeight > viewportHeight) {
            top = viewportHeight - menuHeight - 10;
        }
        
        menu.style.left = left + 'px';
        menu.style.top = top + 'px';
        menu.classList.remove('hidden');
        isContextMenuOpen = true;
        
        element.classList.add('context-active');
        
        clearTimeout(menu._hideTimer);
    }

    function hideContextMenu() {
        const menu = document.getElementById('contextMenu');
        menu.classList.add('hidden');
        isContextMenuOpen = false;
        
        document.querySelectorAll('.message-item.context-active').forEach(el => {
            el.classList.remove('context-active');
        });
    }

    // ============================================
    // MODIFIER UN MESSAGE
    // ============================================
    function contextMenuAction(action) {
        const menu = document.getElementById('contextMenu');
        const messageId = menu.dataset.messageId;
        const senderId = menu.dataset.senderId;
        const currentUserId = {{ Auth::id() }};
        
        if (senderId != currentUserId && (action === 'edit' || action === 'delete')) {
            showNotification('Vous ne pouvez pas modifier ce message.', 'error');
            hideContextMenu();
            return;
        }
        
        hideContextMenu();
        
        switch(action) {
            case 'edit':
                startEditing(messageId);
                break;
            case 'delete':
                if (confirm('Êtes-vous sûr de vouloir supprimer ce message ?')) {
                    deleteMessage(messageId);
                }
                break;
        }
    }

    function startEditing(messageId) {
        editingMessageId = messageId;
        const messageElement = document.querySelector(`.message-item[data-message-id="${messageId}"]`);
        const textElement = document.getElementById(`message-text-${messageId}`);
        
        if (!textElement || !messageElement) return;
        
        // Récupérer le texte brut sans les balises HTML
        const currentText = textElement.textContent;
        
        const editHtml = `
            <div class="edit-message-form" data-message-id="${messageId}">
                <textarea class="edit-textarea w-full rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2" 
                          style="background-color: var(--color-bg-gray); border: 1px solid var(--color-nav-border); color: var(--color-nav-text); min-height: 60px;"
                          rows="2">${currentText}</textarea>
                <div class="flex items-center justify-end gap-2 mt-2">
                    <button onclick="cancelEdit(${messageId})" 
                            class="px-3 py-1 text-sm rounded-lg transition"
                            style="color: var(--color-nav-text); background-color: var(--color-bg-gray);">
                        Annuler
                    </button>
                    <button onclick="saveEdit(${messageId})" 
                            class="px-3 py-1 text-sm rounded-lg text-white transition"
                            style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                        <i class="fas fa-check mr-1"></i> Enregistrer
                    </button>
                </div>
            </div>
        `;
        
        const contentDiv = messageElement.querySelector('.message-content');
        contentDiv.innerHTML = editHtml;
        
        const textarea = contentDiv.querySelector('.edit-textarea');
        textarea.focus();
        textarea.setSelectionRange(textarea.value.length, textarea.value.length);
        
        messageElement.classList.add('editing');
    }

    function cancelEdit(messageId) {
        editingMessageId = null;
        const messageElement = document.querySelector(`.message-item[data-message-id="${messageId}"]`);
        const contentDiv = messageElement.querySelector('.message-content');
        
        const originalText = document.getElementById(`message-text-${messageId}`)?.textContent || '';
        contentDiv.innerHTML = `<p class="text-sm message-text" id="message-text-${messageId}">${originalText}</p>`;
        
        messageElement.classList.remove('editing');
    }

    function saveEdit(messageId) {
        const messageElement = document.querySelector(`.message-item[data-message-id="${messageId}"]`);
        const textarea = messageElement.querySelector('.edit-textarea');
        const newContent = textarea.value.trim();
        
        if (!newContent) {
            showNotification('Le message ne peut pas être vide.', 'error');
            return;
        }
        
        fetch(`/messagerie/${messageId}`, {
            method: 'PUT',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ contenu: newContent })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const contentDiv = messageElement.querySelector('.message-content');
                contentDiv.innerHTML = `<p class="text-sm message-text" id="message-text-${messageId}">${data.new_content}</p>`;
                
                const dateSpan = messageElement.querySelector('.text-\\[10px\\]');
                if (dateSpan && !dateSpan.innerHTML.includes('(modifié)')) {
                    dateSpan.innerHTML += ' <span class="text-[8px] opacity-40 ml-1">(modifié)</span>';
                }
                
                messageElement.classList.remove('editing');
                editingMessageId = null;
                
                showNotification('Message modifié avec succès !');
            } else {
                showNotification(data.message || 'Erreur lors de la modification.', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur de connexion.', 'error');
        });
    }

    function deleteMessage(messageId) {
        fetch(`/messagerie/${messageId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const element = document.querySelector(`.message-item[data-message-id="${messageId}"]`);
                if (element) {
                    element.style.opacity = '0';
                    element.style.transition = 'opacity 0.3s';
                    setTimeout(() => element.remove(), 300);
                    showNotification('Message supprimé avec succès.');
                }
            } else {
                showNotification(data.message || 'Erreur lors de la suppression.', 'error');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showNotification('Erreur de connexion.', 'error');
        });
    }

    // Fermer le menu contextuel en cliquant ailleurs
    document.addEventListener('click', function(e) {
        const menu = document.getElementById('contextMenu');
        if (menu && !menu.contains(e.target) && !e.target.closest('.message-item')) {
            hideContextMenu();
        }
    });

    document.getElementById('contextMenu').addEventListener('click', function(e) {
        e.stopPropagation();
    });

    // ============================================
    // ENREGISTREMENT AUDIO
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const audioBtn = document.getElementById('audio-record-btn');
        if (audioBtn) {
            audioBtn.addEventListener('click', function() {
                if (isAudioRecording) {
                    toggleAudioRecording();
                    return;
                }
                isAudioRecorded = false;
                document.getElementById('audio_data').value = '';
                document.getElementById('audio-record-btn').innerHTML = '<i class="fas fa-microphone text-sm"></i>';
                document.getElementById('audio-record-btn').style.color = 'var(--color-primary)';
                const modal = document.getElementById('audioRecorderModal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    initAudioRecorder();
                }
            });
        }
    });

    async function initAudioRecorder() {
        try {
            if (audioStream) {
                audioStream.getTracks().forEach(track => track.stop());
                audioStream = null;
            }
            
            audioStream = await navigator.mediaDevices.getUserMedia({ 
                audio: { channelCount: 1, sampleRate: 48000, echoCancellation: true, noiseSuppression: true } 
            });
            
            audioChunks = [];
            audioRecorder = new MediaRecorder(audioStream, {
                mimeType: 'audio/webm;codecs=opus',
                audioBitsPerSecond: 48000
            });

            audioRecorder.ondataavailable = (e) => {
                if (e.data.size > 0) audioChunks.push(e.data);
            };

            audioRecorder.onstop = () => {
                if (audioChunks.length === 0) {
                    closeAudioRecorder();
                    return;
                }
                
                const blob = new Blob(audioChunks, { type: 'audio/opus' });
                const reader = new FileReader();
                reader.onloadend = () => {
                    const base64Data = reader.result;
                    if (base64Data) {
                        document.getElementById('audio_data').value = base64Data;
                        isAudioRecorded = true;
                        document.getElementById('audio-record-btn').innerHTML = '<i class="fas fa-check text-sm" style="color: #10B981;"></i>';
                        document.getElementById('audio-record-btn').title = 'Audio enregistré';
                        document.getElementById('audio-record-btn').style.color = '#10B981';
                        showNotification('Audio enregistré avec succès !');
                    } else {
                        showNotification('Erreur lors de l\'enregistrement audio', 'error');
                    }
                    closeAudioRecorder();
                };
                reader.readAsDataURL(blob);
                
                if (audioStream) {
                    audioStream.getTracks().forEach(track => track.stop());
                    audioStream = null;
                }
                isAudioRecording = false;
                clearInterval(audioTimer);
                const startBtn = document.getElementById('audioStartStop');
                if (startBtn) {
                    startBtn.innerHTML = '<i class="fas fa-microphone text-2xl"></i>';
                }
                const progress = document.getElementById('audioProgress');
                if (progress) {
                    progress.style.width = '0%';
                }
                const timeCurrent = document.getElementById('audioTimeCurrent');
                if (timeCurrent) {
                    timeCurrent.textContent = '00:00';
                }
            };

            audioRecorder.onstart = () => {
                audioStartTime = Date.now();
                audioTimer = setInterval(updateAudioTimer, 100);
                isAudioRecording = true;
                const startBtn = document.getElementById('audioStartStop');
                if (startBtn) {
                    startBtn.innerHTML = '<i class="fas fa-stop text-2xl"></i>';
                }
            };

            const startBtn = document.getElementById('audioStartStop');
            if (startBtn) {
                startBtn.onclick = toggleAudioRecording;
            }

        } catch (error) {
            console.error('Erreur audio:', error);
            alert('Impossible d\'accéder au microphone: ' + error.message);
            closeAudioRecorder();
        }
    }

    function toggleAudioRecording() {
        if (isAudioRecording) {
            if (audioRecorder && audioRecorder.state === 'recording') {
                audioRecorder.stop();
            }
        } else {
            if (audioRecorder && audioRecorder.state === 'inactive') {
                audioRecorder.start(100);
            }
        }
    }

    function updateAudioTimer() {
        const elapsed = (Date.now() - audioStartTime) / 1000;
        const minutes = Math.floor(elapsed / 60);
        const seconds = Math.floor(elapsed % 60);
        const timeCurrent = document.getElementById('audioTimeCurrent');
        if (timeCurrent) {
            timeCurrent.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }
        const progress = document.getElementById('audioProgress');
        if (progress) {
            progress.style.width = Math.min((elapsed / AUDIO_MAX_DURATION) * 100, 100) + '%';
        }
        
        const bars = document.querySelectorAll('.audio-bar');
        bars.forEach(bar => bar.style.height = (Math.random() * 40 + 5) + 'px');
        
        if (elapsed >= AUDIO_MAX_DURATION) {
            if (audioRecorder && audioRecorder.state === 'recording') {
                audioRecorder.stop();
            }
            showNotification('Durée maximale d\'enregistrement atteinte (30s)');
        }
    }

    function closeAudioRecorder() {
        if (isAudioRecording) {
            if (audioRecorder && audioRecorder.state === 'recording') {
                audioRecorder.stop();
            }
        }
        const modal = document.getElementById('audioRecorderModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // ============================================
    // ENREGISTREMENT VIDÉO
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const videoBtn = document.getElementById('video-record-btn');
        if (videoBtn) {
            videoBtn.addEventListener('click', function() {
                if (isVideoRecording) {
                    toggleVideoRecording();
                    return;
                }
                isVideoRecorded = false;
                document.getElementById('video_data').value = '';
                document.getElementById('video-record-btn').innerHTML = '<i class="fas fa-video text-sm"></i>';
                document.getElementById('video-record-btn').style.color = 'var(--color-primary)';
                
                const modal = document.getElementById('videoRecorderModal');
                if (modal) {
                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                    initVideoRecorder();
                }
            });
        }
    });

    async function initVideoRecorder() {
        try {
            if (videoStream) {
                videoStream.getTracks().forEach(track => track.stop());
                videoStream = null;
            }
            
            videoStream = await navigator.mediaDevices.getUserMedia({
                video: {
                    width: { ideal: 640 },
                    height: { ideal: 480 },
                    frameRate: { ideal: 30 }
                },
                audio: {
                    echoCancellation: true,
                    noiseSuppression: true
                }
            });
            
            const preview = document.getElementById('videoPreview');
            if (preview) {
                preview.srcObject = videoStream;
                await preview.play();
            }
            
            videoChunks = [];
            videoRecorder = new MediaRecorder(videoStream, {
                mimeType: 'video/webm;codecs=vp9,opus',
                videoBitsPerSecond: 1000000,
                audioBitsPerSecond: 64000
            });

            videoRecorder.ondataavailable = (e) => {
                if (e.data.size > 0) {
                    videoChunks.push(e.data);
                }
            };

            videoRecorder.onstop = () => {
                if (videoChunks.length === 0) {
                    closeVideoRecorder();
                    return;
                }
                
                const blob = new Blob(videoChunks, { type: 'video/webm' });
                const reader = new FileReader();
                reader.onloadend = () => {
                    const base64Data = reader.result;
                    if (base64Data && base64Data.startsWith('data:video/webm;base64,')) {
                        document.getElementById('video_data').value = base64Data;
                        isVideoRecorded = true;
                        const btn = document.getElementById('video-record-btn');
                        if (btn) {
                            btn.innerHTML = '<i class="fas fa-check text-sm" style="color: #10B981;"></i>';
                            btn.title = 'Vidéo enregistrée';
                            btn.style.color = '#10B981';
                        }
                        showNotification('Vidéo enregistrée avec succès !');
                    } else {
                        showNotification('Erreur lors de l\'enregistrement vidéo', 'error');
                    }
                    closeVideoRecorder();
                };
                reader.readAsDataURL(blob);
                
                if (videoStream) {
                    videoStream.getTracks().forEach(track => track.stop());
                    videoStream = null;
                }
                isVideoRecording = false;
                clearInterval(videoTimer);
                const startBtn = document.getElementById('videoStartStop');
                if (startBtn) {
                    startBtn.innerHTML = '<i class="fas fa-video text-2xl"></i>';
                }
                const overlay = document.getElementById('videoRecordingOverlay');
                if (overlay) {
                    overlay.classList.add('hidden');
                }
                const progress = document.getElementById('videoProgress');
                if (progress) {
                    progress.style.width = '0%';
                }
                const timeCurrent = document.getElementById('videoTimeCurrent');
                if (timeCurrent) {
                    timeCurrent.textContent = '00:00';
                }
                
                const preview = document.getElementById('videoPreview');
                if (preview) {
                    preview.srcObject = null;
                }
            };

            videoRecorder.onstart = () => {
                videoStartTime = Date.now();
                videoTimer = setInterval(updateVideoTimer, 100);
                isVideoRecording = true;
                const startBtn = document.getElementById('videoStartStop');
                if (startBtn) {
                    startBtn.innerHTML = '<i class="fas fa-stop text-2xl"></i>';
                }
                const overlay = document.getElementById('videoRecordingOverlay');
                if (overlay) {
                    overlay.classList.remove('hidden');
                }
            };

            const startBtn = document.getElementById('videoStartStop');
            if (startBtn) {
                startBtn.onclick = toggleVideoRecording;
            }

        } catch (error) {
            console.error('Erreur vidéo:', error);
            alert('Impossible d\'accéder à la caméra: ' + error.message);
            closeVideoRecorder();
        }
    }

    function toggleVideoRecording() {
        if (isVideoRecording) {
            if (videoRecorder && videoRecorder.state === 'recording') {
                videoRecorder.stop();
            }
        } else {
            if (videoRecorder && videoRecorder.state === 'inactive') {
                videoRecorder.start(100);
            }
        }
    }

    function updateVideoTimer() {
        const elapsed = (Date.now() - videoStartTime) / 1000;
        const minutes = Math.floor(elapsed / 60);
        const seconds = Math.floor(elapsed % 60);
        const timeCurrent = document.getElementById('videoTimeCurrent');
        if (timeCurrent) {
            timeCurrent.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;
        }
        
        const progress = document.getElementById('videoProgress');
        if (progress) {
            progress.style.width = Math.min((elapsed / VIDEO_MAX_DURATION) * 100, 100) + '%';
        }
        
        if (elapsed >= VIDEO_MAX_DURATION) {
            if (videoRecorder && videoRecorder.state === 'recording') {
                videoRecorder.stop();
            }
            showNotification('Durée maximale d\'enregistrement atteinte (60s)');
        }
    }

    function closeVideoRecorder() {
        if (isVideoRecording) {
            if (videoRecorder && videoRecorder.state === 'recording') {
                videoRecorder.stop();
            }
        }
        const modal = document.getElementById('videoRecorderModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        const preview = document.getElementById('videoPreview');
        if (preview && preview.srcObject) {
            preview.srcObject.getTracks().forEach(track => track.stop());
            preview.srcObject = null;
        }
    }

    // ============================================
    // PRÉVISUALISATION D'IMAGE
    // ============================================
    function openImagePreview(src, title, downloadUrl) {
        const previewImage = document.getElementById('previewImage');
        const previewTitle = document.getElementById('previewTitle');
        const previewDownloadLink = document.getElementById('previewDownloadLink');
        const modal = document.getElementById('imagePreviewModal');
        
        if (previewImage) previewImage.src = src;
        if (previewTitle) previewTitle.textContent = title || 'Aperçu';
        if (previewDownloadLink) {
            previewDownloadLink.href = downloadUrl || src;
            previewDownloadLink.download = title || 'image';
        }
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    }

    function closeImagePreview() {
        const modal = document.getElementById('imagePreviewModal');
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    }

    // ============================================
    // ERREURS & NOTIFICATIONS
    // ============================================
    function showError(message) {
        const container = document.getElementById('form-errors');
        const errorText = document.getElementById('error-text');
        if (container && errorText) {
            errorText.textContent = message;
            container.classList.remove('hidden');
        }
    }

    function hideError() {
        const container = document.getElementById('form-errors');
        if (container) {
            container.classList.add('hidden');
        }
    }

    function showNotification(message, type = 'success') {
        const colors = {
            success: 'linear-gradient(135deg, #10B981, #059669)',
            error: 'linear-gradient(135deg, #EF4444, #DC2626)',
            warning: 'linear-gradient(135deg, #F59E0B, #D97706)'
        };
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle'
        };
        
        const notification = document.createElement('div');
        notification.className = 'fixed bottom-4 right-4 px-4 py-3 rounded-lg shadow-lg text-white z-[9999]';
        notification.style.background = colors[type] || colors.success;
        notification.style.zIndex = '9999';
        notification.style.animation = 'slideInRight 0.3s ease-out';
        notification.innerHTML = `<i class="fas ${icons[type] || icons.success} mr-2"></i>${message}`;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.5s';
            setTimeout(() => notification.remove(), 500);
        }, 3000);
    }

    // ============================================
    // INITIALISATION
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('messages-container');
        if (container) {
            container.scrollTop = container.scrollHeight;
        }
        
        initContextMenu();
    });

    // Fermer avec Echap
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (editingMessageId) {
                cancelEdit(editingMessageId);
            }
            closeImagePreview();
            closeAudioRecorder();
            closeVideoRecorder();
            hideContextMenu();
        }
    });
    </script>
</x-app-layout>