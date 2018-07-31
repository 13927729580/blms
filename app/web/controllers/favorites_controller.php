<?php

/*****************************************************************************
 * Seevia 用户收藏
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 FavoritesController 的收藏控制器.
 */
class FavoritesController extends AppController
{
    /*
    *@var $name
    *@var $components
    *@var $helpers
    *@var $uses
    */
    public $name = 'Favorites';
//	var $helpers = array('Html');
    public $components = array('Pagination','RequestHandler'); // Added
    public $helpers = array('Html','Pagination'); // Added
    public $uses = array('User','UserFavorite','Brand','Product','UserLike','UserFans','Blog','Attribute');

    /**
     *函数 add 用于添加收藏.
     *
     *@param $type
     *@param $type_id
     */
    public function add($type = '', $type_id = '')
    {
        if ($type == 'p') {
            $_SESSION['login_back'] = '/products/'.$type_id;
        }
        if ($type == 'a') {
            $_SESSION['login_back'] = '/articles/'.$type_id;
        }
        if ($type == 'md') {
            $_SESSION['login_back'] = '/travel_destinations/view/'.$type_id;
        }
        if ($type == 'h') {
            $_SESSION['login_back'] = '/travel_hotels/view/'.$type_id;
        }
        if ($type_id == '') {
            $is_ajax = 1;
        } else {
            $is_ajax = 0;
        }
        $result['type_flag'] = $type;
        $result['message'] = $this->ld['invalid_operation'];
//		if($this->RequestHandler->isPost()){
            if (isset($_SESSION['User'])) {
                if (isset($_POST['type']) && isset($_POST['type_id'])) {
                    $type = $_POST['type'];
                    $type_id = $_POST['type_id'];
                }
                if (!isset($_SESSION['User'])) {
                    $result['type'] = 1;
                    $result['message'] = $this->ld['only for membership users to keep,only member keep'];
                } else {
                    if (isset($_POST['file_url'])) {
                        $file_name = rand(0, 100).rand(0, 100).'.png';
                        $data = isset($_POST['file_url']) ? $_POST['file_url'] : '';
                        $uri = substr($data, strpos($data, ',') + 1);
                        $dirc = explode('/', $_SERVER['SCRIPT_FILENAME']);
                        foreach ($dirc as $drk => $drv) {
                            if ($drv == $_SERVER['HTTP_HOST']) {
                                $unk = $drk;
                            }
                            if (isset($unk) && $drk > $unk) {
                                unset($dirc[$drk]);
                            }
                        }
                        $dir_url = implode('/', $dirc);
                        //$dir_root = dirname(dirname($_SERVER['SCRIPT_FILENAME']))."/data";
                        $dir_root = $dir_url.'/data';
                        if (!is_dir($dir_root.'/files/')) {
                            mkdir($dir_root.'/files/', 0777);
                            @chmod($dir_root.'/files/', 0777);
                        }
//						@chmod(dirname(dirname($_SERVER['SCRIPT_FILENAME']))."/data/files/".$file_name, 0777);
//						$file_path=dirname(dirname($_SERVER['SCRIPT_FILENAME']))."/data/files/".$file_name;
                        @chmod($dir_url.'/data/files/'.$file_name, 0777);
                        $file_path = $dir_url.'/data/files/'.$file_name;
                        file_put_contents($file_path, base64_decode($uri));
                        $file_url = $this->server_host.'files/'.$file_name;

                        $this->set('file_url', $file_url);
                        $condition = " user_id = '".$_SESSION['User']['User']['id']."' and type = '".$type."' and type_id = '".$type_id."'and file_url = '".$file_url."'";
                    } else {
                        $condition = " user_id = '".$_SESSION['User']['User']['id']."' and type = '".$type."' and type_id = '".$type_id."'";
                    }

                    if ($this->UserFavorite->find('count', array('conditions' => $condition))) {
                        $result['type'] = 1;
                        $result['message'] = $this->ld['already_favorite'];
                    } else {
                        if (isset($_POST['file_url'])) {
                            $favorite = array('user_id' => intval($_SESSION['User']['User']['id']),'type' => trim($type),'type_id' => intval($type_id),'status' => 1,'file_url' => $file_url);
                        } else {
                            $favorite = array('user_id' => intval($_SESSION['User']['User']['id']),'type' => trim($type),'type_id' => intval($type_id),'status' => 1);
                        }
                        //	$this->UserFavorite->save($favorite);
                        $this->UserFavorite->saveAll($favorite);
                        if (!empty($file_url)) {
                            $user_favaorite_id = $this->UserFavorite->id;
                            $this->UserFavorite->updateAll(array('UserFavorite.file_url' => "'".$file_url."'"), array('UserFavorite.id' => $user_favaorite_id));
                        }
                        $product_info = $this->Product->findbyid(intval($type_id));
                        if ($this->user_is_promotion($product_info)) {
                            $product_info['is_promotion'] = 1;
                        }
                    //	pr($product_info);

                        $this->set('product_info', $product_info);
                        $result['type'] = 0;
                        //$result['message'] = $product_info['ProductI18n']['name']." ".$this->ld['collection_success'];
                        $result['message'] = $this->ld['collection_success'];//$this->ld['treasure']
                        if ($type == 'md') {
                            $result['message'] = '目的地收藏成功！';
                        }
                        if ($type == 'h') {
                            $result['message'] = '酒店收藏成功！';
                        }
                    }
                }
            } else {
                $result['type'] = 1;
                $result['message'] = $this->ld['time_out_relogin'];
            }
//		}

        $this->set('result', $result);
        $this->layout = 'ajax';
    }

