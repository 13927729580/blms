<?php

/*****************************************************************************
 * Seevia 素材管理
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
 *素材管理.
 *
 *对于OpenElement这张表的增删改查
 *
 *@author   weizhngye
 *
 *@version  $Id$
 */
class OpenElementsController extends AppController
{
    /*
    *控制器的名字
    */
    public $name = 'OpenElements';
    /*
    *引用的助手
    */
    public $helpers = array('Html','Pagination','Tinymce','fck','Form','Javascript','Ckeditor');
    /*
    *引用的组件
    */
    public $components = array('Pagination','RequestHandler','Email');
    /*
    *引用的model
    */
    public $uses = array('Article','Page','OpenElement','Resource','InformationResource','Template','Template','OperatorLog','OpenUser','OpenModel','OpenUserMessage','OpenMedia','OpenConfig','Topic');

    /**
     *pagetype主页列表.
     *
     *呈现数据库表OpenElement的数据
     *
     *@author   weizhengye
     *
     *@version  $Id$
     */
    public function index($page = 1)
    {
        /*判断权限*/
        $this->operator_privilege('open_elements_view');
        $this->operation_return_url(true);//设置操作返回页面地址
        $this->menu_path = array('root' => '/open_model/','sub' => '/open_elements/');
        /*end*/
        $this->set('title_for_layout', $this->ld['open_elements'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['open_model'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['open_elements'],'url' => '/open_elements/');
        $conditions = array();
        if (isset($_REQUEST['keywords']) && $_REQUEST['keywords'] != '') {
            $conditions['and']['or']['OpenElement.title like'] = '%'.$_REQUEST['keywords'].'%';
            $this->set('keywords', $_REQUEST['keywords']);
        }
        $conditions['parent_id'] = 0;
        $cond['conditions'] = $conditions;
        //分页
        $total = $this->OpenElement->find('count', $cond);//获取总记录数
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
            $page = $_REQUEST['page'];//当前页
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';//默认显示记录数
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'OpenElement','action' => 'view','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'OpenElement');
        $this->Pagination->init($conditions, $parameters, $options);
        $cond['limit'] = $rownum;
        $cond['page'] = $page;
        $cond['order'] = 'OpenElement.created desc';
        $element_list = $this->OpenElement->find('all', $cond);
        $this->set('element_list', $element_list);
        //获取微信公众号类型（服务号）及认证状态
        $open_type = $this->OpenModel->find('all', array('conditions' => array('OpenModel.status' => 1, 'OpenModel.verify_status' => 1)));
        $this->set('open_type', $open_type);
        $element_ids=array();
        foreach($element_list as $v){
            $element_ids[]=$v['OpenElement']['id'];
        }
        $openmedia_list=$this->OpenMedia->find('list',array('fields'=>"OpenMedia.open_element_id,OpenMedia.id",'conditions'=>array('OpenMedia.open_element_id'=>$element_ids)));
        $this->set('openmedia_list',$openmedia_list);
    }

    /**
     *OpenElement修改页和添加页.
     *
     *增加和修改数据库表OpenElement的记录
     *
     *@author   weizhngye
     *
     *@version  $Id$
     */
    public function view($type = 1, $id = 0)
    {
        /*判断权限*/
        if ($id == 0) {
            $this->operator_privilege('open_elements_add');
        } else {
            $this->operator_privilege('open_elements_edit');
        }
        $this->menu_path = array('root' => '/open_model/','sub' => '/open_elements/');
        $this->set('title_for_layout', $this->ld['add_edit_page'].' - '.$this->configs['shop_name']);
        $this->navigations[] = array('name' => $this->ld['open_model'],'url' => '');
        $this->navigations[] = array('name' => $this->ld['open_elements'],'url' => '/open_elements/');
        $this->navigations[] = array('name' => $this->ld['add_edit_page'],'url' => '/open_elements/view/'.$type.'/'.$id);
        if ($this->RequestHandler->isPost()) {
		$seq=array();
		foreach($this->data as $arr2){
			$seq[]=isset($arr2['OpenElement']["seq"])?$arr2['OpenElement']["seq"]:$kk;
		}
            array_multisort($seq,SORT_ASC,$this->data);
            $data = $this->data[0];
            $data['OpenElement']['id'] = isset($data['OpenElement']['id']) && $data['OpenElement']['id'] != '' ? $data['OpenElement']['id'] : 0;
            $data['OpenElement']['parent_id'] = '0';
            if(empty($data['OpenElement']['id'])){
            	$data['OpenElement']['creator'] = $this->admin['id'];
            }else{
            	$data['OpenElement']['editor'] = $this->admin['id'];
            }
            $this->OpenElement->save($data);
            $parent_id = $this->OpenElement->id;
            if ($data['OpenElement']['url'] == '') {
                $url_link = $this->server_host.'/open_elements/'.$parent_id;
                $data['OpenElement']['id'] = $parent_id;
                $data['OpenElement']['url'] = $url_link;
                $this->OpenElement->save($data);
            }
            $old_child_element_count=$this->OpenElement->find('count',array('conditions'=>array('OpenElement.parent_id'=>$parent_id)));
            if (sizeof($this->data) > 0 && $parent_id != 0) {
                $child_ids=array();
                foreach ($this->data as $k => $v) {
                    if ($k != 0) {
                        if (trim($v['OpenElement']['title']) != '' && trim($v['OpenElement']['media_url']) != '' && trim($v['OpenElement']['description']) != '') {
                            $v['OpenElement']['parent_id'] = $parent_id;
                            if ($v['OpenElement']['url'] == '') {
                                $v['OpenElement']['url'] = '';
                            }
                            $this->OpenElement->saveAll($v);
                            $open_element_id = $this->OpenElement->id;
                            if ($v['OpenElement']['url'] == '') {
                                $v['OpenElement']['id'] = $open_element_id;
                                $v['OpenElement']['url'] = $this->server_host.'/open_elements/'.$open_element_id;
                                $this->OpenElement->saveAll($v);
                            }
                            $child_ids[]=$open_element_id;
                        } else {
                            if ($v['OpenElement']['id'] != '' && $v['OpenElement']['id'] != 0) {
                                $this->OpenElement->deleteAll(array('OpenElement.id' => $v['OpenElement']['id']));
                            }
                        }
                    }
                }
                if(!empty($child_ids)){
                    $del_cond=array();
                    $del_cond['not']['OpenElement.id']=$child_ids;
                    $del_cond['OpenElement.parent_id']=$parent_id;
                    $del_element_ids=$this->OpenElement->find('list',array('fields'=>array('OpenElement.id'),'conditions'=>$del_cond));
                    if(!empty($del_element_ids)){//删除多余数据
                        $this->OpenElement->deleteAll(array('OpenElement.id'=>$del_element_ids));
                        $this->OpenMedia->deleteAll(array('OpenMedia.open_element_id'=>$del_element_ids));
                    }
                }
            }
            $child_element_count=$this->OpenElement->find('count',array('conditions'=>array('OpenElement.parent_id'=>$parent_id)));
            if($child_element_count!=$old_child_element_count){//保存前后数量不一致时删除公众平台上相关素材信息
                $this->element_api_remove($parent_id);
            }
            /*操作员日志*/
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.$this->ld['modify'].$this->ld['source_material'].':id '.$id, $this->admin['id']);
            }
            $back_url = $this->operation_return_url();//获取操作返回页面地址
            $this->redirect($back_url);
        }
        $this->data = $this->OpenElement->find('first', array('conditions' => array('OpenElement.id' => $id)));
        //拿到id相关的数据
        //分成2个，一个是单图文只要拿出对应的id的内容,多图文拿出对应的id和拿出自己对应的parentid的集合
        if ($type == 2 && $id != 0) {
            //多图文
            $manypic = $this->OpenElement->find('all', array('conditions' => array('OpenElement.parent_id' => $id), 'order' => 'OpenElement.seq asc,OpenElement.created asc'));
            $this->set('manypic', $manypic);
        }
        if (!empty($this->data)) {
            $open_media_list=$this->OpenMedia->find('list',array('fields'=>array('OpenMedia.id','OpenMedia.open_type_id'),'conditions'=>array('OpenMedia.open_element_id'=>$id)));

            $open_model_list = $this->OpenModel->find('list', array('fields' => array('OpenModel.open_type_id'), 'conditions' => array('OpenModel.status' => 1, 'OpenModel.verify_status' => 1,'OpenModel.open_type_id'=>$open_media_list)));
            $this->set('open_model_list', $open_model_list);
        }
        $this->set('type', $type);
    }

