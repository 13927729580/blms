<div class="am-g toubu_ceshi am-text-center">
	<h1 >答题以结束，感谢你的参加</h1>
	<h3 >感谢参加本次<?php echo $evaluation_data['Evaluation']['name'] ?>！可返回&nbsp;<a class="shouye" href="<?php echo $html->url('/evaluations/index'); ?>">首页</a>&nbsp;做其他考试真题及练习题！</h3>
</div>
<div class="triangle-down"></div>
<div class="am-g pinc_jieshu">
	<div class="am-u-sm-12 am-u-lg-12 am-u-md-12">
		<div class="am-hide-sm-only am-u-lg-2 am-u-md-2">&nbsp;</div>
		<div class="am-u-lg-8 am-u-sm-12 am-u-md-8 score_result">
			<div class="evaluation_data">
				<?php echo $evaluation_data['Evaluation']['name'] ?>
			</div>
			<div class="user_data">
				<div class='user_avatar'><img class="am-circle" src="<?php echo $_SESSION['User']['User']['img01']!=''?$_SESSION['User']['User']['img01']:'/theme/default/img/no_head.png'; ?>" width="15%"/></div>
				<div class='user_name'><?php echo $_SESSION['User']['User']['name']; ?></div>
			</div>
			<div class="evaluation_log_data">
				
				<div class='evaluation_score'>
					<?php if(isset($evaluation_log_data['UserEvaluationLog'])&&$evaluation_log_data['UserEvaluationLog']['status']=='1'){ ?>
					<span class="evaluation_score_span"><?php echo $evaluation_score; ?>分</span><span style="color:#149842;"> (100)</span>
					<?php }else{ ?>
					<span>等待阅卷</span>
					<?php } ?>
				</div>
				<div class="am-u-lg-3 am-u-md-1 am-hide-sm-only">&nbsp;</div>
				<div class="am-u-lg-6 am-u-sm-12 am-u-md-10 am-text-center" style="padding-top:15px;">
	<div class="am-u-sm-12 am-u-md-4 am-u-lg-4 anniu">
						<a href="<?php echo $html->url('/evaluations/start_evaluation/'.$evaluation_log_data['UserEvaluationLog']['evaluation_id']); ?>" class='am-btn am-radius am-btn-warning congxin'>重新作答</a></div>
					
					<div class="am-u-sm-12 am-u-md-4 am-u-lg-4 anniu"><?php if(isset($evaluation_log_data['UserEvaluationLog'])&&$evaluation_log_data['UserEvaluationLog']['status']=='1'){ ?><a href="<?php echo $html->url('/user_evaluation_logs/view/'.$evaluation_log_data['UserEvaluationLog']['id']); ?>" class='am-btn am-radius am-btn-warning' >查看对错</a><?php } ?>&nbsp;</div>
					
					<div class="am-u-sm-12 am-u-md-4 am-u-lg-4 anniu"><a href="/contacts/" class='am-btn am-radius am-btn-success'>在线反馈</a></div>
					<div class="am-cf"></div>
				</div>
				<div class="am-u-lg-3 am-u-md-1 am-hide-sm-only">&nbsp;</div>
				<div class="am-cf"></div>
			</div>
		</div>
		<div class="am-hide-sm-only am-u-lg-2 am-u-md-2">&nbsp;</div>
		<div class="am-cf"></div>
	</div>
	<div class="am-cf"></div>
</div>
<style type='text/css'>
body.am-with-topbar-fixed-top{padding-top:0px;}
#amz-header,ol.am-breadcrumb,div.bottom_navigations,bottom_navigations,footer.am-footer{display:none!important;}
.toubu_ceshi h1{margin:0 0 10px 0;color:#f1f1ef;font-size:20px;}
.toubu_ceshi h3{color:#fff;letter-spacing: 2px;font-size:14px;font-weight:500;}
.am-g-fixed .pinc_jieshu{max-width:1200px;margin:0 auto;margin-bottom:200px;}
.shouye{color:#149941;}
.shouye:hover{color:#149941;}
.evaluation_log_data .congxin{background:#ff7674;border-color:#ff7674;}
.evaluation_log_data .anniu{padding-bottom:10px;}
.triangle-down{
width:0;
height:0;
border-left: 20px solid transparent;
border-right: 20px solid transparent;
border-top: 20px solid #514c49;
margin:0 auto;
}
.toubu_ceshi{background:#514c49;padding:10px 0;}
.score_result{margin-top:70px;text-align:center;border:1px solid #eeeeee;padding:20px 0 10px 0;box-shadow: 0px 3px 5px #ccc;}
.evaluation_data{font-size:25px;font-weight:600;color:#434343;padding-bottom:15px;}
.evaluation_score{margin:5px auto;}
.evaluation_score span{color:#414141;}
.user_name{color:#888888;padding-bottom:5px;font-size:14px;}
.am-btn-warning{background-color: #efb70a;border-color: #efb70a;}
.evaluation_score p{
	font-size:15px;
	font-weight: 600;
	color:#585858;
}
.evaluation_score_span{
	font-weight: 600;
}
.am-container, .am-g-fixed{max-width: inherit;}
@media only screen and (max-width: 640px){
	.am-g-fixed .pinc_jieshu{width:95%;}
	.pinc_jieshu .score_result{margin-top:30px;}
	body .am-g-fixed .pinc_jieshu{margin-bottom:100px;}
	body h1{font-size:16px;}
	body h3{font-size:12px;padding:0 10px;}
}
</style>
<script>
	$(function(){
		if(parseInt($('.evaluation_score_span').html())<=60)
		{
			//alert($('.evaluation_score_span').html());
			$('.evaluation_score').append("<p style='padding-top:20px;'>别哭，下次继续努力，加油！<p>");
		}
		
		if(parseInt($('.evaluation_score_span').html())<80&&parseInt($('.evaluation_score_span').html())>60)
		{
			$('.evaluation_score').append("<p>答得不错，再接再厉！</p>");
		}
		if(parseInt($('.evaluation_score_span').html())>=80)
		{
			$('.evaluation_score').append("<p>真棒！你就是天才！</p>");
		}
	});
</script>