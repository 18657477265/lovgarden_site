<?php
return array(
	//'配置项'=>'配置值'
    'DB_TYPE' =>  'mysqli',
    //'DB_DSN'    => 'mysql:host=127.0.0.1;dbname=lovgarden;charset=utf8',
    'DB_USER' =>  'root',
    'DB_PWD' =>  'sherry418!',
    'DB_PORT' =>  '3306',
    'DB_PREFIX' =>  'lovgarden_',
    'DB_HOST' => '127.0.0.1',
    'DB_NAME' => 'lovgarden',
    'DB_CHARSET' => 'utf8',
    'DEFAULT_FILTER' => 'trim,htmlspecialchars',
    'TMPL_EXCEPTION_FILE' => './404.html',// 异常页面的模板文件
    'ERROR_PAGE' => './404.html', // 错误定向页面
    'URL_MODEL'  =>  2, 
    'URL_CASE_INSENSITIVE' => true,
    'MODULE_ALLOW_LIST' => array('Home','Admin'), 
    'APP_GROUP_LIST' => 'Home,Admin', //项目分组设定 
    'DEFAULT_GROUP' => 'Home', //默认分组
    'DATA_CACHE_TYPE' => 'Memcache',
    'MEMCACHE_HOST'  => 'tcp://127.0.0.1:11211',
    //'MEMCACHED_PORT' => array('11211'),
    'DATA_CACHE_TIME' => 7200,
    /************ 图片相关的配置 ***************/
    'IMAGE_CONFIG' => array(
    	'maxSize' => 1024*1024,
    	'exts' => array('jpg', 'gif', 'png', 'jpeg'), 
    	'rootPath' => './Public/Uploads/',  // 上传图片的保存路径  -> PHP要使用的路径，硬盘上的路径
    	//'viewPath' => '//local.lovgarden.com/Public/Uploads/',   // 显示图片时的路径    -> 浏览器用的路径，相对网站根目录
        'viewPath' => '//47.98.216.142/Public/Uploads/'
    ),
    /************分页配置***************/
    'PRODUCT_VARIENT_PAGE' => array(
         'page_count' => 10,  
    ), 
);