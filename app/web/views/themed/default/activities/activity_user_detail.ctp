<style type='text/css'>
ol.am-breadcrumb.am-hide-md-down.am-color{
max-width:1200px;
margin:10px auto;
padding:0;
line-height: 25px;
}
.fw600{font-weight:600;}
.organization_user_tags li{margin-bottom:5px;}
.organization_user_tags li div[class*=am-u-]{padding-left:0px;}
.organization_user_tags a.am-icon-times,.organization_user_tags a.am-icon-times:hover{color:#dd514c;line-height:37px;}
</style>
<div style="max-width:1200px;margin:10px auto;">
	<?php echo $this->element('org_menu')?>
	<?php echo $this->element('organization_menu')?>
	<button class="am-btn am-btn-primary am-show-sm-only" data-am-offcanvas="{target: '#org_menu2', effect: 'push'}" style="margin-right:0.5rem;">我的组织</button>
	<div class="am-g am-u-lg-9 am-u-sm-12">
		<h3 style="font-size:20px;font-weight:400;margin-top:4px;border-bottom:1px solid #ccc;padding-left:5px;margin-bottom:20px;padding-bottom:1rem;line-height:1;" class="am-hide-sm-only">
			<span>我的客户</span>
		</h3>
		<form action="" class="am-form">
			<input type='hidden' name='data[OrganizationUserTag][organization_id]' value="<?php echo isset($organization_user_detail['OrganizationUser'])?$organization_user_detail['OrganizationUser']['organization_id']:'0'; ?>" />
			<input type='hidden' name='data[OrganizationUserTag][user_id]' value="<?php echo isset($organization_user_detail['OrganizationUser'])?$organization_user_detail['OrganizationUser']['user_id']:'0'; ?>" />
			<div class='am-form-group'>
				<label class='am-text-right am-u-lg-3' style='font-weight:400;line-height:37px;'>备注:</label>
				<div class='am-u-lg-4'>
					<input type='hidden' name='data[OrganizationUser][id]' value="<?php echo isset($organization_user_detail['OrganizationUser'])?$organization_user_detail['OrganizationUser']['id']:'0'; ?>" />
					<input type='text' name='data[OrganizationUser][remark]' value="<?php echo isset($organization_user_detail['OrganizationUser'])?$organization_user_detail['OrganizationUser']['remark']:''; ?>" />
				</div>
				<div class="am-cf"></div>
			</div>
			<div class='am-form-group'>
				<label class='am-text-right am-u-lg-3'  style='font-weight:400;line-height:37px;'>标签:</label>
				<div class='am-u-lg-8'>
					<div class='am-text-right'><button type='button' class='am-btn am-btn-radius am-btn-warning am-btn-sm' onclick='add_organization_user_tag(this)'><i class="am-icon-plus"></i>&nbsp;<?php echo $ld['add']; ?></button></div>
					<ul class="am-avg-sm-2 organization_user_tags">
						<?php if(isset($organization_user_tags)&&sizeof($organization_user_tags)>0){foreach($organization_user_tags as $v){ ?>
						<li>
							<div class='am-u-sm-8'><input type='hidden' name='data[OrganizationUserTag][id][]' value="<?php echo $v['OrganizationUserTag']['id']; ?>" /><input type='text' name='data[OrganizationUserTag][tag_name][]' value="<?php echo $v['OrganizationUserTag']['tag_name']; ?>" /></div>
							<div class='am-u-sm-3'><a href="javascript:void(0);" class="am-icon-times am-text-danger" onclick='remove_organization_user_tag(this)'></a></div>
							<div class="am-cf"></div>
						</li>
						<?php }}else{ ?>
						<li>
							<div class='am-u-sm-8'><input type='hidden' name='data[OrganizationUserTag][id][]' value="0" /><input type='text' name='data[OrganizationUserTag][tag_name][]' value="" /></div>
							<div class='am-u-sm-3'><a href="javascript:void(0);" class="am-icon-times am-text-danger" onclick='remove_organization_user_tag(this)'></a></div>
							<div class="am-cf"></div>
						</li>
						<?php } ?>
					</ul>
				</div>
				<div class="am-cf"></div>
			</div>
			<div class='am-form-group'>
				<label class='am-text-right am-u-lg-3'>&nbsp;</label>
				<div class='am-u-lg-8'>
					<button type='button' class='am-btn am-btn-radius am-btn-secondary am-btn-sm' onclick='organization_user_tag_save(this)'><?php echo $ld['save']; ?></button>
				</div>
				<div class="am-cf"></div>
			</div>
		</form>
	</div>
	<div class="am-cf"></div>
</div>
<script type='text/javascript'>
function add_organization_user_tag(btn){
	var tag_html="<li><div class='am-u-sm-8'><input type='hidden' name='data[OrganizationUserTag][id][]' value='0' /><input type='text' name='data[OrganizationUserTag][tag_name][]' value='' /></div><div class='am-u-sm-3'><a href='javascript:void(0);' class='am-icon-times am-text-danger' onclick='remove_organization_user_tag(this)'></a></div><div class='am-cf'></div></li>";
	$('ul.organization_user_tags').append(tag_html);
}

function remove_organization_user_tag(btn){
	if(confirm(j_confirm_delete)){
		$(btn).parents('li').remove();
	}
}

function organization_user_tag_save(btn){
	$.ajax({
		url:web_base+"/activities/ajax_activity_user_modify",
		type:"POST",
		data:$(btn).parents('form').serialize(),
		dataType:"json",
		success: function(data){
			if(data.code=='1'){
				if(typeof(data.organization_id)!='undefined'){
					window.location.href=web_base+"/activities/activity_user/"+data.organization_id;
				}else{
					window.location.reload();
				}
			}else{
				seevia_alert(data.message);
			}
		}
    	});
}
</script>