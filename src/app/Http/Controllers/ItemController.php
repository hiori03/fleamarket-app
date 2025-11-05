<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\AddressRequest;

class ItemController extends Controller
{
    public function __construct()
    {
        //ログイン必須ならここに追加していく
        $this->middleware('auth')->only(['favorite', 'comment', 'sellform', 'purchaseform', 'purchaseaddressform']);
    }

    public function index(Request $request)
    {
        $tab = $request->query('tab');
        $search = $request->query('search', '');

        if ($tab === 'mylist') {
            if (Auth::check()) {
                $items = Auth::user()->favoriteItems()
                    ->when($search, fn($q) => $q->where('item_name', 'like', "%{$search}%"))
                    ->get();
            } else {
                $items = collect();
            }
        } else {
            $items = Item::when($search, fn($q) => $q->where('item_name', 'like', "%{$search}%"))
                ->get();
        }

        return view('index', compact('items', 'tab'));
    }



    public function search(Request $request)
    {
        $search = $request->input('search');
        $query = Item::query();

        if ($search) {
            $query->where('item_name', 'like', "%{$search}%");
        }

        $items = $query->get();
        return view('index', compact('items', 'search'));
    }

    public function show(Item $item)
    {
        $item->load('categories');
        return view('item', compact('item'));
    }

    public function favorite(Item $item)
    {
        $user = Auth::user();

        if ($user->favoriteItems()->where('item_id', $item->id)->exists()) {
            $user->favoriteItems()->detach($item->id);
        } else {
            $user->favoriteItems()->attach($item->id);
        }

        return back();
    }

    public function comment(CommentRequest $request, Item $item)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        $item->comments()->create([
            'user_id' => $user->id,
            'comment' => $request->comment,
        ]);

        return back();
    }


    public function purchaseform(Request $request, Item $item)
    {
        $user = Auth::user();
        $sessionAddress = session('purchase_address');
        if ($sessionAddress) {
            $address = (object) $sessionAddress;
        } else {
            $address = $user->address;
        }
        $payment_method = $request->query('payment_method');
        return view('purchase', compact('item', 'address', 'payment_method'));
    }

    public function purchase(PurchaseRequest $request, Item $item)
    {
        

        Order::create([
            'user_id' => Auth::id(),
            'item_id' => $item->id,
            'payment_method' => $request->payment_method,
            'postal_order' => $request->postal_order,
            'address_order' => $request->address_order,
            'building_order' => $request->building_order,
        ]);

        return redirect('/');
    }

    public function purchaseaddressform(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('purchase_address', compact('item'));
    }

    public function addressUpdate(AddressRequest $request, $item_id)
    {
        $data = $request->only(['postal', 'address', 'building']);
        session(['purchase_address' => $data]);
        return redirect()->route('purchaseform', ['item' => $item_id]);
    }


    public function sellform()
    {
        $categories = Category::all();
        return view('sell', compact('categories'));
    }

    public function sell(ExhibitionRequest $request)
    {
        $item = Item::create([
            'user_id' => Auth::id(),
            'item_name' => $request->item_name,
            'brand' => $request->brand,
            'content' => $request->content,
            'situation' => $request->situation,
            'price' => $request->price,
            'description' => $request->description,
            'item_image' => $request->file('item_image')
                ? $request->file('item_image')->store('public/items')
                : null,
        ]);

        $item->categories()->attach($request->category_ids);

        return view('sell')->with('success', '商品を出品しました');
    }
}