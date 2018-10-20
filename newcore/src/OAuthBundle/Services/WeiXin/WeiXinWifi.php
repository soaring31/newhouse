<?php
/**
 * 微信连Wi-Fi
 *
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月29日
*/
namespace OAuthBundle\Services\WeiXin;

class WeiXinWifi extends WeiXin
{
    /**
     * 开通微信连Wi-Fi插件Url
     * @var string
     */
    private $openpluginUrl = 'https://api.weixin.qq.com/bizwifi/openplugin/token?access_token=%s';

    /**
     * Wi-Fi门店管理Url
     * @var string
     */
    private $shopUrl = 'https://api.weixin.qq.com/bizwifi/shop/%s?access_token=%s';

    /**
     * Wi-Fi设备管理Url
     * @var string
     */
    private $deviceUrl = 'https://api.weixin.qq.com/bizwifi/device/%s?access_token=%s';

    /**
     * 添加portal型Wi-Fi设备Url
     * @var string
     */
    private $addportalUrl = 'https://api.weixin.qq.com/bizwifi/apportal/register?access_token=%s';

    /**
     * 配置连网方式Url
     * @var string
     */
    private $qrcodeUrl = 'https://api.weixin.qq.com/bizwifi/qrcode/%s?access_token=%s';

    /**
     * 获取公众号连网URL UrL
     * @var string
     */
    private $getConnectUrl = 'https://api.weixin.qq.com/bizwifi/account/get_connecturl?access_token=%s';

    /**
     * 商家主页管理UrL
     * @var string
     */
    private $homepageUrl = 'https://api.weixin.qq.com/bizwifi/homepage/%s?access_token=%s';

    /**
     * 设置微信首页欢迎语UrL
     * @var string
     */
    private $wxhomepageUrl = 'https://api.weixin.qq.com/bizwifi/bar/set?access_token=%s';

    /**
     * 设置连网完成页UrL
     * @var string
     */
    private $wifihomepageUrl = 'https://api.weixin.qq.com/bizwifi/finishpage/set?access_token=%s';

    /**
     * Wi-Fi数据统计UrL
     * @var string
     */
    private $statisticsUrl = 'https://api.weixin.qq.com/bizwifi/statistics/list?access_token=%s';

    /**
     * 卡券投放UrL
     * @var string
     */
    private $couponputUrl = 'https://api.weixin.qq.com/bizwifi/couponput/%s?access_token=%s';


