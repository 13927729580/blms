<?php

/*****************************************************************************
 * svcms  文章分类
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
class CategoryArticle extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    /*
     * @var $name ArticleCategory 关于文章扩展分类的模块
     */
    public $name = 'CategoryArticle';

    /*
     * @var $hasOne array 关联文章分类多语言表//注释download会报错！
     */
    public $hasOne = array('CategoryArticleI18n' => array(
                        'className' => 'CategoryArticleI18n',
                        'order' => '',
        		   'conditions' => array('CategoryArticleI18n.locale' => LOCALE),
                        'dependent' => true,
                        'foreignKey' => 'category_id',
                    ),
                  );

    /*
     * @var $actsAs array 关联主键
     */
    public $actsAs = array('Tree');
    /*
     * @var $categories_parent_format array 关联类别格式
     */
    public $categories_parent_format = array();
    /*
     * @var $cat_navigate_format array 关联游览格式
     */
    public $cat_navigate_format = array();
    /*
     * @var $all_subcat array 关联所有的subcat
     */
    public $all_subcat = array();
    /*
     * @var $allinfo array 关联所有输入信息
     */
    public $allinfo = array();

    //直接子分类
    public $direct_subcat = array();

    /**
     * set_locale方法，设置语言环境.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        $conditions = '';
        $conditions = " CategoryArticleI18n.locale = '".$locale."'";
        $this->hasOne['CategoryArticleI18n']['conditions'] = $conditions;
    }

    public function get_cat($id)
    {
        if (empty($id)) {
            return false;
        }
        $foo = array();
        $foo_cat = $this->find('first', array('conditions' => array('CategoryArticle.id' => $id), 'fields' => array('CategoryArticle.parent_id', 'CategoryArticle.id')), false);
        if (!empty($foo_cat)) {
            $foo[] = $foo_cat['CategoryArticle']['id'];
            while ($foo_cat['CategoryArticle']['parent_id'] != '0') {
                if (!isset($foo_cat['CategoryArticle']['parent_id'])) {
                    break;
                }
                $foo_cat = $this->find('first', array('conditions' => array('CategoryArticle.id' => $foo_cat['CategoryArticle']['parent_id']), 'fields' => array('CategoryArticle.parent_id', 'CategoryArticle.id')), false);
                $foo[] = $foo_cat['CategoryArticle']['id'];
            }
            $foo = array_reverse($foo);
        }

        return $foo;
    }

    /**
     * tree方法，主键.
     *
     * @param $locale 输入语言
     * @param $db 输入数据库
     *
     * @return $this->allinfo[$type] 返回所有的输入值，如果不存在则从数据库中取出相对应的数据
     */
    public function tree($type = 'A', $category_id = 0, $locale = 'chi', $limit = '')
    {
        $this->categories_parent_format = array();
        $this->cat_navigate_format = array();
        $this->all_subcat = array();
        $this->direct_subcat = array();
        $this->allinfo[$type] = array();
        $lists = $this->find('all', array('cache' => $this->short, 'order' => 'CategoryArticle.orderby asc,CategoryArticle.created asc',
                    'fields' => array('CategoryArticle.id', 'CategoryArticle.tree_show_type', 'CategoryArticle.parent_id', 'CategoryArticle.type', 'CategoryArticle.img01', 'CategoryArticle.img02', 'CategoryArticleI18n.name', 'CategoryArticleI18n.meta_description', 'CategoryArticle.link', 'CategoryArticle.code', 'CategoryArticle.template', 'CategoryArticle.layout', 'CategoryArticle.created', 'CategoryArticle.modified', 'CategoryArticle.new_show', 'CategoryArticle.home_show',
                    ),
                    'limit' => $limit,
                    'conditions' => array("status ='1' AND type='".$type."' AND locale='".$locale."' "),
                ));
        $lists2 = $this->find('list', array('cache' => $this->short, 'order' => 'home_cat_orderby asc',
                    'fields' => array('CategoryArticle.id'),
                    'limit' => $limit,
                    'conditions' => array("status ='1' AND type='".$type."' "),
                ));
        $week_ago = date('Y-m-d H:00:00', strtotime('-1 week'));
        $lists_formated = array();
        //全部的分类
        if (is_array($lists)) {
            foreach ($lists as $k => $v) {
                $this->allinfo[$type]['all_ids'][] = $v['CategoryArticle']['id'];
                if ($v['CategoryArticle']['created'] >= $week_ago && $v['CategoryArticle']['new_show'] == '1') {
                    $v['CategoryArticle']['is_new'] = 1;
                }
                $lists_formated[$v['CategoryArticle']['id']] = $v;
            }

            //格式化为ID为序
            $this->allinfo[$type]['assoc'] = $lists_formated;
            $all_ids = array();
            foreach ($lists as $k => $v) {
                $all_ids[] = $v['CategoryArticle']['id'];
                if ($v['CategoryArticle']['created'] >= $week_ago && $v['CategoryArticle']['new_show'] == '1') {
                    $v['CategoryArticle']['is_new'] = 1;
                }
                $this->categories_parent_format[$v['CategoryArticle']['parent_id']][] = $v;
            }
            //格式化为以parent_id为序
            $this->allinfo[$type]['tree'] = $this->subcat_get(0);
            $this->allinfo[$type]['subids'] = $this->all_subcat;
            $this->allinfo[$type]['all_catids'] = $all_ids;
            $this->allinfo[$type]['home_all_ids'] = $lists2;
            $this->allinfo[$type]['direct_subids'] = $this->direct_subcat;
            $this->categories_parent_format = array();

            return $this->allinfo[$type];
        }
    }

    /**
     * subcat_get方法，获得subcat.
     *
     * @param $category_id 输入id
     *
     * @return $subcat 根据id检索相对应的数据并返回
     */
    public function subcat_get($category_id)
    {
        $subcat = array();
        if (isset($this->categories_parent_format[$category_id]) && is_array($this->categories_parent_format[$category_id])) { //判断parent_id = 0 的数据
            foreach ($this->categories_parent_format[$category_id] as $k => $v) {
                $category = $v; //parent_id 为 0 的数据
                if (isset($this->categories_parent_format[$v['CategoryArticle']['id']]) && is_array($this->categories_parent_format[$v['CategoryArticle']['id']])) {
                    $category['SubCategory'] = $this->subcat_get($v['CategoryArticle']['id']);
                }

                $subcat[$v['CategoryArticle']['id']] = $category;
                //	pr($subcat); //parent_id 为 0 的数据

                $this->all_subcat[$v['CategoryArticle']['id']][] = $v['CategoryArticle']['id'];
                $this->direct_subcat[$v['CategoryArticle']['parent_id']][] = $v['CategoryArticle']['id'];
                if (isset($this->all_subcat[$v['CategoryArticle']['parent_id']])) {
                    if ($v['CategoryArticle']['parent_id'] > 0) {
                        $this->all_subcat[$v['CategoryArticle']['parent_id']] = array_merge($this->all_subcat[$v['CategoryArticle']['parent_id']], $this->all_subcat[$v['CategoryArticle']['id']]);
                    } else {
                        $this->all_subcat[$v['CategoryArticle']['parent_id']][] = $v['CategoryArticle']['id'];
                    }
                } else {
                    $this->all_subcat[$v['CategoryArticle']['parent_id']] = $this->all_subcat[$v['CategoryArticle']['id']];
                }

                //	pr($this->all_subcat);  ??
            }
        }

        return $subcat;
    }

    /*
    * 函数get_module_category_list 获取文章分类列表
    * @params 查询参数
    * @return category_list 返回文章分类
    */
    public function get_article_category_list($params)
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
        if (isset($params['locale'])) {
            $conditions['CategoryArticleI18n.locale'] = $params['locale'];
        }
        $conditions['CategoryArticle.status'] = '1';

        $category = $this->find('first', array('conditions' => array('CategoryArticle.id' => $params['id'])));
        if (!empty($category) && $category['CategoryArticle']['tree_show_type'] == 2) {
	            $conditions['OR']['CategoryArticle.id'] = $category['CategoryArticle']['id'];
	            $conditions['OR']['CategoryArticle.parent_id'] = $category['CategoryArticle']['id'];
        }
        $category_list = $this->find('all', array('fields' => array('CategoryArticle.id', 'CategoryArticle.tree_show_type', 'CategoryArticle.img01', 'CategoryArticleI18n.name', 'CategoryArticle.parent_id'), 'conditions' => $conditions, 'order' => 'CategoryArticle.'.$order));
	$sub_category_ids=array();
	$SubCategory = array();
        foreach ($category_list as $k => $v) {
            if ($v['CategoryArticle']['parent_id'] != 0 && $v['CategoryArticle']['tree_show_type'] != 1) {
                $SubCategory[$v['CategoryArticle']['id']] = $v;
                unset($category_list[$k]);
            }
            if (!empty($category) && $category['CategoryArticle']['id'] == $v['CategoryArticle']['parent_id']) {
                	$sub_category_ids[]=$v['CategoryArticle']['id'];
                	$category['SubCategory'][]=$v;
            }
        }
        foreach ($category_list as $ck => $cv) {
            $category_list[$ck]['SubCategory'] = array();
            foreach ($SubCategory as $sk => $sv) {
                if ($sv['CategoryArticle']['parent_id'] == $cv['CategoryArticle']['id']) {
                    array_push($category_list[$ck]['SubCategory'], $sv);
                }
            }
        }
        $category_list['category'] = $category;
        $category_list['top_categroy_id'] = $this->get_top_category_id($params['id']);
        $category_list['article_categories_tree'] = $this->allinfo['A']['tree'];
        $category_list['direct_subids'] = $this->allinfo['A']['direct_subids'];
        $category_list['assoc'] = $this->allinfo['A']['assoc'];
	if (!empty($category) && $category['CategoryArticle']['parent_id'] == 0&&!empty($sub_category_ids)) {
		$category_article_ids=array();
		$conditions=array();
		$conditions['Article.status']='1';
		$conditions['or']['Article.category_id'] = $sub_category_ids;
		$ArticleCategory = ClassRegistry::init('ArticleCategory');
		$articles_ids=$ArticleCategory->find('list',array("fields"=>"ArticleCategory.article_id,ArticleCategory.category_id",'conditions'=>array("ArticleCategory.category_id"=>$sub_category_ids,"ArticleCategory.article_id >"=>0)));
		if(!empty($articles_ids)){
			$conditions['or']['Article.id'] = array_keys($articles_ids);
		}
		$Article = ClassRegistry::init('Article');
		$sub_category_article_infos=$Article->find('all',array('fields'=>"Article.id,Article.category_id,Article.type,ArticleI18n.title,ArticleI18n.subtitle,ArticleI18n.img01,Article.created,Article.modified,ArticleI18n.meta_description",'conditions'=>$conditions,'order'=>"Article.orderby,Article.created desc"));
		$sub_category_articles=array();
		foreach($sub_category_article_infos as $v){
			$sub_category_articles[$v['Article']['category_id']][$v['Article']['id']]=$v;
			if(isset($articles_ids[$v['Article']['id']])){
				$sub_category_articles[$articles_ids[$v['Article']['id']]][$v['Article']['id']]=$v;
			}
		}
		$category_list['sub_category_articles'] = $sub_category_articles;
	}
        return $category_list;
    }
    /*
    * 函数get_articlecategory_name_by_id 获取文章分类名称
    * @category_id 查询参数
    * @category_id 查询参数
    * @return category_list 返回文章分类名称
    */
    public function get_articlecategory_name_by_id($category_id, $c_locale)
    {
        $name = $this->find('first', array('conditions' => array('category_id' => $category_id, 'locale' => $c_locale), 'fields' => 'CategoryArticleI18n.name'));

        return $name['CategoryArticleI18n']['name'];
    }
    /*
    * 函数get_articlecategory_name_by_id 获取文章分类描述
    * @category_id 查询参数
    * @category_id 查询参数
    * @return category_list 返回文章分类名称
    */
    public function get_articlecategory_detail_by_id($category_id, $c_locale)
    {
        $detail = $this->find('first', array('conditions' => array('category_id' => $category_id, 'locale' => $c_locale), 'fields' => 'CategoryArticleI18n.detail'));

        return $detail['CategoryArticleI18n']['detail'];
    }
    public function get_top_category_id($category_id)
    {
        foreach ($this->allinfo['A']['direct_subids'][0] as $k => $v) {
            if (in_array($category_id, $this->allinfo['A']['subids'][$v])) {
                return $v;
            }
        }

        return $category_id;
    }
    
    public function category_article_tree($params){
    		$category_tree=array();
    		$category_articles=array();
    		$category_conditions=array(
    			'CategoryArticle.status'=>'1',
    			'CategoryArticle.type'=>'A',
    			'CategoryArticleI18n.locale'=>LOCALE
    		);
    		$category_lists = $this->find('all', array('cache' => $this->short, 'order' => 'CategoryArticle.parent_id,CategoryArticleI18n.name',
                    'fields' => array('CategoryArticle.id', 'CategoryArticle.tree_show_type', 'CategoryArticle.parent_id', 'CategoryArticle.type', 'CategoryArticle.img01', 'CategoryArticle.img02', 'CategoryArticleI18n.name', 'CategoryArticleI18n.meta_description', 'CategoryArticle.link', 'CategoryArticle.code', 'CategoryArticle.template', 'CategoryArticle.layout', 'CategoryArticle.created', 'CategoryArticle.modified', 'CategoryArticle.new_show', 'CategoryArticle.home_show',
                    ),
                    'conditions' => $category_conditions,
             ));
            if(!empty($category_lists)){
            	$top_category_tree=array();
            	$sub_category_ids=array();
            	$category_ids=array();
            	foreach($category_lists as $v){
            		$category_ids[]=$v['CategoryArticle']['id'];
            		$sub_category_ids[$v['CategoryArticle']['parent_id']][]=$v['CategoryArticle']['id'];
            		if($v['CategoryArticle']['parent_id']==0){
            			$top_category_tree[]=$v;
            		}
            	}
            	$ArticleCategory = ClassRegistry::init('ArticleCategory');
            	$relation_article_ids = $ArticleCategory->find('list', array('conditions' => array('ArticleCategory.category_id' => $category_ids), 'fields' => 'article_id'));
            	
            	$Article = ClassRegistry::init('Article');
            	$conditions=array();
            	$conditions['and']['Article.status']='1';
            	$conditions['and']['ArticleI18n.locale']=LOCALE;
			$conditions['and']['or']['Article.category_id']=$category_ids;
			if(!empty($relation_article_ids)){
				$conditions['and']['or']['Article.id']=$relation_article_ids;
			}
			$article_infos=$Article->find('all',array('conditions'=>$conditions,'fields' => 'Article.id,Article.category_id,Article.clicked,Article.created,ArticleI18n.img01,ArticleI18n.img02,ArticleI18n.title,ArticleI18n.subtitle,ArticleI18n.content,ArticleI18n.meta_description','order'=>'Article.category_id,ArticleI18n.title'));
			$article_ids=array();
			$article_list=array();
			foreach($article_infos as $v){
				$article_list[$v['Article']['category_id']][]=$v;
				$article_ids[]=$v['Article']['id'];
			}
			App::import('model','UserLike');
	            if (class_exists('UserLike')) {
				$UserLike = new UserLike(); 
	            $article_like_info=$UserLike->find('all',array('conditions'=>array('UserLike.type'=>'A','UserLike.type_id'=>$article_ids,'UserLike.action'=>'like'),'fields'=>array("UserLike.type_id","count(UserLike.id) as like_num"),"group"=>'type_id'));
	            	$article_like_data=array();
	            	foreach($article_like_info as $v){
	            		$category_tree[$v['UserLike']['type_id']]=$v[0]['like_num'];
	            	}
	            	$article_data['article_like'] = $article_like_data;
	            	if(isset($_SESSION['User']['User'])){
	            		$user_id=$_SESSION['User']['User']['id'];
	            		$article_user_like=$UserLike->find('list',array('conditions'=>array('UserLike.type'=>'A','UserLike.type_id'=>$article_ids,'UserLike.user_id'=>$user_id,'UserLike.action'=>'like'),'fields'=>array("UserLike.type_id","UserLike.id")));
	            		$category_tree['article_user_like'] = $article_user_like;
	            	}
	            }
			foreach($top_category_tree as $k=>$v){
				$category_id=$v['CategoryArticle']['id'];
				$category_articles=isset($article_list[$category_id])?$article_list[$category_id]:array();
				$child_category=isset($sub_category_ids[$category_id])?$sub_category_ids[$category_id]:array();
				foreach($child_category as $child_category_id){
					$child_articles=isset($article_list[$child_category_id])?$article_list[$child_category_id]:array();
					$sub_child_category=isset($sub_category_ids[$child_category_id])?$sub_category_ids[$child_category_id]:array();
					foreach($sub_child_category as $sub_child_category_id){
						$sub_child_articles=isset($article_list[$sub_child_category_id])?$article_list[$sub_child_category_id]:array();
						if(!empty($sub_child_articles))$child_articles=array_merge($child_articles,$sub_child_articles);
					}
					if(!empty($child_articles))$category_articles=array_merge($category_articles,$child_articles);
				}
				$top_category_tree[$k]['Articles']=$category_articles;
            	}
            	$category_tree['category']=$top_category_tree;
            }
            return $category_tree;
    }
}
