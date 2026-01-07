# Dokumentasi API - Frontend Fokus

## Informasi Umum
API ini dirancang khusus untuk kebutuhan frontend (Livewire) tanpa autentikasi, dengan fokus pada tampilan publik dan fitur interaktif untuk pengguna.

**Base URL:** `http://localhost:8000/api/v1` (atau sesuai konfigurasi)

**Content-Type:** `application/json`

## Endpoint Publik

### 1. Artikel

#### Daftar Artikel
- **URL:** `GET /api/v1/articles`
- **Deskripsi:** Mendapatkan daftar artikel
- **Parameter Query:**
  - `type` (opsional): Filter berdasarkan tipe artikel (news, gallery, page, information)
  - `category_id` (opsional): Filter berdasarkan ID kategori
  - `search` (opsional): Pencarian berdasarkan judul
  - `per_page` (opsional): Jumlah item per halaman (default: 10)
- **Contoh Request:**
  ```
  GET /api/v1/articles?type=news&category_id=1&per_page=5
  ```
- **Contoh Response:**
  ```json
  {
    "success": true,
    "data": {
      "current_page": 1,
      "data": [
        {
          "id": 1,
          "title": "Judul Artikel",
          "slug": "judul-artikel-1",
          "summary": "Ringkasan artikel",
          "image": "http://localhost:8000/storage/path/to/image.jpg",
          "category": {
            "id": 1,
            "name": "Kategori"
          },
          "author": {
            "id": 1,
            "name": "Nama Penulis",
            "image": "http://localhost:8000/storage/path/to/author.jpg"
          },
          "published_at": "2023-01-01T00:00:00.000000Z",
          "hit": 100
        }
      ],
      "total": 100,
      "per_page": 5,
      "last_page": 20
    }
  }
  ```

#### Detail Artikel
- **URL:** `GET /api/v1/articles/{id}`
- **Deskripsi:** Mendapatkan detail artikel berdasarkan ID
- **Parameter Path:**
  - `id`: ID artikel
- **Contoh Request:**
  ```
  GET /api/v1/articles/1
  ```
- **Contoh Response:**
  ```json
  {
    "success": true,
    "data": {
      "id": 1,
      "title": "Judul Artikel",
      "slug": "judul-artikel-1",
      "summary": "Ringkasan artikel",
      "content": "Isi lengkap artikel",
      "image": "http://localhost:8000/storage/path/to/image.jpg",
      "category": {
        "id": 1,
        "name": "Kategori"
      },
      "author": {
        "id": 1,
        "name": "Nama Penulis",
        "image": "http://localhost:8000/storage/path/to/author.jpg"
      },
      "tags": [
        {
          "id": 1,
          "name": "Tag 1"
        }
      ],
      "images": [
        {
          "id": 1,
          "url": "http://localhost:8000/storage/path/to/gallery.jpg",
          "caption": "Keterangan gambar"
        }
      ],
      "published_at": "2023-01-01T00:00:00.000000Z",
      "hit": 101
    }
  }
  ```

#### Artikel Berdasarkan Slug
- **URL:** `GET /api/v1/articles/slug/{slug}`
- **Deskripsi:** Mendapatkan detail artikel berdasarkan slug
- **Parameter Path:**
  - `slug`: Slug artikel
- **Contoh Request:**
  ```
  GET /api/v1/articles/slug/judul-artikel-1
  ```
- **Contoh Response:** Sama seperti detail artikel

### 2. Kategori

#### Daftar Kategori
- **URL:** `GET /api/v1/categories`
- **Deskripsi:** Mendapatkan daftar kategori
- **Parameter Query:**
  - `type` (opsional): Filter berdasarkan tipe kategori
- **Contoh Request:**
  ```
  GET /api/v1/categories
  ```
- **Contoh Response:**
  ```json
  {
    "success": true,
    "data": [
      {
        "id": 1,
        "name": "Nama Kategori",
        "slug": "nama-kategori-1",
        "articles_count": 10
      }
    ]
  }
  ```

#### Detail Kategori
- **URL:** `GET /api/v1/categories/{id}`
- **Deskripsi:** Mendapatkan detail kategori berdasarkan ID
- **Parameter Path:**
  - `id`: ID kategori
- **Contoh Request:**
  ```
  GET /api/v1/categories/1
  ```
