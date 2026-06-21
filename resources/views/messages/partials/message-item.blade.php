@php
    use Illuminate\Support\Str;
    
    $currentUserId = Auth::id();
    $isMine = $message->id_expediteur === $currentUserId;
    
    // ============================================
    // DÉTERMINATION DES RÔLES À PARTIR DES RELATIONS
    // ============================================
    $estVendeur = false;
    $estAcheteur = false;
    $estFournisseur = false;
    $estClient = false;
    $vendeurId = null;
    $acheteurId = null;
    
    // Si le message a une annonce, on détermine qui est le vendeur
    if (isset($message->annonce) && $message->annonce) {
        // Le vendeur est celui qui a créé l'annonce (id_user)
        $vendeurId = $message->annonce->id_user;
        
        // Si une commande est liée, on utilise ses relations
        if ($message->commande) {
            $estVendeur = $message->commande->id_vendeur === $currentUserId;
            $estAcheteur = $message->commande->id_acheteur === $currentUserId;
            $acheteurId = $message->commande->id_acheteur;
        } else {
            // Sinon, on utilise l'annonce pour déterminer
            $estVendeur = $vendeurId === $currentUserId;
            // L'acheteur est le destinataire du message (celui qui reçoit le message du vendeur)
            $estAcheteur = $message->id_destinataire === $currentUserId && $vendeurId !== $currentUserId;
            $acheteurId = $message->id_destinataire;
        }
        
        // Alias pour plus de clarté
        $estFournisseur = $estVendeur;
        $estClient = $estAcheteur;
    }
    
    // ============================================
    // FONCTION POUR RÉCUPÉRER LES CARACTÉRISTIQUES
    // Vérification pour éviter la redéclaration
    // ============================================
    if (!function_exists('getMessageCaracteristiques')) {
        function getMessageCaracteristiques($annonce) {
            $caracteristiques = [];
            
            // Type animal
            if ($annonce->type === 'animal' && $annonce->animal) {
                $animal = $annonce->animal;
                if ($animal->race) {
                    $caracteristiques[] = ['label' => 'Race', 'value' => $animal->race->nom ?? 'N/A'];
                }
                if ($animal->age_mois) {
                    $caracteristiques[] = ['label' => 'Âge', 'value' => $animal->age_mois . ' mois'];
                }
                if ($animal->sexe) {
                    $caracteristiques[] = ['label' => 'Sexe', 'value' => $animal->sexe === 'M' ? 'Mâle' : 'Femelle'];
                }
                if ($animal->description) {
                    $caracteristiques[] = ['label' => 'Description', 'value' => Str::limit(strip_tags($animal->description), 60)];
                }
            }
            
            // Type nourriture
            if ($annonce->type === 'nourriture' && $annonce->nourriture) {
                $nourriture = $annonce->nourriture;
                if ($nourriture->type) {
                    $caracteristiques[] = ['label' => 'Type', 'value' => ucfirst(str_replace('_', ' ', $nourriture->type))];
                }
                if ($nourriture->nom) {
                    $caracteristiques[] = ['label' => 'Nom', 'value' => $nourriture->nom];
                }
                if ($nourriture->description) {
                    $caracteristiques[] = ['label' => 'Description', 'value' => Str::limit(strip_tags($nourriture->description), 60)];
                }
            }
            
            // Type accessoire
            if ($annonce->type === 'accessoire' && $annonce->accessoire) {
                $accessoire = $annonce->accessoire;
                if ($accessoire->categorie) {
                    $caracteristiques[] = ['label' => 'Catégorie', 'value' => $accessoire->categorie];
                }
                if ($accessoire->nom) {
                    $caracteristiques[] = ['label' => 'Nom', 'value' => $accessoire->nom];
                }
                if ($accessoire->description) {
                    $caracteristiques[] = ['label' => 'Description', 'value' => Str::limit(strip_tags($accessoire->description), 60)];
                }
            }
            
            // Type escrement
            if ($annonce->type === 'escrement' && $annonce->escrement) {
                $escrement = $annonce->escrement;
                if ($escrement->nom) {
                    $caracteristiques[] = ['label' => 'Type', 'value' => $escrement->nom];
                }
                if ($escrement->description) {
                    $caracteristiques[] = ['label' => 'Description', 'value' => Str::limit(strip_tags($escrement->description), 60)];
                }
            }
            
            // Quantité en stock - affichée pour TOUS
            if ($annonce->quantite) {
                $caracteristiques[] = ['label' => 'Quantité disponible', 'value' => $annonce->quantite];
            }
            
            // Description de l'annonce
            if ($annonce->description) {
                $caracteristiques[] = ['label' => 'Description', 'value' => Str::limit(strip_tags($annonce->description), 60)];
            }
            
            // Auteur de l'annonce
            $auteurNom = $annonce->auteur ? ($annonce->auteur->prenom ?? '') . ' ' . ($annonce->auteur->nom ?? '') : 'Vendeur';
            $caracteristiques[] = ['label' => 'Vendeur', 'value' => $auteurNom];
            
            return $caracteristiques;
        }
    }
    
    // Récupérer les caractéristiques avec la fonction
    $caracteristiques = [];
    if (isset($message->annonce) && $message->annonce) {
        $caracteristiques = getMessageCaracteristiques($message->annonce);
    }
