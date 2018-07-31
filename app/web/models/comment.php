<?php

/**
 * 商品评论模型.
 */
class comment extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name Comment 商品类型表
     */
    public $name = 'Comment';

    /*
      评论列表
     */

    /**
     * get_list方法，获得列表并按升序排列.
     *
     * @param $type 输入类型
     * @param $id 输入id
     *
     * @return $Lists 返回列表
     *
     * @todo 修改get_list为get_position_list以及相关调用和其中的findAll
     */
    public function get_list($type, $id = '')
    {
        $Lists = array();
        $conditions = "status ='1'";
        $conditions .= " AND type = '".$type."'";
        if ($id != '') {
            $conditions .= " AND type_id = '".$id."'";
        }

        $Lists = $this->findAll($conditions, '', 'modified asc');

        return $Lists;
    }

    public function find_comments($locale)
    {
        $comments = $this->find('all', array('order' => 'Comment.created',
                    'conditions' => array('Comment.type' => 'P', 'Comment.status' => 1), 'limit' => 5, ),
                        'Comment_home_prodcut_'.$locale);

        return $comments;
    }

    public function find_new_comments($id)
    {
        $new_comments = $this->find('all', array('conditions' => array('Comment.user_id' => $id),
                    'fields' => array('Comment.id', 'Comment.type', 'Comment.rank', 'Comment.type_id', 'Comment.content'),
                    'order' => array('Comment.created DESC'),
                    'limit' => 4, ));

        return $new_comments;
    }

    public function get_my_comments($condition)
    {
        $my_comments = $this->find('all', array('order' => 'Comment.created DESC',
                    //	   'fields' => array('Comment.id','Comment.type','Comment.type_id','Comment.title','Comment.user_id','Comment.parent_id','Comment.status','Comment.created','Comment.content'),
                    'conditions' => $condition, )); //,'limit'=>$rownum,'page'=>$page
        return $my_comments;
    }

    public function get_comments($condition, $rownum, $page)
    {
        $my_comments = $this->find('all', array('fields' => array('Comment.id', 'Comment.type', 'Comment.type_id', 'Comment.title', 'Comment.parent_id', 'Comment.status', 'Comment.created', 'Comment.content'),
                    'conditions' => array($condition),
                    'limit' => $rownum,
                    'page' => $page, ));

        return $my_comments;
    }

    public function get_products_comment($products_comment_conditions)
    {
        $products_comment = $this->find('all', array('conditions' => $products_comment_conditions,
                    'fields' => array('Comment.id', 'Comment.type', 'Comment.type_id', 'Comment.title', 'Comment.parent_id', 'Comment.status', 'Comment.created', 'Comment.content'),
                ));

        return $products_comment;
    }

    public function find_comments_by_num($id, $show_comments_number, $type = 'P')
    {
        $comments = $this->find('threaded', array('conditions' => "Comment.type_id = '$id' and Comment.type = '$type' and Comment.status = '1'", 'recursive' => '1', 'order' => 'Comment.created desc', 'limit' => $show_comments_number));

        return $comments;
    }

    public function find_comment_times($id)
    {
        $comment_times = $this->find('all', array('fields' => 'Comment.rank', 'conditions' => array('Comment.type_id' => $id, 'Comment.status' => 1, 'Comment.type' => 'P')));

        return $comment_times;
    }

    public function find_comments_by_list($products_ids_list, $locale)
    {
        $comments = $this->find('all', array('conditions' => array('Comment.type' => 'P', 'Comment.type_id' => $products_ids_list), 'status' => 1, 'limit' => 5), 'Comment_categories_prodcut_'.$locale);

        return $comments;
    }

    //取商品评论平均值和评论人数
    public function find_comment_rank($ids)
    {
        //$id= array('43','45');
        $comments = $this->find('all', array('conditions' => array('Comment.type' => 'P', 'Comment.type_id' => $ids), 'fields' => array('count(rank) as num', 'sum(rank) as addall', 'Comment.type_id'), 'group' => 'Comment.type_id'));
        //pr($comments);
        $comment_assoc = array();
        foreach ($comments as $k => $v) {
            $comment_assoc[$v['Comment']['type_id']] = array('comment_average' => round($v[0]['addall'] / $v[0]['num']), 'comment_num' => $v[0]['num']);
            //$comment=array($v['Comment'],array('comment_average'=>($v[0]['addall']/$v[0]['num']),'comment_num'=>$v[0]['num']));
        }
        //pr($comment_assoc);
        return $comment_assoc;
    }
    /**
     * get_module_infos方法，获取模块数据.
     *
     * @param  查询参数集合
     *
     * @return $link 根据param，返回数组
     */
    public function get_module_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'orderby';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        $conditions['Comment.type'] = 'A';
        $conditions['Comment.status'] = 1;
        $comment_infos = $this->find('all', array('conditions' => $conditions, 'limit' => $limit, 'order' => 'Comment.'.$order, 'fields' => 'Comment.id,Comment.type_id,Comment.name,Comment.content,Comment.created'));

        return $comment_infos;
    }
    
    function comment_point($controller=null,$comment_type='',$comment_type_id=0){
    		if(isset($_SESSION['User'])&&!empty($_SESSION['User'])){
    			$comment_object="";
    			if(!empty($comment_type_id)){
    				if($comment_type=='P'){
    					$ProductI18nModel = ClassRegistry::init('ProductI18n');
    					$product_list = $ProductI18nModel->find('first', array('conditions' =>array('ProductI18n.product_id'=>$comment_type_id,'ProductI18n.locale'=>LOCALE,'ProductI18n.name <>'=>''), 'fields' => array('ProductI18n.product_id','ProductI18n.name')));
    					if(isset($product_list['ProductI18n'])){
    						$comment_object="<a href='/products/view/".$comment_type_id."'>".$product_list['ProductI18n']['name']."</a>";
    					}else{
    						$comment_object=isset($controller->ld['product'])?$controller->ld['product']:'';
    					}
    				}else if($comment_type=='A'){
    					$ArticleI18nModel = ClassRegistry::init('ArticleI18n');
    					$article_list = $ArticleI18nModel->find('first', array('conditions' =>array('ArticleI18n.article_id'=>$comment_type_id,'ArticleI18n.locale'=>LOCALE,'ArticleI18n.title <>'=>''), 'fields' => array('ArticleI18n.article_id','ArticleI18n.title')));
    					if(isset($article_list['ArticleI18n'])){
    						$comment_object="<a href='/articles/view/".$comment_type_id."'>".$article_list['ArticleI18n']['title']."</a>";
    					}else{
    						$comment_object=isset($controller->ld['article'])?$controller->ld['article']:'';
    					}
    				}else if($comment_type=='T'){
    					$TopicI18nModel = ClassRegistry::init('TopicI18n');
    					$topic_list = $TopicI18nModel->find('first', array('conditions' =>array('TopicI18n.topic_id'=>$comment_type_id,'TopicI18n.locale'=>LOCALE,'TopicI18n.title <>'=>''), 'fields' => array('TopicI18n.topic_id','TopicI18n.title')));
    					if(isset($topic_list['TopicI18n'])){
    						$comment_object="<a href='/topics/view/".$comment_type_id."'>".$topic_list['TopicI18n']['title']."</a>";
    					}else{
    						$comment_object=isset($controller->ld['topic'])?$controller->ld['topic']:'';
    					}
    				}else if($comment_type=='CP'){
    					$CategoryProductI18nModel = ClassRegistry::init('CategoryProductI18n');
    					$product_catgegory_list = $CategoryProductI18nModel->find('first', array('conditions' =>array('CategoryProductI18n.category_id'=>$comment_type_id,'CategoryProductI18n.locale'=>LOCALE,'CategoryProductI18n.name <>'=>''), 'fields' => array('CategoryProductI18n.category_id','CategoryProductI18n.name')));
    					if(isset($product_catgegory_list['CategoryProductI18n'])){
    						$comment_object="<a href='/categories/view/".$comment_type_id."'>".$product_catgegory_list['CategoryProductI18n']['name']."</a>";
    					}else{
    						$comment_object=isset($controller->ld['category_product'])?$controller->ld['category_product']:'';
    					}
    				}else if($comment_type=='SP'){
    					$PageI18nModel = ClassRegistry::init('PageI18n');
    					$page_list = $PageI18nModel->find('first', array('conditions' =>array('PageI18n.page_id'=>$comment_type_id,'PageI18n.locale'=>LOCALE,'PageI18n.title <>'=>''), 'fields' => array('PageI18n.page_id','PageI18n.title')));
    					if(isset($page_list['PageI18n'])){
    						$comment_object="<a href='/pages/view/".$comment_type_id."'>".$page_list['PageI18n']['title']."</a>";
    					}else{
    						$comment_object=isset($controller->ld['page'])?$controller->ld['page']:'';
    					}
    				}else if($comment_type=='AT'){
    					$comment_object="<a href='/activities/index'>".(isset($controller->ld['activity'])?$controller->ld['activity']:'')."</a>";
    				}
    			}
	    		$user_id=$_SESSION['User']['User']['id'];
			$UserModel = ClassRegistry::init('User');
			$user_info=$UserModel->findById($user_id);
			if(!empty($user_info)&&isset($controller->configs['comment_gift_points'])&&intval($controller->configs['comment_gift_points'])>0){
				$UserPointLogModel = ClassRegistry::init('UserPointLog');
				$UserModel->save(array('id'=>$user_id,'point'=>intval($user_info['User']['point'])+intval($controller->configs['comment_gift_points'])));
				$point_log_data = array(
					'id' => 0,
					'user_id' => $user_id,
					'log_type'=>'C',
					'point'=>$user_info['User']['point'],
					'point_change' =>$controller->configs['comment_gift_points'],
					'system_note' => $controller->ld['comment']." ".$comment_object
				);
				$UserPointLogModel->save($point_log_data);
				$UserPointLogModel->point_notify($point_log_data);
			}
		}
    }
}
