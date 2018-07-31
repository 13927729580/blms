<style>
.am-form-label{font-weight:bold;text-align:center;margin-top:7px;left:20px;}	
</style>
<div class="listsearch">
    <?php echo $form->create('votes',array('action'=>'/','name'=>'searchtrash','type'=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0;">
    
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-4 am-u-lg-3 am-u-md-3 am-form-label" style="margin-top:5px;"><?php echo $ld['posted_time']?></label>
            <div class="am-u-sm-3 am-u-lg-3 am-u-md-3" style="padding:0 0rem;width:32%;">
              <div class="am-input-group">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="date" value="<?php echo @$date;?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
            </div>
            <em class=" am-u-sm-1 am-u-lg-1 am-u-md-1 am-text-center" style="padding: 0.35em 0px;width:5%;">-</em>
            <div class="am-u-sm-3 am-u-lg-3 am-u-md-3  am-u-end" style="padding:0 0rem;width:32%;">
              <div class="am-input-group">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="date2" value="<?php echo @$date2;?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
            </div>
        </li>
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-4 am-u-lg-3 am-u-md-3  am-form-label" style="margin-top:3px;"><?php echo $ld['status']?></label>
            <div class="am-u-sm-7 am-u-lg-7 am-u-md-7" style="padding:0 0.5rem;">
                <select name="mystatus" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?> '}" >
                    <option value=""><?php echo $ld['all_data']?></option>
                    <option value="1" <?php if(@$mystatus=="1"){echo "selected";}?>><?php echo $ld['valid']?></option>
                    <option value="0" <?php if(@$mystatus=="0"){echo "selected";}?>><?php echo $ld['invalid']?></option>
                </select>
            </div>
         </li>
        	<li style="margin:0 0 10px 0"  >
        	 <label class="am-u-sm-4 am-u-lg-3 am-u-md-4  am-form-label"> </label>		
        	<div class="am-u-sm-7 am-u-lg-7 am-u-md-7" >
                  <input class="am-btn am-btn-success am-radius am-btn-sm" type="submit" value="<?php echo $ld['search'];?>"/>
            </div>		
             </li>
    </ul>
    				
    <?php echo $form->end();?>
</div>
<p class="am-u-md-12 am-text-right am-btn-group-xs">
    <?php if($svshow->operator_privilege('votes_add')){?>
    	<?php if(  isset($profile_id) && !empty($profile_id)   ) {  ?>
    			<a  class="am-btn am-btn-default am-btn-sm"   href="<?php echo $html->url('/votes/vote_upload/') ?>"  >
			<?php  echo $ld['bulk_upload'] ?>
			</a>
	  <?php } ?>
	  	        <a class="am-btn am-btn-warning am-radius am-btn-sm" href="<?php echo $html->url('add/'); ?>">
	  	         <span class="am-icon-plus"></span> 
	  	         <?php echo $ld['vote_add_subject'] ?>
		      </a>	
	  		
	    <?php }?>
</p>
	
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12 am-u-lg-12">
    <table id="t1" class="am-table  table-main">
        <thead>
        <tr>
            <th class="thwrap ">
     <label class="am-checkbox am-success" style="font-weight:bold;  margin:0 0 0 0;"><span class="am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></span> <?php echo $ld['vote_investigat_subject']?></label>
          </th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['start_date']?></th>
            <th class="thwrap am-hide-md-down"><?php echo $ld['end_date']?></th>
            <th class="am-text-center"><?php echo $ld['vote_num']?></th>
            <th class="am-text-center"><?php echo $ld['valid']?></th>
            <th  ><?php echo $ld['operate']?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($vote_list) && sizeof($vote_list)>0){?>
            <?php foreach($vote_list as $k=>$v){?> 
                 <tr>
                    <td>
                     <label class="am-checkbox am-success">
                    <span class="am-hide-sm-down"><input type="checkbox" name="checkboxes[]" value="<?php echo $v['Vote']['id']?>" data-am-ucheck/></span>
                     <span onclick="javascript:listTable.edit(this, 'Vote/update_brand_code/', <?php echo $v['Vote']['id']?>)">
                    <?php echo $v['VoteI18n']['name'];?>
                     </span>
                     </label>
                    </td>
                    <td class="thwrap am-hide-md-down"><?php echo $v['Vote']['start_time']?></td>
                    <td class="thwrap am-hide-md-down"><?php echo $v['Vote']['end_time'];?></td>
                    <td class="am-text-center"><?php echo $v['Vote']['vote_count'];?></td>
                    <td class="am-text-center"><?php if ($v['Vote']['status'] == 1){?>
                    		<div style="cursor:pointer;color:#5eb95e" class="am-icon-check"></div>
                        <?php }elseif($v['Vote']['status'] == 0){?>
                            <div style="cursor:pointer;color:#dd514c" class="am-icon-close"></div>
                        <?php }?></td>
                    <td class="am-action" style="width:210px;"><?php
                        if($svshow->operator_privilege("vote_logs_view")){
                            echo $html->link($ld['statistics'],"/votes/vote_logs/{$v['Vote']['id']}",array("class"=>"am-btn am-btn-default am-btn-xs am-radius"));
                        }
                        if($svshow->operator_privilege("votes_edit")){?> <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/votes/edit/'.$v['Vote']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                    </a>
                      <?php   }
                        if($svshow->operator_privilege("votes_remove")){?> <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="if(confirm(j_confirm_delete)){window.location.href=admin_webroot+'votes/remove/<?php echo $v['Vote']['id'] ?>';}">
                        <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                      </a>
                          <?php  }?></td>
                </tr>
            <?php }?>
        <?php }else{?>
            <tr>
                <td colspan="6" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php }?>
        </tbody>
    </table>
     <?php if($svshow->operator_privilege('brands_remove')){?>
    <?php if(isset($vote_list) && sizeof($vote_list)){?>
    		<div id="btnouterlist" class="btnouterlist">
			<div class="am-u-lg-6 am-u-md-6 am-u-sm-12  am-hide-sm-only" style="margin-left:6px;">
							<div class="am-fl">
								<label class="am-checkbox am-success" style="font-size:14px; line-height:14px;">
									<input onclick="listTable.selectAll(this,&quot;checkboxes[]&quot;)" type="checkbox" data-am-ucheck />
									<span><?php echo $ld['select_all']?></span>
								</label>
				            	</div>
							<div class="am-fl" style="margin-left:3px;">
						            <select name="barch_opration_select" id="barch_opration_select" data-am-selected  onchange="batch_vote_opration_select_onchange(this)">
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
		
            <div class="am-cf"></div>
		</div>
		<div  class="btnouterlist">
				<div class="am-u-lg-4 am-u-md-6 am-hide-sm-only">
					&nbsp;
				</div>
				<div class="am-u-lg-8 am-u-md-6 am-u-sm-12">		
				<?php echo $this->element('pagers')?>
				</div>
		</div>
    
        <?php }?> 	
    <?php }?>
</div>
	
<script >
function select_batch_operations(){
	var barch_opration_select = document.getElementById("barch_opration_select");
      var export_csv = document.getElementById("export_csv");
      if(barch_opration_select.value==0){
      	  	alert(j_select_operation_type);
			return;
      }
      if(barch_opration_select.value=='delete'){
		batch_operations();
	}
	if(barch_opration_select.value=='export_csv'){
		if(export_csv.value=='all_export_csv'){
			window.location.href=admin_webroot+"/votes/all_export_csv";
		
		}
		if(export_csv.value=='choice_export'){
			choice_upload();
		}
	}
}
//批量删除操作
   function batch_operations(){ 
   	   	var bratch_operat_check = document.getElementsByName("checkboxes[]");
   	   	var postData = "";
   	   		for(var i=0;i<bratch_operat_check.length;i++){
		     if(bratch_operat_check[i].checked){
			postData+="&checkboxes[]="+bratch_operat_check[i].value;
	  	     }
	}
	
		if( postData=="" ){
		alert(j_please_select);
		return;
   	    }
   	    	if(confirm(j_confirm_delete)){
   	    		$.ajax({
   	    			type:"POST",
   	    		       url:admin_webroot+"votes/batch_operations/",
   	    			data:postData,
   	    		      datatype: "json",
   	    			success:function(data){
				window.location.href = window.location.href;
			}
   	    		});
   	    	
   	    	
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
	window.location.href=admin_webroot+"votes/choice_export/"+postData;
	
	}
}	
//触发子下拉
function batch_vote_opration_select_onchange(obj){
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
	
	
	

</script>