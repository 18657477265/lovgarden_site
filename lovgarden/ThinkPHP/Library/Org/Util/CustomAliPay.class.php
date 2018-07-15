<?php
namespace Org\Util;
use Think\Model;
require_once './alipay_php/pagepay/service/AlipayTradeService.php';
require_once './alipay_php/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php';


class CustomAliPay {
   public $out_trade_no = '';
   public $subject = '';
   public $total_amount = '';
   public $body = '';
   public $config = array (	
		//应用ID,您的APPID。
//		'app_id' => "2018070660584041",
		'app_id' => "2018071460664066",

		//商户私钥
//		'merchant_private_key' => "MIIEpQIBAAKCAQEA2ggqhYPVNrTiYy2btMinshaaGlqkSL4QooCK7t93/xVTvwTo+Mk/o3g1wAm8LZQeChljWEdzG/zCM0+FE3huCDmTvU8IUGszuZnJpw3AMqLlW4RtBrJuGvHa9/r+huxKkoTZvQuR1YsyAcER41FMk464CNTjHno7vVTSyDYXRUM+DX3SAbg+O7DAPKFIDk+VTq6Jl+/ck0uGVXDW8UguC2Xwu/gcnAu2KUeh3NS+yLwAc0PtWfPH9g9WWgN0uFxAkWbfiTCGUK1DEQAPhZJeg/a9wRzydsrPd+ar94DWgvIfaMyBMgdkenpyIMaas+W/yA6cN2PNugPJhw+hQlEAuwIDAQABAoIBAQDFVebGeQ+dOBI+maT39zRwZyyK9ccDX6NGsPkOQowk/3SQyyzhH6TNm5tqeGUtC4Y0tc3ItMJmblqGfk5/1Nwh7ZreGI35200xixOMc1GlgvH75tuW2B/3mzcIgs+j5nGIM12vUK1pjVZxaAF8sLSSSPYgaC44A4HWVtOAChT9xbahpHODwWlYctX6UyDCZjGUJCadGwmCjTWCKsyV01S4c2w8UqKbp4b7vVpNcPW39dKSgAtpfPEfDkEvGXYQYge8Cl1oirP5FUuy3xBiTHgIqZd3UE0FPVPk773tYT7vRMu2lz7v3va0MvF3sX5lm8cQ2u0Rwm1rPOCz8NVmRpABAoGBAPOyUjMONS75WjTF5FV+kwq20FLObR1uocLtO2KusX4ygpc/anEcsVmbYRlgkfwq0iTlAPWpis5VtMq3ArRQbg78mksShbLPkT6QB5Rlq6v/GOVNvdbmh9mCHaStMFAojKh67Y3JLpYgIXCH6usdeaUTqtyI3YZDEiyY1oUwzsIBAoGBAOUKI75nhLq6qeZMl9otyb9+5cfhlg2duFnGSxugc1IBAf0/uMgKl0XfjicSpWPai3eiI0fjeU/2BImuG/Qr0ZIBjMkAQbTqFUgOFmtFV4ngPDCYExzIVyd2QvccDVEX1F6XIF9uT6vUYERX87Umef+dk3TpItzE6blO5FKtNUq7AoGBAIHvHzLHrYWpP/aJWPBYt4/r33F3TOh3d1pWYOaB3HM7/TMlhdxffxQW65O7ULsSHc+8JmHVjwPq4KWBJLj9dWEaQC4s5wpq77da1h/CgeEH978zTzgI3IAVhzapfOwQYsbmHEkP7n3vDcVRQukvYw+oR96kPvD6S/NpXl/PoT4BAoGBAOFc8cC3EQW/B8/AS6Z1aU7QaP4c/M2XBD7pexvK682jijaKzaGfsishhjjyAuTWtGZZlkd1DvfbxalHNOAzgXkdp05bv0tpDNmiavLP/wt+JRtXd27Zvc/pcIi/BGdngCI2p7bezuvvA24b8IOtQVt/zAi8IP6Djso0Uzr6jTZjAoGAc7QVKXGtaqyuyzkCtA1n+aYpJd3HwD8miW+NdXJqWSlyrfX6oIfPvwkyZ+eOVdASywv+fJdIKaHUVTCToEpVmRjGD8mLliwrIErDXYuu3vFyPd3z7PUAGDaM/rmT/7ixuSKNdTocM7YFNJ7phZ+s5kZ1GR+L4z/3vSrbxSltQec=",
		'merchant_private_key' => "MIIEpAIBAAKCAQEA76Lq4TjiEBXjY5SfQe0wLRmRXiZKZYbEF3CGMZsq94uq9vMH7Hiut6Y34LQe7Hbu8WwAN8TEDv+o1P59dqMxNzt3YC/wH1HrJvOzY0iKwIsmqtl/PPGl+cWWx2l9fHvpOd2Sdnq3mdHZhwxNoJHOQ4XTSUUGeLd94ZsPk2naD8vpYttJoz8g0pV/xxtd2Ty1D+TMPUNXimzTA0lVM1QN06wZuxkUTCnPmClf347+Dl7rOmx39BaU5gv7q4f//91LWlgOO/Hy+VLh5HFiE7iVsC+gR6HRBNJ2T3/XuUtvVN7Z/EKCbB9LJxauUILgUf04RG9RB9S4XQpnTPbTiWApvQIDAQABAoIBADiu4rwvhlmjs8vERf+EKVchqx8IE6T8QboBpdxqBbnUUbZkOkWXLkzGhoUiVgY9A7wbfdmzM2UQ2FC5edfsNiIIcO5RqIzgBa2LC626ZQeyYo0bMVurFUWFlWrJ6yLc9If5f0GDOZaq6rdlE5+dnZqfNyEnsKYxURdiZEbMTQZbEka8kVWJETmQkCYyFWiOcS7zskwW2PvWdy/BuBOqi+7CyNHE3CvT3IJUqrZyXSRdqHKzyK/ZKDuiG549tjednZSkkvteskUOaKIk3ygraQlL8web+zinV5YiI3ydmiKNsLW8Y/zfSlgxsb3SkhEBJjWtFK5gs3ey4uRSmBnyXwkCgYEA+DqIhAR+8khBrFo+dDQlJyrQtRWUIx21PaMQF4eJeaUJrehq8mlXReiyjAZvSTB2Rk59uOpGlOftv07J7T8mQ/BhKQ9lSmTI5vRwd9iyoZSV5xh4BQU0d+MbGeFiW66GSXJn9aF3nnBFGE3nNaaQmcBINMhru3IBl4X/hhxV6pcCgYEA9yOFNFx9a2772BHVfOf55kE2SW1axHtCL2G6WZ92KniSgjTxnAuUE4qE+rQl0s+pFb1CnUovx6JC+axI8sr01m3b81gNeUFUi8oHkKypyRf78XiiRO/BIbS6uD/Mbdi/42pLNojgCIpcxFtABAznIg2YD2ijpfLjpTeZmFMSfMsCgYEAm78eKLcpb5smmZ2F0iNlrE2Q5g3EBMR/E01cq2a/OzPx/sv1TmQJ/jMi0/i1mpZtn29gMsXLw2JCKeali9YzjC0myTx/sU3LQB3e81NjwT4ZUAZqR7wcT5yPRI3FyQCYAT1nPRcylpaaw8UdbOw1jkK3C2c1MUc2+Ht6+AqPXcECgYAH99Q2oPjGOvjRuZl5gn0zKE0YxvbTOOokXvFELHkB24tVuWFE17a5fpDVCHPjvSBFfCmmqpqpxBiGtkBP261TMI2T5fXhP/L64tOlnXuH6UcNPQd2U4iVjp8qi5wKup9NNMMiCf23KMbe6dZBiyz0kx2g+Y21LwSZ8hX4rEz/SwKBgQDGGCPI+hhkvneyb+3SLUjqLw9LoHNmpfPD3CNrJQYsCfbB1icT6ednSjuvAlg6rVUz6YTVMM2aTJQqzStKA6N5mrm/9W+oJsyFgYC0akHLLOP9kXMzVg4AeHmCQsHMLHWJsu8iQCYNoibBLz5qy233e+Ovqka64Fisq46l7FS5ag==",
		
		//异步通知地址
		'notify_url' => "https://www.flowerideas.cn/user/user_order_handle",
		
		//同步跳转
		'return_url' => "https://www.flowerideas.cn/user/pay_result",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
//		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArsr9JijJkO8WCy+soVSKnEXHiotZl165qn6N6STtLYKkFxLJR++pA57JMC0lDy4h61mqMSuBhK9j9Q51pK4lkkym+WeUmjNbpr6cjoSqBntc2yac64tv9qJMUHLAMEBTL4G5s+GE9V++GTu0MOF8hE70YwdG7oS1NRdHLBtOSfh1jZ3X1cIeezs88XGJywpj4oEZjEjDDPTyMxhqnj2jrigFliqdq8IY5gT76iwYnHU9XKhJaF37mbGGX+bqS2EkiXKz+LKw2+T5MtZ1wbJqUGfAU45vvSsAu51dwDapKeR9k2hOUfc07+OoMuW2JxBzLZ/wnpdMPZxzgygE08GgEQIDAQAB",
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAnMU+7+DLkejmJxXLsvT0KHX2tJyy+0Zrj6a0aAS/D8w4qa0b5yK8Fh40w7p8u2KuYUPow19axuSvR+ZDTB0rntzKuxiJEpyATetflblMhCLaLoJrNHLC8gmntftIq1R6me/EYur/OGd4Gf/+KUOZTEMZMwX4FT83J20Y1HaLuRI64GLhOKpTESfQ0oAMebIZ6tVnw7Ewii8Let9SaRKf26he1wcNO5piCupWKNTIdnSeKkuzuRxlLhvtevuh4vCJ9ItcIqfI1iqNapuKdKzNrmerF9zm0ku/qL4vfXKq6ebj0YIklAuyywqC7TG3Rp4cUz+edDeDTogRSfwCbDyn+QIDAQAB",
       
   );
   
