# Panduan Deploy Aset CSS/JS ke InfinityFree (blackfile.xo.je)

Dokumen ini menjelaskan cara **update aset build (CSS/JS) ke hosting InfinityFree tanpa terminal SSH**, cukup dengan **build di PC lokal** lalu **upload 1 folder `build` via FileZilla**.

---

## 1. Konsep Kuncinya (Baca Dulu)

Vite menghasilkan nama file ber-hash, contoh:

```
public/build/assets/app-DkmcnJVT.css
public/build/assets/app-DU_SfR_c.js
```

Kalau ada nama hash itu yang ditulis manual di Blade, setiap `npm run build` nama hash-nya berubah → harus ganti di Blade. **That's the pain you're feeling.**

**Tapi project ini sudah pakai `@vite`, yang artinya Blade TIDAK PERNAH menyebut nama file hasil build.**

Di `resources/views/components/layout.blade.php:14`:

```blade
@vite(['resources/css/app.css', 'resources/js/app.js'])
@vite('resources/js/pages/friends-index.js')
```

Blade cuma menyebut **file sumber** (`resources/css/app.css`), bukan file hasil build.

Yang terjadi saat halaman dirender:

```
Blade  →  @vite  →  baca public/build/manifest.json  →  ketemu "app.css" dibangun jadi "assets/app-DkmcnJVT.css"
                                                        →  print <link href="/build/assets/app-DkmcnJVT.css">
```

`manifest.json` adalah "kamus" yang **dibuat otomatis oleh Vite**. Jadi selama kamu upload folder `build` (**termasuk `manifest.json` di dalamnya**), semua Blade otomatis ikut berubah sendiri. **Tidak ada satu pun file Blade/JS yang perlu kamu sentuh.**

> **Kesimpulan: strategi yang kamu mau ("hapus folder build di server, upload ulang 1 folder, Blade ngikutin sendiri") itu SUDAH otomatis bekerja di project ini.** Yang perlu diperbaiki cuma Cara kamu upload-nya.

---

## 2. Apa Saja yang Perlu Diupload

Folder `public/build` hasil `npm run build` lokal berisi:

```
public/build/
├── manifest.json          ← WAJIB! ini "kamus" yang dibaca @vite
└── assets/
    ├── app-DkmcnJVT.css
    ├── app-DU_SfR_c.js
    ├── friends-index-CceEGRlH.js
    ├── graph-controls-l0sNRNKZ.js
    ├── fa-solid-900-8GirhLYJ.woff2
    ├── fa-regular-400-BVHPE7da.woff2
    ├── fa-brands-400-BfBXV7Mm.woff2
    └── fa-v4compatibility-DnhYSyY-.woff2
```

**WAJIB upload `manifest.json`.** Kalau cuma upload folder `assets/` tanpa `manifest.json`, situs akan error `Vite manifest not found at: .../public/build/manifest.json`.

---

## 3. Workflow Rutin (Lokal → Upload)

### Step 1 — Build di PC lokal

Di PC lokal (punya Node.js & terminal, Laragon juga cukup):

```bash
npm install
npm run build
```

Laravel otomatis menimpa `public/build` dengan hasil baru.

### Step 2 — (Opsional tapi disarankan) Bersihkan sisa file lama

Kalau nama hash berubah, file lama menumpuk di `public/build/assets/`.
Karena `.gitignore` sudah mengabaikan `/public/build`, aman dihapus:

```bash
# di PC lokal (Windows CMD)
rmdir /S /Q public\build
npm run build
```

> Di PC lokal tidak wajib, tapi bersih-bersih biar folder upload tidak memblenderak file lama yang tidak kepakai.

### Step 3 — Login FileZilla ke InfinityFree

| Field       | Isi                                                       |
| ----------- | --------------------------------------------------------- |
| Host        | `ftpd.infinityfree.com`                                    |
| Protocol    | FTP (atau FTPS Explicit Encryption, kalau mau aman)     |
| Username    | akun FTP dari control panel InfinityFree                  |
| Password    | password FTP dari control panel InfinityFree               |
| Port        | `21` (FTP) atau `990` (FTPS)                              |

### Step 4 — Hapus folder `build` lama di server

Di FileZilla, navigasi ke folder document root hosting kamu — folder yang berisi `index.php`, `app-icon.png`, `.htaccess` (folder public Laravel).

```
htdocs/
├── index.php
├── .htaccess
├── app-icon.png
├── avatars/
├── build/          ← HAPUS SELURUH FOLDER INI
└── ...
```

Klik kanan `build` → **Delete**. (Atau klik `build` lalu tekan `Shift+Delete` untuk hapus permanen tanpa masuk Recycle Bin.)

