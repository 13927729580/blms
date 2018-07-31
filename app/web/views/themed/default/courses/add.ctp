<style>
.am-u-lg-2.am-u-md-2.am-u-sm-4.am-form-label{text-align: left;}
#accordion{font-size: 1.4rem;}
.am-product label{font-weight: normal;}
.am-selected.am-dropdown{width: 100%;}
.am-selected-content.am-dropdown-content{width: 100%;}
.am-selected-btn.am-btn.am-dropdown-toggle.am-btn-default{font-size: 1.4rem;}
.am-radio-inline{padding-top: 0!important;}
.am-u-lg-2.am-u-md-2.am-u-sm-12{margin-bottom: 10px;text-align: left;}
<?php if($organizations_id!=''){ ?>
.am-u-lg-3.am-u-md-3.am-u-sm-12.am-user-menu.am-hide-sm-only.am-padding-right-0{display: none!important;}
.am-u-lg-9.am-u-md-8.am-u-sm-12{width:100%;}
.am-btn.am-btn-sm.am-btn-secondary.am-show-sm-only{display:none!important;}
.am-u-lg-2.am-u-md-2.am-u-sm-2.am-panel-group.am-hide-sm-only{margin-right:5%;}
<?php } ?>
</style>
<script src="<?php echo $webroot.'plugins/kindeditor/kindeditor-min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<script src="<?php echo $webroot.'plugins/ajaxfileupload.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<div class="am-g am-g-fixed">
	<?php if($organizations_id!=''){ ?>
	<?php echo $this->element('organization_menu');?>
	<?php echo $this->element('org_menu')?>

	<button style="margin:10px 0;" class="am-btn am-btn-primary am-show-sm-only" data-am-offcanvas="{target: '#org_menu2', effect: 'push'}">组织菜单</button>
	<?php } ?>
	<div class="am-product <?php if($organizations_id!=''){echo 'am-u-lg-9';} ?>" style="padding:0;">
	    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
	        <div style="padding:10px;font-size: 20px;"></div>
	        <?php if($organizations_id!=''){$aa = '?organizations_id='.$organizations_id;}else{$aa = '';} ?>
	        <?php echo $form->create('/courses',array('action'=>'add'.$aa,'id'=>'course_add_form','name'=>'course_add','class'=>'am-form am-form-horizontal','type'=>'POST','onsubmit'=>"return chechk_form()"));?>
	        <input type="hidden" name="data[Course][id]" id="_id" value="" />
	        <input type="hidden" name="data[Course][user_id]" value="<?php echo $_SESSION['User']['User']['id'] ?>">
	        <input type="hidden" name="data[Course][code]" value="">
	        <div style="text-align:left;font-size:20px;padding-bottom:1rem;margin-top:1%;padding-left:5px;margin-bottom:1rem;border-bottom: 1px solid #ccc;" >
	          <span style="float:left;">添加课程</span>
	          <div class="am-cf"></div>
	        </div>
	        <div class="am-panel am-panel-default">
	            <div class="am-panel-hd">
	                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Basic_information_pancel'}"><?php echo '基本信息' ?></h4>
	            </div>
	            <div style="padding-top: 10px;" id="Basic_information_pancel" class="am-panel-collapse am-collapse am-in">
	                <div id="basic_information" class="scrollspy_nav_hid"></div>
	                <div class="am-panel-bd">
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label">课程类型</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-6">
	                            <select id="course_type_code" name="data[Course][course_type_code]" data-am-selected="{maxHeight:100}" onchange="course_type_code_select(this.value)">
	                                <option value=''><?php echo $ld['please_select'];?></option>
	                                <option value='-1'>自定义</option>
	                                <?php foreach ($course_type as $tid=>$t){ ?>
	                                    <option value="<?php echo $t['CourseType']['code'];?>"><?php echo $t['CourseType']['name'];?></option>
	                                <?php }?>
	                            </select>
	                        </div>
	                        <div id="course_type_code_zidingyi" class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="display: none;">
	                            <input type="text" style="padding:5px;" name="course_type_code_1">
	                        </div>
	                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label" style="padding-top: 3px;">可见性</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-12">
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Course][visibility]" value="0" checked>公开</label>
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Course][visibility]" value="2">限定</label>
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Course][visibility]" value="1">仅自己</label>
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label">课程分类</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-6">
	                            <select id="course_type_code" name="data[Course][course_category_code]" data-am-selected="{maxHeight:100}" onchange="course_category_code_select(this.value)">
	                                <option value=''><?php echo $ld['please_select'];?></option>
	                                <option value='-1'>自定义</option>
	                                <?php foreach ($course_category as $tid=>$t){ ?>
	                                    <option value="<?php echo $t['CourseCategory']['code'];?>"><?php echo $t['CourseCategory']['name'];?></option>
	                                <?php }?>
	                            </select>
	                        </div>
	                        <div id="course_category_code_zidingyi" class="am-u-lg-4 am-u-md-4 am-u-sm-4" style="display: none;">
	                            <input type="text" style="padding:5px;" name="course_category_code_1">
	                        </div>
	                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label">难度级别</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-6">
	                            <select name='data[Course][level]' data-am-selected>
	                                <option value=''><?php echo $ld['please_select'];?></option>
	                                <?php if(isset($resource_info['course_level'])&&sizeof($resource_info['course_level'])>0){foreach($resource_info['course_level'] as $k=>$v){ ?>
	                                    <option value="<?php echo $k; ?>"><?php echo $v; ?></option>
	                                <?php }} ?>
	                            </select>
	                        </div>
	                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label" style="padding-top: 13px;">课程名称</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8"><input type="text" name="data[Course][name]" id="name" value=""></div>
	                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label" style="padding-top: 3px;"><?php echo $ld['status'] ?></label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Course][status]" value="1" checked/>有效</label>
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Course][status]" value="0"/>无效</label>
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label" style="padding-top: 4px;">是否推荐</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Course][recommend_flag]" value="1" checked/>是</label>
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Course][recommend_flag]" value="0"/>否</label>
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label" style="padding-top: 9px;">图片</label>
	                        <div class="am-u-lg-10 am-u-md-10 am-u-sm-12 am-form-file">
	                            <div class="am-form-group am-form-file">
	                                <button type="button" class="am-btn am-btn-default am-btn-sm">
	                                <i class="am-icon-cloud-upload"></i> 选择要上传的图片</button>
	                                <span class="" style="font-size:12px;">(推荐尺寸150*150)</span>
	                                <input type="file" multiple name="org_logo" onchange="ajax_upload_media(this,this.id)" id="org_logo">
	                                <input type="hidden" multiple name="data[Course][img]" >
	                            </div>
	                            <?php if(isset($course_info['Course']['img'])&&$course_info['Course']['img']!=''){ ?>
	                            <figure data-am-widget="figure" class="am am-figure am-figure-default am-no-layout am-figure-zoomable" data-am-figure="{  pureview: 'true' }">
	                            <img style="max-height: 200px;max-width: 200px;" src="<?php echo $server_host.$course_info['Course']['img'] ?>" data-rel="<?php echo $server_host.$course_info['Course']['img'] ?>" alt="" id="img_logo" >
	                            </figure>
	                            <?php }else{ ?>
	                            <img src="" data-rel="" alt="" id="img_logo" style="display:none;max-width:100%;max-height: 200px;max-width: 200px;">
	                            <?php } ?>
	                        </div>
	                        <div class="am-cf"></div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label"><?php echo '简单描述' ?></label>
	                        <div class="am-u-lg-10 am-u-md-10 am-u-sm-12">
	                        	<textarea name="data[Course][meta_description]" rows="10" style="height:300px;"></textarea>
	                        </div>
	                        <div class="am-cf"></div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label"><?php echo '描述' ?></label>
	                        <div class="am-u-lg-10 am-u-md-10 am-u-sm-12">
	                            <textarea cols="30" id="elm" name="data[Course][description]" rows="10" style="height:300px;"></textarea>
	                            <script>
	                            var editor;
	                            KindEditor.ready(function(K) {
	                                editor = K.create('#elm', {width:'100%',
	                                    langType : 'zh-cn',filterMode : false,
	                                    items:['source','undo','redo','cut','paste','plainpaste','fontname','fontsize','forecolor','hilitecolor','bold','italic','underline','strikethrough','justifyleft','justifycenter','justifyright','indent','outdent']
	                                });
	                            });
	                            </script>
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label" style="padding-top: 13px;"><?php echo $ld['price'] ?></label>
	                        <div class="am-u-lg-2 am-u-md-3 am-u-sm-6"><input type="text" id="price" name="data[Course][price]" value="0"/></div>
	                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
	                    </div>
	                    <!-- <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label" style="padding-top: 13px;">总时长（分）</label>
	                        <div class="am-u-lg-2 am-u-md-3 am-u-sm-6"><input type="text" id="hour" name="data[Course][hour]" value="0"/></div>
	                    </div> -->
	                    <!-- <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">可获经验值</label>
	                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="experience_value" name="data[Course][experience_value]" value="0"/></div>
	                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-4 am-form-label">点击数</label>
	                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="text" id="clicked" name="data[Course][clicked]" value="0"/></div>
	                        <em style="position: relative; top: 10px; color: red; left: 10px;">*</em>
	                    </div> -->
	                    <input type="hidden" id="experience_value" name="data[Course][experience_value]" value="0"/>
	                    <input type="hidden" id="clicked" name="data[Course][clicked]" value="0"/>
	                    <div style="clear:both;"></div>
	                </div>
	            </div>
	        </div>
	        <div class="btnouter" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
	            <label class="am-u-lg-2 am-u-md-2 am-u-sm-12 am-form-label" style="padding-top: 9px;">&nbsp;</label>
	            <div class="am-u-lg-10 am-u-md-10 am-u-sm-12" style="padding-left:25px;">
	                <button type="submit" class="am-btn am-btn-success am-btn-sm am-btn-bottom am-radius">提交</button>
	                <button style="margin-right: 0;" type="reset" class="am-btn am-btn-default am-btn-sm am-btn-bottom am-radius">重置</button>
	            </div>
	        </div>
	        <?php echo $form->end(); ?>
	    </div>
	    <div class="am-cf"></div>
	</div>
