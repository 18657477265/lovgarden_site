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
   public function faq($id) {
       $article = D('Article');
       $this_faq = $article->field('article_title,article_summary,article_body,article_category,banner_image')->where(array(
           'id' => $id,
           'article_publish' => 1,
           'article_category' => 7
       ))->find();      
       $this->assign(array(
          'faq' => $this_faq 
       ));
       $this->display('faq');
   }
}