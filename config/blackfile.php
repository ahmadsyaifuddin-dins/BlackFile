<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Asset Categories
    |--------------------------------------------------------------------------
    |
    | Daftar tipe hubungan atau kategori yang lebih spesifik untuk entitas 'Friend' (Asset).
    |
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
    |
    | Daftar tipe project.
    |
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
    | Defines the standardized lists for entity attributes. Centralizing
    | this data ensures consistency and prevents input errors across the
    | application. To add a new option, simply add it to the relevant array.
    |
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
];  