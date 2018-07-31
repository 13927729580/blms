
<style>
/*小屏*/
@media only screen and (max-width: 640px)
{
	body .user_work .am-form-horizontal .am-form-group input{font-size:12px;}
	body .user_work form
{
	padding:30px 0 0 0px;
}
	.select_p #shangchuan_btn{width:100px;height:30px;line-height:30px;font-size:16px;}
}
	.user_work .am-form-horizontal .am-form-group input
{
height:35px;
padding:0 5px;

}
	label
{
margin-bottom:0;
}
	.am-form-groupcolor:red
{

margin-bottom:20px;}
	#course_chapter_list .admin-user-img
{
display:none;
}


	.up_class:hover
{
cursor:pointer;
}
	.up_class
{
    border: 1px solid #000;
    background: #ccc;
    width:20px;
    height:20px;
    line-height:20px;
    font-size:13px;
    text-align:center;
    border-radius: 5px;
    color:#000;
}
	.delete_class
{
	text-align:center;
	display:inline-block;
    border: 1px solid #000;
    background: #ee382b;
    border-radius: 5px;
    width:20px;
    height:23px;
    line-height:20px;
    font-size:13px;
    font-size:125%;
    color:#fff;
    padding-right:1px;
}
.delete_class:hover
{
cursor:pointer;
color:#fff;
}


	.am-list>li
{
margin-bottom:0;
}
.user_work label
{
	padding-top:6px;
font-weight:400;
font-size:14px;
padding-right:20px;
}
.usercenter_fu .user_work
{
margin:20px 0 50px 10px;
border:1px solid #ccc;
border-radius:3px;
box-shadow: 0 0 15px #ccc;
padding: 10px 20px 30px 20px;
}
.user_work form
{
	padding:30px 0 0 30px;
}
.user_work .shangchuan
{
    color: #585858;
    background: #dcdcdc;
    border: 1px solid #7d7d7d;
    width: 100px;
    border-radius: 4px;
    text-align: center;
    font-size: 14px;
    height: 30px;
    line-height: 30px;
    margin-bottom: 10px;
}
.user_work .shangchuan:hover
{
cursor:pointer;
}
.delete span:hover
{
cursor:pointer;
}
.user_works_annex
{
margin-bottom:20px;
}
.user_works_annex>.img_work
{
margin-bottom:30px;
border:none;
}
.user_works_annex>.img_work:last-child
{
margin-bottom:0;
}
.bianji_img
{
max-width: 120px;
max-height:85px;;

}
h3
{
    font-size: 25px;
    color: #424242;
    padding: 5px 0;
    font-weight: 500;
    border-bottom:1px solid #ccc;
}
.leibie{color:red;}
.select_p{margin-left:-5px;}

.user_work .select_p .hangyan
{
border-radius:5px;color:#333;border-color:#7d7d7d;padding:5px 2px;font-size:14px;
}
.user_work  .select_p .jineng
{
border-radius:5px;border-color:#7d7d7d;font-size:14px;
}
.mt_11
{
margin-top:11px;
}
.paixu
{
padding:0 10px;float:left;
}
textarea
{
border-radius:5px;width:345px;border-color:#7d7d7d;
}
</style>
<script src="/plugins/ajaxfileupload.js" type="text/javascript"></script>
<div class="am-g user_work">
	<div class="am-g">
	<h3 
>上传作品</h3>

