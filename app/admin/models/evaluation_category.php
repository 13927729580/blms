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
        $evaluation_categorys=$this->find('all',array('conditions'=>$conditions,'order'=>"EvaluationCategory.name"));
        return $evaluation_categorys;
    }
}