<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016-06-20
 */
namespace OAuthBundle\Buzz\Message\Form;

use OAuthBundle\Buzz\Message\MessageInterface;

interface FormUploadInterface extends MessageInterface
{
    public function setName($name);
    public function getFile();
    public function getFilename();
    public function getContentType();
}