</div>	
	<?php echo $form->create('/user_work',array('action'=>'view','class'=>' am-form am-form-horizontal','type'=>'POST'));?>
	<input type='hidden' name="backup" value="/user_resumes/index" />
	<input type='hidden' name="data[UserWork][id]" value="<?php echo isset($UserWork_data['UserWork'])?$UserWork_data['UserWork']['id']:'0'; ?>" />
	<div class="am-form-group">
		<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right'><span class="leibie">*&nbsp;</span><?php echo "类别"; ?></label>
		<div class='am-u-lg-6 am-u-sm-6 am-u-md-6 select_p'>
			<select id="hangyan" class="hangyan" name="data[UserWork][works_type]">
					<option value=''><?php echo $ld['please_select'] ?></option>
					<option value='1' <?php echo $UserWork_data['UserWork']['works_type']=='1'?'selected':'' ?>><?php echo "移动应用开发" ?></option>
					<option value='2' <?php echo $UserWork_data['UserWork']['works_type']=='2'?'selected':'' ?>><?php echo "设计及多媒体" ?></option>
					<option value='3' <?php echo $UserWork_data['UserWork']['works_type']=='3'?'selected':'' ?>><?php echo "网站应用开发" ?></option>
					<option value='4' <?php echo $UserWork_data['UserWork']['works_type']=='4'?'selected':'' ?>><?php echo "应用软件开发" ?></option>
					<option value='5' <?php echo $UserWork_data['UserWork']['works_type']=='5'?'selected':'' ?>><?php echo "金碟ERP" ?></option>
				</select>
		</div>
		<div class='am-cf'></div>
	</div>
		<div class="am-form-group">
		<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right'><?php echo '技能'; ?></label>
		<div class='am-u-lg-6 am-u-sm-8 am-u-md-6 select_p'>
			<input  class="jineng"type='text' name="data[UserWork][skill]" placeholder="HTML5,PHP,JAVA,微信公众平台" onfocus=/>
		</div>
		<div class='am-cf'></div>
	</div>
	<div class="am-form-group">
		<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right'><span class="leibie">*&nbsp;</span><?php echo '标题'; ?></label>
		<div class='am-u-lg-6 am-u-sm-6 am-u-md-6 select_p'>
			<input class="hangyan" type='text' name="data[UserWork][works_name]" value="<?php echo isset($UserWork_data['UserWork'])?$UserWork_data['UserWork']['works_name']:''; ?>" />
		</div>
		<div class='am-cf'></div>
	</div>
	
	<div class="am-form-group">
		<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right' ><?php echo '案例截图'; ?></label>
		<div class='am-u-lg-6 am-u-sm-6 am-u-md-6 select_p' >
			<input class="am-hide" type='file' name='works_img' id='works_img' multiple accept="image/*" onchange="ajax_upload_files(this)" />
			<div class="shangchuan">上传附件</div>
			<ul class="am-list user_works_annex">
				<?php if(isset($UserWorksAnnex_list)&&!empty($UserWorksAnnex_list)){foreach($UserWorksAnnex_list as $k=>$v){ ?>
				<li style="" class="img_work">
					<div class="am-g ">
					<div class="am-fl">
						<div><img src="/theme/default/img/jt2.png" onclick="zp_img_up(this)"></div>
						<div class="mt_11" ><img src="/theme/default/img/jt.png" onclick="zp_img_down(this)"></div>
						<div class="mt_11"><a onclick='shangchuan_delete(this);' ><img src="/theme/default/img/sc_3.png"></a></div>
					</div>
					<div class="am-u-lg-4 am-u-md-5 am-u-sm-6 am-text-center paixu"><?php echo $html->image($v['UserWorksAnnex']['file_url']!=''?$v['UserWorksAnnex']['file_url']:"/theme/default/img/no_head.png",array('class'=>'bianji_img')); ?><input type='hidden' name="data[UserWorksAnnex][<?php echo $k; ?>][id]"  value="<?php echo $v['UserWorksAnnex']['id']; ?>" /><input type='hidden' name="data[UserWorksAnnex][<?php echo $k; ?>][file_url]" value="<?php echo $v['UserWorksAnnex']['file_url']; ?>" />
					</div>
					<div class="am-u-lg-7 am-hide-sm-only am-u-md-5" style="padding:0 5px;"><textarea maxlength="100" name="data[UserWorksAnnex][<?php echo $k; ?>][name]" style="border-radius:5px;"><?php echo $v['UserWorksAnnex']['name'];?></textarea></div>
					<div class="am-cf"></div>
					</div>
				</li>
				<?php }} ?>
			</ul>
		</div>
		<div class='am-cf'></div>
	</div>
	<div class="am-form-group">
		<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right'><span class="leibie">*&nbsp;</span><?php echo '描述'; ?></label>
		<div class='am-u-lg-6 am-u-sm-6 am-u-md-6 select_p'>
			<textarea rows="4" id="desc" style="border-radius:5px;border-color:#7d7d7d;font-size:14px;" name="data[UserWork][description]"><?php echo isset($UserWork_data['UserWork'])?$UserWork_data['UserWork']['description']:''; ?></textarea>
		</div>
		<div class='am-cf'></div>
	</div>
	<div class="am-form-group">
		<label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right'>&nbsp;</label>
		<div class='am-u-lg-6 am-u-sm-6 am-u-md-9 am-text-left select_p'>
			<input type="hidden" name="backup" value="/user_resumes/index/?user_work=1" />
			<button  id="shangchuan_btn" type='submit' class='am-btn am-btn-primary am-radius'><?php echo '提交'; ?></button>
		</div>
		<div class='am-cf'></div>
	</div>
	<?php echo $form->end();?>
</div>
<style type='text/css'>
</style>
<script type='text/javascript'>
$(function(){
	$('.shangchuan').click(function(){
		$('#works_img').click();
	});
});

