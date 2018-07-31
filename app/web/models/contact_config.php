<?php

/*****************************************************************************
 * svoms  ��ϵ��������ģ��
 * ===========================================================================
 * ��Ȩ����  �Ϻ�ʵ������Ƽ����޹�˾������������Ȩ����
 * ��վ��ַ: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * �ⲻ��һ�������������ֻ���ڲ�������ҵĿ�ĵ�ǰ���¶Գ����������޸ĺ�ʹ�ã�
 * ������Գ���������κ���ʽ�κ�Ŀ�ĵ��ٷ�����
 * ===========================================================================
 * $����: �Ϻ�ʵ��$
 * $Id$
*****************************************************************************/
class ContactConfig extends AppModel
{
    /*
     * @var $useDbConfig ���ݿ�����
     */
    public $useDbConfig = 'cms';
    /*
     * @var $name UserConfig �û�����
     */
    public $name = 'ContactConfig';

    /*
     * @var $hasOne array ������������Ա�
     */
    public $hasOne = array('ContactConfigI18n' => array('className' => 'ContactConfigI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => 'ContactConfig.orderby asc',
            'dependent' => true,
            'foreignKey' => 'contact_config_id',
        ),
    );
}