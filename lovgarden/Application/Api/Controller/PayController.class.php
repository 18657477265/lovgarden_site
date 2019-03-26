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
                       //创建微信支付预订单
                       $wx_pay_model = new WxpayModel('wxd7561da4052911c3', '1526072861', 'https://www.flowerideas.cn/api/pay/wx_notify', '9xtnukxfqwvid4it94ieu736lktnc3mu',$login_exist);
                       $params['body'] = '花点馨思花卉商品';
                       $params['out_trade_no'] = $order_info['order_id'];
                       $params['total_fee'] = $order_info['order_final_price'];
                       $params['trade_type'] = 'JSAPI';
                       $result = $wx_pay_model->unifiedOrder( $params );
                       $data = array();
                       if(!empty($result['prepay_id'])) {
                           $data = $wx_pay_model->getPayParams($result['prepay_id']);                          
                       }
                       echo json_encode(array(
                          'data' => $data,
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
       //$wx_pay_model = new WxpayModel('wxd7561da4052911c3', '1526072861', 'https://www.flowerideas.cn/api/pay/wx_notify', '9xtnukxfqwvid4it94ieu736lktnc3mu','onPOZ5dswyfYGoSNF18xAfFoEBRg');
       //$params['body'] = '商品描述'; //商品描述
       //$params['out_trade_no'] = 'O20160617021323-001';
       //$params['total_fee'] = '100';
       //$params['trade_type'] = 'JSAPI'; 
       //$result = $wx_pay_model->unifiedOrder( $params );
       $result = unserialize('a:16:{s:5:"appid";s:18:"wxd7561da4052911c3";s:9:"bank_type";s:3:"CFT";s:8:"cash_fee";s:3:"179";s:8:"fee_type";s:3:"CNY";s:12:"is_subscribe";s:1:"N";s:6:"mch_id";s:10:"1526072861";s:9:"nonce_str";s:32:"HoPEJG2piNffcUCTH1udK23wBkun7IxR";s:6:"openid";s:28:"onPOZ5dswyfYGoSNF18xAfFoEBRg";s:12:"out_trade_no";s:17:"19031517372733755";s:11:"result_code";s:7:"SUCCESS";s:11:"return_code";s:7:"SUCCESS";s:4:"sign";s:32:"CDFA6E304C20D4ABD467B3864F1C240F";s:8:"time_end";s:14:"20190315173731";s:9:"total_fee";s:3:"179";s:10:"trade_type";s:5:"JSAPI";s:14:"transaction_id";s:28:"4200000247201903152054578863";}');

   
   }
   public function wx_notify(){
       //$xml = $GLOBALS['HTTP_RAW_POST_DATA'];
       $xml = file_get_contents("php://input");
       //$data = array();
       if( empty($xml) ){
           //file_put_contents('/b.txt', $xml, FILE_APPEND);
           return false;
       }
       $result = translate_xml_to_data( $xml );
 
       $get_sign = $result['sign'];
       unset($result['sign']);
       $string = '';
       if( !empty($result) ){
           $array = array();
           foreach( $result as $key => $value ){
             $array[] = $key.'='.$value;
           }
           $string = implode("&",$array);
       }
       $string = $string . "&key=".'9xtnukxfqwvid4it94ieu736lktnc3mu';
       $string = md5($string);
       $check_sign = strtoupper($string);
       if($get_sign != $check_sign) {
           return false;
           exit();
       }
       $out_trade_no = $result['out_trade_no'];
       $total_amount = $result['total_fee'];
       $trade_no = $result['transaction_id'];
       //file_put_contents('/b.txt', $out_trade_no.'---'.$total_amount.'---'.$trade_no, FILE_APPEND);
       //exit();
       if(!empty($out_trade_no)){
          $sql_pay = "UPDATE lovgarden_order SET out_trade_no = '$out_trade_no', total_amount = '$total_amount', trade_no = '$trade_no', order_status = '2' WHERE order_id = '$out_trade_no'";
          //file_put_contents('/b.txt', '------'.$sql_pay, FILE_APPEND);
          $data_model = D('Order');
          $sql_result = $data_model->execute($sql_pay);
          //file_put_contents('/b.txt', $sql_result, FILE_APPEND);
          if($sql_result) {
             echo "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";
          }
       }
   }
   public function wx_order_pay() {
       $login_ip = I('get.login_ip');
       $order_id = I('get.order_id');
       $order_info = array();
       $params = array();
       $data = array();
       $login_status = 404;
       $open_id = '0';
       $create_order = 404;
       if(!empty($login_ip) && !empty($order_id)){
           $mem_cache = new Memcache();
           $login_exist = $mem_cache->get($login_ip);
           if(!empty($login_exist)){
               $open_id = $login_exist;
               $login_status = 200;
               $order_model = D('Order');
               $order_info = $order_model->alias('orders')->field('*')->where("orders.`order_owner`='%s' and orders.`order_id`='%s'",array($login_exist,$order_id))->select();

               $new_order_id = date('ymdHis'). rand(10000,99999);
               $order_original_id = $order_info[0]['id'];
               //为了避免微信里面的预订单重复,需要将该订单号重新编号,需要修改order表和order product表中的order_id,再重新创建微信支付预订单
               $new_order_id_sql = "UPDATE lovgarden_order SET order_id = '".$new_order_id."' WHERE id = '".$order_original_id."'";
               $new_order_product_id_sql = "UPDATE lovgarden_order_product_varient SET order_info_id = '".$new_order_id."' WHERE order_original_id = '".$order_original_id."'";
               
               $order_model->startTrans();
               $update_order_result = $order_model->execute($new_order_id_sql);
               $update_order_product_result = $order_model->execute($new_order_product_id_sql);
               if($update_order_result && $update_order_product_result) {
                   //改变成功就提交
                   $order_model->commit();
               }
               else {
                   //改变失败就回滚
                   $order_model->rollback();
                   echo json_encode(array(
                        'data' => $data,
                        'login_status' => $login_status,
                        'create_order' => $create_order
                    ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
                   exit();
               }
               
               $wx_pay_model = new WxpayModel('wxd7561da4052911c3', '1526072861', 'https://www.flowerideas.cn/api/pay/wx_notify', '9xtnukxfqwvid4it94ieu736lktnc3mu',$login_exist);
               
               $params['body'] = '花点馨思花卉商品';
               $params['out_trade_no'] = $new_order_id;
               $params['total_fee'] = $order_info[0]['order_final_price'] * 100;
               $params['trade_type'] = 'JSAPI';

                 
               $result = $wx_pay_model->unifiedOrder( $params );
               
               //$data = array();
               if(!empty($result['prepay_id'])) {
                    $data = $wx_pay_model->getPayParams($result['prepay_id']);
                    $create_order = 200;
               }
           }
       }
       echo json_encode(array(
            'data' => $data,
            'login_status' => $login_status,
            'create_order' => $create_order
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
       exit();
   }
}
