<?php
namespace app\index\controller;

use think\Controller;
use think\Session;
use app\weixin\exClass\weixin\WeAuthorize;//授权服务类
use app\index\model\UserInfo;//用户模型
use service\XmlArray;

class Pay extends controller
{
  //支付向导
  public function toPay()
  {
    //先判断用户是否登陆
    if(Session::has('user')){  //登录直接开始付款逻辑
      //获取用户登录信息
      $user = Session::get('user');
      $openid = $user['openid'];
      //先判断用户是否绑定
      $user_bind = UserInfo::get(['openid'=>$openid]);
      if($user_bind!=null)//用户绑定
      {
        //开始付款逻辑
        $userPay = \think\Loader::model('User','service');
        $jsParam = $userPay->pay($openid);
        if($jsParam==null){
          return view('noPay');
        }else{
          return view('pay',['jsParam'=>$jsParam]);
        }
      }else{//用户没绑定
        return view('toBind');
      }

    }else{//未登录开始授权登录
      //组装回调地址
      $urlAuth = new WeAuthorize(APPID,APPSECRET);
      $url = 'http://www.kangquanpay.top/prepay';
      $backUrl = $urlAuth->get_authorize_url($url);
      //重定向到回调地址(即到prePay方法)
      $this->redirect($backUrl);
    }

  }

  //支付首页
  public function prePay1()
  {
    //网页授权（登录）
    $userAuth = \think\Loader::model('GetUserView','service');
    $userAuth->getInfoLogin();

    //开始付款逻辑
    //获取用户登录信息
    $user = Session::get('user');
    $openid = $user['openid'];
    //先判断用户是否绑定
    $user_bind = UserInfo::get(['openid'=>$openid]);
    if($user_bind!=null)//用户绑定
    {
      //开始付款逻辑
      $userPay = \think\Loader::model('User','logic');
      $jsParam = $userPay->pay($openid);
      if($jsParam==null){
        return view('noPay');
      }else{
        return view('pay',['jsParam'=>$jsParam]);
      }
    }else{//用户没绑定
      return view('toBind');
    }

  }

  //支付回调处理
  public function payBack2()
  {

  //获取数据
  //$xmlData = file_get_contents('php://input');
  //调式处理

  file_put_contents('payTest2.txt','a',FILE_APPEND);
  //返回成功信息
  $backInfo = [
    'return_code'=>'SUCCESS',
    'return_msg'=>'OK'
  ];
  $xml = "
<xml>
  <return_code><![CDATA[SUCCESS]]></return_code>
  <return_msg><![CDATA[OK]]></return_msg>
</xml>
";
$xml = str_replace(' ','',$xml);
  //  Header("Content-type:xml/text;charset=utf-8");
return ($xml);
  //处理逻辑
  //$userPay = \think\Loader::model('User','logic');
  }

public function payBack()
{
  echo 'SUCCESS';
  //获取数据
  $xmlData = file_get_contents('php://input');
  $arrData = XmlArray::XmlToArr($xmlData);
  $user = \think\Loader::model('User','logic');
  $res = $user->updateStatus($arrData);
  if($res == 'success')
  {
    Header("Content-type:text/xml;charset=utf-8");
    //file_put_contents('pay.txt','a',FILE_APPEND);
    $arrData = [
      'return_code'=>'SUCCESS',
      'return_msg'=>'OK'
    ];
    $backData = XmlArray::ArrToXml($arrData);
    return  $backData;

  }
}

}

 ?>
