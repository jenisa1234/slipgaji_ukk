<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->input('email'))->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan'])->onlyInput('email');
        }

        $code = (string) random_int(100000, 999999);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'token' => $code,
                'created_at' => now(),
            ]
        );

        $user->notify(new ResetPasswordNotification($code));

        session([
            'reset_password_email' => $user->email,
            'reset_password_verified' => false,
        ]);

        return redirect()->route('password.reset.code')->with([
            'status' => 'Kode verifikasi telah dikirim ke ' . $user->email,
        ]);
    }
}