Ini adalah **Dokumentasi Resmi Sistem Penilaian Taktis (Tactical Assessment System)** untuk **BlackFile**.

Sebagai developer dan "Kepala Agensi", kamu perlu standar baku agar database entitas kamu konsisten. Sistem ini memadukan logika _game RPG_ dengan _lore intelligence agency_ (seperti SCP Foundation atau SHIELD).

Berikut adalah rincian strukturnya:

---

### 1. POWER TIER (Hierarki Eksistensi)

Ini adalah **patokan mutlak**. Sistem BlackFile menggunakan **Inverse Scale** (Angka makin kecil = Makin kuat).
_Logika Code:_ Jika selisih Tier $\ge$ 2, entitas dengan Tier lebih kecil menang otomatis (_Absolute Stomp_), tidak peduli stats-nya.

| Tier  | Klasifikasi            | Deskripsi Kekuatan                                                   | Contoh Entitas                               |
| :---- | :--------------------- | :------------------------------------------------------------------- | :------------------------------------------- |
| **0** | **BOUNDLESS**          | Omnipotent. Pencipta, Tak Terbatas, di luar logika sistem.           | _The Creator (God)_                          |
| **1** | **TRANS-DIMENSIONAL**  | Mampu memanipulasi realitas, waktu, dan dimensi. Multiverse level.   | _Michael (Full Power), Lucifer (Full Power)_ |
| **2** | **PLANETARY / COSMIC** | Mampu menghancurkan planet atau memanipulasi hukum alam skala besar. | _Leviathan, Behemoth, Asmodeus_              |
| **3** | **CONTINENTAL**        | Ancaman level benua. Serangan bisa mengubah peta dunia.              | _Kaiju (Godzilla), Ancient Dragons_          |
| **4** | **CATASTROPHE**        | Ancaman level negara/kota besar.                                     | _Nuklir, High-Tier SCPs_                     |
| **5** | **DESTRUCTIVE**        | Ancaman level gedung/blok kota. Superhuman kuat.                     | _Hulk (Base), Iron Man_                      |
| **6** | **SUPERHUMAN**         | Di atas kemampuan manusia normal (fisik/sihir).                      | _Captain America, Vampire_                   |
| **7** | **PEAK HUMAN**         | Batas maksimal potensi manusia (Atlet Olimpiade/Pasukan Khusus).     | _John Wick, Batman_                          |
| **8** | **HUMAN**              | Manusia biasa atau makhluk setara.                                   | _Warga Sipil, Prajurit Biasa_                |
| **9** | **SUB-HUMAN**          | Lemah, hewan kecil, atau entitas non-kombatan.                       | _Kucing, Goblin Lemah_                       |

---

### 2. COMBAT STATS (Radar Chart Parameters)

Ini adalah variabel dinamis untuk simulasi pertarungan jika **Tier-nya setara** atau selisihnya dikit (1 poin). Nilai berkisar **0 - 100+**.

1.  **STR (Strength - Kekuatan Fisik)**
    -   Daya angkat, daya pukul, dan kerusakan fisik murni.
    -   _Contoh:_ Superman (100), Wizard (10).
2.  **SPD (Speed - Kecepatan)**
    -   Kecepatan gerak, terbang, dan refleks/reaksi.
    -   _Contoh:_ Flash (100), Golem Batu (5).
3.  **DUR (Durability - Ketahanan)**
    -   Seberapa keras kulit/armor, kemampuan regenerasi, dan stamina menerima _damage_.
    -   _Contoh:_ SCP-682 (100 - Immortal), Manusia (20).
4.  **INT (Intelligence - Kecerdasan)**
    -   IQ, kemampuan strategi, taktik tempur, dan pengetahuan.
    -   _Contoh:_ Batman (100), Binatang Buas (10).
5.  **NRG (Energy - Energi/Sihir)**
    -   Kapasitas Mana, Ki, Chakra, atau energi kosmik. Juga mencakup _Ranged Attack_.
    -   _Contoh:_ Dr. Strange (100), Petinju (0).
6.  **CBT (Combat Skill - Keahlian Bertarung)**
    -   Teknik bela diri, penggunaan senjata, dan pengalaman tempur. Beda dengan STR (Kekuatan kasar).
    -   _Contoh:_ John Wick (100 - Skill tinggi meski STR manusia), Monster Raksasa (10 - Cuma ngamuk).

---

### 3. COMBAT CLASSIFICATION (Tipe Entitas)

Klasifikasi ini menentukan **Algoritma AI** dalam menceritakan pertarungan dan **Bonus Arena**.