    /**
     * 开通微信连Wi-Fi插件
     *
     * @param string   $callbackUrl  回调URL
     */
    public function openplugin($callbackUrl)
    {
        $options = array();
        $options['me_url'] = sprintf($this->openpluginUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['callback_url'] = urlencode(trim($callbackUrl));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 获取Wi-Fi门店列表
     *
     * @param int   $pageindex  分页下标，默认从1开始
     * @param int   $pagesize  每页的个数，默认10个，最大20个
     */
    public function listShop($pageindex = 1, $pagesize = 10)
    {
        $options = array();
        $options['me_url'] = sprintf($this->shopUrl, 'list', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['pageindex'] = (int)$pageindex;
        $data['pagesize'] = (int)$pagesize;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 查询门店Wi-Fi详细信息
     *
     * @param int   $shopId  门店ID
     */
    public function getShop($shopId)
    {
        $options = array();
        $options['shop_id'] = sprintf($this->shopUrl, 'get', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['callback_url'] = (int)$shopId;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 更新门店Wi-Fi网络配置信息
     *
     * @param int   $shopId  门店ID
     * @param string   $oldSsid  需要修改的ssid，当门店下有多个ssid时，必填
     * @param string   $ssid  新的ssid。32个字符以内；ssid支持中文，但可能因设备兼容性问题导致显示乱码，或无法连接等问题
     * @param string   $password  无线网络设备的密码。8-24个字符；不能包含中文字符；当门店下是密码型设备时，才可填写password，且ssid和密码必须有一个以大写字母“WX”开头
     */
    public function updateShop($shopId, $oldSsid, $ssid, $password = '')
    {
        $options = array();
        $options['me_url'] = sprintf($this->shopUrl, 'update', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['shop_id'] = (int)$shopId;
        $data['old_ssid'] = urlencode(trim($oldSsid));
        $data['ssid'] = urlencode(trim($ssid));
        $data['password'] = urlencode(trim($password));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 清空门店网络及设备
     *
     * @param int   $shopId  门店ID
     * @param string   $ssid  无线网络设备的ssid。若不填写ssid，默认为清空门店下所有设备；填写ssid则为清空该ssid下的所有设备
     */
    public function cleanShop($shopId, $ssid = '')
    {
        $options = array();
        $options['me_url'] = sprintf($this->shopUrl, 'clean', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['shop_id'] = (int)$shopId;
        $data['ssid'] = urlencode(trim($ssid));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 添加密码型设备
     *
     * @param int   $shopId  门店ID
     * @param string   $ssid  新的ssid。32个字符以内；ssid支持中文，但可能因设备兼容性问题导致显示乱码，或无法连接等问题
     * @param string   $password  无线网络设备的密码。8-24个字符；不能包含中文字符；当门店下是密码型设备时，才可填写password，且ssid和密码必须有一个以大写字母“WX”开头
     */
    public function addDevice($shopId, $ssid, $password)
    {
        $options = array();
        $options['me_url'] = sprintf($this->deviceUrl, 'add', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['shop_id'] = (int)$shopId;
        $data['ssid'] = urlencode(trim($ssid));
        $data['password'] = urlencode(trim($password));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 添加portal(网站首页)型设备
     *
     * @param int   $shopId  门店ID
     * @param string   $ssid  新的ssid。32个字符以内；ssid支持中文，但可能因设备兼容性问题导致显示乱码，或无法连接等问题
     * @param bool   $reset  重置secretkey，false-不重置，true-重置，默认为false
     */
    public function addPortalDevice($shopId, $ssid, $reset = false)
    {
        $options = array();
        $options['me_url'] = sprintf($this->addportalUrl, 'add', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['shop_id'] = (int)$shopId;
        $data['ssid'] = urlencode(trim($ssid));
        $data['reset'] = (bool)$reset;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 查询设备
     *
     * @param int   $shopId  门店ID
     * @param int   $pageindex  分页下标，默认从1开始
     * @param int   $pagesize  每页的个数，默认10个，最大20个
     */
    public function listDevice($shopId, $ssid, $password)
    {
        $options = array();
        $options['me_url'] = sprintf($this->deviceUrl, 'list', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['shop_id'] = (int)$shopId;
        $data['ssid'] = urlencode(trim($ssid));
        $data['password'] = urlencode(trim($password));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 删除设备
     *
     * @param string   $bssid  要删除的无线网络设备无线mac地址，格式冒号分隔，字符长度17个，并且字母小写，例如：00:1f:7a:ad:5c:a8
     */
    public function deleteDevice($bssid)
    {
        $options = array();
        $options['me_url'] = sprintf($this->deviceUrl, 'delete', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['password'] = urlencode(trim($bssid));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 获取物料二维码
     *
     * @param int   $shopId  门店ID
     * @param string   $ssid  新的ssid。32个字符以内；ssid支持中文，但可能因设备兼容性问题导致显示乱码，或无法连接等问题
     * @param int   $imgId  物料样式编号,0-纯二维码,1-二维码物料
     */
    public function getQrcode($shopId, $ssid, $imgId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->qrcodeUrl, 'get', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['shop_id'] = (int)$shopId;
        $data['password'] = urlencode(trim($ssid));
        $data['img_id'] = (int)$imgId;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 获取公众号连网URL
     *
     */
    public function getConnectUrl()
    {
        $options = array();
        $options['me_url'] = sprintf($this->getConnectUrl, 'get', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $info = $this->resourceOwner->getMeUrl();
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 设置商家主页
     *
     * @param int   $shopId  门店ID
     * @param int   $templateId  模板ID，0-默认模板，1-自定义url
     * @param array   struct  模板结构，当template_id为0时可以不填
     * @param string   url  自定义链接，当template_id为1时必填
     */
    public function setHomepage($shopId, $templateId, $struct = array())
    {
        $options = array();
        $options['me_url'] = sprintf($this->homepageUrl, 'set', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['shop_id'] = (int)$shopId;
        $data['template_id'] = (int)$templateId;
        $data['struct']['url'] = urlencode(trim($options['me_url']));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 查询商家主页
     *
     * @param int   $shopId  门店ID
     *
     */
    public function getHomepage($shopId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->homepageUrl, 'get', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['shop_id'] = (int)$shopId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 设置微信首页欢迎语
     *
     * @param int   $shopId  门店ID
     * @param int   $barType  微信首页欢迎语的文本类型
     */
    public function setBar($shopId, $barType)
    {
        $options = array();
        $options['me_url'] = sprintf($this->homepageUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['shop_id'] = (int)$shopId;
        $data['bar_type'] = (int)$barType;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 设置连网完成页
     *
     * @param int   $shopId  门店ID
     * @param string   $finishpageRrl  连网完成页URL
     */
    public function setFinishpage($shopId, $finishpageRrl)
    {
        $options = array();
        $options['me_url'] = sprintf($this->finishpageUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['shop_id'] = (int)$shopId;
        $data['finishpage_url'] = urlencode(trim($finishpageRrl));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * Wi-Fi数据统计
     *
     * @param string   $beginDate  起始日期时间，格式yyyy-mm-dd，最长时间跨度为30天
     * @param string   $endDate  结束日期时间戳，格式yyyy-mm-dd，最长时间跨度为30天
     * @param int   $shopId  按门店ID搜索，-1为总统计
     */
    public function listStatistics($beginDate, $endDate, $shopId = -1)
    {
        $options = array();
        $options['me_url'] = sprintf($this->statisticsUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode(trim($beginDate));
        $data['end_date'] = urlencode(trim($endDate));
        $data['shop_id'] = (int)$shopId;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 设置门店卡券投放信息
     *
     * @param int   $shopId  门店ID，可设置为0，表示所有门店
     * @param int   $cardId  卡券ID
     * @param string   $cardDescribe  卡券描述，不能超过18个字符
     * @param int   $startTime  卡券投放开始时间（单位是秒）
     * @param int   $endTime  卡券投放结束时间（单位是秒）注：不能超过卡券的有效期时间
     * @param int   $cardQuantity  卡券库存
     */
    public function setCouponput($shopId, $cardId, $cardDescribe, $startTime, $endTime, $cardQuantity)
    {
        $options = array();
        $options['me_url'] = sprintf($this->couponputUrl, 'set', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['shop_id'] = (int)$shopId;
        $data['card_id'] = (int)$cardId;
        $data['card_describe'] = urlencode(trim($cardDescribe));
        $data['start_time'] = (int)$startTime;
        $data['end_time'] = (int)$endTime;
        $data['card_quantity'] = (int)$cardQuantity;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 查询门店卡券投放信息
     *
     * @param int   $shopId  门店ID，可设置为0，表示所有门店
     */
    public function getCouponput($shopId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->couponputUrl, 'get', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['shop_id'] = (int)$shopId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }


}