<?php
/**
* @copyright Copyright (c) 2008 – 2017 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2017年5月15日
*/
namespace HouseBundle\Controller;

class MsglogController extends Controller
{
    public function previewAction()
    {
        $map = array();
        $map['id'] = $this->get('request')->get('id',0);
        $msglog = $this->get('db.msglog')->findOneBy($map);
        
        if(!is_object($msglog))
            return $this->error('该数据不存在或已被删除');
            
        $content = json_decode($msglog->getContent(), true);
        $tpl = isset($content['tpl'])?$content['tpl']:'';
        
        if(empty($tpl))
            return $this->error('无效的模版文件');
        
        if (!preg_match('/.html.twig$/', $tpl))
            $tpl = $tpl.".html.twig";

        switch($msglog->getType())
        {
            case 'mobile':
                if (!preg_match('/^Mobile/', $tpl))
                    $tpl = "Mobile/".$tpl;
                break;
            case 'mail':                
                if (!preg_match('/^Emails/', $tpl))
                    $tpl = "Emails/".$tpl;
                break;
        }
        return parent::render1($tpl, $content);
    }
}