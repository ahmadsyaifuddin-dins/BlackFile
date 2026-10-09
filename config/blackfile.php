<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Asset Categories
    |--------------------------------------------------------------------------
    */
    'asset_categories' => [
        // Lingkaran Pribadi
        'Sahabat',
        'Teman',
        'Keluarga',

        // Lingkaran Akademis
        'Teman Kelas',
        'Rekan Kelompok',
        'Senior / Junior',

        // Lingkaran Profesional
        'Rekan Kerja',
        'Kontak Profesional',

        // Lingkaran Operasional
        'Informan',
        'Aset',

        // Lain-lain
        'Lainnya',
    ],

    /*
    |--------------------------------------------------------------------------
    | Project Types
    |--------------------------------------------------------------------------
    */
    'project_types' => [
        'Web',
        'Web Application',
        'Mobile Application',
        'Desktop Application',
        'IoT Application',
        'CLI Tool',
        'DevOps Tool',
        'Blockchain',
        'Library / API',
        'Framework',
        'BOT',
        'Operating System',
        'UI/UX Design',
        'Lainnya',
    ],

    /*
    |--------------------------------------------------------------------------
    | Entity Classifications
    |--------------------------------------------------------------------------
    |
    | Defines the standardized lists for entity attributes. Grouped logically
    | to ensure clarity and proper placement of terms.
    |
    */

    'entity_categories' => [
        // --- Biological & Cryptids ---
        'Humanoid',
        'Cryptid',
        'Mutant',
        'Unidentified Biological Entity (UBE)',
        'Geological Anomaly', // Natural but anomalous

        // --- Extraterrestrial & Dimensional ---
        'Extraterrestrial',
        'Extradimensional',

        // --- Mythological & Supernatural ---
        'Mythological Being',
        'Folklore Creature',
        'Angelic Hierarchy',
        'Demonic Hierarchy',
        'Metaphysical Concept', // Abstract, non-physical beings/ideas

        // --- Faction & Project Specific ---
        '[B.F] Anomaly', // Anomalies specific to BlackFile's findings
        'SCP Anomaly',
        'Cthulhu Mythos',
        'Hindu Mythology',

        // --- Technological ---
        'Artificial Intelligence',
    ],

    'entity_ranks' => [
        // --- General Threat Levels (BlackFile Standard) ---
        'Benign',
        'Unclassified',
        'Class-C (Low Threat)',
        'Class-B (Moderate Threat)',
        'Class-A (High Threat)',
        'Omega (Global Threat)',
        'Cosmic (Existential Threat)',

        // --- SCP Foundation Object Classes (Ordered by difficulty/danger) ---
        'Neutralized', // Represents a former threat, now inert.
        'Safe',
        'Euclid',
        'Keter',
        'Thaumiel', // Special class, high power but controlled.
        'Apollyon', // Uncontainable, world-ending threat.

        // --- Mythological & Divine Tiers (Ordered by power scale) ---
        'Legendary Hero',
        'Demigod',
        'God / Deity',
        'Titan',
        'Primordial',

        // --- Angelic & Demonic Tiers (Ordered by hierarchy) ---
        'Pit Fiend',
        'Archdevil',
        'Principalities',
        'Nine Lords (Demon)',
        'Lowest Sphere Guardian',
        'Cosmic Guardian',
        'Seraphim Guard',
        'Territorial Commander',
        'Archangel',
        'Seventh Choir / Third Sphere',
        'Sixth Choir / Second Sphere',
        'Fifth Choir / Second Sphere ',
        'Fourth Choir / Second Sphere',
        'Third Choir / First Sphere',
        'Second Choir / First Sphere',
        'Highest Choir / First Sphere',
        'Chancellor of Heaven',

        // --- Cthulhu Mythos Tiers (Ordered by cosmic scale) ---
        'Servitor Race',
        'Elder Thing',
        'Great Old One',
        'Outer God',
    ],

    'entity_origins' => [
        // --- Terrestrial & General ---
        'Unknown',
        'Earth',
        'Remote/Unexplored Region',
        'The Abyss (Oceanic)',
        'Subterranean',
        'Antarctica',
        'Extraterrestrial',
        'Cosmic',
        'Heaven',

        // --- Faction & Agency Specific ---
        '[B.F] Laboratory Creation',
        '[REDACTED]', // For classified origins
        'Site-19 (SCP)',
        'Area 51 (GOI)', // Government of Interest

        // --- Mythological & Folklore Realms ---
        'Norse Mythology (Asgard/Yggdrasil)',
        'Greek Mythology (Olympus/Underworld)',
        'Egyptian Mythology (Duat)',
        'Japanese Folklore (Yomi)',
        'Abrahamic Lore',
        'Celtic Otherworld',

        // --- Cosmic & Extradimensional ---
        'Alternate Dimension',
        'The Void',
        'Zeta Reticuli Star System',
        'Andromeda Galaxy',
        'The Dreamlands', // Specific to Cthulhu Mythos
        'Beyond the Wall of Sleep', // Specific to Cthulhu Mythos
        'Cthulhu Mythos', // General term for this origin
    ],

    /*
    |--------------------------------------------------------------------------
    | Entity Statuses
    |--------------------------------------------------------------------------
    |
    | Daftar ini digunakan untuk mengisi dropdown status di fitur Entities.
    |
    */
    'entity_statuses' => [
        'UNKNOWN' => 'UNKNOWN',
        'ACTIVE' => 'ACTIVE',
        'CONTAINED' => 'CONTAINED', // Tertahan
        'STANDBY' => 'STANDBY',
        'UNCONTAINED' => 'UNCONTAINED', // Masih Buron / Aktif Secara Musiman
        'NEUTRALIZED' => 'NEUTRALIZED',
        'MIA' => 'MISSING IN ACTION',
        'TERMINATED' => 'TERMINATED',
        'FAILING' => 'FAILING', // Rantai Penahanan Sedang Putus
        'SEALED' => 'SEALED', // (Akses dilarang keras / Disegel dengan beton)
        'DORMANT' => 'DORMANT', // '(Tertidur Abadi)'
    ],

    /*
    |--------------------------------------------------------------------------
    | Archive Categories
    |--------------------------------------------------------------------------
    |
    | Daftar ini digunakan untuk mengisi dropdown kategori di fitur Archives.
    | Pastikan 'Other' selalu ada jika Anda ingin menggunakan fungsionalitas
    | input kustom.
    |
    */
    'archive_categories' => [
        'Link Download Film',
        'Link Game',
        'Video Langka',
        'Video Eksklusif',
        'Video Rahasia',
        'Dokumen Rahasia',
        'Gambar Rahasia',
        'Top Secret',
        'Super Secret',
        'Dokumen PDF',
        'Dokumen Excel',
        'Dokumen CSV',
        'Dokumen Word',
        'Other',
    ],

    /*
    |--------------------------------------------------------------------------
    | Public Archive Sharing
    |--------------------------------------------------------------------------
    |
    | Pengaturan untuk link publik arsip ("/s/{token}").
    |
    | 'gate_seconds' => durasi (detik) pengunjung menunggu di halaman gerbang
    |                    sebelum dialihkan ke data tujuan saat trik iklan
    |                    (has_ad) aktif. Tempat menyisipkan kode iklan
    |                    disiapkan di view `archives.public-gate`.
    */
    'public_share' => [
        'gate_seconds' => 15,
    ],

    /*
    |--------------------------------------------------------------------------
    | Google AdSense (Monetisasi Link Publik)
    |--------------------------------------------------------------------------
    |
    | Settings untuk unit iklan yang ditampilkan di halaman publik arsip
    | ("/s/{token}" dan "/s/{token}/open").
    |
    | 'enabled'  => matikan/nyalakan iklan secara global.
    | 'client'   => CA Publisher ID (ca-pub-XXXX).
    | 'slot'     => Slot ID ad unit "Responsive" dari dashboard AdSense.
    | 'debug'    => cetak log di console browser (devtools) setiap kali unit
    |               iklan selesai diproses, apakah berhasil termuat/tidak.
    |               Berguna mencari tahu kenapa iklan tidak muncul.
    |
    | Catatan: iklan sungguhan hanya tampil di domain yang sudah diverifikasi
    | (mis. https://blackfile.xo.je). Di localhost area iklan tetap kosong.
    |
    | Referensi unit:
    |   <ins class="adsbygoogle"
    |        style="display:block"
    |        data-ad-client="ca-pub-XXXX"
    |        data-ad-slot="0000000000"
    |        data-ad-format="auto"
    |        data-full-width-responsive="true"></ins>
    */
    'adsense' => [
        'enabled' => true,
        'client' => 'ca-pub-4013515598409306',
        'slot' => '9540365470',
        'debug' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Adsterra (Monetisasi Link Publik - Alternatif)
    |--------------------------------------------------------------------------
    |
    | Pengaturan untuk Adsterra (Social Bar / Popunder / Banner). Script Social
    | Bar biasanya bersifat async (dengan data-cfasync="false") dan bisa meng-
    | inject elemen sendiri.
    |
    | 'enabled' => nyalakan/matikan Adsterra secara global.
    | 'provider' => 'socialbar' (placeholder untuk fleksibilitas)
    | 'script_url' => URL direct script Adsterra (sudah diobfuscate oleh pihak
    |                 Adsterra). Akan disisipkan dengan data-cfasync="false".
    */
    'adsterra' => [
        'enabled' => true,
        'provider' => 'socialbar',
        'script_url' => 'https://bauval.org/14/db1adb0d008fdfd6de74acc98ff3f0ce',
    ],

    /*
    |--------------------------------------------------------------------------
    | Project Metadata & System Information
    |--------------------------------------------------------------------------
    |
    | Metadata ini ditampilkan pada halaman "System Information". Ubah nilai
    | di sini untuk memperbarui versioning & identitas proyek tanpa perlu
    | menyentuh kode view maupun controller.
    |
    */
    'project' => [
        'name' => 'BlackFile',
        'codename' => 'SECURE TERMINAL',

        // Versi aplikasi saat ini (juga dipakai di sidebar).
        'version' => '5.0.0',
        'channel' => 'STABLE',

        // Tanggal & waktu proyek pertama kali diinisialisasi (commit pertama).
        'created_at' => '2025-08-15 14:33:38',

        // Tanggal & waktu build/rilis terakhir.
        'released_at' => '2026-10-09 11:23:19',

        'description' => 'BlackFile adalah terminal operasi rahasia untuk mengarsipkan data, memetakan jaringan aset, menganalisis entitas, dan menjalankan simulasi konflik.',
    ],
];
