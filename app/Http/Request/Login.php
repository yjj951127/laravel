<?php

namespace App\Http\Request;

use App\Http\Request\Request;

class Login extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|regex:/^1[345789][0-9]{9}$/',
            'password' => 'required|min:8',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => '账号不能为空',
            'password.required' => '密码不能为空',
            'username.regex' => '手机号格式错误',
            'password.min' => '密码不能少于8位',
        ];
    }
}
