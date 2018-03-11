<?php
namespace Admin\Controller;
use Think\Controller;
class PermissionController extends BaseController {
    public function add() {
        if(IS_POST) {
          $errorMessage = '';
          $data = array(
              'permission_name' => I('post.permission_name',''),
              'permission_description' => I('post.permission_description',''),  
              'module_name' => I('post.module_name',''),  
              'controller_name' => I('post.controller_name',''),  
              'action_name' => I('post.action_name',''),  
              'url' => I('post.url',''),  
          ); 
          $permission_model = D('Permission');
          $status_data = $permission_model->create($data);
          if($status_data) {
              $add_status = $permission_model->add($status_data);
              if($add_status) {
                  $this->redirect('Success/success');
              }
          }
          $errorMessage = $permission_model->getError();
          $this->assign(array(
             'errorMessage' => $errorMessage,
             'data' => $data
          ));
          
        }
        $this->display('permission_add');
    }
    
    public function permission_list() {
        $permission_model = D("Permission");
        $count = $permission_model->count();
        $Page  = new \Think\Page($count,C('PRODUCT_VARIENT_PAGE')['page_count']);
        $show  = $Page->show();
        $permissions = $permission_model->limit($Page->firstRow,$Page->listRows)->select();
        $this->assign(array(
            'permissions' => $permissions,
            'page' => $show,
        ));
        $this->display('permission_list');
    }
    
    public function update($id) {
        $permission_model = D('Permission');
        $errorMessage = '';
        $data = array();
        if(IS_POST) {
          $data = array(
              'id' => $id,
              'permission_name' => I('post.permission_name',''),
              'permission_description' => I('post.permission_description',''),  
              'module_name' => I('post.module_name',''),  
              'controller_name' => I('post.controller_name',''),  
              'action_name' => I('post.action_name',''),  
              'url' => I('post.url',''),  
          );        
          $status = $permission_model->create($data);
          $unique_status_url = $permission_model->is_unique_url($data['url'],$id);
          $unique_status_permission_name = $permission_model->is_unique_permission_name($data['permission_name'],$id);
          if($status && $unique_status_url && $unique_status_permission_name) {
              $save_status = $permission_model->save($data);
              if($save_status !== FALSE) {
                  $this->redirect('Success/success');
              }
          }
          $errorMessage = $permission_model->getError();
        } 
        else {
            $data = $permission_model->where("id = $id")->find();
        }
        
        $this->assign(array(
               'errorMessage' => $errorMessage,
               'data' => $data 
        ));
        $this->display('permission_update');     
    }
    
    public function ajax_permission_delete() {
       
        if(IS_POST) {
           $row_id = $_POST['row_id'];
           $permission_model = D('Permission');
           $permission_model->startTrans();
           $delete_status = $permission_model->where(array(
               'id' => $row_id,
           ))->delete();
           if(delete_status) {
               //删除权限绑定表中的内容
               $role_to_permission = D('RoleToPermission');
               $status = $role_to_permission->where(array(
                   'permission_id' => $row_id
               ))->delete();
               if($status!==FALSE) {
                  $permission_model->commit();
                  echo '1';//表示删除成功
               }
               else {
                   $permission_model->rollback();
                   echo '2';
               }
           }
           else {
               echo '2';//表示删除失败
           }
        }
    }
}