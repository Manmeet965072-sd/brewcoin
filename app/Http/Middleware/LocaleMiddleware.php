<?php

namespace App\Http\Middleware;

use App\Models\Language;
use Closure;
use Illuminate\Http\Request;

class LocaleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // available language in template array
        //$availLocale = ['en' => 'en', 'fr' => 'fr', 'de' => 'de', 'pt' => 'pt', 'vn' => 'vn', 'ar' => 'ar', 'th' => 'th', 'es' => 'es', 'it' => 'it', 'nl' => 'nl'];

        // Locale is enabled and allowed to be change
        // if (session()->has('locale') && array_key_exists(session()->get('locale'), $availLocale)) {
        //     // Set the Laravel locale
        //     app()->setLocale(session()->get('locale'));
        // }
        $languages = Language::where('status', 1)->pluck('lang_code')->toArray();
        $availLocale = [];
        foreach ($languages as $language) {
            $availLocale[$language] = $language;
        }
   

        if ($request->hasHeader("Accept-Language") && array_key_exists($request->header("Accept-Language"), $availLocale)) {
            app()->setLocale($request->header("Accept-Language"));
        } else {
            app()->setLocale('en');
        }

        return $next($request);
    }
}
