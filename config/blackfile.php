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
    */

    'entity_categories' => [
        // General & Project Specific
        '[B.F] Anomaly',
        'Cryptid',
        'Mutant',
        'Humanoid',
        'Extradimensional',
        'Unidentified Biological Entity (UBE)',

        // Fictional & Mythological Universes
        'SCP Anomaly',
        'Cthulhu Mythos',
        'Mythological Being',
        'Angelic Hierarchy',
        'Demonic Hierarchy',
        'Folklore Creature',
        'Extraterrestrial',
        
        // Technical
        'Artificial Intelligence',
        'Metaphysical Concept',
        'Geological Anomaly',
    ],

    'entity_ranks' => [
        // General Threat Levels
        'Unclassified',
        'Benign',
        'Class-C (Low Threat)',
        'Class-B (Moderate Threat)',
        'Class-A (High Threat)',
        'Omega (Global Threat)',
        'Cosmic (Existential Threat)',

        // SCP Foundation Ranks
        'Safe',
        'Euclid',
        'Keter',
        'Thaumiel',
        'Apollyon',
        'Neutralized',

        // Mythological & Divine Tiers
        'Primordial',
        'Titan',
        'God / Deity',
        'Demigod',
        'Legendary Hero',

        // Angelic & Demonic Tiers
        'Archangel',
        'Seraphim / Cherubim',
        'Principalities',
        'Nine Lords (Demon)',
        'Archdevil',
        'Pit Fiend',
        
        // Cthulhu Mythos Tiers
        'Outer God',
        'Great Old One',
        'Elder Thing',
        'Servitor Race',
    ],

    'entity_origins' => [
        // General & Terrestrial
        'Unknown',
        'Earth',
        'Remote/Unexplored Region',
        'The Abyss (Oceanic)',
        'Subterranean',
        'Antarctica',
        
        // Project & Agency Specific
        '[B.F] Laboratory Creation',
        '[REDACTED]',
        'Site-19 (SCP)',
        'Area 51 (GOI)',
        
        // Mythological & Folklore Realms
        'Norse Mythology (Asgard/Yggdrasil)',
        'Greek Mythology (Olympus/Underworld)',
        'Egyptian Mythology (Duat)',
        'Japanese Folklore (Yomi)',
        'Abrahamic Lore',
        'Celtic Otherworld',
        
        // Cosmic & Extradimensional
        'Alternate Dimension',
        'The Void',
        'Zeta Reticuli Star System',
        'Andromeda Galaxy',
        'Cthulhu Mythos',
        'The Dreamlands',
        'Beyond the Wall of Sleep',
    ],

     /*
    |--------------------------------------------------------------------------
    | BlackFile Codex Terms
    |--------------------------------------------------------------------------
    */

    'codex_terms' => [
        // Kategori: SCP Ranks
        [
            'term' => 'Safe',
            'category' => 'SCP Ranks',
            'definition' => 'Anomali yang cukup dipahami untuk dapat ditampung secara andal dan permanen. Kelas "Safe" tidak berarti anomali tersebut tidak berbahaya; hanya saja prosedur penanganannya diketahui efektif.',
        ],
        [
            'term' => 'Euclid',
            'category' => 'SCP Ranks',
            'definition' => 'Anomali yang tidak cukup dipahami atau pada dasarnya tidak dapat diprediksi, sehingga penanganannya tidak selalu andal. Euclid adalah kelas default untuk anomali baru yang belum sepenuhnya dipahami.',
        ],
        [
            'term' => 'Keter',
            'category' => 'SCP Ranks',
            'definition' => 'Anomali yang sangat sulit untuk ditampung secara konsisten atau andal, dengan prosedur penahanan yang seringkali rumit dan ekstensif. Entitas di kelas ini biasanya sangat berbahaya.',
        ],
        [
            'term' => 'Thaumiel',
            'category' => 'SCP Ranks',
            'definition' => 'Anomali yang digunakan oleh The Foundation untuk menampung atau melawan anomali lain. Keberadaan kelas Thaumiel sangat rahasia.',
        ],
        [
            'term' => 'Apollyon',
            'category' => 'SCP Ranks',
            'definition' => 'Anomali yang tidak dapat ditampung dan secara aktif menimbulkan skenario akhir dunia. Kelas ini mewakili kegagalan total dalam misi penahanan.',
        ],

        // Kategori: Cthulhu Mythos
        [
            'term' => 'Great Old One',
            'category' => 'Cthulhu Mythos',
            'definition' => 'Entitas kosmik yang sangat kuat dan kuno yang berasal dari luar angkasa atau dimensi lain. Keberadaan mereka saja dapat merusak realitas dan kewarasan.',
        ],
        [
            'term' => 'Outer God',
            'category' => 'Cthulhu Mythos',
            'definition' => 'Tingkatan yang lebih tinggi dari Great Old Ones, seringkali merupakan perwujudan dari prinsip-prinsip kosmik itu sendiri (misalnya Azathoth). Mereka berada di luar pemahaman manusia.',
        ],
        [
            'term' => 'R\'lyeh',
            'category' => 'Cthulhu Mythos',
            'definition' => 'Kota kuno yang tenggelam di Samudra Pasifik, dibangun dengan arsitektur non-Euclidean. Ini adalah penjara sekaligus tempat peristirahatan bagi Great Old One Cthulhu.',
        ],
        
        // Kategori: Asset Categories
        [
            'term' => 'Informan',
            'category' => 'Asset Categories',
            'definition' => 'Individu yang menyediakan informasi, seringkali secara rahasia. Tidak selalu dapat diandalkan sepenuhnya dan mungkin memiliki agenda pribadi. Tingkat kepercayaan harus diverifikasi secara berkala.',
        ],
        [
            'term' => 'Aset',
            'category' => 'Asset Categories',
            'definition' => 'Individu atau sumber daya yang dapat diaktifkan untuk melakukan tugas tertentu. Aset memiliki tingkat loyalitas dan kemampuan yang telah teruji, serta berada di bawah kendali operasional langsung atau tidak langsung.',
        ],

        // Kategori: Project Types
        [
            'term' => 'Web Application',
            'category' => 'Project Types',
            'definition' => 'Aplikasi yang diakses melalui browser web. Biasanya melibatkan interaksi pengguna yang kompleks, manajemen data, dan otentikasi. Contoh: Dasbor analitik, sistem manajemen konten.',
        ],
        [
            'term' => 'IoT Application',
            'category' => 'Project Types',
            'definition' => 'Aplikasi yang dirancang untuk mengontrol atau menerima data dari perangkat fisik yang terhubung ke internet (Internet of Things). Melibatkan komunikasi perangkat-ke-perangkat atau perangkat-ke-cloud.',
        ],
        [
            'term' => 'Blockchain',
            'category' => 'Project Types',
            'definition' => 'Proyek yang memanfaatkan teknologi buku besar terdistribusi (distributed ledger). Digunakan untuk aplikasi yang memerlukan desentralisasi, transparansi, dan keamanan data yang tidak dapat diubah.',
        ],
    ],
];
