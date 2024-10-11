@extends('dashboard.layout.index')

@section('title', 'SubKategori')

@section('container')

    <!-- Bootstrap Table with Header - Dark -->
    <!-- Slide from Top Modal -->
    <div class="col-lg-4 col-md-6">
        <div class="mt-3">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-primary mb-3 modal-tambah" data-bs-toggle="modal" data-bs-target="#modal-form">
                Tambah @yield('title')
            </button>

            <!-- Modal -->
            <div class="modal modal-top fade" id="modal-form" tabindex="-1">
                <div class="modal-dialog">
                    <form class="form-subkategori modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title modal-tambah" id="modalTopTitle">Tambah @yield('title')</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="exampleFormControlSelect1" class="form-label">Pilih Kategori &#42;</label>
                                <select class="form-select" id="id_kategori" name="id_kategori" required
                                    aria-label="Default select example">
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mb-3">
                                <label for="nameWithTitle" class="form-label">Nama SubKategori</label>
                                <input type="text" id="nama_subkategori" name="nama_subkategori" required
                                    class="form-control" placeholder="Nama Sub Kategori" />
                            </div>
                            <div class="row g-2">
                                <div class="input-group">
                                    <span class="input-group-text">Deskripsi</span>
                                    <textarea class="form-control" aria-label="With textarea" placeholder="Deskripsi" name="deskripsi"></textarea>
                                </div>
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Gambar</label>
                                    <input name="gambar" class="form-control" type="file" id="formFile" />
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
                        <th>Nama Kategori</th>
                        <th>Nama @yield('title')</th>
                        <th>Deskripsi</th>
                        <th>Gambar</th>
                        <th>Aksi</th>
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
                url: '/api/subcategories',
                success: function({
                    data
                }) {

                    let row;
                    data.map(function(val, index) {
                        row += `
                        <tr> 
                            <td> ${val.category.nama_kategori} </td> 
                            <td> ${val.nama_subkategori} </td> 
                            <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"> ${val.deskripsi} </td> 
                            <td> <img src='/uploads/${val.gambar}' width="100" height="100"> </td> 
                            <td>
                                <a  data-toogle="modal" href="#modal-form" data-id="${val.id}" class="btn btn-warning modal-ubah">Edit </a>    
                                <a  href="#" data-id="${val.id}" class="btn btn-danger btn-hapus">Hapus </a>    
                            </td>
                        </tr>`;
                    });

                    $('tbody').append(row);
                }
            });

            $(document).on('click', '.btn-hapus', function() {
                const id = $(this).data('id');
                const token = localStorage.getItem('token');

                confirm_dialog = confirm('Yakin ingin menghapus?');

                if (confirm_dialog) {
                    $.ajax({
                        url: '/api/subcategories/' + id,
                        type: "DELETE",
                        headers: {
                            "Authorization": "Bearer" + token
                        },
                        success: function(data) {
                            if (data.success) {
                                alert("Data berhasil di hapus");
                                location.reload();
                            }

                        }
                    })
                }

            })

            $('.modal-tambah').click(function() {
                $('#modal-form').modal('show')
                $('select[name="nama_kategori"]').val('')
                $('input[name="nama_subkategori"]').val('')
                $('textarea[name="deskripsi"]').val('')
                $('input[name="gambar"]').val('')

                $('.form-subkategori').submit(function(e) {
                    e.preventDefault()
                    const token = localStorage.getItem('token')

                    const frmdata = new FormData(this);

                    $.ajax({
                        url: 'api/subcategories',
                        type: 'POST',
                        data: frmdata,
                        cache: false,
                        contentType: false,
                        processData: false,
                        headers: {
                            "Authorization": "Bearer" + token
                        },
                        success: function(data) {
                            if (data.success) {
                                alert("Data berhasil Ditambah");
                                location.reload();
                            }
                        }
                    })
                })
            });

            $(document).on('click', '.modal-ubah', function() {
                $('#modal-form').modal('show')
                const id = $(this).data('id');

                $.get('api/subcategories/' + id, function({
                    data
                }) {
                    $('select[name="id_kategori"]').val(data.id_kategori)
                    $('input[name="nama_subkategori"]').val(data.nama_subkategori)
                    $('textarea[name="deskripsi"]').val(data.deskripsi)
                })

                $('.form-subkategori').submit(function(e) {
                    e.preventDefault()
                    const token = localStorage.getItem('token')

                    const frmdata = new FormData(this);

                    $.ajax({
                        url: `api/subcategories/${id}?_method=PUT`,
                        type: 'POST',
                        data: frmdata,
                        cache: false,
                        contentType: false,
                        processData: false,
                        headers: {
                            "Authorization": "Bearer" + token
                        },
                        success: function(data) {
                            if (data.success) {
                                alert("Data berhasil Diubah");
                                location.reload();
                            }
                        }
                    })
                })

            })
        });
    </script>
@endpush
