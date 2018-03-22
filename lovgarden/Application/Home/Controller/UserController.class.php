<?php
namespace Home\Controller;
use Think\Controller;
use Think\Cache\Driver\Memcache;
use Org\Util\SendCustomCode;
class UserController extends Controller {
    public function login(){
        if(IS_POST) {            
   	    $user_model = D('User');
   	    // 接收表单并且验证表单
            
            $user_info = array(
               'user_telephone' => I('post.login_telephone',''),
               'user_password' => I('post.login_password',''),
            );
            //首先要检查这个登录是不是正常用户登录，如果登入失败10次封掉IP 1小时
            $client_ip = getClientIp();
            $mem = new Memcache();
            $ip_send_count = $mem->get($client_ip);
            if(empty($ip_send_count) || $ip_send_count <10) {
                if($user_model->validate($user_model->_login_validate)->create($user_info)) {
                    if($user_model->login()) {
                       $this->redirect("/");
                       exit();
                    }
                }
                
                if(!empty($ip_send_count)) {
                  //一小时内不是第一次发送
                  $ip_send_count ++;
                  $mem->set($client_ip, $ip_send_count, 21600);
                }
                else {
                  //第一次发送
                  $mem->set($client_ip,1,21600); 
                }
                
   	        $error_message = $user_model->getError();
                $this->assign(array(
                   'error_message' => $error_message,
                   'user_telephone' => $user_info['user_telephone']
                ));
            }
            else {
               $this->assign(array(
                   'error_message' => '由于您失败太多次,出于安全考虑系统暂时将您冻结',
                   'user_telephone' => $user_info['user_telephone']
               )); 
            }
   	}
        //如果是已经登录用户，跳转到首页去
        if(!empty(session('custom_id'))) {
            $this->redirect('/');
        }
        $this->display('login');
    }
    
    public function register() {
        if(IS_POST) {
           $user_model = D('User');
           $user_info = array(
               'user_telephone' => I('post.telephone',''),
               'user_password' => I('post.password',''),
               'repassword' => I('post.repassword',''),
               'user_active_code' => I('post.auth_code',''),
               'user_status' => '1'
            );
          
            $data_status = $user_model->create($user_info);
            if($data_status) {
               $add_status = $user_model->add($data_status);
               if($add_status) {
                   //注册成功，跳转到成功页面提示后再跳转到登入页面去
                   $this->redirect('User/operation_success/status/1');
               }
            }
            $error_message = $user_model->getError();
            $this->assign(array(
              'error_message' => $error_message,
            ));
         }
         $this->display('register');
    }
    
    public function operation_success($status='') {
        $this->assign(array(
            'status' => $status,
        ));
        $this->display('success');
    } 
    
    public function send_ali_message_code() {
        //将验证码保存在session中，其实最好得方式是保存在memcache中，之后得改
        $telephone= I('post.send_telephone');       
        if(!empty($telephone)) {
            //检查是否过于频繁发送，一个ip地址一小时内最多发送5次
            $client_ip = getClientIp();
            $mem = new Memcache();
            $ip_send_count = $mem->get($client_ip);
            if(empty($ip_send_count) || $ip_send_count <10) {
                //生成随机验证码
                $param = str_pad(mt_rand(0, 999999), 6, "0", STR_PAD_BOTH);              
                set_time_limit(0);          
                $response = SendCustomCode::sendSms($telephone,$param);
                //将数据存入memcache,并设置5分钟后过期
                //S($telephone,$param,300);
                $mem->set($telephone, $param, 300);
                if(!empty($ip_send_count)) {
                  //一小时内不是第一次发送
                  $ip_send_count ++;
                  $mem->set($client_ip, $ip_send_count, 21600);
                }
                else {
                  //第一次发送
                  $mem->set($client_ip,1,21600); 
                }
            }
            else {
              echo '3';
            }
        }
        else {
            echo "3";
        }
    }
    //测试memcache取值用
    public function get_cache_code() {
        $a = getClientIp();
        //echo $a;
        //exit();
       
      
          $mem2 = new Memcache();
          $value = $mem2->get($a);
          echo $value;
          exit();
          $value2 = $mem2->set($a, 1, 3600);
          echo '------';
          echo $value2;
          exit();
    }
    
    //接收来自Ajax的检查用户是否登录状态的请求，用于缓存页面的局部不缓存
    public function get_user_status() {
      if(!empty(session('custom_id'))){
          echo '1';//表示用户处于登录状态
      }else {
          echo '2';//表示用户是非登录状态
      }
    } 
    
    public function logout() {
        $user_model = D('User');
        $user_model->logout();
        $this->redirect('User/login');
    }
    
    public function forgetPassword() {
        $this->display('reset_password');
    }
}