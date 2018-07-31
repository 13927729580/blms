<style type="text/css">
.am-radio, .am-checkbox {margin-top:0px; margin-bottom:0px;}
.am-panel-title div{font-weight:bold;}
.am-dropdown-toggle{background:#fff;border:1px solid #ccc;}
</style>
<div>
    <?php echo $form->create('Evaluation',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-horizontal'));?>
    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">部门</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="keyword" id="keyword" value="<?php echo isset($keyword)?$keyword:'';?>"/>
            </div>
        </li>
        <li >
            <label class="am-u-sm-3 am-u-md-3 am-u-sm-4 am-form-label">&nbsp;</label>
            <div class="am-u-sm-8 am-u-md-8 am-u-sm-7 " style="float:left;">
                <input type="button"  class="am-btn am-btn-success am-btn-sm am-radius"  value="<?php echo $ld['search'];?>" onclick="formsubmit()" />
            </div>
        </li>
    </ul>
    <?php echo $form->end()?>
</div>
<?php if($svshow->operator_privilege("department_add")){ ?>
<div class="am-g am-other_action ">
    <div class="am-fr am-u-lg-12 am-btn-group-xs" style="text-align:right;margin-bottom:10px;margin-right:15px;">
        <a class="am-btn am-btn-warning am-btn-sm am-radius" href="<?php echo $html->url('/departments/view/0'); ?>">
            <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
        </a>
    </div>
</div>
<?php } ?>
<?php echo $form->create('StaticPage',array('action'=>'/','name'=>'PageForm','type'=>'get',"onsubmit"=>"return false;",'method'=>'post'));?>
<div class="am-panel-group am-panel-tree">
    <div class="listtable_div_btm am-panel-header">
        <div class="am-panel-hd">
            <div class="am-panel-title am-g">
                <div class="am-u-lg-2 am-u-md-3 am-u-sm-5">
                    <label class="am-checkbox am-success" style="font-weight:bold;"><div class="am-hide-sm-down"><input onclick='listTable.selectAll(this,"checkboxes[]")' type="checkbox" data-am-ucheck/></div>
                        部门名称
                    </label>
                </div>
                <div class="am-u-lg-4  am-u-md-2 am-u-sm-3">部门主管</div>
                <div class="am-u-lg-4  am-u-md-2 am-u-sm-2">部门操作员</div>
                <div class="am-u-lg-2  am-u-md-2 am-u-sm-2">操作</div>
            </div>
        </div>
    </div>
    <?php if(isset($departments) && sizeof($departments)>0){foreach($departments as $k=>$v){?>
    <div>
        <div class="listtable_div_top am-panel-body">
            <div class="am-panel-bd am-g">
                <div class="am-u-lg-2 am-u-md-2 am-u-sm-2">
                    <label class="am-checkbox am-success">
                        <div class="am-hide-sm-down"><input type="checkbox" name="checkboxes[]" data-am-ucheck value="<?php echo $v['Department']['id']?>" /></div>
                        <?php echo $v['DepartmentI18n']['name'];?>&nbsp;
                    </label>
                </div>
                <div class="am-u-lg-4  am-u-md-2 am-u-sm-3" style="word-wrap:break-word;"><?php
                		$department_managers=trim($v['Department']['manager'])!=''?explode(',',$v['Department']['manager']):array();
                		foreach($department_managers as $kk=>$vv){
                			echo isset($operator_data[$vv])?$operator_data[$vv]:'';echo $kk<sizeof($department_managers)-1?"&nbsp;|&nbsp;":'';
                		}
                	?>&nbsp;</div>
                <div class="am-u-lg-4  am-u-md-2 am-u-sm-2" style="word-wrap:break-word;"><?php
                		$department_operator_list=isset($department_operators[$v['Department']['id']])?$department_operators[$v['Department']['id']]:array();
                		$department_operator_list=array_values($department_operator_list);
                		foreach($department_operator_list as $kk=>$vv){
                			echo isset($operator_data[$vv])?$operator_data[$vv]:'';echo $kk<sizeof($department_operator_list)-1?"&nbsp;|&nbsp;":'';
                		}
                	?>&nbsp;</div>
                <div class="am-u-lg-2  am-u-md-2 am-u-sm-2">
                	<?php if($svshow->operator_privilege("department_edit")){ ?>
                    <a class="am-icon-pencil-square-o am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/departments/view/'.$v['Department']['id']); ?>"> <?php echo $ld['edit']; ?>
                        </a>
                   <?php } ?>
                   <?php if($svshow->operator_privilege("department_remove")){ ?>
                    <a class="am-icon-trash-o am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'departments/remove/<?php echo $v['Department']['id'] ?>')"> <?php echo $ld['delete']; ?>
                    </a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php }}else{?>
        <div style="text-align:center;"><b><?php echo $ld['no_operators'];?></b></div>
    <?php }?>
</div>
<div id="btnouterlist" class="btnouterlist" > 
    <div class="am-u-lg-5 am-u-md-6 am-u-sm-12 am-hide-sm-only" style="margin-left:7px;">
       <?php if($svshow->operator_privilege("department_remove")){ ?>
        <div class="am-fl">
            <label class="am-checkbox am-success" style="margin:5px 5px 5px 0px;">
                <input onclick='listTable.selectAll(this,"checkboxes[]")' data-am-ucheck type="checkbox">
                <?php echo $ld['select_all']?>
            </label>
        </div>
        <div class="am-fl">
                <select name="act_type" id="act_type" data-am-selected>
                    <option value="0"><?php echo $ld['all_data']?></option>
                    <?php   if($svshow->operator_privilege("invoice_remove")){?>
                    <option value="delete"><?php echo $ld['batch_delete']?></option>
                    <?php } ?>
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
        <?php } ?>
    </div>
    <div class="am-u-lg-6 am-u-md-12 am-u-sm-12">       
        <?php echo $this->element('pagers');?>
    </div>
    <div class="am-cf"></div>
</div>
<?php echo $form->end();?>
<script type="text/javascript">
    function formsubmit(){
        var keyword=document.getElementById('keyword').value;
        var url = "keyword="+keyword;
        window.location.href = encodeURI(admin_webroot+"departments?"+url);
    }
    function diachange(){
        var a=document.getElementById("act_type");
        if(a.value!='0'){
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
        }
    }
    function batch_action(){
        document.PageForm.action=admin_webroot+"departments/batch";
        document.PageForm.onsubmit= "";
        document.PageForm.submit();
    }
</script>