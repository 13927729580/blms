<?php

/**
 * 支付方式模型.
 */
class payment extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Payment 付款表
     */
    public $name = 'Payment';
    /*
     * @var $hasOne array 付款语言对应表
     */
    public $hasOne = array('PaymentI18n' => array('className' => 'PaymentI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'payment_id',
        ),
    );

    public $acionts_parent_format = array();

    /**
     * availables方法，按照升序的方式显示支付方式.
     *
     * @return array $payments 按照Payment中的status、order_use_flag字段以及PaymentI18n表中的status字段来排序，并将排序的结果放进一个数组中返回。
     */
    public function availables()
    {
        $payments = $this->find('all', array('order' => array('Payment.orderby asc'),
                    'conditions' => array('Payment.status' => 1, 'Payment.order_use_flag' => 1, 'PaymentI18n.status' => 1),
                    'fields' => array('Payment.id', 'Payment.code', 'Payment.version', 'PaymentI18n.status', 'Payment.fee', 'Payment.config', 'Payment.is_cod', 'Payment.is_online', 'Payment.supply_use_flag', 'Payment.order_use_flag', 'Payment.logo', 'PaymentI18n.name', 'PaymentI18n.description',
                        ), ));

        return $payments;
    }
    public function cac_availables()
    {
        $payments = $this->find('all', array('order' => array('Payment.orderby asc'),
                    'conditions' => array('Payment.status' => 1, 'Payment.is_getinshop' => 1, 'Payment.order_use_flag' => 1, 'PaymentI18n.status' => 1),
                    'fields' => array('Payment.id', 'Payment.code', 'Payment.version', 'PaymentI18n.status', 'Payment.fee', 'Payment.config', 'Payment.is_cod', 'Payment.is_online', 'Payment.supply_use_flag', 'Payment.order_use_flag', 'Payment.logo', 'PaymentI18n.name', 'PaymentI18n.description',
                        ), ));

        return $payments;
    }

    public function find_pay_by_code($code)
    {
        $pay = $this->findbycode($code);

        return $pay;
    }

    public function get_payment_id($payment_id)
    {
        $pay = $this->findbyid($payment_id);

        return $pay;
    }

    public function getOrderPayments()
    {
        $this->acionts_parent_format = array();
        $payments = $this->find('all', array('order' => array('Payment.orderby asc'),
                    'conditions' => array('Payment.status' => 1, 'Payment.order_use_flag' => 1),
                    'fields' => array('Payment.id', 'Payment.parent_id', 'Payment.code', 'Payment.version', 'PaymentI18n.status', 'Payment.fee', 'Payment.config', 'Payment.is_cod', 'Payment.is_online', 'Payment.supply_use_flag', 'Payment.order_use_flag', 'Payment.logo', 'PaymentI18n.name', 'PaymentI18n.description',
                        ), ));
        if (!empty($payments) && is_array($payments)) {
            foreach ($payments as $k => $v) {
                $this->acionts_parent_format[$v['Payment']['parent_id']][] = $v;
            }
        }

        return $this->subcat_get(0);
    }

    public function getOrderChildPayments($parent_id)
    {
        $payments = $this->find('all', array('order' => array('Payment.orderby asc'),
                    'conditions' => array('Payment.parent_id' => $parent_id, 'Payment.status' => 1, 'Payment.order_use_flag' => 1),
                    'fields' => array('Payment.id', 'Payment.code', 'Payment.version', 'PaymentI18n.status', 'Payment.fee', 'Payment.config', 'Payment.is_cod', 'Payment.is_online', 'Payment.supply_use_flag', 'Payment.order_use_flag', 'Payment.logo', 'PaymentI18n.name', 'PaymentI18n.description',
                        ), ));

        return $payments;
    }

    public function subcat_get($action_id)
    {
        $subcat = array();
        if (isset($this->acionts_parent_format[$action_id]) && is_array($this->acionts_parent_format[$action_id])) {
            foreach ($this->acionts_parent_format[$action_id] as $k => $v) {
                $action = $v;
                if (isset($this->acionts_parent_format[$v['Payment']['id']]) && is_array($this->acionts_parent_format[$v['Payment']['id']])) {
                    $action['SubMenu'] = $this->subcat_get($v['Payment']['id']);
                }
                $subcat[$k] = $action;
            }
        }

        return $subcat;
    }
    
    /*
    		微信红包发放
    		@param  $pack_data
			    		array(
			    			user_id:用户Id
			    			money:红包金额(元)
			    			act_name:红包主题
			    			remark:红包备注
			    		)
    */
    function wechat_redpacket($pack_data=array()){
    		$result=array();
    		$result['code']='0';
    		$result['message']='无法发放';
    		$to_user_id=isset($pack_data['user_id'])?$pack_data['user_id']:0;
    		$SynchroUser = ClassRegistry::init('SynchroUser');
    		$wechat_user=$SynchroUser->find('first',array('conditions'=>array('user_id'=>$to_user_id,'type'=>'wechat','status'=>'1')));
    		if(!empty($wechat_user)){
    			$pack_data['openId']=$wechat_user['SynchroUser']['account'];
    			$send_amount=isset($pack_data['money'])?$pack_data['money']:0;
    			$Payment = ClassRegistry::init('Payment');
    			App::import('Vendor', 'Weixinpay', array('file' => 'WxPay.Api.php'));
    			$PaymentData=$Payment->find('first',array('conditions'=>array('code'=>'weixinpay','Payment.status'=>'1')));
			$payment_config=isset($PaymentData['Payment'])?unserialize($PaymentData['Payment']['config']):array();
			if(empty($payment_config))return $result;
			$OpenModel = ClassRegistry::init('OpenModel');
			$sender_open_model=$OpenModel->find('first',array('conditions'=>array('open_type'=>'wechat','app_id'=>$payment_config['APPID'])));
			if(empty($sender_open_model))return $result;
			$pack_data['sender']=$sender_open_model['OpenModel']['open_type_id'];
			$inputObj = new WxPayRedPackData();
			$inputObj->SetWxappid($payment_config['APPID']);//公众账号ID
			$inputObj->SetMchId($payment_config['MCHID']); //商户号
			$inputObj->SetKey($payment_config['KEY']);//商户平台密钥key
			$inputObj->SetReOpenid($wechat_user['SynchroUser']['account']);
			$inputObj->SetSendName(isset($pack_data['sender'])?$pack_data['sender']:''); // 红包发送者名称
			$inputObj->SetTotalAmount($send_amount*100); // 收红包的用户的金额，精确到分
			$inputObj->SetTotalNum(1); // 收红包的个数
			$inputObj->SetWishing(isset($pack_data['remark'])?$pack_data['remark']:'');
			$inputObj->SetActName(isset($pack_data['act_name'])?$pack_data['act_name']:''); // 红包主题
			$inputObj->SetRemark(isset($pack_data['remark'])?$pack_data['remark']:''); // 备注
			try{
				$api_result = WxPayApi::sendRedPack($inputObj);
				if(isset($api_result['result_code'])&&$api_result['result_code']=='SUCCESS'){
					$result['code']='1';
					$result['message']=isset($api_result['err_code_des'])?$api_result['err_code_des']:'发放成功';
				}else{
					$result['message']=isset($api_result['err_code_des'])?$api_result['err_code_des']:'发放失败';
				}
			} catch (Exception $e) {
				$result['message']=$e->getMessage();
			}
			$WebserviceLog = ClassRegistry::init('WebserviceLog');
			$log_data=array(
				'id'=>0,
				'nick'=>'System',
				'method'=>'wechat_redpacket',
				'post_data'=>$pack_data,
				'return_data'=>isset($api_result)?$api_result:$result,
				'error_message'=>$result['message'],
				'status'=>$result['code'],
				'remark'=>$result['message']
			);
			$WebserviceLog->save($log_data);
    		}
    		return $result;
    }
}
