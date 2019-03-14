<?php
namespace Api\Controller;
use Think\Controller\RestController;
use Think\Cache\Driver\Memcache;
use Api\Model\WxpayModel;
class PayController extends RestController {
   public function create_wx_order() {
         $error_message = '';
         $error_message_content = '';
         $login_ip = I('get.login_ip');
         $sku_ids = json_decode($_GET['sku_ids']);
         $vase_count = I('get.vase_count');
         $coupon_code = I('get.coupon_code');
         $mem_cache = new Memcache();
         $login_exist = $mem_cache->get($login_ip);
         $sku_ids_str = '';
         $count = 0;
         $sku_ids_count = count($sku_ids);
         foreach ($sku_ids as $k => $v) {
             $count = $count + 1;
             $sku_ids_str = $sku_ids_str . $v->sku_id;
             if($count != $sku_ids_count){
                $sku_ids_str = $sku_ids_str . ',';         
             }
         }
         if(!empty($login_exist)) {
             $model = new \Think\Model();
             $sql = "SELECT id,sku_id,varient_name,varient_summary,varient_body,varient_status,varient_price,decoration_level,vase  FROM lovgarden_product_varient                 
                    WHERE`sku_id` IN ($sku_ids_str);";            
             $result_rows = $model->query($sql);
             //$multiple_fileds_array = array('image_url','flower_home_id','hurry_level_id','flower_home');  
             $result_rows_array = translate_database_result_to_logic_array($result_rows,array(),'sku_id');
             foreach ($sku_ids as $k => $v) {
                 $result_rows_array[$v->sku_id]['count']=$v->count;
                 $result_rows_array[$v->sku_id]['vase']=$v->vase_option;
                 $result_rows_array[$v->sku_id]['deliver_time']=$v->deliver_time;
             }
             //$error_message = '';
             $order_products_info = $result_rows_array;
             $costs = wx_calculate_cost($order_products_info,20,$coupon_code,$vase_count);                   
             $order_info = array(
                'order_id' => date('ymdHis'). rand(10000,99999),
                'order_owner' => $login_exist,
                'last_name' => 'WeChat',
                'first_name' => 'User',
                'telephone' => I('get.phone_number'),
                'area' => I('get.address_province_city'),
                'address' => I('get.address_detail_location'),
                'post_code' => I('get.zip_code'),
                'content_body' => I('get.order_item_message'),
                'order_create_time' => date('Y-m-d H:i:s'),
                'order_products_total_price' => $costs['products_original_cost'],
                'order_deliver_price' => $costs['deliver_cost'],
                'order_vases_price' => $costs['vase_cost'],
                'order_coupon_code' => $coupon_code,
                'order_coupon_cut' => $costs['cut_cost'],
                'order_vip_level_cut' => '0',
                'order_final_price' => $costs['total_cost'],
                'order_status' => '1',  
             );
        
             $order_model = D('Order');
             $validate_status = $order_model->create($order_info);
             if($validate_status){
                 $order_model->startTrans();
                 $order_id = $order_model->add($validate_status);
                 //echo $order_id;
                 //exit();
                 if($order_id) {
                     $final_status = TRUE;
                     $order_bind_product_model = D('OrderProductVarient');
                     foreach($order_products_info as $k => $v) {
                        $order_bind_product_array = array(
                          'order_original_id' => $order_id,
                          'order_info_id' => $order_info['order_id'],
                          'product_sku_id' => $v['sku_id'],
                          'vase_option' => $v['vase'],
                          'deliver_time' => $v['deliver_time'],
                          'items_count' => $v['count']
                        );
                        $order_bind_product_status = $order_bind_product_model->add($order_bind_product_array);
                        if(!$order_bind_product_status) {
                            $order_model->rollback();
                            $final_status = FALSE;
                            break;
                        }
                    }                 
                    if($final_status) {
                       $order_model->commit();
                       echo json_encode(array(
                          'create_order' => '200'
                       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
                       exit();
                    }
                 }
             }
             else {
                 $error_message = '404';
                 $error_message_content = $order_model->getError();
             }
         }
         else {
             $error_message = '403';
         }
         echo json_encode(array(
             'create_order' => $error_message,
             'error_message_content' => $error_message_content
         ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
         exit();        
   }
   public function wx_pay() {
       $wx_pay_model = new WxpayModel('wxd7561da4052911c3', '1526072861', 'https://www.flowerideas.cn/api/pay/wx_notify', '9xtnukxfqwvid4it94ieu736lktnc3mu');
       $params['body'] = '商品描述'; //商品描述
       $params['out_trade_no'] = 'O20160617021323-001';
       $params['total_fee'] = '100';
       $params['trade_type'] = 'APP'; 
       $result = $wx_pay_model->unifiedOrder( $params );
       print_r($result);     
   }
   public function wx_notify(){
       
   }
}
