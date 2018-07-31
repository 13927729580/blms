<?php
	/*
		keywords:关键字
		log_time_start:日志开始时间
		log_time_end:日志结束时间
	*/
	//pr($system_log_data);
?>


<form action="/admin/system_logs/index" name="Form_system_logs"  method="get">
<ul class="am-avg-lg-3 am-avg-md-2  am-avg-sm-1">
		<li class="am-margin-top-xs">
  	<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['log_type']?></label>
  	<div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
    <select data-am-selected name="log_type" value="<?php if(isset($log_type)){ echo $log_type; } ?>">
        <option value=""><?php echo $ld['please_select']?></option>
        <option value="debug" <?php if (@$log_type == 'debug'){echo "selected";} ?>><?php echo $ld['debugging']?></option>
        <option value="info" <?php if (@$log_type == 'info'){echo "selected";} ?>><?php echo $ld['info']?></option>
        <option value="notice" <?php if (@$log_type == 'notice'){echo "selected";} ?>><?php echo $ld['notice']?></option>
        <option value="warning" <?php if (@$log_type == 'warning'){echo "selected";} ?>><?php echo $ld['warning']?></option>
        <option value="error" <?php if (@$log_type == 'error'){echo "selected";} ?>><?php echo $ld['error']?></option>
    </select>
  </div>
  </li>

	<li class="am-margin-top-xs">
		<label class="am-u-lg-3  am-u-md-3  am-u-sm-4 am-form-label-text am-margin-left-0"><?php echo $ld['log_time']?></label>
		<div class="am-u-lg-3  am-u-md-3 am-u-sm-3" >
                    <input style="min-height:35px;cursor:pointer" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success'}" name="log_time_start" value="<?php echo @$log_time_start; ?>" />
                </div>
                <em class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center" style="padding: 0.35em 0px;">-</em>
                <div class=" am-u-lg-3  am-u-md-3  am-u-sm-3 am-u-end" >
                    <input style="min-height:35px;cursor:pointer" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success'}" name="log_time_end" value="<?php echo @$log_time_end; ?>" />
        </div>
	</li>

	<li class="am-margin-top-xs">
    <label class="am-u-lg-4  am-u-md-3  am-u-sm-4 am-form-label-text"><?php echo $ld['meta_keywords']?></label>
    <div class="am-u-lg-7 am-u-md-7 am-u-sm-6">
		<input name="keywords" value="<?php echo @$keywords ?>" class="am-form-field am-input-sm" type="text" placeholder="<?php echo $ld['meta_keywords']?>">
    </div>
	</li>

  <li class="am-margin-top-xs" style="margin-left:10px;">
   <div class="am-u-sm-3 ">&nbsp;</div>
   <div class="am-u-sm-3 ">
	<button class="am-btn am-btn-success am-btn-sm am-radius"><?php echo $ld['search']?></button>
    </div>
	</li>
</ul>
</form>
<div class="am-panel am-panel-default am-margin-top-lg">
	<div class="am-panel-hd am-cf">
  	<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
      <?php echo $ld['log_type']?>
    </div>
          <div class="am-u-lg-3 am-u-sm-3 am-u-sm-3"><?php echo $ld['log_access_url']?>
</div>
    <div class="am-u-lg-6 am-u-sm-6 am-u-sm-6"><?php echo $ld['log_details']?></div>
  	<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['log_time']?></div>
   </div>
  	<?php if (isset($system_log_data)&&sizeof($system_log_data)>0) { foreach ($system_log_data as $k => $v) { ?>
	<div class="am-panel-bd am-cf" style="border-bottom:1px solid #ddd">
	<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
      <?php if ($v['SystemLog']['type'] == 'debug') {
      	echo  $ld['debugging'];
      }elseif($v['SystemLog']['type'] == 'info'){
      	echo  $ld['info'];
      }elseif ($v['SystemLog']['type'] == 'notice') {
      	echo  $ld['notice'];
      }elseif ($v['SystemLog']['type'] == 'warning') {
      	echo  $ld['warning'];
      }elseif ($v['SystemLog']['type'] == 'error') {
      	echo  $ld['error'];
      } ?>&nbsp;
    </div>
    	    <div class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-text-break">
      <?php echo $v['SystemLog']['url'] ?>&nbsp;<br />
    </div>
    <div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-text-break">
      <?php echo $v['SystemLog']['log_text'] ?>&nbsp;
    </div>
    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-break">
      <?php echo $v['SystemLog']['created'] ?>&nbsp;
    </div>
	</div>
	<?php }} ?>
	<?php if (empty($system_log_data)) { ?>
	<div class="am-panel-bd am-cf am-text-center" style="border-bottom:1px solid #ddd">
	<?php echo $ld['no_data']?>
	</div>
	<?php } ?>
</div>
<?php echo $this->element('pagers') ?>
