<?php
namespace Admin\Model;
use Think\Model;
class ArticleModel extends Model 
{
    public $model_data;
    //调用时候create方法允许接受的字段
    protected $insertFields = 'article_title,article_summary,article_author,article_body,article_publish,article_create_time,article_category,banner_image';
    protected $updateFields = 'article_title,article_summary,article_author,article_body,article_publish,article_category,banner_image';
    protected $_validate = array(
        array('article_title','require','文章标题不能为空',1,'',3),
        array('article_title','1,50','超出标题最大限制',1,'length'),
        array('article_summary','require','文章简单描述不能为空',1,'',3),
        array('article_summary','1,500','超出简单描述最大长度',1,'length'),
        array('article_author','require','文章作者不能为空',0,'',1),
        array('article_body','require','文章详细描述不能为空',1,'',3),
        array('article_publish',array('1','2','3'),'状态值不正确！',1,'in'), 
        array('article_category','categoryIdIsRight','文章种类不存在',1,'callback',3),
        array('banner_image','require','文章banner图不能为空',0,'',1),
    );
    
    function categoryIdIsRight($article_category) {
        $sql = 'select id from lovgarden_article_category';
        $model = new Model();
        $results = $model->query($sql);
        $article_categories_ids = array();
        foreach($results as $k => $v) {
            $article_categories_ids[] = $v['id'];
        }
        if(in_array($article_category, $article_categories_ids)){
            return TRUE;
        }
        return FALSE;
    }
    
    function add_article() {
        $data = array();
        $data['article_title'] = I('post.article_title','');
        $data['article_summary'] = I('post.article_summary','');
        $data['article_publish'] = I('post.article_publish','0');
        $data['article_category'] = I('post.article_category_name','0');
        $data['article_body'] = I('post.article_body','');
        $data['article_author'] = !empty(session('user_name'))? session('user_name'):'';
        $data['article_create_time'] = date('Y-m-d H:i:s');
        
        $uploaded_images = uploadImage('article','article');
        //if($uploaded_images) {          
        $data['banner_image'] = $uploaded_images['banner_image']['savepath'].$uploaded_images['banner_image']['savename'];             
        $this->model_data = $data;
        $data = $this->create($data);
        if($data) {
            $article_id = $this->add($data);
            if($article_id) {
                return TRUE;   
            }            
        }
        //}
        return FALSE;
    }
    function get_original_banner($id) {
        $banner_image = $this->query("select banner_image from lovgarden_article where id = '$id'");
        $banner_image = $banner_image[0]['banner_image'];
        return $banner_image;
    }
    function update_article($article_id) {
        $data = array();
        $data['article_title'] = I('post.article_title','');
        $data['article_summary'] = I('post.article_summary','');
        $data['article_publish'] = I('post.article_publish','0');
        $data['article_category'] = I('post.article_category_name','0');
        $data['article_body'] = I('post.article_body','');
        
        if(!empty($_FILES)) {
            if($_FILES["banner_image"]['error'] > 0) {
                unset($_FILES['banner_image']);
            }
            else {
                $uploaded_images = uploadImage('article','article');
                if($uploaded_images) {  
                    $data['banner_image'] = $uploaded_images['banner_image']['savepath'].$uploaded_images['banner_image']['savename'];             
                }
            }
        }
        //$this->model_data = $data;
        $save_data = $this->create($data);
        if($save_data) {
           $update_status = $this->where("id = $article_id")->save($save_data);
           if($update_status !== FALSE) {
                return TRUE;
           }
        }
        //make banner_image to old one to avoid misunderstanding
        $data['banner_image'] = $this->get_original_banner($article_id);
        $this->model_data = $data;
        return FALSE;
    }
}












