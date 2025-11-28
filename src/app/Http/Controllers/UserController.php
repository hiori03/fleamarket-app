<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->only(['mypage', 'mypage_profileform']);
    }

    public function mypage(Request $request)
    {
        $user = Auth::user();
        $page = $request->query('page');
        $items = collect();

        if ($page === 'sell') {
            $items = Item::where('user_id', Auth::id())->get();
        } elseif ($page === 'buy') {
            $orders = Order::where('user_id', Auth::id())->get();
            $items = Item::whereIn('id', $orders->pluck('item_id'))->get();
        }

        return view('mypage', compact('items', 'page', 'user'));
    }

    public function mypage_profileform()
    {
        $user = Auth::user();
        return view('mypage_profile', compact('user'));
    }

    public function mypage_profile(ProfileRequest $request)
    {
        $user = Auth::user();

        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('profiles', 'public');

            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->profile_image = $path;
        }

        $user->name = $request->name;
        $user->save();

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
            return redirect('/mypage?page=sell');
        }
    }
}
