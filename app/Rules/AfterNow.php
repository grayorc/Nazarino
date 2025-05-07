<?php

namespace App\Rules;

use Closure;
use Hekmatinasser\Verta\Verta;
use Illuminate\Contracts\Validation\ValidationRule;

class AfterNow implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
            if (Verta::parse($value)->lte(Verta::today())) {
                $fail(__('تاریخ انتخاب شده باید بعد از امروز باشد.'));
            }
    }
}
