<?php

namespace App\Http\Controllers;

use App\Models\SupplierModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\Cast\Object_;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;



class SupplierController extends Controller {
    //menampilkan halaman awal user
    public function index() {
        $breadcrumb = (object) [
            'title' => 'Daftar Supplier',
            'list' => ['Home', 'Supplier']
        ];

        $page = (object) [
            'title' => 'Daftar supplier yang terdaftar dalam sistem'
        ];

        $activeMenu = 'supplier'; 
        $supplier = SupplierModel::all(); 
        return view('supplier.index', ['breadcrumb' => $breadcrumb, 'page' => $page,'supplier' => $supplier, 'activeMenu' => $activeMenu]);
    }

    // Ambil data user dalam bentuk json untuk datatables
    public function list(Request $request) {
        $supplier = SupplierModel::select('supplier_id', 'supplier_kode', 'supplier_nama', 'supplier_alamat');

        // Filter data user berdasarkan supplier
        if ($request->supplier_id) {
            $supplier->where('supplier_id',$request->supplier_id);
        }

        return DataTables::of($supplier)
        ->addIndexColumn() // menambahkan kolom index / no urut (default nama kolom:DT_RowIndex)
        ->addColumn('aksi', function ($supplier) { // menambahkan kolom aksi
            $btn = '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id .
            '/show_ajax').'\')" class="btn btn-info btn-sm">Detail</button> ';

            $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id .
            '/edit_ajax').'\')" class="btn btn-warning btn-sm">Edit</button> ';

            $btn .= '<button onclick="modalAction(\''.url('/supplier/' . $supplier->supplier_id .
            '/delete_ajax').'\')" class="btn btn-danger btn-sm">Hapus</button> ';

            return $btn; 
        })
        ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
        ->make(true);
    }
    
    // Menampilkan halaman form tambah user
    public function create() {
        $breadcrumb = (object) [
            'title' => 'Tambah supplier',
            'list' => ['Home', 'supplier', 'Tambah'] 
        ];

        $page = (object) [
            'title' => 'Tambah supplier baru'
        ];

        $supplier = SupplierModel::all(); // ambil data supplier untuk ditampilkan di form
        $activeMenu = 'supplier'; // set menu yang sedang aktif
        return view('supplier.create', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'supplier' => $supplier,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan data user baru
    public function store(Request $request) {
        $request->validate([
            'supplier_kode' => 'required | string | min:2 | unique:m_supplier, supplier_kode',
            'supplier_nama' => 'required | string | max:100',
            'supplier_alamat' => 'required | string | min:100'
        ]);

        // simpan ke database 
        SupplierModel::create([
            'supplier_kode' => $request->supplier_kode,
            'supplier_nama' => $request->supplier_nama,
            'supplier_alamat' => $request->supplier_alamat
        ]);

        return redirect('/supplier')->with('success', 'Data user berhasil disimpan');
    }

    // Menampilkan detail user
    public function show(string $id) {
        $supplier = SupplierModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Detail supplier',
            'list' => ['Home', 'supplier', 'Detail']
        ];

        $page = (object) [
            'title' => 'Detail supplier'
        ];

        $activeMenu = 'supplier'; // set menu yang sedang aktif

        return view('user.show', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'supplier' => $supplier,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menampilkan halaman form edit user
    public function edit(string $id) {
        $supplier = SupplierModel::find($id);

        $breadcrumb = (object) [
            'title' => 'Edit supplier',
            'list' => ['Home', 'supplier', 'Edit']
        ];

        $page = (object) [
            'title' => 'Edit supplier'
        ];

        $activeMenu = 'supplier'; // set menu yang sedang aktif
        return view('supplier.edit', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'supplier' => $supplier,
            'activeMenu' => $activeMenu
        ]);
    }

    // Menyimpan perubahan data user
    public function update(Request $request, string $id) {
        $request->validate([
            'supplier_kode' => 'required|string|max:50|unique:m_supplier,supplier_kode,' . $id . ',supplier_id',
            'supplier_nama' => 'required|string|max:100',
            'supplier_alamat' => 'required|string|min:100'
        ]);

        SupplierModel::find($id)->update([
            'supplier_nama' => $request->supplier_nama,
            'supplier_kode' => $request->supplier_kode,
            'supplier_alamat' => $request->supplier_alamat,
        ]);

        return redirect('/supplier')->with('success', 'Data supplier berhasil diubah');
    }

    // Menghapus data user
    public function destroy(string $id) {
        $check = SupplierModel::find($id);
        if (!$check) { // untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
            return redirect('/supplier')->with('error', 'Data supplier tidak ditemukan');
        }

        try {
            SupplierModel::destroy($id); // Hapus data user

            return redirect('/supplier')->with('success', 'Data supplier berhasil dihapus');
        } catch (\Illuminate\Database\QueryException $e) {

            // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
            return redirect('/supplier')->with('error', 'Data supplier gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
        }
    }

    public function create_ajax() {
        $supplier = SupplierModel::select('supplier_id', 'supplier_nama')->get();
        return view('supplier.create_ajax') ->with('supplier', $supplier);
    }

    public function store_ajax(Request $request) {
        // cek apakah request berupa ajax
        if($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_kode' => 'required|string|max:50|unique:m_supplier,supplier_kode,',
                'supplier_nama' => 'required|string|max:100',
                'supplier_alamat' => 'required|string|min:100'
            ];
        
            $validator = Validator::make($request->all(), $rules);
        
            if($validator->fails()){
                return response()->json([
                    'status'  => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField'=> $validator->errors(), // pesan error validasi
                ]);
            }
        
            SupplierModel::create($request->all());
            return response()->json([
                'status'  => true,
                'message' => 'Data supplier berhasil disimpan'
            ]);
        }
        return redirect('/');
    }  
        
    //menampilkan halaman form edit user ajax
    public function edit_ajax(string $id) {
        $supplier = SupplierModel::find($id);
        return view('supplier.edit_ajax', ['supplier' => $supplier]);
    }

    public function update_ajax(Request $request, $id) {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'supplier_kode' => 'required|string|min:2|max:10|unique:m_supplier,supplier_kode,' . $id . ',supplier_id',
                'supplier_nama' => 'required|string|min:3|max:100',
                'supplier_alamat' => 'required|string|min:10|max:255'
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

            $check = SupplierModel::find($id);
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
        $supplier = SupplierModel::find($id);
        return view('supplier.confirm_ajax', ['supplier' => $supplier]);
    }

    public function delete_ajax(Request $request, $id) {
        // cek apakah request dari ajax
        if ($request->ajax() || $request->wantsJson()) {
            $supplier = SupplierModel::find($id);
            if ($supplier) {
                $supplier->delete();
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
}