<?php
namespace Admin\Model;
use Think\Model;
class PermissionModel extends Model 
{
	protected $insertFields = array('permission_name','permission_description','module_name','controller_name','action_name','url');
	protected $updateFields = array('permission_name','permission_description','module_name','controller_name','action_name','url');
	protected $_validate = array(
		array('permission_name', 'require', '权限名称不能为空！', 1, 'regex', 3),
		array('permission_name', '1,30', '权限名称的值最长不能超过 30 个字符！', 1, 'length', 3),
                array('permission_name', '', '权限名称已经存在！', 1, 'unique', 1),
                array('permission_description', 'require', '权限说明不能为空！', 1, 'regex', 3),
		array('url', 'require', '授权行为不能为空', 1, 'regex', 3),
                array('url', '/^\/[A-Za-z0-9_-]+\/[A-Za-z0-9_-]+\/[A-Za-z0-9_-]+$/', '输入类似/abs/abs/abs', 1, 'regex', 3),
                array('url', '', '授权行为已经存在！', 1, 'unique', 1),
                array('module_name', '1,30', '模块名称的值最长不能超过 30 个字符！', 2, 'length', 3),
		array('controller_name', '1,30', '控制器名称的值最长不能超过 30 个字符！', 2, 'length', 3),
		array('action_name', '1,30', '方法名称的值最长不能超过 30 个字符！', 2, 'length', 3),
	);
	
	/**
	 * 检查当前管理员是否有权限访问这个页面
	 */
	public function check_user_permission() {
                //匿名用户肯定没有权限                
                //return TRUE; //debug用 暂时把权限都打开了                
                if(empty(session('id'))){
                    return FALSE;
                }
		// 获取当前管理员正要访问的模型名称、控制器名称、方法名称
		// tP中正带三个常量
		//MODULE_NAME , CONTROLLER_NAME , ACTION_NAME
		$adminId = session('id');
		// 如果是超级管理员直接返回 TRUE
		if($adminId == 1) {
			return TRUE;
                }
                $model = D('User');
                $sql = "SELECT a.user_id,a.user_name,d.`url` FROM lovgarden_user AS a 
                        LEFT JOIN lovgarden_user_to_role AS b ON a.`user_id`=b.`user_id`
                        LEFT JOIN lovgarden_role_to_permission AS c ON b.`role_id`=c.`role_id`
                        LEFT JOIN lovgarden_permission AS d ON c.`permission_id`= d.`id` WHERE a.`user_id`= '$adminId'";
                $permission_list = $model->query($sql);
                if(!empty($permission_list)) {
                    $user_permission_info = translate_database_result_to_logic_array($permission_list,array('url'),'user_id');               
                    $user_permission_info = $user_permission_info[$adminId];
                    $current_uri = '/'.MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME;
                    if(in_array($current_uri, $user_permission_info['url'])) {
                        return TRUE;
                    }
                    
                }
                return FALSE;
	}
        
        //用于编辑时候验证权限名称输入的唯一性
        public function is_unique_permission_name($input,$self_id) {
            $sql = "select id from lovgarden_permission where permission_name = '$input'";
            $row = $this->query($sql);
            if($row){
                if($row['0']['id'] == $self_id) {
                    return TRUE;
                }
                else {
                    $this->error = "权限的名称已经存在";
                    return FALSE;
                }
            }
            else {
                return TRUE;
            }
        }
        
        //用于编辑时候验证授权行为输入的唯一性
        public function is_unique_url($input,$self_id) {
            $sql = "select id from lovgarden_permission where url = '$input'";
            $row = $this->query($sql);
            if($row){
                if($row['0']['id'] == $self_id) {
                    return TRUE;
                }
                else {
                    $this->error = "该授权行为已经存在";
                    return FALSE;
                }
            }
            else {
                return TRUE;
            }
        }
}