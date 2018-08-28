<?php
//	pr($sm);
?>
<style>
	/*小屏*/
@media only screen and (max-width: 640px)
{
	.evaluation_rule_div{font-size:12px;}
	.xiangguan_pince li{}
}
	.evaluation_data
{
padding:0 0 20px 0;
}
.am-nav-tabs>li.am-active>a, .am-nav-tabs>li.am-active>a:focus, .am-nav-tabs>li.am-active>a:hover
{
	background:#fafafa;
}
/*选择之后的颜色*/
.am-nav-tabs>li.am-active>a, .am-nav-tabs>li.am-active>a:focus, .am-nav-tabs>li.am-active>a:hover
{
	color:#12983f;
}
a
{
	color:#000;
}

.zhangjie_ul>li>a
{
	padding:10px 1em 15px 15px;
}
.am-nav-tabs>li.am-active>a, .am-nav-tabs>li.am-active>a:focus, .am-nav-tabs>li.am-active>a:hover
{
	border:none;
}
.am-tabs-bd
{
	border:none;
}
.am-tabs-bd .am-tab-panel
{
	padding:0 0;
}

.evaluation_data .am-btn
{
	font-size:14px;
	padding:10px 35px;
}
.zhangjie
{

	max-width:1200px;
	margin:0 auto;
	padding:20px 0;
	width:95%;
}
.zhangjie_ul
{
	font-size:20px;
}
#evaluation_desc
{
	padding:20px 0 0px 15px;	}
