<?php

/*****************************************************************************
 * svsys 促销
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
class promotion extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'Promotion';
    public $hasOne = array('PromotionI18n' => array('className' => 'PromotionI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'promotion_id',
                        ),
                  );

    public function set_locale($locale)
    {
        $conditions = " PromotionI18n.locale = '".$locale."'";
        $this->hasOne['PromotionI18n']['conditions'] = $conditions;
    }

    //数组结构调整
    public function localeformat($id)
    {
        $lists_formated = array();
        $lists = $this->find('all', array('conditions' => "Promotion.id = '".$id."'"));
        //pr($lists);
        foreach ($lists as $k => $v) {
            $lists_formated['Promotion'] = $v['Promotion'];
            $lists_formated['PromotionI18n'][] = $v['PromotionI18n'];
            foreach ($lists_formated['PromotionI18n'] as $key => $val) {
                $lists_formated['PromotionI18n'][$val['locale']] = $val;
            }
        }
    //	pr($lists_formated);
        return $lists_formated;
    }
}
