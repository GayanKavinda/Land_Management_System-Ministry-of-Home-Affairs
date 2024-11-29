<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;


class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // app/Http/Middleware/SetLocale.php
        $locale = $request->segment(1); // Assuming the first segment of the URL contains the language code

        if (in_array($locale, config('app.locales'))) {
            App::setLocale($locale);
            Session::put('locale', $locale); // Store the selected locale in the session
        } else {
            $locale = Session::get('locale', config('app.fallback_locale'));
            App::setLocale($locale);
        }

        return $next($request);
    }
}