- **Contoh Response:**
  ```json
  {
    "success": true,
    "data": {
      "id": 1,
      "name": "Nama Kategori",
      "slug": "nama-kategori-1",
      "description": "Deskripsi kategori",
      "articles_count": 10
    }
  }
  ```

### 3. Slideshow

#### Daftar Slideshow
- **URL:** `GET /api/v1/slideshows`
- **Deskripsi:** Mendapatkan daftar slideshow yang aktif
- **Contoh Request:**
  ```
  GET /api/v1/slideshows
  ```
- **Contoh Response:**
  ```json
  {
    "success": true,
    "data": [
      {
        "id": 1,
        "title": "Judul Slideshow",
        "image": "http://localhost:8000/storage/path/to/slide.jpg",
        "link": "http://example.com",
        "order": 1
      }
    ]
  }
  ```

### 4. Informasi Publik

#### Submit Pertanyaan
- **URL:** `POST /api/v1/questions`
- **Deskripsi:** Mengirim pertanyaan publik
- **Header:**
  - `Content-Type: application/json`
- **Body:**
  ```json
  {
    "reporter_name": "Nama Pelapor",
    "email": "email@example.com",
    "mobile": "081234567890",
    "identity_number": "Nomor identitas",
    "content": "Pertanyaan yang ingin diajukan",
    "report_title": "Judul pertanyaan",
    "identity_card_attachment": "path/to/attachment (opsional)"
  }
  ```
- **Contoh Request:**
  ```
  POST /api/v1/questions
  Content-Type: application/json

  {
    "reporter_name": "John Doe",
    "email": "john@example.com",
    "mobile": "081234567890",
    "identity_number": "1234567890",
    "content": "Apa itu BDI Yogyakarta?",
    "report_title": "Pertanyaan tentang BDI"
  }
  ```
- **Contoh Response:**
  ```json
  {
    "success": true,
    "message": "Question submitted successfully",
    "data": {
      "registration_code": "A7X9K2"
    }
  }
  ```

#### Cek Status Pertanyaan
- **URL:** `GET /api/v1/questions/{registration_code}`
- **Deskripsi:** Melihat status pertanyaan berdasarkan kode registrasi
- **Parameter Path:**
  - `registration_code`: Kode registrasi pertanyaan (6 karakter acak)
- **Contoh Request:**
  ```
  GET /api/v1/questions/A7X9K2
  ```
- **Contoh Response:**
  ```json
  {
    "success": true,
    "data": {
      "registration_code": "A7X9K2",
      "reporter_name": "John Doe",
      "content": "Apa itu BDI Yogyakarta?",
      "report_title": "Pertanyaan tentang BDI",
      "mobile": "081234567890",
      "email": "john@example.com",
      "identity_number": "1234567890",
      "identity_card_attachment": null,
      "status": "Initiation",
      "history": [
        {
          "status": "Initiation",
          "answer": null,
          "answer_attachment": null,
          "created_at": "2023-01-01T00:00:00.000000Z"
        }
      ],
      "created_at": "2023-01-01T00:00:00.000000Z"
    }
  }
  ```

#### Submit Permintaan Informasi
- **URL:** `POST /api/v1/information-requests`
- **Deskripsi:** Mengirim permintaan informasi publik
- **Header:**
  - `Content-Type: application/json`
- **Body:**
  ```json
  {
    "reporter_name": "Nama Pelapor",
    "id_card_number": "Nomor KTP",
    "address": "Alamat lengkap",
    "occupation": "Pekerjaan",
    "mobile": "Nomor HP",
    "email": "Email",
    "report_title": "Judul permintaan informasi",
    "used_for": "Tujuan penggunaan informasi",
    "grab_method": ["see", "read", "hear", "write", "hardcopy", "softcopy"],
    "delivery_method": ["direct", "courier", "post", "fax", "email"],
    "rule_accepted": true,
    "identity_card_attachment": "path/to/attachment (opsional)"
  }
  ```
- **Contoh Request:**
  ```
  POST /api/v1/information-requests
  Content-Type: application/json

  {
    "reporter_name": "John Doe",
    "id_card_number": "1234567890",
    "address": "Jl. Contoh No. 123",
    "occupation": "Wiraswasta",
    "mobile": "081234567890",
    "email": "john@example.com",
    "report_title": "Permintaan Informasi Program Pelatihan",
    "used_for": "Untuk keperluan penelitian",
    "grab_method": ["read", "softcopy"],
    "delivery_method": ["email"],
    "rule_accepted": true
  }
  ```
