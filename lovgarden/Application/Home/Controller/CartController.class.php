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
                    
                    echo "<pre>";
                    header("Content-type:text/html;charset=utf-8");
                    print_r($new_cart_info_choose);
                    echo "</pre>";
                    exit();
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
        $this->display('checkout');
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