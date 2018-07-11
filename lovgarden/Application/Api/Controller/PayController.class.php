<?php
namespace Home\Controller;
use Think\Controller\RestController;
use Think\Cache\Driver\Memcache;
class PayController extends RestController {
   //protected $allowMethod    = array('post');
   //protected $allowType      = array('json');
   public function create_order($codepay_id,$order_id,$type,$price,$param = '') {
       //$codepay_id="1111111111";//这里改成码支付ID
       $codepay_key="abcdefg"; //这是您的通讯密钥
       
       $data = array(
            "id" => $codepay_id,//你的码支付ID
            "pay_id" => $order_id, //唯一标识 可以是用户ID,用户名,session_id(),订单ID,ip 付款后返回
            "type" => $type,//1支付宝支付 3微信支付 2QQ钱包
            "price" => $price,//金额100元
            "cparam" => $param,//自定义参数
            //"notify_url"=>"",//通知地址
            //"return_url"=>"http://codepay.fateqq.com/",//跳转地址
        ); //构造需要传递的参数
       ksort($data);
       reset($data);
       
       //echo "<pre>";
       //print_r($data);
       //echo "</pre>";
       //exit();
       
       $sign = '';
       $urls = ''; 
       foreach ($data AS $key => $val) { //遍历需要传递的参数
            if ($val == ''||$key == 'sign') continue; //跳过这些不参数签名
            if ($sign != '') { //后面追加&拼接URL
                $sign .= "&";
                $urls .= "&";
            }
            $sign .= "$key=$val"; //拼接为url参数形式
            $urls .= "$key=" . urlencode($val); //拼接为url参数形式并URL编码参数值

        }
        $query = $urls . '&sign=' . md5($sign .$codepay_key); //创建订单所需的参数
        
        //echo $query;
        //exit();
        $url = "https://www.flowerideas.cn/api/pay/alipay/?{$query}"; //支付页面

        header("Location:{$url}"); //跳转到支付页面
   }
   public function alipay(){
       //验证签名
        $arr = $_GET;
        $codepay_key="abcdefg"; //这是您的密钥
        $sign = $this->get_sign($arr,$codepay_key);
        echo $_GET['sign'];
        echo '------------';
        echo $sign;
        exit();
        if (!$_GET['pay_id'] || $sign != $_GET['sign']) { //不合法的数据
            exit('fail');  //返回失败 继续补单
        } else { //合法的数据
            $helper = D('Home/Helper');
            //echo "<pre>";
            //print_r($_GET);
            //echo "</pre>";
            //exit();
            $order_id = I('get.pay_id');
            $price = I('get.price');
            $shop_subject = '1 plus 1';
            $body = '1 plus 1'; 
            $helper->alipay($order_id,$shop_subject, $price,$body,$time_expire='1m');
        }
   }
   private function get_sign($arr,$codepay_key) {
       ksort($arr);
       reset($_GET);
       $sign = '';//初始化
       foreach ($arr AS $key => $val) { //遍历POST参数
            if ($val == '' || $key == 'sign') continue; //跳过这些不签名
            if ($sign) $sign .= '&'; //第一个字符串签名不加& 其他加&连接起来参数
            $sign .= "$key=$val"; //拼接为url参数形式
       }
       return md5($sign . $codepay_key);
   }
   function return_url() {
       echo '22';
       exit();
   }
   function notify_url(){
       return '2';
   }
}
