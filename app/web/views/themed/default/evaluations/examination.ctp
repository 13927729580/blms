<style type='text/css'>
@media only screen and (max-width: 640px)
{
	.examination_qustion .examination_action{padding-top:0;}
	.examination .mianban_zi{text-align:right;}
	.examination_question{font-size:14px;}
	.examination_action .link_disabled{font-size:14px;}
	.examination_action a{font-size:14px;}
	.question_type_header{font-size:12px;}
	.suoyou{font-size:12px;}
}
.biankuang
{
	border:1px solid rgb(20, 152, 66);
}
	.yanse
{
background:#86d09f;color:#fff;}
	.am-btn-default
{
background:inherit;
border:none;}
	.am-btn-default:hover
{
background:inherit;
border:none;}

.am-btn-danger
{
    color: #fff;
    background-color: #149842;
    border-color: #149842;
    padding: 5px 10px;
    font-size: 14px;
    margin:15px 0 20px 0;
}
.tixing
{
	color:#999;
	background-color: #dcdcdc;
	margin-right:0px;
}
/*尾巴绝对定位*/
.first_foot
{
	position: fixed;
	bottom:0;
}
/*按钮样式*/
.examination_action .link_disabled
{
	border:1px solid #b9b9b9;
	color:#b9b9b9;
}
.examination_action a
{
	padding:5px 22px;
}
.toubu_daohang
{
	display:none;
}
	.am-container, .am-g-fixed
	{
		max-width:inherit;
	}
	.am-g-fixed .examination_fu
	{
		background:#fafafa;
		width:95%;
		margin:0 auto;
	}
	.examination
	{
		max-width: 1200px;
		margin:0 auto;
		    margin-bottom: 115px;
	}
	.am-list>li
	{
		background:#fafafa;
	}
	.am-checkbox, .am-checkbox-inline, .am-radio, .am-radio-inline
	{
		position: static;
	}
	.am-ucheck-icons
	{
		line-height: inherit;
	}
	.am-btn-success
	{
		border:#149842;
		background:#149842;
	}
	/*checkbok样式*/
	.am-ucheck-icons
	{
		color:#a2a2a2;
		font-size:12px;
		line-height: 30px;
	}
	.xuanzhe
	{
		color:#3ea45b;
		padding:0 5px;
	}
	.dangqian
	{
		color:#40a45b;
	}
	/*面板样式*/
	#question_type
	{
		padding:10px 0 25px 0;
	}
	.question_type_header
	{
		padding-bottom:10px;
	}
	.suoyou
	{
		color:#179943;
		background:#fff;
		border:1px solid #179943;
	}
	.tixing:focus
	{
		color:#179943;
		border:1px solid #179943;
	}
	/*标记*/
	.marks_link
	{
	    width: 75px;
	    padding: 0 0;
	    font-size: 15px;
	    line-height: 25px;
	}
	.examination_option_list .examination_option>div:hover
	{
	    cursor: pointer;
	}
	.am-ucheck-icons
	{
	font-size:14px;
	}
	/*.confir{
		width:20%;
		height:14%;
		background-color: #fff;
		border: 1px solid #ddd;
		position: absolute;
		top:50%;
		left: 50%;
		margin-top: -7%;
		margin-left: -10%;
		z-index: 100000;
		display: none;
		text-align: center;
		padding-top: 1rem;
	}*/
	/*.curtain{
		background-color: rgba(0,0,0,0.5);
		width:100%;
		height:100%;
		position:fixed;
		z-index: 99999;
		top:0;
		left:0;
		display: none;
	}*/
	/*.btn1{
		width:30%;
		position: absolute;
		left:10%;
		bottom: 5px;
	}
	.btn2{
		width:30%;
		position: absolute;
		right:10%;
		bottom: 5px;
	}*/
#question_board_dropdown .am-dropdown-toggle{padding:.5em 0 .5em 1em;}
.mianban_zi{text-align:center;}
</style>
<?php
	$evaluation_rule_question_type_total=array();
	foreach($evaluation_rule_list as $v){
		$evaluation_rule_question_type_total[$v['EvaluationRule']['question_type']]=isset($evaluation_rule_question_type_total[$v['EvaluationRule']['question_type']])?$evaluation_rule_question_type_total[$v['EvaluationRule']['question_type']]+$v['EvaluationRule']['proportion']:$v['EvaluationRule']['proportion'];
	}
	if(isset($information_data['question_type'])&&!empty($information_data['question_type']))ksort($information_data['question_type']);
?>
<div class="am-g examination_fu">
	<div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
		<div class='examination'>
		<div class="am-g">
			<div class="am-u-lg-6 am-u-md-5 am-hide-sm-only">&nbsp;</div>
			<div class="am-u-lg-2 am-u-md-3 am-u-sm-8 am-text-center" style="color:#149940;font-size:12px;padding-top:10px;">考试计时<span id="count_down" style="font-size:18px;color:#149940;padding-left:10px;">&nbsp;</span><input type="hidden" value="<?php echo $user_evaluation_log_data['UserEvaluationLog']['end_time']; ?>" id="time_end" /></div>
			<div class='am-u-lg-3 am-u-sm-4 am-u-md-4 mianban_zi' style="padding-top:5px;">
				<div class="am-dropdown" id="question_board_dropdown" data-am-dropdown>
					<div class="am-btn am-btn-default am-dropdown-toggle" style="padding-left:22px;padding-right:22px;">题目面板<span class="am-icon-caret-down" style="padding-left:3px;"></span></div>
					<div class="am-dropdown-content" id="question_board">
						<?php if(isset($evaluation_rule_list)&&sizeof($evaluation_rule_list)>0){ ?>
						<div class='' id="question_type">
								<div class="question_type_header">你正在作答的试卷是：<?php echo $evaluation_data['Evaluation']['name']; ?></div>
								<?php if(isset($information_data['question_type'])&&!empty($information_data['question_type'])){foreach($information_data['question_type'] as $k=>$v){
									if(!isset($evaluation_rule_question_type_total[$k])||$evaluation_rule_question_type_total[$k]==0)continue;
							 	?>
								<button class="am-btn tixing"  onclick="quick_question('question_type<?php echo $k; ?>')"><?php echo $v; ?>(共<?php echo $evaluation_rule_question_type_total[$k]; ?>题)</button>
								<?php }} ?>
						</div>
						<?php }?>
						<div class="am-g">
							<div style="font-size:12px;margin-bottom:5px;">当前部分包括以下题目：</div>
							<div class="question_number_list">
								<?php if(isset($evaluation_questions)&&sizeof($evaluation_questions)>0){foreach($evaluation_questions as $k=>$v){
										$question_type=$v['EvaluationQuestion']['question_type'];
										$marks_flag=$v['UserEvaluationLogDetail']['marks'];
									
								 ?>
									<div class="<?php echo 'question_type'.$question_type; echo $marks_flag=='1'?' is_marks':'' ;echo $v['UserEvaluationLogDetail']['answer']!=''?' yanse':''?>"><?php echo $k+1;?></div>
								<?php }} ?>
							</div>
						</div>
						<div class="am-u-lg-8" style="margin-top:15px;padding-left:0;">
							<div class="am-u-lg-6" style="padding-left:0;">
								<form class="am-form" method='post' action="<?php echo $html->url('/evaluations/score_result/'.$user_evaluation_log_data['UserEvaluationLog']['id']); ?>">
								<input type='hidden' name='data[UserEvaluationLog][id]' value="<?php echo $user_evaluation_log_data['UserEvaluationLog']['id']; ?>" />
								<input type='hidden' name='data[UserEvaluationLog][submit_time]' value="" />
								<a href="javascript:void(0);" type='button' class='am-btn am-radius am-btn-danger submit_question' style="margin:0 0;">交卷</a>
								</form>
							</div>
						</div>
						<div class='am-cf'></div>
					</div>
				</div>
			</div>
		
			<div class='am-cf'></div>
		</div>
		<div class="am-g examination_qustion">
			<div class="am-u-lg-10 am-u-md-9 am-u-sm-12 examination_question_list">
				<?php if(isset($evaluation_questions)&&sizeof($evaluation_questions)>0){ ?>
				<ul class="am-list">
					<?php foreach($evaluation_questions as $k=>$v){ ?>
					<li class="<?php echo $k>0?'am-hide':'am-show'; ?>">
						<form class="am-form examination_answer_form" action="">
							<div class="am-u-sm-6 am-u-lg-6 am-u-md-6 am-text-left" style="padding-left:0;">
								<span class="xuanzhe"><?php echo isset($information_data['question_type'][$v['EvaluationQuestion']['question_type']])?$information_data['question_type'][$v['EvaluationQuestion']['question_type']]:$v['EvaluationQuestion']['question_type']; ?></span>
								<span>&nbsp;(<?php
										$score_data=isset($evaluation_rule_score[$v['EvaluationQuestion']['evaluation_code']][$v['EvaluationQuestion']['question_type']])?$evaluation_rule_score[$v['EvaluationQuestion']['evaluation_code']][$v['EvaluationQuestion']['question_type']]:array();
									echo isset($score_data[$v['EvaluationQuestion']['question_type']])?$score_data[$v['EvaluationQuestion']['question_type']]:1;
								?>分)</span>
							</div>
							<div class="am-u-sm-6 am-u-lg-6 am-u-md-6 am-text-right">
								<div class="am-u-sm-6 am-u-md-12 am-u-lg-12 am-text-center">
								<span class="dangqian"><?php echo $k+1; ?></span>
								<span>/<?php echo sizeof($evaluation_questions);?></span>
								</div>
							
							</div>
									<div class="am-cf"></div>
							<div class="examination_question"><?php echo ($k+1).":";?><?php echo $v['EvaluationQuestion']['name'];//echo $v['EvaluationQuestion']['question_type']=='2'?$v['EvaluationQuestion']['name']:htmlspecialchars($v['EvaluationQuestion']['name']); ?></div>
							<div class="examination_option_list">
								<input type='hidden' name="data[UserEvaluationLogDetail][<?php echo $v['EvaluationQuestion']['code']; ?>][id]" value="<?php echo $v['UserEvaluationLogDetail']['id']; ?>" />
								<input type='hidden' class='marks_flag' name="data[UserEvaluationLogDetail][<?php echo $v['EvaluationQuestion']['code']; ?>][marks]" value="<?php echo $v['UserEvaluationLogDetail']['marks']; ?>" />
								<?php
									$evaluation_answer=explode(',',$v['UserEvaluationLogDetail']['answer']);
									$evaluation_options=isset($evaluation_option_datas[$v['EvaluationQuestion']['code']])?$evaluation_option_datas[$v['EvaluationQuestion']['code']]:array();
									$evaluation_option_names=array();
									if(!empty($evaluation_options)){
										foreach($evaluation_options as $kk=>$vv){
											$evaluation_option_names[$kk]=$vv['name'];
										}
									}
									shuffle($evaluation_options);
									$question_type=$v['EvaluationQuestion']['question_type'];
									if($question_type=='0'||$question_type=='1'){
									foreach($evaluation_options as $kk=>$vv){
								 ?>
								 <div class="examination_option">
								 	 <div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-text-center" style="padding:3px 0;"><?php if($question_type){ ?>
								 	 	<label class="am-radio am-success am-u-lg-12 am-u-md-12 am-u-sm-12">
								 	 		<div class="am-u-lg-1 am-u-md-1 am-u-sm-2">
								 	 		<input onclick="jilu_1(this)" type='checkbox' name="data[UserEvaluationLogDetail][<?php echo $v['EvaluationQuestion']['code']; ?>][answer][]" value="<?php echo $vv['name']; ?>" <?php echo in_array($vv['name'],$evaluation_answer)?'checked':''; ?> data-am-ucheck>
								 	 			<span style="text-align:left;font-size:18px;line-height:20px;color:#696969;display:inline-block;padding-top:5px;"><?php echo isset($evaluation_option_names[$kk])?$evaluation_option_names[$kk]:''; ?></span>
								 	 		</div>
								 	 		<div class="am-u-lg-11 am-u-md-11 am-u-sm-10 am-text-left" style="color:#696969;padding-top:5px;">
									 	 		<?php echo htmlspecialchars($vv['description']); ?>
								 	 		</div>
								 	 </label>
								 	 	<?php }else{ ?>
								 	 	<label class="am-radio am-success am-u-lg-12 am-u-md-12 am-u-sm-12" style="padding:4px 0;"><div class="am-u-lg-1 am-u-md-1 am-u-sm-2"><input type='radio' onclick="jilu_1(this)" name="data[UserEvaluationLogDetail][<?php echo $v['EvaluationQuestion']['code']; ?>][answer]" value="<?php echo $vv['name']; ?>" <?php echo in_array($vv['name'],$evaluation_answer)?'checked':''; ?> data-am-ucheck>
								 	 		<span style="text-align:left;font-size:18px;line-height:20px;color:#696969;display:inline-block;padding-top:5px;"><?php echo isset($evaluation_option_names[$kk])?$evaluation_option_names[$kk]:''; ?></span></div>
								 	 		<div class="am-u-lg-11 am-u-md-11 am-u-sm-10 am-text-left" style="color:#696969;padding-top:5px;"><?php echo htmlspecialchars($vv['description']); ?></div>
								 	 		</label>
								 	 	<?php }?>
								 	 </div>
								 	 <div class="am-cf"></div>
								 </div>
								 <?php }}else{ ?>
								 <div class="examination_answer">
								 	 <textarea name="data[UserEvaluationLogDetail][<?php echo $v['EvaluationQuestion']['code']; ?>][answer]" style="height:150px;resize:none;"><?php echo $v['UserEvaluationLogDetail']['answer']; ?></textarea>
								 </div>
								 <?php } ?>
							</div>
						</form>
					</li>
					<?php } ?>
				</ul>
				<?php } ?>
			</div>
			<div class="am-u-lg-2 am-u-md-3 am-u-sm-12 am-text-center examination_action">
				<div class="am-hide-lg-only am-u-sm-12 am-u-md-12">&nbsp;</div>
				<div class="am-u-lg-12 am-u-md-12 am-u-sm-4">
				<a href="javascript:void(0);" class='am-btn am-radius am-btn-warning marks_link' onclick="marks_action(this)" style='margin-bottom:30px;padding:0 0;'>标记</a></div>
				<div class="am-u-lg-12 am-u-md-12 am-u-sm-4">
				<a href="javascript:void(0);" class='am-btn am-radius am-btn-success last_question link_disabled' style="border-radius:3px;background:#fff;color:#000;border:1px solid #b9b9b9;margin-bottom:5px;">上一题</a></div>
				<div class="am-u-lg-12 am-u-md-12 am-u-sm-4">
				<a href="javascript:void(0);" class='am-btn am-radius am-btn-success next_question' style="border-radius:3px;">下一题</a></div>
				<form class="am-form" method='post' action="<?php echo $html->url('/evaluations/score_result/'.$user_evaluation_log_data['UserEvaluationLog']['id']); ?>">
					<input type='hidden' id='evaluation_log_id' name='data[UserEvaluationLog][id]' value="<?php echo $user_evaluation_log_data['UserEvaluationLog']['id']; ?>" />
					<input type='hidden' id='evaluation_blur_count' value="<?php echo intval($evaluation_data['Evaluation']['blur_time_limit']); ?>" />
					<input type='hidden' name='data[UserEvaluationLog][submit_time]' value="" />
					<a href="javascript:void(0);" type='button' class='am-btn am-radius am-btn-danger submit_question am-hide'>交卷</a>
				</form>
			</div>
			<div class="am-cf"></div>
		</div>
	</div>
	</div>
