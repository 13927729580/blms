<?php

/*****************************************************************************
 * Seevia 专题管理
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 CouponsController 的控制优惠券管控制器.
 */
class CouponsController extends AppController
{
    /*
    *@var $name
    *@var $components
    *@var $helpers
    *@var $uses
    */
    public $name = 'Coupons';
    public $components = array('Pagination','RequestHandler'); // Added
    public $helpers = array('Pagination'); // Added
    public $uses = array('Coupon','Order','User','CouponType', 'Order','UserFans','Blog','UserApp');

    /**
     *函数 index 用于进入商品优惠栏目页面.
     */
    public function user_index($page = 1, $limit = 20)
    {
    	 $_GET=$this->clean_xss($_GET);
        //登录验证
        $this->checkSessionUser();
        //页面标题
        $this->pageTitle = $this->ld['rebate_084'].' - '.$this->configs['shop_title'];
        //当前位置开始
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => $this->ld['rebate_086'],'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $user_id = $_SESSION['User']['User']['id'];
        $conditions = array();
        $conditions['Coupon.user_id'] = $user_id;
        $status = 0;
        if (isset($_GET['status'])&&$_GET['status']!="") {
            $status = $_GET['status'];
        }
        $this->set('status', $status);
        if ($status == 0) {
            $conditions['Coupon.order_id'] = 0;
        }
        if ($status == 1) {
            $conditions['Coupon.order_id <>'] = 0;
        }
        if (isset($_SESSION['User']['User']['id'])) {
            //pr($_SESSION['User']['User']['id']);
            //分享绑定显示判断
            $app_share = $this->UserApp->app_status();
            $this->set('app_share', $app_share);
            $id = $_SESSION['User']['User']['id'];
            $user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
            $this->set('user_list', $user_list);
            //pr($user_list);
            //粉丝数量
            $fans = $this->UserFans->find_fanscount_byuserid($id);
            $this->set('fanscount', $fans);
            //日记数量
            $blog = $this->Blog->find_blogcount_byuserid($id);
            $this->set('blogcount', $blog);
            //关注数量
            $focus = $this->UserFans->find_focuscount_byuserid($id);
            $this->set('focuscount', $focus);
        }
        //get参数
        $limit = $limit;
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'coupons', 'action' => 'index', 'page' => $page);
        //分页参数
        $options = array('page' => $page, 'show' => $limit, 'modelClass' => 'Coupon');
        $page = $this->Pagination->init($conditions, $parameters, $options); // Added

