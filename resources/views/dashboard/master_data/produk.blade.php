@extends('dashboard.layout.index')

@section('title', 'Produk')

@section('container')


    <!-- Bootstrap Table with Header - Dark -->
    <!-- Slide from Top Modal -->
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
                    <form class="form-produk modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title modal-tambah" id="modalTopTitle">Tambah @yield('title')</h5> <button
                                type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-3">
                                    <label for="exampleFormControlSelect1" class="form-label">Pilih Kategori &#42;</label>
                                    <select class="form-select" id="id_kategori" name="id_kategori" required
                                        aria-label="Default select example">
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->nama_kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlSelect1" class="form-label">Pilih SubKategori &#42;</label>
                                    <select class="form-select" id="id_subkategori" name="id_subkategori" required
                                        aria-label="Default select example">
                                        @foreach ($subcategories as $subcategory)
                                            <option value="{{ $subcategory->id }}">{{ $subcategory->nama_subkategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col mb-3">
                                    <label for="nameWithTitle" class="form-label">Nama Produk</label>
                                    <input type="text" id="nama_produk" name="nama_produk" required class="form-control"
                                        placeholder="Nama Produk" />
                                </div>
                            </div>
                            <div class="row g-2">
                                <div class="col mb-3">
                                    <label for="emailWithTitle" class="form-label">Harga</label>
                                    <input type="text" name="harga" required class="form-control"
                                        placeholder="150000" />
                                </div>
                                <div class="col mb-3">
                                    <label for="dobWithTitle" class="form-label">Diskon</label>
                                    <input type="text" id="diskon" name="diskon" class="form-control"
                                        placeholder="5000" />
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Gambar 1</label>
                                <input class="form-control" name="gambar_1" type="file" id="formFile" name="gambar" />
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Gambar 2</label>
                                <input class="form-control" name="gambar_2" type="file" id="formFile" name="gambar" />
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Gambar 3</label>
                                <input class="form-control" name="gambar_3" type="file" id="formFile" name="gambar" />
                            </div>
                            <div class="mb-3">
                                <label for="formFile" class="form-label">Gambar 4</label>
                                <input class="form-control" name="gambar_4" type="file" id="formFile" name="gambar" />
                            </div>
                            <div class="row g-2">
                                <div class="input-group">
                                    <span class="input-group-text">Deskripsi 1</span>
                                    <textarea class="form-control" name="deskripsi_1" aria-label="With textarea" placeholder="Deskripsi" name="deskripsi"></textarea>
                                </div>
                                <div class="input-group">
                                    <span class="input-group-text">Deskripsi 2</span>
                                    <textarea class="form-control" name="deskripsi_2" aria-label="With textarea" placeholder="Deskripsi"
                                        name="deskripsi"></textarea>
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
                        <th>Nama Subkategori</th>
                        <th>Nama @yield('title')</th>
                        <th>harga</th>
                        <th>diskon</th>
                        <th>gambar 1</th>
                        <th>gambar 2</th>
                        <th>gambar 3</th>
                        <th>gambar 4</th>
                        <th>Deskripsi 1</th>
                        <th>Deskripsi 2</th>
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
                url: '/api/produks',
                success: function({
                    data
                }) {

                    let row;
                    data.map(function(val, index) {
                        row += `
                        <tr> 
                            <td> ${val.category.nama_kategori} </td> 
                            <td> ${val.subcategory.nama_subkategori} </td> 
                            <td> ${val.nama_produk} </td> 
                            <td> ${val.harga} </td> 
                            <td> ${val.diskon} </td> 
                            <td> <img src='/uploads/${val.gambar_1}' width="100" height="100"> </td> 
                            <td> <img src='/uploads/${val.gambar_2}' width="100" height="100"> </td> 
                            <td> <img src='/uploads/${val.gambar_3}' width="100" height="100"> </td> 
                            <td> <img src='/uploads/${val.gambar_4}' width="100" height="100"> </td> 
                            <td> ${val.deskripsi_1} </td> 
                            <td> ${val.deskripsi_2} </td> 
                            
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
                        url: '/api/produks/' + id,
                        type: "DELETE",
                        headers: {
                            "Authorization": "Bearer" + token
                        },
                        success: function(data) {
                            if (!data.success) {
                                alert("Data berhasil di hapus");
                                location.reload();
                            }

                        }
                    })
                }

            })

            $('.modal-tambah').click(function() {
                $('#modal-form').modal('show')
                $('input[name="nama_kategori"]').val('');
                $('input[name="nama_subkategori"]').val('');
                $('input[name="nama_produk"]').val('');
                $('input[name="harga"]').val('');
                $('input[name="diskon"]').val('');
                $('input[name="gambar_1"]').val('');
                $('input[name="gambar_2"]').val('');
                $('input[name="gambar_3"]').val('');
                $('input[name="gambar_4"]').val('');
                $('input[name="deskripsi_1"]').val('');
                $('input[name="deskripsi_2"]').val('');


                $('.form-produk').submit(function(e) {
                    e.preventDefault()
                    const token = localStorage.getItem('token')

                    const frmdata = new FormData(this);

                    $.ajax({
                        url: 'api/produks',
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

                $.get('api/produks/' + id, function({
                    data
                }) {
                    $('select[name="id_kategori"]').val(data.id_kategori);
                    $('select[name="id_subkategori"]').val(data.id_subkategori);
                    $('input[name="nama_produk"]').val(data.nama_produk);
                    $('input[name="harga"]').val(data.harga);
                    $('input[name="diskon"]').val(data.diskon);
                    $('textarea[name="deskripsi_1"]').val(data.deskripsi_1);
                    $('textarea[name="deskripsi_2"]').val(data.deskripsi_2);
                })

                $('.form-produk').submit(function(e) {
                    e.preventDefault()
                    const token = localStorage.getItem('token')

                    const frmdata = new FormData(this);

                    $.ajax({
                        url: `api/produks/${id}?_method=PUT`,
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


        })
    </script>
@endpush
