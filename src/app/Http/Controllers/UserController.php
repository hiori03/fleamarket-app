<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Order;

class UserController extends Controller
{
    public function __construct()
    {
        //ログイン必須ならここに追加していく
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

        return view('mypage_profile');
    }

    public function mypage_profile(ProfileRequest $request)
    {
        $user = Auth::user();

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
            return redirect('/mypage?page=sell');
        }
    }
}
