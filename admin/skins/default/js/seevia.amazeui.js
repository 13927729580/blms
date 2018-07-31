/* Write your js */
$(document).ready(function(){	
	$("img").each(function(){
		$(this).prop("onerror",function(e){
			var error = false;
			if (!this.complete) {
				error = true;
			}
			if (typeof this.naturalWidth != "undefined" && this.naturalWidth == 0) {
				error = true;
			}
			if(error){
				$(this).bind('error.replaceSrc',function(){
					this.src = (webroot=='/'?'':webroot)+"/theme/default/images/default.png";
					$(this).unbind('error.replaceSrc');
				}).trigger('load');
			}
		});
	});
});


//后台执行登入
function ajax_login_check(){
	var btn = $("#login_check_id");
	btn.button('loading');
	
	var cookie_session = document.getElementById("cookie_session");//获取cookie对象
	var operator_pwd =document.getElementById("operator_pwd").value;
	operator_pwd=hex_md5(operator_pwd);
	var operator_id =document.getElementById("operator_id").value;
	if(document.getElementById("locale")){
		var locale = document.getElementById("locale").value;
	}else{
		var locale="eng";
	}
	cookie_session = (document.getElementById("cookie_session").checked)?1:0;
	var postData={'operator_pwd':operator_pwd,'operator':operator_id,'cookie_session':cookie_session};
	if(document.getElementById("authnum")){
		var authnum = document.getElementById("authnum").value;
		if(document.getElementById('vcode').style.display!="none"){
			var ck_login_authnum=document.getElementById('ck_login_authnum').value;
			if(ck_login_authnum==authnum.toLowerCase()){
				postData={'operator_pwd':operator_pwd,'operator':operator_id,'cookie_session':cookie_session,'authnum':authnum};
			}else{
				if(locale == 'chi'){
					alert('验证码错误');
					btn.button('reset');
					return false;
				}else{
					alert('Verification code error');
					btn.button('reset');
					return false;
				}
			}
		}
	}
	$.ajax({ url:admin_webroot+"operators/ajax_login/",
			type:"POST",
			dataType:"json",
			data: postData,
			success: function(data){
				try{
					if(data.code=="0"){
						if(data.url==""||data.url=="/pages/home"){
							window.location.href= admin_webroot+"pages/home";
						}else{
							window.location.href=data.url;
						}
					}else{
                        			var count_login=data.count_login;
						btn.button('reset');
						alert(data.message);
						if(document.getElementById("authnum")&&count_login>=2){
							document.getElementById('vcode').style.display="block";
						}
						show_login_captcha();
					}
				}catch(e){
					alert(data);
				} 
			}
		});
}


function seevia_system_modified(link_obj){
	$('#admin_username').dropdown('toggle');
	$.ajax({
		url:admin_webroot+"pages/ajax_system_list",
		type:"POST",
		dataType:"json",
		data:{},
		success:function (data){
			if(data.code=='1'){
				$("#seevia_system div.am-modal-bd div.am-g").html("");
				$.each(data.message,function(index,item){
					$("#seevia_system div.am-modal-bd div.am-g").append("<div class='am-u-lg-3 am-u-md-5 am-u-sm-6'><label class='am-checkbox am-success'><input type='checkbox' value='"+item['System']['id']+"' onchange=\"modified_seevia_system(this,'"+item['System']['id']+"')\" "+(item['System']['status']=='1'?'checked':'')+"/>"+item['System']['code']+"</label></div>");
				});
				$("#seevia_system div.am-modal-bd div.am-g").append("<div class='am-cf'></div><div class='am-text-center'><button type='button' onclick='window.location.reload();' class='am-btn am-btn-success am-btn-xs'>"+j_refresh+"</button></div>");
				$('#seevia_system').modal('open');
				$("#seevia_system div.am-modal-bd div.am-g input[type='checkbox']").uCheck();
			}else{
				alert(data.message);
			}
		}
	});
}


function modified_seevia_system(obj,Id){
	var checked_status=$(obj).prop('checked')?'1':'0';
	$.ajax({
		url:admin_webroot+"pages/modified_system",
		type:"POST",
		dataType:"json",
		data:{'id':Id,'status':checked_status},
		success:function (data){
			if(data.code=='0'){
				alert(data.message);
			}
		}
	});
}

$(document).click(function(e) {
	//自动关闭导航
	var $menu_length=$("nav.am-menu ul.am-menu-nav li.am-parent.am-open").length;
	if($menu_length>0){
		var $menu=$("nav.am-menu ul.am-menu-nav li.am-parent.am-open");
		if(!(e.target == $menu[0] || $.contains($menu[0], e.target))) {
			$menu.removeClass("am-open").children("ul").removeClass("am-in");
		}
	}
});

