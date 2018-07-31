<style type="text/css">
	.am-form-group{margin:20px 0;} 

		.red{
color:red;
margin-top:14px;
float:left;
}
</style>	

	<div class="am-g">
	<form  method="POST" name="select_upload">
	
							<div class="am-form-group am-cf" >
						    			<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="padding-top:15px">批量上传</label>
						    			<div class="am-u-lg-8 am-u-md-8 am-u-sm-8 ">
						    						<select name="data[select][option]" id="select_upload" class="am-form-field am-radius">
													<option value="">请选择</option>
									<?php if(  isset($project_profile_id) && !empty($project_profile_id)   ) {  ?>
													<option value="项目">项目</option>
									<?php } ?>						
									<?php if(  isset($category_profile_id) && !empty($category_profile_id)   ) {  ?>
													<option value="分类">分类</option>
									<?php } ?>
									<?php if(  isset($method_profile_id) && !empty($method_profile_id)   ) {  ?>	
													<option value="方法">方法</option>
									<?php } ?>
									<?php if(  isset($object_profile_id) && !empty($object_profile_id)   ) {  ?>					
													<option value="对象">对象</option>
									<?php } ?>					
									<?php if(  isset($object_field_profile_id) && !empty($object_field_profile_id)   ) {  ?>					
													<option value="对象字段">对象字段</option>
									<?php } ?>					
									<?php if(  isset($error_code_interpretation_profile_id) && !empty($error_code_interpretation_profile_id)   ) {  ?>					
													<option value="错误代码解释">错误代码解释</option>
									<?php } ?>					
									<?php if(  isset($project_common_parameter_profile_id) && !empty($project_common_parameter_profile_id)   ) {  ?>					
													<option value="公共参数">公共参数</option>
									<?php } ?>					
									<?php if(  isset($method_request_profile_id) && !empty($method_request_profile_id)   ) {  ?>					
													<option value="请求参数">请求参数</option>
									<?php } ?>					
									<?php if(  isset($method_response_profile_id) && !empty($method_response_profile_id)   ) {  ?>					
													<option value="响应参数">响应参数</option>
									<?php } ?>					
									<?php if(  isset($method_request_example_profile_id) && !empty($method_request_example_profile_id)   ) {  ?>					
													<option value="请求示例">请求示例</option>
									<?php } ?>					
									<?php if(  isset($method_response_example_profile_id) && !empty($method_response_example_profile_id)   ) {  ?>					
													<option value="响应示例">响应示例</option>
									<?php } ?>					
									<?php if(  isset($method_response_exception_profile_id) && !empty($method_response_exception_profile_id)   ) {  ?>					
													<option value="异常示例">异常示例</option>
									<?php } ?>					
									<?php if(  isset($method_error_code_profile_id) && !empty($method_error_code_profile_id)   ) {  ?>					
													<option value="错误代码">错误代码</option>
									<?php } ?>					
									<?php if(  isset($method_faq_profile_id) && !empty($method_faq_profile_id)   ) {  ?>					
													<option value="FAQ">FAQ</option>
														
												</select>
						    			<?php } ?>			
						    			</div><span class="red">* </span>
						    	</div>
						   
					<div class="am-form-group am-cf" >
						<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
						<div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
							<button type="button" class="am-btn am-btn-success am-btn-sm am-radius" value="" onclick="select_ajax()" ><?php echo $ld['d_submit'];?></button>
							<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
						</div>
	      			</div>
			
	</form>
	</div>
	  <script type="text/javascript" >	
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
	  	function select_ajax(){
				var select_option=document.getElementsByName("data[select][option]")?document.getElementsByName("data[select][option]")[0].value:0;
				switch(select_option){
					case '项目':
					window.location.href='/admin/api_methods/project_upload';
					break;
					case '分类':
					window.location.href='/admin/api_methods/category_upload';
					break;
					case '方法':
					window.location.href='/admin/api_methods/method_upload';
					break;
					case '对象':
					window.location.href='/admin/api_methods/object_upload';
					break;
					case '对象字段':
					window.location.href='/admin/api_methods/object_field_upload';
					break;
					case '错误代码解释':
					window.location.href='/admin/api_methods/error_code_interpretation_upload';
					break;
					case '公共参数':
					window.location.href='/admin/api_methods/common_parameter_upload';
					break;
					case '请求参数':
					window.location.href='/admin/api_methods/method_request_upload';
					break;
					case '响应参数':
					window.location.href='/admin/api_methods/method_response_upload';
					break;
					case '请求示例':
					window.location.href='/admin/api_methods/method_request_example_upload';
					break;
					case '响应示例':
					window.location.href='/admin/api_methods/method_response_example_upload';
					break;
					case '异常示例':
					window.location.href='/admin/api_methods/method_response_exception_upload';
					break;
					case '错误代码':
					window.location.href='/admin/api_methods/method_error_code_upload';
					break;
					case 'FAQ':
					window.location.href='/admin/api_methods/method_faq_upload';
					break;
				
				
				}
				
		
		}
	 </script>