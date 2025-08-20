<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class EntityImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks untuk truncate
        Schema::disableForeignKeyConstraints();
        DB::table('entity_images')->truncate();
        Schema::enableForeignKeyConstraints();

        $entityImages = [
            // Record 1-4 (Data Awal)
            [
                'entity_id' => 1,
                'path' => 'https://static.scientificamerican.com/dam/m/4aaa836e513fa8a5/original/krijn_neanderthal_face_reconstruction.jpg?m=1728652157.415&w=900',
                'caption' => 'Arsip Visual Awal',
            ],
            [
                'entity_id' => 2,
                'path' => 'https://static.wikia.nocookie.net/power-level-world/images/b/b9/SCP-173.jpg',
                'caption' => 'Arsip Visual Awal',
            ],
            [
                'entity_id' => 3,
                'path' => 'https://static.wikia.nocookie.net/villains/images/1/11/Cthulhu_Underwater.jpg',
                'caption' => 'Arsip Visual Awal',
            ],
            [
                'entity_id' => 4,
                'path' => 'https://bonniewilks.com/wp-content/uploads/2011/10/burning.jpg',
                'caption' => 'Arsip Visual Awal',
            ],
            
            // Record 5-6 (Sasquatch)
            [
                'entity_id' => 5,
                'path' => 'https://npr.brightspotcdn.com/dims4/default/87b6d53/2147483647/strip/true/crop/565x348+0+3/resize/880x542!/quality/90/?url=http%3A%2F%2Fnpr-brightspot.s3.amazonaws.com%2Ff2%2F91%2F76551511422dbcd917680518a583%2Fscreenshot-2023-07-20-235828.png',
                'caption' => 'Arsip Visual Awal',
            ],
            [
                'entity_id' => 5,
                'path' => 'https://static01.nyt.com/images/2024/04/12/multimedia/sasquatch1-wtlc/sasquatch1-wtlc-jumbo.jpg?quality=75&auto=webp',
                'caption' => 'Arsip Visual Awal',
            ],
            
            // Record 7 (Fenrir)
            [
                'entity_id' => 6,
                'path' => 'https://static.wikia.nocookie.net/darkpictures/images/3/3c/F1c68876a9c2fc5b9c74bda67f114a39.jpg',
                'caption' => 'Arsip Visual Awal',
            ],
            
            // Record 8-10 (Anubis)
            [
                'entity_id' => 7,
                'path' => 'https://www.chiddingstonecastle.org.uk/wp-content/uploads/2022/09/01.0379_1.jpeg',
                'caption' => 'Arsip Visual Awal',
            ],
            [
                'entity_id' => 7,
                'path' => 'https://i.pinimg.com/736x/fa/35/92/fa3592eb0146b2e8772237083946a5da.jpg',
                'caption' => 'Arsip Visual Awal',
            ],
            [
                'entity_id' => 7,
                'path' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6d/Anubis_standing.svg/330px-Anubis_standing.svg.png',
                'caption' => 'Arsip Visual Awal',
            ],
            
            // Record 11, 21 (Azazel)
            [
                'entity_id' => 8,
                'path' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/de/Azazel.jpg/330px-Azazel.jpg',
                'caption' => 'Arsip Visual Awal',
            ],
            [
                'entity_id' => 8,
                'path' => 'https://static.wikia.nocookie.net/the-stars-in-heaven/images/d/de/Azazel.jpg',
                'caption' => 'Linked Evidence',
            ],
            
            // Record 13-14 (Nyarlathotep)
            [
                'entity_id' => 9,
                'path' => 'https://2d4chan.org/mediawiki/thumb.php?f=Nyarlathotep_erkanerturk.jpg&width=300',
                'caption' => 'Arsip Visual Awal',
            ],
            [
                'entity_id' => 9,
                'path' => 'https://cdnb.artstation.com/p/assets/images/images/072/917/519/large/pascal-quidault-bragelonne-lovecraft-nyarlathotep-pharaon-noir-final.jpg',
                'caption' => 'Arsip Visual Awal',
            ],
            
            // Record 15-16 (Metatron)
            [
                'entity_id' => 10,
                'path' => 'https://static.wikia.nocookie.net/angelology/images/8/84/Metatron.jpg',
                'caption' => 'Arsip Visual Awal',
            ],
            [
                'entity_id' => 10,
                'path' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/MetatronInIslamicArts.jpg/250px-MetatronInIslamicArts.jpg',
                'caption' => 'Arsip Visual Awal',
            ],
            
            // Record 29-30 (SCP-682)
            [
                'entity_id' => 11,
                'path' => 'https://preview.redd.it/be-honest-currently-do-you-think-a-rewrite-of-the-scp-682-v0-mqjpys27c4kd1.jpeg?width=320&crop=smart&auto=webp&s=0e8faa1cdb7e4d53a975c600e694bb388ef16c12',
                'caption' => 'Linked Evidence',
            ],
            [
                'entity_id' => 11,
                'path' => 'https://media.sketchfab.com/models/ec7d0dcffc18400ea24b985159a171ef/thumbnails/0a9ede3abe214031938e250519b1f591/95632bd4342545ef853aba6137802c57.jpeg',
                'caption' => 'Linked Evidence',
            ],
            
            // Record 31-35 (Lilith)
            [
                'entity_id' => 12,
                'path' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/19/Burney_Relief_Babylon_-1800-1750.JPG/500px-Burney_Relief_Babylon_-1800-1750.JPG',
                'caption' => 'Linked Evidence',
            ],
            [
                'entity_id' => 12,
                'path' => 'https://www.myjewishlearning.com/wp-content/uploads/2008/10/Lady-Lilith-1024x575.jpg',
                'caption' => 'Linked Evidence',
            ],
            [
                'entity_id' => 12,
                'path' => 'https://static.wikia.nocookie.net/villains/images/3/31/Lilith.jpg',
                'caption' => 'Linked Evidence',
            ],
            [
                'entity_id' => 12,
                'path' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b6/Lilith_%28John_Collier_painting%29.jpg/330px-Lilith_%28John_Collier_painting%29.jpg',
                'caption' => 'Linked Evidence',
            ],
            [
                'entity_id' => 12,
                'path' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b6/Lilith_%28John_Collier_painting%29.jpg/330px-Lilith_%28John_Collier_painting%29.jpg',
                'caption' => 'Arsip Visual Awal',
            ],
            
            // Record 41-45 (Yamata no Orochi)
            [
                'entity_id' => 13,
                'path' => 'https://static.wikia.nocookie.net/mythology/images/e/e5/YAMATA_NO_OROCHI.png',
                'caption' => 'Linked Evidence',
            ],
            [
                'entity_id' => 13,
                'path' => 'https://cdn.shopify.com/s/files/1/0266/2403/2817/files/yamata_no_orochi_par_Maukamauka_1024x1024.webp?v=1693133573',
                'caption' => 'Linked Evidence',
            ],
            [
                'entity_id' => 13,
                'path' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c8/Susano-o_no_Mikoto_Killing_the_Eight-headed_Dragon.jpg/330px-Susano-o_no_Mikoto_Killing_the_Eight-headed_Dragon.jpg',
                'caption' => 'Linked Evidence',
            ],
            [
                'entity_id' => 13,
                'path' => 'https://preview.redd.it/titanus-yamata-no-orochi-v0-q5ajq6169wvc1.jpg?width=1080&crop=smart&auto=webp&s=ade89a34ea3f44cb9960b99b3d9803fd43441214',
                'caption' => 'Linked Evidence',
            ],
            [
                'entity_id' => 13,
                'path' => 'https://static.wikia.nocookie.net/mythology/images/7/71/Okamiden-orochi-artwork.png',
                'caption' => 'Linked Evidence',
            ],
            // Record 36-40 (Prometheus)
            [
                'entity_id' => 14,
                'path' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/ef/Jan_Cossiers_-_Prometheus_Carrying_Fire.jpg/800px-Jan_Cossiers_-_Prometheus_Carrying_Fire.jpg',
                'caption' => 'Linked Evidence',
            ],
            [
                'entity_id' => 14,
                'path' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/69/La_tortura_de_Prometeo%2C_por_Salvator_Rosa.jpg/250px-La_tortura_de_Prometeo%2C_por_Salvator_Rosa.jpg',
                'caption' => 'Linked Evidence',
            ],
            [
                'entity_id' => 14,
                'path' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6a/Der_gefesselte_Prometheus_von_Jacob_Jordaens.jpg/800px-Der_gefesselte_Prometheus_von_Jacob_Jordaens.jpg',
                'caption' => 'Linked Evidence',
            ],
            [
                'entity_id' => 14,
                'path' => 'https://static.wikia.nocookie.net/greek-myth933/images/6/63/Prometheus.png',
                'caption' => 'Linked Evidence',
            ],
            
            // Record 24-27, 46-49 (Reptilian dan Seraphim tambahan)
            [
                'entity_id' => 15,
                'path' => 'https://i.pinimg.com/736x/5c/de/f6/5cdef65d30ad6476c466dc33d47d2d91.jpg',
                'caption' => 'Linked Evidence',
            ],
            [
                'entity_id' => 15,
                'path' => 'https://cinereverso.org/wp-content/uploads/2021/03/Los-reptilianos-han-sido-popularizados-en-filmes-y-serie-de-televisi%C3%B3n.jpg',
                'caption' => 'Linked Evidence',
            ],
            [
                'entity_id' => 15,
                'path' => 'https://preview.redd.it/sorry-guys-hes-a-reptilian-v0-h051s6uakcme1.jpeg?width=1080&crop=smart&auto=webp&s=6607b9e61c8117bf037023f5e940f524c7bb9461',
                'caption' => 'Linked Evidence',
            ],
            [
                'entity_id' => 15,
                'path' => 'https://journalnews.com.ph/wp-content/uploads/2022/08/Reptilian-768x512.jpg',
                'caption' => 'Linked Evidence',
            ],
            
            // Record 46-49 (Seraphim tambahan)
            [
                'entity_id' => 4,
                'path' => 'https://miro.medium.com/v2/resize:fit:720/format:webp/1*Da6xj2FnBYu_B4aCkmdN2Q.jpeg',
                'caption' => 'Linked Evidence',
            ],
            [
                'entity_id' => 4,
                'path' => 'https://upload.wikimedia.org/wikipedia/commons/1/1f/Seraphim_-_Petites_Heures_de_Jean_de_Berry.jpg',
                'caption' => 'Linked Evidence',
            ],
            [
                'entity_id' => 4,
                'path' => 'https://static.wikia.nocookie.net/the-stars-in-heaven/images/c/c4/Seraphim1-2022-K2-1200PX_918x.jpg',
                'caption' => 'Linked Evidence',
            ],
        ];

        foreach ($entityImages as $image) {
            DB::table('entity_images')->insert([
                'entity_id' => $image['entity_id'],
                'path' => $image['path'],
                'caption' => $image['caption'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}