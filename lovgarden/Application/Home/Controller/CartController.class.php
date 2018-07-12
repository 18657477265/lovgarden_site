<?php
namespace Home\Controller;
use Think\Controller;
use Think\Cache\Driver\Memcache;
class CartController extends Controller {
    public function detail(){
        //用户购物车详情
        //取出购物车中的相关信息
        $user_id = session('custom_id');
        if(!empty($user_id)) {
          //查看是否是提交的表单信息
          $error_message = '';
          //memcache准备工作
          $mem_store_cart_info = new Memcache();
          $name = $user_id.'order_cart_info';
          
          if(IS_POST) {
            //验证提交条件,验证通过后将数据更新到memcache中
            $old_cart_info = $mem_store_cart_info->get($name);
            if(!empty($old_cart_info)){
                $old_cart_info = unserialize($old_cart_info);
                $submit_info = I('post.');
 
                //通过比对提交数据和缓存中的购物车数据，查看有没有非法篡改数据，要求内存中的购物车数据必须准确
                if(check_sumbit_sku_id_right($old_cart_info,$submit_info,$user_id)) {
                    $new_cart_info_choose = merge_submit_cart_info($old_cart_info,$submit_info,$user_id);
                    //这个在原购物车数据基础上生成的新的数据和提交的优惠码一同构成了order对象的基本数据，接下来要验证这些数据是否允许构成一个订单
                    $checked_date = checkOrderItemsDeliverDateValid($new_cart_info_choose,$old_cart_info);
                    if($checked_date['result_code'] == '1') {
                        //正式保留数据进行跳转                        
                        $correct_data = $user_id.'correct_order_cart_info';
                        $coupon_code = $user_id.'coupon_code';
                        $mem_store_cart_info->set($correct_data, serialize($new_cart_info_choose), 1200);
                        $mem_store_cart_info->set($coupon_code, $submit_info['discount_code'], 1200);
                        //跳转到checkout页面
                        $this->redirect('cart/checkout');
                        exit();
                    }   
                    else {
                        //输出错误信息
                        $costs_info_array = calculate_cost($old_cart_info);
                        $this->assign(array(
                            'cart_products' => $old_cart_info,
                            'costs_info_array' => $costs_info_array,
                            'error_message' => $checked_date['error_message']
                         ));
                         $this->display('detail');
                         exit();
                    }
                }
                else {
                    header("Content-type:text/html;charset=utf-8");
                    echo '有数据的篡改';
                    exit();
                }
                //将新提交的数据和老的cart_info数据组合生成当前正确的数据，同时正好比对验证数据                
            }
            //说明数据已经过期，需要重新提交
            else {
                echo '数据已经过期,请重新提交结算';
                exit();
            }
          }         
          //正常进入页面
          $model = new \Think\Model();
          $sql = "SELECT cart.id AS cart_id, product.id,product.sku_id,product.varient_name,product.varient_price,product.decoration_level,images.`image_url` , cart.`deliver_time` , cart.`vase` FROM lovgarden_cart AS cart
                  LEFT JOIN lovgarden_product_varient AS product ON cart.varient_id = product.`sku_id`
                  LEFT JOIN lovgarden_product_varient_images AS images ON product.id = images.`product_varient_id` 
                  WHERE product.`varient_status`='1' AND cart.`user_id` = '$user_id' ";
          $result = $model->query($sql);
          if(!empty($result)) {
             $result_fix = translate_database_result_to_logic_array($result,array('image_url'),'cart_id');
             //将购物车信息放入到内存中，用于之后新的提交数据的比对和验证，内存中保存600秒，过了自动失效(用于安全机制)
             $mem_store_cart_info->set($name, serialize($result_fix),600);
             
             //计算各类收费信息
             $costs_info_array = calculate_cost($result_fix);
             $this->assign(array(
                'cart_products' => $result_fix,
                'costs_info_array' => $costs_info_array,
                'error_message' => $error_message
             ));
             $this->display('detail');
          }
          else {
              $this->display('empty_cart');//显示购物车空空页面
          }
        }
        else {
           //非登入用户访问直接到登录页面
           $this->redirect('user/login');
        }
    }
    
