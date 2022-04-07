<div class="card  animate__animated  animate__fadeIn" id="">
    <div class="card-body">
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group mb-2">
                    <label class="form-label" for=""><strong>UserId</strong></label>
                    <input type="text" name="username" class="form-control" placeholder="">
                </div>
                <div class="form-group mb-2">
                    <label class="form-label" for=""><strong>Password</strong></label>
                    <input type="password" name="password" class="form-control" placeholder="">
                </div>
                <div class="form-group mb-2">
                    <label class="form-label" for=""><strong>Konfirmasi Password</strong></label>
                    <input type="password" name="password_confirmation" class="form-control"
                        placeholder="">
                </div>
                <div class="form-group mb-2">
                    <label class="form-label" for=""><strong>Nama Lengkap</strong></label>
                    <input type="text" name="nama" class="form-control" placeholder="">
                </div>
                 <div class="form-group mb-2">
                        <label class="form-label" for=""><strong>Keterangan </strong></label>
                        <textarea name="keterangan" id="" cols="30" rows="2" class="form-control"></textarea>
                    </div>
            </div>
            <div class="col-lg-6">
                <div class=" animate__animated  animate__fadeIn" id="formInternalUser">
                    <div class="form-group mb-2">
                        <label class="form-label" for=""><strong>NIP</strong></label>
                        <input type="text" name="nip" class="form-control" placeholder="">
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" for=""><strong>Nomor Telepon</strong></label>
                        <input type="text" name="nomor_telepon" class="form-control" placeholder="">
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" for=""><strong>Email</strong></label>
                        <input type="email" name="email" class="form-control" placeholder="">
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" for=""><strong>Stasiun VTS</strong></label>
                        <select name="stasiun_vts_id" id="" class="form-select" required>
                            <option value="">-- PILIH STASIUN VTS</option>
                            @foreach ($stasiun_vts as $item)
                                <option value="{{$item->id}}">{{$item->nama_stasiun_vts}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group mb-2">
                        <label class="form-label" for=""><strong>Foto Profil </strong></label>
                        <input type="file" name="fs_avatar" class="form-control" placeholder="">
                    </div>

                </div>
            </div>
        </div>
        <div class="">
            <a href="{{route('user.index')}}" class="btn btn-danger" type="submit">Kembali</a>
            <button class="btn btn-success" type="submit">Simpan</button>

        </div>
    </div>
</div>