.evaluation_desc
{
	margin-bottom:20px;
	font-size:14px;
}
.am-btn-success
{
	background:#149842;
}
.am-table
{
	font-size:14px;
}
.zhangjie_div
{
padding-bottom:50px;
margin-bottom:50px;
border-bottom:2px solid #e6e6e6;
}
/*评论*/
#evaluation_comment
{
	padding:10px 0 0 15px;
}
.pingce_fu
{

	border-top:2px solid #e6e6e6;
	background:#fafafa;
}
.evaluation_rule
{
	font-size:14px;
}
.evaluation_rule_div div
{
	
	padding:5px 5px;
}
.evaluation_data>div
{
	color:#898989;
}
.am-btn.am-radius
{
	border-radius: 4px;
}
.zhangjie_ul
{
	border-bottom:1px solid #ccc;
}
.am-g-fixed .ceshi_start
{
max-width:1200px;margin:0 auto;width:95%;
}
h3
{
margin-bottom:10px;font-size:28px;font-weight:500;
}
.pince{border-right:1px solid #ccc;}
.ceshi_start .tishu{border-right:1px solid #ccc;padding-left:9%;}
.ceshi_start .zongfen{padding-left:9%;}
.ceshi_btn{margin-top:5px}
.jiegou{margin-bottom:15px;}
.evaluation_desc_title{font-size:16px;}
#evaluation_condition .am-modal-hd span{font-size:1.5rem;}
#evaluation_condition .am-modal-bd{font-size:2rem;}
</style>
<div class="am-g ceshi_start">
	<div class="am-u-sm-12 am-u-md-12 am-u-lg-12 ceshi_1" style="padding-left:2%;">
		<div class='evaluation_data'>
			<h3><?php echo isset($sm['evaluation_data'])?$sm['evaluation_data']['name']:''; ?></h3>
			<div class="am-g">
				<div class="am-u-sm-12 am-u-md-6 am-u-lg-6" style="font-size:14px;">
					<div class="am-u-sm-3 am-u-lg-3 am-u-md-3 am-text-left pince" >评测时长<br ><?php echo isset($sm['evaluation_data'])?$sm['evaluation_data']['evaluation_time'].'分钟':'-'; ?></div>
					<div class="am-u-sm-4 am-u-lg-4 am-u-md-4 am-text-left tishu" >题目数<br ><?php echo isset($sm['question_total'])?$sm['question_total']:'0'; ?>道题</div>
					<div class="am-u-sm-4 am-u-lg-4 am-u-md-4 am-text-left zongfen" >试卷总分<br ><?php echo isset($sm['score_total'])?$sm['score_total']:'0'; ?>分</div>
					<div class="am-cf"></div>
				</div>
				<div class="am-hide-md-only am-hide-lg-only am-u-sm-12">&nbsp;</div>
				<div class="am-u-sm-12 am-u-lg-6 am-u-md-6 am-text-center">
					<?php 	if(isset($evaluation_condition)&&!empty($evaluation_condition)){ ?>
						<textarea class='am-hide'><?php echo json_encode($evaluation_condition); ?></textarea>
					<?php 	}
							if(!isset($evaluation_view_action)||$evaluation_view_action=='0'){
					?>
					<?php if(isset($need_buy)&&$need_buy){ ?>
						<?php if(isset($_SESSION['User'])){ ?>
						<button class="am-btn am-radius am-btn-danger" onclick="virtual_purchase_pay('evaluation','<?php echo $sm['evaluation_data']['id'] ?>')">购买评测 ￥<?php echo $sm['evaluation_data']['price']; ?></button>
						<?php }else{ ?>
						<button class="am-btn am-radius am-btn-danger" onclick="user_login()">购买课程 ￥<?php echo $sm['evaluation_data']['price']; ?></button>
						<?php } ?>
					<?php }else{ ?>
					<a class="am-btn am-radius am-btn-success ceshi_btn" href="<?php echo $html->url('/evaluations/start_evaluation/'.(isset($sm['evaluation_data'])?$sm['evaluation_data']['id']:0)); ?>" onclick="return check_evaluation(this);" <?php if($sm['question_total']=='0'){echo 'disabled="disabled"';}else{echo '';}?>>去测试</a>
					<?php } ?>
					<?php }else{ ?>
						<button class="am-btn am-radius am-btn-default" disabled>您没有权限查看此评测</button>
					<?php 	} ?>
				</div>
				<div class="am-cf"></div>
			</div>
		</div>
	</div>
</div>
<div class="am-g pingce_fu">
<div class="zhangjie">
	<div class="am-u-sm-12 am-u-md-12 am-u-lg-12 zhangjie_div">
		<div class="evaluation_other">
			<div class="am-tabs" data-am-tabs="{noSwipe: 1}">
				<ul class="am-tabs-nav am-nav am-nav-tabs zhangjie_ul">
					<li class="am-active"><a href="#evaluation_desc" style="margin-right:0;">章节</a></li>
					<!--<li><a href="#evaluation_comment">评论</a></li>-->
				</ul>
				<div class="am-tabs-bd">
					<div class="am-tab-panel am-fade am-in am-active" id="evaluation_desc">
						<h3 class='evaluation_desc_title'>概述</h3>
						<div class="am-g evaluation_desc">
							<?php echo isset($sm['evaluation_data'])?$sm['evaluation_data']['description']:'';  ?>
						</div>
						<h3 class='evaluation_desc_title jiegou' >试卷结构</h3>
						<div class="am-g evaluation_rule">
							<div class='am-g evaluation_rule_div' style="background:#dcdcdc;">
									<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">技能</div>
									<div class="am-u-lg-2 am-u-sm-2 am-u-md-2">题型</div>
									<div class="am-u-lg-2 am-u-sm-2 am-u-md-2 am-text-center">题目数</div>
									<div class="am-u-lg-2 am-u-sm-2 am-u-md-2 am-text-right">分值</div>
								</div>
								<?php if(isset($sm['evaluation_rule'])&&!empty($sm['evaluation_rule'])){foreach($sm['evaluation_rule'] as $v){ ?>
									<div class="am-g evaluation_rule_div" style="background:#f3f3f3;">
										<div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><?php echo isset($v['Evaluation'])?$v['Evaluation']:'-'; ?></div>
										<div class="am-u-lg-2 am-u-sm-2 am-u-md-2"><?php echo isset($information_data['question_type'][$v['EvaluationRule']['question_type']])?$information_data['question_type'][$v['EvaluationRule']['question_type']]:$v['EvaluationRule']['question_type']; ?></div>
										<div class="am-u-lg-2 am-u-sm-2 am-u-md-2 am-text-center"><?php echo $v['EvaluationRule']['proportion']; ?></div>
										<div class="am-u-lg-2 am-u-sm-2 am-u-md-2 am-text-right"><?php echo round($v['EvaluationRule']['score']); ?></div>
									</div>
								<?php }} ?>
							<div class="am-cf"></div>
						</div>
				    </div>
				    <div class="am-tab-panel am-fade" id="evaluation_comment">
				      	<h3>评论</h3>
				    </div>
				</div>
			</div>
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

<?php
	$wechat_imgUrl=trim($sm['evaluation_data']['img']);
	if(strstr($wechat_imgUrl,$server_host)<0&&strstr($wechat_imgUrl,'http')<0){
		$wechat_imgUrl=$server_host.(str_replace($server_host,'',$sm['evaluation_data']['img']));
	}
?>
<script type='text/javascript'>
var wechat_shareTitle="<?php echo $sm['evaluation_data']['name']; ?>";
var wechat_descContent="<?php echo $svshow->emptyreplace($sm['evaluation_data']['description']); ?>";
var wechat_lineLink=location.href.split('#')[0];
<?php
	if(trim($wechat_imgUrl)!=""&&$svshow->imgfilehave($wechat_imgUrl)){  ?>
		var wechat_imgUrl="<?php echo $wechat_imgUrl; ?>";
<?php } ?>

function check_evaluation(evaluation_link){
	var evaluation_condition_txt=$(evaluation_link).parent().find('textarea').val();
	if(typeof(evaluation_condition_txt)=="undefined"||evaluation_condition_txt==''){return true;}
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

function user_login(){
	alert('请先登录再购买');
}
</script>