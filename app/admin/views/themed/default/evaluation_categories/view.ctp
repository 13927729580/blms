<script src="<?php echo $webroot; ?>plugins/ajaxfileupload.js" type="text/javascript"></script>
<div class="am-product">
    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
        <?php echo $form->create('/evaluation_categories',array('action'=>'view/'.$evaluation_categories_info["EvaluationCategory"]["id"],'id'=>'evaluation_category_edit_form','class'=>'am-form am-form-horizontal','name'=>'evaluation_category_edit','type'=>'POST','onsubmit'=>"return chechk_form()"));?>
        <input type="hidden" name="data[EvaluationCategory][id]" id="_id" value="<?php echo $evaluation_categories_info['EvaluationCategory']['id'];?>" />
        <div class="am-g">
            <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:<?php echo isset($configs['is_qa_environment'])&&$configs['is_qa_environment']=='1'?90:56; ?>,animation:'slide-top'}" style="height: 42px; margin: 0px 0px 8px;">
                <ul>
                    <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
                </ul>
            </div>
            <div class="am-text-right save_action">
				<button type="submit" class="am-btn am-btn-success am-btn-sm am-btn-bottom am-radius"><?php echo $ld['d_submit'] ?></button>
				<button type="reset" class="am-btn am-btn-default am-btn-sm am-btn-bottom am-radius"><?php echo $ld['d_reset'] ?></button>
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
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['code'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[EvaluationCategory][code]" id="code" value="<?php echo $evaluation_categories_info['EvaluationCategory']['code'];?>"></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[EvaluationCategory][name]" id="name" value="<?php echo $evaluation_categories_info['EvaluationCategory']['name'];?>"></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['status'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[EvaluationCategory][status]" <?php if($evaluation_categories_info['EvaluationCategory']['status'] == 1){?>checked="checked"<?php }?> value="1"/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[EvaluationCategory][status]" <?php if($evaluation_categories_info['EvaluationCategory']['status'] == 0){?>checked="checked"<?php }?> value="0"/>无效</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $form->end(); ?>
    </div>
</div>
<script>
    function chechk_form(){
        var name_obj = document.getElementById("name");
        var code_obj = document.getElementById("code");
        if(code_obj.value==""){
            alert("编码不能为空");
            return false;
        }
        if(code_obj.value.length>20){
            alert("编码最大为20个字符");
            return false;
        }
        if(name_obj.value==""){
            alert("标题不能为空");
            return false;
        }
        if(name_obj.value.length>10){
            alert("标题最大为10个中文字符");
            return false;
        }
        return true;
    }
</script>