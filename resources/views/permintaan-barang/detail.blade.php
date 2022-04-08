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
                        <div class="d-block mb-3">
                            <span>{{$data->perihal}}</span>
                            <br>
                            <span class="d-block">Diminta Oleh : {{$data->dimintaOleh()}}</span>
                            <span class="d-block">Bagian/Bidang : {{$data->bagianBidang()}}</span>
                            <span class="d-block">Nomor UPP3 : {{$data->nomor_upp3}}</span>
                            <span class="d-block">Tanggal Permintaan :
                                {{date('d F Y',strtotime($data->tanggal_permintaan))}}</span>
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
                                            <div class="swiper-slide ">
                                                <div class="event-list text-start">
                                                    <h5 class="font-size-14 mb-1 fw-bold mt-3">Dalam Proses Approval
                                                    </h5>
                                                    <p class="text-muted">-</p>
                                                </div>
                                            </div>
                                            <div class="swiper-slide ">
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
                                            </div>

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
