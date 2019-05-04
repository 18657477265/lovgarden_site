<?php
namespace Api\Controller;
use Think\Controller\RestController;
use Think\Cache\Driver\Memcache;
use Org\Util\SendCustomCode;
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
       //根据openID创建一个微信用户
       //将session_key保存到缓存中,以便小程序检查登录状态
       $login_code = 'Login Error';
       $mem_cache = new Memcache();
       if(!empty($session_key) && !empty($openid)) {
           $login_code = md5($openid.$session_key);
           $mem_cache->set($login_code, $openid, 3600);
           $wx_user = D('Wxuser');      
           $wx_user->add_wxuser($openid);
       }
       echo json_encode(array(
            'loginInfo' => $login_code
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
       //return $this->result(0, 'success', $session_key);//返回给前台一个sess
   }
   public function checkMiniProgramLoginStatus($login_ip = '') {
       $login_status = 404;
       if(!empty($login_ip)){
           $mem_cache = new Memcache();
           $login_exist = $mem_cache->get($login_ip);
           if(!empty($login_exist)){
               $login_status = 200;
           }
       }
       echo json_encode(array(
              'loginStatus' => $login_status
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);       
   }
   public function miniProgramSendMessage($telephone = '',$login_code = '') {
       $error_code = 404;
       if(!empty($telephone) && !empty($login_code)){
         $mem_code = new Memcache();
         //确保是登录状态,否则不发短信
         $open_id = $mem_code->get($login_code);
         if(!empty($open_id)) {
            $param = str_pad(mt_rand(0, 999999), 6, "0", STR_PAD_BOTH);              
            set_time_limit(0);          
            $response = SendCustomCode::sendSms($telephone,$param);
            //将数据存入memcache,并设置30分钟后过期
            $mem_code->set($telephone, $param, 1800);
            $error_code = 200;
         }
         else {
            $error_code = 403;
         }
       }
       echo json_encode(array(
              'error_code' => $error_code
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);         
   }
   public function miniProgramVerifyCode($telephone = '',$login_code = '',$code = '') {
       $error_code = '404';
       $memory = new Memcache();
       if(!empty($telephone) && !empty($login_code) && !empty($code)) {
           $open_id = $memory->get($login_code);
           $mem_code = $memory->get($telephone);
           if(!empty($open_id) && $mem_code == $code) {
               //说明处于登录状态并且输入的验证码是正确的,将电话号码绑定到数据库中
               $wxuser = D("Wxuser");
               $result = $wxuser->update_wxuser($open_id,$telephone);
               if($result){
                   $error_code = 200;
                   //删除缓存数据
                   $memory->rm($telephone);
               }
           }
       }
       echo json_encode(array(
              'error_code' => $error_code
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);          
   }
   public function getUserInfo() {
       $login_ip = I('get.login_ip');
       $login_status = 404;
       $data = array();
       if(!empty($login_ip)) {
           $mem_cache = new Memcache();
           $login_exist = $mem_cache->get($login_ip);
           if(!empty($login_exist)){
               $open_id = $login_exist;
               $login_status = 200;
               $model = new \Think\Model();
               $sql = "SELECT telephone , reward_points FROM lovgarden_wxuser WHERE open_id = '$open_id'";
               $data = $model->query($sql);
           }
       }
       echo json_encode(array(
          'login_status'=> $login_status,
          'user_info'=> $data
      ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }
}
