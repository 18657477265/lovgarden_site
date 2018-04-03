<?php
namespace Admin\Controller;
use Think\Controller;
class OrderController extends BaseController {
    public function order_list(){
        //罗列出所有订单，按照时间倒叙排列
        $this->display('order_list');
    }
}