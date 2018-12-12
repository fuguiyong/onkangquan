<?php
/**
 * Created by PhpStorm.
 * User: fugui
 * Date: 2018/12/12
 * Time: 9:33
 */

namespace app\vueApi\controller;

use think\Controller;

class Base extends Controller
{

    //返回信息函数
    public function return_msg($errcode, $errmsg = '', $data = null)
    {
        $backInfo = [
            'errcode' => $errcode,
            'errmsg' => $errmsg,
            'data' => $data
        ];

        echo json_encode($backInfo);
        die;
    }

}