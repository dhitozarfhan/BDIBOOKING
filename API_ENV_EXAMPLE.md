# Environment Configuration untuk API

## File .env Configuration

Tambahkan atau pastikan konfigurasi berikut ada di file `.env`:

```env
# Application
APP_NAME="BDIYK API"
APP_ENV=local
APP_KEY=base64:your-app-key-here
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bdiyk_db
DB_USERNAME=root
DB_PASSWORD=

# Sanctum
SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
SESSION_DRIVER=cookie

# API Configuration
API_VERSION=v1
API_PREFIX=api

# CORS Configuration
CORS_ALLOWED_ORIGINS=*
CORS_ALLOWED_METHODS=*
CORS_ALLOWED_HEADERS=*

# Rate Limiting
API_RATE_LIMIT=60

# File Upload
FILESYSTEM_DISK=public
MAX_UPLOAD_SIZE=10240

# Locale
APP_LOCALE=id
APP_FALLBACK_LOCALE=en
```

## Production Environment

Untuk production, ubah konfigurasi berikut:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# CORS - Set specific domains
CORS_ALLOWED_ORIGINS=https://your-mobile-app.com,https://your-web-app.com

# Rate Limiting - Adjust as needed
API_RATE_LIMIT=120

# Enable HTTPS
FORCE_HTTPS=true
```

## Sanctum Configuration

Pastikan file `config/sanctum.php` sudah dikonfigurasi dengan benar:

```php
'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
    '%s%s',
    'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
    Sanctum::currentApplicationUrlWithPort()
))),

'expiration' => null, // Token tidak expire, atau set ke jumlah menit

'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),
```

## CORS Configuration

Edit file `config/cors.php`:

```php
return [
    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => explode(',', env('CORS_ALLOWED_ORIGINS', '*')),
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => false,
];
```

## API Middleware

Tambahkan middleware di `bootstrap/app.php` atau `app/Http/Kernel.php`:

```php
'api' => [
    \App\Http\Middleware\ForceJsonResponse::class,
    \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
],
```
