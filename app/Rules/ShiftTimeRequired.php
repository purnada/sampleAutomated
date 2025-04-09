<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ShiftTimeRequired implements Rule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function passes($attribute, $value)
    {
        // $value contains the entire 'shift' array
        foreach ($value as $shift) {
            // Check if each shift's end_time is after its start_time
            if (strtotime($shift['end_time']) <= strtotime($shift['start_time'])) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'Each shift end time must be greater than its start time.';
    }
}
