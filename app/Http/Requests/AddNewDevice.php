<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddNewDevice extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
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
            'priority' => 'required',
            'quantity' => 'required',
            'price' => 'required',
            'warranty' => 'required',
            'status' => 'required',
            'supplier' => 'required',
            'purchase_dt' => 'required',
            'location' => 'required',
        ];
    }
}
