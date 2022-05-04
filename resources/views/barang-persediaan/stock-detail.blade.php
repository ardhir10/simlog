


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
                <h4 class="card-title">Stock Masuk {{$page_title}}</h4>
                <a  data-bs-toggle="modal" data-bs-target="#myModal" class="btn btn-outline btn-outline-success  btn-rounded">

                    <i class="fa fa-plus align-middle"></i>
                </a>
            </div>
            <div class="card-body">
                <div class="col-12">

                    @include('components.flash-message')
                </div>
                <div class="table-responsive">
                    <table class="table-striped datatables" id="data-table" style="font-size: 16px">
                        <thead>
                            <tr class="tr-head">
                                <th class="td-head" width="1%" style="white-space: nowrap;">No</th>
                                <th class="td-head" width="20%">Tanggal</th>
                                <th class="td-head" width="20%">Jumlah ({{$data->satuan->nama_satuan}})</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data->barangMasuk as $bm)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$bm->timestamp}}</td>
                                <td>{{$bm->jumlah}}</td>
                            </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="row animate__animated  animate__fadeIn">
    <div class="col-lg-12">
        <div class="card shadow-lg">
            <div class="card-header justify-content-between d-flex align-items-center">
                <h4 class="card-title">Stock Keluar {{$page_title}}</h4>
                {{-- <a href="{{route('barang-persediaan.create')}}" class="btn btn-outline btn-outline-success  btn-rounded"> Tambah Barang
                    <i class="fa fa-plus align-middle"></i>
                </a> --}}
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table-striped datatables" id="data-table2" style="font-size: 16px">
                        <thead>
                            <tr class="tr-head">
                                <th class="td-head" width="1%" style="white-space: nowrap;">No</th>
                                <th class="td-head" width="20%">Tanggal</th>
                                <th class="td-head" width="20%">Permintaan</th>
                                <th class="td-head" width="20%">Jumlah ({{$data->satuan->nama_satuan}})</th>
                            </tr>
                        </thead>
                        <tbody>
                             @foreach ($data->barangKeluar as $bk)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$bk->timestamp}}</td>
                                <td>{{$bk->permintaan->perihal ?? ''}}</td>
                                <td>{{$bk->jumlah}}</td>
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

@push('modals')
<div>
    <!-- sample modal content -->
    <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Tambah Stok</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <form action="{{route('barang-persediaan.stock-masuk',$data->id)}}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group mb-2 d-none">
                            <label for="">Tanggal</label>
                            <input type="date" name="timestamp" class="form-control" value="{{date('Y-m-d')}}">
                        </div>
                        <div class="form-group">
                            <label for="">Jumlah ({{$data->satuan->nama_satuan}})</label>
                            <input type="number" name="jumlah" class="form-control" value="{{date('Y-m-d')}}">
                        </div>
                        <div class="form-group">
                            <label for="">Keterangan </label>
                            <textarea name="keterangan" class="form-control" id="" cols="30" rows="3"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success" >Simpan</button>
                    </div>
                </form>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
@endpush

@push('scripts')
<script>
    $('#data-table').DataTable({
        //   "pageLength": 3

    });
    $('#data-table2').DataTable({
        //   "pageLength": 3

    });

</script>
@endpush
