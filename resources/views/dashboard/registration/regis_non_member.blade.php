@extends('dashboard.layout.index')

@section('title', 'Non Member')

@section('container')

    <!-- SweetAlert CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <!-- Bootstrap Table with Header - Dark -->
    <div class="col-lg-4 col-md-6">
        <div class="mt-3">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3 modal-tambah" data-bs-toggle="modal"
                data-bs-target="#modal-form">
                Registrasi @yield('title')
            </button>

            <!-- Modal -->
            <div class="modal modal-top fade" id="modal-form" tabindex="-1">
                <div class="modal-dialog">
                    <form class="form-regis-non-member modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title modal-tambah" id="modalTopTitle">Tambah @yield('title')</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Type Member Selection -->
                            <div class="mb-3">
                                <label for="exampleFormControlSelect1" class="form-label">Tipe Member &#42;</label>
                                <select class="form-select" id="kategori" name="kategori" required>
                                    <option value="" disabled selected>Pilih Kategori</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" data-harga="{{ $category->biaya }}">
                                            {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Nama Member -->
                            <div class="col mb-3">
                                <label for="name" class="form-label">Nama Member</label>
                                <input type="text" id="nama" name="nama" required class="form-control"
                                    placeholder="Nama Member" />
                            </div>
                            <!-- Harga -->
                            <div class="col mb-3">
                                <label for="harga" class="form-label">Harga</label>
                                <input type="text" id="harga" name="harga" required class="form-control"
                                    placeholder="Harga" readonly />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                Close
                            </button>
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <h5 class="card-header">Data Registrasi | @yield('title')</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-10">
                    <!-- Isi data member dari JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
    <!--/ Bootstrap Table with Header Dark -->

@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Mengambil data member dari API
            $.ajax({
                url: '/api/non-member-reports',
                success: function({ data }) {
                    let row = '';
                    data.map(function(val) {
                        // Format tanggal created_at ke hari-bulan-tahun
                        const createdDate = new Date(val.created_at);
                        const options = {
                            day: '2-digit',
                            month: '2-digit',
                            year: 'numeric'
                        };
                        const formattedDate = createdDate.toLocaleDateString('id-ID', options);

                        row += `
                            <tr> 
                                <td> ${val.nama} </td> 
                                <td> ${val.category.name} </td> 
                                <td> ${val.harga} </td> 
                                <td> ${formattedDate} </td> 
                            </tr>`;
                    });

                    $('tbody').append(row);
                }
            });

            // Update harga ketika kategori dipilih
            $('#kategori').change(function() {
                var harga = $(this).find(':selected').data('harga');
                $('#harga').val(harga);
            });

            $('.modal-tambah').click(function() {
                $('#modal-form').modal('show');

                // Reset nilai field pada modal untuk penambahan member baru
                $('#kategori').val('');
                $('#harga').val('');
                $('#nama').val('');
            });

            // Proses Submit Form
            $('.form-regis-non-member').submit(function(e) {
                e.preventDefault();
                const token = localStorage.getItem('token');
                const frmdata = new FormData(this);

                $.ajax({
                    url: 'api/non-member-reports',
                    type: 'POST',
                    data: frmdata,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        "Authorization": "Bearer " + token
                    },
                    success: function(data) {
                        if (data.success) {
                            $('#modal-form').modal('hide'); // Tutup modal terlebih dahulu
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: 'Data berhasil Ditambah!',
                                backdrop: 'rgba(0,0,0,0.5)',
                            }).then(() => {
                                location.reload(); // Reload data setelah alert ditutup
                            });
                        }
                    },
                    error: function(jqXHR) {
                        const errorMessage = jqXHR.responseJSON ? jqXHR.responseJSON.message :
                            'Terjadi kesalahan saat menyimpan data.';
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: errorMessage,
                            backdrop: 'rgba(0,0,0,0.5)',
                        });
                    }
                });
            });
        });
    </script>
@endpush
