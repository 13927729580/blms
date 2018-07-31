<?php

/*****************************************************************************
 * Seevia 邮件统计管理
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
class MailStatisticsController extends AppController
{
    public $name = 'MailStatistics';
    public $components = array('Pagination','RequestHandler','Email');
    public $helpers = array('Pagination','Html','Form','Javascript','Tinymce','fck');
    public $uses = array('MailStatistic','InformationResource');

    public function index($page = 1)
    {
        $this->operator_privilege('mail_statistics_view');
        $this->menu_path = array('root' => '/oms/','sub' => '/mail_statistics/');
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['manage_email'],'url' => '/mail_statistics/');

        $condition = '';
        $start_time = '';
        $end_time = '';
        $mail_type = '';
        //关键字
        $remark = '';
        if (isset($this->params['url']['mail_type']) && $this->params['url']['mail_type'] != '') {
            $condition['type'] = $mail_type;
        }
        $this->set('mail_type', $mail_type);
        if (isset($this->params['url']['start_time']) && $this->params['url']['start_time'] != '') {
            $condition['mail_date >='] = $this->params['url']['start_time'].' 00:00:00';
            $start_time = $this->params['url']['start_time'];
        }
        $this->set('start_time', $start_time);
        if (isset($this->params['url']['end_time']) && $this->params['url']['end_time'] != '') {
            $condition['mail_date <='] = $this->params['url']['end_time'].' 23:59:59';
            $end_time = $this->params['url']['end_time'];
        }
        $this->set('end_time', $end_time);

        if (isset($this->params['url']['page']) && $this->params['url']['page'] != '') {
            $page = $this->params['url']['page'];
        }
        $total = $this->MailStatistic->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'articles','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'MailStatistic');
        $this->Pagination->init($condition, $parameters, $options);
        $mail_statistics = $this->MailStatistic->find('all', array('page' => $page, 'limit' => $rownum, 'order' => 'id desc', 'conditions' => $condition));
        $this->set('mail_statistics', $mail_statistics);
        $inbound_type_arr = array();
        $information_resources_info = $this->InformationResource->information_formated('mail_statistics_code', $this->locale);
        $mail_type_array = array();
        if (isset($information_resources_info['mail_statistics_code'])) {
            $mail_type_array = $information_resources_info['mail_statistics_code'];
        }
        $this->set('mail_type_array', $mail_type_array);
        $this->set('title_for_layout', '邮件统计'.' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    public function batch()
    {
        //批量处理
        $result['flag'] = 2;
        $result['message'] = '删除失败';
        $user_checkboxes = $_REQUEST['checkboxes'];
        $this->MailStatistic->deleteAll(array('id' => $user_checkboxes));
        $result['flag'] = 1;
        $result['message'] = '删除成功';
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    //清空邮件(统计)日志
    public function clearall()
    {
        $this->operator_privilege('mail_statistics_clear');
        $this->MailStatistic->query('TRUNCATE TABLE `svcart_mail_statistics`');
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'清空邮件(统计)日志', $this->admin['id']);
        }
        $this->redirect('index');
    }
}
