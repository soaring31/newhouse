<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月31日
*/
namespace ManageBundle\Controller;

/**
* 附件上传
* @author admina
*/
class AttachmentController extends Controller
{
    public function upFileAction()
    {
        $fileData = $this->get('request')->get('fileData', '');
        
        if(empty($fileData))
            return $this->error('上传参数错误');
        
        if(filesize($fileData)>$this->get('core.common')->C('file_size'))
        {
            
        }
    }
}