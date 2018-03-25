<?php
namespace Home\Controller;
use Think\Controller;
class CartController extends Controller {
    public function detail(){
        //用户购物车详情
        $this->display('detail');
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
          }
        }
        echo '0';        
    }
}