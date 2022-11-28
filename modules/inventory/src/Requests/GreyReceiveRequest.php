<?php

namespace SkylarkSoft\GoRMG\Inventory\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class GreyReceiveRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }
//
//    public function messages(): array
//    {
//        return [
//            'challan_no.required' => 'challan no field is required',
//
//        ];
//    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function rules(): array
    {
        return [
            'challan_no' => request()->get('received_type_id') == 1 ? 'required' : 'sometimes',
            'barcode' => request()->get('received_type_id') == 2 ? 'required' : 'sometimes',
            'received_type_id' => 'required|in:1,2',
            'source_id' => 'required',
            'factory_id' => 'required',
        ];
    }

}
