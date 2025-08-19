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
