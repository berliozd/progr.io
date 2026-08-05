[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F6a0540fe-45ae-4d88-acae-39a0aef8846a&style=plastic)](https://forge.laravel.com/servers/763260/sites/2265458)

# progr.io

## What it is

progr.io helps indie hackers and SaaS founders explore and validate project ideas. Users create "Projects"
describing a SaaS/side-project idea, and an AI service (OpenAI) auto-populates each one with:

- Notes on benefits, monetization, pricing, features, target users, and possible domain names
- A category and SEO meta description/keywords
- A list of competitors, each with their own AI-generated notes (benefits, monetization, pricing, features, targets)

Projects can be exported as PDF or CSV, shared publicly via a presentation page, and browsed through a public
"project ideas" catalog. Access to AI actions is gated by a credits system, with paid plans and one-time credit
top-ups billed through Stripe.

## How it's built

- **Backend**: Laravel 11 (PHP 8.3), with Laravel Cashier (Stripe billing), Sanctum (API auth), Socialite
  (GitHub/Google login), and the OpenAI PHP client for AI generation.
- **Frontend**: Inertia.js + Vue 3, Tailwind CSS / DaisyUI, Pinia for state, `laravel-vue-i18n` for translations,
  bundled with Vite.
- **Admin**: A Filament admin panel served at `/admin`.
- **Background jobs**: Scheduled artisan commands handle auto-population, competitor enrichment, SEO meta
  generation, sitemap regeneration, and the public ideas catalog feed.
- **Testing**: Pest (on top of PHPUnit).

See `CLAUDE.md` for a deeper architecture walkthrough.

## Running it locally

### 1. Get the code

```
git clone git@github.com:berliozd/progr.io.git
cd progr.io
composer install
cp .env.example .env
```

### 2. Configure `.env`

Database:
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=progr_io
DB_USERNAME=sail
DB_PASSWORD=password
```

OpenAI (required for AI-powered features):
```
OPENAI_API_KEY=<YOUR_OPENAI_API_KEY>
```

Stripe:
```
STRIPE_KEY=<YOUR_STRIPE_KEY>
STRIPE_SECRET=<YOUR_STRIPE_SECRET>
STRIPE_BASIC_PRICE=<YOUR_BASIC_PRICE>
STRIPE_PREMIUM_PRICE=<YOUR_PREMIUM_PRICE>
STRIPE_TRIAL_PERIOD=<YOUR_TRIAL_PERIOD> # Set 0 if no trial period
```

GitHub login (optional):
```
GITHUB_CLIENT_ID=<YOUR_GITHUB_CLIENT_ID>
GITHUB_CLIENT_SECRET=<YOUR_GITHUB_CLIENT_SECRET>
GITHUB_CLIENT_CALLBACK_URI=<YOUR_GITHUB_CALLBACK_URI>
```

Google login (optional):
```
GOOGLE_CLIENT_ID=<YOUR_GOOGLE_CLIENT_ID>
GOOGLE_CLIENT_SECRET=<YOUR_GOOGLE_CLIENT_SECRET>
GOOGLE_CLIENT_CALLBACK_URI=<YOUR_GOOGLE_CALLBACK_URI>
```

Mailjet (transactional email):
```
MAILJET_CLIENT_ID=<YOUR_MAILJET_CLIENT_ID>
MAILJET_CLIENT_SECRET=<YOUR_MAILJET_CLIENT_SECRET>
```

Other settings:
```
APP_STYLE_DEFAULT_MODE=dark
FREE_NB_PROJECTS=<NB_FREE_PROJECTS>
FREE_AI_CREDITS=<NB_FREE_AI_CREDITS>
AUTO_POPULATION_CREDITS=<NB_CREDITS_FOR_AUTO_POPULATION>
```

### 3. Finalize the Laravel installation

Build and start containers (app, mysql):
```
sail up --build -d
```

Generate the Laravel key:
```
sail artisan key:generate
```

Run migrations:
```
sail artisan migrate
```

Run the seeder to create the products:
```
sail artisan db:seed
```

Create an admin user:
```
sail artisan make:filament-user
```

Build and run the frontend:
```
npm install
npm run dev
```

That's it! Go to http://localhost to verify your installation.

### Running tests

```
sail artisan test
```
