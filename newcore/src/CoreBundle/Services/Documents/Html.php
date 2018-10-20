<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-4-21
*/
namespace CoreBundle\Services\Documents;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 
 */
class Html extends ServiceBase
{
    private $html = '';
    private $items = array();
	
	protected $container;
    
    public function __construct(ContainerInterface $container)
    {
		$this->container = $container;
    }
	
	public function init($html)
	{
		$this->html = $html;
	}
	
    /**
     * 类似于JQuery的选择器，目前只支持选择DOM名称
     * 
     * @param mixed $items DOM名称（可用字符串或数组传递方式，字符串以英文逗号分隔）
     **/
    public function pQuery($items)
    {
        if (is_string($items))
        {
            $temp = explode(',', $items);
			$items = array();
			foreach($temp as $item){
				$items = array_merge($items,explode('_',$item));
			}
        }
        else
        {
            $items = (array) $items;
        	foreach ($items as &$item)
            {
                if (is_string($item) && (false !== strpos($item, ',')))
                {
                    $item = explode(',', $item);
                }
                
                if (is_string($item) && (strtolower($item) === 'h'))
                {
                    $item = array();
                    for ($i = 1; $i <= 7; ++$i)
                    {
                        $item[] = "h{$i}";
                    }
                }
            }
            
            $items = self::_array_multi_to_one($items);
        }        
        
        $this->items = array_unique(array_map('strtolower', array_map('trim', $items)));
    }
    
    private function _array_multi_to_one ( array $arrays, $retentionKey = false )
    {
        static $_one_array = array();
        foreach($arrays as $key => $array)
        {
            if ( is_array($array) )
            {
                self::_array_multi_to_one($array);
            }
            else
            {
                if ( $retentionKey )
                {
                    $_one_array[$key] = $array;
                }
                else
                {
                	$_one_array[] = $array;
                }
            	
            }
        }
        
        return $_one_array;
    }
	
    /**
     * 删除节点
     * 
     * @param  bool   $clearText 当前清除一个标签时，是否清除标签里面的内容，TRUE为清除，FALSE为不清除
     * @return string $text      返回清除后的内容
     **/
    public function remove($clearText = false)
    {
        $text = $this->html;
        
        if (in_array('all', $this->items))
        {
            $text = $this->get('core.common')->htmlClear($text);
        }
        else
        {
            $trimKey = array_keys($this->items, 'trim');
        	if ($trimKey)
            {
                $text = trim($text);
                unset($this->items[$trimKey[0]]);
            }
            
            $tabKey = array_keys($this->items, 'tab');
            if ($tabKey)
            {
                $text = str_replace(array("\n", "\r", "\t"), '', $text);
                unset($this->items[$tabKey[0]]);
            }
            
            $nbspKey = array_keys($this->items, 'nbsp');
            if ($nbspKey)
            {
                $text = str_replace('&nbsp;', '', $text);
                unset($this->items[$tabKey[0]]);
            }
            
            $regexp = '';
            foreach ($this->items as $item)
            {
                if (empty($item))
                    continue;
                
                if ($regexp)
                    $regexp .= '|';
                
                if (in_array($item, array('br', 'img', 'hr', 'input', 'meta', 'link')))
                {
                    $regexp .= "<{$item}.*>";
                }else{
                    if ($clearText || ($item === 'script'))
                        $split = '.*';
                    else
                    	$split = '|';
                    
                	$regexp .= "<{$item}.*>$split</$item>";
                }                
            }
            
            $text = str_replace(array('<!cmsurl />', '<!ftpurl />'), array('[--08CMS-cmsurl-]', '[--08CMS-ftpurl-]'), $text);
            $text = preg_replace("@{$regexp}@isU", '', $text);
            $text = str_replace(array('[--08CMS-cmsurl-]', '[--08CMS-ftpurl-]'), array('<!cmsurl />', '<!ftpurl />'), $text);
        }        
        
       # $this->html = $text;
        
        return $text;
    }
    
    public function getInstance($html)
    {        
        return new self($html);
    }

}

