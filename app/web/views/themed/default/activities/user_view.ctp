<link href="/plugins/AmazeUI/css/amazeui.datetimepicker-se.min.css" type="text/css" rel="stylesheet">
<script src="/plugins/AmazeUI/js/moment-with-locales.min.js" type="text/javascript"></script>
<script src="/plugins/AmazeUI/js/amazeui.datetimepicker-se.min.js" type="text/javascript"></script>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<style type='text/css'>
#course_chapter_list .admin-user-img{
	display:none;
}
ol.am-breadcrumb.am-hide-md-down.am-color{
    max-width:1200px;
    margin:0 auto;
    padding:0;
    margin-bottom:0.5rem;
    margin-top:0.5rem;
}
.am-selected{
	width:100%;
}
.tag{
	display:inline-block;height:35px;line-height:35px;border:1px solid #ccc;padding-left:10px;padding-right:10px;margin-right:1rem;
}
.tag-add{
	display:inline-block;width:15px;height:15px;border-radius:50%;background:#ccc;text-align:center;line-height:15px;cursor:pointer;
}
.am-form-field:focus{
	border-color:red;
}
@media (max-width: 767px){ 
	div.am-text-left.lineh1{line-height: 1;margin-bottom:2px;}
	.timeleft{margin-bottom: 3px;}
	.w12{width:12%;float:left;}
	.hs{display: none;}
 }
</style>
<script src="<?php echo $webroot.'plugins/ajaxfileupload.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="act_config">
  <div class="am-modal-dialog">
    <div class="am-modal-hd" style="padding-bottom:13.3px;">自定义填写项
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
    	<form class="am-form" id="config_form">
	      <div class="am-g">
	        <input type="hidden" name="config_id" value="0">
	        <input type="hidden" name="activity_id" value="<?php echo isset($activity_info['Activity']['id'])&&$activity_info['Activity']['id']!=''?$activity_info['Activity']['id']:0; ?>">
	      	<div style="line-height:37px;margin-bottom:1rem;" class="am-text-right">
	      		<div class="am-u-lg-2 am-hide-sm-only">&nbsp;</div>
		      	<div class="am-u-lg-2 am-text-left am-u-sm-12 lineh1">名称</div>
		      	<div class="am-u-lg-6 am-text-left am-u-sm-12"><input type="text" name="config_name"></div>
		      	<div class='am-cf'></div>
	      	</div>
	      	<div style="line-height:37px;margin-bottom:1rem;" class="am-text-right">
	      		<div class="am-u-lg-2 am-hide-sm-only">&nbsp;</div>
		      	<div class="am-u-lg-2 am-text-left am-u-sm-12 lineh1" style="margin-top:3px;">类型</div>
		      	<div class="am-u-lg-6 am-text-left am-u-sm-12">
			      	<select name="config_type" id="" data-am-selected="{maxHeight: '100px',noSelectedText:'请选择'}" onchange="text_show(this)">
			      		<option value="">请选择</option>
			      		<option value="text">文本框</option>
			      		<option value="radio">单选框</option>
			      		<option value="checkbox">多选框</option>
			      		<option value="image">图片</option>
			      	</select>
		      	</div>
		      	<div class='am-cf'></div>
	      	</div>
	      	<div style="line-height:37px;margin-bottom:1rem;display:none;" class="am-text-right" id="config_op" >
	      		<div class="am-u-lg-2 am-hide-sm-only">&nbsp;</div>
		      	<div class="am-u-lg-2 am-text-left am-u-sm-12 lineh1">选项</div>
		      	<div class="am-u-lg-6 am-text-left am-u-sm-12"><textarea name="config_option" id="conf_option" cols="10" rows="5" placeholder="key:value"></textarea></div>
		      	<div class='am-cf'></div>
	      	</div>
	      	<div style="margin-bottom:1rem;line-height:1;" class="am-text-right">
	      		<div class="am-u-lg-2 am-hide-sm-only">&nbsp;</div>
		      	<div class="am-u-lg-2 am-text-left am-u-sm-12 lineh1" style="margin-top:2px;">必填</div>
		      	<div class="am-u-lg-6 am-text-left am-u-sm-12">
		      	  <label class="am-radio-inline am-u-lg-3 am-u-sm-3">
				    <input type="radio" name="config_is_required" data-am-ucheck value="1">是
				  </label>
				  <label class="am-radio-inline">
				    <input type="radio" name="config_is_required" data-am-ucheck value="0">否
				  </label>
		      	</div>
		      	<div class='am-cf'></div>
	      	</div>
	      	<div style="margin-bottom:13px;line-height:1;" class="am-text-right">
	      		<div class="am-u-lg-2 am-hide-sm-only">&nbsp;</div>
		      	<div class="am-u-lg-2 am-text-left am-u-sm-12 lineh1" style="margin-top:3px;">状态</div>
		      	<div class="am-u-lg-6 am-text-left am-u-sm-12">
		      	  <label class="am-radio-inline am-u-lg-3 am-u-sm-3" style="padding-right:0;">
				    <input type="radio" name="config_status" data-am-ucheck value="1">有效
				  </label>
				  <label class="am-radio-inline">
				    <input type="radio" name="config_status" data-am-ucheck value="0">无效
				  </label>
		      	</div>
		      	<div class='am-cf'></div>
	      	</div>
	      	<div style="line-height:37px;margin-bottom:1rem;" class="am-text-right">
	      		<div class="am-u-lg-2 am-hide-sm-only">&nbsp;</div>
		      	<div class="am-u-lg-2 am-text-left am-u-sm-12 lineh1">排序</div>
		      	<div class="am-u-lg-6 am-text-left am-u-sm-12">
			      	<input type="text" name="config_orderby" value="50">
		      	</div>
		      	<div class='am-cf'></div>
	      	</div>
	      	
	      </div>
        </form>
        <div style="margin-bottom:0.5rem;" class="am-text-left">
	      	<div class="am-u-lg-4 am-hide-sm-only">&nbsp;</div>
	      	<div class="am-u-lg-6">
		      	<button class="am-btn am-btn-primary" onclick="config_sub()" style="min-width:130px;">提交</button>
	      	</div>
	      	<div class='am-cf'></div>
      	</div>
    </div>
  </div>
