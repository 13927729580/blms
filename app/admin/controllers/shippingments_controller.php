<?php

/*****************************************************************************
 * Seevia 配送方式管理控制器
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
 *这是一个名为 ShippingmentsController 的控制器
 *后台配送管理控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class ShippingmentsController extends AppController
{
    public $name = 'Shippingments';
    public $helpers = array('Html','Pagination','Ckeditor','Svshow');
    public $components = array('Pagination','RequestHandler');
    public $uses = array('ShippingI18n','Shipping','ShippingArea','ShippingAreaI18n','ShippingAreaRegion','Region','Application','LogisticsCompany','OperatorLog');

    /**
     *显示配送方式列表.
     */
    public function index($page = 1)
    {
        $this->operator_privilege('shippingments_view');
        $this->menu_path = array('root' => '/oms/','sub' => '/shippingments/');
        $this->Shipping->Behaviors->attach('Containable');
        $app_sp = $this->Shipping->find('all', array('fields' => array('Shipping.code'), 'contain' => false));
        $xx = array();
        $aa = array();
        //$ps=$this->Shipping->shipping_effective_list_beta('chi',$this->apps['codes']);
        if (!empty($app_sp)) {
            foreach ($app_sp as $k => $v) {
                $aa[] = 'APP-DSP-'.strtoupper($v['Shipping']['code']);
                $app_groupby = $this->Application->find('first', array('conditions' => array('Application.code' => $aa)));
                if (empty($app_groupby['Application']['groupby'])) {
                    $appgroupby = 139;
                } else {
                    $appgroupby = $app_groupby['Application']['groupby'];
                }
                $this->set('app_groupby', $app_groupby['Application']['groupby']);
                $xx[] = $v['Shipping']['code'];
            }
        } else {
            $this->set('app_groupby', 134);
        }
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['shippingments'],'url' => '');

        $this->Shipping->set_locale($this->locale);
        $condition = array('Shipping.code' => $xx);
        $sortClass = 'Shipping';
        $total = $this->Shipping->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        //echo $this->configs['show_count'];
          if (isset($_GET['page']) && $_GET['page'] != '') {
              $page = $_GET['page'];
          }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        //echo ','.$rownum;
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'shippings','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Shipping');
        $this->Pagination->init($condition, $parameters, $options);
        $data = $this->Shipping->find('all', array('page' => $page, 'limit' => $rownum, 'conditions' => $condition, 'order' => 'Shipping.created,Shipping.id'));
        $this->set('shippings', $data);

        foreach ($data as $dk => $dv) {
            $this->ShippingArea->set_locale($this->locale);
            $shippingarea = $this->ShippingArea->find('all', array('conditions' => array('shipping_id' => $dv['Shipping']['id'])));
            $a = array();
            if (!empty($shippingarea)) {
                foreach ($shippingarea as $spak => $shav) {
                    $a[] = $shav['ShippingAreaI18n']['name'];
                }
            }
            if (!empty($a)) {
                $area_name = implode(',', $a);
            } else {
                $area_name = '';
            }
            $data[$dk]['Shipping']['area_name'] = $area_name;
        }
        $this->set('shippings', $data);
        $this->set('title_for_layout', $this->ld['shippingments'].' - '.$this->ld['page'].' '.$page.' - '.$this->configs['shop_name']);
    }

    /**
     *配送方式 编辑.
     *
     *@param int $id 输入配送方式ID
     */
    public function edit($id)
    {
        $this->menu_path = array('root' => '/oms/','sub' => '/shippingments/');
        $this->operator_privilege('shippingments_edit');
        $this->set('title_for_layout', $this->ld['edit_shipping_method'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['shipping_method_list'],'url' => '/shippingments/');
        $this->navigations[] = array('name' => $this->ld['edit_shipping_method'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            $php_code = serialize(@$this->data['php_code']);
            $shipping['status'] = isset($this->data['Shipping']['status']) ? $this->data['Shipping']['status'] : 1;
            $shipping['insure_fee'] = $this->data['Shipping']['insure_fee'];
            $shipping['support_cod'] = $this->data['Shipping']['support_cod'];
            $shipping['php_code'] = $php_code;
            $shipping['id'] = $id;
            $this->Shipping->save($shipping);
            foreach ($this->data['ShippingI18n'] as $k => $v) {
                $ShippingI18n = array(
                    'id' => !empty($v['id']) ? $v['id'] : '',
                    'shipping_id' => $id,
                    'locale' => $v['locale'],
                    'name' => $v['name'],
                    'description' => $v['description'],
                );
                if ($v['locale'] == $this->locale) {
                    $name = $v['name'];
                }
                $this->ShippingI18n->save(array('ShippingI18n' => $ShippingI18n));
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_shipping_method'].':id '.$id.' '.$name, $this->admin['id']);
            }
            $this->redirect('/shippingments/');
        }
        $Shipping_info = $this->Shipping->localeformat($id);
        if ($Shipping_info['Shipping']['code'] == 'usps') {
            $php_code = unserialize(StripSlashes($Shipping_info['Shipping']['php_code']));
            $this->set('php_code', $php_code);
        }

        foreach ($Shipping_info['ShippingI18n'] as $k => $v) {
            $Shipping_info['ShippingI18n'][$v['locale']] = $v;
        }

        $this->set('Shipping_info', $Shipping_info);
        //导航显示
        $this->navigations[] = array('name' => $Shipping_info['ShippingI18n'][$this->backend_locale]['name'],'url' => '');
    }

    /**
     *配送方式所辖区域 编辑.
     *
     *@param int $id 输入配送方式ID
     *@param int $shipping_id 输入配送方式所辖区域ID
     */
    public function area_view($id = 0, $shipping_id = 0)
    {
        $this->operator_privilege('shippingments_area');
        $this->menu_path = array('root' => '/oms/','sub' => '/shippingments/');
        $this->set('title_for_layout', $this->ld['edit_region'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['shippingments'],'url' => '/shippingments/');
        $this->Shipping->set_locale($this->locale);
        $Shipping_info = $this->Shipping->findById($shipping_id);
        $this->navigations[] = array('name' => $Shipping_info['ShippingI18n']['name'],'url' => '/shippingments/area/'.$shipping_id);
        if ($this->RequestHandler->isPost()) {
            $this->data['ShippingArea']['orderby'] = !empty($this->data['ShippingArea']['orderby']) ? $this->data['ShippingArea']['orderby'] : 50;
            $this->data['ShippingArea']['free_subtotal'] = !empty($this->data['ShippingArea']['free_subtotal']) ? $this->data['ShippingArea']['free_subtotal'] : 0;
            if (isset($_REQUEST['money']) && sizeof($_REQUEST['money']) > 0) {
                foreach ($_REQUEST['money'] as $k => $v) {
                    if ($v['value'] == '') {
                        $_REQUEST['money'][$k]['value'] = 0;
                    }
                }
            }
            $money = serialize($_REQUEST['money']);
            $this->data['ShippingArea']['fee_configures'] = $money;

            if (isset($this->data['ShippingArea']['id']) && $this->data['ShippingArea']['id'] != '') {
                $this->ShippingArea->save(array('ShippingArea' => $this->data['ShippingArea'])); //保存
            } else {
                $this->ShippingArea->saveAll(array('ShippingArea' => $this->data['ShippingArea'])); //保存
                $id = $this->ShippingArea->getLastInsertId();
            }

            $this->ShippingAreaI18n->deleteAll(array('shipping_area_id' => $id));
            foreach ($this->data['ShippingAreaI18n'] as $k => $v) {
                $v['shipping_area_id'] = $id;
                $this->ShippingAreaI18n->saveAll(array('ShippingAreaI18n' => $v));
            }

            //保存地区
            $datas = array();
            //pr($datas);
            if (isset($_REQUEST['items'])) {
                $this->ShippingAreaRegion->deleteall("shipping_area_id = '".$id."'", false);
                foreach ($_REQUEST['items'] as $kv => $vs) {
                    $datas['ShippingAreaRegion']['shipping_area_id'] = $id;
                    $datas['ShippingAreaRegion']['region_id'] = $vs;

                    $this->ShippingAreaRegion->saveAll($datas);
                    $this->ShippingAreaRegion->id = false;
                }
            }

            foreach ($this->data['ShippingAreaI18n'] as $k => $v) {
                if ($v['locale'] == $this->locale) {
                    $userinformation_name = $v['name'];
                }
            }
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_shipping_method'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $this->redirect('/shippingments/area/'.$shipping_id);
        }                //foreach($_REQUEST['items'] as $kv=>$vs){

            //.$shipping_id);
        //}

        $shippingarea = $this->ShippingArea->localeformat($id);
        $this->set('shippingarea', $shippingarea);

        $money = empty($shippingarea['ShippingArea']['fee_configures']) ? '' : unserialize($shippingarea['ShippingArea']['fee_configures']);//右边重量区
        $this->set('money', $money);//右边重量区

        $this->Region->set_locale($this->locale);
        $shippingarearegion_edit = $this->ShippingAreaRegion->find('all', array('conditions' => array('shipping_area_id' => $id)));
        foreach ($shippingarearegion_edit as $kd => $vd) {
            $region_edit[] = $this->Region->locales_formated($vd['ShippingAreaRegion']['region_id']);
        }

        $region_country = $this->Region->getarealist(0, $this->locale);
        $region = $this->Region->getarealist(1, $this->locale);
        ksort($region);
        if (isset($region_edit)) {
            $this->set('region_edit', $region_edit);
        }

        $this->set('region', $region);
        $this->set('region_country', $region_country);
        $this->set('shipping_id', $shipping_id);
    }

    /**
     *删除配送方式所辖区域.
     */
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_shipping_area_failure'];
        $pn = $this->ShippingAreaI18n->find('list', array('fields' => array('ShippingAreaI18n.shipping_area_id', 'ShippingAreaI18n.name'), 'conditions' => array('ShippingAreaI18n.shipping_area_id' => $id, 'ShippingAreaI18n.locale' => $this->locale)));
        $this->ShippingArea->deleteAll("ShippingArea.id='$id'");
        //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_delivery_region'].':id '.$id.' '.$pn[$id], $this->admin['id']);
            }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_shipping_area_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *处理选择的配送区域的数据.
     *
     *@param int $id 输入配送方式ID
     */
    public function province($id=0)
    {
        $regions = $this->Region->getarealist($id, $this->locale);
        $i = 0;
        ksort($regions);

        $number = count($regions);
        $this->set('regions', $regions);
        if (!empty($regions)) {
            foreach ($regions as $k => $v) {
                Configure::write('debug', 0);
                $results['number'] = $number + $k;
                $results['first_key'] = $k;
                $results['message'] = $regions;

                die(json_encode($results));
            }
        } else {
            Configure::write('debug', 0);
            $results['number'] = $number + $k;
            $results['first_key'] = $k;
            $results['message'] = $regions;

            die(json_encode($results));
        }
    }

    /**
     *配送方式所辖区域列表.
     *
     *@param int $id 输入配送方式ID
     */
    public function area($id, $page = 1)
    {
        $this->operator_privilege('shippingments_area');
        $this->menu_path = array('root' => '/oms/','sub' => '/shippingments/');
        $this->set('title_for_layout', '配送区域'.' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['shippingments'],'url' => '/shippingments/');
        $this->Shipping->set_locale($this->locale);
        $Shipping_info = $this->Shipping->find('first', array('conditions' => array('Shipping.id' => $id), 'fields' => array('Shipping.id', 'ShippingI18n.name')));
        $this->navigations[] = array('name' => $Shipping_info['ShippingI18n']['name'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['set_region'],'url' => '');

        $this->ShippingArea->set_locale($this->locale);
        $total = $this->ShippingArea->find('count', array('conditions' => array('shipping_id' => $id)));//统计全部商品总数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];//获取商店设置的默认一个商品个数数据
        if (isset($_GET['page']) && $_GET['page'] != '') {
            $page = $_GET['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        //$sortClass="Product";
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'shippingments','action' => 'area','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'ShippingArea');
        $condition['shipping_id'] = $id;
        $this->Pagination->init($condition, $parameters, $options);
        $shippingarea = $this->ShippingArea->find('all', array('conditions' => array('shipping_id' => $id), 'limit' => $rownum, 'page' => $page));
        $this->set('ids', $id);
        $this->set('shippingarea', $shippingarea);
        if (!empty($shippingarea)) {
            foreach ($shippingarea as $sk => $sv) {
                $shippingarearegions = $this->ShippingAreaRegion->find('all', array('conditions' => array('shipping_area_id' => $sv['ShippingAreaI18n']['shipping_area_id']), 'fields' => array('ShippingAreaRegion.region_id')));
                $this->Region->set_locale($this->locale);
                $region_area_name = array();
                if (!empty($shippingarearegions)) {
                    foreach ($shippingarearegions as $kd => $vd) {
                        $region_area = $this->Region->find('first', array('conditions' => array('Region.id' => $vd['ShippingAreaRegion']['region_id'])));
                        $region_area_name[] = $region_area['RegionI18n']['name'];
                    }
                }
                $region_areaname = implode(',', $region_area_name);
                $shippingarea[$sk]['ShippingArea']['region_area_name'] = $region_areaname;
            }
            $this->set('shippingarea', $shippingarea);
        }
    }

    /**
     *配送方式所辖区域安装.
     *
     *@param int $id 输入配送方式ID
     */
    public function install($id)
    {
        $this->Shipping->updateAll(
                          array('Shipping.status' => 1),
                          array('Shipping.id' => $id)
                       );
        $this->Shipping->set_locale($this->locale);
        $ship_info = $this->Shipping->findById($id);
        //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_install_delivery'].':'.$ship_info['ShippingI18n']['name'], $this->admin['id']);
            }
        $this->redirect('/shippingments/edit/'.$id);
    }

    /**
     *配送方式所辖区域卸载.
     *
     *@param int $id 输入配送方式ID
     */
    public function uninstall($id)
    {
        $result['flag'] = 2;
        $result['message'] = $this->ld['unload_the_shipping_method_fail'];
        $this->Shipping->updateAll(
                          array('Shipping.status' => 0),
                          array('Shipping.id' => $id)
                       );
        $this->Shipping->set_locale($this->locale);
        $ship_info = $this->Shipping->findById($id);
        //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_uninstall_delivery'].':'.$ship_info['ShippingI18n']['name'], $this->admin['id']);
            }
        $result['flag'] = 1;
        $result['message'] = $this->ld['uninstall_the_shipping_method_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表名称修改.
     */
    public function update_shipping_name()
    {
        $this->Shipping->hasMany = array();
        $this->Shipping->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->ShippingI18n->updateAll(
            array('name' => "'".$val."'"),
            array('shipping_id' => $id)
        );
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表费用修改.
     */
    public function update_shipping_fee()
    {
        $this->Shipping->hasMany = array();
        $this->Shipping->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (!is_numeric($val)) {
            $result['flag'] = 2;
            $result['content'] = $this->ld['enter_correct_cost'];
        }
        if (is_numeric($val) && $this->Shipping->save(array('id' => $id, 'insure_fee' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表货到付款修改.
     */
    public function toggle_on_cod()
    {
        $this->Shipping->hasMany = array();
        $this->Shipping->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Shipping->save(array('id' => $id, 'support_cod' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表状态修改.
     */
    public function toggle_on_status()
    {
        $this->Shipping->hasMany = array();
        $this->Shipping->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $result = array();
        if (is_numeric($val) && $this->Shipping->save(array('id' => $id, 'status' => $val))) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表配送区域名称修改.
     */
    public function update_shippingarea_name()
    {
        $this->ShippingArea->hasMany = array();
        $this->ShippingArea->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->ShippingAreaI18n->updateAll(
            array('name' => "'".$val."'"),
            array('shipping_area_id' => $id)
        );
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }

    /**
     *列表所辖地区修改.
     */
    public function update_shippingarea_areaname()
    {
        $this->ShippingArea->hasMany = array();
        $this->ShippingArea->hasOne = array();
        $id = $_REQUEST['id'];
        $val = $_REQUEST['val'];
        $request = $this->ShippingAreaI18n->updateAll(
            array('areaname' => "'".$val."'"),
            array('shipping_area_id' => $id)
        );
        $result = array();
        if ($request) {
            $result['flag'] = 1;
            $result['content'] = stripslashes($val);
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
    /**
     *获取配送方式下的物流公司.
     */
    public function get_ship_logistics_companies()
    {
        $id = $_REQUEST['id'];
        $lc_infos = array();
    //	pr($_REQUEST);die;
        $lc_infos = $this->LogisticsCompany->find('all', array('conditions' => array('LogisticsCompany.fettle' => 1, 'LogisticsCompany.type like' => '%'.$id.'%'), 'fields' => 'LogisticsCompany.id,LogisticsCompany.name,LogisticsCompany.type'));
    //	pr($lc_infos);die;
        if (!empty($lc_infos)) {
            foreach ($lc_infos as $k => $lc) {
                $ship_ids = explode(';', $lc['LogisticsCompany']['type']);
                if (!in_array($id, $ship_ids)) {
                    unset($lc_infos[$k]);
                }
            }
        }
        $result['flag'] = 1;
        $result['lc_infos'] = $lc_infos;
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
