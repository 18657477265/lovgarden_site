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
       //????????????id,???????????,?????,???????????
       $mem = new Memcache();
       $faq_ids = $mem->get('faq_ids');
       //????id?????????????      
       if(in_array($id, $faq_ids) || empty($faq_ids)) {           
            $article = D('Article');
            $all_faq = $article->field('id,article_title,article_summary,article_body,article_category,banner_image')->where(array(
                'article_publish' => 1,
                'article_category' => 7
            ))->select();
            $all_faq = translate_database_result_to_logic_array($all_faq,array(),'id');
            if(!empty($all_faq[$id])) {
                //???????id,????
                if(empty($faq_ids)) {
                    $faq_ids = array();
                    foreach($all_faq as $k=>$v) {
                        $faq_ids[] = $v['id'];
                    }
                    $mem->set('faq_ids', $faq_ids, '604800');
                }
                $this->assign(array(
                    'faq' => $all_faq[$id],
                    'all_faq' => $all_faq
                ));
                $this->display('faq');
                exit();
            }
       }
       $this->display("Common:404");
   }
}