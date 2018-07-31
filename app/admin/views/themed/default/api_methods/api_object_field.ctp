<style type="text/css">
	.am-form-group{margin:20px 0;} 
	.am-radio-inline{ float:left;}
		.red{
color:red;
margin-top:10px;
float:left;
}
</style>

	<?php $url=$_GET['url'] ; ?>
		<?php	  $arr=explode('/',$url); //pr($arr) ?>
			<input type="hidden" name="data[main_id]" value="<?php echo $arr[3];   ?>" />
		
<?php if($type=="list"){ ?>
<input type="hidden" name="data[project_name]" value="<?php echo $project_info['ApiProject']['name']; ?>" />
			<?php $valid=empty($_GET['ids'])?$_POST['ids']:$_GET['ids']; // pr($valid);?>
			<input type="hidden" name="data[pro_id]" value="<?php echo $valid; ?>" />

	    		<div class="am-panel-group am-panel-tree" style="padding-top:10px">
	    				<div class="am-g am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
					<ul class=" am-bt" style="list-style-type:none;">	
							<li style="margin-left:-10px; float:left;">									
										  <button type="button" class="am-btn am-btn-warning am-btn-sm am-radius"  onclick="loadproloadobject('<?php echo $project_info['ApiProject']['name']   ?>')">
 											 <span class="am-icon-reply"></span> 返回
										</button>
							</li>						
							<li style="margin:0 10px 0 0; float:right;">									
										  <button type="button" class="am-btn am-btn-warning am-btn-sm am-radius" data-am-modal="{target: '#addrresp', closeViaDimmer: 1, width: 900,height:500}" onclick="edit_object_field('','<?php echo $project_info['ApiProject']['name'] ?>','<?php echo $object_info['ApiObject']['name'] ?>');">
 											 <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
										</button>
							</li>
					</ul>
				</div>			
				<div class="  listtable_div_btm am-panel-header">
							<div class="am-panel-hd">
											<div class="am-panel-title am-g">
												<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
													<label class="am-checkbox am-success  " style="font-weight:bold;padding-top:0">
														<input type="checkbox" data-am-ucheck onclick='listTable.selectAll(this,"checkbox[]")'/>
																名称
													</label>  
												</div>
												 <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-left: 0;">类型</div>
												 <div class="am-u-lg-3 am-u-md-3 am-u-sm-3">示例值</div>
												 <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-left: 0;">排序</div>
												<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['operate']?></div>
											</div>
							</div>
				</div>
<div style="height:200px;overflow-y:auto">
	<?php  if(isset($object_field_infos) && !empty($object_field_infos)) { ?>
												 
												 											
	    		<?php      foreach( $object_field_infos as $v){ ?>
		<div >
					<div class="listtable_div_top am-panel-body">
						<div class="am-panel-bd am-g">
											<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
									    							<label class="am-checkbox am-success  " >
													<input type="checkbox" name="checkboxs[]" data-am-ucheck  value="<?php echo $v['ApiObjectField']['id']?>" />
													<div style="word-wrap:break-word;word-break:normal;" onclick='listTable.selectAll(this,"checkboxs[]")'><?php   echo $v['ApiObjectField']['name']   ?></div>
												</label>
											</div>
										
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-left: 0;">
													<span ><?php switch( $v['ApiObjectField']['type']){case 'String' :echo 'String'; break; case 'Number': echo 'Number'; break; case 'Boolean':  echo 'Boolean';break; }  ?></span>
											</div>
											<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
													<div style="word-wrap:break-word;word-break:normal;" ><?php echo $v['ApiObjectField']['samples']  ?></div>
											</div>
												<div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-left: 0;">
													<div style="word-wrap:break-word;word-break:normal;" ><?php echo $v['ApiObjectField']['orderby']  ?></div>
											</div>	
											<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">						
												 <button type="button" class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_object_field('<?php echo $v['ApiObjectField']['id']  ?>','<?php echo $project_info['ApiProject']['name'] ?>','<?php echo $object_info['ApiObject']['name'] ?> ');">
								                        		<span class="am-icon-pencil-square-o"> <?php echo $ld['edit']; ?></span>
								                      	</button>                
												<button type="button" class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" 
												  onclick="del_object_field('<?php echo $v['ApiObjectField']['id'] ?>');" >
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
function loadobject(user_id,val1){

	$.ajax({ url: admin_webroot+"api_methods/api_object/"+user_id,
		type:"GET",
		dataType:"html",
		success: function(data){
			$("#modal-title").html(val1+"——对象管理");
			$("#api_pro_show").html(data);
  		}
  	});
}	  			
	  	
		function loadproloadobject(val1){
	  				var pro_id=document.getElementsByName("data[pro_id]")?document.getElementsByName("data[pro_id]")[0].value:0;
	  			loadobject(pro_id,val1);
	  			
	  			}
	  			//加载
function loadobjectfield(user_id,pro_id,val1,val2){

	$.ajax({ url: admin_webroot+"api_methods/api_object_field/"+user_id,
		type:"POST",
		data:{'ids':pro_id},
		dataType:"html",
		success: function(data){
				$("#modal-title").html(val1+'/'+val2+'——字段管理');
			$("#api_pro_show").html(data);
  		}
  	});
}	  			
	  			
	  			
	  function edit_object_field(Id,val1,val2){
	  	  	  	  if(Id=='' || Id=='0'){var str='添加';}else{ var str='编辑'}

	var pro_id=document.getElementsByName("data[pro_id]")?document.getElementsByName("data[pro_id]")[0].value:0;
	//alert(pro_id);
	var main_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
	$.ajax({ url: admin_webroot+"api_methods/api_object_field/"+main_id+"/edit",
		type:"POST",
		data:{'Id':Id,'ids':pro_id},
		dataType:"html",
		success: function(data){
						$("#modal-title").html(val1+'/'+val2+'/字段管理——'+str);

			$("#api_pro_show").html(data);
  		}
  	});
}

function del_object_field(Id){
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		var pro_id=document.getElementsByName("data[pro_id]")?document.getElementsByName("data[pro_id]")[0].value:0;

		var user_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
		$.ajax({ url: admin_webroot+"api_methods/api_object_field/"+user_id+"/del",
			type:"POST",
			data:{'Id':Id,'ids':pro_id},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadobjectfield(user_id,pro_id);
				}else{
					alert("<?php echo $ld['delete_failure'] ?>");
				}
			}
		});
	}
}