</div>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="act_config">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">活动配置
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
    	<form class="am-form" id="config_form">
	      <div class="am-g">
	        <input type="hidden" name="config_id" value="0">
	        <input type="hidden" name="activity_id" value="<?php echo isset($activity_info['Activity']['id'])&&$activity_info['Activity']['id']!=''?$activity_info['Activity']['id']:0; ?>">
	      	<div style="line-height:37px;margin-bottom:1rem;" class="am-text-right">
		      	<div class="am-u-lg-4">配置名称</div>
		      	<div class="am-u-lg-6"><input type="text" name="config_name"></div>
		      	<div class='am-cf'></div>
	      	</div>
	      	
	      </div>
        </form>
        <div style="margin-bottom:0.5rem;" class="am-text-left">
	      	<div class="am-u-lg-4">&nbsp;</div>
	      	<div class="am-u-lg-6">
	      		<?php if(isset($act_id)&&$act_id!=0){ ?>
		      	<button class="am-btn am-btn-secondary" onclick="config_sub()">提交</button>
		      	<?php } ?>
	      	</div>
	      	<div class='am-cf'></div>
      	</div>
    </div>
  </div>
</div>
<div style="max-width:1200px;margin:20px auto;">
	<div class="am-u-lg-12 am-u-sm-12">
			<h3 style="font-size:18px;font-weight:400;margin-top:4px;padding-bottom:5px;border-bottom:1px solid #ccc;padding-left:5px;">
		    	<span>活动基本信息</span>
		    	<?php if(isset($act_id)&&$act_id!=0){ ?>
		    		<span style="float:right;font-size:14px;cursor:pointer;" onclick='check_activity_qrcode(this)'>点击验票</span>
		    	<?php } ?>
		    	<div class="am-cf"></div>
		    </h3>
			<div class="am-u-lg-2 am-hide-sm-only">&nbsp;</div>
			<div class="am-u-lg-10 am-u-sm-12" style="margin-top:1.5rem;padding:0;">
			<form class="am-form" id="activity_form">
				<input type='hidden' name="data[Activity][id]" value="<?php echo isset($activity_info['Activity']['id'])?$activity_info['Activity']['id']:0; ?>" />
				<input type='hidden' name="data[Activity][publisher_type]" value="U" />
				<div class="am-form-group" style="line-height:37px;">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">发布者描述：</label>
					<div class="am-u-lg-6 am-u-sm-8">
						<input type='hidden' name="data[ActivityPublisher][id]" value="<?php echo isset($activity_publisher_info['ActivityPublisher']['id'])?$activity_publisher_info['ActivityPublisher']['id']:'0'; ?>" />
	          				<textarea name="data[ActivityPublisher][description]" id="publisher_desc" cols="30" rows="10"><?php echo isset($activity_publisher_info['ActivityPublisher']['description'])?$activity_publisher_info['ActivityPublisher']['description']:''; ?></textarea>
		          			<script type='text/javascript'>
							var editor;
							KindEditor.ready(function(K) {
								editor = K.create('#publisher_desc', {width:'100%',
									langType : 'zh-cn',filterMode : false,
									items:['source','undo','redo','cut','paste','plainpaste','fontname','fontsize','forecolor','hilitecolor','bold','italic','underline','strikethrough','justifyleft','justifycenter','justifyright','indent','outdent'],
									afterBlur:function(){this.sync();}
								});
							});
						</script>
					</div>
					<div class="am-cf"></div>
				</div>
				<div class="am-form-group" style="line-height:37px;">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">活动渠道：</label>
					<div class="am-u-lg-6 am-u-sm-8">
	          			<select name="data[Activity][channel]" data-am-selected="{noSelectedText:'请选择'}" onchange="activity_channel(this.value)">
	          				<option value="">请选择</option>
	          				<option value="0" <?php echo isset($activity_info['Activity']['channel'])&&$activity_info['Activity']['channel']=='0'?'selected':''; ?>>线上</option>
	          				<option value="1" <?php echo isset($activity_info['Activity']['channel'])&&$activity_info['Activity']['channel']=='1'?'selected':''; ?>>线下</option>
	          				<option value="2" <?php echo isset($activity_info['Activity']['channel'])&&$activity_info['Activity']['channel']=='2'?'selected':''; ?>>直播</option>
	          			</select>
					</div>
					<div class="am-cf"></div>
				</div>
				<div class="am-form-group" style="line-height:37px;">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">活动图片：</label>
					<div class="am-u-lg-9 am-u-sm-8">
	          			<div class="am-form-group am-form-file" style="margin:0;">
							<button type="button" class="am-btn am-btn-default am-btn-sm">
	   						 	<i class="am-icon-cloud-upload"></i> 选择要上传的文件
	   						</button>
	   						<span class="hs">(推荐尺寸150*150)</span>
							<input type="file" name="activity_pic" multiple onchange="ajax_upload_media(this,this.id)" id="activity_pic" class="upload-img">
							<input type="hidden" name="data[Activity][image]" value="">
						</div>
						<img src="" alt="" style="display:none;max-width:150px;max-height:150px;margin-top:0.5rem;" class="img">
						<?php if(isset($activity_info['Activity']['image'])&&$activity_info['Activity']['image']!=''){ ?>
						<figure data-am-widget="figure" class="am am-figure am-figure-default am-no-layout am-figure-zoomable" data-am-figure="{  pureview: 'true' }" style="margin-top:0.5rem;max-width:150px;">
							<img src="<?php echo $server_host.$activity_info['Activity']['image'] ?>" alt="" data-rel="<?php echo $server_host.$activity_info['Activity']['image'] ?>" style="max-width:150px;max-height:150px;" class="img">
						</figure>
						<?php } ?>
					</div>
					<div class="am-cf"></div>
				</div>
				<div class="am-form-group" style="line-height:37px;">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">活动名称：</label>
					<div class="am-u-lg-6 am-u-sm-8">
	          			<input type="text" name="data[Activity][name]" value="<?php echo isset($activity_info['Activity']['name'])&&$activity_info['Activity']['name']!=''?$activity_info['Activity']['name']:''; ?>">
					</div>
					<div class="am-cf"></div>
				</div>
				<div class="am-form-group" style="line-height:37px;">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">活动描述：</label>
					<div class="am-u-lg-9 am-u-sm-8">
		          			<textarea name="data[Activity][description]" id="activity_desc" cols="30" rows="10"><?php echo isset($activity_info['Activity']['description'])&&$activity_info['Activity']['description']!=''?$activity_info['Activity']['description']:''; ?></textarea>
						<script type='text/javascript'>
							var editor;
							KindEditor.ready(function(K) {
								editor = K.create('#activity_desc', {width:'100%',
									langType : 'zh-cn',filterMode : false,
									items:['source','undo','redo','cut','paste','plainpaste','fontname','fontsize','forecolor','hilitecolor','bold','italic','underline','strikethrough','justifyleft','justifycenter','justifyright','indent','outdent'],
									afterBlur:function(){this.sync();}
								});
							});
						</script>
					</div>
					<div class="am-cf"></div>
				</div>
				<div class="am-form-group" style="line-height:37px;" id="huodongdizhi">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">活动地址：</label>
	          			<div class="am-u-lg-6 am-u-sm-8 ">
		          			<input type="text" name="data[Activity][address]" value="<?php echo isset($activity_info['Activity']['address'])&&$activity_info['Activity']['address']!=''?$activity_info['Activity']['address']:''; ?>">
					</div>
					<div class="am-cf"></div>
				</div>
				<div class="am-form-group" style="line-height:37px;margin-bottom:13px;">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">时间范围：</label>
					<div class="am-u-lg-4 am-u-sm-8 timeleft" style="">		
		                <div class="am-input-group">
		                    <input style="min-height:35px;" type="text" id="start_date" class="am-form-field" name="data[Activity][start_date]" value="<?php echo isset($activity_info['Activity']['start_date'])&&$activity_info['Activity']['start_date']!=''?date('Y-m-d H',strtotime($activity_info['Activity']['start_date'])):''; ?>"/>
		                    <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
		                		<i class="am-icon-remove"></i>
		              		</span>
		          		</div>
					</div>
					<em style="float:left;width:3%;text-align:center;" class="am-hide-sm-only">-</em>
					<label class="am-u-lg-3 am-text-right am-u-sm-4 am-show-sm-only">&nbsp;</label>
					<div class="am-u-lg-4 am-u-sm-8" style="">
		                <div class="am-input-group">
		                    <input style="min-height:35px;" type="text" id="end_date" class="am-form-field" name="data[Activity][end_date]" value="<?php echo isset($activity_info['Activity']['end_date'])&&$activity_info['Activity']['end_date']!=''?date('Y-m-d H',strtotime($activity_info['Activity']['end_date'])):''; ?>" />
		                    <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
		                		<i class="am-icon-remove"></i>
		              		</span>
		          		</div>
					</div>
					<div class="am-cf"></div>
				</div>
				<div class="am-form-group" style="line-height:37px;">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">活动类型：</label>
					<div class="am-u-lg-6 am-u-sm-8">
	          			<select name="data[Activity][type]" data-am-selected="{noSelectedText:'请选择'}" onchange="get_type_id(this,<?php echo isset($activity_info['Activity']['type_id'])&&$activity_info['Activity']['type_id']!=''?$activity_info['Activity']['type_id']:'0'; ?>)">
	          				<option value="">请选择</option>
	          				<option value="C" <?php echo isset($activity_info['Activity']['type'])&&$activity_info['Activity']['type']=='C'?'selected':''; ?>>课程</option>
	          				<option value="E" <?php echo isset($activity_info['Activity']['type'])&&$activity_info['Activity']['type']=='E'?'selected':''; ?>>评测</option>
	          			</select>
	          			<div style="display:none;" id="activity_type_id">
		          			<select name="data[Activity][type_id]" data-am-selected="{noSelectedText:'请选择'}">
		          				<option value="0">请选择</option>
		          			</select>
	          			</div>
					</div>
					<div class="am-cf"></div>
				</div>
				<div class="am-form-group" style="line-height:37px;">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">活动金额：</label>
	          		<div class="am-u-lg-6 am-u-sm-8 ">
		          		<input type="text" name="data[Activity][price]" value="<?php echo isset($activity_info['Activity']['price'])&&$activity_info['Activity']['price']!=''?$activity_info['Activity']['price']:'0.00'; ?>">
					</div>
					<div class="am-cf"></div>
				</div>
				<div class="am-form-group" style="">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">活动状态：</label>
		          		<div class="am-u-lg-6 am-u-sm-8 ">
		          			<label class="am-checkbox am-primary">
			          			<input type="checkbox"  value="1" data-am-ucheck <?php echo '' ?>  onclick="change_status(this)" <?php echo isset($activity_info['Activity']['status'])&&$activity_info['Activity']['status'] == 0?'':'checked'; ?> >有效
			          			<input type="hidden" name="data[Activity][status]" value="<?php echo isset($activity_info['Activity']['status'])?$activity_info['Activity']['status']:'1'; ?>">
			          		</label>
						</div>
						<div class="am-cf"></div>
					</div>
				</form>
				<div class="am-form-group">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">&nbsp;</label>
	          			<div class="am-u-lg-7 am-u-sm-8 ">
	          				<button type="button" class="am-btn am-btn-primary" onclick="activity_submit()" style="min-width:130px;">提交</button>
					</div>
					<div class="am-cf"></div>
				</div>
			</div>
			
			<div class="am-cf"></div>
			<?php if(isset($activity_config_info)){ ?>
			<h3 style="font-size:18px;font-weight:400;margin-top:4px;border-bottom:1px solid #ccc;padding-left:5px;">
		    	<span style="float:left;margin-top:1.2rem;">自定义填写项</span>
		    	<button class="am-btn am-btn-sm am-btn-warning" style="float:right;margin-bottom:0.5rem;" onclick="config_edit(0)"><i class="am-icon-plus"></i>添加</button>
		    	<div class="am-cf"></div>
		    </h3>
		    
			<div style="margin-top:1.5rem;padding:0;margin-bottom:1.5rem;">
				
					<div style="border-bottom:1px solid #ccc;line-height:20px;">
						<div class="am-u-lg-4 am-u-sm-3" style="padding-right:0;">名称</div>
						<div class="am-u-lg-2 w12" style="padding:0;">类型</div>
						<div class="am-u-lg-2 w12" style="padding:0;">状态</div>
						<div class="am-u-lg-2 am-u-sm-2" style="padding:0;">必填</div>
						<div class="am-u-lg-2 am-u-sm-4" style="padding-right:0;">操作</div>
						<div class="am-cf"></div>
					</div>
					<div style="border-bottom:1px solid #ccc;padding-top:0.5rem;padding-bottom:0.5rem;">
						<div class="am-u-lg-4 am-u-sm-3" style="padding-right:0;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;line-height:28px;"><?php echo '姓名'; ?></div>
						<div class="am-u-lg-2 w12" style="padding:0;line-height:28px;">
							文本
						</div>
						<div class="am-u-lg-2 w12" style="padding:0;line-height:28px;"><?php echo '<i class="am-icon-check am-yes"></i>'; ?></div>
						<div class="am-u-lg-2 am-u-sm-2" style="padding:0;line-height:28px;"><?php echo '<i class="am-icon-check am-yes"></i>'; ?></div>
						<div class="am-u-lg-2 am-u-sm-4" style="padding-right:0;">
							
						</div>
						<div class="am-cf"></div>
					</div>
					<div style="border-bottom:1px solid #ccc;padding-top:0.5rem;padding-bottom:0.5rem;">
						<div class="am-u-lg-4 am-u-sm-3" style="padding-right:0;overflow: hidden;text-overflow: ellipsis;white-space: nowrap;line-height:28px;"><?php echo '手机号'; ?></div>
						<div class="am-u-lg-2 w12" style="padding:0;line-height:28px;">
							文本
						</div>
						<div class="am-u-lg-2 w12" style="padding:0;line-height:28px;"><?php echo '<i class="am-icon-check am-yes"></i>'; ?></div>
						<div class="am-u-lg-2 am-u-sm-2" style="padding:0;line-height:28px;"><?php echo '<i class="am-icon-check am-yes"></i>'; ?></div>
						<div class="am-u-lg-2 am-u-sm-4" style="padding-right:0;">
							
						</div>
						<div class="am-cf"></div>
					</div>
				<?php if(count($activity_config_info)>0){ ?>
				<?php foreach ($activity_config_info as $k => $v) {
				?>
					<div style="border-bottom:1px solid #ccc;padding-top:0.5rem;padding-bottom:0.5rem;">
						<div class="am-u-lg-4 am-u-sm-3" style="line-height:28px;"><?php echo isset($v['ActivityConfig']['name'])?$v['ActivityConfig']['name']:''; ?></div>
						<div class="am-u-lg-2 w12" style="line-height:28px;padding:0;">
							<?php if($v['ActivityConfig']['type'] == 'text'){echo '文本';}else if($v['ActivityConfig']['type'] == 'radio'){echo '单选框';}else if($v['ActivityConfig']['type'] == 'checkbox'){echo '多选框';}else if($v['ActivityConfig']['type'] == 'image'){echo '图片';} ?>
						</div>
						<div class="am-u-lg-2 w12" style="padding:0;line-height:28px;"><?php echo isset($v['ActivityConfig']['status'])&&$v['ActivityConfig']['status']==1?'<i class="am-icon-check am-yes"></i>':'<i class="am-icon-close am-no"></i>'; ?></div>
						<div class="am-u-lg-2 am-u-sm-2" style="padding:0;line-height:28px;"><?php echo isset($v['ActivityConfig']['is_required'])&&$v['ActivityConfig']['is_required']==1?'<i class="am-icon-check am-yes"></i>':'<i class="am-icon-close am-no"></i>'; ?></div>
						<div class="am-u-lg-2 am-u-sm-4">
							<a href="javascript:;" class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" onclick="config_edit(<?php echo isset($v['ActivityConfig']['id'])?$v['ActivityConfig']['id']:0; ?>)">编辑</a>
							<a style="" class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="config_delete(<?php echo isset($v['ActivityConfig']['id'])?$v['ActivityConfig']['id']:0; ?>)">
                        	删除</a>
						</div>
						<div class="am-cf"></div>
					</div>
				<?php }} ?>
				
			</div>
			<?php } ?>
			<?php //pr($activity_tag_info); ?>
			<?php if(isset($activity_info)&&!empty($activity_info)){ ?>
			<h3 style="font-size:18px;font-weight:400;margin-top:4px;padding-bottom:5px;border-bottom:1px solid #ccc;padding-left:5px;">
				<span style="float:left;margin-top:0.8rem;">活动标签</span>
				<div class="am-cf"></div>
			</h3>
			<div style="padding-top:10px;">
				<form class="am-form" id="tag_form">
					<?php if(isset($activity_tag_info)&&count($activity_tag_info)>0){foreach ($activity_tag_info as $k => $v) { ?>
						<span class="tag" style="margin-bottom:10px;" id="tag_<?php echo $v['ActivityTag']['id']; ?>"><span onclick="change_tag(this)"><?php echo $v['ActivityTag']['tag_name']; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="am-icon-close am-no" style="cursor:pointer;" onclick="delete_tag(this)"></span></span>
					<?php }}else{ ?>
						<span class="tag" style="margin-bottom:10px;" id="tag_0"><span onclick="change_tag(this)"><?php echo '点击添加一个标签'; ?></span>&nbsp;&nbsp;&nbsp;&nbsp;<span class="am-icon-close am-no" style="cursor:pointer;" onclick="delete_tag(this)"></span></span>
					<?php } ?>
					<a style="margin-top:-5px;" class="am-btn am-btn-warning am-btn-sm" onclick="add_tag(this)"><i class="am-icon-plus"></i> 添加</a>
				</form>
			</div>
		    <?php } ?>
		<div class="am-cf"></div>
	</div>
	<div class="am-cf"></div>
