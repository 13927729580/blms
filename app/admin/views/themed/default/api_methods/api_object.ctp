<style type="text/css">
	.am-form-group{margin:20px 0;} 
	.am-radio-inline{ float:left;}
	.red{
color:red;
margin-top:10px;
float:left;
}
</style>


		<?php	foreach ($_GET as $v){$strs=$v;} $arrs=explode('/',$strs); ?>
		<input type="hidden" name="data[main_id]" value="<?php echo $arrs[3] ; ?>" /><?php //pr($project_info['ApiProject']['code']) ; ?>
<?php if($type=="list"){ ?>

			
		
				<?php //pr($project_info['ApiProject']['name'] ) ?>
				<input type="hidden" name="data[project_name]" value="<?php echo $project_info['ApiProject']['name'] ; ?>" />
	    		<div class="am-panel-group am-panel-tree" style="padding-top:10px">
	    				<div class="am-g am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
					<ul class=" am-bt" style="list-style-type:none;">	
							<li style="margin-left:-10px; float:left;">									
										  <button type="button" class="am-btn am-btn-warning am-btn-sm am-radius"  onclick="ajaxloadapipro() ">
 										<span class="am-icon-reply" ></span>返回
										</button>
							</li>						
							<li style="margin:0 10px 0 0; float:right;">									
										  <button type="button" class="am-btn am-btn-warning am-btn-sm am-radius" data-am-modal="{target: '#addrresp', closeViaDimmer: 1, width: 900,height:600}" onclick="edit_object('','<?php echo $project_info['ApiProject']['name'] ?>'); ">
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
															<span style="float:left">	对象 名称/代码</span>
													</label>  
												</div>
												 <div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="margin-left:-10px;">类型</div>
												<div class="am-u-lg-5 am-u-md-5 am-u-sm-5"><?php echo $ld['operate']?></div>
											</div>
							</div>
				</div>
<div style="height:200px;overflow-y:scroll" >												 
	<?php  if(isset($object_infos) && !empty($object_infos)) { ?>
												 
												 											
	    		<?php      foreach( $object_infos as $v){ ?>
		<div >
					<div class="listtable_div_top am-panel-body">
						<div class="am-panel-bd am-g">
											<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
									    							<label class="am-checkbox am-success  " style="padding-top:0">
													<input type="checkbox" name="checkboxs[]" data-am-ucheck  value="<?php echo $v['ApiObject']['id']?>" />
													<div style="word-wrap:break-word;word-break:normal;" onclick='listTable.selectAll(this,"checkboxs[]")'>
												<div style="word-wrap:break-word;word-break:normal;float:left;">
													<?php   echo $v['ApiObject']['name']   ?>
														</div><br/>
												<div style="word-wrap:break-word;word-break:normal;color:gray; float:left">
													<?php   echo $v['ApiObject']['code']   ?>
												</div>
																	
													</div>
												</label>
											</div>
										
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php switch( $v['ApiObject']['type']){ case '1': echo '免费';break; case '0': echo '基础'; break; case '2':echo '其他';  break; }  ?>
											</div>
						
												
											<div class="am-u-lg-5 am-u-md-5 am-u-sm-5  am-btn-group-xs am-action">
												<button type="button" class="am-btn am-btn-warning am-btn-sm am-radius" onclick="ajaxloadapiobjectfield('<?php echo $v['ApiObject']['id']  ?>','<?php echo $project_info['ApiProject']['name'] ; ?>','<?php echo $v['ApiObject']['name']  ?>');">
								                        <span class="am-icon-plus"> 字段</span>
								                      </button>							
												 <button type="button"class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_object('<?php echo $v['ApiObject']['id']  ?>','<?php echo $project_info['ApiProject']['name'] ?>');">
								                        <span class="am-icon-pencil-square-o"> <?php echo $ld['edit']; ?></span>
								                      </button>                
												<button type="button" class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" 
												  onclick="del_object('<?php echo $v['ApiObject']['id'] ?>');" >
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
	<div align="center" style="padding-top:10px">	<?php echo $html->link($ld['download_example_batch_csv'],"/api_methods/download_object_csv_example/",'',false,false);?> </div>	

</div>
	  <script type="text/javascript">
	  			//返回
	  	function ajaxloadapipro(){

	$.ajax({ url: admin_webroot+"api_methods/api_project/",
		type:"GET",
		dataType:"html",
		success: function(data){
			
			$("#api_pro_show").html(data);
  		}
  	});
}
function loadobject(user_id,val1){
	$.ajax({ url: admin_webroot+"api_methods/api_object/"+user_id,
		type:"POST",
		dataType:"html",
		success: function(data){
						$("#modal-title").html(val1+"——对象管理");

			$("#api_pro_show").html(data);
  		}
  	});
}	  			
	  			
	  			
	  function edit_object(Id,val1){
	  	  if(Id=='' || Id=='0'){var str='添加';}else{ var str='编辑'}
	var main_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
	$.ajax({ url: admin_webroot+"api_methods/api_object/"+main_id+"/edit",
		type:"POST",
		data:{'Id':Id},
		dataType:"html",
		success: function(data){
					$("#modal-title").html(val1+'/对象管理——'+str);

			$("#api_pro_show").html(data);
  		}
  	});
}

function del_object(Id){
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		
		var project_name=document.getElementsByName("data[project_name]")?document.getElementsByName("data[project_name]")[0].value:0;
		var user_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
		$.ajax({ url: admin_webroot+"api_methods/api_object/"+user_id+"/del",
			type:"POST",
			data:{'Id':Id},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadobject(user_id,project_name);
				}else{
					alert("<?php echo $ld['delete_failure'] ?>");
				}
			}
		});
	}
}


