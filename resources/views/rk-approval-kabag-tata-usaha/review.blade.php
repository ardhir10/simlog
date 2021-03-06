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
                                    <span>{{$data->perihal}}</span>
                                    <br>
                                    <span class="d-block">Diminta Oleh : {{$data->dimintaOleh()}}</span>
                                    <span class="d-block">Bagian/Bidang : {{$data->bagianBidang()}}</span>
                                    <span class="d-block">Nomor UPP3 : {{$data->nomor_upp3}}</span>
                                    <span class="d-block">Tanggal Permintaan :
                                        {{date('d F Y',strtotime($data->tanggal_permintaan))}}</span>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    @if (($data->isNeedApprove()->role_to_name ?? null) == Auth::user()->role->name ||
                                    ($data->isNeedApproveDisposisi()->role_to_name ?? null) == Auth::user()->role->name
                                    )
                                        {{-- JIKA DISPOSISI DARI KADISNAV --}}
                                        @if (($data->fromKadisnav()) == 'DISPOSISI' )
                                                <div class="text-end">
                                                    <button class="btn btn-lg btn-warning " data-bs-toggle="modal" data-bs-target="#disposisiFromKadisnavModal">
                                                    <i class="fas fa-comments"></i>
                                                    DISPOSISI</button>
                                                    <button class="btn btn-lg btn-success " data-bs-toggle="modal" data-bs-target="#setujuDisposisiKadisnav">
                                                        <i class="fa fa-check"></i>
                                                        SETUJU</button>
                                                </div>
                                        @else
                                            <div class="text-end">
                                                <button class="btn btn-lg btn-warning " data-bs-toggle="modal" data-bs-target="#disposisiModal">
                                                <i class="fas fa-comments"></i>
                                                DISPOSISI</button>
                                                <button class="btn btn-lg btn-success " data-bs-toggle="modal" data-bs-target="#myModal">
                                                    <i class="fa fa-check"></i>
                                                    SETUJU</button>
                                            </div>
                                        @endif
                                    @endif
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
                                                    <h5 class="font-size-14 mb-1 fw-bold mt-3">Permintaan Diajukan</h5>
                                                    <p class="text-muted">
                                                        {{date('d F T',strtotime($data->tanggal_permintaan))}} ||
                                                        {{date('H:i:s',strtotime($data->tanggal_permintaan))}}</p>
                                                </div>
                                            </div>

                                            @foreach ($data->timeline->where('type','!=','Disetujui Staff Seksi Pengadaan') as $apv)
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
                                                        <th class="td-head" style="vertical-align: middle" rowspan="2">
                                                            No</th>
                                                        <th class="td-head" style="vertical-align: middle" rowspan="2">
                                                            Nama Barang</th>
                                                        <th class="td-head" style="vertical-align: middle" rowspan="2">
                                                            Kode</th>
                                                        <th class="td-head" style="vertical-align: middle" rowspan="2">
                                                            Kategori</th>
                                                        <th class="td-head text-center" colspan="2">Jumlah</th>
                                                        <th class="td-head" rowspan="2">Satuan</th>

                                                    </tr>
                                                    <tr class="tr-head"
                                                        style="background: #1E1E1E;color:white;font-weight:bold;">
                                                        <th class="td-head" style="border-radius: 0px !important">
                                                            Permintaan</th>
                                                        <th class="td-head" style="border-radius: 0px !important">
                                                            Disetujui</th>
                                                    </tr>
                                                </thead>
                                                @foreach ($data->barang_diminta as $bd)
                                                <tr style="">
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>
                                                        <div>
                                                            <span
                                                                class="d-block font-size-15 fw-bold">{{$bd->barang->nama_barang ?? 'N/A'}}</span>
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
                                                    <td><span
                                                            class="font-size-15">{{$bd->barang->kategori_barang->nama_kategori ?? 'N/A'}}</span>
                                                    </td>
                                                    <td class="font-size-15">{{$bd->jumlah}}</td>
                                                    <td class="font-size-15">{{$bd->jumlah_disetujui ?? 0}}</td>
                                                    <td class="font-size-15">
                                                        {{$bd->barang->satuan->nama_satuan ?? 'N/A'}}</td>


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
                                            <div class="col-lg-3">
                                                <div class="d-flex">
                                                    <div>
                                                        <img height="65" src="{{asset('assets/images/icon/file.png')}}"
                                                            alt="">
                                                    </div>
                                                    <div>
                                                        <span class="d-block"
                                                            style="font-size:20px;font-weight:bold;">Nota Dinas</span>
                                                        <a href="{{route('permintaan-barang.nota-dinas',$data->id)}}"
                                                            target="_blank">
                                                            <button class="btn btn-sm btn-success">Download</button>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="d-flex">
                                                    <div>
                                                        <img height="65" src="{{asset('assets/images/icon/file.png')}}"
                                                            alt="">
                                                    </div>
                                                    <div>
                                                        <span class="d-block"
                                                            style="font-size:20px;font-weight:bold;">UPP3</span>
                                                        <a href="{{route('permintaan-barang.upp3',$data->id)}}"
                                                            target="_blank">
                                                            <button class="btn btn-sm btn-success">Download</button>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            @if ($data->approvals->where('kategori','PERSETUJUAN')->first->id ?? null)
                                                <div class="col-lg-3">
                                                    <div class="d-flex">
                                                        <div>
                                                            <img height="65" src="{{asset('assets/images/icon/file.png')}}"
                                                                alt="">
                                                        </div>
                                                        <div>
                                                            <span class="d-block"
                                                                style="font-size:20px;font-weight:bold;">UPP4</span>
                                                            <a href="{{route('permintaan-barang.upp4',$data->id)}}"
                                                                target="_blank">
                                                                <button class="btn btn-sm btn-success">Download</button>
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                             @if ($data->nomor_bast)
                                                <div class="col-lg-3">
                                                    <div class="d-flex">
                                                        <div>
                                                            <img height="65" src="{{asset('assets/images/icon/file.png')}}"
                                                                alt="">
                                                        </div>
                                                        <div>
                                                            <span class="d-block"
                                                                style="font-size:20px;font-weight:bold;">BAST</span>
                                                            <a href="{{route('permintaan-barang.upp4',$data->id)}}"
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
                        {{-- DOKUMEN DOWNLOAD --}}
                        <div class="row animate__animated  animate__fadeIn">
                            <div class="col-lg-12">
                                <div class="card shadow-lg">
                                    <div class="card-body ">
                                        <div class="row ">
                                            <div class="col-lg-3">
                                                <div class="d-flex">
                                                    <div>
                                                        <img height="65" src="{{asset('assets/images/icon/file.png')}}"
                                                            alt="">
                                                    </div>
                                                    <div>
                                                        <span class="d-block"
                                                            style="font-size:20px;font-weight:bold;">Nota Dinas</span>
                                                        <a href="{{route('rencana-kebutuhan.nota-dinas',$data->id)}}"
                                                            target="_blank">
                                                            <button class="btn btn-sm btn-success">Download</button>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3">
                                                <div class="d-flex">
                                                    <div>
                                                        <img height="65" src="{{asset('assets/images/icon/file.png')}}"
                                                            alt="">
                                                    </div>
                                                    <div>
                                                        <span class="d-block"
                                                            style="font-size:20px;font-weight:bold;">Usulan Kebutuhan</span>
                                                        <a href="{{route('rencana-kebutuhan.usulan-kebutuhan',$data->id)}}"
                                                            target="_blank">
                                                            <button class="btn btn-sm btn-success">Download</button>
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>


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
                                                                    {{-- <span class="fw-bold d-block">Dari : {{$appvs->user->name ?? 'N/A'}} ({{$appvs->user->role->name ?? 'N/A'}})</span> --}}
                                                                    {{-- <span class="fw-bold d-block">Ke : {{$appvs->role_to_name ?? 'N/A'}} </span> --}}
                                                                    <span class="d-block mb-2">Disetujui oleh {{$appvs->role_to_name}}</span>
                                                                    <span class="d-block mb-2">Keterangan :</span>
                                                                    <span>{{$appvs->keterangan}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @elseif ($appvs->kategori == 'DISPOSISI')
                                                    {{-- JIKA LEVELNYA ATAS KEATAS  --}}
                                                    @if ($appvs->user->role->level < $appvs->diminta->level)
                                                        <div class="col-lg-12">
                                                            <div class="card bg-warning" style="border: 1px solid">
                                                                <div class="card-body p-4">
                                                                    <p class="text-muted">
                                                                        {{date('d F T',strtotime($appvs->timestamp))}} ||
                                                                        {{date('H:i:s',strtotime($appvs->timestamp))}}</p>
                                                                        <span class="d-block mb-2">{{$appvs->user->role->name}} Disposisi ke {{$appvs->diminta->name}}</span>
                                                                        <span class="d-block mb-2">Keterangan :</span>
                                                                        <span>{{$appvs->keterangan}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="col-lg-12">
                                                            <div class="card bg-warning" style="border: 1px solid">
                                                                <div class="card-body p-4">
                                                                    <p class="text-muted">
                                                                        {{date('d F T',strtotime($appvs->timestamp))}} ||
                                                                        {{date('H:i:s',strtotime($appvs->timestamp))}}</p>
                                                                        <span class="d-block mb-2">{{$appvs->user->role->name}} Meminta Arahan ke {{$appvs->diminta->name}}</span>
                                                                        <span class="d-block mb-2">Keterangan :</span>
                                                                        <span>{{$appvs->keterangan}}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">PESANAN DISETUJUI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <form action="{{route('approval.kabid-logistik-setuju',$data->id)}}" method="post">
                    @csrf

                    <div class="modal-body">
                        {{-- <p class="text-center">Dengan menekan tombol lanjutkan anda sebagai Pengelola Gudang telah menyiapkan barang-barang sesuai dengan nomor UPP4 {{$data->nomor_upp4}}</p> --}}

                        <div class="form-group">
                            <label for="">Keterangan</label>
                            <textarea name="keterangan" id=""  cols="30" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="simpanBeritaTambahan">LANJUTKAN SETUJUI</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



    <!-- sample modal content -->
    <div id="disposisiModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">DISPOSISI PERMINTAAN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <form action="{{route('approval.kabid-logistik-disposisi',$data->id)}}" method="post">
                    @csrf

                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <label for="">Disposisi Ke :</label>
                            <select name="disposisi_ke" id="" class="form-select">
                                <option value="Kepala Distrik Navigasi">Kepala Distrik Navigasi</option>
                                <option value="Kasie Pengadaan">Kasie Pengadaan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Keterangan</label>
                            <textarea name="keterangan" id=""  cols="30" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="simpanBeritaTambahan">LANJUTKAN SETUJUI</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- sample modal content -->
    <div id="disposisiFromKadisnavModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">DISPOSISI PERMINTAAN</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <form action="{{route('approval.kabid-logistik-disposisi',$data->id)}}" method="post">
                    @csrf

                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <label for="">Disposisi Ke :</label>
                           <select name="disposisi_ke" id="" class="form-select">
                                <option value="Kasie Kepeg & Umum">Kasie Kepeg & Umum</option>
                                <option value="Kasie Keuangan">Kasie Keuangan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Keterangan</label>
                            <textarea name="keterangan" id=""  cols="30" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="simpanBeritaTambahan">LANJUTKAN SETUJUI</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- sample modal content -->
    <div id="setujuDisposisiKadisnav" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">PESANAN DISETUJUI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <form action="{{route('approval.kabid-logistik-setuju-disposisi-kadisnav',$data->id)}}" method="post">
                    @csrf

                    <div class="modal-body">
                        {{-- <p class="text-center">Dengan menekan tombol lanjutkan anda sebagai Pengelola Gudang telah menyiapkan barang-barang sesuai dengan nomor UPP4 {{$data->nomor_upp4}}</p> --}}

                        <div class="form-group">
                            <label for="">Keterangan</label>
                            <textarea name="keterangan" id=""  cols="30" rows="5" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="simpanBeritaTambahan">LANJUTKAN SETUJUI</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


     <!-- Modal Serahkan Barang -->
    <div id="modalSerahkanBarang" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Serahkan Barang</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <form action="{{route('approval.serahkan-barang',$data->id)}}" method="post">
                    @csrf

                    <div class="modal-body">
                        <p class="text-center">Dengan menekan tombol ini maka barang akan diserahkan ke peminta </p>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="simpanBeritaTambahan">SERAHKAN BARANG</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


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

    $(".clickable-row").click(function () {
        window.location = $(this).data("href");
    });

</script>
@endpush
