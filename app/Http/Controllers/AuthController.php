<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Validator;
use Hash;
use Session;
use App\User;
use Spatie\Permission\Models\Role;

class AuthController extends Controller
{
    public function showFormLogin()
    {
        if (Auth::check()) { // true sekalian session field di users nanti bisa dipanggil via Auth
            //Login Success
            Auth::logout(); // menghapus session yang aktif
            return redirect()->route('login');
        }
        return view('auth.login-page');
    }

    public function login(Request $request)
    {
        $rules = [
            'email'                 => 'required',
            'password'              => 'required|string'
        ];

        $messages = [
            'email.required'        => 'Email wajib diisi',
            'password.required'     => 'Password wajib diisi',
            'password.string'       => 'Password harus berupa string'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        $data = [
            filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username' => $request->email,
            'password' => $request->password
        ];



        Auth::attempt($data);
        // dd(Auth::attempt($data));
        if (Auth::check()) { // true sekalian session field di users nanti bisa dipanggil via Auth
            //Login Success
            return redirect()->route('dashboard');
        } else { // false

            //Login Fail
            Session::flash('error', 'Email atau password salah');
            return redirect()->route('login');
        }
    }

    public function showFormRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {




        $rules = [
            'username'                => 'required|unique:users,username',
            'password'                => 'required|confirmed',
            'nama'                    => 'required',
            'nip'                     => '',
            'nomor_telepon'           => '',
            'email'                   => 'required|email|unique:users,email',
            'fs_avatar'               => 'mimes:jpeg,png',
        ];




        $messages = [
            'nama.required'         => 'Nama Lengkap wajib diisi',
            'nama.min'              => 'Nama lengkap minimal 3 karakter',
            'nama.max'              => 'Nama lengkap maksimal 35 karakter',
            'email.required'        => 'Email wajib diisi',
            'email.email'           => 'Email tidak valid',
            'email.unique'          => 'Email sudah terdaftar',

            'username.required'     => 'User ID Wajib Diisi',

            'password.required'     => 'Password wajib diisi',
            'password.confirmed'  => 'Password tidak sama dengan konfirmasi password'
        ];

        $validator = Validator::make($request->all(), $rules,$messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput($request->all);
        }

        if ($request->hasFile('fs_avatar')) {
            $avatar = $request->file('fs_avatar');
            $name = time() . '.' . $avatar->getClientOriginalExtension();
            $destinationPath = public_path('images/avatar/');
            $avatar->move($destinationPath, $name);
            $fileName = $name;
        }
        $dataUserReadyInsert = [
            'name'=> $request->nama,
            'email'=> $request->email,
            'username'=> $request->username,
            'role_id'=> $request->role_id,
            'password'=> Hash::make($request->password),
            'fs_avatar'=> $fileName,
            'nip' => $request->nip,
            'nomor_telepon' => $request->nomor_telepon,
            'kapal_negara_id' => $request->kapal_negara_id,
            'stasiun_vts_id' => $request->stasiun_vts_id,
            'srop_id' => $request->srop_id,
            'keterangan' => $request->keterangan,
            'email_verified_at' => \Carbon\Carbon::now(),
        ];


        $user = User::create($dataUserReadyInsert);


        $user->syncRoles($request->role_id);
        if ($user) {
            Session::flash('success', 'Pembuatan berhasil ! Silahkan login untuk mengakses data');
            return redirect()->route('user.index');
        } else {
            Session::flash('errors', ['' => 'Register gagal! Silahkan ulangi beberapa saat lagi']);
            return redirect()->route('user.create');
        }
    }

    public function logout()
    {
        Auth::logout(); // menghapus session yang aktif
        return redirect()->route('login');
    }
}
