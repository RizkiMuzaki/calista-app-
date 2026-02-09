# 📱 PROJECT PLAN: CALISTA (Character Aided Learning & Interactive Storytelling Assistant)

> **Status**: 🟢 Development Phase (Verification)
> **Methodology**: Design Thinking
> **Team**: Rizki, Tasya, Tia, Rahma

---

## 🔍 Codebase Audit (Current Status)

Project ini dalam tahap verifikasi sistem setelah restorasi database.

### ✅ Database Status

- **Status**: 🟢 Fully Restored
- **Verification**:
    - Modules: 5
    - Games: 1
    - Levels: 21
    - Users: 5
    - Anaks: 2

---

## 🏗️ Architecture Overview

### Tech Stack

- **Backend**: Laravel 12.x
- **Admin**: FilamentPHP v3
- **AI Engine**: Python (Flask) communicating via HTTP (`localhost:5000`)
- **Database**: MySQL

---

## 📅 Roadmap & Next Steps

### Phase 1: AI Service Consolidation & Verification (NOW)

- Consolidate all AI-related Python services into a single Flask application (Port 5000).
- Standardize on `voiceagent.py` and add missing `/api/writing/` and `/api/story/` endpoints.
- Update Laravel Controllers (`MenulisAIController`, `MenghitungAIController`) to use the consolidated port.

### Phase 2: Frontend Polish & Asset Cleanup

- Fix broken images.
- Redesign Landing Page to be more child-friendly.
- Optimize mobile responsiveness.

### Phase 3: Business Logic & QA

- Test Midtrans integration (Sandbox).
- Perform User Scenario Testing.

---

## 👥 Division of Labor

| Name      | Role        | Focus Area                                                                 |
| :-------- | :---------- | :------------------------------------------------------------------------- |
| **Rizki** | Lead Dev/AI | Mengurus `voiceagent.py`, Server Python, dan Integrasi Payment.            |
| **Tasya** | Game Dev    | Mengurus `MenulisAIController`, `MenghitungAIController`, dan Admin Panel. |
| **Tia**   | UI/UX       | Desain tampilan Blade, Icon, dan Animasi.                                  |
| **Rahma** | QA/Biz      | Testing fitur (mencari bug) dan menyusun User Scenarios.                   |
