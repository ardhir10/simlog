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
                        <a href="{{route('rab.batalkan',$data->id ?? null)}}" onclick="return confirm('Yakin batalkan permintaan, semua data akan dihapus ?')">
                            <button class="btn btn-danger">
                                <i class="fa fa-times"></i>
                                Batalkan Permintaan
                            </button>
                        </a>
                    </div>
                </div>


                <form action="{{route('rab.store',$data->id ?? null)}}" enctype="multipart/form-data" method="POST">
                    @csrf
                    <div class="card-body p-4">
                        <div class="col-12">
                            @include('components.flash-message')
                        </div>
                        <div class="fw-bold mb-3">{{$data->nomor_rab ?? null}}</div>
                        <div class="col-lg-12">
                            <div class="row mb-3">
                                <div class="col-lg-7 mb-3">
                                    <div class="form-group">
                                        <label for="">Rencana Kebutuhan</label>
                                        @if ($data->rencana_kebutuhan_id ?? null)
                                            <select name="rencana_kebutuhan_id" class="form-select select2" disabled>
                                                <option  value="">PILIH RENCANA KEBUTUHAN</option>
                                                @foreach ($rencana_kebutuhan as $rk)
                                                    <option {{($rk->id ?? null ) == $data->rencana_kebutuhan_id ?? null ? 'selected=selected' :''}} value="{{$rk->id}}">{{$rk->nomor_rk}}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select name="rencana_kebutuhan_id" class="form-select select2" >
                                                <option  value="">PILIH RENCANA KEBUTUHAN</option>
                                                @foreach ($rencana_kebutuhan as $rk)
                                                    <option {{($rk->id ?? null ) == ($data->rencana_kebutuhan_id ?? null) ? 'selected=selected' :''}} value="{{$rk->id}}">{{$rk->nomor_rk}}</option>
                                                @endforeach
                                            </select>
                                        @endif

                                    </div>
                                </div>
                                <div class="col-lg-6 ">
                                    <div class="form-group">
                                        <label for="">Kegiatan</label>
                                        <input type="text" name="kegiatan" class="form-control" value="{{$data->kegiatan ?? null}}" required>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">MAK</label>
                                        <input type="text" name="mak" class="form-control" value="{{$data->mak ?? null}}" required>
                                    </div>
                                </div>

                            </div>
                            <div class="row mb-3">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Pengguna</label>
                                        <select name="pengguna" class="form-select " required>
                                            <option {{($data->pengguna ?? null ) == '01' ? 'selected=selected' :''}}  value="01">(01) Umum</option>
                                            <option {{($data->pengguna ?? null ) == '02' ? 'selected=selected' :''}}  value="02">(02) Sie Kepeg & Umum</option>
                                            <option {{($data->pengguna ?? null ) == '03' ? 'selected=selected' :''}}  value="03">(03) Sie Keuangan</option>
                                            <option {{($data->pengguna ?? null ) == '04' ? 'selected=selected' :''}}  value="04">(04) Sie Pengadaan</option>
                                            <option {{($data->pengguna ?? null ) == '05' ? 'selected=selected' :''}}  value="05">(05) Sie Inventaris</option>
                                            <option {{($data->pengguna ?? null ) == '06' ? 'selected=selected' :''}}  value="06">(06) Sie SarPras</option>
                                            <option {{($data->pengguna ?? null ) == '07' ? 'selected=selected' :''}}  value="07">(07) Sie Program & Evaluasi</option>
                                            <option {{($data->pengguna ?? null ) == '08' ? 'selected=selected' :''}}  value="08">(08) SBNP</option>
                                            <option {{($data->pengguna ?? null ) == '09' ? 'selected=selected' :''}}  value="09">(09) Telkompel</option>
                                            <option {{($data->pengguna ?? null ) == '10' ? 'selected=selected' :''}}  value="10">(10) Pengla</option>
                                            <option {{($data->pengguna ?? null ) == '11' ? 'selected=selected' :''}}  value="11">(11) KNK</option>
                                            <option {{($data->pengguna ?? null ) == '12' ? 'selected=selected' :''}}  value="12">(12) Bengkel</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="">Tahun Anggaran</label>
                                         <select name="tahun_anggaran" id="" required class="form-select select2">
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


                                <div class="col-lg-12 mt-4">
                                    <div class="fw-bold mb-3">Rencana Anggaran Biaya</div>
                                    <div class="row mb-3">

                                        @if ($data->rencana_kebutuhan_id ?? null)
                                            <div class="col-lg-12">
                                                <table class="table">
                                                    <thead class="bg-success text-white">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nama Barang</th>
                                                            <th>Satuan</th>
                                                            <th>Qty</th>
                                                            <th>Harga Satuan</th>
                                                            <th>Keterangan</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($rab_details as $rd)
                                                            <tr>
                                                                <td>{{$loop->iteration}}</td>
                                                                <td>{{$rd->nama_barang}}</td>
                                                                <td>{{$rd->satuan}}</td>
                                                                <td>{{$rd->qty}}</td>
                                                                <td>
                                                                    <input type="text" name="harga_satuan[{{$rd->id}}]" value="{{$rd->harga_satuan}}" required>
                                                                    <select  name="mata_uang[{{$rd->id}}]" class="" id="" required>
                                                                        <option value="IDR">IDR</option>
                                                                    </select>
                                                                </td>

                                                                <td>{{$rd->keterangan}}</td>
                                                                <td>
                                                                    <a href="{{route('rab.delete-item',$rd->id)}}" onclick="return confirm('Yakin hapus item ?')">
                                                                        <button class="btn btn-danger">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                        </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="col-lg-12">
                                                <table class="table">
                                                    <thead class="bg-success text-white">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nama Barang</th>
                                                            <th>Satuan</th>
                                                            <th>Qty</th>
                                                            <th>Harga Satuan</th>
                                                            <th>Jumlah</th>
                                                            <th>Keterangan</th>
                                                            <th></th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($rab_details as $rd)
                                                            <tr>
                                                                <td>{{$loop->iteration}}</td>
                                                                <td>{{$rd->nama_barang}}</td>
                                                                <td>{{$rd->satuan}}</td>
                                                                <td>{{$rd->qty}}</td>
                                                                <td>{{$rd->harga_satuan}}</td>
                                                                <td>{{$rd->mata_uang}}&nbsp;{{ $rd->harga_satuan * $rd->qty}}</td>
                                                                <td>{{$rd->keterangan}}</td>
                                                                <td>
                                                                    <a href="{{route('rab.delete-item',$rd->id)}}" onclick="return confirm('Yakin hapus item ?')">
                                                                        <button class="btn btn-danger">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                        </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>

                                        @endif


                                    </div>
                                </div>

                            </div>

                            @if ($data)
                                @if ($rab_details)
                                    <input type="hidden" name="submit" value="AJUKAN">
                                    <button class="btn  btn-success btn-lg">
                                    <i class="fa fa-list-alt"></i>
                                    Ajukan RAB </button>
                                @endif

                            @else
                                    <input type="hidden" name="submit" value="PILIH ITEM">
                                <button class="btn  btn-warning btn-lg">
                                <i class="fa fa-list-alt"></i>
                                Pilih Item </button>
                            @endif

                        </div>
                    </div>
                </form>
                <hr>




            </div>
            @if ($data)
                @if ($data->rencana_kebutuhan_id == null)
                    <div class="card shadow-lg">
                        <form action="{{route('rab.input-item',$data->id ?? null)}}" method="POST">
                            @csrf
                            <div class="card-body p-4">
                                <div class="col-12">
                                    @include('components.flash-message')
                                </div>
                                <div class="fw-bold mb-3">Input Item RAB</div>
                                <div class="col-lg-12">
                                    <div class="row mb-3">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label for="">Nama Barang</label>
                                                <input type="hidden" name="barang_id" class="form-control" value="" required>
                                                <input type="text" name="nama_barang" class="form-control" value="" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="">Satuan</label>
                                                <select name="satuan" id="" class="form-select" required>
                                                    @foreach ($satuan as $s)
                                                    <option value="{{$s->nama_satuan}}">{{$s->nama_satuan}}</option>

                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="">Pilih Barang</label>
                                                <button class="btn btn-warning w-100" type="button" data-bs-toggle="modal" data-bs-target="#barangExistingList">Pilih Barang Existing</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <div class="col-lg-2">
                                            <div class="form-group">
                                                <label for="">Qty</label>
                                                <input type="number" name="qty" class="form-control" value="" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="">Harga Satuan</label>
                                                <input type="number" class="form-control" name="harga_satuan" required>
                                            </div>
                                        </div>
                                        <div class="col-lg-3">
                                            <div class="form-group">
                                                <label for="">Mata Uang</label>
                                                <select name="mata_uang"  class="form-select" id="" required>
                                                    <option value="IDR">IDR</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-lg-6 mt-2">
                                            <div class="form-group">
                                                <label for="">Keterangan</label>
                                                <textarea name="keterangan"  rows="3" class="form-control" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="btn btn-primary btn-sm ">INPUT ITEM</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif

            @endif
    </div>

    <!-- end row -->

