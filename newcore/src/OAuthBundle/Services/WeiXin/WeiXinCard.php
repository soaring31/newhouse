<?php
/**
 * 微信卡券接口
 *
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年7月8日
*/
namespace OAuthBundle\Services\WeiXin;

class WeiXinCard extends WeiXin
{
    /**
     * 卡券管理接口Url
     * @var string
     */
    private $cardUrl = 'https://api.weixin.qq.com/card/%s?access_token=%s';

    /**
     * 买单接口Url
     * @var string
     */
    private $paycellUrl = 'https://api.weixin.qq.com/card/paycell/%s?access_token=%s';

    /**
     * 自助核销接口Url
     * @var string
     */
    private $selfconsumecellUrl = 'https://api.weixin.qq.com/card/selfconsumecell/%s?access_token=%s';

    /**
     * 生成卡券二维码口Url
     * @var string
     */
    private $qrcodelUrl = 'https://api.weixin.qq.com/card/qrcode/%s?access_token=%s';

    /**
     * 创建货架接口Url
     * @var string
     */
    private $landingpageUrl = 'https://api.weixin.qq.com/card/landingpage/%s?access_token=%s';

    /**
     * 导入自定义code接口Url
     * @var string
     */
    private $codeUrl = 'http://api.weixin.qq.com/card/code/%s?access_token=%s';

    /**
     * 图文消息群发卡券接口Url
     * @var string
     */
    private $mpnewsUrl = 'https://api.weixin.qq.com/card/mpnews/%s?access_token=%s';

    /**
     * 设置测试白名单接口Url
     * @var string
     */
    private $testwhitelistUrl = 'https://api.weixin.qq.com/card/testwhitelist/%s?access_token=%s';




    /**
     * 创建卡券
     *
     * @param array   $card   卡券详细信息
     * @return json
     */
    public function addCard($card)
    {
        $options = array();
        $options['me_url'] = sprintf($this->cardUrl, 'create', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['card'] = $common->encodeUrl($card, true);
        $data = $common->decodeUrl(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 设置买单接口
     * 设置买单的card_id必须已经配置了门店，否则会报错
     *
     * @param string   $cardId   卡券ID
     * @param bool   $isOpen   是否开启买单功能，填true/false
     * @return json
     */
    public function setPaycell($cardId, $isOpen)
    {
        $options = array();
        $options['me_url'] = sprintf($this->paycellUrl, 'set', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['cardid'] = $cardId;
        $data['is_open'] = $isOpen;
        json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 设置自助核销
     * 设置自助核销的card_id必须已经配置了门店，否则会报错
     *
     * @param string   $cardId   卡券ID
     * @param bool   $isOpen   是否开启买单功能，填true/false
     * @return json
     */
    public function setSelfConsumecell($cardId, $isOpen)
    {
        $options = array();
        $options['me_url'] = sprintf($this->selfconsumecellUrl, 'set', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['cardid'] = $cardId;
        $data['is_open'] = $isOpen;
        json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 创建卡券二维码
     * 调用该接口生成一张卡券二维码供用户扫码后添加卡券到卡包
     *
     * @param array   $qrcodeInfo   二维码信息
     * @return json
     */
    public function createQrcode($qrcodeInfo)
    {
        $options = array();
        $options['me_url'] = sprintf($this->qrcodelUrl, 'create', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data = $common->encodeUrl($qrcodeInfo, true);
        $data = $common->decodeUrl(json_encode($data));


        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 创建货架
     * 调用该接口创建货架链接，用于卡券投放。创建货架时需填写投放路径的场景字段
     *
     * @param array   $shelfInfo   货架信息
     * @return json
     */
    public function createShelf($shelfInfo)
    {
        $options = array();
        $options['me_url'] = sprintf($this->landingpageUrl, 'create', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data = $common->encodeUrl($shelfInfo, true);
        $data = $common->decodeUrl(json_encode($data));


        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 导入自定义code
     * 在自定义code卡券成功创建并且通过审核后，必须将自定义code按照与发券方的约定数量调用导入code接口导入微信后台
     *
     * @param string   $cardId   需要进行导入code的卡券ID
     * @param array   $code   需导入微信卡券后台的自定义code，上限为100个
     * @return json
     */
    public function depositCode($cardId, $code)
    {
        $options = array();
        $options['me_url'] = sprintf($this->codeUrl, 'deposit', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['card_id'] = $common->encodeUrl($cardId, true);
        $data['code'] = $common->encodeUrl($code, true);
        $data = $common->decodeUrl(json_encode($data));


        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 查询导入自定义code成功数目
     * 调用该接口查询code导入微信后台成功的数目
     *
     * @param string   $cardId   需要进行导入code的卡券ID
     * @return json
     */
    public function getDepositCount($cardId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->codeUrl, 'getdepositcount', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['card_id'] = $common->encodeUrl($cardId, true);
        $data = $common->decodeUrl(json_encode($data));


        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 核查code接口
     * 为了避免出现导入差错，在查询完code数目的时候核查code接口校验code导入微信后台的情况
     *
     * @param string   $cardId   需要进行导入code的卡券ID
     * @param array   $code   需导入微信卡券后台的自定义code，上限为100个
     * @return json
     */
    public function checkCode($cardId, $code)
    {
        $options = array();
        $options['me_url'] = sprintf($this->codeUrl, 'checkcode', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['card_id'] = $common->encodeUrl($cardId, true);
        $data['code'] = $common->encodeUrl($code, true);
        $data = $common->decodeUrl(json_encode($data));


        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 图文消息群发卡券
     * 调用该接口获取卡券嵌入图文消息的标准格式代码，将返回代码填入上传图文素材接口中content字段，即可获取嵌入卡券的图文消息素材
     * 注意：目前该接口仅支持填入非自定义code的卡券,自定义code的卡券需先进行code导入后调用
     *
     * @param string   $cardId   需要进行导入code的卡券ID
     * @return json
     */
    public function getHtml($cardId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->mpnewsUrl, 'gethtml', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['card_id'] = $common->encodeUrl($cardId, true);
        $data = $common->decodeUrl(json_encode($data));


        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 设置测试白名单
     * 由于卡券有审核要求，为方便公众号调试，可以设置一些测试帐号，这些帐号可领取未通过审核的卡券，体验整个流程
     *
     * @param array   $openid   测试的openid列表
     * @param array   $username   测试的微信号列表
     * @return json
     */
    public function setWhiteList($openid, $username)
    {
        $options = array();
        $options['me_url'] = sprintf($this->testwhitelistUrl, 'set', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['openid'] = $common->encodeUrl($openid, true);
        $data['username'] = $common->encodeUrl($username, true);
        $data = $common->decodeUrl(json_encode($data));


        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }




}