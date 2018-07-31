<?php

/*****************************************************************************
 * Seevia 用户报表
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
/**
 *这是一个名为 UserReportscontroller 的控制器.
 *
 *@var
 *@var
 */
class UserReportscontroller extends AppController
{
	public $name = 'UserReports';
	public $helpers = array('Pagination','Ckeditor');
	public $components = array('Pagination','RequestHandler','Email','Phpexcel');
	public $uses = array('User','UserPointLog','ShareAffiliateLog');
    	
    	function index(){
		$this->set('title_for_layout','用户报表'.' - '.$this->configs['shop_name']);
		$this->navigations[] = array('name' => $this->ld['report'],'url' => '');
		$this->navigations[] = array('name' => '用户报表','url' => '/user_reports/');
    		if(isset($_GET['date_type'])&&trim($_GET['date_type'])!=''){
    			$date_type = trim($_GET['date_type']);
    		}else{
    			$date_type="week";
    		}
    		
    		$point_conditions=array();
    		$point_conditions['User.id >']=0;
    		if (isset($this->params['url']['date_start']) && $this->params['url']['date_start'] != '') {
			$point_conditions['UserPointLog.created >=']=date('Y-m-d 00:00:00',strtotime($this->params['url']['date_start']));
		}else{
			$point_conditions['UserPointLog.created >=']=date('Y-m-01 00:00:00');
		}
		$this->set('date_start',date('Y-m-d',strtotime($point_conditions['UserPointLog.created >='])));
		if (isset($this->params['url']['date_end']) && $this->params['url']['date_end'] != '') {
			$point_conditions['UserPointLog.created <=']=date('Y-m-d 23:59:59',strtotime($this->params['url']['date_end']));
		}else{
			$BeginDate=date('Y-m-01 00:00:00');
			$point_conditions['UserPointLog.created <=']=date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day"));
		}
		$this->set('date_end',date('Y-m-d',strtotime($point_conditions['UserPointLog.created <='])));
    		$point_date="date_format(UserPointLog.created,'%y-%m') as point_date";
		if($date_type=="year"){
			$point_date="date_format(UserPointLog.created,'%y') as point_date";
		}else if($date_type=="day"){
			$point_date="date_format(UserPointLog.created,'%y-%m-%d') as point_date";
		}else if($date_type=="week"){
			$point_date="date_format(UserPointLog.created,'%v') as point_date";
		}
		$point_increase=$point_conditions;
		$point_increase['UserPointLog.point_change >']=0;
		$point_increase_log=$this->UserPointLog->find('all',array('conditions'=>$point_increase,'fields'=>"{$point_date},SUM(point_change) as point_modify_total",'group'=>'point_date','order'=>'point_date'));
		$point_increase_log_data=array();
		foreach($point_increase_log as $v){
			$date_key=$v[0]['point_date'];
			if($date_type=="week"){
				$start_week=date('W',strtotime($point_conditions['UserPointLog.created >=']));
				$date_key=$v[0]['point_date']-$start_week+1;
			}
			$point_increase_log_data[$date_key]=abs($v[0]['point_modify_total']);
		}
		$this->set('point_increase_log_data',$point_increase_log_data);
		
    		$point_use=$point_conditions;
    		$point_use['UserPointLog.point_change <']=0;
    		$point_use_log=$this->UserPointLog->find('all',array('conditions'=>$point_use,'fields'=>"{$point_date},SUM(point_change) as point_modify_total",'group'=>'point_date','order'=>'point_date'));
		$point_use_log_data=array();
		foreach($point_use_log as $v){
			$date_key=$v[0]['point_date'];
			if($date_type=="week"){
				$start_week=date('W',strtotime($point_conditions['UserPointLog.created >=']));
				$date_key=$v[0]['point_date']-$start_week+1;
			}
			$point_use_log_data[$date_key]=abs($v[0]['point_modify_total']);
		}
		$this->set('point_use_log_data',$point_use_log_data);
		
		$share_affiliate_conditions=array();
		$share_affiliate_conditions['User.id >']=0;
		if (isset($this->params['url']['date_start']) && $this->params['url']['date_start'] != '') {
			$share_affiliate_conditions['ShareAffiliateLog.created >=']=date('Y-m-d 00:00:00',strtotime($this->params['url']['date_start']));
		}else{
			$share_affiliate_conditions['ShareAffiliateLog.created >=']=date('Y-m-01 00:00:00');
		}
		if (isset($this->params['url']['date_end']) && $this->params['url']['date_end'] != '') {
			$share_affiliate_conditions['ShareAffiliateLog.created <=']=date('Y-m-d 23:59:59',strtotime($this->params['url']['date_end']));
		}else{
			$BeginDate=date('Y-m-01 00:00:00');
			$share_affiliate_conditions['ShareAffiliateLog.created <=']=date('Y-m-d 23:59:59', strtotime("$BeginDate +1 month -1 day"));
		}
		$share_date="date_format(ShareAffiliateLog.created,'%y-%m') as share_date";
		if($date_type=="year"){
			$share_date="date_format(ShareAffiliateLog.created,'%y') as share_date";
		}else if($date_type=="day"){
			$share_date="date_format(ShareAffiliateLog.created,'%y-%m-%d') as share_date";
		}else if($date_type=="week"){
			$share_date="date_format(ShareAffiliateLog.created,'%v') as share_date";
		}
		$share_affiliate_log=$this->ShareAffiliateLog->find('all',array('conditions'=>$share_affiliate_conditions,'fields'=>"{$share_date},count(*) as share_affiliate_total",'group'=>'share_date','order'=>'share_date'));
		$share_affiliate_data=array();
		foreach($share_affiliate_log as $v){
			$date_key=$v[0]['share_date'];
			if($date_type=="week"){
				$start_week=date('W',strtotime($share_affiliate_conditions['ShareAffiliateLog.created >=']));
				$date_key=$v[0]['share_date']-$start_week+1;
			}
			$share_affiliate_data[$date_key]=$v[0]['share_affiliate_total'];
		}
		$this->set('share_affiliate_data',$share_affiliate_data);
		
		$week_list=array();
		$start_week=date('W',strtotime($point_conditions['UserPointLog.created >=']));
		$end_week=date('W',strtotime($point_conditions['UserPointLog.created <=']));
		$week_start=date("Y-m-d",strtotime($point_conditions['UserPointLog.created >=']));
		$lastday=date("Y-m-d",strtotime("{$week_start} Sunday"));
		$week_start=date('Y-m-d',strtotime($lastday.' -6 days'));
		$week_list[1]=date('Y-m-d',strtotime($week_start));
		$week_key=1;
		while(true){
			if(strtotime($week_start." +".($week_key*7)." days")>strtotime($point_conditions['UserPointLog.created <=']))break;
			$week_list[$week_key+1]=date("Y-m-d",strtotime($week_start." +".($week_key*7)." days"));
			$week_key++;
		}
		$this->set('week_list',$week_list);
		$this->set('date_type',$date_type);
    	}
    	