    /**
     *函数 index 用于进入我收藏的商品页面.
     *
     *@param $page
     *@param $rownum
     *@param $orderby
     */
    public function index($page = 1, $rownum = 10, $orderby = ''){
        //登录验证
        $this->checkSessionUser();
	 
        $this->layout = 'usercenter';            //引入模版
        $this->page_init();                        //页面初始化
        if(isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest"){
            Configure::write('debug', 0);
            $this->layout = 'ajax';
        }
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => $this->ld['account_my_wishlist'],'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $this->pageTitle = $this->ld['account_my_wishlist'].' - '.sprintf($this->ld['page'], $page).' - '.$this->configs['shop_title'];

        $orderby = UrlDecode($orderby);
        $rownum = UrlDecode($rownum);

        if (empty($rownum)) {
            //默认显示5条
            $rownum = isset($this->configs['products_list_num']) ? $this->configs['products_list_num'] : ((!empty($rownum)) ? $rownum : 5);
        }
        if (empty($orderby)) {
            //默认根据时间来排序
            $orderby = isset($this->configs['products_category_page_orderby_type']) ? $this->configs['products_category_page_orderby_type'].' '.$this->configs['products_category_page_orderby_method'] : ((!empty($orderby)) ? $orderby : 'created '.$this->configs['products_category_page_orderby_method']);
        }

        //根据输入来显示每页条数
        if ($rownum == 'all') {
            $rownum = 99999;
        } else {
            $rownum = $rownum;
        }

	$user_id = $_SESSION['User']['User']['id'];
	$user_list = $this->User->find('first', array('conditions' => array('User.id' => $user_id)));
	$this->set('user_list', $user_list);
	//pr($user_list);
	//粉丝数量
	$fans = $this->UserFans->find_fanscount_byuserid($user_id);
	$this->set('fanscount', $fans);
	//日记数量
	$blog = $this->Blog->find_blogcount_byuserid($user_id);
	$this->set('blogcount', $blog);
	//关注数量
	$focus = $this->UserFans->find_focuscount_byuserid($user_id);
	$this->set('focuscount', $focus);
      /***************我收藏的商品*************/
      if (isset($_GET['type']) && $_GET['type'] == 'D') {
		$this->md_fav($rownum, $orderby, $user_id);
		return;
      }
      $limit = $rownum;
      $joins=array(
                    array('table' => 'svoms_products',
				'alias' => 'Product',
				'type' => 'left',
				'conditions' => array('Product.id = UserFavorite.type_id'),
                         ),
          		array('table' => 'svoms_product_i18ns',
				'alias' => 'ProductI18n',
				'type' => 'left',
				'conditions' => array('ProductI18n.product_id = UserFavorite.type_id and ProductI18n.locale="'.LOCALE.'"'),
                         )
      );
      $fav_products=array();
      
      $condition=array();
      $condition["UserFavorite.type"]="p";
      $condition["UserFavorite.type_id <>"]='0';
      $condition["UserFavorite.user_id"]=$user_id;
      $condition["UserFavorite.status"]='1';
      $total = $this->UserFavorite->find('count', array('conditions' => $condition,"joins"=>$joins));
      if($total>0){
		$parameters['get'] = array();
		$parameters['route'] = array('controller' => 'favorites', 'action' => 'index', 'page' => $page, 'limit' => $limit);
		//分页参数
		$options = array('page' => $page, 'show' => $limit, 'modelClass' => 'UserFavorite');
		$pages = $this->Pagination->init($condition, $parameters, $options); // Added
      	
      	
      $fav_products=$this->UserFavorite->find('all',array("conditions"=>$condition,"fields"=>"UserFavorite.*,Product.id,Product.code,Product.brand_id,Product.category_id,Product.img_thumb,Product.img_big,Product.shop_price,Product.promotion_status,Product.promotion_status,Product.promotion_start,Product.promotion_end,ProductI18n.name","joins"=>$joins,'page' => $page, 'limit' => $limit));
      	$fav_product_ids= array();
      	foreach($fav_products as $v){
      		$fav_product_ids[]=$v['UserFavorite']['type_id'];
      	}
      	$UserLike_data=$this->UserLike->find('list',array('fields'=>'type_id,id','conditions'=>array('UserLike.user_id'=>$user_id,'UserLike.action'=>'like','UserLike.type'=>'P','UserLike.type_id'=>$fav_product_ids)));
      	$attribute_info=$this->Attribute->find('all',array('fields'=>array("Attribute.id","AttributeI18n.name"),'conditions'=>array("Attribute.status"=>'1')));
            	$attribute_data=array();
		foreach($attribute_info as $v){
			$attribute_data[$v['Attribute']['id']]=$v['AttributeI18n']['name'];
		}
		$this->set('attribute_data',$attribute_data);
      	
      	$brand_info=$this->Brand->find('all',array('fields'=>array('Brand.id','BrandI18n.name'),'conditions'=>array("Brand.status"=>'1')));
		$brand_data=array();
		foreach($brand_info as $v){
			$brand_data[$v['Brand']['id']]=$v['BrandI18n']['name'];
		}
		foreach($fav_products as $k=>$v){
      		$fav_products[$k]['Brand']=isset($brand_data[$v['Product']['brand_id']]) ? $brand_data[$v['Product']['brand_id']] : '';
      		$fav_products[$k]['UserLike']=isset($UserLike_data[$v['Product']['id']])?'1':'0';
			if ($this->Product->is_promotion($v)) {
				$fav_products[$k]['Product']['off'] = floor((1 - ($v['Product']['promotion_price'] / $v['Product']['shop_price'])) * 100);
			}
      	}
      }
	$this->set('fav_products', $fav_products);
	$this->set('fav_products_count', $total);
	$this->set('user_id', $user_id);
	//排序方式,显示方式,分页数量限制
	$this->set('orderby', $orderby);
	$this->set('rownum', $rownum);
    }
    
