<?php

/*****************************************************************************
 * svsys 系统
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
class System extends AppModel
{
    /*
    * @var $useDbConfig 数据库配置
    */
    public $useDbConfig = 'default';
    public $name = 'System';
    public $modules = array();

    //角色数组结构调整
    public function modules()
    {

    			$systems= $this->find('all',array(
                        'fields' => array('System.code', 'System.version'),
                        'conditions' => array('System.status' => '1')
                    )
                );
    		//pr($systems);
    	        $SystemModule = ClassRegistry::init('SystemModule');
    	        $modules = $SystemModule->find('all',array(
                        'fields' => array('SystemModule.code', 'SystemModule.system_code', 'SystemModule.status'),
                        'conditions' => array('SystemModule.status' => '1')
                    )
                );
                $systems_format=array();
                if(is_array($systems)){
	                foreach ($systems as $k => $v) {
	                	$systems_format[$v['System']['code']]= $v['System'];
	                }
	                
	                if(is_array($modules))
	                foreach ($modules as $k => $v) {
	                	if(isset($systems_format[$v['SystemModule']['system_code']])){
	                		$systems_format[$v['SystemModule']['system_code']]['modules'][$v['SystemModule']['code']]= $v['SystemModule'];
	                	}
	                }
	            }
		$this->modules=$systems_format;
        return $systems_format;
    }
    
    public function checkmodule($system,$module=''){
    	    if($module ==''){
    	    		return isset($this->modules[$system]['code']);
    	    }else{
    	    		return isset($this->modules[$system]['modules'][$module]['status']);
    	    }
    	
    }
    
}
