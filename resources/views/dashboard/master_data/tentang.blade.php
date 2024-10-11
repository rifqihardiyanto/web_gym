@extends('dashboard.layout.index')

@section('title', 'Tentang')

@section('container')

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-2"><span class="text-muted fw-light">Form</span> Update tentang Website</h4>
            @if (session()->has('success'))
                <div style="color: green" class="alert alert-success">
                    {{ session()->get('success') }}
                </div>
            @endif

            <!-- Basic Layout -->
            <div class="row">
                <div class="col-xl">
                    <div class="card mb-4">
                        <div class="card-body">
                            <form action="/tentang/{{ $about->id }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-fullname">Judul</label>
                                    <input type="text" class="form-control" id="basic-default-fullname"
                                        placeholder="John Doe" name="judul_website" value="{{ $about->judul_website }}" />
                                </div>
                                <img src="/uploads/{{ $about->logo }}" alt="" width="200" class="mb-3">
                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Logo</label>
                                    <input class="form-control" type="file" id="formFile" name="logo" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-company">Deskripsi</label>
                                    <textarea type="text" class="form-control" id="basic-default-company" placeholder="ACME Inc." name="deskripsi">{{ $about->deskripsi }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-company">Alamat</label>
                                    <textarea type="text" class="form-control" id="basic-default-company" placeholder="ACME Inc." name="alamat">{{ $about->alamat }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-email">Email</label>
                                    <div class="input-group input-group-merge">
                                        <input type="text" id="basic-default-email" class="form-control"
                                            placeholder="john.doe" aria-label="john.doe"
                                            aria-describedby="basic-default-email2" name="email"
                                            value="{{ $about->email }}" />
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-phone">No Telepon</label>
                                    <input type="text" id="basic-default-phone" class="form-control phone-mask"
                                        placeholder="658 799 8941" name="telepon" value="{{ $about->telepon }}" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-phone">Atas Nama</label>
                                    <input type="text" id="basic-default-phone" class="form-control phone-mask"
                                        placeholder="Atas Nama" name="atas_nama" value="{{ $about->atas_nama }}" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="basic-default-phone">No Rekening</label>
                                    <input type="text" id="basic-default-phone" class="form-control phone-mask"
                                        placeholder="No Rekening" name="no_rekening" value="{{ $about->no_rekening }}" />
                                </div>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- / Content -->

    @endsection
