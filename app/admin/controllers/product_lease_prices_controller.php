<?php

/**
 *这是一个名为 ProductLeasePricesController 的控制器.
 *
 *@var
 *@var
 *@ 租赁参数设置 2012/02/17
 */
class ProductLeasePricesController extends AppController
{
    	public $name = 'ProductLeasePrices';
    	public $uses = array('CategoryType','ProductLeasePrice','CategoryTypeI18n','CategoryTypeRelation','OperatorLog','ProductStyle','ProductStyleI18n','StyleTypeGroup','ProductType','ProductTypeI18n');

	//租赁参数设置管理——列表显示
	public function index()
	{
		$this->operator_privilege('product_view');
		$this->menu_path = array('root' => '/product/','sub' => '/products/');
		$this->navigations[] = array('name' => $this->ld['manager_products'],'url' => '');
		$this->navigations[] = array('name' => $this->ld['manage_products'],'url' => '/products/');
		$this->navigations[] = array('name' => $this->ld['lease_parameter'].$this->ld['set_up'],'url' => '');
		$this->set('title_for_layout', $this->ld['lease_parameter'].$this->ld['set_up'].' - '.$this->configs['shop_name']);
		$product_lease_price_infos=$this->ProductLeasePrice->find('all',array('order'=>'ProductLeasePrice.created DESC'));
		$this->set('product_lease_price_infos',$product_lease_price_infos);
	}
	
	//租赁参数设置管理——编辑操作	
	public function view()
	{
		$this->operator_privilege('product_edit');
		$id= isset($_POST['Id']) ? $_POST['Id'] : 0;
		$product_lease_price_info = $this->ProductLeasePrice->find('first', array('conditions' => array('ProductLeasePrice.id' =>$id)));
		$this->set('product_lease_price_info', $product_lease_price_info);
		Configure::write('debug', 0);
		$this->layout = 'ajax';
		if (!empty($this->data)) 
		{	
	    	 	$result['code'] = 0;
	      	if ($this->ProductLeasePrice->save($this->data))
	             {
	                  $result['code'] = 1;
	             }
	             die(json_encode($result));
	       }
	}
	
//租赁参数设置管理——删除操作
	public function remove()
	{
		$this->operator_privilege('product_edit');
		Configure::write('debug', 0);
	 	$this->layout = 'ajax';
		$result['code'] = 0;
        	$del_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
        	if ($this->ProductLeasePrice->delete(array('id' => $del_id))) 
        	{ 
          	  $result['code'] = 1;
        	}
        	die(json_encode($result));
	}
	
  //租赁参数设置管理——批量删除
    public function batch()
    {
    	$this->operator_privilege('product_edit');
        $ct_ids = !empty($_REQUEST['checkbox']) ? $_REQUEST['checkbox'] : 0;
        if (sizeof($ct_ids) > 0) {
            $this->ProductLeasePrice->deleteAll(array('ProductLeasePrice.id' => $ct_ids));
        }
        $this->redirect('/product_lease_prices/');
    }

}
?>




