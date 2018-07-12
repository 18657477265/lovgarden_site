<?php
$codepay_id="60905";//这里改成码支付ID
$codepay_key="5v4bRQhIB00NqjcuJPOmIGG49VMXpcPM"; //这是您的通讯密钥
$data = array(
    "id" => $codepay_id,//你的码支付ID
    "pay_id" => "18040314555687237", //唯一标识 可以是用户ID,用户名,session_id(),订单ID,ip 付款后返回
    "type" => 1,//1支付宝支付 3微信支付 2QQ钱包
    "price" => 0.01,//金额100元
    "param" => "",//自定义参数
    "notify_url"=>"https://www.flowerideas.cn/user/user_order_handle",//通知地址
    "return_url"=>"https://www.flowerideas.cn/user/operation_success/status/7",//跳转地址
); //构造需要传递的参数
ksort($data); //重新排序$data数组
reset($data); //内部指针指向数组中的第一个元素

$sign = ''; //初始化需要签名的字符为空
$urls = ''; //初始化URL参数为空

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
$url = "https://codepay.fateqq.com:51888/creat_order/?{$query}"; //支付页面

header("Location:{$url}"); //跳转到支付页面
