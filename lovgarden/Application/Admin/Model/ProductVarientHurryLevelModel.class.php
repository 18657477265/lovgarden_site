<?php
namespace Admin\Model;
use Think\Model;
class ProductVarientHurryLevelModel extends Model 
{
    //调用时候create方法允许接受的字段
    protected $insertFields = 'product_varient_id,hurry_level_id';
    protected $updateFields = 'product_varient_id,hurry_level_id';
    protected $_validate = array(
        array('product_varient_id','require','商品varient主键id必填',1),
        array('product_varient_id','/^[0-9]*$/','商品varient主键id必须为数字',1),
        array('hurry_level_id','require','配送缓急id必须',1),
        array('hurry_level_id','/^[0-9]*$/','配送缓急Id必须为数字',1),
    );
    
    public function product_varient_id_to_hurry_level_id_add($product_varient_id,$hurry_level_ids){
        foreach ($hurry_level_ids as $key => $value) {
                //保存进lovgarden_product_varient_hurry_level表
                $array = array(
                    'product_varient_id' => $product_varient_id,
                    'hurry_level_id' => $value,
                );
                $product_varient_id_to_hurry_level_id = $this->create($array);
                $product_varient_id_to_hurry_level_id_status = $this->add($product_varient_id_to_hurry_level_id);
                if(!$product_varient_id_to_hurry_level_id_status) {
                    return FALSE;
                }
        }
        return TRUE;
    }
}