var file_key=$("ul.user_works_annex li").length-1;
function ajax_upload_files(file_input){
	var input_obj=$(file_input).parent();
	var fileList = file_input.files;
	var formData = new FormData(); 
	for( var i = 0 ; i < fileList.length ; i++ ){
		formData.append("works_img["+i+"]", fileList[i]);
	}
	var xhr = new XMLHttpRequest();
	xhr.open('POST',web_base+"/user_works/ajax_upload_files/");
	xhr.send(formData);
	//ajax返回
	xhr.onreadystatechange = function(){
		if ( xhr.readyState == 4 && xhr.status == 200 ) {
			eval('var result='+xhr.responseText);
			if(result.code=="1"){
				var filelist=result.file_list;
				for( var j = 0 ; j < filelist.length ; j++ ){
					file_key++;
					var file_path=filelist[j]['file_path'];
					var file_list=$(input_obj).find("ul.am-list");
	  				var file_html="<li style='border:none;' class='img_work'><div class='am-g'><div class='' style='float:left;'><div ><img src='/theme/default/img/jt2.png' onclick='zp_img_up(this)'></div><div style='margin-top:11px;'><img src='/theme/default/img/jt.png' onclick='zp_img_down(this)'></div><div style='margin-top:11px;'><a onclick='shangchuan_delete(this);'><img src='/theme/default/img/sc_3.png'></a></div></div><div class='am-u-lg-4 am-u-md-5 am-u-sm-5 am-text-center' style='padding:0 10px;float:left;'><img src='"+file_path+"' style='max-width:120px;max-height:85px;min-height:85px;' /><input type='hidden' name='data[UserWorksAnnex]["+file_key+"][id]'  value='0' /><input type='hidden' name='data[UserWorksAnnex]["+file_key+"][file_url]'  value='"+file_path+"'  class='field_orderby'/></div><div class='am-u-lg-7 am-hide-sm-only am-u-md-5' ><textarea name='data[UserWorksAnnex]["+file_key+"][name]'  value='"+file_path+"' maxlength='100'  style='border-radius:5px;width:345px;border-color:#7d7d7d;'></textarea></div></div></li>";
	  				$(file_list).append(file_html);
				}
			}else{
				alert(result.message);
			}
		}
	}
	//设置超时时间    
	xhr.timeout = 100000;
	xhr.ontimeout = function(event){
		alert('请求超时！');
	}
	/*
	var input_obj=$(file_input).parent();
	$.ajaxFileUpload({
		url:web_base+"/user_works/ajax_upload_files/",
		secureuri:false,
		fileElementId:'works_img',
		dataType: 'json',
		data:{},
	  	success: function (result){
	  		if(result.code=='1'){
	  			file_key++;
	  			var file_path=result.file_path;
	  			var file_list=$(input_obj).find("ul.am-list");
	  			var file_html="<li style='border:none;' class='img_work'><div class='am-g' style='padding-bottom:30px;'><div class='am-u-lg-1 am-u-md-2 am-u-sm-2 am-text-center'><div ><span class='am-icon-arrow-up up_class' onclick='zp_img_up(this)'></span></div><div style='margin-top:5px;'><span class='am-icon-arrow-down up_class'onclick='zp_img_down(this)'></span></div><div style='margin-top:20px;'><a class='delete_class' onclick='shangchuan_delete(this);'><span class='am-icon-remove '></span></a></div></div><div class='am-u-lg-4 am-u-sm-5 am-u-md-5 am-text-center'><img src='"+file_path+"' style='width:100px;height:100px;' /><input type='hidden' name='data[UserWorksAnnex]["+file_key+"][id]'  value='0' /><input type='hidden' name='data[UserWorksAnnex]["+file_key+"][file_url]'  value='"+file_path+"'  class='field_orderby'/></div><div class='am-u-lg-7 am-u-sm-5 am-u-md-5' style='padding:0 5px;'><textarea name='data[UserWorksAnnex]["+file_key+"][name]'  value='"+file_path+"' maxlength='100'  style='border-radius:5px;'></textarea></div></div></li>";
	  			$(file_list).append(file_html);
	  		}else{
	  			alert(result.message);
	  		}
	  	},
	  	error: function (data, status, e){//服务器响应失败处理函数
  	  		alert('上传失败');
  		}
	 });
	 */
}
function shangchuan_delete(obj)
{
	if(confirm(j_confirm_delete))$(obj).parents('.img_work').remove();
}
//排序
function zp_img_up(obj){

	var field_orderby=$(obj).parents('.img_work').find("input[type='hidden']").val();
	//alert(field_orderby);
	var field_div=$(obj).parents("li.img_work");
	var prevfield_div = field_div.prev();//前一个同级元素

	if(prevfield_div.hasClass('img_work')){
		var prevfield_orderby=$(prevfield_div).find("input.field_orderby").val();
		$(prevfield_div).find("input.group_orderby").val(field_orderby);
		$(obj).parent().find("input[type='hidden']").val(prevfield_orderby);
		prevfield_div.before(field_div.clone());
		field_div.remove();
	}

}

function zp_img_down(obj){
	var field_orderby=$(obj).parent().find("input[type='hidden']").val();
	var field_div=$(obj).parents("li.img_work");
	var nextfield_div = field_div.next();
		console.log(nextfield_div);
	if(nextfield_div.hasClass('img_work')){
		var nextfield_orderby=$(nextfield_div).find("input.field_orderby").val();
		$(nextfield_div).find("input.group_orderby").val(field_orderby);
		$(obj).parent().find("input[type='hidden']").val(nextfield_orderby);
		nextfield_div.after(field_div.clone());
		field_div.remove();
	}

}
$(function(){
	$('#shangchuan_btn').click(function(){
	if($('#hangyan').val()=='')
		{
		alert('请选择类别');
		return false;
		}else if($('.hangyan').eq(1).val()=='')
		{
		alert('标题不能为空');
		return false;
		}else if($('#desc').val()==''){
			alert('描述不能为空');
			return false;
		}else{
			return true;
		}
	});
});
</script>