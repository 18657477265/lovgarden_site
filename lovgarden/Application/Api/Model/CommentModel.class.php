<?php
namespace Api\Model;
use Think\Model;
use Think\Cache\Driver\Memcache;
class CommentModel extends Model {
    protected $insertFields = 'open_id,order_id,sku_ids,content_body,image_urls,products_names,comment_date';
    protected $_validate = array(
        array('order_id', '', '该订单已评论',0,'unique',1),//新增字段时候验证唯一性，编辑字段时候不验证 
        array('content_body','require','内容不能为空',1,'',1),
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
    public function addComment($open_id,$order_id,$sku_ids,$comment_words,$comment_images,$products_names) {
        $data['open_id'] = $open_id;
        $data['order_id'] = $order_id;
        $data['sku_ids'] = $sku_ids;
        $data['content_body'] = $comment_words;
        //$data['image_urls'] = $comment_images;
        //$data['image_urls']  = trim($comment_images , "\xEF\xBB\xBF");
        $data['image_urls'] = str_replace("\xEF\xBB\xBF", '', $comment_images);
        $data['products_names'] = $products_names;
        $data['comment_date'] = date('Y-m-d H:i:s');
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
    public function getMyComments($login_ip) {
        $login_status = 404;
        $open_id = '0';
        $order_products_info = array();
        $my_comments = array();
        $mem_cache = new Memcache();
        $login_exist = $mem_cache->get($login_ip);
        if(!empty($login_exist)){
            $open_id = $login_exist;
            $login_status = 200;
            //获取此时可以评论的订单,也就是已经完成的订单
            $sql = "SELECT orders.id, orders.order_id,orders_products.product_sku_id,products.varient_name,products_images.image_url FROM lovgarden_order AS orders 
                    LEFT JOIN lovgarden_order_product_varient AS orders_products ON orders.id = orders_products.order_original_id 
                    LEFT JOIN lovgarden_product_varient AS products ON orders_products.product_sku_id = products.sku_id
                    LEFT JOIN lovgarden_product_varient_images AS products_images ON products.id = products_images.product_varient_id
                    WHERE orders.order_status = '4' AND orders.order_owner = '$login_exist'";
            $order_products = $this->query($sql);
            $order_products_info = translate_database_result_to_logic_array($order_products, array('image_url','product_sku_id','varient_name'), 'order_id');
            //获取已经评价的信息
    
            $sql2 = "select id,order_id,content_body,image_urls,products_names,comment_date from lovgarden_comment where open_id = '$login_exist'";            
            $my_comments = $this->query($sql2);
            if(!empty($my_comments)) {
                foreach($my_comments as $key => $value) {
                   $my_comments[$key]['image_urls'] = explode(",",$value['image_urls']);
                   $my_comments[$key]['product_image'] = $order_products_info[$value['order_id']]['image_url'][0];
                   //剔除掉订单里那些已评价的单
                   unset($order_products_info[$value['order_id']]);
                }
            }
        }
        return ['login_status'=> $login_status,'order_products_info' => $order_products_info,'my_comments'=>$my_comments];
    }
    public function getAllComments($login_ip,$index,$count = 5,$cache_time = 3600,$max = 999) {
 
          $offset = ceil($index * $count);
          $comment_cache = new Memcache();
          $login_exist = $comment_cache->get($login_ip);
          $all_comments = array();
          if(!empty($login_exist) && $offset < $max) {
            $key = "allComment".$offset;
            $all_comments = $comment_cache->get($key);
            if(empty($all_comments)) {
              $sql_comment = "select id,order_id,content_body,image_urls,products_names,comment_date from lovgarden_comment order by id desc limit $offset , $count";            
              $all_comments = $this->query($sql_comment);
              $comment_cache->set($key,$all_comments,$cache_time);
            }
          }
          return $all_comments;
    }
}



