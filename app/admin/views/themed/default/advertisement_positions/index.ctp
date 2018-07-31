<div class="am-g am-other_action  am-text-right am-btn-group-xs">
	<select id="template">
		<?php if(isset($template)){foreach($template as $k=>$v){?>
		<option <?php if($v['Template']['name'] == (isset($defaulttemplate)?$defaulttemplate:'')){echo 'selected=selected';}?> value="<?php echo $v['Template']['name'];?>"><?php echo $v['Template']['description'];?></option>
		<?php }}?>
	</select>
</div>
<div class="am-panel-group am-panel-tree">
	<div class="listtable_div_btm am-panel-header">
		<div class="am-panel-hd">
			<div class="am-panel-title am-g">
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $ld['ad_position_name']?></div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['position_code'] ?></div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['ad_position_width']?></div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['ad_position_height']?></div>
				<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['ad_position_description']?></div>
				<div class="am-u-lg-1 am-show-lg-only"><?php echo $ld['sort']?></div>
				<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php echo $ld['operator']?></div>
			</div>
		</div>
	</div>
	<?php if(isset($advertisement_position_list) && sizeof($advertisement_position_list)>0){foreach($advertisement_position_list as $k=>$v){?>
	<div class="listtable_div_top am-panel-body">
		<div class="am-panel-bd am-g">
			<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $v['AdvertisementPosition']['name']; ?>&nbsp;</div>
			<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['AdvertisementPosition']['code'];?>&nbsp;</div>
			<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['AdvertisementPosition']['ad_width']; ?>&nbsp;</div>
			<div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['AdvertisementPosition']['ad_height']; ?>&nbsp;</div>
			<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['AdvertisementPosition']['position_desc']; ?>&nbsp;</div>
			<div class="am-u-lg-1 am-show-lg-only"><?php echo $v['AdvertisementPosition']['orderby']; ?>&nbsp;</div>
			<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><?php
					if(!isset($v['AdvertisementPosition']['is_new'])){
						if($svshow->operator_privilege("advertisement_positions_mgt")){
							echo $html->link($ld['layout'],"/advertisement_positions/position/".$defaulttemplate."#".$v['AdvertisementPosition']['code'],array("target"=>'view_adsiteall'));
						}
						if($svshow->operator_privilege("advertisement_positions_mgt")){
							echo $html->link($ld['ads_list'],"/advertisements/index/{$v['AdvertisementPosition']['id']}");
						}
						if($svshow->operator_privilege("advertisement_positions_edit")){
							echo $html->link($ld['edit'],"/advertisement_positions/view/{$v['AdvertisementPosition']['id']}");
						}
						if($svshow->operator_privilege("advertisement_positions_remove")&&!isset($v['AdvertisementPosition']['is_new'])){
							echo $html->link($ld['delete'],"javascript:;",array("onclick"=>"list_delete_submit('{$admin_webroot}advertisement_positions/remove/{$v['AdvertisementPosition']['id']}')"));
						}
					}elseif (isset($v['AdvertisementPosition']['is_new'])&&$v['AdvertisementPosition']['is_new']=='1') {
						echo $html->link($ld['install'],"javascript:;",array("onclick"=>"list_delete_submit('{$admin_webroot}advertisement_positions/install/{$defaulttemplate}/{$v['AdvertisementPosition']['code']}');"));
					}
				?></div>
		</div>
	</div>
	<?php }}else{ ?>
		<div class="no_data_found"><?php echo $ld['no_data_found']?></div>
	<?php } ?>
</div>
<?php if(isset($advertisement_position_list) && sizeof($advertisement_position_list)>0){ ?>
<div id="btnouterlist" class="btnouterlist" >
	<?php echo $this->element('pagers');?>
</div>
<?php } ?>
<script type="text/javascript">
$(function(){
	$("#template").change(function(){
		var templatename=$(this).val();
		window.location.href=admin_webroot+"advertisement_positions/index/"+templatename;
	})
})
</script>