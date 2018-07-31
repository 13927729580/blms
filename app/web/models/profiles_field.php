<?php

class ProfilesField extends AppModel
{
    /*
    * @var $useDbConfig ���ݿ�����
    */
    public $useDbConfig = 'default';
    public $name = 'ProfilesField';
    public $hasOne = array(
                    'ProfilesFieldI18n' => array(
                        'className' => 'ProfilesFieldI18n',
        		   'conditions' => array('locale' => LOCALE),
                        'order' => '',
                        'dependent' => true,
                        'foreignKey' => 'profiles_field_id',
                    ),
                 );
}
