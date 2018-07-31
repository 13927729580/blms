
<style type="text/css">
.red{
color:red;
margin-top:18px;
float:left;
}
label{font-weight:normal;}
@media only screen and (max-width: 640px){body {word-wrap: normal;}}
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
.btnouter{margin:50px;}
.am-selected.am-dropdown .am-selected-list{
max-width:550px;
}
.am-list>li{margin-bottom:0;border-style: none;}
.admin-sidebar-list li a{color:#fff;background-color: #5eb95e;}
.am-list > li > a.am-active, .am-list > li > a.am-active:hover, .am-list > li > a.am-active:focus{font-weight: bold;}
.scrollspy-nav.am-sticky.am-animation-slide-top{width: 100%;}
.am-sticky-placeholder{margin-top: 10px;}
.scrollspy-nav {top: 0;z-index: 100;background: #5eb95e;width: 100%;padding: 0 10px}
.scrollspy-nav ul {margin: 0;padding: 0;}
.scrollspy-nav li {display: inline-block;list-style: none;}
.scrollspy-nav a {color: #eee;padding: 10px 20px;display: inline-block;}
.scrollspy-nav a.am-active {color: #fff;font-weight: bold;}
.crumbs{padding-left:0;margin-bottom:22px;}
.btnouter{margin:0;}
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
			$("#apimethod_api_category_code").find("option").remove();
			$("<option></option>").val('').text(j_please_select).appendTo($("#apimethod_api_category_code"));
			$(data).each(function(i,item){
				if(subgroup_code==item['ApiCategory']["code"]){
					$("<option selected></option>").val(item['ApiCategory']["code"]).text(item['ApiCategory']["name"]).appendTo($("#apimethod_api_category_code"));
				}else{
					$("<option></option>").val(item['ApiCategory']["code"]).text(item['ApiCategory']["name"]).appendTo($("#apimethod_api_category_code"));
				}
			});
			$("#apimethod_api_category_code").selected();
		}
  	});

}				



</script>


<div class="am-g">
			
	<div class="am-panel-group admin-content am-detail-view" id="accordion" style="width: 95%;margin-right: 2.5%;" >
		<form action="" method="post"  id="loadajaxadd"   accept-charset="utf-8"><div style="display:none;"><input type="hidden" name="_method" value="POST" /></div>
		<!-- 导航 -->
			<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
			    <ul>
					<li><a href="#basic_information">基本信息</a></li>
				</ul>
			</div>
			<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
			    <button style="margin-right: 0;" type="button" class="am-btn am-btn-success am-btn-sm am-radius" value="" onclick="addajax()"><?php echo $ld['d_submit'];?></button>
				<button style="margin-right: 0;" type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
			</div>
			<!-- 导航结束 -->			
		<div id="basic_information"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">基本信息</h4>	
			    </div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
								
							<div class="am-form-group">
							     		 <label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">API项目名称</label>
									<div class="am-u-lg-9 am-u-md-9 am-u-sm-9 " >
										<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
										      <select name="data[ApiMethod][api_project_code]"  onchange="get_code(this,'<?php echo isset($method_info['ApiMethod']['api_category_code'])?$method_info['ApiMethod']['api_category_code']:''; ?>');"  class="am-form-field am-radius" >
														<?php  foreach($project_list as $k=>$v){ ?>
														<option   value="<?php echo $v;  ?>"   <?php echo isset($method_info['ApiMethod']['api_project_code']) && $method_info['ApiMethod']['api_project_code']==$v?'selected':''; ?> ><?php echo $k  ?></option>
														<?php } ?>
											</select>
										 </div>
							<span class="red">* </span>

									 </div>
					    			</div>
									
								
								<div class="am-form-group">
						    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">API项目分类名称</label>
						    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
						    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
						    						<select name="data[ApiMethod][api_category_code]" id="apimethod_api_category_code" class="am-form-field am-radius">
													<option value="">请选择</option>
												</select>
						    						
						    				</div>	<span class="red">* </span>
							  		</div>
						    		</div>	
						    		
						    		<div class="am-form-group">
						    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">方法代码</label>
						    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
						    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
						    					<input type="text"  name="data[ApiMethod][code]" value="<?php echo $method_info['ApiMethod']['code']; ?>" />
						    				</div>	<span class="red">* </span>
							  		</div>
						    		</div>	
						    		
						    	
						    			
						    		<div class="am-form-group">
						    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">方法名称</label>
						    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
						    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
						    					<input type="text"  name="data[ApiMethod][name]" value="<?php echo $method_info['ApiMethod']['name']; ?>" />
						    				</div>	<span class="red">* </span>
							  		</div>
						    		</div>	
						    		
						    			<div class="am-form-group">
						    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">描述</label>
						    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
						    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
						    				<textarea name="data[ApiMethod][description]" ><?php echo $method_info['ApiMethod']['description']; ?></textarea>
						    				</div>
							  		</div>
						    		</div>	

								<div class="am-form-group">
					    				<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"  style="margin-top:2px;">类型</label>
					    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
					    			
					    					
					    					<label class="am-radio-inline am-success" style="padding-top:2px;margin-left: 20px;">
					    					<input type="radio" class="radio"  data-am-ucheck value="1" name="data[ApiMethod][type]" <?php echo isset($method_info['ApiMethod']['type']) && $method_info['ApiMethod']['type']=='1' ?'checked':'';   ?>  /> 收费 </label> 
										<label class="am-radio-inline am-success" style="padding-top:2px;">
										<input type="radio" class="radio"  data-am-ucheck name="data[ApiMethod][type]" value="0" <?php echo (isset($method_info['ApiMethod']['type']) && $method_info['ApiMethod']['type']=='0')||!isset($method_info['ApiMethod']['type']) ?'checked':'';   ?>   />免费</label>
					    					
						    			</div>
									
					    			</div>
					    				<div class="am-form-group">
						    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">排序</label>
						    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
						    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
						    				<input type="text"  name="data[ApiMethod][orderby]" value="<?php echo isset($method_info['ApiMethod']['orderby']) && !empty($method_info['ApiMethod']['orderby'])?$method_info['ApiMethod']['orderby']:'50'; ?>" />

						    				</div>
							  		</div>
						    		</div>	
					    					<input type="hidden" name="data[ApiMethod][id]" value="0" />
					    				
					  	     		
					</div>
				</div>
		</form>
	</div>
