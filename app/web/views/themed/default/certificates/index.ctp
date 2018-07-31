<style>.am-g-fixed, pre{line-height: 0.6;}</style>
<div class="am-g am-group am-g-fixed" style="margin-bottom: 45px;width: 56%;border:2px solid #B9B9B9;padding:3px;min-width: 300px;margin-top:10px;">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tbody><tr>
										<td width="27"><img src="/theme/default/images/zhengshu1.gif" width="27" height="27"></td>
										<td width="198" background="/theme/default/images/zhengshu3.jpg" style=" font-size: 9pt;
    color: #000000;">国际认证证书查询</td>
										<td width="38"><img src="/theme/default/images/zhengshu2.gif"></td>
										<td background="/theme/default/images/zhengshu4.gif" align="right">&nbsp;</td>
									</tr>
								</tbody></table>
	<div style="background:white;margin-top: 11px;margin-bottom: 10px;">
	<br>
		<p style="margin: 0 auto;font-family:宋体,Arial;font-size:16px;font-style:normal;font-variant:normal;font-weight:bold;
" align="center">国际认证证书查询</p>
	</div>
	<br>
	<div style="background:white">
	<div style="margin: 0 auto;">
		<div  class="am-u-sm-12 am-u-md-6 am-u-lg-6"><img class="" style="margin-top:5px;max-width:100%;" src="/theme/default/images/certificates1.png"></div>
		<div class="am-u-sm-12 am-u-md-6 am-u-lg-6"><img  style="margin-top:5px;max-width:100%;" src="/theme/default/images/certificates2.png"></div>
		<div style="clear:both;"></div>
	</div>
	</div>
	<form method='post' class='am-form am-form-horizontal' style="background:white">
		<ul class=" am-avg-lg-3 am-avg-md-2 am-avg-sm-1 am-text-center" style="margin: 0 auto;margin-top: 10px;">
			<li  style="margin:0 0 10px 0;"  class="am-form-group am-avg-sm-12">
				<label  class="am-radio-inline" style="margin-top: 6px;" >
				<input type="radio" name="certificate_type" value="0" class="" checked data-am-ucheck>
				<span style="margin-top: -8px;display: block;"><?php echo '身份证号码';?></span></label>
				<label  class="am-radio-inline" style="margin-top: 6px;" >
				<input type="radio" name="certificate_type" value="1" class="" data-am-ucheck>
				<span style="margin-top: -8px;display: block;"><?php echo '证书编号';?></span></label>
			</li>
			<li  class="am-u-lg-12  am-u-md-12 am-u-sm-12" style="margin:0 0 10px 0;">
				<div class="am-u-lg-12  am-u-md-7 am-u-sm-12"  >
					<input type="text" style="" name="certificate_number" class="am-form-field am-radius am-u-md-12"  value="" />
				</div>
			</li>
			<li  style="margin:0 0 10px 0;width: 100px;">
				<label class="am-hide-lg-only am-u-md-3 am-u-sm-3 am-form-label-text ">&nbsp;</label> 
				<div class="am-u-lg-7 am-u-md-7 am-u-sm-7">
					<button type="button" class="am-btn am-btn-warning am-radius" onclick="certificate_search(this)"><?php echo $ld['query'];?></button>
				</div>
			</li>
    		</ul>
	</form>
	<div id="certificate_list" class="am-g am-g-fixed"></div>
</div>

<hr style="border: 1px solid #c7c7c7;">
<div class="am-hide-sm-only am-g-fixed" style="width:60%;margin-left:21%;height:350px;margin-top:40px;margin-bottom:40px;border:2px solid #B9B9B9;padding:3px;">
<table width="100%" border="0" cellspacing="0" cellpadding="0">
									<tbody><tr>
										<td width="27"><img src="/theme/default/images/zhengshu1.gif" width="27" height="27"></td>
										<td width="198" background="/theme/default/images/zhengshu3.jpg" style=" font-size: 9pt;
    color: #000000;">职业技能鉴定成绩查询</td>
										<td width="38"><img src="/theme/default/images/zhengshu2.gif"></td>
										<td background="/theme/default/images/zhengshu4.gif" align="right">&nbsp;</td>
									</tr>
								</tbody></table>
<iframe style="height:300px;width:100%"   src="http://www.12333sh.gov.cn/wsbs/zypxjd/jnjd/jdcx/new_cjcx.jsp"></iframe>
</div>
<script type='text/javascript'>
function certificate_search(btn){
	var post_form=$(btn).parents('form');
	var post_data=post_form.serialize();
	var search_flag=false;
	post_form.find("input[type='text']").each(function(){
		if($(this).val()!='')search_flag=true;
	});
	if(search_flag){
		$.ajax({
	            type: "POST",
	            url: web_base+"/certificates/ajax_certificate_list",
	            data: post_data,
	            dataType:"html",
	            success: function (data) {
	            	$('#certificate_list').html(data);
	            },
	            error: function (xhr, ajaxOptions, thrownError) {
	                	alert("Operation failure! Status=" + xhr.status + " Message=" + thrownError);
	            }
	        });
    	}
}
</script>