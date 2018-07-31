<table class="am-table  am-table-main" style="padding-left:17px;padding-right:10px">
	<thead>
		<tr>
			<th><?php echo '名字';?></th>
			<th class="am-hide-sm-only"><?php  echo '身份证号码';?></th>
			<th style="width: 148px;"><?php echo '证书类型';?></th>
			<th><?php echo '证书编号';?></th>
			<th class="am-hide-sm-only"><?php echo '注册时间';?></th>
		</tr>
	</thead>
	<tbody>
		<?php if(isset($certificate_infos)&&sizeof($certificate_infos)>0){foreach($certificate_infos as $v){ ?>
		<tr>
			<td><?php echo $v['Certificate']['name']; ?></td>
			<td class="am-hide-sm-only"><?php echo $v['Certificate']['identity_no']; ?></td>
			<td><?php echo isset($informationresource_info['certificatetype'][$v['Certificate']['type']])?$informationresource_info['certificatetype'][$v['Certificate']['type']]:$v['Certificate']['type']; ?></td>
			<td><?php echo $v['Certificate']['certificate_number']; ?></td>
			<td  class="am-hide-sm-only"><?php echo $v['Certificate']['register_date']; ?></td>
		</tr>
		<?php }}else{ ?>
		<tr>
			<td class='am-text-center' colspan='5'><?php echo $ld['no_record']; ?></td>
		</tr>
		<?php } ?>
	</tbody>
</table>