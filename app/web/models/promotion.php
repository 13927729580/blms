<?php

/**
 * 促销模型.
 */
class promotion extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name 用来解决PHP4中的一些奇怪的类名
     * @var $hasOne 设置模型关联
     */
    public $name = 'Promotion';
    public $hasOne = array('PromotionI18n' => array('className' => 'PromotionI18n',
            'conditions' => array('locale' => LOCALE),
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'promotion_id',
        ),
    );
    /**
     *函数set_locale,设置促销商品语言.
     *
     *@param $locale 促销商品语言
     *@param $conditions 促销商品信息
     */
    public function set_locale($locale)
    {
        $conditions = " PromotionI18n.locale = '".$locale."'";
        $this->hasOne['PromotionI18n']['conditions'] = $conditions;
    }

    public function find_promotions($conditions)
    {
        $promotions = $this->find('all', array(
        'fields' => array('Promotion.id', 'Promotion.type', 'Promotion.type_ext', 'Promotion.start_time', 'Promotion.end_time',
            'Promotion.min_amount', 'Promotion.max_amount', 'Promotion.user_rank', 'Promotion.point_multiples',
            'PromotionI18n.title', 'PromotionI18n.meta_keywords', 'PromotionI18n.meta_description', ),
            'conditions' => array($conditions),
            'order' => array('Promotion.orderby asc'), ));

        return $promotions;
    }
    public function get_one_day_promotions($filter_conditions)
    {
        $one_day_promotions = $this->find('all', array('conditions' => $filter_conditions,
                    'fields' => array('Promotion.id'), ));

        return $one_day_promotions;
    }

    public function get_promotions($orderby, $promotion_conditions, $rownum, $page)
    {
        $promotions = $this->find('all', array('conditions' => $promotion_conditions,
                    'fields' => array('Promotion.id', 'Promotion.start_time', 'Promotion.end_time', 'PromotionI18n.title', 'PromotionI18n.short_desc'),
                    'order' => "Promotion.$orderby asc",
                    'limit' => $rownum,
                    'page' => $page, ));

        return $promotions;
    }

    public function get_all_promotions($locale)
    {
        $promotions = $this->find('all', array('conditions' => array('Promotion.status' => 1),
                    'order' => 'Promotion.created DESC',
                    'fields' => array('Promotion.id', 'PromotionI18n.title'), ),
                        'all_promotions_'.$locale);

        return $promotions;
    }

    //模块相关
    public function get_module_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        $conditions['Promotion.status'] = 1;
        $conditions = array('Promotion.start_time <=' => DateTime,'Promotion.end_time >=' => DateTime);
        $module_promotion_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'fields' => '', 'order' => 'Promotion.'.$order));

        return $module_promotion_infos;
    }
}
