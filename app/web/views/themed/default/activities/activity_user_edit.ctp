<style type='text/css'>
.am-u-lg-2.am-u-md-2.am-u-sm-4.am-form-label{text-align: left;}
#accordion{font-size: 1.4rem;}
.am-product label{font-weight: normal;}
.am-selected.am-dropdown{width: 100%;}
.am-selected-content.am-dropdown-content{width: 100%;}
.am-selected-btn.am-btn.am-dropdown-toggle.am-btn-default{font-size: 1.4rem;}
.am-radio-inline{padding-top: 0!important;}
.am-u-lg-2.am-u-md-2.am-u-sm-12{margin-bottom: 10px;text-align: left;}
.am-form-group{margin-bottom: 10px;}
</style>
<script src="<?php echo $webroot.'plugins/kindeditor/kindeditor-min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<script src="<?php echo $webroot.'plugins/ajaxfileupload.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<div class="am-product" style="padding:0;">
    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
        <div style="padding:10px;font-size: 20px;"></div>
        <?php echo $form->create('/activities',array('action'=>'activity_user_edit','id'=>'activity_user_edit_form','name'=>'activity_user_edit','class'=>'am-form am-form-horizontal','type'=>'POST'));?>
        <input type="hidden" name="data[activity_id]" value="<?php echo $activity_id ?>">
        <input type="hidden" name="data[user_id]" value="<?php echo isset($_GET['user_id'])?$_GET['user_id']:(isset($_SESSION['User'])?$_SESSION['User']['User']['id']:0); ?>">
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd" style="max-width: 600px;margin:auto;">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Basic_information_pancel'}"><?php if(!isset($_GET['user_id'])){echo '填写信息';}else{echo '报名用户信息';} ?></h4>
            </div>
            <div style="padding-top: 10px;max-width: 600px;margin:auto;" id="Basic_information_pancel" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd">
                	<div class="am-form-group">
                		<?php if(isset($_GET['user_info'])){ ?>
						<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="padding-top: 8px;">姓名</label>
                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-top:8px;">
	                        <span><?php echo isset($user_activitiy['ActivityUser']['name'])?$user_activitiy['ActivityUser']['name']:''; ?></span>
                        </div>
                		<?php }else{ ?>
                		<label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 8px;">姓名</label>
                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-12">
                        	<input id="ActivityUser_name" type="text" name="ActivityUser_name" value="<?php echo isset($user_activitiy['ActivityUser']['name'])?$user_activitiy['ActivityUser']['name']:''; ?>" style="width: 80%;display: inline-block;">
	                        <em class="required text" style="position: relative; top: 5px; color: red; left: 10px;">*</em>
                        </div>
                		<?php } ?>
                    </div>
                    <div class="am-form-group">
                    	<?php if(isset($_GET['user_info'])){ ?>
						<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="padding-top: 8px;">手机号</label>
                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-top:8px;">
	                        <span><?php echo isset($user_activitiy['ActivityUser']['mobile'])?$user_activitiy['ActivityUser']['mobile']:''; ?></span>
                        </div>
                		<?php }else{ ?>
                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 8px;">手机号</label>
                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-12">
                        	<input id="ActivityUser_mobile" type="text" name="ActivityUser_mobile" value="<?php echo isset($user_activitiy['ActivityUser']['mobile'])?$user_activitiy['ActivityUser']['mobile']:''; ?>" style="width: 80%;display: inline-block;">
	                        <em class="required text" style="position: relative; top: 5px; color: red; left: 10px;">*</em>
                        </div>
                        <?php } ?>
                    </div>
                	<?php if(isset($activity_configs)&&sizeof($activity_configs)>0){foreach ($activity_configs as $k => $v) { ?>
                	<?php //pr($v); ?>
                		<?php if($v['ActivityConfig']['type']=='text'){ ?>
	                    <div class="am-form-group">
	                    	<?php if(isset($_GET['user_info'])){ ?>
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="padding-top: 8px;"><?php echo $v['ActivityConfig']['name'] ?></label>
	                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-top:8px;">
		                        <span><?php echo isset($activity_user_config_datas[$v['ActivityConfig']['id']])?$activity_user_config_datas[$v['ActivityConfig']['id']]['config_value']:''; ?></span>
	                        </div>
	                		<?php }else{ ?>
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 8px;"><?php echo $v['ActivityConfig']['name'] ?></label>
	                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-12">
	                        	<input type="text" name="data[ActivityUserConfig][<?php echo $v['ActivityConfig']['id'] ?>]" value="<?php echo isset($activity_user_config_datas[$v['ActivityConfig']['id']])?$activity_user_config_datas[$v['ActivityConfig']['id']]['config_value']:''; ?>" style="width: 80%;display: inline-block;">
	                        	<?php if($v['ActivityConfig']['is_required']==1){ ?>
		                        <em class="required text" style="position: relative; top: 5px; color: red; left: 10px;">*</em>
		                        <?php } ?>
	                        </div>
	                        <?php } ?>
	                    </div>
	                    <?php } ?>

	                    <?php if($v['ActivityConfig']['type']=='checkbox'){ ?>
	                    <?php if($v['ActivityConfig']['options']!=''){
	                    			$activity_config_options = explode(chr(13).chr(10),$v['ActivityConfig']['options']);
	                    ?>
	                    <div class="am-form-group">
	                    	<?php if(isset($_GET['user_info'])){ ?>
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="padding-top: 8px;"><?php echo $v['ActivityConfig']['name'] ?></label>
	                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-top:8px;">
	                            <span><?php 
			                        	if(isset($activity_user_config_datas[$v['ActivityConfig']['id']]['config_value'])){
		                        			$config_check_prev[$v['ActivityConfig']['id']] = explode(',',$activity_user_config_datas[$v['ActivityConfig']['id']]['config_value']);
		                        			echo implode('&nbsp;',$config_check_prev[$v['ActivityConfig']['id']]);
			                        	}
	                        	?></span>
	                        </div>
	                		<?php }else{ ?>
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 2px;"><?php echo $v['ActivityConfig']['name'] ?></label>
	                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-12">
	                        <?php
						if(isset($activity_user_config_datas[$v['ActivityConfig']['id']]['config_value'])){
							$config_check_prev[$v['ActivityConfig']['id']] = explode(',',$activity_user_config_datas[$v['ActivityConfig']['id']]['config_value']);
						}else{
							$config_check_prev[$v['ActivityConfig']['id']]=array();
						}
	                        ?>
	                        	<?php foreach ($activity_config_options as $kk => $vv) { ?>
	                            <label class="am-checkbox-inline am-success" style="padding-top:0px;margin-right: 10px;"><input data-am-ucheck type="checkbox" name="data[ActivityUserConfig][<?php echo $v['ActivityConfig']['id'] ?>][]" value="<?php echo $vv; ?>"  <?php echo in_array($vv,$config_check_prev[$v['ActivityConfig']['id']])?'checked':'';  ?>/><?php echo $vv; ?></label>
	                            <?php } ?>
	                            <?php if($v['ActivityConfig']['is_required']==1){ ?>
		                        <em class="required checkbox" style="position: relative; top: 5px; color: red; left: 10px;">*</em>
		                        <?php } ?>
	                        </div>
	                        <?php } ?>
	                    </div>
	                    <?php } ?>
	                    <?php } ?>
	                    <?php if($v['ActivityConfig']['type']=='radio'){ ?>
	                    <?php if($v['ActivityConfig']['options']!=''){ ?>
	                    <?php 
	                    	$activity_config_options = explode(chr(13).chr(10),$v['ActivityConfig']['options']);
	                    ?>
	                    <div class="am-form-group">
	                    	<?php if(isset($_GET['user_info'])){ ?>
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="padding-top: 8px;"><?php echo $v['ActivityConfig']['name'] ?></label>
	                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-top:8px;">
		                        <span><?php echo isset($activity_user_config_datas[$v['ActivityConfig']['id']]['config_value'])?$activity_user_config_datas[$v['ActivityConfig']['id']]['config_value']:''; ?></span>
	                        </div>
	                		<?php }else{ ?>
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 2px;"><?php echo $v['ActivityConfig']['name'] ?></label>
	                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-12">
	                        	<?php foreach ($activity_config_options as $kk => $vv) { ?>
	                            <label class="am-radio-inline am-success" style="padding-top:0px;margin-right: 10px;"><input data-am-ucheck type="radio" name="data[ActivityUserConfig][<?php echo $v['ActivityConfig']['id'] ?>]" value="<?php echo $vv ?>" <?php if(isset($activity_user_config_datas[$v['ActivityConfig']['id']])&&$activity_user_config_datas[$v['ActivityConfig']['id']]['config_value']==$vv){echo 'checked';} ?> /><?php echo $vv; ?></label>
	                            <?php } ?>
	                            <?php if($v['ActivityConfig']['is_required']==1){ ?>
		                        <em class="required radio" style="position: relative; top: 5px; color: red; left: 10px;">*</em>
		                        <?php } ?>
	                        </div>
	                        <?php } ?>
	                    </div>
	                    <?php } ?>
	                    <?php } ?>
	                    <?php if($v['ActivityConfig']['type']=='image'){ ?>
	                    <div class="am-form-group">
	                    	<?php if(isset($_GET['user_info'])){ ?>
							<label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label" style="padding-top: 8px;"><?php echo $v['ActivityConfig']['name'] ?></label>
	                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-top:8px;">
		                        <?php if(isset($activity_user_config_datas[$v['ActivityConfig']['id']]['config_value'])&&$activity_user_config_datas[$v['ActivityConfig']['id']]['config_value']!=''){ ?>
	                            <figure data-am-widget="figure" class="am am-figure am-figure-default am-no-layout am-figure-zoomable" data-am-figure="{  pureview: 'true' }">
	                            <img style="max-height: 200px;max-width: 200px;" src="<?php echo $server_host.$activity_user_config_datas[$v['ActivityConfig']['id']]['config_value'] ?>" data-rel="<?php echo $server_host.$activity_user_config_datas[$v['ActivityConfig']['id']]['config_value'] ?>" alt="" id="img_logo" >
	                            </figure>
	                            <?php }else{ ?>
	                            
	                            <?php } ?>
	                        </div>
	                		<?php }else{ ?>
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 2px;"><?php echo $v['ActivityConfig']['name'] ?></label>
	                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-12 am-form-file">
	                            <div class="am-form-group am-form-file">
	                                <button type="button" class="am-btn am-btn-default am-btn-sm">
	                                <i class="am-icon-cloud-upload"></i> 选择要上传的图片</button>
	                                <?php if($v['ActivityConfig']['is_required']==1){ ?>
			                        <em class="required image" style="position: relative; top: 5px; color: red; left: 10px;">*</em>
			                        <?php } ?>
	                                <input type="file" multiple name="activity_user_img_<?php echo $v['ActivityConfig']['id'] ?>" id="activity_user_img_<?php echo $v['ActivityConfig']['id'] ?>" onchange="ajax_upload_media(this,this.id)" id="org_logo">
	                                <input type="hidden" multiple name="data[ActivityUserConfig][<?php echo $v['ActivityConfig']['id'] ?>]"  value="<?php echo isset($activity_user_config_datas[$v['ActivityConfig']['id']]['config_value'])&&$activity_user_config_datas[$v['ActivityConfig']['id']]['config_value']!=''?$activity_user_config_datas[$v['ActivityConfig']['id']]['config_value']:''; ?>">
	                            </div>
	                            <?php if(isset($activity_user_config_datas[$v['ActivityConfig']['id']]['config_value'])&&$activity_user_config_datas[$v['ActivityConfig']['id']]['config_value']!=''){ ?>
	                            <figure data-am-widget="figure" class="am am-figure am-figure-default am-no-layout am-figure-zoomable" data-am-figure="{  pureview: 'true' }">
	                            <img style="max-height: 200px;max-width: 200px;" src="<?php echo $server_host.$activity_user_config_datas[$v['ActivityConfig']['id']]['config_value'] ?>" data-rel="<?php echo $server_host.$activity_user_config_datas[$v['ActivityConfig']['id']]['config_value'] ?>" alt="" id="img_logo" >
	                            </figure>
	                            <?php }else{ ?>
	                            
	                            <?php } ?>
	                            <img src="" data-rel="" alt="" id="img_logo" style="display:none;max-width:100%;max-height: 200px;max-width: 200px;">
	                        </div>
	                        <div class="am-cf"></div>
	                        <?php } ?>
		                </div>
	                    <?php } ?>
                    <?php }} ?>
                    <?php if(isset($_GET['user_id'])){ ?>
					    <?php if(isset($_GET['user_info'])){ ?>
					    
						<label class="am-u-lg-4 am-u-md-4 am-u-sm-12 am-form-label" style="padding-top: 8px;">活动门票二维码</label>
                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-top:8px;">
	                        <div id='activity_qrcode'></div>
                        </div>
						<?php } ?>
					<?php } ?>
                    <div style="clear:both;"></div>
                </div>
            </div>
        </div>
        <?php if(!isset($_GET['user_id'])){ ?>
	        <div class="btnouter" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;max-width: 600px;margin:auto;">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 8px;">&nbsp;</label>
			<div class="am-u-lg-8 am-u-md-8 am-u-sm-12">
				<?php if(isset($user_activitiy['ActivityUser'])&&!empty($user_activitiy['ActivityUser'])){ ?>
					<?php if(isset($pay_judge['ActivityUser'])&&$pay_judge['ActivityUser']['payment_status']=='0'){ ?>
						<button class='am-btn am-btn-success' type='button' onclick="virtual_purchase_pay('activity',<?php echo $pay_judge['ActivityUser']['activity_id'] ?>)"><?php echo $ld['pay_now']; ?></button>
					<?php }else{ ?>
					<div id='activity_qrcode'></div>
					<?php } ?>
				<?php }else{ ?>
					<button type='button' id="baoming_btn" style="margin-right: 0;margin:auto;min-width:120px;" class="am-btn am-btn-secondary am-btn-sm am-btn-bottom am-radius am-btn-block" onclick="activity_user_add('<?php echo $activity_id; ?>')">报名</button>
				<?php } ?>
			</div>
	        </div>
        <?php }else{ ?>
        	<?php if(!isset($_GET['user_info'])){ ?>
        	<div style="margin-bottom:0;max-width: 600px;margin:auto;">
        		<label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 8px;">&nbsp;</label>
        		<div class="am-u-lg-8 am-u-md-8 am-u-sm-12">
		            <div style="margin-right: 0;margin:auto;min-width: 130px;" class="am-btn am-btn-secondary am-btn-sm am-btn-bottom am-radius am-btn-block" onclick="activity_user_sub('<?php echo $activity_id; ?>')">保存</div>
		        </div>
	            <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label am-hide-sm-only" style="padding-top: 8px;">&nbsp;</label>
        		<div class="am-u-lg-8 am-u-md-8 am-u-sm-12" style="margin-top: 10px;">
		            <div style="margin-right: 0;margin:auto;min-width: 130px;" class="am-btn am-btn-primary am-btn-sm am-btn-bottom am-radius am-btn-block" onclick="send_out('<?php echo isset($_GET['user_id'])?$_GET['user_id']:''; ?>')">发验证码给用户</div>
		            </div>
	            </div>
	            <div class="am-cf"></div>
	        </div>
	        <?php } ?>
        <?php } ?>
        <?php echo $form->end(); ?>
    </div>
    <div class="am-cf"></div>
    
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="wechat_ajax_payaction">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
      <div class="am-text-center"><?php echo $html->image('/theme/default/images/loading.gif');  ?></div>
    </div>
  </div>
