<style type="text/css">
	.am-form-group{margin:20px 0;} 
	.am-radio-inline{ float:left;}
	.am-modal-hd{
border-bottom:1px solid #ddd;
padding-bottom:5px;
}
	.red{
color:red;
margin-top:10px;
float:left;
}
</style>


<?php if($type=="list"){ ?>
		<?php	foreach ($_GET as $v){$str=$v;} $arr=explode('/',$str); ?>
		<input type="hidden" name="data[main_id]" value="<?php echo $arr[3] ; ?>" />
		<input type="hidden" name="data[project_name]" value="<?php echo $project_info['ApiProject']['name'] ; ?>" />
				<input type="hidden" name="data[method_name]" value="<?php echo $method_info['ApiMethod']['name'] ; ?>" />
<?php echo $project_info['ApiProject']['name'] ; ?>
		<div class="am-panel-group am-panel-tree" id="method_request_ajaxdata">
	    		<div class="am-g am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
				<ul class=" am-bt" style="list-style-type:none;">							
					<li style="margin:0 10px 0 0; float:right;">
						<button type="button" class="am-btn am-btn-warning am-btn-sm am-radius"  onclick="edit_method_request('','<?php echo $project_info['ApiProject']['name'] ; ?>','<?php echo $method_info['ApiMethod']['name'] ; ?>')">
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
								<input type="checkbox" data-am-ucheck onclick='listTable.selectAll(this,"checkbox[]")'/>名称
							</label>  
						</div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
								<?php echo $ld["type"]?>
						</div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
									是否必须
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
										默认值
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
										描述
						</div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
							排序
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
								<?php echo $ld['operate']?>
						</div>
						
				   </div>
			   </div>
	            </div>
										
		<?php if(isset($method_request_infos) && !empty($method_request_infos)) {?>											
	    		<?php foreach( $method_request_infos as $v){ ?>
	           <div >
				<div class=" am-panel-body">
					<div class="am-panel-bd am-g">
					     <div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
							<label class="am-checkbox am-success  " style="padding-top:0">
								<input type="checkbox" name="checkboxs[]" data-am-ucheck  value="<?php echo $v['ApiMethodRequest']['id']?>" />
								<div style="word-wrap:break-word;word-break:normal;" onclick='listTable.selectAll(this,"checkboxs[]")'>
									<?php echo $v['ApiMethodRequest']['name']?>
								</div>
							</label>
						</div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
									<span ><?php switch( $v['ApiMethodRequest']['type']){case 'String': echo 'String';break; case 'Number':echo 'Number';break; case 'Boolean':echo 'Boolean';break;} ?>   </span>
						</div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
								<span ><?php if($v['ApiMethodRequest']['required']=='1'){echo '是';}else{echo '否';} ?></span>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
						 		<div style="word-wrap:break-word;word-break:normal;"><?php echo $v['ApiMethodRequest']['defualt']?></div>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"  >
								<div style="word-wrap:break-word;word-break:normal;"><?php if( empty($v['ApiMethodRequest']['description'])){echo "&nbsp;";}else{ echo $v['ApiMethodRequest']['description']; }?></div>
						</div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"  >
								<div style="word-wrap:break-word;word-break:normal;"><?php if( empty($v['ApiMethodRequest']['orderby'])){echo "&nbsp;";}else{ echo $v['ApiMethodRequest']['orderby']; }?></div>
						</div>
						<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 seolink am-btn-group-xs am-action">							
							 <button type="button" class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_method_request('<?php echo $v['ApiMethodRequest']['id']  ?>','<?php echo $project_info['ApiProject']['name'] ; ?>','<?php echo $method_info['ApiMethod']['name'] ; ?>')">
								        <span class="am-icon-pencil-square-o"> <?php echo $ld['edit']; ?></span>
							 </button>                
							<button type="button" class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" onclick="del_method_request('<?php echo $v['ApiMethodRequest']['id'] ?>')" >
						                    	<span class="am-icon-trash-o"> <?php echo $ld['delete']; ?></span>
						      </button>
						                      							
						</div>
				</div>
			</div>
		</div>
	    	<?php }}else{  ?>
		<div>
			<div  class="no_data_found"><?php echo $ld['no_data_found']?></div>
		</div>																
		<?php } ?>
</div>
	
<script type="text/javascript">
function loadmethodrequest(user_id){
	$.ajax({ url: admin_webroot+"api_methods/api_method_request/"+user_id,
		type:"POST",
		dataType:"html",
		success: function(data){
			$("#method_request_ajaxdata").parent().html(data);
  		}
  	});
}	  			
	  			
	  			
function edit_method_request(Id,val1,val2){
	$("#method_request_btn").click();

	var main_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
		if(Id=='' || Id=='0'){ var str='添加'}else{ var str='编辑'}
	$.ajax({ url: admin_webroot+"api_methods/api_method_request/"+main_id+"/edit",
		type:"POST",
		data:{'Id':Id},
		dataType:"html",
		success: function(data){
			$("#method_request_popup .am-modal-bd").find('#methodrequestoperation').remove();
			$(" #modal_title_request").html(val1+'/'+val2+'/请求参数管理——'+str);
			$("#method_request_popup .am-modal-bd").html(data);
  		}
  	});
}

function del_method_request(Id){
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		var user_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
		$.ajax({ url: admin_webroot+"api_methods/api_method_request/"+user_id+"/del",
			type:"POST",
			data:{'Id':Id},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadmethodrequest(user_id);
				}else{
					alert("<?php echo $ld['delete_failure'] ?>");
				}
			}
		});
	}
}

