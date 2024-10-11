@extends('dashboard.layout.index')

@section('title', 'Payment')

@section('container')

    <!-- Bootstrap Table with Header - Dark -->
    <!-- Slide from Top Modal -->
    <div class="col-lg-4 col-md-6">
        <div class="mt-3">
            <!-- Button trigger modal -->

            <!-- Modal -->
            <div class="modal modal-top fade" id="modal-form" tabindex="-1">
                <div class="modal-dialog">
                    <form class="form-kategori modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title modal-tambah" id="modalTopTitle">Form Pembayaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameSlideTop" class="form-label">Tanggal</label>
                                    <input type="text" class="form-control" name="tanggal" placeholder="Tanggal"
                                        readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameSlideTop" class="form-label">No Rekening</label>
                                    <input type="text" class="form-control" name="no_rekening"
                                    placeholder="No Rekening" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameSlideTop" class="form-label">Jumlah</label>
                                    <input type="text" class="form-control" name="jumlah"
                                    placeholder="Jumlah" readonly>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col mb-3">
                                    <label for="nameSlideTop" class="form-label">Atas Nama</label>
                                    <input type="text" class="form-control" name="atas_nama"
                                    placeholder="Atas Nama" readonly>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="DITERIMA">DITERIMA</option>
                                    <option value="DITOLAK">DITOLAK</option>
                                    <option value="MENUNGGU">MENUNGGU</option>
                                </select>
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
                        <th>Tanggal</th>
                        <th>Order</th>
                        <th>Jumlah</th>
                        <th>No Rekening</th>
                        <th>Atas Nama</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody class="table-border-bottom-10">

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
                url: '/api/payments',
                success: function({
                    data
                }) {

                    let row;
                    data.map(function(val, index) {
                        tgl = new Date(val.created_at)
                        tgl_lengkap = `${tgl.getDate()}-${tgl.getMonth()+1}-${tgl.getFullYear()}`
                        row += `
                        <tr> 
                            <td> ${tgl_lengkap} </td> 
                            <td> ${val.id_order} </td> 
                            <td> ${val.jumlah} </td> 
                            <td> ${val.no_rekening} </td> 
                            <td> ${val.atas_nama} </td> 
                            <td> ${val.status} </td> 
                            <td>
                                <a  data-toogle="modal" href="#modal-form" data-id="${val.id}" class="btn btn-warning modal-ubah">Edit </a>        
                            </td>
                        </tr>`;
                    });

                    $('tbody').append(row);
                }
            });

            function date(date) {
                var date = new Date(date)
                var day = date.getDate();
                var month = date.getMonth() + 1;
                var year = date.getFullYear();

                return `${day}-${month}-${year}`;
            }

            $(document).on('click', '.modal-ubah', function() {
                $('#modal-form').modal('show')
                const id = $(this).data('id');

                $.get('api/payments/' + id, function({
                    data
                }) {
                    $('input[name="tanggal"]').val(date(data.created_at));
                    $('input[name="no_rekening"]').val(data.no_rekening);
                    $('input[name="jumlah"]').val(data.jumlah);
                    $('input[name="atas_nama"]').val(data.atas_nama);
                    $('select[name="status"]').val(data.status);
                })

                $('.form-kategori').submit(function(e) {
                    e.preventDefault()
                    const token = localStorage.getItem('token')

                    const frmdata = new FormData(this);

                    $.ajax({
                        url: `api/payments/${id}?_method=PUT`,
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
