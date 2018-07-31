<style type="text/css">
    .am-radio input[type="radio"]{margin-left:0px;}
    .am-form-horizontal .am-radio{padding-top:0;margin-top:0.5rem;display:inline;position:relative;top:5px;}
    .img_select{max-width:150px;max-height:120px;}
    .am-selected-content.am-dropdown-content{width: 100%;}
</style>
<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-product">
    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
        <?php echo $form->create('CourseCategory',array('action'=>'view/'.(isset($this->data['CourseCategory']['id'])?$this->data['CourseCategory']['id']:"0"),'class'=>'am-form am-form-horizontal','onsubmit'=>'return check_all();'));?>
        <input id="CourseCategory_id" name="data[CourseCategory][id]" type="hidden" value="<?php echo isset($this->data['CourseCategory']['id'])?$this->data['CourseCategory']['id']:"";?>">
        <div class="am-g">
            <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:<?php echo isset($configs['is_qa_environment'])&&$configs['is_qa_environment']=='1'?90:56; ?>,animation:'slide-top'}" style="height: 42px; margin: 0px 0px 8px;">
                <ul>
                    <li><a href="#basic_information"><?php echo $ld['basic_information']?></a></li>
                    <li><a href="#advanced_set_up"><?php echo $ld['advanced'].$ld['set_up']?></a></li>
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
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">
                            <?php echo $ld['higher_category']?>
                        </label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <select id="CategoryParentId" name="data[CourseCategory][parent_id]" data-am-selected="width:200px;" >
                                <option value="0"><?php echo $ld['root']?></option>
                                <?php if(isset($categories_tree) && sizeof($categories_tree)>0){
                                    foreach($categories_tree as $k=>$v){?>
                                        <option value="<?php echo $v['id'];?>" <?php echo isset($this->data['CourseCategory']['parent_id'])&&$v['id']==$this->data['CourseCategory']['parent_id']?"selected":"";?> ><?php echo $v['name'];?></option>
                                        <?php if(isset($v['parent_id']) && sizeof($v['parent_id'])>0){
                                            foreach($v['parent_id'] as $kk=>$vv){?>
                                                <option value="<?php echo $vv['id'];?>" <?php echo isset($this->data['CourseCategory']['parent_id'])&&$vv['id']==$this->data['CourseCategory']['parent_id']?"selected":"";?> >|-- <?php echo $vv['name'];?></option>
                                                <?php if(isset($vv['parent_id']) && sizeof($vv['parent_id'])>0){foreach($v['parent_id'] as $kkk=>$vvv){?>
                                                    <option value="<?php echo $vvv['id'];?>" <?php echo isset($this->data['CourseCategory']['parent_id'])&&$vvv['id']==$this->data['CourseCategory']['parent_id']?"selected":"";?> >|--|-- <?php echo $vvv['name'];?></option>
                                                <?php }
                                                }
                                            }
                                        }
                                    }
                                }?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">
                            <?php echo $ld['category_name']?>
                        </label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <input id="category_name" name="data[CourseCategory][name]" type="text" value="<?php echo isset($this->data['CourseCategory']['name'])?$this->data['CourseCategory']['name']:'';?>">
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">
                            <?php echo $ld['meta_keywords']?>
                        </label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <input name="data[CourseCategory][meta_keywords]" type="text" value="<?php echo isset($this->data['CourseCategory']['meta_keywords'])?$this->data['CourseCategory']['meta_keywords']:'';?>">
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['r_description']?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <textarea cols="30" id="elm" name="data[CourseCategory][description]" rows="10" style="width:auto;height:300px;"><?php echo isset($this->data['CourseCategory']['description'])?$this->data['CourseCategory']['description']:'';?></textarea>
                            <script>
                                var editor;
                                KindEditor.ready(function(K) {
                                    editor = K.create('#elm', {
                                        width:'100%',
                                        cssPath : '/css/index.css',filterMode : false});
                                });
                            </script>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">
                            <?php echo $ld['display']?>
                        </label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <label class="am-radio am-success">
                                <input type="radio" name="data[CourseCategory][status]" data-am-ucheck value="1" <?php echo !isset($this->data['CourseCategory']['status'])||(isset($this->data['CourseCategory']['status'])&&$this->data['CourseCategory']['status']==1)?"checked":"";?> >
                                <?php echo $ld['yes']?>
                            </label>&nbsp;&nbsp;
                            <label class="am-radio am-success">
                                <input name="data[CourseCategory][status]" id="CategoryStatus" type="radio" data-am-ucheck value="0" <?php echo isset($this->data['CourseCategory']['status'])&&$this->data['CourseCategory']['status']==0?"checked":"";?> />
                                <?php echo $ld['no']?>
                            </label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">
                            <?php echo $ld['show_new']?>
                        </label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <label class="am-radio am-success">
                                <input type="radio" name="data[CourseCategory][new_show]" data-am-ucheck value="1" <?php echo !isset($this->data['CourseCategory']['new_show'])||(isset($this->data['CourseCategory']['new_show'])&&$this->data['CourseCategory']['new_show']==1)?"checked":"";?> />
                                <?php echo $ld['yes']?>
                            </label>&nbsp;&nbsp;
                            <label class="am-radio am-success">
                                <input name="data[CourseCategory][new_show]" type="radio" data-am-ucheck value="0" <?php echo isset($this->data['CourseCategory']['new_show'])&&$this->data['CourseCategory']['new_show']==0?"checked":"";?> />
                                <?php echo $ld['no']?>
                            </label>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">
                            <?php echo $ld['menu_icon']?>
                        </label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <input name="data[CourseCategory][img01]" type="text" id="category_01" value="<?php echo isset($this->data['CourseCategory']['img01'])?$this->data['CourseCategory']['img01']:'';?>" />
                            <input type="button" class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('category_01')" value="<?php echo $ld['choose_picture']?>" style="margin-top:5px;"/>
                            <div class="img_select" style="margin:5px;">
                                <?php echo $html->image((isset($this->data['CourseCategory']['img01'])&&$this->data['CourseCategory']['img01']!="")?$this->data['CourseCategory']['img01']:$configs['shop_default_img'],array('id'=>'show_category_01'))?>
                            </div>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">
                            <?php echo $ld['parent_category_image']; ?>
                        </label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <input name="data[CourseCategory][img02]" type="text" id="category_02" value="<?php echo isset($this->data['CourseCategory']['img02'])?$this->data['CourseCategory']['img02']:'';?>" />
                            <input type="button" class="am-btn am-btn-xs am-btn-success am-radius" onclick="select_img('category_02')" value="<?php echo $ld['choose_picture']?>"  style="margin-top:5px;"/>
                            <div class="img_select" style="margin:5px;">
                                <?php echo $html->image((isset($this->data['CourseCategory']['img02'])&&$this->data['CourseCategory']['img02']!="")?$this->data['CourseCategory']['img02']:$configs['shop_default_img'],array('id'=>'show_category_02'))?>
                            </div>
                        </div>
                    </div>
                    <div class="am-form-group" >
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">
                            <?php echo $ld['sort']?>
                        </label>
                        <div class="am-u-lg-3 am-u-md-4 am-u-sm-3">
                            <input name="data[CourseCategory][orderby]" type="text" value="<?php echo isset($this->data['CourseCategory']['orderby'])?$this->data['CourseCategory']['orderby']:'50';?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="am-panel am-panel-default">
            <div class="am-panel-hd">
                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Advanced_set_up_pancel'}"><?php echo $ld['advanced'].$ld['set_up']?></h4>
            </div>
            <div id="Advanced_set_up_pancel" class="am-panel-collapse am-collapse am-in">
                <div id="advanced_set_up" class="scrollspy_nav_hid"></div>
                <div class="am-panel-bd" id="style">
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo  $ld['code']?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <input id="category_code" name="data[CourseCategory][code]" type="text" value="<?php echo isset($this->data['CourseCategory']['code'])?$this->data['CourseCategory']['code']:'';?>" />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">
                            <?php echo  $ld['category_template']?>
                        </label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <select name="data[CourseCategory][template]" data-am-selected="{noSelectedText:'<?php echo $ld['please_select']; ?>'}">
                                <option value=''><?php echo $ld['please_select'] ?></option>
                                <?php
                                if(isset($Resource_info['course_category_template'])&&sizeof($Resource_info['course_category_template'])>0){
                                    foreach($Resource_info['course_category_template'] as $k=>$v){
                                        ?>
                                        <option value='<?php echo $k; ?>' <?php echo isset($this->data['CourseCategory']['template'])&&$this->data['CourseCategory']['template']==$k?'selected':''; ?>><?php echo $v; ?></option>
                                    <?php }} ?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">
                            <?php echo $ld['layout']?>
                        </label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <input type="text" name="data[CourseCategory][layout]" value="<?php echo isset($this->data['CourseCategory']['layout'])?$this->data['CourseCategory']['layout']:'';?>" />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">课程模板</label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <select name='data[CourseCategory][course_template]' data-am-selected="{noSelectedText:'<?php echo $ld['default']; ?>',maxHeight: 100}">
                                <option value=''><?php echo $ld['default'] ?></option>
                                <?php
                                if(isset($Resource_info['course_tamplate'])&&sizeof($Resource_info['course_tamplate'])>0){
                                    foreach($Resource_info['course_tamplate'] as $k=>$v){
                                        ?>
                                        <option value='<?php echo $k; ?>' <?php echo isset($this->data['CourseCategory']['course_template'])&&$this->data['CourseCategory']['course_template']==$k?'selected':''; ?>><?php echo $v; ?></option>
                                    <?php }} ?>
                            </select>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">
                            <?php echo $ld['routeurl']?>
                        </label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <input type="text" id="Route_url" onchange="checkrouteurl()" name="data[Route][url]" value="<?php echo isset($routecontent['Route']['url'])?$routecontent['Route']['url']:'';?>" placeholder="(<?php echo $ld['routeurl_desc'] ?>)" /><input type="hidden" id="route_url_h" value="0">
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label">
                            <?php echo $ld['jump_address']?>
                        </label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <input type="text" name="data[CourseCategory][link]" value="<?php echo isset($this->data['CourseCategory']['link'])?$this->data['CourseCategory']['link']:'';?>"  placeholder="(<?php echo $ld['page_url_desc'] ?>)"/>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['classification_tree_type'];?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <label class="am-radio am-success">
                                <input id="CategoryStatus" name="data[CourseCategory][tree_show_type]" data-am-ucheck type="radio" value="0" <?php echo isset($this->data['CourseCategory']['tree_show_type'])&&$this->data['CourseCategory']['tree_show_type']==0?"checked":"";?> ><?php echo $ld['type_top'];?>
                            </label>&nbsp;&nbsp;
                            <label class="am-radio am-success">
                                <input type="radio" name="data[CourseCategory][tree_show_type]" data-am-ucheck  value="1" <?php echo !isset($this->data['CourseCategory']['tree_show_type'])||(isset($this->data['CourseCategory']['tree_show_type'])&&$this->data['CourseCategory']['tree_show_type']==1)?"checked":"";?> ><?php echo $ld['same_level'];?>
                            </label>&nbsp;&nbsp;
                            <label class="am-radio am-success">
                                <input type="radio" name="data[CourseCategory][tree_show_type]" data-am-ucheck  value="2" <?php echo  isset($this->data['CourseCategory']['tree_show_type']) && $this->data['CourseCategory']['tree_show_type']==2?"checked":"";?> ><?php echo $ld['sub_grade'];?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $form->end(); ?>
    </div>
</div>

<script type="text/javascript">
    function check_all(){
        var category_code_obj = document.getElementById("category_code");
        if(category_code_obj.value==""){
            alert("编码不能为空");
            return false;
        }
        var category_name_obj = document.getElementById("category_name");
        if(category_name_obj.value==""){
            alert("分类名称不能为空");
            return false;
        }
        return true;
    }

    function productcat_input_checks(){
        var productcat_name_obj = document.getElementById("productcat_name_"+backend_locale);
        if(productcat_name_obj.value==""){
            alert("<?php echo $ld['enter_category_name']?>");
            return false;
        }
        return true;
    }
</script>