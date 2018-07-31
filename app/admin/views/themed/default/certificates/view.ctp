<div class="am-g">
	<?php echo $form->create('certificates',array('action'=>'/view/'.(isset($certificate_data['Certificate']['id'])?$certificate_data['Certificate']['id']:''),'onsubmit'=>'return certificate_check();return false;'));?>
	<div class="am-u-lg-2  am-u-md-2 am-u-sm-4 am-detail-menu">
		<ul class="am-list admin-sidebar-list" data-am-scrollspy-nav="{offsetTop: 45}" style="position: fixed; z-index: 100; width: 15%;max-width:200px;">
		   	<li><a href="#basic_information"><?php echo '基本信息'?></a></li>
		</ul>
	</div>
	<div class="am-panel-group admin-content am-detail-view" id="accordion"  >
			<div id="basic_information"  class="am-panel am-panel-default">
				<div class="am-panel-hd">
					<h4 class="am-panel-title"><?php echo '基本信息'?></h4>
				</div>
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">
		      			<input type="hidden" name="data[Certificate][id]" value="<?php echo isset($certificate_data['Certificate'])?$certificate_data['Certificate']['id']:'0'; ?>" />
						<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label am-text-right"><?php echo '名字'?></label>
				    			<div class="am-u-lg-5 am-u-md-5 am-u-sm-5 am-text-left">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
				    					<input type="text" name="data[Certificate][name]" value="<?php echo isset($certificate_data['Certificate'])?$certificate_data['Certificate']['name']:''; ?>"/>
				    				</div>
				    			</div>
				    		</div>
							<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label am-text-right"><?php echo '身份证号码'?></label>
				    			<div class="am-u-lg-5 am-u-md-5 am-u-sm-5  am-text-left">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11 am-text-left">
				    					<input maxlength="18" type="text" name="data[Certificate][identity_no]" value="<?php echo isset($certificate_data['Certificate'])?$certificate_data['Certificate']['identity_no']:''; ?>"/>
				    				</div>
				    			</div>
				    		</div>
							<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label am-text-right"><?php echo '证书类型'?></label>
				    			<div class="am-u-lg-9 am-u-md-9 am-u-sm-9 am-text-left">
				    				<div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-text-left">
										<?php if(isset($informationresource_info['certificatetype'])&&sizeof($informationresource_info['certificatetype'])>0){foreach($informationresource_info['certificatetype'] as $k=>$v){ ?>
								<label class='am-radio am-radio-inline am-success'><input type='radio' name="data[Certificate][type]" value="<?php echo $k; ?>" <?php echo isset($certificate_data['Certificate'])&&$certificate_data['Certificate']['type']==$k?'checked':''; ?> /><?php echo $v; ?></label>
										<?php }} ?>
						</select>
				    				</div>
				    			</div>
				    		</div>
							<div class="am-form-group">
				    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label am-text-right"><?php echo '证书编码'?></label>
				    			<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
				    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
				    					<input type="text"  name="data[Certificate][certificate_number]" value="<?php echo isset($certificate_data['Certificate'])?$certificate_data['Certificate']['certificate_number']:''; ?>" is_exists='0' onblur="ajax_check_certificate_number()"/>
				    				</div>
				    			</div>
				    		</div>	
						<div class="am-form-group" style="margin-top: 11px;">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label am-text-right"><?php echo $ld['date']?></label>
							<div class="am-u-lg-5 am-u-md-5 am-u-sm-5 ">
								<div class="am-u-lg-9 am-u-md-11 am-u-sm-11">
									<input type='text' class="am-form-field am-radius" name="data[Certificate][register_date]" value="<?php echo isset($certificate_data['Certificate'])?$certificate_data['Certificate']['register_date']:''; ?>" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
								</div>
							</div>
				    		</div>
					</div>
				 	<div  class="btnouter">
					      <button type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
						<button type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
				  	</div> 
				</div>
			</div>
	</div>
	<?php echo $form->end(); ?>
</div>
<style type='text/css'>
label.am-radio-inline+.am-radio-inline{margin-right:10px;margin-left:0px;}
</style>
<script type='text/javascript'>
var check_certificate_number=true;
function certificate_check(){
	if($("input[name='data[Certificate][name]']").val()==''){
		alert('请输入名字');return false;
	}
	if($("input[name='data[Certificate][identity_no]']").val()==''){
		alert('请输入身份证号码');return false;
	}
	if($("input[name='data[Certificate][type]']:checked").length=='0'){
		alert('请输入证书类型'); return false;
	}
	if($("input[name='data[Certificate][certificate_number]']").val()==''){
		alert('请输入证书编码'); return false;
	}
	var certificate_number_is_exists=$("input[name='data[Certificate][certificate_number]']").attr('is_exists');
	if(certificate_number_is_exists=='1'){
		alert('证书编码已存在'); return false;
	}
	if($("input[name='data[Certificate][register_date]']").val()==''){
		alert('请输入日期'); return false;
	}
	return check_certificate_number;
}

function ajax_check_certificate_number(){
	var certificate_id=$("input[name='data[Certificate][id]']").val();
	var certificate_number=$("input[name='data[Certificate][certificate_number]']").val();
	if(certificate_number==''){
		$("input[name='data[Certificate][certificate_number]']").attr('is_exists','1');
		return false;
	}
	check_certificate_number=false;
	$.ajax({
		url:admin_webroot+"certificates/ajax_check_certificate_number",
		type:"POST",
		data:{'certificate_id':certificate_id,'certificate_number':certificate_number},
		dataType:"json",
		success:function(data){
			if(data.code=='1'){
				check_certificate_number=true;
				$("input[name='data[Certificate][certificate_number]']").attr('is_exists','0');
			}else{
				$("input[name='data[Certificate][certificate_number]']").attr('is_exists','1');
				alert(data.message);
			}
		}
	});
}
</script>