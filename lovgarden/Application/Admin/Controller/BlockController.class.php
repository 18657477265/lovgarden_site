<?php
namespace Admin\Controller;
use Think\Controller;
class BlockController extends BaseController {
    public function add(){
        if(IS_POST) {
            $data = array(
                    'block_title' => I('post.block_title',''),
                    'block_body' => I('post.block_body',''),
                    'block_link_title' => I('post.block_link_title',''),
                    'block_link' => I('post.block_link',''),
                    'page_link' => I('post.page_link',''),
                    'block_order' => I('post.block_order',''),
            );
            $Block_model = D('Block');
            $result_status = $Block_model->block_add($data);
            if($result_status) {
                //说明添加成功
                $this->redirect('Success/success');
            }
            else {
                $this->assign(array(
                   'errorMessage' => $Block_model->getError(),
                   'data' => $data
                ));
            }          
        }
        $this->display('block_add'); 
    }
    
    public function update($id = '') {
        if(!empty($id)) {
            if(IS_POST) {
                //收集提交的post数据
                $data = array(
                    'id' => $id,
                    'block_title' => I('post.block_title',''),
                    'block_body' => I('post.block_body',''),
                    'block_link_title' => I('post.block_link_title',''),
                    'block_link' => I('post.block_link',''),
                    'page_link' => I('post.page_link',''),
                    'block_order' => I('post.block_order',''),
                );
                $block_model = D('Block');
                $update_status = $block_model->block_update($data,$id);
                if($update_status) {
                    $this->redirect("Success/success");
                } else {
                    //这里保存老的图片，以防验证不通过时候用
                    $old_block_content = $block_model->find($id);
                    $image_pc = $old_block_content['image_pc'];
                    $image_mobile = $old_block_content['image_mobile'];
                    $data['image_pc'] = $image_pc;
                    $data['image_mobile'] = $image_mobile;
                    $this->assign(array(
                       'errorMessage' => $block_model->getError(),
                       'data' => $data,
                    ));
                    $this->display('block_update');
                }
            }
            else {
                $block_model = D('Block');
                $data = $block_model->where("id = $id")->find();
                if($data) {
                    $this->assign(array(
                       'data' => $data 
                    ));
                    $this->display('block_update');
                }
                else {
                  //跳转到block list页面
                  $this->redirect("Success/failure");     
                }
            }
        }
        else {
            //跳转到block list页面
            $this->redirect("Success/failure");        
        }
    }
    
    public function block_list($keyword = '') {
        $where = array();
        $filter_selection = array();
        if(!empty($keyword)) {
            $where['_string'] = "( block_title like '%$keyword%')  OR ( page_link like '%$keyword%')";
            $filter_selection["block_info"] = $keyword;
        }
        $block_model = D("Block");
        $all_blocks = $block_model->where($where)->select();
        $count = count($all_blocks);
        $Page  = new \Think\Page($count,C('PRODUCT_VARIENT_PAGE')['page_count']);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show  = $Page->show();// 分页显示输出
        
        $blocks = $block_model->field('id , image_pc , image_mobile , block_title , page_link , block_order')->where($where)->limit($Page->firstRow,$Page->listRows)->select();

        $this->assign(array(
            'blocks' => $blocks,
            'page' => $show,
            'filter_selection' => $filter_selection
        ));// 赋值数据集
        $this->display('block_list');
    }
    
    public function ajax_block_delete() {
        if(IS_POST) {
           $row_id = $_POST['row_id'];
           $block_model = D('Block');
           $delete_status = $block_model->where(array(
               'id' => $row_id,
           ))->delete();
           if(delete_status) {
               echo '1';//表示删除成功
           }
           else {
               echo '2';//表示删除失败
           }
        }
    }
}