<style>
.am-form-group {margin-bottom:0px;}
.btnouter{margin:50px;}
</style>
<div>   
	<div class="am-text-right  am-btn-group-xs" style="margin-right:10px;margin-bottom:10px">
		<?php echo $html->link($ld['api_method_manage'],'/api_methods',array("class"=>"am-btn am-btn-default am-btn-sm"),'',false,false).'&nbsp;';?>
				<button type="button" class="am-btn am-btn-warning am-btn-sm am-radius" data-am-modal="{target: '#select_bulk_upload', closeViaDimmer: 1, width:700,height:300}"  onclick="ajax_select_bulk_upload()">选择批量上传</button>	

	</div>
	<div>
		<div class="am-u-lg-2 am-u-md-3 am-u-sm-4">
			<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
		    	<li><a href="#batch_upload_user"><?php echo '公共参数'.$ld['bulk_upload'] ?></a></li>
		    	<?php if(isset($uploads_list)&&sizeof($uploads_list)>0){ ?><li><?php echo $ld['preview']?></li>	<?php } ?>
			</ul>
		</div>
		<div class="am-panel-group admin-content" id="accordion" style="width:83%;float:right;">
			<?php echo $form->create('api_methods',array('action'=>'/common_parameter_uploadpreview/','name'=>"uploadusersForm","enctype"=>"multipart/form-data"));?>
				<div id="batch_upload_user" class="am-panel am-panel-default">
			  		<div class="am-panel-hd">
						<h4 class="am-panel-title">
							<?php echo '公共参数'.$ld['bulk_upload'] ?>
						</h4>
				    </div>
				    <div class="am-panel-collapse am-collapse am-in">
			      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
							<div class="am-form-group">
				    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['csv_file_bulk_upload']?></label>
				    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
				    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9"  style="margin-bottom:10px;">
										<p style="margin:10px 0px;"><input name="file" id="file" size="40" type="file" style="height:30px;" onchange="checkFile()"/></p>
										<p style="padding:6px 0px;"><?php echo $ld['articles_upload_file_encod']?></p>
				    				</div>
				    			</div>
				    		</div>
						
								<div class="am-form-group">
					    			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label"></label>
					    			<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
					    				<div class="am-u-lg-9 am-u-md-9 am-u-sm-9">
											<?php if( isset($profile_id) && !empty($profile_id) ){
												echo $html->link($ld['download_example_batch_csv'],"/api_methods/download_common_parameter_csv_example/",'',false,false);
										}  ?>
					    				</div>
					    			</div>
					    		</div>	
				    	
						</div>
						<div class="btnouter">
							  <input type="hidden" value="1" name="sub1"/>
							<button type="submit"  name="upload_submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
							<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
						</div>	
					</div>
				</div>
			<?php echo $form->end();?>
		</div>
	</div>
</div>	
<!-- 项目下选择弹层--->	
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
function ajax_select_bulk_upload(){
		$.ajax({ url: admin_webroot+"api_methods/select_upload/",
		type:"GET",
		dataType:"html",
		success: function(data){
			$("#bulk_upload_show").html(data);
  		}
  	});
}	

function checkFile() {
	var obj = document.getElementById('file');
	var suffix = obj.value.match(/^(.*)(\.)(.{1,8})$/)[3];
	if(suffix != 'csv'&&suffix != 'CSV'){
 		alert("<?php echo $ld['file_format_csv']?>");
 		obj.value="";
 		return false;
	}
}
</script>