</div>
<script type='text/javascript'>
$(function(){
	$("#start_date").datetimepicker({
		format: 'YYYY-MM-DD HH:00'
	});
	$("#end_date").datetimepicker({
		format: 'YYYY-MM-DD HH:00'
	});
});
function activity_channel(val){
	if(val=='1'){
		$("input[name='data[Activity][address]']").parents('div.am-form-group').show();
	}else{
		$("input[name='data[Activity][address]']").parents('div.am-form-group').hide();
	}
}

function get_type_id(select,type_id){
	var org_id = 0;
	var content = '';
	var activity_type=$(select).val();
	if($(select).val() == 'C'){
		$.ajax({
			url: web_base+"/courses/get_activity_type_id",
			type:"GET",
			data:{'organizations_id':org_id,'type':'member'},
			dataType:"json",
			success: function(data){
				$(select).siblings('div').show();
				$(select).siblings('div').children('select').html('<option value="0">请选择</option>');
				for(var i=0;i<data.length;i++){
					content = '';
					if(type_id == data[i].Course.id){
						content += '<option value="'+data[i].Course.id+'" selected>'+data[i].Course.name+'</option>';
					}else{
						content += '<option value="'+data[i].Course.id+'">'+data[i].Course.name+'</option>';
					}
					$(select).siblings('div').children('select').append(content);
				}
			}
        	});
	}else if($(select).val() == 'E'){
		$.ajax({
			url: web_base+"/evaluations/get_activity_type_id",
			type:"GET",
			data:{'organizations_id':org_id,'type':'member'},
			dataType:"json",
			success: function(data){
				$(select).siblings('div').show();
				$(select).siblings('div').children('select').html('<option value="0">请选择</option>');
				for(var i=0;i<data.length;i++){
					content = '';
					if(type_id == data[i].Evaluation.id){
						content += '<option value="'+data[i].Evaluation.id+'" selected>'+data[i].Evaluation.name+'</option>';
					}else{
						content += '<option value="'+data[i].Evaluation.id+'">'+data[i].Evaluation.name+'</option>';
					}
					$(select).siblings('div').children('select').append(content);
				}
			}
		});
	}else{
		$('#activity_type_id').hide();
		$("input[name='activity_type_id']").find('option').eq(0).attr('selected',true);
	}
}

