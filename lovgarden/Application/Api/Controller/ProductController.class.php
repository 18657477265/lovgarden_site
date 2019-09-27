<?php
namespace Api\Controller;
use Think\Controller\RestController;
use Think\Cache\Driver\Memcache;
class ProductController extends RestController {
   public function show($sku_id = '0') {
//首先要确认这个sku_id是不是一个有效的sku_id，无效的话返回404
        $model = new \Think\Model();
        $sku_id = I('get.sku_id');
        
        $mem_cache = new Memcache();
        $result_rows_array = $mem_cache->get('products'.$sku_id);
        if(empty($result_rows_array)) {
        
          $sku_id_real = $model->query("SELECT sku_id FROM lovgarden_product_varient WHERE sku_id = '$sku_id'");
          $result_rows_array = '';
          if(!empty($sku_id_real)) {
            //根据传入的sku_id通过算法获取该sku_id的相关产品信息，并传到页面中去
            $sku_id = $sku_id_real[0]['sku_id'];
            $sku_ids = get_related_products($sku_id);
            $sku_ids = implode(',', $sku_ids);       
            $sql = "SELECT a.id,a.sku_id,a.varient_name,a.wait_days,a.decoration_names,a.varient_summary,a.varient_body,a.varient_status,a.varient_price,a.decoration_level,a.vase,b.`image_url`,c.`flower_home_id`,d.`hurry_level_id`,f.`flower_type_id`,g.`flower_name` FROM lovgarden_product_varient AS a
                    LEFT JOIN lovgarden_product_varient_images AS b ON a.`id`=b.`product_varient_id`
                    LEFT JOIN lovgarden_product_varient_flower_home AS c ON a.`id`=c.`product_varient_id`
                    LEFT JOIN lovgarden_product_varient_hurry_level AS d ON a.`id`=d.`product_varient_id`
                    LEFT JOIN lovgarden_product_varient_flower_type AS f ON a.`id`=f.`product_varient_id`
                    LEFT JOIN lovgarden_flower_type AS g ON f.`flower_type_id`=g.`id`
                    WHERE a.`sku_id` IN ($sku_ids) ;";

            $result_rows = $model->query($sql);
            $multiple_fileds_array = array('image_url','flower_home_id','hurry_level_id','flower_type_id','flower_name');    
            $result_rows_array = translate_database_result_to_logic_array($result_rows,$multiple_fileds_array,'sku_id');
            
            $mem_cache->set('products'.$sku_id,$result_rows_array,86400);
          }
        }
       echo json_encode(array(
            'products' => $result_rows_array,
            'sku_id' => $sku_id
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }  
   public function select_list() {
       $where = array();
       
       $mem_cache = new Memcache();
       $filter_key = 'select_list';
       
       if(!empty($_GET['filters'])){
            //echo "<pre>";
            //print_r(array(5,6));
            //print_r(json_decode($_GET['filters']));
            //echo "</pre>";
            $filters_object = json_decode($_GET['filters']);
            if(!empty($filters_object->hurry_level)){
               $where['hurrylevel.id'] = array('IN', $filters_object->hurry_level);
               $filter_key = $filter_key.implode('_', $filters_object->hurry_level);
            }
            if(!empty($filters_object->flower_type)){
               $where['flowertype.id'] = array('IN', $filters_object->flower_type);
               $filter_key = $filter_key.implode('_', $filters_object->flower_type);
            }
            if(!empty($filters_object->flower_occasion)){
               $where['floweroccasion.id'] = array('IN', $filters_object->flower_occasion);
               $filter_key = $filter_key.implode('_', $filters_object->flower_occasion);
            }
            if(!empty($filters_object->flower_color)){
               $where['flowercolor.id'] = array('IN', $filters_object->flower_color);
               $filter_key = $filter_key.implode('_', $filters_object->flower_color);
            }
       }
       $block = $mem_cache->get('select_list_header_block');
       if(empty($block)) {
         $model = new \Think\Model();
         $sql_block = 'SELECT image_pc,block_title,block_body,block_link_title FROM lovgarden_block WHERE page_link = "/product/select_list"';
         $block = $model->query($sql_block);
         $mem_cache->set('select_list_header_block',$block,86400);
       }
       
       
    
       //$where = array();
       $where['product.varient_status'] = array('EQ','1');
       $filter_key = $filter_key.'_1';
       //echo $filter_key;
       //exit();
       $product_varients_info = $mem_cache->get($filter_key);
       if(empty($product_varients_info)) {
       
         $product_model = D('Admin/ProductVarient');
         //echo "<pre>";
         //print_r($where);
         //echo "</pre>";
         //exit();
         $order = 'product.sku_id asc';
         //$product_varients = '';
         $product_varients = $product_model->alias('product')
                           ->join('LEFT JOIN lovgarden_product_varient_images AS images ON product.id = images.product_varient_id')
                           ->join('LEFT JOIN lovgarden_product_varient_hurry_level AS hurrylevelid ON product.id = hurrylevelid.product_varient_id')
                           ->join('LEFT JOIN lovgarden_hurry_level AS hurrylevel ON hurrylevelid.hurry_level_id = hurrylevel.id')
                           ->join('LEFT JOIN lovgarden_product_varient_flower_type AS flowertypeid ON product.id = flowertypeid.product_varient_id')
                           ->join('LEFT JOIN lovgarden_flower_type AS flowertype ON flowertypeid.flower_type_id = flowertype.id')
                           ->join('LEFT JOIN lovgarden_product_varient_flower_occasion AS floweroccasionid ON product.id = floweroccasionid.product_varient_id')
                           ->join('LEFT JOIN lovgarden_flower_occasion AS floweroccasion ON floweroccasionid.flower_occasion_id = floweroccasion.id')
                           ->join('LEFT JOIN lovgarden_product_varient_flower_color AS flowercolorid ON product.id = flowercolorid.product_varient_id')
                           ->join('LEFT JOIN lovgarden_flower_color AS flowercolor ON flowercolorid.flower_color_id = flowercolor.id')
                           ->field('product.id , product.sku_id , product.varient_name , product.varient_summary , product.varient_price , images.`image_url` , hurrylevelid.hurry_level_id , hurrylevel.hurry_level,flowertypeid.flower_type_id,flowertype.flower_name,floweroccasionid.flower_occasion_id,floweroccasion.flower_occasion,flowercolorid.flower_color_id,flowercolor.flower_color')
                           ->where($where)
                           ->order($order)
                           ->select(); 
         $product_varients_info = translate_database_result_to_logic_array($product_varients,array('image_url','hurry_level_id','hurry_level','flower_type_id','flower_name','flower_occasion_id','flower_occasion','flower_color_id','flower_color'),'sku_id');
         $mem_cache->set($filter_key,$product_varients_info,86400);
       }
       
       $filters_options_array = $mem_cache->get('select_list_filters_options_array');
       if(empty($filters_options_array)) {
         $filters_options_array = array();
         $model2 = new \Think\Model();
         $sql = 'select id, hurry_level from lovgarden_hurry_level';
         $hurry_level_original = $model2->query($sql);
         $hurry_level_original = translate_database_result_to_logic_array($hurry_level_original,array(),'id');
         $filters_options_array['hurry_level'] = $hurry_level_original;
       
         $sql = 'select id, flower_name from lovgarden_flower_type';
         $flower_type_original = $model2->query($sql);
         $flower_type_original = translate_database_result_to_logic_array($flower_type_original,array(),'id');
         $filters_options_array['flower_type'] = $flower_type_original;
       
         $sql = 'select id, flower_occasion from lovgarden_flower_occasion';
         $flower_occasion_original = $model2->query($sql);
         $flower_occasion_original = translate_database_result_to_logic_array($flower_occasion_original,array(),'id');
         $filters_options_array['flower_occasion'] = $flower_occasion_original;
       
         $sql = 'select id, flower_color from lovgarden_flower_color';
         $flower_color_original = $model2->query($sql);
         $flower_color_original = translate_database_result_to_logic_array($flower_color_original,array(),'id'); 
         $filters_options_array['flower_color'] = $flower_color_original;
         $mem_cache->set('select_list_filters_options_array',$filters_options_array,86400);
       }
       echo json_encode(array(
            'block' => $block[0],
            'products' => $product_varients_info,
            'hurry_level' => $filters_options_array['hurry_level'],
            'flower_type' => $filters_options_array['flower_type'],
            'flower_occasion' => $filters_options_array['flower_occasion'],
            'flower_color' => $filters_options_array['flower_color']
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE); 
   }
   public function getProductsInfo($sku_ids) {
        $model = new \Think\Model();
        $sku_ids = I('get.sku_ids');
        //echo $sku_ids;
        //exit();
        if(!empty($sku_ids)) {
            //根据传入的sku_id通过算法获取该sku_id的相关产品信息，并传到页面中去
            //$sku_ids = implode(',', $sku_ids);  
            $mem_cache = new Memcache();
            $result_rows_array = $mem_cache->get('getProductsInfo'.$sku_ids);
            if(empty($result_rows_array)) {
                    
            $sql = "SELECT a.id,a.sku_id,a.varient_name,a.varient_summary,a.varient_body,a.varient_status,a.varient_price,a.decoration_level,a.vase,b.`image_url`,c.`flower_home_id`,d.`hurry_level_id`,e.`flower_home` FROM lovgarden_product_varient AS a
                    LEFT JOIN lovgarden_product_varient_images AS b ON a.`id`=b.`product_varient_id`
                    LEFT JOIN lovgarden_product_varient_flower_home AS c ON a.`id`=c.`product_varient_id`
                    LEFT JOIN lovgarden_flower_home AS e ON c.`flower_home_id`= e.`id`
                    LEFT JOIN lovgarden_product_varient_hurry_level AS d ON a.`id`=d.`product_varient_id`
                    WHERE a.`sku_id` IN ($sku_ids) ;";

              $result_rows = $model->query($sql);
              $multiple_fileds_array = array('image_url','flower_home_id','hurry_level_id','flower_home');  
              $result_rows_array = translate_database_result_to_logic_array($result_rows,$multiple_fileds_array,'sku_id');
            
              //$mem_cache = new Memcache();
              $mem_cache->set('getProductsInfo'.$sku_ids,$result_rows_array,86400);
            }
            
            $likes_products = $mem_cache->get('likes_products');
            if($likes_products) {
                foreach ($result_rows_array as $key => $value) {
                    $result_rows_array[$key]['likes'] = $likes_products[$key]['likes'];
                }
            }
        }
       echo json_encode(array(
            'products' => $result_rows_array,
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }
   public function getProductsAndArticles($all_ids = '') {
       $viewed_objects = array();
       if(!empty($all_ids)) {
         $all_ids = explode(',', $all_ids);
         $product_pattern = '/^\d{6,7}$/';
         $article_pattern = '/^\d{2,3}$/';
         $mem_cache = new Memcache();
         $all_articles = $mem_cache->get("AllArticles");
         $all_products = $mem_cache->get("AllProducts");
         if(empty($all_articles) || empty($all_products)) {       
           $model = new \Think\Model();
           $product_sql = "SELECT a.id,a.sku_id,a.varient_name,a.varient_summary,a.varient_status,a.varient_price,a.decoration_level,a.vase,b.`image_url`,c.`flower_home_id`,d.`hurry_level_id`,e.`flower_home` FROM lovgarden_product_varient AS a
                 LEFT JOIN lovgarden_product_varient_images AS b ON a.`id`=b.`product_varient_id`
                 LEFT JOIN lovgarden_product_varient_flower_home AS c ON a.`id`=c.`product_varient_id`
                 LEFT JOIN lovgarden_flower_home AS e ON c.`flower_home_id`= e.`id`
                 LEFT JOIN lovgarden_product_varient_hurry_level AS d ON a.`id`=d.`product_varient_id`
               ;";
           $result_rows = $model->query($product_sql);
           $multiple_fileds_array = array('image_url','flower_home_id','hurry_level_id','flower_home');
           $all_products = translate_database_result_to_logic_array($result_rows,$multiple_fileds_array,'sku_id');
         
           $articleModel = D('Article');
           $where['article_publish'] = array('EQ','1');
           $where['article_category'] = array('EQ','9');
           $articles_array = $articleModel->field('id,article_title,article_summary,article_category,banner_image')->where($where)->select();
           $all_articles = translate_database_result_to_logic_array($articles_array,array(),'id');
         
           //将数据存入缓存
           $mem_cache->set('AllProducts',$all_products,86400);
           $mem_cache->set('AllArticles',$all_articles,86400);
         }
         $products = array();
         $articles = array();
         
         //收集articles
         foreach ($all_articles as $item_id => $item_article ) {
             if(in_array($item_id, $all_ids)) {
                 $articles[$item_id] = $item_article;
             }
         }
         //收集products
         foreach ($all_products as $item_sku_id => $item_product) {
             if(in_array($item_sku_id, $all_ids)) {
                 $products[$item_sku_id] = $item_product;
             }
         }
         //数据排序整理
         foreach ($all_ids as $value) {
             if(preg_match($product_pattern, $value)) {
                 $viewed_objects[$value]["type"] = "product"; 
                 $viewed_objects[$value]["object"] = $products[$value];
             }
             elseif(preg_match($article_pattern, $value)) {
                 $viewed_objects[$value]["type"] = "article";
                 $viewed_objects[$value]["object"] = $articles[$value];
             }
         }
       }
       echo json_encode(array(
            'viewedObjects' => $viewed_objects,
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);      
   }
}
