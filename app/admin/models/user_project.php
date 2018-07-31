<?php
/**
 * 用户项目
 */
class UserProject extends AppModel{
	/*
	* @var $useDbConfig 数据库配置
	*/
	public $useDbConfig = 'oms';
	public $name = 'UserProject';
	
	function project_resource($locale){
		$InformationResource = ClassRegistry::init('InformationResource');
		$Resource_info = $InformationResource->information_formated(array('user_project','user_project_time','user_project_site','user_project_fee'), $locale);
		if(isset($Resource_info['user_project'])&&!empty($Resource_info['user_project'])){
	    		$sub_user_project=array_keys($Resource_info['user_project']);
			$sub_info_resource = $InformationResource->information_formated($sub_user_project,$locale,false);
			$Resource_info['all_user_project']=$Resource_info['user_project'];
			foreach($Resource_info['all_user_project'] as $k=>$v){
				if(isset($sub_info_resource[$k])&&!empty($sub_info_resource[$k])){
					unset($Resource_info['all_user_project'][$k]);
					foreach($sub_info_resource[$k] as $kk=>$vv)$Resource_info['all_user_project'][$kk]=$vv;
				}
			}
			ksort($Resource_info['all_user_project']);
			$Resource_info=array_merge($Resource_info,$sub_info_resource);
	       }
	       $Resource_info['project_status']=array('0'=>'待付款','1'=>'已付款');
	       $PaymentI18n = ClassRegistry::init('PaymentI18n');
	       $payment_list=$PaymentI18n->find('list',array('fields'=>'payment_id,name','conditions'=>array('name <>'=>'','locale'=>$locale)));
	       $Resource_info['payment_list']=$payment_list;
	       return $Resource_info;
	}
	
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
		                foreach($save_data as $k=>$v){
			                    if(isset($old_data[$k])&&is_null($old_data[$k])&&trim($old_data[$k])!=trim($v)){
			                        	$result.=$type.".".$k."   [null] => [".$v."] <br >";
			                    }else if(isset($old_data[$k])&&trim($old_data[$k])!=trim($v)){
			                        	$result.=$type.".".$k."   [".$old_data[$k]."] => [".$v."] <br >";
			                    }
		                }
	            }else if(!empty($save_data)){
	            		$project_resource_data=$this->project_resource($ControllerObj->backend_locale);
	            		$save_data_result=array(
	            			isset($project_resource_data['all_user_project'][$save_data['project_code']])?$project_resource_data['all_user_project'][$save_data['project_code']]:$save_data['project_code'],
	            			date('Y-m-d',strtotime($save_data['project_time'])),
	            			isset($project_resource_data['user_project_time'][$save_data['project_hour']])?$project_resource_data['user_project_time'][$save_data['project_hour']]:$save_data['project_hour'],
	            			isset($project_resource_data['user_project_site'][$save_data['project_site']])?$project_resource_data['user_project_site'][$save_data['project_site']]:$save_data['project_site'],
	            			isset($project_resource_data['project_status'][$save_data['status']])?$project_resource_data['project_status'][$save_data['status']]:$save_data['status'],
	            			$save_data['remark']
	            		);
	            		$result=implode(' ',$save_data_result);
	            }
	        }
	        return $result;
	}
}