</div> <!-- container-fluid -->
</div>
@endsection

@push('modals')
<div>
    <!-- sample modal content -->
    <div id="barangExistingList" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Barang Existing</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">

                    </button>
                </div>
                <div class="modal-body">
                    <div class="table-reponsive">
                        <table class="table" id="dt-barang-exisisting">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Foto</th>
                                    <th>Kategori</th>
                                    <th>Nama Barang</th>
                                    <th>Kode Barang</th>
                                    <th>Peruntukkan</th>
                                    <th>Stock</th>
                                    <th>Harga Perolehan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($barang_persediaan as $item)
                                    <tr class='clickable-row' onclick="pilihBarang({{$item->id}})">
                                        <td>{{$loop->iteration}}</td>
                                        <td>
                                            <img src="{{asset('dokumen_barang/foto_barang/'.$item->foto_barang)}}" class="img-fluid" alt="">
                                        </td>
                                        <td>{{$item->kategori_barang->nama_kategori ?? 'Tidak Dalam Kategori'}}</td>
                                        <td>{{$item->nama_barang}}</td>
                                        <td>{{$item->kode_barang}}</td>
                                        <td>{{$item->sub_sub_kategori}}</td>
                                        <td style="white-space:nowrap">{{$item->stokBarang()}} {{$item->satuan->nama_satuan ?? ''}}</td>
                                        <td>{{$item->harga_perolehan}}</td>

                                    </tr>
                                @endforeach
                            </tbody>

                        </table>

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Batal</button>
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

    $('#dt-barang-exisisting').DataTable({
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
