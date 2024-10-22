@extends('dashboard.layout.index')
@section('title', 'Index')

@section('container')
    <!-- Style variation -->
    <h5 class="pb-1 mb-4">Dashboard</h5>
    <div class="row">
        <div class="col-md-6 col-xl-4">
            <div class="card bg-primary text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title text-white">Daftar Member</h5>
                    <p class="card-text">Klik disini untuk mendaftarkan member baru.</p>
                    <a href="{{ url('member') }}" class="btn rounded-pill btn-dark">Daftar Member</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card bg-info text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title text-white">Registrasi Member</h5>
                    <p class="card-text">Klik disini untuk melakukan absen member.</p>
                    <a href="{{ url('member-reports') }}" class="btn rounded-pill btn-dark">Absen</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 col-xl-4">
            <div class="card bg-success text-white mb-3">
                <div class="card-body">
                    <h5 class="card-title text-white">Registrasi Non Member</h5>
                    <p class="card-text">Klik disini untuk melakukan absen non member.</p>
                    <a href="{{ url('non-member-reports') }}" class="btn rounded-pill btn-dark">Absen</a>
                </div>
            </div>
        </div>
    </div>
    <!--/ Style variation -->
@endsection
