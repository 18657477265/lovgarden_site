<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2018070660584041",

		//商户私钥
		'merchant_private_key' => "MIIEpQIBAAKCAQEA2ggqhYPVNrTiYy2btMinshaaGlqkSL4QooCK7t93/xVTvwTo+Mk/o3g1wAm8LZQeChljWEdzG/zCM0+FE3huCDmTvU8IUGszuZnJpw3AMqLlW4RtBrJuGvHa9/r+huxKkoTZvQuR1YsyAcER41FMk464CNTjHno7vVTSyDYXRUM+DX3SAbg+O7DAPKFIDk+VTq6Jl+/ck0uGVXDW8UguC2Xwu/gcnAu2KUeh3NS+yLwAc0PtWfPH9g9WWgN0uFxAkWbfiTCGUK1DEQAPhZJeg/a9wRzydsrPd+ar94DWgvIfaMyBMgdkenpyIMaas+W/yA6cN2PNugPJhw+hQlEAuwIDAQABAoIBAQDFVebGeQ+dOBI+maT39zRwZyyK9ccDX6NGsPkOQowk/3SQyyzhH6TNm5tqeGUtC4Y0tc3ItMJmblqGfk5/1Nwh7ZreGI35200xixOMc1GlgvH75tuW2B/3mzcIgs+j5nGIM12vUK1pjVZxaAF8sLSSSPYgaC44A4HWVtOAChT9xbahpHODwWlYctX6UyDCZjGUJCadGwmCjTWCKsyV01S4c2w8UqKbp4b7vVpNcPW39dKSgAtpfPEfDkEvGXYQYge8Cl1oirP5FUuy3xBiTHgIqZd3UE0FPVPk773tYT7vRMu2lz7v3va0MvF3sX5lm8cQ2u0Rwm1rPOCz8NVmRpABAoGBAPOyUjMONS75WjTF5FV+kwq20FLObR1uocLtO2KusX4ygpc/anEcsVmbYRlgkfwq0iTlAPWpis5VtMq3ArRQbg78mksShbLPkT6QB5Rlq6v/GOVNvdbmh9mCHaStMFAojKh67Y3JLpYgIXCH6usdeaUTqtyI3YZDEiyY1oUwzsIBAoGBAOUKI75nhLq6qeZMl9otyb9+5cfhlg2duFnGSxugc1IBAf0/uMgKl0XfjicSpWPai3eiI0fjeU/2BImuG/Qr0ZIBjMkAQbTqFUgOFmtFV4ngPDCYExzIVyd2QvccDVEX1F6XIF9uT6vUYERX87Umef+dk3TpItzE6blO5FKtNUq7AoGBAIHvHzLHrYWpP/aJWPBYt4/r33F3TOh3d1pWYOaB3HM7/TMlhdxffxQW65O7ULsSHc+8JmHVjwPq4KWBJLj9dWEaQC4s5wpq77da1h/CgeEH978zTzgI3IAVhzapfOwQYsbmHEkP7n3vDcVRQukvYw+oR96kPvD6S/NpXl/PoT4BAoGBAOFc8cC3EQW/B8/AS6Z1aU7QaP4c/M2XBD7pexvK682jijaKzaGfsishhjjyAuTWtGZZlkd1DvfbxalHNOAzgXkdp05bv0tpDNmiavLP/wt+JRtXd27Zvc/pcIi/BGdngCI2p7bezuvvA24b8IOtQVt/zAi8IP6Djso0Uzr6jTZjAoGAc7QVKXGtaqyuyzkCtA1n+aYpJd3HwD8miW+NdXJqWSlyrfX6oIfPvwkyZ+eOVdASywv+fJdIKaHUVTCToEpVmRjGD8mLliwrIErDXYuu3vFyPd3z7PUAGDaM/rmT/7ixuSKNdTocM7YFNJ7phZ+s5kZ1GR+L4z/3vSrbxSltQec=",
		
		//异步通知地址
		'notify_url' => "https://flowerideas.cn/alipay/alipay.trade.page.pay-PHP-UTF-8/notify_url.php",
		
		//同步跳转
		'return_url' => "https://flowerideas.cn/alipay/alipay.trade.page.pay-PHP-UTF-8/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEArsr9JijJkO8WCy+soVSKnEXHiotZl165qn6N6STtLYKkFxLJR++pA57JMC0lDy4h61mqMSuBhK9j9Q51pK4lkkym+WeUmjNbpr6cjoSqBntc2yac64tv9qJMUHLAMEBTL4G5s+GE9V++GTu0MOF8hE70YwdG7oS1NRdHLBtOSfh1jZ3X1cIeezs88XGJywpj4oEZjEjDDPTyMxhqnj2jrigFliqdq8IY5gT76iwYnHU9XKhJaF37mbGGX+bqS2EkiXKz+LKw2+T5MtZ1wbJqUGfAU45vvSsAu51dwDapKeR9k2hOUfc07+OoMuW2JxBzLZ/wnpdMPZxzgygE08GgEQIDAQAB",
);