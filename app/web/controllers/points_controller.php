<?php

/*****************************************************************************
 * Seevia 用户积分
 * @copyright 版权?  上海实玮网络科技有限公司，并保留?权利?
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布?
 * $?: 上海实玮$
 * $Id$
*****************************************************************************/
uses('sanitize');
/**
 *这是?名为 PointsController 的用户积分控制器.
 */
class PointsController extends AppController
{
    /*
     *@var $name
     *@var $components
     *@var $helpers
     *@var $uses
     */
    public $name = 'Points';
    public $components = array('Pagination'); // Added
    public $helpers = array('Pagination'); // Added
    public $uses = array('UserPointLog','Article','CategoryArticle','User','Product','ProductI18n','Payment','UserAccount','PaymentApiLog','UserFans','Blog');
    //var $log_type=array("A"=>"管理员操作","O"=>"购买消费","B"=>"购买赠送","R"=>"注册赠送");


    /**
     *函数 index 进入用户积分页面.
     */
    public function index($page = 1)
    {
        //登录验证
        $this->checkSessionUser();
        $this->layout = 'usercenter';            //引入模版
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            Configure::write('debug', 0);
            $this->layout = 'ajax';
        }
        $this->page_init();                        //页面初始化 
        //页面标题
        $this->pageTitle = $this->ld['points'].' - '.$this->configs['shop_title'];
        //当前位置开始 
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => $this->ld['account_reward_points'],'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        
        //我的积分信息
        // pr($_SESSION);
        $user_id = $_SESSION['User']['User']['id'];
         //pr($user_id);
        $user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
         //pr($user_list);

        $this->set('user_list', $user_list);
        //分享绑定显示判断
        $app_share = $this->UserApp->app_status();
        //pr($app_share);
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
        
        //当前用户积分
        $my_point=isset($user_list['User']['point'])?$user_list['User']['point']:0;
        $this->set('my_point', $my_point);
        
        //积分规则 
        $point_category = $this->CategoryArticle->find('first', array('conditions' => array('CategoryArticleI18n.name LIKE' => '%积分%'), 'fields' => 'CategoryArticle.id'));
        $mypoint = $this->Article->find('first', array('conditions' => array('ArticleI18n.title LIKE' => '%积分规则%', 'Article.category_id' => $point_category['CategoryArticle']['id']), 'fields' => 'Article.id'));
        $this->set('mypoint_id', $mypoint['Article']['id']);

        //热门兑换推荐 
        $conditions = array();
        $conditions['AND']['Product.status'] = 1;
        $conditions['AND']['Product.forsale'] = 1;
        $conditions['AND']['Product.recommand_flag'] = 1;
        $conditions['AND']['Product.point_fee >'] = 0;
        $myproduct = $this->Product->find('all', array('conditions' => $conditions, 'limit' => '10', 'order' => 'Product.sale_stat desc'));
        $this->set('myproduct', $myproduct);

