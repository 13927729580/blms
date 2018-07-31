<?php
class VirtualPurchasesController extends AppController
{
	public $name = 'VirtualPurchases';
	public $helpers = array('Html','Pagination');
	public $uses = array('User','Payment','Order','OrderProduct','OrderAction');
	public $components = array('RequestHandler','Pagination','Notify');
	
	public function purchase_order(){
        	Configure::write('debug',1);
        	$this->layout = 'ajax';
        	if(!$this->RequestHandler->isPost())$this->redirect('/');
        	
        	$user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
		$virtual_type=isset($_POST['virtual_type'])?$_POST['virtual_type']:'';
		$virtual_type_id=isset($_POST['virtual_type_id'])?$_POST['virtual_type_id']:0;
		$virtual_data=array();
		if($virtual_type=='course'){
			$this->loadModel('Course');
			$course_data=$this->Course->find('first',array('conditions'=>array('id'=>$virtual_type_id,'status'=>'1')));
			if(!empty($course_data)){
				$virtual_data['code']=$course_data['Course']['code'];
				$virtual_data['name']=$course_data['Course']['name'];
				$virtual_data['description']=strip_tags(htmlspecialchars($course_data['Course']['description']));
				$virtual_data['price']=$course_data['Course']['price'];
				$virtual_data['image']=$course_data['Course']['img'];
				$virtual_data['link']='/courses/view/'.$virtual_type_id;
				
				$this->loadModel('CourseClass');
				$course_class_list=$this->CourseClass->find('list',array('fields'=>'CourseClass.id','conditions'=>array('CourseClass.course_code'=>$course_data['Course']['code'])));
				if(!empty($course_class_list)){
					$pre_course_class_cond=array();
					$pre_course_class_cond['Order.user_id']=$user_id;
					$pre_course_class_cond['Order.status']='1';
					$pre_course_class_cond['Order.payment_status']='2';
					$pre_course_class_cond['OrderProduct.item_type']='course_class';
					$pre_course_class_cond['OrderProduct.product_id']=$course_class_list;
					$pre_course_class_order=$this->OrderProduct->find('first',array('conditions'=>$pre_course_class_cond,'fields'=>"SUM(Order.total) as course_class_total"));
					if(isset($pre_course_class_order[0])){
						$virtual_data['discount']=$pre_course_class_order[0]['course_class_total'];
					}
				}
			}
		}else if($virtual_type=='evaluation'){
			$this->loadModel('Evaluation');
			$evaluation_data=$this->Evaluation->find('first',array('conditions'=>array('id'=>$virtual_type_id,'status'=>'1')));
			if(!empty($evaluation_data)){
				$virtual_data['code']=$evaluation_data['Course']['code'];
				$virtual_data['name']=$evaluation_data['Evaluation']['name'];
				$virtual_data['description']=strip_tags(htmlspecialchars($evaluation_data['Evaluation']['description']));
				$virtual_data['price']=$evaluation_data['Evaluation']['price'];
				$virtual_data['image']=$evaluation_data['Evaluation']['img'];
				$virtual_data['link']='/evaluations/view/'.$virtual_type_id;
			}
		}else if($virtual_type=='organization'){
			$this->loadModel('Organization');
			$organization_data=$this->Organization->find('first',array('conditions'=>array('id'=>$virtual_type_id,'manage_user'=>$user_id,'manage_user <>'=>0,'status'=>'1')));
			if(!empty($organization_data)){
				$virtual_data['code']='organization_'.$organization_data['Organization']['id'];
				$virtual_data['name']="企业认证 - ".$organization_data['Organization']['name'];
				$virtual_data['description']=strip_tags(htmlspecialchars($organization_data['Organization']['description']));
				$virtual_data['price']=isset($this->configs['organization_certification_fee'])?floatval($this->configs['organization_certification_fee']):0;
				$virtual_data['image']=$organization_data['Organization']['logo'];
				$virtual_data['link']='/organizations/view/'.$virtual_type_id;
			}
		}else if($virtual_type=='activity'){
			$this->loadModel('Activity');
			$activity_data=$this->Activity->find('first',array('conditions'=>array('id'=>$virtual_type_id,'status'=>'1')));
			if(!empty($activity_data)){
				$virtual_data['code']='activity_'.$activity_data['Activity']['id'];
				$virtual_data['name']=$activity_data['Activity']['name'];
				$virtual_data['description']=strip_tags(htmlspecialchars($activity_data['Activity']['description']));
				$virtual_data['price']=$activity_data['Activity']['price'];
				$virtual_data['image']=$activity_data['Activity']['image'];
				$virtual_data['link']='/activities/view/'.$virtual_type_id;
			}
		}else if($virtual_type=='course_class'){
			$this->loadModel('CourseClass');
			$course_class_data=$this->CourseClass->find('first',array('conditions'=>array('CourseClass.status'=>'1','CourseClass.id'=>$virtual_type_id)));
			if(!empty($course_class_data)&&!empty($course_class_data['Course'])){
				$virtual_data['code']=$course_class_data['CourseClass']['code'];
				$virtual_data['name']=$course_class_data['CourseClass']['name'];
				$virtual_data['description']=strip_tags(htmlspecialchars($course_class_data['CourseClass']['description']));
				$virtual_data['price']=$course_class_data['CourseClass']['price'];
				$virtual_data['image']=$course_class_data['Course']['img'];
				$virtual_data['link']='/courses/view/'.$course_class_data['Course']['id'];
			}
		}
		if(!empty($virtual_data)){
			$virtual_data['type']=$virtual_type;
			$virtual_data['type_id']=$virtual_type_id;
		}
		$this->set('virtual_data',$virtual_data);
		$user_data=$this->User->find('first',array('conditions'=>array('User.id'=>$user_id,'status'=>'1')));
		if(!empty($virtual_data)&&!empty($user_data)){
			$this->set('user_data',$user_data);
			
			$order_cond=array();
			$order_cond['Order.user_id']=$user_id;
			$order_cond['Order.status']=array(0,1);
			$order_cond['OrderProduct.item_type']=$virtual_type;
			$order_cond['OrderProduct.product_id']=$virtual_type_id;
			$order_cond['or'][]=array('Order.payment_status'=>'2');
			$order_cond['or'][]=array('Order.payment_status'=>'0','OrderProduct.product_price'=>$virtual_data['price']);
			$order_info=$this->OrderProduct->find('first',array('conditions'=>$order_cond));
			if(empty($order_info)){
				$sub_paylist=array();
				$PaymentInfo=$this->Payment->find('first',array('fields'=>'Payment.id,Payment.code,PaymentI18n.name','conditions'=>array('Payment.code'=>'online_payment','Payment.status'=>'1')));
				if(!empty($PaymentInfo)){
					$order_data['payment_id']=$PaymentInfo['Payment']['id'];
					$order_data['payment_name']=$PaymentInfo['PaymentI18n']['name'];
					$sub_paylist = $this->Payment->getOrderChildPayments($PaymentInfo['Payment']['id']);
				}
				$this->set('sub_paylist',$sub_paylist);
				
				$order_need_pay=$virtual_data['price'];
				if(isset($virtual_data['discount']))$order_need_pay=$order_need_pay-$virtual_data['discount'];
				$order_need_pay=$order_need_pay>=0?$order_need_pay:0;
				$order_need_pay=number_format($order_need_pay, 2, '.', '') + 0;
		        	$this->set('order_need_pay',$order_need_pay);
			}else{
				$order_need_pay=$order_info['Order']['payment_status']=='0'?(number_format($order_info['Order']['total'] - $order_info['Order']['point_fee'] - $order_info['Order']['discount'] - $order_info['Order']['coupon_fee'] - $order_info['Order']['user_balance']-$order_info['Order']['money_paid'], 2, '.', '') + 0):0;
		        	$this->set('order_need_pay',$order_need_pay);
		        	$this->set('order_info',$order_info);
			}
		}
	}
	
