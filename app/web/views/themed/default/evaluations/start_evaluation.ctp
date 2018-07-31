<style type='text/css'>
/*小屏*/
@media only screen and (max-width: 640px)
{
	.zhuyi ul li{font-size:12px;}
	.evaluation_type .am-radius{font-size:16px;}
	.evaluation_desc_content{font-size:12px;}
}
.toubu_daohang
{
display:none;
}
.am-container, .am-g-fixed
{
	max-width: inherit;
}
	.start_evaluation_fu
	{
		background:#f8f8f8;
		padding-bottom:60px;
	}
	.start_evaluation
	{
		width:95%;
		max-width: 1200px;
		margin:0 auto;
		padding:30px 0;
	}
	.am-list>li
	{
		background:none;
		
	}
	.am-btn-secondary
	{
		background:#149842;
		border-color:#149842;
	}
	.am-btn-secondary:focus, .am-btn-secondary:hover
	{
		background:#149842;
		border-color:#149842;
	}
	.am-radius
	{
		    padding: 13px 40px;
    		font-size: 19px;
	}
	.dian
	{
		color:#434343;
		font-size:12px;
	}
	.dian_2
	{
	color:##efb70a;
	font-size:12px;
	}
	.zhuyi
	{
		padding:0 10px 10px 10px;
	}
	h2
	{
		margin-top:30px;
		margin-bottom:5px;
	}
	.evaluation_desc_content
	{
	color:#434343;
	}
	.evaluation_title
	{
	color:#159a41;
	}
	.zhuyi ul li
	{
	margin-bottom:10px;
	}
</style>
<div class="am-g start_evaluation_fu">
	<div class="am-g">
		<div class="start_evaluation">
	<h1 class="am-text-center evaluation_title"><?php echo isset($last_evaluation_log['UserEvaluationLog'])?'您已答过该评测':"您好，欢迎参加<span>".$evaluation_data['Evaluation']['name']."</span>"; ?></h1>
	<div class="evaluation_desc">
		<h2>试卷说明</h2>
		<div class="evaluation_desc_content"><?php echo $evaluation_data['Evaluation']['description']; ?>&nbsp;</div>
	</div>
	<div class="evaluation_type">
		<h2>试卷结构</h2>
	<div class="evaluation_desc_content">
	<?php //pr($evaluation_rule_list); ?>
		<?php 
			     foreach($evaluation_rule_list as $k=>$v){ ?>
			<div><span class="dian">●</span>
				<span><?php echo $v['Evaluation']; echo isset($information_data['question_type'][$v['EvaluationRule']['question_type']])?$information_data['question_type'][$v['EvaluationRule']['question_type']]:$v['EvaluationRule']['question_type']; ?> :<?php echo $v['EvaluationRule']['proportion'].'道题'?></span>
			</div>
		<?php }?>
	</div>
	<div class="evaluation_note">
		<h2>注意事项</h2>
		<div class="zhuyi" style="border:none;background:none;">
			<ul class="am-list">
				<li ><span class="dian">●&nbsp;</span>答题过程中系统自动计时，到时自动交卷</li>
				<li class='danger'><span class="dian_2">●&nbsp;</span>考试前请关闭其他浏览器窗口，关闭可能弹窗的应用如QQ、屏保等，考试中不要切换到考试窗口之外的区域</li>
					<li><span class="dian">●&nbsp;</span>如果答题过程中因电源、网络故障等造成终端，请推出并在几分钟之内再次按照相同的步骤进入考试，从中断处继续答题</li>
			<li><span class="dian">●&nbsp;</span>Sogou、360浏览器请使用极速模式，不能使用兼容模式</li>
			</ul>
		</div>
	</div>
	<div class="am-g am-text-center" style="padding:10px 0 80px 0;">
		<?php
			if(isset($evaluation_condition)&&!empty($evaluation_condition)){
		?>
			<textarea class='am-hide'><?php echo json_encode($evaluation_condition); ?></textarea>
		<?php } ?>
		<a href="javascript:void(0);" onclick="return to_examination(this,<?php echo $evaluation_data['Evaluation']['id']; ?>)" class='am-btn am-radius am-btn-secondary'><?php echo isset($last_evaluation_log['UserEvaluationLog'])?'我知道了,重新作答':"我知道了"; ?></a>
	</div>
</div>
	</div>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="evaluation_condition">
  <div class="am-modal-dialog">
    <div class="am-modal-hd"><span>&nbsp;</span>
      <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    </div>
    <div class="am-modal-bd">
      	
    </div>
  </div>
</div>
<style type='text/css'>
.start_evaluation h2:after{padding-left:5px;content:":";}
.evaluation_title{font-weight:600;}
.evaluation_desc_content,.evaluation_rule_content,.evaluation_note_content{border:1px solid #dcdcdc;background:#fff;border-radius:3px;padding:10px 10px;margin:15px auto;color:#777777;}
.evaluation_rule_content>ul,.evaluation_note_content>ul{margin-top:10px;}
.start_evaluation ul li{border:none;}
.start_evaluation ul li em{color: #000;display: inline-block;font-size: 16px;text-align: center;text-decoration: none;padding-right:5px;}
.start_evaluation ul li.danger,.start_evaluation ul li.danger em{color:#efb70a;}
#evaluation_condition .am-modal-hd span{font-size:1.5rem;}
#evaluation_condition .am-modal-bd{font-size:2rem;}
</style>
<script type='text/javascript'>
function to_examination(evaluation_link,evaluation_id){
	var evaluation_condition_txt=$(evaluation_link).parent().find('textarea').val();
	if(typeof(evaluation_condition_txt)=="undefined"||evaluation_condition_txt==''){
		var timestamp = new Date().getTime(); //设定当前时间
		window.location.href=web_base+"/evaluations/examination/"+evaluation_id+'?timestamp='+timestamp;
	}
	var evaluation_condition=JSON.parse(evaluation_condition_txt);
	 
	var evaluation_condition_title="您还没有满足以下条件:";
	var evaluation_condition_message="";
	if(evaluation_condition.type=='ability_level'){
		evaluation_condition_message="未达到";
		$.each(evaluation_condition.data,function(index,item){
			evaluation_condition_message+="<span>"+item+'</span>';
		});
	}else if(evaluation_condition.type=='cycle'){
		evaluation_condition_message="还需要等待"+evaluation_condition.data+'天后才能进行此次评测';
	}else if(evaluation_condition.type=='parent_evaluation'){
		evaluation_condition_message="未完成以下评测";
		$.each(evaluation_condition.data,function(index,item){
			evaluation_condition_message+="<span><a href='"+web_base+"/evaluations/view/"+index+"'>"+item+'</a></span>';
		});
	}
	$("#evaluation_condition div.am-modal-hd span").html(evaluation_condition_title);
	$("#evaluation_condition div.am-modal-bd").html(evaluation_condition_message);
	$("#evaluation_condition").modal('open');
	return false;
}
</script>