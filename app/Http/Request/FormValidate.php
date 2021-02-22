<?php

namespace App\Http\Request;

use App\Http\Request\Request;

class FormValidate extends Request
{
    /**
      * Get the validation rules that apply to the request.
      *
      * @return array
      */
    public function rules()
    {
        return [
            'title' => 'required|min:6',
            'date' => 'required|date',
            'email' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => '标题不能为空',
            'title.min' => '标题最少6个字',
            'date.required'  => '日期不能为空',
            'date.date'  => '日期格式错误',
            'email.required'  => 'email不能为空',
            'email.email'  => 'email格式错误',
        ];
    }
}
