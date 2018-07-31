<?php

/*****************************************************************************
 * svoms  分享记录访问信息表
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
class ShareAffiliateLog extends AppModel
{
	/*
	 * @var $useDbConfig 数据库配置
	 */
	public $useDbConfig = 'oms';

	/*
	* @var $name 分享记录访问信息表
	*/
    	public $name = 'ShareAffiliateLog';
    	
    	public $belongsTo = array(
    			'User' => array(
                            'className' => 'User',
                            'conditions' => 'User.id=ShareAffiliateLog.user_id',
            			'fields'=>'User.id,User.name,User.first_name,User.last_name,User.email,User.mobile,User.img01',
                            'order' => '',
                            'dependent' => true,
                            'foreignKey' => ''
                        ),'VisitorUser' => array(
                            'className' => 'User',
                            'conditions' => 'VisitorUser.id=ShareAffiliateLog.visitors_user_id',
            			'fields'=>'VisitorUser.id,VisitorUser.name,VisitorUser.first_name,VisitorUser.last_name,VisitorUser.email,VisitorUser.mobile,VisitorUser.img01',
                            'order' => '',
                            'dependent' => true,
                            'foreignKey' => ''
                        )
                );
}