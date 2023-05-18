<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AddNewReqRequest extends FormRequest
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
            'requester' => 'required',
            'department' => 'required',
            'priority' => 'required',
            'completeDate' => 'required|date_format:d-m-Y|after_or_equal:'.date('d-m-Y'),
            'requestTp' => 'required',
            'subject' => 'required',
            'content' => 'required',
        ];
    }
}
