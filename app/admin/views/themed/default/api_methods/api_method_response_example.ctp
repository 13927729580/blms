<style type="text/css">
	.am-form-group{margin:10px 0;} 
	.am-radio-inline{ float:left;}
</style>


<?php if($type=="list"){ ?>
	<?php	foreach ($_GET as $v){$str=$v;} $arr=explode('/',$str); //pr($arr) ?>
			<input type="hidden" name="data[main_id]" value="<?php echo $arr[3] ; ?>" />
				<?php //pr($arr[3]) ; ?>
	<div class="am-panel-group am-panel-tree" id="method_response_example_ajaxdata">
	    			<div class="am-g am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
					<ul class=" am-bt" style="list-style-type:none;">							
							<li style="margin:0 10px 0 0; float:right;">
									
										  <button type="button" class="am-btn am-btn-warning am-btn-sm am-radius"  onclick="edit_method_response_example('','<?php echo $project_info['ApiProject']['name'] ; ?>','<?php echo $method_info['ApiMethod']['name'] ; ?>')">
 											 <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
										</button>

								
									
									
									
							
							</li>
					</ul>
				</div>
				<div class="   am-panel-header">
			
							<div class="am-panel-hd">
											<div class="am-panel-title am-g">
												<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
													<label class="am-checkbox am-success  " style="font-weight:bold;padding-top:0">
														<input type="checkbox" data-am-ucheck onclick='listTable.selectAll(this,"checkbox[]")'/>
																
													</label>  
												</div>
												<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">类型</div>
											
												<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">描述</div>
												
											
												
												<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['operate']?></div>
											</div>
							</div>
				</div>
										
		<?php if(isset($method_response_example_infos) && !empty($method_response_example_infos)) {?>											
	    		<?php foreach( $method_response_example_infos as $v){ ?>
	    			<div >
				
					<div class=" am-panel-body">
						<div class="am-panel-bd am-g">
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
												<label class="am-checkbox am-success  " style="padding-top:0">
													<input type="checkbox" name="checkboxs[]" data-am-ucheck  value="<?php echo $v['ApiMethodResponseExample']['id']?>" />
													
												</label>
											</div>
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
													<span ><?php switch( $v['ApiMethodResponseExample']['type']){case 'XML': echo 'XML示例';break; case 'JSON':echo 'JSON示例';break; }?>   </span>
											</div>
										
											<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
													<div  style="word-wrap:break-word;word-break:normal;" ><?php echo empty($v['ApiMethodResponseExample']['description'])?'&nbsp;':$v['ApiMethodResponseExample']['description'];     ?></div>
											</div>
											
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 seolink am-btn-group-xs am-action">							
												 <button type="button" class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_method_response_example('<?php echo $v['ApiMethodResponseExample']['id']  ?>','<?php echo $project_info['ApiProject']['name'] ; ?>','<?php echo $method_info['ApiMethod']['name'] ; ?>')">
								                        <span class="am-icon-pencil-square-o"> <?php echo $ld['edit']; ?></span>
								                      </button>                
												<button type="button" class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" 
												  onclick="del_method_response_example('<?php echo $v['ApiMethodResponseExample']['id'] ?>')" >
						                        			<span class="am-icon-trash-o"> <?php echo $ld['delete']; ?></span>
						                      			</button>
						                      							
											</div>
							</div>
						</div>
					</div>
	    			<?php }}else{  ?>
						<div>
							<div  class="no_data_found"><?php echo $ld['no_data_found']?></div>
<!--1-->						</div>						
												
		<?php } ?>
</div>
	
	  <script type="text/javascript">
function loadmethodresponseexample(user_id){
	$.ajax({ url: admin_webroot+"api_methods/api_method_response_example/"+user_id,
		type:"POST",
		dataType:"html",
		success: function(data){
			$("#method_response_example_ajaxdata").parent().html(data);
	
  		}
  	});
}	  			
	  			
	  			
	  function edit_method_response_example(Id,val1,val2){
	  	  $("#method_response_example_btn").click();
	  	  		if(Id=='' || Id=='0'){ var str='添加'}else{ var str='编辑'}

	var main_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
	$.ajax({ url: admin_webroot+"api_methods/api_method_response_example/"+main_id+"/edit",
		type:"POST",
		data:{'Id':Id},
		dataType:"html",
		success: function(data){
		
			$("#method_response_example_popup .am-modal-bd").find('#methodresponseexampleoperation').remove();
						$(" #modal_title_response_example").html(val1+'/'+val2+'/响应示例管理——'+str);

				$("#method_response_example_popup .am-modal-bd").html(data);
  		}
  	});
}

function del_method_response_example(Id){
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		var user_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
		$.ajax({ url: admin_webroot+"api_methods/api_method_response_example/"+user_id+"/del",
			type:"POST",
			data:{'Id':Id},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadmethodresponseexample(user_id);
				}else{
					alert("<?php echo $ld['delete_failure'] ?>");
				}
			}
		});
	}
}



</script>
	
