<?php
class UserProjectsController extends AppController{
	public $name = 'UserProjects';
	public $helpers = array('Html','Pagination');
	public $uses = array('User','Operator','InformationResource','UserExperience','UserEducation','UserConfig','UserProject','UserProjectLog','NotifyTemplateType');
	public $components = array('RequestHandler','Pagination','Notify');
	
	public function index(){
		$this->layout = 'default_full';            //引入模版
		$this->page_init();                        //页面初始化
        	$this->pageTitle = '预约报名 - '.$this->configs['shop_title'];
        	
		$informationresource_infos = $this->InformationResource->code_information_formated(array('user_project','user_project_time','user_project_site','education_type','company_type'), $this->locale);
		if(isset($informationresource_infos['user_project'])&&!empty($informationresource_infos['user_project'])){
			$sub_user_project=array_keys($informationresource_infos['user_project']);
			$sub_info_resource = $this->InformationResource->code_information_formated($sub_user_project,$this->locale);
			$informationresource_infos=array_merge($informationresource_infos,$sub_info_resource);
			$informationresource_infos['all_user_project']=$informationresource_infos['user_project'];
			foreach($sub_info_resource as $k=>$v){
				if(isset($informationresource_infos['all_user_project'][$k]))unset($informationresource_infos['all_user_project'][$k]);
				$informationresource_infos['all_user_project']=array_merge($informationresource_infos['all_user_project'],$v);
			}
		}
		$this->set('informationresource_infos', $informationresource_infos);
		
        	if(isset($this->params['url']['InvitingSource'])&&intval($this->params['url']['InvitingSource'])>0){
        		$InvitingSource=$this->Operator->find('first',array('fields'=>'id,name,avatar,mobile,email','conditions'=>array('id'=>$this->params['url']['InvitingSource'],'status'=>'1')));
        		$this->set('InvitingSource',$InvitingSource);
        	}
        	
        	if(isset($this->params['url']['Inviting'])&&trim($this->params['url']['Inviting'])!=''){
        		if(trim($this->params['url']['Inviting'])!='-1'){
        			$InvitingList=explode(',',trim($this->params['url']['Inviting']));
        		}else{
        			$InvitingList=isset($informationresource_infos['all_user_project'])?array_keys($informationresource_infos['all_user_project']):array();
        		}
        		$this->set('InvitingList',$InvitingList);
        	}
        	if($this->RequestHandler->isPost()){
        		Configure::write('debug',1);
        		$this->layout = 'ajax';
        		
        		$result=array();
        		$result['code']="0";
        		$result['message']=$this->ld['invalid_operation'];
        		
        		$complete_user_project=array();
        		$user_project_list=array();
        		if(isset($this->data['UserProject']['project_code'])&&!empty($this->data['UserProject']['project_code'])){
        			foreach($this->data['UserProject']['project_code'] as $k=>$v){
        				if($v==''||$v=='0')continue;
        				$user_project_data=array(
        					'project_code'=>$v,
        					'project_time'=>isset($this->data['UserProject']['project_time'][$k])&&$this->data['UserProject']['project_time'][$k]!=''?date('Y-m-01 00:00:00',strtotime(str_replace("/","-",$this->data['UserProject']['project_time'][$k]))):'',
        					'project_hour'=>isset($this->data['UserProject']['project_hour'][$k])?$this->data['UserProject']['project_hour'][$k]:'',
        					'project_site'=>isset($this->data['UserProject']['project_site'][$k])?$this->data['UserProject']['project_site'][$k]:0
        				);
        				$user_project_list[]=$user_project_data;
        			}
        			if(!empty($user_project_list)){
        				$user_mobile=isset($this->data['User']['mobile'])?trim($this->data['User']['mobile']):'';
        				if($user_mobile!=''){
        					$user_info=$this->User->find('first',array('fields'=>'User.id,User.mobile','conditions'=>array('User.mobile'=>$user_mobile)));
        					$user_data=array(
        						'id'=>isset($user_info['User'])?$user_info['User']['id']:0,
        						'first_name'=>isset($this->data['User']['first_name'])?$this->data['User']['first_name']:'',
        						'identity_card'=>isset($this->data['User']['identity_card'])?trim($this->data['User']['identity_card']):'',
        						'mobile'=>$user_mobile,
        						'email'=>isset($this->data['User']['email'])?trim($this->data['User']['email']):'',
        						'sex'=>isset($this->data['User']['sex'])?trim($this->data['User']['sex']):'0'
        					);
        					if(!isset($user_info['User'])){
        						$user_data['operator_id']=isset($this->data['UserProject']['manager'])?$this->data['UserProject']['manager']:0;
        						$user_data['user_sn']=$user_mobile;
        						$user_data['name']=isset($this->data['User']['first_name'])&&trim($this->data['User']['first_name'])!=''?$this->data['User']['first_name']:$user_mobile;
        					}else if(isset($user_info['User'])&&empty($user_info['User']['operator_id'])){
        						$user_data['operator_id']=isset($this->data['UserProject']['manager'])?$this->data['UserProject']['manager']:0;
        					}
        					$this->User->save($user_data);
        					extract($user_data,EXTR_PREFIX_ALL,'user');
        					$user_id=$this->User->id;
        					
        					if(isset($this->data['UserEducation']['education_id'])&&trim($this->data['UserEducation']['education_id'])!=''){
        						$education_id=trim($this->data['UserEducation']['education_id']);
        						$EducationInfo=$this->UserEducation->find('first',array('conditions'=>array('user_id'=>$user_id)));
        						$EducationData=array(
        							'id'=>isset($EducationInfo['UserEducation'])?$EducationInfo['UserEducation']['id']:0,
        							'user_id'=>$user_id,
        							'education_id'=>$education_id
        						);
        						$this->UserEducation->save($EducationData);
        					}
        					
        					if(isset($this->data['UserExperience']['company_name'])&&trim($this->data['UserExperience']['company_name'])!=''){
        						$company_name=trim($this->data['UserExperience']['company_name']);
        						$ExperienceInfo=$this->UserExperience->find('first',array('conditions'=>array('user_id'=>$user_id)));
        						$ExperienceData=array(
        							'id'=>isset($ExperienceInfo['UserExperience'])?$ExperienceInfo['UserExperience']['id']:0,
        							'user_id'=>$user_id,
        							'company_name'=>$company_name,
        							'company_industry'=>isset($this->data['UserExperience']['company_industry'])?$this->data['UserExperience']['company_industry']:'',
        							'position'=>isset($this->data['UserExperience']['position'])?$this->data['UserExperience']['position']:''
        						);
        						$this->UserExperience->save($ExperienceData);
        					}
        					
        					if(isset($this->data['UserProject']['remark'])&&trim($this->data['UserProject']['remark'])!=''){
        						$this->data['UserConfig']['remark']=trim($this->data['UserProject']['remark']);
        					}
        					if(isset($this->data['UserConfig'])&&!empty($this->data['UserConfig'])){
        						foreach($this->data['UserConfig'] as $k=>$v){
        							$user_config_info=$this->UserConfig->find('first',array('conditions'=>array('UserConfig.code'=>$k,'UserConfig.user_id'=>$user_id)));
        							if(empty($user_config_info)&&trim($v)=='')continue;
        							$user_config_data=array(
        								'id'=>isset($user_config_info['UserConfig'])?$user_config_info['UserConfig']['id']:0,
        								'user_id'=>$user_id,
        								'code'=>$k,
        								'value'=>$v
        							);
        							$this->UserConfig->save($user_config_data);
        						}
        					}
        					$complete_project_list=array();
        					$project_manager=$this->Operator->find('first',array('fields'=>'id,name,avatar,mobile,email','conditions'=>array('id'=>isset($this->data['UserProject']['manager'])?$this->data['UserProject']['manager']:0,'status'=>'1')));
        					foreach($user_project_list as $v){
        						$user_project_info=$this->UserProject->find('first',array('fields'=>'UserProject.id,UserProject.status','conditions'=>array('UserProject.user_id'=>$user_id,'UserProject.project_code'=>$v['project_code'])));
        						if(isset($user_project_info['UserProject']))continue;
        						$user_project_data=array(
        							'id'=>isset($user_project_info['UserProject'])?$user_project_info['UserProject']['id']:0,
        							'user_id'=>$user_id,
        							'manager'=>isset($this->data['UserProject']['manager'])?$this->data['UserProject']['manager']:0,
        							'project_code'=>$v['project_code'],
        							'project_time'=>$v['project_time'],
        							'project_hour'=>$v['project_hour'],
        							'project_site'=>$v['project_site'],
        							'status'=>0,
        							'remark'=>isset($this->data['UserProject']['remark'])?$this->data['UserProject']['remark']:'',
        						);
        						$this->UserProject->save($user_project_data);
        						$user_project_id=$this->UserProject->id;
        						$this->UserProjectLog->save(array(
        							'id'=>0,
        							'user_id'=>$user_id,
        							'project_code'=>$v['project_code'],
        							'operator'=>isset($user_data['first_name'])?$user_data['first_name']:'',
        							'operator_id'=>-1,
        							'status'=>'0',
        							'remark'=>json_encode(array_merge(array('用户报名'),$this->data))
        						));
        						$complete_user_project[$v['project_code']]=isset($informationresource_infos['all_user_project'][$v['project_code']])?$informationresource_infos['all_user_project'][$v['project_code']]:$v['project_code'];
        						
        						$complete_project_list[$v['project_code']]=(isset($informationresource_infos['all_user_project'][$v['project_code']])?$informationresource_infos['all_user_project'][$v['project_code']]:$v['project_code'])." ".date('Y-m',strtotime($v['project_time']))." ".((isset($informationresource_infos['user_project_time'][$v['project_hour']])?$informationresource_infos['user_project_time'][$v['project_hour']]:$v['project_hour']))." ".(isset($informationresource_infos['user_project_site'][$v['project_site']])?$informationresource_infos['user_project_site'][$v['project_site']]:$v['project_site']);
        					}
        					if(!empty($complete_user_project)){
        						$complete_project_list=implode(',',$complete_project_list);
        						$Notify_template=$this->NotifyTemplateType->typeformat('user_project_sign_up','sms');
        						if(!empty($Notify_template)&&!empty($project_manager)&&trim($project_manager['Operator']['mobile'])!=''){
        							$sms_content=$Notify_template['sms']['NotifyTemplateTypeI18n']['param02'];
        							@eval("\$sms_content = \"$sms_content\";");
        							$sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
								$sms_result=$this->Notify->send_sms($project_manager['Operator']['mobile'],$sms_content,$sms_kanal,$this->configs);
        						}
        						$result['code']="1";
        						$result['message']='提交成功';
        						$result['complete_user_project']=$complete_user_project;
        					}else{
        						$result['message']='当前手机号已报名此项目';
        					}
        				}else{
        					$result['message']=$this->ld['phone_can_not_be_empty'];
        				}
        			}else{
        				$result['message']='项目信息不完整';
        			}
        		}else{
    				$result['message']='缺少项目信息';
    			}
        		die(json_encode($result));
        	}
	}
	
	function entered(){
		$params=isset($_GET)?$_GET:array();
		if(isset($params['url']))unset($params['url']);
		if(empty($params))$this->redirect('/');
		$this->redirect('index?'.http_build_query($params));
	}
}
