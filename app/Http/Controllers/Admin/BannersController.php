<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannersController extends Controller
{
    //
    public function index()
    {
        $page_title = 'Banners Manager';
        $pages = Banner::latest()->paginate(getPaginate(10));
        return view('admin.banners.index')->with(compact('pages', 'page_title'));
    }

    public function create()
    {
        $page_title = 'Add Banner';
        return view('admin.banners.add')->with(compact('page_title'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'banner' => 'required',
            'link'=>'required'
        ]);
        $banner = new Banner();
        $image      = $request->file('banner');
        if ($image) {
            $fileName   = $image->getClientOriginalName();
            $Path = public_path('assets/images/banners');
            $image->move($Path, $fileName);
            $banner->banner_url = env('APP_URL') . 'assets/images/banners/' . $fileName;
            $banner->link=$request->link;
            $banner->save();
        }


        return redirect()->route('admin.banners');
    }

    public function edit($id)
    {
        $page_title = 'Edit Banner Page';
        $bannerdetail = Banner::find($id);
        return view('admin.banners.edit')->with(compact('bannerdetail', 'page_title'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
           // 'banner' => 'required',
            'link'=>'required'
        ]);
        $banner = Banner::find($request->id);
        $image      = $request->file('banner');
        if ($image) {
            $fileName   = $image->getClientOriginalName();
            $Path = public_path('assets/images/banners');
            $image->move($Path, $fileName);
            $banner->banner_url = env('APP_URL') . 'assets/images/banners/' . $fileName;
         
        }
        $banner->link=$request->link;
        $banner->save();


        return redirect()->route('admin.banners');
    }


    public function destroy($id)
    {
        $center = Banner::where('id', $id)->first();
        $center->delete();
        return redirect()->back()->with('success', 'Banner deleted Successfully!');
    }
}
