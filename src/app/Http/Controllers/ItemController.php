<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $tab = $request->query('tab');
        $items = Item::all();
        return view('index', compact('items', 'tab'));
    }

    public function search(Request $request)
    {
        $keyword = $request->input('search');
        $query = Item::query();

        if ($keyword) {
            $query->where('item_name', 'like', "%{$keyword}%");
        }

        $items = $query->get();
        return view('index', compact('items'));
    }
}
