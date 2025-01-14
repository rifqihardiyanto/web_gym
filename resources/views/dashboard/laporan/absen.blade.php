@extends('dashboard.layout.index')

@section('title', 'Absen')

@section('container')

    <div class="card">
        <h5 class="card-header">@yield('title')</h5>
        <div class="card-body">
            <!-- Input untuk rentang tanggal -->
            <div class="row mb-4">
                <div class="col-md-6 mb-3">
                    <label for="start_date" class="form-label">Tanggal Mulai:</label>
                    <input type="date" id="start_date" name="start_date" class="form-control"
                        value="{{ request('start_date') }}">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="end_date" class="form-label">Tanggal Akhir:</label>
                    <input type="date" id="end_date" name="end_date" class="form-control"
                        value="{{ request('end_date') }}">
                </div>
            </div>

            <div class="d-flex mb-3">
                <!-- Tombol untuk menerapkan filter -->
                <button type="button" class="btn btn-primary me-2" id="apply-filter">Filter</button>

                <!-- Tombol untuk ekspor data -->
                <!-- Tombol untuk ekspor data -->
                <button type="button" class="btn btn-success" id="export-btn"
                    onclick="window.location.href='{{ route('absen.export') }}?start_date=' + new URLSearchParams(window.location.search).get('start_date') + '&end_date=' + new URLSearchParams(window.location.search).get('end_date')">Ekspor</button>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Jam Absen</th>
                            <th>Nama</th>
                            <th>ID Member</th>
                            <th>Kategori</th>
                            <th>Payment</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($allReports as $report)
                            <tr>
                                <td>{{ $report['jam_absen'] }} WIB</td>
                                <td>{{ $report['nama'] }}</td>
                                <td>{{ $report['id_member'] }}</td>
                                <td>{{ $report['kategori'] }}</td>
                                <td>{{ $report['payment'] }}</td>
                                <td>{{ $report['harga'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5" class="text-end"><strong>Total Harga</strong></td>
                            <td><strong>{{ number_format($totalHarga, 0, ',', '.') }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>

@endsection

@push('js')
    <script>
        document.getElementById('apply-filter').addEventListener('click', function() {
            // Ambil nilai tanggal
            var startDate = document.getElementById('start_date').value;
            var endDate = document.getElementById('end_date').value;

            // Redirect atau kirim permintaan dengan parameter tanggal
            window.location.href = "{{ route('absen') }}?start_date=" + startDate + "&end_date=" + endDate;
        });
    </script>
@endpush
