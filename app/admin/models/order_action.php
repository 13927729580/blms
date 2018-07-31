<?php

/*****************************************************************************
 * svoms  订单操作模型
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
class OrderAction extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name OrderAction  订单操作计录
     */
    public $name = 'OrderAction';

    /**
     * order_action_list方法，，订单操作计录列表.
     *
     * @param int $order_id 订单号
     *
     * @return array $order_action_list 返回订单操作计录列表
     */
    public function order_action_list($order_id)
    {
        $fields = array('OrderAction.from_operator_id','OrderAction.order_status','OrderAction.shipping_status','OrderAction.payment_status','OrderAction.action_note','OrderAction.created');
        $order_action_list = $this->find('all', array('conditions' => array('OrderAction.order_id' => $order_id), 'order' => 'OrderAction.id desc', 'fields' => $fields));

        return $order_action_list;
    }

    /**
     * update_order_actions方法，，新增订单操作日志.
     *
     * @param int    $order_id        订单号
     * @param int    $operator_id     操作员ID
     * @param int    $user_id         用户Id
     * @param int    $order_status    订单状态
     * @param int    $payment_status  支付状态
     * @param int    $shipping_status 配送状态
     * @param string $action_note     操作备注
     *
     * @return true 返回成功
     */
    public function update_order_actions($order_id, $operator_id, $user_id, $order_status, $payment_status, $shipping_status, $action_note)
    {
        $this->saveAll(array('OrderAction' => array(
            'order_id' => $order_id,
            'from_operator_id' => $operator_id,
            'user_id' => $user_id,
            'order_status' => $order_status,
            'payment_status' => $payment_status,
            'shipping_status' => $shipping_status,
            'action_note' => $action_note,
        )));

        return true;
    }
    //新增订单操作日志
    public function update_order_action($arr)
    {
        $this->saveAll(array('OrderAction' => $arr));
    }
}
