# 📋 CALISTA PROJECT TASKS

## 🔴 BACKEND (LARAVEL)
- [x] Auth System (Login, Register)
- [x] Child Management (CRUD Profile Anak)
- [x] Module & Level Data
- [x] Progress Tracking System
- [x] Shop & Inventory System
- [x] Voice Agent API (Typecast API Integration & Caching)
- [x] Payment System (Tripay, Checkout, Callback Validation)
- [x] Louvin Subscription Integration (Sync Plan IDs, prices, names, and fix missing reference SQL 500 error)
- [x] Fix Story Upload crash when validation fails (Spatie Media Library & Livewire size retrieval crash)
- [ ] Security Audit (Input Sanitization)
- [x] Delete "Pulo Kemarau" story from database & Spatie Media library via migration (June 2026)
- [x] Verify & Audited Louvin Webhook security and configuration (June 2026)
- [x] Technical Debt Phase 2 Cleanup (Obsolete Web Views, Assets, and Controllers) (June 2026)



## 🟡 FRONTEND (FLUTTER)
- [x] Onboarding & Walkthrough
- [x] Child Selection Dashboard
- [x] Main Dashboard (Home)
- [x] Game Selection Module
- [x] Profile & Parental Gateway
- [x] Shop (Bento UI)
- [x] Voice Agent AI Page (NusaChatScreen)
- [x] Game Berhitung (Counting Drop with Dynamic TTS)
- [ ] Play Timer Backend Integration

## 🟢 STABILITY FIXES (COMPLETED)
- [x] Fix empty shop screen (UI)
- [x] Resolve hardcoded childId in Dashboard
- [x] Implement dynamic Child Profile in Settings
- [x] Fix PHP linter warnings (Controller map closures)
- [x] Complete Logout Logic with Confirmation
- [x] Implement Reward Claim Validation (Backend)
- [x] Rebuild Game Menulis (A-Z levels, auto-play TTS walkthrough, animated guided tracing, glassmorphism CCW reset)
- [x] Fix PaymentController fatal error (Pesanan class)
- [x] Fix LevelController counting item null pointer
- [x] Fix VoiceAgentController speech-to-text timeout issue
- [x] Fix Audio Clipping for TTS and fallback audio overlapping
- [x] Fix offline audio pack generation stuck at 0% and storage 404 errors (added queue worker daemon and auto-symlink generation to Nixpacks start command) (June 2026)
- [x] Fix Livewire file upload signature mismatch & body size limit issues behind reverse proxy (June 2026)


## 🟢 VOICE AI OPTIMIZATION (COMPLETED)
- [x] (LEGACY) Synchronize Laravel controller ports to port 5003 -> Dihentikan, bermigrasi ke ElevenLabs native.
- [x] (LEGACY) Implement FallbackTTS in voiceagent.py (Typecast -> EdgeTTS) -> Dihentikan.
- [x] (LEGACY) Implement `/api/writing` aliases in voiceagent.py -> Dihentikan.
- [x] Optimize pre-fetching & cache validation for questions & choices (ElevenLabs Native).

---
*Status Update: 31 May 2026 - Voice AI Optimization & Mismatched Ports Fix.*
