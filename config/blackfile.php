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
    | Entity Classifications (Refactored)
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
        'Seraphim / Cherubim',
        'Archangel',

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
];