//字段
function ajaxloadapiobjectfield(val1,val2,val3){
		var user_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
		
	$.ajax({ url: admin_webroot+"api_methods/api_object_field/"+val1,
		type:"GET",
			data:{'ids':user_id},
		dataType:"html",
	
		success: function(data){
			$("#modal-title").html(val2+'/'+val3+'——字段管理');
			$("#api_pro_show").html(data);
  		}
  	});
}
</script>
<?php }else{ ?>
	<div style="margin-top:10px;" >
				
    			 <?php echo $form->create('',array('action'=>'','id'=>'api_error_code_interpretation_form','name'=>'object_info_view'));?>
    			 	 	<?php	foreach ($_GET as $v){$str=$v;} $arr=explode('/',$str); ?>
    			  <?php if( $arr[3]==$project_info['ApiProject']['id']){
    			 	    
    			 				$project_code=$project_info['ApiProject']['code']; 
    			 	}
    			 	?>	
			<input type="hidden" name="data[main][id]" value="<?php echo $arr[3] ; ?>" />
    			 	 	<input type="hidden" name="data[project_name]" value="<?php echo $project_info['ApiProject']['name'] ; ?>" />

      		<div class="am-tab-panel am-fade am-active am-in am-form-detail am-form am-form-horizontal" >
		      			 <input type="hidden" value="<?php echo isset($object_info['ApiObject']['api_project_code'])?$object_info['ApiObject']['api_project_code']:$project_code ?>" name="data[ApiObject][api_project_code]" >
					    <input type="hidden" value="<?php echo isset($object_info['ApiObject']['id'])?$object_info['ApiObject']['id']:'0'; ?>" name="data[ApiObject][id]" >
        <input type="hidden" value="<?php echo isset($object_info['ApiObject']['user_id'])?$object_info['ApiObject']['user_id']:'0'; ?>" name="data[ApiObject][user_id]" >
				 <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">名称</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
						     		<input type="text" value="<?php echo $object_info['ApiObject']['name'] ?>" class="am-form-field" name="data[ApiObject][name]" onkeydown="if(event.keyCode==13){return false;}">
						      </div><span class="red">* </span>
				  </div>
				  	<div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" > 代码</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
				<input type="text" value="<?php echo $object_info['ApiObject']['code'] ?>" class="am-form-field" name="data[ApiObject][code]" onkeydown="if(event.keyCode==13){return false;}">

						      </div><span class="red">* </span>
				    </div>					  
				  <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:2px;">类型</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        
						    				<label class="am-radio-inline am-success" style="padding-top:2px;">
					    					<input type="radio" class="radio"  data-am-ucheck value="1" name="data[ApiObject][type]" <?php  echo isset($object_info['ApiObject']['type']) && $object_info['ApiObject']['type']=='1' ?'checked':'';  ?> /> 免费</label> 
										<label class="am-radio-inline am-success" style="padding-top:2px;">
										<input type="radio" class="radio"  data-am-ucheck name="data[ApiObject][type]" value="0" <?php  echo (isset($object_info['ApiObject']['type']) && $object_info['ApiObject']['type']=='0')||!isset($object_info['ApiObject']['type']) ?'checked':'';  ?>  />基础</label>
										<label class="am-radio-inline am-success" style="padding-top:2px;">
										<input type="radio" class="radio"  data-am-ucheck name="data[ApiObject][type]" value="2" <?php  echo isset($object_info['ApiObject']['type']) && $object_info['ApiObject']['type']=='2' ?'checked':'';  ?>  />其他</label>

						      	
						      </div>
				    </div>
    			
    							
				    <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" >描述</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        	<textarea name="data[ApiObject][description]" class="am-form-field" ><?php echo $object_info['ApiObject']['description']; ?></textarea>
						      	
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
	var user_id=document.getElementsByName("data[main][id]")?document.getElementsByName("data[main][id]")[0].value:0;
	var id=document.getElementsByName("data[ApiObject][id]")[0].value;
		var project_name=document.getElementsByName("data[project_name]")?document.getElementsByName("data[project_name]")[0].value:0;

	var api_project_code=document.getElementsByName("data[ApiObject][api_project_code]")[0].value;
	var names=document.getElementsByName("data[ApiObject][name]")[0].value;
		var name=names.replace( /^\s*/, '');

	var codes=document.getElementsByName("data[ApiObject][code]")[0].value;
	var code=codes.replace( /^\s*/, '');
	var type=$('input[name="data[ApiObject][type]"]:checked').val();
	var descriptions=document.getElementsByName("data[ApiObject][description]")[0].value;
	var description=descriptions.replace( /^\s*/, '');

	 if(api_project_code==""){
		alert("致命错误，没有项目代码传进来");
		return false;
	}else if(name ==''){
		alert("请填写名称");return false;
		}else if(code ==''){
		alert("请填写代码");return false;
	}else{
		$.ajax({ url: admin_webroot+"api_methods/api_object/0/save",
			type:"POST",
			data:{
					'data[ApiObject][id]':id,
					
					'data[ApiObject][api_project_code]':api_project_code,
					'data[ApiObject][name]':name,
					'data[ApiObject][code]':code,
					'data[ApiObject][type]':type,
					'data[ApiObject][description]':description,
			
				},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadobject(user_id,project_name);
				
				}else{
					alert("名称或代码重复");
				}
	  		}
	  	});
	  	return false;
  	}
}

function clearaddr(){
			var user_id=document.getElementsByName("data[main][id]")?document.getElementsByName("data[main][id]")[0].value:0;
					var project_name=document.getElementsByName("data[project_name]")?document.getElementsByName("data[project_name]")[0].value:0;

		loadobject(user_id,project_name);
}
</script>

<?php } ?>		