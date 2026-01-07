# Rencana API - Frontend Fokus

## Tujuan
Mengganti dan menyederhanakan API saat ini (v1) untuk fokus pada kebutuhan frontend (Livewire) tanpa autentikasi, dengan menghapus atau memperbaiki endpoint yang tidak diperlukan untuk frontend publik.

## Analisis API Saat Ini (v1)

### Endpoint Publik (Tidak Memerlukan Autentikasi)
- `GET /api/v1/articles` - Daftar artikel
- `GET /api/v1/articles/{id}` - Detail artikel
- `GET /api/v1/articles/slug/{slug}` - Artikel berdasarkan slug
- `GET /api/v1/categories` - Daftar kategori
- `GET /api/v1/categories/{id}` - Detail kategori
- `GET /api/v1/slideshows` - Slide tampilan utama
- `POST /api/v1/information/questions` - Submit pertanyaan
- `GET /api/v1/information/questions/{registration_code}` - Cek status pertanyaan
- `POST /api/v1/information/requests` - Submit permintaan informasi
- `GET /api/v1/information/requests/{registration_code}` - Cek status permintaan
- `POST /api/v1/gratification/reports` - Submit laporan gratifikasi
- `GET /api/v1/gratification/reports/{report_code}` - Cek status laporan gratifikasi
- `POST /api/v1/wbs/reports` - Submit laporan WBS
- `GET /api/v1/wbs/reports/{report_code}` - Cek status laporan WBS

### Endpoint Privat (Memerlukan Autentikasi)
- `POST /api/v1/auth/login` - Login user
- `POST /api/v1/auth/logout` - Logout user
- `GET /api/v1/auth/profile` - Get user profile
- `GET /api/v1/gratification/reports` - Daftar laporan gratifikasi (memerlukan auth)
- `GET /api/v1/wbs/reports` - Daftar laporan WBS (memerlukan auth)

## Rencana API Baru (Frontend Fokus)

### A. Endpoint Publik (Frontend Fokus)

#### 1. Artikel dan Konten
```
GET /api/v1/articles                 # Daftar artikel
GET /api/v1/articles/{id}           # Detail artikel
GET /api/v1/articles/slug/{slug}    # Artikel berdasarkan slug
GET /api/v1/articles/search         # Pencarian artikel
GET /api/v1/articles/latest         # Artikel terbaru
GET /api/v1/articles/popular        # Artikel populer
```

#### 2. Kategori dan Organisasi Konten
```
GET /api/v1/categories              # Daftar kategori
GET /api/v1/categories/{id}         # Detail kategori
GET /api/v1/tags                    # Daftar tag
GET /api/v1/archives                # Arsip artikel
```

#### 3. Slide dan Tampilan Utama
```
GET /api/v1/slideshows             # Slide tampilan utama
GET /api/v1/news                   # Berita terbaru
GET /api/v1/galleries              # Galeri terbaru
```

#### 4. Informasi Publik dan Interaktif
```
# Pertanyaan Publik
POST /api/v1/information/questions    # Submit pertanyaan
GET /api/v1/information/questions/{registration_code}  # Cek status pertanyaan

# Permintaan Informasi
POST /api/v1/information/requests     # Submit permintaan informasi
GET /api/v1/information/requests/{registration_code}   # Cek status permintaan

# Laporan Gratifikasi
POST /api/v1/gratification/reports    # Submit laporan gratifikasi
GET /api/v1/gratification/reports/{report_code}        # Cek status laporan

# Laporan WBS
POST /api/v1/wbs/reports              # Submit laporan WBS
GET /api/v1/wbs/reports/{report_code}                 # Cek status laporan
```

#### 5. Halaman dan Statik
```
GET /api/v1/pages                   # Daftar halaman statis
GET /api/v1/pages/{id}              # Detail halaman
GET /api/v1/about                   # Tentang kami
GET /api/v1/contact                 # Kontak
```

### B. Endpoint yang Akan Dihapus (Tidak Relevan untuk Frontend)

