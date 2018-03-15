<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $block_model = D('Admin/Block');
        $sql = "SELECT image_pc,image_mobile,block_title,block_body,block_link_title,block_link,page_link FROM lovgarden_block WHERE page_link = 'front' ORDER BY block_order ASC;";
        $blocks = $block_model->query($sql);
        $this->assign(array(
           'blocks' => $blocks, 
        ));
        $this->display('Index/index');
    }
}