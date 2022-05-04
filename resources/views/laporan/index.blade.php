


@extends('main')


@push('styles')

<style>
.bg-ditolak{
    background: #ED1F24 !important;
}

.bg-diproses{
    background: #F3EC17 !important;
}
.bg-disetujui{
    background: #70BF44 !important;
}
.bg-selesai{
    background: #050505 !important;
}
.avatar-sm{
    height: 1.2rem !important;
    width: 1.2rem !important;
}
.clickable-row{
    cursor: pointer;
}
.clickable-row:hover{
    background: #0bb9795b !important;
}

.table-laporan{
    /* width: 100%; */
      border: 1px solid;
      border-collapse: collapse;

  border: 0.5px solid black;

}
.table-laporan, td, th {
  border: 0.5px solid black;
  padding: 5px;
}
.table-laporan,thead th {
  background: #92D050;
  color: black;
  font-weight: bold;
}
.table-laporan,tbody td {
  background: transparent;
  color: black;
  font-weight: normal;
    font-size: 10.5px;
    padding: 1px 5px  !important;
}

.table-laporan th{
    font-size: 11px;
    border: 0.5px solid black !important;
        vertical-align: top;
    text-align: center;
}
.table-laporan thead {
  position: sticky;
  top: 0; /* Don't forget this, required for the stickiness */
  box-shadow: 0 2px 2px -1px rgba(0, 0, 0, 0.4);
}

tr:nth-child(even) {
  background-color: lightgray;
}



</style>

@endpush
@section('content')
<div class="page-content">

<!-- end page title -->
<div class="row animate__animated  animate__fadeIn">
    <div class="col-lg-12">
        <div class="card shadow-lg">
            <div class="card-header justify-content-between d-flex align-items-center">
                <h4 class="card-title">{{$page_title}}</h4>
            </div>
            <div class="card-body">
                <div class="col-12">
                    @include('components.flash-message')
                </div>
                <div class="col-lg-12">
                    <a href="{{route('laporan.export-excel')}}">
                        <button class="btn btn-success mb-3" style="background: #217346;">
                            <i class="fa fa-file-excel"></i>
                            Export Excel</button>
                    </a>
                    <div class="table-responsive" style="overflow: auto;max-height:450px;">
                       <table class="table-laporan">
                           <thead>
                               <tr>
                                   <th rowspan="2">NO</th>
                                   <th rowspan="2">KODE</th>
                                   <th rowspan="2">URAIAN</th>
                                   <th rowspan="2">TAHUN</th>


                                   <th colspan="3" class="text-center">NILAI</th>
                                   <th colspan="2" class="text-center">SALDO AKHIR</th>


                                   <th rowspan="2">POSISI BARANG</th>

                                   <th colspan="12" class="text-center">PERUNTUKKAN</th>


                               </tr>
                               <tr>
                                   <th>SATUAN</th>
                                   <th>HARGA</th>
                                   <th>MATA UANG</th>
                                    <th>STOCK</th>
                                   <th>JUMLAH TOTAL</th>
                                   <th>UMUM</th>
                                   <th>SBNP</th>
                                   <th>TELKOMPEL</th>
                                   <th>PENGLA</th>
                                   <th>KNK</th>
                                   <th>BENGKEL</th>
                                   <th>SIE KEPEG & UMUM</th>
                                   <th>SIE KEUANGAN</th>
                                   <th>PENGADAAN</th>
                                   <th>INVENTARIS</th>
                                   <th>SIE SARPRAS</th>
                                   <th>PROGRAM</th>
                               </tr>
                           </thead>
                           <tbody>
                               @foreach ($laporans as $item)
                                   <tr>
                                       <td>{{$loop->iteration}}</td>
                                       <td>{{$item['kode_barang']}}</td>
                                       <td>{{$item['nama_barang']}}</td>
                                       <td>{{$item['tahun_perolehan']}}</td>
                                       <td>{{$item['satuan']}}</td>
                                       <td>{{$item['harga_perolehan']}}</td>
                                       <td>{{$item['mata_uang']}}</td>
                                       <td>{{$item['stock']}}</td>
                                       <td>{{$item['jumlah_total']}}</td>
                                       <td>{{$item['posisi_barang']}}</td>

                                       <td style="{{ $item['umum'] > 0 ? 'color:green;font-weight:bolder;':''}}">{{$item['umum']}}</td>
                                       <td style="{{ $item['sbnp'] > 0 ? 'color:green;font-weight:bolder;':''}}">{{$item['sbnp']}}</td>
                                       <td style="{{ $item['telkompel'] > 0 ? 'color:green;font-weight:bolder;':''}}">{{$item['telkompel']}}</td>
                                       <td style="{{ $item['pengla'] > 0 ? 'color:green;font-weight:bolder;':''}}">{{$item['pengla']}}</td>
                                       <td style="{{ $item['knk'] > 0 ? 'color:green;font-weight:bolder;':''}}">{{$item['knk']}}</td>
                                       <td style="{{ $item['bengkel'] > 0 ? 'color:green;font-weight:bolder;':''}}">{{$item['bengkel']}}</td>

                                       <td style="{{ $item['siekepeg'] > 0 ? 'color:green;font-weight:bolder;':''}}">{{$item['siekepeg']}}</td>
                                       <td style="{{ $item['siekeuangan'] > 0 ? 'color:green;font-weight:bolder;':''}}">{{$item['siekeuangan']}}</td>
                                       <td style="{{ $item['siepengadaan'] > 0 ? 'color:green;font-weight:bolder;':''}}">{{$item['siepengadaan']}}</td>
                                       <td style="{{ $item['sieinventaris'] > 0 ? 'color:green;font-weight:bolder;':''}}">{{$item['sieinventaris']}}</td>
                                       <td style="{{ $item['sarpras'] > 0 ? 'color:green;font-weight:bolder;':''}}">{{$item['sarpras']}}</td>
                                       <td style="{{ $item['sieprogram'] > 0 ? 'color:green;font-weight:bolder;':''}}">{{$item['sieprogram']}}</td>



                                   </tr>
                               @endforeach
                           </tbody>
                       </table>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<!-- end row -->

</div> <!-- container-fluid -->
</div>
@endsection

@push('scripts')
<script>
    $('#data-table').DataTable({
        //   "pageLength": 3
        paging:true

    });

    $("#data-table").on("click", ".clickable-row", function(){
        window.location = $(this).data("href");
    });



</script>
@endpush
