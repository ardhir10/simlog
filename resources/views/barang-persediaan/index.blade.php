


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
                <div class="d-flex">

                    <button data-bs-toggle="modal" data-bs-target="#importModal" class="btn btn-outline btn-outline-primary  me-1 btn-rounded"> Import Barang
                        <i class="fa fa-upload align-middle"></i>
                    </button>
                    <a href="{{route('barang-persediaan.create')}}" class="btn btn-outline btn-outline-success  btn-rounded"> Tambah Barang
                        <i class="fa fa-plus align-middle"></i>
                    </a>
                </div>
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
                                <th class="td-head" width="1%">Action</th>
                                <th class="td-head" width="20%">Foto</th>

                                <th class="td-head" width="20%" style="white-space: nowrap;">Kategori</th>
                                <th class="td-head" width="20%" style="white-space: nowrap;">Nama Barang</th>
                                <th class="td-head" width="20%" style="white-space: nowrap;">Kode Barang</th>
                                <th class="td-head" width="20%">Peruntukkan</th>
                                <th class="td-head" width="20%">Stok Tsd</th>
                                <th class="td-head" width="20%">Stok Msk</th>
                                <th class="td-head" width="20%">Stok Klr</th>
                                <th class="td-head" width="20%" style="white-space: nowrap;">Harga Perolehan</th>
                                {{-- <th class="td-head" width="20%" style="white-space: nowrap;">Masa Simpan</th> --}}
                                <th class="td-head" width="20%">Min. Stock</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($barang_persediaan as $item)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{route('barang-persediaan.create',['id'=>$item->id])}}" data-toggle="tooltip" title="Barang Baru"
                                            class="btn btn rounded-3 btn-outline btn-outline-secondary me-1">
                                            <i class="fa fa-money-bill-alt"></i>
                                        </a>
                                        <a href="{{route('barang-persediaan.stock-detail',$item->id)}}" data-toggle="tooltip" title="Detail Stok"
                                            class="btn btn rounded-3 btn-outline btn-outline-primary me-1">
                                            <i class="fa fa-box"></i>
                                        </a>
                                        <a href="{{route('barang-persediaan.edit',['id'=>$item->id,'sumber_barang'=>$item->sumber_barang])}}" data-toggle="tooltip" title="Edit Data"
                                            class="btn btn rounded-3 btn-outline btn-outline-success me-1">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button class="btn btn rounded-3 btn-outline btn-outline-danger" data-toggle="tooltip" title="Hapus Data" onclick="return confirmDelete('{{route('barang-persediaan.delete',$item->id)}}')">
                                            <i class="fa fa-trash"></i></button>
                                    </div>
                                </td>
                                <td>
                                    <img src="{{asset('dokumen_barang/foto_barang/'.$item->foto_barang)}}" class="img-fluid" alt="">
                                </td>
                                <td>{{$item->kategori_barang->nama_kategori ?? 'Tidak Dalam Kategori'}}</td>
                                <td>{{$item->nama_barang}}</td>
                                <td>{{$item->kode_barang}}</td>
                                <td>{{$item->peruntukkan()}}</td>
                                <td style="white-space:nowrap">{{$item->stokBarang()}} {{$item->satuan->nama_satuan ?? ''}}</td>
                                <td>{{$item->barangMasuk->sum('jumlah')}}</td>
                                <td>{{$item->barangkeluar->sum('jumlah')}}</td>
                                <td>{{$item->mata_uang}} {{number_format($item->harga_perolehan,0,'.',',')}}</td>
                                {{-- <td>{{$item->masa_simpan}} Bulan</td> --}}
                                <td>{{$item->jumlah_stok_minimal}} {{$item->satuan->nama_satuan ?? ''}}</td>


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
<!-- MODAL IMPORT -->
<div id="importModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Import Data From Excel</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>

            </div>
            <div class="modal-body">
                <form action="{{route('barang-persediaan.import')}}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="">Upload File Excel</label>
                        <input type="file" name="file" class="form-control">
                    </div>
                    <button class="btn btn-success mt-3">Import Data</button>
                </form>

            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endpush

@push('scripts')
<script>
    $('#data-table').DataTable({
        //   "pageLength": 3

    });

</script>
@endpush
