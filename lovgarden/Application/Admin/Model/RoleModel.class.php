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
        
    public function permissions_add($role_id,$permissions) {
        foreach ($permissions as $key => $value) {
                //保存进表
                $array = array(
                    'role_id' => $role_id,
                    'permission_id' => $value,
                );
                $role_to_permission_model = D('RoleToPermission');
                $status = $role_to_permission_model->add($array);
                if(!$status) {
                    return FALSE;
                }
        }
        return TRUE;
    }
    
    public function permissions_delete($role_id,$permissions) {
       foreach ($permissions as $key => $value) {
                //保存进表
                $array = array(
                    'role_id' => $role_id,
                    'permission_id' => $value,
                );
                $role_to_permission_model = D('RoleToPermission');
                $status = $role_to_permission_model->where($array)->delete();
                if(!$status) {
                    return FALSE;
                }
        }
        return TRUE;
    }

    //通过交叉比对的方式更新角色权限数据，类似商品varient里的多选的属性数据
    public function add_permissions($new_permission_ids=array(),$role_id){
        //先查出原来$product_varient_id对应的flower_occasion_id保存到一个数组里
        $add_status = TRUE;
        $delete_status = TRUE;
        $sql = "SELECT permission_id FROM lovgarden_role_to_permission WHERE role_id = '$role_id'";
        $old_permission_ids = $this->query($sql);
        //整理组织查出来的数据，便于比对
        $old_permission_ids_fix = array(); 
        foreach($old_permission_ids as $key => $value) {
            $old_permission_ids_fix[] = $value['permission_id'];
        }
        //交叉比对这次提交上来的数据和老的数据
        //1 循环新提交上来的数据，如果不在老的数据里，说明是要新加的
        //2 循环老的数据，如果不在新的数据里，说明要删除
        $array_add_ones = array();
        $array_delete_ones = array();
        foreach($new_permission_ids as $key => $value) {
            if(!in_array($value, $old_permission_ids_fix)) {
                //说明是要新增的
                $array_add_ones[] = $value;
            }
        }
        foreach($old_permission_ids_fix as $key => $value) {
            if(!in_array($value, $new_permission_ids)) {
                //说明是要删除的
                $array_delete_ones[] = $value;
            }
        }     
        if(!empty($array_add_ones)) {
            $add_status = $this->permissions_add($role_id, $array_add_ones);
        }
        if(!empty($array_delete_ones)) {
            $delete_status = $this->permissions_delete($role_id,$array_delete_ones);
        }
        if($add_status && $delete_status) {
            //表示数据更新成功
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
    
}