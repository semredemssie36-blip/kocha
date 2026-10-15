# Kombolcha City Metropolitan Portal — Implementation Stages (stage.md)

## Tech Stack Overview
- **Backend:** Laravel 11 (PHP 8.2+) REST API + MySQL
- **Frontend:** Vue 3 (Composition API) + Vite + Tailwind CSS + Lucide Icons (SVG only, no emojis)
- **State Management & Routing:** Pinia + Vue Router (with code-splitting & route lazy-loading)
- **Languages:** Trilingual — Amharic (`am`), English (`en`), Afaan Oromoo (`om`)
- **Performance Standard:** Sub-second page loads, zero client lag, responsive on 3G/4G mobile networks.

---

## Stage 1: Environment & Project Scaffolding
- [x] **1.1 Backend Initialization:**
  - Create Laravel application in `backend/` using `composer.phar`:
    `php composer.phar create-project laravel/laravel backend`
  - Configure `.env` database connection for MySQL (`kombolcha_city_db`).
  - Install Laravel Sanctum for API token authentication (`php artisan install:api`).
  - Enable storage symlink: `php artisan storage:link`.
- [x] **1.2 Frontend Initialization:**
  - Create Vue 3 project in `frontend/` using Vite:
    `npm create vite@latest frontend -- --template vue`
  - Install core packages:
    `npm install tailwindcss @tailwindcss/vite vue-router pinia axios lucide-vue-next`
  - Configure `vite.config.js` with Tailwind plugin and `@/` path alias.
  - Establish base styling with custom municipal color palette and clean typography.
- [x] **1.3 Trilingual Setup:**
  - Build language store in Pinia supporting `am` (Amharic), `en` (English), and `om` (Afaan Oromoo).
  - Configure language dictionaries for UI labels and static text (`am.js`, `en.js`, `om.js`).

---

## Stage 2: Performance & Anti-Lag Architecture
- [x] **2.1 Vue Lazy Loading & Route Splitting:**
  - Dynamic route imports (`() => import('./views/...')`) with chunk sizes between 1kB and 14kB.
- [x] **2.2 Skeleton Loaders:**
  - Animated skeleton placeholders for news cards, directory tables, tenders, and banners (`SkeletonCard.vue`).
- [x] **2.3 Image Optimization & WebP:**
  - Automated image compression on upload and native lazy loading.
- [x] **2.4 Backend Response Caching & Eager Loading:**
  - Strict pagination on news, offices, and tenders; high performance MySQL indexed lookups.

---

## Stage 3: MySQL Database Migrations (Trilingual Schema)
All translatable entities include `_am`, `_en`, and `_om` fields.
- [x] **3.1 Users & Roles Migration:**
  - `users` with `role` (`super_admin`, `editor`, `author`), `department_id`, `phone`, `is_active`.
- [x] **3.2 Menu & Layout Migrations:**
  - `site_settings`, `hero_slides`, `emergency_contacts`, `menu_items`.
- [x] **3.3 Governance & Directory Migrations:**
  - `sub_cities` (The 4 Sub-Cities: Abishager, Borkena, Hasenager, Biraro).
  - `offices` (The 22 Municipal Offices with location, room, and direct phone).
  - `mayors` & `leadership_members` (Cabinets and City Councils).
- [x] **3.4 Media Migrations:**
  - `news_articles`, `city_events`, `vacancies`, `tenders` (with closing countdown & PDF attachment).
- [x] **3.5 Services Migrations:**
  - `services` (Text Services vs. Step Services).
  - `service_steps` (Sequential civic steps with fees, time, and required documents).
- [x] **3.6 Projects & Programs Migrations:**
  - `projects` (Infrastructure tracker with % progress bar, budget, contractor).
- [x] **3.7 Resources & Downloads Migrations:**
  - `resources` (Category, department, year, downloadable files).
- [x] **3.8 Interactive & Community Migrations:**
  - `gallery_albums` & `gallery_items` (Photos & embedded videos).
  - `polls`, `poll_options` & `poll_votes` (One-vote per user anti-spam protection).
  - `internal_board_topics` & `internal_board_posts` (Public read, staff post).
  - `contact_messages` (Inbound citizen inquiries).
  - `dynamic_pages` (Custom pages `/page/:slug`).

---

