<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-product">
    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
        <?php echo $form->create('/user_tasks',array('action'=>'add','id'=>'user_task_add_form','name'=>'user_task_add','type'=>'POST','class'=>'am-form am-form-horizontal','onsubmit'=>"return check_all();"));?>
        <input type="hidden" name="data[UserTask][id]" id="_id" value="" />
        <div class="am-g">
            <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:<?php echo isset($configs['is_qa_environment'])&&$configs['is_qa_environment']=='1'?90:56; ?>,animation:'slide-top'}" style="height: 42px; margin: 0px 0px 8px;">
                <ul>
                    <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
                </ul>
            </div>
			<div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
				<input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['d_submit'];?>" />
				<input class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['d_reset']?>" />
			</div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Basic_information_pancel'}"><?php echo $ld['basic_information'] ?>&nbsp;</h4>
            </div>
            <div id="Basic_information_pancel" class="am-panel-collapse am-collapse am-in">
                <div id="basic_information" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">任务分组</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <?php foreach($task_group as $tid=>$t){?>
                                <label class="am-checkbox am-success" style="padding-top:0px">
                                    <input type="checkbox" class="checkbox" name="data[UserTaskGroup][id][]" value="<?php echo $t['UserTaskGroup']['id'];?>"  data-am-ucheck/>
                                    <?php echo $t["UserTaskGroup"]["name"];?>
                                </label>
                            <?php }?>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['code'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input type="text" onchange="check_code(this)" name="data[UserTask][code]" id="code" value=""></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input type="text" name="data[UserTask][name]" id="name" value=""></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">url</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="task_url" name="data[UserTask][task_url]" value=""/></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">经验值</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="task_experience_value" name="data[UserTask][task_experience_value]" value="0"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['status'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <label class="am-radio-inline"><input type="radio" name="data[UserTask][status]" value="1" checked/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[UserTask][status]" value="0"/>无效</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['description'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <textarea cols="80" id="elm" name="data[UserTask][description]" rows="10" style="width:auto;height:300px;"></textarea>
                            <script>
                                var editor;
                                KindEditor.ready(function(K) {
                                    editor = K.create('#elm', {width:'100%',
                                        langType : '',cssPath : '/css/index.css',filterMode : false});
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $form->end(); ?>
    </div>
</div>

<script>
    var code_check=true;
    function check_code(obj){
        code_check=false;
        var code=obj.value;
        if(code==""){return false;}
        $.ajax({url: admin_webroot+"user_tasks/check_code",
            type:"POST",
            data:{'code':code},
            dataType:"json",
            success: function(data){
                try{
                    if(data.code==1){
                        code_check=true;
                    }else{
                        alert(data.msg);
                    }
                }catch (e){
                    alert(j_object_transform_failed);
                }
            }
        });
    }

    function check_all(){
        if(code_check==false){
            alert("code已存在");
            return false;
        }
        var name_obj = document.getElementById("name");
        var code_obj = document.getElementById("code");
        if(code_obj.value==""){
            alert("编码不能为空");
            return false;
        }
        if(name_obj.value==""){
            alert("名称不能为空");
            return false;
        }
        return true;
    }
</script>