> Catatan: hapus folder `build` **hanya** ya. Jangan sentuh folder lain.

### Step 5 — Upload 1 folder `build` baru

Drag folder `public\build` dari PC lokal ke folder document root hosting di FileZilla.

FileZilla otomatis membuat folder `build` baru beserta `manifest.json` dan `assets/`.

### Step 6 — Hard refresh browser

```
Ctrl + F5   (Windows)
Cmd + Shift + R  (Mac)
```

**Selesai. Tidak ada file Blade/JS yang perlu diedit. Tidak ada nama asset yang perlu diganti.**

---

## 4. Kenapa Blade Tidak Perlu Disentuh? (Teknis)

Blade dikompilasi oleh Laravel ke PHP di `storage/framework/views/`.

Directive `@vite` dikompilasi menjadi:

```php
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
```

Perhatikan: **nama file hasil build TIDAK ikut ter-hardcode** di hasil kompilasi. `Vite` membaca `manifest.json` **pada saat request berjalan (runtime)**.

Konsekuensinya:

- Ganti `manifest.json` di server → semua halaman otomatis pakai asset baru.
- Kamu **tidak perlu** hapus cache view (`php artisan view:clear`) untuk perubahan asset.
- Ini justru alasan `@vite` Aman di shared hosting tanpa SSH.

---

## 5. Yang JANGAN Diupload

| Item                     | Alasan                                                                 |
| ------------------------ | ---------------------------------------------------------------------- |
| `public/hot`             | Kalau file ini ada, `@vite` mengira dev server aktif dan assets-nya gagal dimuat. |
| `node_modules/`          | Jauh terlalu besar, tidak perlu di server.                             |
| `resources/js`, `resources/css` (file sumber) | Tidak dibaca browser, hanya dipakai Vite saat build. |
| `.env` lokal             | Contains kredensial lokal, tidak boleh bocor.                          |

`.gitignore` sudah meng-cover `public/hot` dan `/public/build` otomatis.

---

## 6. Checklist Sebelum Upload

- [ ] `npm run build` di PC lokal sudah dijalankan, tidak error
- [ ] File `public/build/manifest.json` ada
- [ ] Folder `public/build/assets/` berisi file CSS + JS baru
- [ ] Tidak ada file `public/hot`
- [ ] Di FileZilla, folder `build` lama di server sudah dihapus
- [ ] Upload selesai, cek di FileZilla tidak ada file berstatus gagal (merah)
- [ ] Hard refresh browser

---

## 7. Troubleshooting

### Error: `Vite manifest not found at: .../public/build/manifest.json`

Penyebab: `manifest.json` tidak ikut terupload, atau ada di lokasi yang salah.

Perbaikan:
1. Cek di FileZilla apakah `build/manifest.json` ada (bukan `build/assets/manifest.json`).
2. Case-sensitive: harus lowercase `manifest.json`.

### Tampilan masih CSS/JS versi lama (style lama)

1. Hard refresh (`Ctrl+F5`) — kadang browser/CDN InfinityFree masih cache.
2. Buka `https://blackfile.xo.je/build/manifest.json` di browser. Kalau muncul JSON, berarti upload sudah benar dan masalahnya murni cache browser.
3. Buka `https://blackfile.xo.je/build/assets/` — kalau folder assets tidak bisa diakses, berarti masih salah lokasi.

### Manifest sudah benar, tapi halaman tetap 404 assetnya

Buka `manifest.json`, cari entry `resources/css/app.css`, lihat nilai `file`-nya.
Kalau file di `file` **tidak ada** di `assets/`, berarti upload tidak lengkap → ulangi Step 5.

### Still hitung "kode" jelek? Tak perlu ubah asset lagi

Kalau `@vite` error karena `manifest.json` bermasalah (mis. upload sebagian), dan kamu mau **fallback yang 100% stabil tanpa Manifest**, lihat **Alternatif (Opsi B)** di bawah.

---

## 8. Daftar Entry Point Vite

Kalau nanti kamu tambah file JS baru dan mau dimuat terpisah, daftarkan dulu di `vite.config.js:19`:

```js
laravel({
  input: [
    'resources/css/app.css',
    'resources/js/app.js',
    'resources/js/graph-controls.js',
    'resources/js/pages/friends-index.js',
    // tambah file baru di sini
  ],
  refresh: true,
}),
```

Lalu panggil di Blade:

```blade
@vite('resources/js/file-baru.js')
```

