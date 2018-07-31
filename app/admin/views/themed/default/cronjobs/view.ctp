<style type="text/css">
label{font-weight:normal}
.btnouter{margin:50px;}
 .am-form-horizontal .am-checkbox{padding-top: 0em;}
 .am-checkbox input[type="checkbox"]{margin-left:0px;}
 .scrollspy-nav {
    top: 0;
    z-index: 500;
    background: #5eb95e;
    width: 100%;
    padding: 0 10px;
  }

  .scrollspy-nav ul {
    margin: 0;
    padding: 0;
  }

  .scrollspy-nav li {
    display: inline-block;
    list-style: none;
  }

  .scrollspy-nav a {
    color: #eee;
    padding: 10px 20px;
    display: inline-block;
  }

  .scrollspy-nav a.am-active {
    color: #fff;
    font-weight: bold;
  }
  
  .crumbs{
  	padding-left:0;
  	margin-bottom:22px;
  }
</style>
<div class="">
	<!-- 导航条 -->	
	<div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
		<ul>
		   	<li><a href="#cronjob_info"><?php echo $ld['cronjob_info']?></a></li>
		</ul>
	</div>
	<div class="am-panel-group admin-content" id="accordion">
		<?php echo $form->create('Cronjob',array('action'=>'view/'.(isset($cronjob_info['Cronjob']['id'])?$cronjob_info['Cronjob']['id']:''),'name'=>'userformedit'));?> 
		<!-- 右上角按钮 -->
		<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin:0;">
			<button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-radius" value=""><?php echo $ld['d_submit'];?></button>
			<button style="margin-right: 0;" type="reset" class="am-btn am-btn-default am-btn-sm am-radius" value="" ><?php echo $ld['d_reset']?></button>
		</div>
			<input id="id" type="hidden" name="data[Cronjob][id]" value="<?php echo isset($cronjob_info['Cronjob']['id'])?$cronjob_info['Cronjob']['id']:'';?>">	
			<div class="am-panel am-panel-default">
		  		<div class="am-panel-hd">
					<h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#cronjob_info'}">
						<?php echo $ld['cronjob_info']?>
					</h4>
			    </div>
			    <div id="cronjob_info" class="am-panel-collapse am-collapse am-in">
		      		<div class="am-panel-bd am-form-detail am-form am-form-horizontal">	
					
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['system'] ?></label>
							<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
								<select data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}" name="data[Cronjob][system_code]">
							<option value=""><?php echo $ld['please_select']; ?></option>
							<?php if(isset($SystemList)&&sizeof($SystemList)>0){foreach($SystemList as $v){ ?>
							<option value="<?php echo $v; ?>" <?php echo isset($cronjob_info['Cronjob']['system_code'])&&$cronjob_info['Cronjob']['system_code']==$v?'selected':''; ?>><?php echo $v; ?></option>
							<?php }} ?>
						</select>
							</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['module'] ?></label>
							<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
								<input type='text' name="data[Cronjob][module_code]" value="<?php echo isset($cronjob_info['Cronjob']['module_code'])?$cronjob_info['Cronjob']['module_code']:''; ?>" />
							</div>
						</div>
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['task_name']?>:</label>
							<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
								<input type="text" id="task_name"  maxlength="60" name="data[Cronjob][task_name]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['task_name']:'';?>" />
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['task_code']?>:</label>
							<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
								<input type="text" id="task_code"  maxlength="60" name="data[Cronjob][task_code]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['task_code']:'';?>" />
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['interval_time']?>:</label>
							<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
								<input type="text" id="interval_time"  maxlength="60" name="data[Cronjob][interval_time]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['interval_time']:'';?>" />
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['app_code']?>:</label>
							<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
								<select class="all" name="data[Cronjob][app_code]" id="cronjob_app" data-am-selected>
									<option value="0"><?php echo $ld['select_app_code']?></option>
									<?php if(isset($appcode_tree) && sizeof($appcode_tree)>0){?>
									<?php foreach($appcode_tree as $k=>$v){?>
									  <option value="<?php echo $v['Application']['code']?>" <?php if((isset($cronjob_info)?$cronjob_info['Cronjob']['app_code']:'') == $v['Application']['code'] && (isset($cronjob_info)?$cronjob_info['Cronjob']['app_code']:'')!=""){?>selected<?php }?>><?php echo $v['Application']['code']?></option>
									<?php }}?>
								</select>
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['param01']?>:</label>
							<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
								<input type="text" id="param01"  maxlength="60" name="data[Cronjob][param01]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['param01']:'';?>" />
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['param02']?>:</label>
							<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
								<input type="text" id="param02"  maxlength="60" name="data[Cronjob][param02]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['param02']:'';?>" />
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['remark']?>:</label>
							<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
								<input type="text" id="remark"  maxlength="60" name="data[Cronjob][remark]" value="<?php echo isset($cronjob_info)?$cronjob_info['Cronjob']['remark']:'';?>" />
							</div>
						</div>	
						<div class="am-form-group">
							<label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="padding-top:0;"><?php echo $ld['status']?>:</label>
							<div class="am-u-lg-5 am-u-md-5 am-u-sm-5">
								<label class="am-checkbox am-success">
									<input type="checkbox"  id="check" data-am-ucheck onClick="statuschange()"  value="<?php if(isset($cronjob_info['Cronjob']['status'])){echo $cronjob_info['Cronjob']['status'];}?>"  <?php if(isset($cronjob_info['Cronjob']['status'])&&$cronjob_info['Cronjob']['status']==1){echo "checked";}?> />
									<?php echo $ld['valid']?>
								</label>
								<input type="hidden" value="<?php if(isset($cronjob_info['Cronjob']['status'])){echo $cronjob_info['Cronjob']['status'];}?>" name="data[Cronjob][status]" id="status" />
							</div>
						</div>	
					</div>
					
				</div>
			</div>
		<?php echo $form->end();?>
	</div>
</div>
<script>
function statuschange(){
 var check=document.getElementById("check");
 var status=document.getElementById("status");
 if(check.checked == true){
 	status.value=1;
 }else{
 	status.value=0;
 }
}
</script>
<style type="text/css">
#cronjob_info .am-form-group{margin-bottom:1rem;}
</style>

	