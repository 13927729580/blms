	<style type="text/css">
.am-form-group{margin:20px 0;}
.am-radio-inline {float:left;}
label{font-weight:normal;}
@media only screen and (max-width: 640px){body {word-wrap: normal;}}
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
.btnouter{margin:50px;}
.red{
color:red;
margin-top:10px;
float:left;
}
</style>












<?php if($type=="list"){ ?>
	
	<script type="text/javascript">
	$(function() {
  // 使用默认参数
  $('select').selected();

  // 设置参数
  $('select').selected({
    btnWidth: '200px',
    btnSize: 'sm',
    btnStyle: 'primary',
    
  });
});

change_modal_title();
function change_modal_title(){	
document.getElementById("modal-title").innerHTML='项目管理';
}
</script>

<div style="margin-top:10px;" >
			<?php echo $form->create('',array('action'=>'','id'=>'Aproject','name'=>'AProjectForm','type'=>'get','class'=>'am-form-horizontal'));?>
				 	<div>
					<ul class=" am-avg-md-2 am-avg-lg-3 am-avg-sm-1">
						<li style="margin:0 0 10px 0">  
							<label class="am-u-lg-4  am-u-md-4 am-u-sm-4 am-form-label" style="padding-right: 0;text-align: left;"><?php echo $ld['status'];?></label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7  am-u-end">
								<select name="status" id="status"  data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?> '}">
									<option value=""><?php echo $ld['all_data']?> </option>
									<option value="1" <?php echo @$status=='无效'?'selected':''; ?>> 无效 </option>
									<option value="0" <?php echo @$status=='有效'?'selected':''; ?>> 有效 </option>
									<option value="2" <?php echo @$status=='停用'?'selected':''; ?>> 停用</option>
									<option value="3" <?php echo @$status=='删除'?'selected':''; ?>> 删除</option>
								
								</select>
							</div>
						</li> 
						<li  style="margin:0 0 10px 0">
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label-text " style="padding-right: 0;">关键字</label> 
								<div class="am-u-lg-7  am-u-md-7 am-u-sm-7"  >
								<input type="text" name="keyword" id="pro_name" class="am-form-field am-radius"  value="<?php echo @$keyword;?>" placeholder="<?php echo '项目名称/'.'项目代码';?>" />
								</div>
						</li>

						<li style="margin:0 0 10px 0">
											<div class="am-u-sm-3 am-hide-lg-only">&nbsp;</div>
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-6" style="padding-left:16px;" >
												<button type="button"  class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>"  onclick="searchajax();	"><?php echo $ld['search'];?></button>
											</div>
						</li>
    					</ul>
    				</div>
			<?php echo $form->end();?>
			<div class="am-g am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
					<ul class=" am-bt" style="list-style-type:none;">
					<li style="margin:0 10px 0 0; float:right;"></li>	
<li style="margin:0 10px 0 0; float:right;"><button class="am-btn am-btn am-btn-warning am-btn-sm am-radius" type="button" data-am-modal="{target: 'addredittables', closeViaDimmer: 1, width: 900,height:400}" onclick="ajax_project_upload()">上传</button></li>

<li style="margin:0 10px 0 0; float:right;">
<button  type="button"
  class="am-btn am-btn am-btn-warning am-btn-sm am-radius"
  data-am-modal="{target: 'addredittables', closeViaDimmer: 1, width: 900,height:400}" onclick="ajaxloadapiproview('0')">
  	<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
</button>
			
							
							</li>
					</ul>
			</div>
<!--3-->		
	<?php echo $form->create('',array('action'=>'','name'=>'pro_Form','type'=>'get',"onsubmit"=>"return false;"));?>
	<div class="am-panel-group am-panel-tree">
				<div class="  listtable_div_btm am-panel-header">
							<div class="am-panel-hd" style="padding-left: 0;padding-right: 0;">
											<div class="am-panel-title am-g">
												<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" >
													<label class="am-checkbox am-success  " style="font-weight:bold;padding-top:0">
														<input type="checkbox" data-am-ucheck onclick='listTable.selectAll(this,"checkbox[]")'/>
														<span style="float:left">	项目 代码/名称</span>
													</label>									                        	
												</div>											
												<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">状态</div>
																								
												<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">操作</div>
											</div>
							</div>
				</div>
<!--2-->	
<div style="height:200px;overflow-y:auto">
<?php if(isset($project_infos) && !empty($project_infos)){ ?>												
		<?php foreach($project_infos as $project_info){ ?>
			<div>			
				<div class="listtable_div_top am-panel-body">
					<div class="am-panel-bd am-g" style="padding-left: 0;padding-right: 0;">
										<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
											<label class="am-checkbox am-success  " >
												<input type="checkbox" name="checkbox[]" data-am-ucheck value="<?php echo $project_info['ApiProject']['id']?>" />
												<div style="word-wrap:break-word;word-break:normal;"  onclick='listTable.selectAll(this,"checkbox[]")'>
												<div style="word-wrap:break-word;word-break:normal;float:left;">
												<?php echo $project_info['ApiProject']['name']; ?>
												</div><br>
												<div style="word-wrap:break-word;word-break:normal;color:gray; float:left">
													<?php echo $project_info['ApiProject']['code']; ?>
												</div>
												</div>
											</label>									                
										</div>
										
										<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
												<span ><?php switch($project_info['ApiProject']['status']){case '0':echo '有效';break; case '1': echo '无效';break; case '2': echo '停用';break; case '3': echo '删除';break;}?></span>
										</div>
									
										<div class="am-u-lg-7 am-u-md-7 am-u-sm-7 seolink am-btn-group-xs am-action">	
											
												<button  type="button" class="am-btn am-btn-warning am-btn-sm am-radius" data-am-modal="{target: '#addreobject', closeViaDimmer: 1, width: 1000,height:600}" onclick="ajaxloadapiobject('<?php echo $project_info['ApiProject']['id'] ?>','<?php echo $project_info['ApiProject']['name'] ?>')">
					  								<span >对象</span>
												</button>
													
											
												<button  type="button" class="am-btn am-btn-warning am-btn-sm am-radius" data-am-modal="{target: '#addrerrorcodeinterpretation', closeViaDimmer: 1, width: 600,height:500}" onclick="ajaxloaderrorcodeinterpretation('<?php echo $project_info['ApiProject']['id'] ?>','<?php echo $project_info['ApiProject']['name'] ?>')">
					  								<span >错误解释</span>
												</button>
													
												<button  type="button" class="am-btn am-btn-warning am-btn-sm am-radius" data-am-modal="{target: '#addrprojectcommonparameter', closeViaDimmer: 1, width: 600,height:500}" onclick="ajaxloadprojectcommonparameter('<?php echo $project_info['ApiProject']['id'] ?>','<?php echo $project_info['ApiProject']['name'] ?>')">
					  								<span >公共参数</span>
												</button>

													
								 <button type="button" class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit"  data-am-modal="{target: 'addredittables', closeViaDimmer: 1, width: 900}" onclick="ajaxloadapiproview('<?php echo $project_info['ApiProject']['id'] ?> ')" >
  									<span class="am-icon-pencil-square-o"> 	 <?php echo $ld['edit']; ?></span>
								</button>


											
								 <button type="button" class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" onclick="ajaxloadapiproremove('<?php echo $project_info['ApiProject']['id']; ?>')">
									<span class="am-icon-trash-o"> <?php echo $ld['delete']; ?></span>
								</button>		
											
											
											
																		
											         
												
										</div>
													
												
						</div>
					</div>
				</div>
			<?php } }else{?>
							
						<div>
							<div  class="no_data_found"><?php echo $ld['no_data_found']?></div>
						</div>						
												
<!--2-->		<?php } ?>												
</div>

</div>

		<?php echo $form->end();?>
<!--3-->
</div>

<script type="text/javascript">

	


/*	

	加载
*/


//公共参数
function ajaxloadprojectcommonparameter(val1,val2){

	$.ajax({ url: admin_webroot+"api_methods/api_project_common_parameter/"+val1,
		type:"GET",
		dataType:"html",
		
		success: function(data){
			$("#modal-title").html(val2+'——公共参数管理');
			$("#api_pro_show").html(data);
  		}
  	});
}
	
	
	
//错误代码解释
function ajaxloaderrorcodeinterpretation(val1,val2){

	$.ajax({ url: admin_webroot+"api_methods/api_error_code_interpretation/"+val1,
		type:"GET",
		dataType:"html",
		
		success: function(data){
			$("#modal-title").html(val2+'——错误解释管理');
			$("#api_pro_show").html(data);
  		}
  	});
}


//对象
function ajaxloadapiobject(val1,val2){
	$.ajax({ url: admin_webroot+"api_methods/api_object/"+val1,
		type:"GET",
		dataType:"html",
	
		success: function(data){
			$("#modal-title").html(val2+'——对象管理');
			$("#api_pro_show").html(data);
  		}
  	});
}



function ajaxloadapiproview(id){
		if(id=='' || id=='0'){var str='项目管理——添加'}else{ var str="项目管理——编辑"}
	$.ajax({ url: admin_webroot+"api_methods/api_project/"+id+"/edit",
		type:"POST",
		data:{'Id':id},
		dataType:"html",
		success: function(data){
		$("#modal-title").html(str);
		$("#api_pro_show").html(data);
  		}
  	});
}
    		
   
function searchajax(){

	
	$.ajax({ url: admin_webroot+"api_methods/api_project/",
		type:"GET",
		data:$('#Aproject').serialize(),
		dataType:"html",
		success: function(data){
			$("#api_pro_show").html(data);
  		}
  	});
}
//function ajaxloadapipro(){
//
//	$.ajax({ url: admin_webroot+"api_methods/api_project/",
//		type:"POST",
//		dataType:"html",
//		success: function(data){
//			$("#api_pro_show").html(data);
//		//	searchajax();
//  		}
//  	});
//}
function ajaxloadapiproremove(id)
{       if(confirm("确定删除")){
	$.ajax({ url: admin_webroot+"api_methods/api_project/"+id+"/del",
		type:"POST",
		data: {'Id':id},
		dataType:"json",
		success: function(data){
			searchajax();
  		}
  	});
}
}





</script>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	<?php } else { ?>

<div style="height:300px;overflow-y:auto">
<div style="margin-top:10px;" id="api_pro_view_ajaxdata" >	 
    			 <?php echo $form->create('',array('action'=>'','id'=>'project_info_view_form','name'=>'project_info_view'));?>
    			 	 
      							 <div class="am-tab-panel am-fade am-active am-in am-form-detail am-form am-form-horizontal" class="am-form">
      								<div class="am-form-group">
					     		 <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">API项目代码</label>
					      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
					      	<input type="text" value="<?php  echo $project_info['ApiProject']['code'] ?>" name="data[ApiProject][code]"  class="am-form-field" onkeydown="if(event.keyCode==13){return false;}" ><span></span>
					      </div><span class="red">* </span>
    				</div>
    						  <input type="hidden" value="<?php  echo isset($project_info['ApiProject']['id'])?$project_info['ApiProject']['id'] :'0';  ?>" name="data[ApiProject][id]" />
				 <div class="am-form-group">
						      <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">API项目名称</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input type="text" value="<?php echo $project_info['ApiProject']['name'] ?>" class="am-form-field" name="data[ApiProject][name]" onkeydown="if(event.keyCode==13){return false;}">
						      </div><span class="red">* </span>
				  </div>
				    <div class="am-form-group">
						      <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">HTTP请求地址</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
						        <input type="text" value="<?php echo $project_info['ApiProject']['http_address'] ?>" name="data[ApiProject][http_address]"  class="am-form-field" onkeydown="if(event.keyCode==13){return false;}" ><span></span>
				      		</div><span class="red">* </span>
    				</div>
				    <div class="am-form-group">
						      <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">沙箱HTTP请求地址</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						      	<input type="text" value="<?php echo $project_info['ApiProject']['sandbox_http_address'] ?>" name="data[ApiProject][sandbox_http_address]"  class="am-form-field" onkeydown="if(event.keyCode==13){return false;}" ><span></span>
						      </div><span class="red">* </span>
				    </div>
				    <div class="am-form-group">
						      <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" >HTTPs请求地址</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input type="text" value="<?php echo $project_info['ApiProject']['https_address'] ?> " name="data[ApiProject][https_address]" class="am-form-field" onkeydown="if(event.keyCode==13){return false;}" >
						      </div><span class="red">* </span>
				    </div>
				    <div class="am-form-group">
						      <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">沙箱HTTPs请求地址</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input type="text" value="<?php echo $project_info['ApiProject']['sandbox_https_address'] ?>" name="data[ApiProject][sandbox_https_address]" class="am-form-field" onkeydown="if(event.keyCode==13){return false;}" >
						      </div><span class="red">* </span>
				    </div>
				    <div class="am-form-group">
						      <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="top: -8px;">状态</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" >	
						      	  
						      	  	
					    					
						    				<label class="am-radio-inline am-success" style="padding-top:2px;">
					    					<input type="radio" class="radio"  data-am-ucheck value="1" name="data[ApiProject][status]" <?php echo isset($project_info['ApiProject']['status'])&&$project_info['ApiProject']['status']=='1'?'checked':''; ?>   />无效</label> 
										<label class="am-radio-inline am-success" style="padding-top:2px;">
										<input type="radio" class="radio"  data-am-ucheck name="data[ApiProject][status]" value="0"  <?php echo (isset($project_info['ApiProject']['status'])&&$project_info['ApiProject']['status']=='0')||!isset($project_info['ApiProject']['status'])?'checked':''; ?>   />有效</label>
										<label class="am-radio-inline am-success" style="padding-top:2px;">
										<input type="radio" class="radio"  data-am-ucheck name="data[ApiProject][status]" value="2" <?php echo isset($project_info['ApiProject']['status'])&&$project_info['ApiProject']['status']=='2'?'checked':''; ?>   />停用</label>
										<label class="am-radio-inline am-success" style="padding-top:2px;">
										<input type="radio" class="radio"  data-am-ucheck name="data[ApiProject][status]" value="3"  <?php echo isset($project_info['ApiProject']['status'])&&$project_info['ApiProject']['status']=='3'?'checked':''; ?>   />删除</label>
						</div>	
				      	
				    </div>
    
					    				
				<div class="am-form-group">
					<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
					<div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><button type="button" class="am-btn am-btn-success am-btn-sm am-radius" value="" onclick="addajax()"><?php echo $ld['d_submit'];?></button>
						<button type="button" class="am-btn am-btn-default am-btn-sm am-radius" value=""  onclick="clearaddr()"><?php echo $ld['cancel']?></button></div>
				</div>
      		</div>			
      		<?php echo $form->end(); ?>						
   </div>
   </div>
   	
   	
   <script type="text/javascript">

function addajax(){
		var id=document.getElementsByName("data[ApiProject][id]")?document.getElementsByName("data[ApiProject][id]")[0].value:0;

	var codes=document.getElementsByName("data[ApiProject][code]")?document.getElementsByName("data[ApiProject][code]")[0].value:0;
			var code=codes.replace( /^\s*/, '');

	var names=document.getElementsByName("data[ApiProject][name]")?document.getElementsByName("data[ApiProject][name]")[0].value:0;
			var name=names.replace( /^\s*/, '');

	var http_addresss=document.getElementsByName("data[ApiProject][http_address]")?document.getElementsByName("data[ApiProject][http_address]")[0].value:0;
		var http_address=http_addresss.replace( /^\s*/, '');

	var sandbox_http_addresss=document.getElementsByName("data[ApiProject][sandbox_http_address]")?document.getElementsByName("data[ApiProject][sandbox_http_address]")[0].value:0;
		var sandbox_http_address=sandbox_http_addresss.replace( /^\s*/, '');

	var https_addresss=document.getElementsByName("data[ApiProject][https_address]")?document.getElementsByName("data[ApiProject][https_address]")[0].value:0;
		var https_address=https_addresss.replace( /^\s*/, '');

	var sandbox_https_addresss=document.getElementsByName("data[ApiProject][sandbox_https_address]")?document.getElementsByName("data[ApiProject][sandbox_https_address]")[0].value:0;
		var sandbox_https_address=sandbox_https_addresss.replace( /^\s*/, '');

	var status=$('input[name="data[ApiProject][status]"]:checked').val();
	if(code==''){
	alert('请填写项目代码');return false;
	}else if(name==''){
	alert('请填写项目名称');return false;
	}else if(http_address==''){
	alert('请填写HTTP请求地址');return false;
	}else if(sandbox_http_address==''){
	alert('请填写沙箱HTTP请求地址');return false;
	}else if(https_address==''){
	alert('请填写HTTPs请求地址');return false;
	}else if(sandbox_https_address==''){
	alert('请填写沙箱HTTPs请求地址');return false;
	}else{
	$.ajax({ url: admin_webroot+"api_methods/api_project/0/save",
		type:"POST",
	data:{
					'data[ApiProject][id]':id,
					
					'data[ApiProject][name]':name,
					'data[ApiProject][status]':status,
					'data[ApiProject][http_address]':http_address,
					'data[ApiProject][code]':code,
						'data[ApiProject][sandbox_http_address]':sandbox_http_address,
					'data[ApiProject][https_address]':https_address,
					'data[ApiProject][sandbox_https_address]':sandbox_https_address,
				},
		
		dataType:"json",
		success: function(data){
			//alert(data.code);
		if(data.code==1){
			//ajaxloadapipro();
			searchajax();
		}else{
			alert("项目名称或代码重复");
		}
  		}
  	});
  	}
}


function clearaddr(){
	//ajaxloadapipro();
	searchajax();
}


</script>	
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		<?php } ?>
