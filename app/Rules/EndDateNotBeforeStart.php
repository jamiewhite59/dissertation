<?php

namespace App\Rules;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class EndDateNotBeforeStart implements ValidationRule, DataAwareRule
{
    protected $data = [];

    public $start_date;

    public function setData(array $data): static {
        $this->data = $data;

        return $this;
    }

    public function __construct($start_date) {
        $this->start_date = $start_date;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $end = Carbon::parse($value);
        $start = Carbon::parse($this->start_date);

        if (is_null($value) === false) {
            if ($start > $end) {
                $fail('The end date must not be before the start date');
            }
        }

    }
}
