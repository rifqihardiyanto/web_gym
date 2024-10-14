@extends('dashboard.layout.index')

@section('title', 'Member')

@section('container')

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
                    <form class="form-member modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title modal-tambah" id="modalTopTitle">Tambah @yield('title')</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Type Member Selection -->
                            <div class="mb-3">
                                <label for="exampleFormControlSelect1" class="form-label">Tipe Member &#42;</label>
                                <select class="form-select" id="type_member" name="type_member" required>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!-- Nama Member -->
                            <div class="col mb-3">
                                <label for="name" class="form-label">Nama Member</label>
                                <input type="text" id="name" name="name" required class="form-control"
                                    placeholder="Nama Member" />
                            </div>
                            <!-- Nomor Telepon -->
                            <div class="col mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" id="phone" name="phone" required class="form-control"
                                    placeholder="Nomor Telepon" />
                            </div>
                            <div class="col mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" id="email" name="email" required class="form-control"
                                    placeholder="Email" />
                            </div>
                            <!-- Tanggal Kedaluwarsa -->
                            <div class="col mb-3">
                                <label for="exp" class="form-label">Masa Berlaku</label>
                                <input type="date" id="exp" name="exp" required class="form-control" />
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
                        <th>Tipe Member</th>
                        <th>Nama Member</th>
                        <th>Nomor Telepon</th>
                        <th>ID Member</th>
                        <th>Email</th>
                        <th>Masa Berlaku</th>
                        <th>Sisa Hari</th>
                        <th>Aksi</th>
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
        $(function() {
            $.ajax({
                url: '/api/members',
                success: function({
                    data
                }) {
                    let row;
                    data.map(function(val, index) {
                        const today = new Date();
                        const expDate = new Date(val.exp);

                        // Menghitung selisih hari
                        const timeDiff = expDate.getTime() - today.getTime();
                        const remainingDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

                        row += `
                <tr> 
                    <td> ${val.category.name} </td> 
                    <td> ${val.name} </td> 
                    <td> ${val.phone} </td> 
                    <td> ${val.id_member} </td> 
                    <td> ${val.email} </td> 
                    <td> ${val.exp} </td>
                    <td> ${remainingDays} hari </td>
                    <td>
                        <a data-toggle="modal" href="#modal-form" data-id="${val.id}" class="btn btn-warning modal-ubah">Edit </a>    
                        <a href="#" data-id="${val.id}" class="btn btn-danger btn-hapus">Hapus </a>    
                        <a href="#" data-id="${val.id}" class="btn btn-info btn-wa">Kirim WhatsApp </a>    
                    </td>
                </tr>`;
                    });

                    $('tbody').append(row);
                }
            });

            $(document).on('click', '.btn-wa', function() {
                const id = $(this).data('id');

                // Mengambil data member dari server untuk membuat pesan
                $.get(`/api/members/${id}`, function({
                    data
                }) {
                    const phoneNumber = data.phone;
                    const idMember = data.id_member;
                    const name = data.name;
                    const exp = data.exp;
                    const message = encodeURIComponent(
                        `Halo, ${name}. ID Member Anda adalah ${idMember}. Berakhir sampai ${exp}`);

                    // Mengarahkan pengguna ke WhatsApp
                    const whatsappUrl = `https://wa.me/${phoneNumber}?text=${message}`;

                    // Konfirmasi pengiriman pesan
                    const confirmSend = confirm('Yakin ingin mengirim pesan WhatsApp?');

                    if (confirmSend) {
                        window.location.href = whatsappUrl;
                    }
                });
            });


            $(document).on('click', '.btn-hapus', function() {
                const id = $(this).data('id');
                const token = localStorage.getItem('token');

                confirm_dialog = confirm('Yakin ingin menghapus?');

                if (confirm_dialog) {
                    $.ajax({
                        url: '/api/members/' + id,
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
                $('#modal-form').modal('show');

                // Reset nilai field pada modal untuk penambahan member baru
                $('select[name="type_member"]').val('');
                $('input[name="name"]').val('');
                $('input[name="phone"]').val('');
                $('input[name="email"]').val('');
                $('input[name="exp"]').val('');

                $('.form-member').submit(function(e) {
                    e.preventDefault();
                    const token = localStorage.getItem('token');
                    const frmdata = new FormData(this);

                    $.ajax({
                        url: 'api/members',
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
                                alert("Data berhasil Ditambah");
                                location.reload();
                            }
                        }
                    });
                });
            });


            $(document).on('click', '.modal-ubah', function() {
                $('#modal-form').modal('show')
                const id = $(this).data('id');

                $.get('api/members/' + id, function({
                    data
                }) {
                    $('select[name="type_member"]').val(data.type_member)
                    $('input[name="name"]').val(data.name)
                    $('input[name="phone"]').val(data.phone)
                    $('input[name="email"]').val(data.email)
                    $('input[name="exp"]').val(data.exp)
                })

                $('.form-member').submit(function(e) {
                    e.preventDefault()
                    const token = localStorage.getItem('token')

                    const frmdata = new FormData(this);

                    $.ajax({
                        url: `api/members/${id}?_method=PUT`,
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
