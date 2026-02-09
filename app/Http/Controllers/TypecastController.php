<?php

namespace App\Http\Controllers;

use App\Services\TypecastService;
use Illuminate\Http\Request;

class TypecastController extends Controller
{
    protected $typecastService;

    public function __construct(TypecastService $typecastService)
    {
        $this->typecastService = $typecastService;
    }

    /**
     * Generate greeting untuk halaman menulis
     */
    public function generateWritingGreeting(Request $request)
    {
        $request->validate([
            'writing_item_id' => 'required|exists:writing_items,id',
        ]);

        try {
            $writingItem = \App\Models\WritingItems::with(['level.module'])
                ->findOrFail($request->writing_item_id);
            
            $user = auth()->user();
            
            // Text tambahan berdasarkan tipe writing item
            $additionalText = '';
            if ($writingItem->type === 'letter') {
                $additionalText = "Kita akan belajar menulis huruf {$writingItem->text}. Ayo ikuti garis panduannya dengan baik!";
            } else {
                $additionalText = "Kita akan belajar menulis kata '{$writingItem->text}'. Pastikan setiap hurufnya rapi ya!";
            }

            $result = $this->typecastService->generateGreetingAudio(
                $user->name,
                $writingItem->level->module->name ?? 'Modul',
                "Level {$writingItem->level->order_number}",
                $additionalText
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'audio_url' => $result['url'],
                    'message' => 'Greeting audio generated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => $result['error']
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Simple test endpoint
     */
    public function simpleTest()
    {
        $result = $this->typecastService->testConnection();
        
        return response()->json($result);
    }

    /**
     * Raw test endpoint
     */
    public function rawTest()
    {
        try {
            $user = auth()->user();
            
            $result = $this->typecastService->generateGreetingAudio(
                $user->name,
                'Huruf dan Angka',
                'Level 1',
                'Mari kita mulai petualangan belajar yang menyenangkan!'
            );

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Custom text to speech
     */
    public function customTTS(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:500',
        ]);

        $result = $this->typecastService->generateAudio($request->text, [
            'emotion' => $request->emotion ?? 'happy',
            'emotion_intensity' => $request->emotion_intensity ?? 1.2
        ]);

        return response()->json($result);
    }
}