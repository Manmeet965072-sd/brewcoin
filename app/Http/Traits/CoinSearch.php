<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Http;
use stdClass;


trait CoinSearch
{
    public function search($search)
    {
        $response = Http::get("https://pro-api.coingecko.com/api/v3/search?query=" . $search."&x_cg_pro_api_key=CG-sYvZVyLemCpKVyjdrb1eQt3o");
        $response = json_decode($response->getBody()->getContents());
        $array = json_decode(json_encode($response), true);
        $ids = [];
        foreach ($array as $arr) {
            $ids = array_column($arr, 'id');
            break;
        }
        return (implode(',', $ids));
    }
}
