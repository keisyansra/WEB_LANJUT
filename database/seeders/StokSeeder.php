<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StokSeeder extends Seeder
{
    public function run(): void
    {
        $data=[
            ['stok_id' => 1, 'barang_id' => 1, 'supplier_id' => 3, 'user_id' => 3, 'stok_tanggal' => '2025-02-01', 'stok_jumlah' => 15],
            ['stok_id' => 2, 'barang_id' => 2, 'supplier_id' => 3, 'user_id' => 3, 'stok_tanggal' => '2025-02-02', 'stok_jumlah' => 20],
            ['stok_id' => 3, 'barang_id' => 3, 'supplier_id' => 3, 'user_id' => 3, 'stok_tanggal' => '2025-02-03', 'stok_jumlah' => 15],   
            ['stok_id' => 4, 'barang_id' => 4, 'supplier_id' => 3, 'user_id' => 3, 'stok_tanggal' => '2025-02-04', 'stok_jumlah' => 55],
            ['stok_id' => 5, 'barang_id' => 5, 'supplier_id' => 3, 'user_id' => 3, 'stok_tanggal' => '2025-02-05', 'stok_jumlah' => 14],
            ['stok_id' => 6, 'barang_id' => 6, 'supplier_id' => 1, 'user_id' => 3, 'stok_tanggal' => '2025-02-06', 'stok_jumlah' => 48],
            ['stok_id' => 7, 'barang_id' => 7, 'supplier_id' => 1, 'user_id' => 3, 'stok_tanggal' => '2025-02-07', 'stok_jumlah' => 11],
            ['stok_id' => 8, 'barang_id' => 8, 'supplier_id' => 1, 'user_id' => 3, 'stok_tanggal' => '2025-02-08', 'stok_jumlah' => 45],
            ['stok_id' => 9, 'barang_id' => 9, 'supplier_id' => 1, 'user_id' => 3, 'stok_tanggal' => '2025-02-09', 'stok_jumlah' => 3],
            ['stok_id' => 10, 'barang_id' => 10, 'supplier_id' => 1, 'user_id' => 3, 'stok_tanggal' => '2025-02-10', 'stok_jumlah' => 41],
            ['stok_id' => 11, 'barang_id' => 11, 'supplier_id' => 2, 'user_id' => 3, 'stok_tanggal' => '2025-02-11', 'stok_jumlah' => 19],
            ['stok_id' => 12, 'barang_id' => 12, 'supplier_id' => 2, 'user_id' => 3, 'stok_tanggal' => '2025-02-12', 'stok_jumlah' => 29],
            ['stok_id' => 13, 'barang_id' => 13, 'supplier_id' => 2, 'user_id' => 3, 'stok_tanggal' => '2025-02-13', 'stok_jumlah' => 17],
            ['stok_id' => 14, 'barang_id' => 14, 'supplier_id' => 2, 'user_id' => 3, 'stok_tanggal' => '2025-02-14', 'stok_jumlah' => 20],
            ['stok_id' => 15, 'barang_id' => 15, 'supplier_id' => 2, 'user_id' => 3, 'stok_tanggal' => '2025-02-15', 'stok_jumlah' => 23],

        ];
        DB::table('m_stok')->insert($data);
    }
}
