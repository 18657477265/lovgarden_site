<?php
namespace Home\Controller;
use Think\Controller;
use Think\Cache\Driver\Memcache;
class ArticleController extends Controller {
   public function detail() {
       $this->display('detail');
   }
   public function helpcenter() {
       $this->display('helpcenter');
   }
   public function faq() {
       $this->display('faq');
   }
}