- **Contoh Response:**
  ```json
  {
    "success": true,
    "message": "Information request submitted successfully",
    "data": {
      "registration_code": "M3B8P1"
    }
  }
  ```

#### Cek Status Permintaan Informasi
- **URL:** `GET /api/v1/information-requests/{registration_code}`
- **Deskripsi:** Melihat status permintaan informasi berdasarkan kode registrasi
- **Parameter Path:**
  - `registration_code`: Kode registrasi permintaan informasi (6 karakter acak)
- **Contoh Request:**
  ```
  GET /api/v1/information-requests/M3B8P1
  ```
- **Contoh Response:**
  ```json
  {
    "success": true,
    "data": {
      "registration_code": "M3B8P1",
      "reporter_name": "John Doe",
      "id_card_number": "1234567890",
      "address": "Jl. Contoh No. 123",
      "occupation": "Wiraswasta",
      "mobile": "081234567890",
      "email": "john@example.com",
      "report_title": "Permintaan Informasi Program Pelatihan",
      "used_for": "Untuk keperluan penelitian",
      "grab_method": ["read", "softcopy"],
      "delivery_method": [
        "email"
      ],
      "rule_accepted": true,
      "status": "Initiation",
      "history": [
        {
          "status": "Initiation",
          "answer": null,
          "answer_attachment": null,
          "created_at": "2023-01-01T00:00:00.000000Z"
        }
      ],
      "created_at": "2023-01-01T00:00:00.000000Z"
    }
  }
  ```

### 5. Laporan Gratifikasi

#### Submit Laporan Gratifikasi
- **URL:** `POST /api/v1/gratification/reports`
- **Deskripsi:** Mengirim laporan gratifikasi
- **Header:**
  - `Content-Type: application/json`
- **Body:**
  ```json
  {
    "reporter_name": "Nama Pelapor",
    "identity_number": "Nomor identitas",
    "address": "Alamat lengkap",
    "occupation": "Pekerjaan",
    "phone": "Nomor telepon",
    "email": "Email",
    "report_title": "Judul laporan",
    "report_description": "Deskripsi laporan gratifikasi",
    "attachment": "path/to/attachment (opsional)",
    "identity_card_attachment": "path/to/identity_card_attachment (opsional)"
  }
  ```
- **Contoh Request:**
  ```
  POST /api/v1/gratification/reports
  Content-Type: application/json

  {
    "reporter_name": "John Doe",
    "identity_number": "1234567890",
    "address": "Jl. Contoh No. 123",
    "occupation": "Wiraswasta",
    "phone": "081234567890",
    "email": "john@example.com",
    "report_title": "Laporan Gratifikasi",
    "report_description": "Saya melihat gratifikasi terjadi di..."
  }
  ```
- **Contoh Response:**
  ```json
  {
    "success": true,
    "message": "Gratification report submitted successfully",
    "data": {
      "report_code": "SYL47V"
    }
  }
  ```

#### Cek Status Laporan Gratifikasi
- **URL:** `GET /api/v1/gratification/reports/{report_code}`
- **Deskripsi:** Melihat status laporan gratifikasi berdasarkan kode laporan
- **Parameter Path:**
  - `report_code`: Kode laporan gratifikasi (6 karakter acak)
- **Contoh Request:**
  ```
  GET /api/v1/gratification/reports/SYL47V
  ```
- **Contoh Response:**
  ```json
  {
    "success": true,
    "data": {
      "report_code": "SYL47V",
      "reporter_name": "John Doe",
      "identity_number": "1234567890",
      "address": "Jl. Contoh No. 123",
      "occupation": "Wiraswasta",
      "phone": "081234567890",
      "email": "john@example.com",
      "report_title": "Laporan Gratifikasi",
      "report_description": "Saya melihat gratifikasi terjadi di...",
      "attachment": null,
      "identity_card_attachment": null,
      "status": "Initiation",
      "history": [
        {
          "status": "Initiation",
          "answer": null,
          "answer_attachment": null,
          "created_at": "2023-01-01T00:00:00.000000Z"
        }
      ],
      "created_at": "2023-01-01T00:00:00.000000Z"
    }
  }
  ```

