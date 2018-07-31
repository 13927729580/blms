<?php

/**
 * 文章分类模型.
 */
class ArticleCategory extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'cms';
    /*
     * @var $name ArticleCategory 文章分类表
     */
    public $name = 'ArticleCategory';

    /**
     * findcountassoc方法，取得id=>count.
     *
     * @return $lists_formated 返回格式列表
     */
    public function findcountassoc()
    {
        $lists = $this->find('all', array('fields' => array('id', 'count(*) as count'), 'group' => 'id'));
        $lists_formated = array();
        if (is_array($lists)) {
            foreach ($lists as $k => $v) {
                $lists_formated[$v['ArticleCategory']['id']] = $v['0']['count'];
            }
        }

        return $lists_formated;
    }

    //扩展分类
    /**
     * handle_other_cat方式，扩展分类.
     *
     * @param $article_id 输入文章id
     * @param $cat_list 输入列表
     *
     * @return boolen 返回是否正确
     */
    public function handle_other_cat($article_id, $cat_list)
    {
        //查询现有的扩展分类
        $res = $this->findAll('ArticleCategory.article_id = '.$article_id.'');
        $exist_list = array();
        foreach ($res as $k => $v) {
            $exist_list[$k] = $v['ArticleCategory']['category_id'];
        }
        //删除不再有的分类
        $delete_list = array_diff($exist_list, $cat_list);
        if ($delete_list) {
            $condition = array('ArticleCategory.category_id' => $delete_list, 'ArticleCategory.article_id = '.$article_id.'');
            $this->deleteAll($condition);
        }
        //添加新加的分类
        $add_list = array_diff($cat_list, $exist_list, array(0));
        foreach ($add_list as $k => $cat_id) {
            $other_cat_info = array(
                'product_id' => $product_id,
                'category_id' => $add_list[$k],
            );
            $this->saveAll(array('ArticleCategory' => $other_cat_info));
        }

        return true;
    }

    /**
     * find_indx_all方法，查找所有.
     *
     * @param $category_id 输入类别id
     * @param $locale 输入语言
     *
     * @return article_categorys 返回文章类别
     */
    public function find_indx_all($category_id, $locale)
    {
        $params = array(
            'order' => array('ArticleCategory.modified DESC'),
            'conditions' => array(' ArticleCategory.category_id in ('.$category_id.')'),
        );
        $article_categorys = $this->find('all', $params, $this->name.$locale);

        return $article_categorys;

        //"all",array( "conditions" =>array(" ArticleCategory.category_id in (".$category_id.")"))
    }
    /**
     * 函数get_module_infos方法，获取分类文章列表数据.
     *
     * @param  查询参数集合
     *
     * @return $category_article 根据param，返回分类文章列表数组
     */
    public function get_module_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $page = 1;
        if (isset($params['page'])) {
            $page = $params['page'];
        }
        $c_locale = 'chi';
        if (isset($params['locale'])) {
            $c_locale = $params['locale'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['id'])) {
			$category_ids=array();
			$CategoryArticle = ClassRegistry::init('CategoryArticle');
			$category_ids=$CategoryArticle->find('list',array("fields"=>"CategoryArticle.id",'conditions'=>array("CategoryArticle.parent_id"=>$params['id'],"CategoryArticle.status"=>'1')));
			$category_ids[]=$params['id'];
			$conditions['or']['Article.category_id'] = $category_ids;
			
			$ArticleCategory = ClassRegistry::init('ArticleCategory');
			$articles_ids=$ArticleCategory->find('list',array("fields"=>"ArticleCategory.article_id",'conditions'=>array("ArticleCategory.category_id"=>$category_ids,"ArticleCategory.article_id >"=>0)));
			if(!empty($articles_ids)){
				$conditions['or']['Article.id'] = $articles_ids;
			}
        }
        if(isset($params['article_keywords'])&&trim($params['article_keywords'])!=""){
    		$article_keywords=trim($params['article_keywords']);
    		$conditions['or']['ArticleI18n.title like'] = "%{$article_keywords}%";
    		$conditions['or']['ArticleI18n.subtitle like'] = "%{$article_keywords}%";
    		$conditions['or']['ArticleI18n.content like'] = "%{$article_keywords}%";
    		$conditions['or']['ArticleI18n.meta_description like'] = "%{$article_keywords}%";
        }
        if ($params['type'] == 'module_help_information') {
            $conditions['Article.type'] = 'H';
        }
        $conditions['Article.status'] = 1;
        $Article = ClassRegistry::init('Article');
        //分页start
        $total = $Article->find('count', array('conditions' => $conditions));
        App::import('Component', 'Paginationmodel');
        $pagination = new PaginationModelComponent();

        //get参数
        $parameters['get'] = array();
        //地址路由参数（和control,action的参数对应）
        $parameters['route'] = array('controller' => 'articles','action' => 'category/'.$params['id'],'page' => $page,'limit' => $limit);
        //分页参数
        $options = array('page' => $page,'show' => $limit,'modelClass' => 'Article','total' => $total);
        //pr($conditions);die;
        $pages = $pagination->init($conditions, $parameters, $options); // Added
        //pr($pages);
        //分页end
	

        $category_article_infos = $Article->find('all', array('conditions' => $conditions, 'page' => $page, 'limit' => $limit, 'order' => 'Article.'.$order, 'fields' => 'Article.id,Article.clicked,Article.file_url,Article.category_id,Article.file,Article.created,ArticleI18n.img01,ArticleI18n.title,ArticleI18n.subtitle,ArticleI18n.content,ArticleI18n.meta_description'));
        if (!empty($category_article_infos)) {
            $article_ids=array();
            foreach ($category_article_infos as $k => $v) {
                $article_ids[]=$v['Article']['id'];
                $category_article_infos[$k]['ArticleI18n']['des_content'] = $this->cutstr($v['ArticleI18n']['meta_description'], 80);
            }
            App::import('model','UserLike');
            if (class_exists('UserLike')) {
			$UserLike = new UserLike(); 
            $article_like_info=$UserLike->find('all',array('conditions'=>array('UserLike.type'=>'A','UserLike.type_id'=>$article_ids,'UserLike.action'=>'like'),'fields'=>array("UserLike.type_id","count(UserLike.id) as like_num"),"group"=>'type_id'));
            	$article_like_data=array();
            	foreach($article_like_info as $v){
            		$article_like_data[$v['UserLike']['type_id']]=$v[0]['like_num'];
            	}
            	$category_article['article_like'] = $article_like_data;
            	if(isset($_SESSION['User']['User'])){
            		$user_id=$_SESSION['User']['User']['id'];
            		$article_user_like=$UserLike->find('list',array('conditions'=>array('UserLike.type'=>'A','UserLike.type_id'=>$article_ids,'UserLike.user_id'=>$user_id,'UserLike.action'=>'like'),'fields'=>array("UserLike.type_id","UserLike.id")));
            		$category_article['article_user_like'] = $article_user_like;
            	}
            }
            App::import('model','UserFavorite');
            if (class_exists('UserFavorite')) {
            	$UserFavorite = new UserFavorite();
            	$article_favourite_info=$UserFavorite->find('all',array('conditions'=>array('UserFavorite.type'=>'a','UserFavorite.type_id'=>$article_ids,'UserFavorite.status'=>'1'),'fields'=>array("UserFavorite.type_id","count(UserFavorite.id) as favourite_num"),"group"=>'type_id'));
            	$article_favourite_data=array();
            	foreach($article_favourite_info as $v){
            		$article_favourite_data[$v['UserFavorite']['type_id']]=$v[0]['favourite_num'];
            	}
            	$category_article['article_favourite']=$article_favourite_data;
            	if(isset($_SESSION['User']['User'])){
            		$user_id=$_SESSION['User']['User']['id'];
            		$article_user_favourite=$UserFavorite->find('list',array('conditions'=>array('UserFavorite.type'=>'a','UserFavorite.type_id'=>$article_ids,'UserFavorite.user_id'=>$user_id,'UserFavorite.status'=>'1'),'fields'=>array("UserFavorite.type_id","UserFavorite.id")));
            		$category_article['article_user_favourite'] = $article_user_favourite;
            	}
            }
        }
        $CategoryArticle = ClassRegistry::init('CategoryArticle');
        $category_article['category_name'] = $CategoryArticle->get_articlecategory_name_by_id($params['id'], $c_locale);
        $category_article['category_detail'] = $CategoryArticle->get_articlecategory_detail_by_id($params['id'], $c_locale);
        $category_article['category_article'] = $category_article_infos;
        $category_article['paging'] = $pages;
        return $category_article;
    }
    
    /**
     * cutstr方法，.
     *
     * @param $string 输入字符串
     * @param $length 输入长度
     * @param $dot 输入点
     *
     * @return $string      返回字符串
     * @return $strcut.$dot 返回结构
     * @return $strcut      返回结构
     *
     * @todo 标注 这个可能在controller里面没有用到或者统一到app_model
     */
    public function cutstr($string, $length, $dot = ' ...')
    {
        global $charset;
        $oldstr = strlen($string);
        if (strlen($string) <= $length) {
            return $string;
        }

        $string = str_replace(array('&amp;', '&quot;', '&lt;', '&gt;'), array('&', '"', '<', '>'), $string);
        if (function_exists('mb_substr')) {
            $string = mb_substr($string, 0, $length, 'utf-8');
            $charset = 'utf-8';
        } elseif (function_exists('iconv_substr')) {
            $string = iconv_substr($string, 0, $length, 'utf-8');
            $charset = 'utf-8';
        }
        $strcut = '';
        if (strtolower($charset) == 'utf-8') {
            $n = $tn = $noc = 0;
            while ($n < strlen($string)) {
                $t = ord($string[$n]);
                if ($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
                    $tn = 1;
                    ++$n;
                    ++$noc;
                } elseif (194 <= $t && $t <= 223) {
                    $tn = 2;
                    $n += 2;
                    $noc += 2;
                } elseif (224 <= $t && $t <= 239) {
                    $tn = 3;
                    $n += 3;
                    $noc += 2;
                } elseif (240 <= $t && $t <= 247) {
                    $tn = 4;
                    $n += 4;
                    $noc += 2;
                } elseif (248 <= $t && $t <= 251) {
                    $tn = 5;
                    $n += 5;
                    $noc += 2;
                } elseif ($t == 252 || $t == 253) {
                    $tn = 6;
                    $n += 6;
                    $noc += 2;
                } else {
                    ++$n;
                }

                if ($noc >= $length) {
                    break;
                }
            }
            if ($noc > $length) {
                $n -= $tn;
            }

            $strcut = substr($string, 0, $n);
        } else {
            for ($i = 0; $i < $length; ++$i) {
                $strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
            }
        }

        $strcut = str_replace(array('&', '"', '<', '>'), array('&amp;', '&quot;', '&lt;', '&gt;'), $strcut);
        if ($oldstr > strlen($strcut)) {
            return $strcut.$dot;
        }

        return $strcut;
    }

    /**
     * sub_str方法.
     *
     * @param $str 输入字符串
     * @param $length 输入长度
     * @param $append 输入追加数据
     *
     * @return $str    返回字符串
     * @return $newstr 返回新的字符串
     *
     * @todo  标注 这个可能在controller里面没有用到
     */
    public function sub_str($str, $length = 0, $append = true)
    {
        $str = trim($str);
        $strlength = strlen($str);

        if ($length == 0 || $length >= $strlength) {
            return $str;
        } elseif ($length < 0) {
            $length = $strlength + $length;
            if ($length < 0) {
                $length = $strlength;
            }
        }

        if (function_exists('mb_substr')) {
            $newstr = mb_substr($str, 0, $length, 'utf-8');
        } elseif (function_exists('iconv_substr')) {
            $newstr = iconv_substr($str, 0, $length, 'utf-8');
        } else {
            //$newstr = trim_right(substr($str, 0, $length));
            $newstr = substr($str, 0, $length);
        }

        if ($append && $str != $newstr) {
            $newstr .= '...';
        }

        return $newstr;
    }

    public function find_list_by_cat($condition, $rownum, $page)
    {
        $list_by_cat = $this->find('all', array('conditions' => $condition, 'fields' => array('Article.id'), 'order' => array("Article.$orderby asc"), 'limit' => $rownum, 'page' => $page));

        return $list_by_cat;
    }
}
