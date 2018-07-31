<?php if(!empty($note_data)){
    foreach($note_data as $k=>$v){?>
        <input type="hidden" name="ids[]" value="<?php echo $v['CourseNote']['id']; ?>"/>
        <div class="am-form-group">
            <label class='am-u-lg-6 am-u-sm-4 am-u-md-3 am-text-left'>笔记内容:</label>
            <div class='am-cf'></div>
        </div>
        <div class="am-form-group">
            <div class='am-u-lg-6 am-u-sm-8 am-u-md-6'>
                <p style="border-radius:3px;text-align:left;" id='note_<?php echo $v["CourseNote"]["id"]; ?>'><?php echo $v['CourseNote']['note']; ?></p>
                <?php
                    $assignment_media="am-icon-eye";
                    if(preg_match("/(audio|video)\/(.*)$/",mime_content_type(WWW_ROOT.$v['CourseNote']['media']))){
                        $assignment_media="am-icon-youtube-play";
                    }else if(preg_match("/(image|IMAGE)\/(.*)$/",mime_content_type(WWW_ROOT.$v['CourseNote']['media']))){
                        $assignment_media="am-icon-image";
                    }
                    if(isset($v['CourseNote']['media'])&&trim($v['CourseNote']['media'])!=''&&file_exists(WWW_ROOT.$v['CourseNote']['media'])&&is_file(WWW_ROOT.$v['CourseNote']['media'])){ ?><a href='javascript:void(0);' class='am-fl assignment_file_preview' onclick="PreviewMedia ('<?php echo $v['CourseNote']['media']; ?>','<?php echo mime_content_type(WWW_ROOT.$v['CourseNote']['media']); ?>')"><i class="<?php echo 'am-icon '.$assignment_media; ?>"></i></a><?php } ?><a href='javascript:void(0);' class='am-fl' style='display:none;' data-am-modal="{target: '#Media', closeViaDimmer: 0}"><i class='am-icon'></i></a>
            </div>
            <div class='am-u-lg-6 am-u-sm-2 am-u-md-6'>
                <?php if(!empty($v[$v['CourseNote']['id']])){?>
                    <input type="button" onclick="showNoteInfo(<?php echo $v['CourseNote']['id']; ?>)" class="am-btn am-btn-primary am-btn-sm am-radius" value="编辑"/>
                    <input type="button" onclick="deleteInfo(<?php echo $v[$v['CourseNote']['id']]['CourseNoteReply']['id'];; ?>)" class="am-btn am-btn-danger am-btn-sm am-radius" value="删除"/>
                <?php }else{?>
                    <input type="button" onclick="showNoteInfo(<?php echo $v['CourseNote']['id']; ?>)" class="am-btn am-btn-primary am-btn-sm am-radius" value="回复"/>
                <?php }?>
            </div>
            <div class='am-cf'></div>
        </div>
        <div class="am-form-group" id="show_<?php echo $v['CourseNote']['id']; ?>">
            <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-text-left" style="padding-top:21px;padding-right:0px;">回复内容:</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-8">
                <textarea style="border-radius:3px;overflow:hidden;resize:none" name='note_<?php echo $v["CourseNote"]["id"]; ?>'><?php echo empty($v[$v['CourseNote']['id']])?'':$v[$v['CourseNote']['id']]['CourseNoteReply']['content'];?></textarea>
            </div>
            <input type="hidden" name="id_<?php echo $v['CourseNote']['id']; ?>" value="<?php echo empty($v[$v['CourseNote']['id']])?0:$v[$v['CourseNote']['id']]['CourseNoteReply']['id'];?>">
        </div>
        <div class='am-form-group' id="file_<?php echo $v['CourseNote']['id']; ?>">
            <div class='am-u-lg-8 am-padding-xs'><input type='file' name='ware_info_<?php echo $v["CourseNote"]["id"]; ?>' class='am-fl' onchange='loadAssignmentMedia(this)' accept="audio/*,video/*,image/*,application/pdf,application/pdf,application/zip" /><?php
                $assignment_media="am-icon-eye";
                if(preg_match("/(audio|video)\/(.*)$/",mime_content_type(WWW_ROOT.$v[$v['CourseNote']['id']]['CourseNoteReply']['media']))){
                    $assignment_media="am-icon-youtube-play";
                }else if(preg_match("/(image|IMAGE)\/(.*)$/",mime_content_type(WWW_ROOT.$v[$v['CourseNote']['id']]['CourseNoteReply']['media']))){
                    $assignment_media="am-icon-image";
                }
                if(isset($v[$v['CourseNote']['id']]['CourseNoteReply']['media'])&&trim($v[$v['CourseNote']['id']]['CourseNoteReply']['media'])!=''&&file_exists(WWW_ROOT.$v[$v['CourseNote']['id']]['CourseNoteReply']['media'])&&is_file(WWW_ROOT.$v[$v['CourseNote']['id']]['CourseNoteReply']['media'])){ ?><a href='javascript:void(0);' class='am-fl assignment_file_preview' onclick="PreviewMedia('<?php echo $v[$v['CourseNote']['id']]['CourseNoteReply']['media']; ?>','<?php echo mime_content_type(WWW_ROOT.$v[$v['CourseNote']['id']]['CourseNoteReply']['media']); ?>')"><i class="<?php echo 'am-icon '.$assignment_media; ?>"></i></a><?php } ?><a href='javascript:void(0);' class='am-fl' style='display:none;' data-am-modal="{target: '#Media', closeViaDimmer: 0}"><i class='am-icon'></i></a></div>
        </div>
        <div class="am-form-group" id="submit_<?php echo $v['CourseNote']['id']; ?>" style="display:none">
            <div class='am-u-lg-6 am-u-sm-8 am-u-md-6'>
                <input type="button" class="am-btn am-btn-sm am-btn-success am-radius" value="提交" onclick="ajax_modify_submit(this)"/>
            </div>
            <div class='am-cf'></div>
        </div>
    <?php }
}?>

