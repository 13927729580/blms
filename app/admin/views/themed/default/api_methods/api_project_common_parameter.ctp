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
	    		<div class="am-panel-group am-panel-tree" id="project_common_parameter_ajaxdata" style="padding-top:10px">
	    				<div class="am-g am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
					<ul class=" am-bt" style="list-style-type:none;">
						<li style="margin-left:-10px; float:left;">									
										  <button type="button" class="am-btn am-btn-warning am-btn-sm am-radius"  onclick="ajaxloadapipro()">
 											 <span class="am-icon-reply"></span> 返回
										</button>
							
							</li>							
							<li style="margin:0 10px 0 0; float:right;">									
										  <button type="button" class="am-btn am-btn-warning am-btn-sm am-radius" data-am-modal="{target: '#addrresp', closeViaDimmer: 1, width: 900}" onclick="edit_project_common_parameter('','<?php echo $project_info['ApiProject']['name'] ?>');">
 											 <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
										</button>
							
							</li>
					</ul>
				</div>			
				<div class="  listtable_div_btm am-panel-header">
							<div class="am-panel-hd">
											<div class="am-panel-title am-g">
												<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
													<label class="am-checkbox am-success  " style="font-weight:bold;padding-top:0">
														<input type="checkbox" data-am-ucheck onclick='listTable.selectAll(this,"checkbox[]")'/>
																名称
													</label>  
												</div>
												 <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">类型</div>
												 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">是否必须</div>
												<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['operate']?></div>
											</div>
							</div>
				</div>
<div style="height:200px;overflow-y:auto">

	<?php  if(isset($project_common_parameter_infos) && !empty($project_common_parameter_infos)) { ?>
												 
												 											
	    		<?php      foreach( $project_common_parameter_infos as $v){ ?>
		<div >
					<div class="listtable_div_top am-panel-body">
						<div class="am-panel-bd am-g">
											<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
									    							<label class="am-checkbox am-success  " style="padding-top:0">
													<input type="checkbox" name="checkboxs[]" data-am-ucheck  value="<?php echo $v['ApiProjectCommonParameter']['id']?>" />
													<div style="word-wrap:break-word;word-break:normal;" onclick='listTable.selectAll(this,"checkboxs[]")'><?php   echo $v['ApiProjectCommonParameter']['name']   ?></div>
												</label>
											</div>
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
													<span ><?php switch( $v['ApiProjectCommonParameter']['type'] ){case '0': echo 'String';break; case '1': echo 'Boolean';break; case '2': echo '其他';break;} ?></span>
											</div>
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
													<span ><?php switch( $v['ApiProjectCommonParameter']['required']){case '0': echo '否';break;case '1': echo "是" ; break;}  ?></span>
											</div>
						
												
											<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 seolink am-btn-group-xs am-action">							
												 <button type="button" class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_project_common_parameter('<?php echo $v['ApiProjectCommonParameter']['id']  ?>','<?php echo $project_info['ApiProject']['name'] ?>');">
								                        <span class="am-icon-pencil-square-o"> <?php echo $ld['edit']; ?></span>
								                      </button>                
												<button type="button" class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" 
												  onclick="del_project_common_parameter('<?php echo $v['ApiProjectCommonParameter']['id'] ?>');" >
						                        			<span class="am-icon-trash-o"> <?php echo $ld['delete']; ?></span>
						                      			</button>				
											</div>
							</div>
						</div>
					</div>
	    		<?php } }else{  ?>
						<div>
							<div  class="no_data_found"><?php  echo $ld['no_data_found']?></div>
						</div>														
				<?php } ?>
</div>
	    	</div>
	  <script type="text/javascript">
	  			
	   				  	function ajaxloadapipro(){			
	  			
	  	$.ajax({ url: admin_webroot+"api_methods/api_project/",			
	  		type:"GET",			
	  		dataType:"html",			
	  		success: function(data){			
	  			$("#api_pro_show").html(data);			
	    		}			
	    	});			
	  }			
	  			

	  			
