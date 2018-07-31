 <style>
.am-form-label{font-weight:bold;text-align:center;margin-top:5px;margin-left:17px;}	
</style>
<div class="listsearch">
    <?php echo $form->create('OpenKeyword',array('action'=>'/','name'=>"SeearchForm","type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0">
        <li style="margin:0 0 10px 0">
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['open_model']?></label>
            <div class="am-u-lg-8 am-u-md-6 am-u-sm-6" style="padding:0 0.5rem;">
                <select id='OpenModelType' name='openType' data-am-selected="{noSelectedText:''}">
                    <option value='wechat' <?php if (isset($openType) && $openType == 'wechat') echo 'selected'; ?>><?php echo $ld['wechat'] ?></option>
                </select>
            </div>
        </li>
        <li style="margin:0 0 10px 0">
            <label class="am-u-lg-4 am-u-md-4 am-u-sm-4 am-form-label"><?php echo $ld['open_model_account']?></label>
            <div class="am-u-lg-7 am-u-md-6 am-u-sm-6">
                <select name="open_type_id" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?>'}">
                    <option value=""><?php echo $ld['all_data'] ?></option>
                    <?php foreach($openmodel_list as $k=>$v){ ?>
                        <option value="<?php echo $v['OpenModel']['open_type_id'] ?>"><?php echo $v['OpenModel']['open_type_id'] ?></option>
                    <?php } ?>
                </select>
            </div>
        </li>
        <li style="margin:0 0 10px 0">
            <label class="am-u-lg-2 am-u-md-3 am-u-sm-4 am-form-label"><?php echo $ld['keyword']?></label>
            <div class="am-u-lg-8 am-u-md-6 am-u-sm-6">
                <input placeholder="<?php echo $ld['keyword']?>" type="text" name="keywords" id="keywords" value="<?php echo isset($keywords)?$keywords:'';?>" />
            </div>
        </li>
        <li style="margin:0 0 10px 0">
         <label class="am-u-lg-3 am-u-md-4 am-u-sm-4 am-form-label"> </label>
            <div class="am-u-lg-8 am-u-md-6 am-u-sm-6">
                <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search'];?>"/>
            </div>
        </li>
    </ul>
    <?php echo $form->end()?>
</div>
<p class="am-u-md-12 am-text-right am-btn-group-xs">
	<?php if($svshow->operator_privilege("open_keywords_add")){?>
					<?php if(  isset($profile_id) && !empty($profile_id)   ) {  ?>
					 <a class="am-btn am-btn-xs am-btn-default" href="<?php echo $html->url('/open_keywords/open_keyword_upload'); ?>"><?php echo $ld['bulk_upload']?></a>
					<?php } ?>
				<a class="am-btn am-btn-warning am-radius am-btn-sm" href="<?php echo $html->url('view/'); ?>">
				  <span class="am-icon-plus"></span>
				  <?php echo $ld['add'] ?>
				</a>
<?php }?>
</p>
<?php echo $form->create('OpenKeyword',array('action'=>'/remove/','name'=>'OpenCallKeywordForm','type'=>'get',"onsubmit"=>"return false;"));?>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table id="tablelist" class="am-table  table-main">
        <thead>
        <tr>
            <th><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span><b><?php echo $ld['open_model'];?></b></label></th>
            
            <th><?php echo $ld['open_model_account'];?></th>
            <th><span id="edit"></span><?php echo $ld['keyword']?></th>
            <th class="am-hide-sm-down"><?php echo $ld['type']?></th>
            <th class="am-hide-sm-down"><?php echo $ld['status']; ?></th>
            <th style="width:250px;"><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($key_list) && sizeof($key_list)>0){foreach($key_list as $k=>$v){?>
            <tr>
                <td><label style="margin:0 0 0 0;" class="am-checkbox am-success"><span class="am-hide-sm-down"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['OpenKeyword']['id']?>" /></span><?php echo ($v['OpenKeyword']['open_type'] == 'wechat')?$ld['wechat']: $v['OpenKeyword']['open_type'];?></label></td>
             
                <td><?php echo $v['OpenKeyword']['open_type_id']?></td>
                <td class="am-hide-sm-down"><div style="height:auto;white-space: normal;"><?php echo $v['OpenKeyword']['keyword']?></div></td>
                <td class="am-hide-sm-down"><?php if($v['OpenKeyword']['match_type']==0){echo $ld['fuzzy_matching'];}else{echo $ld['perfect_matching'];}?></td>
                <td>
                    
                    <?php if($svshow->operator_privilege("open_keywords_edit")){
                        if($v['OpenKeyword']['status']=='1'){
                      
                         echo '<span class="am-icon-check am-yes" style="cursor:pointer;" onclick=change_state(this,"open_keywords/toggle_on_status",'.$v["OpenKeyword"]["id"].')></div>';
                        }else{
                       
                            echo '<div style="cursor:pointer;"  class="am-icon-close am-no" onclick=change_state(this,"open_keywords/toggle_on_status",'.$v["OpenKeyword"]["id"].')></div>';
                        }
                    }else{
                        if($v['OpenKeyword']['status']=='1'){
                            echo '<span class="am-icon-check am-yes"></span>';
                        }else{
                            echo '<span class="am-icon-close am-no"></span>';
                        }
                    } ?>
                </td>
                <td class="am-action">
                    <?php if($svshow->operator_privilege("open_keywords_edit")){?><a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/open_keywords/view/'.$v['OpenKeyword']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
                    <?php } ?><?php if($svshow->operator_privilege("open_keywords_remove")){?>
                            <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(j_confirm_delete){remove1('<?php echo $v['OpenKeyword']['id'] ?>');}">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a><?php } ?>
                            </td>
            </tr>
        <?php }}else{
            $noo=1;
            ?>
            <tr>
                <td colspan="9" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
    <div id="btnouterlist" class="btnouterlist" style="<?php if(isset($noo)&&$noo==1){echo 'display:none';} ?>">
        <?php if($svshow->operator_privilege("open_keywords_remove")){?>
        		<div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-hide-sm-down" style="left:6px;">
						<div class="am-fl">
					          <label class="am-checkbox am-success" style="display: inline;">
					            <input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox"
								value="checkbox" data-am-ucheck><span><?php echo $ld['select_all']?></span>
					          </label>
			            	</div>
						<div class="am-fl" style="margin-left:3px;">
					            <select name="barch_opration_select" id="barch_opration_select" data-am-selected  onchange="barchopen_opration_select_onchange(this)">
					              <option value="0"><?php echo $ld['batch_operate']?></option>
					              <option value="delete"><?php echo $ld['batch_delete']?></option>
							<?php if( isset($profile_id) && !empty($profile_id) ){ ?>
					    		  <option value="export_csv"><?php echo $ld['batch_export']?></option>
					    		  <?php } ?>
					            </select>
			            	</div> 
						<div class="am-fl" style="display:none;margin-left:3px;">
			                    <select id="export_csv" data-am-selected name="barch_opration_select_onchange" >
			                        <option value=""><?php echo $ld['click_select']?></option>
			                        <option value="all_export_csv"><?php echo  $ld['all_export']?></option>
			                        <option value="choice_export"><?php echo $ld['choice_export']?></option>
			                       
			                    </select>&nbsp;
			              	</div>
						<div class="am-fl" style="margin-left:3px;">
			               	   <button type="button" class="am-btn am-radius am-btn-danger am-btn-sm" onclick="select_batch_operations()"><?php echo $ld['submit']?></button>
			              	</div>
				</div>
        	
        	
        	
        	
           
        <?php }?>
        <div class="am-u-lg-6 am-u-md-6 am-u-sm-6 am-fr"><?php echo $this->element('pagers')?></div>
        <div class="am-cf"></div>
    </div>
</div>
<?php echo $form->end()?>
<script type="text/javascript">
function select_batch_operations(){
	var barch_opration_select = document.getElementById("barch_opration_select");
      var export_csv = document.getElementById("export_csv");
      if(barch_opration_select.value==0){
      	  	alert(j_select_operation_type);
			return;
      }
      if(barch_opration_select.value=='delete'){
		removeAll();
	}
	if(barch_opration_select.value=='export_csv'){
		if(export_csv.value=='all_export_csv'){
			window.location.href=admin_webroot+"/open_keywords/all_export_csv";
		
		}
		if(export_csv.value=='choice_export'){
			choice_upload();
		}
	}
}



//选择导出
function choice_upload(){
	var bratch_operat_check = document.getElementsByName("checkboxes[]");
	var postData = "";
	for(var i=0;i<bratch_operat_check.length;i++){
		if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
		}
	}
	if( postData=="" ){
		alert("<?php echo $ld['please_select'] ?>");
		return;
	}else{
	window.location.href=admin_webroot+"open_keywords/choice_export/"+postData;
	
	}
}	

//触发子下拉
function barchopen_opration_select_onchange(obj){
	if(obj.value!="export_csv"){
		$("#export_csv").parent().hide();		
	}
	$("select[name='barch_opration_select_onchange[]']").parent().hide();
	
	var export_csv=document.getElementById("export_csv").value;
	
	if(obj.value=="export_csv"){
		if(export_csv=="all_export_csv"){
			$("#export_csv").parent().show();
		}else{
			$("#export_csv").parent().show();
		}
	}

}



    function checkbox(){
        var str=document.getElementsByName("box");
        var leng=str.length;
        var chestr="";
        for(i=0;i<leng;i++){
            if(str[i].checked == true)
            {
                chestr+=str[i].value+",";
            };
        };
        return chestr;
    };
//批量删除
    function removeAll(){
        var ck=document.getElementsByName('checkboxes[]');
        var j=0;
        for(var i=0;i<=parseInt(ck.length)-1;i++)
        {
            if(ck[i].checked)
            {
                j++;
            }
        }
        if(j>=1){
            if(confirm("<?php echo $ld['confirm_delete'] ?>"))
            {
                document.OpenCallKeywordForm.action=admin_webroot+"open_keywords/remove/";
                document.OpenCallKeywordForm.onsubmit= "";
                document.OpenCallKeywordForm.submit();
            }
        }
    }

    function remove1(id){
        if(confirm("<?php echo $ld['confirm_delete'] ?>")){
            var func="open_keywords/remove";
            var sUrl = admin_webroot+func;//访问的URL地址
            $.ajax({
                type: "POST",
                url: sUrl,
                dataType: 'json',
                data: {id: id},
                success: function (result) {
                    if(result.flag == 1){
                        alert(j_deleted_success);
                        window.location.reload();
                    }
                    if(result.flag == 2){
                        alert(result.message);
                    }
                }
            });
        }
    }
    //状态切换
    function change_state(obj,func,id){
    var ClassName=$(obj).attr('class');
    var val = (ClassName.match(/yes/i)) ? 0 : 1;
    var postData = "val="+val+"&id="+id;
    $.ajax({
        url:admin_webroot+func,
        Type:"POST",
        data: postData,
        dataType:"json",
        success:function(data){
            if(data.flag == 1){
                if(val==0){
                    $(obj).removeClass("am-icon-check am-yes");
                    $(obj).addClass("am-icon-close am-no");
                }
                if(val==1){
                    $(obj).removeClass("am-icon-close am-no");
                    $(obj).addClass("am-icon-check am-yes");
                }
            }

        }
    });
}
</script>