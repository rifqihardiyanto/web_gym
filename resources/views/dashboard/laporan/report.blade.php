@extends('dashboard.layout.index')

@section('title', 'Reports')

@section('container')

    <!-- Bootstrap Table with Header - Dark -->
    <!-- HTML5 Inputs -->
    <div class="row g-2">
        <form>
            <div class="col mb-3">
                <label for="html5-date-input" class="col-md-2 col-form-label">Dari</label>
                <div class="col-md-4">
                    <input class="form-control" type="date" value="{{ request()->input('dari') }}" name="dari"
                        id="dari" />
                </div>
            </div>
            <div class="col mb-3">
                <label for="html5-date-input" class="col-md-2 col-form-label">Sampai</label>
                <div class="col-md-4">
                    <input class="form-control" type="date" value="{{ request()->input('sampai') }}" name="sampai"
                        id="sampai" />
                </div>
            </div>
            <div class="form-group mb-3">
                <button type="submit" class="btn btn-primary btn-block">Submit</button>
            </div>
        </form>
    </div>

    <div class="card">
        <h5 class="card-header">Dashboard | @yield('title')</h5>
        <div class="table-responsive text-nowrap">
            <table class="table">
                <thead class="table-dark">
                    <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah Dibeli</th>
                        <th>Harga</th>
                        <th>Total Qty</th>
                        <th>Pendapatan</th>
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
        const dari = '{{ request()->input('dari') }}'
        const sampai = '{{ request()->input('sampai') }}'

        $(function() {
            function rupiah(angka) {
                const format = angka.toString().split('').reverse().join('');
                const convert = format.match(/\d{1,3}/g);
                return 'Rp ' + convert.join('.').split('').reverse().join('');
            }
            const token = localStorage.getItem('token');
            $.ajax({
                url: `/api/reports?dari=${dari}&sampai=${sampai}`,
                headers: {
                    "Authorization": "Bearer" + token
                },
                success: function({
                    data
                }) {

                    let row;
                    data.map(function(val, index) {
                        row += `
                        <tr>   
                            <td> ${val.nama_produk} </td> 
                            <td> ${val.jumlah_dibeli} </td> 
                            <td> ${rupiah(val.harga)} </td> 
                            <td> ${val.total_qty} </td> 
                            <td> ${rupiah(val.pendapatan)} </td> 
                        </tr>`;
                    });

                    $('tbody').append(row);
                }
            });
        });
    </script>
@endpush
