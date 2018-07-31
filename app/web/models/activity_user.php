<?php
/*****************************************************************************
 * svcms  活动管理模型
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
class ActivityUser extends AppModel{
	    /*
	    * @var $useDbConfig 数据库配置
	    */
	    public $useDbConfig = 'cms';
	    public $name = 'ActivityUser';
	    	
		public $belongsTo = array(
			'Activity' => array(
				'className' => 'Activity',
				'conditions' => 'Activity.id=ActivityUser.activity_id',
				'order' => '',
				'dependent' => true,
				'foreignKey' => ''
			)
		);
	    
	    
	    /*
	    public function find($type, $params = array()){
	    		if(!isset($params['joins'])){
	    			$joins=array(
		                    array(
							'table' => 'svoms_users',
							'alias' => 'User',
							'type' => 'left',
							'conditions' => array('ActivityUser.user_id = User.id'),
							'fields'=>'User.id,User.name,User.first_name,User.img01'
		                         )
		            	);
		              $params['joins']=$joins;
	    		}
	    		if(isset($params['fields'])&&is_array($params['fields'])){
	    			$search_fields=$params['fields'];
	    			$search_fields[]="User.id";
	    			$search_fields[]="User.name";
	    			$search_fields[]="User.first_name";
	    			$search_fields[]="User.img01";
	    			$search_fields[]="User.email";
	    			$search_fields[]="User.mobile";
	    			$params['fields']=$search_fields;
	    		}else if(isset($params['fields'])&&is_string($params['fields'])){
	    			$search_fields=$params['fields'];
	    			$search_fields="User.id,User.name,User.first_name,User.img01,User.email,User.mobile,".$search_fields;
	    			$params['fields']=$search_fields;
	    		}else{
	    			$params['fields']=array('User.id,User.name,User.first_name,User.img01,User.email,User.mobile,ActivityUser.*');
	    		}
	    		$data=parent::find($type, $params);
	    		return $data;
	    }
	    */
	    
	    function user_activity_list(){
	    		$result=array();
    			$user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
    			$conditions=array();
    			$conditions['Activity.status']='1';
    			$conditions['ActivityUser.status']='1';
    			$conditions['ActivityUser.user_id']=$user_id;
    			$activity_list=$this->find('all',array('conditions'=>$conditions));
    			$result['activity_list']=$activity_list;
    			if(!empty($activity_list)){
    				$activity_ids=array();
    				foreach($activity_list as $v)$activity_ids[]=$v['ActivityUser']['activity_id'];
    				
    				$activity_user_totals=$this->find('all',array('fields'=>"activity_id,count(*) as activity_user",'conditions'=>array('activity_id'=>$activity_ids,'user_id >'=>0),'group'=>'activity_id', 'recursive' => -1));
    				$activity_user_list=array();
    				foreach($activity_user_totals as $v)$activity_user_list[$v['ActivityUser']['activity_id']]=$v[0]['activity_user'];
    				$result['activity_user']=$activity_user_list;
    			}
    			return $result;
	    }
}
