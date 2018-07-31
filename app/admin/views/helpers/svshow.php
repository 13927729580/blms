<?php

/*****************************************************************************
 * Seevia svshow
 *===========================================================================
 * 版权所有上海实玮网络科技有限公司，并保留所有权利。
 * 网站地址: http://www.seevia.cn
 *---------------------------------------------------------------------------
 *这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和使用；
 *不允许对程序代码以任何形式任何目的的再发布。
 *===========================================================================
 * $开发: 上海实玮$
 * $Id$
*****************************************************************************/

class SVshowHelper extends HTMLHelper
{
    //构造函数
    public function SVshowHelper()
    {
        $this->themes_host = Configure::read('themes_host');
        if (substr($this->themes_host, -1) == '/') {
            $this->themes_host = substr($this->themes_host, 0, -1);
        }
        $this->themes_host=$this->webroot;
    }

    //重载link方法
    public function link($title, $url = null, $options = array(), $confirmMessage = false)
    {
        $escapeTitle = false;//pr($url);
        if ($url !== null) {
            //$url = $this->url($url);
        } else {
            $url = $this->url($title);
            $title = $url;
            $escapeTitle = false;
        }
        if (isset($options['escape'])) {
            $escapeTitle = $options['escape'];
        }

        if ($escapeTitle === true) {
            $title = h($title);
        } elseif (is_string($escapeTitle)) {
            $title = htmlentities($title, ENT_QUOTES, $escapeTitle);
        }

        if (!empty($options['confirm'])) {
            $confirmMessage = $options['confirm'];
            unset($options['confirm']);
        }
        if ($confirmMessage) {
            $confirmMessage = str_replace("'", "\'", $confirmMessage);
            $confirmMessage = str_replace('"', '\"', $confirmMessage);
            $options['onclick'] = "return confirm('{$confirmMessage}');";
        } elseif (isset($options['default']) && $options['default'] == false) {
            if (isset($options['onclick'])) {
                $options['onclick'] .= ' event.returnValue = false; return false;';
            } else {
                $options['onclick'] = 'event.returnValue = false; return false;';
            }
            unset($options['default']);
        }

        return sprintf($this->tags['link'], $url, $this->_parseAttributes($options), $title);
    }

    //链接地址特殊字符格式化
    public function url_name_format($name)
    {
        $name = str_replace('\\', '-', $name);
        $name = str_replace(',', '-', $name);
        $name = str_replace('&', '-', $name);
        $name = str_replace('?', '-', $name);
        $name = str_replace('/', '-', $name);
        $name = str_replace(' ', '-', $name);

        return urlencode($name);
    }

    //金额格式化
    public function price_format($price, $config, $rate = 1)
    {
        $price = round($price, 2);

        return sprintf($config, $price * $rate);
    }

    //获取图片路径
    public function image_path($path)
    {
        if (empty($path)) {
            $path = '/theme/default/img/default.jpg';
        }
        if (strpos($path, '://') === false) {
            if ($path[0] !== '/') {
                $path = $this->themes_host.'/themed/'.$this->theme.'/img/'.$path;
            } else {
                $path = $this->themes_host.$path;
            }
            //$path = $this->assetTimestamp($this->webroot($path));
        }

        return $path;
    }

    //获取图片标签
    public function image($path, $options = array())
    {
        $path = $this->image_path($path);

        if (!isset($options['alt'])) {
            $options['alt'] = '';
        }

        $url = false;
        if (!empty($options['url'])) {
            $url = $options['url'];
            unset($options['url']);
        }

        $image = sprintf($this->tags['image'], $path, $this->_parseAttributes($options, null, '', ' '));
        if ($url) {
            return sprintf($this->tags['link'], $this->url($url), null, $image);
        }

        return $image;
    }

