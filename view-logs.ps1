# view-logs.ps1
param(
    [string]$LogType = "all",
    [int]$Lines = 50
)

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "   VISUALISATION DES LOGS AGRIFARM" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

switch ($LogType) {
    "laravel" {
        Write-Host "📋 Dernières $Lines lignes de laravel.log :" -ForegroundColor Green
        Get-Content -Path storage/logs/laravel.log -Tail $Lines
    }
    "errors" {
        Write-Host "📋 Dernières $Lines lignes de message_errors.log :" -ForegroundColor Red
        Get-Content -Path storage/logs/message_errors.log -Tail $Lines
    }
    "audit" {
        Write-Host "📋 Dernières $Lines lignes de message_audit.log :" -ForegroundColor Magenta
        Get-Content -Path storage/logs/message_audit.log -Tail $Lines
    }
    "all" {
        Write-Host "📋 Dernières $Lines lignes de laravel.log :" -ForegroundColor Green
        Get-Content -Path storage/logs/laravel.log -Tail $Lines
        Write-Host ""
        Write-Host "📋 Dernières $Lines lignes de message_errors.log :" -ForegroundColor Red
        Get-Content -Path storage/logs/message_errors.log -Tail $Lines
        Write-Host ""
        Write-Host "📋 Dernières $Lines lignes de message_audit.log :" -ForegroundColor Magenta
        Get-Content -Path storage/logs/message_audit.log -Tail $Lines
    }
    "watch" {
        Write-Host "📋 Surveillance en temps réel de tous les logs (Ctrl+C pour arrêter)..." -ForegroundColor Yellow
        Get-Content -Path storage/logs/laravel.log -Wait
    }
    "watch-errors" {
        Write-Host "📋 Surveillance en temps réel des erreurs (Ctrl+C pour arrêter)..." -ForegroundColor Yellow
        Get-Content -Path storage/logs/message_errors.log -Wait
    }
    default {
        Write-Host "Usage: .\view-logs.ps1 [-LogType laravel|errors|audit|all|watch|watch-errors] [-Lines N]" -ForegroundColor Yellow
        Write-Host "Exemples:" -ForegroundColor White
        Write-Host "  .\view-logs.ps1 -LogType errors -Lines 30" -ForegroundColor Gray
        Write-Host "  .\view-logs.ps1 -LogType watch" -ForegroundColor Gray
        Write-Host "  .\view-logs.ps1 -LogType watch-errors" -ForegroundColor Gray
    }
}