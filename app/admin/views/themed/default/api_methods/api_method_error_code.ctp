<style type="text/css">
	.am-form-group{margin:20px 0;} 
	.am-radio-inline{ float:left;}
	.am-selected-list li{
	max-width:350px;
	}
	.am-active .am-btn-default.am-dropdown-toggle, .am-btn-default.am-active, .am-btn-default:active{
		background-color:white;
	}
		.red{
color:red;
margin-top:14px;
float:left;
}
</style>
<script type="text/javascript">
	$(function() {
  // 使用默认参数

  // 设置参数
  $('select').selected({

    btnSize: 'sm',
    btnStyle: 'default',
    
  });
});

</script>
<?php if($type=="list"){ ?>

	<?php	foreach ($_GET as $v){$str=$v;} $arr=explode('/',$str); //pr($arr) ?>
			<input type="hidden" name="data[main_id]" value="<?php echo $arr[3] ; ?>" />
				<?php //pr($arr[3]) ; ?>
	<div class="am-panel-group am-panel-tree" id="method_error_code_ajaxdata">
	    			<div class="am-g am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
					<ul class=" am-bt" style="list-style-type:none;">							
							<li style="margin:0 10px 0 0; float:right;">
									
										  <button type="button" class="am-btn am-btn-warning am-btn-sm am-radius"  onclick="edit_method_error_code('','<?php echo $project_info['ApiProject']['name'] ; ?>','<?php echo $method_info['ApiMethod']['name'] ; ?>')">
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
																错误代码
													</label>  
												</div>
												<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">描述</div>
												<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">解决方案</div>
												<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['operate']?></div>
											</div>
							</div>
				</div>
										
		<?php if(isset($method_error_code_infos) && !empty($method_error_code_infos)) {?>											
	    		<?php foreach( $method_error_code_infos as $v){ 	  	
?>
	    			<?php if(isset($error_code_interpretation_infos) && !empty($error_code_interpretation_infos)) {?>											
	 				<?php foreach( $error_code_interpretation_infos as $vv){ 
	 							//	pr($v['ApiMethodErrorCode']['error_code'].'  next');
		 						//	pr($vv['ApiErrorCodeInterpretation']['code']);
			 					if($vv['ApiErrorCodeInterpretation']['code']==  $v['ApiMethodErrorCode']['error_code'] ){   ?>
	 					<div >
				
					<div class=" am-panel-body">
							<div class="am-panel-bd am-g">
											<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
									    				<label class="am-checkbox am-success  " style="padding-top:0">
													<input type="checkbox" name="checkboxs[]" data-am-ucheck  value="<?php echo $v['ApiMethodErrorCode']['id']?>" />
													<div style="word-wrap:break-word;word-break:normal;" onclick='listTable.selectAll(this,"checkboxs[]")'><?php   echo $vv['ApiErrorCodeInterpretation']['code']   ?></div>
													</label>
											</div>
				
											<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
													<div style="word-wrap:break-word;word-break:normal;" ><?php echo empty($vv['ApiErrorCodeInterpretation']['description'])?'&nbsp;':$vv['ApiErrorCodeInterpretation']['description'];    ; ?></div>
											</div>
											<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
													<div style="word-wrap:break-word;word-break:normal;"><?php echo $vv['ApiErrorCodeInterpretation']['solution'] ; ?></div>
											</div>		

											<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 seolink am-btn-group-xs am-action">							
												 <button type="button" class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_method_error_code('<?php echo $v['ApiMethodErrorCode']['id']  ?>','<?php echo $project_info['ApiProject']['name'] ; ?>','<?php echo $method_info['ApiMethod']['name'] ; ?>')" >
								                       	 <span class="am-icon-pencil-square-o"> <?php echo $ld['edit']; ?></span>
								                      	</button>                
												<button type="button" class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" 
												  onclick="del_method_error_code('<?php echo $v['ApiMethodErrorCode']['id'] ?>')" >
						                        			<span class="am-icon-trash-o"> <?php echo $ld['delete']; ?></span>
						                      			</button>				
											</div>
							</div>
						</div>
					</div>
					<?php } }} ?>

	    			<?php }}else{  ?>
						<div>
							<div  class="no_data_found"><?php echo $ld['no_data_found']?></div>
<!--2-->				</div>						
												
		<?php } ?>
</div>
	
	  <script type="text/javascript">
function loadmethoderrorcode(user_id){
	$.ajax({ url: admin_webroot+"api_methods/api_method_error_code/"+user_id,
		type:"POST",
		dataType:"html",
		success: function(data){
			$("#method_error_code_ajaxdata").parent().html(data);
	
  		}
  	});
}	  			
	  			
	  			
	  function edit_method_error_code(Id,val1,val2){
	  	  $("#method_error_code_btn").click();
	  	  	if(Id=='' || Id=='0'){ var str='添加'}else{ var str='编辑'}
	var main_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
	$.ajax({ url: admin_webroot+"api_methods/api_method_error_code/"+main_id+"/edit",
		type:"POST",
		data:{'Id':Id},
		dataType:"html",
		success: function(data){

			$("#method_error_code_popup .am-modal-bd").find('#methoderrorcodeoperation').remove();
				$(" #modal_title_error_code").html(val1+'/'+val2+'/错误代码管理——'+str);
				$("#method_error_code_popup .am-modal-bd").html(data);
			
  		}
  	});
}

function del_method_error_code(Id){
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		var user_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
		$.ajax({ url: admin_webroot+"api_methods/api_method_error_code/"+user_id+"/del",
			type:"POST",
			data:{'Id':Id},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadmethoderrorcode(user_id);
				}else{
					alert("<?php echo $ld['delete_failure'] ?>");
				}
			}
		});
	}
}



