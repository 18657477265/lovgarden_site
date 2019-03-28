<?php
namespace Api\Controller;
use Think\Controller\RestController;
use Think\Cache\Driver\Memcache;
class OrderController extends RestController {
   public function myOrderList(){
       $login_status = 404;
       $open_id = '0';
       $orders_fix = array();
       $login_ip = I('get.login_ip');
       if(!empty($login_ip)){
           $mem_cache = new Memcache();
           $login_exist = $mem_cache->get($login_ip);
           if(!empty($login_exist)){
               $open_id = $login_exist;
               $login_status = 200;
               
               $order_model = D('Order');
               //$filter_selection = array();
               $where = array(array(
                'orders.order_owner' => $login_exist,
               ));
               $order_status_check = I('get.order_status');
               if(!empty($order_status_check)) {
                  $where['orders.order_status'] = $order_status_check;
               }
               //$filter_selection['order_status'] = I('get.order_status');
               $order_sort = array(
                'orders.order_create_time'=>'desc'
               );

               $orders = $order_model->alias('orders')
                        ->join('LEFT JOIN lovgarden_order_product_varient AS order_product ON orders.`id` = order_product.`order_original_id`')
                        ->join('LEFT JOIN lovgarden_product_varient AS products ON order_product.`product_sku_id` = products.`sku_id`')
                        ->join('LEFT JOIN lovgarden_product_varient_images AS images ON products.`id`=images.`product_varient_id`')
                        ->field('orders.`id`,orders.`order_id`,orders.`area`,orders.`address`,orders.`telephone`,orders.`order_create_time`,orders.`order_final_price`, orders.order_coupon_code,orders.order_coupon_cut,orders.order_status, order_product.`product_sku_id`,products.varient_name,images.`image_url`')
                        ->where($where)
                        ->order($order_sort)
                        ->select();
               
               if(!empty($orders)) {
                   $orders_fix = translate_database_result_to_logic_array($orders, array('product_sku_id','image_url','varient_name'), 'id');
               }
           }           
       }
       echo json_encode(array(
             'orders'=> $orders_fix,
             'login_status' => $login_status
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }
   public function myOrderDetail() {
       $login_status = 404;
       $open_id = '0';
       $orders_fix = array();
       $login_ip = I('get.login_ip');
       //此处order_id修改为order表的主键id
       $order_id = I('get.order_id');
       $order_info = array();
       $order_products_fix = array();
       if(!empty($login_ip) && !empty($order_id)){
           $mem_cache = new Memcache();
           $login_exist = $mem_cache->get($login_ip);
           if(!empty($login_exist)){
               $open_id = $login_exist;
               $login_status = 200;
               
               $order_model = D('Order');
               $order_info = $order_model->alias('orders')->field('*')->where("orders.`order_owner`='%s' and orders.`id`='%s'",array($login_exist,$order_id))->select();
               if(!empty($order_info)) {         
                  $sql = "SELECT order_products.*,products.`varient_name`,products.`varient_price`,products.`decoration_level`,images.`image_url` FROM lovgarden_order_product_varient AS order_products
                        LEFT JOIN lovgarden_product_varient AS products ON order_products.`product_sku_id`=products.`sku_id`
                        LEFT JOIN lovgarden_product_varient_images AS images ON products.`id`=images.`product_varient_id` WHERE order_products.`order_original_id`= '$order_id'";

                  $order_products = $order_model->query($sql);
                  $order_products_fix = translate_database_result_to_logic_array($order_products, array('image_url'), 'product_sku_id');
               }
           }
       }
       echo json_encode(array(
             'order_info'=> $order_info,
             'order_products_fix' => $order_products_fix,
             'login_status' => $login_status
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }
   //cron,删除已经过期7天的未支付订单,7天执行一次
   public function deleteExpiredUnpaidOrders() {
       $ip = getIP();
       if($ip == '127.0.0.1' || $ip == '47.98.216.142' || $ip == '172.16.207.38') {
           $sql = "SELECT * FROM lovgarden_order e WHERE DATE_SUB(CURDATE(), INTERVAL 7 DAY) >= DATE(order_create_time) AND order_status = '1'";
           $model = new \Think\Model();
           $data = $model->query($sql);
           $expired_orders = array();
           if(!empty($data)) {
               foreach($data as $key => $value) {
                 $expired_orders[] = $value['id'];
               }
               $expired_orders_string = implode(',', $expired_orders);
               $sql1 = "DELETE FROM lovgarden_order WHERE id IN ($expired_orders_string) AND order_status = '1'";
               $sql2 = "DELETE FROM lovgarden_order_product_varient WHERE order_original_id IN ($expired_orders_string)";
               $model->startTrans();
               $result1 = $model->execute($sql1);
               $result2 = $model->execute($sql2);
               if($result1 && $result2) {
                   $model->commit();
                   file_put_contents('/cron_order.log',date('Y-m-d H:i:s',time())." Delete Expired  Unpaid Orders In Orders and Order-Products Table . Number:".$result1.'|'.$result2.PHP_EOL,FILE_APPEND);
               }
               else {
                   $model->rollback();
                   file_put_contents('/cron_order.log',date('Y-m-d H:i:s',time())." No Delete Expired  Unpaid Orders In Orders and Order-Products Table . Number:".$result1.'|'.$result2.PHP_EOL,FILE_APPEND);
               }
           }
       }
   }
   
}
