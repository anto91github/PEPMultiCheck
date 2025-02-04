<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {   
        return 'email';
    }

    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            [
                'email' => $request->email,
                'password' => $request->password,
                'status' => 1 // Hanya izinkan login jika status = 1
            ],
            $request->filled('remember')
        );
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        // Cek apakah pengguna ada di database
        $user = $this->guard()->getProvider()->retrieveByCredentials($request->only('email', 'password'));

        if ($user && $user->status != 1) {
            return redirect()->back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors([
                    'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                ]);
        }

        // Jika tidak, kembalikan pesan error default
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }
}
