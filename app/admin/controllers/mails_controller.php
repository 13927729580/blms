<?php

/*****************************************************************************
 * Seevia 邮件控制器
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id:
*****************************************************************************/
class MailsController extends AppController
{
    public $name = 'Mails';
    public $components = array('Pagination','RequestHandler','Phpexcel','Notify');
    public $helpers = array('Pagination','Html','Form','Javascript','Ckeditor');
    	var $uses = array("MailTemplate","MailTemplateI18n","Product","Brand","ProductAttribute","ProductTypeAttribute","SystemResource","ProductLocalePrice","NotifyTemplateType","UserCategory","User");

    public function send()
    {
        if ($this->RequestHandler->isPost()) {
		Configure::write('debug', 1);
		$this->layout = 'ajax';
            $receiver_email_arr = array();
            if (strpos($this->data['mail']['receiver_email'], ';')) {
                $receiver_email = explode(';', $this->data['mail']['receiver_email']);
                foreach ($receiver_email as $v) {
                    $receiver_email_arr[] = $v.';'.$v;
                }
            } else {
                $receiver_email_arr[] = $this->data['mail']['receiver_email'].';'.$this->data['mail']['receiver_email'];
            }
            $cc_email_arr = array();
            if (isset($this->data['mail']['cc_email']) && trim($this->data['mail']['cc_email']) != '') {
                if (strpos($this->data['mail']['cc_email'], ';')) {
                    $cc_email = explode(';', $this->data['mail']['cc_email']);
                    foreach ($cc_email as $v) {
                        $cc_email_arr[] = $v.';'.$v;
                    }
                } else {
                    $cc_email_arr[] = $this->data['mail']['cc_email'].';'.$this->data['mail']['cc_email'];
                }
            }
            $bcc_email_arr = array();
            if (isset($this->data['mail']['bcc_email']) && trim($this->data['mail']['bcc_email']) != '') {
                if (strpos($this->data['mail']['bcc_email'], ';')) {
                    $bcc_email = explode(';', $this->data['mail']['bcc_email']);
                    foreach ($bcc_email as $v) {
                        $bcc_email_arr[] = $v.';'.$v;
                    }
                } else {
                    $bcc_email_arr[] = $this->data['mail']['bcc_email'].';'.$this->data['mail']['bcc_email'];
                }
            }
            $mailsendqueue = array(
                'sender_name' => $this->configs['shop_name'],//发送从姓名
                'receiver_email' => implode(chr(13).chr(10),$receiver_email_arr),//接收人姓名;接收人地址
                'cc_email' => implode(chr(13).chr(10),$cc_email_arr),//抄送人
                'bcc_email' => implode(chr(13).chr(10),$bcc_email_arr),//抄送人
                'title' => $this->data['mail']['title'],//主题
                'html_body' => $this->data['mail']['html_body'],//内容
                'text_body' => '',//内容
                'sendas' => 'html',
            );
            $mailsendname = '';
            $mail_result=$this->Notify->phpmailer_send($mailsendqueue,$this->configs,($this->admin['name'].";".$this->admin['email']));
            $results['code'] = 0;
            $results['msg'] = '';
            if ($mail_result === true) {
                $results['code'] = 1;
                $results['msg'] = '';
            } else {
                $results['code'] = 0;
                $results['msg'] = $result;
            }
            die(json_encode($results));
        }
    }

