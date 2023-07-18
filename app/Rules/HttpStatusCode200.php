<?php

namespace App\Rules;

use Closure;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Contracts\Validation\ValidationRule;

class HttpStatusCode200 implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $cacheKey = 'url_validation_' . md5($value);

        // Check if the response is cached
        if (Cache::has($cacheKey)) {
            $statusCode = Cache::get($cacheKey);
        } else {
            // Make an HTTP request to the URL and check the response status code
            $statusCode = Http::get($value)->status();

            // Cache the response for future requests
            Cache::put($cacheKey, $statusCode, now()->addHours(12));
        }

        if ($statusCode !== 200) {
            $fail('The :attribute must return a status code of 200.');
        }
    }
}
