<?php
namespace CoreBundle\Twig\Extension;

use Symfony\Component\DependencyInjection\ContainerInterface;

class QRCodeUrlExtension extends BaseExtension
{
    /**
     * {@inheritdoc}
     */
    protected $container;

    
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('qrcode_url', array($this, 'qRCodeUrlFilter')),
            new \Twig_SimpleFilter('qrcode_base64', array($this, 'qRCodeBase64Filter'))
        );
    }
    
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('qrcode_url', array($this, 'qRCodeUrlFunction')),
            new \Twig_SimpleFunction('qrcode_base64', array($this, 'qRCodeBase64Function')),
        );
    }
    
    /**
     * Get the URL for the QRCode with the specified text, format and size
     *
     * @param string $text
     * @param int $size
     * @param string $format
     * @return string The URL for the QRCode
     */
    public function qRCodeUrlFilter($text = '', $size = 3, $format = 'png')
    {
        return $this->generateUrl($text, $size, $format);
    }
    
    /**
     * Get the Base64 data for the QRCode with the specified text, format and size
     *
     * @param string $text
     * @param int $size
     * @param string $format
     * @return string The URL for the QRCode
     */
    public function qRCodeBase64Filter($text = '', $size = 3, $format = 'png')
    {
        return $this->generateBase64($text, $size, $format);
    }
    
    /**
     * Get the URL for the QRCode with the specified text, format and size
     *
     * @param string $text
     * @param int $size
     * @param string $format
     * @return string The URL for the QRCode
     */
    public function qRCodeUrlFunction($text = '', $size = 3, $format = 'png')
    {
        return $this->generateUrl($text, $size, $format);
    }
    
    /**
     * Get the Base64 data for the QRCode with the specified text, format and size
     *
     * @param string $text
     * @param int $size
     * @param string $format
     * @return string The URL for the QRCode
     */
    public function qRCodeBase64Function($text = '', $size = 3, $format = 'png')
    {
        return $this->generateBase64($text, $size, $format);
    }
    
    private function generateUrl($text = '', $size = 3, $format)
    {
        $options = $this->container->getParameter('qrcode');
        $isAbsoluteUrl = $options['absolute_url'];

        //$text = urlencode($text);
        //$text = str_replace('.', '%2E', $text);
        //$text = str_replace('-', '%2D', $text);
        $url = $this->get('router')->generate(
            'qrcode_url',
            array(
                'text' => $text,
                '_format' => $format,
                'size' => $size,
            ),
            $isAbsoluteUrl
        );

        return $url;
    }
    
    private function generateBase64($text = '', $size = 3, $format = 'png')
    {
        return "data:image/$format;base64," . $this->get('core.qrcode')->getQRCodeBase64($text, $size, $format);
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'qrcode_url_extension';
    }
}
