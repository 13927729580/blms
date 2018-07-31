<?php

/*****************************************************************************
 * svcms  ¹ã¸æÌõÄ£ÐÍ
 * ===========================================================================
 * °æÈ¨ËùÓÐ  ÉÏº£ÊµçâÍøÂç¿Æ¼¼ÓÐÏÞ¹«Ë¾£¬²¢±£ÁôËùÓÐÈ¨Àû¡£
 * ÍøÕ¾µØÖ·: http://www.seevia.cn
 * ---------------------------------------------------------------------------
 * Õâ²»ÊÇÒ»¸ö×ÔÓÉÈí¼þ£¡ÄúÖ»ÄÜÔÚ²»ÓÃÓÚÉÌÒµÄ¿µÄµÄÇ°ÌáÏÂ¶Ô³ÌÐò´úÂë½øÐÐÐÞ¸ÄºÍÊ¹ÓÃ£»
 * ²»ÔÊÐí¶Ô³ÌÐò´úÂëÒÔÈÎºÎÐÎÊ½ÈÎºÎÄ¿µÄµÄÔÙ·¢²¼¡£
 * ===========================================================================
 * $¿ª·¢: ÉÏº£Êµçâ$
 * $Id$
*****************************************************************************/
class Invoice extends AppModel
{
    /*
     * @var $useDbConfig Êý¾Ý¿âÅäÖÃ
     */
    public $useDbConfig = 'oms';

    /*
     * @var $name ¹ã¸æÌõ
     */
    public $name = 'Invoice';
    /*
     * @var $hasOne array ÎÄÕÂµÄ¶àÓïÑÔÄ£¿é
     */
    

    public function set_locale($locale)
    {
        $conditions = " PageI18n.locale = '".$locale."'";
        // $this->hasOne['PageI18n']['conditions'] = $conditions;
    }
    //Êý×é½á¹¹µ÷Õû
    public function localeformat($id)
    {
        $lists = $this->find('all', array('conditions' => "Invoice.id = '".$id."'"));
        $lists_formated = array();
        //pr($lists);
        foreach ($lists as $k => $v) {
            $lists_formated['Invoice'] = $v['Invoice'];
            // $lists_formated['PageI18n'][] = $v['PageI18n'];
            // foreach ($lists_formated['PageI18n'] as $key => $val) {
            //     $lists_formated['PageI18n'][$val['locale']] = $val;
            // }
        }
        //pr($lists_formated);
        return $lists_formated;
    }
}