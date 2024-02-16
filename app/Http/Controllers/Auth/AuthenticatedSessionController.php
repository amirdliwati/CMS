<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Controller, Auth, Hash, Carbon;
use Illuminate\Http\Request;

class AuthenticatedSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['create','store']);
    }
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        if ($user->status != 1)
        {
            Auth::guard('web')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return redirect('/');

            return back()->with('info', 'Your account has been locked. Please contact your administrator.');
        }
        else
        {
            $user -> last_login_at = Carbon::now()->toDateTimeString();
            $user -> last_login_ip = \Request::getClientIp(true);
            $user -> save();

            if (Hash::check($user->email, $user->password))
            {
                return redirect('/ChangePassword');
            }

            return redirect()->intended(RouteServiceProvider::HOME);
        }

    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
