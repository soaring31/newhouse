<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月23日
*/
namespace CoreBundle\Services\Sms;

class ErrorCode
{
    public function getText()
    {
        return array(
            '1' => '请求成功',
            '0' => '请求失败',
        );
    }
}

