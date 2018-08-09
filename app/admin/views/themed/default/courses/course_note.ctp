<div>
    <?php echo $form->create('Course',array('action'=>'course_note',"type"=>"get",'class'=>'am-form am-form-horizontal'));?>
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
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">笔记内容</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7 am-padding-left-0">
    			<input type='text' name='note_content' value='<?php echo isset($note_content)?$note_content:''; ?>'/>
            </div>
        </li>
        <li class='am-form-group'>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">记录时间</label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-padding-0">
                <div class="am-input-group">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="start_date_time" value="<?php echo isset($start_date_time)?$start_date_time:"";?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
            </div>
            <div class=" am-text-center am-fl am-padding-top-sm">-</div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3 am-padding-0">
                <div class="am-input-group">
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
    <div class="listtable_div_btm">
        <div class="am-g">
            <div class="am-u-lg-3 am-u-md-3 am-u-sm-3">课程</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">用户</div>
            <div class="am-u-lg-3 am-u-md-3 am-u-sm-6">笔记内容</div>
            <div class="am-u-lg-1 am-u-md-1 am-hide-sm-only">回复数</div>
            <div class="am-u-lg-2 am-u-md-2 am-hide-sm-only">记录时间</div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['operate']; ?></div>
        </div>
        <?php if(isset($course_note_list) && sizeof($course_note_list)>0){foreach($course_note_list as $k=>$v){ ?>
            <div class="am-g">
                <div class="listtable_div_top" >
                    <div style="margin:10px auto;" class="am-g">
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $v['Course']['name']."&nbsp;<br >";echo $v['CourseChapter']['name'].'&nbsp;/&nbsp;'.$v['CourseClass']['name']; ?></div>
				<div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo isset($course_note_user_list[$v['CourseNote']['user_id']])?($course_note_user_list[$v['CourseNote']['user_id']]['first_name']!=''?$course_note_user_list[$v['CourseNote']['user_id']]['first_name']:$course_note_user_list[$v['CourseNote']['user_id']]['name']):''; ?>&nbsp;</div>
				<div class="am-u-lg-3 am-u-md-3 am-u-sm-6"><?php echo $v['CourseNote']['note']!=''?(mb_strlen($v['CourseNote']['note'],'utf-8')>50?mb_substr($v['CourseNote']['note'],0,50,'utf-8').'...':$v['CourseNote']['note']):($v['CourseNote']['media']!=''?"<a href='javascript:void(0);' onclick=\"PreviewCourseMedia('".trim($v['CourseNote']['media'])."')\"><i class='am-icon am-icon-youtube-play'></i></a>":''); ?>&nbsp;</div>
				<div class="am-u-lg-1 am-u-md-1 am-hide-sm-only"><?php echo isset($course_note_reply_list[$v['CourseNote']['id']])?$course_note_reply_list[$v['CourseNote']['id']]:0; ?></div>
				<div class="am-u-lg-2 am-u-md-2 am-hide-sm-only"><?php echo $v['CourseNote']['created']; ?></div>
				<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
					<a class="am-btn am-btn-success am-btn-xs am-seevia-btn-view" href="<?php echo $html->url('/courses/course_note_detail/'.$v['CourseNote']['id']); ?>"><span class="am-icon-eye"></span></a>
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
    <?php if(isset($course_note_list) && sizeof($course_note_list)){?>
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
						<th class='am-text-center'>开始时间</th>
						<th class='am-text-center'>结束时间</th>
					</tr>
				</thead>
				<tbody></tbody>
			</table>
        </div>
    </div>
</div>
<div class="am-modal am-modal-no-btn" tabindex="-1" id="CourseMedia">
  <div class="am-modal-dialog">
    <div class="am-modal-hd">&nbsp;
      	<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
    		<video controls="controls">你当前的浏览器不支持!</video>
    </div>
  </div>
</div>
<style type='text/css'>
#CourseMedia video{max-width:100%;max-height:100%;}
</style>
<script type='text/javascript'>
function PreviewCourseMedia(mediaPath){
	$("#CourseMedia video").attr("src", mediaPath);
	$("#CourseMedia").modal();
}
</script>