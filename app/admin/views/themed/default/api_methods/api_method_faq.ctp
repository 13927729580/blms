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
	<div class="am-panel-group am-panel-tree" id="method_faq_ajaxdata">
	    			<div class="am-g am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
					<ul class=" am-bt" style="list-style-type:none;">							
							<li style="margin:0 10px 0 0; float:right;">
									
										  <button type="button" class="am-btn am-btn-warning am-btn-sm am-radius"  onclick="edit_method_faq('','<?php echo $project_info['ApiProject']['name'] ; ?>','<?php echo $method_info['ApiMethod']['name'] ; ?>')">
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
												<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">问题</div>
											
												<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">答案</div>
												
											
												
												<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['operate']?></div>
											</div>
							</div>
				</div>
										
		<?php if(isset($method_faq_infos) && !empty($method_faq_infos)) {?>											
	    		<?php foreach( $method_faq_infos as $v){ ?>
	    			<div >
				
					<div class=" am-panel-body">
						<div class="am-panel-bd am-g">
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
												<label class="am-checkbox am-success  " style="padding-top:0">
													<input type="checkbox" name="checkboxs[]" data-am-ucheck  value="<?php echo $v['ApiMethodFaq']['id']?>" />
													
												</label>
											</div>
											<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
													<div style="word-wrap:break-word;word-break:normal;"><?php echo empty($v['ApiMethodFaq']['question'])&&isset($v['ApiMethodFaq']['question'])?'&nbsp;':$v['ApiMethodFaq']['question'];  ?> </div>
											</div>
										
											<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
													<div style="word-wrap:break-word;word-break:normal;"><?php echo empty($v['ApiMethodFaq']['answer'])&&isset($v['ApiMethodFaq']['answer'])?'&nbsp;':$v['ApiMethodFaq']['answer']; ?></div>
											</div>
											
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 seolink am-btn-group-xs am-action">							
												 <button type="button" class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_method_faq('<?php echo $v['ApiMethodFaq']['id']  ?>','<?php echo $project_info['ApiProject']['name'] ; ?>','<?php echo $method_info['ApiMethod']['name'] ; ?>')">
								                        <span class="am-icon-pencil-square-o"> <?php echo $ld['edit']; ?></span>
								                      </button>                
												<button type="button" class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" 
												  onclick="del_method_faq('<?php echo $v['ApiMethodFaq']['id'] ?>')" >
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
function loadmethodfaq(user_id){
	$.ajax({ url: admin_webroot+"api_methods/api_method_faq/"+user_id,
		type:"POST",
		dataType:"html",
		success: function(data){
			$("#method_faq_ajaxdata").parent().html(data);
	
  		}
  	});
}	  			
	  			
	  			
	  function edit_method_faq(Id,val1,val2){
	  	  $("#method_faq_btn").click();
	  	  		if(Id=='' || Id=='0'){ var str='添加'}else{ var str='编辑'}

	var main_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
	$.ajax({ url: admin_webroot+"api_methods/api_method_faq/"+main_id+"/edit",
		type:"POST",
		data:{'Id':Id},
		dataType:"html",
		success: function(data){
		
			$("#method_faq_popup .am-modal-bd").find('#methodfaqoperation').remove();
				$(" #modal_title_faq").html(val1+'/'+val2+'/FAQ管理——'+str);
				$("#method_faq_popup .am-modal-bd").html(data);
  		}
  	});
}

function del_method_faq(Id){
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		var user_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
		$.ajax({ url: admin_webroot+"api_methods/api_method_faq/"+user_id+"/del",
			type:"POST",
			data:{'Id':Id},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadmethodfaq(user_id);
				}else{
					alert("<?php echo $ld['delete_failure'] ?>");
				}
			}
		});
	}
}



</script>
	
<?php }else{ ?>
		<div style="margin-top:10px;"  id="methodfaqoperation">
				
    			 <?php echo $form->create('',array('action'=>'','name'=>'method_faq_Info'));?>
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
		      			 <input type="hidden" value="<?php echo isset($method_faq_Info['ApiMethodFaq']['api_project_code'])?$method_faq_Info['ApiMethodFaq']['api_project_code']:$project_code ?>" name="data[ApiMethodFaq][api_project_code]" >
		      			<input type="hidden" value="<?php echo isset($method_faq_Info['ApiMethodFaq']['api_category_code'])?$method_faq_Info['ApiMethodFaq']['api_category_code']:$category_code ?>" name="data[ApiMethodFaq][api_category_code]" >
					    <input type="hidden" value="<?php echo isset($method_faq_Info['ApiMethodFaq']['api_method_code'])?$method_faq_Info['ApiMethodFaq']['api_method_code']:$method_code ?>" name="data[ApiMethodFaq][api_method_code]" >
					    <input type="hidden" value="<?php echo isset($method_faq_Info['ApiMethodFaq']['id'])?$method_faq_Info['ApiMethodFaq']['id']:'0'; ?>" name="data[ApiMethodFaq][id]" >
        <input type="hidden" value="<?php echo isset($method_faq_Info['ApiMethodFaq']['user_id'])?$method_faq_Info['ApiMethodFaq']['user_id']:'0'; ?>" name="data[ApiMethodFaq][user_id]" >		  
				  <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" >问题</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
							
							<textarea    class="am-form-field" name="data[ApiMethodFaq][question]" ><?php echo $method_faq_Info['ApiMethodFaq']['question']; ?></textarea>
						      </div><span class="red">* </span>
				    </div>
				    
    							
				    <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" >答案</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        	<textarea  name="data[ApiMethodFaq][answer]" class="am-form-field"  ><?php echo $method_faq_Info['ApiMethodFaq']['answer']; ?></textarea>
						      </div><span class="red">* </span>
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
	var id=document.getElementsByName("data[ApiMethodFaq][id]")[0].value;
	var api_project_code=document.getElementsByName("data[ApiMethodFaq][api_project_code]")[0].value;
	var api_category_code=document.getElementsByName("data[ApiMethodFaq][api_category_code]")[0].value;
	var api_method_code=document.getElementsByName("data[ApiMethodFaq][api_method_code]")[0].value;

	var questions=document.getElementsByName("data[ApiMethodFaq][question]")[0].value;
		var question=questions.replace( /^\s*/, '');

	var answers=document.getElementsByName("data[ApiMethodFaq][answer]")[0].value;
	var answer=answers.replace( /^\s*/, '');

	 if(api_project_code=="" && api_category_code=="" && api_method_code==""){
		alert("致命错误，没有项目代码和对象代码传进来");
		return false;
	}else if(question==''){
		alert("请填写问题");
		return false;
		
	}else if(answer==''){
		alert("请填写答案");
		return false;
	}else{
		$.ajax({ url: admin_webroot+"api_methods/api_method_faq/0/save",
			type:"POST",
			data:{
					'data[ApiMethodFaq][id]':id,
					'data[ApiMethodFaq][api_project_code]':api_project_code,		
					'data[ApiMethodFaq][api_category_code]':api_category_code,
					'data[ApiMethodFaq][api_method_code]':api_method_code,
					'data[ApiMethodFaq][question]':question,
					'data[ApiMethodFaq][answer]':answer,
			
				},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loadmethodfaq(user_id);
					$("#method_faq_popup").modal('close');
				}else{
					alert("问题重复");
				}
	  		}
	  	});
	  	return false;
  	}
}

function clearaddr(){
	$("#method_faq_ajaxdata").parent().find('#methodfaqoperation').remove();
	$("#method_faq_popup").modal('close');
}
</script>
	
	
	<?php } ?>