<?php

/*****************************************************************************
 * Seevia 公众平台消息管理
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
 *公众平台消息管理.
 *
 *对于OpenMessages这张表的增删改查
 *
 *@author   
 *
 *@version  $Id$
 */
class OpenMessagesController extends AppController
{
    /*
    *控制器的名字
    */
    public $name = 'OpenMessages';
    /*
    *引用的助手
    */
    public $helpers = array('Html','Pagination','Tinymce','fck','Form','Javascript','Ckeditor');
    /*
    *引用的组件
    */
    public $components = array('Pagination','RequestHandler','Email');
    /*
    *引用的model
    */
    public $uses = array('OperatorLog','OpenModel','OpenUserMessage','OpenUser');

    /**
     *OpenKeywords主页列表.
     *
     *呈现数据库表OpenKeywords的数据
     *
     *@author   weizhengye 
     *
     *@version  $Id$
     */
    public function index($page = 1)
    {
        //判断权限
        $this->operator_privilege('open_users_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/open_model/','sub' => '/open_messages/');
        //end
        $this->set('title_for_layout', $this->ld['open_message_management'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['open_model'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['open_message_management'],'url' => '/open_messages/');
        
        $conditions = array();
        $conditions['OpenUserMessage.open_user_id <>']='0';
        $cond['conditions'] = $conditions;
        $cond['fields'] = "OpenUserMessage.open_type,OpenUserMessage.open_type_id,OpenUserMessage.open_user_id,MAX(OpenUserMessage.id) as message_id";
        $cond['group']="OpenUserMessage.open_type,OpenUserMessage.open_type_id,OpenUserMessage.open_user_id"; 
        $message_id_list = $this->OpenUserMessage->find('all',$cond);
        $message_ids=array();
        foreach($message_id_list as $v){
        	$message_ids[]=$v[0]['message_id'];
        }
        $message_list=array();
        if(!empty($message_ids)){
        	$search_conditions=array();
        	$search_conditions['and']['OpenUserMessage.id']=$message_ids;
        	$search_conditions['and']['OpenUserMessage.open_user_id <>']='0';
        	$search_conditions['OpenUserMessage.return_code']=array('0','ok');
	        if (isset($_REQUEST['keywords']) && trim($_REQUEST['keywords']) != '') {
	            $search_conditions['and']['or']['OpenUserMessage.message like'] = '%'.trim($_REQUEST['keywords']).'%';
	            $search_conditions['and']['or']['OpenUserMessage.open_type_id like'] = '%'.trim($_REQUEST['keywords']).'%';
	            $open_user_cond=array();
	            $open_user_cond['OpenUser.nickname like']='%'.urlencode(trim($_REQUEST['keywords'])).'%';
	            $open_user_ids=$this->OpenUser->find('list',array('conditions'=>$open_user_cond));
	            if(!empty($open_user_ids)){
	            		$search_conditions['and']['or']['OpenUserMessage.open_user_id'] = $open_user_ids;
	            }
	            $this->set('keywords', $_REQUEST['keywords']);
	        }
	        if (isset($_REQUEST['selectstatus']) && trim($_REQUEST['selectstatus']) != '') {
	            $selectstatus = trim($_REQUEST['selectstatus']);
	            $search_conditions['and']['OpenUserMessage.send_from'] =$selectstatus;
	            $this->set('selectstatus', $selectstatus);
	        }
	        if (isset($this->params['url']['start_date']) && trim($this->params['url']['start_date']) != '') {
	            $search_conditions['and']['OpenUserMessage.created >='] = trim($this->params['url']['start_date']).' 00:00:00';
	            $start_date = $this->params['url']['start_date'];
	            $this->set('start_date', $start_date);
	        }
	        if (isset($this->params['url']['end_date']) && trim($this->params['url']['end_date']) != '') {
	            $search_conditions['and']['OpenUserMessage.created <='] = trim($this->params['url']['end_date']).' 23:59:59';
	            $end_date = $this->params['url']['end_date'];
	            $this->set('end_date', $end_date);
	        }
        	$search_cond=array();
        	$search_cond['conditions']=$search_conditions;
        	//分页
        	$total = $this->OpenUserMessage->find('count', $search_cond);//获取总记录数
        	$this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
	        if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
	            $page = $_REQUEST['page'];//当前页
	        }
	        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';//默认显示记录数
	        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
	        $parameters['get'] = array();
	        //地址路由参数（和control,action的参数对应）
	        $parameters['route'] = array('controller' => 'OpenMessage','action' => 'index','page' => $page,'limit' => $rownum);
	        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'OpenKeyword');
	        $this->Pagination->init($search_conditions, $parameters, $options);
	        $search_cond['joins']= array(
	            array('table' => 'svsns_open_users',
	                  'alias' => 'OpenUser',
	                  'type' => 'inner',
	                  'conditions' => array('OpenUser.id = OpenUserMessage.open_user_id'),
	                 ));
	        $search_cond['order']='OpenUserMessage.created desc';
	        $search_cond['fields']='OpenUserMessage.*,OpenUser.nickname,OpenUser.headimgurl';
        	 $message_list = $this->OpenUserMessage->find('all', $search_cond);
        }
        $this->set('message_list', $message_list);
        $openmodel_list = $this->OpenModel->find('all', array('conditions' => array('status' => 1, 'verify_status' => 1)));
        $this->set('openmodel_list', $openmodel_list);
    }
    
}
