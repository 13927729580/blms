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
	<div class="am-panel-group am-panel-tree" id="method_request_example_ajaxdata">
	    			<div class="am-g am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
					<ul class=" am-bt" style="list-style-type:none;">							
							<li style="margin:0 10px 0 0; float:right;">
									
										  <button type="button" class="am-btn am-btn-warning am-btn-sm am-radius"  onclick="edit_method_request_example('','<?php echo $project_info['ApiProject']['name'] ; ?>','<?php echo $method_info['ApiMethod']['name'] ; ?>')">
 											 <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
										</button>

								
									
									
									
							
							</li>
					</ul>
				</div>
				<div class="  am-panel-header">
			
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
										
		<?php if(isset($method_request_example_infos) && !empty($method_request_example_infos)) {?>											
	    		<?php foreach( $method_request_example_infos as $v){ ?>
	    			<div >
				
					<div class=" am-panel-body">
						<div class="am-panel-bd am-g">
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
												<label class="am-checkbox am-success  " style="padding-top:0">
													<input type="checkbox" name="checkboxs[]" data-am-ucheck  value="<?php echo $v['ApiMethodRequestExample']['id']?>" />

												</label>
											</div>
										
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
													<span ><?php switch($v['ApiMethodRequestExample']['type']){ case 'JAVA':echo 'JAVA';break; case '.NET': echo '.NET';break; case 'PHP': echo 'PHP';break; case 'Python':echo 'Python';break; case 'CURL': echo 'CURL';break; case 'C/C++': echo 'C/C++';break;case 'NodeJS': echo 'NodeJS';break; case 'JQuery': echo 'JQuery';break;default: echo '&nbsp;';}?></span>
											</div>
											<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
													<span  style="word-wrap:break-word;word-break:normal;" ><?php echo empty($v['ApiMethodRequestExample']['description'])?'&nbsp;':$v['ApiMethodRequestExample']['description'];     ?></span>
											</div>
										
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 seolink am-btn-group-xs am-action">							
												 <button type="button" class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_method_request_example('<?php echo $v['ApiMethodRequestExample']['id']  ?>','<?php echo $project_info['ApiProject']['name'] ; ?>','<?php echo $method_info['ApiMethod']['name'] ; ?>')">
								                        <span class="am-icon-pencil-square-o"> <?php echo $ld['edit']; ?></span>
								                      </button>                
												<button type="button" class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" 
												  onclick="del_method_request_example('<?php echo $v['ApiMethodRequestExample']['id'] ?>')" >
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
function loadmethodrequestexample(user_id){
	$.ajax({ url: admin_webroot+"api_methods/api_method_request_example/"+user_id,
		type:"POST",
		dataType:"html",
		success: function(data){
			$("#method_request_example_ajaxdata").parent().html(data);
	
  		}
  	});
}	  			
	  			
	  			
	  function edit_method_request_example(Id,val1,val2){
	  	  $("#method_request_example_btn").click();
	  	  	if(Id=='' || Id=='0'){ var str='添加'}else{ var str='编辑'}
	var main_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
	$.ajax({ url: admin_webroot+"api_methods/api_method_request_example/"+main_id+"/edit",
		type:"POST",
		data:{'Id':Id},
		dataType:"html",
		success: function(data){
		
			$("#method_request_example_popup .am-modal-bd").find('#methodrequestexampleoperation').remove();
			$(" #modal_title_request_example").html(val1+'/'+val2+'/请求示例管理——'+str);
			$("#method_request_example_popup .am-modal-bd").html(data);
			$("#method_request_example_popup select#select_2").selected();
  		}
  	});
}

function del_method_request_example(Id){
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		var user_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
		$.ajax({ url: admin_webroot+"api_methods/api_method_request_example/"+user_id+"/del",
			type:"POST",
			data:{'Id':Id},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadmethodrequestexample(user_id);
				}else{
					alert("<?php echo $ld['delete_failure'] ?>");
				}
			}
		});
	}
}



</script>
	
