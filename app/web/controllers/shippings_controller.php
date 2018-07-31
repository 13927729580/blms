<?php

/*****************************************************************************
 * Seevia 配送方式
 * @copyright 版权所有  上海实玮网络科技有限公司，并保留所有权利。
 * @url 网站地址: http://www.seevia.cn
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 * 不允许对程序代码以任何形式任何目的的再发布。
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/
/**
 *这是一个名为 ShippingsController 的运送方式控制器.
 */
class ShippingsController extends AppController
{
    /*
    *@var $name
    *@var $uses*/
    public $name = 'Shippings';
    public $uses = array('Shipping');

    /**
     *函数 user_index() 用于商品配送.
     */
    public function user_index()
    {
    }
}
