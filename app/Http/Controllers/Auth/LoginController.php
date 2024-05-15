<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // protected $redirectTo = '/home';


    protected function redirectTo()
    {
        if (Auth::user()) {
            if (Auth::user()->level == 'Admin') {
                activity()->log(Auth::user()->name . ' melakukan login');
                toastr()->success('Selamat datang, ' . Auth::user()->name . '! Anda berhasil login sebagai Admin');
                return '/template';
            } elseif (Auth::user()->level == 'Petugas') {
                activity()->log(Auth::user()->name . ' melakukan login');
                toastr()->success('Selamat datang, ' . Auth::user()->name . '! Anda berhasil login sebagai Petugas');
                return '/petugas';
            } elseif (Auth::user()->level == 'Peminjam') {
                activity()->log(Auth::user()->name . ' melakukan login');
                toastr()->success('Selamat datang, ' . Auth::user()->name . '! Anda berhasil login sebagai Peminjam');
                return '/peminjaman';
            }
        }
        toastr()->error('Login gagal. Silakan coba lagi.');
        return '/login';
    }




    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Notifikasi berhasil logout
        // toastr()->success('Anda telah berhasil logout');

        return redirect('/')->with('success', 'Anda telah berhasil logout');
    }
}