Endpoint berikut **akan dihapus** karena tidak relevan untuk frontend publik:
- `POST /api/v1/auth/login` - Login user (untuk admin saja)
- `POST /api/v1/auth/logout` - Logout user (untuk admin saja)
- `GET /api/v1/auth/profile` - Get user profile (untuk admin saja)
- `GET /api/v1/gratification/reports` - Daftar semua laporan gratifikasi (memerlukan auth)
- `GET /api/v1/wbs/reports` - Daftar semua laporan WBS (memerlukan auth)

## Perbaikan dan Pembenahan

### 1. Endpoint yang Tidak Diperlukan untuk Frontend

#### Endpoint Legacy
- `/api/v1/user` - Endpoint legacy yang tidak diperlukan untuk frontend publik

#### Endpoint Admin/Privat
- Endpoint autentikasi (`/api/v1/auth/*`) - Tidak relevan untuk frontend publik
- Endpoint reports untuk admin (tanpa kode) - Tidak relevan untuk frontend publik

### 2. Endpoint yang Perlu Ditingkatkan

#### Endpoint Tracking dengan Kode Registrasi
Endpoint ini **sangat penting** untuk frontend publik karena:
- Memungkinkan pengguna melacak status permintaan mereka
- Tidak memerlukan login, hanya kode unik
- Memberikan feedback kepada pengguna

#### Endpoint Informasi Publik
Endpoint ini perlu ditingkatkan untuk:
- Menyediakan format respons yang konsisten
- Menambahkan pagination dan filtering
- Menyediakan caching untuk performa lebih baik

## Implementasi Bertahap

### Tahap 1: Persiapan Struktur
1. Buat namespace `App\Http\Controllers\Api\Frontend`
2. Buat resource collections untuk format respons konsisten
3. Siapkan middleware opsional untuk rate limiting

### Tahap 2: Implementasi Endpoint Dasar
1. Artikel dan konten
2. Kategori dan organisasi
3. Slide dan tampilan utama

### Tahap 3: Implementasi Endpoint Interaktif
1. Submit dan tracking pertanyaan
2. Submit dan tracking permintaan informasi
3. Submit dan tracking laporan gratifikasi/WBS

### Tahap 4: Penyempurnaan dan Testing
1. Penambahan dokumentasi API
2. Testing endpoint
3. Optimasi performa dan caching

## Keunggulan API Baru

### 1. Pemisahan yang Jelas
- API publik untuk frontend (tanpa autentikasi)
- API privat untuk admin (dengan autentikasi)
- Tidak ada campuran endpoint yang membingungkan

### 2. Performa Lebih Baik
- Endpoint publik tidak perlu middleware autentikasi
- Format respons konsisten
- Caching yang optimal

### 3. Keamanan Lebih Baik
- Endpoint sensitif tidak tercampur dengan endpoint publik
- Tidak ada risiko akses tidak sah ke data admin

### 4. Dokumentasi Lebih Jelas
- Endpoint publik mudah diidentifikasi
- Contoh penggunaan untuk frontend developer
- Spesifikasi API yang jelas

## Catatan Penting

### Endpoint Reports dengan Kode Registrasi
Endpoint reports dengan kode registrasi **sangat penting** untuk frontend publik karena:
- Memungkinkan pengguna melacak status permintaan mereka
- Tidak memerlukan login, hanya kode unik
- Memberikan pengalaman pengguna yang baik
- Mendukung transparansi dan akuntabilitas

### Endpoint yang Tidak Diperlukan
- Endpoint login/logout untuk frontend publik
- Endpoint profil pengguna untuk frontend publik
- Endpoint daftar semua laporan (tanpa kode) untuk frontend publik

## Kesimpulan
API akan difokuskan pada kebutuhan frontend publik dengan menyediakan endpoint yang relevan dan menghapus endpoint yang tidak diperlukan. Endpoint reports dengan kode registrasi tetap dipertahankan karena sangat penting untuk pengalaman pengguna yang interaktif.