<?php

namespace App\Http\Controllers\Admin;

use App\Exports\TranslationsExport;
use App\Http\Controllers\Controller;
use App\Imports\TranslationsImport;
use App\Models\Key;
use App\Models\Language;
use App\Models\LanguageKeyValue;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class LanguageKeyValueController extends Controller
{
    //
    public function index()
    {
        $page_title = 'Translation Manager';
        $translations = Key::with('translations')->latest()->paginate(getPaginate(10));
        $languages = Language::get();
        return view('admin.translations.index')->with(compact('translations', 'languages', 'page_title'));
    }

    public function export()
    {
        return Excel::download(new TranslationsExport, 'translations.xlsx');
    }

    public function openImportForm(Request $request)
    {
        $page_title = 'Import Translation';
        return view('admin.translations.import')->with(compact('page_title'));;
    }

    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'upload_excel' => 'required',
        ]);
        if ($validator->fails()) {
            foreach ($validator->errors()->toArray() as $error_key => $error_value) {
                return response()->json(['status' => 0, "message" => $error_value[0]]);
            }
        }
        try {
            if ($request->hasfile('upload_excel') && $request->file('upload_excel')) {
                $csv_file = $request->file('upload_excel');
                $filename = str_replace(' ', '', md5(time()) . '_' . $csv_file->getClientOriginalName());
                $FileEnconded =  File::get($request->upload_excel);
                $path = 'file/' . $filename;
                Storage::put($path, (string)$FileEnconded, 'public');
                $data_array = [
                    'file_path' => $path,
                    'model_type' => 'translation_import',

                ];
                Excel::import(new TranslationsImport($data_array), request()->file('upload_excel'));
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 0, 'erorr' => $e->getMessage(),
                'error_line' => $e->getLine()
            ]);
           
        }
         return redirect()->route('admin.translations');
    }
}
