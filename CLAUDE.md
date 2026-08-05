# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## User shortcuts

- `cp` — when the user types this, it means "commit and push". Only commit and/or push when the user actually says so (e.g. via `cp` or an explicit request) — never proactively.

## Before committing

Double-check that no secrets (API keys, tokens, passwords, `.env` files) are being staged — `git diff --staged` and `git status` before every commit. `.env*` (except `.env.example`), `auth.json`, `Homestead.json/.yaml` are already gitignored; don't remove those entries.

## What this is

progr.io — a SaaS built on a Laravel 11 + Inertia/Vue3 boilerplate. Users create "Projects" (SaaS/side-project ideas), and an AI service (OpenAI) auto-populates each project with notes (benefits, monetization, pricing, features, targets, domains), a category, SEO meta, and a list of competitors (each competitor also gets AI-generated notes). Billing is Stripe via Laravel Cashier, with a credits system (`nb_credits` / `used_credits` on `User`) gating AI actions. There's a public "project ideas" catalog, and a Filament admin panel at `/admin`.

## Commands

Local dev runs via Laravel Sail (Docker). Prefix PHP/artisan commands with `sail` when using Sail; the raw `php artisan` / `composer` / `npm` forms below work if running outside Docker.

```bash
sail up -d                          # start containers
sail artisan migrate                # run migrations
sail artisan db:seed                # seed products etc.
sail artisan orchid:admin           # create an admin user (script name predates Filament switch)

npm run dev                         # Vite dev server (frontend)
npm run build                       # production frontend build

sail artisan test                   # run full Pest/PHPUnit suite
sail artisan test --filter=NameOrClass   # run a single test/class
vendor/bin/pest tests/Feature/Auth/RegistrationTest.php  # run a single test file directly

vendor/bin/pint                     # code style fixer (Laravel Pint)
```

Tests use Pest (with `pestphp/pest-plugin-laravel`) but plain PHPUnit-style classes also exist under `tests/`. `phpunit.xml` sets `testing` env with array/sync drivers — no external services needed to run the suite.

## Architecture

**Two front-of-house UIs, one back office:**
- Public/marketing + authenticated app pages are Inertia.js + Vue 3 SPA-style pages under `resources/js/Pages`, rendered from Laravel routes. `App/*` pages (`Projects`, `Project`, `NewProject`, `Dashboard`, `Ideas`) are the authenticated app; `Catalog/*` and `Home/*` are public.
- The admin back office is Filament (`app/Providers/Filament/AdminPanelProvider.php`), mounted at `/admin`, auto-discovering resources/pages/widgets from `app/Filament/*` (folders are created on demand via `filament:make-*`, not committed yet beyond the provider).
- `HandleInertiaRequests` (`app/Http/Middleware/HandleInertiaRequests.php`) shares global props to every Inertia page: current user/auth state, app name, available locales (scanned from `lang/*`), and credit/config values pulled from `config('app.*')` custom keys (`free-nb-projects`, `free-ai-credits`, `auto-population-credits`, `home-route`, `style-default-mode`). These custom `config/app.php` keys are populated from `.env` (`FREE_NB_PROJECTS`, `FREE_AI_CREDITS`, `AUTO_POPULATION_CREDITS`, `APP_HOME_ROUTE`, `APP_STYLE_DEFAULT_MODE`) — check there before adding new global config.

**Routing split:** `routes/web.php` has purely public/Inertia pages; most authenticated app pages and Stripe/billing routes live in `routes/auth.php` (grouped under `auth`/`verified`/`guest` middleware, alongside Breeze's own auth routes — auth.php is required from web.php); `routes/api.php` is the JSON API consumed by Vue pages via axios, split into public endpoints and a `auth:sanctum` group for everything else (projects CRUD, notes, competitors, AI endpoints, mail, user).

**AI content generation flow** (`app/Services/AIService.php` + `app/Services/AutoPopulateService.php`):
- `AIService` is a thin OpenAI wrapper — every method builds a prompt string (`getContext`/`getNoteQuestion`/etc.) and calls `getInsight()`, which hits the chat completions endpoint and returns raw text. Responses are parsed with fragile string conventions (`\n`-separated items, `|`-separated fields) rather than structured output — when touching prompts, preserve these delimiters or update all parsers (`getFormatedIdeas`, `getCompetitors`, `validateCompetitors`) together.
- `AutoPopulateService` orchestrates: populating project notes, deducting/crediting `User::nb_credits`/`used_credits`, adding competitors (with their own notes), assigning category, and generating SEO meta. It dispatches `ProjectPopulated`/`ProjectCompetitorsPopulated` events (consumed by `Listeners/Send*Notification`) rather than sending mail directly.
- Credit gating constants live on `AutoPopulateService` (`NB_CREDITS_REQUIRED_AUTO_POPULATION`, `NB_CREDITS_REQUIRED_COMPETITORS`) — check these before changing pricing/credit logic, they're not config-driven.
- Scheduled console commands (`app/Console/Kernel.php`) drive the background side of this: `project:auto_populate_projects` (every 5 min) and `project:set_metas` (every 5 min) sweep projects flagged for auto-population; `project:enrich_projects` (twice daily) adds competitors; `project:create_project_ideas` (daily) and `project:send_ideas` (weekly) feed the public ideas catalog; `sitemap:generate` (every 4 hours) regenerates the sitemap for `Catalog` pages.

**Domain model** (`app/Models`): `Project` belongs to `User` (`owner()`), has many `ProjectsNote` (each typed via `NotesType`), belongs-to-many `Competitor` through `competitors_projects` (ordered pivot), belongs to `Category`, and has two `ProjectMeta` relations (`metaDescription`/`metaKeywords`) distinguished by `MetaType`. `Competitor` mirrors the notes pattern with `CompetitorsNote`. `User` uses Cashier's `Billable` for Stripe subscriptions/one-time products (`Product` model) plus the credits columns. Status/visibility (`ProjectsStatus`, `ProjectsVisibility`) and auto-population state (`AutoPopulations`) are lookup tables referenced by id/code rather than enums — check migrations under `database/migrations` for the seeded codes (e.g. `AutoPopulations::where('code', 'on'|'off')`) before adding new states.

**Auth:** Laravel Breeze-based session auth plus Socialite (GitHub/Google), unified through `ProvidersCallbackController` (both provider callback routes point at the same controller). Sanctum guards the JSON API.

**Frontend conventions:** Composables under `resources/js/Composables/App/` wrap app-specific concerns (`useProject`, `aiAvailable`, `reallyAskAi`, `sendMail`, `userUsedCredits`); the top-level `Composables/` holds cross-cutting helpers (date/price formatting, statuses/visibilities lookups, a Pinia `store.js`). i18n uses `laravel-vue-i18n` with locale files in `lang/` (also surfaced to the frontend via the shared Inertia props above).
