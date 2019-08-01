<?php
namespace Api\Model;
use Think\Model;
use Think\Cache\Driver\Memcache;
class WxuserModel extends Model 
{
    //调用时候create方法允许接受的字段
    protected $insertFields = 'open_id,telephone,nickname,avatarurl';
    protected $updateFields = 'open_id,telephone,wxuser_status,nickname,avatarurl';
    protected $_validate = array(
        array('open_id', '', 'exist', 1, 'unique', 1),
    );
    function add_wxuser($open_id,$nickname = '',$avatarurl = '',$telephone = '') {
        $data = array();
        $data['open_id'] = $open_id;
        $data['telephone'] = $telephone;
        $data['nickname'] = $nickname;
        $data['avatarurl'] = $avatarurl;
        $data_check = $this->create($data);
        if($data_check) {
            $wxuser_id = $this->add($data_check);
            if($wxuser_id) {
                return TRUE;   
            }            
        }
        else {
            if($nickname != '' && $avatarurl !='') {
            //更新用户的头像和昵称
              if($telephone == '') {
                unset($data['telephone']);
              }
              $this->where("open_id=$open_id")->save($data);
            }
        }
        return FALSE;
    }

    function update_wxuser($open_id = '0',$telephone = '0') {
        //$data = array();
        //$data['open_id'] = $open_id;
        //$data['telephone'] = $telephone;
        $sql = "update lovgarden_wxuser set telephone = '".$telephone."' where open_id = '".$open_id."'";
        $result = $this->execute($sql);
        if($result !== FALSE) {
            return true;
        }
        else {
            return false;
        }
    }
}



