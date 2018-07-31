<?php

/*****************************************************************************
 * svsys 优惠卷
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
class CouponType extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'CouponType';
    public $hasOne = array('CouponTypeI18n' => array('className' => 'CouponTypeI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'coupon_type_id',
                        ),
                  );

    public function set_locale($locale)
    {
        $conditions = " CouponTypeI18n.locale = '".$locale."'";
        $this->hasOne['CouponTypeI18n']['conditions'] = $conditions;
    }

    //数组结构调整
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => "CouponType.id = '".$id."'"));
        $lists_formated = array();
        //pr($lists);
        foreach ($lists as $k => $v) {
            $lists_formated['CouponType'] = $v['CouponType'];
            $lists_formated['CouponTypeI18n'][] = $v['CouponTypeI18n'];
            foreach ($lists_formated['CouponTypeI18n'] as $key => $val) {
                $lists_formated['CouponTypeI18n'][$val['locale']] = $val;
            }
        }
        //pr($lists_formated);
        return $lists_formated;
    }
    //获取name
    public function getCouponName($id)
    {
        $lists = $this->find('all', array('conditions' => array('CouponType.id' => $id), 'fields' => 'CouponType.id,CouponTypeI18n.name'));
        $lists_formated = array();
        if (!empty($lists)) {
            foreach ($lists as $l) {
                $lists_formated[$l['CouponType']['id']] = $l['CouponTypeI18n']['name'];
            }
        }

        return $lists_formated;
    }
}
