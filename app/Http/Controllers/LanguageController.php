<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Redirect;

class LanguageController extends Controller
{
    public function switchLanguage($language)
    {
        // Check if the selected language is supported
        if (in_array($language, config('app.locales'))) {
            App::setLocale($language);
            session()->put('locale', $language);
        }

        return Redirect::back();
    }
}