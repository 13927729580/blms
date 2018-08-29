<?php

uses('sanitize');
/**
 *这是一个名为 PagesController 的页面控制器.
 *
 *@var
 *@var
 *@var
 *@var
 *@var
 *@var
 */
class UsersController extends AppController
{
    public $name = 'Users';
    public $helpers = array('Html','Flash','Cache','Pagination');
    public $uses = array('Precondition','CourseNoteReply','CourseClass','CourseNote','Course','UserCourseClass','UserRelationship','UserPointLog','Application','UserAddress','User','Product','UserRank','NotifyTemplateType','ProductI18n','Region','Comment','Blog','UserFans','SynchroUser','UserBalanceLog','Flash','UserApp','Payment','PaymentApiLog','Enquiry','UserRankLog','Template','ScoreLog','OpenModel','UserConfig','UserLike','UserFavorite','Brand','Attribute','UserProductSubscription','ProductTypeAttribute','Attribute','UserCategory','UserActionLog');
    public $components = array('RequestHandler','Cookie','Session','Captcha','Notify','Pagination');
    public $cacheQueries = false;
    public $cacheAction = '1 hour';
    
    public function beforeFilter(){
    		parent::beforeFilter();
    		if(isset($this->configs['enable_registration_closed'])&&$this->configs['enable_registration_closed']=='1')$this->redirect('/');
    }
    
    public function index()
    {
        //登录验证
        $this->checkSessionUser();
        /*
        判断是否为手机版
        */
        if ($this->is_mobile) {
            $this->layout = 'mobile/default_full';
            $this->render('mobile/index');
            Configure::write('debug', 1);
        }else{
            $this->layout = 'usercenter';            //引入模版
        }
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            Configure::write('debug', 0);
            $this->layout = 'ajax';
        }
        $this->page_init();                        //页面初始化
        $this->pageTitle = $this->ld['user_center'].' - '.$this->configs['shop_title'];
        //当前位置开始
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $user_id=$_SESSION['User']['User']['id'];
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        if(empty($user_list))$this->redirect('/user/logout');
        if ($user_list['User']['rank'] > 0) {
            $rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
            $user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
        }
        if (isset($user_list['User']['operator_id'])&&$user_list['User']['operator_id'] > 0) {
            $this->loadModel('Operator');
            $UserManagerInfo=$this->Operator->find('first',array('conditions'=>array('Operator.id'=>$user_list['User']['operator_id'],'Operator.status'=>'1')));
            $this->set('UserManagerInfo',$UserManagerInfo);
        }
        $this->set('user_list', $user_list);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($user_id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($user_id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($user_id);
        $this->set('focuscount', $focus);

        /*
    if (constant('Product') == 'AllInOne') {
    //当前订单信息（该用户最新3个订单）
    $this->loadModel('Order');
    $order_list = $this->Order->find('all', array('conditions' => array('Order.user_id' => $_SESSION['User']['User']['id']), 'order' => 'Order.created desc', 'limit' => '3'));
    $this->set('order_list', $order_list);
    //优惠券
    $this->loadModel('Coupon');
         $coupon_total=$this->Coupon->find('count', array('conditions' => array('Coupon.user_id' => $_SESSION['User']['User']['id'],'Coupon.order_id'=>0)));
         $this->set('coupon_total',$coupon_total);
    }
    //猜您可能会喜欢（最新推荐的8个商品）
    $pro_like = $this->Product->find('all', array('conditions' => array('Product.recommand_flag' => '1','Product.status'=>'1','alone'=>'1','forsale'=>'1'), 'fields' => 'Product.img_thumb,Product.img_detail,Product.id,Product.brand_id,Product.promotion_end,Product.promotion_start,ProductI18n.name,Product.market_price,Product.promotion_price,Product.promotion_status,Product.shop_price,ProductI18n.description,ProductI18n.description02', 'order' => 'Product.modified desc', 'limit' => '8'));
    if(!empty($pro_like)){
        $attribute_info=$this->Attribute->find('all',array('fields'=>array("Attribute.id","AttributeI18n.name"),'conditions'=>array("Attribute.status"=>'1')));
        $attribute_data=array();
    foreach($attribute_info as $v){
        $attribute_data[$v['Attribute']['id']]=$v['AttributeI18n']['name'];
    }
    $this->set('attribute_data',$attribute_data);

    $brand_info=$this->Brand->find('all',array('fields'=>array('Brand.id','BrandI18n.name'),'conditions'=>array("Brand.status"=>'1')));
    $brand_data=array();
    foreach($brand_info as $v){
        $brand_data[$v['Brand']['id']]=$v['BrandI18n']['name'];
    }
        $pro_ids=array();
        foreach ($pro_like as $k => $v) {
            $pro_ids[]=$v['Product']['id'];
        }
        $UserLike_data=$this->UserLike->find('list',array('fields'=>'type_id,id','conditions'=>array('UserLike.user_id'=>$user_id,'UserLike.action'=>'like','UserLike.type'=>'P','UserLike.type_id'=>$pro_ids)));
                $UserFavorite_data=$this->UserFavorite->find('list',array('fields'=>'type_id,id','conditions'=>array('UserFavorite.user_id'=>$user_id,'UserFavorite.status'=>'1','UserFavorite.type'=>'P','UserFavorite.type_id'=>$pro_ids)));
        foreach ($pro_like as $k => $v) {
            $pro_like[$k]['UserLike']=isset($UserLike_data[$v['Product']['id']])?'1':'0';
          $pro_like[$k]['UserFavorite']=isset($UserFavorite_data[$v['Product']['id']])?'1':'0';
          $pro_like[$k]['Brand']=isset($brand_data[$v['Product']['brand_id']])?$brand_data[$v['Product']['brand_id']]:'';
            //判断是否促销产品
            if ($this->Product->is_promotion($v)) {
                $pro_like[$k]['Product']['off'] = floor((1 - ($v['Product']['promotion_price'] / $v['Product']['shop_price'])) * 100);
            }
        }
    }
    $this->set('pro_like', $pro_like);
    $id = $_SESSION['User']['User']['id'];
    //粉丝数量
    $fans = $this->UserFans->find_fanscount_byuserid($id);
    $this->set('fanscount', $fans);
    //日记数量
    $blog = $this->Blog->find_blogcount_byuserid($id);
    $this->set('blogcount', $blog);
    //关注数量
    $focus = $this->UserFans->find_focuscount_byuserid($id);
    $this->set('focuscount', $focus);
    //分享绑定显示判断
    $app_share = $this->UserApp->app_status();
    $this->set('app_share', $app_share);
    //商品订阅数量
    $UserProductSubscription_total=$this->UserProductSubscription->find('count',array('conditions'=>array('UserProductSubscription.user_id'=>$id,'status'=>'1')));
    $this->set('UserProductSubscription_total',$UserProductSubscription_total);
    if (constant('Product') == 'AllInOne') {
        $this->loadModel('Order');
        $this->loadModel('Payment');
         //取得我的订单（最新的3条订单）
        $condition = " Order.user_id='".$_SESSION['User']['User']['id']."' ";
        $my_orders = $this->Order->my_list($condition, 3, 1);
        if (empty($my_orders)) {
            $my_orders = array();
        } else {
            $my_order_ids = array();
            foreach ($my_orders as $k => $v) {
                //获取订单ID
                $my_order_ids[] = $v['Order']['id'];
                //获取该订单使用的付款方式
                $payment_info = $this->Payment->find('first', array('conditions' => array('Payment.id' => $v['Order']['payment_id'])));
                $my_orders[$k]['Order']['payment_name'] = $payment_info['PaymentI18n']['name'];
                $my_orders[$k]['Order']['payment_is_cod'] = $payment_info['Payment']['is_cod'];
                if (empty($v['Order']['consignee'])) {
                    //获取该订单的收货人
                    $address = $this->UserAddress->find_user_address($v['Order']['user_id']);
                    $my_orders[$k]['Order']['consignee'] = $address[0]['UserAddress']['consignee'];
                }
                //去掉优惠后，我需要付款的总额
                $my_orders[$k]['Order']['need_paid'] = number_format($v['Order']['total'] - $v['Order']['money_paid'] - $v['Order']['point_fee'] - $v['Order']['discount'], 2, '.', '') + 0;
            }
        }
        //本月消费
        $years = date('m');
        $order_month_count = 0;
        $order_year = $this->Order->find('all', array(
                'conditions' => array('Order.user_id' => $_SESSION['User']['User']['id'], 'Order.payment_status' => 2, 'month(Order.created)' => $years), ));
        foreach ($order_year as $k => $v) {
            $order_month_count += ($v['Order']['total'] - $v['Order']['point_fee']);
        }
         //总消费
        $order_all_count = 0;
        $order_all = $this->Order->find('all', array(
                'conditions' => array('Order.user_id' => $_SESSION['User']['User']['id'], 'Order.payment_status' => 2), ));
        foreach ($order_all as $k => $v) {
            $order_all_count += ($v['Order']['total'] - $v['Order']['point_fee']);
        }
         //待支付订单
        $pay_orderscount = $this->Order->find('count', array(
                'conditions' => array('Order.user_id' => $_SESSION['User']['User']['id'], 'Order.payment_status' => 0), ));
         //待收货订单
        $receiving_orderscount = $this->Order->find('count', array(
                'conditions' => array('Order.user_id' => $_SESSION['User']['User']['id'], 'Order.payment_status' => 2, 'Order.status' => 1, 'Order.shipping_status' => 1), ));
        //待评论订单
        $pro_comments = array();
        //获取购买过但未评论的商品
        $comment_orders = $this->Order->find('all', array('conditions' => array('Order.user_id' => $_SESSION['User']['User']['id'], 'Order.status' => '1', 'Order.shipping_status' => '2', 'Order.payment_status' => '2')));
        foreach ($comment_orders as $k => $v) {
            foreach ($v['OrderProduct'] as $kk => $vv) {
                $counts = $this->Comment->find('count', array('conditions' => array('Comment.type_id' => $vv['product_id'], 'Comment.type' => 'P', 'Comment.status' => 1, 'Comment.user_id' => $_SESSION['User']['User']['id'])));//获取我的评论
                 if ($counts == 0) {
                     $pro_first = $this->Product->find('first', array('conditions' => array('Product.id' => $vv['product_id']), 'fields' => 'Product.img_thumb,Product.img_detail'));//获取我的评论
                    $pro_comments[$vv['id']] = $vv;
                     $pro_comments[$vv['id']]['product_img_thumb'] = $pro_first['Product']['img_thumb'];
                     $pro_comments[$vv['id']]['product_img_detail'] = $pro_first['Product']['img_detail'];
                    //获取回复数量
                    $pro_comments[$vv['id']]['count'] = $this->Comment->find('count', array('conditions' => array('Comment.type_id' => $vv['product_id'], 'Comment.type' => 'P', 'Comment.status' => 1)));//获取我的评论
                 }
            }
        }
        $this->set('order_all_count', $order_all_count);
        $this->set('order_month_count', $order_month_count);
        $this->set('pay_orderscount', $pay_orderscount);
        $this->set('pro_comments', $pro_comments);
        $this->set('my_orders', $my_orders);
        $this->set('receiving_orderscount', $receiving_orderscount);
    }
    $this->set('user_list', $user_list);

    if (isset($this->configs['phistory-ustatus']) && $this->configs['phistory-ustatus'] == '1') {
        //商品浏览历史
        $params['controller'] = $this->params['controller'];
        $params['action'] = $this->params['action'];
        $params['ControllerObj'] = $this;//控制器对象
        $pro_log_list = $this->Product->pro_view_log($params);
        $this->set('pro_log_list', $pro_log_list);
    }

    $this->loadModel('UserCourseClass');
    $this->UserCourseClass->user_course_list();
    */
    }

    //注册
    public function register()
    {
        $this->set('type', isset($_GET['type']) ? $_GET['type'] : 0);
        $this->set('sev', $this->server_host);
        $this->layout = 'default_full';            //引入模版
        $this->page_init();
        $this->pageTitle = $this->ld['register'].' - '.$this->configs['shop_title'];                //页面初始化
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['register'],'url' => '');
        /*
            判断是否为手机版
        */
        if ($this->is_mobile) {
            $this->layout = 'mobile/default_full';
            $this->render('mobile/register');
            Configure::write('debug', 1);
        }
        $messege_error = '';                        //报错提示变量
        //登录注册轮播
        $flash_conditions['Flash.page'] = 'LR';
        $flash_conditions['Flash.type'] = '0';
        $flash_list = $this->Flash->find('first', array('conditions' => $flash_conditions));
        $this->set('flash_list', $flash_list);

        $syns_cond=array(
            'UserApp.status'=>'1',
            'UserApp.location'=>array(0,2),
            'not'=>array(
                'UserApp.type'=>array('Wechat','QYWechat')
            )
        );
        $syns = $this->UserApp->find('list', array('conditions' => $syns_cond, 'fields' => array('UserApp.type')));
        $this->set('syns', $syns);
        if ($this->RequestHandler->isPost()) {
            if(isset($this->configs['registration_invitation_code'])&&trim($this->configs['registration_invitation_code'])!=''){
                $invitation_code=isset($_REQUEST['invitation_code'])?trim($_REQUEST['invitation_code']):'';
                if($invitation_code!=trim($this->configs['registration_invitation_code'])){
                    if (isset($_POST['is_ajax'])){
                        Configure::write('debug', 0);
                        $this->layout = 'ajax';
                        $result = array(
                            'result' => '邀请码错误',
                            'message' => '邀请码错误',
                            'back_url' => '',
                            'error_no' => 1
                        );
                        die(json_encode($result));
                    }
                    $this->flash("<font color='red'>邀请码错误</font>", array('controller' => '/'), '');
                    return;
                }
            }
            if(isset($this->configs['user_register_mode'])&&$this->configs['user_register_mode']=='2'){
                $user_register_mode=isset($_REQUEST['user_register_mode'])?$_REQUEST['user_register_mode']:'0';
            }else{
                $user_register_mode=isset($this->configs['user_register_mode'])?$this->configs['user_register_mode']:'0';
            }
            if($user_register_mode=='1'){
                $mobile=isset($this->data['Users']['mobile'])?trim($this->data['Users']['mobile']):'';
                $phone_code_key="phone_code_number{$mobile}";
                $phone_code_number=isset($_COOKIE[$phone_code_key])?$_COOKIE[$phone_code_key]:'';
                $mobile_code=isset($this->data['Users']['mobile_code'])?$this->data['Users']['mobile_code']:'';
                if($mobile==""){
                    if (isset($_POST['is_ajax'])) {
                        $error_no = 1;
                        $messege_error = $this->ld['phone_can_not_be_empty'];
                        $back_url = '';
                        $this->layout = 'ajax';
                        Configure::write('debug', 0);
                        $result = array(
                            'result' => $messege_error,
                            'message' => $messege_error,
                            'back_url' => $back_url,
                            'error_no' => $error_no,
                            //'check_mobile' => $mobile,
                        );
                        die(json_encode($result));
                    }
                    $this->flash("<font color='red'>".$this->ld['phone_can_not_be_empty'].'</font>', array('controller' => '/'), '');
                    return;
                }
                if($mobile_code!=$phone_code_number||$mobile_code==""){
                    if (isset($_POST['is_ajax'])) {
                        $error_no = 1;
                        $messege_error = $this->ld['incorrect_verification_code'];
                        $back_url = '';
                        $this->layout = 'ajax';
                        Configure::write('debug', 0);
                        $result = array(
                            'result' => $messege_error,
                            'message' => $messege_error,
                            'back_url' => $back_url,
                            'error_no' => $error_no,
                            //'check_mobile' => $mobile,
                        );
                        die(json_encode($result));
                    }
                    $this->flash("<font color='red'>".$this->ld['incorrect_verification_code'].'</font>', array('controller' => '/'), '');
                    return;
                }
                $tmp_x = $this->User->find('first', array('conditions' => array('User.mobile' => $mobile)));
                if (!empty($tmp_x)) {
                    //判断email是否have
                    if (isset($_POST['is_ajax'])) {
                        $error_no = 1;
                        $messege_error = $this->ld['mobile_exists'];
                        $back_url = '';
                        $this->layout = 'ajax';
                        Configure::write('debug', 0);
                        $result = array(
                            'result' => $messege_error,
                            'message' => $messege_error,
                            'back_url' => $back_url,
                            'error_no' => $error_no,
                            //'check_mobile' => $mobile,
                        );
                        die(json_encode($result));
                    }
                    $this->flash("<font color='red'>".$this->ld['mobile_exists'].'</font>', array('controller' => '/'), '');
                    return;
                }
                if (!isset($this->data['Users']['name']) || (isset($this->data['Users']['name']) && $this->data['Users']['name'] == '')) {
                    $this->data['Users']['name'] = $this->data['Users']['mobile'];
                }
            }else if($user_register_mode=='0'){//邮箱注册
                $email=isset($this->data['Users']['email'])?trim($this->data['Users']['email']):'';
                $tmp_x = $this->User->find('first', array('conditions' => array('User.email' => $email)));
                if (!empty($tmp_x)) {
                    //判断email是否have
                    if (isset($_POST['is_ajax'])) {
                        $error_no = 1;
                        $messege_error = $this->ld['email_already_exists'];
                        $back_url = '';
                        $this->layout = 'ajax';
                        Configure::write('debug', 0);
                        $result = array(
                            'result' => $messege_error,
                            'message' => $messege_error,
                            'back_url' => $back_url,
                            'error_no' => $error_no,
                            //'check_email' => $this->data['Users']['email'],
                        );
                        die(json_encode($result));
                    }
                    $this->flash("<font color='red'>".$this->ld['email_already_exists'].'</font>', array('controller' => '/'), '');
                    return;
                }
                $email_code_key="email_code_number{$email}";
                $email_code_key=str_replace('.','_',$email_code_key);
                $email_code_number=isset($_COOKIE[$email_code_key])?$_COOKIE[$email_code_key]:'';
                $email_code=isset($this->data['Users']['email_code'])?$this->data['Users']['email_code']:'';
                if($email_code!=$email_code_number||$email_code==""){
                    if (isset($_POST['is_ajax'])) {
                        $error_no = 1;
                        $messege_error = $this->ld['incorrect_verification_code'];
                        $back_url = '';
                        $this->layout = 'ajax';
                        Configure::write('debug', 0);
                        $result = array(
                            'result' => $messege_error,
                            'message' => $messege_error,
                            'back_url' => $back_url,
                            'error_no' => $error_no,
                        );
                        die(json_encode($result));
                    }
                    $this->flash("<font color='red'>".$this->ld['incorrect_verification_code'].'</font>', array('controller' => '/'), '');
                    return;
                }
                if (!isset($this->data['Users']['name']) || (isset($this->data['Users']['name']) && $this->data['Users']['name'] == '')) {
                    $this->data['Users']['name'] = $this->data['Users']['email'];
                }
            }
            //是否使用注册验证码
            $register_captcha = isset($this->configs['register_captcha']) && $this->configs['register_captcha'] == '1' ? true : false;
            if ($register_captcha) {
                if (!isset($this->data['Users']['authnum']) || isset($this->data['Users']['authnum']) && $this->captcha->check($this->data['Users']['authnum']) == false) {
                    //判断验证码是否正确
                    if (isset($_POST['is_ajax'])) {
                        $messege_error = $this->ld['incorrect_verification_code'];
                        $error_no = 1;
                        $back_url = '';
                        $this->layout = 'ajax';
                        Configure::write('debug', 0);
                        $result = array(
                            'result' => $messege_error,
                            'message' => $messege_error,
                            'back_url' => $back_url,
                            'error_no' => $error_no
                        );
                        die(json_encode($result));
                    }
                    $this->flash("<font color='red'>".$this->ld['incorrect_verification_code'].'</font>', array('controller' => '/'), '');
                    return;
                }
            }
            //添加用户
            $psw = $this->data['Users']['password'];
            $this->data['Users']['password'] = md5($this->data['Users']['password']);
            if($user_register_mode=='1'){
                $this->data['Users']['user_sn'] = $this->data['Users']['mobile'];
                $this->data['Users']['mobile'] = $this->data['Users']['mobile'];
            }else{
                $this->data['Users']['user_sn'] = $this->data['Users']['email'];
                $this->data['Users']['email'] = $this->data['Users']['email'];
            }
            $this->data['Users']['last_login_time'] = gmdate('Y-m-d H:i:s', time());
            //填写的用户信息到session
            $x = $this->User->save($this->data['Users']);

            if (isset($this->data['Address']['RegionUpdate'])) {
                $this->data['UserAddress']['consignee'] = $this->data['Users']['name'];
                $this->data['UserAddress']['regions'] = (isset($this->data['Address']['RegionUpdate'][0]) ? $this->data['Address']['RegionUpdate'][0] : '1').' '.(isset($this->data['Address']['RegionUpdate'][1]) ? $this->data['Address']['RegionUpdate'][1] : '').' '.(isset($this->data['Address']['RegionUpdate'][2]) ? $this->data['Address']['RegionUpdate'][2] : '');
                $this->data['UserAddress']['country'] = isset($this->data['Address']['RegionUpdate'][0]) ? $this->data['Address']['RegionUpdate'][0] : '1';
                $this->data['UserAddress']['province'] = isset($this->data['Address']['RegionUpdate'][1]) ? $this->data['Address']['RegionUpdate'][1] : '';
                $this->data['UserAddress']['city'] = isset($this->data['Address']['RegionUpdate'][2]) ? $this->data['Address']['RegionUpdate'][2] : '';
                if (isset($this->data['Users']['mobile'])) {
                    $this->data['UserAddress']['mobile'] = $this->data['Users']['mobile'];
                }
                $this->data['UserAddress']['user_id'] = $this->User->id;
                $this->UserAddress->save($this->data['UserAddress']);
                $this->data['Users']['address_id'] = $this->UserAddress->id;
                $this->data['Users']['id'] = $this->User->id;
                $this->User->save($this->data['Users']);
            }
            //判断注册是否送积分$this->app_infos
            $user_info = $this->User->find('first', array('conditions' => array('User.id' => $this->User->id)));
            if (isset($this->configs['use_point']) && $this->configs['use_point'] == 1) {
                $register = isset($this->configs['point-register']) ? $this->configs['point-register'] : 0;
                if (isset($register) && $register > 0) {
                    $old_point=$user_info['User']['point'];
                    $user_info['User']['point'] = $register;
                    $user_info['User']['user_point'] = $register;
                    $this->User->save($user_info['User']);
                    $user_point_log = array('id' => '',
                        'user_id' => $user_info['User']['id'],
                        'point' => $old_point,
                        'point_change' => $register,
                        'log_type' => 'R',
                        'system_note' => $this->ld['registration_gift_points'],
                        'type_id' => '0',
                    );
                    $this->UserPointLog->save($user_point_log);
                    $this->UserPointLog->point_notify($user_point_log);
                }
            }
            //推荐注册
            if(isset($this->configs['share_points'])&&$this->configs['share_points']=='1'){
                $share_identification=isset($_SESSION['share_identification'])?$_SESSION['share_identification']:(isset($_COOKIE['share_identification'])?$_COOKIE['share_identification']:'');
                if($share_identification!=''&&isset($this->configs['recommend_points'])&&intval($this->configs['recommend_points'])>0){
                    $this->loadModel('ShareAffiliateLog');
                    $share_affiliate_log=$this->ShareAffiliateLog->find('first',array('conditions'=>array('ShareAffiliateLog.user_id >'=>0,'ShareAffiliateLog.identification'=>$share_identification)));
                    if(!empty($share_affiliate_log)){
                        $share_user_info=$this->User->findById($share_affiliate_log['ShareAffiliateLog']['user_id']);
                        if(!empty($share_user_info)){
                        	$this->User->save(array('id'=>$user_info['User']['id'],'parent_id'=>$share_affiliate_log['ShareAffiliateLog']['user_id']));
                            $this->User->save(array('id'=>$share_affiliate_log['ShareAffiliateLog']['user_id'],'point'=>intval($share_user_info['User']['point'])+intval($this->configs['recommend_points'])));
                            $point_log_data = array(
                                'id' => 0,
                                'log_type'=>'T',
                                'user_id' => $share_affiliate_log['ShareAffiliateLog']['user_id'],
                                'point'=>$share_user_info['User']['point'],
                                'point_change' =>$this->configs['recommend_points'],
                                'system_note' => $this->ld['recommend_friend']
                            );
                            $this->UserPointLog->save($point_log_data);
                            $this->UserPointLog->point_notify($point_log_data);
                        }
                    }
                }
            }
            //判断是否送优惠券  start chenfan 2012/05/25
            if (constant('Product') == 'AllInOne') {
                $this->loadModel('CouponType');
                $this->loadModel('Coupon');
                $now = date('Y-m-d H:i:s');
                $coupon_type = $this->CouponType->find('all', array('conditions' => "CouponType.send_type = '4' and CouponType.send_start_date <= '".$now."' and  CouponType.send_end_date >='".$now."'"));
                if (is_array($coupon_type) && sizeof($coupon_type) > 0) {
                    $coupon_arr_list = $this->Coupon->find('list', array('conditions' => array('1=1'), 'fields' => array('Coupon.sn_code')));
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
                    foreach ($coupon_type as $k => $v) {
                        if (isset($coupon_sn)) {
                            $num = $coupon_sn;
                        }
                        $num = substr($num, 2, 10);
                        $num = $num ? floor($num / 10000) : 100000;
                        $coupon_sn = $v['CouponType']['prefix'].$num.str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                        $coupon = array(
                            'id' => '',
                            'coupon_type_id' => $v['CouponType']['id'],
                            'sn_code' => $coupon_sn,
                            'user_id' => $user_info['User']['id'],
                        );
                        $this->Coupon->save($coupon);
                    }
                }
            }
            //优惠券 end
            if ($x) {
                //绑定公司邀请会员
                App::import('Model', 'OrganizationMember');
                if(trim($user_info['User']['mobile'])!=''&&class_exists('OrganizationMember')){
                    $this->OrganizationMember= new OrganizationMember;
                    $member_cond=array();
                    $member_cond['OrganizationMember.user_id']=0;
                    $member_cond['OrganizationMember.mobile']=trim($user_info['User']['mobile']);
                    $member_cond['OrganizationMember.status']=0;
                    $this->OrganizationMember->updateAll(array('user_id'=>$user_info['User']['id']),$member_cond);
                }
                //邮箱注册发送验证邮件
                if($user_register_mode=='0'){
                    if(isset($this->configs['register_send_mail'])&&$this->configs['register_send_mail']=='1'){
                        extract($user_info['User'],EXTR_PREFIX_ALL,'User');
                        $register_date=strtotime($user_info['User']['created']);
                        $verify_request=base64_encode($User_email."|".$register_date);
                        $verify_link=$this->server_host.$this->webroot.'users/user_verifyemail?verify_request='.$verify_request;
                        $notify_template=$this->NotifyTemplateType->typeformat("register_validate","email");
                        if(!empty($notify_template)){
                            $subject=$notify_template['email']['NotifyTemplateTypeI18n']['title'];
                            @eval("\$subject = \"$subject\";");
                            $html_body = addslashes($notify_template['email']['NotifyTemplateTypeI18n']['param01']);
                            @eval("\$html_body = \"$html_body\";");
                            $text_body = $notify_template['email']['NotifyTemplateTypeI18n']['param02'];
                            @eval("\$text_body = \"$text_body\";");
                            $mail_send_queue = array(
                                'id' => '',
                                'sender_name' => $this->configs['shop_name'],
                                'receiver_email' => $User_email,//接收人姓名;接收人地址
                                'cc_email' => "",
                                'bcc_email' => "",
                                'title' => $subject,
                                'html_body' => $html_body,
                                'text_body' => $text_body,
                                'sendas' => 'html',
                                'flag' => 0,
                                'pri' => 0,
                            );
                            $this->Notify->send_email($mail_send_queue, $this->configs);
                        }
                    }
                }
                if(isset($this->configs['enable_user_log'])&&$this->configs['enable_user_log']=='1'){
                    $this->UserActionLog->update_action(array(
                        'user_id'=>$user_info['User']['id'],
                        'operator_id'=>0,
                        'remark'=>$this->ld['successfully_registered']
                    ),$this);
                }
                $_SESSION['User'] = $user_info;
                $_SESSION['User']['User']['type_level_id'] = 0;
                //跳转到提示页
                if (isset($_SESSION['login_back']) && $_SESSION['login_back'] == '/flashes/index/H') {
                    unset($_SESSION['login_back']);
                }
                $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users/index';
                if (isset($_POST['is_ajax'])) {
                    $messege_error = $this->ld['successfully_registered_into_user_center'];
                    $error_no = 0;
                    $this->layout = 'ajax';
                    $result = array(
                        'result' => $messege_error,
                        'message' => $messege_error,
                        'back_url' => $back_url,
                        'error_no' => $error_no,
                        //'check_email' => $this->data['Users']['user_sn'],
                        'user_data' => $user_info,
                    );
                    Configure::write('debug', 0);
                    die(json_encode($result));
                }
                $this->redirect($back_url);
            } else {
                $this->flash($this->ld['fail_regist'], array('controller' => 'users/register'), '');
            }
        }
        $this->set('messege_error', $messege_error);
    }

