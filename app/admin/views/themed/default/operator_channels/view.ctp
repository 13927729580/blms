<style type="text/css">
label{font-weight:normal;}
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
</style>
<div class="am-g">
<div class="am-panel-group admin-content am-detail-view" id="accordion"  style="width: 95%;margin-right: 2.5%;">
<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
    <ul>
	   	<li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
	   	<?php if(isset($channel_config_info)&&count($channel_config_info)>0){ ?>
	   	<li><a href="#operator_source_config"><?php echo $ld['operator_source_config']?></a></li>
	   	<?php } ?>
	</ul>
</div>
<form action="" id="basic_form" onsubmit="return form_check()" method="POST">
<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
    <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" onclick="" value="<?php echo $ld['d_submit'];?>" />  
    <input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
</div>
		<!-- 导航结束 -->
	<div id="basic_information"  class="am-panel am-panel-default">
  		<div class="am-panel-hd">
			<h4 class="am-panel-title">
				<?php echo $ld['basic_information']?>
			</h4>
	    </div>
	    
	    <div class="am-panel-collapse am-collapse am-in">
      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
	    		<div class="am-form-group">
	    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo '渠道编码'?></label>
	    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
	    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
	    					<input type="text" name="channel_code" value="<?php echo isset($channel_info['OperatorChannel']['code'])&&$channel_info['OperatorChannel']['code']!=''?$channel_info['OperatorChannel']['code']:''; ?>" maxlength="50">
		    				<span style="position: absolute;right: 0;top: 9px;color: red;">*</span>
	    				</div>
	    			</div>
	    		</div>
			</div>
		</div>
		<div class="am-panel-collapse am-collapse am-in">
      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
	    		<div class="am-form-group">
	    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo '渠道名称'?></label>
	    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
	    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
	    					<input type="text" name="channel_name" value="<?php echo isset($channel_info['OperatorChannel']['name'])&&$channel_info['OperatorChannel']['name']!=''?$channel_info['OperatorChannel']['name']:''; ?>" maxlength="60">
		    				<span style="position: absolute;right: 0;top: 9px;color: red;">*</span>
	    				</div>
	    			</div>
	    		</div>
			</div>
		</div>
		<div class="am-panel-collapse am-collapse am-in">
      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
	    		<div class="am-form-group">
	    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo '渠道描述'?></label>
	    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
	    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
	    					<textarea name="channel_desc" id="" cols="30" rows="10" style="resize:none;" maxlength="250"><?php echo isset($channel_info['OperatorChannel']['description'])&&$channel_info['OperatorChannel']['description']!=''?$channel_info['OperatorChannel']['description']:''; ?></textarea>
	    				</div>
	    			</div>
	    		</div>
			</div>
		</div>
		<div class="am-panel-collapse am-collapse am-in">
      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
	    		<div class="am-form-group">
	    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo '状态'?></label>
	    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
	    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
	    					<label class="am-radio am-success">
						      <input type="radio" name="channel_status" value="1" data-am-ucheck <?php echo (isset($channel_info['OperatorChannel']['status'])&&$channel_info['OperatorChannel']['status'] == 1)||isset($channel_info['OperatorChannel']['status']) == false?'checked':''; ?>>
						      有效
						    </label>
						    <label class="am-radio am-success" style="margin-left:0.5rem;">
						      <input type="radio" name="channel_status" value="0" data-am-ucheck <?php echo isset($channel_info['OperatorChannel']['status'])&&$channel_info['OperatorChannel']['status'] == 0?'checked':''; ?>>
						      无效 
						    </label>
		    				<span style="position: absolute;right: 0;top: 9px;color: red;">*</span>
	    				</div>
	    			</div>
	    		</div>
			</div>
		</div>
	</div>
	<?php if(isset($channel_config_info)&&count($channel_config_info)>0){ ?>
	<div id="operator_source_config" class="am-panel am-panel-default">
		<div class="am-panel-hd">
			<h4 class="am-panel-title">
				<?php echo $ld['operator_source_config']?>
			</h4>
	    </div>
	    <?php //pr($channel_config_info); ?>
	<?php foreach ($channel_config_info as $k => $v) { ?>
	    <div class="am-panel-collapse am-collapse am-in">
      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
	    		<div class="am-form-group">
	    			<label class="am-u-lg-2 am-u-md-2 am-u-sm-3 am-view-label"><?php echo $v['OperatorChannelConfig']['name'] ?></label>
	    			<div class="am-u-lg-9 am-u-md-10 am-u-sm-9">
	    				<div class="am-u-lg-9 am-u-md-11 am-u-sm-11" >
	    				<?php if(isset($v['OperatorChannelConfig']['readonly'])&&$v['OperatorChannelConfig']['readonly'] == 0){ ?>
	    					<input type="text" name="data[config][<?php echo $v['OperatorChannelConfig']['code'] ?>]" value="<?php echo isset($channel_config_value_check[$v['OperatorChannelConfig']['code']]['OperatorChannelConfigValue']['config_value'])?$channel_config_value_check[$v['OperatorChannelConfig']['code']]['OperatorChannelConfigValue']['config_value']:''; ?>"  >
	    				<?php }else{ ?>
	    					<label class="am-view-label" style="margin-top:9px;"><?php echo isset($channel_config_value_check[$v['OperatorChannelConfig']['code']]['OperatorChannelConfigValue']['config_value'])?$channel_config_value_check[$v['OperatorChannelConfig']['code']]['OperatorChannelConfigValue']['config_value']:''; ?></label>
	    				<?php } ?>
		    				<!-- <span style="position: absolute;right: 0;top: 9px;color: red;">*</span> -->
	    				</div>
	    			</div>
	    		</div>
			</div>
		</div>
	<?php } ?>
	</div>
	<?php } ?>
	</form>
</div>
<script>
	function form_check(){
		if($('input[name="channel_code"]').val() == ''){
			alert('渠道编码不能为空！');
			return false;
		}else if($('input[name="channel_name"]').val() == ''){
			alert('渠道 名称不能为空！');
			return false;
		}
		return true;
	}
</script>
