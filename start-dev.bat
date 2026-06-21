@echo off
REM ============================================
REM  Lancement de l'environnement de dev AgriHub
REM  - Reverb (WebSocket)
REM  - Serveur Laravel
REM  - Vite (assets front)
REM ============================================

REM Se placer dans le dossier du projet (celui ou se trouve ce .bat)
cd /d "%~dp0"

echo Demarrage de l'environnement de developpement AgriHub...
echo.

start "Reverb (WebSocket)" cmd /k "php artisan reverb:start --debug"
timeout /t 1 /nobreak >nul

start "Laravel Serve" cmd /k "php artisan serve"
timeout /t 1 /nobreak >nul

start "Vite (npm run dev)" cmd /k "npm run dev"

echo.
echo Les 3 terminaux ont ete lances.
echo Tu peux fermer cette fenetre.
timeout /t 3 >nul