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

function buildHtmlSelect2($tableName,$valueFieldName,$textFieldName,$selectName,$current_value='',$all=FALSE) {
    $model = new Think\Model();
    $sql = "select $valueFieldName , $textFieldName from $tableName";
    $resultArray = $model->query($sql);
    $select = "<select name='$selectName' class='select' style='width:150px;'>";
    if($all) {
        $select .= "<option value='all'>All</option>";
    }
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
            $decoration_level_label = '经典';
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


function get_vase_label($vase) {
    $vase_label = '';
    switch ($vase) {
        case '1':
            $vase_label = '有花瓶';
            break;
        case '2':
            $vase_label = '无花瓶';
            break;
    }
    return $vase_label;
}

function get_order_status_label($order_status) {
    $order_status_label = '';
    switch ($order_status) {
        case '1':
            $order_status_label = '未付款';
            break;
        case '2':
            $order_status_label = '已付款';
            break;
        case '3':
            $order_status_label = '已发货';
            break;
        case '4':
            $order_status_label = '已完成';
            break;
    }
    return $order_status_label;
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
        //$url = $_SERVER['PHP_SELF'];
          $url = $_SERVER['REQUEST_URI'];
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
        case '5':
            $result = '数据已过期，请重新提交结算';
            break;
        case '6':
            $result = '您的订单提交成功';
            break;
        case '0':
            $result = '订单付款成功';
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

//封装memcache检查IP尝试连接次数，邮件发送次数
function mem_check_ip_attention_mail($max_visit_count = 5,$frozen_time = 3600,$type='mail') {
    $client_ip = getClientIp().$type;
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

//根据商品信息整理出价格，优惠，花瓶价格等信息
function wx_calculate_cost($products_array,$vase_price = 20,$coupon_code = '0',$vase_count = 0,$login_exist=0) {
    $cost_info_array = array(
        'total_cost' => 0,
        'vase_cost' => 0,
        'cut_cost' => 0,
        'deliver_cost' => 0,
        'products_original_cost' => 0,
        'vip_discount'=> 1
    );
    $coupon_value = 0;
    $model = new \Think\Model();
    foreach($products_array as $k => $v) {
       $cost_info_array['products_original_cost']+= $v['varient_price'] * $v['count'];
       $sql = "select * from lovgarden_coupon where coupon_id = $coupon_code";
       //$model = new \Think\Model();
       $data = $model->query($sql);
       if(!empty($data)) {
           $coupon_value = $data[0]['coupon_value'];
           $cost_info_array['cut_cost'] = $coupon_value;
       }
    }
    $cost_info_array['vase_cost'] = $vase_price * $vase_count;
    //这里以后添加购物券的逻辑，从数据库里取出扣除的价格
    $cost_info_array['total_cost'] = $cost_info_array['vase_cost'] + $cost_info_array['deliver_cost'] + $cost_info_array['products_original_cost'] - $cost_info_array['cut_cost'];
    if($login_exist != 0) {
        $sql_vip = "SELECT reward_points FROM lovgarden_wxuser WHERE open_id = '$login_exist'";
        $reward_points_array = $model->query($sql_vip);
        $reward_points = $reward_points_array[0]['reward_points'];
        if($reward_points > 2000) {
            $cost_info_array['vip_discount'] = 0.8;
        }
        elseif ($reward_points >= 1000 && $reward_points < 2000) {
            $cost_info_array['vip_discount'] = 0.9;
        }
        $cost_info_array['total_cost'] = $cost_info_array['total_cost'] * $cost_info_array['vip_discount'];
    }
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

//当用户提交订单后，需要验证原来的日期数据是不是还支不支持配送
function orderCheckDateIsValid($deliver_time) {
        $unixTime = strtotime($deliver_time);
        if ($unixTime) { //strtotime转换不对，日期格式显然不对。
            return TRUE;
        }
        return false;
}

function orderCheckIsFurture($deliver_time) {
        if(orderCheckDateIsValid($deliver_time)) {
            $today = date("Y-m-d");
            if(strtotime($deliver_time)-strtotime($today) >= 0){
                return TRUE;
            }
        }
        return FALSE;        
}

//验证此刻商品的配送日期是不是还合法
function orderCheckDeliverTodayAllowed($deliver_time,$sku_id) {        
        $unix_time = strtotime($deliver_time); 
        $deliver_date = date('Y-m-d',$unix_time);
        $today = date('Y-m-d');
        if($deliver_date == $today) {
            //输入的日期是今天，查看该商品是不是允许今天下单
            //这里还是得使用memcache,防止多次查询数据库的情况发生
            //注意：之所以直接从后台取数据而不从模板里面传上来是怕有人故意传错误的值上来
            $mem_check_deliver = new Think\Cache\Driver\Memcache();
            $name = $sku_id.'deliver_status';
            $count = $mem_check_deliver->get($name);
            if(empty($count)){
                $sql = "SELECT b.hurry_level_id  from lovgarden_product_varient AS a LEFT JOIN lovgarden_product_varient_hurry_level AS b ON a.`id`=b.`product_varient_id` WHERE a.`sku_id`='$sku_id';";
                $model = new \Think\Model();
                $result = $model->query($sql);
                $count = count($result);//如果配送选择大于1的说明肯定支持当天配送，否则肯定是预约配送
                $mem_check_deliver->set($name,$count,86400);
            }
            if($count > 1) {
                return TRUE;
            }
            else {
                return FALSE;
            }
        }
        //如果输入的配送日期不是今天，则直接通过
        else {
            return TRUE;
        }
}

//开始利用上面三个方法验证提交上来的新的订单配送日期信息
function checkOrderItemsDeliverDateValid($new_cart_info_choose,&$old_cart_info) {
    $flag = array(
        'result_code' => '0',
        'error_message' => '',
    );
    if(!empty($new_cart_info_choose)) {
        foreach($new_cart_info_choose as $k => $v) {
            //修改原数据,用于输出错误信息，提高用户体验
            $old_cart_info[$k]['deliver_time']= $v['deliver_time'];
            if(orderCheckIsFurture($v['deliver_time'])){
                if(orderCheckDeliverTodayAllowed($v['deliver_time'],$v['sku_id'])){
                    $flag = array(
                        'result_code' => '1',
                        'error_message' => '',
                    );
                }
                else {
                    $flag = array(
                        'result_code' => '2',
                        'error_message' => $v['deliver_time'].':'.$v['varient_name'].' 不支持今日配送,请重选配送日期',
                    );
                    break;
                }
            }
            else {
                $flag = array(
                        'result_code' => '3',
                        'error_message' => $v['deliver_time'].':该日期已过期',
                );
                
                break;
            }
        }
    }
    else {
       $flag = array(
        'result_code' => '0',
        'error_message' => '数据为空',
       );
    }
    return $flag;
}

//调用码支付支付url
function pay_codepay($order_id,$price) {
    $codepay_id="60905";//这里改成码支付ID
    $codepay_key=C('PAY_SECRET'); //这是您的通讯密钥
    $data = array(
        "id" => $codepay_id,//你的码支付ID
        "pay_id" => $order_id, //唯一标识 可以是用户ID,用户名,session_id(),订单ID,ip 付款后返回
        "type" => 1,//1支付宝支付 3微信支付 2QQ钱包
        "price" => 0.02,//金额100元
        "param" => "",//自定义参数
        "notify_url"=>"https://www.flowerideas.cn/user/user_order_handle",//通知地址
        "return_url"=>"https://www.flowerideas.cn/user/operation_success/status/7",//跳转地址
    ); //构造需要传递的参数
    ksort($data); //重新排序$data数组
    reset($data); //内部指针指向数组中的第一个元素

    $sign = ''; //初始化需要签名的字符为空
    $urls = ''; //初始化URL参数为空

    foreach ($data AS $key => $val) { //遍历需要传递的参数
        if ($val == ''||$key == 'sign') continue; //跳过这些不参数签名
        if ($sign != '') { //后面追加&拼接URL
            $sign .= "&";
            $urls .= "&";
        }
        $sign .= "$key=$val"; //拼接为url参数形式
        $urls .= "$key=" . urlencode($val); //拼接为url参数形式并URL编码参数值

    }
    $query = $urls . '&sign=' . md5($sign .$codepay_key); //创建订单所需的参数
    $url = "https://codepay.fateqq.com:51888/creat_order/?{$query}"; //支付页面

    header("Location:{$url}"); //跳转到支付页面
}

//判断是不是手机端
function isMobile() { 
  // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
  if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
    return true;
  } 
  // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
  if (isset($_SERVER['HTTP_VIA'])) { 
    // 找不到为flase,否则为true
    return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
  } 
  // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
  if (isset($_SERVER['HTTP_USER_AGENT'])) {
    $clientkeywords = array('nokia','sony','ericsson','mot','samsung','htc','sgh','lg','sharp','sie-','philips','panasonic','alcatel','lenovo','iphone','ipod','blackberry','meizu','android','netfront','symbian','ucweb','windowsce','palm','operamini','operamobi','openwave','nexusone','cldc','midp','wap','mobile','MicroMessenger'); 
    // 从HTTP_USER_AGENT中查找手机浏览器的关键字
    if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
      return true;
    } 
  } 
  // 协议法，因为有可能不准确，放到最后判断
  if (isset ($_SERVER['HTTP_ACCEPT'])) { 
    // 如果只支持wml并且不支持html那一定是移动设备
    // 如果支持wml和html但是wml在html之前则是移动设备
    if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
      return true;
    } 
  } 
  return false;
}
//根据花卉产地打印出产地宣传图
function render_flower_id_to_image($flower_home_id) {
    $flower_home_image_dir = C("FLOWER_HOME_IMAGE_DIR");
    return $flower_home_image_dir.$flower_home_id.'.jpg';   
}
//获取访问IP
function getIP() { 
    if (! empty ( $_SERVER ["HTTP_CLIENT_IP"] )) { 
      $cip = $_SERVER ["HTTP_CLIENT_IP"]; 
    } else if (! empty ( $_SERVER ["HTTP_X_FORWARDED_FOR"] )) { 
      $cip = $_SERVER ["HTTP_X_FORWARDED_FOR"]; 
    } else if (! empty ( $_SERVER ["REMOTE_ADDR"] )) { 
      $cip = $_SERVER ["REMOTE_ADDR"]; 
    } else { 
      $cip = ''; 
    } 
    preg_match ( "/[\d\.]{7,15}/", $cip, $cips ); 
    $cip = isset ( $cips [0] ) ? $cips [0] : 'unknown'; 
    unset ( $cips ); 
    return $cip; 
}
//Translate xml data to array
function translate_xml_to_data($xml){ 
    if(!$xml){
        return false;
    }
    //将XML转为array
    //禁止引用外部xml实体
    libxml_disable_entity_loader(true);
    $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true); 
    return $data;
}

