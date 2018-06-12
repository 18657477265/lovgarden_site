<?php
return array(
	//'配置项'=>'配置值'
	'HTML_CACHE_ON'     =>    false, // 开启静态缓存
	'HTML_CACHE_TIME'   =>    60,   // 全局静态缓存有效期（秒）
	'HTML_FILE_SUFFIX'  =>    '.shtml', // 设置静态缓存文件后缀
	'HTML_CACHE_RULES'  =>     array(  // 定义静态缓存规则
	   'Index:index' => array('index' => 86400),
        ),
        'MAIL_CONFIG' => array(
            'smtpdebug' => FALSE,
            'host' => 'smtp.163.com',
            'smtpsecure' => 'tls', 
            'port' => 587, 
            'smtpauth' =>TRUE,
            'username' => '18657477265@163.com',
            'password' => 'sherry418',
            'from' => '18657477265@163.com', 
            'address' => '1048290286@qq.com',
            'wordwrap' => 50,
            'ishtml' => TRUE,
            'charset' => 'utf-8'
        ),
        'URL_ROUTER_ON' => true, // 开启URL路由
        'URL_ROUTE_RULES' => array(
            array('message/:id','message/read'),
        )
);