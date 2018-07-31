<?php

/*****************************************************************************
 * svsys  通知模板渠道模型
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
class NotifyTemplateType extends AppModel{
    /*
    * @var $useDbConfig 数据库配置
    */
    public $useDbConfig = 'default';
    public $name = 'NotifyTemplateType';
    
    public $hasOne = array('NotifyTemplateTypeI18n' => array('className' => 'NotifyTemplateTypeI18n',
                              'conditions' => '',
                              'order' => '',
                              'dependent' => true,
                              'foreignKey' => 'notify_template_type_id',
                        ),
                  );

    public function set_locale($locale)
    {
        if (empty($locale)) {
            $locale = 'chi';
        }
        $conditions = " NotifyTemplateTypeI18n.locale = '".$locale."'";
        $this->hasOne['NotifyTemplateTypeI18n']['conditions'] = $conditions;
    }

    //数组结构调整
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => array('NotifyTemplateType.id' => $id)));
        $lists_formated = array();
        foreach ($lists as $k => $v) {
            $lists_formated['NotifyTemplateType'] = $v['NotifyTemplateType'];
            $lists_formated['NotifyTemplateTypeI18n'][] = $v['NotifyTemplateTypeI18n'];
            foreach ($lists_formated['NotifyTemplateTypeI18n'] as $key => $val) {
                $lists_formated['NotifyTemplateTypeI18n'][$val['locale']] = $val;
            }
        }
        return $lists_formated;
    }
    
    function typeformat($notify_template_code,$type=""){
    		$NotifyTemplateType_data=array();
    		$conditions=array(
    			"NotifyTemplateType.notify_template_code"=>$notify_template_code,
    			"status"=>'1'
    		);
    		if(is_string($type)&&trim($type)!=""){
    			$conditions['NotifyTemplateType.type']=trim($type);
    		}else if(is_array($type)&&!empty($type)){
    			$conditions['NotifyTemplateType.type']=$type;
    		}
    		$NotifyTemplateType_info=$this->find('all',array('conditions'=>$conditions,"order"=>"NotifyTemplateType.type"));
    		if(!empty($NotifyTemplateType_info)){
	    		foreach($NotifyTemplateType_info as $v){
	    			$NotifyTemplateType_data[$v['NotifyTemplateType']['type']]=$v;
	    		}
    		}
    		return $NotifyTemplateType_data;
    }
    
    
    function wechatparamsformat($notify_template=array()){
    		$wechat_params=array();
    		if(isset($notify_template['NotifyTemplateTypeI18n'])){
    			$send_content=split(chr(13).chr(10),$notify_template['NotifyTemplateTypeI18n']['param04']);
    			foreach($send_content as $v){
    				if(trim($v)==""||!strpos($v,'='))continue;
				$send_content_arr=explode("=",trim($v));
				$send_content_key=$send_content_arr[0];
				$send_content_key_arr=explode('.',$send_content_key);
				$send_content_key=trim($send_content_key_arr[0]);
				$send_content_value=explode("$",isset($send_content_arr[1])?trim($send_content_arr[1]):'');
				if(isset($send_content_value[1])&&$send_content_value[1]!=''){
					$wechat_params[$send_content_key]=$send_content_value[1];
				}
    			}
    		}
    		return $wechat_params;
    }
}
