<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * get reset password form
     */
    public function forgotPassword()
    {
        return view('pages.forgotPassword');
    }

    /**
     * send reset password link
     * @param Illuminate\Http\Request;
     * @return redirect
     */
    public function sendRequest(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $count = User::where('email', $request->only('email'))->count();
        if ($count == 0) {
            return response(['errors' => ['email' => 'Email not exist.'], 'message' => 'Email not exist.'], 500)->header('Content-Type', 'application/json');
        }

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? back()->with(['status' => __($status)])
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * send reset password link
     * @param token
     * @return view
     */
    public function resetPassword($token)
    {
        return view('pages.resetPassword', ['token' => $token]);
    }

    /**
     * reset password
     * @param Illuminate\Http\Request;
     * @return redirect
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ]);
    
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
    
                $user->save();
    
                event(new PasswordReset($user));
            }
        );
    
        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('login')->with('status', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }
}
