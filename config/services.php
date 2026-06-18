<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'voice_agent' => [
        'url' => env('VOICE_AGENT_URL', 'http://76.13.21.74:5003'),
    ],

    'groq' => [
        'base_url' => env('GROQ_BASE_URL', 'https://api.groq.com/openai/v1'),
        'api_key' => env('GROQ_API_KEY'),
        'chat_model' => env('GROQ_CHAT_MODEL', 'llama-3.1-8b-instant'),
        'stt_model' => env('GROQ_STT_MODEL', 'whisper-large-v3-turbo'),
    ],

    'elevenlabs' => [
        'base_url' => env('ELEVENLABS_BASE_URL', 'https://api.elevenlabs.io'),
        'api_key' => env('ELEVENLABS_API_KEY'),
        'nusa_voice_id' => env('ELEVENLABS_NUSA_VOICE_ID'),
        'model_id' => env('ELEVENLABS_MODEL_ID', 'eleven_flash_v2_5'),
        'output_format' => env('ELEVENLABS_OUTPUT_FORMAT', 'mp3_44100_128'),
        'optimize_streaming_latency' => env('ELEVENLABS_OPTIMIZE_STREAMING_LATENCY', 3),
        'voice_settings' => [
            'stability' => env('ELEVENLABS_NUSA_STABILITY', 0.45),
            'similarity_boost' => env('ELEVENLABS_NUSA_SIMILARITY', 0.90),
            'style' => env('ELEVENLABS_NUSA_STYLE', 0.15),
            'speed' => env('ELEVENLABS_NUSA_SPEED', 0.97),
            'use_speaker_boost' => env('ELEVENLABS_NUSA_SPEAKER_BOOST', true),
        ],
    ],

    'calista_ai' => [
        'credit_plans' => [
            'free' => [
                'code' => 'free',
                'limit' => (int) env('CALISTA_AI_FREE_CREDITS', 20000),
                'reset' => 'monthly',
            ],
            'weekly' => [
                'code' => 'weekly',
                'limit' => (int) env('CALISTA_AI_WEEKLY_CREDITS', 15000),
                'reset' => 'weekly',
            ],
            'monthly' => [
                'code' => 'monthly',
                'limit' => (int) env('CALISTA_AI_MONTHLY_CREDITS', 15000),
                'reset' => 'monthly',
            ],
            'yearly' => [
                'code' => 'yearly',
                'limit' => (int) env('CALISTA_AI_YEARLY_CREDITS', 0),
                'reset' => 'monthly',
            ],
        ],
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
    ],

    'louvin' => [
        'base_url' => env('LOUVIN_BASE_URL', 'https://api.louvin.dev'),
        'api_key' => env('LOUVIN_API_KEY'),
        'webhook_token' => env('LOUVIN_WEBHOOK_TOKEN'),
        'plans' => [
            'weekly' => env('LOUVIN_WEEKLY_PLAN_ID', '5dd5cdf8-9c52-43d8-a9fe-261e19e53ee9'),
            'monthly' => env('LOUVIN_MONTHLY_PLAN_ID', 'a3f5ddbd-995e-4d78-9a3d-00cf5728e7f7'),
            'yearly' => env('LOUVIN_YEARLY_PLAN_ID', '35e41d3d-16b6-48cf-985a-523a8373a27d'),
        ],
    ],

];
