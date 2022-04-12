@extends('main')


@push('styles')

<style>
    .bg-ditolak {
        background: #ED1F24 !important;
    }

    .bg-proses {
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
                    <div class="float-end">
                         <a href="{{route('permintaan-barang.batalkan-permintaan',$data->id)}}" onclick="return confirm('Yakin batalkan permintaan, semua data akan dihapus ?')">
                                <button class=" btn btn-danger">
                                <i class="fa fa-times"></i>
                                Batalkan Permintaan</button>
                            </a>

                    </div>

                </div>
                <div class="card-body p-4">
                    <div class="col-12">
                        @include('components.flash-message')
                    </div>
                    <div class="col-lg-12">
                        <div class="row mb-3">
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Nomor Nota Dinas</label>
                                    <input type="text" class="form-control" value="{{$data->nomor_nota_dinas}}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Nomor UPP3</label>
                                    <input type="text" class="form-control" value="{{$data->nomor_upp3}}" readonly>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="form-group">
                                    <label for="">Tanggal Permintaan</label>
                                    <input type="text" class="form-control" value="{{date('Y-m-d',strtotime($data->tanggal_permintaan))}}" readonly>
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="card">
                            <div class="card-body">
                                <span class="d-block fs-4 mb-3">Pilih Barang Persediaan</span>
                                <form action="">
                                    <span for="" class="d-block mb-2">Kategori Barang</span>
                                    <div class="row mb-4">
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <select name="kategori" id="" class="form-select">
                                                    <option value="all">Semua</option>
                                                    @foreach ($kategori_barang as $item)
                                                        <option {{Request::get('kategori') == $item->id ? 'selected=selected':''}} value="{{$item->id}}">{{$item->nama_kategori}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <input type="text" name="namakode" value="{{Request::get('namakode')}}" class="form-control"
                                                    placeholder="masukkan nama/kode barang">
                                            </div>
                                        </div>
                                        <div class="col-lg-2">
                                        <button class="btn btn-success w-100">Cari</button>
                                        </div>
                                    </div>
                                </form>

                                <span for="" class=" mb-5 fw-bold ">Barang Persediaan</span>
                                @if (Request::get('kategori'))
                                    <span class="font-italic">( {{count($barang_persediaan)}} Hasil pencarian )</span>

                                @endif
                                <div class="row mt-3">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table-striped datatables" id="data-table"
                                                style="font-size: 16px">
                                                <thead>
                                                    <tr class="tr-head">
                                                        <th class="td-head" width="1%" style="white-space: nowrap;">No
                                                        </th>
                                                        <th class="td-head" style="white-space: nowrap;">Nama Barang
                                                        </th>
                                                        <th class="td-head" style="white-space: nowrap;">Kode</th>
                                                        <th class="td-head" style="white-space: nowrap;">Kategori</th>
                                                        <th class="td-head" style="white-space: nowrap;">Stock</th>
                                                        <th class="td-head" style="white-space: nowrap;">Satuan</th>
                                                        <th class="td-head" style="white-space: nowrap;"></th>
                                                    </tr>
                                                </thead>
                                                <tbody>

                                                    @foreach ($barang_persediaan as $item)
                                                    <tr>
                                                        <td>{{$loop->iteration}}</td>
                                                        <td>{{$item->nama_barang}}</td>
                                                        <td>{{$item->kode_barang}}</td>
                                                        <td>{{$item->kategori_barang->nama_kategori ?? null}}</td>
                                                        <td>{{$item->jumlah}}</td>
                                                        <td>{{$item->satuan->nama_satuan}}</td>
                                                        <td style="width: 30%">
                                                            <form action="{{route('permintaan-barang.add-barang',['id'=>$item->id,'permintaanBarangId'=>$data->id])}}" method="post">
                                                                @csrf
                                                                <div class="d-flex">
                                                                    <input type="number" style="width: 100px;" name="jumlah" class="me-2" value="1" min="1" max="{{$item->jumlah}}">
                                                                    <input type="hidden"  id="beritaTambahan{{$item->id}}" value="" name="berita_tambahan">
                                                                    <button type="button" class="btn btn-sm btn-secondary me-2" onclick="popupBeritaTambahan('beritaTambahan{{$item->id}}')" data-bs-toggle="modal" data-bs-target="#myModal">
                                                                        <i class="fa fa-sticky-note"></i> Catatan
                                                                    </button>
                                                                    <button type="submit" class="btn btn-sm btn-success me-2">
                                                                        <i class="fa fa-plus"></i> minta barang
                                                                    </button>
                                                                </div>
                                                            </form>

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

                        <hr>
                        <div class="card">
                            <div class="card-body">
                                <span class="d-block fs-4 mb-3">Daftar Barang Yang Diminta</span>

                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="table-responsive">
                                            <table class="table  w-100">

                                                @foreach ($barang_diminta as $bd)
                                                    <tr style="border-bottom: 1px solid #74788D">
                                                        <td>{{$loop->iteration}}</td>
                                                        <td style="width: 30%">
                                                            <div>
                                                                <span class="d-block fs-5 fw-bold">{{$bd->barang->nama_barang ?? 'N/A'}}</span>
                                                                <span class="">{{$bd->barang->kode_barang ?? 'N/A'}}</span>
                                                                <span class="">{{$bd->barang->kategori_barang->nama_kategori ?? 'N/A'}}</span>
                                                                <span class="d-block">Sisa Stock : {{$bd->barang->jumlah ?? 'N/A'}}</span>

                                                            </div>
                                                        </td>
                                                        <td style="width: 5%">{{$bd->jumlah}}</td>
                                                        <td style="width: 5%">{{$bd->barang->satuan->nama_satuan ?? 'N/A'}}</td>
                                                        <td style="width: 30%">
                                                            <div class="d-flex">
                                                                <i class="fa fa-sticky-note me-3"></i>
                                                                <div>
                                                                    <p>{{$bd->berita_tambahan}}</p>
                                                                </div>

                                                            </div>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex">
                                                                {{-- <button class="btn btn-success me-2">
                                                                    <i class="fa fa-edit"></i>
                                                                </button> --}}
                                                                <a href="{{route('permintaan-barang.delete-barang',$bd->id)}}" onclick="return confirm('Yakin hapus item ?')">
                                                                <button class="btn btn-danger">
                                                                    <i class="fa fa-trash"></i>
                                                                </button>
                                                                </a>

                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach


                                            </table>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <form action="{{route('permintaan-barang.ajukan-permintaan',$data->id)}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-lg-8">
                                    <div class="form-group">
                                        <label for="">Perihal</label>
                                        <textarea class="form-control" name="perihal" cols="" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="float-start">
                                <a href="{{route('permintaan-barang.batalkan-permintaan',$data->id)}}" onclick="return confirm('Yakin batalkan permintaan, semua data akan dihapus ?')">
                                    <button  type="button" class=" btn btn-danger">
                                    <i class="fa fa-times"></i>
                                    Batalkan Permintaan</button>
                                </a>

                                <button class="btn btn-success" type="submit" onclick="return confirm('Konfirmasi pengajuan permintaan ?')">Ajukan Permintaan</button>
                            </div>
                        </form>
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
                    <h5 class="modal-title" id="myModalLabel">Berita Tambahan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Berita Tambahan</label>
                        <input type="hidden" id="beritaTambahanId" value="">
                        <textarea name="" id="beritaTambahan"  cols="30" rows="5" class="form-control"></textarea>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-success" id="simpanBeritaTambahan">Simpan</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
</div>
@endpush

@push('scripts')
<script>
    function popupBeritaTambahan(id){
        let BeritaTambahan = $('#'+id).val();
        $('#beritaTambahanId').val(id);
        $('#beritaTambahan').val(BeritaTambahan);
    }
    $('#simpanBeritaTambahan').on('click',function(){
        let beritaTambahanId = $('#beritaTambahanId').val();
        let BeritaTambahan = $('#beritaTambahan').val();


        $('#'+beritaTambahanId).val(BeritaTambahan);
        $('#myModal').modal('toggle');
    })
    $('#data-table').DataTable({
        "pageLength": 3,
        ordering: false,
        paging: false,
        "searching": false,
        keys: true, //enable KeyTable extension
    });


    $('.btn-number').click(function (e) {
        e.preventDefault();
        fieldName = $(this).attr('data-field');
        type = $(this).attr('data-type');
        var input = $("input[name='" + fieldName + "']");
        var currentVal = parseInt(input.val());
        if (!isNaN(currentVal)) {
            if (type == 'minus') {

                if (currentVal > input.attr('min')) {
                    input.val(currentVal - 1).change();
                }
                if (parseInt(input.val()) == input.attr('min')) {
                    $(this).attr('disabled', true);
                }

            } else if (type == 'plus') {

                if (currentVal < input.attr('max')) {
                    input.val(currentVal + 1).change();
                }
                if (parseInt(input.val()) == input.attr('max')) {
                    $(this).attr('disabled', true);
                }

            }
        } else {
            input.val(0);
        }
    });
    $('.input-number').focusin(function () {
        $(this).data('oldValue', $(this).val());
    });
    $('.input-number').change(function () {

        minValue = parseInt($(this).attr('min'));
        maxValue = parseInt($(this).attr('max'));
        valueCurrent = parseInt($(this).val());

        name = $(this).attr('name');
        if (valueCurrent >= minValue) {
            $(".btn-number[data-type='minus'][data-field='" + name + "']").removeAttr('disabled')
        } else {
            alert('Sorry, the minimum value was reached');
            $(this).val($(this).data('oldValue'));
        }
        if (valueCurrent <= maxValue) {
            $(".btn-number[data-type='plus'][data-field='" + name + "']").removeAttr('disabled')
        } else {
            alert('Sorry, the maximum value was reached');
            $(this).val($(this).data('oldValue'));
        }
    });

    $(".input-number").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
            // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    });

</script>
@endpush
