<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data=[
        //Kategori 1
        ['barang_id' => '1', 'kategori_id' => '1', 'barang_kode' => 'ELK001', 'barang_nama' => 'Rexus Keyboard', 'harga_beli' => 560000, 'harga_jual' => 850000],
        ['barang_id' => '2', 'kategori_id' => '1', 'barang_kode' => 'ELK002', 'barang_nama' => 'Aula Keyboard', 'harga_beli' => 620000, 'harga_jual' => 835000],
        ['barang_id' => '3', 'kategori_id' => '1', 'barang_kode' => 'ELK003', 'barang_nama' => 'Gamen Keyboard', 'harga_beli' => 154000, 'harga_jual' => 299000],
        ['barang_id' => '4', 'kategori_id' => '1', 'barang_kode' => 'ELK004', 'barang_nama' => 'Mouse Logitech', 'harga_beli' => 200000, 'harga_jual' => 250000],
        ['barang_id' => '5', 'kategori_id' => '1', 'barang_kode' => 'ELK005', 'barang_nama' => 'Printer Epson', 'harga_beli' => 2500000, 'harga_jual' => 27500000],

        // Kategori 2
        ['barang_id' => '6', 'kategori_id' => '2', 'barang_kode' => 'MKN001', 'barang_nama' => 'Roti Gandum', 'harga_beli' => 10000, 'harga_jual' => 18000],
        ['barang_id' => '7', 'kategori_id' => '2', 'barang_kode' => 'MKN002', 'barang_nama' => 'Cereal Mini', 'harga_beli' => 5000, 'harga_jual' => 7500],
        ['barang_id' => '8', 'kategori_id' => '2', 'barang_kode' => 'MKN003', 'barang_nama' => 'Coklat Batang', 'harga_beli' => 7000, 'harga_jual' => 12000],
        ['barang_id' => '9', 'kategori_id' => '2', 'barang_kode' => 'MKN004', 'barang_nama' => 'Keju', 'harga_beli' => 15000, 'harga_jual' => 17000],
        ['barang_id' => '10', 'kategori_id' => '2', 'barang_kode' => 'MKN005', 'barang_nama' => 'Minyak Goreng', 'harga_beli' => 30000, 'harga_jual' => 35000],

        //Kategori 3
        ['barang_id' => '11', 'kategori_id' => '3', 'barang_kode' => 'MNM001', 'barang_nama' => 'Susu UHT', 'harga_beli' => 5000, 'harga_jual' => 7500],
        ['barang_id' => '12', 'kategori_id' => '3', 'barang_kode' => 'MNM002', 'barang_nama' => 'Teh Botol', 'harga_beli' => 3500, 'harga_jual' => 6000],
        ['barang_id' => '13', 'kategori_id' => '3', 'barang_kode' => 'MNM003', 'barang_nama' => 'Air Mineral', 'harga_beli' => 1700, 'harga_jual' => 3000],
        ['barang_id' => '14', 'kategori_id' => '3', 'barang_kode' => 'MNM004', 'barang_nama' => 'Cocomelon', 'harga_beli' => 2700, 'harga_jual' => 5000],
        ['barang_id' => '15', 'kategori_id' => '3', 'barang_kode' => 'MNM005', 'barang_nama' => 'Pocari Sweat', 'harga_beli' => 3400, 'harga_jual' => 6500],
        ];

        DB::table('m_barang')->insert($data);
    }
}
