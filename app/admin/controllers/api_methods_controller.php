<?php
class ApiMethodsController extends  AppController
	{
		var $name='ApiMethods';
		public $components = array('RequestHandler','Pagination','Phpexcel','Phpcsv');
		public $helper=array('Pagination');
		public $uses = array('Profile','ProfileFiled','Operator','Config','Application','ApiMethod','Route','ApiProject','ApiCategory','ApiObject','ApiObjectField','ApiMethodRequest','ApiMethodResponse','ApiMethodResponseExample','ApiMethodRequestExample','ApiMethodResponseException','ApiMethodErrorCode','ApiErrorCodeInterpretation','ApiMethodFaq','ApiProjectCommonParameter');
		
		

	 //-请求参数管理		
		public function api_method_request($main_id="",$type = 'list')
		{
		            Configure::write('debug', 0);
		            $this->layout = 'ajax';
		            $this->set('type', $type);
		            
		            //根据Id，获取当前方法的详细信息
		            $method_info=$this->ApiMethod->find('first',array('conditions'=>array('ApiMethod.id'=>$main_id)));
		            $this->set('method_info',$method_info);
		            $project_info=$this->ApiProject->find('first',array('conditions'=>array('ApiProject.code'=>$method_info['ApiMethod']['api_project_code'])));
		            $this->set('project_info',$project_info);
		            //请求参数管理——列表显示
		            if ($type == 'list') 
		            {
			           	 if (!empty($method_info)) 
			           	 {
					     $method_request_condition='';
		                        $method_request_condition['AND']['ApiMethodRequest.api_project_code']  = $method_info['ApiMethod']['api_project_code'];
		                        $method_request_condition['AND']['ApiMethodRequest.api_category_code']  = $method_info['ApiMethod']['api_category_code'];
		                        $method_request_condition['AND']['ApiMethodRequest.api_method_code']  = $method_info['ApiMethod']['code'];
					     $method_request_infos=$this->ApiMethodRequest->find('all',array('conditions'=>$method_request_condition,'order'=>'ApiMethodRequest.orderby ASC'));
					     $this->set('method_request_infos',$method_request_infos);
		  		        }
		  		        
	  		      //请求参数管理——编辑操作
			      }elseif ($type == 'edit') {
			                 $edit_id= isset($_POST['Id']) ? $_POST['Id'] : 0;
		                	    $method_request_info = $this->ApiMethodRequest->find('first', array('conditions' => array('ApiMethodRequest.id' => $edit_id)));
		                	    $this->set('method_request_info', $method_request_info);		      
                		//请求参数管理——保存数据   
		            }elseif ($type == 'save') {
		            	    $result['code'] = 0;
			                 if (!empty($this->data)) 
			                 {
			                      if ($this->ApiMethodRequest->save($this->data))
			                       {
			                            $result['code'] = 1;
			                        }
			                  }
               			    die(json_encode($result));
               		//请求参数管理——删除操作
		            }elseif ($type == 'del') {
		            
		            	$result['code'] = 0;
			                $del_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
			                if ($this->ApiMethodRequest->delete(array('id' => $del_id))) 
			                {
			                    $result['code'] = 1;
			                  
			                }
			                die(json_encode($result));
		            }	
 
		}
		

//--请求示例管理			
		public function api_method_request_example($main_id="",$type = 'list')
		{
		            Configure::write('debug', 0);
		            $this->layout = 'ajax';
		            $this->set('type', $type);
		            
		           //根据Id，获取当前方法的详细信息
		            $method_info=$this->ApiMethod->find('first',array('conditions'=>array('ApiMethod.id'=>$main_id)));
		            $this->set('method_info',$method_info);
		            $project_info=$this->ApiProject->find('first',array('conditions'=>array('ApiProject.code'=>$method_info['ApiMethod']['api_project_code'])));
		            $this->set('project_info',$project_info);
		            //请求示例管理——列表显示
		            if ($type == 'list') {
	            	      if (!empty($method_info)) {
		                        $method_request_example_condition='';
		                        $method_request_example_condition['AND']['ApiMethodRequestExample.api_project_code']  = $method_info['ApiMethod']['api_project_code'];
		                        $method_request_example_condition['AND']['ApiMethodRequestExample.api_category_code']  = $method_info['ApiMethod']['api_category_code'];
		                        $method_request_example_condition['AND']['ApiMethodRequestExample.api_method_code']  = $method_info['ApiMethod']['code'];
	            		     $method_request_example_infos=$this->ApiMethodRequestExample->find('all',array('conditions'=>$method_request_example_condition ,'order'=>'ApiMethodRequestExample.created DESC' ));
			                  $this->set('method_request_example_infos',$method_request_example_infos);
  		            	}
  		            	
	  		       //请求示例管理——编辑操作
		            }elseif ($type == 'edit') {
		              	$edit_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
                			$method_request_example_Info = $this->ApiMethodRequestExample->find('first', array('conditions' => array('ApiMethodRequestExample.id' => $edit_id)));
                			$this->set('method_request_example_Info', $method_request_example_Info);		      
                		//请求示例管理——保存数据      
		            }elseif ($type == 'save') {
		            	$result['code'] = 0;
			               if (!empty($this->data)) {
			                    if ($this->ApiMethodRequestExample->save($this->data)) 
			                    {
			                        $result['code'] = 1;
			                    }
			             }
               			die(json_encode($result));
               		//请求示例管理——删除操作		 
		            }elseif ($type == 'del') {
		            	$result['code'] = 0;
			             $del_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
		                	if ($this->ApiMethodRequestExample->delete(array('id' => $del_id)))
		                	 {
		                   	 $result['code'] = 1;
		               	 }
		                	die(json_encode($result));
		            }	

		}
			
	 //--响应参数管理		
		public function api_method_response($main_id="",$type = 'list')
		{
		            Configure::write('debug', 0);
		            $this->layout = 'ajax';
		            $this->set('type', $type);
		            
		             //根据Id，获取当前方法的详细信息
		            $method_info=$this->ApiMethod->find('first',array('conditions'=>array('ApiMethod.id'=>$main_id)));
		            $this->set('method_info',$method_info);
		            $project_info=$this->ApiProject->find('first',array('conditions'=>array('ApiProject.code'=>$method_info['ApiMethod']['api_project_code'])));
		            $this->set('project_info',$project_info);
		            $object_conditions='';
		            $object_conditions['AND']['ApiObject.api_project_code']=$method_info['ApiMethod']['api_project_code'];
		            $object_info=$this->ApiObject->find('all',array('conditions'=>$object_conditions));
		            $this->set('object_info',$object_info);
		            
		            //响应参数管理——列表显示
		            if ($type == 'list') {
		            	      if (!empty($method_info)) {
				                    $method_response_condition='';
				                    $method_response_condition['AND']['ApiMethodResponse.api_project_code']  = $method_info['ApiMethod']['api_project_code'];
				                    $method_response_condition['AND']['ApiMethodResponse.api_category_code']  = $method_info['ApiMethod']['api_category_code'];
				                    $method_response_condition['AND']['ApiMethodResponse.api_method_code']  = $method_info['ApiMethod']['code'];
		            			 $method_response_infos=$this->ApiMethodResponse->find('all',array('conditions'=>$method_response_condition,'order'=>'ApiMethodResponse.customer_case_id ASC'));
				                    $this->set('method_response_infos',$method_response_infos);
	  		            	}
	  		            	
	  		      //响应参数管理——编辑操作
		            }elseif ($type == 'edit') {
		              	$edit_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
                			$method_response_Info = $this->ApiMethodResponse->find('first', array('conditions' => array('ApiMethodResponse.id' => $edit_id)));
                			$this->set('method_response_Info', $method_response_Info);	
                				      
                		//响应参数管理——保存数据	      
		            }elseif ($type == 'save') {
		            	 $result['code'] = 0;
			               if (!empty($this->data)) {
			                    if ($this->ApiMethodResponse->save($this->data))
			                     {
			                        $result['code'] = 1;
			                      }
			             }
               			 die(json_encode($result));
               		//响应参数管理——删除操作	 
		            }elseif ($type == 'del') {
		            	$result['code'] = 0;
			             $del_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
			             if ($this->ApiMethodResponse->delete(array('id' => $del_id))) 
			             {
			                    $result['code'] = 1;
			              }
			              die(json_encode($result));
		            }	
		}

	 //----响应示例管理		
		public function api_method_response_example($main_id="",$type = 'list')
		{
		            Configure::write('debug', 0);
		            $this->layout = 'ajax';
		            $this->set('type', $type);
		            
		            //根据Id，获取当前方法的详细信息
		            $method_info=$this->ApiMethod->find('first',array('conditions'=>array('ApiMethod.id'=>$main_id)));
		            $this->set('method_info',$method_info);
		            $project_info=$this->ApiProject->find('first',array('conditions'=>array('ApiProject.code'=>$method_info['ApiMethod']['api_project_code'])));
		            $this->set('project_info',$project_info);
		            //响应示例管理——列表显示
		            if ($type == 'list') {
		            	 if (!empty($method_info))
		            	 {
				                  $method_response_example_condition='';
			                        $method_response_example_condition['AND']['ApiMethodResponseExample.api_project_code']  = $method_info['ApiMethod']['api_project_code'];
			                        $method_response_example_condition['AND']['ApiMethodResponseExample.api_category_code']  = $method_info['ApiMethod']['api_category_code'];
			                        $method_response_example_condition['AND']['ApiMethodResponseExample.api_method_code']  = $method_info['ApiMethod']['code'];
		            		     $method_response_example_infos=$this->ApiMethodResponseExample->find('all',array('conditions'=>$method_response_example_condition,'order'=>'ApiMethodResponseExample.created DESC'));
				                  $this->set('method_response_example_infos',$method_response_example_infos);
	  		           	}
	  		           	
	  		      //响应示例管理——编辑操作
		            }elseif ($type == 'edit') {
		             	$edit_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
                			$method_response_example_Info = $this->ApiMethodResponseExample->find('first', array('conditions' => array('ApiMethodResponseExample.id' => $edit_id)));
                			$this->set('method_response_example_Info', $method_response_example_Info);
                					      
                		//响应示例管理——保存数据   
		            }elseif ($type == 'save') {
		            	 $result['code'] = 0;
			               if (!empty($this->data)) {
			                    if ($this->ApiMethodResponseExample->save($this->data))
			                     {
			                        $result['code'] = 1;
			                     }
			             }
               			 die(json_encode($result));
               			 
               		//响应示例管理——删除操作		 
		            }elseif ($type == 'del') {
		            	$result['code'] = 0;
			             $del_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
			             if ($this->ApiMethodResponseExample->delete(array('id' => $del_id))) 
			             {
			                    $result['code'] = 1;
			              }
			             die(json_encode($result));
		            }	
		}		
		
	 //--异常示例管理				
		public function api_method_response_exception($main_id="",$type = 'list')
		{
		            Configure::write('debug', 0);
		            $this->layout = 'ajax';
		            $this->set('type', $type);
		            
		            //根据Id，获取当前方法的详细信息
		            $method_info=$this->ApiMethod->find('first',array('conditions'=>array('ApiMethod.id'=>$main_id)));
		            $this->set('method_info',$method_info);
		            $project_info=$this->ApiProject->find('first',array('conditions'=>array('ApiProject.code'=>$method_info['ApiMethod']['api_project_code'])));
		            $this->set('project_info',$project_info);
		            //异常示例管理——列表显示
		            if ($type == 'list') {
	            	      if (!empty($method_info)) {
			                    $method_response_exception_condition='';
			                    $method_response_exception_condition['AND']['ApiMethodResponseException.api_project_code']  = $method_info['ApiMethod']['api_project_code'];
			                    $method_response_exception_condition['AND']['ApiMethodResponseException.api_category_code']  = $method_info['ApiMethod']['api_category_code'];
			                    $method_response_exception_condition['AND']['ApiMethodResponseException.api_method_code']  = $method_info['ApiMethod']['code'];
	            			 $method_response_exception_infos=$this->ApiMethodResponseException->find('all',array('conditions'=>$method_response_exception_condition,'order'=>'ApiMethodResponseException.created DESC'));
			                    $this->set('method_response_exception_infos',$method_response_exception_infos);
  		            	}
  		            	
	  		      //异常示例管理——编辑操作
		            }elseif ($type == 'edit') {
		              	$edit_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
                			$method_response_exception_Info = $this->ApiMethodResponseException->find('first', array('conditions' => array('ApiMethodResponseException.id' => $edit_id)));
                			$this->set('method_response_exception_Info', $method_response_exception_Info);		   
                			   
                		//异常示例管理——保存数据	      
		            }elseif ($type == 'save') {
		            	 $result['code'] = 0;
			               if (!empty($this->data)) {
			                    if ($this->ApiMethodResponseException->save($this->data))
			                     {
			                        $result['code'] = 1;
			                    }
			             }
               			 die(json_encode($result));
               			 
               		//异常示例管理——删除操作		 
		            }elseif ($type == 'del') {
		            
		            	$result['code'] = 0;
			             $del_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
			             if ($this->ApiMethodResponseException->delete(array('id' => $del_id))) 
			             {
			                    $result['code'] = 1;
			              }
			             die(json_encode($result));
		            }	
		}		
		
//-错误代码管理
		public function api_method_error_code($main_id="",$type = 'list')
		{
		            Configure::write('debug', 0);
		            $this->layout = 'ajax';
		            $this->set('type', $type);
		            
		             //根据Id，获取当前方法的详细信息
		            $method_info=$this->ApiMethod->find('first',array('conditions'=>array('ApiMethod.id'=>$main_id)));
		            $this->set('method_info',$method_info);
		          
		            $project_info=$this->ApiProject->find('first',array('conditions'=>array('ApiProject.code'=>$method_info['ApiMethod']['api_project_code'])));
		            $this->set('project_info',$project_info);
		            //错误代码管理——列表显示
		            if ($type == 'list') {
	            	      if (!empty($method_info)) {
			                    $error_code_interpretation_condition='';
			                    $error_code_interpretation_condition['AND']['ApiErrorCodeInterpretation.api_project_code']=$method_info['ApiMethod']['api_project_code'];
			                    $error_code_interpretation_infos=$this->ApiErrorCodeInterpretation->find('all',array('conditions'=>$error_code_interpretation_condition ));
			                    $this->set('error_code_interpretation_infos',$error_code_interpretation_infos);
			                    $method_error_code_condition='';
			                    $method_error_code_condition['AND']['ApiMethodErrorCode.api_project_code']  = $method_info['ApiMethod']['api_project_code'];
			                    $method_error_code_condition['AND']['ApiMethodErrorCode.api_method_code']  = $method_info['ApiMethod']['code'];
	            			 $method_error_code_infos=$this->ApiMethodErrorCode->find('all',array('conditions'=>$method_error_code_condition,'order'=>'ApiMethodErrorCode.created DESC'));
			                    $this->set('method_error_code_infos',$method_error_code_infos);
  		            	}
  		            	
  		            //错误代码管理——编辑操作
		            }elseif ($type == 'edit') {
				 	$method_error_code_interpretation_condition='';
				    	$method_error_code_interpretation_condition['AND']['ApiErrorCodeInterpretation.api_project_code']=$method_info['ApiMethod']['api_project_code'];
				      $error_code_interpretation_infos=$this->ApiErrorCodeInterpretation->find('all',array('conditions'=>$method_error_code_interpretation_condition));
				      $this->set('error_code_interpretation_infos',$error_code_interpretation_infos);
		              	$edit_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
                			$method_error_code_info = $this->ApiMethodErrorCode->find('first', array('conditions' => array('ApiMethodErrorCode.id' => $edit_id)));
                			$this->set('method_error_code_info', $method_error_code_info);	
                				      
                		//错误代码管理——保存数据      
		            }elseif ($type == 'save') {
					if (!empty($this->data)) 
					{
						 $method_error_code_condition='';
			                    $method_error_code_condition['AND']['ApiMethodErrorCode.api_project_code']  = $this->data['ApiMethodErrorCode']['api_project_code'];
			                    $method_error_code_condition['AND']['ApiMethodErrorCode.api_method_code']  = $this->data['ApiMethodErrorCode']['api_method_code'];
			                    $method_error_code_condition['AND']['ApiMethodErrorCode.error_code']  = $this->data['ApiMethodErrorCode']['error_code'];
			                    $method_error_code_condition['AND']['ApiMethodErrorCode.id <>']  = $this->data['ApiMethod']['id'];
		     			$method_error_code_Info_num = $this->ApiMethodErrorCode->find('count',array('conditions'=>$method_error_code_condition));
		            		$result['code'] = 0;
		           			if($method_error_code_Info_num=='0')
		           			{
				                    if ($this->ApiMethodErrorCode->save($this->data))
				                     {
				                        $result['code'] = 1;
				                      }
			             	}
			             	die(json_encode($result));
               			}
               			
               	       //错误代码管理——删除操作	 
		         	}elseif ($type == 'del') {
		            	$result['code'] = 0;
			             $del_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
			             if ($this->ApiMethodErrorCode->delete(array('id' => $del_id))) 
			             {
			                    $result['code'] = 1;
			              }
			              die(json_encode($result));
		            }	
 
		}	
			
//---FAQ管理
		public function api_method_faq($main_id,$type = 'list')
		{
		            Configure::write('debug', 0);
		            $this->layout = 'ajax';
		            $this->set('type', $type);
		            
		            //根据Id，获取当前方法的详细信息
		            $method_info=$this->ApiMethod->find('first',array('conditions'=>array('ApiMethod.id'=>$main_id)));
		            $this->set('method_info',$method_info);
		            $project_info=$this->ApiProject->find('first',array('conditions'=>array('ApiProject.code'=>$method_info['ApiMethod']['api_project_code'])));
		            $this->set('project_info',$project_info);
		            //FAQ管理——列表显示
		            if ($type == 'list') {
		            	  if (!empty($method_info)) 
		            	  {
				                 $method_faq_condition='';
			                       $method_faq_condition['AND']['ApiMethodFaq.api_project_code']  = $method_info['ApiMethod']['api_project_code'];
			                       $method_faq_condition['AND']['ApiMethodFaq.api_category_code']  = $method_info['ApiMethod']['api_category_code'];
			                       $method_faq_condition['AND']['ApiMethodFaq.api_method_code']  = $method_info['ApiMethod']['code'];
		            		    $method_faq_infos=$this->ApiMethodFaq->find('all',array('conditions'=>$method_faq_condition,'order'=>'ApiMethodFaq.created DESC'));
				                 $this->set('method_faq_infos',$method_faq_infos);
	  		           	}
	  		           	
	  		      //FAQ管理——编辑操作
		            }elseif ($type == 'edit') {
		              	$edit_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
                			$method_faq_Info = $this->ApiMethodFaq->find('first', array('conditions' => array('ApiMethodFaq.id' => $edit_id)));
                			$this->set('method_faq_Info', $method_faq_Info);	
                				      
                		//FAQ管理——保存数据     
		            }elseif ($type == 'save') {
			            if (!empty($this->data)) 
			            {
				            $method_faq_condition='';
		                         $method_faq_condition['AND']['ApiMethodFaq.api_project_code']  = $this->data['ApiMethodFaq']['api_project_code'];
		                         $method_faq_condition['AND']['ApiMethodFaq.api_category_code']  = $this->data['ApiMethodFaq']['api_category_code'];
		                         $method_faq_condition['AND']['ApiMethodFaq.api_method_code']  = $this->data['ApiMethodFaq']['api_method_code'];
		                         $method_faq_condition['AND']['ApiMethodFaq.question']  = $this->data['ApiMethodFaq']['question'];
		                         $method_faq_condition['AND']['ApiMethodFaq.id <>']  = $this->data['ApiMethodFaq']['id'];
				            $methods_faq_Info_num = $this->ApiMethodFaq->find('count',array('conditions'=>$method_faq_condition ));
			            	$result['code'] = 0;
						if($methods_faq_Info_num=='0')
						{
				                if ($this->ApiMethodFaq->save($this->data)) 
				                {
				                        $result['code'] = 1;
				                  }
				            }
				                  die(json_encode($result));
			             }
               		//FAQ管理——删除操作	 
		            }elseif ($type == 'del') {
		            	$result['code'] = 0;
			             $del_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
			             if ($this->ApiMethodFaq->delete(array('id' => $del_id))) 
			             {
			                    $result['code'] = 1;
			                }
			                die(json_encode($result));
		            }	
		}		
	
	 //-方法管理
		//方法管理——列表显示
		public function index( )
		{				       
				Configure::write('debug', 0);
				//项目列表
				$project_list=$this->ApiProject->find('list',array('fields'=>array('ApiProject.name','ApiProject.code')));
				$this->set('project_list',$project_list);
				
				//分类列表
				$category_list=$this->ApiCategory->find('list',array('fields'=>array('ApiCategory.name','ApiCategory.code'),'order'=>'ApiCategory.created DESC'));
				$this->set('category_list',$category_list);
			
			        /*判断权限*/
			        $this->operator_privilege('api_method_view');
			        $this->operation_return_url(true);//设置操作返回页面地址
			        $this->menu_path = array('root' => '/edi/','sub' => '/api_methods/');
			     $this->set('title_for_layout', $this->ld['api_method_manage'].' - '.$this->configs['shop_name']);

			        //当前位置
			        $this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
			        $this->navigations[] = array('name' => $this->ld['api_method_manage'],'url' =>'/api_methods/');
			        //pr($this->backend_locale);
			        $condition = '';
			        $this->ApiMethod->set_locale($this->backend_locale);
			        //项目筛选
			         if (isset($this->params['url']['project_code']) && $this->params['url']['project_code'] != '') {
			             $condition['AND']['ApiMethod.api_project_code']=$this->params['url']['project_code'];
			            $this->set('project_code', $this->params['url']['project_code']);
			        }
			        //分类筛选
			         if (isset($this->params['url']['category_code']) && $this->params['url']['category_code'] !='') {
			         	 $condition['AND']['ApiMethod.api_category_code']= $this->params['url']['category_code'];
			         	 $this->set('category_code', $this->params['url']['category_code']);
			         }
			         //类型筛选
			          if (isset($this->params['url']['type']) && $this->params['url']['type'] != '') {
			             $condition['AND']['ApiMethod.type']=$this->params['url']['type'];
			            $this->set('type', $this->params['url']['type']);
			        }
			        //方法（名称/代码）筛选
			        if (isset($this->params['url']['keyword']) && $this->params['url']['keyword'] != '') {
			          $condition['OR'][]="ApiMethod.code like '%".$this->params['url']['keyword']."%' ";
			          $condition['OR'][]="ApiMethod.name like '%".$this->params['url']['keyword']."%' ";
			            $this->set('keyword', $this->params['url']['keyword']);
			        }
			          $method_infos = $this->ApiMethod->find('all', array('conditions' => $condition,'order' => 'ApiMethod.orderby ASC'));
			           $this->set('method_infos', $method_infos);
			     
		}
		
		//项目与分类——ajax关联
		public function ajax_get_category_code($val='0')
		{
			 Configure::write('debug', 0);
		    	 $this->layout = 'ajax';
		    	 //project.code赋值给分类的api_project_code
			 $sub_group_code['ApiCategory.api_project_code'] =$val;
			//根据分类的api_project_code查询出分类的代码和名称
		       $sub_group_Info= $this->ApiCategory->find('all', array('fields' => array('ApiCategory.name','ApiCategory.code'), 'conditions' => $sub_group_code));
			die(json_encode($sub_group_Info));	 
		}
		
		//方法管理——添加操作
		public function add($id=0)
		{
			Configure::write('debug', 0);
			$this->operator_privilege('api_method_view');
			
			//设置操作返回页面地址
			$this->operation_return_url(true);
			$this->menu_path = array('root' => '/edi/','sub' => '/api_methods/');
			
			//当前位置
			$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
			$this->navigations[] = array('name' => $this->ld['api_method_manage'],'url' =>'/api_methods/');				
			$this->navigations[] = array('name' => $this->ld['add'],'url' =>'');
			$this->set('title_for_layout', $this->ld['add'].' - '.$this->ld['api_method_manage'].' - '.$this->configs['shop_name']);

			//项目列表
			$project_list=$this->ApiProject->find('list',array('fields'=>array('ApiProject.name','ApiProject.code')));
			$this->set('project_list',$project_list);
			
			//分类列表
			$category_list=$this->ApiCategory->find('list',array('fields'=>array('ApiCategory.name','ApiCategory.api_project_code')));
			$this->set('category_list',$category_list);
			
			//根据Id，获取当前方法的详细信息
			$this->ApiMethod->id=$id;
			$method_info=$this->ApiMethod->read();
			$this->set('method_info',$method_info);
			
			if(!empty($this->data))
			{
				Configure::write('debug', 0);
				$this->layout = 'ajax';
				//查询具有相同方法代码的数据NUM
				$method_code_condition='';
				$method_code_condition['AND']['ApiMethod.api_project_code']=$this->data['ApiMethod']['api_project_code'];
				$method_code_condition['AND']['ApiMethod.api_category_code']=$this->data['ApiMethod']['api_category_code'];
				$method_code_condition['AND']['ApiMethod.code']=$this->data['ApiMethod']['code'];
				$method_code_num= $this->ApiMethod->find('count',array('conditions'=>$method_code_condition));
				//查询具有相同方法名称的数据NUM
				$method_name_condition='';
				$method_name_condition['AND']['ApiMethod.api_project_code']=$this->data['ApiMethod']['api_project_code'];
				$method_name_condition['AND']['ApiMethod.api_category_code']=$this->data['ApiMethod']['api_category_code'];
				$method_name_condition['AND']['ApiMethod.name']=$this->data['ApiMethod']['name'];
		  		$method_name_num= $this->ApiMethod->find('count',array('conditions'=>$method_name_condition));
			
				$result['code']=0;
				//如果都为0，说明没有添加过相同的数据，保存
				if($method_code_num=='0' && $method_name_num=='0')
				{
						if($this->ApiMethod->save($this->data))
						{
								$result['code']=1;
						}
				}
				die(json_encode($result));
			}
		}
		
		//方法管理——编辑操作
		public function view($id=0)
		{
			Configure::write('debug', 0);
			$this->operator_privilege('api_method_view');
			//设置操作返回页面地址
			$this->operation_return_url(true);
			
			//项目列表
			$project_list=$this->ApiProject->find('list',array('fields'=>array('ApiProject.name','ApiProject.code')));
			$this->set('project_list',$project_list);
			
			//根据Id，获取当前方法的详细信息
			$this->ApiMethod->id=$id;
			$method_info=$this->ApiMethod->read();
			$this->set('method_info',$method_info);	
			
			//当前位置
			//当前项目/当前方法
			foreach($project_list as $k=>$v){
				if($v==$method_info['ApiMethod']['api_project_code']){$str=''; $str=$k.'>';}
			}
			$str.=$method_info['ApiMethod']['name'];
			$this->ld['api_project_method']=$str;
			$this->menu_path = array('root' => '/edi/','sub' => '/api_methods/');
			$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
			$this->navigations[] = array('name' => $this->ld['api_method_manage'],'url' =>'/api_methods/');
			$this->navigations[] = array('name' =>$this->ld['api_project_method'],'url' =>'');
			$this->navigations[] = array('name' => $this->ld['edit'],'url' =>'');
			$this->set('title_for_layout', $this->ld['edit'].' - '.$this->ld['api_project_method'].' - '.$this->ld['api_method_manage'].' - '.$this->configs['shop_name']);
			//分类列表
			$category_list=$this->ApiCategory->find('list',array('fields'=>array('ApiCategory.name','ApiCategory.api_project_code')));
			$this->set('category_list',$category_list);
		
			
  		
	  		
	  		//如果后台提交数据不为空
			if(!empty($this->data))
			{
				Configure::write('debug', 1);
				$this->layout = 'ajax';
				//（除本条提交的数据外）查询具有相同方法名称的数据NUM
				$method_name_condition='';
				$method_name_condition['AND']['ApiMethod.api_project_code']=$this->data['ApiMethod']['api_project_code'];
				$method_name_condition['AND']['ApiMethod.api_category_code']=$this->data['ApiMethod']['api_category_code'];
				$method_name_condition['AND']['ApiMethod.name']=$this->data['ApiMethod']['name'];
				$method_name_condition['AND']['ApiMethod.id <>']=$this->data['ApiMethod']['id'];
				$method_name_num= $this->ApiMethod->find('count',array('conditions'=>$method_name_condition));
				
				//（除本条提交的数据外）查询具有相同方法代码的数据NUM
				$method_code_condition='';
				$method_code_condition['AND']['ApiMethod.api_project_code']=$this->data['ApiMethod']['api_project_code'];
				$method_code_condition['AND']['ApiMethod.api_category_code']=$this->data['ApiMethod']['api_category_code'];
				$method_code_condition['AND']['ApiMethod.code']=$this->data['ApiMethod']['code'];
				$method_code_condition['AND']['ApiMethod.id <>']=$this->data['ApiMethod']['id'];
			  	$method_code_num= $this->ApiMethod->find('count',array('conditions'=>$method_code_condition));
			  	
				$result['code'] = 0;
				//如果都为0，说明没有重复数据，保存
				if($method_name_num=='0' && $method_code_num=='0')	
				{       
					if($this->ApiMethod->save($this->data))
					{
							$result['code'] = 1;
					}
				}
				die(json_encode($result));
			}
		}
		
		//方法管理——删除操作
		public function remove($id=0)
		 {	Configure::write('debug', 0);
		      $this->layout = 'ajax';
		    	$result['flag'] = 2;
		      $page_info = $this->ApiMethod->findById($id);
		      if($this->ApiMethod->deleteAll("ApiMethod.id = '".$id."'"))
		      {
		       	$result['flag'] = 1;
    			}
			die(json_encode($result));				     
		  }
		  









public function select_upload(){
	Configure::write('debug', 0);

	 $this->Profile->hasOne = array();
       $project_profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_project_export', 'Profile.status' => 1)));
       $this->set('project_profile_id',$project_profile_id);
       
       $object_profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_object_export', 'Profile.status' => 1)));
       $this->set('object_profile_id',$object_profile_id);
       
       $object_field_profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_object_field_export', 'Profile.status' => 1)));
       $this->set('object_field_profile_id',$object_field_profile_id);
       
       $project_common_parameter_profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_project_common_parameter_export', 'Profile.status' => 1)));
       $this->set('project_common_parameter_profile_id',$project_common_parameter_profile_id);
       
       $error_code_interpretation_profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_error_code_interpretation_export', 'Profile.status' => 1)));
       $this->set('error_code_interpretation_profile_id',$error_code_interpretation_profile_id);
       
       $category_profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_category_export', 'Profile.status' => 1)));
       $this->set('category_profile_id',$category_profile_id);
       
       $method_profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_export', 'Profile.status' => 1)));
       $this->set('method_profile_id',$method_profile_id);
       
       $method_request_profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_request_export', 'Profile.status' => 1)));
       $this->set('method_request_profile_id',$method_request_profile_id);
       
       $method_response_example_profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_response_example_export', 'Profile.status' => 1)));
       $this->set('method_response_example_profile_id',$method_response_example_profile_id);
       
       $method_response_exception_profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_response_exception_export', 'Profile.status' => 1)));
       $this->set('method_response_exception_profile_id',$method_response_exception_profile_id);
       
       $method_response_profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_response_export', 'Profile.status' => 1)));
       $this->set('method_response_profile_id',$method_response_profile_id);
       
       $method_error_code_profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_error_code_export', 'Profile.status' => 1)));
       $this->set('method_error_code_profile_id',$method_error_code_profile_id);
       
       $method_faq_profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_faq_export', 'Profile.status' => 1)));
       $this->set('method_faq_profile_id',$method_faq_profile_id);
       
       $method_request_example_profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_request_example_export', 'Profile.status' => 1)));
       $this->set('method_request_example_profile_id',$method_request_example_profile_id);
       

}







		  
//项目上传
public function project_upload(){
	Configure::write('debug', 0);
        	$this->operation_return_url(true);
			$this->menu_path = array('root' => '/edi/','sub' => '/api_methods/');
			
			//当前位置
			$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
			$this->navigations[] = array('name' => $this->ld['api_method_manage'],'url' =>'/api_methods/');				
			$this->navigations[] = array('name' => '项目'.$this->ld['bulk_upload'],'url' =>'');
			 $this->set('title_for_layout','项目'.$this->ld['bulk_upload'].' - '.$this->ld['api_method_manage'].' - '.$this->configs['shop_name']);
			 $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_project_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//上传项目cvs查看
 public function project_uploadpreview()
    {
    	Configure::write('debug', 0);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/project_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_project_export', 'Profile.status' => 1)));
		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
      	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	$fields[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
  	  }
               
                            $key_arr = array();
                            foreach ($fields_array as $k => $v) {
                                $fields_k = explode('.', $v);
                                $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8//IGNORE', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/project_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
			$upload_num=count($checkbox_arr);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        
                        $ApiProject_first = $this->ApiProject->find('first', array('conditions' => array('ApiProject.code' => $v['code'], 'ApiProject.status' => $v['status'])));
                        $v['id']=isset($ApiProject_first['ApiProject']['id'])?$ApiProject_first['ApiProject']['id']:0;
                        	
                        	$s=$this->ApiProject->save($v);
                        	 if(isset($s)&&!empty($s)){
                        	 	++$success_num;
                        	 }
                     	    $result['code']=1;
                    }
                   
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/api_methods/project_upload/'</script>";
		            die();
                } else {
		            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/api_methods/project_upload/'</script>";
                    	
                }
         
    }

      /////////////////////////////////////////////
      public function fgetcsv_reg($handle, $length = null, $d = ',', $e = '"')
      {
          $d = preg_quote($d);
          $e = preg_quote($e);
          $_line = '';
          $eof = false;
          while ($eof != true) {
              $_line .= (empty($length) ? fgets($handle) : fgets($handle, $length));
              $itemcnt = preg_match_all('/'.$e.'/', $_line, $dummy);
              if ($itemcnt % 2 == 0) {
                  $eof = true;
              }
          }
          $_csv_line = preg_replace('/(?: |[ ])?$/', $d, trim($_line));
          $_csv_pattern = '/('.$e.'[^'.$e.']*(?:'.$e.$e.'[^'.$e.']*)*'.$e.'|[^'.$d.']*)'.$d.'/';
          preg_match_all($_csv_pattern, $_csv_line, $_csv_matches);
          $_csv_data = $_csv_matches[1];
          for ($_csv_i = 0; $_csv_i < count($_csv_data); ++$_csv_i) {
              $_csv_data[$_csv_i] = preg_replace('/^'.$e.'(.*)'.$e.'$/s', '$1', $_csv_data[$_csv_i]);
              $_csv_data[$_csv_i] = str_replace($e.$e, $e, $_csv_data[$_csv_i]);
          }

          return empty($_line) ? false : $_csv_data;
      }



		 
//导出项目csv
public function download_project_csv_example($out_type = 'ApiProject'){
 Configure::write('debug', 0);
     $this->layout="ajax";
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_project_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $ApiProject_info = $this->ApiProject->find('all', array('order' => 'ApiProject.id desc'));
          foreach ($ApiProject_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         //pr($newdatas);die();
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}


//项目对象上传
public function object_upload(){
	Configure::write('debug', 0);
        	$this->operation_return_url(true);
			$this->menu_path = array('root' => '/edi/','sub' => '/api_methods/');
			
			//当前位置
			$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
			$this->navigations[] = array('name' => $this->ld['api_method_manage'],'url' =>'/api_methods/');				
			$this->navigations[] = array('name' => '对象'.$this->ld['bulk_upload'],'url' =>'');
			 $this->set('title_for_layout','对象'.$this->ld['bulk_upload'].' - '.$this->ld['api_method_manage'].' - '.$this->configs['shop_name']);
			 $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_object_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//上传项目对象cvs查看
 public function object_uploadpreview()
    {
    	Configure::write('debug', 0);
    	$success_num=0;

                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/object_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_object_export', 'Profile.status' => 1)));
		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
      	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	$fields[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
  	  }
               
                            $key_arr = array();
                            foreach ($fields_array as $k => $v) {
                                $fields_k = explode('.', $v);
                                $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8//IGNORE', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] =@iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/object_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
                    $upload_num=count($checkbox_arr);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        
                        $ApiObject_first = $this->ApiObject->find('first', array('conditions' => array('ApiObject.code' => $v['code'], 'ApiObject.type' => $v['type'], 'ApiObject.api_project_code' => $v['api_project_code'])));
                        $v['id']=isset($ApiObject_first['ApiObject']['id'])?$ApiObject_first['ApiObject']['id']:0;
                        $s=$this->ApiObject->save($v);
                        if(isset($s)&&!empty($s)){
   					++$success_num;
				}
                        $result['code']=1;
                    }
                     echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/api_methods/object_upload/'</script>";
		            die();
                } else {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/api_methods/object_upload/'</script>";
	
                }
         
    }
//导出项目对象csv
public function download_object_csv_example($out_type = ' ApiObject'){
 Configure::write('debug', 0);
     $this->layout="ajax";
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_object_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $ApiObject_info = $this->ApiObject->find('all', array('order' => 'ApiObject.id desc'));
          foreach ($ApiObject_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}



//对象字段上传
public function object_field_upload(){
	Configure::write('debug', 0);
        	$this->operation_return_url(true);
			$this->menu_path = array('root' => '/edi/','sub' => '/api_methods/');
			
			//当前位置
			$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
			$this->navigations[] = array('name' => $this->ld['api_method_manage'],'url' =>'/api_methods/');				
			$this->navigations[] = array('name' => '项目方法'.$this->ld['bulk_upload'],'url' =>'');
			 $this->set('title_for_layout','项目方法'.$this->ld['bulk_upload'].' - '.$this->ld['api_method_manage'].' - '.$this->configs['shop_name']);
			 $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_object_field_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//上传对象字段cvs查看
 public function object_field_uploadpreview()
    {
    	Configure::write('debug', 0);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/object_field_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_object_field_export', 'Profile.status' => 1)));
		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
      	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	$fields[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
  	  }
               
                            $key_arr = array();
                            foreach ($fields_array as $k => $v) {
                                $fields_k = explode('.', $v);
                                $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8//IGNORE', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] =@iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/object_field_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
                    $upload_num=count($checkbox_arr);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        
                        $ApiObjectField_first = $this->ApiObjectField->find('first', array('conditions' => array('ApiObjectField.api_project_code' => $v['api_project_code'],'ApiObjectField.api_object_code' => $v['api_object_code'], 'ApiObjectField.type' => $v['type'], 'ApiObjectField.name' => $v['name'])));
                        $v['id']=isset($ApiObjectField_first['ApiObjectField']['id'])?$ApiObjectField_first['ApiObjectField']['id']:0;
                        $s=$this->ApiObjectField->save($v);
                        if(isset($s)&&!empty($s)){
                        	 	++$success_num;
                        	 }
                        $result['code']=1;
                    }
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/api_methods/object_field_upload/'</script>";
			die();
                } else {
                   echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/api_methods/object_field_upload/'</script>";
                }
         
    }
//导出对象字段csv
public function download_object_field_csv_example($out_type = 'ApiObjectField'){
 Configure::write('debug', 0);
     $this->layout="ajax";
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_object_field_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $ApiObjectField_info = $this->ApiObjectField->find('all', array('order' => 'ApiObjectField.id desc'));
          foreach ($ApiObjectField_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}




//公共参数上传
public function common_parameter_upload(){
	Configure::write('debug', 0);
        	$this->operation_return_url(true);
			$this->menu_path = array('root' => '/edi/','sub' => '/api_methods/');
			
			//当前位置
			$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
			$this->navigations[] = array('name' => $this->ld['api_method_manage'],'url' =>'/api_methods/');				
			$this->navigations[] = array('name' => '公共参数'.$this->ld['bulk_upload'],'url' =>'');
			 $this->set('title_for_layout','公共参数'.$this->ld['bulk_upload'].' - '.$this->ld['api_method_manage'].' - '.$this->configs['shop_name']);
			 $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_project_common_parameter_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//上传公共参数cvs查看
 public function common_parameter_uploadpreview()
    {
    	Configure::write('debug', 0);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/common_parameter_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_project_common_parameter_export', 'Profile.status' => 1)));
		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
      	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	$fields[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
  	  }
               
                            $key_arr = array();
                            foreach ($fields_array as $k => $v) {
                                $fields_k = explode('.', $v);
                                $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8//IGNORE', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] =@iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/common_parameter_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
                    $upload_num=count($checkbox_arr);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        
                        $ApiProjectCommonParameter_first = $this->ApiProjectCommonParameter->find('first', array('conditions' => array('ApiProjectCommonParameter.api_project_code' => $v['api_project_code'], 'ApiProjectCommonParameter.type' => $v['type'], 'ApiProjectCommonParameter.name' => $v['name'])));
                        $v['id']=isset($ApiProjectCommonParameter_first['ApiProjectCommonParameter']['id'])?$ApiProjectCommonParameter_first['ApiProjectCommonParameter']['id']:0;
                        $s=$this->ApiProjectCommonParameter->save($v);
                         if(isset($s)&&!empty($s)){
                        	 	++$success_num;
                        	 }
                        $result['code']=1;
                    }
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/api_methods/common_parameter_upload/'</script>";
			die();
                } else {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/api_methods/common_parameter_upload/'</script>";
                }
         
    }
//导出公共参数csv
public function download_common_parameter_csv_example($out_type = 'ApiProjectCommonParameter'){
 Configure::write('debug', 0);
     $this->layout="ajax";
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_project_common_parameter_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $ApiProjectCommonParameter_info = $this->ApiProjectCommonParameter->find('all', array('order' => 'ApiProjectCommonParameter.id desc'));
          foreach ($ApiProjectCommonParameter_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}





//错误代码解释上传
public function error_code_interpretation_upload(){
	Configure::write('debug', 0);
        	$this->operation_return_url(true);
			$this->menu_path = array('root' => '/edi/','sub' => '/api_methods/');
			
			//当前位置
			$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
			$this->navigations[] = array('name' => $this->ld['api_method_manage'],'url' =>'/api_methods/');				
			$this->navigations[] = array('name' => '错误代码解释'.$this->ld['bulk_upload'],'url' =>'');
			 $this->set('title_for_layout','错误代码解释'.$this->ld['bulk_upload'].' - '.$this->ld['api_method_manage'].' - '.$this->configs['shop_name']);
			 $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_error_code_interpretation_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//上传错误代码解释cvs查看
 public function error_code_interpretation_uploadpreview()
    {
    	Configure::write('debug', 0);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/error_code_interpretation_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_error_code_interpretation_export', 'Profile.status' => 1)));
		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
      	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	$fields[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
  	  }
               
                            $key_arr = array();
                            foreach ($fields_array as $k => $v) {
                                $fields_k = explode('.', $v);
                                $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8//IGNORE', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] =@iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/error_code_interpretation_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
                    $upload_num=count($checkbox_arr);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        
                        $ApiErrorCodeInterpretation_first = $this->ApiErrorCodeInterpretation->find('first', array('conditions' => array('ApiErrorCodeInterpretation.code' => $v['code'], 'ApiErrorCodeInterpretation.api_project_code' => $v['api_project_code'])));
                        $v['id']=isset($ApiErrorCodeInterpretation_first['ApiErrorCodeInterpretation']['id'])?$ApiErrorCodeInterpretation_first['ApiErrorCodeInterpretation']['id']:0;
                        $s=$this->ApiErrorCodeInterpretation->save($v);
                        if(isset($s)&&!empty($s)){
                        	 	++$success_num;
                        	 }
                        $result['code']=1;
                    }
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/api_methods/error_code_interpretation_upload/'</script>";
			die();
                } else {
                   echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/api_methods/error_code_interpretation_upload/'</script>";

                }
         
    }
//导出错误代码解释csv
public function download_error_code_interpretation_csv_example($out_type = 'ApiErrorCodeInterpretation'){
 Configure::write('debug', 0);
     $this->layout="ajax";
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_error_code_interpretation_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $ApiErrorCodeInterpretation_info = $this->ApiErrorCodeInterpretation->find('all', array('order' => 'ApiErrorCodeInterpretation.id desc'));
          foreach ($ApiErrorCodeInterpretation_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}

































//项目分类上传
public function category_upload(){
	Configure::write('debug', 0);
        	$this->operation_return_url(true);
			$this->menu_path = array('root' => '/edi/','sub' => '/api_methods/');
			
			//当前位置
			$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
			$this->navigations[] = array('name' => $this->ld['api_method_manage'],'url' =>'/api_methods/');				
			$this->navigations[] = array('name' => '项目分类'.$this->ld['bulk_upload'],'url' =>'');
			 $this->set('title_for_layout','项目分类'.$this->ld['bulk_upload'].' - '.$this->ld['api_method_manage'].' - '.$this->configs['shop_name']);
			 $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_category_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//上传项目分类cvs查看
 public function category_uploadpreview()
    {
    	Configure::write('debug', 0);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/category_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_category_export', 'Profile.status' => 1)));
		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
      	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	$fields[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
  	  }
               
                            $key_arr = array();
                            foreach ($fields_array as $k => $v) {
                                $fields_k = explode('.', $v);
                                $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8//IGNORE', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] = @iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/category_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
                    $upload_num=count($checkbox_arr);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        
                        $ApiCategory_first = $this->ApiCategory->find('first', array('conditions' => array('ApiCategory.code' => $v['code'],'ApiCategory.api_project_code' => $v['api_project_code'])));
                        $v['id']=isset($ApiCategory_first['ApiCategory']['id'])?$ApiCategory_first['ApiCategory']['id']:0;
                        $s=$this->ApiCategory->save($v);
                        if(isset($s)&&!empty($s)){
                        	 	++$success_num;
                        	 }
                        $result['code']=1;
                    }
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/api_methods/category_upload/'</script>";
			die();
                } else {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/api_methods/category_upload/'</script>";

                }
         
    }
//导出项目分类csv
public function download_category_csv_example($out_type = 'ApiCategory'){
 Configure::write('debug', 0);
     $this->layout="ajax";
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_category_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $ApiCategory_info = $this->ApiCategory->find('all', array('order' => 'ApiCategory.id desc'));
          foreach ($ApiCategory_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}

//项目方法上传
public function method_upload(){
	Configure::write('debug', 0);
        	$this->operation_return_url(true);
			$this->menu_path = array('root' => '/edi/','sub' => '/api_methods/');
			
			//当前位置
			$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
			$this->navigations[] = array('name' => $this->ld['api_method_manage'],'url' =>'/api_methods/');				
			$this->navigations[] = array('name' => '项目方法'.$this->ld['bulk_upload'],'url' =>'');
			 $this->set('title_for_layout','项目方法'.$this->ld['bulk_upload'].' - '.$this->ld['api_method_manage'].' - '.$this->configs['shop_name']);
			 $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//上传项目方法cvs查看
 public function method_uploadpreview()
    {
    	Configure::write('debug', 0);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/method_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_export', 'Profile.status' => 1)));
		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
      	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	$fields[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
  	  }
               
                            $key_arr = array();
                            foreach ($fields_array as $k => $v) {
                                $fields_k = explode('.', $v);
                                $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8//IGNORE', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] =@iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/method_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
                    $upload_num=count($checkbox_arr);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        
                        $ApiMethod_first = $this->ApiMethod->find('first', array('conditions' => array('ApiMethod.code' => $v['code'], 'ApiMethod.type' => $v['type'],'ApiMethod.api_category_code' => $v['api_category_code'], 'ApiMethod.api_project_code' => $v['api_project_code'])));
                        $v['id']=isset($ApiMethod_first['ApiMethod']['id'])?$ApiMethod_first['ApiMethod']['id']:0;
                        $s=$this->ApiMethod->save($v);
                        if(isset($s)&&!empty($s)){
                        	 	++$success_num;
                        	 }
                        $result['code']=1;
                    }
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/api_methods/method_upload/'</script>";
			die();
                } else {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/api_methods/method_upload/'</script>";

                }
         
    }
//导出项目方法csv
public function download_method_csv_example($out_type = 'ApiMethod'){
 Configure::write('debug', 0);
     $this->layout="ajax";
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $ApiMethod_info = $this->ApiMethod->find('all', array('order' => 'ApiMethod.id desc'));
          foreach ($ApiMethod_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}

//项目方法——请求参数上传
public function method_request_upload(){
	Configure::write('debug', 0);
        	$this->operation_return_url(true);
			$this->menu_path = array('root' => '/edi/','sub' => '/api_methods/');
			
			//当前位置
			$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
			$this->navigations[] = array('name' => $this->ld['api_method_manage'],'url' =>'/api_methods/');				
			$this->navigations[] = array('name' => '请求参数'.$this->ld['bulk_upload'],'url' =>'');
			 $this->set('title_for_layout','请求参数'.$this->ld['bulk_upload'].' - '.$this->ld['api_method_manage'].' - '.$this->configs['shop_name']);
			 $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_request_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//上传项目方法——请求参数cvs查看
 public function method_request_uploadpreview()
    {
    	Configure::write('debug', 0);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/method_request_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_request_export', 'Profile.status' => 1)));
		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
      	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	$fields[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
  	  }
               
                            $key_arr = array();
                            foreach ($fields_array as $k => $v) {
                                $fields_k = explode('.', $v);
                                $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8//IGNORE', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] =@iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/method_request_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
                    $upload_num=count($checkbox_arr);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        
                        $ApiMethodRequest_first = $this-> ApiMethodRequest->find('first', array('conditions' => array('ApiMethodRequest.api_project_code' => $v['api_project_code'],'ApiMethodRequest.api_category_code' => $v['api_category_code'],'ApiMethodRequest.api_method_code' => $v['api_method_code'], 'ApiMethodRequest.type' => $v['type'],'ApiMethodRequest.name' => $v['name'])));
                        $v['id']=isset($ApiMethodRequest_first['ApiMethodRequest']['id'])?$ApiMethodRequest_first['ApiMethodRequest']['id']:0;
                        $s=$this->ApiMethodRequest->save($v);
                        if(isset($s)&&!empty($s)){
                        	 	++$success_num;
                        	 }
                        $result['code']=1;
                    }
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/api_methods/method_request_upload/'</script>";
			die();
                } else {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/api_methods/method_request_upload/'</script>";
	
                }
         
    }
//导出项目方法——请求参数csv
public function download_method_request_csv_example($out_type = ' ApiMethodRequest'){
 Configure::write('debug', 0);
     $this->layout="ajax";
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_request_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $ApiMethodRequest_info = $this-> ApiMethodRequest->find('all', array('order' => ' ApiMethodRequest.id desc'));
          foreach ($ApiMethodRequest_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}




//项目方法——请求示例上传
public function method_request_example_upload(){
	Configure::write('debug', 0);
        	$this->operation_return_url(true);
			$this->menu_path = array('root' => '/edi/','sub' => '/api_methods/');
			
			//当前位置
			$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
			$this->navigations[] = array('name' => $this->ld['api_method_manage'],'url' =>'/api_methods/');				
			$this->navigations[] = array('name' => '请求示例'.$this->ld['bulk_upload'],'url' =>'');
			 $this->set('title_for_layout','请求示例'.$this->ld['bulk_upload'].' - '.$this->ld['api_method_manage'].' - '.$this->configs['shop_name']);
			 $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_request_example_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//上传项目方法——请求示例cvs查看
 public function method_request_example_uploadpreview()
    {
    	Configure::write('debug', 0);
    		$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/method_request_example_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_request_example_export', 'Profile.status' => 1)));
		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
      	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	$fields[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
  	  }
               
                            $key_arr = array();
                            foreach ($fields_array as $k => $v) {
                                $fields_k = explode('.', $v);
                                $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8//IGNORE', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] =@iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/method_request_example_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
                    $upload_num=count($checkbox_arr);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        
                        $ApiMethodRequestExample_first = $this-> ApiMethodRequestExample->find('first', array('conditions' => array('ApiMethodRequestExample.api_project_code' => $v['api_project_code'],'ApiMethodRequestExample.api_category_code' => $v['api_category_code'],'ApiMethodRequestExample.api_method_code' => $v['api_method_code'], 'ApiMethodRequestExample.type' => $v['type'])));
                        $v['id']=isset($ApiMethodRequestExample_first['ApiMethodRequestExample']['id'])?$ApiMethodRequestExample_first['ApiMethodRequestExample']['id']:0;
                        $s=$this->ApiMethodRequestExample->save($v);
                         if(isset($s)&&!empty($s)){
                        	 	++$success_num;
                        	 }
                        $result['code']=1;
                    }
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/api_methods/method_request_example_upload/'</script>";
			die();
                } else {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/api_methods/method_request_example_upload/'</script>";

                }
         
    }
//导出项目方法——请求示例csv
public function download_method_request_example_csv_example($out_type = ' ApiMethodRequestExample'){
 Configure::write('debug', 0);
     $this->layout="ajax";
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_request_example_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $ApiMethodRequestExample_info = $this-> ApiMethodRequestExample->find('all', array('order' => ' ApiMethodRequestExample.id desc'));
          foreach ($ApiMethodRequestExample_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}


//项目方法——响应参数上传
public function method_response_upload(){
	Configure::write('debug', 0);
        	$this->operation_return_url(true);
			$this->menu_path = array('root' => '/edi/','sub' => '/api_methods/');
			
			//当前位置
			$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
			$this->navigations[] = array('name' => $this->ld['api_method_manage'],'url' =>'/api_methods/');				
			$this->navigations[] = array('name' => '响应参数'.$this->ld['bulk_upload'],'url' =>'');
			 $this->set('title_for_layout','响应参数'.$this->ld['bulk_upload'].' - '.$this->ld['api_method_manage'].' - '.$this->configs['shop_name']);
			 $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_response_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//上传项目方法——响应参数cvs查看
 public function method_response_uploadpreview()
    {
    	Configure::write('debug', 0);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/method_response_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_response_export', 'Profile.status' => 1)));
		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
      	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	$fields[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
  	  }
               
                            $key_arr = array();
                            foreach ($fields_array as $k => $v) {
                                $fields_k = explode('.', $v);
                                $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8//IGNORE', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] =@iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/method_response_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
                    $upload_num=count($checkbox_arr);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        
                        $ApiMethodResponse_first = $this->ApiMethodResponse->find('first', array('conditions' => array('ApiMethodResponse.api_project_code' => $v['api_project_code'],'ApiMethodResponse.api_category_code' => $v['api_category_code'],'ApiMethodResponse.api_method_code' => $v['api_method_code'], 'ApiMethodResponse.type' => $v['type'], 'ApiMethodResponse.name' => $v['name'])));
                        $v['id']=isset($ApiMethodResponse_first['ApiMethodResponse']['id'])?$ApiMethodResponse_first['ApiMethodResponse']['id']:0;
                        $s=$this->ApiMethodResponse->save($v);
                        if(isset($s)&&!empty($s)){
                        	 	++$success_num;
                        	 }
                        $result['code']=1;
                    }
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/api_methods/method_response_upload/'</script>";
			die();
                } else {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/api_methods/method_response_upload/'</script>";
	
                }
         
    }
//导出项目方法——响应参数csv
public function download_method_response_csv_example($out_type = ' ApiMethodResponse'){
 Configure::write('debug', 0);
     $this->layout="ajax";
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_response_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $ApiMethodResponse_info = $this-> ApiMethodResponse->find('all', array('order' => ' ApiMethodResponse.id desc'));
          foreach ($ApiMethodResponse_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}


//项目方法——响应示例上传
public function method_response_example_upload(){
	Configure::write('debug', 0);
        	$this->operation_return_url(true);
			$this->menu_path = array('root' => '/edi/','sub' => '/api_methods/');
			
			//当前位置
			$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
			$this->navigations[] = array('name' => $this->ld['api_method_manage'],'url' =>'/api_methods/');				
			$this->navigations[] = array('name' => '响应示例'.$this->ld['bulk_upload'],'url' =>'');
			 $this->set('title_for_layout','响应示例'.$this->ld['bulk_upload'].' - '.$this->ld['api_method_manage'].' - '.$this->configs['shop_name']);
			 $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_response_example_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//上传项目方法——响应示例cvs查看
 public function method_response_example_uploadpreview()
    {
    	Configure::write('debug', 0);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/method_response_example_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_response_example_export', 'Profile.status' => 1)));
		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
      	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	$fields[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
  	  }
               
                            $key_arr = array();
                            foreach ($fields_array as $k => $v) {
                                $fields_k = explode('.', $v);
                                $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8//IGNORE', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] =@iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/method_response_example_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
                    $upload_num=count($checkbox_arr);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        
                        $ApiMethodResponseExample_first = $this->ApiMethodResponseExample->find('first', array('conditions' => array('ApiMethodResponseExample.api_project_code' => $v['api_project_code'],'ApiMethodResponseExample.api_category_code' => $v['api_category_code'],'ApiMethodResponseExample.api_method_code' => $v['api_method_code'], 'ApiMethodResponseExample.type' => $v['type'])));
                        $v['id']=isset($ApiMethodResponseExample_first['ApiMethodResponseExample']['id'])?$ApiMethodResponseExample_first['ApiMethodResponseExample']['id']:0;
                        $s=$this->ApiMethodResponseExample->save($v);
                        if(isset($s)&&!empty($s)){
                        	 	++$success_num;
                        	 }
                        $result['code']=1;
                    }
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/api_methods/method_response_example_upload/'</script>";
			die();
                } else {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/api_methods/method_response_example_upload/'</script>";

                }
         
    }
//导出项目方法——响应示例csv
public function download_method_response_example_csv_example($out_type = ' ApiMethodResponseExample'){
 Configure::write('debug', 0);
     $this->layout="ajax";
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_response_example_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $ApiMethodResponseExample_info = $this-> ApiMethodResponseExample->find('all', array('order' => ' ApiMethodResponseExample.id desc'));
          foreach ($ApiMethodResponseExample_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}


//项目方法——响应异常上传
public function method_response_exception_upload(){
	Configure::write('debug', 0);
        	$this->operation_return_url(true);
			$this->menu_path = array('root' => '/edi/','sub' => '/api_methods/');
			
			//当前位置
			$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
			$this->navigations[] = array('name' => $this->ld['api_method_manage'],'url' =>'/api_methods/');				
			$this->navigations[] = array('name' => '响应异常'.$this->ld['bulk_upload'],'url' =>'');
			 $this->set('title_for_layout','响应异常'.$this->ld['bulk_upload'].' - '.$this->ld['api_method_manage'].' - '.$this->configs['shop_name']);
			  $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_response_exception_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//上传项目方法——响应异常cvs查看
 public function method_response_exception_uploadpreview()
    {
    	Configure::write('debug', 0);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/method_response_exception_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_response_exception_export', 'Profile.status' => 1)));
		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
      	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	$fields[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
  	  }
               
                            $key_arr = array();
                            foreach ($fields_array as $k => $v) {
                                $fields_k = explode('.', $v);
                                $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8//IGNORE', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] =@iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/method_response_exception_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
                    $upload_num=count($checkbox_arr);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        
                        $ApiMethodResponseException_first = $this->ApiMethodResponseException->find('first', array('conditions' => array('ApiMethodResponseException.api_project_code' => $v['api_project_code'],'ApiMethodResponseException.api_category_code' => $v['api_category_code'],'ApiMethodResponseException.api_method_code' => $v['api_method_code'], 'ApiMethodResponseException.type' => $v['type'])));
                        $v['id']=isset($ApiMethodResponseException_first['ApiMethodResponseException']['id'])?$ApiMethodResponseException_first['ApiMethodResponseException']['id']:0;
                        $s=$this->ApiMethodResponseException->save($v);
                         if(isset($s)&&!empty($s)){
                        	 	++$success_num;
                        	 }
                        $result['code']=1;
                    }
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/api_methods/method_response_exception_upload/'</script>";
			die();
                } else {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/api_methods/method_response_exception_upload/'</script>";
	
                }
         
    }
//导出项目方法——响应异常csv
public function download_method_response_exception_csv_example($out_type = ' ApiMethodResponseException'){
 Configure::write('debug', 0);
     $this->layout="ajax";
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_response_exception_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $ApiMethodResponseException_info = $this-> ApiMethodResponseException->find('all', array('order' => ' ApiMethodResponseException.id desc'));
          foreach ($ApiMethodResponseException_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}



//FAQ上传
public function method_faq_upload(){
	Configure::write('debug', 0);
        	$this->operation_return_url(true);
			$this->menu_path = array('root' => '/edi/','sub' => '/api_methods/');
			
			//当前位置
			$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
			$this->navigations[] = array('name' => $this->ld['api_method_manage'],'url' =>'/api_methods/');				
			$this->navigations[] = array('name' => 'FAQ'.$this->ld['bulk_upload'],'url' =>'');
			 $this->set('title_for_layout','FAQ'.$this->ld['bulk_upload'].' - '.$this->ld['api_method_manage'].' - '.$this->configs['shop_name']);
			 $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_faq_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//上传项目方法——FAQcvs查看
 public function method_faq_uploadpreview()
    {
    	Configure::write('debug', 0);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/method_faq_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_faq_export', 'Profile.status' => 1)));
		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
      	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	$fields[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
  	  }
               
                            $key_arr = array();
                            foreach ($fields_array as $k => $v) {
                                $fields_k = explode('.', $v);
                                $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8//IGNORE', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] =@iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/method_faq_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
                    $upload_num=count($checkbox_arr);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        
                        $ApiMethodFaq_first = $this->ApiMethodFaq->find('first', array('conditions' => array('ApiMethodFaq.api_project_code' => $v['api_project_code'],'ApiMethodFaq.api_category_code' => $v['api_category_code'],'ApiMethodFaq.api_method_code' => $v['api_method_code'], 'ApiMethodFaq.question' => $v['question'])));
                        $v['id']=isset($ApiMethodFaq_first['ApiMethodFaq']['id'])?$ApiMethodFaq_first['ApiMethodFaq']['id']:0;
                        $s=$this->ApiMethodFaq->save($v);
                        if(isset($s)&&!empty($s)){
                        	 	++$success_num;
                        	 }
                        $result['code']=1;
                    }
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/api_methods/method_faq_upload/'</script>";
			die();
                } else {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/api_methods/method_faq_upload/'</script>";
	
                }
         
    }
//导出项目方法——FAQcsv
public function download_method_faq_csv_example($out_type = 'ApiMethodFaq'){
 Configure::write('debug', 0);
    
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_faq_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $ApiMethodFaq_info = $this->ApiMethodFaq->find('all', array('order' => 'ApiMethodFaq.id desc'));
          foreach ($ApiMethodFaq_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  if($vs=="ApiMethodFaq.question"||$vs=="ApiMethodFaq.answer"){
                  	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? htmlspecialchars_decode($v[$fields_ks[0]][$fields_ks[1]]) : '';
                  }else{
                  	$user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
                  }
              }
              $newdatas[] = $user_tmp;
              
          }
		//定义文件名称
		$this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
		die();
      
}


//错误代码上传
public function method_error_code_upload(){
	Configure::write('debug', 0);
        	$this->operation_return_url(true);
			$this->menu_path = array('root' => '/edi/','sub' => '/api_methods/');
			
			//当前位置
			$this->navigations[] = array('name' => $this->ld['web_application'],'url' => '');
			$this->navigations[] = array('name' => $this->ld['api_method_manage'],'url' =>'/api_methods/');				
			$this->navigations[] = array('name' => '错误代码'.$this->ld['bulk_upload'],'url' =>'');
			 $this->set('title_for_layout','错误代码'.$this->ld['bulk_upload'].' - '.$this->ld['api_method_manage'].' - '.$this->configs['shop_name']);
			 $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_error_code_export', 'Profile.status' => 1)));
       $this->set('profile_id',$profile_id);
    }



//上传项目方法——错误代码cvs查看
 public function method_error_code_uploadpreview()
    {
    	Configure::write('debug', 0);
    	$success_num=0;
                if (!empty($_FILES['file'])) {
                    if (!empty($_FILES['file'])) {
                        if ($_FILES['file']['error'] > 0) {
                            echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' />;<script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/method_error_code_upload';</script>";
                            die();	
                        } else {
                            $handle = @fopen($_FILES['file']['tmp_name'], 'r');
             $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_error_code_export', 'Profile.status' => 1)));
		$profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
      	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	$fields[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
  	  }
               
                            $key_arr = array();
                            foreach ($fields_array as $k => $v) {
                                $fields_k = explode('.', $v);
                                $key_arr[] = isset($fields_k[1]) ? $fields_k[1] : '';
                            }
                            $csv_export_code = 'gb2312';
                            $i = 0;
                            while ($row = $this->fgetcsv_reg($handle, 10000, ',')) {
                                if ($i == 0) {
                                    $check_row = $row[0];
                                    $row_count = count($row);
                                    $check_row = iconv('GB2312', 'UTF-8//IGNORE', $check_row);
                                    $num_count = count($key_arr);
                                    ++$i;
                                }
                                $temp = array();
                                foreach ($row as $k => $v) {
                                    $temp[$key_arr[$k]] =@iconv($csv_export_code, 'utf-8//IGNORE', $v);
                                }
                                if (!isset($temp) || empty($temp)) {
                                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script>alert('".$this->ld['file_upload_error']."');window.location.href='/admin/api_methods/method_error_code_upload';</script>";
                                    die();
                                }
                                $data[] = $temp;
                            }
                            fclose($handle);
                            $this->set('fields', $fields);
                            $this->set('key_arr', $key_arr);
                            $this->set('data_list', $data);
                        }
                    }
                } elseif (isset($_REQUEST['checkbox']) && !empty($_REQUEST['checkbox'])) {
                    $checkbox_arr = $_REQUEST['checkbox'];
                    $upload_num=count($checkbox_arr);
                    foreach ($this->data as $key => $v) {
                        if (!in_array($key, $checkbox_arr)) {
                            continue;
                        }
                        
                        $ApiMethodErrorCode_first = $this->ApiMethodErrorCode->find('first', array('conditions' => array('ApiMethodErrorCode.api_project_code' => $v['api_project_code'], 'ApiMethodErrorCode.api_method_code' => $v['api_method_code'], 'ApiMethodErrorCode.error_code' => $v['error_code'])));
                        $v['id']=isset($ApiMethodErrorCode_first['ApiMethodErrorCode']['id'])?$ApiMethodErrorCode_first['ApiMethodErrorCode']['id']:0;
                        $s=$this->ApiMethodErrorCode->save($v);
                        if(isset($s)&&!empty($s)){
                        	 	++$success_num;
                        	 }
                        $result['code']=1;
                    }
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('".'共上传：'.$upload_num.'　条数据'.'\\r\\n'.'上传成功：'.$success_num.'　条数据'.'\\r\\n'.'上传失败：'.($upload_num-$success_num).'　条数据'."');window.location.href='/admin/api_methods/method_error_code_upload/'</script>";
			die();
                } else {
                    echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8' /><script type='text/javascript'>alert('未上传任何数据');window.location.href='/admin/api_methods/method_error_code_upload/'</script>";

                }
         
    }
//导出项目方法——错误代码csv
public function download_method_error_code_csv_example($out_type = 'ApiMethodErrorCode'){
 Configure::write('debug', 0);
     
     //定义一个数组
     $this->Profile->hasOne = array();
       $profile_id = $this->Profile->find('first', array('fields' => array('Profile.id'), 'conditions' => array('Profile.code' =>'api_method_error_code_export', 'Profile.status' => 1)));
      if (isset($profile_id) && !empty($profile_id)) {
       $profilefiled_info = $this->ProfileFiled->find('all', array('fields' => array('ProfileFiled.code', 'ProfilesFieldI18n.description'), 'conditions' => array('ProfilesFieldI18n.locale' => $this->backend_locale, 'ProfileFiled.profile_id' => $profile_id['Profile']['id'], 'ProfileFiled.status' => 1), 'order' => 'ProfileFiled.orderby asc,ProfileFiled.id'));
  	$fields_array=array();
	  	foreach($profilefiled_info as $k=>$v){
	  	//描述：注释
	  	 $tmp[] = $v['ProfilesFieldI18n']['description'];
	  	 //project_list(样式modal.field)
	       $fields_array[] = $v['ProfileFiled']['code'];
	  	}
  	}
   		$newdatas = array();
          $newdatas[] =  $tmp;
          //查询所有表里面所有信息 
          $ApiMethodErrorCode_info = $this->ApiMethodErrorCode->find('all', array('order' => 'ApiMethodErrorCode.id desc'));
          foreach ($ApiMethodErrorCode_info as $k => $v) {
              $user_tmp = array();
              //循环数组
              foreach ($fields_array as $ks => $vs) {
                    //分解字符串为数组
                  $fields_ks = explode('.', $vs);
                  $user_tmp[] = isset($v[$fields_ks[0]][$fields_ks[1]]) ? $v[$fields_ks[0]][$fields_ks[1]] : '';
              }
              $newdatas[] = $user_tmp;
          }
          //定义文件名称
         
           $this->Phpcsv->output($out_type.date('YmdHis').'.csv', $newdatas);
        	exit;
      
}














	 //-------项目管理		    
		public function api_project($id='0',$type = 'list')
		{
		 	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		            //仅限ajax访问
		            Configure::write('debug', 1);
		            $this->layout = 'ajax';
		            $this->set('type', $type);
		            
		            //项目管理——列表显示
		            if ($type == 'list') {
					$condition = '';
					//项目状态筛选
					if (isset($this->params['url']['status']) && $this->params['url']['status'] != '') {
				     	$condition['AND']['ApiProject.status LIKE']=$this->params['url']['status'];
				     	$this->set('status', $this->params['url']['status']);
					}
					//项目名称/代码筛选
			        	if (isset($this->params['url']['keyword']) && $this->params['url']['keyword'] != '') {
			         	      $condition['OR'][]="ApiProject.code like '%".$this->params['url']['keyword']."%' ";
			                   $condition['OR'][]="ApiProject.name like '%".$this->params['url']['keyword']."%' ";
			           	      $this->set('keyword', $this->params['url']['keyword']);
			             }
					$project_infos = $this->ApiProject->find('all', array('conditions' => $condition,'order'=>'ApiProject.created DESC'));
					$this->set('project_infos', $project_infos);
					
				//项目管理——编辑操作
		            }elseif ($type == 'edit') {
					$id = isset($_POST['Id']) ? $_POST['Id'] : 0;
				   	$this->ApiProject->id=$id;
					$project_info=$this->ApiProject->read();
					$this->set('project_info',$project_info);
					
		            //项目管理——保存数据
		            }elseif ($type == 'save') {
		            	//如果后台提交数据不为空
					if(!empty($this->data))
					{	
						$result['code'] = 0;
						//（除本条提交的数据外）查询具有相同项目代码的数据NUM
						$project_code_condition='';
						$project_code_condition['AND']['ApiProject.code']=$this->data['ApiProject']['code'];
						$project_code_condition['AND']['ApiProject.id <>']=$this->data['ApiProject']['id'];
						$project_code_num= $this->ApiProject->find('count',array('conditions'=>$project_code_condition));
						
						//（除本条提交的数据外）查询具有相同项目名称的数据NUM
						$project_name_condition='';
						$project_name_condition['AND']['ApiProject.code']=$this->data['ApiProject']['code'];
						$project_name_condition['AND']['ApiProject.id <>']=$this->data['ApiProject']['id'];
						$project_name_num= $this->ApiProject->find('count',array('conditions'=>$project_name_condition));
									
						//如果都为0，说明没有重复数据，保存
						if($project_code_num=='0' && $project_name_num=='0')
						{
							if($this->ApiProject->save($this->data))
							{
								$result['code'] = 1;
							}
						}
					      die(json_encode($result));
					}
					
				//项目管理——删除操作
		            }elseif ($type == 'del') {
		            	$result['code']=0;
		                	$del_id = isset($_POST['Id']) ? $_POST['Id'] :0;
       				 $project_condition['ApiProject.id']=$del_id;
				        $project_info = $this->ApiProject->find('first',array('conditions'=>$project_condition));
				        
				        //如果项目删除成功，删除对应的分类，对应的方法
				        if($this->ApiProject->delete(array('id '=> $del_id)))
				        {
					       $result['code'] = 1;
					       $this->ApiCategory->deleteAll(array('ApiCategory.api_project_code'=>$project_info['ApiProject']['code']));
					       $this->ApiMethod->deleteAll(array('ApiMethod.api_project_code'=> $project_info['ApiProject']['code']));
				        }
				        die(json_encode($result));
		            }	
 			}
		}
				    

 //项目管理中的公共请求参数管理---
		public function api_project_common_parameter($main_id,$type = 'list')
		{
		 	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		            //仅限ajax访问
		            Configure::write('debug', 1);
		            $this->layout = 'ajax';
		            $this->set('type', $type);
		            
		            $project_info=$this->ApiProject->find('first',array('conditions'=>array('ApiProject.id'=>$main_id)));
		            $this->set('project_info',$project_info);
		            
		          	//公共请求参数管理——显示列表
		            if ($type == 'list') {
		            	 if (!empty($project_info)) 
		            	 {
				                   $project_common_parameter_condition='';
				                   $project_common_parameter_condition['AND']['ApiProjectCommonParameter.api_project_code']  = $project_info['ApiProject']['code'];
		            		    	$project_common_parameter_infos=$this->ApiProjectCommonParameter->find('all',array('conditions'=>$project_common_parameter_condition,'order'=>'ApiProjectCommonParameter.created DESC'));
				                   $this->set('project_common_parameter_infos',$project_common_parameter_infos);
	  		            	}
	  		            	
	  		      //公共请求参数管理——编辑操作
		            }elseif ($type == 'edit') {
		                   $edit_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
                			$project_common_parameter_info = $this->ApiProjectCommonParameter->find('first', array('conditions' => array('ApiProjectCommonParameter.id' => $edit_id)));
                			$this->set('project_common_parameter_info', $project_common_parameter_info);
                			
		            //公共请求参数管理——保存数据
		            }elseif ($type == 'save') {
					if (  !empty($this->data)  ){
						$result['code'] = 0;
						//（除本条提交的数据外）查询具有相同项目名称的数据NUM
						$project_common_parameter_condition='';
						$project_common_parameter_condition['AND']['ApiProjectCommonParameter.api_project_code']=$this->data['ApiProjectCommonParameter']['api_project_code'];
						$project_common_parameter_condition['AND']['ApiProjectCommonParameter.name']=$this->data['ApiProjectCommonParameter']['name'];
						$project_common_parameter_condition['AND']['ApiProjectCommonParameter.id <>']=$this->data['ApiProjectCommonParameter']['id'];
						$project_common_parameter_num= $this->ApiProjectCommonParameter->find('count',array('conditions'=>$project_common_parameter_condition));
					
						//如果为0，说明没有重复数据，保存
				             if($project_common_parameter_num=='0') 
				             {
				                    if ($this->ApiProjectCommonParameter->save($this->data)) 
				                    {
				                      	  $result['code'] = 1;
				                    }
				           	}
				            die(json_encode($result));
				   	 }
				   	 
				//公共请求参数管理——删除操作
		            }elseif ($type == 'del') {
		            	$result['code'] = 0;
			             $del_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
			             if ($this->ApiProjectCommonParameter->delete(array('id' => $del_id))) 
			             {
			                    $result['code'] = 1;
			                }
			              die(json_encode($result));
		            }	
 			}
		}
		
//-----项目管理中的对象管理			
		public function api_object($main_id,$type = 'list')
		{
		 if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		            //仅限ajax访问
		            Configure::write('debug', 1);
		            $this->layout = 'ajax';
		            $this->set('type', $type);
		            $project_info=$this->ApiProject->find('first',array('conditions'=>array('ApiProject.id'=>$main_id)));
		            $this->set('project_info',$project_info);
		            
		            //对象管理——列表显示
		            if ($type == 'list') {
		            	 if (!empty($project_info)) 
		            	 {
				               $object_condition='';
				               $object_condition['AND']['ApiObject.api_project_code']  = $project_info['ApiProject']['code'];
		            		  $object_infos=$this->ApiObject->find('all',array('conditions'=>$object_condition,'order'=>'ApiObject.created DESC'));
				           	  $this->set('object_infos',$object_infos);
	  		         	}
	  		         	
	  		      //对象管理——编辑操作
		            }elseif ($type == 'edit') {
		              	$edit_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
                			$object_info = $this->ApiObject->find('first', array('conditions' => array('ApiObject.id' => $edit_id)));
                			$this->set('object_info', $object_info);
                			
                		//对象管理——保存数据
		            }elseif ($type == 'save') {
					 if (!empty($this->data)){
					 	 $id=$this->data['ApiObject']['id'];
						 $object_info= $this->ApiObject->find('first', array('conditions' => array('ApiObject.id' => $id)));
						 $object_condition='';
						 
			                    $object_condition['AND']['ApiObject.api_project_code']  = $this->data['ApiObject']['api_project_code'];
			                    $object_condition['AND']['ApiObject.code']  = $this->data['ApiObject']['code'];
			                    $object_condition['AND']['ApiObject.id <>']  = $this->data['ApiObject']['id'];
			                    //$this->set('object_condition',$object_condition);
						$object_name_num= $this->ApiObject->find('count',array('conditions'=>$object_condition));
					 	 $result['code'] = 0;
						if($object_name_num=='0'){
					              if ($this->ApiObject->save($this->data)) 
					              {
					                        $result['code'] = 1;
					                        $this->loadModel('ApiObjectField');
								   $this->ApiObjectField->updateAll(array('api_object_code'=>"'".$this->data['ApiObject']['code']."'"),array('api_project_code'=>$this->data['ApiObject']['api_project_code'],'api_object_code'=>$object_info['ApiObject']['code'] ));
					               }
					          }
						  	die(json_encode($result));
						
						 }
						 
				//对象管理——删除操作
		            }elseif ($type == 'del') {
		            	$result['code'] = 0;
			             $del_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
			           	$object_Infos = $this->ApiObject->find('first', array('conditions' => array('ApiObject.id' => $del_id)));
			             if ($this->ApiObject->delete(array('id' => $del_id))) {
			                    $result['code'] = 1;
			                    $this->loadModel('ApiObjectField');
					 	$this->ApiObjectField->deleteAll(array('api_object_code'=>$object_Infos['ApiObject']['code'],'api_project_code'=>$object_Infos['ApiObject']['api_project_code'] ));
			                }
			                die(json_encode($result));
		            }	
 			}
		}

		
//-项目管理中的-对象管理中的-对象字段管理
		public function api_object_field($main_id,$type = 'list')
		{
		 	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		            //仅限ajax访问
		            Configure::write('debug', 1);
		            $this->layout = 'ajax';
		            $this->set('type', $type);
		            //根据Id，获取对象的详细信息
		            $object_info=$this->ApiObject->find('first',array('conditions'=>array('ApiObject.id'=>$main_id)));
		            $this->set('object_info',$object_info);
		            $project_info=$this->ApiProject->find('first',array('conditions'=>array('ApiProject.code'=>$object_info['ApiObject']['api_project_code'])));
		            $this->set('project_info',$project_info);
		            //对象字段管理——列表显示
		            if ($type == 'list') {
		            	      if (!empty($object_info)) {
				                    $object_field_condition='';
				                    $object_field_condition['AND']['ApiObjectField.api_project_code']  = $object_info['ApiObject']['api_project_code'];
				                    $object_field_condition['AND']['ApiObjectField.api_object_code']  = $object_info['ApiObject']['code'];
		            		  	 $object_field_infos=$this->ApiObjectField->find('all',array('conditions'=>$object_field_condition));
				                 	 $this->set('object_field_infos',$object_field_infos);
	  		            	}
	  		       //对象字段管理——编辑操作
		            }elseif ($type == 'edit') {
			              $edit_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
	                		$object_field_info = $this->ApiObjectField->find('first', array('conditions' => array('ApiObjectField.id' => $edit_id)));
	                		$this->set('object_field_info', $object_field_info);	
	                		
                		//对象字段管理——保存数据	            
		            }elseif ($type == 'save') {
		            	if (!empty($this->data))
		            	{
		            		//（除本条提交的数据外）查询具有相同项目名称的数据NUM
		            		 $object_field_condition='';
			                    $object_field_condition['AND']['ApiObjectField.api_project_code']  = $object_info['ApiObject']['api_project_code'];
			                    $object_field_condition['AND']['ApiObjectField.name']  = $object_info['ApiObject']['name'];
			                    $object_field_condition['AND']['ApiObjectField.id <>']  = $object_info['ApiObject']['id'];
						$object_fields_name_num= $this->ApiObjectField->find('count',array('conditions'=>$object_field_condition));
		            	 	$result['code'] = 0;
		            	 	
		            	 	//如果为0，说明没有重复数据，保存
 	 					if($object_fields_name_num=='0')
 	 					{
			                   	 if ($this->ApiObjectField->save($this->data))
			                   	  {
			                       		 $result['code'] = 1;
			                    	    }
			            	 }
			           	 	die(json_encode($result));
			          }
			      //对象字段管理——删除操作
		            }elseif ($type == 'del') {
		            	$result['code'] = 0;
			             $del_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
			             if ($this->ApiObjectField->delete(array('id' => $del_id)))
			              {
			                    $result['code'] = 1;
			                }
			                die(json_encode($result));
		            }	
 			}
		}
		

//-项目管理中的-错误代码解释管理
	public function api_error_code_interpretation($main_id,$type = 'list')
		{
		 if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		            //仅限ajax访问
		            Configure::write('debug', 1);
		            $this->layout = 'ajax';
		            $this->set('type', $type);
		            //根据id，获取项目的详细信息
		            $project_info=$this->ApiProject->find('first',array('conditions'=>array('ApiProject.id'=>$main_id)));
		            $this->set('project_info',$project_info);
		            
		            //错误代码解释管理——列表显示
		            if ($type == 'list') {
		            	      if (!empty($project_info)) {
				                    $error_code_interpretation_condition='';
				                    $error_code_interpretation_condition['AND']['ApiErrorCodeInterpretation.api_project_code']  = $project_info['ApiProject']['code'];
		            			 $error_code_interpretation_infos=$this->ApiErrorCodeInterpretation->find('all',array('conditions'=>$error_code_interpretation_condition,'order'=>'ApiErrorCodeInterpretation.created DESC'));
				                    $this->set('error_code_interpretation_infos',$error_code_interpretation_infos);
	  		            	}
	  		            	
	  		       //错误代码解释管理——编辑操作    	
		            }elseif ($type == 'edit') {
			             $edit_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
	                		$error_code_interpretation_info = $this->ApiErrorCodeInterpretation->find('first', array('conditions' => array('ApiErrorCodeInterpretation.id' => $edit_id)));
	                		$this->set('error_code_interpretation_info', $error_code_interpretation_info);
	                		
	                	//错误代码解释管理——保存数据	
		            }elseif ($type == 'save') {
					 if (!empty($this->data))
					 {
					    	       $id= $this->data['ApiErrorCodeInterpretation']['id'];
							$error_code_interpretation_info = $this->ApiErrorCodeInterpretation->find('first', array('conditions' => array('ApiErrorCodeInterpretation.id' => $id)));
					    		$result['code'] = 0;
					    		//（除本条提交的数据外）查询具有相同项目名称的数据NUM
					    		$error_code_interpretation_condition='';
					    		$error_code_interpretation_condition['AND']['ApiErrorCodeInterpretation.api_project_code']=$this->data['ApiErrorCodeInterpretation']['api_project_code'];
					    		$error_code_interpretation_condition['AND']['ApiErrorCodeInterpretation.code']=$this->data['ApiErrorCodeInterpretation']['code'];
					    		$error_code_interpretation_condition['AND']['ApiErrorCodeInterpretation.id <>']=$this->data['ApiErrorCodeInterpretation']['id'];
                					$error_code_interpretation_num= $this->ApiErrorCodeInterpretation->find('count',array('conditions'=>$error_code_interpretation_condition));
					   	 
					   	 	//如果为0，说明没有重复数据，保存
					   	 	if($error_code_interpretation_num=='0')  
					   	 	  {
					                    if ($this->ApiErrorCodeInterpretation->save($this->data))
					                     {
									$result['code'] = 1;
						      	 	$this->ApiMethodErrorCode->updateAll(array('error_code'=>"'".$this->data['ApiErrorCodeInterpretation']['code']."'"),array('api_project_code'=>$this->data['ApiErrorCodeInterpretation']['api_project_code'],'error_code'=>$error_code_interpretation_info['ApiErrorCodeInterpretation']['code'] ));
					                       }
					               }
					               die(json_encode($result));
			        	}
			        	
			      //错误代码解释管理——删除操作
		            }elseif ($type == 'del') {
		            	$result['code'] = 0;
			             $del_id = isset($_POST['Id']) ? $_POST['Id'] : 0;
					$error_code_interpretation_info = $this->ApiErrorCodeInterpretation->find('first', array('conditions' => array('ApiErrorCodeInterpretation.id' => $del_id)));
			             if ($this->ApiErrorCodeInterpretation->delete(array('id' => $del_id)))
			              {
			                    $result['code'] = 1;
			              	 $this->loadModel('ApiMethodErrorCode');
						 $this->ApiMethodErrorCode->deleteAll(array('error_code'=>$error_code_interpretation_info['ApiErrorCodeInterpretation']['code'],'api_project_code'=>$error_code_interpretation_info['ApiErrorCodeInterpretation']['api_project_code'] ));

			                }
			                die(json_encode($result));
		            }
 			}
		}
		
  //------项目分类管理
		public function api_category($id='0',$type = 'list')
		{
		 if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
		            //仅限ajax访问
		            Configure::write('debug', 1);
		            $this->layout = 'ajax';
		            $this->set('type', $type);
		            
		            //项目列表
		           	$project_list=$this->ApiProject->find('list',array('fields'=>array('ApiProject.name','ApiProject.code')));
				$this->set('project_list',$project_list);
				
				//项目分类管理——列表显示
		            if ($type == 'list') {
			            //设置操作返回页面地址
				       $this->operator_privilege('api_categorie_view');
				        $this->operation_return_url(true);
				       $this->menu_path = array('root' => '/edi/','sub' => '/api_categories/');
				        $this->ApiCategory->set_locale($this->backend_locale);
				        
				        $condition = '';
				        //项目筛选
				         if (isset($this->params['url']['codes']) && $this->params['url']['codes'] != '') {
				             $condition['AND']['ApiCategory.api_project_code ']=$this->params['url']['codes'];
				            $this->set('codes', $this->params['url']['codes']);
				        }
			        	 //分类代码或名称筛选
				        if (isset($this->params['url']['categ_name']) && $this->params['url']['categ_name'] != '') {
				          	$condition['OR'][]="ApiCategory.code like '%".$this->params['url']['categ_name']."%' ";
				          	$condition['OR'][]="ApiCategory.name like '%".$this->params['url']['categ_name']."%' ";
				             $this->set('categ_names', $this->params['url']['categ_name']);
				        }
			        	$category_infos = $this->ApiCategory->find('all', array('conditions' => $condition,'order'=>'ApiCategory.orderby ASC'));
			       	 $this->set('category_infos', $category_infos);
			       	 
			      //项目分类管理——编辑操作
		            }elseif ($type == 'edit') {
			  		$id = isset($_POST['Id']) ? $_POST['Id'] :0;
					$this->ApiCategory->id=$id;
					$category_info=$this->ApiCategory->read();
					$this->set('category_info',$category_info);
					
				//项目分类管理——保存数据
		            }elseif ($type == 'save') {
					if(!empty($this->data))
					{
			            	$result['code'] = 0;
			            	//（除本条提交的数据外）查询具有相同分类代码的数据NUM
			            	$category_code_condition='';
			            	$category_code_condition['AND']['ApiCategory.api_project_code']=$this->data['ApiCategory']['api_project_code'];
			            	$category_code_condition['AND']['ApiCategory.code']=$this->data['ApiCategory']['code'];
			            	$category_code_condition['AND']['ApiCategory.id <>']=$this->data['ApiCategory']['id'];
						$category_code_num= $this->ApiCategory->find('count',array('conditions'=>$category_code_condition));
						//（除本条提交的数据外）查询具有相同分类名称的数据NUM
						$category_name_condition='';
			            	$category_name_condition['AND']['ApiCategory.api_project_code']=$this->data['ApiCategory']['api_project_code'];
			            	$category_name_condition['AND']['ApiCategory.name']=$this->data['ApiCategory']['name'];
			            	$category_name_condition['AND']['ApiCategory.id <>']=$this->data['ApiCategory']['id'];
						$category_name_num= $this->ApiCategory->find('count',array('conditions'=>$category_name_condition));
						if($category_code_num=='0' && $category_name_num=='0') 
						{	
							if($this->ApiCategory->save($this->data))
							{
								$result['code'] = 1;

							}
						}
						die(json_encode($result));
					}
					
		            //项目分类管理——删除操作
		            }elseif ($type == 'del') {
		            	$result['code']=0;
		                	$del_id = isset($_POST['Id']) ? $_POST['Id'] :0;
       				$categcondition['ApiCategory.id']=$del_id;
				       $category_info = $this->ApiCategory->find('first',array('conditions'=>$categcondition));
				        if($this->ApiCategory->delete(array('id'=>$del_id)))
				        {
		         	 		$result['code']=1;
		   			 	$this->ApiMethod->deleteAll(array('ApiMethod.api_category_code'=> $category_info['ApiCategory']['code']));
 				        }
				        die(json_encode($result));
		            }	
 			}
		}


}

?>