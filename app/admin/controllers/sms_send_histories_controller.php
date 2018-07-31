<?php

/*****************************************************************************
 * Seevia sms_send_histories 短信发送日志
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
class SmsSendHistoriesController extends AppController
{
	public $name = 'SmsSendHistories';
	public $uses = array('SmsSendHistory');
	public $components = array('Pagination','RequestHandler','Phpexcel');//,分页
	public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');//分页样式

    public function index($page = 1)
    {
		$this->operator_privilege('log_management_view');
        $this->menu_path = array('root' => '/system/','sub' => '/sms_send_histories/');
		$this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['log_management'],'url' => '/log_managements/');
        $this->navigations[] = array('name' => $this->ld['sms_type'],'url' => '/sms_send_histories/');
		
        $condition = '';
        //状态
        if (isset($this->params['url']['status']) && $this->params['url']['status'] != '') {
            $condition['and']['SmsSendHistory.flag'] = $this->params['url']['status'];
            $this->set('status', $this->params['url']['status']);
        }
        //关键字
        if (isset($this->params['url']['keywords']) && $this->params['url']['keywords'] != '') {
            $keyname = $this->params['url']['keywords'];
            $condition['or']['SmsSendHistory.phone like'] = "%$keyname%";
            $condition['or']['SmsSendHistory.content like'] = "%$keyname%";
            $this->set('keywords', $this->params['url']['keywords']);
        }
        //更新时间
        if (isset($this->params['url']['start_time']) && $this->params['url']['start_time'] > 0) {
            $condition['and']['SmsSendHistory.modified >='] = $this->params['url']['start_time'];
            $this->set('ftime', $this->params['url']['start_time']);
        }
        if (isset($this->params['url']['end_time']) && $this->params['url']['end_time'] > 0) {
            $condition['and']['SmsSendHistory.modified <='] = $this->params['url']['end_time'];
            $this->set('etime', $this->params['url']['end_time']);
        }
        ///分页
        $total = $this->SmsSendHistory->find('count', array('conditions' => $condition));//统计全部商品总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        //$sortClass="Product";
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'sms_send_histories','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'SmsSendHistory');
        $this->Pagination->init($condition, $parameters, $options);
        ///分页

        $sms_send_histories_logs = $this->SmsSendHistory->find('all', array('conditions' => $condition, 'limit' => $rownum, 'page' => $page, 'order' => 'SmsSendHistory.modified desc'));
        $this->set('sms_send_histories_logs', $sms_send_histories_logs);
		
        $this->set('title_for_layout', $this->ld['sms_type'].' - '.$this->configs['shop_name']);
    }

    public function view($id = 0)
    {
        $this->operator_privilege('log_management_view');
        $this->menu_path = array('root' => '/system/','sub' => '/sms_send_histories/');
		$this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['log_management'],'url' => '/log_managements/');
        $this->navigations[] = array('name' => $this->ld['sms_type'],'url' => '/sms_send_histories/');
		$this->navigations[] = array('name' => $this->ld['view'],'url' => '');
		
        $conditions = array('SmsSendHistory.id' => $id);
        $sms_send_histories_data = $this->SmsSendHistory->find('first', array('conditions' => $conditions));
        $this->set('sms_send_histories_data', $sms_send_histories_data);
		if(empty($sms_send_histories_data)){$this->redirect('index');}
        $this->set('title_for_layout', $this->ld['sms_type'].$this->ld['view'].' - '.$this->configs['shop_name']);
    }

    //清空日志
    public function clearall()
    {
        $this->operator_privilege('log_management_remove');
        $this->SmsSendHistory->query('TRUNCATE TABLE `svsys_sms_send_histories`');
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'清空邮件日志', $this->admin['id']);
        }
        $this->redirect('index');
    }
}
