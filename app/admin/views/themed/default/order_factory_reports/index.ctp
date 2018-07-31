<?php echo $form->create('OrderFactoryReport',array('action'=>'/','name'=>'SReportForm','type'=>'get','class'=>'am-form am-form-horizontal'));?>
	<ul class="am-avg-lg-2 am-avg-md-1 am-avg-sm-1">
		<li style="margin-bottom:10px;">
			<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label am-text-center" style="font-weight:bold;">发货时间</label>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
                <div class="am-input-group">
				<input type="text" name="start_date" value="<?php echo $start_date;?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"/>
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
        </div>
			</div>
			<label class="am-u-lg-1 am-u-md-1 am-u-sm-1 am-text-center"><em>-</em></label>
			<div class="am-u-lg-4 am-u-md-4 am-u-sm-4">
                <div class="am-input-group">
				<input type="text" name="end_date" value="<?php echo $end_date;?>"  class="am-form-field" readonly  data-am-datepicker="{theme: 'success',locale:'<?php echo $backend_locale; ?>'}"/>
                <span class="am-input-group-label" onclick="cla(this)" style="cursor:pointer;">
                <i class="am-icon-remove"></i>
              </span>
        </div>
			</div>
		</li>
		<li style="margin-bottom:10px;">
			<label class="am-u-lg-2 am-u-md-2 am-u-sm-2 am-form-label am-text-center" style="font-weight:bold;">报表间隔</label>
			<div class="am-u-lg-6 am-u-md-6 am-u-sm-10">
				<select name="time_type" data-am-selected="{noSelectedText:''}">
					<option value=""><?php echo $ld['please_select']?></option>
					<option value="1" <?php echo isset($time_type) && $time_type==1?"selected":"";?>>每日<?php //echo $ld['please_select']?></option>
					<option value="2" <?php echo isset($time_type) && $time_type==2?"selected":"";?>>每月<?php //echo $ld['please_select']?></option>
				</select>
			</div>
			<div class="am-u-lg-1 am-u-md-1 am-u-sm-1">
				<input type="submit" class="am-btn am-btn-success am-btn-xs am-radius" onclick="search_report()" value="<?php echo $ld['search'];?>" />
			</div>
		</li>
	</ul>
<?php echo $form->end();?>
    <!-- 为ECharts准备一个具备大小（宽高）的Dom -->
    <div id="main" style="height:400px"></div>
    <!-- ECharts单文件引入 -->
	<?php echo $javascript->link('/vendors/echarts-2.2.0/build/dist/echarts');?>
    <script type="text/javascript">
		function search_report(){
			document.SReportForm.action=admin_webroot+"order_factory_reports/";
			document.SReportForm.onsubmit= "";
			document.SReportForm.submit();
		}
		var date=new Array();//日期
		var esd=new Array();//预计发货
		var asd=new Array();//实际发货
		<?php if(isset($date_arr)){foreach($date_arr as $k=>$v){?>
			date[<?php echo $k?>]="<?php echo $v?>";
		<?php }}?>
		<?php if(isset($data_predict_arr)){foreach($data_predict_arr as $k=>$v){?>
			esd[<?php echo $k?>]="<?php echo $v?>";
		<?php }}?>
		<?php if(isset($data_real_arr)){foreach($data_real_arr as $k=>$v){?>
			asd[<?php echo $k?>]="<?php echo $v?>";
		<?php }}?>
		// alert(date);
        // 路径配置
        require.config({
            paths: {
                echarts: '/admin/vendors/echarts-2.2.0/build/dist/'
            }
        });
           // 使用
        require(
            [
                'echarts',
                'echarts/chart/bar' // 使用柱状图就加载bar模块，按需加载
            ],
            function (ec) {
                // 基于准备好的dom，初始化echarts图表
                var myChart = ec.init(document.getElementById('main')); 
                
                var option = {
                    tooltip: {
                        show: true
                    },
                    legend: {
                        data:['实际发货量','预计发货量']
                    },
                    xAxis : [
                        {
                            type : 'category',
                            data :  date
                        }
                    ],
                    yAxis : [
                        {
                            type : 'value'
                        }
                    ],
                    series : [
                        {
                            "name":"实际发货量",
                            "type":"bar",
                            "data":asd
                        },
                        {
                            "name":"预计发货量",
                            "type":"bar",
                            "data":esd
                        }
                    ]
                };
                // 为echarts对象加载数据 
                myChart.setOption(option); 
            }
        );
    </script>
