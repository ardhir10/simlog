

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
                                    <h5 class="mb-2">{{$data->nomor_rk}} | {{date('d F Y',strtotime($data->timestamp))}}</h5>
                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <span class="d-block text-success">Kegiatan</span>
                                            <span class="d-block">{{$data->kegiatan}}</span>

                                        </div>
                                    </div>
                                    <div class="row ">
                                        <div class="col-lg-6">
                                            <span class="d-block text-success">Pengguna</span>
                                            <span class="d-block">{{$data->pengguna()}}</span>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 offset-lg-2">
                                <div class="text-end">
                                    @if (optional($data->lastProcess())->role_to_name == Auth::user()->role->name)
                                        <button class="btn btn-lg btn-success " data-bs-toggle="modal" data-bs-target="#myModal">TINDAK LANJUT</button>
                                        <span class="d-block">Anda Belum memberikan tindak lanjut terhadap Permintaan Barang ini. Harap berikan tindak lanjut dengan menekan tombol tindak lanjut diatas.</span>
                                    @else
                                        @if (optional($data->lastProcess(Auth::user()->role->name))->tindak_lanjut == 'SETUJUI' || optional($data->lastProcess(Auth::user()->role->name))->tindak_lanjut == 'UPDATE')
                                        <h1 class="text-success">DISETUJUI</h1>
                                        @elseif (optional($data->lastProcess(Auth::user()->role->name))->tindak_lanjut == 'TOLAK')
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
                                                            Satuan</th>

                                                        <th class="td-head text-center" colspan="2">Permintaan</th>
                                                        <th class="td-head" rowspan="2">Keterangan</th>

                                                    </tr>
                                                    <tr class="tr-head"
                                                        style="background: #1E1E1E;color:white;font-weight:bold;">
                                                        <th class="td-head" style="border-radius: 0px !important">
                                                            Qty</th>
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
                                                            class="font-size-15">{{$bd->satuan ?? 'N/A'}}</span>
                                                    </td>

                                                    <td class="font-size-15">{{$bd->qty}}</td>
                                                    <td class="font-size-15">{{$bd->jumlah_disetujui ?? 0}}</td>
                                                    <td class="font-size-15">
                                                        {{$bd->keterangan?? 'N/A'}}</td>


                                                </tr>
                                                @endforeach


                                            </table>

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
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Tindak Lanjut</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>

                <form action="{{route('rk-approval.tindak-lanjut',$data->id)}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-4">
                            <label for=""></label>
                            <select name="tindak_lanjut" class="form-select" id="tidakLanjut">
                                <option value="SETUJUI">SETUJUI</option>
                                <option value="TOLAK">TOLAK</option>
                                <option value="DISPOSISI">DISPOSISI</option>
                                <option value="UPDATE">UPDATE</option>
                            </select>
                        </div>
                        <div class="form-group mb-4 d-none" id="disposisiKe">
                            <label for="">Disposisi Ke :</label>
                            <select name="disposisi_ke" class="form-select" >
                                <option value="Kabid Logistik">Kabid Logistik</option>
                                <option value="Kabid Operasi">Kabid Operasi</option>
                                <option value="Kabag Tata Usaha">Kabag Tata Usaha</option>
                            </select>
                        </div>
                        <div class="table-responsive d-none" id="updateJumlahBarang">
                            <table class="table table-bordered">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th>No</th>
                                        <th>nama Barang</th>
                                        <th>Kode Barang</th>
                                        <th>Jumah</th>
                                        <th>Jumlah DIsetujui</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data->barang_diminta as $item)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$item->nama_barang?? 'N/A'}}</td>
                                            <td>{{$item->satuan?? 'N/A'}}</td>
                                            <td>{{$item->qty}}</td>
                                            <td><input name="jumlah_disetujui[{{$item->id}}]" type="number" step="0.01" value="{{$item->qty}}"></td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>

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
                                    <option  value="REVIEW">REVIEW</option>
                                    <option  value="UPDATE">UPDATE</option>
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
