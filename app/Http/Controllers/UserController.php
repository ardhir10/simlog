<?php

namespace App\Http\Controllers;

use App\KapalNegara;
use App\StasiunRadioPantai;
use App\StasiunVts;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\File;
use Validator;



class UserController extends Controller
{

    public function __construct()
    {
        // $this->middleware('permission:lihat-user',['only'=>'index']);
    }
    public function index(Request $request)
    {
        $data['page_title'] = "Data User - All";

        if($request->type == 'internal'){
            $data['page_title'] = "Data User - Internal";
            $data['users'] = User::
            where('type_user','typeInternalUser')
            ->orderBy('id','desc')->get();
        }elseif($request->type == 'eksternal'){
            $data['page_title'] = "Data User - Eksternal - Badan Usaha Pemilik SBNP";
            $data['users'] = User::
            where('type_user','typeBadanUsahaPemilik')
            ->orderBy('id','desc')->get();
        }else{
            $data['users'] = User::orderBy('id','desc')->get();
        }
        return view('master-data.user.index', $data);
    }

    public function show(Request $request,$id)
    {

    }

    public function create()
    {
        $data['page_title'] = "Tambah Data User";

        $data['roles'] = Role::all();

        $data['kapal_negara'] = KapalNegara::get();
        $data['stasiun_vts'] = StasiunVts::get();
        $data['srop'] = StasiunRadioPantai::get();
        return view('master-data.user.create', $data);
    }
    public function edit($id)
    {
        $data['page_title'] = "Edit Data user";

        $data['data'] = User::find($id);
        $data['roles'] = Role::all();
        $data['kapal_negara'] = KapalNegara::get();
        $data['stasiun_vts'] = StasiunVts::get();
        $data['srop'] = StasiunRadioPantai::get();

        return view('master-data.user.edit', $data);
    }
    public function editProfile($id)
    {
        $data['page_title'] = "Edit Profile";

        $data['user'] = User::find($id);
        $data['page_title'] = "Edit Profile";
        $data['roles'] = Role::all();

        return view('master-data.user.edit-profile', $data);
    }



    public function update(Request $request, $id)
    {


        $rules = [
            'username'                => 'required|unique:users,username,' . $id,
            'nip'                   => 'required',
            'role_id'                => 'required',
        ];


        $messages = [
            'name.required'         => 'Nama Lengkap wajib diisi',
            'name.min'              => 'Nama lengkap minimal 3 karakter',
            'name.max'              => 'Nama lengkap maksimal 35 karakter',
            'email.required'        => 'Email wajib diisi',
            'email.email'           => 'Email tidak valid',
            'email.unique'          => 'Email sudah terdaftar',
        ];
        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }




        $dataUserReadyInsert = [
            'name' => $request->nama,
            'email' => $request->email,
            'username' => $request->username,
            'role_id' => $request->role_id,
            'nip' => $request->nip,
            'nomor_telepon' => $request->nomor_telepon,
            'kapal_negara_id' => $request->kapal_negara_id,
            'stasiun_vts_id' => $request->stasiun_vts_id,
            'srop_id' => $request->srop_id,
            'keterangan' => $request->keterangan,
            'email_verified_at' => \Carbon\Carbon::now(),
        ];

        if($request->password != ''){
            $dataUserReadyInsert['password'] = Hash::make($request->password);
        }

        $role = Role::findById($request->role_id);




        // --- HANDLE PROCESS
        try {
            User::where('id',$id)->update($dataUserReadyInsert);
            $user = User::find($id);
            $user->syncRoles($role->name);
            if ($request->hasFile('fs_avatar')) {
                // Delete Picture
                if ($user->fs_avatar) {
                    $picture_path = public_path('images/avatar/' . $user->fs_avatar);
                    if (File::exists($picture_path)) {
                        File::delete($picture_path);
                    }
                }

                $avatar = $request->file('fs_avatar');
                $name = time() . '.' . $avatar->getClientOriginalExtension();
                $destinationPath = public_path('images/avatar/');
                $avatar->move($destinationPath, $name);
                $user->fs_avatar = $name;
            }
            $user->save();

            return redirect()->route('user.index')->with(['success' => 'Data berhasil disimpan !']);
        } catch (\Throwable $th) {
            return redirect()->route('user.index')->with(['failed' => $th->getMessage()]);
        }

        $user->syncRoles($role->name);
        if ($user) {
            Session::flash('success', 'Pembuatan berhasil ! Silahkan login untuk mengakses data');
            return redirect()->route('user.index');
        } else {
            Session::flash('errors', ['' => 'Register gagal! Silahkan ulangi beberapa saat lagi']);
            return redirect()->route('user.create');
        }



    }

    public function updateProfile(Request $request, $id)
    {
        // --- BAGIAN VALIDASI


        $parameterUpdate= [];

        if ($request->password != '') {
            $parameterUpdate['password'] = Hash::make($request->password);
        }

        // --- HANDLE PROCESS
        try {

            if ($request->hasFile('fs_avatar')) {
                // Delete Picture
                $user = User::where('id',$id)->first();
                if ($user->fs_avatar) {
                    $picture_path = public_path('images/avatar/' . $user->fs_avatar);
                    if (File::exists($picture_path)) {
                        File::delete($picture_path);
                    }
                }

                $avatar = $request->file('fs_avatar');
                $name = time() . '.' . $avatar->getClientOriginalExtension();
                $destinationPath = public_path('images/avatar/');
                $avatar->move($destinationPath, $name);
                $parameterUpdate['fs_avatar'] = $name;
            }


            User::where('id', $id)->update($parameterUpdate);
            return redirect()->route('dashboard')->with(['success' => 'Profile berhasil diubah !']);
        } catch (\Throwable $th) {
            return redirect()->route('dashboard')->with(['failed' => $th->getMessage()]);
        }
    }


    public function delete($id)
    {

        try {
            User::destroy($id);
            return redirect()->route('user.index')->with(['failed' => 'Data berhasil dihapus !']);
        } catch (\Throwable $th) {
            return redirect()->route('user.index')->with(['failed' => $th->getMessage()]);
        }
    }

    public function asignRole($id){
        // $user = User::find($id);
        // $user->assignRole('root');

        $dataPermissions = [
            'buat-user',
            'lihat-user',
            'edit-user',
            'hapus-user'
        ];

          // $user = User::find($id);
          $role = Role::findByName('root');
          $role->givePermissionTo($dataPermissions);
          dd($role);
    }

    public function userSetting()
    {
        $data['page_title'] = "User Setting Profile";

        $data['user'] = User::find(Auth::user()->id);
        $data['page_title'] = "User Setting Profile";
        $data['roles'] = Role::all();

        return view('master-data.user.user-setting', $data);
    }
    public function userSettingUpdate(Request $request)
    {
         if ($request->password != '') {
            $parameterUpdate['password'] = Hash::make($request->password);

            try {
                User::where('id', Auth::user()->id)
                ->update($parameterUpdate);
                return redirect()->route('user.setting')->with(['success' => 'Password berhasil diubah !']);
            } catch (\Throwable $th) {
                return redirect()->route('user.setting')->with(['failed' => $th->getMessage()]);
            }
        }
        return redirect()->route('user.setting')->with(['' => 'data tidak di ubah']);



    }

}
