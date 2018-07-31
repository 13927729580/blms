<?php

/*****************************************************************************
 * Seevia 发票类型管理
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
class InvoiceTypesController extends AppController
{
    public $name = 'InvoiceTypes';
    public $components = array('Pagination','RequestHandler');
    public $helpers = array('Pagination');
    public $uses = array('InvoiceType','InvoiceTypeI18n','OperatorLog');

    public function index()
    {
        /*判断权限*/
        $this->operator_privilege('invoice_types_view');
        $this->menu_path = array('root' => '/oms/','sub' => '/invoice_types/');
        /*end*/
        $this->set('title_for_layout', $this->ld['invoice_type'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['invoice_type'],'url' => '');

        $this->InvoiceType->set_locale($this->locale);
        $invoice_type_data = $this->InvoiceType->find('all');
        $this->set('invoice_type_data', $invoice_type_data);
    }

    public function view($id = 0)
    {
        /*判断权限*/
        $this->operator_privilege('invoice_types_edit');
        $this->menu_path = array('root' => '/oms/','sub' => '/invoice_types/');
        /*end*/
        $this->set('title_for_layout', $this->ld['invoice_type_manager'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['invoice_list_type'],'url' => '/invoice_types/');
        $this->navigations[] = array('name' => $this->ld['edit_invoice_type'],'url' => '');

        if ($id != 0) {
            if ($this->RequestHandler->isPost()) {
                $this->InvoiceType->saveAll(array('InvoiceType' => $this->data['InvoiceType']));
                $this->InvoiceTypeI18n->deleteAll(array('invoice_type_id' => $id));
                foreach ($this->data['InvoiceTypeI18n'] as $k => $v) {
                    $v['invoice_type_id'] = $id;
                    $this->InvoiceTypeI18n->saveAll(array('InvoiceTypeI18n' => $v));
                    if ($v['locale'] == $this->locale) {
                        $thisname = $v['name'];
                    }
                }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_invoice_type'].':'.$thisname.' id '.$id, $this->admin['id']);
            }
                $this->flash($this->ld['invoice_type'].$thisname.$this->ld['edit_invoice_type_success'], '/invoice_types/edit/'.$id, 10);
            }
        } else {
            if ($this->RequestHandler->isPost()) {
                $this->InvoiceType->saveAll(array('InvoiceType' => $this->data['InvoiceType']));
                foreach ($this->data['InvoiceTypeI18n'] as $k => $v) {
                    $v['invoice_type_id'] = $this->InvoiceType->getLastInsertId();
                    $this->InvoiceTypeI18n->saveAll(array('InvoiceTypeI18n' => $v));
                    if ($v['locale'] == $this->locale) {
                        $thisname = $v['name'];
                    }
                }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['new_invoice_type'].':'.$thisname, $this->admin['id']);
            }
                $this->flash($this->ld['invoice_type'].$thisname.$this->ld['add_successful_invoice_type'], '/invoice_types/edit/'.$this->InvoiceType->getLastInsertId(), 10);
            }
        }
        $invoice_type_data = $this->InvoiceType->localeformat($id);
        foreach ($invoice_type_data['InvoiceTypeI18n'] as $k => $v) {
            if ($v['locale'] == $this->locale) {
                $thisname = $v['name'];
            }
        }
        $this->navigations[] = array('name' => $thisname,'url' => '');

        $this->set('invoice_type_data', $invoice_type_data);
    }

    public function add()
    {
        /*判断权限*/
        $this->operator_privilege('invoice_types_add');
        $this->menu_path = array('root' => '/oms/','sub' => '/invoice_types/');
        /*end*/
        $this->set('title_for_layout', $this->ld['new_invoice_type'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['invoice_list_type'],'url' => '/invoice_types/');
        $this->navigations[] = array('name' => $this->ld['new_invoice_type'],'url' => '/invoice_types/');

        if ($this->RequestHandler->isPost()) {
            $this->InvoiceType->saveAll(array('InvoiceType' => $this->data['InvoiceType']));
            foreach ($this->data['InvoiceTypeI18n'] as $k => $v) {
                $v['invoice_type_id'] = $this->InvoiceType->getLastInsertId();
                $this->InvoiceTypeI18n->saveAll(array('InvoiceTypeI18n' => $v));
                if ($v['locale'] == $this->locale) {
                    $thisname = $v['name'];
                }
            }
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['new_invoice_type'].':'.$thisname, $this->admin['id']);
            }
            $this->set('type', true);
            $this->redirect('/invoice_types');
            //$this->flash($this->ld['invoice_type'].$thisname.$this->ld['add_successful_invoice_type'],'/invoice_types/edit/'.$this->InvoiceType->getLastInsertId(),10);
        }
    }
    public function edit($id)
    {
        /*判断权限*/
        $this->operator_privilege('invoice_types_edit');
        $this->menu_path = array('root' => '/oms/','sub' => '/invoice_types/');
        /*end*/
        $this->set('title_for_layout', $this->ld['invoice_type'].$this->ld['edit'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['transactions'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['invoice_list_type'],'url' => '/invoice_types/');
        $this->navigations[] = array('name' => $this->ld['edit_invoice_type'],'url' => '');

        if ($this->RequestHandler->isPost()) {
            $this->InvoiceType->saveAll(array('InvoiceType' => $this->data['InvoiceType']));
            $this->InvoiceTypeI18n->deleteAll(array('invoice_type_id' => $id));
            foreach ($this->data['InvoiceTypeI18n'] as $k => $v) {
                $v['invoice_type_id'] = $id;
                $this->InvoiceTypeI18n->saveAll(array('InvoiceTypeI18n' => $v));
                if ($v['locale'] == $this->locale) {
                    $thisname = $v['name'];
                }
            }
            $this->set('type', true);
            //操作员日志
            if ($this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit_invoice_type'].':'.$thisname, $this->admin['id']);
            }
            //$this->flash($this->ld['invoice_type'].$thisname.$this->ld['edit_invoice_type_success'],'/invoice_types/edit/'.$id,10);
            $this->redirect('/invoice_types');
        }
        $invoice_type_data = $this->InvoiceType->localeformat($id);
        foreach ($invoice_type_data['InvoiceTypeI18n'] as $k => $v) {
            if ($v['locale'] == $this->locale) {
                $thisname = $v['name'];
            }
        }
        $this->navigations[] = array('name' => $thisname,'url' => '');

        $this->set('invoice_type_data', $invoice_type_data);
    }
    public function remove($id)
    {
        $result['flag'] = 2;
        $result['message'] = '删除失败';//$this->ld['deleted_failure']
        $this->InvoiceType->hasOne = array();
        $this->InvoiceType->hasMany = array();
        $pn = $this->InvoiceTypeI18n->find('list', array('fields' => array('InvoiceTypeI18n.invoice_type_id', 'InvoiceTypeI18n.name'), 'conditions' => array('InvoiceTypeI18n.invoice_type_id' => $id, 'InvoiceTypeI18n.locale' => $this->locale)));
        $this->InvoiceType->deleteAll(array('id' => $id));
        $this->InvoiceTypeI18n->deleteAll(array('invoice_type_id' => $id));
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_delete_invoice_type'].':id '.$id.' '.$pn[$id], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['deleted_success'];
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result));
    }
}
