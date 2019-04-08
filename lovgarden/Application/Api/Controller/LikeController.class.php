<?php
namespace Api\Controller;
use Think\Controller\RestController;
use Think\Cache\Driver\Memcache;
use Api\Model\WxpayModel;
use SimpleXMLElement;

class LikeController extends RestController {
   public function addXmlToMemory() {
       //启动任务和每周定时任务,将xml文件中的数据读入到内存中去
       $ip = getIP();
       if($ip == '127.0.0.1' || $ip == '47.98.216.142' || $ip == '172.16.207.38') {
         $xmlTag = array('id','sku_id','varient_name','likes');
         $products = array();
         $xml = simplexml_load_file('/xmls/products_likes.xml');
         foreach($xml->children() as $product) {
           $item = get_object_vars($product);//获取对象全部属性，返回数组
           $products[$item['id']] = $item;
         }
         $mem_cache = new Memcache();
         $mem_cache->set('likes_products',$products,604800);
         file_put_contents('/xmls/cron_products_likes.log',date('Y-m-d H:i:s',time())." Add products_likes.xml To Memory".PHP_EOL,FILE_APPEND);
       }
   }
   public function addLikes($id) {
       //操作内存中的数据
       $mem_cache = new Memcache();
       $products = $mem_cache->get('likes_products');
       $products[$id]['likes'] = $products[$id]['likes'] + 1;
       $mem_cache = new Memcache();
       $mem_cache->set('likes_products',$products,604800);
   }
   public function removeLikes($id) {
       //操作内存中的数据
       $mem_cache = new Memcache();
       $products = $mem_cache->get('likes_products');
       $products[$id]['likes'] = $products[$id]['likes'] - 1;
       $mem_cache = new Memcache();
       $mem_cache->set('likes_products',$products,604800);
   }
   public function addMemoryToXml(){
       //每天定时任务,将最新的内存中的数组写入到xml文件中去
       $ip = getIP();
       if($ip == '127.0.0.1' || $ip == '47.98.216.142' || $ip == '172.16.207.38') {
         $mem_cache = new Memcache();
         $products = $mem_cache->get('likes_products');
         $xmlTag = array('id','sku_id','varient_name','likes');
         $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><products />');
         foreach($products as $item) {
            $product = $xml->addChild('product');
            foreach($xmlTag as $x) {
                $product->addChild($x, $item[$x]);
            }
         }
         $xml->asXml('/xmls/products_likes.xml');
         file_put_contents('/xmls/cron_products_likes.log',date('Y-m-d H:i:s',time())." Add Product_likes Memory To products_likes.xml".PHP_EOL,FILE_APPEND);
       }
   }
   public function makeXml() {
       //将数据库里面的数据制作成本地XML文件,数据初始化
       $ip = getIP();
       if($ip == '127.0.0.1' || $ip == '47.98.216.142' || $ip == '172.16.207.38') {
         $sql = "select id , sku_id , varient_name from lovgarden_product_varient";
         $model = new \Think\Model();
         $data = $model->query($sql);
         $products = translate_database_result_to_logic_array($data,array(),'id');
         $xmlTag = array('id','sku_id','varient_name','likes');
         $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><products />');
         foreach($products as $item) {
            $product = $xml->addChild('product');
            foreach($xmlTag as $x) {
              if($x == 'likes') {
                $product->addChild($x, rand(100,1000));
              }
              else {
                $product->addChild($x, $item[$x]);
              }
            }
         }
         $xml->asXml('/xmls/products_likes.xml');
       }
   }
}
