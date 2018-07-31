<?php

/**
 * 操作员菜单模型.
 */
class Menu extends AppModel
{
    /*
    * @var $useDbConfig 数据库配置
    */
    public $useDbConfig = 'default';

    /*
     * @var $name Menu 操作员菜单
     */
    public $name = 'Menu';

    /*
     * @var $name actions_parent_format 制作树用的
     */
    public $actions_parent_format = array();

    /*
     * @var $hasOne array 关联分类多语言表
     */
    public $hasOne = array('MenuI18n' => array('className' => 'MenuI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'menu_id',
                        ),
                    );

    /*
     * @var $name cache_config 缓存用
     */
    public $cache_config = 'day';

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = " MenuI18n.locale = '".$locale."'";
        $this->hasOne['MenuI18n']['conditions'] = $conditions;
    }
    /**
     * tree方法，菜单树.
     *
     * @param string $actions 权限字符串
     * @param string $locale  输入语言
     *
     * @return array $this->allinfo[$type] 返回所有的输入值
     */
    public function tree($actions = 'all', $locale, $systemmodules, $action_codes)
    {
        $menu_formatcode = array();
        if ($actions != 'all' && $actions != 'tree') {
            $conditions['or']['Menu.level <'] = $actions;
        } elseif ($actions == 'all') {
            $conditions['or']['Menu.status'] = '1';
        }
        $conditions['and']['Menu.action_code <>'] = null;

        if ($action_codes == 'all' && isset($_SESSION['dev']) && $_SESSION['dev'] == 1) {
        	$systemmodules='all';
        } elseif ($action_codes == 'all') {
            $conditions['and']['Menu.action_code <>'] = $action_codes;
        }
        $menus_arr = $this->find('all', array(
            'conditions' => array($conditions),
            'fields' => array('Menu.action_code,Menu.system_code,Menu.module_code,Menu.link,Menu.orderby,Menu.parent_id,Menu.id,Menu.level,MenuI18n.name,Menu.status'),
            'order' => array('orderby asc'), )
        );
        //pr($actions_arr);
        $this->menus_parent_format = array();//先致空
        if (is_array($menus_arr)) {
            foreach ($menus_arr as $k => $v) {
                //echo 	$v['MenuI18n']['name'];
		   //判断模块
		   if($systemmodules !='all'&&!(isset($_SESSION['dev'])&&$_SESSION['dev'] == 1)){ 
			   if($v['Menu']['module_code'] == '' && $v['Menu']['system_code']!=''){
			   	   if(!isset($systemmodules[$v['Menu']['system_code']])){
			   	   	   continue;
			   	   }
			   }elseif($v['Menu']['module_code'] != '' && $v['Menu']['system_code']!=''){
			   	   if(!isset($systemmodules[$v['Menu']['system_code']]['modules'][$v['Menu']['module_code']]['status'])){
			   	   	   continue;
			   	   }
			   }else{
			   	   continue;
			   }
		   }
		   
		   
                //判断权限
                if ($action_codes != 'all') {
                    if ($v['Menu']['action_code'] != null && !in_array($v['Menu']['action_code'], $action_codes)) {
                        continue;
                    }
                }

                $v['Menu']['name'] = $v['MenuI18n']['name'];
                $this->menus_parent_format[$v['Menu']['parent_id']][] = $v;
            }
        }

        return $this->subcat_get('0');
    }

    /**
     * subcat_get方法，获得subcat.
     *
     * @param int $category_id 输入id
     *
     * @return array $subcat 根据id检索相对应的数据并返回
     */
    public function subcat_get($action_id)
    {
        $subcat = array();
        if (isset($this->menus_parent_format[$action_id]) && is_array($this->menus_parent_format[$action_id])) {
            foreach ($this->menus_parent_format[$action_id] as $k => $v) {
                $action = $v;
                if (isset($this->menus_parent_format[$v['Menu']['id']]) && is_array($this->menus_parent_format[$v['Menu']['id']])) {
                    $action['SubMenu'] = $this->subcat_get($v['Menu']['id']);
                } else {
                    $action['SubMenu'] = '';
                }
                $subcat[$k] = $action;
            }
        }

        return $subcat;
    }
    /**
     * localeformat方法，数组结构调整.
     *
     * @param string $id 输入菜单编号
     *
     * @return $lists_formated 返回菜单所有语言的信息
     */
    public function localeformat($id)
    {
        $this->hasOne['MenuI18n']['conditions'] = '';
        $lists = $this->find('all', array('conditions' => array('Menu.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['Menu'] = $v['Menu'];
            $lists_formated['MenuI18n'][] = $v['MenuI18n'];
            foreach ($lists_formated['MenuI18n'] as $key => $val) {
                $lists_formated['MenuI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }
    
    function ListTree($cond=array()){
    		$menus_arr = $this->find('all', array(
	            'conditions' => $cond,
	            'fields' => array('Menu.action_code,Menu.system_code,Menu.module_code,Menu.link,Menu.orderby,Menu.parent_id,Menu.id,Menu.level,MenuI18n.name,Menu.status'),
	            'order' => array('orderby asc'), )
	       );
	       $this->menus_parent_format = array();//先致空
	       if (is_array($menus_arr)) {
	            foreach ($menus_arr as $k => $v) {
	                $v['Menu']['name'] = $v['MenuI18n']['name'];
	                $this->menus_parent_format[$v['Menu']['parent_id']][] = $v;
	            }
	       }
	       return $this->subcat_get('0');
    }
}
