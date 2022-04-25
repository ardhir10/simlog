<div class="row mb-3">
    <div class="col-lg-3">
        <div class="form-group">
            <label for=""><strong>Kategori Barang</strong></label>

            <select name="kategori_barang_id" class="form-select select2" required disabled>
                <option value="">-- PILIH KATEGORI BARANG</option>
                @foreach ($kategori_barang->where('parent_id',null) as $item)
                    <option {{$data->kategori_barang_id == $item->id ? 'selected=selected':''}} value="{{$item->id}}" style="font-weight: bold"><strong>({{$item->kode_kategori}}) {{$item->nama_kategori}} </strong> </option>
                    @foreach ($kategori_barang->where('parent_id',$item->id) as $item)
                        <option {{$data->kategori_barang_id == $item->id ? 'selected=selected':''}} value="{{$item->id}}">&nbsp;&nbsp;&nbsp;&nbsp;({{$item->kode_kategori}}) {{$item->nama_kategori}} </option>
                    @endforeach
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for=""><strong>Peruntukkan</strong></label>
            <select name="sub_sub_kategori" class="form-select select2" required>


                <option {{$data->sub_sub_kategori == '01' ? 'selected=selected':''}}  value="01">(01) Umum</option>
                <option {{$data->sub_sub_kategori == '02' ? 'selected=selected':''}}  value="02">(02) Sie Kepeg & Umum</option>
                <option {{$data->sub_sub_kategori == '03' ? 'selected=selected':''}}  value="03">(03) Sie Keuangan</option>
                <option {{$data->sub_sub_kategori == '04' ? 'selected=selected':''}}  value="04">(04) Sie Pengadaan</option>
                <option {{$data->sub_sub_kategori == '05' ? 'selected=selected':''}}  value="05">(05) Sie Inventaris</option>
                <option {{$data->sub_sub_kategori == '06' ? 'selected=selected':''}}  value="06">(06) Sie SarPras</option>
                <option {{$data->sub_sub_kategori == '07' ? 'selected=selected':''}}  value="07">(07) Sie Program & Evaluasi</option>
                <option {{$data->sub_sub_kategori == '08' ? 'selected=selected':''}}  value="08">(08) SBNP</option>
                <option {{$data->sub_sub_kategori == '09' ? 'selected=selected':''}}  value="09">(09) Telkompel</option>
                <option {{$data->sub_sub_kategori == '10' ? 'selected=selected':''}}  value="10">(10) Pengla</option>
                <option {{$data->sub_sub_kategori == '11' ? 'selected=selected':''}}  value="11">(11) KNK</option>
                <option {{$data->sub_sub_kategori == '12' ? 'selected=selected':''}}  value="12">(12) Bengkel</option>
            </select>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for=""><strong>Nama Barang</strong></label>
            <input type="text" class="form-control" name="nama_barang" value="{{$data->nama_barang}}" disabled>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for=""><strong>kode Barang</strong></label>
            <input type="text" class="form-control" name="kode_barang" value="{{$data->kode_barang}}" disabled>
        </div>
    </div>
</div>

{{-- <div class="row mb-3">
    <div class="col-lg-6">
        <div class="form-group">
            <label for=""><strong>Nomor Berita Acara Serah Terima</strong></label>
            <input type="text" class="form-control" name="nomor_bast" value="{{$data->nomor_bast}}">
        </div>
    </div>
    <div class="col-lg-6">
        <div class="form-group">
            <label for=""><strong>Upload BAST</strong></label>
            @if ($data->dokumen_bast)
                <a href="{{asset('dokumen_barang/dokumen_bast/'.$data->dokumen_bast)}}" target="_blank">Download Dokumen BAST</a>
            @endif
            <input type="file" class="form-control" name="dokumen_bast">
        </div>
    </div>
</div> --}}


<div class="row mb-3">
    <div class="col-lg-4">
        <div class="form-group">
            <label for=""><strong>Tahun Perolehan </strong></label>
            <select name="tahun_perolehan" id="" required class="form-select select2">
                <?php
                for ($x=date("Y"); $x>1900; $x--)
                {
                        if ($data->tahun_perolehan == $x) {
                            echo'<option selected=selected value="'.$x.'">'.$x.'</option>';
                            # code...
                        }else{
                            echo'<option value="'.$x.'">'.$x.'</option>';

                        }
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label for=""><strong>Jumlah</strong></label>
            <input type="number" class="form-control" name="jumlah" value="{{$data->jumlah}}">
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label for=""><strong>Satuan</strong></label>
            <select name="satuan_id" class="form-select select2" required disabled>
                <option value="">-- PILIH SATUAN</option>
                @foreach ($satuan as $item)
                    <option  {{$data->satuan_id == $item->id ? 'selected=selected':''}} value="{{$item->id}}">{{$item->nama_satuan}} </option>
                @endforeach
            </select>
        </div>
    </div>
</div>


<div class="row mb-3">
    <div class="col-lg-3">
        <div class="form-group">
            <label for=""><strong>Harga Perolehan</strong></label>
            <input type="number" step="0.1" class="form-control" name="harga_perolehan" value="{{$data->harga_perolehan}}">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for=""><strong>Mata Uang</strong></label>
            <select name="mata_uang" class="form-select " required>
                <option value="">-- PILIH MATA UANG</option>
                <option {{$data->mata_uang == 'IDR' ? 'selected=selected':''}} value="IDR">IDR (Rp)</option>
                <option {{$data->mata_uang == 'USD' ? 'selected=selected':''}} value="USD">USD ($)</option>
            </select>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for=""><strong>Masa Simpan (Bulan)</strong></label>
            <select name="masa_simpan" class="form-select select2" required disabled>
                @for ($i = 1; $i <= 200; $i++)
                    <option {{$data->masa_simpan == $i ? 'selected=selected':''}}  value="{{$i}}">{{$i}} Bulan</option>
                @endfor
            </select>
        </div>
    </div>
     <div class="col-lg-3">
        <div class="form-group">
            <label for=""><strong>Jml. Stock Minimal</strong></label>
            <input type="number"  class="form-control" name="jumlah_stok_minimal" value="{{$data->jumlah_stok_minimal}}" disabled>
        </div>
    </div>
</div>



<div class="row mb-3">
    <div class="col-lg-12">
        <div class="form-group">
            <label for=""><strong>Spesifikasi Barang</strong></label>
              <textarea id="summernote" class="d-none" rows="15" name="spesifikasi_barang" class="summernote" disabled></textarea>
        </div>
    </div>
</div>

{{--
<div class="row mb-3">
    <div class="col-lg-12">
        <div class="form-group">
            <label for=""><strong>Foto Barang</strong></label>
            <input type="file" class="form-control" name="foto_barang">
        </div>
    </div>
</div> --}}


