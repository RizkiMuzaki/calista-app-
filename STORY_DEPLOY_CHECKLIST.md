# Dongeng Nusa Deploy Checklist

Run these after pulling story changes on a new server or staging machine.

1. `php artisan migrate`
2. `php artisan storage:link`
3. `php artisan db:seed --class=StoryContentSeeder`
4. Confirm these files exist under `storage/app/public`:
   - `books/01KF3R3XMYN7KKTTRSSHCCPZH9.png`
   - `story-images/01KF3SGFEN0VTGGVXB0WTR6ADS.png`
   - `story-images/01KF3SW87G6GQQ1VB4PPRV1F06.png`
   - `story_audio/legenda-pulo-kemaro-palembang/1_1768640507.mp3`
   - `story_audio/legenda-pulo-kemaro-palembang/2_1768640533.mp3`
   - `story_audio/legenda-pulo-kemaro-palembang/3_1768667030.mp3`
   - `story_audio/legenda-pulo-kemaro-palembang/4_1768640567.mp3`
   - `story_audio/legenda-pulo-kemaro-palembang/5_1768640596.mp3`
   - `story_audio/legenda-pulo-kemaro-palembang/6_1768667224.mp3`
5. Verify `/api/stories`, `/api/stories/{slug}/progress`, and `/api/stories/{slug}/page/1/details` using a Sanctum token.
