<div class="message-item flex {{ $message->id_expediteur === Auth::id() ? 'justify-end' : 'justify-start' }}"
     data-message-id="{{ $message->id }}"
     data-sender-id="{{ $message->id_expediteur }}"
     data-sender-name="{{ $message->expediteur->prenom ?? '' }}">
    
    <div class="max-w-[80%] {{ $message->id_expediteur === Auth::id() ? 'order-2' : 'order-1' }}">
        <!-- Bulle du message -->
        <div class="rounded-xl px-4 py-2.5 shadow-sm message-bubble {{ $message->id_expediteur === Auth::id() ? 'text-white' : '' }}"
             style="{{ $message->id_expediteur === Auth::id() 
                 ? 'background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));' 
                 : 'background-color: var(--color-secondary-light); color: var(--color-nav-text);' }}">
            
            <!-- Contenu du message avec gestion des retours à la ligne -->
            <div class="message-content" data-message-id="{{ $message->id }}">
                @if($message->contenu)
                    <p class="text-sm message-text" id="message-text-{{ $message->id }}">
                        {!! nl2br(e($message->contenu)) !!}
                    </p>
                @endif
            </div>
            
            <!-- ✅ Pièces jointes avec vérification des chemins -->
            @if($message->piecesJointes->count() > 0)
                <div class="mt-2 space-y-2">
                    @foreach($message->piecesJointes as $piece)
                        @php
                            // ✅ Vérifier l'existence du fichier dans le bon dossier
                            $fileExists = Storage::disk('public')->exists($piece->chemin_stockage);
                            // ✅ Construire l'URL correcte
                            $fileUrl = $fileExists ? asset('storage/' . $piece->chemin_stockage) : null;
                        @endphp
                        
                        @if($piece->type_media === 'image' && $fileExists)
                            <div class="rounded-lg overflow-hidden max-w-xs">
                                <img src="{{ $fileUrl }}" 
                                     alt="{{ $piece->nom_media }}"
                                     class="w-full h-auto max-h-64 object-cover cursor-pointer"
                                     style="display: block; border-radius: 8px;"
                                     onclick="openImagePreview('{{ $fileUrl }}', '{{ $piece->nom_media }}', '{{ route('messagerie.download-piece', $piece->id) }}')"
                                     loading="lazy">
                                <div class="flex items-center justify-between mt-1 px-1">
                                    <span class="text-[10px] opacity-60">{{ $piece->nom_media }}</span>
                                    <span class="text-[10px] opacity-60">{{ number_format($piece->taille / 1024, 1) }} KB</span>
                                </div>
                            </div>
                            
                        @elseif($piece->type_media === 'video' && $fileExists)
                            <div class="rounded-lg overflow-hidden max-w-xs">
                                <video controls class="w-full max-h-64" preload="metadata" style="display: block; background: #000; border-radius: 8px;">
                                    <source src="{{ $fileUrl }}" type="video/mp4">
                                    <source src="{{ $fileUrl }}" type="video/webm">
                                    <source src="{{ $fileUrl }}" type="video/avi">
                                    <source src="{{ $fileUrl }}" type="video/mov">
                                    Votre navigateur ne supporte pas la lecture de vidéo.
                                </video>
                                <div class="flex items-center justify-between mt-1 px-1">
                                    <span class="text-[10px] opacity-60">{{ $piece->nom_media }}</span>
                                    <span class="text-[10px] opacity-60">{{ number_format($piece->taille / 1024, 1) }} KB</span>
                                </div>
                            </div>
                            
                        @elseif($piece->type_media === 'audio' && $fileExists)
                            <div class="rounded-lg p-2 max-w-xs" style="background: {{ $message->id_expediteur === Auth::id() ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)' }};">
                                <audio controls class="w-full" preload="metadata">
                                    <source src="{{ $fileUrl }}" type="audio/opus">
                                    <source src="{{ $fileUrl }}" type="audio/webm">
                                    <source src="{{ $fileUrl }}" type="audio/mpeg">
                                    <source src="{{ $fileUrl }}" type="audio/wav">
                                    Votre navigateur ne supporte pas la lecture audio.
                                </audio>
                                <div class="flex items-center justify-between mt-1 px-1">
                                    <span class="text-[10px] opacity-60">{{ $piece->nom_media }}</span>
                                    <span class="text-[10px] opacity-60">{{ number_format($piece->taille / 1024, 1) }} KB</span>
                                </div>
                            </div>
                            
                        @elseif($fileExists)
                            <!-- ✅ Document avec style amélioré -->
                            <div class="file-attachment"
                                 style="background: {{ $message->id_expediteur === Auth::id() ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)' }};">
                                <div class="file-icon" style="color: {{ $message->id_expediteur === Auth::id() ? 'white' : 'var(--color-primary)' }};">
                                    <i class="fas fa-file-pdf"></i>
                                </div>
                                <div class="file-info">
                                    <div class="file-name" style="color: {{ $message->id_expediteur === Auth::id() ? 'white' : 'var(--color-nav-text)' }};">
                                        {{ $piece->nom_media }}
                                    </div>
                                    <div class="file-size" style="color: {{ $message->id_expediteur === Auth::id() ? 'rgba(255,255,255,0.6)' : 'var(--color-nav-text)' }};">
                                        {{ number_format($piece->taille / 1024, 1) }} KB
                                    </div>
                                </div>
                                <a href="{{ route('messagerie.download-piece', $piece->id) }}" 
                                   target="_blank"
                                   class="file-download"
                                   style="color: {{ $message->id_expediteur === Auth::id() ? 'white' : 'var(--color-primary)' }};">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif
        </div>
        
        <!-- Date, statut et bouton Répondre -->
        <div class="flex items-center gap-2 mt-1 {{ $message->id_expediteur === Auth::id() ? 'justify-end' : 'justify-start' }}">
            <span class="text-[10px]" style="color: var(--color-nav-text); opacity: 0.5;">
                {{ $message->created_at->format('d/m/Y H:i') }}
                @if($message->id_expediteur === Auth::id())
                    @if($message->lu)
                        <i class="fas fa-check-double ml-1 text-blue-500"></i>
                    @else
                        <i class="fas fa-check ml-1"></i>
                    @endif
                @endif
                @if($message->has_pieces_jointes)
                    <i class="fas fa-paperclip ml-1"></i>
                @endif
                @if($message->created_at != $message->updated_at)
                    <span class="text-[8px] opacity-40 ml-1">(modifié)</span>
                @endif
            </span>
            
            <button onclick="replyToMessage({{ $message->id }}, '{{ addslashes($message->expediteur->prenom ?? '') }}')" 
                    class="text-[10px] transition hover:scale-110"
                    style="color: var(--color-primary);"
                    title="Répondre à ce message">
                <i class="fas fa-reply"></i>
            </button>
        </div>
    </div>
    
    @if($message->id_expediteur !== Auth::id())
        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0 ml-2 order-2"
             style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            {{ strtoupper(substr($message->expediteur->prenom ?? '', 0, 1) . substr($message->expediteur->nom ?? '', 0, 1)) }}
        </div>
    @endif
</div>