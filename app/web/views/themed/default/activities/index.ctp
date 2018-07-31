<style type='text/css'>
/*中评*/
@media only screen and (max-width: 1024px)
{
	body .yincang{display:none;}
	body .am-table>tbody>tr>td:nth-child(4){display:none;}
	body .am-table>tbody>tr>td:nth-child(6){display:none;}
}
/*小屏*/
@media only screen and (max-width: 640px)
{
	body .am-table>thead>tr>th{font-size:12px;padding:10px 10px;}
	body .am-table>tbody>tr>td{font-size:12px;padding:0px 5px;}
	
	body .jiesao_sp{font-size:16px;}
	.yincang{display:none;}
	body .am-table>tbody>tr>td:nth-child(4){display:none;}
	body .am-table>tbody>tr>td:nth-child(6){display:none;}
}

.jiesao_sp
{
border-bottom:3px solid #0e90d2;
    padding: 2px 5px;
    font-size:20px;
}
.jiessao{margin:20px auto 30px auto;}
.kaiban_table
{
    max-width: 1200px;
    margin: 0 auto;
    width: 95%;
}
.kaiban_table table{box-shadow: 1px 1px 10px #888;border:none;}
.am-table>thead>tr>th{text-align:center;background:#08afff;color:#fff;border-right:1px #73d3ff dashed;padding:20px 0;font-weight:normal;border-left:none;}
.am-table>tbody>tr>td{text-align:center;border-right:1px #73d3ff dashed;padding:5px 0;border-top:none;border-left:none;}
.am-table>tbody>tr>td:last-child{border-right:none;}
.am-table>tbody>tr:nth-child(even){background:#E1f5fe;}
.am-table>thead>tr>th:last-child{border-right:none;}

.zixun
{
display:inline-block;
background:#ea992d;
color:#fff;
padding:3px 10px;
border-radius:5px;
margin:3px 0;
}
.zixun:hover
{
color:#fff;
}
.am-table>tbody>tr>td{padding:0 10px;}
body .am-breadcrumb{padding:15px 0 0 0;}
</style>
<div class="kaiban_fu am-g">
	<div class="jiessao">
		<div class="am-text-center"><span  class="jiesao_sp">最新开班</span></div>
	</div>
	<!--开班-->
	<div class="kaiban_table">
		<!--<div class="am-scrollable-horizontal">-->
		<table class="am-table">
		    <thead>
		        <tr>
				<th class="yincang">课程等级</th>
		            <th>项目名称</th>
		            <th>开课校区</th>
				<th class="yincang">上课时段</th>
		            <th>开学日期</th>
		            <th class="yincang">价格</th>
			    <th>在线报名</th>
		        </tr>
		    </thead>
		    <tbody>
			<?php 
					$activity_names=array();
			if(isset($activity_list)) {foreach($activity_list as $k => $v){
						$project_code=isset($activity_type_code_infos[$v['Activity']['type']][$v['Activity']['type_id']])?$activity_type_code_infos[$v['Activity']['type']][$v['Activity']['type_id']]:'';
						$activity_names[]=$v['Activity']['name'];
			?>
		        <tr>
		        	<td><?php echo $v['Activity']['name'];?></td>
				<td class="yincang"><?php echo isset($activity_type_infos[$v['Activity']['type']][$v['Activity']['type_id']])?$activity_type_infos[$v['Activity']['type']][$v['Activity']['type_id']]:'-'; ?></td>
				<td><?php echo $v['Activity']['address'];?></td>
				<td><?php echo date("m月d号",strtotime($v['Activity']['start_date']))?>—<?php echo date("m月d号",strtotime($v['Activity']['end_date']))?></td>
				<td><?php echo date("m月d号",strtotime($v['Activity']['start_date']))?></td>
				<td>&yen;<?php echo $v['Activity']['price'];?></td>
				<td><a href="<?php echo $html->url('/contacts/?project_code='.$project_code); ?>" class="zixun">在线报名</a></td>
		        </tr>
		        <?php }}?>
		    </tbody>
		</table>
	<?php if(isset($activity_list) && sizeof($activity_list)>0){?>
	<?php echo $this->element('pager')?>
	<?php }?>
	</div>
	<div style="clear: both;"></div>
  	<div id="activity_comment" class="am-cf" style='margin:0 auto;'></div>
</div>
<script type="text/javascript">
var wechat_shareTitle="最新开班";
var wechat_lineLink="<?php echo $server_host.'/activities/index'; ?>";
var wechat_descContent="<?php echo implode(' ',$activity_names); ?>";

activity_comment()
function activity_comment(){
	$.ajax({ 
		url: web_base+"/activities/activity_comment/",
		dataType:"html",
		type:"POST",
		success: function(data){
			$('#activity_comment').html(data);
	    }
	});
}
</script>