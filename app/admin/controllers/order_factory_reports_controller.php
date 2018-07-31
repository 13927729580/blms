<?php

/*****************************************************************************
 * Seevia 订单工厂报表控制器
 * ===========================================================================
 * 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * ===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 OrderFactoryReportsController 的控制器
 *后台订单工厂报表控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class OrderFactoryReportsController extends AppController
{
    public $name = 'OrderFactoryReports';
    public $helpers = array('Pagination');
    public $components = array('Pagination','RequestHandler','Email','Orderfrom','EcFlagWebservice','Phpexcel');
    public $uses = array('Operator','Application','ConfigI18n','Language','UserAddress','OrderProduct','PurchaseOrder');
    public $dear_id = array();

    public function index()
    {
        $this->operator_privilege('factory_report_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/report/','sub' => '/order_factory_reports/');
        $this->navigations[] = array('name' => $this->ld['report'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['order_factory_report'],'url' => '/order_factory_reports/');

        $real_condition = '';//实际发货条件
        $predict_condition = '';//预计发货条件
        //工厂发货开始时间
        $start_date = '';
        if (isset($_REQUEST['start_date']) && $_REQUEST['start_date'] != '') {
            $start_date = trim($_REQUEST['start_date']);
        }
        $this->set('start_date', $start_date);
        //工厂发货结束时间
        $end_date = '';
        if (isset($_REQUEST['end_date']) && $_REQUEST['end_date'] != '') {
            $end_date = trim($_REQUEST['end_date']);
        }
        $this->set('end_date', $end_date);
        $time_type = '1';//默认按每日生成报表
        $time_format = '%Y-%m-%d';
        $date_arr = '';
        if (isset($_REQUEST['time_type']) && $_REQUEST['time_type'] != '') {
            $time_type = trim($_REQUEST['time_type']);
            if ($time_type == 1) {
                $time_format = '%Y-%m-%d';//按每日
                $date_arr = $this->prDates($start_date, $end_date);//日期区间数组
            } elseif ($time_type == 2) {
                $time_format = '%Y-%m';//按每月
                $date_arr = $this->prMonths($start_date, $end_date);//日期区间数组
            }
        } else {
            $time_format = '%Y-%m-%d';//按每日
            $date_arr = $this->prDates($start_date, $end_date);//日期区间数组
        }
        $this->set('time_type', $time_type);

        $real_list = $this->PurchaseOrder->query("select time,count(time)  from (
select date_format(`ASD`,'".$time_format."') time from svoms_purchase_orders  where `ASD` <= '".$end_date."' and `ASD` >= '".$start_date."' ) as t group by time");//实际发货数量
        $data_real = array();
        foreach ($real_list as $k => $v) {
            $data_real[$v['t']['time']] = $v['0']['count(time)'];
        }
        foreach ($date_arr as $ak => $av) {
            if (!array_key_exists($av, $data_real)) {
                $data_real[$av] = 0;
            }
        }
        //排序
        $data_real_arr = array();
        foreach ($date_arr as $ak => $av) {
            foreach ($data_real as $dk => $dv) {
                if ($av == $dk) {
                    $data_real_arr[$ak] = $dv;
                }
            }
        }
//		$real_total2=$this->Order->query("SELECT DAY (factory_real_time),count(id) FROM svoms_orders WHERE `factory_real_time` < '".$end_date." 23:59:59' and `factory_real_time` > '".$start_date." 00:00:00' GROUP BY DAY (factory_real_time)");//实际发货数量
//		pr($real_total2);

        $predict_list = $this->PurchaseOrder->query("select time,count(time)  from (
select date_format(ESD,'".$time_format."') time from svoms_purchase_orders  where `ESD` <= '".$end_date."' and `ESD` >= '".$start_date."' ) as p group by time");//预计发货数量
        //pr($predict_list);
        $data_predict = array();
        foreach ($predict_list as $k => $v) {
            $data_predict[$v['p']['time']] = $v['0']['count(time)'];
        }
        foreach ($date_arr as $ak => $av) {
            if (!array_key_exists($av, $data_predict)) {
                $data_predict[$av] = 0;
            }
        }
        $data_predict_arr = array();
        foreach ($date_arr as $ak => $av) {
            foreach ($data_predict as $dk => $dv) {
                if ($av == $dk) {
                    $data_predict_arr[$ak] = $dv;
                }
            }
        }
//		pr($date_arr);
//		pr($data_real_arr);
        $this->set('date_arr', $date_arr);
        $this->set('data_real_arr', $data_real_arr);
        $this->set('data_predict_arr', $data_predict_arr);
        //格式化数据
        $order_codes = '';
        $user_id_array = array();
        $this->set('title_for_layout', '报表 - '.$this->ld['page'].' - '.$this->configs['shop_name']);
    }
    public function prDates($start, $end)
    {
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);
        $date_arr = array();
        while ($dt_start <= $dt_end) {
            //echo date('Y-m-d',$dt_start)."\n";
            array_push($date_arr, date('Y-m-d', $dt_start));
            $dt_start = strtotime('+1 day', $dt_start);
        }

        return $date_arr;
    }
    public function prMonths($start, $end)
    {
        $dt_start = strtotime($start);
        $dt_end = strtotime($end);
        $date_arr = array();
        while ($dt_start <= $dt_end) {
            //echo date('Y-m-d',$dt_start)."\n";
            array_push($date_arr, date('Y-m', $dt_start));
            $dt_start = strtotime('+31 day', $dt_start);
        }

        return $date_arr;
    }
}
