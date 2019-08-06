<?php
namespace Api\Model;
use Think\Model;
use Think\Cache\Driver\Memcache;
class CommentModel extends Model {
    protected $insertFields = 'open_id,order_id,sku_ids,content_body,image_urls,products_names';
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
    public function addComment($open_id,$order_id,$sku_ids,$comment_words,$comment_images,$products_names) {
        $data['open_id'] = $open_id;
        $data['order_id'] = $order_id;
        $data['sku_ids'] = $sku_ids;
        $data['content_body'] = $comment_words;
        $data['image_urls'] = $comment_images;
        $data['products_names'] = $products_names;    
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
            
        }
        return ['login_status'=> $login_status,'order_products_info' => $order_products_info];
    }
}



