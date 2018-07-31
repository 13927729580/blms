<?php
/**
 * 用户项目日志
 */
class UserProjectLog extends AppModel{
	/*
	* @var $useDbConfig 数据库配置
	*/
	public $useDbConfig = 'oms';
	public $name = 'UserProjectLog';
	
	function project_log($log_data=array()){
		$log_cond=array(
			'user_id'=>$log_data['user_id'],
			'project_code'=>$log_data['project_code'],
			'status'=>$log_data['status'],
			'operator_id'=>$log_data['operator_id'],
			'created >='=>date('Y-m-d H:i:s',strtotime('-20 minute'))
		);
		$last_log_data=$this->find('first',array('fields'=>'id,remark','conditions'=>$log_cond,'order'=>'UserProjectLog.id desc'));
		if(!empty($last_log_data)){
			$log_data['id']=$last_log_data['UserProjectLog']['id'];
			$log_remark=json_decode($last_log_data['UserProjectLog']['remark'],true);
			$log_remark[]="(".date('Y-m-d H:i:s').")".$log_data['remark'];
			$log_data['remark']=json_encode($log_remark);
		}else{
			$log_data['id']=0;
			$log_data['remark']=json_encode(array($log_data['remark']));
		}
		$this->save($log_data);
	}
}