<?php
/** 
 *这是一个名为 UserProductSubscriptionsController 的商品订阅控制器.
 *
 *@var
 *@var
 *@var
 *@var 
 *@var
 *@var
 */
uses('sanitize');
class UserProductSubscriptionsController extends AppController
{
    public $name = 'UserProductSubscriptions';
    public $helpers = array('Html','Flash','Cache','Pagination');
    public $uses = array('Brand','CategoryProduct','ProductType','Attribute','ProductTypeAttribute','UserProductSubscription','InformationResource','User','UserRank','UserFans','Blog','UserApp');
    public $components = array('RequestHandler','Cookie','Session','Captcha','Notify','Pagination');
    
    public function index(){
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
        	$id = $_SESSION['User']['User']['id'];
	        //粉丝数量
	        $fans = $this->UserFans->find_fanscount_byuserid($id);
	        $this->set('fanscount', $fans);
	        //日记数量
	        $blog = $this->Blog->find_blogcount_byuserid($id);
	        $this->set('blogcount', $blog);
	        //关注数量
	        $focus = $this->UserFans->find_focuscount_byuserid($id);
	        $this->set('focuscount', $focus);
		//分享绑定显示判断
		$app_share = $this->UserApp->app_status();
		$this->set('app_share', $app_share);
		
		$this->pageTitle = $this->ld['product_subscription'].' - '.$this->configs['shop_title'];
		//当前位置开始
		$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
		$this->ur_heres[] = array('name' => $this->ld['product_subscription'], 'url' => '');
		$this->set('ur_heres', $this->ur_heres);
		
		$data = $this->UserProductSubscription->find('all',array('conditions'=>array('UserProductSubscription.user_id'=>$user_id)));
		$this->set('data',$data);
		
		$informationresource_infos = $this->InformationResource->code_information_formated(array('product_subscription'), $this->locale);
		$this->set('informationresource_infos',$informationresource_infos);
    }
    
    public function remove($id=0){
	    	$this->checkSessionUser();
	    	Configure::write('debug',1);
	    	$this->layout="ajax";
	    	$user_id=$_SESSION['User']['User']['id'];
	    	$this->UserProductSubscription->delete(array('UserProductSubscription.id'=>$id,'UserProductSubscription.user_id'=>$user_id));
	    	$this->redirect('index');
    }
   
