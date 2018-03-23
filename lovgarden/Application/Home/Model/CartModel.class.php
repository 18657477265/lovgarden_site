<?php
namespace Home\Model;
use Think\Model;
use Think\Cache\Driver\Memcache;
class CartModel extends Model 
{
    //调用时候create方法允许接受的字段
    protected $insertFields = 'user_id,varient_id,deliver_time,vase';
    protected $updateFields = 'user_id,varient_id,deliver_time,vase';
    protected $_validate = array(
        array('user_id','require','用户不能为空',1),
        array('varient_id','require','商品编号不能为空',1),
        array('deliver_time','require','配送日期不能为空',1),
        array('vase',array('1','2'),'花瓶选项非法',0,'in'),
        array('deliver_time','checkIsFurture','请选择当下或将来日期',1,'callback',3),
        array('varient_id','productIdIsRight','商品编号非法',1,'callback',3),
        array('deliver_time','checkDeliverTodayAllowed','本商品不支持今日配送',1,'callback',3),
    );
    
    //检查日期格式是否有效
    function checkDateIsValid($deliver_time) {
        $unixTime = strtotime($deliver_time);
        if ($unixTime) { //strtotime转换不对，日期格式显然不对。
            return TRUE;
        }
        return false;
    }
    //检查日期是否是未来，如果是过去返回错误
    function checkIsFurture($deliver_time) {
        if($this->checkDateIsValid($deliver_time)) {
            $today = date("Y-m-d");
            if(strtotime($deliver_time)-strtotime($today) >= 0){
                return TRUE;
            }
        }
        return FALSE;        
    }
    //检查商品编号是存在的
    function productIdIsRight($varient_id) {
        //先看看memcache 里面有没有全部商品的sku_id值，没有的话去数据库检查，检查完后保存进memcache
        $mem_case = new Memcache();
        $product_sku_ids = $mem_case->get('sku_ids');
        if(!empty($product_sku_ids)) {
            $product_sku_ids = unserialize($product_sku_ids);
            if(in_array($varient_id, $product_sku_ids)){
                return TRUE;
            }
            return FALSE;
        }
        else {
            $sql = 'select sku_id from lovgarden_product_varient';
            $model = new Model();
            $results = $model->query($sql);
            $product_sku_ids = array();
            foreach($results as $k => $v) {
                $product_sku_ids[] = $v['sku_id'];
            }
            $mem_case->set('sku_ids', serialize($product_sku_ids), 604800);
            if(in_array($varient_id, $product_sku_ids)){
                return TRUE;
            }
            return FALSE;
        }
    }
    //检查该商品编号下的允许配送日期是否支持当日配送
    function checkDeliverTodayAllowed($deliver_time) {        
        $unix_time = strtotime($deliver_time); 
        $deliver_date = date('Y-m-d',$unix_time);
        $today = date('Y-m-d');
        if($deliver_date == $today) {
            //输入的日期是今天，查看该商品是不是允许今天下单    
            $sku_id = I('post.sku_id');
            //这里还是得使用memcache,防止多次查询数据库的情况发生
            //注意：之所以直接从后台取数据而不从模板里面传上来是怕有人故意传错误的值上来
            $mem_check_deliver = new Memcache();
            $name = $sku_id.'deliver_status';
            $count = $mem_check_deliver->get($name);
            if(empty($count)){
                $sql = "SELECT b.hurry_level_id  from lovgarden_product_varient AS a LEFT JOIN lovgarden_product_varient_hurry_level AS b ON a.`id`=b.`product_varient_id` WHERE a.`sku_id`='$sku_id';";
                $model = new Model();
                $result = $model->query($sql);
                $count = count($result);//如果配送选择大于1的说明肯定支持当天配送，否则肯定是预约配送
                $mem_check_deliver->set($name,$count,86400);
            }
            if($count > 1) {
                return TRUE;
            }
            else {
                return FALSE;
            }
        }
        //如果输入的配送日期不是今天，则直接通过
        else {
            return TRUE;
        }
    }
}












