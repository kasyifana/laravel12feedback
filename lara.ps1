# Laravel Development Server Script
# Usage: .\lara.ps1 [serve|migrate|seed|clear]

param(
    [string]$command = "serve"
)

switch ($command) {
    "serve" {
        Write-Host "Starting Laravel development server..." -ForegroundColor Green
        php artisan serve --host=0.0.0.0 --port=8000
    }
    "migrate" {
        Write-Host "Running database migrations..." -ForegroundColor Green
        php artisan migrate
    }
    "seed" {
        Write-Host "Running database seeders..." -ForegroundColor Green
        php artisan db:seed
    }
    "fresh" {
        Write-Host "Fresh migration with seeding..." -ForegroundColor Green
        php artisan migrate:fresh --seed
    }
    "clear" {
        Write-Host "Clearing Laravel caches..." -ForegroundColor Green
        php artisan config:clear
        php artisan cache:clear
        php artisan route:clear
        php artisan view:clear
    }
    default {
        Write-Host "Available commands:" -ForegroundColor Yellow
        Write-Host "  serve  - Start development server (default)"
        Write-Host "  migrate - Run database migrations"
        Write-Host "  seed   - Run database seeders"
        Write-Host "  fresh  - Fresh migration with seeding"
        Write-Host "  clear  - Clear all caches"
    }
}