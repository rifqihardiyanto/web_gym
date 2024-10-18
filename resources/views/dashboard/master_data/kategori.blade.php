@extends('dashboard.layout.index')

@section('title', 'Kategori')

@section('container')

    <!-- SweetAlert CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <!-- Bootstrap Table with Header - Dark -->
    <div class="col-lg-4 col-md-6">
        <div class="mt-3">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3 modal-tambah" data-bs-toggle="modal"
                data-bs-target="#modal-form">
                Tambah @yield('title')
            </button>

            <!-- Modal -->
            <div class="modal modal-top fade" id="modal-form" tabindex="-1">
                <div class="modal-dialog">
                    <form class="form-kategori modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title modal-tambah" id="modalTopTitle">Tambah @yield('title')</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameSlideTop" class="form-label">Nama @yield('title')</label>
                                    <input type="text" class="form-control" placeholder="Enter Kategori"
                                        name="name" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameSlideTop" class="form-label">Biaya @yield('title')</label>
                                    <input type="text" class="form-control" placeholder="Enter Kategori"
                                        name="biaya" />
                                </div>
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
        <h5 class="card-header">Dashboard | @yield('title')</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th>Nama @yield('title')</th>
                        <th>Biaya @yield('title')</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-10">

                </tbody>
            </table>
        </div>
    </div>
    <!--/ Bootstrap Table with Header Dark -->

@endsection

@push('js')
    <script>
        $(function() {
            $.ajax({
                url: '/api/categories',
                success: function({
                    data
                }) {
                    let row = '';
                    data.map(function(val) {
                        row += `
                        <tr> 
                            <td> ${val.name} </td> 
                            <td> ${val.biaya} </td> 
                            <td>
                                <a data-toggle="modal" href="#modal-form" data-id="${val.id}" class="btn btn-warning modal-ubah">Edit</a>    
                                <a href="#" data-id="${val.id}" class="btn btn-danger btn-hapus">Hapus</a>    
                            </td>
                        </tr>`;
                    });

                    $('tbody').append(row);
                }
            });

            $(document).on('click', '.btn-hapus', function() {
                const id = $(this).data('id');
                const token = localStorage.getItem('token');

                Swal.fire({
                    title: 'Yakin ingin menghapus?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                    backdrop: 'rgba(0,0,0,0.5)',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/api/categories/' + id,
                            type: "DELETE",
                            headers: {
                                "Authorization": "Bearer " + token
                            },
                            success: function(data) {
                                if (data.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Sukses',
                                        text: 'Data berhasil dihapus!',
                                    }).then(() => {
                                        location
                                    .reload(); // Reload page after deletion
                                    });
                                }
                            }
                        });
                    }
                });
            });


            $('.modal-tambah').click(function() {
                $('#modal-form').modal('show');
                $('input[name="name"]').val('');
                $('input[name="biaya"]').val('');

                $('.form-kategori').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    const token = localStorage.getItem('token');

                    const frmdata = new FormData(this);

                    $.ajax({
                        url: 'api/categories',
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
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: 'Data berhasil ditambah!',
                                    backdrop: 'rgba(0,0,0,0.5)',
                                }).then(() => {
                                    $('#modal-form').modal(
                                        'hide'); // Tutup modal di sini
                                    location
                                        .reload(); // Reload page setelah menambah
                                });
                            }
                        }
                    });
                });
            });

            $(document).on('click', '.modal-ubah', function() {
                $('#modal-form').modal('show');
                const id = $(this).data('id');

                $.get('api/categories/' + id, function({
                    data
                }) {
                    $('input[name="name"]').val(data.name);
                    $('input[name="biaya"]').val(data.biaya);
                });

                $('.form-kategori').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    const token = localStorage.getItem('token');

                    const frmdata = new FormData(this);

                    $.ajax({
                        url: `api/categories/${id}?_method=PUT`,
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
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Sukses',
                                    text: 'Data berhasil diubah!',
                                    backdrop: 'rgba(0,0,0,0.5)',
                                }).then(() => {
                                    $('#modal-form').modal(
                                        'hide'); // Close the modal
                                    location
                                        .reload(); // Reload page after updating
                                });
                            }
                        }
                    });
                });
            });
        });
    </script>
@endpush
