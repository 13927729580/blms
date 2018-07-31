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

	<div class="am-panel am-panel-default <?php if($organizations_id!=''){echo 'am-u-lg-9';} ?>" id="course_study" style="font-size: 14px;margin-left: 0;">
	    <div style="text-align:left;font-size:20px;padding-bottom:1rem;margin-top:1%;padding-left:5px;margin-bottom:1rem;border-bottom: 1px solid #ccc;" >
	      <span style="float:left;"><?php echo isset($course_info['Course']['name'])?$course_info['Course']['name']:''; ?></span>
	      <div class="am-cf"></div>
	    </div>
	    <div class="am-panel-hd" style="font-size: 15px;">
	        <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#note_situation'}">笔记记录</h4>
	    </div>
	    <div id="note_situation" class="am-panel-collapse am-collapse am-in">
	        <div class="listtable_div_btm" style="margin-top: 10px;">
	            <div class="am-panel-hd">
	                <div class="am-panel-title">
	                    <!-- <div class="am-u-md-3 am-u-sm-3">记录者</div> -->
	                    <div class="am-u-md-3 am-u-sm-3">记录章节</div>
	                    <div class="am-u-md-5 am-u-sm-5">内容</div>
	                    <div class="am-u-md-3 am-u-sm-3 am-hide-sm-only">记录时间</div>
	                    <div class="am-u-md-1 am-u-sm-3">操作</div>
	                    <div style="clear:both;"></div>
	                </div>
	            </div>
	        </div>
	        <?php //pr($class_note_list); ?>
	        <?php if(isset($course_note)&&sizeof($course_note)>0){foreach ($course_note as $k => $v) { ?>
	        <div class="listtable_div_btm" style="border-top: 1px solid #ccc;padding:1.25rem;">
	            <div class="am-panel-hd" style="padding: 0;">
	                <div class="am-panel-title">
	                    <!-- <div class="am-u-md-3 am-u-sm-3" style="word-wrap:break-word"><?php if(isset($user_note_list)&&sizeof($user_note_list)>0){echo $user_note_list[$v['CourseNote']['user_id']]['name'];} ?>&nbsp;</div> -->
	                    <div class="am-u-md-3 am-u-sm-3" style="word-wrap:break-word"><?php if(isset($class_note_list)&&sizeof($class_note_list)>0){echo isset($class_note_list[$v['CourseNote']['course_class_id']]['name'])?$class_note_list[$v['CourseNote']['course_class_id']]['name']:$course_info['Course']['name'];} ?>&nbsp;</div>
	                    <div class="am-u-md-5 am-u-sm-5" style="word-wrap:break-word"><?php echo $v['CourseNote']['note'] ?>&nbsp;</div>
	                    <div class="am-u-md-3 am-u-sm-3 am-hide-sm-only" style="word-wrap:break-word"><?php echo $v['CourseNote']['created'] ?>&nbsp;</div>
	                    <div class="am-u-md-1 am-u-sm-3" style="">
	                        <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(web_base+'/courses/remove_note/<?php echo $v['CourseNote']['id'] ?>');">
	                            <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
	                        </a>
	                    </div>
	                    <div style="clear:both;"></div>
	                </div>
	            </div>
	        </div>
	        <?php }}else{?>
	            <div style="border-top: 1px solid #ccc;text-align: center;padding:75px;">暂无笔记记录</div>
	        <?php }?>
	    </div>
	</div>
</div>
<script>
function list_delete_submit(sUrl){
    var r=confirm("确定删除？")
    if (r==true){
        $.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            success: function (result) {
                if(result.flag==1){
                    //alert(result.message);
                    window.location.reload();
                }
                if(result.flag==2){
                    seevia_alert(result.message);
                }
            }
        });
    }
}
</script>