**Important:** file yang di-`import` dari `resources/js/app.js` (seperti `battle-logic.js`, `archive-thumb.js`, `prototypes-crud.js`, `forms/archive-form.js`) **sudah otomatis ikut ter-bundle** ke `app.js` — tidak perlu didaftarkan di `input` dan tidak perlu `@vite` terpisah.

---

## 9. Alternatif (Opsi B) — Nama File Aset Selalu Tetap / Tanpa Hash

Kalau karena satu alasan tertentu kamu **tidak ingin** nama file berubah sama sekali (mis. ada CDN/proxy yang bermasalah dengan nama ber-hash), kamu bisa memaksa Vite menghasilkan nama file statis.

### Edit `vite.config.js`

```js
export default defineConfig({
  plugins: [
    tailwindcss({ /* ... */ }),
    laravel({
      input: [ /* ... input kamu ... */ ],
      refresh: true,
    }),
  ],
  build: {
    rollupOptions: {
      output: {
        entryFileNames: 'assets/[name].js',
        chunkFileNames: 'assets/[name].js',
        assetFileNames: 'assets/[name][extname]',
      },
    },
  },
});
```

Hasilnya:
```
public/build/assets/app.css
public/build/assets/app.js
public/build/assets/friends-index.js
public/build/assets/fa-solid-900.woff2
```

Blade **tidak perlu diubah** — `@vite` tetap bekerja karena `manifest.json` tetap dihasilkan.

### Cache busting manual (WAJIB, karena nama file tidak berubah)

Karena nama file statis, browser akan cache asset lama. Solusinya, di `resources/views/components/layout.blade.php`, ganti pemanggilan `@vite` menjadi:

```blade
<link rel="stylesheet"
      href="{{ asset('build/assets/app.css') }}?v={{ @filemtime(public_path('build/assets/app.css')) }}">

<script src="{{ asset('build/assets/app.js') }}?v={{ @filemtime(public_path('build/assets/app.js')) }}"></script>
```

`filemtime()` mengembalikan waktu file terakhir diubah, jadi otomatis berubah setiap kali kamu upload build baru → browser otomatis menarik versi terbaru.

**Trade-off Opsi B:**
- ✅ Nama file tidak pernah berubah
- ❌ `manifest.json` tetap dibutuhkan (kalau tetap pakai `@vite`)
- ❌ Cache busting bergantung pada `filemtime()`, bisa bermasalah kalau FTP/Meja server merusak timestamp

> **Rekomendasi: tetap pakai Opsi A (`@vite` + hash).** Cache busting otomatis, gratis, dan sudah berjalan di project kamu sekarang.

---

## 10. Ringkasan Singkat

```
LOKAL (PC)
   npm run build        →  public/build/ refreshed

SERVER (FileZilla)
   htdocs/build/        →  hapus
   htdocs/build/        →  upload ulang 1 folder

BLADE / JS / PHP
   TIDAK PERLU DISENTUH — @vite baca manifest.json otomatis

BROWSER
   Ctrl+F5
```

---

## 11. Catatan Tambahan Khusus Shared Hosting InfinityFree

Karena kamu **tidak punya terminal SSH**:

1. **Tidak bisa `php artisan ...`** (tidak ada `optimize:clear`, `view:clear`, `storage:link`, `migrate`).
2. Kalau pernah mengubah file **Blade/PHP**, kamu perlu hapus manual file cache lewat FileZilla:
   ```
   htdocs/storage/framework/views/*.php     → hapus isinya
   htdocs/storage/framework/cache/data/*    → hapus isinya
   htdocs/bootstrap/cache/*.php             → hapus config.php, routes-*.php, services.php
   ```
   (Untuk perubahan **asset saja**, ini **tidak perlu**.)
3. Kalau `.env` diubah, hapus `bootstrap/cache/config.php` (kalau ada) supaya `.env` terbaca ulang.
4. Pastikan permission folder `storage/` dan `bootstrap/cache/` adalah **755** (folder) dan **644** (file).
5. InfinityFree punya limitasi resource (CPU/memori). Asset kamu sekarang:

```
app-DkmcnJVT.css   229.73 kB  (gzip:  44.35 kB)
app-DU_SfR_c.js    535.66 kB  (gzip: 176.30 kB)
friends-index.js     3.08 kB
Total ~772 kB (gzip ~223 kB) — masih aman.
```

   Kalau `\"Some chunks are larger than 500 kB\"` muncul saat build, itu **warning saja**, bukan error, dan tidak apa-apa untuk hosting free.

---

*Document ini berlaku untuk project BlackFile (Laravel 10 + Vite 5 + Tailwind CSS v4) — domain: `blackfile.xo.je` (InfinityFree).*