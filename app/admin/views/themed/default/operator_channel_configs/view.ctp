<?php
	//pr($account_information);
?>
<style type="text/css">
label{font-weight:normal;}
body{font-size: 1.25rem;}
@media only screen and (max-width: 640px){body {word-wrap: normal;}}
.am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
.btnouter{}
.img_select{max-width:150px;max-height:120px;}

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
.am-u-lg-9.am-u-md-11.am-u-sm-11{margin-top: 19px;}
.img_organization{  
    cursor: pointer;  
    transition: all 2s;  
} 
.payment_time .am-selected.am-dropdown{width:33%!important;float: left;}
.payment_time .am-selected-btn.am-btn.am-dropdown-toggle.am-btn-default{height: 35px;}
</style>
<div class="am-g">
	<!-- 导航 -->
	<?php echo $form->create('/operator_channel_configs',array('action'=>'view/','id'=>'operator_channel_configs_form','name'=>'operator_channel_configs_form','type'=>'POST'));?>
	<div style="width: 95%;margin-left: 2.5%;">
		<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
		    <ul>
			   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
			</ul>
		</div>
		<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
		    <input type="button" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit'];?>" onclick="operator_channel_configs_add()"/>
	        <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
		</div>
	</div>
	<div class="am-panel-group admin-content am-detail-view" id="accordion" style="width: 95%;margin-right: 2.5%;">
			<div id="basic_information"  class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title">
						<?php echo $ld['basic_information']?>
					</h4>
			    </div>
			    <input type="hidden" name="data[OperatorChannelConfig][id]" value="<?php echo $id ?>">
			    <div class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
		      			<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label">渠道</label>
			    			<div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
				    			<select data-am-selected="{maxHeight:150}" name="data[OperatorChannelConfig][operator_channel_id]" id="operator_channel_id">
				                    <?php if(isset($operator_channel_list)&&sizeof($operator_channel_list)>0){foreach ($operator_channel_list as $k => $v) { ?>
				                	<option value="<?php echo $k ?>" <?php if($operator_channel_config['OperatorChannelConfig']['operator_channel_id']==$k){echo 'selected';} ?>><?php echo $v ?></option>
				                    <?php }} ?>
				    			</select>
				    		</div>
			    		</div>
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label">配置编码</label>
			    			<div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
			    				<input type="text" value="<?php echo $operator_channel_config['OperatorChannelConfig']['code'] ?>" name="data[OperatorChannelConfig][code]" id="code">
			    			</div>
			    		</div>	
						<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label">名称</label>
			    			<div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
				    			<input type="text" value="<?php echo $operator_channel_config['OperatorChannelConfig']['name'] ?>" name="data[OperatorChannelConfig][name]" id="name">
				    		</div>
			    		</div>
			    		<div class="am-form-group">
			    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label">描述</label>
			    			<div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
				    			<textarea name="data[OperatorChannelConfig][description]" id="description" cols="30" rows="10"><?php echo $operator_channel_config['OperatorChannelConfig']['description'] ?></textarea>
				    		</div>
			    		</div>
			    		<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label">只读</label>
							<label class="am-radio am-success" style="margin-left: 15px;">
								<input type="radio" name="data[OperatorChannelConfig][readonly]" value="1" data-am-ucheck <?php if($operator_channel_config['OperatorChannelConfig']['readonly']==1){echo 'checked';} ?>>
								是
							</label>
							<label class="am-radio am-success" style="margin-left: 15px;">
								<input type="radio" name="data[OperatorChannelConfig][readonly]" value="0" data-am-ucheck <?php if($operator_channel_config['OperatorChannelConfig']['readonly']==0){echo 'checked';} ?>>
								否
							</label>	
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-view-label">状态</label>
							<label class="am-radio am-success" style="margin-left: 15px;">
								<input type="radio" name="data[OperatorChannelConfig][status]" value="1" data-am-ucheck <?php if($operator_channel_config['OperatorChannelConfig']['status']==1){echo 'checked';} ?>>
								有效
							</label>
							<label class="am-radio am-success" style="margin-left: 15px;">
								<input type="radio" name="data[OperatorChannelConfig][status]" value="0" data-am-ucheck <?php if($operator_channel_config['OperatorChannelConfig']['status']==0){echo 'checked';} ?>>
								无效
							</label>	
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style type='text/css'>
.am-view-label{margin-top:0px;}
</style>

<script>
function operator_channel_configs_add(){
	if($('#code').val()==''){
		alert('配置编码不能为空！');
		return false;
	}
	if($('#name').val()==''){
		alert('名称不能为空！');
		return false;
	}
    $.ajax({ 
		url: admin_webroot+"/operator_channel_configs/operator_channel_configs_add",
		data:$('#operator_channel_configs_form').serialize(),
		dataType:"json",
		type:"POST",
		success: function(data){
			if(data.code == '1'){
				window.location.href=admin_webroot+'operator_channel_configs/index';
			}
	    }
	});
}
</script>