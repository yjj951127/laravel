<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\UserModel;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Http\Request\FormValidate as FormRequest;

class IndexController extends Controller
{
    /**
     * @var \Medoo\Medoo
     */
    protected $medoo;

    protected $s_user;

    public function __construct(Request $request)
    {
        $this->medoo = app('medoo');

        $BearerToken = $request->server('HTTP_AUTHORIZATION');

        $authToken = Str::of($BearerToken)->replaceFirst('Bearer ', '');

        $this->s_user = Cache::get($authToken);
    }

    public function selectApi()
    {
        $data = $this->medoo->select('user', ['id', 'username', 'head_url', 'time'], ['is_delete' => 0]);

        return ['data' => $data];
    }

    public function getApi()
    {
        $data = $this->medoo->get('user', [
            'id', 'username', 'head_url', 'time'
        ], ['is_delete' => 0]);

        return ['data' => $data];
    }

    public function insertApi(Request $request)
    {
        $param = $request->post();

        if (empty($param['username'])) {
            return ['msg' => '请输入您的姓名'];
        }

        if (empty($param['password'])) {
            return ['msg' => '请输入您的密码'];
        }

        $pdosmt = $this->medoo->insert('user', [
            'username' => $param['username'],
            'password' => $param['password'],
        ]);

        if ($pdosmt->rowCount() > 0) {
            return ['data' => true];
        }

        return ['msg' => '系统错误，操作失败'];
    }

    public function updateApi(Request $request)
    {
        $param = $request->post();

        if (empty($param['id'])) {
            return ['msg' => '缺少重要参数ID'];
        }

        if (empty($param['username'])) {
            return ['msg' => '请输入您的姓名'];
        }

        if (empty($param['password'])) {
            return ['msg' => '请输入您的密码'];
        }

        $id = $this->medoo->get('user', 'id', ['id' => $param['id']]);
        if (!$id) {
            return ['msg' => '数据错误，找不到该数据'];
        }

        $pdosmt = $this->medoo->update('user', [
            'username' => $param['username'],
            'password' => $param['password']
        ], ['id' => $param['id']]);

        if ($pdosmt->rowCount() > 0) {
            return ['data' => true];
        }

        return ['msg' => '系统错误，操作失败'];
    }

    public function deleteApi(Request $request)
    {
        $id  = $request->post('id');

        $id = $this->medoo->get('user', 'id', ['id' => $id]);
        if (!$id) {
            return ['msg' => '数据错误，找不到该数据'];
        }

        $pdosmt = $this->medoo->delete('user', ['id' => $id]);

        if ($pdosmt->rowCount() > 0) {
            return ['data' => true];
        }

        return ['msg' => '系统错误，操作失败'];
    }

    public function whereApi()
    {
        // $data = $this->medoo->select('user', [
        //     'id', 'username', 'password', 'head_url'
        // ], [
        //     // 'id[><]' => [3, 10],
        //     // 'ORDER' => ['id' => 'DESC'],

        //     // 'OR' => [
        //     //     'id[<]' => 10,
        //     //     'username[~]' => 'user10%'
        //     // ]

        //     // 'id[<]' => 10,
        //     // 'ORDER' => [
        //     //     'id' => [3, 9,7,4, 8],
        //     // ]
        // ]);

        $data = $this->medoo->select('user', [
            'admin',
            'count' => $this->medoo->raw('COUNT(<id>)')
        ], [
            'GROUP' => 'admin'
        ]);

        return ['data' => $data];
    }

    public function pageApi(Request $request)
    {
        $page = $request->post('page', 1);
        $pageSize = $request->post('page_size', 10);

        $param = $request->post();

        $where = [];
        $where['is_delete'] = 0;

        if (!empty($param['username'])) {
            $where['username[~]'] = $param['username'];
        }

        if (!empty($param['status']) && in_array($param['status'], [1, 2])) {
            $where['status'] = $param['status'];
        }

        if (isset($param['admin']) && in_array($param['admin'], [0, 1])) {
            $where['admin'] = $param['admin'];
        }

        $total = $this->medoo->count('user', 'id', $where);
        if ($total == 0) {
            return ['total' => 0, 'data' => []];
        }

        $where['LIMIT'] = [($page - 1) * $pageSize, $pageSize];
        $where['ORDER'] = ['id' => 'DESC'];

        $data = $this->medoo->select('user', ['id','username', 'password', 'head_url', 'status'], $where);

        return ['total' => $total, 'data' => $data];
    }

    /**
     * insert 的 实用 方法
     */
    public function createApi(\App\User $user)
    {
        $pdosmt = $this->medoo->insert('test', [
            'title' => '测试1',
            'json_content[JSON]' => [
                'username' => 'user1',
                'password' => '123'
            ],
            'obj_content' => $user
        ]);

        if ($pdosmt->rowCount() > 0) {
            return ['data' => true];
        }

        return ['msg' => '插入失败'];
    }

