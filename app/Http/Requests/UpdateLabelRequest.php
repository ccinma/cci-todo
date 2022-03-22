<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLabelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'string',
                'min:1',
                'max:20',
            ],
            'color' => [
                'string',
                'min:4',
                'max:7',
                'regex:/#([a-f0-9]{3}){1,2}\b/i',
            ],
        ];
    }
}
