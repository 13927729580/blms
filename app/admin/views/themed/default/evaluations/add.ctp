<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-product">
    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
        <?php echo $form->create('/evaluations',array('action'=>'add','id'=>'evaluation_add_form','name'=>'evaluation_add','type'=>'POST','class'=>'am-form am-form-horizontal','onsubmit'=>"return check_all();"));?>
        <input type="hidden" name="data[Evaluation][id]" id="_id" value="" />
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
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">评测类型</label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <select id="course_type_code" name="data[Evaluation][evaluation_category_code]">
                                <option value=''><?php echo $ld['please_select'];?></option>
                                <?php foreach ($evaluation_category as $tid=>$t){ ?>
                                    <option value="<?php echo $t['EvaluationCategory']['code'];?>"><?php echo $t['EvaluationCategory']['name'];?></option>
                                <?php }?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['type'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
				<label class="am-radio-inline am-success am-padding-top-0"><input data-am-ucheck type="radio" name="data[Evaluation][evaluation_type]" value="0" checked />练习</label>
				<label class="am-radio-inline am-success am-padding-top-0"><input data-am-ucheck type="radio" name="data[Evaluation][evaluation_type]" value="1"/>考试</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['code'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" onchange="check_code(this)" name="data[Evaluation][code]" id="code" value=""></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[Evaluation][name]" id="name" value=""></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">图片</label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <input id="img" type="text" name="data[Evaluation][img]" value="" />
                            <input type="button" class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('img')" value="<?php echo $ld['choose_picture']?>"  style="margin-top:5px;"/>
                            <div class="img_select" style="margin:5px;">
                                <?php echo $html->image("",array('id'=>'show_img'))?>
                            </div>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">评测时间(分钟,0:无限制)</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="evaluation_time" name="data[Evaluation][evaluation_time]" value="0"/></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">离开界面限制次数</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="blur_time_limit" name="data[Evaluation][blur_time_limit]" value="0"/></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">及格分数</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="pass_score" name="data[Evaluation][pass_score]" value="60"/></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">点击数</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="clicked" name="data[Evaluation][clicked]" value="0"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['status'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[Evaluation][status]" value="1" checked/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[Evaluation][status]" value="0"/>无效</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">显示正确答案</label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[Evaluation][show_right_answer]" value="1" checked/>是</label>
                            <label class="am-radio-inline"><input type="radio" name="data[Evaluation][show_right_answer]" value="0"/>否</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['description'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            	<?php echo $this->element('editor',array('editorName'=>"data[Evaluation][description]",'editorId'=>'elm')); ?>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">价格</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="price" name="data[Evaluation][price]" value="0.00"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
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
        $.ajax({url: admin_webroot+"evaluations/check_code",
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
            alert("编码已存在");
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