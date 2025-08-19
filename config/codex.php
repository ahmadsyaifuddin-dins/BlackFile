<?php

return [

    /*
    |--------------------------------------------------------------------------
    | BlackFile Codex Terms
    |--------------------------------------------------------------------------
    */



    'codex_terms' => [

        // Additional Project-Specific Terms
        [
            'term' => '[B.F] Asset',
            'category' => 'BlackFile Terminology',
            'definition' => 'Individu atau entity biasanya dianggap sebagai "Friend/Teman" yang memiliki value strategis untuk BlackFile operations.',
            'classification_levels' => 'Alpha (critical), Beta (important), Gamma (useful)',
            'management' => 'Regular contact, status updates, protection protocols',
        ],
        [
            'term' => '[B.F] Anomaly Classification',
            'category' => 'BlackFile Terminology',
            'definition' => 'System untuk categorizing anomalous entities berdasarkan threat level dan containment requirements.',
            'parameters' => 'Threat assessment, containment difficulty, resource requirements',
            'review_cycle' => 'Monthly reassessment, incident-triggered reviews',
        ],
        [
            'term' => 'Operational Security (OpSec)',
            'category' => 'BlackFile Terminology',
            'definition' => 'Protocols untuk protecting sensitive information dan maintaining operational secrecy.',
            'principles' => 'Need-to-know basis, compartmentalization, secure communications',
            'violations' => 'Automatic review, potential termination, memory treatment',
        ],
        [
            'term' => 'Field Agent',
            'category' => 'BlackFile Terminology',
            'definition' => 'Operative yang conducts investigations dan field work untuk BlackFile project.',
            'requirements' => 'Security clearance, anomaly resistance training, combat readiness',
            'equipment' => 'Reality anchors, memetic protection, emergency extraction',
        ],
        [
            'term' => 'Researcher',
            'category' => 'BlackFile Terminology',
            'definition' => 'Scientist atau specialist yang studies anomalous entities dan phenomena.',
            'specializations' => 'Xenobiology, parapsychology, quantum mechanics, mythology',
            'safety_protocols' => 'Hazmat procedures, memetic protection, emergency containment',
        ],

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
        [
            'term' => 'Neutralized',
            'category' => 'SCP Ranks',
            'definition' => 'Anomali yang telah dihancurkan, dimusnahkan, atau kehilangan sifat anomalinya. Tidak lagi memerlukan containment procedures.',
            'containment_requirements' => 'Archive documentation, monitor for reactivation',
            'examples' => 'SCP-1609 (destroyed), various neutralized objects',
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

        // Kategori General & Project Specific
        [
            'term' => '[B.F] Anomaly',
            'category' => 'General & Project Specific',
            'definition' => 'Anomali yang teridentifikasi dan dikategorikan dalam sistem BlackFile. Entitas dengan karakteristik yang menyimpang dari norma scientific atau natural.',
        ],

        [
            'term' => 'Cryptid',
            'category' => 'General & Project Specific',
            'definition' => 'Makhluk misterius yang keberadaannya belum terbukti secara ilmiah. Termasuk Bigfoot, Chupacabra, dan makhluk legendaris lainnya.',
        ],

        [
            'term' => 'Mutant',
            'category' => 'General & Project Specific',
            'definition' => 'Entitas dengan mutasi genetik atau transformasi biologis. Dapat hasil dari radiasi, eksperimen, atau evolusi natural yang tidak biasa.',
        ],

        [
            'term' => 'Humanoid',
            'category' => 'General & Project Specific',
            'definition' => 'Entitas dengan bentuk atau karakteristik menyerupai manusia. Memiliki struktur bipedal dan proporsi yang mirip manusia.',
        ],

        [
            'term' => 'Extradimensional',
            'category' => 'General & Project Specific',
            'definition' => 'Entitas yang berasal dari dimensi atau realitas alternatif. Memiliki sifat fisik yang tidak sesuai dengan hukum alam dimensi kita.',
        ],

        [
            'term' => 'Unidentified Biological Entity (UBE)',
            'category' => 'General & Project Specific',
            'definition' => 'Organisme biologis yang belum teridentifikasi. Dapat berupa spesies baru atau hasil modifikasi biologis yang tidak diketahui.',
        ],

        // Angelic & Demonic Tiers
        [
            'term' => 'Archangel',
            'category' => 'Angelic & Demonic Tiers',
            'definition' => 'Malaikat tingkat tertinggi dengan otoritas dan kekuatan besar. Pemimpin pasukan celestial.',
            'hierarchy_level' => 'Supreme',
        ],
        [
            'term' => 'Seraphim / Cherubim',
            'category' => 'Angelic & Demonic Tiers',
            'definition' => 'Malaikat yang paling dekat dengan takhta ilahi. Makhluk dengan enam sayap yang bersinar.',
            'hierarchy_level' => 'Highest',
        ],
        [
            'term' => 'Principalities',
            'category' => 'Angelic & Demonic Tiers',
            'definition' => 'Malaikat yang mengatur bangsa dan kerajaan. Guardian atas negara dan institusi besar.',
            'hierarchy_level' => 'Regional',
        ],
        [
            'term' => 'Nine Lords (Demon)',
            'category' => 'Angelic & Demonic Tiers',
            'definition' => 'Sembilan penguasa tertinggi dalam hierarki iblis. Kontrol atas aspek berbeda dari kegelapan.',
            'hierarchy_level' => 'Supreme Dark',
        ],
        [
            'term' => 'Archdevil',
            'category' => 'Angelic & Demonic Tiers',
            'definition' => 'Iblis dengan kekuatan dan otoritas tertinggi. Penguasa layer tertentu dalam neraka.',
            'hierarchy_level' => 'Infernal Noble',
        ],
        [
            'term' => 'Pit Fiend',
            'category' => 'Angelic & Demonic Tiers',
            'definition' => 'Demon kelas atas dari neraka dengan kekuatan destruktif. Jenderal dalam pasukan kegelapan.',
            'hierarchy_level' => 'Greater Demon',
        ],
        [
            'term' => 'Archdemon',
            'category' => 'Angelic & Demonic Tiers',
            'definition' => 'Demon kelas atas dari neraka dengan kekuatan destruktif. Jenderal dalam pasukan kegelapan.',
            'hierarchy_level' => 'Greater Demon',
        ],

        // Cosmic & Extradimensional
        [
            'term' => 'Alternate Dimension',
            'category' => 'Cosmic & Extradimensional',
            'definition' => 'Dimensi alternatif atau paralel. Universe with different physical laws or history.',
            'location_type' => 'Parallel Dimension',
        ],
        [
            'term' => 'The Void',
            'category' => 'Cosmic & Extradimensional',
            'definition' => 'Kekosongan kosmik atau ruang antar dimensi. Empty space between realities.',
            'location_type' => 'Interdimensional Space',
        ],
        [
            'term' => 'Zeta Reticuli Star System',
            'category' => 'Cosmic & Extradimensional',
            'definition' => 'Sistem bintang yang sering dikaitkan dengan UFO. Binary star system 39 light years away.',
            'location_type' => 'Stellar System',
        ],
        [
            'term' => 'Andromeda Galaxy',
            'category' => 'Cosmic & Extradimensional',
            'definition' => 'Galaksi terdekat dengan Bima Sakti. Nearest major galaxy to Milky Way.',
            'location_type' => 'Galactic',
        ],
        [
            'term' => 'Cthulhu Mythos',
            'category' => 'Cosmic & Extradimensional',
            'definition' => 'Alam semesta kosmik horror Lovecraftian. Reality where cosmic entities exist.',
            'location_type' => 'Cosmic',
        ],
        [
            'term' => 'The Dreamlands',
            'category' => 'Cosmic & Extradimensional',
            'definition' => 'Alam mimpi dalam mitologi Lovecraft. Dimension accessed through dreams.',
            'location_type' => 'Dream Dimension',
        ],
        [
            'term' => 'Beyond the Wall of Sleep',
            'category' => 'Cosmic & Extradimensional',
            'definition' => 'Wilayah di luar batas tidur dan mimpi. Realm beyond normal sleep and dreams.',
            'location_type' => 'Transcendent Dimension',
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
            'term' => 'Web',
            'category' => 'Project Types',
            'definition' => 'Situs web statis atau dinamis dengan fokus presentasi informasi. Dapat berupa website portfolio, blog, atau landing page.',
        ],
        [
            'term' => 'Web Application',
            'category' => 'Project Types',
            'definition' => 'Aplikasi yang diakses melalui browser web. Biasanya melibatkan interaksi pengguna yang kompleks, manajemen data, dan otentikasi. Contoh: Dasbor analitik, sistem manajemen konten.',
        ],
        [
            'term' => 'Mobile Application',
            'category' => 'Project Types',
            'definition' => 'Aplikasi untuk perangkat mobile (iOS, Android, dll.). Dapat berupa native app atau cross-platform application.',
        ],
        [
            'term' => 'Desktop Application',
            'category' => 'Project Types',
            'definition' => 'Software yang berjalan di sistem operasi desktop. Dapat berupa standalone application atau client untuk sistem yang lebih besar.',
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
        [
            'term' => 'CLI Tool',
            'category' => 'Project Types',
            'definition' => 'Command Line Interface tool untuk otomasi dan utilitas sistem. Membantu developer dalam workflow dan task automation.',
        ],
        [
            'term' => 'DevOps Tool',
            'category' => 'Project Types',
            'definition' => 'Alat untuk deployment, monitoring, dan manajemen infrastructure. Mendukung CI/CD pipeline dan system administration.',
        ],
        [
            'term' => 'Library / API',
            'category' => 'Project Types',
            'definition' => 'Kumpulan fungsi atau layanan yang dapat digunakan oleh aplikasi lain. Menyediakan interface untuk integrasi sistem.',
        ],
        [
            'term' => 'Framework',
            'category' => 'Project Types',
            'definition' => 'Kerangka kerja untuk pengembangan aplikasi dengan struktur terstandar. Menyediakan foundation dan best practices.',
        ],
        [
            'term' => 'BOT',
            'category' => 'Project Types',
            'definition' => 'Automated program untuk melakukan tugas repetitif atau interaksi. Dapat berupa chatbot, web scraper, atau automation tool.',
        ],
        [
            'term' => 'Operating System',
            'category' => 'Project Types',
            'definition' => 'Sistem operasi atau komponen tingkat sistem. Termasuk kernel development dan system programming.',
        ],
        [
            'term' => 'UI/UX Design',
            'category' => 'Project Types',
            'definition' => 'Proyek desain antarmuka pengguna dan pengalaman pengguna. Fokus pada usability dan visual design.',
        ],
    ],
];
