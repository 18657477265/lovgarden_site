<?php
namespace Api\Controller;
use Think\Controller\RestController;
use Think\Cache\Driver\Memcache;
class LoginController extends RestController {
   public function miniProgramlogin() {
       $appid = 'wxd7561da4052911c3';
       $secret = '25506f061e1ae1b137fa37a15809639d';
       $code=$_GET['code'];     //微擎获取前台上传的code值
       $url = 'https://api.weixin.qq.com/sns/jscode2session?appid='.$appid.'&secret='.$secret.'&js_code='.$code.'&grant_type=authorization_code';
       $info = file_get_contents($url);//get请求网址，获取数据
       $json = json_decode($info);//对json数据解码
       $arr = get_object_vars($json);//返回一个数组。获取$json对象中的属性，组成一个数组
       $openid = $arr['openid'];
       $session_key = $arr['session_key'];
       //echo json_encode($arr);
       echo json_encode(array(
            'loginInfo' => $arr
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
       //return $this->result(0, 'success', $session_key);//返回给前台一个sess
   }  
}