@endphp

<div class="message-item flex {{ $isMine ? 'justify-end' : 'justify-start' }}"
     data-message-id="{{ $message->id }}"
     data-sender-id="{{ $message->id_expediteur }}"
     data-sender-name="{{ $message->expediteur->prenom ?? '' }}">
    
    <div class="max-w-[85%] {{ $isMine ? 'order-2' : 'order-1' }}">
        
        <!-- ============================================ -->
        <!-- CARTE UNIFIÉE - MÊME AFFICHAGE POUR TOUS -->
        <!-- ============================================ -->
        @if($message->id_annonce && $message->annonce)
            @php
                $annonce = $message->annonce;
                $annonceRoute = match($annonce->type) {
                    'animal' => route('annonces.animaux.show', $annonce->id),
                    'nourriture' => route('annonces.aliments.show', $annonce->id),
                    'accessoire' => route('annonces.accessoires.show', $annonce->id),
                    'escrement' => route('annonces.escrements.show', $annonce->id),
                    default => route('annonces.show', $annonce->id),
                };
                
                // Récupérer UNE SEULE image
                $imagePrincipale = $annonce->piecesJointes->where('est_principale', true)->first() ?? $annonce->piecesJointes->first();
                $imageExists = $imagePrincipale && $imagePrincipale->chemin_stockage && Storage::disk('public')->exists($imagePrincipale->chemin_stockage);
                
                // Styles unifiés - MÊMES pour tous
                $icon = match($annonce->type) {
                    'animal' => 'fa-paw',
                    'nourriture' => 'fa-apple-alt',
                    'accessoire' => 'fa-tools',
                    'escrement' => 'fa-leaf',
                    default => 'fa-tag',
                };
                
                $typeLabel = match($annonce->type) {
                    'animal' => 'Animal',
                    'nourriture' => 'Aliment',
                    'accessoire' => 'Accessoire',
                    'escrement' => 'Engrais',
                    default => 'Annonce',
                };
                
                // Statut de la commande si elle existe
                $statutCommande = $message->commande ? $message->commande->statut_commande : null;
                $statutLabel = match($statutCommande) {
                    'validee' => 'Validée',
                    'livree' => 'Livrée',
                    'annulee' => 'Annulée',
                    'en_attente' => 'En attente',
                    default => 'En attente',
                };
                $statutColor = match($statutCommande) {
                    'validee' => '#4CAF50',
                    'livree' => '#2196F3',
                    'annulee' => '#f44336',
                    default => '#FF9800',
                };
            @endphp
            
            <!-- Carte d'annonce - STYLE UNIFIÉ -->
            <div class="annonce-card-message rounded-xl overflow-hidden mb-2 shadow-md" 
                 style="background-color: var(--color-bg-white); 
                        border: 2px solid var(--color-primary);">
                <div class="flex flex-col p-4">
                    
                    <!-- ============================================ -->
                    <!-- EN-TÊTE - Selon le type de message -->
                    <!-- ============================================ -->
                    @if($message->est_demande_commande)
                        <div class="text-center mb-3 p-2 rounded-lg" style="background: linear-gradient(135deg, #FFF8E1, #FFECB3); border: 1px solid #FFD54F;">
                            <span class="font-bold text-sm" style="color: #F57F17;">
                                <i class="fas fa-hand-point-right mr-2"></i> Demande de commande
                            </span>
                        </div>
                    @endif
                    
                    @if($message->est_demande_paiement)
                        <div class="text-center mb-3 p-2 rounded-lg" style="background: linear-gradient(135deg, #E3F2FD, #BBDEFB); border: 1px solid #64B5F6;">
                            <span class="font-bold text-sm" style="color: #1565C0;">
                                <i class="fas fa-hand-point-right mr-2"></i> Demande de paiement
                            </span>
                        </div>
                    @endif
                    
                    <!-- ============================================ -->
                    <!-- IMAGE + TITRE + PRIX - UNIFIÉ -->
                    <!-- ============================================ -->
                    <div class="flex items-start gap-4">
                        <!-- Image -->
                        <div class="w-24 h-24 rounded-lg overflow-hidden flex-shrink-0 border-2" style="border-color: var(--color-primary);">
                            @if($imageExists)
                                <img src="{{ asset('storage/' . $imagePrincipale->chemin_stockage) }}" 
                                     alt="{{ $annonce->titre }}" 
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="background-color: var(--color-secondary-light);">
                                    <i class="fas {{ $icon }} text-3xl" style="color: var(--color-primary);"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Titre et prix -->
                        <div class="flex-1 min-w-0">
                            <h5 class="font-bold text-base truncate" style="color: var(--color-primary-dark);">
                                {{ $annonce->titre }}
                            </h5>
                            <div class="flex items-center gap-2 flex-wrap mt-1">
                                <span class="text-lg font-bold" style="color: var(--color-primary);">
                                    {{ number_format($annonce->prix, 0, ',', ' ') }} FCFA
                                </span>
                                <span class="text-xs px-3 py-1 rounded-full font-medium" style="background: var(--color-secondary-light); color: var(--color-nav-text);">
                                    <i class="fas {{ $icon }} mr-1"></i> {{ $typeLabel }}
                                </span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- ============================================ -->
                    <!-- CARACTÉRISTIQUES - UNIFIÉES POUR TOUS -->
                    <!-- ============================================ -->
                    @if(count($caracteristiques) > 0)
                        <div class="grid grid-cols-2 gap-2 mt-3 p-3 rounded-lg" style="background-color: var(--color-secondary-light);">
                            @foreach($caracteristiques as $carac)
                                <div class="flex items-center gap-1 text-sm">
                                    <span style="color: var(--color-nav-text); opacity: 0.7;">{{ $carac['label'] }}:</span>
                                    <span class="font-medium" style="color: var(--color-nav-text);">{{ $carac['value'] }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <!-- ============================================ -->
                    <!-- INFOS COMMANDE - UNIFIÉES POUR TOUS -->
                    <!-- ============================================ -->
                    @if($message->commande)
                        @php $commande = $message->commande; @endphp
                        <div class="mt-3 p-3 rounded-lg" style="background-color: var(--color-secondary-light); border: 1px solid var(--color-primary);">
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div style="color: var(--color-nav-text);">
                                    <i class="fas fa-hashtag mr-1" style="color: var(--color-primary);"></i>
                                    Commande #{{ $commande->id }}
                                </div>
                                <div style="color: var(--color-nav-text);">
                                    <i class="fas fa-box mr-1" style="color: var(--color-primary);"></i>
                                    Qté: {{ $commande->quantite }}
                                </div>
                                <div style="color: var(--color-nav-text);">
                                    <i class="fas fa-money-bill-wave mr-1" style="color: var(--color-primary);"></i>
                                    Total: {{ number_format($commande->montant_total, 0, ',', ' ') }} FCFA
                                </div>
                                @if($commande->reduction > 0)
                                    <div style="color: #4CAF50;">
                                        <i class="fas fa-tag mr-1"></i>
                                        Réduction: -{{ number_format($commande->reduction, 0, ',', ' ') }} FCFA
                                    </div>
                                @endif
                                <div class="col-span-2 font-bold text-center mt-1 p-2 rounded" style="color: var(--color-primary); background-color: var(--color-bg-gray);">
                                    <i class="fas fa-credit-card mr-1"></i>
                                    À payer: {{ number_format($commande->montant_ajuste, 0, ',', ' ') }} FCFA
                                </div>
                                <div class="col-span-2 text-center text-xs mt-1" style="color: var(--color-nav-text); opacity: 0.7;">
                                    Statut: 
                                    <span class="font-medium" style="color: {{ $statutColor }};">
                                        {{ $statutLabel }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    
                    <!-- ============================================ -->
                    <!-- MESSAGE DE SÉCURITÉ - UNIFIÉ -->
                    <!-- ============================================ -->
                    @if($message->est_demande_commande || $message->est_demande_paiement)
                        <div class="mt-3 text-xs px-3 py-2 rounded-lg text-center" 
                             style="background: rgba(255,193,7,0.1); color: #F57F17; border: 1px solid #FFD54F;">
                            <i class="fas fa-shield-alt mr-1"></i> 
                            {{ $message->contenu }}
                        </div>
                    @endif
                    
                    <!-- ============================================ -->
                    <!-- BOUTONS - SELON LE RÔLE UNIQUEMENT -->
                    <!-- ============================================ -->
                    <div class="flex flex-wrap items-center gap-2 mt-4">
                        
                        <!-- Bouton "Voir l'annonce" - TOUS -->
                        <a href="{{ $annonceRoute }}" 
                           class="px-4 py-2 rounded-lg text-sm font-semibold text-white transition hover:scale-105"
                           style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                            <i class="fas fa-eye mr-1"></i> Voir l'annonce
                        </a>
                        
                        <!-- ============================================ -->
                        <!-- MESSAGE NORMAL AVEC ANNONCE -->
                        <!-- ============================================ -->
                        @if(!$message->est_demande_commande && !$message->est_demande_paiement)
                            <!-- Initier commande - UNIQUEMENT pour le VENDEUR -->
                            @if($estVendeur)
                                <button onclick="initierCommande({{ $annonce->id }}, {{ $message->id_expediteur }})" 
                                        class="px-4 py-2 rounded-lg text-sm font-semibold text-white transition hover:scale-105"
                                        style="background: linear-gradient(135deg, #FF9800, #F57C00);">
                                    <i class="fas fa-shopping-cart mr-1"></i> Initier commande
                                </button>
                            @endif
                        @endif
                        
                        <!-- ============================================ -->
                        <!-- DEMANDE DE COMMANDE -->
                        <!-- ============================================ -->
                        @if($message->est_demande_commande)
                            <!-- Commander maintenant - UNIQUEMENT pour l'ACHETEUR -->
                            @if($estAcheteur)
                                <button onclick="ouvrirFormulaireCommande({{ $annonce->id }}, {{ $message->id }})" 
                                        class="px-4 py-2 rounded-lg text-sm font-bold text-white transition hover:scale-105"
                                        style="background: linear-gradient(135deg, #4CAF50, #2E7D32);">
                                    <i class="fas fa-shopping-cart mr-1"></i> Commander maintenant
                                </button>
                            @endif
                        @endif
                        
                        <!-- ============================================ -->
                        <!-- DEMANDE DE PAIEMENT -->
                        <!-- ============================================ -->
                        @if($message->est_demande_paiement)
                            <!-- Actions pour le VENDEUR -->
                            @if($estVendeur && $message->commande)
                                <a href="{{ route('commandes.show', $message->commande->id) }}" 
                                   class="px-4 py-2 rounded-lg text-sm font-semibold text-white transition hover:scale-105"
                                   style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                                    <i class="fas fa-eye mr-1"></i> Voir la commande
                                </a>
                                <button onclick="ajusterPaiement({{ $message->commande->id }})" 
                                        class="px-4 py-2 rounded-lg text-sm font-semibold text-white transition hover:scale-105"
                                        style="background: linear-gradient(135deg, #FF9800, #F57C00);">
                                    <i class="fas fa-edit mr-1"></i> Ajuster le paiement
                                </button>
                            @endif
                            
                            <!-- Payer maintenant - UNIQUEMENT pour l'ACHETEUR -->
                            @if($estAcheteur && $message->commande)
                                <a href="#" 
                                   class="px-6 py-2.5 rounded-lg text-sm font-bold text-white transition hover:scale-105"
                                   style="background: linear-gradient(135deg, #f44336, #c62828);">
                                    <i class="fas fa-credit-card mr-1"></i> Payer maintenant
                                </a>
                            @endif
                        @endif
                        
                    </div>
                </div>
            </div>
        @endif
        
        <!-- ============================================ -->
        <!-- BULLE DU MESSAGE - Uniquement pour les messages normaux -->
        <!-- ============================================ -->
        @if(!$message->est_demande_commande && !$message->est_demande_paiement)
            <div class="rounded-xl px-4 py-2.5 shadow-sm message-bubble {{ $isMine ? 'text-white' : '' }}"
                 style="{{ $isMine 
                     ? 'background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));' 
                     : 'background-color: var(--color-secondary-light); color: var(--color-nav-text);' }}">
                
                <div class="message-content" data-message-id="{{ $message->id }}">
                    @if($message->contenu)
                        <p class="text-sm message-text" id="message-text-{{ $message->id }}">
                            {!! nl2br(e($message->contenu)) !!}
                        </p>
                    @endif
                </div>
                
                <!-- Pièces jointes -->
                @if($message->piecesJointes->count() > 0)
                    <div class="mt-2 space-y-2">
                        @foreach($message->piecesJointes as $piece)
                            @php
                                $fileExists = Storage::disk('public')->exists($piece->chemin_stockage);
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
                                        Votre navigateur ne supporte pas la lecture de vidéo.
                                    </video>
                                    <div class="flex items-center justify-between mt-1 px-1">
                                        <span class="text-[10px] opacity-60">{{ $piece->nom_media }}</span>
                                        <span class="text-[10px] opacity-60">{{ number_format($piece->taille / 1024, 1) }} KB</span>
                                    </div>
                                </div>
                            @elseif($piece->type_media === 'audio' && $fileExists)
                                <div class="rounded-lg p-2 max-w-xs" style="background: {{ $isMine ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)' }};">
                                    <audio controls class="w-full" preload="metadata">
                                        <source src="{{ $fileUrl }}" type="audio/opus">
                                        <source src="{{ $fileUrl }}" type="audio/webm">
                                        <source src="{{ $fileUrl }}" type="audio/mpeg">
                                        Votre navigateur ne supporte pas la lecture audio.
                                    </audio>
                                    <div class="flex items-center justify-between mt-1 px-1">
                                        <span class="text-[10px] opacity-60">{{ $piece->nom_media }}</span>
                                        <span class="text-[10px] opacity-60">{{ number_format($piece->taille / 1024, 1) }} KB</span>
                                    </div>
                                </div>
                            @elseif($fileExists)
                                <div class="file-attachment"
                                     style="background: {{ $isMine ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.05)' }};">
                                    <div class="file-icon" style="color: {{ $isMine ? 'white' : 'var(--color-primary)' }};">
                                        <i class="fas fa-file-pdf"></i>
                                    </div>
                                    <div class="file-info">
                                        <div class="file-name" style="color: {{ $isMine ? 'white' : 'var(--color-nav-text)' }};">
                                            {{ $piece->nom_media }}
                                        </div>
                                        <div class="file-size" style="color: {{ $isMine ? 'rgba(255,255,255,0.6)' : 'var(--color-nav-text)' }};">
                                            {{ number_format($piece->taille / 1024, 1) }} KB
                                        </div>
                                    </div>
                                    <a href="{{ route('messagerie.download-piece', $piece->id) }}" 
                                       target="_blank"
                                       class="file-download"
                                       style="color: {{ $isMine ? 'white' : 'var(--color-primary)' }};">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
            
            <!-- Date, statut et bouton Répondre -->
            <div class="flex items-center gap-2 mt-1 {{ $isMine ? 'justify-end' : 'justify-start' }}">
                <span class="text-[10px]" style="color: var(--color-nav-text); opacity: 0.5;">
                    {{ $message->created_at->format('d/m/Y H:i') }}
                    @if($isMine)
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
        @else
            <!-- Date uniquement pour les demandes de commande/paiement -->
            <div class="flex items-center gap-2 mt-1 {{ $isMine ? 'justify-end' : 'justify-start' }}">
                <span class="text-[10px]" style="color: var(--color-nav-text); opacity: 0.5;">
                    {{ $message->created_at->format('d/m/Y H:i') }}
                    @if($isMine)
                        @if($message->lu)
                            <i class="fas fa-check-double ml-1 text-blue-500"></i>
                        @else
                            <i class="fas fa-check ml-1"></i>
                        @endif
                    @endif
                </span>
            </div>
        @endif
    </div>
    
    <!-- Avatar de l'expéditeur -->
    @if(!$isMine)
        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white font-bold text-xs flex-shrink-0 ml-2 order-2"
             style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            {{ strtoupper(substr($message->expediteur->prenom ?? '', 0, 1) . substr($message->expediteur->nom ?? '', 0, 1)) }}
        </div>
    @endif
</div>