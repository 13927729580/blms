<?php

/**
 * 分类模型 todo 把商品和文章分两个成员变量存放.
 */
class CategoryProduct extends AppModel
{
    /*
     * @var $useDbConfig 数据库配置
     */
    public $useDbConfig = 'oms';
    /*
     * @var $name CategoryProduct 分类表
     */
    public $name = 'CategoryProduct';
    /*
     * @var $cacheQueries true 是否开启缓存：是。
     */
    public $cacheQueries = true;
    /*
     * @var $cacheAction 1day 缓存时间：1天。
     */
    public $cacheAction = '1 day';
    /*
     * @var $hasOne array 关联分类多语言表
     */
    public $hasOne = array('CategoryProductI18n' => array('className' => 'CategoryProductI18n',
            'order' => '',
            'dependent' => true,
            'conditions' => array('CategoryProductI18n.locale' => LOCALE),
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

    public function set_locale($locale)
    {
        $conditions = " CategoryProductI18n.locale = '".$locale."'";
        $this->hasOne['CategoryProductI18n']['conditions'] = $conditions;
    }

    public function get_cat($id)
    {
        if (empty($id)) {
            return false;
        }
        $foo = array();
        $foo_cat = $this->find('first', array('conditions' => array('CategoryProduct.id' => $id), 'fields' => array('CategoryProduct.parent_id', 'CategoryProduct.id')), false);
        if (!empty($foo_cat)) {
            $foo[] = $foo_cat['CategoryProduct']['id'];
            while ($foo_cat['CategoryProduct']['parent_id'] != '0') {
                if (!isset($foo_cat['CategoryProduct']['parent_id'])) {
                    break;
                }
                $foo_cat = $this->find('first', array('conditions' => array('CategoryProduct.id' => $foo_cat['CategoryProduct']['parent_id']), 'fields' => array('CategoryProduct.parent_id', 'CategoryProduct.id')), false);
                $foo[] = $foo_cat['CategoryProduct']['id'];
            }
            $foo = array_reverse($foo);
        }

        return $foo;
    }
/**
 * tree方法，主键.
 *
 * @param $type 输入类型
 * @param $category_id 输入id
 * @param $locale 输入语言
 * @param $db 输入数据库
 *
 * @return $this->allinfo[$type] 返回所有的输入值，如果不存在则从数据库中取出相对应的数据
 */
     //缓存之后
    public function tree($type = 'P', $category_id = 0, $locale = '', $limit = '')
    {
        // $x=$this->find('first',array('cache'=>$this->short,'conditions'=>array('CategoryProduct.modified >'=>date("Y-m-d H:00:00")),'fields'=>array('CategoryProduct.modified')));
            $this->categories_parent_format = array();
        $this->cat_navigate_format = array();
        $this->all_subcat = array();
        $this->direct_subcat = array();
        $this->allinfo[$type] = array();
        $lists = $this->find('all', array('cache' => $this->short, 'order' => 'CategoryProduct.orderby asc,CategoryProduct.created asc',
                        'fields' => array('CategoryProduct.id', 'CategoryProduct.parent_id', 'CategoryProduct.type', 'CategoryProduct.img01', 'CategoryProduct.img02', 'CategoryProductI18n.name', 'CategoryProductI18n.meta_description',      'CategoryProduct.link', 'CategoryProduct.code', 'CategoryProduct.template', 'CategoryProduct.layout', 'CategoryProduct.created', 'CategoryProduct.modified', 'CategoryProduct.new_show', 'CategoryProduct.home_show','CategoryProductI18n.detail','CategoryProductI18n.top_detail','CategoryProductI18n.foot_detail'
                        ),
                        'limit' => $limit,
                        'conditions' => array("status ='1' AND type='".$type."' "),
                    ));
        $lists2 = $this->find('list', array('cache' => $this->short, 'order' => 'home_cat_orderby asc',
                        'fields' => array('CategoryProduct.id'),
                        'limit' => $limit,
                        'conditions' => array("status ='1' AND type='".$type."' "),
                    ));
        $week_ago = date('Y-m-d H:00:00', strtotime('-1 week'));
        $lists_formated = array();
            //	pr($lists) 全部的分类
            if (is_array($lists)) {
                foreach ($lists as $k => $v) {
                    $this->allinfo[$type]['all_ids'][] = $v['CategoryProduct']['id'];
                    if ($v['CategoryProduct']['created'] >= $week_ago && $v['CategoryProduct']['new_show'] == '1') {
                        $v['CategoryProduct']['is_new'] = 1;
                    }
                    $lists_formated[$v['CategoryProduct']['id']] = $v;
                }

                //	pr($lists_formated); 格式化为ID为序
                $this->allinfo[$type]['assoc'] = $lists_formated;
                $all_ids = array();
                foreach ($lists as $k => $v) {
                    $all_ids[] = $v['CategoryProduct']['id'];
                    if ($v['CategoryProduct']['created'] >= $week_ago && $v['CategoryProduct']['new_show'] == '1') {
                        $v['CategoryProduct']['is_new'] = 1;
                    }
                    $this->categories_parent_format[$v['CategoryProduct']['parent_id']][] = $v;
                }
                //pr($all_ids);
                //	pr($this->categories_parent_format); //格式化为以parent_id为序
                $this->allinfo[$type]['tree'] = $this->subcat_get(0);
                $this->allinfo[$type]['subids'] = $this->all_subcat;
                $this->allinfo[$type]['all_catids'] = $all_ids;
                $this->allinfo[$type]['home_all_ids'] = $lists2;
                $this->allinfo[$type]['direct_subids'] = $this->direct_subcat;
                $this->categories_parent_format = array();
                //pr($this->allinfo);
                return $this->allinfo[$type];
            }
    }

   /* function tree($type='P', $category_id = 0, $locale='') {
        $cache_key = md5($this->name . '_' . $type . '_' . $category_id . '_' . $locale);
        $this->allinfo[$type] = cache::read($cache_key);
        $x=$this->find('first',array('cache'=>$this->short,'conditions'=>array('CategoryProduct.modified >'=>date("Y-m-d H:i:s",strtotime("-1 hour"))),'fields'=>array('CategoryProduct.modified')));
        if ($this->allinfo[$type] && !$x) {
        	//echo 1;
            return $this->allinfo[$type];
        } else {
        	//var_dump($x);
            $this->categories_parent_format = array();
            $this->cat_navigate_format = array();
            $this->all_subcat = array();
            $this->direct_subcat = array();
            $this->allinfo[$type] = array();
            //	$lists=$this->findall("status ='1' AND type='".$type."' ",'','orderby asc');
            $lists = $this->find('all', array('cache'=>$this->short,'order' => 'orderby asc',
                        'fields' => array('CategoryProduct.id', 'CategoryProduct.parent_id', 'CategoryProduct.type', 'CategoryProduct.img01', 'CategoryProduct.img02', 'CategoryProductI18n.name',
                            'CategoryProduct.link', 'CategoryProduct.created', 'CategoryProduct.new_show', 'CategoryProduct.home_show'
                        ),
                        'conditions' => array("status ='1' AND type='" . $type . "' ")
                    ));

            //pr($lists);
            $week_ago = date("Y-m-d H:i:s", strtotime("-1 week"));

            $lists_formated = array();
            //	pr($lists);  全部的分类
            if (is_array($lists)) {
                foreach ($lists as $k => $v) {
                    $this->allinfo[$type]['all_ids'][] = $v['CategoryProduct']['id'];
                    if ($v['CategoryProduct']['created'] >= $week_ago && $v['CategoryProduct']['new_show'] == '1') {
                        $v['CategoryProduct']['is_new'] = 1;
                    }
                    $lists_formated[$v['CategoryProduct']['id']] = $v;
                }

                //	pr($lists_formated); 格式化为ID为序
                $this->allinfo[$type]['assoc'] = $lists_formated;

                foreach ($lists as $k => $v) {
                    if ($v['CategoryProduct']['created'] >= $week_ago && $v['CategoryProduct']['new_show'] == '1') {
                        $v['CategoryProduct']['is_new'] = 1;
                    }
                    $this->categories_parent_format[$v['CategoryProduct']['parent_id']][] = $v;
                }
                //	pr($this->categories_parent_format); //格式化为以parent_id为序
                $this->allinfo[$type]['tree'] = $this->subcat_get(0);
                $this->allinfo[$type]['subids'] = $this->all_subcat;
                $this->allinfo[$type]['direct_subids'] = $this->direct_subcat;
                $this->categories_parent_format = array();
                cache::write($cache_key, $this->allinfo[$type]);
                return $this->allinfo[$type];
            }
        }
    }*/

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
                if (isset($this->categories_parent_format[$v['CategoryProduct']['id']]) && is_array($this->categories_parent_format[$v['CategoryProduct']['id']])) {
                    $category['SubCategory'] = $this->subcat_get($v['CategoryProduct']['id']);
                }

                $subcat[$v['CategoryProduct']['id']] = $category;
                //	pr($subcat); //parent_id 为 0 的数据

                $this->all_subcat[$v['CategoryProduct']['id']][] = $v['CategoryProduct']['id'];
                $this->direct_subcat[$v['CategoryProduct']['parent_id']][] = $v['CategoryProduct']['id'];
                if (isset($this->all_subcat[$v['CategoryProduct']['parent_id']])) {
                    if ($v['CategoryProduct']['parent_id'] > 0) {
                        $this->all_subcat[$v['CategoryProduct']['parent_id']] = array_merge($this->all_subcat[$v['CategoryProduct']['parent_id']], $this->all_subcat[$v['CategoryProduct']['id']]);
                    } else {
                        $this->all_subcat[$v['CategoryProduct']['parent_id']][] = $v['CategoryProduct']['id'];
                    }
                } else {
                    $this->all_subcat[$v['CategoryProduct']['parent_id']] = $this->all_subcat[$v['CategoryProduct']['id']];
                }

                //	pr($this->all_subcat);  ??
            }
        }

