<?php
/**
 * 微信小店接口
 *
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年7月8日
*/
namespace OAuthBundle\Services\WeiXin;

class WeiXinMerchant extends WeiXin
{
    /**
     * 商品管理接口Url
     * @var string
     */
    private $merchantUrl = 'https://api.weixin.qq.com/merchant/%s?access_token=%s';

    /**
     * 商品分类管理Url
     * @var string
     */
    private $categoryUrl = 'https://api.weixin.qq.com/merchant/category/%s?access_token=%s';

    /**
     * 库存管理Url
     * @var string
     */
    private $stockUrl = 'https://api.weixin.qq.com/merchant/stock/%s?access_token=%s';

    /**
     * 邮费模板管理Url
     * @var string
     */
    private $expressUrl = 'https://api.weixin.qq.com/merchant/express/%s?access_token=%s';

    /**
     * 商品分组管理Url
     * @var string
     */
    private $groupUrl = 'https://api.weixin.qq.com/merchant/group/%s?access_token=%s';

    /**
     * 货架管理Url
     * @var string
     */
    private $shelfUrl = 'https://api.weixin.qq.com/merchant/shelf/%s?access_token=%s';

    /**
     * 订单管理Url
     * @var string
     */
    private $orderUrl = 'https://api.weixin.qq.com/merchant/order/%s?access_token=%s';

    /**
     * 功能管理Url
     * @var string
     */
    private $commonUrl = 'https://api.weixin.qq.com/merchant/common/%s?access_token=%s';

    /**
     * 语义理解Url
     * @var string
     */
    private $semanticUrl = 'https://api.weixin.qq.com/semantic/semproxy/search?access_token=%s';