-   **ASSAULT:** Fokus pada fisik (STR/SPD/CBT). Suka pertarungan jarak dekat.
    -   _Contoh:_ Hulk, Kratos, Werewolf.
-   **MYSTIC:** Fokus pada sihir/energi (NRG/INT). Lemah di fisik tapi _damage_ area luas.
    -   _Contoh:_ Witches, Sorcerers, Djinn.
-   **HAZARD:** Entitas yang berbahaya hanya dengan _berada di dekatnya_ (Racun, Radiasi, Memetic). Seringkali stat fisiknya aneh.
    -   _Contoh:_ SCP-173, Virus Sentient, Blob.
-   **DIVINE:** Entitas suci/terkutuk yang memiliki otoritas hierarki (Malaikat/Iblis). Punya _Lore Override_ tinggi.
    -   _Contoh:_ Michael, Gabriel, Lucifer, Satan.
-   **COSMIC:** Entitas luar angkasa atau alien. Kebal terhadap lingkungan vakum (VOID).
    -   _Contoh:_ Cthulhu, Aliens, Star Eater.
-   **TACTICAL:** Mengandalkan alat, teknologi, dan otak.
    -   _Contoh:_ Cyborg, Iron Man, Special Forces.

---

### 4. TACTICAL ASSESSMENT FORMULA (Cara BlackFile Menghitung)

Saat kamu menekan tombol "Simulate", sistem melakukan 3 tahap pengecekan:

#### **Tahap 1: The Hierarchy Check (Cek Tier)**

Sistem bertanya: _"Apakah beda Tier $\ge$ 2?"_

-   **YA:** Pertarungan batal. Tier tinggi menang mutlak (**Absolute Stomp**).
    -   _Logika:_ Semut (Tier 9) punya Skill 100 pun tidak bisa membunuh Gajah (Tier 4).
-   **TIDAK:** Lanjut ke Tahap 2.

#### **Tahap 2: Lore & Script Check (Cek Cerita)**

Sistem bertanya: _"Apakah ada sejarah khusus antara A dan B?"_

-   _Cek:_ Michael vs Satan -> Trigger `REVELATION_12:7`.
-   _Cek:_ SCP-173 vs SCP-096 -> Trigger `Infinite Stalemate`.
-   _Cek:_ Air vs Api (Arena Factor).

#### **Tahap 3: The Simulation (Adu Stats)**

Jika Tier setara dan tidak ada Lore khusus, sistem mengadu total poin Stats + RNG (Faktor Keberuntungan/Dadu).

Rumus Sederhana (dari Trait kamu):
$$Score = (STR \times 1.0) + (SPD \times 1.2) + (INT \times 1.5) + (CBT \times 1.3) + (NRG \times 1.1) + (DUR \times 1.0)$$

_Kenapa dikali?_

-   **INT & CBT** punya pengali (multiplier) paling besar (1.5 dan 1.3).
-   _Filosofi BlackFile:_ Di level setara, **Kecerdasan dan Skill** lebih menentukan kemenangan daripada sekadar otot (STR) atau kulit keras (DUR).

---

### Contoh Kartu Data Entitas (Untuk Input di Database)

**Designation:** ARCHANGEL MICHAEL

-   **Power Tier:** 1 (Trans-Dimensional)
-   **Classification:** DIVINE / COMMANDER
-   **Origin:** Heaven (Kingdom of Light)
-   **Stats:**
    -   STR: 95 (Fisik manifestasi sempurna)
    -   SPD: 100 (Omnipresent/Teleportasi)
    -   DUR: 99 (Hampir tak bisa dilukai materi)
    -   INT: 100 (Pengetahuan Ilahi)
    -   NRG: 100 (Cahaya Suci Tak Terbatas)
    -   CBT: 100 (Panglima Perang Surga)

**Designation:** LEVIATHAN

-   **Power Tier:** 2 (Planetary)
-   **Classification:** MYSTIC / BEAST
-   **Origin:** Abyssal Ocean
-   **Stats:**
    -   STR: 98 (Sangat kuat fisik)
    -   SPD: 60 (Lambat di darat, 100 di air)
    -   DUR: 95 (Kulit bersisik tebal)
    -   INT: 70 (Cerdas tapi insting binatang)
    -   NRG: 85 (Sihir air/es)
    -   CBT: 50 (Mengandalkan kekuatan kasar)

Dengan panduan ini, data di _BlackFile_ akan terlihat sangat profesional dan konsisten! Gimana, masuk akal strukturnya?
