 <style>
.red{
color:red;
margin-top:18px;
float:left;
}
.am-form-label {
    font-weight: bold;
    margin-left: 10px;
    top: 0px;
 }
.am-form-group{margin-top:10px;}

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
    btnWidth: '200px',
    btnSize: 'sm',
    btnStyle: 'primary',
    
  });
});

	function get_code(obj,categorycode){	
		var	vals=obj.value;
		$.ajax({ url: admin_webroot+"api_methods/ajax_get_category_code/"+vals,
		type:"POST",
		data: {},
		dataType:"json",
		success: function(data){
			//alert(data);
			$("#apimethod_api_category_code").find("option").remove();
			$("<option></option>").val(' ').text(j_please_select).appendTo($("#apimethod_api_category_code"));
			$(data).each(function(i,item){
				if(categorycode==item['ApiCategory']["code"]){
					$("<option selected></option>").val(item['ApiCategory']["code"]).text(item['ApiCategory']["name"]).appendTo($("#apimethod_api_category_code"));			
				}else{
					$("<option></option>").val(item['ApiCategory']["code"]).text(item['ApiCategory']["name"]).appendTo($("#apimethod_api_category_code"));
				}
			});
		//	$("#apimethod_api_category_code").selected();
		}
  	});
}				
</script>


<script src="<?php echo $webroot; ?>plugins/ajaxfileupload.js" type="text/javascript"></script>
<div class="am-g admin-content am-user  ">
        <form action=" " id="loadajaxview"  method="post" accept-charset="utf-8"><div style="display:none;"><input type="hidden" name="_method" value="POST" /></div>
		<div class="am-panel-group admin-content am-detail-view" id="accordion" style="width: 98%;margin-right: 1%;">
			<!-- 导航 -->
			<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
			    <ul>
					<li><a data-am-collapse="{parent: '#accordion', target: '#basic_information'}" href="#basic_information"><?php echo $ld['basic_information']?></a></li>

					<li><a data-am-collapse="{parent: '#accordion', target: '#api_request'}" href="#api_request">请求参数</a></li>
					<li><a href="#api_response" data-am-collapse="{parent: '#accordion', target: '#api_response'}">响应参数</a></li>
					<li><a href="#api_request_exple" data-am-collapse="{parent: '#accordion', target: '#api_request_exple'}">请求示例</a></li>

					<li><a href="#api_response_exple" data-am-collapse="{parent: '#accordion', target: '#api_response_exple'}">响应示例</a></li>
					<li><a href="#api_response_excep" data-am-collapse="{parent: '#accordion', target: '#api_response_excep'}">异常示例</a></li>
					<li><a href="#api_error_code" data-am-collapse="{parent: '#accordion', target: '#api_error_code'}">错误代码及解释</a></li>
					<li><a href="#api_faq" data-am-collapse="{parent: '#accordion', target: '#api_faq'}">FAQ</a></li>
				</ul>
			</div>
			<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
			    <button style="margin-right: 0;" type="button" class="am-btn am-btn-success am-btn-sm am-radius" value="" onclick="addajax()"><?php echo $ld['d_submit'];?></button>
				<button style="margin-right: 0;" type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
			</div>
			<!-- 导航结束 -->
            <!-- 编辑按钮区域 -->
	  <div class="am-panel am-panel-default">
	    <div class="am-panel-hd">
	      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#basic_information'}"><?php echo $ld['basic_information'] ?></h4>
	    </div>
	    <div id="basic_information" class="am-panel-collapse am-collapse am-in">
	      <div class="am-panel-bd am-form-detail am-form am-form-horizontal">

                        
                        
                        	<div class="am-form-group">
							     		 <label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">API项目名称</label>
										      <div class="am-u-lg-9 am-u-md-10 am-u-sm-9 " >
												<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
										      <select data-am-selected name="data[ApiMethod][api_project_code]"  class="am-form-field am-radius"  onchange="get_code(this,'<?php echo isset($method_info['ApiMethod']['api_category_code'])? $method_info['ApiMethod']['api_category_code']:'';  ?>')"  >
													
														<?php  foreach($project_list as $k=>$v){ ?>
														<option   value="<?php echo $v;  ?>"   <?php echo isset($method_info['ApiMethod']['api_project_code']) && $method_info['ApiMethod']['api_project_code']==$v?'selected':''; ?> ><?php echo $k  ?></option>
														<?php } ?>
													</select>
										      </div><span class="red">* </span>
										    </div>
					    				</div>
									
								
								<div class="am-form-group">
						    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">API项目分类名称</label>
						    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
						    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
						    						<select data-am-selected name="data[ApiMethod][api_category_code]" id="apimethod_api_category_code" class="am-form-field am-radius">
													<option value="">请选择</option>
												</select>
						    						
						    				</div><span class="red">* </span>
							  		</div>
						    		</div>	
						    		
						    		<div class="am-form-group">
						    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">方法代码</label>
						    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
						    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
						    					<input type="text"  name="data[ApiMethod][code]" value="<?php echo $method_info['ApiMethod']['code']; ?>" />
						    				</div><span class="red">* </span>
							  		</div>
						    		</div>	
						    		
						    	
						    			
						    		<div class="am-form-group">
						    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">方法名称</label>
						    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
						    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
						    					<input type="text"  name="data[ApiMethod][name]" value="<?php echo $method_info['ApiMethod']['name']; ?>" />
						    				</div><span class="red">* </span>
							  		</div>
						    		</div>	
						    		
						    			<div class="am-form-group">
						    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">描述</label>
						    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
						    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
						    				<textarea name="data[ApiMethod][description]" ><?php echo $method_info['ApiMethod']['description']; ?></textarea>
						    				</div>
							  		</div>
						    		</div>	

								<div class="am-form-group">
					    				<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"  style="margin-top:2px;">类型</label>
					    				<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
					    			
					    					<label class="am-radio-inline am-success" style="padding-top:2px;margin-left: 20px;">
					    					<input type="radio" class="radio"  data-am-ucheck value="1" name="data[ApiMethod][type]" <?php echo isset($method_info['ApiMethod']['type']) &&	$method_info['ApiMethod']['type']=='1' ?'checked':'';   ?> /> 收费 </label> 
										<label class="am-radio-inline am-success" style="padding-top:2px;">
										<input type="radio" class="radio"  data-am-ucheck name="data[ApiMethod][type]" value="0" <?php echo isset($method_info['ApiMethod']['type']) &&	$method_info['ApiMethod']['type']=='0'?'checked':'';   ?> />免费</label>
					    					
						    			
									</div>
					    			</div>
					    			<div class="am-form-group">
						    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label">排序</label>
						    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
						    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" style="margin-top:10px;">
						    				<input type="text"  name="data[ApiMethod][orderby]" value="<?php echo $method_info['ApiMethod']['orderby']; ?>" />

						    				</div>
							  		</div>
						    		</div>
					    					<input type="hidden" name="data[ApiMethod][id]" value="<?php echo $method_info['ApiMethod']['id']; ?>" />
	      </div>
	    </div>
	  </div>
	  		  
	  		  <script type="text/javascript" >