    	function view($report_date='',$date_type='day'){
    		$this->set('title_for_layout','用户报表明细 - '.$this->configs['shop_name']);
		$this->navigations[] = array('name' => $this->ld['report'],'url' => '');
		$this->navigations[] = array('name' => '用户报表','url' => '/user_reports/');
    		$this->navigations[] = array('name' => '用户报表明细','url' => '');
    		
    		if($report_date=='')$report_date=date('Y-m-d');
    		if($date_type=='day'){
    			$start_date=date('Y-m-d 00:00:00',strtotime($report_date));
    			$end_date=date('Y-m-d 23:59:59',strtotime($report_date));
    		}else if($date_type=='week'){
    			$lastday=date("Y-m-d",strtotime("{$report_date} Sunday"));
    			$start_date=date("Y-m-d 00:00:00",strtotime("$lastday - 6 days"));
    			$end_date=date('Y-m-d 23:59:59',strtotime($lastday));
    		}else if($date_type=='month'){
    			$start_date=date('Y-m-01 00:00:00',strtotime($report_date));
			$end_date=date('Y-m-d 23:59:59', strtotime("$start_date +1 month -1 day"));
    		}else if($date_type=='year'){
    			$report_date=intval($report_date);
    			$start_date=date("$report_date-01-01 00:00:00");
			$end_date=date("$report_date-12-31 23:59:59");
    		}
    		$point_conditions=array();
    		$point_conditions['UserPointLog.user_id >']=0;
    		$point_conditions['UserPointLog.created >=']=$start_date;
    		$point_conditions['UserPointLog.created <=']=$end_date;
		$point_conditions['UserPointLog.point_change <>']=0;
		$user_point_log=$this->UserPointLog->find('all',array('conditions'=>$point_conditions,'order'=>'UserPointLog.user_id,UserPointLog.id'));
		$this->set('user_point_log',$user_point_log);
		
		$share_affiliate_conditions=array();
		$share_affiliate_conditions['User.id >']=0;
		$share_affiliate_conditions['ShareAffiliateLog.created >=']=$start_date;
    		$share_affiliate_conditions['ShareAffiliateLog.created <=']=$end_date;
    		$share_affiliate_log=$this->ShareAffiliateLog->find('all',array('conditions'=>$share_affiliate_conditions,'order'=>'ShareAffiliateLog.user_id,ShareAffiliateLog.id'));
    		$this->set('share_affiliate_log',$share_affiliate_log);
    	}
}
