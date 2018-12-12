<?php
/**
 * Created by PhpStorm.
 * User: fugui
 * Date: 2018/9/17
 * Time: 10:48
 */

namespace app\api\controller;

use think\Controller;
use think\Request;

class Test extends Controller
{
    protected $request;
    public function apiTest()
    {
        $this->request = Request::instance();
        $data = [
            'url'=>$this->request->url(true)
        ];

        return json($data);

    }
}