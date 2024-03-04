[![Laravel Forge Site Deployment Status](https://img.shields.io/endpoint?url=https%3A%2F%2Fforge.laravel.com%2Fsite-badges%2F6a0540fe-45ae-4d88-acae-39a0aef8846a&style=plastic)](https://forge.laravel.com/servers/763260/sites/2265458)

## Installing saas-boilerplate

### 1. Download code

```
git clone https://<USERNAME>:<PERSONNAL_ACCESS_TOKEN>@github.com/berliozd/saas-boilerplate <PROJECT_NAME>
rm -rf .git
```

```
cd <PROJECT_NAME>
```

```
composer install
```

```
cp .env.example .env
```

### 2. Set configurations in .env file

For database configuration
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=<PROJECT_NAME>
DB_USERNAME=sail
DB_PASSWORD=password
```

Stripe configuration
```
STRIPE_KEY=<YOUR_STRIPE_KEY>
STRIPE_SECRET=<YOUR_STRIPE_SECRET>
STRIPE_BASIC_PRICE=<YOUR_BASIC_PRICE>
STRIPE_PREMIUM_PRICE=<YOUR_PREMIUM_PRICE>
STRIPE_TRIAL_PERIOD=<YOUR_TRIAL_PERIOD> # Set 0 if no trial period
```

If you are planning to use github as an auth service provider
```
GITHUB_CLIENT_ID=<YOUR_GITHUB_CLIEN_ID>
GITHUB_CLIENT_SECRET=<YOUR_GITHUB_CLIENT_SECRET>
GITHUB_CLIENT_CALLBACK_URI=<YOUR_GITHUB_CALLBACK_URI>
```

If you are planning to use google as an auth service provider
```
GOOGLE_CLIENT_ID=<YOUR_GOOGLE_CLIENT_ID>
GOOGLE_CLIENT_SECRET=<YOUR_GOOGLE_CLIENT_SECRET>
GOOGLE_CLIENT_CALLBACK_URI=<YOUR_GOOGLE_CALLBACK_URI>
```

For mailjet, set your keys
```
MAILJET_CLIENT_ID=<YOUR_MAILJET_CLIENT_ID>
MAILJET_CLIENT_SECRET=<YOUR_MAILJET_CLIENT_SECRET>
```

Other settings
```
APP_STYLE_DEFAULT_MODE=dark
```

### 3. Finalize Laravel installation

Build containers (app, mysql)
```
sail up --build -d
```

Generate Laravel key
```
sail artisan key:generate
```

Run migrations

```
sail artisan migrate
```

Run seeder to create the products

```
sail artisan db:seed
```

Create an admin user

```
sail artisan orchid:admin
```

Build and run frontend

```
npm install
npm run dev
```

That's it!

You can go to http://localhost and verify your installation.
# progr.io
