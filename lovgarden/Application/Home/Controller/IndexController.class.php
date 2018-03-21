<?php
namespace Home\Controller;
use Think\Controller;
use Org\Util\SendCustomCode;
class IndexController extends Controller {
    public function index(){
        $block_model = D('Admin/Block');
        $sql = "SELECT image_pc,image_mobile,block_title,block_body,block_link_title,block_link,page_link FROM lovgarden_block WHERE page_link = 'front' ORDER BY block_order ASC;";
        $blocks = $block_model->query($sql);
        $this->assign(array(
           'blocks' => $blocks, 
        ));
        //require_once('/application/apache/htdocs/lovgarden/ThinkPHP/Library/Org/Util/SendCustomCode.class.php');
        //set_time_limit(0);
        //header('Content-Type: text/plain; charset=utf-8');       
        //$response = SendCustomCode::sendSms('18657477265','33tt33');
        //echo $response;
        //exit();
        //print_r($response);
        //exit();
        $this->display('Index/index');
    }
}