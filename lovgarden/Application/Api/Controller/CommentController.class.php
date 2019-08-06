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
             echo $info['file']['savepath'].$info['file']['savename'];
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
           $sku_ids = I('get.sku_ids');
           $sku_ids = json_decode($sku_ids);
           $sku_ids = implode(",", $sku_ids);
           $products_names = I('get.products_names');
           $products_names = json_decode($products_names);
           $products_names = implode(",", $products_names);
           $uploadImages = I('get.uploadImages');
           $uploadImages = json_decode($uploadImages); 
           $uploadImages = implode(",", $uploadImages);
           $words = I('get.words');
           $open_id = $login_exist;
           
           echo $sku_ids;
           exit();
           
           
           $comment = D('Comment');
           $result = $comment->addComment($open_id,$order_id,$sku_ids,$words,$uploadImages);
           if($result) {
              $add_result = '200';
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
}
