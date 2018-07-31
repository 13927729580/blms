<?php

/*****************************************************************************
 * svoh 用户卡牌
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
class UserCard extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oh';
    public $name = 'UserCard';
    
    function last_card_detail($user_id=0,$ohcard_rule_id=0){
    		$result=array();
    		$user_card_info=$this->find('first',array(
    			'fields'=>array(
    				'UserCard.id','UserCard.user_id','UserCard.ohcard_rule_id','UserCard.created','UserCard.modified'
    			),
    			'conditions'=>array(
    				'UserCard.user_id'=>$user_id,
    				'UserCard.ohcard_rule_id'=>$ohcard_rule_id,
    			),
    			'order'=>'UserCard.created desc,UserCard.id desc'
    		));
    		if(!empty($user_card_info)){
    			$result=$user_card_info;
    			$result['UserCard']['UserCardDetail']=array();
    			$OhcardRuleConfig=ClassRegistry::init('OhcardRuleConfig');
    			$rule_config_cond=array(
				'OhcardRuleConfig.ohcard_rule_id'=>$ohcard_rule_id,
				'OhcardRuleConfig.status'=>'1',
				'UserCardDetail.user_card_id'=>$user_card_info['UserCard']['id']
			);
			$joins=array(
	                	array(
					'table' => 'svoh_user_card_details',
					'alias' => 'UserCardDetail',
					'type' => 'left',
					'conditions' => array('UserCardDetail.ohcard_rule_config_id = OhcardRuleConfig.id')
	                     )
	        	);
	        $ohcard_rule_config=$OhcardRuleConfig->find('all',array('fields'=>array('UserCardDetail.id','UserCardDetail.ohcard_rule_config_id','UserCardDetail.ohcard_id','UserCardDetail.message'),'conditions'=>$rule_config_cond,'joins'=>$joins,'order'=>'OhcardRuleConfig.orderby,OhcardRuleConfig.id'));
	        	
	        	if(!empty($ohcard_rule_config)){
	        		foreach($ohcard_rule_config as $v){
	        			$result['UserCard']['UserCardDetail'][]=$v['UserCardDetail'];
	        		}
  		      }
    		}
    		return $result;
    }
}
