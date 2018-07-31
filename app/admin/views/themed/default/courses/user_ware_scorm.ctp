<div>
    <?php echo $form->create('Course',array('action'=>'user_ware_scorm',"type"=>"get",'class'=>'am-form am-form-horizontal'));?>
    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
        <li class='am-form-group'>
		<label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">课程</label>
		<div class="am-u-lg-8 am-u-md-8 am-u-sm-7 am-padding-left-0">
			<input type='text' name='course_keyword' value='<?php echo isset($course_keyword)?$course_keyword:''; ?>' />
		</div>
        </li>
        <li class='am-form-group'>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">用户</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7 am-padding-left-0">
    			<input type='text' name='user_keyword' value='<?php echo isset($user_keyword)?$user_keyword:''; ?>'/>
            </div>
        </li>
        <li class='am-form-group'>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">学习时间</label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-padding-0">
                <div class="am-input-group am-margin-top-0">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="start_date_time" value="<?php echo isset($start_date_time)?$start_date_time:"";?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
            </div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-padding-0">
                <div class="am-input-group am-margin-top-0">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="end_date_time" value="<?php echo isset($end_date_time)?$end_date_time:"";?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
            </div>
        </li>
        <li class='am-form-group'>
            <label class="am-u-sm-3 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
            <div class="am-u-sm-8 am-u-md-8 am-u-sm-7 " style="float:left;">
                <input type="submit"  class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" />
            </div>
        </li>
    </ul>
    <?php echo $form->end()?>
</div>
<div class='am-margin-top-lg'>
    <div class="listtable_div_btm am-margin-bottom-lg">
        <div class="am-g">
            <div class="am-u-lg-3 am-u-md-3 am-u-sm-3">课程</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">用户</div>
            <div class="am-u-lg-1 am-u-md-1 am-hide-sm-only">得分</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">学习时长</div>
            <div class="am-u-lg-2 am-u-md-2 am-hide-sm-only">上次学习时间</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-center"><?php echo $ld['operate']; ?></div>
        </div>
        <?php if(isset($course_scorm_list) && sizeof($course_scorm_list)>0){foreach($course_scorm_list as $k=>$v){
        			$scorm_data=trim($v['CourseScormLog']['scorm_data'])!=''?json_decode($v['CourseScormLog']['scorm_data'],true):array();
        ?>
            <div class="am-g">
                <div class="listtable_div_top ware_scorm_detail">
                    <div class="am-g am-margin-top-sm am-margin-bottom-sm">
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $v['Course']['name']."&nbsp;<br >";echo $v['CourseChapter']['name'].'&nbsp;/&nbsp;'.$v['CourseClass']['name']; ?></div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['User']['first_name']!=''?$v['User']['first_name']:$v['User']['name']; ?>&nbsp;</div>
				<div class="am-u-lg-1 am-u-md-1 am-hide-sm-only"><?php echo isset($scorm_data['cmi_core_score_raw'])?$scorm_data['cmi_core_score_raw']:0; ?>&nbsp;</div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo isset($scorm_data['cmi_core_session_time'])?$scorm_data['cmi_core_session_time']:'0'; ?>&nbsp;</div>
				<div class="am-u-lg-2 am-u-md-2 am-hide-sm-only"><?php echo date('Y-m-d',strtotime($v['CourseScormLog']['created'])); ?></div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-center">
					<a class="am-btn am-btn-success am-btn-xs am-seevia-btn-view" href="javascript:void(0);" onclick="ajax_ware_scorm_detail(<?php echo $v['CourseScormLog']['id']; ?>)"><span class="am-icon-eye"></span></a>
					<a class="am-btn am-btn-xs am-margin-left-xs user_ware_scorm" href="javascript:void(0);" onclick="ajax_user_ware_scorm(this,<?php echo $v['CourseScormLog']['course_ware_id']; ?>,<?php echo $v['CourseScormLog']['user_id']; ?>)"><span class="am-icon am-icon-angle-down"></span></a>
                		</div>
            		<div class='am-cf'></div>
                    </div>
                </div>
            </div>
        <?php }}else{?>
            <div>
                <div class="no_data_found" ><?php echo $ld['no_data_found']?></div>
            </div>
        <?php }?>
    </div>
    <?php if(isset($course_scorm_list) && sizeof($course_scorm_list)){?>
            <?php echo $this->element('pagers')?>
    <?php }?>
</div>

