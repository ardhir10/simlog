<div class="row mb-3">
    <div class="col-lg-4">
        <div class="form-group">
            <label for=""><strong>Kategori Barang</strong></label>
            <select name="kategori_barang_id" class="form-select select2" required>
                <option value="">-- PILIH KATEGORI BARANG</option>
                @foreach ($kategori_barang as $item)
                    <option value="{{$item->id}}">({{$item->kode_kategori}}) {{$item->nama_kategori}} </option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label for=""><strong>Nama Barang</strong></label>
            <input type="text" class="form-control" name="nama_barang">
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label for=""><strong>kode Barang</strong></label>
            <input type="text" class="form-control" name="kode_barang">
        </div>
    </div>
</div>




<div class="row mb-3">
    <div class="col-lg-4">
        <div class="form-group">
            <label for=""><strong>Tahun Perolehan</strong></label>
            <select name="tahun_perolehan" id="" required class="form-select select2">
                <?php
                for ($x=date("Y"); $x>1900; $x--)
                {
                        echo'<option value="'.$x.'">'.$x.'</option>';
                }
                ?>
            </select>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label for=""><strong>Jumlah</strong></label>
            <input type="number" class="form-control" name="jumlah">
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label for=""><strong>Satuan</strong></label>
            <select name="satuan_id" class="form-select select2" required>
                <option value="">-- PILIH SATUAN</option>
                @foreach ($satuan as $item)
                    <option value="{{$item->id}}">{{$item->nama_satuan}} </option>
                @endforeach
            </select>
        </div>
    </div>
</div>


<div class="row mb-3">
    <div class="col-lg-3">
        <div class="form-group">
            <label for=""><strong>Harga Perolehan</strong></label>
            <input type="number" step="0.1" class="form-control" name="harga_perolehan">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for=""><strong>Mata Uang</strong></label>
            <select name="mata_uang" class="form-select " required>
                <option value="">-- PILIH MATA UANG</option>
                <option value="IDR">IDR (Rp)</option>
                <option value="USD">USD ($)</option>
            </select>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for=""><strong>Masa Simpan (Bulan)</strong></label>
            <select name="masa_simpan" class="form-select select2" required>
                @for ($i = 1; $i <= 200; $i++)
                    <option value="{{$i}}">{{$i}} Bulan</option>
                @endfor
            </select>
        </div>
    </div>
     <div class="col-lg-3">
        <div class="form-group">
            <label for=""><strong>Jml. Stock Minimal</strong></label>
            <input type="number"  class="form-control" name="jumlah_stok_minimal">
        </div>
    </div>
</div>



<div class="row mb-3">
    <div class="col-lg-12">
        <div class="form-group">
            <label for=""><strong>Spesifikasi Barang</strong></label>
              <textarea id="summernote" class="d-none" rows="15" name="spesifikasi_barang"></textarea>
        </div>
    </div>
</div>


<div class="row mb-3">
    <div class="col-lg-12">
        <div class="form-group">
            <label for=""><strong>Foto Barang</strong></label>
            <input type="file" class="form-control" name="foto_barang">
        </div>
    </div>
</div>