/*
	Pop positioning
*/
function AmazeuiPopPositioning(Id){
	var WindowWidth=$(window).width();
	var WindowHeight=$(window).height();
	var PopWidth=$("#"+Id).width();
	var PopHeight=$("#"+Id).height();
	var Popmargin_left=((WindowWidth-PopWidth)/2).toFixed(2);
	var Popmargin_top=((WindowHeight-PopHeight)/2).toFixed(2);
	$("#"+Id).css("left","0px").css("top","0px").css("margin-left",Popmargin_left+"px").css("margin-top",Popmargin_top+"px");
}

function checkSpecial(str){
	var reg=/[@#\$%\^&\*]+/g;
	if(reg.test(str)){
		return false;
	}
	return true;
}

/*
	分页回车
*/
function pagers_onkeypress(obj,e){
	if(window.event){
		keynum = event.keyCode
	}else if(e.which){
		keynum = e.which
	}
	if(keynum==13){
		pagers_onblur(obj,e);
	}
}
function pagers_onblur(obj,e){
	$.ajax({ url:admin_webroot+"pages/pagers_num/"+obj.value+"?"+new Date(),
			type:"GET",
			dataType:"html",
			data: {},
			success: function(data){
				window.location.href = window.location.href;
			}
		});
}

//ajax取区域
function show_two_regions(str,id,ii){
	if(document.getElementById('local')){
		var local=document.getElementById('local').value;
	}else{
		var local="chi";
	}
	if(id==undefined || id==0){
		var data = { str:str,local_area: local,ii:ii};
		id = '';
	}
	else 
		var data = { str:str,updateaddress_id:id,local_area: local,ii:ii}
	$.post(
		"/admin/regions/twochoice/"+str, //url
		data,//data
		function (result, textStatus){//callback
			if(result.type == "0"){
				document.getElementById('regionsupdate'+ii+id).innerHTML = result.message;
			}else{
				document.getElementById('message_content').innerHTML = result.message;
			}
			//$("#AddressRegionUpdate00").selectIt();
		},
 		"json"//type
 	);

}
//重载区域
function reload_two_regions(ii){
	var i=0;
	var str="";
	var now_id1=document.getElementById("AddressRegionUpdate"+ii+"0").value;
	var now_id2=document.getElementById("AddressRegionUpdate"+ii+"1")?document.getElementById("AddressRegionUpdate"+ii+"1").value:'';
	var now_id3=document.getElementById("AddressRegionUpdate"+ii+"2")?document.getElementById("AddressRegionUpdate"+ii+"2").value:'';
	document.getElementById("region_hidden_id"+ii).value=now_id1+" "+now_id2+" "+now_id3;
	while(true){
		if(document.getElementById('AddressRegionUpdate'+ii+i)==null){
			break;
		}
		str +=document.getElementById('AddressRegionUpdate'+ii+i).value + " ";
		i++;
	} 
    show_two_regions(str,0,ii);
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
//两边去空格
function Trim(str){ //删除左右两端的空格 
    return str.replace(/(^\s*)|(\s*$)/g,"");
}
//选择物流公司
function select_logistics_company(m){
		if(document.getElementById("logistic_save_button")){
			document.getElementById('logistic_save_button').style.display="";
			//Y.one("#logistic_save_button").setStyle('display','');
		}

	if(m!=''){
		$("#order_invoice_no_tr").removeClass('order_status');
		//Y.one("#order_invoice_no_tr").setStyle('display','');
		document.getElementById('order_invoice_no_tr').style.display="";
	}else{
		$("#order_invoice_no_tr").addClass('order_status');
	}
}


function sprintf(){
    var arg = arguments,
        str = arg[0] || '',
        i, n;
    for (i = 1, n = arg.length; i < n; i++) {
        str = str.replace(/%s/, arg[i]);
    }
    return str;
}

function list_delete_submit(sUrl,confirm_delete_str){
	if(typeof(confirm_delete_str)=="undefined"){
		confirm_delete_str=j_confirm_delete;
	}
	if(confirm(confirm_delete_str)){
		$.ajax({ url:sUrl,
				type:"POST",
				dataType:"json",
				data: {},
				success: function(data){
					try{  
						if(data.flag==1){
							window.location.reload();
						}else{
							alert(data.message);
						}
					}catch(e){
						alert(data);
					}
				}
			});
	}
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
      		url: admin_webroot+"regions/load_region",
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
	  		url: admin_webroot+"regions/load_region",
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
							$(this).after("<input type='text' class='region_input' name='"+region_name+"' value=''/>");
						}
					});
				}
	  		}
	  	});
	}
}

cla = function(obj){
	$(obj).siblings('input').val('');
}