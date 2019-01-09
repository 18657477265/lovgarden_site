<?php
namespace Api\Controller;
use Think\Controller\RestController;
use Think\Cache\Driver\Memcache;
class ProductController extends RestController {
   public function show($sku_id = '0') {
//首先要确认这个sku_id是不是一个有效的sku_id，无效的话返回404
        $model = new \Think\Model();
        $sku_id = I('get.sku_id');
        $sku_id_real = $model->query("SELECT sku_id FROM lovgarden_product_varient WHERE sku_id = '$sku_id'");
        $result_rows_array = '';
        if(!empty($sku_id_real)) {
            //根据传入的sku_id通过算法获取该sku_id的相关产品信息，并传到页面中去
            $sku_id = $sku_id_real[0]['sku_id'];
            $sku_ids = get_related_products($sku_id);
            $sku_ids = implode(',', $sku_ids);       
            $sql = "SELECT a.id,a.sku_id,a.varient_name,a.varient_summary,a.varient_body,a.varient_status,a.varient_price,a.decoration_level,a.vase,b.`image_url`,c.`flower_home_id`,d.`hurry_level_id` FROM lovgarden_product_varient AS a
                    LEFT JOIN lovgarden_product_varient_images AS b ON a.`id`=b.`product_varient_id`
                    LEFT JOIN lovgarden_product_varient_flower_home AS c ON a.`id`=c.`product_varient_id`
                    LEFT JOIN lovgarden_product_varient_hurry_level AS d ON a.`id`=d.`product_varient_id`
                    WHERE a.`sku_id` IN ($sku_ids) ;";

            $result_rows = $model->query($sql);
            $multiple_fileds_array = array('image_url','flower_home_id','hurry_level_id');    
            $result_rows_array = translate_database_result_to_logic_array($result_rows,$multiple_fileds_array,'sku_id');
        }
       echo json_encode(array(
            'products' => $result_rows_array,
            'sku_id' => $sku_id
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }  
   public function select_list() {
       $where = array();
       if(!empty($_GET['filters'])){
            //echo "<pre>";
            //print_r(array(5,6));
            //print_r(json_decode($_GET['filters']));
            //echo "</pre>";
            $filters_object = json_decode($_GET['filters']);
            if(!empty($filters_object->hurry_level)){
               $where['hurrylevel.id'] = array('IN', $filters_object->hurry_level);
            }
            if(!empty($filters_object->flower_type)){
               $where['flowertype.id'] = array('IN', $filters_object->flower_type);
            }
            if(!empty($filters_object->flower_occasion)){
               $where['floweroccasion.id'] = array('IN', $filters_object->flower_occasion);
            }
            if(!empty($filters_object->flower_color)){
               $where['flowercolor.id'] = array('IN', $filters_object->flower_color);
            }
       }
       $model = new \Think\Model();
       $sql_block = 'SELECT image_pc,block_title,block_body,block_link_title FROM lovgarden_block WHERE page_link = "/product/select_list"';
       $block = $model->query($sql_block);
       
       $product_model = D('Admin/ProductVarient');
       //$where = array();
       $where['product.varient_status'] = array('EQ','1');
       //echo "<pre>";
       //print_r($where);
       //echo "</pre>";
       //exit();
       $order = 'product.sku_id asc';
       $product_varients = '';
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
       $sql = 'select id, hurry_level from lovgarden_hurry_level';
       $hurry_level_original = $model->query($sql);
       $hurry_level_original = translate_database_result_to_logic_array($hurry_level_original,array(),'id');
       $sql = 'select id, flower_name from lovgarden_flower_type';
       $flower_type_original = $model->query($sql);
       $flower_type_original = translate_database_result_to_logic_array($flower_type_original,array(),'id');
       $sql = 'select id, flower_occasion from lovgarden_flower_occasion';
       $flower_occasion_original = $model->query($sql);
       $flower_occasion_original = translate_database_result_to_logic_array($flower_occasion_original,array(),'id');
       $sql = 'select id, flower_color from lovgarden_flower_color';
       $flower_color_original = $model->query($sql);
       $flower_color_original = translate_database_result_to_logic_array($flower_color_original,array(),'id'); 
       echo json_encode(array(
            'block' => $block[0],
            'products' => $product_varients_info,
            'hurry_level' => $hurry_level_original,
            'flower_type' => $flower_type_original,
            'flower_occasion' => $flower_occasion_original,
            'flower_color' => $flower_color_original
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
            $sql = "SELECT a.id,a.sku_id,a.varient_name,a.varient_summary,a.varient_body,a.varient_status,a.varient_price,a.decoration_level,a.vase,b.`image_url`,c.`flower_home_id`,d.`hurry_level_id` FROM lovgarden_product_varient AS a
                    LEFT JOIN lovgarden_product_varient_images AS b ON a.`id`=b.`product_varient_id`
                    LEFT JOIN lovgarden_product_varient_flower_home AS c ON a.`id`=c.`product_varient_id`
                    LEFT JOIN lovgarden_product_varient_hurry_level AS d ON a.`id`=d.`product_varient_id`
                    WHERE a.`sku_id` IN ($sku_ids) ;";

            $result_rows = $model->query($sql);
            $multiple_fileds_array = array('image_url','flower_home_id','hurry_level_id');    
            $result_rows_array = translate_database_result_to_logic_array($result_rows,$multiple_fileds_array,'sku_id');
        }
       echo json_encode(array(
            'products' => $result_rows_array,
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }
}
