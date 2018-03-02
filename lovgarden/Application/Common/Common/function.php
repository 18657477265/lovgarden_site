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