function loadindex(){
					//	window.location.href="http://allinone.products.seevia.cn/admin/api_methods/index";
						window.location.href=admin_webroot+"/api_methods/index";

}
	
function addajax(){
		var id=document.getElementsByName("data[ApiMethod][id]")?document.getElementsByName("data[ApiMethod][id]")[0].value:0;
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
	$.ajax({ url: admin_webroot+"api_methods/view/",
		type:"POST",
		data:{
					'data[ApiMethod][id]':id,
					'data[ApiMethod][description]':description,

					'data[ApiMethod][api_project_code]':api_project_code,
					'data[ApiMethod][name]':name,
					'data[ApiMethod][type]':type,
					'data[ApiMethod][api_category_code]':api_category_code,
					'data[ApiMethod][code]':code,
					'data[ApiMethod][orderby]':orderby,
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
<!-- 请求参数-->
	  <div class="am-panel am-panel-default " style="margin-top: 5px;">
	    <div class="am-panel-hd">
	      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#api_request'}">请求参数</h4>
	    </div>
	    <div id="api_request" class="am-panel-collapse am-collapse ">
	    	<div class="am-panel-bd  am-form-detail am-form am-form-horizontal"  id="api_request_show">
	    		
	    </div>
	  </div>
	 </div>
	
<!-- 响应参数-->
	<div class="am-panel am-panel-default">
	    <div class="am-panel-hd">
	      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#api_response'}">响应参数</h4>
	    </div>
	    <div id="api_response" class="am-panel-collapse am-collapse ">
	    	<div class="am-panel-bd  am-form-detail am-form am-form-horizontal" id="api_response_show">
	    	
	    		
	    </div>
	  </div>	  			
	 </div>
	
	
		<!-- 请求示例-->
	<div class="am-panel am-panel-default">
	    <div class="am-panel-hd">
	      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#api_request_exple'}">请求示例</h4>
	    </div>
	    <div id="api_request_exple" class="am-panel-collapse am-collapse ">
	    	<div class="am-panel-bd  am-form-detail am-form am-form-horizontal" id="api_request_example_show">
	    		
	    	
	    	</div>
	  </div>	
	 </div>
	
	
	<!-- 响应示例-->	
		<div class="am-panel am-panel-default">
			<div class="am-panel-hd">
				<h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#api_response_exple'}">响应示例</h4>
			</div>
			<div id="api_response_exple" class="am-panel-collapse am-collapse ">
				<div class="am-panel-bd  am-form-detail am-form am-form-horizontal" id="api_response_example_show">
			</div>
			</div>	
		</div>
	
	<!-- 异常示例-->
	<div class="am-panel am-panel-default">
	    <div class="am-panel-hd">
	      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#api_response_excep'}">异常示例</h4>
	    </div>
	    <div id="api_response_excep" class="am-panel-collapse am-collapse ">
	    	<div class="am-panel-bd  am-form-detail am-form am-form-horizontal" id="api_response_exception_show">
	    	
	    	
	    </div>
	  </div>	
	 </div>
	
	<!-- 错误代码-->
	<div class="am-panel am-panel-default">
		<div class="am-panel-hd">
			<h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#api_error_code'}">错误代码及解释</h4>
		</div>
		<div id="api_error_code" class="am-panel-collapse am-collapse ">
			<div class="am-panel-bd  am-form-detail am-form am-form-horizontal" id="api_error_code_show">
			</div>
		</div>	  			
	</div>
	
		<!-- FAQ-->
	<div class="am-panel am-panel-default">
	    <div class="am-panel-hd">
	      <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#api_faq'}">FAQ</h4>
	    </div>
	    <div id="api_faq" class="am-panel-collapse am-collapse ">
	    	<div class="am-panel-bd  am-form-detail am-form am-form-horizontal" id="api_faq_show">
	    	
	    		
	    </div>
	  </div>	  			
	 </div>
	
	 			
</div>
</form>


	
</div>
	<!---请求参数-->
<button id="method_request_btn" class="am-btn am-btn-primary am-radius am-btn-sm" style="display:none;" data-am-modal="{target: '#method_request_popup', closeViaDimmer:1,width:600,height:500}">Modal</button>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="method_request_popup">
  <div class="am-modal-dialog" >
    <div class="am-modal-hd"><span id="modal_title_request">请求参数管理</span>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd"></div>
  </div>
</div>
	
	<!---请求示例-->
<button id="method_request_example_btn" class="am-btn am-btn-primary am-radius am-btn-sm" style="display:none;" data-am-modal="{target: '#method_request_example_popup', closeViaDimmer:0,width:600,height:300}">Modal</button>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="method_request_example_popup">
  <div class="am-modal-dialog" >
    <div class="am-modal-hd"><span id="modal_title_request_example">请求示例管理</span>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd"></div>
  </div>
</div>
		<!---响应参数-->
<button id="method_response_btn" class="am-btn am-btn-primary am-radius am-btn-sm" style="display:none;" data-am-modal="{target: '#method_response_popup', closeViaDimmer:0,width:600,height:450}">Modal</button>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="method_response_popup">
  <div class="am-modal-dialog" >
    <div class="am-modal-hd"><span id="modal_title_response">响应参数示例管理</span>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd"></div>
  </div>
</div>
		<!---响应示例-->
<button id="method_response_example_btn" class="am-btn am-btn-primary am-radius am-btn-sm" style="display:none;" data-am-modal="{target: '#method_response_example_popup', closeViaDimmer:0,width:600,height:250}">Modal</button>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="method_response_example_popup">
  <div class="am-modal-dialog" >
    <div class="am-modal-hd"><span id="modal_title_response_example">响应示例管理</span>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd"></div>
  </div>
</div>
				<!---异常示例-->
<button id="method_response_exception_btn" class="am-btn am-btn-primary am-radius am-btn-sm" style="display:none;" data-am-modal="{target: '#method_response_exception_popup', closeViaDimmer:0,width:600,height:250}">Modal</button>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="method_response_exception_popup">
  <div class="am-modal-dialog" >
    <div class="am-modal-hd"><span id="modal_title_response_exception">异常示例管理</span>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd"></div>
  </div>
</div>

		<!---错误代码-->
<button id="method_error_code_btn" class="am-btn am-btn-primary am-radius am-btn-sm" style="display:none;" data-am-modal="{target: '#method_error_code_popup', closeViaDimmer:0,width:600,height:200}">Modal</button>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="method_error_code_popup">
  <div class="am-modal-dialog" >
    <div class="am-modal-hd"><span id="modal_title_error_code">错误代码管理</span>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd"></div>
  </div>
</div>
	<!--FAQ管理-->
<button id="method_faq_btn" class="am-btn am-btn-primary am-radius am-btn-sm" style="display:none;" data-am-modal="{target: '#method_faq_popup', closeViaDimmer:0,width:600,height:300}">Modal</button>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="method_faq_popup">
  <div class="am-modal-dialog" >
    <div class="am-modal-hd"><span id="modal_title_faq">FAQ管理</span>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd"></div>
  </div>
</div>
<style type="text/css">
.am-g.admin-content{margin:0 auto;}
.am-form-label{text-align:right;}
.am-form .am-form-group:last-child{margin-bottom:0;}
#rank_operator{display:none;}
#rank_operator select{width:50%;}
#rank_operator em{float: left;margin: 0 5px;position: relative;top: 5px;}
#rank_operator input[type="button"]{margin-right:1.2rem;}
#user_order{padding:0;}
#user_style_pop{height:600px;top:50%; }
#user_style_pop .am-modal-bd{overflow-y:auto;overflow-x: hidden;height:430px;}
#user_style_pop .radio-btn{margin-top:0px;}
#user_style_pop .radio-btn label{margin:5px 5px;}
#user_style_pop .radio-btn .am-btn{background:#e6e6e6;border-color:#e6e6e6;color:#000; padding:0.375em 0.75em}
#user_style_pop .radio-btn .am-btn.am-active{background:#c7c7c7;border-color:#c7c7c7;}
#user_style_pop .radio-btn .am-btn:hover{background:#c7c7c7;border-color:#c7c7c7;}
#user_balance{display: inline-block;position: relative;left:5px;top:5px;}
.user_avatar img{max-width:150px;max-height:150px;width:100%;}
#user_address_popup .am-close{margin-right:1em;}
p.user_config_file{margin:5px auto;}
p.user_config_file img{max-width:500px;}
</style>
<script type="text/javascript">
function ajaxloadindex(){

	$.ajax({ url: admin_webroot+"api_methods/index/",
		type:"GET",
		dataType:"html",
		success: function(data){
			$("#REMOVE").html(data);
  		}
  	});
}	
	
	

	
	
	
	//ajax_请求参数
ajaxloadrequest();
function ajaxloadrequest(){
	var main_id=document.getElementsByName("data[ApiMethod][id]")[0].value;
	$.ajax({ url: admin_webroot+"api_methods/api_method_request/"+main_id,
		type:"POST",
		dataType:"html",
		success: function(data){
			$("#api_request_show").html(data);
  		}
  	});
}
	//ajax_请求示例
ajaxloadrequestexample();
function ajaxloadrequestexample(){
	var main_id=document.getElementsByName("data[ApiMethod][id]")[0].value;
	$.ajax({ url: admin_webroot+"api_methods/api_method_request_example/"+main_id,
		type:"POST",
		dataType:"html",
		success: function(data){
			$("#api_request_example_show").html(data);
  		}
  	});
}
	//ajax_响应参数
ajaxloadresponse();
function ajaxloadresponse(){
	var main_id=document.getElementsByName("data[ApiMethod][id]")[0].value;
	$.ajax({ url: admin_webroot+"api_methods/api_method_response/"+main_id,
		type:"POST",
		dataType:"html",
		success: function(data){
			$("#api_response_show").html(data);
  		}
  	});
}
	//ajax_响应示例
ajaxloadresponseexample();
function ajaxloadresponseexample(){
	var main_id=document.getElementsByName("data[ApiMethod][id]")[0].value;
	$.ajax({ url: admin_webroot+"api_methods/api_method_response_example/"+main_id,
		type:"POST",
		dataType:"html",
		success: function(data){
			$("#api_response_example_show").html(data);
  		}
  	});
}
	//ajax_异常示例
ajaxloadresponseexception();
function ajaxloadresponseexception(){
	var main_id=document.getElementsByName("data[ApiMethod][id]")[0].value;
	$.ajax({ url: admin_webroot+"api_methods/api_method_response_exception/"+main_id,
		type:"POST",
		dataType:"html",
		success: function(data){
			$("#api_response_exception_show").html(data);
  		}
  	});
}
//ajax_错误代码
ajaxloaderrorcode();
function ajaxloaderrorcode(){
	var main_id=document.getElementsByName("data[ApiMethod][id]")[0].value;
	$.ajax({ url: admin_webroot+"api_methods/api_method_error_code/"+main_id,
		type:"POST",
		dataType:"html",
		success: function(data){
			$("#api_error_code_show").html(data);
  		}
  	});
}
//ajax_FAQ
ajaxloadfaq();
function ajaxloadfaq(){
	var main_id=document.getElementsByName("data[ApiMethod][id]")[0].value;
	$.ajax({ url: admin_webroot+"api_methods/api_method_faq/"+main_id,
		type:"POST",
		dataType:"html",
		success: function(data){
			$("#api_faq_show").html(data);
  		}
  	});
}



	



</script>