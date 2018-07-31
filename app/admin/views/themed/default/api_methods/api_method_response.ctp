<style type="text/css">
	.am-form-group{margin:20px 0;} 
	.am-radio-inline{ float:left;}
		.red{
color:red;
margin-top:10px;
float:left;
}
</style>


<?php if($type=="list"){ ?>
	<?php	foreach ($_GET as $v){$str=$v;} $arr=explode('/',$str); //pr($arr) ?>
			<input type="hidden" name="data[main_id]" value="<?php echo $arr[3] ; ?>" />
				<?php //pr($arr[3]) ; ?>
	<div class="am-panel-group am-panel-tree" id="method_response_ajaxdata">
	    			<div class="am-g am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
					<ul class=" am-bt" style="list-style-type:none;">							
							<li style="margin:0 10px 0 0; float:right;">
									
										  <button type="button" class="am-btn am-btn-warning am-btn-sm am-radius"  onclick="edit_method_response('','<?php echo $project_info['ApiProject']['name'] ; ?>','<?php echo $method_info['ApiMethod']['name'] ; ?>')">
 											 <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
										</button>

								
									
									
									
							
							</li>
					</ul>
				</div>
				<div class="  am-panel-header">
			
							<div class="am-panel-hd">
											<div class="am-panel-title am-g">
												<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
													<label class="am-checkbox am-success  " style="font-weight:bold;padding-top:0">
														<input type="checkbox" data-am-ucheck onclick='listTable.selectAll(this,"checkbox[]")'/>
																名称
													</label>  
												</div>
												<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld["type"]?></div>
										
												<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">示例值</div>
												<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">描述</div>
												
											
												<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">排序</div>
												<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['operate']?></div>
											</div>
							</div>
				</div>
										
		<?php if(isset($method_response_infos) && !empty($method_response_infos)) {?>											
	    		<?php foreach( $method_response_infos as $v){ ?>
	    			<div >
				
					<div class=" am-panel-body">
						<div class="am-panel-bd am-g">
											<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
												<label class="am-checkbox am-success  " style="padding-top:0">
													<input type="checkbox" name="checkboxs[]" data-am-ucheck  value="<?php echo $v['ApiMethodResponse']['id']?>" />
													<div style="word-wrap:break-word;word-break:normal;"  onclick='listTable.selectAll(this,"checkboxs[]")'><?php echo $v['ApiMethodResponse']['name']?></div>
												</label>
											</div>
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
													<div style="word-wrap:break-word;word-break:normal;" ><?php switch($v['ApiMethodResponse']['type']){ case 'String':echo 'String';break; case 'Number': echo 'Number';break; case 'Boolean': echo 'Boolean';break; default: echo $v['ApiMethodResponse']['type']; }?>   </div>
											</div>
											
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
													<div style="word-wrap:break-word;word-break:normal;" ><?php echo $v['ApiMethodResponse']['samples']?></div>
											</div>
											<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
													<div style="word-wrap:break-word;word-break:normal;" ><?php echo empty( $v['ApiMethodResponse']['description'])?'&nbsp;':$v['ApiMethodResponse']['description'];   ?></div>
											</div>
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
													<div style="word-wrap:break-word;word-break:normal;" ><?php echo empty( $v['ApiMethodResponse']['customer_case_id'])?'&nbsp;':$v['ApiMethodResponse']['customer_case_id'];   ?></div>
											</div>	
											
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 seolink am-btn-group-xs am-action">							
												 <button type="button" class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_method_response('<?php echo $v['ApiMethodResponse']['id']  ?>','<?php echo $project_info['ApiProject']['name'] ; ?>','<?php echo $method_info['ApiMethod']['name'] ; ?>')">
								                        <span class="am-icon-pencil-square-o"> <?php echo $ld['edit']; ?></span>
								                      </button>                
												<button type="button" class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" 
												  onclick="del_method_response('<?php echo $v['ApiMethodResponse']['id'] ?>')" >
						                        			<span class="am-icon-trash-o"> <?php echo $ld['delete']; ?></span>
						                      			</button>
						                      							
											</div>
							</div>
						</div>
					</div>
	    			<?php }}else{  ?>
						<div>
							<div  class="no_data_found"><?php echo $ld['no_data_found']?></div>
<!--2-->						</div>						
												
		<?php } ?>
</div>
	
	  <script type="text/javascript">
function loadmethodresponse(user_id){
	$.ajax({ url: admin_webroot+"api_methods/api_method_response/"+user_id,
		type:"POST",
		dataType:"html",
		success: function(data){
			$("#method_response_ajaxdata").parent().html(data);
	
  		}
  	});
}	  			
	  			
	  			
	  function edit_method_response(Id,val1,val2){
	  	  $("#method_response_btn").click();
	  	  		if(Id=='' || Id=='0'){ var str='添加'}else{ var str='编辑'}

	var main_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
	$.ajax({ url: admin_webroot+"api_methods/api_method_response/"+main_id+"/edit",
		type:"POST",
		data:{'Id':Id},
		dataType:"html",
		success: function(data){
		
			$("#method_response_popup .am-modal-bd").find('#methodresponseoperation').remove();
			$(" #modal_title_response").html(val1+'/'+val2+'/响应参数管理——'+str);
			$("#method_response_popup .am-modal-bd").html(data);
			$("#method_response_popup select#select_1").selected();
  		}
  	});
}

function del_method_response(Id){
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		var user_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
		$.ajax({ url: admin_webroot+"api_methods/api_method_response/"+user_id+"/del",
			type:"POST",
			data:{'Id':Id},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadmethodresponse(user_id);
				}else{
					alert("<?php echo $ld['delete_failure'] ?>");
				}
			}
		});
	}
}

