@extends('dashboard.layout.index')

@section('title', 'Member')

@section('container')

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
                    <form class="form-regis-member modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title modal-tambah" id="modalTopTitle">Tambah @yield('title')</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- ID Member -->
                            <div class="mb-3">
                                <label for="id_member" class="form-label">ID Member</label>
                                <div class="input-group">
                                    <input type="text" id="id" name="id_member" required class="form-control"
                                        placeholder="Masukkan ID Member" />
                                    <button type="button" class="btn btn-outline-secondary"
                                        id="btn-cari-member">Cari</button>
                                </div>
                            </div>
                            <!-- Nama Member -->
                            <div class="col mb-3">
                                <label for="nama" class="form-label">Nama Member</label>
                                <input type="text" id="nama" name="nama" required class="form-control"
                                    placeholder="Nama Member" readonly />
                            </div>
                            <!-- Tipe Member Selection -->
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Tipe Member &#42;</label>
                                <input type="text" id="kategori" name="kategori" required class="form-control"
                                    placeholder="Kategori" readonly />
                            </div>
                            <!-- Harga -->
                            <div class="col mb-3">
                                <label for="harga" class="form-label">Harga</label>
                                <input type="text" id="harga" name="harga" required class="form-control"
                                    placeholder="Harga" readonly />
                            </div>
                            <!-- Notifikasi Expired -->
                            <div class="alert alert-warning d-none" id="expired-notification">Member sudah expired!</div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
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
                <button id="btn-search" class="btn btn-primary me-2">Filter</button>

                <button type="button" class="btn btn-success" id="export-btn">Ekspor</button>
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>ID Member</th>
                            <th>Kategori</th>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        $(document).ready(function() {
            let allData = [];

            $.ajax({
                url: '/api/member-reports',
                method: 'GET',
                success: function({
                    data
                }) {
                    allData = data;
                    displayData(data);
                }
            });

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
                    const expDate = new Date(val.exp);
                    const expired = expDate < new Date() ? ' (Expired)' : '';

                    row += `
                        <tr> 
                            <td>${val.nama}</td> 
                            <td>PG-${val.id_member}</td> 
                            <td>${val.category.name}</td> 
                            <td>${val.harga}</td> 
                            <td>${formattedDate}${expired}</td> 
                            <td>
                                <button class="btn btn-danger btn-delete" data-id="${val.id}">Delete</button>
                            </td>
                        </tr>`;
                });
                $('tbody').html(row);
            }
            $(document).on('click', '.btn-delete', function() {
                const id = $(this).data('id');
                const token = localStorage.getItem('token');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data yang sudah dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/api/member-reports/${id}`,
                            type: 'DELETE',
                            headers: {
                                "Authorization": "Bearer " + token
                            },
                            success: function(data) {
                                if (data.success) {
                                    Swal.fire(
                                        'Dihapus!',
                                        'Data berhasil dihapus.',
                                        'success'
                                    ).then(() => {
                                        location
                                            .reload();
                                    });
                                }
                            },
                            error: function(jqXHR) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Kesalahan',
                                    text: 'Gagal menghapus data. Silakan coba lagi.',
                                });
                            }
                        });
                    }
                });
            });

            $('#btn-search').click(function() {
                const startDate = $('#start_date').val();
                const endDate = $('#end_date').val();

                $.ajax({
                    url: '/api/member-reports',
                    method: 'GET',
                    data: {
                        start_date: startDate,
                        end_date: endDate
                    },
                    success: function(response) {
                        displayData(response
                            .data);
                    }
                });
            });

            $(document).ready(function() {
                $('#export-btn').click(function() {
                    var startDate = $('#start_date').val();
                    var endDate = $('#end_date').val();

                    if (!startDate || !endDate) {
                        alert('Harap pilih rentang tanggal terlebih dahulu.');
                        return;
                    }

                    window.location.href = "{{ url('export-regis-member') }}?start_date=" +
                        startDate + "&end_date=" + endDate;
                });
            });


            $('#btn-cari-member').click(function() {
                const id = $('#id').val();

                $.ajax({
                    url: `/api/member/search?id=${id}`,
                    method: 'GET',
                    success: function(response) {
                        if (response) {
                            $('#nama').val(response.name);
                            $('#kategori').val(response.type_member);
                            $('#harga').val(response.harga);

                            const expDate = new Date(response.exp);
                            const today = new Date();
                            if (expDate < today) {
                                $('#expired-notification').removeClass(
                                    'd-none');
                                $('.modal-footer .btn-primary')
                                    .hide();
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Member Expired',
                                    text: 'Member sudah expired!',
                                    backdrop: 'rgba(0,0,0,0.5)',
                                }).then(() => {
                                    $('#modal-form').modal(
                                        'hide'
                                    );
                                });
                            } else {
                                $('#expired-notification').addClass(
                                    'd-none');
                                $('.modal-footer .btn-primary')
                                    .show();
                            }

                        }
                    },
                    error: function(jqXHR) {
                        $('#modal-form').modal('hide');

                        let errorMessage;

                        if (jqXHR.status === 404) {
                            errorMessage = 'Member tidak ditemukan. Pastikan ID benar.';
                        } else {
                            errorMessage =
                                'Terjadi kesalahan saat menghubungi server. Silakan coba lagi nanti.';
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: errorMessage,
                            backdrop: 'rgba(0,0,0,0.5)',
                        });

                        $('#nama, #kategori, #harga').val('');
                        $('#expired-notification').addClass('d-none');
                    }
                });
            });

            $('.modal-tambah').click(function() {
                $('#modal-form').modal('show');
                $('#id_member, #nama, #kategori, #harga').val('');
                $('#expired-notification').addClass('d-none');
                $('.modal-footer .btn-primary').show();
            });

            $('.form-regis-member').submit(function(e) {
                e.preventDefault();
                const token = localStorage.getItem('token');
                const frmdata = new FormData(this);

                $.ajax({
                    url: '/api/member-reports',
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
                            $('#modal-form').modal('hide');

                            Swal.fire({
                                icon: 'success',
                                title: 'Data berhasil ditambahkan',
                                text: 'Member baru telah ditambahkan.',
                                backdrop: 'rgba(0,0,0,0.5)',
                            }).then(() => {
                                location
                                    .reload();
                            });
                        }
                    },
                    error: function(jqXHR) {
                        console.log('Error:', jqXHR);
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
