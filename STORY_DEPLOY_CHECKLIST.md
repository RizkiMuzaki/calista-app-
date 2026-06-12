# Dongeng Nusa Deploy Checklist

Run these after pulling story changes on a new server or staging machine.

1. `php artisan migrate`
2. `php artisan storage:link`
3. Upload new stories and their media assets (Cover, Audio Narasi, Video Animasi) via Filament Admin Panel (dashboard.calista-mobile.my.id).
4. Confirm media directories exist under `storage/app/public/`:
   - Verify uploaded files exist in dynamic Spatie media directories (e.g. `storage/app/public/15/`, `storage/app/public/16/`, etc.)
5. Verify API endpoints using a Sanctum token:
   - GET `/api/stories`
   - GET `/api/stories/{slug}`
   - POST `/api/stories/{slug}/progress`
   - POST `/api/stories/{slug}/like`
   - GET/POST `/api/stories/{slug}/reviews`
