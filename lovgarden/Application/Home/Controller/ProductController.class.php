<?php
namespace Home\Controller;
use Think\Controller;
class ProductController extends Controller {
    public function show($sku_id){
       
        //根据传入的sku_id通过算法获取该sku_id的相关产品信息，并传到页面中去
        $sku_ids = get_related_products($sku_id);
        $sku_ids = implode(',', $sku_ids);       
        $sql = "SELECT a.id,a.sku_id,a.varient_name,a.varient_summary,a.varient_body,a.varient_status,a.varient_price,a.decoration_level,a.vase,b.`image_url`,c.`flower_home_id` FROM lovgarden_product_varient AS a
                LEFT JOIN lovgarden_product_varient_images AS b ON a.`id`=b.`product_varient_id`
                LEFT JOIN lovgarden_product_varient_flower_home AS c ON a.`id`=c.`product_varient_id`
                WHERE a.`sku_id` IN ($sku_ids) ;";
        $model = new \Think\Model();
        $result_rows = $model->query($sql);
        $multiple_fileds_array = array('image_url','flower_home_id');    
        $result_rows_array = translate_database_result_to_logic_array($result_rows,$multiple_fileds_array,'sku_id');
        $this->assign(array(
           'product_varients' => $result_rows_array,
           'current_sku_id' => $sku_id,
        ));
        $this->display('product_detail');
    }
    public function select_list() {
        
        $model = new \Think\Model();
        //取出头部block
        $sql_block = 'SELECT image_pc,block_title,block_body,block_link_title FROM lovgarden_block WHERE page_link = "/product/select_list"';
        $block = $model->query($sql_block);
        //取出所有上架商品
        $product_model = D('Admin/ProductVarient');
        $where = array();

        $where['product.varient_status'] = array('EQ','1');
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
        ->order('product.sku_id asc')
        ->select();   
        
        //将查询到的数据整理成类似对象的数组形式
        $product_varients_info = translate_database_result_to_logic_array($product_varients,array('image_url','hurry_level_id','hurry_level','flower_type_id','flower_name','flower_occasion_id','flower_occasion','flower_color_id','flower_color'),'sku_id');
        //提取查询到的结果中所包含的属性条件作为前端筛选条件
        $filters_available = get_available_filters($product_varients_info);  
        echo "<pre>";
        header("Content-type:text/html;charset=utf-8");
        print_r($filters_available);
        echo "</pre>";
        exit();
        $this->assign(array(
            'block' => $block[0],
            'product_varients_info' => $product_varients_info
        ));
        $this->display('product_list');
        
    }
}