    //seo 取链接地址
    public function seo_link_path($options = array())
    {
        $path = '';
        if (!empty($options['type'])) {
            if ($options['name'] == '' && $options['sub_name'] != '') {
                $options['name'] = $options['sub_name'];
            }
            switch ($options['type']) {
                case 'T': //商品详细页
                    $path = 'topics/'.$options['id'].'';
                    break;
                case 'P': //商品详细页
                    $path = 'products/'.$options['id'].'';
                    break;
                case 'PC': //商品分类页
                    $path = 'categories/'.$options['id'].'';
                    break;
                case 'A': //文章详细页
                    $path = 'articles/'.$options['id'].'';
                    break;
                case 'V'://视频页
                    $path = 'articles/video/'.$options['id'].'';
                    break;
                case 'AC': //文章分类页
                    $path = 'articles/category/'.$options['id'].'';
                    break;
                case 'TP': //文章分类页
                    $path = 'topics/'.$options['id'].'';
                    break;
                case 'BV': //品牌页
                    $path = 'brands/'.$options['id'].'';
                    break;
                case 'SM': //前台
                    $path = $options['id'].'';
                    break;
                default :
                    $path = '';
            }
        }
        $server_host = isset($_SERVER['HTTP_X_FORWARDED_HOST']) ? $_SERVER['HTTP_X_FORWARDED_HOST'] : (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '');
        $webroot=$this->webroot;
        return 'http://'.$server_host.$webroot.$path;
    }
    //seo 取链接地址
    public function seo_link_url($options = array())
    {
        $path = '';
        if (!empty($options['type'])) {
            switch ($options['type']) {
                    case 'P': //商品详细页
                        $path = 'products/'.$options['id'].'';
                        break;
                    case 'PC': //商品分类页
                        $path = 'categories/'.$options['id'].'';
                        break;
                    case 'A': //文章详细页
                        $path = 'articles/'.$options['id'].'';
                        break;
                    case 'V': //视频页
                        $path = 'articles/video/'.$options['id'].'';
                        break;
                    case 'AC': //文章分类页
                        $path = 'articles/category/'.$options['id'].'';
                        break;
                    case 'TP': //文章分类页
                        $path = 'topics/'.$options['id'].'';
                        break;
                    case 'BV': //品牌页
                        $path = 'brands/'.$options['id'].'';
                        break;
                    default :
                        $path = '';
                }
        }
	$webroot=$this->webroot;
        return $this->url($webroot.$path);
    }
    //seo 取链接
    public function seo_link($options = array())
    {
        $path = $this->seo_link_path($options);
        $sub_name = empty($options['sub_name']) ? $options['name'] : $options['sub_name'];
        $sub_name = htmlspecialchars($sub_name);
        if (isset($options['img'])) {
            if (isset($options['style'])) {
                $sub_name = $this->image($options['img'], array('alt' => $options['name'], 'style' => $options['style']));
            } else {
                $sub_name = $this->image($options['img'], array('alt' => $options['name']));
            }
        }

        return $this->link($sub_name, $path, array('title' => $options['name'], 'target' => '_blank'));
    }

    //替换地址
  public function url_replace($options = array(), $defaults = array())
  {
      $url = '/';
      $defaults = $defaults;
      foreach ($options as $k => $v) {
          if (isset($defaults[$k])) {
              $defaults[$k] = $v;
          }
      }
      if ($defaults) {
          $url .= implode('/', $defaults);
      }

      return $this->url($url);
  }

  //管理员权限检查
    public function operator_privilege($action_code)
    {
        //	pr(ClassRegistry::getObject('view')->viewVars['admin']);
        $admin = ClassRegistry::getObject('view')->viewVars['admin'];
        if ($admin['actions'] == 'all') { //
            return true;
        } elseif (in_array($action_code, $admin['action_codes'])) {
            return true;
        } else {
            return false;
        }
    }

  //系统模块检查
    public function check_module($system,$module='')
    {
        //	pr(ClassRegistry::getObject('view')->viewVars['admin']);
        $system_modules = ClassRegistry::getObject('view')->viewVars['system_modules'];
        if($module==''){
        	 return isset($system_modules[$system]);
        }else{
        	return isset($system_modules[$system]['modules'][$module]);
        }
        
    }
    
  
}
