<?php

/**
 * 客户预约
 */
class ClientAppointment extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'isp';
    /*
     * @var $name ClientAppointment 客户预约
     */
    public $name = 'ClientAppointment';
    
    public $belongsTo = array(
		'Client'=>array(
			'className' => 'Client',
			'conditions' => "Client.id=ClientAppointment.client_id and Client.status='1'",
			'order' => '',
			'fields'=>'Client.realname,Client.mobile,Client.birthday,Client.sex,Client.img01',
			'dependent' => true
		)
    );
}
