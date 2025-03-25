<?php

namespace App\Http\Controllers\WEB\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * index
     *
     * @return View
     */
    public function index(): View|Factory
    {
        return view('pages/auth/login');
    }

    /**
     * login
     *
     * @param  LoginRequest $request
     * @return RedirectResponse|Redirector
     */
    public function login(LoginRequest $request): RedirectResponse|Redirector
    {
        $credentials = $request->validated();
        if (Auth::attempt($credentials)) {
            session()->flash('success', trans('message.loginSuccess'));
            return redirect()->intended('/');
        }
        session()->flash('error', trans('message.loginFail'));
        return redirect()->back();
    }

    /**
     * logout
     *
     * @param  Request $request
     * @return RedirectResponse|Redirector
     */
    public function logout(Request $request): RedirectResponse|Redirector
    {
        Auth::guard()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->to('/login');
    }
}
