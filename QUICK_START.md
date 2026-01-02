# Quick Start Guide - BDIYK Mobile API

Panduan cepat untuk memulai menggunakan BDIYK Mobile API.

## 🚀 Quick Installation

```bash
# 1. Navigate to project directory
cd bdiyk-baru

# 2. Install dependencies (jika belum)
composer install

# 3. Configure environment
cp .env.example .env
# Edit .env dan sesuaikan DB_DATABASE, DB_USERNAME, DB_PASSWORD

# 4. Generate application key
php artisan key:generate

# 5. Run migrations
php artisan migrate

# 6. Create storage link
php artisan storage:link

# 7. Start server
php artisan serve
```

API akan berjalan di: `http://localhost:8000`

## 📋 Quick Test

### Test dengan cURL

```bash
# Test get slideshows
curl http://localhost:8000/api/v1/slideshows

# Test get articles
curl http://localhost:8000/api/v1/articles?type=news&per_page=5

# Test get categories
curl http://localhost:8000/api/v1/categories
```

### Test dengan Script

```bash
# Run test script
./test_api.sh
```

### Test dengan Postman

1. Import file `POSTMAN_COLLECTION.json` ke Postman
2. Set variable `base_url` = `http://localhost:8000/api/v1`
3. Klik "Send" pada setiap request untuk testing

## 📖 API Endpoints

### Public Endpoints (No Auth Required)

```
GET    /api/v1/slideshows                    - Get active slideshows
GET    /api/v1/categories                    - Get all categories
GET    /api/v1/articles                      - Get all articles
GET    /api/v1/articles/{id}                 - Get article detail
POST   /api/v1/information/questions         - Submit question
GET    /api/v1/information/questions/{code}  - Check question status
POST   /api/v1/gratification/reports         - Submit gratification report
POST   /api/v1/wbs/reports                   - Submit WBS report
```

### Protected Endpoints (Auth Required)

```
POST   /api/v1/auth/login                    - Login
POST   /api/v1/auth/logout                   - Logout
GET    /api/v1/auth/profile                  - Get user profile
GET    /api/v1/gratification/reports         - Get all gratification reports
GET    /api/v1/wbs/reports                   - Get all WBS reports
```

## 🔑 Authentication

### 1. Login

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
      "email": "john@example.com"
    }
  }
}
```

### 2. Use Token

```bash
curl -X GET http://localhost:8000/api/v1/auth/profile \
  -H "Authorization: Bearer 1|abcdefghijklmnopqrstuvwxyz"
```

## 📱 Mobile Integration

### Android (Kotlin)

```kotlin
// Add Retrofit dependency
implementation 'com.squareup.retrofit2:retrofit:2.9.0'
implementation 'com.squareup.retrofit2:converter-gson:2.9.0'

// API Service
interface ApiService {
    @POST("auth/login")
    suspend fun login(@Body credentials: LoginRequest): Response<LoginResponse>
    
    @GET("articles")
    suspend fun getArticles(): Response<ArticlesResponse>
}

// Usage
val response = apiService.login(LoginRequest(username, password))
if (response.isSuccessful) {
    val token = response.body()?.data?.token
    // Save token and navigate
}
```

### iOS (Swift)

```swift
// API Service
func login(username: String, password: String) {
    let url = URL(string: "http://your-domain.com/api/v1/auth/login")!
    var request = URLRequest(url: url)
    request.httpMethod = "POST"
    request.setValue("application/json", forHTTPHeaderField: "Content-Type")
    
    let body = ["username": username, "password": password]
    request.httpBody = try? JSONSerialization.data(withJSONObject: body)
    
    URLSession.shared.dataTask(with: request) { data, response, error in
        // Handle response
    }.resume()
}
```

### React Native

```javascript
// Install axios
npm install axios

// API Client
import axios from 'axios';

const apiClient = axios.create({
  baseURL: 'http://your-domain.com/api/v1',
  headers: {
    'Content-Type': 'application/json',
  },
});

// Login
const login = async (username, password) => {
  const response = await apiClient.post('/auth/login', {
    username,
    password,
  });
  return response.data;
};
```

## 📚 Documentation

| File | Description |
|------|-------------|
| `API_DESIGN.md` | Complete API design and endpoints documentation |
| `API_README.md` | Installation and usage guide |
| `IMPLEMENTATION_GUIDE.md` | Mobile integration guide with code examples |
| `API_ENV_EXAMPLE.md` | Environment configuration examples |
| `API_CHANGES_SUMMARY.md` | Summary of all changes and features |
| `POSTMAN_COLLECTION.json` | Postman collection for testing |
| `test_api.sh` | Bash script for automated testing |

## 🔧 Configuration

### Database

Edit `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=bdiyk_db
DB_USERNAME=root
DB_PASSWORD=
```

### CORS

Edit `config/cors.php`:
```php
'allowed_origins' => ['*'], // or specific domains
```

### Rate Limiting

Default: 60 requests per minute

Edit in `app/Providers/RouteServiceProvider.php` to change.

## 🐛 Troubleshooting

### Issue: CORS Error
**Solution:** Configure `config/cors.php` with your mobile app domain

### Issue: 404 Not Found
**Solution:** Run `php artisan route:clear`

### Issue: Token not working
**Solution:** Check header format: `Authorization: Bearer {token}`

### Issue: Storage link not found
**Solution:** Run `php artisan storage:link`

## 📞 Support

- **Documentation**: Check all `API_*.md` files
- **Testing**: Use `test_api.sh` or Postman collection
- **Issues**: Create issue on GitHub repository

## ✅ Checklist

Before deploying to production:

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Configure proper `CORS_ALLOWED_ORIGINS`
- [ ] Enable HTTPS
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Set proper rate limiting
- [ ] Backup database
- [ ] Test all endpoints

## 🎯 Next Steps

1. **Test all endpoints** using Postman or test script
2. **Read documentation** in `IMPLEMENTATION_GUIDE.md`
3. **Integrate with mobile app** using code examples
4. **Configure production** environment
5. **Deploy to server**

## 📦 Files Included

```
app/Http/Controllers/Api/
├── AuthController.php
├── ArticleController.php
├── CategoryController.php
├── SlideshowController.php
├── InformationController.php
├── GratificationController.php
└── WbsController.php

app/Http/Middleware/
└── ForceJsonResponse.php

app/Exceptions/
└── ApiExceptionHandler.php

routes/
└── api.php

database/migrations/
└── 2025_01_02_000001_add_api_fields_to_tables.php

Documentation/
├── API_DESIGN.md
├── API_README.md
├── IMPLEMENTATION_GUIDE.md
├── API_ENV_EXAMPLE.md
├── API_CHANGES_SUMMARY.md
├── QUICK_START.md
├── POSTMAN_COLLECTION.json
└── test_api.sh
```

## 🚀 Ready to Go!

Your BDIYK Mobile API is now ready to use. Start testing with the provided tools and integrate with your mobile application.

**Happy Coding! 🎉**
