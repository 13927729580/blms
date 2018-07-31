<?php

/*****************************************************************************
 * Seevia 订单管理控制器
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
/**
 *这是一个名为 OrdersController 的控制器
 *后台订单管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
App::import('Vendor', 'Ecflag', array('file' => 'ec_flag_webservice.php'));
class OrdersController extends AppController
{
    public $name = 'Orders';
    public $helpers = array('Pagination','Ckeditor');
    public $components = array('Pagination','RequestHandler','Notify','Orderfrom','EcFlagWebservice','Phpexcel','Phpcsv');
    public $uses = array('ProductLease','ProductLeasePrice','PackageProduct','Operator','UserPointLog','Application','ConfigI18n','Language','UserAddress','OrderProduct','Product','Region','RegionI18n','Coupon','CouponType','CouponProduct','OrderAction','UserBalanceLog','InvoiceType','LogisticsCompany','Payment','Shipping','User','Order','InformationResource','Resource','ResourceI18n','PaymentApiLog','NotifyTemplateType','MailSendQueue','PaymentI18n','ShippingI18n','Stock','Warehouse','Outbound','OutboundProduct','Brand','ProductI18n','Store','Inbound','InboundProduct','AppController','OperatorLog','Shop', 'OpenUser', 'OpenRelation', 'OpenModel', 'OpenUserMessage','Profile','ProfileFiled','UserConfig','ProductType','ProductStyle','PurchaseOrder','OrderProductValue','UserRank','OrderShipment','OrderShipmentProduct','OrderProductAction','OrderProductAdditional','OrderProductMedia','Attribute');
    public $dear_id = array();

    /**
     *显示友情链接列表.
     */
    public function index($page = 1)
    {
        $this->operator_privilege('orders_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/oms/','sub' => '/orders/');
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['orders_search'],'url' => '/orders/');
        $this->Order->hasMany = array();//去掉关联
        $condition = '';
        $condition['Order.lease_type'] = "P";
        //配货方式
        $picking_type_cond = array();
        if ($this->operator_privilege('stores_shipping', false)) {
            $picking_type_cond[] = 0;
        }
        if ($this->operator_privilege('factory_shipments', false)) {
            $picking_type_cond[] = 1;
        }
        if (!empty($picking_type_cond)) {
            $condition['Order.picking_type'] = $picking_type_cond;
        } else {
            $condition['Order.picking_type'] = 0;
        }
        //管理员
        if($this->operator_privilege('order_advanced',false)){
            if (isset($_REQUEST['order_manager']) && intval($_REQUEST['order_manager'])>0) {
                $condition['Order.order_manager'] = intval($_REQUEST['order_manager']);
                $this->set('order_manager', intval($_REQUEST['order_manager']));
            }else if(isset($_REQUEST['order_manager']) && intval($_REQUEST['order_manager']) == 0){
                $condition['Order.order_manager'] = intval($_REQUEST['order_manager']);
                $this->set('order_manager', intval($_REQUEST['order_manager']));
            }
        }else{
            $condition['Order.order_manager'] = $this->admin['id'];
        }
        //搜索订单号用
        $order_code = '';
        if (isset($_REQUEST['order_code']) && $_REQUEST['order_code'] != '') {
            $order_code = trim($_REQUEST['order_code']);
            $condition['Order.order_code'] = $order_code;
        }
        $this->set('order_code', $order_code);
        $order_type = 0;
        if (isset($_REQUEST['order_type']) && $_REQUEST['order_type'] != '') {
            $order_type = $_REQUEST['order_type'];
            $condition['Order.order_type'] = $order_type;
        }
        $this->set('order_type_value', $order_type);
        //搜收货人用
        $consignee = '';
        if (isset($_REQUEST['consignee']) && $_REQUEST['consignee'] != '') {
            $consignee = trim($_REQUEST['consignee']);
            $condition['Order.consignee like'] = "%$consignee%";
        }
        $this->set('consignee', $consignee);
        //订单状态
        $exp_flag = '-1';
        if (isset($_REQUEST['exp_flag']) && $_REQUEST['exp_flag'] != '-1') {
            $exp_flag = trim($_REQUEST['exp_flag']);
            $condition['Order.export_flag'] = $exp_flag;
            $this->set('exp_flag', $exp_flag);
        }
        //订单状态
        $order_status = '-1';
        if (isset($_REQUEST['order_status']) && $_REQUEST['order_status'] != '-1' && $_REQUEST['order_status'] < '5') {
            $order_status = trim($_REQUEST['order_status']);
            $condition['Order.status'] = $order_status;
        }
        if (isset($_REQUEST['order_status']) && $_REQUEST['order_status'] != '-1' && $_REQUEST['order_status'] == '20') {
            $order_status = trim($_REQUEST['order_status']) - 15;
            $condition['Order.status'] = $order_status;
            $this->set('order_status', $order_status);
        }
        //付款状态
        if (isset($_REQUEST['order_status']) && $_REQUEST['order_status'] != '-1' && $_REQUEST['order_status'] < '10' && $_REQUEST['order_status'] > '4') {
            $payment_status = trim($_REQUEST['order_status']) - 5;
            $condition['Order.status'] = 1;
            $condition['Order.payment_status'] = $payment_status;
            $condition['Order.shipping_status'] = array(0,3,6);
            $condition['Order.payment_name !='] = $this->ld['cod'];
        }
        
        //发货状态
        if (isset($_REQUEST['order_status']) && $_REQUEST['order_status'] != '-1' && $_REQUEST['order_status'] > '9' && $_REQUEST['order_status'] < '20') {
            $shipping_status = trim($_REQUEST['order_status']) - 10;
            $condition['or']['and']['Order.status'] = 1;
            $condition['or']['and']['Order.payment_status'] = 2;
            $condition['or']['and']['Order.shipping_status'] = $shipping_status;
            $condition['or']['or']['and']['Order.payment_name'] = $this->ld['cod'];
            $condition['or']['or']['and']['Order.status'] = '1';
            $condition['or']['or']['and']['Order.shipping_status'] = $shipping_status;
            if ($shipping_status == 6) {
                $condition['or']['and']['Order.payment_status'] = 0;
                $condition['or']['and']['Order.shipping_status'] = 1;
                $condition['or']['or']['and']['Order.shipping_status'] = 1;
            }
        }
        if (isset($_REQUEST['order_status']) && $_REQUEST['order_status'] == '25') {
            $condition['Order.status'] = 1;
            $condition['Order.payment_status'] = 0;
            $condition['Order.shipping_status'] = 2;
            $order_status = $_REQUEST['order_status'];
        }
        
        //待取货状态
        if (isset($_REQUEST['order_status']) && $_REQUEST['order_status'] == '26') {
            $shipping_status = trim($_REQUEST['order_status']) - 20;
            $condition['Order.shipping_status'] = $shipping_status;
            $condition['Order.payment_status'] = 2;
            $condition['Order.status'] = 1;
            $this->set('shipping_status', $shipping_status);
        }
        //第一次进来 默认为已付款的订单
        if (!isset($_REQUEST['order_status']) && $order_type == 0) {
            $order_default_search_status = isset($this->configs['order_default_search_status']) && $this->configs['order_default_search_status'] != '' ? $this->configs['order_default_search_status'] : '10';
            if (isset($order_default_search_status) && $order_default_search_status != '-1' && $order_default_search_status < '5') {
                $order_status = trim($order_default_search_status);
                $condition['Order.status'] = $order_status;
            }
            if (isset($order_default_search_status) && $order_default_search_status != '-1' && $order_default_search_status == '20') {
                $order_status = trim($order_default_search_status) - 15;
                $condition['Order.status'] = $order_status;
                $this->set('order_status', $order_status);
            }
            //付款状态
            if (isset($order_default_search_status) && $order_default_search_status != '-1' && $order_default_search_status < '10' && $order_default_search_status > '4') {
                $payment_status = trim($order_default_search_status) - 5;
                $condition['Order.status'] = 1;
                $condition['Order.payment_status'] = $payment_status;
                $condition['Order.shipping_status'] =array(0,3,6);
                $condition['Order.payment_name !='] = $this->ld['cod'];
            }
            //发货状态
            if (isset($order_default_search_status) && $order_default_search_status != '-1' && $order_default_search_status > '9' && $order_default_search_status < '20') {
                $shipping_status = trim($order_default_search_status) - 10;
                $condition['or']['and']['Order.status'] = 1;
                $condition['or']['and']['Order.payment_status'] = 2;
                $condition['or']['and']['Order.shipping_status'] = $shipping_status;
                $condition['or']['or']['and']['Order.payment_name'] = $this->ld['cod'];
                $condition['or']['or']['and']['Order.status'] = '1';
                $condition['or']['or']['and']['Order.shipping_status'] = $shipping_status;
                if ($shipping_status == 6) {
                    $condition['or']['and']['Order.payment_status'] = 0;
                    $condition['or']['and']['Order.shipping_status'] = 1;
                    $condition['or']['or']['and']['Order.shipping_status'] = 1;
                }
            }
            if (isset($order_default_search_status) && $order_default_search_status == '25') {
                $condition['Order.status'] = 1;
                $condition['Order.payment_status'] = 0;
                $condition['Order.shipping_status'] = 2;
                $order_status = $order_default_search_status;
            }
        }
        // 审核状态
        if (isset($_REQUEST['check_status']) && $_REQUEST['check_status'] != '-1') {
            $check_status = trim($_REQUEST['check_status']) ;
            //pr($check_status);
            $condition['Order.check_status'] = $check_status;
            $this->set('check_status', $check_status);
        }
        if (isset($payment_status)) {
            $this->set('payment_status', $payment_status);
        } elseif (isset($shipping_status)) {
            $this->set('shipping_status', $shipping_status);
        } else {
            $this->set('order_status', $order_status);
        }
        $type_arr = array();
        $ta_str = '';
        if (isset($_REQUEST['ta']) && $_REQUEST['ta'] != '') {
            $type_arr = explode(',', $_REQUEST['ta']);
            foreach ($type_arr as $k => $v) {
                $type_arr_detail = explode(':', $v);
                if (sizeof($type_arr_detail) == 2) {
                    $condition['and']['or'][$k]['Order.type'] = $type_arr_detail[0];
                    $condition['and']['or'][$k]['Order.type_id'] = $type_arr_detail[1];
//					if($this->admin['type']=='D'&&$this->admin['type_id']!=$type_arr_detail[1])
//					$condition['and']['or'][$k]["Order.type"]=$type_arr_detail[0];
//					$condition['and']['or'][$k]["Order.type_id"]=$type_arr_detail[1];
                }
                $ta_str = $_REQUEST['ta'];
            }
        }
        $this->set('type_arr', $type_arr);
        $this->set('ta_str', $ta_str);
        //订单来源
        $this->Orderfrom->get($this);
        //下单开始时间
        $start_date = '';
        if (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] != '') {
            $start_date = trim($_REQUEST['start_date']);
            $condition['Order.created >'] = $start_date.' 00:00:00';
        }
        $this->set('start_date', $start_date);
        //下单结束时间
        $end_date = '';
        if (isset($_REQUEST['end_date']) && $_REQUEST['end_date'] != '') {
            $end_date = trim($_REQUEST['end_date']);
            $condition['Order.created <'] = $end_date.' 23:59:59';
        }
        $this->set('end_date', $end_date);
        $min_amount = '';
        if (isset($_REQUEST['min_amount']) && $_REQUEST['min_amount'] != '') {
            $min_amount = trim($_REQUEST['min_amount']);
            $condition['Order.total >='] = $min_amount;
        }
        $this->set('min_amount', $min_amount);
        $max_amount = '';
        if (isset($_REQUEST['max_amount']) && $_REQUEST['max_amount'] != '') {
            $max_amount = trim($_REQUEST['max_amount']);
            $condition['Order.total <='] = $max_amount;
        }
        $this->set('max_amount', $max_amount);
        //根据商品的名称或货号来搜
        $product_keywords = '';
        $expire_date_start="";
        $expire_date_end="";
        $next_condition = '';
        if (isset($_REQUEST['product_keywords']) && $_REQUEST['product_keywords'] != '') {
            $product_keywords = trim($_REQUEST['product_keywords']);
            $next_condition['or']['OrderProduct.product_name like'] = "%$product_keywords%";
            $next_condition['or']['OrderProduct.product_code like'] = "%$product_keywords%";
        }
        if (isset($_REQUEST['expire_date_start']) && $_REQUEST['expire_date_start'] != '') {
            $expire_date_start = trim($_REQUEST['expire_date_start']);
            $next_condition['and']['OrderProduct.expire_date >='] = $expire_date_start;
        }
        if (isset($_REQUEST['expire_date_end']) && $_REQUEST['expire_date_end'] != '') {
            $expire_date_end = trim($_REQUEST['expire_date_end']);
            $next_condition['and']['OrderProduct.expire_date <='] = $expire_date_end;
        }
        if(!empty($next_condition)){
            $this->OrderProduct->hasOne = array();
            $order_ids = $this->OrderProduct->find('list', array('conditions' => $next_condition, 'fields' => 'OrderProduct.order_id'));
            //	if(!empty($order_ids)){
            $condition['Order.id'] = $order_ids;
            //	}
        }
        $this->set('product_keywords', $product_keywords);
        $this->set('expire_date_start', $expire_date_start);
        $this->set('expire_date_end', $expire_date_end);
        if (isset($_REQUEST['export_act_flag']) && $_REQUEST['export_act_flag'] == 1) {
            $code = $_REQUEST['select_code'];
            $this->search_result($condition, 'order_search', $code);
        }
        $total = $this->Order->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $sortClass = 'Order';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'orders','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Order');
        $this->Pagination->init($condition, $parameters, $options);
        $fields = array('Order.id','Order.message','Order.user_id','Order.order_code','Order.to_type','Order.to_type_id','Order.export_flag','Order.note','Order.postscript','Order.total','Order.money_paid','Order.point_fee','Order.discount','Order.consignee','Order.telephone','Order.address','Order.user_id','Order.payment_id','Order.payment_name','Order.shipping_name','Order.shipping_id','Order.shipping_status','Order.payment_status','Order.status','Order.created','Order.type_id','Order.type','Order.payment_time','Order.shipping_time','Order.logistics_company_id','Order.invoice_no','Order.coupon_fee','Order.order_manager','Order.check_status','Order.best_time','Order.mobile','Order.country','Order.province','Order.city','Order.district');//需用到的字段
        $orders_list = $this->Order->find('all', array('conditions' => $condition, 'fields' => $fields, 'order' => 'Order.created DESC', 'page' => $page, 'limit' => $rownum));
        //格式化数据
        $order_codes = '';
        $order_code_list = array();
        $user_id_array = array();
        $this->Payment->set_locale($this->backend_locale);
        $payment_names = $this->PaymentI18n->find('list', array('fields' => array('PaymentI18n.payment_id', 'PaymentI18n.name'), 'conditions' => array('PaymentI18n.locale' => $this->locale)));
        $payment_iscods = $this->Payment->find('list', array('fields' => array('Payment.id', 'Payment.is_cod'), 'conditions' => array('Payment.status' => 1)));
        $shipping_names = $this->ShippingI18n->find('list', array('fields' => array('ShippingI18n.shipping_id', 'ShippingI18n.name'), 'conditions' => array('ShippingI18n.locale' => $this->locale)));
        //快递公司名称
        $logistics_companys = $this->LogisticsCompany->find('list', array('fields' => array('LogisticsCompany.id', 'LogisticsCompany.name')));
        $this->set('logistics_companys', $logistics_companys);
        $order_ids=array();
        foreach ($orders_list as $k => $v) {
            $order_id = $v['Order']['id'];
            $order_ids[]=$v['Order']['id'];
            if (in_array('APP-API-WEBSERVICE', $this->apps['codes'])) {
                $order_codes .= $v['Order']['order_code'].'|';
            }
            $order_code_list[] = $v['Order']['order_code'];
            //$orders_list[$k]['Order']['should_pay'] = $v['Order']['total']-$v['Order']['point_fee']-$v['Order']['discount'];//计算应付金额
            $need_pay=$v['Order']['total']-$v["Order"]["coupon_fee"]-$v["Order"]["point_fee"]-$v['Order']['money_paid']-$v['Order']['discount'];
            $orders_list[$k]['Order']['should_pay'] = $need_pay;//计算应付金额
            $user_id_array[] = $v['Order']['user_id'];
            $orders_list[$k]['Order']['paymenttype'] = isset($payment_iscods[$v['Order']['payment_id']]) ? $payment_iscods[$v['Order']['payment_id']] : '';
            $orders_list[$k]['Order']['payment_name'] = isset($payment_names[$v['Order']['payment_id']]) ? $payment_names[$v['Order']['payment_id']] : '';
            $orders_list[$k]['Order']['shipping_name'] = isset($shipping_names[$v['Order']['shipping_id']]) ? $shipping_names[$v['Order']['shipping_id']] : '';
            $orders_list[$k]['Order']['logistics_company_name'] = isset($logistics_companys[$v['Order']['logistics_company_id']]) ? $logistics_companys[$v['Order']['logistics_company_id']] : '';
        }
        if (in_array('APP-API-WEBSERVICE', $this->apps['codes'])) {
            if (!empty($order_codes)) {
                $order_codes = substr($order_codes, 0, strlen($order_codes) - 1);
                //$this->EcFlagWebservice->startup($this);
                $get_order_detail = $this->EcFlagWebservice->GetOrderDetail($order_codes);
                $get_order_array = explode(';', $get_order_detail['GetOrderDetailResult']);
                foreach ($get_order_array as $k => $v) {
                    $order_info_detail = explode('|', $v);
                    if (isset($order_info_detail[2])) {
                        if ($order_info_detail[2] == 0) {
                            $order_info_detail[2] = '0     订单异常';
                        }
                        if ($order_info_detail[2] == 100) {
                            $order_info_detail[2] = '100     导入完成';
                        }
                        if ($order_info_detail[2] == 101) {
                            $order_info_detail[2] = '101     等待审核';
                        }
                        if ($order_info_detail[2] == 110) {
                            $order_info_detail[2] = '110     正在取货';
                        }
                        if ($order_info_detail[2] == 120) {
                            $order_info_detail[2] = '120     装箱完成';
                        }
                        if ($order_info_detail[2] == 130) {
                            $order_info_detail[2] = '130     等待出运';
                        }
                        if ($order_info_detail[2] == 135) {
                            $order_info_detail[2] = '135     出运完成';
                        }
                        if ($order_info_detail[2] == 140) {
                            $order_info_detail[2] = '140     订单合并';
                        }
                        if ($order_info_detail[2] == 150) {
                            $order_info_detail[2] = '150     缺货待定';
                        }
                        if ($order_info_detail[2] == 151) {
                            $order_info_detail[2] = '151     缺货退单';
                        }
                        if ($order_info_detail[2] == 152) {
                            $order_info_detail[2] = '152     工厂生产';
                        }
                        if ($order_info_detail[2] == 160) {
                            $order_info_detail[2] = '160     其它退单';
                        }
                        if ($order_info_detail[2] == 170) {
                            $order_info_detail[2] = '170     订单取消';
                        }
                    } else {
                        $order_info_detail[2] = '';
                    }
                    $orders_list[$k]['Order']['ec_export_flag'] = isset($order_info_detail[2]) ? $order_info_detail[2] : '';
                }
            }
        }
        $user_id_array = array_unique($user_id_array);//去除重复
        //取相关用户数据
        $user_data = $this->User->user_name_array($user_id_array);
        $pro_infos = array();
        $condition2 = array('OrderProduct.order_id' => $order_ids,'OrderProduct.status' => '1');
        $cond['conditions'] = $condition2;
        $cond['fields'] = array('OrderProduct.parent_product_id','OrderProduct.order_id','OrderProduct.product_id','OrderProduct.product_name','OrderProduct.product_code','OrderProduct.product_quntity','OrderProduct.product_price');
        $this->OrderProduct->hasOne = array();
        $pro_info = $this->OrderProduct->find('all', $cond);
        if (!empty($pro_info)) {
            foreach($pro_info as $v){
                $pro_infos[$v['OrderProduct']['order_id']][] = $v;
            }
            $order_product_ids = array();
            $order_product_info = array();
            foreach ($pro_info as $k => $v) {
                $order_product_ids[$v['OrderProduct']['product_id']] = $v['OrderProduct']['product_id'];
            }
            $product_info = $this->Product->find('all', array('conditions' => array('Product.id' => $order_product_ids), 'fields' => array('Product.id', 'Product.img_thumb', 'Product.market_price')));
            foreach ($product_info as $v) {
                $order_product_info[$v['Product']['id']] = $v['Product'];
            }
            $this->set('order_product_info', $order_product_info);
        }
        $this->set('pro_infos', $pro_infos);
        $this->set('pro_info', $pro_info);
        $this->set('orders_list', $orders_list);//订单列表
        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('order_status', 'shipping_status', 'payment_status', 'picking_type','order_service_type'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
        if (!empty($order_code_list) && isset($this->configs['vendor_shipment']) && $this->configs['vendor_shipment'] == '1') {
            $purchase_order_data = $this->PurchaseOrder->find('all', array('conditions' => array('PurchaseOrder.order_code' => $order_code_list)));
            $purchase_order_list = array();
            foreach ($purchase_order_data as $v) {
                $purchase_order_list[$v['PurchaseOrder']['order_code']] = $v['PurchaseOrder'];
            }
            $this->set('purchase_order_list', $purchase_order_list);
        }
        $operator_list = $this->Operator->find('list',array('fields' => array('Operator.id','Operator.name'),'conditions'=>array('Operator.status'=>'1')));
        $this->set('operator_list',$operator_list);

        $title = $this->ld['orders_search'];
        if ($order_type == 1) {
            $title = '采购单管理';
        } elseif ($order_type == 2) {
            $title = '退货单管理';
        }
        $this->set('title_for_layout', $title.' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    /**
     *租赁列表.
     */
    public function lease_order($page = 1)
    {
        $this->operator_privilege('lease_order_view');

        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/oms/','sub' => '/orders/');
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['lease_order'],'url' => '/orders/lease_order');
        $this->Order->hasMany = array();//去掉关联
        $condition = '';
        $condition['Order.lease_type'] = "L";

        //配货方式
        $picking_type_cond = array();
        if ($this->operator_privilege('stores_shipping', false)) {
            $picking_type_cond[] = 0;
        }
        if ($this->operator_privilege('factory_shipments', false)) {
            $picking_type_cond[] = 1;
        }
        if (!empty($picking_type_cond)) {
            $condition['Order.picking_type'] = $picking_type_cond;
        } else {
            $condition['Order.picking_type'] = 0;
        }
        //搜索订单号用
        $order_code = '';
        if (isset($_REQUEST['order_code']) && $_REQUEST['order_code'] != '') {
            $order_code = trim($_REQUEST['order_code']);
            $condition['Order.order_code'] = $order_code;
        }
        $this->set('order_code', $order_code);
        $order_type = 0;
        if (isset($_REQUEST['order_type']) && $_REQUEST['order_type'] != '') {
            $order_type = $_REQUEST['order_type'];
            $condition['Order.order_type'] = $order_type;
        }
        $this->set('order_type_value', $order_type);
        //搜收货人用
        $consignee = '';
        if (isset($_REQUEST['consignee']) && $_REQUEST['consignee'] != '') {
            $consignee = trim($_REQUEST['consignee']);
            $condition['Order.consignee like'] = "%$consignee%";
        }
        $this->set('consignee', $consignee);
        //订单状态
        $exp_flag = '-1';
        if (isset($_REQUEST['exp_flag']) && $_REQUEST['exp_flag'] != '-1') {
            $exp_flag = trim($_REQUEST['exp_flag']);
            $condition['Order.export_flag'] = $exp_flag;
            $this->set('exp_flag', $exp_flag);
        }
        //订单状态
        $order_status = '-1';
        if (isset($_REQUEST['order_status']) && $_REQUEST['order_status'] != '-1' && $_REQUEST['order_status'] < '5') {
            $order_status = trim($_REQUEST['order_status']);
            $condition['Order.status'] = $order_status;
        }
        if (isset($_REQUEST['order_status']) && $_REQUEST['order_status'] != '-1' && $_REQUEST['order_status'] == '20') {
            $order_status = trim($_REQUEST['order_status']) - 15;
            $condition['Order.status'] = $order_status;
            $this->set('order_status', $order_status);
        }
        //付款状态
        if (isset($_REQUEST['order_status']) && $_REQUEST['order_status'] != '-1' && $_REQUEST['order_status'] < '10' && $_REQUEST['order_status'] > '4') {
            $payment_status = trim($_REQUEST['order_status']) - 5;
            $condition['Order.status'] = 1;
            $condition['Order.payment_status'] = $payment_status;
            $condition['Order.shipping_status'] = 0;
            $condition['Order.payment_name !='] = $this->ld['cod'];
        }
        //发货状态
        if (isset($_REQUEST['order_status']) && $_REQUEST['order_status'] != '-1' && $_REQUEST['order_status'] > '9' && $_REQUEST['order_status'] < '20') {
            $shipping_status = trim($_REQUEST['order_status']) - 10;
            $condition['or']['and']['Order.status'] = 1;
            $condition['or']['and']['Order.payment_status'] = 2;
            $condition['or']['and']['Order.shipping_status'] = $shipping_status;
            $condition['or']['or']['and']['Order.payment_name'] = $this->ld['cod'];
            $condition['or']['or']['and']['Order.status'] = '1';
            $condition['or']['or']['and']['Order.shipping_status'] = $shipping_status;
            if ($shipping_status == 6) {
                $condition['or']['and']['Order.payment_status'] = 0;
                $condition['or']['and']['Order.shipping_status'] = 1;
                $condition['or']['or']['and']['Order.shipping_status'] = 1;
            }
        }
        if (isset($_REQUEST['order_status']) && $_REQUEST['order_status'] == '25') {
            $condition['Order.status'] = 1;
            $condition['Order.payment_status'] = 0;
            $condition['Order.shipping_status'] = 2;
            $order_status = $_REQUEST['order_status'];
        }

        //第一次进来 默认为已付款的订单
        if (!isset($_REQUEST['order_status']) && $order_type == 0) {
            $order_default_search_status = isset($this->configs['order_default_search_status']) && $this->configs['order_default_search_status'] != '' ? $this->configs['order_default_search_status'] : '10';
            if (isset($order_default_search_status) && $order_default_search_status != '-1' && $order_default_search_status < '5') {
                $order_status = trim($order_default_search_status);
                $condition['Order.status'] = $order_status;
            }
            if (isset($order_default_search_status) && $order_default_search_status != '-1' && $order_default_search_status == '20') {
                $order_status = trim($order_default_search_status) - 15;
                $condition['Order.status'] = $order_status;
                $this->set('order_status', $order_status);
            }
            //付款状态
            if (isset($order_default_search_status) && $order_default_search_status != '-1' && $order_default_search_status < '10' && $order_default_search_status > '4') {
                $payment_status = trim($order_default_search_status) - 5;
                $condition['Order.status'] = 1;
                $condition['Order.payment_status'] = $payment_status;
                $condition['Order.shipping_status'] = 0;
                $condition['Order.payment_name !='] = $this->ld['cod'];
            }
            //发货状态
            if (isset($order_default_search_status) && $order_default_search_status != '-1' && $order_default_search_status > '9' && $order_default_search_status < '20') {
                $shipping_status = trim($order_default_search_status) - 10;
                $condition['or']['and']['Order.status'] = 1;
                $condition['or']['and']['Order.payment_status'] = 2;
                $condition['or']['and']['Order.shipping_status'] = $shipping_status;
                $condition['or']['or']['and']['Order.payment_name'] = $this->ld['cod'];
                $condition['or']['or']['and']['Order.status'] = '1';
                $condition['or']['or']['and']['Order.shipping_status'] = $shipping_status;
                if ($shipping_status == 6) {
                    $condition['or']['and']['Order.payment_status'] = 0;
                    $condition['or']['and']['Order.shipping_status'] = 1;
                    $condition['or']['or']['and']['Order.shipping_status'] = 1;
                }
            }
            if (isset($order_default_search_status) && $order_default_search_status == '25') {
                $condition['Order.status'] = 1;
                $condition['Order.payment_status'] = 0;
                $condition['Order.shipping_status'] = 2;
                $order_status = $order_default_search_status;
            }
        }
        if (isset($payment_status)) {
            $this->set('payment_status', $payment_status);
        } elseif (isset($shipping_status)) {
            $this->set('shipping_status', $shipping_status);
        } else {
            $this->set('order_status', $order_status);
        }
        $type_arr = array();
        $ta_str = '';
        if (isset($_REQUEST['ta']) && $_REQUEST['ta'] != '') {
            $type_arr = explode(',', $_REQUEST['ta']);
            foreach ($type_arr as $k => $v) {
                $type_arr_detail = explode(':', $v);
                if (sizeof($type_arr_detail) == 2) {
                    $condition['and']['or'][$k]['Order.type'] = $type_arr_detail[0];
                    $condition['and']['or'][$k]['Order.type_id'] = $type_arr_detail[1];
                }
                $ta_str = $_REQUEST['ta'];
            }
        }
        $this->set('type_arr', $type_arr);
        $this->set('ta_str', $ta_str);
        //订单来源
        $this->Orderfrom->get($this);
        //下单开始时间
        $start_date = '';
        if (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] != '') {
            $start_date = trim($_REQUEST['start_date']);
            $condition['Order.created >'] = $start_date.' 00:00:00';
        }
        $this->set('start_date', $start_date);
        //下单结束时间
        $end_date = '';
        if (isset($_REQUEST['end_date']) && $_REQUEST['end_date'] != '') {
            $end_date = trim($_REQUEST['end_date']);
            $condition['Order.created <'] = $end_date.' 23:59:59';
        }
        $this->set('end_date', $end_date);
        $min_amount = '';
        if (isset($_REQUEST['min_amount']) && $_REQUEST['min_amount'] != '') {
            $min_amount = trim($_REQUEST['min_amount']);
            $condition['Order.total >='] = $min_amount;
        }
        $this->set('min_amount', $min_amount);
        $max_amount = '';
        if (isset($_REQUEST['max_amount']) && $_REQUEST['max_amount'] != '') {
            $max_amount = trim($_REQUEST['max_amount']);
            $condition['Order.total <='] = $max_amount;
        }
        $this->set('max_amount', $max_amount);
        //根据商品的名称或货号来搜
        $product_keywords = '';
        $expire_date_start="";
        $expire_date_end="";
        $lease_date_start="";
        $lease_date_end="";
        $next_condition = '';
        if (isset($_REQUEST['product_keywords']) && $_REQUEST['product_keywords'] != '') {
            $product_keywords = trim($_REQUEST['product_keywords']);
            $next_condition['or']['OrderProduct.product_name like'] = "%$product_keywords%";
            $next_condition['or']['OrderProduct.product_code like'] = "%$product_keywords%";
        }
        if(!empty($next_condition)){
            $this->OrderProduct->hasOne = array();
            $order_ids = $this->OrderProduct->find('list', array('conditions' => $next_condition, 'fields' => 'OrderProduct.order_id'));
            //	if(!empty($order_ids)){
            $condition['Order.id'] = $order_ids;
            //	}
        }
        $this->set('product_keywords', $product_keywords);
        $this->set('expire_date_start', $expire_date_start);
        $this->set('expire_date_end', $expire_date_end);
        if (isset($_REQUEST['export_act_flag']) && $_REQUEST['export_act_flag'] == 1) {
            $code = $_REQUEST['select_code'];
            $this->search_result($condition, 'order_search', $code);
        }
        $sortClass = 'Order';
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $parameters['get'] = array();
        $fields = array('Order.id','Order.message','Order.user_id','Order.order_code','Order.to_type','Order.to_type_id','Order.export_flag','Order.note','Order.postscript','Order.total','Order.money_paid','Order.point_fee','Order.discount','Order.consignee','Order.telephone','Order.address','Order.user_id','Order.payment_id','Order.payment_name','Order.shipping_name','Order.shipping_id','Order.shipping_status','Order.payment_status','Order.status','Order.created','Order.type_id','Order.type','Order.payment_time','Order.shipping_time','Order.logistics_company_id','Order.invoice_no');//需用到的字段
        $orders_list = $this->Order->find('all', array('conditions' => $condition, 'fields' => $fields, 'order' => 'Order.created DESC'));
        //格式化数据
        $order_codes = '';
        $order_code_list = array();
        $user_id_array = array();
        $this->Payment->set_locale($this->backend_locale);
        $payment_names = $this->PaymentI18n->find('list', array('fields' => array('PaymentI18n.payment_id', 'PaymentI18n.name'), 'conditions' => array('PaymentI18n.locale' => $this->locale)));
        $payment_iscods = $this->Payment->find('list', array('fields' => array('Payment.id', 'Payment.is_cod'), 'conditions' => array('Payment.status' => 1)));
        $shipping_names = $this->ShippingI18n->find('list', array('fields' => array('ShippingI18n.shipping_id', 'ShippingI18n.name'), 'conditions' => array('ShippingI18n.locale' => $this->locale)));
        foreach ($orders_list as $k => $v) {
            $order_id = $v['Order']['id'];
            $order_code_list[] = $v['Order']['order_code'];
            //$orders_list[$k]['Order']['should_pay'] = $v['Order']['total']-$v['Order']['point_fee']-$v['Order']['discount'];//计算应付金额
            $orders_list[$k]['Order']['should_pay'] = $v['Order']['total'] - $v['Order']['point_fee'];//计算应付金额
            $user_id_array[] = $v['Order']['user_id'];
            $orders_list[$k]['Order']['paymenttype'] = isset($payment_iscods[$v['Order']['payment_id']]) ? $payment_iscods[$v['Order']['payment_id']] : '';
            $orders_list[$k]['Order']['payment_name'] = isset($payment_names[$v['Order']['payment_id']]) ? $payment_names[$v['Order']['payment_id']] : '';
            $orders_list[$k]['Order']['shipping_name'] = isset($shipping_names[$v['Order']['shipping_id']]) ? $shipping_names[$v['Order']['shipping_id']] : '';
            $orders_list[$k]['Order']['logistics_company_name'] = isset($logistics_companys[$v['Order']['logistics_company_id']]) ? $logistics_companys[$v['Order']['logistics_company_id']] : '';
        }
        $user_id_array = array_unique($user_id_array);//去除重复
        //取相关用户数据
        $user_data = $this->User->user_name_array($user_id_array);
        $arr = array();
        for ($i = 0;$i < sizeof($orders_list);++$i) {
            $arr[$i] = $orders_list[$i]['Order']['id'];
        }
        $condition2 = array('OrderProduct.order_id' => $arr,'OrderProduct.lease_type' => 'L');
        $cond['conditions'] = $condition2;
        $cond['order'] = "OrderProduct.created DESC";
        $cond['fields'] = array('OrderProduct.status','OrderProduct.id','OrderProduct.order_id','OrderProduct.product_id','OrderProduct.product_name','OrderProduct.product_code','OrderProduct.product_quntity','OrderProduct.product_price','OrderProduct.lease_unit','OrderProduct.expire_date','OrderProduct.begin_date');
        $this->OrderProduct->hasOne = array();
        $pro_info = $this->OrderProduct->find('all', $cond);
        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('order_status', 'shipping_status', 'payment_status', 'picking_type'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
        if (!empty($order_code_list) && isset($this->configs['vendor_shipment']) && $this->configs['vendor_shipment'] == '1') {
            $purchase_order_data = $this->PurchaseOrder->find('all', array('conditions' => array('PurchaseOrder.order_code' => $order_code_list)));
            $purchase_order_list = array();
            foreach ($purchase_order_data as $v) {
                $purchase_order_list[$v['PurchaseOrder']['order_code']] = $v['PurchaseOrder'];
            }
            $this->set('purchase_order_list', $purchase_order_list);
        }
        $title = "租赁订单管理";
        if ($order_type == 1) {
            $title = '采购单管理';
        } elseif ($order_type == 2) {
            $title = '退货单管理';
        }
        //数组整合
        if(!empty($orders_list)){
            foreach($orders_list as $kk=>$vv){
                $ord_info[$vv["Order"]["id"]]=$vv["Order"];
            }
            if(!empty($pro_info)){
                foreach($pro_info as $po_kk=>$po_vv){
                    $day=$po_vv["OrderProduct"]["lease_unit"];
                    $start_time=$ord_info[$po_vv["OrderProduct"]["order_id"]]["created"];
                    //$end_time=date("Y-m-d H:i:s", strtotime("$start_time +$day day"));
                    $lease_info= $this->ProductLease->find('first', array('conditions' => array('ProductLease.product_code' => $po_vv['OrderProduct']['product_code'])));
                    $po_info[]=array(
                        "order_id"=>$po_vv["OrderProduct"]["order_id"],
                        "order_product_id"=>$po_vv["OrderProduct"]["id"],
                        "product_name"=>$po_vv["OrderProduct"]["product_name"],
                        "product_code"=>$po_vv["OrderProduct"]["product_code"],
                        "product_quntity"=>$po_vv["OrderProduct"]["product_quntity"],
                        "product_price"=>$po_vv["OrderProduct"]["product_price"],
                        "base_unit"=>$lease_info["ProductLease"]["unit"],
                        "lease_unit"=>$day,
                        "order_code"=>$ord_info[$po_vv["OrderProduct"]["order_id"]]["order_code"],
                        "total"=>$ord_info[$po_vv["OrderProduct"]["order_id"]]["total"],
                        "consignee"=>$ord_info[$po_vv["OrderProduct"]["order_id"]]["consignee"],
                        "paymenttype"=>$ord_info[$po_vv["OrderProduct"]["order_id"]]["paymenttype"],
                        "product_status"=>$po_vv["OrderProduct"]["status"],
                        "status"=>$ord_info[$po_vv["OrderProduct"]["order_id"]]["status"],
                        "shipping_status"=>$ord_info[$po_vv["OrderProduct"]["order_id"]]["shipping_status"],
                        "payment_status"=>$ord_info[$po_vv["OrderProduct"]["order_id"]]["payment_status"],
                        "created"=>$start_time,
                        "payment_time"=>$ord_info[$po_vv["OrderProduct"]["order_id"]]["payment_time"],
                        "shipping_time"=>$ord_info[$po_vv["OrderProduct"]["order_id"]]["shipping_time"],
                        "expire_date"=>$po_vv["OrderProduct"]["expire_date"],
                        "begin_date"=>$po_vv["OrderProduct"]["begin_date"]
                    );
                }
                //商品租赁时间
                if (isset($_REQUEST['lease_date_start']) && $_REQUEST['lease_date_start'] != '') {
                    $lease_date_start = trim($_REQUEST['lease_date_start']);
                    foreach($po_info as $pp=>$pv){
                        if($pv["expire_date"]<$lease_date_start){
                            unset($po_info[$pp]);
                        }
                    }
                }
                if (isset($_REQUEST['lease_date_end']) && $_REQUEST['lease_date_end'] != '') {
                    $lease_date_end = trim($_REQUEST['lease_date_end']);
                    foreach($po_info as $pp=>$pv){
                        if($pv["expire_date"]>$lease_date_end){
                            unset($po_info[$pp]);
                        }
                    }
                }
                //地址路由参数（和control,action的参数对应）
                $total = count($po_info);
            }else{
                $total=0;
                $po_info=array();
            }
        }else{
            $total=0;
            $po_info=array();
        }
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['route'] = array('controller' => 'orders','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Order');
        $this->Pagination->init($condition, $parameters, $options);
        $this->set('lease_date_start', $lease_date_start);
        $this->set('lease_date_end', $lease_date_end);
        $po_info=array_slice($po_info,$rownum*($page-1),$rownum);
        $this->set('po_info', $po_info);//租赁列表
        $this->set('title_for_layout', $this->ld['lease_order_man'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function get_dealer_id()
    {
        $this->loadModel('Dealer');
        $dear_id = array();
        if ($this->admin['type'] == 'D') {
            $dear_tree = $this->Dealer->tree();
            foreach ($dear_tree as $k => $v) {
                if ($v['Dealer']['id'] == $this->admin['type_id']) {
                    $dear_id[$v['Dealer']['id']] = $v['Dealer']['name'];
                } elseif (!empty($v['SubDealer'])) {
                    foreach ($v['SubDealer'] as $kk => $vv) {
                        if ($vv['Dealer']['id'] == $this->admin['type_id']) {
                            $dear_id[$v['Dealer']['id']] = $v['Dealer']['name'];
                            $dear_id[$vv['Dealer']['id']] = '--'.$vv['Dealer']['name'];
                        } elseif (!empty($vv['SubDealer'])) {
                            foreach ($vv['SubDealer'] as $kkk => $vvv) {
                                if ($vvv['Dealer']['id'] == $this->admin['type_id']) {
                                    $dear_id[$v['Dealer']['id']] = $v['Dealer']['name'];
                                    $dear_id[$vv['Dealer']['id']] = '--'.$vv['Dealer']['name'];
                                    $dear_id[$vvv['Dealer']['id']] = '----'.$vvv['Dealer']['name'];
                                } elseif (!empty($vvv['SubDealer'])) {
                                    foreach ($vvv['SubDealer'] as $kkkk => $vvvv) {
                                        if ($vvvv['Dealer']['id'] == $this->admin['type_id']) {
                                            $dear_id[$v['Dealer']['id']] = $v['Dealer']['name'];
                                            $dear_id[$vv['Dealer']['id']] = '--'.$vv['Dealer']['name'];
                                            $dear_id[$vvv['Dealer']['id']] = '----'.$vvv['Dealer']['name'];
                                            $dear_id[$vvvv['Dealer']['id']] = '----'.$vvvv['Dealer']['name'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $dear_tree = $this->Dealer->tree();
            if (!empty($dear_tree)) {
                foreach ($dear_tree as $k => $v) {
                    $dear_id[$v['Dealer']['id']] = $v['Dealer']['name'];
                    if (!empty($v['SubDealer'])) {
                        foreach ($v['SubDealer'] as $kk => $vv) {
                            $dear_id[$vv['Dealer']['id']] = '--'.$vv['Dealer']['name'];
                            if (!empty($vv['SubDealer'])) {
                                foreach ($vv['SubDealer'] as $kkk => $vvv) {
                                    $dear_id[$vvv['Dealer']['id']] = '----'.$vvv['Dealer']['name'];
                                    if (!empty($vvv['SubDealer'])) {
                                        foreach ($vvv['SubDealer'] as $kkkk => $vvvv) {
                                            $dear_id[$vvvv['Dealer']['id']] = '------'.$vvvv['Dealer']['name'];
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $dear_id;
    }

    public function view($id)
    {
        $this->menu_path = array('root' => '/oms/','sub' => '/orders/');
        if ($this->admin['type'] == 'D') {
            $this->set('dp_displaed', 0);
        }
        $order_info = $this->Order->findbyid($id);//订单信息
        if(empty($order_info)){$this->redirect("/orders/");}
        if($order_info['Order']['lease_type']=='L'){
            $this->operator_privilege('lease_order_view');
        }else{
            $this->operator_privilege('order_view');
        }
        $OrderProductInfos = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $id), 'fields' => 'OrderProduct.product_id,OrderProduct.product_code'));
        $OrderProductIds = array();
        $OrderProductCodes = array();
        foreach ($OrderProductInfos as $k => $v) {
            $OrderProductIds[$v['OrderProduct']['product_id']] = $v['OrderProduct']['product_id'];
            $OrderProductCodes[$v['OrderProduct']['product_code']] = $v['OrderProduct']['product_code'];
        }
        //$OrderProductIds=$this->OrderProduct->find('list',array('conditions'=>array('OrderProduct.order_id'=>$id),'fields'=>"OrderProduct.product_id"));
        //$all_product_infos=$this->Product->getIdPrices($OrderProductIds);
        $all_product_infos = $this->Product->getOrderProductPriceList($OrderProductIds, $OrderProductCodes);
        //$all_product_quantity_infos=$this->Product->getIdQuantities($OrderProductIds);
        //$this->set('all_product_quantity_infos',$all_product_quantity_infos);
        $this->set('all_product_infos', $all_product_infos);
        //如果订单不存在 提示
        if (empty($order_info)) {
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("订单不存在！");	window.location.href="/admin/orders/"</script>';
            die();
        }
        $this->set('title_for_layout', $this->ld['view'].$this->ld['order'].'-'.$this->ld['orders_search'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['orders_search'],'url' => '/orders/');
        $this->navigations[] = array('name' => $this->ld['view'].$this->ld['order'],'url' => '');
        $this->navigations[] = array('name' => $order_info['Order']['order_code'],'url' => '');
        if (ereg('batch_shipping_print', $_SERVER['QUERY_STRING'])) {
            $this->change_ld($order_info['Order']['order_locale']);
        }
        $foo_1 = $this->PaymentI18n->find('first', array('conditions' => array('PaymentI18n.locale' => $order_info['Order']['order_locale'], 'PaymentI18n.payment_id' => $order_info['Order']['payment_id']), 'fields' => array('PaymentI18n.name', 'PaymentI18n.locale')));
        $order_info['Order']['payment_name'] = $foo_1['PaymentI18n']['name'];
        //供应商信息
        $purchase_order_data = $this->PurchaseOrder->find('first', array('conditions' => array('PurchaseOrder.order_code' => $order_info['Order']['order_code'])));
        $this->set('purchase_order_data', $purchase_order_data);
        if (!empty($purchase_order_data)) {
            //快递公司名称
            $logistics_companys = $this->LogisticsCompany->find('list', array('fields' => array('LogisticsCompany.id', 'LogisticsCompany.name')));
            $this->set('logistics_companys', $logistics_companys);
        }
        //资源库信息
        //$this->Resource->set_locale($this->backend_locale);
        $Resource_info = $this->Resource->getformatcode(array('order_status', 'shipping_status', 'payment_status', 'picking_type'), $this->backend_locale);
        //物流公司
//		$logistic =$this->LogisticsCompany->find('first',array('conditions'=>array('LogisticsCompany.id'=>$order_info["Order"]['logistics_company_id']),'fields'=>array('LogisticsCompany.name')));
//		$order_info["Order"]['logistic_name']=$logistic["LogisticsCompany"]['name'];
        //DAM
        $price_format = $this->configs['price_format'];
        if (isset($this->configs['mlti_currency_module']) && $this->configs['mlti_currency_module'] == 1) {
            if ($order_info['Order']['order_locale'] != ' ') {
                $price_format = $this->currency_format[$order_info['Order']['order_locale']];
            } else {
                $price_format = $this->configs['price_format'];
            }
        }
        $this->configs['price_format'] = $price_format;
        //标签
        $wh['UserAddress.regions'] = trim($order_info['Order']['regions']);
        $wh['UserAddress.user_id'] = trim($order_info['Order']['user_id']);
        $regions_names_arr = $this->UserAddress->find($wh);
        $order_info['Order']['regions_names'] = $regions_names_arr['UserAddress']['name'];
        //区域
        $regions = $order_info['Order']['regions'];
        $regions = explode(' ', $regions);
        $this->Region->set_locale($this->backend_locale);
        $regions_info = $this->Region->find('all');
        $new_regions_info = array();
        /*foreach($regions_info as $k=>$v){
            if($v["Region"]["parent_id"]==0){
                $new_regions_info[$regions[0]][$k] = $v;
            }
            if($v["Region"]["parent_id"]==@$regions[0]){
                $new_regions_info[$regions[1]][$k] = $v;
            }
            if($v["Region"]["parent_id"]==@$regions[1]){
                $new_regions_info[$regions[2]][$k] = $v;
            }
            if($v["Region"]["parent_id"]==@$regions[2]){
                $new_regions_info[$regions[3]][$k] = $v;
            }
        }*/
        //钱
        $order_info['Order']['pro_weight'] = 0;
        $product_id_arr = array();
        if (ereg('batch_shipping_print', $_SERVER['QUERY_STRING'])) {
            $foo_2 = $this->get_config_by_locale($order_info['Order']['order_locale']);
        }
        foreach ($order_info['OrderProduct'] as $k => $v) {
            $product_id_arr[] = $v['product_id'];
            $products[$k] = $this->OrderProduct->find('OrderProduct.product_id = '.$v['product_id'].'');
            $order_info['Order']['pro_weight'] += $products[$k]['Product']['weight'];
            if (ereg('batch_shipping_print', $_SERVER['QUERY_STRING'])) {
                $order_info['OrderProduct'][$k]['total'] = sprintf($foo_2['price_format'], sprintf('%01.2f', $v['product_quntity'] * $v['product_price']));
            } else {
                $order_info['OrderProduct'][$k]['total'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $v['product_quntity'] * $v['product_price']));
            }
        }
        //是否使用余额
        $balance_log_filter = '1=1';
        $balance_log_filter .= ' and UserBalanceLog.type_id = '.$id.' and UserBalanceLog.user_id = '.$order_info['Order']['user_id']." and UserBalanceLog.log_type = 'O'";
        $balance_log = $this->UserBalanceLog->find($balance_log_filter);
        $balance_log['UserBalanceLog']['amount'] = !empty($balance_log['UserBalanceLog']['amount']) ? $balance_log['UserBalanceLog']['amount'] : '0';
        //$this->CouponType->set_locale($this->backend_locale); //chenfan 2012/5/28 change
//		$coupon_info=$this->Coupon->findById($order_info["Order"]["coupon_id"]);
//		$coupon_types_info=$this->CouponType->findById($coupon_info["Coupon"]["coupon_type_id"]);
//		$order_info['Order']['coupon_fee']=$order_info['Order']['coupon_fee'];
//		$order_info['Order']['coupon_fees']=sprintf($this->configs['price_format'],sprintf("%01.2f",$order_info['Order']['coupon_fee']));
//		$order_info['Order']['coupon_type_name']=$coupon_types_info["CouponTypeI18n"]["name"];
        //$coupon_types_info=$this->CouponType->find("first",array("conditions"=>array("CouponType.id"=>$order_info["Order"]["coupon_id"])));
        if ($order_info['Order']['coupon_id'] != '') {
            $coupon_arr = explode(',', $order_info['Order']['coupon_id']);
            if (!empty($coupon_arr)) {
                $coupon_infos = $this->Coupon->find('list', array('conditions' => array('Coupon.id' => $coupon_arr), 'fields' => 'Coupon.coupon_type_id'));
                $this->set('coupon_infos', $coupon_infos);
                $coupon_type_infos = $this->CouponType->getCouponName($coupon_infos);
                $coupon_name_arr = array();
                foreach ($coupon_infos as $ci) {
                    if (!in_array($coupon_type_infos[$ci], $coupon_name_arr)) {
                        $coupon_name_arr[] = $coupon_type_infos[$ci];
                    }
                }
                $this->set('coupon_name_arr', $coupon_name_arr);
            }
        }
        //应付款金额$coupon_info
        $Order_total = $order_info['Order']['subtotal'] - $order_info['Order']['discount'] + $order_info['Order']['tax'] + $order_info['Order']['shipping_fee'] + $order_info['Order']['insure_fee'] + $order_info['Order']['payment_fee'] + $order_info['Order']['pack_fee'] + $order_info['Order']['card_fee'];
        $maney_fee = round($Order_total - $order_info['Order']['money_paid'] + $balance_log['UserBalanceLog']['amount'] - $order_info['Order']['point_fee'] - $order_info['Order']['coupon_fee'], 2);
        if (ereg('batch_shipping_print', $_SERVER['QUERY_STRING'])) {
            $order_info['Order']['amount_payable'] = sprintf($foo_2['price_format'], sprintf('%01.2f', $maney_fee));
        } else {
            $order_info['Order']['amount_payable'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $maney_fee));
        }
        //操作信息列表
        //$action_list=$this->OrderAction->findAll("OrderAction.order_id = '".$id."'",'','OrderAction.created desc');
        $action_list = $this->OrderAction->find('all', array('conditions' => "OrderAction.order_id = '".$id."'", 'order' => 'OrderAction.created desc'));
        $operator_ids = array();
        foreach ($action_list as $ak => $av) {
            array_push($operator_ids, $av['OrderAction']['from_operator_id']);
        }
        if (!empty($operator_ids)) {
            $name_list = $this->Operator->operator_name_list($operator_ids);
            $name_list['0']=$this->ld['customers'];
            foreach ($action_list as $akk => $avv) {
                foreach ($name_list as $nk => $nv) {
                    if ($avv['OrderAction']['from_operator_id'] == $nk) {
                        $action_list[$akk]['OrderAction']['operator_name'] = $nv;
                    }
                }
            }
        }
        //商品
        $this->Product->hasOne = array();
        $this->Product->hasMany = array();
        $product_img = $this->Product->find('all', array('conditions' => array('id' => $product_id_arr), 'fields' => array('img_thumb', 'id', 'market_price', 'file_url', 'product_type_id')));
        $product_img_new = array();
        foreach ($product_img as $k => $v) {
            $product_img_new[$v['Product']['id']] = $v;
        }
        //可定制的商品属性组
        $this->ProductType->set_locale($this->backend_locale);
        $customize_product_type_info = $this->ProductType->find('all', array('conditions' => array('ProductType.customize' => '1', 'ProductType.status' => 1)));
        $customize_product_type_list = array();
        foreach ($customize_product_type_info as $v) {
            if ($v['ProductType']['customize'] == '1') {
                $customize_product_type_list[] = $v['ProductType']['id'];
            }
        }
        $this->set('customize_product_type_list', $customize_product_type_list);
        //商品Id与code对应
        $all_product_code_infos = $this->Product->find('list', array('fields' => array('Product.id', 'Product.code'), 'conditions' => array('Product.id' => $product_id_arr)));
        $this->set('all_product_code_infos', $all_product_code_infos);
        $this->set('balance_log', $balance_log); //是否使用余额
        $this->set('new_regions_info', $new_regions_info);
        $this->set('product_img_new', $product_img_new);
        $this->set('regions', $regions);
        $this->set('Resource_info', $Resource_info);//
        $this->set('order_info', $order_info);//订单信息
        if (ereg('batch_shipping_print', $_SERVER['QUERY_STRING'])) {
            $this->set('price_format', $foo_2['price_format']);
        } else {
            $this->set('price_format', $this->configs['price_format']);
        }
        $this->set('action_list', $action_list);
        $this->set('all_app_codes', $this->apps['codes']);
        //发票
        $this->InvoiceType->set_locale($this->backend_locale);
        $InvoiceType = $this->InvoiceType->find('all', array('cinditions' => array('InvoiceType.status' => 1)));
        $this->set('InvoiceType', $InvoiceType);
        //物流信息判断
        $company_info = $this->LogisticsCompany->find('first', array('conditions' => array('LogisticsCompany.id' => $order_info['Order']['logistics_company_id']), 'fields' => array('LogisticsCompany.name', 'LogisticsCompany.express_code')));
        //装了快递查询的应用
        if (!empty($company_info) && isset($order_info['Order']['invoice_no']) && isset($order_info['Order']['logistics_company_id']) && $order_info['Order']['logistics_company_id'] != '') {
            $inquire_key = $this->configs['express-inquire-key'];
            if ($inquire_key != '') {
                $url = 'http://www.kuaidi100.com/api?id='.$inquire_key.'&com='.$company_info['LogisticsCompany']['express_code'].'&nu='.$order_info['Order']['invoice_no'].'&show=2&muti=1';
                $r = file_get_contents($url);
                $this->set('express_info', $r);
            }
        }
    }

    /**
     *编辑订单.
     *
     *@param int $id 输入订单ID
     *@param int $is_ajax 是不是用AJAX访问
     */
    public function get_child_dealer_id($parent_id = '')
    {
        $this->loadModel('Dealer');
        $dear_info = $this->Dealer->find('all', array('conditions' => array('Dealer.parent_id' => $parent_id)));
        if (!empty($dear_info)) {
            foreach ($dear_info as $k => $v) {
                $this->dear_id[] = $v['Dealer']['id'];
                $this->get_child_dealer_id($v['Dealer']['id']);
            }
        }
        return $this->dear_id;
    }

    public function edit($id = 0, $is_ajax = 0)
    {
        $this->operator_privilege('orders_edit');
        $this->Product->hasOne = array();
        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $id)));//订单信息
 
        if (in_array('APP-DEALER', $this->apps['codes'])) {
            $dealers = $this->get_dealer_id();
            $this->loadModel('Dealer');
            if ($this->admin['type'] == 'D') {
                //				$dealer_ids=array();
//				$dealer_ids=$this->get_child_dealer_id($this->admin['type_id']);
//				$dealer_ids[]=$this->admin['type_id'];
                $dealers_info = $this->Dealer->find('first', array('conditions' => array('id' => $this->admin['type_id'])));
                $this->set('dealers_info', $dealers_info);
            }
            $this->set('dealers', $dealers);
        }
        $order_parent = $this->Order->find('first', array('conditions' => array('Order.order_code' => $order_info['Order']['parent_order_code'])));//合并后订单信息
        $this->set('order_parent', $order_parent);
		$service_type_info = $this->Resource->getformatcode(array('order_product_service_type'), $this->locale);
        $this->set('service_type_info',$service_type_info);
        $shipping_info = $this->InformationResource->information_formated(array('shipping_way'), $this->locale);
        $this->set('shipping_info',$shipping_info);
        $order_info_code = $this->Order->find('all', array('fields' => 'Order.order_code', 'conditions' => array('Order.parent_order_code' => $order_info['Order']['order_code'])));//合并后的订单号
        $this->set('order_info_code', $order_info_code);
        //如果订单不存在 提示
        if (empty($order_info)) {
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("订单不存在！");	window.location.href="/admin/orders/"</script>';
            die();
        }
        $user_id = $order_info['Order']['user_id'];
        if (isset($order_info['Order']['user_id']) && $order_info['Order']['user_id'] != '') {
            $user_info = $this->User->find('first', array('conditions' => array('User.id' => $order_info['Order']['user_id'])));
            $this->set('user_info', $user_info);
            /*
                用户量体信息设置
            */
            $this->UserConfig->set_locale($this->backend_locale);
            $default_user_config_list = array();
            $user_config_list = array();
            $body_type_list = $this->UserConfig->find('all', array('conditions' => array('UserConfig.user_id' => array(0, $order_info['Order']['user_id']), 'type' => 'body_type')));
            foreach ($body_type_list as $k => $v) {
                if ($v['UserConfig']['user_id'] == 0) {
                    $default_user_config_list[$v['UserConfig']['code']]['name'] = $v['UserConfigI18n']['name'];
                    $default_user_config_list[$v['UserConfig']['code']]['value_type'] = $v['UserConfig']['value_type'];
                    $default_user_config_list[$v['UserConfig']['code']]['user_config_values'] = $v['UserConfigI18n']['user_config_values'];
                    $default_user_config_list[$v['UserConfig']['code']]['value'] = $v['UserConfig']['value'];
                } else {
                    $user_config_list[$v['UserConfig']['code']] = $v['UserConfig']['value'];
                }
            }
            $this->set('default_user_config_list', $default_user_config_list);
            $this->set('user_config_list', $user_config_list);

            if (!empty($user_info) && !empty($user_info['User']['admin_note2'])) {
                $discount = $user_info['User']['admin_note2'];
                $this->set('discount', $discount);
            }
        }
        $this->set('title_for_layout', $this->ld['edit_order'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['orders_search'],'url' => '/orders/');
        $this->menu_path = array('root' => '/oms/','sub' => '/orders/');
        $this->navigations[] = array('name' => $this->ld['edit_order'],'url' => '');
        $this->navigations[] = array('name' => $order_info['Order']['order_code'],'url' => '');
        if ($this->admin['type'] == 'D') {
            $this->set('dp_displaed', 0);
        }
        $this->Region->set_locale($this->backend_locale);
        $regions_info = $this->Region->find('all');
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
        //获取语言的对应关系
        $lan = $this->Language->find('all');
        if (isset($lan) && $lan != '') {
            foreach ($lan as $v) {
                $lname[$v['Language']['locale']] = $v['Language']['name'];
            }
            $this->set('lname', $lname);
        }
        //获取商品ID。。取商品图片用
        $product_id_array = array();
        //获取商品货号。。取商品销售属性用
        $order_product_code = array();
        $order_info['OrderProduct'] = isset($order_info['OrderProduct']) ? $order_info['OrderProduct'] : array();
        foreach ($order_info['OrderProduct'] as $k => $v) {
            $product_id_array[] = $v['product_id'];
            $order_product_code[$v['product_id']] = $v['product_code'];
        }
        $order_product_detail = $this->Product->order_product_detail_format_get($product_id_array);//获取商品详细
        //缩略图赋值给订单商品
        $order_package_products = array();
        foreach ($order_info['OrderProduct'] as $k => $v) {
            $order_info['OrderProduct'][$k]['img_thumb'] = empty($order_product_detail[$v['product_id']]['Product']['img_thumb']) ? '' : $order_product_detail[$v['product_id']]['Product']['img_thumb'];
            $order_info['OrderProduct'][$k]['product_type_id'] = empty($order_product_detail[$v['product_id']]['Product']['product_type_id']) ? '' : $order_product_detail[$v['product_id']]['Product']['product_type_id'];
            //套装子商品显示处理
            if ($v['parent_product_id'] != 0 && !isset($order_info['OrderProductValue'])) {
                $order_package_products[$v['parent_product_id']][$k] = $order_info['OrderProduct'][$k];
                unset($order_info['OrderProduct'][$k]);
                continue;
            }
            //查询去除套装主商品的属性
            $option_type_id = $this->Product->checkProductType($v['product_id']);
            if ($option_type_id == 1) {
                $pkg_attr_price = 0;
                foreach ($order_info['OrderProductValue'] as $opk => $opv) {
                    if ($opv['order_product_id'] == $v['id']) {
                        $pkg_attr_price += $opv['attr_price'];
                        unset($order_info['OrderProductValue'][$opk]);
                    }
                }
                $order_info['OrderProduct'][$k]['pkg_attr_price'] = $pkg_attr_price;
            }
            /*
            if(isset($sku_product_list[$v['product_id']])&&!empty($sku_product_list[$v['product_id']]['sku_product'])){
                //销售属性显示处理
                $order_info['OrderProduct'][$k]['sku_product']=$sku_product_list[$v['product_id']]['sku_product'];
            }
            */
        }
        $this->set('order_package_products', $order_package_products);
        $this->set('order_info', $order_info);
        $pro_type_attr_info = $this->Attribute->find('all', array('conditions' => array('Attribute.status' => 1), 'order' => 'Attribute.id'));
        $this->set('pro_type_attr_info',$pro_type_attr_info);
        if ($order_info['Order']['coupon_id'] != '') {
            $coupon_arr = explode(',', $order_info['Order']['coupon_id']);
            if (!empty($coupon_arr)) {
                $coupon_infos = $this->Coupon->find('list', array('conditions' => array('Coupon.id' => $coupon_arr), 'fields' => 'Coupon.coupon_type_id'));
                $this->set('coupon_infos', $coupon_infos);
                $coupon_type_infos = $this->CouponType->getCouponName($coupon_infos);
                $coupon_name_arr = array();
                foreach ($coupon_infos as $ci) {
                    if (!in_array($coupon_type_infos[$ci], $coupon_name_arr)) {
                        $coupon_name_arr[] = $coupon_type_infos[$ci];
                    }
                }
                $this->set('coupon_name_arr', $coupon_name_arr);
            }
        }
        //发货地址
        $order_shipment_address = $this->OrderShipment->find('first',array('conditions'=>array('OrderShipment.order_id'=>$id,'OrderShipment.status'=>0)));
        $this->set('order_shipment_address',$order_shipment_address);
        //淘宝订单信息
        if ($order_info['Order']['type'] == 'taobao') {
            $this->loadModel('TaobaoOrder');
            $this->loadModel('TaobaoRefund');
            $this->loadModel('TaobaoTrade');
            $taobao_order_info = $this->TaobaoOrder->find('all', array('conditions' => array('TaobaoOrder.tid' => $order_info['Order']['order_code']), 'fields' => array('price', 'num', 'adjust_fee', 'discount_fee')));
            $taobao_order_item_num = 0;
            $taobao_subtotal = 0;
            foreach ($taobao_order_info as $tk => $tv) {
                $taobao_order_item_num += $tv['TaobaoOrder']['num'];
                $taobao_subtotal += $tv['TaobaoOrder']['price'] * $tv['TaobaoOrder']['num'] + $tv['TaobaoOrder']['adjust_fee'] - $tv['TaobaoOrder']['discount_fee'];
            }
            $this->set('taobao_item_num', $taobao_order_item_num);
            $this->set('taobao_subtotal', $taobao_subtotal);
        }
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
        $l_c_list = array();
        if (!empty($logistics_company_list)) {
            foreach ($logistics_company_list as $v) {
                $l_c_list[$v['LogisticsCompany']['id']] = $v['LogisticsCompany']['name'];
            }
            $this->set('l_c_list', $l_c_list);
        }
        //发票
        $invoice_type_list = $this->InvoiceType->invoice_type_list($this->locale);
        $this->set('invoice_type_list', $invoice_type_list);
        $user_addresses_array = '';
        //资金日志 余额
        if ($user_id != '' && $user_id != 0) {
            $order_user_balance_log_info = $this->UserBalanceLog->order_user_balance_log_info($order_info['Order']['id'], $order_info['Order']['user_id']);
            $this->set('order_user_balance_log_info', $order_user_balance_log_info);
            //用户地址簿
            $user_addresses_array = $this->UserAddress->user_addresses_get($user_id);
        }
        //操作日志
        $order_action_list = $this->OrderAction->order_action_list($id);
        $order_action_operator_name = $this->Operator->find('list',array('fields' => array('Operator.id','Operator.name')));
        $order_action_operator_name[0]=$this->ld['customers'];
        //操作员name添加
        foreach ($order_action_list as $k => $v) {
            $order_action_list[$k]['OrderAction']['name'] = isset($order_action_operator_name[$v['OrderAction']['from_operator_id']])?$order_action_operator_name[$v['OrderAction']['from_operator_id']]:'';
        }
        $this->set('order_action_list', $order_action_list);
        //供应商信息
        $purchase_order_data = $this->PurchaseOrder->find('first', array('conditions' => array('PurchaseOrder.order_code' => $order_info['Order']['order_code'])));
        $this->set('purchase_order_data', $purchase_order_data);
        //去所有的品牌
        $this->Brand->set_locale($this->backend_locale);
        $brands = $this->Brand->find('all', array('order' => 'Brand.code,Brand.orderby'));
        $this->set('brands', $brands);
        //商品属性组
        $product_type_tree = $this->ProductType->product_type_tree($this->backend_locale);
        $this->set('product_type_tree', $product_type_tree);
        //可定制的商品属性组
        $this->ProductType->set_locale($this->backend_locale);
        $customize_product_type_info = $this->ProductType->find('all', array('conditions' => array('ProductType.customize' => '1', 'ProductType.status' => 1)));
        $customize_product_type_list = array();
        foreach ($customize_product_type_info as $v) {
            if ($v['ProductType']['customize'] == '1') {
                $customize_product_type_list[] = $v['ProductType']['id'];
            }
        }
        $this->set('customize_product_type_list', $customize_product_type_list);
        //取出id对应区域名称
        $rnames = $this->RegionI18n->getNames($this->locale);
        $OrderProductInfos = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $id), 'fields' => 'OrderProduct.id,OrderProduct.product_id,OrderProduct.product_code'));
        $order_product_ids=array();
        $OrderProductIds = array();
        $OrderProductCodes = array();
        foreach ($OrderProductInfos as $k => $v) {
            $order_product_ids[]=$v['OrderProduct']['id'];
            $OrderProductIds[$v['OrderProduct']['product_id']] = $v['OrderProduct']['product_id'];
            $OrderProductCodes[$v['OrderProduct']['product_code']] = $v['OrderProduct']['product_code'];
        }
        //商品Id与code对应
        $all_product_code_infos = $this->Product->find('list', array('fields' => array('Product.id', 'Product.code'), 'conditions' => array('Product.id' => $OrderProductIds)));
        //$all_product_infos=$this->Product->getIdPrices($OrderProductIds);
        $all_product_quantity_infos = $this->Product->getCodeQuantities($OrderProductCodes);
        $all_product_infos = $this->Product->getOrderProductPriceList($OrderProductIds, $OrderProductCodes);
        $this->loadModel('OrderProductMedia');
        $order_product_medias=$this->OrderProductMedia->find('all',array('conditions'=>array('OrderProductMedia.order_id'=>$id,'OrderProductMedia.media_group'=>2)));
        $this->set('order_product_medias',$order_product_medias);
        /*
                //取出商品id 和 price 的到对应关系
                $OrderProductIds=$this->OrderProduct->find('list',array('conditions'=>array('OrderProduct.order_id'=>$id),'fields'=>"OrderProduct.product_id,OrderProduct.product_id"));

                $all_product_infos=$this->Product->getIdPrices($OrderProductIds);
                $all_product_quantity_infos=$this->Product->getIdQuantities($OrderProductIds);
                */
        $this->set('all_product_code_infos', $all_product_code_infos);
        $this->set('all_product_quantity_infos', $all_product_quantity_infos);
        $this->set('all_product_infos', $all_product_infos);
        $this->set('rnames', $rnames);
        $this->set('regions_info3', $regions_info2);
        $this->set('regions_info', json_encode($regions_info2));
        $this->set('regions_infovalues', $regions_infovalues);
        $this->set('user_addresses_json', json_encode($user_addresses_array));
        $this->set('user_addresses_array', $user_addresses_array);
        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('order_status', 'shipping_status', 'payment_status', 'picking_type','order_service_type','order_product_service_type','order_product_status'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
        $information_resources_info = $this->InformationResource->information_formated(array('how_oos', 'best_time'), $this->locale);
        $this->set('information_resources_info', $information_resources_info);
        $this->set('order_action', $this->operable_list($order_info));//操作状态
        $this->set('all_app_codes', $this->apps['codes']);
        //物流信息判断
//       	$company_info=$this->LogisticsCompany->find('first',array('conditions'=>array('LogisticsCompany.id'=>$order_info['Order']['logistics_company_id']),'fields'=>array('LogisticsCompany.name','LogisticsCompany.express_code')));
//		//装了快递查询的应用
//		if(!empty($company_info)&&in_array('APP-EXPRESS-INQUIRE',$this->apps['codes'])&&isset($order_info['Order']['invoice_no'])&&isset($order_info['Order']['logistics_company_id'])&&$order_info['Order']['logistics_company_id']!=""){
//				$inquire_key=$this->apps['Applications']['APP-EXPRESS-INQUIRE']['configs']['APP-EXPRESS-INQUIRE-KEY'];
//				if($inquire_key!=""){
//					$url="http://www.kuaidi100.com/api?id=".$inquire_key."&com=".$company_info['LogisticsCompany']['express_code']."&nu=".$order_info['Order']['invoice_no']."&show=2&muti=1";
//					$r = file_get_contents($url);
//					$this->set('express_info',$r);
//				}
//		}
        //仓库
        if (in_array('APP-WAREHOUSE', $this->apps['codes'])) {
            $ware_condition = array();
            $ware_condition['Warehouse.status'] = 1;
            $ware_condition['Warehouse.allow_status'] = 1;
            if ($order_info['Order']['type'] == 'taobao') {
                $ware_condition['Warehouse.type'] = 'C';
                $ware_condition['Warehouse.type_id'] = 'taobao';
            } elseif ($order_info['Order']['type'] == 'jingdong') {
                $ware_condition['Warehouse.type'] = 'C';
                $ware_condition['Warehouse.type_id'] = 'jingdong';
            }
            $warehouse_list = $this->Warehouse->find('all', array('conditions' => $ware_condition));
            $warehouse_list = $this->Warehouse->ware_operator($warehouse_list, $this->admin['id']);
            $this->set('warehouse_list', $warehouse_list);
        }
        //查看仓储信息
        if (in_array('APP-WAREHOUSE', $this->apps['codes'])) {
            $stockProductInfo = '';
            $OrderProductCodes = $this->OrderProduct->find('list', array('conditions' => array('OrderProduct.order_id' => $id), 'fields' => 'OrderProduct.product_code,OrderProduct.product_id'));
            $warehouse_all_list = $this->Warehouse->find('all', array('conditions' => array('Warehouse.status' => 1)));
            foreach ($warehouse_all_list as $w) {
                $warehouseInfos[$w['Warehouse']['code']] = $w['Warehouse']['warehouse_name'];
            }
            $stockProductQuantityInfo = '';
            if (!empty($OrderProductCodes)) {
                foreach ($OrderProductCodes as $k => $v) {
                    //if(in_array($v,$this->get_xu_list())){
                    //	continue;//虚拟库存不显示
                    //}
                    $stockInfo = $this->Stock->find('all', array('conditions' => array('Stock.product_code' => $k), 'recursive' => -1));
                    if (!empty($stockInfo)) {
                        $stockProductQuantityInfo[$v] = 0;
                        foreach ($stockInfo  as $vv) {
                            if (isset($warehouseInfos[$vv['Stock']['warehouse_code']])) {
                                $vv['Stock']['warehouse_name'] = $warehouseInfos[$vv['Stock']['warehouse_code']];
                                $stockProductInfo[$v][] = $vv;
                                $stockProductQuantityInfo[$v] += $vv['Stock']['quantity'];
                            }
                        }
                    }
                }
            }
            $this->set('stockProductQuantityInfo', $stockProductQuantityInfo);
            $this->set('stockProductInfo', $stockProductInfo);
            if ($this->Application->config('APP-WAREHOUSE', 'APP-WAREHOUSE-REFUND-INBOUND')) {
                $refund_warehouse = $this->Warehouse->ware_operator($warehouse_all_list, $this->admin['id']);
                $this->set('refund_warehouse', $refund_warehouse);
            }
        }
        $code = $this->Payment->find('first', array('conditions' => array('Payment.id' => $order_info['Order']['payment_id']), 'fields' => array('Payment.code', 'Payment.config')));
        if ($code['Payment']['code'] == 'pos_pay' || $code['Payment']['code'] == 'bank_trans') {
            $y = array();
            $x = $code['Payment']['config'];
            $x = unserialize($x);
            if (isset($x['bank']['bb'])) {
                unset($x['bank']['bb']);
            }
            if (isset($x['bank']) && !empty($x['bank']) && isset($x['bank'][0]) && !empty($x['bank'][0])) {
                $this->set('banks', $x['bank']);
            }
        }
        //订单来源
        $this->Orderfrom->get($this, 1);
        if ($is_ajax == 1) {
            $this->layout = 'ajax';
            Configure::write('debug', 0);
        }
        $this->set('is_ajax', $is_ajax);

        $operator_list = $this->Operator->find('list',array('fields' => array('Operator.id','Operator.name'),'conditions'=>array('Operator.status'=>'1')));
        $this->set('operator_list',$operator_list);

        //上门服务订单
        if(isset($order_info['Order']['service_type'])&&$order_info['Order']['service_type']=='appointment'){
            $sub_order_product_info=$this->OrderProduct->find('all',array('fields'=>'OrderProduct.parent_product_id,sum(product_quntity) as sub_total','conditions'=>array('OrderProduct.order_id'=>$id,'OrderProduct.parent_product_id >'=>0),'group'=>'OrderProduct.parent_product_id'));
            if(!empty($sub_order_product_info)){
                $sub_order_product_list=array();
                foreach($sub_order_product_info as $v){
                    $sub_order_product_list[$v['OrderProduct']['parent_product_id']]=$v[0]['sub_total'];
                }
                $this->set('sub_order_product_list',$sub_order_product_list);
            }
        }
	
        $order_shipment_info=$this->OrderShipment->find('all',array('conditions'=>array('OrderShipment.order_id'=>$id,'OrderShipment.status <>'=>'0'),'order'=>'OrderShipment.created desc'));
        $address_info = array();
        $address_info_each = array();
        foreach ($order_shipment_info as $k1 => $v1){
           $address_info_each['id']=$v1['OrderShipment']['id'];
           $address_info_each['country']=$v1['OrderShipment']['country'];
           $address_info_each['province']=$v1['OrderShipment']['province'];
           $address_info_each['city']=$v1['OrderShipment']['city'];
           $address_info[]=$address_info_each;
        }
        //pr($address_info);
        $regions_list = array();
        foreach ($regions_info as $k11 => $v11) {
            $regions_list[$v11['Region']['id']] = $v11['RegionI18n']['name'];
        }
        $this->set('regions_list',$regions_list);
        //pr($regions_list);
        if(!empty($order_shipment_info)){
            $order_shipment_ids=array();
            foreach($order_shipment_info as $v)$order_shipment_ids[]=$v['OrderShipment']['id'];
            $order_shipment_product_info=$this->OrderShipmentProduct->find('all',array('conditions'=>array('OrderShipmentProduct.order_shipment_id'=>$order_shipment_ids),'order'=>'OrderShipmentProduct.created desc'));
            $order_shipment_product_data=array();
            foreach($order_shipment_product_info as $v){
                $order_shipment_product_data[$v['OrderShipmentProduct']['order_shipment_id']][]=$v;
            }
            $this->set('order_shipment_info',$order_shipment_info);
            $this->set('order_shipment_product_data',$order_shipment_product_data);
            //pr($order_shipment_product_data);
        }
	if($order_info['Order']['service_type']=='appointment'){
		$enable_order_manager_cond=array();
		$enable_order_manager_cond['Order.id <>']=$id;
		$enable_order_manager_cond['Order.shipping_status']=6;
		$enable_order_manager_cond['Order.order_manager >']=0;
		$enable_order_manager_cond['Order.best_time']=$order_info['Order']['best_time'];
		$orders_list_manager = $this->Order->find('list',array('fields'=>'Order.id,Order.order_manager','conditions'=>$enable_order_manager_cond));
		$this->set('orders_list_manager',$orders_list_manager);
	}
    }

    public function lease_edit($id = 0, $is_ajax = 0)
    {
        $this->operator_privilege('lease_orders_edit');
        $this->Product->hasOne = array();
        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $id)));//订单信息
        if (in_array('APP-DEALER', $this->apps['codes'])) {
            $dealers = $this->get_dealer_id();
            $this->loadModel('Dealer');
            if ($this->admin['type'] == 'D') {
                //				$dealer_ids=array();
//				$dealer_ids=$this->get_child_dealer_id($this->admin['type_id']);
//				$dealer_ids[]=$this->admin['type_id'];
                $dealers_info = $this->Dealer->find('first', array('conditions' => array('id' => $this->admin['type_id'])));
                $this->set('dealers_info', $dealers_info);
            }
            $this->set('dealers', $dealers);
        }
        $order_parent = $this->Order->find('first', array('conditions' => array('Order.order_code' => $order_info['Order']['parent_order_code'])));//合并后订单信息
        $this->set('order_parent', $order_parent);
        $order_info_code = $this->Order->find('all', array('fields' => 'Order.order_code', 'conditions' => array('Order.parent_order_code' => $order_info['Order']['order_code'])));//合并后的订单号
        $this->set('order_info_code', $order_info_code);
        //如果订单不存在 提示
        if (empty($order_info)) {
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("订单不存在！");	window.location.href="/admin/orders/"</script>';
            die();
        }
        $user_id = $order_info['Order']['user_id'];
        if (isset($order_info['Order']['user_id']) && $order_info['Order']['user_id'] != '') {
            $user_info = $this->User->find('first', array('conditions' => array('User.id' => $order_info['Order']['user_id'])));
            $this->set('user_info', $user_info);
            /*
                用户量体信息设置
            */
            $this->UserConfig->set_locale($this->backend_locale);
            $default_user_config_list = array();
            $user_config_list = array();
            $body_type_list = $this->UserConfig->find('all', array('conditions' => array('UserConfig.user_id' => array(0, $order_info['Order']['user_id']), 'type' => 'body_type')));
            foreach ($body_type_list as $k => $v) {
                if ($v['UserConfig']['user_id'] == 0) {
                    $default_user_config_list[$v['UserConfig']['code']]['name'] = $v['UserConfigI18n']['name'];
                    $default_user_config_list[$v['UserConfig']['code']]['value_type'] = $v['UserConfig']['value_type'];
                    $default_user_config_list[$v['UserConfig']['code']]['user_config_values'] = $v['UserConfigI18n']['user_config_values'];
                    $default_user_config_list[$v['UserConfig']['code']]['value'] = $v['UserConfig']['value'];
                } else {
                    $user_config_list[$v['UserConfig']['code']] = $v['UserConfig']['value'];
                }
            }
            $this->set('default_user_config_list', $default_user_config_list);
            $this->set('user_config_list', $user_config_list);

            if (!empty($user_info) && !empty($user_info['User']['admin_note2'])) {
                $discount = $user_info['User']['admin_note2'];
                $this->set('discount', $discount);
            }
        }
        $this->set('title_for_layout', $this->ld['edit_order'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->menu_path = array('root' => '/oms/','sub' => '/orders/lease_order');
        $this->navigations[] = array('name' => $this->ld['lease_order_man'],'url' => '/orders/lease_order');
        $this->navigations[] = array('name' => $this->ld['edit_order'],'url' => '');
        $this->navigations[] = array('name' => $order_info['Order']['order_code'],'url' => '');
        if ($this->admin['type'] == 'D') {
            $this->set('dp_displaed', 0);
        }
        $this->Region->set_locale($this->backend_locale);
        $regions_info = $this->Region->find('all');
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
        //获取语言的对应关系
        $lan = $this->Language->find('all');
        if (isset($lan) && $lan != '') {
            foreach ($lan as $v) {
                $lname[$v['Language']['locale']] = $v['Language']['name'];
            }
            $this->set('lname', $lname);
        }
        //获取商品ID。。取商品图片用
        $product_id_array = array();
        //获取商品货号。。取商品销售属性用
        $order_product_code = array();
        $order_info['OrderProduct'] = isset($order_info['OrderProduct']) ? $order_info['OrderProduct'] : array();
        foreach ($order_info['OrderProduct'] as $k => $v) {
            $product_id_array[] = $v['product_id'];
            $order_product_code[$v['product_id']] = $v['product_code'];
        }
        $order_product_detail = $this->Product->order_product_detail_format_get($product_id_array);//获取商品详细
        //缩略图赋值给订单商品
        $order_package_products = array();
        foreach ($order_info['OrderProduct'] as $k => $v) {
            //租赁
            if($v["lease_type"]=="L"&&$order_info['Order']['shipping_time']!="2008-01-01 00:00:00"&&$v["expire_date"]=="2008-01-01 00:00:00"){
                $day=$v["lease_unit"];
                $start_time=$order_info['Order']['shipping_time'];
                $order_info['OrderProduct'][$k]['expire_date']=date("Y-m-d H:i:s", strtotime("$start_time +$day day"));
                $this->OrderProduct->saveAll(array('OrderProduct' => $order_info['OrderProduct'][$k]));
            }
            $lease_info= $this->ProductLease->find('first', array('conditions' => array('ProductLease.product_code' => $v['product_code'])));
            $order_info['OrderProduct'][$k]['base_unit']=$lease_info["ProductLease"]["unit"];
            $order_info['OrderProduct'][$k]['lease_price']=$lease_info["ProductLease"]["lease_price"];
            $order_info['OrderProduct'][$k]['img_thumb'] = empty($order_product_detail[$v['product_id']]['Product']['img_thumb']) ? '' : $order_product_detail[$v['product_id']]['Product']['img_thumb'];
            $order_info['OrderProduct'][$k]['product_type_id'] = empty($order_product_detail[$v['product_id']]['Product']['product_type_id']) ? '' : $order_product_detail[$v['product_id']]['Product']['product_type_id'];
            //套装子商品显示处理
            if ($v['parent_product_id'] != 0 && !isset($order_info['OrderProductValue'])) {
                $order_package_products[$v['parent_product_id']][$k] = $order_info['OrderProduct'][$k];
                unset($order_info['OrderProduct'][$k]);
                continue;
            }
            //查询去除套装主商品的属性
            $option_type_id = $this->Product->checkProductType($v['product_id']);
            if ($option_type_id == 1) {
                $pkg_attr_price = 0;
                foreach ($order_info['OrderProductValue'] as $opk => $opv) {
                    if ($opv['order_product_id'] == $v['id']) {
                        $pkg_attr_price += $opv['attr_price'];
                        unset($order_info['OrderProductValue'][$opk]);
                    }
                }
                $order_info['OrderProduct'][$k]['pkg_attr_price'] = $pkg_attr_price;
            }
            /*
            if(isset($sku_product_list[$v['product_id']])&&!empty($sku_product_list[$v['product_id']]['sku_product'])){
                //销售属性显示处理
                $order_info['OrderProduct'][$k]['sku_product']=$sku_product_list[$v['product_id']]['sku_product'];
            }
            */
        }
        $this->set('order_package_products', $order_package_products);
        $this->set('order_info', $order_info);
        if ($order_info['Order']['coupon_id'] != '') {
            $coupon_arr = explode(',', $order_info['Order']['coupon_id']);
            if (!empty($coupon_arr)) {
                $coupon_infos = $this->Coupon->find('list', array('conditions' => array('Coupon.id' => $coupon_arr), 'fields' => 'Coupon.coupon_type_id'));
                $this->set('coupon_infos', $coupon_infos);
                $coupon_type_infos = $this->CouponType->getCouponName($coupon_infos);
                $coupon_name_arr = array();
                foreach ($coupon_infos as $ci) {
                    if (!in_array($coupon_type_infos[$ci], $coupon_name_arr)) {
                        $coupon_name_arr[] = $coupon_type_infos[$ci];
                    }
                }
                $this->set('coupon_name_arr', $coupon_name_arr);
            }
        }
        //淘宝订单信息
        if ($order_info['Order']['type'] == 'taobao') {
            $this->loadModel('TaobaoOrder');
            $this->loadModel('TaobaoRefund');
            $this->loadModel('TaobaoTrade');
            $taobao_order_info = $this->TaobaoOrder->find('all', array('conditions' => array('TaobaoOrder.tid' => $order_info['Order']['order_code']), 'fields' => array('price', 'num', 'adjust_fee', 'discount_fee')));
            $taobao_order_item_num = 0;
            $taobao_subtotal = 0;
            foreach ($taobao_order_info as $tk => $tv) {
                $taobao_order_item_num += $tv['TaobaoOrder']['num'];
                $taobao_subtotal += $tv['TaobaoOrder']['price'] * $tv['TaobaoOrder']['num'] + $tv['TaobaoOrder']['adjust_fee'] - $tv['TaobaoOrder']['discount_fee'];
            }
            $this->set('taobao_item_num', $taobao_order_item_num);
            $this->set('taobao_subtotal', $taobao_subtotal);
        }
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
        $l_c_list = array();
        if (!empty($logistics_company_list)) {
            foreach ($logistics_company_list as $v) {
                $l_c_list[$v['LogisticsCompany']['id']] = $v['LogisticsCompany']['name'];
            }
            $this->set('l_c_list', $l_c_list);
        }
        //发票
        $invoice_type_list = $this->InvoiceType->invoice_type_list($this->locale);
        $this->set('invoice_type_list', $invoice_type_list);
        $user_addresses_array = '';
        //资金日志 余额
        if ($user_id != '' && $user_id != 0) {
            $order_user_balance_log_info = $this->UserBalanceLog->order_user_balance_log_info($order_info['Order']['id'], $order_info['Order']['user_id']);
            $this->set('order_user_balance_log_info', $order_user_balance_log_info);
            //用户地址簿
            $user_addresses_array = $this->UserAddress->user_addresses_get($user_id);
        }
        //操作日志
        $order_action_list = $this->OrderAction->order_action_list($id);
        $order_action_operator_name = $this->Operator->find('list',array('fields' => array('Operator.id','Operator.name')));
        $order_action_operator_name[0]=$this->ld['customers'];
        //操作员name添加
        foreach ($order_action_list as $k => $v) {
            $order_action_list[$k]['OrderAction']['name'] = isset($order_action_operator_name[$v['OrderAction']['from_operator_id']])?$order_action_operator_name[$v['OrderAction']['from_operator_id']]:'';
        }
        $this->set('order_action_list', $order_action_list);
        //供应商信息
        $purchase_order_data = $this->PurchaseOrder->find('first', array('conditions' => array('PurchaseOrder.order_code' => $order_info['Order']['order_code'])));
        $this->set('purchase_order_data', $purchase_order_data);
        //去所有的品牌
        $this->Brand->set_locale($this->backend_locale);
        $brands = $this->Brand->find('all', array('order' => 'Brand.code,Brand.orderby'));
        $this->set('brands', $brands);
        //商品属性组
        $product_type_tree = $this->ProductType->product_type_tree($this->backend_locale);
        $this->set('product_type_tree', $product_type_tree);
        //可定制的商品属性组
        $this->ProductType->set_locale($this->backend_locale);
        $customize_product_type_info = $this->ProductType->find('all', array('conditions' => array('ProductType.customize' => '1', 'ProductType.status' => 1)));
        $customize_product_type_list = array();
        foreach ($customize_product_type_info as $v) {
            if ($v['ProductType']['customize'] == '1') {
                $customize_product_type_list[] = $v['ProductType']['id'];
            }
        }
        $this->set('customize_product_type_list', $customize_product_type_list);
        //取出id对应区域名称
        $rnames = $this->RegionI18n->getNames($this->locale);
        $OrderProductInfos = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $id), 'fields' => 'OrderProduct.product_id,OrderProduct.product_code'));
        $OrderProductIds = array();
        $OrderProductCodes = array();
        foreach ($OrderProductInfos as $k => $v) {
            $OrderProductIds[$v['OrderProduct']['product_id']] = $v['OrderProduct']['product_id'];
            $OrderProductCodes[$v['OrderProduct']['product_code']] = $v['OrderProduct']['product_code'];
        }
        //商品Id与code对应
        $all_product_code_infos = $this->Product->find('list', array('fields' => array('Product.id', 'Product.code'), 'conditions' => array('Product.id' => $OrderProductIds)));
        //$all_product_infos=$this->Product->getIdPrices($OrderProductIds);
        $all_product_quantity_infos = $this->Product->getCodeQuantities($OrderProductCodes);
        $all_product_infos = $this->Product->getOrderProductPriceList($OrderProductIds, $OrderProductCodes);
        /*
                //取出商品id 和 price 的到对应关系
                $OrderProductIds=$this->OrderProduct->find('list',array('conditions'=>array('OrderProduct.order_id'=>$id),'fields'=>"OrderProduct.product_id,OrderProduct.product_id"));

                $all_product_infos=$this->Product->getIdPrices($OrderProductIds);
                $all_product_quantity_infos=$this->Product->getIdQuantities($OrderProductIds);
                */
        $this->set('all_product_code_infos', $all_product_code_infos);
        $this->set('all_product_quantity_infos', $all_product_quantity_infos);
        $this->set('all_product_infos', $all_product_infos);
        $this->set('rnames', $rnames);
        $this->set('regions_info3', $regions_info2);
        $this->set('regions_info', json_encode($regions_info2));
        $this->set('regions_infovalues', $regions_infovalues);
        $this->set('user_addresses_json', json_encode($user_addresses_array));
        $this->set('user_addresses_array', $user_addresses_array);
        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('order_status', 'shipping_status', 'payment_status', 'picking_type'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
        $information_resources_info = $this->InformationResource->information_formated(array('how_oos', 'best_time'), $this->locale);
        $this->set('information_resources_info', $information_resources_info);
        $this->set('order_action', $this->operable_list($order_info));//操作状态
        $this->set('all_app_codes', $this->apps['codes']);
        //物流信息判断
//       	$company_info=$this->LogisticsCompany->find('first',array('conditions'=>array('LogisticsCompany.id'=>$order_info['Order']['logistics_company_id']),'fields'=>array('LogisticsCompany.name','LogisticsCompany.express_code')));
//		//装了快递查询的应用
//		if(!empty($company_info)&&in_array('APP-EXPRESS-INQUIRE',$this->apps['codes'])&&isset($order_info['Order']['invoice_no'])&&isset($order_info['Order']['logistics_company_id'])&&$order_info['Order']['logistics_company_id']!=""){
//				$inquire_key=$this->apps['Applications']['APP-EXPRESS-INQUIRE']['configs']['APP-EXPRESS-INQUIRE-KEY'];
//				if($inquire_key!=""){
//					$url="http://www.kuaidi100.com/api?id=".$inquire_key."&com=".$company_info['LogisticsCompany']['express_code']."&nu=".$order_info['Order']['invoice_no']."&show=2&muti=1";
//					$r = file_get_contents($url);
//					$this->set('express_info',$r);
//				}
//		}
        //仓库
        if (in_array('APP-WAREHOUSE', $this->apps['codes'])) {
            $ware_condition = array();
            $ware_condition['Warehouse.status'] = 1;
            $ware_condition['Warehouse.allow_status'] = 1;
            if ($order_info['Order']['type'] == 'taobao') {
                $ware_condition['Warehouse.type'] = 'C';
                $ware_condition['Warehouse.type_id'] = 'taobao';
            } elseif ($order_info['Order']['type'] == 'jingdong') {
                $ware_condition['Warehouse.type'] = 'C';
                $ware_condition['Warehouse.type_id'] = 'jingdong';
            }
            $warehouse_list = $this->Warehouse->find('all', array('conditions' => $ware_condition));
            $warehouse_list = $this->Warehouse->ware_operator($warehouse_list, $this->admin['id']);
            $this->set('warehouse_list', $warehouse_list);
        }
        //查看仓储信息
        if (in_array('APP-WAREHOUSE', $this->apps['codes'])) {
            $stockProductInfo = '';
            $OrderProductCodes = $this->OrderProduct->find('list', array('conditions' => array('OrderProduct.order_id' => $id), 'fields' => 'OrderProduct.product_code,OrderProduct.product_id'));
            $warehouse_all_list = $this->Warehouse->find('all', array('conditions' => array('Warehouse.status' => 1)));
            foreach ($warehouse_all_list as $w) {
                $warehouseInfos[$w['Warehouse']['code']] = $w['Warehouse']['warehouse_name'];
            }
            $stockProductQuantityInfo = '';
            if (!empty($OrderProductCodes)) {
                foreach ($OrderProductCodes as $k => $v) {
                    //if(in_array($v,$this->get_xu_list())){
                    //	continue;//虚拟库存不显示
                    //}
                    $stockInfo = $this->Stock->find('all', array('conditions' => array('Stock.product_code' => $k), 'recursive' => -1));
                    if (!empty($stockInfo)) {
                        $stockProductQuantityInfo[$v] = 0;
                        foreach ($stockInfo  as $vv) {
                            if (isset($warehouseInfos[$vv['Stock']['warehouse_code']])) {
                                $vv['Stock']['warehouse_name'] = $warehouseInfos[$vv['Stock']['warehouse_code']];
                                $stockProductInfo[$v][] = $vv;
                                $stockProductQuantityInfo[$v] += $vv['Stock']['quantity'];
                            }
                        }
                    }
                }
            }
            $this->set('stockProductQuantityInfo', $stockProductQuantityInfo);
            $this->set('stockProductInfo', $stockProductInfo);
            if ($this->Application->config('APP-WAREHOUSE', 'APP-WAREHOUSE-REFUND-INBOUND')) {
                $refund_warehouse = $this->Warehouse->ware_operator($warehouse_all_list, $this->admin['id']);
                $this->set('refund_warehouse', $refund_warehouse);
            }
        }
        $code = $this->Payment->find('first', array('conditions' => array('Payment.id' => $order_info['Order']['payment_id']), 'fields' => array('Payment.code', 'Payment.config')));
        if ($code['Payment']['code'] == 'pos_pay' || $code['Payment']['code'] == 'bank_trans') {
            $y = array();
            $x = $code['Payment']['config'];
            $x = unserialize($x);
            if (isset($x['bank']['bb'])) {
                unset($x['bank']['bb']);
            }
            if (isset($x['bank']) && !empty($x['bank']) && isset($x['bank'][0]) && !empty($x['bank'][0])) {
                $this->set('banks', $x['bank']);
            }
        }
        //订单来源
        $this->Orderfrom->get($this, 1);
        if ($is_ajax == 1) {
            $this->layout = 'ajax';
            Configure::write('debug', 0);
        }
        $this->set('is_ajax', $is_ajax);
    }

//	function express_inquire(){
//		$logistics_company_id = $_REQUEST['logistics_company_id'];
//		$invoice_no = $_REQUEST['invoice_no'];
//		$timestamp = $_REQUEST['timestamp'];
//		$result["flag"] = 0;
//	    $company_info=$this->LogisticsCompany->find('first',array('conditions'=>array('LogisticsCompany.id'=>$logistics_company_id),'fields'=>array('LogisticsCompany.name','LogisticsCompany.express_code')));
//		//装了快递查询的应用
//			if(!empty($company_info)&&!empty($invoice_no)&&!empty($logistics_company_id)){
//				$url="http://www.kuaidi100.com/query?type=".$company_info['LogisticsCompany']['express_code']."&postid=".$invoice_no."";
//				$content = file_get_contents($url);
//
//			//	echo $url;
//				$express_info=json_decode($content);
//			//	pr($express_info);
//				if($express_info->message=='ok'){
//					$return['flag']=1;
//					$return['message']='';
//					foreach($express_info->data as $k=>$v){
//						$return['message'].=$v->time." =>".$v->context."<br />";
//					}
//
//				}else{
//					$return['flag']=1;
//					$return['message']=$express_info->message;
//				}
//			}
//		die(json_encode($return));
//	}

    public function export_flag_button()
    {
        $order_id = $_REQUEST['order_id'];
        $result['flag'] = 0;
        if (in_array('APP-API-WEBSERVICE', $this->apps['codes'])) {
            $order_info = $this->Order->find('first', array('fileds' => array('order_code,export_flag'), 'conditions' => array('Order.id' => $order_id)));
            if (!empty($order_info)) {
                $this->EcFlagWebservice->startup($this);
                $get_order_detail = $this->EcFlagWebservice->GetOrderDetail($order_info['Order']['order_code']);
                $get_order_detail_array1 = explode(',', $get_order_detail['GetOrderDetailResult']);
                $order_info_detail = explode('|', $get_order_detail['GetOrderDetailResult']);
                if (isset($order_info_detail[2])) {
                    if ($order_info_detail[2] == 0) {
                        $order_info_detail[2] = '0     订单异常';
                    }
                    if ($order_info_detail[2] == 100) {
                        $order_info_detail[2] = '100     导入完成';
                    }
                    if ($order_info_detail[2] == 101) {
                        $order_info_detail[2] = '101     等待审核';
                    }
                    if ($order_info_detail[2] == 110) {
                        $order_info_detail[2] = '110     正在取货';
                    }
                    if ($order_info_detail[2] == 120) {
                        $order_info_detail[2] = '120     装箱完成';
                    }
                    if ($order_info_detail[2] == 130) {
                        $order_info_detail[2] = '130     等待出运';
                    }
                    if ($order_info_detail[2] == 135) {
                        $order_info_detail[2] = '135     出运完成';
                    }
                    if ($order_info_detail[2] == 140) {
                        $order_info_detail[2] = '140     订单合并';
                    }
                    if ($order_info_detail[2] == 150) {
                        $order_info_detail[2] = '150     缺货待定';
                    }
                    if ($order_info_detail[2] == 151) {
                        $order_info_detail[2] = '151     缺货退单';
                    }
                    if ($order_info_detail[2] == 152) {
                        $order_info_detail[2] = '152     工厂生产';
                    }
                    if ($order_info_detail[2] == 160) {
                        $order_info_detail[2] = '160     其它退单';
                    }
                    if ($order_info_detail[2] == 170) {
                        $order_info_detail[2] = '170     订单取消';
                    }
                } else {
                    $order_info_detail[2] = '';
                }
                $result['flag'] = 1;
                $result['message'] = $order_info['Order']['export_flag'];
                $result['ec_detail'] = '物流状态:'.$order_info_detail[2];
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *保存AJAX提交过来的数据.
     */
    public function form_data_save()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $order_id = $_REQUEST['order_id'];
        //基本信息 部份
//		$order_shipping_fee = $_REQUEST["order_shipping_fee"];//配送费用
//		$order_insure_fee = $_REQUEST["order_insure_fee"];//保价费用
        if (isset($_REQUEST['order_user_id']) && $_REQUEST['order_user_id'] != '') {
            $order_user_id = $_REQUEST['order_user_id'];
        }//用户id
//		$order_payment_id = $_REQUEST["order_payment_id"];//支付方式
//		$order_payment_fee = $_REQUEST["order_payment_fee"];//支付费用
//		$order_shipping_id = $_REQUEST["order_shipping_id"];//配送方式
//		$order_pack_fee = $_REQUEST["order_pack_fee"];//包装费用
//		$order_card_fee = $_REQUEST["order_card_fee"];//贺卡费用
//		$order_postscript= '';
//		//$order_postscript = $_REQUEST["order_postscript"];//客户给商家留言
//		$order_tax = $_REQUEST["order_tax"];//发票税额
//		$order_discount = $_REQUEST["order_discount"];//折扣
//		//收货人信息
//		$order_telephone = $_REQUEST["order_telephone"];//电话
//		$order_consignee = $_REQUEST["order_consignee"];//收货人
//		$order_mobile = $_REQUEST["order_mobile"];//手机
//		$regions=$_REQUEST["order_country"].' ';
//		$regions=$regions.$_REQUEST["order_province"].' ';
//		$regions=$regions.$_REQUEST["order_city"];
//		$order_country = $_REQUEST["order_country"];//国家
//		$order_province = $_REQUEST["order_province"];//省
//		$order_city = $_REQUEST["order_city"];//市
//		$order_district = $_REQUEST["order_district"];//区
//		$order_sign_building = $_REQUEST["order_sign_building"];//标致性建筑
//		$order_address = $_REQUEST["order_address"];//地址
//		$order_best_time = $_REQUEST["order_best_time"];//最佳送货时间
//		$order_zipcode = $_REQUEST["order_zipcode"];//邮编
//		$order_note = $_REQUEST["order_note"];//备注  客户给商家留言
//		$order_email = $_REQUEST["order_email"];//电子邮件
//		//其它信息
//		$order_to_buyer = $_REQUEST["order_to_buyer"];//商家对客户的留言
        $order_invoice_type = $_REQUEST['order_invoice_type'];//发票类型
        $order_invoice_payee = $_REQUEST['order_invoice_payee'];//发票抬头
        $order_invoice_content = $_REQUEST['order_invoice_content'];//发票内容
        $order_how_oos = $_REQUEST['order_how_oos'];//缺货处理
        /*
        //订单商品
        $order_product_code = isset($_REQUEST["order_product_code"])?$_REQUEST["order_product_code"]:array();//订单商品货号
        $order_product_price = isset($_REQUEST["order_product_price"])?$_REQUEST["order_product_price"]:array();//订单商品价格
        $order_product_discount = isset($_REQUEST["order_product_discount"])?$_REQUEST["order_product_discount"]:array();//订单商品折扣
        $order_product_attr = isset($_REQUEST["order_product_attr"])?$_REQUEST["order_product_attr"]:array();//订单商品价格
        $order_product_quntity = isset($_REQUEST["order_product_quntity"])?$_REQUEST["order_product_quntity"]:array();//订单商品数据
        $subtotal = 0;
        foreach($order_product_code as $k=>$v){
            $order_product_data = $this->OrderProduct->find("first",array("conditions"=>array("order_id"=>$order_id,"product_code"=>$v)));
            $order_product_data["OrderProduct"]["product_code"] = $v;
            $order_product_data["OrderProduct"]["product_price"] = $order_product_price[$k]-$order_product_discount[$k];
            $order_product_data["OrderProduct"]["product_attrbute"] = str_replace("\n", "<br />", $order_product_attr[$k]);
            $order_product_data["OrderProduct"]["product_quntity"] = $order_product_quntity[$k];
            $subtotal+=$order_product_data["OrderProduct"]["product_price"]*$order_product_data["OrderProduct"]["product_quntity"];
            if(!empty($order_product_data["OrderProduct"]["id"])){
                $this->OrderProduct->save($order_product_data);
            }else{
                $this->OrderProduct->saveAll($order_product_data);
            }
        }
        //计算订单金额
        $order_total = $this->order_total_amounts($subtotal,$order_tax,$order_shipping_fee,$order_insure_fee,$order_payment_fee,$order_pack_fee,$order_card_fee);
        */
        if (isset($_REQUEST['order_user_id']) && $_REQUEST['order_user_id'] != '') {
            $order_data = array(
                'id' => $order_id,
                'operator_id' => $this->admin['id'],
                'user_id' => $order_user_id,
                'invoice_type' => $order_invoice_type,
                'invoice_payee' => $order_invoice_payee,
                'invoice_content' => $order_invoice_content,
                'how_oos' => $order_how_oos,
            );
        } else {
            $order_data = array(
                'id' => $order_id,
                'operator_id' => $this->admin['id'],
                'invoice_type' => $order_invoice_type,
                'invoice_payee' => $order_invoice_payee,
                'invoice_content' => $order_invoice_content,
                'how_oos' => $order_how_oos,
            );
        }
//		if(isset($_REQUEST["order_user_id"])&&$_REQUEST["order_user_id"]!=""){
//			$OrderAction['order_id'] = $order_id;
//			$OrderAction['user_id'] = $_REQUEST["order_user_id"];
//			$this->OrderAction->saveAll($OrderAction);
//		}
        $order_code = $this->Order->find('first', array('fileds' => array('order_code,shipping_status,payment_status,status'), 'conditions' => array('Order.id' => $order_id)));
        //	pr($order_code['Order']['order_code']);die;
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑了订单'.'.'.$order_code['Order']['order_code'], $this->admin['id']);
        }
        $this->Order->save(array('Order' => $order_data));
        //OrderAction
        if (isset($_REQUEST['order_user_id']) && $_REQUEST['order_user_id'] != '') {
            $OrderAction['order_id'] = $order_id;
            $OrderAction['action_note'] = $this->ld['order_success_update'];
            $OrderAction['user_id'] = $_REQUEST['order_user_id'];
            $OrderAction['from_operator_id'] = $this->admin['id'];
            $OrderAction['order_status'] = $order_code['Order']['status'];
            $OrderAction['shipping_status'] = $order_code['Order']['shipping_status'];
            $OrderAction['payment_status'] = $order_code['Order']['payment_status'];
            $this->OrderAction->saveAll($OrderAction);
        }
        $result['code'] = 1;
        $result['message'] = $this->ld['order_success_update'];
        echo json_encode($result);
        die;
    }

    /**
     *保存AJAX提交过来的订单价格相关数据 chenfan 2012/2/10.
     */
    public function order_total_change()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $order_id = $_REQUEST['order_id'];
        //基本信息 部份
        $order_shipping_fee = $_REQUEST['order_shipping_fee'];//配送费用
        $order_insure_fee = $_REQUEST['order_insure_fee'];//保价费用
        $order_payment_id = $_REQUEST['order_payment_id'];//支付方式
        $order_sub_payment = isset($_REQUEST['order_sub_payment']) ? $_REQUEST['order_sub_payment'] : '';//支付方式 2
        $order_payment_fee = $_REQUEST['order_payment_fee'];//支付费用
        $order_shipping_id = $_REQUEST['order_shipping_id'];//配送方式
        $order_pack_fee = $_REQUEST['order_pack_fee'];//包装费用
        $order_card_fee = $_REQUEST['order_card_fee'];//贺卡费用
        $order_tax = $_REQUEST['order_tax'];//发票税额
        $order_discount = $_REQUEST['order_discount'];//折扣
        $order_to_buyer = $_REQUEST['order_to_buyer'];//商家对客户的留言
        $subtotal = $_REQUEST['order_subtotal'];//商品总金额
        $order_money_paid = $_REQUEST['order_money_paid']; //已付款金额
        //计算订单金额
        $order_total = $this->order_total_amounts($subtotal, $order_tax, $order_shipping_fee, $order_insure_fee, $order_payment_fee, $order_pack_fee, $order_card_fee);
        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id), 'fields' => 'Order.id,Order.order_code,Order.operator_code,Order.user_id,Order.shipping_status,Order.status,Order.payment_status'));
        $payment_name = '';
        if (!empty($order_payment_id)) {
            $payment_name = $this->Payment->get_payment_name($order_payment_id, $this->backend_locale);
        }
        $shipping_name = '';
        if (!empty($order_shipping_id)) {
            $shipping_name = $this->Shipping->get_shipping_name($order_shipping_id, $this->backend_locale);
        }
        $operator_code = $order_info['Order']['operator_code'].';4';
        $order_data = array(
            'id' => $order_id,
            'operator_id' => $this->admin['id'],
            'total' => $order_total,
            'shipping_fee' => $order_shipping_fee,
            'insure_fee' => $order_insure_fee,
            'payment_id' => $order_payment_id,
            'sub_pay' => $order_sub_payment,
            'payment_name' => !empty($payment_name) ? $payment_name['PaymentI18n']['name'] : '',
            'payment_fee' => $order_payment_fee,
            'shipping_id' => $order_shipping_id,
            'shipping_name' => !empty($shipping_name) ? $shipping_name['ShippingI18n']['name'] : '',
            'pack_fee' => $order_pack_fee,
            'card_fee' => $order_card_fee,
            'tax' => $order_tax,
            'discount' => $order_discount,
            'to_buyer' => $order_to_buyer,
            'money_paid' => $order_money_paid,
            'operator_code' => $operator_code,
        );
        $this->Order->save(array('Order' => $order_data));
        $json = json_encode($_POST);
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'修改订单信息编号:'.$order_info['Order']['order_code'], $this->admin['id']);
        }
        //OrderAction
        $user_id = $order_info['Order']['user_id'];
        $shipping_status = $order_info['Order']['shipping_status'];
        $order_status = $order_info['Order']['status'];
        $payment_status = $order_info['Order']['payment_status'];
        $operation_notes = '修改订单信息';
        $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status, $shipping_status, $operation_notes);
        $result['code'] = 1;
        $result['message'] = $this->ld['order_success_update'];
        $result['total'] = sprintf('%01.2f', $order_total);
        $need_pay = $this->need_pay($order_id);
        $result['need_pay'] = $need_pay;
        echo json_encode($result);
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die;
    }

    /**
     *保存AJAX提交过来的订单商品相关数据 chenfan 2012/2/10.
     */
    public function order_products_data_save()
    {
        Configure::write('debug',0);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $order_id = $_REQUEST['order_id'];
        //订单商品
        $order_product_id = isset($_REQUEST['order_product_id']) ? $_REQUEST['order_product_id'] : array();//订单商品Id
        $order_product_code = isset($_REQUEST['order_product_code']) ? $_REQUEST['order_product_code'] : array();//订单商品货号
        $order_product_price = isset($_REQUEST['order_product_price']) ? $_REQUEST['order_product_price'] : array();//订单商品价格
        $order_product_discount = isset($_REQUEST['order_product_discount']) ? $_REQUEST['order_product_discount'] : array();//订单商品折扣
        $order_product_attr = isset($_REQUEST['order_product_attr']) ? $_REQUEST['order_product_attr'] : array();//订单商品价格
        $order_product_quntity = isset($_REQUEST['order_product_quntity']) ? $_REQUEST['order_product_quntity'] : array();//订单商品数量数据
        $order_product_begin_date=isset($_REQUEST['order_product_begin_date']) ? $_REQUEST['order_product_begin_date'] : array();//订单商品起始时间数据
        $order_product_expire_date=isset($_REQUEST['order_product_expire_date']) ? $_REQUEST['order_product_expire_date'] : array();//订单商品到期时间数据
        $order_product_lease_unit=isset($_REQUEST['order_product_lease_unit']) ? $_REQUEST['order_product_lease_unit'] : array();//订单商品天数数据
        $subtotal = 0;$purchase_price_total=0;
        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id), 'recursive' => -1));
        foreach ($order_product_code as $k => $v) {
            $order_product_data = $this->OrderProduct->find('first', array('conditions' => array('OrderProduct.id' => $order_product_id[$k])));
            if(!empty($order_product_data['OrderProduct']['parent_product_id']))continue;
            $package_info=$this->PackageProduct->find('all',array('conditions'=>array('PackageProduct.product_code'=>$v)));
            //套装子商品库存
            if(!empty($package_info)){
                $orderproductid=isset($order_product_data['OrderProduct']['product_id'])?$order_product_data['OrderProduct']['product_id']:0;
                foreach($package_info as $pa_k=>$pa_v){
                    $package_order_product=$this->OrderProduct->find('first',array('conditions'=>array('OrderProduct.order_id'=>$order_id,'OrderProduct.parent_product_id'=>$orderproductid,'OrderProduct.product_code'=>$pa_v['PackageProduct']['package_product_code'])));
                    if(!empty($package_order_product['OrderProduct'])){
                        $package_order_product_data['id']=$package_order_product['OrderProduct']['id'];
                        $package_order_product_data['product_quntity']=$order_product_quntity[$k]*$pa_v['PackageProduct']['package_product_qty'];
                        $this->OrderProduct->saveAll(array("OrderProduct"=>$package_order_product_data));
                        if(!empty($package_order_product['Product'])&&$order_info['Order']['payment_status'] == 2){
                            $package_product_data=$package_order_product['Product'];
                            $package_product_data['quantity']=$package_product_data['quantity']+$package_order_product['OrderProduct']['product_quntity']-($order_product_quntity[$k]*$pa_v['PackageProduct']['package_product_qty']);
                            $package_product_data['frozen_quantity'] = $package_product_data['frozen_quantity'] - $package_order_product['OrderProduct']['product_quntity'] + ($order_product_quntity[$k]*$pa_v['PackageProduct']['package_product_qty']);
                        }
                    }
                }
            }
            //套装子商品库存
            if ($order_info['Order']['payment_status'] == 2) {
                $product_info = $this->Product->find('first', array('conditions' => array('Product.code' => $v)));
                if (!empty($product_info) && !empty($order_product_data)) {
                    $product_info['Product']['quantity'] = $product_info['Product']['quantity'] + $order_product_data['OrderProduct']['product_quntity'] - $order_product_quntity[$k];
                    $product_info['Product']['frozen_quantity'] = $product_info['Product']['frozen_quantity'] - $order_product_data['OrderProduct']['product_quntity'] + $order_product_quntity[$k];
                    $this->Product->save($product_info);
                }
            }
            $order_product_data['OrderProduct']['product_code'] = $v;
            $order_product_data['OrderProduct']['product_price'] = $order_product_price[$k];
            $order_product_data['OrderProduct']['adjust_fee'] = $order_product_discount[$k] * ($order_product_quntity[$k] - $order_product_data['OrderProduct']['refund_quantity']);
            $order_product_data['OrderProduct']['product_attrbute'] = str_replace("\n", '<br />', $order_product_attr[$k]);
            $order_product_data['OrderProduct']['product_quntity'] = $order_product_quntity[$k];
            $subtotal += $order_product_data['OrderProduct']['product_price'] * ($order_product_data['OrderProduct']['product_quntity'] - $order_product_data['OrderProduct']['refund_quantity']) + $order_product_data['OrderProduct']['adjust_fee'];
            $purchase_price_total+= $order_product_data['OrderProduct']['purchase_price'] * ($order_product_data['OrderProduct']['product_quntity'] - $order_product_data['OrderProduct']['refund_quantity']);
            $order_product_data['OrderProduct']['begin_date'] = isset($order_product_begin_date[$k])&&$order_product_begin_date[$k]!=''?$order_product_begin_date[$k]:(isset($order_product_data['OrderProduct']['begin_date'])?$order_product_data['OrderProduct']['begin_date']:'2008-01-01 00:00:00');
            $order_product_data['OrderProduct']['expire_date'] = isset($order_product_expire_date[$k])&&$order_product_expire_date[$k]!=''?$order_product_expire_date[$k]:(isset($order_product_data['OrderProduct']['expire_date'])?$order_product_data['OrderProduct']['expire_date']:'2008-01-01 00:00:00');
            $order_product_data['OrderProduct']['lease_unit'] = isset($order_product_lease_unit[$k])?$order_product_lease_unit[$k]:(isset($order_product_data['OrderProduct']['lease_unit'])?$order_product_data['OrderProduct']['lease_unit']:'0');
            if (!empty($order_product_data['OrderProduct']['id'])) {
                $this->OrderProduct->save($order_product_data);
            } else {
                $this->OrderProduct->saveAll($order_product_data);
            }
            //OrderAction
            $user_id = $order_info['Order']['user_id'];
            $shipping_status = $order_info['Order']['shipping_status'];
            $order_status = $order_info['Order']['status'];
            $payment_status = $order_info['Order']['payment_status'];
            $operation_notes = '编辑订单商品信息,商品货号：'.$v;
            $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status, $shipping_status, $operation_notes);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'修改订单商品货号:'.$v.'，订单号:'.$order_info['Order']['order_code'], $this->admin['id']);
            }
        }
        $order_total = $this->update_order_product($order_id);
        
        $need_pay = $this->need_pay($order_id);
        //计算订单金额
        $operator_code = $order_info['Order']['operator_code'];
        $order_data = array(
            'id' => $order_id,
            'operator_id' => $this->admin['id'],
            'total' => $order_total,
            'subtotal' => $subtotal,
            'operator_code' => $operator_code,
            'need_pay'=>$need_pay
        );
        if (isset($order_info['Order']['service_type'])&&$order_info['Order']['service_type']!='appointment'&&$order_info['Order']['payment_status'] == 2) {
            	$order_data['money_paid'] = $order_total - $order_info['Order']['discount'] - $order_info['Order']['point_fee'];
        }
        $this->Order->save(array('Order' => $order_data));
        $result['code'] = 1;
        $result['message'] = $this->ld['order_success_update'];
        $result['subtotal'] = sprintf('%01.2f', $subtotal);
        $result['total'] = sprintf('%01.2f', $order_total);
        $result['need_pay'] = sprintf('%01.2f', $need_pay);
        $result['purchase_price_total'] = sprintf('%01.2f', $purchase_price_total);
        if (isset($order_data['money_paid'])) {
            $result['money_paid'] = sprintf('%01.2f', $order_data['money_paid']);
        }
        $order_info = $this->Order->find('first', array('fields' => array('id', 'order_code','insure_fee','discount'), 'conditions' => array('Order.id' => $order_id),'recursive'=>'-1'));
        if(isset($order_info['Order']['insure_fee'])){
            	$result['insure_fee'] = sprintf('%01.2f', $order_info['Order']['insure_fee']);
        }
        if(isset($order_info['Order']['discount'])){
            	$result['discount'] = sprintf('%01.2f', $order_info['Order']['discount']);
        }
        die(json_encode($result));
    }

    function order_product_attribute_save(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result = array();
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['flag'] = 0;
            $result['content'] = $this->ld['have_no_operation_perform'];
        }else{
            $id=isset($_REQUEST['id'])?$_REQUEST['id']:0;
            $val=isset($_REQUEST['val'])?trim($_REQUEST['val']):'';
            $attribute=$val=="-"?"":$val;
            $order_product_info=$this->OrderProduct->find('first',array('conditions'=>array('OrderProduct.id'=>$id)));
            if(!empty($order_product_info['OrderProduct'])){
                $order_product_data=array(
                    'id'=>$id,
                    'product_attrbute'=>$attribute
                );
                $this->OrderProduct->save($order_product_data);
                $order_id = $order_product_info['Order']['id'];
                $user_id = $order_product_info['Order']['user_id'];
                $shipping_status = $order_product_info['Order']['shipping_status'];
                $order_status = $order_product_info['Order']['status'];
                $payment_status = $order_product_info['Order']['payment_status'];
                $product_code=$order_product_info['OrderProduct']['product_code'];
                $operation_notes = '编辑订单商品信息,商品货号：'.$product_code." 属性:".$attribute;
                $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status, $shipping_status, $operation_notes);
            }
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        die(json_encode($result));
    }

    public function order_status_select_reload()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $order_id = isset($_REQUEST['order_id']) ? $_REQUEST['order_id'] : 266;
        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));
        $order_action = array();
        $order_action_list = array();
        if (!empty($order_info)) {
            $order_action = $this->operable_list($order_info);
        }
        if (isset($order_action['confirm']) && $order_action['confirm']) {
            $order_action_list['order_confirm'] = $this->ld['confirm'];
        }
        if (isset($order_action['pickup']) && $order_action['pickup']) {
            $order_action_list['order_pickup'] = $this->ld['pick_up'];
        }
        if (isset($order_action['pay']) && $order_action['pay']) {
            $order_action_list['order_payment'] = $this->ld['payment_btn'];
        }
        if (isset($order_action['unpay']) && $order_action['unpay']) {
            $order_action_list['order_make_no_payments'] = $this->ld['make_unpay'];
        }
        if (isset($order_action['prepare']) && $order_action['prepare']) {
            $order_action_list['order_picking'] = $this->ld['picking_btn'];
        }
        if (isset($order_action['ship']) && $order_action['ship']) {
            $order_action_list['order_delivery'] = $this->ld['delivery'];
        }
        if (isset($order_action['receive']) && $order_action['receive']) {
            $order_action_list['order_has_been_receiving'] = $this->ld['received'];
        }
        if (isset($order_action['cancel']) && $order_action['cancel']) {
            $order_action_list['order_cancel'] = $this->ld['cancel'];
        }
        if (isset($order_action['invalid']) && $order_action['invalid']) {
            $order_action_list['order_invalid'] = $this->ld['invalid'];
        }
        if (isset($order_action['return']) && $order_action['return']) {
            $order_action_list['order_returns'] = $this->ld['return'];
        }
        if (isset($order_action['after_service']) && $order_action['after_service']) {
            $order_action_list['after_service'] = $this->ld['service'];
        }
        die(json_encode($order_action_list));
    }

    /**
     *保存AJAX提交过来的订单地址相关数据 chenfan 2012/2/10.
     */
    public function order_address_data_save($type = 'user')
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $this->set('action_type', $type);
        $operator_list = $this->Operator->find('list',array('fields' => array('Operator.id','Operator.name'),'conditions'=>array('Operator.status'=>'1')));
        $this->set('operator_list',$operator_list);
        //资源库信息
        $Resource_info = $this->Resource->getformatcode(array('order_status', 'shipping_status', 'payment_status', 'picking_type','order_service_type'), $this->backend_locale);
        $this->set('Resource_info', $Resource_info);
        $order_id = isset($_REQUEST['order_id']) ? $_REQUEST['order_id'] : 0;
        //物流公司
        $logistics_company_list = $this->LogisticsCompany->logistics_company_effective_list();
        $this->set('logistics_company_list', $logistics_company_list);
        if (isset($_REQUEST['order_type'])) {
            $order_type_txt = isset($_REQUEST['order_type']) ? $_REQUEST['order_type'] : '';//订单来源
            $order_type_arr = explode(':', $order_type_txt);
            $order_type = !empty($order_type_arr[0]) ? $order_type_arr[0] : $order_type_txt;
            $order_type_id = !empty($order_type_arr[1]) ? $order_type_arr[1] : $order_type_txt;
            $order_data['type'] = $order_type;
            $order_data['order_type_id'] = $order_type_id;
        }
        $order_user_id = isset($_REQUEST['order_user_id']) && !empty($_REQUEST['order_user_id']) ? $_REQUEST['order_user_id'] : 0;//用户id
        $create_user_name = isset($_REQUEST['create_user_name']) && !empty($_REQUEST['create_user_name']) ? $_REQUEST['create_user_name'] : '';//新增购货人
        $create_user_mobile = isset($_REQUEST['create_user_mobile']) && !empty($_REQUEST['create_user_mobile']) ? $_REQUEST['create_user_mobile'] : 0;//新增购货人手机
        $order_shipping_id = isset($_REQUEST['order_shipping_id']) ? $_REQUEST['order_shipping_id'] : 1;
        $order_telephone = isset($_REQUEST['order_telephone']) ? $_REQUEST['order_telephone'] : '';//电话
        $order_consignee = isset($_REQUEST['order_consignee']) ? $_REQUEST['order_consignee'] : '';//收货人
        $order_mobile = isset($_REQUEST['order_mobile']) ? $_REQUEST['order_mobile'] : '';//手机
        $order_country = isset($_REQUEST['order_country']) ? $_REQUEST['order_country'] : '';//国家
        $order_province = isset($_REQUEST['order_province']) ? $_REQUEST['order_province'] : '';//省
        $order_city = isset($_REQUEST['order_city']) ? $_REQUEST['order_city'] : '';//市
        $regions = $order_country.' '.$order_province.' '.$order_city;
        $order_district = isset($_REQUEST['order_district']) ? $_REQUEST['order_district'] : '';//区
        $order_sign_building = isset($_REQUEST['order_sign_building']) ? $_REQUEST['order_sign_building'] : '';//标致性建筑
        $order_address = isset($_REQUEST['order_address']) ? $_REQUEST['order_address'] : '';//地址
        $order_best_time = isset($_REQUEST['order_best_time']) ? $_REQUEST['order_best_time'] : '';//最佳送货时间
        $order_how_oos = isset($_REQUEST['order_how_oos']) ? $_REQUEST['order_how_oos'] : '';//缺货处理
        $order_zipcode = isset($_REQUEST['order_zipcode']) ? $_REQUEST['order_zipcode'] : '';//邮编
        $order_note = isset($_REQUEST['order_note']) ? $_REQUEST['order_note'] : '';//备注  卖家留言
        $order_postscript = isset($_REQUEST['order_postscript']) ? $_REQUEST['order_postscript'] : '';//备注  客户给商家留言
        $order_email = isset($_REQUEST['order_email']) ? $_REQUEST['order_email'] : '';//电子邮件
        $sel_address = isset($_REQUEST['sel_address']) ? $_REQUEST['sel_address'] : 0;
        $user_address_id = isset($_REQUEST['user_address_id']) ? $_REQUEST['user_address_id'] : '';
        $user_data = isset($_REQUEST['data']['User']) ? $_REQUEST['data']['User'] : array();
        $user_config_data = isset($_REQUEST['data']['UserConfig']) ? $_REQUEST['data']['UserConfig'] : array();
        $order_data['id'] = $order_id;
        $order_data['shipping_id'] = $order_shipping_id;
        if ($order_user_id == 0 && $create_user_name != '' && $create_user_mobile != '') {
            $user['name'] = $create_user_name;
            $user['first_name'] = $create_user_name;
            $user['user_sn'] = $create_user_name;
            $conditions['or']['User.user_sn'] = $user['user_sn'];
            $conditions['or']['User.mobile'] = $create_user_mobile;
            $users_info = $this->User->find('first', array('conditions' => $conditions));
            if (!empty($users_info)) {
                $user['id'] = $users_info['User']['id'];
                $order_user_id = $users_info['User']['id'];
                $note = $users_info['User']['admin_note'];
            } else {
                $user['password'] = isset($this->configs['password-defult']) && !empty($this->configs['password-defult']) ? md5($this->configs['password-defult']) : md5('123456');
                $user['email'] = '';
                $user['mobile'] = $create_user_mobile;
                $user['sex'] = 0;
                $this->User->saveAll($user);
                $order_user_id = $this->User->id;
            }
        }
        $user_address['user_id'] = $order_user_id;
        if ($type == 'address') {
            //获取区域ID
            $RegionInfo = $this->RegionI18n->find('list', array('fields' => array('RegionI18n.name', 'RegionI18n.region_id'), 'conditions' => array('RegionI18n.locale' => $this->backend_locale, 'RegionI18n.name' => array($order_country, $order_province, $order_city))));
            $order_country_id = isset($RegionInfo[$order_country]) ? $RegionInfo[$order_country] : 0;
            $order_province_id = isset($RegionInfo[$order_province]) ? $RegionInfo[$order_province] : 0;
            $order_city_id = isset($RegionInfo[$order_city]) ? $RegionInfo[$order_city] : 0;
            // 判断地址是否存在
            if ($sel_address == 0) {
                $address_conditions['UserAddress.user_id'] = $order_user_id;
                $address_conditions['UserAddress.country'] = $order_country_id;
                $address_conditions['UserAddress.province'] = $order_province_id;
                $address_conditions['UserAddress.city'] = $order_city_id;
                $address_conditions['UserAddress.address'] = $order_address;
            } else {
                $address_conditions['UserAddress.id'] = $sel_address;
            }
            $addressInfo = $this->UserAddress->find('first', array('conditions' => $address_conditions));
            if (empty($addressInfo)) {
                $user_address['user_id'] = $order_user_id;
                $user_address['consignee'] = $order_consignee;
                $user_address['email'] = $order_email;
                $user_address['mobile'] = $order_mobile;
                $user_address['telephone'] = $order_telephone;
                $user_address['country'] = $order_country_id;
                $user_address['province'] = $order_province_id;
                $user_address['city'] = $order_city_id;
                $user_address['address'] = $order_address;
                $user_address['regions'] = $order_country_id.' '.$order_province_id.' '.$order_city_id;
                $user_address['best_time'] = $order_best_time;
                $user_address['sign_building'] = $order_sign_building;
                $user_address['zipcode'] = $order_zipcode;
                $this->UserAddress->saveAll($user_address);
                $user_address_id = $this->UserAddress->id;
                $this->User->updateAll(array('User.address_id' => $user_address_id), array('User.id' => $order_user_id));
            }
            $regions = $order_country_id.' '.$order_province_id.' '.$order_city_id;
            $order_data['consignee'] = $order_consignee;
            $order_data['address'] = $order_address;
            $order_data['mobile'] = $order_mobile;
            $order_data['telephone'] = $order_telephone;
            $order_data['regions'] = $regions;
            $order_data['country'] = $order_country;
            $order_data['province'] = $order_province;
            $order_data['city'] = $order_city;
            $order_data['district'] = $order_district;
            $order_data['sign_building'] = $order_sign_building;
            $order_data['best_time'] = $order_best_time;
            $order_data['zipcode'] = $order_zipcode;
            $order_data['note'] = $order_note;
            $order_data['postscript'] = $order_postscript;
            $order_data['email'] = $order_email;
            $order_data['how_oos'] = $order_how_oos;
        }
        if (!empty($user_data) && $order_user_id != 0) {
            $user_data = $_REQUEST['data']['User'];
            $user_data['id'] = $order_user_id;
            $this->User->save($user_data);
        }
        if (!empty($user_config_data) && $order_user_id != 0) {
            foreach ($user_config_data as $k => $v) {
                $this->UserConfig->deleteAll(array('UserConfig.type' => $k, 'UserConfig.user_id' => $order_user_id));
                foreach ($v as $ck => $cv) {
                    $config_data['user_id'] = $order_user_id;
                    $config_data['type'] = $k;
                    $config_data['code'] = $ck;
                    $config_data['value'] = $cv;
                    $this->UserConfig->saveAll($config_data);
                }
            }
        }
        $order_data['user_id'] = $order_user_id;
        $this->Order->save($order_data);
        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));
        $order_data['operator_code'] = $order_info['Order']['operator_code'].';1';
        $this->Order->save($order_data);
        $shipping_status = $order_info['Order']['shipping_status'];
        $order_status = $order_info['Order']['status'];
        $payment_status = $order_info['Order']['payment_status'];
        $operation_notes = '编辑订单用户（收货人地址）信息';
        $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $order_user_id, $order_status, $payment_status, $shipping_status, $operation_notes);
        if ($this->Order->save(array('Order' => $order_data))) {
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑了订单'.'.'.$order_info['Order']['order_code'].' '.'的用户（收货人地址）信息'.'.', $this->admin['id']);
            }
        }
        $user_info = $this->User->find('first', array('conditions' => array('User.id' => $order_user_id)));
        $user_addresses_array = array();
        $default_user_config_list = array();
        $user_config_list = array();
        if (!empty($user_info)) {
            $user_addresses_array = $this->UserAddress->user_addresses_get($order_user_id);
            //用户量体信息设置
            $this->UserConfig->set_locale($this->backend_locale);
            $body_type_list = $this->UserConfig->find('all', array('conditions' => array('UserConfig.user_id' => array(0, $order_user_id), 'type' => 'body_type')));
            foreach ($body_type_list as $k => $v) {
                if ($v['UserConfig']['user_id'] == 0) {
                    $default_user_config_list[$v['UserConfig']['code']]['name'] = $v['UserConfigI18n']['name'];
                    $default_user_config_list[$v['UserConfig']['code']]['value_type'] = $v['UserConfig']['value_type'];
                    $default_user_config_list[$v['UserConfig']['code']]['user_config_values'] = $v['UserConfigI18n']['user_config_values'];
                    $default_user_config_list[$v['UserConfig']['code']]['value'] = $v['UserConfig']['value'];
                } else {
                    $user_config_list[$v['UserConfig']['code']] = $v['UserConfig']['value'];
                }
            }
        }
        $RegionList = $this->RegionI18n->find('list', array('fields' => array('RegionI18n.region_id', 'RegionI18n.name'), 'conditions' => array('RegionI18n.locale' => $this->backend_locale)));
        $this->set('RegionList', json_encode($RegionList));
        $this->set('regions_info3', $RegionList);
        $this->set('order_info', $order_info);
        $this->set('user_info', $user_info);
        $this->set('user_addresses_array', $user_addresses_array);
        $this->set('user_addresses_json', json_encode($user_addresses_array));
        $this->set('default_user_config_list', $default_user_config_list);
        $this->set('user_config_list', $user_config_list);
        //配送方式
        $shipping_effective_list = $this->Shipping->shipping_effective_list_beta($this->locale);
        $this->set('shipping_effective_list', $shipping_effective_list);
        //订单来源
        $this->Orderfrom->get($this, 1);
        $information_resources_info = $this->InformationResource->information_formated(array('how_oos', 'best_time'), $this->locale);
        $this->set('information_resources_info', $information_resources_info);
        $order_country_id = 0;
        $order_province_id = 0;
        $order_city_id = 0;
        $regionsInfo = trim($order_info['Order']['regions']);
        if (!empty($regionsInfo)) {
            $regionids = explode(' ', $regionsInfo);
            $order_country_id = isset($regionids[0]) ? $regionids[0] : '';
            $order_province_id = isset($regionids[1]) ? $regionids[1] : '';
            $order_city_id = isset($regionids[2]) ? $regionids[2] : '';
        }
        $this->set('order_country_id', $order_country_id);
        $this->set('order_province_id', $order_province_id);
        $this->set('order_city_id', $order_city_id);
        if(!empty($order_info)){
            $this->set('order_action', $this->operable_list($order_info));//操作状态
        }
    }

    //租赁订单操作
    public function lease_order_status(){
        $this->operator_privilege('lease_orders_edit');
        $order_id = isset($_REQUEST['order_id'])?$_REQUEST['order_id']:0;
        $pro_id = isset($_REQUEST['order_id'])?$_REQUEST['pro_id']:0;
        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));//订单信息
        if(empty($order_info)){$this->redirect("/orders/lease_order");}
        $user_id = $order_info['Order']['user_id'];//订单用户ID
        $order_code = $order_info['Order']['order_code'];//订单号
        $order_status = $order_info['Order']['status'];//订单状态
        $payment_status = $order_info['Order']['payment_status'];//支付方式状态
        $shipping_status = $order_info['Order']['shipping_status'];//配送方式状态
        $status = $_REQUEST['status'];
        //退回

        if ($status == 'cancel'||$status == 'change') {
            $this->operator_privilege('lease_return');
            $users = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
            //还原用户余额支付的金额
            if (!empty($users['User']) && !empty($order_info['Order']['user_balance']) && $order_info['Order']['user_balance'] > 0) {
                $user_balance = $users['User']['balance'] + $order_info['Order']['user_balance'];
                $order_user = array(
                    'id' => $user_id,
                    'balance' => $user_balance,
                );
                $this->User->save($order_user);
                //余额记录支付记录
                if (!empty($user_balance) && $user_balance > 0) {
                    $balance_log = array(
                        'user_id' => $user_id,
                        'amount' => $order_info['Order']['user_balance'],
                        'log_type' => 'O',
                        'system_note' => '订单退款:'.$order_info['Order']['order_code'],
                        'type_id' => $order_id,
                    );
                    $this->UserBalanceLog->save($balance_log);
                }
            }
            $operator_code = $order_info['Order']['operator_code'].';6';
            //$this->Order->update_order(array('id' => $order_id, 'status' => '2', 'shipping_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'operator_id' => $this->admin['id'], 'operator_code' => $operator_code));
            //OrderAction
            $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, 2, $payment_status, $shipping_status, "租赁订单退回");
            $product_info= $this->OrderProduct->find('first', array('conditions' => array('OrderProduct.id' => $pro_id)));
            $frozen_list = $this->OrderProduct->get_frozen_product_list();
            //修改商品状态
            $order_product_data['OrderProduct']['id'] = $pro_id;
            $order_product_data['OrderProduct']['status'] = "3";
            $this->OrderProduct->save($order_product_data);
            //剩余到期天数
            $day=ceil((strtotime($product_info['OrderProduct']['expire_date'])-time())/86400);
            if($day>30){

            }
            $product_id = $product_info['Product']['id'];
            $product_code = $product_info['OrderProduct']['product_code'];
            if (isset($this->configs['enable_decrease_stock_time']) && $this->configs['enable_decrease_stock_time'] == 2) {
                $product_quntity = $product_info['Product']['quantity'] + $product_info['OrderProduct']['product_quntity'];
            } else {
                $product_quntity = $product_info['Product']['quantity'];
            }
            $product_frozen_quantity = isset($frozen_list[$product_info['OrderProduct']['product_code']]) ? $frozen_list[$product_info['OrderProduct']['product_code']] : 0;
            $update_product = array('id' => $product_id,'quantity' => $product_quntity,'frozen_quantity' => $product_frozen_quantity);
            $this->Product->save($update_product);

            if($status == 'change'){
                //换货
                $new_order_code = $this->get_order_code();
                $order_data_old = $order_info;
                $order_info['Order']['id'] = '';
                $order_info['Order']['order_code'] = $new_order_code;
                $order_info['Order']['check_status'] = '0';
                $order_info['Order']['lease_type'] = 'L';
                $order_info['Order']['parent_order_code'] = $order_data_old['Order']['order_code'];//4.新订单parent_order_code保存老订单的order_code
                $order_info['Order']['chargeback_status'] = 0;
                $order_info['Order']['shipping_status'] = 0;
                $order_info['Order']['payment_status'] = 0;
                $order_info['Order']['status'] = 1;
                $order_info['Order']['taobao_delivery_send'] = $order_data_old['Order']['taobao_delivery_send'];
                $order_info['Order']['shipping_time'] = '2008-01-01 00:00:00';
                $order_info['Order']['export_flag'] = 1;
                $order_info['Order']['created'] = date('Y-m-d H:i:s');
                $order_info['Order']['modified'] = date('Y-m-d H:i:s');
                $order_info['Order']['invoice_no'] = '';
                $order_info['Order']['payment_fee'] = 0;
                $order_info['Order']['money_paid'] = 0;
                $order_info['Order']['total'] = 0;
                $order_info['Order']['subtotal'] = 0;
                $order_info['Order']['discount'] = 0;
                $order_info['Order']['point_fee'] = 0;
                $order_info['Order']['refund_status'] = 2;
                $order_info['Order']['tax'] = 0;
                $order_info['Order']['type_id'] = '网站';
                $order_info['Order']['type'] = 'ioco';
                $this->Order->saveAll(array('Order' => $order_info['Order']));//复制一个订单
                $new_order_id = $this->Order->id;
                //修改商品状态
                $order_product_data['OrderProduct']['id'] = $pro_id;
                $order_product_data['OrderProduct']['status'] = "4";
                $this->OrderProduct->save($order_product_data);
                //操作记录
                $old_act = array('order_id' => $order_data_old['Order']['id'],'from_operator_id' => $this->admin['id'],'user_id' => $order_data_old['Order']['user_id'],'order_status' => 6,'payment_status' => $order_data_old['Order']['payment_status'],'shipping_status' => $order_data_old['Order']['shipping_status'],'action_note' => '换货');
                $this->OrderAction->update_order_action($old_act);
                $new_act = array('order_id' => $new_order_id,'from_operator_id' => $this->admin['id'],'user_id' => $order_info['Order']['user_id'],'order_status' => 0,'payment_status' => $order_info['Order']['payment_status'],'shipping_status' => 0,'action_note' => '换货于订单'.$order_code);
                $this->OrderAction->update_order_action($new_act);
                $this->redirect('/orders/lease_edit/'.$new_order_id);
            }else{
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("退回成功");location.href="/admin/orders/lease_order/"</script>';
                exit();
            }
        }
        //续租

        if ($status == 'continue') {
            $this->operator_privilege('lease_renew');
            $day = $_REQUEST['day'];
            $pro_info= $this->OrderProduct->find('first', array('conditions' => array('OrderProduct.id' => $pro_id)));
            $start_time=$pro_info["OrderProduct"]["expire_date"];
            $pro_info["OrderProduct"]["expire_date"]=date("Y-m-d H:i:s", strtotime("$start_time +$day day"));
            $pro_info["OrderProduct"]["lease_unit"]=$pro_info["OrderProduct"]["lease_unit"]+$day;
            $lease_info= $this->ProductLease->find('first', array('conditions' => array('ProductLease.product_code' => $pro_info['OrderProduct']['product_code'])));
            $change_price=$lease_info["ProductLease"]["lease_price"]*($_REQUEST['day']/$lease_info["ProductLease"]["unit"]);
            $pro_info["OrderProduct"]["product_price"]=$pro_info["OrderProduct"]["product_price"]+$change_price;
            $this->OrderProduct->save(array('OrderProduct' => $pro_info['OrderProduct']));
            $order_info['Order']['money_paid']=$order_info['Order']['money_paid']+$change_price*$pro_info["OrderProduct"]["product_quntity"];
            $order_info['Order']['subtotal']=$order_info['Order']['subtotal']+$change_price*$pro_info["OrderProduct"]["product_quntity"];
            $order_info['Order']['total']=$order_info['Order']['total']+$change_price*$pro_info["OrderProduct"]["product_quntity"];
            $this->Order->save(array('Order' => $order_info['Order']));
            $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, 2, $payment_status, $shipping_status, "租赁订单续租");
            //修改商品状态
            $products = $this->OrderProduct->find('all', array('fields' => array('OrderProduct.id'), 'conditions' => array('OrderProduct.order_id' => $order_id)));
            foreach ($products as $k => $v) {
                $order_product_data['OrderProduct']['id'] = $v["OrderProduct"]["id"];
                //$order_product_data['OrderProduct']['status'] = "5";
                $this->OrderProduct->saveAll($order_product_data);
            }
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("续租成功");location.href="/admin/orders/lease_order"</script>';
            exit();
        }
    }

    /**
     *订单各钟状态转换 订单状态 支付方式状态 配送方式状态.
     */
    public function order_status_change(){
        $order_id = isset($_REQUEST['order_id'])?$_REQUEST['order_id']:0;
        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));//订单信息
        if(empty($order_info)){$this->redirect("/orders/");}
        if($order_info['Order']['lease_type']=='L'){
            $this->operator_privilege('lease_orders_edit');
        }else{
            $this->operator_privilege('orders_edit');
        }
        $user_id = $order_info['Order']['user_id'];//订单用户ID
        $order_code = $order_info['Order']['order_code'];//订单号
        $order_status = $order_info['Order']['status'];//订单状态
        $picking_type = $order_info['Order']['picking_type'];//配货方式状态（0.门店出货，1.工厂发货）
        $payment_status = $order_info['Order']['payment_status'];//支付方式状态
        $shipping_status = $order_info['Order']['shipping_status'];//配送方式状态
        $order_payment_id = $order_info['Order']['payment_id'];//支付方式状态
        $order_shipping_id = $order_info['Order']['shipping_id'];//配送方式状态
        $order_status_message_code = $_REQUEST['order_status_message_code'];
        $check_status = $order_info['Order']['check_status'];
        $operation_notes = $_REQUEST['operation_notes'];//操作备注
        if(isset($_REQUEST['check_status'])&&$_REQUEST['check_status']!=''){
            $this->Order->update_order(array('id' => $order_id, 'status' => '1', 'check_status' => $_REQUEST['check_status'], 'operator_id' => $this->admin['id']));
            // if($_REQUEST['check_status'] == '1') {
            //     $msg = $this->ld['order'].' '.$order_code.' '.$this->ld['order_check'].$this->ld['succeed'];
            // } else{
            //     $msg = $this->ld['order'].' '.$order_code.' '.$this->ld['remove_checked'];
            // }
                
        }
        if (!empty($order_payment_id)) {
            $payment_name = $this->Payment->get_payment_name($order_payment_id, $this->backend_locale);
            $this->Order->update_order(array('id' => $order_id, 'status' => '1', 'payment_id' => $order_payment_id, 'payment_name' => $payment_name['PaymentI18n']['name']));
        }
        if (!empty($order_shipping_id)) {
            $shipping_name = $this->Shipping->get_shipping_name($order_shipping_id, $this->backend_locale);
            $this->Order->update_order(array('id' => $order_id, 'status' => '1', 'shipping_id' => $order_shipping_id, 'shipping_name' => $shipping_name['ShippingI18n']['name']));
        }
        $need_pay = $order_info['Order']['total'] - $order_info['Order']['point_fee'] - $order_info['Order']['coupon_fee'] - $order_info['Order']['money_paid'] - $order_info['Order']['discount'];
        if ($order_status_message_code == 'order_payment_delivery') {
            $payment_status = 2;
            $shipping_status = 1;
        }
        //订单来源
//		if(isset($_REQUEST['type'])&&$_REQUEST['type']!=''){
//			$this->Order->updateAll(array('Order.type'=>"'".$_REQUEST['type']."'"),array('Order.id'=>$order_id));
//		}
        //取得订单支付方式是否货到付款
        $payment = $this->Payment->find('first', array('conditions' => array('Payment.id' => $order_info['Order']['payment_id'], 'Payment.status' => 1)));
        $is_cod = $payment['Payment']['is_cod'] == 1;
        //订单确认
        if ($order_status_message_code == 'order_confirm') {
            $this->Order->update_order(array('id' => $order_id, 'status' => '1', 'confirm_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'operator_id' => $this->admin['id']));
            //OrderAction
            $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, 1, $payment_status, $shipping_status, $operation_notes);
            $msg = $this->ld['order'].' '.$order_code.' '.$this->ld['confirmed'];
            if (isset($this->configs['enable_decrease_stock_time']) && $this->configs['enable_decrease_stock_time'] == 2) {
                $order_product_list = $this->OrderProduct->find('list', array('fields' => array('OrderProduct.product_code', 'OrderProduct.product_quntity'), 'conditions' => array('OrderProduct.order_id' => $order_id, 'OrderProduct.status' => 1)));
                foreach ($order_product_list as $opk => $opv) {
                    $update_proudct = $this->Product->find('first', array('conditions' => array('Product.code' => $opk)));
                    $this->Product->updateAll(array('Product.quantity' => $update_proudct['Product']['quantity'] - $opv), array('Product.id' => $update_proudct['Product']['id']));
                    $this->Product->updateskupro($opk, $opv, true);
                }
            }
            $this->notify_order_confirm($order_id);
        }
        //订单取货
        if ($order_status_message_code == 'order_pickup') {
		$need_pay = $this->need_pay($order_id);
		$order_update_data=array(
			'id' => $order_id,
			'shipping_status' => '3', 
			'operator_id' => $this->admin['id']
		);
		if($need_pay >0 ){
			$order_update_data['payment_status']='0';
			$payment_status=0;
		}
		$this->Order->save($order_update_data);
		//OrderAction
		$showtime=date("Y-m-d", strtotime('+7 days'));
		$pro_info = $this->OrderProduct->find('all',array('conditions'=>array('OrderProduct.order_id'=>$order_id)));
		if(isset($pro_info)&&count($pro_info)>0){
			foreach ($pro_info as $k11 => $v11) {
				$v11['OrderProduct']['pre_delivery_time'] = $showtime;
				$this->OrderProduct->save($v11);
			}
		}
		$this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status,3, $operation_notes);
		$msg = $this->ld['order'].' '.$order_code.' '.$this->ld['pick_up'];
        }
        //审核
        if ($order_status_message_code == 'order_check') {
            $this->Order->update_order(array('id' => $order_id, 'check_status' => '1', 'operator_id' => $this->admin['id']));
            //OrderAction
            $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status,0 , $operation_notes);
            $msg = $this->ld['order'].' '.$order_code.' '.$this->ld['order_check'].$this->ld['succeed'];
        }

        // 取消审核
        if ($order_status_message_code == 'order_check_remove') {
            $this->Order->update_order(array('id' => $order_id, 'check_status' => '0', 'operator_id' => $this->admin['id']));
            //OrderAction
            $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status,0 , $operation_notes);
            $msg = $this->ld['order'].' '.$order_code.' '.$this->ld['remove_checked'];
        }

        //订单付款
        if ($order_status_message_code == 'order_payment' || $order_status_message_code == 'order_payment_delivery') {
            $old_payment_status=$order_info['Order']['payment_status'];
            //付款前确认订单
            $this->Order->update_order(array('id' => $order_id, 'status' => '1', 'confirm_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'operator_id' => $this->admin['id']));
            $order_info['Order']['payment_status'] = 2;
            $this->Order->update_order(array('id' => $order_id, 'payment_status' => '2', 'payment_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'operator_id' => $this->admin['id'], 'money_paid' => ($need_pay + $order_info['Order']['money_paid'])));
            
            
            
            if ($order_status_message_code == 'order_payment'||$old_payment_status!=2) {//付款或付款并发货操作
                $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, 1, 2, $shipping_status, $operation_notes);
                if(isset($this->configs['use_point'])&&$this->configs['use_point']=='1'){
                    $points_awarded_occasion=isset($this->configs['points_awarded_occasion'])?$this->configs['points_awarded_occasion']:'';//积分赠送场合
                    //下单积分赠送
                    if(isset($order_info['Order']['lease_type'])&&$order_info['Order']['lease_type']=='L'){//租赁订单
                        if(in_array($points_awarded_occasion,array('0','3'))&&isset($this->configs['lease_order_points'])&&$this->configs['lease_order_points']>0){
                            $user_info = $this->User->findbyid($order_info['Order']['user_id']);
                            $old_point=$user_info['User']['point'];
                            $user_info['User']['point'] += $this->configs['lease_order_points'];
                            $user_info['User']['user_point'] += $this->configs['lease_order_points'];
                            $this->User->save($user_info);
                            $point_log = array('id' => '',
                                'user_id' => $order_info['Order']['user_id'],
                                'point' => $old_point,
                                'point_change' => $this->configs['lease_order_points'],
                                'log_type' => 'B',
                                'system_note' => '下单送积分',
                                'type_id' => $order_info['Order']['id']
                            );
                            $this->UserPointLog->save($point_log);
                        }
                    }else{//购物订单
                        if(in_array($points_awarded_occasion,array('0','2'))&&isset($this->configs['order_points'])&&$this->configs['order_points'] > 0){
                            $user_info = $this->User->findbyid($order_info['Order']['user_id']);
                            $old_point=$user_info['User']['point'];
                            $user_info['User']['point'] += $this->configs['order_points'];
                            $user_info['User']['user_point'] += $this->configs['order_points'];
                            $this->User->save($user_info);
                            $point_log = array('id' => '',
                                'user_id' => $order_info['Order']['user_id'],
                                'point' => $old_point,
                                'point_change' => $this->configs['order_points'],
                                'log_type' => 'B',
                                'system_note' => '下单送积分',
                                'type_id' => $order_info['Order']['id']
                            );
                            $this->UserPointLog->save($point_log);
                        }
                    }

                    //超过订单金额赠送积分
                    $config_order_smallest = isset($this->configs['order_smallest'])?$this->configs['order_smallest']:0;
                    if(isset($order_info['Order']['lease_type'])&&$order_info['Order']['lease_type']=='L'){//租赁订单
                        $config_order_smallest=isset($this->configs['lease_order_smallest'])?$this->configs['lease_order_smallest']:0;
                        if (in_array($points_awarded_occasion,array('0','3'))&&$config_order_smallest <= $order_info['Order']['subtotal']&&$this->configs['out_lease_order_points']>0) {
                            $user_info = $this->User->findbyid($order_info['Order']['user_id']);
                            $old_point=$user_info['User']['point'];
                            $user_info['User']['point'] += $this->configs['out_lease_order_points'];
                            $user_info['User']['user_point'] += $this->configs['out_lease_order_points'];
                            $this->User->save($user_info);
                            $point_log = array('id' => '',
                                'user_id' => $order_info['Order']['user_id'],
                                'point' => $old_point,
                                'point_change' => $this->configs['out_lease_order_points'],
                                'log_type' => 'B',
                                'system_note' => '超过订单金额 '.$config_order_smallest.' 赠送积分',
                                'type_id' => $order_info['Order']['id'],
                            );
                            $this->UserPointLog->save($point_log);
                        }
                    }else{
                        if(in_array($points_awarded_occasion,array('0','2'))&&$config_order_smallest <= $order_info['Order']['subtotal']&&$this->configs['out_order_points']>0){
                            $user_info = $this->User->findbyid($order_info['Order']['user_id']);
                            $old_point=$user_info['User']['point'];
                            $user_info['User']['point'] += $this->configs['out_order_points'];
                            $user_info['User']['user_point'] += $this->configs['out_order_points'];
                            $this->User->save($user_info);
                            $point_log = array('id' => '',
                                'user_id' => $order_info['Order']['user_id'],
                                'point' => $old_point,
                                'point_change' => $this->configs['out_order_points'],
                                'log_type' => 'B',
                                'system_note' => '超过订单金额 '.$config_order_smallest.' 赠送积分',
                                'type_id' => $order_info['Order']['id'],
                            );
                            $this->UserPointLog->save($point_log);
                        }
                    }
                }
                	$orderuser=$this->User->findById($user_id);
	        	if (!empty($orderuser['User']) && isset($need_pay)&&$need_pay>0) {
	        		$payment_ids=array();
				if(intval($order_info['Order']['payment_id'])>0){
					$payment_ids[]=intval($order_info['Order']['payment_id']);
				}
				if(intval($order_info['Order']['sub_pay'])>0){
					$payment_ids[]=intval($order_info['Order']['sub_pay']);
				}
				$payment_info=$this->Payment->find('first',array('conditions'=>array('Payment.id'=>$payment_ids),'order'=>'parent_id desc'));
				$config_value=array();
				if (isset($payment_info['Payment']['config']) && !empty($payment_info['Payment']['config'])) {
					$config_value = unserialize($payment_info['Payment']['config']);
				}
				$payee=$payment_info['PaymentI18n']['name'];
				$receipt_account=isset($config_value['account'])?$config_value['account']:(isset($config_value['MCHID'])?$config_value['MCHID']:$payment_info['PaymentI18n']['name']);
				$this->loadModel('AccountInformation');
				$account_info=array(
					'id'=>0,
					'account_type'=>0,
					'payer'=>isset($orderuser['User']['first_name'])&&trim($orderuser['User']['first_name'])!=''?$orderuser['User']['first_name']:(isset($orderuser['User']['name'])?$orderuser['User']['name']:''),
					'payee'=>$receipt_account,
					'receipt_account'=>$payee,
					'payment_id'=>$payment_info['Payment']['id'],
					'transaction'=>$order_info['Order']['order_code'],
					'payment_amount'=>$need_pay,
					'payment_time'=>date('Y-m-d H:i:s'),
					'status'=>'1',
					'note'=>$this->ld['order'].$order_total['Order']['order_code']
				);
				$this->AccountInformation->save($account_info);
	        	}
                
            }
            //已付款 未发货的商品库存处理
            if (isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 0) {
                if (isset($order_info['Order']['shipping_status']) && $order_info['Order']['shipping_status'] == 0) {
                    $order_products = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $order_id)));
                    foreach ($order_products as $opk => $opv) {
                        //套装子商品库存
                        //已付款 未发货的商品冻结库存处理
                        if (!empty($opv['OrderProduct']) && $order_info['Order']['shipping_status'] == 0) {
                            $product_frozen = $this->Product->find('first', array('conditions' => array('Product.code' => $opv['OrderProduct']['product_code']), 'fields' => 'Product.id,Product.code,Product.frozen_quantity,Product.quantity'));
                            if (!empty($product_frozen)) {
                                $product_frozen['Product']['frozen_quantity'] = $product_frozen['Product']['frozen_quantity'] - $opv['OrderProduct']['product_quntity'];
                                //$product_frozen["Product"]["quantity"] = $product_frozen["Product"]["quantity"]-$opv["OrderProduct"]["product_quntity"];
                                $this->Product->save(array('Product' => $product_frozen['Product']));
                                //$this->Product->updateskupro($opv["OrderProduct"]["product_code"],$opv["OrderProduct"]["product_quntity"],true);
                            }
                        }
                    }
                }
            }
            //已付款 未发货的商品冻结材料处理
            if (isset($this->configs['shop-frozen-materials']) && $this->configs['shop-frozen-materials'] == 0) {
                if (isset($order_info['Order']['shipping_status']) && $order_info['Order']['shipping_status'] == 0) {
                    $order_products = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $order_id)));
                    foreach ($order_products as $opk => $opv) {
                        //套装子商品库存
                        //已付款 未发货的商品冻结材料处理
                        if (!empty($opv['OrderProduct']) && $order_info['Order']['shipping_status'] == 0) {
                            //查询使用材料
                            $pro_material = ClassRegistry::init('ProductMaterial')->find('all', array('conditions' => array('ProductMaterial.product_code' => $opv['OrderProduct']['product_code'])));
                            //减材料库存
                            if (!empty($pro_material)) {
                                $order_product_id = $opv['OrderProduct']['id'];
                                foreach ($pro_material as $mk => $mv) {
                                    $material = ClassRegistry::init('Material');
                                    $material_info = $material->find('first', array('conditions' => array('Material.code' => $mv['ProductMaterial']['product_material_code'])));
                                    $order_material_product_data = array(
                                        'order_id' => $order_id,
                                        'order_product_id' => $order_product_id,
                                        'product_code' => $mv['ProductMaterial']['product_code'],
                                        'material_product_code' => $mv['ProductMaterial']['product_material_code'],
                                        'material_qty' => $mv['ProductMaterial']['quantity'],
                                    );
                                    ClassRegistry::init('OrderMaterialProduct')->saveAll($order_material_product_data);
                                    $material_info['Material']['frozen_quantity'] += $mv['ProductMaterial']['quantity'];
                                    $material_info['Material']['quantity'] = $material_info['Material']['quantity'] - $mv['ProductMaterial']['quantity'];
                                    $material->updateAll(array('Material.frozen_quantity' => $material_info['Material']['frozen_quantity'], 'Material.quantity' => $material_info['Material']['quantity']), array('Material.code' => $mv['ProductMaterial']['product_material_code']));
                                }
                            }
                        }
                    }
                }
            }
            $msg = $this->ld['order'].' '.$order_code.' '.$this->ld['paid'];
        }
        //订单设为未付款
        if ($order_status_message_code == 'order_make_no_payments') {
        	$orderuser=$this->User->findById($user_id);
        	if (!empty($orderuser['User']) && !empty($order_info['Order']['total']) && $order_info['Order']['total'] > 0) {
        		$payment_ids=array();
			if(intval($order_info['Order']['payment_id'])>0){
				$payment_ids[]=intval($order_info['Order']['payment_id']);
			}
			if(intval($order_info['Order']['sub_pay'])>0){
				$payment_ids[]=intval($order_info['Order']['sub_pay']);
			}
			$payment_info=$this->Payment->find('first',array('conditions'=>array('Payment.id'=>$payment_ids),'order'=>'parent_id desc'));
			$config_value=array();
			if (isset($payment_info['Payment']['config']) && !empty($payment_info['Payment']['config'])) {
				$config_value = unserialize($payment_info['Payment']['config']);
			}
			$payee=$payment_info['PaymentI18n']['name'];
			$receipt_account=isset($config_value['account'])?$config_value['account']:(isset($config_value['MCHID'])?$config_value['MCHID']:$payment_info['PaymentI18n']['name']);
			$this->loadModel('AccountInformation');
			$account_info=array(
				'id'=>0,
				'account_type'=>1,
				'payer'=>$receipt_account,
				'payee'=>isset($orderuser['User']['first_name'])&&trim($orderuser['User']['first_name'])!=''?$orderuser['User']['first_name']:(isset($orderuser['User']['name'])?$orderuser['User']['name']:''),
				'receipt_account'=>$payee,
				'payment_id'=>$payment_info['Payment']['id'],
				'transaction'=>$order_info['Order']['order_code'],
				'payment_amount'=>$order_info['Order']['total'],
				'payment_time'=>'0000-00-00 00:00:00',
				'note'=>$this->ld['set_unpaid'].$order_code
			);
			$this->AccountInformation->save($account_info);
        	}
            //库存修改
            if (isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 0) {
                if (isset($order_info['Order']['shipping_status']) && $order_info['Order']['shipping_status'] == 0) {
                    $order_products = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $order_id)));
                    foreach ($order_products as $opk => $opv) {
                        //套装子商品库存
                        //已付款 未发货的商品冻结库存处理
                        if (!empty($opv['OrderProduct'])) {
                            $product_frozen = $this->Product->find('first', array('conditions' => array('Product.code' => $opv['OrderProduct']['product_code']), 'fields' => 'Product.id,Product.code,Product.frozen_quantity,Product.quantity'));
                            if (!empty($product_frozen)) {
                                $product_frozen['Product']['frozen_quantity'] = $product_frozen['Product']['frozen_quantity'] - $opv['OrderProduct']['product_quntity'];
                                //$product_frozen["Product"]["quantity"] = $product_frozen["Product"]["quantity"]+$opv["OrderProduct"]["product_quntity"];
                                $this->Product->save(array('Product' => $product_frozen['Product']));
                                //$this->Product->updateskupro($opv["OrderProduct"]["product_code"],$opv["OrderProduct"]["product_quntity"],false);
                            }
                        }
                    }
                }
            }
            //订单设为未付款的商品冻结材料处理
            if (isset($this->configs['shop-frozen-materials']) && $this->configs['shop-frozen-materials'] == 0) {
                if (isset($order_info['Order']['shipping_status']) && $order_info['Order']['shipping_status'] == 0) {
                    $order_products = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $order_id)));
                    foreach ($order_products as $opk => $opv) {
                        if (!empty($opv['OrderProduct'])) {
                            //查询使用材料
                            $pro_material = ClassRegistry::init('ProductMaterial')->find('all', array('conditions' => array('ProductMaterial.product_code' => $opv['OrderProduct']['product_code'])));
                            //减材料冻结的库存
                            if (!empty($pro_material)) {
                                $order_product_id = $opv['OrderProduct']['id'];
                                foreach ($pro_material as $mk => $mv) {
                                    $material = ClassRegistry::init('Material');
                                    $material_info = $material->find('first', array('conditions' => array('Material.code' => $mv['ProductMaterial']['product_material_code'])));
                                    //订单商品使用材料
                                    $order_pro_material = ClassRegistry::init('OrderMaterialProduct')->find('first', array('conditions' => array('OrderMaterialProduct.product_code' => $opv['OrderProduct']['product_code'], 'OrderMaterialProduct.order_id' => $order_id, 'OrderMaterialProduct.order_product_id' => $order_product_id, 'OrderMaterialProduct.material_product_code' => $mv['ProductMaterial']['product_material_code'])));
                                    //更新冻结材料
                                    $material_info['Material']['frozen_quantity'] -= $order_pro_material['OrderMaterialProduct']['material_qty'];
                                    $material_info['Material']['quantity'] = $material_info['Material']['quantity'] + $order_pro_material['OrderMaterialProduct']['material_qty'];
                                    $material->updateAll(array('Material.frozen_quantity' => $material_info['Material']['frozen_quantity'], 'Material.quantity' => $material_info['Material']['quantity']), array('Material.code' => $mv['ProductMaterial']['product_material_code']));
                                }
                            }
                        }
                    }
                }
            }
            $this->Order->update_order(array('id' => $order_id, 'payment_status' => '0', 'payment_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'operator_id' => $this->admin['id'], 'money_paid' => 0));
            //OrderAction
            $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, 0, $shipping_status, $operation_notes);
            $msg = $this->ld['order'].' '.$order_code.' '.$this->ld['set_unpaid'];
        }
        //订单配货中
        if ($order_status_message_code == 'order_picking') {
            $picking_type = isset($_REQUEST['picking_type']) ? $_REQUEST['picking_type'] : '0';
            $this->Order->update_order(array('id' => $order_id, 'shipping_status' => '3', 'shipping_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'operator_id' => $this->admin['id'], 'picking_type' => $picking_type));
            //OrderAction
            $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status, 3, $operation_notes);
            $msg = $this->ld['order'].' '.$order_code.' '.$this->ld['set_picked'];
        }
        //订单发货
        if ($order_status_message_code == 'order_delivery' || $order_status_message_code == 'order_payment_delivery') {
            $this->Order->update_order(array('id' => $order_id, 'status' => '1', 'confirm_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'operator_id' => $this->admin['id']));
            //订单发货修改库存
            //if(isset($this->configs['enable_decrease_stock_time']) && $this->configs['enable_decrease_stock_time']==3){
            $order_product_list = $this->OrderProduct->find('list', array('fields' => array('OrderProduct.product_code', 'OrderProduct.product_quntity'), 'conditions' => array('OrderProduct.order_id' => $order_id, 'OrderProduct.status' => 1)));
            foreach ($order_product_list as $opk => $opv) {
                $update_proudct = $this->Product->find('first', array('conditions' => array('Product.code' => $opk)));
                $this->Product->updateAll(array('Product.frozen_quantity' => $update_proudct['Product']['frozen_quantity'] - $opv), array('Product.id' => $update_proudct['Product']['id']));
                //$this->Product->updateskupro($opk,$opv,true);
            }
            //}
            if ($picking_type == 1) {
                $order_product_list = $this->OrderProduct->find('list', array('fields' => array('OrderProduct.product_code', 'OrderProduct.id'), 'conditions' => array('OrderProduct.order_id' => $order_id, 'OrderProduct.status' => 1)));
                foreach ($order_product_list as $opk => $opv) {
                    //查询使用材料
                    $pro_material = ClassRegistry::init('ProductMaterial')->find('all', array('conditions' => array('ProductMaterial.product_code' => $opk)));
                    //减材料库存
                    if (!empty($pro_material)) {
                        $order_product_id = $opv;
                        foreach ($pro_material as $mk => $mv) {
                            $material = ClassRegistry::init('Material');
                            $material_info = $material->find('first', array('conditions' => array('Material.code' => $mv['ProductMaterial']['product_material_code'])));
                            //订单商品使用材料
                            $order_pro_material = ClassRegistry::init('OrderMaterialProduct')->find('first', array('conditions' => array('OrderMaterialProduct.product_code' => $opk, 'OrderMaterialProduct.order_id' => $order_id, 'OrderMaterialProduct.order_product_id' => $order_product_id, 'OrderMaterialProduct.material_product_code' => $mv['ProductMaterial']['product_material_code'])));
                            //更新冻结材料
                            $material_info['Material']['frozen_quantity'] -= $order_pro_material['OrderMaterialProduct']['material_qty'];
                            $material->updateAll(array('Material.frozen_quantity' => $material_info['Material']['frozen_quantity']), array('Material.code' => $mv['ProductMaterial']['product_material_code']));
                        }
                    }
                }
            }
            $order_logistics_company_id = $_REQUEST['order_logistics_company_id'];//物流公司
            if (empty($order_logistics_company_id)) {
                $logistics_company = '无需物流';
            } else {
                $logistics_company_infos = $this->LogisticsCompany->find('first', array('conditions' => array('LogisticsCompany.id' => $order_logistics_company_id), 'fields' => 'LogisticsCompany.id,LogisticsCompany.name,LogisticsCompany.type'));
                $logistics_company = empty($logistics_company_infos['LogisticsCompany']['name']) ? '' : $logistics_company_infos['LogisticsCompany']['name'];
            }
            $invoice_no = $_REQUEST['order_invoice_no'];//发货单号
            //检查是否有关联数据发送信息给关注用户
            $openUserIds = $this->OpenRelation->getOpenUserIdListByCode($order_code, 1, 'wechat');
            if (!empty($openUserIds)) {
                $openIds = $this->OpenUser->getOpenIdListByUserId($openUserIds);
                //发送最新的状态给用户
                if (!empty($openIds)) {
                    $content = '你关注的订单：'.$order_code."已发货！\n".$logistics_company.':'.$invoice_no;
                    foreach ($openIds as $id => $openId) {
                        $this->sendMsg($id, $openId, $content, 'wechat');
                    }
                }
            }
            //修改商品状态及租赁订单起始日期
            $products = $this->OrderProduct->find('all', array('fields' => array('OrderProduct.id'), 'conditions' => array('OrderProduct.order_id' => $order_id)));
            foreach ($products as $k => $v) {
                $order_product_data['OrderProduct']['id'] = $v["OrderProduct"]["id"];
                $order_product_data['OrderProduct']['status'] = "2";
                if(isset($order_info['Order']['lease_type'])&&$order_info['Order']['lease_type']=='L'){
                    $order_product_data['OrderProduct']['begin_date'] = date("Y-m-d H:i:s");
                }
                $this->OrderProduct->saveAll($order_product_data);
            }
            //邮件模板
            $user_info = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
            if (!empty($user_info['User']['email'])) {
                $consignee = $user_info['User']['name'];//template
                $formated_add_time = $order_info['Order']['created'];//template
                $products = array();//template
                $products = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $order_id)));
                $products_info = '';
                foreach ($products as $k => $v) {
                    $products_info .= '------------------------------------- <br />';
                    $products_info .= $v['OrderProduct']['product_quntity'].'*'.$v['OrderProduct']['product_name'].'<br />';
//				$products_info.=$v['OrderProduct']['product_quntity']."<br />";
                    $products_info .= '------------------------------------- <br />';
//				$order_list=$this->OrderProduct->find('all',array('conditions'=>array('OrderProduct.order_id'=>$order_id)));
//				$product_id=$v['OrderProduct']['product_id'];
//				$frozen_quantity=$v['Product']['frozen_quantity']-$v['OrderProduct']['product_quntity'];
//				$product_quntity=$this->Stock->get_total_num($v['OrderProduct']['product_code'])-$frozen_quantity;
//				$update_product=array('id' => $product_id,'quantity' => $product_quntity,'frozen_quantity'=>$frozen_quantity);
//				$this->Product->save($update_product);
                    /* if(isset($this->configs['enable_decrease_stock_time']) && $this->configs['enable_decrease_stock_time']==2){
                         $product_quntity=$v['Product']['quantity']-$v['OrderProduct']['product_quntity'];
                         $update_product=array('id' => $product_id,'quantity' => $product_quntity);
                         $this->Product->save($update_product);
                     }*/
                }
                $url = $this->server_host.$this->webroot.'orders/view/'.$order_id;
                $shop_name = $this->configs['shop_name'];//template
                $shop_url = $this->server_host.$this->webroot;//template
                $send_date = date('Y-m-d H:m:s');//template
                //读模板
                $template = 'out_confirm';
                $this->NotifyTemplateType->set_locale($this->backend_locale);
                $totify_template_info=$this->NotifyTemplateType->typeformat($template);
                if (empty($totify_template_info)) {
                    $msg = $this->ld['no_delivery_email_template'];
                } else {
                    foreach($totify_template_info as $template_type=>$totify_template){
                        if($template_type=="email"&&$user_info['User']['email']!=""){
                            $subject = $totify_template['NotifyTemplateTypeI18n']['title'];
                            $subject = str_replace('$shop_name', $shop_name, $subject);
                            $html_body=addslashes($totify_template['NotifyTemplateTypeI18n']['param01']);
                            eval("\$html_body = \"$html_body\";");
                            $text_body = $totify_template['NotifyTemplateTypeI18n']['param02'];
                            eval("\$text_body = \"$text_body\";");
                            $mail_send_queue = array(
                                'sender_name' => $shop_name,//发送从姓名
                                'receiver_email' =>$user_info['User']['email'],//接收人姓名;接收人地址
                                'cc_email' => ';',
                                'bcc_email' => ';',
                                'title' => $subject,
                                'html_body' => $html_body,
                                'text_body' => $text_body,
                                'sendas' => 'html',
                                'flag' => 0,
                                'pri' => 0
                            );
                            $this->Notify->send_email($mail_send_queue, $this->configs);
                        }else if($template_type=="mobile"&&$user_info['User']['mobile']!=""){
                            $sms_content=$Notify_template['sms']['NotifyTemplateTypeI18n']['param02'];
                            eval("\$sms_content = \"$sms_content\";");
                            $sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
                            $this->Notify->send_sms($user_info['User']['mobile'],$sms_content,$sms_kanal,$this->configs,false);
                        }
                    }
                }
            }
            if ($order_info['Order']['type'] != 'ioco') {
                $this->Order->update_order(array('id' => $order_id, 'shipping_status' => '1', 'taobao_delivery_send' => '1', 'error_count' => '0', 'shipping_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'invoice_no' => $invoice_no, 'logistics_company_id' => $order_logistics_company_id));
                if ($order_status_message_code == 'order_delivery') {
                    //OrderAction
                    $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status, 1, $operation_notes);
                } else {
                    //OrderAction
                    $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, 1, 2, 1, $operation_notes);
                }
                $msg = $this->ld['order'].' '.$order_code.$this->ld['set_shipped'];
                $order_msg = $this->ld['order'].' '.$order_code.$this->ld['set_shipped'].' ';
                if (!empty($user_info['User']['email']) && empty($template)) {
                    $msg = $order_msg.$this->ld['no_delivery_email_template'];
                } elseif (!empty($user_info['User']['email']) && !empty($template)) {
                    $this->MailSendQueue->saveAll(array('MailSendQueue' => $mailsendqueue));//保存邮件队列
                    $result_msg = $this->Notify->send_email($mailsendqueue, $this->configs);
                    if ($result_msg) {
                        $msg = $order_msg.$this->ld['mail_sent_successfully'];
                    } else {
                        $msg = $order_msg.$this->ld['send_mail_failed'];
                    }
                }
            } else {
                $order_msg = $this->ld['order'].' '.$order_code.$this->ld['set_shipped'].' ';
                if ($this->is_aim($order_id)) {
                    if ($this->aim_status()) {
                        if ($this->app_auth_aim()) {
                            $x_array = $this->aim_capture($order_id);
                            if ($x_array['return_code'] != 1) {
                                $msg = $x_array['reason_text'];
                            } else {
                                $this->Order->update_order(array('id' => $order_id, 'shipping_status' => '1', 'taobao_delivery_send' => '1', 'shipping_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'invoice_no' => $invoice_no, 'logistics_company_id' => $order_logistics_company_id));
                                if ($order_status_message_code == 'order_delivery') {
                                    //OrderAction
                                    $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status, 1, $operation_notes);
                                } else {
                                    //OrderAction
                                    $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, 1, 2, 1, $operation_notes);
                                }
                                $msg = $this->ld['order'].' '.$order_code.' Shipped ';
                                if (!empty($user_info['User']['email']) && empty($template)) {
                                    $msg = $order_msg.$this->ld['no_delivery_email_template'];
                                } elseif (!empty($user_info['User']['email']) && !empty($template)) {
                                    $this->MailSendQueue->saveAll(array('MailSendQueue' => $mailsendqueue));//保存邮件队列
                                    $result_msg = $this->Notify->send_email($mailsendqueue, $this->configs);
                                    if ($result_msg) {
                                        $msg = $order_msg.$this->ld['mail_sent_successfully'];
                                    } else {
                                        $msg = $order_msg.$this->ld['send_mail_failed'];
                                    }
                                }
                            }
                        } else {
                            $msg = $this->ld['payment_no_installed'];
                        }
                    } else {
                        $this->Order->update_order(array('id' => $order_id, 'shipping_status' => '1', 'shipping_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'invoice_no' => $invoice_no, 'logistics_company_id' => $order_logistics_company_id));
                        if ($order_status_message_code == 'order_delivery') {
                            //OrderAction
                            $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status, 1, $operation_notes);
                        } else {
                            //OrderAction
                            $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, 1, 2, 1, $operation_notes);
                        }
                        $msg = $this->ld['order'].' '.$order_code.$this->ld['set_shipped'];
                        if (!empty($user_info['User']['email']) && empty($template)) {
                            $msg = $order_msg.$this->ld['no_delivery_email_template'];
                        } elseif (!empty($user_info['User']['email']) && !empty($template)) {
                            $this->MailSendQueue->saveAll(array('MailSendQueue' => $mailsendqueue));//保存邮件队列
                            $result_msg = $this->Notify->send_email($mailsendqueue, $this->configs);
                            if ($result_msg) {
                                $msg = $order_msg.$this->ld['mail_sent_successfully'];
                            } else {
                                $msg = $order_msg.$this->ld['send_mail_failed'];
                            }
                        }
                    }
                } else {
                    $invoice_no = $_REQUEST['order_invoice_no'];//发货单号
                    $order_logistics_company_id = $_REQUEST['order_logistics_company_id'];//物流公司
                    $onfo = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));
                    if ($this->alipay_check_go($onfo)) {
                        $onfo['invoice_no'] = $invoice_no;
                        $onfo['order_logistics_company_id'] = $order_logistics_company_id;
                        $ali_gofoo = $this->alipay_go($onfo);
                        if ($ali_gofoo['type'] == 1) {
                            $this->Order->update_order(array('id' => $order_id, 'shipping_status' => '1', 'shipping_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'invoice_no' => $invoice_no, 'logistics_company_id' => $order_logistics_company_id));
                            if ($order_status_message_code == 'order_delivery') {
                                //OrderAction
                                $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status, 1, $operation_notes);
                            } else {
                                //OrderAction
                                $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, 1, 2, 1, $operation_notes);
                            }
                            $msg = $this->ld['order'].' '.$order_code.$this->ld['set_shipped'];
                            if (!empty($user_info['User']['email']) && empty($template)) {
                                $msg = $order_msg.$this->ld['no_delivery_email_template'];
                            } elseif (!empty($user_info['User']['email']) && !empty($template)) {
                                $this->MailSendQueue->saveAll(array('MailSendQueue' => $mailsendqueue));//保存邮件队列
                                $result_msg = $this->Notify->send_email($mailsendqueue, $this->configs);
                                if ($result_msg) {
                                    $msg = $order_msg.$this->ld['mail_sent_successfully'];
                                } else {
                                    $msg = $order_msg.$this->ld['send_mail_failed'];
                                }
                            }
                        } else {
                            $msg = $this->ld['send_mail_failed'];
                        }
                    } else {
                        $this->Order->update_order(array('id' => $order_id, 'shipping_status' => '1', 'shipping_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'invoice_no' => $invoice_no, 'logistics_company_id' => $order_logistics_company_id));
                        if ($order_status_message_code == 'order_delivery') {
                            //OrderAction
                            $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status, 1, $operation_notes);
                        } else {
                            //OrderAction
                            $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, 1, 2, 1, $operation_notes);
                        }
                        $msg = $this->ld['order'].' '.$order_code.$this->ld['set_shipped'];
                        if (!empty($user_info['User']['email']) && empty($template)) {
                            $msg = $order_msg.$this->ld['no_delivery_email_template'];
                        } elseif (!empty($user_info['User']['email']) && !empty($template)) {
                            $this->MailSendQueue->saveAll(array('MailSendQueue' => $mailsendqueue));//保存邮件队列
                            $result_msg = $this->Notify->send_email($mailsendqueue, $this->configs);
                            if ($result_msg) {
                                $msg = $order_msg.$this->ld['mail_sent_successfully'];
                            } else {
                                $msg = $order_msg.$this->ld['send_mail_failed'];
                            }
                        }
                    }
                }
            }
            $products = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $order_id), 'fields' => array('sum(OrderProduct.product_quntity) as product_quntity', 'Order.*', 'Product.*', 'OrderProduct.*'), 'group' => 'product_code'));
            $stock_arr = array();
            $outbound_arr = array();
            $flag = 1;
            $i = 0;
            $j = 0;
            $email_content = '';
            $warn_note = '';
            $frozen_list = $this->OrderProduct->get_frozen_product_list();
            foreach ($products as $k => $v) {
                //库存处理   付款时
                if ($order_info['Order']['payment_status'] == 2) {
                    $frozen_quantity = isset($frozen_list[$v['OrderProduct']['product_code']]) ? $frozen_list[$v['OrderProduct']['product_code']] : 0;
                    $this->Product->updateAll(array('Product.frozen_quantity' => $frozen_quantity), array('Product.code' => $v['OrderProduct']['product_code']));
                }
                if ($order_info['Order']['payment_status'] == 0) {
                    //$this->Product->down_quantity($v['OrderProduct']['product_quntity'],$v['OrderProduct']['product_code']);
                }
                if (!in_array('APP-WAREHOUSE', $this->apps['codes'])) {
                    //没有装仓库应用跳过
                    continue;
                }
                if (!empty($_REQUEST['w'])) {
                    $stock = $this->Stock->find('first', array('conditions' => array('Stock.warehouse_code' => $_REQUEST['w'], 'Stock.product_code' => $v['OrderProduct']['product_code'])));
                    if (empty($stock)) {
                        $email_content .= '商品货号：'.$v['OrderProduct']['product_code'].' 库存不足,无法出库<br />';
                        $flag = 0;
                    } else {
                        $stock_arr[$k]['Stock']['quantity'] = $stock['Stock']['quantity'] - $v['0']['product_quntity'];
                        if ($stock_arr[$k]['Stock']['quantity'] < 0) {
                            $flag = 0;
                            $email_content .= '商品货号：'.$v['OrderProduct']['product_code'].' 库存不足,无法出库';
                            unset($stock_arr[$k]['Stock']['quantity']);
                        } else {
                            $stock_arr[$k]['Stock']['id'] = $stock['Stock']['id'];
                            $outbound_arr[$k]['OutboundProduct']['product_code'] = $v['OrderProduct']['product_code'];
                            $outbound_arr[$k]['OutboundProduct']['before_out'] = $stock['Stock']['quantity'];
                            $outbound_arr[$k]['OutboundProduct']['quantity'] = $v['0']['product_quntity'];
                            $i += $v['0']['product_quntity'];
                        }
                    }
                }
                //记录warn_note
                $warn_note .= $v['OrderProduct']['product_code'].','.$v['OrderProduct']['product_name'].':'.$v['OrderProduct']['product_price'].';';
                $j += $v['0']['product_quntity'];
            }
            $warn_note .= $order_info['Order']['created'].','.$order_info['Order']['total'].','.$j;
            $this->Stock->hasOne = array();
            //每个订单商品都有库存 出库
            if (!empty($stock_arr) && $flag && in_array('APP-WAREHOUSE', $this->apps['codes'])) {
                $outbound['batch_id'] = $this->get_batch_id();
                $outbound['warehouse_code'] = $_REQUEST['w'];
                $outbound['created_operator_id'] = $this->admin['id'];
                $outbound['outbound_type'] = 0;
                $outbound['quantity'] = $i;
                $this->Outbound->saveAll(array('Outbound' => $outbound));
                $outbound_id = $this->Outbound->getLastInsertId();
                foreach ($stock_arr as $k => $v) {
                    $this->Stock->updateAll(array('Stock.quantity' => $v['Stock']['quantity']), array('Stock.id' => $v['Stock']['id']));
                    $outbound_arr[$k]['OutboundProduct']['outbound_id'] = $outbound_id;
                    $this->OutboundProduct->saveAll(array('OutboundProduct' => $outbound_arr[$k]['OutboundProduct']));
                    $this->Stock->hasOne = array();
                    $total_stock = $this->Stock->find('all', array('fields' => array('SUM(Stock.quantity) AS total_stock'), 'conditions' => array('Stock.product_code' => $outbound_arr[$k]['OutboundProduct']['product_code'])));
                    //if(!in_array($outbound_arr[$k]['OutboundProduct']['product_code'],$this->get_xu_list())){
                    //	$this->Product->up_under_foz($total_stock[0][0]['total_stock'],$outbound_arr[$k]['OutboundProduct']['product_code']);
                    //}
//	                else{
//	                	$this->Product->updateAll(array('Product.quantity'=>'quantity-'.$outbound_arr[$k]['OutboundProduct']['quantity']),array('Product.code'=>$outbound_arr[$k]['OutboundProduct']['product_code']));
//	                }
                }
            }
            //订单出库数量不足报警邮件
            if ($email_content != '' && in_array('APP-WAREHOUSE', $this->apps['codes'])) {
                $email_content = '订单号：'.$order_info['Order']['order_code'].'<br />'.$email_content;
                $to_email = $this->Application->config('APP-WAREHOUSE', 'APP-WAREHOUSE-EMAIL-TO');
                if ($to_email == '') {
                    $to_email = ';';
                }
                $mailsendqueue = array(
                    'sender_name' => empty($this->configs['shop_name']) ? '--' : $this->configs['shop_name'],//发送从姓名
                    'receiver_email' => $to_email,//接收人姓名;接收人地址
                    'cc_email' => ';',
                    'bcc_email' => ';',//暗送人
                    'title' => '订单出库警告',//主题
                    'html_body' => $email_content,//内容
                    'text_body' => $email_content,//内容
                    'sendas' => 'html',
                );
                $this->Notify->send_email($mailsendqueue, $this->configs);
            }
            //订单发货没出库
            if ((empty($_REQUEST['w']) || !$flag) && in_array('APP-WAREHOUSE', $this->apps['codes'])) {
                $warn_arr = array('type' => 2,
                    'type_id' => $order_info['Order']['order_code'],
                    'note' => $warn_note,
                    'level' => 2,
                );
                //$this->WarnList->save_warning($warn_arr);
            }
            $this->notify_order_delivery($order_info['Order']['id']);
        }
        //订单未发货
        if ($order_status_message_code == 'order_unfilled') {
            $order_shipment_ids=$this->OrderShipment->find('list',array('conditions'=>array('OrderShipment.order_id'=>$order_id,'OrderShipment.status'=>'1')));
            if(!empty($order_shipment_ids)){
                $this->OrderShipmentProduct->deleteAll(array('OrderShipmentProduct.order_shipment_id'=>$order_shipment_ids));
                $this->OrderShipment->deleteAll(array('OrderShipment.order_id'=>$order_id));
            }
            $this->Order->update_order(array('id' => $order_id, 'shipping_status' => '0', 'shipping_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'operator_id' => $this->admin['id']));
            //OrderAction
            $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status, 0, $operation_notes);
            $products = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $order_id)));
            $frozen_list = $this->OrderProduct->get_frozen_product_list();
            foreach ($products as $k => $v) {
                //	$order_list=$this->OrderProduct->find('all',array('conditions'=>array('OrderProduct.order_id'=>$order_id)));
                $product_id = $v['OrderProduct']['product_id'];
                //$product_quntity=$v['Product']['quantity']+$v['OrderProduct']['product_quntity'];
                if ($order_info['Order']['payment_status'] == 2) {
                    $frozen_quantity = isset($frozen_list[$v['OrderProduct']['product_code']]) ? $frozen_list[$v['OrderProduct']['product_code']] : 0;
                    $update_product = array('id' => $product_id,'frozen_quantity' => $frozen_quantity);
                    $this->Product->save($update_product);
                }
                if ($order_info['Order']['payment_status'] == 0) {
                    //$this->Product->up_quantity($v['OrderProduct']['product_quntity'],$v['OrderProduct']['product_code']);
                }
            }
            //货到付款，设为未发货后付款状态改为未付款
            if ($is_cod) {
                $this->Order->update_order(array('id' => $order_id, 'payment_status' => '0', 'payment_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'operator_id' => $this->admin['id']));
                //$this->OrderAction->update_order_actions($order_id,$this->admin['id'],$user_id,$order_status,0,$shipping_status,$operation_notes);
            }
            $msg = $this->ld['order'].' '.$order_code.' '.$this->ld['set_unshiped'];
        }
        //订单已收货
        if ($order_status_message_code == 'order_has_been_receiving') {
            $this->Order->update_order(array('id' => $order_id, 'shipping_status' => '2', 'shipping_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'operator_id' => $this->admin['id']));
            //OrderAction
            if($is_cod){
                $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status, 2, $operation_notes);
            }
            $msg = $this->ld['order'].' '.$order_code.' '.$this->ld['set_received'];
            //货到付款，收货后状态改为已付款
            $this->Order->update_order(array('id' => $order_id, 'payment_status' => '2', 'payment_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'operator_id' => $this->admin['id']));
            if(isset($this->configs['use_point'])&&$this->configs['use_point']=='1'){
                if($is_cod){
                    $points_awarded_occasion=isset($this->configs['points_awarded_occasion'])?$this->configs['points_awarded_occasion']:'';//积分赠送场合
                    if($order_info['Order']['lease_type']!='L'){
                        $config_order_smallest=isset($this->configs['lease_order_smallest'])?$this->configs['lease_order_smallest']:0;
                        if(in_array($points_awarded_occasion,array('0','2'))&&isset($this->configs['order_points'])&&$this->configs['order_points'] > 0){
                            $user_info = $this->User->findbyid($order_info['Order']['user_id']);
                            $old_point=$user_info['User']['point'];
                            $user_info['User']['point'] += $this->configs['order_points'];
                            $user_info['User']['user_point'] += $this->configs['order_points'];
                            $this->User->save($user_info);
                            $point_log = array('id' => '',
                                'user_id' => $order_info['Order']['user_id'],
                                'point' => $old_point,
                                'point_change' => $this->configs['order_points'],
                                'log_type' => 'B',
                                'system_note' => '下单送积分',
                                'type_id' => $order_info['Order']['id'],
                            );
                            $this->UserPointLog->save($point_log);
                        }

                        if(in_array($points_awarded_occasion,array('0','2'))&&$config_order_smallest <= $order_info['Order']['subtotal']){
                            $user_info = $this->User->findbyid($order_info['Order']['user_id']);
                            $old_point=$user_info['User']['point'];
                            $user_info['User']['point'] += $this->configs['out_order_points'];
                            $user_info['User']['user_point'] += $this->configs['out_order_points'];
                            $this->User->save($user_info);
                            $point_log = array('id' => '',
                                'user_id' => $order_info['Order']['user_id'],
                                'point' => $old_point,
                                'point_change' => $this->configs['out_order_points'],
                                'log_type' => 'B',
                                'system_note' => '超过订单金额 '.$this->configs['order_smallest'].' 赠送积分',
                                'type_id' => $order_info['Order']['id'],
                            );
                            $this->UserPointLog->save($point_log);
                        }
                    }
                }
                $p = $this->Product->find('list',array('fields'=>"Product.id,Product.point"));
                $product_point = '';
                foreach ($order_info['OrderProduct'] as $v) {
                    $product_point[] = array(
                        'point' => isset($p[$v['product_id']])? $p[$v['product_id']]* $v['product_quntity']:0,
                        'name' => $v['product_name'],
                    );
                }
                if (is_array($product_point) && sizeof($product_point) > 0) {
                    foreach ($product_point as $k => $v) {
                        if ($v['point'] > 0) {
                            $user_info = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
                            $point_log = array('id' => '',
                                'user_id' => $_SESSION['User']['User']['id'],
                                'point' => $user_info['User']['point'],
                                'point_change'=>$v['point'],
                                'log_type' => 'B',
                                'system_note' => $this->ld['product'].' '.$v['name'].' '.$this->ld['give_points'],
                                'type_id' => $order_id,
                            );
                            $this->UserPointLog->save($point_log);
                            $user_info['User']['point'] += $v['point'];
                            $user_info['User']['user_point'] += $v['point'];
                            $this->User->save($user_info);
                        }
                    }
                }
            }
            $this->check_coupon($order_info);
            $order_product_ids = array();
            foreach ($order_info['OrderProduct'] as $v) {
                $order_product_ids[] = $v['id'];
            }
            $OrderProductValue_count = $this->OrderProductValue->find('count', array('conditions' => array('OrderProductValue.order_product_id' => $order_product_ids)));
            if ($OrderProductValue_count > 0) {
                $this->UserRank->user_upgrade_vip($order_info['Order']['user_id']);
            }
        }
        //订单已取消
        if ($order_status_message_code == 'order_cancel') {
            $order_product_list = $this->OrderProduct->find('all', array('fields' => array('OrderProduct.product_code', 'OrderProduct.product_quntity',"OrderProduct.product_id","Product.quantity"), 'conditions' => array('OrderProduct.order_id' => $order_id, 'OrderProduct.status' => 1)));
            $frozen_list = $this->OrderProduct->get_frozen_product_list();
            foreach($order_product_list  as $v){
                if(!isset($v['Product']))continue;
                if($order_info['Order']['shipping_status']=='0'){//未发货订单商品冻结库存还原
                    $product_id = $v['OrderProduct']['product_id'];
                    $frozen_quantity = isset($frozen_list[$v['OrderProduct']['product_code']]) ? $frozen_list[$v['OrderProduct']['product_code']] : 0;
                    $product_quntity = $v['Product']['quantity'] + $v['OrderProduct']['product_quntity'];
                    $update_product = array('id' => $product_id,'frozen_quantity' => $frozen_quantity,'quantity'=>$product_quntity);
                    $this->Product->save($update_product);
                }
            }
            $order_user_update=array();
            $users = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
            //还原用户余额支付的金额
            if (!empty($users['User']) && !empty($order_info['Order']['user_balance']) && $order_info['Order']['user_balance'] > 0) {
            		$order_user_update['balance']=$users['User']['balance'] + $order_info['Order']['user_balance'];
            }
            if(isset($this->configs['use_point'])&&$this->configs['use_point']=='1'){
            		if (!empty($users['User']) && !empty($order_info['Order']['user_balance']) && $order_info['Order']['user_balance'] > 0) {
				$order_user_update['point'] =$users['User']['point']+$order_info['Order']['point_use'];
				$point_log = array(
					'id' => 0,
	                            'user_id' => $order_info['Order']['user_id'],
	                            'point' => $users['User']['point'],
	                            'point_change' => $order_info['Order']['point_use'],
	                            'log_type' => 'O',
	                            'system_note' => "取消订单:".$order_info['Order']['order_code'].' 积分退回',
	                            'type_id' => $order_id
	                        );
	                        $this->UserPointLog->save($point_log);
            		}
            }
            //退还已支付
            if (!empty($users['User']) && !empty($order_info['Order']['money_paid']) && $order_info['Order']['money_paid'] > 0) {
            		$payment_ids=array();
			if(intval($order_info['Order']['payment_id'])>0){
				$payment_ids[]=intval($order_info['Order']['payment_id']);
			}
			if(intval($order_info['Order']['sub_pay'])>0){
				$payment_ids[]=intval($order_info['Order']['sub_pay']);
			}
			$payment_info=$this->Payment->find('first',array('conditions'=>array('Payment.id'=>$payment_ids),'order'=>'parent_id desc'));
			$config_value=array();
			if (isset($payment_info['Payment']['config']) && !empty($payment_info['Payment']['config'])) {
				$config_value = unserialize($payment_info['Payment']['config']);
			}
			$payee=$payment_info['PaymentI18n']['name'];
			$receipt_account=isset($config_value['account'])?$config_value['account']:(isset($config_value['MCHID'])?$config_value['MCHID']:$payment_info['PaymentI18n']['name']);
            		$this->loadModel('AccountInformation');
            		$account_info=array(
				'id'=>0,
				'account_type'=>1,
				'payer'=>$receipt_account,
				'payee'=>isset($users['User']['first_name'])&&trim($users['User']['first_name'])!=''?$users['User']['first_name']:(isset($users['User']['name'])?$users['User']['name']:''),
				'receipt_account'=>$payee,
				'payment_id'=>$payment_info['Payment']['id'],
				'transaction'=>$order_info['Order']['order_code'],
				'payment_amount'=>$order_info['Order']['money_paid'],
				'payment_time'=>'0000-00-00 00:00:00',
				'note'=>$this->ld['refund'].$order_code
      		);
      		$this->AccountInformation->save($account_info);
            }
            if(isset($order_user_update['balance'])){
            		$balance_log = array(
                        'user_id' => $users['User']['id'],
                        'amount' => $order_info['Order']['user_balance'],
                        'log_type' => 'O',
                        'system_note' => '订单退款:'.$order_info['Order']['order_code'],
                        'type_id' => $order_id,
                    );
                    $this->UserBalanceLog->save($balance_log);
            }
            if(!empty($order_user_update)){
            		$order_user_update['id']=$users['User']['id'];
            		$this->User->save($order_user_update);
            }
            $operator_code = $order_info['Order']['operator_code'].';6';
            $this->Order->update_order(array('id' => $order_id, 'status' => '2', 'shipping_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'operator_id' => $this->admin['id'], 'operator_code' => $operator_code));
            //OrderAction
            $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, 2, $payment_status, $shipping_status, $operation_notes);
            $products = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $order_id)));
            $frozen_list = $this->OrderProduct->get_frozen_product_list();
            foreach ($products as $k => $v) {
                $product_id = $v['Product']['id'];
                $product_code = $v['OrderProduct']['product_code'];
                if (isset($this->configs['enable_decrease_stock_time']) && $this->configs['enable_decrease_stock_time'] == 2) {
                    $product_quntity = $v['Product']['quantity'] + $v['OrderProduct']['product_quntity'];
                } else {
                    $product_quntity = $v['Product']['quantity'];
                }
                $product_frozen_quantity = isset($frozen_list[$v['OrderProduct']['product_code']]) ? $frozen_list[$v['OrderProduct']['product_code']] : 0;
                $update_product = array('id' => $product_id,'quantity' => $product_quntity,'frozen_quantity' => $product_frozen_quantity);
                $this->Product->save($update_product);
            }
            $msg = $this->ld['order'].' '.$order_code.' '.$this->ld['set_canceled'];
            $this->notify_order_cancel($order_id,$operation_notes);
        }
        //订单无效
        if ($order_status_message_code == 'order_invalid') {
            $this->Order->update_order(array('id' => $order_id, 'status' => '3', 'shipping_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'operator_id' => $this->admin['id']));
            //OrderAction
            $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, 3, $payment_status, $shipping_status, $operation_notes);
            $products = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $order_id)));
            $frozen_list = $this->OrderProduct->get_frozen_product_list();
            foreach ($products as $k => $v) {
                $product_id = $v['Product']['id'];
                $product_code = $v['OrderProduct']['product_code'];
                if (isset($this->configs['enable_decrease_stock_time']) && $this->configs['enable_decrease_stock_time'] == 2) {
                    $product_quntity = $v['Product']['quantity'] + $v['OrderProduct']['product_quntity'];
                } else {
                    $product_quntity = $v['Product']['quantity'];
                }
                $product_frozen_quantity = isset($frozen_list[$v['OrderProduct']['product_code']]) ? $frozen_list[$v['OrderProduct']['product_code']] : 0;
                $update_product = array('id' => $product_id,'quantity' => $product_quntity,'frozen_quantity' => $product_frozen_quantity);
                $this->Product->save($update_product);
            }
            $msg = $this->ld['order'].' '.$order_code.' '.$this->ld['set_invalid'];
        }
        //订单退货
        if ($order_status_message_code == 'order_returns') {
            $this->Order->update_order(array('id' => $order_id, 'shipping_status' => '5', 'shipping_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'operator_id' => $this->admin['id']));
            //OrderAction
            $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status, 5, $operation_notes);
            if (!in_array('APP-WAREHOUSE', $this->apps['codes'])) {
                $products = $this->OrderProduct->find('all', array('conditions' => array('OrderProduct.order_id' => $order_id)));
                foreach ($products as $k => $v) {
                    $product_id = $v['OrderProduct']['product_id'];
                    $product_quntity = $v['Product']['quantity'] + $v['OrderProduct']['product_quntity'];
                    $update_product = array('id' => $product_id,'quantity' => $product_quntity);
                    $this->Product->save($update_product);
                }
            }
            $msg = $this->ld['order'].' '.$order_code.' '.$this->ld['set_returned'];
        }
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_change_order_status'].' '.$this->ld['order'].':'.$order_code, $this->admin['id']);
        }
        //售后
        if ($order_status_message_code == 'after_service') {
            $this->OrderAction->update_order_action(array('order_id' => $order_id, 'from_operator_id' => $this->admin['id'], 'user_id' => $user_id, 'order_status' => $order_info['Order']['status'], 'payment_status' => $order_info['Order']['payment_status'], 'shipping_status' => $order_info['Order']['shipping_status'], 'action_note' => $_REQUEST['operation_notes']));
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['order'].$order_info['Order']['order_code'].':'.$this->ld['service'], $this->admin['id']);
            }
            $msg = $this->ld['order'].' '.$order_info['Order']['order_code'].' '.$this->ld['service'];
        }
        $result['code'] = 1;
        $result['message'] = $msg;
        //$result['message'] = '修改成功';
        echo json_encode($result);
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die;
    }

    public function sendMsg($id, $openId, $content, $openType)
    {
        $openModelInfo = $this->OpenModel->getInfoByOpenType($openType);
        $openTypeId = $openModelInfo['OpenModel']['open_type_id'];
        if (empty($openModelInfo)) {
            return;
        }
        $appId = $openModelInfo['OpenModel']['app_id'];
        $appSecret = $openModelInfo['OpenModel']['app_secret'];
        $accessToken = $openModelInfo['OpenModel']['token'];
        if (!$this->OpenModel->validateToken($openModelInfo)) {
            //无效重新获取
            $accessToken = $this->OpenModel->getAccessToken($appId, $appSecret);
            if (empty($accessToken)) {
                return;
            }
            $openModelInfo['OpenModel']['token'] = $accessToken;
            $this->OpenModel->save($openModelInfo);
        }
        $url = $this->OpenModel->getPostUrl($openType, $accessToken);
        $post_data = '{ "touser": "'.$openId.'","msgtype": "text", "text": { "content": "'.$content.'" } }';
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return;
        }
        curl_close($curl);
        $this->_saveMsg($id, $content, $openId, $openTypeId, $openType);
        return;
    }

    private function _saveMsg($id, $msg, $openId, $openTypeId, $openType)
    {
        $userMsg = array();
        $userMsg['OpenUserMessage']['open_type'] = $openType;
        $userMsg['OpenUserMessage']['open_type_id'] = $openTypeId;
        $userMsg['OpenUserMessage']['open_user_id'] = $id;
        $userMsg['OpenUserMessage']['send_from'] = 0;
        $userMsg['OpenUserMessage']['msgtype'] = 'text';
        $userMsg['OpenUserMessage']['message'] = $msg;
        $this->OpenUserMessage->save($userMsg);
    }

    /*
        选择销售属性商品
    */
    public function selectskuproduct($product_code)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $this->loadModel('SkuProduct');
        $this->loadModel('ProductAttribute');
        $this->loadModel('Attribute');
        $this->layout = 'ajax';
        Configure::write('debug', 1);
        $sku_product_list = $this->SkuProduct->find('list', array('fields' => array('SkuProduct.id', 'SkuProduct.sku_product_code'), 'conditions' => array('SkuProduct.product_code' => $product_code)));
        $cond['Product.status'] = '1';
        $cond['Product.forsale'] = '1';
        $cond['Product.code'] = $sku_product_list;
        $this->Product->set_locale($this->backend_locale);
        $this->Attribute->set_locale($this->backend_locale);
        $pro_list = $this->Product->find('all', array('conditions' => $cond));
        $pro_ids = array();
        foreach ($pro_list as $v) {
            $pro_ids[] = $v['Product']['id'];
        }
        $attr_ids = array();
        $pro_attr_infos = array();
        $attr_group_Info = array();
        $pro_have_attr = array();
        $pro_attr_list = $this->ProductAttribute->find('all', array('conditions' => array('ProductAttribute.product_id' => $pro_ids, 'ProductAttribute.locale' => $this->backend_locale)));
        foreach ($pro_attr_list as $v) {
            if (!isset($pro_have_attr[$v['ProductAttribute']['attribute_id']]) || !in_array($v['ProductAttribute']['attribute_value'], $pro_have_attr[$v['ProductAttribute']['attribute_id']])) {
                $pro_have_attr[$v['ProductAttribute']['attribute_id']][] = $v['ProductAttribute']['attribute_value'];
            }
            $attr_ids[$v['ProductAttribute']['attribute_id']] = $v['ProductAttribute']['attribute_id'];
            $pro_attr_infos[$v['ProductAttribute']['product_id']][] = $v;
            $attr_group_Info[$v['ProductAttribute']['product_id']][] = $v['ProductAttribute']['attribute_value'];
        }
        $check_attr_txt = array();
        foreach ($attr_group_Info as $v) {
            $check_attr_txt[] = implode(';', $v);
        }
        $pro_have_attr_data = array();
        foreach ($pro_have_attr as $v) {
            foreach ($v as $vv) {
                foreach ($check_attr_txt as $vvv) {
                    if (strstr($vvv, $vv)) {
                        $str_txt_arr = explode(';', $vvv);
                        if (is_array($str_txt_arr) && sizeof($str_txt_arr) > 0) {
                            foreach ($str_txt_arr as $attr_txt) {
                                if ($attr_txt != $vv) {
                                    $pro_have_attr_data[$vv][] = $attr_txt;
                                }
                            }
                        }
                    }
                }
            }
        }
        $attr_list = array();
        $attr_infos = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $attr_ids, 'Attribute.status' => 1)));
        foreach ($attr_infos as $v) {
            $attr_Id = $v['Attribute']['id'];
            if (isset($pro_have_attr[$attr_Id])) {
                $attrInfo_data = array();
                $attrInfo_data['Attribute'] = $v['Attribute'];
                $attrInfo_data['AttributeI18n'] = $v['AttributeI18n'];
                $attrInfo_data['AttributeI18n']['attr_value'] = implode("\n", $pro_have_attr[$attr_Id]);
                $attr_list[] = $attrInfo_data;
            }
        }
        $order_product_info = $this->Product->find('first', array('conditions' => array('Product.code' => $product_code)));
        $this->set('order_product_info', $order_product_info);
        $this->set('attr_list', $attr_list);
        $this->set('attr_check', $pro_have_attr_data);
    }

    public function check_sales_attribute($pro_code = '')
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        if ($this->RequestHandler->isPost()) {
            $this->loadModel('SkuProduct');
            $this->loadModel('ProductAttribute');
            $this->layout = 'ajax';
            Configure::write('debug', 1);
            $result['flag'] = '0';
            $sku_pro_info = $this->Product->find('first', array('conditions' => array('Product.code' => $pro_code)));
            if (empty($sku_pro_info)) {
                $result['data'] = $this->ld['not_yet'].$this->ld['product'];
            } else {
                $result['flag'] = '1';
                $result['data'] = $sku_pro_info;
            }
            $sku_code_list = $this->SkuProduct->find('list', array('conditions' => array('SkuProduct.product_code' => $pro_code), 'fields' => 'SkuProduct.sku_product_code'));
            if (!empty($sku_code_list)) {
                $sku_id_list = $this->Product->find('list', array('conditions' => array('Product.code' => $sku_code_list), 'fields' => 'Product.id'));
                $attr_cond['ProductAttribute.product_id'] = $sku_id_list;
                $attr_cond['ProductAttribute.locale'] = $this->backend_locale;
                $attr_cond['ProductAttribute.attribute_id'] = isset($_REQUEST['attr_id']) ? $_REQUEST['attr_id'] : 0;
                $attr_cond['ProductAttribute.attribute_value'] = isset($_REQUEST['attr_value']) ? $_REQUEST['attr_value'] : 0;
                $attr_info = $this->ProductAttribute->find('first', array('fields' => array('ProductAttribute.product_id'), 'conditions' => $attr_cond));
                if (!empty($attr_info)) {
                    $attr_pro_info = $this->Product->find('first', array('conditions' => array('Product.id' => $attr_info['ProductAttribute']['product_id'])));
                    if (!empty($attr_pro_info)) {
                        $result['flag'] = '2';
                        $result['data'] = $attr_pro_info;
                    }
                }
            }
            $this->layout = 'ajax';
            Configure::write('debug', 0);
            die(json_encode($result));
        } else {
            $this->redirect('/orders/');
        }
    }

    /**
     *添加订单商品.
     */
    public function add_order_product()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $order_id = $_REQUEST['order_id'];
        $order_product_id = trim($_REQUEST['order_product_id']);//订单商品货号
        $order_product_code = trim($_REQUEST['order_product_code']);//订单商品货号
        if (isset($_REQUEST['order_product_id'])) {
            $order_product_id = trim($_REQUEST['order_product_id']);//订单商品id
        }
        $this->Product->set_locale($this->backend_locale);
        
        if(isset($_POST['order_product_number'])&&trim($_POST['order_product_number'])!=''){
        	$order_product_number_info=$this->OrderProduct->find('count',array('conditions'=>array('OrderProduct.product_number'=>trim($_POST['order_product_number']),'OrderProduct.del_status'=>'1')));
        	if($order_product_number_info>0){
        		$result['code'] = 0;
            		$result['message'] = $this->ld['code_already_exists'];
            		die(json_encode($result));
        	}
        }
        //属性为空
        $order_product_cond=array();
        $order_product_cond['OrderProduct.order_id']=$order_id;
        $order_product_cond['OrderProduct.product_id']=$order_product_id;
        $order_product_cond['OrderProduct.product_code']=$order_product_code;
        $order_product_cond['and']['or'][]['OrderProduct.product_attrbute']='';
        $order_product_cond['and']['or'][]['OrderProduct.product_attrbute']=null;
        $order_product_cond['OrderProduct.parent_product_id']=0;
        $order_product_cond['OrderProduct.product_number']='';
        $order_product_cond['OrderProduct.del_status']='1';
        $order_product_data = $this->OrderProduct->find('first', array('conditions' => $order_product_cond));
        //查看订单是否已存在要添加的商品
        if (!empty($order_product_data)) {
            //如果属性相同数量叠加
            //if($order_product_data['OrderProduct']['product_attrbute']==""||$order_product_data['OrderProduct']['product_attrbute']==null){
            $order_product_data['OrderProduct']['product_quntity'] += 1;
            $this->OrderProduct->saveAll($order_product_data);
            $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));//订单信息
            $operator_code = $order_info['Order']['operator_code'].';3';
            $this->Order->updateAll(array('Order.operator_code' => "'".$operator_code."'"), array('Order.id' => $order_id));
            //	}
            //$msg = $this->ld['product']." ".$order_product_code." ".$this->ld['already_exists_in_order'];
        }
        if (empty($order_product_data) || (!empty($order_product_data) && $order_product_data['OrderProduct']['product_attrbute'] != '' || $order_product_data['OrderProduct']['product_attrbute'] != null)) {
            $product_info = $this->Product->product_first_get($order_product_code, $this->locale);
            if (empty($product_info)) {
                $msg = $this->ld['product'].' '.$order_product_code.' '.$this->ld['not_exist'];
            } else {
                if (isset($order_product_id) && $order_product_id != $product_info['Product']['id']) {
                    $this->loadModel('SkuProduct');
                    $sku_pro_info = $this->Product->find('first', array('conditions' => array('Product.id' => $order_product_id)));
                    if (!empty($sku_pro_info)) {
                        $sku_pro = $this->SkuProduct->find('first', array('conditions' => array('SkuProduct.product_code' => $sku_pro_info['Product']['code'], 'SkuProduct.sku_product_code' => $order_product_code)));
                    }
                }
                $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));//订单信息
                if (in_array('APP-DEALER', $this->apps['codes']) && $order_info['Order']['type'] == 'dealer' && !empty($order_info['Order']['type_id'])) {
                    $this->loadModel('Dealer');
                    $dealer = $this->Dealer->find('first', array('conditions' => array('Dealer.id' => $order_info['Order']['type_id'])));//订单信息
                    if (!empty($dealer)) {
                        $adjust_fee = $product_info['Product']['shop_price'] * $dealer['Dealer']['discount'] - $product_info['Product']['shop_price'];
                    }
                }
                $product_price=isset($sku_pro['SkuProduct']['price']) ? $sku_pro['SkuProduct']['price'] : $product_info['Product']['shop_price'];
                if($order_info["Order"]["lease_type"]=="L"){
                    $lease_info= $this->ProductLease->find('first', array('conditions' => array('ProductLease.product_code' => $product_info['Product']['code'])));
                }
                //添加关联套装商品
                $package_info = $this->PackageProduct->find('all', array('conditions' => array('PackageProduct.product_id' => isset($sku_pro_info['Product']['id']) ? $sku_pro_info['Product']['id'] : $product_info['Product']['id'])));
                if (!empty($package_info)) {
                    $order_product_price=isset($lease_info['ProductLease']['lease_price']) ? $lease_info['ProductLease']['lease_price'] : $product_price;
                    $package_product_data=array();
                    $PackageProduct_total=0;
                    foreach ($package_info as $package_k => $package_v) {
                        $package_product_info=$this->Product->findbyid($package_v['PackageProduct']['package_product_id']);
                        if(empty($package_product_info))continue;
                        $PackageProduct_total+=$package_product_info['Product']['shop_price'];
                        $package_product_data[$package_v['PackageProduct']['package_product_id']]=$package_product_info;
                    }
                    $p_mun = 0;
                    $PackageProduct_proportion=$order_product_price/$PackageProduct_total;
                    $PackageProduct_sutotal=0;
                    foreach ($package_info as $package_k => $package_v) {
                        $child_orderproduct = $this->OrderProduct->find('first', array('conditions' => array('order_id' => $order_id, 'product_id' => $package_v['PackageProduct']['package_product_id'], 'product_code' => $package_v['PackageProduct']['package_product_code'], 'product_attrbute' => null)));
                        if (!empty($child_orderproduct)) {
                            $child_orderproduct['OrderProduct']['product_quntity'] += 1;
                            $this->OrderProduct->saveAll($child_orderproduct);
                        } else {
                            $package_product_id=$package_v['PackageProduct']['package_product_id'];
                            $update_proudct = isset($package_product_data[$package_product_id])?$package_product_data[$package_product_id]:array();
                            if(empty($update_proudct))continue;
                            if($p_mun<sizeof($package_info)-1){
                                $PackageProduct_price=$PackageProduct_proportion*$update_proudct['Product']['shop_price'];
                                $PackageProduct_price = number_format($PackageProduct_price, 2, '.', ' '); //套装单品价格
                                $PackageProduct_sutotal+=$PackageProduct_price;
                            }else{
                                $PackageProduct_price=$order_product_price-$PackageProduct_sutotal;
                            }
                            $order_product_array = array(
                                'order_id' => $order_id,
                                'product_id' => $package_v['PackageProduct']['package_product_id'],
                                'product_name' => $package_v['PackageProduct']['package_product_name'],
                                'product_code' => $package_v['PackageProduct']['package_product_code'],
                                'product_quntity' => $package_v['PackageProduct']['package_product_qty'],
                                'product_price'=>$PackageProduct_price,
                                'parent_product_id' => isset($sku_pro_info['Product']['id']) ? $sku_pro_info['Product']['id'] : $product_info['Product']['id'],
                            );
                            $this->OrderProduct->saveAll($order_product_array);
                            $x = array();
                            $x = ClassRegistry::init('DisupdateList')->find('list', array('fields' => array('DisupdateList.product_code')));
                            //订单产生的时候 加冻结库存 不减库存
                            if (!in_array($update_proudct['Product']['code'], $x) && isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 1) {
                                $update_proudct['Product']['frozen_quantity'] += $package_v['PackageProduct']['package_product_qty'];
                                $update_proudct['Product']['quantity'] = $update_proudct['Product']['quantity'] - $package_v['PackageProduct']['package_product_qty'];
                                $this->Product->updateAll(array('Product.frozen_quantity' => $update_proudct['Product']['frozen_quantity'], 'Product.quantity' => $update_proudct['Product']['quantity']), array('Product.id' => $package_v['PackageProduct']['package_product_id']));
                                $this->Product->updateskupro($update_proudct['Product']['code'], $package_v['PackageProduct']['package_product_qty'], true);
                            }
                            //订单产生的时候 加冻结材料
                            if (!in_array($update_proudct['Product']['code'], $x) && isset($this->configs['shop-frozen-materials']) && $this->configs['shop-frozen-materials'] == 1 && $child_orderproduct[$p_mun]['parent_product_id'] != 0) {
                                //查询使用材料
                                $pro_material = ClassRegistry::init('ProductMaterial')->find('all', array('conditions' => array('ProductMaterial.product_code' => $update_proudct['Product']['code'])));
                                //减材料库存
                                if (!empty($pro_material)) {
                                    foreach ($pro_material as $mk => $mv) {
                                        $material = ClassRegistry::init('Material');
                                        $material_info = $material->find('first', array('conditions' => array('Material.code' => $mv['ProductMaterial']['product_material_code'])));
                                        if (empty($material_info)) {
                                            continue;
                                        }
                                        $order_material_product_data = array(
                                            'order_id' => $order_id,
                                            'order_product_id' => $order_product_id,
                                            'product_code' => $mv['ProductMaterial']['product_code'],
                                            'material_product_code' => $mv['ProductMaterial']['product_material_code'],
                                            'material_qty' => $mv['ProductMaterial']['quantity'],
                                        );
                                        $om_product = ClassRegistry::init('OrderMaterialProduct');
                                        $result_om_product = $om_product->check_order_pro_material($order_id, $order_product_id, $mv['ProductMaterial']['product_code'], $mv['ProductMaterial']['product_material_code']);
                                        if ($result_om_product) {
                                            $om_product->saveAll($order_material_product_data);
                                        }
                                        $material_info['Material']['frozen_quantity'] += $mv['ProductMaterial']['quantity'];
                                        $material_info['Material']['quantity'] -= $mv['ProductMaterial']['quantity'];
                                        $material->updateAll(array('Material.frozen_quantity' => $material_info['Material']['frozen_quantity'], 'Material.quantity' => $material_info['Material']['quantity']), array('Material.code' => $mv['ProductMaterial']['product_material_code']));
                                    }
                                }
                            }
                            $this->Product->updateAll(array('Product.sale_stat' => $update_proudct['Product']['sale_stat'] + $package_v['PackageProduct']['package_product_qty']), array('Product.id' => $package_v['PackageProduct']['package_product_id']));
                        }
                        $p_mun++;
                    }
                }
                $order_product_array = array(
                    'order_id' => $order_id,
                    'product_id' => isset($sku_pro_info['Product']['id']) ? $sku_pro_info['Product']['id'] : $product_info['Product']['id'],
                    'product_name' => isset($sku_pro_info['ProductI18n']['name']) ? $sku_pro_info['ProductI18n']['name'] : $product_info['ProductI18n']['name'],
                    'product_code' => $product_info['Product']['code'],
                    'product_number'=>isset($_POST['order_product_number'])?trim($_POST['order_product_number']):'',
                    'lease_type'=>$order_info["Order"]["lease_type"],
                    'lease_unit'=>isset($lease_info['ProductLease']['unit'])&&$lease_info['ProductLease']['unit']!="" ? $lease_info['ProductLease']['unit'] : 0,
                    'product_quntity' => 1,
                    'product_price' =>isset($lease_info['ProductLease']['lease_price']) ? $lease_info['ProductLease']['lease_price'] : $product_price,
                    'purchase_price'=>$product_price,
                    'adjust_fee' => isset($adjust_fee) ? $adjust_fee : 0,
                    'product_weight' => isset($sku_pro_info['Product']['weight']) ? $sku_pro_info['Product']['weight'] : $product_info['Product']['weight'],
                );
                $this->OrderProduct->saveAll(array('OrderProduct' => $order_product_array));
                $operator_code = $order_info['Order']['operator_code'].';3';
                $this->Order->updateAll(array('Order.operator_code' => "'".$operator_code."'", 'Order.modified' => "'".date('Y-m-d H:i:s')."'"), array('Order.id' => $order_id));
                $operation_notes = '添加订单商品货号'.$product_info['Product']['code'];
                $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $order_info['Order']['user_id'], $order_info['Order']['status'], $order_info['Order']['payment_status'], $order_info['Order']['shipping_status'], $operation_notes);
                //如果是付款状态是减库存 加冻结库存
                if ($order_info['Order']['payment_status'] == 2) {
                    $product_info['Product']['quantity'] = $product_info['Product']['quantity'] - 1;
                    $product_info['Product']['frozen_quantity'] = $product_info['Product']['frozen_quantity'] + 1;
                    $this->Product->save($product_info);
                }
                $msg = $this->ld['product'].' '.$product_info['Product']['code'].' '.$this->ld['add_successful'];
                //操作员日志
                if ($this->configs['operactions-log']  == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'订单号'.'.'.$order_info['Order']['order_code'].' '.'添加商品货号为'.'.'.$product_info['Product']['code'], $this->admin['id']);
                }
            }
        }
        $result['order_product_id'] = $this->OrderProduct->id;
        $total = $this->update_order_product($order_id);
        $result['total'] = $total;
        $result['hasproduct'] = true;
        $need_pay = $this->need_pay($order_id);
        $result['need_pay'] = $need_pay;
        $result['code'] = 1;
        $order_info = $this->Order->find('first', array('fields' => array('id', 'order_code','insure_fee','discount'), 'conditions' => array('Order.id' => $order_id),'recursive'=>'-1'));
        if(isset($order_info['Order']['insure_fee'])){
            	$result['insure_fee'] = sprintf('%01.2f', $order_info['Order']['insure_fee']);
        }
        if(isset($order_info['Order']['discount'])){
            	$result['discount'] = sprintf('%01.2f', $order_info['Order']['discount']);
        }
        die(json_encode($result));
    }

    /**
     *删除订单.
     *
     *@param int $order_id 订单ID
     */
    public function delete_order($order_id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_remove',false)&&!$this->operator_privilege('lease_orders_remove',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $this->Order->hasOne = array();
        $this->Order->hasMany = array();
        $this->Order->belongsTo = array();
        $this->Order->deleteAll(array('id' => $order_id));
        $this->OrderProduct->hasOne = array();
        $this->OrderProduct->deleteAll(array('order_id' => $order_id));
        $this->OrderProductValue->deleteAll(array('order_id' => $order_id));
        $operation_notes = '删除订单';
        $this->OrderAction->update_order_actions($order_id, $this->admin['id'], 0, 0, 0, 0, $operation_notes);
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_order'].':'.$order_id, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function batch_delete($order_checkboxes, $order_status)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_remove',false)&&!$this->operator_privilege('lease_orders_remove',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $this->Order->hasOne = array();
        $this->Order->hasMany = array();
        $this->Order->belongsTo = array();
        $this->OrderProduct->hasOne = array();
        foreach ($order_checkboxes as $k => $v) {
            //查询是否是付款 发货的订单
            $orderInfo = $this->Order->find('first', array('conditions' => array('Order.id' => $v)));
            if (!empty($orderInfo) && ($orderInfo['Order']['payment_status'] == 2 || $orderInfo['Order']['shipping_status'] == 1 || $orderInfo['Order']['shipping_status'] == 2)) {
                continue;
            }
            $this->Order->deleteAll(array('id' => $v));
            $this->OrderProduct->deleteAll(array('order_id' => $v));
            $this->OrderProductValue->deleteAll(array('order_id' => $v));
            $operation_notes = '批量删除订单'.$v;
            $this->OrderAction->update_order_actions($v, $this->admin['id'], 0, 0, 0, 0, $operation_notes);
        }
        if (isset($order_status)) {
            $this->redirect('/orders/?order_status='.$order_status);
        } else {
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
    }

    /**
     *删除订单商品.
     *
     *@param int $order_id 订单ID
     *@param int $order_product_id 订单商品ID
     */
    public function delete_order_product($order_id, $order_product_id)
    {
        Configure::write('debug', 0);
        $this->layout = 'ajax';
         if($svshow->operator_privilege("orders_edit") == false){
	             $result['code'] = 0;
	             $result['message'] = $this->ld['have_no_operation_perform'];
	             die(json_encode($result));
         }
        $order_code = $this->Order->find('first', array('fields' => array('id', 'order_code'), 'conditions' => array('Order.id' => $order_id)));
        $this->OrderProduct->hasOne = array();
        $order_info = $this->OrderProduct->find('first', array('conditions' => array('OrderProduct.order_id' => $order_id, 'OrderProduct.id' => $order_product_id)));//订单信息
        if(!empty($order_info['OrderProduct']['product_id'])){
            $child_order_product=$this->OrderProduct->find('list',array('fields'=>'OrderProduct.id','conditions'=>array('OrderProduct.order_id' => $order_id, 'OrderProduct.parent_product_id' => $order_info['OrderProduct']['id'])));
            if(!empty($child_order_product)){
                $this->OrderProduct->deleteAll(array('id' => $child_order_product));
                $this->OrderProductValue->deleteAll(array('order_product_id' => $child_order_product));
            }
        }
        $this->OrderProduct->deleteAll(array('id' => $order_product_id));
        $this->OrderProductValue->deleteAll(array('order_product_id' => $order_product_id));
        $total = $this->update_order_product($order_id);
        $result['total'] = $total;
        $need_pay = $this->need_pay($order_id);
        $result['need_pay'] = $need_pay;
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'订单号'.'.'.$order_code['Order']['order_code'].' '.'删除商品货号'.'.'.$order_info['OrderProduct']['product_code'], $this->admin['id']);
        }
        $product_count = $this->OrderProduct->find('count', array('conditions' => array('OrderProduct.order_id' => $order_id)));
        $product_info = $this->Product->find('first', array('conditions' => array('Product.code' => $order_info['OrderProduct']['product_code'])));
        $order = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));
        //订单操作记录删除商品
        $operator_code = $order['Order']['operator_code'].';3';
        $this->Order->updateAll(array('Order.operator_code' => "'".$operator_code."'", 'Order.modified' => "'".date('Y-m-d H:i:s')."'"), array('Order.id' => $order_id));
        $operation_notes = '删除订单商品货号'.$order_info['OrderProduct']['product_code'];
        $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $order['Order']['user_id'], $order['Order']['status'], $order['Order']['payment_status'], $order['Order']['shipping_status'], $operation_notes);
        //如果是付款状态是减库存 加冻结库存
        if ($order['Order']['payment_status'] == 2) {
            $product_info['Product']['quantity'] = $product_info['Product']['quantity'] + $order_info['OrderProduct']['product_quntity'];
            $product_info['Product']['frozen_quantity'] = $product_info['Product']['frozen_quantity'] - $order_info['OrderProduct']['product_quntity'];
            $this->Product->save(array('Product' => $product_info['Product']));
        }
        $result['insure_fee'] = $order['Order']['insure_fee'];
        if(isset($order['Order']['discount'])){
            	$result['discount'] = sprintf('%01.2f', $order['Order']['discount']);
        }
        $result['code'] = 1;
        if ($product_count == 0) {
            $result['code'] = 2;
        }
        if ($product_count == 0) {
            $result['hasproduct'] = false;
        } else {
            $result['hasproduct'] = true;
        }
        $msg = $this->ld['deleted_success'];
        $result['message'] = $msg;
        echo json_encode($result);
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        die;
    }

    /**
     *订单应付金额.
     *
     *@param int $order_id 订单ID
     */
    public function need_pay($order_id)
    {
        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));
        $need_pay = $order_info['Order']['total'] - $order_info['Order']['point_fee'] - $order_info['Order']['money_paid'] - $order_info['Order']['coupon_fee'] - $order_info['Order']['discount'];
        return $need_pay;
    }

    /**
     *更新订单商品.
     *
     *@param int $order_id 订单ID
     */
    public function update_order_product($order_id){
        $this->OrderProduct->hasOne = array();
        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));//订单信息
        $order_product_array = $this->OrderProduct->find('all', array('conditions' => array('order_id' => $order_id)));
        $subtotal = 0;
        $purchase_price_total=0;
        $insure_fee=0;
        $product_code = array();
        foreach ($order_product_array as $k => $v) {
            $product_code[]=$v['OrderProduct']['product_code'];
            if($v['OrderProduct']['parent_product_id']==0){
                $subtotal += ($v['OrderProduct']['product_price'] * $v['OrderProduct']['product_quntity'] + $v['OrderProduct']['adjust_fee']);
                $purchase_price_total+= $v['OrderProduct']['purchase_price'] * $v['OrderProduct']['product_quntity'];
            }
        }
        if($order_info['Order']['lease_type']=="L"){
            $insure_fee_rule=$this->ProductLeasePrice->find('first',array("conditions"=>array("ProductLeasePrice.price >="=>0,"ProductLeasePrice.price <="=>$purchase_price_total),"order"=>"ProductLeasePrice.price desc"));
            if($insure_fee_rule['ProductLeasePrice']){
                if($purchase_price_total>$insure_fee_rule['ProductLeasePrice']['price']){
                    $insure_fee=$insure_fee_rule['ProductLeasePrice']['lease_deposit_base']+($purchase_price_total-$insure_fee_rule['ProductLeasePrice']['price'])*($insure_fee_rule['ProductLeasePrice']['lease_deposit_increase_percent']/100);
                }else{
                    $insure_fee=$insure_fee_rule['ProductLeasePrice']['lease_deposit_base'];
                }
            }
        }
        $order_info['Order']['insure_fee'] = $insure_fee;
        $total = $this->order_total_amounts($subtotal, $order_info['Order']['tax'], $order_info['Order']['shipping_fee'], $order_info['Order']['insure_fee'], $order_info['Order']['payment_fee'], $order_info['Order']['pack_fee'], $order_info['Order']['card_fee'], 0);
        $order_info['Order']['subtotal'] = $subtotal;
        $order_info['Order']['total'] = $total;
        $order_info['Order']['discount'] = $this->static_order_promotion($order_id,$subtotal);
        $this->Order->save($order_info);
        $operation_notes = '更新订单商品'.implode(" ",$product_code);
        $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $order_info['Order']['user_id'], $order_info['Order']['status'], $order_info['Order']['payment_status'], $order_info['Order']['shipping_status'], $operation_notes);
        //如果是付款状态是减库存 加冻结库存
        return $total;
    }
    
    function update_order_product_detail($order_id=0){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result=array();
		$result['code'] = 0;
		if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
			$result['message'] = $this->ld['have_no_operation_perform'];
		}else{
			$order_product_id=isset($_REQUEST['order_product_id'])?$_REQUEST['order_product_id']:0;
			$update_field=isset($_REQUEST['update_field'])?$_REQUEST['update_field']:'';
			$update_field_value=isset($_REQUEST['update_field_value'])?$_REQUEST['update_field_value']:'';
			$order_product_detail = $this->OrderProduct->find('first', array('conditions' => array('order_id' => $order_id,'id'=>$order_product_id)));
			if(!empty($order_product_detail)){
				if(isset($order_product_detail['OrderProduct'][$update_field])){
					$this->OrderProduct->save(array('id'=>$order_product_id,"{$update_field}"=>$update_field_value));
					$result['code'] = 1;
					$result['message'] = $this->ld['update_successful'];
				}else{
					$result['message'] = $this->ld['unknown_error'];
				}
			}else{
				$result['message'] = $this->ld['validation_error_message'];
			}
		}
		die(json_encode($result));
    }
    
function change_note($order_id=0){
    		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$result=array();
		$result['code'] = 0;
		if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
			$result['message'] = $this->ld['have_no_operation_perform'];
		}else{
			$order_info=$this->Order->find('first',array('conditions'=>array('Order.id'=>$order_id)));
			if(!empty($order_info)){
				$order_note=isset($_POST['note'])?$_POST['note']:'';
				$this->Order->save(array('id'=>$order_id,'note'=>$order_note));
				$result['code'] = 1;
				$result['message'] = $this->ld['update_successful'];
			}else{
				$result['message'] = '订单不存在';
			}
		}
		die(json_encode($result));
    }
    
    function static_order_promotion($order_id,$order_subtotal){
    		$order_promotion=0;
    		$order_info=$this->Order->find('first',array('conditions'=>array('Order.id'=>$order_id),'recursive'=>'-1'));
    		if(!empty($order_info)){
    			$this->loadModel('Promotion');
    			$this->loadModel('PromotionActivityProduct');
    			$promotion_condition=array();
    			$promotion_condition['Promotion.status']='1';
    			$promotion_condition['Promotion.type']=array('0','1');
    			$promotion_condition['Promotion.start_time <=']=$order_info['Order']['created'];
    			$promotion_condition['Promotion.end_time >=']=$order_info['Order']['created'];
    			$promotion_condition['or'][0]['Promotion.min_amount <=']=$order_subtotal;
    			$promotion_condition['or'][0]['Promotion.max_amount >=']=$order_subtotal;
    			$promotion_condition['or'][1]['Promotion.min_amount <=']=$order_subtotal;
    			$promotion_condition['or'][1]['Promotion.max_amount']=0;
    			$promotion_list=$this->Promotion->find('all',array('conditions'=>$promotion_condition));
    			if(!empty($promotion_list)){
    				foreach($promotion_list as $v){
    					$related_product_ids = $this->PromotionActivityProduct->find('list', array('conditions' => array('PromotionActivityProduct.promotion_id' => $v['Promotion']['id'], 'PromotionActivityProduct.status' => 1), 'fields' => array('PromotionActivityProduct.product_id')));
    					if(empty($related_product_ids)){
    						if($v['Promotion']['type']==0){
    							$order_promotion+=floatval($v['Promotion']['type_ext']);
    						}else{
    							$order_promotion+=round(floatval($v['Promotion']['type_ext'])/100*$order_subtotal,2);
    						}
    					}
    				}
    			}
    		}
    		return $order_promotion;
    }

    /**
     *订单金额计算.
     *
     *@param float $subtotal 商品总金额
     *@param float $tax 发票税
     *@param float $shipping_fee 配送费
     *@param float $insure_fee 保价费
     *@param float $payment_fee 支付费
     *@param float $pack_fee 包装费
     *@param float $card_fee 贺卡费
     */
    public function order_total_amounts($subtotal, $tax, $shipping_fee, $insure_fee, $payment_fee, $pack_fee, $card_fee)
    {
        return $subtotal + $tax + $shipping_fee + $insure_fee + $payment_fee + $pack_fee + $card_fee;
    }

    /**
     *返回某个订单可执行的操作列表，包括权限判断.
     *
     *@param array $order 订单的所有信息
     */
    public function operable_list($order)
    {
        if($order['Order']['lease_type']=='L'){
            $this->operator_privilege('lease_orders_edit');
        }else{
            $this->operator_privilege('orders_edit');
        }
        //取得订单状态、发货状态、付款状态
        if (isset($order['Order']['status']) && $order['Order']['status'] != '') {
            $os = $order['Order']['status'];
        } else {
            $os = 0;
        }
        if (isset($order['Order']['shipping_status']) && $order['Order']['shipping_status'] != '') {
            $ss = $order['Order']['shipping_status'];
        } else {
            $ss = 0;
        }
        if (isset($order['Order']['payment_status']) && $order['Order']['payment_status'] != '') {
            $ps = $order['Order']['payment_status'];
        } else {
            $ps = 0;
        }
        if (isset($order['Order']['check_status']) && $order['Order']['check_status'] != '') {
        	$cs = $order['Order']['check_status'];
        } else {
        	$cs = 0;
        }
        //取得订单支付方式是否货到付款
        $payment = $this->Payment->find('first', array('conditions' => array('Payment.id' => $order['Order']['payment_id'], 'Payment.status' => 1)));
        $is_cod = $payment['Payment']['code'] == 'cod';
        /*
        		根据状态返回可执行操作
	        	订单状态:0,未确认;1,已确认;2,已取消;3,无效;4,退货;5,已合并;6,重发货;7:换货;8,疑问
	        	支付状态:0,未付款;1,付款中;2,已付款3,申请退款;4,已退款
	        	发货状态:0,未发货;1,已发货;2,已收货;3,备货中;4,申请退货;5:已退货
	        	审核状态:0:未审核,1:已审核
        */
        $list = array();
        $product_check=true;
        if($order['Order']['service_type']=='appointment'){
	        if(isset($order['OrderProduct'])&&sizeof($order['OrderProduct'])>0){
	        	$child_order_products=array();
	        	foreach($order['OrderProduct'] as $vv){
	        		if($vv['parent_product_id']==0)continue;
	        		if(trim($vv['product_number'])!=''){
	        			$child_order_products[$vv['parent_product_id']][]=$vv;
	        		}
	        	}
	        	foreach($order['OrderProduct'] as $vv){
	        		if($vv['delivery_status']==5)continue;
	        		if($vv['parent_product_id']==0&&$vv['product_quntity']==1&&trim($vv['product_number'])==''){
	        			$product_check=false;
	        		}else if($vv['parent_product_id']==0&&$vv['product_quntity']>1){
	        			if(!isset($child_order_products[$vv['id']])||sizeof($child_order_products[$vv['id']])!=$vv['product_quntity']){
	        				$product_check=false;
	        			}
	        		}else if($vv['parent_product_id']>0&&$vv['product_number']==''){
	        			$product_check=false;
	        		}
	        	}
	        }else{
	        	$product_check=false;
	        }
        }else{
        	if(!isset($order['OrderProduct'])||sizeof($order['OrderProduct'])<=0){
        		$product_check=false;
        	}
        }
        
        /*
        if ($os == 0) {
            //状态：未确认 => 未付款、未发货
            $list['confirm'] = true; // 确认
            $list['invalid'] = true; // 无效
            $list['cancel'] = true; // 取消
            if ($is_cod) {
                //货到付款
                //$list['prepare'] = true; // 配货
                //$list['ship'] = true; // 发货
            } else {
                //不是货到付款
                $list['pay'] = true;  // 付款
            }
        } elseif ($os == 1) {
            //状态：已确认
            if ($ps == 0) {
                //状态：已确认、未付款
                if ($ss == 0 || $ss == 6 || $ss == 3) {
                    //状态：已确认、未付款、未发货（或配货中）
                    $list['cancel'] = true; // 取消
                    $list['invalid'] = true; // 无效
                    if($ss == 6){
                    	  $list['pickup'] = true; // 取货
                    }
                    if ($is_cod) {
                        //货到付款
                        if ($ss == 0) {
                            $list['prepare'] = true; // 配货
                        }
                        $list['ship'] = true; // 发货
                        //$list['pay'] = true; // 付款先放着
                    } else {
                        //不是货到付款
                        $list['pay'] = true; // 付款
                    }
                } else {
                    //状态：已确认、未付款、已发货或已收货 => 货到付款
                    if (!$is_cod) {
                        $list['pay'] = true; // 付款
                    }
                    if ($ss == 1) {
                        $list['receive'] = true; // 收货确认
                    }
                    $list['unship'] = true; // 设为未发货
                    $list['return'] = true; // 退货
                }
            } else {
                //状态：已确认、已付款和付款中
                if ($ss == 0 || $ss == 3) {
                    //状态：已确认、已付款和付款中、未发货（配货中） => 不是货到付款
                    if ($ss == 0) {
                        $list['prepare'] = true; // 配货
                    }
                    $list['ship'] = true; // 发货
                    $list['unpay'] = true; // 设为未付款
                    if ($os) {
                        $list['cancel'] = true; // 取消
                    }
                } else {
                    //状态：已确认、已付款和付款中、已发货或已收货
                    if ($ss == 1) {
                        $list['receive'] = true; // 收货确认
                    }
                    if ($is_cod) {
                        $list['unship'] = true; // 设为未发货
                    }
                    if (!$is_cod) {
                        $list['unpay'] = true; // 设为未付款
                    }
                    if ($ss  == 5) {
                        $list['return'] = false;
                    } else {
                        $list['return'] = true;// 退货（包括退款）
                    }
                }
            }
        } elseif ($os == 2) {
            //状态：取消
            $list['confirm'] = true;
            $list['remove'] = true;
        } elseif ($os == 3) {
            //状态：无效
            $list['confirm'] = true;
            $list['remove'] = true;
        } elseif ($os == 4) {
            //状态：退货
            $list['confirm'] = true;
        }
        */
        if($os==0||$os==9){
            $list['confirm'] = true; // 确认
            //$list['invalid'] = true; // 无效
            $list['cancel'] = true; // 取消
        }else if($os==1){
            if (!$is_cod&&$ps == 0) {
                if($order['Order']['to_type_id']==$this->admin['type_id']&&($ss==0||$ss==6||$ss==3)){
                    	$list['pay'] = true;  // 付款
                }
                //$list['invalid'] = true; // 无效
                $list['cancel'] = true; // 取消
            }else if($ps == 0&&$order['Order']['service_type']=='appointment'){
			if($order['Order']['to_type_id']==$this->admin['type_id']&&($ss==0||$ss==6||$ss==3)){
				$list['pay'] = true;  // 付款
			}
			$list['cancel'] = true; // 取消
            }else if(!$is_cod&&$ps == 2&&($ss==0||$ss==3||$ss==6)){
                if($this->operator_privilege("orders_no_payments",false)&&$order['Order']['to_type_id']==$this->admin['type_id']){
                    $list['unpay'] = true; // 设为未付款
                }
                $list['cancel'] = true; // 取消
            }
            if($this->operator_privilege("order_check",false)){//订单审核权限
	            if($cs==1&&($ss==0||$ss==3||$ss==6)){
	            		$list['cancel_check'] = true; // 取消审核
	            }else if($cs!=1&&($ps == 2||$is_cod)&&($ss==0||$ss==3)&&$product_check){
	            		$list['check'] = true; // 审核
	            }
	            $list['cancel'] = true; // 取消
            }
            if(($ss==0&&$ps == 2&&$cs=1)||($ss==0&&$is_cod&&$cs=1)){
                $list['prepare'] = true; // 配货
                if($this->operator_privilege("order_shippings_view",false)&&$order['Order']['to_type_id']==$this->admin['type_id']){
                    $list['ship'] = true; // 发货
                }
                //$list['invalid'] = true; // 无效
                $list['cancel'] = true; // 取消
            }else if(($ss==3&&$ps == 2&&$cs=1)||($ss==0&&$is_cod&&$cs=1)){
                if($this->operator_privilege("order_shippings_view",false)&&$order['Order']['to_type_id']==$this->admin['type_id']){
                    $list['ship'] = true; // 发货
                }
                $list['cancel'] = true; // 取消
            }
            if($ss==1){
                if($this->operator_privilege("orders_unfilled",false)){
                    $list['unship'] = true; // 设为未发货
                }
                $list['receive'] = true; // 收货确认
            }else if($ss==6&&$product_check){
                $list['pickup'] = true; // 取货
                $list['cancel'] = true; // 取消
            }
        }else if($os==2){
            $list['confirm'] = true; // 确认
        }
        if($ss==1||$ss==2){
            $list['after_service'] = true;//售后
        }
        return $list;
    }

    public function add(){
        $this->operator_privilege('orders_add');
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['orders_search'],'url' => '/orders/');
        $this->navigations[] = array('name' => $this->ld['add_order'],'url' => '/orders/add');
        //判断是否会有配送方式
        $shipping_effective_list = $this->Shipping->shipping_effective_list_beta($this->locale);
        if (empty($shipping_effective_list)) {
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("配送方式不存在!");window.location.href="/admin/orders/"</script>';
            die();
        }
        if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
            $info = $this->User->find('first', array('conditions' => array('User.id' => $_GET['user_id'])));
            if (!empty($info)) {
                $this->data['Order']['user_id'] = $_GET['user_id'];
                $this->data['Order']['mobile'] = $info['User']['mobile'];
                $this->data['Order']['consignee'] = !empty($info['User']['first_name']) ? $info['User']['first_name'] : '';
                if (!empty($info['User']['address_id'])) {
                    $addressInfo = $this->UserAddress->find('first', array('conditions' => array('UserAddress.id' => $info['User']['address_id'])));
                    if (!empty($addressInfo)) {
                        $this->Region->set_locale($this->backend_locale);
                        $regions_info = $this->Region->find('all', array('fields' => 'Region.id,RegionI18n.name'));
                        foreach ($regions_info as $k => $v) {
                            if ($v['Region']['id'] == $addressInfo['UserAddress']['country']) {
                                $addressInfo['UserAddress']['country'] = $v['RegionI18n']['name'];
                            }
                            if ($v['Region']['id'] == $addressInfo['UserAddress']['province']) {
                                $addressInfo['UserAddress']['province'] = $v['RegionI18n']['name'];
                            }
                            if ($v['Region']['id'] == $addressInfo['UserAddress']['city']) {
                                $addressInfo['UserAddress']['city'] = $v['RegionI18n']['name'];
                            }
                        }
                        $this->data['Order']['country'] = $addressInfo['UserAddress']['country'];
                        $this->data['Order']['province'] = $addressInfo['UserAddress']['province'];
                        $this->data['Order']['city'] = $addressInfo['UserAddress']['city'];
                        $this->data['Order']['address'] = $addressInfo['UserAddress']['address'];
                        $this->data['Order']['mobile'] = $addressInfo['UserAddress']['mobile'];
                        $this->data['Order']['zipcode'] = $addressInfo['UserAddress']['zipcode'];
                        $this->data['Order']['email'] = $addressInfo['UserAddress']['email'];
                        $this->data['Order']['consignee'] = $addressInfo['UserAddress']['consignee'];
                        $this->data['Order']['sign_building'] = $addressInfo['UserAddress']['sign_building'];
                        $this->data['Order']['telephone'] = $addressInfo['UserAddress']['telephone'];
                        $this->data['Order']['best_time'] = $addressInfo['UserAddress']['best_time'];
                    }
                }
            }
        }
        //订单管理员
        if (isset($_REQUEST['order_manager']) && intval($_REQUEST['order_manager'])>0) {
            $this->data['Order']['order_manager'] = intval($_REQUEST['order_manager']);
            $this->data['Order']['status'] = 0;
        }else if(isset($_REQUEST['order_service_type']) && trim($_REQUEST['order_service_type'])== 'appointment'){
            $this->data['Order']['status'] = 9;
        }
        //订单服务类型
        if (isset($_REQUEST['order_service_type']) && trim($_REQUEST['order_service_type']) != '') {
            $this->data['Order']['service_type'] = trim($_REQUEST['order_service_type']);
            if($this->data['Order']['service_type'] =='appointment'){
                $this->data['Order']['shipping_status'] = 6;
            }
        }
        $this->data['Order']['order_code'] = $this->get_order_code();
        $this->data['Order']['operator_id'] = $this->admin['id'];
        $this->data['Order']['subtotal'] = 0;
        $this->data['Order']['order_locale'] = $this->backend_locale;
        //判断是否有现金支付 如果有默认
        $payInfo = $this->Payment->find('first', array('conditions' => array('Payment.code' => 'money')));
        if (!empty($payInfo)) {
            $this->data['Order']['payment_id'] = $payInfo['Payment']['id'];
        }
        //判断来源 有门店默认第一个门店 没有就默认网站  2012/04/10 新增订单没有订单来源
        /*		if(in_array('APP-SHOPS',$this->apps['codes'])){
                    $this->Store->set_locale($this->backend_locale);
                    $stores = $this->Store->find('all',array('conditions'=>array("status"=>1),'fields'=>array('store_sn','StoreI18n.name','operator_id'),'order'=>'orderby'));
                    $stores = $this->Store->store_operator($stores);
                    if(!empty($stores)){
                        foreach ($stores as $k => $v) {
                            $this->data['Order']['type']=$v['Store']['store_sn'];
                            $this->data['Order']['type_id']="store";
                            break;
                        }
                    }
                }
                if(empty($this->data['Order']['type'])||empty($this->data['Order']['type_id'])){
                    $this->data['Order']['type']='网站';
                    $this->data['Order']['type_id']="ioco";
                }
        */
        if (isset($this->admin['type']) && $this->admin['type'] == 'D') {
            $this->data['Order']['type'] = 'dealer';
            $this->data['Order']['type_id'] = $this->admin['type_id'];
        } else {
            $this->data['Order']['type'] = 'website';
            $this->data['Order']['type_id'] = 'backend';
        }
        $this->data['Order']['to_type'] = 'S';
        $this->data['Order']['to_type_id'] = '0';
        $this->Order->saveAll(array('Order' => $this->data['Order']));
        $a = $this->Order->find('all', array('limit' => 1, 'order' => 'Order.id desc'));
        //OrderAction
        $OrderAction['order_id'] = $a[0]['Order']['id'];
        $OrderAction['from_operator_id'] = $this->admin['id'];
        $OrderAction['action_note'] = $this->ld['add_order'];
        $this->OrderAction->saveAll($OrderAction);
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_order'].':id '.$a[0]['Order']['id'], $this->admin['id']);
        }
        $_SESSION['add_order'] = true;
        $this->redirect('/orders/edit/'.$a[0]['Order']['id']);
    }

    public function lease_add(){
        $this->operator_privilege('lease_orders_add');
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['orders_search'],'url' => '/orders/');
        $this->navigations[] = array('name' => $this->ld['add_order'],'url' => '/orders/add');
        //判断是否会有配送方式
        $shipping_effective_list = $this->Shipping->shipping_effective_list_beta($this->locale);
        if (empty($shipping_effective_list)) {
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("配送方式不存在!");window.location.href="/admin/orders/"</script>';
            die();
        }
        if (isset($_GET['user_id']) && $_GET['user_id'] != '') {
            $info = $this->User->find('first', array('conditions' => array('User.id' => $_GET['user_id'])));
            if (!empty($info)) {
                $this->data['Order']['user_id'] = $_GET['user_id'];
                $this->data['Order']['mobile'] = $info['User']['mobile'];
                $this->data['Order']['consignee'] = !empty($info['User']['first_name']) ? $info['User']['first_name'] : '';
                if (!empty($info['User']['address_id'])) {
                    $addressInfo = $this->UserAddress->find('first', array('conditions' => array('UserAddress.id' => $info['User']['address_id'])));
                    if (!empty($addressInfo)) {
                        $this->Region->set_locale($this->backend_locale);
                        $regions_info = $this->Region->find('all', array('fields' => 'Region.id,RegionI18n.name'));
                        foreach ($regions_info as $k => $v) {
                            if ($v['Region']['id'] == $addressInfo['UserAddress']['country']) {
                                $addressInfo['UserAddress']['country'] = $v['RegionI18n']['name'];
                            }
                            if ($v['Region']['id'] == $addressInfo['UserAddress']['province']) {
                                $addressInfo['UserAddress']['province'] = $v['RegionI18n']['name'];
                            }
                            if ($v['Region']['id'] == $addressInfo['UserAddress']['city']) {
                                $addressInfo['UserAddress']['city'] = $v['RegionI18n']['name'];
                            }
                        }
                        $this->data['Order']['country'] = $addressInfo['UserAddress']['country'];
                        $this->data['Order']['province'] = $addressInfo['UserAddress']['province'];
                        $this->data['Order']['city'] = $addressInfo['UserAddress']['city'];
                        $this->data['Order']['address'] = $addressInfo['UserAddress']['address'];
                        $this->data['Order']['mobile'] = $addressInfo['UserAddress']['mobile'];
                        $this->data['Order']['zipcode'] = $addressInfo['UserAddress']['zipcode'];
                        $this->data['Order']['email'] = $addressInfo['UserAddress']['email'];
                        $this->data['Order']['consignee'] = $addressInfo['UserAddress']['consignee'];
                        $this->data['Order']['sign_building'] = $addressInfo['UserAddress']['sign_building'];
                        $this->data['Order']['telephone'] = $addressInfo['UserAddress']['telephone'];
                        $this->data['Order']['best_time'] = $addressInfo['UserAddress']['best_time'];
                    }
                }
            }
        }
        $this->data['Order']['order_code'] = $this->get_order_code();
        $this->data['Order']['operator_id'] = $this->admin['id'];
        $this->data['Order']['subtotal'] = 0;
        $this->data['Order']['order_locale'] = $this->backend_locale;
        //判断是否有现金支付 如果有默认
        $payInfo = $this->Payment->find('first', array('conditions' => array('Payment.code' => 'money')));
        if (!empty($payInfo)) {
            $this->data['Order']['payment_id'] = $payInfo['Payment']['id'];
        }
        //判断来源 有门店默认第一个门店 没有就默认网站  2012/04/10 新增订单没有订单来源
        /*		if(in_array('APP-SHOPS',$this->apps['codes'])){
                    $this->Store->set_locale($this->backend_locale);
                    $stores = $this->Store->find('all',array('conditions'=>array("status"=>1),'fields'=>array('store_sn','StoreI18n.name','operator_id'),'order'=>'orderby'));
                    $stores = $this->Store->store_operator($stores);
                    if(!empty($stores)){
                        foreach ($stores as $k => $v) {
                            $this->data['Order']['type']=$v['Store']['store_sn'];
                            $this->data['Order']['type_id']="store";
                            break;
                        }
                    }
                }
                if(empty($this->data['Order']['type'])||empty($this->data['Order']['type_id'])){
                    $this->data['Order']['type']='网站';
                    $this->data['Order']['type_id']="ioco";
                }
        */
        if (isset($this->admin['type']) && $this->admin['type'] == 'D') {
            $this->data['Order']['type'] = 'dealer';
            $this->data['Order']['type_id'] = $this->admin['type_id'];
        } else {
            $this->data['Order']['type'] = 'website';
            $this->data['Order']['type_id'] = 'backend';
        }
        $this->data['Order']['to_type'] = 'S';
        $this->data['Order']['to_type_id'] = '0';
        $this->data['Order']['lease_type'] = 'L';
        $this->Order->saveAll(array('Order' => $this->data['Order']));
        $a = $this->Order->find('all', array('limit' => 1, 'order' => 'Order.id desc'));
        //OrderAction
        $OrderAction['order_id'] = $a[0]['Order']['id'];
        $OrderAction['from_operator_id'] = $this->admin['id'];
        $OrderAction['action_note'] = $this->ld['add_order'];
        $this->OrderAction->saveAll($OrderAction);
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['add_order'].':id '.$a[0]['Order']['id'], $this->admin['id']);
        }
        $_SESSION['add_order'] = true;
        $this->redirect('/orders/lease_edit/'.$a[0]['Order']['id']);
    }

    //获取网址站点信息，将信息传给模板处理
    public function AbsoluteUrl()
    {
        global $HTTP_SERVER_VARS;
        $HTTPS = @$HTTP_SERVER_VARS['HTTPS'];
        $HTTP_HOST = @$HTTP_SERVER_VARS['HTTP_HOST'];
        $SCRIPT_URL = @$HTTP_SERVER_VARS['SCRIPT_URL'];
        $PATH_INFO = @$HTTP_SERVER_VARS['PATH_INFO'];
        $REQUEST_URI = @$HTTP_SERVER_VARS['REQUEST_URI'];
        $SCRIPT_NAME = @$HTTP_SERVER_VARS['SCRIPT_NAME'];
        $QUERY_STRING = $HTTP_SERVER_VARS['QUERY_STRING'];
        if (get_magic_quotes_gpc() == 1) {
            $QUERY_STRING = stripslashes($QUERY_STRING);
        }
        if ($QUERY_STRING != '') {
            $QUERY_STRING = '?'.$QUERY_STRING;
        }
        $uri_http = (((strtolower($HTTPS) == 'off') or ($HTTPS == 0)) ? 'http' : 'https').'://'.$HTTP_HOST;
        $url = '';
        if (isset($SCRIPT_URL)) {
            $url = $SCRIPT_URL;
        } elseif (isset($PATH_INFO)) {
            $url = $PATH_INFO;
        } elseif (isset($REQUEST_URI)) {
            $url = $REQUEST_URI;
        } elseif (isset($SCRIPT_NAME)) {
            $url = $SCRIPT_NAME;
        }
        $url = $uri_http.$url;
        return $url;
    }

    //获得订单号
    public function get_order_code()
    {
        $order_code="";
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
        $order_code=$sn.$c;
        while(true){
            $order_count=$this->Order->find('count',array('conditions'=>array("Order.order_code"=>$order_code),'recursive' => -1));
            if($order_count>0){
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
                $order_code=$sn.$c;
            }else{
                break;
            }
        }
        return $order_code;
    }

    //获取搜索商品
    public function search_order_product()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $this->Product->set_locale($this->backend_locale);
        $product_keyword = trim($_REQUEST['keywords']);
        if (isset($_REQUEST['brand']) && $_REQUEST['brand'] != '') {
            $condition['and']['Product.brand_id'] = $_REQUEST['brand'];
        }
        if (isset($_REQUEST['product_type']) && $_REQUEST['product_type'] != '') {
            $condition['and']['Product.product_type_id'] = $_REQUEST['product_type'];
        }
        $condition['and']['Product.status'] = '1';
        $condition['and']['Product.forsale'] = '1';
        if($_REQUEST['lease_type']=="L"){
            $condition['and']['Product.lease_status'] = '1';
        }
        if ($product_keyword != '') {
            $keyword = preg_split('#\s+#', $product_keyword);
            foreach ($keyword as $k => $v) {
                $conditions_p18n['AND']['or'][0]['and'][]['ProductI18n.name like'] = "%$v%";
                $conditions_p18n['AND']['or'][1]['and'][]['ProductI18n.meta_keywords  like'] = "%$v%";
                $conditions_p18n['AND']['ProductI18n.locale'] = $this->languages_assoc;
            }
            $product18n_pid = $this->ProductI18n->find_product18n_pid($conditions_p18n); //model
            $condition['AND']['OR']['Product.id'] = $product18n_pid;
            $condition['AND']['OR']['Product.code like'] = "%$v%";
        }
        $fields[] = 'Product.id';
        $fields[] = 'Product.code';
        $fields[] = 'Product.img_thumb';
        $fields[] = 'Product.shop_price';
        $fields[] = 'Product.quantity';
        $fields[] = 'ProductI18n.name';
        $limit = isset($configs['order_product_search_limit']) && $configs['order_product_search_limit'] != '' ? $configs['order_product_search_limit'] : 100;
        $pro_list = $this->Product->find('all', array('conditions' => $condition, 'order' => 'Product.code,Product.id desc', 'fields' => $fields, 'limit' => $limit));
        //标记销售属性商品
        $this->loadModel('SkuProduct');
        $sku_product_list = $this->SkuProduct->find('list', array('fields' => array('SkuProduct.id', 'SkuProduct.product_code'), 'group' => 'product_code'));
        if(!empty($pro_list)){
            foreach ($pro_list as $k => $v) {
                if($_REQUEST['lease_type']=="L"){
                    $lease_info= $this->ProductLease->find('first', array('conditions' => array('ProductLease.product_code' => $v['Product']['code'])));
                    $pro_list[$k]['Product']['shop_price']=$lease_info["ProductLease"]["lease_price"];
                }
                if (!empty($sku_product_list)){
                    if (in_array($v['Product']['code'], $sku_product_list)) {
                        $pro_list[$k]['Product']['is_sku'] = 1;
                    } else {
                        $pro_list[$k]['Product']['is_sku'] = 0;
                    }
                }
            }
        }
        Configure::write('debug', 0);
        $result['type'] = '0';
        $result['message'] = $pro_list;
        echo json_encode($result);
        die();
    }

    //获取搜索经销商
    public function search_dealer()
    {
        $this->loadModel('Dealer');
        $dealer_keyword = trim($_REQUEST['keywords']);
        $condition['and']['Dealer.status'] = '1';
        if ($dealer_keyword != '') {
            $condition['AND']['OR']['Dealer.name like'] = "%$dealer_keyword%";
            $condition['AND']['OR']['Dealer.code like'] = "%$dealer_keyword%";
        }
        $fields[] = 'Dealer.id';
        $fields[] = 'Dealer.code';
        $fields[] = 'Dealer.name';
        $pro_list = $this->Dealer->find('all', array('conditions' => $condition, 'fields' => $fields));
        //	$this->set("pro_list",$pro_list);
        //	$result["code"] = 1;
        Configure::write('debug', 0);
        $result['type'] = '0';
        $result['message'] = $pro_list;
        echo json_encode($result);
        die();
    }

    public function is_aim($oid)
    {
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $pid = $this->Order->find('first', array('conditions' => array('Order.id' => $oid), 'fields' => array('Order.payment_id')));
        $foo = $this->Payment->find('first', array('conditions' => array('Payment.id' => $pid['Order']['payment_id']), 'fields' => array('Payment.code')));
        if ($foo['Payment']['code'] == 'AuthorizeNet_AIM') {
            return true;
        } else {
            return false;
        }
    }

    /**
     *列表批量操作.
     *
     *@param string $type 类型
     */
    public function batch_operations($type)
    {

        $order_checkboxes = $_REQUEST['checkboxes'];
        if ($type == 'delete') {
            $order_status = $_REQUEST['order_status'];
            $this->batch_delete($order_checkboxes, $order_status);
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete'], $this->admin['id']);
            }
        }
        if ($type == 'export_flag') {
            $this->export_flag($order_checkboxes);
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['set_modified'], $this->admin['id']);
            }
        }
        if ($type == 'order_batch_check') {
            $this->batch_check($order_checkboxes);
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['submit_review'], $this->admin['id']);
            }
        }
        if ($type == 'order_batch_check_remove') {
            $this->batch_check_remove($order_checkboxes);
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['submit_review'], $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        exit();
    }
    // 批量审核
    public function batch_check($order_checkboxes = '')
    {
        foreach ($order_checkboxes as $k => $v) {
            $order_info = array(
                'check_status' => '1',
                'id' => $v,
            );
            $this->Order->save($order_info);
            $order = $this->Order->find('first', array('conditions' => array('Order.id' => $v)));
            $this->OrderAction->update_order_action(array('order_id' => $v, 'from_operator_id' => $this->admin['id'], 'user_id' => $order['Order']['user_id'], 'order_status' => $order['Order']['status'], 'payment_status' => $order['Order']['payment_status'], 'shipping_status' => $order['Order']['shipping_status'], 'action_note' => '批量设置为已同步'));
        }
        $this->redirect('/orders');
    }
    // 批量取消审核
    public function batch_check_remove($order_checkboxes = '')
    {
        foreach ($order_checkboxes as $k => $v) {
            $order_info = array(
                'check_status' => '0',
                'id' => $v,
            );
            $this->Order->save($order_info);
            $order = $this->Order->find('first', array('conditions' => array('Order.id' => $v)));
            $this->OrderAction->update_order_action(array('order_id' => $v, 'from_operator_id' => $this->admin['id'], 'user_id' => $order['Order']['user_id'], 'order_status' => $order['Order']['status'], 'payment_status' => $order['Order']['payment_status'], 'shipping_status' => $order['Order']['shipping_status'], 'action_note' => '批量设置为已同步'));
        }
        $this->redirect('/orders');
    }
    //订单的搜索导出;;
    public function search_result($condition = '', $actout_type = '', $code = '')
    {
        $this->loadModel('Profile');
        $this->loadModel('ProfileFiled');
        $this->loadModel('ProfilesFieldI18n');
        $this->Profile->hasOne = array();
        $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $code, 'Profile.status' => 1)));
        $newdata = array();
        if (isset($profile_id) && !empty($profile_id)) {
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1, 'ProfilesFieldI18n.locale' => $this->backend_locale), 'order' => 'ProfileFiled.orderby asc'));
            $tmp = array();
            $fields_array = array();
            foreach ($profilefiled_info as $k => $v) {
                $tmp[] = $v['ProfilesFieldI18n']['description'];
                $fields_array[] = $v['ProfileFiled']['code'];
            }
            $Resource_info = array();
            if (in_array('Order.status', $fields_array) || in_array('Order.payment_status', $fields_array) || in_array('Order.shipping_status', $fields_array)) {
                $Resource_info = $this->Resource->getformatcode(array('order_status', 'shipping_status', 'payment_status', 'picking_type'), $this->backend_locale);
            }
            if (in_array('Order.logistics_company_id', $fields_array)) {
                $logistics_company_infos = $this->LogisticsCompany->find('list', array('fields' => 'LogisticsCompany.id,LogisticsCompany.name'));
            }
            if (in_array('APP-DEALER', $this->apps['codes'])) {
                $this->loadModel('Dealer');
                $dealers_list = $this->Dealer->find('list', array('fields' => array('id', 'name'), 'order' => 'orderby'));
            }
            $this->Order->hasMany = array();
            $this->OrderProduct->hasOne = array();
            $this->Order->hasOne = array('OrderProduct' => array(
                'className' => 'OrderProduct',
                'order' => '',
                'dependent' => true,
                'foreignKey' => 'order_id',
            ));
            $orders_list = $this->Order->find('all', array('conditions' => $condition, 'fields' => $fields_array, 'order' => 'Order.order_code desc'));
            $newdata[] = $tmp;
            $order_code_array = array();
            foreach ($orders_list as $k => $v) {
                $order_products_flag = 0;
                $datas = array();
                if (!in_array($v['Order']['order_code'], $order_code_array)) {
                    $order_code_array[] = $v['Order']['order_code'];
                } else {
                    $order_products_flag = 1;
                }
                foreach ($fields_array as $kk => $vv) {
                    $fields_kk = explode('.', $vv);
                    if (isset($order_products_flag) && $order_products_flag == 1 && stristr($vv, 'Order.')) {
                        //在里面
                        $datas[] = '';
                    } else {
                        if ($vv == 'Order.type') {
                            if ($v['Order']['type'] == 'fenxiao') {
                                $datas[] = '分销';
                            } elseif ($v['Order']['type'] == 'taobao') {
                                $datas[] = '淘宝';
                            } elseif ($v['Order']['type'] == 'dealer') {
                                $datas[] = '经销商';
                            } else {
                                $datas[] = '本站';
                            }
                        } elseif ($vv == 'Order.type_id') {
                            if (isset($v['Order']['type']) && $v['Order']['type'] == 'dealer') {
                                $dealer_name = isset($dealers_list[$v['Order']['type_id']]) ? $dealers_list[$v['Order']['type_id']] : '';
                                $datas[] = $dealer_name;
                            } else {
                                $datas[] = $v['Order']['type_id'];
                            }
                        } elseif ($vv == 'Order.status') {
                            if (isset($v[$fields_kk[0]][$fields_kk[1]])) {
                                $datas[] = isset($Resource_info['order_status'][$v['Order']['status']]) ? $Resource_info['order_status'][$v['Order']['status']] : '';
                            } else {
                                $datas[] = '';
                            }
                        } elseif ($vv == 'Order.payment_status') {
                            if (isset($v[$fields_kk[0]][$fields_kk[1]])) {
                                $datas[] = isset($Resource_info['payment_status'][$v['Order']['payment_status']]) ? $Resource_info['payment_status'][$v['Order']['payment_status']] : '';
                            } else {
                                $datas[] = '';
                            }
                        } elseif ($vv == 'Order.shipping_status') {
                            if (isset($v[$fields_kk[0]][$fields_kk[1]])) {
                                $datas[] = isset($Resource_info['shipping_status'][$v['Order']['shipping_status']]) ? $Resource_info['shipping_status'][$v['Order']['shipping_status']] : '';
                            } else {
                                $datas[] = '';
                            }
                        }elseif($vv == 'Order.logistics_company_id'){
                            if (isset($v[$fields_kk[0]][$fields_kk[1]])) {
                                $datas[] = isset($logistics_company_infos[$v['Order']['logistics_company_id']]) ? $logistics_company_infos[$v['Order']['logistics_company_id']] : '';
                            } else {
                                $datas[] = '';
                            }
                        }else {
                            $datas[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
                        }
                    }
                }
                $newdata[] = $datas;
            }
        }
        $this->Phpexcel->output($actout_type.date('YmdHis').'.xls', $newdata);
        exit;
    }

    //判断是否已装auth应用，或是否启用
    public function app_auth_aim()
    {
        return true;
    }

    public function aim_status()
    {
        $payment = $this->Payment->find('first', array('conditions' => array('Payment.code' => 'AuthorizeNet_AIM'), 'fields' => array('Payment.config')));
        $payment_config = unserialize($payment['Payment']['config']);
        if ($payment_config['used'] == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function card_status($card)
    {
        if (empty($card)) {
            return false;
        } else {
            return true;
        }
    }

    public function aim_capture($order_id)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        /*#$order_id
        $order['transaction_id']=$order['Order']['note']
        $order['payerEmail'];$order['Order']['email']
        $order['payerPhone'];$order['Order']['telephone']
        $order['amount']$order['Order']['total']
        $tesc['Order']['user_card_id']->table:user_cards
        $order['expDate']$card['Order']['note']
        $order['CAVV']$card['Order']['note']
        $order['card_num']$card['Order']['note']
        */
        $payment = $this->Payment->find('first', array('conditions' => array('Payment.code' => 'AuthorizeNet_AIM'), 'fields' => array('Payment.config')));
        $payment_config = unserialize($payment['Payment']['config']);
        $tesc = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id), 'fields' => array('Order.credit_card', 'Order.email', 'Order.telephone', 'Order.total')));
        if (empty($tesc)) {
            $return_array = array();
            $return_array['return_code'] = 8;
            $return_array['reason_text'] = $this->ld['order_number_not_exist'];

            return $return_array;
        }
        $card = explode(';', $tesc['Order']['credit_card']);
        if (empty($card[0])) {
            $return_array = array();
            $return_array['return_code'] = 7;
            $return_array['reason_text'] = $this->ld['card_not_exist_table'];
            return $return_array;
        }
        if (empty($card[1]) || !isset($card[1])) {
            $return_array = array();
            $return_array['return_code'] = 18;
            $return_array['reason_text'] = $this->ld['card_verification_standardized'];
            return $return_array;
        }
        $arr = explode('/', $card[2]);
        $expDate = $arr[1].substr($arr[0], -2);
        $order = array();
        $order['transaction_id'] = $card[1];//transaction_id
        $order['payerEmail'] = $tesc['Order']['email'];
        $order['payerPhone'] = $tesc['Order']['telephone'];
        $order['amount'] = $tesc['Order']['total'];
        $order['expDate'] = $expDate;
        $order['CAVV'] = '';
        $order['card_num'] = $card[0];
        App::import('Vendor', 'payments/authorizenet_aim');
        $pay_acter = new authorizenet_aim();
        return $pay_acter->go_prior_auth_capture($order, $payment_config);
    }

    public function alipay_check_go($orfo)
    {
        if (empty($orfo['Order']['trade_no'])) {
            return false;
        } else {
            return true;
        }
    }

    public function alipay_go($orfo)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $payment = $this->Payment->find('first', array('conditions' => array('Payment.code' => 'alipay')));
        $payment_config = unserialize($payment['Payment']['config']);
        $partner = $payment_config['partner'];//合作商户号
        $security_code = $payment_config['key'];//安全检验码
        $sign_type = 'MD5';     //加密方式 系统默认(不要修改)
        $notify_url = $this->server_host.'/alipay_shipping/respond';//回调地址
        //物流公司名称
        $logistics_name = $this->LogisticsCompany->find('first', array('conditions' => array('LogisticsCompany.id' => $orfo['order_logistics_company_id']), 'fields' => array('LogisticsCompany.name')));
        $trade_no = $orfo['Order']['trade_no'];//支付宝交易号
        $invoice_no = $orfo['Order']['invoice_no'];//物流单号
        $transport_type = 'DIRECT';//发货类型，POST（平邮），EXPRESS（快递），EMS（EMS）
        $parameter = array(
            'service' => 'send_goods_confirm_by_platform',  //接口类型
            'partner' => $partner,           //合作商户号
            '_input_charset' => 'utf-8',    //字符集，默认为GBK
            'trade_no' => $trade_no, //支付宝交易号
            //"logistics_name"  => 'ZJS', //物流公司名称
            'invoice_no' => '', //物流单号
            'transport_type' => $transport_type, //发货类型，POST（平邮），EXPRESS（快递），EMS（EMS）
        );
        if ($orfo['order_logistics_company_id'] != 0) {
            $parameter['logistics_name'] = $logistics_name['LogisticsCompany']['name'];
        }
        App::import('Vendor', 'support/alipay_go');
        $alipay_go = new alipay_go();
        $alipay_go->init($parameter, $security_code, $sign_type);
        $link = $alipay_go->create_url($parameter);
        $buff = '';
        $fp = fopen($link, 'r') or die("can not open $link");
        while (!feof($fp)) {
            $buff .= fgets($fp, 4096);
        }
        //关闭文件打开
        fclose($fp);
        //建立一个 XML 解析器
        $parser = xml_parser_create();
        //xml_parser_set_option -- 为指定 XML 解析进行选项设置
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        //xml_parse_into_struct -- 将 XML 数据解析到数组$values中
        xml_parse_into_struct($parser, $buff, $values, $idx);
        //xml_parser_free -- 释放指定的 XML 解析器
        xml_parser_free($parser);
        $respons_arr = array();
        foreach ($values as $val) {
            $val['tag'] = strtolower($val['tag']);
            if ($val['type'] == 'complete') {
                if ($val['tag'] == 'param') {
                    $respons_arr['request_'.$val['attributes']['NAME']] = $val['value'];
                } else {
                    $respons_arr[$val['tag']] = $val['value'];
                }
            }
        }
        $result['type'] = '1';
        $result['msg'] = '';
        $result['order_id'] = $orfo['Order']['id'];
        if ($respons_arr['is_success'] != 'T' && (empty($respons_arr['trade_status']) || $respons_arr['trade_status'] != 'TRADE_FINISHED')) {
            $msg = '支付宝发货确认失败!';
            Configure::write('debug', 0);
            $result['type'] = '0';
            $result['msg'] = $msg;
            $result['order_id'] = $orfo['Order']['id'];
            return $result;
        }
        return    $result;
    }

    //打印订单
    public function batch_shipping_print($id = '')
    {
        @header('content-Type: text/html; charset=utf-8');
        if (!in_array('APP-ORDER-PRINT', $this->apps['codes'])) {
            $this->redirect('/');
        }
        $this->operator_privilege('orders_print');
        //$this->change_ld($order_info["Order"]['order_locale']);
//		$this->set("title_for_layout",$this->ld['view'].$this->ld['order']."-".$this->ld['orders_search']." - ".$this->configs['shop_name']);
//		$this->navigations[]=array('name'=>$this->ld['manager_orders'],'url'=>'');
//		$this->navigations[]=array('name'=>$this->ld['orders_search'],'url' => '/orders/');
//		$this->navigations[]=array('name'=>$this->ld['view'].$this->ld['order'],'url' => '');
//		$this->navigations[]=array('name'=>$order_info["Order"]["order_code"],'url' => '');
//		$this->requestAction('/orders/view/'.$id, array('return'));
        $this->layout = 'ajax';
        $order_info = $this->Order->findbyid($id);//订单信息
        //$this->change_ld($order_info["Order"]['order_locale']);
        //$this->redirect('/orders/view/'.$id);
        //$ex_data = file_get_contents(view($id));
        $csv_export_code = 'utf-8';
        $filename = $this->ld['order_print_tit'].date('Ymd').'.html';
        $ex_data = '<html><head><title>'.$this->ld['order_print_tit'].'#'.$order_info['Order']['order_code'].'</title><style type="text/css">';
        $ex_data .= file_get_contents($this->admin_webroot.'/themed/admin/css/layout_min.css');
        $ex_data .= file_get_contents($this->admin_webroot.'/themed/admin/css/print.css');
        $ex_data .= '</style></head><body>';
        if (isset($this->configs['shop-order-logo']) && !empty($this->configs['shop-order-logo'])) {
            $ex_data .= '<div style="width: 50px;margin: 0 auto 5px;"><img class="printlogo" src="'.$this->configs['shop-order-logo'].'" /></div>';
        }
        $ex_data .= $this->requestAction('/orders/view/'.$id, array('return'));
        $ex_data .= '<div class="preprint"><input type="button" value="'.$this->ld['print'].'" onclick="window.print();"/></div></body><style type="text/javascript">window.onload = function() { window.print();}//document.onreadystatechange = function() { window.print();}</style></html>';
        echo $ex_data;
    }

    //批量打印配送单
    public function batch_order_shipping_print($id = '')
    {
        $this->operator_privilege('orders_print');
        $this->Order->belongsTo = array();
        $this->set('title_for_layout', '订单信息');
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['orders_search'],'url' => '/orders/');
        $this->navigations[] = array('name' => $this->ld['view'].$this->ld['order'],'url' => '');
        $pro_ids = '';
        if (empty($id)) {
            if (isset($this->params['form']['act']) && $this->params['form']['act'] == 'batch') {
                $pro_ids = !empty($this->params['form']['checkboxes']) ? $this->params['form']['checkboxes'] : 0;
            } else {
                $order_code = !empty($this->params['form']['order_code']) ? $this->params['form']['order_code'] : 0;
                $order = $this->Order->findByOrder_code($order_code);
                $pro_ids[] = $order['Order']['id'];
            }
        } else {
            $pro_ids[] = $id;
        }
        if (!empty($pro_ids)) {
            $all_order_info = array();
            foreach ($pro_ids as $vid) {
                $order_info = array();
                //取得订单及订单商品信息
                $order_info = $this->Order->findById($vid);
                if ($order_info['Order']['express_status'] != 1) {
                    $order_info['Order']['express_status'] = 1;
                    $order_info['Order']['express_date'] = date('Y-m-d h:i:s');
                    $this->Order->save(array('Order' => $order_info['Order']));
                }
                $this->Shipping->set_locale($this->backend_locale);
                //$shippings_list=$this->Shipping->shipping_list();
                $shippings_list = $this->Shipping->find('all', array('conditions' => array('Shipping.status' => 1)));
                //支持货到付款的配送方式
                $is_cod_shipping = array();
                foreach ($shippings_list as $ks => $kv) {
                    if ($kv['Shipping']['support_cod'] == 1) {
                        $is_cod_shipping[] = $kv['Shipping']['id'];
                    }
                }
                $order_info['Order']['is_cod'] = (in_array($order_info['Order']['shipping_id'], $is_cod_shipping) ? 1 : 0);
                $order_info['Order']['allvirtual'] = '';
                $order_info['Order']['novirtual_subtotal'] = 0;
                $order_info['Order']['regionname'] = array();
                //取得收货人的地址
                $regionid = array();
                $regionname1 = array();
                if (!empty($order_info['Order']['regions'])) {
                    $regionid = explode(' ', $order_info['Order']['regions']);
                    foreach ($regionid as $vid) {
                        if (!empty($vid)) {
                            $regionname1[] = $this->RegionI18n->find('list', array('fields' => ('name'), 'conditions' => array('RegionI18n.region_id' => $vid, 'RegionI18n.locale' => $this->locale)));
                        }
                    }
                }
                $order_info['Order']['regionname'] = $regionname1;
                //商品小计
                $virtualnum = $novsub = 0;
                foreach ($order_info['OrderProduct']as $kk => $vv) {
                    $order_info['OrderProduct'][$kk]['product_total'] = '';
                    $order_info['OrderProduct'][$kk]['product_total'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $vv['product_price'] * $vv['product_quntity']));
                    $order_info['OrderProduct'][$kk]['product_price'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $vv['product_price']));
                    $aa = isset($vv['extension_code']) ? $vv['extension_code'] : '0';
                    $virtualnum += (($aa == 'virtual_card') ? 1 : 0);
                    if ($aa == 'virtual_card') {
                        $novsub += ($vv['product_price'] * $vv['product_quntity']);
                    }
                }
//				$coupon_info=$this->Coupon->findById($order_info["Order"]["coupon_id"]);
//			   // $this->CouponType->set_locale($this->backend_locale);
//				$coupon_types_info=$this->CouponType->findById($coupon_info["Coupon"]["coupon_type_id"]);
//				$order_info['Order']['coupon_fee']=sprintf($this->configs['price_format'],sprintf("%01.2f",$order_info['Order']['coupon_fee']));
//				$order_info['Order']['coupon_fees']=$order_info['Order']['coupon_fee'];
                $order_info['Order']['allvirtual'] = (count($order_info['OrderProduct']) == $virtualnum) ? 1 : 0;
                $order_info['Order']['novirtual_subtotal'] = $order_info['Order']['subtotal'] - $novsub;
                //格式化价格数据
                $order_info['Order']['format_shipping_fee'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['shipping_fee']));
                $order_info['Order']['format_point_fee'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['point_fee']));
                $order_info['Order']['format_payment_fee'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['payment_fee']));
                $order_info['Order']['format_money_paid'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['money_paid']));
                $order_info['Order']['format_total'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['total']));
                $order_info['Order']['format_novir_subtotal'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['novirtual_subtotal']));
                $order_info['Order']['format_tax'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['tax']));
                $order_info['Order']['format_insure_fee'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['insure_fee']));
                $order_info['Order']['format_pack_fee'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['pack_fee']));
                $order_info['Order']['format_card_fee'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['card_fee']));
                $order_info['Order']['format_discount'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['discount']));
                $order_info['Order']['format_coupon_fee'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['coupon_fee']));
                $order_info['Order']['format_should_pay'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['total'] - $order_info['Order']['point_fee'] - $order_info['Order']['discount'] - $order_info['Order']['coupon_fee']));
                $all_order_info[] = $order_info;
            }
            $this->set('all_order_info', $all_order_info);
            $this->layout = 'print';
        }
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'订单打印:'.$order_info['Order']['order_code'], $this->admin['id']);
        }
    }

    //重发货
    public function new_order($order_code, $type = 'rma')
    {
        $this->operator_privilege('orders_redelivery');
        $new_order_code = $this->get_order_code();
        $order_data = $this->Order->find('first', array('conditions' => array('order_code' => $order_code)));
        $order_data_old = $order_data;
        //重发货
        if ($type == 'rma') {
            $order_data['Order']['id'] = '';
            $order_data['Order']['order_code'] = $new_order_code;
            $order_data['Order']['parent_order_code'] = $order_data_old['Order']['order_code'];//4.新订单parent_order_code保存老订单的order_code
            $order_data['Order']['chargeback_status'] = 0;
            $order_data['Order']['shipping_status'] = 0;
            $order_data['Order']['status'] = 1;
            $order_data['Order']['taobao_delivery_send'] = $order_data_old['Order']['taobao_delivery_send'];
            $order_data['Order']['shipping_time'] = '';
            $order_data['Order']['export_flag'] = 1;
            $order_data['Order']['created'] = date('Y-m-d H:i:s');
            $order_data['Order']['modified'] = date('Y-m-d H:i:s');
            $order_data['Order']['invoice_no'] = '';
            $order_data['Order']['payment_fee'] = 0;
            $order_data['Order']['money_paid'] = 0;
            $order_data['Order']['total'] = 0;
            $order_data['Order']['subtotal'] = 0;
            $order_data['Order']['discount'] = 0;
            $order_data['Order']['point_fee'] = 0;
            $order_data['Order']['tax'] = 0;
            $order_data['Order']['type_id'] = '网站';
            $order_data['Order']['type'] = 'ioco';
            $this->Order->saveAll(array('Order' => $order_data['Order']));//复制一个订单
            $order_id = $this->Order->getLastInsertId();
            foreach ($order_data['OrderProduct'] as $k => $v) {
                $v['id'] = '';
                $v['order_id'] = $order_id;
                $v['created'] = date('Y-m-d H:i:s');
                $v['modified'] = date('Y-m-d H:i:s');
                $v['product_price'] = 0;
                $this->OrderProduct->saveAll(array('OrderProduct' => $v));
                $this->Product->updateAll(array('Product.quantity' => 'quantity-'.$v['product_quntity'], 'Product.frozen_quantity' => 'frozen_quantity+'.$v['product_quntity']), array('Product.code' => $v['product_code']));
            }
            //更新老订单商品
            $order_data_old['Order']['status'] = 6;//2.设原订单状态为换货
            $this->Order->save(array('Order' => $order_data_old['Order']));
            //操作记录
            $old_act = array('order_id' => $order_data['Order']['id'],'from_operator_id' => $this->admin['id'],'user_id' => $order_data['Order']['user_id'],'order_status' => 6,'payment_status' => $order_data['Order']['payment_status'],'shipping_status' => $order_data['Order']['shipping_status'],'action_note' => '重发货');
            $this->OrderAction->update_order_action($old_act);
            $new_act = array('order_id' => $order_id,'from_operator_id' => $this->admin['id'],'user_id' => $order_data['Order']['user_id'],'order_status' => 0,'payment_status' => $order_data['Order']['payment_status'],'shipping_status' => 0,'action_note' => '重发货于订单'.$order_code);
            $this->OrderAction->update_order_action($new_act);
            $this->redirect('/orders/edit/'.$order_id);
        } elseif ($type = 'change') {
            //换货
            $order_data['Order']['id'] = '';
            $order_data['Order']['order_code'] = $new_order_code;
            $order_data['Order']['check_status'] = '0';
            $order_data['Order']['parent_order_code'] = $order_data_old['Order']['order_code'];//4.新订单parent_order_code保存老订单的order_code
            $order_data['Order']['chargeback_status'] = 0;
            $order_data['Order']['shipping_status'] = 0;
            $order_data['Order']['status'] = 1;
            $order_data['Order']['taobao_delivery_send'] = $order_data_old['Order']['taobao_delivery_send'];
            $order_data['Order']['shipping_time'] = '';
            $order_data['Order']['export_flag'] = 1;
            $order_data['Order']['created'] = date('Y-m-d H:i:s');
            $order_data['Order']['modified'] = date('Y-m-d H:i:s');
            $order_data['Order']['invoice_no'] = '';
            $order_data['Order']['payment_fee'] = 0;
            $order_data['Order']['money_paid'] = 0;
            $order_data['Order']['total'] = 0;
            $order_data['Order']['subtotal'] = 0;
            $order_data['Order']['discount'] = 0;
            $order_data['Order']['point_fee'] = 0;
            $order_data['Order']['refund_status'] = 2;
            $order_data['Order']['tax'] = 0;
            $order_data['Order']['type_id'] = '网站';
            $order_data['Order']['type'] = 'ioco';
            $this->Order->saveAll(array('Order' => $order_data['Order']));//复制一个订单
            $order_id = $this->Order->id;
            foreach ($order_data['OrderProduct'] as $k => $v) {
                $v['id'] = '';
                $v['order_id'] = $order_id;
                $v['created'] = date('Y-m-d H:i:s');
                $v['modified'] = date('Y-m-d H:i:s');
                $v['product_price'] = 0;
                $this->OrderProduct->saveAll(array('OrderProduct' => $v));
                $this->Product->updateAll(array('Product.quantity' => 'quantity-'.$v['product_quntity'], 'Product.frozen_quantity' => 'frozen_quantity+'.$v['product_quntity']), array('Product.code' => $v['product_code']));
            }
            //更新老订单商品
            $order_data_old['Order']['status'] = 6;//2.设原订单状态为换货
            $this->Order->save(array('Order' => $order_data_old['Order']));
            //操作记录
            $old_act = array('order_id' => $order_data['Order']['id'],'from_operator_id' => $this->admin['id'],'user_id' => $order_data['Order']['user_id'],'order_status' => 6,'payment_status' => $order_data['Order']['payment_status'],'shipping_status' => $order_data['Order']['shipping_status'],'action_note' => '换货');
            $this->OrderAction->update_order_action($old_act);
            $new_act = array('order_id' => $order_id,'from_operator_id' => $this->admin['id'],'user_id' => $order_data['Order']['user_id'],'order_status' => 0,'payment_status' => $order_data['Order']['payment_status'],'shipping_status' => 0,'action_note' => '换货于订单'.$order_code);
            $this->OrderAction->update_order_action($new_act);
            $this->redirect('/orders/edit/'.$order_id);
        }
    }

    public function get_batch_id()
    {
        $y = date('Y-m-d', time());
        $y .= ' 00:00:00';
        $this->Outbound->hasOne = array();
        $x = $this->Outbound->find('count', array('conditions' => array('Outbound.created >' => $y)));
        if ($x > 9999) {
            return false;
        }
        $x = str_pad($x, 4, '0', STR_PAD_LEFT);
        $y = date('Ymd', time());
        return $y.'02'.$x;
    }

    //获取区域
    public function getRegions()
    {
        $this->Region->set_locale($this->backend_locale);
        if ($_POST['id'] == 0) {
            $infos = $this->Region->find('all', array('conditions' => array('Region.parent_id' => 0), 'fields' => 'Region.id,RegionI18n.name'));
        } else {
            $infos = $this->Region->find('all', array('conditions' => array('Region.parent_id' => $_POST['id']), 'fields' => 'Region.id,RegionI18n.name'));
        }
        $result['region'] = $infos;
        echo json_encode($result);
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die;
    }

    public function get_sub_pay()
    {
        $rs = array();
        $id = $_REQUEST['id'];
        $rs['cd'] = 2;
        $sub_pay = array();
        $this->Payment->set_locale($this->backend_locale);
        //$code=$this->Payment->find('first',array('conditions'=>array('Payment.id'=>$id),'fields'=>array('Payment.code','Payment.config')));
        $sub_pay_list = $this->Payment->getOrderChildPayments($id);
        foreach ($sub_pay_list as $k => $v) {
            //$sub_pay['id'][$v['Payment']['id']]=$v['PaymentI18n']['name'];
            $sub_pay[] = array(
                'id' => $v['Payment']['id'],
                'value' => $v['PaymentI18n']['name'],
            );
        }
        if (!empty($sub_pay_list)) {
            $rs['cd'] = 0;
            $rs['ps'] = $sub_pay;
        }
//		if($code['Payment']['code'] == 'pos_pay' || $code['Payment']['code'] == 'bank_trans'){
//			$y=array();
//			$x=$code['Payment']['config'];
//			$x=unserialize($x);
//			if(isset($x['bank']['bb'])){
//				unset($x['bank']['bb']);
//			}
//			if(isset($x['bank']) && !empty($x['bank']) && isset($x['bank'][0]) && !empty($x['bank'][0])){
//				$rs['cd']=0;
//				$rs['ps']=$x['bank'];
//			}
//		}
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($rs));
    }

    //修改订单来源
    public function change_order_type()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $type = explode(':', $_POST['type']);
        $order_id = isset($_POST['oid']) ? $_POST['oid'] : 0;
        if (is_array($type) && $order_id != '' && isset($type[1]) && isset($type[0])) {
            $this->Order->updateAll(array('Order.type_id' => "'".$type[1]."'", 'Order.type' => "'".$type[0]."'", 'Order.modified' => "'".date('Y-m-d H:i:s')."'"), array('Order.id' => $order_id));
            $order_info = $this->Order->findbyid($order_id);
            //OrderAction
            $user_id = $order_info['Order']['user_id'];
            $shipping_status = $order_info['Order']['shipping_status'];
            $order_status = $order_info['Order']['status'];
            $payment_status = $order_info['Order']['payment_status'];
            $operation_notes = '编辑修改订单来源';
            $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status, $shipping_status, $operation_notes);
        }
        $result['code'] = 1;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function change_order_to_type()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $to_type = $_POST['to_type'];
        $to_type_id = $_POST['to_type_id'];
        $oid = $_POST['oid'];
        $this->Order->updateAll(array('Order.to_type' => "'".$to_type."'", 'Order.to_type_id' => "'".$to_type_id."'", 'Order.modified' => "'".date('Y-m-d H:i:s')."'"), array('Order.id' => $oid));
        $order_info = $this->Order->findbyid($oid);
        //OrderAction
        $user_id = $order_info['Order']['user_id'];
        $shipping_status = $order_info['Order']['shipping_status'];
        $order_status = $order_info['Order']['status'];
        $payment_status = $order_info['Order']['payment_status'];
        $operation_notes = '编辑修改订单去向';
        $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status, $shipping_status, $operation_notes);
        $result['code'] = 1;
        $total = $this->OrderProduct->find('count', array('conditions' => array('order_id' => $oid)));
        if ($total == 0) {
            $result['reload'] = false;
        } else {
            $result['reload'] = true;
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /*修改退货数量*/
    public function change_order_refund()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $id = $_POST['id'];
        $quantity = $_POST['quantity'];
        $result['code'] = 0;
        $result['message'] = '保存失败';
        if ($id > 0 && $quantity > 0) {
            $op = $this->OrderProduct->find('first', array('conditions' => array('OrderProduct.id' => $id)));
            if ($op['OrderProduct']['product_quntity'] < $quantity + $op['OrderProduct']['refund_quantity']) {
                $result['code'] = 0;
                $result['message'] = '退货数量不能大于购买数量';
                die(json_encode($result));
            } else {

                //	$op['OrderProduct']['adjust_fee'] =$op['OrderProduct']['adjust_fee']/($op['OrderProduct']['product_quntity']-$op['OrderProduct']['refund_quantity'])*($op['OrderProduct']['product_quntity']-$op['OrderProduct']['refund_quantity']-$quantity);
                $op['OrderProduct']['refund_quantity'] += $quantity;
                if ($op['OrderProduct']['refund_quantity'] == $op['OrderProduct']['product_quntity']) {
                    //退完
                    $op['OrderProduct']['adjust_fee'] = 0;
                }
                $this->OrderProduct->save(array('OrderProduct' => $op['OrderProduct']));
                $result['code'] = 1;
                if (!$data = $this->Order->calculate_total($op['OrderProduct']['order_id'])) {
                    $result['code'] = 0;
                    $result['message'] = '订单总金额保存失败';
                    die(json_encode($result));
                }
                //订单操作记录
                if (isset($data['status']) && $data['status'] == 4) {
                    $op['Order']['status'] = 4;
                }
                $this->OrderAction->update_order_action(array('order_id' => $op['Order']['id'], 'from_operator_id' => $this->admin['id'], 'order_status' => $op['Order']['status'], 'payment_status' => $op['Order']['payment_status'], 'shipping_status' => $op['Order']['shipping_status'], 'action_note' => '退货:'.$op['OrderProduct']['product_name'].$quantity.'个'));
                //退货直接进库
                if (!empty($_POST['warehouse'])) {
                    //日志
                    $inbound['batch_id'] = $this->Inbound->get_batch_id();
                    $inbound['warehouse_code'] = $_POST['warehouse'];
                    $inbound['created_operator_id'] = $this->admin['id'];
                    $inbound['inbound_type'] = 2;//客户退货
                    $inbound['quantity'] = $quantity;//客户退货
                    $this->Inbound->saveAll(array('Inbound' => $inbound));
                    $inbound_id = $this->Inbound->getLastInsertId();
                    //仓库
                    $stock = $this->Stock->find('first', array('conditions' => array('Stock.warehouse_code' => $_POST['warehouse'], 'Stock.product_code' => $op['OrderProduct']['product_code'])));
                    if (empty($stock)) {
                        $stock['Stock']['quantity'] = $quantity;
                        $stock['Stock']['warehouse_code'] = $_POST['warehouse'];
                        $stock['Stock']['product_code'] = $op['OrderProduct']['product_code'];
                        $this->Stock->saveAll(array('Stock' => $stock['Stock']));
                    } else {
                        $stock['Stock']['quantity'] = $stock['Stock']['quantity'] + $quantity;
                        $this->Stock->save(array('Stock' => $stock['Stock']));
                    }
                    //日志详细
                    $inboundproduct['inbound_id'] = $inbound_id;
                    $inboundproduct['product_code'] = $op['OrderProduct']['product_code'];
                    $inboundproduct['before_in'] = 0;
                    $inboundproduct['quantity'] = $quantity;
                    $inboundproduct['remark'] = '订单号:'.$op['Order']['order_code'];
                    $this->InboundProduct->saveAll(array('InboundProduct' => $inboundproduct));
                    //不是虚拟库存 修改可售数
                    //if(!in_array($op['OrderProduct']['product_code'],$this->get_xu_list())){
                    //   	$total = $this->Stock->get_total_num($op['OrderProduct']['product_code']);
                    //   	$this->Product->up_under_foz($total,$op['OrderProduct']['product_code']);
                    //}
                }
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    public function change_order_logistic()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $logistic_company_id = $_REQUEST['logistic_id'];
        $invoice_no = $_REQUEST['invoice_no'];
        $order_id = $_REQUEST['order_id'];
        $this->Order->updateAll(array('Order.logistics_company_id' => $logistic_company_id, 'Order.invoice_no' => "'".$invoice_no."'", 'Order.error_count' => 0, 'Order.modified' => "'".date('Y-m-d H:i:s')."'"), array('Order.id' => $order_id));
        Configure::write('debug', 0);
        die(json_encode(1));
    }

    //批量生成pdf
    public function batch_order_shipping_print_pdf($id = '')
    {
        $this->operator_privilege('orders_print');
        $this->Order->belongsTo = array();
        $pro_ids = '';
        if (empty($id)) {
            if (isset($this->params['form']['act']) && $this->params['form']['act'] == 'batch') {
                $pro_ids = !empty($this->params['form']['checkboxes']) ? $this->params['form']['checkboxes'] : 0;
            } else {
                $order_code = !empty($this->params['form']['order_code']) ? $this->params['form']['order_code'] : 0;
                $order = $this->Order->findByOrder_code($order_code);
                $pro_ids[] = $order['Order']['id'];
            }
        } else {
            $pro_ids[] = $id;
        }
        if (!empty($pro_ids)) {
            $all_order_info = array();
            foreach ($pro_ids as $vid) {
                //取得订单及订单商品信息
                $order_info = $this->Order->findById($vid);
                if ($order_info['Order']['express_status'] != 1) {
                    $order_info['Order']['express_status'] = 1;
                    $order_info['Order']['express_date'] = date('Y-m-d h:i:s');
                    $this->Order->save(array('Order' => $order_info['Order']));
                }
                $this->Shipping->set_locale($this->backend_locale);
                $shippings_list = $this->Shipping->find('all', array('conditions' => array('Shipping.status' => 1)));
                //支持货到付款的配送方式
                $is_cod_shipping = array();
                foreach ($shippings_list as $ks => $kv) {
                    if ($kv['Shipping']['support_cod'] == 1) {
                        $is_cod_shipping[] = $kv['Shipping']['id'];
                    }
                }
                $order_info['Order']['is_cod'] = (in_array($order_info['Order']['shipping_id'], $is_cod_shipping) ? 1 : 0);
                $order_info['Order']['allvirtual'] = '';
                $order_info['Order']['novirtual_subtotal'] = 0;
                $order_info['Order']['regionname'] = array();
                //取得收货人的地址
                $regionname1 = array();
                if (!empty($order_info['Order']['regions'])) {
                    $regionid = explode(' ', $order_info['Order']['regions']);
                    foreach ($regionid as $vid) {
                        if (!empty($vid)) {
                            $regionname1[] = $this->RegionI18n->find('list', array('fields' => ('name'), 'conditions' => array('RegionI18n.region_id' => $vid, 'RegionI18n.locale' => $this->locale)));
                        }
                    }
                }
                $order_info['Order']['regionname'] = $regionname1;
                //商品小计
                $virtualnum = $novsub = 0;
                foreach ($order_info['OrderProduct']as $kk => $vv) {
                    $order_info['OrderProduct'][$kk]['product_total'] = '';
                    $order_info['OrderProduct'][$kk]['product_total'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $vv['product_price'] * $vv['product_quntity'] + $vv['adjust_fee']));
                    $order_info['OrderProduct'][$kk]['product_price'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $vv['product_price']));
                    $order_info['OrderProduct'][$kk]['adjust_fee'] = sprintf($this->configs['price_format'], sprintf('%01.2f', ($vv['adjust_fee'] * (-1))));
                    $aa = isset($vv['extension_code']) ? $vv['extension_code'] : '0';
                    $virtualnum += (($aa == 'virtual_card') ? 1 : 0);
                    if ($aa == 'virtual_card') {
                        $novsub += ($vv['product_price'] * $vv['product_quntity']);
                    }
                }
                $coupon_info = $this->Coupon->findById($order_info['Order']['coupon_id']);
                // $this->CouponType->set_locale($this->backend_locale);
//				$coupon_types_info=$this->CouponType->findById($coupon_info["Coupon"]["coupon_type_id"]);
//				$order_info['Order']['coupon_fee']=sprintf($this->configs['price_format'],sprintf("%01.2f",$order_info['Order']['coupon_fee']));
//				$order_info['Order']['coupon_fees']=$order_info['Order']['coupon_fee'];
                $order_info['Order']['allvirtual'] = (count($order_info['OrderProduct']) == $virtualnum) ? 1 : 0;
                $order_info['Order']['novirtual_subtotal'] = $order_info['Order']['subtotal'] - $novsub;
                //格式化价格数据
                $order_info['Order']['format_shipping_fee'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['shipping_fee']));
                $order_info['Order']['format_point_fee'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['point_fee']));
                $order_info['Order']['format_payment_fee'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['payment_fee']));
                $order_info['Order']['format_money_paid'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['money_paid']));
                $order_info['Order']['format_total'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['total']));
                $order_info['Order']['format_novir_subtotal'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['novirtual_subtotal']));
                $order_info['Order']['format_tax'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['tax']));
                $order_info['Order']['format_insure_fee'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['insure_fee']));
                $order_info['Order']['format_pack_fee'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['pack_fee']));
                $order_info['Order']['format_card_fee'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['card_fee']));
                $order_info['Order']['should_pay'] = $order_info['Order']['total'] - $order_info['Order']['point_fee'] - $order_info['Order']['discount'] - $order_info['Order']['coupon_fee'] - $order_info['Order']['money_paid'];
                $order_info['Order']['format_should_pay'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['should_pay']));
                $order_info['Order']['format_discount'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['discount']));
                $order_info['Order']['format_coupon_fee'] = sprintf($this->configs['price_format'], sprintf('%01.2f', $order_info['Order']['coupon_fee']));
                $all_order_info[] = $order_info;
            }
            //logo图保存到本地
            if ($logo_addr = $this->configs['shop-order-logo']) {
                $ext = strrchr($logo_addr, '.');
                $filename = dirname(dirname($_SERVER['SCRIPT_FILENAME'])).'/orderlogo'.$ext;
                if (file_exists($filename)) {
                    unlink($filename);
                }
                ob_start();
                readfile($logo_addr);
                $img = ob_get_contents();
                ob_end_clean();
                $size = strlen($img);
                $fp2 = fopen($filename, 'a');
                fwrite($fp2, $img);
                fclose($fp2);
                if (file_exists($filename)) {
                    $this->set('order_logo', $filename);
                }
            }
            $this->set('all_order_info', $all_order_info);
            $this->layout = 'pdf';
            $this->render();
        }
    }

    public function export_flag($order_checkboxes = '')
    {
        foreach ($order_checkboxes as $k => $v) {
            $order_info = array(
                'export_flag' => '0',
                'id' => $v,
            );
            $this->Order->save($order_info);
            $order = $this->Order->find('first', array('conditions' => array('Order.id' => $v)));
            $this->OrderAction->update_order_action(array('order_id' => $v, 'from_operator_id' => $this->admin['id'], 'user_id' => $order['Order']['user_id'], 'order_status' => $order['Order']['status'], 'payment_status' => $order['Order']['payment_status'], 'shipping_status' => $order['Order']['shipping_status'], 'action_note' => '批量设置为已同步'));
        }
        $this->redirect('/orders');
    }

    public function order_export_flag($export_flag = 0)
    {
        $order_id = $_REQUEST['order_id'];
        $this->Order->updateAll(array('Order.export_flag' => $export_flag, 'Order.modified' => "'".date('Y-m-d H:i:s')."'"), array('Order.id' => $order_id));
        //操作员日志
        $result['code'] = 1;
        if ($export_flag == 0) {
            $result['message'] = '订单设为恢复同步';
        } else {
            $result['message'] = '订单设为暂停同步';
        }
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$result['message'].':'.$order_id, $this->admin['id']);
        }
        $order = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));
        $this->OrderAction->update_order_action(array('order_id' => $order_id, 'from_operator_id' => $this->admin['id'], 'user_id' => $order['Order']['user_id'], 'order_status' => $order['Order']['status'], 'payment_status' => $order['Order']['payment_status'], 'shipping_status' => $order['Order']['shipping_status'], 'action_note' => $result['message']));
        echo json_encode($result);
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die;
    }

    //订单的导出
    public function export_act($code)
    {
        $checkboxes = isset($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : 0;
        $condition = '';
        $condition['Order.id'] = $checkboxes;
        //$code=$_REQUEST['code'];
        $this->search_result($condition, $code, $code);
//      $orders_list=array();
// 	    $orders_list=$this->Order->find("all",array('conditions'=>array("Order.id"=>$checkboxes),"order"=>"Order.order_code desc"));
// 	 	$data=array();
// 	 	$data=array($this->ld["order_reffer"],$this->ld["order_number"],$this->ld["orders_time"],$this->ld["members_name"],$this->ld["paymengts"],$this->ld["shippingments"],$this->ld["order_status"],$this->ld["order_abbr_shippingFee"],$this->ld["paid"],$this->ld["product_total_amount"],$this->ld["preferential_price"],$this->ld["shipping_address"],$this->ld["zip_code"],$this->ld["telephone_number"],$this->ld["mobilephone_number"],$this->ld["sku"],$this->ld["merchandise_name"],$this->ld["app_unit_price"],$this->ld["products_number"],$this->ld["discount_price"]);
// 	 	$newdata=array();
// 	 	$newdata[]=$data;
// 	 	$Resource_info = $this->Resource->resource_formated(array("order_status","shipping_status","payment_status"),$this->backend_locale);
// 	 	foreach ($orders_list as $k=>$v) {
// 	 		$datas=array();
//     		foreach ($fields_array as $kk=>$vv) {
// 	 	if($v['OrderProduct']){
//	 	 	foreach($v['OrderProduct'] as $vv){
//	     	    if($v['Order']['type']=="fenxiao"){ $datas[]="分销"."-".$v['Order']['type_id'];}elseif($v['Order']['type']=="taobao"){  $datas[]="淘宝"."-".$v['Order']['type_id']; }else{ $datas[]="本站"."-".$v['Order']['type_id']; }
//	 	       	  $datas[]=$v['Order']['order_code'];
//	 	 		  $datas[]=$v['Order']['created'];
//	 	 		  $datas[]=$v['User']['name'];
//	 	 		  $datas[]=$v['Order']['payment_name'];
//	 	 		  $datas[]=$v['Order']['shipping_name'];
//	 	 		  $datas[] =$Resource_info["order_status"][$v['Order']['status']]." ".
//	              $Resource_info["payment_status"][$v['Order']['payment_status']]." ".
//	 			  $Resource_info["shipping_status"][$v['Order']['shipping_status']];
//	 	 		  $datas[]=$v['Order']['shipping_fee'];
//	 	 		  $datas[]=$v['Order']['money_paid'];
//	 	 		  $datas[]=$v['Order']['total'];
//	 	 		  $datas[]=$v['Order']['discount'];
//	 	 		  $datas[]=$v['Order']['country'].$v['Order']['province'].$v['Order']['city'].$v['Order']['district'].$v['Order']['address'];
//	 	 		  $datas[]=$v['Order']['zipcode'];
//	 	 		  $datas[]=$v['Order']['telephone'];
//	 	 		  $datas[]=$v['Order']['mobile'];
//	 	 		  $datas[]=isset($vv['id'])?$vv['id']:" ";
//	 	 		  $datas[]=isset($vv['product_name'])?$vv['product_name']:" ";
//	 	 		  $datas[]=isset($vv['product_price'])?$vv['product_price']:" ";
//	 	 		  $datas[]=isset($vv['product_quntity'])?$vv['product_quntity']:" ";
//	 	 		  $datas[]=isset($vv['adjust_fee'])?$vv['adjust_fee']:" ";
//	 	 		  $newdata[]=$datas;
//	 	 	}
// 	 	}else{
//     	    if($v['Order']['type']=="fenxiao"){
//     	    	$datas[]="分销"."-".$v['Order']['type_id'];
//     	    }elseif($v['Order']['type']=="taobao"){
//     	    	$datas[]="淘宝"."-".$v['Order']['type_id'];
//     	    }else{
//     	    	$datas[]="本站"."-".$v['Order']['type_id'];
//     	    }
// 	       	  $datas[]=$v['Order']['order_code'];
// 	 		  $datas[]=$v['Order']['created'];
// 	 		  $datas[]=$v['User']['name'];
// 	 		  $datas[]=$v['Order']['payment_name'];
// 	 		  $datas[]=$v['Order']['shipping_name'];
// 	 		  $datas[] =$Resource_info["order_status"][$v['Order']['status']]." ".
//              $Resource_info["payment_status"][$v['Order']['payment_status']]." ".
// 			  $Resource_info["shipping_status"][$v['Order']['shipping_status']];
// 	 		  $datas[]=$v['Order']['shipping_fee'];
// 	 		  $datas[]=$v['Order']['money_paid'];
// 	 		  $datas[]=$v['Order']['total'];
// 	 		  $datas[]=$v['Order']['discount'];
// 	 		  $datas[]=$v['Order']['country'].$v['Order']['province'].$v['Order']['city'].$v['Order']['district'].$v['Order']['address'];
// 	 		  $datas[]=$v['Order']['zipcode'];
// 	 		  $datas[]=$v['Order']['telephone'];
// 	 		  $datas[]=$v['Order']['mobile'];
// 	 		  $datas[]=" ";
// 	 		  $datas[]=" ";
// 	 		  $datas[]=" ";
// 	 		  $datas[]=" ";
// 	 		  $datas[]=" ";
// 	 		  $newdata[]=$datas;
// 	      	}
// 	 	}
//   		 $this->Phpexcel->output("Order_Export".date('YmdHis').".xls",$newdata);
// 	 	 die;
    }

    public function batch_order_all_split($code = '')
    {
        $order_parent_id = $this->Order->find('first', array('fields' => array('Order.id'), 'conditions' => array('Order.order_code' => $code)));//合并后订单ID
        $order_all = $this->Order->find('all', array('fields' => array('Order.id', 'Order.order_code'), 'conditions' => array('Order.parent_order_code' => $code)));//合并后子订单
        if (is_array($order_all)) {
            $order_all_id = array();
            foreach ($order_all as $k => $v) {
                $order_all_id[$k] = $v['Order']['id'];
                //操作员日志
                if ($this->configs['operactions-log'] == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'还原了订单号'.'.'.$v['Order']['order_code'], $this->admin['id']);
                }
            }
            $this->Order->updateAll(array('Order.status' => '1', 'Order.parent_order_code' => '0', 'Order.modified' => "'".date('Y-m-d H:i:s')."'"), array('Order.id' => $order_all_id));
            $this->Order->updateAll(array('Order.status' => '2', 'Order.modified' => "'".date('Y-m-d H:i:s')."'"), array('Order.order_code' => $code));
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'取消了合并订单号'.'.'.$code, $this->admin['id']);
            }
            $this->redirect('/orders/edit/'.$order_parent_id['Order']['id']);
        }
    }

    /**
     * 函数 check_coupon 修改支付方式.
     *
     * @param $order_info
     *
     * @author chenfan 2012/05/29
     */
    public function check_coupon($order_info)
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $product_coupon_type = array();
        $now = date('Y-m-d H:i:s');
        //取出order优惠券
        $order_coupon_type = $this->CouponType->find('all', array('conditions' => "CouponType.send_type = 2 and CouponType.send_start_date <= '".$now."' and CouponType.send_end_date >= '".$now."'"));
        //取出商品的优惠券
        if (isset($order_info['OrderProduct']) && sizeof($order_info['OrderProduct']) > 0) {
            $p_ids = array();
            $p_nums = array();
            $coupon_nums = array();
            foreach ($order_info['OrderProduct'] as $op) {
                $p_ids[] = $op['product_id'];
                $p_nums[$op['product_id']] = $op['product_quntity'];
            }
            if (!empty($p_ids)) {
                $coupon_type_ids = $this->CouponProduct->find('list', array('conditions' => array('CouponProduct.product_id' => $p_ids), 'fields' => 'CouponProduct.product_id,CouponProduct.coupon_type_id'));
                if (!empty($coupon_type_ids)) {
                    foreach ($coupon_type_ids as $k => $v) {
                        if (!isset($coupon_nums[$v])) {
                            $coupon_nums[$v] = 0;
                        }
                        $coupon_nums[$v] += $p_nums[$k];
                    }
                    $conditions = array();
                    $conditions['CouponType.id'] = $coupon_type_ids;
                    $conditions['CouponType.send_start_date <='] = $now;
                    $conditions['CouponType.send_end_date >='] = $now;
                    $product_coupon_type = $this->CouponType->find('all', array('conditions' => $conditions));
                }
            }
        }
        $coupon_types = array_merge($order_coupon_type, $product_coupon_type);
        if (is_array($coupon_types) && sizeof($coupon_types) > 0) {
            $coupon_arr_list = $this->Coupon->find('list', array('conditions' => array('1=1'), 'fields' => array('Coupon.sn_code')));//标注
            $coupon_arr = array();
            if (is_array($coupon_arr_list) && sizeof($coupon_arr_list) > 0) {
                foreach ($coupon_arr_list as $k => $v) {
                    $coupon_arr[] = $v;
                }
            }
            $coupon_count = count($coupon_arr);
            $num = 0;
            if ($coupon_count > 0) {
                $num = $coupon_arr[$coupon_count - 1];
            }
            $order_coupon = array();
            foreach ($coupon_types as $k => $v) {
                if ($v['CouponType']['min_products_amount'] < $order_info['Order']['subtotal']) {
                    if (isset($coupon_sn)) {
                        $num = $coupon_sn;
                    }
                    $num = substr($num, 2, 10);
                    $num = $num ? floor($num / 10000) : 100000;
                    $coupon_sn = $v['CouponType']['prefix'].$num.str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                    if (isset($coupon_nums[$v['CouponType']['id']]) && $coupon_nums[$v['CouponType']['id']] > 0) {
                        for ($i = 1;$i <= $coupon_nums[$v['CouponType']['id']];++$i) {
                            $order_coupon[] = array(
                                'id' => '',
                                'coupon_type_id' => $v['CouponType']['id'],
                                'user_id' => $order_info['Order']['user_id'],
                                'sn_code' => $coupon_sn,
                            );
                            $coupon_sn = $v['CouponType']['prefix'].$num.str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                        }
                    } else {
                        $order_coupon[] = array(
                            'id' => '',
                            'coupon_type_id' => $v['CouponType']['id'],
                            'user_id' => $order_info['Order']['user_id'],
                            'sn_code' => $coupon_sn,
                        );
                    }
                }
            }
            $this->Coupon->saveAll($order_coupon);
        }
    }

    public function uploadorders()
    {
        $this->operator_privilege('orders_upload');
        $this->menu_path = array('root' => '/oms/','sub' => '/orders/');
        $this->navigations[] = array('name' => $this->ld['manager_orders'],'url' => '/orders/');
        $this->navigations[] = array('name' => $this->ld['order_bulk_upload_orders'],'url' => '');
        $flag_code = 'order_import';
        $this->Profile->set_locale($this->backend_locale);
        $profilefiled_codes = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
        $this->set('title_for_layout', $this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
        if (isset($profilefiled_codes) && !empty($profilefiled_codes)) {
            $this->set('profilefiled_codes', $profilefiled_codes);
        }
    }

    public function uploaddelivery()
    {
        $this->menu_path = array('root' => '/oms/','sub' => '/orders/');
        $this->navigations[] = array('name' => $this->ld['manager_orders'],'url' => '/orders/');
        $this->navigations[] = array('name' => $this->ld["import"].$this->ld["delivery"],'url' => '');
        $flag_code = 'order_import';
        $this->Profile->set_locale($this->backend_locale);
        $profilefiled_codes = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
        $this->set('title_for_layout', $this->ld['bulk_upload'].' - '.$this->configs['shop_name']);
        if (isset($profilefiled_codes) && !empty($profilefiled_codes)) {
            $this->set('profilefiled_codes', $profilefiled_codes);
        }
    }

    public function uploaddeliverypreview()
    {
        $this->operator_privilege('orders_upload');
        if ($this->RequestHandler->isPost()) {
            $this->menu_path = array('root' => '/oms/','sub' => '/orders/');
            $this->navigations[] = array('name' => $this->ld['manager_orders'],'url' => '/orders/');
            $this->navigations[] = array('name' => $this->ld["import"].$this->ld["delivery"],'url' => '');
            $this->navigations[] = array('name' => $this->ld['preview'],'url' => '');
            //$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
            //pr($profilefiled_info);
            $flag_code = 'order_delivery';
            $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
            //pr($profilefiled_info);
            $fields_array = array();
            foreach ($profilefiled_info as $key => $value) {
                if($key%2 == 1){
                    $fields_array[]= $value['ProfileFiled']['code'];
                }
                
            }
            //pr($fields_array);exit();
            
            set_time_limit(300);
            if (!empty($_FILES['file'])) {
                if ($_FILES['file']['error'] > 0) {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/orders/uploaddelivery'</script>";
                    die();
                } else {
                    $handle = @fopen($_FILES['file']['tmp_name'], 'r');
                    $csv_export_code = 'gb2312';
                    $i = 0;
                    $key_arr = array();
                    foreach ($fields_array as $k => $v) {
                    $fields_k = explode('.', $v);
                    //pr($fields_k);exit();
                    $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                    }
                    //pr($key_arr);exit();
                    // $key_arr[0]='order_code';
                    // $key_arr[1]='logistics_code';
                    // $key_arr[2]='tracking_number';
                    while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                        if ($i == 0) {
                            $check_row = $row[0];
                            $row_count = count($row);
                            $check_row = iconv('GB2312', 'UTF-8', $check_row);
                            if ($check_row != $this->ld['order_code']) {
                                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('上传文件格式不标准');window.location.href='/admin/orders/uploaddelivery';</script>";
                            }
                            ++$i;
                        }
                        $temp = array();
                        foreach ($row as $k => $v) {
                            $temp[$key_arr[$k]] = empty($v) ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                        }
                        if (!isset($temp) || empty($temp)) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/orders/uploaddelivery';</script>";
                        }
                        $data[] = $temp;
                    }
                    fclose($handle);
                    foreach ($data as $k => $v) {
                        if ($k == 0) {
                            continue;
                        }
                    }
                    //pr($data);exit();
                    $this->set('uploads_list', $data);
                }
            }
        } else {
            $this->redirect('/orders/');
        }
    }

    public function uploadorderspreview()
    {
        $this->operator_privilege('orders_upload');
        if ($this->RequestHandler->isPost()) {
            $this->menu_path = array('root' => '/oms/','sub' => '/orders/');
            $this->navigations[] = array('name' => $this->ld['manager_orders'],'url' => '/orders/');
            $this->navigations[] = array('name' => $this->ld['order_bulk_upload_orders'],'url' => '');
            $this->navigations[] = array('name' => $this->ld['preview'],'url' => '');
            $flag_code = 'order_import';
            $this->set('extension_code', array('' => $this->ld['real_product'], 'virtual_card' => $this->ld['virtual_cards']));
            $this->Profile->set_locale($this->backend_locale);
            set_time_limit(300);
            if (!empty($_FILES['file'])) {
                if ($_FILES['file']['error'] > 0) {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/orders/uploadorders'</script>";
                    die();
                } else {
                    $handle = @fopen($_FILES['file']['tmp_name'], 'r');
                    $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
                    $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
                    if (empty($profilefiled_info)) {
                        echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/orders/uploadorders';</script>";
                        die();
                    }
                    $key_arr = array();
                    foreach ($profilefiled_info as $k => $v) {
                        $fields_k = explode('.', $v['ProfileFiled']['code']);
                        $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                    }
                    $csv_export_code = 'gb2312';
                    $i = 0;
                    while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                        if ($i == 0) {
                            $check_row = $row[0];
                            $row_count = count($row);
                            $check_row = iconv('GB2312', 'UTF-8', $check_row);
                            $num_count = count($profilefiled_info);
                            if ($row_count > $num_count || $check_row != $profilefiled_info[0]['ProfilesFieldI18n']['description']) {
                                echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('上传文件格式不标准');window.location.href='/admin/orders/uploadorders';</script>";
                            }
                            ++$i;
                        }
                        $temp = array();
                        foreach ($row as $k => $v) {
                            $temp[$key_arr[$k]] = empty($v) ? '' : @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                        }
                        if (!isset($temp) || empty($temp)) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/orders/uploadorders';</script>";
                        }
                        $data[] = $temp;
                    }
                    fclose($handle);
                    foreach ($data as $k => $v) {
                        if ($k == 0) {
                            continue;
                        }
                        if ($v['status'] == '') {
                            $data[$k]['status'] = $data[$k - 1]['status'];
                        }
                    }
                    $msg = $this->check_order_products($data);
                    $this->set('msg', $msg);
                    $this->set('profilefiled_info', $profilefiled_info);
                    $this->set('uploads_list', $data);
                }
            }
        } else {
            $this->redirect('/orders/');
        }
    }

    //检测上传订单商品  chenfan
    public function check_order_products($data)
    {
        $this->loadModel('Store');
        $this->loadModel('LogisticsCompany');
        $this->loadModel('Warehouse');
        $this->loadModel('Stock');
        $this->loadModel('Outbound');
        $this->loadModel('OutboundProduct');
        $this->loadModel('Order');
        $this->loadModel('OrderProduct');
        $this->loadModel('OrderAction');
        $operator_id = $this->admin['id'];
        //导入订单所有信息数据
        $product_codes = array();
        $payment_names = array();
        $sub_payment_names = array();
        $shipping_names = array();
        $types = array();
        $order_status = array('未付款', '未发货', '已发货');
        $logistics_companys = array();
        $warehouse_codes = array();
        $code_quantity_infos = array();
        $warehouse_code_quantity_infos = array();
        //订单不存在的信息数据
        $without_product_codes = array();//不存在的商品货号
        $without_quantity_product_codes = array();//商品可售不足的商品货号
        $without_warehouse_quantity_product_codes = array();//商品仓库库存不足的商品货号
        $without_payment_names = array();//不存在的支付方式
        $without_sub_payment_names = array();//不存在的二级支付方式
        $without_shipping_names = array();//不存的配送方式
        $without_types = array();//不存在的订单来源
        $without_order_status = array();//不存在的订单状态
        $without_logistics_companys = array();//不存在的物流公司
        $without_warehouse_codes = array();//不存在的仓库code
        $without_types_permission = array();//没有权限的订单来源
        $without_warehouse_permission = array();//没有权限的仓库code
        foreach ($data as $k => $v) {
            if ($k == 0) {
                continue;
            }
            $data[$k]['product_quntity'] = (int) ($v['product_quntity']);
            //统计订单商品库存 判断可售数量 判断仓库库存
            if (isset($v['product_code']) && $v['product_code'] != '') {
                if (!isset($code_quantity_info[$v['product_code']])) {
                    $code_quantity_infos[$v['product_code']] = $data[$k]['product_quntity'];
                } else {
                    $code_quantity_infos[$v['product_code']] += $data[$k]['product_quntity'];
                }
                if ($v['status'] == '已发货') {
                    if (!isset($warehouse_code_quantity_infos[$v['warehouse_code']][$v['product_code']])) {
                        $warehouse_code_quantity_infos[$v['warehouse_code']][$v['product_code']] = $data[$k]['product_quntity'];
                    } else {
                        $warehouse_code_quantity_infos[$v['warehouse_code']][$v['product_code']] += $data[$k]['product_quntity'];
                    }
                }
            }
            $data[$k]['product_price'] = (int) ($v['product_price']);
            $data[$k]['subtotal'] = (int) ($v['subtotal']);
            $data[$k]['subtotal'] = (int) ($v['subtotal']);
            $data[$k]['money_paid'] = (int) ($v['money_paid']);
            $data[$k]['payment_fee'] = (int) ($v['payment_fee']);
            $data[$k]['shipping_fee'] = (int) ($v['shipping_fee']);
            if (isset($v['product_code']) && $v['product_code'] != '' && !in_array($v['product_code'], $product_codes)) {
                $product_codes[] = $v['product_code'];
            }
            if (isset($v['payment_name']) && $v['payment_name'] != '' && !in_array($v['payment_name'], $payment_names)) {
                $payment_names[] = $v['payment_name'];
            }
            if (isset($v['shipping_name']) && $v['shipping_name'] != '' && !in_array($v['shipping_name'], $shipping_names)) {
                $shipping_names[] = $v['shipping_name'];
            }
            if (isset($v['type_id']) && $v['type_id'] != '' && !in_array($v['type_id'], $types)) {
                $types[] = $v['type_id'];
            }
            if (isset($v['logistics_company_id']) && $v['logistics_company_id'] != '' && !in_array($v['logistics_company_id'], $logistics_companys)) {
                $logistics_companys[] = $v['logistics_company_id'];
            }
            if (isset($v['warehouse_code']) && $v['warehouse_code'] != '' && !in_array($v['warehouse_code'], $warehouse_codes)) {
                $warehouse_codes[] = $v['warehouse_code'];
            }
        }
        $msg = '';
        //判断类型是否存在
        $this->loadModel('TaobaoShop');
        $taobao_shop_arr = $this->TaobaoShop->find('list', array('conditions' => array('status' => 1), 'order' => 'orderby asc', 'fields' => array('nick')));
        $store_arr = array();
        $store_permissions = array();
        $this->Store->set_locale($this->backend_locale);
        $stores = $this->Store->find('all', array('conditions' => array('status' => 1), 'fields' => array('store_sn', 'Store.operator_id', 'StoreI18n.name'), 'order' => 'orderby'));
        if (!empty($stores)) {
            foreach ($stores as $sk => $sv) {
                $store_arr[] = $sv['StoreI18n']['name'];
                $store_permissions[$sv['StoreI18n']['name']] = explode(',', $sv['Store']['operator_id']);
            }
        }
        if (!empty($types)) {
            foreach ($types as $ty) {
                //判断格式
                if ($ty == '本站' || $ty == '门店' || $ty == '批发' || in_array($ty, $taobao_shop_arr) || in_array($ty, $store_arr)) {
                    //$ty == "网站" ||
                    //判断权限//$ty == "网站" ||
                    if ($ty == '本站' || $ty == '门店' || in_array($ty, $taobao_shop_arr) || !isset($store_permissions[$ty]) || !in_array($operator_id, $store_permissions[$ty])) {
                        $without_types_permission[] = $ty;
                    }
                    continue;
                } elseif ($ty != '网站') {
                    $without_types[] = $ty;
                }
            }
        }
        //判断商品是否存在
        if (empty($product_codes)) {
            $msg .= '商品货号不能为空';
        } else {
            //判断商品是否存在  判断商品可售数是否充足
            foreach ($product_codes as $pro) {
                $product = $this->Product->find('first', array('conditions' => array('Product.code' => $pro), 'fields' => 'Product.id,Product.code,Product.quantity'));
                if (empty($product)) {
                    $without_product_codes[] = $pro;
                } else {
                    if ($product['Product']['quantity'] < $code_quantity_infos[$product['Product']['code']]) {
                        $without_quantity_product_codes[] = $pro;
                    }
                }
            }
        }
        //判断支付方式是否存在
        if (empty($payment_names)) {
            $msg .= '支付方式不能为空';
        } else {
            foreach ($payment_names as $pay) {
                $this->Payment->set_locale($this->backend_locale);
                $payment = $this->Payment->find('first', array('conditions' => array('PaymentI18n.name' => $pay, 'Payment.status' => 1), 'fields' => 'Payment.id'));
                if (empty($payment)) {
                    $without_payment_names[] = $pay;
                }
            }
        }
        //二级支付方式是否存在
        if (!empty($sub_payment_names)) {
            foreach ($sub_payment_names as $sub_pay) {
                $pay = explode('-', $sub_pay);
                if ($pay[0] == '') {
                    $without_sub_payment_names[] = $pay[1];
                    continue;
                }
                $this->Payment->set_locale($this->backend_locale);
                $payment = $this->Payment->find('first', array('conditions' => array('PaymentI18n.name' => $pay), 'fields' => 'Payment.config,Payment.id'));
                $x = $payment['Payment']['config'];
                if ($x != '') {
                    $x = unserialize($x);
                    if (isset($x['bank']['bb'])) {
                        unset($x['bank']['bb']);
                    }
                    $a = true;
                    foreach ($x['bank'] as $v) {
                        if (trim($v) == trim($pay[1])) {
                            $a = false;
                        }
                    }
                    if ($a) {
                        $without_sub_payment_names[] = $pay[1];
                    }
                }
            }
        }
        //判断配送方式是否存在
        if (empty($shipping_names)) {
            $msg .= '配送方式不能为空';
        } else {
            foreach ($shipping_names as $ship) {
                $this->Shipping->set_locale($this->backend_locale);
                $shipping = $this->Shipping->find('first', array('conditions' => array('ShippingI18n.name' => $ship, 'Shipping.status' => 1), 'fields' => 'Shipping.id'));
                if (empty($shipping)) {
                    $without_shipping_names[] = $ship;
                }
            }
        }
        //判断物流公司是否存在
        if (!empty($logistics_companys)) {
            foreach ($logistics_companys as $lc) {
                $logistics_company = $this->LogisticsCompany->find('first', array('conditions' => array('LogisticsCompany.id' => $lc), 'fields' => 'LogisticsCompany.id'));
                if (empty($logistics_company)) {
                    $without_logistics_companys[] = $lc;
                }
            }
        }
        //判断仓库是否存在
        if (in_array('APP-WAREHOUSE', $this->apps['codes'])) {
            if (!empty($warehouse_codes)) {
                foreach ($warehouse_codes as $wa) {
                    $warehouse = $this->Warehouse->find('first', array('conditions' => array('Warehouse.code' => $wa), 'fields' => array('Warehouse.code', 'Warehouse.warehouse_name', 'Warehouse.operator_id')));
                    if (empty($warehouse)) {
                        $without_warehouse_codes[] = $wa;
                    } else {
                        //判断权限 仓库
                        $operator_ids = explode(',', $warehouse['Warehouse']['operator_id']);
                        if (!in_array($operator_id, $operator_ids)) {
                            $without_warehouse_permission[] = $wa;
                        } else {
                            //判断商品仓库库存
                            foreach ($warehouse_code_quantity_infos[$wa] as $wk => $wpq) {
                                if (in_array($wk, $without_product_codes)) {
                                    continue;
                                }
                                $stock = $this->Stock->find('first', array('conditions' => array('Stock.warehouse_code' => $wa, 'Stock.product_code' => $wk)));
                                if (empty($stock) || ((($stock['Stock']['quantity'] - $wpq) < 0))) {
                                    if (isset($without_warehouse_quantity_product_codes[$wa])) {
                                        $without_warehouse_quantity_product_codes[$wa] .= ','.$wk;
                                    } else {
                                        $without_warehouse_quantity_product_codes[$wa] = $wk;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        if (!empty($without_types)) {
            $msg_types = implode(',', $without_types);
            $msg .= $msg_types.' 订单来源不存在';
        }
        if (!empty($without_types_permission)) {
            $msg_permission_types = implode(',', $without_types_permission);
            $msg .= $msg_permission_types.' 订单来源无权限';
        }
        if (!empty($without_product_codes)) {
            $msg_codes = implode(',', $without_product_codes);
            $msg .= $msg_codes.' 货号不存在';
        }
        if (!empty($without_quantity_product_codes)) {
            $msg_codes = implode(',', $without_quantity_product_codes);
            $msg .= $msg_codes.' 商品可售数不足';
        }
        if (!empty($without_payment_names)) {
            $msg_pays = implode(',', $without_payment_names);
            $msg .= $msg_pays.' 支付方式不存在';
        }
        if (!empty($without_sub_payment_names)) {
            $msg_pays = implode(',', $without_sub_payment_names);
            $msg .= $msg_pays.'二级支付方式不存在';
        }
        if (!empty($without_shipping_names)) {
            $msg_ships = implode(',', $without_shipping_names);
            $msg .= $msg_ships.' 配送方式不存在';
        }
        if (!empty($without_order_status)) {
            $msg_status = implode(',', $without_order_status);
            $msg .= $msg_status.' 订单状态不正确';
        }
        if (!empty($without_logistics_companys)) {
            $msg_lc_name = implode(',', $without_logistics_companys);
            $msg .= $msg_lc_name.' 物流公司不存在';
        }
        if (in_array('APP-WAREHOUSE', $this->apps['codes'])) {
            if (!empty($without_warehouse_codes)) {
                $msg_warehouse = implode(',', $without_warehouse_codes);
                $msg .= $msg_warehouse.' 仓库不存在';
            }
            if (!empty($without_warehouse_permission)) {
                $msg_warehouse_permission = implode(',', $without_warehouse_permission);
                $msg .= $msg_warehouse_permission.' 仓库无权限';
            }
            if (!empty($without_warehouse_quantity_product_codes)) {
                foreach ($without_warehouse_quantity_product_codes as $wk => $wp) {
                    $msg .= '仓库 '.$wk.':'.$wp.' ';
                }
                $msg .= ' 库存不足';
            }
        }
        if ($msg != '') {
            $msg .= '请重新上传！';
        }
        return $msg;
    }

    public function batch_delivery_orders(){
        if ($this->RequestHandler->isPost()) {
            $checkbox_arr = $_REQUEST['checkbox'];
            $msg='';
            $i=0;
            foreach ($this->data as $key => $v) {
                if (!in_array($key, $checkbox_arr)) {
                    unset($this->data[$key]);
                    continue;
                }
            }
            //pr($this->data);
            foreach ($this->data as $key => $data) {
                $order_id = $this->Order->find('first', array('conditions' => array('Order.order_code' => $data['order_code']), 'fields' => 'Order.id'));

                //pr($order_id);exit();
                if(empty($order_id)){
                    $msg .= $data['order_code'].'不存在;';
                    continue;
                }
                //pr($order_id);
                //pr($data);exit();
                $o_id = $order_id['Order']['id'];
                //pr($o_id);exit();
                $order = $this->Order->find('first', array('conditions' => array('Order.id' => $o_id)));
                //pr($order);exit();
                $order_shipment = $this->OrderShipment->find('first', array('conditions' => array('OrderShipment.order_id' => $o_id,'OrderShipment.status'=>'0')));

                //$order['id'] = $order_id['OrderShipment']['id'];
                //pr($order_shipment);exit();
                //$order['payment_status'] = 2;
                //$order['shipping_status'] = 1;
                //pr($data);exit();
                //pr($district);
                $this->Region->set_locale($this->backend_locale);
                $regions = $this->Region->find('all',array('conditions'=>array()));
                $logisticscompany = $this->LogisticsCompany->find('all', array('conditions' => array()));
                //pr($regions);
                //pr($logisticscompany);exit();
                foreach ($logisticscompany as $key11 => $value11) {
                    if($data['logistics_company_id'] == $value11['LogisticsCompany']['code']){
                        $data['logistics_company_id'] = $value11['LogisticsCompany']['id'];
                    }
                }

                foreach ($regions as $key1 => $value1){
                    //pr($value1['RegionI18n']);
                    if($data['country'] == $value1['RegionI18n']['name']){
                        $data['country'] = $value1['RegionI18n']['region_id'];
                    }
                    if($data['province'] == $value1['RegionI18n']['name']){
                        $data['province'] = $value1['RegionI18n']['region_id'];
                    }
                    if($data['city'] == $value1['RegionI18n']['name']){
                        $data['city'] = $value1['RegionI18n']['region_id'];
                    }
                    if($data['district'] == $value1['RegionI18n']['name']){
                        $data['district'] = $value1['RegionI18n']['region_id'];
                    }
                }
                //pr($data);exit();
                $order_shipment['OrderShipment']['order_id'] = $o_id;
                $order_shipment['OrderShipment']['status'] = '1';
                foreach ($data as $k => $v) {
                    if($k != 'order_code'){
                        $order_shipment['OrderShipment'][$k] = $v;
                    }
                }
                foreach ($data as $kk => $vv) {
                    if($kk == 'logistics_company_id'){
                        $order['Order'][$kk] = $vv;
                    }
                    if($kk == 'invoice_no'){
                        $order['Order'][$kk] = $vv;
                    }
                    $order['Order']['shipping_status'] = 1;
                }
                // if (!empty($data['logistics_code'])) {
                //     $logistics_company = $this->LogisticsCompany->find('first', array('conditions' => array('LogisticsCompany.code' => $data['logistics_code']), 'fields' => 'LogisticsCompany.id'));
                //     if (empty($logistics_company)) {
                //         $msg .= '找不到物流公司:'.$data['logistics_code'].';';
                //     }
                // }
                // $order['logistics_company_id'] = isset($logistics_company['LogisticsCompany']['id']) ? $logistics_company['LogisticsCompany']['id'] : 0;
                // $order['invoice_no'] = $data['tracking_number'];
                //pr($order_shipment);
                $this->Order->save($order);
                $this->OrderShipment->save($order_shipment);
                $i++;
            }
            $msg .="成功发货".$i."条";
            echo "<meta charset='utf-8' /><script>alert('".$msg."');window.location.href='/admin/orders';</script>";
            die();
        }
        $this->redirect('/orders/');
    }

    public function batch_add_orders()
    {
        $this->operator_privilege('orders_upload');
        Configure::write('debug', 1);
        $this->layout="ajax";
        if ($this->RequestHandler->isPost()) {
            $checkbox_arr = $_REQUEST['checkbox'];
            $product_codes = array();
            $store_arr = array();
            $stores = $this->Store->find('all', array('conditions' => array('status' => 1)));
            foreach ($stores as $sk => $sv) {
                $store_arr[$sv['StoreI18n']['name']] = $sv['Store']['store_sn'];
            }
            $msg = $this->check_order_products($this->data);
            if ($msg != '') {
	                $msg = str_replace("\n",  '\\n',  $msg);
	                $msg = str_replace("\r",  '\\r',  $msg);
	                echo "<meta charset='utf-8' /><script>alert('".$msg."');window.location.href='/admin/orders/uploadorders';</script>";
	                die();
            }
            $upload_order_list=array();
            foreach ($this->data as $key => $data) {
			if (!in_array($key, $checkbox_arr))continue;
			$order=array();
			//判断商品是否存在
			$this->Product->set_locale($this->backend_locale);
			$order_product_info = $this->Product->find('first', array('conditions' => array('Product.code' => $data['product_code']), 'recursive' => -1));
			if (empty($order_product_info)) {
				$product_codes[] = $data['product_code'];
				continue;
			}
			$user_id=0;
                	if ($data['mobile'] != '' || $data['email'] != '' || $data['telephone'] != '') {
                		$user_address=array();
                		$user_address['user_id >'] = 0;
                		$user_address['consignee'] = $data['consignee'];
                        	if($data['email'] != '')$user_address['or']['email'] = $data['email'];
                        	if($data['mobile'] != '')$user_address['or']['mobile'] = $data['mobile'];
                        	if($data['telephone'] != '')$user_address['or']['telephone'] = $data['telephone'];
                        	$address=$this->UserAddress->find('first',array('conditions'=>$user_address));
                        	if(empty($address)){
                        		$user_cond=array();
                        		if($data['mobile'] != ''){
                        			$user_cond['or']['mobile'] = $data['mobile'];
                        			$user_cond['or']['user_sn'] = $data['mobile'];
                        		}else if($data['email'] != ''){
                        			$user_cond['or']['email'] = $data['email'];
                        			$user_cond['or']['user_sn'] = $data['email'];
                        		}else if($data['telephone'] != ''){
                        			$user_cond['or']['mobile'] = $data['telephone'];
                        		}
                        		$user_info = $this->User->find('first', array('conditions' =>$user_cond));
                        		$user=array();
                        		$user['id']=isset($user_info['User'])?$user_info['User']['id']:0;
                        		if($data['email'] != ''&&(empty($user_info)||(isset($user_info['User'])&&trim($user_info['User']['email'])==''))){
                        			$user['user_sn'] = $data['email'];
                        			$user['email'] = $data['email'];
                        		}
                        		if($data['mobile'] != ''&&(empty($user_info)||(isset($user_info['User'])&&trim($user_info['User']['mobile'])==''))){
                        			$user['user_sn'] = $data['mobile'];
                        			$user['mobile'] = $data['mobile'];
                        		}else if($data['telephone'] != ''&&(empty($user_info)||(isset($user_info['User'])&&trim($user_info['User']['mobile'])==''))){
                        			$user['mobile'] = $data['telephone'];
                        		}
                        		if(empty($user_info)||(isset($user_info['User'])&&trim($user_info['User']['first_name'])=='')){
                        			$user['first_name'] = $data['consignee'];
                        		}
                        		if(empty($user_info)||(isset($user_info['User'])&&trim($user_info['User']['name'])=='')){
                        			$user['name'] = $data['consignee'];
                        		}
                        		if(empty($user_info))$data['password']=md5($this->configs['password-defult']);
                        		$this->User->save($user);
                        		$user_id=$this->User->id;
                        		
                        		$user_address = array();
                        		$user_address['id'] = 0;
                        		$user_address['user_id'] = $user_id;
                        		$user_address['consignee'] = $data['consignee'];
                        		if($data['email'] != ''){
                        			$user_address['email'] = $data['email'];
                        		}
                        		if($data['mobile'] != ''){
                        			$user_address['mobile'] = $data['mobile'];
                        		}
                        		$address_region=array();
                        		if($data['country']!='')$address_region[]=$data['country'];
                        		if($data['province']!='')$address_region[]=$data['province'];
                        		if($data['city']!='')$address_region[]=$data['city'];
                        		if(!empty($address_region)){
                        			$address_region_list = $this->RegionI18n->find('list', array('conditions' => array('RegionI18n.locale' => $this->backend_locale, 'RegionI18n.name' => $address_region), 'fields' => 'RegionI18n.region_id,RegionI18n.name'));
                        		}else{
                        			$address_region_list = array();
                        		}
                        		$user_address['country'] = array_search($data['country'],$address_region_list);
                        		$user_address['province'] = array_search($data['province'],$address_region_list);
                        		$user_address['city'] = array_search($data['city'],$address_region_list);
                        		$user_address['address'] = $data['address'];
                        		$this->UserAddress->save($user_address);
                        		$address_id = $this->UserAddress->id;
                        		$this->User->updateAll(array('User.address_id' => $address_id), array('User.id' => $user_id));
                        	}else{
                        		$user_id=$address['UserAddress']['user_id'];
                        		$address_id=$address['UserAddress']['id'];
                        	}
                	}
                	if(empty($user_id))continue;
                	$order_tmp=isset($upload_order_list[$address_id])?$upload_order_list[$address_id]:array();
                	if(empty($order_tmp)){
                		$order['id'] = 0;
                		$order['user_id'] = $user_id;
                		$order['order_code'] = $this->get_order_code();
                		$order['operator_id'] = $this->admin['id'];
                    	$order['order_locale'] = $this->backend_locale;
				if ($data['type_id'] != '') {
					if (isset($store_arr[$data['type_id']])) {
						$order['type_id'] = $store_arr[$data['type_id']];
					} else {
						$order['type_id'] = $data['type_id'];
					}
				}
				if (!empty($data['created'])) {
					$order['created'] = date("Y-m-d H:i:s",strtotime($data['created']));
				}
				$order['subtotal'] = $data['product_quntity'] * $data['product_price'];
                    	$order['total'] = $data['subtotal'];
				if ($data['payment_fee'] != '') {
					$order['payment_fee'] = $data['payment_fee'];
					$order['total'] += $data['payment_fee'];
				}
				$order['money_paid'] = floatval($data['money_paid']);
				$order['payment_name'] = $data['payment_name'];
				if (isset($data['sub_payment_name'])) {
					$order['sub_pay'] = isset($data['sub_payment_name']) ? $data['sub_payment_name'] : '';
				}
				$this->Payment->set_locale($this->backend_locale);
				$payment = $this->Payment->find('first', array('conditions' => array('PaymentI18n.name' => $data['payment_name']), 'fields' => 'Payment.id'));
				if (isset($payment['Payment']['id'])) {
					$order['payment_id'] = $payment['Payment']['id'];
				} else {
					$order['payment_id'] = '0';
				}
				$order['shipping_name'] = $data['shipping_name'];
				$this->Shipping->set_locale($this->backend_locale);
				$shipping = $this->Shipping->find('first', array('conditions' => array('ShippingI18n.name' => $data['shipping_name']), 'fields' => 'Shipping.id'));
				if (isset($shipping['Shipping']['id'])) {
					$order['shipping_id'] = $shipping['Shipping']['id'];
				} else {
					$order['shipping_id'] = '0';
				}
				if ($data['shipping_fee'] != '') {
					$order['shipping_fee'] = $data['shipping_fee'];
					$order['total'] += $data['shipping_fee'];
				}
				$order['consignee'] = $data['consignee'];
				$order['mobile'] = $data['mobile'];
				$order['note'] = $data['note'];
				$order['email'] = $data['email'];
				$order['country'] = $data['country'];
				$order['province'] = $data['province'];
				$order['city'] = $data['city'];
				$order['address'] = $data['address'];
				$order['telephone'] = $data['telephone'];
				$order['invoice_payee'] = $data['invoice_payee'];
				$order['invoice_type'] = $data['invoice_type'];
				$order['check_status'] = 0;
				$order['status'] = 1;
				$order_status=$data['status'];
				if ($order_status < '5') {
					$order['status'] = $order_status;
				}
				if ($order_status=='20') {
					$order['status'] = intval($order_status)-15;
				}
				if($order_status>'4'&&$order_status<='10'){
					$order['status'] = 1;
					$order['payment_status'] =intval($order_status)-5;
					$order['shipping_status'] = 0;
				}
				if($order_status>'9'&&$order_status<='20'){
					if((intval($order_status)-10)!=6){
						$order['status'] = 1;
						$order['payment_status'] =2;
						$order['shipping_status'] = intval($order_status)-10;
					}else{
						$order['status'] = 0;
						$order['payment_status'] =1;
						$order['shipping_status'] = 1;
					}
				}
				if($order_status=='25'){
					$order['status'] = 1;
					$order['payment_status'] = 0;
					$order['shipping_status'] = 2;
				}
				if($order_status=='26'){
					$order['status'] = 1;
					$order['payment_status'] = 0;
					$order['shipping_status'] = 6;
				}
	                	if(isset($order['shipping_status'])&&$order['shipping_status']>0){
	                		$order['check_status'] = 1;
	                	}
                	}else{
                		$order=$order_tmp;
				$order['subtotal'] += $data['product_quntity'] * $data['product_price'];
				$order['total'] += $data['product_quntity'] * $data['product_price'];
                	}
                	$this->Order->save($order);
                	$order_id=$this->Order->id;
                	$order['id']=$order_id;
                	$upload_order_list[$address_id]=$order;
			$order_product = array();
			$order_product['id'] = 0;
			$order_product['order_id'] = $order_id;
			$order_product['product_id'] = $order_product_info['Product']['id'];
			$order_product['product_number'] = isset($data['product_number'])?$data['product_number']:'';
			$order_product['product_code'] = $data['product_code'];
			$order_product['product_name'] = $data['product_name'];
			$order_product['product_quntity'] = $data['product_quntity'];
			$order_product['product_price'] = $data['product_price'];
			$order_product['purchase_price'] = isset($data['purchase_price'])?floatval($data['purchase_price']):0;
			$this->OrderProduct->save($order_product);
                	
			$order_info = $this->Order->findbyid($order_id);
			$user_id = $order_info['Order']['user_id'];
			$shipping_status = $order_info['Order']['shipping_status'];
			$order_status = $order_info['Order']['status'];
			$payment_status = $order_info['Order']['payment_status'];
			$operation_notes = '批量新增订单';
			$this->OrderAction->update_order_actions($order_id, $this->admin['id'], $user_id, $order_status, $payment_status, $shipping_status, $operation_notes);
			//下单
			if (($payment_status == 2 && isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 0) || (isset($this->configs['shop-frozen-quantity']) && $this->configs['shop-frozen-quantity'] == 1)) {
				if (!empty($order_product_info)) {
					$order_product_info['Product']['frozen_quantity'] = $order_product_info['Product']['frozen_quantity'] + $data['product_quntity'];
					$order_product_info['Product']['quantity'] = $order_product_info['Product']['quantity'] - $data['product_quntity'];
					//下架处理
					if ($order_product_info['Product']['quantity'] <= 0) {
						$order_product_info['Product']['forsale'] = 0;
					}
					$this->Product->save($order_product_info);
				}
			}
			//已付款 未发货的商品冻结材料处理
			if (($payment_status == 2 && isset($this->configs['shop-frozen-materials']) && $this->configs['shop-frozen-materials'] == 0) || (isset($this->configs['shop-frozen-materials']) && $this->configs['shop-frozen-materials'] == 1)) {
				if (!empty($order_product_info)) {
					//已付款 未发货的商品冻结材料处理
					//查询使用材料
					$pro_material = ClassRegistry::init('ProductMaterial')->find('all', array('conditions' => array('ProductMaterial.product_code' => $data['product_code'])));
					//减材料库存
					if (!empty($pro_material)) {
						foreach ($pro_material as $mk => $mv) {
							$material = ClassRegistry::init('Material');
							$material_info = $material->find('first', array('conditions' => array('Material.code' => $mv['ProductMaterial']['product_material_code'])));
							$order_material_product_data = array(
								'order_id' => $order_id,
								'order_product_id' => $order_product_id,
								'product_code' => $mv['ProductMaterial']['product_code'],
								'material_product_code' => $mv['ProductMaterial']['product_material_code'],
								'material_qty' => $mv['ProductMaterial']['quantity'],
							);
							ClassRegistry::init('OrderMaterialProduct')->saveAll($order_material_product_data);
							$material_info['Material']['frozen_quantity'] += $mv['ProductMaterial']['quantity'];
							$material_info['Material']['quantity'] = $material_info['Material']['quantity'] - $mv['ProductMaterial']['quantity'];
							$material->updateAll(array('Material.frozen_quantity' => $material_info['Material']['frozen_quantity'], 'Material.quantity' => $material_info['Material']['quantity']), array('Material.code' => $mv['ProductMaterial']['product_material_code']));
						}
					}
				}
			}
			//直接发货的
			if ($shipping_status == 1) {
				if (in_array('APP-WAREHOUSE', $this->apps['codes'])) {
					if (empty($data['warehouse_code']) && isset($apps['Applications']['APP-WAREHOUSE']['configs']['APP-WAREHOUSE-ALLOW-NO']) && $apps['Applications']['APP-WAREHOUSE']['configs']['APP-WAREHOUSE-ALLOW-NO'] == 0) {
						$msg = '已发货订单仓库代码不能为空';
					}
					if (!empty($data['warehouse_code']) && $data['product_code'] != '') {
						$outbound_infos[$data['warehouse_code']][$key]['OutboundProduct']['product_code'] = $data['product_code'];
						$outbound_infos[$data['warehouse_code']][$key]['OutboundProduct']['order_code'] = $order['order_code'];
						$outbound_infos[$data['warehouse_code']][$key]['OutboundProduct']['quantity'] = $data['product_quntity'];
						//查询该仓库该商品的库存
						$stock_info = $this->Stock->find('first', array('conditions' => array('Stock.warehouse_code' => $data['warehouse_code'], 'Stock.product_code' => $data['product_code']), 'fields' => 'Stock.quantity'));
						$outbound_infos[$data['warehouse_code']][$key]['OutboundProduct']['before_out'] = $stock_info['Stock']['quantity'];
					}
				}
			}
            }
            //出库操作
            if (isset($outbound_infos) && !empty($outbound_infos)) {
                	$this->outbound_action($outbound_infos);
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                	$this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'批量上传订单', $this->admin['id']);
            }
            if (!empty($product_codes)) {
                	$msg_codes = implode(',', $product_codes);
                	$msg = $msg_codes.' 货号不存在';
            } else {
                	$msg = $this->ld['import_success'];
            }
            echo "<meta charset='utf-8' /><script>alert('".$msg."');window.location.href='/admin/orders/uploadorders';</script>";
        }
        $this->redirect('/orders/');
    }

    public function download_csv_delivery()
    {
        Configure::write('debug', 1);
        $filename = '订单发货csv实例'.date('Ymd').'.csv';
        
        $newdatas=array();
        
        $flag_code = 'order_delivery';
        $this->Profile->set_locale($this->backend_locale);
        $this->ProfileFiled->set_locale($this->backend_locale);
        $profile_info = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
        if (!empty($profile_info)) {
	        	$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
                //pr($profilefiled_info);exit();
	        	$tmp=array();
	        	foreach($profilefiled_info as $vv)$tmp[]=$vv['ProfilesFieldI18n']['description'];
        		$newdatas[]=$tmp;
        		$joins=array(
				array(
					'table' => 'svoms_orders',
					'alias' => 'Order',
					'type' => 'left',
					'conditions' => array('Order.id = OrderShipment.order_id')
		        	)
            		);
        		$order_shipment_list=$this->OrderShipment->find('all',array('fields'=>'Order.order_code,OrderShipment.*','conditions'=>array('OrderShipment.logistics_company_id >'=>0,'OrderShipment.invoice_no <>'=>''),'limit'=>5,'order'=>'order_code','joins'=>$joins));
                $this->Region->set_locale($this->backend_locale);
                $regions = $this->Region->find('all',array('conditions'=>array()));
                $logisticscompany = $this->LogisticsCompany->find('all', array('conditions' => array()));
                //pr($regions);exit();
                // foreach ($regions as $key1 => $value1) {
                //     if($order_shipment_list['regions'] == $value1['RegionI18n']['id']){
                //         $order_shipment_list['regions'] = $value1['RegionI18n']['name'];
                //     }
                // }
                //pr($order_shipment_list);exit();
        		foreach($order_shipment_list as $v){
                    //pr($v);exit();
        			$shipment_data=array();
                    //pr($profilefiled_info);exit();
                    //pr($field_code);
                    foreach ($logisticscompany as $key11 => $value11) {
                        if($v['OrderShipment']['logistics_company_id'] == $value11['LogisticsCompany']['id']){
                        $v['OrderShipment']['logistics_company_id'] = $value11['LogisticsCompany']['code'];
                        }
                    }
                    //pr($v['OrderShipment']);
                    foreach ($regions as $key1 => $value1){

                        //pr($value1['RegionI18n']);
                    if($v['OrderShipment']['country'] == $value1['RegionI18n']['region_id']){
                        $v['OrderShipment']['country'] = $value1['RegionI18n']['name'];
                        }
               
                    if($v['OrderShipment']['province'] == $value1['RegionI18n']['region_id']){
                        $v['OrderShipment']['province'] = $value1['RegionI18n']['name'];
                        }
                  
                    if($v['OrderShipment']['city'] == $value1['RegionI18n']['region_id']){
                        $v['OrderShipment']['city'] = $value1['RegionI18n']['name'];
                        }
                    
                    if($v['OrderShipment']['district'] == $value1['RegionI18n']['region_id']){
                        $v['OrderShipment']['district'] = $value1['RegionI18n']['name'];
                        }
                    }
        			foreach($profilefiled_info as $vv){
        				$field_code=explode('.',$vv['ProfileFiled']['code']);
        				$shipment_data[]=isset($v[$field_code[0]][$field_code[1]])?$v[$field_code[0]][$field_code[1]]:'';
        			}
                    //pr($shipment_data);exit();
        			$newdatas[]=$shipment_data;
        		}
        }
        //pr($newdatas);exit();
        //pr($filename);exit();
        $this->Phpcsv->output($filename, $newdatas);
        exit();
    }

    public function download_csv_example()
    {
    	 Configure::write('debug', 1);
    	 $this->layout='ajax';
        $this->loadModel('ProfilesFieldI18n');
        $this->Profile->set_locale($this->backend_locale);
        $this->Profile->hasOne = array();
        $flag_code = 'order_import';
        $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => $flag_code, 'Profile.status' => 1)));
        $tmp = array();
        $fields_array = array();
        $newdatas = array();
        if (isset($profile_id) && !empty($profile_id)) {
            $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc'));
            foreach ($profilefiled_info as $k => $v) {
                $tmp[] = $v['ProfilesFieldI18n']['description'];
                $fields_array[] = $v['ProfileFiled']['code'];
            }
        }
        $newdatas[] = $tmp;
        $filename = '订单导出csv实例'.date('Ymd').'.csv';
        $this->Order->hasMany = array();
        $this->OrderProduct->hasOne = array();
        $this->Order->hasOne = array('OrderProduct' => array(
            'className' => 'OrderProduct',
            'order' => '',
            'dependent' => true,
            'foreignKey' => 'order_id',
        ));
        $order_conditions=array(
	        	'Order.status'=>'1',
	        	
	        	'Order.order_locale'=>$this->backend_locale,
	        	'OrderProduct.product_code <>'=>''
        );
        $order_all = $this->Order->find('all', array('fields' => $fields_array, 'conditions' => $order_conditions, 'limit' => 2));
        foreach ($order_all as $k => $v) {
            $user_tmp = array();
            foreach ($fields_array as $kk => $vv) {
                $fields_kk = explode('.', $vv);
                $user_tmp[] = isset($v[$fields_kk[0]][$fields_kk[1]]) ? $v[$fields_kk[0]][$fields_kk[1]] : '';
            }
            $newdatas[] = $user_tmp;
        }
        $this->Phpcsv->output($filename, $newdatas);
        exit();
    }

    public function fgetcsv_reg($handle, $length = null, $d = ',', $e = '"')
    {
        $d = preg_quote($d);
        $e = preg_quote($e);
        $_line = '';
        $eof = false;
        while ($eof != true) {
            $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
            $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
            if ($itemcnt % 2 == 0) {
                $eof = true;
            }
        }
        $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));
        $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
        preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
        $_csv_data = $_csv_matches[1];
        for ($_csv_i = 0; $_csv_i < count($_csv_data); ++$_csv_i) {
            $_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s', '$1', $_csv_data[$_csv_i]);
            $_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
        }
        return empty($_line) ? false : $_csv_data;
    }

    public function config()
    {
        $this->operator_privilege('configvalues_view');
        $this->menu_path = array('root' => '/oms/','sub' => '/orders/');
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['orders_search'],'url' => '/orders/');
        $this->navigations[] = array('name' => $this->ld['order'].$this->ld['set_up'],'url' => '');
        $this->set('title_for_layout', $this->ld['order'].$this->ld['set_up'].' - '.$this->configs['shop_name']);
        if ($this->RequestHandler->isPost()) {
            if (!empty($this->data)) {
                foreach ($this->data as $vv) {
                    $vv['value'] = isset($vv['value']) ? $vv['value'] : 0;
                    $data = $vv;
                    $this->ConfigI18n->saveAll($data);
                }
            }
            $this->redirect('/orders');
        }
        $resource_code = 'shopcart_set';
        $group_code = 'shopcart';
        $Resource_info = $this->Resource->find('first', array('conditions' => array('Resource.code' => $resource_code, 'Resource.status' => 1)));
        if (!empty($Resource_info)) {
            $resource_cond['Resource.parent_id'] = $Resource_info['Resource']['id'];
            $resource_cond['Resource.status'] = 1;
            $resource_cond['ResourceI18n.locale'] = $this->backend_locale;
            $Resource_list_info = $this->Resource->find('all', array('conditions' => $resource_cond, 'order' => 'orderby'));
            $resource_list = array();
            foreach ($Resource_list_info as $v) {
                $resource_list[$v['Resource']['code']] = $v['ResourceI18n']['name'];
            }
            $this->Config->hasOne = array();
            $this->Config->hasMany = array('ConfigI18n' => array('className' => 'ConfigI18n',
                'conditions' => '',
                'order' => '',
                'dependent' => true,
                'foreignKey' => 'config_id',
            ),
            );
            $conditions['Config.group_code'] = $group_code;
            $conditions['Config.status'] = 1;
            $conditions['Config.readonly'] = 0;
            $configs = $this->Config->find('all', array('conditions' => $conditions, 'order' => 'Config.orderby,Config.group_code'));
            $val = array();
            foreach ($configs as $k => $v) {
                $val['Config'] = $v['Config'];
                foreach ($v['ConfigI18n'] as $kk => $vv) {
                    if ($vv['locale'] == $this->backend_locale) {
                        $val['Config']['name'] = @$vv['name'];
                    }
                    $val['ConfigI18n'][$vv['locale']] = $vv;
                    if ($v['Config']['type'] == 'radio' || $v['Config']['type'] == 'checkbox' || $v['Config']['type'] == 'image') {
                        $val['ConfigI18n'][$vv['locale']]['options'] = explode("\n", $vv['options']);
                    }
                }
                $config_groups[$v['Config']['subgroup_code']][] = $val;
            }
            $this->set('resource_list', $resource_list);
            $this->set('config_groups', $config_groups);
        } else {
            $this->redirect('/orders');
        }
    }

    /**
     *保存AJAX提交过来的订单地址相关数据  2014/12/18.
     */
//	function amzui_order_address_data_save(){
//		$this->operator_privilege('orders_edit');
//		$order_id = $_REQUEST["order_id"];
//		if(isset($_REQUEST["order_user_id"]))
//		$order_user_id = $_REQUEST["order_user_id"];//用户id
//        if(isset($_REQUEST["type"])&&$_REQUEST["type"]=='user'){
//        	if(!empty($order_user_id)){
//				$user_info = $this->User->find('first',array('conditions'=>array('User.id'=>$order_user_id)));
//				$user_first_name=!empty($user_info["User"]["first_name"])?$user_info["User"]["first_name"]:'';
//			}
//			$order_data = array(
//				"id"=>$order_id,
//				"shipping_id"=>isset($_REQUEST["order_shipping_id"])?$_REQUEST["order_shipping_id"]:1,
//				"consignee"=>isset($user_first_name)?$user_first_name:'',
//				"user_id"=>$order_user_id
//			);
//			$this->Order->save(array("Order"=>$order_data));
//        }else{
//		//收货人信息
//		//pr($_REQUEST);die;
//		$order_telephone = $_REQUEST["order_telephone"];//电话
//		$order_consignee = $_REQUEST["order_consignee"];//收货人
//		$order_mobile = $_REQUEST["order_mobile"];//手机
//		$regions=$_REQUEST["order_country"].' ';
//		$regions=$regions.$_REQUEST["order_province"].' ';
//		$regions=$regions.$_REQUEST["order_city"];
//		$order_country = $_REQUEST["order_country"];//国家
//		$order_province = $_REQUEST["order_province"];//省
//		$order_city = $_REQUEST["order_city"];//市
//		$order_district = $_REQUEST["order_district"];//区
//		$order_sign_building = $_REQUEST["order_sign_building"];//标致性建筑
//		$order_address = $_REQUEST["order_address"];//地址
//		$order_best_time = $_REQUEST["order_best_time"];//最佳送货时间
//		$order_zipcode = $_REQUEST["order_zipcode"];//邮编
//		$order_note = $_REQUEST["order_note"];//备注  卖家留言
//		$order_postscript = $_REQUEST["order_postscript"];//备注  客户给商家留言
//		$order_email = $_REQUEST["order_email"];//电子邮件
//		$order_shipping_id = $_REQUEST["order_shipping_id"];//配送方式
//		$order_type = $_REQUEST["order_type"];//订单来源
//		$type=explode(':',$order_type);
//		$order_type = !empty($type[0])?$type[0]:$_REQUEST["order_type"];
//		$order_type_id = !empty($type[1])?$type[1]:$_REQUEST["order_type"];
//		$order_info=$this->Order->find('first',array('conditions'=>array('Order.id'=>$order_id),'fields'=>"Order.id,Order.operator_code,Order.order_code,Order.user_id,Order.shipping_status,Order.status,Order.payment_status"));
//		$operator_code=$order_info['Order']['operator_code'].";1";
//		if(isset($_REQUEST["order_user_id"])){
//			$order_data = array(
//				"id"=>$order_id,
//				"user_id"=>$order_user_id,
//				"type"=>$order_type,
//				"type_id"=>$order_type_id,
//				"shipping_id"=>$order_shipping_id,
//				"consignee"=>$order_consignee,
//				"mobile"=>$order_mobile,
//				"telephone"=>$order_telephone,
//				"operator_id"=>$this->admin['id'],
//				"regions"=>$regions,
//				"country"=>$order_country,
//				"province"=>$order_province,
//				"city"=>$order_city,
//				"district"=>$order_district,
//				"sign_building"=>$order_sign_building,
//				"address"=>$order_address,
//				"best_time"=>$order_best_time,
//				"zipcode"=>$order_zipcode,
//				"note"=>$order_note,
//				"postscript"=>$order_postscript,
//				"email"=>$order_email,
//				'operator_code' =>$operator_code
//			);
//		}else{
//			$order_data = array(
//			"id"=>$order_id,
//			"type"=>$order_type,
//			"type_id"=>$order_type_id,
//			"shipping_id"=>$order_shipping_id,
//			"consignee"=>$order_consignee,
//			"mobile"=>$order_mobile,
//			"telephone"=>$order_telephone,
//			"operator_id"=>$this->admin['id'],
//			"regions"=>$regions,
//			"country"=>$order_country,
//			"province"=>$order_province,
//			"city"=>$order_city,
//			"district"=>$order_district,
//			"sign_building"=>$order_sign_building,
//			"address"=>$order_address,
//			"best_time"=>$order_best_time,
//			"zipcode"=>$order_zipcode,
//			"note"=>$order_note,
//			"postscript"=>$order_postscript,
//			"email"=>$order_email,
//			'operator_code' =>$operator_code
//			);
//		}
////		if(isset($_REQUEST["order_user_id"])&&$_REQUEST["order_user_id"]!=""){
////			$OrderAction['order_id'] = $order_id;
////			$OrderAction['user_id'] = $_REQUEST["order_user_id"];
////			$this->OrderAction->saveAll($OrderAction);
////		}
//		$this->Order->save(array("Order"=>$order_data));
//		//OrderAction
//		$user_id=$order_info['Order']['user_id'];
//		$shipping_status=$order_info['Order']['shipping_status'];
//		$order_status=$order_info['Order']['status'];
//		$payment_status=$order_info['Order']['payment_status'];
//		$operation_notes="编辑订单地址信息";
//		$this->OrderAction->update_order_actions($order_id,$this->admin['id'],$user_id,$order_status,$payment_status,$shipping_status,$operation_notes);
////	pr($order_data);
//		if($this->Order->save(array("Order"=>$order_data)))
//		{
//			//操作员日志
//			if( $this->configs['operactions-log'] == 1){
//				$this->OperatorLog->log(date("H:i:s").' '.$this->ld['operator'].' '.$this->admin['name'].' '.'编辑了订单'.'.'.$order_info['Order']['order_code'].' '.'的地址'.'.',$this->admin['id']);
//			}
//
//		}
//
//		//判断地址薄是否存在
//		if(isset($order_user_id)&&$order_user_id!=""&&$order_address!=""){
//			$addressInfo=$this->UserAddress->find('first',array('conditions'=>array('UserAddress.user_id'=>$order_user_id,'UserAddress.address'=>$order_address)));
//			//为空 新增
//			if(empty($addressInfo)){
//            	$order_country_id='';
//            	$order_province_id='';
//            	$order_city_id='';
//            	$country=$this->RegionI18n->find('first',array('conditions'=>array('RegionI18n.locale'=>$this->locale,'RegionI18n.name'=>$order_country),'fields'=>'RegionI18n.region_id'));
//            	if(!empty($country)){
//            		$order_country_id=$country['RegionI18n']['region_id'];
//            	}
//            	$province=$this->RegionI18n->find('first',array('conditions'=>array('RegionI18n.locale'=>$this->locale,'RegionI18n.name'=>$order_province),'fields'=>'RegionI18n.region_id'));
//            	if(!empty($province)){
//            		$order_province_id=$province['RegionI18n']['region_id'];
//            	}
//            	$city=$this->RegionI18n->find('first',array('conditions'=>array('RegionI18n.locale'=>$this->locale,'RegionI18n.name'=>$order_city),'fields'=>'RegionI18n.region_id'));
//            	if(!empty($city)){
//            		$order_city_id=$city['RegionI18n']['region_id'];
//            	}
//		    	$user_address['user_id']=$_REQUEST["order_user_id"];
//		        $user_address['consignee']=$order_consignee;
//		        $user_address['email']=$order_email;
//		        $user_address['mobile']=$order_mobile;
//		        $user_address['telephone']=$order_telephone;
//		        $user_address['country']=$order_country_id;
//		        $user_address['province']=$order_province_id;
//		        $user_address['city']=$order_city_id;
//		        $user_address['regions']=$order_country_id.' '.$order_province_id.' '.$order_city_id;
//		        $user_address['address']=$order_address;
//		        $user_address['best_time']=$order_best_time;
//		        $user_address['sign_building']=$order_sign_building;
//		        $user_address['zipcode']=$order_zipcode;
//		        $this->UserAddress->saveAll($user_address);
//		        $aId=$this->UserAddress->id;
//		        $this->User->updateAll(array('User.address_id' =>$aId),array('User.id' =>$_REQUEST["order_user_id"]));
//			}
//		}
//		//如果购买者为空 收货人不为空 自动将收货人新增为会员
//		if((!isset($_REQUEST["order_user_id"])||$_REQUEST["order_user_id"]=="")&&($order_mobile!=""||$order_email!="")&&$order_consignee!=""){
// 			$user=array();
//            $uId="";
//            $aId="";
//            $user['name']=$order_mobile;
//            $user['first_name']=$order_consignee;
//            if($order_mobile!=""){
//                $user['user_sn']=$order_mobile;
//            }else{
//            	$user['user_sn']=$order_email;
//            }
//            if($order_email==""){
//            	$order_email=$order_mobile.'@139.com';
//            }
//			if($order_email!=""){
//           	$conditions['or']['User.email']=$order_email;
//            }
//            //判断用户是否存在
//            $conditions['or']['User.user_sn']=$user['user_sn'];
//            $info=$this->User->find('first',array('conditions'=>$conditions));
//            if(!empty($info)){
//              	$user['id']=$info['User']['id'];
//             	$uId=$info['User']['id'];
//              	$note=$info['User']['admin_note'];
//            }
//            $user['password']=md5('123456');
//            $user['email']=$order_email;
//            $user['mobile']=$order_mobile;
//            $user['sex']=0;
//            $this->User->saveAll($user);
//            if(empty($info)){
//               	$uId=$this->User->id;
//               	$oId=$this->Order->id;
//               	$order['Order']['id']=$oId;
//               	$order['Order']['user_id']=$uId;
//               	$this->Order->save($order);
//            }
//            $user_address=array();
//            if(($order_country!=""||$order_province!=""||$order_city!=""||$order_address!="")){
//            	//获取区域ID
//            	$order_country_id='';
//            	$order_province_id='';
//            	$order_city_id='';
//            	$country=$this->RegionI18n->find('first',array('conditions'=>array('RegionI18n.locale'=>$this->locale,'RegionI18n.name'=>$order_country),'fields'=>'RegionI18n.region_id'));
//            	if(!empty($country)){
//            		$order_country_id=$country['RegionI18n']['region_id'];
//            	}
//            	$province=$this->RegionI18n->find('first',array('conditions'=>array('RegionI18n.locale'=>$this->locale,'RegionI18n.name'=>$order_province),'fields'=>'RegionI18n.region_id'));
//            	if(!empty($province)){
//            		$order_province_id=$province['RegionI18n']['region_id'];
//            	}
//            	$city=$this->RegionI18n->find('first',array('conditions'=>array('RegionI18n.locale'=>$this->locale,'RegionI18n.name'=>$order_city),'fields'=>'RegionI18n.region_id'));
//            	if(!empty($city)){
//            		$order_city_id=$city['RegionI18n']['region_id'];
//            	}
//	            // 判断地址是否存在
//	            $add_conditions['UserAddress.country']=$order_country_id;
//	            $add_conditions['UserAddress.province']=$order_province_id;
//	            $add_conditions['UserAddress.city']=$order_city_id;
//	            $add_conditions['UserAddress.address']=$order_address;
//	            $addInfo=$this->UserAddress->find('first',array('conditions'=>$add_conditions));
//	            if(empty($addInfo)){
//		            $user_address['user_id']=$uId;
//		            $user_address['consignee']=$order_consignee;
//		            $user_address['email']=$order_email;
//		            $user_address['mobile']=$order_mobile;
//		            $user_address['telephone']=$order_telephone;
//		            $user_address['country']=$order_country_id;
//		            $user_address['province']=$order_province_id;
//		            $user_address['city']=$order_city_id;
//		            $user_address['address']=$order_address;
//		        	$user_address['regions']=$order_country_id.' '.$order_province_id.' '.$order_city_id;
//		            $user_address['best_time']=$order_best_time;
//		            $user_address['sign_building']=$order_sign_building;
//		          	$user_address['zipcode']=$order_zipcode;
//		          	$this->UserAddress->saveAll($user_address);
//		          	$aId=$this->UserAddress->id;
//		          	$this->User->updateAll(array('User.address_id' =>$aId),array('User.id' =>$uId));
//	          	}
//          	}
//		}
//		}
//		$order_info=$this->Order->find('first',array('conditions'=>array('Order.id'=>$order_id)));
//		if(isset($order_info['Order']['user_id'])&&$order_info['Order']['user_id']!=""){
//			$user_info = $this->User->find('first',array('conditions'=>array('User.id'=>$order_info["Order"]["user_id"])));
//			if(!empty($user_info)&&!empty($user_info['User']['admin_note2'])){
//				$discount=$user_info['User']['admin_note2'];
//				$this->set('discount',$discount);
//			}
//		}
//		$this->set("order_info",$order_info);
//		$user_addresses_array="";
//		if(isset($_REQUEST["order_user_id"])&&$_REQUEST["order_user_id"]!=""){
//			$user_addresses_array = $this->UserAddress->user_addresses_get($_REQUEST["order_user_id"]);
//		}
//		$this->set('user_addresses_json',json_encode($user_addresses_array));
//		$this->set('user_addresses_array',$user_addresses_array);
//		//配送方式
//		$shipping_effective_list = $this->Shipping->shipping_effective_list_beta($this->locale);
//		$this->set("shipping_effective_list",$shipping_effective_list);
//		$regions_info = $this->Region->find("all");
//		foreach($regions_info as $k=>$v){
//			if($v['Region']['id']==$order_info['Order']['country']){
//				$order_info['Order']['country2']=$v['RegionI18n']['name'];
//			}
//			if($v['Region']['id']==$order_info['Order']['province']){
//				$order_info['Order']['province2']=$v['RegionI18n']['name'];
//			}
//			if($v['Region']['id']==$order_info['Order']['city']){
//				$order_info['Order']['city2']=$v['RegionI18n']['name'];
//			}
//		}
//		$regions_info2=array();
//		foreach($regions_info as $k=>$v){
//			$regions_info2[$v['Region']['id']]=$v['RegionI18n']['name'];
//		}
//		if(count($user_addresses_array)==1){
//			foreach( $user_addresses_array as $k=>$v){
//				if($k==0){
//					$order_info['Order']['country']=isset($regions_info2[$v["UserAddress"]["country"]])?$regions_info2[$v["UserAddress"]["country"]]:'';
//					$order_info['Order']['province']=isset($regions_info2[$v["UserAddress"]["province"]])?$regions_info2[$v["UserAddress"]["province"]]:'';
//					$order_info['Order']['city']=isset($regions_info2[$v["UserAddress"]["city"]])?$regions_info2[$v["UserAddress"]["city"]]:'';
//					$order_info['Order']['district']=$v["UserAddress"]["district"];
//					$order_info['Order']['address']=$v["UserAddress"]["address"];
//					$order_info['Order']['zipcode']=$v["UserAddress"]["zipcode"];
//					$this->Order->save(array("Order"=>$order_info['Order']));
//				}
//			}
//			$this->set("order_info",$order_info);
//		}
//		$this->set('regions_info3',$regions_info2);
//		$information_resources_info = $this->InformationResource->information_formated(array("how_oos","best_time"),$this->locale);
//		$this->set('information_resources_info',$information_resources_info);
//		//订单来源
//		$this->Orderfrom->get($this,1);
//		if(in_array('APP-DEALER',$this->apps['codes'])){
//			$dealers=$this->get_dealer_id();
//			$this->set("dealers",$dealers);
//		}
//		$this->layout = "ajax";
//		$this->render('order_address_data_save');
//	}

    /*
        订单商品属性修改
        $action_code 操作类型
            page_show:初始加载页面
            set_default_attr_value:获取规格默认值
            data_save:数据保存
    */
    public function update_order_product_attr($action_code = 'page_show')
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        //if($this->RequestHandler->isPost()){
        $this->set('action_code', $action_code);
        $this->loadModel('Attribute');
        $this->loadModel('AttributeOption');
        $this->loadModel('SkuProduct');
        $this->loadModel('ProductAttribute');
        $this->loadModel('ProductTypeAttribute');
        $this->loadModel('StyleTypeGroup');
        $this->loadModel('StyleTypeGroupAttributeValue');
        $this->loadModel('UserStyle');
        $this->loadModel('UserStyleValue');
        $this->Product->set_locale($this->backend_locale);
        $this->ProductStyle->set_locale($this->backend_locale);
        $this->ProductType->set_locale($this->backend_locale);
        $this->Attribute->set_locale($this->backend_locale);
        if ($action_code == 'page_show' || $action_code == 'attr_view') {
            $order_id = isset($_POST['order_id']) ? $_POST['order_id'] : 0;
            $pro_id = isset($_POST['pro_id']) ? $_POST['pro_id'] : 0;
            $pro_code = isset($_POST['pro_code']) ? $_POST['pro_code'] : '';
            $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0;
            $product_style_id = 0;
            $order_product_id = isset($_POST['order_product_id']) ? $_POST['order_product_id'] : 0;
            $media_list = $this->OrderProductMedia->find('all',array('conditions'=>array('OrderProductMedia.order_product_id'=>$order_product_id,'OrderProductMedia.media_group'=>0)));
            $this->set('media_list', $media_list);
            $media_condition_list = $this->OrderProductMedia->find('all',array('conditions'=>array('OrderProductMedia.order_product_id'=>$order_product_id,'OrderProductMedia.media_group'=>1)));
            $this->set('media_condition_list', $media_condition_list);
			$media_primary_list = $this->OrderProductMedia->find('first',array('conditions'=>array('OrderProductMedia.order_product_id'=>$order_product_id,'OrderProductMedia.media_group'=>2)));
            $this->set('media_primary_list', $media_primary_list);
            $pro_Info = $this->Product->find('first', array('conditions' => array('Product.code' => $pro_code)));
            if (!empty($pro_Info)) {
                //订单商品信息
                $order_pro_info = $this->OrderProduct->find('first', array('conditions' => array('OrderProduct.order_id' => $order_id, 'OrderProduct.product_code' => $pro_code, 'OrderProduct.id' => $order_product_id)));
                if (!empty($order_pro_info['OrderProduct'])) {
                     	$order_info=$order_pro_info['Order'];
	                    $this->set('order_info', $order_info);
	                    $order_product_id = $order_pro_info['OrderProduct']['id'];
	                    $product_style_id = $order_pro_info['OrderProduct']['product_style_id'];
	                    $order_pro_info = $order_pro_info['OrderProduct'];
	                    $this->set('order_pro_info', $order_pro_info);
	                    $orderproinfo = $this->Product->find('first', array('fields' => array('Product.id', 'Product.code'), 'conditions' => array('Product.code' => $order_pro_info['product_code'])));
	                    if (!empty($orderproinfo)) {
	                        $order_product_attr_info = $this->ProductAttribute->find('list', array('fields' => array('ProductAttribute.attribute_id', 'ProductAttribute.attribute_value'), 'conditions' => array('ProductAttribute.product_id' => $orderproinfo['Product']['id'])));
	                        $this->set('order_product_attr_info', $order_product_attr_info);
	                    }
	                    $orderproduct_user_style_id = $order_pro_info['user_style_id'];
	                    if ($orderproduct_user_style_id != 0) {
	                        	$orderproduct_user_style_info = $this->UserStyle->find('first', array('conditions' => array('UserStyle.id' => $orderproduct_user_style_id)));
	                        	$this->set('orderproduct_user_style_info', $orderproduct_user_style_info);
	                    }
	                    if(!empty($order_info['user_id'])){
	                    	$user_info=$this->User->findById($order_info['user_id']);
	                    	$this->set('user_info',$user_info);
	                    }
	                    
                }
                
                $info_resource_info=$this->InformationResource->information_formated('clothes_location',$this->backend_locale,false);
                $this->set('info_resource_info',$info_resource_info);
                
                $resource_info = $this->Resource->getformatcode(array('order_product_service_type'), $this->backend_locale);
                $this->set('resource_info',$resource_info);
                $pro_ids = $pro_Info['Product']['id'];
                //商品属性组信息
                $pro_type_info = $this->ProductType->find('first', array('conditions' => array('ProductType.id' => $pro_Info['Product']['product_type_id'])));
                $is_customize = false;//当前商品属于定制
                if ($pro_type_info['ProductType']['customize'] == '1') {
                    	$is_customize = true;
                }
                //属性列表
                $attr_ids = $this->ProductTypeAttribute->getattrids($pro_Info['Product']['product_type_id']);
                $pro_type_attr_info = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $attr_ids, 'Attribute.status' => 1), 'order' => 'Attribute.id'));
                //记录购买、定制属性信息
                $pro_type_attr_list = array();
                $pro_type_attr_defaultvalue = array();
                $buy_attr = array();//购买属性
                $buy_attr_ids = array();//购买属性Id
                $attr_select_ids = array();
                $attr_select_list = array();//可选属性下拉选项
                $userstyletype_attribute_code = array();//用户可用模板规格
                $multiple_customize=array();
                foreach ($pro_type_attr_info as $v) {
	                    if ($v['Attribute']['type'] == 'buy') {
	                        	$buy_attr_ids[] = $v['Attribute']['id'];
	                        	$buy_attr[$v['Attribute']['id']] = $v['AttributeI18n']['name'];
	                    }
	                    if ($v['Attribute']['type'] == 'customize'){
	                        	$pro_type_attr_list[$v['Attribute']['id']] = $v['AttributeI18n']['name'];
	                    }else if($v['Attribute']['type'] == 'multiple_customize'){
	                    	$multiple_customize[] = $v;
	                    }
	                    if ($v['Attribute']['attr_type'] == 1 && $v['Attribute']['attr_input_type'] == 1) {
	                        	$attr_select_ids[] = $v['Attribute']['id'];
	                    }
	                    $pro_type_attr_defaultvalue[$v['Attribute']['id']] = $v['AttributeI18n']['default_value'];
                }
                $this->set('buy_attr', $buy_attr);
                $this->set('multiple_customize', $multiple_customize);
                $attr_select_list = $this->AttributeOption->getattroption($attr_select_ids, $this->backend_locale);
                $att_sel_list = $this->get_order_pro_code($pro_id, $pro_code, $pro_Info['Product']['product_type_id']);
                $this->set('att_sel_list', $att_sel_list);
                if ($this->SkuProduct->check_sku_pro($pro_code)) {
                    $pro_ids = $this->SkuProduct->get_sku_pro_ids($pro_code);
                } elseif ($pro_code != '') {
                    $sku_pro_codelists = $this->SkuProduct->find('list', array('fields' => array('SkuProduct.product_code'), 'conditions' => array('SkuProduct.sku_product_code' => $pro_code)));
                    $pro_ids = $this->SkuProduct->get_sku_pro_ids($sku_pro_codelists);
                }
                //查询当前商品属性（购买属性）
                $pro_attr = $this->ProductAttribute->find('all', array('conditions' => array('ProductAttribute.attribute_id' => $buy_attr_ids, 'ProductAttribute.product_id' => $pro_ids, 'ProductAttribute.locale' => $this->backend_locale)));
                $pro_attr_list = array();
                foreach ($pro_attr as $v) {
                    $pro_attr_list[$v['ProductAttribute']['attribute_value']] = $v['ProductAttribute']['attribute_value'];
                }
                if (empty($pro_attr_list)) {
//                    echo $this->ld['not_product_attributes'];
//                    die();
                }
                //用户版型列表
                $user_style_list = $this->UserStyle->find('all', array('conditions' => array('UserStyle.user_id' => $user_id, 'UserStyle.type_id' => $pro_Info['Product']['product_type_id'], 'UserStyle.attribute_code' => $pro_attr_list)));
                $this->set('user_style_list', $user_style_list);
                if ($is_customize) {
                    //商品属于可定制
                    //规格信息
                    $styletypegroupinfo = $this->StyleTypeGroup->getstyletypegrouplist($pro_Info['Product']['product_type_id'], $pro_attr_list);
                    $styletypegroupids = array();//规格Ids
                    $style_ids = array();//版型Ids
                    $styletypegrouplist = array();
                    if (!empty($styletypegroupinfo)) {
                        foreach ($styletypegroupinfo as $k => $v) {
                            $styletypegroupids[] = $v['StyleTypeGroup']['id'];
                            $style_ids[] = $v['StyleTypeGroup']['style_id'];
                            $styletypegrouplist[$v['StyleTypeGroup']['id']] = $v['StyleTypeGroup']['group_name'];
                        }
                    }
                    $style_type_group_id = isset($styletypegroupids[0]) ? $styletypegroupids[0] : 0;
                    //自定义属性版型规格尺寸列表
                    $attrvaluelist = array();
                    $pro_type_attr_type_list = array();//自定义属性修改可选值列表
                    $attrvalueInfo = $this->StyleTypeGroupAttributeValue->getattrvaluelist($product_style_id, $pro_Info['Product']['product_type_id'], $style_type_group_id);
                    foreach ($attrvalueInfo as $v) {
                        $attrvaluelist[$v['StyleTypeGroupAttributeValue']['attribute_id']] = $v['StyleTypeGroupAttributeValue']['default_value'];
                        if (trim($v['StyleTypeGroupAttributeValue']['select_value']) != '') {
                            $pro_type_attr_type_list[$v['StyleTypeGroupAttributeValue']['attribute_id']] = split("\r\n", $v['StyleTypeGroupAttributeValue']['select_value']);
                        }
                    }
                    $userstyletype_attribute_code = $styletypegrouplist;
                    $this->set('pro_type_info', $pro_type_info);
                    $this->set('styletypegrouplist', $styletypegrouplist);
                    $this->set('attrvaluelist', $attrvaluelist);
                    $this->set('pro_type_attr_type_list', $pro_type_attr_type_list);
                }
                $order_product_value_cond = array(
                    'order_id' => $order_id,
                    'order_product_id' => $order_product_id,
                );
                $order_product_value_data = $this->OrderProductValue->find('all', array('fields' => array('OrderProductValue.attribute_id', 'OrderProductValue.attribute_value','OrderProductValue.attr_price'), 'conditions' => $order_product_value_cond));
                $this->set('order_product_value_data', $order_product_value_data);
                $this->set('is_customize', $is_customize);
                $this->set('pro_type_attr_list', $pro_type_attr_list);//所有属性
                $this->set('pro_type_attr_defaultvalue', $pro_type_attr_defaultvalue);//所有属性默认值
                $this->set('attr_select_list', $attr_select_list);//可选属性下拉
                $this->set('order_id', $order_id);
                $this->set('user_id', $user_id);
                $this->set('pro_Id', $pro_id);
                $this->set('pro_Info', $pro_Info);
                $this->set('order_product_id', $order_product_id);
            } else {
                echo '该商品未找到';
                die();
            }
        } elseif ($action_code == 'get_order_pro_code') {
            $attr_ids = isset($_POST['attr_ids']) ? $_POST['attr_ids'] : array();
            $attr_values = isset($_POST['attr_values']) ? $_POST['attr_values'] : array();
            $order_product_code = isset($_POST['order_product_code']) ? $_POST['order_product_code'] : '';
            $product_style_id = isset($_POST['product_style_id']) ? $_POST['product_style_id'] : 0;
            $product_type_id = isset($_POST['product_type_id']) ? $_POST['product_type_id'] : 0;
            $attr_value_info = array();
            foreach ($attr_ids as $v) {
                $attr_value_info[$v] = isset($attr_values[$v]) ? $attr_values[$v] : '';
            }
            if (!empty($attr_value_info)) {
                $pro_code_info = array();
                $conditions = '';
                if ($this->SkuProduct->check_sku_pro($order_product_code)) {
                    $pro_ids = $this->SkuProduct->get_sku_pro_ids($order_product_code);
                    $conditions[]['and']['ProductAttribute.product_id'] = $pro_ids;
                } elseif ($order_product_code != '') {
                    $sku_pro_codelists = $this->SkuProduct->find('list', array('fields' => array('SkuProduct.product_code'), 'conditions' => array('SkuProduct.sku_product_code' => $order_product_code)));
                    $pro_ids = $this->SkuProduct->get_sku_pro_ids($sku_pro_codelists);
                    $conditions[]['and']['ProductAttribute.product_id'] = $pro_ids;
                }
                foreach ($attr_value_info as $k => $v) {
                    $conditions[]['and']['ProductAttribute.attribute_id'] = $k;
                    $conditions[]['and']['ProductAttribute.attribute_value'] = $v;
                }
                $attr_info = $this->ProductAttribute->find('first', array('conditions' => $conditions));
                if (!empty($attr_info)) {
                    $result['code'] = 1;
                    $pro_code_info = $this->Product->find('first', array('conditions' => array('Product.id' => $attr_info['ProductAttribute']['product_id'])));
                    $style_type_group_cond['StyleTypeGroup.type_id'] = $product_type_id;
                    $style_type_group_cond['StyleTypeGroup.group_name'] = $attr_value_info;
                    $product_style_ids = $this->StyleTypeGroup->find('list', array('fields' => array('StyleTypeGroup.style_id'), 'conditions' => $style_type_group_cond));
                    $product_style_infos = $this->ProductStyle->find('all', array('fields' => array('ProductStyle.id', 'ProductStyleI18n.style_name'), 'conditions' => array('ProductStyle.id' => $product_style_ids, 'ProductStyle.status' => 1)));
                    if (!empty($product_style_infos)) {
                        $result['product_style_infos'] = $product_style_infos;
                    }
                }
                $result['pro_code_info'] = $pro_code_info;
            } else {
                $result['code'] = 0;
                $result['msg'] = '未找到属性';
            }
            die(json_encode($result));
        } elseif ($action_code == 'get_pro_style') {
            $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : 0;
            $user_style_id = isset($_POST['user_style_id']) ? $_POST['user_style_id'] : 0;
            //用户版型信息
            $user_style_info = $this->UserStyle->find('first', array('conditions' => array('UserStyle.user_id' => $user_id, 'UserStyle.id' => $user_style_id)));
            if (!empty($user_style_info['UserStyle'])) {
                $result['code'] = 1;
                $result['data'] = $user_style_info;
            } else {
                $result['code'] = 0;
                $result['data'] = array();
            }
            die(json_encode($result));
        } elseif ($action_code == 'product_style_change') {
            $page_action = isset($_REQUEST['page_action']) ? $_REQUEST['page_action'] : 'page_show';
            $this->set('page_action', $page_action);
            $order_id = isset($_POST['order_id']) ? $_POST['order_id'] : 0;
            $order_product_id = isset($_POST['order_product_id']) ? $_POST['order_product_id'] : 0;
            $attr_values = isset($_POST['attr_values']) ? $_POST['attr_values'] : array();
            $product_style_id = isset($_POST['product_style_id']) ? $_POST['product_style_id'] : 0;
            $product_type_id = isset($_POST['product_type_id']) ? $_POST['product_type_id'] : 0;
            $attr_value_info = array();
            foreach ($attr_values as $v) {
                if (empty($v)) {
                    continue;
                }
                $attr_value_info[] = $v;
            }
            if (!empty($attr_value_info)) {
                $cond['StyleTypeGroup.group_name'] = $attr_value_info;
            }
            $cond['StyleTypeGroup.style_id'] = $product_style_id;
            $cond['StyleTypeGroup.type_id'] = $product_type_id;
            $style_type_group_info = $this->StyleTypeGroup->find('first', array('conditions' => $cond));
            if (!empty($style_type_group_info)) {
                $style_type_group_id = $style_type_group_info['StyleTypeGroup']['id'];
                $style_attr_ids = array();
                $attrvaluelist = array();
                $pro_type_attr_type_list = array();
                $attrids = $this->ProductTypeAttribute->getattrids($product_type_id);
                $attrvalueInfo = $this->StyleTypeGroupAttributeValue->getattrvaluelist($product_style_id, $product_type_id, $style_type_group_id);
                foreach ($attrvalueInfo as $k => $v) {
                    if (!in_array($v['StyleTypeGroupAttributeValue']['attribute_id'], $attrids)) {
                        unset($attrvalueInfo[$k]);
                        continue;
                    }
                    $style_attr_ids[$v['StyleTypeGroupAttributeValue']['attribute_id']] = $v['StyleTypeGroupAttributeValue']['attribute_id'];
                    $attrvaluelist[$v['StyleTypeGroupAttributeValue']['attribute_id']] = $v['StyleTypeGroupAttributeValue']['default_value'];
                    if (trim($v['StyleTypeGroupAttributeValue']['select_value']) != '') {
                        $pro_type_attr_type_list[$v['StyleTypeGroupAttributeValue']['attribute_id']] = split("\r\n", $v['StyleTypeGroupAttributeValue']['select_value']);
                    }
                }
                $attr_values = array();
                $attr_infos = array();
                $attr_select_list = array();
                $attr_value_infos = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $attrids, 'Attribute.status' => 1), 'fields' => array('Attribute.id', 'Attribute.type', 'Attribute.code', 'AttributeI18n.name', 'AttributeI18n.default_value')));
                foreach ($attr_value_infos as $k => $v) {
                    if ($v['Attribute']['type'] == 'buy') {
                        continue;
                    }
                    $attr_values[$v['Attribute']['id']] = $v['AttributeI18n']['default_value'];
                    $attr_infos[$v['Attribute']['id']] = $v['AttributeI18n']['name'];
                    if (!in_array($v['Attribute']['id'], $style_attr_ids)) {
                        $style_attr_info = array();
                        $style_attr_info['StyleTypeGroupAttributeValue']['id'] = 0;
                        $style_attr_info['StyleTypeGroupAttributeValue']['attribute_id'] = $v['Attribute']['id'];
                        $style_attr_info['StyleTypeGroupAttributeValue']['attribute_code'] = $v['Attribute']['code'];
                        $style_attr_info['StyleTypeGroupAttributeValue']['default_value'] = $v['AttributeI18n']['default_value'];
                        $attrvalueInfo[] = $style_attr_info;
                    }
                    $style_attr_option = array();
                    foreach ($v['AttributeOption'] as $kk => $vv) {
                        $style_attr_option[$vv['option_value']] = $vv['option_name'];
                    }
                    $attr_select_list[$v['Attribute']['id']] = $style_attr_option;
                }
                $this->set('attrvalueInfo', $attrvalueInfo);
                $this->set('attr_infos', $attr_infos);
                $this->set('attr_values', $attr_values);
                $this->set('attrvaluelist', $attrvaluelist);
                $this->set('pro_type_attr_type_list', $pro_type_attr_type_list);
                $this->set('attr_select_list', $attr_select_list);
                $order_product_value_cond = array(
                    'order_id' => $order_id,
                    'order_product_id' => $order_product_id,
                );
                $order_product_value_data = $this->OrderProductValue->find('list', array('fields' => array('OrderProductValue.attribute_id', 'OrderProductValue.attribute_value'), 'conditions' => $order_product_value_cond));
                $this->set('order_product_value_data', $order_product_value_data);
                $user_style_id = isset($_POST['user_style_id']) ? $_POST['user_style_id'] : 0;
                $user_style_value_data = $this->UserStyleValue->find('all', array('conditions' => array('UserStyleValue.user_style_id' => $user_style_id)));
                $user_style_value_data_list = array();
                foreach ($user_style_value_data as $v) {
                    $user_style_value_data_list[$v['UserStyleValue']['attribute_id']] = $v['UserStyleValue']['attribute_value'];
                }
                $this->set('user_style_value_data_list', $user_style_value_data_list);
            }
        } elseif ($action_code == 'add_order_product') {
            $order_id = $_REQUEST['order_id'];
            $order_product_code = trim($_REQUEST['order_product_code']);//订单商品货号
            $order_product_id = trim($_REQUEST['order_product_id']);//订单商品货号
            if (isset($_REQUEST['order_product_id'])) {
                $order_product_id = trim($_REQUEST['order_product_id']);//订单商品id
            }
            $last_order_product_id = 0;
            $product_info = $this->Product->product_first_get($order_product_code, $this->locale);
            if (empty($product_info)) {
                $msg = $this->ld['product'].' '.$order_product_code.' '.$this->ld['not_exist'];
            } else {
                if (isset($order_product_id) && $order_product_id != $product_info['Product']['id']) {
                    $this->loadModel('SkuProduct');
                    $sku_pro_info = $this->Product->find('first', array('conditions' => array('Product.id' => $order_product_id)));
                    if (!empty($sku_pro_info)) {
                        $sku_pro = $this->SkuProduct->find('first', array('conditions' => array('SkuProduct.product_code' => $sku_pro_info['Product']['code'], 'SkuProduct.sku_product_code' => $order_product_code)));
                    }
                }
                $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));//订单信息
                if (in_array('APP-DEALER', $this->apps['codes']) && $order_info['Order']['type'] == 'dealer' && !empty($order_info['Order']['type_id'])) {
                    $this->loadModel('Dealer');
                    $dealer = $this->Dealer->find('first', array('conditions' => array('Dealer.id' => $order_info['Order']['type_id'])));//订单信息
                    if (!empty($dealer)) {
                        $adjust_fee = $product_info['Product']['shop_price'] * $dealer['Dealer']['discount'] - $product_info['Product']['shop_price'];
                    }
                }
                $order_product_array = array(
                    'order_id' => $order_id,
                    'product_id' => isset($sku_pro_info['Product']['id']) ? $sku_pro_info['Product']['id'] : $product_info['Product']['id'],
                    'product_name' => isset($sku_pro_info['ProductI18n']['name']) ? $sku_pro_info['ProductI18n']['name'] : $product_info['ProductI18n']['name'],
                    'product_code' => $product_info['Product']['code'],
                    'product_quntity' => 1,
                    'product_price' => isset($sku_pro['SkuProduct']['price']) && $sku_pro['SkuProduct']['price'] != '0.00' ? $sku_pro['SkuProduct']['price'] : $product_info['Product']['shop_price'],
                    'adjust_fee' => isset($adjust_fee) ? $adjust_fee : 0,
                    'purchase_price'=>isset($sku_pro['SkuProduct']['price']) && $sku_pro['SkuProduct']['price'] != '0.00' ? $sku_pro['SkuProduct']['price'] : $product_info['Product']['shop_price'],
                    'product_weight' => isset($sku_pro_info['Product']['weight']) ? $sku_pro_info['Product']['weight'] : $product_info['Product']['weight'],
                );
                $this->OrderProduct->saveAll(array('OrderProduct' => $order_product_array));
                $last_order_product_id = $this->OrderProduct->id;
                $operator_code = $order_info['Order']['operator_code'].';3';
                $this->Order->updateAll(array('Order.operator_code' => "'".$operator_code."'", 'Order.modified' => "'".date('Y-m-d H:i:s')."'"), array('Order.id' => $order_id));
                $operation_notes = '添加订单商品货号'.$product_info['Product']['code'];
                $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $order_info['Order']['user_id'], $order_info['Order']['status'], $order_info['Order']['payment_status'], $order_info['Order']['shipping_status'], $operation_notes);
                //如果是付款状态是减库存 加冻结库存
                if ($order_info['Order']['payment_status'] == 2) {
                    $product_info['Product']['quantity'] = $product_info['Product']['quantity'] - 1;
                    $product_info['Product']['frozen_quantity'] = $product_info['Product']['frozen_quantity'] + 1;
                    $this->Product->save($product_info);
                }
                //
                $msg = $this->ld['product'].' '.$product_info['Product']['code'].' '.$this->ld['add_successful'];
                //操作员日志
                if ($this->configs['operactions-log']  == 1) {
                    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'订单号'.'.'.$order_info['Order']['order_code'].' '.'添加商品货号为'.'.'.$product_info['Product']['code'], $this->admin['id']);
                }
            }
            $total = $this->update_order_product($order_id);
            $result['total'] = $total;
		$order_info = $this->Order->find('first', array('fields' => array('id', 'order_code','insure_fee','discount'), 'conditions' => array('Order.id' => $order_id),'recursive'=>'-1'));
		if(isset($order_info['Order']['insure_fee'])){
			$result['insure_fee'] = sprintf('%01.2f', $order_info['Order']['insure_fee']);
		}
		if(isset($order_info['Order']['discount'])){
			$result['discount'] = sprintf('%01.2f', $order_info['Order']['discount']);
		}
            $result['hasproduct'] = true;
            $need_pay = $this->need_pay($order_id);
            $result['need_pay'] = $need_pay;
            $result['code'] = 1;
            $result['last_order_product_id'] = $last_order_product_id;
            die(json_encode($result));
        } elseif ($action_code == 'data_save') {
            $result['code'] = 0;
            $result['msg'] = '';
            $order_id = isset($this->data['order_id']) ? $this->data['order_id'] : '0';
            $pro_Id = isset($this->data['pro_Id']) ? $this->data['pro_Id'] : 0;
            $order_product_id = isset($this->data['order_product_id']) ? $this->data['order_product_id'] : 0;
            $product_style_id = isset($this->data['product_style_id']) ? $this->data['product_style_id'] : 0;
            $product_type_id = isset($this->data['product_type_id']) ? $this->data['product_type_id'] : 0;
            $user_id = isset($this->data['user_id']) ? $this->data['user_id'] : 0;
            $pro_notes = isset($this->data['notes']) ? $this->data['notes'] : '';
            $order_product_code = isset($this->data['order_product_code']) ? $this->data['order_product_code'] : '';
            $user_style_id = isset($this->data['user_style_id']) ? $this->data['user_style_id'] : '';
            //商品属性
            $attr_ids = $this->ProductTypeAttribute->getattrids($product_type_id);
            $pro_type_attr_info = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $attr_ids, 'Attribute.status' => 1, 'Attribute.type' => array('customize','multiple_customize')), 'order' => 'Attribute.id'));
            $order_pro_info = $this->OrderProduct->find('first', array('conditions' => array('OrderProduct.id' => $order_product_id)));
            if (!empty($order_pro_info['OrderProduct'])) {
                if (isset($this->data['saveflag']) && $this->data['saveflag'] == 'save_as') {
                    $user_style_action = isset($this->data['user_style_action']) ? $this->data['user_style_action'] : '0';
                    $default_status = isset($this->data['default_status']) ? $this->data['default_status'] : '0';
                    $user_style_name = isset($this->data['user_style_name']) ? $this->data['user_style_name'] : '';
                    $user_style_name = $user_style_name.'-'.date('Y-m-d');
                    $pro_info = $this->Product->find('first', array('conditions' => array('Product.code' => $order_product_code)));
                    $attr_info = $this->Attribute->find('first', array('conditions' => array('Attribute.code' => 'size', 'Attribute.status' => 1, 'Attribute.type' => 'buy')));
                    if (!empty($attr_info)) {
                        $pro_attr_info = $this->ProductAttribute->find('first', array('conditions' => array('ProductAttribute.attribute_id' => $attr_info['Attribute']['id'], 'ProductAttribute.product_id' => $pro_info['Product']['id'])));
                        if ($default_status == '1') {
                            $this->UserStyle->updateAll(array('UserStyle.default_status' => 0), array('UserStyle.user_id' => $user_id, 'UserStyle.style_id' => $product_style_id, 'UserStyle.type_id' => $product_type_id));
                        }
                        if ($user_style_action == '0') {
                            $UserStyle_data['user_id'] = $user_id;
                            $UserStyle_data['attribute_code'] = $pro_attr_info['ProductAttribute']['attribute_value'];
                            $UserStyle_data['style_id'] = $product_style_id;
                            $UserStyle_data['type_id'] = $product_type_id;
                            $UserStyle_data['default_status'] = $default_status;
                            $UserStyle_data['user_style_name'] = $user_style_name;
                            $this->UserStyle->saveAll($UserStyle_data);
                            $user_style_id = $this->UserStyle->id;
                        } else {
                            $user_style_id = $user_style_action;
                            $this->UserStyleValue->deleteAll(array('user_style_id' => $user_style_id));
                        }
                        foreach ($pro_type_attr_info as $v) {
                            $attribute_value = isset($this->data['pro_type_attr_value'][$v['Attribute']['id']]) ? $this->data['pro_type_attr_value'][$v['Attribute']['id']] : 0;
                            $user_style_value_data = array(
                                'user_style_id' => $user_style_id,
                                'attribute_id' => $v['Attribute']['id'],
                                'attribute_value' => $attribute_value,
                            );
                            $this->UserStyleValue->saveAll($user_style_value_data);
                        }
                    }
                    $result['code'] = 2;
                    $result['user_style_id'] = $user_style_id;
                } else {
					$order_info=$order_pro_info['Order'];
                    $order_pro_info = $order_pro_info['OrderProduct'];
                    //查询当前用户是否设置了默认规格
                    $pro_info = $this->Product->find('first', array('conditions' => array('Product.code' => $order_pro_info['product_code'])));
                    $attr_info = $this->Attribute->find('first', array('conditions' => array('Attribute.code' => 'size', 'Attribute.status' => 1, 'Attribute.type' => 'buy')));
                    if (!empty($attr_info)) {
                        $pro_attr_info = $this->ProductAttribute->find('first', array('conditions' => array('ProductAttribute.attribute_id' => $attr_info['Attribute']['id'], 'ProductAttribute.product_id' => $pro_info['Product']['id'])));
                        if (!empty($pro_attr_info)) {
                            $user_style_count = $this->UserStyle->find('count', array('conditions' => array('UserStyle.user_id' => $user_id, 'UserStyle.attribute_code' => $pro_attr_info['ProductAttribute']['attribute_value'], 'UserStyle.style_id' => $product_style_id, 'UserStyle.type_id' => $product_type_id)));
                        }
                    }
                    $attr_price_total = 0;
                    //订单商品属性值保存
                    
                    $this->OrderProductValue->deleteAll(array('order_id' => $order_id, 'order_product_id' => $order_product_id));
                    foreach ($pro_type_attr_info as $v) {
                        $attribute_value = isset($this->data['pro_type_attr_value'][$v['Attribute']['id']]) ? $this->data['pro_type_attr_value'][$v['Attribute']['id']] : '';
                        $attribute_price = $this->AttributeOption->getattroptionprice($v['Attribute']['id']);
                        if(is_array($attribute_value)){
                        	   foreach($attribute_value as $option_code=>$option_value){
                        	   	   	$attr_option_qty=isset($option_value['qty'])?$option_value['qty']:1;
                        	   	   	$attr_option_val=isset($option_value['value'])?$option_value['value']:'';
									$attr_option_val=is_array($attr_option_val)?implode(',',$attr_option_val):$attr_option_val;
                        	   	   	
                    	   	   	$order_product_value_data = array(
                    	   	   	    'id'=>0,
		                                'order_id' => $order_id,
		                                'order_product_id' => $order_product_id,
		                                'attribute_id' => $v['Attribute']['id'],
		                                'attribute_value' => $option_code.":".$attr_option_qty." ".$attr_option_val,
		                                'attr_price' => isset($attribute_price[$v['Attribute']['id']][$option_code]) ? $attribute_price[$v['Attribute']['id']][$option_code]*$attr_option_qty : 0,
		                            );
		                            $attr_price_total += isset($attribute_price[$v['Attribute']['id']][$option_code]) ? $attribute_price[$v['Attribute']['id']][$option_code]*$attr_option_qty : 0;
		                            $this->OrderProductValue->save($order_product_value_data);
                        	   }
                        }else{
	                        if ($attribute_value) {
	                            $order_product_value_data = array(
	                                'id'=>0,
	                                'order_id' => $order_id,
	                                'order_product_id' => $order_product_id,
	                                'attribute_id' => $v['Attribute']['id'],
	                                'attribute_value' => $attribute_value,
	                                'attr_price' => isset($attribute_price[$v['Attribute']['id']][$attribute_value]) ? $attribute_price[$v['Attribute']['id']][$attribute_value] : 0,
	                            );
	                            $attr_price_total += isset($attribute_price[$v['Attribute']['id']][$attribute_value]) ? $attribute_price[$v['Attribute']['id']][$attribute_value] : 0;
	                            $this->OrderProductValue->save($order_product_value_data);
	                        }
                        }
                    }
                    $sku_pro = array();
                    $sku_pro_info = $this->Product->find('first', array('conditions' => array('Product.id' => $order_pro_info['product_id'])));
                    if (!empty($sku_pro_info)) {
                        $sku_pro = $this->SkuProduct->find('first', array('conditions' => array('SkuProduct.product_code' => $sku_pro_info['Product']['code'], 'SkuProduct.sku_product_code' => $order_pro_info['product_code'])));
                    }
                    $product_price = 0;
                    if (!empty($pro_info)) {
                        $product_price = isset($sku_pro['SkuProduct']['price']) && $sku_pro['SkuProduct']['price'] != '0.00' ? $sku_pro['SkuProduct']['price'] : $pro_info['Product']['shop_price'];
                    }
                    //记录商品备注
                    $order_pro_info['product_code'] = $order_product_code;
                    $order_pro_info['note'] = $pro_notes;
                    $order_pro_info['user_style_id'] = $user_style_id;
                    $order_pro_info['product_style_id'] = $product_style_id;
                    if(!(isset($order_info['service_type'])&&$order_info['service_type']=='appointment'&&isset($order_info['payment_status'])&&$order_info['payment_status']=='2'&&isset($order_info['shipping_status'])&&$order_info['shipping_status']=='3')){
                    	$order_pro_info['product_price'] = $product_price + $attr_price_total;
                    }
                    $this->OrderProduct->save($order_pro_info);
                    $last_order_product_id = $this->OrderProduct->id;
                    $total = $this->update_order_product($order_id);
                    $result['last_order_product_id'] = $last_order_product_id;
                    $result['total'] = $total;
                    $result['hasproduct'] = true;
                    $need_pay = $this->need_pay($order_id);
                    $result['need_pay'] = $need_pay;
                    $result['code'] = 1;
			$order_info = $this->Order->find('first', array('fields' => array('id', 'order_code','insure_fee','discount'), 'conditions' => array('Order.id' => $order_id),'recursive'=>'-1'));
			if(isset($order_info['Order']['insure_fee'])){
				$result['insure_fee'] = sprintf('%01.2f', $order_info['Order']['insure_fee']);
			}
			if(isset($order_info['Order']['discount'])){
				$result['discount'] = sprintf('%01.2f', $order_info['Order']['discount']);
			}
                }
            } else {
                if (isset($this->data['saveflag']) && $this->data['saveflag'] == 'save_as' && $order_product_code != '') {
                    $user_style_action = isset($this->data['user_style_action']) ? $this->data['user_style_action'] : '0';
                    $default_status = isset($this->data['default_status']) ? $this->data['default_status'] : '0';
                    $user_style_name = isset($this->data['user_style_name']) ? $this->data['user_style_name'] : '';
                    $user_style_name = $user_style_name.'-'.date('Y-m-d');
                    $pro_info = $this->Product->find('first', array('conditions' => array('Product.code' => $order_product_code)));
                    $attr_info = $this->Attribute->find('first', array('conditions' => array('Attribute.code' => 'size', 'Attribute.status' => 1, 'Attribute.type' => 'buy')));
                    if (!empty($attr_info)) {
                        $pro_attr_info = $this->ProductAttribute->find('first', array('conditions' => array('ProductAttribute.attribute_id' => $attr_info['Attribute']['id'], 'ProductAttribute.product_id' => $pro_info['Product']['id'])));
                        if ($default_status == '1') {
                            $this->UserStyle->updateAll(array('UserStyle.default_status' => 0), array('UserStyle.user_id' => $user_id, 'UserStyle.style_id' => $product_style_id, 'UserStyle.type_id' => $product_type_id));
                        }
                        if ($user_style_action == '0') {
                            $UserStyle_data['user_id'] = $user_id;
                            $UserStyle_data['attribute_code'] = $pro_attr_info['ProductAttribute']['attribute_value'];
                            $UserStyle_data['style_id'] = $product_style_id;
                            $UserStyle_data['type_id'] = $product_type_id;
                            $UserStyle_data['default_status'] = $default_status;
                            $UserStyle_data['user_style_name'] = $user_style_name;
                            $this->UserStyle->saveAll($UserStyle_data);
                            $user_style_id = $this->UserStyle->id;
                        } else {
                            $user_style_id = $user_style_action;
                            $this->UserStyleValue->deleteAll(array('user_style_id' => $user_style_id));
                        }
                        foreach ($pro_type_attr_info as $v) {
                            $attribute_value = isset($this->data['pro_type_attr_value'][$v['Attribute']['id']]) ? $this->data['pro_type_attr_value'][$v['Attribute']['id']] : 0;
                            $user_style_value_data = array(
                                'user_style_id' => $user_style_id,
                                'attribute_id' => $v['Attribute']['id'],
                                'attribute_value' => $attribute_value,
                            );
                            $this->UserStyleValue->saveAll($user_style_value_data);
                        }
                    }
                    $result['code'] = 2;
                    $result['user_style_id'] = $user_style_id;
                } else {
                    $result['msg'] = '订单商品未找到';
                }
            }
            $user_style_list = $this->UserStyle->find('all', array('fields' => array('UserStyle.id', 'UserStyle.attribute_code', 'UserStyle.user_style_name'), 'conditions' => array('UserStyle.user_id' => $user_id, 'UserStyle.type_id' => $product_type_id)));
            $result['user_style_list'] = $user_style_list;
            die(json_encode($result));
        }
    }

    public function print_attr_value($order_id = 0, $order_product_id = 0, $product_type_id = 0, $product_style_id = 0)
    {
        Configure::write('debug', 1);
        if(!$this->operator_privilege('orders_edit',false)&&!$this->operator_privilege('lease_orders_edit',false)){
            $result['code'] = 0;
            $result['message'] = $this->ld['have_no_operation_perform'];
            die(json_encode($result));
        }
        $this->layout = 'pdf';
        $this->loadModel('SkuProduct');
        $this->loadModel('Attribute');
        $this->loadModel('ProductAttribute');
        $this->loadModel('ProductTypeAttribute');
        $this->loadModel('StyleTypeGroup');
        $this->loadModel('StyleTypeGroupAttributeValue');
        $this->loadModel('UserStyle');
        $this->loadModel('UserStyleValue');
        $this->Product->set_locale($this->backend_locale);
        $this->ProductStyle->set_locale($this->backend_locale);
        $this->ProductType->set_locale($this->backend_locale);
        $this->Attribute->set_locale($this->backend_locale);
        $pro_notes = '';
        $attr_edit_value = array();
        $style_type_group = '';
        if ($this->RequestHandler->isPost()) {
            $order_id = isset($this->data['order_id']) ? $this->data['order_id'] : '0';
            $order_product_id = isset($this->data['order_product_id']) ? $this->data['order_product_id'] : 0;
            $product_style_id = isset($this->data['product_style_id']) ? $this->data['product_style_id'] : 0;
            $product_type_id = isset($this->data['product_type_id']) ? $this->data['product_type_id'] : 0;
            $style_type_group = isset($this->data['style_type_group']) ? $this->data['style_type_group'] : 0;
            $pro_notes = isset($this->data['notes']) ? $this->data['notes'] : '';
            if (isset($this->data['pro_type_attr_value']) && !empty($this->data['pro_type_attr_value'])) {
                $attr_edit_value = $this->data['pro_type_attr_value'];
            }
        }
        //订单信息
        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));
        if (empty($order_info)) {
            $this->redirect('/orders/index');
        }
        //操作员信息
        $operatorInfo = array();
        if (!empty($this->admin)) {
            $operatorInfo = $this->Operator->find('first', array('conditions' => array('Operator.id' => $this->admin['id'])));
        }
        //商品信息
        $order_pro_info = array();
        if (isset($order_info['OrderProduct']) && sizeof($order_info['OrderProduct']) > 0) {
            foreach ($order_info['OrderProduct'] as $v) {
                if ($v['id'] == $order_product_id) {
                    $order_pro_info = $v;
                    $pro_notes = $pro_notes == '' ? $v['note'] : $pro_notes;
                }
            }
        }
        //版型信息
        $pro_style_info = $this->ProductStyle->find('first', array('conditions' => array('ProductStyle.id' => $product_style_id)));
        //属性列表
        $attr_ids = $this->ProductTypeAttribute->getattrids($product_type_id);
        $pro_type_attr_info = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $attr_ids, 'Attribute.status' => 1), 'order' => 'Attribute.id'));
        $pro_type_attr_list = array();
        $pro_type_attr_default_value = array();
        foreach ($pro_type_attr_info as $v) {
            $pro_type_attr_list[$v['Attribute']['id']] = $v['AttributeI18n']['name'];
            $pro_type_attr_default_value[$v['Attribute']['id']] = $v['AttributeI18n']['default_value'];
        }
        $attr_info = $this->Attribute->find('first', array('conditions' => array('Attribute.code' => 'size', 'Attribute.status' => 1, 'Attribute.type' => 'buy')));
        if (!empty($attr_info) && !empty($order_pro_info)) {
            $pro_info = $this->Product->find('first', array('conditions' => array('Product.code' => $order_pro_info['product_code'])));
            $pro_attr_info = $this->ProductAttribute->find('first', array('conditions' => array('ProductAttribute.attribute_id' => $attr_info['Attribute']['id'], 'ProductAttribute.product_id' => $pro_info['Product']['id'])));
            $style_type_group = $pro_attr_info['ProductAttribute']['attribute_value'];
            $style_type_group_cond['StyleTypeGroup.style_id'] = $product_style_id;
            $style_type_group_cond['StyleTypeGroup.type_id'] = $product_type_id;
            $style_type_group_cond['StyleTypeGroup.group_name'] = $pro_attr_info['ProductAttribute']['attribute_value'];
            $style_type_group_info = $this->StyleTypeGroup->find('first', array('conditions' => $style_type_group_cond));
            $style_type_group_id = isset($style_type_group_info['StyleTypeGroup']['id']) ? $style_type_group_info['StyleTypeGroup']['id'] : 0;
            $attrvaluelist = array();
            $pro_type_attr_type_list = array();//属性修改可选值列表
            $attrvalueInfo = $this->StyleTypeGroupAttributeValue->getattrvaluelist($product_style_id, $product_type_id, $style_type_group_id);
            foreach ($attrvalueInfo as $v) {
                $attrvaluelist[$v['StyleTypeGroupAttributeValue']['attribute_id']] = $v['StyleTypeGroupAttributeValue']['default_value'];
                if (trim($v['StyleTypeGroupAttributeValue']['select_value']) != '') {
                    $pro_type_attr_type_list[$v['StyleTypeGroupAttributeValue']['attribute_id']] = split("\r\n", $v['StyleTypeGroupAttributeValue']['select_value']);
                }
            }
            if (empty($attr_edit_value)) {
                $order_product_value_data_cond = array(
                    'order_id' => $order_id,
                    'order_product_id' => $order_product_id,
                );
                $attr_edit_value = $this->OrderProductValue->find('list', array('fields' => array('attribute_id', 'attribute_value'), 'conditions' => $order_product_value_data_cond));
            }
        } else {
            $this->redirect('/orders/index');
        }
        $this->set('operatorInfo', $operatorInfo);
        $this->set('order_info', $order_info);
        $this->set('order_pro_info', $order_pro_info);
        $this->set('pro_style_info', $pro_style_info);
        $this->set('style_type_group', $style_type_group);
        $this->set('style_type_group_info', $style_type_group_info);
        $this->set('pro_type_attr_list', $pro_type_attr_list);
        $this->set('attrvaluelist', $attrvaluelist);
        $this->set('pro_type_attr_default_value', $pro_type_attr_default_value);
        $this->set('pro_type_attr_type_list', $pro_type_attr_type_list);
        $this->set('pro_notes', $pro_notes);
        $this->set('attr_edit_value', $attr_edit_value);
        $this->render();
    }

    public function change_vendor_information($action = 'show')
    {
        $this->layout = 'ajax';
        Configure::write('debug', 1);
        if ($this->RequestHandler->isPost()) {
            $order_code = isset($_POST['order_code']) ? $_POST['order_code'] : '';
            $purchase_order_data = $this->PurchaseOrder->find('first', array('conditions' => array('PurchaseOrder.order_code' => $order_code)));
            if ($action == 'show') {
                if (!empty($purchase_order_data)) {
                    $result['code'] = 1;
                    $result['data'] = $purchase_order_data['PurchaseOrder'];
                } else {
                    $result['code'] = 0;
                    $result['data'] = array();
                }
                die(json_encode($result));
            } elseif ($action == 'data_save') {
                $ESD = isset($_POST['ESD']) ? $_POST['ESD'] : '0000-00-00';
                $ASD = isset($_POST['ASD']) ? $_POST['ASD'] : '0000-00-00';
                $logistics_company_id = isset($_POST['logistics_company_id']) ? $_POST['logistics_company_id'] : 0;
                $invoice_no = isset($_POST['invoice_no']) ? $_POST['invoice_no'] : '';
                $purchase_order_data['PurchaseOrder']['order_code'] = $order_code;
                $purchase_order_data['PurchaseOrder']['ESD'] = $ESD;
                $purchase_order_data['PurchaseOrder']['ASD'] = $ASD;
                $purchase_order_data['PurchaseOrder']['logistics_company_id'] = $logistics_company_id;
                $purchase_order_data['PurchaseOrder']['invoice_no'] = $invoice_no;
                if ($this->PurchaseOrder->save($purchase_order_data)) {
                    $result['code'] = 1;
                    $result['msg'] = $this->ld['modified_successfully'];
                } else {
                    $result['code'] = 0;
                    $result['msg'] = $this->ld['modify_failed'];
                }
                die(json_encode($result));
            }
        } else {
            $this->redirect('/orders/index');
        }
    }

    /*
        获取销售属性商品购买属性可选值
    */
    public function get_order_pro_code($pro_id, $pro_code, $product_type_id)
    {
        $this->loadModel('SkuProduct');
        $this->loadModel('ProductAttribute');
        $this->loadModel('ProductTypeAttribute');
        if ($this->SkuProduct->check_sku_pro($pro_code)) {
            //主商品货号
            $pro_ids = $this->SkuProduct->get_sku_pro_ids($pro_code);
        } elseif ($this->SkuProduct->check_sku($pro_code)) {
            //子商品货号
            $sku_pro = $this->Product->find('first', array('conditions' => array('Product.id' => $pro_id)));
            $pro_ids = $this->SkuProduct->get_sku_pro_ids($sku_pro['Product']['code']);
        } else {
            $pro_ids = $pro_id;
        }
        //购买属性列表
        $attr_ids = $this->ProductTypeAttribute->getattrids($product_type_id);
        $pro_type_attr_info = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $attr_ids, 'Attribute.status' => 1, 'Attribute.type' => 'buy')));
        $pro_attr_info = array();
        //商品属性(规格)
        $pro_attr = $this->ProductAttribute->find('all', array('conditions' => array('ProductAttribute.product_id' => $pro_ids, 'ProductAttribute.locale' => $this->backend_locale)));
        foreach ($pro_attr as $v) {
            $pro_attr_info[$v['ProductAttribute']['product_id']][$v['ProductAttribute']['attribute_id']] = $v['ProductAttribute']['attribute_value'];
        }
        $attr_sel_list = array();
        foreach ($pro_attr_info as $k => $v) {
            if (!empty($v) && is_array($v)) {
                foreach ($v as $kk => $vv) {
                    $attr_sel_list[$kk][] = $vv;
                }
            }
        }
        return $attr_sel_list;
    }

    //取货单导出
    public function delivery_exprot_out()
    {
        $ids=isset($_REQUEST["checkboxes"])?$_REQUEST["checkboxes"]:0;
        $str = $this->ld['sku'].','.$this->ld['name'].','.$this->ld['product_attribute'].','.$this->ld['order_quantity'].','.$this->ld['shop_price'].','.$this->ld['purchase_price'].',';
        if($ids!=0){
            $p = $this->OrderProduct->find('all', array('fields' => array('sum(OrderProduct.product_quntity) as amt', 'OrderProduct.product_name', 'OrderProduct.product_code', 'OrderProduct.product_attrbute', 'OrderProduct.product_id', 'OrderProduct.product_price'),'conditions' => array('OrderProduct.order_id' => $ids), 'group' => 'OrderProduct.product_id,OrderProduct.product_attrbute'));
        }else{
            $p = $this->OrderProduct->find('all', array('fields' => array('sum(OrderProduct.product_quntity) as amt', 'OrderProduct.product_name', 'OrderProduct.product_code', 'OrderProduct.product_attrbute', 'OrderProduct.product_id', 'OrderProduct.product_price'), 'group' => 'OrderProduct.product_id,OrderProduct.product_attrbute'));
        }
        $datas[] = array($str);
        foreach ($datas[0] as $v) {
            $newdatas[] = explode(',', $v);
        }
        if (!empty($p)) {
            foreach ($p as $v) {
                $purchase_price = $this->Product->find('first', array('fields' => array('Product.purchase_price','Product.option_type_id'),'conditions' => array('Product.id' => $v['OrderProduct']['product_id'])));
                if($purchase_price["Product"]["option_type_id"]==1){continue;}
                $newdata = array();
                $newdata[] = $v['OrderProduct']['product_code'];
                $newdata[] = $v['OrderProduct']['product_name'];
                $newdata[] = $v['OrderProduct']['product_attrbute'];
                $newdata[] = $v[0]['amt'];
                $newdata[] = $v['OrderProduct']['product_price'];
                $newdata[] = isset($purchase_price["Product"]["purchase_price"])?$purchase_price["Product"]["purchase_price"]:"0.00";
                $newdatas[] = $newdata;
            }
        }
        $this->Phpexcel->output('products'.date('YmdHis').'.xls', $newdatas);
        exit;
    }

    /*
    		设置订单商品服务类型
    */
    function order_product_service_type_modify(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';

        $result=array();
        $result['code']='0';
        $result['message']=$this->ld['modify_failed'];
        $order_id=isset($_POST['order_id'])?$_POST['order_id']:0;
        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id,'Order.shipping_status'=>array(0,6,9))));
        if(!empty($order_info)){
            $order_product_id=isset($_POST['order_product_id'])?$_POST['order_product_id']:0;
            $service_type=isset($_POST['service_type'])?$_POST['service_type']:'';
            $order_product_info=$this->OrderProduct->find('first',array('conditions'=>array('OrderProduct.id'=>$order_product_id)));
            $Resource_info = $this->Resource->getformatcode(array('order_product_status','order_product_service_type'), $this->backend_locale);
            if(!empty($order_product_info)){
                $order_product_data=array(
                    'id'=>$order_product_id,
                    'service_type'=>$service_type
                );
                $this->OrderProduct->save($order_product_data);
                $action_note="设置商品".$order_product_info['OrderProduct']['product_code'].(trim($order_product_info['OrderProduct']['product_number'])!=''?"(".$order_product_info['OrderProduct']['product_number'].")":"").'服务类型:'.isset($Resource_info['order_product_service_type'][$service_type])?$Resource_info['order_product_service_type'][$service_type]:$this->ld['default'];
                $this->OrderProductAction->update_order_product_action(array(
                    'order_id'=>$order_id,
                    'order_product_id'=>$order_product_id,
                    'status'=>$order_product_info['OrderProduct']['delivery_status'],
                    'operator_id'=>$this->admin['id'],
                    'action_note'=>$action_note
                ));
                $result['code']='1';
                $result['message']=$action_note;
                $total = $this->update_order_product($order_id);
                $result['total'] = $total;
                $need_pay = $this->need_pay($order_id);
                $result['need_pay'] = $need_pay;
                $result['insure_fee'] = $order_info['Order']['insure_fee'];

                $result['code']='1';
                $result['message']=$this->ld['order_success_update'];
            }
        }
        die(json_encode($result));
    }


    /*
    	批量设置订单管理员
    */
    function ajax_batch_order_manager(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $result['message']=$this->ld['modify_failed'];
        if($this->operator_privilege('order_advanced',false)){
            $order_ids=isset($_POST['order_ids'])?$_POST['order_ids']:array();
            $order_manager=isset($_POST['order_manager'])?intval($_POST['order_manager']):0;

            $operator_info = $this->Operator->find('first',array('fields' => array('Operator.id','Operator.name','Operator.mobile'),'conditions'=>array('Operator.id'=>$order_manager,'Operator.status'=>'1')));
            $conditions=array(
                'Order.id'=>$order_ids,
                'Order.shipping_status'=>array(0,6,9),
                'Order.order_manager <>'=>$order_manager
            );
            $order_list=$this->Order->find('all',array('fields'=>'Order.id,Order.order_code,Order.user_id,Order.status,Order.payment_status,Order.shipping_status','conditions'=>$conditions,'recursive' => -1));
            if(sizeof($order_list)>0&&!empty($operator_info)){
                foreach($order_list as $v){
                    $operation_notes="批量更新管理员:".$operator_info['Operator']['name'];
                    $this->Order->save(array('id'=>$v['Order']['id'],'order_manager'=>$order_manager,'status'=>$v['Order']['status']=='9'?'0':$v['Order']['status']));
                    $this->OrderAction->update_order_actions($v['Order']['id'], $this->admin['id'], $v['Order']['user_id'], $v['Order']['status']=='9'?'0':$v['Order']['status'], $v['Order']['payment_status'], $v['Order']['shipping_status'], $operation_notes);
                }
                if(trim($operator_info['Operator']['mobile'])!=''){
                		$operator_mobile=trim($operator_info['Operator']['mobile']);
                		$sms_content="有新订单待处理";
                		$sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
                		$sms_result=$this->Notify->send_sms($operator_mobile,$sms_content,$sms_kanal,$this->configs,false);
                }
                $result['code']='1';
                $result['message']=$this->ld['order_success_update'];
            }else if(sizeof($order_list)>0&&intval($order_manager)==0){
                foreach($order_list as $v){
                    $operation_notes="批量更新管理员:-";
                    $this->Order->save(array('id'=>$v['Order']['id'],'order_manager'=>$order_manager,'status'=>$v['Order']['status']=='0'?'9':$v['Order']['status']));
                    $this->OrderAction->update_order_actions($v['Order']['id'], $this->admin['id'], $v['Order']['user_id'], $v['Order']['status']=='9'?'0':$v['Order']['status'], $v['Order']['payment_status'], $v['Order']['shipping_status'], $operation_notes);
                }
                $result['code']='1';
                $result['message']=$this->ld['order_success_update'];
            }
        }else{
            $result['message']=$this->ld['have_no_operation_perform'];
        }
        die(json_encode($result));
    }

    /*
    		设置商品条码
    */
    function ajax_modify_product_number(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $result['message']=$this->ld['modify_failed'];
        $order_product_id=isset($_POST['order_product_id'])?$_POST['order_product_id']:0;
        $order_product_info=$this->OrderProduct->find('first',array('conditions'=>array('OrderProduct.id'=>$order_product_id)));
        if(!empty($order_product_info)){
            $order_id=$order_product_info['Order']['id'];
            $order_product_number=isset($_POST['product_number'])?trim($_POST['product_number']):'';
            if($order_product_number!=''){
                $product_number_check=$this->OrderProduct->find('first',array('conditions'=>array('OrderProduct.product_number'=>$order_product_number,'OrderProduct.del_status'=>'1'),'recursive' => -1));
            }else{
                	die(json_encode($result));
            }
            if(!empty($product_number_check)){
                $result['message']=$this->ld['code_already_exists'];
                die(json_encode($result));
            }
            $order_product_code=$order_product_info['OrderProduct']['product_code'];
            if($order_product_info['OrderProduct']['parent_product_id']==0){
                if($order_product_info['OrderProduct']['product_quntity']==1){
                    if(!empty($product_number_check)&&$product_number_check['OrderProduct']['id']!=$order_product_id){
                        $result['message']=$this->ld['code_already_exists'];
                        die(json_encode($result));
                    }
                    $order_product_data=array(
                        'id'=>$order_product_id,
                        'product_number'=>$order_product_number
                    );
                    $this->OrderProduct->save($order_product_data);
                    $this->OrderProductAction->update_order_product_action(array(
                        'order_id'=>$order_id,
                        'order_product_id'=>$order_product_id,
                        'status'=>$order_product_info['OrderProduct']['delivery_status'],
                        'operator_id'=>$this->admin['id'],
                        'action_note'=>"更新商品{$order_product_code}商品条码:".$order_product_number
                    ));
                    $result['code']='1';
                    $result['message']=$this->ld['order_success_update'];
                }else{
                    $child_product_count=$this->OrderProduct->find('count',array('conditions'=>array('OrderProduct.order_id'=>$order_id,'OrderProduct.parent_product_id'=>$order_product_info['OrderProduct']['product_id'])));
                    if($child_product_count<$order_product_info['OrderProduct']['product_quntity']){
                        $order_product_data=$order_product_info['OrderProduct'];
                        unset($order_product_data['created']);unset($order_product_data['modified']);
                        $order_product_data['id']=0;
                        $order_product_data['parent_product_id']=$order_product_info['OrderProduct']['id'];
                        $order_product_data['product_quntity']=1;
                        $order_product_data['product_price']=0;
                        $order_product_data['adjust_fee']=0;
                        $order_product_data['purchase_price']=0;
                        $order_product_data['product_number']=$order_product_number;
                        $order_product_data['delivery_status']=0;
                        $order_product_data['picker']=0;
                        $order_product_data['QC']=0;
                        $this->OrderProduct->save($order_product_data);
                        $new_order_product_id=$this->OrderProduct->id;
                        $this->OrderProductAction->update_order_product_action(array(
                            'order_id'=>$order_id,
                            'order_product_id'=>$new_order_product_id,
                            'status'=>'0',
                            'operator_id'=>$this->admin['id'],
                            'action_note'=>"设置商品{$order_product_code}商品条码:".$order_product_number
                        ));
                        $result['code']='1';
                        $result['message']=$this->ld['order_success_update'];
                    }
                }
            }else if($order_product_info['OrderProduct']['product_quntity']==1){
                if(!empty($product_number_check)&&$product_number_check['OrderProduct']['id']!=$order_product_id){
                    $result['message']=$this->ld['code_already_exists'];
                    die(json_encode($result));
                }
                $order_product_data=array(
                    'id'=>$order_product_id,
                    'product_number'=>$order_product_number,
                );
                $this->OrderProduct->save($order_product_data);
                $this->OrderProductAction->update_order_product_action(array(
                    'order_id'=>$order_id,
                    'order_product_id'=>$order_product_id,
                    'status'=>$order_product_info['OrderProduct']['delivery_status'],
                    'operator_id'=>$this->admin['id'],
                    'action_note'=>"更新商品{$order_product_code}商品条码:".$order_product_number
                ));
                $result['code']='1';
                $result['message']=$this->ld['order_success_update'];
            }
            $total = $this->update_order_product($order_id);
            $result['total'] = $total;
            $need_pay = $this->need_pay($order_id);
            $result['need_pay'] = $need_pay;
            $result['insure_fee'] = $order_product_info['Order']['insure_fee'];
        }
        die(json_encode($result));
    }

    /*
    		待发货订单商品
    */
    function ajax_to_be_delivered($order_id=0){
        Configure::write('debug',1);
        $this->layout = 'ajax';

        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));
        //pr($order_info);
        $this->set('order_info',$order_info);
        if(!empty($order_info)){
            $this->set('order_action', $this->operable_list($order_info));//操作状态
        }
        $parent_order_product_ids=$this->OrderProduct->find('list',array('fields'=>"OrderProduct.parent_product_id",'conditions'=>array('OrderProduct.order_id'=>$order_id,'OrderProduct.parent_product_id >'=>0)));
        //未发货
        $conditions=array();
        $conditions['Order.id']=$order_id;
        if(isset($order_info['Order']['service_type'])&&$order_info['Order']['service_type']=='appointment'){
            	$conditions['OrderProduct.delivery_status']='4';
        }
        if(!empty($parent_order_product_ids)){
            $not_to_be_delivered_product_ids=$this->OrderProduct->find('list',array('conditions'=>array('OrderProduct.order_id'=>$order_id,'OrderProduct.parent_product_id'=>0,'OrderProduct.product_id'=>$parent_order_product_ids)));
            	if(!empty($parent_order_product_ids))$conditions['not']['OrderProduct.id']=$not_to_be_delivered_product_ids;
        }
        $order_product_list=$this->OrderProduct->find('all',array('conditions'=>$conditions,'order'=>'OrderProduct.id'));
        $this->set('order_product_list',$order_product_list);
	 
    	$order_shipment_info=$this->OrderShipment->find('first',array('conditions'=>array('OrderShipment.order_id'=>$order_id,'OrderShipment.status'=>'0')));
    	$this->set('order_shipment_info',$order_shipment_info);
    	
        if(!empty($order_product_list)){
            	$logistics_company_list = $this->LogisticsCompany->logistics_company_effective_list();
            	$this->set('logistics_company_list', $logistics_company_list);
            	
            	$this->loadModel('OrderProductMedia');
            	$order_product_medias=$this->OrderProductMedia->find('all',array('conditions'=>array('OrderProductMedia.order_id'=>$order_id,'type'=>'image','media <>'=>'','OrderProductMedia.media_group'=>2)));
        	$this->set('order_product_medias',$order_product_medias);
        }
    }
    
    function ajax_order_shipment($order_id=0){
    		Configure::write('debug', 1);
        	$this->layout = 'ajax';
        	
		$result=array();
		$result['code']='0';
		$result['message']=$this->ld['modify_failed'];
		$order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id,'Order.shipping_status'=>array(0,3,6))));
        	if(!empty($order_info)){
			$order_shipment_data=isset($_POST['data']['OrderShipment'])?$_POST['data']['OrderShipment']:array();
			$order_shipment_data['id']=isset($order_shipment_data['id'])?$order_shipment_data['id']:0;
			$order_shipment_data['order_id']=$order_id;
			$region_ids=array();
			if(is_numeric($order_shipment_data['country'])&&intval($order_shipment_data['country'])>0){
				$region_ids[]=$order_shipment_data['country'];
			}
			if(is_numeric($order_shipment_data['province'])&&intval($order_shipment_data['province'])>0){
				$region_ids[]=$order_shipment_data['province'];
			}
			if(is_numeric($order_shipment_data['city'])&&intval($order_shipment_data['city'])>0){
				$region_ids[]=$order_shipment_data['city'];
			}
			$order_shipment_data['regions']=$order_shipment_data['country'].' '.$order_shipment_data['province'].' '.$order_shipment_data['city'];
			if(!empty($region_ids)){
				$region_list=$this->RegionI18n->find('list',array('fields'=>'region_id,name','conditions'=>array('region_id'=>$region_ids,'locale'=>$this->backend_locale)));
				$order_shipment_data['country']=isset($region_list[$order_shipment_data['country']])?$region_list[$order_shipment_data['country']]:$order_shipment_data['country'];
				$order_shipment_data['province']=isset($region_list[$order_shipment_data['province']])?$region_list[$order_shipment_data['province']]:$order_shipment_data['province'];
				$order_shipment_data['city']=isset($region_list[$order_shipment_data['city']])?$region_list[$order_shipment_data['city']]:$order_shipment_data['city'];
			}
			$this->OrderShipment->save($order_shipment_data);

			if(isset($_POST['shipping_id'])&&$_POST['shipping_id']!=''){
                $shipping_effective_list = $this->Shipping->shipping_effective_list_beta($this->locale);
                //pr($shipping_effective_list);exit();
                foreach ($shipping_effective_list as $kk11 => $vv11) {
                    $ship_info[$vv11['Shipping']['id']] = $vv11['ShippingI18n']['name'];
                }
                //pr($ship_info);exit();
                $or_info = $this->Order->find('first',array('conditions'=>array('Order.id'=>$order_id)));
                $or_info['Order']['shipping_id'] = $_POST['shipping_id'];
                if(isset($ship_info)&&count($ship_info)>0){
                    $or_info['Order']['shipping_name'] = $ship_info[$_POST['shipping_id']];
                }
                //pr($or_info);exit();
                $this->Order->save($or_info);
            }
			$result['code']='1';
			$result['message']=$this->ld['modified_successfully'];
        	}
        	die(json_encode($result));
    }

    /*
    		订单商品发货
    */
    function ajax_order_delivered($order_id=0){
        Configure::write('debug', 1);
        $this->layout = 'ajax';

        $result=array();
        $result['code']='0';
        $result['message']=$this->ld['modify_failed'];
        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id,'Order.shipping_status'=>array(0,3))));
        if(!empty($order_info)){
            $action_code=isset($_POST['code'])?$_POST['code']:'';
            if($action_code=='order_payment_delivery'){
                $need_pay = $order_info['Order']['total'] - $order_info['Order']['point_fee'] - $order_info['Order']['coupon_fee'] - $order_info['Order']['money_paid'] - $order_info['Order']['discount'];
                $order_info['Order']['payment_status'] = 2;
                $this->Order->update_order(array('id' => $order_id, 'payment_status' => '2', 'payment_time' => date('Y-m-d H:i:s', strtotime(DateTime)), 'operator_id' => $this->admin['id'], 'money_paid' => ($need_pay + $order_info['Order']['money_paid'])));
            }
            $conditions=array();
            $conditions['Order.id']=$order_id;
            if(isset($_POST['order_product_id'])&&is_array($_POST['order_product_id'])&&!empty($_POST['order_product_id'])){
                $conditions['OrderProduct.id']=$_POST['order_product_id'];
            }
            if(isset($order_info['Order']['service_type'])&&$order_info['Order']['service_type']=='appointment'){
                $conditions['OrderProduct.delivery_status']='4';
            }
            $order_product_list=$this->OrderProduct->find('all',array('fields'=>"OrderProduct.id,OrderProduct.product_code,OrderProduct.product_quntity,OrderProduct.product_number",'conditions'=>$conditions,'order'=>'OrderProduct.id'));
            if(!empty($order_product_list)){
            	  $order_shipment_data=isset($_POST['data']['OrderShipment'])?$_POST['data']['OrderShipment']:array();
            	  $order_shipment_data['id']=isset($order_shipment_data['id'])?$order_shipment_data['id']:0;
            	  $order_shipment_data['order_id']=$order_id;
            	  $order_shipment_data['status']=1;
            	  $order_shipment_data['logistics_company_id']=isset($_POST['logistics_company_id'])?$_POST['logistics_company_id']:0;
            	  $order_shipment_data['invoice_no']=isset($_POST['invoice_no'])?$_POST['invoice_no']:'';
            	  $region_ids=array();
            	  if(is_numeric($order_shipment_data['country'])&&intval($order_shipment_data['country'])>0){
            	  	$region_ids[]=$order_shipment_data['country'];
            	  }
            	  if(is_numeric($order_shipment_data['province'])&&intval($order_shipment_data['province'])>0){
            	  	$region_ids[]=$order_shipment_data['province'];
            	  }
            	  if(is_numeric($order_shipment_data['city'])&&intval($order_shipment_data['city'])>0){
            	  	$region_ids[]=$order_shipment_data['city'];
            	  }
				$order_shipment_data['regions']=$order_shipment_data['country'].' '.$order_shipment_data['province'].' '.$order_shipment_data['city'];
            	  if(!empty($region_ids)){
            	  	  $region_list=$this->RegionI18n->find('list',array('fields'=>'region_id,name','conditions'=>array('region_id'=>$region_ids,'locale'=>$this->backend_locale)));
            	  	  $order_shipment_data['country']=isset($region_list[$order_shipment_data['country']])?$region_list[$order_shipment_data['country']]:$order_shipment_data['country'];
            	  	  $order_shipment_data['province']=isset($region_list[$order_shipment_data['province']])?$region_list[$order_shipment_data['province']]:$order_shipment_data['province'];
            	  	  $order_shipment_data['city']=isset($region_list[$order_shipment_data['city']])?$region_list[$order_shipment_data['city']]:$order_shipment_data['city'];
            	  }  
                $this->OrderShipment->save($order_shipment_data);
                $order_shipment_id=$this->OrderShipment->id;
                foreach($order_product_list as $v){
                    $order_shipment_product_data=array(
                        'id'=>0,
                        'order_shipment_id'=>$order_shipment_id,
                        'order_product_id'=>$v['OrderProduct']['id'],
                        'product_quantity'=>$v['OrderProduct']['product_quntity'],
                        'status'=>'1'
                    );
                    $this->OrderShipmentProduct->save($order_shipment_product_data);
                    $this->OrderProduct->save(array('id'=>$v['OrderProduct']['id'],'delivery_status'=>5));
                    $action_note="商品".$v['OrderProduct']['product_code'].(trim($v['OrderProduct']['product_number'])!=''?"(".$v['OrderProduct']['product_number'].")":"")."发货";
                    $this->OrderProductAction->update_order_product_action(array(
                        'order_id'=>$order_id,
                        'order_product_id'=>$v['OrderProduct']['id'],
                        'status'=>'5',
                        'operator_id'=>$this->admin['id'],
                        'action_note'=>$action_note
                    ));
                }
                $this->notify_order_delivery($order_id);
            }
            $result['code']='1';
            $result['message']=$this->ld['set_shipped'];
            $parent_order_product_ids=$this->OrderProduct->find('list',array('fields'=>"OrderProduct.parent_product_id",'conditions'=>array('OrderProduct.order_id'=>$order_id,'OrderProduct.parent_product_id >'=>0)));
            $conditions=array();
            $conditions['Order.id']=$order_id;
            $conditions['OrderProduct.delivery_status <>']='5';
            if(!empty($parent_order_product_ids)){
                $conditions['not']['OrderProduct.id']=$parent_order_product_ids;
            }
            $to_be_delivered_products=$this->OrderProduct->find('count',array('conditions'=>$conditions));
            if($to_be_delivered_products==0){
                $this->Order->update_order(array('id' => $order_id, 'shipping_status' => '1', 'taobao_delivery_send' => '1', 'error_count' => '0', 'shipping_time' => date('Y-m-d H:i:s', strtotime(DateTime)),'logistics_company_id'=>isset($_POST['logistics_company_id'])?$_POST['logistics_company_id']:0,'invoice_no'=>isset($_POST['invoice_no'])?$_POST['invoice_no']:''));
                $this->OrderAction->update_order_actions($order_id, $this->admin['id'], $order_info['Order']['user_id'], $order_info['Order']['status'], $order_info['Order']['payment_status'], 1, $this->ld['set_shipped']);
			if(isset($this->configs['order_point_give_time'])&&$this->configs['order_point_give_time']=='1'){
				//订单商品、订单支付金额赠送积分
				$this->Order->give_order_point($order_id,$this);
			}
            }
        }
        die(json_encode($result));
    }
    
    function ajax_order_cancel_delivered(){
    		Configure::write('debug', 1);
        	$this->layout = 'ajax';
        	
		$result=array();
		$result['code']='0';
		$result['message']=$this->ld['modify_failed'];
        	
        	$order_id=isset($_POST['order_id'])?$_POST['order_id']:0;
        	$shipment_id=isset($_POST['shipment_id'])?$_POST['shipment_id']:0;
        	$order_shipment=$this->OrderShipment->find('first',array('conditions'=>array('OrderShipment.id'=>$shipment_id,'OrderShipment.order_id'=>$order_id)));
        	if(!empty($order_shipment)){
        		$shipment_product_ids=$this->OrderShipmentProduct->find('list',array('fields'=>'OrderShipmentProduct.order_product_id','conditions'=>array('OrderShipmentProduct.order_shipment_id'=>$shipment_id)));
        		if(!empty($shipment_product_ids)){
        			$order_product_list=$this->OrderProduct->find('list',array('fields'=>'id,product_code','conditions'=>array('OrderProduct.order_id'=>$order_id,'OrderProduct.id'=>$shipment_product_ids)));
        			$this->OrderProduct->updateAll(array('delivery_status'=>"'4'"),array('OrderProduct.order_id'=>$order_id,'OrderProduct.id'=>$shipment_product_ids));
        		}
        		$shipment_data=array(
        			'id'=>$shipment_id,
        			'status'=>'2'
        		);
        		$this->OrderShipment->save($shipment_data);
        		$order_info=$this->Order->find('first',array('conditions'=>array('Order.id'=>$order_id,'Order.shipping_status'=>'1')));
        		if(!empty($order_info)){
        			$this->Order->update_order(array('id' => $order_id, 'shipping_status' => '0', 'taobao_delivery_send' => '0', 'error_count' => '0', 'shipping_time' =>NULL));
        			$this->OrderAction->update_order_actions($order_id, $this->admin['id'], $order_info['Order']['user_id'], $order_info['Order']['status'], $order_info['Order']['payment_status'], 0, $this->ld['cancel'].$this->ld['set_shipped']);
        		}
        		$result['code']='1';
			$result['message']=$this->ld['modified_successfully'];
        	}
        	die(json_encode($result));
    }
    
    // 订单审核
    function ajax_order_check($order_id = 0){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $result['message']=$this->ld['modify_failed'];
        $order_id=isset($_POST['order_id'])?$_POST['order_id']:0;
        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));
        if(!empty($order_info)){
             if(isset($_POST['order_check'])&&$_POST['order_check'] != ''){
                $order_info['Order']['check_status'] = $_POST['order_check'];
               //pr($order_info);exit();

                $this->Order->save($order_info);
                $result['code']='1';
                $result['message']='修改成功';
            }
        }
        die(json_encode($result));
    }


    /*
    		订单商品状态修改
    */
    function ajax_order_product_status_modify(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $result['message']=$this->ld['modify_failed'];
        $order_id=isset($_POST['order_id'])?$_POST['order_id']:0;
        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id)));
        if(!empty($order_info)){
        	if($order_info['Order']['shipping_status']==0||$order_info['Order']['shipping_status']==3){
	            $order_product_id=isset($_POST['order_product_id'])?$_POST['order_product_id']:0;
	            $order_product_status=isset($_POST['order_product_status'])?$_POST['order_product_status']:0;
	            $order_product_info=$this->OrderProduct->find('first',array('conditions'=>array('OrderProduct.id'=>$order_product_id)));
	            $Resource_info = $this->Resource->getformatcode(array('order_product_status'), $this->backend_locale);
	            if(!empty($order_product_info)&&isset($Resource_info['order_product_status'][$order_product_status])){
				$order_product_data=array(
					'id'=>$order_product_id,
					'delivery_status'=>$order_product_status
				);
				$this->OrderProduct->save($order_product_data);
				$action_note="商品".$order_product_info['OrderProduct']['product_code'].(trim($order_product_info['OrderProduct']['product_number'])!=''?"(".$order_product_info['OrderProduct']['product_number'].")":"").$Resource_info['order_product_status'][$order_product_status];
				$this->OrderProductAction->update_order_product_action(array(
					'order_id'=>$order_id,
					'order_product_id'=>$order_product_id,
					'status'=>$order_product_status,
					'operator_id'=>$this->admin['id'],
					'action_note'=>$action_note
				));
				$result['code']='1';
				$result['message']=$action_note;
				$total = $this->update_order_product($order_id);
				$result['total'] = $total;
				$need_pay = $this->need_pay($order_id);
				$result['need_pay'] = $need_pay;
				$result['insure_fee'] = $order_info['Order']['insure_fee'];
				$result['code']='1';
				$result['message']=$this->ld['order_success_update'];
	                	
				//通知
				if($order_product_status==2)$this->OrderProduct->order_product_notify('order_product_modify',$this->backend_locale,$order_product_info['OrderProduct']['id'],$this);
				
				/*
				$mobile_picker = $this->Operator->find('first', array('conditions' => array('Operator.id'=>$order_product_info['OrderProduct']['picker'])));
				$mobile_QC = $this->Operator->find('first', array('conditions' => array('Operator.id' =>$order_product_info['OrderProduct']['QC'])));
				$mobile_order_manager = $this->Operator->find('first', array('conditions' => array('Operator.id' =>$order_info['Order']['order_manager'])));
				if($order_product_status==2){
					$message="改衣师有新的衣服待修改";
					$mobile=!empty($mobile_picker['Operator']['mobile'])?$mobile_picker['Operator']['mobile']:"";
				}elseif($order_product_status==3){
					$message="质检师有完成的衣服待质检";
					$mobile=!empty($mobile_QC['Operator']['mobile'])?$mobile_QC['Operator']['mobile']:"";
				}elseif($order_product_status==4){
					$message="销售有已完成的订单待发货";
					$mobile=!empty($mobile_order_manager['Operator']['mobile'])?$mobile_order_manager['Operator']['mobile']:"";
				}
				if(!empty($mobile)){
					$this->NotifyTemplateType->set_locale($this->backend_locale);
					$notify_template_info=$this->NotifyTemplateType->typeformat("order_product_modify","sms");
					$notify_template=isset($notify_template_info['sms'])?$notify_template_info['sms']:array();
					if(!empty($notify_template)){
						$data=array(
							"order_code"=>$order_info['Order']['order_code'],
							"product_code"=>$order_product_info['OrderProduct']['product_code'],
							"product_number"=>$order_product_info['OrderProduct']['product_number'],
							"delivery_status"=>$message
						);
						extract($data);
						$sms_content=$notify_template['NotifyTemplateTypeI18n']['param02'];
						eval("\$sms_content = \"$sms_content\";");
						$sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
		                            $sms_result=$this->Notify->send_sms($mobile,$message,$sms_kanal,$this->configs,false);
		                	}
	                     }
	                     */
	                     
            		}
            		$no_wait_delivery_products=$this->OrderProduct->find('count',array('conditions'=>array('order_id'=>$order_id,'del_status'=>'1','delivery_status'=>array('0','1','2'))));
            		if($no_wait_delivery_products==0){
            			$this->Order->save(array('id'=>$order_id,'shipping_status'=>'0'));
            		}
            	}else{
	            	$Resource_info = $this->Resource->getformatcode(array('shipping_status'), $this->backend_locale);
	            	$result['message']="订单".(isset($Resource_info["shipping_status"][$order_info['Order']['shipping_status']])?$Resource_info["shipping_status"][$order_info['Order']['shipping_status']]:'无法操作');
            	}
        }else{
        	$result['message']='订单不存在';
        }
        die(json_encode($result));
    }

    /*
    	批量修改订单商品状态修改
    */
    function barch_ajax_order_product_status_modify(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $result['message']=$this->ld['modify_failed'];
        $ids=$_POST['ids'];
        $res_count=0;
        $orderids=$_POST['orderids'];
        foreach($ids as $k=>$v){
        	$order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $orderids[$k],'Order.shipping_status'=>array(0,3))));
	        if(!empty($order_info)){
	            $order_product_id=$v;
	            $order_product_status=$_POST['status'];
	            $order_product_info=$this->OrderProduct->find('first',array('conditions'=>array('OrderProduct.id'=>$order_product_id)));
	            $Resource_info = $this->Resource->getformatcode(array('order_product_status'), $this->backend_locale);
	            if(!empty($order_product_info)&&isset($Resource_info['order_product_status'][$order_product_status])){
		                $order_product_data=array(
		                    'id'=>$order_product_id,
		                    'delivery_status'=>$order_product_status
		                );
		                $this->OrderProduct->save($order_product_data);
		                $action_note="商品".$order_product_info['OrderProduct']['product_code'].(trim($order_product_info['OrderProduct']['product_number'])!=''?"(".$order_product_info['OrderProduct']['product_number'].")":"").$Resource_info['order_product_status'][$order_product_status];
		                $this->OrderProductAction->update_order_product_action(array(
		                    'order_id'=>$order_id,
		                    'order_product_id'=>$order_product_id,
		                    'status'=>$order_product_status,
		                    'operator_id'=>$this->admin['id'],
		                    'action_note'=>$action_note
		                ));
		                $result['code']='1';
		                $result['message']=$action_note;
		                $total = $this->update_order_product($order_id);
		                $result['total'] = $total;
		                $need_pay = $this->need_pay($order_id);
		                $result['code']='1';
		                
				$no_wait_delivery_products=$this->OrderProduct->find('count',array('conditions'=>array('order_id'=>$order_id,'del_status'=>'1','delivery_status'=>array('0','1','2'))));
				if($no_wait_delivery_products==0){
					$this->Order->save(array('id'=>$order_id,'shipping_status'=>'0'));
				}
		                $res_count++;
	            }
	        }
        }
        $result['message']="修改成功".$res_count."条";
        die(json_encode($result));
    }

    function ajax_order_product_modify(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        $result['code']='0';
        $result['message']=$this->ld['modify_failed'];
        $order_id=isset($_REQUEST['order_id'])?$_REQUEST['order_id']:0;
        $order_info = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id,'Order.shipping_status'=>array(0,3,6))));
        $showtime=date("Y-m-d", strtotime('+7 days'));
        if(!empty($order_info)){
            $order_product_id=isset($_REQUEST['order_product_id'])?$_REQUEST['order_product_id']:0;
            $order_product_info=$this->OrderProduct->find('first',array('conditions'=>array('OrderProduct.id'=>$order_product_id,'OrderProduct.delivery_status <>'=>5)));
            if(!empty($order_product_info)){
                $resource_code="order_product_modify";
                $information_resources_info = $this->InformationResource->information_formated(array($resource_code), $this->backend_locale);
                $resource_info=isset($information_resources_info[$resource_code])?$information_resources_info[$resource_code]:array();
                $order_product_additional_info=$this->OrderProductAdditional->find('first',array('conditions'=>array('OrderProductAdditional.order_product_id'=>$order_product_id)));
                if(isset($_POST['data'])){
                    $modify_data=json_encode($_POST['data']);
                    $order_product_additional_data=array(
                        'id'=>isset($order_product_additional_info['OrderProductAdditional'])?$order_product_additional_info['OrderProductAdditional']['id']:0,
                        'order_id'=>$order_id,
                        'order_product_id'=>$order_product_id,
                        'value'=>$modify_data
                    );
                    $this->OrderProductAdditional->save($order_product_additional_data);
                    $action_note="更新商品".$order_product_info['OrderProduct']['product_code'].(trim($order_product_info['OrderProduct']['product_number'])!=''?"(".$order_product_info['OrderProduct']['product_number'].")":"")."附加信息";
                    $order_product_data=array(
                        'id'=>$order_product_id,
                        'picker'=>isset($_POST['order_product_picker'])?$_POST['order_product_picker']:'0',
                        'QC'=>isset($_POST['order_product_qc'])?$_POST['order_product_qc']:'0',
                        'pre_delivery_time'=>$showtime
                    );
                    $this->OrderProduct->save($order_product_data);
                    $operator_ids=array();
                    if(intval($order_product_data['picker'])>0){
                        $operator_ids[]=$order_product_data['picker'];
                    }
                    if(intval($order_product_data['QC'])>0){
                        $operator_ids[]=$order_product_data['QC'];
                    }
                    if(!empty($operator_ids)){
                        $operator_list = $this->Operator->find('list',array('fields' => array('Operator.id','Operator.name'),'conditions'=>array('id'=>$operator_ids,'Operator.status'=>'1')));
                        if($order_product_data['picker']>0){
                            $action_note.=",指定改衣师:".(isset($operator_list[$order_product_data['picker']])?$operator_list[$order_product_data['picker']]:'-');
                        }
                        if($order_product_data['QC']>0){
                            $action_note.=",指定质检师:".(isset($operator_list[$order_product_data['QC']])?$operator_list[$order_product_data['QC']]:'-');
                        }
                    }
                    $this->OrderProductAction->update_order_product_action(array(
                        'order_id'=>$order_id,
                        'order_product_id'=>$order_product_id,
                        'status'=>$order_product_info['OrderProduct']['delivery_status'],
                        'operator_id'=>$this->admin['id'],
                        'action_note'=>$action_note
                    ));
                    $result['code']='1';
                    $result['message']=$this->ld['order_success_update'];
                    $total = $this->update_order_product($order_id);
                    $result['total'] = $total;
                    $need_pay = $this->need_pay($order_id);
                    $result['need_pay'] = $need_pay;
                    $result['insure_fee'] = $order_info['Order']['insure_fee'];
                }else{
                    $result['code']='1';
                    $result['data']=array(
                        'order'=>$order_info['Order'],
                        'order_product'=>$order_product_info['OrderProduct'],
                        'order_product_additional'=>isset($order_product_additional_info['OrderProductAdditional'])?$order_product_additional_info['OrderProductAdditional']:array(),
                        'resource_info'=>$resource_info
                    );
                    $result['message']='';
                }
            }
        }
        die(json_encode($result));
    }
    
    /*
    		按条码获取订单商品详情
    */
    function ajax_product_number_detail(){
    		Configure::write('debug', 1);
        	$this->layout = 'ajax';
        	
		$result=array();
		$result['code']='0';
		$result['data']=array();
        	
        	$product_number=isset($_REQUEST['product_number'])?trim($_REQUEST['product_number']):'';
        	if($product_number!=''){
			$order_product_cond=array();
			$order_product_cond['OrderProduct.product_number']=$product_number;
			$order_product_cond['OrderProduct.del_status']='1';
			$order_product_data = $this->OrderProduct->find('first', array('conditions' => $order_product_cond));
			if(!empty($order_product_data)){
				$result['code']='1';
				$result['data']=$order_product_data;
			}
		}
		die(json_encode($result));
    }
    

    /*
    		订单确认通知
    */
    function notify_order_confirm($order_id=0){
        $this->loadModel('SynchroUser');
        $order_data=$this->Order->find('first',array('conditions'=>array('Order.id'=>$order_id)));
        if(empty($order_data))return false;
        $synchro_user = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.status' => 1, 'SynchroUser.type' => 'wechat','SynchroUser.user_id'=>$order_data['Order']['user_id'])));
        if(empty($synchro_user))return false;
        $touser=$synchro_user['SynchroUser']['account'];
        $this->NotifyTemplateType->set_locale($this->backend_locale);
        $notify_template_info=$this->NotifyTemplateType->typeformat("order_confirm","wechat");
        $notify_template=isset($notify_template_info['wechat'])?$notify_template_info['wechat']:array();
        $wechat_params=$this->NotifyTemplateType->wechatparamsformat($notify_template);
        $action_content="您的订单已确认，即将安排送货人员配送";
        $order_code=$order_data['Order']['order_code'];
        $product_name=isset($order_data['OrderProduct'][0]['product_name'])?$order_data['OrderProduct'][0]['product_name']:'';
        $product_quantity=isset($order_data['OrderProduct'][0]['product_quntity'])?$order_data['OrderProduct'][0]['product_quntity']:1;
        $order_consignee=$order_data['Order']['consignee'];
        $order_address=$order_data['Order']['province'].$order_data['Order']['city'].$order_data['Order']['address'];
        $action_desc="为了送货人员能及时联系您，请保持通讯设备畅通";
        $wechat_message=array();
        foreach($wechat_params as $k=>$v){
            $wechat_message[$k]=array(
                'value'=>isset($$v)?$$v:''
            );
        }
        $wechat_post=array(
            'touser'=>$touser,
            'template_id'=>$notify_template['NotifyTemplateTypeI18n']['param03'],
            'url'=>$this->server_host.'/orders/view/'.$order_id,
            'data'=>$wechat_message
        );
        $this->Notify->wechat_message($wechat_post);
    }

    /*
    		订单发货通知
    */
    function notify_order_delivery($order_id=0){
    		$order_data=$this->Order->find('first',array('conditions'=>array('Order.id'=>$order_id)));
        	if(empty($order_data))return;
    		
    		extract($order_data['Order'],EXTR_PREFIX_ALL,'Order');
    		
		$this->NotifyTemplateType->set_locale($this->backend_locale);
		$notify_template=$this->NotifyTemplateType->typeformat('order_delivery',array('wechat','sms'));
    		if(empty($notify_template))return;
    		
    		$shipment_conditions=array();
    		$shipment_conditions['OrderShipment.order_id']=$order_id;
    		$shipment_conditions['OrderShipment.status']='1';
    		$shipment_conditions['OrderShipment.created >=']=date('Y-m-d H:i:s',strtotime('-10 minutes'));
    		$order_shipment_info=$this->OrderShipment->find('first',array('conditions'=>$shipment_conditions,'order'=>'id DESC'));
    		if(empty($order_shipment_info))return;
    		
    		$order_shipment_id=$order_shipment_info['OrderShipment']['id'];
    		$order_shipment_id=43;
    		$order_shipment_detail=$this->OrderShipmentProduct->find('all',array('conditions'=>array('OrderShipmentProduct.order_shipment_id'=>$order_shipment_id)));
    		$order_content="";
    		if(!empty($order_shipment_detail)){
    			$order_shipment_items=array();
    			foreach($order_shipment_detail as $v){
    				$order_shipment_items[]=$v['OrderProduct']['product_name'].'x'.$v['OrderShipmentProduct']['product_quantity'];
    			}
    			$order_content=implode(' ',$order_shipment_items);
    		}else{
    			$order_content=$order_data['Order']['order_code'];
    		}
    		$logistics_company=isset($order_shipment_info['LogisticsCompany'])?$order_shipment_info['LogisticsCompany']['name']:'';
        	$invoice_no=$order_shipment_info['OrderShipment']['invoice_no'];
        	
    		$shipment_detail_list=array();
    		if(trim($order_shipment_info['OrderShipment']['consignee'])!='')$shipment_detail_list[]=$order_shipment_info['OrderShipment']['consignee'];
    		if(trim($order_shipment_info['OrderShipment']['mobile'])!='')$shipment_detail_list[]=$order_shipment_info['OrderShipment']['mobile'];
    		if(trim($order_shipment_info['OrderShipment']['province'])!='')$shipment_detail_list[]=$order_shipment_info['OrderShipment']['province'];
    		if(trim($order_shipment_info['OrderShipment']['city'])!='')$shipment_detail_list[]=$order_shipment_info['OrderShipment']['city'];
    		if(trim($order_shipment_info['OrderShipment']['address'])!='')$shipment_detail_list[]=$order_shipment_info['OrderShipment']['address'];
    		$shipment_detail=implode(' ',$shipment_detail_list);
    		
    		$server_host=isset($this->server_host)?$this->server_host:'';
		if($server_host==''){
			$http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
			$host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
			$post=isset($_SERVER['SERVER_PORT'])?$_SERVER['SERVER_PORT']:'80';
			$server_host = $http_type.$host.($post!='80'&&$post!='443'?(":".$post):'');
		}
		$this->loadModel('SynchroUser');
		$order_user_id=$order_data['Order']['user_id'];
		$order_user_detail=$this->User->find('first',array('fields'=>'User.id,User.name,User.first_name,User.mobile','conditions'=>array('User.id'=>$order_user_id)));
		$WechatUser_detail=$this->SynchroUser->find('first',array('fields'=>'SynchroUser.id,SynchroUser.account','conditions'=>array('SynchroUser.user_id'=>$order_user_id,'SynchroUser.status'=>'1')));
    		if(!empty($order_user_detail)){
			extract($order_user_detail['User'],EXTR_PREFIX_ALL,'User');
		}
		if(!empty($order_user_detail)&&!empty($WechatUser_detail)){
			$notify_template_detail=isset($notify_template['wechat'])?$notify_template['wechat']:array();
			if(empty($notify_template_detail))return;
			$action_content=trim(strip_tags($notify_template_detail['NotifyTemplateTypeI18n']['param01']));
			@eval("\$action_content = \"$action_content\";");
			$action_desc=trim(strip_tags($notify_template_detail['NotifyTemplateTypeI18n']['param02']));
			@eval("\$action_desc = \"$action_desc\";");
			$wechat_params=$this->NotifyTemplateType->wechatparamsformat($notify_template_detail);
			$wechat_message=array();
   			foreach($wechat_params as $k=>$v){
   				$wechat_message[$k]=array(
   					'value'=>isset($$v)?$$v:''
   				);
   			}
			$wechat_post=array(
	   			'touser'=>$WechatUser_detail['SynchroUser']['account'],
	   			'template_id'=>$notify_template_detail['NotifyTemplateTypeI18n']['param03'],
	   			'url'=>$server_host.'/orders/view/'.$order_id,
	   			'data'=>$wechat_message
	   		);
			$this->Notify->wechat_message($wechat_post);
		}else if(!empty($order_user_detail)&&trim($order_user_detail['User']['mobile'])!=''){
			$notify_template_detail=isset($notify_template['sms'])?$notify_template['sms']:array();
			if(empty($notify_template_detail))return;
			$sms_content=trim(strip_tags($notify_template_detail['NotifyTemplateTypeI18n']['param02']));
			@eval("\$sms_content = \"$sms_content\";");
			$sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
			$sms_result=$this->Notify->send_sms($order_user_detail['User']['mobile'],$sms_content,$sms_kanal,isset($this->configs)?$this->configs:array());
		}
    }

    /*
    		订单取消通知
    */
    function notify_order_cancel($order_id=0,$action_desc=''){
        $this->loadModel('SynchroUser');
        $order_data=$this->Order->find('first',array('conditions'=>array('Order.id'=>$order_id)));
        if(empty($order_data))return false;
        $synchro_user = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.status' => 1, 'SynchroUser.type' => 'wechat','SynchroUser.user_id'=>$order_data['Order']['user_id'])));
        if(empty($synchro_user))return false;
        $touser=$synchro_user['SynchroUser']['account'];
        $this->NotifyTemplateType->set_locale($this->backend_locale);
        $notify_template_info=$this->NotifyTemplateType->typeformat("order_cancel","wechat");
        $notify_template=isset($notify_template_info['wechat'])?$notify_template_info['wechat']:array();
        $wechat_params=$this->NotifyTemplateType->wechatparamsformat($notify_template);
        $action_content="您的订单已取消";
        $order_code=$order_data['Order']['order_code'];
        $order_subtotal=$order_data['Order']['total'];
        $wechat_message=array();
        foreach($wechat_params as $k=>$v){
            $wechat_message[$k]=array(
                'value'=>isset($$v)?$$v:''
            );
        }
        $wechat_post=array(
            'touser'=>$touser,
            'template_id'=>$notify_template['NotifyTemplateTypeI18n']['param03'],
            'url'=>$this->server_host.'/orders/view/'.$order_id,
            'data'=>$wechat_message
        );
        $this->Notify->wechat_message($wechat_post);
    }

    /*
    		订单导出至供应商
    */
    function import_to_vendor(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(file_exists(WWW_ROOT."data/api/config.php")){
            include_once(WWW_ROOT."data/api/config.php");//引入配置文件
        }else{
            die(json_encode(array('flag'=>'0','message'=>'API配置文件不存在')));
        }
        $result=array();
        $result['flag']='0';
        $result['message']='';
        $login_data=array('login_name'=>(defined('api_login')?api_login:''),'login_password'=>(defined('api_password')?api_password:''));
        $server_url=defined('api_server_url')?api_server_url:'';
        $login_result=$this->curl($server_url."/apis/UserLoad",$login_data);
        if(isset($login_result['code'])&&$login_result['code']=='1'){
            $api_token=isset($login_result['message'])?$login_result['message']:'';
        }else{
            die(json_encode(array('flag'=>'0','message'=>isset($login_result['message'])?$login_result['message']:'API应用验证码不能为空')));
        }

        $order_ids=isset($_POST['checkboxes'])?$_POST['checkboxes']:array();
        if(empty($order_ids)){
            die(json_encode(array('flag'=>'0','message'=>'Data Error')));
        }
        $order_conditions=array();
        $order_conditions['Order.id']=$order_ids;
        $order_conditions['Order.payment_status']='2';
        $order_conditions['Order.status']='1';
        $order_infos=$this->Order->find('all',array('conditions'=>$order_conditions,"order"=>"Order.payment_time"));
        if(!empty($order_infos)){
            $error_message="";$success=0;$error=0;
            $LogisticsCompany_data=$this->LogisticsCompany->find('list',array("fields"=>"LogisticsCompany.id,LogisticsCompany.code","conditions"=>array("LogisticsCompany.fettle"=>1)));
            $Shipping_data=$this->Shipping->find('list',array("fields"=>"Shipping.id,Shipping.code","conditions"=>array("Shipping.status"=>'1')));
            $payment_data = $this->Payment->find('list', array('fields' => array('Payment.id', 'Payment.code'), 'conditions' => array('Payment.status' => 1)));
            foreach($order_infos as $v){
                $order_code=$v['Order']['order_code'];//订单号
                $order_data=$v['Order'];
                $order_product_data=$v['OrderProduct'];
                $order_data['logistics_company_code']=isset($LogisticsCompany_data[$order_data['logistics_company_id']])?$LogisticsCompany_data[$order_data['logistics_company_id']]:'';
                $order_data['shipping_code']=isset($Shipping_data[$order_data['shipping_id']])?$Shipping_data[$order_data['shipping_id']]:'';
                $order_data['payment_code']=isset($payment_data[$order_data['payment_id']])?$payment_data[$order_data['payment_id']]:'';
                if(!empty($order_product_data)){
                    foreach($order_product_data as $kk=>$vv){
                        if($vv['del_status']!='1')unset($order_product_data[$kk]);
                    }
                }
                if(empty($order_product_data)){
                    $error_message.=$order_code."无有效商品";
                }else{
                    $order_data['token']=$api_token;
                    $order_data['OrderProduct']=json_encode($order_product_data);
                    $api_result=$this->curl($server_url."/apis/import_ecommerce_order",$order_data);
                    if(isset($api_result['code'])&&$api_result['code']=='1'){
                        $success++;
                    }else{
                        $error++;
                    }
                    $error_message.=isset($api_result['message'])?$api_result['message']:'';
                    if(isset($api_result['code'])&&$api_result['code']=='-1'){
                        break;
                    }
                }
            }
            if($success>0){
                $result['flag']='1';
            }
            $result['message']=$error_message;
        }else{
            $result['message']='无有效订单';
        }
        die(json_encode($result));
    }


    protected function curl($url, $postFields = null){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if (is_array($postFields) && 0 < count($postFields))
        {
            $postBodyString = "";
            $postMultipart = false;
            foreach ($postFields as $k => $v)
            {
                if("@" != substr($v, 0, 1))//判断是不是文件上传
                {
                    $postBodyString .= "$k=" . urlencode($v) . "&";
                }
                else//文件上传用multipart/form-data，否则用www-form-urlencoded
                {
                    $postMultipart = true;
                }
            }
            unset($k, $v);
            curl_setopt($ch, CURLOPT_POST, true);
            if ($postMultipart)
            {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
            }
            else
            {
                curl_setopt($ch, CURLOPT_POSTFIELDS, substr($postBodyString,0,-1));
            }
        }
        $reponse = curl_exec($ch);
        if (curl_errno($ch))
        {
            throw new Exception(curl_error($ch),0);
        }
        else
        {
            $httpStatusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (200 !== $httpStatusCode)
            {
                throw new Exception($reponse,$httpStatusCode);
            }
        }
        curl_close($ch);
        return json_decode($reponse,true);
    }
}