<div>
    <?php echo $form->create('Course',array('action'=>'user_course_detail','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'class'=>'am-form am-form-horizontal'));?>
    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">课程</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7 am-padding-left-0">
                <input type='text' name='course_keyword' value='<?php echo isset($course_keyword)?$course_keyword:''; ?>' />
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">用户</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7 am-padding-left-0">
    			<input type='text' name='user_keyword' value='<?php echo isset($user_keyword)?$user_keyword:''; ?>'/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">学习时间</label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3" style="padding-left:0;padding-right:0.5rem;">
                <div class="am-input-group">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="start_date_time" value="<?php echo isset($start_date_time)?$start_date_time:"";?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
            </div>
            <div class=" am-text-center am-fl " style="margin-top:7px;">-</div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3" style="padding-left:0.5rem;padding-right:0;">
                <div class="am-input-group">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="end_date_time" value="<?php echo isset($end_date_time)?$end_date_time:"";?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">最后学习</label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3" style="padding-left:0;padding-right:0.5rem;">
                <div class="am-input-group">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="last_read_date_start" value="<?php echo isset($last_read_date_start)?$last_read_date_start:"";?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
            </div>
            <div class=" am-text-center am-fl " style="margin-top:7px;">-</div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3" style="padding-left:0.5rem;padding-right:0;">
                <div class="am-input-group">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="last_read_date_end" value="<?php echo isset($last_read_date_end)?$last_read_date_end:"";?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
            </div>
        </li>
        <li >
            <label class="am-u-sm-3 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
            <div class="am-u-sm-8 am-u-md-8 am-u-sm-7 am-padding-left-0">
                <input type="submit"  class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" />
            </div>
        </li>
    </ul>
    <?php echo $form->end()?>
</div>
<div class='am-margin-top-sm am-text-right'>
    <?php echo $html->link('Scorm课件学习情况','/courses/user_ware_scorm',array('class'=>'am-btn am-btn-default am-btn-xs am-radius am-margin-right-xs'));echo $html->link('课程作业','/courses/course_assignment',array('class'=>'am-btn am-btn-default am-btn-xs am-radius am-margin-right-xs'));echo $html->link('课程笔记','/courses/course_note',array('class'=>'am-btn am-btn-default am-btn-xs am-radius')); ?>
</div>
<div class='am-margin-top-lg'>
    <div class="listtable_div_btm am-margin-bottom-sm">
        <div class="am-g">
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">课程</div>
            <div class="am-u-lg-3 am-u-md-3 am-u-sm-3">用户</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">开始学习时间</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">最后学习时间</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $ld['status'];?></div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['operate']; ?></div>
        </div>
        <?php if(isset($user_course_list) && sizeof($user_course_list)>0){foreach($user_course_list as $k=>$v){ ?>
            <div class="am-g">
                <div class="listtable_div_top" >
                    <div style="margin:10px auto;" class="am-g">
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['Course']['name']; ?></div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo isset($v['User']['name'])?$v['User']['name']:'';echo "&nbsp;";echo isset($v['User']['mobile'])&&$v['User']['mobile']!=$v['User']['name']?$v['User']['mobile']:''; ?></div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo isset($user_read_detail[$v['UserCourseClass']['user_id']][$v['UserCourseClass']['course_id']])?$user_read_detail[$v['UserCourseClass']['user_id']][$v['UserCourseClass']['course_id']]['first_read']:'-'; ?></div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo isset($user_read_detail[$v['UserCourseClass']['user_id']][$v['UserCourseClass']['course_id']])?$user_read_detail[$v['UserCourseClass']['user_id']][$v['UserCourseClass']['course_id']]['last_read']:'-'; ?></div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><a style="color:black;cursor:pointer;" title="学习中"><?php echo isset($couse_class_detail_list[$v['UserCourseClass']['user_id']][$v['UserCourseClass']['course_id']])?$couse_class_detail_list[$v['UserCourseClass']['user_id']][$v['UserCourseClass']['course_id']]:0;?></a>&nbsp;/&nbsp;<span class='am-text-success'><a style="color:#5eb95e;cursor:pointer;" title="已完成"><?php echo isset($user_complete_course[$v['UserCourseClass']['user_id']][$v['UserCourseClass']['course_id']])?$user_complete_course[$v['UserCourseClass']['user_id']][$v['UserCourseClass']['course_id']]:0;?></a></span>&nbsp;/&nbsp;<a style="color:lightgrey;cursor:pointer;" title="总章节"><?php echo isset($couse_class_list[$v['Course']['code']])?$couse_class_list[$v['Course']['code']]:0; ?></a></div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
					<a class="mt am-btn am-btn-success am-btn-xs am-seevia-btn-view" href="javascript:void(0);" onclick="course_class_detail(<?php echo $v['UserCourseClass']['id']; ?>)"><span class="am-icon-eye"></span></a>
                		</div>
                    </div>
                </div>
            </div>
        <?php }}else{?>
            <div>
                <div class="no_data_found" ><?php echo $ld['no_data_found']?></div>
            </div>
        <?php }?>
    </div>
    <?php if(isset($user_course_list) && sizeof($user_course_list)){?>
            <?php echo $this->element('pagers')?>
    <?php }?>
</div>

<div class="am-modal am-modal-no-btn" id="course_class_detail">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">
            <h4 class="am-popup-title">学习记录</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
			<table class='am-table'>
				<thead>
					<tr>
						<th class='am-text-center'>课时</th>
						<th class='am-text-center'>时长(分)</th>
						<th class='am-text-center' width='15%'>是否完成</th>
						<th class='am-text-center'>开始时间</th>
						<th class='am-text-center'>结束时间</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
        </div>
    </div>
</div>
<style type='text/css'>
#SearchForm ul li{margin-bottom:10px;}
#course_class_detail.am-modal .am-modal-bd{max-height:450px;overflow-y: scroll;}
#course_class_detail.am-modal .am-modal-bd::-webkit-scrollbar{width:2px;}
#course_class_detail.am-modal .am-modal-bd::-webkit-scrollbar-track{background:#fff;}
</style>
<script type='text/javascript'>
function course_class_detail(user_course_class_id){
	$.ajax({
		url: admin_webroot+"courses/course_log_detail/"+user_course_class_id,
		type:"POST",
		data:{},
		dataType:"json",
		success: function(data){
			if(data.code=='1'){
				$('#course_class_detail table tbody').html('');
				$.each(data.user_course_class,function(index,item){
					var LogHtml="<tr>";
					LogHtml+="<td>"+item['CourseClass']['name']+"</td>";
					LogHtml+="<td>"+Math.ceil(parseInt(item['UserCourseClassDetail']['read_time'])/60)+"</td>";
					LogHtml+="<td>"+(item['UserCourseClassDetail']['status']=='1'?"<span class='am-icon am-icon-check am-text-success'></span>":"<span class='am-icon am-icon-times am-text-danger'></span>")+"</td>";
					LogHtml+="<td>"+item['UserCourseClassDetail']['created']+"</td>";
					LogHtml+="<td>"+item['UserCourseClassDetail']['modified']+"</td>";
					LogHtml+="</tr>";
					$('#course_class_detail table tbody').append(LogHtml);
				});
				if (!$("#course_class_detail").hasClass('am-modal-active')) {
					$("#course_class_detail").modal({'closeViaDimmer':false});
				}
			}
		}
	});
}
</script>