    public function editApi(\App\User $user)
    {
        $pdosmt = $this->medoo->update('test', [
            'title' => '测试2',
            'json_content[JSON]' => [
                'username' => 'user345',
                'password' => '7890'
            ],
            'obj_content' => $user,
            'uid' => $this->medoo->raw("UUID()"),
            'num[+]' => 1,
        ], [
            'id' => 4
        ]);

        if ($pdosmt->rowCount() > 0) {
            return ['data' => true];
        }

        return ['msg' => '修改失败'];
    }

    public function get2Api()
    {
        $data = $this->medoo->get('test', '*', ['ORDER' => ['id' => 'DESC']]);

        // $data['json_content'] = json_decode($data['json_content']);

        $data['obj_content'] = unserialize($data['obj_content']);

        echo '<pre>';
        print_r($data);
        echo '</pre>';

        // return ['data' => $data];
    }

    /**
     * select 实用方法
     */
    public function select2Api()
    {
        $data = $this->medoo->select('order(o)', [
            '[>]user(u)' => ['o.user_id' => 'id']
        ], [
            'o.id','u.username', 'o.code', 'o.contact[JSON]', 'o.order_goods[JSON]',
            'affirm' => $this->medoo->raw("IF(<o.affirm> = 1, '已确认', '未确认')"),
            'time' => $this->medoo->raw('FROM_UNIXTIME(<o.time>)'),
            'o.is_pay', 'o.status'
        ], [
            'o.is_delete' => 0,
        ]);

        return ['data' => $data];
    }

    public function actionApi()
    {
        $param = [
            'username' => 'user1234567',
            'password' => '12345678'
        ];

        $result = $this->medoo->action(function () use ($param) {
            $pdosmt = $this->medoo->update('test', [
                'title' => '测试3',
                'json_content[JSON]' => $param,
                'uid' => $this->medoo->raw("UUID()"),
                'num[+]' => 2,
            ], [
                'id' => 4
            ]);

            if ($pdosmt->rowCount() < 1) {
                return false;
            }

            $pdosmt = $this->medoo->insert('user', $param);

            if ($pdosmt->rowCount() < 1) {
                return false;
            }

            return true;
        });

        if ($result) {
            return ['data' => true];
        }

        return ['msg' => '系统错误'];
    }

    public function loginApi(Request $request)
    {
        $username = $request->post('username');
        $password = $request->post('password');

        $user = UserModel::where(['username' => $username, 'password' => md5($password)])->first();
        if (!$user) {
            return ['msg' => '该用户不存在'];
        }

        $user->tokens()->delete();

        $token = $user->createToken('login-token')->plainTextToken;

        $minutes = config('sanctum.expiration');

        Cache::put($token, $user, $minutes * 60);

        return ['data' => ['userinfo' => $user, 'token' => $token]];
    }

    public function userInfoApi()
    {
        return $this->s_user;
    }

    public function httpLoginApi()
    {
        return Http::post('http://api.myvue.com/admin/common/login', [
            'username' => 'root',
            'password' => '123',
        ])->json();
    }

    public function httpAdvertApi()
    {
        return Http::post('http://api.myvue.com/admin/advert/list', [
            'token' => 'cdd658e08d99168be5adfaa10219fd8d',
            'page' => 1,
        ])->json();
    }

    public function httpUploadApi()
    {
        return Http::attach(
            'file',
            file_get_contents('http://uploads.myvue.com/20200606/0728c707f25cb4c55bc7c80eb404a144.jpg'),
            '0728c707f25cb4c55bc7c80eb404a144.jpg'
        )->attach('token', 'cdd658e08d99168be5adfaa10219fd8d')->post('http://api.myvue.com/admin/system/upload')->json();
    }

    public function httpAdvertInsertApi()
    {
        return Http::post('http://api.myvue.com/admin/advert/insert', [
            'token' => 'cdd658e08d99168be5adfaa10219fd8d',
            'title' => '测试11',
            'url' => 'http://baidu.com',
            'pos' => 30,
            'img' => "http://uploads.myvue.com/20200606\\f5a47c2b49c519dc130bd44dd9db489c.jpg",
        ])->json();
    }

    public function httpAdvertInfoApi()
    {
        return Http::post('http://api.myvue.com/admin/advert/info', [
            'token' => 'cdd658e08d99168be5adfaa10219fd8d',
            'id' => 38
        ])->json();
    }

    public function httpAdvertDelApi()
    {
        return Http::post('http://api.myvue.com/admin/advert/delete', [
            'token' => 'cdd658e08d99168be5adfaa10219fd8d',
            'id' => 38
        ])->json();
    }

    public function httpAdvertUpdateApi()
    {
        return Http::post('http://api.myvue.com/admin/advert/update', [
            'token' => 'cdd658e08d99168be5adfaa10219fd8d',
            'id' => 38,
            'title' => '测试12',
            'url' => 'http://www.yzmedu.com',
            'pos' => 31,
            'img' => "http://uploads.myvue.com/20200606\\f5a47c2b49c519dc130bd44dd9db489c.jpg",
        ])->json();
    }

    public function formValidateApi(FormRequest $request)
    {
        $param = $request->post();

        return [200, $param];
    }
}