</div>
<script>
	function activity_user_add(id){
		var phone = $('#ActivityUser_mobile').val();
		if(phone.trim()==''){
			seevia_alert("请填写手机号");
			return false;
		}
		if(!(/^1[34578]\d{9}$/.test(phone))){ 
			seevia_alert("手机号码有误，请重填");
			return false;
		} 
		var required = document.querySelectorAll('.required');
		for(var i = 0;i<required.length;i++){
			if(required[i].className=='required text'){
				if(required[i].parentNode.childNodes[1].value == ''){
					seevia_alert(required[i].parentNode.parentNode.childNodes[1].innerText+'不能为空！');
					return false;
				}
			}
			if(required[i].className=='required checkbox'){
				if(required[i].parentNode.childNodes[1].childNodes[0].value == ''){
					seevia_alert(required[i].parentNode.parentNode.childNodes[1].innerText+'不能为空！');
					return false;
				}
			}
			if(required[i].className=='required image'){
				if(required[i].parentNode.parentNode.childNodes[1].childNodes[7].value == ''){
					seevia_alert(required[i].parentNode.parentNode.parentNode.childNodes[1].innerText+'图片不能为空！');
					return false;
				}
			}
		}
		$('#baoming_btn').attr('disabled',true);
        var postData=$('#activity_user_edit_form').serialize();
        $.ajax({
            url:web_base+"/activities/activity_user_add",
            type:"POST",
            data:postData,
            dataType:"json",
            success: function(data){
                $('#baoming_btn').attr('disabled',false);
                if(data.code==1){
                	if(data.message=='payment_status_1'){
                		seevia_alert_func(function(){
                			window.location.href=web_base+'/user_activities/index';
	                	},'提交成功!');
                	}else{
                		virtual_purchase_pay('activity',id);
                	}
                }else{
                	seevia_alert('报名失败！');
                }
            }
        });
        
	}
	
	function activity_user_sub(id){
		var phone = $('#ActivityUser_mobile').val();
		if(!(/^1[34578]\d{9}$/.test(phone))){ 
	        seevia_alert("手机号码有误，请重填！");
	        return false; 
	    } 
		var required = document.querySelectorAll('.required');
		for(var i = 0;i<required.length;i++){
			if(required[i].className=='required text'){
				if(required[i].parentNode.childNodes[1].value == ''){
					seevia_alert(required[i].parentNode.parentNode.childNodes[1].innerText+'不能为空！');
					return false;
				}
			}
			if(required[i].className=='required checkbox'){
				var checkbox_name = required[i].parentNode.childNodes[1].childNodes[0].name;
				var required_arr=document.getElementsByName(checkbox_name);
				var required_list = [];
				for(var j = 0;j<required_arr.length;j++){
					if(required_arr[j].checked){
						required_list[j] = j;
					}
				}
				if(required_list.length==0){
					seevia_alert(required[i].parentNode.parentNode.childNodes[1].innerText+'不能为空！');
					return false;
				}
			}
			if(required[i].className=='required image'){
				if(required[i].parentNode.parentNode.childNodes[1].childNodes[7].value == ''){
					seevia_alert(required[i].parentNode.parentNode.parentNode.childNodes[1].innerText+'图片不能为空！');
					return false;
				}
			}
		}
		var user_id = "<?php echo isset($_GET['user_id'])?$_GET['user_id']:''; ?>";
		var postData=$('#activity_user_edit_form').serialize();
		$.ajax({
			url:web_base+"/activities/activity_user_sub?user_id="+user_id,
			type:"POST",
			data:postData,
			dataType:"json",
			success: function(data){
				seevia_alert('提交成功!');
				window.location.href=web_base+'/activities/org_activity_user/'+id;
			}
		});
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
			  url:'/activities/ajax_upload_media',
			  secureuri:false,
			  fileElementId:img_id,
			  data:{'org_id':org_id,'org_code':img_id},
			  dataType: 'json',
			  success: function (data){
			  		//alert(img_id);
			  	 $('#'+img_id).siblings('input[type="hidden"]').val(data.img_url);
			  	 $('#'+img_id).parent().siblings("img").attr('src',data.img_url);
			  	 $('#'+img_id).parent().siblings("img").show();
			  	 $('#'+img_id).parent().siblings("figure").hide();
			  }
		 });
		return false;
	}

	//生成二维码
	activity_qrcode();
	function activity_qrcode(){
		var activity_id=$("input[name='data[activity_id]']").val();
		var user_id=$("input[name='data[user_id]']").val();
		var qrcode_link=location.protocol+"//"+window.location.host+web_base+"/activities/activity_user_check/"+activity_id+'/'+user_id;
		$qr = $('#activity_qrcode');
	    	var QRCode = $.AMUI.qrcode;
	    	$qr.html(new QRCode({text: qrcode_link}));
	}

	function check_activity_qrcode(btn){
		if(typeof(wx)!='undefined'){
			wx.scanQRCode({
				needResult:1, // 默认为0，扫描结果由微信处理，1则直接返回扫描结果，
				scanType: ["qrCode"], // 可以指定扫二维码还是一维码，默认二者都有
				success: function (res) {
					var result = res.resultStr; // 当needResult 为 1 时，扫码返回的结果
					seevia_alert(result);
				}
			});
		}
	}

	function send_out(user_id){
		$.ajax({
			url:web_base+"/activities/activity_send_out",
			type:"POST",
			data:{'user_id':user_id,'activity_id':$("input[name='data[activity_id]']").val()},
			dataType:"json",
			success: function(data){
				if(data.code=='1'){
					seevia_alert('发送成功');
				}else{
					seevia_alert('发送失败');
				}
			}
        	});
	}
</script>