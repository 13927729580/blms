<?php

/*****************************************************************************
 * Seevia 用户分类管理
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
 *这是一个名为 UserCategoriesController 的控制器
 *后台用户分类管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class UserCategoriesController extends AppController
{
    public $name = 'UserCategories';
    public $components = array('Pagination','RequestHandler','Phpexcel','Orderfrom','Phpcsv','Notify');
    public $helpers = array('Pagination');
    public $uses = array('UserCategory');
    
    /**
     *	显示分类列表
     */
    public function index($page = 1){
		$this->operator_privilege('user_category_view');
		$this->operation_return_url(true);//设置操作返回页面地址
		$this->menu_path = array('root' => '/crm/','sub' => '/users/');
		$this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
		$this->navigations[] = array('name' => $this->ld['users_search'],'url' => '/users/');
		$this->navigations[] = array('name' => $this->ld['user_category_management'],'url' => '/user_categories/');
		
		$condition=array();
		if (isset($_REQUEST['category_keyword']) && trim($_REQUEST['category_keyword']) != '') {
			$category_keyword=trim($_REQUEST['category_keyword']);
			$condition['or']['UserCategory.code like'] = '%'.$category_keyword.'%';
			$condition['or']['UserCategory.name like'] = '%'.$category_keyword.'%';
			$condition['or']['UserCategory.description like'] = '%'.$category_keyword.'%';
			$this->set('category_keyword', $category_keyword);
		}
		if (isset($_REQUEST['category_status']) && $_REQUEST['category_status'] != '') {
			$condition['UserCategory.status'] = $_REQUEST['category_status'];
			$this->set('category_status', $_REQUEST['category_status']);
		}
		$total = $this->UserCategory->find('count', array('conditions' => $condition));
		$this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
		$rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        	$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
        	$parameters['route'] = array('controller' => 'user_categories','action' => 'index','page' => $page,'limit' => $rownum);
        	$options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'UserCategory');
        	$this->Pagination->init($condition, $parameters, $options);
        	$usercategory_data = $this->UserCategory->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'UserCategory.created desc'));
        	$this->set('usercategory_data',$usercategory_data);
        	
        	$this->set('title_for_layout', $this->ld['user_category_management'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }
    
    /**
     *	添加、编辑
     *
     *	@param int $id 用户ID
     */
    public function view($id=0){
		$this->menu_path = array('root' => '/crm/','sub' => '/users/');
		$this->navigations[] = array('name' => $this->ld['manager_customers'],'url' => '');
		$this->navigations[] = array('name' => $this->ld['users_search'],'url' => '/users/');
		$this->navigations[] = array('name' => $this->ld['user_category_management'],'url' => '/user_categories/');
        	if(empty($id)){
        		$this->operator_privilege('user_category_add');
        		$this->navigations[] = array('name' => $this->ld['add'],'url' => '');
        	}else{
        		$this->operator_privilege('user_category_edit');
        		$this->navigations[] = array('name' => $this->ld['edit'],'url' => '');
        	}
        	if ($this->RequestHandler->isPost()) {
        		if(isset($this->data['UserCategory'])&&!empty($this->data['UserCategory'])){
    				$usercategory_code=$this->data['UserCategory']['code'];
    				$usercategory_id=isset($this->data['UserCategory']['id'])?intval($this->data['UserCategory']['id']):0;
    				$usercategory_check=$this->UserCategory->find('count',array('conditions'=>array('UserCategory.code'=>$usercategory_code,'UserCategory.id <>'=>$usercategory_id)));
    				if(!$usercategory_check){
    					$this->UserCategory->save($this->data['UserCategory']);
    					//操作员日志
			            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
			                	$this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].$this->ld['classification'].':'.$usercategory_code, $this->admin['id']);
			            }
    				}
        		}
        		$back_url = $this->operation_return_url();//获取操作返回页面地址
            	$this->redirect($back_url);
        	}
        	
        	$usercategory_data = $this->UserCategory->find('first', array('conditions' => array("UserCategory.id"=>$id)));
        	$this->set('usercategory_data',$usercategory_data);
        	
        	$this->set('title_for_layout', $this->ld['add_edit'].' - '.$this->ld['user_category_management'].' - '.$this->configs['shop_name']);
    }
    
    function remove($id=0){
    		$this->operator_privilege('user_category_remove');
    		Configure::write('debug', 0);
		$this->layout = 'ajax';
		$result=array();
             $result['flag']='0';
             $result['message']=$this->ld['delete_failure'];
		if($this->UserCategory->delete(array('id'=>$id))){
			$result['flag']='1';
             	$result['message']=$this->ld['deleted_success'];
			//操作员日志
		       if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
		            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['remove'].$this->ld['user_category'].":".$id, $this->admin['id']);
		       }
	    	}
	       die(json_encode($result));
    }
    
    function categorycode_check(){
		Configure::write('debug',0);
		$this->layout = 'ajax';
             $result=array();
             $result['code']='0';
             $result['message']=$this->ld['code_already_exists'];
             $categoryid=isset($_POST['categoryid'])?intval($_POST['categoryid']):0;
             $categorycode=isset($_POST['categorycode'])?trim($_POST['categorycode']):'';
             $usercategory_check=$this->UserCategory->find('count',array('conditions'=>array('UserCategory.code'=>$categorycode,'UserCategory.id <>'=>$categoryid)));
             if($usercategory_check==0){
             	$result['code']='1';
             	$result['message']='';
             }
             die(json_encode($result));
    }
    
    function toggle_on_status(){
    		$this->operator_privilege('user_category_edit');
    		Configure::write('debug', 0);
        	$this->layout = 'ajax';
        	$id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
        	$val = isset($_REQUEST['val'])?$_REQUEST['val']:1;
        	$result = array();
        	$result['flag'] = 0;
		$result['content'] = stripslashes($val);
        	if (!empty($id)&&is_numeric($val) && $this->UserCategory->save(array('id' => $id, 'status' => $val))) {
			$result['flag'] = 1;
			$result['content'] = stripslashes($val);
        		//操作员日志
	            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
	                	$this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].$this->ld['user_category'].$this->ld['status'].":".$id, $this->admin['id']);
	            }
        	}
        	die(json_encode($result));
    }
    
    public function batch_operations(){
    		$this->operator_privilege('user_category_remove');
    		Configure::write('debug', 0);
        	$this->layout = 'ajax';
        	$categoryids = !empty($_REQUEST['checkboxes']) ? $_REQUEST['checkboxes'] : array();
        	$result = array();
        	$result['flag'] = 0;
		$result['message'] = $this->ld['delete_failure'];
        	if(!empty($categoryids)){
        		$this->UserCategory->deleteAll(array('id'=>$categoryids));
        		$result['flag']='1';
             	$result['message']=$this->ld['deleted_success'];
        		//操作员日志
		       if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
		            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['remove'].$this->ld['user_category'].":".implode(",",$categoryids), $this->admin['id']);
		       }
        	}
        	die(json_encode($result));
    }
}