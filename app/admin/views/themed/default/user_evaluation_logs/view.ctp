<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-product">
    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
        <?php echo $form->create('/user_evaluation_logs',array('action'=>'/view/'.$evaluation_info['UserEvaluationLog']['id'],'id'=>'user_evaluation_log_form','name'=>'user_evaluation_log','class'=>'am-form am-form-horizontal','type'=>'POST'));?>
        <div class="am-g">
            <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:<?php echo isset($configs['is_qa_environment'])&&$configs['is_qa_environment']=='1'?90:56; ?>,animation:'slide-top'}">
                <ul>
                    <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
                    <li><a href="#question_list">答题详情</a></li>
                </ul>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Basic_information_pancel'}"><?php echo $ld['basic_information'] ?>&nbsp;</h4>
            </div>
            <div id="Basic_information_pancel" class="am-panel-collapse am-collapse am-in">
                <div id="basic_information" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['user_name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $evaluation_info['User']['name'];?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo htmlspecialchars($evaluation_info['Evaluation']['name'])?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['start_time'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $evaluation_info['UserEvaluationLog']['start_time'];?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['end_time'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $evaluation_info['UserEvaluationLog']['end_time'];?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">提交时间</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $evaluation_info['UserEvaluationLog']['submit_time'];?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">分数</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $evaluation_info['UserEvaluationLog']['score'];?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">IP地址</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $evaluation_info['UserEvaluationLog']['ipaddress'];?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['system'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $evaluation_info['UserEvaluationLog']['system'];?></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">浏览器</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo $evaluation_info['UserEvaluationLog']['browser'];?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Question_list_pancel'}">答题详情&nbsp;</h4>
            </div>
            <div id="Question_list_pancel" class="am-panel-collapse am-collapse am-in">
                <div id="question_list" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd">
                    <table class="am-table  table-main">
				<thead>
					<tr>
						<th width="75%" class='am-text-left'>题目/用户答案</th>
						<th width="10%">正确答案</th>
						<th width="5%">分值</th>
						<th width="5%">得分</th>
						<th width="5%"><?php echo $ld['status'];?></th>
					</tr>
				</thead>
				<tbody>
                        <?php if(isset($question_list) && sizeof($question_list)>0){foreach($question_list as $k=>$v){ ?>
               	   		<tr>
               	   			<td class='am-text-left'><div class="am-fl am-margin-right-xs">题目:</div><div class="am-fl"><?php echo $v['EvaluationQuestion']['name']; ?></div><div class='am-cf'></div></td>
               	   			<td><?php echo $v['EvaluationQuestion']['right_answer']; ?></td>
               	   			<td><?php echo $v['EvaluationQuestion']['question_type']=='2'?$v['EvaluationQuestion']['score']:'-'; ?></td>
	                                	<td><?php if($v['EvaluationQuestion']['question_type']=='2'&&$evaluation_info['UserEvaluationLog']['status']=='0'){ ?><input type='text' name="<?php echo 'data[UserEvaluationLogDetail]['.$v['UserEvaluationLogDetail']['id'].']';  ?>" value='0' size='3' placeholder="<?php echo '满分'.$v['EvaluationQuestion']['score'].'分'; ?>" max="<?php echo $v['EvaluationQuestion']['score']; ?>" onblur="check_question_score(this)" /><?php }else if($v['EvaluationQuestion']['question_type']=='2'&&$evaluation_info['UserEvaluationLog']['status']=='1')echo $v['UserEvaluationLogDetail']['score'];?></td>
	                                	<td>
	                                    <?php if(($v['UserEvaluationLogDetail']['answer'] == $v['EvaluationQuestion']['right_answer']&&$v['EvaluationQuestion']['question_type']!='2')||($v['EvaluationQuestion']['question_type']=='2'&&trim($v['UserEvaluationLogDetail']['answer'])!='')) {?>
	                                        <span class="am-icon-check am-yes"></span>
	                                    <?php }else{ ?>
	                                        <span class="am-icon-close am-no"></span>
	                                    <?php } ?>
	                                </td>
               	   		</tr>
                            	<tr>
                                		<td class="am-text-left" colspan='5'><div class="am-fl am-margin-right-xs">答案:</div><div class="am-fl"><?php echo $v['UserEvaluationLogDetail']['answer']; ?></div><div class='am-cf'></div></td>
                            	</tr>
                        <?php }}?>
                        </tbody>
                    </table>
                    <?php if(isset($evaluation_info['UserEvaluationLog']['status'])&&$evaluation_info['UserEvaluationLog']['status']=='0'){ ?>
                    <div class='am-g'>
                    	<button type="submit" class="am-btn am-btn-danger am-btn-sm am-radius"><?php echo $ld['post']; ?></button>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php echo $form->end(); ?>
    </div>
</div>
<style type='text/css'>
#Question_list_pancel tbody tr:nth-child(even) td{border-bottom:1px solid #ddd;}
#Question_list_pancel tbody tr:nth-child(odd) td:first-child>div.am-fl:nth-child(2)  *{white-space:normal!important;}
#Question_list_pancel tbody tr:nth-child(odd) td:first-child>div.am-fl:nth-child(2) p:first-child{display: -webkit-box;-webkit-box-orient: vertical;-webkit-line-clamp:1;overflow: hidden;text-overflow:ellipsis;white-space: nowrap;margin-bottom:0px;}
#Question_list_pancel tbody tr:nth-child(odd) td:first-child>div.am-fl:nth-child(2) p:not(:first-child){display:none;}
#Question_list_pancel tbody input[type='text']{padding:0.3rem;}
</style>
<script type='text/javascript'>
function check_question_score(input){
	var MaxScore=$(input).attr('max');
	MaxScore=MaxScore==''?1:parseInt(MaxScore);
	var InputScore=$(input).val().trim();
	InputScore=InputScore.replace(/[^\d]/g,"");
	InputScore=InputScore==''?0:parseInt(InputScore);
	if(InputScore>MaxScore){
		$(input).val(MaxScore);
	}else{
		$(input).val(InputScore);
	}
}
</script>