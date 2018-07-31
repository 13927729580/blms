<?php if(!empty($note_data)){?>
    <table class="am-table">
        <thead class="integral_list">
        <tr>
            <td width="20%" style="border-bottom:0px;">笔记内容</th>
            <td width="20%" style="border-bottom:0px;">笔记附件
            <td width="20%" style="border-bottom:0px;">回复内容</th>
            <td width="20%" style="border-bottom:0px;">提交文件</th>
            <td width="20%" style="border-bottom:0px;">操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($note_data)&&sizeof($note_data)>0){foreach($note_data as $k=>$v){?>
            <tr>
                <td style="line-height:2.5;"><textarea style="border-radius:3px;"><?php echo $v["CourseNote"]["note"]; ?></textarea></td>
                <td style="line-height:2.5;max-width: 50px;overflow: hidden;"><?php echo $v["CourseNote"]["media"]; ?></td>
                <td style="line-height:2.5;"><textarea style="border-radius:3px;" name='note_<?php echo $v["CourseNote"]["id"]; ?>'></textarea></td>
                <td style="line-height:2.5;">
                    <input type="text" name="ware_info_<?php echo $v['CourseNote']['id']; ?>" value="">
                    <input onchange="uploadcourse(this,<?php echo $v['CourseNote']['id']; ?>)" name="file_upload_<?php echo $v['CourseNote']['id']; ?>" size="40" type="file" style="height:22px;width: 100%;"/>
                </td>
                <td style="line-height:2.5;"><input type="button" class="am-btn am-btn-primary am-btn-sm am-radius" value="提交" onclick="ajax_modify_submit(this)"/></td>
            </tr>
        <?php }}?>
        </tbody>
    </table>
<?php }?>
<script type='text/javascript'>
    function ajax_modify_submit(btn){
        var postForm=$(btn).parents('form');
        var postData=postForm.serialize();
        $.ajax({
            url: web_base+"/users/ajax_modify_submit",
            type:"POST",
            data:postData,
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

    function uploadcourse(obj,id){
        var files = obj.files;
        var post_data = new FormData();
        if (files && files.length){
            for(var i=0;i<files.length;i++){
                var file = files[i];
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
                post_data.append("courseware[]",file);
            }
        }else{
            return false;
        }
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
                alert(result.message);
                if(result.code=='1'){
                    var name="note_"+id;
                    $("input[name='"+name+"']").val(result.message);
                    $(obj).val('');
                }else{
                    alert(result.message);
                }
            }
        };
        xhr.onerror=function(evt){
            console.log(j_object_transform_failed);
        };
        xhr.open("POST", admin_webroot+'course_classes/ajax_upload_course');
        xhr.send(post_data);
    }
</script>