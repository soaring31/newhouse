<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package Tripod
* create date 2015-7-9
*/
namespace CoreBundle\Twig\Extension;

class FileuploadJSExtension extends \Twig_Extension
{
    private $twig;

    public function __construct($twig)
    {
        $this->twig = $twig;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('fileupload_js', array($this, 'fileupload_js'))
        );
    }

    public function fileupload_js()
    {
        return $this->twig->render('@Core/file_upload_js.html.twig');
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'fileupload_js_extension';
    }
}