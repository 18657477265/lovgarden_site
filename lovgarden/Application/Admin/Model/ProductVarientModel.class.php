<?php
namespace Admin\Model;
use Think\Model;
class ProductVarientModel extends Model 
{
    //调用时候create方法允许接受的字段
    protected $insertFields = 'sku_id,varient_name,varient_summary,varient_body,varient_status,varient_price,decoration_level,varient_create_time,vase';
    protected $updateFields = 'sku_id,varient_name,varient_summary,varient_body,varient_status,varient_price,decoration_level,vase';
    
    //验证规则
    protected $_validate = array(
        array('sku_id','require','商品编码必填',1),
        array('sku_id','/^[1-9]\d{5}$/','商品编码必须为6位数字',1),
        array('sku_id', '', '商品编号已经存在！',0,'unique',1),//新增字段时候验证唯一性，编辑字段时候不验证
        array('varient_name','require','商品名字必填',1),
        array('varient_summary','require','商品简要必填',1),
        array('varient_body','require','商品详情必填',1),
        array('varient_status',array(1,2,3),'商品状态值的范围不正确！',1,'in'), 
        array('varient_price','currency','价格格式不正确',1), 
        array('decoration_level',array(1,2,3),'配花多少值的范围不正确！',1,'in'),
        array('vase','/^(0|1)$/','是否包含花瓶必选',1),           
    );
    //搜索product varient
    public function search($varient_status,$decoration_level,$vase,$keyword,$firstRow,$listRow) {
        $sql = "SELECT mf.sku_id , mf.varient_name , mf.varient_status , mf.varient_price , mf.decoration_level , mf.vase , df.`image_url` FROM lovgarden_product_varient AS mf LEFT JOIN 
lovgarden_product_varient_images AS df ON mf.`id`= df.`product_varient_id` WHERE mf.`varient_status`='$varient_status' and mf.`decoration_level`='$decoration_level' and (mf.`varient_name` like '%$keyword%' or mf.`sku_id` like '%$keyword%')  group by mf.sku_id limit "."$firstRow".","."$listRow";
        $result = $this->query($sql);
        return $result;
    }

    //封装控制器的数据提交逻辑,仅仅针对product_varient表
    public function product_varient_add() {
        //首选构建多选框数据集
        $multiple_select_array = array();
        if(isset($_POST['flower_color'])) {
            $multiple_select_array['flower_color'] = $_POST['flower_color'];
        }
        if(isset($_POST['hurry_level'])) {
            $multiple_select_array['hurry_level'] = $_POST['hurry_level'];
        }
        if(isset($_POST['flower_name'])) {
            $multiple_select_array['flower_name'] = $_POST['flower_name'];
        }
        if(isset($_POST['flower_home'])) {
            $multiple_select_array['flower_home'] = $_POST['flower_home'];
        }
        if(isset($_POST['flower_occasion'])) {
            $multiple_select_array['flower_occasion'] = $_POST['flower_occasion'];
        }
        
        //构建product_varient数据
        $data['sku_id'] = htmlspecialchars($_POST['sku_id']);
        $data['varient_name'] = htmlspecialchars($_POST['varient_name']);
        $data['varient_summary'] = htmlspecialchars($_POST['varient_summary']);
        $data['varient_body'] = $_POST['varient_body'];
        $data['varient_status'] = htmlspecialchars($_POST['varient_status']);
        $data['varient_price'] = htmlspecialchars($_POST['varient_price']);
        $data['decoration_level'] = htmlspecialchars($_POST['decoration_level']);
        $data['vase'] = htmlspecialchars($_POST['vase']);
        $data['varient_create_time'] = date('Y-m-d H:i:s',time());
        $status = $this->create($data);
        $result = array(
          'data' => $data,
          'multipleSelectData' => $multiple_select_array,
          'report' => ''
        );
        if($status) {
                //插入到数据库
                $success_id = $this->add($status);
                if($success_id) {
                   $result['report'] = 'insert_into_product_varient_ok';
                   $result['new_product_varient_id'] = $success_id;
                }
                else {
                   $result['report'] = 'insert_into_product_varient_error';
                   $result['error_message'] = $this->getError();
                }
        }
        else {
                $error = $this->getError();
                $result['report'] = $error;
        }
        return $result;
    }
    
