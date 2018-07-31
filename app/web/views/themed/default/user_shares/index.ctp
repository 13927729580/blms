 <div style="text-align:left;font-size:20px;border-bottom:1px solid #ccc;padding-bottom:1rem;margin-top:1%;padding-left:5px;" >我的分享</div>
<div class="am-u-user-point">
  <?php echo $form->create('',array('action'=>'/index/','type'=>'get','name'=>"SearchForm","class"=>'am-form am-form-horizontal'));?>
 <div style="padding-bottom:1rem;">
                        <label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label am-text-left" style="padding-left:0;margin-top:0.1rem;">分享时间</label>
                            <div class="am-u-lg-3  am-u-md-3 am-u-sm-4"  style="padding-right:0.5rem;padding-left:0;width:18%;">
                            <div class="am-input-group">
                            <input type="text" name="date1" data-am-datepicker="{}"  class="am-form-field am-radius dateonly"  value="<?php echo @$dates1;?>" placeholder="" style="cursor:pointer;background-color: #fff;" />
                            <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;height: 32px;line-height: 30px;">
            <i class="am-icon-remove" style="font-size: 10px;"></i>
          </span>
        </div>

                           </div>
                            <span style="float: left;margin-top:0.5rem;"><em>-</em></span>
                            
                            <div class="am-u-lg-3  am-u-md-3 am-u-sm-4"  style="padding-left:0.5rem;padding-right:0;width:18%;">
                                <div class="am-input-group">
                            <input type="text" name="date2" data-am-datepicker="{}"  class="am-form-field am-radius dateonly"  value="<?php echo @$dates2;?>" placeholder="" style="cursor:pointer;background-color: #fff;" />
                            <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;height: 32px;line-height: 30px;">
            <i class="am-icon-remove" style="font-size: 10px;"></i>
          </span>
        </div>
                            </div>
                            <div class="am-u-lg-2 am-u-md-3 am-u-sm-12" style="padding-top:0.3rem;padding-left:2rem;"><input type="submit" class="am-btn am-btn-primary am-btn-sm am-radius"  value="<?php echo $ld['query'];?>"/></div>
                            <div class="am-cf"></div>
</div>
<?php echo $form->end();?>
<table class="am-table integral_list">
        <thead>
    		<tr>
    			<th width="20%"><?php echo $ld['title_name']; ?></th>
                	<th width="50%"><?php echo '分享地址'?></th>
                	<th width="20%" class='am-text-center'><?php echo '是否赠送积分'?></th>
    			<th width="10%"><?php echo '分享时间'?></th>      			
    		</tr>
        </thead>
        <tbody>
    		<?php if(isset($my_share_logs)&&sizeof($my_share_logs)>0){foreach($my_share_logs as $k=>$v){?>
    		<tr>
    			<td><?php echo $v['UserShareLog']['share_title']; ?></td>
    			<td><?php echo $v['UserShareLog']['share_link'];?></td>
    			<td class='am-text-center'>
                    <?php if( $v['UserShareLog']['is_give_point'] == 1){?>
                    <span class="am-icon-check am-yes" style="cursor:pointer;" ></span>&nbsp;
                <?php }else{ ?>
                    <span class="am-icon-close am-no" style="cursor:pointer;"></span>&nbsp; 
                <?php }?>
                </td>
                <td><?php echo date("Y-m-d",strtotime($v['UserShareLog']['created']));?></td>
    		</tr>
    		<?php }}else{?>
    		<tr>
            	<td class="no_details_enquiry_points" colspan='4' style="text-align:center;padding-top:70px;border:none;"><?php echo '暂无分享记录'; ?></td>
            </tr>
            <?php } ?>
        </tbody>
	</table>
    <div class="pagenum"><?php echo $this->element('pager');?></div>
</div>
<style type='text/css'>
label.am-form-label{font-weight:normal;}
table.integral_list th{font-weight:normal;}
</style>
<script type='text/javascript'>
function cla(obj){
    $(obj).siblings('input').val("");
}
</script>