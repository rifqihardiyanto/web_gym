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
                            <td> ${val.harga} </td> 
                            <td> ${formattedDate} </td> 
                        </tr>`;
                });

                $('tbody').html(row);
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
                const startDate = $('#start-date').val();
                const endDate = $('#end-date').val();

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
                const columns = ['Nama', 'Kategori', 'Harga', 'Tanggal'];

                // Header table
                columns.forEach((header, index) => {
                    doc.text(header, 14 + index * 50, startY); // Adjust spacing based on index
                });

                // Data rows
                filteredData.forEach((val, index) => {
                    const createdDate = new Date(val.created_at).toLocaleDateString('id-ID');
                    const dataRow = [val.nama, val.category.name, val.harga, createdDate];

                    dataRow.forEach((data, colIndex) => {
                        doc.text(data.toString(), 14 + colIndex * 50, startY + (index + 1) *
                            rowHeight);
                    });
                });

                // Simpan file
                doc.save('data_non_member.pdf');
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
