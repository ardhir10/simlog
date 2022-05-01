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
                    <h4 class="card-title">{{$page_title}}</h4>

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
                            <div class="col-lg-4 offset-lg-2">
                                <div class="text-end">
                                        @if (($data->lastApproval()->type ?? null) == 'Barang Telah diterima')
                                            <button class="btn btn-lg btn-success " data-bs-toggle="modal" data-bs-target="#laporDistribusi" >

                                                <i class="fa fa-file"></i>
                                                LAPOR DISTRIBUSI</button>
                                        @else
                                            @if ($data->approvals->where('type','Barang Dijemput Kurir')->first())
                                                <button class="btn btn-lg btn-success " data-bs-toggle="modal" data-bs-target="#terimaBarang" >
                                                <i class="fa fa-check"></i>
                                                TERIMA BARANG</button>
                                            @elseif (($data->lastApproval()->type ?? null) == 'Barang Telah diterima')
                                                <button class="btn btn-lg btn-success " disabled>
                                                    <i class="fa fa-check"></i>
                                                    BARANG SUDAH DITERIMA</button>
                                            @else

                                            @endif

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
                                                        <a href="{{route('permintaan-barang.nota-dinas',$data->id)}}" target="_blank">
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
                                                        <a href="{{route('permintaan-barang.upp3',$data->id)}}" target="_blank">
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
                                                            <a href="{{route('permintaan-barang.bast',$data->id)}}"
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

                        @if ($data->laporanDistribusi)

                            {{-- LAPORAN DISTRIBUSI --}}
                            <hr>
                            <div class="row animate__animated  animate__fadeIn">
                                <div class="col-lg-12">
                                    <div class="card shadow-lg">
                                        <div class="card-body ">
                                            <h3 class="fw-bold">LAPORAN DISTRIBUSI BARANG</h3>
                                            <div class="row ">
                                                <div class="col-lg-6">
                                                    <span class="d-block">Lokasi Distribusi</span>
                                                    <span class="d-block fs-5" >{{$data->laporanDistribusi->lokasi_distribusi}}</span>
                                                </div>
                                                <div class="col-lg-6">
                                                    <span class="d-block">Tanggal dan Jam</span>
                                                    <span class="d-block fs-5" >{{$data->laporanDistribusi->tanggal_waktu}}</span>
                                                </div>
                                                <div class="col-lg-12 mt-2">
                                                    <span class="d-block">Keterangan</span>
                                                    <span class="d-block fs-5" >{{$data->laporanDistribusi->keterangan}}</span>
                                                </div>
                                                <div class="col-lg-12 mt-3">
                                                    <table class="table table-bordered">
                                                        <thead class="bg-dark text-white ">
                                                            <tr>
                                                                <th>No</th>
                                                                <th>Nama Barang</th>
                                                                <th>Satuan</th>
                                                                <th>Kode</th>
                                                                <th>UPP4</th>
                                                                <th>Detail Distribusi</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @foreach ($data->barang_diminta as $item)
                                                                <tr style="background:{{$item->distribusiSelesai() == true ? '#51D28C': '#FBC31E'}}">
                                                                    <td>{{$loop->iteration}}</td>
                                                                    <td>{{$item->barang->nama_barang ?? 'N/A'}}</td>
                                                                    <td>{{$item->barang->satuan->nama_satuan ?? 'N/A'}}</td>
                                                                    <td>{{$item->barang->kode_barang ?? 'N/A'}}</td>
                                                                    <td>{{$item->jumlah_disetujui ?? 'N/A'}}</td>
                                                                    <td>
                                                                        <table class="table table-bordered">
                                                                            <tr>
                                                                                <td colspan="2" class="fw-bolder">Total Distribusi : {{$item->totalDistribusi() ?? 'N/A'}}</td>
                                                                            </tr>
                                                                            @foreach ($item->barangDistribusi as $bdis)
                                                                                <tr>
                                                                                    <td>{{$bdis->created_at}}</td>
                                                                                    <td>{{$bdis->jumlah}}</td>
                                                                                    <td><a href="{{asset('images/laporan-distribusi/'.optional(optional($bdis)->gambar)->file_name ?? null)}}" target="_blank">
                                                                                        <img src="{{asset('images/laporan-distribusi/'.optional(optional($bdis)->gambar)->file_name ?? null)}}" height="30" alt="">
                                                                                        </a>
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </table>
                                                                    </td>

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
                        @endif



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
    <div id="terimaBarang" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">TERIMA BARANG</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <form action="{{route('approval.terima-barang-by-kurir',$data->id)}}" method="post">
                    @csrf

                    <div class="modal-body">
                        <p class="text-center">
                            Dengan menekan tombol lanjutkan , anda sebagai Kurir melakukan konfirmasi bahwa Anda telah menerima barang dengan jumlah yang tepat dan barang dalam kondisi baik.
                        </p>


                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="simpanBeritaTambahan">TERIMA BARANG</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Lapor Distribusi -->
    <div id="laporDistribusi" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">LAPORAN DISTRIBUSI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <form action="{{route('approval.lapor',$data->id)}}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-2">
                            <input type="checkbox" id="check" class="" required>
                            <label for="check">Saya telah melakukan distribusi yang saya terima</label>

                        </div>
                        <div class="form-group mb-2">
                            <label for="check">Lokasi Distribusi</label>
                            <input type="text" name="lokasi_distribusi" value="{{$laporan_distribusi->lokasi_distribusi ?? null}}" class="form-control" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="check">Tanggal</label>
                            <input type="date" name="tanggal" value="{{ ($laporan_distribusi->tanggal_waktu??null) ? explode(' ',$laporan_distribusi->tanggal_waktu)[0]:''}}" class="form-control" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="check">Jam</label>
                            <input type="time" value="{{ ($laporan_distribusi->tanggal_waktu??null) ? explode(' ',$laporan_distribusi->tanggal_waktu)[1]:''}}"  name="jam" class="form-control" required>
                        </div>
                        <div class="form-group mb-2">
                            <label for="check">Keterangan</label>
                            <textarea name="keterangan" id="" cols="30" rows="3" class="form-control">{{$laporan_distribusi->keterangan ?? null}}</textarea>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-dark text-white ">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Kode</th>
                                        <th>UPP4</th>
                                        <th>Total Distribusi</th>
                                        <th>Distribusi</th>
                                        <th>Satuan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->barang_diminta as $item)
                                        <tr style="background:{{$item->distribusiSelesai() == true ? '#51D28C': '#FBC31E'}}">
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$item->barang->nama_barang ?? 'N/A'}}</td>
                                            <td>{{$item->barang->kode_barang ?? 'N/A'}}</td>
                                            <td>{{$item->jumlah_disetujui ?? 'N/A'}}</td>
                                            <td>{{$item->totalDistribusi() ?? 'N/A'}}</td>
                                            <td>
                                                @if ($item->distribusiSelesai() == true)
                                                    Distribusi Selesai
                                                    <input value="0" max="{{$item->jumlah_disetujui-$item->totalDistribusi()}}" class="{{$item->distribusiSelesai() == true ? 'd-none': ''}}" type="hidden" name="jumlah_distribusi[]">
                                                @else
                                                    <input value="0" max="{{$item->jumlah_disetujui-$item->totalDistribusi()}}" class="{{$item->distribusiSelesai() == true ? 'd-none': ''}}" type="number" name="jumlah_distribusi[]">
                                                @endif
                                            </td>
                                            <td>{{$item->barang->satuan->nama_satuan ?? 'N/A'}}</td>
                                            <td>
                                                @if ($item->distribusiSelesai() == true)
                                                    Distribusi Selesai
                                                @else
                                                    <input type="file" class="{{$item->distribusiSelesai() == true ? 'd-none': ''}}" name="file_upload[{{$item->id}}]">
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                        {{-- <div class="form-group mb-2">
                            <label for="check"></label>
                            <input type="file" multiple name="file[]" class="form-control" required>
                        </div> --}}


                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success" id="">LAPOR</button>
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
