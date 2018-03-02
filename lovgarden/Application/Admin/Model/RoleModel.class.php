<?php
namespace Admin\Model;
use Think\Model;
class RoleModel extends Model 
{
	protected $insertFields = array('role_name');
	protected $updateFields = array('role_name');
	protected $_validate = array(
		array('role_name', 'require', '角色名称不能为空！', 1, 'regex', 3),
		array('role_name', '1,20', '角色名称的值最长不能超过 20 个字符！', 1, 'length', 3),
		array('role_name', '', '角色名称已经存在！', 1, 'unique', 1),
	);
        //用于编辑时候验证输入的唯一性
        public function is_unique($input,$self_id) {
            $sql = "select id from lovgarden_role where role_name = '$input'";
            $row = $this->query($sql);
            if($row){
                if($row['0']['id'] == $self_id) {
                    return TRUE;
                }
                else {
                    $this->error = "输入的值已经存在";
                    return FALSE;
                }
            }
            else {
                return TRUE;
            }
        }
}