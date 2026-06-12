<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Story;
use App\Models\UserStoryProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StoryApiController extends Controller
{
    private function resolveMediaUrl(?string $url): ?string
    {
        if (empty($url)) {
            return null;
        }
        
        if (preg_match('/^(http|https):\/\//', $url)) {
            $path = parse_url($url, PHP_URL_PATH);
            $host = parse_url($url, PHP_URL_HOST);
            
            // Rewrite host if it is a local IP, localhost, or our main domain to match the request host dynamically
            $isLocalOrOwn = preg_match('/(localhost|127\.0\.0\.1|10\.\d+\.\d+\.\d+|calista-mobile\.my\.id)/i', $host);
            
            if ($isLocalOrOwn && !empty($path)) {
                $baseUrl = rtrim(request()->getSchemeAndHttpHost(), '/');
                return $baseUrl . '/' . ltrim($path, '/');
            }
            
            return $url;
        }
        
        $baseUrl = rtrim(request()->getSchemeAndHttpHost(), '/');
        return $baseUrl . '/' . ltrim($url, '/');
    }

    /**
     * Get list of all active stories with cover image and user progress
     */
    public function index(Request $request)
    {
        try {
            $user = auth('sanctum')->user();
            $stories = Story::where('is_active', true)
                ->orderBy('order', 'asc')
                ->get();

            $data = $stories->map(function (Story $story) use ($user) {
                // Get Spatie cover media url
                $coverUrl = $this->resolveMediaUrl($story->getFirstMediaUrl('cover'));

                // Get progress for current user
                $progress = null;
                if ($user) {
                    $userProgress = UserStoryProgress::where('user_id', $user->id)
                        ->where('story_id', $story->id)
                        ->first();
                    if ($userProgress) {
                        $progress = [
                            'last_position_seconds' => (double) $userProgress->last_position_seconds,
                            'completed' => (bool) $userProgress->completed,
                            'completed_at' => $userProgress->completed_at ? $userProgress->completed_at->toIso8601String() : null,
                        ];
                    }
                }

                return [
                    'id' => $story->id,
                    'title' => $story->title,
                    'slug' => $story->slug,
                    'description' => $story->description,
                    'rating' => (double) $story->rating,
                    'age_group' => $story->age_group,
                    'duration' => $story->duration,
                    'is_premium' => false, // Bypassed: force false for testing
                    'stars_required' => (int) $story->stars_required,
                    'order' => (int) $story->order,
                    'cover_url' => $coverUrl,
                    'user_progress' => $progress,
                ];
            });
 
            return response()->json([
                'success' => true,
                'message' => 'Daftar dongeng berhasil diambil',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('[StoryApiController] Index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil daftar dongeng: ' . $e->getMessage()
            ], 500);
        }
    }
 
    /**
     * Get detailed story segments (pages) and full media assets
     */
    public function show($slug)
    {
        try {
            $user = auth('sanctum')->user();
            $story = Story::where('slug', $slug)
                ->where('is_active', true)
                ->firstOrFail();
 
            // Lock premium stories for non-subscribers (Bypassed for testing)
            /*
            if ($story->is_premium) {
                if (!$user || !$user->hasActiveSubscription()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Dongeng ini adalah konten premium. Silakan berlangganan terlebih dahulu.',
                    ], 403);
                }
            }
            */
 
            // Resolve assets
            $coverUrl = $this->resolveMediaUrl($story->getFirstMediaUrl('cover'));
            $narrationUrl = $this->resolveMediaUrl($story->getFirstMediaUrl('full_narration'));
            $animationUrl = $this->resolveMediaUrl($story->getFirstMediaUrl('full_animation'));
 
            // Load pages logic removed - using full_script instead
 
            // Get progress
            $progress = null;
            if ($user) {
                $userProgress = UserStoryProgress::where('user_id', $user->id)
                    ->where('story_id', $story->id)
                    ->first();
                if ($userProgress) {
                    $progress = [
                        'last_position_seconds' => (double) $userProgress->last_position_seconds,
                        'completed' => (bool) $userProgress->completed,
                        'completed_at' => $userProgress->completed_at ? $userProgress->completed_at->toIso8601String() : null,
                    ];
                }
            }
 
            return response()->json([
                'success' => true,
                'message' => 'Detail dongeng berhasil diambil',
                'data' => [
                    'id' => $story->id,
                    'title' => $story->title,
                    'slug' => $story->slug,
                    'description' => $story->description,
                    'rating' => (double) $story->rating,
                    'age_group' => $story->age_group,
                    'duration' => $story->duration,
                    'is_premium' => false, // Bypassed: force false for testing
                    'stars_required' => (int) $story->stars_required,
                    'full_script' => $story->full_script,
                    'cover_url' => $coverUrl,
                    'narration_url' => $narrationUrl,
                    'animation_url' => $animationUrl,
                    'user_progress' => $progress
                ]
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dongeng tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('[StoryApiController] Show error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil detail dongeng'
            ], 500);
        }
    }

    /**
     * Save child read progress (last position in seconds)
     */
    public function saveProgress(Request $request, $slug)
    {
        $request->validate([
            'last_position_seconds' => 'required|numeric|min:0',
            'completed' => 'required|boolean'
        ]);

        try {
            $user = auth('sanctum')->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }

            $story = Story::where('slug', $slug)->firstOrFail();

            $completed = $request->completed;

            $progress = UserStoryProgress::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'story_id' => $story->id,
                ],
                [
                    'last_position_seconds' => (double) $request->last_position_seconds,
                    'completed' => $completed,
                    'completed_at' => $completed ? now() : null,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Progress berhasil disimpan',
                'data' => [
                    'last_position_seconds' => (double) $progress->last_position_seconds,
                    'completed' => (bool) $progress->completed,
                ]
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dongeng tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('[StoryApiController] SaveProgress error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan progress'
            ], 500);
        }
    }

    /**
     * Toggle like/unlike for a story
     * POST /api/stories/{slug}/like
     */
    public function toggleLike(Request $request, $slug)
    {
        try {
            $user = auth('sanctum')->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $story = Story::where('slug', $slug)->where('is_active', true)->firstOrFail();
            $anakId = $request->input('anak_id');

            $existingLike = \App\Models\StoryLike::where('story_id', $story->id)
                ->where('user_id', $user->id)
                ->where('anak_id', $anakId)
                ->first();

            if ($existingLike) {
                $existingLike->delete();
                $liked = false;
            } else {
                \App\Models\StoryLike::create([
                    'story_id' => $story->id,
                    'user_id' => $user->id,
                    'anak_id' => $anakId,
                ]);
                $liked = true;
            }

            $likesCount = \App\Models\StoryLike::where('story_id', $story->id)->count();

            return response()->json([
                'success' => true,
                'liked' => $liked,
                'likes_count' => $likesCount,
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Dongeng tidak ditemukan'], 404);
        } catch (\Exception $e) {
            Log::error('[StoryApiController] toggleLike error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memproses like'], 500);
        }
    }

    /**
     * Get reviews for a story
     * GET /api/stories/{slug}/reviews
     */
    public function getReviews(Request $request, $slug)
    {
        try {
            $story = Story::where('slug', $slug)->where('is_active', true)->firstOrFail();

            $reviews = \App\Models\StoryReview::where('story_id', $story->id)
                ->with(['user:id,name', 'anak:id,nama'])
                ->orderByDesc('created_at')
                ->limit(50)
                ->get()
                ->map(function ($review) {
                    return [
                        'id' => $review->id,
                        'rating' => $review->rating,
                        'comment' => $review->comment,
                        'author' => $review->anak?->nama ?? $review->user?->name ?? 'Anonim',
                        'is_child' => $review->anak_id !== null,
                        'created_at' => $review->created_at->diffForHumans(),
                    ];
                });

            $user = auth('sanctum')->user();
            $anakId = $request->query('anak_id');
            $userLiked = false;
            $likesCount = \App\Models\StoryLike::where('story_id', $story->id)->count();
            if ($user) {
                $userLiked = \App\Models\StoryLike::where('story_id', $story->id)
                    ->where('user_id', $user->id)
                    ->where('anak_id', $anakId)
                    ->exists();
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'reviews' => $reviews,
                    'reviews_count' => $reviews->count(),
                    'likes_count' => $likesCount,
                    'user_liked' => $userLiked,
                ],
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Dongeng tidak ditemukan'], 404);
        } catch (\Exception $e) {
            Log::error('[StoryApiController] getReviews error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mengambil ulasan'], 500);
        }
    }

    /**
     * Submit a review for a story
     * POST /api/stories/{slug}/reviews
     */
    public function submitReview(Request $request, $slug)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
            'anak_id' => 'nullable|integer',
        ]);

        try {
            $user = auth('sanctum')->user();
            if (!$user) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }

            $story = Story::where('slug', $slug)->where('is_active', true)->firstOrFail();
            $anakId = $request->input('anak_id');

            $review = \App\Models\StoryReview::updateOrCreate(
                [
                    'story_id' => $story->id,
                    'user_id' => $user->id,
                    'anak_id' => $anakId,
                ],
                [
                    'rating' => $request->rating,
                    'comment' => $request->comment,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Ulasan berhasil disimpan',
                'data' => [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                ],
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Dongeng tidak ditemukan'], 404);
        } catch (\Exception $e) {
            Log::error('[StoryApiController] submitReview error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menyimpan ulasan'], 500);
        }
    }
}
