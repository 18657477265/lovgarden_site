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
	public function check_user_permission()
	{
                //匿名用户肯定没有权限
                if(empty(session('id'))){
                    return FALSE;
                }
		// 获取当前管理员正要访问的模型名称、控制器名称、方法名称
		// tP中正带三个常量
		//MODULE_NAME , CONTROLLER_NAME , ACTION_NAME
		$adminId = session('id');
		// 如果是超级管理员直接返回 TRUE
		if($adminId == 1)
			return TRUE;
		$arModel = D('admin_role');
		$has = $arModel->alias('a')
		->join('LEFT JOIN __ROLE_PRI__ b ON a.role_id=b.role_id 
		        LEFT JOIN __PRIVILEGE__ c ON b.pri_id=c.id')
		->where(array(
			'a.admin_id' => array('eq', $adminId),
			'c.module_name' => array('eq', MODULE_NAME),
			'c.controller_name' => array('eq', CONTROLLER_NAME),
			'c.action_name' => array('eq', ACTION_NAME),
		))->count();
		return ($has > 0);
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