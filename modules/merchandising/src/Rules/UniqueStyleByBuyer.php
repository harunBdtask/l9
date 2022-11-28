<?php

namespace SkylarkSoft\GoRMG\Merchandising\Rules;

use Illuminate\Contracts\Validation\Rule;
use Skylarksoft\Ordertracking\Models\Style;

class UniqueStyleByBuyer implements Rule
{
    protected $buyer_id;
    protected $style_name;

    public function __construct($data)
    {
        $this->buyer_id = $data['buyer_id'];
        $this->style_name = $data['style_id'];
    }

    public function passes($attribute, $value)
    {
        $query = Style::where(['buyer_id' => $this->buyer_id, 'name' => $this->style_name]);
        if (request()->route('id')) {
            $query = $query->where('id', '!=', request()->route('id'));
        }
        $sample = $query->first();

        return $sample ? false : true;
    }

    public function message()
    {
        return 'This style is exists under this buyer.';
    }
}
