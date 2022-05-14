


@extends('main')


@push('styles')

<style>


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
                <a href="{{route('rencana-kebutuhan-tahunan.create')}}" class="btn btn-outline btn-outline-success  btn-rounded"> Tambah Rencana
                    <i class="fa fa-plus align-middle"></i>
                </a>
            </div>
            <div class="card-body p-2 ">
                <div class="col-12">
                    @include('components.flash-message')
                </div>
                <form action="">
                    <div class="row mb-3">
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label for="">Pilih Tahun</label>
                                <select name="tahun" id="" required class="form-select select2">
                                    <?php
                                    for ($x=date("Y"); $x>1900; $x--)
                                    {
                                            if (($data->tahun_anggaran ?? null ) == $x) {
                                                echo'<option selected=selected value="'.$x.'">'.$x.'</option>';
                                            } else {
                                                echo'<option value="'.$x.'">'.$x.'</option>';
                                            }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-1">
                                <div class="form-group">
                                    <label for="">_</label>
                                    <button type="submit" class="btn btn-primary w-100 " >SHOW</button>
                                </div>

                        </div>
                    </div>
                </form>
                <div class="table-responsive">
                    <table class="table-striped datatables" id="data-table" style="font-size: 16px">
                        <thead>
                            <tr class="tr-head">
                                <th class="td-head" width="1%" style="white-space: nowrap;">No</th>
                                <th class="td-head" width="1%">Action</th>
                                {{-- <th class="td-head" width="20%">Foto</th> --}}
                                <th class="td-head" width="20%" style="white-space: nowrap;">Kategori</th>
                                <th class="td-head" width="20%" style="white-space: nowrap;">Nama Barang</th>
                                <th class="td-head" width="20%" style="white-space: nowrap;">Tahun</th>
                                <th class="td-head" width="20%">UMUM</th>
                                    <th class="td-head" width="20%">SIE KEPEG </th>
                                <th class="td-head" width="20%">SIE SIE KEUANGAN</th>
                                <th class="td-head" width="20%">PENGADAAN</th>
                                <th class="td-head" width="20%">INVENTARIS</th>
                                <th class="td-head" width="20%">SIE SARPRAS</th>
                                <th class="td-head" width="20%">SIE PROGRAM</th>
                                <th class="td-head" width="20%">SBNP</th>
                                <th class="td-head" width="20%">TELKOMPEL</th>
                                <th class="td-head" width="20%">PENGLA</th>
                                <th class="td-head" width="20%">KNK</th>
                                <th class="td-head" width="20%">BENGKEL</th>

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rencana_kebutuhan_tahunan as $item)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>
                                         <div class="d-flex">
                                            <a href="{{route('rencana-kebutuhan-tahunan.edit',$item->id)}}" data-toggle="tooltip" title="Edit Data"
                                                class="btn btn rounded-3 btn-outline btn-outline-success me-1">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button class="btn btn-sm rounded-3 btn-outline btn-outline-danger" data-toggle="tooltip" title="Hapus Data" onclick="return confirmDelete('{{route('rencana-kebutuhan-tahunan.delete',$item->id)}}')">
                                                <i class="fa fa-trash"></i></button>
                                        </div>
                                    </td>
                                    <td>{{$item->kategori}}</td>
                                    <td>{{$item->nama_barang}}</td>
                                    <td>{{$item->tahun}}</td>
                                    <td>{{$item->p01}}</td>
                                    <td>{{$item->p02}}</td>
                                    <td>{{$item->p03}}</td>
                                    <td>{{$item->p04}}</td>
                                    <td>{{$item->p05}}</td>
                                    <td>{{$item->p06}}</td>
                                    <td>{{$item->p07}}</td>
                                    <td>{{$item->p08}}</td>
                                    <td>{{$item->p09}}</td>
                                    <td>{{$item->p10}}</td>
                                    <td>{{$item->p11}}</td>
                                    <td>{{$item->p12}}</td>
                                </tr>
                            @endforeach




                        </tbody>

                    </table>
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

</script>
@endpush
