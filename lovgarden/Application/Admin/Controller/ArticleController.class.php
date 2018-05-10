<?php
namespace Admin\Controller;
use Think\Controller;
class ArticleController extends Controller {
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
}