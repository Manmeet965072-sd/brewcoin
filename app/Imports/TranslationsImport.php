<?php

namespace App\Imports;

use App\Model\UkImport;
use App\Model\UsImport;
use Illuminate\Support\Facades\Auth;
use App\Model\Email;
use App\Model\ImportLog;
use App\Model\PostalTransaction;
use App\Model\Transaction;
use App\Model\EmailFile;
use App\Model\ThirdPartySupporterRef;
use App\Model\MailingSupporter;
use App\Model\Mailing;
use App\Model\SupporterLog;
use App\Models\Key;
use App\Models\Language;
use App\Models\LanguageKeyValue;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class TranslationsImport implements ToModel, WithHeadingRow, WithChunkReading
{

    public $data = [];
    private $sr_no = 1;
    public function __construct($data)
    {
        $this->data = $data;
    }
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */


    public function model(array $row)
    {
        if (!array_key_exists('label', $row)) {
            throw new \Exception(__('messages.import_header_row_not_exist'));
        }

        $languages = Language::get();
        foreach ($languages as $language) {
            if (!array_key_exists(lcfirst($language->name), $row)) {
                throw new \Exception(__('messages.import_header_row_not_exist'));
            }
        }
        try {
            $key = Key::where('name', $row['label'])->first();

            if ($key == NULL) {
                $key = new Key();
                $key->name = $row['label'];
                $key->save();
            }

            foreach ($languages as $language) {
                $translation = LanguageKeyValue::where('key_id', $key->id)->where('lang_id', $language->id)->first();
                if ($translation == NULL) {
                    $translation = new LanguageKeyValue();
                    $translation->key_id = $key->id;
                    $translation->lang_id = $language->id;
                    $translation->value = $row[lcfirst($language->name)];
                    $translation->save();
                } else {
                    $translation->value = $row[lcfirst($language->name)];
                    $translation->save();
                }
                // $translation = LanguageKeyValue::firstOrCreate(
                //     ['key_id' => $key->id, 'lang_id' => $language->id],
                //     ['value' => $row[lcfirst($language->name)]]
                // );
            }

            return $translation;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function chunkSize(): int
    {
        return 200;
    }
}
