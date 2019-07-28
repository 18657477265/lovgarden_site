<?php
namespace Api\Model;
use Think\Model;
use Think\Cache\Driver\Memcache;
class RechargeModel extends Model 
{
   public function addRecharge($recharge_array) {
        $result = $this->add($recharge_array);
        return $result;
    }
   public function findGiftPayCost($original_pay) {
       $activitys_rows = $this->getActivityData("gift_pay","1");
       $level = $this->findRightLevelActivity($activitys_rows, $original_pay);
       if($level != '-1') {
           return $activitys_rows[$level]['gift_pay']; 
       }
       else {
          return 0;
       }
   }
   function getActivityData($type,$status) {
       $activitys = new Memcache();
       $activitys_rows = $activitys->get('activitys_rows');
       if(empty($activitys_rows)){
         $sql = "SELECT * FROM lovgarden_activity WHERE `type` = '$type' AND `status` = '$status' ORDER BY if_pay ASC";
         $activitys_rows = $this->query($sql);
         $activitys->set('activitys_rows', $activitys_rows,86400);
       }
       return $activitys_rows;
   }
   function findRightLevelActivity($activitys_rows,$original_pay) {
       if(!empty($activitys_rows)) {
         foreach($activitys_rows as $key => $value) {
             if($value["if_pay"]<$original_pay) {           
               if(!empty($activitys_rows[$key+1])){
                   continue;
               }
               else {
                   return $key;
               }
             }
             else {
                 $index = $key - 1;
                 return $index;
             }
         }
       }
       else {
           return -1;
       }
   }
   public function userPayWithBalance($order_id,$open_id= '0',$order_final_price = 0 ) {
       if($open_id == '0' || $order_final_price == 0) {
         $sql = "select order_owner, order_final_price from lovgarden_order where order_id = '$order_id'";
         $order_info = $this->query($sql);
         $order_final_price = $order_info[0]["order_final_price"];
         $open_id = $order_info[0]["order_owner"];
       }
       $balance = $this->getUserBalance($open_id);      
       if($balance > $order_final_price) {
           //将用户余额减去订单金额
           $sql_pay_with_balance = "UPDATE lovgarden_wxuser AS a SET a.balance=a.balance - '$order_final_price',a.reward_points = a.reward_points + '$order_final_price' WHERE open_id = '$open_id'";
           $sql_update_order = "UPDATE lovgarden_order SET order_status = '2' WHERE order_id = '$order_id' AND order_status = '1'";
           $this->startTrans();
           $update_balance_result = $this->execute($sql_pay_with_balance);
           if($update_balance_result) {
               $update_order_result = $this->execute($sql_update_order);
               if($update_order_result) {
                   $this->commit();
                   return 'success';
               }
               else {
                   $this->rollback();
                   return 'update order error';
               }
           }
           else {
             $this->rollback();
             return "pay with balance error";
           }
       }
       else {
           return 'balance not enough';
       }
   }
   public function getUserBalance($open_id) {
       $sql = "select balance from lovgarden_wxuser where open_id = '$open_id'";
       $current_balance = $this->query($sql);
       $balance = $current_balance[0]["balance"];
       return $balance;
   }
}



