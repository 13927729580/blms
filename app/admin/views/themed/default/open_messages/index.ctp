<div class="listsearch">
    <?php echo $form->create('open_messages',array('action'=>'/index','name'=>"SeearchForm","type"=>"get",'class'=>'am-form am-form-inline am-form-horizontal'));?>
    <ul class="am-avg-sm-1 am-avg-md-2 am-avg-lg-3" style="margin:10px 0 0 0">
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3 am-u-lg-2  am-u-md-3 am-form-label"><?php echo $ld['keyword'] ?></label>
            <div class="am-u-sm-7   am-u-lg-8   am-u-md-7 ">
                <input placeholder="<?php echo $ld['keyword'];?>" type="text" name="keywords" id="keywords" value="<?php echo isset($keywords)?$keywords:'';?>" />
            </div>
        </li><!--1--->
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3 am-u-lg-3 am-u-md-3 am-form-label"><?php echo $ld['reply'].''.$ld['status'] ?></label>
            <div class="am-u-sm-7 am-u-lg-7 am-u-md-7">
                <select name="selectstatus" data-am-selected="{noSelectedText:'<?php echo $ld['all_data'] ?>'}">
                    <option value=""><?php echo $ld['all_data'] ?></option>
                    <option value="1" <?php echo isset($selectstatus)&&$selectstatus=='1'?'selected':''; ?>><?php echo $ld['unreplied'] ?></option>
                    <option value="0" <?php echo isset($selectstatus)&&$selectstatus=='0'?'selected':''; ?>><?php echo $ld['replied'] ?></option>
                </select>
            </div>
        </li><!--\2--->
        <li style="margin:0 0 10px 0">
            <label class="am-u-sm-3  am-u-lg-3  am-u-md-3 am-form-label"><?php echo $ld['added_time'];?></label>
            <div class="am-u-sm-3  am-u-lg-3 am-u-md-3 " style="padding:0 0rem;width:32%;">
                <div class="am-input-group">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="start_date" value="<?php echo isset($start_date)?$start_date:'';?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
        </div>
            </div>
            <em class=" am-u-lg-1  am-u-sm-1 am-u-md-1 am-text-center " style="padding: 0.35em 0px;width:4%;">-</em>
            <div class="am-u-lg-3 am-u-sm-3 am-u-md-3 am-u-end" style="padding:0 0rem;width:32%;">
                <div class="am-input-group">
                <input style="min-height:35px;" type="text" class="am-form-field am-input-sm" data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}" readonly name="end_date" value="<?php echo isset($end_date)?$end_date:'';?>" />
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
        </div>
            </div>
        </li>
        		<!--3--->
         <li style="margin:0 0 10px 0">
        	<label class="am-u-sm-3 am-u-lg-2 am-u-md-3 am-form-label"> </label>
            <div class="am-u-sm-7 am-u-lg-8 am-u-md-7">
        	 <input type="submit" class="am-btn am-btn-success am-radius am-btn-sm" value="<?php echo $ld['search'];?>"/>
        	</div>
        	</li>
    </ul>
    <?php echo $form->end()?>
</div>
<div id="tablelist" class="tablelist am-u-md-12 am-u-sm-12">
    <table class="am-table  table-main">

        <?php if($svshow->operator_privilege("configvalues_view")){echo $html->link($ld['not_to_search_keywords'],"/open_keywords/nottosearchkeyword",array('target'=>'_blank','class'=>'mt am-btn am-btn-default am-btn-sm')).'&nbsp;';}?>
        <?php if($svshow->operator_privilege("configvalues_view")){echo $html->link($ld['open_call_keywords'],"/open_keywords/",array('target'=>'_blank','class'=>'mt am-btn am-btn-default am-btn-sm')).'&nbsp;';}?>

        <thead>
        <tr>
            <th class="am-hide-sm-only"><?php echo $ld['avatar'];?></th>
            <th><?php echo $ld['user_name'];?></th>
            <th><?php echo $ld['msg'];?></th>
            <th><?php echo $ld['status']; ?></th>
            <th class="am-hide-sm-only"><?php echo $ld['time']; ?></th>
            <th><?php echo $ld['operate']; ?></th>
        </tr>
        </thead>
        <tbody>
        <?php if(isset($message_list)&&sizeof($message_list)>0){ foreach($message_list as $k=>$v){ ?>
            <tr>
                <td class="am-hide-sm-only"><?php echo $html->image($v['OpenUser']['headimgurl'],array('style'=>'width:60px;height:60px;'));?></td>
                <td><?php echo urldecode($v['OpenUser']['nickname']);?></td>
                <td><div class="ellipsis"><?php echo $v['OpenUserMessage']['message']; ?></div></td>
                <td ><?php echo $v['OpenUserMessage']['send_from']=='0'?$ld['replied']:$ld['unreplied']; ?></td>
                <td class="am-hide-sm-only"><?php echo $v['OpenUserMessage']['created']; ?></td>
                <td style="min-width:150px;"><?php echo $html->link($ld['view'],"/open_users/view/".$v['OpenUserMessage']['open_user_id']."#historical",array("class"=>"am-btn am-btn-default am-btn-xs am-radius")); ?></a></td>
            </tr>
        <?php }}else{ ?>
            <tr>
                <td   colspan="7" class="no_data_found"><?php echo $ld['no_data_found']?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php if(isset($message_list)&&sizeof($message_list)>0){?>
    <div id="btnouterlist" class="btnouterlist"><?php echo $this->element('pagers')?></div>
    <?php }?>
</div>
<style type="text/css">
.am-form-label{font-weight:bold;margin-top:5px;margin-left:15px;}
.ellipsis {
    overflow: hidden;
    text-overflow: ellipsis;
    text-transform: capitalize;
    white-space: nowrap;
    width:500px;
}
.mt{
    border-radius: 2px;
    float:right;
    margin-left:10px;
}
</style>