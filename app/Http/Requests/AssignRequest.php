<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AssignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'request_id' => 'required',
            'complete_date' => 'required|date_format:d-m-Y|after_or_equal:'.date('d-m-Y'),
            'request_type' => 'required',
            'handler' => 'required',
            'assign_content' => 'required',
        ];
    }
}