function loadprojectcommonparameter(user_id,val1){
	$.ajax({ url: admin_webroot+"api_methods/api_project_common_parameter/"+user_id,
		type:"POST",
		dataType:"html",
		success: function(data){
			$("#modal-title").html(val1+"——公共参数管理");

			$("#api_pro_show").html(data);
  		}
  	});
}	  			
	  			
	  			
	  function edit_project_common_parameter(Id,val1){
	  	  	  	  if(Id=='' || Id=='0'){var str='添加';}else{ var str='编辑'}

	var main_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
	$.ajax({ url: admin_webroot+"api_methods/api_project_common_parameter/"+main_id+"/edit",
		type:"POST",
		data:{'Id':Id},
		dataType:"html",
		success: function(data){
					$("#modal-title").html(val1+'/公共参数管理——'+str);

			$("#api_pro_show").html(data);
  		}
  	});
}

function del_project_common_parameter(Id){
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		var user_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
		$.ajax({ url: admin_webroot+"api_methods/api_project_common_parameter/"+user_id+"/del",
			type:"POST",
			data:{'Id':Id},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadprojectcommonparameter(user_id);
				}else{
					alert("<?php echo $ld['delete_failure'] ?>");
				}
			}
		});
	}
}
</script>
<?php }else{ ?>
	<div style="margin-top:10px;"  id="projectcommonparameter">
				
    			 <?php echo $form->create('',array('action'=>'','id'=>'api_error_code_interpretation_form','name'=>'api_object_view'));?>
    			 	 	<?php	foreach ($_GET as $v){$str=$v;} $arr=explode('/',$str); //pr($arr) ?>
    			 	 <?php if( $arr[3]==$project_info['ApiProject']['id']){
    			 	    
    			 				$project_code=$project_info['ApiProject']['code']; 
    			 		}
    			 	?>	 
			<input type="hidden" name="data[main][id]" value="<?php echo $arr[3] ; ?>" />
						    			 	 	<input type="hidden" name="data[project_name]" value="<?php echo $project_info['ApiProject']['name'] ; ?>" />

      		<div class="am-tab-panel am-fade am-active am-in am-form-detail am-form am-form-horizontal" >
		      			 <input type="hidden" value="<?php echo isset($project_common_parameter_info['ApiProjectCommonParameter']['api_project_code'])?$project_common_parameter_info['ApiProjectCommonParameter']['api_project_code']:$project_code ?>" name="data[ApiProjectCommonParameter][api_project_code]" >
					    <input type="hidden" value="<?php echo isset($project_common_parameter_info['ApiProjectCommonParameter']['id'])?$project_common_parameter_info['ApiProjectCommonParameter']['id']:'0'; ?>" name="data[ApiProjectCommonParameter][id]" >
        <input type="hidden" value="<?php echo isset($project_common_parameter_info['ApiProjectCommonParameter']['user_id'])?$project_common_parameter_info['ApiProjectCommonParameter']['user_id']:'0'; ?>" name="data[ApiProjectCommonParameter][user_id]" >
				 <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">名称</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input type="text" value="<?php echo $project_common_parameter_info['ApiProjectCommonParameter']['name'] ?>" class="am-form-field" name="data[ApiProjectCommonParameter][name]" onkeydown="if(event.keyCode==13){return false;}">
						      </div><span class="red">* </span>
				  </div>
				  <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:2px;" >类型</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        
					    					<label class="am-radio-inline am-success" style="padding-top:2px;">
					    					<input type="radio" class="radio"  data-am-ucheck value="1" name="data[ApiProjectCommonParameter][type]" <?php echo isset($project_common_parameter_info['ApiProjectCommonParameter']['type']) && $project_common_parameter_info['ApiProjectCommonParameter']['type']=='1'?'checked':'';   ?> /> Boolean </label> 
										<label class="am-radio-inline am-success" style="padding-top:2px;">
										<input type="radio" class="radio"  data-am-ucheck name="data[ApiProjectCommonParameter][type]" value="0" <?php echo (isset($project_common_parameter_info['ApiProjectCommonParameter']['type']) && $project_common_parameter_info['ApiProjectCommonParameter']['type']=='0')||!isset($project_common_parameter_info['ApiProjectCommonParameter']['type'])?'checked':'';   ?> />String</label>
										<label class="am-radio-inline am-success" style="padding-top:2px;">
										<input type="radio" class="radio"  data-am-ucheck name="data[ApiProjectCommonParameter][type]" value="2"  <?php echo isset($project_common_parameter_info['ApiProjectCommonParameter']['type']) && $project_common_parameter_info['ApiProjectCommonParameter']['type']=='2'?'checked':'';   ?>/>其他</label>
					    				
					    					
						    			
						      	
						      </div>
				    </div>
    				<div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:2px;">是否必须</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
					    					<label class="am-radio-inline am-success" style="padding-top:2px;">
					    					<input type="radio" class="radio"  data-am-ucheck value="0" name="data[ApiProjectCommonParameter][required]" <?php echo (isset($project_common_parameter_info['ApiProjectCommonParameter']['required']) &&  $project_common_parameter_info['ApiProjectCommonParameter']['required']=='0')||!isset($project_common_parameter_info['ApiProjectCommonParameter']['required'])?'checked':''; ?> /> 否 </label> 
										<label class="am-radio-inline am-success" style="padding-top:2px;">
										<input type="radio" class="radio"  data-am-ucheck name="data[ApiProjectCommonParameter][required]" value="1" <?php echo isset($project_common_parameter_info['ApiProjectCommonParameter']['required']) &&  $project_common_parameter_info['ApiProjectCommonParameter']['required']=='1'?'checked':''; ?>  />是</label>
								
						      	
						      </div>
				    </div>	
    							
				    <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" >描述</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        	<textarea name="data[ApiProjectCommonParameter][description]" class="am-form-field" ><?php echo $project_common_parameter_info['ApiProjectCommonParameter']['description']; ?></textarea>
						      	
						      </div>
				    </div>
				    
				    
    
					    				
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
					
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><button type="button"   class="am-btn am-btn-success am-btn-sm am-radius" onclick="saveajax()"  ><?php echo $ld['save'];?></button>
						<button type="button" class="am-btn am-btn-default am-btn-sm am-radius" value="" onclick="clearaddr()" ><?php echo $ld['cancel']?></button>　　　　　 </div>
				</div>
      		</div>			
      		<?php echo $form->end(); ?>						
   </div>
	
	<script type="text/javascript">
	function saveajax(){
	//	alert(document.getElementsByName("data[main][id]")[0].value);
	var user_id=document.getElementsByName("data[main][id]")?document.getElementsByName("data[main][id]")[0].value:0;
	var id=document.getElementsByName("data[ApiProjectCommonParameter][id]")[0].value;
	var api_project_code=document.getElementsByName("data[ApiProjectCommonParameter][api_project_code]")[0].value;
	var names=document.getElementsByName("data[ApiProjectCommonParameter][name]")[0].value;
		var name=names.replace( /^\s*/, '');
		var project_name=document.getElementsByName("data[project_name]")?document.getElementsByName("data[project_name]")[0].value:0;

	var type=$('input[name="data[ApiProjectCommonParameter][type]"]:checked').val();
	var descriptions=document.getElementsByName("data[ApiProjectCommonParameter][description]")[0].value;
		var description=descriptions.replace( /^\s*/, '');

	var required=$('input[name="data[ApiProjectCommonParameter][required]"]:checked').val();
	 if(api_project_code==""){
		alert("致命错误，没有项目代码传进来");
		return false;
		}else if(name==""){
			alert("请填写名称");
			return false;
	}else{
		$.ajax({ url: admin_webroot+"api_methods/api_project_common_parameter/0/save",
			type:"POST",
			data:{
					'data[ApiProjectCommonParameter][id]':id,
					
					'data[ApiProjectCommonParameter][api_project_code]':api_project_code,
					'data[ApiProjectCommonParameter][name]':name,
					'data[ApiProjectCommonParameter][type]':type,
					'data[ApiProjectCommonParameter][description]':description,
					'data[ApiProjectCommonParameter][required]':required,
				},
			dataType:"json",
			success: function(data){
				
				if(data.code==1){
					loadprojectcommonparameter(user_id,project_name);
				
				}else{
					alert("名称重复");
				}
	  		}
	  	});
	  	return false;
  	}
}

function clearaddr(){
var user_id=document.getElementsByName("data[main][id]")?document.getElementsByName("data[main][id]")[0].value:0;
		var project_name=document.getElementsByName("data[project_name]")?document.getElementsByName("data[project_name]")[0].value:0;

 loadprojectcommonparameter(user_id,project_name);
}
</script>
	<?php } ?>		