<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/*自动生成单选框*/
function buildHtmlSelect($tableName,$valueFieldName,$textFieldName,$selectName,$current_value='') {
    $model = new Think\Model();
    $sql = "select $valueFieldName , $textFieldName from $tableName";
    $resultArray = $model->query($sql);
    $select = "<select name='$selectName' multiple='multiple' style='width:150px;'>";
    foreach ($resultArray as $k => $v) {
        $select_status='';
        if($current_value == $v[$valueFieldName]) {
            $select_status='selected="selected"';
        }
        $row="<option value=$v[$valueFieldName] $select_status>$v[$textFieldName]</option>";
        $select .= $row;
        
    }
     $select .= "</select>";
    return $select;
}
/*自动生成多选框*/
function buildMultipleHtmlSelect($tableName,$valueFieldName,$textFieldName,$selectName,$current_value=array()) {
    $model = new Think\Model();
    $sql = "select $valueFieldName , $textFieldName from $tableName";
    $resultArray = $model->query($sql);
    $select = "<select name='$selectName"."[]'"." multiple='multiple' style='width:150px;'>";
    foreach ($resultArray as $k => $v) {
        $select_status='';
        if(in_array($v[$valueFieldName], $current_value)) {
            $select_status='selected="selected"';
        }
        $row="<option value=$v[$valueFieldName] $select_status>$v[$textFieldName]</option>";
        $select .= $row;
        
    }
     $select .= "</select>";
    return $select;
}

/*自动生成复选框*/
function buildCheckboxes($tableName,$valueFieldName,$textFieldName,$selectName,$current_value=array()) {
    $model = new Think\Model();
    $sql = "select $valueFieldName , $textFieldName from $tableName";
    $resultArray = $model->query($sql);
    $select = "";
    $selectName .='[]';
    foreach ($resultArray as $k => $v) {
        $select_status='';
        if(in_array($v[$valueFieldName], $current_value) && !empty($current_value)) {
            $select_status='checked="checked"';
        }
        $row="<span><input type='checkbox' name='$selectName' value='$v[$valueFieldName]' $select_status>$v[$textFieldName]</span>";
        $select .= $row;
        
    }
    return $select;
}

/*自动生成权限列表复选框*/
function buildCheckboxesForPermission($tableName,$valueFieldName,$textFieldName,$selectName,$description,$current_value=array()) {
    $model = new Think\Model();
    $sql = "select $valueFieldName , $textFieldName , $description from $tableName";
    $resultArray = $model->query($sql);
    $select = "";
    $selectName .='[]';
    foreach ($resultArray as $k => $v) {
        $select_status='';
        if(in_array($v[$valueFieldName], $current_value) && !empty($current_value)) {
            $select_status='checked="checked"';
        }
        $row="<p class='permission-row'><span class='permission_name'><strong>$v[$textFieldName]</strong></span><span class='permission-checkbox'><input type='checkbox' name='$selectName' value='$v[$valueFieldName]' $select_status></span></p><p class='permission_description'>$v[$description]</p>";
        $select .= $row;
        
    }
    return $select;
}

//封装上传图片的函数
function uploadImage($imageName,$dirName,$thumb = array()) {
    $image_config = C('IMAGE_CONFIG');    
    $upload = new \Think\Upload();// 实例化上传类
    $upload->maxSize = $image_config['maxSize'];// 设置附件上传大小
    $upload->exts = $image_config['exts'];// 设置附件上传类型
    $upload->rootPath = $image_config['rootPath']; // 设置附件上传根目录
    $upload->savePath  = $dirName; // 设置附件上传（子）目录
    $now = $_SERVER['REQUEST_TIME'];
    $upload->saveName = array('uniqid',$now);
    // 上传文件 
    $info   =   $upload->upload();
    if(!$info) {// 上传错误提示错误信息
        return FALSE;
    }else{// 上传成功
        return $info;
    }
    
}

