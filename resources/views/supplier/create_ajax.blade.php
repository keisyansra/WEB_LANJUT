<form action="{{ url('/supplier/ajax') }}" method="POST" id="form-tambah">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data Supplier</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><spanaria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Supplier</label>
                    <input type="text" name="supplier_kode" id="supplier_kode" class="form-control" required>
                    <small id="error-supplier_kode" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Nama Supplier</label>
                    <input type="text" name="supplier_nama" id="supplier_nama" class="form-control" required>
                    <small id="error-supplier_nama" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Alamat Supplier</label>
                    <input type="text" name="supplier_alamat" id="supplier_alamat" class="form-control" required>
                    <small id="error-supplier-alamat" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" data-dismiss="modal" class="btn btn-warning">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>
<script>
    $(document).ready(function() {
        $("#form-tambah").validate({
            rules: {
                supplier_kode: {
                    required: true,
                    minlength: 2,
                    maxlength: 10
                },
                supplier_nama: {
                    required: true,
                    minlength: 3,
                    maxlength: 50
                },
                supplier_alamat: {
                    required: true,
                    minlength: 5,
                    maxlength: 100
                }
            },
            messages: {
                supplier_kode: {
                    required: "Kode Supplier wajib diisi",
                    minlength: "Kode Supplier minimal 2 karakter",
                    maxlength: "Kode Supplier maksimal 10 karakter"
                },
                supplier_nama: {
                    required: "Nama Supplier wajib diisi",
                    minlength: "Nama Supplier minimal 3 karakter",
                    maxlength: "Nama Supplier maksimal 50 karakter"
                },
                supplier_alamat: {
                    required: "Alamat Supplier wajib diisi",
                    minlength: "Alamat Supplier minimal 5 karakter",
                    maxlength: "Alamat Supplier maksimal 100 karakter"
                }
            },
            submitHandler: function(form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function(response) {
                        if (response.status) {
                            $('#myModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            });
                            dataUser.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(response.msgField, function(prefix, val) {
                                $('#error-' + prefix).text(val[0]);
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan',
                                text: response.message
                            });
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>