</div>
<script>
    function chechk_form(){
        var name_obj = document.getElementById("name");
        if($('select[name="data[Course][course_type_code]"]').val()==''){
            seevia_alert("请选择课程类型");
            return false;
        }
        if($('select[name="data[Course][course_category_code]"]').val()==''){
            seevia_alert("请选择课程分类");
            return false;
        }
        if($('select[name="data[Course][level]"]').val()==''){
            seevia_alert("请选择难度级别");
            return false;
        }
        if(name_obj.value==""){
            seevia_alert("名称不能为空");
            return false;
        }
        return true;
    }

    function course_type_code_select(value){
        if(value=='-1'){
            $('#course_type_code_zidingyi').css('display','');
        }else{
            $('#course_type_code_zidingyi').css('display','none');
        }
    }

    function course_category_code_select(value){
        if(value=='-1'){
            $('#course_category_code_zidingyi').css('display','');
        }else{
            $('#course_category_code_zidingyi').css('display','none');
        }
    }

    function ajax_upload_media(obj,obj_id){
        if($(obj).val()!=""){
            var fileName_arr=$(obj).val().split('.');
            var fileType=fileName_arr[fileName_arr.length-1];
            var fileTypearray=Array('jpg','JPG','jpeg','JPEG','gif','GIF','png','PNG');
            ajaxFileUpload(obj_id);
            console.log(obj_id);
        }
    }

    function ajaxFileUpload(img_id){
        var org_id = 0;
        console.log(org_id);
        console.log(img_id);
        $.ajaxFileUpload({
            url:'/courses/ajax_upload_media',
            secureuri:false,
            fileElementId:img_id,
            data:{'org_id':org_id,'org_code':img_id},
            dataType: 'json',
            success: function (data){
                $('#'+img_id).siblings('input[type="hidden"]').val(data.img_url);
                var url = 'http://'+window.location.host+data.img_url;
                //alert(url);
                $("#img_logo").attr('src',url);
                $("#img_logo").attr('data-rel',url);
                $("#img_logo").show();
                console.log(data);
            }
        });
        return false;
    }

    $(document).ready(function(){
        if($(window).width()<600){
            $('#accordion').css('padding','0px');
        }else{
            $('#accordion').css('padding','0 12px');
        }
    })
    $(window).resize(function(){
        if($(window).width()<600){
            $('#accordion').css('padding','0px');
        }else{
            $('#accordion').css('padding','0 12px');
        }
    });
</script>