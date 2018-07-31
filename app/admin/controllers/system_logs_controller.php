<?php

/**
 * @category  PHP
 *
 * @author    Bo Huang <hobbysh@seevia.cn>
 * @copyright 2015 上海实玮网络科技有限公司
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU General Public License
 *
 * @version   Release: 1.0
 *
 * @link      http://www.seevia.cn
 */

/**
 *这是一个名为 SystemLogsController 的控制器
 *	后台系统日志控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class SystemLogsController extends AppController
{
    public $name = 'SystemLogs';
    public $components = array('RequestHandler','Cookie','Pagination');
    public $helpers = array('Html','Javascript','Pagination');
    public $uses = array('Operator','SystemLog');
    
    public function index($page=1){
    		$this->operator_privilege('log_management_view');
    		$this->operation_return_url(true);//设置操作返回页面地址
    		$this->menu_path = array('root' => '/web_application/','sub' => '/system_logs/');
    		
    		$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        	$this->navigations[] = array('name' => $this->ld['system_log'],'url' => '/system_logs/index');
        	
        	$condition = '';
        	if(isset($this->params['url']['log_type']) && trim($this->params['url']['log_type']) != '') {
			$log_type=trim($this->params['url']['log_type']);
			$condition['SystemLog.type'] = $log_type;
			$this->set('log_type', $log_type);
		}
		if(isset($this->params['url']['keywords']) && trim($this->params['url']['keywords']) != '') {
			$log_keywords=trim($this->params['url']['keywords']);
			$condition['SystemLog.log_text LIKE'] = '%'.$log_keywords.'%';
			$this->set('keywords', $log_keywords);
		}
		if(isset($this->params['url']['log_time_start']) && trim($this->params['url']['log_time_start']) != '') {
			$log_time_start=trim($this->params['url']['log_time_start']);
			$condition['SystemLog.created >='] = date("Y-m-d 00:00:00",strtotime($log_time_start));
			$this->set('log_time_start', $log_time_start);
		}
		if(isset($this->params['url']['log_time_end']) && trim($this->params['url']['log_time_end']) != '') {
			$log_time_end=trim($this->params['url']['log_time_end']);
			$condition['SystemLog.created <='] = date("Y-m-d 23:59:59",strtotime($log_time_end));
			$this->set('log_time_end', $log_time_end);
		}
        	$total = $this->SystemLog->find('count', array('conditions'=>$condition));
        	$this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
		if (isset($_GET['page']) && $_GET['page'] != '') {
			$page = $_GET['page'];
		}
		$this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        	$rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        	$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
        	$parameters['route'] = array('controller' => 'system_logs','action' => 'index','page' => $page,'limit' => $rownum);
        	$options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'SystemLog');
        	$this->Pagination->init($condition, $parameters, $options);
        	$system_log_data = $this->SystemLog->find('all', array('conditions' => $condition,'order' => 'SystemLog.id desc', 'limit' => $rownum, 'page' => $page));
        	$this->set('system_log_data',$system_log_data);
        	$this->set('title_for_layout', $this->ld['system_log'].' - '.$this->configs['shop_name']);
    }
}
