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

            <div class="row mb-3">
                <div class="col-lg-5">
                    <label for="start-date" class="form-label">Tanggal Mulai</label>
                    <input type="date" id="start-date" class="form-control">
                </div>
                <div class="col-lg-5">
                    <label for="end-date" class="form-label">Tanggal Akhir</label>
                    <input type="date" id="end-date" class="form-control">
                </div>
                <div class="col-lg-2 d-flex align-items-end">
                    <button id="btn-search" class="btn btn-primary">Cari</button>
                </div>
            </div>

            <div class="mb-3">
                <button id="export-excel" class="btn btn-success">Export to Excel</button>
                <button id="export-pdf" class="btn btn-danger">Export to PDF</button>
            </div>

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
                                    <input type="text" id="id_member" name="id_member" required class="form-control"
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
        <h5 class="card-header">Data Registrasi | @yield('title')</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th>Nama</th>
                        <th>ID Member</th>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        $(document).ready(function() {
            let allData = []; // Deklarasi allData di sini

            // Mengambil data member dari API
            $.ajax({
                url: '/api/member-reports',
                method: 'GET', // Pastikan metode GET
                success: function({
                    data
                }) {
                    allData = data; // Simpan semua data untuk pemfilteran dan ekspor
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
                            <td>${val.id_member}</td> 
                            <td>${val.category.name}</td> 
                            <td>${val.harga}</td> 
                            <td>${formattedDate}${expired}</td> 
                        </tr>`;
                });
                $('tbody').html(row); // Ganti konten tabel dengan baris yang baru
            }

            // Fungsi untuk mencari data berdasarkan rentang tanggal
            $('#btn-search').click(function() {
                const startDate = $('#start-date').val();
                const endDate = $('#end-date').val();

                // Panggil API dan kirim tanggal yang dipilih
                $.ajax({
                    url: '/api/member-reports',
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

            // Fungsi untuk mengekspor data ke Excel
            $('#export-excel').click(function() {
                const startDate = $('#start-date').val();
                const endDate = $('#end-date').val();

                let filteredData = allData;

                // Jika rentang tanggal sudah diisi, filter data
                if (startDate && endDate) {
                    filteredData = allData.filter(val => {
                        const createdDate = new Date(val.created_at);
                        const endOfDay = new Date(endDate);
                        endOfDay.setHours(23, 59, 59, 999);

                        return createdDate >= new Date(startDate) && createdDate <= endOfDay;
                    });
                }

                const ws = XLSX.utils.json_to_sheet(filteredData.map(val => ({
                    Nama: val.nama,
                    IDMember: val.id_member,
                    Kategori: val.category.name,
                    Harga: val.harga,
                    Tanggal: new Date(val.created_at).toLocaleDateString('id-ID')
                })));

                const wb = XLSX.utils.book_new();
                XLSX.utils.book_append_sheet(wb, ws, "Data Non Member");

                // Simpan file
                XLSX.writeFile(wb, 'data_non_member.xlsx');
            });

            // Fungsi untuk mengekspor data ke PDF
            $('#export-pdf').click(function() {
                const {
                    jsPDF
                } = window.jspdf;
                const doc = new jsPDF();

                const startDate = $('#start-date').val();
                const endDate = $('#end-date').val();

                let filteredData = allData;

                // Jika rentang tanggal sudah diisi, filter data
                if (startDate && endDate) {
                    filteredData = allData.filter(val => {
                        const createdDate = new Date(val.created_at);
                        const endOfDay = new Date(endDate);
                        endOfDay.setHours(23, 59, 59, 999);

                        return createdDate >= new Date(startDate) && createdDate <= endOfDay;
                    });
                }

                // Header
                doc.setFontSize(12);
                doc.text("Data Non Member", 14, 10);

                const startY = 20; // Y position for the first row
                const rowHeight = 10; // Height of each row
                const columns = ['Nama', 'ID Member', 'Kategori', 'Harga', 'Tanggal'];

                // Header table
                columns.forEach((header, index) => {
                    doc.text(header, 14 + index * 40, startY); // Adjust spacing based on index
                });

                // Data rows
                filteredData.forEach((val, index) => {
                    const createdDate = new Date(val.created_at).toLocaleDateString('id-ID');
                    const dataRow = [val.nama, val.id_member, val.category.name, val.harga,
                        createdDate
                    ];

                    dataRow.forEach((data, colIndex) => {
                        // Pastikan posisi Y yang sesuai untuk setiap baris
                        const yPos = startY + (index + 1) * rowHeight;
                        doc.text(data.toString(), 14 + colIndex * 40, yPos);
                    });
                });

                // Simpan file
                doc.save('data_non_member.pdf');
            });


            $('#btn-cari-member').click(function() {
                const idMember = $('#id_member').val();

                $.ajax({
                    url: `/api/member/search?id_member=${idMember}`,
                    method: 'GET',
                    success: function(response) {
                        if (response) {
                            $('#nama').val(response.name);
                            $('#kategori').val(response.type_member);
                            $('#harga').val(response.harga);

                            // Cek tanggal exp
                            const expDate = new Date(response.exp);
                            const today = new Date();
                            if (expDate < today) {
                                $('#expired-notification').removeClass(
                                    'd-none'); // Tampilkan notifikasi expired
                                // Tampilkan alert SweetAlert
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Member Expired',
                                    text: 'Member sudah expired!',
                                    backdrop: 'rgba(0,0,0,0.5)', // Atur backdrop jika ingin
                                }).then(() => {
                                    $('#modal-form').modal(
                                        'hide'
                                    ); // Tutup modal setelah alert ditampilkan
                                });
                            } else {
                                $('#expired-notification').addClass(
                                    'd-none'); // Sembunyikan notifikasi
                            }
                        }
                    },
                    error: function(jqXHR) {
                        let errorMessage;

                        if (jqXHR.status === 404) {
                            errorMessage = 'Member tidak ditemukan. Pastikan ID benar.';
                        } else {
                            errorMessage =
                                'Terjadi kesalahan saat menghubungi server. Silakan coba lagi nanti.';
                        }

                        // Tampilkan alert SweetAlert
                        Swal.fire({
                            icon: 'error',
                            title: 'Kesalahan',
                            text: errorMessage,
                            backdrop: 'rgba(0,0,0,0.5)', // Atur backdrop jika ingin
                        }).then(() => {
                            $('#modal-form').modal(
                                'hide'); // Tutup modal setelah menampilkan alert
                        });

                        $('#nama, #kategori, #harga').val(
                            ''); // Reset input jika terjadi kesalahan
                        $('#expired-notification').addClass('d-none'); // Sembunyikan notifikasi
                    }
                });
            });


            // Reset nilai field pada modal untuk penambahan member baru
            $('.modal-tambah').click(function() {
                $('#modal-form').modal('show');
                $('#id_member, #nama, #kategori, #harga').val('');
                $('#expired-notification').addClass('d-none'); // Sembunyikan notifikasi saat reset
            });

            // Proses Submit Form
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
                            // Sembunyikan modal terlebih dahulu
                            $('#modal-form').modal('hide');

                            // Tampilkan SweetAlert
                            Swal.fire({
                                icon: 'success',
                                title: 'Data berhasil ditambahkan',
                                text: 'Member baru telah ditambahkan.',
                                backdrop: 'rgba(0,0,0,0.5)', // Atur backdrop jika ingin
                            }).then(() => {
                                location
                                    .reload(); // Reload halaman setelah alert ditutup
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
                            backdrop: 'rgba(0,0,0,0.5)', // Atur backdrop jika ingin
                        });
                    }
                });
            });
        });
    </script>
@endpush