### 6. Laporan WBS (Whistle Blowing System)

#### Submit Laporan WBS
- **URL:** `POST /api/v1/wbs/reports`
- **Deskripsi:** Mengirim laporan WBS (Whistle Blowing System)
- **Header:**
  - `Content-Type: application/json`
- **Body:**
  ```json
  {
    "reporter_name": "Nama Pelapor",
    "identity_number": "Nomor identitas",
    "address": "Alamat lengkap",
    "occupation": "Pekerjaan",
    "phone": "Nomor telepon",
    "email": "Email",
    "report_title": "Judul laporan",
    "report_description": "Deskripsi laporan WBS",
    "attachment": "path/to/attachment (opsional)",
    "identity_card_attachment": "path/to/identity_card_attachment (opsional)",
    "violation_id": "ID pelanggaran (opsional)"
  }
  ```
- **Contoh Request:**
  ```
  POST /api/v1/wbs/reports
  Content-Type: application/json

  {
    "reporter_name": "John Doe",
    "identity_number": "1234567890",
    "address": "Jl. Contoh No. 123",
    "occupation": "Wiraswasta",
    "phone": "081234567890",
    "email": "john@example.com",
    "report_title": "Laporan Pelanggaran",
    "report_description": "Saya melihat pelanggaran terjadi di..."
  }
  ```
- **Contoh Response:**
  ```json
  {
    "success": true,
    "message": "WBS report submitted successfully",
    "data": {
      "report_code": "G13UZ0"
    }
  }
  ```

#### Cek Status Laporan WBS
- **URL:** `GET /api/v1/wbs/reports/{report_code}`
- **Deskripsi:** Melihat status laporan WBS berdasarkan kode laporan
- **Parameter Path:**
  - `report_code`: Kode laporan WBS (6 karakter acak)
- **Contoh Request:**
  ```
  GET /api/v1/wbs/reports/G13UZ0
  ```
- **Contoh Response:**
  ```json
  {
    "success": true,
    "data": {
      "report_code": "G13UZ0",
      "reporter_name": "John Doe",
      "identity_number": "1234567890",
      "address": "Jl. Contoh No. 123",
      "occupation": "Wiraswasta",
      "phone": "081234567890",
      "email": "john@example.com",
      "report_title": "Laporan Pelanggaran",
      "report_description": "Saya melihat pelanggaran terjadi di...",
      "attachment": null,
      "identity_card_attachment": null,
      "violation_id": 1,
      "violation_name": "Pelanggaran terhadap peraturan",
      "status": "Initiation",
      "history": [
        {
          "status": "Initiation",
          "answer": null,
          "answer_attachment": null,
          "created_at": "2023-01-01T00:00:00.000000Z"
        }
      ],
      "created_at": "2023-01-01T00:00:00.000000Z"
    }
  }
  ```

## Format Respons Umum

### Respons Sukses
```json
{
  "success": true,
  "message": "Deskripsi pesan opsional",
  "data": {}
}
```

### Respons Gagal
```json
{
  "success": false,
  "message": "Deskripsi pesan error",
  "errors": {}
}
```

## Catatan Penting

1. **Endpoint Tanpa Autentikasi:** Semua endpoint di dokumentasi ini tidak memerlukan autentikasi
2. **Kode Registrasi/Unik:** Setiap pengiriman laporan (question, information request, gratification, WBS) akan menghasilkan kode registrasi/unik acak 6 karakter (huruf kapital dan angka) yang digunakan untuk melacak status permintaan
3. **Alur Timbal Balik:**
   - Pengguna mengirim data melalui endpoint POST
   - Sistem menghasilkan kode registrasi/unik acak 6 karakter (huruf kapital dan angka) tanpa tanda hubung
   - Pengguna dapat melacak status permintaan menggunakan kode tersebut melalui endpoint GET
   - Status permintaan akan diperbarui seiring proses penanganan (melalui sistem ReportProcess)
4. **Rate Limiting:** Beberapa endpoint mungkin memiliki rate limiting untuk mencegah spam
5. **Caching:** Beberapa endpoint mungkin menggunakan caching untuk performa lebih baik

## Analisis & Troubleshooting: Alur Data Gratifikasi

Bagian ini menjelaskan secara teknis bagaimana data Gratifikasi mengalir dari input pengguna hingga tampilan admin, untuk membantu debugging masalah seperti status kosong.

