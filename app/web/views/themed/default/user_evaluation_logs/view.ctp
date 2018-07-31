<style type='text/css'>
.contain-all{max-width:1100px;margin:0px auto;padding:0px 1rem;}
.ceshi_fu{width:100%;margin:0 auto;}
.ceshi{padding:20px 0 25px 12px;}
@media screen and (max-width: 640px){
body .youxiang{margin-top:5px;}
.manfen{font-size:12px;}
.shuxian_2+span{font-size:12px;}
.evaluation_question .evaluation_question_list{padding-left:10px;}
.am-g-fixed .ceshi{font-size:12px;}	
.user_data .youxiang{font-size:14px;}
.evaluation_question_list .font_15{font-size:14px;}
.shuxian{font-size:12px;}
body .evaluation_rule_content{padding-left:0;}
.evaluation_rule_content .evaluation_time{font-size:12px;}
.evaluation_question_list .examination_question{font-size:12px;}
.evaluation_question_list .answer_info{font-size:12px;}
}
 .ceshi{padding:15px 0 15px 10px;font-size:14px;}
.ceshi>div{padding-bottom:5px;}
.evaluation_title{text-align:center;font-size:25px;font-weight:600;color:#424242;}
.user_avatar{text-align:center;}
.evaluation_log_detail em{color:#ededed;padding:0px 10px;}
.examination_question_desc span.am-icon-check{color:#36bd9d;}
.examination_question_desc span.am-icon-close{color:#d94553;}
.examination_option_list .am-ucheck-icons{color:#aab2bd;}
.am-radio.am-success .am-ucheck-radio:checked+.am-ucheck-icons{color:#aab2bd;}
	/*去掉头部导航*/
.toubu_daohang
{
	display:none;
}
.evaluation_log_detail
{
	padding:30px 0 0 0;
}
.evaluation_log_detail h3
{
    border-left: 4px solid #4a89dc;
    padding: 5px 0 5px 5px;
}
.user_avatar
{
	font-size:13px;
	padding-right:10px;
}
.user_data
{
	padding:20px 0 20px 10px;
}
.evaluation_time
{
	display:inline-block;
    font-size: 14px;
        color: #009ee7;
        padding: 20px 0 25px 0;
}
.jineng_class
{
	padding:10px 0;
	border-bottom:1px solid #f4f8fb;
}
.am-list>li
{
	margin-bottom:inherit;
	padding-bottom:10px;
	border:none;
	border-bottom: 1px dashed #ccc;
}
.examination_question_desc
{
	background:#ecfdeb;
	border-radius: 3px;
	padding:5px 20px 5px 15px;
}
.jineng_span
{
	background:#8cadca;
	color:#fff;
	font-size:12px;
	padding:3px 10px;
	border-radius: 3px;
}
.am-ucheck-icons
{
	font-size:13px;
	line-height: 24px;
}
.youxiang
{
	color:#333;font-size:16px;margin-top:10px;
	    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    padding-left:10px;
}
.answer_info
{
	font-size:14px;
	padding-left:15px;
}
.answer_info>div:first-child{ 
border-left: 3px solid #afc6da;
    padding-left: 5px;margin-bottom:8px;}
    .answer_info>div:last-child{ 
border-left: 3px solid #37bc9b;
    padding-left: 5px;}
.am-checkbox, .am-radio
{
	margin-top:0;
	margin-bottom:0;
}
label
{
	margin-bottom:0;
}
.examination_option_list
{
	margin:10px 0 10px 15px;
}
.dian
{
	padding-right:5px;color:#8eadca;
}
.evaluation_log_detail .touxiang_img
{
width:60px;
height:60px;
}
.fensu_1
{
color:#e9573f;font-size:28px;padding-right:5px;
}
.panjuan_2{color:#e9573f;;font-size:14px;
}
.panjuan
{
color:#999;font-size:16px;
}
.timu_table{margin-bottom:10px;border-bottom:1px solid #f4f8fb;border-radius:3px;}
.evaluation_rule_content>img{margin-left:10px;}
.evaluation_rule_content{padding-left:10px;}
.timu_table_1{background:#dae6f2;padding:8px 0;font-size:12px;}
.pl_20{padding-left:20px;}
.pr_20{padding-right:20px;}
.timu_table_zi{font-size:12px;background:#fcfcfc;}
.timu_table_zi>div:first-child{background:#f4f8fb;padding:8px 0 8px 20px;font-size:12px;}
.yanse_1{color:#e9573f;padding:0 3px;}
.qingkuang{padding:20px 0 25px 12px;font-size:16px;}
.font_15{font-size:15px;}
.manfen{color:#999;padding-left:20px;}
.examination_question{padding:10px 0 10px 15px;font-size:14px;}
.span_fu{font-size:12px;color:#434343;}
.am-g-fixed .evaluation_log_detail_fu{width:95%;margin:0 auto;}
.shuxian{color:#ccc;padding:0 5px;}
.evaluation_question{margin-top:15px;}
.shuxian_2{color:#ccc;}
.color_1{color:#999;}
.evaluation_question_list{
padding-left:25px;}
.kongbai_1{height:30px;}
#evaluation_echart{min-height:330px;}
</style>
<script src="<?php echo $webroot.'plugins/echarts/dist/echarts.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<div class="contain-all">
<div class="am-g">
	<div class="am-g evaluation_log_detail_fu">
		<div class='evaluation_log_detail'>
	<div class='evaluation_title'><?php echo $evaluation_data['Evaluation']['name']; ?></div>
	<div class='am-g user_data'>
		<div class='am-fl user_avatar'>
			<img class="touxiang_img"  src="<?php echo $_SESSION['User']['User']['img01']!=''?$_SESSION['User']['User']['img01']:'/theme/default/img/no_head.png'; ?>"/>
		</div>
		<div class='am-u-lg-8 am-u-sm-9 am-u-md-8 youxiang' style="margin-top:0;line-height:1.3;padding-left:0;">
			<div><?php echo $_SESSION['User']['User']['name']; ?></div>
			<div class="am-u-sm-12" style="font-size:14px;padding-left:0;"><?php echo $ld['email']; ?>:<?php echo $_SESSION['User']['User']['email']; ?></div>
			<div class="am-u-sm-12" style="padding-left:0;"><?php echo $ld['mobile']; ?>:<?php echo $_SESSION['User']['User']['mobile']; ?></div>
		</div>
		<div class='am-u-lg-3 am-u-sm-12 am-u-md-2 am-text-right panjuan'>
			<span>(已判卷) <span class="fensu_1"><?php echo $evaluation_log_data['UserEvaluationLog']['score']; ?></span ><span class="panjuan_2">分</span></span>
		</div>
	</div>
	<div id='evaluation_echart'><textarea class='am-hide'><?php echo isset($evaluation_question_type_groups)?json_encode($evaluation_question_type_groups):''; ?></textarea></div>
	<div class='evaluation_rule'>
		<h3>全面概括</h3>
		<div class='evaluation_rule_content am-g'>
		<img class="am-hide-sm-only" src="/theme/default/img/zzz.png" style="width:18px;height:17px;" />
			<div class='evaluation_time'>考试时间&nbsp;<?php echo $evaluation_log_data['UserEvaluationLog']['start_time']; ?>&nbsp;至&nbsp;<?php echo $evaluation_log_data['UserEvaluationLog']['submit_time']; ?></div>
			<?php if(isset($evaluation_rule_list)&&!empty($evaluation_rule_list)){foreach($evaluation_rule_list as $v){ ?>
			<div class='am-g timu_table am-hide-sm-only'>
				<div class="am-g timu_table_1">
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-center pl_20 occupy"><?php echo isset($v['Evaluation'])?$v['Evaluation']:'-'; ?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-center">题型</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-center">题目数</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-center">错题数</div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center pr_20">得分</div>
					<div class='am-cf'></div>
				</div>
				<div class="am-g timu_table_zi" >
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
						<div class="am-g"><span class="dian">●</span>题目<span class="yanse_1" ><?php echo $v['EvaluationRule']['proportion']; ?></span>题</div>
						<div class="am-g"><span class="dian">●</span>总分<span class="yanse_1" ><?php echo $v['EvaluationRule']['score'];
						?></span>分</div>
						<div class="am-g"><span class="dian">●</span>得分<span class="yanse_1"><?php $success_total=$v['EvaluationRule']['proportion']-(isset($evaluation_question_error_data[$v['EvaluationRule']['child_evaluation_code']][$v['EvaluationRule']['question_type']])?$evaluation_question_error_data[$v['EvaluationRule']['child_evaluation_code']][$v['EvaluationRule']['question_type']]:0);
						echo intval($success_total/$v['EvaluationRule']['proportion']*$v['EvaluationRule']['score']);
					?></span>分</div>
					</div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-center jineng_class"><?php echo isset($information_data['question_type'][$v['EvaluationRule']['question_type']])?$information_data['question_type'][$v['EvaluationRule']['question_type']]:$v['EvaluationRule']['question_type']; ?></div>
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-center jineng_class"><?php echo $v['EvaluationRule']['proportion']; ?></div> 
					<div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-center jineng_class"><?php echo isset($evaluation_question_error_data[$v['EvaluationRule']['child_evaluation_code']][$v['EvaluationRule']['question_type']])?$evaluation_question_error_data[$v['EvaluationRule']['child_evaluation_code']][$v['EvaluationRule']['question_type']]:0; ?></div>
					<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center jineng_class pr_20" style="padding-right:0.6rem;"><?php
							$success_total=$v['EvaluationRule']['proportion']-(isset($evaluation_question_error_data[$v['EvaluationRule']['child_evaluation_code']][$v['EvaluationRule']['question_type']])?$evaluation_question_error_data[$v['EvaluationRule']['child_evaluation_code']][$v['EvaluationRule']['question_type']]:0);
						echo intval($success_total/$v['EvaluationRule']['proportion']*$v['EvaluationRule']['score']);
					?></div>
					<div class='am-cf'></div>
				</div>
			</div>
			<?php }} ?>
		</div>
	</div>
	<div class='evaluation_question'>
		<h3 >答题情况</h3>
		<div class='evaluation_question_list'>
			<?php if(isset($evaluation_questions)&&sizeof($evaluation_questions)>0){ ?>
			<ul class="am-list">
				<?php foreach($evaluation_questions as $k=>$v){
							$answer_check=false;
							if($v['EvaluationQuestion']['right_answer']==$v['UserEvaluationLogDetail']['answer']){
								$answer_check=true;
							}
							$rule_score=isset($evaluation_rule_score[$v['EvaluationQuestion']['evaluation_code']][$v['EvaluationQuestion']['question_type']])?$evaluation_rule_score[$v['EvaluationQuestion']['evaluation_code']][$v['EvaluationQuestion']['question_type']]:1;
				?>
				<li class="am-list_li">
					<div class="examination_question_desc">
						<div class="am-u-lg-9 am-u-md-6 am-u-sm-12 am-text-left font_15" style="padding-right:0;padding-left:12px;">
							<span class='question_type'><?php echo $k+1; ?>&nbsp;[<?php echo isset($information_data['question_type'][$v['EvaluationQuestion']['question_type']])?$information_data['question_type'][$v['EvaluationQuestion']['question_type']]:$v['EvaluationQuestion']['question_type']; ?>]&nbsp;&nbsp;</span>
							<span class="jineng_span"><?php echo isset($evaluation_infos[$v['EvaluationQuestion']['evaluation_code']])?$evaluation_infos[$v['EvaluationQuestion']['evaluation_code']]:$v['EvaluationQuestion']['evaluation_code']; ?>
							</span>
							<span class="am-fr <?php echo $answer_check?'am-icon-check':'am-icon-close'; ?> duicuo" id="examination_question_desc_span"></span>
						</div>
						<div class="kongbai_1 am-show-sm-only"></div>
						<div class="am-u-lg-3 am-u-md-6 am-u-sm-12 am-text-left" style="padding-right:0;padding-left:1rem;">
							<div class="shuxian_2 am-hide-sm-only am-u-md-4 am-u-lg-1"><span style="margin-bottom:0.2rem;">|</span></div>
							<span>得分：<?php echo $answer_check?$rule_score:0; ?></span>
							<span class="manfen am-hide" style="padding-left:1rem;">满分：<?php echo $rule_score; ?></span>
							<div class="am-cf"></div>
						</div>
						
						<div class="am-cf"></div>
					</div>
					<div class="examination_question" style="padding-left:2.3rem;"><?php echo htmlspecialchars($v['EvaluationQuestion']['name']); ?></div>
					<div class="examination_option_list" style="">
						<?php
							$evaluation_answer=explode(',',$v['UserEvaluationLogDetail']['answer']);
							 $evaluation_options=isset($evaluation_option_datas[$v['EvaluationQuestion']['code']])&&$evaluation_data['Evaluation']['show_right_answer']=='1'?$evaluation_option_datas[$v['EvaluationQuestion']['code']]:array();
							$question_type=$v['EvaluationQuestion']['question_type'];
							foreach($evaluation_options as $vv){
						 ?>
						 <div class='examination_option'>
						 	 <div class="am-u-lg-10 am-u-md-10 am-u-sm-10"><?php if($question_type){ ?>
						 	 	<label class="am-radio am-success"><input type='checkbox' name="data[UserEvaluationLogDetail][<?php echo $v['EvaluationQuestion']['code']; ?>][answer][]" value="<?php echo $vv['name']; ?>" <?php echo in_array($vv['name'],$evaluation_answer)?'checked':''; ?> data-am-ucheck>
								<span class="span_fu" ><?php echo $vv['name']; ?>.&nbsp;&nbsp;</span>
								<span class="span_fu" ><?php echo $vv['description']; ?></span>
						 	 	</label>
						 	 	<?php }else{ ?>
						 	 	<label class="am-radio am-success"><input type='radio' name="data[UserEvaluationLogDetail][<?php echo $v['EvaluationQuestion']['code']; ?>][answer]" value="<?php echo $vv['name']; ?>" <?php echo in_array($vv['name'],$evaluation_answer)?'checked':''; ?> data-am-ucheck>
								<span class="span_fu"><?php echo $vv['name']; ?>.&nbsp;&nbsp;</span>
								<span class="span_fu"><?php echo htmlspecialchars($vv['description']); ?></span>
						 	 	</label>
						 	 	<?php }?>
						 	 </div>
						 	 <div class="am-cf"></div>
						 </div>
						 <?php } ?>
					</div>
					<div class="answer_info" style="padding-left:2.3rem;">
				 		 <div class='user_answer'>考生答案：<div class="<?php echo isset($v['UserEvaluationLogDetail']['answer'])&&!empty($v['UserEvaluationLogDetail']['answer'])?'&nbsp;':'color_1'; ?>"><?php echo isset($v['UserEvaluationLogDetail']['answer'])&&!empty($v['UserEvaluationLogDetail']['answer'])?$v['UserEvaluationLogDetail']['answer']:'无'; ?></div></div>
				 		 <?php if($evaluation_data['Evaluation']['show_right_answer']=='1'){ ?>
				 		 <div class='user_answer'>正确答案：<div><?php echo $v['EvaluationQuestion']['right_answer']; ?></div></div>
				 		 <?php } ?>
				 		 <?php if(isset($v['EvaluationQuestion']['analyze'])&&trim($v['EvaluationQuestion']['analyze'])!=''){ ?>
				 		 <div class='user_answer' style="margin-top:15px;">题目解析：<?php echo $v['EvaluationQuestion']['analyze']; ?></div>
				 		 <?php } ?>
					 </div>

				</li>
				<?php } ?>
			</ul>
			<?php } ?>
		</div>
	</div>
</div>

	</div>
	<div class="am-cf"></div>
</div>


<div class="ceshi_fu" style="width:100%;">
	<h2 style="border-left: 4px solid #4a89dc;padding: 5px 0 5px 5px;">日志</h2>
	<div class="ceshi">
		<div>IP地址<span class="pl_20" ><?php echo $evaluation_log_data['UserEvaluationLog']['ipaddress']?></span></div>
		<div>操作系统<span class="pl_20" ><?php echo $evaluation_log_data['UserEvaluationLog']['system']?></span></div>
		<div>浏览器<span class="pl_20" ><?php echo $evaluation_log_data['UserEvaluationLog']['browser']?></span></div>
		<div><span class="pr_20"><?php echo $evaluation_log_data['UserEvaluationLog']['start_time']?></span>开始</div>
		<div><span class="pr_20"><?php echo $evaluation_log_data['UserEvaluationLog']['submit_time']?></span>结束</div>
	</div>
</div>
</div>
<script>
$(function(){
	if($('.am-list').find('#examination_question_desc_span').hasClass()){
		$(this).find('.examination_question_desc').css('background','#fff3f3');
	}
	$('.evaluation_question_list ul li').find('.duicuo').not('.am-icon-check').parents('.examination_question_desc').css('background','#fff3f3');
});

var echart_json_txt=$("#evaluation_echart textarea:eq(0)").val().trim();
if(echart_json_txt!=''){
	var echart_json_data=JSON.parse(echart_json_txt);
	var echart_series_data=[];
	var echart_legend=[];
	
	$.each(echart_json_data,function(index,item){
		echart_series_data.push({
			name:'正确'+item['question_type_name'],
			value:item['right']
		});
		echart_legend.push('正确'+item['question_type_name']);
		echart_series_data.push({
			name:'错误'+item['question_type_name'],
			value:item['error']
		});
		echart_legend.push('错误'+item['question_type_name']);
	});
	var echarts_option= {
	    title : {
	        text: '',
	        subtext: '',
	        x:'center'
	    },
	    tooltip : {
	        trigger: 'item',
	        formatter: "{b} : {c} ({d}%)"
	    },
	    legend: {
	        orient: 'vertical',
	        left: 'left',
	        data: echart_legend
	    },
	    series : [
	        {
	            name: '',
	            type: 'pie',
	            radius : '55%',
	            center: ['50%', '60%'],
	            data:echart_series_data,
	            itemStyle: {
	                emphasis: {
	                    shadowBlur: 10,
	                    shadowOffsetX: 0,
	                    shadowColor: 'rgba(0, 0, 0, 0.5)'
	                }
	            }
	        }
	    ]
	};
	load_echarts();
	
	function load_echarts(){
		require.config({
			paths: {
				echarts: '/plugins/echarts/dist'
			}
		});
		require(
			[
				'echarts',
				'echarts/theme/default',
				'echarts/chart/pie'
			],
			function (ec,theme) {
				var myChart = ec.init(document.getElementById('evaluation_echart'),theme);
				myChart.resize();
				// 指定图表的配置项和数据
				option = echarts_option;
				myChart.setOption(option,true); 
				window.onresize = function (){
					myChart.resize();
				}
			}
		);
	}
}

for(var i = 0;i<$(".occupy").length;i++){
	if($(".occupy").eq(i).html() == ''){
		$(".occupy").eq(i).html('&nbsp;')
	}
}
</script>