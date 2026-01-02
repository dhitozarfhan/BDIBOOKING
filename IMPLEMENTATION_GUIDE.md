# Implementation Guide - BDIYK Mobile API

Panduan implementasi untuk mengintegrasikan API BDIYK ke aplikasi mobile.

## Daftar Isi

1. [Arsitektur API](#arsitektur-api)
2. [File yang Dibuat](#file-yang-dibuat)
3. [Langkah Implementasi](#langkah-implementasi)
4. [Integrasi dengan Aplikasi Mobile](#integrasi-dengan-aplikasi-mobile)
5. [Best Practices](#best-practices)

## Arsitektur API

### Struktur Folder

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Api/
│   │       ├── AuthController.php
│   │       ├── ArticleController.php
│   │       ├── CategoryController.php
│   │       ├── SlideshowController.php
│   │       ├── InformationController.php
│   │       ├── GratificationController.php
│   │       └── WbsController.php
│   └── Middleware/
│       └── ForceJsonResponse.php
├── Exceptions/
│   └── ApiExceptionHandler.php
└── Models/
    ├── Article.php
    ├── Category.php
    ├── Employee.php
    ├── Question.php
    ├── InformationRequest.php
    ├── Gratification.php
    └── Wbs.php

routes/
└── api.php

database/
└── migrations/
    └── 2025_01_02_000001_add_api_fields_to_tables.php
```

## File yang Dibuat

### 1. Controllers

#### AuthController.php
Menangani autentikasi user menggunakan Laravel Sanctum:
- Login
- Logout
- Get Profile

#### ArticleController.php
Menangani operasi artikel (news, gallery, page):
- Get all articles dengan filter
- Get article by ID
- Get article by slug

#### CategoryController.php
Menangani kategori artikel:
- Get all categories
- Get category by ID

#### SlideshowController.php
Menangani slideshow untuk homepage:
- Get active slideshows

#### InformationController.php
Menangani layanan informasi publik:
- Submit question
- Check question status
- Submit information request
- Check request status

#### GratificationController.php
Menangani laporan gratifikasi:
- Submit gratification report
- Check report status
- Get all reports (authenticated)

#### WbsController.php
Menangani laporan whistleblowing:
- Submit WBS report
- Check report status
- Get all reports (authenticated)

### 2. Middleware

#### ForceJsonResponse.php
Memastikan semua response API dalam format JSON.

### 3. Exception Handler

#### ApiExceptionHandler.php
Menangani semua exception dan mengembalikan response JSON yang konsisten.

### 4. Routes

#### api.php
Mendefinisikan semua endpoint API dengan prefix `/api/v1`.

### 5. Migration

#### 2025_01_02_000001_add_api_fields_to_tables.php
Menambahkan field yang diperlukan untuk API ke tabel existing.

## Langkah Implementasi

### Step 1: Persiapan Database

1. **Jalankan migration baru**
   ```bash
   php artisan migrate
   ```

2. **Verifikasi tabel**
   Pastikan tabel berikut memiliki field yang diperlukan:
   - `questions`: registration_code, status, answer, answered_at
   - `information_requests`: registration_code, status, response, documents, responded_at
   - `gratifications`: report_code, status, response, responded_at
   - `wbs`: report_code, status, response, responded_at

### Step 2: Konfigurasi Environment

1. **Edit file .env**
   ```env
   # Sanctum Configuration
   SANCTUM_STATEFUL_DOMAINS=localhost,127.0.0.1
   
   # CORS Configuration
   CORS_ALLOWED_ORIGINS=*
   ```

2. **Clear cache**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   ```

### Step 3: Setup Storage

1. **Create storage link**
   ```bash
   php artisan storage:link
   ```

2. **Set permissions**
   ```bash
   chmod -R 775 storage
   chmod -R 775 bootstrap/cache
   ```

### Step 4: Testing API

1. **Start Laravel server**
   ```bash
   php artisan serve
   ```

2. **Run test script**
   ```bash
   ./test_api.sh
   ```

3. **Import Postman collection**
   - Import file `POSTMAN_COLLECTION.json` ke Postman
   - Set base_url ke `http://localhost:8000/api/v1`
   - Test semua endpoint

### Step 5: Verifikasi Endpoint

Verifikasi bahwa semua endpoint berfungsi dengan baik:

```bash
# Test public endpoints
curl http://localhost:8000/api/v1/slideshows
curl http://localhost:8000/api/v1/categories
curl http://localhost:8000/api/v1/articles

# Test authentication
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}'
```

## Integrasi dengan Aplikasi Mobile

### Android (Kotlin/Java)

#### 1. Setup Retrofit

**build.gradle**
```gradle
dependencies {
    implementation 'com.squareup.retrofit2:retrofit:2.9.0'
    implementation 'com.squareup.retrofit2:converter-gson:2.9.0'
    implementation 'com.squareup.okhttp3:logging-interceptor:4.9.0'
}
```

#### 2. Create API Service

**ApiService.kt**
```kotlin
interface ApiService {
    @POST("auth/login")
    suspend fun login(@Body credentials: LoginRequest): Response<LoginResponse>
    
    @GET("articles")
    suspend fun getArticles(
        @Query("type") type: String?,
        @Query("page") page: Int?,
        @Query("per_page") perPage: Int?
    ): Response<ArticlesResponse>
    
    @GET("articles/{id}")
    suspend fun getArticle(@Path("id") id: Int): Response<ArticleDetailResponse>
    
    @POST("information/questions")
    suspend fun submitQuestion(@Body question: QuestionRequest): Response<QuestionResponse>
}
```

#### 3. Create Retrofit Instance

**RetrofitClient.kt**
```kotlin
object RetrofitClient {
    private const val BASE_URL = "https://your-domain.com/api/v1/"
    
    private val loggingInterceptor = HttpLoggingInterceptor().apply {
        level = HttpLoggingInterceptor.Level.BODY
    }
    
    private val authInterceptor = Interceptor { chain ->
        val token = TokenManager.getToken()
        val request = chain.request().newBuilder()
            .addHeader("Accept", "application/json")
            .apply {
                if (token != null) {
                    addHeader("Authorization", "Bearer $token")
                }
            }
            .build()
        chain.proceed(request)
    }
    
    private val client = OkHttpClient.Builder()
        .addInterceptor(loggingInterceptor)
        .addInterceptor(authInterceptor)
        .build()
    
    val apiService: ApiService by lazy {
        Retrofit.Builder()
            .baseUrl(BASE_URL)
            .client(client)
            .addConverterFactory(GsonConverterFactory.create())
            .build()
            .create(ApiService::class.java)
    }
}
```

#### 4. Example Usage

**LoginViewModel.kt**
```kotlin
class LoginViewModel : ViewModel() {
    fun login(username: String, password: String) {
        viewModelScope.launch {
            try {
                val response = RetrofitClient.apiService.login(
                    LoginRequest(username, password)
                )
                if (response.isSuccessful) {
                    val token = response.body()?.data?.token
                    TokenManager.saveToken(token)
                    // Navigate to home
                } else {
                    // Handle error
                }
            } catch (e: Exception) {
                // Handle exception
            }
        }
    }
}
```

### iOS (Swift)

#### 1. Create API Service

**APIService.swift**
```swift
class APIService {
    static let shared = APIService()
    private let baseURL = "https://your-domain.com/api/v1"
    
    func login(username: String, password: String, completion: @escaping (Result<LoginResponse, Error>) -> Void) {
        let url = URL(string: "\(baseURL)/auth/login")!
        var request = URLRequest(url: url)
        request.httpMethod = "POST"
        request.setValue("application/json", forHTTPHeaderField: "Content-Type")
        
        let body: [String: Any] = [
            "username": username,
            "password": password
        ]
        request.httpBody = try? JSONSerialization.data(withJSONObject: body)
        
        URLSession.shared.dataTask(with: request) { data, response, error in
            if let error = error {
                completion(.failure(error))
                return
            }
            
            guard let data = data else {
                completion(.failure(NSError(domain: "", code: -1, userInfo: nil)))
                return
            }
            
            do {
                let loginResponse = try JSONDecoder().decode(LoginResponse.self, from: data)
                completion(.success(loginResponse))
            } catch {
                completion(.failure(error))
            }
        }.resume()
    }
    
    func getArticles(type: String? = nil, page: Int = 1, perPage: Int = 10, completion: @escaping (Result<ArticlesResponse, Error>) -> Void) {
        var urlComponents = URLComponents(string: "\(baseURL)/articles")!
        var queryItems: [URLQueryItem] = [
            URLQueryItem(name: "page", value: "\(page)"),
            URLQueryItem(name: "per_page", value: "\(perPage)")
        ]
        if let type = type {
            queryItems.append(URLQueryItem(name: "type", value: type))
        }
        urlComponents.queryItems = queryItems
        
        var request = URLRequest(url: urlComponents.url!)
        request.httpMethod = "GET"
        request.setValue("application/json", forHTTPHeaderField: "Accept")
        
        URLSession.shared.dataTask(with: request) { data, response, error in
            // Handle response similar to login
        }.resume()
    }
}
```

#### 2. Create Models

**Models.swift**
```swift
struct LoginResponse: Codable {
    let success: Bool
    let message: String
    let data: LoginData
}

struct LoginData: Codable {
    let token: String
    let user: User
}

struct User: Codable {
    let id: Int
    let username: String
    let name: String
    let email: String
    let image: String?
    let position: String?
}

struct ArticlesResponse: Codable {
    let success: Bool
    let data: ArticlesData
}

struct ArticlesData: Codable {
    let currentPage: Int
    let data: [Article]
    let total: Int
    let perPage: Int
    let lastPage: Int
    
    enum CodingKeys: String, CodingKey {
        case currentPage = "current_page"
        case data
        case total
        case perPage = "per_page"
        case lastPage = "last_page"
    }
}

struct Article: Codable {
    let id: Int
    let title: String
    let slug: String
    let summary: String?
    let image: String?
    let publishedAt: String
    let hit: Int
    
    enum CodingKeys: String, CodingKey {
        case id, title, slug, summary, image, hit
        case publishedAt = "published_at"
    }
}
```

### React Native

#### 1. Setup Axios

```bash
npm install axios
```

#### 2. Create API Client

**api.js**
```javascript
import axios from 'axios';
import AsyncStorage from '@react-native-async-storage/async-storage';

const API_BASE_URL = 'https://your-domain.com/api/v1';

const apiClient = axios.create({
  baseURL: API_BASE_URL,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json',
  },
});

// Add token to requests
apiClient.interceptors.request.use(
  async (config) => {
    const token = await AsyncStorage.getItem('token');
    if (token) {
      config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
  },
  (error) => {
    return Promise.reject(error);
  }
);

// Handle responses
apiClient.interceptors.response.use(
  (response) => response,
  async (error) => {
    if (error.response?.status === 401) {
      await AsyncStorage.removeItem('token');
      // Navigate to login
    }
    return Promise.reject(error);
  }
);

export const authAPI = {
  login: (username, password) =>
    apiClient.post('/auth/login', { username, password }),
  
  logout: () =>
    apiClient.post('/auth/logout'),
  
  getProfile: () =>
    apiClient.get('/auth/profile'),
};

export const articlesAPI = {
  getArticles: (params) =>
    apiClient.get('/articles', { params }),
  
  getArticle: (id) =>
    apiClient.get(`/articles/${id}`),
};

export const informationAPI = {
  submitQuestion: (data) =>
    apiClient.post('/information/questions', data),
  
  checkQuestion: (code) =>
    apiClient.get(`/information/questions/${code}`),
};

export default apiClient;
```

#### 3. Example Usage

**LoginScreen.js**
```javascript
import React, { useState } from 'react';
import { View, TextInput, Button, Alert } from 'react-native';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { authAPI } from './api';

const LoginScreen = ({ navigation }) => {
  const [username, setUsername] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);

  const handleLogin = async () => {
    setLoading(true);
    try {
      const response = await authAPI.login(username, password);
      if (response.data.success) {
        await AsyncStorage.setItem('token', response.data.data.token);
        await AsyncStorage.setItem('user', JSON.stringify(response.data.data.user));
        navigation.replace('Home');
      }
    } catch (error) {
      Alert.alert('Error', error.response?.data?.message || 'Login failed');
    } finally {
      setLoading(false);
    }
  };

  return (
    <View>
      <TextInput
        placeholder="Username"
        value={username}
        onChangeText={setUsername}
      />
      <TextInput
        placeholder="Password"
        value={password}
        onChangeText={setPassword}
        secureTextEntry
      />
      <Button
        title={loading ? 'Loading...' : 'Login'}
        onPress={handleLogin}
        disabled={loading}
      />
    </View>
  );
};

export default LoginScreen;
```

## Best Practices

### 1. Token Management

- **Simpan token dengan aman** menggunakan Keychain (iOS) atau EncryptedSharedPreferences (Android)
- **Refresh token** jika menggunakan token expiration
- **Hapus token** saat logout atau token invalid

### 2. Error Handling

```javascript
try {
  const response = await apiClient.get('/articles');
  // Handle success
} catch (error) {
  if (error.response) {
    // Server responded with error
    const { status, data } = error.response;
    if (status === 401) {
      // Unauthorized - redirect to login
    } else if (status === 422) {
      // Validation error
      console.log(data.errors);
    } else {
      // Other errors
      console.log(data.message);
    }
  } else if (error.request) {
    // Request made but no response
    console.log('Network error');
  } else {
    // Other errors
    console.log(error.message);
  }
}
```

### 3. Caching

Implementasikan caching untuk meningkatkan performa:
- Cache data yang jarang berubah (categories, slideshows)
- Implement pull-to-refresh untuk update data
- Use pagination untuk list data

### 4. Loading States

Selalu tampilkan loading indicator saat fetching data:
```javascript
const [loading, setLoading] = useState(false);
const [data, setData] = useState(null);

const fetchData = async () => {
  setLoading(true);
  try {
    const response = await apiClient.get('/articles');
    setData(response.data.data);
  } catch (error) {
    // Handle error
  } finally {
    setLoading(false);
  }
};
```

### 5. Image Loading

Gunakan library untuk lazy loading images:
- Android: Glide atau Coil
- iOS: SDWebImage atau Kingfisher
- React Native: react-native-fast-image

### 6. Offline Support

Implementasikan offline support untuk UX yang lebih baik:
- Cache data locally
- Queue requests saat offline
- Sync saat kembali online

## Troubleshooting

### Issue: CORS Error

**Solution:**
```php
// config/cors.php
'allowed_origins' => ['https://your-mobile-app.com'],
```

### Issue: Token Not Working

**Solution:**
- Pastikan format header: `Authorization: Bearer {token}`
- Cek token masih valid
- Verify token di database

### Issue: 404 Not Found

**Solution:**
- Cek endpoint URL
- Verify route dengan `php artisan route:list`
- Clear route cache: `php artisan route:clear`

### Issue: Validation Error

**Solution:**
- Cek required fields
- Verify data types
- Check validation rules di controller

## Support

Untuk pertanyaan lebih lanjut, silakan hubungi tim development atau buat issue di GitHub repository.
