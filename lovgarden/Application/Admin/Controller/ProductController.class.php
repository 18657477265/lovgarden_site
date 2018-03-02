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
        $model = D('ProductVarient'); // 实例化User对象
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        /*$sql = "SELECT mf.sku_id , mf.varient_name , mf.varient_status , mf.varient_price , mf.decoration_level , mf.vase , df.`image_url` FROM lovgarden_product_varient AS mf LEFT JOIN 
lovgarden_product_varient_images AS df ON mf.`id`= df.`product_varient_id` WHERE mf.`varient_status`='$varient_status' group by mf.sku_id limit "."$Page->firstRow".","."$Page->listRows";
        $product_varients = $model->query($sql);*/
        //$this->assign('product_varients',$product_varients);// 赋值数据集
        //$this->assign('page',$show);// 赋值分页输出
        //$this->display('product_list'); // 输出模板
        $where = array();
        $filter_selection = array();
        if(!empty($varient_status) && $varient_status != 'all') {
            $where['mf.varient_status'] = array('EQ',$varient_status);
            $filter_selection['varient_status'] = $varient_status;
        }
        if(($vase == '1' || $vase == '0') && $vase != 'all') {
            $where['mf.vase'] = array('EQ',$vase);
            $filter_selection['vase'] = $vase;
        }
        if(!empty($decoration_level) && $decoration_level != 'all') {
            $where['mf.decoration_level'] = array('EQ',$decoration_level);
            $filter_selection['decoration_level'] = $decoration_level;
        }
        if(!empty($varient_name_or_sku_id)) {
            $where['_string'] = "( mf.sku_id like '%$varient_name_or_sku_id%')  OR ( mf.varient_name like '%$varient_name_or_sku_id%')";
            $filter_selection['varient_name_or_sku_id'] = $varient_name_or_sku_id;
        }
       
        $all_condiion_rows = $model->alias('mf')->join('LEFT JOIN lovgarden_product_varient_images AS df ON mf.id = df.product_varient_id')->field('mf.sku_id , mf.varient_name , mf.varient_status , mf.varient_price , mf.decoration_level , mf.vase , df.`image_url`')->where($where)->group('mf.sku_id')->select();// 查询满足要求的总记录数
        $count = count($all_condiion_rows);
        $Page  = new \Think\Page($count,C('PRODUCT_VARIENT_PAGE')['page_count']);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $Page->show();// 分页显示输出
        
        $product_varients = $model->alias('mf')->join('LEFT JOIN lovgarden_product_varient_images AS df ON mf.id = df.product_varient_id')->field('mf.id , mf.sku_id , mf.varient_name , mf.varient_status , mf.varient_price , mf.decoration_level , mf.vase , df.`image_url`')->where($where)->group('mf.sku_id')->limit($Page->firstRow,$Page->listRows)->select();
        $this->assign(array(
            'product_varients' => $product_varients,
            'page' => $show,
            'filter_selection' => $filter_selection
        ));// 赋值数据集
        $this->display('product_list'); // 输出模板
    }
    public function update($id='') {
        if(empty($id)){
            //跳转到列表页
             $this->redirect('Product/product_list');
        }
        else if(IS_POST) {
            //这里用来更新数据
          $product_varient_model = D("ProductVarient");
          $product_varient_model->startTrans();//为了保护数据一致性和安全性开启事务
          $result = $product_varient_model->product_varient_update($id);
          $check_status = $result['report'];
          if($check_status == 'create_check_success') {
              //这里验证全部通过，并且已经更新了product_varient
             if(isset($_FILES['product_varient_images'])) {
                 //开始插入新图片
                 $product_varient_images_model = D('ProductVarientImages');
                 $product_varient_id_to_images_status = $product_varient_images_model->product_varient_id_to_images_add($id);
                 if(!$product_varient_id_to_images_status) {
                     //图片更新失败,数据库回滚
                     $product_varient_model->rollback();
                     $this->display('Success/failure');
                 }
             }
             //如果图片没有提交，用老的图片，那么就直接更新属性
             $product_varient_flower_colors_model = D('ProductVarientFlowerColor');
             $update_flower_color_ids = $product_varient_flower_colors_model->product_varient_id_to_flower_color_id_update($id,$_POST['flower_color']);
             $product_varient_flower_home_model = D('ProductVarientFlowerHome');
             $update_flower_home_ids = $product_varient_flower_home_model->product_varient_id_to_flower_home_id_update($id,$_POST['flower_home']);
             $product_varient_flower_occasion_model = D('ProductVarientFlowerOccasion');
             $update_flower_occasion_ids = $product_varient_flower_occasion_model->product_varient_id_to_flower_occasion_id_update($id,$_POST['flower_occasion']);
             $product_varient_flower_type_model = D('ProductVarientFlowerType');
             $update_flower_type_ids = $product_varient_flower_type_model->product_varient_id_to_flower_type_id_update($id,$_POST['flower_name']);
             $product_varient_hurry_level_model = D('ProductVarientHurryLevel');
             $update_hurry_level_ids = $product_varient_hurry_level_model->product_varient_id_to_hurry_level_id_update($id,$_POST['hurry_level']); 
             if($update_flower_color_ids && $update_flower_home_ids && $update_flower_occasion_ids && $update_flower_type_ids && $update_hurry_level_ids) {
                 $product_varient_model->commit();
                 $this->display('Success/success');
             }
             else {
                 $product_varient_model->rollback();
                 $this->display('Success/failure');
             }
          }
          else {
              $key_value_images = $product_varient_model->query("SELECT id,image_url FROM lovgarden_product_varient_images WHERE product_varient_id='$id'");           
              $this->assign(array(
                 'id' => $id,
                 'errorMessage' => $check_status,
                 'this_product_varient_info' => $result['this_product_varient_info'],
                 'key_value_images' => $key_value_images
              ));
              $this->display('product_update');
          }
        }
        else {
            $sql = "SELECT pv.id , pv.sku_id , pv.varient_name,pv.varient_summary,pv.varient_body,pv.varient_status,pv.varient_price,pv.decoration_level,pv.vase,pvi.`image_url` ,pvhl.`hurry_level_id` , pvft.`flower_type_id`,pvfo.`flower_occasion_id`,pvfh.`flower_home_id`,pvfc.`flower_color_id` FROM lovgarden_product_varient AS pv 
                    LEFT JOIN lovgarden_product_varient_images AS pvi ON pv.`id`=pvi.`product_varient_id`
                    LEFT JOIN lovgarden_product_varient_hurry_level AS pvhl ON pv.`id`=pvhl.`product_varient_id`
                    LEFT JOIN lovgarden_product_varient_flower_type AS pvft ON pv.`id`=pvft.`product_varient_id`
                    LEFT JOIN lovgarden_product_varient_flower_occasion AS pvfo ON pv.`id`=pvfo.`product_varient_id`
                    LEFT JOIN lovgarden_product_varient_flower_home AS pvfh ON pv.`id`=pvfh.`product_varient_id`
                    LEFT JOIN lovgarden_product_varient_flower_color AS pvfc ON pv.`id`=pvfc.`product_varient_id` WHERE pv.`id`= '$id'";
            $model = new Model();
            $result_rows = $model->query($sql);
            $multiple_fileds_array = array('hurry_level_id','image_url','flower_type_id','flower_occasion_id','flower_home_id','flower_color_id');    
            $result_rows_array = translate_database_result_to_logic_array($result_rows,$multiple_fileds_array,'id');

            $key_value_images = $model->query("SELECT id,image_url FROM lovgarden_product_varient_images WHERE product_varient_id='$id'");
            $this->assign(array(
               'id' => $id,
               'this_product_varient_info' => $result_rows_array,
               'key_value_images' => $key_value_images
            ));
            $this->display('product_update');
        }
    }
    public function ajax_delete() {
        //制作Ajax图片删除接口
        if(IS_POST) {
           $row_id = $_POST['row_id'];
           $product_varient_id = $_POST['product_varient_id'];
           $product_varient_images_model = D('ProductVarientImages');
           $delete_status = $product_varient_images_model->where(array(
               'id' => $row_id,
               'product_varient_id' => $product_varient_id,
           ))->delete();
           if(delete_status) {
               echo '1';//表示删除成功
           }
           else {
               echo '2';//表示删除失败
           }
        }
    }
}