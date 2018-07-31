<style type='text/css'>
.am-modal-hd{padding:15px 10px;}
#course_comment_modal .am-icon{color:#f3b13e;}
.gouzi{    margin-right: 10px;
    width: 20px;
    height: 20px;
    border: 1px solid #149941;
    text-align: center;
    border-radius: 20px;
    background: #149941;
    color: #fff;
    line-height: 18px;}
.am-list>li
{
    border:none;
}
.course_other
{
    padding-bottom:100px;
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
.start_xuexi>div
{
    color:#888888;
}
.course_data
{
    padding:0 0 20px 0;
    width:100%;
    margin:0 auto;
}
.am-g-fixed .jianjie
{
    border-top:2px solid #ebebeb;
    padding:25px 0;
    width:95%;
    margin:0 auto;
}
.am-nav-tabs>li.am-active>a, .am-nav-tabs>li.am-active>a:focus, .am-nav-tabs>li.am-active>a:hover
{
    color:#149844;
    border:none;
}
.am-tabs-bd
{
    border:none;
}
.course_desc
{
    font-size:14px;
    padding-bottom:20px;
    color:#585858;
}
.am-btn-success
{
    background: #149842;
}
.am-btn.am-radius
{
    border-radius: 4px;
    font-size:14px;
}
.zhangjie
{
    height:45px;
    line-height: 45px;
    border-bottom: 1px solid #ccc;
    color:#333333;
    font-weight: 600;
    font-size:17px;
}
.zhangjie .am-u-sm-3
{
    padding-right:10px;
}
.admin-sidebar-list li a
{
    padding-left:15px;
}
.am-tabs-bd .am-tab-panel
{
    padding:0 0;
}
.am-panel
{
    margin-left:0;
    margin-right:0;
}
#course_chapter_list>li>ul>li
{
	 cursor: pointer;
    background: #eeeeee;
    margin:10px 15px;
    padding:10px 0;
    font-size:14px;
}
#course_chapter_list>li>ul>li a
{
    color:#898989;
}
.admin-sidebar-sub
{
    padding-left:0;
    box-shadow:none;

}
#course_chapter_list>li
{
    font-size:16px;
    border-top:1px solid #ccc;
    border-bottom:1px solid #ccc;
}
.am-panel>.am-list-static li, .am-panel>.am-list>li a
{
    padding-left:25px;
}
#course_chapter_list>li>ul>li a
{
    padding:3px 20px;
}
#course_chapter_list>li>ul>li a.xuexi_but
{
    font-size: 13px;
    border: none;
    border-radius: 4px;
}
#course_chapter_list>li>ul>li a.xuexi_danger{background-color: #dd514c;color: #fff;
}
#course_chapter_list>li>ul>li a.xuexi_success{background-color: #149842;color: #fff;}
#course_chapter_list>li>ul>li a.xuexi_complete{color: #149842;}
#course_chapter_list>li>ul>li:hover a.xuexi_complete{color: #fff;}
.a_1
{
    font-weight: 600;
}
h5
{
    padding-left:15px;
}
.jibie_list
{
    font-size:14px;
}
.jibie_list>.nandu
{
    border-right:1px solid #ccc;
}
.jibie_list>.shichang
{
    border-right:1px solid #ccc;padding-left:8%;
}
.jibie_list>.pinfen
{
    padding-left:8%;
}
.start_xuexi>div:last-child
{
    padding-top:8px;
}
.xuexi_span
{
    float:right;margin-right:10px;margin-top:0px;
}
.admin-sidebar-list li:last-child>a
{
    border-bottom: 1px solid #ccc;

}
.course_data .am-icon-heart-o{cursor:pointer;}
#course_comment_modal [class*=am-u-]{padding-left:0.5rem;padding-right:0.5rem;}
#course_comment_modal .am-icon{cursor:pointer;}
#course_comment_modal textarea{height:50px;resize: none;}
@media only screen and (max-width: 640px)
{
    #course_comment_modal .am-modal-bd{font-size:12px;}
    #course_comment_modal .am-btn-sm{font-size:12px;}
}
#course_chapter_list>li>ul>li.none_read,#course_chapter_list>li>ul>li.none_read a{cursor:default;}
#course_chapter_list>li>ul>li .gouzi{display:block;}
#course_chapter_list>li>ul>li:hover{background:#898989;}
#course_chapter_list>li>ul>li:hover .keshi{color:#fff;}
#course_chapter_list>li>ul>li .keshi.course_class_error,#course_chapter_list li.course_class_error{cursor:not-allowed;}
#ajax_access_permission .am-modal-hd{font-size:1.4rem;}
</style>
<div style="max-width: 1200px;margin:0 auto;margin-top: 30px;">
    <div class="am-g">
        <div class="am-u-sm-12 am-u-lg-12 am-u-md-12">
            <div class='course_data'>
                <h3><?php echo isset($sm['course_data'])?$sm['course_data']['name']:''; ?></h3>
                <div class="am-g start_xuexi">
                    <div class="am-u-sm-12  am-u-lg-6 am-u-md-6 jibie_list" style="padding:0;">
                        <div class="am-u-sm-3 am-u-md-3 am-u-lg-3 am-text-center nandu" style="padding:0;">难度级别<br ><?php echo isset($informationresource_infos['course_level'][$sm['course_data']['level']])?$informationresource_infos['course_level'][$sm['course_data']['level']]:'-';?></div>
                        <div class="am-u-sm-4 am-u-md-4 am-u-lg-4 am-text-center shichang" style="padding:0;padding-left:8%; ">课程时长<br ><?php echo isset($sm['course_data'])?intval($sm['course_data']['hour']):'0'; ?>分</div>
                        <div class="am-u-sm-4 am-u-md-4 am-u-lg-4 am-text-center pinfen" style="padding:0;padding-left:8%;">综合评分<br ><?php $course_avg=isset($sm['course_avg'])?$sm['course_avg']:0; echo number_format($course_avg,1,'.',''); ?></div>
                        <div class="am-cf"></div>
                    </div>
                    <div class="am-u-sm-12 am-hide-lg-only am-hide-md-only">&nbsp;</div>
                    <div class="am-u-sm-12 am-u-lg-6 am-u-md-6 am-text-center">
                        <?php if(isset($user_course_comment)){ ?>
                            <a class="am-btn am-radius am-btn-success" href="javascript:void(0);" disabled='true'>已完成</a>
                        <?php }else if(!empty($sm['course_log_detail'])&&sizeof($sm['course_log_detail'])>=$sm['course_chapter_total']){ ?>
                            <a class="am-btn am-radius am-btn-success" data-am-modal="{target: '#course_comment_modal', closeViaDimmer: 0}" href="javascript:void(0);">完成并评价</a>
                        <?php }else if(isset($access_result)&&!empty($access_result)){?>
                            <?php if($access_result['code']=='-1'&&isset($sm['course_data']['allow_learning'])&&$sm['course_data']['allow_learning']=='0'){ ?>
                            	<span>此课程为非公开报名课程</span>
                            <?php }else if($access_result['code']=='-1'&&floatval($sm['course_data']['price'])>0){ ?>
                            	<a href="<?php echo $html->url('/users/login'); ?>" class='am-btn am-btn-danger am-btn-sm'>￥<?php echo $sm['course_data']['price']; ?> 购买课程</a>
				<?php }else if($access_result['code']=='-1'){ ?>
					<a href="<?php echo $html->url('/users/login'); ?>" class='am-btn am-btn-success am-btn-sm'>开始学习</a>
				<?php }else if((!isset($sm['course_log'])||empty($sm['course_log']))&&isset($sm['course_data']['allow_learning'])&&$sm['course_data']['allow_learning']=='0'){ ?>
					<span>此课程为非公开报名课程</span>
				<?php }else if($access_result['code']=='0'&&isset($access_result['message']['max_course_read'])){
							$max_course_read_data=$access_result['message']['max_course_read'];
				?>
					<span>当前课程学习人数已满</span>
				<?php }else if($access_result['code']=='0'&&floatval($sm['course_data']['price'])>0){
							$copy_access_result=$access_result['message'];if(isset($copy_access_result['buy']))unset($copy_access_result['buy']);
							if(empty($copy_access_result)&&isset($access_result['message']['buy'])){
				?>
					<button class="am-btn am-radius am-btn-danger" onclick="virtual_purchase_pay('course','<?php echo $sm['course_data']['id']; ?>')">￥<?php echo $sm['course_data']['price']; ?> 购买课程</button>
				<?php
							}else if(!empty($copy_access_result)&&!isset($access_result['message']['buy'])){
				?>
					<button class="am-btn am-radius am-btn-danger" onclick="ajax_access_permission(this,'course','<?php echo $sm['course_data']['id']; ?>')">￥<?php echo $sm['course_data']['price']; ?> 开始学习</button>
				<?php
							}else{
				?>
					<button class="am-btn am-radius am-btn-danger" onclick="ajax_access_permission(this,'course','<?php echo $sm['course_data']['id']; ?>')">￥<?php echo $sm['course_data']['price']; ?> 购买课程 </button>
				<?php
							}
				?>
				<?php }else if($access_result['code']=='0'){ ?>
						<button class="am-btn am-radius am-btn-success" onclick="ajax_access_permission(this,'<?php echo $sm['course_data']['id'] ?>')">开始学习</button>
				<?php }else{ ?>
                                <a class="am-btn am-radius am-btn-success" target='_blank' href="<?php echo $html->url('/courses/detail/'.$sm['course_data']['id']); ?>"><?php if(!empty($sm['course_log_data'])&&sizeof($sm['course_log_data'])<=$sm['course_chapter_total']&&sizeof($sm['course_log_data'])>0){echo '继续学习';}else{echo '开始学习';} ?></a>
                            <?php } ?>
                        <?php }else if(isset($sm['course_data']['allow_learning'])&&$sm['course_data']['allow_learning']=='1'){ ?>
                            <a class="am-btn am-radius am-btn-success" href="<?php echo $html->url('/courses/detail/'.$sm['course_data']['id']); ?>">开始学习</a>
                        <?php } ?>
                    </div>
                    <div class="am-cf"></div>
                </div>
            </div>
        </div>
        <div class="am-cf"></div>
    </div>
    <!-- 简介 -->
    <div class="am-g jianjie" style="border-top: 2px solid #ebebeb;padding: 25px 0;">
        <div class="am-u-sm-12 am-u-lg-12 am-u-md-12">
            <div class="course_other">
                <div class="am-g course_desc" >
                    简介:<?php echo isset($sm['course_data'])?$sm['course_data']['description']:'';  ?>
                </div>
                <div class="am-tabs" data-am-tabs>
                    <ul class="am-tabs-nav am-nav am-nav-tabs" style="border-style: none;">
                        <li class="am-active"><a href="#course_chapter" class='am-padding-0'>章节</a></li>
                        <?php if(isset($sm['learning_plan'])&&!empty($sm['learning_plan'])){ ?><li><a href="#learning_plan" class='am-padding-0'>学习计划</a></li><?php } ?>
                        <li class="am-hide"><a href="#course_notes">问答</a></li>
                    </ul>
                    <div class="am-tabs-bd">
                        <div class="am-tab-panel am-fade am-in am-active" id="course_chapter">
                            <ul class="am-list admin-sidebar-list" id="course_chapter_list">
                                <?php
                                $chapter_key=0;
                                if(isset($sm['course_chapter'])&&sizeof($sm['course_chapter'])>0){foreach($sm['course_chapter'] as $k=>$v){ ?>
                                    <li  class="am-panel">
                                        <?php
	                                        if(isset($v['CourseClass'])&&!empty($v['CourseClass'])){ ?>
                                            <a class="a_1" data-am-collapse="{target: '#chapter_<?php echo $v['CourseChapter']['id']; ?>'}"><?php echo $v['CourseChapter']['name']; ?><i class="<?php echo $chapter_key==0?'am-icon-caret-down':'am-icon-caret-right'; ?> am-fr am-margin-right"></i></a>
                                            <ul class="zhangjie_ul_1 am-list am-collapse admin-sidebar-sub <?php echo $chapter_key==0?'am-in':''; ?>" id="chapter_<?php echo $v['CourseChapter']['id']; ?>">
                                            <?php
								foreach($v['CourseClass'] as $vv){$class_access_permission=isset($sm['class_access_permission'][$vv['id']])?$sm['class_access_permission'][$vv['id']]:array();
						  ?>
                                                    <li class="am-padding-left-lg <?php echo isset($class_access_permission['code'])&&$class_access_permission['code']!='1'?'course_class_error':''; ?>" onclick="read_course_detail(this,'<?php echo $sm['course_data']['id']; ?>','<?php echo $vv['id']; ?>');">
                                                    		<span><a class="keshi <?php echo isset($class_access_permission['code'])&&$class_access_permission['code']!='1'?'course_class_error':''; ?>" href="<?php echo isset($class_access_permission['code'])&&$class_access_permission['code']=='1'?$html->url('/courses/detail/'.$sm['course_data']['id'].'/'.$vv['id']):'javascript:void(0);'; ?>" onclick="return false;"><?php echo $vv['name']; ?></a></span>
                              					<?php if((!isset($sm['course_log'])||empty($sm['course_log']))&&isset($sm['course_data']['allow_learning'])&&$sm['course_data']['allow_learning']=='0'){
                              						      }else if(isset($class_access_permission['code'])&&$class_access_permission['code']=='0'&&isset($class_access_permission['message']['max_course_read'])){ ?>
                                                    		<?php }else if(isset($class_access_permission['code'])&&$class_access_permission['code']=='0'&&floatval($vv['price'])>0){
                                                    					$copy_class_access_result=$class_access_permission['message'];if(isset($copy_class_access_result['buy']))unset($copy_class_access_result['buy']);
                                                    					if(!empty($copy_class_access_result)&&isset($class_access_permission['message']['buy'])){
                                                    		?>
                                            				<span class="xuexi_span"><a class="xuexi_but xuexi_danger" href='javascript:void(0);' data-function="ajax_access_permission(null,<?php echo $sm['course_data']['id']; ?>,<?php echo $vv['id']; ?>);">￥<?php echo $vv['price']; ?> 购买</a></span>
                                                			<?php	}else if(!empty($copy_class_access_result)&&!isset($class_access_permission['message']['buy'])){ ?>
                                                			<span class="xuexi_span"><a class="xuexi_but xuexi_danger" href='javascript:void(0);' data-function="ajax_access_permission(null,<?php echo $sm['course_data']['id']; ?>,<?php echo $vv['id']; ?>);">开始学习</a></span>
                                                			<?php  }else{ ?>
                                            				<span class="xuexi_span"><a class="xuexi_but xuexi_danger" href='javascript:void(0);' data-function="virtual_purchase_pay('course_class',<?php echo $vv['id']; ?>);">￥<?php echo $vv['price']; ?> 购买</a></span>
                                                    			<?php	} ?>
                                                    		<?php }else if(isset($class_access_permission['code'])&&$class_access_permission['code']=='0'){ ?>
                                                    		<span class="xuexi_span"><a class="xuexi_but xuexi_danger" href='javascript:void(0);' data-function="ajax_access_permission(null,<?php echo $sm['course_data']['id']; ?>,<?php echo $vv['id']; ?>);">开始学习</a></span>
                                                    		<?php }else if(isset($class_access_permission['code'])&&$class_access_permission['code']=='1'&&isset($sm['course_log_data'][$vv['id']])){
                                                    					if($sm['course_log_data'][$vv['id']]['status']=='1'){
                                                    		?>
                                                    					<span class="xuexi_span"><a class="xuexi_but xuexi_complete" href="<?php echo $html->url('/courses/detail/'.$sm['course_data']['id'].'/'.$vv['id']); ?>"><span class="am-icon am-icon-check-circle am-icon-sm"></span>&nbsp; 已完成</a></span>
                                                    		<?php		}else{ 	?>
                                                    					<span class="xuexi_span"><a class="xuexi_but xuexi_success" href="<?php echo $html->url('/courses/detail/'.$sm['course_data']['id'].'/'.$vv['id']); ?>">继续学习</a></span>
                                                    		<?php
                                                    					}
                                                    		 	      }else if(isset($class_access_permission['code'])&&$class_access_permission['code']=='1'){?>
                                                    		<span class="xuexi_span"><a class="xuexi_but xuexi_success" href="<?php echo $html->url('/courses/detail/'.$sm['course_data']['id'].'/'.$vv['id']); ?>">开始学习</a></span>
                                                    		<?php } ?>
                                                    		<?php if(isset($vv['CourseWare'])&&sizeof($vv['CourseWare'])>0){foreach($vv['CourseWare'] as $vvv){if($vvv['CourseClassWare']['type']=='evaluation'){ ?>
                                                    		<span class="xuexi_span"><i class="am-icon am-icon-file-code-o <?php echo isset($sm['ware_result']['ware_evaluation'][$vvv['CourseClassWare']['ware']])&&$sm['ware_result']['ware_evaluation'][$vvv['CourseClassWare']['ware']]=='1'?'am-text-success':''; ?>" title="<?php echo $vvv['CourseClassWare']['name']; ?>"></i></span>
                                                    		<?php }else if($vvv['CourseClassWare']['type']=='assignment'){ ?>
                                                    		<span class="xuexi_span"><i class="am-icon am-icon-file-text-o <?php echo isset($sm['ware_result']['ware_assignment'][$vvv['CourseClassWare']['id']])?'am-text-success':''; ?>" title="<?php echo $vvv['CourseClassWare']['name']; ?>"></i></span>
                                                    		<?php }}} ?>
                                                    </li>
                                                    <?php } ?>
                                            </ul>
                                        <?php }else{ ?>
                                            <a href="javascript:void(0);"><?php echo $v['CourseChapter']['name']; ?></a>
                                        <?php } ?>
                                    </li>
                                    <?php $chapter_key++;}} ?>
                            </ul>
                        </div>
                        <?php if(isset($sm['learning_plan'])&&!empty($sm['learning_plan'])){
                        			$course_date_list=isset($sm['course_start_date_list'])?$sm['course_start_date_list']:array();
                        			$course_start=!empty($course_date_list)?date('Y-m-d',min($course_date_list)):date('Y-m-d');
                         ?>
                            <div class="am-tab-panel am-fade" id="learning_plan">
                                <div class='am-g'>
                                    开始学习时间:<strong><?php echo $course_start; ?></strong>,已学习<strong><?php echo round((time()-strtotime($course_start))/(3600*24)); ?>天</strong><?php
                                   	if(!isset($user_course_comment)||empty($user_course_comment)){
                                			$learning_plan_days=isset($sm['learning_plan'])&&sizeof($sm['learning_plan'])>0?array_keys($sm['learning_plan']):array();
							$learning_plan_day_total=sizeof($learning_plan_days)*7;
							$Expected_end_time=strtotime($course_start." +{$learning_plan_day_total} day");
                                			$SurplusDays=round(($Expected_end_time-time())/(3600*24));
                                			echo $SurplusDays>=0?",剩余<strong>".($SurplusDays)."天</strong>":",延误<strong>".round((time()-$Expected_end_time)/(3600*24))."</strong>";
						}
                                ?>
                                </div>
                                <table class='am-table'>
						<thead>
							<tr>
								<th class='am-text-center'>周期</th>
								<th>章节</th>
								<th>课时</th>
								<th><?php echo $ld['status']; ?></th>
								<th>完成时间</th>
							</tr>
						</thead>
						<tbody>
							<?php
								     if(isset($sm['learning_plan'])&&sizeof($sm['learning_plan'])>0){foreach($sm['learning_plan'] as $k=>$v){
								     	 	$Expected_completion_time=null;
								     	 	$course_last_read=null;
								     	 	if(is_array($v)&&sizeof($v)>0){foreach($v as $kk=>$vv){
								     	 		if(isset($sm['course_start_date_list'][$vv['CourseClass']['id']])){
								     	 			$course_class_last_read=$sm['course_start_date_list'][$vv['CourseClass']['id']];
								     	 			if($course_last_read==null){
								     	 				$course_last_read=$course_class_last_read;
								     	 			}else if($course_last_read>$course_class_last_read){
								     	 				$course_last_read=$course_class_last_read;
								     	 			}
								     	 		}
								     	 	}}
								     	 	if($course_last_read!=null)$Expected_completion_time=date('Y/m/d',strtotime(date('Y-m-d',$course_last_read)." +7 day"));
										if(is_array($v)&&sizeof($v)>0){foreach($v as $kk=>$vv){
							?>
								<tr>
									<?php if($kk==0){ ?>
									<td rowspan="<?php echo is_array($v)?sizeof($v):'1'; ?>" style="text-align:center;vertical-align:middle;">第<?php echo $k; ?>周</td>
									<?php } ?>
									<td><?php echo $vv['CourseChapter']['name']; ?></td>
									<td><?php echo $vv['CourseClass']['name']; ?></td>
									<td><?php echo isset($sm['course_log_data'][$vv['CourseClass']['id']])?($sm['course_log_data'][$vv['CourseClass']['id']]['status']=='1'?'已完成':'学习中'):'未开始';?></td>
									<td><?php echo isset($sm['course_log_data'][$vv['CourseClass']['id']])&&$sm['course_log_data'][$vv['CourseClass']['id']]['status']=='1'?"".(date('Y/m/d',strtotime($sm['course_log_data'][$vv['CourseClass']['id']]['modified'])))."</span>":("<span class='am-text-danger'>".$Expected_completion_time."</span>");?></td>
								</tr>
							<?php }} ?>
							<?php }} ?>
						</tbody>
                                </table>
                            </div>
                        <?php } ?>
                        <div class="am-tab-panel am-fade" id="course_notes">
                            <h5>问答</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="am-cf"></div>
    </div>

    <div class="am-modal am-modal-no-btn" tabindex="-1" id="course_comment_modal">
        <div class="am-modal-dialog">
            <div class="am-modal-hd">
                <h4 class="am-popup-title am-text-left">课程评价</h4>
                <span data-am-modal-close class="am-close">&times;</span>
            </div>
            <div class="am-modal-bd">
                <form method="post" class="am-form am-form-detail">
                    <input type='hidden' name="data[CourseComment][course_id]" value="<?php echo $sm['course_data']['id']; ?>" />
                    <?php if(isset($informationresource_infos['course_comment'])){foreach($informationresource_infos['course_comment'] as $k=>$v){ ?>
                        <div class='am-form-group star_source'>
                            <input type='hidden' name="data[CourseComment][<?php echo $k; ?>]" value="" />
                            <label class='am-u-sm-4 am-u-md-3 am-u-lg-3 am-form-label am-text-right'><?php echo $v; ?></label>
                            <?php for($i=0;$i<5;$i++){ ?><div class='am-u-sm-1 am-text-center'><span class='am-icon am-icon-star-o am-icon-sm'></span></div><?php } ?>
                            <div class='am-u-sm-1'>&nbsp;</div>
                            <div class='am-cf'></div>
                        </div>
                    <?php }} ?>
                    <div class='am-form-group'>
                        <label class='am-u-sm-4 am-u-md-3 am-u-lg-3 am-form-label am-text-right'><?php echo $ld['other']; ?></label>
                        <div class='am-u-sm-7 am-u-md-8 am-u-lg-9 am-text-left'>
                            <textarea style="font-size:14px;" name="data[CourseComment][comment]"></textarea>
                        </div>
                        <div class='am-cf'></div>
                    </div>
                    <div class='am-form-group'>
                        <div class="am-u-sm-4 am-u-md-3 am-u-lg-3">&nbsp;</div>
                        <div class='am-u-sm-8 am-u-md-9 am-u-lg-9 am-text-left'>
                            <button type='button' class='am-btn am-btn-sm am-btn-block am-btn-success am-round' onclick='ajax_course_comment(this)'>完成并评价</button>
                        </div>
                        <div class='am-cf'></div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="wechat_ajax_payaction">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <div class="am-text-center"><?php echo $html->image('/theme/default/images/loading.gif');  ?></div>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="ajax_access_permission">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">满足以下条件您可以开始学习:
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <div class="am-text-center"><?php echo $html->image('/theme/default/images/loading.gif');  ?></div>
        </div>
    </div>
