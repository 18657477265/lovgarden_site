<?php
namespace Api\Model;
use Think\Model;
use Think\Cache\Driver\Memcache;
class WxuserModel extends Model 
{
    //调用时候create方法允许接受的字段
    protected $insertFields = 'open_id,telephone';
    protected $updateFields = 'open_id,telephone,wxuser_status';
    protected $_validate = array(
        array('open_id', '', 'exist', 1, 'unique', 1),
    );
    function add_wxuser($open_id,$telephone = '') {
        $data = array();
        $data['open_id'] = $open_id;
        $data['telephone'] = $telephone;    
        $data = $this->create($data);
        if($data) {
            $wxuser_id = $this->add($data);
            if($wxuser_id) {
                return TRUE;   
            }            
        }
        return FALSE;
    }

    function update_wxuser($open_id,$telephone = '') {
        $data = array();
        $data['open_id'] = $open_id;
        $data['telephone'] = $telephone;
        $result = $this->where("open_id = $open_id")->save($data);
        if($result !== FALSE) {
            return true;
        }
        else {
            return false;
        }
    }
}