## Stage 4: Backend API Development (Laravel)
- [x] **4.1 Public Endpoints (Read-Only, Fast & Cached):**
  - Navigation, Hero Slider, Site Info & Emergency Contacts (`/api/site-data`).
  - Sub-Cities (4) and Directory (22 Offices) with instant keyword search (`/api/sub-cities`, `/api/offices`).
  - Services (Text and Step-by-step guides) (`/api/services`, `/api/services/{slug}`).
  - Media: Tenders with auto-closing logic & Vacancies (`/api/tenders`, `/api/vacancies`).
  - News Articles & Mega Projects (`/api/news`, `/api/projects`, `/api/resources`).
  - Public Voting API (Submit vote + anti-spam check) (`/api/polls/active`, `/api/polls/{id}/vote`).
  - Contact Form submission (`/api/contact/submit`).
  - Global Search across all content types (`/api/search`).
- [x] **4.2 Admin Authentication & RBAC (Sanctum):**
  - `/api/admin/login`, `/api/admin/logout`, `/api/admin/me`.
  - Bearer token authentication verified.
- [x] **4.3 Admin CRUD Controllers:**
  - `/api/admin/stats`, `/api/admin/messages`, `/api/admin/staff`, `/api/admin/news`.

---

## Stage 5: Frontend Core & Shared Components (Vue 3 + Tailwind CSS)
- [x] **5.1 Layout & Navigation Components:**
  - Top Utility Bar: Trilingual toggle (`AM` | `EN` | `OM`), Working hours, Emergency hotline.
  - Header & Mega Menu: Multi-column dropdowns with Lucide SVG icons (zero emojis).
  - Mobile Drawer Navigation: Responsive slide-out menu.
  - Footer: 4-column structured layout with emergency contacts and copyright.
- [x] **5.2 Shared Widgets:**
  - Emergency Alert Strip (`EmergencyBar.vue`) with 24/7 quick-dial numbers.
  - Live Countdown Timer component for Tenders.
  - Step-by-Step interactive civic progress cards.
  - Universal Search & Axios API client (`src/services/api.js`).

---

## Stage 6: Public Portal Pages
- [x] **6.1 Homepage (`/`):**
  - Hero Slider, Quick Sub-City & Office links, Latest News Grid, Active Tenders countdown preview, Civic Opinion Poll.
- [x] **6.2 Government Pages:**
  - `/government/offices`: Live searchable directory of the 22 municipal offices.
  - `/government/sub-cities`: Abishager, Borkena, Hasenager, Biraro sub-cities hub.
  - `/government/mayor`: Mayor's keynote speech, executive biography, cabinet council, and city council.
  - `/about`: Historical overview, 10-year spatial plan highlights, core municipal values.
- [x] **6.3 Citizen Services (`/services`):**
  - Interactive selection between Text guidance and Step-by-Step walkthroughs (e.g. Kebele ID card, birth certificate).
- [x] **6.4 Media & Opportunities:**
  - `/media/tenders`: Open public bids with live countdown and PDF downloads.
  - `/media/news`: Press releases with category filter and reading views.
  - `/media/vacancies`: Civil service job openings with qualification criteria and application instructions.
- [x] **6.5 Projects & Resources:**
  - `/projects`: Infrastructure tracker with % progress bars, contractor, and budget.
  - `/resources`: Document repository with 3-way filters (Category, Department, Year).
  - `/explore`: Tourism destinations, Wollo cultural bazaar, Kombolcha Industrial Park.
  - `/contact`: Frictionless citizen inquiry form with direct delivery to sub-city desks.
  - `/internal-board`: Staff memos and administrative circulars.

---

## Stage 7: Custom Vue 3 Admin Dashboard (`/admin`)
- [x] **7.1 Admin Layout & Guard:**
  - Secure login page (`/admin/login`) with Sanctum token authentication.
  - Dashboard layout (`/admin`) with dark sidebar, live stats, and staff profile badge.
- [x] **7.2 Content & Operations Management:**
  - Live stats overview (News count, Active Tenders, 22 Offices, Unread messages).
  - Trilingual Press Release Publisher modal (`storeNews`).
  - Citizen inquiries inbox with status management (`read`, `resolved`).
  - Staff user management with activation toggle and account creation.

---

## Stage 8: Testing, Optimization & Deployment Readiness
- [x] **8.1 Speed & Anti-Lag Audit:**
  - Code-splitting verified: 1820 modules compiled cleanly in 377ms.
  - All chunks strictly under 15kB gzipped.
- [x] **8.2 Cross-Device & Trilingual QA:**
  - All views support Amharic (`am`), English (`en`), and Afaan Oromoo (`om`).
  - Strictly SVG Lucide vector icons (zero emojis).
- [x] **8.3 Production Build:**
  - Production bundle generated cleanly (`npm run build`).
