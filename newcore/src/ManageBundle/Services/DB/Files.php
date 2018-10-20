<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年02月17日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 文件管理
* 
*/
class Files extends AbstractServiceManager
{
    protected $table = 'Files';

    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    
    /**
     * 本地上传
     * @param string $token
     * @param UserInterface $userinfo
     * @param string $upLoadPath
     * @param string $filetypes
     * @param string $wximg
     * @return multitype:number unknown
     */
    public function localUpload($token='', $upLoadPath='product', $filetypes = '')
    {
        $userinfo = parent::getUser();
        $token = $token ? $token : $userinfo->getUsername();
        $width = (int)$this->get('request')->get('width',0);
        $height = (int)$this->get('request')->get('height',0);

        //允许的后缀名
        if (!$filetypes)
            $this->get('fileuploader')->allowExts = explode(',', $this->get('core.common')->C('up_exts'));
        else
            $this->get('fileuploader')->allowExts = explode(',', $filetypes);

        //最大文件上传字节
        $this->get('fileuploader')->maxSize = intval($this->get('core.common')->C('up_file_size')) * 1024 * 1024;
        
        //最大图片上传字节
        $this->get('fileuploader')->maxPictureSize = intval($this->get('core.common')->C('up_image_size')) * 1024 * 1024;

        //是否生成缩略图
        if ($width>0&&$height>0)
        {
            $this->get('fileuploader')->thumbMaxWidth = abs($width);
            $this->get('fileuploader')->thumbMaxHeight = abs($height);
        }

        //已上传数量
        $upLoadCount = parent::count(array('token'=>$token));

        $upLoadCount = $upLoadCount>0?$upLoadCount:1;

        //上传路径
        $upLoadDir = $this->get('core.common')->getFileDir($userinfo->getId()).DIRECTORY_SEPARATOR;
        $upLoadDir .= $this->get('core.common')->getFileSubDir($upLoadCount);

        //上传动作
        $result = $this->get('fileuploader')->handleUploadRequest($upLoadPath, $upLoadDir);

        $msg = "";
        $error = 1;

        if(!$result['status'])
            $msg = $result['info'];
        else{
            if(is_array($result['filename']))
            {
                $error = 0;
                $uptotal = 0;
                $msg = implode(',', $result['filename']);
                $fileType = $this->get('fileuploader')->getFileType();

                foreach($result['filename'] as $key=>$item)
                {

                    $item = trim($item,'/');

                    //已上传过的文件不加入计算
                    $count = parent::count(array('token'=>$token,'url'=>$item));

                    if($count<=0)
                    {
                        $uptotal += (int)($result['size'][$key]/1024);

                        $data = array();
                        $data['type'] = $fileType[$key];
                        $data['size'] = $result['size'][$key];
                        $data['url'] = $item;
                        $data['token'] = $token;
                        $data['time'] = time();
                        $data['sync_url'] = '';
                        $data['media_id'] = '';

                        parent::add($data, null, false);
                    }
                }

                if($uptotal>0)
                {
                    $data = array();
                    $data['uptotal'] = $userinfo->getUptotal() + $uptotal;
                    $this->get('db.users')->update($userinfo->getId(), $data, null, false);
                }
            }
        }
        return array('error' => $error, 'msg' => $msg, 'name'=>implode(',', $result['name']));
    }

    /**
     * 阿里云上传
     * @param string $token
     * @param UserInterface $userinfo
     * @param string $upLoadPath
     * @param string $filetypes
     * @param string $wximg
     * @return multitype:number unknown
     */
    public function ossUpload($option,$token='', $upLoadPath='product', $filetypes = '')
    {
        $userinfo = $this->getUser();
        $token = $token ? $token : $userinfo->getUsername();
        $width = (int)$this->get('request')->get('width',0);
        $height = (int)$this->get('request')->get('height',0);

        //允许的后缀名
        if (!$filetypes)
            $this->get('fileuploader')->allowExts = explode(',', $this->get('core.common')->C('up_exts'));
        else
            $this->get('fileuploader')->allowExts = explode(',', $filetypes);

        //最大文件上传字节
        $this->get('fileuploader')->maxSize = intval($this->get('core.common')->C('up_file_size')) * 1024 * 1024;

        //最大图片上传字节
        $this->get('fileuploader')->maxPictureSize = intval($this->get('core.common')->C('up_image_size')) * 1024 * 1024;

        //是否生成缩略图
        if ($width>0&&$height>0)
        {
            $this->get('fileuploader')->thumbMaxWidth = abs($width);
            $this->get('fileuploader')->thumbMaxHeight = abs($height);
        }

        //上传动作
        $result = $this->get('fileuploader')->getName($upLoadPath);

        $this->get('core.ossupload')->uploadFile($option,$result);

        $msg = "";
        $error = 0;

        if(is_array($result['filename']))
        {
            $error = 0;
            $uptotal = 0;
            $msg = implode(',', $result['filename']);
            $fileType = $this->get('fileuploader')->getFileType();

            foreach($result['filename'] as $key=>$item)
            {
                $item = trim($item,'/');

                //已上传过的文件不加入计算
                $count = parent::count(array('token'=>$token,'url'=>$item));
                if($count<=0)
                {
                    $uptotal += (int)($result['size'][$key]/1024);

                    $data = array();
                    $data['type'] = $fileType[$key];
                    $data['size'] = $result['size'][$key];
                    $data['url'] = $item;
                    $data['token'] = $token;
                    $data['time'] = time();
                    $data['sync_url'] = '';
                    $data['media_id'] = '';
                    
                    parent::add($data, null, false);
                }
            }

            if($uptotal>0)
            {
                $data = array();
                $data['uptotal'] = $userinfo->getUptotal() + $uptotal;
                $this->get('db.users')->update($userinfo->getId(), $data, null, false);
            }
        }

        return array('error' => $error, 'msg' => $msg, 'name'=>implode(',', $result['name']));
    }

