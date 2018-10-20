<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月20日
*/
namespace WeixinBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 微信关键字
* 
*/
class Wxkeyword extends AbstractServiceManager
{
    protected $table = 'Wxkeyword';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    
    public function HandleKeyword($token, $keyword)
    {
        $reponse = array();
        
        if(empty($token) || empty($keyword))
            return $reponse;
        
        $map = array();
        $map['findType'] = 1;
        $map['token'] = $token;
        $map['keyword'] = $keyword;
        
        $info = parent::findOneBy($map);
        
        if(empty($info))
            return $reponse;
        
        
        $reponse['type'] = $info['replytype'];
        $reponse['content'] = $info['replytext'];

        return $reponse;
    }

    public function HandleKeywordback($token, $keyword)
    {
    	if(empty($token) || empty($keyword))
    		return false;

    	$keywords = parent::findBy(array('token'=>$token, 'findType' => 1), array('sort'=>'DESC'));
    	
    	if(!isset($keywords['data']))
    		return false;

    	foreach($keywords['data'] as $value)
    	{
    		foreach(explode("\r\n", $value['keyword']) as $item){
    			if(strpos($keyword, $item) !== false){
    				if(!empty($value['is_switch'])){
    					if($keyword !== $item)
                            continue;
    				}
    				if(empty($value['replytype']) || empty($value['reply'.$value['replytype']]))
    					return false;
    				$reponse = array(
			    		'type'   => $value['replytype'], 
			    		'content'=> $value['reply'.$value['replytype']]
			    	);
			    	if($reponse['type'] == 'news'){

			    		$reponse['articles'] = array();
			    		$articles = $this->get('db.wxnews')->findBy(array('media_id'=>$reponse['content'], 'findType' => 1), array('manyindex'=>'ASC'));
			    		if(empty($articles))
			    			return false;

			    		foreach($articles['data'] as $article){
			    			$thumburl = $this->get('db.files')->findOneBy(array('media_id' => $article['thumb_media_id'], 'token' => $token));
			    			$reponse['articles'][] = array(
			    				"Title" => $article['title'], 
			    				"Description" => $article['digest'],
			    				"PicUrl" => empty($thumburl) ? '' : $thumburl->getUrl(), 
			    				"Url" => $article['url']
			    			);
			    		}

			    	}
			    	return $reponse;
    			}
    		}
    	}
    	return false;
    }

}