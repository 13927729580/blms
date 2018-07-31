<?php

/*****************************************************************************
 * Seevia 关注用户管理
* ===========================================================================
* 版权所有  上海实玮网络科技有限公司，并保留所有权利。
* 网站地址: http://www.seevia.cn
* ---------------------------------------------------------------------------
* 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
* 不允许对程序代码以任何形式任何目的的再发布。
* ===========================================================================
* $开发: 上海实玮$
* $Id$*/

class OperatorChannelConfigsController extends AppController{
    public $name = 'OperatorChannelConfigs';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination','Html','Javascript');
    public $uses = array('OperatorChannel','OperatorChannelConfig','OperatorChannelConfigValue','OperatorChannelRelation','WebserviceLog');
    
    public function index(){
        /*判断权限*/
        $this->operator_privilege('view_channel_config');
        $this->operation_return_url(true);//设置操作返回页面地址

        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operators'],'url' => '/operators/');
        $this->navigations[] = array('name' => $this->ld['wechat_operator_manage'],'url' => '/operator_channels/');
        $this->navigations[] = array('name' => '配置管理','url' => '');
        $this->set('title_for_layout', '配置管理'.' - '.$this->configs['shop_name']);

        $condition = '';
        $code="";
        $configs_name="";
        $start_date_time = '';
        $end_date_time = '';
        $status="-1";
        if (isset($this->params['url']['keyword']) && $this->params['url']['keyword'] != '') {
            $condition['and']['or']['OperatorChannelConfig.code like'] = '%' . $_REQUEST['keyword'] . '%';
            $condition['and']['or']['OperatorChannelConfig.name like'] = '%' . $_REQUEST['keyword'] . '%';
            $this->set('keyword', $_REQUEST['keyword']);
        }
        if (isset($this->params['url']['operator_channel_id']) && $this->params['url']['operator_channel_id'] != -1) {
            $condition['and']['OperatorChannelConfig.operator_channel_id'] = $this->params['url']['operator_channel_id'];
            $operator_channel_id = $this->params['url']['operator_channel_id'];
            $this->set('operator_channel_id', $operator_channel_id);
        }
        if (isset($this->params['url']['status']) && $this->params['url']['status'] != '-1') {
            $condition['and']['OperatorChannelConfig.status'] = $this->params['url']['status'];
            $status = $this->params['url']['status'];
            $this->set('status', $status);
        }
        if (isset($this->params['url']['configs_name']) && $this->params['url']['configs_name'] != '') {
            $condition['and']['OperatorChannelConfig.name like'] = '%' . $this->params['url']['configs_name'] . '%';
            $configs_name = $this->params['url']['configs_name'];
            $this->set('configs_name', $configs_name);
        }
        if (isset($this->params['url']['code']) && $this->params['url']['code'] != '') {
            $condition['and']['OperatorChannelConfig.code like'] ='%' . $this->params['url']['code'] . '%';
            $code = $this->params['url']['code'];
            $this->set('code', $code);
        }
        if (isset($this->params['url']['start_date_time']) && $this->params['url']['start_date_time'] != '') {
            $condition['and']['OperatorChannelConfig.created >='] = $this->params['url']['start_date_time'].' 00:00:00';
            $start_date_time = $this->params['url']['start_date_time'];
            $this->set('start_date_time', $start_date_time);
        }
        if (isset($this->params['url']['end_date_time']) && $this->params['url']['end_date_time'] != '') {
            $condition['and']['OperatorChannelConfig.created <='] = $this->params['url']['end_date_time'].' 23:59:59';
            $end_date_time = $this->params['url']['end_date_time'];
            $this->set('end_date_time', $end_date_time);
        }

        $total = $this->OperatorChannelConfig->find('count', array('conditions'=>$condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $page = '1';
        if (isset($_GET['page']) && $_GET['page'] != '') {
                $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'operator_channel_configs','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'OperatorChannelConfig');
        $this->Pagination->init($condition, $parameters, $options);

        $operator_channel_config = $this->OperatorChannelConfig->find('all', array('conditions' => $condition,'order' => 'created desc','limit' => $rownum, 'page' => $page));
        $this->set('operator_channel_config', $operator_channel_config);

        $operator_channel = $this->OperatorChannel->find('all');
        $operator_channel_list = array();
        foreach ($operator_channel as $k => $v) {
           $operator_channel_list[$v['OperatorChannel']['id']] = $v['OperatorChannel']['name'];
        }
        $this->set('operator_channel_list', $operator_channel_list);
    }

    public function view($id = 0){
        $this->operator_privilege('view_channel_config');

        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operators'],'url' => '/operators/');
        $this->navigations[] = array('name' => $this->ld['wechat_operator_manage'],'url' => '/operator_channels/');
        $this->navigations[] = array('name' => '配置管理','url' => '');
        $this->set('title_for_layout', '编辑-配置管理'.' - '.$this->configs['shop_name']);

        $operator_channel_config = $this->OperatorChannelConfig->find('first', array('conditions' => array('OperatorChannelConfig.id'=>$id)));
        $this->set('operator_channel_config', $operator_channel_config);
        $this->set('id', $id);

        $operator_channel = $this->OperatorChannel->find('all');
        $operator_channel_list = array();
        foreach ($operator_channel as $k => $v) {
           $operator_channel_list[$v['OperatorChannel']['id']] = $v['OperatorChannel']['name'];
        }
        $this->set('operator_channel_list', $operator_channel_list);
    }

    public function operator_channel_configs_add(){
        Configure::write('debug',1);
        $this->layout='ajax';
        $result = array();
        $result['code'] = 0;
        $result['message']='';
        if ($this->RequestHandler->isPost()) {
            $this->OperatorChannelConfig->save($_POST['data']);
            $result['code'] = 1;
        }
        die(json_encode($result));
    }

    public function remove($id){
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_member_failure'];

        $code = $this->OperatorChannelConfig->find('first',array('conditions'=>array('OperatorChannelConfig.id'=>$id)));
        $this->OperatorChannelConfig->deleteAll(array('id' => $id));
        $this->OperatorChannelConfigValue->deleteAll(array('config_code' => $code['OperatorChannelConfig']['code']));
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_member_success'];
        if ($this->RequestHandler->isPost()) {
            die(json_encode($result));
            $this->redirect('/operator_channel_configs/');
        } else {
            $this->redirect('/operator_channel_configs/');
        }
    }
}