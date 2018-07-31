<table class='am-table'>
	<thead>
		<tr>
			<th>名称</th>
			<th>类型</th>
			<th>状态</th>
			<th>操作</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo $ld['j_isp_name']; ?></td>
			<td>文本</td>
			<td colspan='2'><span class="am-icon-check am-text-success"></span></td>
		</tr>
		<tr>
			<td><?php echo $ld['mobile']; ?></td>
			<td>文本</td>
			<td colspan='2'><span class="am-icon-check am-text-success"></span></td>
		</tr>
		<?php if(isset($activity_config_list)&&sizeof($activity_config_list)>0){foreach($activity_config_list as $v){ ?>
		<tr>
			<td><?php echo $v['ActivityConfig']['name']; ?></td>
			<td><?php if($v['ActivityConfig']['type']=='text'){echo "文本";}else if($v['ActivityConfig']['type']=='radio'){echo "单选框";}else if($v['ActivityConfig']['type']=='checkbox'){echo "多选框";}else if($v['ActivityConfig']['type']=='image'){echo "图片";}?></td>
			<td><?php if( $v['ActivityConfig']['status'] == 1){?>
						<span class="am-icon-check am-text-success"></span>
					<?php }else{ ?>&nbsp;
						<span class="am-icon-close am-text-danger"></span>	
					<?php }?></td>
			<td>
				<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="javascript:void(0);" onclick="ajax_activity_config_detail(<?php echo $v['ActivityConfig']['id']; ?>)">
					<span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
				</a>
				<a class="am-btn am-btn-default am-btn-xs am-text-danger  am-seevia-btn-delete" href="javascript:void(0);" onclick="ajax_activity_config_remove(<?php echo $v['ActivityConfig']['id']; ?>)">
                			<span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
          			</a>
			</td>
		</tr>
		<?php }} ?>
	</tbody>
</table>