</script>
	
<?php }else{ ?>
		<div style="margin-top:10px;"  id="methodrequestoperation" >

    			 <?php echo $form->create('',array('action'=>'','name'=>'method_request_info'));?>
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
		      			 <input type="hidden" value="<?php echo isset($method_request_info['ApiMethodRequest']['api_project_code'])?$method_request_info['ApiMethodRequest']['api_project_code']:$project_code ?>" name="data[ApiMethodRequest][api_project_code]" >
		      			<input type="hidden" value="<?php echo isset($method_request_info['ApiMethodRequest']['api_category_code'])?$method_request_info['ApiMethodRequest']['api_category_code']:$category_code ?>" name="data[ApiMethodRequest][api_category_code]" >
					    <input type="hidden" value="<?php echo isset($method_request_info['ApiMethodRequest']['api_method_code'])?$method_request_info['ApiMethodRequest']['api_method_code']:$method_code ?>" name="data[ApiMethodRequest][api_method_code]" >
					    <input type="hidden" value="<?php echo isset($method_request_info['ApiMethodRequest']['id'])?$method_request_info['ApiMethodRequest']['id']:'0'; ?>" name="data[ApiMethodRequest][id]" >
        <input type="hidden" value="<?php echo isset($method_request_info['ApiMethodRequest']['user_id'])?$method_request_info['ApiMethodRequest']['user_id']:'0'; ?>" name="data[ApiMethodRequest][user_id]" >
						 <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">名称</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
						     		<input type="text"  value="<?php echo $method_request_info['ApiMethodRequest']['name'] ?>" class="am-form-field" name="data[ApiMethodRequest][name]" onkeydown="if(event.keyCode==13){return false;}">
						      </div><span class="red">* </span>
				  </div>
				  					  
				  <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:2px;" >类型</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        
						
						        
					    					<label class="am-radio-inline am-success" style="padding-top:2px;">
					    					<input type="radio" class="radio"  data-am-ucheck value="Number" name="data[ApiMethodRequest][type]" <?php echo  isset( $method_request_info['ApiMethodRequest']['type'] ) && $method_request_info['ApiMethodRequest']['type']=='Number' ?'checked': ''; ?>   /> Number </label> 
										<label class="am-radio-inline am-success" style="padding-top:2px;">
										<input type="radio" class="radio"  data-am-ucheck name="data[ApiMethodRequest][type]" value="String" <?php echo  (isset( $method_request_info['ApiMethodRequest']['type'] ) && $method_request_info['ApiMethodRequest']['type']=='String')||!isset($method_request_info['ApiMethodRequest']['type']) ?'checked' : '';?> />String</label>
										<label class="am-radio-inline am-success" style="padding-top:2px;">
										<input type="radio" class="radio"  data-am-ucheck name="data[ApiMethodRequest][type]" value="Boolean"  <?php echo  isset( $method_request_info['ApiMethodRequest']['type'] ) && $method_request_info['ApiMethodRequest']['type']=='Boolean' ?'checked' : ''; ?>/>Boolean</label>
					    				
									
						      	
						      </div>
				    </div>
				    	<div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:2px;">是否必须</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        	
					    					<label class="am-radio-inline am-success" style="padding-top:2px;">
					    					<input type="radio" class="radio"  data-am-ucheck value="0" name="data[ApiMethodRequest][required]" <?php echo  (isset( $method_request_info['ApiMethodRequest']['required'] ) && $method_request_info['ApiMethodRequest']['required']=='0')||!isset($method_request_info['ApiMethodRequest']['required']) ?'checked' : ''   ; ?>/> 否 </label> 
										<label class="am-radio-inline am-success" style="padding-top:2px;">
										<input type="radio" class="radio"  data-am-ucheck name="data[ApiMethodRequest][required]" value="1" <?php echo  isset( $method_request_info['ApiMethodRequest']['required'] ) && $method_request_info['ApiMethodRequest']['required']=='1' ?'checked': '' ;?> />是</label>
			
						      </div>
				    </div>	
    					<div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" > 默认值</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        	
						      	<input type="text"  value="<?php echo $method_request_info['ApiMethodRequest']['defualt'] ?>" class="am-form-field" name="data[ApiMethodRequest][defualt]" onkeydown="if(event.keyCode==13){return false;}">
						      </div><span class="red">* </span>
				    </div>
    							
				    <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" >描述</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
					
						        	<textarea   name="data[ApiMethodRequest][description]" class="am-form-field"   ><?php echo $method_request_info['ApiMethodRequest']['description']; ?></textarea>
						      </div>
				    </div>
				    <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" >排序</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						      	<input type="text"  value="<?php echo isset($method_request_info['ApiMethodRequest']['orderby']) && !empty($method_request_info['ApiMethodRequest']['orderby'])?$method_request_info['ApiMethodRequest']['orderby']:'50'; ?>" class="am-form-field" name="data[ApiMethodRequest][orderby]" onkeydown="if(event.keyCode==13){return false;}">
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
	var id=document.getElementsByName("data[ApiMethodRequest][id]")[0].value;
	var api_project_code=document.getElementsByName("data[ApiMethodRequest][api_project_code]")[0].value;
	var api_category_code=document.getElementsByName("data[ApiMethodRequest][api_category_code]")[0].value;
	var api_method_code=document.getElementsByName("data[ApiMethodRequest][api_method_code]")[0].value;
	var names=document.getElementsByName("data[ApiMethodRequest][name]")[0].value;
	var name=names.replace( /^\s*/, '');

	var requireds=$('input[name="data[ApiMethodRequest][required]"]:checked').val();
		var required=requireds.replace( /^\s*/, '');

	var defualts=document.getElementsByName("data[ApiMethodRequest][defualt]")[0].value;
		var defualt=defualts.replace( /^\s*/, '');
	var orderbys=document.getElementsByName("data[ApiMethodRequest][orderby]")[0].value;
		var orderby=orderbys.replace( /^\s*/, '');

var type=$('input[name="data[ApiMethodRequest][type]"]:checked').val();
	//alert(type);
	var descriptions=document.getElementsByName("data[ApiMethodRequest][description]")[0].value;
		var description=descriptions.replace( /^\s*/, '');

	 if(api_project_code=="" && api_category_code=="" && api_method_code==""){
		alert("致命错误，没有项目代码和对象代码传进来");
		return false;
	}else if(name == ""){
		alert("请填写名称");return false;
	}else if(type == ""){
		alert("请选择类型");return false;
	}else if(required == ""){
		alert("请选择是否必须");return false;			
	}else if(defualt == ""){
		alert("请填写默认值");return false;

	}else{
		$.ajax({ url: admin_webroot+"api_methods/api_method_request/0/save",
			type:"POST",
			data:{
					'data[ApiMethodRequest][id]':id,
					'data[ApiMethodRequest][api_project_code]':api_project_code,		
					'data[ApiMethodRequest][api_category_code]':api_category_code,
					'data[ApiMethodRequest][api_method_code]':api_method_code,
					'data[ApiMethodRequest][name]':name,
					'data[ApiMethodRequest][required]':required,
					'data[ApiMethodRequest][defualt]':defualt,
					'data[ApiMethodRequest][type]':type,
					'data[ApiMethodRequest][description]':description,
					'data[ApiMethodRequest][orderby]':orderby,
			
				},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadmethodrequest(user_id);
					$("#method_request_popup").modal('close');
				}else{
					alert("<?php echo $ld['operation_success'] ?>");
				}
	  		}
	  	});
	  	return false;
  	}
}

function clearaddr(){
	$("#method_request_ajaxdata").parent().find('#methodrequestoperation').remove();
	$("#method_request_popup").modal('close');
}
</script>
	
	
	<?php } ?>