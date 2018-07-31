<div style="margin-top:10px;">
	<?php echo $form->create('certificates',array('action'=>'/','type'=>'get'));?>
	<ul class=" am-avg-md-2 am-avg-lg-3 am-avg-sm-1">
		<li  style="margin:0 0 10px 0">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text "><?php echo '名字';?></label> 
				<div class="am-u-lg-7  am-u-md-7 am-u-sm-7"  >
				<input type="text" name="user_keywords" class="am-form-field am-radius"  value="<?php echo isset($user_keywords)?$user_keywords:''; ?>"  />
				</div>
		</li>
		<li  style="margin:0 0 10px 0">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text "><?php echo '身份证号码';?></label> 
				<div class="am-u-lg-7  am-u-md-7 am-u-sm-7"  >
				<input type="text" name="identity_no" class="am-form-field am-radius"  value="<?php echo isset($identity_no)?$identity_no:''; ?>"  />
				</div>
		</li>
		<li  style="margin:0 0 10px 0">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text "><?php echo '证书类型';?></label> 
				<div class="am-u-lg-7  am-u-md-7 am-u-sm-7"  >
					<select name='certificate_type'  data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?>',maxHeight: 100}">
						<option value=''><?php echo $ld['all_data']; ?></option>
						<?php if(isset($informationresource_info['certificatetype'])&&sizeof($informationresource_info['certificatetype'])>0){foreach($informationresource_info['certificatetype'] as $k=>$v){ ?>
						<option value="<?php echo $k; ?>" <?php echo isset($certificate_type)&&$certificate_type==$k?'selected':''; ?>><?php echo $v; ?></option>
						<?php }} ?>
					</select>
				</div>
		</li>
		<li  style="margin:0 0 10px 0">
			<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text "><?php echo '证书编码';?></label> 
				<div class="am-u-lg-7  am-u-md-7 am-u-sm-7"  >
				<input type="text" name="certificate_number" class="am-form-field am-radius"  value="<?php echo isset($certificate_number)?$certificate_number:''; ?>"  />
				</div>
		</li>
		<li style="margin:0 0 10px 0">  
				<label style="padding-top: 1em;" class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['date'];?></label>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
					<input type='text' class="am-form-field am-radius" value="<?php echo isset($register_date_start)?$register_date_start:''; ?>" name="register_date_start" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
				</div>
				<div class="  am-text-center  am-fl " style="margin-top:7px;">-</div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
					<input type='text' class="am-form-field am-radius" value="<?php echo isset($register_date_end)?$register_date_end:''; ?>" name="register_date_end" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" />
				</div>
			</li>
		<li style="margin:0 0 10px 0">
				<div class="am-u-sm-3 am-hide-lg-only">&nbsp;</div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-6" style="padding-left:16px;" >
					<button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>"><?php echo $ld['search'];?></button>
				</div>
		</li>
	</ul>
	<?php echo $form->end(); ?>
	<div class="am-g am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
		<?php if($svshow->operator_privilege('certificate_add')){
					echo $html->link($ld['bulk_upload'],'/certificates/upload',array('class'=>'am-btn am-btn-default am-btn-sm am-radius')); ?>
			<a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/certificates/view/0'); ?>">
				<span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
			</a> 
		<?php }?>
	</div>
	<div class="am-panel-group am-panel-tree">
		<div class="  listtable_div_btm am-panel-header">
			<div class="am-panel-hd">
				<div class="am-panel-title am-g">
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-1"><?php echo '名字'?></div>
					<div class="am-u-lg-2 am-show-lg-only"><?php echo '身份证号码'?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo '证书类型'?></div>
					<div class="am-u-lg-2 am-u-md-2 am-hide-sm-only"><?php echo '证书编码'?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo '注册日期'?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo '操作'?></div>
				</div>
			</div>
		</div>
		<div>
			<div class="listtable_div_top am-panel-body">
				<?php if(isset($certificate_infos)&&sizeof($certificate_infos)>0){foreach($certificate_infos as $v){ ?>
				<div class="am-panel-bd am-g">
					<div class="am-u-lg-1 am-u-md-2 am-u-sm-1"><?php echo $v['Certificate']['name']; ?>&nbsp;</div>
					<div class="am-u-lg-2 am-show-lg-only"><?php echo $v['Certificate']['identity_no']; ?>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo isset($informationresource_info['certificatetype'][$v['Certificate']['type']])?$informationresource_info['certificatetype'][$v['Certificate']['type']]:$v['Certificate']['type']; ?>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-2 am-hide-sm-only"><?php echo $v['Certificate']['certificate_number']; ?>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-3"><?php echo $v['Certificate']['register_date']; ?>&nbsp;</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-3">
						<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-view" href="<?php echo $html->url('/certificates/view/'.$v['Certificate']['id']); ?>"><span class="am-icon-eye"></span><?php echo $ld['edit']; ?></a>&nbsp;
	                    		<a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:void(0);" 
							onclick="list_delete_submit(admin_webroot+'certificates/remove/<?php echo $v['Certificate']['id']; ?>')">
	                        			<span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?></a>
	                        	</div>
				</div>
				<?php }} ?>
			</div>
		</div>
	</div>
	<?php if(isset($certificate_infos)&&sizeof($certificate_infos)>0){ ?>
	<div id="btnouterlist" class="btnouterlist"><?php echo $this->element('pagers');?></div>
	<?php } ?>
</div>
	