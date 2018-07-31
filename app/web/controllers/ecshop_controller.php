<?php

/*****************************************************************************
 * Seevia 询价
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为EcshopController的控制器
 *控制联系方式.
 *
 *@var
 *@var
 *@var
 *@var
 */
class EcshopsController extends Controller
{
    public $name = 'Ecshops';
    public $helpers = array('Html');
    public $uses = array('EcshopOrder');
    public $components = array('RequestHandler');

    public function index($order_id)
    {
        pr($this->EcshopOrder->find('first'));
    }
}
