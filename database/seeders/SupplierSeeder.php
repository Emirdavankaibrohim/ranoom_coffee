<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $suppliers = [
            [
                'name'    => 'PT. Kopi Nusantara',
                'contact' => '08111222333',
                'address' => 'Jl. Kebun Kopi No. 10, Bandung',
                'status'  => 'Active',
            ],
            [
                'name'    => 'Cv. Susu Segar Jaya',
                'contact' => '08111444555',
                'address' => 'Jl. Peternakan No. 5, Lembang',
                'status'  => 'Active',
            ],
            [
                'name'    => 'UD. Gula Manis',
                'contact' => '08111666777',
                'address' => 'Jl. Pasar Besar No. 88, Surabaya',
                'status'  => 'Active',
            ],
            [
                'name'    => 'Imported Beans Co.',
                'contact' => '021-5556667',
                'address' => 'Sudirman Central Business District, Jakarta',
                'status'  => 'Active',
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::firstOrCreate(
                ['name' => $supplier['name']],
                $supplier
            );
        }
    }
}
