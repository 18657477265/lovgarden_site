<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;
class ProductController extends Controller {
    public function add(){
        if(IS_POST) {
            $model = D('ProductVarient');
            //开启事务处理机制，为了保证数据一致性
            $model->startTrans();
            $result = $model->product_varient_add();
            $check_status = $result['report'];
            $data = $result['data'];
            $multipleSelectData = $result['multipleSelectData'];
            if($check_status == 'insert_into_product_varient_ok') {
                //上传图片 
                $new_product_varient_id = $result['new_product_varient_id'];
                $product_varient_id_to_images_model = D('ProductVarientImages');
                $images_upload_status = $product_varient_id_to_images_model->product_varient_id_to_images_add($new_product_varient_id);
                if($images_upload_status) {
                   //如果图片上传成功并成功和product_varient_id对应,那么开始建立product_varient_id和所有多选属性的关系
                    $product_varient_flower_color_model = D('ProductVarientFlowerColor');
                    $product_varient_flower_home_model = D('ProductVarientFlowerHome');
                    $product_varient_flower_occassion_model = D('ProductVarientFlowerOccasion');
                    $product_varient_flower_type_model = D('ProductVarientFlowerType');
                    $product_varient_hurry_level_model = D('ProductVarientHurryLevel');
                    //将属性ID和新生成的product_varient_id进行关联
                    $attribute_flower_color_check = $product_varient_flower_color_model->product_varient_id_to_flower_color_id_add($new_product_varient_id,$_POST['flower_color']);
                    $attribute_flower_home_check = $product_varient_flower_home_model->product_varient_id_to_flower_home_id_add($new_product_varient_id,$_POST['flower_home']);
                    $attribute_flower_occasion_check = $product_varient_flower_occassion_model->product_varient_id_to_flower_occasion_id_add($new_product_varient_id,$_POST['flower_occasion']);
                    $attribute_flower_type_check = $product_varient_flower_type_model->product_varient_id_to_flower_type_id_add($new_product_varient_id,$_POST['flower_name']);
                    $attribute_hurry_level_check = $product_varient_hurry_level_model->product_varient_id_to_hurry_level_id_add($new_product_varient_id,$_POST['hurry_level']);
                    if($attribute_flower_color_check && $attribute_flower_home_check && $attribute_flower_occasion_check && $attribute_flower_type_check && $attribute_hurry_level_check) {
                        //所有检查通过，那么最终完成了一个product_varient和所有属性的添加,并完成事务
                        $model->commit();
                        $this->redirect('Success/success');
                    }
                    else {
                        $model->rollback();
                        $this->redirect('Success/failure');
                    }
                }else {
                    $model->rollback();
                    $this->redirect('Success/failure');
                }               
            }
            elseif ($check_status == 'insert_into_product_varient_error') {
                $model->rollback();
                //输出失败原因
                $this->assign(array(
                   'errorMessage' => $result['error_message'],
                   'data' => $data,
                   'multipleSelectData' => $multipleSelectData
                ));
                $this->display('product_detail');
            }
            else {
                $model->rollback();
                $this->assign(array(
                   'errorMessage' => $check_status,
                   'data' => $data,
                   'multipleSelectData' => $multipleSelectData
                ));
                $this->display('product_detail');
            }
        }
        else {
          $this->display('product_detail');
        }
    }
    public function product_list($varient_status='',$vase='',$decoration_level='',$varient_name_or_sku_id='') {
        //分页输出product_varient_list
        //exit();
        
        
        $model = D('ProductVarient'); // 实例化User对象
        
        $a=$model->search('1','1','1','野',0,2);
        header("Content-type:text/html;charset=utf-8");
        echo "<pre>";
        print_r($a);
        echo "</pre>";
        exit();
        
        $count = $model->where("varient_status=1")->count();// 查询满足要求的总记录数
        $Page  = new \Think\Page($count,C('PRODUCT_VARIENT_PAGE')['page_count']);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $sql = "SELECT mf.sku_id , mf.varient_name , mf.varient_status , mf.varient_price , mf.decoration_level , mf.vase , df.`image_url` FROM lovgarden_product_varient AS mf LEFT JOIN 
lovgarden_product_varient_images AS df ON mf.`id`= df.`product_varient_id` WHERE mf.`varient_status`='1' group by mf.sku_id limit "."$Page->firstRow".","."$Page->listRows";
        $product_varients = $model->query($sql);
        $this->assign('product_varients',$product_varients);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display('product_list'); // 输出模板
    }
}