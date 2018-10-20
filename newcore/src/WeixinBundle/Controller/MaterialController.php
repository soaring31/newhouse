<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月07日
*/
namespace WeixinBundle\Controller;

/**
* 素材管理
* @author admina
*/
class MaterialController extends Controller
{
    /**
    * 同步更新
    * admina
    */
    public function wxupdateAction()
    {
        $this->get('db.files')->syncmaterial();
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现preview方法
    * admina
    */
    public function previewAction()
    {
        $id = $this->getRequest()->get('id', '');
        $result = $this->get('db.files')->findOneBy(array('id'=>$id));

        /*$weburl = $this->get('request')->getHttpHost();
        $webpath = $this->get('core.common')->getWebRoot();

        #语言、视频、图片下载等等
        $filename = $result->getToken().'_'.$id.'.png';
        $filepath = $webpath.'preview\\'.$filename;

        if(!is_file($filepath)){
        	$this->get('oauth.weixin_material')->getEverMaterial($result->getMediaId(), $result->getType(), $filepath);
        }
        //str_replace("http","http",$result->getUrl());*/
        
        $this->parameters['url'] = $result->getUrl();
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的show方法
    * admina
    */
    public function showAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的save方法
    * admina
    */
    public function saveAction()
    {
    	$pathBase = $this->get('core.common')->getWebRoot();

        if(!empty($_POST) && $this->get('request')->getMethod() == "POST")
        {
        	$filedata = $this->get('db.files')->findOneBy(array('url'=>$_POST['upfile']));
        	$path = rtrim($pathBase,'\\').$_POST['upfile'];
        	$contentype = $this->get('db.files')->getFileMime($path);

        	if(empty($contentype))
        		throw new \InvalidArgumentException('文件类型错误或不存在！');

        	$upload = array(
        		'filename'=> $path, 
        		'content-type'=> $contentype[1], 
        		'filelength'=> $filedata->getSize(),
        	);
        	if($contentype[0] == 'video'){
        		$upload['title'] = $_POST['upfile'];
        		$upload['introduction'] = $_POST['introduction'];
        	}

    		$this->get('oauth.weixin_material')->addEverMaterial($upload, $contentype[0]);
        	$this->get('db.files')->syncmaterial();

        	$this->cleanCace();
            return $this->success('添加成功');
        }
        
        return $this->error('操作失败');
    } 

    /**
    * 实现的delete方法
    * admina
    */
    public function deleteAction()
    {
        $id = $this->get('request')->get('id', '');

        $result = $this->get('db.files')->findOneBy(array('id' => $id));

        if(!$result->getUrl())
        	$this->error('这条素材不是合法的应用素材！');

        $this->get('oauth.weixin_material')->deleteEverMaterial($result->getMediaId());

        $this->get('db.files')->delete($id);

        return $this->success('操作成功');
    }
}