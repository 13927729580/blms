//ajax取区域
function show_two_regions(str,id){
	var local=document.getElementById('local').value;
	if(id==undefined || id==0){
		var data = { str:str,local_area: local};
		id = '';
	}
	else 
		var data = { str:str,updateaddress_id:id,local_area: local}
 		var region_search_Success=function (result, textStatus){//callback
			if(result.type == "0"){
				document.getElementById('regionsupdate'+id).innerHTML = result.message;
			}else{
				document.getElementById('message_content').innerHTML = result.message;
			}
	};
	$.post(web_base+"/regions/twochoice/"+str,data,region_search_Success,"json");

}
//ajax取无需验证区域
function show_uncheck_regions(str,id){
	console.log(str);
	var local=document.getElementById('local').value;
	if(id==undefined || id==0){
		var data = { str:str,local_area: local};
		id = '';
	}
	else 
		var data = { str:str,updateaddress_id:id,local_area: local}
 		var region_search_Success=function (result, textStatus){//callback
			if(result.type == "0"){
				document.getElementById('regionsupdate'+id).innerHTML = result.message;
			}else{
				document.getElementById('message_content').innerHTML = result.message;
			}
	};
	$.post(web_base+"/regions/uncheckchoice/"+str,data,region_search_Success,"json");
}
//重载区域
function reload_two_regions(id){
	var i=0;
	var str="";
	while(true){
		if(document.getElementById('AddressRegionUpdate'+i)==null){
			break;
		}
		str +=document.getElementById('AddressRegionUpdate'+i).value + " ";
		i++;
	} 
    show_two_regions(str);
}
//重载无需验证区域
function reload_uncheck_regions(id){
	var i=0;
	var str="";
	while(true){
		if(document.getElementById('AddressRegionUpdate'+i)==null){
			break;
		}
		str +=document.getElementById('AddressRegionUpdate'+i).value + " ";
		i++;
	} 
    show_uncheck_regions(str);
}
//
function reload_edit_two_regions(addressId){
	var i=0;
	var str="";
	while(true){
		//alert('AddressRegionUpdate'+i+addressId);
		if(document.getElementById('AddressRegionUpdate'+i+addressId)==null){
			break;
		}
		str +=document.getElementById('AddressRegionUpdate'+i+addressId).value + " ";
		i++;
	}
   	show_two_regions(str,addressId);
}
function reload_edit_uncheck_regions(addressId){
	var i=0;
	var str="";
	while(true){
		//alert('AddressRegionUpdate'+i+addressId);
		if(document.getElementById('AddressRegionUpdate'+i+addressId)==null){
			break;
		}
		str +=document.getElementById('AddressRegionUpdate'+i+addressId).value + " ";
		i++;
	}
   	show_uncheck_regions(str,addressId);
}