        //我的积分使用情况
        $condition['UserPointLog.user_id'] = $user_id;
        if (isset($this->params['url']['date']) && $this->params['url']['date'] != '0') {
            $m = date('Y-m-d');
            //获取传来的日期的前三个月份
            $last_m = date('Y-m-d H:i:s', mktime(0, 0, 0, date('m', strtotime($m)) - $this->params['url']['date'], date('d', strtotime($m)), date('Y', strtotime($m))));
            $condition['UserPointLog.created >='] = $last_m;
            $this->set('date', $this->params['url']['date']);
        }
        //分页start
        //get参数
        $limit = 20;
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'points', 'action' => 'index', 'page' => $page, 'limit' => $limit);
        //分页参数
        $options = array('page' => $page, 'show' => $limit, 'modelClass' => 'UserPointLog');
        $page = $this->Pagination->init($condition, $parameters, $options); // Added
        //分页end 
        $my_point_logs = $this->UserPointLog->find('all', array('conditions' => $condition, 'order' => 'UserPointLog.id desc', 'limit' => $limit, 'page' => $page));
        //pr($my_point_logs);
        $this->set('my_point_logs', $my_point_logs);
        
        $log_type = isset($this->system_resources['point_log_type'])?$this->system_resources['point_log_type']:array();
        $this->set('log_type',$log_type);
    }
    /**
     *函数 user_exchange 用于商品交易.
     */
    public function user_exchange()
    {
        if (!isset($_SESSION['User']['User'])) {
            $this->redirect('/login/');
        }
        $user_info = $this->User->find_user_by_id($_SESSION['User']['User']['id']);

        if ($user_info['User']['point'] > 0) {
            $orderby = 'point_fee ASC';
        } else {
            $orderby = 'market_price DESC';
        }

        $sortClass = 'Product';
        $rownum = 10;
        $page = 1;
        $parameters = array($orderby,$rownum,$page);
        $options = array();

        $condition = "Product.point_fee > '0' and Product.status = '1' and Product.forsale = '1' and Product.quantity > '0'";

        $products = $this->Product->get_products($condition, $rownum, $page);

        $products_ids_list = array();
        if (is_array($products) && sizeof($products) > 0) {
            foreach ($products as $k => $v) {
                $products_ids_list[] = $v['Product']['id'];
            }
        }

        // 商品多语?
        $productI18ns_list = array();

        $productI18ns = $this->ProductI18n->find('all', array(
                'fields' => array('ProductI18n.id', 'ProductI18n.name', 'ProductI18n.product_id'),
                'conditions' => array('ProductI18n.product_id' => $products_ids_list, 'ProductI18n.locale' => LOCALE), ));

        if (isset($productI18ns) && sizeof($productI18ns) > 0) {
            foreach ($productI18ns as $k => $v) {
                $productI18ns_list[$v['ProductI18n']['product_id']] = $v;
            }
        }
        $options = array('page' => $page,'show' => $rownum,'modelClass' => 'Product');
        $total = count($products);
//        $page = $this->Pagination->init($condition,$parameters,$options,$total,$rownum,$sortClass); // Added
        $page = $this->Pagination->init($condition, $parameters, $options); // Added

        foreach ($products as $k => $v) {
            if (isset($productI18ns_list[$v['Product']['id']])) {
                $products[$k]['ProductI18n'] = $productI18ns_list[$v['Product']['id']]['ProductI18n'];
            } else {
                $products[$k]['ProductI18n']['name'] = '';
            }

            if (isset($this->configs['products_name_length']) && $this->configs['products_name_length'] > 0) {
                $products[$k]['ProductI18n']['name'] = $this->Product->sub_str($products[$k]['ProductI18n']['name'], $this->configs['products_name_length']);
            }
        }
    }

    /**
     *函数 user_payment_point 用于支付.
     */
    public function user_payment_point()
    {
        $no_error = 1;
        if (!isset($_POST['is_ajax'])) {
            if ($_POST['amount_num'] == '') {
                $no_error = 0;//larger_zero
                $_REQUEST['msg'] = $this->ld['supply'].$this->ld['amount'].$this->ld['can_not_empty'];
            } elseif ($_POST['amount_num'] == 0) {
                $no_error = 0;//larger_zero
                $_REQUEST['msg'] = $this->ld['supply'].$this->ld['amount'].$this->ld['larger_zero'];
            } elseif (!isset($_POST['payment_id']) || $_POST['payment_id'] == '' || $_POST['payment_id'] < 1) {
                $no_error = 0;
                $_REQUEST['msg'] = $this->ld['please_select'].$this->ld['payment'];
            } else {
                $_REQUEST['money'] = $_POST['amount_num'];
            }
            $url_format = isset($_REQUEST['msg']) ? $_REQUEST['msg'] : '';
        }
        if (!(isset($_REQUEST['msg']))) {
            $modified = date('Y-m-d H:i:s');
            $user_id = $_SESSION['User']['User']['id'];
            $user_info = $this->User->find_user_by_id($user_id);//调用model
            $user_money = $user_info['User']['balance'] + $_REQUEST['money'];
            $amount_money = $_REQUEST['money'];
            $payment_id = $_POST['payment_id'];
            $pay = $this->Payment->get_payment_id($payment_id);
            $pay_php = $pay['Payment']['php_code'];
            $account_info = array(
                    'id' => '',
                    'user_id' => $user_id,
                    'amount' => $amount_money,
                    'payment' => $payment_id,
                    'status' => 0,
            );
            $this->UserAccount->save($account_info);
            $account_id = $this->UserAccount->id;

            $pay_log = array();
            $pay_log['id'] = '';
            $pay_log['payment_code'] = $pay['Payment']['code'];
            $pay_log['type'] = 2;
            $pay_log['type_id'] = $account_id;
            $pay_log['amount'] = $amount_money;
            $pay_log['is_paid'] = 0;
            $this->PaymentApiLog->save($pay_log);
            $log_id = $this->PaymentApiLog->id;
            $pay_created = $this->PaymentApiLog->find_payment_log_by_id($log_id);
            $order = array(
                    'total' => $amount_money,
                    'log_id' => $log_id,
                    'created' => $pay_created['PaymentApiLog']['created'],
            );
            $message = array(
                    'msg' => $this->ld['supply_method_is'].':'.$pay['PaymentI18n']['name'],
                    'url' => '',
            );
            $_REQUEST['msg'] = $this->ld['supply_method_is'].':'.$pay['PaymentI18n']['name'];
            $str = '$pay_class = new '.$pay['Payment']['code'].'();';
            if ($pay['Payment']['code'] == 'bank' || $pay['Payment']['code'] == 'post' || $pay['Payment']['code'] == 'COD' ||  $pay['Payment']['code'] == 'account_pay') {
                $pay_message = $pay['PaymentI18n']['description'];
                $url_format = $pay_message;
                $this->set('pay_message', $pay_message);
            } elseif ($pay['Payment']['code'] == 'alipay') {
                eval($pay_php);
                eval($str);
                $url = $pay_class->get_code($order, $pay, $this);
                $url_format = "<input type=\"button\" onclick=\"window.open('".$url."')\" value=\"".$this->ld['alipay_pay_immedia'].'" />';
                $this->set('pay_button', $url);
            } else {
                eval($pay_php);
                eval($str);
                $url = $pay_class->get_code($order, $pay, $this);
                $url_format = $url;
                $this->set('pay_message', $url);
            }
        } else {
            $message = array(
                    'msg' => $_REQUEST['msg'],
                    'url' => '',
            );
        }
        if (!isset($_POST['is_ajax'])) {
            $this->page_init();
            $this->pageTitle = $_REQUEST['msg'];
            $flash_url = $this->server_host.$this->user_webroot.'balances';
            $this->flash($url_format, $flash_url, 10);
        }
        $this->set('result', $message);
        $this->layout = 'ajax';
    }
    /**
     *函数 user_pay_point 用于支付.
     */
    public function user_pay_point()
    {
        $no_error = 1;
        if ($this->RequestHandler->isPost()) {
            $pay_log = $this->PaymentApiLog->find_payment_log_by_id($_POST['id']);
            $pay = $this->Payment->find_pay_by_code($pay_log['PaymentApiLog']['payment_code']);
            $order_pr = array(
                    'total' => $pay_log['PaymentApiLog']['amount'],
                    'log_id' => $pay_log['PaymentApiLog']['id'],
                    'created' => $pay_log['PaymentApiLog']['created'],
            );

            //	$result['msg'] = $this->ld['supply'];
            $result['msg'] = $this->ld['supply_method_is'].':'.$pay['PaymentI18n']['name'];
            $pay_php = $pay['Payment']['php_code'];
            $str = '$pay_class = new '.$pay['Payment']['code'].'();';
            if ($pay['Payment']['code'] == 'bank' || $pay['Payment']['code'] == 'post' || $pay['Payment']['code'] == 'COD' ||  $pay['Payment']['code'] == 'account_pay') {
                $pay_message = $pay['PaymentI18n']['description'];
                $url_format = $pay_message;
                $this->set('pay_message', $pay_message);
            } elseif ($pay['Payment']['code'] == 'alipay') {
                eval($pay_php);
                eval($str);
                $url = $pay_class->get_code($order_pr, $pay, $this);
                $url_format = "<input type=\"button\" onclick=\"window.open('".$url."')\" value=\"".$this->ld['alipay_pay_immedia'].'" />';
                $this->set('pay_button', $url);
            } else {
                eval($pay_php);
                eval($str);
                $url = $pay_class->get_code($order_pr, $pay, $this);
                $url_format = $url;
                $this->set('pay_message', $url);
            }
            $result['type'] = 0;
        }
        if (!isset($_POST['is_ajax'])) {
            $this->page_init();
            $this->pageTitle = $result['msg'];
            $flash_url = $this->server_host.$this->user_webroot.'balances';
            $this->flash($url_format, $flash_url, 10);
        }

        $this->set('result', $result);
        $this->layout = 'ajax';
    }
}