    public function checkout() {
        $user_id = session('custom_id');
        if(!empty($user_id)){
            $correct_data = $user_id.'correct_order_cart_info';
            $coupon_code = $user_id.'coupon_code';
            $mem_order = new Memcache();
            $order_products_info = $mem_order->get($correct_data);
            $order_coupon_code = $mem_order->get($coupon_code);
            
            if(!empty($order_products_info)) {
                $order_products_info = unserialize($order_products_info);
                $costs = calculate_cost($order_products_info);
                $error_message = '';
                if(IS_POST) {
                    //生成最终订单，并验证配送信息                    
                    $order_info = array(
                      'order_id' => date('ymdHis'). rand(10000,99999),
                      'order_owner' => session('user_telephone'),
                      'last_name' => I('post.last_name'),
                      'first_name' => I('post.first_name'),
                      'telephone' => I('post.phone_number'),
                      'area' => I('post.address_province_city'),
                      'address' => I('post.address_detail_location'),
                      'post_code' => I('post.zip_code'),
                      'content_body' => I('post.order_item_message'),
                      'order_create_time' => date('Y-m-d H:i:s'),
                      'order_products_total_price' => $costs['products_original_cost'],
                      'order_deliver_price' => $costs['deliver_cost'],
                      'order_vases_price' => $costs['vase_cost'],
                      'order_coupon_code' => $order_coupon_code,
                      'order_coupon_cut' => $costs['cut_cost'],
                      'order_vip_level_cut' => '0',
                      'order_final_price' => $costs['total_cost'],
                      'order_status' => '1',  
                    );
                    $order_model = D('Order');                    
                    $validate_status = $order_model->create($order_info);
//                    
//                    echo "<pre>";
//                    header("Content-type:text/html;charset=utf-8");
//                    print_r($order_model->getError());
//                    echo "</pre>";
//                    exit();
//                    
                    if($validate_status){
                        //订单通过验证，提交事务，生成订单(不能大于10个),关联商品
                        //开始启动事务
                        $order_model->startTrans();
                        $order_id = $order_model->add($validate_status);
                        if($order_id) {
                            $final_status = TRUE;
                            $order_bind_product_model = D('OrderProductVarient');
                            foreach($order_products_info as $k => $v) {
                                $order_bind_product_array = array(
                                  'order_original_id' => $order_id,
                                  'order_info_id' => $order_info['order_id'],
                                  'product_sku_id' => $v['sku_id'],
                                  'vase_option' => $v['vase'],
                                  'deliver_time' => $v['deliver_time'],                                  
                                );
                                $order_bind_product_status = $order_bind_product_model->add($order_bind_product_array);
                                if(!$order_bind_product_status) {
                                    $order_model->rollback();
                                    $final_status = FALSE;
                                    break;
                                }
                            }
                            if($final_status) {
                                //删除内存信息，删除购物车中数据  
                                $prefix_cart_info = $user_id.'order_cart_info';
                                $prefix_cart_block_info = $user_id.'cart_info';
                                $prefix_cart_block_count = $user_id.'cart_items_count';
                                $mem_order->rm($correct_data);
                                $mem_order->rm($coupon_code);
                                $mem_order->rm($prefix_cart_info);
                                $mem_order->rm($prefix_cart_block_info);
                                $mem_order->rm($prefix_cart_block_count);
                                
                                $user_cart_model = D('Cart');
                                $delete_status = $user_cart_model->where(array(
                                    'user_id' => $user_id,
                                ))->delete();
                                if($delete_status !== FALSE) {
                                    $order_model->commit();
                                    $this->redirect('cart/order/status/6');
                                    exit();
                                }
                                else {
                                    $order_model->rollback();
                                    header("Content-type:text/html;charset=utf-8");
                                    echo "清除购物车数据失败";
                                    exit();
                                }                               
                            }
                            else {
                                header("Content-type:text/html;charset=utf-8");
                                echo "提交订单产品出错";
                                exit();
                            }                                                       
                        }else {
                            header("Content-type:text/html;charset=utf-8");
                            echo "提交订单出错";
                            exit();
                        }
                    }
                    else {
                        $error_message = $order_model->getError();
                    }                 
                }
                
                $this->assign(array(
                    'order_products_info' => $order_products_info,
                    'order_coupon_code' => $order_coupon_code,
                    'costs' => $costs,
                    'error_message' => $error_message
                ));
                $this->display('checkout');
                exit();
            }
            else {
               $this->redirect('User/operation_success/status/5');
               exit();
            }
        }
        else {
            $this->redirect('user/login');
        }
    }
    function order($status = '6'){
        $this->assign(array(
            'status' => $status,
        ));
        $this->display('order');
    }
    
    function ajax_get_cart_items() {
        $user_id = session('custom_id');
        if(!empty($user_id)) {
          $mem = new \Think\Cache\Driver\Memcache();
          $name = $user_id.'cart_items_count';
          $count = $mem->get($name);
          if(!empty($count)) {
              echo $count;
              exit();
          }else {
            $sql = "SELECT id FROM lovgarden_cart WHERE user_id = '$user_id'";        
            $model_for_user = new \Think\Model();
            $results = $model_for_user->query($sql);
            $count = count($results);
            $mem->set($name, $count,86400);
            echo $count;
            exit();
          }
        }
        echo '0';        
    }
}