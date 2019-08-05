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
       $comment = D('Comment');
       $result = $comment->addComment($open_id,$order_id,$sku_ids,$comment_words,$comment_images);
       if($result) {
           $add_result = '200';
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
