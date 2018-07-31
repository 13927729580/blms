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
            'conditions' => '',
            'order' => 'ContactConfig.orderby asc',
            'dependent' => true,
            'foreignKey' => 'contact_config_id',
        ),
    );

    /**
     * set_locale�������������Ի���.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions ['ContactConfigI18n.locale'] = $locale;
        $this->locale = $locale;
        $this->hasOne['ContactConfigI18n']['conditions'] = $conditions;
    }

    /**
     * localeformat�������ṹ����.
     *
     * @param int $id �������±��
     *
     * @return array $lists_formated ����Ʒ���������Ե���Ϣ
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('ContactConfig.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['ContactConfig'] = $v['ContactConfig'];
            $lists_formated['ContactConfigI18n'][] = $v['ContactConfigI18n'];
            foreach ($lists_formated['ContactConfigI18n'] as $key => $val) {
                $lists_formated['ContactConfigI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }
}
