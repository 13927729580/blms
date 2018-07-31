<?php

/*****************************************************************************
 * svoms  发票模型
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
class InvoiceType extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name InvoiceType 发票类型
     */
    public $name = 'InvoiceType';

    /*
     * @var $hasOne array 关联分类多语言表
     */
    public $hasOne = array('InvoiceTypeI18n' => array('className' => 'InvoiceTypeI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'invoice_type_id',
                        ),
                    );

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = " InvoiceTypeI18n.locale = '".$locale."'";
        $this->hasOne['InvoiceTypeI18n']['conditions'] = $conditions;
    }

    /**
     * localeformat方法，数组结构调整.
     *
     * @param int $id 输入分类编号
     *
     * @return array $lists_formated 返回发票所有语言的信息
     */
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('InvoiceType.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['InvoiceType'] = $v['InvoiceType'];
            $lists_formated['InvoiceTypeI18n'][] = $v['InvoiceTypeI18n'];
            foreach ($lists_formated['InvoiceTypeI18n'] as $key => $val) {
                $lists_formated['InvoiceTypeI18n'][$val['locale']] = $val;
            }
        }

        return $lists_formated;
    }

    /**
     * invoice_type_list方法，可以发票列表.
     *
     * @param string $locale 输入分类编号
     *
     * @return array $invoice_type_list 返回可以发票列表
     */
    public function invoice_type_list($locale)
    {
        $fields = array('InvoiceType.id','InvoiceType.tax_point','InvoiceTypeI18n.name');
        $invoice_type_list = $this->find('all', array('conditions' => array('InvoiceType.status' => 1, 'InvoiceTypeI18n.locale' => $locale), 'fields' => $fields));

        return $invoice_type_list;
    }
}
