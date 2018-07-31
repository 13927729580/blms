<?php
class Profile extends AppModel
{
    /*
    * @var $useDbConfig 数据库配置
    */
    public $useDbConfig = 'default';
    /*
     * @var $name Profile 
     */
    public $name = 'Profile';
    
    public $hasOne = array(
                    'ProfileI18n' => array('className' => 'ProfileI18n',
                              'conditions' => array('locale' => LOCALE),
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'profile_id',
                        ),
                  );
}
