<?php

/**
 * 	EvaluationRule 评测题目组合规则
 */
class EvaluationRule extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
    /*
    		评测列表
    */
    function evaluation_rule_list($evaluation_code=''){
    		$evaluation_rule_data=array();
    		$conditions=array();
    		$conditions['EvaluationRule.evaluation_code']=$evaluation_code;
		$evaluation_rule_info=$this->find('all',array('conditions'=>$conditions,'order'=>'EvaluationRule.question_type,EvaluationRule.orderby,EvaluationRule.id'));
		if(!empty($evaluation_rule_info)){
			$evaluation_codes=array();
			foreach($evaluation_rule_info as $v){
				$evaluation_codes[]=$v['EvaluationRule']['child_evaluation_code'];
			}
			if(!in_array($evaluation_code,$evaluation_codes)){
				$evaluation_codes[]=$evaluation_code;
			}
			$Evaluation = ClassRegistry::init('Evaluation');
			$evaluation_datas=$Evaluation->find('list',array('conditions'=>array('Evaluation.code'=>$evaluation_codes),'fields'=>"Evaluation.code,Evaluation.name"));
			$evaluation_question_datas=array();
			$EvaluationQuestion = ClassRegistry::init('EvaluationQuestion');
			$qustion_cond=array();
			$qustion_cond['EvaluationQuestion.status']='1';
			$qustion_cond['EvaluationQuestion.evaluation_code']=$evaluation_codes;
			$evaluation_question_infos=$EvaluationQuestion->find('all',array('conditions'=>$qustion_cond,'fields'=>"EvaluationQuestion.evaluation_code,EvaluationQuestion.question_type,count(*) as question_total",'group'=>'EvaluationQuestion.evaluation_code,EvaluationQuestion.question_type'));
			foreach($evaluation_question_infos as $v){
				$evaluation_question_datas[$v['EvaluationQuestion']['evaluation_code']][$v['EvaluationQuestion']['question_type']]=$v[0]['question_total'];
			}
			foreach($evaluation_rule_info as $k=>$v){
				$child_evaluation_code=$v['EvaluationRule']['child_evaluation_code'];
				$evaluation_question_type=$v['EvaluationRule']['question_type'];
				if($child_evaluation_code!=''){
					$evaluation_rule_info[$k]['Evaluation']=isset($evaluation_datas[$child_evaluation_code])?$evaluation_datas[$child_evaluation_code]:'';
				}
				if(isset($evaluation_question_datas[$child_evaluation_code][$evaluation_question_type])){
					$max_question_total=$evaluation_question_datas[$child_evaluation_code][$evaluation_question_type];
					if($max_question_total<$v['EvaluationRule']['proportion']){
						$evaluation_rule_info[$k]['EvaluationRule']['proportion']=$max_question_total;
					}
				}else{
					$evaluation_rule_info[$k]['EvaluationRule']['proportion']=0;
				}
			}
			if(in_array($evaluation_code,$evaluation_codes)&&isset($evaluation_question_datas[$evaluation_code])){
				foreach($evaluation_question_datas[$evaluation_code] as $k=>$v){
					$evaluation_rule_info[]=array(
						'EvaluationRule'=>array(
							'evaluation_code'=>$evaluation_code,
							'child_evaluation_code'=>$evaluation_code,
							'question_type'=>$k,
							'proportion'=>$v,
							'score'=>0
						),
						'Evaluation'=>isset($evaluation_datas[$evaluation_code])?$evaluation_datas[$evaluation_code]:''
					);
				}
			}
			$score_total=0;$question_total=0;
			foreach($evaluation_rule_info as $k=>$v){
				if($v['EvaluationRule']['proportion']>0){
					$question_total+=$v['EvaluationRule']['proportion'];
				}else{
					unset($evaluation_rule_info[$k]);
					continue;
				}
				$evaluation_rule_data[]=$v;
			}
			foreach($evaluation_rule_data as $k=>$v){
				if($k==(sizeof($evaluation_question_infos)-1)){
					$question_score=100-$score_total;
				}else{
				$score_info=isset($evaluation_question_datas[$v['EvaluationRule']['child_evaluation_code']][$v['EvaluationRule']['question_type']])?$evaluation_question_datas[$v['EvaluationRule']['child_evaluation_code']][$v['EvaluationRule']['question_type']]:$v['EvaluationRule']['score'];
					$question_score=($score_info/$question_total)*100;
				}
				$score_total+=$question_score;
				$evaluation_rule_data[$k]['EvaluationRule']['score']=$question_score;
			}
		}else{
			$EvaluationQuestion = ClassRegistry::init('EvaluationQuestion');
			$qustion_cond=array();
			$qustion_cond['EvaluationQuestion.status']='1';
			$qustion_cond['EvaluationQuestion.evaluation_code']=$evaluation_code;
			$evaluation_question_infos=$EvaluationQuestion->find('all',array('conditions'=>$qustion_cond,'fields'=>"EvaluationQuestion.evaluation_code,EvaluationQuestion.question_type,count(*) as question_total",'group'=>'EvaluationQuestion.evaluation_code,EvaluationQuestion.question_type'));
			if(!empty($evaluation_question_infos)){
				$Evaluation = ClassRegistry::init('Evaluation');
				$evaluation_datas=$Evaluation->find('list',array('conditions'=>array('Evaluation.code'=>$evaluation_code),'fields'=>"Evaluation.code,Evaluation.name"));
				
				$score_total=0;$question_total=0;
				foreach($evaluation_question_infos as $v)$question_total+=$v['0']['question_total'];
				foreach($evaluation_question_infos as $k=>$v){
					if($k==(sizeof($evaluation_question_infos)-1)){
						$question_score=100-$score_total;
					}else{
						$question_score=($v['0']['question_total']/$question_total)*100;
					}
					$score_total+=$question_score;
					$evaluation_rule_data[]=array(
						'EvaluationRule'=>array(
							'evaluation_code'=>$evaluation_code,
							'child_evaluation_code'=>$evaluation_code,
							'question_type'=>$v['EvaluationQuestion']['question_type'],
							'proportion'=>$v['0']['question_total'],
							'score'=>1
						),
						'Evaluation'=>isset($evaluation_datas[$evaluation_code])?$evaluation_datas[$evaluation_code]:''
					);
				}
			}
		}
		return $evaluation_rule_data;
    }
}