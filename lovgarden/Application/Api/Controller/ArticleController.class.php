<?php
namespace Api\Controller;
use Think\Controller\RestController;
use Think\Cache\Driver\Memcache;
class ArticleController extends RestController {
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
           $helper = D('Home/Helper');
           $products = $helper->get_category_related_products($where);
       }
       echo json_encode(array(
            'faq' => $all_faq[$id],
            'products' => $products
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }
   public function getArticles($ids) {
       $articleModel = D('Article');
       $where['article_publish'] = array('EQ','1');
       $where['article_category'] = array('EQ','9');
       $where['id'] = array('IN',$ids);
       $articles = $articleModel->field('id,article_title,article_summary,article_category,banner_image')->where($where)->select();
       echo json_encode(array(
           'articles'=> $articles
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }
}
