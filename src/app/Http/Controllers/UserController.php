<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function mypage_profileform()
    {

        return view('mypage_profile');
    }

    public function mypage_profile(ProfileRequest $request)
    {
        $user = Auth::user(); // 現在ログイン中のユーザーを取得

        $user->update([
            'name' => $request->name,
            'profile_image' => $request->profile_image
        ]);

        $isFirstTime = !$user->address()->exists();

        $user->address()->updateOrCreate(
            [],
            [
                'postal' => $request->postal,
                'address' => $request->address,
                'building' => $request->building,
            ]
        );

        if ($isFirstTime) {
            return redirect('/');
        } else {
            return redirect('/mypage'); //ここ直す
        }
    }
}
