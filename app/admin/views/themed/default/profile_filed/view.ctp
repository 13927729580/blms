<style type='text/css'>
.am-radio, .am-checkbox{display:inline;}
em{color:red;}
.am-checkbox {margin-top:0px; margin-bottom:0px;}
label{font-weight:normal;}
.am-form-horizontal .am-radio{padding-top:0;}
.am-radio input[type="radio"], .am-radio-inline input[type="radio"], .am-checkbox input[type="checkbox"], .am-checkbox-inline input[type="checkbox"]{margin-left:0px;}

.am-list>li{margin-bottom:0;border-style: none;}
.admin-sidebar-list li a{color:#fff;background-color: #5eb95e;}
.am-list > li > a.am-active, .am-list > li > a.am-active:hover, .am-list > li > a.am-active:focus{font-weight: bold;}
.scrollspy-nav.am-sticky.am-animation-slide-top{width: 100%;}
.am-sticky-placeholder{margin-top: 10px;}
.scrollspy-nav {top: 0;z-index: 100;background: #5eb95e;width: 100%;padding: 0 10px}
.scrollspy-nav ul {margin: 0;padding: 0;}
.scrollspy-nav li {display: inline-block;list-style: none;}
.scrollspy-nav a {color: #eee;padding: 10px 20px;display: inline-block;}
.scrollspy-nav a.am-active {color: #fff;font-weight: bold;}
.crumbs{padding-left:0;margin-bottom:22px;}
.btnouter{margin:0;}
</style>
<?php 
	//pr($profilefiled_data);
	echo $form->create('ProfileFiled',array('action'=>'/view/'.$id.'/'.$uid,'name'=>"ProfileFiledForm","type"=>"POST"));?>
<div class="am-panel-group admin-content am-u-lg-9 am-u-md-9 am-u-sm-9  am-detail-view" id="accordion" style="width: 100%;" >
    <!-- 导航 -->
    <div class="scrollspy-nav" data-am-scrollspy-nav="{offsetTop: 45}" data-am-sticky="{top:'52px',animation:'slide-top'}" style="margin-bottom:8px;">
        <ul>
            <li><a href="#tablemain"><?php echo $ld['export_configuration'];?></a></li>
        </ul>
    </div>
    <div class="btnouter am-text-right" data-am-sticky="{top:'100px',animation:'slide-top'}" style="margin-bottom:0;">
        <input style="margin-right: 0;" class="am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['submit'];?>" /> 
        <input style="margin-right: 0;" class="am-btn am-btn-success am-radius am-btn-sm" type="reset" value="<?php echo $ld['reset'];?>" />
    </div>
    <!-- 导航结束 -->
    <div id="tablemain" class="am-panel am-panel-default">
        <div class="am-panel-hd">
            <h4 class="am-panel-title">
                <?php echo $ld['export_configuration']?>
            </h4>
        </div>
        <div id="export_configuration" class="am-panel-collapse am-collapse am-in">
            <div class="am-panel-bd am-form-detail am-form am-form-horizontal" style="padding-bottom:0;">
                <table class="am-table">
                    <tbody>
                    <!-- <tr>
                        <th style="padding-top:15px;" rowspan="2"><?php echo $ld['code'];?></th>
                    </tr>
                    <tr>
                        <td>
                            <input style="width:200px;float:left;" type="text" id="name" name="data[ProfileFiled][code]" value="<?php echo empty($profilefiled_data['ProfileFiled']['code'])?'':$profilefiled_data['ProfileFiled']['code']?>" /> <em>*</em>
                            <input type="hidden" id="id" name="data[ProfileFiled][profile_id]" value="<?php echo empty($profilefiled_data['ProfileFiled']['profile_id'])?$id:$profilefiled_data['ProfileFiled']['profile_id']?>">
                            <input type="hidden" id="id" name="data[ProfileFiled][id]" value="<?php echo empty($profilefiled_data['ProfileFiled']['id'])?'':$profilefiled_data['ProfileFiled']['id']?>">
                        </td>
                    </tr> -->
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;padding-left: 0;margin-top: 5px;"><?php echo $ld['code'];?></label>
                        <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-left: 0;">
                            <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="padding-left: 0;"> 
                                <input style="float:left;" type="text" id="name" name="data[ProfileFiled][code]" value="<?php echo empty($profilefiled_data['ProfileFiled']['code'])?'':$profilefiled_data['ProfileFiled']['code']?>" /> 
                                <input type="hidden" id="id" name="data[ProfileFiled][profile_id]" value="<?php echo empty($profilefiled_data['ProfileFiled']['profile_id'])?$id:$profilefiled_data['ProfileFiled']['profile_id']?>">
                                <input type="hidden" id="id" name="data[ProfileFiled][id]" value="<?php echo empty($profilefiled_data['ProfileFiled']['id'])?'':$profilefiled_data['ProfileFiled']['id']?>">
                            </div>
                            <em style="padding-top: 15px;display: inline-block;">*</em>
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;padding-left: 0;margin-top: 5px;"><?php echo $ld['name'];?></label>
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                            <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-left: 0;">
                                <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="padding-left: 0;"> 
                                    <input type="hidden"  name="data[ProfilesFieldI18n][<?php echo $k;?>][locale]" value="<?php echo $v['Language']['locale'] ?>">
                                    <input style="float:left;" type="text" id="profilefiled_name<?php echo $v['Language']['locale'];?>" name="data[ProfilesFieldI18n][<?php echo $k;?>][name]" value="<?php echo isset($profilefiled_data['ProfilesFieldI18n'][$v['Language']['locale']]['name'])?$profilefiled_data['ProfilesFieldI18n'][$v['Language']['locale']]['name']:'';?>" />

                                </div>
                                <?php if(sizeof($backend_locales)>1){?>
                                <span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?>
                                <em style="padding-top: 15px;display: inline-block;">*</em>
                            </div>
                            <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"></label>
                        <?php }}?>
                    </div>
<!--                     <tr>
                        <th style="padding-top:15px;" rowspan="<?php echo isset($backend_locales)?count($backend_locales)+1:1;?>"><?php echo $ld['description'];?></th>
                    </tr>
                    <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                    <tr>
                        <td><textarea style="width:400px;float:left;" id="profilefiled_description<?php echo $v['Language']['locale'];?>" name="data[ProfilesFieldI18n][<?php echo $k;?>][description]"><?php echo isset($profilefiled_data['ProfilesFieldI18n'][$v['Language']['locale']]['description'])?$profilefiled_data['ProfilesFieldI18n'][$v['Language']['locale']]['description']:'';?></textarea><?php if(sizeof($backend_locales)>1){?><div style="margin-top:10px;"><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em></div></td>
                    </tr>
                    <?php }}?> -->
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;padding-left: 0;margin-top: 5px;"><?php echo $ld['description'];?></label>
                        <?php if(isset($backend_locales)&&sizeof($backend_locales)>0){foreach ($backend_locales as $k => $v){?>
                            <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-left: 0;">
                                <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="padding-left: 0;"> 
                                    	<textarea style="float:left;height: 150px;" id="profilefiled_description<?php echo $v['Language']['locale'];?>" name="data[ProfilesFieldI18n][<?php echo $k;?>][description]"><?php echo isset($profilefiled_data['ProfilesFieldI18n'][$v['Language']['locale']]['description'])?$profilefiled_data['ProfilesFieldI18n'][$v['Language']['locale']]['description']:'';?></textarea><?php if(sizeof($backend_locales)>1){?><div style="margin-top:10px;position: absolute;right: -25px;"><span class="lang"><?php echo $ld[$v['Language']['locale']]?></span><?php }?><em>*</em>
                                </div>
                                </div>
                            </div>
                            <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label"></label>
                        <?php }}?>
                        <div class='am-cf'></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;padding-left: 0;margin-top: 5px;"><?php echo $ld['prod_type_format'];?></label>
                            <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-left: 0;">
                                <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="padding-left: 0;"> 
                                    <input style="float:left;" type="text" id="name" name="data[ProfileFiled][format]" value="<?php echo empty($profilefiled_data['ProfileFiled']['format'])?'':$profilefiled_data['ProfileFiled']['format']?>">
                                </div>
                                <em style="padding-top: 15px;display: inline-block;">*</em>
                            </div>
                            
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;padding-left: 0;margin-top: 5px;"><?php echo $ld['required'];?></label>
                            <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-left: 0;">
                                <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="padding-left: 0;"> 
                                    <label class="am-radio am-success" style="padding-top:2px;"><input type="radio" value="1" data-am-ucheck name="data[ProfileFiled][required]" <?php echo isset($profilefiled_data['ProfileFiled']['required'])&&$profilefiled_data['ProfileFiled']['required']=='1'?'checked':''; ?> /><?php echo $ld['yes'];?></label>
                                    <label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input type="radio" name="data[ProfileFiled][required]" value="0" data-am-ucheck <?php echo !isset($profilefiled_data['ProfileFiled']['required'])||(isset($profilefiled_data['ProfileFiled']['required'])&&$profilefiled_data['ProfileFiled']['required']=='0')?'checked':''; ?>/><?php echo $ld['no'];?></label>
                                </div>
                            </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;padding-left: 0;margin-top: 5px;"><?php echo $ld['minlength'];?></label>
                            <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-left: 0;">
                                <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="padding-left: 0;"> 
                                    <input style=float:left;" type="text" id="name" name="data[ProfileFiled][minlength]" value="<?php echo empty($profilefiled_data['ProfileFiled']['minlength'])?'0':$profilefiled_data['ProfileFiled']['minlength']?>">
                                </div>
                            </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;padding-left: 0;margin-top: 5px;"><?php echo $ld['maxlength'];?></label>
                            <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-left: 0;">
                                <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="padding-left: 0;"> 
                                    <input style="float:left;" type="text" id="name" name="data[ProfileFiled][maxlength]" value="<?php echo empty($profilefiled_data['ProfileFiled']['maxlength'])?'0':$profilefiled_data['ProfileFiled']['maxlength']?>">
                                </div>
                            </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;padding-left: 0;margin-top: 5px;"><?php echo $ld['data_sources'];?></label>
                            <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-left: 0;">
                                <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="padding-left: 0;"> 
                                    <textarea name="data[ProfileFiled][data_sources]" style="float:left;height: 150px;"><?php echo isset($profilefiled_data['ProfileFiled']['data_sources'])?$profilefiled_data['ProfileFiled']['data_sources']:''; ?></textarea>
                                </div>
                            </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;padding-left: 0;margin-top: 5px;"><?php echo $ld['sort'];?></label>
                            <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-left: 0;">
                                <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="padding-left: 0;"> 
                                    <input style="float:left;" type="text" id="name" name="data[ProfileFiled][orderby]" value="<?php echo empty($profilefiled_data['ProfileFiled']['orderby'])?'50':$profilefiled_data['ProfileFiled']['orderby']?>"> 
                                </div>
                                <em style="display: inline-block;padding-top: 15px;">*</em>
                            </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-2 am-u-md-3 am-u-sm-3 am-form-label" style="text-align: left;padding-left: 0;margin-top: 5px;"><?php echo $ld['status'];?></label>
                            <div class="am-u-lg-8 am-u-md-8 am-u-sm-8" style="padding-left: 0;">
                                <div class="am-u-lg-9 am-u-md-9 am-u-sm-9" style="padding-left: 0;"> 
                                    <label class="am-radio am-success" style="padding-top:2px;"><input type="radio" value="1" data-am-ucheck name="data[ProfileFiled][status]" checked/><?php echo $ld['yes'];?></label>
                                    <label style="margin-left:10px;padding-top:2px;" class="am-radio am-success"><input type="radio" name="data[ProfileFiled][status]" value="0" data-am-ucheck <?php if(isset($profilefiled_data['ProfileFiled'])&&$profilefiled_data['ProfileFiled']['status'] == 0){ echo "checked"; } ?>/><?php echo $ld['no'];?></label>
                                </div>
                                <em style="display: inline-block;padding-top: 15px;">*</em>
                            </div>
                    </div>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php echo $form->end();?>