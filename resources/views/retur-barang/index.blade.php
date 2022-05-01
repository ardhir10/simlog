


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

                @if ((Auth::user()->role->type ?? null) == 2 || 
                    (Auth::user()->role->name ?? null) == 'Kurir/Offsetter'
                    )
                    
                <a href="{{route('retur-barang.create')}}" class="btn btn-outline btn-outline-success  btn-rounded"> RETUR BARANG
                    <i class="fa fa-plus align-middle"></i>
                </a>
                @endif

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
                                        <span class="mt-auto ">Retur Selesai</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex pb-1 ps-4">
                                        <div class="avatar-sm me-1">
                                            <div class="avatar-title bg-diproses rounded-circle ">
                                            </div>
                                        </div>
                                        <span class="m-tauto ">Retur Dalam Proses</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex pb-1 ps-4">
                                        <div class="avatar-sm me-1">
                                            <div class="avatar-title bg-ditolak rounded-circle ">
                                            </div>
                                        </div>
                                        <span class="mt-auto ">Retur Ditolak</span>
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
                                    <th class="td-head"  style="white-space: nowrap;">Nomor Retur</th>
                                    <th class="td-head"  style="white-space: nowrap;">Perihal</th>
                                    <th class="td-head"  style="white-space: nowrap;">Instalasi</th>
                                    <th class="td-head"  style="white-space: nowrap;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($retur_barang as $item)
                                <tr class='clickable-row' data-href='{{ $item->is_draft != true ? route('retur-barang.approval-review',$item->id) : ''}}'>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$item->nomor_retur}}
                                        @if (optional($item->lastProcess())->role_to_name == Auth::user()->role->name)
                                            <span class="noti-dotnya bg-danger"> ! </span>
                                        @else

                                        @endif
                                    </td>
                                    <td>{{$item->perihal}}</td>
                                    <td>{{$item->instalasi}}</td>
                                    <td>
                                        @if ($item->status == 'Dalam Proses')
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
