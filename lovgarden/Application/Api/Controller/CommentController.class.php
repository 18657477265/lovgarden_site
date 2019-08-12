<?php
namespace Api\Controller;
use Think\Controller\RestController;
use Think\Cache\Driver\Memcache;
use Think\Image;
use Think\Upload;
class CommentController extends RestController {      
   public function  uploadImage() {
       if($_FILES['pic']['error'] == 0){
           $comment = D('Comment');
           if($comment->checkUserUploadFilesTimes()) {
             $info = uploadImage('comments', 'comments');
             $savePath = $info['file']['savepath'];
             $savePath = trim($savePath, "\xEF\xBB\xBF");
             $saveName = $info['file']['savename'];
             $result = $savePath.$saveName;            
             //生成缩略图
             $img = new Image();
             $image_config = C('IMAGE_CONFIG');  
             $big_img = $image_config['rootPath'].$result;
             $img->open($big_img);
             $img->thumb(100,100);
             $small_img = $image_config['rootPath'].$savePath.'small_'.$saveName;
             $img->save($small_img);
             echo $result;
           }
           else {
              echo 500;
           }
       }
   }
   public function addComment() {
       $add_result = '404';
       $login_ip = I('get.login_ip');
       $mem_cache = new Memcache();
       $login_exist = $mem_cache->get($login_ip);
       if(!empty($login_exist)){        
           $order_id = I('get.order_id');
           $sku_ids = $_GET['sku_ids'];
           $sku_ids = json_decode($sku_ids);
           $sku_ids = implode(",", $sku_ids);
           $products_names = $_GET['products_names'];
           $products_names = json_decode($products_names);
           $products_names = implode(",", $products_names);
           $uploadImages = $_GET['uploadImages'];
           $uploadImages = json_decode($uploadImages); 
           $uploadImages = implode(",", $uploadImages);
           $words = I('get.words');
           $open_id = $login_exist;
           
                    
           $comment = D('Comment');
           $result = $comment->addComment($open_id,$order_id,$sku_ids,$words,$uploadImages,$products_names);
           if($result) {
              $add_result = '200';
              //删除已经存在的个人评论数据缓存
              $mem_cache->rm($open_id.'noCommentOrders');
              $mem_cache->rm($open_id.'commentOrders');
           }
       }
       echo json_encode(array(
            'add_result' => $add_result
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }
   public function getCanCommentOrders() {
       $login_ip = I('get.login_ip');
       $comment = D('Comment');
       $orders = $comment->getMyComments($login_ip);
       echo json_encode($orders,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }
   public function getAllComments() {
       $login_ip = I('get.login_ip');
       $index = I('get.index');
       $comment = D('Comment');
       $bottom = '1';
       $indexed_comments = $comment->getAllComments($login_ip,$index);
       if(empty($indexed_comments)) {
           $bottom = '2';
       }
       echo json_encode(array(
           'comments' => $indexed_comments,
           'bottom' => $bottom
       ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
   }
}
