<?php
	//pr($user_course_assignments);
?>
<div style="text-align:left;font-size:20px;border-bottom:1px solid #ccc;padding-bottom:1rem;margin-top:1%;padding-left:5px;" >
    <span>徒弟作业列表</span>
</div>
<div class="am-u-user-point am-margin-top-0">
	<div class="am-g">
		<?php echo $form->create('',array('action'=>'/user_assignment','type'=>'get','name'=>"SearchForm","class"=>'am-form am-form-horizontal'));?>
        <div class="am-form-detail">
            <ul class="am-avg-lg-3 am-avg-md-3 am-avg-sm-1">
                <li style="padding:10px 10px;">
                    <label class="am-fl am-form-label">课程</label>
                    <div class="am-u-lg-4 am-u-md-6 am-u-sm-6">
    				<input type='text' name="user_course" value="<?php echo isset($user_course)?$user_course:'';?>" />
                    </div>
                </li>
                <li style="padding:10px 10px;">
                    <label class="am-fl am-form-label">姓名</label>
                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-6">
                        <input type="text" name="user_name" id="user_name" value="<?php echo isset($user_name)?$user_name:'';?>"/>
                    </div>
                </li>
                <li style="padding:10px 10px;">
                    <label class="am-fl am-form-label"><?php echo $ld['mobile']?></label>
                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-6">
                        <input type="text" name="user_phone" id="user_phone" value="<?php echo isset($user_phone)?$user_phone:'';?>"/>
                    </div>
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="submit" class="am-btn am-btn-primary am-btn-xs am-radius" value="<?php echo $ld['query'];?>"/></div>
                </li>
            </ul>
        </div>
        <?php echo $form->end();?>
	</div>
	<table class="am-table">
		<thead>
			<tr>
				<td width="30%" style="border-bottom:0px;" class="am-hide-sm-only">课程名称</th>
				<td width="20%" style="border-bottom:0px;"><?php echo $ld['name']?></th>
				<td width="30%" style="border-bottom:0px;">章节名称<br/>课时名称</th>
				<td width="10%" style="border-bottom:0px;" class="am-hide-sm-only">提交时间</th>
				<td width="10%" style="border-bottom:0px;">操作</th>
			</tr>
            </thead>
    		<tbody>
    			<?php if(isset($user_course_assignments)&&sizeof($user_course_assignments)>0){foreach($user_course_assignments as $v){ 
    				$course_class_total=isset($v['percentage']['learning_plan_total'])?$v['percentage']['learning_plan_total']:0;
					$complete_course_total=isset($v['percentage']['learning_plan_status_list'][1])?$v['percentage']['learning_plan_status_list'][1]:0;
					$reading_course_total=isset($v['percentage']['learning_plan_status_list'][0])?$v['percentage']['learning_plan_status_list'][0]:0;
					$complete_course_percentage=$reading_course_percentage=0;
					if($complete_course_total!=0){
						$complete_course_percentage=round($complete_course_total/$course_class_total*100,2);
					}
					if($reading_course_total!=0){
						$reading_course_percentage=round($reading_course_total/$course_class_total*100,2);
					}
					$none_read_course_percentage=100-$complete_course_percentage-$reading_course_percentage;
    			?>
    			<tr>
    				<td><div style="float: left;"><?php echo $v['Course']['name']; ?></div><div style="width:55%;float: left;height: 20px;">
                                                        	<div class="am-progress-bar am-progress-bar-success" style="<?php echo 'width:'.$complete_course_percentage.'%'; ?>">已完成(<?php echo $complete_course_percentage.'%'; ?>)</div>
                                                        	<div class="am-progress-bar am-progress-bar-warning" style="<?php echo 'width:'.$reading_course_percentage.'%'; ?>">学习中(<?php echo $reading_course_percentage.'%'; ?>)</div>
                                                        	<div class="am-progress-bar"  style="<?php echo 'width:'.$none_read_course_percentage.'%'; ?>">未开始(<?php echo $none_read_course_percentage.'%'; ?>)</div>
                                                    	</div></td>
    				<td><?php echo isset($assignment_user_list[$v['CourseAssignment']['user_id']])?($assignment_user_list[$v['CourseAssignment']['user_id']]['first_name']!=''?$assignment_user_list[$v['CourseAssignment']['user_id']]['first_name']:$assignment_user_list[$v['CourseAssignment']['user_id']]['name']):'';  ?></td>
    				<td><?php echo $v['CourseChapter']['name']; ?><br><?php echo $v['CourseClass']['name']; ?></td>
    				<td><?php echo date('Y.m.d',strtotime($v['CourseAssignment']['created'])); ?></td>
    				<td><a class="mt am-btn am-btn-primary am-seevia-btn-add am-btn-xs am-radius" href="<?php echo $html->url('/users/user_assignment_detail/'.$v['CourseAssignment']['id']); ?>" ><span class="am-icon-chevron-right"></span></a></td>
    			</tr>
    			<?php }}else{ ?>
    			<tr>
    				<td colspan="5"><div class='am-text-center am-padding-lg'>还没有作业</div></td>
    			</tr>
    			<?php } ?>
    		</tbody>
	</table>
</div>