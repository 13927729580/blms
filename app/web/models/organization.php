<?php
class Organization extends AppModel{
	public $useDbConfig = 'hr';
	public $name = 'Organization';
	
	function getIdByName($organization_name=''){
		$OrganizationInfo=$this->find('first',array('conditions'=>array('Organization.status'=>'1','Organization.authentication_status'=>'3','Organization.name'=>$organization_name)));
		return isset($OrganizationInfo['Organization'])?$OrganizationInfo['Organization']['id']:0;
	}
	
	function get_manager($organization_id=0){
		$OrganizationManager = ClassRegistry::init('OrganizationManager');
		$OrganizationMember = ClassRegistry::init('OrganizationMember');
	        $manager_ids = array();
	        $organization_info = $this->find('first',array('conditions'=>array('Organization.id'=>$organization_id)));
	        $manager_ids[]=$organization_info['Organization']['manage_user'];
	        $org_ma = $OrganizationManager->find('all',array('conditions'=>array('OrganizationManager.organization_id'=>$organization_id,'OrganizationManager.manager_type'=>0)));
	        $cond = array();
	        if(isset($org_ma)&&is_array($org_ma)&&count($org_ma)>0){
	            foreach ($org_ma as $k => $v) {
	                $cond['OrganizationMember.id'][]=$v['OrganizationManager']['organization_member_id'];
	            }
	        }
	        if(!empty($cond)){
	            $org_ma = $OrganizationMember->find('all',array('conditions'=>$cond));
	        }
	        if(isset($org_ma)&&is_array($org_ma)&&count($org_ma)>0){
	            foreach ($org_ma as $k => $v) {
	                $manager_ids[] = $v['OrganizationMember']['user_id'];
	            }
	        }
	        //pr($manager_ids);
	        $this->set('org_manager',$manager_ids);
	       
	        $manage = $OrganizationManager->find('all',array('conditions'=>array('OrganizationManager.organization_id'=>$organization_id)));
	        $conn = array();
	        if(isset($manage)&&is_array($manage)&&count($manage)>0){
	            foreach ($manage as $k => $v) {
	                $conn['OrganizationMember.id'][]=$v['OrganizationManager']['organization_member_id'];
	            }
	        }
	        if(!empty($conn)){
	            $manages = $OrganizationMember->find('all',array('conditions'=>$conn));
	        }
	        $manage_ids = array();
	        $ma_check = '';
	        if(isset($manages)&&is_array($manages)&&count($manages)>0){
	            foreach ($manages as $k => $v) {
	                $manage_ids[]=$v['OrganizationMember']['user_id'];
	                $ma_check[$v['OrganizationMember']['id']] = $v['OrganizationMember']['user_id'];
	            }
	        }
	        $manage_ids[]=$organization_info['Organization']['manage_user'];
	        $this->set('manager_ids',$manage_ids);
	        $dep_manage = '';
	        if(isset($manage)&&is_array($manage)&&count($manage)>0){
	            foreach ($manage as $k => $v) {
	                if(isset($ma_check[$v['OrganizationManager']['organization_member_id']])){
	                    $dep_manage[$v['OrganizationManager']['manager_type']][]=$ma_check[$v['OrganizationManager']['organization_member_id']];
	                }
	            }
	        }
	        $this->set('dep_managers',$dep_manage);
    	}
    	
    	function manager_operator($organization_id=0,$user_id=0){
    		$action_list=array();
    		$OrganizationMember = ClassRegistry::init('OrganizationMember');
    		$OrganizationRole = ClassRegistry::init('OrganizationRole');
    		$OrganizationAction = ClassRegistry::init('OrganizationAction');
    		$organization_detail=$this->find('first',array('conditions'=>array('id'=>$organization_id,'manage_user'=>$user_id,'manage_user <>'=>0)));
    		if(empty($organization_detail)){
	    		$member_detail=$OrganizationMember->find('first',array('conditions'=>array('organization_id'=>$organization_id,'user_id'=>$user_id,'user_id <>'=>0,'status'=>'1')));
	    		$role_ids=isset($member_detail['OrganizationMember']['roles'])?explode(',',trim($member_detail['OrganizationMember']['roles'])):array();
	    		if(!empty($role_ids)){
		    		$role_lists=$OrganizationRole->find('list',array('fields'=>'id,actions','conditions'=>array('id'=>$role_ids,'actions <>'=>'','organization_id'=>array(-1,$organization_id),'status'=>'1')));
		    		$role_actions=array();
		    		foreach($role_lists as $v)$role_actions=array_merge($role_actions,explode(',',$v));
		    		if(!empty($role_actions)){
		    			$role_actions=array_unique($role_actions);
		    			$action_list=$OrganizationAction->find('list',array('fields'=>'id,code','conditions'=>array('status'=>'1','code <>'=>'','code'=>$role_actions)));
		    		}
	    		}
	    		if(isset($member_detail['OrganizationMember']['id'])){
	    			$organization_member_id=$member_detail['OrganizationMember']['id'];
	    			$OrganizationManager = ClassRegistry::init('OrganizationManager');
	    			$member_manager_list=$OrganizationManager->find('list',array('fields'=>'manager_type,id','conditions'=>array('organization_id'=>$organization_id,'organization_member_id'=>$organization_member_id)));
	    			if(!empty($member_manager_list)){
	    				$action_list=$OrganizationAction->find('list',array('fields'=>'id,code','conditions'=>array('status'=>'1','code <>'=>'')));
	    				$action_list[]='member';
	    			}
	    			if(isset($member_manager_list[0])){
	    				$action_list[]='manager';
    					//$action_list[]='third_party_platform';
	    			}
	    		}
    		}else{
    			$action_list=$OrganizationAction->find('list',array('fields'=>'id,code','conditions'=>array('status'=>'1','code <>'=>'')));
    			$action_list[]='member';
    			$action_list[]='manager';
    			//$action_list[]='third_party_platform';
    		}
		return $action_list;
    	}
}