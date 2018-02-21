<?php
namespace Admin\Model;
use Think\Model;
class ProductVarientFlowerHomeModel extends Model 
{
    //调用时候create方法允许接受的字段
    protected $insertFields = 'product_varient_id,flower_home_id';
    protected $updateFields = 'product_varient_id,flower_home_id';
    protected $_validate = array(
        array('product_varient_id','require','商品varient主键id必填',1),
        array('product_varient_id','/^[0-9]*$/','商品varient主键id必须为数字',1),
        array('flower_home_id','require','产地id必须',1),
        array('flower_home_id','/^[0-9]*$/','产地Id必须为数字',1),
    );
    
    public function product_varient_id_to_flower_home_id_add($product_varient_id,$flower_home_ids){
        foreach ($flower_home_ids as $key => $value) {
                //保存进lovgarden_product_varient_images表
                $array = array(
                    'product_varient_id' => $product_varient_id,
                    'flower_home_id' => $value,
                );
                $product_varient_id_to_flower_home_id = $this->create($array);
                $product_varient_id_to_flower_home_id_status = $this->add($product_varient_id_to_flower_home_id);
                if(!$product_varient_id_to_flower_home_id_status) {
                    return FALSE;
                }
        }
        return TRUE;
    }
}












