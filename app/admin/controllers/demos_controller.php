<?php

/**
 *这是一个名为 DemosController 的控制器
 *后台调试控制器
 *
 *@var
 *@var
 *@var
 *@var
 */
class DemosController extends AppController
{
    public $name = 'Demos';
    public $components = array('RequestHandler','Pagination');
    public $helpers = array('Html','Javascript','Pagination','Ckeditor');
    public $uses = array();
    
    public function index($page=1){
    		$this->set('title_for_layout', '后台列表示例 - '.$this->configs['shop_name']);
    		$this->navigations[] = array('name' => '产品','url' => '');
    		$this->navigations[] = array('name' => '后台列表示例','url' => '/demos/index');
    		
    		$this->loadModel('Page');
    		$condition = array();
    		//分页start
	        $total = $this->Page->find('count', array('conditions'=>$condition));
	        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
	        if (isset($_GET['page']) && $_GET['page'] != '') {
	            	$page = $_GET['page'];
	        }
	        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
	        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
	        $parameters['get'] = array();
	        //地址路由参数（和control,action的参数对应）
	        $parameters['route'] = array('controller' => 'demos','action' => 'index','page' => $page,'limit' => $rownum);
	        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Page');
	        $this->Pagination->init($condition, $parameters, $options);
    }
    
    public function view(){
    		$this->set('title_for_layout', '后台详情示例 - '.$this->configs['shop_name']);
    		$this->navigations[] = array('name' => '产品','url' => '');
    		$this->navigations[] = array('name' => '后台列表示例','url' => '/demos/index');
    		$this->navigations[] = array('name' => '后台详情示例','url' => '');
    }
    
    public function uploadpreview(){
    		$this->set('title_for_layout', '后台上传 - '.$this->configs['shop_name']);
    		$this->navigations[] = array('name' => '产品','url' => '');
    		$this->navigations[] = array('name' => '后台列表示例','url' => '/demos/index');
    		$this->navigations[] = array('name' => '后台上传','url' => '');
    		
    		$this->loadModel('Profile');
        	$this->loadModel('ProfileFiled');
    		$this->Profile->set_locale($this->backend_locale);
        	$profile_info = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' => 'product_import', 'Profile.status' => 1)));
        	if(empty($profile_info))$this->redirect('index');
        	$this->ProfileFiled->set_locale($this->backend_locale);
        	$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description','ProfileFiled.currency_format'), 'conditions' => array('ProfileFiled.profile_id' => $profile_info['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
        	if(empty($profilefiled_info))$this->redirect('index');
        	$this->set('profilefiled_info',$profilefiled_info);
    }
}