   public function __construct($out_trade_no = '',$subject= '',$total_amount = '',$body = '') {       
     $this->out_trade_no = $out_trade_no;
     $this->subject = $subject;
     $this->total_amount = $total_amount;
     $this->body = $body;
  }

  function proxy_post($url, $param){
    $httph =curl_init($url);
    curl_setopt($httph, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($httph, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($httph,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($httph, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0)");
    curl_setopt($httph, CURLOPT_POST, 1);//设置为POST方式 
    curl_setopt($httph, CURLOPT_POSTFIELDS, $param);
    curl_setopt($httph, CURLOPT_RETURNTRANSFER,1);
    //curl_setopt($httph, CURLOPT_HEADER,1);
    $rst=curl_exec($httph);
    curl_close($httph);
    return $rst;
  }

  function proxy_get_sign($arr,$codepay_key) {
    $sign = '';//初始化
    foreach ($arr AS $key => $val) { //遍历POST参数
         if ($val == '' || $key == 'sign') continue; //跳过这些不签名
         if ($sign) $sign .= '&'; //第一个字符串签名不加& 其他加&连接起来参数
         $sign .= "$key=$val"; //拼接为url参数形式
    }
    return md5($sign . $codepay_key);
  }
  
  public function lovgardenPagePay(){
      $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
      $payRequestBuilder->setBody($this->body);
      $payRequestBuilder->setSubject($this->subject);
      $payRequestBuilder->setTotalAmount($this->total_amount);
      $payRequestBuilder->setOutTradeNo($this->out_trade_no);
      
      
      $aop = new \AlipayTradeService($this->config);
      $response = $aop->pagePay($payRequestBuilder,$this->config['return_url'],$this->config['notify_url']);
      
   
      
      $header = '<html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><title>支付</title></head>';
      //echo $header;
      var_dump($header.$response);
  }

  
  //设置alipay的return 方法
  public function lovgarden_return_url($arr) {
      //$arr=$_GET;
      $alipaySevice = new \AlipayTradeService($this->config);
      $result = $alipaySevice->check($arr);
      if($result) {//验证成功
          redirect('/user/pay_success');
     }
     else {
         //验证失败
         echo "验证失败";
     }
  }
  
  //设置alipay的通知函数
  public function lovgarden_notify_url($arr){
      //file_put_contents('/a.txt', '1-2', FILE_APPEND);
      $alipaySevice = new \AlipayTradeService($this->config);
      $alipaySevice->writeLog(var_export($_POST,true));
      $result = $alipaySevice->check($arr);
      if($result) {
          //file_put_contents('/a.txt', '2', FILE_APPEND);
          $out_trade_no = $_POST['out_trade_no'];
          $trade_no = $_POST['trade_no'];
          $trade_status = $_POST['trade_status'];
          
          if($_POST['trade_status'] == 'TRADE_FINISHED') {

		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
			//如果有做过处理，不执行商户的业务程序
				
		//注意：
		//退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
           }
           else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
		//判断该笔订单是否在商户网站中已经做过处理
			//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
			//请务必判断请求时的total_amount与通知时获取的total_fee为一致的
			//如果有做过处理，不执行商户的业务程序			
		//注意：
		//付款完成后，支付宝系统发送该交易状态通知
                // file_put_contents('/a.txt', '3', FILE_APPEND);
               //file_put_contents('/a.txt', '1', FILE_APPEND);
                $order_id = $_POST['out_trade_no']; //需要充值的ID 或订单号 或用户名
                if(strpos($order_id,"A2018") !== FALSE){
                    //Api订单
                  // file_put_contents('/a.txt', '5', FILE_APPEND);
                   $array_post = array(
                       'gmt_create' => $_POST['gmt_create'],
                       'receipt_amount' => $_POST['receipt_amount'],
                       'buyer_pay_amount' => $_POST['buyer_pay_amount'],
                       'total_amount' => $_POST['total_amount'],
                       'seller_id' => $_POST['seller_id'],
                       'gmt_payment' => $_POST['gmt_payment'],
                       'notify_time' => $_POST['notify_time'],
                       'out_trade_no' => $_POST['out_trade_no'],
                   );
                   $codepay_key="KrpiUmSEr2pvaGa46tcwqajSGK8NRw7I1";
                   $sign = $this->proxy_get_sign($array_post,$codepay_key);
                   $array_post['sign'] = $sign;
                   $result_post = $this->proxy_post('https://www.flowerideas.cn/api/pay/notify_url', $array_post);
                   //file_put_contents('/a.txt', $result_post, FILE_APPEND);
                   if(strpos($result_post,'success') !== FALSE){
                       //efile_put_contents('/a.txt','6',FILE_APPEND);
                       return "success"; 
                       exit();
                   }
                   else {
                       $log = 'gmt_payment:'.$array_post['gmt_payment'].'||'.'receipt_amount:'.$array_post['receipt_amount'].'||'.'out_trade_no:'.$array_post['out_trade_no'].'||'.'trade_no:'.$_POST['trade_no'];
                       file_put_contents("/app/logs/api_order_log.txt", $log.PHP_EOL, FILE_APPEND);
                       exit();
                   }
                }else {
                    $receipt_amount = (float)$_POST['receipt_amount']; //实际付款金额
                    $total_amount = (float)$_POST['total_amount']; //订单的原价
                    $buyer_pay_amount = (float)$_POST['buyer_pay_amount'];
                    $trade_no = $_POST['trade_no'];
                    $buyer_id = $_POST['buyer_id'];
                    $gmt_create = $_POST['gmt_create'];
                    $gmt_payment = $_POST['gmt_payment'];
                    //站点业务逻辑:需要更新订单状态到已支付


                    //$pay_id = '18040314555687237';
                    //file_put_contents('/a.txt', '4', FILE_APPEND);
                    $sql_pay = "UPDATE lovgarden_order SET 
                                gmt_payment = '$gmt_payment',
                                gmt_create = '$gmt_create',
                                buyer_id = '$buyer_id',
                                out_trade_no = '$order_id',
                                receipt_amount = $receipt_amount,
                                buyer_pay_amount = $buyer_pay_amount,
                                total_amount = $total_amount,
                                trade_no = '$trade_no',
                                order_status = '2' WHERE order_id = '$order_id'";
                    //file_put_contents('/a.txt', $sql_pay, FILE_APPEND);
                    $data_model = new Model();
                    $result = $data_model->execute($sql_pay);
                    if($result) {
                        // file_put_contents('/a.txt', '6', FILE_APPEND);
                    }
                    else {//返回成功 不要删除哦
                        // file_put_contents('/a.txt', '7', FILE_APPEND);
                        $log_file = './alipay_php/failed_order_id.txt';
                        file_put_contents($log_file, serialize($_POST).'-------', FILE_APPEND);
                    }
                }
           }
	   //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
	   //file_put_contents('/a.txt', 'done', FILE_APPEND);
           return "success";	//请不要修改或删除          
      }
      else {
          //验证失败
           return "fail";
      }
  }
}
