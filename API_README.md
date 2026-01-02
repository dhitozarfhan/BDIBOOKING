# BDIYK Mobile REST API

REST API untuk aplikasi mobile BDIYK yang dibangun dengan Laravel dan Laravel Sanctum untuk autentikasi.

## Daftar Isi

- [Instalasi](#instalasi)
- [Konfigurasi](#konfigurasi)
- [Endpoint API](#endpoint-api)
- [Autentikasi](#autentikasi)
- [Testing](#testing)
- [Troubleshooting](#troubleshooting)

## Instalasi

### Prerequisites

- PHP 8.1 atau lebih tinggi
- Composer
- MySQL/MariaDB
- Laravel 11.x

### Langkah Instalasi

1. **Clone repositori**
   ```bash
   git clone https://github.com/kurn14/bdiyk-baru.git
   cd bdiyk-baru
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Copy file environment**
   ```bash
   cp .env.example .env
   ```

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Konfigurasi database**
   
   Edit file `.env` dan sesuaikan konfigurasi database:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=bdiyk_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Jalankan migrasi database**
   ```bash
   php artisan migrate
   ```

7. **Jalankan seeder (opsional)**
   ```bash
   php artisan db:seed
   ```

8. **Create storage link**
   ```bash
   php artisan storage:link
   ```

9. **Jalankan server**
   ```bash
   php artisan serve
   ```

API akan berjalan di `http://localhost:8000`

## Konfigurasi

### Laravel Sanctum

Laravel Sanctum sudah dikonfigurasi untuk autentikasi API. Pastikan di file `config/sanctum.php` sudah sesuai dengan kebutuhan Anda.

### CORS Configuration

Jika aplikasi mobile Anda memerlukan akses CORS, edit file `config/cors.php`:

```php
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_methods' => ['*'],
'allowed_origins' => ['*'],
'allowed_origins_patterns' => [],
'allowed_headers' => ['*'],
'exposed_headers' => [],
'max_age' => 0,
'supports_credentials' => false,
```

### Rate Limiting

Rate limiting sudah dikonfigurasi di `app/Providers/RouteServiceProvider.php`. Default adalah 60 requests per menit untuk API.

## Endpoint API

### Base URL

```
http://your-domain.com/api/v1
```

### Dokumentasi Lengkap

Lihat [API_DESIGN.md](API_DESIGN.md) untuk dokumentasi lengkap semua endpoint.

### Ringkasan Endpoint

#### Authentication
- `POST /api/v1/auth/login` - Login user
- `POST /api/v1/auth/logout` - Logout user (requires auth)
- `GET /api/v1/auth/profile` - Get user profile (requires auth)

#### Articles
- `GET /api/v1/articles` - Get all articles
- `GET /api/v1/articles/{id}` - Get article by ID
- `GET /api/v1/articles/slug/{slug}` - Get article by slug

#### Categories
- `GET /api/v1/categories` - Get all categories
- `GET /api/v1/categories/{id}` - Get category by ID

#### Slideshows
- `GET /api/v1/slideshows` - Get active slideshows

#### Information
- `POST /api/v1/information/questions` - Submit question
- `GET /api/v1/information/questions/{code}` - Check question status
- `POST /api/v1/information/requests` - Submit information request
- `GET /api/v1/information/requests/{code}` - Check request status

#### Gratification
- `POST /api/v1/gratification/reports` - Submit gratification report
- `GET /api/v1/gratification/reports/{code}` - Check report status
- `GET /api/v1/gratification/reports` - Get all reports (requires auth)

#### WBS (Whistleblowing System)
- `POST /api/v1/wbs/reports` - Submit WBS report
- `GET /api/v1/wbs/reports/{code}` - Check report status
- `GET /api/v1/wbs/reports` - Get all reports (requires auth)

## Autentikasi

API menggunakan Laravel Sanctum untuk autentikasi berbasis token.

### Login

**Request:**
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "username": "your_username",
    "password": "your_password"
  }'
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "token": "1|abcdefghijklmnopqrstuvwxyz",
    "user": {
      "id": 1,
      "username": "john_doe",
      "name": "John Doe",
      "email": "john@example.com",
      "image": "http://localhost:8000/storage/images/user.jpg",
      "position": "Position Name"
    }
  }
}
```

### Menggunakan Token

Setelah login, gunakan token yang diterima pada header `Authorization`:

```bash
curl -X GET http://localhost:8000/api/v1/auth/profile \
  -H "Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz"
```

## Testing

### Manual Testing dengan cURL

**1. Test Login**
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}'
```

**2. Test Get Articles**
```bash
curl -X GET "http://localhost:8000/api/v1/articles?type=news&page=1&per_page=10"
```

**3. Test Submit Question**
```bash
curl -X POST http://localhost:8000/api/v1/information/questions \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "081234567890",
    "identity_number": "1234567890123456",
    "address": "Jakarta",
    "question": "Bagaimana cara mendaftar pelatihan?",
    "question_type": "general"
  }'
```

### Testing dengan Postman

Import file `POSTMAN_COLLECTION.json` ke Postman untuk testing yang lebih mudah.

1. Buka Postman
2. Klik **Import**
3. Pilih file `POSTMAN_COLLECTION.json`
4. Set variable `base_url` ke URL API Anda
5. Mulai testing

### Unit Testing

Jalankan unit test Laravel:

```bash
php artisan test
```

## Response Format

### Success Response

```json
{
  "success": true,
  "message": "Optional success message",
  "data": {}
}
```

### Error Response

```json
{
  "success": false,
  "message": "Error message",
  "errors": {
    "field_name": ["Error detail"]
  }
}
```

### HTTP Status Codes

- `200 OK` - Request berhasil
- `201 Created` - Resource berhasil dibuat
- `400 Bad Request` - Request tidak valid
- `401 Unauthorized` - Autentikasi diperlukan
- `403 Forbidden` - Akses ditolak
- `404 Not Found` - Resource tidak ditemukan
- `422 Unprocessable Entity` - Validasi error
- `500 Internal Server Error` - Server error

## Troubleshooting

### Error: "Unauthenticated"

**Solusi:**
- Pastikan token valid dan belum expired
- Pastikan header `Authorization: Bearer {token}` sudah benar
- Cek apakah user masih aktif di database

### Error: "CORS policy"

**Solusi:**
- Pastikan konfigurasi CORS di `config/cors.php` sudah benar
- Tambahkan domain aplikasi mobile ke `allowed_origins`

### Error: "Route not found"

**Solusi:**
- Pastikan endpoint URL sudah benar
- Jalankan `php artisan route:list` untuk melihat semua route
- Clear cache route dengan `php artisan route:clear`

### Error: "Storage link not found"

**Solusi:**
```bash
php artisan storage:link
```

### Error: "Token mismatch"

**Solusi:**
- Clear cache: `php artisan cache:clear`
- Clear config: `php artisan config:clear`
- Regenerate key: `php artisan key:generate`

## Deployment

### Production Checklist

1. **Set environment ke production**
   ```
   APP_ENV=production
   APP_DEBUG=false
   ```

2. **Optimize aplikasi**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

3. **Set CORS untuk domain production**
   Edit `config/cors.php` dan tambahkan domain production

4. **Enable HTTPS**
   Pastikan API hanya diakses melalui HTTPS di production

5. **Set rate limiting yang sesuai**
   Edit rate limiting di `app/Providers/RouteServiceProvider.php`

6. **Backup database secara berkala**

## Security Best Practices

1. **Gunakan HTTPS** - Selalu gunakan HTTPS di production
2. **Validate Input** - Semua input sudah divalidasi
3. **Rate Limiting** - Sudah dikonfigurasi untuk mencegah abuse
4. **Token Expiration** - Set token expiration yang sesuai
5. **Password Hashing** - Password di-hash menggunakan bcrypt
6. **SQL Injection Protection** - Menggunakan Eloquent ORM
7. **XSS Protection** - Laravel sudah memiliki built-in protection

## Support

Untuk pertanyaan atau masalah, silakan buat issue di GitHub repository.

## License

[MIT License](LICENSE)
