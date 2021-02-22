<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Model\UserModel;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Request\Login;
use App\Http\Request\Register;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $s_user;

    /**
     * @var \Medoo\Medoo
     */
    protected $medoo;

    public function __construct(Request $request)
    {
        $this->medoo = app('medoo');

        $BearerToken = $request->server('HTTP_AUTHORIZATION');

        $authToken = Str::of($BearerToken)->replaceFirst('Bearer ', '');

        $this->s_user = Cache::get($authToken);
    }

    public function registerApi(Register $request)
    {
        $param = $request->post();

        $user = UserModel::create([
            'username' => $param['username'],
            'password' => Hash::make($param['password']),
            'head_url' => $param['head_url'],
            'admin' => 0,
            'create_time' => time(),
            'is_delete' => 0,
            'status' => 1,
            'update_time' => time(),
        ]);

        $token = $user->createToken('login-token')->plainTextToken;

        $minutes = config('sanctum.expiration');

        Cache::put($token, [
            'username' => $param['username'],
            'head_url' => $param['head_url'],
        ], $minutes * 60);

        return [200, [
            'userinfo' => [
                'username' => $param['username'],
                'head_url' => $param['head_url'],
            ],
            'token' => $token,
        ]];
    }

    public function loginApi(Login $request)
    {
        $param = $request->post();

        $user = UserModel::where([
            'username' => $param['username'],
            'admin' => 0,
            'status' => 1,
            'is_delete' => 0,
        ])->first();

        if (!$user) {
            return [500, '该用户不存在'];
        }

        $user->tokens()->delete();

        $token = $user->createToken('login-token')->plainTextToken;

        $minutes = config('sanctum.expiration');

        Cache::put($token, [
            'username' => $param['username'],
            'head_url' => $user['head_url'],
        ], $minutes * 60);

        return [200, [
            'userinfo' => [
                'username' => $param['username'],
                'head_url' => $user['head_url'],
            ],
            'token' => $token,
        ]];
    }

    public function advertListApi()
    {
        $data = $this->medoo->select('advert', [
            'id', 'title', 'pos', 'img', 'url'
        ], ['ORDER' => ['id' => 'DESC'], 'LIMIT' => 10, 'is_delete' => 0]);

        return [200, $data];
    }
}
