<?php

/*****************************************************************************
 * Seevia 询价
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为EnquiryController的控制器
 *控制联系方式.
 *
 *@var
 *@var
 *@var
 *@var
 */
class EnquiriesController extends AppController
{
    public $name = 'Enquiries';
    public $helpers = array('Html');
    public $uses = array('User','NotifyTemplateType','Enquiry','MailSendQueue','InformationResourceI18n','ApplicationConfig','ApplicationConfigI18n','Product','BrandI18n','ProductTypeAttribute','Attribute');
    public $components = array('RequestHandler','Notify');
    /*
     *函数index 询价单页面
     *@param	product_id 商品id
    */
    public function index($product_id = 0){
	if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
		Configure::write('debug', 0);
		$this->layout = 'ajax';
	}
	$_GET=$this->clean_xss($_GET);
	$product_id=intval($product_id);
	if(!in_array('Member',$this->SystemList)||!isset($this->system_modules['Member']['modules']['Quotation'])){
		Header("HTTP/1.1 404 Not Found");
		die();
	}
	if (isset($this->configs['open_enquiry']) && $this->configs['open_enquiry'] == 0) {
		$this->redirect('/');
	}
        if(isset($_GET['product_id'])&&trim($_GET['product_id'])!=''){
        	$product_id =explode(',',$_GET['product_id']);
        }
        $attr = isset($_GET['attr']) ? $_GET['attr'] : '';
        $this->pageTitle = $this->ld['enquiry_form'].' - '.$this->configs['shop_name'];
        $this->ur_heres[] = array('name' => $this->ld['enquiry'], 'url' => '');
        $this->set('product_id', $product_id);
        
