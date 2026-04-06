<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use GuzzleHttp\Client;

/**
 * Validation rule to verify a Google reCAPTCHA token.
 *
 * Sends the token to the reCAPTCHA siteverify API and returns
 * whether the challenge was successfully completed.
 */
class ValidRecaptcha implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes by verifying the reCAPTCHA token.
     *
     * Sends a POST request to the Google reCAPTCHA siteverify endpoint using
     * the configured secret key and the token provided by the user.
     *
     * @param  string  $attribute  The name of the attribute being validated.
     * @param  mixed   $value      The reCAPTCHA token submitted by the client.
     * @return bool True if the reCAPTCHA challenge was passed successfully.
     */
    public function passes($attribute, $value)
    {
        // Validate ReCaptcha
        $client = new Client([
            'base_uri' => 'https://google.com/recaptcha/api/'
        ]);
        $response = $client->post('siteverify', [
            'query' => [
                'secret' => env('GOOGLE_RECAPTCHA_SECRET'),
                'response' => $value
            ]
        ]);
        return json_decode($response->getBody())->success;
    }

    /**
     * Get the validation error message shown when reCAPTCHA verification fails.
     *
     * @return string The error message string.
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