    public function favourite_article($page = 1, $rownum =10, $orderby = ''){
    		Configure::write('debug', 1);
    		$this->layout="ajax";
    		if(!isset($_SESSION['User']['User']['id'])){
    			exit();
    		}
        	$user_id = $_SESSION['User']['User']['id'];
        	$fav_articles=array();
        	$limit = $rownum;
        	$joins=array(
                    array('table' => 'svcms_articles',
				'alias' => 'Article',
				'type' => 'left',
				'conditions' => array('Article.id = UserFavorite.type_id'),
                         ),
          		array('table' => 'svcms_article_i18ns',
				'alias' => 'ArticleI18n',
				'type' => 'left',
				'conditions' => array('ArticleI18n.article_id = UserFavorite.type_id and ArticleI18n.locale="'.LOCALE.'"'),
                         )
      	);
        	$condition=array();
      	$condition["UserFavorite.type"]="a";
      	$condition["UserFavorite.type_id <>"]='0';
      	$condition["UserFavorite.user_id"]=$user_id;
      	$condition["UserFavorite.status"]='1';
        	$total = $this->UserFavorite->find('count', array('conditions' => $condition,"joins"=>$joins));
        	if($total>0){
        		$parameters['get'] = array();
			$parameters['route'] = array('controller' => 'favorites', 'action' => 'favourite_article', 'page' => $page, 'limit' => $limit);
			//分页参数
			$options = array('page' => $page, 'show' => $limit, 'modelClass' => 'UserFavorite');
			$pages = $this->Pagination->init($condition, $parameters, $options); // Added
			$fav_articles=$this->UserFavorite->find('all',array("conditions"=>$condition,"fields"=>"UserFavorite.*,Article.*,ArticleI18n.*","joins"=>$joins,'page' => $page, 'limit' => $limit));
			$article_ids=array();
			foreach($fav_articles as $v){
				$article_ids[]=$v['UserFavorite']['type_id'];
			}
			$UserLike_data=$this->UserLike->find('list',array('fields'=>'type_id,id','conditions'=>array('UserLike.user_id'=>$user_id,'UserLike.action'=>'like','UserLike.type'=>'A','UserLike.type_id'=>$article_ids)));
			foreach($fav_articles as $k=>$v){
				$fav_articles[$k]['UserFavorite']=isset($UserLike_data[$v['UserFavorite']['type_id']])?'1':'0';
			}
        	}
        	$this->set('fav_articles',$fav_articles);
    }
    