	$public_attr_ids = $this->ProductTypeAttribute->getattrids(0);
	$pubile_attr_info = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $public_attr_ids), 'fields' => 'Attribute.id,Attribute.code,AttributeI18n.name'));
       if (!empty($product_id)) {
            //查询商品货号及价格
            $pro_info = $this->Product->find('all', array('conditions' => array('Product.id' => $product_id), 'fields' => 'Product.id,Product.brand_id,Product.code,Product.img_thumb,ProductI18n.name,Product.shop_price'));
            if(!empty($pro_info)){
            		$brand_ids=array();
            		$public_product_attributes=array();
            		foreach($pro_info as $v){
            			$brand_ids[$v['Product']['brand_id']]=$v['Product']['brand_id'];
            			if(!empty($v['ProductAttribute'])){
            				foreach($v['ProductAttribute'] as $vv){
            					$public_product_attributes[$vv['product_id']][$vv['attribute_id']]=$vv['attribute_value'];
            				}
            			}
            		}
            		$this->set('public_product_attributes',$public_product_attributes);
            		if(isset($brand_ids[0]))unset($brand_ids[0]);
            		if(!empty($brand_ids)){
            			$brand_list=$this->BrandI18n->find('list',array('conditions'=>array('BrandI18n.brand_id'=>$brand_ids,'BrandI18n.locale'=>LOCALE),'fields'=>'BrandI18n.brand_id,BrandI18n.name'));
            			$this->set('brand_list',$brand_list);
            		}
            		$this->set('pro_info',$pro_info);
            }
        }
        if ($this->RequestHandler->isPost()) {
		Configure::write('debug', 0);
		$this->layout = 'ajax';
		$this->data=$this->clean_xss($this->data);
		$currency = $this->ld['RMB'];
		$this->data['Enquiry']['company_type'] = 0;
            //联系人
            if (!empty($this->data['Enquiry']['user_id'])) {
                $user_info = $this->User->find('first', array('conditions' => array('User.id' => $this->data['Enquiry']['user_id']), 'fields' => 'User.name,User.mobile,User.email'));
                if (empty($this->data['Enquiry']['contact_person'])) {
                    $this->data['Enquiry']['contact_person'] = $user_info['User']['name'];
                }
                if (empty($this->data['Enquiry']['tel1'])) {
                    $this->data['Enquiry']['tel1'] = $user_info['User']['mobile'];
                }
                if (empty($this->data['Enquiry']['email'])) {
                    $this->data['Enquiry']['email'] = $user_info['User']['email'];
                }
            }
            $this->data['Enquiry']['ip_address'] = $this->real_ip();
            $this->data['Enquiry']['browser'] = $this->getbrowser();
            $this->data['Enquiry']['locale'] = LOCALE;
            if (is_array($this->data['Enquiry']['part_num'])&&count($this->data['Enquiry']['part_num']) > 1) {
                $this->data['Enquiry']['part_num'] = implode(';', $this->data['Enquiry']['part_num']);
            } else {
                $this->data['Enquiry']['part_num'] = $this->data['Enquiry']['part_num'][0];
            }
            if(isset($this->data['Enquiry']['brand'])){
	            if (is_array($this->data['Enquiry']['brand'])&&count($this->data['Enquiry']['brand']) > 1) {
	                $this->data['Enquiry']['brand'] = implode(';', $this->data['Enquiry']['brand']);
	            } else {
	                $this->data['Enquiry']['brand'] = $this->data['Enquiry']['brand'][0];
	            }
            }
            if(isset($this->data['Enquiry']['brand'])){
	            if (is_array($this->data['Enquiry']['brand'])&&count($this->data['Enquiry']['brand']) > 1) {
	                $this->data['Enquiry']['brand'] = implode(';', $this->data['Enquiry']['brand']);
	            } else {
	                $this->data['Enquiry']['brand'] = $this->data['Enquiry']['brand'][0];
	            }
            }
            if (is_array($this->data['Enquiry']['attribute'])&&count($this->data['Enquiry']['attribute']) > 1) {
                $this->data['Enquiry']['attribute'] = implode(';', $this->data['Enquiry']['attribute']);
            } else {
                $this->data['Enquiry']['attribute'] = $this->data['Enquiry']['attribute'][0];
            }
            if (is_array($this->data['Enquiry']['qty'])&&count($this->data['Enquiry']['qty']) > 1) {
                $this->data['Enquiry']['qty'] = implode(';', $this->data['Enquiry']['qty']);
            } else {
                $this->data['Enquiry']['qty'] = $this->data['Enquiry']['qty'][0];
            }
            if (is_array($this->data['Enquiry']['target_price'])&&count($this->data['Enquiry']['target_price']) > 1) {
                $this->data['Enquiry']['target_price'] = implode(';', $this->data['Enquiry']['target_price']);
            } else {
                $this->data['Enquiry']['target_price'] = $this->data['Enquiry']['target_price'][0];
                $price = $this->data['Enquiry']['target_price'];
            }
            //存储数据
            $request = $this->Enquiry->save($this->data['Enquiry']);
            if (isset($_POST['is_ajax'])) {
                if ($request) {
                    $result['flag'] = 1;
                    $result['content'] = $this->ld['information_submitted'].$this->configs['contactus_conversion'];
                }
                die(json_encode($result));
            }
            //发送询价接收通知邮件
            if ($request && isset($this->configs['enquiry-email']) && !empty($this->configs['enquiry-email'])) {
                $send_date = date('Y-m-d');
                $shop_name = $this->configs['shop_name'];
                //模板code查询
                $totify_template=$this->NotifyTemplateType->typeformat("enquiry_email","email");
                if(!empty($totify_template['email'])){
			$email_product_info = '<table width="400" border="0" cellspacing="0" cellpadding="0"><thead><td>'.$this->ld['sku'].'</td><td>'.$this->ld['attribute'].'</td><td>'.$this->ld['price'].'</td><td>'.$this->ld['qty_f'].'</td></thead>';
			$part_num_arr = explode(';', $this->data['Enquiry']['part_num']);
			$attribute_arr = explode(';', $this->data['Enquiry']['attribute']);
			$target_price_arr = explode(';', $this->data['Enquiry']['target_price']);
			$qty_arr = explode(';', $this->data['Enquiry']['qty']);
			for ($i = 0;$i < count($part_num_arr);++$i) {
				$email_product_info .= '<tr><td>'.$part_num_arr[$i].'</td><td>'.$attribute_arr[$i].'</td><td>'.$target_price_arr[$i].'</td><td>'.$qty_arr[$i].'</td></tr>';
			}
			$email_product_info .= '</table>';
                	$remark = $this->data['Enquiry']['remark'];
                	$shop_url = $this->server_host.$this->webroot;
                	
			$subject = $totify_template['email']['NotifyTemplateTypeI18n']['title'];
			$subject = str_replace('$shop_name', $shop_name, $subject);
			$html_body = addslashes($totify_template['email']['NotifyTemplateTypeI18n']['param01']);
                	$html_body = str_replace('$consignee', $_SESSION['User']['User']['name'], $html_body);
                	$html_body = str_replace('$formated_add_time', DateTime, $html_body);
			$html_body = str_replace('$remark', $remark, $html_body);
			$html_body = str_replace('$sent_date', $send_date, $html_body);
			$html_body = str_replace('$products_info', $email_product_info, $html_body);
			$html_body = str_replace('$shop_url', $shop_url, $html_body);
			$html_body = str_replace('$shop_name', $shop_name, $html_body);
                	$receiver_email=$this->configs['enquiry-email'];
                	$mail_send_queue = array(
                                'id' => '',
                                'sender_name' => $shop_name,
                                'receiver_email' => $receiver_email,
                                'cc_email' => ';',
                                'bcc_email' => ';',
                                'title' => $subject,
                                'html_body' => $html_body,
                                'text_body' => $html_body,
                                'sendas' => 'html',
                                'flag' => 0,
                                'pri' => 0
                      );
                      $this->Notify->send_email($mail_send_queue, $this->configs);
                }
            }
            $url = $this->data['Enquiry']['product_id'] == 0 ? '/' : '/products/'.$this->data['Enquiry']['product_id'];
            $msg = isset($this->configs['contactus_conversion']) && $this->configs['contactus_conversion'] != '' ? $this->configs['contactus_conversion'] : $this->ld['information_submitted'];
            $this->layout = 'ajax';
            $result['flag'] = 2;
            $result['content'] = $msg;
            $result['url'] = $url;
            die(json_encode($result));
        }
    }

    /**
     *实际id.
     */
    public function real_ip()
    {
        static $realip = null;
        if ($realip !== null) {
            return $realip;
        }
        if (isset($_SERVER)) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                /* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */
                foreach ($arr as $ip) {
                    $ip = trim($ip);
                    if ($ip != 'unknown') {
                        $realip = $ip;
                        break;
                    }
                }
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $realip = $_SERVER['HTTP_CLIENT_IP'];
            } else {
                if (isset($_SERVER['REMOTE_ADDR'])) {
                    $realip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $realip = '0.0.0.0';
                }
            }
        } else {
            if (getenv('HTTP_X_FORWARDED_FOR')) {
                $realip = getenv('HTTP_X_FORWARDED_FOR');
            } elseif (getenv('HTTP_CLIENT_IP')) {
                $realip = getenv('HTTP_CLIENT_IP');
            } else {
                $realip = getenv('REMOTE_ADDR');
            }
        }
        preg_match("/[\d\.]{7,15}/", $realip, $onlineip);
        $realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';

        return $realip;
    }

    /**
     *获得游览器.
     */
    public function getbrowser()
    {
        global $_SERVER;
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $browser = '';
        $browser_ver = '';
        if (preg_match('/OmniWeb\/(v*)([^\s|;]+)/i', $agent, $regs)) {
            $browser = 'OmniWeb';
            $browser_ver = $regs[2];
        }
        if (preg_match('/Netscape([\d]*)\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'Netscape';
            $browser_ver = $regs[2];
        }
        if (preg_match('/safari\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'Safari';
            $browser_ver = $regs[1];
        }
        if (preg_match('/MSIE\s([^\s|;]+)/i', $agent, $regs)) {
            $browser = 'Internet Explorer';
            $browser_ver = $regs[1];
        }
        if (preg_match('/Opera[\s|\/]([^\s]+)/i', $agent, $regs)) {
            $browser = 'Opera';
            $browser_ver = $regs[1];
        }
        if (preg_match('/NetCaptor\s([^\s|;]+)/i', $agent, $regs)) {
            $browser = '(Internet Explorer '.$browser_ver.') NetCaptor';
            $browser_ver = $regs[1];
        }
        if (preg_match('/Maxthon/i', $agent, $regs)) {
            $browser = '(Internet Explorer '.$browser_ver.') Maxthon';
            $browser_ver = '';
        }
        if (preg_match('/FireFox\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'FireFox';
            $browser_ver = $regs[1];
        }
        if (preg_match('/Lynx\/([^\s]+)/i', $agent, $regs)) {
            $browser = 'Lynx';
            $browser_ver = $regs[1];
        }
        if ($browser != '') {
            return $browser.' '.$browser_ver;
        } else {
            return 'Unknow browser';
        }
    }
}
