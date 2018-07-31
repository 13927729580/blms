<?php

class interfacesController extends AppController
{
    public $name = 'JingdongOrders';
//接口返回参数
public function index()
{
    $a = $this->aaa('360buy_promotion_search');
    pr($a);
    die;
}

    public function aaa($str)
    {
        //begin商品API
    //商品ID查询,通过条件查询商品ID列表
    if ($str == '360buy_ware_ids_search') {
        return $ware_ids_search_response = array('ware_ids' => array(
                                    'vender_id' => 20032,
                                    'ware_total' => 5,
                                    'ware_id_list' => array('1100011962','1100017482','1100018827','1100018300','1100017476'), ),
                                    'code' => '0', );
    }
    //商品信息查询,通过商品ID查询商品信息
    if ($str == '360buy_ware_search') {
        return $ware_search_response = array('ware_search' => array(
                            'vender_id' => 20032,
                            'ware_total' => 5,
                            'wares' => array(
                                    array('ware_id' => '1100011962','ware_outer_id' => '','vender_id' => '',
                                            'ware_name' => 'sop1个sku','product_no' => '','ware_state' => '在售',
                                            'jd_price' => '100.00','ware_stocks_total' => '',
                                            'delisting_or_listing_time' => '2011-05-13 13:38:47', ),
                                    array('ware_id' => '1100017482','ware_outer_id' => '','vender_id' => '',
                                            'ware_name' => 'sss','product_no' => '','ware_state' => '在售',
                                            'jd_price' => '299.00','ware_stocks_total' => '',
                                            'delisting_or_listing_time' => '2011-05-23 15:36:51', ),
                                    array('ware_id' => '1100018827','ware_outer_id' => '','vender_id' => '',
                                            'ware_name' => '增值税发票测试1','product_no' => '','ware_state' => '在售',
                                            'jd_price' => '300.00','ware_stocks_total' => '',
                                              'delisting_or_listing_time' => '2011-05-13 14:54:46', ),
                                    array('ware_id' => '1100018300','ware_outer_id' => '','vender_id' => '',
                                            'ware_name' => 'test1','product_no' => '',
                                            'ware_state' => '在售','jd_price' => '99.00','ware_stocks_total' => '',
                                              'delisting_or_listing_time' => '2011-04-07 14:14:05', ),
                                      array('ware_id' => '1100017476','ware_outer_id' => '','vender_id' => '',
                                            'ware_name' => '男士衬衫','product_no' => '',
                                              'ware_state' => '在售','jd_price' => '125.00','ware_stocks_total' => '',
                                            'delisting_or_listing_time' => '2011-04-28 14:31:19', ), ), ),
                                    'code' => '0', );
    }
    //商品上下架,通过商品id对商品上下架
    if ($str == '360buy_ware_state_update') {
        return $ware_state_update_response = array(
                            'vender_id' => '20032',
                            'modified' => '2011-06-21 11:57:52',
                            'ware_id' => '1100000001',
                            'code' => '0', );
    }
    //sku详细信息的查询,通过检索条件，检索sku的详细信息
    if ($str == '360buy_ware_sku_search') {
        return $ware_sku_search_response = array(
                                'sku_search' => array('vender_id' => '','sku_total' => 1,
                                'sku_info_list' => array(
                                            array(
                                            'sku_id' => '',
                                            'outer_sku_id' => '',
                                            'ware_id' => '',
                                            'sku_info_list' => '',
                                            'jd_sku_price' => '',
                                            'sku_name' => '',
                                            'created_time' => '',
                                            'modified_time' => '', ), ), ),
                                'code' => '0', );
    }
    //sku详细信息的更新,更改SKU详细信息接口
    if ($str == '360buy_ware_sku_update') {
        return $ware_sku_update_response = array(
                                'vender_id' => '20032',
                                'modified' => '2011-06-21 13:46:58',
                                'sku_id' => '1100036864',
                                'code' => '0', );
    }
    //end商品API

    //begin订单API
    //获取面单打印数据,根据订单id获取面单打印数据
    if ($str == '360buy_order_print_data_get') {
        return $order_printdata_response = array(
                    'order_printdata' => array(
                                    'id' => '25003765',
                                    'cod_time_name' => '只双休日、假日送货(工作日不用送)',
                                    'should_pay' => '297.00','remark' => '',
                                    'create_date' => '2011-04-29 16:34:00',
                                    'out_bound_date' => '2011-08-05 14:47:49',
                                    'bf_deli_good_glag' => '否',
                                    'cky2_name' => 'LBP - 北京',
                                    'sorting_code' => '',
                                    'payment_typeStr' => '自提',
                                    'partner' => '中关村自提点',
                                    'generade' => '/9j/4AAQSkZJRgABAgEBLAEsAAD/2wBDAAgGBgcGBQgHBwcJCQgKDBQNDAsLDBkSEw8UH',
                                    'items_count' => '1',
                                    'order_item' => array(
                                                array(
                                                'ware' => '1100038509',
                                                'jd_price' => '99.00',
                                                'ware_name' => '圆领印花T恤  XXL',
                                                'price' => '297.00',
                                                'num' => '3', ), ),
                                    'Consignee' => array(
                                                'cons_name' => 'ochirly',
                                                'cons_address' => '北京海淀区三环到四环之间广东省广州市天河区中旅大厦30A',
                                                'cons_phone' => '010-12345678',
                                                'cons_handset' => '13800000000', ), ),
                    'code' => '0', );
    }
    //面单打印
    if ($str == '360buy_order_print') {
        return $order_print_response = array(
                    'print_result' => array(
                                    'html_content' => '',
                                    'image_data' => '', ),
                    'code' => '0', );
    }
    //SOP发货操作
    if ($str == '360buy_order_sop_delivery') {
        return $order_sop_delivery_response = array(
                    'vender_id' => '20032',
                    'modified' => '2011-06-22 09:50:11',
                    'order_id' => 16050909,
                    'code' => '0', );
    }
    //订单lbp出库
    if ($str == '360buy_order_lbp_outstorage') {
        return $order_lbp_outstorage_response = array(
                    'vender_id' => '20032',
                    'modified' => '2011-06-22 09:46:58',
                    'order_id' => 16050909,
                    'code' => '0', );
    }
    //订单sop出库
    if ($str == '360buy_order_sop_outstorage') {
        return $order_sop_outstorage_response = array(
                    'vender_id' => '20032',
                    'modified' => '2011-06-22 09:47:34',
                    'order_id' => 16050909,
                    'code' => '0', );
    }
    //订单sopl出库
    if ($str == '360buy_order_sopl_outstorage') {
        return $order_sopl_outstorage_response = array(
                    'vender_id' => '20032',
                    'modified' => '2011-06-22 09:48:28',
                    'order_id' => 16050909,
                    'code' => '0', );
    }
    //获取单个订单信息-非生产数据,根据订单id，进行交易的详细信息的检索
    if ($str == '360buy_order_get') {
        return $order_get_response = array(
                            'order' => array(
                                    'orders' => array(
                                        array(
                                            'order_id' => '',
                                            'vender_id' => '',
                                            'ware_infos' => array(
                                                        array(
                                                        'ware_id' => '',
                                                        'ware_out_id' => '',
                                                        'ware_name' => '',
                                                        'sku_out_id' => '',
                                                        'sku_id' => '',
                                                        'ware_total' => '',
                                                        'jd_price' => '',
                                                        'product_no' => '',
                                                        'ware_discount_fee' => '',
                                                        'gift_point' => '', ), ),
                                            'pay_type' => '',
                                            'freight_price' => '',
                                            'ware_total_price' => '',
                                            'order_total_price' => '',
                                            'payment' => '','order_state' => '',
                                            'fact_freight_price' => '',
                                            'delivery_date_remark' => '',
                                            'total_discount_fee' => '',
                                            'invoice_info' => '',
                                            'buyer_order_remark' => '',
                                            'seller_order_remark' => '',
                                            'order_start_time' => '',
                                            'order_end_time' => '', ), ),
                                    'order_total' => 1, ),
                            'code' => '0', );
    }
    //获取一段时间内订单信息-非生产数据,根据下单的时间以及状态，进行交易信息的检索
    if ($str == '360buy_order_search') {
        return $order_search_on_response = array(
                                    'order' => array(
                                                'orders' => array(
                                                    array(
                                                        'order_id' => '25006408',
                                                        'vender_id' => '10002',
                                                        'ware_infos' => array(
                                                            array(
                                                                'ware_id' => '',
                                                                'ware_out_id' => '',
                                                                'ware_name' => '',
                                                                'sku_out_id' => '',
                                                                'sku_id' => '',
                                                                'ware_total' => '',
                                                                'jd_price' => '',
                                                                'product_no' => '',
                                                                'ware_discount_fee' => '',
                                                                'gift_point' => '', ), ),
                                                        'pay_type' => '在线支付',
                                                        'freight_price' => '0.00',
                                                        'ware_total_price' => '',
                                                        'order_total_price' => '',
                                                        'payment' => '',
                                                        'consignee_info' => array(
                                                                'user_name' => '',
                                                                'user_address' => '',
                                                                'user_post' => '',
                                                                'user_telephone' => '',
                                                                'user_mobile_phone' => '',
                                                                'user_email' => '', ),
                                                        'order_state' => '',
                                                        'fact_freight_price' => '',
                                                        'delivery_date_remark' => '',
                                                        'total_discount_fee' => '',
                                                        'invoice_info' => '',
                                                        'buyer_order_remark' => '',
                                                        'seller_order_remark' => '',
                                                        'order_start_time' => '',
                                                        'order_end_time' => '', ),
                                                    array(
                                                        'order_id' => '25006407',
                                                        'vender_id' => '10002',
                                                        'ware_infos' => array(
                                                            array(
                                                                'ware_id' => '',
                                                                'ware_out_id' => '',
                                                                'ware_name' => '',
                                                                'sku_out_id' => '',
                                                                'sku_id' => '',
                                                                'ware_total' => '',
                                                                'jd_price' => '',
                                                                'product_no' => '',
                                                                'ware_discount_fee' => '',
                                                                'gift_point' => '', ), ),
                                                        'pay_type' => '在线支付',
                                                        'freight_price' => '0.00',
                                                        'ware_total_price' => '',
                                                        'order_total_price' => '',
                                                        'payment' => '',
                                                        'consignee_info' => array(
                                                                'user_name' => '',
                                                                'user_address' => '',
                                                                'user_post' => '',
                                                                'user_telephone' => '',
                                                                'user_mobile_phone' => '',
                                                                'user_email' => '', ),
                                                        'order_state' => '',
                                                        'fact_freight_price' => '',
                                                        'delivery_date_remark' => '',
                                                        'total_discount_fee' => '',
                                                        'invoice_info' => '',
                                                        'buyer_order_remark' => '',
                                                        'seller_order_remark' => '',
                                                        'order_start_time' => '',
                                                        'order_end_time' => '', ), ),
                                        'order_total' => 2, ),
                                'code' => '0', );
    }
    //获取单个订单信息-生产数据,根据订单id，查询需生产的订单的详细信息的检索，可获取货号以及商家优惠明细（单品促销、团购优惠）
    if ($str == '360buy_new_order_get') {
        return $new_order_get_response = array(
                                    'order' => array(
                                            'orderInfo' => array(
                                                    'vender_id' => '20361',
                                                    'order_id' => '25002825',
                                                    'order_state' => 'WAIT_SELLER_STOCK_OUT',
                                                    'pay_type' => '1-货到付款',
                                                    'delivery_type' => '只双休日、假日送货(工作日不用送)',
                                                    'order_payment' => '15643.10',
                                                    'seller_discount' => '1.00',
                                                    'invoice_info' => '发票类型:普通发票;发票抬头:个人;发票内容:明细;',
                                                    'order_remark' => '',
                                                    'order_start_time' => '2011-04-08 17:40:00',
                                                    'consignee_info' => array(
                                                                'province' => '北京',
                                                                'city' => '海淀区',
                                                                'county' => '三环以内',
                                                                'fullname' => 'ai',
                                                                'telephone' => '010-12345678',
                                                                'mobile' => '               ',
                                                                'full_address' => '北京海淀区三环以内3333', ),
                                                    'item_info_list' => array(
                                                            array(
                                                                'sku_id' => '1100037898',
                                                                'ware_id' => '',
                                                                'jd_price' => '400.00',
                                                                'sku_name' => '大苹果',
                                                                'product_no' => '',
                                                                'gift_point' => '0',
                                                                'outer_sku_id' => '',
                                                                'item_total' => '1', ), ), ),
                                            'coupon_detail_list' => array(
                                                        array(
                                                            'order_id' => '25002825',
                                                            'sku_id' => '1100037898',
                                                            'coupon_type' => '29-团购优惠',
                                                            'coupon_price' => '1.00', ),
                                                        array(
                                                            'order_id' => '25002825',
                                                            'sku_id' => '1100037898',
                                                            'coupon_type' => '29-团购优惠',
                                                            'coupon_price' => '1.00', ), ), ),
                                        'code' => '0', );
    }
    //获取一段时间内订单信息-生产数据,根据更新时间以及状态，进行交易信息的检索
    if ($str == '360buy_order_fast_search') {
        return $order_search_offline_response = array(
                                    'order_search' => array(
                                            'order_total' => 19,
                                            'order_info_list' => array(
                                                                array(
                                                                'order_id' => '25003603',
                                                                'vender_id' => '10002',
                                                                'pay_type' => '5-公司转帐',
                                                                'order_total_price' => '',
                                                                'order_seller_price' => '',
                                                                'order_payment' => '',
                                                                'freight_price' => '',
                                                                'seller_discount' => '',
                                                                'order_state' => '',
                                                                'order_state_remark' => '',
                                                                'delivery_type' => '',
                                                                'invoice_info' => '',
                                                                'order_remark' => '',
                                                                'order_start_time' => '',
                                                                'order_end_time' => '',
                                                                'item_info_list' => array(array()), ),
                                                                array(
                                                                'order_id' => '25003604',
                                                                'vender_id' => '10002',
                                                                'pay_type' => '2-邮局汇款',
                                                                'order_total_price' => '',
                                                                'order_seller_price' => '',
                                                                'order_payment' => '',
                                                                'freight_price' => '',
                                                                'seller_discount' => '',
                                                                'order_state' => '',
                                                                'order_state_remark' => '',
                                                                'delivery_type' => '',
                                                                'invoice_info' => '',
                                                                'order_remark' => '',
                                                                'order_start_time' => '',
                                                                'order_end_time' => '',
                                                                'item_info_list' => array(array()), ),
                                                                array(
                                                                'order_id' => '25003605',
                                                                'vender_id' => '10002',
                                                                'pay_type' => '3-自提',
                                                                'order_total_price' => '',
                                                                'order_seller_price' => '',
                                                                'order_payment' => '',
                                                                'freight_price' => '',
                                                                'seller_discount' => '',
                                                                'order_state' => '',
                                                                'order_state_remark' => '',
                                                                'delivery_type' => '',
                                                                'invoice_info' => '',
                                                                'order_remark' => '',
                                                                'order_start_time' => '',
                                                                'order_end_time' => '',
                                                                'item_info_list' => array(array()), ),
                                                                array(
                                                                'order_id' => '25003607',
                                                                'vender_id' => '10002',
                                                                'pay_type' => '4-在线支付',
                                                                'order_total_price' => '',
                                                                'order_seller_price' => '',
                                                                'order_payment' => '',
                                                                'freight_price' => '',
                                                                'seller_discount' => '',
                                                                'order_state' => '',
                                                                'order_state_remark' => '',
                                                                'delivery_type' => '',
                                                                'invoice_info' => '',
                                                                'order_remark' => '',
                                                                'order_start_time' => '',
                                                                'order_end_time' => '',
                                                                'item_info_list' => array(array()), ),
                                                                array(
                                                                'order_id' => '25003608',
                                                                'vender_id' => '10002',
                                                                'pay_type' => '4-在线支付',
                                                                'order_total_price' => '',
                                                                'order_seller_price' => '',
                                                                'order_payment' => '',
                                                                'freight_price' => '',
                                                                'seller_discount' => '',
                                                                'order_state' => '',
                                                                'order_state_remark' => '',
                                                                'delivery_type' => '',
                                                                'invoice_info' => '',
                                                                'order_remark' => '',
                                                                'order_start_time' => '',
                                                                'order_end_time' => '',
                                                                'item_info_list' => array(array()), ),
                                                                array(
                                                                'order_id' => '25003609',
                                                                'vender_id' => '10002',
                                                                'pay_type' => '6-银行卡转帐',
                                                                'order_total_price' => '',
                                                                'order_seller_price' => '',
                                                                'order_payment' => '',
                                                                'freight_price' => '',
                                                                'seller_discount' => '',
                                                                'order_state' => '',
                                                                'order_state_remark' => '',
                                                                'delivery_type' => '',
                                                                'invoice_info' => '',
                                                                'order_remark' => '',
                                                                'order_start_time' => '',
                                                                'order_end_time' => '',
                                                                'item_info_list' => array(array()), ),
                                                                array(
                                                                'order_id' => '25003613',
                                                                'vender_id' => '10002',
                                                                'pay_type' => '1-货到付款',
                                                                'order_total_price' => '',
                                                                'order_seller_price' => '',
                                                                'order_payment' => '',
                                                                'freight_price' => '',
                                                                'seller_discount' => '',
                                                                'order_state' => '',
                                                                'order_state_remark' => '',
                                                                'delivery_type' => '',
                                                                'invoice_info' => '',
                                                                'order_remark' => '',
                                                                'order_start_time' => '',
                                                                'order_end_time' => '',
                                                                'item_info_list' => array(array()), ),
                                                                array(
                                                                'order_id' => '25003761',
                                                                'vender_id' => '10002',
                                                                'pay_type' => '1-货到付款',
                                                                'order_total_price' => '',
                                                                'order_seller_price' => '',
                                                                'order_payment' => '',
                                                                'freight_price' => '',
                                                                'seller_discount' => '',
                                                                'order_state' => '',
                                                                'order_state_remark' => '',
                                                                'delivery_type' => '',
                                                                'invoice_info' => '',
                                                                'order_remark' => '',
                                                                'order_start_time' => '',
                                                                'order_end_time' => '',
                                                                'item_info_list' => array(array()), ),
                                                                array(
                                                                'order_id' => '25003765',
                                                                'vender_id' => '10002',
                                                                'pay_type' => '3-自提',
                                                                'order_total_price' => '',
                                                                'order_seller_price' => '',
                                                                'order_payment' => '',
                                                                'freight_price' => '',
                                                                'seller_discount' => '',
                                                                'order_state' => '',
                                                                'order_state_remark' => '',
                                                                'delivery_type' => '',
                                                                'invoice_info' => '',
                                                                'order_remark' => '',
                                                                'order_start_time' => '',
                                                                'order_end_time' => '',
                                                                'item_info_list' => array(array()), ),
                                                                array(
                                                                'order_id' => '25003799',
                                                                'vender_id' => '10002',
                                                                'pay_type' => '2-邮局汇款',
                                                                'order_total_price' => '',
                                                                'order_seller_price' => '',
                                                                'order_payment' => '',
                                                                'freight_price' => '',
                                                                'seller_discount' => '',
                                                                'order_state' => '',
                                                                'order_state_remark' => '',
                                                                'delivery_type' => '',
                                                                'invoice_info' => '',
                                                                'order_remark' => '',
                                                                'order_start_time' => '',
                                                                'order_end_time' => '',
                                                                'item_info_list' => array(array()), ), ), ),
                        'code' => '0', );
    }
    //end订单API

    //begin库存API
    //根据sku的外部id查询京东sku的id,使用商家的sku检索与之对应的京东的sku
    if ($str == '360buy_ware_sku_ids_search') {
        return $ware_sku_ids_search_response = array(
                            'sku_ids' => array(
                                    'jd_skus' => '1100000004',
                                    'out_skus' => '1111111',
                                    'vender_key' => '', ),
                            'code' => '0', );
    }
    //根据京东sku的id更新sku库存,更改库存接口
    if ($str == '360buy_ware_sku_stock_update') {
        return $ware_sku_stock_update_response = array(
                                    'vender_id' => '20032',
                                    'modified' => '2011-06-21 13:56:58',
                                    'sku_id' => '1100036864',
                                    'code' => '0', );
    }
    //end库存API

    //begin售后API
    //退货信息检索
    if ($str == '360buy_after_search') {
        return $after_search_response = array(
          'after' => array(
                'total_num' => 5,
                'return_infos' => array(
                          array(
                          'return_id' => '299042',
                          'vender_id' => '20032',
                          'send_type' => 'TOVENDER',
                          'receive_state' => 'RECEIVED',
                          'linkman' => 'zzz',
                          'phone' => '132',
                          'return_address' => 'eee',
                          'consignee' => 'sop_order',
                          'consignor' => '谭畅',
                          'send_time' => '2011-04-15 11:58:54',
                          'receive_time' => '2011-04-15 12:09:22',
                          'modifid_time' => '2011-04-15 12:09:22',
                          'return_item_list' => array(
                                      array(
                                      'return_item_id' => '1501043',
                                      'attachment_code' => 'PP1000006897101027101',
                                      'sku_id' => '1000006897',
                                      'sku_name' => 'Giordano/佐丹奴男装厚实御寒四合一功能外套01070521冷灰色 深灰 XL',
                                      'order_id' => '0',
                                      'price' => '390.00',
                                      'return_type' => 'RETURNED',
                                      'return_reason' => '',
                                      'modifid_time' => '2011-04-15 12:07:18', ),
                                      array(
                                      'return_item_id' => '1501044',
                                      'attachment_code' => 'PP1000003962101027101',
                                      'sku_id' => '1000003962',
                                      'sku_name' => 'Giordano/佐丹奴男装六条装Logo宽皮筋三角内裤01179514白/中花灰/黑色 白色 M',
                                      'order_id' => '0',
                                      'price' => '71.25',
                                      'return_type' => 'RETURNED',
                                      'return_reason' => '',
                                      'modifid_time' => '2011-04-15 12:07:18', ), ), ),
                           array(
                           'return_id' => '299040',
                           'vender_id' => '20032',
                           'send_type' => 'TOVENDER',
                           'receive_state' => 'RECEIVED',
                           'linkman' => 'ewaetery',
                           'phone' => '22446688',
                           'return_address' => '323w7y4e3',
                           'consignee' => 'sop_order',
                           'consignor' => '谭畅',
                           'send_time' => '2011-04-13 16:59:06',
                           'receive_time' => '2011-04-13 17:59:24',
                           'modifid_time' => '2011-04-13 17:59:24',
                           'return_item_list' => array(
                                       array(
                                       'return_item_id' => '1501040',
                                       'attachment_code' => 'fx1733775',
                                       'sku_id' => '1000004665',
                                       'sku_name' => 'Giordano/佐丹奴男装胜利狮王连帽开胸卫衣01070932爪哇咖啡色 咖啡色 L',
                                       'order_id' => '0',
                                       'price' => '159.00',
                                       'return_type' => 'FROMWAREHOUSE',
                                       'return_reason' => '',
                                       'modifid_time' => '2011-04-13 17:06:49', ), ), ),
                          array(
                          'return_id' => '299039',
                          'vender_id' => '20032',
                          'send_type' => 'TOVENDER',
                          'receive_state' => 'RECEIVED',
                          'linkman' => 'sdgsag',
                          'phone' => '15900883366',
                          'return_address' => 'gsdgsdfgh',
                          'consignee' => 'sop_order',
                          'consignor' => '谭畅',
                          'send_time' => '2011-04-13 16:50:51',
                          'receive_time' => '2011-04-15 11:56:51',
                          'modifid_time' => '2011-04-15 11:56:51',
                          'return_item_list' => array(
                                      array(
                                      'return_item_id' => '1501039',
                                      'attachment_code' => 'fx1650253',
                                      'sku_id' => '1000005219',
                                      'sku_name' => 'Hasbro孩之宝-变形金刚 2-加强级横炮 H8397189159',
                                      'order_id' => '0',
                                      'price' => '119.00',
                                      'return_type' => 'FROMWAREHOUSE',
                                      'return_reason' => '',
                                      'modifid_time' => '2011-04-13 16:58:34', ), ), ),
                          array(
                          'return_id' => '299038',
                          'vender_id' => '20032',
                          'send_type' => 'TOVENDER',
                          'receive_state' => 'RECEIVED',
                          'linkman' => 'zzzz',
                          'phone' => '132',
                          'return_address' => 'qqq',
                          'consignee' => 'sop_order',
                          'consignor' => '谭畅',
                          'send_time' => '2011-04-13 11:45:06',
                          'receive_time' => '2011-04-13 11:56:00',
                          'modifid_time' => '2011-04-13 11:56:00',
                          'return_item_list' => array(
                                      array(
                                      'return_item_id' => '1501037',
                                      'attachment_code' => 'fx-pop1689079',
                                      'sku_id' => '1000001810',
                                      'sku_name' => '一生一石Q暗送秋波满天星绿幽灵手链情侣对11+15mm',
                                      'order_id' => '0',
                                      'price' => '196.00',
                                      'return_type' => 'REJECTED',
                                      'return_reason' => '',
                                      'modifid_time' => '2011-04-13 11:52:44', ),
                                      array(
                                      'return_item_id' => '1501038',
                                      'attachment_code' => 'fx-pop1683333',
                                      'sku_id' => '1000002497',
                                      'sku_name' => '一生一石一夜暴富貔貅趴叶子3A级天然黄晶挂坠M附权威证书',
                                      'order_id' => '0',
                                      'price' => '366.00',
                                      'return_type' => 'REJECTED',
                                      'return_reason' => '',
                                      'modifid_time' => '2011-04-13 11:52:44', ), ), ),
                           array(
                          'return_id' => '299037',
                          'vender_id' => '20032',
                          'send_type' => 'TOVENDER',
                          'receive_state' => 'RECEIVED',
                          'linkman' => '张无忌',
                          'phone' => '1341242432342 --15988006644',
                          'return_address' => '上海宝山区南京西路39号',
                          'consignee' => 'sop_order',
                          'consignor' => '张婷',
                          'send_time' => '2011-04-13 14:41:25',
                          'receive_time' => '2011-04-15 12:03:46',
                          'modifid_time' => '2011-04-15 12:03:46',
                          'return_item_list' => array(
                                      array(
                                      'return_item_id' => '1501035',
                                      'attachment_code' => 'fx1734992',
                                      'sku_id' => '1000010418',
                                      'sku_name' => 'I‘d爱帝 针织牛仔女裤 暗紫罗兰（紫蓝） L',
                                      'order_id' => '0',
                                      'price' => '99.00',
                                      'return_type' => 'FROMWAREHOUSE',
                                      'return_reason' => '',
                                      'modifid_time' => '2011-04-13 14:49:06', ),
                                      array(
                                      'return_item_id' => '1501036',
                                      'attachment_code' => 'fx1734967',
                                      'sku_id' => '1000010407',
                                      'sku_name' => 'I‘d爱帝 针织牛仔女裤 黑色 XL',
                                      'order_id' => '0',
                                      'price' => '99.00',
                                      'return_type' => 'FROMWAREHOUSE',
                                      'return_reason' => '',
                                      'modifid_time' => '2011-04-13 14:49:06', ), ), ), ), ),
            'code' => '0', );
    }
    //退货确认
    if ($str == '360buy_after_state_update') {
        return $after_state_update_response = array(
                                'vender_id' => '20032',
                                'modified' => '2011-06-21 15:43:58',
                                'return_id' => '299042',
                                'code' => '0', );
    }
    //end售后API

    //begin配送API
    //商家获取发货地址
    if ($str == '360buy_delivery_ship_address_get') {
        return $delivery_ship_address_get_response = array(
                            'ship_addresses' => array(
                                    'address_list' => array(
                                    array(
                                      'address_id' => '78',
                                      'district' => '北京通州区通州城区内',
                                      'street' => '4retyrytr22',
                                      'warehouse' => '北京仓库',
                                      'phone' => '44',
                                      'default' => true,
                                    ),
                                    array(
                                      'address_id' => '91',
                                      'district' => '北京海淀区三环到四环之间',
                                      'street' => '苏州街银333丰大厦3层',
                                      'warehouse' => '北京仓库',
                                      'phone' => '01089654755',
                                      'default' => false,
                                    ),
                                    array(
                                      'address_id' => '46',
                                      'district' => '四川成都市郫县(除主城区,犀浦、郫茼镇,进出口加工区外区域)',
                                      'street' => 'e11111111111155533243trehytrey',
                                      'warehouse' => '成都仓库',
                                      'phone' => '2222885522222222',
                                      'default' => false,
                                    ), ),
                                'vender_id' => 10002, ),
                        'code' => '0', );
    }
    //商家获取物流公司
    if ($str == '360buy_delivery_logistics_get') {
        return $delivery_logistics_get_response = array(
          'logistics_companies' => array(
          'logistics_list' => array(
            array(
              'logistics_id' => 471,
              'logistics_name' => '龙邦快递',
              'logistics_remark' => '个',
              'sequence' => '5',
            ),
            array(
              'logistics_id' => 476,
              'logistics_name' => '杨海物流',
              'logistics_remark' => '',
              'sequence' => '100',
            ),
            array(
              'logistics_id' => 475,
              'logistics_name' => '门对门快递',
              'logistics_remark' => '',
              'sequence' => '3',
            ),
            array(
              'logistics_id' => 474,
              'logistics_name' => '荣捷快递',
              'logistics_remark' => '',
              'sequence' => '4',
            ),
            array(
              'logistics_id' => 472,
              'logistics_name' => '中外运快递',
              'logistics_remark' => '1221',
              'sequence' => '12',
            ),
            array(
              'logistics_id' => -1000,
              'logistics_name' => 'hngbvhgf',
              'logistics_remark' => '54',
              'sequence' => '45',
            ),
            array(
              'logistics_id' => -1000,
              'logistics_name' => 'dfdsafdsaf',
              'logistics_remark' => 'fddsafdsaf',
              'sequence' => '111',
            ), ),
          'vender_id' => 20032, ),
        'code' => '0', );
    }
    //end配送API

    //begin促销API
    //促销检索
    if ($str == '360buy_promotion_search') {
        return $promotion_search_response = array(
            'code' => '0',
            'promotionSearch' => array(
              'promotionList' => array(
              array(
              'id' => 346,
              'venderId' => 20032,
              'promoName' => '111',
              'type' => '买就送',
              'levelMember' => '无限制',
              'timeBegin' => '2011-06-01 16:16:00',
              'timeEnd' => '2011-07-01 21:37:24',
              'evtStatus' => '已结束-未同步',
              'synchStatus' => '未同步',
              'checkStatus' => '未审核',
              'promotionProductList' => array(
                    array(
                  'skuId' => 1100039422,
                  'prodName' => '增值税发票测试1',
                  'prodId' => 1100018827,
                  'price' => '300.0',
                  'promoPrice' => '240.0',
                  'isMain' => '', ), ), ),
            array(
              'id' => 345,
              'venderId' => 20032,
              'promoName' => '22',
              'type' => '赠品促销',
              'levelMember' => '无限制',
              'timeBegin' => '2011-06-01 16:06:00',
              'timeEnd' => '2011-07-01 21:37:24',
              'evtStatus' => '已结束-未同步',
              'synchStatus' => '未同步',
              'checkStatus' => '未审核',
              'promotionProductList' => array(
                    array(
                  'skuId' => 1100037903,
                  'prodName' => '主：test1 尺码:S ',
                  'prodId' => 1100018300,
                  'price' => '99.0',
                  'promoPrice' => '0.0',
                  'isMain' => '主：', ),
                    array(
                  'skuId' => 1100037214,
                  'prodName' => '赠：商品导入测试733（一行一个商品） 颜色:明黄 ',
                  'prodId' => 1100017916,
                  'price' => '236.0',
                  'promoPrice' => '0.0',
                  'isMain' => '赠：', ),
                    array(
                  'skuId' => 1100037213,
                  'prodName' => '赠：商品导入测试733（一行一个商品） 颜色:白色 ',
                  'prodId' => 1100017916,
                  'price' => '236.0',
                  'promoPrice' => '0.0',
                  'isMain' => '赠：', ),
                    array(
                  'skuId' => 1100037212,
                  'prodName' => '赠：商品导入测试733（一行一个商品） 颜色:红色 ',
                  'prodId' => 1100017916,
                  'price' => '236.0',
                  'promoPrice' => '0.0',
                  'isMain' => '赠：', ), ), ),
            array(
              'id' => 343,
              'venderId' => 20032,
              'promoName' => '赠品',
              'type' => '赠品促销',
              'levelMember' => '无限制',
              'timeBegin' => '2011-06-01 16:04:00',
              'timeEnd' => '2011-07-01 21:37:24',
              'evtStatus' => '已结束-未同步',
              'synchStatus' => '未同步',
              'checkStatus' => '未审核',
              'promotionProductList' => array(
                    array(
                  'skuId' => 1100039422,
                  'prodName' => '主：增值税发票测试1',
                  'prodId' => 1100018827,
                  'price' => '300.0',
                  'promoPrice' => '0.0',
                  'isMain' => '主：', ),
                    array(
                  'skuId' => 1100037906,
                  'prodName' => '赠：test1 尺码:11 ',
                  'prodId' => 1100018300,
                  'price' => '99.0',
                  'promoPrice' => '0.0',
                  'isMain' => '赠：', ),
                    array(
                  'skuId' => 1100037904,
                  'prodName' => '赠：test1 尺码:L ',
                  'prodId' => 1100018300,
                  'price' => '99.0',
                  'promoPrice' => '0.0',
                  'isMain' => '赠：', ),
                    array(
                  'skuId' => 1100037903,
                  'prodName' => '赠：test1 尺码:S ',
                  'prodId' => 1100018300,
                  'price' => '99.0',
                  'promoPrice' => '0.0',
                  'isMain' => '赠：', ),
                    array(
                  'skuId' => 1100037811,
                  'prodName' => '赠：我带表月亮消灭你 颜色:黑色 ',
                  'prodId' => 1100016721,
                  'price' => '23.0',
                  'promoPrice' => '0.0',
                  'isMain' => '赠：', ), ), ),
            array(
              'id' => 342,
              'venderId' => 20032,
              'promoName' => '赠品',
              'type' => '赠品促销',
              'levelMember' => '无限制',
              'timeBegin' => '2011-06-01 16:04:00',
              'timeEnd' => '2011-07-01 21:37:24',
              'evtStatus' => '已结束-未同步',
              'synchStatus' => '未同步',
              'checkStatus' => '未审核',
              'promotionProductList' => array(
                       array(
                  'skuId' => 1100039641,
                  'prodName' => '主：sss 颜色:红色 尺码:XL ',
                  'prodId' => 1100017482,
                  'price' => '7.0',
                  'promoPrice' => '0.0',
                  'isMain' => '主：', ),
                    array(
                  'skuId' => 1100037903,
                  'prodName' => '赠：test1 尺码:S ',
                  'prodId' => 1100018300,
                  'price' => '99.0',
                  'promoPrice' => '0.0',
                  'isMain' => '赠：', ),
                    array(
                  'skuId' => 1100037906,
                  'prodName' => '赠：test1 尺码:11 ',
                  'prodId' => 1100018300,
                  'price' => '99.0',
                  'promoPrice' => '0.0',
                  'isMain' => '赠：', ),
                    array(
                  'skuId' => 1100037811,
                  'prodName' => '赠：我带表月亮消灭你 颜色:黑色 ',
                  'prodId' => 1100016721,
                  'price' => '23.0',
                  'promoPrice' => '0.0',
                  'isMain' => '赠：', ),
                    array(
                  'skuId' => 1100037904,
                  'prodName' => '赠：test1 尺码:L ',
                  'prodId' => 1100018300,
                  'price' => '99.0',
                  'promoPrice' => '0.0',
                  'isMain' => '赠：', ), ), ),
            array(
              'id' => 341,
              'venderId' => 20032,
              'promoName' => '4324',
              'type' => '套装促销',
              'levelMember' => '无限制',
              'timeBegin' => '2011-06-01 16:03:00',
              'timeEnd' => '2011-07-01 21:37:24',
              'evtStatus' => '已结束-已同步',
              'synchStatus' => '已同步',
              'checkStatus' => '审核通过',
              'promotionProductList' => array(
                    array(
                  'skuId' => 1100039641,
                  'prodName' => 'sss 颜色:红色 尺码:XL ',
                  'prodId' => 1100017482,
                  'price' => '7.0',
                  'promoPrice' => '6.0',
                  'isMain' => '', ),
                    array(
                  'skuId' => 1100039422,
                  'prodName' => '增值税发票测试1',
                  'prodId' => 1100018827,
                  'price' => '300.0',
                  'promoPrice' => '299.0',
                  'isMain' => '', ),
                    array(
                  'skuId' => 1100037906,
                  'prodName' => 'test1 尺码:11 ',
                  'prodId' => 1100018300,
                  'price' => '99.0',
                  'promoPrice' => '98.0',
                  'isMain' => '', ),
                    array(
                  'skuId' => 1100037905,
                  'prodName' => 'test1 尺码:SL ',
                  'prodId' => 1100018300,
                  'price' => '99.0',
                  'promoPrice' => '98.0',
                  'isMain' => '', ), ), ), ),
          'promotionTotal' => 5, ), );
    }
    //end促销API
    }
}
