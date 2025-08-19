<?php

namespace Database\Seeders;

use App\Models\Entity;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EntitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks untuk truncate
        Schema::disableForeignKeyConstraints();
        DB::table('entity_images')->truncate();
        DB::table('entities')->truncate();
        Schema::enableForeignKeyConstraints();

        // Data entitas dalam array
        $entitiesData = [
            // =================================================================
            // == DATA AWAL
            // =================================================================
                [
                    'name' => 'Manusia',
                    'codename' => 'Subjek-Dasar',
                    'category' => 'Humanoid',
                    'rank' => 'Benign',
                    'origin' => 'Earth',
                    'description' => 'Spesies dominan di planet Bumi. Menunjukkan berbagai tingkat kecerdasan, agresi, dan empati. Fondasi dari sebagian besar masyarakat dan konflik global. Dianggap sebagai baseline untuk perbandingan anomali humanoid.',
                    'abilities' => 'Kemampuan beradaptasi yang tinggi, penggunaan alat yang kompleks, pemikiran abstrak, dan struktur sosial yang rumit.',
                    'weaknesses' => 'Rentan terhadap penyakit, kerusakan fisik, faktor psikologis, dan memiliki masa hidup yang terbatas.',
                    'status' => 'ACTIVE',
                    'images' => [
                        'https://static.scientificamerican.com/dam/m/4aaa836e513fa8a5/original/krijn_neanderthal_face_reconstruction.jpg?m=1728652157.415&w=900',
                    ],
                ],
                [
                    'name' => 'The Sculpture',
                    'codename' => 'SCP-173',
                    'category' => 'SCP Anomaly',
                    'rank' => 'Euclid',
                    'origin' => 'Site-19 (SCP)',
                    'description' => 'Konstruksi dari beton dan rebar dengan jejak cat semprot merek Krylon. Bersifat hidup dan sangat memusuhi. Tidak dapat bergerak saat berada dalam garis pandang langsung. Personel yang ditugaskan ke dalam kontainmen diinstruksikan untuk menjaga kontak mata setiap saat.',
                    'abilities' => 'Kecepatan luar biasa saat tidak diamati, mampu mematahkan leher di pangkal tengkorak atau mencekik.',
                    'weaknesses' => 'Sepenuhnya tidak bergerak saat diamati secara langsung oleh organisme hidup.',
                    'status' => 'CONTAINED',
                    'images' => [
                        'https://static.wikia.nocookie.net/power-level-world/images/b/b9/SCP-173.jpg',
                    ],
                ],
                [
                    'name' => 'Cthulhu',
                    'codename' => 'The Great Dreamer',
                    'category' => 'Cthulhu Mythos',
                    'rank' => 'Great Old One',
                    'origin' => 'Cthulhu Mythos',
                    'description' => 'Entitas kosmik yang saat ini tertidur di kota R\'lyeh yang tenggelam di Pasifik Selatan. Digambarkan memiliki penampilan seperti gurita, naga, dan karikatur bentuk manusia. Pemujaannya tersebar di seluruh dunia, menunggu kebangkitannya.',
                    'abilities' => 'Kekuatan dan ukuran yang luar biasa, proyeksi telepati yang dapat menyebabkan kegilaan, keabadian semu.',
                    'weaknesses' => 'Saat ini dalam keadaan seperti kematian di R\'lyeh, meskipun dapat terbangun ketika "bintang-bintang berada di posisi yang benar".',
                    'status' => 'UNKNOWN',
                    'images' => [
                        'https://static.wikia.nocookie.net/villains/images/1/11/Cthulhu_Underwater.jpg',
                    ],
                ],
                [
                    'name' => 'Seraphim',
                    'codename' => 'The Burning Ones',
                    'category' => 'Angelic Hierarchy',
                    'rank' => 'Seraphim / Cherubim',
                    'origin' => 'Abrahamic Lore',
                    'description' => 'Malaikat tingkat tertinggi dalam hierarki surgawi. Digambarkan memiliki enam sayap: dua untuk menutupi wajah mereka, dua untuk menutupi kaki mereka, dan dua untuk terbang. Mereka terus-menerus mengelilingi takhta Tuhan.',
                    'abilities' => 'Api surgawi, pemurnian, pemahaman kosmik yang mendalam, kecepatan cahaya.',
                    'weaknesses' => 'Terikat pada kehendak ilahi; intervensi langsung di alam fana sangat dibatasi.',
                    'status' => 'ACTIVE',
                    'images' => [
                        'https://bonniewilks.com/wp-content/uploads/2011/10/burning.jpg',
                    ],
                ],

            // =================================================================
            // == DATA TAMBAHAN
            // =================================================================
            [
                'name' => 'Sasquatch',
                'codename' => 'Proyek Hominid Liar',
                'category' => 'Cryptid',
                'rank' => 'Class-C (Low Threat)',
                'origin' => 'Remote/Unexplored Region',
                'description' => 'Hominid besar berbulu yang dilaporkan menghuni hutan-hutan di Amerika Utara. Sangat sulit dipahami dan menghindari kontak dengan manusia. Bukti keberadaannya sebagian besar bersifat anekdotal atau jejak fisik yang diperdebatkan.',
                'abilities' => 'Kekuatan fisik yang luar biasa, kamuflase alami yang superior, mampu mengeluarkan vokalisasi frekuensi rendah yang menyebabkan disorientasi.',
                'weaknesses' => 'Menghindari pusat populasi manusia, diduga sensitif terhadap suara frekuensi tinggi.',
                'status' => 'ACTIVE',
                'images' => [
                    'https://npr.brightspotcdn.com/dims4/default/87b6d53/2147483647/strip/true/crop/565x348+0+3/resize/880x542!/quality/90/?url=http%3A%2F%2Fnpr-brightspot.s3.amazonaws.com%2Ff2%2F91%2F76551511422dbcd917680518a583%2Fscreenshot-2023-07-20-235828.png',
                    'https://static01.nyt.com/images/2024/04/12/multimedia/sasquatch1-wtlc/sasquatch1-wtlc-jumbo.jpg?quality=75&auto=webp',
                ],
            ],
            [
                'name' => 'Fenrir',
                'codename' => 'Wolf of Ragnarok',
                'category' => 'Mythological Being',
                'rank' => 'Titan',
                'origin' => 'Norse Mythology (Asgard/Yggdrasil)',
                'description' => 'Serigala raksasa, anak dari Loki dan Angrboda. Ditakdirkan membunuh Odin saat Ragnarok.',
                'abilities' => 'Kekuatan fisik luar biasa, gigi dapat menghancurkan baja, kecepatan tinggi.',
                'weaknesses' => 'Terikat pada takdir Ragnarok; hanya bisa dibatasi dengan rantai magis Gleipnir.',
                'status' => 'UNKNOWN',
                'images' => [
                    'https://static.wikia.nocookie.net/darkpictures/images/3/3c/F1c68876a9c2fc5b9c74bda67f114a39.jpg',
                ],
            ],
            [
                'name' => 'Anubis',
                'codename' => 'Lord of the Dead',
                'category' => 'Mythological Being',
                'rank' => 'God / Deity',
                'origin' => 'Egyptian Mythology (Duat)',
                'description' => 'Dewa Mesir dengan kepala serigala/jakal, penjaga dunia kematian dan pengadil jiwa.',
                'abilities' => 'Menguasai ritual kematian, pengadilan jiwa, perjalanan antara dunia hidup dan mati.',
                'weaknesses' => 'Terikat pada hukum kosmik Ma\'at.',
                'status' => 'ACTIVE',
                'images' => [
                    'https://www.chiddingstonecastle.org.uk/wp-content/uploads/2022/09/01.0379_1.jpeg',
                    'https://i.pinimg.com/736x/fa/35/92/fa3592eb0146b2e8772237083946a5da.jpg',
                    'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6d/Anubis_standing.svg/330px-Anubis_standing.svg.png',
                ],
            ],
            [
                'name' => 'Azazel',
                'codename' => 'The Scapegoat',
                'category' => 'Demonic Hierarchy',
                'rank' => 'Nine Lords (Demon)',
                'origin' => 'Abrahamic Lore',
                'description' => 'Entitas jatuh yang dihubungkan dengan dosa, pengajaran sihir, dan pengorbanan kambing hitam.',
                'abilities' => 'Menggoda manusia, kekuatan sihir kuno, manipulasi pikiran.',
                'weaknesses' => 'Terikat dalam gurun; kekuatan berkurang tanpa pengorbanan.',
                'status' => 'CONTAINED',
                'images' => [
                    'https://upload.wikimedia.org/wikipedia/commons/thumb/d/de/Azazel.jpg/330px-Azazel.jpg',
                    'https://static.wikia.nocookie.net/the-stars-in-heaven/images/d/de/Azazel.jpg',
                ],
            ],
            [
                'name' => 'Nyarlathotep',
                'codename' => 'The Crawling Chaos',
                'category' => 'Cthulhu Mythos',
                'rank' => 'Outer God',
                'origin' => 'Cthulhu Mythos',
                'description' => 'Utusan para Outer God, dikenal memiliki seribu wujud. Manipulator dan pengacau di dunia manusia.',
                'abilities' => 'Bentuk berubah-ubah, manipulasi psikis, komunikasi antar dimensi.',
                'weaknesses' => 'Tidak sepenuhnya terikat pada satu realitas.',
                'status' => 'ACTIVE',
                'images' => [
                    'https://2d4chan.org/mediawiki/thumb.php?f=Nyarlathotep_erkanerturk.jpg&width=300',
                    'https://cdnb.artstation.com/p/assets/images/images/072/917/519/large/pascal-quidault-bragelonne-lovecraft-nyarlathotep-pharaon-noir-final.jpg',
                ],
            ],
            [
                'name' => 'Metatron',
                'codename' => 'The Voice of God',
                'category' => 'Angelic Hierarchy',
                'rank' => 'Archangel',
                'origin' => 'Abrahamic Lore',
                'description' => 'Malaikat yang berfungsi sebagai juru tulis surgawi dan penyampai kehendak Tuhan.',
                'abilities' => 'Komunikasi kosmik, rekaman seluruh tindakan manusia, energi cahaya surgawi.',
                'weaknesses' => 'Tidak dapat melanggar kehendak ilahi.',
                'status' => 'ACTIVE',
                'images' => [
                    'https://static.wikia.nocookie.net/angelology/images/8/84/Metatron.jpg',
                    'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/MetatronInIslamicArts.jpg/250px-MetatronInIslamicArts.jpg',
                ],
            ],
            [
                'name' => 'SCP-682',
                'codename' => 'Hard-to-Destroy Reptile',
                'category' => 'SCP Anomaly',
                'rank' => 'Keter',
                'origin' => 'Site-19 (SCP)',
                'description' => 'Reptil besar yang sangat cerdas dan penuh kebencian terhadap kehidupan. Hampir mustahil dihancurkan.',
                'abilities' => 'Regenerasi cepat, adaptasi terhadap hampir semua bentuk serangan, kekuatan fisik luar biasa.',
                'weaknesses' => 'Membutuhkan energi besar untuk regenerasi total.',
                'status' => 'CONTAINED',
                'images' => [
                    'https://static.wikia.nocookie.net/scp/images/0/0d/SCP-682.png',
                ],
            ],
            [
                'name' => 'Lilith',
                'codename' => 'The First Rebel',
                'category' => 'Demonic Hierarchy',
                'rank' => 'Archdevil',
                'origin' => 'Abrahamic Lore',
                'description' => 'Dalam tradisi Yahudi, Lilith adalah istri pertama Adam yang menolak tunduk dan melarikan diri.',
                'abilities' => 'Pesona, manipulasi mimpi, kekuatan malam, penciptaan iblis keturunan.',
                'weaknesses' => 'Nama suci dan simbol pelindung tertentu.',
                'status' => 'UNKNOWN',
                'images' => [
                    'https://upload.wikimedia.org/wikipedia/commons/5/5d/Lilith_%28John_Collier%29.jpg',
                ],
            ],
            [
                'name' => 'Yamata no Orochi',
                'codename' => 'Eight-Headed Dragon',
                'category' => 'Mythological Being',
                'rank' => 'Titan',
                'origin' => 'Japanese Folklore (Yomi)',
                'description' => 'Naga berkepala delapan dari mitologi Jepang, dikalahkan oleh dewa Susanoo.',
                'abilities' => 'Delapan kepala dengan nafas beracun, tubuh raksasa seukuran delapan lembah.',
                'weaknesses' => 'Minuman keras suci yang melemahkan; dipenggal oleh Susanoo.',
                'status' => 'UNKNOWN',
                'images' => [
                    'https://upload.wikimedia.org/wikipedia/commons/8/8e/Yamata_no_Orochi.jpg',
                ],
            ],
            [
                'name' => 'Prometheus',
                'codename' => 'The Fire Bringer',
                'category' => 'Mythological Being',
                'rank' => 'Titan',
                'origin' => 'Greek Mythology (Olympus/Underworld)',
                'description' => 'Titan pemberi api kepada manusia, dihukum oleh Zeus untuk dirantai di gunung.',
                'abilities' => 'Kecerdasan tinggi, pengetahuan kosmik, membawa api ilahi.',
                'weaknesses' => 'Dihukum dirantai, disiksa setiap hari oleh elang Zeus.',
                'status' => 'UNKNOWN',
                'images' => [
                    'https://upload.wikimedia.org/wikipedia/commons/f/f6/Prometheus_Bound_Louvre_Ma711.jpg',
                ],
            ],
        ];
        // Looping untuk membuat entitas dan gambarnya
        foreach ($entitiesData as $data) {
            $imageData = $data['images'];
            unset($data['images']); // Hapus data gambar sebelum membuat entitas

            $entity = Entity::create($data);

            if (!empty($imageData)) {
                foreach ($imageData as $imageUrl) {
                    // Karena ini seeder, kita simpan URL lengkapnya langsung
                    $entity->images()->create([
                        'path' => $imageUrl,
                        'caption' => 'Arsip Visual Awal',
                    ]);
                }
            }
        }
    }
}
