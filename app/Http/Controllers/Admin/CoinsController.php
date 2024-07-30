<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coin;
use Illuminate\Http\Request;

class CoinsController extends Controller
{
    //
    public function index_active()
    {
        $page_title = 'Assets Manager';
        $coins = Coin::where('is_active', 1)->latest()->paginate(getPaginate(10));
        return view('admin.coins.index')->with(compact('coins', 'page_title'));
    }

    public function index_banned()
    {
        $page_title = 'Assets Manager';
        $coins = Coin::where('is_active', 0)->latest()->paginate(getPaginate(10));
        return view('admin.coins.index')->with(compact('coins', 'page_title'));
    }

    public function change_status(Request $request, $id, $status)
    {
        $data = $request->all();
        $coin = Coin::find($id);
        $coin->is_active = $status;
        $coin->save();
        return redirect()->back()->with('success', 'Status changed Successfully!');
    }
}
