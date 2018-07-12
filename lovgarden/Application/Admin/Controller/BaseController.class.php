<?php
namespace Admin\Controller;
use Think\Controller;
class BaseController extends Controller {
    public function __construct() {
	// 必须先调用父类的构造函数
	parent::__construct();
        //无权限控制url
        $open_url = array(
            '/Admin/User/login',
            '/Admin/User/chkcode',
            '/Admin/User/logout'
        );
        $current_uri = '/'.MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
	if(in_array($current_uri, $open_url)) {
		return TRUE;
        }
        
        //进入有权限控制操作
	$permission = D('Permission');
	if(!$permission->check_user_permission()) {
	  $this->redirect('Success/access_denied');
        }
        else {
            return TRUE;
        }
    }
}