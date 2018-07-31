<?php

/**
 * 	EvaluationCategory 评测分类
 */
class EvaluationCategory extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
    function evaluation_category_list(){
    		$conditions=array();
    		$conditions['EvaluationCategory.status']='1';
            $conditions['or'][]=array('EvaluationCategory.user_id'=>0);
            if(isset($_SESSION['User']['User']['id'])){
                $conditions['or'][]=array('EvaluationCategory.user_id'=>$_SESSION['User']['User']['id']);
            }
    		$evaluation_categorys=$this->find('all',array('conditions'=>$conditions,'order'=>"EvaluationCategory.name"));
    		return $evaluation_categorys;
    }
}
