<?php

/*****************************************************************************
 * svsys 操作员操作权限模型
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
class Action extends AppModel
{
    /*
    * @var $useDbConfig 数据库配置
    */
    public $useDbConfig = 'default';

    public $name = 'Action';
    /*
     * @var $hasOne array 关联分类多语言表
     */
    public $hasOne = array('ActionI18n' => array('className' => 'ActionI18n',
        'conditions' => '',
        'order' => 'Action.id',
        'dependent' => true,
        'foreignKey' => 'action_id',
    ),
    );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = " ActionI18n.locale = '".$locale."'";
        $this->hasOne['ActionI18n']['conditions'] = $conditions;
    }

    public function localeformat($id)
    {
        $node['config'] = 'node';
        $node['use'] = true;
        $lists = $this->find('all', array('cache' => $node, 'conditions' => array('Action.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['Action'] = $v['Action'];
            $lists_formated['ActionI18n'][] = $v['ActionI18n'];
            foreach ($lists_formated['ActionI18n'] as $key => $val) {
                $lists_formated['ActionI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }

    public $acionts_parent_format = array();
    public function alltree_hasname()
    {
        $conditions['Action.status'] = 1;
        $actions = $this->find('all', array('conditions' => $conditions, 'order' => 'orderby asc'));
        $this->acionts_parent_format = array();
        if (is_array($actions)) {
            foreach ($actions as $k => $v) {
                $v['Action']['name'] = $v['ActionI18n']['name'];
                $this->acionts_parent_format[$v['Action']['parent_id']][] = $v;
            }
        }

        return $this->subcat_get(0);
    }
    /*
    *获取权限结构树
    */
    public function tree($locale = 'chi',$systemmodules=array())
    {
        $this->acionts_parent_format = array();
        $this->set_locale($locale);
        $conditions['ActionI18n.locale'] = $locale;
        $conditions['Action.status'] = '1';
        $cond['conditions'] = $conditions;

        $cond['order'] = array('Action.orderby asc,Action.created asc');
        $action_list = $this->find('all', $cond);

        if (is_array($action_list)) {
            foreach ($action_list as $k => $v) {
                if(!empty($systemmodules)){
                    if($v['Action']['module_code'] == '' && $v['Action']['system_code']!=''){
                        if(!isset($systemmodules[$v['Action']['system_code']])){
                            continue;
                        }
                    }elseif($v['Action']['module_code'] != '' && $v['Action']['system_code']!=''){
                        if(!isset($systemmodules[$v['Action']['system_code']]['modules'][$v['Action']['module_code']]['status'])){
                            continue;
                        }
                    }
                }
                $this->acionts_parent_format[$v['Action']['parent_id']][] = $v;
            }
        }
        return $this->subcat_get(0);
    }
    public function alltree($conditions = ''){
        $actions = $this->find('all', array('conditions' => $conditions, 'order' => 'orderby asc'));
        $this->acionts_parent_format = array();
        if (is_array($actions)) {
            foreach ($actions as $k => $v) {
                $this->acionts_parent_format[$v['Action']['parent_id']][] = $v;
            }
        }

        return $this->subcat_get(0);
    }

    public function subcat_get($action_id)
    {
        $subcat = array();
        if (isset($this->acionts_parent_format[$action_id]) && is_array($this->acionts_parent_format[$action_id])) {
            //判断parent_id = 0 的数据
            foreach ($this->acionts_parent_format[$action_id] as $k => $v) {
                $action = $v;//parent_id 为 0 的数据
                if (isset($this->acionts_parent_format[$v['Action']['id']]) && is_array($this->acionts_parent_format[$v['Action']['id']])) {
                    $action['SubAction'] = $this->subcat_get($v['Action']['id']);
                } else {
                }
                $subcat[$k] = $action;
            }
        }

        return $subcat;
    }

    public function get_all_user($code)
    {
    		$OperatorList=array();
	        $action_data=$this->find('first', array('fields'=>'Action.id,Action.code,ActionI18n.name','conditions' => array("code"=>$code,'status'=>'1')));
	        if(!empty($action_data)){
		        $action_id=$action_data['Action']['id'];
		        $Role = ClassRegistry::init('Role');
		        $role_list=$Role->find('list',array('fields'=>'Role.id','conditions'=>array('actions like'=>"%{$action_id}%")));
		        
		        $Operator = ClassRegistry::init('Operator');
		        $condition=array();
		        $condition['or']['Operator.actions'] = 'all';
		        $condition['or']['Operator.actions like'] = '%;'.$action_id.';%';
		        if(!empty($role_list)){
		        	foreach($role_list as $v){
		        		$condition['or'][]['Operator.role_id like'] = "%;{$v};%";
		        	}
		        }
		        $OperatorList=$Operator->find('all',array('fields'=>'Operator.id,Operator.name','conditions'=>$condition));
	        }
	        return $OperatorList;
    }
}