//将数据库的连表查询数据整理为一个个类似对象的数据结构,便于在模板中直接使用
function translate_database_result_to_logic_array($databaseResultArray,$multiple_field_name_array,$unique_field) {
    $product_varients = array();
    foreach ($databaseResultArray as $key => $value) {
            foreach ($value as $field_name => $field_value) {
                if(in_array($field_name, $multiple_field_name_array)) {
                    if(!in_array($field_value, $product_varients[$value[$unique_field]][$field_name])) {
                      $product_varients[$value[$unique_field]][$field_name][] = $field_value;
                    }
                }
                else {
                    $product_varients[$value[$unique_field]][$field_name] = $field_value;
                }
            }
    }
    return $product_varients;
}

//根据状态值返回商品varient的状态
function product_varient_status($status_value){
    $status_label = '';
    switch ($status_value) {
        case '1':
            $status_label = '上架';
            break;
        case '2':
            $status_label = '下架';
            break;
        case '3':
            $status_label = '删除';
            break;
    }
    return $status_label;
}

//根据状态值返回商品varient的配花程度
function product_varient_decoration_level($decoration_level_value){
    $decoration_level_label = '';
    switch ($decoration_level_value) {
        case '1':
            $decoration_level_label = '适中';
            break;
        case '2':
            $decoration_level_label = '奢侈';
            break;
        case '3':
            $decoration_level_label = '豪华';
            break;
    }
    return $decoration_level_label;
}

//根据状态值返回商品varient是否可以配置花瓶
function product_varient_vase_attach($vase_status_value){
    $vase_status_label = '';
    switch ($vase_status_value) {
        case '1':
            $vase_status_label = '有';
            break;
        case '0':
            $vase_status_label = '无';
            break;
    }
    return $vase_status_label;
}

//根据状态值返回user_status 账号状态
function user_status_label($user_status){
    $user_status_label = '';
    switch ($user_status) {
        case '1':
            $user_status_label = '已激活';
            break;
        case '0':
            $user_status_label = '已冻结';
            break;
        case '2':
            $user_status_label = '已封停';
            break;
    }
    return $user_status_label;
}

//判断后台用户是否有权限看这个链接，如果有这个链接的权限，可以看，如果没有，不能看
function check_permission_view($url,$text_field,$class='',$title='') {
    $result = "";
    if(!empty(session('id'))){
        $user_id = session('id');
        if($user_id == 1) {
            $return_string = '<a title="'.$title.'" class="'.$class.'" href='.$url.'>'.$text_field.'</a>';
            return $return_string;
        }
        else{
            $model = D('User');
            $sql = "SELECT a.user_id,a.user_name,d.`url` FROM lovgarden_user AS a 
                        LEFT JOIN lovgarden_user_to_role AS b ON a.`user_id`=b.`user_id`
                        LEFT JOIN lovgarden_role_to_permission AS c ON b.`role_id`=c.`role_id`
                        LEFT JOIN lovgarden_permission AS d ON c.`permission_id`= d.`id` WHERE a.`user_id`= '$user_id'";
            $permission_list = $model->query($sql);
            if(!empty($permission_list)) {
                    $user_permission_info = translate_database_result_to_logic_array($permission_list,array('url'),'user_id');               
                    $user_permission_info = $user_permission_info[$user_id];                    
                    foreach ($user_permission_info['url'] as $k => $v) {
                        if(stristr($url,$v)) {
                            $return_string = '<a title="'.$title.'" class="'.$class.'" href='.$url.'>'.$text_field.'</a>';
                            return $return_string;
                            break;
                        }
                    }
             }
        }
    }
    return $result;   
}

//根据decoration_id输入对应的包装等级名称
function get_decoration_name($decoration_id) {
    $decoration_name = '';
    switch ($decoration_id) {
        case '1':
            $decoration_name = '经典';
            break;
        case '2':
            $decoration_name = '奢华';
            break;
        case '3':
            $decoration_name = '豪华';
            break;
    }
    return $decoration_name;
}

