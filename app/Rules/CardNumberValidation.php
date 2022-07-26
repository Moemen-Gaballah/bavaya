<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class CardNumberValidation implements Rule
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
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $newValue = str_replace(' ', '', $value);
        if(strlen($newValue) != 16)
            return false;

        if(!ctype_digit($newValue))
            return false;

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'invalid card number must be numeric and 16 digits';
    }
}