    public function open_user_list($open_type_id = '')
    {
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        $condition['OpenUser.subscribe'] = '1';
        $condition['OpenUser.open_type_id'] = isset($_REQUEST['open_type_id']) ? $_REQUEST['open_type_id'] : $open_type_id;
        if (isset($_REQUEST['open_user_keywords']) && $_REQUEST['open_user_keywords'] != '') {
            $condition['or']['OpenUser.nickname like '] = '%'.urlencode($_REQUEST['open_user_keywords']).'%';
            $this->set('open_user_keywords', $_REQUEST['open_user_keywords']);
        }
        $page = 1;
        $total = $this->OpenUser->find('count', array('conditions' => $condition));
        $this->configs['show_count'] = $this->configs['show_count'] > $total ? $total : $this->configs['show_count'];
        if (isset($_REQUEST['page']) && $_REQUEST['page'] != '') {
            $page = $_REQUEST['page'];
        }
        $this->configs['show_count'] = (int) $this->configs['show_count'] ? $this->configs['show_count'] : '20';
        $rownum = !empty($this->configs['show_count']) ? $this->configs['show_count'] : ((!empty($rownum)) ? $rownum : 20);
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'OpenElement','action' => 'view/{$type}/{$id}/{$media_id}','page' => $page,'limit' => $rownum);
        $options = array('page' => $page,'show' => $rownum,'total' => $total,'modelClass' => 'OpenUser');
        $this->Pagination->init($condition, $parameters, $options);
        $user_list = $this->OpenUser->find('all', array('conditions' => $condition, 'page' => $page, 'limit' => $rownum, 'order' => 'OpenUser.created desc'));
        $this->set('user_list', $user_list);
    }

    /**
     *OpenElement删除的方法.
     *
     *删除OpenElement的记录（如果多图文的话，还要删除对应的父级id）
     *
     *@author   weizhngye
     *
     *@version  $Id$
     */
    public function remove($id)
    {
        /*判断权限*/
        Configure::write('debug', 1);
        $this->layout = 'ajax';
        if (!$this->operator_privilege('open_elements_remove', false)) {
            die(json_encode(array('flag' => 2, 'message' => $this->ld['have_no_operation_perform'])));
        }
        $api_result=$this->element_api_remove($id);
        if($api_result['code']=='1'){
            //先根据查找有没有对应的父级id，如果有的话删除
            $open_elementsInfo = $this->OpenElement->find('first', array('OpenElement.id' => $id));
            $this->OpenElement->deleteAll(array('OpenElement.parent_id' => $id));
            $this->OpenElement->deleteAll(array('OpenElement.id' => $id));
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].' '.'删除素材:id '.$id.' '.$open_elementsInfo['OpenElement']['title'], $this->admin['id']);
            }
            $result['flag'] = 1;
            $result['message'] = $this->ld['delete_the_ad_list_success'];
        }else{
            $result['flag'] = 0;
            $result['message'] = $this->ld['delete_failure'];
        }
        die(json_encode($result));
    }

    /**
     *OpenElement 批量删除的方法.
     *
     *批量删除OpenElement的记录
     *
     *@author   赵殷程
     *
     *@version  $Id$
     */
    public function removeall()
    {
        /*判断权限*/
        $this->operator_privilege('open_elements_remove');
        $open_elements_checkboxes = $_REQUEST['checkboxes'];
        $open_elements_Ids = '';
        foreach ($open_elements_checkboxes as $k => $v) {
            $open_elements_Ids = $open_elements_Ids.$v.',';
            $api_result=$this->element_api_remove($v);
            if($api_result['code']=='1'){
                $this->OpenElement->deleteAll(array('OpenElement.id' => $v));
                $this->OpenElement->deleteAll(array('OpenElement.parent_id' => $v));
            }
        }
        if ($open_elements_Ids != '') {
            $open_elements_Ids = substr($open_elements_Ids, 0, strlen($open_elements_Ids) - 1);
            //操作员日志
            if (isset($this->configs['operactions-log']) && $this->configs['operactions-log'] == 1) {
                $this->OperatorLog->log(date('H:i:s').' '.$this->ld['operator'].' '.$this->admin['name'].'删除所有素材:'.$open_elements_Ids, $this->admin['id']);
            }
        }
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($api_result));
    }

    //上传群发素材
    public function element_upload()
    {
        Configure::write('debug', 1);
        $this->layout="ajax";
        $element_id=isset($_POST['element_id'])?$_POST['element_id']:0;
        $element_type=isset($_POST['element_type'])?$_POST['element_type']:0;
        $open_type=isset($_POST['open_type'])?$_POST['open_type']:'wechat';
        $open_type_id=isset($_POST['open_type_id'])?$_POST['open_type_id']:'';
        $result['code'] = 1;
        $result['msg'] = '';
        $openmodelinfo = $this->OpenModel->find('first', array('conditions' => array('OpenModel.open_type'=>$open_type,'OpenModel.open_type_id' => $open_type_id)));
        if(empty($openmodelinfo)){
            $result['code'] = 0;
            $result['msg'] = 'Data Error';
            die(json_encode($result));
        }
        $this->OpenConfig->set_locale($this->backend_locale);
        $open_config_data=$this->OpenConfig->tree(array('open_type'=>$open_type,'open_type_id'=>$open_type_id));
        $HEADER_AREA_INFORMATION=isset($open_config_data['HEADER-AREA-INFORMATION']['OpenConfigsI18n'][$this->backend_locale]['value'])?trim($open_config_data['HEADER-AREA-INFORMATION']['OpenConfigsI18n'][$this->backend_locale]['value']):'';
        $BOTTOM_AREA_INFORMATION=isset($open_config_data['BOTTOM-AREA-INFORMATION']['OpenConfigsI18n'][$this->backend_locale]['value'])?trim($open_config_data['BOTTOM-AREA-INFORMATION']['OpenConfigsI18n'][$this->backend_locale]['value']):'';
        $max_size = 1024;
        $types = array('jpg','jpeg','JPG','JPEG','png','PNG');
        if ($element_type == 2) {
            $conditions['or'] = array('OpenElement.id' => $element_id,'OpenElement.parent_id' => $element_id);
        } else {
            $conditions = array('OpenElement.id' => $element_id);
        }
        $element_list = $this->OpenElement->find('all', array('conditions' => $conditions, 'group' => 'parent_id,id'));
        $img_dir = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);//服务器路径
        if (!empty($element_list)) {
            $element_ids=array();
            foreach ($element_list as $ik => $iv) {
                $element_ids[]=$iv['OpenElement']['id'];
                $str = $iv['OpenElement']['media_url'];
                if ($str == '' || strlen($str) == 0) {
                    $result['code'] = 0;
                    $result['msg'] = '请检查当前素材中是否都添加了图片';
                    break;
                }
                $img_url = $img_dir.$str;
                $imgInfo = $this->getImagesInfo($img_url);
                if (!empty($imgInfo)) {
                    if (!in_array($imgInfo['type'], $types)) {
                        $result['code'] = 0;
                        $result['msg'] = '当前素材的图片中存在不支持的图片类型';
                        break;
                    } elseif ($imgInfo['size'] == 0 || $imgInfo['size'] > $max_size * 1024) {
                        $result['code'] = 0;
                        $result['msg'] = '当前素材的图片中存在大小异常的图片,图片最大限制1M';
                        break;
                    }
                } else {
                    $result['code'] = 0;
                    $result['msg'] = '素材图片类型、大小获取失败';
                    break;
                }
            }
        } else {
            $result['code'] = 0;
            $result['msg'] = '未找到素材';
        }
        if ($result['code'] == 1) {
            $media_id="";
            $img_media_data = array();
            $open_media_list=$this->OpenMedia->find('all',array('conditions'=>array('OpenMedia.open_element_id'=>$element_ids,'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id)));
            foreach($open_media_list as $v){
                $img_media_data[$v['OpenMedia']['open_element_id']]=$v['OpenMedia'];
                $media_id=$v['OpenMedia']['media_id'];
            }
            $result['code'] = 0;
            if (!$this->OpenModel->validateToken($openmodelinfo)) {
                $openType = empty($openmodelinfo['OpenModel']['open_type']) ? '' : $openmodelinfo['OpenModel']['open_type'];
                $appId = $openmodelinfo['OpenModel']['app_id'];
                $appSecret = $openmodelinfo['OpenModel']['app_secret'];
                //无效重新获取
                $accessToken = $this->OpenModel->getAccessToken($appId, $appSecret, $openType);
                $openmodelinfo['OpenModel']['token'] = $accessToken;
                $this->OpenModel->save($openmodelinfo);
            }
            $access_token = $openmodelinfo['OpenModel']['token'];
            $error_message = '';

            $img_media = array();
            $uploadimgUrl = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$access_token.'&type=image'; //上传图片
            foreach ($element_list as $v) {
                if(isset($img_media_data[$v['OpenElement']['id']])&&$img_media_data[$v['OpenElement']['id']]['image_media_url']==$v['OpenElement']['media_url']){
                    $img_media[$v['OpenElement']['id']] = $img_media_data[$v['OpenElement']['id']]['image_media_id'];
                    continue;
                }
                $imgurl = '@'.$img_dir.$v['OpenElement']['media_url'];
                $data = array('media' => $imgurl);
                $data_result = $this->https_request($uploadimgUrl, $data);
                if (isset($data_result['media_id'])) {
                    $img_media[$v['OpenElement']['id']] = $data_result['media_id'];
                } else {
                    $error_message = $data_result['errmsg'];
                    break;
                }
                $this->OpenUserMessage->saveMsg(
                    'upload_img', json_encode($data), 0,
                    $openmodelinfo['OpenModel']['open_type_id'], 0,
                    isset($data_result['media_id']) ? 'ok' : 'no',
                    json_encode($data_result)
                );
            }
            if (!empty($img_media) && $error_message == ''){
                $data_result=array();
                if(empty($media_id)){
                    //上传素材
                    $uploadUrl = 'https://api.weixin.qq.com/cgi-bin/material/add_news?access_token='.$access_token;
                    $element_data = array();
                    foreach ($element_list as $v) {
                        $elementdata['thumb_media_id'] = isset($img_media[$v['OpenElement']['id']]) ? $img_media[$v['OpenElement']['id']] : '';
                        $elementdata['title'] = $v['OpenElement']['title'];
                        $elementdata['content_source_url'] = trim($v['OpenElement']['link'])!=''?$v['OpenElement']['link']:$v['OpenElement']['url'];
                        $element_content=$HEADER_AREA_INFORMATION.$v['OpenElement']['description'].$BOTTOM_AREA_INFORMATION;//素材内容组合
                        $element_content=$this->content_image_filtering($openmodelinfo,$element_content);//素材内容图片提交处理
                        $elementdata['content'] = addslashes($element_content);//内容进行转义处理
                        $elementdata['show_cover_pic'] = 1;
                        $element_data[] = $elementdata;
                    }
                    $data = array('articles' => $element_data);
                    $data = $this->to_josn($data);
                    $data_result = $this->https_request($uploadUrl, $data);
                    $this->OpenUserMessage->saveMsg(
                        'upload_new', $data, 0,
                        $openmodelinfo['OpenModel']['open_type_id'], 0,
                        isset($data_result['media_id']) ? 'ok' : 'no',
                        json_encode($data_result)
                    );
                }else{
                    //更新素材
                    $updateUrl = 'https://api.weixin.qq.com/cgi-bin/material/update_news?access_token='.$access_token;
                    foreach ($element_list as $k=>$v) {
                        $element_data = array();
                        $element_data['media_id'] = $media_id;
                        $element_article_data=array();
                        $element_article_data['thumb_media_id'] = isset($img_media[$v['OpenElement']['id']]) ? $img_media[$v['OpenElement']['id']] : '';
                        $element_article_data['title'] = $v['OpenElement']['title'];
                        $element_article_data['content_source_url'] = trim($v['OpenElement']['link'])!=''?$v['OpenElement']['link']:$v['OpenElement']['url'];
                        $element_content=$HEADER_AREA_INFORMATION.$v['OpenElement']['description'].$BOTTOM_AREA_INFORMATION;//素材内容组合
                        $element_content=$this->content_image_filtering($openmodelinfo,$element_content);//素材内容图片提交处理
                        $element_article_data['content'] = addslashes($element_content);//内容进行转义处理
                        $element_article_data['show_cover_pic'] = 1;
                        $element_data['articles'] = $element_article_data;
                        $element_data['index'] = $k;
                        $element_data = $this->to_josn($element_data);
                        $data_result = $this->https_request($updateUrl, $element_data);
                        $this->OpenUserMessage->saveMsg(
                            'update_new', $element_data, 0,
                            $openmodelinfo['OpenModel']['open_type_id'], 0,
                            $data_result['errmsg']=='ok'? 'ok' : 'no',
                            json_encode($data_result)
                        );
                    }
                }
                if (isset($data_result['media_id'])) {
                    $media_id=$data_result['media_id'];
                }else if($media_id!=""&&$data_result['errmsg']!='ok'){
                    $media_id="";
                }
                if(!empty($media_id)){
                    $result['code'] = 1;
                    $result['media_id'] = $media_id;
                    $get_element_url="https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=".$access_token;
                    $element_post_data = array('media_id' => $media_id);
                    $element_post_data = $this->to_josn($element_post_data);
                    $element_data=$this->https_request($get_element_url, $element_post_data);
                    if(!empty($element_data['news_item'])){
                        foreach($element_list as $k=>$v){
                            $open_media_data=$this->OpenMedia->find('first',array('conditions'=>array('OpenMedia.open_element_id'=>$v['OpenElement']['id'],'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id)));
                            $open_media_data['OpenMedia']['id']=isset($open_media_data['OpenMedia']['id'])?$open_media_data['OpenMedia']['id']:0;
                            $open_media_data['OpenMedia']['media_type']='image';
                            $open_media_data['OpenMedia']['open_type']=$open_type;
                            $open_media_data['OpenMedia']['open_type_id']=$open_type_id;
                            $open_media_data['OpenMedia']['open_element_id']=$v['OpenElement']['id'];
                            $open_media_data['OpenMedia']['image_media_id']=isset($img_media[$v['OpenElement']['id']])?$img_media[$v['OpenElement']['id']]:'';
                            $open_media_data['OpenMedia']['image_media_url']=$v['OpenElement']['media_url'];
                            $open_media_data['OpenMedia']['media_id']=$media_id;
                            $open_media_data['OpenMedia']['url']=isset($element_data['news_item'][$k]['url'])?$element_data['news_item'][$k]['url']:'';
                            $this->OpenMedia->save($open_media_data);
                        }
                    }
                } else {
                    $result['msg'] = $data_result['errmsg'];
                }
            } else {
                $result['code'] = 0;
                $result['msg'] = '图文上传失败';
            }
        }
        die(json_encode($result));
    }

    //素材API删除
    public function element_api_remove($element_id=0){
        $result=array();
        $result['code'] = 0;
        $result['msg'] = 'Data Error';
        $cond['and']['or']['OpenElement.id']=$element_id;
        $cond['and']['or']['OpenElement.parent_id']=$element_id;
        $open_elements_ids = $this->OpenElement->find('list', array('fields'=>array('OpenElement.id'),'conditions'=>$cond));
        if(!empty($open_elements_ids)){
            $media_list=$this->OpenMedia->find('all',array('conditions'=>array('OpenMedia.open_element_id'=>$open_elements_ids),'order'=>'OpenMedia.open_type,OpenMedia.open_type_id'));
            if(!empty($media_list)){
                $open_model_info=array();
                foreach($media_list as $v){
                    $open_model_info[$v['OpenMedia']['open_type']][]=$v['OpenMedia']['open_type_id'];
                }
                $open_model_cond=array();
                foreach($open_model_info as $k=>$v){
                    $openmodal_info=array_unique($v);
                    foreach($openmodal_info as $v){
                        $cond=array();
                        $cond['OpenModel.open_type']=$k;
                        $cond['OpenModel.open_type_id']=$v;
                        $open_model_cond['or'][]=$cond;
                    }
                }
                $open_model_data=$this->OpenModel->find('all',array('conditions'=>$open_model_cond,'order'=>'OpenModel.open_type,OpenModel.open_type_id'));
                unset($open_model_info);unset($open_model_cond);
                if(!empty($open_model_data)){
                    $open_model_new_data=array();
                    foreach($open_model_data as $v){
                        $open_model_new_data[$v['OpenModel']['open_type']][$v['OpenModel']['open_type_id']]=$v;
                    }
                    unset($open_model_data);
                    foreach($media_list as $v){
                        if(isset($open_model_new_data[$v['OpenMedia']['open_type']][$v['OpenMedia']['open_type_id']])){
                            $api_result=$this->api_element_remove($v['OpenMedia'],$open_model_new_data[$v['OpenMedia']['open_type']][$v['OpenMedia']['open_type_id']]);
                            if($api_result['code']=='1'){
                                $result['code'] = '1';
                                $this->OpenMedia->deleteAll(array('OpenMedia.id'=>$v['OpenMedia']['id']));
                            }
                        }else{
                            $result['code'] = '1';
                            $this->OpenMedia->deleteAll(array('OpenMedia.id'=>$v['OpenMedia']['id']));
                        }
                    }
                }
            }else{
                $result['code'] = '1';
            }
        }
        return $result;
    }

    public function api_element_remove($openmedia_data,$openmodelinfo){
        $result=array();
        $result['code'] = 0;
        if(empty($openmodelinfo)){
            $result['msg'] = 'Data Error';
            return $result;
        }
        $media_id=$openmedia_data['media_id'];
        if (!$this->OpenModel->validateToken($openmodelinfo)) {
            $openType = empty($openmodelinfo['OpenModel']['open_type']) ? '' : $openmodelinfo['OpenModel']['open_type'];
            $appId = $openmodelinfo['OpenModel']['app_id'];
            $appSecret = $openmodelinfo['OpenModel']['app_secret'];
            //无效重新获取
            $accessToken = $this->OpenModel->getAccessToken($appId, $appSecret, $openType);
            $openmodelinfo['OpenModel']['token'] = $accessToken;
            $this->OpenModel->save($openmodelinfo);
        }
        $access_token = $openmodelinfo['OpenModel']['token'];
        $del_Url = 'https://api.weixin.qq.com/cgi-bin/material/del_material?access_token='.$access_token;
        $request_data = array('media_id' => $media_id);
        $request_data = $this->to_josn($request_data);
        $data_result = $this->https_request($del_Url, $request_data);
        $this->OpenUserMessage->saveMsg(
            'del_material', $request_data, 0,
            $openmodelinfo['OpenModel']['open_type_id'], 0,
            $data_result['errmsg']=='ok'? 'ok' : 'no',
            json_encode($data_result)
        );
        if(isset($data_result['errcode'])&&$data_result['errcode']=='0'){
            $result['code'] = '1';
        }
        return $result;
    }

    public function send()
    {
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 1);
            $this->layout = 'ajax';
            $result['code'] = 1;
            $result['msg'] = '';
            $open_type_id = isset($_POST['open_type_id']) ? $_POST['open_type_id'] : '';
            $element_id = isset($_POST['element_id']) ? $_POST['element_id'] : 0;
            $send_type = isset($_POST['send_type']) ? $_POST['send_type'] : 'preview';
            $open_media_list=$this->OpenMedia->find('first',array('conditions'=>array('OpenMedia.open_element_id'=>$element_id,'OpenMedia.open_type_id'=>$open_type_id,"OpenMedia.media_id <>"=>0)));
            $openmodelinfo = $this->OpenModel->find('first', array('conditions' => array('OpenModel.open_type_id' => $open_type_id)));
            if (!empty($openmodelinfo)) {
                $openType = empty($openmodelinfo['OpenModel']['open_type']) ? '' : $openmodelinfo['OpenModel']['open_type'];
                $appId = $openmodelinfo['OpenModel']['app_id'];
                $appSecret = $openmodelinfo['OpenModel']['app_secret'];
                if (!$this->OpenModel->validateToken($openmodelinfo)) {
                    //无效重新获取
                    $accessToken = $this->OpenModel->getAccessToken($appId, $appSecret, $openType);
                    $openmodelinfo['OpenModel']['token'] = $accessToken;
                    $this->OpenModel->save($openmodelinfo);
                }
                $media_id = isset($open_media_list['OpenMedia']['media_id']) ? $open_media_list['OpenMedia']['media_id'] : '';
                if ($media_id == '') {
                    $result['msg'] = '素材尚未上传到当前公众平台';
                    die(json_encode($result));
                }
                $access_token = $openmodelinfo['OpenModel']['token'];
                $touser = isset($_POST['touser']) ? $_POST['touser'] : '';
                if ($touser != '') {
                    if($send_type=='send'){
                        $send_url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$access_token;
                        $send_data = array(
                            'touser' => $touser,
                            'mpnews' => array('media_id' => $media_id),
                            'msgtype' => 'mpnews',
                        );
                    }else{
                        $send_url = 'https://api.weixin.qq.com/cgi-bin/message/mass/preview?access_token='.$access_token;
                        $send_data = array(
                            'touser' => isset($touser[0])?$touser[0]:'',
                            'mpnews' => array('media_id' => $media_id),
                            'msgtype' => 'mpnews',
                        );
                    }
                    $send_data = json_encode($send_data);
                    $data_result = $this->https_request($send_url, $send_data);
                    $result['code'] = $data_result['errcode'];
                    $result['msg'] = $data_result['errcode'] == '0' ? $this->ld['send_success'] : $this->ld['send_failed'];

                    $this->OpenUserMessage->saveMsg(
                        'send_news', $send_data, 0,
                        $openmodelinfo['OpenModel']['open_type_id'], 0,
                        isset($data_result['errcode']) && $data_result['errcode'] == '0' ? 'ok' : 'no',
                        json_encode($data_result)
                    );
                } else {
                    $result['msg'] = '接收者不能为空';
                }
            } else {
                $result['msg'] = '未找到相应的公众平台账号';
            }
            die(json_encode($result));
        } else {
            $this->redirect('/open_elements/');
        }
    }

    /*
        调用接口
    */
    public function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return json_decode($output, true);
    }

    /*
        $data   需要转换josn提交的数据
    */
    public function to_josn($data)
    {
        $this->arrayRecursive($data, 'urlencode');
        $json = json_encode($data);

        return urldecode($json);
    }

    /**************************************************************
     * 对数组中所有元素做处理,保留中文
     * @param string &$array 要处理的数组
     * @param string $function 要执行的函数
     * @return boolean $apply_to_keys_also 是否也应用到key上
     * @access public
     *
     *************************************************************/
    public function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
            } else {
                $array[$key] = $function($value);
            }
            if ($apply_to_keys_also && is_string($key)) {
                $new_key = $function($key);
                if ($new_key != $key) {
                    $array[$new_key] = $array[$key];
                    unset($array[$key]);
                }
            }
        }
        --$recursive_counter;
    }

    //参数images为图片的绝对地址
    public function getImagesInfo($images)
    {
        $img_info = getimagesize($images);
        switch ($img_info[2]) {
            case 1:
                $imgtype = 'gif';
                break;
            case 2:
                $imgtype = 'jpg';
                break;
            case 3:
                $imgtype = 'png';
                break;
        }
        $img_type = $imgtype;
        //获取文件大小     
        $img_size = ceil(filesize($images) / 1000);//kb
        $new_img_info = array(
            'url' => $images,
            'width' => $img_info[0], //图像宽
            'height' => $img_info[1], //图像高
            'type' => $img_type, //图像类型
            'size' => $img_size, //图像大小
        );
        return $new_img_info;
    }

    /*
    	 素材正文图片过滤处理
    */
    public function content_image_filtering($openmodelinfo,$wechat_content){
        $access_token = $openmodelinfo['OpenModel']['token'];
        $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.JPG|\.GIF]))[\'|\"].*?[\/]?>/";
        preg_match_all($pattern,$wechat_content,$img_match);
        if(!empty($img_match)){
            $new_img_data=array();
            $img_dir = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);//服务器路径
            if(isset($img_match[1])&&!empty($img_match[1])){
                $old_img_url=array_unique($img_match[1]);
                foreach($old_img_url as $k=>$v){
                    $data_result=array();
                    $imgurl = str_replace($this->server_host,'',$v);
                    $uploadimgUrl = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$access_token; //上传图片
                    $imgurl = $img_dir.$imgurl;
                    if(file_exists($imgurl)){
                        $data = array('media' => "@".$imgurl);
                        $data_result = $this->https_request($uploadimgUrl, $data);
                        if (isset($data_result['url'])) {
                            $new_img_data[$k] = $data_result['url'];
                        }
                    }
                }
                foreach($old_img_url as $k=>$v){
                    if(isset($new_img_data[$k])){
                        $wechat_content = str_replace($v,$new_img_data[$k],$wechat_content);
                    }
                }
            }
        }
        return $this->compress_html($wechat_content);
    }

    function compress_html($content) {
        $content = str_replace("\r\n", '', $content); //清除换行符
        $content = str_replace("\n", '', $content); //清除换行符
        $content = str_replace("\t", '', $content); //清除制表符
        $pattern = array (
            "/> *([^ ]*) *</", //去掉注释标记
            "/[\s]+/",
            "/<!--[\\w\\W\r\\n]*?-->/",
            "/\" /",
            "/ \"/",
            "'/\*[^*]*\*/'"
        );
        $replace = array (
            ">\\1<",
            " ",
            "",
            "\"",
            "\"",
            ""
        );
        return preg_replace($pattern, $replace, $content);
    }

    public function sendAll()
    {
        if ($this->RequestHandler->isPost()) {
            Configure::write('debug', 1);
            $this->layout = 'ajax';
            $result['code'] = 1;
            $result['msg'] = '';
            $open_type_id = isset($_POST['open_type_id']) ? $_POST['open_type_id'] : '';
            $element_id = isset($_POST['element_id']) ? $_POST['element_id'] : 0;
            $open_media_list=$this->OpenMedia->find('first',array('conditions'=>array('OpenMedia.open_element_id'=>$element_id,'OpenMedia.open_type_id'=>$open_type_id,"OpenMedia.media_id <>"=>0)));
            $openmodelinfo = $this->OpenModel->find('first', array('conditions' => array('OpenModel.open_type_id' => $open_type_id)));
            if (!empty($openmodelinfo)) {
                $openType = empty($openmodelinfo['OpenModel']['open_type']) ? '' : $openmodelinfo['OpenModel']['open_type'];
                $appId = $openmodelinfo['OpenModel']['app_id'];
                $appSecret = $openmodelinfo['OpenModel']['app_secret'];
                if (!$this->OpenModel->validateToken($openmodelinfo)) {
                    //无效重新获取
                    $accessToken = $this->OpenModel->getAccessToken($appId, $appSecret, $openType);
                    $openmodelinfo['OpenModel']['token'] = $accessToken;
                    $this->OpenModel->save($openmodelinfo);
                }
                $media_id = isset($open_media_list['OpenMedia']['media_id']) ? $open_media_list['OpenMedia']['media_id'] : '';
                if ($media_id == '') {
                    $result['msg'] = '素材尚未上传到当前公众平台';
                    die(json_encode($result));
                }
                $access_token = $openmodelinfo['OpenModel']['token'];
                $condition=array();
                $condition['OpenUser.subscribe'] = '1';
                $condition['OpenUser.open_type_id'] = $open_type_id;
                $user_list = $this->OpenUser->find('all', array('conditions' => $condition,'order' => 'OpenUser.created desc'));
                $touser = array();
                foreach($user_list as $row){
                    foreach($row as $v){
                        $touser[] = $v['openid'];
                    }
                }
                if ($touser != '') {
                    $send_url = 'https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='.$access_token;
                    $send_data = array(
                        'touser' => $touser,
                        'mpnews' => array('media_id' => $media_id),
                        'msgtype' => 'mpnews',
                    );
                    $send_data = json_encode($send_data);
                    $data_result = $this->https_request($send_url, $send_data);
                    $result['code'] = $data_result['errcode'];
                    $result['msg'] = $data_result['errcode'] == '0' ? $this->ld['send_success'] . ',共 ' . count($touser) . ' 位用户' : $this->ld['send_failed'];
                    $this->OpenUserMessage->saveMsg(
                        'send_news', $send_data, 0,
                        $openmodelinfo['OpenModel']['open_type_id'], 0,
                        isset($data_result['errcode']) && $data_result['errcode'] == '0' ? 'ok' : 'no',
                        json_encode($data_result)
                    );
                    $send_msg_id=isset($data_result['msg_id'])?$data_result['msg_id']:'';
                    $this->OpenElement->save(array('id'=>$element_id,'response'=>$send_msg_id));
                } else {
                    $result['msg'] = '接收者不能为空';
                }
            } else {
                $result['msg'] = '未找到相应的公众平台账号';
            }
            die(json_encode($result));
        } else {
            $this->redirect('/open_elements/');
        }
    }

    public function ajaxGetArticleContent(){
        $result = $this->Article->localeformat($_GET['id']);
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result['ArticleI18n'][$this->locale]));
    }
    
    public function ajaxGetTopicContent(){
        $result = $this->Topic->localeformat($_GET['id']);
        Configure::write('debug', 0);
        $this->layout = 'ajax';
        die(json_encode($result['TopicI18n'][$this->locale]));
    }

    public function ajaxGetPageContent(){
        $id = $_GET['id'] ? $_GET['id'] : 0;
        if (!is_numeric($id) || $id < 1) {
            $this->pageTitle = $this->ld['invalid_id'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['invalid_id'], '/', 5);
            return;
        }
        $conditions = array('Page.id' => $id,'Page.status' => '1');
        $this->Page->set_locale($this->locale);
        $page = $this->Page->find('first', array('conditions' => $conditions));
        if (empty($page)) {
            $this->pageTitle = $this->ld['page'].' - '.$this->configs['shop_title'];
            $this->flash($this->ld['page'].$this->ld['not_exist'], '/', 5);
            return;
        } elseif (!empty($page)) {
            $this->pageTitle = $page['PageI18n']['title'].' - '.$this->configs['shop_title'];
        }
        $this->set('page', $page);
        $this->set('meta_description', $page['PageI18n']['meta_description'].' '.$this->configs['seo-des']);
        $this->set('meta_keywords', $page['PageI18n']['meta_keywords'].' '.$this->configs['seo-key']);
        $this->ur_heres[] = array('name' => $page['PageI18n']['title'],'url' => '');
        $this->pageTitle = $page['PageI18n']['title'].' - '.$this->configs['shop_title'];
    }

    public function ajaxGetElement(){
        Configure::write('debug', 1);
        $this->layout="ajax";
        $element_id=isset($_POST['element_id'])?$_POST['element_id']:0;
        $element_type=isset($_POST['element_type'])?$_POST['element_type']:0;
        $open_type=isset($_POST['open_type'])?$_POST['open_type']:'wechat';
        $open_type_id=isset($_POST['open_type_id'])?$_POST['open_type_id']:'';
        $result['code'] = 1;
        $result['msg'] = '';
        $openmodelinfo = $this->OpenModel->find('first', array('conditions' => array('OpenModel.open_type'=>$open_type,'OpenModel.open_type_id' => $open_type_id)));
        if(empty($openmodelinfo)){
            $result['code'] = 0;
            $result['msg'] = 'Data Error';
            die(json_encode($result));
        }
        $this->OpenConfig->set_locale($this->backend_locale);
        $max_size = 1024;
        $types = array('jpg','jpeg','JPG','JPEG','png','PNG');
        if ($element_type == 2) {
            $conditions['or'] = array('OpenElement.id' => $element_id,'OpenElement.parent_id' => $element_id);
        } else {
            $conditions = array('OpenElement.id' => $element_id);
        }
        $element_list = $this->OpenElement->find('all', array('conditions' => $conditions, 'group' => 'parent_id,id'));
        $img_dir = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);//服务器路径
        if (!empty($element_list)) {
            $element_ids=array();
            foreach ($element_list as $ik => $iv) {
                $element_ids[]=$iv['OpenElement']['id'];
                $str = $iv['OpenElement']['media_url'];
                if ($str == '' || strlen($str) == 0) {
                    $result['code'] = 0;
                    $result['msg'] = '请检查当前素材中是否都添加了图片';
                    //break;
                }
                $img_url = $img_dir.$str;
                $imgInfo = $this->getImagesInfo($img_url);
                if (!empty($imgInfo)) {
                    if (!in_array($imgInfo['type'], $types)) {
                        $result['code'] = 0;
                        $result['msg'] = '当前素材的图片中存在不支持的图片类型';
                        //break;
                    } elseif ($imgInfo['size'] == 0 || $imgInfo['size'] > $max_size * 1024) {
                        $result['code'] = 0;
                        $result['msg'] = '当前素材的图片中存在大小异常的图片,图片最大限制1M';
                        //break;
                    }
                } else {
                    $result['code'] = 0;
                    $result['msg'] = '素材图片类型、大小获取失败';
                    //break;
                }
                if($result['code'] ==0){
                	$this->OpenUserMessage->saveMsg(
		                'upload_img', json_encode($iv), 0,
		                $openmodelinfo['OpenModel']['open_type_id'], 0,
		                 'no',
		                json_encode($result)
		            );
	            	break;
                }
            }
        } else {
            $result['code'] = 0;
            $result['msg'] = '未找到素材';
        }
        $result['data'] = array();
        $num = 0;
        if($result['code'] == 1){
            foreach ($element_list as $k => $v) {
                $open_media_list=$this->OpenMedia->find('all',array('conditions'=>array('OpenMedia.open_element_id'=>$element_ids,'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id,'OpenMedia.image_media_url' => $v['OpenElement']['media_url'])));
                if($open_media_list) continue;
                $open_media_data['OpenMedia']['media_type']='news';
                $open_media_data['OpenMedia']['open_type']=$open_type;
                $open_media_data['OpenMedia']['open_type_id']=$open_type_id;
                $open_media_data['OpenMedia']['open_element_id']=$v['OpenElement']['id'];
                $open_media_data['OpenMedia']['image_media_id']='0';
                $open_media_data['OpenMedia']['image_media_url']=$v['OpenElement']['media_url'];
                $open_media_data['OpenMedia']['media_id']=0;
                $open_media_data['OpenMedia']['url']='';
                $this->OpenMedia->save($open_media_data);
                $num++;
            }
        }
        $result['data'] = $element_list;
        $result['img_num'] = $num;
        die(json_encode($result));
    }

    public function ajaxGetUploadImage(){
        Configure::write('debug', 1);
        $this->layout="ajax";
        $element_id=isset($_POST['element_id'])?$_POST['element_id']:0;
        $element_type=isset($_POST['element_type'])?$_POST['element_type']:0;
        $open_type=isset($_POST['open_type'])?$_POST['open_type']:'wechat';
        $open_type_id=isset($_POST['open_type_id'])?$_POST['open_type_id']:'';
        $result = array(
            'code' => 0,
            'msg' => '',
            'data' => array()
        );
        $openmodelinfo = $this->OpenModel->find('first', array('conditions' => array('OpenModel.open_type'=>$open_type,'OpenModel.open_type_id' => $open_type_id)));
        $this->OpenConfig->set_locale($this->backend_locale);
        if ($element_type == 2) {
            $conditions['or'] = array('OpenElement.id' => $element_id,'OpenElement.parent_id' => $element_id);
        } else {
            $conditions = array('OpenElement.id' => $element_id);
        }
        $element_list = $this->OpenElement->find('all', array('conditions' => $conditions, 'group' => 'parent_id,id'));
        $img_dir = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);//服务器路径
        $element_ids=array();
        foreach ($element_list as $ik => $iv) {
            $element_ids[]=$iv['OpenElement']['id'];
        }
        $img_media_data = array();
        $open_media_list=$this->OpenMedia->find('all',array('conditions'=>array('OpenMedia.open_element_id'=>$element_ids,'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id)));
        foreach($open_media_list as $v){
            $img_media_data[$v['OpenMedia']['open_element_id']]=$v['OpenMedia'];
        }
        if (!$this->OpenModel->validateToken($openmodelinfo)) {
            $openType = empty($openmodelinfo['OpenModel']['open_type']) ? '' : $openmodelinfo['OpenModel']['open_type'];
            $appId = $openmodelinfo['OpenModel']['app_id'];
            $appSecret = $openmodelinfo['OpenModel']['app_secret'];
            //无效重新获取
            $accessToken = $this->OpenModel->getAccessToken($appId, $appSecret, $openType);
            $openmodelinfo['OpenModel']['token'] = $accessToken;
            $this->OpenModel->save($openmodelinfo);
        }
        $access_token = $openmodelinfo['OpenModel']['token'];
        $img_media = array();
        $uploadimgUrl = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$access_token.'&type=image'; //上传图片
        foreach ($element_list as $k => $v) {
            if(isset($img_media_data[$v['OpenElement']['id']])&&$img_media_data[$v['OpenElement']['id']]['image_media_url']==$v['OpenElement']['media_url']){
                $open_media_list=$this->OpenMedia->find('all',array('conditions'=>array('OpenMedia.open_element_id'=>$v['OpenElement']['id'],'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id,'OpenMedia.image_media_url' => $v['OpenElement']['media_url'],'OpenMedia.image_media_id !=' => '0')));
                if($open_media_list) {
                    $result = array(
                        'code' => 1,
                        'msg' => 'success',
                        'data' => 'continue',
                    );
                    continue;
                }
            }
            $imgurl = '@'.$img_dir.$v['OpenElement']['media_url'];
            $data = array('media' => $imgurl);
            $data_result = $this->https_request($uploadimgUrl, $data);
            if (isset($data_result['media_id'])) {
                $img_media[$v['OpenElement']['id']] = $data_result['media_id'];
                $open_media_data=$this->OpenMedia->find('first',array('conditions'=>array('OpenMedia.open_element_id'=>$v['OpenElement']['id'],'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id,'OpenMedia.image_media_url' => $v['OpenElement']['media_url'])));
                $open_media_data['OpenMedia']['media_type']='news';
                $open_media_data['OpenMedia']['open_type']=$open_type;
                $open_media_data['OpenMedia']['open_type_id']=$open_type_id;
                $open_media_data['OpenMedia']['open_element_id']=$v['OpenElement']['id'];
                $open_media_data['OpenMedia']['image_media_id']=$data_result['media_id'];
                $open_media_data['OpenMedia']['image_media_url']=$v['OpenElement']['media_url'];
                $open_media_data['OpenMedia']['media_id']=0;
                $open_media_data['OpenMedia']['url']='';
                $this->OpenMedia->save($open_media_data);
                $result = array(
                    'code' => 1,
                    'msg' => 'success',
                    'data' => $img_media,
                );
            } else {
                $error_message = $data_result['errmsg'];
                $result['msg'] = $error_message;
                break;
            }
            $this->OpenUserMessage->saveMsg(
                'upload_img', json_encode($data), 0,
                $openmodelinfo['OpenModel']['open_type_id'], 0,
                isset($data_result['media_id']) ? 'ok' : 'no',
                json_encode($data_result)
            );
        }
        die(json_encode($result));
    }

    public function ajaxUploadElement(){
        Configure::write('debug', 1);
        $this->layout="ajax";
        $element_id=isset($_POST['element_id'])?$_POST['element_id']:0;
        $element_type=isset($_POST['element_type'])?$_POST['element_type']:0;
        $open_type=isset($_POST['open_type'])?$_POST['open_type']:'wechat';
        $open_type_id=isset($_POST['open_type_id'])?$_POST['open_type_id']:'';
        $result['code'] = 0;
        $result['msg'] = '';
        $openmodelinfo = $this->OpenModel->find('first', array('conditions' => array('OpenModel.open_type'=>$open_type,'OpenModel.open_type_id' => $open_type_id)));
        if (!$this->OpenModel->validateToken($openmodelinfo)) {
            $openType = empty($openmodelinfo['OpenModel']['open_type']) ? '' : $openmodelinfo['OpenModel']['open_type'];
            $appId = $openmodelinfo['OpenModel']['app_id'];
            $appSecret = $openmodelinfo['OpenModel']['app_secret'];
            //无效重新获取
            $accessToken = $this->OpenModel->getAccessToken($appId, $appSecret, $openType);
            $openmodelinfo['OpenModel']['token'] = $accessToken;
            $this->OpenModel->save($openmodelinfo);
        }
        $access_token = $openmodelinfo['OpenModel']['token'];
        $this->OpenConfig->set_locale($this->backend_locale);
        $open_config_data=$this->OpenConfig->tree(array('open_type'=>$open_type,'open_type_id'=>$open_type_id));
        if ($element_type == 2) {
            $conditions['or'] = array('OpenElement.id' => $element_id,'OpenElement.parent_id' => $element_id);
        } else {
            $conditions = array('OpenElement.id' => $element_id);
        }
        $element_list = $this->OpenElement->find('all', array('conditions' => $conditions, 'group' => 'parent_id,id'));
        $img_media_data = array();
        $element_ids=array();
        foreach ($element_list as $ik => $iv) {
            $element_ids[]=$iv['OpenElement']['id'];
        }
        $open_media_list=$this->OpenMedia->find('all',array('conditions'=>array('OpenMedia.open_element_id'=>$element_ids,'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id)));
        foreach($open_media_list as $v){
            $img_media_data[$v['OpenMedia']['open_element_id']]=$v['OpenMedia'];
            $media_id=$v['OpenMedia']['media_id'];
        }
        $img_media=array();
        foreach ($element_list as $k => $v) {
            $open_media_list=$this->OpenMedia->find('all',array('conditions'=>array('OpenMedia.open_element_id'=>$element_ids,'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id,'OpenMedia.image_media_url' => $v['OpenElement']['media_url'])));
            if($open_media_list)
                $img_media[$v['OpenElement']['id']] = $open_media_list[0]['OpenMedia']['image_media_id'];
        }
        $HEADER_AREA_INFORMATION=isset($open_config_data['HEADER-AREA-INFORMATION']['OpenConfigsI18n'][$this->backend_locale]['value'])?trim($open_config_data['HEADER-AREA-INFORMATION']['OpenConfigsI18n'][$this->backend_locale]['value']):'';
        $BOTTOM_AREA_INFORMATION=isset($open_config_data['BOTTOM-AREA-INFORMATION']['OpenConfigsI18n'][$this->backend_locale]['value'])?trim($open_config_data['BOTTOM-AREA-INFORMATION']['OpenConfigsI18n'][$this->backend_locale]['value']):'';
        $result['code'] = $old_img_url = 0;
        $pattern="/<[img|IMG].*?src=[\'|\"](.*?)[\'|\"].*?[\/]?>/";
        preg_match_all($pattern,$BOTTOM_AREA_INFORMATION,$img_match);
        if(!empty($img_match)){
            if(isset($img_match[1])&&!empty($img_match[1])){
                $old_img_url=array_unique($img_match[1]);
            }
        }
        if (!empty($img_media)){
            $data_result=array();
            if(empty($media_id)){
                //上传素材
                $uploadUrl = 'https://api.weixin.qq.com/cgi-bin/material/add_news?access_token='.$access_token;
                $element_data = array();
                foreach($element_list as $arr2){
                    $seq[]=$arr2['OpenElement']["seq"];
                }
                array_multisort($seq,SORT_ASC,$element_list);
                foreach ($element_list as $k => $v) {
                    $elementdata['thumb_media_id'] = isset($img_media[$v['OpenElement']['id']]) ? $img_media[$v['OpenElement']['id']] : '';
                    $elementdata['title'] = $v['OpenElement']['title'];
                    $elementdata['content_source_url'] = trim($v['OpenElement']['link'])!=''?$v['OpenElement']['link']:$v['OpenElement']['url'];
                    $element_content=$HEADER_AREA_INFORMATION.$v['OpenElement']['description'].$BOTTOM_AREA_INFORMATION;//素材内容组合
                    $open_media_data_image_media=$this->OpenMedia->find('all',array('conditions'=>array('OpenMedia.open_element_id'=>$element_ids,'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id,'OpenMedia.image_media_id'=>$v['OpenElement']['id'])));
                    if($open_media_data_image_media){
                        foreach($open_media_data_image_media as $image_v){
                            $old_img_data[] = $image_v['OpenMedia']['image_media_url'];
                            $new_img_data[] = $image_v['OpenMedia']['url'];
                            $element_content = str_replace($old_img_data,$new_img_data,$element_content);
                        }
                    }
                    if(!empty($old_img_url)){
                        foreach($old_img_url as $old_img){
                            $open_media_data_image_media=$this->OpenMedia->find('first',array('conditions'=>array('OpenMedia.open_element_id'=>$element_ids,'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id,'OpenMedia.image_media_url'=>$old_img_url)));
                            if($open_media_data_image_media){
                                $element_content = str_replace($old_img,$open_media_data_image_media['OpenMedia']['url'],$element_content);
                            }
                        }
                    }
                    $element_content = $this->compress_html($element_content);
                    $elementdata['content'] = addslashes($element_content);//内容进行转义处理
                    $elementdata['show_cover_pic'] = 1;
                    $element_data[] = $elementdata;
                }
                $data = array('articles' => $element_data);
                $data = $this->to_josn($data);
                $data_result = $this->https_request($uploadUrl, $data);
                $this->OpenUserMessage->saveMsg(
                    'upload_new', $data, 0,
                    $openmodelinfo['OpenModel']['open_type_id'], 0,
                    isset($data_result['media_id']) ? 'ok' : 'no',
                    json_encode($data_result)
                );
            }else{
                //更新素材
                $updateUrl = 'https://api.weixin.qq.com/cgi-bin/material/update_news?access_token='.$access_token;
                foreach($element_list as $arr2){
                    $seq[]=$arr2['OpenElement']["seq"];
                }
                array_multisort($seq,SORT_ASC,$element_list);
                foreach ($element_list as $k=>$v) {
                    $element_data = array();
                    $element_data['media_id'] = $media_id;
                    $element_article_data=array();
                    $element_article_data['thumb_media_id'] = isset($img_media[$v['OpenElement']['id']]) ? $img_media[$v['OpenElement']['id']] : '';
                    $element_article_data['title'] = $v['OpenElement']['title'];
                    $element_article_data['content_source_url'] = trim($v['OpenElement']['link'])!=''?$v['OpenElement']['link']:$v['OpenElement']['url'];
                    $element_content=$HEADER_AREA_INFORMATION.$v['OpenElement']['description'].$BOTTOM_AREA_INFORMATION;//素材内容组合
                    $open_media_data_image_media=$this->OpenMedia->find('all',array('conditions'=>array('OpenMedia.open_element_id'=>$element_ids,'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id,'OpenMedia.image_media_id'=>$v['OpenElement']['id'])));
                    if($open_media_data_image_media){
                        foreach($open_media_data_image_media as $image_v){
                            $old_img_data[] = $image_v['OpenMedia']['image_media_url'];
                            $new_img_data[] = $image_v['OpenMedia']['url'];
                            $element_content = str_replace($old_img_data,$new_img_data,$element_content);
                        }
                    }
                    if(!empty($old_img_url)){
                        foreach($old_img_url as $old_img){
                            $open_media_data_image_media=$this->OpenMedia->find('first',array('conditions'=>array('OpenMedia.open_element_id'=>$element_ids,'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id,'OpenMedia.image_media_url'=>$old_img_url)));
                            if($open_media_data_image_media){
                                $element_content = str_replace($old_img,$open_media_data_image_media['OpenMedia']['url'],$element_content);
                            }
                        }
                    }
                    $element_content = $this->compress_html($element_content);
                    $element_article_data['content'] = addslashes($element_content);//内容进行转义处理
                    $element_article_data['show_cover_pic'] = 1;
                    $element_data['articles'] = $element_article_data;
                    $element_data['index'] = $k;
                    $element_data = $this->to_josn($element_data);
                    $data_result = $this->https_request($updateUrl, $element_data);
                    $this->OpenUserMessage->saveMsg(
                        'update_new', $element_data, 0,
                        $openmodelinfo['OpenModel']['open_type_id'], 0,
                        $data_result['errmsg']=='ok'? 'ok' : 'no',
                        json_encode($data_result)
                    );
                }
            }
            if (isset($data_result['media_id'])) {
                $media_id=$data_result['media_id'];
            }else if($media_id!=""&&$data_result['errmsg']!='ok'){
                $media_id="";
            }
            if(!empty($media_id)){
                $result['code'] = 1;
                $result['media_id'] = $media_id;
                $get_element_url="https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=".$access_token;
                $element_post_data = array('media_id' => $media_id);
                $element_post_data = $this->to_josn($element_post_data);
                $element_data=$this->https_request($get_element_url, $element_post_data);
                if(!empty($element_data['news_item'])){
                    foreach($element_list as $k=>$v){
                        $open_media_data=$this->OpenMedia->find('first',array('conditions'=>array('OpenMedia.open_element_id'=>$v['OpenElement']['id'],'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id,'OpenMedia.media_id !=' => '0')));
                        $open_media_data['OpenMedia']['id']=isset($open_media_data['OpenMedia']['id'])?$open_media_data['OpenMedia']['id']:0;
                        $open_media_data['OpenMedia']['media_type']='news';
                        $open_media_data['OpenMedia']['open_type']=$open_type;
                        $open_media_data['OpenMedia']['open_type_id']=$open_type_id;
                        $open_media_data['OpenMedia']['open_element_id']=$v['OpenElement']['id'];
                        $open_media_data['OpenMedia']['image_media_id']=isset($img_media[$v['OpenElement']['id']])?$img_media[$v['OpenElement']['id']]:'';
                        $open_media_data['OpenMedia']['image_media_url']=$v['OpenElement']['media_url'];
                        $open_media_data['OpenMedia']['media_id']=$media_id;
                        $open_media_data['OpenMedia']['url']=isset($element_data['news_item'][$k]['url'])?$element_data['news_item'][$k]['url']:'';
                        $this->OpenMedia->save($open_media_data);
                    }
                }
            } else {
                $result['msg'] = $data_result['errmsg'];
            }
        }
        die(json_encode($result));
    }

    public function ajaxGetTotalImageByContent(){
        Configure::write('debug', 1);
        $this->layout="ajax";
        $element_id=isset($_POST['element_id'])?$_POST['element_id']:0;
        $element_type=isset($_POST['element_type'])?$_POST['element_type']:0;
        $open_type=isset($_POST['open_type'])?$_POST['open_type']:'wechat';
        $open_type_id=isset($_POST['open_type_id'])?$_POST['open_type_id']:'';
        $key = isset($_POST['key'])?$_POST['key']:-1;
        $this->OpenConfig->set_locale($this->backend_locale);
        $open_config_data=$this->OpenConfig->tree(array('open_type'=>$open_type,'open_type_id'=>$open_type_id));
        $HEADER_AREA_INFORMATION=isset($open_config_data['HEADER-AREA-INFORMATION']['OpenConfigsI18n'][$this->backend_locale]['value'])?trim($open_config_data['HEADER-AREA-INFORMATION']['OpenConfigsI18n'][$this->backend_locale]['value']):'';
        $BOTTOM_AREA_INFORMATION=isset($open_config_data['BOTTOM-AREA-INFORMATION']['OpenConfigsI18n'][$this->backend_locale]['value'])?trim($open_config_data['BOTTOM-AREA-INFORMATION']['OpenConfigsI18n'][$this->backend_locale]['value']):'';
        if ($element_type == 2) {
            $conditions['or'] = array('OpenElement.id' => $element_id,'OpenElement.parent_id' => $element_id);
        } else {
            $conditions = array('OpenElement.id' => $element_id);
        }
        $element_list = $this->OpenElement->find('all', array('conditions' => $conditions, 'group' => 'parent_id,id'));
        $result = array(
            'code' => 1,
            'msg' => 'success',
            'data' => array(),
        );
        $element_ids=array();
        foreach ($element_list as $ik => $iv) {
            $element_ids[]=$iv['OpenElement']['id'];
        }
        $open_media_list=$this->OpenMedia->find('all',array('conditions'=>array('OpenMedia.open_element_id'=>$element_ids,'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id,'OpenMedia.media_id !='=>0)));
        $media_id = 0;
        foreach($open_media_list as $value){
            $media_id=$value['OpenMedia']['media_id'];
        }
        $element_content_data = $res = array();
        $filter_domain_arr = array(
            'mmbiz.qpic.cn',
            'kf.qq.com',
            'res.wx.qq.com',
        );
        foreach ($element_list as $k =>  $v) {
            $element_content = $v['OpenElement']['description'].$BOTTOM_AREA_INFORMATION;//加上头和尾会增加不需上传的图片，导致统计图片数量时有误
            $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.JPG|\.GIF]))[\'|\"].*?[\/]?>/";   //上传后的图片无图片格式。故修改正则
            preg_match_all($pattern,$element_content,$img_match);
            if(!empty($img_match)){
                if(isset($img_match[1])&&!empty($img_match[1])){
                    $old_img_url=array_unique($img_match[1]);
                    if(empty($old_img_url)) continue;
                    foreach($old_img_url as $k => $img_url){
                        foreach($filter_domain_arr as $filter_domain){
                            if(stristr($img_url,$filter_domain) !== false)
                                continue 2;
                        }
                        $open_media_data=$this->OpenMedia->find('first',array('conditions'=>array('OpenMedia.open_element_id'=>$element_id,'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id,'image_media_url' =>$img_url)));
                        if(!$open_media_data){
                            $open_media_data['OpenMedia']['media_type']='news';
                            $open_media_data['OpenMedia']['open_type']=$open_type;
                            $open_media_data['OpenMedia']['open_type_id']=$open_type_id;
                            $open_media_data['OpenMedia']['open_element_id']=$element_id;
                            $open_media_data['OpenMedia']['image_media_id']=$v['OpenElement']['id'];
                            $open_media_data['OpenMedia']['image_media_url']=$img_url;
                            $open_media_data['OpenMedia']['media_id']=$media_id;
                            $open_media_data['OpenMedia']['url']=$img_url;
                            $this->OpenMedia->save($open_media_data);
                            if(!in_array($img_url,$result['data'])){
                                $result['data'][] = $img_url;
                                $res[] = $v['OpenElement']['title'];
                            }
                        }
                        $open_media_data=$this->OpenMedia->find('first',array('conditions'=>array('OpenMedia.open_element_id'=>$element_id,'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id,'image_media_url' =>$img_url,'url' =>$img_url)));
                        if($open_media_data && !in_array($img_url,$result['data'])){
                            $result['data'][] = $img_url;
                            $res[] = $v['OpenElement']['title'];
                        }
                    }
                }
            }
            $tmp_element_content_data[] = $element_content;
            $element_content_data[] = $HEADER_AREA_INFORMATION.$v['OpenElement']['description'].$BOTTOM_AREA_INFORMATION;
            $indexs[] = count($result['data']);
        }
        if($key >= 0){
            $openmodelinfo = $this->OpenModel->find('first', array('conditions' => array('OpenModel.open_type'=>$open_type,'OpenModel.open_type_id' => $open_type_id)));
            if (!$this->OpenModel->validateToken($openmodelinfo)) {
                $openType = empty($openmodelinfo['OpenModel']['open_type']) ? '' : $openmodelinfo['OpenModel']['open_type'];
                $appId = $openmodelinfo['OpenModel']['app_id'];
                $appSecret = $openmodelinfo['OpenModel']['app_secret'];
                //无效重新获取
                $accessToken = $this->OpenModel->getAccessToken($appId, $appSecret, $openType);
                $openmodelinfo['OpenModel']['token'] = $accessToken;
                $this->OpenModel->save($openmodelinfo);
            }
            $access_token = $openmodelinfo['OpenModel']['token'];
            foreach($result['data'] as $k => $v){
                $img_dir = substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1);//服务器路径
                $imgurl = str_replace($this->server_host,'',$v);
                $uploadimgUrl = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$access_token; //上传图片
                $imgurl = $img_dir.$imgurl;
                $open_media_data=$this->OpenMedia->find('first',array('conditions'=>array('OpenMedia.open_element_id'=>$element_id,'OpenMedia.open_type'=>$open_type,'OpenMedia.open_type_id'=>$open_type_id,'image_media_url' =>$v)));
                if($open_media_data && $open_media_data['OpenMedia']['url'] !== $v){
                    $data_result = array('url'=>$open_media_data['OpenMedia']['url']);
                }else{
                    $data = array('media' => "@".$imgurl);
                    $data_result = $this->https_request($uploadimgUrl, $data);
                }
                if (isset($data_result['url'])) {
                    $open_media_data['OpenMedia']['url']=$data_result['url'];
                    $this->OpenMedia->saveAll($open_media_data);

                }else{
                    $result = array(
                        'code' => 0,
                        'msg' => '上传内容图片失败。标题为【' . $res[$k] .'】',
                        'data' => isset($data_result['errmsg']) ? $data_result['errmsg'] : '',
                    );
                    break;
                }
            }
        }
        die(json_encode($result));
    }

    //采集外部链接的文章
    public function ajaxGetContents(){
        Configure::write('debug', 1);
        $this->layout="ajax";
        $url = $_POST['url'] ? trim($_POST['url']) : '';
        $result = array(
            'code' => 0,
            'msg' => '无效的URL',
            'data' => array(),
        );
        if(!$url)
            die(json_encode($result));
        $contents = file_get_contents($url);
        if(!$contents)
            die(json_encode($result));
        $patt = '/<div[^>]+id=[\'|"]js_content[\'|"]>([\s\S]+?)<\/p>[\s]+<\/div>/';
        preg_match($patt,$contents,$res);
        $content = str_replace('data-src','src',$res['1']);
        $title_patt = '/<h2[^>]+id=[\'|"]activity-name[\'|"]>([\s\S]+?)<\/h2>/';
        preg_match($title_patt,$contents,$title_res);
        $title = $title_res['1'];
        $thumb_patt = '/var msg_cdn_url[^\"]+[\'|"]([\s\S]+?)[\'|"];/';
        preg_match($thumb_patt,$contents,$thumb_res);
        $thumb_url = isset($thumb_res['1']) ? $thumb_res['1'] : '';
        $thumb = $this->getImage($thumb_url);
        $result = array(
            'code' => '1',
            'msg' => '采集成功',
            'data' => array(
                'content' => $content,
                'title' => trim($title),
                'thumb' => $thumb,
            )
        );
        die(json_encode($result));
    }


    private function getImage($url,$filename='',$type=1){
        if($url==''){return false;}
        if($filename=='')
            $filename=time().'.jpg';
        //文件保存路径
        if($type){
            $ch=curl_init();
            $timeout=5;
            curl_setopt($ch,CURLOPT_URL,$url);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
            $img=curl_exec($ch);
            curl_close($ch);
        }else{
            ob_start();
            readfile($url);
            $img=ob_get_contents();
            ob_end_clean();
        }
        $file_path = '/media/photos/download/' . $filename;
        $fp2=@fopen(substr(WWW_ROOT, 0, strlen(WWW_ROOT) - 1) . $file_path,'a');
        fwrite($fp2,$img);
        fclose($fp2);
        return $file_path;
    }
}