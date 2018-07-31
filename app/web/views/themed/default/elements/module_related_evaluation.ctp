<style>
@media only screen and (max-width: 640px)
{
body .xiangguan_pince li{padding:10px 10px;}
}
h3
{
	color:#424242;
}
	.related_evaluation
	{
		max-width:1200px;
		margin:0 auto;
		width:95%;
	}
	.xiangguan_pince
	{
		background-color: #fafafa;
		padding-bottom:80px;
	}
	.xiangguan_pince li
	{
		background:#fff;
		border:1px solid #fff;
		padding:20px 20px;
		box-shadow: 0px 3px 5px #ccc;
		margin-right:10px;
	}
	.xiangguan_pince .shijian
	{
		font-size:12px;

		color:#878787;
	}
	.xiangguan_pince .jiejian
	{
		font-size:12px;
		padding-top:15px;
		color:#878787;
	}
	.pingce_zi
	{
		padding-top:20px;
	}
	.pingce_zi a
	{
		    padding: 5px 20px;
    font-size: 14px;
    border: 1px solid #149842;
    color: #149842;
    border-radius: 3px;
	}
</style>
<div class="am-g xiangguan_pince">
	<div class="am-u-sm-12 am-u-md-12 am-u-lg-12">
		<div class="related_evaluation">
			<h3 style="padding:20px 0;margin-bottom:0;">相关评测</h3>
			<ul class="am-avg-lg-6 am-avg-md-3 am-avg-sm-2">
				<?php if(is_array($sm)&&sizeof($sm)>0){foreach($sm as $v){ ?>

				<li class="pince_xiangguan" style="min-height: 250px;position: relative;">
					<div class="">
						<div style="font-size:14px;"><?php echo $v['Evaluation']['name'];?></div>
						<div class="shijian"><?php echo date("Y-m-d",strtotime($v['Evaluation']['created']));?></div>
						<div class="jiejian"><?php echo $v['Evaluation']['description'];?></div>
					</div>
		
					<div class="am-text-center pingce_zi"><a href="<?php echo $html->url('/evaluations/view/'.$v['Evaluation']['id']); ?>">去测试</a></div>
				</li>
				<?php }} ?>
			</ul>
		</div>
	</div>
</div>
<script>
var pince_xiangguan = document.querySelectorAll('.pince_xiangguan');
var pingce_zi = document.querySelectorAll('.pingce_zi');
for(var i =0;i<pince_xiangguan.length;i++){
	if(pince_xiangguan[i].offsetHeight == 250){
		var width_li = pince_xiangguan[i].offsetWidth;
		width_li = width_li - 40;
		pingce_zi[i].style = "position:absolute;bottom:20px;";
		pingce_zi[i].style.width =width_li+'px';
	}
}
window.onresize = function(){
	var pince_xiangguan = document.querySelectorAll('.pince_xiangguan');
	var pingce_zi = document.querySelectorAll('.pingce_zi');
	for(var i =0;i<pince_xiangguan.length;i++){
		if(pince_xiangguan[i].offsetHeight == 250){
			var width_li = pince_xiangguan[i].offsetWidth;
			width_li = width_li - 40;
			pingce_zi[i].style = "position:absolute;bottom:20px;";
			pingce_zi[i].style.width =width_li+'px';
		}
	}
};
</script>
<?php //pr($v);?>