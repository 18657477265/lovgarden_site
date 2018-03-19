<?php
namespace Home\Controller;
use Think\Controller;
class UserController extends Controller {
    public function login(){
        if(IS_POST) {            
   	    $user_model = D('User');
   	    // 接收表单并且验证表单
            $user_info = array(
               'user_name' => I('post.user_name',''),
               'user_password' => I('post.user_password',''),
               'user_active_code' => I('post.user_active_code','')
            );
           
   	    if($user_model->validate($user_model->_login_validate)->create()) {
   		if($user_model->login()) {
   		   $this->redirect("Index/index");
   	        }
   	    }
   	   $error_message = $user_model->getError();
           $this->assign(array(
              'error_message' => $error_message,
              'user_name' => $user_info['user_name']
           ));
   	}
        //如果是已经登录用户，跳转到后台首页去
        if(!empty(session('id'))) {
            $this->redirect('/');
        }
        $this->display('login');
    }
    
    public function register() {
        if(IS_POST) {
           $user_model = D('User');
           $user_info = array(
               'user_telephone' => I('post.telephone',''),
               'user_password' => I('post.password',''),
               'repassword' => I('post.repassword',''),
               'user_active_code' => I('post.auth_code','')
            );
          
            $data_status = $user_model->create($user_info);
            if($data_status) {
               $add_status = $user_model->add($data_status);
               if($add_status) {
                   //注册成功，跳转到成功页面提示后再跳转到登入页面去
                   $this->redirect('User/operation_success/status/1');
               }
            }
            $error_message = $user_model->getError();
            $this->assign(array(
              'error_message' => $error_message,
            ));
         }
         $this->display('register');
    }
    
    public function send_ali_message_code() {
        $param = str_pad(mt_rand(0, 999999), 6, "0", STR_PAD_BOTH);
        //将验证码保存在session中，其实最好得方式是保存在memcache中，之后得改
        session('verify_message_code', $param);
        //发送短信
        $send_text_status = TRUE;
        if($send_text_status) {
            echo '1';//表示发送成功
        }
        else {
            echo '2';//表示发送失败
        }
    }
    
    public function operation_success($status='') {
        $this->assign(array(
            'status' => $status,
        ));
        $this->display('success');
    } 
    
    public function logout() {
        $user_model = D('User');
        $user_model->logout();
        $this->redirect('User/login');
    }

    public function chkcode() {
	$Verify = new \Think\Verify(array(
	  'fontSize'    =>    20,    // 验证码字体大小
          'length'      =>    4,     // 验证码位数
	  'useNoise'    =>   TRUE, // 关闭验证码杂点
	));
	$Verify->entry();
    }
    public function user_add(){
        if(IS_POST) {
          $errorMessage = '';
          $data = array(
              'user_name' => I('post.user_name',''),
              'user_email' => I('post.user_email',''),
              'user_password' => I('post.user_password',''),
              'repassword' => I('post.repassword',''),
          ); 
          $user_model = D('User');
          $status_data = $user_model->create($data);
          if($status_data) {
              $add_status = $user_model->add($status_data);
              if($add_status) {
                  $this->redirect('Success/success');
              }
          }
          $errorMessage = $user_model->getError();
          $this->assign(array(
             'errorMessage' => $errorMessage,
             'data' => $data
          ));
          
        }
        $this->display('user_add');   
    }
    
    public function user_list($keyword = '') {
        $where = array();
        $filter_selection = array();
        if(!empty($keyword)) {
            $where['_string'] = "( user_email like '%$keyword%')  OR ( user_name like '%$keyword%') OR ( user_telephone like '%$keyword%')";
            $filter_selection["user_info"] = $keyword;
        }
        $user_model = D("User");
        $all_users = $user_model->where($where)->select();
        $count = count($all_users);
        $Page  = new \Think\Page($count,C('PRODUCT_VARIENT_PAGE')['page_count']);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $Page->show();// 分页显示输出
        
        $users = $user_model->field('user_id , user_name , user_email , open_id , user_telephone , user_status , regist_time')->where($where)->limit($Page->firstRow,$Page->listRows)->select();

        $this->assign(array(
            'users' => $users,
            'page' => $show,
            'filter_selection' => $filter_selection
        ));// 赋值数据集
        $this->display('user_list');
    }
    
    public function update($id) {
        $user_model = D('User');
        $user_to_role_model = D('UserToRole');
        $errorMessage = '';
        $data = array();
        $roles = array();
        
        if(IS_POST) {
          //获取role_id
          $user_to_roles = array(
              'role_id' => I('post.role_id',''),
          );
          $roles = I('post.role_id','');//万一验证出错原样输出到模板上
          
          $data = array(
            'user_id' => $id,
            'user_name' => I('post.user_name',''),
            'user_email' => I('post.user_email',''),
            'user_password' => I('post.user_password',''),
            'repassword' => I('post.repassword',''), 
            'user_status' => I('post.user_status',''), 
          );        
          $status = $user_model->create($data);
          $unique_status = $user_model->is_unique($data['user_email'],$id);
          if($status && $unique_status) {
              //验证都通过，开始保存数据，开启事务
              $user_model->startTrans();
              $save_status = $user_model->save($data);
              if($save_status !== FALSE) {                  
                  //这里用户的基本信息已经保存成功
                  if(!empty($user_to_roles['role_id'])){
                      $add_roles_status = $user_model->add_roles($user_to_roles['role_id'],$id);
                      if(!$add_roles_status) {
                          $user_model->rollback();
                          $this->redirect('Success/failure');
                          exit();
                      }
                  }
                  $user_model->commit();
                  $this->redirect('Success/success');
                  exit();
              }
          }
          $errorMessage = $user_model->getError();
        } 
        else {
            $data = $user_model->where("user_id = $id")->find();
            $roles_found = $user_to_role_model->field('role_id')->where("user_id = $id")->select();
            foreach ($roles_found as $key => $value) {
                $roles[] = $value['role_id'];
            }
        }
        
        $this->assign(array(
               'errorMessage' => $errorMessage,
               'data' => $data,
               'roles' => $roles
        ));
        $this->display('user_update');
        
    }
}