     public function update_status($id=0,$status=1)
     {
		$this->checkSessionUser();
		Configure::write('debug',1);
		$this->layout="ajax";
		$user_id=$_SESSION['User']['User']['id'];
		$hp = $this->UserProductSubscription->find('first',array('conditions'=>array('UserProductSubscription.id'=>$id))); 	
		$result = array();
		$result['flag']=0;
		if(!empty($hp)){
			$u_id = $hp['UserProductSubscription']['user_id'];
			if($user_id==$u_id){
				$data=array(
					'id'=>$id,
					'status'=>$status,
					'UserProductSubscription.user_id'=>$user_id
				);
				$res = $this->UserProductSubscription->save(array('UserProductSubscription'=>$data));
				$result['flag']=1;
			}
		}
		die(json_encode($result));
    } 
    public function view($id=0){
    		// 登录验证 
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
		
		$this->pageTitle = $this->ld['product_subscription'].' - '.$this->configs['shop_title'];
		//当前位置开始
		$this->ur_heres[] = array('name' => $this->ld['user_center'], 'url' => '/users/index');
		$this->ur_heres[] = array('name' => $this->ld['product_subscription'], 'url' => '/user_product_subscriptions/index');
		$this->ur_heres[] = array('name' => $this->ld['edit'], 'url' => '');
		$this->set('ur_heres', $this->ur_heres);
		
		if ($this->RequestHandler->isPost()) {
			if(!empty($this->data['UserProductSubscription'])){
				$this->data['UserProductSubscription']['user_id']=$user_id;
				if(isset($this->data['UserProductSubscription']['brand'])){
					$this->data['UserProductSubscription']['brand']=implode(",",$this->data['UserProductSubscription']['brand']);
				}else{
					$this->data['UserProductSubscription']['brand']="";
				}
				if(isset($this->data['UserProductSubscription']['category'])){
					$this->data['UserProductSubscription']['category']=implode(",",$this->data['UserProductSubscription']['category']);
				}else{
					$this->data['UserProductSubscription']['category']="";
				}
				if(isset($this->data['UserProductSubscription']['product_type'])){
					$this->data['UserProductSubscription']['product_type']=implode(",",$this->data['UserProductSubscription']['product_type']);
				}else{
					$this->data['UserProductSubscription']['product_type']="";
				}
				if(isset($this->data['UserProductSubscription']['attribute_value'])){
					$this->data['UserProductSubscription']['attribute_value']=implode(chr(13).chr(10),$this->data['UserProductSubscription']['attribute_value']);
				}else{
					$this->data['UserProductSubscription']['attribute_value']="";
				}
				$this->UserProductSubscription->save($this->data['UserProductSubscription']);
			}
			$this->redirect('/user_product_subscriptions/index');
		}
		$brand_infos=$this->Brand->find('all', array('fields' => 'Brand.id,BrandI18n.name', 'conditions' => array('Brand.status' =>'1')));
		$category_infos=$this->CategoryProduct->tree("P");
		$category_data=isset($category_infos['tree'])?$category_infos['tree']:array();
		$product_type_infos=$this->ProductType->find('all',array('fields' => 'ProductType.id,ProductTypeI18n.name','conditions'=>array('ProductType.status'=>'1')));
		$public_attribute_ids=$this->ProductTypeAttribute->find('list',array('fields'=>"ProductTypeAttribute.id,attribute_id",'conditions'=>array('ProductTypeAttribute.product_type_id'=>0)));
		$public_attribute_infos=array();
		if(!empty($public_attribute_ids)){
			$public_attribute_infos=$this->Attribute->find('all',array('fields' => 'Attribute.id,AttributeI18n.name', 'conditions'=>array('Attribute.id'=>$public_attribute_ids,"Attribute.status"=>'1'),"order"=>"Attribute.id"));
		}
		$this->set('brand_data',$brand_infos);
		$this->set('category_data',$category_data);
		$this->set('product_type_data',$product_type_infos);
		$this->set('public_attribute_data',$public_attribute_infos);
		$informationresource_infos = $this->InformationResource->code_information_formated(array('product_subscription'), $this->locale);
		$this->set('informationresource_infos',$informationresource_infos);
		
		$data = $this->UserProductSubscription->find('first',array('conditions'=>array('UserProductSubscription.id' =>$id,'UserProductSubscription.user_id'=>$user_id)));
		if(!empty($data)){
			$data['UserProductSubscription']['brand']=explode(',', $data['UserProductSubscription']['brand']);
			$data['UserProductSubscription']['product_type'] = explode(',',$data['UserProductSubscription']['product_type']);
			$data['UserProductSubscription']['category'] =  explode(',',$data['UserProductSubscription']['category']);
			$data['UserProductSubscription']['attribute_value'] = explode(chr(13).chr(10),$data['UserProductSubscription']['attribute_value']);
		}
		$this->set('data',$data);
    } 
    
    function ajax_product_attribute(){
    		Configure::write('debug', 1);
    		$this->layout="ajax";
    		$result=array();
    		$result['code']='0';
    		$result['data']="";
    		$product_type=isset($_POST['product_type_id'])?$_POST['product_type_id']:"";
    		$product_type_id=explode(",",$product_type);
    		$product_type_id[]=0;
    		$product_attribute_ids=$this->ProductTypeAttribute->find('list',array('fields'=>"ProductTypeAttribute.id,attribute_id",'conditions'=>array('ProductTypeAttribute.product_type_id'=>$product_type_id)));
    		$attribute_infos=$this->Attribute->find('all',array('fields' => 'Attribute.id,AttributeI18n.name', 'conditions'=>array('Attribute.id'=>$product_attribute_ids,"Attribute.status"=>'1'),"order"=>"Attribute.id"));
		if(!empty($attribute_infos)){
			$result['code']='1';
    			$result['data']=$attribute_infos;
		}
		die(json_encode($result));
    }
}
