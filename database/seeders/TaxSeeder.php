<?php

namespace Database\Seeders;

use App\Models\TaxSetting;
use Illuminate\Database\Seeder;

class TaxSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TaxSetting::updateOrCreate(
            ['tax_name' => 'VAT'],
            [
                'tax_rate' => 11, // 11% VAT
            ]
        );
        
        TaxSetting::updateOrCreate(
             ['tax_name' => 'Service Charge'],
             [
                 'tax_rate' => 5, // 5% Service
             ]
         );
    }
}
