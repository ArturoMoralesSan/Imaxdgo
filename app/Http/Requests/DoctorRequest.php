<?php

namespace App\Http\Requests;

use App\Rules\NotLowercase;
use App\Rules\NotUppercase;
use Illuminate\Validation\Rule;

class DoctorRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => ['required', new NotUppercase, new NotLowercase, 'max:100'],
            'last_name' => ['required', new NotUppercase, new NotLowercase, 'max:100'],
            'address' => ['required', 'max:100'],
            'cp' => ['required','max:10'],
            'email' => ['required', 'email','max:100'],
            'tel' => ['required', 'string','max:12'],

        ];
    }
}
