<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LocaleController extends Controller
{
    public function switch(string $locale, Request $request) 
    {
        $available = ['es','en'];
        if (!in_array($locale, $available, true)) {
            $locale = config('app.fallback_locale', 'en');
        }
        session(['locale' => $locale]);
        return redirect()->back()->with('success', __('site.flash.lang_updated'));
    }
}
