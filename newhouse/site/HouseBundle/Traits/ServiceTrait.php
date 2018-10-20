<?php
/**
 * Created by PhpStorm.
 * User: zhangshuzhen
 * Date: 2018/4/17
 * Time: 13:33
 */

namespace HouseBundle\Traits;

use CoreBundle\Functions\Common;
use Doctrine\Common\Cache\MemcacheCache;
use HouseBundle\Handler\HouseHandler;
use HouseBundle\Handler\SyncHandler;
use HouseBundle\Handler\ZhugeSsoHandler;
use HouseBundle\Handler\ZhugePassPortHandler;
use HouseBundle\Services\DB\Build;
use HouseBundle\Services\DB\Category;
use HouseBundle\Services\DB\CateLine;
use HouseBundle\Services\DB\CateCircle;
use HouseBundle\Services\DB\Collects;
use HouseBundle\Services\DB\Door;
use HouseBundle\Services\DB\Houses;
use HouseBundle\Services\DB\HousesArc;
use HouseBundle\Services\DB\HousesAttr;
use HouseBundle\Services\DB\HouseSem;
use HouseBundle\Services\DB\HouseSemTel;
use HouseBundle\Services\DB\HousesInfo;
use HouseBundle\Services\DB\HousesPrice;
use HouseBundle\Services\DB\InterAdviser;
use HouseBundle\Services\DB\InterAlbum;
use HouseBundle\Services\DB\InterHousescomment;
use HouseBundle\Services\DB\InterHousesintention;
use HouseBundle\Services\DB\Loupandongtai;
use HouseBundle\Services\DB\News;
use HouseBundle\Services\DB\HouseMake;
use HouseBundle\Services\DB\Prices;
use HouseBundle\Services\DB\Promotion;
use HouseBundle\Services\DB\PromotionOrder;
use HouseBundle\Services\DB\Pushs;
use HouseBundle\Services\DB\SemTel;
use HouseBundle\Services\DB\Topic;
use HouseBundle\Services\DB\TopicArc;
use HouseBundle\Services\DB\HousesTel;
use ManageBundle\Services\DB\Area;
use ManageBundle\Services\DB\AuthGroupArea;
use HouseBundle\Services\DB\CateMetro;
use HouseBundle\Services\DB\Showingsnews;
use HouseBundle\Services\DB\Subscribe;
use ManageBundle\Services\DB\Users;
use HouseBundle\Services\DB\UserBehavior;
use Symfony\Component\HttpFoundation\Session\Session;


trait ServiceTrait
{
    /**
     * @return Area
     */
    public function getAreaService()
    {
        return $this->get('db.area');
    }

    /**
     * @return AuthGroupArea
     */
    public function getAuthGroupAreaService()
    {
        return $this->get('db.auth_group_area');
    }

    /**
     * @return Build
     */
    public function getBuildsService()
    {
        return $this->get('house.build');
    }

    /**
     * @return Category
     */
    public function getCategoryService()
    {
        return $this->get('house.category');
    }

    /**
     * @return CateCircle
     */
    public function getCateCircleService()
    {
        return $this->get('house.cate_circle');
    }

    /**
     * @return CateLine
     */
    public function getCatelineService()
    {
        return $this->get('house.cate_line');
    }

    /**
     * @return CateMetro
     */
    public function getCatemetroService()
    {
        return $this->get('house.cate_metro');
    }

    /**
     * @return Collects
     */
    public function getCollectsService()
    {
        return $this->get('house.collects');
    }

    /**
     * @return Common
     */
    public function getCommonService()
    {
        return $this->get('cc');
    }

    /**
     * @return house_make
     */
    public function getHouseMakeService()
    {
        return $this->get('house.house_make');
    }

    /**
     * @return Users
     */
    public function getDbUserService()
    {
        return $this->get('db.users');
    }

