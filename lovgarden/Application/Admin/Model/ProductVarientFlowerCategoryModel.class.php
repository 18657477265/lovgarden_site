<?php
namespace Admin\Model;
use Think\Model;
class ProductVarientFlowerCategoryModel extends Model 
{
    //调用时候create方法允许接受的字段
    protected $insertFields = 'product_varient_id,flower_category_id';
    protected $updateFields = 'product_varient_id,flower_category_id';
    protected $_validate = array(
        array('product_varient_id','require','商品varient主键id必填',1),
        array('product_varient_id','/^[0-9]*$/','商品varient主键id必须为数字',1),
        array('flower_category_id','require','产地id必须',1),
        array('flower_category_id','/^[0-9]*$/','产地Id必须为数字',1),
    );
    
    public function product_varient_id_to_flower_category_id_add($product_varient_id,$flower_category_ids){
        foreach ($flower_category_ids as $key => $value) {
                //保存进lovgarden_product_varient_images表
                $array = array(
                    'product_varient_id' => $product_varient_id,
                    'flower_category_id' => $value,
                );
                $product_varient_id_to_flower_category_id = $this->create($array);
                $product_varient_id_to_flower_category_id_status = $this->add($product_varient_id_to_flower_category_id);
                if(!$product_varient_id_to_flower_category_id_status) {
                    return FALSE;
                }
        }
        return TRUE;
    }
    
    public function product_varient_id_to_flower_category_id_delete($product_varient_id,$delete_ids) {
        $result = TRUE;
        foreach ($delete_ids as $k=> $v){
            $row_status = $this->where(array(
                'product_varient_id' => $product_varient_id,
                'flower_category_id' => $v
            ))->delete();
            if($row_status === FALSE) {
                $result = FALSE;
                break;
            }
        }
        return $result;
    }
    
    public function product_varient_id_to_flower_category_id_update($product_varient_id,$new_flower_category_ids=array()){
        //先查出原来$product_varient_id对应的flower_category_id保存到一个数组里
        $add_status = TRUE;
        $delete_status = TRUE;
        $sql = "SELECT flower_category_id FROM lovgarden_product_varient_flower_category WHERE product_varient_id = '$product_varient_id'";
        $old_category_ids = $this->query($sql);
        //整理组织查出来的数据，便于比对
        $old_category_ids_fix = array(); 
        foreach($old_category_ids as $key => $value) {
            $old_category_ids_fix[] = $value['flower_category_id'];
        }
        //交叉比对这次提交上来的数据和老的数据
        //1 循环新提交上来的数据，如果不在老的数据里，说明是要新加的
        //2 循环老的数据，如果不在新的数据里，说明要删除
        $array_add_ones = array();
        $array_delete_ones = array();
        foreach($new_flower_category_ids as $key => $value) {
            if(!in_array($value, $old_category_ids_fix)) {
                //说明是要新增的flower_category_id
                $array_add_ones[] = $value;
            }
        }
        foreach($old_category_ids_fix as $key => $value) {
            if(!in_array($value, $new_flower_category_ids)) {
                //说明是要删除的flower_category_id
                $array_delete_ones[] = $value;
            }
        }     
        if(!empty($array_add_ones)) {
            $add_status = $this->product_varient_id_to_flower_category_id_add($product_varient_id, $array_add_ones);
        }
        if(!empty($array_delete_ones)) {
            $delete_status = $this->product_varient_id_to_flower_category_id_delete($product_varient_id,$array_delete_ones);
        }
        if($add_status && $delete_status) {
            //表示数据更新成功
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
}












