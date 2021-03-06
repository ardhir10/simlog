<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// --- LOGIN AREA

Route::get('/', 'AuthController@showFormLogin')->name('login');
Route::get('login', 'AuthController@showFormLogin')->name('login');
Route::post('login', 'AuthController@login');
Route::get('register', 'AuthController@showFormRegister')->name('register');
Route::post('register', 'AuthController@register');
Route::get('logout', 'AuthController@logout')->name('logout');


Route::middleware('auth')->group(function () {

    Route::get('/', 'DashboardController@index')->name('dashboard');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/dashboard-2', 'DashboardController@index2')->name('dashboard-2');

    Route::prefix('barang-persediaan')->name('barang-persediaan.')->group(function(){
        Route::get('/', 'BarangPersediaanController@index')->name('index');
        Route::get('/create', 'BarangPersediaanController@create')->name('create');
        Route::get('/edit/{id}', 'BarangPersediaanController@edit')->name('edit');
        Route::get('/delete/{id}', 'BarangPersediaanController@delete')->name('delete');
        Route::post('/store', 'BarangPersediaanController@store')->name('store');
        Route::post('/update/{id}', 'BarangPersediaanController@update')->name('update');
        Route::get('/stock-detail/{id}', 'BarangPersediaanController@stockDetail')->name('stock-detail');
        Route::get('/stock-detail/create/{id}', 'BarangPersediaanController@stockDetailCreate')->name('stock-detail-create');
        Route::post('/stock-masuk/{id}', 'BarangPersediaanController@stockMasuk')->name('stock-masuk');

        Route::post('/create-diff/{id}', 'BarangPersediaanController@createDiff')->name('create-diff');
        Route::post('/import', 'BarangPersediaanController@import')->name('import');
    });

    Route::prefix('permintaan-barang')->name('permintaan-barang.')->group(function () {
        Route::get('/', 'PermintaanBarangController@index')->name('index');
        Route::get('/create', 'PermintaanBarangController@create')->name('create');
        Route::post('/add-barang/{id}/{permintaanBarangId}', 'PermintaanBarangController@addBarang')->name('add-barang');
        Route::get('/delete-barang/{id}', 'PermintaanBarangController@deleteBarang')->name('delete-barang');
        Route::get('/batalkan-permintaan/{id}', 'PermintaanBarangController@batalkanPermintaan')->name('batalkan-permintaan');
        Route::post('/ajukan-permintaan/{id}', 'PermintaanBarangController@ajukanPermintaan')->name('ajukan-permintaan');
        Route::get('/detail/{id}', 'PermintaanBarangController@detail')->name('detail');
        Route::get('/cetak-nota-dinas/{id}', 'PermintaanBarangController@pdfNotaDinas')->name('nota-dinas');
        Route::get('/cetak-nota-upp3/{id}', 'PermintaanBarangController@pdfUpp3')->name('upp3');
        Route::get('/cetak-nota-upp4/{id}', 'PermintaanBarangController@pdfUpp4')->name('upp4');
        Route::get('/cetak-bast/{id}', 'PermintaanBarangController@pdfBast')->name('bast');
    });

    Route::prefix('approval')->name('approval.')->group(function () {

        Route::get('/', 'ApprovalController@index')->name('index');
        Route::get('/review/{id}', 'ApprovalController@review')->name('review');
        Route::post('/tindak-lanjut/{id}', 'ApprovalController@tindakLanjut')->name('tindak-lanjut');
        Route::post('/tindak-lanjut-update/{id}/{idApproval}/{idPersetujuan}', 'ApprovalController@tindakLanjutUpdate')->name('tindak-lanjut-update');

        Route::post('/pengelola-gudang-setuju/{id}', 'ApprovalController@pengelolaGudangsetuju')->name('pengelola-gudang-setuju');

        Route::post('/kabid-logistik-setuju/{id}', 'ApprovalController@kabidLogistikSetuju')->name('kabid-logistik-setuju');
        Route::post('/kabid-logistik-setuju-disposisi-kadisnav/{id}', 'ApprovalController@kabidLogistikSetujuDisposisiKadisnav')->name('kabid-logistik-setuju-disposisi-kadisnav');
        Route::post('/kabid-logistik-disposisi/{id}', 'ApprovalController@kabidLogistikDisposisi')->name('kabid-logistik-disposisi');

        Route::post('/kasie-pengadaan-setuju/{id}', 'ApprovalController@kasiePengadaanSetuju')->name('kasie-pengadaan-setuju');
        Route::post('/kasie-pengadaan-setuju-disposisi-kadisnav/{id}', 'ApprovalController@kasiePengadaanSetujuDisposisiKadisnav')->name('kasie-pengadaan-setuju-disposisi-kadisnav');
        Route::post('/bendahara-materil-setuju/{id}', 'ApprovalController@bendaharaMaterilSetuju')->name('bendahara-materil-setuju');
        Route::post('/staff-seksi-pengadaan-setuju/{id}', 'ApprovalController@staffSeksiPengadaanSetuju')->name('staff-seksi-pengadaan-setuju');
        Route::post('/pengelola-gudang-siap/{id}', 'ApprovalController@pengelolaGudangSiap')->name('pengelola-gudang-siap');

        Route::post('/terima-barang/{id}', 'ApprovalController@terimaBarang')->name('terima-barang');
        Route::post('/kirim-kurir/{id}', 'ApprovalController@kirimKurir')->name('kirim-kurir');
        Route::post('/terima-barang-by-kurir/{id}', 'ApprovalController@terimaBarangByKurir')->name('terima-barang-by-kurir');

        Route::post('/serahkan-barang/{id}', 'ApprovalController@serahkanBarang')->name('serahkan-barang');
        Route::post('/lapor/{id}', 'ApprovalController@lapor')->name('lapor');

    });


    Route::prefix('rencana-kebutuhan')->name('rencana-kebutuhan.')->group(function () {
        Route::get('/', 'RencanaKebutuhanController@index')->name('index');
        Route::get('/create', 'RencanaKebutuhanController@create')->name('create');
        Route::get('/batalkan/{id?}', 'RencanaKebutuhanController@batalkan')->name('batalkan');
        Route::post('/store/{id?}', 'RencanaKebutuhanController@store')->name('store');
        Route::get('/get-barang-persediaan/{id?}', 'RencanaKebutuhanController@getBarangPersediaan')->name('get-barang-persediaan');
        Route::post('/input-item/{id?}', 'RencanaKebutuhanController@inputItem')->name('input-item');
        Route::get('/delete-item/{id?}', 'RencanaKebutuhanController@deleteItem')->name('delete-item');
        Route::get('/detail/{id?}', 'RencanaKebutuhanController@detail')->name('detail');

        // --- ROUTE NOTA DINAS RK
        Route::get('/rk-nota-dinas/{id}', 'RencanaKebutuhanController@pdfNotaDinas')->name('nota-dinas');
        Route::get('/rk-nota-usulan-kebutuhan/{id}', 'RencanaKebutuhanController@pdfUsulanKebutuhan')->name('usulan-kebutuhan');


    });

    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', 'LaporanController@index')->name('index');
        Route::get('/export-excel', 'LaporanController@exportExcel')->name('export-excel');

    });

    Route::prefix('rk-approval')->name('rk-approval.')->group(function () {

        Route::get('/', 'ApprovalRencanaKebutuhanController@index')->name('index');
        Route::get('/review/{id}', 'ApprovalRencanaKebutuhanController@review')->name('review');
        Route::post('/tindak-lanjut/{id}', 'ApprovalRencanaKebutuhanController@tindakLanjut')->name('tindak-lanjut');
        Route::post('/tindak-lanjut-update/{id}/{idApproval}/{idPersetujuan}', 'ApprovalRencanaKebutuhanController@tindakLanjutUpdate')->name('tindak-lanjut-update');

        Route::post('/pengelola-gudang-setuju/{id}', 'ApprovalRencanaKebutuhanController@pengelolaGudangsetuju')->name('pengelola-gudang-setuju');

        Route::post('/kabid-logistik-setuju/{id}', 'ApprovalRencanaKebutuhanController@kabidLogistikSetuju')->name('kabid-logistik-setuju');
        Route::post('/kabid-logistik-setuju-disposisi-kadisnav/{id}', 'ApprovalRencanaKebutuhanController@kabidLogistikSetujuDisposisiKadisnav')->name('kabid-logistik-setuju-disposisi-kadisnav');
        Route::post('/kabid-logistik-disposisi/{id}', 'ApprovalRencanaKebutuhanController@kabidLogistikDisposisi')->name('kabid-logistik-disposisi');

        Route::post('/kasie-pengadaan-setuju/{id}', 'ApprovalRencanaKebutuhanController@kasiePengadaanSetuju')->name('kasie-pengadaan-setuju');
        Route::post('/kasie-pengadaan-setuju-disposisi-kadisnav/{id}', 'ApprovalRencanaKebutuhanController@kasiePengadaanSetujuDisposisiKadisnav')->name('kasie-pengadaan-setuju-disposisi-kadisnav');
        Route::post('/bendahara-materil-setuju/{id}', 'ApprovalRencanaKebutuhanController@bendaharaMaterilSetuju')->name('bendahara-materil-setuju');
        Route::post('/staff-seksi-pengadaan-setuju/{id}', 'ApprovalRencanaKebutuhanController@staffSeksiPengadaanSetuju')->name('staff-seksi-pengadaan-setuju');
        Route::post('/pengelola-gudang-siap/{id}', 'ApprovalRencanaKebutuhanController@pengelolaGudangSiap')->name('pengelola-gudang-siap');

        Route::post('/terima-barang/{id}', 'ApprovalRencanaKebutuhanController@terimaBarang')->name('terima-barang');
        Route::post('/kirim-kurir/{id}', 'ApprovalRencanaKebutuhanController@kirimKurir')->name('kirim-kurir');
        Route::post('/terima-barang-by-kurir/{id}', 'ApprovalRencanaKebutuhanController@terimaBarangByKurir')->name('terima-barang-by-kurir');

        Route::post('/serahkan-barang/{id}', 'ApprovalRencanaKebutuhanController@serahkanBarang')->name('serahkan-barang');
        Route::post('/lapor/{id}', 'ApprovalRencanaKebutuhanController@lapor')->name('lapor');
    });

    Route::prefix('rab')->name('rab.')->group(function () {
        Route::get('/', 'RencanaAnggaranBiayaController@index')->name('index');
        Route::get('/create', 'RencanaAnggaranBiayaController@create')->name('create');
        Route::post('/store/{id?}', 'RencanaAnggaranBiayaController@store')->name('store');
        Route::post('/store-wrk/{id?}', 'RencanaAnggaranBiayaController@storeWithRencanaKebutuhan')->name('store-with-rencana-kebutuhan');
        Route::get('/get-barang-persediaan/{id?}', 'RencanaAnggaranBiayaController@getBarangPersediaan')->name('get-barang-persediaan');
        Route::post('/input-item-rab/{id?}', 'RencanaAnggaranBiayaController@inputItem')->name('input-item');
        Route::get('/delete-item/{id?}', 'RencanaAnggaranBiayaController@deleteItem')->name('delete-item');
        Route::get('/batalkan/{id?}', 'RencanaAnggaranBiayaController@batalkan')->name('batalkan');


        Route::get('/approval-review/{id?}', 'RencanaAnggaranBiayaController@approvalReview')->name('approval-review');
        Route::post('/approval-tindak-lanjut/{id?}', 'RencanaAnggaranBiayaController@approvalTindakLanjut')->name('approval-tindak-lanjut');

        Route::get('/cetak-rab/{id}', 'RencanaAnggaranBiayaController@cetakRab')->name('cetak-rab');

    });

    Route::prefix('rencana-kebutuhan-tahunan')->name('rencana-kebutuhan-tahunan.')->group(function () {
        Route::get('/', 'RencanaKebutuhanTahunanController@index')->name('index');
        Route::get('/create', 'RencanaKebutuhanTahunanController@create')->name('create');
        Route::get('/edit/{id}', 'RencanaKebutuhanTahunanController@edit')->name('edit');
        Route::post('/store/{id?}', 'RencanaKebutuhanTahunanController@store')->name('store');
        Route::get('/delete/{id}', 'RencanaKebutuhanTahunanController@delete')->name('delete');

    });

    Route::prefix('retur-barang')->name('retur-barang.')->group(function () {
        Route::get('/', 'ReturBarangController@index')->name('index');
        Route::get('/create', 'ReturBarangController@create')->name('create');
        Route::get('/batalkan/{id?}', 'ReturBarangController@batalkan')->name('batalkan');
        Route::get('/hapus-barang/{id?}', 'ReturBarangController@hapusBarang')->name('hapus-barang');
        Route::post('/tambah-barang', 'ReturBarangController@tambahBarang')->name('tambah-barang');
        Route::post('/retur-barang/{id}', 'ReturBarangController@store')->name('simpan');

        Route::get('/approval-review/{id?}', 'ReturBarangController@approvalReview')->name('approval-review');
        Route::post('/approval-tindak-lanjut/{id?}', 'ReturBarangController@approvalTindakLanjut')->name('approval-tindak-lanjut');
        Route::get('/cetak-bast/{id}', 'ReturBarangController@cetakBast')->name('cetak-bast');


    });




    // --- MASTER DATA
    Route::prefix('master-data')->name('master-data.')->group(function () {
        Route::get('/', 'DataMasterController@index')->name('index');

        // ---KAPAL NEGARA
        Route::get('/kapal-negara', 'KapalNegaraController@index')->name('kapal-negara.index');
        Route::get('/kapal-negara/add', 'KapalNegaraController@create')->name('kapal-negara.create');
        Route::post('/kapal-negara', 'KapalNegaraController@store')->name('kapal-negara.store');
        Route::get('/kapal-negara/{id}/edit', 'KapalNegaraController@edit')->name('kapal-negara.edit');
        Route::post('/kapal-negara/{id}/update', 'KapalNegaraController@update')->name('kapal-negara.update');
        Route::get('/kapal-negara/{id}/delete', 'KapalNegaraController@delete')->name('kapal-negara.delete');


        // ---STASIUN VTS
        Route::get('/stasiun-vts', 'StasiunVtsController@index')->name('stasiun-vts.index');
        Route::get('/stasiun-vts/add', 'StasiunVtsController@create')->name('stasiun-vts.create');
        Route::post('/stasiun-vts', 'StasiunVtsController@store')->name('stasiun-vts.store');
        Route::get('/stasiun-vts/{id}/edit', 'StasiunVtsController@edit')->name('stasiun-vts.edit');
        Route::post('/stasiun-vts/{id}/update', 'StasiunVtsController@update')->name('stasiun-vts.update');
        Route::get('/stasiun-vts/{id}/delete', 'StasiunVtsController@delete')->name('stasiun-vts.delete');

        // ---STASIUN RADIO PANTAI
        Route::get('/stasiun-radio-pantai', 'StasiunRadioPantaiController@index')->name('stasiun-radio-pantai.index');
        Route::get('/stasiun-radio-pantai/add', 'StasiunRadioPantaiController@create')->name('stasiun-radio-pantai.create');
        Route::post('/stasiun-radio-pantai', 'StasiunRadioPantaiController@store')->name('stasiun-radio-pantai.store');
        Route::get('/stasiun-radio-pantai/{id}/edit', 'StasiunRadioPantaiController@edit')->name('stasiun-radio-pantai.edit');
        Route::post('/stasiun-radio-pantai/{id}/update', 'StasiunRadioPantaiController@update')->name('stasiun-radio-pantai.update');
        Route::get('/stasiun-radio-pantai/{id}/delete', 'StasiunRadioPantaiController@delete')->name('stasiun-radio-pantai.delete');


        // ---KATEGORI BARANG
        Route::get('/kategori-barang', 'KategoriBarangController@index')->name('kategori-barang.index');
        Route::get('/kategori-barang/add', 'KategoriBarangController@create')->name('kategori-barang.create');
        Route::post('/kategori-barang', 'KategoriBarangController@store')->name('kategori-barang.store');
        Route::get('/kategori-barang/{id}/edit', 'KategoriBarangController@edit')->name('kategori-barang.edit');
        Route::post('/kategori-barang/{id}/update', 'KategoriBarangController@update')->name('kategori-barang.update');
        Route::get('/kategori-barang/{id}/delete', 'KategoriBarangController@delete')->name('kategori-barang.delete');

        Route::get('/sub-kategori-barang', 'SubKategoriBarangController@index')->name('sub-kategori-barang.index');
        Route::get('/sub-kategori-barang/add', 'SubKategoriBarangController@create')->name('sub-kategori-barang.create');
        Route::get('/sub-kategori-barang', 'SubKategoriBarangController@index')->name('sub-kategori-barang.index');
        Route::post('/sub-kategori-barang', 'SubKategoriBarangController@store')->name('sub-kategori-barang.store');
        Route::get('/sub-kategori-barang/{id}/edit', 'SubKategoriBarangController@edit')->name('sub-kategori-barang.edit');
        Route::post('/sub-kategori-barang/{id}/update', 'SubKategoriBarangController@update')->name('sub-kategori-barang.update');
        Route::get('/sub-kategori-barang/{id}/delete', 'SubKategoriBarangController@delete')->name('sub-kategori-barang.delete');

        // ---SATUAN
        Route::get('/satuan', 'SatuanController@index')->name('satuan.index');
        Route::get('/satuan/add', 'SatuanController@create')->name('satuan.create');
        Route::post('/satuan', 'SatuanController@store')->name('satuan.store');
        Route::get('/satuan/{id}/edit', 'SatuanController@edit')->name('satuan.edit');
        Route::post('/satuan/{id}/update', 'SatuanController@update')->name('satuan.update');
        Route::get('/satuan/{id}/delete', 'SatuanController@delete')->name('satuan.delete');

    });


});