    public function send_product_mail($id)
    {
        if (empty($id)) {
            $this->redirect('/products/');
        }
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 1);
		$this->layout = 'ajax';
            $receiver_email_arr = array();
            if(isset($_REQUEST['user_category_id'])&&intval($_REQUEST['user_category_id'])>0){
            	$conditions=array();
			$conditions['User.category_id']=intval($_REQUEST['user_category_id']);
			$conditions['User.email <>']='';
			$conditions['User.status']='1';
			$receiver_email_arr=$this->User->find('list',array('conditions'=>$conditions,'fields'=>'id,email'));
            }else{
	            if (strpos($this->data['mail']['receiver_email'], ';')) {
	                $receiver_email = explode(';', $this->data['mail']['receiver_email']);
	                foreach ($receiver_email as $v) {
	                    $receiver_email_arr[] = $v.';'.$v;
	                }
	            } else {
	                $receiver_email_arr[] = $this->data['mail']['receiver_email'].';'.$this->data['mail']['receiver_email'];
	            }
            }
            $cc_email_arr = array();
            if (isset($this->data['mail']['cc_email']) && trim($this->data['mail']['cc_email']) != '') {
                if (strpos($this->data['mail']['cc_email'], ';')) {
                    $cc_email = explode(';', $this->data['mail']['cc_email']);
                    foreach ($cc_email as $v) {
                        $cc_email_arr[] = $v.';'.$v;
                    }
                } else {
                    $cc_email_arr[] = $this->data['mail']['cc_email'].';'.$this->data['mail']['cc_email'];
                }
            }
            $bcc_email_arr = array();
            if (isset($this->data['mail']['bcc_email']) && trim($this->data['mail']['bcc_email']) != '') {
                if (strpos($this->data['mail']['bcc_email'], ';')) {
                    $bcc_email = explode(';', $this->data['mail']['bcc_email']);
                    foreach ($bcc_email as $v) {
                        $bcc_email_arr[] = $v.';'.$v;
                    }
                } else {
                    $bcc_email_arr[] = $this->data['mail']['bcc_email'].';'.$this->data['mail']['bcc_email'];
                }
            }
            $mailsendqueue = array(
                'sender_name' => $this->configs['shop_name'],//发送从姓名
                'receiver_email' => implode(chr(13).chr(10),$receiver_email_arr),//接收人姓名;接收人地址
                'cc_email' => implode(chr(13).chr(10),$cc_email_arr),//抄送人
                'bcc_email' => implode(chr(13).chr(10),$bcc_email_arr),//抄送人
                'title' => $this->data['mail']['title'],//主题
                'html_body' => $this->data['mail']['html_body'],//内容
                'text_body' => '',//内容
                'sendas' => 'html',
            );
            $mailsendname = $this->admin['name'].';'.$this->admin['email'];
            $result = $this->Notify->phpmailer_send($mailsendqueue,$this->configs,$mailsendname);
            $results['code'] = 0;
            $results['msg'] = '';
            if ($result === true) {
                $results['code'] = 1;
                $results['msg'] = '';
            } else {
                $results['code'] = 0;
                $results['msg'] = $result;
            }
            die(json_encode($results));
        }
	$product_ids = explode(',', $id);
	$this->MailTemplate->set_locale($this->backend_locale);
	$template = $this->MailTemplate->find('first', array('conditions' => array('code' => 'product_send_mail', 'status' => 1)));
	$shop_name = $this->configs['shop_name'];
	$this->NotifyTemplateType->set_locale($this->backend_locale);
	$totify_template=$this->NotifyTemplateType->typeformat("quotes_product","email");
        $this->navigations[] = array('name' => $this->ld['log_send_email'],'url' => '');
        $this->set('navigations', $this->navigations);
        $this->set('id', $id);

        $cond = array();
        $conditions = array();
        $conditions['Product.id'] = $product_ids;
        $cond['conditions'] = $conditions;
        if ($this->configs['product_order'] == 'category') {
            $cond['order'] = 'Product.category_id,Product.id';
        } else {
            $cond['order'] = 'Product.'.$this->configs['product_order'];
        }
        $cond['fields'] = array('Product.id','Product.code','ProductI18n.name','Product.brand_id','Product.quantity','Product.max_buy','Product.shop_price');
        $this->Product->hasOne = array('ProductI18n' => array(
                'className' => 'ProductI18n',
                'order' => '',
                'dependent' => true,
                'foreignKey' => 'product_id',
            ),
        );
        $this->Product->set_locale($this->backend_locale);
        $product_list = $this->Product->find('all', $cond);
	  
        $this->loadModel('ProductTypeAttribute');
        $this->loadModel('Attribute');
        
        //取出所有公共属性
        $this->Attribute->set_locale($this->backend_locale);
        $public_attr_ids = $this->ProductTypeAttribute->getattrids(0);
        $this->Attribute->set_locale($this->backend_locale);
        $public_attr_info = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $public_attr_ids, 'Attribute.status' => 1), 'fields' => 'Attribute.id,AttributeI18n.name'));
        $p_ids = array();
        if (!empty($product_list)) {
            foreach ($product_list as $p) {
                $p_ids[] = $p['Product']['id'];
            }
        }
        $attr_info = $this->ProductAttribute->product_list_format($p_ids, $public_attr_ids, $this->backend_locale);
        $is_pro_city_price = false;
        /*
            多地区商品价格
        */
        if(isset($this->configs['product_location_price'])&&$this->configs['product_location_price']=='1'){
            $is_pro_city_price = true;
            if (!empty($p_ids)) {
			$systemresource_info = $this->SystemResource->resource_formated(array("product_location"),$this->backend_locale);
			$pro_city_price=$this->ProductLocalePrice->get_pro_city_price_list(array("product_id"=>$p_ids));
			$pro_city = isset($systemresource_info['product_location'])?$systemresource_info['product_location']:array();
            }
        }

        $brand_names = array();
        //品牌获取
        $brand_tree = $this->Brand->brand_tree($this->backend_locale);
        if (!empty($brand_tree) && is_array($brand_tree)) {
            foreach ($brand_tree as $k => $v) {
                $brand_names[$v['Brand']['id']] = $v['Brand']['id'];
                $brand_names[$v['Brand']['id']] = $v['BrandI18n']['name'];
            }
        }
        $html_mail_content = "<table style='border:1px solid black;'cellspacing='0' cellpadding='0'>
			<tr>
				<td style='border:1px solid black;padding:5px;'>Part Number</td>
				<td style='border:1px solid black;width:60px;padding:5px;'>Mfg</td>
				<td style='border:1px solid black;width:140px;padding:5px;'>Desc</td>
				<td style='border:1px solid black;padding:5px;'>Qty Rfq</td>
				<td style='border:1px solid black;width:75px;padding:5px;'>Offer Qty</td>
				<td style='border:1px solid black;width:90px;padding:5px;'>Offer Price</td>
				<td style='border:1px solid black;width:100px;padding:5px;'>Customer Target Price</td>
				<td style='border:1px solid black;width:110px;padding:5px;'>Payment Terms</td>";
		  foreach($public_attr_info as $v){
		  		$html_mail_content.="<td style='border:1px solid black;padding:5px;'>".$v['AttributeI18n']['name']."</td>";
		  }
	$html_mail_content.="</tr>";
        foreach ($product_list as $v) {
            $html_content = "<tr  style='border:1px solid black;'>
				<td style='border:1px solid black;padding:5px;'>".$v['Product']['code']."</td>
				<td style='border:1px solid black;padding:5px;'>".(isset($brand_names[$v['Product']['brand_id']]) ? $brand_names[$v['Product']['brand_id']] : '')."</td>
				<td style='border:1px solid black;padding:5px;'>".$v['ProductI18n']['name']."</td>
				<td style='border:1px solid black;padding:5px;'></td><td style='border:1px solid black;padding:5px;'>".$v['Product']['max_buy'].'</td>';
            if ($is_pro_city_price) {
                $html_content .= "<td  style='border:1px solid black;padding:5px;'>";
                if (isset($pro_city) && sizeof($pro_city) > 0) {
                    foreach ($pro_city as $kk => $vv) {
                        $_city_name = $vv.':';
                        $_pro_city_price = isset($pro_city_price[$v['Product']['id']][$kk]['ProductLocalePrice']['product_price']) ? $pro_city_price[$v['Product']['id']][$kk]['ProductLocalePrice']['product_price'] : '0';
                        $html_content .= $_city_name.$_pro_city_price.'&nbsp;';
                    }
                }
                $html_content .= '</td>';
            } else {
                $html_content .= "<td style='border:1px solid black;padding:5px;'>".$v['Product']['shop_price'].'</td>';
            }
            $html_content .= "<td style='border:1px solid black;padding:5px;'>&nbsp;</td>";
            foreach($public_attr_info as $vv){
	  		$html_content.="<td style='border:1px solid black;padding:5px;'>".(isset($attr_info[$v['Product']['id']][$vv['Attribute']['id']])?$attr_info[$v['Product']['id']][$vv['Attribute']['id']]:'-')."</td>";
	      }
            $html_content .= '</tr>';
            $html_mail_content .= $html_content;
        }
        	$html_mail_content .= '</table>';
		$operator = $this->admin['name'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$this->admin['email'];
		$subject = $totify_template['email']['NotifyTemplateTypeI18n']['title'];
		$subject = str_replace('$shop_name', $shop_name, $subject);
		$html_body=$totify_template['email']['NotifyTemplateTypeI18n']['param01'];
		$html_body = str_replace('$customer_name', '', $html_body);
		$html_body = str_replace('$contact_person', '', $html_body);
		$html_body = str_replace('$sent_date', date("Y-m-d H:i:s"), $html_body);
		$html_body = str_replace('$email', '', $html_body);
		$html_body = str_replace('$quoted_by', $operator, $html_body);
		$html_body = str_replace('$remark', '', $html_body);
		$html_body = str_replace('$mail_title', '', $html_body);
		$html_body = str_replace('$products_info', $html_mail_content, $html_body);
        	$this->set('html_body', $html_body);
        	
        	$user_category_ids=$this->User->find('list',array('fields'=>'id,category_id','conditions'=>array('User.category_id >'=>0,'email <>'=>'','status'=>'1')));
        	if(!empty($user_category_ids)){
        		$user_category_ids=array_unique($user_category_ids);
        	}
        	$user_category_list=$this->UserCategory->find('list',array('fields'=>'id,name','conditions'=>array('UserCategory.status'=>'1','UserCategory.id'=>$user_category_ids)));
        	$this->set('user_category_list',$user_category_list);
    }
    
    function ajax_category_user(){
    		Configure::write('debug', 1);
		$this->layout = 'ajax';
		$conditions=array();
		if(isset($_REQUEST['user_category_id'])&&intval($_REQUEST['user_category_id'])>0){
			$conditions['User.category_id']=intval($_REQUEST['user_category_id']);
		}else{
			$conditions['User.id']=0;
		}
		$conditions['User.email <>']='';
		$conditions['User.status']='1';
		$user_list=$this->User->find('list',array('conditions'=>$conditions,'fields'=>'id,email'));
		die(json_encode($user_list));
    }
}
