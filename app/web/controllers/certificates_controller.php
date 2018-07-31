<?php
/*****************************************************************************
 * Seevia 证书
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/*
 *这是一个名为 CertificatesController 的控制器
 *文章控制器.

*/
class CertificatesController extends AppController{
	public $name = 'Certificates';
	public $components = array('Pagination');
	public $uses = array('InformationResource','Certificate');
	public $helpers = array('Pagination');
	
	function index(){
		$this->pageTitle = '证书查询 - '.$this->configs['shop_title'];
		$this->ur_heres[] = array('name' => '证书查询','url' => '');
	}
	
	function ajax_certificate_list(){
		Configure::write('debug', 1);
        	$this->layout = 'ajax';
        	
        	$condition=array();
        	if(isset($_POST['certificate_type'])&&$_POST['certificate_type']=='0'){
        		if(isset($_POST['certificate_number'])&&$_POST['certificate_number']!=''){
	        		$condition['Certificate.identity_no'] = $_POST['certificate_number'];
	        	}
        	}else if(isset($_POST['certificate_type'])&&$_POST['certificate_type']=='1'){
        		if(isset($_POST['certificate_number'])&&$_POST['certificate_number']!=''){
        			$condition['Certificate.certificate_number'] = $_POST['certificate_number'];
        		}
        	}
        	if(!empty($condition)){
	        	$certificate_infos = $this->Certificate->find('all', array('conditions' => $condition, 'order' => 'Certificate.register_date desc'));
	        	$this->set('certificate_infos',$certificate_infos);
	        	
	        	$informationresource_info = $this->InformationResource->code_information_formated(array('certificatetype'), $this->locale);
	        	$this->set('informationresource_info', $informationresource_info);
        	}
	}
}