<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;


class ApplyRequest extends FormRequest
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
            'category_id' => ['required', Rule::exists('categories', 'id')],
            'gender'                => 'required',
            'job'                   => 'required',
            'salary'                => 'required',
            'number'                => 'required',
            'permanent_residence'   => 'required',
            'location'              => 'required'
        ];
    }
}