### 1. Struktur Data & Relasi
Laporan Gratifikasi menggunakan sistem **Polymorphic Relationship** untuk mencatat status dan proses penanganan.

- **Tabel Utama:** `gratifications` (Menyimpan data laporan)
- **Tabel Proses:** `report_processes` (Menyimpan riwayat status & jawaban)
- **Tabel Status:** `response_statuses` (Master data status)

**Relasi:**
`Gratification` (model) -> `morphMany` -> `ReportProcess` (model) -> `belongsTo` -> `ResponseStatus` (model)

### 2. Alur Input (Livewire - Public)
Saat pengguna mengirim laporan melalui form publik:
1. Data disimpan ke tabel `gratifications`.
2. Secara otomatis, sistem membuat record pertama di tabel `report_processes`.
3. Record ini memiliki `response_status_id` yang diambil dari Enum `App\Enums\ResponseStatus::Initiation` (Nilai: 1).

### 3. Alur Tampilan Admin (Filament)
Saat admin melihat daftar laporan:
1. Sistem memuat `Gratification`.
2. Kolom Status mengambil data via relasi: `gratification->process->responseStatus->name`.
   - `process` adalah relasi `latestOfMany` ke `report_processes`.
   - `responseStatus` adalah relasi ke tabel `response_statuses`.

### 4. Diagnosis Masalah Status Kosong
Jika kolom Status terlihat kosong di dashboard admin, penyebab teknisnya adalah putusnya rantai relasi data:

**Kondisi:**
- Di tabel `report_processes`, kolom `response_status_id` bernilai `1` (Initiation).
- Di tabel `response_statuses`, **TIDAK ADA** baris dengan `id = 1`.

**Penyebab Umum:**
- `ResponseStatusSeeder` menggunakan auto-increment, bukan ID eksplisit.
- Jika database pernah di-reset atau di-seed ulang tanpa truncate yang bersih, ID di tabel `response_statuses` mungkin dimulai dari angka lain (misal: 5, 6, 7, 8).
- Akibatnya: Code mencari status ID 1, tapi di database adanya ID 5. Relasi return `null`.

**Verifikasi Manual (Tanpa Ubah Code):**
Cek database langsung:
```sql
SELECT * FROM report_processes WHERE reportable_type LIKE '%Gratification%';
-- Perhatikan response_status_id (misal: 1)

SELECT * FROM response_statuses;
-- Cek apakah ada id yang sesuai (misal: 1). Jika isinya mulai dari angka lain, itulah penyebabnya.
```

## Analisis & Troubleshooting: Alur Data WBS

Secara arsitektur, modul WBS (Whistle Blowing System) sangat identik dengan modul Gratifikasi ("kembar"). Analisis untuk masalah status kosong berlaku juga di sini.

### 1. Struktur Data & Relasi
WBS memiliki tambahan relasi ke Jenis Pelanggaran:

- **Tabel Utama:** `wbs`
- **Tabel Proses:** `report_processes` (Polymorphic)
- **Tabel Pelanggaran:** `violations` (Master data jenis pelanggaran)

**Relasi:**
`Wbs` (model) -> `belongsTo` -> `Violation` (model)

### 2. Diagnosis Masalah Jenis Pelanggaran Kosong
Jika kolom "Jenis Pelanggaran" kosong di dashboard admin, penyebabnya mirip dengan masalah status: mismatch ID.

**Kondisi:**
- Di tabel `wbs`, kolom `violation_id` terisi (misal: 1).
- Di tabel `violations`, **TIDAK ADA** baris dengan `id = 1`.

**Penyebab (Seeder Issue):**
- `ViolationSeeder` menggunakan metode insert manual `DB::table('violations')->insert(...)`.
- Metode ini bergantung pada auto-increment database untuk menghasilkan ID.
- Jika database Anda memiliki "sejarah" ID sebelumnya (tidak di-reset bersih), ID pelanggaran mungkin dimulai dari angka lain (misal: 5, 6, 7).

**Solusi & Verifikasi:**
1. Cek isi tabel `violations`:
   ```sql
   SELECT * FROM violations;
   ```
2. Pastikan saat mengirim laporan via API (field `violation_id`), Anda menggunakan ID yang **benar-benar ada** di hasil query di atas.
3. Jangan berasumsi ID selalu dimulai dari 1.