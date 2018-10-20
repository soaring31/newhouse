<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月11日
*/
namespace WeixinBundle\Controller;
        
/**
* 
* @author admina
*/
class MaterialnewsController extends Controller
{
   
   /**
    * 同步更新
    * admina
    */
    public function wxupdateAction()
    {
        $this->get('db.wxnews')->updataDb();
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现preview方法
    * admina
    */
    public function previewAction()
    {
        $id = $this->getRequest()->get('id', '');
        $this->parameters['data'] = $this->get('db.wxnews')->PreviewData($id);
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
        if($this->get('request')->getMethod() == "POST")
        {
            $ids = $this->get('request')->get('id', '');
            $article = $_POST;
            if($article['csrf_token'])
                unset($article['csrf_token']);

            $article = array_map(function($item){return urlencode($item);}, $article);
            if($ids){
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $result = $this->get('db.wxnews')->findOneBy(array('id'=>$id));
                    $this->get('oauth.weixin_material')->updateEverNewsMaterial($result->getMediaId(), $article, $result->getManyindex());                   
                    #$this->get('db.wxnews')->update($id, $_POST);
                }

            }else{
                $this->get('oauth.weixin_material')->addEverNewsMaterial(array($article));
                #$this->get('db.wxnews')->add($_POST);
            }

            $this->get('db.wxnews')->updataDb();
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
        $id = $this->get('request')->get('id', '');
        $this->get('db.wxnews')->deleteWxNews($id);
        return $this->success('操作成功');
    }
}