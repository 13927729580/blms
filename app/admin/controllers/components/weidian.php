<?php
/**
 * Soap component for handling soap requests in Cake.
 *
 * @author      Hobbysh (hobbysh@gmail.com)
 * @copyright   Copyright 2009, �Ϻ�ʵ������Ƽ����޹�˾
 * @license     http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class WeidianComponent extends Component
{
    public $name = 'Weidian';
    public $components = array('RequestHandler');

    /**
     * @var �̼�appkey
     */
    public $appKey;

    /**
     * @var �̼�appsecret
     */
    public $appSecret;

    /**
     * @var �̼�Token
     */
    public $appToken;

    /*
        ���ýӿ�
    */
    public function https_request($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            $data = $this->to_josn($data);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
        $output = curl_exec($curl);
        curl_close($curl);
        return json_decode($output, true);
    }

    /*
        $data  ��Ҫת��josn�ύ������
    */
    public function to_josn($data)
    {
        $this->arrayRecursive($data, 'urlencode');
        $json = json_encode($data);

        return urldecode($json);
    }

    /**************************************************************
     * ������������Ԫ��������,��������
     * @param string &$array Ҫ���������
     * @param string $function Ҫִ�еĺ���
     * @return boolean $apply_to_keys_also �Ƿ�ҲӦ�õ�key��
     * @access public
     *
     *************************************************************/
    public function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
    {
        static $recursive_counter = 0;
        if (++$recursive_counter > 1000) {
            die('possible deep recursion attack');
        }
        if(is_array($array)){
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
        }
        --$recursive_counter;
    }

    /*
        ��Ʒ����
    */
    function api_get_product_all($shop,$data=null){
        $result=array();
        try{
            $access_token=$shop['app_token'];
            $api_url='http://api.vdian.com/api?param='.$data.'&public={"method":"vdian.item.list.get","access_token":"'.$access_token.'","version":"1.0","format":"json"}';
            $result=$this->https_request($api_url,$data);
        }catch (Exception $e){
            $result['errcode']='-1';
            $result['errmsg']=$e->getMessage();
        }
        return $result;
    }

    /*
        �����б�����
    */
    function api_get_order_all($shop,$data=null){
        $result=array();
        try{
            $access_token=$shop['app_token'];
            $api_url='http://api.vdian.com/api?param='.$data.'&public={"method":"vdian.order.list.get","access_token":"'.$access_token.'","version":"1.1","format":"json"}';
            $result=$this->https_request($api_url);
        }catch (Exception $e){
            $result['errcode']='-1';
            $result['errmsg']=$e->getMessage();
        }
        return $result;
    }

    /*
        ������������
    */
    function api_get_order_info($shop,$data=null){
        $result=array();
        try{
            $access_token=$shop['app_token'];
            $api_url='http://api.vdian.com/api?param='.$data.'&public={"method":"vdian.order.get","access_token":"'.$access_token.'","version":"1.0","format":"json"}';
            $result=$this->https_request($api_url);
        }catch (Exception $e){
            $result['errcode']='-1';
            $result['errmsg']=$e->getMessage();
        }
        return $result;
    }

    /*
        ��������
    */
    function delivery_send($shop,$data=null){
        $result=array();
        try{
            $access_token=$shop['app_token'];
            $api_url='http://api.vdian.com/api?param='.$data.'&public={"method":"vdian.order.deliver","access_token":"'.$access_token.'","version":"1.0","format":"json"}';
            $result=$this->https_request($api_url,$data);
        }catch (Exception $e){
            $result['errcode']='-1';
            $result['errmsg']=$e->getMessage();
        }
        return $result;
    }

    /*
        ������Ʒ���
    */
    function stock_update($shop,$data=null){
        $result=array();
        try{
            $access_token=$shop['app_token'];
            $api_url='http://api.vdian.com/api?public={"method":"vdian.item.update","access_token":"'.$access_token.'","version":"1.0","format":"json"}&param='.$data;
            $result=$this->https_request($api_url,$data);
        }catch (Exception $e){
            $result['errcode']='-1';
            $result['errmsg']=$e->getMessage();
        }
        return $result;
    }

    /*
        ��Ʒ�¼�
    */
    function product_soldout($shop,$data=null){
        $result=array();
        try{
            $access_token=$shop['app_token'];
            $api_url='http://api.vdian.com/api?public={"method":"weidian.item.onSale","access_token":"'.$access_token.'","version":"1.0","format":"json"}&param='.$data;
            $result=$this->https_request($api_url,$data);
        }catch (Exception $e){
            $result['errcode']='-1';
            $result['errmsg']=$e->getMessage();
        }
        return $result;
    }
}
