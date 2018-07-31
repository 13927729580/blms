<?php

/*****************************************************************************
 * SEEVIA 应用控制器
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$SystemList
 * $Id$
*****************************************************************************/

/**
 *这是一个名为 AppController 的控制器
 *后台控制控制器.
 *
 *@var
 *@var
 *@var
 *@var
 *@var
 *@var
 *@var
 *@var
 *@var
 */
class AppController extends Controller
{
    public $helpers = array('combinator.combinator','Html','Javascript','Form','html','minify','Svshow');
    public $uses = array('Application','Language','Config','Operator','Menu','Action','Template','Dictionary','Role','OperatorLog','Resource','System','SystemModule');
    public $components = array('Captcha','RequestHandler','Cookie');

    public $configs = array();//商店设置参数
    public $languages = array();//全部语言参数
    public $front_locales = array();
    //后台当前语言
    public $locale = '';
    //后台当前语言
    public $backend_locale = '';

    public $themes_host = '';//公共模块路径
    	
    public $systems = array();

    public $apps = array();

    //操作员
    public $admin = array();

    public $view = 'Theme';

    /**
     *前过滤器.
     */
    public function beforeFilter()
    {
        @session_start();
        //分页时cookie有读取
        $pass_check['authnums']['get_authnums'] = 1; // 
        $pass_check['authnums']['get_authnumber'] = 1; // 
        $pass_check['operators']['ajax_login'] = 1; // 
        $pass_check['js']['selectlang'] = 1; // /js/selectlang
        $pass_check['pages']['login'] = 1;   // /pages/login
        $pass_check['photo_category_gallery']['add_image_water'] = 1;  //图片水印
        $pass_check['photo_category_gallery']['photo'] = 1;  //图片空间上传
        $pass_check['photo_category_gallery']['product_photo'] = 1;  //商品旋转图片上传
        $pass_check['photo_category_gallery']['photo_replace'] = 1;  //图片替换
        $pass_check['operator_channels']['channel_callback'] = 1;  //渠道授权回调
        $pass_check['operator_channels']['ajax_load_channel'] = 1;  //加载可用渠道
        if (!isset($pass_check[$this->params['controller']][$this->params['action']])) {
            //判断是否登陆
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                //ajax不保留当前地址 zhta
            } else {
                $_SESSION['url'] = $this->here;
            }
            $this->admin = $this->Operator->check_login();
            if (!$this->admin) {
                $this->Operator->logout();
                $this->redirect('/login');
            }
            
        if (isset($_GET['dev'])) {   //开发模式显示开发级菜单及所有模块菜单
            $_SESSION['dev'] = $_GET['dev'];
        }
            //20170206 system-module
            $this->System->modules();
            $system_modules=$this->System->modules;
            $SystemList=array_keys($system_modules);
            $this->set('system_modules', $system_modules);
            //pr($system_modules);
            $this->set('SystemList', $SystemList);
            //echo ($this->System->checkmodule('O2O','Product'))?'1':'0';

            if ($this->admin['actions'] != 'all') {
            		$action_id_lists=explode(';', $this->admin['actions']);
            		if ($this->admin['role_id'] != '') {
            			$operator_role_ids=explode(';',$this->admin['role_id']);
            			$operator_role_ids=array_unique($operator_role_ids);
            			$operator_role_ids=array_filter($operator_role_ids);
                    	$role_action_id_lists = $this->Role->find('list', array('fields' => array('Role.actions'), 'conditions' => array('Role.id' => $operator_role_ids)));
                    	foreach($role_action_id_lists as $v)$action_id_lists=array_merge($action_id_lists,explode(";",$v));
            		}
            		if(!empty($action_id_lists))$action_id_lists=array_unique($action_id_lists);
			$this->admin['action_codes'] = $this->Action->find('list',
				array(
					'fields' => array('Action.id', 'Action.code'),
					'conditions' => array('Action.id' => $action_id_lists),
					'recursive' => -1
				)
			);
            } else {
                $this->admin['action_codes'] = 'all';
            }
            $this->set('admin', $this->admin);
        }
        //当前域名
        $http_type = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https://' : 'http://';
        $host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
        $post=isset($_SERVER['SERVER_PORT'])?$_SERVER['SERVER_PORT']:'80';
        $this->server_host = $http_type.$host.($post!='80'&&$post!='443'?(":".$post):'');
        $hr_server_host=trim(Configure::read('HR.server_host'));
        if($hr_server_host!=$this->server_host){
        	throw new Exception(dirname(ROOT).'/data/lms.php is error file');
        }
        $this->set('server_host', $this->server_host);
        //语言读取
        $this->Language->getinfo();
        $this->languages_assoc = $this->Language->findalllang_assoc();
        //pr($_REQUEST);
        if (isset($_REQUEST['backend_locale'])) {
            $this->backend_locale = $_REQUEST['backend_locale'];
            $this->locale = $_REQUEST['backend_locale'];
        } else {
            $backend_locale = $this->Cookie->read('backend_locale');
            //echo $backend_locale;
            if ($backend_locale != '') {
                $this->backend_locale = $backend_locale;
            } else {
                $this->backend_locale = isset($this->admin['default_lang']) ? $this->admin['default_lang'] : $this->Language->info['backend']['locale'];
            }
            $this->locale = $this->backend_locale;
        }
        $this->front_locales = $this->Language->info['front_locales'];
        $this->backend_locales = $this->Language->info['backend_locales'];
        $this->set('front_locales', $this->front_locales);//赋值到模板
        $this->set('backend_locales', $this->backend_locales);//后台语言赋值到模板
        $this->set('locale', $this->locale);//赋值到模板
        $this->set('backend_locale', $this->backend_locale);
        $this->set('backend_locale_info', $this->Language->info['backend']);
        $pagers_num_cookies = $this->Cookie->read('pagers_num_cookies');
        $this->Cookie->write('backend_locale', $this->backend_locale);
        $this->ld = $this->Dictionary->getformatcode($this->backend_locale);
        $this->set('ld', $this->ld);
        $this->admin_webroot = $this->base.'/';
        //$this->webroot = dirname($this->base);
	 $this->webroot = str_replace('\\','/',dirname($this->base).'/');
	 $this->webroot = str_replace('//','/',$this->webroot);
        $this->set('admin_webroot', $this->admin_webroot);
        $this->set('webroot', $this->webroot);
        //商店设置参数
        $this->Config->set_locale($this->backend_locale);
        $this->configs = $this->Config->getformatcode();
        $this->check_version();
        if (!empty($pagers_num_cookies)) {
            $this->configs['show_count'] = $pagers_num_cookies;//重置分页数
        }
        $show_edit_type = isset($this->configs['show_edit_type']) ? $this->configs['show_edit_type'] : '1';
        $this->set('show_edit_type', $show_edit_type);
        $this->set('configs', $this->configs);
        if ($this->admin) {
            //所有插件的状态
            $this->apps = $this->Application->init($this->backend_locale);
            $this->set('apps', $this->apps);
            //资源库信息
            $resources = $this->Resource->getformatcode(array('msg_type'), $this->locale);
            $this->set('resources', $resources);
            //操作员菜单
            $this->Menu->set_locale($this->backend_locale);
            $menus = $this->Menu->tree('all', $this->backend_locale, $this->System->modules, $this->admin['action_codes']);
            //如果没有子菜单删掉主菜单
            foreach ($menus as $k => $v) {
                if ((isset($v['SubMenu']) && empty($v['SubMenu'])) || !isset($v['SubMenu'])) {
                    unset($menus[$k]);
                }
            }
            $this->set('menus', $menus);
            $this->menus=$menus;
            $this->menu_path = array('root' => '','sub' => '');
            //后台导航
            $this->navigations[] = array('name' => $this->ld['home'],'url' => '/');
        }
    }

    /**
     * 提前渲染.
     */
    public function beforeRender()
    {
        if ($this->admin) {
        		$SubMenu=array();$DefaultMenuId=0;
        		if(isset($this->menus)&&!empty($this->menus)){
        			foreach($this->menus as $k=>$v){
        				if(isset($v['SubMenu'])&&!empty($v['SubMenu'])){
        					foreach($v['SubMenu'] as $kk=>$vv){
        						$outMenuLink=explode('/',$vv['Menu']['link']);
        						if(!empty($outMenuLink))$outMenuLink=array_values(array_filter($outMenuLink));
        						if(isset($outMenuLink[0])){
			        				if(isset($this->params['controller'])&&$this->params['controller']==$outMenuLink[0]){
			        					$this->navigations[1] = array('name' => $v['MenuI18n']['name'],'url' => '');
			        					$SubMenu=$v['SubMenu'];
			        					$DefaultMenuId=$v['Menu']['id'];
			        				}
        						}
        					}
        				}
        			}
        			if(!empty($SubMenu)){
        				$DefaultSubMenu=array();
	        			foreach($SubMenu as $k=>$v){
						$menu_link_info=array_values(array_filter(explode('/',$v['Menu']['link'])));
						if(!isset($menu_link_info[1]))$menu_link_info[1]='index';
						if(isset($this->params['controller'])&&isset($this->params['action'])&&$this->params['controller']==$menu_link_info[0]&&$this->params['action']==$menu_link_info[1]){
							$DefaultSubMenu[0]=$v['Menu']['id'];
						}else if(isset($this->params['controller'])&&$this->params['controller']==$menu_link_info[0]){
							$DefaultSubMenu[1]=$v['Menu']['id'];
						}
	        			}
        			}
        		}
			$this->set('menu_path', $this->menu_path);
			$this->set('navigations', $this->navigations);
			$this->set('SubMenu', $SubMenu);
			$this->set('DefaultMenuId', $DefaultMenuId);
			$this->set('DefaultSubMenuId', isset($DefaultSubMenu[0])?$DefaultSubMenu[0]:(isset($DefaultSubMenu[1])?$DefaultSubMenu[1]:0));
        }
        unset($this->configs);
        unset($this->ld);
        if (isset($_SESSION['template_operator']) && $_SESSION['template_operator'] != '') {
            $this->admin['template_code'] = $_SESSION['template_operator'];
        }
        if (isset($this->admin['template_code']) && $this->admin['template_code'] != '') {
            $this->theme = $this->admin['template_code'];
        } else {
            $this->theme = 'default';
        }
        if (isset($_REQUEST['themes']) && $_REQUEST['themes'] != '') {
            $this->admin['template_code'] = $_REQUEST['themes'];
            $this->theme = $_REQUEST['themes'];
            $_SESSION['template_operator'] = $this->theme;
        }
        $this->set('memory_useage', number_format((memory_get_usage() / 1048576), 3, '.', ''));//占用内存
    }

    //管理员权限检查
    public function operator_privilege($action_code, $is_jump = true)
    {
        if ($this->admin['actions'] == 'all') {
            return true;
        } elseif (in_array($action_code, $this->admin['action_codes'])) {
            return true;
        } else {
            if ($is_jump) {
                $this->redirect('/');

                return false;
            } else {
                return false;
            }
        }
    }

    /*
        记录操作返回url地址
    */
    public function operation_return_url($flag = false)
    {
        $back_url = $_SERVER['REQUEST_URI'];
        if (strstr($back_url, $this->base)) {
            $back_url = substr($back_url, strlen($this->base));
        }
        if ($flag == true) {
            $_SESSION['operation_return_url'] = $back_url;
        } else {
            return isset($_SESSION['operation_return_url']) ? $_SESSION['operation_return_url'] : '/';
        }
    }

    /*
        创建目录路径
    */
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
    
    /*
		检查版本信息
	*/
	function check_version(){
		if(isset($this->configs['version'])&&defined('Version')){
			$version_config=$this->configs['version'];
			if($version_config!=Version){
				header('Location:'.$this->server_host.$this->webroot.'/tools/upgrades');
			        exit();
			}
		}
	}
}
