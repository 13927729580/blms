<?php

/*****************************************************************************
 * Seevia 产品销售报表管理控制器
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
 *这是一个名为 ProductSaleCategoryStatementsController 的控制器.
 *
 *@var
 *@var
 */
class Reportscontroller extends AppController
{
    public $name = 'Reports';
    public $helpers = array('Pagination','Ckeditor');
    public $components = array('Pagination','RequestHandler','Email','Phpexcel','Orderfrom');
    public $uses = array('InformationResource');

    public function enterprise_sales_report()
    {
	        $this->set('title_for_layout','企业销售报表'.'-'.$this->configs['shop_name']);
	        $this->navigations[] = array('name' => $this->ld['report'],'url' => '');
	        $this->navigations[] = array('name' => $this->ld['enterprise_sales_report'],'url' => '/reports/product_sale_category_statements/');
    }
    
    public function order_products($page=1){
    		$this->set('title_for_layout','订单商品统计报表 -'.$this->configs['shop_name']);
		$this->navigations[] = array('name' => $this->ld['report'],'url' => '');
		$this->navigations[] = array('name' => '订单商品统计报表','url' => '/reports/order_products');
		
		$this->loadModel('OrderProduct');
		
		$conditions=array();
		$conditions['OrderProduct.status']='1';
		$conditions['OrderProduct.del_status']='1';
		$conditions['OrderProduct.product_id >']=0;
		$conditions['OrderProduct.item_type <>']='';
		if (isset($this->params['url']['keyword']) && $this->params['url']['keyword'] != '') {
			$conditions['or']['OrderProduct.product_code like'] = '%' . $this->params['url']['keyword'] . '%';
			$conditions['or']['OrderProduct.product_name like'] = '%' . $this->params['url']['keyword'] . '%';
			$this->set('keyword', $this->params['url']['keyword']);
		}
		if (isset($this->params['url']['order_date_start']) && $this->params['url']['order_date_start'] != '') {
			$conditions['Order.created >='] = date('Y-m-d 00:00:00',strtotime($this->params['url']['order_date_start']));
			$this->set('order_date_start', $this->params['url']['order_date_start']);
		}
		if (isset($this->params['url']['order_date_end']) && $this->params['url']['order_date_end'] != '') {
			$conditions['Order.created <='] = date('Y-m-d 23:59:59',strtotime($this->params['url']['order_date_end']));
			$this->set('order_date_end', $this->params['url']['order_date_end']);
		}
		$total = sizeof($this->OrderProduct->find('all',array('fields'=>'OrderProduct.item_type,OrderProduct.product_id','conditions'=>$conditions,'group'=>'OrderProduct.item_type,OrderProduct.product_id')));
		if (isset($_GET['page']) && $_GET['page'] != '') {
			$page = $_GET['page'];
		}
		$rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'reports','action' => 'order_products','page' => $page,'limit' => $rownum);
		$options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'OrderProduct');
		$this->Pagination->init($conditions, $parameters, $options);
		$order_product_lists=$this->OrderProduct->find('all',array('fields'=>'OrderProduct.item_type,OrderProduct.product_id,sum(OrderProduct.product_quntity) as buy_total,max(Order.created) as last_buy,sum(OrderProduct.product_quntity*OrderProduct.product_price) as sub_total','conditions'=>$conditions,'group'=>'OrderProduct.item_type,OrderProduct.product_id','order'=>'Order.created desc,Order.id','page' => $page, 'limit' => $rownum));
		if(!empty($order_product_lists)){
			$item_type_infos=array();
			$product_ids=array();$activity_ids=array();$course_ids=array();$evaluation_ids=array();
			foreach($order_product_lists as $v){
				$item_type=trim($v['OrderProduct']['item_type']);
				$order_product_id=intval($v['OrderProduct']['product_id']);
				if($item_type==''){
					$product_ids[]=$order_product_id;
				}else if($item_type=='activity'){
					$activity_ids[]=$order_product_id;
				}else if($item_type=='course'){
					$course_ids[]=$order_product_id;
				}else if($item_type=='evaluation'){
					$evaluation_ids[]=$order_product_id;
				}
			}
			if(!empty($product_ids)){
				$this->loadModel('ProductI18n');
				$product_list=$this->ProductI18n->find('list',array('fields'=>'product_id,name','conditions'=>array('product_id'=>$product_ids,'locale'=>$this->backend_locale)));
				$item_type_infos['']=$product_list;
			}
			if(!empty($activity_ids)){
				$this->loadModel('Activity');
				$activity_list=$this->Activity->find('list',array('fields'=>'id,name','conditions'=>array('id'=>$activity_ids)));
				$item_type_infos['activity']=$activity_list;
			}
			if(!empty($course_ids)){
				$this->loadModel('Course');
				$course_list=$this->Course->find('list',array('fields'=>'id,name','conditions'=>array('id'=>$course_ids)));
				$item_type_infos['course']=$course_list;
			}
			if(!empty($evaluation_ids)){
				$this->loadModel('Evaluation');
				$evaluation_list=$this->Evaluation->find('list',array('fields'=>'id,name','conditions'=>array('id'=>$evaluation_ids)));
				$item_type_infos['evaluation']=$evaluation_list;
			}
			$this->set('item_type_infos',$item_type_infos);
		}
		$this->set('order_product_lists',$order_product_lists);
    }
    
    public function product_like_favourite(){
    		$this->set('title_for_layout','商品统计报表 -'.$this->configs['shop_name']);
		$this->navigations[] = array('name' => $this->ld['report'],'url' => '');
		$this->navigations[] = array('name' => '商品统计报表','url' => '/reports/product_like_favourite/');
		
		$this->loadModel('Product');
		$this->loadModel('ProductI18n');
		$this->loadModel('UserLike');
		$this->loadModel('UserFavorite');
		$this->Product->set_locale($this->backend_locale);
		
		$view_stat_info=$this->Product->find('all',array("fields"=>"Product.id,Product.view_stat","conditions"=>array("Product.status"=>'1',"Product.forsale"=>'1',"Product.alone"=>"1"),"order"=>"Product.view_stat desc",'limit'=>10,"recursive"=>'-1'));
		$user_like_info=$this->UserLike->find('all',array('conditions'=>array('UserLike.type'=>'P','UserLike.action'=>'like','UserLike.type_id <>'=>0),'fields'=>"UserLike.type_id,count(id) as like_count","group"=>'UserLike.type_id','limit'=>10,'order'=>'like_count desc'));
		$user_favourite_info=$this->UserFavorite->find('all',array('conditions'=>array('UserFavorite.type'=>'p','UserFavorite.status'=>'1','UserFavorite.type_id <>'=>0),'fields'=>"UserFavorite.type_id,count(id) as favourite_count","group"=>'UserFavorite.type_id','limit'=>10,'order'=>'favourite_count desc'));
		
		$product_ids=array();
		$view_stat_data=array();
		$user_like_data=array();
		$user_favourite_data=array();
		foreach($user_like_info as $v){
			$user_like_data[]=array('product_id'=>$v['UserLike']['type_id'],'count'=>$v[0]['like_count']);
			$product_ids[$v['UserLike']['type_id']]=$v['UserLike']['type_id'];
		}
		foreach($user_favourite_info as $v){
			$user_favourite_data[]=array('product_id'=>$v['UserFavorite']['type_id'],'count'=>$v[0]['favourite_count']);
			$product_ids[$v['UserFavorite']['type_id']]=$v['UserFavorite']['type_id'];
		}
		foreach($view_stat_info as $v){
			$view_stat_data[]=array('product_id'=>$v['Product']['id'],'count'=>$v['Product']['view_stat']);
			$product_ids[$v['Product']['id']]=$v['Product']['id'];
		}
		$this->set('user_like_data',$user_like_data);
		$this->set('user_favourite_data',$user_favourite_data);
		$this->set('view_stat_data',$view_stat_data);
		
		$product_data=array();
		if(!empty($product_ids)){
		$product_data=$this->ProductI18n->find('list',array('fields'=>array("ProductI18n.product_id","ProductI18n.name"),'conditions'=>array('ProductI18n.product_id'=>$product_ids,'ProductI18n.locale'=>$this->backend_locale)));
		}
		$this->set('product_data',$product_data);
    }
    
    function export_product_like(){
		Configure::write('debug', 1);
		$this->layout = null;
		$this->loadModel('Product');
		$this->loadModel('UserLike');
		$this->Product->set_locale($this->backend_locale);
		
		$excel_data=array();
		$excel_tittle=array(
			$this->ld['product_code'],
			$this->ld['name'],
			'喜欢数量'
		);
		$excel_data[]=$excel_tittle;
		$product_info=$this->Product->find('all',array("fields"=>"Product.id,Product.code,ProductI18n.name","conditions"=>array("Product.status"=>'1',"Product.forsale"=>'1',"Product.alone"=>"1"),"order"=>"Product.id"));
		if(!empty($product_info)){
			$product_ids=array();
			$product_data=array();
			foreach($product_info as $v){
				$product_ids[]=$v['Product']['id'];
				$product_data[$v['Product']['id']]=array($v['Product']['code'],$v['ProductI18n']['name']);
			}
			$user_like_info=$this->UserLike->find('all',array('conditions'=>array('UserLike.type'=>'P','UserLike.action'=>'like','UserLike.type_id'=>$product_ids),'fields'=>"UserLike.type_id,count(id) as like_count","group"=>'UserLike.type_id','order'=>'like_count desc'));
			if(!empty($user_like_info)){
				foreach($user_like_info as $v){
					$product_data[$v['UserLike']['type_id']][]=$v[0]['like_count'];
				}
			}
			foreach($product_data as $k=>$v){
				if(!isset($v[2])){
					$product_data[$k][2]=0;
				}
			}
			$product_data=$this->multi_array_sort($product_data,'2');
			foreach($product_data as $k=>$v){
				$excel_data[]=$v;
			}
		}
		$this->Phpexcel->output('product_like_report'.date('YmdHis').'.xls', $excel_data);
      	exit;
    }
    
    function export_product_favourite(){
    		Configure::write('debug', 1);
		$this->layout = null;
		
		$this->loadModel('Product');
		$this->loadModel('UserFavorite');
		$this->Product->set_locale($this->backend_locale);
		
		$excel_data=array();
		$excel_tittle=array(
			$this->ld['product_code'],
			$this->ld['name'],
			'收藏数量'
		);
		$excel_data[]=$excel_tittle;
		$product_info=$this->Product->find('all',array("fields"=>"Product.id,Product.code,ProductI18n.name","conditions"=>array("Product.status"=>'1',"Product.forsale"=>'1',"Product.alone"=>"1"),"order"=>"Product.id"));
		if(!empty($product_info)){
			$product_ids=array();
			$product_data=array();
			foreach($product_info as $v){
				$product_ids[]=$v['Product']['id'];
				$product_data[$v['Product']['id']]=array($v['Product']['code'],$v['ProductI18n']['name']);
			}
			$user_favourite_info=$this->UserFavorite->find('all',array('conditions'=>array('UserFavorite.type'=>'p','UserFavorite.status'=>'1','UserFavorite.type_id'=>$product_ids),'fields'=>"UserFavorite.type_id,count(id) as favourite_count","group"=>'UserFavorite.type_id','order'=>'favourite_count desc'));
			if(!empty($user_favourite_info)){
				foreach($user_favourite_info as $v){
					$product_data[$v['UserFavorite']['type_id']][]=$v[0]['favourite_count'];
				}
			}
			foreach($product_data as $k=>$v){
				if(!isset($v[2])){
					$product_data[$k][2]=0;
				}
			}
			$product_data=$this->multi_array_sort($product_data,'2');
			foreach($product_data as $k=>$v){
				$excel_data[]=$v;
			}
		}
		$this->Phpexcel->output('product_favourite_report'.date('YmdHis').'.xls', $excel_data);
		die();
    }
    
    function export_product_view(){
    		Configure::write('debug', 1);
		$this->layout = null;
		
		$excel_data=array();
		$excel_tittle=array(
			$this->ld['product_code'],
			$this->ld['name'],
			'浏览量'
		);
		$excel_data[]=$excel_tittle;
		
		$this->loadModel('Product');
		$this->loadModel('ProductI18n');
		$this->Product->set_locale($this->backend_locale);
		$product_info=$this->Product->find('all',array("fields"=>"Product.code,Product.view_stat,ProductI18n.name","conditions"=>array("Product.status"=>'1',"Product.forsale"=>'1',"Product.alone"=>"1"),"order"=>"Product.view_stat desc"));
		if(!empty($product_info)){
			foreach($product_info as $k=>$v){
				$excel_data[]=array(
					$v['Product']['code'],
					$v['ProductI18n']['name'],
					$v['Product']['view_stat'],
				);
			}
		}
		$this->Phpexcel->output('product_view_report'.date('YmdHis').'.xls', $excel_data);
		die();
    }
    
    function user_project(){
        	$this->operator_privilege('user_project_report_view');
    		$this->set('title_for_layout','业绩汇总 -'.$this->configs['shop_name']);
		$this->navigations[] = array('name' => $this->ld['report'],'url' => '');
		$this->navigations[] = array('name' => '业绩汇总','url' => '/reports/user_project');
		
		$this->loadModel('UserProject');
		$this->loadModel('UserProjectFee');
		$this->loadModel('AccountInformation');
		$this->loadModel('User');
		
		$this->loadModel('Department');
		$this->loadModel('OperatorDepartment');
		$this->loadModel('Operator');
		
		$department_list=array();$department_managers=array();
		$this->Department->set_locale($this->backend_locale);
		$DepartmentInfos=$this->Department->find('all',array('fields'=>'Department.id,Department.manager,DepartmentI18n.name','conditions'=>array('Department.status'=>'1')));
		if(!empty($DepartmentInfos)){
			foreach($DepartmentInfos as $v){
				if($this->admin['actions']=='all'||$this->operator_privilege('account_check',false)){
					$department_list[$v['Department']['id']]=$v['DepartmentI18n']['name'];
				}else{
					$department_manager=explode(',',trim($v['Department']['manager'],','));
					if(in_array($this->admin['id'],$department_manager)){
						$department_list[$v['Department']['id']]=$v['DepartmentI18n']['name'];
						$department_managers[]=$v['Department']['id'];
					}
				}
			}
		}
		$this->set('DepartmentInfos',$department_list);
		$this->set('department_managers',$department_managers);
		
		$operator_list=array();
		$info_resource=$this->InformationResource->information_formated(array('user_project','user_project_fee'),$this->backend_locale,false);
		if(isset($info_resource['user_project_fee']))ksort($info_resource['user_project_fee']);
		if(isset($info_resource['user_project'])&&!empty($info_resource['user_project'])){
			$user_project_list=array();
			$sub_user_project=array_keys($info_resource['user_project']);
			$sub_info_resource = $this->InformationResource->information_formated($sub_user_project,$this->backend_locale,false);
			foreach($info_resource['user_project'] as $k=>$v){
				if(isset($sub_info_resource[$k])&&!empty($sub_info_resource[$k])){
					foreach($sub_info_resource[$k] as $kk=>$vv)$user_project_list[$kk]=$vv;
				}else{
					$user_project_list[$k]=$v;
				}
			}
			$info_resource['user_project']=$user_project_list;
		}
		$this->set('info_resource',$info_resource);
		
		$conditions=array();
		if($this->admin['actions']=='all'||$this->operator_privilege('account_check',false)){
			
		}else if(!empty($department_managers)){
			$this->params['url']['department_id']=$department_managers;
		}else{
			$this->params['url']['department_id']=-1;
			$conditions['AccountInformation.payee']=$this->admin['name'];
		}
		$conditions['AccountInformation.status']='1';
		$conditions['AccountInformation.account_category like']='user_project_%';
		if(isset($info_resource['user_project_fee']))$conditions['AccountInformation.transaction_category']=array_keys($info_resource['user_project_fee']);
		if(isset($this->params['url']['department_id']) && $this->params['url']['department_id']!='0'){
			$DepartmentOperatorIds=$this->OperatorDepartment->find('list',array('fields'=>'OperatorDepartment.operator_id','conditions'=>array('OperatorDepartment.department_id'=>isset($this->params['url']['department_id']) && $this->params['url']['department_id']!='0'?$this->params['url']['department_id']:0)));
			if(!empty($DepartmentOperatorIds)){
				$operator_list=$this->Operator->find('list',array('fields'=>'id,name','conditions'=>array('Operator.status'=>'1','Operator.id'=>$DepartmentOperatorIds)));
				if(!(isset($this->params['url']['manager_id']) && $this->params['url']['manager_id']!='0')){
					//$conditions['or']['AccountInformation.operator']=$DepartmentOperatorIds;
					if(!empty($operator_list)){
						$conditions['AccountInformation.payee']=$operator_list;
					}
				}
			}
			$this->set('department_id',$this->params['url']['department_id']);
		}else{
			$operator_list=$this->Operator->find('list',array('fields'=>'id,name','conditions'=>array('Operator.status'=>'1')));
		}
		$this->set('operator_list',$operator_list);
		if(isset($this->params['url']['manager_id']) && $this->params['url']['manager_id']!='0'){
			//$conditions['or']['AccountInformation.operator']=$this->params['url']['manager_id'];
			if(isset($operator_list[$this->params['url']['manager_id']])){
				$conditions['AccountInformation.payee']=$operator_list[$this->params['url']['manager_id']];
			}
			$this->set('manager_id',$this->params['url']['manager_id']);
		}
		if(isset($this->params['url']['payment_time_start']) && $this->params['url']['payment_time_start']!=''){
			$conditions['AccountInformation.payment_time >=']=date('Y-m-d 00:00:00',strtotime($this->params['url']['payment_time_start']));
			$this->set('payment_time_start',$this->params['url']['payment_time_start']);
		}
		if(isset($this->params['url']['payment_time_end']) && $this->params['url']['payment_time_end']!=''){
			$conditions['AccountInformation.payment_time <=']=date('Y-m-d 23:59:59',strtotime($this->params['url']['payment_time_end']));
			$this->set('payment_time_end',$this->params['url']['payment_time_end']);
		}
		if(isset($this->params['url']['user_project_fee']) && !empty($this->params['url']['user_project_fee'])){
			$conditions['AccountInformation.transaction_category']=$this->params['url']['user_project_fee'];
			$this->set('user_project_fee',$this->params['url']['user_project_fee']);
		}
		if(isset($this->params['url']['account_type']) && trim($this->params['url']['account_type'])!=''){
			$conditions['AccountInformation.account_type']=$this->params['url']['account_type'];
			$this->set('account_type',$this->params['url']['account_type']);
		}
		$account_infos=$this->AccountInformation->find('all',array('conditions'=>$conditions,'fields'=>"AccountInformation.payee,AccountInformation.account_category_code,AccountInformation.transaction_category,sum(AccountInformation.payment_amount) as amount_total","group"=>"AccountInformation.payee,AccountInformation.account_category_code,AccountInformation.transaction_category",'order'=>"AccountInformation.payee,AccountInformation.account_category_code,AccountInformation.transaction_category"));
		if(!empty($account_infos)){
			$operator_project_datas=array();
			$operator_names=array();$project_code_fee=array();
			$project_fee_types=array();
			foreach($account_infos as $v)$operator_names[]=$v['AccountInformation']['payee'];
			$operator_names=array_unique($operator_names);
			foreach($account_infos as $v){
				$operator_key=array_search($v['AccountInformation']['payee'],$operator_names);
				$operator_project_datas[$operator_key][$v['AccountInformation']['account_category_code']][$v['AccountInformation']['transaction_category']]=$v[0]['amount_total'];
				$project_code_fee[$v['AccountInformation']['account_category_code']][$v['AccountInformation']['transaction_category']]=$v['AccountInformation']['transaction_category'];
				$project_fee_types[]=$v['AccountInformation']['transaction_category'];
			}
			$this->set('operator_project_datas',$operator_project_datas);
			$this->set('project_code_fee',$project_code_fee);
			$this->set('project_fee_types',array_unique($project_fee_types));
			
			$this->set('operator_infos',$operator_names);
		}else{
			if(isset($this->params['url']['user_project_fee']) && !empty($this->params['url']['user_project_fee'])){
				$this->set('project_fee_types',$this->params['url']['user_project_fee']);
			}
		}
		if(isset($this->params['url']['export']) && $this->params['url']['export']=='1'&&!empty($account_infos)){
			$export_data=array();
			$export_fields1=array($this->ld['real_name']);
			$export_fields2=array('');
			if(isset($info_resource['user_project'])&&sizeof($info_resource['user_project'])>0){
				foreach($info_resource['user_project'] as $k=>$v){
					if(!isset($project_code_fee[$k]))continue;
					if(isset($info_resource['user_project_fee'])&&sizeof($info_resource['user_project_fee'])>0){
						foreach($info_resource['user_project_fee'] as $kk=>$vv){
							if(isset($this->params['url']['user_project_fee']) && !empty($this->params['url']['user_project_fee'])){
								if(!in_array($kk,$this->params['url']['user_project_fee']))continue;
							}
							if(!isset($project_code_fee[$k][$kk]))continue;
							$export_fields1[]=$v;
							$export_fields2[]=$vv;
						}
					}else{
						$export_fields1[]=$v;
					}
				}
			}
			$export_fields1[]='合计';
			if(isset($info_resource['user_project_fee'])&&sizeof($info_resource['user_project_fee'])>0){
				foreach($info_resource['user_project_fee'] as $kk=>$vv){
					if(!in_array($kk,$project_fee_types))continue;
					$export_fields2[]=$vv;
				}
			}
			$export_data[]=$export_fields1;
			$export_data[]=$export_fields2;
			foreach($operator_project_datas as $k=>$v){
				$user_project_data=array();
				$user_project_data[]=isset($operator_names[$k])?$operator_names[$k]:$k;
				$user_project_fee_group=array();
				if(isset($info_resource['user_project'])&&sizeof($info_resource['user_project'])>0){
					foreach($info_resource['user_project'] as $kk=>$vv){
						if(!isset($project_code_fee[$kk]))continue;
						if(isset($info_resource['user_project_fee'])&&sizeof($info_resource['user_project_fee'])>0){
							foreach($info_resource['user_project_fee'] as $kkk=>$vvv){
								if(isset($this->params['url']['user_project_fee']) && !empty($this->params['url']['user_project_fee'])){
									if(!in_array($kkk,$this->params['url']['user_project_fee']))continue;
								}
								if(!isset($project_code_fee[$kk][$kkk]))continue;
								
								$project_fee_info=isset($v[$kk][$kkk])?$v[$kk][$kkk]:0;
								$user_project_fee_group[$kkk][]=$project_fee_info;
								$user_project_fees[$kk][$kkk][]=$project_fee_info;
								$user_project_data[]=$project_fee_info;
							}
						}
					}
				}
				if(isset($info_resource['user_project_fee'])&&sizeof($info_resource['user_project_fee'])>0){
					foreach($info_resource['user_project_fee'] as $kkk=>$vvv){
						if(isset($this->params['url']['user_project_fee']) && !empty($this->params['url']['user_project_fee'])){
							if(!in_array($kkk,$this->params['url']['user_project_fee']))continue;
						}
						if(!in_array($kkk,$project_fee_types))continue;
						$user_project_data[]=isset($user_project_fee_group[$kkk])?array_sum($user_project_fee_group[$kkk]):0;
					}
				}
				$export_data[]=$user_project_data;
			}
			$export_fields3=array('小计');
			if(isset($info_resource['user_project'])&&sizeof($info_resource['user_project'])>0){
				$user_project_fees_total=array();
				foreach($info_resource['user_project'] as $kk=>$vv){
					if(!isset($project_code_fee[$kk]))continue;
					if(isset($info_resource['user_project_fee'])&&sizeof($info_resource['user_project_fee'])>0){
						foreach($info_resource['user_project_fee'] as $kkk=>$vvv){
							if(isset($this->params['url']['user_project_fee']) && !empty($this->params['url']['user_project_fee'])){
								if(!in_array($kkk,$this->params['url']['user_project_fee']))continue;
							}
							if(!isset($project_code_fee[$kk][$kkk]))continue;
							$user_project_fees_total[$kkk][]=isset($user_project_fees[$kk][$kkk])?array_sum($user_project_fees[$kk][$kkk]):0;
							$export_fields3[]=isset($user_project_fees[$kk][$kkk])?array_sum($user_project_fees[$kk][$kkk]):0;
						}
					}
				}
				if(isset($info_resource['user_project_fee'])&&sizeof($info_resource['user_project_fee'])>0){
					foreach($info_resource['user_project_fee'] as $kkk=>$vvv){
						if(isset($this->params['url']['user_project_fee']) && !empty($this->params['url']['user_project_fee'])){
							if(!in_array($kkk,$this->params['url']['user_project_fee']))continue;
						}
						if(!in_array($kkk,$project_fee_types))continue;
						$export_fields3[]=isset($user_project_fees_total[$kkk])?array_sum($user_project_fees_total[$kkk]):0;
					}
				}
			}
			$export_data[]=$export_fields3;
			$this->Phpexcel->output('业绩汇总'.date('Ymd').'.xls', $export_data);
			die();
		}
    }
    
    function ajax_user_project_manager(){
    		Configure::write('debug', 1);
		$this->layout = 'ajax';
		
		$this->loadModel('Department');
		$this->loadModel('OperatorDepartment');
		$this->loadModel('Operator');
		
		$operator_list=array();
		$manager_id=isset($_POST['manager_id'])?$_POST['manager_id']:array();
		$DepartmentOperatorIds=$this->OperatorDepartment->find('list',array('fields'=>'OperatorDepartment.operator_id','conditions'=>array('OperatorDepartment.department_id'=>$manager_id)));
		if(!empty($DepartmentOperatorIds)){
			$conditions['UserProject.manager']=$DepartmentOperatorIds;
			$operator_list=$this->Operator->find('all',array('fields'=>'id,name','conditions'=>array('Operator.status'=>'1','Operator.id'=>$DepartmentOperatorIds)));
		}
		die(json_encode($operator_list));
    }
    
    function multi_array_sort($multi_array,$sort_key,$sort=SORT_DESC){
		if(is_array($multi_array)){
			foreach ($multi_array as $row_array){
				if(is_array($row_array)){
					$key_array[] = $row_array[$sort_key];
				}else{
					return false;
				}
			}
		}else{
			return false;
		}
		array_multisort($key_array,$sort,$multi_array);
		return $multi_array;
    }
}