    /**
     * 远程上传
     * @param array $option
     * @param string $token
     * @param UserInterface $userinfo
     * @param string $upLoadPath
     * @param string $filetypes
     */
    public function remoteUpload($option, $token='', $upLoadPath='product', $filetypes = '')
    {
        $userinfo = parent::getUser();
        $token = $token ? $token : $userinfo->getUsername();
        $width = (int)$this->get('request')->get('width',0);
        $height = (int)$this->get('request')->get('height',0);
    
        //允许的后缀名
        if (!$filetypes)
            $this->get('fileuploader')->allowExts = explode(',', $this->get('core.common')->C('up_exts'));
        else
            $this->get('fileuploader')->allowExts = explode(',', $filetypes);
    
        //最大文件上传字节
        $this->get('fileuploader')->maxSize = intval($this->get('core.common')->C('up_file_size')) * 1024 * 1024;
        
        //最大图片上传字节
        $this->get('fileuploader')->maxPictureSize = intval($this->get('core.common')->C('up_image_size')) * 1024 * 1024;

        //是否生成缩略图
        if ($width>0&&$height>0)
        {
            $this->get('fileuploader')->thumbMaxWidth = abs($width);
            $this->get('fileuploader')->thumbMaxHeight = abs($height);
        }
        
        //上传动作
        $result = $this->get('fileuploader')->handleRemoteUploadRequest($option);

        $msg = "";
        $error = 1;

        if (!$result['status'])
            $msg = $result['info'];
        elseif (is_array($result['filename']))
        {
            $error = 0;
            $uptotal = 0;
            $msg = implode(',', $result['filename']);
            $fileType = $this->get('fileuploader')->getFileType();

            foreach($result['filename'] as $key=>$item)
            {
                $item = trim($item,'/');

                //已上传过的文件不加入计算
                $count = parent::count(array('token'=>$token,'url'=>$item));

                if($count<=0)
                {
                    $uptotal += (int)($result['size'][$key]/1024);

                    $data = array();
                    $data['type'] = $fileType[$key];
                    $data['size'] = $result['size'][$key];
                    $data['url'] = $item;
                    $data['token'] = $token;
                    $data['time'] = time();
                    $data['sync_url'] = '';
                    $data['media_id'] = '';
                    parent::add($data, $info, false);
                }
            }

            if($uptotal>0)
            {
                $data = array();
                $data['uptotal'] = $userinfo->getUptotal() + $uptotal;
                $this->get('db.users')->update($userinfo->getId(), $data, null, false);
            }
        }
        
        return array('error' => $error, 'msg' => $msg, 'name'=>implode(',', $result['name']));
    }

    /**
     * 删除附件
     * @param int $id
     * @param string $token
     * @param string $upLoadPath
     */
    public function deletematerial($id, $token, $upLoadPath='product')
    {
        $info = parent::findOneBy(array('id'=>$id, 'token'=>$token));
        
        if(!is_object($info))
            throw new \LogicException('数据不存在或已被删除');
        
        parent::delete($id, $info);
        
        $this->get('fileuploader')->removeFile($upLoadPath, $info->getUrl());
    }

    /**
     * 同步附件
     */
    public function syncmaterial()
    {
        $batchData = array();

        $token = $this->get('request')->getSession()->get('wxtoken');

        parent::dbalUpdate(array('is_sync'=>0),array('token'=>$token));

        $materialCount = $this->get('oauth.weixin_material')->getMaterialCount();

        unset($materialCount['news_count']);

        foreach($materialCount as $key => $value)
        {
            $typearr = explode('_', $key);

            if(empty($value))
                continue;

            for($i=0;$i<ceil($value/20);$i++)
            {
                $materialType = $this->get('oauth.weixin_material')->getMaterialList($typearr[0], 20, ($i*20));

                foreach($materialType['item'] as $item)
                {
                    if(empty($item['url']))
                        continue;

                    $map = array();
                    $map['media_id'] = $item['media_id'];
                    $map['token'] = $token;
                    $count = parent::findOneBy($map);

                    $item['type'] = $typearr[0];
                    $item['token'] = $token;
                    $item['filetype'] = 'weixin';
                    $item['is_sync'] = 1;

                    unset($item['update_time']);

                    if($count)
                        parent::update($count->getId(),$item, null, false);
                    else
                        $batchData[] = $item;
                }
            }
        }
        
        if(count($batchData)>0)
            parent::batchadd($batchData);

        //删除已清掉的
        $map['token'] = $token;
        $map['is_sync'] = 0;
        parent::dbalDelete($map);

        return true;
    }

    public function getFileMime($path)
    {
        if(!is_file($path))
            return false;

        $typearr = array(
            'png'  => array('image', 'image/png'),
            'jpg'  => array('image', 'image/jpg'),
            'jpeg' => array('image', 'image/jpeg'),
            'gif'  => array('image', 'image/gif'),
            'mp3'  => array('voice', 'voice/mp3'),
            'mp4'  => array('video', 'video/mp4'),
            'avi'  => array('video', 'video/avi'),
            'flv'  => array('video', 'video/flv'),
        );

        $filetype = pathinfo($path,PATHINFO_EXTENSION);

        return array_key_exists($filetype, $typearr) ? $typearr[$filetype] : false;
    }
}