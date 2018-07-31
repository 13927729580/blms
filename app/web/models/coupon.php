<?php

/**
 * 优惠券模型.
 */
class coupon extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    public $name = 'Coupon';
    /*
    var $hasOne = array('CouponType' =>
        array('className' => 'CouponType',
            'conditions' => '',
            'order' => 'CouponType.id',
            'dependent' => true,
            'foreignKey' => 'id'
        )
    );
    */

    public function find_coupon_arr_list()
    {
        $coupon_arr_list = $this->find('list', array('conditions' => array('1=1'), 'fields' => array('Coupon.sn_code')));

        return $coupon_arr_list;
    }

    public function find_coupons($id)
    {
        $coupons = $this->findall('Coupon.user_id ='.$id." and Coupon.order_id = '0'");

        return $coupons;
    }

    public function get_coupons($user_id, $rownum, $page)
    {
        $coupons = $this->findall('Coupon.user_id = '.$user_id, '', 'Coupon.created DESC', "$rownum", $page);

        return $coupons;
    }

    public function get_coupons_order($user_id, $rownum, $page)
    {
        $coupons = $this->findall('Coupon.user_id = '.$user_id." and Coupon.order_id > '0'", '', 'Coupon.created DESC', "$rownum", $page);

        return $coupons;
    }

    public function get_coupons_infos($user_id, $coupon_sn, $rownum, $page)
    {
        $coupons = $this->findall('Coupon.user_id = '.$user_id." and Coupon.order_id > '0' and Coupon.sn_code =".$coupon_sn, '', 'Coupon.created DESC', "$rownum", $page);

        return $coupons;
    }
}
