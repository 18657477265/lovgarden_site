<?php
namespace Api\Controller;
use Think\Controller\RestController;
use Think\Cache\Driver\Memcache;
use Api\Model\WxpayModel;
use SimpleXMLElement;

class LikeController extends RestController {
   public function addXmlToMemory() {
       //启动任务和每周定时任务,将xml文件中的数据读入到内存中去
   }
   public function addLikes() {
       //操作内存中的数据
   }
   public function removeLikes() {
       //操作内存中的数据
   }
   public function addMemoryToXml(){
       //每天定时任务,将最新的内存中的数组写入到xml文件中去
       
   }
   public function makeXml() {
       //将数据库里面的数据制作成本地XML文件
       $sql = "select id , sku_id , varient_name from lovgarden_product_varient";
       $model = new \Think\Model();
       $data = $model->query($sql);
       $products = translate_database_result_to_logic_array($data,array(),'id');
       
       $xmlTag = array('id','sku_id','varient_name','likes');
       $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><products />');
       foreach($products as $item) {
          $product = $xml->addChild('product');
          foreach($xmlTag as $x) {
            if($x == 'likse') {
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
