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
        
//        $cart = D('Cart');
//        $cart_info = array(
//            'user_id' => 5,
//            'varient_id' => '180011',
//            'deliver_time' => '2018/12/29',
//            'vase' => '1',
//        );
//        $status = $cart->create($cart_info);
//        header('Content-Type: text/plain; charset=utf-8');
//        var_dump($status);
//        var_dump($cart->getError());
//        exit();
        
//        $model = new \Think\Model();
//        $t=$model->query("SELECT b.hurry_level_id  from lovgarden_product_varient AS a 
//LEFT JOIN lovgarden_product_varient_hurry_level AS b ON a.`id`=b.`product_varient_id` WHERE a.`sku_id`='180011';
//");
//        echo "<pre>";
//        print_r($t);
//        echo "</pre>";
//        exit();
        
//        $sql = "SELECT b.varient_name,b.varient_price,COUNT(*) AS number FROM lovgarden_cart AS a
//LEFT JOIN lovgarden_product_varient AS b ON a.varient_id = b.sku_id WHERE a.user_id = '16' GROUP BY b.id;";
//        $model = new \Think\Model();
//        $results = $model->query($sql);
//        
//        $this->assign(array(
//           'results' => $results, 
//        ));
//        $content = $this->display('Common/cart_block');
//        echo $content;
//        exit();
//        
//        echo "<pre>";
//        print_r($results);
//        echo "</pre>";
//        exit();
        
        $this->display('Index/index');
    }
}