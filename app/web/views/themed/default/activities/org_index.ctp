<style>
@media only screen and (max-width: 641px)
{
.riqi{padding-bottom:10px;}
.riqi .yue{font-size:12px;}
.course_ul .yixue{font-size:12px;}
.course_ul .keshi{font-size:12px;}
.course_ul .xuexu_but{padding-top:15px;}
.course_ul .kc_xx{padding:5px 0;line-height: inherit;height: inherit;width:60px;font-size:12px;
    }
.w72{width:72%;float:left;}
.w28{width:28%;float:left;}
}
		#course_chapter_list .admin-user-img
{
		display:none;
}

	.nian
{
    font-size: 12px;
    color: #ccc;
}
	a.am-btn-success:visited
{
color:#149941;
}
	.am-btn-success
{
background-color:#fff;
color:#149941;
}
.am-btn-success:hover
{
background-color:#fff;
color:#149941;
}
	.course_ul .xinxi
{
	border-bottom:1px solid #ccc;
	padding-bottom:30px;
	padding-right:20px;
}
.xinxi>div:first-child
{
float:left;padding-right:18px;
}
	.riqi
{
color:#888888
}
.tab_name
{
color:#434343;}
	.usercenter_fu .course_log
{
   
    padding: 10px 0 30px 20px;
    margin: 20px 0 50px 10px;
    border: 1px solid #ccc;
    box-shadow: 0 0 15px #ccc;
    border-radius: 3px;
}
h3
{

    font-size: 25px;
    color: #424242;
    padding: 5px 0;
    font-weight: 500;
}
.kc_xx
{
    padding: 0 0;
    line-height: 40px;
    display: inline-block;
    width: 100px;
    height: 40px;
    text-align: center;
    font-size: 16px;
    color: #12873a;
    border: 1px solid #12873a;
    border-radius: 5px;

}
.course_log>div:first-child
{
border-bottom: 1px solid #ccc;
}
.course_ul>li
{
border:none;padding-top:30px;
}

.neirong
{
padding-bottom:20px;font-size:16px;color:#424242;
}
.yixue
{
color:#149940;font-size:14px
}

.xuexu_but
{
padding-top:5%;
}
.keshi{font-size:14px;}
.course_log{
    margin: 20px 0 50px 10px;
    border-radius: 3px;
    padding: 10px 20px 30px 20px;
}
.course_log .am-g h3{
	height: 50px;
	line-height: 38px;
}
.am-active .am-btn-default.am-dropdown-toggle, .am-btn-default.am-active, .am-btn-default:active {
	background-color: #fff;
}
div.course_log ul.am-list li{border-top:none;}
div.course_log ul.am-list li img{max-width:100%;max-height:120px;}

.am-u-lg-3.am-u-md-3.am-u-sm-12.am-user-menu.am-hide-sm-only.am-padding-right-0{display: none!important;}
.am-u-lg-9.am-u-md-8.am-u-sm-12{width:100%;}
.am-btn.am-btn-sm.am-btn-secondary.am-show-sm-only{display:none!important;}
#org_list{margin-right:50px;}
ol.am-breadcrumb.am-hide-md-down.am-color{
	max-width:1200px;
	margin:10px auto;
	padding:0;
	line-height: 25px;
}
</style>
<div class="am-g am-g-fixed">
	<?php echo $this->element('org_menu')?>
	<?php echo $this->element('organization_menu')?>
	<button class="am-btn am-btn-primary am-show-sm-only" data-am-offcanvas="{target: '#org_menu2', effect: 'push'}" style="margin-right:0.5rem;">我的组织</button>
	<div class='course_log am-u-lg-9 am-u-sm-12' style="margin-left: 0;">
		<div class="am-g" style="position: relative;">
			<h3>活动管理</h3>
			<h3 style="height: 30px;">&nbsp;</h3>
			
			<div style="position: absolute;right: 0;top: 40px;">
				
				<a style="margin-left: 5px;" class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/activities/org_view/0/?organization_id='.$organizations_id); ?>">
		            <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
		        </a>
		        <div class="am-cf"></div>
	        </div>

		</div>
		
		<ul class='am-list course_ul'>
			<?php //pr($activity_list); ?>
			<?php if(isset($activity_list)&&sizeof($activity_list)>0){foreach ($activity_list as $k => $v) { ?>
			<?php //pr($v); ?>
			<li>
				<div class='am-g'>
				<div class="am-u-sm-12 am-u-md-12 am-u-lg-12" style="padding-left: 0;border-bottom: 1px solid #ccc;">
					<div class="am-u-lg-12 am-u-md-12 am-u-sm-12 xinxi" style="padding-left: 0;padding-right:0;border-bottom: 0px;">
						<div style="padding-left: 0;width: none;margin-bottom:1rem;" class='am-u-md-3 am-u-sm-12'><a href="<?php echo $html->url('/activities/org_view/'.$v['Activity']['id'].'?organization_id='.$organizations_id); ?>"><?php echo $html->image(isset($v['Activity']['image'])&&$v['Activity']['image']!=''?$v['Activity']['image']:"/theme/default/images/default.png",array('style'=>'margin-left:7px;max-width:150px;max-height:150px;')); ?></a></div>
						<div class="am-u-sm-12 am-u-lg-9" style="padding-right:0;">
						<div class='am-u-lg-6 w72 am-u-md-5' style="padding:0;">
							<div class='am-u-lg-12 am-u-sm-12 am-u-md-12 neirong' style="padding-left: 0;"><a class="tab_name"  href="<?php echo $html->url('/activities/view/'.$v['Activity']['id']); ?>"><?php echo $v['Activity']['name']; ?></a></div>
							<div class='am-u-lg-12 am-u-sm-12 am-u-md-7 keshi' style="padding: 0;">时间：
								<span><?php echo date("Y-m-d",strtotime($v['Activity']['start_date'])); ?></span>
								<span>~</span>
								<span><?php echo date("Y-m-d",strtotime($v['Activity']['end_date'])); ?></span>
							</div>
							<div class='am-u-lg-12 am-u-sm-12 am-u-md-7 keshi' style="padding: 0;margin-top:5px;">状态：<?php if(date('Y-m-d')<$v['Activity']['start_date']){
								echo '未开始';
							}else if(date('Y-m-d')>$v['Activity']['start_date']&&date('Y-m-d')<$v['Activity']['end_date']){echo '进行中';}else{echo '已结束';} ?>
							</div>
							<div class='am-u-lg-12 am-u-sm-12 am-u-md-12 keshi' style="padding: 0;margin-top: 5px;">参加人数：<?php echo isset($act_user_check[$v['Activity']['id']])?count($act_user_check[$v['Activity']['id']]):'0'; ?></div>
							<div class='am-cf'></div>
						</div>
						<div class='am-text-right w28 am-u-md-5 xuexu_but' style="padding:0;">
							<a style="margin-left: 5px;padding:7px 10px;margin-bottom: 5px;margin-bottom: 4px;" class="mt am-btn am-btn-primary am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/activities/view/'.$v['Activity']['id']); ?>"  title="预览活动信息" target="_blank">
					            <span class="am-icon-chevron-right" style="width: 14px;height: 14px;"></span>
					        </a>
							<a style="margin-left: 5px;color: #3bb4f2;padding:7px 10px;margin-bottom: 4px;" class="mt am-btn am-btn-default am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/activities/org_view/'.$v['Activity']['id'].'?organization_id='.$organizations_id); ?>" title="编辑活动">
				            	<span class="am-icon-pencil-square-o" style="width: 14px;height: 14px;"></span>
				        	</a>
				        	<a style="margin-left: 5px;padding:4px 12px 11px 12px;margin-bottom: 4px;" class="mt am-btn am-btn-secondary am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/activities/org_activity_user/'.$v['Activity']['id'].'?organization_id='.$v['Activity']['publisher']); ?>" title="查看参与活动人员">
				            	...
				        	</a>			    
					        
					        <a style="margin-left: 5px;padding:7px 10px;margin-bottom: 5px;margin-bottom: 4px;" class="mt am-btn am-btn-danger am-seevia-btn-add am-btn-sm am-radius" href="javascript:;"  title="删除活动" onclick="delete_activity(<?php echo $v['Activity']['id'] ?>)">
					            <span class="am-icon-trash-o" style="width: 14px;height: 14px;"></span>
					        </a>
						</div>
						</div>
					</div>
				</div>
				<div class='am-cf'></div>
				</div>
			</li>
			<?php }}else{ ?>
				<div class="am-text-center" style="margin-top: 200px;">暂无活动</div>
			<?php } ?>
		</ul>
		<?php echo $this->element('pager')?>
	</div>
</div>
<script>
	function delete_activity(id){
		var delete_con = function(){
			$.ajax({
				url: web_base+'/activities/delete_activity/',
	        	type:"POST",
	        	data:{'activity_id':id},
	        	dataType:"json",
	        	success: function(data){
	            	if(data.code == 1){
						window.location.reload();
	            	}
	        	}
	    	});
		}	
		seevia_confirm(delete_con,'是否确认删除？');
	}
</script>