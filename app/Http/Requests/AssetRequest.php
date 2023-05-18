<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AssetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'model' => 'required',
            'quantity' => 'required',
            'cost' => 'required',
            'warranty' => 'required',
            'status' => 'required',
            'supplier' => 'required',
            'order_no' => 'required',
            'purchase_dt' => 'required',
            'location' => 'required',
            'order_no' => 'required',
        ];
    }
}