// PROVINCE,KABUPATEN/KOTA
Route::get('/get-regency/{id?}', 'LahanController@getRegency')->name('get-regency');
Route::get('/get-district/{id?}', 'LahanController@getDistrict')->name('get-district');


// --- ROLE SETUP
Route::get('/master-data/role', 'RoleController@index')->name('role.index');
Route::get('/master-data/role/add', 'RoleController@create')->name('role.create');
Route::post('/master-data/role', 'RoleController@store')->name('role.store');
Route::get('/master-data/role/{id}/edit', 'RoleController@edit')->name('role.edit');
Route::post('/master-data/role/{id}/update', 'RoleController@update')->name('role.update');
Route::get('/master-data/role/{id}/delete', 'RoleController@delete')->name('role.delete');

// --- USER SETUP
Route::get('/master-data/user/assign/{id}', 'UserController@asignRole')->name('user.asign');
Route::get('/master-data/user', 'UserController@index')->name('user.index');
Route::get('/master-data/user/add', 'UserController@create')->name('user.create');
Route::post('/master-data/user', 'UserController@store')->name('user.store');
Route::get('/master-data/user/{id}/edit', 'UserController@edit')->name('user.edit');
Route::get('/master-data/user/{id}/edit/profile', 'UserController@editProfile')->name('user.edit.profile');
Route::post('/master-data/user/{id}/update', 'UserController@update')->name('user.update');
Route::post('/master-data/user/{id}/update-profile', 'UserController@updateProfile')->name('user.update-profile');
Route::get('/master-data/user/{id}/delete', 'UserController@delete')->name('user.delete');

Route::get('/master-data/user/show/{id}', 'UserController@show')->name('user.show');
Route::get('/user-setting', 'UserController@userSetting')->name('user.setting');
Route::post('/user-setting', 'UserController@userSettingUpdate')->name('user.setting.update');

Route::get('/public-data/user/{id}', 'UserController@viewUserPublic')->name('public-data.user');



