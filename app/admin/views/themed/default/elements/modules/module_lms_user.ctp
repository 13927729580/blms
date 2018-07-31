<div class="am-panel am-panel-default" id="course_list" >
            <div class="am-panel-hd">课程记录
            </div>
                <div class="am-panel-bd" id="style">
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th class="am-u-lg-4 am-u-md-3 am-u-sm-2">课程<?php echo $ld['name'];?></th>
                            <th class="am-u-lg-1 am-u-md-1"><?php echo $ld['status'];?></th>
                            <th class="am-u-lg-1 am-u-md-1 am-u-sm-1">阅读次数</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($course_list) && sizeof($course_list)>0){foreach($course_list as $k=>$v){ ?>
                            <tr >
                                <td class="am-u-lg-4 am-u-md-3 am-u-sm-2"><?php echo htmlspecialchars($v['Course']['name']);?></td>
                                <td class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                                    <?php if ($v['UserCourseClass']['status'] == 0) {?>
                                        无效
                                    <?php }elseif($v['UserCourseClass']['status'] == 1){ ?>
                                        学习中
                                    <?php }elseif($v['UserCourseClass']['status'] == 2){ ?>
                                        已完成
                                    <?php }elseif($v['UserCourseClass']['status'] == 3){ ?>
                                        已评价
                                    <?php } ?>
                                </td>
                                <td class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['UserCourseClass']['readcount'];?></td>
                            </tr>
                        <?php }}else{?>
                            <tr><td colspan="6" align="center"><?php echo $ld['no_data_found']?></td></tr>
                        <?php }?>
                        </tbody>
                    </table>
            </div>
        </div>
        <div class="am-panel am-panel-default"  id="evaluation_list" >
            <div class="am-panel-hd">评测记录</div>
                <div class="am-panel-bd" id="style">
                    <table class="am-table  table-main">
                        <thead>
                        <tr>
                            <th class="am-u-lg-3 am-u-md-3 am-u-sm-3">评测<?php echo $ld['name'];?></th>
                            <th class="am-u-lg-2 am-u-md-2 am-u-sm-2">评测分数</th>
			                <th class="am-u-lg-3 am-u-md-3 am-u-sm-3">开始时间</th>
			                <th class="am-u-lg-3 am-u-md-3 am-u-sm-3">提交时间</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if(isset($evaluation_list) && sizeof($evaluation_list)>0){foreach($evaluation_list as $k=>$v){ ?>
                            <tr >
                                <td class="am-u-lg-3 am-u-md-3 am-u-sm-2"><?php echo htmlspecialchars($v['Evaluation']['name']);?></td>
                                <td class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['UserEvaluationLog']['score'];?></td>
                                <td class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $v['UserEvaluationLog']['start_time'];?></td>
                                <td class="am-u-lg-3 am-u-md-3 am-u-sm-3"><?php echo $v['UserEvaluationLog']['submit_time'];?></td>
                            </tr>
                        <?php }}else{?>
                            <tr><td colspan="6" align="center"><?php echo $ld['no_data_found']?></td></tr>
                        <?php }?>
                        </tbody>
                    </table>

            </div>
        </div>