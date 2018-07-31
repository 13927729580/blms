<style>
    /*小屏*/
    @media only screen and (max-width: 640px)
    {
        body #user_info{width:100%;left:0;margin-left:0;}
        body #user_info>form{padding:10px 10px;}
        body #master_name{width:100px;text-overflow:ellipsis;overflow: hidden;white-space: nowrap;}
        body #Media{width:100%;left:0;margin-left:0;}
    }
    #user_info form{max-height:300px;overflow-y:scroll;}
    #Media video{max-width:100%;max-height:100%;}
	#Media img{max-width:100%;}
</style>
<div style="text-align:left;font-size:20px;border-bottom:1px solid #ccc;padding-bottom:1rem;margin-top:1%;padding-left:5px;" >
    <span>徒弟笔记列表</span>
</div>
<div class="am-u-user-point am-margin-top-0">
    <div class="am-point-log">
        <?php echo $form->create('',array('action'=>'/user_note','type'=>'get','name'=>"SearchForm","class"=>'am-form am-form-horizontal'));?>
        <div>
            <ul class="am-avg-lg-3 am-avg-md-3 am-avg-sm-1">
                <li style="padding:10px 10px;">
                    <label class="am-fl am-form-label">课程</label>
                    <div class="am-u-lg-4 am-u-md-6 am-u-sm-6"><select name="course_id" id="course_id" data-am-selected>
                            <option value="-1"><?php echo $ld["please_select"]?></option>
                            <?php if(!empty($course_list)){
                                foreach($course_list as $k=>$v){?>
                                    <option value="<?php echo $k;?>" <?php if(isset($course_id) && $course_id==$k){echo "selected";}?>><?php echo $v;?></option>
                                <?php }
                            }?>
                        </select>
                    </div>
                </li>
                <li style="padding:10px 10px;">
                    <label class="am-fl am-form-label">姓名</label>
                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-6">
                        <input type="text" name="user_name" id="user_name" value="<?php echo isset($user_name)?$user_name:'';?>"/>
                    </div>
                </li>
                <li style="padding:10px 10px;">
                    <label class="am-fl am-form-label"><?php echo $ld['mobile']?></label>
                    <div class="am-u-lg-8 am-u-md-8 am-u-sm-6">
                        <input type="text" name="user_phone" id="user_phone" value="<?php echo isset($user_phone)?$user_phone:'';?>"/>
                    </div>
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2"><input type="submit" class="am-btn am-btn-primary am-btn-sm am-radius" value="<?php echo $ld['query'];?>"/></div>
                </li>
            </ul>
        </div>
        <?php echo $form->end();?>
        <table class="am-table">
            <thead class="integral_list">
            <tr>
                <td width="20%" style="border-bottom:0px;"><?php echo $ld['name']?></th>
                <td width="50%" style="border-bottom:0px;">课程/章节/课时
                <td width="10%" style="border-bottom:0px;text-align: center;" class="am-hide-sm-only">笔记数量</th>
                <td width="10%" style="border-bottom:0px;" class="am-hide-sm-only">提交时间</th>
                <td width="10%" style="border-bottom:0px;">操作</th>
            </tr>
            </thead>
            <tbody>
            <?php if(isset($note_data)&&sizeof($note_data)>0){foreach($note_data as $kk=>$vv){?>
                <tr>
                    <td style="line-height:2.5;"><p id="master_name"><?php echo $vv['user_name'];?></p></td>
                    <td style="line-height:2.5;"><?php echo $vv['course_name'];?>/<?php echo $vv['course_chapter_name'];?>/<?php echo $vv['course_class_name'];?>——<?php echo $vv['courseware_hour'];?></td>
                    <td style="line-height:2.5;text-align: center;" class="am-hide-sm-only"><?php echo $vv['reply_num'];?>/<?php echo $vv['note_num'];?></td>
                    <td style="line-height:2.5;" class="am-hide-sm-only"><?php echo $vv['note_create'];?></td>
                    <td style="line-height:2.5;"><?php if($vv['note_num']!=0){?><a onclick="showInfo(<?php echo $flag[$vv['course_class_id']]['code']?>,<?php echo $vv['course_class_id']?>,<?php echo $vv['user_id']?>)">查看</a><?php }?></td>
                </tr>
            <?php }}else{?>
                <tr><td colspan="8" style="text-align: center;padding-top: 150px;">您还没有笔记</td></tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
<div class='am-modal am-modal-no-btn' id='user_info'>
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='POST' class='am-form am-form-horizontal'>
                <div class="user_detail" id="user_detail">
                </div>
            </form>
        </div>
    </div>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="Media">
    <div class="am-modal-dialog">
        <div class="am-modal-hd">&nbsp;
            <a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
        </div>
        <div class="am-modal-bd">
            <video controls="controls">你当前的浏览器不支持!</video>
            <img src="" />
        </div>
    </div>
</div>

<script type='text/javascript'>
    function showInfo(flag,id,user_id){
        if(flag==1){
            $.ajax({
                url: web_base+"/users/note_reply",
                type:"POST",
                dataType:"html",
                data: {'id':id,'user_id':user_id},
                success: function(data){
                    $('#user_detail').html(data);
                    $('#user_info').modal('open');
                }
            });
        }else{
            alert("您没有权限查看");
        }
    }
    
    $("#Media").on('opened.modal.amui', function(){
		var MediaAudio = $("#Media video")[0];
		if(MediaAudio.src!=''){
			if (MediaAudio.paused){
				MediaAudio.play();
			}else {
				MediaAudio.pause();
			}
		}
	}).on('close.modal.amui', function(){
		var MediaAudio = $("#Media video")[0];
		if(MediaAudio.src!=''){
			if(!MediaAudio.paused){
				MediaAudio.pause();
			}
		}
	});
</script>