</div>
<script type="text/javascript" >
function loadindex(){
					//	window.location.href="http://allinone.products.seevia.cn/admin/api_methods/index";
						window.location.href=admin_webroot+"/api_methods/index";

}
	
function addajax(){
var id = 0;
	var codes=document.getElementsByName("data[ApiMethod][code]")?document.getElementsByName("data[ApiMethod][code]")[0].value:0;
		var code=codes.replace( /^\s*/, '');

	var names=document.getElementsByName("data[ApiMethod][name]")?document.getElementsByName("data[ApiMethod][name]")[0].value:0;
		var name=names.replace( /^\s*/, '');

	var api_category_codes=document.getElementsByName("data[ApiMethod][api_category_code]")?document.getElementsByName("data[ApiMethod][api_category_code]")[0].value:0;
	var api_category_code=api_category_codes.replace( /^\s*/, '');

	var api_project_codes=document.getElementsByName("data[ApiMethod][api_project_code]")?document.getElementsByName("data[ApiMethod][api_project_code]")[0].value:0;
	var api_project_code=api_project_codes.replace( /^\s*/, '');
	var descriptions=document.getElementsByName("data[ApiMethod][description]")?document.getElementsByName("data[ApiMethod][description]")[0].value:0;
	var description=descriptions.replace( /^\s*/, '');
		var orderbys=document.getElementsByName("data[ApiMethod][orderby]")?document.getElementsByName("data[ApiMethod][orderby]")[0].value:0;
	var orderby=orderbys.replace( /^\s*/, '');
	var type=$('input[name="data[ApiMethod][type]"]:checked').val();
	if(code==''){
	alert('请填写方法代码');return false;
	}else if(name==''){
	alert('请填写方法名称');return false;
	}else if(api_category_code==''){
	alert('请选择项目分类名称');return false;
	}else if(api_project_code==''){
	alert('请选择项目名称');return false;
	}else if(type==''){
	alert('请选择类型');return false;
	}else{
	$.ajax({ url: admin_webroot+"api_methods/add/",
		type:"POST",
		data:{
					'data[ApiMethod][id]':id,
					'data[ApiMethod][api_project_code]':api_project_code,		
					'data[ApiMethod][api_category_code]':api_category_code,
					'data[ApiMethod][code]':code,
					'data[ApiMethod][name]':name,

					'data[ApiMethod][type]':type,
					'data[ApiMethod][description]':description,
			
				},
		
		dataType:"json",
		success: function(data){
		if(data.code==1){
			loadindex();
		}else{
			alert("项目名称或代码重复");
		}
  		}
  	});
  	}
}
					
						
</script>