</script>
<?php }else{ ?>
	<div style="margin-top:10px;" >
				
    			 <?php echo $form->create('',array('action'=>'','id'=>'api_error_code_interpretation_form','name'=>'object_field_info_view'));?>
    			 	  			 	 <?php $ids=$_POST['ids'];  ?>
    			 	 				<?php  foreach($_GET as $v){$str=$v;}  $arr=explode('/',$str);   ?>
    			 	 	<input type="hidden" name="data[pro_id]" value="<?php echo $ids ; ?>" />
    			 	 	<input type="hidden" name="data[project_name]" value="<?php echo $project_info['ApiProject']['name'] ; ?>" />

    			 	 	<input type="hidden" name="data[object_name]" value="<?php echo $object_info['ApiObject']['name'] ; ?>" />

    			 	 <?php if( $arr[3]==$object_info['ApiObject']['id']){$object_code=$object_info['ApiObject']['code'];
    			 	    
    			 				$project_code=$object_info['ApiObject']['api_project_code']; 
    			 		}
    			 	?>	 
			<input type="hidden" name="data[main][id]" value="<?php echo $arr[3] ; ?>" />
      		<div class="am-tab-panel am-fade am-active am-in am-form-detail am-form am-form-horizontal" >
		      			 <input type="hidden" value="<?php echo isset($object_field_info['ApiObjectField']['api_project_code'])?$object_field_info['ApiObjectField']['api_project_code']:$project_code ?>" name="data[ApiObjectField][api_project_code]" >
		      			<input type="hidden" value="<?php echo isset($object_field_info['ApiObjectField']['api_object_code'])?$object_field_info['ApiObjectField']['api_object_code']:$object_code ?>" name="data[ApiObjectField][api_object_code]" >
					    <input type="hidden" value="<?php echo isset($object_field_info['ApiObjectField']['id'])?$object_field_info['ApiObjectField']['id']:'0'; ?>" name="data[ApiObjectField][id]" >
				 <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">名称</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
						     		<input type="text" value="<?php echo $object_field_info['ApiObjectField']['name'] ?>" class="am-form-field" name="data[ApiObjectField][name]" onkeydown="if(event.keyCode==13){return false;}">
						      </div><span class="red">* </span>
				  </div>
				  					  
				  <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:2px;">类型</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        	
					    					<label class="am-radio-inline am-success" style="padding-top:2px;">
					    					<input type="radio" class="radio"  data-am-ucheck  name="data[ApiObjectField][type]" value="Number" <?php  echo isset(  $object_field_info['ApiObjectField']['type']) &&  $object_field_info['ApiObjectField']['type']=='Number' ?'checked':'';  ?>    /> Number </label> 
										<label class="am-radio-inline am-success" style="padding-top:2px;">
										<input type="radio" class="radio"  data-am-ucheck name="data[ApiObjectField][type]" value="String" <?php  echo (isset(  $object_field_info['ApiObjectField']['type']) &&  $object_field_info['ApiObjectField']['type']=='String')||!isset($object_field_info['ApiObjectField']['type']) ?'checked':'';  ?>    />String</label>
										<label class="am-radio-inline am-success" style="padding-top:2px;">
										<input type="radio" class="radio"  data-am-ucheck name="data[ApiObjectField][type]" value="Boolean"  <?php  echo isset(  $object_field_info['ApiObjectField']['type']) &&  $object_field_info['ApiObjectField']['type']=='Boolean' ?'checked':'';  ?>   />Boolean</label>
					    				
					    					
						    			
						      	
						      </div>
				    </div>
    					<div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" > 示例值</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        	
						      	<input type="text" value="<?php echo $object_field_info['ApiObjectField']['samples'] ?>" class="am-form-field" name="data[ApiObjectField][samples]" onkeydown="if(event.keyCode==13){return false;}">
						      </div><span class="red">* </span>
				    </div>
    							
				    <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" >描述</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        	<textarea name="data[ApiObjectField][description]" class="am-form-field" ><?php echo $object_field_info['ApiObjectField']['description']; ?></textarea>
						      	
						      </div>
				    </div>
				    
				    	<div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" > 排序</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        	
						      	<input type="text" value="<?php echo isset($object_field_info['ApiObjectField']['orderby']) && !empty($object_field_info['ApiObjectField']['orderby'])?$object_field_info['ApiObjectField']['orderby']:'50';     ?>" class="am-form-field" name="data[ApiObjectField][orderby]" onkeydown="if(event.keyCode==13){return false;}">
						      </div>
				    </div>
    
					    				
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
					
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><button type="button"   class="am-btn am-btn-success am-btn-sm am-radius" onclick="saveajax()"  ><?php echo $ld['save'];?></button>
						<button type="button" class="am-btn am-btn-default am-btn-sm am-radius" value=""  onclick="clearaddr()"><?php echo $ld['cancel']?></button>　　　　　 </div>
				</div>
      		</div>			
      		<?php echo $form->end(); ?>						
   </div>
	
	<script type="text/javascript">
	function saveajax(){
	//	alert(document.getElementsByName("data[main][id]")[0].value);
	var pro_id=document.getElementsByName("data[pro_id]")?document.getElementsByName("data[pro_id]")[0].value:0;
	var project_name=document.getElementsByName("data[project_name]")?document.getElementsByName("data[project_name]")[0].value:0;
	var object_name=document.getElementsByName("data[object_name]")?document.getElementsByName("data[object_name]")[0].value:0;
	var user_id=document.getElementsByName("data[main][id]")?document.getElementsByName("data[main][id]")[0].value:0;
	var id=document.getElementsByName("data[ApiObjectField][id]")[0].value;
	var api_project_code=document.getElementsByName("data[ApiObjectField][api_project_code]")[0].value;
	var api_object_code=document.getElementsByName("data[ApiObjectField][api_object_code]")[0].value;
	var names=document.getElementsByName("data[ApiObjectField][name]")[0].value;
			var name=names.replace( /^\s*/, '');

	var sampless=document.getElementsByName("data[ApiObjectField][samples]")[0].value;
				var samples=sampless.replace( /^\s*/, '');

	var type=$('input[name="data[ApiObjectField][type]"]:checked').val();
	var descriptions=document.getElementsByName("data[ApiObjectField][description]")[0].value;
				var description=descriptions.replace( /^\s*/, '');
	var orderbys=document.getElementsByName("data[ApiObjectField][orderby]")?document.getElementsByName("data[ApiObjectField][orderby]")[0].value:50;
				var orderby=orderbys.replace( /^\s*/, '');

	 if(api_project_code=="" && api_object_code==''){
		alert("致命错误，没有项目代码和对象代码传进来");
		return false;
	}else if(name ==''){
		alert("请填写名称");return false;
	}else if(samples == ''){
		alert("请填写示例值");return false;

	}else{
		$.ajax({ url: admin_webroot+"api_methods/api_object_field/0/save",
			type:"POST",
			data:{
					'data[ApiObjectField][id]':id,
					'data[ApiObjectField][api_project_code]':api_project_code,		
					'data[ApiObjectField][api_object_code]':api_object_code,
					'data[ApiObjectField][name]':name,
					'data[ApiObjectField][samples]':samples,
					'data[ApiObjectField][type]':type,
					'data[ApiObjectField][description]':description,
					'data[ApiObjectField][orderby]':orderby,
					'ids':pro_id,
				},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadobjectfield(user_id,pro_id,project_name,object_name);
				
				}else{
					alert("名称重复");
				}
	  		}
	  	});
	  	return false;
  	}
}

function clearaddr(){
		  				var pro_id=document.getElementsByName("data[pro_id]")?document.getElementsByName("data[pro_id]")[0].value:0;
	var project_name=document.getElementsByName("data[project_name]")?document.getElementsByName("data[project_name]")[0].value:0;
	var object_name=document.getElementsByName("data[object_name]")?document.getElementsByName("data[object_name]")[0].value:0;
		var user_id=document.getElementsByName("data[main][id]")?document.getElementsByName("data[main][id]")[0].value:0;
	loadobjectfield(user_id,pro_id,project_name,object_name);
}
</script>
	<?php } ?>		