<?php

/**
 *这是一个名为 ApplicationsController 的控制器
 *后台商品管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
//include(ROOT."/vendors/nusoap/nusoap.php");
App::import('Vendor', 'nusoap');
App::import('Controller', 'Commons');//加载公共控制器
class ApplicationsController extends AppController
{
    public $name = 'Applications';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');
    public $uses = array('Cronjob','Application','ApplicationConfig','Navigation','NavigationI18n','ApplicationConfigI18n','Language','Dictionary','ProductI18n','Language','ConfigI18n','ArticleI18n','CategoryArticleI18n','BrandI18n','Resource','InformationResourceI18n','Template','Advertisement','AdvertisementPosition','AdvertisementPosition','Advertisement','OperatorLog','Payment','PaymentI18n');
    public $all_app_infos = array();//'Domain',
    /**
      *显示商品列表.
      */
    public function index($page = 1, $id = 0)
    {
        $this->menu_path = array('root' => '/system/','sub' => '/applications/?order=isInstall');
//		if(isset($_GET['order'])&&$_GET['order']=='isInstall'){
//			$this->operator_privilege('myapplications_view');
//		}else{
//			$this->operator_privilege('applications_view');
//		}
        $this->operator_privilege('configvalues_view');//按商品设置权限判断
        if (isset($_GET['use_app'])) {
            $_SESSION['use_app'] = $_GET['use_app'];
        }
        if (!isset($_POST['order']) && !isset($_GET['order']) && !isset($_POST['catenum']) && !isset($_GET['catenum']) && isset($_SESSION['order']) && isset($_SESSION['catenum'])) {
            $order = $_SESSION['order'];
            $catenum = $_SESSION['catenum'];
        } else {
            $order = isset($_POST['order']) && $_POST['order'] != '' || isset($_GET['order']) ? isset($_POST['order']) ? $_POST['order'] : $_GET['order'] : 'created';
            $catenum = isset($_POST['catenum']) && $_POST['catenum'] != '' || isset($_GET['catenum']) ? isset($_POST['catenum']) ? $_POST['catenum'] : $_GET['catenum'] : 0;
        }
        //精确搜索 关键字
        if (isset($_POST['app_key']) && $_POST['app_key'] != '') {
            $str_keyword = $_POST['app_key'];
            $arr_keyword = preg_split('#\s+#', trim($str_keyword));
        }
        //	$catenum=isset($_POST['catenum'])&&$_POST['catenum']!=""?$_POST['catenum']:"0";
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        if (isset($_GET['order']) && $_GET['order'] == 'isInstall') {
            $this->navigations[] = array('name' => $this->ld['app_mine'],'url' => '/applications/?order=isInstall');
        } else {
            $this->navigations[] = array('name' => $this->ld['plugins'],'url' => '/applications/');
        }

//		$did=$this->Domain->find('first');
        $did = $this->configs['shop_domain_id'];
        $conditions = '';
        if (isset($this->configs['use_app']) && $this->configs['use_app'] != '1' && (!isset($_SESSION['use_app']) || $_SESSION['use_app'] != 1)) {
            $order = 'isInstall';
        }
        if ($order == 'isInstall') {
            $conditions['order'] = 'Application.created DESC';
        } else {
            $conditions['order'] = 'Application.'.$order.' DESC';
        }

        if ($catenum != 0) {
            $conditions['conditions']['Application.groupby'] = $catenum;
        } else {
            $conditions['conditions']['Application.groupby <>'] = '';
        }

        if ($order == 'isInstall') {
            $_SESSION['order'] = 'created';
        } else {
            $_SESSION['order'] = $order;
        }
        $_SESSION['url_order'] = $order;
        $_SESSION['catenum'] = $catenum;
        $this->set('order', $order);
        $this->set('catenum', $catenum);
        //获取官网当前分类下的所有的app信息
//		$count_info=$this->Application->get_all_app();
        $count_info = $this->Application->find('all', array('fields' => 'Application.groupby,Application.code,Application.status,Application.end_time'));
        //获取官网记录安装的优惠券
        $have_install_app_codes = $this->Application->find('all', array('fields' => 'Application.groupby,Application.code,Application.status,Application.end_time'));
//		$have_install_app_codes = $this->Application->get_install_app($did['Domain']['id']);
//		$have_install_app_codes = $this->Application->get_install_app($did);
        $this->set('have_install_app_codes', $have_install_app_codes);
        //根据当前分类来过滤获取的信息
        $all_app = $count_info;
        if (isset($catenum) && $catenum != 0) {
            foreach ($all_app as $k => $v) {
                if ($v['Application']['groupby'] != $catenum) {
                    unset($all_app[$k]);
                }
            }
        }

        //获取网店所有安装过的app信息
        $install_apps['Applications'] = $this->Application->find('all', array('fields' => 'Application.groupby,Application.code,Application.status,Application.end_time'));
        //获取所有的安装过的codes的集合
        $all_install_app_codes = $this->Application->getallcodes();
        if (isset($install_apps['Applications']) && !empty($install_apps['Applications'])) {
            foreach ($install_apps['Applications'] as $v) {
                $install_code[] = $v['Application']['code'];
                $install_infos[$v['Application']['code']]['status'] = $v['Application']['status'];
                $install_infos[$v['Application']['code']]['end_time'] = $v['Application']['end_time'];
                if (isset($v['ApplicationConfig']) && count($v['ApplicationConfig']) > 0) {
                    $install_infos[$v['Application']['code']]['count'] = 1;
                } else {
                    $install_infos[$v['Application']['code']]['count'] = 0;
                }
            }

            $this->set('install_code', $install_code);
            $this->set('install_infos', $install_infos);
        }
        foreach ($all_app as $k => $v) {
            if ($id != 0) {
                if ($v['Application']['id'] != $id) {
                    unset($all_app[$k]);
                    continue;
                }
            }
            foreach ($v['ApplicationI18n'] as $vv) {
                if ($vv['locale'] == $this->backend_locale) {
                    $all_app[$k]['ApplicationI18n'] = $vv;
                }
            }
        }
        foreach ($count_info as $k => $v) {
            foreach ($v['ApplicationI18n'] as $vv) {
                if ($vv['locale'] == $this->backend_locale) {
                    $count_info[$k]['ApplicationI18n'] = $vv;
                }
            }
        }
        //取出 id 对应 name的数组 id对应的img path
        $app_price_arr = array();
        foreach ($count_info as $v) {
            $app_name[$v['Application']['id']] = isset($v['ApplicationI18n']['name']) ? $v['ApplicationI18n']['name'] : '';
        //	$img_path[$v['Application']['id']]=$v['Application']['icon'];
        //	$app_price_arr[$v['Application']['id']]=$v['Application']['price'];
        }
        $this->set('app_price_arr', $app_price_arr);
        $this->set('app_name', $app_name);
        //$this->set('img_path',$img_path);
        // 如果是我的应用单独统计总数
        if ($order == 'isInstall') {
            foreach ($all_app as $k => $v) {
                if (!in_array($v['Application']['code'], $all_install_app_codes)) {
                    unset($all_app[$k]);
                }
            }
        }
        //关键字过滤
        if (isset($arr_keyword)) {
            foreach ($all_app as $k => $v) {
                $i = 0;
                foreach ($arr_keyword as $vv) {
                    //echo $vv.'------------'.$v['ApplicationI18n']['name'].'<br>';
                    if (count(@spliti($vv, $v['ApplicationI18n']['name'])) > 1 || count(@spliti($vv, $v['ApplicationI18n']['tags'])) > 1) {
                        $i = 1;
                    }
                }
                if ($i == 0) {
                    unset($all_app[$k]);
                }
            }
            $this->set('app_key', $str_keyword);
        }
        if ($order == 'isInstall') {
            $conditions['conditions']['Application.code'] = $all_install_app_codes;
        }
        $total = count($all_app);//统计总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : 20;
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'applications','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total);
        $this->Pagination->init($conditions['conditions'], $parameters, $options);
        $conditions['limit'] = $rownum;
        $conditions['page'] = $page;
        //$all=$this->Application->get_all_app($conditions);
        $all = $this->Application->find('all', array('fields' => 'Application.groupby,Application.code,Application.status,Application.end_time'));
        //根据当前语言选择显示的数据
        //$all_app_infos=$all['order'];
        $all_app_infos = $all;
        if (!empty($all_app_infos)) {
            foreach ($all_app_infos as $k => $v) {
                if ($id != 0) {
                    if ($v['Application']['id'] != $id) {
                        unset($all_app_infos[$k]);
                        continue;
                    }
                }
                foreach ($v['ApplicationI18n'] as $vv) {
                    if ($vv['locale'] == $this->backend_locale) {
                        $all_app_infos[$k]['ApplicationI18n'] = $vv;
                    }
                }
            }
        }

        //获取所有安装的app的id的集合
        $install_app_ids = $this->Application->getallids();
        $use_app_ids = $this->Application->getuseids();
        //取出官网的应用依赖关系
        if (isset($count_info)) {
            $depend_ids = array();
            $depend_ids['needbuy'] = array();
            $depend_ids['needbuy_app_list'] = array();
            $depend_ids['has_stop_app'] = array();
            foreach ($count_info as $v) {
                if (!empty($v['ApplicationDependent'])) {
                    foreach ($v['ApplicationDependent'] as $kk => $vv) {
                        if (in_array($vv['dependent_app_id'], $install_app_ids)) {
                            $depend_ids[$v['Application']['id']][$kk]['id'] = $vv['dependent_app_id'];
                            //如果是启用的
                            if (in_array($vv['dependent_app_id'], $use_app_ids)) {
                                $depend_ids[$v['Application']['id']][$kk]['status'] = 1;
                            } else {
                                $depend_ids[$v['Application']['id']][$kk]['status'] = 0;
                                $depend_ids['has_stop_app'][] = $v['Application']['id'];
                            }
                        } else {
                            $depend_ids[$v['Application']['id']][$kk]['id'] = $vv['dependent_app_id'];
                            $depend_ids[$v['Application']['id']][$kk]['status'] = 0;
                            //判断是否有关联的应用没有购买
                            //$depend_ids[$v['Application']['id']]['status']=0;
                            $depend_ids['needbuy'][] = $v['Application']['id'];
                            $depend_ids['needbuy_app_list'][] = $vv['dependent_app_id'];
                        }
                    }
                }
            }
            $this->set('depend_ids', $depend_ids);
        }
        //从官网获取所有安装过的app且是正在试用的code的集合
//		$try_install_codes=$this->get_try_install_app($did['Domain']['id']);
//		$try_install_codes=$this->get_try_install_app($did);
//		if(isset($try_install_codes)&&$try_install_codes!=""){
//			$this->set('try_install_codes',$try_install_codes);
//		}

        //取出当前语言的类别

//		foreach($all['cate'] as $k=>$v){
//			$all['cate'][$k]=$v[$this->backend_locale];
//		}
//
//
//		//所有的类别
//		$this->set('cate',$all['cate']);
        $this->set('cate', $this->backend_locale);
        if ($order == 'isInstall' && false) {
            foreach ($all_install_app_codes as $k => $v) {
                $time_order[$v] = $k;
            }
            $last_order_all_app = array();
            if (isset($all_app) && !empty($all_app)) {
                foreach ($all_app as $v) {
                    $order_all_app[$time_order[$v['Application']['code']]] = $v;
                }
                $count_app = count($time_order);
                for ($i = 0;$i < $count_app;++$i) {
                    if (isset($order_all_app[$i])) {
                        $last_order_all_app[$i] = $order_all_app[$i];
                    }
                }
            }
            $all_app_infos = $last_order_all_app;
        }
        if (isset($arr_keyword) || $id != 0) {
            $all_app_infos = $all_app;
        }
        //pr($all_app_infos);die;
        $this->set('all_app_infos', $all_app_infos);
        $this->set('title_for_layout', $this->ld['plugins'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }
    //去官网查询所有的安装的正在试用app信息
    public function get_try_install_app($did)
    {
        $soap_api = 'http://'.IOCOMGT.'/soap/webservices/wsdl';
        $client = new nusoap_client($soap_api, true);
        $client->soap_defencoding = 'utf-8';
        $client->decode_utf8 = false;
        $client->xml_encoding = 'utf-8';
        $all_install_apps = $client->call('get_try_install_app', array($did));
        $install_app_code = array();
        if (isset($all_install_apps['install_app_info']) && $all_install_apps['install_app_info'] != '') {
            $all_install_apps = $all_install_apps['install_app_info']['install_app_info'];
            if (sizeof($all_install_apps) == 1) {
                $install_app_code[] = $all_install_apps['app_code'];
            } else {
                foreach ($all_install_apps as $v) {
                    $install_app_code[] = $v['app_code'];
                }
            }
        }
        //返回的是 当前网店所有试用的的app的code的集合
        return $install_app_code;
    }

    /**
     *编辑商品信息 新增/编辑.
     *
     *@param int $id 输入商品ID
     */
    public function view($id = '')
    {
        $this->operator_privilege('configvalues_view');//按商品设置权限判断
        $this->menu_path = array('root' => '/system/','sub' => '/applications/?order=isInstall');
        $this->set('title_for_layout', $this->ld['plugin_setting'].' - '.$this->configs['shop_name']);

        $app_info = $this->Application->find('first', array('conditions' => array('Application.id' => $id)));
        $transactions_info = $this->Cronjob->find('first', array('conditions' => array('Cronjob.task_name' => 'APP-IOCO-ORDERTOOl-CRONJOB')));
        if (!empty($transactions_info)) {
            $this->set('transactions_info', $transactions_info);
        }

        $taobaoproducts_info = $this->Cronjob->find('first', array('conditions' => array('Cronjob.task_name' => 'APP-IOCO-SHOPPRODUCT-CRONJOB')));
        if (!empty($taobaoproducts_info)) {
            $this->set('taobaoproducts_info', $taobaoproducts_info);
        }
        //pr($taobaoproducts_info);die;
        //去官网匹配最新的应用参数信息
        $languages = $this->Language->find('all');
    //	$compare_infos=$this->Application->get_all_app();
    $compare_infos = $this->Application->find('all', array('fields' => 'Application.orderby,Application.groupby,Application.code,Application.status,Application.end_time'));
    //	pr($compare_infos);die;
        //pr($languages);
        foreach ($compare_infos as $k => $v) {
            if ($v['Application']['code'] == $app_info['Application']['code']) {
                unset($v['ApplicationI18n']);
                if (empty($v['ApplicationDependent'])) {
                    unset($v['ApplicationDependent']);
                }
                $new_apps['Applications'] = $v;
                if (isset($new_apps['Applications']['ApplicationConfig']) && !empty($new_apps['Applications']['ApplicationConfig'])) {
                    foreach ($languages as $lv) {
                        $all_locale[] = $lv['Language']['locale'];
                    }
                    foreach ($new_apps['Applications']['ApplicationConfigI18n'] as $ck => $cv) {
                        if (!in_array($cv['locale'], $all_locale)) {
                            unset($new_apps['Applications']['ApplicationConfigI18n'][$ck]);
                        }
                    }
                }
            }
        }

        $app_info['Application']['groupby'] = $new_apps['Applications']['Application']['groupby'];
        $app_info['Application']['orderby'] = $new_apps['Applications']['Application']['orderby'];
        $app_info['ApplicationConfig'] = $new_apps['Applications']['ApplicationConfig'];

        //pr($app_info);
        //pr($new_apps['Applications']['ApplicationConfig']);
        foreach ($new_apps['Applications']['ApplicationConfigI18n'] as $k => $v) {
            foreach ($app_info['ApplicationConfigI18n'] as $vv) {
                if ($v['app_config_id'] == $vv['app_config_id'] && $v['locale'] == $vv['locale']) {
                    $new_apps['Applications']['ApplicationConfigI18n'][$k]['value'] = $vv['value'];
                }
            }
        }

        $app_info['ApplicationConfigI18n'] = $new_apps['Applications']['ApplicationConfigI18n'];
        $this->ApplicationConfig->deleteAll(array('ApplicationConfig.app_id' => $id));
        $this->ApplicationConfigI18n->deleteAll(array('ApplicationConfigI18n.app_id' => $id));
        $this->Application->saveAll($app_info);
        $app_config_infos = $this->ApplicationConfig->find('all', array('conditions' => array('ApplicationConfig.app_id' => $id), 'order' => 'ApplicationConfig.orderby asc'));
       // pr($app_config_infos);die;
        if ($app_info['Application']['code'] == 'APP-WEIBO') {
            $wop = $this->SynchroOperator->find('first', array('conditions' => array('SynchroOperator.type' => 'weibo_op')));
            $this->to_install_table('weibo');
            $this->clear_cache_files();
            $this->set('wop', $wop);
        }
        if ($app_info['Application']['code'] == 'APP-WWW') {
            $this->set('vid', $id);
//			$did_foo=$this->Domain->find('first',array('fields'=>array('Domain.id')));
//			$did=$did_foo['Domain']['id'];
            $did = $this->configs['shop_domain_id'];
            $foo = $this->soap_to_web_status($did);
            $msg_foo = array();
            $msg_foo['code'] = $foo['code'];//echo $foo['ip'];
            if ($foo['code'] != '402' && $foo['code'] != '404') {
                if ($foo['code'] == '0') {
                    $msg_foo['msg'] = '已审核通过绑定';
                } elseif ($foo['code'] == '3') {
                    $msg_foo['msg'] = '未审核通过绑定';
                } elseif ($foo['code'] == '1') {
                    $msg_foo['msg'] = '审核中，请把DNS A记录指向此ip:'.$foo['ip'];
                }
                $this->set('msg_foo', $msg_foo);
            }
            if (!empty($foo['www'])) {
                $www_foo = $this->ApplicationConfig->find('first', array('conditions' => array('ApplicationConfig.code' => 'APP-WWW-WEB'), 'fields' => array('ApplicationConfig.id')));
                $this->ApplicationConfigI18n->updateAll(array('ApplicationConfigI18n.value' => "'".$foo['www']."'"), array('ApplicationConfigI18n.app_config_id' => $www_foo['ApplicationConfig']['id']));
            }
            if (!empty($foo['icp'])) {
                $www_foo = $this->ApplicationConfig->find('first', array('conditions' => array('ApplicationConfig.code' => 'APP-WWW-ICP'), 'fields' => array('ApplicationConfig.id')));
                $this->ApplicationConfigI18n->updateAll(array('ApplicationConfigI18n.value' => "'".$foo['icp']."'"), array('ApplicationConfigI18n.app_config_id' => $www_foo['ApplicationConfig']['id']));
            }
        }
        //var_dump($app_info['Application']['code']);die();
        $code = explode('-', $app_info['Application']['code']);
        //var_dump($app_info['Application']['code']);die();
        if ($code[1] == 'LANG') {
            //var_dump();
            $this->install_lan_app_i18ns($code[2]);
            $_SESSION['app_lan'] = 'k';

            if ($this->is_language_install($code[2])) {
                //echo $code[2];
                $this->install_language($code[2]);
            } else {
                if ($this->count_app_lan() <= 1) {
                    $this->Language->updateAll(array('Language.is_default' => 1, 'Language.front' => 1, 'Language.backend' => 1), array('Language.locale' => strtolower($code[2])));
                }
                $xlan = $this->Language->find('first', array('conditions' => array('Language.locale' => strtolower($code[2]))));
                $this->redirect('/languages/view/'.$xlan['Language']['id']);
            }
        } elseif ($code[1] == 'THM') {
            //$_SESSION['app_lan']='k';
            if ($this->is_set_thm($code[2])) {
                $this->to_install_thm($code[2]);
                $this->redirect('/themes/');
            } else {
                $this->redirect('/themes/');
            }
        } elseif ($code[1] == 'PAY') {
            if ($this->is_set_pay($code[2])) {
                $this->to_install_pays($code[2]);
            } else {
                $xpay = $this->Payment->find('first', array('conditions' => array('Payment.code' => strtolower($code[2]))));
                $this->redirect('/payments/edit/'.$xpay['Payment']['id']);
            }
        } elseif ($code[1] == 'BALANCE') {
            if ($this->is_set_pay('account_pay')) {
                $this->to_install_pays('account_pay');
            } else {
                $xpay = $this->Payment->find('first', array('conditions' => array('Payment.code' => strtolower('account_pay'))));
                $this->redirect('/payments/edit/'.$xpay['Payment']['id']);
            }
        } elseif ($code[1] == 'DSP') {
            if (constant('Product') == 'AllInOne') {
                $this->loadModel('Shipping');
                $xsp = $this->Shipping->find('first', array('conditions' => array('Shipping.code' => strtolower($code[2]))));
                $this->redirect('/shippingments/edit/'.$xsp['Shipping']['id']);
            }
        }
        $all_app_info = $this->Application->find('all', array('fields' => 'Application.groupby,Application.code,Application.status,Application.end_time'));
        foreach ($all_app_info as $v) {
            if ($v['Application']['code'] == $app_info['Application']['code']) {
                foreach ($v['ApplicationI18n'] as $vv) {
                    if ($vv['locale'] == $this->locale) {
                        $app_name = $vv['name'];
                    }
                }
            }
        }
        $Resource_info = $this->Resource->getformatcode(array('appvalues'), $this->backend_locale);
        $this->set('ApplicationConfig_group_code', $Resource_info['appvalues']);

        $AppConfigs = array();
        $group_code2 = array();
        foreach ($Resource_info['appvalues'] as $k => $v) {
            $group_code2[] = $k;
        }
        $group_code2[] = 'product';
        $group_code2[] = 'user';
        $group_code2[] = 'shopcart';
        $ApplicationConfig = $this->ApplicationConfig->find('all', array('conditions' => array('ApplicationConfig.group_code' => $group_code2, 'ApplicationConfig.app_id' => $id), 'order' => 'ApplicationConfig.orderby'));
        //pr($ApplicationConfig);
        foreach ($ApplicationConfig as $k => $v) {
            foreach ($v['ApplicationConfigI18n'] as $vv) {
                if ($vv['locale'] == $this->backend_locale) {
                    $ApplicationConfig[$k]['ApplicationConfig']['name'] = $vv['description'];
                    if (isset($vv['remark']) && $vv['remark'] != '') {
                        $ApplicationConfig[$k]['ApplicationConfig'][$vv['locale']]['remark'] = $vv['remark'];
                    }
                    if (isset($vv['value']) && $vv['value'] != '' && $v['ApplicationConfig']['type'] == 'read_only') {
                        //获取只读属性url（仅限code不能直接转化为url的应用，需额外设参数）
                        $url = $vv['value'];
                    }
                }
                $ApplicationConfig[$k]['ApplicationConfig'][$vv['locale']]['id'] = $vv['id'];
                $ApplicationConfig[$k]['ApplicationConfig'][$vv['locale']]['value'] = $vv['value'];
            }
            $AppConfigs[$ApplicationConfig[$k]['ApplicationConfig']['group_code']][] = $ApplicationConfig[$k];
        }
        $this->set('app_info', $app_info);
        //pr($AppConfigs);//die;
        $this->set('AppConfigs', $AppConfigs);
        $this->navigations[] = array('name' => $app_name,'url' => '');
        $this->navigations[] = array('name' => $this->ld['plugin_setting'],'url' => '/applications/'.$app_info['Application']['id']);
        $code = explode('-', $app_info['Application']['code']);
        if (!isset($url)) {
            $url = '/'.strtolower($code[1]).'/';
        }

        if ($this->RequestHandler->isPost()) {
            //pr($this->data);die;
           //pr($app_info);die;
            foreach ($this->data as $k => $v) {
                if ($k == 'APP-MOBILE-WAP-LANG') {
                    echo $app_info['Application']['id'];
                    $s = isset($v['value']) ? $v['value'] : '';
                    $s2 = $this->ApplicationConfig->find('first', array('conditions' => array('ApplicationConfig.code' => 'APP-MOBILE-WAP-LANG'), 'fields' => array('ApplicationConfig.id')));
                    $this->ApplicationConfigI18n->updateAll(array('value' => "'".$s."'"), array('app_config_id' => $s2['ApplicationConfig']['id']));
                    continue;
                }

                if (isset($v['code']) && $v['code'] == 'CRONJOB_SENDEMAIL') {
                    if ($v['value'] == '1') {
                        $cronjobs = array('task_name' => 'APP-IOCO-ORDERTOOl-CRONJOB',
                                                'status' => '1',
                                                'next_time' => $this->data['Cronjob']['next_time'],
                                                'interval_time' => $this->data['Cronjob']['interval_time'],
                                                'param01' => $this->data['Cronjob']['param01'],
                                                'app_code' => $app_info['Application']['code'],
                                                'task_code' => 'IocoOrderTool',
                                );
                        if (!empty($transactions_info)) {
                            $this->Cronjob->updateAll($cronjobs, array('task_name' => 'APP-IOCO-ORDERTOOl-CRONJOB'));
                        } else {
                            $this->Cronjob->saveAll($cronjobs);
                        }
                    } else {
                        if (!empty($transactions_info)) {
                            $this->Cronjob->updateAll(array('status' => '0'), array('task_name' => 'APP-IOCO-ORDERTOOl-CRONJOB'));
                        }
                    }
                }
                if (isset($v['code']) && $v['code'] == 'CRONJOB_TAOBAOSENDMAIL') {
                    if ($v['value'] == '1') {
                        $taobaocronjobs = array('task_name' => 'APP-IOCO-SHOPPRODUCT-CRONJOB',
                                                'status' => '1',
                                                'next_time' => $this->data['taobaoCronjob']['next_time'],
                                                'interval_time' => $this->data['taobaoCronjob']['interval_time'],
                                                'param01' => $this->data['taobaoCronjob']['param01'],
                                                'app_code' => $app_info['Application']['code'],
                                                'task_code' => 'TaobaoItemTool',
                                );
                            //pr($taobaocronjobs);die;
                            if (!empty($taobaoproducts_info)) {
                                $this->Cronjob->updateAll($taobaocronjobs, array('task_name' => 'APP-IOCO-SHOPPRODUCT-CRONJOB'));
                            } else {
                                $this->Cronjob->saveAll($taobaocronjobs);
                            }
                    } else {
                        if (!empty($taobaoproducts_info)) {
                            $this->Cronjob->updateAll(array('status' => '0'), array('task_name' => 'APP-IOCO-SHOPPRODUCT-CRONJOB'));
                        }
                    }
                }

                if (isset($v['value']) && is_array($v['value'])) {
                    if (isset($v['value']['tmp_name']) && !empty($v['value']['tmp_name'])) {
                        //							$dir_root = dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME'])))."/data";
//							if(!is_dir($dir_root."/files/")){
//								mkdir($dir_root."/files/", 0777);
//								@chmod($dir_root."/files/", 0777);
//							}
//							$file_name=strtolower('.'.pathinfo($v['value']['name'], PATHINFO_EXTENSION));
//							move_uploaded_file($v['value']['tmp_name'],dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME'])))."/data/files/".date("YmdHis").$file_name);
//							@chmod(dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME'])))."/data/files/".date("YmdHis").$file_name, 0777);
//							$file_path=dirname(dirname(dirname($_SERVER['SCRIPT_FILENAME'])))."/data/files/".date("YmdHis").$file_name;
//							$v['value'] = "http://".$_SERVER['HTTP_HOST']."/files/".date("YmdHis").$file_name;
                            $imgname_arr = explode('.', $v['value']['name']);
                        if ($imgname_arr[1] == 'jpg' || $imgname_arr[1] == 'gif' || $imgname_arr[1] == 'png' || $imgname_arr[1] == 'bmp' || $imgname_arr[1] == 'jpeg') {
                            $img_thumb_name = md5($imgname_arr[0].time());
                            $image_name = $img_thumb_name.'.'.$imgname_arr[1];
                            $imgaddr = WWW_ROOT.'img/files/';
                            $this->mkdirs($imgaddr);
                            move_uploaded_file($v['value']['tmp_name'], $imgaddr.$image_name);
                            if (isset($user_list) && !empty($user_list['User']['img01'])) {
                                if (file_exists(WWW_ROOT.$user_list['User']['img01'])) {
                                    unlink(WWW_ROOT.$user_list['User']['img01']);
                                }
                            }
                            $v['value'] = '/media/files/'.$image_name;
                        }
                    } elseif (isset($v['value']['tmp_name']) && empty($v['value']['tmp_name'])) {
                        continue;
                    } else {
                        $v['value'] = implode(';', $v['value']);
                    }
                }
                $value = isset($v['value']) ? $v['value'] : '';
                if (isset($v['warn_code']) && $v['warn_code'] == 'APP-WARN-QUANTITY-PRODUCTS-NUM') {
                    if ($value != '') {
                        $this->loadModel('Product');
                        $this->Product->updateAll(array('Product.warn_quantity' => "'".$value."'"), array('Product.status' => 1, 'Product.warn_style' => 0));
                    }
                    unset($v['warn_code']);
                }
                $change_value = '';
                $change_value = $this->ApplicationConfigI18n->find('first', array('conditions' => array('ApplicationConfigI18n.id' => $k)));

                $change_value['ApplicationConfigI18n']['value'] = $value;

                    //pr($change_value);

                    $a = $this->ApplicationConfigI18n->save($change_value);
                if (isset($v['type']) && isset($v['type'])) {
                    //判断是否有导航的属性
                        $info = $this->ApplicationConfig->find('first', array('conditions' => array('ApplicationConfig.code' => $v['code'])));
                    $this->ApplicationConfigI18n->updateAll(array('value' => "'".$value."'"), array('app_config_id' => $info['ApplicationConfig']['id']));

                    $c = $this->ApplicationConfigI18n->find('first', array('conditions' => array('ApplicationConfigI18n.id' => $k)));
                    //	pr($c);


                            if ($v['type'] == 'nav_select') {
                                $info = $this->ApplicationConfig->find('first', array('conditions' => array('ApplicationConfig.code' => $v['code'])));
                            //导航处理
                            //$this->all_apps['Applications']=$this->Application->get_all_app();
                    $this->all_apps['Applications'] = $this->Application->find('all', array('fields' => 'Application.groupby,Application.code,Application.status,Application.end_time'));
                                foreach ($this->front_locales as $lv) {
                                    $all_locale[] = $lv['Language']['locale'];
                                }
                                foreach ($this->all_apps['Applications'] as $vv) {
                                    foreach ($vv['ApplicationConfig'] as $code_info) {
                                        if ($code_info['code'] == $v['code']) {
                                            foreach ($vv['ApplicationI18n'] as $vvv) {
                                                if (in_array($vvv['locale'], $all_locale)) {
                                                    $name[$vvv['locale']] = $vvv['name'];
                                                }
                                            }
                                        }
                                    }
                                }

                                $this->change_nav($url, $info['ApplicationConfigI18n'][0]['value'], $name, $all_locale);//第一个参数$url,本为$code(由code改写的路径)
                            }
                }
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_set_application'].':'.$app_name, $this->admin['id']);
            }
//		    die();
            $this->redirect('/applications/');
        }
    }
    //导航表的插入 和 修改
    public function change_nav($code, $type, $name, $all_locale)
    {
        //判断是第一次插入还是对已经存在数据进行修改
        //$url='/'.strtolower($code[1]).'/';
        $url = $code;
        $isHave = $this->Navigation->find('first', array('conditions' => array('NavigationI18n.url' => $url)));

        if (!empty($isHave)) {
            if ($type == '0') {
                $this->Navigation->deleteAll(array('NavigationI18n.url' => $url));
                $this->Navigation->deleteAll(array('Navigation.id' => $isHave['Navigation']['id']));
            } else {
                $isHave['Navigation']['type'] = $type;
                $this->Navigation->save($isHave);
            }
        } elseif (empty($isHave) && $type != '0') {
            $nav_info['type'] = $type;
            $nav_info['status'] = 1;
            $nav_info['parent_id'] = 0;
            $this->Navigation->save($nav_info);
            $id = $this->Navigation->id;
            foreach ($all_locale as $k => $v) {
                $navi18n_info[$k]['navigation_id'] = $id;
                $navi18n_info[$k]['locale'] = $v;
                $navi18n_info[$k]['url'] = $url;
                $navi18n_info[$k]['name'] = $name[$v];
            }
            $this->NavigationI18n->saveAll($navi18n_info);
        }
    }
    //应用停用和使用时修改 导航的状态
    public function change_nav_status($url, $status)
    {
        $nav_info = $this->Navigation->find('first', array('conditions' => array('NavigationI18n.url' => $url)));
        $nav_info['Navigation']['status'] = $status;
        $this->Navigation->saveAll($nav_info);
    }
    //应用的使用
    public function app_use($id, $app_name)
    {
        $app_info = $this->Application->find('first', array('conditions' => array('Application.id' => $id)));
        //判断属性里面是否有导航属性 如果有将导航的状态也改掉
        foreach ($app_info['ApplicationConfig'] as $v) {
            if ($v['type'] = 'nav_select') {
                $code = explode('-', $app_info['Application']['code']);
                $url = '/'.strtolower($code[1]).'/';
                $this->change_nav_status($url, 1);
            }
        }
        $app_info['Application']['status'] = 1;
        $this->Application->save($app_info);
//		$did=$this->Domain->find('first');
        $status = array(
                    'domain_id' => $this->configs['shop_domain_id'],//$did['Domain']['id']
                    'app_code' => $app_info['Application']['code'],
                    'status' => 1,
                    );
        $this->change_status($status);

//		//操作员日志
//		$all_app_info=$this->Application->get_all_app();
//		foreach($all_app_info as $v){
//			if($v['Application']['code']==$app_info['Application']['code']){
//				foreach($v['ApplicationI18n'] as $vv){
//					if($vv['locale']==$this->locale){
//						$app_name=$vv['name'];
//					}
//				}
//			}
//		}
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_enable_application'].':'.$app_name, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['url'] = '/admin/applications/?order='.$_SESSION['url_order'].'&catenum='.$_SESSION['catenum'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //应用的停用
    public function app_stop($id, $app_name)
    {
        $app_info = $this->Application->find('first', array('conditions' => array('Application.id' => $id)));
        $code = explode('-', $app_info['Application']['code']);
        if ($code[1] == 'LANG') {
            $this->stop_language(strtolower($code[2]));
        } elseif ($code[1] == 'THM') {
            $this->stop_thm($code[2]);
        } elseif ($code[1] == 'PAY') {
            $this->stop_pays($code[2]);
        } elseif ($code[1] == 'DSP') {
            $this->stop_dsp($code[2]);
        }
        //判断属性里面是否有导航属性 如果有将导航的状态也改掉
        foreach ($app_info['ApplicationConfig'] as $v) {
            if ($v['type'] = 'nav_select') {
                $code = explode('-', $app_info['Application']['code']);
                $url = '/'.strtolower($code[1]).'/';
                $this->change_nav_status($url, 0);
            }
        }
        $app_info['Application']['status'] = 0;
        $this->Application->save($app_info);
//		$did=$this->Domain->find('first');
        $status = array(
                    'domain_id' => $this->configs['shop_domain_id'],//$did['Domain']['id']
                    'app_code' => $app_info['Application']['code'],
                    'status' => 0,
                    );
        $this->change_status($status);

//		//操作员日志
//		$all_app_info=$this->Application->get_all_app();
//		foreach($all_app_info as $v){
//			if($v['Application']['code']==$app_info['Application']['code']){
//				foreach($v['ApplicationI18n'] as $vv){
//					if($vv['locale']==$this->locale){
//						$app_name=$vv['name'];
//					}
//				}
//			}
//		}
if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
    $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_disable_application'].':'.$app_name, $this->admin['id']);
}
        $result['flag'] = 1;
        $result['url'] = '/admin/applications/?order='.$_SESSION['url_order'].'&catenum='.$_SESSION['catenum'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    //应用的停用
    public function app_remove($id, $app_name)
    {
        $this->Application->deleteAll(array('Application.id' => $id));
        $this->ApplicationConfig->deleteAll(array('ApplicationConfig.app_id' => $id));
        $this->ApplicationConfigI18n->deleteAll(array('ApplicationConfigI18n.app_id' => $id));
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_disable_application'].':'.$app_name, $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['url'] = '/admin/applications/?order='.$_SESSION['url_order'].'&catenum='.$_SESSION['catenum'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //应用安装
    public function install($action, $id, $num = 1)
    {
        $app_status = 0;
        $free_status = 0;
    //	$this->all_apps['Applications']=$this->Application->get_all_app();
        $this->all_apps['Applications'] = $this->Application->find('all', array('fields' => 'Application.groupby,Application.code,Application.status,Application.end_time'));
        $install_app_ids = $this->Application->getallids();
        //取出官网的应用依赖关系
        foreach ($this->all_apps['Applications'] as $v) {
            if (!empty($v['ApplicationDependent'])) {
                foreach ($v['ApplicationDependent'] as $kk => $vv) {
                    if (in_array($vv['dependent_app_id'], $install_app_ids)) {
                        continue;
                    }
                    $depend_ids[$v['Application']['id']][$kk] = $vv['dependent_app_id'];
                }
            }
        }
//		$did=$this->Domain->find('first');
        $did = $this->configs['shop_domain_id'];
        foreach ($this->all_apps['Applications'] as $kk => $v) {
            if ($v['Application']['id'] == $id) {
                $k = $kk;
            }
        }
        $code = explode('-', $this->all_apps['Applications'][$k]['Application']['code']);
        if ($code[1] == 'THM') {
            $base_thm = $this->Template->find('first', array('conditions' => array('Template.name' => strtolower($code[2]))));
            if (!isset($base_thm['Template']['name']) || empty($base_thm['Template']['name'])) {
                $result['flag'] = '-1';
                $result['message'] = '该模板应用信息不全，请联系服务商!';
                    //$result['message']=	$result[];
                    Configure::write('debug', 0);
                $this->layout = 'ajax';
                die(json_encode($result));
            }
        }
        if ($action == 'free' && $this->all_apps['Applications'][$k]['Application']['price'] == 0) {
            //免费安装
            $ends = date('0-0-0 0:0:0');
//			$result=$this->buy_app($did['Domain']['id'],$this->all_apps['Applications'][$k]['Application']['id']);
            $result = $this->buy_app($did, $this->all_apps['Applications'][$k]['Application']['id']);
            //pr($result);
        } elseif ($action == 'try' && $this->all_apps['Applications'][$k]['Application']['freedays'] > 0) {
            //试用安装
            $app_status = 1;
            //判断是否试用过  安装过就没有试用的资格
            //$result=$this->get_try_install_app($did['Domain']['id']);
                    //从官网获取所有安装过的app且是正在试用的code的集合
//			$try_install_codes=$this->get_try_install_app($did['Domain']['id']);
//			$try_install_codes=$this->get_try_install_app($did);
//			if(!in_array($this->all_apps['Applications'][$k]['Application']['code'],$try_install_codes)){//没有试用过
//				$free_status=1;
//				//使用的单独处理
//				$now=date('Y-m-d ');
//				$ends=date('Y-m-d ', strtotime("+".$this->all_apps['Applications'][$k]['Application']['freedays']."days"));
//				$try_info['remark']=$this->all_apps['Applications'][$k]['Application']['code'].'-1-'.$this->all_apps['Applications'][$k]['Application']['freedays'];
//				$try_info['code']=$this->all_apps['Applications'][$k]['Application']['code'];
//				//$try_info['freedays']=$this->all_apps['Applications'][$k]['Application']['freedays'];
//				$domain_service=array(
//									'domain_id'=>$did,//$did['Domain']['id']
//									'rank'=>1,
//									'remark'=>json_encode($try_info),
//									'status'=>1,
//									'start_time'=>$now,
//									'end_time'=>$ends,
//				);
//				$result=$this->try_install($domain_service);
//				//pr($result);exit();
//
//			}else{//已经试用过
//				$this->redirect('/applications/');
//			}
        } elseif ($action == 'buy' && $this->all_apps['Applications'][$k]['Application']['price'] > 0) {
            //付钱安装
            $app_status = 1;
            $need_install_app_ids = array();

            $need_install_app_ids[] = $this->all_apps['Applications'][$k]['Application']['id'];
            if (isset($depend_ids[$this->all_apps['Applications'][$k]['Application']['id']])) {
                foreach ($depend_ids[$this->all_apps['Applications'][$k]['Application']['id']] as $k => $v) {
                    $need_install_app_ids[] = $v;
                }
            }

            $json_need_install_app_ids = json_encode($need_install_app_ids);
        //	$result=$this->buy_app($did['Domain']['id'],$json_need_install_app_ids,$num);
            $result = $this->buy_app($did, $json_need_install_app_ids, $num);

            if ($result['code'] == '1') {
                //钱够的情况 切付款成功
                //continue;
                //$end_times=json_decode($result['end_time'],true);
            } elseif ($result['code'] == '403') {
                //不够的到官网的充值页面
                $result['flag'] = 0;
                $result['id'] = $id;
                Configure::write('debug', 0);
                $this->layout = 'ajax';
                die(json_encode($result));
            }
        } elseif ($action == 'reinstall') {
            //重新安装
            // 判断是否可以重新
//			$have_install_app_codes = $this->Application->get_install_app($did['Domain']['id']);
            $have_install_app_codes = $this->Application->get_install_app($did);
            if (in_array($this->all_apps['Applications'][$k]['Application']['code'], $have_install_app_codes)) {
                //				$result=$this->reinstall_app($did['Domain']['id'],$id);
                $result = $this->reinstall_app($did, $id);
                if ($result['code'] == '1') {//钱够的情况 切付款成功
                } elseif ($result['code'] == '403') {//不够的到官网的充值页面
                }
                //pr($result);exit();
                cache::clear('true', 'app');
            } else {
                //echo "aa";exit();
                $this->redirect('/applications/');
            }
        } else {
            $this->redirect('/applications/');
        }

        if ($result['code'] == 1) {

            /*if($code[1]=='TAOBAO'){
                $all_install_app_codes = $this->Application->getallcodes();
                $tb=0;
                foreach($all_install_app_codes as $m=>$n){
                    if(stristr($n,'TAOBAO')){
                        $tb++;
                    }
                }
                if($tb==1){//第一次装淘宝应用 加表
                    $this->to_install_table('taobao');
                }
            }	*/
            $code = explode('-', $this->all_apps['Applications'][$k]['Application']['code']);
            $set_arr = array('LANG','THM','PAY','BALANCE');
            $result['flag'] = 1;
            if (count($this->all_apps['Applications'][$k]['ApplicationConfig']) > 0 || in_array($code[1], $set_arr)) {
                $result['url'] = 'applications/view/'.$id;
            } elseif (isset($this->all_apps['Applications'][$k]['Application']['url']) && !empty($this->all_apps['Applications'][$k]['Application']['url'])) {
                $result['url'] = $this->all_apps['Applications'][$k]['Application']['url'];
            } else {
                $result['url'] = '/applications/?order=isInstall';
            }
            $this->clear_cache_files();
            if ($code[1] == 'DSP') {
                if (constant('Product') == 'AllInOne') {
                    $this->loadModel('Shipping');
                    if ($this->is_set_dsp($code[2])) {
                        $this->to_install_dsp($code[2]);
                        $xsp = $this->Shipping->find('first', array('conditions' => array('Shipping.code' => strtolower($code[2]))));
                        $result['url'] = 'shippingments/edit/'.$xsp['Shipping']['id'];
                    } else {
                        $xpay = $this->Shipping->find('first', array('conditions' => array('Shipping.code' => strtolower($code[2]))));
                        $result['url'] = 'shippingments/edit/'.$xpay['Shipping']['id'];
                    }
                }
            }
            //操作员日志
            $all_app_info = $this->Application->find('all', array('fields' => 'Application.groupby,Application.code,Application.status,Application.end_time'));
            foreach ($all_app_info as $v) {
                if ($v['Application']['id'] == $id) {
                    foreach ($v['ApplicationI18n'] as $vv) {
                        if ($vv['locale'] == $this->locale) {
                            $app_name = $vv['name'];
                        }
                    }
                }
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_install_application'].':'.$app_name, $this->admin['id']);
            }
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            die(json_encode($result));
        } else {
            $result['flag'] = '-1';
//			$result['message']="安装失败，请联系服务商!D".$did['Domain']['id']."-".$result['message'];
            $result['message'] = '安装失败，请联系服务商!D'.$did.'-'.$result['message'];
            //$result['message']=	$result[];
            Configure::write('debug', 0);
            $this->layout = 'ajax';
            die(json_encode($result));
        }
    }

    //去官网插入
    public function try_install($domain_service)
    {
        $soap_api = 'http://'.IOCOMGT.'/soap/webservices/wsdl';
        $client = new nusoap_client($soap_api, true);
        $client->soap_defencoding = 'utf-8';
        $client->decode_utf8 = false;
        $client->xml_encoding = 'utf-8';
        $arr = $domain_service;
        $result = $client->call('insertDomain', $arr);

        return $result;
    }

    //去官网app_domai表中插入数据
    public function app_deal($app_domain_info)
    {
        $soap_api = 'http://'.IOCOMGT.'/soap/webservices/wsdl';
        $client = new nusoap_client($soap_api, true);
        $client->soap_defencoding = 'utf-8';
        $client->decode_utf8 = false;
        $client->xml_encoding = 'utf-8';
        $arr = $app_domain_info;
        $result = $client->call('app_deal', $arr);

        return $result;
    }

    //去官网修改status的状态
    public function change_status($status)
    {
        $soap_api = 'http://'.IOCOMGT.'/soap/webservices/wsdl';
        $client = new nusoap_client($soap_api, true);
        $client->soap_defencoding = 'utf-8';
        $client->decode_utf8 = false;
        $client->xml_encoding = 'utf-8';
        $result = $client->call('change_status', array(json_encode($status)));

        return $result;
    }

    //去官网付款
    public function buy_app($did, $app_id, $num = 1)
    {
        $soap_api = 'http://'.IOCOMGT.'/soap/webservices/wsdl';
        $client = new nusoap_client($soap_api, true);
        $client->soap_defencoding = 'utf-8';
        $client->decode_utf8 = false;
        $client->xml_encoding = 'utf-8';
        $app_info = array(
                'strUserPass' => 'app',
                'domain_id' => $did,
                'app_id' => $app_id,
                'app_num' => $num,
        );
        //pr($app_info);
        $result = $client->call('buy_app', $app_info);
        //pr($result);
        return $result;
    }
    //去重新安装
    public function reinstall_app($did, $app_id)
    {
        $soap_api = 'http://'.IOCOMGT.'/soap/webservices/wsdl';
        $client = new nusoap_client($soap_api, true);
        $client->soap_defencoding = 'utf-8';
        $client->decode_utf8 = false;
        $client->xml_encoding = 'utf-8';
        $app_info = array(
                'id' => $app_id,
                'did' => $did,
        );
        $result = $client->call('reinstall_app', $app_info);

        return $result;
    }
    //test --zhou's app
    public function weibo_config()
    {
        if ($this->RequestHandler->isPost()) {
            //app_config_id
            foreach ($this->data as $k => $v) {
                $this->ApplicationConfigI18n->updateAll(array('ApplicationConfigI18n.value' => $v['value']), array('ApplicationConfigI18n.app_config_id' => $k));
            }
            $this->redirect('/applications/');
        }
    }

    public function is_language_install($lan)
    {
        $lan = strtolower($lan);
        $xlan = $this->Language->find('first', array('conditions' => array('Language.locale' => $lan)));
        //pr($xlan);die();
        if (empty($xlan)) {
            return true;
        } else {
            return false;
        }
    }

    public function install_lan_app_i18ns($locale)
    {
        $lan = strtolower($locale);
        $def = $this->Language->find('first', array('conditions' => array('is_default' => 1), 'fields' => array('Language.locale')));
        $rs = $this->ApplicationConfigI18n->find('first', array('conditions' => array('ApplicationConfigI18n.locale' => $lan)));
        if (!empty($rs)) {
            return;
        }
        $cc = $this->ApplicationConfigI18n->find('all', array('conditions' => array('ApplicationConfigI18n.locale' => $def['Language']['locale'])));
        foreach ($cc as $k1 => $v1) {
            $v1['ApplicationConfigI18n']['locale'] = $lan;
            $v1['ApplicationConfigI18n']['id'] = '';
            $this->ApplicationConfigI18n->save($v1);
        }
    }

    public function install_language($locale)
    {
        Configure::write('debug', 0);
        //$locale1=strtolower($locale1);
        $cc_code = 'APP-LANG-'.$locale;
        $tmc = $this->Application->find('first', array('conditions' => array('Application.code' => $cc_code)));
        if (empty($tmc) || $tmc['Application']['status'] == 0) {
            $msg = 'error_code';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/admin/pages/home"</script>';
            die();
        }
        $locale1 = strtolower($locale);
        $node['config'] = 'node';
        $node['use'] = true;
        $tmp = $this->Language->find('first', array('cache' => $node, 'conditions' => array('Language.locale' => $locale1)));
        //var_dump($tmp);
        $def = $this->Language->find('first', array('conditions' => array('is_default' => 1)));
        $save_file = array(
        'Language' => array(
            'locale' => $tmp['Language']['locale'],
            'name' => $tmp['Language']['name'],
            'charset' => $tmp['Language']['charset'],
            'map' => $tmp['Language']['map'],
            'img01' => $tmp['Language']['img01'],
            'img02' => $tmp['Language']['img02'],
            'front' => $tmp['Language']['front'],
            'backend' => $tmp['Language']['backend'],
            'google_translate_code' => $tmp['Language']['google_translate_code'],
            'is_default' => 0,
            ),
        );
        if ($this->count_app_lan() == 0) {
            //echo 1;
        //var_dump($save_file);
            $save_file['Language']['front'] = 1;
            $save_file['Language']['backend'] = 1;
            $save_file['Language']['is_default'] = 1;
        } else {
        }
        $this->Language->save($save_file);
        $xid = $this->Language->id;
        $tmp = $this->Language->find('first', array('conditions' => array('Language.locale' => $locale1)));
        //多语言
        $xx = $this->ConfigI18n->find('all', array('conditions' => array('ConfigI18n.locale' => strtolower($locale1))));
        $bci18n = ClassRegistry::init('ConfigI18n')->find('all', array('conditions' => array('ConfigI18n.locale' => $locale1)));
        $bci18n2 = array();
        foreach ($bci18n as $bck => $bcv) {
            $bci18n2[$bcv['ConfigI18n']['config_id']] = $bcv['ConfigI18n'];
        }

        //var_dump(empty($xx));
        if (empty($xx)) {
            //echo 1;
            $cc = $this->ConfigI18n->find('all', array('conditions' => array('ConfigI18n.locale' => $def['Language']['locale'])));
            foreach ($cc as $k1 => $v1) {
                $v1['ConfigI18n']['locale'] = $locale1;
                $v1['ConfigI18n']['name'] = isset($bci18n2[$v1['ConfigI18n']['config_id']]['name']) ? $bci18n2[$v1['ConfigI18n']['config_id']]['name'] : '';
                $v1['ConfigI18n']['options'] = isset($bci18n2[$v1['ConfigI18n']['config_id']]['options']) ? $bci18n2[$v1['ConfigI18n']['config_id']]['options'] : '';
                $v1['ConfigI18n']['description'] = isset($bci18n2[$v1['ConfigI18n']['config_id']]['description']) ? $bci18n2[$v1['ConfigI18n']['config_id']]['description'] : '';
                $v1['ConfigI18n']['id'] = '';
                $this->ConfigI18n->save($v1);
                 //var_dump();
                 $this->ConfigI18n->id = false;
//				 pr($v1);
            }
        }
//		die();
        //菜单多语言
        $yy = $this->NavigationI18n->find('all', array('conditions' => array('NavigationI18n.locale' => $locale1)));
        //var_dump($yy);
        if (empty($yy)) {
            $dd = $this->NavigationI18n->find('all', array('conditions' => array('NavigationI18n.locale' => $def['Language']['locale'])));
            foreach ($dd as $k2 => $v2) {
                $v2['NavigationI18n']['locale'] = $locale1;
                $v2['NavigationI18n']['id'] = '';
                $this->NavigationI18n->save($v2);
                $this->NavigationI18n->id = false;
            }//query("insert into svcart_navigation_i18ns SELECT '','jpn','navigation_id','name','url','description','img01','img02','created','modified' FROM svcart_navigation_i18ns WHERE locale ='chi'");
        }

        //$zz=$this->ArticleI18n->find("all",array("conditions"=>array("ArticleI18n.locale"=>$locale1)));
        //var_dump($yy);
        //文章多语言
        $this->ArticleI18n->deleteAll("ArticleI18n.locale='".$locale1."'");
        $ee = $this->ArticleI18n->find('all', array('conditions' => array('ArticleI18n.locale' => $def['Language']['locale'])));
        foreach ($ee as $k3 => $v3) {
            $v3['ArticleI18n']['locale'] = $locale1;
            $v3['ArticleI18n']['id'] = '';
            $this->ArticleI18n->save($v3);
            $this->ArticleI18n->id = false;
        }//query("insert into svcart_navigation_i18ns SELECT '','jpn','navigation_id','name','url','description','img01','img02','created','modified' FROM svcart_navigation_i18ns WHERE locale ='chi'");
//		//分类多语言
//		$this->CategoryI18n->deleteAll("CategoryI18n.locale='".$locale1."'");
//		$ef=$this->CategoryI18n->find("all",array("conditions"=>array("CategoryI18n.locale"=>$def['Language']['locale'])));
//		foreach($ef as $k3=>$v3){
//			$v3["CategoryI18n"]["locale"]=$locale1;
//			$v3["CategoryI18n"]["id"]="";
//			$this->CategoryI18n->save($v3);
//			$this->CategoryI18n->id=false;
//		}
        //文章分类多语言
        $this->CategoryArticleI18n->deleteAll("CategoryArticleI18n.locale='".$locale1."'");
        $ef = $this->CategoryArticleI18n->find('all', array('conditions' => array('CategoryArticleI18n.locale' => $def['Language']['locale'])));
        foreach ($ef as $k3 => $v3) {
            $v3['CategoryArticleI18n']['locale'] = $locale1;
            $v3['CategoryArticleI18n']['id'] = '';
            $this->CategoryArticleI18n->save($v3);
            $this->CategoryArticleI18n->id = false;
        }

        //query("insert into svcart_navigation_i18ns SELECT '','jpn','navigation_id','name','url','description','img01','img02','created','modified' FROM svcart_navigation_i18ns WHERE locale ='chi'");
        //商品多语言
        $pro = $this->ProductI18n->find('all', array('conditions' => array('ProductI18n.locale' => $locale1)));
        if (empty($pro)) {
            $ff = $this->ProductI18n->find('all', array('conditions' => array('ProductI18n.locale' => $def['Language']['locale'])));
            foreach ($ff as $k3 => $v3) {
                $v3['ProductI18n']['locale'] = $locale1;
                $v3['ProductI18n']['id'] = '';
                $this->ProductI18n->save($v3);
                $this->ProductI18n->id = false;
            }
        }//query("insert into svcart_navigation_i18ns SELECT '','jpn','navigation_id','name','url','description','img01','img02','created','modified' FROM svcart_navigation_i18ns WHERE locale ='chi'");
        //品牌多语言（处理中）
        $pro_brand = $this->BrandI18n->find('all', array('conditions' => array('BrandI18n.locale' => $locale1)));
        if (empty($pro_brand)) {
            $ff = $this->BrandI18n->find('all', array('conditions' => array('BrandI18n.locale' => $def['Language']['locale'])));
            foreach ($ff as $k4 => $v4) {
                $v4['BrandI18n']['locale'] = $locale1;
                $v4['BrandI18n']['id'] = '';
                $this->BrandI18n->save($v4);
                $this->BrandI18n->id = false;
            }
        }
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('ShippingI18n');
            //配送方式多语言
            $pro_shipping = $this->ShippingI18n->find('all', array('conditions' => array('ShippingI18n.locale' => $locale1)));
            if (empty($pro_shipping)) {
                $pro_base_shipping = $this->ShippingI18n->find('all', array('conditions' => array('ShippingI18n.locale' => $def['Language']['locale'])));
                foreach ($pro_base_shipping as $p) {
                    $p['ShippingI18n']['id'] = '';
                    $p['ShippingI18n']['locale'] = $locale1;
                    $base_shipping_info['ShippingI18n'] = $p['ShippingI18n'];
                    $this->ShippingI18n->saveall($base_shipping_info);
                    $this->ShippingI18n->id = false;
                }
            }
        }
        //支付方式多语言
        $pro_payment = $this->Payment->find('all', array('conditions' => array('PaymentI18n.locale' => $locale1)));
        if (empty($pro_payment)) {
            $pro_base_payment = $this->PaymentI18n->find('all', array('conditions' => array('PaymentI18n.locale' => $def['Language']['locale'])));
            foreach ($pro_base_payment as $v6) {
                $v6['PaymentI18n']['id'] = '';
                $base_payment_info['PaymentI18n'] = $v6['PaymentI18n'];
                $this->PaymentI18n->saveall($base_payment_info);
                $this->PaymentI18n->id = false;
            }
        }
        $iri = $this->InformationResourceI18n->find('all', array('conditions' => array('InformationResourceI18n.locale' => $locale1)));
        if (empty($iri)) {
            $ff2 = $this->InformationResourceI18n->find('all', array('conditions' => array('InformationResourceI18n.locale' => $def['Language']['locale'])));
            foreach ($ff2 as $k9 => $v9) {
                $v9['InformationResourceI18n']['locale'] = $locale1;
                $v9['InformationResourceI18n']['id'] = '';
                $this->InformationResourceI18n->save($v9);
                $this->InformationResourceI18n->id = false;
            }
        }
        //die();
        $this->redirect('/languages/view/'.$xid);
    }

    public function stop_language($locale)
    {
        $lost = $this->Language->find('first', array('conditions' => array('Language.locale !=' => $locale)));
        if (!empty($lost)) {
            $this->Language->updateAll(array('Language.is_default' => 0, 'Language.front' => 0, 'Language.backend' => 0), array('Language.locale' => $locale));
            $this->Language->updateAll(array('Language.is_default' => 1, 'Language.front' => 1, 'Language.backend' => 1), array('Language.locale' => $lost['Language']['locale']));
            $al = $this->Application->find('first', array('conditions' => array('Application.code' => strtoupper('app-lang-'.$lost['Language']['locale']))));
            if (isset($al['Application']['status']) && $al['Application']['status'] == 0) {
                $this->Application->updateAll(array('Application.status' => 1), array('Application.id' => $al['Application']['id']));
//				$did=$this->Domain->find('first');
                $status = array(
                            'domain_id' => $this->configs['shop_domain_id'],//$did['Domain']['id']
                            'app_code' => $al['Application']['code'],
                            'status' => 1,
                            );
                $this->change_status($status);
            }
        } else {
            $msg = '已是唯一语言!';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");	window.location.href="/admin/applications/"</script>';
            die();
        }
    }

    public function count_app_lan()
    {
        $foo_arr = array();
        foreach ($this->apps['Applications'] as $k => $v) {
            $code = explode('-', $k);
            if ($code[1] == 'LANG') {
                $foo_arr[] = strtolower($code[2]);
            }
        }

        return count($foo_arr);
    }

    public function is_set_thm($code)
    {
        $tanl = strtolower($code);
        $tanp = $this->Template->find('first', array('conditions' => array('Template.name' => $tanl)));
        if (!empty($tanp)) {
            return false;
        } else {
            return true;
        }
    }
    public function to_install_thm($code)
    {
        $this->Template->updateAll(array('Template.is_default' => 0));
        $code = strtolower($code);
        $base_thm = $this->Template->find('first', array('conditions' => array('Template.name' => $code)));
        $app_thm = array();
        $color = explode(',', $base_thm['Template']['template_style']);
        $app_thm['name'] = $base_thm['Template']['name'];
        $app_thm['description'] = $base_thm['Template']['description'];
        $app_thm['template_style'] = $color[0];
        $app_thm['url'] = $base_thm['Template']['url'];
        $app_thm['author'] = $base_thm['Template']['author'];
        $app_thm['status'] = $base_thm['Template']['status'];
        $app_thm['is_default'] = 1;
        $app_thm['version'] = $base_thm['Template']['version'];
        $this->Template->save($app_thm);
        $this->installadver($code);
    }
    public function stop_thm($code)
    {
        $code = strtolower($code);
        $lost = $this->Template->find('first', array('conditions' => array('Template.name !=' => $code)));
        if (!empty($lost)) {
            $this->Template->updateAll(array('Template.is_default' => 0), array('Template.name' => $code));
            $this->Template->updateAll(array('Template.is_default' => 1), array('Template.name' => $lost['Template']['name']));
            $al = $this->Application->find('first', array('conditions' => array('Application.code' => strtoupper('app-thm-'.$lost['Template']['name']))));
            if (isset($al['Application']['status']) && $al['Application']['status'] == 0) {
                $this->Application->updateAll(array('Application.status' => 1), array('Application.id' => $al['Application']['id']));
//				$did=$this->Domain->find('first');
                $status = array(
                            'domain_id' => $this->configs['shop_domain_id'],//$did['Domain']['id']
                            'app_code' => $al['Application']['code'],
                            'status' => 1,
                            );
                $this->change_status($status);
            }
        } else {
            $msg = '已是唯一模版!';
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");window.location.href="/admin/applications/"</script>';
            die();
        }
    }

    public function is_set_pay($code)
    {
        $code = strtolower($code);
        $pays = $this->Payment->find('first', array('conditions' => array('Payment.code' => $code)));
        if (!empty($pays)) {
            return false;
        } else {
            return true;
        }
    }

    public function to_install_pays($code)
    {
        $code = strtolower($code);
        $tmp = $this->Payment->find('first', array('conditions' => array('Payment.code' => $code, 'PaymentI18n.locale' => $this->locale)));
        $save_file = array(
        'Payment' => array(
            'store_id' => $tmp['Payment']['store_id'],
            'code' => $tmp['Payment']['code'],
            'fee' => $tmp['Payment']['fee'],
            'orderby' => $tmp['Payment']['orderby'],
            'config' => $tmp['Payment']['config'],
            'status' => 1,
            'is_cod' => $tmp['Payment']['is_cod'],
            'is_getinshop' => $tmp['Payment']['is_getinshop'],
            'is_online' => $tmp['Payment']['is_online'],
            'supply_use_flag' => $tmp['Payment']['supply_use_flag'],
            'order_use_flag' => $tmp['Payment']['order_use_flag'],
            'php_code' => $tmp['Payment']['php_code'],
            'version' => $tmp['Payment']['version'],
            ),
        );
        $this->Payment->save($save_file);
        $newid = $this->Payment->id;
        $xx = $this->Language->find('all', array('fields' => 'Language.locale'));
        foreach ($xx as $k => $v) {
            $save_file_i18n = array(
            'PaymentI18n' => array(
                'locale' => $v['Language']['locale'],
                'payment_id' => $newid,
                'name' => $tmp['PaymentI18n']['name'],
                'payment_values' => $tmp['PaymentI18n']['payment_values'],
                'description' => $tmp['PaymentI18n']['description'],
                'status' => $tmp['PaymentI18n']['status'],
                ),
            );
            $this->PaymentI18n->save($save_file_i18n);
            $this->PaymentI18n->id = false;
        }
        $this->redirect('/payments/edit/'.$newid);
    }

    public function stop_pays($code)
    {
        $code = strtolower($code);
        $lost = $this->Payment->find('first', array('conditions' => array('Payment.code !=' => $code)));
        if (!empty($lost)) {
            $al = $this->Application->find('first', array('conditions' => array('Application.code' => strtoupper('app-pay-'.$lost['Payment']['code']))));
            if (isset($al['Application']['status']) && $al['Application']['status'] == 0) {
                $this->Application->updateAll(array('Application.status' => 1), array('Application.id' => $al['Application']['id']));
                $status = array(
                            'domain_id' => $this->configs['shop_domain_id'],//$did['Domain']['id']
                            'app_code' => $al['Application']['code'],
                            'status' => 1,
                            );
                $this->change_status($status);
            }
        } else {
            $msg = $this->ld['only_payment'];
            echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");window.location.href="/admin/applications/"</script>';
            die();
        }
    }

    public function to_send_www($id, $status)
    {
        $this->set('title_for_layout', $this->ld['plugin_setting'].' - '.$this->configs['shop_name']);
        if ($this->RequestHandler->isPost()) {
            if ($status == '2') {
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">window.location.href="/admin/applications/"</script>';
                die();
            }
            $www = $this->data['APP-WWW-WEB']['value'];
            $icp = $this->data['APP-WWW-ICP']['value'];
            $did = $this->configs['shop_domain_id'];
            $pattern = '(www.)?.+.(com|net|org)$';
            if (!ereg($pattern, $www)) {
                $msg = '域名格式不正确，申请提交失败!';
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");window.location.href="/admin/applications/"</script>';
                die();
            }
            $www_id = $this->ApplicationConfig->find('first', array('conditions' => array('ApplicationConfig.code' => 'APP-WWW-WEB'), 'fields' => array('ApplicationConfig.id')));
            $icp_id = $this->ApplicationConfig->find('first', array('conditions' => array('ApplicationConfig.code' => 'APP-WWW-ICP'), 'fields' => array('ApplicationConfig.id')));
            $this->ApplicationConfigI18n->updateAll(array('ApplicationConfigI18n.value' => "'".$www."'"), array('ApplicationConfigI18n.app_config_id' => $www_id['ApplicationConfig']['id']));
            $this->ApplicationConfigI18n->updateAll(array('ApplicationConfigI18n.value' => "'".$icp."'"), array('ApplicationConfigI18n.app_config_id' => $icp_id['ApplicationConfig']['id']));
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'申请域名成功', $this->admin['id']);
            }
            if ($this->soap_to_web($did, $www, $icp)) {
                $msg = '域名绑定申请已提交，请耐心等待';
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");window.location.href="/admin/applications/view/'.$id.'"</script>';
                die();
            } else {
                $msg = '域名绑定申请提交失败!';
                echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /><script type="text/javascript">alert("'.$msg.'");window.location.href="/admin/applications/view/'.$id.'"</script>';
                die();
            }
        }
    }

    public function soap_to_web($did, $www, $icp)
    {
        $soap_api = 'http://'.IOCOMGT.'/soap/webservices/wsdl';
        $client = new nusoap_client($soap_api, true);
        $client->soap_defencoding = 'utf-8';
        $client->decode_utf8 = false;
        $client->xml_encoding = 'utf-8';
        $send_info = array(
                'did' => $did,
                'www' => $www,
                'icp' => $icp,
        );
        $result = $client->call('enter_to_www', $send_info);
        if ($result['code'] == '0') {
            return true;
        } else {
            return false;
        }
    }

    public function soap_to_web_status($did)
    {
        $soap_api = 'http://'.IOCOMGT.'/soap/webservices/wsdl';
        $client = new nusoap_client($soap_api, true);
        $client->soap_defencoding = 'utf-8';
        $client->decode_utf8 = false;
        $client->xml_encoding = 'utf-8';
        $send_info = array(
            'did' => $did,
        );
        $result = $client->call('get_www_status', $send_info);

        return $result;
    }

    //模版广告位安装
    public function installadver($code)
    {
        $ad_info = $this->AdvertisementPosition->find('all', array('conditions' => array('template_name' => $code)));
        foreach ($ad_info as $a) {
            $ad_info2['AdvertisementPosition'] = $a['AdvertisementPosition'];
            $this->AdvertisementPosition->save($ad_info2);
        }
        foreach ($ad_info as $v) {
            $adver_info = $this->Advertisement->find('all', array('conditions' => array('advertisement_position_id' => $v['AdvertisementPosition']['id'])));
            foreach ($adver_info as $vv) {
                $adver_info2['Advertisement'] = $vv['Advertisement'];
                $adver_info2['AdvertisementI18n'] = $vv['AdvertisementI18n'];
                $this->Advertisement->saveAll($adver_info2);
            }
        }
    }

    public function get_dbname()
    {
        $did = $this->configs['shop_domain_id'];
        $soap_api = 'http://'.IOCOMGT.'/soap/webservices/wsdl';
        $client = new nusoap_client($soap_api, true);
        $client->soap_defencoding = 'utf-8';
        $client->decode_utf8 = false;
        $client->xml_encoding = 'utf-8';
        $send_info = array(
                'strUserPass' => 'iocowdback',
                'did' => $did,
        );
        $result = $client->call('get_dbname', $send_info);

        return $result;
    }

    public function to_install_table($sql_name)
    {
        $foo = IOCONODE;
        $db_name = $this->get_dbname();
        $db_name = $db_name['info'];
        $soap_api = 'http://'.$foo.'/soap/shops/wsdl';
        $client = new nusoap_client($soap_api, true);
        $client->soap_defencoding = 'utf-8';
        $client->decode_utf8 = false;
        $client->xml_encoding = 'utf-8';
        $send_info = array(
                'db_name' => $db_name,
                'sql_name' => $sql_name,
        );
        $result = $client->call('table_egg', $send_info);
    }

    public function is_set_dsp($code)
    {
        $code = strtolower($code);
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('Shipping');
            $foo = $this->Shipping->find('first', array('conditions' => array('Shipping.code' => $code)));
        }
        if (!empty($foo)) {
            return false;
        } else {
            return true;
        }
    }

    public function to_install_dsp($code)
    {
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('Shipping');
            $this->loadModel('ShippingI18n');
            $code = strtolower($code);
            $foo = $this->Shipping->find('first', array('conditions' => array('Shipping.code' => $code)));
            $install = array();
            $install['store_id'] = $foo['Shipping']['store_id'];
            $install['code'] = $foo['Shipping']['code'];
            $install['insure'] = $foo['Shipping']['insure'];
            $install['support_cod'] = $foo['Shipping']['support_cod'];
            $install['status'] = $foo['Shipping']['status'];
            $install['php_code'] = $foo['Shipping']['php_code'];
            $install['orderby'] = $foo['Shipping']['orderby'];
            $install['insure_fee'] = $foo['Shipping']['insure_fee'];
            $install['version'] = $foo['Shipping']['version'];
            $fooi18n = $this->ShippingI18n->find('all', array('conditions' => array('ShippingI18n.shipping_id ' => $foo['Shipping']['id'])));
            $this->Shipping->save($install);
            $foo_id = $this->Shipping->id;
            $installi8n = array();
            foreach ($fooi18n as $k => $v) {
                $installi8n['locale'] = $v['ShippingI18n']['locale'];
                $installi8n['shipping_id'] = $foo_id;
                $installi8n['name'] = $v['ShippingI18n']['name'];
                $installi8n['description'] = $v['ShippingI18n']['description'];
                $installi8n['param'] = $v['ShippingI18n']['param'];
                $this->ShippingI18n->save($installi8n);
                $this->ShippingI18n->id = false;
            }
        }
    }

    public function stop_dsp($code)
    {
        if (constant('Product') == 'AllInOne') {
            $this->loadModel('Shipping');
            $code = strtolower($code);
            $this->Shipping->updateAll(array('Shipping.status' => '0'), array('Shipping.code' => $code));
        }
    }

    /* 清除缓存文件 */
    public function clear_cache_files()
    {
        $cache_dirs[] = TMPCO.'cache/models/';
        $cache_dirs[] = TMPCO.'cache/persistent/';
        $cache_dirs[] = TMPCO.'cache/views/';
        $cache_dirs[] = TMP.'cache/models/';
        $cache_dirs[] = TMP.'cache/persistent/';
        $cache_dirs[] = TMP.'cache/views/';
        $count = 0;
        foreach ($cache_dirs as $dir) {
            $folder = @opendir($dir);
            if ($folder === false) {
                continue;
            }
            while ($file = readdir($folder)) {
                if ($file == '.' || $file == '..' || $file == '.svn' || $file == 'empty') {
                    continue;
                }
                if (is_file($dir.$file)) {
                    if (@unlink($dir.$file)) {
                        ++$count;
                    }
                }
            }
            closedir($folder);
        }

        return $count;
    }

    /** 去官网提交服务单 send_meesage
     * return result;.
     **/
    public function send_meesage()
    {
        $did = $this->configs['shop_domain_id'];
        $soap_api = 'http://'.IOCOMGT.'/soap/webservices/wsdl';
        $client = new nusoap_client($soap_api, true);
        $client->soap_defencoding = 'utf-8';
        $client->decode_utf8 = false;
        $client->xml_encoding = 'utf-8';
        $info['title'] = $_POST['data']['Message']['title'];
        $info['content'] = $_POST['data']['Message']['content'];
        $info['msg_type'] = $_POST['data']['Message']['msg_type'];
        $info['msg_rank'] = $_POST['data']['Message']['msg_rank'];
        $info['did'] = $did;
        $info['email'] = $this->admin['email'];
        $info['name'] = $this->admin['name'];
        $eninfo = json_encode($info);
        $result = $client->call('addmessage', array('eninfo' => $eninfo));
        $result['flag'] = $result['app'];
        Configure::write('debug', 0);
        die(json_encode($result));
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
            }
        }
    }
}
