<?php
    $short = array('config' => 'short','use' => true);
    if (!defined('LOCALE')) {
        $lngsModel = ClassRegistry::init('Language');
        $lngs = $lngsModel->find('first', array('cache' => $short, 'conditions' => array('is_default' => '1'), 'fields' => array('Language.locale', 'Language.front')));
        if ($lngs['Language']['front'] == 0) {
            $lngs2 = $lngsModel->find('first', array('cache' => $short, 'conditions' => array('front' => '1'), 'fields' => array('Language.locale', 'Language.front')));
            define('LOCALE', $lngs2['Language']['locale']);
        } else {
            define('LOCALE', $lngs['Language']['locale']);
        }
    }
    $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
    $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
    $link_source=$http_type.$host.$_SERVER["REQUEST_URI"];
    $ShortLinkModel = ClassRegistry::init('ShortLink');
    $short_link_Info=$ShortLinkModel->find('first',array('conditions'=>array('link'=>$link_source,'link_source <>'=>'')));
    if(!empty($short_link_Info)){
    		$redirect_link=trim($short_link_Info['ShortLink']['link_source']);
		Header("HTTP/1.1 303 See Other"); 
		Header("Location: $redirect_link");
    		die();
    }
    Router::connect('/closed', array('controller' => 'users', 'action' => 'closed'));
    Router::parseExtensions('rss', 'xml');
    Router::connect('/sitemaps', array('controller' => 'sitemaps', 'action' => 'index'));
    Router::connect('/sitemap', array('controller' => 'sitemaps', 'action' => 'view'));
    Router::connect('/:controller/:id', array('action' => 'view'), array('pass' => array('id'), 'id' => '[0-9]+'));
    
    $RoutesModel = ClassRegistry::init('Route');
    $home_infos = $RoutesModel->find('first', array('cache' => $short, 'conditions' => array('Route.url' => '','Route.status'=>'1')));
    //pr($home_infos);DIE();
    if (empty($home_infos)) {
        Router::connect('/', array('controller' => 'pages', 'action' => 'home'));
    } else {
        //pr($home_infos);首页设置了替换路径
        Router::connect('/', array('controller' => $home_infos['Route']['controller'], 'action' => $home_infos['Route']['action'], $home_infos['Route']['model_id']));
    }
    
    $all_infos = $RoutesModel->find('all', array('cache' => $short, 'conditions' => array('Route.status' => '1','Route.url <>'=>'')));
    foreach ($all_infos as $k => $v) {
        Router::connect('/'.trim($v['Route']['url']), array('controller' => $v['Route']['controller'], 'action' => $v['Route']['action'], $v['Route']['model_id']));
    }
    //$tva = ClassRegistry::init('Application')->find('first', array('cache' => $short, 'conditions' => array('Application.code' => 'APP-TRAVEL'), 'fields' => array('Application.code')));

    Router::connect('/api/uc', array('controller' => 'ucs', 'action' => 'index'));
    Router::connect('/wap', array('controller' => 'waps', 'action' => 'index'));
    Router::connect('/soap/:controller/:action/*', array('prefix' => 'soap', 'soap' => true));
