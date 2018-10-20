<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月21日
*/
namespace ManageBundle\Controller;

/**
*
* @author admina
*/
class SitemapmanageController extends Controller
{

    /**
    * 实现的save方法
    * admina
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $id = $this->get('request')->get('id', 0);

            if($id>0)
                $this->get('db.sitemap')->update($id, $_POST);
            else
                $this->get('db.sitemap')->add($_POST);

            return $this->success('操作成功');
        }

        return $this->error('操作失败');
    }

    /**
    * 实现的delete方法
    * admina
    */
    public function deleteAction()
    {
        $id = $this->get('request')->get('id', 0);
        $this->get('db.sitemap')->delete($id);
        return $this->success('操作成功');
    }

    /**
    * 实现的show方法
    * admina
    */
    public function showAction()
    {

        return $this->render($this->getBundleName(), $this->parameters);
    }

    public function consitemap()
    {
    	$id = $this->get('request')->get('_id', 0);
    	$sitemap = $this->get('db.sitemap')->findOneBy(array('id'=>$id));
    	if(!is_object($sitemap))
    		throw new \InvalidArgumentException('不存在的记录！');
    	if(!$sitemap->getStatus())
    		throw new \InvalidArgumentException('该Sitemap地图未启用');
    	return $sitemap;
    }

    public function createAction()
    {
    	$sitemap = $this->consitemap();
    	$sitemapbind = $this->get('db.sitemapbind')->findBy(array('sitemapid'=>$sitemap->getId()));
    	if(empty($sitemapbind))
    		throw new \InvalidArgumentException('您还没有绑定模型，没有推送数据！');
    	$result = array('info'=>array());
    	foreach($sitemapbind['data'] as $item){
    		if($item->getParamts()){
    			$where = $this->get('core.common')->getQueryParam($item->getParamts());
    			$order = isset($where['order']) ? array($where['order'] => (isset($where['orderBy']) ? $where['orderBy'] : 'DESC')) : array();
    			unset($where['order']);
    			unset($where['orderBy']);
    		}
    		try{
    			$items = $this->get($item->getName())->findBy(isset($where)?$where:array(), isset($order)?$order:array(), $item->getNumbers());
    			foreach($items['data'] as $value){
    				$result['info'][] = $this->get('request')->getHttpHost().$item->getUrl().'?id='.$value->getId();
    			}
    		}catch(\Exception $e){
    			throw new \InvalidArgumentException('您绑定的模型有错误，请检查是否有数据！');
    		}

    	}
    	$this->get('core.sitemap')->exec($sitemap, $result);
    	$this->success('操作成功');
    }

    public function pushsitemapAction()
    {
    	$sitemap = $this->consitemap();
    	if(!function_exists('simplexml_load_string') || !function_exists('curl_init'))
    		throw new \InvalidArgumentException('simplexml_load_string或curl_init函数不可用,请设置php.ini');
		$xml = simplexml_load_string(@file_get_contents($this->get('core.common')->getWebRoot().$sitemap->getXmlurl()));
		//$json = json_encode($xml);
		//$array = json_decode($json,TRUE);dump($array);exit();//这里两步是用json将xml转为数组，下面是推送的其实是xml文件，百度默认是推送url组成的字符串('\n')。
		if(empty($xml->url))
			throw new \InvalidArgumentException('没有正确的xml文件数据，请检查是否生成数据以及XML模版是否正确！');
		//$api = $this->get('core.common')->C('baidu_map_api');
		$mconfig = $this->get('db.mconfig')->findOneBy(array('name'=>'initiativepost'));
		$api = is_object($mconfig)? $mconfig->getValue():'';
		if(empty($api))
			throw new \InvalidArgumentException('没有填写百度推送api链接！');
		$ch = curl_init();
		$options =  array(
				CURLOPT_URL => $api,
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POSTFIELDS => $this->get('request')->getHttpHost().'/web/'.$sitemap->getXmlurl(),
				CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
		);
		curl_setopt_array($ch, $options);
		$result = curl_exec($ch);
		//$message = json_decode($result); //序列化返回字符串。
		strpos($result, 'success') ? $this->success('推送成功>>>'.$result) : $this->error('推送失败,请检查百度推送api是否正确');
	}
}