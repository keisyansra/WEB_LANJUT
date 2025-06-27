<?php

namespace App\Http\Controllers;

use App\Models\KategoriModel;
use App\Models\BarangModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Monolog\Level;
use PhpParser\Node\Expr\Cast\Object_;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
//use App\Http\Controllers\Pdf;
use Barryvdh\DomPDF\Facade\Pdf;




class BarangController extends Controller {
    //menampilkan halaman awal barang
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Daftar Barang',
            'list' => ['Home', 'Barang']
        ];

        $page = (object) [
            'title' => 'Daftar barang yang terdaftar dalam sistem'
        ];

        $activeMenu = 'barang'; //set menu yang sedang aktif
        $kategori = KategoriModel::all(); // ambil data level untuk fitur level
        return view('barang.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'kategori' => $kategori, 'activeMenu' => $activeMenu]);
    }

    // Ambil data barang dalam bentuk json untuk datatables
    public function list(Request $request) {
        $barang = BarangModel::select('barang_id', 'kategori_id', 'barang_kode', 'barang_nama', 'harga_beli', 'harga_jual')->with('kategori');

        // Filter data barang berdasarkan level_id
        if ($request->kategori_id) {
            $barang->where('kategori_id',$request->kategori_id);
        }

        return DataTables::of($barang)
        ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom:DT_RowIndex)
        ->addColumn('aksi', function ($barang) { // menambahkan kolom aksi
            $btn = '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id .
            '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';

            $btn .= '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id .
            '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';

            $btn .= '<button onclick="modalAction(\''.url('/barang/' . $barang->barang_id .
            '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';

            return $btn; 
        })
        ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
        ->make(true);
    }
    
    // Menampilkan halaman form tambah barang
    public function create() {
        $breadcrumb = (object) [
            'title' => 'Tambah Barang',
            'list' => ['Home', 'Barang', 'Tambah'] 
        ];

        $page = (object) [
            'title' => 'Tambah barang baru'
        ];

        $kategori = KategoriModel::all(); // ambil data level untuk ditampilkan di form
        $activeMenu = 'barang'; // set menu yang sedang aktif
        return view('barang.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan data barang baru
    public function store(Request $request) {
        $request->validate([
            'kategori_id' =>'required|integer',
            'barang_kode' =>'required|string|max:50|unique:m_barang,barang_kode',
            'barang_nama' =>'required|string|max:100',
            'harga_beli' =>'required|numeric',
            'harga_jual' =>'required|numeric',
        ]);

        BarangModel::create([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil disimpan');
    }

    // Menampilkan detail barang
    public function show(string $id) {
        $barang = BarangModel::with('kategori')->find($id);

        $breadcrumb = (object) [
            'title' => 'Detail Barang',
            'list' => ['Home', 'Barang', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail barang'
        ];

        $activeMenu = 'barang'; // set menu yang sedang aktif

        return view('barang.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'barang' => $barang,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menampilkan halaman form edit barang
    public function edit(string $id) {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::all();

        $breadcrumb = (object) [
            'title' => 'Edit Barang',
            'list' => ['Home', 'Barang', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit barang'
        ];

        $activeMenu = 'barang'; // set menu yang sedang aktif
        return view('barang.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'barang' => $barang,
            'kategori' => $kategori,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan perubahan data barang
    public function update(Request $request, string $id) {
        $request->validate([
            'kategori_id' =>'required|integer',
            'barang_kode' =>'required|string|max:50|unique:m_barang,barang_kode,' .$id. ',barang_id',
            'barang_nama' =>'required|string|max:100',
            'harga_beli' =>'required|numeric',
            'harga_jual' =>'required|numeric',
        ]);

        BarangModel::find($id)->update([
            'kategori_id' => $request->kategori_id,
            'barang_kode' => $request->barang_kode,
            'barang_nama' => $request->barang_nama,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
        ]);

        return redirect('/barang')->with('success', 'Data barang berhasil diubah');
    }

    // Menghapus data barang
    public function destroy(string $id) {
        $check = BarangModel::find($id);
        if (!$check) { // untuk mengecek apakah data barang dengan id yang dimaksud ada atau tidak
            return redirect('/barang')->with('error', 'Data barang tidak ditemukan');
        }

        try {
            BarangModel::destroy($id); // Hapus data barang

            return redirect('/barang')->with('success', 'Data barang berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {

            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/barang')->with('error', 'Data barang gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax() {
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode')->get();
        return view('barang.create_ajax') ->with('kategori', $kategori);
    }

    public function store_ajax(Request $request) {
        // cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' =>'required|integer',
                'barang_kode' =>'required|string|max:50|unique:m_barang,barang_kode',
                'barang_nama' =>'required|string|max:100',
                'harga_beli' =>'required|numeric',
                'harga_jual' =>'required|numeric'
            ];
        
            $validator = Validator::make($request->all(), $rules);
        
            if($validator->fails()){
                return response()->json([
                    'status'  => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField'=> $validator->errors(), // pesan error validasi
                ]);
            }
        
            BarangModel::create($request->all());
            return response()->json([
                'status'  => true,
                'message' => 'Data barang berhasil disimpan'
            ]);
        }
        redirect('/');
    }  
        
    //menampilkan halaman form edit barang ajax
    public function edit_ajax(string $id) {
        $barang = BarangModel::find($id);
        $kategori = KategoriModel::select('kategori_id', 'kategori_kode')->get();
        return view('barang.edit_ajax', ['barang' => $barang, 'kategori'=> $kategori]);
    }

    public function update_ajax(Request $request, $id) {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'kategori_id' =>'required|integer',
                'barang_kode' => 'required|string|max:50|unique:m_barang,barang_kode,' . $id . ',barang_id',
                'barang_nama' =>'required|string|max:100',
                'harga_beli' =>'required|numeric',
                'harga_jual' =>'required|numeric'
            ];
            // use Illuminate\Support\Facades\Validator;
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                    return response()->json([
                    'status' => false, // respon json, true: berhasil, false: gagal
                    'message' => 'Validasi gagal.',
                    'msgField' => $validator->errors() // menunjukkan field mana yang error
                ]);
            }

            $check = BarangModel::find($id);
            if ($check) {

                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } 
            else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function confirm_ajax(string $id) {
        $barang = BarangModel::find($id);
        return view('barang.confirm_ajax', ['barang' => $barang]);
    }

    public function delete_ajax(Request $request, $id) {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $barang = BarangModel::find($id);
            if ($barang) {
                $barang->delete();
                return response()->json([
                    'status'  => true,
                    'message' => 'Data berhasil dihapus'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        }
        return redirect('/');
    }

    public function import(){
        return view('barang.import');
    }

    public function import_ajax(Request $request)
{
    if ($request->ajax() || $request->wantsJson()) {

        $rules = [
            'file_barang' => ['required', 'mimes:xlsx', 'max:1024'] // max 1MB
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ]);
        }

        $file = $request->file('file_barang');

        // Load Excel dengan PhpSpreadsheet
        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);
        $spreadsheet = $reader->load($file->getRealPath());
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, false, true, true); // hasil berupa array

        $insert = [];

        if (count($data) > 1) { // Lewati header (baris pertama)
            foreach ($data as $baris => $value) {
                if ($baris > 1) {
                    $insert[] = [
                        'kategori_id' => $value['A'],
                        'barang_kode' => $value['B'],
                        'barang_nama' => $value['C'],
                        'harga_beli'  => $value['D'],
                        'harga_jual'  => $value['E'],
                        'created_at'  => now(),
                    ];
                }
            }
        }

        if (count($insert) > 0) {
            BarangModel::insertOrIgnore($insert);

            return response()->json([
                'status' => true,
                'message' => 'Data berhasil diimport'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Tidak ada data yang diimport'
            ]);
        }
    }

        return redirect('/');
    }

    public function export_excel(){
        $barang = BarangModel::select(
            'kategori_id','barang_kode',
            'barang_nama', 'harga_beli', 'harga_jual')
            ->orderBy('kategori_id')
            ->with('kategori')
            ->get();
        
        // load library excel 
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet(); // ambil sheet yang aktif 

        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'Kode Barang');
        $sheet->setCellValue('C1', 'Nama Barang');
        $sheet->setCellValue('D1', 'Harga Beli');
        $sheet->setCellValue('E1', 'Harga Jual');
        $sheet->setCellValue('F1', 'Kategori');

        $sheet->getStyle('A1:F1')->getFont()->setBold(true); //bold header 

        $no = 1; //nomor data dimulai dari 1 
        $baris = 2; // baris data dimulai dari 2 
        foreach($barang as $key => $value){
            $sheet->setCellValue('A'.$baris, $no);
            $sheet->setCellValue('B'.$baris, $value->barang_kode);
            $sheet->setCellValue('C'.$baris, $value->barang_nama);
            $sheet->setCellValue('D'.$baris, $value->harga_beli);
            $sheet->setCellValue('E'.$baris, $value->harga_jual);
            $sheet->setCellValue('E'.$baris, $value->kategori->kategori_nama); // ambil nama kategori 
            $baris++;
            $no++;
        }

        // set lebar tiap kolom 
        foreach(range('A','F') as $columnID){
            $sheet->getColumnDimension($columnID)->setAutoSize(true); // set autoo size untuk kolom 
        }

        $sheet->setTitle('Data Barang');

        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $filename = 'Data Barang '.date('Y-m-d H:i:s').'.xlsx';

        header('Content-Type: application/vnd. openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT' );
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s' ) . ' GMT' );
        header('Cache-Control: cache, must-revalidate');
        header('Pragma: public');

        $writer->save('php://output');
        exit;
    }

    public function export_pdf(){
        $barang = BarangModel::select('kategori_id','barang_kode' ,'barang_nama','harga_beli' ,'harga_jual' )
            ->orderBy('kategori_id')
            ->orderBy('barang_kode' )
            ->with('kategori')
            ->get();
        
        // use Barryvdh\DomPDF\Facade\Pdf;
        $pdf = Pdf::loadView('barang.export_pdf', ['barang' => $barang]);
        $pdf->setPaper('a4', 'portrait'); // set ukuran kertas dan orientasi
        $pdf->setOption("isRemoteEnabled", true); // set true jika ada gambar dari url
        $pdf->render();

        return $pdf->stream('Data Barang '.date('Y-m-d H:i:s').'.pdf');
    }
}