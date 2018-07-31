<?php

/*****************************************************************************
 * svoms  商品操作日志
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
class ProductAction extends AppModel
{
	    /*
	     * @var $useDbConfig 数据库配置
	     */
	    public $useDbConfig = 'oms';
	    /*
	     * @var $name ProductAction  商品操作日志
	     */
	    public $name = 'ProductAction';
	    
	    public $belongsTo = array(
		        'Operator' => array(
			        'className' => 'Operator',
			        'conditions' => 'Operator.id=ProductAction.operator_id',
			        'fields' => 'Operator.id,Operator.name,Operator.email,Operator.mobile',
			        'dependent' => true
		        )
	    );
	    
	    /**
	     * action_list方法，商品操作计录列表.
	     *
	     * @param int $product_id 商品Id
	     *
	     * @return array $product_action_list 返回订单操作计录列表
	     */
	    public function action_list($product_id)
	    {
	        	$product_action_list = $this->find('all', array('conditions' => array('ProductAction.product_id' => $product_id), 'order' => 'ProductAction.id desc'));
	        	return $product_action_list;
	    }

	    /**
	     * update_actions方法，新增商品操作日志.
	     *
	     * @param int    $product_id        商品Id
	     * @param int    $operator_id     操作员ID
	     * @param int    $product_status    商品状态
	     * @param string $action_note     操作备注
	     *
	     * @return true 返回成功
	     */
	    public function update_actions($order_id, $operator_id,$product_status,$action_note,$controller){
		        $this->saveAll(array('ProductAction' =>array(
			            'product_id' => $product_id,
			            'operator_id' => $operator_id,
			            'status' => $product_status,
			            'remark' => $action_note
		        )));
		        if(isset($controller->configs['product_log_send_mail'])&&$controller->configs['product_log_send_mail']=='1'){
		        		$Product = ClassRegistry::init('Product');
		        		$product_data=$Product->find('first',array('fields'=>'Product.id,Product.product_manager','conditions'=>array('Product.id'=>$product_id,'Product.product_manager >'=>0),'recursive' => -1));
		        		if(!empty($product_data)){
		        			$product_manager=$product_data['Product']['product_manager'];
		        			$Operator = ClassRegistry::init('Operator');
	     					$admin_info=$Operator->check_login();
						$operator_info=$Operator->find('first',array('fields'=>'name,email','conditions'=>array('id'=>$product_manager,'email <>'=>'')));
						$NotifyTemplateType = ClassRegistry::init('NotifyTemplateType');
						$NotifyTemplateType->set_locale($controller->backend_locale);
	     					$notify_template=$NotifyTemplateType->typeformat('product_action_log','email');
						if(!empty($operator_info)&&!empty($notify_template)){
							$operator_name=$admin_info['name'];
							$product_link=$controller->server_host.$controller->admin_webroot."products/view/".$action_data['product_id'];
							$action_remark=date("Y-m-d H:i:s").$admin_info['name'].$action_data['remark'];
							$shop_name = $controller->configs['shop_name'];
							$subject = $notify_template['email']['NotifyTemplateTypeI18n']['title'];
			        			@eval("\$subject = \"$subject\";");
							$html_body = $notify_template['email']['NotifyTemplateTypeI18n']['param01'];
			        			eval("\$html_body = \"$html_body\";");
							$text_body = $notify_template['email']['NotifyTemplateTypeI18n']['param02'];
			        			@eval("\$text_body = \"$text_body\";");
			        			$receiver_email=$operator_info['Operator']['email'];
							$mailsendqueue = array(
								'sender_name' => $shop_name,//发送从姓名
								'receiver_email' => $receiver_email,//接收人姓名;接收人地址
								'cc_email' => ';',//抄送人
								'bcc_email' => ';',//暗送人
								'title' => $subject,//主题 
								'html_body' => $html_body,//内容
								'text_body' => trim($text_body)==''?$html_body:$text_body,//内容
								'sendas' => 'html',
								'flag' => 0,
								'pri' => 0
				                	);
				            	App::import('Component', 'NotifyComponent');
		        				$Notify = new NotifyComponent();
							$Notify->send_email($mailsendqueue, $controller->configs);
			            	}
		        		}
		        }
		        return true;
	    }
	    
	    /**
	     * update_action方法,新增商品操作日志
	     *
	     * @param array    $action_data	商品日志数据
	     *
	     */
	    public function update_action($action_data,$controller=null){
	    		$this->saveAll(array('ProductAction' => $action_data));
	    		if(isset($controller->configs['product_log_send_mail'])&&$controller->configs['product_log_send_mail']=='1'){
		        		$Product = ClassRegistry::init('Product');
		        		$product_data=$Product->find('first',array('fields'=>'Product.id,Product.product_manager','conditions'=>array('Product.id'=>$action_data['product_id'],'Product.product_manager >'=>0),'recursive' => -1));
		        		if(!empty($product_data)){
		        			$product_manager=$product_data['Product']['product_manager'];
		        			$Operator = ClassRegistry::init('Operator');
	     				$admin_info=$Operator->check_login();
						$operator_info=$Operator->find('first',array('fields'=>'name,email','conditions'=>array('id'=>$product_manager,'email <>'=>'')));
						$NotifyTemplateType = ClassRegistry::init('NotifyTemplateType');
						$NotifyTemplateType->set_locale($controller->backend_locale);
	     					$totify_template=$NotifyTemplateType->typeformat('product_action_log','email');
						if(!empty($operator_info)&&!empty($totify_template)){
							$operator_name=$operator_info['Operator']['name'];
							$product_link=$controller->server_host.$controller->admin_webroot."products/view/".$action_data['product_id'];
							$action_remark=date("Y-m-d H:i:s").$admin_info['name'].$action_data['remark'];
							$shop_name = $controller->configs['shop_name'];
							$subject = $totify_template['email']['NotifyTemplateTypeI18n']['title'];
			        			@eval("\$subject = \"$subject\";");
							$html_body = $totify_template['email']['NotifyTemplateTypeI18n']['param01'];
			        			eval("\$html_body = \"$html_body\";");
							$text_body = $totify_template['email']['NotifyTemplateTypeI18n']['param02'];
			        			@eval("\$text_body = \"$text_body\";");
			        			$receiver_email=$operator_info['Operator']['email'];
							$mailsendqueue = array(
								'sender_name' => $shop_name,//发送从姓名
								'receiver_email' => $receiver_email,//接收人姓名;接收人地址
								'cc_email' => ';',//抄送人
								'bcc_email' => ';',//暗送人
								'title' => $subject,//主题 
								'html_body' => $html_body,//内容
								'text_body' => trim($text_body)==''?$html_body:$text_body,//内容
								'sendas' => 'html',
								'flag' => 0,
								'pri' => 0
				                	);
				            	App::import('Component', 'NotifyComponent');
		        				$Notify = new NotifyComponent();
							$Notify->send_email($mailsendqueue, $controller->configs);
			            	}
		        		}
		        }
	    }
	    
}
