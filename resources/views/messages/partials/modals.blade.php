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