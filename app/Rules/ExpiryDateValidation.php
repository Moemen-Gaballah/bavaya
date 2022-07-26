<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ExpiryDateValidation implements Rule
{
    protected $msg = 'Invalid visa - Expiry Date';
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
        $explodeValue = explode("/", $value);
        if(isset($explodeValue[0]) && isset($explodeValue[1])){
            $month = $explodeValue[0];
            $year = $explodeValue[1];

            if($month > 12){
                $this->msg = 'month must less than or equal 12 and year bigger than or equal ' . date('Y');
                return false;
            }

            if($year < date('Y')){
                return false;
            }

            if($year == date('Y') && $month < date('m')){
                return false;
            }

            return true;
        }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->msg;
    }
}
