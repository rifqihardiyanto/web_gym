@extends('dashboard.layout.index')

@section('title', 'Member')

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
                    <form class="form-member modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title modal-tambah" id="modalTopTitle">Tambah @yield('title')</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Type Member Selection -->
                            <div class="mb-3">
                                <label for="type_member" class="form-label">Tipe Member &#42;</label>
                                <select class="form-select" id="type_member" name="type_member" required>
                                    @foreach ($categories as $category)
                                        @if (!Str::contains(strtolower($category->name), 'old'))
                                            <option value="{{ $category->id }}" data-harga="{{ $category->biaya }}">
                                                {{ $category->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="col mb-3">
                                <label for="name" class="form-label">Nama Member</label>
                                <input type="text" id="name" name="name" required class="form-control"
                                    placeholder="Nama Member" />
                            </div>
                            <div class="col mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input type="text" id="phone" name="phone" required class="form-control"
                                    placeholder="Nomor Telepon" />
                            </div>
                            <div class="col mb-3">
                                <label for="phone" class="form-label">Metode Pembayaran</label>
                                <select class="form-select" id="payment" name="payment" required>
                                    <option value="" disabled selected>Pilih Payment</option>
                                    <option value="TF">TF</option>
                                    <option value="CASH">CASH</option>
                                </select>
                            </div>
                            <div class="col mb-3">
                                <label for="price" class="form-label">Harga</label>
                                <input type="text" id="price" name="price" required class="form-control"
                                    placeholder="Harga" readonly />
                            </div>
                            <div class="col mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" id="email" name="email" class="form-control"
                                    placeholder="Email" />
                            </div>
                            <div class="col mb-3">
                                <label for="exp" class="form-label">Masa Berlaku</label>
                                <input type="date" id="exp" name="exp" required class="form-control" />
                            </div>
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
        <h5 class="card-header">Dashboard | @yield('title')</h5>
        <div class="card-body">
            <!-- Pencarian -->
            <div class="mb-3">
                <input type="text" id="searchInput" class="form-control" placeholder="Cari ID Member..." />
            </div>
            <div class="table-responsive text-nowrap">
                <table class="table" id="memberTable">
                    <thead class="table-dark">
                        <tr>
                            <th data-sort="category.name">Tipe Member</th>
                            <th data-sort="name">Nama Member</th>
                            <th data-sort="phone">Nomor Telepon</th>
                            <th data-sort="id">ID Member</th>
                            <th data-sort="remainingDays">Sisa Hari</th>
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
    <script>
        $(function() {
            // Function to load members into the table
            const invoiceRouteBase = "{{ url('invoice-member') }}";

            function loadMembers() {
                $.ajax({
                    url: '{{ url('/api/members') }}',
                    success: function({
                        data
                    }) {
                        const today = new Date();
                        data.forEach(val => {
                            val.remainingDays = Math.ceil((new Date(val.exp) - today) / (1000 *
                                3600 * 24));
                        });

                        renderTable(data);
                    }
                });
            }

            function renderTable(data) {
                let row = '';
                data.forEach(val => {
                    row += `
            <tr> 
                <td>${val.category.name}</td> 
                <td>${val.name}</td> 
                <td>${val.phone}</td> 
                <td>PG-${val.id}</td> 
                <td>${val.remainingDays} hari</td>
                <td>
                    <a data-toggle="modal" href="#modal-form" data-id="${val.id}" class="btn btn-warning modal-ubah">Edit</a>    
                    <a href="#" data-id="${val.id}" class="btn btn-danger btn-hapus">Hapus</a>    
                    <a href="#" data-id="${val.id}" class="btn btn-info btn-wa">WA</a>
                    <a href="#" data-id="${val.id}" class="btn btn-primary btn-email">Kirim Email</a>
                    <a href="${invoiceRouteBase}/${val.id}" target="_blank" class="btn btn-success btn-invoice">Invoice</a>
                </td>
            </tr>`;
                });

                $('tbody').html(row); // Update tbody content
            }

            // Pencarian
            $('#searchInput').on('keyup', function() {
                const searchValue = $(this).val().toLowerCase();
                $('tbody tr').filter(function() {
                    $(this).toggle($(this).find('td:eq(3)').text().toLowerCase().indexOf(
                        searchValue) > -1);
                });
            });

            // Sorting
            let currentSort = {
                key: null,
                asc: true
            };

            $('#memberTable th[data-sort]').on('click', function() {
                const sortKey = $(this).data('sort');
                const ascending = currentSort.key === sortKey ? !currentSort.asc : true;
                currentSort = {
                    key: sortKey,
                    asc: ascending
                };

                $.ajax({
                    url: '{{ url('/api/members') }}',
                    success: function({
                        data
                    }) {
                        const today = new Date();
                        data.forEach(val => {
                            val.remainingDays = Math.ceil((new Date(val.exp) - today) /
                                (1000 * 3600 * 24));
                        });

                        data.sort((a, b) => {
                            let aValue = getValueByKey(a, sortKey);
                            let bValue = getValueByKey(b, sortKey);

                            if (typeof aValue === 'string') aValue = aValue
                                .toLowerCase();
                            if (typeof bValue === 'string') bValue = bValue
                                .toLowerCase();

                            return ascending ? (aValue > bValue ? 1 : -1) : (aValue <
                                bValue ? 1 : -1);
                        });

                        renderTable(data);
                    }
                });
            });

            function getValueByKey(obj, key) {
                return key.split('.').reduce((o, i) => o[i], obj);
            }

            // Load members initially
            loadMembers();

            $('#searchInput').on('keyup', function() {
                const searchValue = $(this).val().toLowerCase();
                $('tbody tr').filter(function() {
                    $(this).toggle($(this).find('td:eq(3)').text().toLowerCase().indexOf(
                        searchValue) > -1) // Filter berdasarkan ID Member
                });
            });

            $(document).on('click', '.btn-wa', function() {
                const id = $(this).data('id');

                $.get(`/api/members/${id}`, function({
                    data
                }) {
                    const phoneNumber = data.phone;
                    const idMember = data.id;
                    const name = data.name;
                    const exp = data.exp;

                    const message = encodeURIComponent(
                        `Halo, ${name}!\n\n` +
                        `Terima kasih telah menjadi member kami di Prasasti Gym.\n` +
                        `ID Member Anda adalah: **PG-${idMember}**\n` +
                        `Tanggal Kedaluwarsa: **${exp}**\n\n` +
                        `Kami berharap Anda terus berlatih dengan semangat! 💪\n` +
                        `Jika ada pertanyaan atau butuh bantuan, jangan ragu untuk menghubungi kami.\n` +
                        `Terima kasih telah menjadi bagian dari keluarga Prasasti Gym!`
                    );

                    const whatsappUrl = `https://wa.me/${phoneNumber}?text=${message}`;

                    Swal.fire({
                        title: 'Yakin ingin mengirim pesan WhatsApp?',
                        text: `Pesan akan dikirim ke ${name}.`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, kirim!',
                        cancelButtonText: 'Batal',
                        backdrop: 'rgba(0,0,0,0.5)',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.open(whatsappUrl, '_blank');
                        }
                    });
                });
            });

            $(document).on('click', '.btn-email', function() {
                const id = $(this).data('id');

                $.get(`/api/members/${id}`, function({
                    data
                }) {
                    const email = data.email;
                    const name = data.name;
                    const idMember = data.id;
                    const expDate = data.exp;

                    const message = `Halo, ${name}!\n\n` +
                        `Terima kasih telah menjadi member kami di Prasasti Gym.\n` +
                        `ID Member Anda adalah: **PG-${idMember}**\n` +
                        `Tanggal Kedaluwarsa: **${expDate}**\n\n` +
                        `Kami berharap Anda terus berlatih dengan semangat! 💪\n` +
                        `Jika ada pertanyaan atau butuh bantuan, jangan ragu untuk menghubungi kami.\n` +
                        `Terima kasih telah menjadi bagian dari keluarga Prasasti Gym!`
                    console.log(message);

                    if (!email) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Email member tidak tersedia!',
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Yakin ingin mengirim email?',
                        text: `Email akan dikirim ke ${name}.`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, kirim!',
                        cancelButtonText: 'Batal',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: `/api/send-email`,
                                method: 'POST',
                                data: {
                                    email,
                                    message
                                },
                                success: function(response) {
                                    if (response.success) {
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Sukses',
                                            text: 'Email berhasil dikirim!',
                                        });
                                    } else {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Gagal',
                                            text: 'Email gagal dikirim!',
                                        });
                                    }
                                }
                            });
                        }
                    });
                });
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
                            url: '/api/members/' + id,
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
                                        loadMembers
                                            (); // Load data member setelah penghapusan
                                    });
                                }
                            }
                        });
                    }
                });
            });

            // Update harga ketika kategori dipilih
            $('#type_member').change(function() {
                var harga = $(this).find(':selected').data('harga');
                $('#price').val(harga);
            });

            $('.modal-tambah').click(function() {
                $('#modal-form').modal('show');

                // Reset nilai field pada modal untuk penambahan member baru
                $('select[name="type_member"]').val('');
                $('input[name="name"]').val('');
                $('input[name="phone"]').val('');
                $('select[name="payment"]').val('');
                $('#price').val('');
                $('input[name="email"]').val('');
                $('input[name="exp"]').val('');
            });

            $('.form-member').submit(function(e) {
                e.preventDefault();
                const token = localStorage.getItem('token');
                const frmdata = new FormData(this);
                const id = $(this).find('input[name="id"]').val(); // Ambil ID jika ada

                let url = 'api/members';
                let method = 'POST';

                if (id) {
                    url = `api/members/${id}?_method=PUT`; // Jika ada ID, berarti ini adalah update
                    method = 'POST';
                }

                $.ajax({
                    url: url,
                    type: method,
                    data: frmdata,
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        "Authorization": "Bearer " + token
                    },
                    success: function(data) {
                        $('#modal-form').modal('hide');
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Sukses',
                                text: (id ? 'Data berhasil diubah!' :
                                    'Data berhasil ditambah!'),
                                backdrop: 'rgba(0,0,0,0.5)',
                            }).then(() => {
                                loadMembers
                                    ();
                            });
                        }
                    }
                });
            });

            $(document).on('click', '.modal-ubah', function() {
                $('#modal-form').modal('show');
                const id = $(this).data('id');

                $.get('api/members/' + id, function({
                    data
                }) {
                    $('select[name="type_member"]').val(data.type_member);
                    $('input[name="name"]').val(data.name);
                    $('input[name="phone"]').val(data.phone);
                    $('input[name="email"]').val(data.email);
                    $('#price').val('');
                    $('select[name="payment"]').val(data.payment);
                    $('input[name="exp"]').val(data.exp);
                });

                $('.form-member').off('submit').on('submit', function(e) {
                    e.preventDefault();
                    const token = localStorage.getItem('token');

                    const frmdata = new FormData(this);

                    $.ajax({
                        url: `api/members/${id}?_method=PUT`,
                        type: 'POST',
                        data: frmdata,
                        cache: false,
                        contentType: false,
                        processData: false,
                        headers: {
                            "Authorization": "Bearer " + token
                        },
                        success: function(data) {
                            $('#modal-form').modal('hide');
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
