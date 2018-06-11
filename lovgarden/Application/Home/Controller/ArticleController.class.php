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
       //查询时候先检查缓存有没有id，如果没有或者缓存不存在，进入下一层，否则直接返回页面不存在
       $mem = new Memcache();
       $faq_ids = $mem->get('faq_ids');
       //避免因为id不存在而导致的不必要的查询      
       if(in_array($id, $faq_ids) || empty($faq_ids)) {           
            $article = D('Article');
            $all_faq = $article->field('id,article_title,article_summary,article_body,article_category,banner_image')->where(array(
                'article_publish' => 1,
                'article_category' => 7
            ))->select();
            $all_faq = translate_database_result_to_logic_array($all_faq,array(),'id');
            if(!empty($all_faq[$id])) {
                //收集可以使用的id,放进缓存
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
   public function category($id = 0) {
       $products = '';
       $article = D('Article');
       $all_faq = $article->field('id,article_title,article_summary,article_body,article_category,banner_image,related_article_ids')->where(array(
                'article_publish' => 1,
                'article_category' => 9
       ))->select();
       $all_faq = translate_database_result_to_logic_array($all_faq,array(),'id');
       if(!empty($all_faq[$id]['related_article_ids'])){
           $where = array(
             'product.id' => array('IN', explode(',', $all_faq[$id]['related_article_ids'])),
           );
           $helper = D('Helper');
           $products = $helper->get_category_related_products($where);
       }
       $this->assign(array(
            'faq' => $all_faq[$id],
            'products' => $products
       ));
       $this->display('detail');
       
       
       //$where = array(
           //'flowerhomeid.flower_home_id' => '4'
       //);
       //$helper = D('Helper');
       //$helper->get_category_related_products($where);
   }
   
}