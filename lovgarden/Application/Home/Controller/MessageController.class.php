<?php
namespace Home\Controller;
use Think\Controller\RestController;
use Think\Cache\Driver\Memcache;
class MessageController extends RestController {
   //protected $allowMethod    = array('post');
   //protected $allowType      = array('json');
   Public function read_get_json(){
        echo "read_get_json";
   }
   Public function read_post_json(){
       $value = array();
       if(mem_check_ip_attention_mail()) {
            $telephone = I('post.telephone');
            $content = I('post.content');
            $subject = I('post.subject');
            if(!empty($telephone) && !empty($content) && !empty($subject)) {
                $helper = new \Home\Model\HelperModel();
                if($helper->leave_message('mail',$telephone,$content,$subject)) {
                    $value['code']=200;
                    $value['msg']="Message sent successfully";
                }
                else {
                    $value['code']=500;
                    $value['msg']="Message sent failed";
                }
            }
            else {
                $value['code']= 404;
                $value['msg']="Message can not be empty";
            }
       }
       else {
           $value['code']=403;
           $value['msg']="Submit too many times in short time,the server denied,please try later.";
       }
       $this->response($value,'json');
   }
}