<div class="am-modal am-modal-no-btn" id="user_ware_scorm">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">
            <h4 class="am-popup-title">学习记录</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
			<table class='am-table'>
			</table>
        </div>
    </div>
</div>
<style type='text/css'>
#user_ware_scorm{width:50%;margin-left:-25%;top:50%;}
#user_ware_scorm .am-modal-bd{max-height:400px;overflow-y:scroll;}
#user_ware_scorm table{table-layout:fixed;}
#user_ware_scorm table td{word-wrap:break-word;text-align:left;}
#user_ware_scorm table td:first-child{text-align:right;font-weight:600;width:30%;}
div.ware_scorm_detail div.last_ware_scorm{margin-bottom:10px;}
a.user_ware_scorm,a.user_ware_scorm:hover,a.user_ware_scorm:active{color:#333;box-shadow:none;}
</style>
<script type='text/javascript'>
function ajax_user_ware_scorm(btn,ware_id,user_id){
	var ware_scorm_detail=$(btn).parents('div.ware_scorm_detail');
	if($(ware_scorm_detail).find('div.last_ware_scorm').length>0){
		if($(ware_scorm_detail).find('div.last_ware_scorm').is(':visible')){
			$(ware_scorm_detail).find('div.last_ware_scorm').hide();
			$(btn).find("span.am-icon").removeClass('am-icon-angle-up').addClass('am-icon-angle-down');
		}else{
			$(ware_scorm_detail).find('div.last_ware_scorm').show();
			$(btn).find("span.am-icon").removeClass('am-icon-angle-down').addClass('am-icon-angle-up');
		}
		return;
	}
	$(ware_scorm_detail).append("<div class='last_ware_scorm'></div>")
	var last_ware_scorm=$(ware_scorm_detail).find("div.last_ware_scorm");
	$.ajax({
		url: admin_webroot+"courses/ajax_user_ware_scorm",
		type:"POST",
		data:{'ware_id':ware_id,'user_id':user_id},
		dataType:"json",
		success: function(result){
			if(result.code=='1'){
				var scorm_data=result.data;
				$.each(scorm_data,function(index,item){
					var scorm_detail=JSON.parse(item.CourseScormLog.scorm_data);
					var scorm_date = (new Date(item.CourseScormLog.created)).getTime();
    					var curTime = new Date(scorm_date).format("yyyy-MM-dd");
					last_ware_scorm.append("<div class='am-margin-top-sm am-margin-bottom-sm'><div class='am-u-lg-3 am-u-md-3 am-u-sm-3'>&nbsp;</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'>&nbsp;</div><div class='am-u-lg-1 am-u-md-1 am-hide-sm-only'>"+scorm_detail.cmi_core_score_raw+"&nbsp;</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2'>"+scorm_detail.cmi_core_session_time+"&nbsp;</div><div class='am-u-lg-2 am-u-md-2 am-hide-sm-only'>"+curTime+"&nbsp;</div><div class='am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-center'><a class='am-btn am-btn-success am-btn-xs am-seevia-btn-view am-margin-right-xs' href='javascript:void(0);' onclick='ajax_ware_scorm_detail("+item.CourseScormLog.id+")'><span class='am-icon-eye'></span></div><div class='am-cf'></div></div>");
				});
				$(btn).find("span.am-icon").removeClass('am-icon-angle-down').addClass('am-icon-angle-up');
			}
		}
	});
}

function ajax_ware_scorm_detail(course_scorm_id){
	$('#user_ware_scorm table tr').remove();
	$.ajax({
		url: admin_webroot+"courses/ajax_ware_scorm_detail",
		type:"POST",
		data:{'course_scorm_id':course_scorm_id},
		dataType:"json",
		success: function(result){
			if(result.code=='1'){
				var scorm_data=result.data.scorm_data;
				$.each(scorm_data,function(index,item){
					$('#user_ware_scorm table').append("<tr><td>"+index+"</td><td>"+item+"</td></tr>");
				});
				if(typeof(result.scorm_interaction)!='undefined'&&result.scorm_interaction.length>0){
					$.each(result.scorm_interaction,function(key,scorm_interaction){
						$('#user_ware_scorm table').append("<tr><td>&nbsp;</td><td>&nbsp;</td></tr>");
						$.each(scorm_interaction,function(index,item){
							$('#user_ware_scorm table').append("<tr><td>"+index+"</td><td>"+item+"</td></tr>");
						});
					});
				}
				$('#user_ware_scorm').modal({})
			}
		}
	});
}
</script>