</script>
	
<?php }else{ ?>
		<div style="margin-top:10px;"  id="methodresponseoperation">
				
    			 <?php echo $form->create('',array('action'=>'','name'=>'method_response_Info_vie'));?>
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
		      			 <input type="hidden" value="<?php echo isset($method_response_Info['ApiMethodResponse']['api_project_code'])?$method_response_Info['ApiMethodResponse']['api_project_code']:$project_code ?>" name="data[ApiMethodResponse][api_project_code]" >
		      			<input type="hidden" value="<?php echo isset($method_response_Info['ApiMethodResponse']['api_category_code'])?$method_response_Info['ApiMethodResponse']['api_category_code']:$category_code ?>" name="data[ApiMethodResponse][api_category_code]" >
					    <input type="hidden" value="<?php echo isset($method_response_Info['ApiMethodResponse']['api_method_code'])?$method_response_Info['ApiMethodResponse']['api_method_code']:$method_code ?>" name="data[ApiMethodResponse][api_method_code]" >
					    <input type="hidden" value="<?php echo isset($method_response_Info['ApiMethodResponse']['id'])?$method_response_Info['ApiMethodResponse']['id']:'0'; ?>" name="data[ApiMethodResponse][id]" >
        <input type="hidden" value="<?php echo isset($method_response_Info['ApiMethodResponse']['user_id'])?$method_response_Info['ApiMethodResponse']['user_id']:'0'; ?>" name="data[ApiMethodResponse][user_id]" >
				 <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">名称</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
						     		<input type="text" value="<?php echo $method_response_Info['ApiMethodResponse']['name'] ?>" class="am-form-field" name="data[ApiMethodResponse][name]" onkeydown="if(event.keyCode==13){return false;}">
						      </div><span class="red">* </span>
				  </div>
				  					  
				  <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:2px;">类型</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        	<select id="select_1" name="data[ApiMethodResponse][type]" >
										<option value=""> 请选择</option>
					    					<option value="Number" <?php echo  isset($method_response_Info['ApiMethodResponse']['type'] ) && $method_response_Info['ApiMethodResponse']['type']=='Number' ?selected:''; ?> > Number </option> 
										<option value="String" <?php echo isset($method_response_Info['ApiMethodResponse']['type']) && $method_response_Info['ApiMethodResponse']['type']=='String'?selected:''; ?>  >String</option>
										<option value="Boolean" <?php echo isset($method_response_Info['ApiMethodResponse']['type']) && $method_response_Info['ApiMethodResponse']['type']=='Boolean'?selected:''; ?> >Boolean</option>
				    					<?php foreach($object_info as $k=>$v){ ?>
				    					<option value="<?php echo $v['ApiObject']['code'];  ?>" <?php echo isset($method_response_Info['ApiMethodResponse']['type']) && $method_response_Info['ApiMethodResponse']['type']==$v['ApiObject']['code'] ?selected:''; ?> ><?php echo $v['ApiObject']['code']; ?></option>
				    					<?php } ?>
						      	</select>
						      </div><span class="red">* </span>
				    </div>
				    
    					<div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" > 示例值</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        	
						      	<input type="text"  value="<?php echo $method_response_Info['ApiMethodResponse']['samples'] ?>" class="am-form-field" name="data[ApiMethodResponse][samples]" onkeydown="if(event.keyCode==13){return false;}">
						      </div><span class="red">* </span>
				    </div>
    							
				    <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" >描述</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        	<textarea  name="data[ApiMethodResponse][description]" class="am-form-field" ><?php echo $method_response_Info['ApiMethodResponse']['description']; ?></textarea>

						      </div>
				    </div>
				    <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" >排序</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						      	<input type="text"  value="<?php echo isset($method_response_Info['ApiMethodResponse']['customer_case_id']) && !empty($method_response_Info['ApiMethodResponse']['customer_case_id'])?$method_response_Info['ApiMethodResponse']['customer_case_id']:'50';    ?>" class="am-form-field" name="data[ApiMethodResponse][customer_case_id]" onkeydown="if(event.keyCode==13){return false;}">

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
	var id=document.getElementsByName("data[ApiMethodResponse][id]")[0].value;
	var api_project_code=document.getElementsByName("data[ApiMethodResponse][api_project_code]")[0].value;
	var api_category_code=document.getElementsByName("data[ApiMethodResponse][api_category_code]")[0].value;
	var api_method_code=document.getElementsByName("data[ApiMethodResponse][api_method_code]")[0].value;
	var names=document.getElementsByName("data[ApiMethodResponse][name]")[0].value;
	var name=names.replace( /^\s*/, '');

	var sampless=document.getElementsByName("data[ApiMethodResponse][samples]")[0].value;
		var samples=sampless.replace( /^\s*/, '');

	//var types=$('input[name="data[ApiMethodResponse][type]"]:checked').val();
	var types=document.getElementsByName("data[ApiMethodResponse][type]")[0].value;
		var type=types.replace( /^\s*/, '');
	var customer_case_ids=document.getElementsByName("data[ApiMethodResponse][customer_case_id]")[0].value;
		var customer_case_id=customer_case_ids.replace( /^\s*/, '');

	var descriptions=document.getElementsByName("data[ApiMethodResponse][description]")[0].value;
	var description=descriptions.replace( /^\s*/, '');
	 if(api_project_code=="" && api_category_code=="" && api_method_code==""){
		alert("致命错误，没有项目代码和对象代码传进来");
		return false;
	}else if(name == ""){
		alert("请填写名称");return false;
	}else if(type == ""){
		alert("请选择类型");return false;
	}else if(samples == ""){
		alert("请填写示例值");return false;	

	}else{
		$.ajax({ url: admin_webroot+"api_methods/api_method_response/0/save",
			type:"POST",
			data:{
					'data[ApiMethodResponse][id]':id,
					'data[ApiMethodResponse][api_project_code]':api_project_code,		
					'data[ApiMethodResponse][api_category_code]':api_category_code,
					'data[ApiMethodResponse][api_method_code]':api_method_code,
					'data[ApiMethodResponse][name]':name,
				
					'data[ApiMethodResponse][samples]':samples,
					'data[ApiMethodResponse][type]':type,
					'data[ApiMethodResponse][description]':description,
					'data[ApiMethodResponse][customer_case_id]':customer_case_id,
			
				},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadmethodresponse(user_id);
					$("#method_response_popup").modal('close');
				}else{
					alert("<?php echo $ld['operation_success'] ?>");
				}
	  		}
	  	});
	  	return false;
  	}
}

function clearaddr(){
	$("#method_response_ajaxdata").parent().find('#methodresponseoperation').remove();
	$("#method_response_popup").modal('close');
}
</script>
	
	
	<?php } ?>