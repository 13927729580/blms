<?php

/**
 * 	AbilityLevel 技能等级
 */
class AbilityLevel extends AppModel{
	    /*
	     * @var $useDbConfig 数据库配置
	     */
	    public $useDbConfig = 'hr';
	    
	    public $belongsTo = array(
		        'Ability' => array(
			        'className' => 'Ability',
			        'conditions' => 'AbilityLevel.ability_code=Ability.code',
			        'order' => '',
			    	 'foreignKey'=>'',
			        'dependent' => false
		        )
	    );
}