<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年05月31日
*/
namespace HouseBundle\Controller;
        
/**
* 附件上传
* @author admina
*/
class AttachmentController extends Controller
{
    public function _initialize()
    {
        $this->parameters['navurl'] = array();
    }

    /**
    * 上传保存
    * house
    */
    public function upFileAction()
    {
       if($this->get('request')->getMethod() == "POST")
       {
            if(empty($_FILES))
                return $this->error('上传参数错误');
         
            $this->parameters = $this->get('db.files')->localUpload($this->token, $this->get('core.common')->getUserDb());

            return $this->success($this->parameters);
       }
    }
}