<?php
//define('TOKEN', 'seevia');
define('DEBUG', true);
class OpensController extends AppController
{
    var $name = 'Opens';
    var $uses = array('LanguageDictionary', 'Product','Article','Topic','Application', 'OpenModel', 'OpenUser', 'OpenUserMessage','OpenElement','OpenRelation','OpenKeyword','OpenKeywordAnswer','OpenKeywordError','OpenConfig','Config');
    var $components = array('Notify');
    var $openmodelInfo=array();//记录当前登录的公众平台信息
    
    //微信API签名
    function signature(){
	Configure::write('debug',1);
	$_GET=$this->clean_xss($_GET);
	$signature="";
	setcookie("WECHAT_signature","",time()-60*60*24*14,"/");
        if (!empty($_COOKIE['WECHAT_signature'])){
            $signature=$_COOKIE['WECHAT_signature'];
        }else{
        	$open_wechat_info=$this->OpenModel->find('first',array('conditions'=>array('OpenModel.open_type'=>'wechat','OpenModel.status'=>1),'order'=>'OpenModel.id'));
            if(!empty($open_wechat_info)){
                $appid = $open_wechat_info['OpenModel']['app_id'];
                $secret = $open_wechat_info['OpenModel']['app_secret'];
                
                //获取access_token
                $accesstoken = file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret."");
                $token = json_decode($accesstoken); //对JSON格式的字符串进行编码
                $t = get_object_vars($token);//转换成数组
                if(isset($t['access_token'])){
	                $access_token = $t['access_token'];//输出access_token
	                $open_wechat_info['OpenModel']['token']=$access_token;
	                $this->OpenModel->save($open_wechat_info);
                }else{
                	$access_token = $open_wechat_info['OpenModel']['token'];//输出access_token
                }
                //获取ticket
                $jsapi = file_get_contents("https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".$access_token."&type=jsapi");
                $jsapi = json_decode($jsapi);
                $j = get_object_vars($jsapi);
                $jsapi = isset($j['ticket'])?$j['ticket']:'';
                $url=isset($_REQUEST['page'])?$_REQUEST['page']:"";
                $jsapi_ticket= $jsapi;
                $times = strtotime(date("Y-m-d"));
                $noncestr=$times;
                $timestamp=$times;
                $and = "jsapi_ticket=".$jsapi_ticket."&noncestr=".$noncestr."&timestamp=".$timestamp."&url=".$url;
                $signature = sha1($and);
                setcookie("WECHAT_signature",$signature,7200,"/");
            }
        }
        die($signature);
    }
    
    public function wechat($openTypeId=null){
    	Configure::write('debug', 0);
    	$_GET=$this->clean_xss($_GET);
        App::import('Vendor','Open_Wechat', array('file'=>'open' . DS .'open_wechat.php'));
        if(!empty($openTypeId)){
        	$openModelInfo = $this->OpenModel->getInfoByTypeId($openTypeId);
        }else if(isset($_GET['open_type_id'])&&trim($_GET['open_type_id'])!=""){
        	$openModelInfo = $this->OpenModel->getInfoByTypeId($_GET['open_type_id']);
        }else{
    		$openType = 'wechat';
			$openModelInfo = $this->OpenModel->getInfoByOpenType($openType);
		}
		if(isset($openModelInfo)&&!empty($openModelInfo)){
			$this->openmodelInfo=$openModelInfo;
		}else{
            die();
		}
		$openModel = new Open_Wechat($openModelInfo['OpenModel']['signature_token'], DEBUG);
		$this->openmodelInfo=$openModelInfo;
        $openModel->requestMsg();
        $requestMsg = $openModel->getMsg();
        if(empty($requestMsg)){
	        $this->_saveMsg('valid',json_encode($_GET),0,0,$_GET["echostr"],$_GET["echostr"]);
	        $openModel->valid();
	        //验证end
        }
        //定义默认回复信息
        $ErrorMsg=$this->_getErrorMsg();
        $data = $ErrorMsg;
        if (empty($openModelInfo)) {
        	if($data['type']=='text'&&!empty($data['content'])){
            		echo $openModel->responseMsg($data);
            	}
            die();
        }
        $requestType = $requestMsg->MsgType;
        $openId = $requestMsg->FromUserName;
        $openTypeId = $openModelInfo['OpenModel']['open_type_id'];
        //获取公众平台参数设置
        $open_config_data=$this->OpenConfig->tree(array('open_type'=>$openType,'open_type_id'=>$openTypeId));
        if ($requestType == 'event') {
            if ($requestMsg->Event == 'subscribe'){
                $_data = $this->_getWelcomeMsg($openType);
                if(empty($_data)){
                	$WelcomeMsg=$open_config_data['FIRST-CONCERN'];
                	if(empty($WelcomeMsg)){
                		$_data['type']=$open_config_data['FIRST-CONCERN']['name'];
                		$_data['content']=trim(mb_substr(strip_tags($open_config_data['FIRST-CONCERN']['value']),0,100,'utf-8'));
                	}else{
                		$_data=$ErrorMsg;
                	}
                }
               	$data=$_data;
                if($data['type']=='text'){
                	$this->_saveMsg('text', "首次关注", $openId, 1,'ok',$responsMsg);
                }
                if($data['type']=='material'){
                	$this->_saveMsg('material', "首次关注", $openId,1,'ok',is_array($responsMsg)?$responsMsg[0]['Item']['Description']:$responsMsg);
                }
                $appId = $openModelInfo['OpenModel']['app_id'];
		        $appSecret = $openModelInfo['OpenModel']['app_secret'];
		        $accessToken = $openModelInfo['OpenModel']['token'];
		        if($appId!=""&&$appSecret!=""){
			        if (!$this->_validateToken($openModelInfo)) {
			            //无效重新获取
			            $accessToken =$this->_getAccessToken($appId, $appSecret, $openType);
			            if (empty($accessToken)) {
			                echo $openModel->responseMsg($data);
			                die();
			            }
			            $openModelInfo['OpenModel']['token'] = $accessToken;
			            $this->OpenModel->save($openModelInfo);
			        }
	                $this->_saveUser($accessToken,$openId);
                }
            } elseif ($requestMsg->Event == 'unsubscribe') {
                	$this->_unsubscribeUser($openId);
            }elseif($requestMsg->Event == 'MASSSENDJOBFINISH'){
            		//群发回调
            		$this->OpenUserMessage->saveMsg(
	                    'send_back','MASSSENDJOBFINISH', 0,
	                    $openModelInfo['OpenModel']['open_type_id'], 0,
	                    isset($requestMsg->Status) ? $requestMsg->Status : 'no',
	                    json_encode($requestMsg)
                	);
                	$this->send_back_notify($requestMsg);
            }elseif($requestMsg->Event=='CLICK'){
            	//获取额外可查询的内容类型
	        	if(isset($open_config_data['SEARCH-CONTENT'])&&strlen(trim($open_config_data['SEARCH-CONTENT']['OpenConfigsI18n']['value']))>0){
					$search_content_text=split(';',$open_config_data['SEARCH-CONTENT']['OpenConfigsI18n']['value']);
					if(sizeof($search_content_text)>0){
						$search_content=$search_content_text;
					}
				}else{
					$search_content=array('P','A','T');//默认商品、文章、专题
				}
            	
            	$data=$this->_setMsgReply($requestMsg->EventKey,$search_content);
            	$responsMsg=$data['content'];
	            if($data == $ErrorMsg){
	            	$keyword_error = $this->OpenKeywordError->find('first', array('conditions'=>array('OpenKeywordError.keyword'=>$requestMsg->EventKey)));
	            	$keyword_error['OpenKeywordError']['openid']=$openId;
	            	$keyword_error['OpenKeywordError']['open_type_id']=$openTypeId;
	            	$keyword_error['OpenKeywordError']['keyword']=$requestContent;
	            	$keyword_error['OpenKeywordError']['open_user_id']=$this->OpenUser->getUserIdByOpenId($openId);
	            	$this->OpenKeywordError->save(array("OpenKeywordError"=>$keyword_error['OpenKeywordError']));
	            }
	            $this->_saveMsg($data['type'],$requestMsg->EventKey, $openId, 1,'ok',$responsMsg);
            }
        } elseif ($requestType == 'text') {
        	//获取额外可查询的内容类型
        		if(isset($open_config_data['SEARCH-CONTENT'])&&strlen(trim($open_config_data['SEARCH-CONTENT']['OpenConfigsI18n']['value']))>0){
				$search_content_text=split(';',$open_config_data['SEARCH-CONTENT']['OpenConfigsI18n']['value']);
				if(sizeof($search_content_text)>0){
					$search_content=$search_content_text;
				}
			}else{
				$search_content=array('P','A','T');//默认商品、文章、专题
			}
            $requestContent = $requestMsg->Content;
            //获取回复关键字回复信息
            $data=$this->_setMsgReply($requestContent,$search_content);
            $responsMsg=$data['content'];
            if($data == $ErrorMsg){
            	$keyword_error = $this->OpenKeywordError->find('first', array('conditions'=>array('OpenKeywordError.keyword'=>$requestContent)));
            	$keyword_error['OpenKeywordError']['openid']=$openId;
            	$keyword_error['OpenKeywordError']['open_type_id']=$openTypeId;
            	$keyword_error['OpenKeywordError']['keyword']=$requestContent;
            	$keyword_error['OpenKeywordError']['open_user_id']=$this->OpenUser->getUserIdByOpenId($openId);
            	$this->OpenKeywordError->save(array("OpenKeywordError"=>$keyword_error['OpenKeywordError']));
            }
            $this->_saveMsg($data['type'],$requestContent, $openId, 1,'ok',$responsMsg);
        } elseif ($requestType == 'image') {
            $this->_saveMsg('image', $requestMsg->PicUrl, $openId, 1,'ok',$responsMsg);
        }
        if($data['type']=='text'&&!empty($data['content'])){
        	echo $openModel->responseMsg($data);
        }else if($data['type']!='text'){
        	echo $openModel->responseMsg($data);
        }
        die();
    }
    
    /**
     * 关注信息
     */
    private function _getWelcomeMsg($openType='wechat')
    {
    	$data=array();
		$conditions["OpenKeywordAnswer.keyword"]="首次关注";
        $cond['conditions']=$conditions;
    	$cond['fields']=array("OpenElement.id","OpenKeywordAnswer.msgtype","OpenKeywordAnswer.message","OpenElement.title","OpenElement.url","OpenElement.media_url","OpenElement.description");
    	$cond['order']="OpenKeywordAnswer.created desc,OpenKeywordAnswer.modified desc";
    	$cond['joins']=array(array(
  					'table'=>'svsns_open_elements',
  					'alias'=>'OpenElement',
  					'type'=>'left',
  					'conditions'=>array('OpenKeywordAnswer.element_id=OpenElement.id')
  				));
    	$msg=$this->OpenKeywordAnswer->find('first',$cond);
    	if(!empty($msg)){
    		if($msg['OpenKeywordAnswer']['msgtype']=="text"){
    			$data['type']="text";
    			$data['content']=$msg['OpenKeywordAnswer']['message'];
    		}else if($msg['OpenKeywordAnswer']['msgtype']=="picture"){
    			$data['type']="material";
    			$all_elements=$this->OpenElement->find('all',array("conditions"=>array("OpenElement.parent_id"=>$msg['OpenElement']['id'])));
    			$data['content'][0]['Item']['Title']=$msg['OpenElement']['title'];
    			$data['content'][0]['Item']['Description']= mb_substr($this->emptyreplace($msg['OpenElement']['description']),0,100,'utf-8')."...";
    			$data['content'][0]['Item']['PicUrl']=$this->server_host.$msg['OpenElement']['media_url'];
    			$data['content'][0]['Item']['Url']=empty($msg['OpenElement']['url'])?$this->server_host."/open_elements/preview/".$msg['OpenElement']['id']:$msg['OpenElement']['url'];
    			if(!empty($all_elements)){
	    			foreach($all_elements as $ele_k=>$ele_v){
	    				$data['content'][$ele_k+1]['Item']['Title']=$ele_v['OpenElement']['title'];
                        $data['content'][$ele_k+1]['Item']['Description']=mb_substr($this->emptyreplace($ele_v['OpenElement']['description']),0,100,'utf-8')."...";
		    			$data['content'][$ele_k+1]['Item']['PicUrl']=$this->server_host.$ele_v['OpenElement']['media_url'];
		    			$data['content'][$ele_k+1]['Item']['Url']=empty($ele_v['OpenElement']['url'])?$this->server_host."/open_elements/preview/".$ele_v['OpenElement']['id']:$ele_v['OpenElement']['url'];
	    			}
    			}
    		}
    	}else{
    		$data=$this->_getErrorMsg('FIRST-CONCERN');
    	}
    	return $data;
    }
    
    /**
     * 默认信息
     */
    private function _getErrorMsg($code='DEFAULT-ANSWER'){
    	$openType=$this->openmodelInfo['OpenModel']['open_type'];
    	$openTypeId=$this->openmodelInfo['OpenModel']['open_type_id'];
    	$open_config_data=$this->OpenConfig->tree(array('open_type'=>$openType,'open_type_id'=>$openTypeId,'code'=>$code));
    	if(!empty($open_config_data[$code])){
    		$data['type']=$open_config_data[$code]['name'];
    		$content=$open_config_data[$code]['value'];
    		if($data['type']=="material"){
    			$elementsInfo=$this->OpenElement->find('first',array("conditions"=>array("OpenElement.id"=>$content)));
    			if(!empty($elementsInfo)){
	    			$data['content'][0]['Item']['Title']=$elementsInfo['OpenElement']['title'];
                    $data['content'][0]['Item']['Description']=mb_substr($this->emptyreplace($elementsInfo['OpenElement']['description']),0,100,'utf-8')."...";
	    			$data['content'][0]['Item']['PicUrl']=$this->server_host.$elementsInfo['OpenElement']['media_url'];
	    			$data['content'][0]['Item']['Url']=empty($elementsInfo['OpenElement']['url'])?$this->server_host."/open_elements/preview/".$elementsInfo['OpenElement']['id']:$elementsInfo['OpenElement']['url'];
	    			$all_elements=$this->OpenElement->find('all',array("conditions"=>array("OpenElement.parent_id"=>$elementsInfo['OpenElement']['id'])));
					if(!empty($all_elements)){
						foreach($all_elements as $ele_k=>$ele_v){
							$data['content'][$ele_k+1]['Item']['Title']=$ele_v['OpenElement']['title'];
                            $data['content'][$ele_k+1]['Item']['Description']=mb_substr($this->emptyreplace($ele_v['OpenElement']['description']),0,100,'utf-8')."...";
							$data['content'][$ele_k+1]['Item']['PicUrl']=$this->server_host.$ele_v['OpenElement']['media_url'];
							$data['content'][$ele_k+1]['Item']['Url']=empty($ele_v['OpenElement']['url'])?$this->server_host."/open_elements/preview/".$ele_v['OpenElement']['id']:$ele_v['OpenElement']['url'];
						}
					}
    			}
    		}else{
    			$data['content']=$open_config_data[$code]['value'];
    		}
        }else{
        	$data['type']="text";
    		$data['content']="";
        }
        return $data;
    }
    
    /**
     * 取商品信息
     */
    private function _getProductMsg($keyword)
    {
    	$condition['or']['ProductI18n.name like']="%".$keyword."%";
    	$condition['or']['Product.code like']="%".$keyword."%";
    	$condition['or']['ProductI18n.meta_keywords like'] = "%".$keyword."%";
    	$condition['AND']['Product.status'] = '1';
		$condition['AND']['Product.forsale'] = '1';
		$condition['AND']['ProductI18n.locale'] = 'chi';
        $productInfo = $this->Product->find('all', array('conditions'=>$condition));
        if (empty($productInfo)||sizeof($productInfo)==0) {
            return false;
        }else{
        	return $productInfo;
        }
    }
    
    /**
     * 取订单信息
     */
    private function _getOrderMsg($orderCode)
    {
    	if(constant("Product")=="AllInOne"){
    		$this->loadModel("Order");
	        $orderInfo=$this->Order->find('first', array('conditions' => array('Order.order_code'=>$orderCode)));
	        if (empty($orderInfo)) {
	            return false;
	        }
	        if ($orderInfo['Order']['status']==2) {
	            $orderStatus = '已取消';
	        } elseif ($orderInfo['Order']['payment_status']==0) {
	            if ($orderInfo['Order']['shipping_status']==1) {
	                $orderStatus = '已发货';
	            } else {
	                $orderStatus = '未付款' ;
	            }
	        } elseif ($orderInfo['Order']['status']==1 && $orderInfo['Order']['shipping_status']==0 && $orderInfo['Order']['payment_status']==2) {
	            $orderStatus = '配货中';
	        } elseif ($orderInfo['Order']['status']==1 && $orderInfo['Order']['shipping_status']==1 && $orderInfo['Order']['payment_status']==2) {
	            $orderStatus = '已发货';
	        } elseif ($orderInfo['Order']['status']==1 && $orderInfo['Order']['shipping_status']==2 && $orderInfo['Order']['payment_status']==2) {
	            $orderStatus = '已收货';
	        } elseif ($orderInfo['Order']['status']==1 && $orderInfo['Order']['shipping_status']==3 && $orderInfo['Order']['payment_status']==2) {
	            $orderStatus = '已发货';
	        } elseif ($orderInfo['Order']['status']==4) {
	            $orderStatus = '退货';
	        } elseif ($orderInfo['Order']['shipping_status']==3) {
	            $orderStatus = '已发货';
	        }
	        $total = $orderInfo['Order']['total']-$orderInfo["Order"]["point_fee"]-$orderInfo['Order']['discount']-$orderInfo['Order']['coupon_fee'];
	        $msg = '订单号:' . $orderCode ."\n" .
	                '应付金额:' . $total ."\n" .
	                '订单状态:' . $orderStatus ."\n" .
	                '收货人:' . $orderInfo['Order']['consignee'] ."\n" .
	                '手机:' . $orderInfo['Order']['mobile'] ."\n" .
	                '邮箱:' . $orderInfo['Order']['email'] ."\n" .
	                '地址:' . $orderInfo['Order']['country'] . $orderInfo['Order']['province'] . $orderInfo['Order']['city'] .
	                $orderInfo['Order']['district'] . $orderInfo['Order']['address'] ."\n" ;
	        return $msg;
        }
    }
    
    /*
    * 取文章信息
    */
    private function _getArticleMsg($keyword)
    {
    	$cond['or']['ArticleI18n.title LIKE']="%".$keyword."%";
    	$cond['or']['ArticleI18n.subtitle LIKE']="%".$keyword."%";
    	$cond['or']['ArticleI18n.meta_keywords LIKE']="%".$keyword."%";
    	$cond['Article.type !=']='V';
    	$cond['Article.status']='1';
    	$cond['ArticleI18n.locale'] = 'chi';
    	$article_data=$this->Article->find('all',array('conditions'=>$cond));
    	if(!empty($article_data)&&sizeof($article_data)>0){
    		return $article_data;
    	}else{
    		return false;
    	}
    }
    
    /*
    * 取专题信息
    */
    private function _getTopicMsg($keyword)
    {
    	$cond['or']['TopicI18n.title LIKE']="%".$keyword."%";
    	$cond['Topic.status']='1';
    	$condition['TopicI18n.locale'] = 'chi';
    	$topic_data=$this->Topic->find('all',array('conditions'=>$cond));
    	if(!empty($topic_data)&&sizeof($topic_data)>0){
    		return $topic_data;
    	}else{
    		return false;
    	}
    }
    
    /*
    	设置信息回复内容
    	$parms:	$requestContent 用户发送的信息
    			$openType 公众平台类型
    			$search_range 可搜索的类型
    	return $replycontent
    */
    private function _setMsgReply($requestContent,$search_range=array('P','A','T')){
    	$replycontent=$this->_getErrorMsg();
    	if($requestContent=="人工服务"){
    	     $replycontent['type']='customerservice';
            $replycontent['content']=$requestContent;
            return $replycontent;
    	}
    	//搜索关键字
        if ($_data = $this->_getkeyword($requestContent)){
            $replycontent['type']=$_data['type'];
            $replycontent['content']=$_data['content'];
        }else{//搜索其他内容
        	$responsMsg='';
        	$Msgdata=array();
        	if (in_array('P',$search_range)&&$msg = $this->_getProductMsg($requestContent)){//获取商品信息
        		$responsMsg=$responsMsg."共计搜索到".sizeof($msg)."个商品";
        		foreach($msg as $k=>$v){
        			$pro_data['Item']['Title']=$v['ProductI18n']['name'];
                    $pro_data['Item']['Description']=mb_substr($this->emptyreplace($v['ProductI18n']['meta_description']),0,100,'utf-8')."...";
        			$pro_data['Item']['PicUrl']=$v['Product']['img_thumb']!=''?$this->server_host.$v['Product']['img_thumb']:$this->server_host.$this->configs['shop_default_img'];
        			$pro_data['Item']['Url']=$this->server_host."/products/".$v['Product']['id'];
        			$Msgdata[]=$pro_data;
        			if($k>=2){break;}
        		}
	        }
	        if (in_array('A',$search_range)&&$msg = $this->_getArticleMsg($requestContent)){//获取文章信息
	        	if($responsMsg!=''){
	        		$responsMsg=$responsMsg.",".sizeof($msg)."篇文章";
	        	}else{
	        		$responsMsg=$responsMsg."共计搜索到".sizeof($msg)."篇文章";
	        	}
	            foreach($msg as $k=>$v){
        			$Article_data['Item']['Title']=$v['ArticleI18n']['title'];
                    $Article_data['Item']['Description']=mb_substr($this->emptyreplace($v['ArticleI18n']['meta_description']),0,100,'utf-8')."...";
        			$Article_data['Item']['PicUrl']=$v['ArticleI18n']['img01']!=''?$this->server_host.$v['ArticleI18n']['img01']:$this->server_host.$this->configs['shop_default_img'];
        			$Article_data['Item']['Url']=$this->server_host."/articles/".$v['Article']['id'];
        			$Msgdata[]=$Article_data;
        			if($k>=2){break;}
        		}
	        }
	        if (in_array('T',$search_range)&&$msg = $this->_getTopicMsg($requestContent)){//获取专题信息
	        	if($responsMsg!=''){
	        		$responsMsg=$responsMsg.",".sizeof($msg)."个专题";
	        	}else{
	        		$responsMsg=$responsMsg."共计搜索到".sizeof($msg)."个专题";
	        	}
	            foreach($msg as $k=>$v){
        			$Topic_data['Item']['Title']=$v['TopicI18n']['title'];
                    $Topic_data['Item']['Description']=mb_substr($this->emptyreplace($v['TopicI18n']['mobile_intro']),0,100,'utf-8')."...";
        			$Topic_data['Item']['PicUrl']=$v['TopicI18n']['img01']!=''?$this->server_host.$v['TopicI18n']['img01']:$this->server_host.$this->configs['shop_default_img'];
        			$Topic_data['Item']['Url']=$this->server_host."/topics/".$v['Topic']['id'];
        			$Msgdata[]=$Topic_data;
        			if($k>=2){break;}
        		}
	        }
	        if (in_array('O',$search_range)&&$msg = $this->_getOrderMsg($requestContent)) {//获取订单信息
	            //$responsMsg = $responsMsg.$msg;
	        }
	        if(!empty($Msgdata)){
	        	$_Msgdata['Item']['Title']='更多...';
	        	$_Msgdata['Item']['Description']=$responsMsg;
	        	$_Msgdata['Item']['Url']=$this->server_host."/searchs/keyword/?keyword=".$requestContent;
	        	$Msgdata[]=$_Msgdata;
	        	$replycontent['type']='material';
	        	$replycontent['content']=$Msgdata;
	        }
        }
        return $replycontent;
    }
    
    /*
    * 返回关键字回复信息
    */
    private function _getkeyword($keyword){
    	$openmodelinfo=$this->openmodelInfo;
    	$conditions=array();
    	$msg = $this->_getErrorMsg();
    	$all_keywords=$this->OpenKeyword->find('list',array("fields"=>array('OpenKeyword.id'),"conditions"=>array('OpenKeyword.keyword like'=>"%".$keyword."%","OpenKeyword.status"=>'1','OpenKeyword.open_type'=>$openmodelinfo['OpenModel']['open_type'],'OpenKeyword.open_type_id'=>$openmodelinfo['OpenModel']['open_type_id'])));
    	if(!empty($all_keywords)>0&&sizeof($all_keywords)>0){
    		$conditions["OpenKeywordAnswer.keyword_id"]=$all_keywords;
    	}
    	if(empty($conditions)){
    		return false;
    	}
    	$conditions['OpenKeywordAnswer.status']='1';
    	$cond['conditions']=$conditions;
    	$cond['fields']=array("OpenElement.id","OpenKeywordAnswer.msgtype","OpenKeywordAnswer.message","OpenElement.title","OpenElement.url","OpenElement.media_url","OpenElement.description");
    	$cond['order']="OpenKeywordAnswer.created desc,OpenKeywordAnswer.modified desc";
    	$cond['joins']=array(array(
  					'table'=>'svsns_open_elements',
  					'alias'=>'OpenElement',
  					'type'=>'left',
  					'conditions'=>array('OpenKeywordAnswer.element_id=OpenElement.id')
  				));
    	$Keyword=$this->OpenKeywordAnswer->find('all',$cond);
    	if(!empty($Keyword)&&sizeof($Keyword)>0){
    		$Keyword_arr=array();
    		foreach($Keyword as $k=>$v){
    			$Keyword_arr[]=$v;
    		}
    		$rand_num=rand(0,sizeof($Keyword)-1);
    		$msg=$Keyword_arr[$rand_num];
    		if($msg['OpenKeywordAnswer']['msgtype']=="text"){
    			$data['type']="text";
    			$data['content']=$msg['OpenKeywordAnswer']['message'];
    		}else if($msg['OpenKeywordAnswer']['msgtype']=="picture"){
    			$data['type']="material";
    			$all_elements=$this->OpenElement->find('all',array("conditions"=>array("OpenElement.parent_id"=>$msg['OpenElement']['id'])));
    			$data['content'][0]['Item']['Title']=$msg['OpenElement']['title'];
                $data['content'][0]['Item']['Description']=mb_substr($this->emptyreplace($msg['OpenElement']['description']),0,100,'utf-8')."...";
    			$data['content'][0]['Item']['PicUrl']=$this->server_host.$msg['OpenElement']['media_url'];
    			$data['content'][0]['Item']['Url']=empty($msg['OpenElement']['url'])?$this->server_host."/open_elements/preview/".$msg['OpenElement']['id']:$msg['OpenElement']['url'];
    			if(!empty($all_elements)){
	    			foreach($all_elements as $ele_k=>$ele_v){
	    				$data['content'][$ele_k+1]['Item']['Title']=$ele_v['OpenElement']['title'];
                        $data['content'][$ele_k]['Item']['Description']=mb_substr($this->emptyreplace($ele_v['OpenElement']['description']),0,100,'utf-8')."...";
		    			$data['content'][$ele_k+1]['Item']['PicUrl']=$this->server_host.$ele_v['OpenElement']['media_url'];
		    			$data['content'][$ele_k+1]['Item']['Url']=empty($ele_v['OpenElement']['url'])?$this->server_host."/open_elements/preview/".$ele_v['OpenElement']['id']:$ele_v['OpenElement']['url'];
	    			}
    			}
    		}
    		return $data;
    	}
    	return false;
    }
    
    private function _saveMsg($msgType, $msg, $openId, $sendFrom,$return_code,$return_message)
    {
    	$openType=$this->openmodelInfo['OpenModel']['open_type'];
    	$openTypeId=$this->openmodelInfo['OpenModel']['open_type_id'];
    	
        $userMsg = array();
        $userMsg['OpenUserMessage']['open_type'] = $openType;
        $userMsg['OpenUserMessage']['open_type_id'] = $openTypeId;
        $userMsg['OpenUserMessage']['send_from'] = $sendFrom;
        $userMsg['OpenUserMessage']['msgtype'] = $msgType;
        $userMsg['OpenUserMessage']['message'] = $msg;
        $userMsg['OpenUserMessage']['return_code'] = $return_code;
        $userMsg['OpenUserMessage']['return_message'] = $return_message;
        if(!empty($this->OpenUser->getUserIdByOpenId($openId))){
        	$userMsg['OpenUserMessage']['open_user_id'] = $this->OpenUser->getUserIdByOpenId($openId);
        }else{
        	$userMsg['OpenUserMessage']['open_user_id'] = 0;
        }
        $this->OpenUserMessage->saveAll($userMsg);
    }

    private function _unsubscribeUser($openId)
    {
        $this->OpenUser->updateAll(array('OpenUser.subscribe' => 0), array('OpenUser.openid' => $openId));
    }
    private function _saveUser($accessToken, $openId)
    {
    	$openType=$this->openmodelInfo['OpenModel']['open_type'];
    	$openTypeId=$this->openmodelInfo['OpenModel']['open_type_id'];
        if ($openType == 'wechat') {
            $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$accessToken.'&openid=' .$openId.'&lang=zh_CN';
        }
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        if (!empty($result) && !isset($result['errcode'])) {
        	$this->OpenUser->subscribe_point($result['openid'],isset($this->configs['wechat_subscribe_point'])?$this->configs['wechat_subscribe_point']:0,'S');
		$userInfo = $this->OpenUser->getInfoByOpenId($result['openid']);
		//check if exist， if exist set subscribe 1
		$userInfo['OpenUser']['open_type'] = $openType;
		$userInfo['OpenUser']['open_type_id'] = $openTypeId;
		$userInfo['OpenUser']['openid'] = $result['openid'];
		$userInfo['OpenUser']['nickname'] = $result['nickname'];
		$userInfo['OpenUser']['sex'] = $result['sex'];
		$userInfo['OpenUser']['language'] = $result['language'];
		$userInfo['OpenUser']['city'] = $result['city'];
		$userInfo['OpenUser']['province'] = $result['province'];
		$userInfo['OpenUser']['country'] = $result['country'];
		$userInfo['OpenUser']['headimgurl'] = $result['headimgurl'];
		$userInfo['OpenUser']['subscribe_time'] = $result['subscribe_time'];
		$userInfo['OpenUser']['subscribe'] = 1;
		if(isset($userInfo['OpenUser']['modified']))unset($userInfo['OpenUser']['modified']);
		$this->OpenUser->save($userInfo);
        }
    }

    private function _saveRelations($openId, $type, $typeId, $openTypeId, $openType = 'wechat')
    {
        $userId = $this->OpenUser->getUserIdByOpenId($openId);
        $relationInfo=$this->OpenRelation->find(
            'first',
            array(
                'conditions' =>
                array(
                'OpenRelation.open_user_id'=>$userId,
                'OpenRelation.open_type'=>$openType,
                'OpenRelation.type'=>$type,
                'OpenRelation.type_id'=>$typeId
                )
            )
        );
        if (empty($relationInfo)) {
            $relationInfo['OpenRelation']['open_type'] = $openType;
            $relationInfo['OpenRelation']['open_type_id'] = $openTypeId;
            $relationInfo['OpenRelation']['open_user_id'] =  $userId;
            $relationInfo['OpenRelation']['type'] = $type; //0:商品,1:订单
            $relationInfo['OpenRelation']['type_id'] = $typeId;
            $this->OpenRelation->save($relationInfo);
        }
    }

    private function _getAccessToken($appId, $appSecret, $openType = 'wechat')
    {
        if ($openType == 'wechat') {
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$appSecret;
        }
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        if (isset($result['access_token'])){
            return $result['access_token'];
        }
        return false;
    }

    private function _validateToken($openModel)
    {
        if (empty($openModel['OpenModel']['token']) || (time() - strtotime($openModel['OpenModel']['modified'])) > 7200) {
            return false;
        }
        return true;
    }
    
    /*
    		群发失败回调通知
    */
    function send_back_notify($requestMsg=null){
    		if(isset($requestMsg->Status)&&$requestMsg->Status!='send success'&&isset($requestMsg->MsgID)){
    			$MsgID=$requestMsg->MsgID;
    			$send_log_conditions=array();
    			$send_log_conditions['OpenElement.response']=$requestMsg->MsgID;
    			$send_log_conditions['or']['OpenElement.creator >']=0;
    			$send_log_conditions['or']['OpenElement.editor >']=0;
    			$send_log_msg=$this->OpenElement->find('first',array('fields'=>'id,title,creator,editor','conditions'=>$send_log_conditions));
    			
    			if(!empty($send_log_msg)){
    				$operator_ids=array(
    					$send_log_msg['OpenElement']['creator'],
    					$send_log_msg['OpenElement']['editor']
    				);
    				$this->loadModel('Operator');
    				$operator_list=$this->Operator->find('all',array('fields'=>'id,name,email,mobile','conditions'=>array('Operator.id'=>$operator_ids)));
    				
    				$receiver_email=array();$receiver_mobile=array();
    				foreach($operator_list as $v){
    					if(trim($v['Operator']['email'])!='')$receiver_email[]=$v['Operator']['email'];
    					if(trim($v['Operator']['mobile'])!='')$receiver_mobile[]=$v['Operator']['mobile'];
    				}
    				$content="您负责维护的素材:".$send_log_msg['OpenElement']['title']." 群发操作异常,请尽快确认";
    				if(!empty($receiver_email)){
    					$subject="素材发送异常";
    					$html_body=$content;
    					$text_body=$content;
    					$mail_send_queue = array(
				                'id' => '',
				                'sender_name' => $this->configs['shop_name'],
				                'receiver_email' => $receiver_email,//接收人姓名;接收人地址
				                'cc_email' => "",
				                'bcc_email' => "",
				                'title' => $subject,
				                'html_body' => $html_body,
				                'text_body' => $text_body,
				                'sendas' => 'html',
				                'flag' => 0,
				                'pri' => 0,
				        );
				        $this->Notify->send_email($mail_send_queue, $this->configs);
    				}
    				if(!empty($receiver_mobile)){
    					$sms_kanal=isset($this->configs['sms_kanal'])?$this->configs['sms_kanal']:'0';
    					foreach($receiver_mobile as $mobile){
    						$this->Notify->send_sms($mobile,$content,$sms_kanal,$this->configs);
    					}
    				}
    			}
    		}
    }
    
    function qywechat(){
    		Configure::write('debug', 1);
    		$this->layout="ajax";
    		$this->loadModel('OrganizationApp');
    		$this->loadModel('OrganizationAppConfigValue');
    		$conditions=array();
    		$conditions['OrganizationApp.organization_id']=0;
    		$conditions['OrganizationApp.type']='QYWechat';
    		$conditions['OrganizationApp.status']='1';
    		$organization_app=$this->OrganizationApp->find('first',array('conditions'=>$conditions));
    		if(empty($organization_app))die("ERR: Config Error");
    		$organization_app_id=$organization_app['OrganizationApp']['id'];
    		$organization_app_config=$this->OrganizationAppConfigValue->find('list',array('fields'=>array('config_code','config_value'),'conditions'=>array('organization_app_id'=>$organization_app_id)));
    		if(empty($organization_app_config))die("ERR: Config Error");
    		if(isset($_REQUEST['msg_signature'])&&trim($_REQUEST['msg_signature'])!=''){
    			$sVerifyEchoStr=isset($_REQUEST['echostr'])?Urldecode($_REQUEST['echostr']):'';
    			$sVerifyMsgSig=isset($_REQUEST['msg_signature'])?Urldecode($_REQUEST['msg_signature']):'';
    			$sVerifyNonce=isset($_REQUEST['nonce'])?Urldecode($_REQUEST['nonce']):'';
    			$sVerifyTimeStamp=isset($_REQUEST['timestamp'])?Urldecode($_REQUEST['timestamp']):'';
    			App::import('Vendor','WXBizMsgCrypt', array('file'=>'open' . DS .'qywechat'.DS.'WXBizMsgCrypt.php'));
    			
    			$RequestToken=isset($_REQUEST['Token'])?$_REQUEST['Token']:'';
    			$RequestEncodingAesKey=isset($_REQUEST['EncodingAESKey'])?$_REQUEST['EncodingAESKey']:'';
    			if($RequestToken==''){
				$token=isset($organization_app_config['Token'])?$organization_app_config['Token']:'';
				$encodingAesKey=isset($organization_app_config['EncodingAESKey'])?$organization_app_config['EncodingAESKey']:'';
			}else{
				$token=$RequestToken;
				$encodingAesKey=$RequestEncodingAesKey;
			}
			if($sVerifyEchoStr!=''){
				$corpId=isset($organization_app_config['CorpID'])?$organization_app_config['CorpID']:'';
				$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $corpId);
	    			// 需要返回的明文
				$sEchoStr = "";
	    			$errCode = $wxcpt->VerifyURL($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $sVerifyEchoStr, $sEchoStr);
	    			if ($errCode == 0) {
					// 验证URL成功，将sEchoStr返回
					echo $sEchoStr;
				} else {
					print("ERR: " . $errCode . "\n\n");
				}
			}else{
				$SuiteId=isset($organization_app_config['SuiteId'])?$organization_app_config['SuiteId']:'';
				$SuiteSecret=isset($organization_app_config['SuiteSecret'])?$organization_app_config['SuiteSecret']:'';
				$wxcpt = new WXBizMsgCrypt($token, $encodingAesKey, $SuiteId);
				$encryptMsg = file_get_contents ( 'php://input' );
				if(empty($encryptMsg)){
					echo 'success';
				}else{
					$sMsg = "";  // 解析之后的明文
					$errCode = $wxcpt->DecryptMsg($sVerifyMsgSig, $sVerifyTimeStamp, $sVerifyNonce, $encryptMsg, $sMsg);
					if ($errCode == 0) {
						echo 'success';
						$XMLRequestData = $this->_xmlToArr($sMsg);
						if(isset($XMLRequestData['InfoType'])){
							$InfoType=$XMLRequestData['InfoType'];
							if($InfoType=='suite_ticket'&&isset($XMLRequestData['SuiteTicket'])){//SuiteTicket推送
								$this->UpdateSuiteTicket($XMLRequestData,$organization_app_id);
								$SuiteToken=$this->UpdateSuiteAccessToken($organization_app_config,$organization_app_id);//检测套件Token更新
								$organization_app_config['SuiteToken']=$SuiteToken;
								$this->UpdatePreAuthCode($organization_app_config,$organization_app_id);//检测套件预授权码
								$this->UpdateProviderToken($organization_app_config,$organization_app_id);//更新ProviderToken
							}else if($InfoType=='create_auth'&&isset($XMLRequestData['AuthCode'])){//授权成功通知
								//$this->set_permanent_code($SuiteId,$SuiteSecret,$XMLRequestData['AuthCode']);
								//$this->log('AuthCode:'.json_encode($XMLRequestData));
							}else if($InfoType=='cancel_auth'&&isset($XMLRequestData['AuthCorpId'])){//解除授权
								$this->QYwechatCancelAuth($XMLRequestData['AuthCorpId']);
							}
							$this->log('XMLRequestData:'.json_encode($XMLRequestData));
						}
					}else{
						$this->log('encryptMsg:'.json_encode($encryptMsg));
						$this->log('errCode:'.$errCode);
						print("ERR: " . $errCode . "\n\n");
					}
				}
			}
    		}
    		die();
    }
    
    function _xmlToArr($xml){
	        $res = @simplexml_load_string($xml, NULL, LIBXML_NOCDATA);
	        $res = json_decode(json_encode($res), true);
	        return $res;
    }
    
    //更新套件ticket
    function UpdateSuiteTicket($SuiteTicketData,$organization_app_id){
    		$ticket=$this->OrganizationAppConfigValue->find('first',array('conditions'=>array('organization_app_id'=>$organization_app_id,'config_code'=>'SuiteTicket')));
    		if(isset($SuiteTicketData['SuiteTicket'])&&$SuiteTicketData['SuiteTicket']!=''){
    			$ticket_data=array(
    				'id'=>isset($ticket['OrganizationAppConfigValue'])?$ticket['OrganizationAppConfigValue']['id']:0,
    				'organization_app_id'=>$organization_app_id,
    				'config_code'=>'SuiteTicket',
    				'config_value'=>$SuiteTicketData['SuiteTicket']
    			);
    			$this->OrganizationAppConfigValue->save($ticket_data);
    		}
    }
    
    //检测套件Token更新
    function UpdateSuiteAccessToken($app_config,$organization_app_id){
    		$suite_access_token="";
    		$SuiteToken_infos=$this->OrganizationAppConfigValue->find('all',array('conditions'=>array('organization_app_id'=>$organization_app_id,'config_code'=>array('SuiteToken','SuiteTokenExpireTime'))));
    		$SuiteToken_Configs=array();
    		if(!empty($SuiteToken_infos)){
	    		foreach($SuiteToken_infos as $v){
	    			$SuiteToken_Configs[$v['OrganizationAppConfigValue']['config_code']]=$v['OrganizationAppConfigValue'];
	    		}
    		}
    		if(empty($SuiteToken_Configs['SuiteTokenExpireTime'])||intval($SuiteToken_Configs['SuiteTokenExpireTime']['config_value'])<=time()){
			$SuiteId=isset($app_config['SuiteId'])?$app_config['SuiteId']:'';
			$SuiteSecret=isset($app_config['SuiteSecret'])?$app_config['SuiteSecret']:'';
			$SuiteTicket=isset($app_config['SuiteTicket'])?$app_config['SuiteTicket']:'';
    			$get_token_url = 'https://qyapi.weixin.qq.com/cgi-bin/service/get_suite_token';
			$params = array(
				'suite_id' => $SuiteId,
				'suite_secret' => $SuiteSecret,
				'suite_ticket'=>$SuiteTicket
			);
			$results = $this->https_request($get_token_url, json_encode($params));
			if(isset($results['suite_access_token'])){
				$suite_access_token=$results['suite_access_token'];
				
				$token_data=array(
	    				'id'=>isset($SuiteToken_Configs['SuiteToken'])?$SuiteToken_Configs['SuiteToken']['id']:0,
	    				'organization_app_id'=>$organization_app_id,
	    				'config_code'=>'SuiteToken',
	    				'config_value'=>$results['suite_access_token']
	    			);
	    			$this->OrganizationAppConfigValue->save($token_data);
	    			
	    			$token_express_time_data=array(
	    				'id'=>isset($SuiteToken_Configs['SuiteTokenExpireTime'])?$SuiteToken_Configs['SuiteTokenExpireTime']['id']:0,
	    				'organization_app_id'=>$organization_app_id,
	    				'config_code'=>'SuiteTokenExpireTime',
	    				'config_value'=>time()+$results['expires_in']-660
	    			);
	    			$this->OrganizationAppConfigValue->save($token_express_time_data);
			}
    		}else{
    			$suite_access_token=isset($SuiteToken_Configs['SuiteToken'])?$SuiteToken_Configs['SuiteToken']['config_value']:'';
    		}
    		return $suite_access_token;
    }
    
    //检测套件预授权码
    function UpdatePreAuthCode($app_config,$organization_app_id){
    		$AuthCode_infos=$this->OrganizationAppConfigValue->find('all',array('conditions'=>array('organization_app_id'=>$organization_app_id,'config_code'=>array('PreAuthCode','PreAuthCodeExpireTime'))));
    		$AuthCode_Configs=array();
    		if(!empty($AuthCode_infos)){
	    		foreach($AuthCode_infos as $v){
	    			$AuthCode_Configs[$v['OrganizationAppConfigValue']['config_code']]=$v['OrganizationAppConfigValue'];
	    		}
    		}
    		if(empty($AuthCode_Configs['SuiteTokenExpireTime'])||intval($AuthCode_Configs['PreAuthCodeExpireTime']['config_value'])<=time()){
			$SuiteId=isset($app_config['SuiteId'])?$app_config['SuiteId']:'';
			$SuiteToken=isset($app_config['SuiteToken'])?$app_config['SuiteToken']:'';
    			$get_token_url = 'https://qyapi.weixin.qq.com/cgi-bin/service/get_pre_auth_code?suite_access_token='.$SuiteToken;
			$params = array(
				'suite_id' => $SuiteId
			);
			$results = $this->https_request($get_token_url, json_encode($params));
			if(isset($results['pre_auth_code'])){
				$token_data=array(
	    				'id'=>isset($AuthCode_Configs['PreAuthCode'])?$AuthCode_Configs['PreAuthCode']['id']:0,
	    				'organization_app_id'=>$organization_app_id,
	    				'config_code'=>'PreAuthCode',
	    				'config_value'=>$results['pre_auth_code']
	    			);
	    			$this->OrganizationAppConfigValue->save($token_data);
	    			
	    			$token_express_time_data=array(
	    				'id'=>isset($AuthCode_Configs['PreAuthCodeExpireTime'])?$AuthCode_Configs['PreAuthCodeExpireTime']['id']:0,
	    				'organization_app_id'=>$organization_app_id,
	    				'config_code'=>'PreAuthCodeExpireTime',
	    				'config_value'=>time()+$results['expires_in']-660
	    			);
	    			$this->OrganizationAppConfigValue->save($token_express_time_data);
	    			if(defined('QYwechatTest'))$this->SetSessionInfo($SuiteToken,$results['pre_auth_code']);
			}
    		}
    }
    
    function SetSessionInfo($suite_access_token='',$pre_auth_code=''){
    		$request_url = 'https://qyapi.weixin.qq.com/cgi-bin/service/set_session_info?suite_access_token='.$suite_access_token;
		$params = array(
			'pre_auth_code' => $pre_auth_code,
			'session_info'=>array(
				'auth_type'=>'1'
			)
		);
		$results = $this->https_request($request_url, json_encode($params));
		if(isset($results['errcode'])&&$results['errcode']=='0'){
			
		}else{
			$this->log('QYwechat SetSessionInfo:'.json_encode($results));
		}
    }
    
    /*
    		更新ProviderToken
    */
    function UpdateProviderToken($app_config,$organization_app_id){
		$provider_access_token="";
    		$ProviderToken_infos=$this->OrganizationAppConfigValue->find('all',array('conditions'=>array('organization_app_id'=>$organization_app_id,'config_code'=>array('ProviderSecretToken','ProviderSecretTokenExpireTime'))));
    		$ProviderToken_Configs=array();
    		if(!empty($ProviderToken_infos)){
	    		foreach($ProviderToken_infos as $v){
	    			$ProviderToken_Configs[$v['OrganizationAppConfigValue']['config_code']]=$v['OrganizationAppConfigValue'];
	    		}
    		}
    		if(empty($ProviderToken_Configs['ProviderSecretTokenExpireTime'])||intval($ProviderToken_Configs['ProviderSecretTokenExpireTime']['config_value'])<=time()){
			$CorpID=isset($app_config['CorpID'])?$app_config['CorpID']:'';
			$ProviderSecret=isset($app_config['ProviderSecret'])?$app_config['ProviderSecret']:'';
			if($ProviderSecret=='')return $provider_access_token;
    			$get_token_url = 'https://qyapi.weixin.qq.com/cgi-bin/service/get_provider_token';
			$params = array(
				'corpid' => $CorpID,
				'provider_secret' => $ProviderSecret
			);
			$results = $this->https_request($get_token_url, json_encode($params));
			if(isset($results['provider_access_token'])){
				$provider_access_token=$results['provider_access_token'];
				$token_data=array(
	    				'id'=>isset($ProviderToken_Configs['ProviderSecretToken'])?$ProviderToken_Configs['ProviderSecretToken']['id']:0,
	    				'organization_app_id'=>$organization_app_id,
	    				'config_code'=>'ProviderSecretToken',
	    				'config_value'=>$results['provider_access_token']
	    			);
	    			$this->OrganizationAppConfigValue->save($token_data);
	    			
	    			$token_express_time_data=array(
	    				'id'=>isset($ProviderToken_Configs['ProviderSecretTokenExpireTime'])?$ProviderToken_Configs['ProviderSecretTokenExpireTime']['id']:0,
	    				'organization_app_id'=>$organization_app_id,
	    				'config_code'=>'ProviderSecretTokenExpireTime',
	    				'config_value'=>time()+$results['expires_in']-660
	    			);
	    			$this->OrganizationAppConfigValue->save($token_express_time_data);
			}
    		}else{
    			$provider_access_token=isset($ProviderToken_Configs['ProviderSecretToken'])?$ProviderToken_Configs['ProviderSecretToken']['config_value']:'';
    		}
    		return $provider_access_token;
	}
	
	/*
		解除企业微信授权
	*/
	function QYwechatCancelAuth($corpId=''){
		$organization_app_ids=$this->OrganizationAppConfigValue->find('list',array('fields'=>'id,organization_app_id','conditions'=>array('OrganizationAppConfigValue.config_code'=>'AuthCorpid','OrganizationAppConfigValue.config_value'=>$corpId)));
		if(!empty($organization_app_ids)){
			$this->loadModel('OrganizationAppRelation');
			$this->OrganizationAppRelation->deleteAll(array('organization_app_id'=>$organization_app_ids));
			$this->OrganizationAppConfigValue->deleteAll(array('organization_app_id'=>$organization_app_ids));
			$this->OrganizationApp->deleteAll(array('id'=>$organization_app_ids));
		}
	}
    
    /*
        去除字符串空格
    */
    private function emptyreplace($str){
        $str = trim($str);  
        $str = strip_tags($str,"");  
        $str = ereg_replace("\t","",$str);  
        $str = ereg_replace("\r\n","",$str);  
        $str = ereg_replace("\r","",$str);  
        $str = ereg_replace("\n","",$str);  
        $str = ereg_replace(" "," ",$str);  
        return trim($str);
    }
    
    /*
        调用接口
    */
    private function https_request($url, $data = null){
	        $curl = curl_init();
	        curl_setopt($curl, CURLOPT_URL, $url);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	        if (!empty($data)) {
		            curl_setopt($curl, CURLOPT_POST, 1);
		            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	        }
	        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	        $output = curl_exec($curl);
	        curl_close($curl);
	        return json_decode($output, true);
    }
    
    //创建路径
    public function mkdirs($path, $mode = 0777){
        $dirs = explode('/', $path);
        $pos = strrpos($path, '.');
        if ($pos === false) {
            $subamount = 0;
        } else {
            $subamount = 1;
        }
        for ($c = 0;$c < count($dirs) - $subamount; ++$c) {
            $thispath = '';
            for ($cc = 0; $cc <= $c; ++$cc) {
                $thispath .= $dirs[$cc].'/';
            }
            if (!file_exists($thispath)) {
                mkdir($thispath, $mode);
            }
        }
    }
}
