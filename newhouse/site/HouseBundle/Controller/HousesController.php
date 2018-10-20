<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年08月04日
 */

namespace HouseBundle\Controller;

use HouseBundle\Traits\ServiceTrait;
use Symfony\Component\HttpFoundation\Request;

/**
 *
 * @author house
 */
class HousesController extends Controller
{
    use ServiceTrait;

    /**
     * index
     * house
     */
    public function indexAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * map
     * house
     */
    public function mapAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * map
     * house
     */
    public function dianpingAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * list
     * house
     */
    public function listAction(Request $request)
    {
        //定义数组
        $buildIds = [];
        //获取session
        $area = $request->getSession()->get('area');
        //根据城市ID查询分站ID
        $findAreaId = $this->getAuthGroupAreaService()->findOneBy(['rulesarea' => $area]);

        $buildIds['cy_array'] = $this->getHouseHandler()->getChuanyangAdIds($findAreaId->getRulesarea());
        $buildIds['cy_h5_array'] = $this->getHouseHandler()->getChuanyangAdIds($findAreaId->getRulesarea(),[
            'item'=>'h5'
        ]);

        $this->parameters = array_merge($this->parameters, $buildIds);
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * @todo 抽时间抽出来
     *
     * 用户协议
     *
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function agreementAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * @todo 抽时间抽出来
     *
     * 隐私声明协议
     *
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function statementAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * @todo 抽时间抽出来
     *
     * 隐私声明协议 下 用户声明
     *
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function declarationAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * @todo 抽时间抽出来
     *
     * 关于我们
     *
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function aboutsAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * @todo 抽时间抽出来
     *
     * 开放平台
     *
     * @return array|\Symfony\Component\HttpFoundation\Response
     */
    public function platformAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * detail
     * house
     */
    public function detailAction(Request $request)
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * detail
     * house
     */
    public function detail1Action()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * detail
     * house
     */
    public function ddetailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * news
     * house
     */
    public function dnewsAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * price
     * house
     */
    public function dpricesAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * photo
     * house
     */
    public function dphotosAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * planintro
     * house
     */
    public function dplanintroAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * spehouse
     * house
     */
    public function dspehouseAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * comment
     * house
     */
    public function dcommentAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * comment
     * house
     */
    public function ddongtaiAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }


    /**
     * contrast
     * house
     */
    public function contrastAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * chafangjia
     * house
     */
    public function cfjAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 实现的hAsk方法
     * house
     */
    public function daskAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 楼盘对比详情
     * house
     */
    public function comparisonAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 楼盘app
     * house
     */
    public function blockAction()
    {

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * app详情
     * house
     */
    public function detailblockAction()
    {

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 楼盘详情app
     * house
     */
    public function ddetailblockAction()
    {

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 意向
     * house
     */
    public function yixiangblockAction()
    {

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 价格详情app
     * house
     */
    public function dpricesblockAction()
    {

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的dqwbj方法
    * house
    */
    public function dqwbjAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的dxfgw方法
    * house
    */
    public function dxfgwAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 相册二级页面
     * house
     */
    public function albumAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 动态资讯二级列表页
     * house
     */
    public function dynamicAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 动态资讯二级详情页
     * house
     */
    public function dynamic_detailAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 产权声明
     * house
     */
    public function aboutsfdAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 详情页二级开发商页面
     * house
     */
    public function infodeveloperAction()
    {
        return $this->render($this->getBundleName(), $this->parameters);
    }
}