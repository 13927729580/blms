<style>
    .am-form-label {
        font-weight: bold;
        margin-left: 10px;
        top: 0px;
    }
    .am-form-group{margin-top:10px;}
</style>
<script src="<?php echo $webroot; ?>plugins/ajaxfileupload.js" type="text/javascript"></script>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-g admin-content am-course  ">
    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-detail-menu">
        <ul class="am-list admin-sidebar-list">
            <li><a data-am-collapse="{parent: '#accordion'}" href="#basic_information"><?php echo $ld['basic_information']?></a></li>
        </ul>
    </div>
    <?php echo $form->create('/order_product_medias',array('action'=>'add/'.$id,'id'=>'order_product_media_add_form','name'=>'order_product_media','type'=>'POST','onsubmit'=>""));?>
    <input type="hidden" name="data[OrderProductMedia][id]" id="_id" value="" />
    <input type="hidden" name="data[OrderProductMedia][order_product_id]" id="order_product_id" value="<?php echo $id; ?>" />
    <div class="am-panel-group admin-content" id="accordion">
        <!-- 编辑按钮区域 -->
        <div class="btnouter am-text-right" data-am-sticky="{top:'10%'}">
            <button type="submit" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_submit'] ?></button>
            <button type="reset" class="am-btn am-btn-success am-radius am-btn-sm am-btn-bottom"><?php echo $ld['d_reset'] ?></button>
        </div>
        <!-- 编辑按钮区域 -->
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#basic_information'}"><?php echo $ld['basic_information'] ?>&nbsp;</h4>
            </div>
            <div id="basic_information" class="am-panel-collapse am-collapse am-in">
                <div class="am-panel-bd am-form-detail am-form am-form-horizontal">
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['type']; ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8">
                                <div class="am-u-lg-6 am-u-md-6 am-u-sm-8">
					<select name='data[OrderProductMedia][type]' data-am-selected="{maxHeight:200}">
						<option value='0'><?php echo $ld['please_select']; ?></option>
						<option value="image">图片</option>
						<option value="video">视频</option>
					</select>
                            </div>
                	</div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label">媒体</label>
                        <div class="am-u-lg-9 am-u-md-8 am-u-sm-8">
                            <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="margin-top:10px;">
                                <input type="text" name="data[OrderProductMedia][media]" value="" />
                                <input type="file" onchange="ajax_upload_media(this)"/>
                                <div class="img_select" style="margin:5px;">
                                    <?php echo $html->image("",array('id'=>'show_img'))?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"><?php echo $ld['description'] ?></label>
                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-12">
                            <div class="am-u-lg-11 am-u-md-11 am-u-sm-12">
                                <div class="am-form-group">
                                    <textarea cols="80" id="elm" name="data[OrderProductMedia][description]" rows="10" style="width:auto;height:300px;"></textarea>
                                    <script>
                                        var editor;
                                        KindEditor.ready(function(K) {
                                            editor = K.create('#elm', {width:'100%',
                                                langType : "<?php echo isset($backend_locale_info['google_translate_code'])?$backend_locale_info['google_translate_code']:'zh-cn'; ?>",cssPath : '/css/index.css',filterMode : false});
                                        });
                                    </script>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $form->end();?>
</div>
<style type="text/css">
    .am-g.admin-content{margin:0 auto;}
    .am-form-label{text-align:right;}
    .am-form .am-form-group:last-child{margin-bottom:0;}
    #rank_operator select{width:50%;}
    #rank_operator em{float: left;margin: 0 5px;position: relative;top: 5px;}
    #rank_operator input[type="button"]{margin-right:1.2rem;}
</style>
<script type='text/javascript'>
function ajax_upload_media(input_file){
	var files = input_file.files;
	var post_data = new FormData();
	if (files){
		  var file = files[0];
                var file_name=file.name;
                var reader = new FileReader();//新建一个FileReader
                reader.readAsText(file, "UTF-8");//读取文件
                reader.onload = function(e){ //读取完文件之后会回来这里
                    var file_size=Math.round(e.total/1024/1024);
                    if(file_size>5){
                        alert('最大文件限制为5M,'+file_name+'当前为'+file_size+'M');
                        return false;
                    }
                }
                post_data.append("product_media",file);
	}else{
		return false;
	}
	post_data.append("order_product_id",$("#order_product_id").val());
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
                eval("var result="+xhr.responseText);
                if(result.code=='1'){
                	$(input_file).parent().find("input[type='text']").val(result.message);
                	$("#show_img").attr('src',result.message);
                	$(input_file).val('');
                }else{
                    alert(result.message);
                }
            }
        };
        xhr.onerror=function(evt){
            	console.log(j_object_transform_failed);
        };
        xhr.open("POST", admin_webroot+'order_product_medias/ajax_upload_media');
        xhr.send(post_data);
}
</script>