<script type='text/javascript'>

     function showNoteInfo(id){
	     //$("#show_"+id).show();
	     //$("#file_"+id).show();
	     $("#submit_"+id).show();
     }

    function PreviewMedia(mediaPath,mediaMimeType){
        $("#Media div.am-modal-bd *").hide();
        $("#Media div.am-modal-bd video,#Media div.am-modal-bd img").attr('src','');
        if (typeof(mediaMimeType)!='undefined'&&/(audio|video)\/(.*)$/.test(mediaMimeType)){
            $("#Media div.am-modal-bd video").attr("src",mediaPath).show();
            $("#Media").modal();
        }else if(typeof(mediaMimeType)!='undefined'&&/(image|IMAGE)\/(.*)$/.test(mediaMimeType)){
            $("#Media div.am-modal-bd img").attr("src",mediaPath).show();
            $("#Media").modal();
        }else{
            window.open(mediaPath);
        }
    }

    function loadAssignmentMedia(fileBox){
        $(fileBox).parent().find('a.assignment_file_preview').hide();
        var prebtn=$(fileBox).parent().find('a:last-child');
        $(prebtn).hide();
        var uploadfile=fileBox.files[0];
        var uploadfileType=uploadfile.type;
        var reader = new FileReader();
        reader.readAsText(uploadfile, 'UTF-8');
        reader.onload = function (e) {
            if(reader.readyState==2){//加载完成
                var fileSize=Math.round(e.total/1024/1024/1024);
                if(fileSize>10){
                    alert('最大文件限制为10M,当前为'+fileSize+'M');
                    return false;
                }
                $("#Media div.am-modal-bd *").hide();
                $("#Media div.am-modal-bd video,#Media div.am-modal-bd img").attr('src','');
                if (/(audio|video)\/(.*)$/.test(uploadfileType)){
                    var fileResult = reader.result;
                    $(prebtn).find('i').removeClass('am-icon-image').addClass('am-icon-youtube-play');
                    $(prebtn).show();
                    $("#Media div.am-modal-bd video").attr("src", window.URL.createObjectURL(uploadfile)).show();
                }else if(/(image|IMAGE)\/(.*)$/.test(uploadfileType)){
                    $(prebtn).find('i').removeClass('am-icon-youtube-play').addClass('am-icon-image');
                    $(prebtn).show();
                    $("#Media div.am-modal-bd img").attr("src", window.URL.createObjectURL(uploadfile)).show();
                }
            }
        }
    }

    function ajax_modify_submit(btn){
        var postForm=$(btn).parents('form');
        var formData= new FormData();
        $.each(postForm.serializeArray(),function (i,field) {
            formData.append(field.name,field.value);
        });
        var assignmentFileList=$(postForm).find("input[type='file']");
        $.each(assignmentFileList,function (i,field) {
            var assignmentFile=assignmentFileList[i].files;
            formData.append(field.name,assignmentFile[0]);
        });
        $(btn).button('loading');
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
                $(btn).button('reset');
                eval("var result="+xhr.responseText);
                window.location.reload();
                if(result.code=='1'){
                    seevia_alert(result.message);
                }
            }
        };
        xhr.onerror=function(evt){
            console.log(j_object_transform_failed);
            $(addBtn).button('reset');
        };
        xhr.open("POST", web_base+"/users/ajax_modify_submit");
        xhr.send(formData);
    }

    function ajax_course_assignment(btn){
        var postForm=$(btn).parents('form');
        var assignment_content=$(postForm).find('textarea').val();
        var formData= new FormData();
        $.each(postForm.serializeArray(),function (i,field) {
            formData.append(field.name,field.value);
        });
        var assignmentFileList=$(postForm).find("input[type='file']");
        if(assignmentFileList.length>0){
            var assignmentFile=assignmentFileList[0].files;
            if(assignmentFile.length>0)formData.append('AssignmentMedia',assignmentFile[0]);
        }
        if(assignment_content==''&&((assignmentFileList.length>0&&assignmentFileList.val()=='')||assignmentFileList.length==0)){
            seevia_alert('请填写内容');
            return;
        }
        $(btn).button('loading');
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
                $(btn).button('reset');
                eval("var result="+xhr.responseText);
                window.location.reload();
                if(result.code=='1'){
                    seevia_alert(result.message);
                }
            }
        };
        xhr.onerror=function(evt){
            console.log(j_object_transform_failed);
            $(addBtn).button('reset');
        };
        xhr.open("POST", web_base+"/courses/ajax_course_assignment");
        xhr.send(formData);
    }

    function deleteInfo(id){
        $.ajax({
            url: web_base+"/users/delete_submit",
            type:"POST",
            data:{'id':id},
            dataType:"json",
            success: function(data){
                if(data.code=='1'){
                    alert(data.message);
                    window.location.reload();
                }else{
                    alert(data.message);
                }
            }
        });
    }
</script>