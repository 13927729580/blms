<style type="text/css">
    .am-panel-title div{font-weight:bold;}
    #SearchForm{padding-top:8px;}
    #SearchForm li{margin-bottom:10px;}
    #SearchForm .am-selected-list li{margin-bottom: 0px;}
    .am-form-horizontal{padding-top:0px;}
    .paddingTop{padding-top: 10px;padding-bottom: 10px;}
</style>
<div>
    <?php echo $form->create('Evaluation',array('action'=>'/','name'=>"SeearchForm",'id'=>"SearchForm","type"=>"get",'onsubmit'=>'return formsubmit();','class'=>'am-form am-form-horizontal'));?>
    <ul class="am-avg-lg-3 am-avg-md-2 am-avg-sm-1">
    	<li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">渠道</label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7  ">
                <select name="operator_channel_id" id='operator_channel_id' data-am-selected="{maxHeight:300}" >
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <?php foreach ($operator_channel_list as $k => $v) { ?>
                	<option value="<?php echo $k ?>" <?php if(isset($operator_channel_id)&&$operator_channel_id==$k){echo 'selected';} ?>><?php echo $v ?></option>
                    <?php } ?>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['status']?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7  ">
                <select name="status" id='status' data-am-selected="{maxHeight:300}" >
                    <option value="-1"><?php echo $ld['all_data']?></option>
                    <option value="0" <?php if(isset($status)&&$status==0){echo 'selected';} ?>>有效</option>
                    <option value="1" <?php if(isset($status)&&$status==1){echo 'selected';} ?>>无效</option>
                </select>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text">创建时间</label>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3" style="padding-left:0;padding-right:0.5rem;">
                <div class="am-input-group">
	                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" name="start_date_time" value="<?php echo isset($start_date_time)?$start_date_time:"";?>" />
	                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
	                <i class="am-icon-remove"></i>
	                </span>
	            </div>
            </div>
            <div class=" am-text-center am-fl " style="margin-top:7px;">-</div>
            <div class="am-u-lg-4 am-u-md-4 am-u-sm-3" style="padding-left:0.5rem;padding-right: 0;">
                <div class="am-input-group">
                <input type="text"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"  name="end_date_time" value="<?php echo isset($end_date_time)?$end_date_time:"";?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
            </div>
            </div>
        </li>
        <li>
            <label class="am-u-lg-3 am-u-md-3 am-u-sm-4 am-form-label-text"><?php echo $ld['keyword'];?></label>
            <div class="am-u-lg-8 am-u-md-8 am-u-sm-7">
                <input type="text" name="keyword" id="keyword" value="<?php echo isset($keyword)?$keyword:'';?>" placeholder="配置编码/名称" />
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
<div>
    <div class="am-text-right am-btn-group-xs" style="clear:both;margin:10px auto;">
    	<?php if($svshow->operator_privilege("add_channel_config")){ ?>
        <a class="mt am-btn am-btn-warning am-seevia-btn-add am-btn-sm am-radius" href="<?php echo $html->url('/operator_channel_configs/view/0'); ?>">
            <span class="am-icon-plus"></span> <?php echo $ld['add'] ?>
        </a>
        <?php } ?>
    </div>
    <div class="listtable_div_btm">
        <div class="am-g" style="font-weight: bold;">
        	<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 paddingTop">渠道</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 paddingTop">配置编码</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 paddingTop">名称</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 paddingTop">描述</div>
            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 paddingTop">状态</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 paddingTop">创建时间</div>
            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 paddingTop">操作</div>
        </div>
        <?php if(isset($operator_channel_config) && sizeof($operator_channel_config)>0){foreach($operator_channel_config as $k=>$v){?>
            <div class="am-g">
                <div class="listtable_div_top" >
                    <div style="margin:10px auto;" class="am-g">
                    	<div class="am-u-lg-1 am-u-md-1 am-u-sm-1 paddingTop"><?php echo isset($operator_channel_list[$v['OperatorChannelConfig']['operator_channel_id']])?$operator_channel_list[$v['OperatorChannelConfig']['operator_channel_id']]:'-'; ?>&nbsp;</div>
                        <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 paddingTop"><?php echo $v['OperatorChannelConfig']['code'] ?>&nbsp;</div>
			            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 paddingTop"><?php echo $v['OperatorChannelConfig']['name'] ?>&nbsp;</div>
			            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 paddingTop"><?php echo $v['OperatorChannelConfig']['description'] ?>&nbsp;</div>
			            <div class="am-u-lg-1 am-u-md-1 am-u-sm-1 paddingTop">
			            	<?php if ($v['OperatorChannelConfig']['status'] == 1) {?>
                                <span class="am-icon-check am-yes"></span>
                            <?php }elseif($v['OperatorChannelConfig']['status'] == 0){ ?>
                                <span class="am-icon-close am-no"></span>
                            <?php } ?>
			            </div>
			            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 paddingTop"><?php echo $v['OperatorChannelConfig']['created'] ?>&nbsp;</div>
			            <div class="am-u-lg-2 am-u-md-2 am-u-sm-2 paddingTop">
			            	<?php if($svshow->operator_privilege("edit_channel_config")){ ?>
							<a class="mt am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/operator_channel_configs/view/'.$v['OperatorChannelConfig']['id']); ?>">
                                <span class="am-icon-pencil-square-o"></span> <?php echo '编辑'; ?>
                            </a>
                            <?php } ?>
                            <?php if($svshow->operator_privilege("remove_channel_config")){ ?>
                            <a class="mt am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:;" onclick="list_delete_submit(admin_webroot+'operator_channel_configs/remove/<?php echo $v['OperatorChannelConfig']['id'] ?>');">
			                    <span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
			                </a>
			                <?php } ?>
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
    <div id="btnouterlist" class="btnouterlist">
		<?php if(isset($operator_channel_config)&&sizeof($operator_channel_config)>0){echo $this->element('pagers');} ?>
	</div>
</div>
<script type="text/javascript">
    function formsubmit(){
    	var operator_channel_id=document.getElementById('operator_channel_id').value;
        var keyword=document.getElementById('keyword').value;
        var status=document.getElementById('status').value;
        var start_date_time = document.getElementsByName('start_date_time')[0].value;
        var end_date_time = document.getElementsByName('end_date_time')[0].value;
        var url = "status="+status+"&keyword="+keyword+"&start_date_time="+start_date_time+"&end_date_time="+end_date_time+"&operator_channel_id="+operator_channel_id;
        window.location.href = encodeURI(admin_webroot+"operator_channel_configs?"+url);
    }
</script>