    /**
     * 增加商品
     *
     * @param array   $productInfo   商品详细信息
     * @return json
     */
    public function createProduct($productInfo)
    {
        $options = array();
        $options['me_url'] = sprintf($this->merchantUrl, 'update', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['product_info'] = $common->encodeUrl($productInfo, true);
        $data = $common->decodeUrl(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 删除商品
     *
     * @param string   $productId   商品ID
     * @return json
     */
    public function delProduct($productId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->merchantUrl, 'update', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['product_id'] = $productId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 修改商品
     * 从未上架的商品所有信息均可修改，否则商品的名称(name)、商品分类(category)、商品属性(property)这三个字段不可修改
     *
     * @param string   $productId   商品ID
     * @param array   $productInfo   商品详细信息
     * @return json
     */
    public function updateProduct($productId, $productInfo)
    {
        $options = array();
        $options['me_url'] = sprintf($this->merchantUrl, 'update', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['product_id'] = $productId;
        $productInfo = $common->encodeUrl($productInfo, true);
        $data = array_merge($data, $productInfo);
        $data = $common->decodeUrl(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 查询商品
     *
     * @param string   $productId   商品ID
     * @return json
     */
    public function getProduct($productId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->merchantUrl, 'get', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['product_id'] = $productId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 获取指定状态的所有商品
     *
     * @param int   $status   商品状态(0-全部, 1-上架, 2-下架)
     * @return json
     */
    public function getProductByStatus($status)
    {
        $options = array();
        $options['me_url'] = sprintf($this->merchantUrl, 'getbystatus', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['status'] = $status;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 1.6	商品上下架
     *
     * @param string   $productId   商品ID
     * @param int   $status   商品状态(0-全部, 1-上架, 2-下架)
     * @return json
     */
    public function modProductStatus($productId, $status)
    {
        $options = array();
        $options['me_url'] = sprintf($this->merchantUrl, 'modproductstatus', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['product_id'] = $productId;
        $data['status'] = $status;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 获取指定分类的所有子分类
     *
     * @param string   $cateId   商品分类ID
     * @return json
     */
    public function getSub($cateId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->categoryUrl, 'getsub', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['cate_id'] = $cateId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 获取指定子分类的所有SKU
     *
     * @param string   $cateId   商品分类ID
     * @return json
     */
    public function getSku($cateId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->categoryUrl, 'getsku', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['cate_id'] = $cateId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 获取指定分类的所有属性
     *
     * @param string   $cateId   商品分类ID
     * @return json
     */
    public function getProperty($cateId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->categoryUrl, 'getproperty', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['cate_id'] = $cateId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 增加库存
     *
     * @param array   $cateId   商品ID
     * @param styring   $skuInfo   sku信息,格式"id1:vid1;id2:vid2",如商品为统一规格，则此处赋值为空字符串即可
     * @param int   $quantity   增加的库存数量
     * @return json
     */
    public function addStock($productId, $skuInfo, $quantity)
    {
        $options = array();
        $options['me_url'] = sprintf($this->stockUrl, 'add', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['product_id'] = $productId;
        $data['sku_info'] = trim($skuInfo);
        $data['quantity'] = $quantity;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 减少库存
     *
     * @param array   $cateId   商品ID
     * @param styring   $skuInfo   sku信息,格式"id1:vid1;id2:vid2",如商品为统一规格，则此处赋值为空字符串即可
     * @param int   $quantity   增加的库存数量
     * @return json
     */
    public function reduceStock($productId, $skuInfo, $quantity)
    {
        $options = array();
        $options['me_url'] = sprintf($this->stockUrl, 'reduce', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['product_id'] = $productId;
        $data['sku_info'] = trim($skuInfo);
        $data['quantity'] = $quantity;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 增加邮费模板
     *
     * @param array   $deliveryTemplate   邮费信息
     * @return json
     */
    public function addTemplate($deliveryTemplate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->expressUrl, 'add', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['delivery_template'] = $common->encodeUrl($deliveryTemplate, true);
        $data = $common->decodeUrl(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 删除邮费模板
     *
     * @param string   $templateId   邮费模板ID
     * @return json
     */
    public function delTemplate($templateId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->expressUrl, 'del', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['template_id'] = $templateId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 修改邮费模板
     *
     * @param string   $templateId   邮费模板ID
     * @param array   $deliveryTemplate   邮费信息
     * @return json
     */
    public function updateTemplate($templateId, $deliveryTemplate)
    {
        $options = array();
        $options['me_url'] = sprintf($this->expressUrl, 'update', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['template_id'] = $templateId;
        $data['delivery_template'] = $common->encodeUrl($deliveryTemplate, true);
        $data = $common->decodeUrl(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 获取指定ID的邮费模板
     *
     * @param string   $templateId   邮费模板ID
     * @return json
     */
    public function getTemplateById($templateId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->expressUrl, 'getbyid', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['template_id'] = $templateId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 获取所有邮费模板
     *
     * @return json
     */
    public function getAllTemplate()
    {
        $options = array();
        $options['me_url'] = sprintf($this->expressUrl, 'getall', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $info = $this->resourceOwner->getMeUrl();
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 增加分组
     *
     * @param array   $groupDetail   商品分组信息
     * @return json
     */
    public function addGroup($groupDetail)
    {
        $options = array();
        $options['me_url'] = sprintf($this->groupUrl, 'add', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['group_detail'] = $common->encodeUrl($groupDetail, true);
        $data = $common->decodeUrl(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 删除分组
     *
     * @param string   $groupId   邮费模板ID
     * @return json
     */
    public function delGroup($groupId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->groupUrl, 'del', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['group_id'] = $groupId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 修改分组属性（名称）
     *
     * @param string   $groupId   分组ID
     * @param string   $groupName   分组信息 名称
     * @return json
     */
    public function updateGroupProperty($groupId, $groupName)
    {
        $options = array();
        $options['me_url'] = sprintf($this->groupUrl, 'propertymod', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['group_id'] = $groupId;
        $data['group_name'] = $common->encodeUrl($groupName, true);
        $data = $common->decodeUrl(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 修改分组内商品
     *
     * @param string   $groupId   分组ID
     * @param array   $product   分组商品
     * @return json
     */
    public function updateGroupProduct($groupId, $product)
    {
        $options = array();
        $options['me_url'] = sprintf($this->groupUrl, 'productmod', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['group_id'] = $groupId;
        $data['product'] = $common->encodeUrl($product, true);
        $data = $common->decodeUrl(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 获取所有分组
     *
     * @return json
     */
    public function getAllGroups()
    {
        $options = array();
        $options['me_url'] = sprintf($this->groupUrl, 'getall', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $info = $this->resourceOwner->getMeUrl();
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 获取指定ID的分组
     *
     * @param string   $groupId   邮费模板ID
     * @return json
     */
    public function getGroupById($groupId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->groupUrl, 'getbyid', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['group_id'] = $groupId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 增加货架
     *
     * @param array   $shelfData   货架信息
     * @param string   $shelfBanner   货架招牌图片Url
     * @param string   $shelfName   货架名称
     * @return json
     */
    public function addShelf($shelfData, $shelfBanner, $shelfName)
    {
        $options = array();
        $options['me_url'] = sprintf($this->shelfUrl, 'add', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['shelf_data'] = $common->encodeUrl($shelfData, true);
        $data['shelf_banner'] = $common->encodeUrl($shelfBanner, true);
        $data['shelf_name'] = $common->encodeUrl($shelfName, true);
        $data = $common->decodeUrl(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 删除货架
     *
     * @param string   $shelfId   货架ID
     * @return json
     */
    public function delShelf($shelfId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->shelfUrl, 'del', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['group_id'] = $shelfId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 修改货架
     *
     * @param int   $shelfId   货架ID
     * @param array   $shelfData   货架信息
     * @param string   $shelfBanner   货架招牌图片Url
     * @param string   $shelfName   货架名称
     * @return json
     */
    public function updateShelf($shelfId, $shelfData, $shelfBanner, $shelfName)
    {
        $options = array();
        $options['me_url'] = sprintf($this->shelfUrl, 'mod', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $common = $this->container->get('core.common');
        $data['shelf_id'] = $shelfId;
        $data['shelf_data'] = $common->encodeUrl($shelfData, true);
        $data['shelf_banner'] = $common->encodeUrl($shelfBanner, true);
        $data['shelf_name'] = $common->encodeUrl($shelfName, true);
        $data = $common->decodeUrl(json_encode($data));

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 获取所有货架
     *
     * @return json
     */
    public function getAllShelf()
    {
        $options = array();
        $options['me_url'] = sprintf($this->shelfUrl, 'getall', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $info = $this->resourceOwner->getMeUrl();
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 获取指定ID的货架信息
     *
     * @param string   $shelfId   邮费模板ID
     * @return json
     */
    public function getShelvesById($shelfId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->shelfUrl, 'getbyid', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['shelf_id'] = $shelfId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 获取指定ID的订单信息
     *
     * @param string   $orderId   邮费模板ID
     * @return json
     */
    public function getOrderById($orderId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->orderUrl, 'getbyid', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['order_id'] = $orderId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 据订单状态/创建时间获取订单信息
     *
     * @param string   $status   订单状态(不带该字段-全部状态, 2-待发货, 3-已发货, 5-已完成, 8-维权中, )
     * @param int   $begintime   订单创建时间起始时间(不带该字段则不按照时间做筛选)
     * @param int   $endtime   订单创建时间终止时间(不带该字段则不按照时间做筛选)
     * @return json
     */
    public function getOrderByFilter($status = NULL, $begintime = NULL, $endtime = NULL)
    {
        $options = array();
        $options['me_url'] = sprintf($this->orderUrl, 'getbyfilter', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        !empty($status) && $data['status'] = $status;
        !empty($begintime) && $data['begintime'] = $begintime;
        !empty($endtime) && $data['endtime'] = $endtime;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 设置订单发货信息
     *
     * @param string   $orderId   订单ID
     * @param string   $deliveryCompany   物流公司ID(参考《物流公司ID》；当need_delivery为0时，可不填本字段；当need_delivery为1时，该字段不能为空；当need_delivery为1且is_others为1时，本字段填写其它物流公司名称)
     * @param string   $deliveryTrackNo   运单ID(当need_delivery为0时，可不填本字段；当need_delivery为1时，该字段不能为空；)
     * @param int   $needDelivery   商品是否需要物流(0-不需要，1-需要，无该字段默认为需要物流)
     * @param int   $isOthers   是否为6.4.5表之外的其它物流公司(0-否，1-是，无该字段默认为不是其它物流公司)
     * @return json
     */
    public function setOrderDelivery($orderId, $needDelivery = 1, $deliveryCompany = NULL, $deliveryTrackNo = NULL, $isOthers = 0)
    {
        $options = array();
        $options['me_url'] = sprintf($this->orderUrl, 'setdelivery', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['order_id'] = $orderId;
        $data['need_delivery'] = $needDelivery;
        $needDelivery && $data['delivery_company'] = $deliveryCompany;
        $needDelivery && $data['delivery_track_no'] = $deliveryTrackNo;
        $data['is_others'] = $isOthers;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 关闭订单
     *
     * @param string   $orderId   邮费模板ID
     * @return json
     */
    public function closeOrder($orderId)
    {
        $options = array();
        $options['me_url'] = sprintf($this->orderUrl, 'close', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['order_id'] = $orderId;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 上传图片
     *
     * @param string   $fileName   图片文件名(带文件类型后缀)
     * @return json
     */
    public function uploadImg($fileName)
    {
        $options = array();
        $options['me_url'] = sprintf($this->orderUrl, 'upload_img', parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['filename'] = $fileName;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }

    /**
     * 语义理解
     *
     * @param string   $query   输入文本串
     * @param string   $category   需要使用的服务类型，多个用“，”隔开，不能为空
     * @param float   $latitude   图片文件名(带文件类型后缀)
     * @param float   $longitude   图片文件名(带文件类型后缀)
     * @param string   $city   图片文件名(带文件类型后缀)
     * @param string   $city   图片文件名(带文件类型后缀)
     * @param string   $region   图片文件名(带文件类型后缀)
     * @return json
     */
    public function semantic($query)
    {
        $options = array();
        $options['me_url'] = sprintf($this->semanticUrl, parent::getAccessToken());
        $this->resourceOwner->setOption($options);

        $data = array();
        $data['filename'] = $query;
        $data = json_encode($data);

        $info = $this->resourceOwner->getMeUrl($data);
        if(isset($info['errcode'])&&$info['errcode']==0)
            return $info;

        parent::getError($info);

        return false;
    }


}