</div>
<!-- <div class="confir">
	<span></span>
	<button class="btn1 am-btn ">确定</button>
	<button class="btn2 am-btn">取消</button>
</div> -->
<!-- <div class="curtain"></div> -->

<div class="am-modal am-modal-confirm confir" tabindex="-1" id="my-confirm">
  <div class="am-modal-dialog">
    <div class="am-modal-hd" style="padding:20px 10px;">Amaze UI</div>
    
    <div class="am-modal-footer">
      <span class="am-modal-btn btn2" data-am-modal-cancel style="color: #555;">取消</span>
      <span class="am-modal-btn btn1" data-am-modal-confirm>确定</span>
    </div>
  </div>
</div>
<div class="am-modal am-modal-alert" tabindex="-1" id="window_blur">
	<div class="am-modal-dialog" style="padding:0 10px;">
		<div class="am-modal-hd" style="font-size: 20px;">警告</div>
		<div class="am-modal-bd" style="font-size: 12px;padding-top: 0;"></div>
		<div class="am-modal-footer">
			<span class="am-modal-btn">确定</span>
		</div>
	</div>
</div>
<style type='text/css'>
.examination_question_list ul li{border:none;}
.examination_question{padding:10px 5px;}
.examination_option{border:1px solid #dcdcdc;border-radius:4px;background:#fff;margin:15px auto;padding-left:10px;}
.examination_option label.am-radio{margin-top:0px;margin-bottom:5px;}
.examination_action{padding-top:78px;}
.examination_action .link_disabled{cursor: not-allowed;background:rgba(0,0,0,0.1);color:#cccccc;}
.examination_action .submit_question{padding:0.5rem 2.4rem;}

@media only screen {
	#question_board{width:300px;}
}
@media only screen and (min-width:641px) {
	#question_board{width:500px;}
}
@media only screen and (min-width:1025px) {
	#question_board{width:800px;}
}
.question_number_list>div{border-radius:3px;float:left;width:30px;text-align:center;border:1px solid #ededed;margin-right:5px;margin-bottom:5px;cursor:pointer;}
.question_number_list>div:hover,.question_number_list>div.is_marks{background:#f37b1d;border-color:#f37b1d;color:#fff;}

.marks_link span.am-icon-close{color:#dd514c;}
.marks_link span.am-icon-check{color:#5eb95e;}

#window_blur .am-modal-hd{font-size:15px;font-weight:700;}
#window_blur .am-modal-bd{padding:10px 0px;}
#window_blur .am-modal-bd span{color:red;}
</style>
<script type='text/javascript'>
$(function(){
	$('#question_board').find('.singlequestion').click(function(){
		$('#question_board_dropdown').dropdown('close');
	});
	$('#question_board').find('.multiselectquestion').click(function(){
		$('#question_board_dropdown').dropdown('close');
	});
	$('#question_board_dropdown').on('open.dropdown.amui', function (e) {
		//$('.danxuanti').click();
	});
	var time_end_txt=$("#time_end").val();
	var count_down=null;
	show_time(time_end_txt);
	
	if($(".examination_question_list li:last-child").hasClass("am-show")){
		$(".examination_action .submit_question").removeClass("am-hide");
		$(".examination_action .next_question").addClass("link_disabled");
	}
	if($(".examination_question_list li:first-child").hasClass("am-hide")){
		$(".examination_action .last_question").removeClass("link_disabled");
	}
	
	var first_question_marks_flag=$(".examination_question_list li.am-show input.marks_flag").val();
	if(first_question_marks_flag=='1'){
		$(".examination_action .marks_link").html('取消标记');
	}else{
		$(".examination_action .marks_link").html('标记');
	}
	
	$(".examination_action .next_question").click(function(){
		var next_question=$(".examination_question_list li.am-show").next();
		if(next_question.hasClass('am-hide')){
			$(".examination_question_list li.am-show").removeClass("am-show").addClass('am-hide');
			$(next_question).removeClass("am-hide").addClass("am-show");
		}
		var first_question=$(".examination_question_list li:first-child");
		if(first_question.hasClass('am-hide')){
			$(".examination_action .last_question").removeClass("link_disabled");
		}else{
			$(".examination_action .last_question").addClass("link_disabled");
		}
		var last_question=$(".examination_question_list li:last-child");
		if(last_question.hasClass('am-hide')){
			$(this).removeClass("link_disabled");
			$(".examination_action .submit_question").addClass("am-hide");
		}else{
			$(this).addClass("link_disabled");
			$(".examination_action .submit_question").removeClass("am-hide");
		}
		var marks_flag=$(".examination_question_list li.am-show input.marks_flag").val();
		if(marks_flag=='1'){
			$(".examination_action .marks_link").html('取消标记');
		}else{
			$(".examination_action .marks_link").html('标记');
		}
	});
	
	$(".examination_action .last_question").click(function(){
		var prev_question=$(".examination_question_list li.am-show").prev();
		if(prev_question.hasClass('am-hide')){
			$(".examination_question_list li.am-show").removeClass("am-show").addClass('am-hide');
			$(prev_question).removeClass("am-hide").addClass("am-show");
		}
		var first_question=$(".examination_question_list li:first-child");
		if(first_question.hasClass('am-hide')){
			$(this).removeClass("link_disabled");
		}else{
			$(this).addClass("link_disabled");
		}
		var last_question=$(".examination_question_list li:last-child");
		if(last_question.hasClass('am-hide')){
			$(".examination_action .next_question").removeClass("link_disabled");
			$(".examination_action .submit_question").addClass("am-hide");
		}else{
			$(".examination_action .next_question").addClass("link_disabled");
			$(".examination_action .submit_question").removeClass("am-hide");
		}
		var marks_flag=$(".examination_question_list li.am-show input.marks_flag").val();
		if(marks_flag=='1'){
			$(".examination_action .marks_link").html('取消标记');
		}else{
			$(".examination_action .marks_link").html('标记');
		}
	});
	
	//快速定位
	$(".question_number_list>div").click(function(){
		var question_key=$(this).index();
		$(".examination_question_list li.am-show").removeClass("am-show").addClass('am-hide');
		var question_div=$(".examination_question_list li:eq("+question_key+")");
		$(question_div).removeClass("am-hide").addClass("am-show");
		var first_question=$(".examination_question_list li:first-child");
		if(first_question.hasClass('am-hide')){
			$(this).removeClass("link_disabled");
		}else{
			$(this).addClass("link_disabled");
		}
		var last_question=$(".examination_question_list li:last-child");
		if(last_question.hasClass('am-hide')){
			$(".examination_action .next_question").removeClass("link_disabled");
			$(".examination_action .submit_question").addClass("am-hide");
		}else{
			$(".examination_action .next_question").addClass("link_disabled");
			$(".examination_action .submit_question").removeClass("am-hide");
		}
		var marks_flag=$(".examination_question_list li.am-show input.marks_flag").val();
		if(marks_flag=='1'){
			$(".examination_action .marks_link").html('取消标记');
		}else{
			$(".examination_action .marks_link").html('标记');
		}
	});
	

	$(".examination_option input[type='radio'],.examination_option input[type='checkbox']").click(function(){
		var post_form=$(this).parents("form.examination_answer_form");
		ajax_submit_answer(post_form);
	});
	
	$(".examination_answer textarea").blur(function(){
		var post_form=$(this).parents("form.examination_answer_form");
		ajax_submit_answer(post_form);
	});

	$(".submit_question").click(function(){
			var link_obj=$(this);
			$(this).css('border','none');
			$(this).css('background','#d7342e');

			$(".confir").css("display","block");
			// $(".curtain").css("display","block");
			var answer_check=0;
			var confirm_answer=false;
			$(".examination_option_list").each(function(){
				var examination_option=$(this).find('div.examination_option');
				var examination_answer=$(this).find('div.examination_answer');
				var radio_ck=$(examination_option).find("input[type='radio']:checked").length;
				var checkbox_ck=$(examination_option).find("input[type='checkbox']:checked").length;
				var textInput=$(examination_answer).find("textarea[value!='']").length;
				if(examination_option.length>0&&radio_ck==0&&checkbox_ck==0){
					answer_check++;
				}else if(examination_answer.length>0&&textInput>0){
					answer_check++;
				}
			});
			
			if(answer_check==0){
				$(".confir .am-modal-hd").html('确认交卷?');
					// confirm_answer=true;
				
			}else{
				$(".confir .am-modal-hd").html('还有'+answer_check+'题未填写,确认交卷?');
					// confirm_answer=true;
				// if(confirm('还有'+answer_check+'题未填写,确认交卷?')){
				// 	confirm_answer=true;
				// }
			}
			$('#my-confirm').modal({
        relatedTarget: this,
        onConfirm: function(options) {
			var datetime=new Date().getTime();
			var post_from=link_obj.parents('form');
			post_from.find("input[name='data[UserEvaluationLog][submit_time]']").val(datetime);
			console.log(post_from);
			post_from.submit();
        },
        // closeOnConfirm: false,
        onCancel: function() {
          
        }
      });
			// if(confirm_answer){
			// 	var datetime=new Date().getTime();
			// 	$(this).parents('form').find("input[name='data[UserEvaluationLog][submit_time]']").val(datetime);
			// 	$(this).parents('form').submit();
			// }
	});
		// $(".btn1").on('click',function(){
		// 	var datetime=new Date().getTime();
		// 		$(".submit_question").parents('form').find("input[name='data[UserEvaluationLog][submit_time]']").val(datetime);
		// 		// console.log(datetime);
		// 		  $(".submit_question").parents('form').submit();
		// })
		// $(".btn2").on('click',function(){
		// 	$(".confir").css('display',"none");
		// 	// $(".curtain").css("display","none");
			
		// })
	$('#question_type button').click(function(){
		$(this).addClass('suoyou').siblings().removeClass('suoyou');
	});

	$('.examination_option_list').find(".examination_option label").click(function(){
		$(this).parents('.examination_option').css('border','1px solid #149842').siblings().css('border','1px solid #dcdcdc');
	});
	
	$('.examination_option_list').find(".examination_option label").click(function(){
		$(this).parents('.examination_option').css('border','1px solid #149842');
	});
});

function show_time(time_end_txt){
		var time_end_info=time_end_txt.split(' ');
		var time_end_date=time_end_info[0].split('-');
		var time_end_time=time_end_info[1].split(':');
	    var time_start = new Date().getTime(); //设定当前时间
	    var time_end =  new Date(time_end_date[0],time_end_date[1],time_end_date[2],time_end_time[0],time_end_time[1],time_end_time[2]).getTime(); //设定目标时间
	    // 计算时间差 
	    var time_distance = time_end - time_start;
	    var time_distance_number=time_distance;
	    if(time_distance_number>0){
		    // 天
		    var int_day = Math.floor(time_distance/86400000) 
		    time_distance -= int_day * 86400000; 
		    // 时
		    var int_hour = Math.floor(time_distance/3600000) 
		    time_distance -= int_hour * 3600000; 
		    // 分
		    var int_minute = Math.floor(time_distance/60000) 
		    time_distance -= int_minute * 60000; 
		    // 秒 
		    var int_second = Math.floor(time_distance/1000)
	    }else{
	    	    var int_day=0;var int_hour=0;var int_minute=0;var int_second=0;
	    }
	    // 时分秒为单数时、前面加零 
	    if(int_day < 10){ 
	        int_day = "0" + int_day; 
	    } 
	    if(int_hour < 10){ 
	        int_hour = "0" + int_hour; 
	    } 
	    if(int_minute < 10){ 
	        int_minute = "0" + int_minute; 
	    } 
	    if(int_second < 10){
	        int_second = "0" + int_second; 
	    }
	    // 显示时间
	    var count_down_txt=int_hour+":"+int_minute+":"+int_second;
	    $("#count_down").html(count_down_txt); 
	    // 设置定时器
	    if(time_distance_number<0&&count_down!=null){
	    		window.clearTimeout(count_down);
	    		//自动交卷
	    		var datetime=new Date().getTime();
	    		var post_from=$("a.submit_question").eq(0).parents('form');
			post_from.find("input[name='data[UserEvaluationLog][submit_time]']").val(datetime);
			post_from.submit();
	    }else{
	    		count_down=window.setTimeout("show_time('"+time_end_txt+"')",1000);
	    }
}

function quick_question(question_type){
	$(".question_number_list div").fadeOut();
	$(".question_number_list div."+question_type).fadeIn();
}

//标记处理
function marks_action(btn){
	var marks_flag=0;
	var marks_flag_input=$(".examination_question_list li.am-show input.marks_flag");
	if(marks_flag_input.val()=='1'){
		marks_flag_input.val('0');
		$(btn).html('标记');
	}else{
		marks_flag=1;
		marks_flag_input.val('1');
		$(btn).html('取消标记');
	}
	var post_form=$(".examination_question_list li.am-show form.examination_answer_form");
	ajax_submit_answer(post_form);
	var question_key=$(".examination_question_list li.am-show").index();
	if(marks_flag=='1'){
		$(".question_number_list div:eq("+question_key+")").addClass("is_marks");
	}else{
		$(".question_number_list div:eq("+question_key+")").removeClass("is_marks");
	}
}


function submit_question_answer(){
 var question_number_list_div = $('.question_number_list div');
 if(question_number_list_div.hasClass('yanse'))
	{
		if(confirm('当前部分还有题目未答，你确定要提交吗？')){
			$(".question_number_list div:visible").each(function(){
				var obj_key=$(this).index();
				var post_form=$(".examination_question_list li:eq("+obj_key+") form");
				ajax_submit_answer(post_form);
			});
			seevia_alert('提交成功！');
		}
	}
	else
	{
		if(confirm('当前部分还未全部答完，你确定要提交吗？')){
			$(".question_number_list div:visible").each(function(){
				var obj_key=$(this).index();
				var post_form=$(".examination_question_list li:eq("+obj_key+") form");
				ajax_submit_answer(post_form);
			});
			seevia_alert('提交成功！');
		}
	}
}
//记录答过的题
function jilu_1(put){
	var tishu = $(put).parents('li').index();
	$('.question_number_list').find('div').eq(tishu).addClass('yanse');
}


function ajax_submit_answer(post_form){
	var post_data=post_form.serialize();
	$.ajax({
		url: web_base+"/evaluations/ajax_submit_answer/",
		type:"POST",
		dataType:"json",
		data:post_data,
		success: function(data){
			if(data.code!='1'){
				seevia_alert(data.message);
			}
		}
	});
}



var MaxBlurCount=$('#evaluation_blur_count').val();
var evaluation_log_id=$('#evaluation_log_id').val();
var window_blur_count=getCookie('evaluation_'+evaluation_log_id);
window_blur_count=window_blur_count==null?0:window_blur_count;
$(window).blur(function(){
	if(MaxBlurCount>0){
		window_blur_count++;
		setCookie('evaluation_'+evaluation_log_id,window_blur_count);
		$("#window_blur .am-modal-bd").html('你已离开评测<span>'+window_blur_count+'次</span>,超过<span>'+MaxBlurCount+'次</span>将结束评测<br >为了避免影响你的成绩,请谨慎作答!');
		$("#window_blur").modal('open');
		if(window_blur_count>=MaxBlurCount){
			//自动交卷
	    		var datetime=new Date().getTime();
	    		var post_from=$("a.submit_question").parents('form');
			post_from.find("input[name='data[UserEvaluationLog][submit_time]']").val(datetime);
			post_from.submit();
		}
	}
});

function getCookie(name){
	var arr,reg=new RegExp("(^| )"+name+"=([^;]*)(;|$)");
	if(arr=document.cookie.match(reg))
		return unescape(arr[2]);
	else
		return null;
}

function setCookie(name,value){
	var Days = 1;
	var exp = new Date();
	exp.setTime(exp.getTime() + Days*24*60*60*1000);
	document.cookie = name + "="+ escape (value) + ";expires=" + exp.toGMTString();
}
</script>