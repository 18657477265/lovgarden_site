<?php
namespace Admin\Controller;
use Think\Controller;
class RoleController extends BaseController {
    public function add(){
        if(IS_POST) {
          $errorMessage = '';
          $data = array(
              'role_name' => I('post.role_name','')   
          ); 
          $role_model = D('Role');
          $status_data = $role_model->create($data);
          if($status_data) {
              $add_status = $role_model->add($status_data);
              if($add_status) {
                  $this->redirect('Success/success');
              }
          }
          $errorMessage = $role_model->getError();
          $this->assign(array(
             'errorMessage' => $errorMessage,
             'data' => $data
          ));
          
        }
        $this->display('role_add');       
    }
    public function role_list() {
        $role_model = D("Role");
        $count = $role_model->count();
        $Page  = new \Think\Page($count,C('PRODUCT_VARIENT_PAGE')['page_count']);
        $show  = $Page->show();
        $roles = $role_model->field('id , role_name')->limit($Page->firstRow,$Page->listRows)->select();
        $this->assign(array(
            'roles' => $roles,
            'page' => $show,
        ));
        $this->display('role_list');
    }
    public function update($id) {
        $role_model = D('Role');
        $role_to_permission = D('RoleToPermission');
        $errorMessage = '';
        $data = array();
        $permissions = array();
        if(IS_POST) {
          $data = array(
            'id' => $id,
            'role_name' => I('post.role_name','')  
          );
          $permissions = I('post.permission_id','');                  
          $status = $role_model->create($data);
          $unique_status = $role_model->is_unique($data['role_name'],$id);
          if($status && $unique_status) {
              //这里是验证完成，需要保存到role表和role_to_permission表里，开启事务
              $role_model->startTrans();
              $save_status = $role_model->save($data);
              if($save_status !== FALSE) {
                  //这里保存成功role,开始角色权限绑定
                  if(!empty($permissions)) {
                      $permissions_update_status = $role_model->add_permissions($permissions,$id);
                      if(!$permissions_update_status) {
                          $role_model->rollback();
                          $this->redirect('Success/failure');
                          exit();
                      }
                  }
                  $role_model->commit();
                  $this->redirect('Success/success');
              }
          }
          $errorMessage = $role_model->getError();
        } 
        else {
            $data = $role_model->where("id = $id")->find();
            $permissions_found = $role_to_permission->field('permission_id')->where("role_id = $id")->select();
            foreach ($permissions_found as $key => $value) {
                $permissions[] = $value['permission_id'];
            }
        }
        
        $this->assign(array(
               'errorMessage' => $errorMessage,
               'data' => $data,
               'permissions' => $permissions
        ));
        $this->display('role_update');
        
    }
    
    public function ajax_role_delete() {
        if(IS_POST) {
           $row_id = $_POST['row_id'];
           $role_model = D('Role');
           $role_model->startTrans();
           $delete_status = $role_model->where(array(
               'id' => $row_id,
           ))->delete();
           if(delete_status) {
               //角色删除后需要一并删除绑定表中的信息
               $user_to_role = D('UserToRole');
               $role_to_permission = D('RoleToPermission');
               $status1 = $user_to_role->where(array(
                   'role_id' => $row_id
               ))->delete();
               $status2 = $role_to_permission->where(array(
                   'role_id' => $row_id
               ))->delete();
               if(status1!==FALSE && status2!==FALSE) {
                    $role_model->commit();
                    echo '1';//表示删除成功
               }
               else {
                   $role_model->rollback();
                   echo '2';
               }
           }
           else {
               echo '2';//表示删除失败
           }
        }
    }
}