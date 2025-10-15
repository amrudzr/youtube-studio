# YouTube Studio - PHPUnit Tests Project

Repositori ini berisi implementasi dan contoh penggunaan PHPUnit untuk proyek PHP. Proyek ini di-setup tanpa framework (plain PHP) untuk mendemonstrasikan proses testing dari awal secara terstruktur.

[![PHPUnit Test CI](https://github.com/amrudzr/youtube-studio/actions/workflows/phpunit.yml/badge.svg)](https://github.com/amrudzr/youtube-studio/actions/workflows/phpunit.yml)

> **Disclaimer Badge**: Untuk @amrudzr, badge status di atas adalah contoh. Untuk mengaktifkannya, Anda perlu setup GitHub Actions (atau CI/CD lainnya) di repositori ini. Anda dapat meminta pemilik repositori untuk memberikan akses atau mengonfigurasi workflow CI/CD agar badge ini berfungsi dan menampilkan status tes secara dinamis.

---

## 📋 Daftar Isi

- [Persyaratan](#-persyaratan)
- [Langkah Instalasi](#-langkah-instalasi)
- [Menjalankan Tes](#-menjalankan-tes)
  - [Contoh Hasil Sukses](#-contoh-hasil-sukses)
  - [Contoh Hasil Gagal](#-contoh-hasil-gagal)
- [Alur Kerja & Best Practice](#-alur-kerja--best-practice)
  - [Struktur & Penamaan File](#struktur--penamaan-file)
  - [Alur Kerja Git](#alur-kerja-git)
  - [Membuat Branch Baru](#membuat-branch-baru)
  - [Commit & Push Perubahan](#commit--push-perubahan)
  - [Membuat Pull Request (PR)](#membuat-pull-request-pr)

---

## 🔧 Persyaratan

Pastikan lingkungan pengembangan Anda memenuhi persyaratan berikut:
- **PHP 8.1** atau versi yang lebih baru.
- **Composer** 2.x terinstal secara global.

---

## ⚙️ Langkah Instalasi

1.  **Clone Repositori**
    ```bash
    git clone [https://github.com/username/nama-repo.git](https://github.com/username/nama-repo.git)
    cd nama-repo
    ```

2.  **Instal Dependensi**
    Gunakan Composer untuk menginstal semua paket yang dibutuhkan, termasuk PHPUnit.
    ```bash
    composer install
    ```

3.  **Jalankan Tes Awal**
    Untuk memastikan semuanya terkonfigurasi dengan benar, jalankan tes bawaan.
    ```bash
    ./vendor/bin/phpunit
    ```

---

## 🚀 Menjalankan Tes

Semua tes dijalankan menggunakan perintah berikut dari direktori root proyek:

```bash
./vendor/bin/phpunit
````
---

### ✅ Contoh Hasil Sukses

Jika semua tes berhasil, Anda akan melihat output berwarna hijau seperti ini yang menandakan semuanya berjalan sesuai harapan.

```
PHPUnit 12.4.0 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.2.18
Configuration: /path/to/project/phpunit.xml

.                                     1 / 1 (100%)

Time: 00:00.010, Memory: 6.00 MB

OK (1 test, 1 assertion)
```

### ❌ Contoh Hasil Gagal

Jika ada tes yang gagal, output akan berwarna merah dan menampilkan detail error serta lokasi kegagalannya, memudahkan proses debugging.

```
PHPUnit 12.4.0 by Sebastian Bergmann and contributors.

Runtime:       PHP 8.2.18
Configuration: /path/to/project/phpunit.xml

F                                     1 / 1 (100%)

Time: 00:00.015, Memory: 6.00 MB

There was 1 failure:

1) PolosHermanoz\YoutubeStudio\Tests\ExampleTest::test_greet_method_returns_correct_string
Failed asserting that two strings are identical.
--- Expected
+++ Actual
@@ @@
-'Hello from the Example class!'
+'Hello from the wrong class!'

/path/to/project/tests/ExampleTest.php:21

FAILURES!
Tests: 1, Assertions: 1, Failures: 1.
```

-----

## ✨ Alur Kerja & Best Practice

Untuk menjaga kualitas, konsistensi, dan keterbacaan kode, ikuti panduan berikut.

### Struktur & Penamaan File

  - **Folder `src`**: Berisi semua kode sumber utama aplikasi.
      - **Fungsi**: Logika bisnis, class, service, dan semua yang berhubungan dengan fungsionalitas inti aplikasi.
      - **Nama Class**: Gunakan `PascalCase`, contoh: `src/VideoManager.php` berisi `class VideoManager`.
  - **Folder `tests`**: Berisi semua kode untuk testing.
      - **Fungsi**: Menguji setiap bagian dari kode di `src` untuk memastikan kebenarannya.
      - **Nama File Tes**: Nama file harus sama dengan class yang diuji dengan akhiran `Test`. Contoh: `tests/VideoManagerTest.php` untuk menguji `class VideoManager`.
  - **Nama Fungsi/Method**: Gunakan `camelCase`, contoh: `public function calculateDuration()`.

### Alur Kerja Git

Seluruh pengembangan fitur, perbaikan bug, atau pekerjaan lainnya **wajib** dilakukan di dalam *branch* baru, bukan langsung di `main` atau `develop`.

### Membuat Branch Baru

Gunakan format **`tipe/deskripsi-singkat`** untuk penamaan branch. Ini membantu mengidentifikasi tujuan dari setiap branch dengan cepat.

  - **`feature/`**: Untuk pengembangan fitur baru.
      - Contoh: `feature/user-authentication`, `feature/upload-video`
  - **`bugfix/`**: Untuk memperbaiki bug yang ada.
      - Contoh: `bugfix/fix-login-error`, `bugfix/resolve-memory-leak`
  - **`hotfix/`**: Untuk perbaikan kritis yang harus segera dirilis ke produksi.
      - Contoh: `hotfix/security-patch-xss`
  - **`chore/`**: Untuk pekerjaan yang tidak berhubungan langsung dengan kode aplikasi (misalnya: update dependensi, perbaikan CI/CD).
      - Contoh: `chore/update-phpunit-v12`, `chore/add-linter-config`

**Cara membuat branch baru:**

```bash
# Pindah ke branch utama dan pastikan sudah paling update
git checkout main
git pull origin main

# Buat branch baru sesuai format
git checkout -b feature/add-new-endpoint
```

### Commit & Push Perubahan

Gunakan **Conventional Commits** untuk pesan commit. Formatnya adalah **`tipe(scope): deskripsi singkat`**.

  - **`feat`**: Penambahan fitur baru.
  - **`fix`**: Perbaikan bug.
  - **`docs`**: Perubahan pada dokumentasi.
  - **`style`**: Perbaikan format kode (spasi, titik koma, dll).
  - **`refactor`**: Perubahan kode yang tidak menambah fitur atau memperbaiki bug.
  - **`test`**: Penambahan atau perbaikan tes.
  - **`chore`**: Perubahan pada build process atau tools.

**Contoh pesan commit profesional:**

  - `feat(API): add user registration endpoint`
  - `fix(Auth): resolve incorrect password validation logic`
  - `docs(README): update installation and contribution guide`
  - `test(User): add unit test for user model`
  - `refactor(Service): simplify video processing logic`

**Langkah-langkah untuk commit dan push:**

```bash
# 1. Tambahkan file yang ingin di-commit
git add .

# 2. Buat commit dengan pesan yang jelas
git commit -m "feat(Video): add functionality to fetch video details"

# 3. Push ke remote repository di branch Anda
git push origin feature/add-new-endpoint
```

### Membuat Pull Request (PR)

Setelah pekerjaan di branch Anda selesai dan sudah di-*push*, buat *Pull Request* ke branch `main` atau `develop`.

#### **Judul Pull Request**

Gunakan judul yang ringkas dan jelas, idealnya mengikuti format commit utama Anda.

  - **Baik**: `Feat(API): Add User Registration Endpoint`
  - **Kurang Baik**: `update file`, `perbaikan`

#### **Deskripsi Pull Request**

Gunakan template di bawah ini untuk mengisi deskripsi PR agar *reviewer* mudah memahami konteks dan cara memverifikasi perubahan Anda.

-----

### Deskripsi

Jelaskan secara singkat apa tujuan dari Pull Request ini. Misalnya: "PR ini menambahkan endpoint baru pada API untuk registrasi pengguna, lengkap dengan validasi input dan unit test."

-----

### Target & Hasil Perubahan

  * **Target**:
      * [ ] Membuat endpoint `POST /api/register`.
      * [ ] Menambahkan validasi untuk email, username, dan password.
      * [ ] Menyimpan data pengguna baru ke database.
      * [ ] Menambahkan unit test untuk memastikan logika registrasi berjalan benar.
  * **Hasil**:
      * Endpoint baru `POST /api/register` berhasil dibuat dan berfungsi.
      * Validasi input telah diimplementasikan.
      * Unit test untuk skenario sukses dan gagal telah ditambahkan dengan coverage 95%.

-----

### Keperluan & Persiapan

Sebelum me-review atau menjalankan perubahan ini, pastikan untuk:

1.  Menjalankan migrasi database baru: `php artisan migrate` (jika menggunakan framework).
2.  Menginstal dependensi baru jika ada: `composer install`.
3.  Menambahkan variabel environment baru di file `.env`: `API_KEY_EXAMPLE=...`

-----

### Cara Menjalankan & Verifikasi

1.  Checkout ke branch ini: `git checkout feature/user-registration`.
2.  Jalankan tes untuk memastikan tidak ada yang rusak: `./vendor/bin/phpunit`.
3.  (Jika ada) Jalankan aplikasi secara lokal.
4.  Gunakan Postman atau cURL untuk mengirim request `POST` ke `http://localhost:8000/api/register` dengan body JSON berikut:
    ```json
    {
      "username": "testuser",
      "email": "test@example.com",
      "password": "password123"
    }
    ```
5.  Pastikan response yang diterima adalah status `201 Created` dengan data user yang baru.

-----

```
```