        return $subcat;
    }

    //hobby 20081117 取得id=>name的数组
    /**
     * findassoc方法，取得id=>name的数组.
     *
     * @param $locale 输入语言
     *
     * @return $lists_formated 返回格式化的列表
     */
    public function findassoc($locale = '')
    {
        $condition = " CategoryProduct.status ='1' ";
        $orderby = ' orderby asc ';
        $cache_key = md5($this->name.'_'.$locale);

        $lists_formated = cache::read($cache_key);
        if ($lists_formated) {
            return $lists_formated;
        } else {
            $lists = $this->findall($condition, '', $orderby);
            $lists_formated = array();
            if (is_array($lists)) {
                foreach ($lists as $k => $v) {
                    $lists_formated[$v['CategoryProduct']['id']] = $v;
                }
            }
            cache::write($cache_key, $lists_formated);

            return $lists_formated;
        }
    }

//分类详细页递归获取下级分类信息以及id集合开始
    /**
     * cat_tree方法，.
     *
     * @param $type
     * @param $cat_id
     *
     * @return $this->onesubcat_get($cat_id)
     */
    public function cat_tree($type = 'P', $cat_id = 0)
    {
        $cats_info = $this->find('all', array('conditions' => array('CategoryProduct.status' => 1, 'CategoryProduct.type' => $type)), false);
        if (is_array($cats_info)) {
            foreach ($cats_info as $k => $v) {
                $this->cats_parent_format[$v['CategoryProduct']['parent_id']][] = $v;
            }
        }
        //pr($this->cats_parent_format);
        return $this->onesubcat_get($cat_id);
    }

    /**
     * onesubcat_get方法，.
     *
     * @param $cat_id
     *
     * @return $onesubcat
     */
    public function onesubcat_get($cat_id)
    {
        //echo $cat_id;
        $onesubcat = array();
        if (isset($this->cats_parent_format[$cat_id]) && is_array($this->cats_parent_format[$cat_id])) {
            foreach ($this->cats_parent_format[$cat_id] as $k => $v) {
                $cat = $v;
                if (isset($this->cats_parent_format[$v['CategoryProduct']['id']]) && is_array($this->cats_parent_format[$v['CategoryProduct']['id']])) {
                    $cat['SubCategory'] = $this->onesubcat_get($v['CategoryProduct']['id']);
                } else {
                }
                $onesubcat[$k] = $cat;
            }
        }

        return $onesubcat;
    }

