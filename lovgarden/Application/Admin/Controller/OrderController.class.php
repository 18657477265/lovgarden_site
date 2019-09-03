<?php
namespace Admin\Controller;
use Think\Controller;
use Home\Model\OrderModel;
class OrderController extends BaseController {
    public function order_list($order_id='',$order_owner='',$telephone='',$order_create_time_start='',$order_create_time_end='',$order_status='',$others=''){
        //罗列出所有订单，按照时间倒叙排列
        $order_model = D('Order');
        $where = array();
        $filter_selection = array();       
        
        if(!empty($order_id)) {
            $where['order_id'] = array('EQ',trim($order_id));
            $filter_selection['order_id'] = $order_id;
        }
        if(!empty($order_owner)) {
            $where['order_owner'] = array('EQ',trim($order_owner));
            $filter_selection['order_owner'] = $order_owner;
        }
        if(!empty($telephone)) {
            $where['telephone'] = array('EQ',trim($telephone));
            $filter_selection['telephone'] = $telephone;
        }
        if(!empty($order_create_time_start)) {
            $where['order_create_time'] = array('egt', date('Y-m-d H:i:s',strtotime(trim($order_create_time_start))));
            $filter_selection['order_create_time_start'] = $order_create_time_start;
        }
        if(!empty($order_create_time_end)) {
            $where['order_create_time'] = array('elt', date('Y-m-d H:i:s',strtotime(trim($order_create_time_end))));
            $filter_selection['order_create_time_end'] = $order_create_time_end;
        }
       if(!empty($order_status) && $order_status != 'all') {
            $where['order_status'] = array('EQ',trim($order_status));
            $filter_selection['order_status'] = $order_status;
        }            
        if(!empty($others)) {
            $others = trim($others);
            $where['_string'] = "( last_name like '%$others%')  OR ( content_body like '%$others%') OR ( address like '%$others%') OR ( order_coupon_code like '%$others%')";
            $filter_selection['others'] = $others;
        }
        $count = $order_model->where($where)->count();
        $Page  = new \Think\Page($count,C('PRODUCT_VARIENT_PAGE')['page_count']);
        $show  = $Page->show();
        $orders = $order_model->where($where)->limit($Page->firstRow,$Page->listRows)->order(array('order_create_time'=>'desc'))->select();
       
        $this->assign(array(
            'orders' => $orders,
            'page' => $show,
            'filter_selection' => $filter_selection
        ));// 赋值数据集

        $this->display('order_list');
    }
    public function detail($id) {
        $sql = "SELECT o.id AS list_id, o.order_info_id,o.product_sku_id,o.vase_option,o.deliver_time,o.items_count,p.varient_name,p.varient_status,p.varient_price,img.image_url FROM lovgarden_order_product_varient AS o
                LEFT JOIN lovgarden_product_varient AS p ON o.product_sku_id = p.sku_id 
                LEFT JOIN lovgarden_product_varient_images AS img ON p.id = img.product_varient_id WHERE o.order_original_id = '$id'";
        $model = new \Think\Model();
        $order_bind_products = $model->query($sql);
        $order_bind_products_fix = translate_database_result_to_logic_array($order_bind_products,array('image_url'),'list_id');
        
        $this->assign(array(
            'products' => $order_bind_products_fix
        ));
        
        $this->display('order_detail');
    }
    public function updateStatus() {
        $order_id = $_POST['order_id'];
        $order_status = $_POST['status'];
        
        if($order_status == 'sent') {
            $sql = "UPDATE lovgarden_order set order_status = '3' WHERE id = '$order_id' and order_status = '2'";
            $model = new \Think\Model();
            $result = $model->execute($sql);
            if($result !== FALSE) {
                echo '200';
                exit();
            }
        }
        echo '404';
    }
    public function doneStatus() {
        $order_id = $_POST['order_id'];
        $order_status = $_POST['status'];
        
        if($order_status == 'done') {
            $sql = "UPDATE lovgarden_order set order_status = '4' WHERE id = '$order_id' and order_status = '3'";
            $model = new \Think\Model();
            $result = $model->execute($sql);
            if($result !== FALSE) {
                echo '200';
                exit();
            }
        }
        echo '404';
    }
}