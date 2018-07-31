<?php //pr($Userwork_lists);?>
<style>
@media only screen and (max-width: 640px)
{
body .user_work .bianji{font-size:14px;}
}
	.pages span:hover
{
cursor:pointer;
}
.user_work .shangchuan
{
background:#149842;
border-color:#149842;
padding:6px 15px;
}
.user_work .shangchuan:hover
{
background:#149842;
border-color:#149842;
}



/*样式*/
#user_resume_detail .user_work ul.am-list li:first-child
{

margin-top:0;
}
#user_resume_detail .user_work ul.am-list li
{
padding:0 0 20px 0;
margin-top:20px;
}
.user_work .bianji
{
color:#000;font-size:18px;
}
.user_work .bianji:hover
{
color:#149941;
}
</style>
<div class="am-g user_work">
	<div class="am-g">
		<div class="am-u-lg-12am-u-md-12 am-u-sm-12 am-text-right"><a class='am-btn am-btn-warning am-radius am-btn-xs addbutton' href="<?php echo $html->url('/user_works/view/0'); ?>"><span class="am-icon-plus">&nbsp;上传作品</span></a></div>
		<div class='am-cf'></div>
	</div>
	<div class="am-g">
		<ul class='am-list' style="margin:0 auto;margin-bottom:30px;">
			<?php foreach($Userwork_lists as $v){ ?>
			<li style="border:none;border-bottom:1px solid #ccc;">
				<div class='am-g'>
					<div class='am-u-lg-12 am-u-md-12 am-u-sm-12' style="padding-bottom:17px;font-size:18px;color:#585858;line-height:22px;"><?php echo date("m月d日",strtotime($v['UserWork']['modified'])); ?></div>
				
					<div class="am-u-lg-10 am-u-md-10 am-u-sm-10">
						<div class='tupian am-u-lg-4 am-u-md-4 am-u-sm-4' style="float:left;">
						 <figure data-am-widget="figure" class="am am-figure am-figure-default "   data-am-figure="{pureview: 'true' }">
						<?php 
								/*$works_img=$v['UserWork']['works_img']!=''?$v['UserWork']['works_img']:$configs['shop_default_img'];
								$new_works_img=$works_img;
								if($new_works_img!=$configs['shop_default_img']){
									$works_img_info=pathinfo($works_img);
									if(isset($works_img_info['filename'])){
										$works_img_extension=$works_img_info['extension'];
										$new_works_img=str_replace('.'.$works_img_extension,'_100x100.'.$works_img_extension,$new_works_img);
									}
								} */?>
							<img src="<?php echo $v['UserWork']['works_img']?>" data-rel="<?php echo $v['UserWork']['works_img']?>"/>
							</figure>
							</div>
						<div  style="padding-left:20px;float:left;" class="am-u-lg-7 am-u-md-7 am-u-sm-7">
							<div class='works_name' style="padding-bottom:20px;line-height:25px;"><?php echo $v['UserWork']['works_name']; ?></div>
							<div style="color:#149941;line-height:25px;" class='works_type'><?php 
								 if($v['UserWork']['works_type']=='1'){echo '移动应用开发';}
								if($v['UserWork']['works_type']=='2'){echo '设计及多媒体';}
								if($v['UserWork']['works_type']=='3'){echo '网站应用开发';} 
								if($v['UserWork']['works_type']=='4'){echo '应用软件开发';} 
								if($v['UserWork']['works_type']=='5'){echo '金碟ERP';}
								; ?>
									 	 </div>
						</div>
						<div class='am-cf'></div>
					</div>
					<div class="am-u-lg-2 am-u-md-3 am-u-sm-12 am-text-right am-text-right">
						<!--<div class='am-g delete' style="padding-bottom:18px;"><a  href="javascript:void(0);"  onclick="user_works_remove('<?php echo $v['UserWork']['id']; ?>')"><img src="/theme/default/img/delete.png" /></a></div>
						<div class='am-g'><a class="bianji"  href="<?php echo $html->url('/user_works/view/'.$v['UserWork']['id']); ?>">编辑</a>-->
						
						
						
				    	<a  class="xiugai"  href="<?php echo $html->url('/user_works/view/'.$v['UserWork']['id']); ?>">修改</a>
						
						<a  class="xiugai_2"  href='javascript:void(0);' onclick="user_works_remove('<?php echo $v['UserWork']['id']; ?>')">删除</a>
					</div>
					<div class='am-cf'></div>
				</div>
			</li>
			<?php } ?>
		</ul>
		<?php echo $this->element('pager'); ?>
	</div>
	<div class='am-cf'></div>
</div>
<style type='text/css'>
@media only screen and (max-width: 640px)
{
#user_works .tupian{width:100px;}
body .user_work ul.am-list li .tupian>img{max-width:100px;height:100px;}
}
.tupian{width:200px;}
.user_work ul.am-list{margin:1rem auto;}
.user_work ul.am-list li{border-top:none;padding:0 0;border-bottom:1px solid #dedede;margin:0.5rem auto;}
.user_work ul.am-list li .tupian>img{max-width:200px;max-height:130px;min-height:130px;}
.user_work ul.am-list li div[class*="am-u-"]{padding-left:0;padding-right:0}
.user_work ul.am-list li div a.am-no:hover{color: #dd514c;}
</style>
<script type='text/javascript'>

	/*分页加载*/
$(function(){
	$('.user_work .pages a').click(function(){
		//alert('aa');
		var url = $(this).attr('href');
			$.ajax( {
			        url: web_base+url, 
			        type: "GET",
			        dataType:'html',
			        success: function(data){
			        	//console.log(data);
			            $("#user_works").html(data);
			        }
			});
			return false;
	});
});
	
function user_works_remove(data_id){
	
	if(confirm(confirm_delete)){
		$.ajax({
			url: web_base+"/user_works/remove/"+data_id,
			type:"GET",
			dataType:"JSON", 
			data: {},
			success: function(data){
				if(data.code=='1'){
				window.location.href="/user_resumes/index/?user_work=1";
				user_works();
				}else{
					alert(data.message);
				}
			}
		});
	}
	
}
if(GetQueryString('user_work')=='1'){
	$('#user_resume_tab ul.am-tabs-nav li.am-active').removeClass('am-active');
	$('#user_resume_tab ul.am-tabs-nav li:last-child').addClass('am-active');
	$('#user_resume_detail div.am-active').removeClass('am-active');
	$('#user_resume_detail #user_works').addClass('am-active');
}
if(GetQueryString('user_work')=='2'){
	$('#user_resume_tab ul.am-tabs-nav li.am-active').removeClass('am-active');
	$('#user_resume_tab ul.am-tabs-nav li:nth-child(2)').addClass('am-active');
	$('#user_resume_detail div.am-active').removeClass('am-active');
	$('#user_resume_detail #user_education').addClass('am-active');
}
if(GetQueryString('user_work')=='3'){
	$('#user_resume_tab ul.am-tabs-nav li.am-active').removeClass('am-active');
	$('#user_resume_tab ul.am-tabs-nav li:first-child').addClass('am-active');
	$('#user_resume_detail div.am-active').removeClass('am-active');
	$('#user_resume_detail #user_experience').addClass('am-active');
}
</script>