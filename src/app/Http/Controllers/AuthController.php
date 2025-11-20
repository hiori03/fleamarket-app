<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\VerifyEmail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function registerform()
    {
        return view('register');
    }

    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $this->sendVerificationMail($user);

        Auth::login($user);
        session(['unverified_user_id' => $user->id]);
        Auth::logout();
        return redirect()->route('email');
    }

    public function emailform()
    {
        $userId = session('unverified_user_id');

        if (!$userId) return redirect()->route('login');

        $user = User::find($userId);

        return view('email', ['user' => $user]);
    }

    public function certification(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if ($user->email_verified_at) {
            Auth::login($user);
            return redirect('/mypage/profile');
        }

        $user->email_verified_at = now();
        $user->save();

        Auth::login($user);

        session()->forget('unverified_user_id');

        return redirect('/mypage/profile');
    }

    public function resend(Request $request)
    {
        $userId = session('unverified_user_id');

        if (! $userId) {
            return redirect()->route('login');
        }

        $user = User::find($userId);

        if ($user->email_verified_at) {
            session()->forget('unverified_user_id');
            return redirect('/');
        }

        $this->sendVerificationMail($user);

        return back();
    }

    public function sendVerificationMail(User $user)
    {
        $url = URL::temporarySignedRoute(
            'email.certification',
            now()->addMinutes(60),
            [
                'id' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        Mail::to($user->email)->send(new VerifyEmail($user, $url));
    }

    public function loginform()
    {
        return view('login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (! $user) {
            return back()->withErrors(['email' => 'ログイン情報が登録されていません'])->withInput();
        }

        if (! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['email' => 'ログイン情報が登録されていません'])->withInput();
        }

        Auth::login($user);
        $request->session()->regenerate();

        if (! $user->email_verified_at) {
            $this->sendVerificationMail($user);
            session(['unverified_user_id' => $user->id]);
            Auth::logout();
            return redirect()->route('email');
        }

        // Auth::login($user);
        // $request->session()->regenerate();

        return redirect()->intended('/');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $request->session()->forget('url.intended');

        return redirect()->route('login');
    }
}
