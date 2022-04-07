@extends('main')


@section('content')
<div class="page-content">
    <div class="container-fluid">
        <div class="row ">
            <div class="col-lg-12">
                <div class="card animate__animated  animate__fadeIn">

                    <div class="card-body">
                        <div class="d-flex mb-4">
                            <div>
                                <img height="100" src="{{asset('images/userExternal').'/'.$user->logo_perusahaan}}" alt="">
                            </div>
                            <div class="ps-2 w-100">
                                <span class="fw-bolder fs-3 d-block">{{$user->nama_perusahaan}}</span>
                                <div class="row">
                                    <div class="col-lg-4">
                                        <span class="text-primary ">Lini Usaha</span> : {{$user->liniUsaha->nama_lini_usaha ?? ''}}
                                        <br>
                                        <span class="text-primary ">Alamat Perusahaan</span> : {{$user->alamat_perusahaan}}
                                        <br>
                                        <span class="text-primary ">Email</span> : {{$user->email_perusahaan}}
                                    </div>
                                   <div class="col-lg-4">
                                        <span class="text-primary ">Penanggung Jawab</span> :  {{$user->nama_penanggung_jawab}}
                                        <br>
                                        <span class="text-primary ">Jabatan</span> :  {{$user->jabatan_penanggung_jawab}}
                                        <br>
                                        <span class="text-primary ">Kontak</span> :  {{$user->nomor_penanggung_jawab}}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <span class="fw-bolder fs-3 d-block mb-4 mt-3">SBNP Yang dimiliki</span>

                        <table class="table-bordered table-striped datatables" id="data-table" style="font-size: 16px">
                            <thead>
                                <tr class="tr-head bg-dark">
                                    <th width="1%">No</th>
                                    <th>Nama SBNP</th>
                                    <th width="">Jenis Penandaan </th>
                                    <th>Terakhir Dilaporkan </th>
                                    <th class="td-head" width="10%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sbnp_dimiliki as $sd)
                                    <tr>
                                        <td>{{$loop->iteration}}</td>
                                        <td class="d-flex ">{{$sd->name}}
                                            @if (optional(optional($sd)->laporan)->keandalan())

                                                @if (optional(optional($sd)->laporan)->keandalan() < 100)
                                                    <div class=" ms-2" style="height: 20px;width: 20px;" >
                                                        <div class="avatar-title bg-danger rounded-circle " data-toggle="tooltip" data-placement="top" title="SBNP Memiliki Kelainan !">
                                                            !
                                                        </div>
                                                    </div>
                                                @endif
                                            @endif

                                        </td>
                                        <td>{{$sd->penandaan}}</td>
                                        <td>
                                            {{date('d F Y', strtotime(optional(optional($sd)->laporan)->tanggal_laporan)) != '01 January 1970' ? date('d F Y', strtotime(optional(optional($sd)->laporan)->tanggal_laporan)): 'Belum Melaporkan' }}</td>
                                        <td>
                                            @if (optional(optional($sd)->laporan)->type_sbnp  == 'Menara Suar')
                                                <a href="{{route('laporan.pengawasan.menara-suar.show',$sd->laporan->id)}}" class="btn btn-info">
                                                    <i class="fa fa-eye"></i>
                                                    Lihat</a>
                                            @elseif(optional(optional($sd)->laporan)->type_sbnp == 'Rambu Suar')
                                                <a href="{{route('laporan.pengawasan.rambu-suar.show',$sd->laporan->id)}}" class="btn btn-info">
                                                    <i class="fa fa-eye"></i>
                                                    Lihat</a>
                                            @elseif(optional(optional($sd)->laporan)->type_sbnp == 'Pelampung Suar')
                                                <a href="{{route('laporan.pengawasan.pelampung-suar.show',$sd->laporan->id)}}" class="btn btn-info">
                                                    <i class="fa fa-eye"></i>
                                                    Lihat</a>

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

        <!-- end row -->

    </div> <!-- container-fluid -->
</div>
@endsection

@push('scripts')
<script>
    $('#data-table').DataTable({});

</script>
@endpush
