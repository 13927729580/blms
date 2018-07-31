<div class='activity_list'>
	<?php echo $form->create('Activity',array('action'=>'/activity_user','type'=>'get','class'=>'am-form am-form-horizontal'));?>
		<ul class=" am-avg-md-2 am-avg-lg-3 am-avg-sm-1">
			<li style="margin:0 0 10px 0">  
				<label class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['activity'];?></label>
				<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
					<input type='text' name='activity_keyword' value="<?php echo isset($activity_keyword)?$activity_keyword:''; ?>" />
				</div>
			</li>
			<li style="margin:0 0 10px 0">  
				<label class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['j_isp_name'];?></label>
				<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
					<input type='text' name='user_keyword' value="<?php echo isset($user_keyword)?$user_keyword:''; ?>" />
				</div>
			</li>
			<li style="margin:0 0 10px 0">
				<label class="am-u-lg-2  am-u-md-2 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['date'];?></label>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="width:33%;padding-left:0;padding-right:0;">
					<div class="am-input-group">
					<input type='text' class="am-form-field am-radius" value="<?php echo isset($activity_start_date)&&$activity_start_date!=''?date('Y-m-d',strtotime($activity_start_date)):''; ?>" name="activity_start_date" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
					<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
				</div>
				</div>
				<label class="am-u-sm-1 am-form-label am-text-center">-</label>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="width:33%;padding-left:0;padding-right:0;">
					<div class="am-input-group">
					<input type='text' class="am-form-field am-radius" value="<?php echo isset($activity_end_date)&&$activity_end_date!=''?date('Y-m-d',strtotime($activity_end_date)):''; ?>" name="activity_end_date" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
					<span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
					</div>
				</div>
			</li>
			<li style="margin:0 0 10px 0">
				<label class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-form-label">&nbsp;</label>
				<div class="am-u-lg-7 am-u-md-7 am-u-sm-7" >
					<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm"><?php echo $ld['search'];?></button>
				</div>
			</li>
		</ul>
	<?php echo $form->end();?>
	<div class="am-panel-group am-panel-tree">
		<div class="  listtable_div_btm am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title am-g">
					<div class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $ld["activity"]; ?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['j_isp_name']; ?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['mobile']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo "报名".$ld['date']?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['operate']?></div>
				</div>
			</div>
		</div>
		<?php if(isset($activity_user_list) && sizeof($activity_user_list)>0){foreach($activity_user_list as $k=>$v){?>
		<div>
		<div class="listtable_div_top am-panel-body">
			<div class="am-panel-bd am-g">
				<div class="am-u-lg-4 am-u-md-4 am-u-sm-4"><?php echo $v['Activity']['name'] ?>&nbsp;</div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['ActivityUser']['name']; ?>&nbsp;</div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['ActivityUser']['mobile']; ?>&nbsp;</div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo date('Y-m-d',strtotime($v['ActivityUser']['modified'])); ?></div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
					<a class="mt am-btn am-btn-success am-btn-xs am-seevia-btn-view" href="<?php echo $html->url('/activities/activity_user_detail/'.$v['ActivityUser']['id']); ?>"><span class="am-icon-eye"></span></a>
				</div>
			</div>
		</div>
		</div>
		<?php }}else{?>
			<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
		<?php }?>
	</div>
	<?php if(isset($activity_user_list) && sizeof($activity_user_list)>0){?>
	<div id="btnouterlist" class="btnouterlist" > 
		<?php echo $this->element('pagers');?>
	</div>
	<?php }?>
</div>