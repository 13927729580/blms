<?php
/**
 * 用户项目费用
 */
class UserProjectFee extends AppModel{
	/*
	* @var $useDbConfig 数据库配置
	*/
	public $useDbConfig = 'oms';
	public $name = 'UserProjectFee';
	
	
	function savecompare($ControllerObj,$save_data,$params,$type=""){
	        $result="";
	        $cond=array();
	        if(isset($params['conditions'])&&!empty($params['conditions'])){
	            $cond['conditions']=$params['conditions'];
	        }
	        if(isset($params['order'])&&!empty($params['order'])){
	            $cond['order']=$params['order'];
	        }
	        if(isset($params['group'])&&!empty($params['group'])){
	            $cond['group']=$params['group'];
	        }
	        if(isset($params['limit'])&&!empty($params['limit'])){
	            $cond['limit']=$params['limit'];
	        }
	        if(isset($params['fields'])&&!empty($params['fields'])){
	            $cond['fields']=$params['fields'];
	        }
	        if(!empty($save_data)&&!empty($cond)){
	            $model = isset($this->name)?$this->name: 'AppModel';
	            $type=($type=="")?$model:$type;
	            $old_data= parent::find('first',$cond);
	            $old_data=isset($old_data[$model])?$old_data[$model]:array();
	            if(!empty($old_data)){
			$UserProject = ClassRegistry::init('UserProject');
			$project_resource_data=$UserProject->project_resource($ControllerObj->backend_locale);
			   $result.=(isset($project_resource_data['user_project_fee'][$save_data['fee_type']])?$project_resource_data['user_project_fee'][$save_data['fee_type']]:$save_data['fee_type'])."<br >";
	                foreach($save_data as $k=>$v){
	                    if(isset($old_data[$k])&&is_null($old_data[$k])&&trim($old_data[$k])!=trim($v)){
	                        $result.=$type.".".$k."   [null] => [".$v."] <br >";
	                    }else if(isset($old_data[$k])&&trim($old_data[$k])!=trim($v)){
	                        $result.=$type.".".$k."   [".$old_data[$k]."] => [".$v."] <br >";
	                    }
	                }
	            }else if(!empty($save_data)){
	            		$UserProject = ClassRegistry::init('UserProject');
	            		$project_resource_data=$UserProject->project_resource($ControllerObj->backend_locale);
	            		$save_data_result=array(
	            			isset($project_resource_data['user_project_fee'][$save_data['fee_type']])?$project_resource_data['user_project_fee'][$save_data['fee_type']]:$save_data['fee_type'],
	            			$save_data['amount'],
	            			isset($project_resource_data['payment_list'][$save_data['payment_id']])?$project_resource_data['payment_list'][$save_data['payment_id']]:$save_data['payment_id'],
	            			date('Y-m-d',strtotime($save_data['payment_time'])),
	            			$save_data['receipt_number'],
	            			isset($save_data['remark'])?$save_data['remark']:''
	            		);
	            		$result=implode(' ',$save_data_result);
	            }
	        }
	        return $result;
	}
}