function get_decoration_name_info($decoration_id) {
    $decoration_name = '';
    switch ($decoration_id) {
        case '1':
            $decoration_name = '款式流行包装';
            break;
        case '2':
            $decoration_name = '更多配花数量';
            break;
        case '3':
            $decoration_name = '更多配花品种';
            break;
    }
    return $decoration_name;
}

//根据sku_id的命名规则获取商品的关联商品
function get_related_products($current_id) {
    //$decoration_id = substr($current_id,-1);
    $group_id = substr($current_id,0,5);
    $related_ids = array();
    $related_ids[] = $group_id.'1';
    $related_ids[] = $group_id.'2';
    $related_ids[] = $group_id.'3';
    return $related_ids;
}
//根据颜色ID 输出 英文class 名字
function get_css_class_color($color_id) {
    $color_class = '';
    switch ($color_id) {
        case '2':
            $color_class = 'white';
            break;
        case '1':
            $color_class = 'pink';
            break;
        case '3':
            $color_class = 'purple';
            break;
        case '6':
            $color_class = 'red';
            break;
        case '5':
            $color_class = 'green';
            break;
        case '4':
            $color_class = 'yellow';
            break;
    }
    return $color_class;
}

//根据配送huryy_level_id选择合适的展现
function show_appropriate_hurry_status($hurry_level_ids = array()){
    if(count($hurry_level_ids) > 1){
        return '可当日配送';
    }else {
        if($hurry_level_ids[0] == '1') {
            return '按预定配送';
        }
        elseif($hurry_level_ids[0] == '2'){
            return '可当日配送';
        }
    }
}

//更具url get 的条件生成查询所需要的条件
//查询url类似：/product/select_list/flowerType_1/1/flowerType_2/2/color_1/2/color_2/6/hurryLevel_1/0/
function get_filter_conditions(){
    $conditiones = I('get.');
    $conditiones_fix = array();
    if(!empty($conditiones)) {
        foreach($conditiones as $k => $v) {
            $temp_array = explode('_', $k);
            $conditiones_fix[$temp_array[0]][]=$v;
        }
    }
    return $conditiones_fix;
}

//去除url get参数中的某一个参数
function filterUrl($param,$url='') {
    if(empty($url)) {
        $url = $_SERVER['PHP_SELF'];
    }
    $pattern = "/\/$param\/[^\/]+/";
    return preg_replace($pattern, '', $url);
}

//根据product_varient 一群产品的获取他们所包含的属性值,用于动态生成查询条件
function get_available_filters($product_varients_info) {    
   $all_filter = array();
   foreach($product_varients_info as $sku_id => $product_varient){
       foreach($product_varient['hurry_level_id'] as $k => $hurry_level_id) {
           $all_filter['hurry_level'][$hurry_level_id] = $product_varient['hurry_level'][$k];
       }
       foreach($product_varient['flower_type_id'] as $k => $flower_type_id) {
           $all_filter['flower_type'][$flower_type_id] = $product_varient['flower_name'][$k];
       }
       foreach($product_varient['flower_occasion_id'] as $k => $flower_occasion_id) {
           $all_filter['flower_occasion'][$flower_occasion_id] = $product_varient['flower_occasion'][$k];
       }
       foreach($product_varient['flower_color_id'] as $k => $flower_color_id) {
           $all_filter['flower_color'][$flower_color_id] = $product_varient['flower_color'][$k];
       }
   }
   return $all_filter;
}

//操作成功页面根据status状态码返回不同信息
function translate_status_label($status_code = '2') {
    $result = '成功';
    switch ($status_code) {
        case '1':
            $result = '恭喜您注册成功';
            break;
        case '2':
            $result = '欢迎来到花点馨思(原名:丽de花苑)';
            break;
        case '3':
            $result = '您的密码已修改,请登录';
            break;
    }
    return $result;
}

