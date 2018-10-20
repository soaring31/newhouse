<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月29日
*/
namespace OAuthBundle\Services\WeiXin;

class WeiXinScan extends WeiXin
{
    /**
     * 获取商户信息Url
     * @var string
     */
    private $merchantinfoUrl = 'https://api.weixin.qq.com/scan/merchantinfo/get?access_token=%s';

    /**
     * 设置测试人员白名单Url
     * @var string
     */
    private $testwhitelistUrl = 'https://api.weixin.qq.com/scan/testwhitelist/set?access_token=%s';

    /**
     * 商品发布、管理Url
     * @var string
     */
    private $baseProductUrl = 'https://api.weixin.qq.com/scan/product/%s?access_token=%s';

    /**
     * 检查wxticket参数Url，检查当前访问用户来源
     * @var string
     */
    private $scanticketUrl = 'https://api.weixin.qq.com/scan/scanticket/check?access_token=%s';


    /**
     * 获取商户信息
     *
     */
    public function merchantinfo()
    {
    	$options = array();
        $options['me_url'] = sprintf($this->merchantinfoUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $info = $this->resourceOwner->getMeUrl();
        parent::getError($info);
        
        return $info;
    }

    /**
     * 创建商品
     *
     * @param string   $keystandard  商品编码标准
     * @param string   $keystr  商品条码内容
     * @param array   $brandInfo  商品信息
     */
    public function create($keystandard, $keystr, $brandInfo)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseProductUrl, 'create', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['keystandard'] = $keystandard;
        $data['keystr'] = $common->encodeUrl(trim($keystr));
        $data['brand_info'] = $common->encodeUrl($brandInfo, true);
        $data = $common->decodeUrl(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 商品发布
     *
     * @param string   $keystandard  商品编码标准
     * @param string   $keystr  商品条码内容
     * @param string   $status  发布状态,on为提交审核,off为取消发布
     */
    public function modstatus($keystandard, $keystr, $status)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseProductUrl, 'modstatus', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['keystandard'] = $keystandard;
        $data['keystr'] = urlencode(trim($keystr));
        $data['status'] = urlencode($status);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 设置测试人员白名单
     * @param array   $openid  测试人员的openid列表
     * @param array   $username  测试人员的微信号列表
     */
    public function testwhitelist($openid, $username)
    {
        $options = array();
        $options['me_url'] = sprintf($this->testwhitelistUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['openid'] = urlencode($openid);
        $data['username'] = urlencode($username);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 获取商品二维码
     *
     * @param string      $keystandard       商品编码标准
     * @param string      $keystr       商品编码内容
     * @param string      $extinfo       商户自定义传入，建议仅使用大小写字母、数字及-_().*这6个常用字符
     * @param int   $qrcodeSize      二维码的尺寸
     */
    public function getqrcode($keystandard, $keystr, $extinfo = '', $qrcodeSize)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseProductUrl, 'getqrcode', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['keystandard'] = urlencode($keystandard);
        $data['keystr'] = urlencode(trim($keystr));
        $data['extinfo'] = urlencode(trim($extinfo));
        $data['qrcode_size'] = (int)$qrcodeSize;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 查询商品信息
     * @param string      $keystandard       商品编码标准
     * @param string      $keystr       商品编码内容
     */
    public function getBrand($keystandard, $keystr)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseProductUrl, 'get', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['keystandard'] = urlencode($keystandard);
        $data['keystr'] = urlencode(trim($keystr));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 批量查询商品信息
     * @param int      $offset       批量查询的起始位置，从0开始，包含该起始位置。
     * @param int      $limit        批量查询的数量
     * @param string      $status       按状态拉取。on为发布状态，off为未发布状态，check为审核中状态，reject为审核未通过状态，all为所有状态
     * @param string      $keystr       按关键词搜索商品编码内容拉取
     */
    public function getlist($offset, $limit, $status = '', $keystr = '')
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseProductUrl, 'getlist', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['offset'] = (int)$offset;
        $data['limit'] = (int)$limit;
        $data['status'] = urlencode($status);
        $data['keystr'] = urlencode(trim($keystr));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 更新商品信息
     *
     * @param string   $keystandard  商品编码标准
     * @param string   $keystr  商品条码内容
     * @param array   $brandInfo  商品信息
     */
    public function update($keystandard, $keystr, $brandInfo)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseProductUrl, 'update', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['keystandard'] = $keystandard;
        $data['keystr'] = urlencode(trim($keystr));
        $data['brand_info'] = urlencode($brandInfo);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 清除商品信息
     * @param string      $keystandard       商品编码标准
     * @param string      $keystr       商品编码内容
     */
    public function clear($keystandard, $keystr)
    {
        $options = array();
        $options['me_url'] = sprintf($this->baseProductUrl, 'clear', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['keystandard'] = urlencode($keystandard);
        $data['keystr'] = urlencode(trim($keystr));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 根据跳转URL参数，检查wxticket参数,检查当前访问用户来源
     * @param string      $ticket       标识来源的凭证参数
     */
    public function scanticket($ticket)
    {
        $options = array();
        $options['me_url'] = sprintf($this->scanticketUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['ticket'] = urlencode($ticket);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

}