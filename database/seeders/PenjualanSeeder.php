<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        $data=[
            ['penjualan_id' => 1, 'user_id' => 3, 'pembeli' => 'Mingyu', 'penjualan_kode' => 'SOLD001', 'penjualan_tanggal' => '2025-03-01'],
            ['penjualan_id' => 2, 'user_id' => 3, 'pembeli' => 'Sugeng', 'penjualan_kode' => 'SOLD002', 'penjualan_tanggal' => '2025-03-02'],
            ['penjualan_id' => 3, 'user_id' => 3, 'pembeli' => 'Rahayu', 'penjualan_kode' => 'SOLD003', 'penjualan_tanggal' => '2025-03-03'],
            ['penjualan_id' => 4, 'user_id' => 3, 'pembeli' => 'Choisan', 'penjualan_kode' => 'SOLD004', 'penjualan_tanggal' => '2025-03-04'],
            ['penjualan_id' => 5, 'user_id' => 3, 'pembeli' => 'Delvin', 'penjualan_kode' => 'SOLD005', 'penjualan_tanggal' => '2025-03-05'],
            ['penjualan_id' => 6, 'user_id' => 3, 'pembeli' => 'Choisan', 'penjualan_kode' => 'SOLD006', 'penjualan_tanggal' => '2025-03-06'],
            ['penjualan_id' => 7, 'user_id' => 3, 'pembeli' => 'Minchae', 'penjualan_kode' => 'SOLD007', 'penjualan_tanggal' => '2025-03-07'],
            ['penjualan_id' => 8, 'user_id' => 3, 'pembeli' => 'Joohyuk', 'penjualan_kode' => 'SOLD008', 'penjualan_tanggal' => '2025-03-08'],
            ['penjualan_id' => 9, 'user_id' => 3, 'pembeli' => 'Choiung', 'penjualan_kode' => 'SOLD009', 'penjualan_tanggal' => '2025-03-09'],
            ['penjualan_id' => 10, 'user_id' => 3, 'pembeli' => 'Hyunwook', 'penjualan_kode' => 'SOLD010', 'penjualan_tanggal' => '2025-03-10'],
        ];
        DB::table('m_penjualan')->insert($data);
    }
}
