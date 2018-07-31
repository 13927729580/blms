<style type="text/css">
    .am-panel-title div{font-weight:bold;}
    #SearchForm{padding-top:8px;}
    #SearchForm li{margin-bottom:10px;}
    #SearchForm .am-selected-list li{margin-bottom: 0px;}
    .am-form-horizontal{padding-top:0px;}
</style>
<div>
    <?php echo $form->create('UserCourseClasses',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-horizontal'));?>
    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['type']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7  ">
                <select name="option_type_code" id='option_type_code' data-am-selected="{maxHeight:300}" >
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <?php foreach($course_type as $kk=>$vv){ ?>
                        <option value="<?php echo $vv['CourseType']['code'] ?>" <?php if($option_type_code ==$vv['CourseType']['code']){?>selected<?php }?>><?php echo $vv['CourseType']['name'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['status']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7  ">
                <select name="status" id='status' data-am-selected="{maxHeight:300}" >
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <option value="1" <?php if($status ==1){?>selected<?php }?>>学习中</option>
                    <option value="2" <?php if($status ==2){?>selected<?php }?>>已完成</option>
                    <option value="3" <?php if($status ==3){?>selected<?php }?>>已评价</option>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['keyword'];?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="keyword" id="keyword" value="<?php echo isset($keyword)?$keyword:'';?>"/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['user_name'];?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="user_name" id="user_name" value="<?php echo isset($user_name)?$user_name:'';?>"/>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"  style="padding-right:0;">最后学习时间</label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3" style="padding-right:0.5rem;width:37%;">
                <div class="am-input-group">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="start_date_time" value="<?php echo isset($start_date_time)?$start_date_time:"";?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
            </div>
            <div class=" am-text-center am-fl " style="margin-top:7px;">-</div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3" style="padding-left:0.5rem;padding-right:0;">
                <div class="am-input-group">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="end_date_time" value="<?php echo isset($end_date_time)?$end_date_time:"";?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
            </div>
        </li>
        <li >
            <label class="am-u-sm-3 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
            <div class="am-u-sm-8 am-u-md-8 am-u-sm-7 " style="float:left;">
                <input type="button"  class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" onclick="formsubmit()" />
            </div>
        </li>
    </ul>
    <div class="am-g">
        <label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-form-group-label">&nbsp;</label>
        <div id="changeAttr" class="am-u-lg-11 am-u-md-11 am-u-sm-11"></div>
        <div style="clear:both;"></div>
    </div>
    <?php echo $form->end()?>
</div>
<div>
    <div class="listtable_div_btm">
        <div class="am-g">
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" ><?php echo $ld['avatar'];?></div>
            <div class="am-u-lg-3 am-u-md-2 am-u-sm-2"><?php echo $ld['user_name'];?></div>
            <div class="am-u-lg-3 am-u-md-3 am-u-sm-2">课程<?php echo $ld['name'];?></div>
            <div class="am-u-lg-1 am-u-md-1"><?php echo $ld['status'];?></div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">阅读次数</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">最后学习时间</div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['operate']; ?></div>
        </div>
        <?php if(isset($course_list) && sizeof($course_list)>0){foreach($course_list as $k=>$v){?>
            <div class="am-g">
                <div class="listtable_div_top" >
                    <div style="margin:10px auto;" class="am-g">
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                            <?php echo $html->image(isset($v['User']['img01'])?$v['User']['img01']:'/theme/default/img/no_head.png',array('style'=>'width:60px;height:60px;display:block;margin:0 auto;')); ?>
                        </div>
                        <div class="am-u-lg-3 am-u-md-2 am-u-sm-2">
                            <a href="<?php echo $html->url('/users/view/'.$v['UserCourseClass']['user_id']); ?>">
                                <?php echo isset($v['User']['name'])?$v['User']['name']:"--";?>
                            </a>
                        </div>
                        <div class="am-u-lg-3 am-u-md-3 am-u-sm-2">
                            <a href="<?php echo $html->url('/courses/view/'.$v['UserCourseClass']['course_id']); ?>">
                                <?php echo isset($v['Course']['name'])?htmlspecialchars($v['Course']['name']):"--";?>
                            </a>
                        </div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                            <?php if ($v['UserCourseClass']['status'] == 0) {?>
                                无效
                            <?php }elseif($v['UserCourseClass']['status'] == 1){ ?>
                                学习中
                            <?php }elseif($v['UserCourseClass']['status'] == 2){ ?>
                                已完成
                            <?php }elseif($v['UserCourseClass']['status'] == 3){ ?>
                                已评价
                            <?php } ?>
                        </div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $v['UserCourseClass']['readcount'];?></div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><?php echo $v['UserCourseClass']['modified'];?></div>
                        <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                            <a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/user_course_classes/view/'.$v['UserCourseClass']['id']); ?>">
                                <span class="am-icon-pencil-square-o"></span> <?php echo $ld['view']; ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php }}else{?>
            <div>
                <div class="no_data_found" ><?php echo $ld['no_data_found']?></div>
            </div>
        <?php }?>
    </div>
    <?php if(isset($course_list) && sizeof($course_list)){?>
        <div id="btnouterlist" class="btnouterlist am-form-group am-hide-sm-only">
            <div><?php echo $this->element('pagers')?></div>
            <div class="am-cf"></div>
        </div>
    <?php }?>
</div>
<script type="text/javascript">
    function formsubmit(){
        var keyword=document.getElementById('keyword').value;
        var option_type_code=document.getElementById('option_type_code').value;
        var status=document.getElementById('status').value;
        var user_name=document.getElementById('user_name').value;
        var start_date_time = document.getElementsByName('start_date_time')[0].value;
        var end_date_time = document.getElementsByName('end_date_time')[0].value;
        var url = "user_name="+user_name+"&keyword="+keyword+"&option_type_code="+option_type_code+"&status="+status+"&start_date_time="+start_date_time+"&end_date_time="+end_date_time;
        window.location.href = encodeURI(admin_webroot+"user_course_classes?"+url);
    }
</script>