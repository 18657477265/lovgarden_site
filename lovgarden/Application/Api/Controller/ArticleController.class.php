<?php
namespace Api\Controller;
use Think\Controller\RestController;
use Think\Cache\Driver\Memcache;
class ArticleController extends RestController {
   public function category($id = 0) {
       //设置缓存,避免重复
       $mem_cache = new Memcache();
              
       $products = '';
       $all_faq = $mem_cache->get('all_faq');
       if(empty($all_faq)) {
         $article = D('Article');
         $all_faq = $article->field('id,article_title,article_summary,article_body,article_category,banner_image,related_article_ids')->where(array(
                'article_publish' => 1,
                'article_category' => 9
         ))->select();
         $all_faq = translate_database_result_to_logic_array($all_faq,array(),'id');
       
         $mem_cache->set('all_faq',$all_faq,86400);
       }
       
       if(!empty($all_faq[$id]['related_article_ids'])){
           $products = $mem_cache->get('all_faq'.$id);
           if(empty($products)) {
             $where = array(
               'product.id' => array('IN', explode(',', $all_faq[$id]['related_article_ids'])),
             );
             $helper = D('Home/Helper');
             $products = $helper->get_category_related_products($where);
             $mem_cache->set('all_faq'.$id,$products,86400);
           }
       }
       echo json_encode(array(
            'faq' => $all_faq[$id],
            'products' => $products
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }
   public function getArticles($ids) {
       $mem_cache = new Memcache();
       $articles = $mem_cache->get('articles'.$ids);
       if(empty($articles)) {
         $articleModel = D('Article');
         $where['article_publish'] = array('EQ','1');
         $where['article_category'] = array('EQ','9');
         $where['id'] = array('IN',$ids);
         $articles = $articleModel->field('id,article_title,article_summary,article_category,banner_image')->where($where)->select();
         $articles = translate_database_result_to_logic_array($articles,array(),'id');
       
         $mem_cache->set('articles'.$ids,$articles,86400);
       }
       $likes_articles = $mem_cache->get('likes_articles');
       if($likes_articles) {
           foreach ($articles as $key => $article) {
               $articles[$key]['likes'] =  $likes_articles[$key]['likes'];
           }
       }
       echo json_encode(array(
           'articles'=> $articles
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }
   public function faq($id) {
       $mem = new Memcache();
       $all_faq = $mem->get('all_faqs');
       if(empty($all_faq)) {
         $article = D('Article');
         $all_faq = $article->field('id,article_title,article_summary,article_body,article_category,banner_image')->where(array(
              'article_publish' => 1,
              'article_category' => 7
         ))->select();
         $all_faq = translate_database_result_to_logic_array($all_faq,array(),'id');
         $mem->set('all_faqs', $all_faq, '604800');
       }
       echo json_encode(array(
            'faq' => $all_faq[$id]
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }
}