        $fields = array('Coupon.*','CouponTypeI18n.name','CouponType.type','CouponType.money','CouponType.min_amount','CouponType.use_end_date','CouponType.use_start_date');
        $joins = array(
            array('table' => 'svoms_coupon_types',
                  'alias' => 'CouponType',
                  'type' => 'inner',
                  'conditions' => array('CouponType.id = Coupon.coupon_type_id'),
                 ),
            array('table' => 'svoms_coupon_type_i18ns',
                  'alias' => 'CouponTypeI18n',
                  'type' => 'inner',
                  'conditions' => array('CouponType.id = CouponTypeI18n.coupon_type_id and CouponTypeI18n.locale="'.$this->locale.'"'),
                 ), );
        $orderBy = 'CouponType.use_end_date desc';
        $coupons = $this->Coupon->find('all', array('fields' => $fields, 'conditions' => $conditions, 'page' => $page, 'joins' => $joins, 'order' => $orderBy));
        if (!empty($coupons)) {
            /*
            $coupon_type_ids = array();
            foreach($coupons as $c){
                if(!in_array( $c['Coupon']['coupon_type_id'], $coupon_type_ids)){
                    $coupon_type_ids[] = $c['Coupon']['coupon_type_id'];
                }
                if($status == 2){
                    $now = date('Y-m-d');
                    $infos = $this->CouponType->find('all',array('conditions'=>array('CouponType.id'=>$coupon_type_ids,'CouponType.use_end_date <'=>$now)));
                }else{
                    $infos = $this->CouponType->find('all',array('conditions'=>array('CouponType.id'=>$coupon_type_ids)));
                }
                foreach($infos as $i){
                    $ct_infos[$i['CouponType']['id']] = $i;
                }
            }
            foreach($coupons as $ck=>$c){
                if(!isset($ct_infos[$c['Coupon']['coupon_type_id']])){
                    unset($coupons[$ck]);
                    continue;
                }
                $coupons[$ck]['Coupon']['name'] = $ct_infos[$c['Coupon']['coupon_type_id']]['CouponTypeI18n']['name'];
                $coupons[$ck]['Coupon']['type'] = $ct_infos[$c['Coupon']['coupon_type_id']]['CouponType']['type'];
                $coupons[$ck]['Coupon']['fee'] = $ct_infos[$c['Coupon']['coupon_type_id']]['CouponType']['money'];
                $coupons[$ck]['Coupon']['min_amount'] = $ct_infos[$c['Coupon']['coupon_type_id']]['CouponType']['min_amount'];
                $coupons[$ck]['Coupon']['use_end_date'] = $ct_infos[$c['Coupon']['coupon_type_id']]['CouponType']['use_end_date'];
                $coupons[$ck]['Coupon']['use_start_date'] = $ct_infos[$c['Coupon']['coupon_type_id']]['CouponType']['use_start_date'];
                if($c['Coupon']['order_id'] == 0){
                    $coupons[$ck]['Coupon']['is_use'] = 0;
                }else{
                    $coupons[$ck]['Coupon']['is_use'] = 1;
                }
            }
            */
            $this->set('coupons', $coupons);
        }
        $this->layout = 'usercenter';
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            Configure::write('debug', 0);
            $this->layout = 'ajax';
        }
    }
    /**
     *函数 user_add_coupon 用于添加优惠券.
     */
    public function user_add_coupon()
    {
        $result['type'] = 0;
        if ($this->RequestHandler->isPost()) {
            if (isset($_SESSION['User']['User']['id'])) {
                if (!isset($_POST['captcha']) || strtolower($_POST['captcha']) != $_SESSION['securimage_code_value']) {
                    $result['msg'] = $this->ld['verify_code'].$this->ld['not_correct'];
                } else {
                    $coupon = $this->Coupon->find('first', array('conditions' => array('Coupon.sn_code' => $_POST['sn_code'])));
                    if (isset($coupon['Coupon'])) {
                        $coupon_type = $this->CouponType->find('first', array('conditions' => array('CouponType.id' => $coupon['Coupon']['coupon_type_id'])));
                        if ($coupon_type['CouponType']['send_type'] == 3 && $coupon['Coupon']['user_id'] == 0 && $coupon['Coupon']['order_id'] == 0) {
                            $coupon['Coupon']['user_id'] = $_SESSION['User']['User']['id'];
                            //$result['msg'] = $this->ld['add'].$this->ld['coupon'].$this->ld['successfully'];
                            $result['msg'] = $this->ld['rebate_095'];
                            //pr($coupon);die();
                            $this->Coupon->save($coupon['Coupon']);
                            $result['type'] = 1;
                        } else {
                            $result['msg'] = $this->ld['rebate_087'];
                        }
                    } else {
                        $result['msg'] = $this->ld['rebate_087'];
                    }
                }
            } else {
                $result['msg'] = $this->ld['time_out_relogin'];
            }
            if (!isset($_POST['is_ajax'])) {
                $this->page_init();
                $this->pageTitle = $result['msg'];
                $flash_url = $this->server_host.$this->user_webroot.'/coupons';
                $this->flash($result['msg'], $flash_url, 10);
            }
        } else {
            $result['msg'] = '';
        }
        Configure::write('debug', 0);
        die(json_encode($result));
    }
}
