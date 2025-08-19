<?php

return [

    /*
    |--------------------------------------------------------------------------
    | BlackFile Project - Glosarium Lengkap
    |--------------------------------------------------------------------------
    |
    | Glosarium komprehensif untuk semua kategori dan istilah dalam BlackFile Project.
    | Setiap kategori memiliki definisi detail untuk konsistensi terminologi.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Kategori Aset (Asset Categories)
    |--------------------------------------------------------------------------
    */
    'asset_categories_glossary' => [
        // Lingkaran Pribadi
        'Sahabat' => [
            'category' => 'Lingkaran Pribadi',
            'definition' => 'Individu dengan hubungan personal yang sangat dekat dan dapat dipercaya sepenuhnya. Memiliki akses ke informasi pribadi dan dapat diandalkan dalam situasi kritis.',
        ],

        // Additional Project-Specific Terms
        [
            'term' => '[B.F] Asset',
            'category' => 'BlackFile Terminology',
            'definition' => 'Individu atau entity yang memiliki value strategis untuk BlackFile operations.',
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
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Threat Assessment Matrix
    |--------------------------------------------------------------------------
    */
    'threat_assessment_matrix' => [
        'criteria' => [
            'lethality' => [
                'none' => 0,
                'low' => 1,
                'moderate' => 2,
                'high' => 3,
                'extreme' => 4,
                'extinction' => 5,
            ],
            'containment_difficulty' => [
                'trivial' => 0,
                'easy' => 1,
                'moderate' => 2,
                'difficult' => 3,
                'extreme' => 4,
                'impossible' => 5,
            ],
            'intelligence' => [
                'none' => 0,
                'animal' => 1,
                'human' => 2,
                'superhuman' => 3,
                'cosmic' => 4,
                'omniscient' => 5,
            ],
            'reality_alteration' => [
                'none' => 0,
                'minor' => 1,
                'localized' => 2,
                'regional' => 3,
                'global' => 4,
                'universal' => 5,
            ],
        ],
        'classification_formula' => 'Total score determines threat classification and required containment protocols',
    ],

    /*
    |--------------------------------------------------------------------------
    | Research Protocols
    |--------------------------------------------------------------------------
    */
    'research_protocols' => [
        'safety_levels' => [
            'Level 0' => 'No special precautions required',
            'Level 1' => 'Standard laboratory safety protocols',
            'Level 2' => 'Enhanced containment, specialized equipment',
            'Level 3' => 'Maximum security, remote operation preferred',
            'Level 4' => 'Extreme hazard, minimal exposure protocols',
            'Level X' => 'Experimental protocols, case-by-case basis',
        ],
        'documentation_requirements' => [
            'initial_report' => 'First contact documentation, preliminary assessment',
            'detailed_analysis' => 'Comprehensive study, anomalous properties catalog',
            'containment_procedures' => 'Step-by-step containment protocols',
            'incident_reports' => 'All anomalous events, containment breaches',
            'termination_logs' => 'If applicable, destruction or neutralization records',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Emergency Response Codes
    |--------------------------------------------------------------------------
    */
    'emergency_codes' => [
        'Code Green' => [
            'definition' => 'No immediate threat, routine operations',
            'response' => 'Standard protocols, normal staffing',
        ],
        'Code Yellow' => [
            'definition' => 'Potential anomaly detected, investigation required',
            'response' => 'Deploy field team, begin preliminary assessment',
        ],
        'Code Orange' => [
            'definition' => 'Confirmed anomaly, containment required',
            'response' => 'Full team deployment, establish containment perimeter',
        ],
        'Code Red' => [
            'definition' => 'Active threat, immediate danger to personnel',
            'response' => 'Emergency protocols, evacuation if necessary',
        ],
        'Code Black' => [
            'definition' => 'Catastrophic failure, potential reality breach',
            'response' => 'All available resources, consider nuclear option',
        ],
        'Code White' => [
            'definition' => 'Mass casualty event, memetic contamination',
            'response' => 'Quarantine protocols, memory treatment preparation',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Equipment Classifications
    |--------------------------------------------------------------------------
    */
    'equipment_classifications' => [
        'detection_equipment' => [
            'EMF_detectors' => 'Electromagnetic field anomaly detection',
            'reality_meters' => 'Hume level measurement devices',
            'spectral_analyzers' => 'Multi-spectrum anomaly analysis',
            'psychic_dampeners' => 'Mental influence protection',
        ],
        'containment_equipment' => [
            'reality_anchors' => 'Local reality stabilization',
            'force_field_generators' => 'Physical barrier creation',
            'temporal_stabilizers' => 'Time anomaly prevention',
            'dimensional_locks' => 'Prevent extradimensional travel',
        ],
        'protection_equipment' => [
            'hazmat_suits' => 'Biological/chemical protection',
            'memetic_filters' => 'Cognitive hazard protection',
            'reality_suits' => 'Personal reality anchor systems',
            'emergency_extraction' => 'Rapid evacuation devices',
        ],
    ],
    /*
    |--------------------------------------------------------------------------
    | Lingkaran Hubungan (Relationship Categories)
    |--------------------------------------------------------------------------
    */
    'relationship_categories' => [
        'Teman' => [
            'category' => 'Lingkaran Pribadi',
            'definition' => 'Individu dengan hubungan personal yang baik namun tidak seintim sahabat. Hubungan bersifat positif namun dengan batasan tertentu.',
        ],
        'Keluarga' => [
            'category' => 'Lingkaran Pribadi',
            'definition' => 'Anggota keluarga biologis atau adopsi yang memiliki ikatan darah atau hukum. Hubungan permanen dengan loyalitas natural.',
        ],

        // Lingkaran Akademis
        'Teman Kelas' => [
            'category' => 'Lingkaran Akademis',
            'definition' => 'Rekan dalam lingkungan pendidikan atau pelatihan yang sama. Berbagi pengalaman belajar dan akademis.',
        ],
        'Rekan Kelompok' => [
            'category' => 'Lingkaran Akademis',
            'definition' => 'Partner dalam proyek akademis atau riset tertentu. Kolaborasi terbatas pada proyek atau tujuan akademis spesifik.',
        ],
        'Senior / Junior' => [
            'category' => 'Lingkaran Akademis',
            'definition' => 'Hubungan hierarkis dalam institusi pendidikan berdasarkan tingkat atau pengalaman. Melibatkan mentoring atau bimbingan.',
        ],

        // Lingkaran Profesional
        'Rekan Kerja' => [
            'category' => 'Lingkaran Profesional',
            'definition' => 'Individu yang bekerja dalam organisasi atau departemen yang sama. Hubungan didasarkan pada kerjasama profesional.',
        ],
        'Kontak Profesional' => [
            'category' => 'Lingkaran Profesional',
            'definition' => 'Jaringan profesional di luar organisasi langsung untuk keperluan bisnis atau karir. Hubungan strategis untuk pengembangan karir.',
        ],

        // Lingkaran Operasional
        'Informan' => [
            'category' => 'Lingkaran Operasional',
            'definition' => 'Sumber informasi yang memberikan data atau intelligence untuk operasi. Dapat bersifat aktif atau pasif dalam penyediaan informasi.',
        ],
        'Aset' => [
            'category' => 'Lingkaran Operasional',
            'definition' => 'Individu yang direkrut atau dimanfaatkan untuk tujuan operasional spesifik. Memiliki nilai strategis dalam misi atau operasi.',
        ],

        // Lain-lain
        'Lainnya' => [
            'category' => 'Lain-lain',
            'definition' => 'Kategori untuk hubungan yang tidak masuk dalam klasifikasi standar di atas. Memerlukan deskripsi khusus untuk setiap kasus.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tipe Proyek (Project Types)
    |--------------------------------------------------------------------------
    */
    'project_types_glossary' => [
        // Aplikasi & Platform
        'Web' => [
            'category' => 'Aplikasi & Platform',
            'definition' => 'Situs web statis atau dinamis dengan fokus presentasi informasi. Dapat berupa website portfolio, blog, atau landing page.',
        ],
        'Web Application' => [
            'category' => 'Aplikasi & Platform',
            'definition' => 'Aplikasi berbasis browser dengan fungsi interaktif kompleks. Memiliki database backend dan sistem user management.',
        ],
        'Mobile Application' => [
            'category' => 'Aplikasi & Platform',
            'definition' => 'Aplikasi untuk perangkat mobile (iOS, Android, dll.). Dapat berupa native app atau cross-platform application.',
        ],
        'Desktop Application' => [
            'category' => 'Aplikasi & Platform',
            'definition' => 'Software yang berjalan di sistem operasi desktop. Dapat berupa standalone application atau client untuk sistem yang lebih besar.',
        ],

        // Teknologi Spesialis
        'IoT Application' => [
            'category' => 'Teknologi Spesialis',
            'definition' => 'Aplikasi untuk Internet of Things dan perangkat terhubung. Mengelola komunikasi antar device dan data sensor.',
        ],
        'CLI Tool' => [
            'category' => 'Teknologi Spesialis',
            'definition' => 'Command Line Interface tool untuk otomasi dan utilitas sistem. Membantu developer dalam workflow dan task automation.',
        ],
        'DevOps Tool' => [
            'category' => 'Teknologi Spesialis',
            'definition' => 'Alat untuk deployment, monitoring, dan manajemen infrastructure. Mendukung CI/CD pipeline dan system administration.',
        ],
        'Blockchain' => [
            'category' => 'Teknologi Spesialis',
            'definition' => 'Aplikasi berbasis teknologi blockchain dan cryptocurrency. Termasuk smart contract dan DeFi applications.',
        ],

        // Framework & Library
        'Library / API' => [
            'category' => 'Framework & Library',
            'definition' => 'Kumpulan fungsi atau layanan yang dapat digunakan oleh aplikasi lain. Menyediakan interface untuk integrasi sistem.',
        ],
        'Framework' => [
            'category' => 'Framework & Library',
            'definition' => 'Kerangka kerja untuk pengembangan aplikasi dengan struktur terstandar. Menyediakan foundation dan best practices.',
        ],
        'BOT' => [
            'category' => 'Framework & Library',
            'definition' => 'Automated program untuk melakukan tugas repetitif atau interaksi. Dapat berupa chatbot, web scraper, atau automation tool.',
        ],

        // Sistem & Design
        'Operating System' => [
            'category' => 'Sistem & Design',
            'definition' => 'Sistem operasi atau komponen tingkat sistem. Termasuk kernel development dan system programming.',
        ],
        'UI/UX Design' => [
            'category' => 'Sistem & Design',
            'definition' => 'Proyek desain antarmuka pengguna dan pengalaman pengguna. Fokus pada usability dan visual design.',
        ],
        'Lainnya' => [
            'category' => 'Sistem & Design',
            'definition' => 'Proyek yang tidak masuk dalam kategori standar di atas. Memerlukan deskripsi khusus untuk setiap kasus.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Kategori Entitas (Entity Categories)
    |--------------------------------------------------------------------------
    */
    'entity_categories_glossary' => [
        // General & Project Specific
        '[B.F] Anomaly' => [
            'category' => 'General & Project Specific',
            'definition' => 'Anomali yang teridentifikasi dan dikategorikan dalam sistem BlackFile. Entitas dengan karakteristik yang menyimpang dari norma scientific atau natural.',
        ],
        'Cryptid' => [
            'category' => 'General & Project Specific',
            'definition' => 'Makhluk misterius yang keberadaannya belum terbukti secara ilmiah. Termasuk Bigfoot, Chupacabra, dan makhluk legendaris lainnya.',
        ],
        'Mutant' => [
            'category' => 'General & Project Specific',
            'definition' => 'Entitas dengan mutasi genetik atau transformasi biologis. Dapat hasil dari radiasi, eksperimen, atau evolusi natural yang tidak biasa.',
        ],
        'Humanoid' => [
            'category' => 'General & Project Specific',
            'definition' => 'Entitas dengan bentuk atau karakteristik menyerupai manusia. Memiliki struktur bipedal dan proporsi yang mirip manusia.',
        ],
        'Extradimensional' => [
            'category' => 'General & Project Specific',
            'definition' => 'Entitas yang berasal dari dimensi atau realitas alternatif. Memiliki sifat fisik yang tidak sesuai dengan hukum alam dimensi kita.',
        ],
        'Unidentified Biological Entity (UBE)' => [
            'category' => 'General & Project Specific',
            'definition' => 'Organisme biologis yang belum teridentifikasi. Dapat berupa spesies baru atau hasil modifikasi biologis yang tidak diketahui.',
        ],

        // Fictional & Mythological Universes
        'SCP Anomaly' => [
            'category' => 'Fictional & Mythological Universes',
            'definition' => 'Entitas dalam klasifikasi SCP Foundation untuk objek anomali. Mengikuti protokol Secure, Contain, Protect.',
        ],
        'Cthulhu Mythos' => [
            'category' => 'Fictional & Mythological Universes',
            'definition' => 'Entitas dari alam semesta kosmik horror H.P. Lovecraft. Makhluk kuno dengan kekuatan yang dapat merusak kewarasan manusia.',
        ],
        'Mythological Being' => [
            'category' => 'Fictional & Mythological Universes',
            'definition' => 'Makhluk dari mitologi dan legenda berbagai budaya. Termasuk dewa-dewi, monster, dan makhluk supernatural dari folklore dunia.',
        ],
        'Angelic Hierarchy' => [
            'category' => 'Fictional & Mythological Universes',
            'definition' => 'Entitas dari hierarki malaikat dalam tradisi Abrahamik. Mengikuti struktur celestial dengan tingkatan kekuatan yang berbeda.',
        ],
        'Demonic Hierarchy' => [
            'category' => 'Fictional & Mythological Universes',
            'definition' => 'Entitas dari hierarki iblis dan demon. Struktur infernal dengan berbagai tingkatan kekuatan dan otoritas.',
        ],
        'Folklore Creature' => [
            'category' => 'Fictional & Mythological Universes',
            'definition' => 'Makhluk dari cerita rakyat dan tradisi oral. Entitas yang hidup dalam kepercayaan dan cerita turun temurun masyarakat.',
        ],
        'Extraterrestrial' => [
            'category' => 'Fictional & Mythological Universes',
            'definition' => 'Entitas yang berasal dari planet atau sistem bintang lain. Kehidupan alien dengan teknologi dan biologi yang berbeda.',
        ],

        // Technical
        'Artificial Intelligence' => [
            'category' => 'Technical',
            'definition' => 'Entitas berbasis kecerdasan buatan atau digital. Dapat berupa AI yang mencapai sentience atau sistem komputer anomali.',
        ],
        'Metaphysical Concept' => [
            'category' => 'Technical',
            'definition' => 'Konsep atau entitas yang bersifat metafisik. Ide atau prinsip yang memperoleh bentuk atau kekuatan dalam realitas.',
        ],
        'Geological Anomaly' => [
            'category' => 'Technical',
            'definition' => 'Anomali yang berkaitan dengan formasi atau fenomena geologis. Struktur bumi atau mineral dengan sifat tidak biasa.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Peringkat Entitas (Entity Ranks)
    |--------------------------------------------------------------------------
    */
    'entity_ranks_glossary' => [
        // General Threat Levels
        'Unclassified' => [
            'category' => 'General Threat Levels',
            'definition' => 'Belum memiliki klasifikasi tingkat ancaman yang jelas. Memerlukan penelitian lebih lanjut untuk menentukan level bahaya.',
            'threat_level' => 'Unknown',
        ],
        'Benign' => [
            'category' => 'General Threat Levels',
            'definition' => 'Tidak berbahaya atau bahkan bermanfaat bagi manusia. Entitas yang dapat berinteraksi dengan aman.',
            'threat_level' => 'None',
        ],
        'Class-C (Low Threat)' => [
            'category' => 'General Threat Levels',
            'definition' => 'Ancaman rendah, dapat ditangani dengan prosedur standar. Risiko minimal dengan protokol keamanan dasar.',
            'threat_level' => 'Low',
        ],
        'Class-B (Moderate Threat)' => [
            'category' => 'General Threat Levels',
            'definition' => 'Ancaman sedang, memerlukan kehati-hatian khusus. Dapat menyebabkan cedera atau kerusakan terbatas.',
            'threat_level' => 'Moderate',
        ],
        'Class-A (High Threat)' => [
            'category' => 'General Threat Levels',
            'definition' => 'Ancaman tinggi, berbahaya bagi personel dan sipil. Memerlukan protokol keamanan ketat dan personel terlatih.',
            'threat_level' => 'High',
        ],
        'Omega (Global Threat)' => [
            'category' => 'General Threat Levels',
            'definition' => 'Ancaman level global yang dapat mengancam peradaban. Potensi untuk menyebabkan kerusakan massal atau collapse society.',
            'threat_level' => 'Global',
        ],
        'Cosmic (Existential Threat)' => [
            'category' => 'General Threat Levels',
            'definition' => 'Ancaman terhadap eksistensi realitas itu sendiri. Dapat merusak fabric of reality atau mengakhiri alam semesta.',
            'threat_level' => 'Existential',
        ],

        // SCP Foundation Ranks
        'Safe' => [
            'category' => 'SCP Foundation Ranks',
            'definition' => 'Anomali yang dapat ditampung secara andal dengan prosedur yang diketahui. Safe tidak berarti tidak berbahaya.',
            'containment_difficulty' => 'Low',
        ],
        'Euclid' => [
            'category' => 'SCP Foundation Ranks',
            'definition' => 'Anomali yang tidak dapat diprediksi atau belum sepenuhnya dipahami. Kelas default untuk anomali baru.',
            'containment_difficulty' => 'Medium',
        ],
        'Keter' => [
            'category' => 'SCP Foundation Ranks',
            'definition' => 'Anomali yang sangat sulit ditampung dan berbahaya. Membutuhkan sumber daya signifikan untuk containment.',
            'containment_difficulty' => 'High',
        ],
        'Thaumiel' => [
            'category' => 'SCP Foundation Ranks',
            'definition' => 'Anomali yang digunakan untuk menampung atau melawan anomali lain. Informasi sangat rahasia.',
            'containment_difficulty' => 'Beneficial',
        ],
        'Apollyon' => [
            'category' => 'SCP Foundation Ranks',
            'definition' => 'Anomali yang tidak dapat ditampung dan menyebabkan skenario akhir dunia. Kegagalan total containment.',
            'containment_difficulty' => 'Impossible',
        ],
        'Neutralized' => [
            'category' => 'SCP Foundation Ranks',
            'definition' => 'Anomali yang telah dihancurkan atau kehilangan sifat anomalinya. Tidak lagi memerlukan containment.',
            'containment_difficulty' => 'None',
        ],

        // Mythological & Divine Tiers
        'Primordial' => [
            'category' => 'Mythological & Divine Tiers',
            'definition' => 'Entitas yang ada sejak awal penciptaan alam semesta. Kekuatan fundamental yang membentuk realitas.',
            'power_level' => 'Cosmic',
        ],
        'Titan' => [
            'category' => 'Mythological & Divine Tiers',
            'definition' => 'Makhluk kolossal dengan kekuatan yang sangat besar. Entitas raksasa yang dapat mengubah landscape.',
            'power_level' => 'Massive',
        ],
        'God / Deity' => [
            'category' => 'Mythological & Divine Tiers',
            'definition' => 'Entitas yang dipuja sebagai dewa dengan kekuatan supernatural. Kontrol atas domain atau aspek tertentu.',
            'power_level' => 'Divine',
        ],
        'Demigod' => [
            'category' => 'Mythological & Divine Tiers',
            'definition' => 'Keturunan dewa atau entitas dengan kekuatan ilahi terbatas. Setengah manusia setengah dewa.',
            'power_level' => 'Semi-Divine',
        ],
        'Legendary Hero' => [
            'category' => 'Mythological & Divine Tiers',
            'definition' => 'Pahlawan legendaris dengan kemampuan luar biasa. Manusia dengan prestasi dan kekuatan yang melampaui normal.',
            'power_level' => 'Heroic',
        ],

        // Angelic & Demonic Tiers
        'Archangel' => [
            'category' => 'Angelic & Demonic Tiers',
            'definition' => 'Malaikat tingkat tertinggi dengan otoritas dan kekuatan besar. Pemimpin pasukan celestial.',
            'hierarchy_level' => 'Supreme',
        ],
        'Seraphim / Cherubim' => [
            'category' => 'Angelic & Demonic Tiers',
            'definition' => 'Malaikat yang paling dekat dengan takhta ilahi. Makhluk dengan enam sayap yang bersinar.',
            'hierarchy_level' => 'Highest',
        ],
        'Principalities' => [
            'category' => 'Angelic & Demonic Tiers',
            'definition' => 'Malaikat yang mengatur bangsa dan kerajaan. Guardian atas negara dan institusi besar.',
            'hierarchy_level' => 'Regional',
        ],
        'Nine Lords (Demon)' => [
            'category' => 'Angelic & Demonic Tiers',
            'definition' => 'Sembilan penguasa tertinggi dalam hierarki iblis. Kontrol atas aspek berbeda dari kegelapan.',
            'hierarchy_level' => 'Supreme Dark',
        ],
        'Archdevil' => [
            'category' => 'Angelic & Demonic Tiers',
            'definition' => 'Iblis dengan kekuatan dan otoritas tertinggi. Penguasa layer tertentu dalam neraka.',
            'hierarchy_level' => 'Infernal Noble',
        ],
        'Pit Fiend' => [
            'category' => 'Angelic & Demonic Tiers',
            'definition' => 'Demon kelas atas dari neraka dengan kekuatan destruktif. Jenderal dalam pasukan kegelapan.',
            'hierarchy_level' => 'Greater Demon',
        ],

        // Cthulhu Mythos Tiers
        'Outer God' => [
            'category' => 'Cthulhu Mythos Tiers',
            'definition' => 'Entitas kosmik tertinggi yang mewakili prinsip fundamental alam semesta. Beyond human comprehension.',
            'cosmic_level' => 'Ultimate',
        ],
        'Great Old One' => [
            'category' => 'Cthulhu Mythos Tiers',
            'definition' => 'Entitas kuno dan sangat kuat dari luar angkasa atau dimensi lain. Can drive mortals insane.',
            'cosmic_level' => 'Ancient',
        ],
        'Elder Thing' => [
            'category' => 'Cthulhu Mythos Tiers',
            'definition' => 'Ras kuno yang pernah menguasai Bumi di masa prasejarah. Advanced alien civilization.',
            'cosmic_level' => 'Prehistoric',
        ],
        'Servitor Race' => [
            'category' => 'Cthulhu Mythos Tiers',
            'definition' => 'Ras yang melayani entitas kosmik yang lebih kuat. Created or enslaved by Great Old Ones.',
            'cosmic_level' => 'Servant',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Asal Usul Entitas (Entity Origins)
    |--------------------------------------------------------------------------
    */
    'entity_origins_glossary' => [
        // General & Terrestrial
        'Unknown' => [
            'category' => 'General & Terrestrial',
            'definition' => 'Asal usul yang tidak diketahui atau tidak dapat ditentukan. Memerlukan investigasi lebih lanjut.',
            'location_type' => 'Unidentified',
        ],
        'Earth' => [
            'category' => 'General & Terrestrial',
            'definition' => 'Berasal dari planet Bumi. Entitas native atau hasil evolusi natural di planet ini.',
            'location_type' => 'Terrestrial',
        ],
        'Remote/Unexplored Region' => [
            'category' => 'General & Terrestrial',
            'definition' => 'Daerah terpencil atau belum dieksplorasi di Bumi. Wilayah yang jarang dikunjungi manusia.',
            'location_type' => 'Isolated Terrestrial',
        ],
        'The Abyss (Oceanic)' => [
            'category' => 'General & Terrestrial',
            'definition' => 'Kedalaman samudra yang belum terjamah. Palung laut dengan tekanan dan kegelapan ekstrem.',
            'location_type' => 'Deep Ocean',
        ],
        'Subterranean' => [
            'category' => 'General & Terrestrial',
            'definition' => 'Berasal dari bawah tanah atau gua-gua dalam. Ekosistem underground yang tersembunyi.',
            'location_type' => 'Underground',
        ],
        'Antarctica' => [
            'category' => 'General & Terrestrial',
            'definition' => 'Benua Antartika dengan kondisi ekstrem dan terisolasi. Wilayah es abadi dengan misteri tersembunyi.',
            'location_type' => 'Polar',
        ],

        // Project & Agency Specific
        '[B.F] Laboratory Creation' => [
            'category' => 'Project & Agency Specific',
            'definition' => 'Ciptaan laboratorium dalam proyek BlackFile. Hasil eksperimen atau rekayasa genetik.',
            'location_type' => 'Artificial',
        ],
        '[REDACTED]' => [
            'category' => 'Project & Agency Specific',
            'definition' => 'Informasi asal usul yang diklasifikasikan. Data sensitif yang disembunyikan untuk keamanan.',
            'location_type' => 'Classified',
        ],
        'Site-19 (SCP)' => [
            'category' => 'Project & Agency Specific',
            'definition' => 'Fasilitas SCP Foundation yang terkenal. Site containment dengan multiple anomalous objects.',
            'location_type' => 'Foundation Facility',
        ],
        'Area 51 (GOI)' => [
            'category' => 'Project & Agency Specific',
            'definition' => 'Fasilitas militer rahasia yang dikaitkan dengan aktivitas anomali. Group of Interest operation.',
            'location_type' => 'Military Facility',
        ],

        // Mythological & Folklore Realms
        'Norse Mythology (Asgard/Yggdrasil)' => [
            'category' => 'Mythological & Folklore Realms',
            'definition' => 'Alam para dewa dalam mitologi Nordik. Nine realms connected by the world tree.',
            'location_type' => 'Mythological Realm',
        ],
        'Greek Mythology (Olympus/Underworld)' => [
            'category' => 'Mythological & Folklore Realms',
            'definition' => 'Alam dewa dan dunia bawah dalam mitologi Yunani. Mount of gods and realm of dead.',
            'location_type' => 'Classical Mythological',
        ],
        'Egyptian Mythology (Duat)' => [
            'category' => 'Mythological & Folklore Realms',
            'definition' => 'Dunia kematian dalam mitologi Mesir kuno. Underworld where souls are judged.',
            'location_type' => 'Ancient Mythological',
        ],
        'Japanese Folklore (Yomi)' => [
            'category' => 'Mythological & Folklore Realms',
            'definition' => 'Dunia kematian dalam mitologi Jepang. Land of the dead in Shinto belief.',
            'location_type' => 'Eastern Mythological',
        ],
        'Abrahamic Lore' => [
            'category' => 'Mythological & Folklore Realms',
            'definition' => 'Tradisi dan cerita dalam agama Abrahamik. Heaven, Hell, and divine realms.',
            'location_type' => 'Religious Mythological',
        ],
        'Celtic Otherworld' => [
            'category' => 'Mythological & Folklore Realms',
            'definition' => 'Alam supernatural dalam mitologi Celtic. Realm of fairies, spirits, and magic.',
            'location_type' => 'Celtic Mythological',
        ],

        // Cosmic & Extradimensional
        'Alternate Dimension' => [
            'category' => 'Cosmic & Extradimensional',
            'definition' => 'Dimensi alternatif atau paralel. Universe with different physical laws or history.',
            'location_type' => 'Parallel Dimension',
        ],
        'The Void' => [
            'category' => 'Cosmic & Extradimensional',
            'definition' => 'Kekosongan kosmik atau ruang antar dimensi. Empty space between realities.',
            'location_type' => 'Interdimensional Space',
        ],
        'Zeta Reticuli Star System' => [
            'category' => 'Cosmic & Extradimensional',
            'definition' => 'Sistem bintang yang sering dikaitkan dengan UFO. Binary star system 39 light years away.',
            'location_type' => 'Stellar System',
        ],
        'Andromeda Galaxy' => [
            'category' => 'Cosmic & Extradimensional',
            'definition' => 'Galaksi terdekat dengan Bima Sakti. Nearest major galaxy to Milky Way.',
            'location_type' => 'Galactic',
        ],
        'Cthulhu Mythos' => [
            'category' => 'Cosmic & Extradimensional',
            'definition' => 'Alam semesta kosmik horror Lovecraftian. Reality where cosmic entities exist.',
            'location_type' => 'Lovecraftian Reality',
        ],
        'The Dreamlands' => [
            'category' => 'Cosmic & Extradimensional',
            'definition' => 'Alam mimpi dalam mitologi Lovecraft. Dimension accessed through dreams.',
            'location_type' => 'Dream Dimension',
        ],
        'Beyond the Wall of Sleep' => [
            'category' => 'Cosmic & Extradimensional',
            'definition' => 'Wilayah di luar batas tidur dan mimpi. Realm beyond normal sleep and dreams.',
            'location_type' => 'Transcendent Dimension',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Istilah Kodeks BlackFile (Codex Terms)
    |--------------------------------------------------------------------------
    */
    'expanded_codex_terms' => [
        // SCP Ranks
        [
            'term' => 'Safe',
            'category' => 'SCP Ranks',
            'definition' => 'Anomali yang cukup dipahami untuk dapat ditampung secara andal dan permanen. Kelas "Safe" tidak berarti anomali tersebut tidak berbahaya; hanya saja prosedur penanganannya diketahui efektif.',
            'containment_requirements' => 'Standard security protocols, regular monitoring',
            'examples' => 'SCP-173 (when properly contained), SCP-914',
        ],
        [
            'term' => 'Euclid',
            'category' => 'SCP Ranks',
            'definition' => 'Anomali yang tidak cukup dipahami atau pada dasarnya tidak dapat diprediksi, sehingga penanganannya tidak selalu andal. Euclid adalah kelas default untuk anomali baru yang belum sepenuhnya dipahami. Sebagian besar anomali yang sadar dan otonom umumnya berada di kelas ini.',
            'containment_requirements' => 'Enhanced security, specialized procedures, regular assessment',
            'examples' => 'SCP-173 (unpredictable movement), SCP-096',
        ],
        [
            'term' => 'Keter',
            'category' => 'SCP Ranks',
            'definition' => 'Anomali yang sangat sulit untuk ditampung secara konsisten atau andal, dengan prosedur penahanan yang seringkali rumit dan ekstensif. Entitas di kelas ini biasanya sangat berbahaya bagi personel dan peradaban manusia. Membutuhkan sumber daya yang signifikan untuk ditangani.',
            'containment_requirements' => 'Maximum security, complex procedures, significant resources',
            'examples' => 'SCP-682, SCP-106, SCP-076-2',
        ],
        [
            'term' => 'Thaumiel',
            'category' => 'SCP Ranks',
            'definition' => 'Anomali yang digunakan oleh The Foundation untuk menampung atau melawan anomali lain. Keberadaan kelas Thaumiel sangat rahasia dan hanya diketahui oleh personel tingkat tertinggi.',
            'containment_requirements' => 'Specialized utilization protocols, highest security clearance',
            'examples' => 'SCP-2000, SCP-3000 (partially), SCP-001 proposals',
        ],
        [
            'term' => 'Apollyon',
            'category' => 'SCP Ranks',
            'definition' => 'Anomali yang tidak dapat ditampung dan secara aktif menimbulkan skenario akhir dunia yang akan segera terjadi atau sedang berlangsung. Kelas ini mewakili kegagalan total dalam misi penahanan.',
            'containment_requirements' => 'No containment possible, damage control only',
            'examples' => 'SCP-001 "When Day Breaks", SCP-3999 (former)',
        ],
        [
            'term' => 'Neutralized',
            'category' => 'SCP Ranks',
            'definition' => 'Anomali yang telah dihancurkan, dimusnahkan, atau kehilangan sifat anomalinya. Tidak lagi memerlukan containment procedures.',
            'containment_requirements' => 'Archive documentation, monitor for reactivation',
            'examples' => 'SCP-1609 (destroyed), various neutralized objects',
        ],

        // Cthulhu Mythos
        [
            'term' => 'Great Old One',
            'category' => 'Cthulhu Mythos',
            'definition' => 'Entitas kosmik yang sangat kuat dan kuno yang berasal dari luar angkasa atau dimensi lain. Mereka bukan dewa dalam arti tradisional, tetapi kekuatan alam semesta yang masif dan acuh tak acuh. Keberadaan mereka saja dapat merusak realitas dan kewarasan.',
            'power_characteristics' => 'Reality manipulation, sanity damage, cosmic influence',
            'examples' => 'Cthulhu, Hastur, Dagon, Yog-Sothoth',
        ],
        [
            'term' => 'Outer God',
            'category' => 'Cthulhu Mythos',
            'definition' => 'Tingkatan yang lebih tinggi dari Great Old Ones, seringkali merupakan perwujudan dari prinsip-prinsip kosmik itu sendiri (misalnya Azathoth, "Sultan Iblis Buta"). Mereka berada di luar pemahaman manusia dan seringkali tidak memiliki bentuk fisik yang tetap.',
            'power_characteristics' => 'Fundamental cosmic forces, reality transcendence, incomprehensible nature',
            'examples' => 'Azathoth, Nyarlathotep, Shub-Niggurath',
        ],
        [
            'term' => 'R\'lyeh',
            'category' => 'Cthulhu Mythos',
            'definition' => 'Kota kuno yang tenggelam di Samudra Pasifik, dibangun dengan arsitektur non-Euclidean yang gila. Ini adalah penjara sekaligus tempat peristirahatan bagi Great Old One Cthulhu, yang menunggu bintang-bintang berada di posisi yang benar untuk bangkit kembali.',
            'characteristics' => 'Non-Euclidean geometry, sunken city, star-spawn presence',
            'location' => 'South Pacific Ocean, coordinates 47°9′S 126°43′W',
        ],
        [
            'term' => 'The Dreamlands',
            'category' => 'Cthulhu Mythos',
            'definition' => 'Dimensi yang dapat diakses melalui mimpi yang mendalam. Alam dengan hukum fisika yang berbeda dan dihuni oleh makhluk oneirik dan entitas dream-based.',
            'characteristics' => 'Dream-based reality, alternate physics, oneiric entities',
            'access_method' => 'Deep sleep, dream rituals, certain substances',
        ],
        [
            'term' => 'Elder Thing',
            'category' => 'Cthulhu Mythos',
            'definition' => 'Ras alien kuno yang menguasai Bumi jutaan tahun lalu. Memiliki teknologi tinggi dan menciptakan Shoggoth sebagai budak biologis.',
            'characteristics' => 'Barrel-shaped body, starfish head, advanced technology',
            'historical_period' => 'Precambrian era, Antarctic civilization',
        ],
        [
            'term' => 'Shoggoth',
            'category' => 'Cthulhu Mythos',
            'definition' => 'Makhluk protoplasma yang diciptakan oleh Elder Things sebagai pekerja biologis. Dapat mengubah bentuk dan memiliki kecerdasan primitif.',
            'characteristics' => 'Protoplasmic mass, shape-shifting, multiple eyes',
            'origin' => 'Created by Elder Things as biological tools',
        ],

        // Angelic Hierarchy
        [
            'term' => 'Seraphim',
            'category' => 'Angelic Hierarchy',
            'definition' => 'Malaikat tertinggi dengan enam sayap yang berada paling dekat dengan takhta Tuhan. Makhluk api yang menyucikan dan memurnikan.',
            'characteristics' => 'Six wings, fiery nature, purification powers',
            'biblical_reference' => 'Isaiah 6:2-6',
        ],
        [
            'term' => 'Cherubim',
            'category' => 'Angelic Hierarchy',
            'definition' => 'Malaikat penjaga dengan empat wajah (manusia, singa, lembu, rajawali) dan empat sayap. Guardian of divine mysteries.',
            'characteristics' => 'Four faces, four wings, guardian role',
            'biblical_reference' => 'Ezekiel 1:5-14, Genesis 3:24',
        ],
        [
            'term' => 'Ophanim',
            'category' => 'Angelic Hierarchy',
            'definition' => 'Malaikat berbentuk roda dengan mata yang mengelilinginya. Terkait dengan pergerakan takhta ilahi.',
            'characteristics' => 'Wheel-like form, multiple eyes, divine chariot',
            'biblical_reference' => 'Ezekiel 1:15-21',
        ],
        [
            'term' => 'Archangel',
            'category' => 'Angelic Hierarchy',
            'definition' => 'Malaikat tingkat tinggi dengan otoritas khusus dan tugas penting. Pemimpin pasukan celestial.',
            'characteristics' => 'High authority, warrior aspect, divine messengers',
            'examples' => 'Michael, Gabriel, Raphael, Uriel',
        ],

        // Demonic Hierarchy
        [
            'term' => 'Archdevil',
            'category' => 'Demonic Hierarchy',
            'definition' => 'Penguasa tertinggi dari Nine Hells, masing-masing menguasai satu layer hell. Extremely powerful lawful evil entities.',
            'characteristics' => 'Immense power, lawful evil, layer rulers',
            'examples' => 'Asmodeus, Baalzebul, Mephistopheles',
        ],
        [
            'term' => 'Demon Prince',
            'category' => 'Demonic Hierarchy',
            'definition' => 'Penguasa layer dalam Abyss, realm of chaotic evil. Unlike archdevils, they embody pure chaos.',
            'characteristics' => 'Chaotic evil, abyss rulers, pure corruption',
            'examples' => 'Demogorgon, Orcus, Graz\'zt',
        ],
        [
            'term' => 'Pit Fiend',
            'category' => 'Demonic Hierarchy',
            'definition' => 'Devil kelas tinggi dengan kekuatan destruktif massive. Generals dalam army of hell.',
            'characteristics' => 'Massive size, fire immunity, tactical genius',
            'role' => 'Military commanders, enforcers of archdevils',
        ],
        [
            'term' => 'Balor',
            'category' => 'Demonic Hierarchy',
            'definition' => 'Demon tertinggi dalam hierarchy chaotic evil. Beings of pure destruction dan flame.',
            'characteristics' => 'Fire and shadow, massive wings, whip and sword',
            'power_level' => 'Near-deity level demons',
        ],

        // Mythological Classifications
        [
            'term' => 'Primordial',
            'category' => 'Mythological Classifications',
            'definition' => 'Entitas yang ada sebelum penciptaan dunia modern. Embodiment dari elemental forces.',
            'characteristics' => 'Pre-creation existence, elemental nature, reality shapers',
            'examples' => 'Chaos (Greek), Tiamat (Mesopotamian)',
        ],
        [
            'term' => 'Titan',
            'category' => 'Mythological Classifications',
            'definition' => 'Giant beings yang mendahului gods dalam mythology. Personification dari natural forces.',
            'characteristics' => 'Enormous size, elemental powers, predecessor to gods',
            'examples' => 'Kronos, Atlas, Prometheus (Greek)',
        ],
        [
            'term' => 'Jötunn',
            'category' => 'Norse Mythology',
            'definition' => 'Giant beings dalam Norse mythology yang often oppose gods. Forces of chaos dan nature.',
            'characteristics' => 'Giant size, magical abilities, chaos alignment',
            'examples' => 'Jormungandr, Fenrir, Loki (partial)',
        ],
        [
            'term' => 'Æsir',
            'category' => 'Norse Mythology',
            'definition' => 'Principal group dari gods dalam Norse mythology. Residents of Asgard.',
            'characteristics' => 'Warrior gods, Asgard dwellers, Ragnarok participants',
            'examples' => 'Odin, Thor, Frigg, Balder',
        ],

        // Cryptozoological Classifications
        [
            'term' => 'Cryptid',
            'category' => 'Cryptozoological Classifications',
            'definition' => 'Makhluk yang keberadaannya suggested oleh anecdotal evidence tapi belum proven secara scientific.',
            'characteristics' => 'Unproven existence, folklore basis, eyewitness accounts',
            'examples' => 'Bigfoot, Chupacabra, Loch Ness Monster',
        ],
        [
            'term' => 'Hominid Cryptid',
            'category' => 'Cryptozoological Classifications',
            'definition' => 'Cryptid dengan characteristics humanoid atau primate-like. Often bipedal dan intelligent.',
            'characteristics' => 'Bipedal, primate features, possible intelligence',
            'examples' => 'Bigfoot/Sasquatch, Yeti, Skunk Ape',
        ],
        [
            'term' => 'Lake Monster',
            'category' => 'Cryptozoological Classifications',
            'definition' => 'Large aquatic cryptid yang reportedly inhabit lakes atau large bodies of water.',
            'characteristics' => 'Aquatic habitat, large size, serpentine or prehistoric features',
            'examples' => 'Loch Ness Monster, Champ, Ogopogo',
        ],

        // Anomalous Science Terms
        [
            'term' => 'Reality Anchor',
            'category' => 'Anomalous Science Terms',
            'definition' => 'Device yang stabilizes local reality dan prevents reality warping effects.',
            'function' => 'Maintain baseline reality, counter reality benders',
            'application' => 'SCP containment, reality stabilization',
        ],
        [
            'term' => 'Hume Level',
            'category' => 'Anomalous Science Terms',
            'definition' => 'Measurement dari reality stability dalam given area. Higher levels indicate greater reality alteration.',
            'scale' => '1.0 = baseline reality, <1.0 = weakened, >1.0 = reinforced',
            'usage' => 'Reality measurement, anomaly detection',
        ],
        [
            'term' => 'Scranton Reality Anchor',
            'category' => 'Anomalous Science Terms',
            'definition' => 'Specific type of reality anchor developed untuk counter reality bending anomalies.',
            'inventor' => 'Dr. Robert Scranton (SCP Foundation)',
            'mechanism' => 'Reality stabilization field generation',
        ],
        [
            'term' => 'Memetic Hazard',
            'category' => 'Anomalous Science Terms',
            'definition' => 'Information yang causes anomalous effects when perceived atau understood.',
            'effects' => 'Cognitive hazards, behavioral changes, reality alteration',
            'protection' => 'Memetic resistance training, cognitive filters',
        ],
        [
            'term' => 'Cognitohazard',
            'category' => 'Anomalous Science Terms',
            'definition' => 'Subset dari memetic hazard yang affects cognition specifically.',
            'effects' => 'Memory alteration, perception changes, mental damage',
            'examples' => 'Visual cognitohazards, auditory hazards, conceptual hazards',
        ],

        // Extradimensional Classifications
        [
            'term' => 'Pocket Dimension',
            'category' => 'Extradimensional Classifications',
            'definition' => 'Small self-contained dimensional space dengan limited access dari baseline reality.',
            'characteristics' => 'Finite space, controlled access, altered physics possible',
            'applications' => 'Storage, containment, refuge',
        ],
        [
            'term' => 'Parallel Universe',
            'category' => 'Extradimensional Classifications',
            'definition' => 'Complete alternate reality dengan different history atau physical laws.',
            'characteristics' => 'Full universe scope, alternate timeline, different constants',
            'access_methods' => 'Dimensional rifts, quantum tunneling, anomalous portals',
        ],
        [
            'term' => 'Mirror Dimension',
            'category' => 'Extradimensional Classifications',
            'definition' => 'Reality that reflects atau mirrors baseline reality dengan key differences.',
            'characteristics' => 'Reflective properties, reversed elements, parallel structure',
            'examples' => 'Upside-down worlds, reverse moral universes',
        ]
    ]
];
