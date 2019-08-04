<?php
namespace Api\Model;
use Think\Model;
use Think\Cache\Driver\Memcache;
class CommentModel extends Model {
    protected $insertFields = 'open_id,order_id,sku_ids,content_body,image_urls';
    protected $_validate = array(
        array('order_id', '', '该订单已评论',0,'unique',1),//新增字段时候验证唯一性，编辑字段时候不验证          
    );
    public function checkUserUploadFilesTimes() {
        $user_ip = getIP();
        $key = $user_ip.'comment';
        $memcache = new Memcache();
        $upload_times = $memcache->get($key);
        if(!empty($upload_times)) {
            if($upload_times>10) {
                return FALSE;
            }
            else {
                $upload_times = $upload_times +1;
                $memcache->set($key,$upload_times,86400);
                return TRUE;
            }
        }
        else {
            $memcache->set($key,1,86400);
            return TRUE;
        }        
    }
    public function addComment($open_id,$order_id,$sku_ids,$comment_words,$comment_images) {
        $data['open_id'] = $open_id;
        $data['order_id'] = $order_id;
        $data['sku_ids'] = $sku_ids;
        $data['content_body'] = $comment_words;
        $data['image_urls'] = $comment_images;    
        $status = $this->create($data);
        if($status) {
            $success_id = $this->add($status);
            if($success_id) {
                return TRUE;
            }
        }
        return FALSE;        
        /*
        $sql = "INSERT INTO lovgarden_comment (open_id,order_id,sku_ids,content_body,image_urls) VALUES ($open_id,$order_id,$sku_ids,$comment_words,$comment_images)";
        $result = $this->execute($sql);
        if($result) {
            return TRUE;
        }
        else {
            return FALSE;
        }
         */
    }
}



