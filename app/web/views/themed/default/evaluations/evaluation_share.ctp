<style>
.am-radio-inline{padding-top: 0!important;}
<?php if($organizations_id!=''){ ?>
.am-u-lg-3.am-u-md-3.am-u-sm-12.am-user-menu.am-hide-sm-only.am-padding-right-0{display: none!important;}
.am-u-lg-9.am-u-md-8.am-u-sm-12{width:100%;}
.am-btn.am-btn-sm.am-btn-secondary.am-show-sm-only{display:none!important;}
.am-u-lg-2.am-u-md-2.am-u-sm-2.am-panel-group.am-hide-sm-only{margin-right:5%;}
<?php } ?>
</style>
<div class="am-g am-g-fixed">
	<?php if($organizations_id!=''){ ?>
	<?php echo $this->element('organization_menu');?>
	<?php echo $this->element('org_menu')?>

	<button style="margin:10px 0;" class="am-btn am-btn-primary am-show-sm-only" data-am-offcanvas="{target: '#org_menu2', effect: 'push'}">组织菜单</button>
	<?php } ?>
	<div class="am-panel am-panel-default <?php if($organizations_id!=''){echo 'am-u-lg-9';} ?>" id="course_share" style="font-size: 14px;margin-left: 0;">
	    <div style="text-align:left;font-size:20px;padding-bottom:1rem;margin-top:1%;padding-left:5px;margin-bottom:1rem;border-bottom: 1px solid #ccc;" >
	      <span style="float:left;"><?php echo isset($evaluation_info['Evaluation']['name'])?$evaluation_info['Evaluation']['name']:''; ?></span>
	      <div class="am-cf"></div>
	    </div>
	    <div class="am-panel-hd" style="font-size: 15px;">
	        <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Share_record'}">分享记录</h4>
	    </div>
	    <div id="Share_record" class="am-panel-collapse am-collapse am-in">
	        <div class="am-g">
	        <div class="listtable_div_btm" style="line-height:46px;padding-left:1.25rem;padding-right:1.25rem;">
	                    <div class="am-u-md-4 am-u-sm-4 am-hide-sm-only" style="padding:0;">分享时间</div>
	                    <div class="am-u-md-6 am-u-sm-8" style="padding:0;">分享对象</div>
	                    <!-- <div class="am-u-md-3 am-u-sm-4" style="padding:0;">分享者姓名</div> -->
	                    <div class="am-u-md-2 am-u-sm-2" style="padding:0;">操作</div>
	                    <div style="clear:both;"></div>
	        </div>
	        <?php if(isset($organization_share)&&sizeof($organization_share)>0){foreach ($organization_share as $k => $v) { ?>
	        <?php //pr($v); ?>
	        <div class="listtable_div_btm" style="line-height:46px;border-top: 1px solid #ccc;padding-left:1.25rem;padding-right:1.25rem;">
	            <div class="am-u-md-4 am-u-sm-4 am-hide-sm-only" style="padding:15px 5px;line-height: 1;"><?php echo $v['OrganizationShare']['created'] ?>&nbsp;</div>
	            <div class="am-u-md-6 am-u-sm-8" style="padding:15px 5px;line-height: 1;">
	                <?php if($v['OrganizationShare']['share_object']==0){ ?>
	                    <?php if(isset($members_list_name[$v['OrganizationShare']['share_object_ids']])){echo $members_list_name[$v['OrganizationShare']['share_object_ids']];}else{$share_name = $v['OrganizationShare']['share_object_ids'];echo $share_name;} ?>
	                    &nbsp;
	                <?php } ?>
	                <?php if($v['OrganizationShare']['share_object']==1){ ?>
	                    <?php echo isset($department_list_name[$v['OrganizationShare']['share_object_ids']])?$department_organzation_list_name[$v['OrganizationShare']['organization_id']].' '.$department_list_name[$v['OrganizationShare']['share_object_ids']]:''; ?>
	                <?php } ?>
	                <?php if($v['OrganizationShare']['share_object']==2){ ?>
	                    <?php echo isset($organization_list_name[$v['OrganizationShare']['share_object_ids']])?$organization_list_name[$v['OrganizationShare']['share_object_ids']]:''; ?>
	                <?php } ?>&nbsp;
	            </div>
	            <!-- <div class="am-u-md-3 am-u-sm-4" style="padding:15px 5px;line-height: 1;">
	                <?php if($v['OrganizationShare']['organization_id']!=0&&$v['OrganizationShare']['organization_id']!=''){ ?>
	                <?php echo $organization_share_list[$v['OrganizationShare']['organization_id']] ?>
	                <?php } ?>
	                <?php foreach ($users_list as $kk => $vv) {if($v['OrganizationShare']['share_user']==$vv['User']['id']){echo $vv['User']['name'];}} ?>&nbsp;
	            </div> -->
	            <div class="am-u-md-2 am-u-sm-2" style="padding:15px 5px;line-height: 1;">
	                <?php if($v['OrganizationShare']['share_user']==$_SESSION['User']['User']['id']){ ?>
	                <a class="mt am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-edit" onclick="delete_share(<?php echo $v['OrganizationShare']['id'] ?>)"><i class="am-icon-trash-o" style="margin-right:3px;"></i>取消分享</a>
	                <?php } ?>&nbsp;
	            </div>
	            <div style="clear:both;"></div>
	        </div>
	        <?php }}else{?>
	            <div style="border-top: 1px solid #ccc;text-align: center;padding:75px;">暂无分享记录</div>
	        <?php }?>
	        </div>
	    </div>
	</div>
</div>
<script>
function delete_share(share_id){
    var delete_s = function(){
        $.ajax({
            type: "POST",
            url:web_base+'/evaluations/delete_share/'+share_id,
            dataType: 'json',
            data: {},
            success: function (data) {
               if(data.code == 1){
                seevia_alert('删除成功！');
                window.location.reload();
               }else{
                seevia_alert(data.message);
               }
            }
        });
    }
    seevia_confirm(delete_s,'是否确认取消此分享？'); 
    
}
</script>