    public function favourite_course($page = 1,$rownum =10){
    		Configure::write('debug', 2);
    		$this->layout="ajax";
    		if(!isset($_SESSION['User']['User']['id'])){
    			exit();
    		}
        	$user_id = $_SESSION['User']['User']['id'];
        	$limit = $rownum;
        	$joins=array(
                    array('table' => 'svhr_courses',
				'alias' => 'Course',
				'type' => 'left',
				'conditions' => array('Course.id = UserFavorite.type_id'),
                         )
      	);
        	$condition=array();
      	$condition["UserFavorite.type"]="c";
      	$condition["UserFavorite.type_id <>"]='0';
      	$condition["UserFavorite.user_id"]=$user_id;
      	$condition["UserFavorite.status"]='1';
      	$condition["Course.status"]='1';
        	$total = $this->UserFavorite->find('count', array('conditions' => $condition,"joins"=>$joins));
        	if($total>0){
        		$parameters['get'] = array();
			$parameters['route'] = array('controller' => 'favorites', 'action' => 'favourite_course', 'page' => $page, 'limit' => $limit);
			//分页参数
			$options = array('page' => $page, 'show' => $limit, 'modelClass' => 'UserFavorite','total'=>$total);
			$pages = $this->Pagination->init($condition, $parameters, $options); // Added
			$favourite_course_list=$this->UserFavorite->find('all',array("conditions"=>$condition,"fields"=>"UserFavorite.*,Course.id,Course.name,Course.img,Course.meta_description","joins"=>$joins,'page' => $page, 'limit' => $limit,'order'=>'UserFavorite.created desc'));
			$this->set('favourite_course_list',$favourite_course_list);
        	}
    }
    
    public function cellphone_index($page = 1, $rownum = '', $orderby = ''){
        Configure::write('debug', 1);
        //登录验证
        $this->checkSessionUser();
        $this->layout = 'usercenter';            //引入模版
        $this->page_init();                        //页面初始化
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => $this->ld['account_my_wishlist'],'url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $this->pageTitle = $this->ld['account_my_wishlist'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_title'];
        $orderby = UrlDecode($orderby);
        $rownum = UrlDecode($rownum);

        if (empty($rownum)) {
            //默认显示5条
            $rownum = isset($this->configs['products_list_num']) ? $this->configs['products_list_num'] : ((!empty($rownum)) ? $rownum : 5);
        }
        if (empty($orderby)) {
            //默认根据时间来排序
            $orderby = isset($this->configs['products_category_page_orderby_type']) ? $this->configs['products_category_page_orderby_type'].' '.$this->configs['products_category_page_orderby_method'] : ((!empty($orderby)) ? $orderby : 'created '.$this->configs['products_category_page_orderby_method']);
        }

