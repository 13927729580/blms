<style type="text/css">
		.am-form-group{margin:20px 0;}
			.red{
color:red;
margin-top:10px;
float:left;
}
		 </style>


<?php if($type=="list"){ ?>
		<?php	foreach ($_GET as $v){$str=$v;} $arr=explode('/',$str); //pr($arr) ?>
			<input type="hidden" name="data[main_id]" value="<?php echo $arr[3] ; ?>" />
				
	    		<div class="am-panel-group am-panel-tree" style="padding-top:10px">

	    				<div class="am-g am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
					<ul class=" am-bt" style="list-style-type:none;">
							<li style="margin-left:-10px; float:left;">									
										  <button type="button" class="am-btn am-btn-warning am-btn-sm am-radius" onclick="ajaxloadapipro('','<?php echo $project_info['ApiProject']['name'] ?>');">
 											 <span class="am-icon-reply"></span> 返回
										</button>
							
							</li>							
							<li style="margin:0 10px 0 0; float:right;">									
										  <button type="button" class="am-btn am-btn-warning am-btn-sm am-radius" data-am-modal="{target: '#addrresp', closeViaDimmer: 1, width: 900}" onclick="edit_error_code_interpretation('','<?php echo $project_info['ApiProject']['name'] ?>');">
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
																错误代码
													</label>  
												</div>
												 <div class="am-u-lg-5 am-u-md-5 am-u-sm-5">描述</div>
												<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['operate']?></div>
											</div>
							</div>
				</div>
	<div style="height:200px;overflow-y:auto">
							
	<?php  if(isset($error_code_interpretation_infos) && !empty($error_code_interpretation_infos)) { ?>
												 
												 											
	    		<?php      foreach( $error_code_interpretation_infos as $v){ ?>
				<div >
					<div class="listtable_div_top am-panel-body">
						<div class="am-panel-bd am-g">
											<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
									    							<label class="am-checkbox am-success  " style="padding-top:0">
													<input type="checkbox" name="checkboxs[]" data-am-ucheck  value="<?php echo $v['ApiErrorCodeInterpretation']['id']?>" />
													<div style="word-wrap:break-word;word-break:normal;" onclick='listTable.selectAll(this,"checkboxs[]")'><?php   echo $v['ApiErrorCodeInterpretation']['code']   ?></div>
												</label>
											</div>
											<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
													<div style="word-wrap:break-word;word-break:normal;"  ><?php echo empty($v['ApiErrorCodeInterpretation']['description'])?'&nbsp;':$v['ApiErrorCodeInterpretation']['description'];       ?></div>
											</div>
												
											<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 seolink am-btn-group-xs am-action">							
												 <button type="button" class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="edit_error_code_interpretation('<?php echo $v['ApiErrorCodeInterpretation']['id']  ?>','<?php echo $project_info['ApiProject']['name'] ?>');">
								                        <span class="am-icon-pencil-square-o"> <?php echo $ld['edit']; ?></span>
								                      </button>                
												<button type="button" class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" 
												  onclick="del_error_code_interpretation('<?php echo $v['ApiErrorCodeInterpretation']['id'] ?>');" >
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
	  			
	  			
	  			
function loaderrorcodeinterpretation(user_id,val1){
	$.ajax({ url: admin_webroot+"api_methods/api_error_code_interpretation/"+user_id,
		type:"POST",
		dataType:"html",
		success: function(data){
$("#modal-title").html(val1+"——错误代码解释管理");

			$("#api_pro_show").html(data);
  		}
  	});
}	  			
	  			
	  			
	  function edit_error_code_interpretation(Id,val1){
	  	  if(Id=='' || Id=='0'){var str='添加';}else{ var str='编辑'}
	var main_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
	$.ajax({ url: admin_webroot+"api_methods/api_error_code_interpretation/"+main_id+"/edit",
		type:"POST",
		data:{'Id':Id},
		dataType:"html",
		success: function(data){
			$("#modal-title").html(val1+'/错误代码解释管理——'+str);
			$("#api_pro_show").html(data);

  		}
  	});
}

function del_error_code_interpretation(Id){
	if(confirm("<?php echo $ld['confirm_delete'] ?>")){
		var user_id=document.getElementsByName("data[main_id]")?document.getElementsByName("data[main_id]")[0].value:0;
		$.ajax({ url: admin_webroot+"api_methods/api_error_code_interpretation/"+user_id+"/del",
			type:"POST",
			data:{'Id':Id},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loaderrorcodeinterpretation(user_id);
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
				
    			 <?php echo $form->create('',array('action'=>'','id'=>'error_code_interpretation_info_form','name'=>'api_object_view'));?>
    			 	 	<?php	foreach ($_GET as $v){$str=$v;} $arr=explode('/',$str); //pr($arr) ?>
    			 	 <?php if($arr[3]==$project_info['ApiProject']['id']){$project_code=$project_info['ApiProject']['code'];} ?>
			<input type="hidden" name="data[main][id]" value="<?php echo $arr[3] ; ?>" />
				    			 	 	<input type="hidden" name="data[project_name]" value="<?php echo $project_info['ApiProject']['name'] ; ?>" />

      		<div class="am-tab-panel am-fade am-active am-in am-form-detail am-form am-form-horizontal" >
		      			 <input type="hidden" value="<?php echo isset($error_code_interpretation_info['ApiErrorCodeInterpretation']['api_project_code'])?$error_code_interpretation_info['ApiErrorCodeInterpretation']['api_project_code']:$project_code ?>" name="data[ApiErrorCodeInterpretation][api_project_code]" >
					    <input type="hidden" value="<?php echo isset($error_code_interpretation_info['ApiErrorCodeInterpretation']['id'])?$error_code_interpretation_info['ApiErrorCodeInterpretation']['id']:'0'; ?>" name="data[ApiErrorCodeInterpretation][id]" >
        <input type="hidden" value="<?php echo isset($error_code_interpretation_info['ApiErrorCodeInterpretation']['user_id'])?$error_code_interpretation_info['ApiErrorCodeInterpretation']['user_id']:'0'; ?>" name="data[ApiErrorCodeInterpretation][user_id]" >
				 <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">代码</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><textarea class="am-form-field" name="data[ApiErrorCodeInterpretation][code]"><?php echo $error_code_interpretation_info['ApiErrorCodeInterpretation']['code'] ?> </textarea>
						      </div><span class="red">* </span>
				  </div>
				  
    						
    							
				    <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" >描述</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        	<textarea name="data[ApiErrorCodeInterpretation][description]" class="am-form-field" ><?php echo $error_code_interpretation_info['ApiErrorCodeInterpretation']['description']; ?></textarea>
						      	
						      </div>
				    </div>
				    
				    <div class="am-form-group">
						      <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">解决方案</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
						        <textarea name="data[ApiErrorCodeInterpretation][solution]"  class="am-form-field" > <?php echo $error_code_interpretation_info['ApiErrorCodeInterpretation']['solution'] ?> </textarea>
				      		</div><span class="red">* </span>
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
	var id=document.getElementsByName("data[ApiErrorCodeInterpretation][id]")[0].value;
	var api_project_code=document.getElementsByName("data[ApiErrorCodeInterpretation][api_project_code]")[0].value;
	var codes=document.getElementsByName("data[ApiErrorCodeInterpretation][code]")[0].value;
	var code=codes.replace( /^\s*/, '');
	var descriptions=document.getElementsByName("data[ApiErrorCodeInterpretation][description]")[0].value;
		var description=descriptions.replace( /^\s*/, '');
		var project_name=document.getElementsByName("data[project_name]")?document.getElementsByName("data[project_name]")[0].value:0;

	var solutions=document.getElementsByName("data[ApiErrorCodeInterpretation][solution]")[0].value;
	var solution=solutions.replace( /^\s*/, '');

	 if(api_project_code==""){
		alert("致命错误，没有项目代码传进来");
		return false;
	}else if(code ==''){
		alert("请填写代码");return false;
	}else if(solution==''){
		alert("请填写解决方案");return false;
	}else{
		$.ajax({ url: admin_webroot+"api_methods/api_error_code_interpretation/0/save",
			type:"POST",
			data:{
					'data[ApiErrorCodeInterpretation][id]':id,
					
					'data[ApiErrorCodeInterpretation][api_project_code]':api_project_code,
					'data[ApiErrorCodeInterpretation][code]':code,
					'data[ApiErrorCodeInterpretation][description]':description,
					'data[ApiErrorCodeInterpretation][solution]':solution,
				},
			dataType:"json",
			success: function(data){
				if(data.code==1){
					loaderrorcodeinterpretation(user_id,project_name);
				
				}else{
					alert("代码重复");
				}
	  		}
	  	});
	  	return false;
  	}
}

function clearaddr(){
			var project_name=document.getElementsByName("data[project_name]")?document.getElementsByName("data[project_name]")[0].value:0;

		var user_id=document.getElementsByName("data[main][id]")?document.getElementsByName("data[main][id]")[0].value:0;
	loaderrorcodeinterpretation(user_id,project_name);
}
</script>
	<?php } ?>		