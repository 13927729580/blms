<?php

/*****************************************************************************
 * Seevia 订单报表控制器
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
 *这是一个名为 OrdersController 的控制器
 *后台订单管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class OrderReportsController extends AppController{
	public $name = 'OrderReports';
	public $helpers = array('Pagination','Ckeditor');
	public $components = array('Pagination','RequestHandler','Notify','Orderfrom','EcFlagWebservice','Phpexcel','Phpcsv');
	public $uses = array('Order','Operator','OrderProduct','User','OrderProductAction');
	
	function index(){
		$this->operation_return_url(true);//设置操作返回页面地址
        	//$this->menu_path = array('root' => '/report/','sub' => '/order_factory_reports/');
        	$this->navigations[] = array('name' => $this->ld['report'],'url' => '');
        	$this->navigations[] = array('name' => '财务收入','url' => '/order_reports/');
        	$this->pageTitle = '财务收入 - '.$this->configs['shop_name'];
        	$this->set('title_for_layout', $this->pageTitle);
        	
		$this->Order->hasMany = array();//去掉关联
		$this->Order->hasOne = array();//去掉关联
		$conditions=array();
		$conditions['Order.status']='1';
		if (isset($this->params['url']['order_date_start']) && $this->params['url']['order_date_start'] != '') {
			$conditions['Order.created >=']=date('Y-m-d 00:00:00',strtotime($this->params['url']['order_date_start']));
		}else{
			$conditions['Order.created >=']=date('Y-m-01 00:00:00');
		}
		$this->set('order_date_start',date('Y-m-d',strtotime($conditions['Order.created >='])));
		if (isset($this->params['url']['order_date_end']) && $this->params['url']['order_date_end'] != '') {
			$conditions['Order.created <=']=date('Y-m-d 23:59:59',strtotime($this->params['url']['order_date_end']));
		}else{
			$conditions['Order.created <=']=date('Y-m-31 23:59:59');
		}
		$this->set('order_date_end',date('Y-m-d',strtotime($conditions['Order.created <='])));
		$date_type="month";
		if (isset($this->params['url']['date_type']) && $this->params['url']['date_type'] != '') {
			$date_type=$this->params['url']['date_type'];
		}
		$this->set('date_type',$date_type);
		$order_date="date_format(Order.created,'%y/%m') as order_date";
		if($date_type=="year"){
			$order_date="date_format(Order.created,'%y') as order_date";
		}else if($date_type=="day"){
			$order_date="date_format(Order.created,'%y/%m/%d') as order_date";
		}else if($date_type=="week"){
			$order_date="date_format(Order.created,'%v') as order_date";
		}
		$fields=array($order_date,'SUM(Order.total) as order_total');
		$report_info = $this->Order->find('all', array('conditions' => $conditions, 'fields' => $fields,'group'=>'order_date', 'order' => 'order_date'));
		$report_list=array();
		if(!empty($report_info)){
			foreach($report_info as $v){
				$report_list[$v[0]['order_date']]=$v[0]['order_total'];
			}
		}
		$this->set('report_list',$report_list);
	}
	
	function order_manager(){
		$this->operation_return_url(true);//设置操作返回页面地址
        	//$this->menu_path = array('root' => '/report/','sub' => '/order_factory_reports/');
        	$this->navigations[] = array('name' => $this->ld['report'],'url' => '');
        	$this->navigations[] = array('name' => '销售顾问业绩','url' => '/order_reports/');
        	$this->pageTitle = '销售顾问业绩 - '.$this->configs['shop_name'];
        	$this->set('title_for_layout', $this->pageTitle);
        	
		$operator_list=$this->Operator->find('list',array('fields'=>'id,name','conditions'=>array('Operator.name <>'=>'')));
		$this->set('operator_list',$operator_list);
		
		$conditions=array();
		$conditions['Order.status']='1';
		if (isset($this->params['url']['order_manager']) && $this->params['url']['order_manager'] != '') {
			$conditions['Order.order_manager']=$this->params['url']['order_manager'];
			$this->set('order_manager',$this->params['url']['order_manager']);
		}else{
			$conditions['Order.order_manager >']=0;
		}
		if (isset($this->params['url']['order_date_start']) && $this->params['url']['order_date_start'] != '') {
			$conditions['Order.created >=']=date('Y-m-d 00:00:00',strtotime($this->params['url']['order_date_start']));
		}else{
			$conditions['Order.created >=']=date('Y-m-01 00:00:00');
		}
		$this->set('order_date_start',date('Y-m-d',strtotime($conditions['Order.created >='])));
		if (isset($this->params['url']['order_date_end']) && $this->params['url']['order_date_end'] != '') {
			$conditions['Order.created <=']=date('Y-m-d 23:59:59',strtotime($this->params['url']['order_date_end']));
		}else{
			$conditions['Order.created <=']=date('Y-m-31 23:59:59');
		}
		$this->set('order_date_end',date('Y-m-d',strtotime($conditions['Order.created <='])));
		$date_type="month";
		if (isset($this->params['url']['date_type']) && $this->params['url']['date_type'] != '') {
			$date_type=$this->params['url']['date_type'];
		}
		$this->set('date_type',$date_type);
		$conditions['OrderProduct.parent_product_id']=0;
		$order_fields=array('Order.id','Order.order_code','Order.order_manager','Order.created','Order.status','Order.payment_status','Order.shipping_status','OrderProduct.product_code','OrderProduct.product_name','OrderProduct.product_id','OrderProduct.product_quntity','Order.user_id');
		$order_info=$this->OrderProduct->find('all',array('conditions'=>$conditions,'fields'=>$order_fields,'order'=>'Order.created,Order.order_manager,Order.id,OrderProduct.id'));
		$this->set('order_info',$order_info);
		
		$report_list=array();
		if(!empty($order_info)){
			$user_ids=array();
			foreach($order_info as $v){
				$user_ids[]=$v['Order']['user_id'];
			}
			$user_list=array();
			$user_info=$this->User->find('all',array('conditions'=>array('User.id'=>$user_ids),'fields'=>'User.id,User.name,User.first_name,User.email'));
			foreach($user_info as $v){
				$user_list[$v['User']['id']]=$v['User'];
			}
			$this->set('user_list',$user_list);
			
			$order_date="date_format(Order.created,'%y/%m') as order_date";
			if($date_type=="year"){
				$order_date="date_format(Order.created,'%y') as order_date";
			}else if($date_type=="day"){
				$order_date="date_format(Order.created,'%y/%m/%d') as order_date";
			}else if($date_type=="week"){
				$order_date="date_format(Order.created,'%v') as order_date";
			}
			$fields=array($order_date,'Order.order_manager','SUM(Order.total) as order_total','SUM(OrderProduct.product_quntity) as product_total','count(Order.user_id) as user_total');
			$report_info = $this->OrderProduct->find('all', array('conditions' => $conditions, 'fields' => $fields,'group'=>'order_manager,order_date', 'order' => 'order_date,order_manager'));
			foreach($report_info as $v){
				$operator_name=isset($operator_list[$v['Order']['order_manager']])?$operator_list[$v['Order']['order_manager']]:$v['Order']['order_manager'];
				$report_list[$operator_name][$v[0]['order_date']]=$v[0];
			}
		}
		$this->set('report_list',$report_list);
	}
	
	function order_picker(){
		$this->operation_return_url(true);//设置操作返回页面地址
        	//$this->menu_path = array('root' => '/report/','sub' => '/order_factory_reports/');
        	$this->navigations[] = array('name' => $this->ld['report'],'url' => '');
        	$this->navigations[] = array('name' => '改衣师业绩','url' => '/order_reports/order_picker');
		$this->pageTitle = '改衣师业绩 - '.$this->configs['shop_name'];
        	$this->set('title_for_layout', $this->pageTitle);
        	
		$operator_list=$this->Operator->find('list',array('fields'=>'id,name','conditions'=>array('Operator.name <>'=>'')));
		$this->set('operator_list',$operator_list);
		
		$conditions=array();
		$conditions['Order.status']='1';
		$conditions['OrderProduct.delivery_status']=array('3','4','5');
		if (isset($this->params['url']['picker']) && $this->params['url']['picker'] != '') {
			$conditions['OrderProduct.picker']=$this->params['url']['picker'];
			$this->set('picker',$this->params['url']['picker']);
		}else{
			$conditions['OrderProduct.picker >']=0;
		}
		if (isset($this->params['url']['order_date_start']) && $this->params['url']['order_date_start'] != '') {
			$conditions['Order.created >=']=date('Y-m-d 00:00:00',strtotime($this->params['url']['order_date_start']));
		}else{
			$conditions['Order.created >=']=date('Y-m-01 00:00:00');
		}
		$this->set('order_date_start',date('Y-m-d',strtotime($conditions['Order.created >='])));
		if (isset($this->params['url']['order_date_end']) && $this->params['url']['order_date_end'] != '') {
			$conditions['Order.created <=']=date('Y-m-d 23:59:59',strtotime($this->params['url']['order_date_end']));
		}else{
			$conditions['Order.created <=']=date('Y-m-31 23:59:59');
		}
		$this->set('order_date_end',date('Y-m-d',strtotime($conditions['Order.created <='])));
		$date_type="month";
		if (isset($this->params['url']['date_type']) && $this->params['url']['date_type'] != '') {
			$date_type=$this->params['url']['date_type'];
		}
		$this->set('date_type',$date_type);
		
		$fields=array('Order.order_code','Order.created','OrderProduct.id','OrderProduct.product_code','OrderProduct.product_name','OrderProduct.product_quntity','OrderProduct.picker');
		$order_product_info=$this->OrderProduct->find('all',array('conditions'=>$conditions,'fields'=>$fields,'order'=>'OrderProduct.picker,Order.created','OrderProduct.id'));
		$this->set('order_product_info',$order_product_info);
		
		$order_product_pick_list=array();
		if(!empty($order_product_info)){
			$order_product_total=array();
			$order_product_action_list=array();
			
			$order_product_ids=array();
			$order_product_pick=array();
			foreach($order_product_info as $v){
				$order_product_ids[]=$v['OrderProduct']['id'];
				$order_product_pick[$v['OrderProduct']['id']]=$v['OrderProduct']['picker'];
				if(!isset($order_product_total[$v['OrderProduct']['picker']])){
					$order_product_total[$v['OrderProduct']['picker']]=1;
				}else{
					$order_product_total[$v['OrderProduct']['picker']]++;
				}
			}
			$action_conditions=array();
			$action_conditions['OrderProductAction.order_product_id']=$order_product_ids;
			$action_conditions['OrderProductAction.status']='2';
			$order_product_action=$this->OrderProductAction->find('all',array('conditions'=>$action_conditions,'fields'=>"OrderProductAction.order_product_id,count(*) as picker_count",'group'=>'OrderProductAction.order_product_id having count(*)>1','order'=>'OrderProductAction.order_product_id'));
			foreach($order_product_action as $v){
				$product_pick=isset($order_product_pick[$v['OrderProductAction']['order_product_id']])?$order_product_pick[$v['OrderProductAction']['order_product_id']]:0;
				if(isset($order_product_action_list[$product_pick])){
					$order_product_action_list[$product_pick]++;
				}else{
					$order_product_action_list[$product_pick]=1;
				}
			}
			foreach($order_product_total as $k=>$v){
				$picker=isset($operator_list[$k])?$operator_list[$k]:$k;
				$order_product_pick_list[$picker]['total']=$v;
				$change_number=isset($order_product_action_list[$k])?$order_product_action_list[$k]:0;
				$order_product_pick_list[$picker]['pass_rate']=number_format(($v-$change_number)/$v*100,2,'.','');
			}
		}
		$this->set('order_product_pick_list',$order_product_pick_list);
		
		$order_date="date_format(Order.created,'%y/%m') as order_date";
		if($date_type=="year"){
			$order_date="date_format(Order.created,'%y') as order_date";
		}else if($date_type=="day"){
			$order_date="date_format(Order.created,'%y/%m/%d') as order_date";
		}else if($date_type=="week"){
			$order_date="date_format(Order.created,'%v') as order_date";
		}
		$fields=array($order_date,'OrderProduct.picker','SUM(OrderProduct.product_quntity) as quntity_total');
		$report_info = $this->OrderProduct->find('all', array('conditions' => $conditions, 'fields' => $fields,'group'=>'picker,order_date', 'order' => 'order_date,picker'));
		
		$report_list=array();
		if(!empty($report_info)){
			foreach($report_info as $v){
				$picker=isset($operator_list[$v['OrderProduct']['picker']])?$operator_list[$v['OrderProduct']['picker']]:$v['OrderProduct']['picker'];
				$report_list[$picker][$v[0]['order_date']]=$v[0]['quntity_total'];
			}
		}
		$this->set('report_list',$report_list);
	}
	
	function order_user(){
		$this->operation_return_url(true);//设置操作返回页面地址
        	//$this->menu_path = array('root' => '/report/','sub' => '/order_factory_reports/');
        	$this->navigations[] = array('name' => $this->ld['report'],'url' => '');
        	$this->navigations[] = array('name' => '客户订单','url' => '/order_reports/order_user');
        	$this->pageTitle = '客户订单 - '.$this->configs['shop_name'];
        	$this->set('title_for_layout', $this->pageTitle);
        	
        	$this->Order->hasMany = array();//去掉关联
        	
		$conditions=array();
		$conditions['Order.status']='1';
		if (isset($this->params['url']['user_keyword']) && $this->params['url']['user_keyword'] != '') {
			$user_cond=array();
			$user_cond['User.status']="1";
			$user_cond['or']['User.name like']="%".$this->params['url']['user_keyword']."%";
			$user_cond['or']['User.first_name like']="%".$this->params['url']['user_keyword']."%";
			$user_cond['or']['User.email like']="%".$this->params['url']['user_keyword']."%";
			$user_cond['or']['User.mobile like']="%".$this->params['url']['user_keyword']."%";
			$order_user_ids=$this->User->find('list',array('fields'=>'User.id','conditions'=>$user_cond));
			$this->set('user_keyword',$this->params['url']['user_keyword']);
		}
		if(!empty($order_user_ids)){
			$conditions['Order.user_id']=$order_user_ids;
		}else{
			$conditions['Order.user_id >']=0;
		}
		if (isset($this->params['url']['order_date_start']) && $this->params['url']['order_date_start'] != '') {
			$conditions['Order.created >=']=date('Y-m-d 00:00:00',strtotime($this->params['url']['order_date_start']));
		}else{
			$conditions['Order.created >=']=date('Y-m-01 00:00:00');
		}
		$this->set('order_date_start',date('Y-m-d',strtotime($conditions['Order.created >='])));
		if (isset($this->params['url']['order_date_end']) && $this->params['url']['order_date_end'] != '') {
			$conditions['Order.created <=']=date('Y-m-d 23:59:59',strtotime($this->params['url']['order_date_end']));
		}else{
			$conditions['Order.created <=']=date('Y-m-31 23:59:59');
		}
		$this->set('order_date_end',date('Y-m-d',strtotime($conditions['Order.created <='])));
		$date_type="month";
		if (isset($this->params['url']['date_type']) && $this->params['url']['date_type'] != '') {
			$date_type=$this->params['url']['date_type'];
		}
		$this->set('date_type',$date_type);
		
		$order_list=$this->OrderProduct->find('all',array('conditions'=>$conditions,'fields'=>"Order.order_code,Order.user_id,Order.created,OrderProduct.product_code,OrderProduct.product_quntity,OrderProduct.product_name",'order'=>'Order.created,Order.id'));
		$this->set('order_list',$order_list);
		
		$order_date="date_format(Order.created,'%y/%m') as order_date";
		if($date_type=="year"){
			$order_date="date_format(Order.created,'%y') as order_date";
		}else if($date_type=="day"){
			$order_date="date_format(Order.created,'%y/%m/%d') as order_date";
		}else if($date_type=="week"){
			$order_date="date_format(Order.created,'%v') as order_date";
		}
		$fields1=array('Order.user_id','SUM(Order.total) as order_total','count(Order.id) as order_count');
		$report_info1 = $this->OrderProduct->find('all', array('conditions' => $conditions, 'fields' => $fields1,'group'=>'user_id', 'order' => 'user_id'));
		
		$user_ids=array();
		$report_list1=array();
		if(!empty($report_info1)){
			foreach($report_info1 as $v){
				$user_ids[]=$v['Order']['user_id'];
				$report_list1[$v['Order']['user_id']]['order_total']=$v[0]['order_total'];
				$report_list1[$v['Order']['user_id']]['order_count']=$v[0]['order_count'];
			}
		}
		$this->set('report_list1',$report_list1);
		
		if(!empty($user_ids)){
			$user_ids=array_unique($user_ids);
			$user_infos=$this->User->find('all',array('fields'=>'User.id,User.name,User.first_name','conditions'=>array('User.status'=>'1','User.id'=>$user_ids)));
			$user_list=array();
			foreach($user_infos as $v){
				$user_list[$v['User']['id']]=trim($v['User']['name'])==''?$v['User']['first_name']:$v['User']['name'];
			}
			$this->set('user_list',$user_list);
		}
	}
	
	function order_product_attribute(){
		$this->operation_return_url(true);//设置操作返回页面地址
        	//$this->menu_path = array('root' => '/report/','sub' => '/order_factory_reports/');
        	$this->navigations[] = array('name' => $this->ld['report'],'url' => '');
        	$this->navigations[] = array('name' => '订单商品属性','url' => '/order_reports/order_product_attribute');
        	$this->pageTitle = '订单商品属性 - '.$this->configs['shop_name'];
        	$this->set('title_for_layout', $this->pageTitle);
        	
        	$this->loadModel('Attribute');
        	$this->loadModel('OrderProductValue');
        	$this->Attribute->set_locale($this->backend_locale);
        	$attribute_info=$this->Attribute->find('all',array('fields'=>'Attribute.id,Attribute.type,AttributeI18n.name','conditions'=>array('Attribute.status'=>'1')));
        	$attribute_ids=array();
        	$attribute_list=array();
        	$attribute_types=array();
        	$attribute_option_list=array();
        	foreach($attribute_info as $v){
        		$attribute_ids[]=$v['Attribute']['id'];
        		$attribute_list[$v['Attribute']['id']]=$v['AttributeI18n']['name'];
        		$attribute_types[$v['Attribute']['id']]=$v['Attribute']['type'];
        		if(isset($v['AttributeOption'])&&sizeof($v['AttributeOption'])>0){
        			foreach($v['AttributeOption'] as $vv){
        				$attribute_option_list[$vv['attribute_id']][$vv['option_value']]=$vv['option_name'];
        			}
        		}
        	}
        	$this->set('attribute_list',$attribute_list);
        	$this->set('attribute_option_list',$attribute_option_list);
        	
        	$conditions=array();
        	$conditions['OrderProductValue.attribute_value <>']='';
        	if (isset($this->params['url']['attribute_id']) && intval($this->params['url']['attribute_id'])>0) {
        		$attribute_id=intval($this->params['url']['attribute_id']);
        		$conditions['OrderProductValue.attribute_id']=$attribute_id;
        		$this->set('attribute_id',$attribute_id);
        	}else{
        		$conditions['OrderProductValue.attribute_id']=$attribute_ids;
        	}
        	if (isset($this->params['url']['date_start']) && trim($this->params['url']['date_start']) != '') {
        		$date_start=trim($this->params['url']['date_start']);
        		$conditions['OrderProductValue.created >=']=date('Y-m-d 00:00:00',strtotime($date_start));
        		$this->set('date_start',$date_start);
        	}
        	if (isset($this->params['url']['date_end']) && trim($this->params['url']['date_end']) != '') {
        		$date_end=trim($this->params['url']['date_end']);
        		$conditions['OrderProductValue.created <=']=date('Y-m-d 23:59:59',strtotime($date_end));
        		$this->set('date_end',$date_end);
        	}
        	$order_product_attributeInfo=$this->OrderProductValue->find('all',array('fields'=>'attribute_id,attribute_value,order_product_id','conditions'=>$conditions,'group'=>'attribute_id,attribute_value,order_product_id','order'=>'attribute_id,attribute_value,order_product_id'));
        	$order_product_attribute_data=array();
        	if(!empty($order_product_attributeInfo)){
	        	foreach($order_product_attributeInfo as $v){
	        		$attributeId=$v['OrderProductValue']['attribute_id'];
	        		$attribute_type=$attribute_types[$v['OrderProductValue']['attribute_id']];
	        		$attribute_value=$v['OrderProductValue']['attribute_value'];
	        		if($attribute_type=='multiple_customize'){
					if(strpos($attribute_value,':')){
						preg_match('/([^:]+?):/i',$attribute_value,$attribute_value_list);
						if(isset($attribute_value_list[1]))$attribute_value=$attribute_value_list[1];
					}
	        		}
	        		$attribute_value=trim($attribute_value)==''?'NaN':$attribute_value;
	        		$order_product_attribute_data[$attributeId][$attribute_value][]=$v['OrderProductValue']['order_product_id'];
	        	}
	        	foreach($order_product_attribute_data as $k=>$v){
	        		if(is_array($v)&&sizeof($v)>0){
	        			foreach($v as $kk=>$vv){
	        				$order_product_attribute_data[$k][$kk]=is_array($vv)?sizeof($vv):0;
	        			}
	        		}
	        	}
    		}
        	$this->set('order_product_attribute_data',$order_product_attribute_data);
	}
}