function ajax_upload_media(obj,obj_id){
	if($(obj).val()!=""){
		var fileName_arr=$(obj).val().split('.');
		var fileType=fileName_arr[fileName_arr.length-1];
		var fileTypearray=Array('jpg','JPG','jpeg','JPEG','gif','GIF','png','PNG');
		ajaxFileUpload(obj_id);
	}
}

function ajaxFileUpload(img_id){
	 $.ajaxFileUpload({
		  url:'/organizations/ajax_upload_media',
		  secureuri:false,
		  fileElementId:img_id,
		  data:{'org_id':0,'org_code':img_id},
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

function change_status(obj){
	if($(obj).is(":checked")){
		$(obj).siblings('input').val(1);
	}else{
		$(obj).siblings('input').val(0);
	}
}

function activity_submit(){
	if($("select[name='data[Activity][channel]']").val() == ''){
		seevia_alert('活动渠道不能为空！');
		return;
	}else if($("input[name='data[Activity][name]']").val() == ''){
		seevia_alert('活动名称不能为空！');
		return;
	}else if($("textarea[name='data[Activity][description]']").val() == ''){
		seevia_alert('活动描述不能为空！');
		return;
	}else if(!$("input[name='data[Activity][address]']").is(':hidden')&&$("input[name='data[Activity][address]']").val() == ''){
		seevia_alert('活动地址不能为空！');
		return;
	}else if($("#start_date").val() == ''){
		seevia_alert('活动开始时间不能为空！');
		return;
	}else if($("#end_date").val() == ''){
		seevia_alert('活动结束时间不能为空！');
		return;
	}else if($("#end_date").val() <= $("#start_date").val()){
		seevia_alert('活动开始时间必须小于结束时间！');
		return;
	}else if($("input[name='data[Activity][price]']").val() == ''){
		seevia_alert('活动金额不能为空！');
		return;
	}
	var getdata = $("#activity_form").serialize();
	$.ajax({
		url: web_base+"/activities/user_view",
	    	type:"POST",
	    	data:getdata,
	    	dataType:"json",
	    	success: function(data){
	        	if(data.code == 1){
	        		window.location.href=web_base+'/activities/user_index';
	        	}
	    	}
	});
}

	function config_edit(id){
		$("#act_config").modal();
		var activity_id = $("#activity_id").val();
		if(id!=0){
			$.ajax({
			url: web_base+"/activities/set_activity_confit/"+id,
        	type:"POST",
        	data:{'activity_id':activity_id},
        	dataType:"json",
        	success: function(data){
            	if(data.length != 0){
            		$("#act_config input[name='config_name']").val(data.ActivityConfig.name);
            		$("#act_config textarea[name='config_option']").val(data.ActivityConfig.options);
            		
            		$("#act_config select").find('option[value="'+data.ActivityConfig.type+'"]').attr('selected',true);
            		if(data.ActivityConfig.is_required == 1){
            			$('input[name="config_is_required"]').eq(0).attr('checked',true);
            		}else{
            			$('input[name="config_is_required"]').eq(1).attr('checked',true);
            		}
            		if(data.ActivityConfig.status == 1){
            			$('input[name="config_status"]').eq(0).attr('checked',true);
            		}else{
            			$('input[name="config_status"]').eq(1).attr('checked',true);
            		}
            		$("#act_config input[name='config_orderby']").val(data.ActivityConfig.orderby);
            		$("#act_config input[name='config_id']").val(data.ActivityConfig.id);
            		//$("#act_config input[name='config_name']").val(data.ActivityConfig.name);
	            	}
	        	}
	    	});
		}else{
			$("#act_config input[name='config_name']").val('');
    		$("#act_config textarea[name='config_option']").val('');
    		
    		$("#act_config select").find('option[value=""]').attr('selected',true);
			$('input[name="config_is_required"]').eq(0).attr('checked',true);
			$('input[name="config_status"]').eq(0).attr('checked',true);
    		$("#act_config input[name='config_orderby']").val(50);
    		$("#act_config input[name='config_id']").val(0);
		}
		
	}

	function config_sub(){
		var postData = $("#config_form").serialize();
		var config_id = $('input[name="config_id"]').val();
		$.ajax({
			url: web_base+'/activities/config_sub/'+config_id,
        	type:"POST",
        	data:postData,
        	dataType:"json",
        	success: function(data){
            	if(data.code == 1){
            		window.location.reload();
            	}
        	}
    	});
	}

	function check_activity_qrcode(btn){
		if(typeof(wx)!='undefined'){
			wx.scanQRCode({
				needResult:1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
				scanType: ["qrCode"], // 可以指定扫二维码还是一维码，默认二者都有
				success: function (res) {
					var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
					//alert(result);
					check_code(result);//进行验票
				}
			});
		}
	}

	function check_code(user_id){
		var activity_id = $("#activity_id").val();
		$.ajax({
			url: web_base+'/activities/check_code',
	    	type:"POST",
	    	data:{'activity_id':activity_id,'user_id':user_id},
	    	dataType:"json",
	    	success: function(data){
	        	if(data.code == 1){
	        		seevia_alert_func(jump_reload,'验票成功！');
	        	}else{
	        		seevia_alert_func(jump_reload,'验票失败！');
	        	}
	    	}
		});
	}

	function config_delete(id){
		var con_delete = function(){
			$.ajax({
				url: web_base+'/activities/config_delete/'+id,
	        	type:"POST",
	        	data:{},
	        	dataType:"json",
	        	success: function(data){
	            	if(data.code == 1){
	            		seevia_alert_func(jump_reload,'删除成功！');
	            	}
	        	}
	    	});
		}
		seevia_confirm(con_delete,'是否确认删除？');
		
	}
	
	function cla(obj){
		$(obj).siblings('input').val('');
	}

	function text_show(obj){
		if($(obj).val() == 'radio' || $(obj).val() == 'checkbox'){
			$("#config_op").show();
		}else{
			$("#config_op").hide();
			$("#config_op textarea").val('');
		}
	}

	function add_tag(obj){
		if($(obj).siblings('input').length == 0){
			$(obj).before('<input type="text" style="width:100px;margin-right:1rem;margin-top:-6px;display:inline-block;" onkeydown="sub_tag_key(this,event)" onblur="sub_tag(this)">');
		}
		
	}

	function change_tag(obj){
		content = '<input type="text" id="'+$(obj).parent().attr('id')+'" style="width:100px;margin-right:1rem;margin-top:-6px;display:inline-block;" onkeydown="sub_tag_key(this,event)" onblur="sub_tag(this)" value="'+$(obj).html()+'">';
		$(obj).parent().replaceWith(content);
		$("#"+$(obj).parent().attr('id')).focus().select();
	}

	function sub_tag(obj){
		var tag_name = $(obj).val();
		var activity_id = $("#activity_id").val();
		//alert($(obj)[0].tagName);
		if($(obj).attr('id')){
			var tag_id = $(obj).attr('id').split('_')[1];
			if($(obj).val() == ''){
				seevia_alert('标签内容不能为空！');
			}else{
				$.ajax({
					url: web_base+'/activities/sub_tag/'+tag_id,
		        	type:"POST",
		        	data:{'tag_name':tag_name,'activity_id':activity_id},
		        	dataType:"json",
		        	success: function(data){
		            	if(data.code == 1){
							re_tag();
		            	}
		        	}
		    	});
			}
		}else{
			if($(obj).val() == ''){
				seevia_alert('标签内容不能为空！');
			}else{
				$.ajax({
					url: web_base+'/activities/sub_tag/',
		        	type:"POST",
		        	data:{'tag_name':tag_name,'activity_id':activity_id},
		        	dataType:"json",
		        	success: function(data){
		            	if(data.code == 1){
							re_tag();
		            	}
		        	}
		    	});
			}
		}
		
	}

	function sub_tag_key(obj,e){
		var tag_name = $(obj).val();
		var activity_id = $("#activity_id").val();
		//alert($(obj)[0].tagName);
		if(e.keyCode == 13){
			if($(obj).attr('id')){
				var tag_id = $(obj).attr('id').split('_')[1];
				if($(obj).val() == ''){
					//seevia_alert('标签内容不能为空！');
				}else{
					$.ajax({
						url: web_base+'/activities/sub_tag/'+tag_id,
			        	type:"POST",
			        	data:{'tag_name':tag_name,'activity_id':activity_id},
			        	dataType:"json",
			        	success: function(data){
			            	if(data.code == 1){
								re_tag();
			            	}
			        	}
			    	});
				}
			}else{
				if($(obj).val() == ''){
					//seevia_alert('标签内容不能为空！');
				}else{
					$.ajax({
						url: web_base+'/activities/sub_tag/',
			        	type:"POST",
			        	data:{'tag_name':tag_name,'activity_id':activity_id},
			        	dataType:"json",
			        	success: function(data){
			            	if(data.code == 1){
								re_tag();
			            	}
			        	}
			    	});
				}
			}
		}
	}

	function re_tag(){
		var activity_id = $("#activity_id").val();
		$.ajax({
			url: web_base+'/activities/user_view/'+activity_id,
	    	type:"GET",
	    	data:{},
	    	dataType:"html",
	    	success: function(data){
	        	var HtmlDiv=document.createElement('div');//用这种创建方法就不会干扰到页面
	            HtmlDiv.innerHTML=data;
	            var order_list=$(HtmlDiv).find('#tag_form').html();
	            $("#tag_form").html(order_list);
	    	}
		});
	}

	function delete_tag(obj){
		var tag_id = $(obj).parent().attr('id').split('_')[1];
		//alert(tag_id);
		$.ajax({
			url: web_base+'/activities/delete_tag/'+tag_id,
	    	type:"POST",
	    	data:{},
	    	dataType:"json",
	    	success: function(data){
	        	if(data.code == 1){
	        		re_tag();
	        	}
	    	}
		});
	}
</script>