</script>
	
<?php }else{ ?>

		<div style="margin-top:10px;"  id="methoderrorcodeoperation">
				
    			 <?php echo $form->create('',array('action'=>'','name'=>'api_method_error_code'));?>
    			 	 	<?php	foreach ($_GET as $v){$str=$v;} $arr=explode('/',$str); //pr($arr) ?>
    			 	  <?php 	if($arr[3]==$method_info['ApiMethod']['id'])
    			 	 			{
    			 	 				 $project_code=$method_info['ApiMethod']['api_project_code'];
    			 	 				 $method_code=$method_info['ApiMethod']['code'];
    			 				 } 
    			 	 ?>	 	 
			<input type="hidden" name="data[main][id]" value="<?php echo $arr[3] ; ?>" />
      		<div class="am-tab-panel am-fade am-active am-in am-form-detail am-form am-form-horizontal" >
		      			 <input type="hidden" value="<?php echo isset($method_error_code_info['ApiMethodErrorCode']['api_project_code'])?$method_error_code_info['ApiMethodErrorCode']['api_project_code']:$project_code ?>" name="data[ApiMethodErrorCode][api_project_code]" >

					    <input type="hidden" value="<?php echo isset($method_error_code_info['ApiMethodErrorCode']['api_method_code'])?$method_error_code_info['ApiMethodErrorCode']['api_method_code']:$method_code ?>" name="data[ApiMethodErrorCode][api_method_code]" >
					    <input type="hidden" value="<?php echo isset($method_error_code_info['ApiMethodErrorCode']['id'])?$method_error_code_info['ApiMethodErrorCode']['id']:'0'; ?>" name="data[ApiMethodErrorCode][id]" >
        <input type="hidden" value="<?php echo isset($method_error_code_info['ApiMethodErrorCode']['user_id'])?$method_error_code_info['ApiMethodErrorCode']['user_id']:'0'; ?>" name="data[ApiMethodErrorCode][user_id]" >		  
				  <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:5px">错误代码</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
				<select name="data[ApiMethodErrorCode][error_code]" data-am-selected={"searchBox: 1"} >
							<option value="">请选择 </option>
				<?php if(isset($error_code_interpretation_infos) && !empty($error_code_interpretation_infos)) {?>											
	    				<?php foreach( $error_code_interpretation_infos as $v){ ?>

									<option  value="<?php echo $v['ApiErrorCodeInterpretation']['code'];  ?>" <?php echo isset($method_error_code_info['ApiMethodErrorCode']['error_code']) &&  ($method_error_code_info['ApiMethodErrorCode']['error_code']==$v['ApiErrorCodeInterpretation']['code'] )?selected:''; ?> ><?php echo  $v['ApiErrorCodeInterpretation']['code'] ?> </option>
					  	<?php  }}  ?>
				</select>

						      </div><span class="red" style="margin-top:5px; ">* </span>
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
	var id=document.getElementsByName("data[ApiMethodErrorCode][id]")[0].value;
	var api_project_code=document.getElementsByName("data[ApiMethodErrorCode][api_project_code]")[0].value;

	var api_method_code=document.getElementsByName("data[ApiMethodErrorCode][api_method_code]")[0].value;


	var error_code=document.getElementsByName("data[ApiMethodErrorCode][error_code]")[0].value;
	//var error_code=$('input[name="data[ApiMethodErrorCode][error_code]"]').val();

	 if(api_project_code==""  && api_method_code==""){
		alert("致命错误，没有项目代码和对象代码传进来");
		return false;
	}else if(error_code == ""){
		alert("请选择错误代码");return false;
		

	}else{
		$.ajax({ url: admin_webroot+"api_methods/api_method_error_code/0/save",
			type:"POST",
			data:{
					'data[ApiMethodErrorCode][id]':id,
					'data[ApiMethodErrorCode][api_project_code]':api_project_code,		
					'data[ApiMethodErrorCode][error_code]':error_code,
					'data[ApiMethodErrorCode][api_method_code]':api_method_code,
		
			
				},
			dataType:"json",
			success: function(data){
				if(data.code==1){
			

					loadmethoderrorcode(user_id);
		$("#method_error_code_popup").modal('close');
				}else{
					alert("错误示例重复");
				}
	  		}
	  	});
	  	return false;
  	}
}

function clearaddr(){
	$("#method_error_code_ajaxdata").parent().find('#methoderrorcodeoperation').remove();
	$("#method_error_code_popup").modal('close');
}
</script>
	
	
	<?php } ?>