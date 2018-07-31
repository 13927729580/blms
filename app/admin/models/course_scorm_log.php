<?php
/*****************************************************************************
 * svhr  Scorm课件记录
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
class CourseScormLog extends AppModel{
	/*
	* @var $useDbConfig 数据库配置
	*/
	public $useDbConfig = 'hr';
	public $name = 'CourseScormLog';
	
	function scorm_interaction($ware_id=0,$user_id=0){
		$scorm_score_detail=array();
		$scorm_cond=array(
			'CourseScormLog.course_ware_id <>'=>0,
			'CourseScormLog.user_id <>'=>0,
			'CourseScormLog.course_ware_id'=>$ware_id,
			'CourseScormLog.user_id'=>$user_id,
			'CourseScormLog.scorm_data like'=>"%cmi_interactions_%"
		);
        	$course_scorm_list=$this->find('list',array('fields'=>'id,scorm_data','conditions'=>$scorm_cond,'order'=>'CourseScormLog.modified,CourseScormLog.id'));
        	if(!empty($course_scorm_list)){
        		$scorm_score_infos=array();
        		foreach($course_scorm_list as $k=>$v){
        			$scorm_data=json_decode($v,true);
        			$score_detail=array();
        			foreach($scorm_data as $kk=>$vv){
        				$interaction_reg="/cmi_interactions_(?P<value>(\d+))_(?P<field_key>(\w+)).*/i";
        				preg_match($interaction_reg, $kk, $field_matches);
        				if(!empty($field_matches)&&isset($field_matches['value'])&&isset($field_matches['field_key'])){
        					$score_key=intval($field_matches['value']);
        					$field_key=trim($field_matches['field_key']);
        					$score_detail[$score_key][$field_key]=$vv;
        					//$score_detail[$score_key]['scorm_log_id']=$k;
        				}
        			}
        			$scorm_score_infos=array_merge($scorm_score_infos,$score_detail);
        		}
    			foreach($scorm_score_infos as $v){
    				$interaction_id=isset($v['id'])?$v['id']:'0';
    				$scorm_score_detail[$interaction_id]=$v;
    			}
    			$scorm_score_detail=array_values($scorm_score_detail);
        	}
        	return $scorm_score_detail;
	}
}
