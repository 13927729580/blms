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
.minw{
	min-width: 95%;
	margin-left:-47.5%;
}
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
.am-selected{
    width:100%;
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
    /*border: 1px solid #ccc;*/
    border-radius: 3px;
    /*box-shadow: 0 0 15px #ccc;*/
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
div.course_log ul.am-list li img{max-width:100%;max-height:100%;}

.am-u-lg-3.am-u-md-3.am-u-sm-12.am-user-menu.am-hide-sm-only.am-padding-right-0{display: none!important;}
.am-u-lg-9.am-u-md-8.am-u-sm-12{width:100%;}
.am-btn.am-btn-sm.am-btn-secondary.am-show-sm-only{display:none!important;}
.am-u-lg-2.am-u-md-2.am-u-sm-2.am-panel-group.am-hide-sm-only{margin-right:5%;}
ol.am-breadcrumb.am-hide-md-down.am-color{
	max-width:1200px;
	margin:10px auto;
	padding:0;
	line-height: 25px;
}

#com_chose li a{color:#aaa;}

</style>
<div class="am-g am-g-fixed">
	<input type="hidden" id="org_id" value="<?php echo $organizations_name['Organization']['id'] ?>">
	<?php echo $this->element('organization_menu');?>
	<?php echo $this->element('org_menu')?>

	<button style="margin:10px 0;" class="am-btn am-btn-primary am-show-sm-only" data-am-offcanvas="{target: '#org_menu2', effect: 'push'}">组织菜单</button>

	<input type="hidden" id="course_id" value="">
	<input type="hidden" id="mem_flag" value="0">
	<div class='course_log am-u-lg-9 am-u-sm-12' style="margin-left: 0;">
		<div class="am-g" style="position: relative;">
			<h3>课程管理</h3>
			<h3 style="height: 30px;">&nbsp;</h3>
			<div style="position: absolute;right: 0;top: 40px;">
				<div style="margin-left: 5px;background-color: #149941;color: #fff;" class="mt am-btn am-btn-success am-seevia-btn-add am-btn-sm am-radius" data-am-modal="{target: '#import_course', closeViaDimmer: 0}">
		            <span class="am-icon-plus"></span> 导入
		        </div>
				<a style="margin-left: 5px;" class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/courses/add?organizations_id='.$organizations_id); ?>">
		            <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
		        </a>
		        <div class="am-cf"></div>
	        </div>
		</div>
		</hr>
		<ul class='am-list course_ul'>
			<?php if(isset($course_list)&&sizeof($course_list)>0){foreach ($course_list as $k => $v) { ?>
			<?php //pr($v); ?>
			<li>
				<div class='am-g'>
				<div class="am-u-sm-12 am-u-md-12 am-u-lg-12" style="padding-left: 0;border-bottom: 1px solid #ccc;">
					<div class='am-u-lg-1 am-u-md-2 am-u-sm-12 riqi' style="padding:0;min-width: 95px;">
						<div style="margin-bottom: 10px;" class="nian am-u-lg-12 am-u-md-12 am-u-sm-2"><?php echo date("Y",strtotime($v['Course']['created'])); ?></div>
						<div class="yue am-u-lg-12 am-u-md-12 am-u-sm-6"  style="margin-bottom: 10px;padding-right: 0;"><?php echo date("m月d日",strtotime($v['Course']['created'])); ?></div>
					</div>
					<div class="am-u-lg-10 am-u-md-10 am-u-sm-12 xinxi" style="padding-left: 0;border-bottom: 0px;margin-bottom: 10px;padding-right: 0;">
						<div style="padding-left: 0;width: none;height: 140px;padding:2px;border:1px solid #ccc;text-align: center;line-height: 130px;margin-bottom: 10px;" class='am-u-md-3 am-u-sm-8'><a target="_blank" href="<?php echo $html->url('/courses/view/'.$v['Course']['id']); ?>"><?php echo $html->image($v['Course']['img']!=''?$v['Course']['img']:"/theme/default/images/default.png",array('title'=>$user_list['User']['name'])); ?></a></div>
						<div class='am-u-lg-4 am-u-sm-12 am-u-md-5' style="margin-bottom: 10px;">
							<div class='am-u-lg-12 am-u-sm-12 am-u-md-12 neirong' style="padding-left: 0;"><a class="tab_name" target="_blank" href="<?php echo $html->url('/courses/view/'.$v['Course']['id']); ?>"><?php echo $v['Course']['name']; ?></a></div>
							<div class='am-u-lg-12 am-u-sm-12 am-u-md-7 keshi' style="padding: 0;">课时数：<?php echo $v['Course']['class_count']; ?></div>
							<div class='am-u-lg-12 am-u-sm-12 am-u-md-12 keshi' style="padding: 0;margin-top: 10px;">课程时长：<?php echo intval($v['Course']['hour']); ?>（分钟）</div>
							<div class='am-cf'></div>
						</div>
						<div class='am-u-lg-5 am-text-right am-u-sm-12 am-u-md-4 xuexu_but' style="padding:0;">
							<a style="margin-left: 5px;color: #fff;padding:7px 10px;margin-bottom: 4px;" class="mt am-btn am-btn-primary am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/courses/view/'.$v['Course']['id']); ?>" title="开始学习" target="_blank">
				            	<span class="am-icon-chevron-right" style="width: 14px;height: 14px;"></span>
				        	</a>
					        <a style="margin-left: 5px;padding:7px 10px;text-align: center;color: #3bb4f2;margin-bottom: 4px;" class="mt am-btn am-btn-default am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/courses/edit/'.$v['Course']['id'].'?organizations_id='.$organizations_id); ?>" title="编辑课程">
					            <span class="am-icon-pencil-square-o" style="width: 14px;height: 14px;"></span>
					        </a>
						<a style="margin-left: 5px;padding:7px 10px;text-align: center;color: #fff;margin-bottom: 4px;" class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="javascript:void(0);"  title="复制课程" onclick="import_course('<?php echo $v['Course']['id']; ?>')"><span class="am-icon-copy" style="width: 14px;height: 14px;"></span></a>
					        <?php if($v['Course']['visibility']!=1){ ?>
					        <a style="margin-left: 5px;padding:7px 10px;margin-bottom: 4px;background-color: #fff;color: #0e90d2;" class="mt am-btn am-btn-primary am-seevia-btn-add am-btn-sm am-radius" data-am-modal="{target: '#class_share', closeViaDimmer: 0}" onclick="chose_course(<?php echo $v['Course']['id'] ?>)" title="分享课程">
					            <span class="am-icon-share-alt" style="width: 14px;height: 14px;"></span>
					        </a>
					        <?php } ?>
					        <div class="am-dropdown" data-am-dropdown onmouseover="add_class(this)" onmouseout="del_class(this)">
								<a data-am-dropdown-toggle style="margin-left: 5px;padding:4px 12px 11px 12px;margin-bottom: 4px;" class="mt am-btn am-btn-secondary am-seevia-btn-add am-btn-sm am-radius am-dropdown-toggle" href="javascript:;" title="查看">...</a>
								<ul class="am-dropdown-content" style="margin-top: 0;margin-left: -66px;">
									<li><a href="/courses/course_share/<?php echo $v['Course']['id'] ?>?organizations_id=<?php echo $organizations_id ?>" target="_blank">分享记录</a></li>
									<li><a href="/courses/course_study/<?php echo $v['Course']['id'] ?>?organizations_id=<?php echo $organizations_id ?>" target="_blank">学习情况</a></li>
									<li><a href="/courses/course_note/<?php echo $v['Course']['id'] ?>?organizations_id=<?php echo $organizations_id ?>" target="_blank">笔记记录</a></li>
								</ul>
							</div>
					        <a style="margin-left: 5px;padding:7px 10px;margin-bottom: 5px;margin-bottom: 4px;" class="mt am-btn am-btn-danger am-seevia-btn-add am-btn-sm am-radius" href="javascript:;" onclick="list_delete_submit('/courses/remove/<?php echo $v['Course']['id'] ?>');" title="删除课程">
					            <span class="am-icon-trash-o" style="width: 14px;height: 14px;"></span>
					        </a>
						</div>
					</div>
				</div>
				<div class='am-cf'></div>
				</div>
			</li>
			<?php }}else{ ?>
				<div class="am-text-center" style="margin-top: 50px;">暂无课程</div>
			<?php } ?>
		</ul>
	</div>
</div>
<!-- 导入 -->
<div class="am-modal am-modal-no-btn" tabindex="-1" id="import_course">
	
    <div class="am-modal-dialog" style="padding:10px;">
        <div class="am-modal-hd">课程导入
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    	</div>
    	<div class="am-modal-bd">
			<div class="am-form-group">
                <label class="am-u-lg-4 am-u-md-4 am-u-sm-12 am-form-label" style="margin-top: 12px;">课程列表</label>
                <div class="am-u-lg-4 am-u-md-4 am-u-sm-6">
                    <select id="import_course_select" data-am-selected="{noSelectedText:'请选择',maxHeight:100}">
                        <option value=''><?php echo $ld['please_select'];?></option>
                        <?php if(isset($import_course_list)&&sizeof($import_course_list)>0){foreach ($import_course_list as $k => $v) { ?>
                        	<optgroup label="<?php echo $k=='S'?'系统':'自有'; ?>">
                        		<?php foreach($v as $vv){ ?>
                        		<option value="<?php echo $vv['id']; ?>"><?php echo $vv['name']; ?></option>
                        		<?php } ?>
                        	</optgroup>
                        <?php }} ?>
                    </select>
                </div>
                <div class="am-u-lg-12 am-u-md-12 am-u-sm-12" style="margin-top: 10px;">
                	<div style="margin-right: 0;background-color: #149941;color: #fff;" class="am-btn am-btn-success am-btn-sm am-btn-bottom am-radius" onclick="import_course()">导入</div>
                </div>
            </div>
            <div class="am-cf"></div>
    	</div>
    </div>

</div>

<div class="am-modal am-modal-no-btn minw" tabindex="-1" id="class_share">
	
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style="padding-bottom:15px;">课程分享
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
    	</div>
    	<div class="am-modal-bd">
      		<div class="am-tabs" data-am-tabs="{noSwipe:1}" id="com_chose">
				<ul class="am-tabs-nav am-nav am-nav-tabs">
					<li class="am-active"><a href="javascript: void(0)" onclick="change(this)" style="color:#3bb4f2;">公司</a></li>
					<li><a href="javascript: void(0)" onclick="change(this)" style="color:#aaa;">个人</a></li>
					<li><a href="javascript: void(0)" onclick="change(this)" style="color:#aaa;">批量上传</a></li>
					<li><a href="javascript: void(0)" onclick="change(this)" style="color:#aaa;">第三方分享</a></li>
				</ul>
      		
      		<div class="am-tabs-bd" style="background-color:#fff;">
      			<div class="am-tab-panel am-active" style="min-height:340px;padding-bottom:10px;padding-top:20px;">
      				
      				<div class="am-g">
				    	<div class="am-form-group am-u-lg-6 am-text-left">
				    		<select name="" id="cour_com" data-am-selected="{noSelectedText:'请选择公司',maxHeight:100}"  onchange="get_depart(this)">
				    		<option value="">请选择公司</option>
				    		<?php //pr($org_info); ?>
				    		<?php foreach ($org_info as $k1 => $v1) { ?>
				    			<option value="<?php echo $v1['Organization']['id'] ?>"><?php echo $v1['Organization']['name'] ?></option>
				    		<?php } ?>
				    			
				    		</select>
				    	</div>
				    	<div class="am-form-group am-u-lg-6 am-text-left">
				    		<select name="" id="cour_depart" data-am-selected="{noSelectedText:'请选择部门',maxHeight:100}" onchange="get_job(this)">
				    			<option value="">请选择部门</option>
				    		</select>
				    	</div>
				    	<div class="am-form-group am-u-lg-6 am-text-left">
				    		<select name="" id="cour_job" data-am-selected="{noSelectedText:'请选择职位',maxHeight:100}" onchange="get_job_mem(this)">
				    			<option value="">请选择职位</option>
				    		</select>
				    	</div>
				    	<div class="am-form-group am-u-lg-6 am-text-left">
				    		<button class="am-btn am-btn-secondary" style="min-width:130px;" onclick="search_org_mem()">搜索</button>
				    	</div>
			    	</div>
			    	<div class="am-u-lg-12">
				    	<div style="overflow-y:scroll;border:1px solid #ddd;height:150px;text-align:left;padding-left:1rem;width:80%;" id="mem_info">
				    	</div>
				    	<button class="am-btn am-btn-secondary" style="float:left;margin-top:1.5rem;min-width:130px;" onclick="share(this)">立即分享</button>
			    	</div>
			    </div>
			    <div class="am-tab-panel" style="height:300px;padding-top:18px;overflow:auto;">
			    	<form action="" class="am-form" onsubmit="return false;">
			    		<div style="width:100%;">
				    		<div style="margin-bottom:10px;">
				    			<div class="am-u-lg-6 am-u-sm-12" style="margin-bottom:5px;">
					    			<input type="text" placeholder="姓名" name="data[user_name][]" class="am-input-sm">
					    		</div>
					    		<div class="am-u-lg-6 am-u-sm-12">
					    			<input type="text" placeholder="手机号码" name="data[user_mobile][]" class="am-input-sm">
					    		</div>
					    		<div class="am-cf"></div>
				    		</div>
			    			<div style="margin-bottom:10px;">
				    			<div class="am-u-lg-6 am-u-sm-12" style="margin-bottom:5px;">
					    			<input type="text" placeholder="姓名" name="data[user_name][]" class="am-input-sm">
					    		</div>
					    		<div class="am-u-lg-6 am-u-sm-12">
					    			<input type="text" placeholder="手机号码" name="data[user_mobile][]" class="am-input-sm">
					    		</div>
					    		<div class="am-cf"></div>
				    		</div>
				    		<div style="margin-bottom:10px;">
				    			<div class="am-u-lg-6 am-u-sm-12" style="margin-bottom:5px;">
					    			<input type="text" placeholder="姓名" name="data[user_name][]" class="am-input-sm">
					    		</div>
					    		<div class="am-u-lg-6 am-u-sm-12">
					    			<input type="text" placeholder="手机号码" name="data[user_mobile][]" class="am-input-sm">
					    		</div>
					    		<div class="am-cf"></div>
				    		</div>
				    		<div style="margin-bottom:10px;">
				    			<div class="am-u-lg-6 am-u-sm-12" style="margin-bottom:5px;">
					    			<input type="text" placeholder="姓名" name="data[user_name][]" class="am-input-sm">
					    		</div>
					    		<div class="am-u-lg-6 am-u-sm-12">
					    			<input type="text" placeholder="手机号码" name="data[user_mobile][]" class="am-input-sm">
					    		</div>
					    		<div class="am-cf"></div>
				    		</div>
				    		<div style="margin-bottom:10px;">
				    			<div class="am-u-lg-6 am-u-sm-12" style="margin-bottom:5px;">
					    			<input type="text" placeholder="姓名" name="data[user_name][]" class="am-input-sm">
					    		</div>
					    		<div class="am-u-lg-6 am-u-sm-12">
					    			<input type="text" placeholder="手机号码" name="data[user_mobile][]" class="am-input-sm">
					    		</div>
					    		<div class="am-cf"></div>
				    		</div>
			    		</div>
			    		<div class="am-u-lg-12">
			    			<button class="am-btn am-btn-secondary" style="float:left;" onclick="ajax_user_share(this)">立即分享</button>
			    		</div>
			    	</form>
			    </div>
			    <div class="am-tab-panel am-text-left" style="height:300px;padding-top:1rem;overflow:auto;padding-left:5px;" id="third_panel">
			    	<form action="<?php echo $html->url('/courses/batch_share') ?>" id="b_invite" method="POST" name="theFileForm" enctype="multipart/form-data">
			    		<!-- <div>
			    			<input type="file" name="file" id="batch_file" onchange="checkFile(this.id)">
			    			<input type="hidden" name="eval_id" >
			    		</div> -->
			    		<div class="am-form-group am-form-file" style="margin-top:1rem;">
						  <button type="button" class="am-btn am-btn-default am-btn-sm">
						    <i class="am-icon-cloud-upload"></i> 选择要上传的文件</button>
						  <input type="file" name="file" id="batch_file" onchange="checkFile(this.id)">
			    		  <input type="hidden" name="eval_id" >
						</div>
			    		
			    		<!-- <button type="button" class="am-btn am-btn-secondary am-btn-sm" style="min-width:130px;" onclick="ajaxFileUpload()">上传</button> -->
			    		<div style="margin-top:18px;">
			    		<?php echo $html->link('下载csv样例',"/courses/download_share_csv_example/",'',false,false);?>
			    		</div>
			    	</form>
			    	<div class="am-cf"></div>
			    </div>
			    <div class="am-tab-panel am-text-left" style="padding-top:1rem;padding-bottom:1rem;overflow:auto;" id="forth_panel">
			    	<form action="" class="am-form">
			    		<div style="margin-bottom:20px;">
				    		<div class="am-form-group" style="margin-bottom:10px;margin-left:15px;">
				    		  <div style="margin-bottom:10px;">按创建者手机邀请：</div>		
						      <div class="am-u-lg-6" style="padding-left:0;"><input type="text" class="" id="search_phone" placeholder="请输入对方的手机号码"></div>
						      <div class="am-u-lg-12 am-show-sm-only" style="height:0.5rem;">&nbsp;</div>
						      <div class="am-u-lg-6" style="display:none;padding-left:0;">
						      	<select name="" id="org_select" data-am-selected="{maxHeight:100}">
						      		<option value="">请选择</option>
						      	</select>
						      </div>
						      <div class="am-cf"></div>
						    </div>
						    <div style="margin-left:15px;">
							    <button type="button" class="am-btn am-btn-secondary" onclick="search_org(this)">确定</button>
							    <button type="button" class="am-btn am-btn-secondary" onclick="org_manager_invite(this)" style="display:none;" >立即邀请</button>
						    </div>
						</div>
					    <div class="am-form-group" style="margin-bottom:10px;margin-left:15px;">
			    		  <div style="margin-bottom:10px;">按公司全称邀请：</div>		
					      <div class="am-u-lg-6" style="padding-left:0;"><input type="text" class="" id="search_name" placeholder="请输入对方公司全称"></div>
					      <div class="am-cf"></div>
					    </div>
					    <button type="button" class="am-btn am-btn-secondary" onclick="org_name_invite(this)" style="margin-left:15px;">立即邀请</button>
			    	</form>
			    </div>
			    </div>
      		</div>
    	</div>
    </div>

</div>
<script>
var checkorg = new Array();
<?php if(isset($check_org)&&count($check_org)>0){foreach ($check_org as $k3 => $v3) { ?>
	checkorg["<?php echo $k3 ?>"] = '<?php echo $v3 ?>';
<?php }} ?>
function list_delete_submit(sUrl){
	var aa = function(){
		$.ajax({
            type: "POST",
            url: sUrl,
            dataType: 'json',
            success: function (result) {
                if(result.flag==1){
                    //alert(result.message);
                    window.location.reload();
                }
                if(result.flag==2){
                    seevia_alert(result.message);
                }
            }
        });
	}
    seevia_alert_func(aa,"确定删除？");
}

function change(obj){
	$(obj).css('color','#19a7f0');
	$(obj).parent().siblings().find('a').css('color',"#aaa");
}

function ajax_user_share(obj){
	var postData = $(obj).parent().parent().serialize();
	var course_id = $("#course_id").val();
	$(obj).attr('disabled',true);
	$.ajax({
		type: "POST",
		url: web_base+"/courses/ajax_user_share/"+course_id,
        dataType: 'json',
        data:postData,
        success: function (data) {
            if(data.message == ''){
            	// alert('分享成功！');
            	// window.location.reload();
            	$("#class_share").modal('close');
            	seevia_alert_func(jump_reload,'分享成功！');
            }else{
            	seevia_alert(data.message);
            	$(obj).attr('disabled',false);
            }
        }
    });
}

function get_depart(obj){	
	if($(obj).val() != ''){
	$("#cour_depart").html('<option value="">请选择</option>');
	$("#mem_info").html('');
	$.ajax({
		type: "POST",
		url: web_base+"/courses/get_depart",
        dataType: 'json',
        data:{'org_id':$(obj).val()},
        success: function (data) {
            
            for(var i =0;i<data.length;i++){
				var depart_info = '<option value="'+data[i].OrganizationDepartment.id+'">'+data[i].OrganizationDepartment.name+'</option>';
				$("#cour_depart").append(depart_info);	
			}
        }
    });
  //   $.ajax({
		// type: "POST",
		// url: web_base+"/courses/get_mem",
  //       dataType: 'json',
  //       data:{'org_id':$(obj).val()},
  //       success: function (data) {

  //           for(var i =0;i<data.length;i++){
		// 		var mem_info = '<label class="am-checkbox"><input type="checkbox" data-am-ucheck class="mem-check" value="'+data[i].OrganizationMember.id+'">'+data[i].OrganizationMember.name+' - '+checkorg[data[i].OrganizationMember.organization_id]+'</label>';
		// 		$("#mem_info").append(mem_info);	
		// 	}
		// 	$("input[type='checkbox'], input[type='radio']").uCheck('check');
  //       }
  //   });
}
}

function get_job(obj){
	$("#cour_job").html('<option value="">请选择</option>');
	$("#mem_info").html('');
	$.ajax({
		type: "POST",
		url: web_base+"/courses/get_job",
        dataType: 'json',
        data:{'depart_id':$(obj).val()},
        success: function (data) {
            
            for(var i =0;i<data.length;i++){
				var job_info = '<option value="'+data[i].OrganizationJob.id+'">'+data[i].OrganizationJob.name+'</option>';
				$("#cour_job").append(job_info);	
			}
        }
    });
  //   $.ajax({
		// type: "POST",
		// url: web_base+"/courses/get_mem",
  //       dataType: 'json',
  //       data:{'depart_id':$(obj).val()},
  //       success: function (data) {
  //       	$("#mem_info").html('');
  //           for(var i =0;i<data.length;i++){
		// 		var mem_info = '<label class="am-checkbox"><input type="checkbox" data-am-ucheck class="mem-check" value="'+data[i].OrganizationMember.id+'">'+data[i].OrganizationMember.name+' - '+checkorg[data[i].OrganizationMember.organization_id]+'</label>';
		// 		$("#mem_info").append(mem_info);	
		// 	}
		// 	$("input[type='checkbox'], input[type='radio']").uCheck('check');
  //       }
  //   });

}

function get_job_mem(obj){
	$("#mem_info").html('');
	// $.ajax({
	// 	type: "POST",
	// 	url: web_base+"/courses/get_mem",
 //        dataType: 'json',
 //        data:{'job_id':$(obj).val()},
 //        success: function (data) {
 //        	$("#mem_info").html('');
 //            for(var i =0;i<data.length;i++){
	// 			var mem_info = '<label class="am-checkbox"><input type="checkbox" data-am-ucheck class="mem-check" value="'+data[i].OrganizationMember.id+'">'+data[i].OrganizationMember.name+' - '+checkorg[data[i].OrganizationMember.organization_id]+'</label>';
	// 			$("#mem_info").append(mem_info);	
	// 		}
	// 		$("input[type='checkbox'], input[type='radio']").uCheck('check');
 //        }
 //    });

}

function share(obj){
	var checkboxes = new Array();
	var course_id = $("#course_id").val();
	var org_id = $("#cour_com").val();
	var depart_id = $("#cour_depart").val();
	var job_id = $("#cour_job").val();
	var bratch_operat_check = document.getElementsByClassName("mem-check");
	$("#mem_flag").val(0);
	if(job_id == ''){
		$(".mem-check").each(function(){
			if($(this).is(":checked") == false){
				$("#mem_flag").val(1);
			}
		});
	}else{
		$("#mem_flag").val(1);
	}
	if($("#mem_flag").val() == 1){
		for(var i=0;i<bratch_operat_check.length;i++){
			if(bratch_operat_check[i].checked){
				checkboxes.push(bratch_operat_check[i].value);
			}
		}
		if(checkboxes.length == 0){
			seevia_alert('您还没有选择成员！');
			return;
		}
		$(obj).attr("disabled", true); 
		//console.log(checkboxes);
		$.ajax({
			type: "POST",
			url: web_base+"/courses/share",
	        dataType: 'json',
	        data:{'mem_id':checkboxes,'course_id':course_id},
	        success: function (data) {
	        	if(data.code == 1){
	        		$("#class_share").modal('close');
	        		seevia_alert_func(jump_reload,'分享成功！');

	        	}else{
	        		seevia_alert(data.message);
	        	}
	        }
	    });
	}else if($("#mem_flag").val() == 0){
		if($("#cour_com").val() == ''&&$("#cour_depart").val() == ''&&$("#cour_job").val() == ''){
			seevia_alert('邀请的对象不能为空！');
		}else if($("#cour_depart").val() == ''){
			$(obj).attr("disabled", true);
			$.ajax({
				type: "POST",
				url: web_base+"/courses/share",
		        dataType: 'json',
		        data:{'org_id':org_id,'course_id':course_id},
		        success: function (data) {
		        	if(data.code == 1){
		        		// alert('邀请成功！');
		        		// window.location.reload();
		        		$("#class_share").modal('close');
		        		seevia_alert_func(jump_reload,'分享成功！');
		        	}
		        }
		    });
		}else{
			for(var i=0;i<bratch_operat_check.length;i++){
			if(bratch_operat_check[i].checked){
				checkboxes.push(bratch_operat_check[i].value);
				}
			}
			$(obj).attr("disabled", true);
			$.ajax({
				type: "POST",
				url: web_base+"/courses/share",
		        dataType: 'json',
		        data:{'depart_id':depart_id,'course_id':course_id,'depart_mem_id':checkboxes},
		        success: function (data) {
		        	if(data.code == 1){
		        		// alert('邀请成功！');
		        		// window.location.reload();
		        		$("#class_share").modal('close');
		        		seevia_alert_func(jump_reload,'分享成功！');
		        	}
		        }
		    });
		}
	}
}

function chose_course(course_id){
	$("#course_id").val(course_id);
}

function checkFile() {
	var obj = document.getElementById('batch_file');
	var suffix = obj.value.match(/^(.*)(\.)(.{1,8})$/)[3];
	if(suffix != 'csv'&&suffix != 'CSV'){
 		seevia_alert("文件格式错位！需要是CSV格式！");
 		obj.value="";
 		return false;
	}else{
		ajaxFileUpload();
	}
}

function ajaxFileUpload(){
	var course_id = $("#course_id").val();
	//var img_id = $('input[name="file"]').attr('id');
	var input_file = $('#batch_file')[0].files[0];
	var formData = new FormData();
	formData.append("file", input_file);
	formData.append("course_id", course_id);
	var xhr = null;
        if (window.XMLHttpRequest){// code for all new browsers
            xhr=new XMLHttpRequest();
        }else if (window.ActiveXObject){// code for IE5 and IE6
            xhr=new ActiveXObject("Microsoft.XMLHTTP");
        }else{
            seevia_alert("Your browser does not support XMLHTTP.");return false;
        }
        xhr.onreadystatechange = function(){
            if (xhr.readyState == 4 && xhr.status == 200){
	                //eval("var result="+xhr.responseText);
	                //console.log(xhr.responseText);
	                $("#third_panel").html(xhr.responseText);
	                $("#third_panel input[type='checkbox']").uCheck();
            }
        };
        xhr.onerror=function(evt){
            //console.log(j_object_transform_failed);
        };
        xhr.open("POST", web_base+'/courses/batch_share');
        xhr.send(formData);
}

function ajax_batch_share(obj){
	var course_id = $("#course_id").val();
	var postData = $("form[name='theDateForm']").serialize();
	var jump = function(){
		window.location.href=web_base+'/courses/course_management?organizations_id='+$("#org_id").val();
	}
	$(obj).attr('disabled',true);
	$.ajax({
        type: "POST",
        url: web_base+'ajax_batch_share/'+course_id,
        dataType: 'json',
        data:postData,
        success: function (data) {
            if(data.message == ''){
        	// alert('分享成功！');
        	// window.location.href=web_base+'/courses/course_management';
        	$("#class_share").modal('close');
        	seevia_alert_func(jump,'分享成功！');
            }else{
            	seevia_alert(data.message);
            	$(obj).attr('disabled',false);
            }
        }
    });
}

function batch_chose(obj){
	if($(obj).is(':checked')){
		$(".update_chose").uCheck('check'); 
	}else{
		$(".update_chose").uCheck('uncheck');
	}
}

function cancel_upload(){
	var org_id = $("#org_id").val();
	
	$.ajax({
        type: "POST",
        url: web_base+'/courses/course_management?organizations_id='+org_id,
        dataType: 'html',
        data:{},
        success: function (data) {
            var HtmlDiv=document.createElement('div');
            HtmlDiv.innerHTML=data;
            var content=$(HtmlDiv).find('#third_panel').html();
            //alert(content);
            $("#third_panel").html(content);
        }
    });
}

	function search_org(obj){
		var reg = /^1[3|4|5|7|8][0-9]{9}$/;
		var phone = $("#search_phone").val();
		var content = '<option value="">请选择</option>';
		if(phone == ''){
			seevia_alert('手机号不能为空！');
			return false;
		}else if(reg.test(phone) == false){
			seevia_alert('手机号码格式有误！');
			return false;
		}
		$.ajax({
            type: "POST",
            url: web_base+'/courses/ajax_search_org',
            dataType: 'json',
            data:{'mobile':phone},
            success: function (data){
                for (var i = 0; i < data.length; i++){
                	content += '<option value="'+data[i].Organization.id+'">'+data[i].Organization.name+'</option>';
                };
                $("#org_select").html(content);
                $("#org_select").parent().show();
                $(obj).siblings('button').show();
            }
        });
	}

	function org_manager_invite(obj){
		var course_id = $("#course_id").val();
		var org_id = $("#org_select").val();
		$(obj).attr('disabled',true);
		$.ajax({
            type: "POST",
            url: web_base+'/courses/org_manager_invite',
            dataType: 'json',
            data:{'org_id':org_id,'course_id':course_id},
            success: function (data){
                if(data.code == 1){
                	// alert('邀请成功！');
                	// window.location.reload();
                	seevia_alert_func(jump_reload,'分享成功！');
                }else{
                	seevia_alert(data.message);
                	$(obj).attr('disabled',false);
                }
            }
        });
	}

	function org_name_invite(obj){
		var course_id = $("#course_id").val();
		var org_name = $("#search_name").val();
		$(obj).attr('disabled',true);
		$.ajax({
            type: "POST",
            url: web_base+'/courses/org_name_invite',
            dataType: 'json',
            data:{'org_name':org_name,'course_id':course_id},
            success: function (data){
                if(data.code == 1){
                	// alert('邀请成功！');
                	// window.location.reload();
                	seevia_alert_func(jump_reload,'分享成功！');
                }else{
                	seevia_alert(data.message);
                	$(obj).attr('disabled',false);
                }
            }
        });
	}

	function search_org_mem(){
		var postData = new Object();
		if($("#cour_job").val()!=''){
			postData['job_id'] = $("#cour_job").val();
		}else if($("#cour_depart").val()!=''){
			postData['depart_id'] = $("#cour_depart").val();
		}else if($("#cour_com").val()!=''){
			postData['org_id'] = $("#cour_com").val();
		}

		$.ajax({
		type: "POST",
		url: web_base+"/courses/get_mem",
	    dataType: 'json',
	    data:postData,
	    success: function (data) {
	    		$("#mem_info").html('');
	        	for(var i =0;i<data.length;i++){
				var mem_info = '<label class="am-checkbox"><input type="checkbox" data-am-ucheck class="mem-check" value="'+data[i].OrganizationMember.id+'">'+data[i].OrganizationMember.name+(typeof(data[i].OrganizationMember.mobile)!='undefined'&&data[i].OrganizationMember.mobile.trim()!=''?(' - '+data[i].OrganizationMember.mobile):'')+(typeof(data[i].OrganizationMember.depart)!='undefined'&&data[i].OrganizationMember.depart.trim()!=''?(' - '+data[i].OrganizationMember.depart):'')+'</label>';
				$("#mem_info").append(mem_info);	
			}
			$("input[type='checkbox'], input[type='radio']").uCheck('check');
	    }
	    });
	}

	function import_course(course_id){
		var id = $('#import_course_select').val();
		if(typeof(course_id)!='undefined')id=course_id;
		var organizations_id = $("#org_id").val()
		if(id==''){
			seevia_alert('请选择课程！');
			return false;
		}
		$.ajax({
			type: "POST",
			url: web_base+"/courses/import_course",
			dataType: 'json',
			data:{'id':id,'organizations_id':organizations_id},
			success: function (data) {
				if(data.code==1){
					seevia_alert('导入成功！');
					window.location.reload();
				}else{
					seevia_alert('导入失败！');
				}
			}
		});
	}

	function add_class(btn){
    	$(btn).addClass('am-active');
    }

    function del_class(btn){
    	$(btn).removeClass('am-active');
    }
</script>