    /**
     * @return Door
     */
    public function getDoorsService()
    {
        return $this->get('house.door');
    }

    /**
     * @return HousesArc
     */
    public function getHouseArcService()
    {
        return $this->get('house.houses_arc');
    }

    /**
     * @return Houses
     */
    public function getHouseService()
    {
        return $this->get('house.houses');
    }

    /**
     * @return HousesAttr
     */
    public function getHouseAttrService()
    {
        return $this->get('house.houses_attr');
    }

    /**
     * @return HousesInfo
     */
    public function getHouseInfoService()
    {
        return $this->get('house.houses_info');
    }

    /**
     * @return HousesPrice
     */
    public function getHousesPriceService()
    {
        return $this->get('house.houses_price');
    }

    /**
     * @return HouseSem
     */
    public function getHouseSemService()
    {
        return $this->get('house.sem');
    }

    /**
     * @return HouseSemTel
     */
    public function getHouseSemTelService()
    {
        return $this->get('house.sem_tel');
    }

    /**
     * @return Session
     */
    public function getHttpSessionService()
    {
        return $this->get('session');
    }

    /**
     * @return InterAdviser
     */
    public function getInterAdverseService()
    {
        return $this->get('house.inter_adviser');
    }

    /**
     * @return InterAlbum
     */
    public function getInterAlbumService()
    {
        return $this->get('house.inter_album');
    }

    /**
     * @return InterHousesintention
     */
    public function getInterHousesintentionService()
    {
        return $this->get('house.inter_housesintention');
    }

    /**
     * @return InterHousescomment
     */
    public function getInterHousesCommentService()
    {
        return $this->get('house.inter_housescomment');
    }

    /**
     * @return Loupandongtai
     */
    public function getLoupandongtaiService()
    {
        return $this->get('house.loupandongtai');
    }

    /**
     * @return MemcacheCache
     */
    public function getMemcacheService()
    {
        return $this->get('core.memcache');
    }

    /**
     * @return News
     */
    public function getNewsInfoService()
    {
        return $this->get('db.news');
    }

    /**
     * @return Prices
     */
    public function getPricesService()
    {
        return $this->get('house.prices');
    }


    /**
     * @return Promotion
     */
    public function getPromotionService()
    {
        return $this->get('house.promotion');
    }

    /**
     * @return PromotionOrder
     */
    public function getPromotionOrderService()
    {
        return $this->get('house.promotion_order');
    }

    /**
     * @return Pushs
     */
    public function getPushService()
    {
        return $this->get('house.pushs');
    }

    /**
     * @return Showingsnews
     */
    public function getShowingsewsService()
    {
        return $this->get('house.showingsnews');
    }

    /**
     * @return Subscribe
     */
    public function getSubscribeService()
    {
        return $this->get('house.subscribe');
    }

    /**
     * @return Topic
     */
    public function getTopicService()
    {
        return $this->get('house.topic');
    }

    /**
     * @return TopicArc
     */
    public function getTopicarcService()
    {
        return $this->get('house.topic_arc');
    }

    /**
     * @return HousesTel
     */
    public function getHousesTelService()
    {
        return $this->get('house.houses_tel');
    }

    // region Handler

    /**
     * @return HouseHandler
     */
    public function getHouseHandler()
    {
        return $this->get('house.house.handler');
    }

    /**
     * @return SyncHandler
     */
    public function getSyncHandler()
    {
        return $this->get('house.sync.handler');
    }

    /**
     * @return ZhugeSsoHandler
     */
    public function getZhugeSsoHandler()
    {
        return $this->get('house.zhugesso.handler');
    }

    /**
     * @return ZhugeSsoHandler
     */
    public function getZhugePassPortHandler()
    {
        return $this->get('house.zhugepassport.handler');
    }

    /**
     * @return UserBehavior
     */
    public function getUserBehaviorService()
    {
        return $this->get('house.user_behavior');
    }

    // endregion
}