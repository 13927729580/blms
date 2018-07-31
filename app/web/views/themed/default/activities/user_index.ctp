<style>
@media only screen and (max-width: 641px)
{
.riqi{padding-bottom:10px;}
.riqi .yue{font-size:12px;}
.course_ul .yixue{font-size:12px;}
.course_ul .keshi{font-size:12px;}
.course_ul .xuexu_but{padding-top:15px;}
.course_ul .kc_xx{padding:5px 0;line-height: inherit;height: inherit;width:60px;font-size:12px;
    }
.w72{width:72%;float:left;}
.w28{width:28%;float:left;}
}
		#course_chapter_list .admin-user-img
{
		display:none;
}

	.nian
{
    font-size: 12px;
    color: #ccc;
}
	a.am-btn-success:visited
{
color:#149941;
}
	.am-btn-success
{
background-color:#fff;
color:#149941;
}
.am-btn-success:hover
{
background-color:#fff;
color:#149941;
}
	.course_ul .xinxi
{
	border-bottom:1px solid #ccc;
	padding-bottom:30px;
	padding-right:20px;
}
.xinxi>div:first-child
{
float:left;padding-right:18px;
}
	.riqi
{
color:#888888
}
.tab_name
{
color:#434343;}
	.usercenter_fu .course_log
{
   
    padding: 10px 0 30px 20px;
    margin: 20px 0 50px 10px;
    border: 1px solid #ccc;
    box-shadow: 0 0 15px #ccc;
    border-radius: 3px;
}
h3
{

    font-size: 25px;
    color: #424242;
    padding: 5px 0;
    font-weight: 500;
}
.kc_xx
{
    padding: 0 0;
    line-height: 40px;
    display: inline-block;
    width: 100px;
    height: 40px;
    text-align: center;
    font-size: 16px;
    color: #12873a;
    border: 1px solid #12873a;
    border-radius: 5px;

}
.course_log>div:first-child
{
border-bottom: 1px solid #ccc;
}
.course_ul>li
{
border:none;padding-top:30px;
}

.neirong
{
padding-bottom:20px;font-size:16px;color:#424242;
}
.yixue
{
color:#149940;font-size:14px
}

.xuexu_but
{
padding-top:5%;
}
.keshi{font-size:14px;}
.course_log{
    margin: 20px 0 50px 10px;
    border-radius: 3px;
    padding: 10px 20px 30px 20px;
}
.course_log .am-g h3{
	height: 50px;
	line-height: 38px;
}
.am-active .am-btn-default.am-dropdown-toggle, .am-btn-default.am-active, .am-btn-default:active {
	background-color: #fff;
}
div.course_log ul.am-list li{border-top:none;}
div.course_log ul.am-list li img{max-width:100%;max-height:120px;}

.am-nav-tabs>li>a,.am-tabs-nav,.am-tabs-bd,.am-tabs-bd .am-tab-panel,.am-tabs-bd,.am-tabs-bd .am-tab-panel.am-active{border:none;}
.am-nav-tabs>li.am-active>a, .am-nav-tabs>li.am-active>a:focus, .am-nav-tabs>li.am-active>a:hover{border:none;}
</style>
<div class="am-tabs" data-am-tabs="{noSwipe: 1}">
  <ul class="am-tabs-nav am-nav am-nav-tabs" id="act_tab">
    <li><a href="javascript: void(0)">我参加的</a></li>
  </ul>

  <div class="am-tabs-bd">
    <div class="am-tab-panel" id="second_content">
    	
    </div>
  </div>
</div>

<script>
	function delete_activity(id){
		var delete_con = function(){
			$.ajax({
				url: web_base+'/activities/delete_activity/',
	        	type:"POST",
	        	data:{'activity_id':id},
	        	dataType:"json",
	        	success: function(data){
	            	if(data.code == 1){
						window.location.reload();
	            	}
	        	}
	    	});
		}	
		seevia_confirm(delete_con,'是否确认删除？');
	}
	
	ajax_get_activity();
	function ajax_get_activity(){
		$.ajax({
			url: web_base+'/user_activities/index',
        	type:"POST",
        	data:{},
        	dataType:"html",
        	success: function(data){
            	var HtmlDiv=document.createElement('div');
	            HtmlDiv.innerHTML=data;
	            var order_list=$(HtmlDiv).find('.course_log').html();
	            $("#second_content").html(order_list);
        	}
    	});
	}

</script>