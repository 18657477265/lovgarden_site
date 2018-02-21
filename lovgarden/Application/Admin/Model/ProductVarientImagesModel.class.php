<?php
namespace Admin\Model;
use Think\Model;
class ProductVarientImagesModel extends Model 
{
    //调用时候create方法允许接受的字段
    protected $insertFields = 'product_varient_id,image_url';
    protected $updateFields = 'product_varient_id,image_url';
    protected $_validate = array(
        array('product_varient_id','require','商品varient主键id必填',1),
        array('image_url','require','商品varient图片地址必填',1),
    );
    
    public function product_varient_id_to_images_add($product_varient_id){
        $upload_images = uploadImage('product_varient_images','product_varient');
        if($upload_images) {
            //$image_server_domain = C('IMAGE_CONFIG')['viewPath'];
            foreach ($upload_images as $key => $value) {
                $image_full_path = $value['savepath'].$value['savename'];
                //保存进lovgarden_product_varient_images表
                $array = array(
                    'product_varient_id' => $product_varient_id,
                    'image_url' => $image_full_path,
                );
                $product_varient_id_to_image_url_one = $this->create($array);
                $product_varient_id_to_images_status_id = $this->add($product_varient_id_to_image_url_one);
                if(!$product_varient_id_to_images_status_id) {
                    return FALSE;
                }
            }
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
}












