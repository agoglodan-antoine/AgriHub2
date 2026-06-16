<x-app-layout>
    <x-slot name="header">
        <div class="py-12" style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
            <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white">Mes discussions</h1>
                        <p class="text-white/80 text-sm mt-1">
                            <i class="fas fa-comments mr-1"></i>
                            {{ $discussions->count() }} discussion(s)
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-4 py-2 rounded-full text-sm font-semibold text-white backdrop-blur-sm"
                              style="background: rgba(255,255,255,0.2); border: 1px solid rgba(255,255,255,0.3);">
                            <i class="fas fa-envelope mr-2"></i>
                            {{ $discussions->sum('unread_count') }} non lu(s)
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12" style="background-color: var(--color-bg-body);">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Liste des discussions -->
            <div class="rounded-xl shadow-lg overflow-hidden" style="background-color: var(--color-bg-white);">
                @forelse($discussions as $discussion)
                    <a href="{{ route('messagerie.show', $discussion->user->id) }}" 
                       class="block hover:bg-gray-50 transition-colors duration-200 border-b last:border-b-0"
                       style="border-color: var(--color-nav-border);">
                        <div class="flex items-center gap-4 p-4 hover:translate-x-1 transition-transform">
                            <!-- Avatar -->
                            <div class="relative flex-shrink-0">
                                <div class="w-14 h-14 rounded-full flex items-center justify-center text-white font-bold text-xl"
                                     style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));">
                                    {{ strtoupper(substr($discussion->user->prenom ?? '', 0, 1) . substr($discussion->user->nom ?? '', 0, 1)) }}
                                </div>
                                @if($discussion->unread_count > 0)
                                    <span class="absolute -top-1 -right-1 w-5 h-5 rounded-full flex items-center justify-center text-xs font-bold text-white"
                                          style="background-color: #EF4444;">
                                        {{ $discussion->unread_count }}
                                    </span>
                                @endif
                            </div>

                            <!-- Infos -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <h3 class="font-semibold truncate" style="color: var(--color-nav-text);">
                                        {{ $discussion->user->prenom ?? '' }} {{ $discussion->user->nom ?? 'Utilisateur' }}
                                        @if($discussion->user->isActive())
                                            <span class="inline-flex items-center ml-2 text-xs text-green-500">
                                                <span class="w-2 h-2 rounded-full bg-green-500 mr-1 animate-pulse"></span>
                                                En ligne
                                            </span>
                                        @endif
                                    </h3>
                                    <span class="text-xs flex-shrink-0" style="color: var(--color-nav-text); opacity: 0.5;">
                                        {{ $discussion->last_message->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                @if($discussion->annonce)
                                    <p class="text-xs truncate" style="color: var(--color-primary);">
                                        <i class="fas fa-tag mr-1"></i>
                                        Annonce: {{ $discussion->annonce->titre }}
                                    </p>
                                @endif

                                <p class="text-sm truncate" style="color: var(--color-nav-text); opacity: 0.7;">
                                    @if($discussion->last_message->id_expediteur === Auth::id())
                                        <span class="text-xs" style="color: var(--color-nav-text); opacity: 0.5;">Vous: </span>
                                    @endif
                                    {{ Str::limit($discussion->last_message->contenu, 60) }}
                                </p>
                            </div>

                            <!-- Flèche -->
                            <div class="flex-shrink-0 ml-2">
                                <i class="fas fa-chevron-right" style="color: var(--color-nav-text); opacity: 0.3;"></i>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-16">
                        <div class="w-24 h-24 rounded-full flex items-center justify-center mx-auto mb-4"
                             style="background-color: var(--color-secondary-light);">
                            <i class="fas fa-comments text-4xl" style="color: var(--color-primary);"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-2" style="color: var(--color-nav-text);">Aucune discussion</h3>
                        <p class="mb-6" style="color: var(--color-nav-text); opacity: 0.7;">
                            Vous n'avez pas encore de discussion. Contactez un vendeur depuis une annonce !
                        </p>
                        <a href="{{ route('home') }}" 
                           class="inline-flex items-center px-6 py-3 rounded-lg font-semibold transition-all duration-300 text-white"
                           style="background: linear-gradient(135deg, var(--color-primary), var(--color-primary-dark));"
                           onmouseover="this.style.background='linear-gradient(135deg, var(--color-primary-dark), var(--color-secondary))'; this.style.transform='translateY(-2px)'"
                           onmouseout="this.style.background='linear-gradient(135deg, var(--color-primary), var(--color-primary-dark))'; this.style.transform='translateY(0)'">
                            <i class="fas fa-search mr-2"></i>
                            Parcourir les annonces
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>