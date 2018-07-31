<style type="text/css">
	.am-checkbox {margin-top:0px; margin-bottom:0px;}
	.btnouterlist{overflow: visible;}
	.am-yes{color:#5eb95e;}
	.am-no{color:#dd514c;}
	.am-panel-title div{font-weight:bold;}
	.am-checkbox input[type="checkbox"]{margin-left:0px;}
	.am-form-horizontal{padding-top: 0.5em;}
</style>
<div>
	<?php echo $form->create('mail_send_histories',array('action'=>'index','name'=>"SearchLogsForm","type"=>"get","class"=>"am-form am-form-horizontal"));?>
		<ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text" ><?php echo $ld['keyword']?></label>
				<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
					<input type="text" name="keywords" value="<?php echo @$keywords;?>"/>
				</div>
			</li>
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" ><?php echo $ld['status']?></label>
				<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
					<select name="status" id="status"   data-am-selected="{noSelectedText:'<?php echo $ld['all_data']?>'}">
						 <option value=""><?php echo $ld['all_data']?></option> 
						<option value='1' <?php if(@$status==1) echo " selected";?>><?php echo $ld['succeed']?></option>
						<option value='0' <?php if(isset($status)&&$status=='0') echo " selected";?>><?php echo $ld['failed']?></option>
					</select>
				</div>
			</li>
			<li style="margin-bottom:10px;">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label-text" ><?php echo $ld['update'].$ld['time']?></label>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
					<input type="text" name="start_time" value="<?php echo @$start_time?>" class="am-form-field" placeholder="" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  readonly />
				</div>
				<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding-top:5px;"><em>-</em></label>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3">
					<input type="text" name="end_time" value="<?php echo @$end_time?>" class="am-form-field" placeholder="" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  readonly />
				</div>
			 	</li>
			 <li style="margin-bottom:10px;">
				<label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label" > </label>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
					<input type="submit" class="am-btn am-radius am-btn-success am-btn-sm" value="<?php echo $ld['search']?>" />
				</div>				
			</li>
		</ul>
	<?php echo $form->end()?>
				<br/>
	<div class="am-text-right am-btn-group-xs " style="margin-bottom:10px;">
	  <?php if($svshow->operator_privilege("log_management_remove")){echo $html->link($ld['clear_all'],"clearall",array('class'=>'am-btn am-btn-xs am-btn-default '),'',false,false);}?>
	</div>
	<div class="am-panel-group am-panel-tree">
		<div class=" am-panel-header">
			<div class="am-panel-hd listtable_div_btm">
				<div class="am-panel-title">
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['sender']?></div>
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['recipients']?></div>
					<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['title']?></div>
					<div class="am-u-lg-1 am-u-md-1 am-hide-sm-only"><?php echo $ld['create_time']?></div>
					<div class="am-u-lg-1 am-u-md-1 am-hide-sm-only"><?php echo $ld['status']?></div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-3"><?php echo $ld['operate']?></div>
					<div style="clear:both;"></div>
				</div>
			</div>
		</div>
		<?php if(isset($mail_send_histories_logs) && sizeof($mail_send_histories_logs)>0){foreach($mail_send_histories_logs as  $k=>$v){?>	
			<div>
				<div class="listtable_div_top am-panel-body">
					<div class="am-panel-bd">					
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $v['MailSendHistory']['sender_name']?></div>
						<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"> &nbsp;<?php echo $v['MailSendHistory']['receiver_email'];?></div>
					   	<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"> &nbsp;<?php echo $v['MailSendHistory']['title'];?></div>
						<div class="am-u-lg-1 am-u-md-1 am-hide-sm-only"> &nbsp;<?php echo $v['MailSendHistory']['created'];?></div>
						<div class="am-u-lg-1 am-u-md-1 am-hide-sm-only">
							<?php if($v['MailSendHistory']['flag']){?>
								<span class="am-icon-check am-yes"></span>
							<?php }else{ ?>
								<span class="am-icon-close am-no" title="<?php echo $v['MailSendHistory']['error_msg']; ?>"></span>	
							<?php }?>
						</div>
						<div class="am-u-lg-1 am-u-md-1 am-u-sm-3">
					 <?php echo $html->link(' '.$ld['view'],'/mail_send_histories/view/'.$v['MailSendHistory']['id'],array('class'=>"am-btn am-btn-xs am-btn-success am-icon-eye"),false,false);?> 
						</div>
						<div style="clear:both;"></div>
					</div>
				</div>
			</div>
		<?php }}?>
	</div>
	<?php if(isset($mail_send_histories_logs) && sizeof($mail_send_histories_logs)>0){?>
		<div id="btnouterlist" class="btnouterlist">
		 <div class="am-u-lg-12  am-u-md-12 am-u-sm-12">		
				<?php echo $this->element('pagers')?>
		 </div>
		</div>
	<?php }?>
</div>

