<?php
/**
 * 微信摇一摇周边
 *
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年6月29日
*/
namespace OAuthBundle\Services\WeiXin;

class WeiXinShake extends WeiXin
{
    /**
     * 申请开通摇一摇周边功能Url
     * @var string
     */
    private $accountRegisterUrl = 'https://api.weixin.qq.com/shakearound/account/%s?access_token=%s';

    /**
     * 设备管理Url
     * @var string
     */
    private $deviceUrl = 'https://api.weixin.qq.com/shakearound/device/%s?access_token=%s';

    /**
     * 页面管理Url
     * @var string
     */
    private $pageUrl = 'https://api.weixin.qq.com/shakearound/page/%s?access_token=%s';

    /**
     * 查询设备与页面的关联关系Url
     * @var string
     */
    private $relationUrl = 'https://api.weixin.qq.com/shakearound/relation/search?access_token=%s';

    /**
     * 素材管理Url
     * @var string
     */
    private $materialUrl = 'https://api.weixin.qq.com/shakearound/material/%s?access_token=%s';

    /**
     * 数据统计Url
     * @var string
     */
    private $statisticsUrl = 'https://api.weixin.qq.com/shakearound/statistics/%s?access_token=%s';

    /**
     * HTML5获取设备信息 UrL
     * @var string
     */
    private $groupUrl = 'https://api.weixin.qq.com/shakearound/device/group/%s?access_token=%s';

    /**
     * 获取设备及用户信息UrL
     * @var string
     */
    private $getshakeinfoUrl = 'https://api.weixin.qq.com/shakearound/user/%s?access_token=%s';

    /**
     * 摇一摇红包预下单接口UrL
     * @var string
     */
    private $hbpreorderUrl = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/hbpreorder';

    /**
     * 摇一摇红包UrL
     * @var string
     */
    private $lotteryUrl = 'https://api.weixin.qq.com/shakearound/lottery/%s?access_token=%s';


