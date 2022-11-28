<?php

namespace SkylarkSoft\GoRMG\Merchandising\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuotationInquiryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    public function messages()
    {
        return [
            'factory_id.required' => 'This field is required',
            'buyer_id.required' => 'This field is required',
            'style_name.required' => 'This field is required',
            'garment_item_id.required' => 'This field is required',
            'inquiry_date.required' => 'This field is required',
            'inquiry_date.date' => 'This field is required a date',
        ];
    }

    public function rules()
    {
        $rules = [
            'factory_id' => 'required',
            'buyer_id' => 'required',
            'style_name' => 'required',
            'garment_item_id' => 'required',
            'inquiry_date' => 'required|date',
            'file_name' => 'nullable|max:2048|mimes:jpeg,png,jpg,gif,svg,doc,docx,pdf,xls, xlsx',
        ];

        return $rules;
    }
}