function getClientIp() {  
    $ip = 'unknow';  
    foreach (array(  
                'HTTP_CLIENT_IP',  
                'HTTP_X_FORWARDED_FOR',  
                'HTTP_X_FORWARDED',  
                'HTTP_X_CLUSTER_CLIENT_IP',  
                'HTTP_FORWARDED_FOR',  
                'HTTP_FORWARDED',  
                'REMOTE_ADDR') as $key) {  
        if (array_key_exists($key, $_SERVER)) {  
            foreach (explode(',', $_SERVER[$key]) as $ip) {  
                $ip = trim($ip);  
                //会过滤掉保留地址和私有地址段的IP，例如 127.0.0.1会被过滤  
                //也可以修改成正则验证IP  
                if ((bool) filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)) {  
                    return $ip;  
                }  
            }  
        }  
    }  
    return $ip;  
}

//封装memcache检查IP尝试连接次数，短信验证次数
function mem_check_ip_attention($max_visit_count = 10,$frozen_time = 7200) {
    $client_ip = getClientIp();
    $mem = new Think\Cache\Driver\Memcache();
    $ip_send_count = $mem->get($client_ip);
    if(empty($ip_send_count) || $ip_send_count < $max_visit_count) {
        if(!empty($ip_send_count)) {
            //一小时内不是第一次发送
            $ip_send_count ++;
            $mem->set($client_ip, $ip_send_count, $frozen_time);
        }
        else {
            //第一次发送
            $mem->set($client_ip,1,$frozen_time); 
        }
        return TRUE;
    }
    else {
        return FALSE;
    }
}
//根据商品信息整理出价格，优惠，花瓶价格等信息
function calculate_cost($products_array,$vase_price = '20',$cut_code = '0') {
    $cost_info_array = array(
        'total_cost' => 0,
        'vase_cost' => 0,
        'cut_cost' => 0,
        'deliver_cost' => 0,
        'products_original_cost' => 0,
    );
    foreach($products_array as $k => $v) {
        $cost_info_array['products_original_cost']+= $v['varient_price'];
        if($v['vase'] == '1') {
            $cost_info_array['vase_cost'] += $vase_price;
        }
    }
    //这里以后添加购物券的逻辑，从数据库里取出扣除的价格
    $cost_info_array['total_cost'] = $cost_info_array['vase_cost'] + $cost_info_array['deliver_cost'] + $cost_info_array['products_original_cost'] - $cost_info_array['cut_cost'];
    return $cost_info_array;
}

//确认订单时候用户提交的数据和老的cart info数据组合成新的数据
function merge_submit_cart_info($old_cart_info,$submit_info,$user_id) {
    foreach($submit_info['vase_options'] as $k => $v) {
        $old_cart_info[$k]['vase'] = $v;
    }
    foreach($submit_info['deliver_date'] as $k1 => $v1) {
        $old_cart_info[$k1]['deliver_time'] = $v1;
    }
    return $old_cart_info;
}

//验证提交的商品ID是不是有被非法改造过
function check_sumbit_sku_id_right($old_cart_info,$submit_info,$user_id) {
    if(!empty($old_cart_info)) {
        $count = count($old_cart_info);
        $old_cart_ids = array();
        foreach($old_cart_info as $k=>$v) {
            $old_cart_ids[] = $k;
        }
        if(!empty($submit_info['vase_options']) && !empty($submit_info['deliver_date'])) {
            $result = TRUE;
            foreach($submit_info['vase_options'] as $k1 => $v1) {
                if(!in_array($k1, $old_cart_ids) || !in_array($v1, array('1','2'))){
                    return false;
                }
            }
            foreach($submit_info['deliver_date'] as $k2 => $v2) {
                if(!in_array($k2, $old_cart_ids) || empty($v2)){
                    return false;
                }
            }
            if(count($submit_info['vase_options']) != $count || count($submit_info['deliver_date']) != $count) {
                return FALSE;
            }
            return TRUE;
        }
        else {
            return FALSE; 
        }
    }
    else {
        return FALSE;
    }
}

