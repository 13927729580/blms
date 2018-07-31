<div class="am-g">
	<?php echo $form->create('notify_templates',array('action'=>'/wechat_template/','type'=>'get','class'=>'am-form-horizontal'));?>
		<ul class=" am-avg-md-2 am-avg-lg-3 am-avg-sm-1">
			<li style="margin:0 0 10px 0">  
				<label class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['open_model_account'];?></label>
				<div class="am-u-lg-7 am-u-md-7 am-u-sm-7  am-u-end">
					<select name="open_type_id"  data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?> '}">
						<?php if(isset($open_type)&&!empty($open_type)){foreach($open_type as $v){ ?>
						<option value="<?php echo $v['OpenModel']['id']; ?>" <?php echo isset($open_type_id)&&$open_type_id==$v['OpenModel']['id']?'selected':''; ?>><?php echo $v['OpenModel']['open_type_id'];  ?></option>
						<?php }} ?>
					</select>
				</div>
			</li> 
			<li style="margin:0 0 10px 0">
				<div class="am-u-sm-3 am-hide-lg-only">&nbsp;</div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-6" style="padding-left:16px;" >
					<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>"><?php echo $ld['search'];?></button>
				</div>
			</li>
    		</ul>
	<?php echo $form->end();?>
</div>
<div class="am-panel-group am-panel-tree">
	<div class="listtable_div_btm am-panel-header">
		<div class="am-panel-hd">
			<div class="am-panel-title am-g">
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['code']; ?></div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['title']?></div>
				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $ld['content']?></div>
			</div>
		</div>
	</div>
	<?php if(isset($template_list) && sizeof($template_list)>0){foreach($template_list as	$v){?>
		<div class="listtable_div_top am-panel-body">
			<div class="am-panel-bd am-g">
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $v['template_id']; ?></div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $v['title']?></div>
				<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $v['content']?></div>
				<div class="am-cf"></div>
			</div>
		</div>
	<?php }}else{ ?>
		<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
	<?php } ?>
</div>