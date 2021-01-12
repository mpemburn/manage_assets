<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class IsAllowedDomain implements Rule
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
     * IsAllowedDomains will test the domain of the email address and pass if it matches
     * one of the domains set in .env as a comma-delimited string in ALLOWED_DOMAINS
     * If ALLOWED_DOMAINS is not set, any domain will pass.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $domain = preg_replace('/(.*)(@)(.*)/', '$3', $value);
        $allowed = collect(explode(',', env('ALLOWED_DOMAINS')))->filter();

        $allow = true;

        if ($allowed->isNotEmpty()) {
            return $allowed->contains(static function ($value) use ($domain) {
                return $value === $domain;
            });
        }

        return $allow;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return 'Registration is restricted to supported organizations';
    }
}
