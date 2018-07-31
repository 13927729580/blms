<?php

/**
 * 公众平台模型.
 */
class OpenModel extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'sns';
    /*
     * @var $name OpenModel
     */
    public $name = 'OpenModel';

    public function getInfoByOpenType($openType)
    {
        return $this->find('first', array('conditions' => array('OpenModel.status' => 1, 'OpenModel.verify_status' => 1, 'open_type' => $openType)));
    }

    public function getInfoById($id)
    {
        return $this->find('first', array('conditions' => array('OpenModel.status' => 1, 'OpenModel.verify_status' => 1, 'id' => $id)));
    }

    public function getInfoByTypeId($openTypeId)
    {
        return $this->find('first', array('conditions' => array('OpenModel.status' => 1, 'OpenModel.verify_status' => 1, 'open_type_id' => $openTypeId)));
    }
    
    public function validateToken($openModel)
    {
        if (empty($openModel['OpenModel']['token']) || (time() - strtotime($openModel['OpenModel']['modified'])) > 7200) {
            return false;
        }
        return true;
    }
    
    public function getAccessToken($appId, $appSecret, $openType = 'wechat')
    {
        if ($openType == 'wechat') {
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$appId.'&secret='.$appSecret;
        }
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        if (isset($result['access_token'])) {
            return $result['access_token'];
        }

        return false;
    }
}
