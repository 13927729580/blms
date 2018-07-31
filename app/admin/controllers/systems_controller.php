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
 *这是一个名为 SystemsController 的控制器
 *	后台系统日志控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class SystemsController extends AppController
{
    public $name = 'Systems';
    public $components = array('RequestHandler','Cookie','Pagination');
    public $helpers = array('Html','Javascript','Pagination');
    public $uses = array('Operator','System','SystemModule');
    
    public function index($page=1){
    		$this->operator_privilege('log_management_view');
    		$this->operation_return_url(true);//设置操作返回页面地址
    		$this->menu_path = array('root' => '/web_application/','sub' => '/systems/');
    		
    		$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
        	$this->navigations[] = array('name' => '系统','url' => '/systems/');
        	
        	$system_info=$this->System->find('all',array('order'=>'id'));
        	$system_list=array();
        	if(!empty($system_info)){
        		$system_code_list=array();
        		foreach($system_info as $v){
        			$system_code_list[]=$v['System']['code'];
        		}
        		$system_module_info=$this->SystemModule->find('all',array('conditions'=>array('SystemModule.system_code'=>$system_code_list),'order'=>'system_code,id'));
        		$system_module_list=array();
        		foreach($system_module_info as $v){
        			$system_module_list[$v['SystemModule']['system_code']][]=$v['SystemModule'];
        		}
        		foreach($system_info as $v){
        			$v['SystemModule']=isset($system_module_list[$v['System']['code']])?$system_module_list[$v['System']['code']]:array();
        			$system_list[]=$v;
        		}
        	}
        	$this->set('system_list',$system_list);
        	
        	$this->set('title_for_layout', '系统'.' - '.$this->configs['shop_name']);
    }
    
    function toggle_status(){
		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
		$val = isset($_REQUEST['val'])?$_REQUEST['val']:0;
		$result = array();
		if (is_numeric($val) && $this->System->save(array('id' => $id, 'status' => $val))) {
			$result['flag'] = 1;
			$result['content'] = stripslashes($val);
			if ($this->configs['operactions-log'] == 1) {
				$this->log($this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['system']."(".$id.") ".$this->ld['log_batch_change_status'], 'operation');
			}
		}else{
			$result['flag'] = 0;
			$result['content'] = stripslashes($val);
		}
        	die(json_encode($result));
    }
    
    function toggle_module_status(){
    		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:0;
		$val = isset($_REQUEST['val'])?$_REQUEST['val']:0;
		$result = array();
		if (is_numeric($val) && $this->SystemModule->save(array('id' => $id, 'status' => $val))) {
			$result['flag'] = 1;
			$result['content'] = stripslashes($val);
			if ($this->configs['operactions-log'] == 1) {
				$this->log($this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['system'].$this->ld['module']."(".$id.") ".$this->ld['log_batch_change_status'], 'operation');
			}
		}else{
			$result['flag'] = 0;
			$result['content'] = stripslashes($val);
		}
        	die(json_encode($result));
    }
}
