<style>
    /*小屏*/
    @media only screen and (max-width: 640px)
    {
        body #master_info{width:100%;left:0;margin-left:0;}
        body #master_info>form{padding:10px 10px;}
        body #master_name{width:100px;text-overflow:ellipsis;overflow: hidden;white-space: nowrap;}
        body #add_master{width:100%;left:0;margin-left:0;}
        body #select_user{margin-left:15px;}
        body #add_master>form{padding:10px 10px;}
    }
    a
    {
        color:#333;
    }
    #act_tab li a{color:#ddd;}
    #act_tab li.am-active a{color:#0e90d2;}
    .am-active .am-btn-default.am-dropdown-toggle, .am-btn-default.am-active, .am-btn-default:active {
        background-color: #fff;
    }
</style>
<link href="<?php echo $webroot.'plugins/AmazeUI/css/amazeui.switch.css?ProjectVersion='.ProjectVersion; ?>" type="text/css" rel="stylesheet">
<script src="<?php echo $webroot.'plugins/AmazeUI/js/amazeui.switch.min.js?ProjectVersion='.ProjectVersion; ?>" type="text/javascript"></script>
<div class="am-tabs" data-am-tabs="{noSwipe: 1}">
    <ul class="am-tabs-nav am-nav am-nav-tabs" id="act_tab">
        <li class="am-active"><a href="javascript: void(0)">我的师傅</a></li>
        <li><a href="javascript: void(0)">我的徒弟</a></li>
    </ul>
    <div class="am-tabs-bd">
        <div class="am-tab-panel am-active">
            <div class='course_log am-u-lg-12 am-u-sm-12' style="margin-left: 0;padding-left:7px;padding-right:7px;margin-top:0;margin-bottom:0;padding-bottom:0;">
                <div class="am-g" style="position: relative;">
                    <a style="margin-left: 5px;float:right;margin-bottom:10px;" class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick='addMaster(1);'>
                        <span class="am-icon-plus"></span> 拜师
                    </a>
                    <div class="am-cf"></div>
                </div>

                <div class="am-u-user-point am-margin-top-0">
                    <div class="am-point-log">
                        <table class="am-table">
                            <thead class="integral_list">
                            <tr>
                                <td width="30%" id="master_name" style="border-bottom:0px;">姓名</th>
                                <td width="25%" style="border-bottom:0px;">拜师时间</th>
                                <td width="20%" style="border-bottom:0px;">状态</th>
                                <td width="25%" style="border-bottom:0px;">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($master_data)&&sizeof($master_data)>0){foreach($master_data as $k=>$v){?>
                                <tr>
                                    <td><a onclick='showInfo("<?php echo $v['name']?>","<?php echo $v['img']?>");'><p id="master_name"><?php echo $v['name']?></p></a></td>
                                    <td><?php echo date("Y-m-d",strtotime($v['created']));?></td>
                                    <td><?php if($v['status']==0){?>
                                            申请中
                                        <?php }else{?>
                                            已接受
                                        <?php }?>
                                    </td>
                                    <td><?php if($v['status']==0 && $v['initiator']==1){?>
                                            <a style="cursor:pointer;" onclick="changeStatus(<?php echo $v['id']?>,1,'同意')">同意</a>&nbsp;&nbsp;&nbsp;&nbsp;<a style="cursor:pointer;" onclick="changeStatus(<?php echo $v['id']?>,2,'拒绝')">拒绝</a>
                                        <?php }elseif($v['status']==1){?>
                                            <a style="cursor:pointer;" onclick="changeStatus(<?php echo $v['id']?>,2,'取消师徒关系')">取消师徒关系</a>
                                        <?php }else{?>
                                        	<a style="cursor:pointer;" onclick="changeStatus(<?php echo $v['id']?>,2,'取消申请')">取消申请</a>
                                        <?php }?>
                                    </td>
                                </tr>
                            <?php }}else{?>
                                <tr><td colspan="4" style="text-align: center;padding-top: 150px;">您还没有师傅</td></tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="am-tab-panel">
            <div class='course_log am-u-lg-12 am-u-sm-12' style="margin-left: 0;padding-left:7px;padding-right:7px;margin-top:0;margin-bottom:0;padding-bottom:0;">
                <div class="am-g" style="position: relative;">
                    <a style="margin-left: 5px;float:right;margin-bottom:10px;" class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" onclick='addMaster(2);'>
                        <span class="am-icon-plus"></span> 收徒
                    </a>
                    <a style="float: right;border-radius:5px;margin-right:5px;" href="<?php echo $html->url('/users/user_note'); ?>" class="am-btn am-btn-primary am-btn-sm am-radius">所有笔记</a>
                    <a style="float: right;border-radius:5px;margin-right:5px;" href="<?php echo $html->url('/users/user_assignment'); ?>" class="am-btn am-btn-primary am-btn-sm am-radius"><span>所有作业</span></a>
                    <div class="am-cf"></div>
                </div>
                <div class='am-g am-padding-xs'>
                    <div class='am-u-lg-10 am-u-md-9 am-u-sm-8 am-text-right' style="margin-top:5px;">开启收徒</div>
                    <div class='am-u-lg-2 am-u-md-3 am-u-sm-4'>
                        <input type='checkbox' value="1" id="allow_apprentice" <?php echo isset($user_config_detail['UserConfig'])?'checked':'' ?> />
                    </div>
                </div>
                <div class="am-u-user-point am-margin-top-0">
                    <div class="am-point-log">
                        <table class="am-table">
                            <thead class="integral_list">
                            <tr>
                                <td width="30%" style="border-bottom:0px;"><?php echo $ld['name']?></th>
                                <td width="25%" style="border-bottom:0px;">收徒时间</th>
                                <td width="20%" style="border-bottom:0px;">状态</th>
                                <td width="25%" style="border-bottom:0px;">操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(isset($apprentice_data)&&sizeof($apprentice_data)>0){foreach($apprentice_data as $kk=>$vv){?>
                                <tr>
                                    <td><a onclick='showInfo("<?php echo $vv['name']?>","<?php echo $vv['img']?>");'><p id="master_name"><?php echo $vv['name']?></p></a></td>
                                    <td><?php echo date("Y-m-d",strtotime($vv['created']));?></td>
                                    <td><?php if($vv['status']==0){?>
                                            申请中
                                        <?php }else{?>
                                            已接受
                                        <?php }?>
                                    </td>
                                    <td><?php if($vv['status']==0 && $vv['initiator']==0){?>
                                            <a style="cursor:pointer;" onclick="changeStatus(<?php echo $vv['id']?>,1,'同意')">同意</a>&nbsp;&nbsp;&nbsp;&nbsp;<a style="cursor:pointer;" onclick="changeStatus(<?php echo $vv['id']?>,2,'拒绝')">拒绝</a>
                                        <?php }elseif($vv['status']==1){?>
                                            <a href="<?php echo $html->url('/users/user_course/'.$vv['user_id']); ?>">查看</a>&nbsp;&nbsp;&nbsp;&nbsp;<a style="cursor:pointer;" onclick="changeStatus(<?php echo $vv['id']?>,2,'取消师徒关系')">取消师徒关系</a>
                                        <?php }else{?>
                                            <a style="cursor:pointer;" onclick="changeStatus(<?php echo $vv['id']?>,2,'取消申请')">取消申请</a>
                                        <?php }?>
                                    </td>
                                </tr>
                            <?php }}else{?>
                                <tr><td colspan="4" style="text-align: center;padding-top: 50px;">您还没有徒弟</td></tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class='am-modal am-modal-no-btn' id='master_info'>
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title">用户信息</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='post' class='am-form am-form-horizontal'>
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right am-form-label'>姓名:</label>
                        <div class='am-u-lg-6 am-u-sm-8 am-u-md-6'>
                            <input type='text' id="user_name" required value="" style="margin-left:0;" />
                        </div>
                        <div class='am-cf'></div>
                    </div>
                    <div class="am-form-group">
                        <label class='am-u-lg-2 am-u-md-4 am-u-sm-4 am-text-right am-form-label'>头像:</label>
                        <div class='am-u-lg-6 am-u-sm-8 am-u-md-6'>
                            <img style="float:left;" id="user_img" src="" width="150px" height="150px">
                        </div>
                        <div class='am-cf'></div>
                    </div>
                    <div class="am-form-group">
                        <label class='am-u-lg-2 am-u-sm-4 am-u-md-3 am-text-right am-form-label'>简介:</label>
                        <div class='am-u-lg-6 am-u-sm-8 am-u-md-6'>
                            <textarea style="border-radius:3px;" name=""></textarea>
                        </div>
                        <div class='am-cf'></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class='am-modal am-modal-no-btn' id='add_master'>
    <div class="am-modal-dialog">
        <div class="am-modal-hd" style=" z-index: 11;">
            <h4 class="am-popup-title" id="class_title">添加师傅</h4>
            <span data-am-modal-close class="am-close">&times;</span>
        </div>
        <div class="am-modal-bd">
            <form method='post' class='am-form am-form-horizontal'>
                <input type="hidden" id="master_type" value=""/>
                <div class="am-panel-bd">
                    <div class="am-form-group">
                        <div class='am-u-lg-8 am-u-md-4 am-u-sm-8 am-text-right'>
                            <div class="am-input-group am-input-group-sm am-u-lg-4 am-u-md-4 am-u-sm-8 am-fr"  >
                                <input style="width: 150px;border: 1px solid #999;border-right: none;outline:none;border-bottom-left-radius: 3px;border-top-left-radius: 3px;" type="text" class="am-form-field" AUTOCOMPLETE="OFF" id="user_keyword" name="user_keyword" placeholder="请输入姓名或手机号" value=""/>
		                        <span class="am-input-group-btn" style="background: #fff;">
		                            <button  onclick="selectName(<?php echo $user_id; ?>)" class="am-btn am-btn-secondary am-btn-sm" style="border-left: none;width: auto;background: #fff;border-color: #999;color: #ccc;border-radius: 0 5px 5px 0;border-bottom-right-radius: 3px;border-top-right-radius: 3px;" type="button"><span  class="am-icon-search"></span></button>
		                        </span>
                            </div>
                        </div>
                        <div class='am-cf'></div>
                    </div>
                    <div class="am-form-group">
                        <label class='am-u-lg-2 am-u-md-4 am-u-sm-3 am-text-right am-form-label'>用户:</label>
                        <div class='am-u-lg-6 am-u-md-4 am-u-sm-8 am-text-right'>
                            <select name="data[Resume][certificate_id]" id="select_user" style="width:100%;">
                                <option value=""><?php echo $ld['please_select'];?></option>
                            </select>
                        </div>
                        <div class='am-cf'></div>
                    </div>
                    <div class="am-form-group">
                        <button style="padding:7px 23px;font-size:14px;margin-right:1rem;" type='button' class='am-btn am-btn-primary am-radius' onclick='saveUser(<?php echo $user_id; ?>)'>发送邀请</button>
                        <div class='am-cf'></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type='text/javascript'>
    $(".user-note").on('click',function(){
        window.location.href="<?php echo $html->url('/users/user_note') ?>";
    });

    $('#allow_apprentice').bootstrapSwitch({
        size:'xs',
        onText:'是',
        offText:'否',
        onSwitchChange:function(event, state){
            $.ajax({
                url: web_base+"/users/master",
                type:"POST",
                dataType:"json",
                data: {'allow_apprentice':state?'1':'0'},
                success: function(data){

                }
            });
        }
    });

    var NextSelect=$("#select_user");
    function showInfo(name,img){
        $('#user_name').val(name);
        $('#user_img').attr("src",img);
        $('#master_info').modal('open');
    }

    function addMaster(type){
        $("#master_type").val(type);
        if(type==1){
            $("#class_title").html("添加师傅");
        }else{
            $("#class_title").html("添加徒弟");
        }
        $("#user_keyword").val("");
        NextSelect.html("<option value=''><?php echo $ld['please_select'];?></option>");
        $('#add_master').modal('open');
    }

    function changeStatus(id,status,text){
        if(confirm("确定"+text+"吗?"))
        {
            $.ajax({
                url: web_base+"/users/change_status",
                type:"POST",
                dataType:"json",
                data: {'id':id,'status':status},
                success: function(data){
                    alert(data.msg);
                    window.location.reload();
                }
            });
        }
    }

    function selectName(id){
        NextSelect.html("<option value=''><?php echo $ld['please_select'];?></option>");
        var search_keyword=$("#user_keyword").val();
        if(search_keyword.length<2){
            alert("关键字必须大于2个字");
            return false;
        }
        var type=$("#master_type").val();
        $.ajax({
            url: web_base+"/users/select_name",
            type:"POST",
            dataType:"json",
            data: {'id':id,'search_keyword':search_keyword,'type':type},
            success: function(data){
                if(data.error=="1"){
                    var name_list=data.data;
                    if(name_list.length!=0){
                        $(name_list).each(function(index,item){
                            if(name_list.length==1){
                                var aa = $("<option selected></option>").val(item['User']['id']).text(item['User']['name']+" "+item['User']['mobile']);
                            }else{
                                var aa = $("<option></option>").val(item['User']['id']).text(item['User']['name']+" "+item['User']['mobile']);
                            }
                            aa.appendTo(NextSelect);
                        });
                        var msg="共搜索到"+name_list.length+"条，请选择";
                        alert(msg);
                    }else{
                        alert("没有搜索到用户");
                    }
                }
            }
        });
    }

    function saveUser(id){
        var type=$("#master_type").val();
        var user_id=$("#select_user").val();
        if(user_id!=""){
            $.ajax({
                url: web_base+"/users/add_master",
                type:"POST",
                dataType:"json",
                data: {'id':id,'type':type,'user_id':user_id},
                success: function(data){
                    alert(data.msg);
                    window.location.reload();
                }
            });
        }else{
            alert("请选择用户");
        }
    }
</script>