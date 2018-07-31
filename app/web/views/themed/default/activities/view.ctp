<style>
.am-breadcrumb.am-hide-md-down.am-color{padding-left: 50px;}
h4{font-size: 20px;}
.scrollspy-nav {
	background:#fff;
	top: 20px;
	z-index: 100;
	width: 100%;
	padding: 0 10px;
  }
  .scrollspy-nav ul {
    margin: 0;
    padding: 0;
  }

  .scrollspy-nav li {
    display: inline-block;
    list-style: none;
  }

  .scrollspy-nav a {
	color: #333;
	padding: 10px 20px;
	display: inline-block;
  }

  .scrollspy-nav a.am-active {
  	background:#0e90d2;
	color: #fff;
	font-weight: bold;
  }

  .am-panel {
    margin-top: 20px;
  }
  .guanlian_child:hover{border:none;box-shadow: 0px 0px 9px #aaa;color: #0e90d2}
</style>
<div style="max-width: 1200px;margin:0 auto;">
	<input type='hidden' id='activity_id' value="<?php echo $activities_info['Activity']['id']; ?>" />
	<?php if(isset($activity_publisher)&&!empty($activity_publisher)){ ?>
	<div style="border-bottom: 10px solid #eee;padding:20px;">
		<div class="am-u-lg-1 am-u-md-2 am-u-sm-2 am-text-center am-padding-0">
			<?php	echo $html->image($activity_publisher['logo']!=''?$activity_publisher['logo']:$configs['shop_logo'],array('style'=>'border-radius: 50%;height: 50px;width: 50px;')); ?>
		</div>
		<div style="display: inline-block;">
			<span><?php echo $activity_publisher['name']; ?></span><span style="background-color: #0e90d2;margin-left: 10px;display: inline-block;padding:2px 6px;border-radius: 5px;color: #fff;">主办方</span>
			<br>
			<span style="font-size: 12px;"><?php echo $activity_publisher['description']; ?></span>
		</div>
		<div class='am-cf'></div>
	</div>
	<?php } ?>
	<div style="padding:20px 18px;border-bottom: 10px solid #eee;">
		<div class="am-u-lg-5 am-u-md-5 am-u-sm-12" style="padding:0;height: 260px;margin-right: 10px;text-align: center;line-height: 260px;">
			<img src="<?php if($activities_info['Activity']['image']==''){echo '/theme/default/images/default.png';}else{echo $activities_info['Activity']['image'];} ?>" alt="" style="max-width: 100%;max-height: 100%;">
			<div class="am-cf"></div>
		</div>
		<div class="am-u-lg-6 am-u-md-6 am-u-sm-12" style="font-size: 15px;">
			<div style="margin-top: 15px;">
				<h4><?php echo $activities_info['Activity']['name'] ?></h4>
			</div>
			<div style="margin-top: 15px;">
				<span >地点：</span><?php echo $activities_info['Activity']['address'] ?>
			</div>
			<div style="margin-top: 15px;">
				<span>时间：</span><?php echo date("Y-m-d",strtotime($activities_info['Activity']['start_date'])).'~'.date("Y-m-d",strtotime($activities_info['Activity']['end_date'])).' '.$activities_info['Activity']['time_quantum']?>
				<?php if(date('Y-m-d',time())>=date("Y-m-d",strtotime($activities_info['Activity']['start_date']))){if(date('Y-m-d',time())<=date("Y-m-d",strtotime($activities_info['Activity']['end_date']))){
				?>
				<span style="background-color: #0099e9;margin-left: 10px;display: inline-block;padding:0 7px;border-radius: 5px;color:#fff;">进行中</span>
				<?php		}else{
				?>
				<span style="background-color:#ccc;margin-left: 10px;display: inline-block;padding:0 7px;border-radius: 5px;color:#fff;">已结束</span>
				<?php
					}}else{
				?>
				<span style="background-color: #0099e9;margin-left: 10px;display: inline-block;padding:0 7px;border-radius: 5px;color:#fff;">未开始</span>
				<?php
					} ?>
			</div>
			<div style="margin-top: 15px;">
				<span>价格：</span><span><?php echo '￥'.$activities_info['Activity']['price'] ?></span>
			</div>
			<div style="margin-top: 15px;">
				<?php if(isset($tap_list)&&sizeof($tap_list)>0){ ?>
				<?php foreach ($tap_list as $k => $v) { ?>
				<span style="background-color: #0e90d2;display: inline-block;padding:2px 6px;border-radius: 5px;font-size: 12px;margin-right: 5px;color: #fff;"><?php echo $v['ActivityTag']['tag_name']; ?></span>
				<?php } ?>
				<?php } ?>
			</div>
			<div style="padding:0;margin-top: 15px;">
				<?php if(isset($_SESSION['User'])&&!empty($_SESSION['User']['User']['id'])){ ?>
					<?php if(isset($max_activity_user)&&$max_activity_user){ ?>
							<span>当前活动参加人数已满</span>
					<?php }elseif(isset($pay_judge)&&$pay_judge!=''){ ?>
						<?php if($pay_judge['ActivityUser']['payment_status']==0){ ?>
						<div class="am-btn am-btn-primary am-btn-block" onclick="virtual_purchase_pay('activity',<?php echo $activities_info['Activity']['id'] ?>)">我要付钱</div>
						<?php }else{ ?>
						<div class="am-btn am-btn-primary am-btn-block" disabled>已报名</div>
						<?php } ?>
					<?php }else{ ?>
						<?php if(date('Y-m-d',time())<=date("Y-m-d",strtotime($activities_info['Activity']['end_date']))){ ?>
						<div class="am-btn am-btn-primary am-btn-block" onclick="sign_up(<?php echo $activities_info['Activity']['id'] ?>)">我要报名</div>
						<?php }else{ ?>
						<div class="am-btn am-btn-primary am-btn-block" disabled style="background-color:#ccc;border-color: #ccc;opacity: inherit;">活动结束</div>
						<?php } ?>
					<?php } ?>
				<?php }else{ ?>
					<div class="am-btn am-btn-primary am-btn-block" onclick="user_login()">我要报名</div>
				<?php } ?>
			</div>
		</div>
		<div class="am-cf"></div>
	</div>
	<nav id="activity_btn" class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 0}" data-am-sticky="{top:50}">
		<ul>
			<li><a href="#huodongxiangqing">活动详情</a></li>
			<li><a href="#baoming">已报名 <?php echo sizeof($activities_user_list); ?></a></li>
		</ul>
	</nav>
	<div id="huodongxiangqing" style="padding:18px;border-bottom: 10px solid #eee;">
		<div style="font-size: 16px;word-wrap:break-word"><?php echo $activities_info['Activity']['description'] ?></div>
	</div>
	<?php if($activities_info['Activity']['type']!=''){ ?>
	<div style="padding:18px;border-bottom: 10px solid #eee;">
		<div style="font-size: 16px;margin-bottom: 10px;">
			<?php if($activities_info['Activity']['type']=='C'){echo '课程关联';}if($activities_info['Activity']['type']=='E'){echo '评测关联';} ?>
		</div>
		<?php if(isset($course_list)&&sizeof($course_list)>0){ ?>
		<?php foreach ($course_list as $k => $v) { ?>
		<div class="am-u-lg-3 am-u-sm-4 am-u-sm-12 guanlian_child" style="padding:10px;background-color: #eee;">
			<div class="am-u-lg-12 am-u-md-12 am-u-sm-12" style="padding:10px;">
				<div class="am-u-lg-12 am-u-md-12 am-u-sm-12" style="padding:0;text-align: center;height: 160px;line-height: 160px;" onclick="course_view('<?php echo $v['Course']['id']; ?>')">
					<img src="<?php if($v['Course']['img']==''){echo '/theme/default/images/default.png';}else{echo $v['Course']['img'];} ?>" style="cursor: pointer;max-width: 100%;max-height: 100%;">
				</div>
				<div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-text-center" style="margin-top: 10px;">
					<?php echo $v['Course']['name'] ?>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php } ?>
		<?php if(isset($evaluation_list)&&sizeof($evaluation_list)>0){ ?>
		<?php foreach ($evaluation_list as $k => $v) { ?>
		<div class="am-u-lg-3 am-u-sm-4 am-u-sm-12 guanlian_child" style="padding:10px;background-color: #eee;">
			<div class="am-u-lg-12 am-u-md-12 am-u-sm-12" style="padding:10px;">
				<div class="am-u-lg-12 am-u-md-12 am-u-sm-12" style="padding:0;text-align: center;height: 160px;line-height: 160px;" onclick="evaluation_view('<?php echo $v['Evaluation']['id']; ?>')">
					<img src="<?php if($v['Evaluation']['img']==''){echo '/theme/default/images/default.png';}else{echo $v['Evaluation']['img'];} ?>" style="cursor: pointer;max-width: 100%;max-height: 100%;">
				</div>
				<div class="am-u-lg-12 am-u-md-12 am-u-sm-12 am-text-center" style="margin-top: 10px;">
					<?php echo $v['Evaluation']['name'] ?>
				</div>
			</div>
		</div>
		<?php } ?>
		<?php } ?>
		<div class="am-cf"></div>
	</div>
	<?php } ?>
	<div id="baoming" style="padding:0 18px;font-size: 16px;">
		<div style="border-bottom: 1px solid #ccc;padding:10px 0;position: relative;">
			<span class="am-u-lg-8" style="padding:0">已报名（<?php echo sizeof($activities_user_list); ?>）<?php if(sizeof($activities_user_pay_list)>0){ ?>，其中<?php echo sizeof($activities_user_pay_list); ?>人待付钱<?php } ?></span>
			<?php if(sizeof($activities_user_list)>0){ ?>
			<a href="<?php echo $html->url('/activities/org_activity_user/'.$activities_info['Activity']['id'].'?viewonly=1'); ?>" style="cursor: pointer;position: absolute;right: 0;">更多报名></a>
			<?php } ?>
			<div class="am-cf"></div>
		</div>
		<div>
			<?php foreach ($activities_user_list as $k => $v) { ?>
			<div style="padding:10px;display: inline-block;text-align: center;">
				<img src="<?php if($v['User']['img01']==''){echo '/theme/default/images/default.png';}else{echo $v['User']['img01'];} ?>" title="<?php echo $v['User']['name']; ?>" style="border-radius: 50%;height: 50px;width: 50px;">
				<br>
				<?php 
					$activities_user_name = "";
					$str = trim($v['User']['name']);
					if(strlen($str) > 9){
						for($i = 0 ; $i<9 ; $i++){
							if(ord($str) > 127){
								$activities_user_name .= $str[$i] . $str[$i+1] . $str[$i+2];
								$i = $i + 2;
							}
							else{
								$activities_user_name .= $str[$i];
							}
						}
						$activities_user_name .= "...";
					}else{
						$activities_user_name = $v['User']['name'];
					}
				 ?>
				<span style="margin-top: 10px;display: inline-block;"><?php echo $activities_user_name; ?></span>
			</div>
			<?php } ?>
			<div class="am-cf"></div>
		</div>
	</div>
	<!-- <div id="activity_comment" class="am-cf"></div>-->
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
<script>
var wechat_shareTitle="<?php echo $activities_info['Activity']['name'] ?>";
var wechat_descContent="<?php echo $svshow->emptyreplace($activities_info['Activity']['description']); ?>";
var wechat_lineLink=location.href.split('#')[0];
<?php if(trim($activities_info['Activity']['image'])!=""&&$svshow->imgfilehave($server_host.(str_replace($server_host,'',$activities_info['Activity']['image'])))){  ?>
var wechat_imgUrl="<?php echo $server_host.(str_replace($server_host,'',$activities_info['Activity']['image'])); ?>";
<?php } ?>
function sign_up(id){
	window.location.href="/activities/activity_user_edit/"+id; 
}

function user_login(){
	seevia_alert('请先登录再报名');
}

var _width = $(window).width(); 
if(_width < 1024){
	$('#activity_btn').css('display','none');
}
window.onresize = function(){
    //获取浏览器宽度
    var _width = $(window).width(); 
    if(_width < 1024){
        $('#activity_btn').css('display','none');
    }else{
     	$('#activity_btn').css('display','');
    }
};

function course_view(id){
	window.location.href=web_base+'/courses/view/'+id;
}
function evaluation_view(id){
	window.location.href=web_base+'/evaluations/view/'+id;
}

activity_comment()
function activity_comment(){
	if(!document.getElementById('activity_comment'))return;
	var activity_id=document.getElementById('activity_id').value;
	$.ajax({ 
		url: web_base+"/activities/activity_comment/",
		dataType:"html",
		type:"POST",
		data:{'activity_id':activity_id},
		success: function(data){
			$('#activity_comment').html(data);
	    }
	});
}
</script>