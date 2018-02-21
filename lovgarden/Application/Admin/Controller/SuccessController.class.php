<?php
namespace Admin\Controller;
use Think\Controller;
class SuccessController extends Controller {
    public function success(){
        $this->display();
    }
    public function failure(){
        $this->display();
    }
    public function notFound(){
        $this->display();
    }
}