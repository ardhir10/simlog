


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


                 <a href="{{route('rencana-kebutuhan.create')}}" class="btn btn-outline btn-outline-success  btn-rounded"> Buat Rencana Kebutuhan
                        <i class="fa fa-plus align-middle"></i>
                    </a>

            </div>
            <div class="card-body">
                <div class="col-12">
                    @include('components.flash-message')
                </div>
                <div class="col-lg-12">
                    <div class="mb-3">
                        <table>
                            <tr>
                                <td>
                                    <span class="fw-bolder">List Indikator :</span>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="d-flex pb-1 ps-4">
                                        <div class="avatar-sm me-1">
                                            <div class="avatar-title bg-disetujui rounded-circle ">
                                            </div>
                                        </div>
                                        <span class="mt-auto ">Disetujui</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex pb-1 ps-4">
                                        <div class="avatar-sm me-1">
                                            <div class="avatar-title bg-diproses rounded-circle ">
                                            </div>
                                        </div>
                                        <span class="m-tauto ">Dalam Proses</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex pb-1 ps-4">
                                        <div class="avatar-sm me-1">
                                            <div class="avatar-title bg-ditolak rounded-circle ">
                                            </div>
                                        </div>
                                        <span class="mt-auto ">Ditolak</span>
                                    </div>
                                </td>


                            </tr>


                        </table>
                    </div>
                    <div class="table-responsive">
                        <table class="table-striped datatables" id="data-table" style="font-size: 16px">
                            <thead>
                                <tr class="tr-head">
                                    <th class="td-head" width="1%" style="white-space: nowrap;">No</th>
                                    <th class="td-head"  style="white-space: nowrap;">Nomor RK</th>
                                    <th class="td-head"  style="white-space: nowrap;">Kegiatan</th>
                                    <th class="td-head"  style="white-space: nowrap;">Pengguna</th>
                                    <th class="td-head"  style="white-space: nowrap;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $item)
                                @if (optional($item->lastProcess())->role_to_name == Auth::user()->role->name)
                                    <tr class='clickable-row' data-href='{{ $item->is_draft != true ? route('rk-approval.review',$item->id) : route('rab.create')}}'>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$item->nomor_rk}}
                                            @if (optional($item->lastProcess())->role_to_name == Auth::user()->role->name)
                                                <span class="noti-dotnya bg-danger"> ! </span>
                                            @else

                                            @endif
                                        </td>
                                        <td>{{$item->kegiatan}}</td>
                                        <td>{{$item->pengguna()}}</td>
                                        <td>
                                            @if ($item->status == 'Diproses')
                                                <div class="avatar-sm ">
                                                    <div class="avatar-title bg-diproses rounded-circle font-size-12">

                                                    </div>
                                                </div>
                                            @elseif ($item->status == 'Disetujui')
                                                <div class="avatar-sm ">
                                                    <div class="avatar-title bg-disetujui rounded-circle font-size-12">

                                                    </div>
                                                </div>
                                            @elseif ($item->status == 'Selesai')
                                                <div class="avatar-sm ">
                                                    <div class="avatar-title bg-selesai rounded-circle font-size-12">

                                                    </div>
                                                </div>
                                            @elseif ($item->status == 'Ditolak')
                                                <div class="avatar-sm ">
                                                    <div class="avatar-title bg-ditolak rounded-circle font-size-12">

                                                    </div>
                                                </div>

                                            @else
                                                Draft
                                                {{-- {{$item->status}} --}}
                                            @endif
                                        </td>
                                    </tr>
                                @else
                                    <tr class='clickable-row' data-href='{{ $item->is_draft != true ? route('rk-approval.review',$item->id) : route('rab.create')}}'>
                                        <td>{{$loop->iteration}}</td>
                                        <td>{{$item->nomor_rk}}
                                            @if (optional($item->lastProcess())->role_to_name == Auth::user()->role->name)
                                                <span class="noti-dotnya bg-danger"> ! </span>
                                            @else

                                            @endif
                                        </td>
                                        <td>{{$item->kegiatan}}</td>
                                        <td>{{$item->pengguna()}}</td>
                                        <td>
                                            @if ($item->status == 'Diproses')
                                                <div class="avatar-sm ">
                                                    <div class="avatar-title bg-diproses rounded-circle font-size-12">

                                                    </div>
                                                </div>
                                            @elseif ($item->status == 'Disetujui')
                                                <div class="avatar-sm ">
                                                    <div class="avatar-title bg-disetujui rounded-circle font-size-12">

                                                    </div>
                                                </div>
                                            @elseif ($item->status == 'Selesai')
                                                <div class="avatar-sm ">
                                                    <div class="avatar-title bg-selesai rounded-circle font-size-12">

                                                    </div>
                                                </div>
                                            @elseif ($item->status == 'Ditolak')
                                                <div class="avatar-sm ">
                                                    <div class="avatar-title bg-ditolak rounded-circle font-size-12">

                                                    </div>
                                                </div>

                                            @else
                                                Draft
                                                {{-- {{$item->status}} --}}
                                            @endif
                                        </td>
                                    </tr>
                                @endif

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

    });

    $(".clickable-row").click(function() {
        window.location = $(this).data("href");
    });

</script>
@endpush
