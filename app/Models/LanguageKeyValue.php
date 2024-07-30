<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LanguageKeyValue extends Model
{
    use HasFactory;

    public function language()
    {
        return $this->belongsTo(Language::class,'lang_id','id');
    }

    public function key()
    {
        return $this->belongsTo(Key::class);
    }
}
