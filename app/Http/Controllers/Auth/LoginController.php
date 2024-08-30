<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;


    protected $redirectTo = RouteServiceProvider::HOME;

    protected $maxAttempts = 5;
    protected $decayMinutes = 10;
    protected $username;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->username = $this->findUsername();
    }

    protected function authenticated(Request $request, $user)
    {
        // Auth::logoutOtherDevices(request('password'));
        $user->update([
            'last_login_at' => now()->toDateTimeString(),
            'last_login_ip' => $request->getClientIp()
        ]);
        return redirect('/')->with('success', 'Login Success!');
    }

    public function findUsername()
    {
        $login = request()->input('username');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$field => $login]);
        return $field;
    }

    public function username()
    {
        return $this->username;
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => 'username or password is incorrect!!!'
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->update(['last_logout_at' => now()]);
        Auth::logout();
        return redirect('/login')->with('success', 'Logout Berhasil!');
    }
}
