<script src="<?php echo $webroot; ?>plugins/kindeditor/kindeditor-min.js" type="text/javascript"></script>
<div class="am-product">
    <div id="accordion" class="am-u-lg-12 am-u-md-12 am-u-sm-12">
        <?php echo $form->create('/user_task_groups',array('action'=>'add','id'=>'user_task_group_add_form','name'=>'user_task_group_add','type'=>'POST','class'=>'am-form am-form-horizontal','onsubmit'=>"return groups_check();"));?>
        <input type="hidden" name="data[UserTaskGroup][id]" id="_id" value="" />
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
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['name'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input type="text" name="data[UserTaskGroup][name]" id="name" value=""></div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['start_time'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input type="text" class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="data[UserTaskGroup][start_time]" value="" />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['end_time'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6"><input type="text" class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="data[UserTaskGroup][end_time]" value="" />
                        </div>
                    </div>
                    <div class="am-form-group">
                        <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['status'] ?></label>
                        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
                            <label class="am-radio-inline"><input type="radio" name="data[UserTaskGroup][status]" value="1" checked="checked"/>有效</label>
                            <label class="am-radio-inline"><input type="radio" name="data[UserTaskGroup][status]" value="0"/>无效</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo $form->end(); ?>
    </div>
</div>
<script>
function groups_check(){
    var start_date = $('input[name="data[UserTaskGroup][start_time]"]').val();
    var end_date = $('input[name="data[UserTaskGroup][end_time]"]').val();
    start_date = new Date(start_date.replace(/\-/g, "\/"));  
    end_date = new Date(end_date.replace(/\-/g, "\/")); 
    if($('input[name="data[UserTaskGroup][name]"]').val()==''){
        alert('名称不能为空！');
        return false;
    }
    if($('input[name="data[UserTaskGroup][start_time]"]').val()==''){
        alert('开始时间不能为空！');
        return false;
    }
    if($('input[name="data[UserTaskGroup][end_time]"]').val()==''){
        alert('结束时间不能为空！');
        return false;
    }
    if(start_date > end_date){
        alert('开始日期 不能大于 结束日期！');
        return false;
    }
}
</script>