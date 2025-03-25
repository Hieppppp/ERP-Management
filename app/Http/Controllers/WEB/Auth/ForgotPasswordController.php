<?php

namespace App\Http\Controllers\WEB\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordViewRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Console\View\Components\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
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
     * Forgot Password View
     *
     * @return View|Factory
     */
    public function index(): View|Factory
    {
        return view('pages/auth/forgot-password');
    }

    // /*
    // |--------------------------------------------------------------------------
    // | Password Reset Controller
    // |--------------------------------------------------------------------------
    // |
    // | This controller is responsible for handling password reset emails and
    // | includes a trait which assists in sending these notifications from
    // | your application to your users. Feel free to explore this trait.
    // |
    // */


    /**
     * ForgotPassword
     *
     * @param  ForgotPasswordRequest $request
     * @return RedirectResponse|Redirector
     */
    public function forgotPassword(ForgotPasswordRequest $request): RedirectResponse|Redirector
    {

        $params = $request->validated();
        $status = Password::sendResetLink(
            ['email' => $params['email']]
        );

        if ($status === Password::RESET_LINK_SENT) {
            session(['userEmailResendEmail' => $params['email']]);
            return redirect()->to('/resend-email');
        } else {
            session()->flash('error', __($status));
            return back()->withErrors(['email' => __($status)]);
        }
    }

    /**
     * Resend Email
     *
     * @return View|Factory|RedirectResponse|Redirector
     */
    public function resendEmailView(): View|Factory|RedirectResponse|Redirector
    {
        if (Auth::check()) {
            return redirect()->to('/');
        }
        if (session()->get('userEmailResendEmail')) {
            $data['time'] = config('auth.passwords.users.throttle');
            $passwordResetToken = DB::table(config('auth.passwords.users.table'))->where('email', session()->get('userEmailResendEmail'))->orderBy('created_at', 'desc')->first();
            if ($passwordResetToken) {
                $now = Carbon::now();
                $createdAt = Carbon::parse($passwordResetToken->created_at);
                $differenceInSeconds = $createdAt->diffInSeconds($now);
                $time = $data['time'] - $differenceInSeconds;
                $data['time'] = $time > 0 ? $time : 0;
            }
            return view('pages/auth/resend-email', $data);
        }
        return redirect()->to('login');
    }

    /**
     * resendMail
     *
     * @return JsonResponse
     */
    public function resendMail(): JsonResponse
    {
        $email = session()->get('userEmailResendEmail');
        if ($email) {
            $status = Password::sendResetLink(
                ['email' => $email]
            );
            if ($status === Password::RESET_LINK_SENT) {
                $data = [
                    "data" => config('auth.passwords.users.throttle')
                ];
                return $this->responseSuccess($data, is_string($status) ? [__($status)] : $status);
            } else {
                return $this->responseFail(is_string($status) ? [__($status)] : $status);
            }
        }
        return $this->responseFail(null, 400, [
            'redirect' => '/login'
        ]);
    }

    /**
     * Reset Password View
     *
     * @param ResetPasswordViewRequest $request
     * @return View|Factory
     */
    public function resetPasswordView(ResetPasswordViewRequest $request): View|Factory
    {
        $params = $request->validated();
        return view('pages/auth/reset-password', $params);
    }
    /**
     * Reset Password
     *
     * @param ResetPasswordRequest $request
     * @return RedirectResponse|Redirector
     */
    public function resetPassword(ResetPasswordRequest $request): RedirectResponse|Redirector
    {
        $params = $request->validated();
        $status = Password::reset(
            $params,
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );
        if ($status === Password::PASSWORD_RESET) {
            session()->remove('userIdResendEmail');
            return redirect()->route('login')->with('status', __($status));
        } else {
            return back()->with(['email' => __($status)]);
        }
    }
}