//分类所有下级分类id集合
    /**
     * cats_id_tree方法，.
     *
     * @param $categories
     *
     * @return $cats_id
     */
    public function cats_id_tree($categories)
    {
        $cats_id = array();
        $cat_id = array();
        $subcat = array();
        foreach ($categories as $k => $v) {
            $cat_id[$k + 1] = $v['CategoryProduct']['id'];
            if (isset($v['SubCategory']) && is_array($v['SubCategory'])) {
                $subcat = $this->cats_id_tree($v['SubCategory']);
                //pr($subcat);
            }
        }
        $cats_id = array_merge($cat_id, $subcat);
//	pr($cats_id);
        return $cats_id;
    }

    /**
     * get_list方法，.
     *
     * @param $category_id
     *
     * @return $Lists
     */
    public function get_list($category_id)
    {
        $Lists = array();
        $condition = "CategoryProduct.status ='1'";
        if ($category_id != '') {
            $condition .= ' AND CategoryProduct.id in ('.$category_id.')';
        }

        $Lists = $this->findAll($condition, '', 'orderby asc');

        return $Lists;
    }

    /**
     * cat_navigate方法，.
     *
     * @param $type
     * @param $category_id
     *
     * @return $this->cat_navigate_format
     */
    public function cat_navigate($type = 'P', $category_id)
    {
        $cat_info = array();
        $condition = "CategoryProduct.status ='1' AND CategoryProduct.type = '$type' AND CategoryProduct.id = '$category_id' ";
        $cat_info = $this->findbyid($category_id);
        $parent_id = $cat_info['CategoryProduct']['parent_id'];
        if ($parent_id != 0) {
            $this->cat_navigate_format[] = $cat_info;

            return $this->cat_navigate($type, $parent_id);
        } else {
            $this->cat_navigate_format[] = $cat_info;

            return $this->cat_navigate_format;
        }
    }

    public function find_all($locale)
    {
        $params = array('cache' => $this->short,'order' => array('CategoryProduct.modified DESC'),
            'fields' => array('CategoryProduct.id', 'CategoryProduct.parent_id', 'CategoryProduct.type', 'CategoryProduct.img01', 'CategoryProductI18n.name',
                'CategoryProduct.link', 'CategoryProduct.modified', 'CategoryProduct.created',
            ),
            'conditions' => array('CategoryProduct.status' => 1),
        );
        $article_categorys = $this->find('all', $params, $this->name.$locale);
        $article_categorys_list = array();
        if (sizeof($article_categorys) > 0) {
            foreach ($article_categorys as $k => $v) {
                $article_categorys_list[$v['CategoryProduct']['id']] = $v;
            }
        }

        return $article_categorys_list;
    }

    /**
     * home_category方法，主分类类别.
     *
     * @param $locale 输入语言
     *
     * @return $home_categorys_list 返回列表
     */
    public function home_category($locale)
    {
        $params = array('cache' => $this->short,'order' => array('CategoryProduct.orderby DESC'),
            'fields' => array('CategoryProduct.id', 'CategoryProduct.parent_id', 'CategoryProduct.type', 'CategoryProduct.img01', 'CategoryProduct.img02', 'CategoryProductI18n.name',
                'CategoryProduct.link', 'CategoryProduct.modified', 'CategoryProduct.created',
            ),
            'conditions' => array('CategoryProduct.status' => 1, 'CategoryProduct.type' => 'P', 'CategoryProduct.parent_id' => 0)
            , 'limit' => '6',
        );
        $home_categorys = $this->find('all', $params, $this->name.$locale.'home_category');
        $home_categorys_list = array();
        if (sizeof($home_categorys) > 0) {
            foreach ($home_categorys as $k => $v) {
                $home_categorys_list[$v['CategoryProduct']['id']] = $v;
            }
        }

        return $home_categorys_list;
    }

    public function home_show_category($locale)
    {
        $params = array('cache' => $this->short,'order' => array('CategoryProduct.orderby DESC'),
            'fields' => array('CategoryProduct.id', 'CategoryProduct.parent_id', 'CategoryProduct.type', 'CategoryProduct.img01', 'CategoryProduct.img02', 'CategoryProductI18n.name',
                'CategoryProduct.link', 'CategoryProduct.modified', 'CategoryProduct.created',
            ),
            'conditions' => array('CategoryProduct.status' => 1, 'CategoryProduct.type' => 'P', 'CategoryProduct.home_show' => '1')
            , 'limit' => '6',
        );
        $home_show_categorys = $this->find('all', $params, $this->name.$locale.'home_show_category');
        $home_show_categorys_list = array();
        if (sizeof($home_show_categorys) > 0) {
            foreach ($home_show_categorys as $k => $v) {
                $home_show_categorys_list[$v['CategoryProduct']['id']] = $v;
            }
        }

        return $home_show_categorys_list;
    }

    public function find_category_info()
    {
        $category_info = $this->find('all', array('cache' => $this->short, 'fields' => array('CategoryProduct.id', 'CategoryProductI18n.name'),
                    'conditions' => array('CategoryProduct.id' => '29'), ));

        return $category_info;
    }

    public function find_sub_category($id, $locale)
    {
        $sub_category = $this->find('all', array('cache' => $this->short, 'conditions' => array('CategoryProduct.status' => 1, 'CategoryProduct.parent_id' => $id, 'CategoryProduct.type' => 'P')), 'sub_category_'.$id.'_'.$locale);

        return $sub_category;
    }

    public function get_navigations()
    {
        $navigations_data = $this->find('all', array('cache' => $this->short, 'conditions' => array('CategoryProduct.sub_type' => 'H', 'CategoryProduct.status' => '1', 'CategoryProduct.type' => 'A'), 'order' => array('CategoryProduct.orderby asc'), 'fields' => array('CategoryProduct.id', 'CategoryProductI18n.name')));

        return $navigations_data;
    }

    //获取顶级分类
    public function find_categories_num()
    {
        $categories = $this->find('all', array('cache' => $this->short, 'conditions' => array('CategoryProduct.type' => 'P', 'CategoryProduct.status' => '1')));
        $categories_num = count($categories);

        return $categories_num;
    }
    //获取首页显示的分类的首页显示商品的数量
    public function homeNum()
    {
        $all = $this->find('all', array('conditions' => array('CategoryProduct.home_show' => 1), 'fields' => 'CategoryProduct.home_show_num,CategoryProduct.id'));
        $catNum = '';
        if (!empty($all)) {
            foreach ($all as $v) {
                $catNum[$v['CategoryProduct']['id']] = $v['CategoryProduct']['home_show_num'];
//    		//判断是否有子分类
//    		$sunInfos=$this->find('all',array('conditions'=>array('CategoryProduct.parent_id'=>$v['CategoryProduct']['id'])));
//    		if(!empty($sunInfos)){
//    			foreach($sunInfos as $vv){
//    				$catNum[$vv['CategoryProduct']['id']]=$v['CategoryProduct']['home_show_num'];
//    			}
//    		}
            }
        }

        return $catNum;
    }
    //获取首页显示的分类的首页显示商品的过滤关键字
    public function homeShowkeywords($locale)
    {
        $this->set_locale($locale);
        $all = $this->find('all', array('conditions' => array('CategoryProduct.home_show' => 1), 'fields' => 'CategoryProductI18n.home_show_keywords,CategoryProduct.id'));
        $catKeys = '';
        if (!empty($all)) {
            foreach ($all as $v) {
                if (isset($v['CategoryProductI18n']['home_show_keywords']) && !empty($v['CategoryProductI18n']['home_show_keywords'])) {
                    $catKeys[$v['CategoryProduct']['id']] = array_filter(explode(' ', $v['CategoryProductI18n']['home_show_keywords']));
                }
//	    		//判断是否有子分类
//	    		$sunInfos=$this->find('all',array('conditions'=>array('CategoryProduct.parent_id'=>$v['CategoryProduct']['id'])));
//	    		if(!empty($sunInfos)){
//	    			foreach($sunInfos as $vv){
//	    				if(isset($v['CategoryProductI18n']['home_show_keywords'])&&!empty($v['CategoryProductI18n']['home_show_keywords'])){
//	    					$catKeys[$vv['CategoryProduct']['id']]=$v['CategoryProductI18n']['home_show_keywords'];
//	    				}
//	    			}
//	    		}
            }
        }

        return $catKeys;
    }
    //获取首页显示的分类的首页显示商品的排序方式
    public function homeShoworders()
    {
        $all = $this->find('all', array('conditions' => array('CategoryProduct.home_show' => 1), 'fields' => 'CategoryProduct.home_show_order,CategoryProduct.id', 'recursive' => -1));
        $catOrders = '';
        if (!empty($all)) {
            foreach ($all as $v) {
                if (isset($v['CategoryProduct']['home_show_order']) && !empty($v['CategoryProduct']['home_show_order'])) {
                    $catOrders[$v['CategoryProduct']['id']] = $v['CategoryProduct']['home_show_order'];
                }
//	    		//判断是否有子分类
//	    		$sunInfos=$this->find('all',array('conditions'=>array('CategoryProduct.parent_id'=>$v['CategoryProduct']['id'])));
//	    		if(!empty($sunInfos)){
//	    			foreach($sunInfos as $vv){
//	    				if(isset($v['CategoryProduct']['home_show_order'])&&!empty($v['CategoryProduct']['home_show_order'])){
//	    					$catOrders[$vv['CategoryProduct']['id']]=$v['CategoryProduct']['home_show_order'];
//	    				}
//	    			}
//	    		}
            }
        }

        return $catOrders;
    }

    public function homeCatimgs()
    {
        $all = $this->find('all', array('conditions' => array('CategoryProduct.home_show' => 1), 'fields' => 'CategoryProduct.img02,CategoryProduct.id', 'recursive' => -1));
    //	pr($all);
        $catImgs = '';
        if (!empty($all)) {
            foreach ($all as $v) {
                if (isset($v['CategoryProduct']['img02']) && !empty($v['CategoryProduct']['img02'])) {
                    $catImgs[$v['CategoryProduct']['id']] = $v['CategoryProduct']['img02'];
                }
//	    		//判断是否有子分类
//	    		$sunInfos=$this->find('all',array('conditions'=>array('CategoryProduct.parent_id'=>$v['CategoryProduct']['id'])));
//	    		if(!empty($sunInfos)){
//	    			foreach($sunInfos as $vv){
//	    				if(isset($v['CategoryProduct']['img02'])&&!empty($v['CategoryProduct']['img02'])){
//	    					$catImgs[$vv['CategoryProduct']['id']]=$v['CategoryProduct']['img02'];
//	    				}
//	    			}
//	    		}
            }
        }
        //pr($catImgs);
        return $catImgs;
    }
    public function getAllDesc()
    {
        $allCatDesc = array();
        $Infos = $this->find('all', array('conditions' => array('CategoryProduct.status' => '1'), 'fields' => 'CategoryProduct.id,CategoryProductI18n.detail'));
        if (!empty($Infos)) {
            foreach ($Infos as $v) {
                $allCatDesc[$v['CategoryProduct']['id']] = $v['CategoryProductI18n']['detail'];
            }
        }

        return $allCatDesc;
    }
    //获取模块分类信息
    public function get_module_infos($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if ($params['type'] == 'module_article_category') {
            $category_infos = $this->tree('A', 0, LOCALE, $limit);
        } else {
            $category_infos = $this->tree('A', 0, LOCALE, $limit);
        }

        return $category_infos;
    }
    //
    public function auto_search($keyword, $type = 'P', $num = 10)
    {
        $result = array();
        $condition = array(
            'OR' => array(
                array("CategoryProduct.code like '%$keyword%' "),
                array("CategoryProductI18n.name like '%$keyword%' "),
                array("CategoryProductI18n.meta_description like '%$keyword%' "),
            ),
            'AND' => array('CategoryProduct.status' => '1',
                'CategoryProduct.type' => $type, ),
        );
        $params = array('order' => array('CategoryProduct.modified DESC'),
            //'fields' => array('CategoryProduct.id' , 'CategoryProduct.code'),
            'conditions' => $condition,
            'limit' => $num,
        );

        $result = $this->find('all', $params);

        return $result;
    }

    public function get_top_category_id($category_id)
    {
    	  if(isset($this->allinfo['P']['direct_subids'][0])){
	        foreach ($this->allinfo['P']['direct_subids'][0] as $k => $v) {
	            if (isset($this->allinfo['P']['subids'][$v])&&in_array($category_id, $this->allinfo['P']['subids'][$v])) {
	                return $v;
	            }
	        }
        }
        return $category_id;
    }

    /*
    * 函数get_mobile_category_list 获取mobile页面新闻分类
    * @params 查询参数
    * @return category_list 返回新闻分类
    */
    public function get_mobile_category_list($params)
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
        $conditions['CategoryProduct.status'] = '1';
        $conditions['CategoryProduct.type'] = 'A';
        $category_list = $this->find('all', array('fields' => array('CategoryProduct.id', 'CategoryProduct.img01', 'CategoryProductI18n.name'), 'conditions' => $conditions, 'order' => 'CategoryProduct.'.$order));

        return $category_list;
    }
    /*
    * 函数get_module_category_list 获取文章分类列表
    * @params 查询参数
    * @return category_list 返回文章分类
    */
    public function get_module_category_list($params)
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
        $conditions['CategoryProduct.status'] = '1';
        $conditions['CategoryProduct.type'] = 'A';
        $category_list = $this->find('all', array('fields' => array('CategoryProduct.id', 'CategoryProduct.img01', 'CategoryProductI18n.name', 'CategoryProduct.parent_id'), 'conditions' => $conditions, 'order' => 'CategoryProduct.'.$order));
        $SubCategory = array();
        foreach ($category_list as $k => $v) {
            if ($v['CategoryProduct']['parent_id'] != 0) {
                $SubCategory[$v['CategoryProduct']['id']] = $v;
                unset($category_list[$k]);
            }
        }

        foreach ($category_list as $ck => $cv) {
            $category_list[$ck]['SubCategory'] = array();
            foreach ($SubCategory as $sk => $sv) {
                if ($sv['CategoryProduct']['parent_id'] == $cv['CategoryProduct']['id']) {
                    array_push($category_list[$ck]['SubCategory'], $sv);
                }
            }
        }

        return $category_list;
    }
    /*
    * 函数get_module_category_pro_list 获取商品分类列表
    * @params 查询参数
    * @return category_list 返回商品分类列表
    */
    public function get_module_category_pro_list($params)
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
        $category_list['product_categories_tree'] = $this->allinfo['P']['tree'];
        $category_list['zhou_cats'] = $this->get_cat($params['id']);
        $category_list['top_categroy_id'] = $this->get_top_category_id($params['id']);

        return $category_list;
    }
    /*
    * 函数get_module_category_product 获取分类商品
    * @params 查询参数
    * @return category_list 返回分类商品
    */
    public function get_module_category_product($params)
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
        $id = 0;
        if (isset($params['id'])) {
            $id = $params['id'];
        }
        $page = 1;
        if (isset($params['page'])) {
            $page = $params['page'];
        }
        //查询当前分类设置的显示产品（新品，推荐，销量），判断赋值给sale和price
        $cond = $this->find('first', array('conditions' => array('CategoryProduct.id' => $id), 'fields' => 'CategoryProduct.show_info'));
        $sale = false;
        $new_arrival = false;
        $recommend = false;
        $price = false;
        $sub_category_goods = false;//子分类商品
        if (sizeof($cond) > 0) {
            $show_arr = explode(';', $cond['CategoryProduct']['show_info']);//字符串分割成数组
            foreach ($show_arr as $sk => $sv) {
                if ($sv == 'new_arrival') {
                    $new_arrival = true;
                } elseif ($sv == 'recommend') {
                    $recommend = true;
                } elseif ($sv == 'selling') {
                    $sale = true;
                } elseif ($sv == 'sub_categories_product') {
                    $sub_category_goods = true;
                }
            }
        }
        $ProductsCategory = ClassRegistry::init('ProductsCategory');
        //echo "1.".$sale."2.".$recommend."3.".$new_arrival;die;
        //分类筛选
        $pro_tab_cond['category_id'] = $this->allinfo['P']['subids'][$id];
        if(isset($params['min_price'])&&$params['min_price']!=-1){
        	$pro_tab_cond['shop_price >='] = $params['min_price'];
        }
        if(isset($params['max_price'])&&$params['max_price']!=-1){
        	$pro_tab_cond['shop_price <='] = $params['max_price'];
        }
        if (isset($params['ControllerObj'])) {
            $pro_tab_cond['ControllerObj'] = $params['ControllerObj'];
        }
        $Brand = ClassRegistry::init('Brand');
        $brand_info=$Brand->find('all',array('fields'=>array('Brand.id','BrandI18n.name'),'conditions'=>array("Brand.status"=>'1')));
        $branddata=array();
        foreach($brand_info as $v){
        	$branddata[$v['Brand']['id']]=$v['BrandI18n']['name'];
        }
        $Product = ClassRegistry::init('Product');
        $category_product['product'] = $Product->products_tab($pro_tab_cond, $sale, $price, $recommend, $new_arrival);
        //查找子分类
        $category_product['sub_categories_product'] = array();
        if ($sub_category_goods) {
            $cate_limt = 15;
            if (isset($params['ControllerObj'])) {
                if (isset($params['ControllerObj']->configs['products_category_page_size'])) {
                    $cate_limit = $params['ControllerObj']->configs['products_category_page_size'];
                }
            }
            $sub_cate_ids = $this->find('all', array('conditions' => array('CategoryProduct.parent_id' => $id), 'fields' => 'CategoryProduct.id,CategoryProductI18n.name'));
            //循环查找每个子分类的商品
            $p_fields = array('Product.id','Product.brand_id', 'Product.recommand_flag', 'Product.status', 'Product.img_thumb','Product.img_detail','Product.img_original','Product.product_image1','Product.product_image2'
                , 'Product.market_price'
                , 'Product.shop_price'
                , 'Product.category_id'
                , 'Product.promotion_price'
                , 'Product.promotion_start'
                , 'Product.promotion_end'
                , 'Product.promotion_status'
                , 'Product.code', 'Product.product_rank_id'
                , 'Product.quantity', 'Product.freeshopping', 'ProductI18n.name', 'ProductI18n.description','ProductI18n.description02','Product.unit' );
            foreach ($sub_cate_ids as $sid_k => $sid_v) {
                $ProductsCategory_pro_ids = $ProductsCategory->find('list', array('fields' => 'ProductsCategory.product_id', 'conditions' => array('ProductsCategory.category_id' => $sid_v['CategoryProduct']['id'])));
                $p_cond['OR']['Product.category_id'] = $sid_v['CategoryProduct']['id'];
                $p_cond['OR']['Product.id'] = $ProductsCategory_pro_ids;
                $p_cond['Product.status'] = 1;
                $p_cond['Product.forsale'] = 1;
                $p_cond['Product.alone'] = 1;
		if(isset($params['min_price'])&&$params['min_price']!=-1){
			$p_cond['Product.shop_price >='] = $params['min_price'];
		}
		if(isset($params['max_price'])&&$params['max_price']!=-1){
			$p_cond['Product.shop_price <='] = $params['max_price'];
		}
                $Product->hasMany = array();
                $p_arr = $Product->find('all', array('conditions' => $p_cond, 'fields' => $p_fields, 'limit' => $cate_limit));
                if (!empty($p_arr)) {
                	foreach($p_arr as $k=>$v){
                		$p_arr[$k]['Brand']=isset($branddata[$v['Product']['brand_id']])?$branddata[$v['Product']['brand_id']]:'';
                	}
                    $sub_category_goods_list[$sid_v['CategoryProductI18n']['name']] = $p_arr;
                }
            }
            $category_product['sub_categories_product'] = $sub_category_goods_list;
        }
        $cate_grade = 'bottom';
        if (!empty($this->allinfo['P']['direct_subids'][$id])) {
            if (!empty($this->allinfo['P']['assoc'][$id]['CategoryProduct']['parent_id'])) {
                //中间层分类
                $cate_grade = 'middle';
            } else {
                //最顶层分类
                $cate_grade = 'top';
            }
        } else {
            //最底层分类
            $cate_grade = 'bottom';
        }
        if (isset($params['ControllerObj']) && $cate_grade == 'top') {
            if (isset($params['ControllerObj']->configs['show_brand_by_category']) && $params['ControllerObj']->configs['show_brand_by_category'] == '1') {
                $brand_data = $Brand->get_brand_by_category($id);
                $category_product['brand'] = $brand_data;
            }
        }
        $category_ids=array();
        $category_ids[]=$id;
        $category_child_ids=$this->find('list',array('conditions'=>array('CategoryProduct.parent_id'=>$id),'fields'=>"CategoryProduct.id"));
        if(!empty($category_child_ids)){
        	$category_ids=array_merge($category_ids,$category_child_ids);
        	$category_sub_ids=$this->find('list',array('conditions'=>array('CategoryProduct.parent_id'=>$category_child_ids),'fields'=>"CategoryProduct.id"));
        	if(!empty($category_sub_ids)){
        		$category_ids=array_merge($category_ids,$category_sub_ids);
        	}
        }
        $bottom_pro_cond = array();
        $bottom_pro_ids = $ProductsCategory->find('list', array('fields' => 'ProductsCategory.product_id', 'conditions' => array('ProductsCategory.category_id' => $category_ids)));
        $bottom_pro_cond['and']['or']['Product.category_id'] = $category_ids;
        $bottom_pro_cond['and']['or']['Product.id'] = $bottom_pro_ids;
        $bottom_pro_cond['and']['Product.status'] = 1;
        $bottom_pro_cond['and']['Product.forsale'] = 1;
        $bottom_pro_cond['and']['Product.alone'] = 1;
	if(isset($params['min_price'])&&$params['min_price']!=-1){
		$bottom_pro_cond['Product.shop_price >='] = $params['min_price'];
	}
	if(isset($params['max_price'])&&$params['max_price']!=-1){
		$bottom_pro_cond['Product.shop_price <='] = $params['max_price'];
	}
            //三级分类的产品
            //分页start
            $total = $Product->find('count', array('conditions' => $bottom_pro_cond));
        App::import('Component', 'Paginationmodel');
        $pagination = new PaginationModelComponent();

            //get参数
            $parameters['get'] = array();
            //地址路由参数（和control,action的参数对应）
	$page_action=isset($params['ControllerObj']->action)?$params['ControllerObj']->action:"";
        $parameters['route'] = array('controller' => 'categories','action' => $page_action."/".$id,'page' => $page,'limit' => $limit);
            //分页参数
            $options = array('page' => $page,'show' => $limit,'modelClass' => 'Product','total' => $total);
            $pages = $pagination->init($conditions, $parameters, $options); // Added
            //分页end
            $category_product['bottom'] = $Product->find('all', array('conditions' => $bottom_pro_cond, 'fields' => 'Product.id,Product.category_id,Product.brand_id,Product.shop_price,Product.img_thumb,Product.img_detail,Product.img_original,Product.product_image1,Product.product_image2,Product.code,ProductI18n.name,Product.like_stat, ProductI18n.description,ProductI18n.description02,Product.unit', 'limit' => $limit, 'page' => $page,'order'=>$order));
        if (!empty($category_product['bottom']) && sizeof($category_product['bottom']) > 0) {
            $product_ids = $Product->getproduct_ids($category_product['bottom']);
            $product_codes = $Product->getproduct_codes($category_product['bottom']);
            $comment = ClassRegistry::init('Comment');
            $user_like = ClassRegistry::init('UserLike');
            $user_favorite = ClassRegistry::init('UserFavorite');
            $SkuProduct = ClassRegistry::init('SkuProduct');
            $Attribute = ClassRegistry::init('Attribute');
            $ProductAttribute = ClassRegistry::init('ProductAttribute');
            $price_range=$SkuProduct->sku_price_range($product_codes);
            
            $attribute_product_data=array();
            $product_attribute_info=$ProductAttribute->find('all',array("fields"=>"ProductAttribute.product_id,ProductAttribute.attribute_id","conditions"=>array("ProductAttribute.product_id"=>$product_ids)));
            if(!empty($product_attribute_info)){
            	$attribute_ids=array();
                   $attribute_product_info=array();
	            foreach($product_attribute_info as $v){
	            	$attribute_ids[$v['ProductAttribute']['attribute_id']]=$v['ProductAttribute']['attribute_id'];
	            	$attribute_product_info[$v['ProductAttribute']['product_id']][]=$v['ProductAttribute']['attribute_id'];
	            }
	            $attribute_info=$Attribute->find('all',array('fields'=>array("Attribute.id","AttributeI18n.name"),'conditions'=>array("Attribute.status"=>'1','Attribute.id'=>$attribute_ids)));
	             $attribute_data=array();
			foreach($attribute_info as $v){
				$attribute_data[$v['Attribute']['id']]=$v['AttributeI18n']['name'];
			}
			foreach($attribute_product_info as $k=>$v){
				foreach($v as $vv){
					if(isset($attribute_data[$vv])){
						$attribute_product_data[$k][$vv]=$attribute_data[$vv];
					}
				}
			}
            }
            $comment_num = $comment->find('all', array('conditions' => array('Comment.type_id' => $product_ids, 'Comment.type' => 'P'), 'fields' => array('Comment.type_id', 'count(Comment.type_id) as Commentnum'), 'group' => 'Comment.type_id'));
            $like_num = $user_like->find('all', array('conditions' => array('UserLike.type' => 'P', 'UserLike.type_id' => $product_ids), 'fields' => array('UserLike.type', 'count(UserLike.type_id) as num'), 'group' => 'UserLike.type_id'));
            
		$UserLike_data=array();
		$UserFavorite_data=array();
		if(isset($_SESSION['User'])&&!empty($product_ids)){
			$user_id=$_SESSION['User']['User']['id'];
            $UserLike_data=$user_like->find('list',array('fields'=>'type_id,id','conditions'=>array('UserLike.user_id'=>$user_id,'UserLike.action'=>'like','UserLike.type'=>'P','UserLike.type_id'=>$product_ids)));
				$UserFavorite_data=$user_favorite->find('list',array('fields'=>'type_id,id','conditions'=>array('UserFavorite.user_id'=>$user_id,'UserFavorite.status'=>'1','UserFavorite.type'=>'P','UserFavorite.type_id'=>$product_ids)));
            }
            foreach ($category_product['bottom'] as $k => $v) {
                foreach ($like_num as $like_k => $like_v) {
                    if ($v['Product']['id'] == $like_v['UserLike']['type']) {
                        $category_product['bottom'][$k]['Product']['like_num'] = $like_v[0]['num'];
                    }
                }
                foreach ($comment_num as $com_k => $com_v) {
                    if ($v['Product']['id'] == $com_v['Comment']['type_id']) {
                        $category_product['bottom'][$k]['Product']['Commentnum'] = $com_v[0]['Commentnum'];
                    }
                }
                if(isset($price_range[$v['Product']['code']])){
			   $category_product['bottom'][$k]['price_range'] = $price_range[$v['Product']['code']];
		  }
		  $category_product['bottom'][$k]['UserLike']=isset($UserLike_data[$v['Product']['id']])?'1':'0';
		  $category_product['bottom'][$k]['UserFavorite']=isset($UserFavorite_data[$v['Product']['id']])?'1':'0';
		  $category_product['bottom'][$k]['Brand']=isset($branddata[$v['Product']['brand_id']])?$branddata[$v['Product']['brand_id']]:'';
		  $category_product['bottom'][$k]['Brand']=isset($attribute_product_data[$v['Product']['id']])?$attribute_product_data[$v['Product']['id']]:array();
            }
        }
        $category_product['paging'] = $pages;
        $category_product['cate_grade'] = $cate_grade;
        return $category_product;
    }
    /**
     * 函数get_module_category_flash 获取分类轮播内容.
     *
     * @param  参数集合
     *
     * @return $module_flash_infos 返回分类轮播
     */
    public function get_module_category_flash($params)
    {
        $conditions = '';
        $limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
        $order = 'created desc';
        if (isset($params['order'])) {
            $order = $params['order'];
        }
        if (isset($params['flash_type'])) {
            $conditions['Flash.page'] = $params['flash_type'];
        }
        if (isset($params['id'])) {
            $conditions['Flash.page_id'] = $params['id'];
        }
        $conditions['Flash.page'] = 'PC';
        $conditions['Flash.type'] = '0';
        $Flash = ClassRegistry::init('Flash');
        $module_flash_infos = $Flash->find('first', array('conditions' => $conditions, 'fields' => array('Flash.width', 'Flash.height', 'Flash.page_id')));
        //pr($module_flash_infos);
        return $module_flash_infos;
    }
	
	public function homepage_category_product($params=array()){
		$category_data=array();
		$category_product_data=array();
		$conditions="";
		$limit = 10;
        if (isset($params['limit'])) {
            $limit = $params['limit'];
        }
		$category_ids=array();
        $category_infos = $this->find('all', array('conditions' => array('CategoryProduct.status'=>'1','CategoryProduct.home_show'=>'1'),'limit'=>$limit));
		if(!empty($category_infos)){
			foreach($category_infos as $v){
				$category_ids[]=$v['CategoryProduct']['id'];
				$category_data[$v['CategoryProduct']['id']]=$v;
			}
			$child_category_data=array();
			$child_category_ids=array();
			$child_category_info=$this->find('all', array('conditions' => array('CategoryProduct.parent_id' => $category_ids,'CategoryProduct.status'=>'1'), 'fields' => 'CategoryProduct.id,CategoryProduct.parent_id'));
			if(!empty($child_category_info)){
				foreach($child_category_info as $v){
					$child_category_ids[]=$v['CategoryProduct']['id'];
					$child_category_data[$v['CategoryProduct']['id']]=$v['CategoryProduct']['parent_id'];
				}
				$category_ids=array_merge($category_ids,$child_category_ids);
				$sub_category_ids=array();
				$sub_category_info=$this->find('all', array('conditions' => array('CategoryProduct.parent_id' => $child_category_ids,'CategoryProduct.status'=>'1'), 'fields' => 'CategoryProduct.id,CategoryProduct.parent_id'));
				if(!empty($sub_category_info)){
					foreach($sub_category_info as $v){
						$sub_category_ids[]=$v['CategoryProduct']['id'];
						$child_category_data[$v['CategoryProduct']['id']]=isset($child_category_data[$v['CategoryProduct']['parent_id']])?$child_category_data[$v['CategoryProduct']['parent_id']]:0;
					}
					$category_ids=array_merge($category_ids,$sub_category_ids);
				}
			}
			$ProductsCategory = ClassRegistry::init('ProductsCategory');
			$product_ids=$ProductsCategory->find('list',array('conditions'=>array('ProductsCategory.category_id'=>$category_ids),'fields'=>'ProductsCategory.product_id'));
			$conditions['and']['or']['Product.category_id']=$category_ids;
			if(!empty($product_ids)){
				$conditions['and']['or']['Product.id']=$product_ids;
			}
			$conditions['and']['Product.status']='1';
			$conditions['and']['Product.forsale'] = '1';
			$conditions['and']['Product.alone'] = '1';
			$conditions['and']['Product.recommand_flag'] = '1';
			
			$p_fields = array('Product.id', 'Product.recommand_flag', 'Product.status','Product.img_big',
			'Product.img_thumb','Product.img_detail','Product.img_original','Product.product_image1','Product.product_image2'
			, 'Product.market_price'
			, 'Product.shop_price'
			, 'Product.category_id'
			, 'Product.promotion_price'
			, 'Product.promotion_start'
			, 'Product.promotion_end'
			, 'Product.promotion_status'
			, 'Product.code','Product.brand_id'
			, 'Product.product_rank_id'
			, 'Product.quantity', 'Product.freeshopping', 'ProductI18n.name', 'ProductI18n.description','ProductI18n.description02','Product.unit' );
			$Product = ClassRegistry::init('Product');
			$category_product_info=$Product->find('all',array('conditions'=>$conditions,'fields'=>$p_fields,'order'=>'Product.modified'));
			$product_ids=array();
			$brand_ids=array();
			$attribute_product_info=array();
			$attribute_ids=array();
			foreach($category_product_info as $v){
				$parent_category_id=isset($child_category_data[$v['Product']['category_id']])&&$child_category_data[$v['Product']['category_id']]!=0?$child_category_data[$v['Product']['category_id']]:0;
				$parent_parent_category_id=isset($child_category_data[$parent_category_id])&&$child_category_data[$parent_category_id]!=0?$child_category_data[$parent_category_id]:0;
				$category_product_data[$v['Product']['category_id']][]=$v;
				if($parent_category_id!=0){
					$category_product_data[$parent_category_id][]=$v;
				}
				if($parent_parent_category_id!=0){
					$category_product_data[$parent_parent_category_id][]=$v;
				}
				$product_ids[]=$v['Product']['id'];
				$brand_ids[$v['Product']['brand_id']]=$v['Product']['brand_id'];
				
				if(isset($v['ProductAttribute'])&&!empty($v['ProductAttribute'])){
					foreach($v['ProductAttribute'] as $vv){
						$attribute_product_info[$v['Product']['id']][]=$vv['attribute_id'];
						$attribute_ids[]=$vv['attribute_id'];
					}
				}
			}
			$Attribute = ClassRegistry::init('Attribute');
			$attribute_info=$Attribute->find('all',array('fields'=>array("Attribute.id","AttributeI18n.name"),'conditions'=>array("Attribute.status"=>'1','Attribute.id'=>$attribute_ids)));
			$attribute_data=array();
			$attribute_product_data=array();
			foreach($attribute_info as $v){
				$attribute_data[$v['Attribute']['id']]=$v['AttributeI18n']['name'];
			}
			foreach($attribute_product_info as $k=>$v){
				foreach($v as $vv){
					if(isset($attribute_data[$vv])){
						$attribute_product_data[$k][$vv]=$attribute_data[$vv];
					}
				}
			}
			$Brand = ClassRegistry::init('Brand');
			$brand_info=$Brand->find('all',array('fields'=>array("Brand.id","BrandI18n.name"),'conditions'=>array("Brand.status"=>'1','Brand.id'=>$brand_ids)));
			$brand_data=array();
			foreach($brand_info as $v){
				$brand_data[$v['Brand']['id']]=$v['BrandI18n']['name'];
			}
			$UserLike_data=array();
			$UserFavorite_data=array();
			if(isset($_SESSION['User'])&&!empty($product_ids)){
				$UserLike = ClassRegistry::init('UserLike');
				$UserFavorite = ClassRegistry::init('UserFavorite');
				$user_id=$_SESSION['User']['User']['id'];
				$UserLike_data=$UserLike->find('list',array('fields'=>'type_id,id','conditions'=>array('UserLike.user_id'=>$user_id,'UserLike.action'=>'like','UserLike.type'=>'P','UserLike.type_id'=>$product_ids)));
				$UserFavorite_data=$UserFavorite->find('list',array('fields'=>'type_id,id','conditions'=>array('UserFavorite.user_id'=>$user_id,'UserFavorite.status'=>'1','UserFavorite.type'=>'P','UserFavorite.type_id'=>$product_ids)));
			}
			foreach($category_product_data as $k=>$v){
				foreach($v as $kk=>$vv){
					$category_product_data[$k][$kk]['UserLike']=isset($UserLike_data[$vv['Product']['id']])?'1':'0';
					$category_product_data[$k][$kk]['UserFavorite']=isset($UserFavorite_data[$vv['Product']['id']])?'1':'0';
					$brand_id=$vv['Product']['brand_id'];
					$category_product_data[$k][$kk]['Brand']=isset($brand_data[$brand_id])?$brand_data[$brand_id]:'';
					$category_product_data[$k][$kk]['Attribute']=isset($attribute_product_data[$vv['Product']['id']])?$attribute_product_data[$vv['Product']['id']]:array();
				}
			}
			foreach($category_data as $k=>$v){
				if(isset($category_product_data[$k])){
					$category_data[$k]['Product']=$category_product_data[$k];
				}else{
					$category_data[$k]['Product']=array();
				}
			}
		}
		return $category_data;
	}
}
