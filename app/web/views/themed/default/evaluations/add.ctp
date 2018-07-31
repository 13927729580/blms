<style>
.am-u-lg-2.am-u-md-2.am-u-sm-4.am-form-label{text-align: left;}
#accordion{font-size: 1.4rem;}
.am-product label{font-weight: normal;}
.am-selected.am-dropdown{width: 100%;}
.am-selected-content.am-dropdown-content{width: 100%;}
.am-selected-btn.am-btn.am-dropdown-toggle.am-btn-default{font-size: 1.4rem;}
.am-radio-inline{padding-top: 0!important;}
.am-u-lg-3.am-u-md-3.am-u-sm-12{margin-bottom: 10px;text-align: left;}
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
	        <?php if($organizations_id!=''){$aa = '?organizations_id='.$organizations_id;}else{$aa = '';} ?>
	        <?php echo $form->create('/evaluations',array('action'=>'add'.$aa,'id'=>'evaluation_add_form','name'=>'evaluation_add','type'=>'POST','class'=>'am-form am-form-horizontal','onsubmit'=>"return check_all();"));?>
	        <input type="hidden" name="data[Evaluation][user_id]" value="<?php echo $_SESSION['User']['User']['id'] ?>">
	        <input type="hidden" name="data[Evaluation][id]" id="_id" value="" />
	        <div style="text-align:left;font-size:20px;padding-bottom:1rem;margin-top:1%;padding-left:5px;margin-bottom:1rem;border-bottom: 1px solid #ccc;" >
	          <span style="float:left;">添加评测</span>
	          <div class="am-cf"></div>
	        </div>
	        <div class="am-panel am-panel-default">
	            <div class="am-panel-hd">
	                <h4 class="am-panel-title" data-am-collapse="{parent: '#accordion', target: '#Basic_information_pancel'}">基本信息&nbsp;</h4>
	            </div>
	            <div style="padding-top: 10px;" id="Basic_information_pancel" class="am-panel-collapse am-collapse am-in">
	                <div id="basic_information" class="scrollspy_nav_hid"></div>
	                <div class="am-panel-bd">
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label">评测类型</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-6">
	                            <select data-am-selected="{maxHeight:100}" id="course_type_code" name="data[Evaluation][evaluation_category_code]" onchange="evaluation_category_code_select(this.value)">
	                                <option value=''><?php echo $ld['please_select'];?></option>
	                                <option value='-1'>自定义</option>
	                                <?php foreach ($evaluation_category as $tid=>$t){ ?>
	                                    <option value="<?php echo $t['EvaluationCategory']['code'];?>"><?php echo $t['EvaluationCategory']['name'];?></option>
	                                <?php }?>
	                            </select>
	                        </div>
	                        <div id="evaluation_category_code_zidingyi" class="am-u-lg-3 am-u-md-3 am-u-sm-3" style="display: none;">
	                            <input type="text" style="padding:5px;" name="evaluation_category_code_1">
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 3px;">可见性</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8">
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][visibility]" value="0" checked>公开</label>
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][visibility]" value="2">限定</label>
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][visibility]" value="1">仅自己</label>
	                        </div>
	                    </div>
	                    <!-- <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 13px;">编码</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8"><input type="text" onchange="check_code(this)" name="data[Evaluation][code]" id="code" value=""></div>
	                    </div> -->
	                    <input type="hidden" name="data[Evaluation][code]" id="code" value="">
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 13px;">名称</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8"><input type="text" name="data[Evaluation][name]" id="name" value=""></div>
	                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label">评测时间(分钟,0:无限制)</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-6"><input type="text" id="evaluation_time" name="data[Evaluation][evaluation_time]" value="0"/></div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label">离开界面限制次数</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-6"><input type="text" id="blur_time_limit" name="data[Evaluation][blur_time_limit]" value="0"/></div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 13px;">及格分数</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-6"><input type="text" id="pass_score" name="data[Evaluation][pass_score]" value="60"/></div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 13px;">点击数</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-6"><input type="text" id="clicked" name="data[Evaluation][clicked]" value="0"/></div>
	                        <!-- <em style="position: relative; top: 10px; color: red; left: 10px;">*</em> -->
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 3px;"><?php echo $ld['status'] ?></label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8">
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][status]" checked value="1"/>有效</label>
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][status]" value="0"/>无效</label>
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 3px;">显示正确答案</label>
	                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-8">
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][recommend_flag]" checked value="1"/>是</label>
	                            <label class="am-radio-inline am-success"><input data-am-ucheck type="radio" name="data[Evaluation][recommend_flag]" value="0"/>否</label>
	                        </div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label">图片</label>
	                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-12 am-form-file">
	                            <div class="am-form-group am-form-file">
	                                <button type="button" class="am-btn am-btn-default am-btn-sm">
	                                <i class="am-icon-cloud-upload"></i> 选择要上传的图片</button>
	                                <span class="" style="font-size:12px;">(推荐尺寸150*150)</span>
	                                <input type="file" multiple name="org_logo" onchange="ajax_upload_media(this,this.id)" id="org_logo">
	                                <input type="hidden" multiple name="data[Evaluation][img]" >
	                            </div>
	                            <?php if(isset($evaluation_info['Evaluation']['img'])&&$evaluation_info['Evaluation']['img']!=''){ ?>
	                            <figure data-am-widget="figure" class="am am-figure am-figure-default am-no-layout am-figure-zoomable" data-am-figure="{  pureview: 'true' }">
	                            <img style="max-height: 200px;max-width: 200px;" src="<?php echo $server_host.$evaluation_info['Evaluation']['img'] ?>" data-rel="<?php echo $server_host.$evaluation_info['Evaluation']['img'] ?>" alt="" id="img_logo" >
	                            </figure>
	                            <?php }else{ ?>
	                            <img src="" data-rel="" alt="" id="img_logo" style="display:none;max-height: 200px;max-width: 200px;">
	                            <?php } ?>
	                        </div>
	                        <div class="am-cf"></div>
	                    </div>
	                    <div class="am-form-group">
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label">描述</label>
	                        <div class="am-u-lg-9 am-u-md-9 am-u-sm-12">
	                            <textarea cols="30" id="elm" name="data[Evaluation][description]" rows="10" style="width:auto;height:300px;"></textarea>
	                            <script type='text/javascript'>
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
	                        <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 13px;">价格</label>
	                        <div class="am-u-lg-4 am-u-md-4 am-u-sm-6"><input type="text" id="price" name="data[Evaluation][price]" value="0.00"/></div>
	                       
	                    </div>
	                </div>
	            </div>
	        </div>
	        <div class="btnouter" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
	            <label class="am-u-lg-3 am-u-md-3 am-u-sm-12 am-form-label" style="padding-top: 9px;">&nbsp;</label>
	            <div class="am-u-lg-9 am-u-md-9 am-u-sm-12" style="padding-left:25px;">
	                <button style="margin-right: 0;" type="submit" class="am-btn am-btn-success am-btn-sm am-btn-bottom am-radius">提交</button>
	                <button style="margin-right: 0;" type="reset" class="am-btn am-btn-default am-btn-sm am-btn-bottom am-radius">重置</button>
	            </div>
	        </div>
	        <?php echo $form->end(); ?>
	    </div>
	</div>
</div>
<script>
    var code_check=true;
    function check_code(obj){
        code_check=false;
        var code=obj.value;
        if(code==""){return false;}
        $.ajax({url: web_base+"/evaluations/ajax_check_code",
            type:"POST",
            data:{'code':code},
            dataType:"json",
            success: function(data){
                try{
                    if(data.code==1){
                        code_check=true;
                    }else{
                        seevia_alert(data.msg);
                    }
                }catch (e){
                    seevia_alert(j_object_transform_failed);
                }
            }
        });
    }

    function check_all(){
        if(code_check==false){
            seevia_alert("编码已存在");
            return false;
        }
        var name_obj = document.getElementById("name");
        var code_obj = document.getElementById("code");
        if(name_obj.value==""){
            seevia_alert("名称不能为空");
            return false;
        }
        return true;
    }

    function evaluation_category_code_select(value){
        if(value=='-1'){
            $('#evaluation_category_code_zidingyi').css('display','');
        }else{
            $('#evaluation_category_code_zidingyi').css('display','none');
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