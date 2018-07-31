<link href="<?php echo $webroot; ?>plugins/Jcrop/css/jquery.Jcrop.css" type="text/css" rel="stylesheet">
<script src="<?php echo $webroot; ?>plugins/Jcrop/js/jquery.Jcrop.js"></script>
<div class="am-edit-user-avatar">
	<?php echo $form->create('Users',array('action'=>'edit_headimg','class'=>"am-form",'id'=>"HeadImgForm","type"=>"post",'enctype'=>'multipart/form-data'));?>
	<div class="am-form-group am-margin-bottom-0">
		<label class='am-u-sm-3 am-text-right am-padding-top-xs'><?php echo $ld['upload_photos']; ?></label>
		<div class="am-u-sm-9">
			<button type="button" class='am-btn am-btn-default am-btn-xs'><span class='am-icon am-icon-upload'></span>&nbsp;选取图片<input type="file" accept="image/png,image/gif,image/jpg" id="UserAvatar" onchange="PreviewAvatar(this)"/></button>
		</div>
		<div class='am-cf'></div>
	</div>
	<div class="am-form-group  am-margin-bottom-0" >
		<label class='am-u-sm-3 am-text-right am-padding-top-xs'>&nbsp;</label>
		<div class="am-u-sm-9" id="PreviewAvatar"></div>
		<div class='am-cf'></div>
	</div>
	<div class="am-form-group">
		<label class='am-u-sm-3 am-text-right am-padding-top-xs'>&nbsp;</label>
		<div class="am-u-sm-9">
			<button type="button" class='am-btn am-btn-secondary am-btn-sm' onclick="ajax_submit_avatar(this)"><?php echo $ld['user_save'] ?></button>
			<button type="button" class='am-btn am-btn-default am-btn-sm' onclick="destroy_avatar(this)"><?php echo $ld['cancel'] ?></button>
		</div>
		<div class='am-cf'></div>
	</div>
	<?php echo $form->end();?>
</div>
<style type='text/css'>
#HeadImgForm button input[type='file']{position: absolute;height:100%;left: 0;top: 0;opacity: 0;filter: alpha(opacity=0);cursor: pointer;}
#PreviewAvatar{margin:1.5rem auto;}
#AvatarPreviewImg{max-width:100%;}
</style>
<script type="text/javascript">
var jcropApi=null;
function PreviewAvatar(input){
        if(jcropApi!=null){
		jcropApi.destroy();
		jcropApi=null;
        	$("#AvatarPreviewImg").remove();
	}
	var files = input.files;
	if (files && files.length){
	            for(var i=0;i<files.length;i++){
				var file = files[i];
				var file_name=file.name;
				var file_size=Math.round(file.size/1024/1024);
				if(file_size>3){
					alert('最大文件限制为3M,'+file_name+'当前为'+file_size+'M');
					return false;
				}
	            }
        }else{
            	return false;
        }
        handleFiles(input);
}

function handleFiles(obj){
	var files = obj.files;
	if(files.length > 0){
		$("#PreviewAvatar").append('<img src="" id="AvatarPreviewImg" />');
		//opera不支持createObjectURL/revokeObjectURL方法。需要用FileReader对象来处理
		var reader = new FileReader();
		reader.readAsDataURL(files[0]);
		reader.onload = function(e){
			var img = new Image();
			img.onload=function(e){
				document.getElementById('AvatarPreviewImg').src = img.src;
				var preview_width=$("#AvatarPreviewImg").width();
				var preview_height=$("#AvatarPreviewImg").height();
				var maxSize=preview_width<preview_height?preview_width:preview_height;
				if(preview_width>=150&&preview_height>=150){
					maxSize=maxSize>300?300:maxSize;
					var c = {"x":0,"y":0,"x2":130,"y2":130};
					$('#AvatarPreviewImg').Jcrop({
						bgFade: true,
						aspectRatio:1,
						minSize:[130,130],
						maxSize:[maxSize,maxSize],
						setSelect: [c.x,c.y,c.x2,c.y2]
					},function(){
						jcropApi=this;
					});
				}else if(preview_width!=preview_height&&(preview_width>100||preview_height>100)){
					var c = {"x":0,"y":0,"x2":100,"y2":100};
					$('#AvatarPreviewImg').Jcrop({
						bgFade: true,
						aspectRatio:1,
						minSize:[100,100],
						maxSize:[maxSize,maxSize],
						setSelect: [c.x,c.y,c.x2,c.y2]
					},function(){
						jcropApi=this;
					});
				}
			}
			img.src=this.result;
		}
	}
}

function dataURLtoBlob(dataurl) {
	var arr = dataurl.split(','), mime = arr[0].match(/:(.*?);/)[1],
	bstr = atob(arr[1]), n = bstr.length, u8arr = new Uint8Array(n);
	while(n--){
		u8arr[n] = bstr.charCodeAt(n);
	}
	return new Blob([u8arr], {type:mime});
}

function ajax_submit_avatar(btn){
	var post_data = new FormData();//提交数据
	var upload_file=document.getElementById("UserAvatar").files[0];
	if(typeof(upload_file)=='undefined')return;
	post_data.append("UserAvatar",upload_file);
	if(jcropApi!=null){
		post_data.append("dst_json",JSON.stringify(jcropApi.tellScaled()));
		post_data.append("preview_width",$("#AvatarPreviewImg").width());
		post_data.append("preview_height",$("#AvatarPreviewImg").height());
	}
	$(btn).button("loading");
	var xhr = null;
	if (window.XMLHttpRequest){// code for all new browsers
		xhr=new XMLHttpRequest();
	}else if (window.ActiveXObject){// code for IE5 and IE6
		xhr=new ActiveXObject("Microsoft.XMLHTTP");
	}else{
		alert("Your browser does not support XMLHTTP.");return false;
	}
	xhr.onreadystatechange = function(){
	            if (xhr.readyState == 4 && xhr.status == 200){
				$(btn).button("reset");
	            		eval("var result="+xhr.responseText);
	            		if(result.code=='1'){
	            			window.location.href=web_base+"/users/edit_headimg";
	            		}else{
	            			seevia_alert(result.message);
	            		}
	            }
	};
	xhr.onerror=function(evt){
		$(btn).button("reset");
		alert('上传失败');
	};
	xhr.open("POST", web_base+'/users/edit_headimg');
	xhr.send(post_data);
}

function destroy_avatar(){
	if(jcropApi!=null){
		jcropApi.destroy();
		jcropApi=null;
        	$("#AvatarPreviewImg").remove();
	}
	$("#UserAvatar").val('');
}
</script>