<?php }else{ ?>
		<div style="margin-top:10px;"  id="methodresponseexampleoperation">
				
    			 <?php echo $form->create('',array('action'=>'','id'=>'api_error_code_interpretation_form','name'=>'method_response_example_Info_views'));?>
    			 	 	<?php	foreach ($_GET as $v){$str=$v;} $arr=explode('/',$str); //pr($arr) ?>
    			 	   <?php 	if($arr[3]==$method_info['ApiMethod']['id'])
    			 	 			{
    			 	 				 $project_code=$method_info['ApiMethod']['api_project_code'];
    			 	 				 $category_code=$method_info['ApiMethod']['api_category_code'];
    			 	 				 $method_code=$method_info['ApiMethod']['code'];
    			 				 } 
    			 	 ?>	 
			<input type="hidden" name="data[main][id]" value="<?php echo $arr[3] ; ?>" />
      		<div class="am-tab-panel am-fade am-active am-in am-form-detail am-form am-form-horizontal" >
		      			 <input type="hidden" value="<?php echo isset($method_response_example_Info['ApiMethodResponseExample']['api_project_code'])?$method_response_example_Info['ApiMethodResponseExample']['api_project_code']:$project_code ?>" name="data[ApiMethodResponseExample][api_project_code]" >
		      			<input type="hidden" value="<?php echo isset($method_response_example_Info['ApiMethodResponseExample']['api_category_code'])?$method_response_example_Info['ApiMethodResponseExample']['api_category_code']:$category_code ?>" name="data[ApiMethodResponseExample][api_category_code]" >
					    <input type="hidden" value="<?php echo isset($method_response_example_Info['ApiMethodResponseExample']['api_method_code'])?$method_response_example_Info['ApiMethodResponseExample']['api_method_code']:$method_code ?>" name="data[ApiMethodResponseExample][api_method_code]" >
					    <input type="hidden" value="<?php echo isset($method_response_example_Info['ApiMethodResponseExample']['id'])?$method_response_example_Info['ApiMethodResponseExample']['id']:'0'; ?>" name="data[ApiMethodResponseExample][id]" >
        <input type="hidden" value="<?php echo isset($method_response_example_Info['ApiMethodResponseExample']['user_id'])?$method_response_example_Info['ApiMethodResponseExample']['user_id']:'0'; ?>" name="data[ApiMethodResponseExample][user_id]" >		  
				  <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:1px;">类型</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        	
					    					<label class="am-radio-inline am-success" style="padding-top:1px;">
					    					<input type="radio" class="radio"  data-am-ucheck value="JSON" name="data[ApiMethodResponseExample][type]" <?php echo isset($method_response_example_Info['ApiMethodResponseExample']['type']) && $method_response_example_Info['ApiMethodResponseExample']['type']=='JSON'?'checked':'';      ?> /> JSON示例 </label> 
										<label class="am-radio-inline am-success" style="padding-top:1px;">
										<input type="radio" class="radio"  data-am-ucheck name="data[ApiMethodResponseExample][type]" value="XML" <?php echo (isset($method_response_example_Info['ApiMethodResponseExample']['type']) && $method_response_example_Info['ApiMethodResponseExample']['type']=='XML')||!isset($method_response_example_Info['ApiMethodResponseExample']['type'])?'checked':'';      ?> />XML示例</label>
									
					    					
						      </div>
				    </div>
				    
    							
				    <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" >描述</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        	<textarea  name="data[ApiMethodResponseExample][description]" class="am-form-field"  ><?php echo $method_response_example_Info['ApiMethodResponseExample']['description']; ?></textarea>
						      </div>
				    </div>
				    
				    
    
					    				
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
					
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><button type="button"   class="am-btn am-btn-success am-btn-sm am-radius" onclick="saveajax()"  ><?php echo $ld['save'];?></button>
						<button type="button" class="am-btn am-btn-default am-btn-sm am-radius" value="" onclick="clearaddr()"><?php echo $ld['cancel']?></button>　　　　　 </div>
				</div>
      		</div>			
      		<?php echo $form->end(); ?>						
   </div>
	
	<script type="text/javascript">
        
    
		
	function saveajax(){
	//	alert(document.getElementsByName("data[main][id]")[0].value);
	var user_id=document.getElementsByName("data[main][id]")?document.getElementsByName("data[main][id]")[0].value:0;
	var id=document.getElementsByName("data[ApiMethodResponseExample][id]")[0].value;
	var api_project_code=document.getElementsByName("data[ApiMethodResponseExample][api_project_code]")[0].value;
	var api_category_code=document.getElementsByName("data[ApiMethodResponseExample][api_category_code]")[0].value;
	var api_method_code=document.getElementsByName("data[ApiMethodResponseExample][api_method_code]")[0].value;

	var type=$('input[name="data[ApiMethodResponseExample][type]"]:checked').val();
	var descriptions=document.getElementsByName("data[ApiMethodResponseExample][description]")[0].value;
	var description=descriptions.replace( /^\s*/, '');

	 if(api_project_code=="" && api_category_code=="" && api_method_code==""){
		alert("致命错误，没有项目代码和对象代码传进来");
		return false;
	}else if(type == ""){
		alert("请填写类型");return false;
		
	}else{
		$.ajax({ url: admin_webroot+"api_methods/api_method_response_example/0/save",
			type:"POST",
			data:{
					'data[ApiMethodResponseExample][id]':id,
					'data[ApiMethodResponseExample][api_project_code]':api_project_code,		
					'data[ApiMethodResponseExample][api_category_code]':api_category_code,
					'data[ApiMethodResponseExample][api_method_code]':api_method_code,
					'data[ApiMethodResponseExample][type]':type,
					'data[ApiMethodResponseExample][description]':description,
			
				},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadmethodresponseexample(user_id);
					$("#method_response_example_popup").modal('close');
				}else{
					alert("<?php echo $ld['operation_success'] ?>");
				}
	  		}
	  	});
	  	return false;
  	}
}

function clearaddr(){
	$("#method_response_example_ajaxdata").parent().find('#methodresponseexampleoperation').remove();
	$("#method_response_example_popup").modal('close');
}
</script>
	
	
	<?php } ?>