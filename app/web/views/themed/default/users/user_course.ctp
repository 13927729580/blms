<style type='text/css'>
    .am-list>li
    {
        border:none;
    }
    h3
    {
        font-size:28px;
        padding-bottom:20px;
    }
    a
    {
        color:#888888;
    }
    .am-nav-tabs>li.am-active>a, .am-nav-tabs>li.am-active>a:focus, .am-nav-tabs>li.am-active>a:hover{
        color:#149844;
        border:none;
    }
    .admin-sidebar-list li a
    {
        padding-left:15px;
    }
    .am-panel
    {
        margin-left:0;
        margin-right:0;
    }
    .admin-sidebar-sub
    {
        padding-left:0;
        box-shadow:none;
    }
    .am-panel>.am-list-static li, .am-panel>.am-list>li a
    {
        padding-left:25px;
    }
    .a_1
    {
        font-weight: 600;
    }
    h5
    {
        padding-left:15px;
    }
    .am-nav-tabs>li.am-active>a, .am-nav-tabs>li.am-active>a:focus, .am-nav-tabs>li.am-active>a:hover{
        color: #000;
        border-bottom: 2px solid #000;
    }
    .chapter_no{
        border-left: 2px red #d82b2b;
    }
    .shuxian{
        border-left: 2px solid #d82b2b;
        height: 16px;
        display: inline-block;
        width: 10px;
        vertical-align: middle;
    }
    #learning_plan table.am-table th,#learning_plan table.am-table td{text-align:center;}
</style>
<div class='am-padding-top-lg'>
    <?php if(!empty($course_data)){foreach($course_data as $k=>$v){?>
        <div style="text-align:left;font-size:20px;border-bottom:1px solid #ccc;padding-bottom:1rem;margin-top:1%;padding-left:5px;padding-top: 35px;" >
            <span>课程名称：<?php echo isset($v['course_data']['name'])?$v['course_data']['name']:"";?>——<?php echo $apprentice_list['User']['name'];?></span>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-bd">
                <ul class="course_chapter_list am-list admin-sidebar-list" id="course_chapter_list">
                    <?php
                    $chapter_key=0;
                    if(isset($v['course_chapter'])&&sizeof($v['course_chapter'])>0){foreach($v['course_chapter'] as $kk=>$vv){ ?>
                        <li class="am-panel chapter_no" style="padding-bottom:0px;">
                            <?php if(isset($vv['CourseClass'])&&!empty($vv['CourseClass'])){ ?>
                                <a style="padding-bottom:10px;" class="a_1" data-am-collapse="{target: '#chapter_<?php echo $vv['CourseChapter']['id']; ?>'}"><div class="shuxian"></div><p style="font-size: 16px;display: inline-block;vertical-align: middle;"><?php echo $vv['CourseChapter']['name']; ?></p><i class="am-icon-angle-down am-fr am-margin-right"></i></a>
                                <ul class="zhangjie_ul_1 am-list am-collapse admin-sidebar-sub" id="chapter_<?php echo $vv['CourseChapter']['id']; ?>">
                                    <?php foreach($vv['CourseClass'] as $vvv){?>
                                        <li style="padding-left: 20px;">
                                            <table style="width:100%">
                                                <tr>
                                                    <td style="width:60%">
                                                        <div style="width:45%;float: left;"><?php echo $vvv['name']; ?></div>
                                                        <?php
                                                        $course_class_total=isset($v['learning_plan_total'])?$v['learning_plan_total']:0;
                                                        $complete_course_total=isset($v['learning_plan_status_list'][1])?$v['learning_plan_status_list'][1]:0;
                                                        $reading_course_total=isset($v['learning_plan_status_list'][0])?$v['learning_plan_status_list'][0]:0;
                                                        $complete_course_percentage=$reading_course_percentage=0;
                                                        if($complete_course_total!=0){
                                                        	$complete_course_percentage=round($complete_course_total/$course_class_total*100,2);
                                                        }
                                                        if($reading_course_total!=0){
                                                        	$reading_course_percentage=round($reading_course_total/$course_class_total*100,2);
                                                        }
                                                        $none_read_course_percentage=100-$complete_course_percentage-$reading_course_percentage;
                                                        ?>
                                                        <div style="width:55%;float: left;height: 20px;">
                                                        	<div class="am-progress-bar am-progress-bar-success" style="<?php echo 'width:'.$complete_course_percentage.'%'; ?>">已完成(<?php echo $complete_course_percentage.'%'; ?>)</div>
                                                        	<div class="am-progress-bar am-progress-bar-warning" style="<?php echo 'width:'.$reading_course_percentage.'%'; ?>">学习中(<?php echo $reading_course_percentage.'%'; ?>)</div>
                                                        	<div class="am-progress-bar"  style="<?php echo 'width:'.$none_read_course_percentage.'%'; ?>">未开始(<?php echo $none_read_course_percentage.'%'; ?>)</div>
                                                    	</div>
	                                                </td>
                                                    <!--<td style="width:15%"><?php if(isset($v['course_log_data'][$vvv['id']]['status'])&&$v['course_log_data'][$vvv['id']]['status']){?>已完成<?php }else{?>未完成<?php }?></td>-->
                                                    <td style="width:20%" class="am-hide-sm-only"><?php echo isset($vvv['courseware_hour'])&&ceil($vvv['courseware_hour'])>0?ceil($vvv['courseware_hour'])."分钟":''; ?>
                                                    <br/><?php if(isset($v['course_log_data'][$vvv['id']]['created'])&&$v['course_log_data'][$vvv['id']]['created']){echo date("Y.m.d",strtotime($v['course_log_data'][$vvv['id']]['created']));}?>
                                                        <span style="<?php if(isset($v['course_log_data'][$vvv['id']]['status'])&&$v['course_log_data'][$vvv['id']]['status']!=1){?>color:#c8bebe<?php }?>"><?php if(isset($v['course_log_data'][$vvv['id']]['modified']) && !empty($v['course_log_data'][$vvv['id']]['modified'])){
                                                                echo "—".date("Y.m.d",strtotime($v['course_log_data'][$vvv['id']]['modified']));}?></span></td>
                                                    <td style="width:10%">笔记数：<?php if(isset($v['course_notes'][$vvv['id']])){echo $v['course_notes'][$vvv['id']];}else{echo "0";}?></td>
                                                    <td style="width:10%"><?php if(isset($v['course_notes'][$vvv['id']]) && $v['course_notes'][$vvv['id']]!=0){?><a style="color:#0e90d2" onclick="check_buy(<?php echo $flag[$vvv['id']]['code'];?>,<?php echo $v['course_data']['id'];?>,<?php echo $vvv['id'];?>)">查看</a><?php }?></td>
                                                </tr>
                                            </table>
                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php }else{ ?>
                                <a class="a_1"><div class="shuxian"></div><p style="display: inline-block;vertical-align: middle;"><?php echo isset($v['CourseChapter']['name'])?$v['CourseChapter']['name']:""; ?></p><i class="<?php echo $chapter_key==0?'am-icon-angle-down':'am-icon-angle-down'; ?> am-fr am-margin-right"></i></a>
                            <?php } ?>
                        </li>
                        <?php $chapter_key++;}} ?>
                </ul>
            </div>
        </div>
    <?php }}else{?>
    	<div style="text-align: center;padding-top: 100px;">暂无课程</div>
    <?php }?>
</div>
<script type='text/javascript'>
    function check_buy(flag,course_id,course_class_id){
        if(flag==1){
            window.location.href="/courses/detail/"+course_id+"/"+course_class_id;
        }else{
            alert("您没有权限查看");
        }
    }
</script>