<style>
.am-selected.am-dropdown{float: left;margin-left: 10px;}
.am-u-lg-7.am-text-left{margin-bottom: 5px;}
.am-u-lg-8.am-text-left{margin-bottom: 5px;}
.am-u-lg-12.am-text-left{margin-bottom: 5px;}
.am-u-lg-5.am-text-left{margin-bottom: 5px;padding:0;}
.am-u-lg-4.am-text-left{margin-bottom: 5px;padding:0;}
i{cursor: pointer;margin-right: 5px;}
a{color: #0e90d2;cursor: pointer;}
</style>
<div class="am-text-right">
	<a href="<?php echo $html->url('/operator_channel_configs') ?>" class="am-btn am-btn-default am-btn-xs am-radius"> 配置管理</a>
	<a href="<?php echo $html->url('/operator_channels/view/0') ?>" class="am-btn am-btn-warning am-btn-xs am-radius"><i class="am-icon-plus"></i> 添加</a>
	<div class="am-cf"></div>
</div>
<div style="border-bottom:1px solid #ccc;">
	<div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
		<label class="am-checkbox am-success" style="font-weight:bold;padding-top:0;">
	        渠道编码
	    </label>
    </div>
	<div class="am-u-lg-4 am-u-md-3 am-u-sm-3"><label class="am-checkbox am-success" style="font-weight:bold;padding-top:0;padding-left:0;">渠道名称</label></div>
	<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><label class="am-checkbox am-success" style="font-weight:bold;padding-top:0;padding-left:0;">状态</label></div>
	<div class="am-u-lg-1 am-u-md-3 am-u-sm-3"><label class="am-checkbox am-success" style="font-weight:bold;padding-top:0;padding-left:0;">操作</label></div>
	<div class="am-cf"></div>
</div>
<div>
	<?php foreach ($operator_channel_info as $k => $v) { ?>
		<div style="border-bottom:1px solid #ccc;">
			<div class="am-u-lg-4 am-u-md-3 am-u-sm-3">
				<label class="am-checkbox am-success" style="font-weight:bold;padding-top:0;font-weight:400;">
		        	<?php echo $v['OperatorChannel']['code'] ?>
		        </label>
		    </div>
			<div class="am-u-lg-4 am-u-md-3 am-u-sm-3"><label class="am-checkbox am-success" style="font-weight:bold;padding-top:0;padding-left:0;font-weight:400;"><?php echo $v['OperatorChannel']['name'] ?></label></div>
			<div class="am-u-lg-2 am-u-md-3 am-u-sm-3"><label class="am-checkbox am-success" style="font-weight:bold;padding-top:0;padding-left:0;font-weight:400;">
				<?php if( $v['OperatorChannel']['status'] == 1){?>
	                <span class="am-icon-check am-yes" style="cursor:pointer;" onclick=""></span>
	            <?php }else{ ?>
	                <span class="am-icon-close am-no" style="cursor:pointer;" onclick=""></span>   
	            <?php }?>&nbsp;</label>
            </div>
			<div class="am-u-lg-2 am-u-md-3 am-u-sm-3" style="padding-top:10px;padding-bottom:10px;">
			<?php if($svshow->operator_privilege('edit_operator_source')){?>
				<a class="am-btn am-btn-default am-btn-xs am-text-secondary am-seevia-btn-edit" href="<?php echo $html->url('/operator_channels/view/'.$v['OperatorChannel']['id']); ?>">
		        	<span class="am-icon-pencil-square-o"></span> <?php echo $ld['edit']; ?>
		        </a>
		    <?php } ?>
		    <?php if($svshow->operator_privilege('delete_operator_source')){?>
		        <a class="am-btn am-btn-default am-btn-xs am-text-danger am-seevia-btn-edit" href="javascript:;" onclick="channel_delete(<?php echo $v['OperatorChannel']['id'] ?>)">
		        	<span class="am-icon-trash-o"></span> <?php echo $ld['delete']; ?>
		        </a>  
		    <?php } ?>
		    <?php if($svshow->operator_privilege('edit_operator_source')){?>
		    	<div onclick="find_department()" style="padding:6px 8px;margin-top: 5px;" class="am-btn am-btn-default am-btn-xs am-radius" data-am-modal="{target: '#find_department',width: 600, height: 400}">获取部门与成员</div> 
		    <?php } ?>
	        </div>
	        <div class="am-cf"></div>
	    </div>
	<?php } ?>
</div>
<div style="margin-top:1rem;">
	<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
		&nbsp;
	</div>
	<div class="am-u-lg-6 am-u-md-6 am-u-sm-6">
		<?php echo $this->element('pagers')?>
	</div>
	<div class="am-cf"></div>
</div>

<div class="am-modal am-modal-no-btn" tabindex="-1" id="find_department">
	<div class="am-modal-dialog">
		<div class="am-modal-hd">部门与成员
			<a href="javascript: void(0)" class="am-close am-close-spin" data-am-modal-close>&times;</a>
		</div>
		<div class="am-modal-bd" style="margin-top: 20px;">
			<div class="am-u-lg-6" id="department_list" style="height:300px;overflow-y:scroll;font-size: 15px;"></div>
			<div class="am-u-lg-6" id="my_department_list" style="height:300px;overflow-y:scroll;font-size: 15px;"></div>
			<input style="position: absolute;bottom: 10px;right: 30px;font-size: 12px;display: none;" type="submit" class="am-btn am-btn-success am-radius am-btn-sm department_merge" onclick="department_hebing()" value="合并" />
			<input style="position: absolute;bottom: 10px;right: 30px;font-size: 12px;display: none;" type="submit" class="am-btn am-btn-success am-radius am-btn-sm menber_merge" onclick="menber_hebing()" value="合并" />
		</div>
	</div>
</div>
<input type="hidden" id="department_parent_id" value="">
<input type="hidden" id="department_name" value="">
<input type="hidden" id="department_code" value="">
<input type="hidden" id="department_value" value="">

<input type="hidden" id="menber_code" value="">
<input type="hidden" id="menber_user_id" value="">
<input type="hidden" id="menber_name" value="">
<input type="hidden" id="menber_mobile" value="">
<input type="hidden" id="menber_email" value="">
<input type="hidden" id="menber_department_id" value="">
<script>
	function channel_delete(obj_id){
		if(confirm('是否确认删除？')){
			$.ajax({ 
				url:admin_webroot+"/operator_channels/delete_channel/",
				type:"POST",
				dataType:"json",
				data: {'channel_id':obj_id},
				success: function(data){
					if(data.code == 1){
						alert('删除成功！');
						window.location.reload();
					}
				}
			});
		}else{

		}
		
	}

	function find_department(){
		$('#department_list').html('<div class="am-u-lg-12 am-u-sm-12">加载中...</div>');
		$('#my_department_list').html('<div class="am-u-lg-12 am-u-sm-12">加载中...</div>');
		$.ajax({ 
			url:admin_webroot+"/operator_channels/ajax_dowmload_qyinfo/qywechat",
			type:"POST",
			dataType:"json",
			data: {},
			success: function(data){
				if(data.code == 1){
					$('#department_list').html('');
					department_digui(data.organization_department_list,data.organization_department_menbers,'#department_list',0,data.code,data.organization_department_menbers_relation,data.organization_department_relation);
					my_department_list();
				}
			}
		});
	}

	function department_digui(val,menbers_list,save_div,num,code,organization_department_menbers_relation,organization_department_relation){
		for(var i = 0;i<val.length;i++){
			var aa = '<div>';
			aa+= '<div>';
			aa+= '<div class="am-u-lg-7 am-text-left parent_id_'+val[i].parentid+' name_'+val[i].name+' code_'+code+' value_'+val[i].id+'" style="padding-left:'+num+'px;"><i class="am-icon-plus" onclick="open_subset(this)" data-am-collapse="{parent: \'#department_list\', target: \'#department_'+val[i].id+'\'}"></i>'+val[i].name+'</div>';
			aa+='<div class="am-u-lg-5 am-text-left">';
			aa+='<a style="margin-right:5px;';
			if(organization_department_relation[code]){
				if(typeof(organization_department_relation[code][val[i].id])!='undefined'){
					aa+='display:none;';
				}else{
					aa+='';
				}
			}
			aa+='" onclick="department_import('+val[i].parentid+',\''+val[i].name+'\','+code+','+val[i].id+')">导入</a>';
			aa+='<a style="';
			if(organization_department_relation[code]){
				if(typeof(organization_department_relation[code][val[i].id])!='undefined'){
					aa+='display:none;';
				}else{
					aa+='';
				}
			}
			aa+='" onclick="department_merge(this,'+val[i].parentid+',\''+val[i].name+'\','+code+','+val[i].id+')">合并</a>';
			aa+='</div>';
			aa+='<div class="am-cf"></div>';
			aa+='</div>';
			aa+='<div class="am-cf"></div>';
			aa+='</div>';

			aa+= '<div id="department_'+val[i].id+'" class="am-panel-collapse am-collapse">';
			if(menbers_list[val[i].id]&&menbers_list[val[i].id].length>0){
				for(var j = 0;j<menbers_list[val[i].id].length;j++){
					aa+= '<div class="am-u-lg-7 am-text-left code_'+code+' userid_'+menbers_list[val[i].id][j].userid+' name_'+menbers_list[val[i].id][j].name+' mobile_'+menbers_list[val[i].id][j].mobile+' email_'+menbers_list[val[i].id][j].email+' departmentid_'+val[i].id+'" style="padding-left:'+(num+15)+'px;">'+menbers_list[val[i].id][j].name+'</div>';
					aa+='<div class="am-u-lg-5 am-text-left">';
					aa+='<a style="margin-right:5px;';
					if(organization_department_menbers_relation[code]){
						if(typeof(organization_department_menbers_relation[code][menbers_list[val[i].id][j].userid])!='undefined'){
							aa+='display:none;';
						}else{
							aa+='';
						}
					}
					aa+='" onclick="menber_import('+code+',\''+menbers_list[val[i].id][j].userid+'\',\''+menbers_list[val[i].id][j].name+'\','+menbers_list[val[i].id][j].mobile+',\''+menbers_list[val[i].id][j].email+'\','+val[i].id+')">导入</a>';
					aa+='<a style="';
					if(organization_department_menbers_relation[code]){
						if(typeof(organization_department_menbers_relation[code][menbers_list[val[i].id][j].userid])!='undefined'){
							aa+='display:none;';
						}else{
							aa+='';
						}
					}
					aa+='" onclick="menber_merge(this,'+code+',\''+menbers_list[val[i].id][j].userid+'\',\''+menbers_list[val[i].id][j].name+'\','+menbers_list[val[i].id][j].mobile+',\''+menbers_list[val[i].id][j].email+'\','+val[i].id+')">合并</a>';
					aa+='</div>';
				}
			}
			aa+= '</div>';
			$(save_div).append(aa);
			if(val[i].child_department&&val[i].child_department.length>0){
				department_digui(val[i].child_department,menbers_list,'#department_'+val[i].id,num+15,code,organization_department_menbers_relation,organization_department_relation);
			}
		}
	}

	function my_department_list(){
		$.ajax({
			url:admin_webroot+"/operator_channels/my_department_list",
			type:"POST",
			dataType:"json",
			data: {'key':1},
			success: function(data){
				if(data.code == 1){
					$('#my_department_list').html('');
					my_department_list_digui(data.message.department_list,0,data.message.operator_list,'#my_department_list');
					$("input[type='radio']").uCheck();
				}
			}
		});
	}
	
	function my_department_list_digui(val,num,operator_list,save_div){
		if(val.length>0){
			for(var i = 0;i<val.length;i++){
				var bb ='<div>';
				bb += '<div style="padding-left:'+num+'px;" class="am-u-lg-8 am-text-left">';
				bb += '<i class="am-icon-plus" onclick="open_subset(this)" data-am-collapse="{parent: \'#my_department_list\', target: \'#my_department_'+val[i].id+'\'}"></i>';
				bb += '<label class="am-radio department_merge" style="display:none;margin:0;margin-top:-2px;"><input type="radio" name="department_merge" value="'+val[i].id+'" data-am-ucheck></label>';
				bb += val[i].name+'</div>';
				bb+='<div class="am-u-lg-4 am-text-left">';
				bb+='&nbsp;</div>';
				bb+= '<div id="my_department_'+val[i].id+'" class="am-panel-collapse am-collapse">';
				if(operator_list.length>0){
					for(var j = 0;j<operator_list.length;j++){
						if(operator_list[j].Operator.department_id==val[i].id){
							bb += '<div class="am-u-lg-8 am-text-left" style="padding-left:'+(num+18)+'px;">';
							bb += '<label class="am-radio menber_merge" style="display:none;margin:0;margin-top:-2px;"><input type="radio" name="menber_merge" value="'+operator_list[j].Operator.id+'" data-am-ucheck></label>';
							bb += operator_list[j].Operator.name+'</div>';
							bb+='<div class="am-u-lg-4 am-text-left">';
							bb+='&nbsp;</div>';
							bb+='<div class="am-cf"></div>';
						}
					}
				}
				bb+= '</div>';
				bb+='<div class="am-cf"></div>';
				bb+='</div>';
				$(save_div).append(bb);
				if(val[i].child_department&&val[i].child_department.length>0){
					my_department_list_digui(val[i].child_department,num+18,operator_list,"#my_department_"+val[i].id);
				}
			}
		}else{
			$('#my_department_list').html('<div class="am-u-lg-12 am-u-sm-12">暂无部门</div>');
		}
	}

	//部门导入
	function department_import(parent_id,name,code,value){
		$.ajax({ 
			url:admin_webroot+"/operator_channels/department_import",
			type:"POST",
			dataType:"json",
			data: {'operator_channel_id':code,'relation_type':1,'value':value,'parent_id':parent_id,'name':name},
			success: function(data){
				if(data.code == 1){
					department_child_import(value);
					alert('导入成功');
					//find_department();
				}
			},
		});
	}

	function department_child_import(value){
		if($('.parent_id_'+value).length>0){
			console.log('department_child_import:'+value);
			$('.parent_id_'+value).each(function(){
				var class_desc=$(this).attr('class');
				var class_desc_list=class_desc.split(' ');
				var child_parent_id = class_desc_list[2].split('_')[2];
				var child_name = class_desc_list[3].split('_')[1];
				var child_code = class_desc_list[4].split('_')[1];
				var child_value = class_desc_list[5].split('_')[1];
				console.log(child_name);
				var parentNode=$('.parent_id_'+child_value);
				$.ajax({ 
					url:admin_webroot+"/operator_channels/department_import",
					type:"POST",
					dataType:"json",
					data: {'operator_channel_id':child_code,'relation_type':1,'value':child_value,'parent_id':child_parent_id,'name':child_name},
					success: function(data){
						if(data.code == 1){
							console.log(parentNode);
							department_child_import(child_value);
						}
					}
				});
			});
		}
		menber_child_import(value);
	}

	function menber_child_import(value){
		if($('.departmentid_'+value).length>0){
			$('.departmentid_'+value).each(function(){
				var class_desc=$(this).attr('class');
				var menber_code = class_desc.split(' ')[2].split('_')[1];
				var menber_user_id = class_desc.split(' ')[3].split('_')[1];
				var menber_name = class_desc.split(' ')[4].split('_')[1];
				var menber_mobile = class_desc.split(' ')[5].split('_')[1];
				var menber_email = class_desc.split(' ')[6].split('_')[1];
				var menber_department_id = class_desc.split(' ')[7].split('_')[1];
				$.ajax({ 
					url:admin_webroot+"/operator_channels/menber_import",
					type:"POST",
					dataType:"json",
					data: {'operator_channel_id':menber_code,'user_id':menber_user_id,'name':menber_name,'mobile':menber_mobile,'email':menber_email,'department_id':menber_department_id},
					success: function(data){
						if(data.code == 1){
							
						}
					}
				});
			});
		}
	}

	//成员导入
	function menber_import(code,user_id,name,mobile,email,department_id){
		$.ajax({ 
			url:admin_webroot+"/operator_channels/menber_import",
			type:"POST",
			dataType:"json",
			data: {'operator_channel_id':code,'user_id':user_id,'name':name,'mobile':mobile,'email':email,'department_id':department_id},
			success: function(data){
				if(data.code == 1){
					alert('导入成功');
					find_department();
				}
			}
		});
	}
	//部门合并
	function department_merge(btn,parent_id,name,code,value){
		$('a').css('color','#0e90d2');
		$(btn).css('color','red');
		$('.department_merge').css('display','inline-block');
		$('.menber_merge').css('display','none');
		$('#department_parent_id').val(parent_id);
		$('#department_name').val(name);
		$('#department_code').val(code);
		$('#department_value').val(value);
	}
	function department_hebing(){
		var department_parent_id = $('#department_parent_id').val();
		var department_name = $('#department_name').val();
		var department_code = $('#department_code').val();
		var department_value = $('#department_value').val();
		var id = $("input[name='department_merge']:checked").val();
		$.ajax({ 
			url:admin_webroot+"/operator_channels/department_merge",
			type:"POST",
			dataType:"json",
			data: {'operator_channel_id':department_code,'relation_type':1,'value':department_value,'parent_id':department_parent_id,'name':department_name,'id':id},
			success: function(data){
				if(data.code == 1){
					alert('合并成功');
					find_department();
				}
			}
		});
	}
	//成员合并
	function menber_merge(btn,code,user_id,name,mobile,email,department_id){
		$('a').css('color','#0e90d2');
		$(btn).css('color','red');
		$('.menber_merge').css('display','inline-block');
		$('.department_merge').css('display','none');
		$('#menber_code').val(code);
		$('#menber_user_id').val(user_id);
		$('#menber_name').val(name);
		$('#menber_mobile').val(mobile);
		$('#menber_email').val(email);
		$('#menber_department_id').val(department_id);
	}

	function menber_hebing(){
		var menber_code = $('#menber_code').val();
		var menber_user_id = $('#menber_user_id').val();
		var menber_name = $('#menber_name').val();
		var menber_mobile = $('#menber_mobile').val();
		var menber_email = $('#menber_email').val();
		var menber_department_id = $('#menber_department_id').val();
		var id = $("input[name='menber_merge']:checked").val();
		$.ajax({ 
			url:admin_webroot+"/operator_channels/menber_merge",
			type:"POST",
			dataType:"json",
			data: {'operator_channel_id':menber_code,'user_id':menber_user_id,'name':menber_name,'mobile':menber_mobile,'email':menber_email,'department_id':menber_department_id,'id':id},
			success: function(data){
				if(data.code == 1){
					alert('合并成功');
					my_department_list();
				}
			}
		});
	}

	function open_subset(btn){
		if($(btn).prop("className")=='am-icon-plus'){
			$(btn).removeClass("am-icon-plus");
			$(btn).addClass("am-icon-minus");
		}else{
			$(btn).removeClass("am-icon-minus");
			$(btn).addClass("am-icon-plus");
		}
	}
</script>