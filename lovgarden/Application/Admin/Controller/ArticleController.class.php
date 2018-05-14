<?php
namespace Admin\Controller;
use Think\Controller;
class ArticleController extends BaseController {
    public function add_article(){
        if(IS_POST) {
           $article = D('Article');
           if($article->add_article()) {
               $this->redirect('Success/success');
               exit();
           }
           else {
               $this->assign(array(
                   'errorMessage' => $article->getError(),
                   'data' => $article->model_data,
               ));
           }
        }
        $this->display('article');
    }
    public function update_article($id = 0) {
        if($id != 0){
            $article = D('Article');
            if(IS_POST) {
               if($article->update_article($id)) {
                  $this->redirect('Success/success');
                  exit();
               }
               else {
                   $data = $article->model_data;
                   $errorMessage = $article->getError();
                   $this->assign(array(
                      'errorMessage' => $errorMessage,
                      'data' => $data,
                   ));
               }
            }
            else {
              $data = $article->where("id = $id")->find();
              $this->assign(array(
                  'data'=>$data,
              ));
            }
            $this->display('update_article');
        }
        else {
            $this->redirect("Success/failure");     
        }
    }
    public function article_list($article_status='',$article_category = '', $article_info = ''){        
        $where = array();
        $filter_selection = array();
        if(!empty($article_status) && $article_status != 'all') {
            $where['articles.article_publish'] = array('EQ',$article_status);
            $filter_selection['article_status'] = $article_status;
        }
        if(!empty($article_category) && $article_category != 'all') {
            $where['articles.article_category'] = array('EQ',$article_category);
            $filter_selection['article_category'] = $article_category;
        }
        if(!empty($article_info)) {
            $where['_string'] = "( articles.article_title like '%$article_info%')  OR ( articles.article_body like '%$article_info%')";
            $filter_selection['article_info'] = $article_info;
        }               
        $articles = D('Article');
        $articles_list = $articles->get_article_list($where);
        $this->assign(array(
           'articles' => $articles_list['articles'],
           'page' => $articles_list['page_show'],
           'filter_selection' => $filter_selection,
        ));
        $this->display('article_list'); 
    }
}