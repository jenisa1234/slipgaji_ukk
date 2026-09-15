<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * Tampilkan form reset password
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with([
            'token' => $token ?? $request->token,
            'email' => session('reset_password_email', $request->email),
            'verified' => session('reset_password_verified', false),
        ]);
    }

    /**
     * Verifikasi kode/token reset password
     */
    public function verifyResetCode(Request $request)
    {
        $email = session('reset_password_email');

        if (!$email) {
            return redirect()->route('password.request')
                ->withErrors(['email' => 'Silakan minta kode verifikasi terlebih dahulu.']);
        }

        $validated = $request->validate([
            'code'  => 'required|digits:6',
        ], [
            'code.required'  => 'Kode verifikasi wajib diisi.',
            'code.digits'    => 'Kode verifikasi harus 6 digit.',
        ]);

        $resetToken = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $validated['code'])
            ->where('created_at', '>=', now()->subMinutes(15))
            ->first();

        if (!$resetToken) {
            return back()
                ->withErrors(['code' => 'Kode verifikasi salah atau sudah kedaluwarsa.'])
                ->withInput();
        }

        session([
            'reset_password_email' => $email,
            'reset_password_verified' => true,
        ]);

        return redirect()->route('password.reset.code', [
            'email' => $email,
        ])->with('status', 'Kode berhasil diverifikasi. Silakan masukkan password baru Anda.');
    }

    /**
     * Eksekusi pembaruan password baru
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email'                 => 'required|email',
            'password'              => 'required|confirmed|min:8',
        ], [
            'password.required'  => 'Password baru wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'password.min'       => 'Password minimal harus 8 karakter.',
        ]);

        if (!session('reset_password_verified') || session('reset_password_email') !== $request->email) {
            return redirect()->route('password.reset.code', ['email' => $request->email])
                ->withErrors(['code' => 'Verifikasi kode terlebih dahulu.']);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak ditemukan.']);
        }

        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->setRememberToken(Str::random(60));
        $user->save();

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        session()->forget(['reset_password_email', 'reset_password_verified']);
        event(new PasswordReset($user));

        return redirect()->route('login')->with('status', 'Password berhasil diubah.');
    }
}