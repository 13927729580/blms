<?php

/*****************************************************************************
 * Seevia 媒体管理
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
 *****************************************************************************/
/**
 *这是一个名为 OrderProductMediasController 的控制器
 *媒体管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class OrderProductMediasController extends AppController
{
    public $name = 'OrderProductMedias';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('OrderProductMedia','OrderProduct');

    /**
     *添加媒体
     */
    public function add($id=0){
		if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
			Configure::write('debug', 1);
			$this->layout = 'ajax';
		}
		$this->menu_path = array('root' => '/oms/', 'sub' => '/order_products/');
		$this->navigations[] = array('name' => $this->ld['transactions'], 'url' => '');
		$this->navigations[] = array('name' => "订单商品管理", 'url' => '/order_products/');
		$this->navigations[] = array('name' => $this->ld['add']."媒体",'url' => '');
		$product_info=$this->OrderProduct->find('first',array('conditions'=>array('OrderProduct.id'=>$id)));
		if(empty($product_info))$this->redirect('/order_products/index');
		if ($this->RequestHandler->isPost()) {
			$this->data["OrderProductMedia"]["order_id"]=$product_info["Order"]["id"];
			$this->data["OrderProductMedia"]["order_product_id"]=$id;
			$this->data["OrderProductMedia"]["operator_id"]=$this->admin['id'];
			$this->OrderProductMedia->save($this->data);
			if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
				die(json_encode(array('code'=>'1')));
			}else{
				$back_url = $this->operation_return_url();//获取操作返回页面地址
				$this->redirect($back_url);
			}
		}
		$this->set('id',$id);
    }

    public function batch_add($id=0){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$this->menu_path = array('root' => '/oms/', 'sub' => '/order_products/');
		$this->navigations[] = array('name' => $this->ld['transactions'], 'url' => '');
		$this->navigations[] = array('name' => "订单商品管理", 'url' => '/order_products/');
		$this->navigations[] = array('name' => $this->ld['add']."媒体",'url' => '');
		$product_info=$this->OrderProduct->find('first',array('conditions'=>array('OrderProduct.id'=>$id)));
		if(empty($product_info))$this->redirect('/order_products/index');
		if ($this->RequestHandler->isPost()) {
			//pr($this->data);exit();
			$this->data["OrderProductMedia"]["order_id"]=$product_info["Order"]["id"];
			$this->data["OrderProductMedia"]["order_product_id"]=$id;
			$this->data["OrderProductMedia"]["operator_id"]=$this->admin['id'];
			$this->data["OrderProductMedia"]["type"]='image';
			foreach ($this->data['OrderProductMedia']['media'] as $k1 => $v1){
				if($v1!=''){
					$add_condition = array();
					$add_condition['id']=$this->data['OrderProductMedia']['id'][$k1];
					$add_condition['media'] = $this->data['OrderProductMedia']['media'][$k1];
					$add_condition['media_group'] = $this->data['OrderProductMedia']['media_group'][$k1];
					$add_condition['description'] = $this->data['OrderProductMedia']['description'][$k1];
					$add_condition['location'] = $this->data['OrderProductMedia']['location'][$k1];
					$add_condition['type'] = 'image';
					$add_condition['order_id'] = $this->data["OrderProductMedia"]["order_id"];
					$add_condition['order_product_id'] = $this->data["OrderProductMedia"]["order_product_id"];
					$add_condition['operator_id'] = $this->data["OrderProductMedia"]["operator_id"];
					$this->OrderProductMedia->save($add_condition);
				}
			}
			
			if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
				die(json_encode(array('code'=>'1')));
			}else{
				$back_url = $this->operation_return_url();//获取操作返回页面地址
				$this->redirect($back_url);
			}
		}
		$this->set('id',$id);
    }

    /**
     *编辑媒体
     */
    public function view($id=0)
    {
    	 if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
	 }
        $product_media_info=$this->OrderProductMedia->find('first',array('conditions'=>array('OrderProductMedia.id'=>$id)));
        if(empty($product_media_info))$this->redirect('/order_products/index');
        if ($this->RequestHandler->isPost()) {
	            	$this->OrderProductMedia->save($this->data);
			if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
				die(json_encode(array('code'=>'1')));
			}else{
				$back_url = $this->operation_return_url();//获取操作返回页面地址
				$this->redirect($back_url);
			}
        }
        $this->menu_path = array('root' => '/oms/', 'sub' => '/order_products/');
        $this->navigations[] = array('name' => $this->ld['transactions'], 'url' => '');
        $this->navigations[] = array('name' => "订单商品管理", 'url' => '/order_products/');
        $this->navigations[] = array('name' => $this->ld['edit']."媒体",'url' => '');
        $this->set('product_media_info', $product_media_info);
        $this->set('id',$id);
    }
    
    /**
     * 删除课时
     *
     *@param int $id
     */
    public function remove($id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];
        $this->OrderProductMedia->deleteAll(array('OrderProductMedia.id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_user'].':id '.$id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_member_success'];
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
        } else {
            $this->redirect('/order_products/');
        }
    }
    
    function ajax_upload_media(){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result=array();
		$result['code'] = 0;
		$result['message'] = $this->ld['operation_failed'];
		if ($this->RequestHandler->isPost()) {
			$result['message'] = $this->ld['file_upload_error'];
			$order_product_id=isset($_POST['order_product_id'])?$_POST['order_product_id']:0;
			if(isset($_FILES['product_media'])&&sizeof($_FILES['product_media']['size'])>0){
				$media_file_tmp=$_FILES['product_media']['tmp_name'];
				$filename=$_FILES['product_media']['name'];
				$file_info = pathinfo($filename);
				$file_ext = isset($file_info['extension'])?$file_info['extension']:'';
				$file_name=md5($filename.$order_product_id.time()).".".$file_ext;
				$media_root=WWW_ROOT.'media/order_product_media/';
				$this->mkdirs($media_root);
				@chmod($imgaddr, 0777);
				$uplod_files=array();
				$file_location=$media_root.$file_name;
				$file_path="/media/order_product_media/".$file_name;
				if (move_uploaded_file($media_file_tmp, $file_location)) {
					$result['code'] = 1;
					$result['message'] = $file_path;
				}
			}
		}
		die(json_encode($result));
    }
}