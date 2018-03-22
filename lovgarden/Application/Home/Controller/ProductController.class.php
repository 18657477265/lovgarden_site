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
        $sub_sql = '';
        $where['product.varient_status'] = array('EQ','1');
        $order = 'product.sku_id asc';
        $product_varients = '';
        //凭借获得的url查询条件整理出一个where
        $current_url_conditions = get_filter_conditions();
        if(!empty($current_url_conditions)) {
            foreach ($current_url_conditions as $filter_type_alias => $options) {
                if($filter_type_alias == 'hurryLevel') {
                   $where['hurrylevel.id'] = array('IN',$options); 
                }
                elseif($filter_type_alias == 'flowerType') {
                   $where['flowertype.id'] = array('IN',$options); 
                }
                elseif($filter_type_alias == 'flowerOccasion') {
                   $where['floweroccasion.id'] = array('IN',$options); 
                }
                elseif($filter_type_alias == 'flowerColor') {
                   $where['flowercolor.id'] = array('IN',$options); 
                }
            }
            $sub_sql = $product_model->alias('product')
                        ->join('LEFT JOIN lovgarden_product_varient_images AS images ON product.id = images.product_varient_id')
                        ->join('LEFT JOIN lovgarden_product_varient_hurry_level AS hurrylevelid ON product.id = hurrylevelid.product_varient_id')
                        ->join('LEFT JOIN lovgarden_hurry_level AS hurrylevel ON hurrylevelid.hurry_level_id = hurrylevel.id')
                        ->join('LEFT JOIN lovgarden_product_varient_flower_type AS flowertypeid ON product.id = flowertypeid.product_varient_id')
                        ->join('LEFT JOIN lovgarden_flower_type AS flowertype ON flowertypeid.flower_type_id = flowertype.id')
                        ->join('LEFT JOIN lovgarden_product_varient_flower_occasion AS floweroccasionid ON product.id = floweroccasionid.product_varient_id')
                        ->join('LEFT JOIN lovgarden_flower_occasion AS floweroccasion ON floweroccasionid.flower_occasion_id = floweroccasion.id')
                        ->join('LEFT JOIN lovgarden_product_varient_flower_color AS flowercolorid ON product.id = flowercolorid.product_varient_id')
                        ->join('LEFT JOIN lovgarden_flower_color AS flowercolor ON flowercolorid.flower_color_id = flowercolor.id')
                        ->field('product.id')
                        ->where($where)
                        ->group('product.id')
                        ->buildSql();
               
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
                                ->where(['product.id'=>['exp', 'in ('.$sub_sql.')']])
                                ->order($order)
                                ->select();   
        }
        else {
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
        }
  
        //将查询到的数据整理成类似对象的数组形式
        $product_varients_info = translate_database_result_to_logic_array($product_varients,array('image_url','hurry_level_id','hurry_level','flower_type_id','flower_name','flower_occasion_id','flower_occasion','flower_color_id','flower_color'),'sku_id');
        //提取查询到的结果中所包含的属性条件作为前端筛选条件
        $filters_available = get_available_filters($product_varients_info);

        //获取hurry level , flower color , flower type , occassion 的对照表
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
//        echo "<pre>";
//        header("Content-type:text/html;charset=utf-8");
//        print_r($hurry_level_original);
//        print_r($flower_type_original);
//        print_r($flower_occasion_original);
//        print_r($flower_color_original);
//        echo "</pre>";
//        exit();      
        $this->assign(array(
            'block' => $block[0],
            'product_varients_info' => $product_varients_info,
            'filters_available' => $filters_available,
            'current_url_conditions' => $current_url_conditions,
            'hurry_level_original' => $hurry_level_original,
            'flower_type_original' => $flower_type_original,
            'flower_occasion_original' => $flower_occasion_original,
            'flower_color_original' => $flower_color_original
        ));
        $this->display('product_list');
        
    }
    
    //点击添加到购物车时候出发的ajax请求
    public function ajax_add_to_cart(){
        sleep(1);
        if(!empty(session('custom_id'))){
          echo '1';
        }
        else {
          echo '2';
        }
    }
}