<?php
namespace Api\Controller;
use Think\Controller\RestController;
use Think\Cache\Driver\Memcache;
use Api\Model\WxpayModel;
class RechargeController extends RestController {      
   //创建充值订单
   public function makeRecharge($original_pay,$login_ip='none') {
       $mem_cache = new Memcache();
       $login_exist = $mem_cache->get($login_ip);
       $add_result = 0;
       $data = array();
       $create_recharge = 404;
       if(!empty($login_exist)) {              
         //获取阈值,避免数据库被恶意写入
         $login_ip_existed_try = $mem_cache->get($login_exist."recharge");
         if(empty($login_ip_existed_try)) {
             $login_ip_existed_try = 0;
         }
         $login_ip_existed_try =  $login_ip_existed_try + 1;
         //半小时以内最多只能创建五个空的充值订单
         $mem_cache->set($login_exist."recharge",$login_ip_existed_try,1800);
         if($login_ip_existed_try < 5) {
             $open_id = $login_exist;
             $recharge = D('Recharge');
             $gift_pay = $recharge->findGiftPayCost($original_pay);
             $recharge_info = array(
                'recharge_id' => date('ymdHis'). rand(10000,99999),
                'open_id' => $open_id,
                'original_pay' => $original_pay,
                'gift_pay' => $gift_pay,
                'result_pay' => $original_pay + $gift_pay,
                'recharge_create_time' => date('Y-m-d H:i:s')
             );
            $add_result = $recharge->addRecharge($recharge_info);
            if($add_result > 0) {
                $create_recharge = 500;
                //创建微信支付预订单
                $wx_pay_model = new WxpayModel('wxd7561da4052911c3', '1526072861', 'https://www.flowerideas.cn/api/recharge/userRechargeSuccess', '9xtnukxfqwvid4it94ieu736lktnc3mu',$login_exist);
                $params['body'] = '花点馨思花卉充值';
                $params['out_trade_no'] = $recharge_info['recharge_id'];
                $params['total_fee'] = $recharge_info['original_pay'];
                $params['trade_type'] = 'JSAPI';
                $result = $wx_pay_model->unifiedOrder( $params );
                if(!empty($result['prepay_id'])) {
                   $data = $wx_pay_model->getPayParams($result['prepay_id']);
                   $create_recharge = 200;
                }
            }
         }
        }
       echo json_encode(array(
          'data' => $data,
          'create_recharge' => $create_recharge
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }
   public function userRechargeSuccess() {
       //接受微信支付通知所传递的微信充值单号和金额
       $xml = file_get_contents("php://input");
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
       if(!empty($out_trade_no)){
         //$yuan = $total_amount/100;
          $yuan = $total_amount;     
         //$recharge_id = '19072021551048956';
         //$original_pay = 1200;
         $recharge = D('Recharge');
         $gift_pay = $recharge->findGiftPayCost($yuan);
         $result_pay = $yuan + $gift_pay;
         //事务:充值表状态改为充值成功,并给用户添加上响应的充值金额
         $sql_pay = "UPDATE lovgarden_recharge SET `status`= '2' WHERE recharge_id = '$out_trade_no' AND `status`= '1'";
         $sql_add_recharge_pay = "UPDATE lovgarden_wxuser AS a SET a.balance = a.balance + $result_pay WHERE a.open_id = (SELECT b.open_id FROM lovgarden_recharge as b WHERE b.recharge_id ='$out_trade_no')";
         //file_put_contents('/b.txt', '------'.$sql_pay, FILE_APPEND);
          //如果有优惠券,还需要吧优惠券设置成已使用
         $recharge->startTrans();
         $sql_result = $recharge->execute($sql_pay);
         if($sql_result) {
             $add_user_pay_result = $recharge->execute($sql_add_recharge_pay);
             if($add_user_pay_result) {
                 $recharge->commit();
               //提交微信停止通知
                 echo "<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>";
                 exit();
             }
             else {
                 $recharge->rollback();
                 exit();
             }
         }
         else {
            $recharge->rollback();
            exit();
         }
       }
   }
   public function userPayWithBalance($order_id) {
       $recharge = D("Recharge");
       $pay_result = $recharge->userPayWithBalance($order_id);
       echo $pay_result;
   }
   public function getUserBalance($login_ip) {
       $mem_cache = new Memcache();
       $login_exist = $mem_cache->get($login_ip);
       $get_balance_status = '404';
       $balance = 0;
       if(!empty($login_exist)) {
           $recharge = D("Recharge");
           $balance = $recharge->getUserBalance($login_exist);
           $get_balance_status = '200';
       }
       echo json_encode(array(
          'get_balance_status' => $get_balance_status,
          'balance' => $balance
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);       
   }
   
}
