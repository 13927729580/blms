<?php
/** 
 *这是一个名为 QuotesController 的报价控制器.
 *
 *@var
 *@var
 *@var
 *@var 
 *@var
 *@var
 */
class QuotesController extends AppController
{
    public $name = 'Quotes';
    public $helpers = array('Html','Flash','Cache','Pagination');
    public $uses = array('Quote','QuoteProduct','InformationResource','User','UserRank','UserFans','Blog','UserApp','ProductTypeAttribute','Attribute','Product','ProductAttribute');
    public $components = array('RequestHandler','Cookie','Session','Captcha','Notify','Pagination');
    
    public function index($page=1,$limit=20){
		if(!in_array('Member',$this->SystemList)||!isset($this->system_modules['Member']['modules']['Quotation'])){
			Header("HTTP/1.1 404 Not Found");
			die();
		}
		if (isset($this->configs['open_enquiry']) && $this->configs['open_enquiry'] == 0) {
			$this->redirect('/');
		}
		//登录验证
		$this->checkSessionUser();
		$this->layout = 'usercenter'; 
		$user_id=$_SESSION['User']['User']['id'];
        	//获取我的信息
        	$user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
		  if ($user_list['User']['rank'] > 0) {
			$rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
			$user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
		  }
		  $this->set('user_list', $user_list);
	        //粉丝数量
	        $fans = $this->UserFans->find_fanscount_byuserid($user_id);
	        $this->set('fanscount', $fans);
	        //日记数量
	        $blog = $this->Blog->find_blogcount_byuserid($user_id);
	        $this->set('blogcount', $blog);
	        //关注数量
	        $focus = $this->UserFans->find_focuscount_byuserid($user_id);
	        $this->set('focuscount', $focus);
		//分享绑定显示判断
		$app_share = $this->UserApp->app_status();
		$this->set('app_share', $app_share);
		$this->pageTitle = $this->ld['quote'].' - '.$this->configs['shop_title'];
		//当前位置开始
		$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
		$this->ur_heres[] = array('name' => $this->ld['quote'], 'url' => '');
		$this->set('ur_heres', $this->ur_heres);
		$condition=array("Quote.user_id"=>$user_id,"Quote.is_sendmail"=>'1');
		$parameters['get'] = array();
		//地址路由参数（和control,action的参数对应）
		$parameters['route'] = array('controller' => 'quotes', 'action' => 'index', 'page' => $page, 'limit' => $limit);
		//分页参数
		$options = array('page' => $page, 'show' => $limit, 'modelClass' => 'Quote');
		$this->Pagination->init($condition, $parameters, $options); // Added
		$quote_data=$this->Quote->find('all',array('conditions'=>$condition,"page"=>$page,"limit"=>$limit,"order"=>"Quote.id desc"));
		$this->set('quote_data',$quote_data);
    }
    
    function view($id=0){
    		if(!in_array('Member',$this->SystemList)||!isset($this->system_modules['Member']['modules']['Quotation'])){
			Header("HTTP/1.1 404 Not Found");
			die();
		}
		if (isset($this->configs['open_enquiry']) && $this->configs['open_enquiry'] == 0) {
			$this->redirect('/');
		}
		//登录验证
		$this->checkSessionUser();
		$this->layout = 'usercenter'; 
		$user_id=$_SESSION['User']['User']['id'];
		//获取我的信息
		$user_list = $this->User->find('first', array('conditions' => array('User.id' => $_SESSION['User']['User']['id'])));
		if ($user_list['User']['rank'] > 0) {
			$rank_list = $this->UserRank->find('first', array('conditions' => array('UserRank.id' => $user_list['User']['rank'])));
			$user_list['User']['rank_name'] = $rank_list['UserRankI18n']['name'];
		}
		$this->set('user_list', $user_list);
		//粉丝数量
		$fans = $this->UserFans->find_fanscount_byuserid($user_id);
		$this->set('fanscount', $fans);
		//日记数量
		$blog = $this->Blog->find_blogcount_byuserid($user_id);
		$this->set('blogcount', $blog);
		//关注数量
		$focus = $this->UserFans->find_focuscount_byuserid($user_id);
		$this->set('focuscount', $focus);
		//分享绑定显示判断
		$app_share = $this->UserApp->app_status();
		$this->set('app_share', $app_share);
    		
    		$this->pageTitle = $this->ld['view'].' - '.$this->ld['quote'].' - '.$this->configs['shop_title'];
		//当前位置开始
		$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
		$this->ur_heres[] = array('name' => $this->ld['quote'], 'url' => '/quotes/');
		$this->ur_heres[] = array('name' => $this->ld['view'], 'url' => '');
		$this->set('ur_heres', $this->ur_heres);
		
		$condition=array("Quote.id"=>$id,"Quote.user_id"=>$user_id,"Quote.is_sendmail"=>'1');
		$quote_data=$this->Quote->find('first',array('conditions'=>$condition));
		if(!empty($quote_data)){
			if(!empty($quote_data['QuoteProduct'])){
				$product_attribute=array();
				$product_codes=array();
				foreach($quote_data['QuoteProduct'] as $v){
					$product_codes[]=$v['product_code'];
				}
				$product_info=$this->Product->find('all',array("fields"=>"Product.id,Product.code",'conditions'=>array('Product.code'=>$product_codes)));
				foreach($product_info as $v){
					if(isset($v['ProductAttribute'])&&!empty($v['ProductAttribute'])){
						$product_code=strtoupper($v['Product']['code']);
						foreach($v['ProductAttribute'] as $vv){
							$product_attribute[$product_code][$vv['attribute_id']]=$vv['attribute_value'];
						}
					}
				}
				$public_attr_ids = $this->ProductTypeAttribute->getattrids(0);
				$pubile_attr_info = $this->Attribute->find('all', array('conditions' => array('Attribute.id' => $public_attr_ids, 'Attribute.status' => 1), 'fields' => 'Attribute.id,AttributeI18n.name'));
				foreach($quote_data['QuoteProduct'] as $k=>$v){
					$product_code=strtoupper($v['product_code']);
					$quote_data['QuoteProduct'][$k]['attribute']=isset($product_attribute[$product_code])?$product_attribute[$product_code]:array();
				}
				$this->set('pubile_attr_info',$pubile_attr_info);
			}
			$this->set('quote_data',$quote_data);
		}else{
			$this->redirect("/quotes/index");
		}
    }
}
