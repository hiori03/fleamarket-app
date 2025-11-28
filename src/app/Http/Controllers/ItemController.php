<?php

namespace App\Http\Controllers;

use Stripe\Stripe;
use Stripe\Checkout\Session;
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
        $this->middleware('auth')->only(['favorite', 'comment', 'sellform', 'purchaseform', 'purchaseaddressform', 'purchase', 'purchaseaddressform']);
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
            $items = Item::when($search, fn($q) => $q->where('item_name', 'like', "%{$search}%"))->when(Auth::check(), fn($q) => $q->where('user_id', '!=', Auth::id()))->get();
        }

        return view('index', compact('items'));
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
        Stripe::setApiKey(config('services.stripe.secret'));
        $payment_method = (int) $request->payment_method;
        $user = Auth::user();

        session([
            'item_id' => $request->item_id,
            'payment_method' => (int) $request->payment_method,
            'postal_order' => $request->postal_order,
            'address_order' => $request->address_order,
            'building_order' => $request->building_order,
        ]);

        if ($payment_method === 1) {
            $session = Session::create([
                'mode' => 'payment',
                'payment_method_types' => ['card'],
                'success_url' => route('purchase.success', ['item_id' => $item->id]),
                'cancel_url' => route('purchase.cancel', ['item_id' => $item->id]),
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => ['name' => (string) $item->item_name],
                        'unit_amount' => (int) $item->price,
                    ],
                    'quantity' => 1,
                ]],
            ]);

            return redirect($session->url);
        } if ($payment_method === 0) {

            Order::create([
                'user_id' => $user->id,
                'item_id' => $item->id,
                'payment_method' => $request->payment_method,
                'postal_order' => $request->postal_order,
                'address_order' => $request->address_order,
                'building_order' => $request->building_order,
            ]);

            $item->is_sold = true;
            $item->save();

            session()->forget(['item_id', 'payment_method', 'postal_order', 'address_order', 'building_order']);

            return redirect('/');
        }
    }

    public function success(Request $request)
    {
        $item_id = $request->query('item_id');
        $payment_method = (int) session('payment_method');
        $postal_order = session('postal_order');
        $address_order = session('address_order');
        $building_order = session('building_order');

        if ($payment_method === 1) {
            Order::create([
                'user_id' => Auth::id(),
                'item_id' => $item_id,
                'payment_method' => $payment_method,
                'postal_order' => $postal_order,
                'address_order' => $address_order,
                'building_order' => $building_order,
            ]);

            $item = Item::find($item_id);
            if ($item) {
                $item->is_sold = true;
                $item->save();
            }
        }
        session()->forget(['item_id', 'payment_method', 'postal_order', 'address_order', 'building_order']);

        return redirect('/');
    }

    public function cancel(Request $request)
    {
        $item_id = $request->query('item_id');
        session()->forget(['item_id', 'payment_method', 'postal_order', 'address_order', 'building_order']);
        return redirect()->route('purchaseform', ['item' => $item_id]);
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
        $imagePath = null;
        if ($request->hasFile('item_image')) {
            $path = $request->file('item_image')->store('products', 'public');
            $imagePath = 'storage/' . $path;
        }

        $item = Item::create([
            'user_id' => Auth::id(),
            'item_name' => $request->item_name,
            'brand' => $request->brand,
            'content' => $request->content,
            'situation' => $request->situation,
            'price' => $request->price,
            'description' => $request->description,
            'item_image' => $imagePath
        ]);

        $item->categories()->attach($request->category_id);

        $categories = Category::all();

        return redirect('/');
    }
}