	function ajax_purchase_order(){
		Configure::write('debug',1);
        	$this->layout = 'ajax';
        	
        	$result=array();
        	$result['code']="0";
        	$result['message']=$this->ld['invalid_operation'];
        	if($this->RequestHandler->isPost()){
        		$user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
        		$user_data=$this->User->find('first',array('conditions'=>array('User.id'=>$user_id,'status'=>'1')));
        		
        		$OrderData=isset($this->data['Order'])?$this->data['Order']:array();
        		$VirtualData=isset($this->data['Virtual'])?$this->data['Virtual']:array();
        		
        		if(empty($user_data)){
        			$result['message']=$this->ld['time_out_relogin'];
        		}else if(!empty($OrderData)&&!empty($VirtualData)){
        			$order_cond=array();
				$order_cond['Order.user_id']=$user_id;
				$order_cond['Order.status']=array(0,1);
				$order_cond['OrderProduct.item_type']=$VirtualData['type'];
				$order_cond['OrderProduct.product_id']=$VirtualData['type_id'];
				$order_cond['or'][]=array('Order.payment_status'=>'2');
				$order_cond['or'][]=array('Order.payment_status'=>'0','OrderProduct.product_price'=>$VirtualData['price']);
        			$order_info=$this->OrderProduct->find('first',array('conditions'=>$order_cond));
        			if(empty($order_info)){
	        			$order_data=array(
						'id'=>0,
						'order_code'=>$this->Order->get_order_code(),
						'user_id'=>$user_id,
						'order_date'=>date('Y-m-d H:i:s'),
						'locale'=>LOCALE,
						'service_type'=>'virtual',
						'type'=>'website',
						'type_id'=>'front'
					);
					$order_data['consignee'] = trim($user_data['User']['first_name'])!=''?$user_data['User']['first_name']:(trim($user_data['User']['name'])==''?$user_data['User']['user_sn']:$user_data['User']['name']);
					$order_data['mobile'] = $user_data['User']['mobile'];
					$order_data['subtotal'] = $VirtualData['price'];
					$order_data['total'] = $VirtualData['price'];
					$order_data['discount'] = isset($OrderData['discount'])?$OrderData['discount']:0;
					$order_data['order_domain'] = $this->server_host;
					$PaymentInfo=$this->Payment->find('first',array('fields'=>'Payment.id,Payment.code,PaymentI18n.name','conditions'=>array('Payment.code'=>'online_payment','Payment.status'=>'1')));
					if(!empty($PaymentInfo)){
						$order_data['payment_id']=$PaymentInfo['Payment']['id'];
						$order_data['payment_name']=$PaymentInfo['PaymentI18n']['name'];
						if(isset($OrderData['sub_pay']))$order_data['sub_pay']=$OrderData['sub_pay'];
					}
					$other_pay_log="";
					if(isset($OrderData['point_use'])&&$OrderData['point_use']>0){
						$order_data['point_use']=$OrderData['point_use'];
						$order_data['point_fee']=round($OrderData['point_use']/(isset($this->configs['point-equal'])?$this->configs['point-equal']:0),2);
						$other_pay_log[]=$this->ld['use_points'].":".$order_data['point_use'];
					}
					if(isset($OrderData['user_balance'])&&$OrderData['user_balance']>0){
						$order_data['user_balance']=$OrderData['user_balance'];
					}
					$this->Order->save($order_data);
					$order_id=$this->Order->id;
					$order_items=array(
						'id'=>0,
						'order_id'=>$order_id,
						'item_type'=>$VirtualData['type'],
						'product_id'=>$VirtualData['type_id'],
						'lease_type'=>'P',
						'product_name'=>$VirtualData['name'],
						'product_code'=>$VirtualData['code'],
						'product_quntity'=>'1',
						'product_price'=>$VirtualData['price'],
						'status'=>'1',
						'del_status'=>'1'
					);
					$this->OrderProduct->save($order_items);
					$order_info=$this->Order->find('first',array('conditions'=>array('id'=>$order_id,'user_id'=>$user_id)));
					if(!empty($order_info)){
						$order_need_pay=(number_format($order_info['Order']['total'] - $order_info['Order']['point_fee'] - $order_info['Order']['discount'] - $order_info['Order']['coupon_fee'] - $order_info['Order']['user_balance']-$order_info['Order']['money_paid'], 2, '.', '') + 0);
						if(!$order_need_pay>0){
							$order_data['id']=$order_id;
							$order_data['status']='1';
							$order_data['payment_status']='2';
							$order_data['payment_time']=date('Y-m-d H:i:s');
							$order_data['Order']['status']='1';
							$order_data['Order']['payment_status']='2';
							$order_data['Order']['payment_time']=date('Y-m-d H:i:s');
							$order_data['Order']['shipping_status']='1';
							$order_data['Order']['shipping_time']=date('Y-m-d H:i:s');
							$order_data['Order']['shipping_id']=0;
							$order_data['Order']['shipping_name']='无需物流';
							$this->Order->save($order_data);
							
							$order_info['Order']['status']='1';
							$order_info['Order']['payment_status']='2';
							$order_info['Order']['shipping_status']='1';
						}
						$this->OrderAction->saveAll(array('OrderAction' => array(
					                'order_id' => $order_id,
					                'from_operator_id' => 0,
					                'user_id' => $user_id,
					                'order_status' => $order_info['Order']['status'],
					                'payment_status' => $order_info['Order']['payment_status'],
					                'shipping_status' => $order_info['Order']['shipping_status'],
					                'action_note' => $this->ld['submit_order']." ".(!empty($other_pay_log)?implode(' ',$other_pay_log):'')
				            	)));
				        	//下单抵扣
				        	if(isset($order_info['Order']['point_use'])&&$order_info['Order']['point_use']>0){
				        		$this->loadModel('UserPointLog');
				        		$order_user_point=isset($user_data['User'])?$user_data['User']['point']:0;
				        		$point_log = array(
								'id' => '',
								'user_id' => $user_id,
								'point'=>$order_user_point,
								'point_change' => "-".$order_info['Order']['point_use'],
								'log_type' => 'O',
								'system_note' => '订单消费:'.$order_info['Order']['order_code'],
								'type_id' => $order_id
							);
			                	 	$this->UserPointLog->save($point_log);
			                	 	$this->UserPointLog->point_notify($point_log);
			                	 	$user_update=array(
		                	 			'id'=>$user_id,
		                	 			'point'=>$order_user_point-$order_info['Order']['point_use']
		                	 		);
		                	 		$this->User->save($user_update);
				        	}
				        	//下单积分赠送
						if(isset($this->configs['use_point'])&&$this->configs['use_point']=='1'){
							$points_awarded_occasion=isset($this->configs['points_awarded_occasion'])?$this->configs['points_awarded_occasion']:'';
							if(in_array($points_awarded_occasion,array('0','2'))&&isset($this->configs['order_points'])&&intval($this->configs['order_points'])>0){
								$order_user_point=isset($user_update['point'])?$user_update['point']:(isset($user_data['User'])?$user_data['User']['point']:0);
								$user_update=array(
			                	 			'id'=>$user_id,
			                	 			'point'=>$order_user_point+intval($this->configs['order_points'])
			                	 		);
			                	 		$this->User->save($user_update);
								$point_log = array('id' => '',
									'user_id' => $user_id,
									'point' => $order_user_point,
									'point_change' => $this->configs['order_points'],
									'log_type' => 'B',
									'system_note' => '下单送积分',
									'type_id' => $order_id
								);
								$this->UserPointLog->save($point_log);
			                	 		$this->UserPointLog->point_notify($point_log);
							}
						}
				        	//余额抵扣
				        	if(isset($order_info['Order']['user_balance'])&&$order_info['Order']['user_balance']>0){
				        		$order_user_balance=isset($user_data['User'])?$user_data['User']['balance']:0;
				        		$this->loadModel('UserBalanceLog');
							$balance_log = array(
								'user_id' => $user_data['User']['id'],
								'amount' => $order_info['Order']['user_balance'],
								'log_type' => 'O',
								'system_note' => '订单消费:'.$order_info['Order']['order_code'],
								'type_id' => $order_id
							);
							$this->UserBalanceLog->save($balance_log);
							$user_update=array(
		                	 			'id'=>$user_id,
		                	 			'balance'=>$order_user_balance-$order_info['Order']['user_balance']
		                	 		);
		                	 		$this->User->save($user_update);
				        	}
					}
				}
				if(isset($order_info)&&!empty($order_info)){
					$order_need_pay=$order_info['Order']['payment_status']=='0'?(number_format($order_info['Order']['total'] - $order_info['Order']['point_fee'] - $order_info['Order']['discount'] - $order_info['Order']['coupon_fee'] - $order_info['Order']['user_balance']-$order_info['Order']['money_paid'], 2, '.', '') + 0):0;
					$result['code']='1';
					$result['message']=$this->ld['submit_order'];
					$result['order_id']=$order_info['Order']['id'];
					$result['order_need_pay']=$order_need_pay;
					if($order_need_pay>0){
						$result['payment_method']=$order_info['Order']['sub_pay'];
					}else{
						if($VirtualData['type']=='activity'){
							$this->loadModel('ActivityUser');
							$this->ActivityUser->updateAll(array('payment_status'=>"'1'"),array('user_id'=>$user_id,'activity_id'=>$VirtualData['type_id']));
						}
					}
				}
        		}
        	}
        	die(json_encode($result));
	}
	
