<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiAfter
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        if (!($response instanceof JsonResponse)) {
            return $response;
        }

        $data = $response->getData();

        if (!is_array($data)) {
            return $response;
        }

        if (!isset($data[0]) || !is_int($data[0]) || !isset($data[1])) {
            return $response->setData([
                'ret' => 500,
                'data' => [],
                'msg' => '数据格式错误'
            ]);
        }

        if ($data[0] === 200) {
            return $response->setData([
                'ret' => 200,
                'data' => $data[1],
                'msg' => ''
            ]);
        }

        return $response->setData([
            'ret' => $data[0],
            'data' => [],
            'msg' => $data[1]
        ]);
    }
}