    /**
     * 申请开通微信摇一摇功能
     *
     * @param string   $name  联系人姓名，不超过20汉字或40个英文字母
     * @param string   $phoneNumber  联系人电话L
     * @param string   $email  联系人邮箱
     * @param string   $industryId  平台定义的行业代号，具体请查看链接http://3gimg.qq.com/shake_nearby/Qualificationdocuments.html
     * @param string   $qualificationCertUrls  相关资质文件的图片url
     * @param string   $applyReason  申请理由，不超过250汉字或500个英文字母
     */
    public function accountRegister($name, $phoneNumber, $email, $industryId, $qualificationCertUrls, $applyReason = '')
    {
        $options = array();
        $options['me_url'] = sprintf($this->accountRegisterUrl, 'register', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['name'] = urlencode(trim($name));
        $data['phone_number'] = trim($phoneNumber);
        $data['email'] = trim($email);
        $data['industry_id'] = trim($industryId);
        $data['qualification_cert_urls'] = urlencode(trim($qualificationCertUrls));
        $data['apply_reason'] = urlencode(trim($applyReason));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 查询申请开通微信摇一摇功能审核状态
     *
     */
    public function accountAuditstatus()
    {
        $options = array();
        $options['me_url'] = sprintf($this->accountRegisterUrl, 'auditstatus', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $info = $this->resourceOwner->getMeUrl();
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 申请设备ID
     *
     * @param int   $quantity  申请的设备ID的数量
     * @param string   $applyReason  申请理由
     * @param string   $comment  备注
     * @param int   $poiId  设备关联的门店ID
     */
    public function applyidDevice($quantity, $applyReason, $comment = '', $poiId = '')
    {
        $options = array();
        $options['me_url'] = sprintf($this->deviceUrl, 'applyid', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['quantity'] = (int)$quantity;
        $data['apply_reason'] = urlencode(trim($applyReason));
        $data['comment'] = urlencode(trim($comment));
        $data['poi_id'] = (int)$poiId;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 查询设备ID申请审核状态
     *
     * @param int   $applyId  申请的设备ID的数量
     */
    public function applystatusDevice($applyId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->deviceUrl, 'applystatus', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['apply_id'] = (int)$applyId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 编辑设备信息
     *
     * @param array   $deviceIdentifier  指定的设备
     * @param string   $comment  设备的备注信息，不超过15个汉字或30个英文字母
     */
    public function updateDevice($deviceIdentifier, $comment)
    {
        $options = array();
        $options['me_url'] = sprintf($this->deviceUrl, 'update', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['device_identifier'] = (array)$deviceIdentifier;
        $data['comment'] = urlencode(trim($comment));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 配置设备与门店的关联关系
     *
     * @param array   $deviceIdentifier  指定的设备
     * @param int   $poiId  设备关联的门店ID
     */
    public function bindlocationDevice($deviceIdentifier, $poiId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->deviceUrl, 'bindlocation', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['device_identifier'] = (array)$deviceIdentifier;
        $data['poi_id'] = (int)$poiId;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 配置设备与其他公众账号门店的关联关系
     *
     * @param array   $deviceIdentifier  指定的设备
     * @param int   $poiId  设备关联的门店ID
     * @param int   $type  为1时，关联的门店和设备归属于同一公众账号；为2时，关联的门店为其他公众账号的门店
     * @param int   $poiAppid  关联门店所归属的公众账号的APPID,当Type为2时，必填
     */
    public function bindlocationOtherDevice($deviceIdentifier, $poiId, $type = 1, $poiAppid = NULL)
    {
        $options = array();
        $options['me_url'] = sprintf($this->deviceUrl, 'bindlocation', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['device_identifier'] = (array)$deviceIdentifier;
        $data['poi_id'] = (int)$poiId;
        $data['type'] = (int)$type;
        $data['poi_appid'] = (int)$poiAppid;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 查询设备列表
     *
     * @param array   $deviceIdentifier  指定的设备
     * @param int   $type  查询类型。1：查询设备id列表中的设备；2：分页查询所有设备信息；3：分页查询某次申请的所有设备信息
     * @param int   $lastSeen  前一次查询列表末尾的设备ID ， 第一次查询last_seen 为0
     * @param int   $count  待查询的设备数量，不能超过50个
     * @param int   $poiAppid  批次ID，申请设备ID时所返回的批次ID；当type为3时，此项为必填
     */
    public function searchDevice($type, $deviceIdentifier = NULL, $lastSeen = NULL, $count = NULL, $applyId = NULL)
    {
        $options = array();
        $options['me_url'] = sprintf($this->deviceUrl, 'search', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['type'] = (int)$type;
        $data['device_identifier'] = (array)$deviceIdentifier;
        $data['last_seen'] = (int)$lastSeen;
        $data['count'] = (int)$count;
        $data['apply_id'] = (int)$applyId;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 新增页面
     *
     * @param string   $title  在摇一摇页面展示的主标题，不超过6个汉字或12个英文字母
     * @param string   $description  在摇一摇页面展示的副标题，不超过7个汉字或14个英文字母
     * @param string   $iconUrl  在摇一摇页面展示的图片
     * @param string   $comment  页面的备注信息，不超过15个汉字或30个英文字母
     */
    public function addPage($title, $description, $iconUrl, $comment)
    {
        $options = array();
        $options['me_url'] = sprintf($this->pageUrl, 'add', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['title'] = urlencode(trim($title));
        $data['description'] = urlencode(trim($description));
        $data['icon_url'] = urlencode(trim($iconUrl));
        $data['comment'] = urlencode(trim($comment));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 编辑页面信息
     *
     * @param int   $pageId  摇周边页面唯一ID
     * @param string   $title  在摇一摇页面展示的主标题，不超过6个汉字或12个英文字母
     * @param string   $description  在摇一摇页面展示的副标题，不超过7个汉字或14个英文字母
     * @param string   $iconUrl  在摇一摇页面展示的图片
     * @param string   $pageUrl  跳转链接
     * @param string   $comment  页面的备注信息，不超过15个汉字或30个英文字母
     */
    public function updatePage($pageId, $title, $description, $iconUrl, $comment = '')
    {
        $options = array();
        $options['me_url'] = sprintf($this->pageUrl, 'update', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['page_id'] = (int)$pageId;
        $data['title'] = urlencode(trim($title));
        $data['description'] = urlencode(trim($description));
        $data['icon_url'] = urlencode(trim($iconUrl));
        //$data['page_url'] = urlencode(trim($pageUrl));
        $data['comment'] = urlencode(trim($comment));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 查询页面列表
     *
     * @param int   $type  查询类型。1： 查询页面id列表中的页面信息；2：分页查询所有页面信息
     * @param array   $pageIds  指定页面的id列表；当type为1时，此项为必填
     * @param int   $begin  页面列表的起始索引值；当type为2时，此项为必填
     * @param int   $count  待查询的页面数量，不能超过50个；当type为2时，此项为必填
     */
    public function searchPageList($type, $pageIds = NULL, $begin = NULL, $count = NULL)
    {
        $options = array();
        $options['me_url'] = sprintf($this->pageUrl, 'search', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['type'] = (int)$type;
        $data['page_ids'] = (array)$pageIds;
        $data['begin'] = (int)$begin;
        $data['count'] = (int)$count;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 删除页面
     *
     * @param int   $pageId  指定页面的id
     */
    public function deletePage($pageId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->pageUrl, 'delete', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['page_id '] = (int)$pageId;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 上传图片素材
     *
     * @param int   $media  图片名字
     * @param string   $type  Icon：摇一摇页面展示的icon图；License：申请开通摇一摇周边功能时需上传的资质文件；若不传type，则默认type=icon
     */
    public function addMateria($media, $type = 'Icon')
    {
        $options = array();
        $options['me_url'] = sprintf($this->materialUrl, 'add', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['media'] = urlencode(trim($media));
        $data['type'] = urlencode(trim($type));
        $data = urldecode(json_encode($data));

        //$info = $this->resourceOwner->getMeUrl($data);
        $info = $this->resourceOwner->uploadRequest($options['me_url'], $data, array('media'));
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 配置设备与页面的关联关系
     *
     * @param array   $deviceIdentifier  图片名字
     * @param string   $pageIds  Icon：摇一摇页面展示的icon图；License：申请开通摇一摇周边功能时需上传的资质文件；若不传type，则默认type=icon
     */
    public function bindpageDevice($deviceIdentifier, $pageIds)
    {
        $options = array();
        $options['me_url'] = sprintf($this->deviceUrl, 'bindpage', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['device_identifier'] = (array)$deviceIdentifier;
        $data['page_ids'] = $pageIds;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 查询设备与页面的关联关系
     *
     * @param int   $type  查询方式。1： 查询设备的关联关系；2：查询页面的关联关系
     * @param array   $deviceIdentifier  指定的设备；当type为1时，此项为必填
     * @param array   $pageId  指定的页面id；当type为2时，此项为必填
     * @param int   $begin  关联关系列表的起始索引值；当type为2时，此项为必填
     * @param int   $count  待查询的关联关系数量，不能超过50个；当type为2时，此项为必填
     */
    public function searchPage($type, $deviceIdentifier = NULL, $pageId = NULL, $begin = NULL, $count = NULL)
    {
        $options = array();
        $options['me_url'] = sprintf($this->relationUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['type'] = (int)$type;
        $data['device_identifier'] = (array)$deviceIdentifier;
        $data['page_id'] = (int)$pageId;
        $data['begin'] = (int)$begin;
        $data['count'] = (int)$count;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 以设备为维度的数据统计接口
     *
     * @param array   $deviceIdentifier  指定页面的设备
     * @param string   $beginDate  起始日期时间戳，最长时间跨度为30天，单位为秒
     * @param string   $endDate  结束日期时间戳，最长时间跨度为30天，单位为秒
     */
    public function deviceStatistics($deviceIdentifier, $beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->statisticsUrl, 'device', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['device_identifier'] = (array)$deviceIdentifier;
        $data['begin_date'] = (int)$beginDate;
        $data['end_date'] = (int)$endDate;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 批量查询设备统计数据接口
     *
     * @param int   $date  指定查询日期时间戳，单位为秒
     * @param int   $pageIndex  指定查询的结果页序号；返回结果按摇周边人数降序排序，每50条记录为一页
     */
    public function devicelistStatistics($date, $pageIndex)
    {
        $options = array();
        $options['me_url'] = sprintf($this->statisticsUrl, 'devicelist', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['date'] = (int)$date;
        $data['page_index'] = (int)$pageIndex;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 以页面为维度的数据统计接口
     *
     * @param int   $pageId  指定页面的设备ID
     * @param int   $beginDate  起始日期时间戳，最长时间跨度为30天，单位为秒
     * @param int   $endDate  结束日期时间戳，最长时间跨度为30天，单位为秒
     */
    public function pageStatistics($pageId, $beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->statisticsUrl, 'page', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['page_id'] = (int)$pageId;
        $data['begin_date'] = (int)$beginDate;
        $data['end_date'] = (int)$endDate;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 批量查询页面统计数据接口
     *
     * @param int   $date  指定查询日期时间戳
     * @param int   $pageIndex  指定查询的结果页序号；返回结果按摇周边人数降序排序，每50条记录为一页
     */
    public function pagelistStatistics($date, $pageIndex)
    {
        $options = array();
        $options['me_url'] = sprintf($this->statisticsUrl, 'pagelist', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['date'] = (int)$date;
        $data['page_index'] = (int)$pageIndex;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 新增分组
     *
     * @param string   $groupName  分组名称，不超过100汉字或200个英文字母
     */
    public function addGroup($groupName)
    {
        $options = array();
        $options['me_url'] = sprintf($this->groupUrl, 'add', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['group_name'] = urlencode(trim($groupName));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 编辑分组信息
     *
     * @param int   $groupId  分组唯一标识
     * @param int   $groupName  分组名称，不超过100汉字或200个英文字母
     */
    public function updateGroup($groupId, $groupName)
    {
        $options = array();
        $options['me_url'] = sprintf($this->groupUrl, 'update', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['group_id'] = (int)$groupId;
        $data['group_name'] = urlencode(trim($groupName));
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 删除分组
     *
     * @param int   $groupId  分组唯一标识
     */
    public function deleteGroup($groupId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->groupUrl, 'delete', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['group_id'] = (int)$groupId;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 查询分组列表
     *
     * @param int   $begin  分组列表的起始索引值
     * @param int   $count  待查询的分组数量，不能超过1000个
     */
    public function getlistGroup($begin, $count)
    {
        $options = array();
        $options['me_url'] = sprintf($this->groupUrl, 'getlist', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin'] = (int)$begin;
        $data['count'] = (int)$count;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 查询分组详情
     *
     * @param int   $groupId  分组唯一标识
     * @param int   $begin  分组列表的起始索引值
     * @param int   $count  待查询的分组里设备的数量，不能超过1000个
     */
    public function getdetailGroup($groupId, $begin, $count)
    {
        $options = array();
        $options['me_url'] = sprintf($this->groupUrl, 'getdetail', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['group_id'] = (int)$groupId;
        $data['begin'] = (int)$begin;
        $data['count'] = (int)$count;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 添加设备到分组
     *
     * @param int   $groupId  分组唯一标识
     * @param int   $deviceIdentifiers  分组列表的起始索引值
     */
    public function adddeviceGroup($groupId, $deviceIdentifiers)
    {
        $options = array();
        $options['me_url'] = sprintf($this->groupUrl, 'adddevice', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['group_id'] = (int)$groupId;
        $data['device_identifiers'] = (array)$deviceIdentifiers;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 从分组中移除设备
     *
     * @param int   $groupId  分组唯一标识
     * @param int   $deviceIdentifiers  分组列表的起始索引值
     */
    public function deletedeviceGroup($groupId, $deviceIdentifiers)
    {
        $options = array();
        $options['me_url'] = sprintf($this->groupUrl, 'deletedevice', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['group_id'] = (int)$groupId;
        $data['device_identifiers'] = (array)$deviceIdentifiers;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 获取设备及用户信息
     *
     * @param string   $ticket  摇周边业务的ticket，可在摇到的URL中得到，ticket生效时间为30分钟，每一次摇都会重新生成新的ticket
     * @param int   $needPoi  是否需要返回门店poi_id，传1则返回，否则不返回；门店相关信息具体可查https://mp.weixin.qq.com/zh_CN/htmledition/comm_htmledition/res/store_manage/store_manage_file.zip
     */
    public function getShakeInfo($ticket, $needPoi)
    {
        $options = array();
        $options['me_url'] = sprintf($this->getshakeinfoUrl, 'getshakeinfo', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['ticket'] = urlencode(trim($ticket));
        $data['need_poi'] = (int)$needPoi;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 红包预下单接口
     * 设置单个红包的金额，类型等，生成红包信息。预下单完成后，需要在72小时内调用jsapi完成抽红包的操作。（红包过期失效后，资金会退回到商户财付通帐号。）
     *
     * @param array   $data   请求参数数组，具体可查看https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1459327193&token=&lang=zh_CN
     */
    public function hbpreorder($data)
    {
        $options = array();
        $options['me_url'] = sprintf($this->hbpreorderUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
//        $data['nonce_str'] = $nonce_str;
        $fromUserName = $this->account->getWxaccount();
//        $data['ToUserName'] = $openId;
        $data['FromUserName'] = $fromUserName;
        $data['CreateTime'] = time();

        $data = $this->arrayToXML($data);

        $info = $this->resourceOwner->getMeUrl($data);
        return $info;
    }


    /**
     * 创建红包活动
     *
     * @param int   $useTemplate  是否使用模板，1：使用，2：不使用
     * @param string   $logoUrl  使用模板页面的logo_url，不使用模板时可不加
     * @param array   $body  JSON格式的结构体
     */
    public function addlotteryinfoLottery($useTemplate, $logoUrl, $body)
    {
        $options = array();
        $options['me_url'] = sprintf($this->lotteryUrl, 'addlotteryinfo', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['use_template'] = (int)$useTemplate;
        $data['logo_url'] = urlencode(trim($logoUrl));
        $data['body'] = (array)$body;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;    }

    /**
     * 录入红包信息
     *
     * @param int   $lotteryId  红包抽奖id，来自addlotteryinfo返回的lottery_id
     * @param int   $mchid  红包提供者的商户号，，需与预下单中的商户号mch_id一致
     * @param int   $sponsorAppid  红包提供商户公众号的appid，需与预下单中的公众账号appid（wxappid）一致
     * @param array   $prizeInfoList  红包ticket列表，如果红包数较多，可以一次传入多个红包，批量调用该接口设置红包信息。每次请求传入的红包个数上限为100
     */
    public function setprizebucketLottery($lotteryId, $mchid, $sponsorAppid, $prizeInfoList)
    {
        $options = array();
        $options['me_url'] = sprintf($this->lotteryUrl, 'setprizebucket', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['lottery_id'] = (int)$lotteryId;
        $data['mchid'] = (int)$mchid;
        $data['sponsor_appid'] = (int)$sponsorAppid;
        $data['prize_info_list'] = (array)$prizeInfoList;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;    }

    /**
     * 设置红包活动抽奖开关
     *
     * @param int   $lotteryId  红包抽奖id，来自addlotteryinfo返回的lottery_id
     * @param string   $onoff  活动抽奖开关，0：关闭，1：开启
     */
    public function setlotteryswitchLottery($lotteryId, $onoff)
    {
        $options = array();
        $options['me_url'] = sprintf($this->lotteryUrl, 'setlotteryswitch', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['lottery_id'] = (int)$lotteryId;
        $data['onoff'] = $onoff;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 红包查询接口
     *
     * @param int   $lotteryId  红包抽奖id，来自addlotteryinfo返回的lottery_id
     */
    public function queryLottery($lotteryId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->lotteryUrl, 'querylottery', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['lottery_id'] = (int)$lotteryId;
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }


}