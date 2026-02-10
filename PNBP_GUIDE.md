# Panduan Modul Diklat PNBP & Lokal (3-in-1)

Dokumen ini menjelaskan implementasi modul **Diklat PNBP** dan **Diklat 3-in-1 (Lokal)** yang baru ditambahkan ke dalam aplikasi. Modul ini memungkinkan pendaftaran peserta umum, manajemen diklat oleh admin, dan proses pembayaran/verifikasi PNBP secara manual.

## Fitur Utama

1.  **Pemisahan Jenis Diklat**:
    *   **Diklat 3-in-1**: Gratis, bersubsidi, fokus pada penempatan kerja.
    *   **Diklat PNBP**: Berbayar, terbuka untuk umum.
2.  **Autentikasi Peserta**:
    *   Login & Register terpisah untuk peserta umum (menggunakan tabel `participants`).
    *   Data profil lengkap sesuai KTP (NIK, Pekerjaan, dll).
3.  **Manajemen Diklat (Admin)**:
    *   CRUD Data Diklat (Judul, Deskripsi, Harga, Kuota, Tanggal).
    *   Memantau pendaftaran masuk.
4.  **Alur Pendaftaran & Pembayaran**:
    *   Peserta mendaftar diklat.
    *   Admin membuat tagihan (Invoice) & Kode Billing.
    *   Peserta upload bukti bayar.
    *   Admin verifikasi pembayaran.

## Cara Menjalankan (Setup)

Untuk menjalankan modul ini di local development, ikuti langkah berikut:

### 1. Migrasi Database
Jalankan migrasi untuk membuat tabel-tabel baru (`participants`, `trainings`, `bookings`, `invoices`, dll) dan update struktur tabel lama.

```bash
php artisan migrate
```

### 2. Akses Aplikasi

*   **Halaman Depan**:
    *   Menu **Program Kerja > Diklat 3-in-1**: `/training` (Hanya info umum 3-in-1).
    *   Menu **Program Kerja > Diklat PNBP**: `/pnbp` (Daftar diklat berbayar).
        > **Catatan**: Jika menu "Diklat PNBP" belum muncul, silakan tambahkan secara manual melalui database (tabel `navigations`) atau panel admin dengan path `/pnbp`.
*   **Pendaftaran Peserta**:
    *   Register: `/participant/register`
    *   Login: `/participant/login`
    *   Dashboard: `/participant/dashboard` (Riwayat & Pembayaran).
*   **Admin Panel** (`/admin`):
    *   **Manajemen Diklat**: Menu untuk tambah/edit data diklat.
    *   **Pendaftaran Peserta**: Menu untuk kelola peserta & tagihan.

## Alur Pengujian (Testing Flow)

1.  **Daftar Akun Peserta**:
    *   Buka `/participant/register`.
    *   Isi data diri lengkap (NIK, Nama, Email, dll).
2.  **Daftar Diklat PNBP**:
    *   Buka `/pnbp` -> Pilih diklat -> Klik "Lihat Detail".
    *   Klik "Daftar Sekarang".
    *   Anda akan diarahkan ke Dashboard. Status: **Pending**.
3.  **Proses Admin (Billing)**:
    *   Login ke Panel Admin (`/admin`).
    *   Buka menu **Pendaftaran Peserta** (`Bookings`).
    *   Edit pendaftaran tersebut -> Scroll ke bagian **Invoices**.
    *   Klik **New Invoice**:
        *   Isi **Kode Billing** (misal: `82773123`).
        *   Isi **Jumlah** (sesuai harga diklat).
        *   Set Status: **Unpaid**.
        *   (Opsional) Upload File Tagihan PDF.
        *   Klik **Create**.
4.  **Pembayaran Peserta**:
    *   Kembali ke Dashboard Peserta (`/participant/dashboard`).
    *   Akan muncul tagihan "Unpaid" dengan tombol **Upload Bukti**.
    *   Upload file gambar/PDF bukti transfer.
5.  **Verifikasi Admin**:
    *   Di Admin Panel, refresh halaman edit Booking/Invoice.
    *   Di tabel Invoices, klik **Lihat Bukti** pada kolom Bukti Bayar.
    *   Jika valid, klik action **Verifikasi Lunas** (icon centang hijau).
    *   Status invoice berubah jadi **Paid** dan pendaftaran otomatis **Approved**.

## Struktur Kode Penting

*   **Models**: `Participant`, `Training`, `Booking`, `Invoice`, `Occupation`.
*   **Livewire (Frontend)**:
    *   `App\Livewire\Pnbp\Index` & `Detail`: Halaman publik PNBP.
    *   `App\Livewire\Participant\Dashboard`: Dashboard peserta.
    *   `App\Livewire\Auth\*`: Login/Register peserta.
*   **Filament (Backend)**:
    *   `App\Filament\Resources\TrainingResource`: CRUD Diklat.
    *   `App\Filament\Resources\BookingResource`: Manajemen Pendaftaran & Invoice.
