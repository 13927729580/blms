<?php

/**
 *这是一个名为 CombinedsController 的控制器
 *验证码控制器.
 *
 *@var
 */
class CombinedsController extends AppController
{
    public $name = 'Combineds';
    public $components = array('RequestHandler','Email');
    public $uses = array('LogisticsCompany','Dictionary','MailSendQueue','InformationResource','Resource','ResourceI18n','ProductRank','UserRank','OrderCard','InvoiceType','ProductAlsobought','OrderPackaging','Operator','RegionI18n','Region','VirtualCard','Shipping','Order','OrderProduct','UserAddress','Payment','Shipping','OrderProduct','User','Product','OrderAction','Brand','ProductAttribute','ProductTypeAttribute','UserBalanceLog','ShippingArea','MailTemplate','UserConfig','UserPointLog','UserBalanceLog','CouponType','Coupon','PaymentApiLog','OperatorLog');
    /**
     *后台验证码显示.
     */
    public function index($status = '')
    {
        //预留权限应用
        $this->operator_privilege('combineds_view');
        $this->menu_path = array('root' => '/oms/','sub' => '/orders/');
        //导航
        $this->set('title_for_layout', $this->ld['new_combined_orders'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_orders'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['new_combined_orders'],'url' => '');
        $this->set('navigations', $this->navigations);
        $error = '';
        if (!empty($status) && !empty($_REQUEST['checkboxes'])) {
            $codes = '';
            $codes_arr = array();
            foreach ($_REQUEST['checkboxes'] as $k => $v) {
                if ($status == 'combined_status') {
                    $product_info = $this->Order->find('first', array('conditions' => array('Order.id' => $v)));
                    if (!empty($product_info)  && $product_info['Order']['payment_status'] == 2 && $product_info['Order']['shipping_status'] == 0) {
                        $codes .= $product_info['Order']['order_code'].'|';
                        $codes_arr[] = $product_info;
                    } else {
                        $error .= $product_info['Order']['order_code'].',';//订单不能合并
                    }
                }
            }
            $this->set('all_codes', $codes_arr);
            $this->set('codes', $codes);
        }
        $this->set('error', $error);
    }

    public function combined_order_unique($orderid)
    {
        $product_info = $this->Order->find('first', array('conditions' => array('Order.order_code' => $orderid)));
        $result['flag'] = 2;
        $result['content'] = '该订单不可以合并';
        if (!empty($product_info)  && $product_info['Order']['payment_status'] == 2 && $product_info['Order']['shipping_status'] == 0) {
            $result['content'] = $product_info;
            $result['flag'] = 1;
        }
        Configure::write('debug', 0);
        die(json_encode($result));
    }

    public function view($order_code = 0)
    {
        $this->operator_privilege('combineds_view');
        $this->set('title_for_layout', $this->ld['new_combined_orders'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['manager_orders'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['new_combined_orders'],'url' => '');
        $this->set('navigations', $this->navigations);

        if ($this->RequestHandler->isPost()) {
            $new_order = array();
            $order_info = array();
            if (isset($this->data['Order']['code']) && !empty($this->data['Order']['code'])) {
                $site = strrpos($this->data['Order']['code'], '|');   // 找出字符串中字符最后出现的位置
                $code = substr($this->data['Order']['code'], 0, $site);
                $code = explode('|', $code);
                if (sizeof($code) < 2) {
                    echo "<meta charset='utf-8'><script>alert('对不起，合并订单最少需要2个订单号');history.go(-1);</script>";
                    die;
                } else {
                    foreach ($code as $k => $v) {
                        $v = str_replace("\r", '', $v);
                        $cc = $this->Order->find('first', array('conditions' => array('Order.order_code' => $v)));
                        if (isset($_SESSION['new_userlist'])) {
                            if (!in_array($cc['User']['name'], $_SESSION['new_userlist'])) {
                                $_SESSION['new_userlist'][$cc['Order']['order_code']] = $cc['User']['name'];
                            }
                        } else {
                            $_SESSION['new_userlist'][$cc['Order']['order_code']] = $cc['User']['name'];
                        }
                        $new_order[$k] = $cc;
                    }
                    $_SESSION['new_order'] = $new_order;
                }
            } else {
                if (!empty($this->data['Order']) && $order_code == 0) {
                    $order_id = 0;
                    $payment_info = $this->Payment->find('first', array('conditions' => array('Payment.id' => $this->data['Order']['payment_id'])));
                    $this->data['Order']['payment_name'] = $payment_info['PaymentI18n']['name'];
                    $shipping_info = $this->Shipping->find('first', array('conditions' => array('Shipping.id' => $this->data['Order']['shipping_id'])));
                    $this->data['Order']['shipping_name'] = $shipping_info['ShippingI18n']['name'];
                    $this->data['Order']['user_id'] = !empty($this->data['Order']['user_id']) ? $this->data['Order']['user_id'] : 0;

                    $this->data['Order']['order_code'] = $this->get_order_code();
                    $this->data['Order']['operator_id'] = $this->admin['id'];
                    $this->data['Order']['parent_order_code'] = 0;
                    $this->data['Order']['status'] = 1;
                    $this->data['Order']['payment_time'] = date('Y-m-d H:i:s');

                    $this->data['Order']['payment_fee'] = empty($this->data['Order']['payment_fee']) ? 0 : $this->data['Order']['payment_fee'];
                    $this->data['Order']['shipping_fee'] = empty($this->data['Order']['shipping_fee']) ? 0 : $this->data['Order']['shipping_fee'];
                    $this->Order->saveAll(array('Order' => $this->data['Order']));
                    $order_id = $this->Order->id;
                    if (isset($_SESSION['new_order']) && !empty($_SESSION['new_order'])) {
                        foreach ($_SESSION['new_order'] as $k => $v) {
                            $this->Order->updateAll(array('Order.status' => '5', 'Order.parent_order_code' => $this->data['Order']['order_code']), array('Order.id' => $v['Order']['id']));
                            $a = array('order_id' => $v['Order']['id'],'from_operator_id' => $this->admin['id'],'payment_status' => 2,'shipping_status' => 0,'action_note' => '合并订单至'.$this->data['Order']['order_code']);
                            $this->OrderAction->update_order_action($a);
                        }
                        unset($_SESSION['new_userlist']);
                        unset($_SESSION['new_order']);
                    }
                    $parent_ids = array();
                    $parent_ids = $this->Order->find('all', array('conditions' => array('Order.parent_order_code' => $this->data['Order']['order_code'])));
                    $act_note = '';
                    foreach ($parent_ids as $k => $v) {
                        $aa = $this->Order->findbyid($v['Order']['id']);
                        foreach ($aa['OrderProduct'] as $kk => $vv) {
                            $vv['order_id'] = $order_id;
                            $vv['id'] = '';
                            $order_info['OrderProduct'][] = $vv;
                            $this->OrderProduct->save($vv);
                        }
                        $act_note .= '<br>'.$v['Order']['order_code'];
                    }
                    $payment_api_log_data = array(
                           'payment_code' => $payment_info['Payment']['code'],
                           'type' => 0,
                           'type_id' => $order_id,
                           'amount' => $this->data['Order']['total'],
                           'is_paid' => 0,
                       );
                    $this->PaymentApiLog->save(array('PaymentApiLog' => $payment_api_log_data));
                    //操作员日志
                    if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                        $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'订单合并成功', $this->admin['id']);
                    }
                    $act = array('order_id' => $order_id,'from_operator_id' => $this->admin['id'],'order_status' => 1,'payment_status' => 2,'shipping_status' => 0,'action_note' => '合并订单'.$act_note);
                    $this->OrderAction->update_order_action($act);
                    echo "<meta charset='utf-8'><script>alert('订单合并成功!');window.location.href='/admin/orders/view/".$order_id."';</script>";
                    die();
                }
            }
            if ((isset($_SESSION['new_order']) && !empty($_SESSION['new_order'])) || (isset($_SESSION['new_userlist']) && !empty($_SESSION['new_userlist']))) {
                if ($order_code == 0) {
                    $order_code = $new_order[0]['Order']['order_code'];
                } else {
                    $order_code = $order_code;
                }
                $order_info = $this->Order->find('first', array('conditions' => array('Order.order_code' => $order_code)));
            }
            unset($order_info['OrderProduct']);
            if (isset($_SESSION['new_order']) && !empty($_SESSION['new_order'])) {
                $aa = array();
                $aa = $_SESSION['new_order'];
                $order_info['User']['balance'] = 0;
                $order_info['Order']['point_fee'] = 0;
                $order_info['Order']['shipping_fee'] = 0;
                $order_info['Order']['point_use'] = 0;
                $order_info['Order']['coupon_fee'] = 0;
                $order_info['Order']['payment_fee'] = 0;
                $order_info['Order']['money_paid'] = 0;
                $order_info['Order']['total'] = 0;
                $order_info['Order']['subtotal'] = 0;
                $order_info['Order']['insure_fee'] = 0;
                $order_info['Order']['pack_fee'] = 0;
                $order_info['Order']['card_fee'] = 0;
                $order_info['Order']['coupon_fees'] = 0;
                $order_info['Order']['tax'] = 0;
                $order_info['Order']['coupon_sn_code'] = '';
                $order_user_balance_log_info = 0;
                $product_code_arr = array();
                foreach ($aa as $k => $v) {
                    //是否使用余额
                    $balance_log_filter = '1=1';
                    $balance_log_filter .= ' and UserBalanceLog.type_id = '.$v['Order']['id'].' and UserBalanceLog.user_id = '.$v['Order']['user_id']." and UserBalanceLog.log_type = 'O'";
                    $balance_log = $this->UserBalanceLog->find($balance_log_filter);
                    $balance_log['UserBalanceLog']['amount'] = !empty($balance_log['UserBalanceLog']['amount']) ? $balance_log['UserBalanceLog']['amount'] : '0';
              //      $this->CouponType->set_locale($this->locale);
                    $coupon_info = $this->Coupon->find('first', array('conditions' => array('Coupon.id' => $v['Order']['coupon_id'])));
                    $coupon_types_info = $this->CouponType->find('first', array('conditions' => array('CouponType.id' => $coupon_info['Coupon']['coupon_type_id'])));
                    $order_info['Order']['coupon_fees'] += sprintf($this->configs['price_format'], sprintf('%01.2f', $coupon_types_info['CouponType']['money']));

                    $order_info['User']['balance'] += $balance_log['UserBalanceLog']['amount'];
                    $order_info['Order']['coupon_sn_code'] = $order_info['Order']['coupon_sn_code'] + ' ' + empty($coupon_types_info['Coupon']['sn_code']) ? '' : $coupon_types_info['Coupon']['sn_code'];

                    $order_info['Order']['payment_fee'] += $v['Order']['payment_fee'];
                    $order_info['Order']['point_fee'] += $v['Order']['point_fee'];
                    $order_info['Order']['shipping_fee'] += $v['Order']['shipping_fee'];
        //    		$order_info['Order']['coupon_fee']+=$v['Order']['coupon_fee'];
                    $order_info['Order']['money_paid'] += $v['Order']['money_paid'];
                    $order_info['Order']['discount'] += $v['Order']['discount'];
                    $order_info['Order']['total'] += $v['Order']['total'];
                    $order_info['Order']['subtotal'] += $v['Order']['subtotal'];
                    $order_info['Order']['insure_fee'] += $v['Order']['insure_fee'];
                    $order_info['Order']['pack_fee'] += $v['Order']['pack_fee'];
                    $order_info['Order']['tax'] += $v['Order']['tax'];
                    $order_info['Order']['card_fee'] += $v['Order']['card_fee'];
                    $user_balance_log = $this->UserBalanceLog->order_user_balance_log_info($v['Order']['id'], $v['Order']['user_id']);
                    $order_user_balance_log_info += $user_balance_log['UserBalanceLog']['amount'];
                    foreach ($v['OrderProduct'] as $kk => $vv) {
                        $vv['total'] = $vv['product_price'] * $vv['product_quntity'];
                        $order_info['OrderProduct'][] = $vv;
                        $product_code_arr[] = $vv['product_code'];
                    }
                }
            }
            $this->set('order_info', $order_info);
            $this->set('tishi', '请在确认订单信息后点击【确定】提交新订单');
        }
        if (empty($order_info)) {
            unset($_SESSION['new_userlist']);
            unset($_SESSION['new_order']);
            $this->redirect('/combineds');
        }
        $product_img = $this->Product->find('all', array('conditions' => array('Product.code' => $product_code_arr), 'fields' => array('img_thumb', 'id', 'market_price', 'code')));
        $product_img_new = array();
        foreach ($product_img as $k => $v) {
            $product_img_new[$v['Product']['code']] = $v;
        }
        $this->set('product_img_new', $product_img_new);
           //用户地址簿
        $user_addresses_array = $this->UserAddress->user_addresses_get($order_info['Order']['user_id']);
        $this->set('user_addresses_array', $user_addresses_array);
           //资金日志 余额
        $this->set('order_user_balance_log_info', $order_user_balance_log_info);
           //配送方式
        $shipping_effective_list = $this->Shipping->shipping_effective_list_beta($this->locale);
        $this->set('shipping_effective_list', $shipping_effective_list);
        //支付方式
        $this->Payment->set_locale($this->locale);
        $payment_effective_list = $this->Payment->getOrderPayments();
        $this->set('payment_effective_list', $payment_effective_list);
        //物流公司
        $logistics_company_list = $this->LogisticsCompany->logistics_company_effective_list();
        $this->set('logistics_company_list', $logistics_company_list);
        //发票
        $invoice_type_list = $this->InvoiceType->invoice_type_list($this->locale);
        $this->set('invoice_type_list', $invoice_type_list);
        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('order_status', 'shipping_status', 'payment_status'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
        $information_resources_info = $this->InformationResource->information_formated(array('how_oos', 'best_time'), $this->locale);
        $this->set('information_resources_info', $information_resources_info);

        //区域
        $this->Region->set_locale($this->locale);
        $regions_info = $this->Region->find('all');
        //pr($regions_info);
        foreach ($regions_info as $k => $v) {
            if ($v['Region']['id'] == $order_info['Order']['country']) {
                $order_info['Order']['country2'] = $v['RegionI18n']['name'];
            }
            if ($v['Region']['id'] == $order_info['Order']['province']) {
                $order_info['Order']['province2'] = $v['RegionI18n']['name'];
            }
            if ($v['Region']['id'] == $order_info['Order']['city']) {
                $order_info['Order']['city2'] = $v['RegionI18n']['name'];
            }
        }
        $regions_infovalues = array();
        $regions_info2 = array();
        foreach ($regions_info as $k => $v) {
            $regions_info2[$v['Region']['id']] = $v['RegionI18n']['name'];
            $regions_infovalues[$v['RegionI18n']['name']] = $v['Region']['id'];
        }
        $this->set('regions_infovalues', $regions_infovalues);
        $this->set('regions_info3', $regions_info2);
        $this->set('regions_info', json_encode($regions_info2));
        //取出id对应区域名称
        $rnames = $this->RegionI18n->getNames($this->locale);
        $this->set('rnames', $rnames);
        $this->set('regions_info3', $regions_info2);
        $this->set('regions_info', json_encode($regions_info2));
        $this->set('user_addresses_json', json_encode($user_addresses_array));
        $this->set('user_addresses_array', $user_addresses_array);
        //pr($payment_effective_list);
    }

     //获得订单号
    public function get_order_code()
    {
        mt_srand((double) microtime() * 1000000);
        $sn = date('Ymd').str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $a = 0;
        $b = 0;
        $c = 0;
        for ($i = 1;$i <= 12;++$i) {
            if ($i % 2) {
                $b += substr($sn, $i - 1, 1);
            } else {
                $a += substr($sn, $i - 1, 1);
            }
        }
        $c = (10 - ($a * 3 + $b) % 10) % 10;

        return $sn.$c;
    }
}
