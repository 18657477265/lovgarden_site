<?php
namespace Api\Model;
use Think\Model;
use Think\Cache\Driver\Memcache;
class OrderModel extends Model 
{
    //调用时候create方法允许接受的字段
    protected $insertFields = 'order_id,order_owner,last_name,first_name,telephone,area,address,post_code,content_body,order_create_time,order_products_total_price,order_deliver_price,order_vases_price,order_coupon_code,order_coupon_cut,order_vip_level_cut,order_final_price,order_status';
    protected $updateFields = '';
    protected $_validate = array(
        array('order_id','require','订单编号不能为空',1),
        //array('order_id','checkUserOrdersUnpaid','未支付订单超过最大限制',1,'callback',3),
        array('order_owner','require','下单人账号不能为空',1),
        array('last_name','1,30','名字格式错误',1,'length'),
        array('first_name','1,30','姓格式错误',1,'length'),
        array('telephone','/^(13[0-9]|17[0|1]|19[0|1]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\\d{8}$/','手机号码格式不正确',1),
        array('area','1,200','省市不能为空',1,'length'),
        array('address','1,255','街道门牌等详细地址不能为空',1,'length'),
        //array('post_code','/^[0-9]\\d{5,8}$/','邮编格式不对',1),
        array('content_body','0,255','留言长度未符合要求',1,'length'),
    );
    //验证未支付订单不得超过20,且处于登录状态
    function checkUserOrdersUnpaid($max_items = 20,$login_ip){
        $mem_cache = new Memcache();
        $login_exist = $mem_cache->get($login_ip);
        if(!empty($login_exist)){
          //$user_telephone = session('user_telephone');        
          $sql = "SELECT id FROM lovgarden_order WHERE order_owner = '$login_exist' and order_status='1'";        
          $model_for_user = new Model();
          $results = $model_for_user->query($sql);
          $count = count($results);
          if($count<$max_items){
             return TRUE;
          }
        }
        return FALSE;
    }
}












