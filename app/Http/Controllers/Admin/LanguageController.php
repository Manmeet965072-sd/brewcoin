<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    //
    public function index()
    {
        $page_title = 'Language Manager';
        $languages = Language::latest()->paginate(getPaginate(10));
        return view('admin.languages.index')->with(compact('languages', 'page_title'));
    }

    public function create()
    {
        $page_title = 'Add Language Page';
        return view('admin.languages.add')->with(compact('page_title'));
    }

    public function store(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'lang_code' => 'required',
        ]);
        $language = new Language();
        $language->name = $request->name;
        $language->lang_code = $request->lang_code;
        $language->status = $request->status;
        $language->save();

        return redirect()->route('admin.languages');
    }


    public function edit($id)
    {
        $page_title = 'Edit Language Page';
        $language = Language::find($id);
        return view('admin.languages.edit')->with(compact('language', 'page_title'));
    }

    public function update(Request $request)
    {

        $this->validate($request, [
            'name' => 'required',
            'lang_code' => 'required',
        ]);
        $language = Language::find($request->id);
        $language->name = $request->name;
        $language->lang_code = $request->lang_code;
        $language->status = $request->status;
        $language->save();

        return redirect()->route('admin.languages');
    }


    public function destroy($id)
    {
        $center = Language::where('id', $id)->first();
        $center->delete();
        return redirect()->back()->with('success', 'Language deleted Successfully!');
    }
}
