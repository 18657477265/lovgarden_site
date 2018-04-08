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
            if(mem_check_ip_attention()) {
                if($user_model->validate($user_model->_login_validate)->create($user_info)) {
                    if($user_model->login()) {
                       $this->redirect("User/operation_success/status/2");
                       exit();
                    }
                }                
   	        $error_message = $user_model->getError();
                $this->assign(array(
                   'error_message' => $error_message,
                   'user_telephone' => $user_info['user_telephone']
                ));
            }
            else {
               $this->assign(array(
                   'error_message' => '由于您失败太多次或者频繁登录,出于安全考虑系统暂时将您冻结',
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
         //如果是已经登录用户，跳转到首页去
        if(!empty(session('custom_id'))) {
            $this->redirect('/');
        }
         $this->display('register');
    }
    
    public function operation_success($status='') {
        $this->assign(array(
            'status' => $status,
        ));
        $this->display('success');
    } 
    //发送短信验证码接口
    public function send_ali_message_code() {
        //将验证码保存在session中，其实最好得方式是保存在memcache中，之后得改
        $telephone= I('post.send_telephone');       
        if(!empty($telephone) && preg_match('/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\\d{8}$/',$telephone)) {
            //检查是否过于频繁发送，一个ip地址一小时内最多发送5次            
            if(mem_check_ip_attention()) {
                //生成随机验证码
                $param = str_pad(mt_rand(0, 999999), 6, "0", STR_PAD_BOTH);              
                set_time_limit(0);          
                $response = SendCustomCode::sendSms($telephone,$param);
                //将数据存入memcache,并设置5分钟后过期
                //S($telephone,$param,300);
                $mem_new = new Memcache();
                $mem_new->set($telephone, $param, 300);
            }
            else {
              echo '5';
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
          $name = '14'.'order_cart_info';
          $value1 = $mem2->get($name);
          
          //$value2 = $mem2->get($a);
          echo $value1;
          exit();
          $value3 = $mem2->set($a, 1, 7200);
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
            if(IS_POST) {
               $error_message_info = '';
               $user_model = D('User');
               $user_info = array(
                   'user_telephone' => I('post.telephone',''),
                   'user_password' => I('post.password',''),
                   'repassword' => I('post.repassword',''),
                   'user_active_code' => I('post.auth_code',''),
                   'user_status' => '1'
                );
                $data_status = $user_model->validate($user_model->_reset_password_validate)->create($user_info);             
                if($data_status) {
                    //通过验证，开始更新用户密码
                    $user = $user_model->where(array(
                               'user_telephone' => array('EQ',$user_info['user_telephone']),
                            ))->find();
                    if($user) {
                        $user_id = $user['user_id'];
                        $user_save_status = $user_model->where(array('user_id' => $user_id))->save($data_status);
                        if($user_save_status !== FALSE) {
                            $this->redirect('User/operation_success/status/3');
                        }
                        else {                             
                            $error_message_info = '密码修改失败,请稍后再试';                       
                        }
                    }else {
                        $error_message_info = '该用户不存在,请注册';   
                    }        
                }                
                $error_message = $error_message_info . $user_model->getError();
                $this->assign(array(
                  'error_message' => $error_message,
                ));
             }
            $this->display('reset_password');   
    }
    
    //获取用户购物车信息
    public function get_cart_info() {
        $user_id = session('custom_id');
        if(!empty($user_id)){
            $mem_cart_info = new memcache();
            $name = $user_id.'cart_info';
            $cache_cart_info = $mem_cart_info->get($name);
            $results = '';
            if($cache_cart_info) {
                $results = unserialize($cache_cart_info);
            }
            else {
            //这里得加入缓存，避免数据库不必要的查询
            //注意缓存得再 用户加入购物车指向时候更新 再删除购物车条目时候页更新
                $sql = "SELECT a.varient_id, b.varient_name,b.varient_price,COUNT(*) AS number FROM lovgarden_cart AS a
    LEFT JOIN lovgarden_product_varient AS b ON a.varient_id = b.sku_id WHERE a.user_id = '$user_id' GROUP BY b.id;";
                $model = new \Think\Model();
                $results = $model->query($sql);
                $mem_cart_info->set($name, serialize($results),86400);
            }            
            if(!empty($results)){
               echo json_encode($results);
               exit();
            }else {
                echo '0';
                exit();
            }                
        }
        //表示输出空购物车的状态码
        echo '0';
    }
    
    //用户中心
    public function usercenter() {
        $user_account = session('user_telephone');
        if(!empty($user_account)) {
            $order_model = D('Order');
            $filter_selection = array(); 
            $where = array(array(
                'orders.order_owner' => $user_account,
            ));
            if(!empty(I('get.order_status'))) {
                $where['orders.order_status'] = I('get.order_status');
                $filter_selection['order_status'] = I('get.order_status');
            }
            if(!empty(I('get.order-id-filter'))) {
                $where['orders.order_id'] = I('get.order-id-filter');
                $filter_selection['order_id'] = I('get.order-id-filter');
            }
            $order_sort = array(
                'orders.order_create_time'=>'desc'
            );
            if(!empty(I('get.order_order_'))) {
                if(I('get.order_order_') == '1') {
                    $order_sort['orders.order_create_time'] = 'desc';
                }
                elseif(I('get.order_order_') == '2') {
                    $order_sort['orders.order_create_time'] = 'asc';
                }
                $filter_selection['order_order_'] = I('get.order_order_');
            }
            $orders = $order_model->alias('orders')
                        ->join('LEFT JOIN lovgarden_order_product_varient AS order_product ON orders.`id` = order_product.`order_original_id`')
                        ->join('LEFT JOIN lovgarden_product_varient AS products ON order_product.`product_sku_id` = products.`sku_id`')
                        ->join('LEFT JOIN lovgarden_product_varient_images AS images ON products.`id`=images.`product_varient_id`')
                        ->field('orders.`id`,orders.`order_id`,orders.`area`,orders.`address`,orders.`telephone`,orders.`order_create_time`,orders.`order_final_price`, orders.order_coupon_code,orders.order_coupon_cut,orders.order_status, order_product.`product_sku_id`,products.varient_name,images.`image_url`')
                        ->where($where)
                        ->order($order_sort)
                        ->select();
            
            if(!empty($orders)) {
                $orders_fix = translate_database_result_to_logic_array($orders, array('product_sku_id','image_url','varient_name'), 'id');
               
                $this->assign(array(
                    'orders' => $orders_fix,
                    'filter_selection' => $filter_selection
                ));// 赋值数据集
                $this->display('user_center'); 
            }else {
                //跑大盘订单为空的页面中去
                echo '订单为空';
                exit();
            }
        }
        else {
            $this->redirect('user/login');
        }
    }
    //用户订单详情
    public function user_order_detail(){
        $this->display('user_order_detail');
    }
}