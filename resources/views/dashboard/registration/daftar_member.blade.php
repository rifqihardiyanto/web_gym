@extends('dashboard.layout.index')

@section('title', 'Member')

@section('container')

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
