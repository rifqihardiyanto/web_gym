@extends('dashboard.layout.index')

@section('title', 'Member')

@section('container')

    <div class="card">
        <h5 class="card-header">@yield('title')</h5>
        <div class="card-body">
            <!-- Input untuk rentang tanggal -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">Tanggal Mulai:</label>
                    <input type="date" id="start_date" class="form-control" placeholder="Pilih Tanggal Mulai">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">Tanggal Akhir:</label>
                    <input type="date" id="end_date" class="form-control" placeholder="Pilih Tanggal Akhir">
                </div>
            </div>

            <div class="d-flex mb-3">
                <!-- Tombol untuk menerapkan filter -->
                <button type="button" class="btn btn-primary me-2" id="apply-filter">Filter</button>

                <!-- Tombol untuk ekspor data -->
                <button type="button" class="btn btn-success" id="export-btn">Ekspor</button>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal Daftar</th>
                            <th>Nama</th>
                            <th>ID Member</th>
                            <th>Tipe Member</th>
                            <th>Metode Pembayaran</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($daftarMember as $member)
                            <tr>
                                <td>{{ $member->created_at->format('Y-m-d') }}</td>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->id_member }}</td>
                                <td>{{ $member->category->name }}</td>
                                <td>{{ $member->payment }}</td>
                                <td>{{ $member->category->biaya }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>

@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#apply-filter').click(function() {
                // Ambil nilai dari input tanggal
                var startDate = $('#start_date').val();
                var endDate = $('#end_date').val();

                // Buat URL untuk request dengan filter tanggal
                var url = "{{ url('daftar-member') }}?start_date=" + startDate + "&end_date=" + endDate;

                // Reload halaman dengan filter
                window.location.href = url;
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

                    window.location.href = "{{ url('export-daftar-member') }}?start_date=" +
                        startDate + "&end_date=" + endDate;
                });
            });

        });
    </script>
@endpush