</div>
<?php
$wechat_imgUrl=trim($sm['course_data']['img']);
if(strstr($wechat_imgUrl,$server_host)<0&&strstr($wechat_imgUrl,'http')<0){
    $wechat_imgUrl=$server_host.(str_replace($server_host,'',$sm['course_data']['img']));
}
?>
<script type='text/javascript'>
    var wechat_shareTitle="<?php echo $sm['course_data']['name']; ?>";
    var wechat_descContent="<?php echo $svshow->emptyreplace($sm['course_data']['description']); ?>";
    var wechat_lineLink=location.href.split('#')[0];
    <?php
    if(trim($wechat_imgUrl)!=""&&$svshow->imgfilehave($wechat_imgUrl)){  ?>
    var wechat_imgUrl="<?php echo $wechat_imgUrl; ?>";
    <?php } ?>

    $(function(){
        $('#course_chapter_list li').on('open.collapse.amui', function() {
            $(this).find('a i').removeClass("am-icon-caret-right").addClass("am-icon-caret-down");
        }).on('close.collapse.amui', function() {
            $(this).find('a i').removeClass("am-icon-caret-down").addClass("am-icon-caret-right");
        });

        $("#course_comment_modal .am-icon").click(function(){
            var icon_span=$(this);
            var prev_icon_length=$(icon_span).parent().prev().find('span.am-icon').length;
            var next_icon_length=$(icon_span).parent().next().find('span.am-icon').length;
            if(prev_icon_length>0){//非第一个
                var prev_icons=$(icon_span).parent().prevAll('div');
                prev_icons.each(function(index,item){
                    var icon_length=$(item).find('span.am-icon').length;
                    if(icon_length==0)return;
                    $(item).find('span.am-icon').removeClass('am-icon-star-o').addClass('am-icon-star');
                });
            }
            if(next_icon_length>0){//非最后一个
                var next_icons=$(icon_span).parent().nextAll('div');
                next_icons.each(function(index,item){
                    var icon_length=$(item).find('span.am-icon').length;
                    if(icon_length==0)return;
                    $(item).find('span.am-icon').removeClass('am-icon-star').addClass('am-icon-star-o');
                });
            }
            $(icon_span).removeClass('am-icon-star-o').addClass('am-icon-star');
        });

        $("#course_comment_modal .am-icon").hover(function(){
            var icon_span=$(this);
            var prev_icon_length=$(icon_span).parent().prev().find('span.am-icon').length;
            var next_icon_length=$(icon_span).parent().next().find('span.am-icon').length;
            if($(icon_span).hasClass('am-icon-star-o')){
                $(icon_span).removeClass('am-icon-star-o').addClass('am-icon-star');
            }
            if(prev_icon_length>0){//非第一个
                var prev_icons=$(icon_span).parent().prevAll('div');
                prev_icons.each(function(index,item){
                    var icon_length=$(item).find('span.am-icon').length;
                    if(icon_length==0)return;
                    $(item).find('span.am-icon').removeClass('am-icon-star-o').addClass('am-icon-star');
                });
            }
            if(next_icon_length>0){//非最后一个
                var next_icons=$(icon_span).parent().nextAll('div');
                next_icons.each(function(index,item){
                    var icon_length=$(item).find('span.am-icon').length;
                    if(icon_length==0)return;
                    $(item).find('span.am-icon').removeClass('am-icon-star').addClass('am-icon-star-o');
                });
            }
        },function(){

        });
    });

    function ajax_course_comment(btn){
        var post_form=$(btn).parents('form');
        var source_flag=false;
        var error_params_name="";
        $("#course_comment_modal div.star_source").each(function(){
            var star_source=$(this).find('span.am-icon-star').length*1;
            star_source+=$(this).find('span.am-icon-star-half-full').length*0.5;
            $(this).find("input[type='hidden']").val(star_source);
            if(star_source==0){
                source_flag=true;
                error_params_name=$(this).find('label.am-form-label').text();
            }
        });
        if(source_flag){
            alert("请给"+error_params_name+"打分");
            return false;
        }
        var post_data=post_form.serialize();
        $.ajax({
            type: "POST",
            url:web_base+"/courses/ajax_course_complete/",
            data:post_data,// 你的formid
            dataType:"json",
            async: false,
            success: function(data) {
		            alert(data.message);
		            if(data.code=='1'){
		                    $('#course_comment_modal').modal('close');
		                    window.location.reload();
		            }
            },
            complete:function(){
            	
            }
        });
    }
    
    function user_login(){
    		seevia_alert_func(function(){
    			window.location.href=web_base+"/users/login";
    		},'请先登录再购买');
    }
    
    function ajax_access_permission(btn,course_id,course_class_id){
    		btn=typeof(btn)=='undefined'?null:btn;
    		course_class_id=typeof(course_class_id)=='undefined'?0:course_class_id;
    		$(btn).button("loading");
    		$.ajax({
			type: "POST",
			url:web_base+"/courses/ajax_access_permission",
			data:{'course_id':course_id,'course_class_id':course_class_id},
			dataType:"html",
			success: function(result) {
				if(result.trim()!=''){
					$("#ajax_access_permission .am-modal-bd").html(result);
					$("#ajax_access_permission").modal({closeViaDimmer:false});
				}else{
					window.location.href=web_base+"/courses/detail/"+course_id+'/'+course_class_id;
				}
			},
			complete:function(){
				$(btn).button("reset");
			}
    		});
    }
    
    function read_course_detail(li,course_id,course_class_id){
    		var title_span=$(li).find('span a');
    		if(title_span.hasClass('course_class_error'))return false;
    		var xuexi_span=$(li).find("span.xuexi_span a");
    		var data_function=$(xuexi_span).attr("data-function");
    		if(typeof(data_function)!='undefined'&&data_function!=''){
    			eval(data_function);
    		}else{
    			window.location.href=web_base+"/courses/detail/"+course_id+'/'+course_class_id;
    		}
    }
</script>