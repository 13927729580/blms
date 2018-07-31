<style type="text/css">
	.am-checkbox {margin-top:0px; margin-bottom:0px;}
    
	.btnouterlist{overflow: visible;}
	.am-yes{color:#5eb95e;}
	.am-no{color:#dd514c;}
	.am-panel-title div{font-weight:bold;}
       .am-checkbox .am-icon-checked, .am-checkbox .am-icon-unchecked, .am-checkbox-inline .am-icon-checked, .am-checkbox-inline .am-icon-unchecked, .am-radio .am-icon-checked, .am-radio .am-icon-unchecked, .am-radio-inline .am-icon-checked, .am-radio-inline .am-icon-unchecked {
    background-color: transparent;
    display: inline-table;
    left: 0;
    margin: 0;
    position: absolute;
    top: 3px;
    transition: color 0.25s linear 0s;
}
	.am-form-group{margin:20px 0;}
	.am-selected-list li{  max-width:550px; }
	.am-active .am-btn-default.am-dropdown-toggle, .am-btn-default.am-active, .am-btn-default:active{
		background-color:white;
	}
		.red{
color:red;
margin-top:10px;
float:left;
}
</style>
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

</script>



<?php if($type=="list"){ ?>
	<script type="text/javascript">change_modal_title();
function change_modal_title(){
	document.getElementById("modal-titles").innerHTML='项目分类管理';
	
	}</script>
	<div style="margin-top:10px;" >
			<?php echo $form->create('',array('action'=>'','id'=>'Acateg','name'=>'ACategForm','type'=>'get','class'=>'am-form-horizontal'));?>
				 <div>	
					<ul class=" am-avg-md-2 am-avg-lg-3 am-avg-sm-1">
						<li style="margin:0 0 10px 0">  
							<label class="am-u-lg-4  am-u-md-4 am-u-sm-4 am-form-label" style="padding-right: 0;text-align: left;">项目名称</label>
							<div class="am-u-lg-6 am-u-md-6 am-u-sm-6  am-u-end">
								<select name="codes" data-am-selected="{searchBox: 1}">
								  <option value="" selected ><?php echo $ld['all_data']?> </option>
									<?php foreach($project_list as $k=>$v){ ?>
									<option value="<?php echo $v  ?>" <?php echo @$codes==$v?'selected':''; ?> > <?php echo $k ?></option>
									<?php } ?>
								</select>
							</div>
						</li> 
						<li  style="margin:0 0 10px 0">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text " style="padding-right: 0;text-align: left;padding-left: 0;">关键字</label> 
								<div class="am-u-lg-7  am-u-md-7 am-u-sm-7"  >
								<input type="text" name="categ_name" class="am-form-field am-radius"   value="<?php echo @$categ_names;?>" placeholder="<?php echo '分类名称/'.'分类代码'?>" />
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
									<li style="margin:0 10px 0 0; float:right;">
									
										 <button type="button" class="am-btn am-btn am-btn-warning am-btn-sm am-radius" data-am-modal="{target: 'addredittables', closeViaDimmer: 1, width: 800,height:400}" onclick="ajaxloadapicategview('0')">
  												<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
										</button>	
									</li>
							</ul>
					</div>
<!--3-->		
	<?php echo $form->create('',array('action'=>'','name'=>'pro_Form','type'=>'get',"onsubmit"=>"return false;"));?>
	<div class="am-panel-group am-panel-tree" >
				<div class="  listtable_div_btm am-panel-header">
							<div class="am-panel-hd">
											<div class="am-panel-title am-g">
												<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
													<label class="am-checkbox am-success  " style="font-weight:bold;padding-top:0">
														<input  type="checkbox" data-am-ucheck onclick='listTable.selectAll(this,"checkbox[]")'/>
															<span style="float:left">	项目名称</span>
													</label>									                        	
												</div>
												<div class="am-u-lg-4 am-u-md-4 am-u-sm-4" ><span style="float:left;margin-left:-8px">分类 代码/名称</span></div>										
												<div class="am-u-lg-2 am-u-md-2 am-u-sm-2" style="margin-left:-8px">排序</div>
																								
												<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">操作</div>
											</div>
							</div>
				</div>
<!--2-->	
<div style="height:200px;overflow-y:auto">
<?php if(isset($category_infos) && !empty($category_infos)){ ?>														
		<?php foreach($category_infos as $category_info){ ?>
			<div>
					<div class="listtable_div_top am-panel-body">
					<div class="am-panel-bd am-g">

										<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
											<label class="am-checkbox am-success  " >
												<input type="checkbox" name="checkbox[]" data-am-ucheck value="<?php echo $category_info['ApiCategory']['id']?>" />
												<div style="word-wrap:break-word;word-break:normal; float:left"  onclick='listTable.selectAll(this,"checkbox[]")'><?php foreach($project_list as $k=>$v){  if($v== $category_info['ApiCategory']['api_project_code'] ){echo $k;}   }?></div>
											</label>									                
										</div>
										<div class="am-u-lg-4 am-u-md-4 am-u-sm-4"  style="word-wrap:break-word;word-break:normal;" >
												
									
											
												<div style="word-wrap:break-word;word-break:normal;float:left;">
													<?php echo $category_info['ApiCategory']['name'] ?>
												</div><br/>
												<div style="word-wrap:break-word;word-break:normal;color:gray; float:left">
													<?php echo $category_info['ApiCategory']['code'] ?>
												</div>
											
										</div>

										<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
												
									
												<div style="word-wrap:break-word;word-break:normal;" ><?php echo $category_info['ApiCategory']['orderby'] ?></div>
											
										</div>
									
										<div class="am-u-lg-3 am-u-md-3 am-u-sm-3 seolink am-btn-group-xs am-action">		
												 <button type="button" class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" data-am-modal="{target: 'addredittables', closeViaDimmer: 1, width: 900,height:400}" onclick="ajaxloadapicategview('<?php echo $category_info['ApiCategory']['id'] ?> ')" >
				  										<span class="am-icon-pencil-square-o"> 	 <?php echo $ld['edit']; ?></span>
												</button>


											
											 	<button type="button" class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" onclick="ajaxloadapicategremove(<?php echo $category_info['ApiCategory']['id'] ?> );" >
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

function ajaxloadapicategview(id){
	if(id=='' || id=='0'){var str='项目分类管理——添加'}else{ var str="项目分类管理——编辑"}
	$.ajax({ url: admin_webroot+"api_methods/api_category/"+id+"/edit",
		type:"POST",
		data:{'Id':id},
		dataType:"html",
		success: function(data){
	
		$("#modal-titles").html(str);
		$("#api_categ_show").html(data);
  		}
  	});
}
    		
   
function searchajax(){

	
	$.ajax({ url: admin_webroot+"api_methods/api_category/",
		type:"GET",
		data:$('#Acateg').serialize(),
		dataType:"html",
		success: function(data){
			$("#api_categ_show").html(data);
  		}
  	});
}

function ajaxloadapicategremove(id)
{	if(confirm("确定删除")){
	$.ajax({ url: admin_webroot+"api_methods/api_category/"+id+"/del",
		type:"POST",
		data: {'Id':id},
		dataType:"json",
		success: function(){
			searchajax();
  		}
  	});
}
}





</script>
	
	
	
	
	<?php }else {  ?>
		
	
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

	<div style="height:400px;overflow-y:auto">
			<div style="margin-top:10px;" >	 
    			 <?php echo $form->create('',array('action'=>'','id'=>'api_categ_view_form','name'=>'api_categ_view'));?>
			 
      		<div class="am-tab-panel am-fade am-active am-in am-form-detail am-form am-form-horizontal" >
		      						<div class="am-form-group">
							     		 <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" style="margin-top:8px">API项目名称</label>
										      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8  " >
										      	<select name="data[ApiCategory][api_project_code]" class="am-form-field ">
														<option value="">请选择</option>
														<?php  foreach($project_list as $k=>$v){ ?>
															<option value="<?php echo $v;  ?>"  <?php echo isset($category_info['ApiCategory']['api_project_code']) && $category_info['ApiCategory']['api_project_code']==$v?'selected':''; ?> ><?php echo $k  ?></option>
														<?php } ?>
													</select>
										      </div><span class="red">* </span>
					    				</div>
    						  <input type="hidden" value="<?php  echo isset($category_info['ApiCategory']['id'])?$category_info['ApiCategory']['id']:'0';     ?>" name="data[ApiCategory][id]" />
				 <div class="am-form-group">
						      <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">分类代码</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8"><input type="text" value="<?php echo $category_info['ApiCategory']['code'] ?>" class="am-form-field" name="data[ApiCategory][code]" onkeydown="if(event.keyCode==13){return false;}">
						      </div><span class="red">* </span>
				  </div>
				    <div class="am-form-group">
						      <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label">分类名称</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
						        <input type="text" value="<?php echo $category_info['ApiCategory']['name'] ?>" name="data[ApiCategory][name]"  class="am-form-field" onkeydown="if(event.keyCode==13){return false;}" ><span></span>
				      		</div><span class="red">* </span>
    				</div>
    				<div class="am-form-group">
						      <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" >排序</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        <input type="text" value="<?php echo isset($category_info['ApiCategory']['orderby']) && !empty($category_info['ApiCategory']['orderby'])?$category_info['ApiCategory']['orderby']:'50'; ?>" name="data[ApiCategory][orderby]"  class="am-form-field" onkeydown="if(event.keyCode==13){return false;}" ><span></span>
						      	
						      </div>
				    </div>
				    <div class="am-form-group">
						      <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" >描述</label>
						      <div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						        	<textarea name="data[ApiCategory][description]" class="am-form-field" ><?php echo $category_info['ApiCategory']['description']; ?></textarea>
						      	
						      </div>
				    </div>
				    
				  
    
					    				
				<div class="am-form-group">
					
					<div class="am-u-lg-12 am-u-md-12 am-u-sm-12"><button type="button" class="am-btn am-btn-success am-btn-sm am-radius" onclick="saveajax()"  value=""><?php echo $ld['d_submit'];?></button>
					<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-label"></label>
						<button type="button" class="am-btn am-btn-default am-btn-sm am-radius" value="" onclick="clearaddr()"><?php echo $ld['cancel']?></button>　　　　　 </div>
				</div>
      		</div>		
      		
      		<?php echo $form->end(); ?>						
   		</div>
    </div>	

   	
   <script type="text/javascript">
   
function saveajax(){
			var id=document.getElementsByName("data[ApiCategory][id]")?document.getElementsByName("data[ApiCategory][id]")[0].value:0;
			var api_project_codes=document.getElementsByName("data[ApiCategory][api_project_code]")?document.getElementsByName("data[ApiCategory][api_project_code]")[0].value:0;
				var api_project_code=api_project_codes.replace( /^\s*/, '');

			var codes=document.getElementsByName("data[ApiCategory][code]")?document.getElementsByName("data[ApiCategory][code]")[0].value:0;
				var code=codes.replace( /^\s*/, '');
			var orderbys=document.getElementsByName("data[ApiCategory][orderby]")?document.getElementsByName("data[ApiCategory][orderby]")[0].value:0;
				var orderby=orderbys.replace( /^\s*/, '');
			var names=document.getElementsByName("data[ApiCategory][name]")?document.getElementsByName("data[ApiCategory][name]")[0].value:0;
				var name=names.replace( /^\s*/, '');
	var descriptions=document.getElementsByName("data[ApiCategory][description]")?document.getElementsByName("data[ApiCategory][description]")[0].value:0;
				var description=descriptions.replace( /^\s*/, '');
		if(api_project_code==''){
		
		alert("请选择项目名称");
		return false;
		}else if(code==''){
		
		alert("请填写分类代码");
		return false;
		}else if(name==''){
		alert("请填写分类代码名称");	
		return false;
		}else{
	
			$.ajax({ url: admin_webroot+"api_methods/api_category/0/save",
				type:"POST",
				data:{
								'data[ApiCategory][id]':id,
								
								'data[ApiCategory][api_project_code]':api_project_code,
								'data[ApiCategory][name]':name,
								'data[ApiCategory][code]':code,
								'data[ApiCategory][description]':description,
								'data[ApiCategory][orderby]':orderby,
							},
				dataType:"json",
				success: function(data){
					if(data.code==1){
						searchajax();
					}else{
						alert("分类名称或分类代码重复");
					}
		  		}
		  	});
	  	}
}

function ajaxloadapicateg(){

	$.ajax({ url: admin_webroot+"api_methods/api_category/",
		type:"GET",
		dataType:"html",
		success: function(data){
			$("#api_categ_show").html(data);
  		}
  	});
}


function clearaddr(){
ajaxloadapicateg();
}
</script>
		
		
		
		
		
		
		
		
		
		
		
		<?php } ?>
