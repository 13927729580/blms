<?php
/**
 * 	CourseCategory 课程分类
 */
class CourseCategory extends AppModel{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'hr';
    
	public function getTree ($parents,$pId)
    {
        $tree = array();
        foreach($parents as $k => $v)
        {
            if($v['CourseCategory']['parent_id'] == $pId)
            {
                $v['CourseCategory']['parent_id'] = $this->getTree($parents, $v['CourseCategory']['id']);
                $tree[] = $v['CourseCategory'];
            }
        }
        return $tree;
    }
}