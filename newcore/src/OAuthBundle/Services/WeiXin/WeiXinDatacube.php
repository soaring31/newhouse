<?php
/**
 * 用户分析数据接口
 *
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年7月14日
*/
namespace OAuthBundle\Services\WeiXin;

class WeiXinDatacube extends WeiXin
{
    /**
     * 数据统计接口Url
     * @var string
     */
    private $datacubeUrl = 'https://api.weixin.qq.com/datacube/%s?access_token=%s';



    /**
     * 获取用户增减数据
     * 最大时间跨度
     *
     * @param string   $beginDate   开始日期
     * @param string   $endDate   结束日期 最多一次性获取 7 天的数据
     * @return json
     */
    public function getUserSummary($beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->datacubUrl, 'getusersummary', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode($beginDate);
        $data['end_date'] = urlencode($endDate);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 获取累计用户数据
     *
     * @param string   $beginDate   开始日期
     * @param string   $endDate   结束日期 最多一次性获取 7 天的数据
     * @return json
     */
    public function getUserCumulate($beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->datacubeUrl, 'getpoi', self::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode($beginDate);
        $data['end_date'] = urlencode($endDate);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 获取图文群发每日数据
     *
     * @param string   $beginDate   开始日期
     * @param string   $endDate   结束日期 最多一次性获取 1 天的数据
     * @return json
     */
    public function getArticleSummary($beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->datacubeUrl, 'getarticlesummary', self::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode($beginDate);
        $data['end_date'] = urlencode($endDate);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 获取图文群发总数据
     *
     * @param string   $beginDate   开始日期
     * @param string   $endDate   结束日期 最多一次性获取 1 天的数据
     * @return json
     */
    public function getArticleTotal($beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->datacubeUrl, 'getarticletotal', self::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode($beginDate);
        $data['end_date'] = urlencode($endDate);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 获取图文统计数据
     *
     * @param string   $beginDate   开始日期
     * @param string   $endDate   结束日期 最多一次性获取 3 天的数据
     * @return json
     */
    public function getUserRead($beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->datacubeUrl, 'getuserread', self::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode($beginDate);
        $data['end_date'] = urlencode($endDate);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 获取图文统计分时数据
     *
     * @param string   $beginDate   开始日期
     * @param string   $endDate   结束日期 最多一次性获取 1 天的数据
     * @return json
     */
    public function getUserReadHour($beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->datacubeUrl, 'getuserreadhour', self::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode($beginDate);
        $data['end_date'] = urlencode($endDate);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 获取图文分享转发数据
     *
     * @param string   $beginDate   开始日期
     * @param string   $endDate   结束日期 最多一次性获取 7 天的数据
     * @return json
     */
    public function getUserShare($beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->datacubeUrl, 'getusershare', self::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode($beginDate);
        $data['end_date'] = urlencode($endDate);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 获取图文分享转发分时数据
     *
     * @param string   $beginDate   开始日期
     * @param string   $endDate   结束日期 最多一次性获取 1 天的数据
     * @return json
     */
    public function getUserShareHour($beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->datacubeUrl, 'getusersharehour', self::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode($beginDate);
        $data['end_date'] = urlencode($endDate);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 获取消息发送概况数据
     *
     * @param string   $beginDate   开始日期
     * @param string   $endDate   结束日期 最多一次性获取 7 天的数据
     * @return json
     */
    public function getUpstreamMsg($beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->datacubeUrl, 'getupstreammsg', self::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode($beginDate);
        $data['end_date'] = urlencode($endDate);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 获取消息分送分时数据
     *
     * @param string   $beginDate   开始日期
     * @param string   $endDate   结束日期 最多一次性获取 1 天的数据
     * @return json
     */
    public function getUpstreaMmsgHour($beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->datacubeUrl, 'getupstreammsghour', self::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode($beginDate);
        $data['end_date'] = urlencode($endDate);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 获取消息发送周数据
     *
     * @param string   $beginDate   开始日期
     * @param string   $endDate   结束日期 最多一次性获取 30 天的数据
     * @return json
     */
    public function getUpstreamMsgWeek($beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->datacubeUrl, 'getupstreammsgweek', self::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode($beginDate);
        $data['end_date'] = urlencode($endDate);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 获取消息发送月数据
     *
     * @param string   $beginDate   开始日期
     * @param string   $endDate   结束日期 最多一次性获取 30 天的数据
     * @return json
     */
    public function getUpstreamMsgMonth($beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->datacubeUrl, 'getupstreammsgmonth', self::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode($beginDate);
        $data['end_date'] = urlencode($endDate);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 获取消息发送分布数据
     *
     * @param string   $beginDate   开始日期
     * @param string   $endDate   结束日期 最多一次性获取 15 天的数据
     * @return json
     */
    public function getUpstreamMsgDist($beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->datacubeUrl, 'getupstreammsgdist', self::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode($beginDate);
        $data['end_date'] = urlencode($endDate);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 获取消息发送分布周数据
     *
     * @param string   $beginDate   开始日期
     * @param string   $endDate   结束日期 最多一次性获取 30 天的数据
     * @return json
     */
    public function getUpstreamMsgDistWeek($beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->datacubeUrl, 'getupstreammsgdistweek', self::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode($beginDate);
        $data['end_date'] = urlencode($endDate);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 获取消息发送分布月数据
     *
     * @param string   $beginDate   开始日期
     * @param string   $endDate   结束日期 最多一次性获取 1 天的数据
     * @return json
     */
    public function getUpstreamMsgDistMonth($beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->datacubeUrl, 'getupstreammsgdistmonth', self::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode($beginDate);
        $data['end_date'] = urlencode($endDate);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 获取接口分析数据
     *
     * @param string   $beginDate   开始日期
     * @param string   $endDate   结束日期 最多一次性获取 30 天的数据
     * @return json
     */
    public function getInterfaceSummary($beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->datacubeUrl, 'getinterfacesummary', self::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode($beginDate);
        $data['end_date'] = urlencode($endDate);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }

    /**
     * 获取接口分析分时数据
     *
     * @param string   $beginDate   开始日期
     * @param string   $endDate   结束日期 最多一次性获取 1 天的数据
     * @return json
     */
    public function getInterfaceSummaryHour($beginDate, $endDate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->datacubeUrl, 'getinterfacesummaryhour', self::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin_date'] = urlencode($beginDate);
        $data['end_date'] = urlencode($endDate);
        $data = urldecode(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        parent::getError($info);

        return $info;
    }


    protected function getAccessToken()
    {
        
    }
}