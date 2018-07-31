<style>
	#course_chapter_list .admin-user-img{
		display:none;
	}
	ol.am-breadcrumb.am-hide-md-down.am-color{
	    max-width:1200px;
	    margin:0 auto;
	    padding:0;
	    margin-bottom:1rem;
    }
    .am-selected{
		width:100%;
	}
</style>
<script src="<?php echo $webroot.'plugins/ajaxfileupload.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<div style="max-width:1200px;margin:20px auto;">
	<div class="am-u-lg-12" style="padding:0;">
	<form class="am-form">
		<?php //pr($activity_config_info); ?>
		<h3 style="font-size:18px;font-weight:400;margin-top:4px;border-bottom:1px solid #ccc;padding-left:5px;margin-bottom:1rem;">
	    	<span>报名成员信息</span>
	    </h3>
		<?php if(isset($activity_config_info)&&count($activity_config_info)>0){foreach ($activity_config_info as $k => $v) { //pr($v)?>
			<div style="line-height:37px;margin-bottom:1rem;">
				<div class="am-u-lg-4 am-text-right" style="font-size:16px;"><?php echo $v['ActivityConfig']['name'] ?></div>
				<div class="am-u-lg-6">
					<?php if($v['ActivityConfig']['type'] == 'text'){ ?>
						<input type="text" value="<?php echo $config_value_info[$v['ActivityConfig']['id']]['ActivityUserConfig']['config_value'] ?>">
					<?php }else if($v['ActivityConfig']['type'] == 'image'){ ?>
						<div class="am-form-group am-form-file" style="margin:0;">
							<button type="button" class="am-btn am-btn-default am-btn-sm">
	   						 	<i class="am-icon-cloud-upload"></i> 选择要上传的文件
	   						</button>
							<input type="file" name="activity_pic" multiple onchange="ajax_upload_media(this,this.id)" id="activity_pic" class="upload-img">
							<input type="hidden" name="activity_picture" value="">
						</div>
						<img src="<?php echo $config_value_info[$v['ActivityConfig']['id']]['ActivityUserConfig']['config_value'] ?> " alt="" style="width:60%;margin-top:10px;">
					<?php } ?>
				</div>
				<div class="am-cf"></div>
			</div>
		<?php }} ?>
		</form>
		<div>
			<div class="am-u-lg-4">&nbsp;</div>
			<div class="am-u-lg-6"><button class="am-btn am-btn-primary">提交</button></div>
			<div class="am-cf"></div>
		</div>
	</div>
	<div class="am-cf"></div>
</div>
<script>
	function ajax_upload_media(obj,obj_id){
		if($(obj).val()!=""){
			var fileName_arr=$(obj).val().split('.');
			var fileType=fileName_arr[fileName_arr.length-1];
			var fileTypearray=Array('jpg','JPG','jpeg','JPEG','gif','GIF','png','PNG');
			// if(in_array(fileType,fileTypearray)){
			// 	ajaxFileUpload(obj_id);
			// }else{
			// 	alert('文件类型不支持');
			// }
			ajaxFileUpload(obj_id);
		}
	}

	function ajaxFileUpload(img_id){
		var org_id = $("#org_id").val();
		 $.ajaxFileUpload({
			  url:'/organizations/ajax_upload_media',
			  secureuri:false,
			  fileElementId:img_id,
			  data:{'org_id':org_id,'org_code':img_id},
			  dataType: 'json',
			  success: function (data){
			  	 $('#'+img_id).siblings('input[type="hidden"]').val(data.img_url);
			  	 $('#'+img_id).parent().siblings("img").attr('src',data.img_url);
			  	 $('#'+img_id).parent().siblings("img").show();
			  	 $('#'+img_id).parent().siblings("figure").hide();
			  }
		 });
		return false;
	}
</script>