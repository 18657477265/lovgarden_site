<?php
namespace Admin\Model;
use Think\Model;
class BlockModel extends Model 
{
    //调用时候create方法允许接受的字段
    protected $insertFields = 'image_pc,image_mobile,block_title,block_body,block_link_title,block_link,page_link,block_order';
    protected $updateFields = 'image_pc,image_mobile,block_title,block_body,block_link_title,block_link,page_link,block_order';
    protected $_validate = array(
        array('image_pc','require','block在PC上的图片必传',1,'',1),
        array('image_mobile','require','block在mobile上的图片必传',1,'',1),
        array('block_title','require','标题不能为空',1),
        array('block_body','require','内容不能为空',1),
        array('block_link_title','require','block跳转标题不能为空',1),
        array('block_link','require','block跳转链接不能为空',1),
        array('page_link','require','所属页面不能为空',1),
        array('block_order','number','顺序必须为数字且在1-256之间',1),
        array('block_order', 'check_order', '顺序必须为数字且在1-256之间', 0, 'callback', 3),
    );
    function check_order ($str) {
        if(is_numeric($str)){
            if($str >= 1 && $str < 256) {
                return TRUE;
            }
            else {
                return FALSE;
            }
        }
        else {
            return FALSE;
        }       
    }
    public function block_add($data) {       
         $uploaded_images = uploadImage('block','block');
         $result_status = FALSE;
         if($uploaded_images) {
             $data['image_pc'] = $uploaded_images['image_pc']['savepath'].$uploaded_images['image_pc']['savename'];
             $data['image_mobile'] = $uploaded_images['image_mobile']['savepath'].$uploaded_images['image_mobile']['savename'];
             $add_check_status = $this->create($data);             
             if($add_check_status) {
                 $add_status = $this->add($add_check_status);
                 if($add_status) {
                     $result_status = TRUE;
                 }
             } 
         }
         else {
             $this->error = '区块的图片不能为空';
         }
         return $result_status;
    }
    
    public function block_update($data,$block_id) {
        if($_FILES["image_pc"]['error'] > 0) {
            unset($_FILES['image_pc']);
        }
        if($_FILES['image_mobile']['error'] >0 ) {
            unset($_FILES['image_mobile']);
        }
        
        if(!empty($_FILES)) {
           $uploaded_images = uploadImage('block','block');
           if(isset($uploaded_images['image_pc'])) {
               $data['image_pc'] = $uploaded_images['image_pc']['savepath'].$uploaded_images['image_pc']['savename'];
           }
           if(isset($uploaded_images['image_mobile'])) {
               $data['image_mobile'] = $uploaded_images['image_mobile']['savepath'].$uploaded_images['image_mobile']['savename'];
           }
        }
        $save_data = $this->create($data);
        if($save_data) {
           $update_status = $this->where("id = $block_id")->save($save_data);
           if($update_status !== FALSE) {
                return TRUE;
            }
            else {
                return FALSE;
            }
        }
        else {
            return FALSE;
        }
    }
}