    /**
     *登录.
     */
    public function login()
    {
        $this->layout = 'default_full';
        $this->pageTitle = $this->ld['login'].' - '.$this->configs['shop_title'];                    //页面初始化
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['login'],'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $messege_error = '';                        //报错提示变量
        $this->set('sev', $this->server_host);
        //登录注册轮播
        $flash_conditions['Flash.page'] = 'LR';
        $flash_conditions['Flash.type'] = '0';
        $flash_list = $this->Flash->find('first', array('conditions' => $flash_conditions));
        $this->set('flash_list', $flash_list);
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('Payment');
            $config_value = $this->Payment->find('first', array('conditions' => array('Payment.code' => 'alipay'), 'fields' => array('Payment.config')));
            $config_value = unserialize($config_value['Payment']['config']);
        }
        if (isset($config_value['login'])) {
            $this->set('fei', $config_value['login']);
        }
        $syns_cond=array(
            'UserApp.status'=>'1',
            'UserApp.location'=>array(0,2),
            'not'=>array(
                'UserApp.type'=>array('Wechat','QYWechat')
            )
        );
        $syns = $this->UserApp->find('all', array('conditions' => $syns_cond));
        $this->set('syns', $syns);
        if ($this->RequestHandler->isPost()) {
            //是否使用登录验证码
            $use_captcha = isset($this->configs['use_captcha']) && $this->configs['use_captcha'] == '1' ? true : false;
            if ($use_captcha) {
                if (!isset($this->data['Users']['authnum']) || isset($this->data['Users']['authnum']) && $this->captcha->check($this->data['Users']['authnum']) == false) {
                    if (isset($this->configs['login_back_url']) && $this->configs['login_back_url'] == 0) {
                        if (isset($_SESSION['login_back']) && $_SESSION['login_back'] == '/flashes/index/H') {
                            unset($_SESSION['login_back']);
                        }
                        $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users';
                    } elseif ($this->configs['login_back_url'] == 1) {
                        $back_url = '/';
                    } elseif ($this->configs['login_back_url'] == 2) {
                        $back_url = '/users/index';
                    }
                    $messege_error = $this->ld['incorrect_verification_code'];
                    //判断验证码是否正确
                    if (isset($_POST['is_ajax'])) {
                        $error_no = 1;
                        $back_url = '';
                        $this->layout = 'ajax';
                        Configure::write('debug', 1);
                        $result = array(
                            'result' => $messege_error,
                            'message' => $messege_error,
                            'back_url' => $back_url,
                            'error_no' => $error_no,
                        );
                        die(json_encode($result));
                    }
                    $this->set('messege_error', $messege_error);
                    return;
                }
            }
            //登录方式
            if(isset($this->configs['user_login_mode'])&&$this->configs['user_login_mode']=='2'){
                $user_login_mode=isset($_REQUEST['user_login_mode'])?$_REQUEST['user_login_mode']:'0';
            }else{
                $user_login_mode=isset($this->configs['user_login_mode'])?$this->configs['user_login_mode']:'0';
            }
            //判断用户是否存在
            $login_type = isset($_POST['login_type']) ? $_POST['login_type'] : 'user_sn';//判断用户登录的用户名方式
            $user_cond = array();
            if ($login_type == 'user_sn') {
                $user_cond['User.user_sn'] = $_POST['user_name'];
            } elseif ($login_type == 'email') {
                $user_cond['User.email'] = $_POST['user_name'];
                $email=str_replace('.','_',$_POST['user_name']);
                $email_code_key="email_code_number".$email;
                $system_verification_code=isset($_COOKIE[$email_code_key])?$_COOKIE[$email_code_key]:'';
            } else {
                $user_cond['User.mobile'] = $_POST['user_name'];
                $phone_code_key="phone_code_number".$_POST['user_name'];
                $system_verification_code=isset($_COOKIE[$phone_code_key])?$_COOKIE[$phone_code_key]:'';
            }
            if(isset($_POST['verification_code'])&&$_POST['verification_code']!=''&&$user_login_mode=='1'){
                $verification_code=$_POST['verification_code'];
                if($verification_code!=$system_verification_code||$system_verification_code==''){
                    $user_cond['User.id'] = 0 ;
                }
            }else if($user_login_mode=='0'){
                $ps = empty($_POST['md5password']) ? !empty($_POST['password'])?md5($_POST['password']):'': $_POST['md5password'];
                $user_cond['User.password'] = $ps;
            }else{
                $user_cond['User.id'] = 0 ;
            }
            $users = $this->User->find('first', array('conditions' => $user_cond));
            if (in_array('APP-UC-BASE', $this->all_app_codes)) {
                $this->uc_on();
                App::import('Vendor', 'uc/uc_client', array('file' => 'client.php'));
            }
            if ($users == null) {
                if (isset($_POST['is_ajax'])) {
                    $error_no = 1;
                    $messege_error = $this->ld['id_password_wrong'];
                    $back_url = '';
                    $this->set('messege_error', $messege_error);
                    $this->set('error_no', $error_no);
                    $this->set('back_url', $back_url);
                    $this->layout = 'ajax';
                    $this->render('login_result');
                    return;
                }
                if (in_array('APP-UC-BASE', $this->all_app_codes)) {
                    $arr = uc_user_login($_POST['user_name'], $_POST['password']);
                    if (isset($arr['0']) && $arr['0'] > 0) {
                        $data = uc_get_user($login_name);
                        //添加用户
                        $this->data['Users']['password'] = md5($_POST['password']);
                        $this->data['Users']['user_sn'] = $_POST['user_name'];
                        //preg_match("/(.*)@.*/",$this->data["Users"]["email"],$m);
                        $this->data['Users']['last_login_time'] = gmdate('Y-m-d H:i:s', time());
                        //$this->data["Users"]['name']=$m[1];
                        //填写的用户信息到session
                        $x = $this->User->save($this->data['Users']);
                        //判断注册是否送积分$this->app_infos
                        $user_info = $this->User->find('first', array('conditions' => array('User.id' => $this->User->id)));
                        if (isset($this->configs['point-register']) && $this->configs['point-register'] > 0) {
                            $register = $this->configs['point-register'];
                            if (isset($register) && $register > 0) {
                                $old_point=$user_info['User']['point'];
                                $user_info['User']['point'] = $register;
                                $user_info['User']['user_point'] = $register;
                                $this->User->save($user_info);
                                $user_point_log = array('id' => '',
                                    'user_id' => $user_info['User']['id'],
                                    'point' => $old_point,
                                    'point_change' => $register,
                                    'log_type' => 'R',
                                    'system_note' => $this->ld['registration_gift_points'],
                                    'type_id' => '0',
                                );
                                $this->UserPointLog->save($user_point_log);
                                $this->UserPointLog->point_notify($user_point_log);
                            }
                        }
                        //判断是否送优惠券  start chenfan 2012/05/25
                        $now = date('Y-m-d H:i:s');
                        $coupon_type = $this->CouponType->find('all', array('conditions' => "CouponType.send_type = '4' and CouponType.send_start_date <= '".$now."' and  CouponType.send_end_date >='".$now."'"));
                        if (is_array($coupon_type) && sizeof($coupon_type) > 0) {
                            //	$coupon_arr = $this->Coupon->findall("1=1",'DISTINCT Coupon.sn_code');
                            $coupon_arr_list = $this->Coupon->find('list', array('conditions' => array('1=1'), 'fields' => array('Coupon.sn_code')));
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
                            foreach ($coupon_type as $k => $v) {
                                if (isset($coupon_sn)) {
                                    $num = $coupon_sn;
                                }
                                $num = substr($num, 2, 10);
                                $num = $num ? floor($num / 10000) : 100000;
                                $coupon_sn = $v['CouponType']['prefix'].$num.str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);
                                $coupon = array(
                                    'id' => '',
                                    'coupon_type_id' => $v['CouponType']['id'],
                                    'sn_code' => $coupon_sn,
                                    'user_id' => $user_info['User']['id'],
                                );
                                $this->Coupon->save($coupon);
                            }
                        }

                        if ($x) {
                            $_SESSION['User'] = $user_info;
                            //跳转到提示页
                            if (isset($this->configs['login_back_url']) && $this->configs['login_back_url'] == 0) {
                                if (isset($_SESSION['login_back']) && $_SESSION['login_back'] == '/flashes/index/H') {
                                    unset($_SESSION['login_back']);
                                }
                                $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users';
                            } elseif ($this->configs['login_back_url'] == 1) {
                                $back_url = '/';
                            } elseif ($this->configs['login_back_url'] == 2) {
                                $back_url = '/users/index';
                            }
//							$lan=array('/en','/cn','/jp');
//							$back_url=str_replace($lan,'',$back_url);
                            $this->flash($this->ld['successfully_registered_into_user_center'], $back_url, 2);
                        } else {
                            $this->flash($this->ld['fail_regist'], array('controller' => 'users/register'), '');
                        }
                    }
                }
                $messege_error = $this->ld['id_password_wrong'];
            } else {
                //判断是否是分销商
                //如果是分销商 添加分销商等级
                if ($users['User']['type'] == 1) {
          //          $distributorInfo = $this->FenxiaoDistributor->find('first', array('conditions' => array('FenxiaoDistributor.user_id' => $users['User']['id'])));
           //         $users['User']['type_level_id'] = isset($distributorInfo['FenxiaoDistributor']['distributor_level_id']) ? $distributorInfo['FenxiaoDistributor']['distributor_level_id'] : 0;
                }
                if (isset($_POST['status']) && $_POST['status'] == 1) {
                    //选择自动登录的，将用户保存到cookie，设为2周有效
                    setcookie('user_info', serialize($users), time() + 60 * 60 * 24 * 14, '/');
                } else {
                    setcookie('user_info', null, time() - 60 * 60 * 24 * 14, '/');
                }
                //将用户信息存到session
                $_SESSION['User'] = $users;
                $x = $users['User']['id'];
                $this->User->updateAll(array('User.last_login_time' => "'".gmdate('Y-m-d H:i:s', time())."'"), array('User.id' => $x));
                /*
                    验证用户是否会员到期
                */
                $this->UserRankLog->checkUserRank($x);
                if (in_array('APP-UC-BASE', $this->all_app_codes)) {
                    $arr = uc_user_login($_POST['user_name'], $_POST['password']);
                    if (isset($arr['0']) && $arr['0'] == '-1') {
                        $rs = mysql_fetch_object($result);
                        $mails = $rs->user_email;
                        $arr = uc_user_register($_POST['user_name'], $_POST['password'], $_POST['user_name']);
                        $arr = uc_user_login($_POST['user_name'], $_POST['password']);
                    }
                    if (isset($arr['0']) && $arr['0'] > 0) {
                        $arr = uc_user_synlogin($arr['0']);
                    }//var_dump($this->app_infos['APP-UC-BASE']['configs']);}
                }
                if (isset($_SESSION['login_back'])) {
                    if ($_SESSION['login_back'] == '/en/' || $_SESSION['login_back'] == '/cn/' || $_SESSION['login_back'] == '/jp/') {
                        $_SESSION['login_back'] = '/';
                    }
                }
                if (isset($this->configs['login_back_url']) && $this->configs['login_back_url'] == 0) {
                    if (isset($_SESSION['login_back']) && $_SESSION['login_back'] == '/flashes/index/H') {
                        unset($_SESSION['login_back']);
                    }
                    $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users';
                } elseif ($this->configs['login_back_url'] == 1) {
                    $back_url = '/';
                } elseif ($this->configs['login_back_url'] == 2) {
                    $back_url = '/users/index';
                }
                $lan = array('/en/','/cn/','/jp/');
                $back_url = str_replace($lan, '/', $back_url);
                $rank_code = $this->UserRank->get_rank_code($users['User']['rank']);
                if (isset($_POST['is_ajax'])) {
                    $error_no = 0;
                    $this->set('back_url', $back_url);
                    $this->set('user_name', $users['User']['name']);
                    $this->set('user_rank', $rank_code);
                    $this->set('error_no', $error_no);
                    $this->set('messege_error', $messege_error);
                    $this->set('user_data', $users);
                    $this->layout = 'ajax';
                    $this->render('login_result');
                    return;
                }
                $this->redirect($back_url);
                exit();
            }
        }
        $this->set('messege_error', $messege_error);
        /*
            判断是否为手机版
        */
        if ($this->is_mobile) {
            $this->layout = 'mobile/default_full';
            $this->render('mobile/login');
            Configure::write('debug', 1);
        }
        if(!empty($this->wechat_loginobj)){
            if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false) {
                //$this->redirect("/synchros/opauth/wechat");
            }
        }
    }

    public function ajax_qy_wechat($action_type='0'){
        Configure::write('debug',1);
        $this->layout = 'ajax';
        $this->loadModel('Organization');
        $this->loadModel('OrganizationApp');
        $result=array();
        $result['code']='0';
        $result['data']=array();
        if($action_type==0){
            $qy_wechat=array();
            $system_qy_wechat=$this->UserApp->find('first', array('fields'=>array('UserApp.app_key','UserApp.app_code','UserApp.app_id'),'conditions' => array('UserApp.status' => 1, 'UserApp.type' => 'QYWechat','UserApp.location'=>array(0,2))));
            if(!empty($system_qy_wechat)){
                $system_qy_wechat['UserApp']['name']=$this->ld['default'];
                $qy_wechat['0']=$system_qy_wechat['UserApp'];
            }
            $organization_qy_wechat=$this->OrganizationApp->find('count', array('conditions' => array('OrganizationApp.status' => 1, 'OrganizationApp.type' => 'QYWechat')));
            if($organization_qy_wechat>0){
                $qy_wechat['1']=$organization_qy_wechat;
            }
            if(!empty($qy_wechat)){
                $result['code']='1';
                $result['data']=$qy_wechat;
                $result['user_agent']=isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
            }
        }else if($action_type==1){
            $organization_name=isset($_POST['organization_name'])?trim($_POST['organization_name']):'';
            if($organization_name!=''){
                $OrganizationInfo=$this->Organization->find('first',array('conditions'=>array('Organization.status'=>'1','Organization.authentication_status'=>'3','Organization.name'=>$organization_name)));
                if(!empty($OrganizationInfo)){
                    $organization_id=$OrganizationInfo['Organization']['id'];
                    $OrganizationAppInfo=$this->OrganizationApp->find('first',array('fields'=>array('OrganizationApp.app_key','OrganizationApp.app_code','OrganizationApp.app_id'),'conditions'=>array('OrganizationApp.status'=>'1','OrganizationApp.type'=>'QYWechat','OrganizationApp.organization_id'=>$organization_id)));
                    if(!empty($OrganizationAppInfo)){
                        $result['code']='1';
                        $result['data']=$OrganizationAppInfo['OrganizationApp'];
                        $result['user_agent']=isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
                    }
                }
            }
        }
        die(json_encode($result));
    }

    public function cellphone_login()
    {
        $ps = empty($_POST['md5password']) ? md5($_POST['password']) : $_POST['md5password'];
        $users = $this->User->find('first', array('conditions' => array('user_sn' => $_POST['user_name'], 'password' => $ps)));
        if (in_array('APP-UC-BASE', $this->all_app_codes)) {
            $this->uc_on();
            App::import('Vendor', 'uc/uc_client', array('file' => 'client.php'));
        }
        $result = array();
        if ($users == null) {
            $result['flag'] = 0;
            $result['msg'] = $this->ld['id_password_wrong'];//'user name or password error!';
        } else {
            $result['flag'] = 1;
            if (isset($_POST['status']) && $_POST['status'] == 1) {
                setcookie('user_info', serialize($users), time() + 60 * 60 * 24 * 14, '/');
            }
            //将用户信息存到session
            $_SESSION['User'] = $users;
            $x = $users['User']['id'];
            $this->User->updateAll(array('User.last_login_time' => "'".gmdate('Y-m-d H:i:s', time())."'"), array('User.id' => $x));
            if (in_array('APP-UC-BASE', $this->all_app_codes)) {
                $arr = uc_user_login($_POST['user_name'], $_POST['password']);
                if (isset($arr['0']) && $arr['0'] == '-1') {
                    $rs = mysql_fetch_object($result);
                    $mails = $rs->user_email;
                    $arr = uc_user_register($_POST['user_name'], $_POST['password'], $_POST['user_name']);
                    $arr = uc_user_login($_POST['user_name'], $_POST['password']);
                }
                if (isset($arr['0']) && $arr['0'] > 0) {
                    $arr = uc_user_synlogin($arr['0']);
                }
            }
        }
        if (isset($_SESSION['login_back'])) {
            if ($_SESSION['login_back'] == '/en/' || $_SESSION['login_back'] == '/cn/' || $_SESSION['login_back'] == '/jp/') {
                $_SESSION['login_back'] = '/';
            }
        }
        if ($_SESSION['login_back'] == '/flashes/index/H') {
            unset($_SESSION['login_back']);
        }
        $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users';
        $lan = array('/en','/cn','/jp');
        $back_url = str_replace($lan, '', $back_url);
        $result['url'] = $back_url;
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        die(json_encode($result));
    }

    public function uc_on()
    {
        define('ROOT_PATH', str_replace('api', '', str_replace('\\', '/', dirname(dirname(dirname(__FILE__))))).DS.'vendors'.DS.'uc'.DS);
        define('UC_CONNECT', 'mysql');
        define('UC_DBHOST', $this->app_infos['APP-UC-BASE']['configs']['APP-UC-BASE-DBHOST']);
        define('UC_DBUSER', $this->app_infos['APP-UC-BASE']['configs']['APP-UC-BASE-DBUSER']);
        define('UC_DBPW', $this->app_infos['APP-UC-BASE']['configs']['APP-UC-BASE-DBPW']);
        define('UC_DBNAME', $this->app_infos['APP-UC-BASE']['configs']['APP-UC-BASE-DBNAME']);
        define('UC_DBCHARSET', $this->app_infos['APP-UC-BASE']['configs']['APP-UC-BASE-DBCHARSET']);
        define('UC_DBTABLEPRE', $this->app_infos['APP-UC-BASE']['configs']['APP-UC-BASE-DBTABLEPRE']);
        define('UC_DBCONNECT', '0');
        define('UC_KEY', $this->app_infos['APP-UC-BASE']['configs']['APP-UC-BASE-KEY']);
        define('UC_API', $this->app_infos['APP-UC-BASE']['configs']['APP-UC-BASE-API']);
        define('UC_CHARSET', 'utf-8');
        define('UC_IP', 1);
        define('UC_APPID', 1);
        define('UC_PPP', '20');
        define('UC_CLIENT_VERSION', '1.5.0');  //note UCenter 版本标识
        define('UC_CLIENT_RELEASE', '20081031');
        define('API_DELETEUSER', 1);    //note 用户删除 API 接口开关
        define('API_RENAMEUSER', 1);    //note 用户改名 API 接口开关
        define('API_GETTAG', 1);        //note 获取标签 API 接口开关
        define('API_SYNLOGIN', 1);      //note 同步登录 API 接口开关
        define('API_UPDATEPW', 1);      //note 更改用户密码 开关
        define('API_UPDATEBADWORDS', 1);//note 更新关键字列表 开关
        define('API_UPDATEHOSTS', 1);   //note 更新域名解析缓存 开关
        define('API_UPDATEAPPS', 1);    //note 更新应用列表 开关
        define('API_UPDATECLIENT', 1);  //note 更新客户端缓存 开关
        define('API_UPDATECREDIT', 1);  //note 更新用户积分 开关
        define('API_GETCREDITSETTINGS', 1);  //note 向 UCenter 提供积分设置 开关
        define('API_GETCREDIT', 1);     //note 获取用户的某项积分 开关
        define('API_UPDATECREDITSETTINGS', 1);  //note 更新应用积分设置 开关
        define('API_RETURN_SUCCEED', '1');
        define('API_RETURN_FAILED', '-1');
        define('API_RETURN_FORBIDDEN', '-2');
        define('IN_ECS', true);
    }

    public function edit_headimg(){
	        //登录验证
	        $this->checkSessionUser();
	        $this->pageTitle = $this->ld['upload_photos'].' - '.$this->configs['shop_title'];
	        $this->layout = 'usercenter';            //引入模版
	        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
	            Configure::write('debug', 0);
	            $this->layout = 'ajax';
	        }
	        $this->page_init();                    //页面初始化
	        //当前位置
	        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
	        $this->ur_heres[] = array('name' => $this->ld['upload_photos'],'url' => '');
	        $this->set('ur_heres', $this->ur_heres);
	        $id = $_SESSION['User']['User']['id'];
	        //获取我的信息
	        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
	        $_SESSION['User'] = $user_list;
	        if ($user_list['User']['address_id'] != '0') {
	            //获取我的地址
	            $user_list['User']['UserAddress'] = $this->UserAddress->find('first', array('conditions' => array('id' => $user_list['User']['address_id']), 'fields' => 'id,zipcode,address_type,address,mobile,regions,sign_building'));
	        }
	        $this->set('user_list', $user_list);
	        //分享绑定显示判断
	        $app_share = $this->UserApp->app_status();
	        $this->set('app_share', $app_share);
	        //粉丝数量
	        $fans = $this->UserFans->find_fanscount_byuserid($id);
	        $this->set('fanscount', $fans);
	        //日记数量
	        $blog = $this->Blog->find_blogcount_byuserid($id);
	        $this->set('blogcount', $blog);
	        //关注数量
	        $focus = $this->UserFans->find_focuscount_byuserid($id);
	        $this->set('focuscount', $focus);
	        
	        if ($this->RequestHandler->isPost()) {
        		Configure::write('debug', 1);
        		$this->layout = 'ajax';
        		$result=array('code'=>'0','message'=>'上传失败');
        		$img_root = 'media/users/'.date('Ym').'/';
        		$imgaddr = WWW_ROOT.'media/users/'.date('Ym').'/';
        		$this->mkdirs($imgaddr);
        		@chmod($imgaddr, 0777);
                	if ((isset($_FILES['UserAvatar']['error'])) && ($_FILES['UserAvatar']['error'] == 0)) {
                		$userfile_name = $_FILES['UserAvatar']['name'];
                    	$userfile_tmp = $_FILES['UserAvatar']['tmp_name'];
                    	$userfile_size = $_FILES['UserAvatar']['size'];
                    	$userfile_type = $_FILES['UserAvatar']['type'];
                    	$filename = basename($_FILES['UserAvatar']['name']);
                    	$file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
                    	$image_location = $imgaddr.md5(date('Y-m-d h:i:s').$id.$userfile_name).'.'.$file_ext;
                    	$image_name = '/'.$img_root.md5(date('Y-m-d h:i:s').$id.$userfile_name).'.'.$file_ext;
                    	if (move_uploaded_file($userfile_tmp, $image_location)) {
                    		$dst_json=isset($_POST['dst_json'])&&trim($_POST['dst_json'])!=''?json_decode($_POST['dst_json'],true):array();
                    		if(isset($dst_json['w'])){
                    			$ImageSize = getimagesize($image_location);
                    			$ImageWidth=$ImageSize[0];
                    			$ImageHeight=$ImageSize[1];
                    			$this->resizeImage($image_location,isset($_POST['preview_width'])&&floatval($_POST['preview_width'])>0?floatval($_POST['preview_width']):$ImageWidth,isset($_POST['preview_height'])&&floatval($_POST['preview_height'])>0?floatval($_POST['preview_height']):$ImageHeight);
                    			$this->resizeThumbnailImage($image_location, $image_location, $dst_json['w'], $dst_json['h'], $dst_json['x'], $dst_json['y'],1);
                    		}
                    		$user_data=array(
                    			'id'=>$id,
                    			'img01'=>$image_name
                    		);
                			$this->User->save($user_data);
                			if(isset($user_list['User']['img01'])&&trim($user_list['User']['img01'])!=''){
                				$old_user_img=WWW_ROOT.$user_list['User']['img01'];
                				if(file_exists($old_user_img)&&is_file($old_user_img))@unlink($old_user_img);
                			}
                    		$result=array('code'=>'1','message'=>$this->ld['success_regist']);
                    	}
                	}
                	die(json_encode($result));
	        }
    }
    
    /*
    		按设置尺寸调整图片大小
    */
    public function resizeImage($image, $newImageWidth, $newImageHeight)
    {
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType = image_type_to_mime_type($imageType);
        $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
        switch ($imageType) {
            case 'image/gif':
                $source = imagecreatefromgif($image);
                break;
            case 'image/pjpeg':
            case 'image/jpeg':
            case 'image/jpg':
                $source = imagecreatefromjpeg($image);
                break;
            case 'image/png':
            case 'image/x-png':
                $source = imagecreatefrompng($image);
                break;
        }
        imagecopyresampled($newImage, $source, 0, 0, 0, 0, $newImageWidth, $newImageHeight, $imagewidth, $imageheight);
        switch ($imageType) {
            case 'image/gif':
                imagegif($newImage, $image);
                break;
            case 'image/pjpeg':
            case 'image/jpeg':
            case 'image/jpg':
                imagejpeg($newImage, $image, 90);
                break;
            case 'image/png':
            case 'image/x-png':
                imagepng($newImage, $image);
                break;
        }
        chmod($image, 0777);
        return $image;
    }

    
    /*
        裁剪头像图片
    */
    public function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale)
    {
        list($imagewidth, $imageheight, $imageType) = getimagesize($image);
        $imageType = image_type_to_mime_type($imageType);
        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($newImageWidth, $newImageHeight);
        switch ($imageType) {
            case 'image/gif':
                $source = imagecreatefromgif($image);
                break;
            case 'image/pjpeg':
            case 'image/jpeg':
            case 'image/jpg':
                $source = imagecreatefromjpeg($image);
                break;
            case 'image/png':
            case 'image/x-png':
                $source = imagecreatefrompng($image);
                break;
        }
        imagecopyresampled($newImage, $source, 0, 0, $start_width, $start_height, $newImageWidth, $newImageHeight, $width, $height);
        switch ($imageType) {
            case 'image/gif':
                imagegif($newImage, $thumb_image_name);
                break;
            case 'image/pjpeg':
            case 'image/jpeg':
            case 'image/jpg':
                imagejpeg($newImage, $thumb_image_name, 90);
                break;
            case 'image/png':
            case 'image/x-png':
                imagepng($newImage, $thumb_image_name);
                break;
        }
        chmod($thumb_image_name, 0777);
        return $thumb_image_name;
    }

    /**
     *编辑我的个人档案.
     */
    public function edit()
    {
        //登录验证
        $this->checkSessionUser();
        $this->pageTitle = $this->ld['account_profile'].' - '.$this->configs['shop_title'];
        $this->layout = 'usercenter';            //引入模版
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            Configure::write('debug', 0);
            $this->layout = 'ajax';
        }
        $this->page_init();                    //页面初始化
        $id = $_SESSION['User']['User']['id'];
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => $this->ld['account_profile'],'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $this->loadModel('Ability');$this->loadModel('UserAbility');
        if ($this->RequestHandler->isPost()) {
            if(isset($_POST['submit_review'])){
                $this->data['Users']['verify_status'] = '1';
                $this->data['Users']['unvalidate_note'] = '';
            }
            $this->data['UserAddress']['user_id'] = $id;
            if (isset($this->data['Address']['RegionUpdate'])) {
                $this->data['UserAddress']['regions'] = (isset($this->data['Address']['RegionUpdate'][0]) ? $this->data['Address']['RegionUpdate'][0] : '1').' '.(isset($this->data['Address']['RegionUpdate'][1]) ? $this->data['Address']['RegionUpdate'][1] : '').' '.(isset($this->data['Address']['RegionUpdate'][2]) ? $this->data['Address']['RegionUpdate'][2] : '');
            }
            $this->data['UserAddress']['country'] = isset($this->data['Address']['RegionUpdate'][0]) ? $this->data['Address']['RegionUpdate'][0] : '1';
            $this->data['UserAddress']['province'] = isset($this->data['Address']['RegionUpdate'][1]) ? $this->data['Address']['RegionUpdate'][1] : '';
            $this->data['UserAddress']['city'] = isset($this->data['Address']['RegionUpdate'][2]) ? $this->data['Address']['RegionUpdate'][2] : '';
            if (isset($this->data['Users']['mobile'])) {
                $this->data['UserAddress']['mobile'] = $this->data['Users']['mobile'];
            }
            if(isset($this->data['UserAddress']['address'])&&$this->data['UserAddress']['address'] != ''&&$this->data['Users']['first_name'] != ''){
                $this->data['UserAddress']['consignee']=$this->data['Users']['first_name'];
                $this->UserAddress->save($this->data['UserAddress']);
                $this->data['Users']['address_id'] = $this->UserAddress->id;
            }
            $this->User->save($this->data['Users']);
            //绑定公司邀请会员
            App::import('Model', 'OrganizationMember');
            if(isset($this->data['Users']['mobile'])&&trim($this->data['Users']['mobile'])!=''&&class_exists('OrganizationMember')){
                $this->OrganizationMember= new OrganizationMember;
                $member_cond=array();
                $member_cond['OrganizationMember.user_id']=0;
                $member_cond['OrganizationMember.mobile']=trim($this->data['User']['mobile']);
                $member_cond['OrganizationMember.status']=0;
                $this->OrganizationMember->updateAll(array('user_id'=>$id),$member_cond);
            }
            if(isset($this->data['UserConfig']['user_review'])&&!empty($this->data['UserConfig']['user_review'])){
                foreach($this->data['UserConfig']['user_review'] as $k=>$v){
                    $user_config_data=array(
                        'id'=>$v['id'],
                        'user_id'=>$id,
                        'type'=>'user_review',
                        'code'=>$k,
                        'value'=>$v['value']
                    );
                    $this->UserConfig->save($user_config_data);
                }
            }
            $this->UserAbility->updateAll(array('UserAbility.status'=>"'0'"),array('UserAbility.user_id'=>$id));
            if(isset($this->data['UserAbility'])&&!empty($this->data['UserAbility'])){
                foreach($this->data['UserAbility'] as $ability_data){
                    if(!isset($ability_data['ability_id']))continue;
                    $ability_data['user_id']=$id;
                    $ability_data['status']='1';
                    $this->UserAbility->save($ability_data);
                }
            }
            if(isset($this->configs['enable_user_log'])&&$this->configs['enable_user_log']=='1'){
                $this->UserActionLog->update_action(array(
                    'user_id'=>$id,
                    'operator_id'=>0,
                    'remark'=>$this->ld['account_profile']
                ),$this);
            }
            if(isset($_POST['is_ajax'])&&$_POST['is_ajax']=='1'){
                $result=array('code'=>'1','message'=>$this->ld['success_regist']);
                die(json_encode($result));
            }else{
                $this->flash($this->ld['success_regist'], '/users/edit', '');
            }
        }
        if (isset($_SESSION['User']['User']['id'])) {
            //分享绑定显示判断
            $app_share = $this->UserApp->app_status();
            $this->set('app_share', $app_share);
            //获取我的信息
            $user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
            $_SESSION['User'] = $user_list;
            if ($user_list['User']['address_id'] != '0') {
                //获取我的地址
                $user_list['User']['UserAddress'] = $this->UserAddress->find('first', array('conditions' => array('id' => $user_list['User']['address_id'])));
            }
            $this->set('user_list', $user_list);
            //粉丝数量
            $fans = $this->UserFans->find_fanscount_byuserid($id);
            $this->set('fanscount', $fans);
            //日记数量
            $blog = $this->Blog->find_blogcount_byuserid($id);
            $this->set('blogcount', $blog);
            //关注数量
            $focus = $this->UserFans->find_focuscount_byuserid($id);
            $this->set('focuscount', $focus);
            $userRank_end_time = '';
            if ($user_list['User']['rank'] != '0') {
                $max_end_time = $this->UserRankLog->find('first', array('fields' => 'max(end_date) as max_end_time', 'conditions' => array('UserRankLog.user_id' => $_SESSION['User']['User']['id'])));
                if (!empty($max_end_time[0])) {
                    $this_time = date('Y-m-d H:i:s');
                    $end_time = $max_end_time[0]['max_end_time'];
                    if ($end_time != '0000-00-00 00:00:00' && $end_time != '') {
                        if (strtotime($this_time) >= strtotime($end_time)) {
                            //会员已到期
                            $userRank_end_time = '';
                        } else {
                            $userRank_end_time = date('Y-m-d', strtotime($end_time));
                        }
                    }
                }
            }
            $this->set('userRank_end_time', $userRank_end_time);
        }
        //会员等级列表
        $rank_list = $this->UserRank->find('all', array('conditions' => array('UserRankI18n.locale' => $this->locale)));
        $user_rank_data = array();
        foreach ($rank_list as $k => $v) {
            $user_rank_data[$v['UserRank']['id']] = $v['UserRankI18n']['name'];
        }
        $this->set('user_rank_data', $user_rank_data);
        //用户配置信息（审核信息）
        $this->UserConfig->set_locale($this->locale);
        $users_config_group_list=array();
        $user_review_configs_data= $this->UserConfig->find('all', array('conditions' => array('UserConfig.user_id' =>array(0,$id), 'type' => 'user_review'),'order'=>'UserConfig.created'));
        $review_configs=array();
        $user_review_data=array();
        if(!empty($user_review_configs_data)){
            foreach($user_review_configs_data as $v){
                if(!empty($v['UserConfig']['group_code'])){
                    $users_config_group_list[$v['UserConfig']['group_code']]=$v['UserConfig']['group_code'];
                }
                if($v['UserConfig']['user_id']==0){
                    $review_configs[$v['UserConfig']['group_code']][]=$v;
                }else{
                    $user_review_data[$v['UserConfig']['type']][$v['UserConfig']['code']]=$v['UserConfig'];
                }
            }
        }
        if(!empty($users_config_group_list)){
            $user_config_group_code=$this->SystemResource->find('all',array('fields'=>array('SystemResource.resource_value','SystemResourceI18n.name'),'conditions'=>array('SystemResource.resource_value'=>$users_config_group_list)));
            foreach($user_config_group_code as $v){
                $user_config_group_list[$v['SystemResource']['resource_value']]=$v['SystemResourceI18n']['name'];
            }
            $this->set('user_config_group_list', $user_config_group_list);
        }
        $this->set('review_configs',$review_configs);
        $this->set('user_review_data',$user_review_data);
        $user_category_id=$user_list['User']['category_id'];
        if(!empty($user_category_id)){
            $user_category_data=$this->UserCategory->find('list',array('conditions'=>array('UserCategory.status'=>'1','UserCategory.id'=>$user_category_id)));
            $this->set('user_category_data',$user_category_data);
        }
        $AbilityInfo=$this->Ability->find('list',array('conditions'=>array('Ability.status'=>'1'),'fields'=>'Ability.id,Ability.name'));
        $this->set('AbilityInfo',$AbilityInfo);
        $UserAbilityInfo=$this->UserAbility->find('list',array('conditions'=>array('UserAbility.user_id'=>$id),'fields'=>'UserAbility.ability_id,UserAbility.id'));
        $this->set('UserAbilityInfo',$UserAbilityInfo);
    }

    /**
     *修改个人密码.
     */
    public function edit_pwd()
    {
        //登录验证
        $this->checkSessionUser();
        $this->pageTitle = $this->ld['change_password'].' - '.$this->configs['shop_title'];
        $this->layout = 'usercenter';        //引入模版
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            Configure::write('debug', 0);
            $this->layout = 'ajax';
        }
        $this->page_init();                    //页面初始化
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => $this->ld['change_password'],'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        if ($this->RequestHandler->isPost()) {
            $result=array();
            $result['code']='0';
            if (isset($this->data['User']['password1']) && $this->data['User']['password2'] != md5($this->data['User']['password1'])) {
                $result['message']=$this->ld['pwd_error'];
            } else {
                $this->User->updateAll(
                    array('User.password' => "'".md5($this->data['User']['password'])."'"),
                    array('User.id' => $this->data['User']['id'])
                );
                $result['code']='1';
                $result['message']=$this->ld['success_regist'];
                $user_data = $this->User->find('first', array('conditions' => array('User.id' => $this->data['User']['id'])));
                if(!empty($user_data)){
                    $notify_template_info=$this->NotifyTemplateType->typeformat("edit_password","wechat");
                    $notify_template=isset($notify_template_info['wechat'])?$notify_template_info['wechat']:array();
                    $wechat_params=$this->NotifyTemplateType->wechatparamsformat($notify_template);
                    if(!empty($notify_template)){//需要发送通知
                        $synchro_user = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.status' => 1, 'SynchroUser.type' => 'wechat','SynchroUser.user_id'=>$this->data['User']['id'])));
                        if(!empty($synchro_user['SynchroUser'])){
                            $action_content="亲爱的用户,您的账户密码已修改";
                            $user_name=$user_data['User']['name'];
                            $action_time=date('Y-m-d H:i:s');
                            $action_desc="如非本人操作,请及时联系客服";
                            $wechat_message=array();
                            foreach($wechat_params as $k=>$v){
                                $wechat_message[$k]=array(
                                    'value'=>isset($$v)?$$v:''
                                );
                            }
                            $wechat_post=array(
                                'touser'=>$synchro_user['SynchroUser']['account'],
                                'template_id'=>$notify_template['NotifyTemplateTypeI18n']['param03'],
                                'url'=>$this->server_host,
                                'data'=>$wechat_message
                            );
                            $this->Notify->wechat_message($wechat_post);
                        }
                    }
                }
            }
            if(isset($_POST['is_ajax'])&&$_POST['is_ajax']=='1'){
                die(json_encode($result));
            }else{
                $this->flash($result['message'], '/users/edit_pwd', 3);
            }
        }
        if (isset($_SESSION['User']['User']['id'])) {
            //分享绑定显示判断
            $app_share = $this->UserApp->app_status();
            $this->set('app_share', $app_share);
            //获取我的信息
            $user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
            $_SESSION['User'] = $user_list;
            if ($user_list['User']['address_id'] != '0') {
                //获取我的地址
                $user_list['User']['UserAddress'] = $this->UserAddress->find('first', array('conditions' => array('id' => $user_list['User']['address_id']), 'fields' => 'id,zipcode,address_type,address,mobile,regions,sign_building'));
            }
            $this->set('user_list', $user_list);
            $id = $_SESSION['User']['User']['id'];
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
    }

    /**
     *找回密码.
     */
    public function forget_password()
    {
        //页面初始化
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            Configure::write('debug', 1);
            $this->layout = 'ajax';
        }
        $this->pageTitle = $this->ld['forget_password'].' - '.$this->configs['shop_title'];
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['forget_password']);
        $this->set('ur_heres', $this->ur_heres);
        $forget_error = '';
        if ($this->RequestHandler->isPost()){
            $result=array(
                'code'=>'0',
                'message'=>$this->ld['j_format_is_incorrect']
            );
            $conditions=array();
            $conditions['User.status']='1';
            if(isset($_POST['email'])&&trim($_POST['email'])!=''){
                $conditions['User.email']=$_POST['email'];
            }else if(isset($_POST['mobile'])&&trim($_POST['mobile'])!=''){
                $conditions['User.mobile']=$_POST['mobile'];
            }else{
                $conditions['User.id']=0;
            }
            $user_info = $this->User->find('first', array('conditions' =>$conditions));
            if(!empty($user_info)){
                $verify_code=isset($_POST['verify_code']) && !empty($_POST['verify_code'])?$_POST['verify_code']:'';
                $system_verify_code="";
                if(isset($_POST['email'])){
                    $email=str_replace('.','_',$_POST['email']);
                    $email_code_key="email_code_number{$email}";
                    $system_verify_code=isset($_COOKIE[$email_code_key])?$_COOKIE[$email_code_key]:'';
                }else if(isset($_POST['mobile'])){
                    $mobile=$_POST['mobile'];
                    $phone_code_key="phone_code_number{$mobile}";
                    $system_verify_code=isset($_COOKIE[$phone_code_key])?$_COOKIE[$phone_code_key]:'';
                }
                if($verify_code!=$system_verify_code||$system_verify_code==""){
                    $result['message'] = $this->ld['incorrect_verification_code'];
                    $result['system_verify_code'] = $system_verify_code;
                }else{
                    $password=isset($_POST['password'])?trim($_POST['password']):'';
                    $user_data=array();
                    $user_data['id']=$user_info['User']['id'];
                    $user_data['password']=md5($password);
                    $this->User->save($user_data);
                    $notify_template_info=$this->NotifyTemplateType->typeformat("edit_password","wechat");
                    $notify_template=isset($notify_template_info['wechat'])?$notify_template_info['wechat']:array();
                    $wechat_params=$this->NotifyTemplateType->wechatparamsformat($notify_template);
                    $synchro_user = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.status' => 1, 'SynchroUser.type' => 'wechat','SynchroUser.user_id'=>$this->data['User']['id'])));
                    if(!empty($notify_template)&&!empty($synchro_user['SynchroUser'])){
                        $action_content="亲爱的用户,您的账户密码已修改";
                        $user_name=$user_info['User']['name'];
                        $action_time=date('Y-m-d H:i:s');
                        $action_desc="如非本人操作,请及时联系客服";
                        $wechat_message=array();
                        foreach($wechat_params as $k=>$v){
                            $wechat_message[$k]=array(
                                'value'=>isset($$v)?$$v:''
                            );
                        }
                        $wechat_post=array(
                            'touser'=>$synchro_user['SynchroUser']['account'],
                            'template_id'=>$notify_template['NotifyTemplateTypeI18n']['param03'],
                            'url'=>$this->server_host,
                            'data'=>$wechat_message
                        );
                        $this->Notify->wechat_message($wechat_post);
                    }
                    $result['code']='1';
                    $result['message'] = $this->ld['saved_successfully'];
                }
            }else{
                $result['message'] = $this->ld['user_not_exist'];
            }
            die(json_encode($result));
        }
        $this->set('forget_error', $forget_error);
    }

    function ajax_forget_password_mail(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array(
            'code'=>'0',
            'message'=>$this->ld['j_format_is_incorrect']
        );
        $user_mail=isset($_POST['email'])?$_POST['email']:'';
        if($user_mail!=''){
            $user_info = $this->User->find('first', array('conditions' => array('User.email' => $user_mail)));
            if (!empty($user_info)) {
                $totify_template=$this->NotifyTemplateType->typeformat("forget_password","email");
                $email_code_number=rand(1000,9999);
                $lifeTime = 60*60*3;
                $email_code_key="email_code_number{$user_mail}";
                setcookie($email_code_key,$email_code_number, time() + $lifeTime, "/");
                $user_name=$user_info['User']['name'];
                $shop_url= $this->server_host.$this->webroot;
                $shop_name = $this->configs['shop_name'];
                $email_md5 = md5($user_info['User']['email'].$user_info['User']['id']);
                $reset_email = $this->server_host.$this->webroot.'users/reset_password?em='.$email_md5;
                $this->User->save(array('id'=>$user_info['User']['id'],'mail_pass'=>$email_md5));
                $send_date = date('Y-m-d H:i:s');
                $subject = $totify_template['email']['NotifyTemplateTypeI18n']['title'];
                @eval("\$subject = \"$subject\";");
                $html_body = addslashes($totify_template['email']['NotifyTemplateTypeI18n']['param01']);
                @eval("\$html_body = \"$html_body\";");
                $text_body = $totify_template['email']['NotifyTemplateTypeI18n']['param02'];
                @eval("\$text_body = \"$text_body\";");
                $mail_send_queue = array(
                    'id' => '',
                    'sender_name' => $this->configs['shop_name'],
                    'receiver_email' => $user_mail,//接收人姓名;接收人地址
                    'cc_email' => "",
                    'bcc_email' => "",
                    'title' => $subject,
                    'html_body' => $html_body,
                    'text_body' => $text_body,
                    'sendas' => 'html',
                    'flag' => 0,
                    'pri' => 0,
                );
                $mail_result = $this->Notify->send_email($mail_send_queue, $this->configs);
                if($mail_result===true){
                    $result['code']='1';
                    $result['message']=$this->ld['sent_password'];
                }else{
                    $result['message']=is_string($mail_result)?$mail_result:$this->ld['send_failed'];
                }
            }else{
                $result['message']=$this->ld['not_exist_email'];
            }
        }
        die(json_encode($result));
    }

    //重置密码
    public function reset_password()
    {
        if (isset($_SESSION['User']['User'])) {
            unset($_SESSION['User']);
        }
        if ($this->RequestHandler->isPost()) {
            if (isset($_POST['ps']) && !empty($_POST['ps'])) {
                $this->User->updateAll(array('User.password' => "'".md5($_POST['ps'])."'", 'User.mail_pass' => "''"), array('User.id' => $_SESSION['us']));
                $user_data = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['us'])));
                if(!empty($user_data)){
                    $notify_template_info=$this->NotifyTemplateType->typeformat("edit_password","wechat");
                    $notify_template=isset($notify_template_info['wechat'])?$notify_template_info['wechat']:array();
                    $wechat_params=$this->NotifyTemplateType->wechatparamsformat($notify_template);
                    if(!empty($notify_template)){//需要发送通知
                        $synchro_user = $this->SynchroUser->find('first', array('conditions' => array('SynchroUser.status' => 1, 'SynchroUser.type' => 'wechat','SynchroUser.user_id'=>$this->data['User']['id'])));
                        if(!empty($synchro_user['SynchroUser'])){
                            $action_content="亲爱的用户,您的账户密码已修改";
                            $user_name=$user_data['User']['name'];
                            $action_time=date('Y-m-d H:i:s');
                            $action_desc="如非本人操作,请及时联系客服";
                            $wechat_message=array();
                            foreach($wechat_params as $k=>$v){
                                $wechat_message[$k]=array(
                                    'value'=>isset($$v)?$$v:''
                                );
                            }
                            $wechat_post=array(
                                'touser'=>$synchro_user['SynchroUser']['account'],
                                'template_id'=>$notify_template['NotifyTemplateTypeI18n']['param03'],
                                'url'=>$this->server_host,
                                'data'=>$wechat_message
                            );
                            $this->Notify->wechat_message($wechat_post);
                        }
                    }
                }
            }
            $this->pageTitle = '信息提示'.'-'.$this->configs['shop_title'];
            $this->set('url', '/users/login/');
            $this->set('message', '已成功修改，请以新密码重新登入');
            $this->layout = 'flash';
            return;
        }
        if (!isset($_GET['em']) && empty($_GET['em'])) {
            $this->redirect('/');
        }
        $us = $this->User->find('first', array('conditions' => array('User.mail_pass' => $_GET['em']), 'fields' => array('User.id', 'User.mail_pass_expire_time')));
        if ($us['User']['mail_pass_expire_time'] < date('Y-m-d H:i:s')) {
            $this->pageTitle = '信息提示'.'-'.$this->configs['shop_title'];
            $this->set('url', '/users/login/');
            $this->set('message', '重置链接已过期，请重新申请');
            $this->layout = 'flash';
            return;
        }
        if (empty($us)) {
            $this->redirect('/');
        }
        $this->pageTitle = $this->ld['reset_pwd'].'-'.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => $this->ld['reset_pwd']);
        $this->page_init();                        //页面初始化
        $this->layout = 'default_full';
        $_SESSION['us'] = $us['User']['id'];
    }

    //用户确认认证
    public function user_verifyemail(){
        $this->layout = 'default_full';
        $this->page_init();
        $this->pageTitle = $this->ld['certification_success'].' - '.$this->configs['shop_title'];
        $verify_request = isset($_REQUEST['verify_request'])?base64_decode($_REQUEST['verify_request']):'';
        $verify_request_data=explode('|',$verify_request);
        $conditions=array();
        $conditions['User.email']=isset($verify_request_data[0])?$verify_request_data[0]:0;
        $conditions['User.created']=isset($verify_request_data[1])?date('Y-m-d H:i:s',$verify_request_data[1]):strtotime('2008-01-01 00:00:00');;
        $user_info = $this->User->find('first', array('conditions' => $conditions));
        if (!empty($user_info)) {
            $this->User->updateAll(array('User.verify_status'=>'1'),array('User.id'=>$user_info['User']['id'],'User.verify_status <>'=>'1'));
            $this->flash($this->ld['certification_success'], array('controller' => '/'),5000);
        }else{
            $this->redirect('/pages/home');
        }
    }

    /*
    	用户充值
    */
    public function deposit($page = 1, $limit = 10)
    {
        //登录验证
        $this->checkSessionUser();
        $this->layout = 'usercenter';            //引入模版
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            Configure::write('debug', 0);
            $this->layout = 'ajax';
        }
        $this->page_init();                        //页面初始化
        $this->pageTitle = $this->ld['user_deposit'].' - '.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/');
        //当前位置开始
        $this->ur_heres[] = array('name' => $this->ld['user_deposit'], 'url' => '');
        $id = $_SESSION['User']['User']['id'];
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
        if ($user_list['User']['rank'] > 0) {
            $rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
            $user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
        }
        $this->set('user_list', $user_list);
        //分享绑定显示判断
        $app_share = $this->UserApp->app_status();
        $this->set('app_share', $app_share);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($id);
        $this->set('focuscount', $focus);
        //支付方式
        $payment_list=array();
        $online_payment=$this->Payment->find('first',array('conditions'=>array('Payment.code'=>'online_payment','Payment.status'=>'1')));
        if(!empty($online_payment)){
            $payment_list = $this->Payment->find('all', array('conditions' => array('Payment.parent_id' => $online_payment['Payment']['id'], 'Payment.supply_use_flag' => 1, 'Payment.is_online' => 1, 'Payment.status' => '1')));
        }
        $this->set('payment_list', $payment_list);
        //用户资金日志
        $condition['UserBalanceLog.user_id'] = $id;
        $total = $this->UserBalanceLog->find('count', array('conditions' => $condition));//获取总记录数
        $parameters['get'] = array();
        $parameters['route'] = array('controller' => 'Users','action' => 'deposit','page' => $page,'limit' => $limit);
        $options = array('page' => $page,'show' => $limit,'modelClass' => 'UserBalanceLog');
        $this->Pagination->init($condition, $parameters, $options);
        $user_balance_log = $this->UserBalanceLog->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $limit, 'order' => 'UserBalanceLog.created desc'));
        $this->set('user_balance_log', $user_balance_log);
    }

    public function setbalance()
    {
        //登录验证
        $this->checkSessionUser();
        $this->layout = 'default_full';            //引入模版
        $this->pageTitle = $this->ld['user_deposit'].' - '.$this->configs['shop_title'];
        Configure::write('debug',1);
        $this->loadModel('PaymentApiLog');
        if (!empty($_GET['code'])&&!empty($_GET['other_data'])){
            $other_data_str=$_GET['other_data'];
            $other_data_arr=explode("_",$other_data_str);
            $payment_api_id=isset($other_data_arr[2])?$other_data_arr[2]:0;
            $payment_log_info=$this->PaymentApiLog->find('first',array('conditions'=>array('PaymentApiLog.id'=>$payment_api_id)));
            $this->data['pay']['money']=isset($other_data_arr[1])?$other_data_arr[1]:0;
            $this->data['pay']['payment_type']=isset($other_data_arr[0])?$other_data_arr[0]:0;
        }
        if ($this->RequestHandler->isPost()||isset($this->data['pay'])) {
            if(isset($this->data)){
                $this->data=$this->clean_xss($this->data);
            }
            $pay_url = '';
            $message = '操作失败';
            if (isset($this->data['pay']) && !empty($this->data['pay'])){
                $this->data['pay']['payment_type']=intval($this->data['pay']['payment_type']);
                $this->data['pay']['money']=floatval($this->data['pay']['money']);
                $payment = $this->Payment->find('first', array('conditions' => array('Payment.id' => $this->data['pay']['payment_type'], 'Payment.status' => '1')));
                if (isset($payment) && !empty($payment)) {
                    //用户Id
                    $user_id = $_SESSION['User']['User']['id'];
                    //获取用户信息
                    $user_info = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
                    //定义路径
                    $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
                    $this->set('payment_code',$payment['Payment']['code']);
                    $payment_amount=$this->data['pay']['money'];
                    $payment_config = unserialize($payment['Payment']['config']);
                    if($payment['Payment']['code']=='weixinpay'){
                        $amount_money = $payment_amount;
                        //在线支付增加api日志
                        $payment_api_log = array(
                            'id'=>isset($payment_log_info['PaymentApiLog']['id'])?$payment_log_info['PaymentApiLog']['id']:0,
                            'payment_code' => $payment['Payment']['code'],
                            'type' => 2,//充值
                            'type_id' => $user_id,//用户Id
                            'order_currency' => 'CHY',
                            'amount' => $payment_amount//需要支付的金额
                        );
                        $this->PaymentApiLog->save($payment_api_log);
                        $payment_api_log['id'] = $this->PaymentApiLog->id;
                        $amt=$amount_money*100;
                        try {
                            $wechatpay_type=false;
                            if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
                                App::import('Vendor', 'Weixinpay', array('file' => 'WxPayPubHelper.php'));
                                $jsApi = new JsApi_pub($payment_config['APPID'],$payment_config['MCHID'],$payment_config['KEY'],$payment_config['APPSECRET']);
                                if (empty($_GET['code'])){
                                    $request_url="http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
                                    $other_data=$this->data['pay']['payment_type']."_".$this->data['pay']['money']."_".$payment_api_log['id'];
                                    $request_url.="?other_data=".$other_data;
                                    //触发微信返回code码
                                    $wechat_pay_url = $jsApi->createOauthUrlForCode($request_url);
                                    Header("Location: $wechat_pay_url");
                                }else
                                {
                                    //获取code码，以获取openid
                                    $code = $_GET['code'];
                                    $jsApi->setCode($code);
                                    $openid = $jsApi->getOpenId();
                                }
                                if(!empty($openid)){
                                    $unifiedOrder = new UnifiedOrder_pub($payment_config['APPID'],$payment_config['MCHID'],$payment_config['KEY'],$payment_config['APPSECRET']);
                                    $unifiedOrder->setParameter("openid","$openid");//商品描述
                                    $unifiedOrder->setParameter("body","用户充值[金额：".$payment_amount."]");//商品描述
                                    //自定义订单号，此处仅作举例
                                    $out_trade_no = $payment_api_log['id'];
                                    $unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号
                                    $unifiedOrder->setParameter("total_fee",$amt);//总金额
                                    $unifiedOrder->setParameter("notify_url",'http://'.$host.$this->webroot.'responds/weixin_balance');//通知地址
                                    $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
                                    $prepay_id = $unifiedOrder->getPrepayId();
                                    $jsApi->setPrepayId($prepay_id);
                                    $jsApiParameters = $jsApi->getParameters();
                                    if(!empty($jsApiParameters)){
                                        $json_result=json_decode($jsApiParameters);
                                        $code_url = isset($json_result->paySign)?$jsApiParameters:'';
                                    }
                                }else{
                                    throw new SDKRuntimeException("支付失败,OpenId 获取失败");
                                }
                            }else{
                                Configure::write('debug', 0);
                                $this->layout = 'ajax';
                                $wechatpay_type=true;
                                App::import('Vendor', 'Weixinpay', array('file' => 'WxPay.Api.php'));
                                App::import('Vendor', 'Phpqcode', array('file' => 'phpqrcode.php'));
                                $input = new WxPayUnifiedOrder();
                                $input->SetKey($payment_config['KEY']);
                                $input->SetBody("用户充值[金额：".$payment_amount."]");
                                $input->SetAttach("用户充值");
                                $input->SetOut_trade_no($payment_api_log['id']."_".time()."_".rand(0,1000));
                                $input->SetAppid($payment_config['APPID']);
                                $input->SetMch_id($payment_config['MCHID']);
                                $input->SetTotal_fee($amt);
                                $input->SetTime_start(date("YmdHis"));
                                $input->SetTime_expire(date("YmdHis", time() + 600));
                                $input->SetGoods_tag("用户充值");
                                $input->SetNotify_url('http://'.$host.$this->webroot.'responds/weixin_balance');
                                $input->SetProduct_id($payment_api_log['id']);
                                $input->SetTrade_type("NATIVE");
                                $notify = new NativePay();
                                $result = $notify->GetPayUrl($input);
                                $code_url = isset($result["code_url"])?$result["code_url"]:'';
                            }
                            $this->set('wechatpay_type',$wechatpay_type);
                            $message = '';
                        } catch (Exception $e) {
                            $message = '支付失败，Caught exception: '.$e->getMessage();
                        }
                    }else{
                        //判断支付方式是否存在
                        $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
                        try {
                            if($this->RequestHandler->isMobile()&&$payment['Payment']['code']=='alipay'){//手机支付宝
                                //if($payment['Payment']['code']=='alipay'){//手机支付宝
                                $this->PaymentApiLog->deleteAll(array("PaymentApiLog.type_id"=>$user_id,"PaymentApiLog.is_paid"=>'0',"PaymentApiLog.created <="=>date("Y-m-d H:i:s",strtotime("-1 days"))));
                                $payment_api_log = array(
                                    'id'=>isset($payment_log_info['PaymentApiLog']['id'])?$payment_log_info['PaymentApiLog']['id']:0,
                                    'payment_code' => $payment['Payment']['code'],
                                    'type' => 2,//充值
                                    'type_id' => $user_id,//用户Id
                                    'order_currency' => 'CHY',
                                    'amount' => $payment_amount//需要支付的金额
                                );
                                $this->PaymentApiLog->save($payment_api_log);
                                $out_trade_no = $this->PaymentApiLog->id;
                                $alipay_config=array();
                                //合作身份者id，以2088开头的16位纯数字
                                $alipay_config['partner']= isset($payment_config['partner'])?$payment_config['partner']:'';
                                //收款支付宝账号，一般情况下收款账号就是签约账号
                                $alipay_config['seller_id']= isset($payment_config['partner'])?$payment_config['partner']:'';
                                //商户的私钥（后缀是.pen）文件相对路径
                                $alipay_config['private_key_path']	= ROOT.'/vendors/payments/alipaywap/key/rsa_private_key.pem';
                                //支付宝公钥（后缀是.pen）文件相对路径
                                $alipay_config['ali_public_key_path']= ROOT.'/vendors/payments/alipaywap/key/alipay_public_key.pem';
                                //签名方式 不需修改
                                $alipay_config['sign_type']    = strtoupper('RSA');
                                //字符编码格式 目前支持 gbk 或 utf-8
                                $alipay_config['input_charset']= strtolower('utf-8');
                                //ca证书路径地址，用于curl中ssl校验
                                //请保证cacert.pem文件在当前文件夹目录中
                                $alipay_config['cacert']    = ROOT.'/vendors/payments/alipaywap/cacert.pem';
                                //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
                                $alipay_config['transport']    = 'http';
                                $alipay_parameter=array(
                                    "service" => "alipay.wap.create.direct.pay.by.user",
                                    "partner" => trim($alipay_config['partner']),
                                    "seller_id" => trim($alipay_config['seller_id']),
                                    "payment_type"	=> '1',
                                    "notify_url"	=> 'http://'.$host.$this->webroot.'responds/return_code/'.$payment['Payment']['code'].'/1/wap',
                                    "return_url"	=>  'http://'.$host.$this->webroot.'responds/return_code/'.$payment['Payment']['code'].'/0/wap',
                                    "out_trade_no"	=> $out_trade_no,
                                    "subject"	=> "用户充值[金额：".$payment_amount."]",
                                    "total_fee"	=> $payment_amount,
                                    "show_url"	=> '',
                                    "body"	=> '',
                                    "it_b_pay"	=> '',
                                    "extern_token"	=> '',
                                    "_input_charset"	=> trim(strtolower($alipay_config['input_charset']))
                                );
                                $alipaySubmit_classfile=ROOT."/vendors/payments/alipaywap/alipay_submit.class.php";
                                include_once($alipaySubmit_classfile);
                                $is_wechat=false;
                                if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false&&		$payment['Payment']['code']=='alipay') {
                                    $is_wechat    = true;
                                }
                                $alipaySubmit = new AlipaySubmit($alipay_config);
                                $api_code = $alipaySubmit->buildRequestForm($alipay_parameter,"get", "支付",$is_wechat);
                                if($is_wechat){
                                    $pay_form_txt=$api_code;
                                }
                            }else if($payment['Payment']['code']=='alipay'){//支付宝
                                $this->PaymentApiLog->deleteAll(array("PaymentApiLog.type_id"=>$user_id,"PaymentApiLog.is_paid"=>'0',"PaymentApiLog.created <="=>date("Y-m-d H:i:s",strtotime("-1 days"))));
                                $payment_api_log = array(
                                    'payment_code' => $payment['Payment']['code'],
                                    'type' => 2,
                                    'type_id' => $user_id,
                                    'order_currency' => 'CHY',
                                    'amount' => $payment_amount
                                );
                                $this->PaymentApiLog->save($payment_api_log);
                                $out_trade_no=$this->PaymentApiLog->id;
                                $alipay_config=$payment_config;
                                $alipay_config['seller_email']=isset($alipay_config['account'])?$alipay_config['account']:'';
                                $alipay_config['sign_type']    = strtoupper('MD5');
                                $alipay_config['input_charset']= strtolower('utf-8');
                                $alipay_config['cacert']    = getcwd().'/app/vendors/payments/alipay/cacert.pem';
                                $alipay_config['transport']    = 'http';
                                $alipay_parameter=array(
                                    "service" => "create_direct_pay_by_user",
                                    "partner" => trim($alipay_config['partner']),
                                    "seller_email" => trim($alipay_config['seller_email']),
                                    "payment_type"	=> '1',//支付类型
                                    "notify_url"	=> 'http://'.$host.$this->webroot.'responds/return_code/'.$payment['Payment']['code'].'/1/pc',
                                    "return_url"	=> 'http://'.$host.$this->webroot.'responds/return_code/'.$payment['Payment']['code'].'/0/pc',
                                    "out_trade_no"	=> $out_trade_no,
                                    "subject"	=> "用户充值[金额：".$payment_amount."]",
                                    "total_fee"	=> $payment_amount,
                                    "body"	=> '',
                                    "show_url"	=> '',
                                    "anti_phishing_key"	=> '',
                                    "exter_invoke_ip"	=> '',
                                    "_input_charset"	=> trim(strtolower('utf-8'))
                                );
                                $alipay_class_file=ROOT."/vendors/payments/alipay/alipay_submit.class.php";
                                include_once($alipay_class_file);
                                $balance_payment = new AlipaySubmit($alipay_config);
                                $is_wechat=false;
                                if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false&&$payment['Payment']['code']=='alipay') {
                                    $is_wechat    = true;
                                }
                                $api_code = $balance_payment->buildRequestForm($alipay_parameter,"get", "支付",$is_wechat);
                            }else{
                                $payment_config = unserialize($payment['Payment']['config']);
                                App::import('Vendor', 'payments/'.$payment['Payment']['code']);
                                $balance_payment = new $payment['Payment']['code']();
                                if ($payment['Payment']['is_online'] == 1) {
                                    //在线支付增加api日志
                                    $payment_api_log = array(
                                        'payment_code' => $payment['Payment']['code'],
                                        'type' => 2,//充值
                                        'type_id' => $user_id,//用户Id
                                        'order_currency' => 'CHY',
                                        'amount' => $this->data['pay']['money'],//需要支付的金额
                                    );
                                    $this->PaymentApiLog->save($payment_api_log);
                                    //记录支付日志Id
                                    $payment_api_log['id'] = $this->PaymentApiLog->id;
                                    $payment_api_log['name'] = $user_info['User']['name'];
                                    $payment_api_log['payerAdderss'] = $user_info['User']['address_id'];
                                    $payment_api_log['payerName'] = $user_info['User']['name'];
                                    $payment_api_log['created'] = date('Y-m-d H:i:s', time());
                                    $payment_config['cancel_return'] = 'http://'.$host.$this->webroot;
                                    $payment_config['return_url'] = 'http://'.$host.$this->webroot.'responds/return_code/'.$payment['Payment']['code'];
                                    //描述
                                    $payment_api_log['subject'] = '['.$user_info['User']['name'].']用户充值';
                                    $payment_api_log['host'] = $host;
                                    if ($payment['Payment']['code'] == 'money' || $payment['Payment']['code'] == 'bank_trans'  || $payment['Payment']['code'] == 'pos_pay') {
                                        $payment_config['co'] = '';
                                    }
                                    $api_code = $balance_payment->go2($payment_api_log, $payment_config);
                                    $_SESSION['api_code'] = $api_code;
                                    $message = '';
                                }else{
                                    $this->layout = 'usercenter';
                                    $message=$payment['PaymentI18n']['description'];
                                }
                            }
                        } catch (Exception $e) {
                            $message = '支付失败，Caught exception: '.$e->getMessage();
                        }
                    }
                } else {
                    $message = '该支付方式无效或不可用!';
                }
            }
            if (isset($api_code)&&!isset($pay_form_txt)) {
                echo "<style type='text/css'>body{display:none;}</style>";
                $this->layout=null;
                $result['pay_url'] = isset($api_code) ? $api_code : $pay_url;
                $this->set('pay_url', $api_code);
            }else if(isset($code_url)&&$code_url!=""){
                $this->set('pay_url', $code_url);
                $this->set('payment_api_id',$payment_api_log['id']);
            }else if(isset($pay_form_txt)){
                $this->set('pay_form_txt', $pay_form_txt);
            }else {
                //跳转到提示页
                $this->flash($message, '/users/deposit', '');
            }
        } else {
            $this->redirect('/users/deposit');
        }
    }

    public function checkwechatpay(){
        //登录验证
        $this->checkSessionUser();
        Configure::write('debug', 0);
        $result['code']=0;
        if ($this->RequestHandler->isPost()) {
            $user_id = $_SESSION['User']['User']['id'];
            $this->loadModel('PaymentApiLog');
            $payment_api_id=isset($_POST['payment_api_id'])?$_POST['payment_api_id']:0;
            $conditions['PaymentApiLog.payment_code']="weixinpay";
            $conditions['PaymentApiLog.type']=2;
            $conditions['PaymentApiLog.type_id']=$user_id;
            $conditions['PaymentApiLog.id']=$payment_api_id;
            $payment_api_log =$this->PaymentApiLog->find('first',array('conditions'=>$conditions));
            if(isset($payment_api_log)&&$payment_api_log['PaymentApiLog']['is_paid']=='1'){
                $result=1;
            }
        }
        die(json_encode($result));
    }

    public function enquiries($page = 1)
    {
        $_GET=$this->clean_xss($_GET);
        //登录验证
        $this->checkSessionUser();
        $this->layout = 'usercenter';            //引入模版
        $this->page_init();                        //页面初始化
        $this->pageTitle = $this->ld['enquiry'].' - '.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/');
        $this->ur_heres[] = array('name' => $this->ld['enquiry'], 'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $user_id = $_SESSION['User']['User']['id'];
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        if ($user_list['User']['rank'] > 0) {
            $rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
            $user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
        }
        $this->set('user_list', $user_list);
        //分享绑定显示判断
        $app_share = $this->UserApp->app_status();
        $this->set('app_share', $app_share);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($user_id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($user_id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($user_id);
        $this->set('focuscount', $focus);
        $enquiry_status = '';
        if (isset($_GET['enquiry_status']) && $_GET['enquiry_status'] != '') {
            $enquiry_status = $_GET['enquiry_status'];
            if ($enquiry_status != '-1') {
                $condition['Enquiry.status'] = $enquiry_status;
            } else {
                $condition['Enquiry.status'] = array('0','1');
            }
        }
        $score_status = '0';
        if (isset($_GET['score_status']) && $_GET['score_status'] != '') {
            $score_status = $_GET['score_status'];
        }
        $this->set('enquiry_status', $enquiry_status);
        $condition['Enquiry.user_id'] = $user_id;
        $total = $this->Enquiry->find('count', array('conditions' => $condition));
        $limit = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array('enquiry_status='.$enquiry_status.'&score_status='.$score_status);
        $parameters['route'] = array('controller' => 'users', 'action' => 'enquiries', 'page' => $page, 'limit' => $limit);
        //分页参数
        $options = array('page' => $page, 'show' => $limit,'total' => $total, 'modelClass' => 'Enquiry');
        $page = $this->Pagination->init($condition, $parameters, $options); //Added
        //分页end
        $enquiries_list = $this->Enquiry->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $limit, 'order' => 'Enquiry.created desc'));
        if (!empty($enquiries_list) && sizeof($enquiries_list) > 0) {
            $product_code = array();
            foreach ($enquiries_list as $k => $v) {
                $pc_arr = explode(';', $v['Enquiry']['part_num']);
                foreach ($pc_arr as $pk => $pv) {
                    $product_code[] = $pv;
                }
            }
            $product_code_arr = $this->Product->find('all', array('fields' => array('Product.id', 'Product.code', 'ProductI18n.name'), 'conditions' => array('Product.code' => $product_code, 'ProductI18n.locale' => $this->locale, 'Product.status' => '1', 'Product.forsale' => '1')));
            $product_code_list = array();
            $product_id_list = array();
            $product_attribute=array();
            foreach ($product_code_arr as $k => $v) {
                $product_code_list[$v['Product']['code']] = $v['ProductI18n']['name'];
                $product_id_list[$v['Product']['code']] = $v['Product']['id'];
                if(isset($v['ProductAttribute'])&&!empty($v['ProductAttribute'])){
                    foreach($v['ProductAttribute'] as $vv){
                        $product_attribute[$v['Product']['code']][$vv['attribute_id']]=$vv['attribute_value'];
                    }
                }
            }
            $this->set('product_code_list', $product_code_list);
            $this->set('product_id_list', $product_id_list);
            $this->set('product_attribute', $product_attribute);
            $public_attr_ids = $this->ProductTypeAttribute->getattrids(0);
            $pubilc_attr_info = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $public_attr_ids, 'Attribute.status' => 1), 'fields' => 'Attribute.id,AttributeI18n.name'));
            $this->set('pubilc_attr_info',$pubilc_attr_info);
            if ($score_status != '0') {
                $_scorelog_list = $this->ScoreLog->find('all', array('fields' => array('count(*) as countnum', 'ScoreLog.type_id'), 'conditions' => array('ScoreLog.type' => 'P', 'ScoreLog.type_id' => $product_id_list, 'ScoreLog.user_id' => $user_id), 'group' => 'ScoreLog.type_id'));
                $scorelog_list = array();
                foreach ($_scorelog_list as $k => $v) {
                    $scorelog_list[$v['ScoreLog']['type_id']] = $v[0]['countnum'];
                }
                $this->set('scorelog_list', $scorelog_list);
            }
        }
        $this->set('enquiries_list', $enquiries_list);
    }

    /*
        ajax动态获取用户信息，并更新$_session
    */
    public function ajax_getUserInfo()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $userInfo = $this->User->changeUserSession();//输出用户信息
        if (empty($userInfo)) {
            $result['code'] = '0';
            $result['msg'] = 'user_not_exist';
            die(json_encode($result));
        } else {
            die(json_encode($userInfo));
        }
    }

    public function real_ip()
    {
        static $realip = null;
        if ($realip !== null) {
            return $realip;
        }
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr as $ip) {
                    $ip = trim($ip);
                    if ($ip != 'unknown') {
                        $realip = $ip;
                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $realip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $realip = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }
        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
        return $realip;
    }

    /**
     *产生激活码.
     */
    public function get_activation_code()
    {
        mt_srand((double) microtime() * 1000000);
        $code = md5(date('H:i:s').mt_rand(1, 9999));
        return $code;
    }

    /**
     *推荐商品.
     */
    public function recgoods()
    {
        $this->layout = 'usercenter';
    }

    /**
     *我的标签.
     */
    public function labels()
    {
        $this->layout = 'usercenter';
    }

    /**
     *我的好友.
     */
    public function friends()
    {
        $this->layout = 'usercenter';
    }

    /**
     *我的推荐.
     */
    public function recomment()
    {
        $this->layout = 'usercenter';
    }

    /**
     *我的优惠券.
     */
    public function coupons()
    {
        $this->layout = 'usercenter';
    }

    /**
     *已购买.
     */
    public function purchased()
    {
        $this->layout = 'usercenter';
    }

    //验证码
    public function captcha()
    {
        if ($this->RequestHandler->isPost()) {
            $securimage_code_value = isset($_SESSION['securimage_code_value']) ? $_SESSION['securimage_code_value'] : '';
            $this->layout = 'ajax';
            Configure::write('debug', 0);
            die(json_encode($securimage_code_value));
        } else {
            $this->layout = 'blank'; //a blank layout
            $this->captcha->show(); //dynamically creates an image
            exit();
        }
    }

    //用户确认认证
    public function check_input()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if ($this->RequestHandler->isPost()) {
            if (!empty($_POST['account'])) {
                $result['type'] = 'account';
                $result['type_id'] = $_POST['type_id'];
                if ($this->User->find('first', array('conditions' => array('User.name' => $_POST['account'])))) {
                    $result['error'] = 1;
                    $result['msg'] = $this->ld['nickname_exists'];
                } else {
                    $result['error'] = 0;
                    $result['msg'] = $this->ld['nickname_be_used'];
                }
                die(json_encode($result));
            }
            if (!empty($_POST['sn_email'])) {
                $condition = array();
                $condition['or']['User.user_sn'] = $_POST['sn_email'];
                $condition['or']['User.email'] = $_POST['sn_email'];
                $result['type'] = 'sn_email';
                $result['type_id'] = $_POST['type_id'];
                if ($this->User->find('first', array('conditions' => $condition))) {
                    $result['error'] = 1;
                    $result['msg'] = $this->ld['email_has_been_registered'];
                } else {
                    $result['error'] = 0;
                    $result['msg'] = $this->ld['email_can_be_used'];
                }
                die(json_encode($result));
            }
            if (!empty($_POST['email'])) {
                $result['type'] = 'email';
                $result['type_id'] = $_POST['type_id'];
                if ($this->User->find('first', array('conditions' => array('User.email' => $_POST['email'])))) {
                    $result['error'] = 1;
                    $result['msg'] = $this->ld['email_exists'];
                } else {
                    $result['error'] = 0;
                    $result['msg'] = $this->ld['email_can_be_used'];
                }
                die(json_encode($result));
            }
            if (!empty($_POST['mobile'])) {
                $result['type'] = 'mobile';
                $result['type_id'] = $_POST['type_id'];
                if ($this->User->find('first', array('conditions' => array('User.mobile' => $_POST['mobile'])))) {
                    $result['error'] = 1;
                    $result['msg'] = $this->ld['mobile_exists'];
                } else {
                    $result['error'] = 0;
                    $result['msg'] = $this->ld['rebate_088'];
                }
                die(json_encode($result));
            }
        }
        die();
    }

    public function bind(){
        //登录验证
        $this->checkSessionUser();
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => $this->ld['user_001'],'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $this->pageTitle = $this->ld['user_001'].' - '.$this->configs['shop_title'];
        $this->layout = 'usercenter';
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            Configure::write('debug', 0);
            $this->layout = 'ajax';
        }
        $this->page_init();
        $this->set('sev', $this->server_host);
        $user_id = $_SESSION['User']['User']['id'];
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        $_SESSION['User'] = $user_list;
        if ($user_list['User']['address_id'] != '0') {
            //获取我的地址
            $user_list['User']['UserAddress'] = $this->UserAddress->find('first', array('conditions' => array('id' => $user_list['User']['address_id']), 'fields' => 'id,zipcode,address_type,address,mobile,regions,sign_building'));
        }
        $this->set('user_list', $user_list);
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 1);
            $this->layout = 'ajax';
            $result=array();
            $result['code']='1';
            $result['message']=$this->ld['set_successfully'];
            $user_app_id=isset($_POST['user_app_id'])?$_POST['user_app_id']:0;
            $this->SynchroUser->deleteAll(array('id'=>$user_app_id,'user_id'=>$user_id));
            die(json_encode($result));
        }
        $user_app_list=$this->UserApp->find('all',array('conditions'=>array('status'=>'1'),'order'=>'type'));
        $this->set('user_app_list', $user_app_list);
        $synchro_user_infos = $this->SynchroUser->find('all', array('conditions' => array('SynchroUser.user_id' => $user_id)));
        $synchro_user=array();
        foreach($synchro_user_infos as $v)$synchro_user[strtolower($v['SynchroUser']['type'])]=$v['SynchroUser'];
        $this->set('synchro_user', $synchro_user);
    }

    //客户退出
    public function logout()
    {
        unset($_SESSION['User']);
        if (isset($_SESSION['svcart'])) {
            unset($_SESSION['svcart']);
        }
        if (isset($_SESSION['payment_tp']) && !empty($_SESSION['payment_tp'])) {
            unset($_SESSION['payment_tp']);
        }
        if (isset($_SESSION['payment_tk']) && !empty($_SESSION['payment_tk'])) {
            unset($_SESSION['payment_tk']);
        }
        setcookie('user_info', '', time() - 60 * 60 * 24 * 14, '/');
        $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users';
        $lan = array('/en/','/cn/','/jp/');
        $back_url = str_replace($lan, '/', $back_url);
        if (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false && strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false) {
            $back_url = "/";
        }
        $this->redirect($back_url);
    }

    //发邮件
    public function __sendMail($arg = array())
    {
        $user_name = isset($arg['sender']) ? $arg['sender'] : '';
        eval("\$user_name = \"$user_name\";");
        if (isset($arg['reset_email'])) {
            $reset_email = $arg['reset_email'];
            eval("\$reset_email = \"$reset_email\";");
        }
        if (isset($arg['shop_name'])) {
            $shop_name = $arg['shop_name'];
            eval("\$shop_name = \"$shop_name\";");
        }
        if (isset($arg['shop_url'])) {
            $shop_url = $arg['shop_url'];
            eval("\$shop_url = \"$shop_url\";");
        }
        $send_date = date('Y-m-d H:i:s');
        eval("\$send_date = \"$send_date\";");
        if(isset($arg['template']['NotifyTemplateTypeI18n'])){
            $subject = $arg['template']['NotifyTemplateTypeI18n']['title'];
            eval("\$subject = \"$subject\";");
            $html_body = addslashes($arg['template']['NotifyTemplateTypeI18n']['param01']);
            eval("\$html_body = \"$html_body\";");
            $text_body = $arg['template']['NotifyTemplateTypeI18n']['param02'];
            eval("\$text_body = \"$text_body\";");
        }else{
            return false;
        }
        $mail_send_queue = array(
            'id' => '',
            'sender_name' => $this->configs['shop_name'],
            'receiver_email' => $arg['receiver'],//接收人姓名;接收人地址
            'cc_email' => "",
            'bcc_email' => "",
            'title' => $subject,
            'html_body' => $html_body,
            'text_body' => $text_body,
            'sendas' => 'html',
            'flag' => 0,
            'pri' => 0,
        );
        $this->Notify->send_email($mail_send_queue, $this->configs);
    }

    //随即产生密码
    public function genPasswd($num)
    {
        $passwd = '';
        $chars = array(
            'digits' => array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9),
            'lower' => array(
                'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
                'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            ),
            'upper' => array(
                'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
                'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            ),
        );
        $charTypes = array_keys($chars);
        $numTypes = count($charTypes) - 1;
        for ($i = 0; $i < $num; ++$i) {
            $charType = $charTypes[ mt_rand(0, $numTypes) ];
            $passwd .= $chars[$charType][
            mt_rand(0, count($chars[$charType]) - 1)
            ];
        }
        return $passwd;
    }

    public function synchro_user()
    {
        $this->pageTitle = $this->ld['member_login'].' - '.$this->configs['shop_title'];
        $this->ur_heres[] = array('name' => $this->ld['member_login'], 'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $this->set('sev', $this->server_host);
    }

    public function syn_reg()
    {
        if ($this->RequestHandler->isPost()) {
            $user = array();
            $user['User']['user_sn'] = $_SESSION['syn_pkg']['email'];
            $user['User']['name'] = isset($_POST['user_name']) ? $_POST['user_name'] : $_SESSION['syn_pkg']['email'];
            $user['User']['first_name'] = $_SESSION['syn_pkg']['email'];
            $user['User']['img01'] = isset($_SESSION['syn_pkg']['img01']) ? $_SESSION['syn_pkg']['img01'] : '';
            $user['User']['img02'] = isset($_SESSION['syn_pkg']['img02']) ? $_SESSION['syn_pkg']['img02'] : '';
            $user['User']['img03'] = isset($_SESSION['syn_pkg']['img03']) ? $_SESSION['syn_pkg']['img03'] : '';
            $user['User']['password'] = md5($_SESSION['syn_pkg']['email']);
            $user['User']['email'] = isset($_POST['user_email']) ? $_POST['user_email'] : '';
            $user['User']['mobile'] = isset($_POST['user_phone']) ? $_POST['user_phone'] : '';
            $user['User']['unvalidate_note'] = $_SESSION['syn_pkg']['type'];
            $this->User->save($user);
            $uid = $this->User->id;
            $info = array();
            $info['SynchroUser']['user_id'] = $uid;
            $info['SynchroUser']['email'] = $_SESSION['syn_pkg']['email'];
            $info['SynchroUser']['account'] = $_SESSION['syn_pkg']['account'];
            $info['SynchroUser']['type'] = $_SESSION['syn_pkg']['type'];
            $info['SynchroUser']['oauth_token'] = $_SESSION['syn_pkg']['oauth_token'];
            $info['SynchroUser']['oauth_token_secret'] = $_SESSION['syn_pkg']['oauth_token_secret'];
            ClassRegistry::init('SynchroUser')->save($info);
            $_SESSION['User'] = $this->User->find('first', array('conditions' => array('User.id' => $uid)));
            $this->get_back();
        }
        die();
    }

    public function check_syn_mail()
    {
        Configure::write('debug', 0);
        if ($this->RequestHandler->isPost()) {
            if (!empty($_POST['user_name'])) {
                if ($this->User->find('first', array('conditions' => array('User.name' => $_POST['user_name'])))) {
                    $result['error'] = 1;
                    $result['msg'] = $this->ld['nickname_exists'];
                    die(json_encode($result));
                } else {
                    $result['error'] = 0;
                    $result['msg'] = $this->ld['email_can_be_used'];
                }
            }
            if (!empty($_POST['user_email'])) {
                if ($this->User->find('first', array('conditions' => array('User.email' => $_POST['user_email'])))) {
                    $result['error'] = 1;
                    $result['msg'] = $this->ld['email_has_been_registered'];
                    die(json_encode($result));
                } else {
                    $result['error'] = 0;
                    $result['msg'] = $this->ld['email_can_be_used'];
                }
            }
            die(json_encode($result));
        }
    }

    public function syn_reg2()
    {
        if ($this->RequestHandler->isPost()) {
            $info = array();
            $info['SynchroUser']['user_id'] = $_SESSION['User']['User']['id'];
            $info['SynchroUser']['email'] = $_SESSION['syn_pkg']['email'];
            $info['SynchroUser']['account'] = $_SESSION['syn_pkg']['account'];
            $info['SynchroUser']['type'] = $_SESSION['syn_pkg']['type'];
            $info['SynchroUser']['oauth_token'] = $_SESSION['syn_pkg']['oauth_token'];
            $info['SynchroUser']['oauth_token_secret'] = $_SESSION['syn_pkg']['oauth_token_secret'];
            ClassRegistry::init('SynchroUser')->save($info);
            $user = $_SESSION['User'];
            $user['User']['img01'] = isset($_SESSION['syn_pkg']['img01']) ? $_SESSION['syn_pkg']['img01'] : '';
            $user['User']['img02'] = isset($_SESSION['syn_pkg']['img02']) ? $_SESSION['syn_pkg']['img02'] : '';
            $user['User']['img03'] = isset($_SESSION['syn_pkg']['img03']) ? $_SESSION['syn_pkg']['img03'] : '';
            if (empty($user['User']['unvalidate_note'])) {
                $user['User']['unvalidate_note'] = $_SESSION['syn_pkg']['type'];
            } else {
                $user['User']['unvalidate_note'] .= ','.$_SESSION['syn_pkg']['type'];
            }
            ClassRegistry::init('User')->save($user);
            $_SESSION['User'] = $this->User->find('first', array('conditions' => array('User.id' => $user['User']['id'])));
            $this->get_back();
        }
        die();
    }

    public function syn_check()
    {
        $result['type'] = 2;
        $result['message'] = $this->ld['invalid_operation'];
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        $user_id = $_POST['id'];
        $ps = $_POST['ps'];
        $ps = md5($ps);
        $x = $this->User->find('first', array('conditions' => array('User.email' => $user_id, 'User.password' => $ps)));
        if (!empty($x)) {
            $result['type'] = 0;
            $_SESSION['User'] = $x;
        }
        die(json_encode($result));
    }

    public function get_back()
    {
        if (isset($_SESSION['login_back'])) {
            if ($_SESSION['login_back'] == '/en/' || $_SESSION['login_back'] == '/cn/' || $_SESSION['login_back'] == '/jp/') {
                $_SESSION['login_back'] = '/';
            }
        } else {
            $_SESSION['login_back'] = '/';
        }
        if ($_SESSION['login_back'] == '/flashes/index/H') {
            unset($_SESSION['login_back']);
        }
        $back_url = isset($_SESSION['login_back']) ? $_SESSION['login_back'] : '/users';
        $this->redirect($back_url);
    }

    public function test()
    {
        unset($_SESSION);
        die();
    }

    //创建路径
    public function mkdirs($path, $mode = 0777)
    {
        $dirs = explode('/', $path);
        $pos = strrpos($path, '.');
        if ($pos === false) {
            $subamount = 0;
        } else {
            $subamount = 1;
        }
        for ($c = 0;$c < count($dirs) - $subamount; ++$c) {
            $thispath = '';
            for ($cc = 0; $cc <= $c; ++$cc) {
                $thispath .= $dirs[$cc].'/';
            }
            if (!file_exists($thispath)) {
                mkdir($thispath, $mode);
                chmod($thispath, $mode);
            } else {
                @chmod($thispath, $mode);
            }
        }
    }

    public function other_login()
    {
        $syns = $this->UserApp->find('list', array('conditions' => array('UserApp.status' => 1, 'UserApp.type !=' => 'Wechat','UserApp.location'=>array(0,2)), 'fields' => array('UserApp.type')));
        $this->layout = 'ajax';
        Configure::write('debug', 0);
        if (!empty($syns)) {
            $result['type'] = 0;
            $result['syns'] = $syns;
        } else {
            $result['type'] = 1;
            $result['syns'] = '';
        }
        die(json_encode($result));
    }

    function ajax_upload_files(){
        Configure::write('debug',0);
        $this->layout = 'ajax';
        $result=array();
        $result['code'] = 0;
        $result['msg'] = 'not file';
        if($this->RequestHandler->isPost()){
            $file_root = 'media/users/files/';
            $fileaddr = WWW_ROOT.'media/users/files/';
            $this->mkdirs($fileaddr);
            $fileCode=isset($_POST['fileCode'])?$_POST['fileCode']:'';
            if(!empty($fileCode)&&!empty($_FILES[$fileCode])){
                $userfile_name = $_FILES[$fileCode]['name'];
                $userfile_tmp = $_FILES[$fileCode]['tmp_name'];
                $userfile_size = $_FILES[$fileCode]['size'];
                $userfile_type = $_FILES[$fileCode]['type'];
                $filename = basename($_FILES[$fileCode]['name']);
                $file_ext = strtolower(substr($filename, strrpos($filename, '.') + 1));
                $file_location = $fileaddr.md5(date('Y-m-d h:i:s').$userfile_name).'.'.$file_ext;
                $file_name = '/'.$file_root.md5(date('Y-m-d h:i:s').$userfile_name).'.'.$file_ext;
                if (move_uploaded_file($userfile_tmp, $file_location)) {
                    $result['code'] = 1;
                    $result['file_name'] = $file_name;
                    $result['file_location'] = $file_location;
                    $result['file_type'] = mime_content_type($file_location);
                    $result['msg'] = '';
                }else{
                    $result['msg'] = 'File not found';
                }
            }
        }
        die(json_encode($result));
    }

    function ajax_remove_files(){
        Configure::write('debug',0);
        $this->layout = 'ajax';
        $result=array();
        $result['code'] = 0;
        $result['msg'] = 'not file';
        if($this->RequestHandler->isPost()){
            $fileaddr = WWW_ROOT;
            $file_root = isset($_POST['FileUrl'])?$_POST['FileUrl']:'';
            if(!empty($file_root)){
                $fileaddr.=$file_root;
            }
            if(is_file($fileaddr)){
                @unlink($fileaddr);
                $result['code'] = 1;
                $result['msg'] = '';
            }
        }
        die(json_encode($result));
    }

    function ajax_login(){
        Configure::write('debug',1);
        $this->layout = null;
        $syns = $this->UserApp->find('list', array('conditions' => array('UserApp.status' => 1, 'UserApp.type !=' => 'Wechat','UserApp.location'=>array(0,2)), 'fields' => array('UserApp.type')));
        $this->set('syns', $syns);
    }

    /*
    		商品访问浏览历史
    */
    function product_view_history(){
        //登录验证
        $this->checkSessionUser();
        /*
        判断是否为手机版
        */
        if ($this->is_mobile) {
            $this->layout = 'mobile/default_full';
            $this->render('mobile/index');
            Configure::write('debug', 1);
        }else{
            $this->layout = 'usercenter';            //引入模版
        }
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            Configure::write('debug', 0);
            $this->layout = 'ajax';
        }
        $user_id=$_SESSION['User']['User']['id'];
        $pro_view_log = 0;
        if (isset($_COOKIE['pro_view_log']) && !empty($_COOKIE['pro_view_log'])) {
            $pro_view_log = explode(';', $_COOKIE['pro_view_log']);
        }
        $conditions=array();
        $conditions['Product.id'] = $pro_view_log;
        $conditions['Product.status'] = 1;
        $conditions['Product.forsale'] = 1;
        $conditions['Product.alone'] = 1;
        $fields=array('Product.id','Product.brand_id', 'Product.recommand_flag', 'Product.status','Product.img_big',
            'Product.img_thumb','Product.img_detail','Product.img_original','Product.product_image1','Product.product_image2'
        , 'Product.market_price'
        , 'Product.shop_price'
        , 'Product.category_id'
        , 'Product.promotion_price'
        , 'Product.promotion_start'
        , 'Product.promotion_end'
        , 'Product.promotion_status'
        , 'Product.code','Product.brand_id'
        , 'Product.product_rank_id'
        , 'Product.quantity', 'Product.freeshopping', 'ProductI18n.name', 'ProductI18n.description','ProductI18n.description02','Product.unit' );
        $product_info=$this->Product->find('all',array('conditions'=>$conditions,'fields'=>$fields,'order'=>'Product.modified'));
        if(!empty($product_info)){
            $attribute_info=$this->Attribute->find('all',array('fields'=>array("Attribute.id","AttributeI18n.name"),'conditions'=>array("Attribute.status"=>'1')));
            $attribute_data=array();
            foreach($attribute_info as $v){
                $attribute_data[$v['Attribute']['id']]=$v['AttributeI18n']['name'];
            }
            $this->set('attribute_data',$attribute_data);
            $product_ids=array();
            $brand_ids=array();
            foreach($product_info as $v){
                $product_ids[]=$v['Product']['id'];
                $brand_ids[]=$v['Product']['brand_id'];
            }
            $UserLike_data=$this->UserLike->find('list',array('fields'=>'type_id,id','conditions'=>array('UserLike.user_id'=>$user_id,'UserLike.action'=>'like','UserLike.type'=>'P','UserLike.type_id'=>$product_ids)));
            $UserFavorite_data=$this->UserFavorite->find('list',array('fields'=>'type_id,id','conditions'=>array('UserFavorite.user_id'=>$user_id,'UserFavorite.status'=>'1','UserFavorite.type'=>'P','UserFavorite.type_id'=>$product_ids)));
            $brand_info=$this->Brand->find('all',array('fields'=>array('Brand.id','BrandI18n.name'),'conditions'=>array("Brand.status"=>'1','Brand.id'=>$brand_ids)));
            $brand_data=array();
            foreach($brand_info as $v){
                $brand_data[$v['Brand']['id']]=$v['BrandI18n']['name'];
            }
            foreach ($product_info as $k => $v) {
                $product_info[$k]['UserLike']=isset($UserLike_data[$v['Product']['id']])?'1':'0';
                $product_info[$k]['UserFavorite']=isset($UserFavorite_data[$v['Product']['id']])?'1':'0';
                $product_info[$k]['Brand']=isset($brand_data[$v['Product']['brand_id']])?$brand_data[$v['Product']['brand_id']]:'';
            }
        }
        $this->set('product_info',$product_info);
    }

    function ajax_modify_user_sn(){
        Configure::write('debug',1);
        $this->layout = 'ajax';
        $result=array();
        $result['code'] = 0;
        $result['msg'] = $this->ld['invalid_operation'];
        if($this->RequestHandler->isPost()){
            $user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
            $user_info=$this->User->findById($user_id);
            if(!empty($user_info)){
                $old_user_sn=trim($user_info['User']['user_sn']);
                $user_sn=isset($_POST['user_sn'])?trim($_POST['user_sn']):'';
                if($user_sn==''){
                    $result['msg'] = $this->ld['user_id'].$this->ld['can_not_empty'];
                }else if($old_user_sn==$user_sn){
                    $result['msg'] = $this->ld['user_id'].$this->ld['rebate_070'];
                }else{
                    $user_sn_total=$this->User->find('count',array('conditions'=>array('user_sn'=>$user_sn)));
                    if($user_sn_total>0){
                        $result['msg'] = $this->ld['user_id'].$this->ld['rebate_070'];
                    }else{
                        $user_data=array(
                            'id'=>$user_id,
                            'user_sn'=>$user_sn
                        );
                        $this->User->save($user_data);
                        $result['code'] = 1;
                        $result['msg'] = $this->ld['tips_edit_success'];
                    }
                }
            }
        }
        die(json_encode($result));
    }

    function ajax_mobile_bind(){
        Configure::write('debug',1);
        $this->layout = 'ajax';
        $result=array();
        $result['code'] = 0;
        $result['msg'] = $this->ld['invalid_operation'];
        if($this->RequestHandler->isPost()){
            $user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
            $user_info=$this->User->findById($user_id);
            if(!empty($user_info)){
                $old_user_mobile=trim($user_info['User']['mobile']);
                if($old_user_mobile!=''){
                    $old_mobile_code=isset($_POST['old_mobile_code'])?$_POST['old_mobile_code']:'';
                    $phone_code_key="phone_code_number{$old_user_mobile}";
                    $phone_code_number=isset($_COOKIE[$phone_code_key])?$_COOKIE[$phone_code_key]:'';
                    if($old_mobile_code==''||$old_mobile_code!=$phone_code_number){
                        $result['msg'] = $old_user_mobile.$this->ld['incorrect_verification_code'];
                        die(json_encode($result));
                    }
                }
                $bind_mobile=isset($_POST['bind_mobile'])?$_POST['bind_mobile']:'';
                $bind_mobile_user=$this->User->find('first',array('conditions'=>array('mobile'=>$bind_mobile)));
                if(empty($bind_mobile_user)){
                    $mobile_code=isset($_POST['bind_mobile_code'])?$_POST['bind_mobile_code']:'';
                    $phone_code_key="phone_code_number{$bind_mobile}";
                    $phone_code_number=isset($_COOKIE[$phone_code_key])?$_COOKIE[$phone_code_key]:'';
                    if($mobile_code==''||$mobile_code!=$phone_code_number){
                        $result['msg'] = $bind_mobile.$this->ld['incorrect_verification_code'];
                    }else{
                        $user_data=array('id'=>$user_id,'mobile'=>$bind_mobile);
                        $this->User->save($user_data);
                        $result['code'] = '1';
                        $result['msg'] = $this->ld['tips_edit_success'];
                    }
                }else{
                    $result['msg'] = $this->ld['mobile_exists'];
                }
            }
        }
        die(json_encode($result));
    }

    function ajax_email_bind(){
        Configure::write('debug',1);
        $this->layout = 'ajax';
        $result=array();
        $result['code'] = 0;
        $result['msg'] = $this->ld['invalid_operation'];
        if($this->RequestHandler->isPost()){
            $user_id=isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0;
            $user_info=$this->User->findById($user_id);
            if(!empty($user_info)){
                $old_user_email=trim($user_info['User']['email']);
                if($old_user_email!=''){
                    $old_email_code=isset($_POST['old_email_code'])?$_POST['old_email_code']:'';
                    $email_code_key="email_code_number{$old_user_email}";
                    $email_code_key=str_replace('.','_',$email_code_key);
                    $email_code_number=isset($_COOKIE[$email_code_key])?$_COOKIE[$email_code_key]:'';
                    if($old_email_code==''||$old_email_code!=$email_code_number){
                        $result['msg'] = $old_user_email.$this->ld['incorrect_verification_code'];
                        die(json_encode($result));
                    }
                }
                $bind_email=isset($_POST['bind_email'])?$_POST['bind_email']:'';
                if(empty($bind_mobile_user)){
                    $email_code=isset($_POST['bind_email_code'])?$_POST['bind_email_code']:'';
                    $email_code_key="email_code_number{$bind_email}";
                    $email_code_key=str_replace('.','_',$email_code_key);
                    $email_code_number=isset($_COOKIE[$email_code_key])?$_COOKIE[$email_code_key]:'';
                    if($email_code==''||$email_code!=$email_code_number){
                        $result['msg'] = $bind_email.$this->ld['incorrect_verification_code'];
                    }else{
                        $user_data=array('id'=>$user_id,'email'=>$bind_email);
                        $this->User->save($user_data);
                        $result['code'] = '1';
                        $result['msg'] = $this->ld['tips_edit_success'];
                    }
                }else{
                    $result['msg'] = $this->ld['email_exists'];
                }
            }
        }
        die(json_encode($result));
    }

    function ajax_check_invitation_code(){
        Configure::write('debug',1);
        $this->layout = 'ajax';
        $result=array();
        $result['code'] = 0;
        if($this->RequestHandler->isPost()){
            $invitation_code=isset($_POST['invitation_code'])?trim($_POST['invitation_code']):'';
            if(isset($this->configs['registration_invitation_code'])&&trim($this->configs['registration_invitation_code'])!=''){
                if($invitation_code==trim($this->configs['registration_invitation_code'])){
                    $result['code'] = 1;
                }
            }
        }
        die(json_encode($result));
    }

    /**
     *师傅列表
     */
    public function master()
    {
        //登录验证
        $this->checkSessionUser();
        $this->pageTitle = '师傅列表 - '.$this->configs['shop_title'];
        $this->layout = 'usercenter';            //引入模版
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            Configure::write('debug', 0);
            $this->layout = 'ajax';
        }
        $this->page_init();                    //页面初始化
        $user_id = $_SESSION['User']['User']['id'];
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => "师傅列表",'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $this->loadModel('Ability');$this->loadModel('UserAbility');
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        $_SESSION['User'] = $user_list;
        if ($user_list['User']['address_id'] != '0') {
            //获取我的地址
            $user_list['User']['UserAddress'] = $this->UserAddress->find('first', array('conditions' => array('id' => $user_list['User']['address_id']), 'fields' => 'id,zipcode,address_type,address,mobile,regions,sign_building'));
        }
        $this->set('user_list', $user_list);
        $master_data=array();
        if (isset($_SESSION['User']['User']['id'])) {
            $master_list = $this->UserRelationship->find('all', array('conditions' => array('UserRelationship.user_id' => $_SESSION['User']['User']['id'],'UserRelationship.status !=' =>2)));
            if(!empty($master_list)){
                foreach($master_list as $k=>$v){
                    $master_info=$this->User->get_user_all(array("User.id"=>$v['UserRelationship']['parent_user_id']));
                    $master_data[$k]['id']=$v['UserRelationship']['id'];
                    $master_data[$k]['user_id']=$master_info[0]['User']['id'];
                    $master_data[$k]['name']=isset($master_info[0]['User']['name'])?$master_info[0]['User']['name']:"-";
                    $master_data[$k]['img']=isset($master_info[0]['User']['img01'])?$master_info[0]['User']['img01']:"";
                    $master_data[$k]['initiator']=$v['UserRelationship']['initiator'];
                    $master_data[$k]['created']=$v['UserRelationship']['created'];
                    $master_data[$k]['status']=$v['UserRelationship']['status'];
                }
            }
        }
        $apprentice_data=array();
        if (isset($_SESSION['User']['User']['id'])) {
            $apprentice_list = $this->UserRelationship->find('all', array('conditions' => array('UserRelationship.parent_user_id' => $_SESSION['User']['User']['id'],'UserRelationship.status !=' =>2)));
            if(!empty($apprentice_list)){
                foreach($apprentice_list as $kk=>$vv){
                    $apprentice_info=$this->User->get_user_all(array("User.id"=>$vv['UserRelationship']['user_id']));
                    $apprentice_data[$kk]['id']=$vv['UserRelationship']['id'];
                    $apprentice_data[$kk]['user_id']=$apprentice_info[0]['User']['id'];
                    $apprentice_data[$kk]['name']=$apprentice_info[0]['User']['name'];
                    $apprentice_data[$kk]['img']=$apprentice_info[0]['User']['img01'];
                    $apprentice_data[$kk]['initiator']=$vv['UserRelationship']['initiator'];
                    $apprentice_data[$kk]['created']=$vv['UserRelationship']['created'];
                    $apprentice_data[$kk]['status']=$vv['UserRelationship']['status'];
                }
            }
        }
        $this->loadModel('UserConfig');
        if($this->RequestHandler->isPost()){
            if(isset($_POST['allow_apprentice'])){
                Configure::write('debug', 1);
                $this->layout = 'ajax';

                $user_config_cond=array();
                $user_config_cond['UserConfig.user_id']=$user_id;
                $user_config_cond['UserConfig.code']='allow_apprentice';
                $user_config_detail=$this->UserConfig->find('first',array('conditions'=>$user_config_cond));
                $user_config_data=array(
                    'id'=>isset($user_config_detail['UserConfig'])?$user_config_detail['UserConfig']['id']:0,
                    'user_id'=>$user_id,
                    'code'=>'allow_apprentice',
                    'value'=>$_POST['allow_apprentice']
                );
                $this->UserConfig->save($user_config_data);
                die($_POST['allow_apprentice']);
            }
        }
        $user_config_cond=array();
        $user_config_cond['UserConfig.user_id']=$user_id;
        $user_config_cond['UserConfig.code']='allow_apprentice';
        $user_config_cond['UserConfig.value']='1';
        $user_config_detail=$this->UserConfig->find('first',array('conditions'=>$user_config_cond));
        $this->set('user_config_detail',$user_config_detail);
        $this->set('apprentice_data',$apprentice_data);
        $this->set('user_id',$_SESSION['User']['User']['id']);
        $this->set('master_data',$master_data);
    }

    /**
     *徒弟课程
     */
    public function user_course($id)
    {
        //登录验证
        $this->checkSessionUser();
        $this->pageTitle = '徒弟课程 - '.$this->configs['shop_title'];
        $this->layout = 'usercenter';            //引入模版
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            Configure::write('debug', 0);
            $this->layout = 'ajax';
        }
        $this->page_init();                    //页面初始化
        $user_id = $_SESSION['User']['User']['id'];
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => "徒弟列表",'url' => '/users/master');
        $this->ur_heres[] = array('name' => "徒弟课程",'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $this->loadModel('Ability');$this->loadModel('UserAbility');
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        $apprentice_list = $this->User->find('first', array('conditions' => array('User.id' => $id)));
        $_SESSION['User'] = $user_list;
        if ($user_list['User']['address_id'] != '0') {
            //获取我的地址
            $user_list['User']['UserAddress'] = $this->UserAddress->find('first', array('conditions' => array('id' => $user_list['User']['address_id']), 'fields' => 'id,zipcode,address_type,address,mobile,regions,sign_building'));
        }
        $this->set('user_list', $user_list);
        $user_data=array();$flag=array();
        if (isset($_SESSION['User']['User']['id'])) {
            $course_data=$this->UserCourseClass->find('all',array('conditions'=>array('UserCourseClass.user_id'=>$id,'UserCourseClass.status !='=>'0'), 'fields' => 'UserCourseClass.id,UserCourseClass.course_id'));
            if(!empty($course_data)){
                foreach($course_data as $k=>$v){
                	$flag[$v['UserCourseClass']['id']]=$this->Course->access_permission($this,$v['UserCourseClass']['course_id'],$v['UserCourseClass']['id'],false);
                    $user_data[]=$this->Course->course_detail(array("id"=>$v['UserCourseClass']['course_id'],"user_id"=>$id));
                }
            }
        }
        $this->set('flag',$flag);
        $this->set('course_data',$user_data);
        $this->set('apprentice_list',$apprentice_list);
        $this->set('user_id',$_SESSION['User']['User']['id']);
    }

    //更改师徒状态
    public function change_status()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!empty($_POST['id']) && !empty($_POST['status'])){
            $data=array('id'=>$_POST['id'],'status'=>$_POST['status']);
            $this->UserRelationship->save($data);
            $result['error'] = 1;
            $result['msg'] = "操作成功";
        }else {
            $result['error'] = 0;
            $result['msg'] = "操作失败";
        }
        die(json_encode($result));
    }

    public function select_name()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!empty($_POST['search_keyword']) && !empty($_POST['id']) && !empty($_POST['type'])){
            $condition=array();
            $condition['or']['and']['User.name like'] = "%{$_POST['search_keyword']}%";
            $condition['or']['or']['and']['User.first_name like'] = "%{$_POST['search_keyword']}%";
            $condition['or']['or']['or']['and']['User.mobile'] = $_POST['search_keyword'];
            $user_config_cond=array();
            $user_config_cond['UserConfig.code']='allow_apprentice';
            $user_config_cond['UserConfig.value']='1';
            if($_POST['type']==1){
	            $user_list=$this->UserConfig->find('list',array('conditions'=>$user_config_cond,'fields'=>'UserConfig.user_id'));
	            if(!empty($user_list)){
	                $condition['User.id']=$user_list;
	            }else{
	                $condition['User.id']=0;
	            }
            }
            $fields=array('User.id','User.name','User.first_name','User.mobile','User.email','User.img01');
            $data=$this->User->find('all', array('conditions' => $condition,'fields'=>$fields));
            if(!empty($data)){
                foreach($data as $k=>$v){
                    if($v['User']['id']==$_POST['id']){
                        unset($data[$k]);
                        continue;
                    }
                    $master_data=$this->UserRelationship->find('first', array('conditions' => array('UserRelationship.parent_user_id' =>$v['User']['id'],'UserRelationship.user_id' =>$_POST['id'],'status !='=>2)));
                    $master_data1=$this->UserRelationship->find('first', array('conditions' => array('UserRelationship.parent_user_id' =>$_POST['id'],'UserRelationship.user_id' =>$v['User']['id'],'status !='=>2)));
                    if(!empty($master_data) || !empty($master_data1)){
                        unset($data[$k]);
                        continue;
                    }
                    if(!empty($v['User']['mobile'])){
                        $data[$k]['User']['mobile']=substr_replace($v['User']['mobile'],'****',3,4);
                    }
                }
            }
            $result['error'] = 1;
            $result['data'] = $data;
            $result['msg'] = "操作成功";
        }else {
            $result['error'] = 0;
            $result['msg'] = "操作失败";
        }
        die(json_encode($result));
    }

    //添加师徒
    public function add_master()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if(!empty($_POST['id']) && !empty($_POST['type']) && !empty($_POST['user_id'])){
            if($_POST['type']==1){
                $data=$this->UserRelationship->find('first', array('conditions' => array('UserRelationship.parent_user_id' =>$_POST['user_id'],'UserRelationship.user_id' =>$_POST['id'],'UserRelationship.initiator' =>0)));
                $data['UserRelationship']['parent_user_id']=$_POST['user_id'];
                $data['UserRelationship']['user_id']=$_POST['id'];
                $data['UserRelationship']['initiator']=0;
                $data['UserRelationship']['status']=0;
            }else{
                $data=$this->UserRelationship->find('first', array('conditions' => array('UserRelationship.user_id' =>$_POST['user_id'],'UserRelationship.parent_user_id' =>$_POST['id'],'UserRelationship.initiator' =>1)));
                $data['UserRelationship']['parent_user_id']=$_POST['id'];
                $data['UserRelationship']['user_id']=$_POST['user_id'];
                $data['UserRelationship']['initiator']=1;
                $data['UserRelationship']['status']=0;
            }
            $this->UserRelationship->saveAll($data);
            $result['error'] = 1;
            $result['msg'] = "操作成功";
        }else {
            $result['error'] = 0;
            $result['msg'] = "操作失败";
        }
        die(json_encode($result));
    }

    /**
     *徒弟笔记
     */
    public function user_note()
    {
        //登录验证
        $this->checkSessionUser();
        $this->pageTitle = '师徒关系 - '.$this->configs['shop_title'];
        $this->layout = 'usercenter';            //引入模版
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            Configure::write('debug', 0);
            $this->layout = 'ajax';
        }
        $this->page_init();                    //页面初始化
        $user_id = $_SESSION['User']['User']['id'];
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => "师徒关系",'url' => '/users/master');
        $this->ur_heres[] = array('name' => "徒弟笔记列表",'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $this->loadModel('Ability');$this->loadModel('UserAbility');
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        $_SESSION['User'] = $user_list;
        if ($user_list['User']['address_id'] != '0') {
            //获取我的地址
            $user_list['User']['UserAddress'] = $this->UserAddress->find('first', array('conditions' => array('id' => $user_list['User']['address_id']), 'fields' => 'id,zipcode,address_type,address,mobile,regions,sign_building'));
        }
        $this->set('user_list', $user_list);
        $note_data=array();
        $course_list=$this->Course->find('list',array('fields'=>"Course.id,Course.name","conditions"=>array('Course.status' =>1)));
        $course_id="-1";
        if (isset($_SESSION['User']['User']['id'])) {
            if (isset($this->params['url']['user_name']) && $this->params['url']['user_name'] != '') {
                $user_condition['or']['User.name like'] = '%' . $_REQUEST['user_name'] . '%';
                $user_ids=$this->User->find('list',array('fields'=>"User.id","conditions"=>$user_condition));
                $condition['and']['CourseNote.user_id'] = $user_ids;
                $this->set('user_name', $_REQUEST['user_name']);
            }
            if (isset($this->params['url']['user_phone']) && $this->params['url']['user_phone'] != '') {
                $user_condition['or']['User.mobile'] = $_REQUEST['user_phone'];
                $user_ids=$this->User->find('list',array('fields'=>"User.id","conditions"=>$user_condition));
                $condition['and']['CourseNote.user_id'] = $user_ids;
                $this->set('user_phone', $_REQUEST['user_phone']);
            }
            if (isset($this->params['url']['course_id']) && $this->params['url']['course_id'] != '-1') {
                $condition['and']['CourseNote.course_id'] = $this->params['url']['course_id'];
                $course_id = $this->params['url']['course_id'];
            }
            //徒弟列表
            $flag=array();
            $apprentice_ids = $this->UserRelationship->find('list', array('conditions' => array('UserRelationship.parent_user_id' => $_SESSION['User']['User']['id'],'UserRelationship.status' =>1), 'fields' => array('UserRelationship.user_id')));
            if(!empty($apprentice_ids)){
                $condition['and']['CourseNote.user_id']=$apprentice_ids;
                $condition['and']['CourseNote.is_public']=0;
                $note_info=$this->CourseNote->find('all', array('fields' => array('count(*) as countnum','CourseNote.user_id', 'CourseNote.course_id', 'CourseNote.course_class_id', 'CourseNote.created'),'conditions' => $condition,'group' => 'CourseNote.course_class_id,CourseNote.user_id','order' => 'CourseNote.created desc'));
                if(!empty($note_info)){
                    foreach($note_info as $k=>$v){
                    	$flag[$v['CourseNote']['course_class_id']]=$this->Course->access_permission($this,$v['CourseNote']['course_id'],$v['CourseNote']['course_class_id'],false);
                        $name=$this->User->find('first', array('conditions' => array('User.id' => $v['CourseNote']['user_id']), 'fields' => array('User.name')));
                        $note_data[$k]['course_id']=$v['CourseNote']['course_id'];
                        $note_data[$k]['course_class_id']=$v['CourseNote']['course_class_id'];
                        $note_data[$k]['user_id']=$v['CourseNote']['user_id'];
                        $note_data[$k]['user_name']=isset($name['User']['name'])?$name['User']['name']:"";
                        $course_class=$this->CourseClass->find('first', array('conditions' => array('CourseClass.id' => $v['CourseNote']['course_class_id'])));
                        $note_data[$k]['course_class_name']=isset($course_class['CourseClass']['name'])?$course_class['CourseClass']['name']:"";
                        $note_data[$k]['courseware_hour']=isset($course_class['CourseClass']['courseware_hour'])?ceil($course_class['CourseClass']['courseware_hour'])."分钟":"";
                        $note_data[$k]['course_chapter_name']=isset($course_class['CourseChapter']['name'])?$course_class['CourseChapter']['name']:"";
                        $course=$this->Course->find('first', array('conditions' => array('Course.id' => $v['CourseNote']['course_id'])));
                        $note_data[$k]['course_name']=isset($course['Course']['name'])?$course['Course']['name']:"";
                        $note_data[$k]['note_num']=$v[0]['countnum'];
                        $reply_num=0;
                        if($v[0]['countnum']!=0){
                        	$note_ids=$this->CourseNote->find('list', array('fields' => array('id'),'conditions' => array('CourseNote.course_class_id' => $v['CourseNote']['course_class_id'],'CourseNote.user_id' => $v['CourseNote']['user_id'])));
                        	$reply_list=$this->CourseNoteReply->find('first', array('fields' => array('count(*) as countnum'),'conditions' => array("CourseNoteReply.course_note_id"=>$note_ids,"CourseNoteReply.reply_from"=>0,"CourseNoteReply.reply_from_id"=>$user_id)));
                        	if(!empty($reply_list)){
                        		$reply_num=$reply_list[0]['countnum'];
                        	}
                        }
                        $note_data[$k]['reply_num']=$reply_num;
                        $note_data[$k]['note_create']=date("Y.m.d",strtotime($v['CourseNote']['created']));
                    }
                }
            }
        }
        $this->set('flag',$flag);
        $this->set('user_id',$_SESSION['User']['User']['id']);
        $this->set('course_list',$course_list);
        $this->set('note_data',$note_data);
        $this->set('course_id',$course_id);
    }

    public function note_reply()
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $note_data=array();
        if(!empty($_POST['id'])) {
            $note_data = $this->CourseNote->find('all', array('conditions' => array('CourseNote.course_class_id' => $_POST['id'],'CourseNote.user_id' => $_POST['user_id'],'CourseNote.is_public' => 0)));
        	foreach($note_data as $k=>$v){
        		$note_data[$k][$v["CourseNote"]["id"]] = $this->CourseNoteReply->find('first', array('conditions' => array('CourseNoteReply.course_note_id' => $v["CourseNote"]["id"],"CourseNoteReply.reply_from"=>0,"CourseNoteReply.reply_from_id"=>$_SESSION['User']['User']['id'])));
        	}
        }
        $this->set('note_data',$note_data);
    }

    function ajax_modify_submit(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        if(isset($_POST['ids'])){
            foreach($_POST['ids'] as $v){
                $data['CourseNoteReply']['course_note_id']=$v;
                $data['CourseNoteReply']['reply_from']=0;
                $data['CourseNoteReply']['reply_from_id']=$_SESSION['User']['User']['id'];
                $name='note_'.$v;
                $id_name='id_'.$v;
                $ware_name='ware_info_'.$v;
                if($_POST[$name]!=""){
                    $data['CourseNoteReply']['content']=$_POST[$name];
                    $data['CourseNoteReply']['id']=$_POST[$id_name];
                    if(isset($_FILES[$ware_name])){
						$mediaInfo=pathinfo($_FILES[$ware_name]['name']);
						$mediaName=md5($mediaInfo['filename'].time()).".".$mediaInfo['extension'];
						$media_root=WWW_ROOT.'media/CourseAssignmentMedia/';
						$this->mkdirs($media_root);
						if (move_uploaded_file($_FILES[$ware_name]['tmp_name'], $media_root.$mediaName)) {
							$media_path = '/media/CourseAssignmentMedia/'.$mediaName;
							$data['CourseNoteReply']['media']=$media_path;
						}
                    }
                    $this->CourseNoteReply->saveAll($data);
                }
            }
        }
        $result['code']='1';
        $result['message']='操作成功';
        die(json_encode($result));
    }

    function delete_submit(){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result=array();
        if(isset($_POST['id'])){
            $result=array();
            $result['code']='1';
            $result['message']='删除成功';
            $this->CourseNoteReply->deleteAll(array('id'=>$_POST['id']));
            die(json_encode($result));
        }
        $result['code']='0';
        $result['message']='操作失败';
        die(json_encode($result));
    }

    function user_assignment($page=1,$limit=10){
        //登录验证
        $this->checkSessionUser();
        $_GET=$this->clean_xss($_GET);
        $this->layout = 'usercenter';//引入模版
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            Configure::write('debug', 0);
            $this->layout = 'ajax';
        }
        $this->page_init();                        //页面初始化
        $this->pageTitle = '课程笔记 - '.$this->configs['shop_title'];
        //当前位置开始
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
        $this->ur_heres[] = array('name' => "师徒关系",'url' => '/users/master');
        $this->ur_heres[] = array('name' => "徒弟作业列表",'url' => '');
        $user_id=$_SESSION['User']['User']['id'];
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        if ($user_list['User']['rank'] > 0) {
            $rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
            $user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
        }
        $this->set('user_list', $user_list);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($user_id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($user_id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($user_id);
        $this->set('focuscount', $focus);

        $this->loadModel('CourseAssignment');

        $joins=array(
            array(
                'table' => 'svhr_courses',
                'alias' => 'Course',
                'type' => 'left',
                'conditions' => array('CourseAssignment.course_id = Course.id')
            ),
            array(
                'table' => 'svhr_course_class_wares',
                'alias' => 'CourseClassWare',
                'type' => 'left',
                'conditions' => array('CourseAssignment.course_ware_id = CourseClassWare.id and CourseClassWare.course_code=Course.code')
            ),
            array(
                'table' => 'svhr_course_classes',
                'alias' => 'CourseClass',
                'type' => 'left',
                'conditions' => array('CourseClass.code = CourseClassWare.course_class_code and CourseClass.course_code=Course.code')
            ),
            array(
                'table' => 'svhr_course_chapters',
                'alias' => 'CourseChapter',
                'type' => 'left',
                'conditions' => array('CourseChapter.course_code = Course.code and CourseChapter.code=CourseClass.chapter_code')
            )
        );

        $apprentice_ids = $this->UserRelationship->find('list', array('conditions' => array('UserRelationship.parent_user_id' => $user_id,'UserRelationship.status' =>1), 'fields' => array('UserRelationship.user_id')));
        $conditions=array();
        $user_condition=array();
        if(!empty($apprentice_ids)){
            $user_condition['User.id']=$apprentice_ids;
        }else{
            $user_condition['User.id']=0;
        }
        if (isset($this->params['url']['user_name']) && $this->params['url']['user_name'] != '') {
            $user_condition['User.name like']="%".$this->params['url']['user_name']."%";
            $user_condition['User.first_name like']="%".$this->params['url']['user_name']."%";
            $this->set('user_name',$this->params['url']['user_name']);
        }
        if (isset($this->params['url']['user_phone']) && $this->params['url']['user_phone'] != '') {
            $user_condition['User.mobile like']="%".$this->params['url']['user_phone']."%";
            $this->set('user_phone',$this->params['url']['user_phone']);
        }
        $assignment_user=$this->User->find('list',array('conditions'=>$user_condition,'fields'=>'User.id'));
        if(!empty($assignment_user)){
            $conditions['CourseAssignment.user_id']=$assignment_user;
        }else{
            $conditions['CourseAssignment.user_id <']=0;
        }
        if (isset($this->params['url']['user_course']) && $this->params['url']['user_course'] != '') {
            $conditions['Course.name like']="%".$this->params['url']['user_course']."%";
            $this->set('user_course',$this->params['url']['user_course']);
        }
        $conditions['CourseAssignment.status']='1';
        $conditions['CourseClassWare.status']='1';
        $conditions['Course.status']='1';
        $user_assignment_total=$this->CourseAssignment->find('count',array('conditions'=>$conditions,'joins'=>$joins));
        $parameters=array();
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'users', 'action' => 'user_assignment', 'page' => $page, 'limit' => $limit);
        //分页参数
        $options = array('page' => $page, 'show' => $limit, 'modelClass' => 'CourseAssignment','total'=>$user_assignment_total);
        $this->Pagination->init($conditions, $parameters, $options); // Added

        $fields=array(
            'CourseAssignment.*',
            'Course.id',
            'Course.name',
            'CourseChapter.name',
            'CourseClass.name',
            'CourseClassWare.name',
            'CourseClassWare.description'
        );
        $user_course_assignments=$this->CourseAssignment->find('all',array('fields'=>$fields,'conditions'=>$conditions,'joins'=>$joins,'page'=>$page,'limit'=>$limit,'order'=>'CourseAssignment.modified desc'));
        foreach($user_course_assignments as $k=>$v){
			$user_course_assignments[$k]['percentage']=$this->Course->course_detail(array("id"=>$v['Course']['id'],"user_id"=>$v['CourseAssignment']['user_id']));
		}
        $this->set('user_course_assignments',$user_course_assignments);
        if(!empty($user_course_assignments)){
            $assignment_user_ids=array();
            foreach($user_course_assignments as $v){
                $assignment_user_ids[]=$v['CourseAssignment']['user_id'];
            }
            $assignment_user_infos=$this->User->find('all',array('fields'=>"User.id,User.name,User.first_name",'conditions'=>array('User.id'=>$assignment_user_ids,'User.status'=>'1')));
            if(!empty($assignment_user_infos)){
                $assignment_user_list=array();
                foreach($assignment_user_infos as $v)$assignment_user_list[$v['User']['id']]=$v['User'];
                $this->set('assignment_user_list',$assignment_user_list);
            }
        }
    }

    function user_assignment_detail($user_assignment_id=0){
        //登录验证
        $this->checkSessionUser();
        $_GET=$this->clean_xss($_GET);
        $this->layout = 'usercenter';//引入模版
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            Configure::write('debug', 0);
            $this->layout = 'ajax';
        }
        $this->page_init();                        //页面初始化
        $this->pageTitle = '课程笔记 - '.$this->configs['shop_title'];
        //当前位置开始
        $this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
        $this->ur_heres[] = array('name' => "师徒关系",'url' => '/users/master');
        $this->ur_heres[] = array('name' => "徒弟作业列表",'url' => '/users/user_assignment');
		$this->ur_heres[] = array('name' => "徒弟作业详情",'url' => '');
        $user_id=$_SESSION['User']['User']['id'];
        //获取我的信息
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
        if ($user_list['User']['rank'] > 0) {
            $rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
            $user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
        }
        $this->set('user_list', $user_list);
        //粉丝数量
        $fans = $this->UserFans->find_fanscount_byuserid($user_id);
        $this->set('fanscount', $fans);
        //日记数量
        $blog = $this->Blog->find_blogcount_byuserid($user_id);
        $this->set('blogcount', $blog);
        //关注数量
        $focus = $this->UserFans->find_focuscount_byuserid($user_id);
        $this->set('focuscount', $focus);

        $this->loadModel('CourseAssignment');
        $this->loadModel('CourseAssignmentScore');

        $joins=array(
            array(
                'table' => 'svhr_courses',
                'alias' => 'Course',
                'type' => 'left',
                'conditions' => array('CourseAssignment.course_id = Course.id')
            ),
            array(
                'table' => 'svhr_course_class_wares',
                'alias' => 'CourseClassWare',
                'type' => 'left',
                'conditions' => array('CourseAssignment.course_ware_id = CourseClassWare.id and CourseClassWare.course_code=Course.code')
            ),
            array(
                'table' => 'svhr_course_classes',
                'alias' => 'CourseClass',
                'type' => 'left',
                'conditions' => array('CourseClass.code = CourseClassWare.course_class_code and CourseClass.course_code=Course.code')
            ),
            array(
                'table' => 'svhr_course_chapters',
                'alias' => 'CourseChapter',
                'type' => 'left',
                'conditions' => array('CourseChapter.course_code = Course.code and CourseChapter.code=CourseClass.chapter_code')
            )
        );
        $apprentice_ids = $this->UserRelationship->find('list', array('conditions' => array('UserRelationship.parent_user_id' => $user_id,'UserRelationship.status' =>1), 'fields' => array('UserRelationship.user_id')));
        $conditions=array();
        $conditions['CourseAssignment.id']=$user_assignment_id;
        if(!empty($apprentice_ids)){
            $conditions['CourseAssignment.user_id']=$apprentice_ids;
        }else{
            $conditions['CourseAssignment.user_id <']=0;
        }
        $fields=array(
            'CourseAssignment.*',
            'Course.name',
            'CourseChapter.name',
            'CourseClass.id',
            'CourseClass.name',
            'CourseClassWare.name',
            'CourseClassWare.description'
        );
        $user_course_assignments=$this->CourseAssignment->find('first',array('fields'=>$fields,'conditions'=>$conditions,'joins'=>$joins));
        if($this->RequestHandler->isPost()){
            Configure::write('debug',1);
            $this->layout = 'ajax';
            $result=array();
            $result['code'] = 0;
            $result['message'] = $this->ld['failed'];
            if(isset($this->data['CourseAssignmentScore'])&&!empty($this->data['CourseAssignmentScore'])&&!empty($user_course_assignments)){
                $score_cond=array();
                $score_cond['CourseAssignmentScore.course_assignment_id']=$user_assignment_id;
                $score_cond['CourseAssignmentScore.reply_from']='0';
                $score_cond['CourseAssignmentScore.reply_from_id']=$user_id;
                $CourseAssignmentScoreInfo=$this->CourseAssignmentScore->find('first',array('conditions'=>$score_cond));
                $this->data['CourseAssignmentScore']['id']=isset($CourseAssignmentScoreInfo['CourseAssignmentScore'])?$CourseAssignmentScoreInfo['CourseAssignmentScore']['id']:0;
                $this->data['CourseAssignmentScore']['reply_from']='0';
                $this->data['CourseAssignmentScore']['reply_from_id']=$user_id;
                $this->CourseAssignmentScore->save($this->data['CourseAssignmentScore']);
                $result['code'] = 1;
                $result['message'] = $this->ld['saved_successfully'];
            }
            die(json_encode($result));
        }
        if(empty($user_course_assignments))$this->redirect('/users/user_assignment');
        $this->set('user_course_assignments',$user_course_assignments);

        $assignment_user_info=$this->User->find('first',array('fields'=>"User.id,User.name,User.first_name",'conditions'=>array('User.id'=>$user_course_assignments['CourseAssignment']['user_id'],'User.status'=>'1')));
        $this->set('assignment_user_info',$assignment_user_info);
    }
}