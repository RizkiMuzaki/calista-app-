<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class Adm09425863PanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('adm09425863')
            ->path('calista-admin')
            ->login()
            ->colors([
                'primary' => Color::Blue,
                'secondary' => Color::Cyan,
                'success' => Color::Green,
                'warning' => Color::Amber,
                'danger' => Color::Red,
                'gray' => Color::Slate,
            ])
            ->font('Inter')
            ->brandName('Calista Admin')
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn (): \Illuminate\Support\HtmlString => new \Illuminate\Support\HtmlString('
                    <style>
                        /* 🚀 CALISTA ADMIN 2026 PREMIUM UI THEME */
                        body {
                            background-image: radial-gradient(at 0% 0%, rgba(30, 41, 59, 0.4) 0px, transparent 50%),
                                              radial-gradient(at 50% 0%, rgba(15, 23, 42, 0.2) 0px, transparent 50%),
                                              radial-gradient(at 100% 0%, rgba(15, 118, 110, 0.12) 0px, transparent 50%) !important;
                        }

                        /* Glassmorphism untuk Widget Stat Card */
                        .fi-wi-stats-overview-stat-card {
                            background: rgba(15, 23, 42, 0.65) !important;
                            backdrop-filter: blur(16px) !important;
                            -webkit-backdrop-filter: blur(16px) !important;
                            border: 1px solid rgba(255, 255, 255, 0.08) !important;
                            border-radius: 20px !important;
                            box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.3) !important;
                            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1) !important;
                            position: relative !important;
                            overflow: hidden !important;
                        }

                        /* Garis gradien menyala di atas card */
                        .fi-wi-stats-overview-stat-card::after {
                            content: "" !important;
                            position: absolute !important;
                            top: 0 !important;
                            left: 0 !important;
                            width: 100% !important;
                            height: 3px !important;
                            background: linear-gradient(90deg, #3b82f6, #06b6d4, #14b8a6) !important;
                            opacity: 0.8 !important;
                        }

                        /* Hover glow effect */
                        .fi-wi-stats-overview-stat-card:hover {
                            transform: translateY(-5px) !important;
                            box-shadow: 0 15px 35px -5px rgba(6, 182, 212, 0.25) !important;
                            border-color: rgba(6, 182, 212, 0.4) !important;
                        }

                        /* Judul Card */
                        .fi-wi-stats-overview-stat-card .text-sm {
                            color: #94a3b8 !important;
                            font-weight: 600 !important;
                            text-transform: uppercase !important;
                            letter-spacing: 0.075em !important;
                            font-size: 0.75rem !important;
                        }

                        /* Nilai Angka Utama */
                        .fi-wi-stats-overview-stat-card .text-3xl {
                            font-size: 2.5rem !important;
                            font-weight: 800 !important;
                            letter-spacing: -0.03em !important;
                            background: linear-gradient(135deg, #ffffff 0%, #94a3b8 100%) !important;
                            -webkit-background-clip: text !important;
                            -webkit-text-fill-color: transparent !important;
                            margin-top: 0.5rem !important;
                        }

                        /* Deskripsi di bawah card */
                        .fi-wi-stats-overview-stat-card .text-gray-500,
                        .fi-wi-stats-overview-stat-card .fi-wi-stats-overview-stat-description {
                            color: #cbd5e1 !important;
                            font-size: 0.85rem !important;
                            font-weight: 500 !important;
                        }

                        /* Ikon Deskripsi */
                        .fi-wi-stats-overview-stat-card .fi-wi-stats-overview-stat-description-icon {
                            width: 18px !important;
                            height: 18px !important;
                            color: #06b6d4 !important;
                        }

                        /* Section heading styling */
                        .fi-wi-stats-overview-heading {
                            font-size: 0.85rem !important;
                            font-weight: 700 !important;
                            letter-spacing: 0.05em !important;
                            color: #cbd5e1 !important;
                            text-transform: uppercase !important;
                            border-left: 4px solid #3b82f6 !important;
                            padding-left: 8px !important;
                            margin-bottom: 0.75rem !important;
                        }

                        /* Header Utama */
                        .fi-header-heading {
                            font-size: 2.25rem !important;
                            font-weight: 950 !important;
                            letter-spacing: -0.04em !important;
                            background: linear-gradient(135deg, #60a5fa 0%, #2dd4bf 100%) !important;
                            -webkit-background-clip: text !important;
                            -webkit-text-fill-color: transparent !important;
                        }

                        /* Pagination ke kanan */
                        .fi-ta-pagination, 
                        .fi-ta-pagination > div {
                            display: flex !important;
                            flex-direction: row !important;
                            justify-content: space-between !important;
                            align-items: center !important;
                            width: 100% !important;
                        }
                        .fi-ta-pagination nav, 
                        .fi-ta-pagination-nav {
                            margin-left: auto !important;
                            margin-right: 0 !important;
                            display: flex !important;
                            justify-content: flex-end !important;
                        }
                    </style>
                ')
            );
    }
}
