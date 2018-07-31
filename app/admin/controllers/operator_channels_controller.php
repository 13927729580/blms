<?php

/*****************************************************************************
 * Seevia 关注用户管理
* ===========================================================================
* 版权所有  上海实玮网络科技有限公司，并保留所有权利。
* 网站地址: http://www.seevia.cn
* ---------------------------------------------------------------------------
* 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
* 不允许对程序代码以任何形式任何目的的再发布。
* ===========================================================================
* $开发: 上海实玮$
* $Id$*/

class OperatorChannelsController extends AppController{
    public $name = 'OperatorChannels';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination');
    public $uses = array('OperatorChannel','OperatorChannelConfig','OperatorChannelConfigValue','OperatorChannelRelation','WebserviceLog','Department','OrganizationDepartment');
    
    public function index($page = 1){
        $this->operator_privilege('view_operator_source');
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operators'],'url' => '/operators/');
        $this->navigations[] = array('name' => $this->ld['wechat_operator_manage'],'url' => '');
        $this->set('title_for_layout', $this->ld['wechat_operator_manage'].' - '.$this->configs['shop_name']);
        $this->Department->set_locale($this->backend_locale);
        $operator_channel_info = $this->OperatorChannel->find('all',array('conditions'=>array()));
        //pr($operator_channel_info);
        $this->set('operator_channel_info',$operator_channel_info);
        //分页start
        $condition = '';
        $total = $this->OperatorChannel->find('count', array('conditions'=>$condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['operator_channel']) && $_GET['operator_channel'] != '') {
            $page = $_GET['operator_channel'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'operator_channels','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'OperatorChannel');
        $this->Pagination->init($condition, $parameters, $options);
        //分页end
        $department_con = $this ->OperatorChannelRelation->find('list',array('conditions'=>array('OperatorChannelRelation.relation_type'=>1),'fields'=>array('OperatorChannelRelation.relation_type_id')));
        $department_list = $this->Department->find('all',array('conditions'=>array('Department.id'=>$department_con)));
        $operator_list = $this->Operator->find('all',array('conditions'=>array('Operator.status'=>1)));
        $this->set('department_list',$department_list);
        $this->set('operator_list',$operator_list);
    }

    public function view($id=0){
        $this->navigations[] = array('name' => $this->ld['manage_system'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['operators'],'url' => '/operators/');
        $this->navigations[] = array('name' => $this->ld['wechat_operator_manage'],'url' => '/operator_channels/');
        $this->navigations[] = array('name' => $this->ld['edit'],'url' => '');
        $this->set('title_for_layout', $this->ld['edit'].' - '.$this->configs['shop_name']);
        $this->set('channel_id',$id);
        $channel_info = $this->OperatorChannel->find('first',array('conditions'=>array('OperatorChannel.id'=>$id)));
        $this->set('channel_info',$channel_info);
        if($id != 0){
            $channel_config_info = $this->OperatorChannelConfig->find('all',array('conditions'=>array('OperatorChannelConfig.operator_channel_id'=>$id)));
            //pr($channel_config_info);
            $this->set('channel_config_info',$channel_config_info);
            $channel_config_value = $this->OperatorChannelConfigValue->find('all',array('conditions'=>array('OperatorChannelConfigValue.operator_channel_id'=>$id)));
            if(isset($channel_config_value)&&count($channel_config_value)>0){
                foreach ($channel_config_value as $k => $v) {
                $channel_config_value_check[$v['OperatorChannelConfigValue']['config_code']] = $v;
                }
               //pr($config_value);
                $this->set('channel_config_value_check',$channel_config_value_check);
            }  
        }
        //pr($this->data);
        if($this->RequestHandler->isPOST()){
            //pr($this->data);exit();
            if(isset($_POST['channel_code'])&&$_POST['channel_code']!=''){
                $channel_info['OperatorChannel']['code'] = $_POST['channel_code'];
            }
            if(isset($_POST['channel_name'])&&$_POST['channel_name']!=''){
                $channel_info['OperatorChannel']['name'] = $_POST['channel_name'];
            }
            if(isset($_POST['channel_desc'])&&$_POST['channel_desc']!=''){
                $channel_info['OperatorChannel']['description'] = $_POST['channel_desc'];
            }
            if(isset($_POST['channel_status'])&&$_POST['channel_status']!=''){
                $channel_info['OperatorChannel']['status'] = $_POST['channel_status'];   
            }
            $this->OperatorChannel->save($channel_info);
            //保存配置信息
            if(isset($this->data)&&count($this->data)>0){
                foreach ($this->data['config'] as $k => $v) {
                    $t[$k] = $v;
                }
                $config_value = $this->OperatorChannelConfigValue->find('all',array('conditions'=>array('OperatorChannelConfigValue.operator_channel_id'=>$id)));
                foreach ($config_value as $k => $v) {
                    $config_value_check[$v['OperatorChannelConfigValue']['config_code']] = $v;
                }
                foreach ($this->data['config'] as $k => $v) {
                    if(isset($config_value_check[$k])){
                        $config_value_check[$k]['OperatorChannelConfigValue']['config_value'] = $v;
                        $this->OperatorChannelConfigValue->save($config_value_check[$k]);
                    }else{
                        $config_v['OperatorChannelConfigValue']['id'] = 0;
                        $config_v['OperatorChannelConfigValue']['operator_channel_id'] = $id;
                        $config_v['OperatorChannelConfigValue']['config_code'] = $k;
                        $config_v['OperatorChannelConfigValue']['config_value'] = $v;
                        $this->OperatorChannelConfigValue->save($config_v);
                    }                  
                }
            }
            $this->redirect('/operator_channels/');

        }
    }

    public function delete_channel(){
        Configure::write('debug',0);
        $this->layout = 'ajax';
        
        $result=array();
        $result['code']="0";
        $result['message']='';
        if(!empty($_POST)){
            if(isset($_POST['channel_id'])&&$_POST['channel_id']!=''){
                $this->OperatorChannel->deleteAll(array('OperatorChannel.id'=>$_POST['channel_id']));
                $this->OperatorChannelConfig->deleteAll(array('OperatorChannelConfig.operator_channel_id'=>$_POST['channel_id']));
                $this->OperatorChannelConfigValue->deleteAll(array('OperatorChannelConfigValue.operator_channel_id'=>$_POST['channel_id']));
                $result['code']="1";
            }
        }
        die(json_encode($result));
    }
    
    
    function ajax_load_channel(){
    		Configure::write('debug',0);
        	$this->layout = 'ajax';
        	
        	$result=array();
        	$result['code']="0";
        	$result['message']='';
        	$channel_infos=$this->OperatorChannel->find('all',array('conditions'=>array('OperatorChannel.status'=>'1'),'order'=>'OperatorChannel.id'));
        	if(!empty($channel_infos)){
        		$channel_lists=array();
        		$operator_channel_ids=array();
	        	foreach($channel_infos as $v)$operator_channel_ids[]=$v['OperatorChannel']['id'];
	        	$channel_params=$this->OperatorChannelConfigValue->find('list',array('fields'=>'config_code,config_value,operator_channel_id','conditions'=>array('OperatorChannelConfigValue.operator_channel_id'=>$operator_channel_ids)));
	        	foreach($channel_infos as $v){
	        		$channel_code=$v['OperatorChannel']['code'];
	        		$channel_config=isset($channel_params[$v['OperatorChannel']['id']])?$channel_params[$v['OperatorChannel']['id']]:array();
	        		$redirect_link="";
	        		$redirect_url=urlencode($this->server_host.$this->admin_webroot.'operator_channels/channel_callback/'.$channel_code);
	        		if($channel_code=='qywechat'){
	    				$CorpID=isset($channel_config['CorpID'])?$channel_config['CorpID']:'';
	    				$redirect_link="https://open.weixin.qq.com/connect/oauth2/authorize?appid={$CorpID}&redirect_uri={$redirect_url}&response_type=code&scope=snsapi_base&agentid=&state=SEEVIA#wechat_redirect";
		        	}
	        		$channel_lists[]=array(
	        			'Channel'=>$v['OperatorChannel'],
	        			'Config'=>$channel_config,
	        			'redirect_link'=>$redirect_link
	        		);
	        	}
	        	$result['code']="1";
        		$result['message']=$channel_lists;
        		$result['user_agent']=isset($_SERVER['HTTP_USER_AGENT'])?$_SERVER['HTTP_USER_AGENT']:'';
        	}
        	die(json_encode($result));
    }
    
    function channel_callback($channel_code=''){
    		Configure::write('debug',1);
        	$this->layout = 'ajax';
		
        	$channel_info=$this->OperatorChannel->find('first',array('conditions'=>array('OperatorChannel.code'=>$channel_code,'OperatorChannel.status'=>'1')));
        	if(!empty($channel_info)){
        		$relation_type_value="";
        		$operator_channel_id=$channel_info['OperatorChannel']['id'];
    			$channel_params=$this->OperatorChannelConfigValue->find('list',array('fields'=>'config_code,config_value','conditions'=>array('OperatorChannelConfigValue.operator_channel_id'=>$operator_channel_id)));
        		if($channel_code=='qywechat'){
        			$access_token=isset($channel_params['AgentToken'])?trim($channel_params['AgentToken']):'';
    				$access_token_expire_time=isset($channel_params['AgentTokenExpireTime'])?strtotime($channel_params['AgentTokenExpireTime']):0;
    				if($access_token==''||$access_token_expire_time<(time()-300)){
    					$access_token=$this->api_update_channel_token($channel_code,$operator_channel_id,$channel_params,'AgentToken');
    				}
        			if(isset($_REQUEST['auth_code'])){
        				$access_token="1KiTVwV1stqDiIG-xN9blyd_hYOLZzyJjhQodc6dk5kyx35TFRZH18No5cg2rG2l";//应用套件Secret
        				$auth_code=$_REQUEST['auth_code'];
					$getuserinfo_url="https://qyapi.weixin.qq.com/cgi-bin/service/get_login_info?access_token={$access_token}";
					$post_data=array(
						'auth_code'=>$auth_code
					);
					$api_result = $this->https_request($getuserinfo_url,json_encode($post_data));
					pr($api_result);die();
        			}else if(isset($_REQUEST['code'])){
        				$code=$_REQUEST['code'];
        				$getuserinfo_url="https://qyapi.weixin.qq.com/cgi-bin/user/getuserinfo?access_token={$access_token}&code={$code}";
        				$api_result = json_decode(file_get_contents($getuserinfo_url),true);
        				if(isset($api_result['errcode'])&&$api_result['errcode']=='0'){
        					$relation_type_value=$api_result['UserId'];
        				}else{
			    			$this->WebserviceLog->save(array(
			    				'id'=>0,
			    				'nick'=>$this->ld['system'],
			    				'method'=>'qywechat/getuserinfo',
			    				'post_data'=>json_encode(array($getuserinfo_url)),
			    				'return_data'=>json_encode($api_result),
			    				'status'=>'0',
			    				'error_message'=>isset($api_result['errmsg'])?$api_result['errmsg']:'',
			    				'remark'=>''
			    			));
        				}
        			}
        		}
        		if($relation_type_value!=''){
	        		$relation_cond=array(
					'operator_channel_id'=>$operator_channel_id,
					'relation_type'=>0,
					'value'=>$api_result['UserId'],
					'relation_type_id >'=>0
				);
				$operator_relation_info=$this->OperatorChannelRelation->find('first',array('conditions'=>$relation_cond));
				if(!empty($operator_relation_info)){
					$operator = $this->Operator->find('first',array('conditions'=>array('id'=>$operator_relation_info['OperatorChannelRelation']['relation_type_id'],'status'=>'1')));
					if(!empty($operator)){
						$this->Cookie->delete('count_login');
						$operator['Operator']['last_login_time'] = date('Y-m-d H:i:s');
						$operator['Operator']['last_login_ip'] = $_SERVER['REMOTE_ADDR'];
						$operator['Operator']['session'] = session_id();
						$operator['Operator']['default_lang'] =$this->backend_locale;
						$this->Operator->save($operator);//更新IP地址  和  登入时间
						$this->Cookie->write('session', session_id(), false, '1 day');
					}
				}
        		}
        	}
        	$this->redirect('/pages/home');
    }
    
    function ajax_dowmload_qyinfo($channel_code=''){
    		Configure::write('debug',1);
        	$this->layout = 'ajax';
        	$result=array();
        	$result['code']="0";
        	$result['message']='';
              $channel_code = isset($_POST['channel_code'])?$_POST['channel_code']:$channel_code;
        	$channel_info=$this->OperatorChannel->find('first',array('conditions'=>array('OperatorChannel.code'=>$channel_code,'OperatorChannel.status'=>'1')));
        	if(!empty($channel_info)){
        		$operator_channel_id=$channel_info['OperatorChannel']['id'];
    			$channel_params=$this->OperatorChannelConfigValue->find('list',array('fields'=>'config_code,config_value','conditions'=>array('OperatorChannelConfigValue.operator_channel_id'=>$operator_channel_id)));
    			if($channel_code=='qywechat'){
    				$access_token=isset($channel_params['ContactAccessToken'])?trim($channel_params['ContactAccessToken']):'';
    				$access_token_expire_time=isset($channel_params['ContactAccessTokenExpireTime'])?strtotime($channel_params['ContactAccessTokenExpireTime']):0;
    				if($access_token==''||$access_token_expire_time<time()){
    					$access_token=$this->api_update_channel_token($channel_code,$operator_channel_id,$channel_params,'Contact');
    				}
    				if($access_token!=''){
    					$request_url="https://qyapi.weixin.qq.com/cgi-bin/department/list?access_token={$access_token}";
    					$api_result = json_decode(file_get_contents($request_url),true);
    					if(isset($api_result['errcode'])&&$api_result['errcode']=='0'){
    						$organization_department_list=$api_result['department'];
    						$department_group_list=array();
    						$organization_department_menbers=array();
		    				foreach($organization_department_list as $v){
		    					$department_group_list[$v['parentid']][]=$v;
		    					$organization_department_id=$v['id'];
		    					$organization_department_menbers[$organization_department_id]=$this->api_dowmload_member($organization_department_id,$channel_code,$access_token);
		    				}
		    				$organization_department_tree=$this->qywechat_department_tree(0,$department_group_list);
                            $operator_channel_relation_department = $this->OperatorChannelRelation->find('list',array('conditions'=>array('OperatorChannelRelation.relation_type'=>1),'fields'=>'value,relation_type_id,operator_channel_id'));
                            $operator_channel_relation_menber = $this->OperatorChannelRelation->find('list',array('conditions'=>array('OperatorChannelRelation.relation_type'=>0),'fields'=>'value,relation_type_id,operator_channel_id'));
		    				$result['code']="1";
    						$result['organization_department_list']=$organization_department_tree;
    						$result['organization_department_menbers']=$organization_department_menbers;
                            $result['organization_department_relation']=$operator_channel_relation_department;
                            $result['organization_department_menbers_relation']=$operator_channel_relation_menber;
    					}
		    			$this->WebserviceLog->save(array(
		    				'id'=>0,
		    				'nick'=>$this->ld['system'],
		    				'method'=>'qywechat/department/list',
		    				'post_data'=>json_encode($channel_params),
		    				'return_data'=>json_encode($api_result),
		    				'status'=>isset($api_result['department'])?'1':'0',
		    				'error_message'=>!isset($api_result['department'])?(isset($api_result['errmsg'])?$api_result['errmsg']:''):'',
		    				'remark'=>''
		    			));
    				}
    			}
    		}
    		die(json_encode($result));
    }
    
    function api_dowmload_member($department_id=0,$channel_type='',$access_token=''){
    		$member_info=array();
    		if($channel_type=='qywechat'){
			$request_url="https://qyapi.weixin.qq.com/cgi-bin/user/list?access_token={$access_token}&department_id={$department_id}&fetch_child=0";
			$api_result = json_decode(file_get_contents($request_url),true);
			if(isset($api_result['errcode'])&&$api_result['errcode']=='0'){
				$member_info=$api_result['userlist'];
			}
    			$this->WebserviceLog->save(array(
    				'id'=>0,
    				'nick'=>$this->ld['system'],
    				'method'=>'qywechat/user/list',
    				'post_data'=>json_encode(array('access_token'=>$access_token,'department_id'=>$department_id)),
    				'return_data'=>json_encode($api_result),
    				'status'=>isset($api_result['userlist'])?'1':'0',
    				'error_message'=>!isset($api_result['userlist'])?(isset($api_result['errmsg'])?$api_result['errmsg']:''):'',
    				'remark'=>''
    			));
		}
		return $member_info;
    }
    
    function qywechat_department_tree($parent_id,$department_infos){
    		$department_tree=array();
    		$department_data = isset($department_infos[$parent_id])?$department_infos[$parent_id]:array();
		if(!empty($department_data)){
			foreach ($department_data as $v) {
				$child = $this ->qywechat_department_tree($v['id'],$department_infos);
				if(!empty($child))$v['child_department']=$child;
				$department_tree[] = $v;
			}
		}
		return $department_tree;
    } 
    
    /*
    		获取部门
    */
    function ajax_dowmload_department($channel_code=''){
    		Configure::write('debug',1);
        	$this->layout = 'ajax';
        	$result=array();
        	$result['code']="0";
        	$result['message']='';
              $channel_code = isset($_POST['channel_code'])?$_POST['channel_code']:$channel_code;
        	$channel_info=$this->OperatorChannel->find('first',array('conditions'=>array('OperatorChannel.code'=>$channel_code,'OperatorChannel.status'=>'1')));
        	if(!empty($channel_info)){
        		$operator_channel_id=$channel_info['OperatorChannel']['id'];
    			$channel_params=$this->OperatorChannelConfigValue->find('list',array('fields'=>'config_code,config_value','conditions'=>array('OperatorChannelConfigValue.operator_channel_id'=>$operator_channel_id)));
    			if($channel_code=='qywechat'){
    				$access_token=isset($channel_params['ContactAccessToken'])?trim($channel_params['ContactAccessToken']):'';
    				$access_token_expire_time=isset($channel_params['ContactAccessTokenExpireTime'])?strtotime($channel_params['ContactAccessTokenExpireTime']):0;
    				if($access_token==''||$access_token_expire_time<time()){
    					$access_token=$this->api_update_channel_token($channel_code,$operator_channel_id,$channel_params,'Contact');
    				}
    				if($access_token!=''){
    					$request_url="https://qyapi.weixin.qq.com/cgi-bin/department/list?access_token={$access_token}";
    					$api_result = json_decode(file_get_contents($request_url),true);
    					if(isset($api_result['errcode'])&&$api_result['errcode']=='0'){
    						$result['code']="1";
    						$result['data']=$api_result['department'];
    					}else{
    						$result['data']=isset($api_result['errmsg'])?$api_result['errmsg']:'获取失败';
    					}
		    			$this->WebserviceLog->save(array(
		    				'id'=>0,
		    				'nick'=>$this->ld['system'],
		    				'method'=>'qywechat/department/list',
		    				'post_data'=>json_encode($channel_params),
		    				'return_data'=>json_encode($api_result),
		    				'status'=>isset($api_result['department'])?'1':'0',
		    				'error_message'=>!isset($api_result['department'])?(isset($api_result['errmsg'])?$api_result['errmsg']:''):'',
		    				'remark'=>''
		    			));
    				}
    			}
        	}
        	die(json_encode($result));
    }
    
    /*
    		获取部门成员
    */
    function ajax_dowmload_member($channel_code=''){
    		Configure::write('debug',1);
        	$this->layout = 'ajax';
        	$result=array();
        	$result['code']="0";
        	$result['message']='';
            $channel_code = isset($_POST['channel_code'])?$_POST['channel_code']:$channel_code;
        	$channel_info=$this->OperatorChannel->find('first',array('conditions'=>array('OperatorChannel.code'=>$channel_code,'OperatorChannel.status'=>'1')));
        	if(!empty($channel_info)){
        		$operator_channel_id=$channel_info['OperatorChannel']['id'];
    			$channel_params=$this->OperatorChannelConfigValue->find('list',array('fields'=>'config_code,config_value','conditions'=>array('OperatorChannelConfigValue.operator_channel_id'=>$operator_channel_id)));
    			if($channel_code=='qywechat'){
    				$department_id=isset($_REQUEST['department_id'])?$_REQUEST['department_id']:0;
    				$access_token=isset($channel_params['ContactAccessToken'])?trim($channel_params['ContactAccessToken']):'';
    				$access_token_expire_time=isset($channel_params['ContactAccessTokenExpireTime'])?strtotime($channel_params['ContactAccessTokenExpireTime']):0;
    				if($access_token==''||$access_token_expire_time<time()){
    					$access_token=$this->api_update_channel_token($channel_code,$operator_channel_id,$channel_params,'Contact');
    				}
    				if($access_token!=''){
    					$request_url="https://qyapi.weixin.qq.com/cgi-bin/user/list?access_token={$access_token}&department_id={$department_id}&fetch_child=1";
    					$api_result = json_decode(file_get_contents($request_url),true);
    					if(isset($api_result['errcode'])&&$api_result['errcode']=='0'){
    						$result['code']="1";
    						$result['data']=$api_result['userlist'];
    					}else{
    						$result['data']=isset($api_result['errmsg'])?$api_result['errmsg']:'获取失败';
    					}
		    			$this->WebserviceLog->save(array(
		    				'id'=>0,
		    				'nick'=>$this->ld['system'],
		    				'method'=>'qywechat/user/list',
		    				'post_data'=>json_encode($channel_params),
		    				'return_data'=>json_encode($api_result),
		    				'status'=>isset($api_result['userlist'])?'1':'0',
		    				'error_message'=>!isset($api_result['department'])?(isset($api_result['errmsg'])?$api_result['errmsg']:''):'',
		    				'remark'=>''
		    			));
    				}
    			}
        	}
        	die(json_encode($result));
    }
    
    /*
    		获取Token
    */
    public function ajax_get_channel_token($channel_code=''){
    		Configure::write('debug',0);
        	$this->layout = 'ajax';
        	$result=array();
        	$result['code']="0";
        	$result['message']='';
    		$channel_info=$this->OperatorChannel->find('first',array('conditions'=>array('OperatorChannel.code'=>$channel_code,'OperatorChannel.status'=>'1')));
    		if(!empty($channel_info)){
    			$token_code=isset($_POST['token_code'])?$_POST['token_code']:'Token';
    			$operator_channel_id=$channel_info['OperatorChannel']['id'];
    			$channel_params=$this->OperatorChannelConfigValue->find('list',array('fields'=>'config_code,config_value','conditions'=>array('OperatorChannelConfigValue.operator_channel_id'=>$operator_channel_id)));
    			if($channel_code=='qywechat'){
    				$access_token=isset($channel_params[$token_code])?trim($channel_params[$token_code]):'';
    				$access_token_expire_time=isset($channel_params[$token_code.'ExpireTime'])?strtotime($channel_params[$token_code.'ExpireTime']):0;
    				if($access_token==''||$access_token_expire_time<time()){
    					$access_token=$this->api_update_channel_token($channel_code,$operator_channel_id,$channel_params,$token_code);
    				}
    				if($access_token!=''){
    					$result['code']="1";
        				$result['message']=$access_token;
    				}
    			}
    		}
    		die(json_encode($result));
    }
    
    /*
    		Api更新Token
    */
    private function api_update_channel_token($channel_code='',$operator_channel_id=0,$params=array(),$token_code='Token'){
    		$access_token="";
    		if($channel_code=='qywechat'){
    			$corpid=isset($params['CorpID'])?$params['CorpID']:'';
    			$token_secret_key=$token_code.'Secret';
    			$token_secret_key=str_replace('Token','',$token_secret_key);
    			$token_secret=isset($params[$token_secret_key])?$params[$token_secret_key]:'';
    			$request_url="https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid={$corpid}&corpsecret={$token_secret}";
    			$result = json_decode(file_get_contents($request_url),true);
    			if(isset($result['errcode'])&&$result['errcode']=='0'){
    				$token_code.="AccessToken";
    				$config_codes=array(
    					"{$token_code}","{$token_code}ExpireTime"
    				);
    				$channel_config=$this->OperatorChannelConfigValue->find('list',array('fields'=>'config_code,id','conditions'=>array('OperatorChannelConfigValue.operator_channel_id'=>$operator_channel_id,'config_code'=>$config_codes)));
    				$channel_config_data=array(
    					'id'=>isset($channel_config[$token_code])?$channel_config[$token_code]:0,
    					'operator_channel_id'=>$operator_channel_id,
    					'config_code'=>$token_code,
    					'config_value'=>$result['access_token']
    				);
    				$this->OperatorChannelConfigValue->save($channel_config_data);
    				$channel_config_data=array(
    					'id'=>isset($channel_config[$token_code.'ExpireTime'])?$channel_config[$token_code.'ExpireTime']:0,
    					'operator_channel_id'=>$operator_channel_id,
    					'config_code'=>$token_code.'ExpireTime',
    					'config_value'=>date('Y-m-d H:i:s',time()+7000)
    				);
    				$this->OperatorChannelConfigValue->save($channel_config_data);
    				$access_token=$result['access_token'];
    			}else{
    				$channel_config_data=array(
    					'id'=>isset($channel_config[$token_code])?$channel_config[$token_code]:0,
    					'operator_channel_id'=>$operator_channel_id,
    					'config_code'=>$token_code,
    					'config_value'=>''
    				);
    				$this->OperatorChannelConfigValue->save($channel_config_data);
    				$channel_config_data=array(
    					'id'=>isset($channel_config[$token_code.'ExpireTime'])?$channel_config[$token_code.'ExpireTime']:0,
    					'operator_channel_id'=>$operator_channel_id,
    					'config_code'=>$token_code.'ExpireTime',
    					'config_value'=>0
    				);
    				$this->OperatorChannelConfigValue->save($channel_config_data);
    			}
    			$this->WebserviceLog->save(array(
    				'id'=>0,
    				'nick'=>$this->ld['system'],
    				'method'=>'qywechat/gettoken/'.$token_code,
    				'post_data'=>json_encode($params),
    				'return_data'=>json_encode($result),
    				'status'=>isset($result['access_token'])?'1':'0',
    				'error_message'=>!isset($result['access_token'])?(isset($result['errmsg'])?$result['errmsg']:''):'',
    				'remark'=>''
    			));
    		}
    		return $access_token;
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
		pr($output);
		return json_decode($output, true);
    }
    
    /*
        去除字符串空格
    */
    private function emptyreplace($str){
        $str = trim($str);
        $str = strip_tags($str, '');
        $str = ereg_replace("\t", '', $str);
        $str = ereg_replace("\r\n", '', $str);
        $str = ereg_replace("\r", '', $str);
        $str = ereg_replace("\n", '', $str);
        $str = ereg_replace(' ', ' ', $str);

        return trim($str);
    }

    /*
        $data   需要转换josn提交的数据
    */
    private function to_josn($data){
	        $this->arrayRecursive($data, 'urlencode');
	        $json = json_encode($data);
	        return urldecode($json);
    }
    
    
    /**************************************************************
     * 对数组中所有元素做处理,保留中文
     * @param string &$array 要处理的数组
     * @param string $function 要执行的函数
     * @return boolean $apply_to_keys_also 是否也应用到key上
     * @access public
     *
     *************************************************************/
    public function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }
            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        --$recursive_counter;
    }

    public function my_department_list(){
        Configure::write('debug',1);
        $this->layout = 'ajax';
        
        $result=array();
        $result['code']="0";
        $result['message']='';
        if(!empty($_POST)){
            if($_POST['key']==1){
                $depart_tree = $this->Department->tree();
                $operator_condition = $this->OperatorChannelRelation->find('list',array('conditions'=>array('relation_type'=>0),'fields'=>'relation_type_id'));
                $operator_list = $this->Operator->find('all',array('conditions'=>array('Operator.status'=>1,'id'=>$operator_condition)));
                $result['code']="1";
                $result['message']['department_list']=$depart_tree;
                $result['message']['operator_list']=$operator_list;
            }
        }
        die(json_encode($result));
    }

    public function department_import(){
        Configure::write('debug',1);
        $this->layout = 'ajax';
        
        $result=array();
        $result['code']="0";
        $result['message']='';
        if(!empty($_POST)){
            //pr($_POST);exit();
            $department_id = $this->OperatorChannelRelation->find('first',array('conditions'=>array('operator_channel_id'=>$_POST['operator_channel_id'],'value'=>$_POST['parent_id'])));
            $add_department = array(
                'parent_id'=>isset($department_id['OperatorChannelRelation']['relation_type_id'])?$department_id['OperatorChannelRelation']['relation_type_id']:0,
                'name'=>$_POST['name'],
                'status'=>0,
                );
            $this->Department->save($add_department);
            $department_id = $this->Department->id;
            $department_child_list = $this->Department->find('all',array('conditions'=>array('status'=>0,'parent_id'=>$department_id)));
            $add_relation = array(
                'operator_channel_id'=>$_POST['operator_channel_id'],
                'relation_type'=>$_POST['relation_type'],
                'relation_type_id'=>$department_id,
                'value'=>$_POST['value']
                );
            $this->OperatorChannelRelation->save($add_relation);
            $result['code']="1";
        }
        die(json_encode($result));
    }

    public function menber_import(){
        Configure::write('debug',1);
        $this->layout = 'ajax';
        
        $result=array();
        $result['code']="0";
        $result['message']='';
        if(!empty($_POST)){
            $department_id = $this->OperatorChannelRelation->find('first',array('conditions'=>array('operator_channel_id'=>$_POST['operator_channel_id'],'value'=>$_POST['department_id'])));
            $department_info = $this->Department->find('first',array('conditions'=>array('id'=>$department_id['OperatorChannelRelation']['relation_type_id'])));
            $add_operator = array(
                'name'=>$_POST['name'],
                'mobile'=>$_POST['mobile'],
                'email'=>$_POST['email'],
                'department_id'=>$department_info['Department']['id'],
                'status'=>1,
                );
            $this->Operator->save($add_operator);
            $operator_id = $this->Operator->id;
            $add_relation = array(
                'operator_channel_id'=>$_POST['operator_channel_id'],
                'relation_type'=>0,
                'relation_type_id'=>$operator_id,
                'value'=>$_POST['user_id']
                );
            $this->OperatorChannelRelation->save($add_relation);
            $result['code']="1";
        }
        die(json_encode($result));
    }

    public function department_merge(){
        Configure::write('debug',1);
        $this->layout = 'ajax';
        
        $result=array();
        $result['code']="0";
        $result['message']='';
        if(!empty($_POST)){
            $add_department = array(
                'id'=>$_POST['id'],
                'name'=>$_POST['name']
                );
            $this->Department->save($add_department);
            $result['code']="1";
        }
        die(json_encode($result));
    }

    public function menber_merge(){
        Configure::write('debug',1);
        $this->layout = 'ajax';
        
        $result=array();
        $result['code']="0";
        $result['message']='';
        if(!empty($_POST)){
            $relation_id = $this->OperatorChannelRelation->find('first',array('conditions'=>array('relation_type_id'=>$_POST['id'])));
            $add_operator = array(
                'id'=>$_POST['id'],
                'mobile'=>$_POST['mobile'],
                'email'=>$_POST['email'],
                );
            $this->Operator->save($add_operator);

            $add_relation = array(
                'id'=>$relation_id['OperatorChannelRelation']['id'],
                'value'=>$_POST['user_id']
                );
            $this->OperatorChannelRelation->save($add_relation);
            $result['code']="1";
        }
        die(json_encode($result));
    }
}
