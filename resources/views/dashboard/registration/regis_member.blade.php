@extends('dashboard.layout.index')

@section('title', 'Pesanan Baru')

@section('container')

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Basic Layout -->
            <div class="row">
                <div class="col-xl">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Input Member</h5>
                            <small class="text-muted float-end">Merged input group</small>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label" for="member-id-input">ID Member</label>
                                <div class="input-group input-group-merge">
                                    <span class="input-group-text">
                                        <i class="bx bx-user"></i>
                                    </span>
                                    <input type="text" id="member-id-input" class="form-control"
                                        placeholder="Enter Member ID" aria-label="Member ID" />
                                </div>
                            </div>
                            <button id="search-btn" type="button" class="btn btn-primary">Search</button>

                            <!-- Tabel hasil pencarian -->
                            <div id="result-card" class="card mt-3" style="display:none;">
                                <h5 class="card-header">Member Details</h5>
                                <div class="table-responsive text-nowrap">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Nama</th>
                                                <th>ID Member</th>
                                                <th>Type Member</th>
                                                <th>Exp</th>
                                            </tr>
                                        </thead>
                                        <tbody id="table-body" class="table-border-bottom-0">
                                            <!-- Data member akan dimasukkan di sini -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Hasil pencarian tidak ditemukan -->
                            <div id="search-result" class="mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Content -->
    </div>

@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#search-btn').on('click', function() {
                var memberId = $('#member-id-input').val().trim(); // Trim whitespace

                if (memberId) {
                    $.ajax({
                        url: '/search-member', // URL endpoint untuk pencarian member di server
                        type: 'GET',
                        data: {
                            id: memberId // Mengirim nilai ID Member ke server
                        },
                        success: function(response) {
                            // Kosongkan pesan error sebelumnya
                            $('#search-result').empty();

                            // Calculate remaining days until expiration
                            const today = new Date();
                            const expDate = new Date(response.exp);

                            // Menghitung selisih hari
                            const timeDiff = expDate.getTime() - today.getTime();
                            const remainingDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

                            // Tampilkan card dan isi tabel dengan data member
                            $('#result-card').show();
                            $('#table-body').html(`
                                <tr>
                                    <td><strong>${response.name}</strong></td>
                                    <td>${response.id_member}</td>
                                    <td>${response.type_member}</td>
                                    <td>${remainingDays} days remaining</td>
                                </tr>
                            `);
                        },
                        error: function() {
                            // Sembunyikan tabel dan tampilkan pesan error
                            $('#result-card').hide();
                            $('#search-result').html(`
                                <div class="alert alert-danger">
                                    ID Member tidak ditemukan!
                                </div>
                            `);
                        }
                    });
                } else {
                    $('#result-card').hide();
                    $('#search-result').html(`
                        <div class="alert alert-warning">
                            Please enter a Member ID.
                        </div>
                    `);
                }
            });
        });
    </script>
@endpush
