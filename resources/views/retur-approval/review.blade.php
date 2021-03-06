

@extends('main')


@push('styles')
<!-- swiper css -->
<link rel="stylesheet" href="{{asset('assets/libs/swiper/swiper-bundle.min.css')}}">
<style>
    .bg-ditolak {
        background: #ED1F24 !important;
    }

    .bg-diproses {
        background: #F3EC17 !important;
    }

    .bg-disetujui {
        background: #70BF44 !important;
    }

    .bg-selesai {
        background: #050505 !important;
    }

    .avatar-sm {
        height: 1.2rem !important;
        width: 1.2rem !important;
    }

    .clickable-row {
        cursor: pointer;
    }

    .clickable-row:hover {
        background: #0bb9795b !important;
    }

    .hori-timeline .event-list::before {
        background-color: #70BF44 !important;
    }

    .hori-timeline .event-list:after {
        width: 30px !important;
        height: 30px !important;
        background-color: #0BB97A !important;
        border: 5px solid #fff;
        border-radius: 50%;
        top: -7px !important;
        left: 11% !important;
        -webkit-transform: translateX(-50%);
        transform: translateX(-50%);
        display: block;
    }

    .font-size-14 {
        font-size: 11px !important;
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
                    <h4 class="card-title">{{$page_title}}
                        @if (optional($data->lastProcess())->role_to_name == Auth::user()->role->name)
                            <span class="noti-dotnya bg-danger"> ! </span>
                        @else

                        @endif
                    </h4>



                </div>
                <div class="card-body">
                    <div class="col-12">
                        @include('components.flash-message')
                    </div>
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="d-block mb-3">
                                    <h5 class="mb-2">{{$data->nomor_retur}} | {{date('d F Y',strtotime($data->timestamp))}}</h5>
                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <span class="d-block text-success">Perihal</span>
                                            <span class="d-block">{{$data->perihal}}</span>

                                        </div>

                                    </div>
                                    <div class="row ">

                                         <div class="col-lg-4">
                                            <span class="d-block text-success">Alasan Retur</span>
                                            <span class="d-block">{{$data->alasan_retur}}</span>
                                        </div>
                                         <div class="col-lg-4">
                                            <span class="d-block text-success">Dari Nota Dinas</span>
                                            @foreach ($nota_dinas as $item)
                                                <span class="d-block">{{$item}}</span>

                                            @endforeach
                                        </div>
                                         <div class="col-lg-4">
                                            <span class="d-block text-success">Dari UPP4</span>
                                            @foreach ($upp4 as $item)
                                                <span class="d-block">{{$item}}</span>

                                            @endforeach
                                        </div>
                                         <div class="col-lg-12">
                                            <span class="d-block text-success">Keterangan</span>
                                            <span class="d-block">{{$data->keterangan}}</span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 offset-lg-2">
                                <div class="text-end">
                                    @if (optional($data->lastProcess())->role_to_name == Auth::user()->role->name)
                                            @if (Auth::user()->role->name == 'Pengelola Gudang')
                                                @if (optional($data->lastProcess())->type =='Menunggu Barang Diterima Pengelola Gudang')
                                                    <button class="btn btn-lg btn-success " data-bs-toggle="modal" data-bs-target="#myModal">TERIMA BARANG</button>
                                                @else
                                                    <button class="btn btn-lg btn-success " data-bs-toggle="modal" data-bs-target="#myModal">SIAP MENERIMA</button>
                                                @endif
                                            @else
                                                <button class="btn btn-lg btn-success " data-bs-toggle="modal" data-bs-target="#myModal">TINDAK LANJUT</button>
                                            @endif
                                    @else
                                        @if (optional($data->lastProcess())->tindak_lanjut == 'SETUJUI' || optional($data->lastProcess())->tindak_lanjut == 'UPDATE')
                                        <h1 class="text-success">DISETUJUI</h1>
                                        @elseif (optional($data->lastProcess())->tindak_lanjut == 'TOLAK')
                                            <h1 class="text-danger">DITOLAK</h1>
                                        @endif
                                        {{-- <button class="btn btn-sm btn-success " data-bs-toggle="modal" data-bs-target="#myModalUpdate" disabled>EDIT TINDAK LANJUT</button> --}}
                                    @endif

                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="hori-timeline">
                                    <!-- Swiper -->
                                    <div class="swiper-container slider">
                                        <div class="d-flex align-items-start">
                                            <div class="flex-grow-1">
                                                <h5 class="card-title mb-4">Progress Approval</h5>
                                            </div>
                                            <div class="flex-shrink-0">
                                                <div class="swiper-arrow d-flex gap-2 justify-content-end arrow-sm">
                                                    <div class="swiper-button-prev position-relative rounded-start">
                                                    </div>
                                                    <div class="swiper-button-next position-relative rounded-end"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide ">
                                                <div class="event-list text-start">
                                                    <h5 class="font-size-14 mb-1 fw-bold mt-3">Retur Diajukan</h5>
                                                    <p class="text-muted">
                                                        {{date('d F T',strtotime($data->timestamp))}} ||
                                                        {{date('H:i:s',strtotime($data->timestamp))}}</p>
                                                </div>
                                            </div>

                                            @foreach ($data->timeline as $apv)
                                                @if ($apv->tindak_lanjut == 'TOLAK')
                                                    @php
                                                    $class = 'event-list-reject';
                                                    @endphp
                                                @else
                                                    @if ($apv->status == 'done')
                                                        @php
                                                        $class = 'event-list';
                                                        @endphp
                                                        @elseif ($apv->status == 'reject')
                                                        @php
                                                        $class = 'event-list-reject';
                                                        @endphp
                                                        @else
                                                        @php
                                                        $class = 'event-list-pending';
                                                        @endphp
                                                    @endif
                                                @endif

                                                <div class="swiper-slide" style="">
                                                    <div class="{{$class}} text-start">
                                                        <h5 class="font-size-14 mb-1 fw-bold mt-3">{{$apv->type}}
                                                        </h5>
                                                        <p class="text-muted">
                                                             {{date('d F T',strtotime($apv->timestamp))}} ||
                                                            {{date('H:i:s',strtotime($apv->timestamp))}}
                                                            {{-- {{$apv->role_to_name}} --}}
                                                        </p>
                                                    </div>
                                                </div>
                                            @endforeach

                                            {{-- <div class="swiper-slide ">
                                                <div class="event-list text-start">
                                                    <h5 class="font-size-14 mb-1 fw-bold mt-3">Permintaan Disetujui</h5>
                                                    <p class="text-muted">-</p>
                                                </div>
                                            </div>
                                            <div class="swiper-slide ">
                                                <div class="event-list text-start">
                                                    <h5 class="font-size-14 mb-1 fw-bold mt-3">Barang Diserahkan Kepala
                                                        Gudang</h5>
                                                    <p class="text-muted">-</p>
                                                </div>
                                            </div>
                                            <div class="swiper-slide ">
                                                <div class="event-list text-start">
                                                    <h5 class="font-size-14 mb-1 fw-bold mt-3">Kurir Dalam Perjalanan
                                                    </h5>
                                                    <p class="text-muted">-</p>
                                                </div>
                                            </div>
                                            <div class="swiper-slide ">
                                                <div class="event-list text-start">
                                                    <h5 class="font-size-14 mb-1 fw-bold mt-3">Barang Diterima</h5>
                                                    <p class="text-muted">-</p>
                                                </div>
                                            </div> --}}

                                            <!-- end swiper slide -->
                                        </div>


                                    </div>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-4">List Barang</h5>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class=" table-striped datatables w-100" id="data-table">
                                                <thead>
                                                    <tr class="tr-head"
                                                        style="background: #1E1E1E;color:white;font-weight:bold;">
                                                        <th class="td-head" style="vertical-align: middle" >
                                                            No</th>
                                                        <th class="td-head" style="vertical-align: middle" >
                                                            Nama Barang</th>
                                                        <th class="td-head" style="vertical-align: middle" >
                                                            Kode</th>
                                                        <th class="td-head" style="vertical-align: middle" >
                                                            Kategori</th>
                                                        <th class="td-head" style="vertical-align: middle" >
                                                            Jumlah</th>
                                                            <th class="td-head" style="vertical-align: middle" >
                                                            Satuan</th>




                                                    </tr>

                                                </thead>
                                                @foreach ($data->retur_detail as $bd)
                                                <tr style="">
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>
                                                        <div>
                                                            <span
                                                                class="d-block font-size-15 fw-bold">{{$bd->nama_barang ?? 'N/A'}}</span>
                                                            {{-- <span class="">{{$bd->barang->kode_barang ?? 'N/A'}}</span>
                                                            <span
                                                                class="">{{$bd->barang->kategori_barang->nama_kategori ?? 'N/A'}}</span>
                                                            <span class="d-block">Sisa Stock :
                                                                {{$bd->barang->jumlah ?? 'N/A'}}</span> --}}

                                                        </div>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="font-size-15">{{$bd->barang->kode_barang ?? 'N/A'}}</span>
                                                    </td>
                                                    <td>
                                                        <span
                                                            class="font-size-15">{{$bd->barang->kategori_barang->nama_kategori ?? 'N/A'}}</span>
                                                    </td>

                                                    <td class="font-size-15">{{$bd->jumlah_retur}}</td>
                                                    <td class="font-size-15">
                                                        {{$bd->barang->satuan->nama_satuan?? 'N/A'}}</td>
                                                </tr>
                                                @endforeach


                                            </table>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        {{-- DOKUMEN DOWNLOAD --}}
                        <div class="row animate__animated  animate__fadeIn">
                            <div class="col-lg-12">
                                <div class="card shadow-lg">
                                    <div class="card-body ">
                                        <div class="row ">
                                            @if (count($data->approvals->where('type','Barang Diterima Pengelola Gudang')))
                                            <div class="col-lg-3">
                                                <div class="d-flex">
                                                    <div>
                                                        <img height="65" src="{{asset('assets/images/icon/file.png')}}"
                                                            alt="">
                                                    </div>
                                                    <div>

                                                         <span class="d-block"
                                                            style="font-size:20px;font-weight:bold;">BAST RETUR</span>
                                                        <a href="{{route('retur-barang.cetak-bast',$data->id)}}"
                                                            target="_blank">
                                                            <button class="btn btn-sm btn-success">Download</button>
                                                        </a>

                                                    </div>
                                                </div>
                                            </div>
                                            @endif

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                       {{-- PERSETUJUAN --}}
                        <hr>
                        <div class="row animate__animated  animate__fadeIn">
                            <div class="col-lg-12">
                                <div class="card shadow-lg">
                                    <div class="card-body ">
                                        <h3 class="fw-bold">PERSETUJUAN</h3>
                                        <div class="row ">
                                            @foreach ($data->approvals as $appvs)
                                                @if ($appvs->kategori == 'PERSETUJUAN')
                                                    <div class="col-lg-12">
                                                        <div class="card" style="border: 1px solid">
                                                            <div class="card-body p-4">
                                                                <p class="text-muted">
                                                                    {{date('d F T',strtotime($appvs->timestamp))}} ||
                                                                    {{date('H:i:s',strtotime($appvs->timestamp))}}</p>
                                                                    <span class="d-block mb-2">Retur Disetujui oleh {{$appvs->user->role->name ?? ''}}</span>
                                                                    <span class="d-block mb-2">Keterangan :</span>
                                                                    <span>{{$appvs->keterangan}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
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


@push('modals')
<div>
    <!-- sample modal content -->
    <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Tindak Lanjut</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>

                <form action="{{route('retur-barang.approval-tindak-lanjut',$data->id)}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for=""></label>
                            <select name="tindak_lanjut" class="form-select" id="tidakLanjut">
                                @if (Auth::user()->role->name == 'Pengelola Gudang')
                                    @if (optional($data->lastProcess())->type =='Menunggu Barang Diterima Pengelola Gudang')
                                    <option value="TERIMA BARANG">TERIMA BARANG</option>
                                    @else
                                    <option value="BARANG SIAP"> SIAP MENERIMA</option>
                                    @endif
                                @elseif (Auth::user()->role->name == 'Bendahara Materil')
                                    <option value="SETUJUI">SETUJUI</option>
                                    <option value="TOLAK">TOLAK</option>
                                @else
                                    <option value="SERAHKAN BARANG">SERAHKAN BARANG</option>
                                @endif

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="">Keterangan</label>
                            <textarea name="keterangan" id=""  cols="30" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" id="simpanBeritaTambahan">TINDAK LANJUT</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    @if ($data->approvals->where('kategori','PERSETUJUAN'))
        <!-- sample modal content -->
        <div id="myModalUpdate" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">Edit Tindak Lanjut </h5>
                        <button type="button" class="btn-close"  data-bs-dismiss="modal" aria-label="Close">

                        </button>
                    </div>
                    <form action="{{route('approval.tindak-lanjut-update',['id'=>$data->id,'idApproval'=>$data->approvals->where('kategori','APPROVAL')->first()->id ?? 0,'idPersetujuan'=>$data->approvals->where('kategori','PERSETUJUAN')->first()->id ?? 0])}}" method="post">
                        @csrf
                        <div class="modal-body">
                            <div class="form-group mb-2">
                                <label for=""></label>
                                <select name="tindak_lanjut" class="form-select" id="">
                                    <option  value="SETUJUI">SETUJUI</option>
                                    <option  value="TOLAK">TOLAK</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="">Keterangan</label>
                                <textarea name="keterangan" id=""  cols="30" rows="5" class="form-control">{{$data->approvals->where('kategori','PERSETUJUAN')->first()->keterangan ?? null}}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success" id="simpanBeritaTambahan">TINDAK LANJUT</button>
                        </div>
                    </form>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    @endif
</div>
@endpush

@push('scripts')

<!-- swiper js -->
<script src="{{asset('assets/libs/swiper/swiper-bundle.min.js')}}"></script>
<!-- timeline init -->
<script src="{{asset('assets/js/pages/timeline.init.js')}}"></script>

<script>
    $('#data-table').DataTable({
        //   "pageLength": 3
        paging: false

    });

    $('#tidakLanjut').on('change',function(){
        if(this.value =='UPDATE'){
            $('#updateJumlahBarang').removeClass('d-none');
        }else{
            $('#updateJumlahBarang').addClass('d-none');
        }
        if(this.value =='DISPOSISI'){
            $('#disposisiKe').removeClass('d-none');
        }else{
            $('#disposisiKe').addClass('d-none');
        }
    })
    $(".clickable-row").click(function () {
        window.location = $(this).data("href");
    });

</script>
@endpush