/*
	地区初始加载
*/
function load_region(user_address_data,region_select_id){
	if(typeof(region_select_id)=='undefined')region_select_id='';
	var region_select_html="regionsupdate"+region_select_id;
	var region_data={'country':'','province':'','city':''};
	var post_data={'parent_id':0};
	if(typeof(user_address_data['id'])!='undefined'){
		post_data={'parent_id[0]':user_address_data['country'],'parent_id[1]':user_address_data['province'],'parent_id[2]':user_address_data['city']};
		region_data={'country':user_address_data['country'],'province':user_address_data['province'],'city':user_address_data['city']};
	}
	$.ajax({
      		url: web_base+"/regions/index",
			type:"POST",
			data:post_data,
			dataType:"json",
			success: function(data){
				$("#"+region_select_html+" select:eq(0)").html("<option value=''>"+j_please_select+"</option>");
				$("#"+region_select_html+" select:eq(1)").html("<option value=''>"+j_please_select+"</option>");
				$("#"+region_select_html+" select:eq(2)").html("<option value=''>"+j_please_select+"</option>");
				if(data.code=='1'){
					var region_list=data.data;
					if(typeof(region_list[0])!='undefined'){
						$(region_list[0]).each(function(index,item){
							if(region_data['country']==item['Region']['id']){
								$("<option></option>").val(item['Region']['id']).text(item['RegionI18n']['name']).attr('selected',true).appendTo("#"+region_select_html+" select:eq(0)");
							}else{
								$("<option></option>").val(item['Region']['id']).text(item['RegionI18n']['name']).appendTo("#"+region_select_html+" select:eq(0)");
							}
						});
						if(region_list[0].length==1){
							$("#"+region_select_html+" select:eq(0) option:last-child").attr('selected',true);
							if(typeof(user_address_data['id'])=='undefined'){
								reload_region($("#"+region_select_html+" select:eq(0)")[0]);
							}
						}
						$("#"+region_select_html+" select:eq(0)").show();
					}
					if(typeof(region_list[region_data['country']])!='undefined'){
						$(region_list[region_data['country']]).each(function(index,item){
							if(region_data['province']==item['Region']['id']){
								$("<option></option>").val(item['Region']['id']).text(item['RegionI18n']['name']).attr('selected',true).appendTo("#"+region_select_html+" select:eq(1)");
							}else{
								$("<option></option>").val(item['Region']['id']).text(item['RegionI18n']['name']).appendTo("#"+region_select_html+" select:eq(1)");
							}
						});
					}else{
						if(region_data['province']!=''){
							$("#"+region_select_html+" select:eq(1)").attr('disabled',true).hide();
							var province_name=$("#"+region_select_html+" select:eq(1)").attr('name');
							province_name=typeof(province_name)=='undefined'?'':province_name;
							$("#"+region_select_html+" select:eq(1)").after("<input type='text' class='region_input' name='"+province_name+"' value='"+region_data['province']+"'/>");
						}
					}
					if(typeof(region_list[region_data['province']])!='undefined'){
						$(region_list[region_data['province']]).each(function(index,item){
							if(region_data['city']==item['Region']['id']){
								$("<option></option>").val(item['Region']['id']).text(item['RegionI18n']['name']).attr('selected',true).appendTo("#"+region_select_html+" select:eq(2)");
							}else{
								$("<option></option>").val(item['Region']['id']).text(item['RegionI18n']['name']).appendTo("#"+region_select_html+" select:eq(2)");
							}
						});
					}else{
						if(region_data['city']!=''){
							$("#"+region_select_html+" select:eq(2)").attr('disabled',true).hide();
							var city_name=$("#"+region_select_html+" select:eq(2)").attr('name');
							city_name=typeof(city_name)=='undefined'?'':city_name;
							$("#"+region_select_html+" select:eq(2)").after("<input type='text' class='region_input' name='"+city_name+"' value='"+region_data['city']+"'/>");
						}
					}
				}
	  		}
	  	});
}

/*
	地区变更
*/
function reload_region(region_select){
	var region_id=$(region_select).val();
	var post_data={'parent_id':region_id};
	$(region_select).nextAll("input.region_input").val('').attr('disabled',true).hide();
	$(region_select).nextAll('select').each(function(){
		$(this).html("<option value=''>"+j_please_select+"</option>").attr('disabled',false).show();
	});
	var NextRegionSelect=$(region_select).nextAll('select')[0];
	if(typeof(NextRegionSelect)=='undefined')return;
	if(region_id!=""){
		$.ajax({
	  		url: web_base+"/regions/index",
			type:"POST",
			data:post_data,
			dataType:"json",
			success: function(data){
				if(data.code=='1'){
					var region_list=data.data;
					if(typeof(region_list[region_id])!='undefined'){
						$(region_list[region_id]).each(function(index,item){
							$("<option></option>").val(item['Region']['id']).text(item['RegionI18n']['name']).appendTo(NextRegionSelect);
						});
					}
				}else{
					$(region_select).nextAll('select').each(function(){
						var NextRegionInput=$(this).next('input.region_input')[0];
						$(this).attr('disabled',true).hide();
						if(typeof(NextRegionInput)!='undefined'){
							$(NextRegionInput).attr('disabled',false).show();
						}else{
							var region_name=$(this).attr('name');
							region_name=typeof(region_name)=='undefined'?'':region_name;
							$(this).after("<input type='text' style='margin-left:5px;' class='region_input' name='"+region_name+"' value=''/>");
						}
					});
				}
	  		}
	  	});
	}
}