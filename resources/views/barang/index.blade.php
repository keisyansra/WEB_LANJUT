@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-header">
        <h3 class="card-title">{{ $page->title }}</h3>
        <div class="card-tools">
            <button onclick="modalAction('{{ url('/barang/import')}}')" class="btn btn-info">Import Barang</button>
            <a class="btn btn-sm btn-primary mt-1" href="{{ url('barang/create') }}">Tambah</a>
            <a href="{{url('/barang/export_excel')}}" class="btn tbn-primary"><i class="fa fa-file-excel"></i>Export Barang</a>
            <a href="{{ url('/barang/export_pdf') }}" class="btn btn-warning"><i class="fa fa-file-pdf"></i> Export Barang</a>
            <button class="btn btn-sm btn-success mt-1" data-url="{{ url('/barang/create_ajax') }}" onclick="modalAction(this)">Tambah Ajax</button>
        </div>
    </div>
    <div class="card-body">
        @if (session('success'))
        <div class="alert alert-success">{{ session ('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session ('error') }}</div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <div class="form-group row">
                    <label class="col-1 control label col-form-label">Filter:</label>
                    <div class="col-3">
                        <select class="form-control" id="kategori_id" name="kategori_id" required>
                            <option value="">- Semua -</option>
                            @foreach($kategori as $item)
                            <option value="{{ $item->kategori_id }}">{{ $item->kategori_kode }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted">Filter Berdasarkan Kode Kategori</small>
                    </div>
                </div>
            </div>
        </div>
        <table class="table table-bordered table-striped table-hover table-sm" id="table_barang">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kode Kategori</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Aksi</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection
@push('css')
@endpush
@push('js')
<script>
    function modalAction(element) {
    let url = typeof element === "string" ? element : element.getAttribute("data-url");
    $('#myModal').load(url, function() {
        $('#myModal').modal('show');
    });
}
    var dataBarang;
    $(document).ready(function() {
        dataBarang = $('#table_barang').DataTable({
            // serverSide: true, jika ingin menggunakan server side processing
            serverSide: true,
            ajax: {
                "url": "{{ url('barang/list') }}",
                "dataType": "json",
                "type": "POST",
                "data": function(d) {
                    d.kategori_id = $('#kategori_id').val();
                }
            },
            columns: [{ // nomor urut dari laravel datatable addIndexColumn()
                    data: "DT_RowIndex",
                    className: "text-center",
                    orderable: false,
                    searchable: false
                },
                { // mengambil data kategori hasil dari ORM berelasi
                    data: "kategori.kategori_id",
                    className: "",
                    // orderable: true, jika ingin kolom ini bisa diurutkan
                    orderable: true,
                    // searchable: true, jika ingin kolom ini bisa dicari
                    searchable: true
                },
                {
                    data: "barang_kode",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                { 
                    data: "barang_nama",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "harga_beli",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "harga_jual",
                    className: "",
                    orderable: true,
                    searchable: true
                },
                {
                    data: "aksi",
                    className: "",
                    orderable: false,
                    searchable: false
                }
            ]
        });
        // $('#data-barang_filter input').unbind().bind().on('keyup', function(e)){
            // if(e.keyCode == 13) {
                // dataBarang.search(this.value).draw();
            // }
        // }
        $('#kategori_id').on('change', function() {
            dataBarang.ajax.reload();
        });
    });
</script>
@endpush