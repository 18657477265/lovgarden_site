<?php
namespace Home\Model;
use Think\Model;
use Org\Util\CustomAliPay;
class UserModel extends Model 
{
    //调用时候create方法允许接受的字段
    protected $insertFields = 'user_password,open_id,user_telephone,user_active_code,repassword,user_status';
    protected $updateFields = 'user_name,user_email,user_password,open_id,user_logo,user_telephone,user_status,user_active_code,repassword,user_email_verify_code';
    protected $_validate = array(
        array('user_telephone','/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\\d{8}$/','手机号码格式不正确',0),
        array('user_telephone','require','手机不能为空',1),
        array('user_telephone', '', '该手机号已经存在', 1, 'unique', 1),
        array('user_password','require','密码不能为空',1,'regex',1),
        array('user_password','check_password','密码由数字和字母组成并且是6-12位',1,'callback',3),
        array('repassword','user_password','确认密码必须和密码一致',1,'confirm'),
        array('user_active_code', 'check_verify_code', '验证码不正确！', 1, 'callback'),
    );
    
    function check_password($user_password) {
        //利用I函数接收对应字段的参数
        //$user_password = I('user_password');
        if(preg_match('/^[0-9A-Za-z]{6,12}$/',$user_password) || empty($user_password)) {
            return TRUE;
        } 
        else {
            return FALSE;
        }
    }
    
    // 为登录的表单定义一个验证规则 
    public $_login_validate = array(
	array('user_telephone', 'require', '手机号不能为空！', 1),
	array('user_password', 'require', '密码不能为空！', 1),
        array('user_telephone','/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\\d{8}$/','手机号码格式不正确',0),
        array('user_password','check_password','密码由数字和字母组成并且是6-12位',1,'callback',3),
    );
    //为忘记密码的表单定义一个验证规则
    public $_reset_password_validate = array(
        array('user_telephone', 'require', '手机号不能为空！', 1),
	array('user_password', 'require', '密码不能为空！', 1),
        array('repassword','user_password','确认密码必须和密码一致',1,'confirm'),
        array('user_password','check_password','密码由数字和字母组成并且是6-12位',1,'callback',3),
        array('user_active_code', 'check_verify_code', '验证码不正确！', 1, 'callback'),
    );
    // 验证验证码是否正确(注册)
    function check_verify_code($user_active_code = ''){
            //session('verify_message_code','2222');
	    //$right_code = session('verify_message_code');
            //通过memcache取得这个号码的验证码
            $telephone = I('post.telephone');
            $mem = new \Think\Cache\Driver\Memcache();
            $right_code = $mem->get($telephone); 
            if($user_active_code === $right_code) {
	       return TRUE;
            }
            else {
                return FALSE;
            }
    }
    //验证是否擅自改了手机号码(忘记密码页面)
    function check_verify_telephone($user_telephone){
            $right_user_telephone = session('user_telephone');
            if(!empty($right_user_telephone)) {
               if($right_user_telephone === $user_telephone){
                   return true;
               }
            }
            return FALSE;
    }
    
    public function login(){
        // 从模型中获取用户名和密码
	$usertelephone = $this->user_telephone;
        $password = $this->user_password;
        $user = $this->where(array(
		'user_telephone' => array('eq', $usertelephone),
		))->find();
        if($user) {
	    if($user['user_password'] == md5($password)) {
                if($user['user_status'] == '1') {
                    // 登录成功存session
                    session('custom_id', $user['user_id']);
                    session('user_telephone', $user['user_telephone']);
                    return TRUE;
                }
                else {
                    $this->error = '该账号已冻结，请稍后再试';
                    return FALSE;
                }
	    }
	    else {
		$this->error = '密码或者账号不正确！';
		return FALSE;
	    }
	}
        else {
            $this->error = '密码或者账号不正确！';
            return FALSE;
        }
    }
    
    public function logout() {
	session(null);
    }
    
    //给密码加密 通过hook函数
    protected function _before_insert(&$data, $option){
	$data['user_password'] = md5($data['user_password']);
        $data['repassword'] = md5($data['repassword']);
    }
    //给密码加密通过hook函数
    protected function _before_update(&$data, $option){
        if(empty($data['user_password'])) {
            unset($data['user_password']);
            unset($data['repassword']);
        }
        else {
            $data['user_password'] = md5($data['user_password']);
            $data['repassword'] = md5($data['repassword']);
        }
    }
    
    //用户调用支付接口进行支付
    function  user_init_order_pay($order_id) {
        //1登录用户
        //2 订单号要和系统中已有的未付款的订单号吻合，不然不允许调用付款接口
        $user_id = session('custom_id');
        $user_telephone = session('user_telephone');
        if(empty($user_id) || empty($user_telephone)) {
            echo "Access Denied";
            exit();
        }
        else {
            $order = D('Order');
            $orders = $order->field('order_id,order_owner,order_final_price')->where(array(
                'order_owner' => $user_telephone,
                'order_status' => '1',
            ))->select();
            if(!empty($orders)){
               $orders = translate_database_result_to_logic_array($orders,array(),'order_id');
               $order_ids = array_keys($orders);
               if(in_array($order_id, $order_ids)){
                   //这里的话才可以正式发起请求                  
                  $this_order = $orders[$order_id];
                  $shop_subject = C('PAY_SHOP');
                 
                  $shop_body = C('PAY_PRODUCT');
                  $this_order['order_final_price']='0.01';
                  $alipay = new CustomAliPay($order_id,$shop_subject, $this_order['order_final_price'],$shop_body);
                  $alipay->lovgardenPagePay();
                  
                  //pay_codepay($order_id, $this_order['order_final_price']);
                  exit();
               }
            }
        }
    }
    
}












