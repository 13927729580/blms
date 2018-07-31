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
            $Evaluation = ClassRegistry::init('Evaluation');
            $evaluation_datas=$Evaluation->find('list',array('conditions'=>array('Evaluation.code'=>$evaluation_codes,'Evaluation.status'=>'1'),'fields'=>"Evaluation.code,Evaluation.name"));
            $evaluation_question_datas=array();
            $EvaluationQuestion = ClassRegistry::init('EvaluationQuestion');
            $qustion_cond=array();
            //$qustion_cond['EvaluationQuestion.status']='1';
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
            $evaluation_rule_data=$evaluation_rule_info;
        }
        return $evaluation_rule_data;
    }
}