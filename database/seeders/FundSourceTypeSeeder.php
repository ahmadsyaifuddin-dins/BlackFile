<?php

namespace Database\Seeders;

use App\Models\FundSourceType;
use Illuminate\Database\Seeder;

class FundSourceTypeSeeder extends Seeder
{
    /**
     * Daftar master sumber dana yang umum dipakai di Indonesia.
     * Idempotent: aman dijalankan berulang.
     */
    public function run(): void
    {
        $types = [
            ['name' => 'Uang Tunai', 'description' => 'Uang fisik di tangan'],
            ['name' => 'Dompet', 'description' => 'Dompet / kantong harian'],
            ['name' => 'Kotak Uang', 'description' => 'Kotak / celengan uang'],
            ['name' => 'Rekening Bank', 'description' => 'Rekening bank umum'],
            ['name' => 'Seabank', 'description' => 'Rekening SeaBank'],
            ['name' => 'BCA', 'description' => 'Rekening Bank BCA'],
            ['name' => 'Mandiri', 'description' => 'Rekening Bank Mandiri'],
            ['name' => 'BRI', 'description' => 'Rekening Bank BRI'],
            ['name' => 'BNI', 'description' => 'Rekening Bank BNI'],
            ['name' => 'Dana', 'description' => 'E-wallet DANA'],
            ['name' => 'OVO', 'description' => 'E-wallet OVO'],
            ['name' => 'GoPay', 'description' => 'E-wallet GoPay'],
            ['name' => 'ShopeePay', 'description' => 'E-wallet ShopeePay'],
            ['name' => 'LinkAja', 'description' => 'E-wallet LinkAja'],
            ['name' => 'Jago', 'description' => 'Rekening Bank Jago'],
            ['name' => 'Jenius', 'description' => 'Rekening Jenius (BTPN)'],
            ['name' => 'Emas', 'description' => 'Tabungan emas / logam mulia'],
            ['name' => 'Investasi', 'description' => 'Reksa dana, saham, deposito'],
        ];

        foreach ($types as $index => $type) {
            FundSourceType::updateOrCreate(
                ['name' => $type['name']],
                [
                    'description' => $type['description'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );
        }
    }
}