    public function product_varient_update($id) {
        //构建product_varient需要修改的数据
        $this_product_varient_info = array(
          $id => array()  
        );
        $data['id'] = $id;//new add in 2018-02-25 为了让更新操作时候sku_id不执行唯一性验证
        $data['sku_id'] = htmlspecialchars($_POST['sku_id']);
        $data['varient_name'] = htmlspecialchars($_POST['varient_name']);
        $data['varient_summary'] = htmlspecialchars($_POST['varient_summary']);
        $data['varient_body'] = $_POST['varient_body'];
        $data['varient_status'] = htmlspecialchars($_POST['varient_status']);
        $data['varient_price'] = htmlspecialchars($_POST['varient_price']);
        $data['decoration_level'] = htmlspecialchars($_POST['decoration_level']);
        $data['vase'] = htmlspecialchars($_POST['vase']);
        //create构造并验证product_varient的字段数据，传回$status
        $status = $this->create($data);
        
        if(isset($_POST['flower_color'])) {
            $data['flower_color_id'] = $_POST['flower_color'];
        }
        if(isset($_POST['hurry_level'])) {
            $data['hurry_level_id'] = $_POST['hurry_level'];
        }
        if(isset($_POST['flower_name'])) {
            $data['flower_type_id'] = $_POST['flower_name'];
        }
        if(isset($_POST['flower_home'])) {
            $data['flower_home_id'] = $_POST['flower_home'];
        }
        if(isset($_POST['flower_occasion'])) {
            $data['flower_occasion_id'] = $_POST['flower_occasion'];
        }      
        //无论成功和失败用于传回表单填写字段信息
        $this_product_varient_info[$id]=$data;
        $result = array(
          'this_product_varient_info'=>$this_product_varient_info 
        );
        //验证成功传回成功状态和字段信息
        if($status) {
            $update_operation_result = $this->where("id=$id")->save($status);
            //如果没改动返回0也应该当成成功
            if($update_operation_result !== FALSE) {
                $result['report'] = 'create_check_success';
            }
            else {
                $error = $this->getError();
                $result['report'] = $error;
            }
        }
        //验证失败传回失败信息和字段信息
        else {
            $error = $this->getError();
            $result['report'] = $error;
        }
        return $result;
    }
    
    // 这个方法在添加之前会自动被调用 --》 钩子方法
	// 第一个参数：表单中即将要插入到数据库中的数据->数组
	// &按引用传递：函数内部要修改函数外部传进来的变量必须按钮引用传递，除非传递的是一个对象,因为对象默认是按引用传递的
    protected function _before_insert($data, $option){
        //验证属性值是否选择
        if(!isset($_POST['flower_color'])) {
            $this->error = "花卉颜色至少选择一种";
            return FALSE;
        }
        if(!isset($_POST['hurry_level'])) {
            $this->error = "可配送时间至少选择一样";
            return FALSE;
        }
        if(!isset($_POST['flower_name'])) {
            $this->error = "至少选择一种花卉品种";
            return FALSE;
        }
        if(!isset($_POST['flower_home'])) {
            $this->error = "花卉产地至少一个";
            return FALSE;
        }
        if(!isset($_POST['flower_occasion'])) {
            $this->error = "适用场合至少一个";
            return FALSE;
        }       
        //验证有没有传图片上来     
        $error_status = $_FILES['product_varient_images']['error'];
        $result = TRUE;
        foreach ($error_status as $key => $value) {
            if($value != '0') {
                $result = FALSE;
                $this->error = "图片必须传至少一张";
                return $result;
            }
        }
    }
    
    //在正式更新product_varient之前还得验证属性信息是否符合规范
    protected function _before_update(&$data, $option) {
        //验证属性值是否选择
        if(!isset($_POST['flower_color'])) {
            $this->error = "花卉颜色至少选择一种";
            return FALSE;
        }
        if(!isset($_POST['hurry_level'])) {
            $this->error = "可配送时间至少选择一样";
            return FALSE;
        }
        if(!isset($_POST['flower_name'])) {
            $this->error = "至少选择一种花卉品种";
            return FALSE;
        }
        if(!isset($_POST['flower_home'])) {
            $this->error = "花卉产地至少一个";
            return FALSE;
        }
        if(!isset($_POST['flower_occasion'])) {
            $this->error = "适用场合至少一个";
            return FALSE;
        }       
        //验证有没有传图片上来,如果没重新传不验证，又传图了的话就验证
        if(isset($_FILES['product_varient_images']['error'])) {
            $error_status = $_FILES['product_varient_images']['error'];
            $result = TRUE;
            foreach ($error_status as $key => $value) {
                if($value != '0') {
                    $result = FALSE;
                    $this->error = "图片提交器不能为空";
                    break;
                }
            }
            return $result;
        }
    }
}












