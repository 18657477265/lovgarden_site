<?php
namespace Home\Model;
use Think\Model;
use Think\Cache\Driver\Memcache;
class OrderModel extends Model 
{
    //调用时候create方法允许接受的字段
    protected $insertFields = 'order_id,order_owner,last_name,first_name,telephone,area,address,post_code,'
                            . 'content_body,order_create_time,order_products_total_price,order_deliver_price,'
                            . 'order_vases_price,order_coupon_code,order_coupon_cut,order_vip_level_cut,'
                            . 'order_final_price,order_status';
    protected $updateFields = '';
    protected $_validate = array(
        array('order_id','require','订单编号不能为空',1),
        array('order_owner','require','下单人账号不能为空',1),
        array('last_name','5,30','姓名长度不符',1,'length'),
        array('first_name','5,30','姓名长度不符',1,'length'),
        array('telephone','/^(13[0-9]|14[5|7]|15[0|1|2|3|5|6|7|8|9]|18[0|1|2|3|5|6|7|8|9])\\d{8}$/','手机号码格式不正确',1),
        array('area','require','省市不能为空',1),
        array('address','require','街道门牌等详细地址不能为空',1),
        array('post_code','/^[0-9]\\d{5,8}$/','邮编格式不对',1),
        array('content_body','1,500','贺卡字数限制在500个字符内',1,'length'),
    );
}












