<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年2月25日
*/
namespace CoreBundle\Form\Type;

use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ClonetplType extends BaseType
{
    protected $container;
    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if (!isset($options['info'])||!is_array($options['info'])||empty($options['info']))
            throw new LogicException('未定义info参数');
    }
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        //是否必填
        $view->vars['required'] = (bool)$options['info']['required'];
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $clonePath  = dirname($this->get('kernel')->getRootdir()).DIRECTORY_SEPARATOR;
        $clonePath .= "Template".DIRECTORY_SEPARATOR."CloneTpl".DIRECTORY_SEPARATOR."customTpl";
        
        $iterator = array();
        if (is_dir($clonePath))
        {
            $finder = new Finder();
            $_iterator = $finder
            ->files()
            ->name('*.html.twig.twig')
            ->sortByName()
            ->in($clonePath);
            
            $i = 0;
            foreach ($_iterator as $file) {
                $relativePath = "customTpl".DIRECTORY_SEPARATOR.$file->getRelativePath();
                $relativePath1 = $relativePath.DIRECTORY_SEPARATOR.$file->getBasename();
                $iterator[$relativePath][$relativePath1] = $relativePath1;
                $i++;
            }
        }
        $resolver->setDefaults(array(
            'info' => array()
            ,'choices' => $iterator
            ,'compound' => false
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'clonetpl';
    }
}