<style type="text/css">
    .am-checkbox {margin-top:0px; margin-bottom:0px;}
    
    .btnouterlist{overflow: visible;}
    .am-yes{color:#5eb95e;}
    .am-no{color:#dd514c;}
    .am-panel-title div{font-weight:bold;}
    #btnouterlist .am-ucheck-icons{top:-3px;}
    .am-form-horizontal .am-form-label{padding-top: 0.5em;}
       .am-checkbox .am-icon-checked, .am-checkbox .am-icon-unchecked, .am-checkbox-inline .am-icon-checked, .am-checkbox-inline .am-icon-unchecked, .am-radio .am-icon-checked, .am-radio .am-icon-unchecked, .am-radio-inline .am-icon-checked, .am-radio-inline .am-icon-unchecked {
    background-color: transparent;
    display: inline-table;
    left: 0;
    margin: 0;
    position: absolute;
    top: 3px;
    transition: color 0.25s linear 0s;
}
</style>


<div style="margin-top:10px;">
                    <?php echo $form->create('StaticPage',array('action'=>'/','name'=>'SPageForm','type'=>'get','class'=>'am-form-horizontal'));?>
                 
                    <ul class=" am-avg-md-2 am-avg-lg-3 am-avg-sm-1">
                       
                        <li  style="margin:0 0 10px 0">
                            <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-left">发票类型</label> 
                                <div class="am-u-lg-7  am-u-md-7 am-u-sm-7"  >
                                <select name="invoice_type"  data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?> '}">
                            <option value=""><?php echo $ld['all_data']?> </option>
                            <option value="0" <?php echo @$types=='0'?'selected':''; ?>>普通发票 </option>
                            <option value="1" <?php echo @$types=='1'?'selected':''; ?>>增值税普通发票 </option>
                            <option value="2" <?php echo @$types=='2'?'selected':''; ?>>增值税专用发票 </option>
                    </select>
                                </div>
                        </li>
                        <li style="margin:0 0 10px 0">  
                <label class="am-u-lg-3  am-u-md-3 am-u-sm-3 am-form-label am-text-left"><?php echo $ld['status'];?></label>
                <div class="am-u-lg-7 am-u-md-7 am-u-sm-7  am-u-end">
                    <select name="status"  data-am-selected="{noSelectedText:'<?php echo $ld['all_data']; ?> '}">
                        <option value=""><?php echo $ld['all_data']?> </option>
                        <option value="1" <?php echo @$status=='1'?'selected':''; ?>><?php echo $ld['valid']?> </option>
                        <option value="0" <?php echo @$status=='0'?'selected':''; ?>><?php echo $ld['invalid']?> </option>
                    </select>
                </div>               
            </li> 
                        <li  style="margin:0 0 10px 0">
                            <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-left">发票金额</label> 
                                <div class="am-u-lg-4  am-u-md-4 am-u-sm-4"  >
                                <input type="text" name="money1" class="am-form-field am-radius"  value="<?php echo @$moneys1;?>" placeholder="" />

                               </div>
                                <span style="float: left;"><em>-</em></span>
                                
                                <div class="am-u-lg-4  am-u-md-4 am-u-sm-4"  >
                                <input type="text" name="money2" class="am-form-field am-radius"  value="<?php echo @$moneys2;?>" placeholder="" />
                                </div>
                        </li>
                        <li  style="margin:0 0 10px 0">
                            <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-left">开票日期</label> 
                                <div class="am-u-lg-4  am-u-md-4 am-u-sm-4"  style="padding-right:0.5rem;width:37%;">
                                <div class="am-input-group">
                                <input type="text" name="date1" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  class="am-form-field am-radius dateonly"  value="<?php echo @$dates1;?>" placeholder="" style="cursor:pointer;background-color: #fff;" />
                                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>

                               </div>
                                <span style="float: left;"><em>-</em></span>
                                
                                <div class="am-u-lg-4  am-u-md-4 am-u-sm-4"  style="padding-left:0.5rem;padding-right:0;">
                                    <div class="am-input-group">
                                <input type="text" name="date2" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  class="am-form-field am-radius dateonly"  value="<?php echo @$dates2;?>" placeholder="" style="cursor:pointer;background-color: #fff;" />
                                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
                                </div>
                        </li>
            <li style="margin:0 0 10px 0" style="">
                <label class="am-u-lg-3 am-u-md-3 am-u-sm-3 am-form-label am-text-left" style="padding-right:0;">发票号/内容</label> 
                    <div class="am-u-lg-7  am-u-md-7 am-u-sm-7"  >
                    <input type="text" name="title" class="am-form-field am-radius"  value="<?php echo @$titles;?>" placeholder="发票号/内容" />
                    </div>
            </li>
            <li style="margin:0 0 10px 0">
                                
                <div class="am-u-lg-2 am-u-md-2 am-u-sm-6" style="" >
                    <div class="am-u-lg-12" style="padding-left:10px;">
                        <button type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search']?>"  onclick="search_page()"><?php echo $ld['search'];?></button>
                    </div>
                    
                </div>
            </li>       
            </ul>
    <?php echo $form->end();?><br/>
    <div class="am-g am-other_action  am-text-right am-btn-group-xs" style="margin-bottom:10px;">
        <?php if($svshow->operator_privilege('invoice_add')){?>
            <a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/invoices/uploadinvoice'); ?>">批量上传</a> 
            <a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/invoices/view/0'); ?>">
                <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
            </a> 
        <?php }?>
    </div>
    <?php echo $form->create('StaticPage',array('action'=>'/','name'=>'PageForm','type'=>'get',"onsubmit"=>"return false;"));?>
    <div class="am-panel-group am-panel-tree">
        <div class="  listtable_div_btm am-panel-header">
            <div class="am-panel-hd">
                <div class="am-panel-title am-g">
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                        <label class="am-checkbox am-success  am-hide-sm-only" style="font-weight:bold;padding-top:0">
                            <input type="checkbox" data-am-ucheck onclick='listTable.selectAll(this,"checkbox[]")'/>
                            发票号
                        </label>
                        <label class="am-checkbox am-success  am-show-sm-only" style="font-weight:bold;padding-top:0">
                            发票号
                        </label>
                    </div>
                    <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">发票类型</div>
                    <div class="am-u-lg-2 am-u-md-3 am-hide-sm-only">发票内容</div>
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">发票金额</div>
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">开票日期</div>
                    <!--<div class="am-u-lg-2 am-show-lg-only"><?php echo $ld['added_time']?></div>-->
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">购买方</div>
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-2">状态</div>
                    <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><?php echo $ld['z_operation']?></div>
                </div>
            </div>
        </div>
        <?php if(isset($pages) && sizeof($pages)>0){foreach($pages as $k=>$v){?>
        <div>
        <?php //pr($v); ?>
        <div class="listtable_div_top am-panel-body">
            <div class="am-panel-bd am-g">
                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                    <label class="am-checkbox am-success  am-hide-sm-only" style="padding-top:0">
                        <input type="checkbox" name="checkbox[]" data-am-ucheck value="<?php echo $v['Invoice']['id']?>" class="invoice-checkbox" />
                        <span onclick="javascript:listTable.edit(this, 'static_pages/update_page_title/', <?php echo $v['Invoice']['invoice_number']?>)"><?php echo $v['Invoice']['invoice_number'] ?></span>&nbsp;
                    </label>
                    <label class="am-checkbox am-success  am-show-sm-only" style="padding-top:0"> 
                        <span ><?php echo $v['Invoice']['invoice_number'] ?></span>&nbsp;
                    </label>
                </div>
                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                    <?php if($v['Invoice']['invoice_type'] == '1'){
                        echo '增值税普通发票' ;
                    }else if($v['Invoice']['invoice_type'] == '0'){
                        echo '普通发票';
                    }else if($v['Invoice']['invoice_type'] == '2'){
                        echo '增值税专用发票';
                    }
                    
                    ?>&nbsp;
                </div>
                <div class="am-u-lg-2 am-u-md-3 am-hide-sm-only">
                    <?php if(isset($v['Invoice']['invoice_content'])){echo $v['Invoice']['invoice_content'];}?>&nbsp;
                </div>
                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
                    <?php if(isset($v['Invoice']['invoice_money'])){echo $v['Invoice']['invoice_money'];}?>&nbsp;
                </div>
                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1" style="padding-right:0;">
                    <span ><?php echo date('Y-m-d',strtotime($v['Invoice']['builling_date']));?>&nbsp;</span>
                </div>
                <div class="am-u-lg-1 am-u-md-1 am-u-sm-1"><span ><?php echo $v['Invoice']['purchaser_name']?>&nbsp;</span></div>
                <div class="am-u-lg-1 am-u-md-1 am-u-sm-2">
                    <?php if( $v['Invoice']['status'] == 1){?>
                        <span class="am-icon-check am-yes" style="cursor:pointer;" onclick="change_state(this,'static_pages/toggle_on_status',<?php echo $v['Invoice']['id'];?>)"></span>
                    <?php }else{ ?>
                        <span class="am-icon-close am-no" style="cursor:pointer;" onclick="change_state(this,'static_pages/toggle_on_status',<?php echo $v['Invoice']['id'];?>)"></span>   
                    <?php }?>&nbsp;
                </div>
                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 seolink am-btn-group-xs am-action">
                    <?php   if($svshow->operator_privilege("invoice_edit")){?>
                 <a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/invoices/view/'.$v['Invoice']['id']); ?>">
                        <span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
                      </a> 
                     <?php }?>     
                    <?php   if($svshow->operator_privilege("invoice_remove")){?>
                      <a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" 
                        href="javascript:;" 
                        onclick="list_delete_submit(admin_webroot+'invoices/remove/<?php echo $v['Invoice']['id'] ?>')">
                                    <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
                                </a>  
                                <?php }?>          
                        
                   
                </div>
            </div>
        </div>
   
        <?php }}else{?>
            <div class="no_data_found"><?php echo $ld['no_data_found']?></div>
                    
        <?php }?>
    </div>
    <?php if($svshow->operator_privilege("static_page_view")){?>
    <?php if(isset($pages) && sizeof($pages)){?>
    <div id="btnouterlist" class="btnouterlist" > 
        <div class="am-u-lg-5 am-u-md-6 am-u-sm-12 am-hide-sm-only" style="margin-left:7px;">
            <div class="am-fl">
                <label class="am-checkbox am-success" style="margin:5px 5px 5px 0px;">
                    <input onclick='listTable.selectAll(this,"checkbox[]")' data-am-ucheck type="checkbox">
                    <?php echo $ld['select_all']?>
                </label>
            </div>
            <div class="am-fl">
                    <select name="act_type" id="act_type" onchange="operate_change(this)" data-am-selected>
                        <option value="0"><?php echo $ld['all_data']?></option>
                        <?php   if($svshow->operator_privilege("invoice_remove")){?>
                        <option value="delete"><?php echo $ld['batch_delete']?></option>
                        <?php } ?>
                        <option value="export"><?php echo $ld['batch_export']?></option>
                        <!-- <option value="a_status"><?php echo $ld['log_batch_change_status']?></option> -->
                    </select>
                     <div  style="display:none;margin-left::5px;margin-bottom:5px;margin-top:5px;">
                    <select name="is_yes_no" id="is_yes_no" data-am-selected>
                        <option value="1"><?php echo $ld['yes']?></option>
                        <option value="0"><?php echo $ld['no']?></option>
                    </select>
                     </div> 
                     <div class="am-fr" style="margin-left:3px;"><button type="button" class="am-btn am-radius am-btn-danger am-btn-sm" onclick="diachange()"><?php echo $ld['submit']?></button></div>
            </div> 
        </div>
        <div class="am-u-lg-6 am-u-md-12 am-u-sm-12">       
            <?php echo $this->element('pagers');?>
        </div>
        <div class="am-cf"></div>
    </div>
    <?php }?>
    <?php }?>
    <?php echo $form->end();?>
</div>
<script>
function operate_change(obj){
    if(obj.value=="delete" || obj.value=="0"){
        $("#is_yes_no").parent().hide();
    }
    if(obj.value=="a_status"){
        $("#is_yes_no").parent().show();
    }
}
function diachange(){
    var a=document.getElementById("act_type");
    if(a.value=='delete'){
        for(var j=0;j<a.options.length;j++){
            if(a.options[j].selected){
                var vals = a.options[j].text ;
            }
        }
        var id=document.getElementsByName('checkbox[]');
        var i;
        var j=0;
        var image="";
        for( i=0;i<=parseInt(id.length)-1;i++ ){
            if(id[i].checked){
                j++;
            }
        }
        if( j>=1 ){
        //  layer_dialog_show('确定'+vals+'?','batch_action()',5);
            if(confirm("<?php echo $ld['submit']?>"+vals+'?'))
            {
                batch_action();
            }
        }else{
        //  layer_dialog_show('请选择！！','batch_action()',3);
            if(confirm(j_please_select))
            {
                batch_action();
            }
        }
    }else if(a.value=="export"){
        // var postData = $('form[name="PageForm"]').serialize();
        // $.ajax({
        //     url:admin_webroot+'/invoices/batch_export',
        //     type:"POST",
        //     data: postData,
        //     dataType:"json",
        //     success:function(data){
                
        //     }   
        // });
        document.PageForm.action=admin_webroot+"invoices/batch_export";
        document.PageForm.onsubmit= "";
        document.PageForm.submit();
    }
}
function batch_action()
{
document.PageForm.action=admin_webroot+"invoices/batch";
document.PageForm.onsubmit= "";
document.PageForm.submit();
}
function search_page()
{
document.SPageForm.action=admin_webroot+"invoices/";
document.SPageForm.onsubmit= "";
document.SPageForm.submit();
}
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
if($(".occupy").html() == ''){
    $(".occupy").html() = " ";
}


</script>