        //根据输入来显示每页条数
        if ($rownum == 'all') {
            $rownum = 99999;
        } else {
            $rownum = $rownum;
        }

        $user_id = $_SESSION['User']['User']['id'];

      /***************我收藏的商品*************/
      if (isset($_GET['type']) && $_GET['type'] == 'D') {
          $this->md_fav($rownum, $orderby, $user_id);

          return;
      }
        $condition = " type = 'p' and user_id=$user_id and status=1";
        $limit = $rownum;
        $parameters['get'] = array();
      //地址路由参数（和control,action的参数对应）
      $parameters['route'] = array('controller' => 'favorites', 'action' => 'cellphone_index', 'page' => $page, 'limit' => $limit);
      //分页参数
      $options = array('page' => $page, 'show' => $limit, 'modelClass' => 'UserFavorite');
        $page = $this->Pagination->init($condition, $parameters, $options);
        $res_p = $this->UserFavorite->find('all', array('page' => $page, 'limit' => $rownum, 'order' => $orderby, 'conditions' => $condition));//获取自己的收藏
      foreach ($res_p as $k => $v) {
          $products_id[$k] = $v['UserFavorite']['type_id'];
      }
        if (!empty($products_id)) {
            $condition = array('Product.id' => $products_id," Product.status ='1'");
            $fav_products = $this->Product->find('all', array('conditions' => array($condition),
                    'fields' => array('Product.id', 'Product.recommand_flag', 'Product.status', 'Product.img_thumb', 'Product.market_price', 'Product.freeshopping', 'Product.shop_price', 'Product.promotion_price', 'Product.promotion_start', 'Product.promotion_end', 'Product.promotion_status', 'Product.code', 'Product.created', 'Product.product_rank_id', 'Product.quantity', 'ProductI18n.name',
                    ), ));
            foreach ($fav_products as $k => $v) {
                //判断是否促销产品
            if ($this->Product->is_promotion($v)) {
                $v['Product']['off'] = floor((1 - ($v['Product']['promotion_price'] / $v['Product']['shop_price'])) * 100);
            }
                foreach ($res_p as $kk => $vv) {
                    if ($vv['UserFavorite']['type_id'] == $v['Product']['id']) {
                        $res_p[$kk]['Product'] = $v['Product'];
                        $res_p[$kk]['ProductI18n'] = $v['ProductI18n'];
                    }
                }
            }
        }

