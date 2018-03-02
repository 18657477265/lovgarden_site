<?php
namespace Admin\Controller;
use Think\Controller;
class RoleController extends Controller {
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
        $errorMessage = '';
        $data = array();
        if(IS_POST) {
          $data = array(
            'id' => $id,
            'role_name' => I('post.role_name','')  
          );        
          $status = $role_model->create($data);
          $unique_status = $role_model->is_unique($data['role_name'],$id);
          if($status && $unique_status) {
              $save_status = $role_model->save($data);
              if($save_status !== FALSE) {
                  $this->redirect('Success/success');
              }
          }
          $errorMessage = $role_model->getError();
        } 
        else {
            $data = $role_model->where("id = $id")->find();
        }
        
        $this->assign(array(
               'errorMessage' => $errorMessage,
               'data' => $data 
        ));
        $this->display('role_update');
        
    }
    
    public function ajax_role_delete() {
        if(IS_POST) {
           $row_id = $_POST['row_id'];
           $role_model = D('Role');
           $delete_status = $role_model->where(array(
               'id' => $row_id,
           ))->delete();
           if(delete_status) {
               echo '1';//表示删除成功
           }
           else {
               echo '2';//表示删除失败
           }
        }
    }
}