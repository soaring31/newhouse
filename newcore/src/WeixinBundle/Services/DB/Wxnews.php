<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月12日
*/
namespace WeixinBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 图文素材
* 
*/
class Wxnews extends AbstractServiceManager
{
    protected $table = 'Wxnews';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    public function updataDb()
    {
    	$token = $this->get('request')->getSession()->get('wxtoken');
    	$table = parent::getTableName();
        $tableName = $this->get('core.common')->prefixName($table);

        $SQL = "UPDATE {$tableName} SET is_sync = 0 WHERE token='{$token}'";
        $stmt = parent::getConnection()->prepare($SQL);
        $stmt->execute();

        $materialCount = $this->get('oauth.weixin_material')->getMaterialCount();

        if(!empty($materialCount['news_count'])){
            for($i=0;$i<ceil($materialCount['news_count']/20);$i++)
            {
                $materialnews = $this->get('oauth.weixin_material')->getMaterialList('news', 20, ($i*20));

                foreach($materialnews['item'] as $value)
                {
                	$aiticles = $value['content']['news_item'];
                	if(array_key_exists(1, $aiticles)){
		    			foreach($aiticles as $key=>$item)
		    			{
		    				$map = array();
		                    $map['media_id'] = $value['media_id'];
		                    $map['manyindex'] = $key + 1;
		                    $count = parent::findOneBy($map);

		    				$item['manyindex'] = $key + 1;
		    				$item['token'] = $token;
		    				$item['is_sync'] = 1;
		    				$item['media_id'] = $value['media_id'];

		    				if($count)
		                        parent::update($count->getId(),$item);
		                    else
		                        parent::add($item);
		    			}
		    		}else{
		    			$map = array();
		                $map['media_id'] = $value['media_id'];
		                $map['token'] = $token;
		                $count = parent::findOneBy($map);

		    			$aiticles[0]['token'] = $token;
		    			$aiticles[0]['is_sync'] = 1;
		    			$aiticles[0]['media_id'] = $value['media_id'];

		    			if($count)
		                    parent::update($count->getId(),$aiticles[0]);
		                else
		                    parent::add($aiticles[0]);
		    		}
                }
            }
        }
        $mapdel = array();
        $mapdel['token'] = $token;
        $mapdel['is_sync'] = 0;
        $delInfo = parent::findBy($mapdel);

        if(isset($delInfo['data']))
        {
            foreach($delInfo['data'] as $item)
            {
                parent::delete($item->getId());
            }
        }

        return true;

    }

    public function deleteWxNews($id)
    {
    	$result = parent::findOneBy(array('id' => $id));
        $index = $result->getManyindex();
        if(!empty($index))
        {
            $manyNews = parent::findBy(array('media_id' => $result->getMediaId(), 'findType' => 1));
            unset($manyNews['data'][$index-1]);
            $this->get('oauth.weixin_material')->addEverNewsMaterial($manyNews['data']);
        }
        $this->get('oauth.weixin_material')->deleteEverMaterial($result->getMediaId());
        $this->updataDb();

        return true;
        
    }

    public function PreviewData($id)
    {
    	$result = array(parent::findOneBy(array('id'=>$id, 'findType'=>1)));

    	if(!empty($result[0]['manyindex']))
    	{
    		$manyNews = parent::findBy(array('media_id' => $result[0]['media_id'], 'findType' => 1));
    		$result = $manyNews['data'];
    	}

    	foreach($result as $key=>$item)
    	{
    		$thumburl = $this->get('db.files')->findOneBy(array('media_id' => $item['thumb_media_id']));
    		$item['thumb_media_id'] = empty($thumburl) ? '' : $thumburl->getUrl();
    		$result[$key] = $item;
    	}

    	return $result;
    }

}