	public function api_pay_callback($order_id=0){
		Configure::write('debug',0);
        	$this->layout = 'ajax';
        	$order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id,'Order.payment_status' =>'2','Order.status' =>'1')));
        	if(!empty($order_info)){
        		$order_item_detail=$this->OrderProduct->find('first',array('conditions'=>array('OrderProduct.order_id'=>$order_id),'order'=>'OrderProduct.id', 'recursive' => '-1'));
        		if(!empty($order_item_detail['OrderProduct'])){
        			$item_type=$order_item_detail['OrderProduct']['item_type'];
        			$item_type_id=$order_item_detail['OrderProduct']['product_id'];
        			if($item_type=='course'){
        				$this->redirect('/courses/view/'.$item_type_id);
        			}else if($item_type=='evaluation'){
        				$this->redirect('/evaluations/view/'.$item_type_id);
        			}else if($item_type=='activity'){
        				$this->loadModel('ActivityUser');
					$this->ActivityUser->updateAll(array('payment_status'=>"'1'"),array('user_id'=>$order_info['Order']['user_id'],'activity_id'=>$item_type_id));
        				$this->redirect('/activities/view/'.$item_type_id);
        			}else if($item_type=='organization'){
        				$this->redirect('/organizations/index');
        			}else if($item_type=='course_class'){
        				$this->loadModel('CourseClass');
					$course_class_data=$this->CourseClass->find('first',array('conditions'=>array('CourseClass.status'=>'1','CourseClass.id'=>$item_type_id)));
					if(!empty($course_class_data)&&!empty($course_class_data['Course'])){
						$this->redirect('/courses/view/'.$course_class_data['Course']['id']);
					}
        			}
        		}
        	}
        	$this->redirect('/');
	}
}
