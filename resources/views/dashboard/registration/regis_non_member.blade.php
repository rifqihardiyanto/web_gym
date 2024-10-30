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
                            <div class="col mb-3">
                                <label for="name" class="form-label">Nama Member</label>
                                <input type="text" id="nama" name="nama" required class="form-control"
                                    placeholder="Nama Member" />
                            </div>
                            <div class="col mb-3">
                                <label for="name" class="form-label">Metode Pembayaran</label>
                                <select class="form-select" id="payment" name="payment" required>
                                    <option value="" disabled selected>Pilih Payment</option>
                                    <option value="TF">TF</option>
                                    <option value="CASH">CASH</option>
                                </select>
                            </div>
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
        <h5 class="card-header">Data Registrasi @yield('title')</h5>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="start-date" class="form-label">Tanggal Mulai</label>
                    <input type="date" id="start_date" class="form-control">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end-date" class="form-label">Tanggal Akhir</label>
                    <input type="date" id="end_date" class="form-control">
                </div>
            </div>
            <div class="d-flex mb-3">
                <button id="btn-search" class="btn btn-primary me-2">Cari</button>
                <button type="button" class="btn btn-success" id="export-btn">Ekspor</button>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Payment</th>
                            <th>Harga</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-10">
                        <!-- Isi data member dari JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!--/ Bootstrap Table with Header Dark -->

@endsection

@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        $(document).ready(function() {
            let allData = []; // Deklarasi allData di sini

            function displayData(data) {
                let row = '';
                data.map(function(val) {
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
                            <td> ${val.payment} </td> 
                            <td> ${val.harga} </td> 
                            <td> ${formattedDate} </td>
                            <td>
                                <button class="btn btn-danger btn-delete" data-id="${val.id}">Hapus</button>
                            </td>
                        </tr>`;
                });

                $('tbody').html(row);
                $('.btn-delete').click(function() {
                    const id = $(this).data('id');
                    deleteNonMemberReport(id);
                });
            }

            function deleteNonMemberReport(id) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Data akan dihapus secara permanen!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/api/non-member-reports/${id}`,
                            type: 'DELETE',
                            headers: {
                                "Authorization": "Bearer " + localStorage.getItem('token')
                            },
                            success: function(data) {
                                if (data.success) {
                                    Swal.fire(
                                        'Dihapus!',
                                        'Data berhasil dihapus.',
                                        'success'
                                    ).then(() => {
                                        location
                                    .reload(); // Reload halaman setelah penghapusan
                                    });
                                }
                            },
                            error: function(jqXHR) {
                                const errorMessage = jqXHR.responseJSON ? jqXHR.responseJSON
                                    .message : 'Terjadi kesalahan saat menghapus data.';
                                Swal.fire('Kesalahan', errorMessage, 'error');
                            }
                        });
                    }
                });
            }

            // Mengambil data member dari API
            $.ajax({
                url: '/api/non-member-reports',
                success: function({
                    data
                }) {
                    allData = data;
                    displayData(data);
                }
            });

            // Fungsi untuk mencari data berdasarkan rentang tanggal
            $('#btn-search').click(function() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                // Panggil API dan kirim tanggal yang dipilih
                $.ajax({
                    url: '/api/non-member-reports',
                    method: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        displayData(response
                            .data); // Tampilkan data yang dikembalikan oleh backend
                    }
                });
            });

            $(document).ready(function() {
                $('#export-btn').click(function() {
                    // Ambil nilai dari input tanggal
                    var startDate = $('#start_date').val();
                    var endDate = $('#end_date').val();

                    // Validasi apakah tanggal diisi
                    if (!startDate || !endDate) {
                        alert('Harap pilih rentang tanggal terlebih dahulu.');
                        return;
                    }

                    window.location.href = "{{ url('export-regis-nonmember') }}?start_date=" +
                        startDate + "&end_date=" + endDate;
                });
            });

            // Update harga ketika kategori dipilih
            $('#kategori').change(function() {
                var harga = $(this).find(':selected').data('harga');
                $('#harga').val(harga);
            });

            $('.modal-tambah').click(function() {
                $('#modal-form').modal('show');

                $('#kategori').val('');
                $('#payment').val('');
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
