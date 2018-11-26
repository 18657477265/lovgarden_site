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
            $sql = "SELECT a.id,a.sku_id,a.varient_name,a.varient_summary,a.varient_body,a.varient_status,a.varient_price,a.decoration_level,a.vase,b.`image_url`,c.`flower_home_id` FROM lovgarden_product_varient AS a
                    LEFT JOIN lovgarden_product_varient_images AS b ON a.`id`=b.`product_varient_id`
                    LEFT JOIN lovgarden_product_varient_flower_home AS c ON a.`id`=c.`product_varient_id`
                    WHERE a.`sku_id` IN ($sku_ids) ;";

            $result_rows = $model->query($sql);
            $multiple_fileds_array = array('image_url','flower_home_id');    
            $result_rows_array = translate_database_result_to_logic_array($result_rows,$multiple_fileds_array,'sku_id');
        }
       echo json_encode(array(
            'products' => $result_rows_array,
            'sku_id' => $sku_id
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }  
}
