<style>
.am-u-lg-2.am-u-md-2.am-u-sm-4.am-form-label{text-align: left;}
</style>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-product">
    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
        <?php echo $form->create('/courses',array('action'=>'add','id'=>'course_add_form','name'=>'course_add','class'=>'am-form am-form-horizontal','type'=>'POST','onsubmit'=>"return chechk_form()"));?>
        <input type="hidden" name="data[Course][id]" id="_id" value="" />
        <!-- 导航 -->
        <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
            <ul>
                <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
            </ul>
        </div>

        <div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
            <button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-btn-bottom am-radius"><?php echo $ld['d_submit'] ?></button>
            <button style="margin-right: 0;" type="reset" class="am-btn am-btn-default am-btn-sm am-btn-bottom am-radius"><?php echo $ld['d_reset'] ?></button>
        </div>
        <!-- 导航结束 -->
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Basic_information_pancel'}"><?php echo $ld['basic_information']?></h4>
            </div>
            <div id="Basic_information_pancel" class="am-panel-collapse am-collapse am-in">
                <div id="basic_information" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">课程类型</label>
                        <div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
                            <select id="course_type_code" name="data[Course][course_type_code]" data-am-selected>
                                <option value=''><?php echo $ld['please_select'];?></option>
                                <?php foreach ($course_type as $tid=>$t){ ?>
                                    <option value="<?php echo $t['CourseType']['code'];?>"><?php echo $t['CourseType']['name'];?></option>
                                <?php }?>
                            </select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">课程分类</label>
                        <div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
                            <select id="course_type_code" name="data[Course][course_category_code]" data-am-selected>
                                <option value=''><?php echo $ld['please_select'];?></option>
                                <?php foreach ($course_category as $tid=>$t){ ?>
                                    <option value="<?php echo $t['CourseCategory']['code'];?>"><?php echo $t['CourseCategory']['name'];?></option>
                                <?php }?>
                            </select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">难度级别</label>
                        <div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
                            <select name='data[Course][level]' data-am-selected>
                                <option value=''><?php echo $ld['please_select'];?></option>
                                <?php if(isset($resource_info['course_level'])&&sizeof($resource_info['course_level'])>0){foreach($resource_info['course_level'] as $k=>$v){ ?>
                                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
                                <?php }} ?>
                            </select>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><?php echo $ld['code'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[Course][code]" id="code" value=""></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5"><input type="text" name="data[Course][name]" id="name" value=""></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><?php echo $ld['status'] ?></label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[Course][status]" value="1" checked/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[Course][status]" value="0"/>无效</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">是否推荐</label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[Course][recommend_flag]" value="1" checked/>是</label>
                            <label class="am-radio-inline"><input type="radio" name="data[Course][recommend_flag]" value="0"/>否</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><?php echo $ld['description'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                        		<?php echo $this->element('editor',array('editorName'=>"data[Course][description]",'editorId'=>'elm')); ?>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">是否公开</label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[Course][allow_public]" value="1" checked />是</label>
                            <label class="am-radio-inline"><input type="radio" name="data[Course][allow_public]" value="0"/>否</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">允许报名</label>
                        <div class="am-u-lg-6 am-u-md-5 am-u-sm-5">
                            <label class="am-radio-inline"><input type="radio" name="data[Course][allow_learning]" value="1" checked />是</label>
                            <label class="am-radio-inline"><input type="radio" name="data[Course][allow_learning]" value="0"/>否</label>
                            <label class="am-radio-inline"><input type="radio" name="data[Course][allow_learning]" value="2"/>无需报名</label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">图片</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <input id="img" type="text" name="data[Course][img]" value="" />
                            <input type="button" class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('img')" value="<?php echo $ld['choose_picture']?>"  style="margin-top:5px;"/>
                            <div class="img_select" style="margin:5px;">
                                <?php echo $html->image("",array('id'=>'show_img'))?>
                            </div>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label"><?php echo $ld['price'] ?></label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="price" name="data[Course][price]" value="0"/></div>
                        <div class='am-u-lg-4 am-u-md-5 am-u-sm-5 am-padding-top-xs'>
                            <label class="am-radio-inline"><input type="radio" name="data[Course][must_buy]" checked value="0"/>满足条件或购买</label>
                        	<label class="am-radio-inline"><input type="radio" name="data[Course][must_buy]"  value="1"/>满足条件并购买</label>
                        </div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">总时长（分）</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="hour" name="data[Course][hour]" value="0"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">可获经验值</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="experience_value" name="data[Course][experience_value]" value="0"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">点击数</label>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="clicked" name="data[Course][clicked]" value="0"/></div>
                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
                    </div>
                    <div style="clear:both;"></div>
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
        if(name_obj.value==""){
            alert("名称不能为空");
            return false;
        }
        return true;
    }
</script>