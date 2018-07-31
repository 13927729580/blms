<?php

/*****************************************************************************
 * Seevia 订单商品列表管理
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
 *这是一个名为 OrderProductsCtroller 的控制器
 *用户题库管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Controller', 'Commons');
class OrderProductsController extends AppController
{
    public $name = 'OrderProducts';
    public $components = array('Pagination', 'RequestHandler', 'Phpexcel', 'Phpcsv');
    public $helpers = array('Pagination', 'Html', 'Form', 'Javascript', 'Time', 'Ckeditor');
    public $uses = array('OrderProduct','CategoryProduct','Product','Brand','User','OrderProductMedia','Resource','Operator','OrderProductAction','OrderProductValue','InformationResource','OrderShipment','OrderShipmentProduct','Attribute');

    /**
     *显示列表.
     */
    public function index($page = 1)
    {
        $this->operation_return_url(true);
        $this->menu_path = array('root' => '/oms/', 'sub' => '/order_products/');
        $this->navigations[] = array('name' => $this->ld['transactions'], 'url' => '');
        $this->navigations[] = array('name' => "订单商品管理", 'url' => '/order_products/');
        //分类树
        $product_category_tree = array();
        $category_tree = $this->CategoryProduct->tree('P','all',$this->backend_locale);
        $this->CategoryProduct->set_locale($this->backend_locale);
        $category_name_list = array();
        if (isset($category_tree) && sizeof($category_tree) > 0) {
            foreach ($category_tree as $first_k => $first_v) {
                $category_name_list[$first_v['CategoryProduct']['id']] = $first_v['CategoryProductI18n']['name'];
                $product_category_tree[$first_v['CategoryProduct']['id']] = $first_v['CategoryProductI18n']['name'];
                if (isset($first_v['SubCategory']) && sizeof($first_v['SubCategory']) > 0) {
                    foreach ($first_v['SubCategory'] as $second_k => $second_v) {
                        $category_name_list[$second_v['CategoryProduct']['id']] = '--'.$second_v['CategoryProductI18n']['name'];
                        $product_category_tree[$second_v['CategoryProduct']['id']] = $second_v['CategoryProductI18n']['name'];
                        if (isset($second_v['SubCategory']) && sizeof($second_v['SubCategory']) > 0) {
                            foreach ($second_v['SubCategory'] as $third_k => $third_v) {
                                $category_name_list[$third_v['CategoryProduct']['id']] = '----'.$third_v['CategoryProductI18n']['name'];
                                $product_category_tree[$third_v['CategoryProduct']['id']] = $third_v['CategoryProductI18n']['name'];
                            }
                        }
                    }
                }
            }
        }
        $this->set('category_name_list', $category_name_list);
        $this->set('product_category_tree', $product_category_tree);
        //品牌获取
        $brand_tree = $this->Brand->brand_tree($this->backend_locale);
        if (is_array($brand_tree)) {
            $brand_names = array();
            foreach ($brand_tree as $k => $v) {
                $brand_names[$v['Brand']['id']] = $v['Brand']['id'];
                $brand_names[$v['Brand']['id']] = $v['BrandI18n']['name'];
            }
            $this->set('brand_names', $brand_names);
        }
        $condition = '';
        $category_id = '';        //分类
        $brand_id = 0;            //品牌
        $delivery_status='';
        //品牌
        if (isset($this->params['url']['brand_id']) && $this->params['url']['brand_id'] != '0') {
            if ($this->params['url']['brand_id'] == -1) {
                $brand_ids_array = array();
                $code_brand_list = $this->Product->find('list', array('fields' => array('Product.brand_id', 'Product.brand_id'), 'group' => 'Product.brand_id'));
                $brand_id_list = $this->Brand->find('list', array('fields' => array('Brand.id', 'Brand.id')));
                $brand_ids_array = array_diff($code_brand_list, $brand_id_list);
                $condition['and']['Product.brand_id'] = $brand_ids_array;
            } else {
                $condition['and']['Product.brand_id ='] = $this->params['url']['brand_id'];
            }
            $brand_id = $this->params['url']['brand_id'];
        }
        $attr_cate = array();
        //商品分类搜索
        $category_arr = array();
        if (isset($_REQUEST['category_id']) && $_REQUEST['category_id'] != '') {
            $category_arr = array();
            $category_arr_id = explode(',', $_REQUEST['category_id']);
            foreach ($category_arr_id as $k => $v) {
                if ($v != '') {
                    $categry_parent_list = $this->CategoryProduct->find('list', array('conditions' => array('CategoryProduct.parent_id' => $v), 'fields' => array('CategoryProduct.id')));
                    if (count($categry_parent_list) > 0) {
                        foreach ($categry_parent_list as $kk => $vv) {
                            $category_arr[] = $vv;
                        }
                    }
                    $category_arr[] = $v;
                }
            }
            $category_ids = array();
            if (in_array('-1', $category_arr)) {
                $code_categry_list = $this->Product->find('list', array('fields' => array('Product.category_id', 'Product.category_id'), 'group' => 'Product.category_id'));
                $categry_id_list = $this->CategoryProduct->find('list', array('conditions' => array('CategoryProduct.type' => 'P'), 'fields' => array('CategoryProduct.id', 'CategoryProduct.id')));
                $category_ids = array_diff($code_categry_list, $categry_id_list);
                foreach ($category_ids as $k => $v) {
                    $category_arr[] = $v;
                }
            }
            $condition['and']['Product.category_id'] = $category_arr;
        }
        if (isset($this->params['url']['product_keyword']) && $this->params['url']['product_keyword'] != '') {
            	$condition['or']['OrderProduct.product_number like'] = "%".$this->params['url']['product_keyword']."%";
            	$condition['or']['OrderProduct.product_code like'] = "%".$this->params['url']['product_keyword']."%";
            	$condition['or']['OrderProduct.product_name like'] = "%".$this->params['url']['product_keyword']."%";
            	$this->set('product_keyword', $this->params['url']['product_keyword']);
        }
        if (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] != '') {
		$start_date = date('Y-m-d 00:00:00',strtotime($_REQUEST['start_date']));
		$condition['OrderProduct.created >='] = $start_date;
		$this->set('start_date', $_REQUEST['start_date']);
        }
        //下单结束时间
        if (isset($_REQUEST['end_date']) && $_REQUEST['end_date'] != '') {
		$end_date = date('Y-m-d 23:59:59',strtotime($_REQUEST['end_date']));
		$condition['OrderProduct.created <='] = $end_date;
		$this->set('end_date', $_REQUEST['end_date']);
        }
        if (isset($this->params['url']['order_manager']) && $this->params['url']['order_manager'] != '') {
            $user_condition['or']['Operator.name like'] = '%'.$_REQUEST['order_manager'] .'%';
            $user_ids=$this->Operator->find('list',array('fields'=>"Operator.id","conditions"=>$user_condition));
            $condition['and']['Order.order_manager'] = $user_ids;
            $this->set('order_manager', $_REQUEST['order_manager']);
        }
        if (isset($this->params['url']['delivery_status']) && $this->params['url']['delivery_status'] != '') {
            $delivery_status_value = explode(',',$this->params['url']['delivery_status']);
            $condition['and']['OrderProduct.delivery_status'] = $delivery_status_value;
            $delivery_status = $delivery_status_value;
        }
        if (isset($this->params['url']['check_status']) && $this->params['url']['check_status'] != '-1') {
            $condition['and']['Order.check_status'] = $this->params['url']['check_status'];
            $check_status = $this->params['url']['check_status'];
            $this->set('check_status', $_REQUEST['check_status']);
        }
        if (isset($this->params['url']['order_code']) && $this->params['url']['order_code'] != '') {
            $condition['and']['Order.order_code'] =$this->params['url']['order_code'];
            $this->set('order_code', $_REQUEST['order_code']);
        }
        if (isset($this->params['url']['consignee']) && $this->params['url']['consignee'] != '') {
            $condition['and']['Order.consignee'] = $this->params['url']['consignee'];
            $this->set('consignee', $_REQUEST['consignee']);
        }
        if (isset($this->params['url']['picker']) && $this->params['url']['picker'] != '') {
            $user_condition['or']['Operator.name like'] = '%'.$_REQUEST['picker'] .'%';
            $user_ids=$this->Operator->find('list',array('fields'=>"Operator.id","conditions"=>$user_condition));
            $condition['and']['OrderProduct.picker'] = $user_ids;
            $this->set('picker', $_REQUEST['picker']);
        }
        if (isset($this->params['url']['QC']) && $this->params['url']['QC'] != '') {
            $user_condition['or']['Operator.name like'] = '%'.$_REQUEST['QC'] .'%';
            $user_ids=$this->Operator->find('list',array('fields'=>"Operator.id","conditions"=>$user_condition));
            $condition['and']['OrderProduct.QC'] = $user_ids;
            $this->set('QC', $_REQUEST['QC']);
        }
        if (isset($this->params['url']['item_type']) && $this->params['url']['item_type'] != '') {
            $condition['OrderProduct.item_type'] = $this->params['url']['item_type'];
            $this->set('item_type', $_REQUEST['item_type']);
        }
        if (isset($this->params['url']['item_type_id']) && $this->params['url']['item_type_id'] != '') {
            $condition['OrderProduct.product_id'] = $this->params['url']['item_type_id'];
            $this->set('item_type_id', $_REQUEST['item_type_id']);
        }
        $this->set('category_arr', $category_arr);
        $total = $this->OrderProduct->find('count', array('conditions' => $condition));
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int)$this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'order_products', 'action' => 'index', 'page' => $page, 'limit' => $rownum);
        $options = array('page' => $page, 'show' => $rownum, 'total' => $total, 'modelClass' => 'OrderProduct');
        $this->Pagination->init($condition, $parameters, $options);
        $product_list = $this->OrderProduct->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum,'order' => 'OrderProduct.created asc,OrderProduct.id asc,OrderProduct.pre_delivery_time asc'));
        //pr($product_list);
        $cond = array();
        foreach ($product_list as $k13 => $v13) {
            $cond['and']['Operator.id'][]=$v13['Order']['order_manager'];
        }
        //pr($cond);

        $op_info = $this->Operator->find('all',array('conditions'=>$cond));
        $ope_info=array();
        foreach ($op_info as $k14 => $v14) {
            $ope_info[$v14['Operator']['id']] = $v14['Operator'];
        }
        //pr($ope_info);
        $this->set('ope_info',$ope_info);
        if(!empty($product_list)){
            $order_product_ids=array();
            $operator_ids=array();
            foreach($product_list as $k=>$v){
            	  $order_product_ids[]=$v['OrderProduct']['id'];
            	  $operator_ids[]=$v['OrderProduct']['picker'];
            	  $operator_ids[]=$v['Order']['order_manager'];
            	  $operator_ids[]=$v['OrderProduct']['QC'];
            }
            $operator_list=$this->Operator->find('list',array('fields'=>'Operator.id,Operator.name','conditions'=>array('Operator.id'=>$operator_ids)));
            //pr($operator_list);
            $order_product_medias=$this->OrderProductMedia->find('list',array('fields'=>'OrderProductMedia.order_product_id,OrderProductMedia.media','conditions'=>array('OrderProductMedia.order_product_id'=>$order_product_ids,'OrderProductMedia.type'=>'image','OrderProductMedia.media <>'=>'','OrderProductMedia.media_group'=>2)));
            //pr($order_product_medias);
            foreach($product_list as $k=>$v){
            		$product_list[$k]["OrderProductMedia"]=isset($order_product_medias[$v['OrderProduct']['id']])?$order_product_medias[$v['OrderProduct']['id']]:'';
			$product_list[$k]["OrderProduct"]["picker_name"]=isset($operator_list[$v['OrderProduct']['picker']])?$operator_list[$v['OrderProduct']['picker']]:'';
			$product_list[$k]["Order"]["manager_name"]=isset($operator_list[$v['Order']['order_manager']])?$operator_list[$v['Order']['order_manager']]:'';
			$product_list[$k]["OrderProduct"]["qc_name"]=isset($operator_list[$v['OrderProduct']['QC']])?$operator_list[$v['OrderProduct']['QC']]:'';
            }
        }
        //pr($product_list);
        $con = array();
        $condi = array();
        foreach ($product_list as $k11 => $v11) {
            $con['and']['User.id'][] = $v11['Order']['user_id'];
            $condi['and']['OrderProductValue.order_product_id'][] = $v11['OrderProduct']['id'];
        }
        $u_info = $this->User->find('all',array('conditions'=>$con));
        //pr($u_info);
        if(isset($u_info)&&count($u_info)>0){
            foreach ($u_info as $k12 => $v12) {
            $user_info[$v12['User']['id']] = $v12['User']['name'];
            }
            $this->set('user_info',$user_info);
        }
        $attr_info = $this->OrderProductValue->find('all',array('conditions'=>$condi));
        $attribute_list = $this->Attribute->find('all',array('conditions'=>array()));
        //pr($attribute_list);
        if(isset($attribute_list)&&count($attribute_list)>0){foreach ($attribute_list as $k => $v){
            if($v['Attribute']['type'] == 'customize'){
                foreach ($v['AttributeOption'] as $kk => $vv) {
                    $attr_che[$vv['option_value']] = $vv['option_name'];
                }
            }
        }} 
        if (isset($attr_che)&&$attr_che!='') {
            $this->set('attr_che',$attr_che);
        }
        foreach ($attr_info as $k21 => $v21) {
            $attr_list[$v21['OrderProductValue']['order_product_id']][$v21['OrderProductValue']['attribute_id']] = $v21['OrderProductValue']['attribute_value'];
        }
        if(isset($attr_list)&&$attr_list!=''){
            $this->set('attr_list',$attr_list);
        }
        
        //pr($attr_list);
        $attr_check = $this->Attribute->find('all',array());
        $this->set('attr_check',$attr_check);
        //pr($attr_check);
        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('order_product_status'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
        $this->set('delivery_status', $delivery_status);
        $this->set('brand_tree', $brand_tree);
        $this->set('product_list', $product_list);
        $this->set('title_for_layout', "订单商品管理" . ' - ' . $this->ld['page'] . ' ' . $page . ' - ' . $this->configs['shop_name']);
    }

    /**
     *查看详情
     */
    public function view($id)
    {
    	$this->operation_return_url(true);
        $this->menu_path = array('root' => '/oms/','sub' => '/order_products/');
        $this->navigations[] = array('name' => $this->ld['transactions'], 'url' => '');
        $this->navigations[] = array('name' => "订单商品管理", 'url' => '/order_products/');
        $this->navigations[] = array('name' => $this->ld['edit'],'url' => '');
        $order_product_info=$this->OrderProduct->find('first',array('conditions'=>array('OrderProduct.id'=>$id)));
        //品牌获取
        //pr($order_product_info);
        $u_info = $this->User->find('first',array('conditions'=>array('User.id'=>$order_product_info['Order']['user_id'])));
        //pr($u_info);
        $this->set('u_info',$u_info);
        $cond = array();
        $cond['Operator.id']=$order_product_info['Order']['order_manager'];
        $op_info = $this->Operator->find('first',array('conditions'=>$cond));
        //pr($op_info);exit();
        $ope_info[$op_info['Operator']['id']] = $op_info['Operator'];
        //pr($ope_info);exit();
        $this->set('ope_info',$ope_info);
        
        $service_type_info = $this->Resource->getformatcode(array('order_product_service_type'), $this->locale);
        $this->set('service_type_info',$service_type_info);
        $brand_tree = $this->Brand->brand_tree($this->backend_locale);
        if (is_array($brand_tree)) {
            $brand_names = array();
            foreach ($brand_tree as $k => $v) {
                $brand_names[$v['Brand']['id']] = $v['Brand']['id'];
                $brand_names[$v['Brand']['id']] = $v['BrandI18n']['name'];
            }
            $this->set('brand_names', $brand_names);
        }
        //分类树
        $location_info = $this->InformationResource->information_formated(array('clothes_location'), $this->locale);
        $this->set('location_info',$location_info);
        $location_info = $this->InformationResource->information_formated(array('clothes_location'), $this->locale);
        $this->set('location_info',$location_info);
        $product_category_tree = array();
        $category_tree = $this->CategoryProduct->tree('P','all',$this->backend_locale);
        $this->CategoryProduct->set_locale($this->backend_locale);
        $category_name_list = array();
        if (isset($category_tree) && sizeof($category_tree) > 0) {
            foreach ($category_tree as $first_k => $first_v) {
                $category_name_list[$first_v['CategoryProduct']['id']] = $first_v['CategoryProductI18n']['name'];
                $product_category_tree[$first_v['CategoryProduct']['id']] = $first_v['CategoryProductI18n']['name'];
                if (isset($first_v['SubCategory']) && sizeof($first_v['SubCategory']) > 0) {
                    foreach ($first_v['SubCategory'] as $second_k => $second_v) {
                        $category_name_list[$second_v['CategoryProduct']['id']] = '--'.$second_v['CategoryProductI18n']['name'];
                        $product_category_tree[$second_v['CategoryProduct']['id']] = $second_v['CategoryProductI18n']['name'];
                        if (isset($second_v['SubCategory']) && sizeof($second_v['SubCategory']) > 0) {
                            foreach ($second_v['SubCategory'] as $third_k => $third_v) {
                                $category_name_list[$third_v['CategoryProduct']['id']] = '----'.$third_v['CategoryProductI18n']['name'];
                                $product_category_tree[$third_v['CategoryProduct']['id']] = $third_v['CategoryProductI18n']['name'];
                            }
                        }
                    }
                }
            }
        }
        $operator_list = $this->Operator->find('list',array('fields' => array('Operator.id','Operator.name'),'conditions'=>array('Operator.status'=>'1')));

        $this->set('operator_list',$operator_list);
        $action_list = $this->OrderProductAction->find('all',array('conditions'=>array('OrderProductAction.order_product_id'=>$id)));
        if(!empty($action_list)){
            foreach($action_list as $k=>$v){
                $operator_action = $this->Operator->find('first',array('fields' => array('Operator.name'),'conditions'=>array('Operator.id'=>$v['OrderProductAction']['operator_id'])));
                $action_list[$k]['OrderProductAction']['operator_name']=!empty($operator_action)?$operator_action['Operator']['name']:"";
            }
        }
        $media_primary_list = $this->OrderProductMedia->find('first',array('conditions'=>array('OrderProductMedia.order_product_id'=>$id,'OrderProductMedia.media_group'=>2)));
        $this->set('media_primary_list', $media_primary_list);
        $media_list = $this->OrderProductMedia->find('all',array('conditions'=>array('OrderProductMedia.order_product_id'=>$id)));
        if(!empty($media_list)){
            foreach($media_list as $k=>$v){
                $operator_media = $this->Operator->find('first',array('fields' => array('Operator.name'),'conditions'=>array('Operator.id'=>$v['OrderProductMedia']['operator_id'])));
                $media_list[$k]['OrderProductMedia']['operator_name']=!empty($operator_media)?$operator_media['Operator']['name']:"";
            }
        }
        
        $order_product_value_info=$this->OrderProductValue->find('all',array('conditions'=>array('OrderProductValue.order_product_id'=>$id)));
        if(!empty($order_product_value_info)){
        		$attribute_ids=array();
        		foreach($order_product_value_info as $v)$attribute_ids[]=$v['OrderProductValue']['attribute_id'];
        		$this->loadModel('Attribute');
        		$this->Attribute->set_locale($this->backend_locale);
        		$attribute_infos=$this->Attribute->find('all',array('conditions'=>array('Attribute.id'=>$attribute_ids)));
        		$attribute_list=array();
        		foreach($attribute_infos as $v){
        			$attribute_list[$v['Attribute']['id']]=$v;
        		}
        		$this->set('attribute_list',$attribute_list);
        }
        $this->set('order_product_value_info',$order_product_value_info);
        
        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('order_product_status'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
        $this->set('action_list', $action_list);
        $this->set('media_list', $media_list);
        $this->set('product_category_tree', $product_category_tree);
        $this->set('brand_tree', $brand_tree);
        $this->set('order_product_info', $order_product_info);
        $this->set('title_for_layout',"编辑订单商品".' - '.$this->configs['shop_name']);

        $order_id = $order_product_info['OrderProduct']['order_id'];
        
        if($order_product_info['OrderProduct']['delivery_status']==5){
            $order_shipment_address_cond = array('OrderShipment.order_id'=>$order_id,'OrderShipment.status'=>'1');
            $order_shipment_prouduct = $this->OrderShipmentProduct->find('first',array('conditions'=>array('OrderShipmentProduct.order_product_id'=>$id)));
            if(isset($order_shipment_prouduct['OrderShipmentProduct']['id'])){
                $order_shipment_address_cond['OrderShipment.id'] = $order_shipment_prouduct['OrderShipmentProduct']['order_shipment_id'];
            }
        }else{
            $order_shipment_address_cond = array('OrderShipment.order_id'=>$order_id,'OrderShipment.status'=>'0');
        }
        
        $order_shipment_address = $this->OrderShipment->find('first',array('conditions'=>$order_shipment_address_cond));
        $this->set('order_shipment_address',$order_shipment_address);
    }

    public function change_prev_time($id=0){
        $result = array();
        $result['code'] = 0;
        if(!empty($_POST)){
            if(isset($_POST['prev_time'])&&$_POST['prev_time']!=''){
                $order_pro_info = $this->OrderProduct->find('first',array('conditions'=>array('OrderProduct.id'=>$id)));
                //pr($order_pro_info);
                $order_pro_info['OrderProduct']['pre_delivery_time'] = $_POST['prev_time'];
                //pr($order_pro_info);exit();
                $this->OrderProduct->save($order_pro_info);
                $result['code'] = 1;
            }
        }
        die(json_encode($result));
    }

    public function change_pro_prev_time(){
        $result = array();
        $result['code'] = 0;
        if(!empty($_POST)){
            if(isset($_POST['order_product_id'])&&$_POST['order_product_id']!=''){
                $order_pro_info = $this->OrderProduct->find('first',array('conditions'=>array('OrderProduct.id'=>$_POST['order_product_id'])));
                //pr($order_pro_info);
                $t=time();
                $order_pro_info['OrderProduct']['pre_delivery_time'] = date("Y-m-d",$t);
                //pr($order_pro_info);exit();
                $this->OrderProduct->save($order_pro_info);
                $result['code'] = 1;
            }
        }
        die(json_encode($result));
    }
}