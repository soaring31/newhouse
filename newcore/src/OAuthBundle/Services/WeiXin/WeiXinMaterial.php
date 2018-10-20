<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月23日
*/
namespace OAuthBundle\Services\WeiXin;

class WeiXinMaterial extends WeiXin
{

    private $addMaterialUrl      = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token=%s&type=%s';
    private $getMaterialUrl      = 'https://api.weixin.qq.com/cgi-bin/media/get?access_token=%s&media_id=%s';
    private $getMaterialCountUrl = 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=%s';
    private $getMaterialListUrl  = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=%s';
    private $getEverMaterialUrl  = 'https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=%s';
    private $deleteEverMaterialUrl  = 'https://api.weixin.qq.com/cgi-bin/material/del_material?access_token=%s';
    private $updateNewsMaterialUrl  = 'https://api.weixin.qq.com/cgi-bin/material/update_news?access_token=%s';
    private $addNewsMaterialUrl  = 'https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=%s';
    private $addImageMaterialUrl = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=%s';
    private $addEverMaterialUrl  = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=%s&type=%s';

    /**
     * 新增临时素材(返回media_id)
     *
     * @param array   $file_info      文件绝对路径、文件类型、文件大小组成的数组
     * @param string  $type           文件类型，有图片(image)语音(voice)视频(video)和缩略图(thumb)
     */
    public function addMaterial(array $file_info, $type)
    {
        $options = array();
        $options['me_url'] = sprintf($this->addMaterialUrl, parent::getAccessToken(), isset($type)?$type:'image');
        $this->resourceOwner->setOption($options);

        $data =array();
        $data['media'] = $file_info['filename'];
        
        $info = $this->resourceOwner->uploadRequest($options['me_url'], $data, array('media'));
        parent::getError($info);
        
        return $info;
    }

    /**
     * 获取临时素材
     *
     * @param string  $media_id          媒体文件ID
     */
    public function getMaterial($media_id)
    {
        $options = array();
        $options['me_url'] = sprintf($this->getMaterialUrl, parent::getAccessToken(), $media_id);
        $this->resourceOwner->setOption($options);     

        $info = $this->resourceOwner->getMeUrl();
        parent::getError($info);

        return $info;
    }

    /**
     * 新增永久图文素材
     *
     * @param array   $articles      图文消息数据
     */
    public function addEverNewsMaterial(array $article)
    {
        $options = array();
        $options['me_url'] = sprintf($this->addNewsMaterialUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['articles'] = $article;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);
        
        return $info;
    }

    /**
     * 修改永久图文素材
     *
     * @param string  $media_id      要修改的图文消息的id
     * @param int     $index         要更新的文章在图文消息中的位置（多图文消息时，此字段才有意义）第一篇为0
     * @param array   $articles      图文素材数据
     */
    public function updateEverNewsMaterial($media_id, array $articles, $index = 0)
    {
        $options = array();
        $options['me_url'] = sprintf($this->updateNewsMaterialUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['media_id'] = $media_id;
        $data['index'] = empty($index) ? $index : ($index - 1);
        $data['articles'] = $articles;
        $data = urldecode(json_encode($data));
    
        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;
        parent::getError($info);
        
        return $info;
    }

    /**
     * 上传群发图片并返回图片URL(这里上传的图片不占用公众号的素材库中图片数量的5000个限制)
     * 
     * @param array   $file_info      图片绝对路径、图片类型、图片大小组成的数组
     */
    public function uploadImage(array $file_info)
    {
        $options = array();
        $options['me_url'] = sprintf($this->addImageMaterialUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data =array();
        $data['media'] = $file_info['filename'];
        
        $info = $this->resourceOwner->uploadRequest($options['me_url'], $data, array('media'));
        parent::getError($info);
        
        return $info;
    }

    /**
     * 新增其他类型永久素材
     *
     * @param array   $file_info      文件绝对路径、文件类型、文件大小组成的数组
     * @param string  $type           文件类型，有图片(image)语音(voice)视频(video)和缩略图(thumb)
     */
    public function addEverMaterial(array $file_info, $type)
    {
        $options = array();
        $options['me_url'] = sprintf($this->addEverMaterialUrl, parent::getAccessToken(), isset($type)?$type:'image');
        $this->resourceOwner->setOption($options);

        $data =array();
        $data['media'] = $file_info['filename'];
        $key = array('media');
        if($type == 'video'){
            $data['description'] = array("title" => $file_info['title'], "introduction" => $file_info['introduction']);
            $key = array('media', 'description');
        }
        
        $info = $this->resourceOwner->uploadRequest($options['me_url'], $data, $key);
        parent::getError($info);
        
        return $info;
    }

    /**
     * 获取永久素材(除了news/video类型，其它的都是直接下载文件)
     *
     * @param string  $media_id          媒体文件ID
     * @param string  $type              媒体文件类型(image)等等
     * @param string  $path              媒体文件保存路径
     */
    public function getEverMaterial($media_id, $type, $path = null)
    {
        $options = array();
        $options['me_url'] = sprintf($this->getEverMaterialUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);     

        $data =array();
        $data['media_id'] = trim($media_id);
        $data = json_encode($data);

        if($type == 'video' || $type == 'news'){
            $info = $this->resourceOwner->getMeUrl($data);
        }else{
            $result = $this->resourceOwner->httpRequest($options['me_url'], $data);
            $pathfile = is_null($path) ? substr($media_id, 0, 10).time().'.png' : $path;
            file_put_contents($pathfile, $result->getContent());  //默认在web目录下
            return $pathfile;
        }
        parent::getError($info);

        return $info;
    }

    /**
     * 删除永久素材
     *
     * @param string  $media_id          媒体文件ID
     */
    public function deleteEverMaterial($media_id)
    {
        $options = array();
        $options['me_url'] = sprintf($this->deleteEverMaterialUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);     

        $data = array();
        $data['media_id'] = trim($media_id);
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;
        parent::getError($info);

        return $info;
    }

    /**
     * 获取素材总数
     *
     */
    public function getMaterialCount()
    {
        $options = array();
        $options['me_url'] = sprintf($this->getMaterialCountUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);     

        $info = $this->resourceOwner->getMeUrl();
        parent::getError($info);

        return $info;
    }

    /**
     * 获取素材列表(永久素材)
     *
     * @param string  $type          素材的类型，图片(image)等等。
     * @param int     $offset        从全部素材的该偏移位置开始返回，0表示从第一个素材返回
     * @param int     $count         返回素材的数量，取值在1到20之间
     */
    public function getMaterialList($type, $count, $offset = 0)
    {
        $options = array();
        $options['me_url'] = sprintf($this->getMaterialListUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);     

        $data =array();
        $data['type'] = trim($type);
        $data['offset'] = (int)$offset;
        $data['count'] = (int)$count;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);

        parent::getError($info);

        return $info;
    }

}