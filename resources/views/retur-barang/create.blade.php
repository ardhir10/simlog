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
                            
                            <a href="{{route('retur-barang.batalkan', $data->id ?? null)}}" onclick="return confirm('Yakin batalkan permintaan, semua data akan dihapus ?')">
                                <button class="btn btn-danger">
                                    <i class="fa fa-times"></i>
                                    BATALKAN RETUR
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card shadow-lg">
                    <form action="{{route('retur-barang.simpan', $data->id ?? null)}}" method="POST">
                        @csrf
                        <div class="card-body p-4">
                            <div class="col-12">
                                @include('components.flash-message')
                            </div>
                            <div class="fw-bold mb-3">NOMOR RETUR</div>
                            <div class="col-lg-12">
                                <div class="row mb-3">
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Perihal</label>
                                            <input type="text" name="perihal" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-group">
                                            <label for="">Alasan Retur</label>
                                            <input type="text" name="alasan_retur" class="form-control" value="">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 mt-3">
                                        <div class="form-group">
                                            <label for="">Keterangan</label>
                                            <textarea name="keterangan" class="form-control" id="" cols="30" rows="3"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="fw-bold mb-3">LIST BARANG YANG DI RETUR</div>
                                <table class="table table-bordered">
                                    <thead class="bg-success "> 
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Kode</th>
                                            <th>kategori</th>
                                            <th>Jumlah</th>
                                            <th>Satuan</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data->retur_detail as $item)
                                            <tr>
                                                <td>{{$loop->iteration}}</td>
                                                <td>{{$item->barang->nama_barang ?? null}}</td>
                                                <td>{{$item->barang->kode_barang ?? null}}</td>
                                                <td>{{$item->barang->kategori_barang->nama_kategori ?? null}}</td>
                                                <td>{{$item->jumlah_retur ?? null}}</td>
                                                <td>{{$item->barang->satuan->nama_satuan ?? null}}</td>
                                                <td>
                                                    <a href="{{route('retur-barang.hapus-barang', $item->id ?? null)}}" onclick="return confirm('Yakin hapus item ?')">
                                                        <button class="btn btn-danger" type="button">
                                                            <i class="fa fa-trash"></i>
                                                            
                                                        </button>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                            @if (count($data->retur_detail))
                                <button class="btn btn-lg mt-3 btn-success" type="submit">RETUR BARANG</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <div class="fw-bold  fs-4">Pilih Barang Retur</div>
                        <div class="fw-bold mb-3 text-success fs-6">List Barang Yang Belum Didistibusikan</div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dt-barang-bd">
                                <thead class="bg-success "> 
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Kode</th>
                                        <th>UPP4</th>
                                        <th>Jumlah</th>
                                        <th>Retur</th>
                                        <th>Satuan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($list_barang_belum_distribusi as $item)
                                            <tr>
                                                <form action="{{route('retur-barang.tambah-barang')}}" method="POST">
                                                    <td>{{$loop->iteration}}</td>
                                                    <td>{{$item->barang->nama_barang ?? null}}</td>
                                                    <td>{{$item->barang->kode_barang ?? null}}</td>
                                                    <td>{{$item->permintaanBarang->nomor_upp4 ?? null}}</td>
                                                    <td>{{$item->barangBelumDistribusi() ?? null}}</td>
                                                    <td>
                                                        @csrf
                                                        <input type="hidden" name="retur_id" value="{{$data->id ?? null}}">
                                                        <input type="hidden" name="nama_barang" value="{{$item->barang->nama_barang ?? null}}">
                                                        <input type="hidden" name="kode_barang" value="{{$item->barang->kode_barang ?? null}}">
                                                        <input type="hidden" name="nomor_nota_dinas" value="{{$item->permintaanBarang->nomor_nota_dinas ?? null}}">
                                                        <input type="hidden" name="nomor_upp3" value="{{$item->permintaanBarang->nomor_upp3 ?? null}}">
                                                        <input type="hidden" name="nomor_upp4" value="{{$item->permintaanBarang->nomor_upp4 ?? null}}">
                                                        <input type="hidden" name="permintaan_barang_id" value="{{$item->permintaan_barang_id ?? null}}">
                                                        <input type="hidden" name="permintaan_barang_detail_id" value="{{$item->id ?? null}}">
                                                        <input type="hidden" name="barang_id" value="{{$item->barang_persediaan_id ?? null}}">
                                                         
                                                        <input type="number" style="width: 100%" name="jumlah_retur" max="{{($item->barangBelumDistribusi() ?? 0) - ($item->barang_retur->jumlah_retur ?? 0)}}" min="0" value="">
                                                    </td>
                                                    <td>{{$item->barang->satuan->nama_satuan ?? null}}</td>
                                                    <td style="width: 190px">
                                                        <button class="btn btn-success" type="submit">
                                                            <i class="fa fa-plus"></i>
                                                            TAMBAH BARANG</button>
                                                    </td>
                                                </form>
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

    $('#dt-barang-bd').DataTable({
        "pageLength": 3,
        ordering: true,
        paging: false,
        "searching": true,
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

    async function pilihBarang(id){
        let resp = await axios.get(@json(route('rab.get-barang-persediaan'))+'/'+id);
        console.log(resp.data);
        let data = resp.data;
        $("input[name='barang_id']").val(data.id);
        $("input[name='nama_barang']").val(data.nama_barang);
        $("select[name='satuan']").val(data.satuan.nama_satuan);
        $("input[name='harga_satuan']").val(data.harga_perolehan);
        $('#barangExistingList').modal('toggle');
    }
</script>
@endpush
