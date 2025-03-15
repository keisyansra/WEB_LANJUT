<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=[
            ['supplier_kode' => 'SNB', 'supplier_nama' => 'SINAR BAKTI', 'supplier_alamat' => 'Jakarta Barat'],
            ['supplier_kode' => 'WJY', 'supplier_nama' => 'WIJAYA ATK', 'supplier_alamat' => 'Balikpapan'],
            ['supplier_kode' => 'KLG', 'supplier_nama' => 'KELAPA GADING', 'supplier_alamat' => 'Surabaya'],
        ];
        DB::table('m_supplier')->insert($data);
    }
}
