<link href="/plugins/AmazeUI/css/amazeui.datetimepicker-se.min.css" type="text/css" rel="stylesheet">
<script src="/plugins/AmazeUI/js/moment-with-locales.min.js" type="text/javascript"></script>
<script src="/plugins/AmazeUI/js/amazeui.datetimepicker-se.min.js" type="text/javascript"></script>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<style>
	#course_chapter_list .admin-user-img{
		display:none;
	}
	ol.am-breadcrumb.am-hide-md-down.am-color{
	    max-width:1200px;
	    margin:0 auto;
	    padding:0;
	    margin-bottom:10px;
	    margin-top:10px;
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
	@media (max-width: 767px){ 
		div.am-text-left.lineh1{line-height: 1;margin-bottom:2px;}
		.timeleft{margin-bottom: 3px;}
		.w12{width:12%;float:left;}
		.hs{display: none;}
	}
</style>
<script src="<?php echo $webroot.'plugins/ajaxfileupload.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<input type="hidden" id="org_id" value="<?php echo $this->params['url']['organization_id'] ?>">
<input type="hidden" id="activity_id" value="<?php echo isset($activity_info['Activity']['id'])&&$activity_info['Activity']['id']!=''?$activity_info['Activity']['id']:0; ?>">
<input type="hidden" id="type_id" value="<?php echo isset($activity_info['Activity']['type_id'])&&$activity_info['Activity']['type_id']!=''?$activity_info['Activity']['type_id']:''; ?>">
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
		      	<button class="am-btn am-btn-secondary" onclick="config_sub()" style="min-width:130px;">提交</button>
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
		      	<button class="am-btn am-btn-secondary" onclick="config_sub()">提交</button>
	      	</div>
	      	<div class='am-cf'></div>
      	</div>
    </div>
  </div>
</div>
<div style="max-width:1200px;margin:10px auto;">
	<?php echo $this->element('org_menu')?>
	<?php echo $this->element('organization_menu')?>
	<button class="am-btn am-btn-primary am-show-sm-only" data-am-offcanvas="{target: '#org_menu2', effect: 'push'}" style="margin-right:0.5rem;">我的组织</button>
	<div class="am-u-lg-9">
			<h3 style="font-size:18px;font-weight:400;margin-top:4px;border-bottom:1px solid #ccc;padding-left:5px;">
		    	<span>活动基本信息</span>
		    	<?php if(isset($act_id)&&$act_id!=0){ ?>
		    		<span style="float:right;font-size:14px;cursor:pointer;" onclick='check_activity_qrcode(this)'>点击验票</span>
		    	<?php } ?>
		    	<div class="am-cf"></div>
		    </h3>
			<div class="am-u-lg-2 am-hide-sm-only">&nbsp;</div>
			<div class="am-u-lg-10 am-u-sm-12" style="margin-top:1.5rem;padding:0;">
			<form class="am-form" id="activity_form">
				<?php if(isset($organization_info['Organization'])){ ?>
				<input type='hidden' name='publisher_type' value='O' />
				<?php }else if(isset($activity_info['Activity']['publisher_type'])){ ?>
				<input type='hidden' name='publisher_type' value="<?php echo isset($activity_info['Activity']['publisher_type'])?$activity_info['Activity']['publisher_type']:''; ?>" />
				<?php }else{ ?>
				<input type='hidden' name='publisher_type' value='U' />
				<?php } ?>
				<div class="am-form-group" style="line-height:37px;">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">活动渠道：</label>
					<div class="am-u-lg-6 am-u-sm-8">
	          			<select name="channel" id="" data-am-selected="{noSelectedText:'请选择'}" onchange="address_hide(this)">
	          				<option value="">请选择</option>
	          				<option value="0" <?php echo isset($activity_info['Activity']['channel'])&&$activity_info['Activity']['channel']=='0'?'selected':''; ?>>线上</option>
	          				<option value="1" <?php echo isset($activity_info['Activity']['channel'])&&$activity_info['Activity']['channel']=='1'?'selected':''; ?>>线下</option>
	          				<option value="2" <?php echo isset($activity_info['Activity']['channel'])&&$activity_info['Activity']['channel']=='2'?'selected':''; ?>>直播</option>
	          			</select>
					</div>
					<div class="am-cf"></div>
				</div>
				<div class="am-form-group" style="line-height:37px;<?php echo isset($activity_info['Activity']['publisher_type'])&&$activity_info['Activity']['publisher_type']=='U'?'':'display:none;'; ?>" id="publisher_desc">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">发布者描述：</label>
					<div class="am-u-lg-6 am-u-sm-8">
	          			<textarea name="publisher_desc" id="elm1" cols="30" rows="10" style=""><?php echo isset($activity_publisher_info['ActivityPublisher']['description'])?$activity_publisher_info['ActivityPublisher']['description']:''; ?></textarea>
	          			<script>
							var editor;
                            KindEditor.ready(function(K) {
                                editor = K.create('#elm1', {width:'100%',
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
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">活动图片：</label>
					<div class="am-u-lg-6 am-u-sm-8">
	          			<div class="am-form-group am-form-file" style="margin:0;">
							<button type="button" class="am-btn am-btn-default am-btn-sm">
	   						 	<i class="am-icon-cloud-upload"></i> 选择要上传的文件
	   						</button>
	   						<span class="hs">(推荐尺寸150*150)</span>
							<input type="file" name="activity_pic" multiple onchange="ajax_upload_media(this,this.id)" id="activity_pic" class="upload-img">
							<input type="hidden" name="activity_picture" value="">
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
	          			<input type="text" name="activity_name" value="<?php echo isset($activity_info['Activity']['name'])&&$activity_info['Activity']['name']!=''?$activity_info['Activity']['name']:''; ?>">
					</div>
					<div class="am-cf"></div>
				</div>
				<div class="am-form-group" style="line-height:37px;">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">活动描述：</label>
					<div class="am-u-lg-9 am-u-sm-8">
	          			<textarea name="activity_desc" id="elm" cols="30" rows="10"><?php echo isset($activity_info['Activity']['description'])&&$activity_info['Activity']['description']!=''?$activity_info['Activity']['description']:''; ?></textarea>
	          			<script>
							var editor;
                            KindEditor.ready(function(K) {
                                editor = K.create('#elm', {width:'100%',
                                    langType : 'zh-cn',filterMode : false,
                                    items:['source','undo','redo','cut','paste','plainpaste','fontname','fontsize','forecolor','hilitecolor','bold','italic','underline','strikethrough','justifyleft','justifycenter','justifyright','indent','outdent'],
                                    afterBlur:function(){this.sync();}
                                });
                            });
						</script>
					</div>
					<div class="am-cf"></div>
				</div>
				<div class="am-form-group" style="line-height:37px;" id="activity_add">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">活动地址：</label>
	          		<div class="am-u-lg-6 am-u-sm-8 ">
		          		<input type="text" name="activity_address" value="<?php echo isset($activity_info['Activity']['address'])&&$activity_info['Activity']['address']!=''?$activity_info['Activity']['address']:''; ?>">
					</div>
					<div class="am-cf"></div>
				</div>
				<div class="am-form-group" style="line-height:37px;margin-bottom:13px;">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">时间范围：</label>
					<div class="am-u-lg-4 am-u-sm-8 timeleft" style="">
		                <div class="am-input-group">
		                    <input style="min-height:35px;" type="text" id="start_date" class="am-form-field" name="start_date" value="<?php echo isset($activity_info['Activity']['start_date'])&&$activity_info['Activity']['start_date']!=''?date('Y-m-d H',strtotime($activity_info['Activity']['start_date'])):''; ?>" />
		                    <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
		                		<i class="am-icon-remove"></i>
		              		</span>
		          		</div>
					</div>
					<em style="float:left;width:3%;text-align:center;" class="am-hide-sm-only">-</em>
					<label class="am-u-lg-3 am-text-right am-u-sm-4 am-show-sm-only">&nbsp;</label>
					<div class="am-u-lg-4 am-u-sm-8" style="">
		                <div class="am-input-group">
		                    <input style="min-height:35px;" type="text" id="end_date" class="am-form-field" name="end_date" value="<?php echo isset($activity_info['Activity']['end_date'])&&$activity_info['Activity']['end_date']!=''?date('Y-m-d H',strtotime($activity_info['Activity']['end_date'])):''; ?>" />
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
	          			<select name="activity_type" id="" data-am-selected="{noSelectedText:'请选择'}" onchange="get_type_id(this)">
	          				<option value="">请选择</option>
	          				<option value="C" <?php echo isset($activity_info['Activity']['type'])&&$activity_info['Activity']['type']=='C'?'selected':''; ?>>课程</option>
	          				<option value="E" <?php echo isset($activity_info['Activity']['type'])&&$activity_info['Activity']['type']=='E'?'selected':''; ?>>评测</option>
	          			</select>
	          			<div style="display:none;" id="activity_type_id">
		          			<select name="activity_type_id" id="" data-am-selected="{noSelectedText:'请选择'}">
		          				<option value="">请选择</option>
		          			</select>
	          			</div>
					</div>
					<div class="am-cf"></div>
				</div>
				<div class="am-form-group" style="line-height:37px;">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">活动金额：</label>
	          		<div class="am-u-lg-6 am-u-sm-8 ">
		          		<input type="text" name="activity_price" value="<?php echo isset($activity_info['Activity']['price'])&&$activity_info['Activity']['price']!=''?$activity_info['Activity']['price']:'0.00'; ?>">
					</div>
					<div class="am-cf"></div>
				</div>
				<div class="am-form-group" style="">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">活动状态：</label>
	          		<div class="am-u-lg-6 am-u-sm-8 ">
	          			<label class="am-checkbox" style="margin:0;">
		          			<input type="checkbox"  value="1" data-am-ucheck <?php echo '' ?>  onclick="change_status(this)" <?php echo isset($activity_info['Activity']['status'])&&$activity_info['Activity']['status'] == 0?'':'checked'; ?> >有效
		          			<input type="hidden" name="activity_status" value="<?php echo isset($activity_info['Activity']['status'])?$activity_info['Activity']['status']:'1'; ?>">
		          		</label>
					</div>
					<div class="am-cf"></div>
				</div>
				</form>
				<div class="am-form-group">
					<label style="font-weight:400;margin-bottom:0;" class="am-u-lg-3 am-text-right am-u-sm-4">&nbsp;</label>
	          		<div class="am-u-lg-6 am-u-sm-8 ">
	          			<button type="button" class="am-btn am-btn-primary" onclick="submit()" style="min-width:130px;">提交</button>
					</div>
					<div class="am-cf"></div>
				</div>
			</div>
			
			<div class="am-cf"></div>
			<?php if(isset($activity_config_info)){ ?>
			<h3 style="font-size:18px;font-weight:400;margin-top:4px;border-bottom:1px solid #ccc;padding-left:5px;">
		    	<span style="float:left;margin-top:0.8rem;">自定义填写项</span>
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
			<?php if(isset($act_id)&&$act_id!='0'){ ?>
			<h3 style="font-size:18px;font-weight:400;margin-top:4px;border-bottom:1px solid #ccc;padding-left:5px;">
		    	<span style="float:left;margin-top:0.8rem;">活动标签</span>
		    	<div class="am-cf"></div>
		    </h3>
		    <div style="padding-top:10px;">
		    	<form class="am-form" id="tag_form" onsubmit="return false;">
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
<script>
	function get_type_id(obj){
		var org_id = $("#org_id").val();
		var content = '';
		var type_id = $("#type_id").val();
		var publisher_type=$("input[name='publisher_type']").val();
		
		
		if(publisher_type != '' && $(obj).val() != ''){
			if(publisher_type == 'O' && $(obj).val() == 'C'){
				$.ajax({
					url: web_base+"/courses/get_activity_type_id",
			            	type:"GET",
			            	data:{'organizations_id':org_id,'type':'organization'},
			            	dataType:"json",
			            	success: function(data){
			                	$(obj).siblings('div').show();
			                	$(obj).siblings('div').children('select').html('<option value="">请选择</option>');
			                	for(var i=0;i<data.length;i++){
			                		content = '';
			                		if(data[i].Course.id == type_id){
			                			content += '<option value="'+data[i].Course.id+'" selected>'+data[i].Course.name+'</option>';
			                		}else{
			                			content += '<option value="'+data[i].Course.id+'">'+data[i].Course.name+'</option>';
			                		}
			                		$(obj).siblings('div').children('select').append(content);
			                	}
			            	}
		        	});
			}else if(publisher_type == 'O' && $(obj).val() == 'E'){
				$.ajax({
					url: web_base+"/evaluations/get_activity_type_id",
			            	type:"GET",
			            	data:{'organizations_id':org_id,'type':'organization'},
			            	dataType:"json",
			            	success: function(data){
			                	$(obj).siblings('div').show();
			                	$(obj).siblings('div').children('select').html('<option value="">请选择</option>');
			                	for(var i=0;i<data.length;i++){
			                		content = '';
			                		if(type_id == data[i].Evaluation.id){
			                			content += '<option value="'+data[i].Evaluation.id+'" selected>'+data[i].Evaluation.name+'</option>';
			                		}else{
			                			content += '<option value="'+data[i].Evaluation.id+'">'+data[i].Evaluation.name+'</option>';
			                		}
			                		
			                		$(obj).siblings('div').children('select').append(content);
			                	}
			            	}
	        		});
			}else if(publisher_type == 'U' && $(obj).val() == 'C'){
				$.ajax({
					url: web_base+"/courses/get_activity_type_id",
	            	type:"GET",
	            	data:{'organizations_id':org_id,'type':'member'},
	            	dataType:"json",
	            	success: function(data){
	                	$(obj).siblings('div').show();
	                	$(obj).siblings('div').children('select').html('<option value="">请选择</option>');
	                	for(var i=0;i<data.length;i++){
	                		content = '';
	                		if(type_id == data[i].Course.id){
	                			content += '<option value="'+data[i].Course.id+'" selected>'+data[i].Course.name+'</option>';
	                		}else{
	                			content += '<option value="'+data[i].Course.id+'">'+data[i].Course.name+'</option>';
	                		}
	                		
	                		$(obj).siblings('div').children('select').append(content);
	                	}
	            	}
	        	});
			}else if(publisher_type == 'U' && $(obj).val() == 'E'){
				$.ajax({
					url: web_base+"/evaluations/get_activity_type_id",
	            	type:"GET",
	            	data:{'organizations_id':org_id,'type':'member'},
	            	dataType:"json",
	            	success: function(data){
	                	$(obj).siblings('div').show();
	                	$(obj).siblings('div').children('select').html('<option value="">请选择</option>');
	                	for(var i=0;i<data.length;i++){
	                		content = '';
	                		if(type_id == data[i].Evaluation.id){
	                			content += '<option value="'+data[i].Evaluation.id+'" selected>'+data[i].Evaluation.name+'</option>';
	                		}else{
	                			content += '<option value="'+data[i].Evaluation.id+'">'+data[i].Evaluation.name+'</option>';
	                		}
	                		
	                		$(obj).siblings('div').children('select').append(content);
	                	}
	            	}
	        	});
			}
		}else if(publisher_type == '' && $(obj).val() != ''){
			seevia_alert('请先选择发布者类型！');
			$(obj).find("option").eq(0).attr("selected",true);
		}else if($(obj).val() == ''){
			$('#activity_type_id').hide();
			$("input[name='activity_type_id']").find('option').eq(0).attr('selected',true);
		}
	}

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

	function change_status(obj){
		if($(obj).is(":checked")){
			$(obj).siblings('input').val(1);
		}else{
			$(obj).siblings('input').val(0);
		}
	}

	function submit(){
		if($("select[name='publisher_type']").val() == ''){
			seevia_alert('发布者类型不能为空！');
			return;
		}else if($("select[name='channel']").val() == ''){
			seevia_alert('活动渠道不能为空！');
			return;
		}else if($("input[name='activity_name']").val() == ''){
			seevia_alert('活动名称不能为空！');
			return;
		}else if($("select[name='channel']").val() == '1'){
			if($("input[name='activity_address']").val() == ''){
				seevia_alert('活动地址不能为空！');
				return;
			}
		}else if($("#start_date").val() == ''){
			seevia_alert('活动开始时间不能为空！');
			return;
		}else if($("#end_date").val() == ''){
			seevia_alert('活动结束时间不能为空！');
			return;
		}else if($("#end_date").val() <= $("#start_date").val()){
			seevia_alert('活动结束时间必须大于开始时间！');
			return;
		}else if($("input[name='activity_price']").val() == ''){
			seevia_alert('活动金额不能为空！');
			return;
		}
		//alert($("#elm").val());
		var org_id = $("#org_id").val();
		var getdata = $("#activity_form").serialize();
		$.ajax({
			url: web_base+"/activities/org_view/"+$("#activity_id").val()+'?organization_id='+$("#org_id").val(),
	        	type:"GET",
	        	data:getdata,
	        	dataType:"json",
	        	success: function(data){
		            	if(data.code == 1){
		            		window.location.href=web_base+'/activities/org_index?organization_id='+org_id;
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
            		//alert(data.ActivityConfig.options);
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

	function config_delete(id){
		var con_delete = function(){
			$.ajax({
				url: web_base+'/activities/config_delete/'+id,
	        	type:"POST",
	        	data:{},
	        	dataType:"json",
	        	success: function(data){
	            	if(data.code == 1){
	            		window.location.reload();
	            	}
	        	}
	    	});
		}
		seevia_confirm(con_delete,'是否确认删除？');
		
	}
	datetime();
	function datetime(){
		$("#start_date").datetimepicker({
			format: 'YYYY-MM-DD HH:00'
		});
		$("#end_date").datetimepicker({
			format: 'YYYY-MM-DD HH:00'
		});
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
			$(obj).before('<input type="text" style="width:100px;margin-right:1rem;margin-top:-6px;display:inline-block;" onblur="sub_tag(this)" onkeydown="sub_tag_key(this,event)">');
		}
		
	}

	function change_tag(obj){
		content = '<input type="text" id="'+$(obj).parent().attr('id')+'" style="width:100px;margin-right:1rem;margin-top:-6px;display:inline-block;" onblur="sub_tag(this)" onkeydown="sub_tag_key(this,event)" value="'+$(obj).html()+'">';
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
		var org_id = $("#org_id").val();
		$.ajax({
			url: web_base+'/activities/org_view/'+activity_id+'?organization_id='+org_id,
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

	function show_desc(obj){
		if($(obj).val() == 'U'){
			$("#publisher_desc").show();
		}else{
			$("#publisher_desc").hide();
		}
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

	function address_hide(obj){
		if($(obj).val() == 0 || $(obj).val() == 2){
			$("#activity_add").hide();
		}else{
			$("#activity_add").show();
		}
	}


</script>