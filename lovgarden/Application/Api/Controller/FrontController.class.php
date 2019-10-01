<?php
namespace Api\Controller;
use Think\Controller\RestController;
use Think\Cache\Driver\Memcache;
class FrontController extends RestController {
   public function index() {
       $mem_cache = new Memcache();
       //$front_blocks_array = array();
       $front_blocks_array = $mem_cache->get('front_blocks');
       if(empty($front_blocks_array)) {
         $block_model = D('Admin/Block');
         $sql = "SELECT image_pc,image_mobile,block_title,block_body,block_link_title,block_link,page_link FROM lovgarden_block WHERE page_link = 'WeChatFront' ORDER BY block_order ASC;";
         $blocks_tip = $block_model->query($sql);
       
         $sql_front_activity = "SELECT image_pc,image_mobile,block_title,block_body,block_link_title,block_link,page_link FROM lovgarden_block WHERE page_link = 'WeChatActivity' ORDER BY block_order ASC;";
         $blocks_activity = $block_model->query($sql_front_activity);
         $front_blocks_array['block_tip'] = $blocks_tip;
         $front_blocks_array['block_activity'] = $blocks_activity;
         $mem_cache->set('front_blocks',$front_blocks_array,86400);
       }
       echo json_encode(array(
            'block' => $front_blocks_array['block_tip'],
            'activity_block' => $front_blocks_array['block_activity']
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }  
}
