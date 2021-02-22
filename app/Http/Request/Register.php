<?php

namespace App\Http\Request;

use App\Http\Request\Request;

class Register extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required|regex:/^1[345789][0-9]{9}$/|unique:user,username',
            'password' => 'required|min:8',
            'repassword' => 'required|same:password',
            'head_url' => 'required|url',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => '账号不能为空',
            'username.unique' => '当前账号已注册',
            'password.required' => '密码不能为空',
            'repassword.required' => '请确认密码',
            'head_url.required' => '头像不能为空',
            'username.regex' => '手机号格式错误',
            'password.min' => '密码不能少于8位',
            'repassword.same' => '两次密码不一致',
            'head_url.url' => '请重新选择头像',
        ];
    }
}
