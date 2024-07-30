<?php

namespace App\Exports;

use App\Models\Key;
use App\Models\Language;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TranslationsExport implements FromView
{
    /**
    * @return \Illuminate\Support\Collection
    */

    public function view() : View
    {
        $translations = Key::with('translations')->get();
        $languages=Language::get();
       
        return view('exports.translations', [
            'translations' => $translations,
            'languages'=>$languages
        ]);
    }

}