        $this->set('fav_products', $res_p);
        $this->set('fav_products_count', count($res_p));
        $this->set('user_id', $user_id);
        $this->set('orderby', $orderby);
        $this->set('rownum', $rownum);
    }
    public function md_fav($page = 1, $rownum = '', $orderby = '')
    {
        //登录验证
        $this->checkSessionUser();

        $this->layout = 'usercenter';            //引入模版
        $this->page_init();                        //页面初始化

        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => '目的地收藏','url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $this->pageTitle = '目的地收藏'.' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_title'];
        $this->loadModel('TravelDestination');
        $orderby = UrlDecode($orderby);
        $rownum = UrlDecode($rownum);

        if (empty($rownum)) {
            //默认显示5条
            $rownum = isset($this->configs['products_list_num']) ? $this->configs['products_list_num'] : ((!empty($rownum)) ? $rownum : 1);
        }
        if (empty($orderby)) {
            //默认根据时间来排序
            $orderby = isset($this->configs['products_category_page_orderby_type']) ? $this->configs['products_category_page_orderby_type'].' '.$this->configs['products_category_page_orderby_method'] : ((!empty($orderby)) ? $orderby : 'created '.$this->configs['products_category_page_orderby_method']);
        }

        //根据输入来显示每页条数
        if ($rownum == 'all') {
            $rownum = 99999;
        } else {
            $rownum = $rownum;
        }

        $user_id = $_SESSION['User']['User']['id'];
        $this->md_fav_reload($rownum, $orderby, $user_id, $page);
    }

    public function md_fav_reload($rownum, $orderby, $user_id, $page)
    {
        //	  $did=1;
      $condition = '';
        $condition = " type = 'md' and user_id=$user_id ";
        $res_p = $this->UserFavorite->find('all', array('conditions' => $condition)); //获取自己的收藏

      foreach ($res_p as $k => $v) {
          //获取自己收藏的商品编号
         $did[$k] = $v['UserFavorite']['type_id'];
      }

        if (!empty($did)) {
            $condition = array('TravelDestination.id' => $did," TravelDestination.status ='1'");
          //分页start
          //get参数
          $limit = $rownum;
            $parameters['get'] = array();
          //地址路由参数（和control,action的参数对应）
          $parameters['route'] = array('controller' => 'favorites', 'action' => 'md_fav', 'page' => $page, 'limit' => $limit);
          //分页参数
          $options = array('page' => $page, 'show' => $limit, 'modelClass' => 'TravelDestination');
            $page = $this->Pagination->init($condition, $parameters, $options); // Added
          //分页end
            //ClassRegistry::init("TravelDestinationPort")
          $data = ClassRegistry::init('TravelDestination')->find('all', array('page' => $page, 'limit' => $rownum, 'order' => 'id desc', 'conditions' => $condition, 'fields' => array('TravelDestination.id', 'TravelDestination.name', 'TravelDestination.pic')));
//	  	  $data=ClassRegistry::init("TravelDestination")->find('all',array('page'=>$page,'limit'=>$rownum,'order'=>"id desc",'fields'=>array('TravelDestination.id','TravelDestination.name','TravelDestination.pic')));

          $dids = array();
            $tmp = array();
            $tmp2 = array();
            foreach ($data as $k => $v) {
                $dids[] = $v['TravelDestination']['id'];
            }
            $area = ClassRegistry::init('TravelDestinationArea')->find('list', array('conditions' => array('TravelDestinationArea.travel_destination_id' => $dids), 'fields' => array('TravelDestinationArea.id', 'TravelDestinationArea.travel_destination_id')));
            $aids = array();
            foreach ($area as $ak => $av) {
                $aids[] = $ak;
            }
            $vi = ClassRegistry::init('TravelViews')->find('all', array('conditions' => array('TravelViews.travel_destination_areas_id' => $aids), 'fields' => array('TravelViews.travel_destination_areas_id', 'TravelViews.name')));
            foreach ($vi as $vik => $viv) {
                $tmp[$viv['TravelViews']['travel_destination_areas_id']][] = $viv['TravelViews']['name'];
            }
            foreach ($area as $ak2 => $av2) {
                if (isset($tmp[$ak2])) {
                    foreach ($tmp[$ak2] as $tk => $tv) {
                        $tmp2[$av2][] = $tv;
                    }
                }
            }
        } else {
            $data = array();
            $tmp2 = array();
        }

        $this->set('fav_md', $data);
        $this->set('fav_count', count($data));
        $this->set('fav_view', $tmp2);
        $this->set('user_id', $user_id);
      //排序方式,显示方式,分页数量限制
      $this->set('orderby', $orderby);
        $this->set('rownum', $rownum);
    }

    /**
     *函数 del_products_t 用于删除收藏商品.
     *
     *@param $type_id
     *@param $user_id
     *@param $type
     */
    public function del_products_t($type_id, $user_id, $type)
    {
        //登录验证
        $this->checkSessionUser();

        $condition = " type_id='".$type_id."' and user_id='".$user_id."' and type='".$type."'";
        $fav_product_info = $this->UserFavorite->find($condition);
        $id = $fav_product_info['UserFavorite']['id'];
        $this->UserFavorite->delete($id);
        //显示的页面
        if ($type == 'h') {
            $this->redirect('/favorites/hotel_fav');
        } else {
            $this->redirect('/favorites');
        }
    }
    public function del_fav($type_id, $user_id, $type)
    {
        //登录验证
        $this->checkSessionUser();

        $condition = " type_id='".$type_id."' and user_id='".$user_id."' and type='".$type."'";
        $fav_product_info = $this->UserFavorite->find($condition);
        $id = $fav_product_info['UserFavorite']['id'];
        $this->UserFavorite->delete($id);
        //显示的页面
        if ($type == 'h') {
            $this->redirect('/favorites/hotel_fav');
        } else {
            $this->redirect('/favorites/cellphone_index');
        }
    }
    /**
     *函数 user_is_promotion 用于获取收藏品信息.
     *
     *@param $product_info
     *
     *@return ($product_info['Product']['promotion_status'] == '1' && $product_info['Product']['promotion_start'] <= date("Y-m-d H:i:s") && $product_info['Product']['promotion_end'] >= date("Y-m-d H:i:s"));
     */
    public function user_is_promotion($product_info)
    {
        return ($product_info['Product']['promotion_status'] == '1' && $product_info['Product']['promotion_start'] <= date('Y-m-d H:i:s') && $product_info['Product']['promotion_end'] >= date('Y-m-d H:i:s'));
    }

        //批量处理
    public function batch($checked, $obj)
    {
        $result['type'] = '0';
        if ($checked != '') {
            if ($obj == 'delete') {
                //批量删除
                $condition['UserFavorite.id'] = explode(',', $checked);
                $this->UserFavorite->deleteAll($condition);
                $result['type'] = '1';
            }
        }
        Configure::write('debug', 0);
        die($result['type']);
    }
    //酒店收藏
    public function hotel_fav($page = 1, $rownum = '', $orderby = '')
    {
        //登录验证
        $this->checkSessionUser();
        $this->layout = 'usercenter';            //引入模版
        $this->page_init();    //页面初始化
        //当前位置
        $this->ur_heres[] = array('name' => $this->ld['user_center'],'url' => '/users');
        $this->ur_heres[] = array('name' => '酒店收藏','url' => '');
        $this->set('ur_heres', $this->ur_heres);
        $this->pageTitle = '酒店收藏'.' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_title'];

        $orderby = UrlDecode($orderby);
        $rownum = UrlDecode($rownum);

        if (empty($rownum)) {
            //默认显示5条
            $rownum = isset($this->configs['products_list_num']) ? $this->configs['products_list_num'] : ((!empty($rownum)) ? $rownum : 5);
        }
        if (empty($orderby)) {
            //默认根据时间来排序
            $orderby = isset($this->configs['products_category_page_orderby_type']) ? $this->configs['products_category_page_orderby_type'].' '.$this->configs['products_category_page_orderby_method'] : ((!empty($orderby)) ? $orderby : 'created '.$this->configs['products_category_page_orderby_method']);
        }

        //根据输入来显示每页条数
        if ($rownum == 'all') {
            $rownum = 99999;
        } else {
            $rownum = $rownum;
        }
        $this->loadModel('TravelHotel');
        $user_id = $_SESSION['User']['User']['id'];
        $condition = " type = 'h' and user_id=$user_id ";
        $user_fav_infos = $this->UserFavorite->find('all', array('conditions' => $condition)); //获取自己的收藏

        $hotel_ids = array();
        $fav_type_id = array();
        if (!empty($user_fav_infos)) {
            foreach ($user_fav_infos as $k => $v) {
                $hotel_ids[$k] = $v['UserFavorite']['type_id'];
                $fav_type_id[$v['UserFavorite']['type_id']] = $v['UserFavorite']['id'];
            }
        }
        $condition = '';
        $condition = array('TravelHotel.id' => $hotel_ids," TravelHotel.status ='1'");
        $limit = $rownum;
        $parameters['get'] = array();
        $parameters['route'] = array('controller' => 'favorites', 'action' => 'index?type=h', 'page' => $page, 'limit' => $limit);
        //分页参数
        $options = array('page' => $page, 'show' => $limit, 'modelClass' => 'TravelHotel');
        $page = $this->Pagination->init($condition, $parameters, $options); // Added
        //分页end
        $hotel_infos = $this->TravelHotel->find('all', array('conditions' => $condition, 'order' => 'TravelHotel.created', 'limit' => $limit, 'page' => $page));
        foreach ($hotel_infos as $k => $v) {
            $hotel_infos[$k]['UserFavoriteId'] = isset($fav_type_id[$v['TravelHotel']['id']]) ? $fav_type_id[$v['TravelHotel']['id']] : '';
        }
        $this->set('hotel_infos', $hotel_infos);
        $user_id = $_SESSION['User']['User']['id'];
        $this->set('user_id', $user_id);
    }
}
