<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if locale is already set in session
        $locale = Session::get('locale');

        // If no locale in session, detect from browser
        if (!$locale) {
            $locale = $this->detectBrowserLocale($request);
            Session::put('locale', $locale);
        }

        // Set the application locale if it's supported
        if (array_key_exists($locale, config('app.available_locales'))) {
            App::setLocale($locale);
        }

        return $next($request);
    }

    /**
     * Detect the preferred locale from browser Accept-Language header
     */
    private function detectBrowserLocale(Request $request): string
    {
        $acceptLanguage = $request->header('Accept-Language');
        $availableLocales = array_keys(config('app.available_locales'));
        $defaultLocale = config('app.locale');

        if (!$acceptLanguage) {
            return $defaultLocale;
        }

        // Parse Accept-Language header
        $languages = [];
        foreach (explode(',', $acceptLanguage) as $lang) {
            $parts = explode(';q=', trim($lang));
            $locale = trim($parts[0]);
            $quality = isset($parts[1]) ? (float) $parts[1] : 1.0;

            // Extract language code (e.g., 'de-DE' -> 'de')
            $langCode = strtolower(substr($locale, 0, 2));

            $languages[$langCode] = $quality;
        }

        // Sort by quality (preference)
        arsort($languages);

        // Find the first supported language
        foreach ($languages as $langCode => $quality) {
            if (in_array($langCode, $availableLocales)) {
                return $langCode;
            }
        }

        return $defaultLocale;
    }
}
