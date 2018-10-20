<?php
/**
 * 微信门店接口
 *
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年7月8日
*/
namespace OAuthBundle\Services\WeiXin;

class WeiXinPoi extends WeiXin
{
    /**
     * 门店管理接口Url
     * @var string
     */
    private $poiUrl = 'http://api.weixin.qq.com/cgi-bin/poi/addpoi?access_token=%s';




    /**
     * 创建门店
     * 注意 常见出错字段规范
     *
     * @param array   $business   门店详细信息
     * @return json
     */
    public function addPoi($business)
    {
        $options = array();
        $options['me_url'] = sprintf($this->poiUrl, 'addpoi', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['business'] = $common->encodeUrl($business, true);
        $data = $common->decodeUrl(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 查询门店
     *
     * @param int   $poiId   门店ID
     * @return json
     */
    public function getPoi($poiId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->poiUrl, 'getpoi', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['poi_id'] = $poiId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 查询门店列表
     *
     * @param int   $begin   开始位置，0 即为从第一条开始查询
     * @param int   $limit   返回数据条数，最大允许50，默认为20
     * @return json
     */
    public function getPoiList($begin = 0, $limit = 20)
    {
        $options = array();
        $options['me_url'] = sprintf($this->poiUrl, 'getpoilist', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['begin'] = $begin;
        $data['limit'] = $limit;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 修改门店服务信息
     * 注意 常见出错字段规范
     *
     * @param array   $business   门店详细信息
     * @return json
     */
    public function updatePoi($business)
    {
        $options = array();
        $options['me_url'] = sprintf($this->poiUrl, 'updatepoi', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['business'] = $common->encodeUrl($business, true);
        $data = $common->decodeUrl(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 删除门店
     *
     * @param int   $poiId   门店ID
     * @return json
     */
    public function delPoi($poiId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->poiUrl, 'delpoi', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['poi_id'] = $poiId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 门店类目表
     *
     * @return json
     */
    public function getPoiCategory()
    {
        $options = array();
        $options['me_url'] = sprintf($this->poiUrl, 'getwxcategory', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $info = $this->resourceOwner->getMeUrl();

        parent::getError($info);

        return $info;
    }


}