<?php
namespace Api\Controller;
use Think\Controller\RestController;
use Think\Cache\Driver\Memcache;
class FrontController extends RestController {
   public function index() {
       $block_model = D('Admin/Block');
       $sql = "SELECT image_pc,image_mobile,block_title,block_body,block_link_title,block_link,page_link FROM lovgarden_block WHERE page_link = 'WeChatFront' ORDER BY block_order ASC;";
       $blocks = $block_model->query($sql);
       
       $sql_front_activity = "SELECT image_pc,image_mobile,block_title,block_body,block_link_title,block_link,page_link FROM lovgarden_block WHERE page_link = 'WeChatActivity' ORDER BY block_order ASC;";
       $blocks_activity = $block_model->query($sql_front_activity);
       echo json_encode(array(
            'block' => $blocks,
            'activity_block' => $blocks_activity
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }  
}