<?php }else{ ?>
		<div style="margin-top:10px;"  id="methodrequestexampleoperation">
				
    			 <?php echo $form->create('',array('action'=>'','name'=>'method_request_example_Info'));?>
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
		      			 <input type="hidden" value="<?php echo isset($method_request_example_Info['ApiMethodRequestExample']['api_project_code'])?$method_request_example_Info['ApiMethodRequestExample']['api_project_code']:$project_code ?>" name="data[ApiMethodRequestExample][api_project_code]" >
		      			<input type="hidden" value="<?php echo isset($method_request_example_Info['ApiMethodRequestExample']['api_category_code'])?$method_request_example_Info['ApiMethodRequestExample']['api_category_code']:$category_code ?>" name="data[ApiMethodRequestExample][api_category_code]" >
					    <input type="hidden" value="<?php echo isset($method_request_example_Info['ApiMethodRequestExample']['api_method_code'])?$method_request_example_Info['ApiMethodRequestExample']['api_method_code']:$method_code ?>" name="data[ApiMethodRequestExample][api_method_code]" >
					    <input type="hidden" value="<?php echo isset($method_request_example_Info['ApiMethodRequestExample']['id'])?$method_request_example_Info['ApiMethodRequestExample']['id']:'0'; ?>" name="data[ApiMethodRequestExample][id]" >
        <input type="hidden" value="<?php echo isset($method_request_example_Info['ApiMethodRequestExample']['user_id'])?$method_request_example_Info['ApiMethodRequestExample']['user_id']:'0'; ?>" name="data[ApiMethodRequestExample][user_id]" >
				 
				  					  
				  <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:2px;">类型</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						       	<select id="select_2"  name="data[ApiMethodRequestExample][type]" >
										<option value=""> 请选择</option>
					    					<option value="JAVA" <?php echo  isset($method_request_example_Info['ApiMethodRequestExample']['type'] ) && $method_request_example_Info['ApiMethodRequestExample']['type']=='JAVA' ?selected:''; ?> > JAVA </option> 
										<option value=".NET" <?php echo isset($method_request_example_Info['ApiMethodRequestExample']['type']) && $method_request_example_Info['ApiMethodRequestExample']['type']=='.NET'?selected:''; ?>  >.NET</option>
										<option value="PHP" <?php echo isset($method_request_example_Info['ApiMethodRequestExample']['type']) && $method_request_example_Info['ApiMethodRequestExample']['type']=='PHP'?selected:''; ?> >PHP</option>
					      				<option value="Python" <?php echo  isset($method_request_example_Info['ApiMethodRequestExample']['type'] ) && $method_request_example_Info['ApiMethodRequestExample']['type']=='Python' ?selected:''; ?> > Python </option> 
										<option value="CURL" <?php echo isset($method_request_example_Info['ApiMethodRequestExample']['type']) && $method_request_example_Info['ApiMethodRequestExample']['type']=='CURL'?selected:''; ?>  >CURL</option>
										<option value="C/C++" <?php echo isset($method_request_example_Info['ApiMethodRequestExample']['type']) && $method_request_example_Info['ApiMethodRequestExample']['type']=='C/C++'?selected:''; ?> >C/C++</option>
					    					<option value="NodeJS" <?php echo  isset($method_request_example_Info['ApiMethodRequestExample']['type'] ) && $method_request_example_Info['ApiMethodRequestExample']['type']=='NodeJS' ?selected:''; ?> > NodeJS </option> 
					    					<option value="JQuery" <?php echo  isset($method_request_example_Info['ApiMethodRequestExample']['type'] ) && $method_request_example_Info['ApiMethodRequestExample']['type']=='JQuery' ?selected:''; ?> > JQuery </option> 
						      	</select>

						      </div><span class="red">* </span>
				    </div>
	
				    <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" >描述</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        	<textarea   name="data[ApiMethodRequestExample][description]" class="am-form-field"   ><?php echo $method_request_example_Info['ApiMethodRequestExample']['description']; ?></textarea>
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
	function checkLength(){
        var value = document.getElementById("description").value;
        if(value.length>80){
            document.getElementById("description").value=document.getElementById("description").value.substr(0, 80);
        }else{
            document.getElementById("validNum").innerHTML = 80- value.length;
        }
    }
	function saveajax(){
	//	alert(document.getElementsByName("data[main][id]")[0].value);
	var user_id=document.getElementsByName("data[main][id]")?document.getElementsByName("data[main][id]")[0].value:0;
	var id=document.getElementsByName("data[ApiMethodRequestExample][id]")[0].value;
	var api_project_code=document.getElementsByName("data[ApiMethodRequestExample][api_project_code]")[0].value;
	var api_category_code=document.getElementsByName("data[ApiMethodRequestExample][api_category_code]")[0].value;
	var api_method_code=document.getElementsByName("data[ApiMethodRequestExample][api_method_code]")[0].value;
	

	var type=document.getElementsByName("data[ApiMethodRequestExample][type]")[0].value;
	var description=document.getElementsByName("data[ApiMethodRequestExample][description]")[0].value;

	 if(api_project_code=="" && api_category_code=="" && api_method_code==""){
		alert("致命错误，没有项目代码和对象代码传进来");
		return false;
	}else if(type == ""){
		alert("请填写类型");return false;

	}else{
		$.ajax({ url: admin_webroot+"api_methods/api_method_request_example/0/save",
			type:"POST",
			data:{
					'data[ApiMethodRequestExample][id]':id,
					'data[ApiMethodRequestExample][api_project_code]':api_project_code,		
					'data[ApiMethodRequestExample][api_category_code]':api_category_code,
					'data[ApiMethodRequestExample][api_method_code]':api_method_code,
				
					'data[ApiMethodRequestExample][type]':type,
					'data[ApiMethodRequestExample][description]':description,
			
				},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadmethodrequestexample(user_id);
					$("#method_request_example_popup").modal('close');
				}else{
					alert("<?php echo $ld['operation_success'] ?>");
				}
	  		}
	  	});
	  	return false;
  	}
}

function clearaddr(){
	$("#method_request_example_ajaxdata").parent().find('#methodrequestexampleoperation').remove();
	$("#method_request_example_popup").modal('close');
}
</script>
	
	
	<?php } ?>