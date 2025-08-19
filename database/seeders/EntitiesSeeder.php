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
                'origin' => 'Earth (Unspecified)',
                'description' => 'Spesies dominan di planet Bumi. Menunjukkan berbagai tingkat kecerdasan, agresi, dan empati. Fondasi dari sebagian besar masyarakat dan konflik global. Dianggap sebagai baseline untuk perbandingan anomali humanoid.',
                'abilities' => 'Kemampuan beradaptasi yang tinggi, penggunaan alat yang kompleks, pemikiran abstrak, dan struktur sosial yang rumit.',
                'weaknesses' => 'Rentan terhadap penyakit, kerusakan fisik, faktor psikologis, dan memiliki masa hidup yang terbatas.',
                'status' => 'ACTIVE',
                'images' => [
                    'https://placehold.co/600x400/161b22/8b949e?text=SUBJECT-BASELINE',
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
                    'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8a/SCP-173.jpg/440px-SCP-173.jpg',
                ],
            ],
            [
                'name' => 'Cthulhu',
                'codename' => 'The Great Dreamer',
                'category' => 'Cthulhu Mythos',
                'rank' => 'Great Old One',
                'origin' => 'R\'lyeh (Cthulhu Mythos)',
                'description' => 'Entitas kosmik yang saat ini tertidur di kota R\'lyeh yang tenggelam di Pasifik Selatan. Digambarkan memiliki penampilan seperti gurita, naga, dan karikatur bentuk manusia. Pemujaannya tersebar di seluruh dunia, menunggu kebangkitannya.',
                'abilities' => 'Kekuatan dan ukuran yang luar biasa, proyeksi telepati yang dapat menyebabkan kegilaan, keabadian semu.',
                'weaknesses' => 'Saat ini dalam keadaan seperti kematian di R\'lyeh, meskipun dapat terbangun ketika "bintang-bintang berada di posisi yang benar".',
                'status' => 'UNKNOWN',
                'images' => [
                    'https://upload.wikimedia.org/wikipedia/commons/thumb/6/69/Cthulhu_and_R%27lyeh.png/560px-Cthulhu_and_R%27lyeh.png',
                    'https://placehold.co/600x400/161b22/2ea043?text=R\'LYEH+GLYPH',
                ],
            ],
            [
                'name' => 'Seraphim',
                'codename' => 'The Burning Ones',
                'category' => 'Angelic Hierarchy',
                'rank' => 'Seraphim / Cherubim',
                'origin' => 'Abrahamic Lore (Heaven/Hell)',
                'description' => 'Malaikat tingkat tertinggi dalam hierarki surgawi. Digambarkan memiliki enam sayap: dua untuk menutupi wajah mereka, dua untuk menutupi kaki mereka, dan dua untuk terbang. Mereka terus-menerus mengelilingi takhta Tuhan.',
                'abilities' => 'Api surgawi, pemurnian, pemahaman kosmik yang mendalam, kecepatan cahaya.',
                'weaknesses' => 'Terikat pada kehendak ilahi; intervensi langsung di alam fana sangat dibatasi.',
                'status' => 'ACTIVE',
                'images' => [
                    'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c6/BLW_Seraph.jpg/500px-BLW_Seraph.jpg',
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
