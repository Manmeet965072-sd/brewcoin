<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Page;

class CmsController extends Controller
{

    public function index()
    {
        $page_title = 'CMS Manager';
        $pages = Page::latest()->paginate(getPaginate(10));
        return view('admin.cms.index')->with(compact('pages', 'page_title'));
    }

    public function create()
    {
        $page_title = 'Add CMS Page';
        return view('admin.cms.add')->with(compact('page_title'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|regex:/^\S*$/u',
            'title' => 'required',
            'description' => 'required|',
        ]);
        $page = new Page();
        $page->title = $request->title;
        $page->name = $request->name;
        $page->description = $request->description;
        $page->meta_title = $request->meta_title;
        $page->meta_keyword = $request->meta_keyword;
        $page->meta_description =$request->meta_description;
        $page->status = $request->status;
        $page->save();

        return redirect()->route('admin.cms');
    }

    public function show($id)
    {
        $page_title = 'CMS Page Detail';
        $cmsdetail = Page::find($id);
        return view('admin.cms.show')->with(compact('cmsdetail', 'page_title'));
    }

    public function edit($id)
    {
        $page_title = 'Edit CMS Page';
        $cmsdetail = Page::find($id);
        return view('admin.cms.edit')->with(compact('cmsdetail', 'page_title'));
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'title' => 'required',
            'description' => 'required',
        ]);

        $page = Page::where('id', $request->id)->first();
        $page->title = $request->title;
        $page->name = $request->name;
        $page->description = $request->description;
        $page->meta_title = $request->meta_title;
        $page->meta_keyword = $request->meta_keyword;
        $page->meta_description = $request->meta_description;
        $page->status = $request->status;
        $page->save();

        return redirect()->route('admin.cms');
    }


    public function destroy($id)
    {
        $center = Page::where('id', $id)->first();
        $center->delete();
        return redirect()->back()->with('success', 'Page deleted Successfully!');
    }
}
