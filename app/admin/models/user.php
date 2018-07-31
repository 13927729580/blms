<?php

/*****************************************************************************
 * svoms  用户模型
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
class user extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name User 用户
     */
    public $name = 'User';

    /**
     * user_name_array方法，获取用户名称数据以user_id为键值.
     *
     * @param array $user_id_array 用户ID数组
     *
     * @return array $sr_parent_data 返回用户名称数据以user_id为键值
     */
    public function user_name_array($user_id_array = array())
    {
        $condition = '';
        if (!empty($user_id_array)) {
            $condition['id'] = $user_id_array;
        }
        $fields = array('id','name');
        $user_data = $this->find('all', array('conditions' => $condition, 'fields' => $fields));
        $user_data_format = array();
        foreach ($user_data as $k => $v) {
            $user_data_format[$v['User']['id']] = $v['User']['name'];
        }

        return $user_data;
    }
    /**
     * update_user_balance方法，更新用户余额.
     *
     * @param int   $user_id 用户ID数组
     * @param float $amount  金额
     */
    public function update_user_balance($user_id, $amount)
    {
        $user_info = $this->find('first', array('conditions' => array('User.id' => $user_id)));
        $user_money = $user_info['User']['balance'] + $amount;
        $update_info = array(
            'id' => $user_id,
            'balance' => $user_money,
        );
        $this->User->save(array('User' => $update_info));
    }

    /**
     *检测用昵称是否存在.
     */
    public function check_user_name_exist($name)
    {
        $user = $this->find('first', array('conditions' => array('User.name' => $name)));

        return empty($user) ? false : true;
    }
    /**
     *检测用户邮箱是否存在.
     */
    public function check_user_email_exist($email)
    {
        $user = $this->find('first', array('conditions' => array('User.email' => $email)));

        return empty($user) ? false : true;
    }
    /**
     *检测用户手机是否存在.
     */
    public function check_user_mobile_exist($mobile)
    {
        $user = $this->find('first', array('conditions' => array('User.mobile' => $mobile)));

        return empty($user) ? false : true;
    }
    
    function user_remove($user_ids=0){
    		if (constant('Product') == 'AllInOne') {
    			$user_orders = ClassRegistry::init('Order')->find('all', array('fields'=>'Order.user_id','conditions' => array('Order.user_id' => $user_ids),'group'=>'Order.user_id having count(*)>0'));
    			if(!empty($user_orders)&&is_array($user_ids)){
    				foreach($user_orders as $v){
    					$user_id_key=array_search($v['Order']['user_id'],$user_ids);
    					if(isset($user_ids[$user_id_key]))unset($user_ids[$user_id_key]);
    				}
    				if(empty($user_ids))return false;
    			}else if(!empty($user_orders)&&is_numeric($user_ids)){
    				return false;
    			}
    		}
    		$user_list = $this->find('all', array('fields' => array('User.id', 'User.img01', 'User.img02', 'User.img03'), 'conditions' => array('User.id' => $user_ids)));
    		if(!empty($user_list)){
    			foreach($user_list as $v){
    				$user_id=$v['User']['id'];
    				ClassRegistry::init('UserAddress')->deleteAll(array('UserAddress.user_id' => $user_id));//删除用户地址
                		ClassRegistry::init('UserLike')->deleteAll(array('UserLike.user_id' => $user_id));//删除用户行为
    				ClassRegistry::init('UserFan')->deleteAll(array(
    					'or'=>array(
    						'UserFan.user_id'=>$user_id,
    						'UserFan.fan_id'=>$user_id
    					)
    				));//删除用户粉丝
				ClassRegistry::init('UserMessage')->deleteAll(array('UserMessage.user_id' => $user_id));//删除用户留言
				ClassRegistry::init('UserVisitors')->deleteAll(array('UserVisitors.user_id' => $user_id));//删除用户访问
				ClassRegistry::init('Blog')->deleteAll(array('Blog.user_id' => $user_id));//删除用户日志
				ClassRegistry::init('Comment')->deleteAll(array('Comment.user_id' => $user_id));//删除用户评论
				ClassRegistry::init('UserAction')->deleteAll(array('UserAction.user_id' => $user_id));//删除用户动作
				ClassRegistry::init('SynchroUser')->deleteAll(array('SynchroUser.user_id' => $user_id));//删除用户授权
				ClassRegistry::init('UserPointLog')->deleteAll(array('UserPointLog.user_id' => $user_id));//删除会员积分日志
				//课程记录
				$UserCourseClass=ClassRegistry::init('UserCourseClass');
				$user_course_ids=$UserCourseClass->find('list',array('conditions'=>array('UserCourseClass.user_id'=>$user_id)));
				if(!empty($user_course_ids)){
					ClassRegistry::init('UserCourseClassDetail')->deleteAll(array('UserCourseClassDetail.user_course_class_id'=>$user_course_ids));
					$UserCourseClass->deleteAll(array('UserCourseClass.user_id'=>$user_id));
				}
				//评测记录
				$UserEvaluationLog=ClassRegistry::init('UserEvaluationLog');
				$user_evaluation_ids=$UserEvaluationLog->find('list',array('conditions'=>array('UserEvaluationLog.user_id'=>$user_id)));
				if(!empty($user_evaluation_ids)){
					ClassRegistry::init('UserEvaluationLogDetail')->deleteAll(array('UserCourseClassDetail.user_evaluation_log_id'=>$user_evaluation_ids));
					$UserEvaluationLog->deleteAll(array('UserEvaluationLog.user_id'=>$user_id));
				}
				//活动记录
				$ActivityUser=ClassRegistry::init('ActivityUser');
				$user_activity_ids=$ActivityUser->find('list',array('conditions'=>array('ActivityUser.user_id'=>$user_id)));
				if(!empty($user_activity_ids)){
					ClassRegistry::init('ActivityUserConfig')->deleteAll(array('ActivityUserConfig.activity_user_id'=>$user_activity_ids));
					$ActivityUser->deleteAll(array('ActivityUser.user_id'=>$user_id));
				}
				//ClassRegistry::init('ActivityUserTag')->deleteAll(array('ActivityUserTag.user_id'=>$user_id));
				
				ClassRegistry::init('UserEducation')->deleteAll(array('UserEducation.user_id' => $user_id));//删除教育经历
				ClassRegistry::init('UserWork')->deleteAll(array('UserWork.user_id' => $user_id));//删除作品
				
				if (trim($v['User']['img01']) != '') {
					$img_file1 = $user_img_root.$v['User']['img01'];
					if (file_exists($img_file1)&&is_file($img_file1))@unlink($img_file1);
				}
				if (trim($v['User']['img02']) != '') {
					$img_file2 = $user_img_root.$v['User']['img02'];
					if (file_exists($img_file2)&&is_file($img_file2))@unlink($img_file2);
				}
				if (trim($v['User']['img03']) != '') {
					$img_file3 = $user_img_root.$v['User']['img03'];
					if (file_exists($img_file3)&&is_file($img_file3))@unlink($img_file3);
				}
				$this->deleteAll(array('id'=>$user_id));
    			}
    		}
    		return true;
    }
}
