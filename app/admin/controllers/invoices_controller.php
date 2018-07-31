<?php

/**
 *这是一个名为 StaitcPagesController 的控制器
 *后台首页控制器.
 *
 *@var
 *@var
 *@var
 *@var
 */
class InvoicesController extends AppController
{
    public $name = 'Invoices';
    public $components = array('RequestHandler','Pagination');
    public $helpers = array('Html','Javascript','Pagination','Ckeditor');
    public $uses = array('Operator','Config','Application','Invoice','PageI18n','Route');

    /**
     *显示后台首页.
     */
    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('invoice_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/oms/','sub' => '/invoices/');
        // *end*
        $this->navigations[] = array('name' => $this->ld['finance_invoice'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['invoice_manage'],'url' => '');
         $this->Invoice->set_locale($this->backend_locale);
        $condition = '';
        //pr($this->params['url']);
        if (isset($this->params['url']['title']) && $this->params['url']['title'] != '') {
            $condition['or']['Invoice.invoice_number like'] = '%'.$this->params['url']['title'].'%';
            $condition['or']['Invoice.invoice_content like'] = '%'.$this->params['url']['title'].'%';
            $this->set('titles', $this->params['url']['title']);
        }
        if (isset($this->params['url']['status']) && $this->params['url']['status'] != ''&& $this->params['url']['status'] != '2') {
             $condition['Invoice.status'] = $this->params['url']['status'];
             $this->set('status', $this->params['url']['status']);
        }
        // 金额
        if (isset($this->params['url']['money1']) && $this->params['url']['money1'] != '' ) {
             $condition['and']['Invoice.invoice_money >='] = $this->params['url']['money1'].'0';
             $this->set('moneys1', $this->params['url']['money1']);
        }
        if (isset($this->params['url']['money2']) && $this->params['url']['money2'] != '' ) {
             $condition['and']['Invoice.invoice_money <='] = $this->params['url']['money2'];
             $this->set('moneys2', $this->params['url']['money2']);
        }
        // 日期
        if (isset($this->params['url']['date1']) && $this->params['url']['date1'] != '' ) {
             $condition['and']['Invoice.builling_date >='] = $this->params['url']['date1'].'00:00:00';
             $this->set('dates1', $this->params['url']['date1']);
        }
        if (isset($this->params['url']['date2']) && $this->params['url']['date2'] != '' ) {
             $condition['and']['Invoice.builling_date <='] = $this->params['url']['date2'].'23:59:59';
             $this->set('dates2', $this->params['url']['date2']);
        }
        //状态
        if (isset($this->params['url']['invoice_type']) && $this->params['url']['invoice_type'] != ''&& $this->params['url']['invoice_type'] != '2') {
             $condition['Invoice.invoice_type'] = $this->params['url']['invoice_type'];
             $this->set('types', $this->params['url']['invoice_type']);
        }
        //pr($condition);
         //分页start
        $total = $this->Invoice->find('count', array('conditions'=>$condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_GET['invoice']) && $_GET['invoice'] != '') {
            $page = $_GET['invoice'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'invoices','action' => 'index','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'Invoice');
        $this->Pagination->init($condition, $parameters, $options);
        //分页end
        $data = $this->Invoice->find('all', array('conditions' => $condition, 'limit' => $rownum, 'page' => $page));
         //pr($data);
        $page_ids = array();
        foreach ($data as $v) {
            array_push($page_ids, $v['Invoice']['id']);
        }
        $page_urls = $this->Route->find('all', array('conditions' => array('Route.model_id' => $page_ids, 'Route.controller' => 'pages', 'Route.action' => 'view', 'Route.url <>' => '/')));
        foreach ($data as $dk => $dv) {
            foreach ($page_urls as $pk => $pv) {
                if ($pv['Route']['model_id'] == $dv['Invoice']['id']) {
                    $data[$dk]['Invoice']['url'] = $pv['Route']['url'];
                }
            }
        }
         
        $this->set('pages', $data);
        // 设置title
        $this->set('title_for_layout', $this->ld['invoice_manage'].' - '.$this->configs['shop_name']);
    }

   public function remove($id){
        Configure::write('debug', 0);
        $this->layout = 'ajax'; //避免引入头部信息和尾部信息
        $result['flag'] = 2;
        $result['message'] = $this->ld['delete_article_failure'];
        if (!$this->operator_privilege('invoice_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        $page_info = $this->Invoice->findById($id);//找到被删除的信息
        $this->Invoice->deleteAll(array("Invoice.id"=>$id));
        // $this->Page->deleteAll(array("PageI18n.page_id"=>$id); //删除原有多语言
        //操作员日志
        if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['delete_article_failure'].':id '.$id.' '.$page_info['Invoice']['invoice_number'], $this->admin['id']);
        }
        $result['flag'] = 1;
        $result['message'] = $this->ld['delete_article_success'];
        die(json_encode($result));
    }
    //批量处理
    public function batch()
    {

        $page_ids = !empty($_REQUEST['checkbox']) ? $_REQUEST['checkbox'] : 0;

        if (isset($this->params['url']['act_type']) && $this->params['url']['act_type'] != '0') {
            if ($this->params['url']['act_type'] == 'delete') {
                
                $this->Invoice->deleteAll(array('Invoice.id' => $page_ids));
            }
            
        }
        //操作员日志
        if ($this->configs['operactions-log'] == 1) {
            $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['log_batch_delete'], $this->admin['id']);
        }
        $back_url = $this->operation_return_url();//获取操作返回页面地址
        $this->redirect($back_url);
    }
   
    function view($id = 0){
        $this->menu_path = array('root' => '/oms/','sub' => '/invoices/');
        /*判断权限*/
        if (empty($id)) {
            $this->operator_privilege('invoice_add');
        } else {
            $this->operator_privilege('invoice_edit');
        }

        $con = array('id'=>$id);
        $data = $this->Invoice->find('first', array('conditions' => $con));
        // pr($data).exit();
        $this->set('invoice_data', $data);
        //面包屑
        $this->set('title_for_layout', $this->ld['edit'].'-'.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['finance_invoice'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['invoice_manage'],'url' => '/invoices/');
        if ($con['id'] == '0') {
            $this->navigations[] = array('name' => $this->ld['add'],'url' => '');
        } else {
            $this->navigations[] = array('name' => $this->ld['edit'],'url' => '');
        }

        if ($this->RequestHandler->isPost()) {
            // pr($this->data).exit();
            $this->data['Invoice']['status'] = isset($this->data['Invoice']['status']) ? $this->data['Invoice']['status'] : 0;
            if (isset($this->data['Invoice']['id']) && $this->data['Invoice']['id'] != '') {
                $this->Invoice->save(array('Invoice' => $this->data['Invoice'])); //关联保存
            } else {
                $this->Invoice->saveAll(array('Invoice' => $this->data['Invoice'])); //关联保存
                $id = $this->Invoice->$id;
            }
            $id = $this->Invoice->id;
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['edit'].':id '.$id.' '.$userinformation_name, $this->admin['id']);
            }
            $url = '/invoices/'.$id;
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);

        }
    }

    function check(){
        Configure::write('debug',1);
            $this->layout='ajax';
            $data_count=-1;
            if ($this->RequestHandler->isPost()) {
                $invoice_id=isset($_POST['invoice_id'])?intval($_POST['invoice_id']):0;
                $invoice_number=isset($_POST['invoice_number'])?trim($_POST['invoice_number']):'';
                $data_count=$this->Invoice->find('count',array('conditions'=>array('Invoice.invoice_number'=>$invoice_number,'Invoice.id <>'=>$invoice_id)));
            }
            echo $data_count;
            die();
    }
}

 