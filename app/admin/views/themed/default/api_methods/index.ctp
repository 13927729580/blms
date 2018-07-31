<style type="text/css">
		.am-selected-list li{
	max-width:200px;
	}
	.am-checkbox {margin-top:0px; margin-bottom:0px;}
    	.am-word{word-wrap:break-word;word-break:normal;}
	.btnouterlist{overflow: visible;}
	.am-yes{color:#5eb95e;}
	.am-no{color:#dd514c;}
	.am-panel-title div{font-weight:bold;}
	.am-form-horizontal .am-form-label{padding-top: 0.5em;}
       .am-checkbox .am-icon-checked, .am-checkbox .am-icon-unchecked, .am-checkbox-inline .am-icon-checked, .am-checkbox-inline .am-icon-unchecked, .am-radio .am-icon-checked, .am-radio .am-icon-unchecked, .am-radio-inline .am-icon-checked, .am-radio-inline .am-icon-unchecked {
    background-color: transparent;
    display: inline-table;
    left: 0;
    margin: 0;
    position: absolute;
    top: 3px;
    transition: color 0.25s linear 0s;
}
.am-modal-hd{
border-bottom:1px solid #ddd;
padding-bottom:5px;
}
</style>
<script type="text/javascript">
	$(function() {
  // 使用默认参数
  $('select').selected();

  // 设置参数
  $('select').selected({
    btnWidth: '100px',
    btnSize: 'sm',
    btnStyle: 'primary',
    
  });
});

function get_code(obj,subgroup_code){	
		var	vals=obj.value;
		
		$.ajax({ url: admin_webroot+"api_methods/ajax_get_category_code/"+vals,
		type:"POST",
		data: {},
		dataType:"json",
		success: function(data){
			$("#pro_cat").find("option").remove();
			$("<option></option>").val('').text('所有').appendTo($("#pro_cat"));
			$(data).each(function(i,item){
				if(subgroup_code==item['ApiCategory']['code']){
					$("<option selected></option>").val(item['ApiCategory']['code']).text(item['ApiCategory']['name']).appendTo($("#pro_cat"));
				}else{
					$("<option></option>").val(item['ApiCategory']['code']).text(item['ApiCategory']['name']).appendTo($("#pro_cat"));
				}
			});
			$("#pro_cat").selected();
		}
  	});

}
</script>

<div style="margin-top:10px;" id="REMOVE">
			<?php echo $form->create('ApiMethod',array('action'=>'/','name'=>'AMethodForm','type'=>'get','class'=>'am-form-horizontal'));?>
				 	<div>
					<ul class=" am-avg-md-2 am-avg-lg-3 am-avg-sm-1">
						<li style="margin:0 0 10px 0">  
							<label class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-form-label">项目</label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7  am-u-end">
										<select class="all" name="project_code" id="project" data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?> '}"  onchange="get_code(this,'<?php echo isset($api_cats)?$api_cats:''; ?>' );">
												<option value=""><?php echo $ld['all_data']?></option>
														<?php  foreach($project_list as $k=>$v){ ?>
														<option value="<?php echo $v;  ?>"  <?php echo @$project_code==$v?'selected':''; ?> ><?php echo $k  ?></option>
														<?php } ?>
										</select>		
							
							</div>
						</li> 
						<li style="margin:0 0 10px 0">  
							<label class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-form-label">项目分类</label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7  am-u-end">
										<select class="all" name="category_code" id="pro_cat"  data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?> '}" >
											<option value=""><?php echo $ld['all_data']?></option>
														
										</select>		
							
							</div>
						</li> 
						<li style="margin:0 0 10px 0">  
							<label class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-form-label-type"><?php echo $ld['type'];?></label>
							<div class="am-u-lg-7 am-u-md-7 am-u-sm-7  am-u-end-type">
								<select name="type"  data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?> '}">
									<option value=""><?php echo $ld['all_data']?> </option>
									<option value="1" <?php echo @$type=='1'?'selected':''; ?>> 免费 </option>
									<option value="0" <?php echo @$type=='0'?'selected':''; ?>> 基础 </option>
									<option value="2" <?php echo @$type=='2'?'selected':''; ?>> 其他</option>
								</select>
							</div>
						</li> 
						<li  style="margin:0 0 10px 0">
							<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text ">关键字</label> 
								<div class="am-u-lg-7  am-u-md-7 am-u-sm-7"  >
								<input type="text" name="keyword" class="am-form-field am-radius"  value="<?php echo @$keyword;?>" placeholder="<?php echo '方法名称/'.'方法代码';?>" />
								</div>
						</li>

						<li style="margin:0 0 10px 0">
											<div class="am-u-sm-3 am-hide-lg-only">&nbsp;</div>
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-6" style="padding-left:16px;" >
												<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>" onclick="search_article()" ><?php echo $ld['search'];?></button>
											</div>
						</li>
    					</ul>
    				</div>
			<?php echo $form->end();?>
			<div class="am-g am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
					<ul class=" am-bt" style="list-style-type:none;">
							<li style="margin:0 10px 0 0; float:right;">
							
									  <button type="button" class="am-btn am-btn-warning am-btn-sm am-radius" data-am-modal="{target: '#addrcateg', closeViaDimmer: 1, width: 700,height:400}" onclick="ajaxloadapicateg()">
 											 项目分类
										</button>

								
							</li>
							<li style="margin:0 10px 0 0; float:right;">
  	  							<button  type="button" class="am-btn am-btn-warning am-btn-sm am-radius" data-am-modal="{target: '#addredittables', closeViaDimmer: 1, width:700,height:400}" onclick="ajaxloadapipro()">
  									项目管理
								</button>

													
							</li>
							
							<li style="margin:0 10px 0 0; float:right;">
								<button type="button" class="am-btn am-btn-warning am-btn-sm am-radius" data-am-modal="{target: '#select_bulk_upload', closeViaDimmer: 1, width:700,height:300}"  onclick="ajax_select_bulk_upload()">
									<?php  echo $ld['bulk_upload'] ?>
								</button>
							</li>
								<li style="margin:0 10px 0 0; float:right;">
								<a class="am-btn am-btn-warning am-btn-sm am-radius"  href="<?php echo $html->url('/api_methods/add'); ?>">
									<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
								</a> 
							</li>	
					</ul>
			</div>
<!--3-->	
	
	<!--左边菜单列表 -->

			<div class="am-u-lg-2 am-u-md-2 am-hide-sm-only">	
				<div class="am-panel-group am-panel-tree" id="accordion" >
						<div class="listtable_div_btm am-panel-header">
							<div class="am-panel-hd">
						      	<div class="am-panel-title">
									 <div class="am-u-lg-12 am-u-md-12 am-u-sm-12">分类名称</div>
									 
									 <div style="clear:both;"></div>
						      	</div>
						    	</div>
						</div>
					<!--一级菜单-->
						<?php foreach($category_list as $k=>$v){  ?>
						<div>
								<div class="listtable_div_top am-panel-body" >
									    	<div class="am-panel-bd fuji">
											<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 " style="word-wrap:break-word;word-break:normal;">
												<span data-am-collapse="{parent: '#accordion', target: '#action_<?php echo $v ?>'}" class="<?php echo (isset($v['SubAction'])&&!empty($v['SubAction']))?"am-icon-plus":"am-icon-minus";?>"></span>&nbsp;
												<?php echo $html->link("{$k}","/api_methods?category_code={$v}",array("style"=>" color:black;"),false,false);?>
											</div>
											<div class="am-u-lg-4 am-u-md-4 am-u-sm-4 seolink am-action ">
		
											</div>
											<div style="clear:both"></div>
										</div>
								</div>
				
						</div>
						<?php } ?>
				</div>
			</div>







<!-- 右边列表-->
	<div class="am-u-lg-10 am-u-md-10 am-u-sm-12" id="right_list">
	<?php echo $form->create('ApiMethod',array('action'=>'/','name'=>'PageForm','type'=>'get',"onsubmit"=>"return false;"));?>
	<div class="am-panel-group am-panel-tree">
				<div class="  listtable_div_btm am-panel-header">
							<div class="am-panel-hd">
											<div class="am-panel-title am-g">
												<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
													<label class="am-checkbox am-success  " style="font-weight:bold;padding-top:0">
														<input type="checkbox" data-am-ucheck onclick='listTable.selectAll(this,"checkbox[]")'/>
																项目名称
													</label>  
												</div>
												<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">分类名称</div>
												<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">方法名称</div>
												<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">方法代码</div>
												<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld["type"]?></div>
												<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">排序</div>
												
												<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['operate']?></div>
											</div>
							</div>
				</div>
<!--2-->	
<?php if(isset($method_infos) && !empty($method_infos)){ ?>												
			<?php foreach($method_infos as $method_info){ ?>
				<div >
				
					<div class="listtable_div_top am-panel-body">
						<div class="am-panel-bd am-g">
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-word" >
												<label class="am-checkbox am-success  " style="padding-top:0">
													<input type="checkbox" name="checkboxs[]" data-am-ucheck  value="<?php echo $method_info['ApiMethod']['id']?>" />
													<div  onclick='listTable.selectAll(this,"checkboxs[]")'><?php foreach($project_list as $k=>$v){  if($v== $method_info['ApiMethod']['api_project_code'] ){echo $k;}   }?></div>
												</label>
											</div>
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-word" style="float:left;">
													<?php foreach($category_list as $k=>$v){  if($v== $method_info['ApiMethod']['api_category_code'] ){echo $k;}else{echo '&nbsp;';}   }?>
											</div>
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-word">
													<div  ><?php echo empty($method_info['ApiMethod']['name'])?'&nbsp;':$method_info['ApiMethod']['name'];  ?></div>
											</div>
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-word">
													<div    ><?php echo empty($method_info['ApiMethod']['code'])?'&nbsp;':$method_info['ApiMethod']['code'];?></div>
											</div>
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-word">
													<div ><?php switch($method_info['ApiMethod']['type']){ case '0':echo '免费';break; case '1':echo '收费';break;}?></div>
											</div>
											<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-word">
											<div   ><?php echo empty($method_info['ApiMethod']['orderby'])?'&nbsp;':$method_info['ApiMethod']['orderby'];?></div>

											</div>
											<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 seolink am-btn-group-xs am-action">	
											
    	
			
    	
																		
												 <a  class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit"  href="<?php echo $html->url('/api_methods/view/'.$method_info['ApiMethod']['id']); ?>" >
								                        <span class="am-icon-pencil-square-o"> <?php echo $ld['edit']; ?></span>
								                      </a>                
												<button type="button" class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" 
												  onclick="list_delete_submit(admin_webroot+'api_methods/remove/<?php echo $method_info['ApiMethod']['id'] ?>');" >
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
		<?php echo $form->end();?>
</div>
<!--右边列表结束 -->
<!--3-->
</div>
	
	
<!-- 项目弹层-->	
<div class="am-modal am-modal-no-btn" tabindex="-1" id="addredittables" style="width: 800px;">
  								<div class="am-modal-dialog" >
    									<div class="am-modal-hd" ><span id="modal-title">项目管理</span>
      									<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    									</div>
    									<div class="am-modal-bd" id="api_pro_show" >
    
    								
									</div>
								</div>						
</div>
<!--分类弹层-->		

<div class="am-modal am-modal-no-btn" tabindex="-1" id="addrcateg" >
	 <div class="am-modal-dialog" >
	    	<div class="am-modal-hd"><span id="modal-titles">项目分类管理</span>
	      	<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
		</div>
		<div class="am-modal-bd"  id="api_categ_show">
	
		</div>

	</div>
</div>
<!-- 批量上传选择弹层-->	
<div class="am-modal am-modal-no-btn" tabindex="-1" id="select_bulk_upload">
  								<div class="am-modal-dialog" >
    									<div class="am-modal-hd" ><span id="modal-title">批量上传管理</span>
      									<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    									</div>
    									<div class="am-modal-bd" id="bulk_upload_show" >
    
    								
									</div>
								</div>						
</div>								
<script type="text/javascript">
function search_article()
{
document.AMethodForm.action=admin_webroot+"api_methods/";
document.AMethodForm.onsubmit= "";
document.AMethodForm.submit();
}
	

/*
	加载
*/
function ajax_select_bulk_upload(){
		$.ajax({ url: admin_webroot+"api_methods/select_upload/",
		type:"GET",
		dataType:"html",
		success: function(data){
			$("#bulk_upload_show").html(data);
  		}
  	});
}	







function ajaxloadapipro(){

	$.ajax({ url: admin_webroot+"api_methods/api_project/",
		type:"GET",
		dataType:"html",
		success: function(data){
			$("#api_pro_show").html(data);
  		}
  	});
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
</script>