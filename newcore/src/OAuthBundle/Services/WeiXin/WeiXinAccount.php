<?php
/**
 * 帐号管理接口
 *
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年7月14日
*/
namespace OAuthBundle\Services\WeiXin;

class WeiXinAccount extends WeiXin
{
    /**
     * 生成带参数的二维码之创建二维码ticket接口Url
     * @var string
     */
    private $qrcodeUrl = 'https://api.weixin.qq.com/cgi-bin/qrcode/%s?access_token=%s';

    /**
     * 生成带参数的二维码之通过ticket换取二维码接口Url
     * @var string
     */
    private $showqrcodeUrl = 'https://api.weixin.qq.com/cgi-bin/showqrcode?ticket=%s';

    /**
     * 长链接转短链接接口Url
     * @var string
     */
    private $shortUrl = 'https://api.weixin.qq.com/cgi-bin/shorturl?access_token=%s';




    /**
     * 生成带参数的二维码之创建二维码ticket
     * 获取带参数的二维码的过程包括两步，首先创建二维码ticket，然后凭借ticket到指定URL换取二维码
     *
     * @param array   $qrcode   二维码参数详细信息
     * @return json
     */
    public function getTicket($qrcode)
    {
        $options = array();
        $options['me_url'] = sprintf($this->qrcodeUrl, 'create', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();

        $data = json_encode($qrcode);

        $info = $this->resourceOwner->getMeUrl($data);

        if(isset($info['errcode'])&&$info['errcode']==0)
            parent::getError($info);

        return $info;
    }

    /**
     * 生成带参数的二维码之通过ticket换取二维码
     * 获取带参数的二维码的过程包括两步，首先创建二维码ticket，然后凭借ticket到指定URL换取二维码
     *
     * @param string   $ticket   ticket字串信息
     * @return json
     */
    public function createQrcode($ticket)
    {
        $options = array();
        $options['me_url'] = sprintf($this->showqrcodeUrl, $ticket);
        $this->resourceOwner->setOption($options);


        $info = $this->resourceOwner->getMeUrl();
        parent::getError($info);

        return $info;
    }

    /**
     * 长链接转短链接接
     *
     * @param string   $longUrl   需要转换的长链接，支持http://、https://、weixin://wxpay 格式的url
     * @return json
     */
    public function getShortUrl($longUrl)
    {
        $options = array();
        $options['me_url'] = sprintf($this->shortUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['action'] = 'long2short';
        $data['long_url'] = urlencode($longUrl);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }


}