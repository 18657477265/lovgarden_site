<?php
namespace Admin\Model;
use Think\Model;
class UserModel extends Model 
{
    //调用时候create方法允许接受的字段
    protected $insertFields = 'user_name,user_email,user_password,open_id,user_logo,user_telephone,user_active_code,repassword,user_email_verify_code';
    protected $updateFields = 'user_name,user_email,user_password,open_id,user_logo,user_telephone,user_status,user_active_code,repassword,user_email_verify_code';
    protected $_validate = array(
        array('user_name','/^[a-zA-Z0-9_-]{4,16}$/','用户名由4到16位字母或者数字组成',0),
        array('user_email','require','邮箱不能为空',1),
        array('user_email', '', '邮箱已经存在！', 1, 'unique', 1),
        array('user_email','/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/','邮箱格式不正确',1),
        array('user_password','require','密码不能为空',1,'regex',1),
        array('user_password','check_password','密码由数字和字母组成并且是6-12位',1,'callback',3),
        array('repassword','user_password','确认密码必须和密码一致',1,'confirm'),
        array('user_telephone','/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\\d{8}$/','手机号码格式不正确',0),
        array('user_status',array(0,1,2),'用户状态必填',0,'in'),
    );
    
    function check_password($user_password) {
        //利用I函数接收对应字段的参数
        $user_password = I('user_password');
        if(preg_match('/^[0-9A-Za-z]{6,12}$/',$user_password) || empty($user_password)) {
            return TRUE;
        } 
        else {
            return FALSE;
        }
    }
    
    // 为登录的表单定义一个验证规则 
    public $_login_validate = array(
	array('user_name', 'require', '用户名不能为空！', 1),
	array('user_password', 'require', '密码不能为空！', 1),
	array('user_active_code', 'require', '验证码不能为空！', 1),
	array('user_active_code', 'check_verify', '验证码不正确！', 1, 'callback'),
    );
    // 验证验证码是否正确
    function check_verify($code, $id = ''){
	    $verify = new \Think\Verify();
	    return $verify->check($code, $id);
    }
    
    public function login(){
        // 从模型中获取用户名和密码
	$username = $this->user_name;
        $password = $this->password;
        $user = $this->where(array(
		'username' => array('eq', $username),
		))->find();
        if($user) {
	    if($user['password'] == md5($password)) {
		// 登录成功存session
		session('id', $user['id']);
		session('username', $user['username']);
		return TRUE;
	    }
	    else {
		$this->error = '密码不正确！';
		return FALSE;
	    }
	}
    }
    
    public function logout() {
	session(null);
    }
    
    //用于编辑时候验证输入的唯一性
    public function is_unique($input_email,$self_id) {
            $sql = "select user_id from lovgarden_user where user_email = '$input_email'";
            $row = $this->query($sql);
            if($row){
                if($row['0']['user_id'] == $self_id) {
                    return TRUE;
                }
                else {
                    $this->error = "输入的邮箱已经存在";
                    return FALSE;
                }
            }
            else {
                return TRUE;
            }
    }
    
    public function roles_add($user_id,$roles) {
        foreach ($roles as $key => $value) {
                //保存进表
                $array = array(
                    'user_id' => $user_id,
                    'role_id' => $value,
                );
                $user_to_role_model = D('UserToRole');
                $status = $user_to_role_model->add($array);
                if(!$status) {
                    return FALSE;
                }
        }
        return TRUE;
    }
    
    public function roles_delete($user_id,$roles) {
       foreach ($roles as $key => $value) {
                //保存进表
                $array = array(
                    'user_id' => $user_id,
                    'role_id' => $value,
                );
                $user_to_role_model = D('UserToRole');
                $status = $user_to_role_model->where($array)->delete();
                if(!$status) {
                    return FALSE;
                }
        }
        return TRUE;
    }

        //通过交叉比对的方式更新角色数据，类似商品varient里的多选的属性数据
    public function add_roles($new_role_ids=array(),$user_id){
        //先查出原来$product_varient_id对应的flower_occasion_id保存到一个数组里
        $add_status = TRUE;
        $delete_status = TRUE;
        $sql = "SELECT role_id FROM lovgarden_user_to_role WHERE user_id = '$user_id'";
        $old_role_ids = $this->query($sql);
        //整理组织查出来的数据，便于比对
        $old_role_ids_fix = array(); 
        foreach($old_role_ids as $key => $value) {
            $old_role_ids_fix[] = $value['role_id'];
        }
        //交叉比对这次提交上来的数据和老的数据
        //1 循环新提交上来的数据，如果不在老的数据里，说明是要新加的
        //2 循环老的数据，如果不在新的数据里，说明要删除
        $array_add_ones = array();
        $array_delete_ones = array();
        foreach($new_role_ids as $key => $value) {
            if(!in_array($value, $old_role_ids_fix)) {
                //说明是要新增的flower_occasion_id
                $array_add_ones[] = $value;
            }
        }
        foreach($old_role_ids_fix as $key => $value) {
            if(!in_array($value, $new_role_ids)) {
                //说明是要删除的flower_occasion_id
                $array_delete_ones[] = $value;
            }
        }     
        if(!empty($array_add_ones)) {
            $add_status = $this->roles_add($user_id, $array_add_ones);
        }
        if(!empty($array_delete_ones)) {
            $delete_status = $this->roles_delete($user_id,$array_delete_ones);
        }
        if($add_status && $delete_status) {
            //表示数据更新成功
            return TRUE;
        }
        else {
            return FALSE;
        }
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
}












