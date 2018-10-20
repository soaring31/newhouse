<?php
/**
 * Created by PhpStorm.
 * User: zhangshuzhen
 * Date: 2018/4/10
 * Time: 17:57
 */

namespace HouseBundle\Controller\Api\V1;


use FOS\RestBundle\Controller\Annotations as Rest;
use HouseBundle\Controller\Api\RestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AreaController extends RestController
{
    public function getAreaAction()
    {
        return $this->view($this->getAreaService()->findOneBy(['id' => 1]));
    }

    public function getAreaGroupAction()
    {
        return $this->returnSuccess($this->getAuthGroupAreaService()->getTableByAll(1, 1000));
    }

    /**
     * @ApiDoc(description="张树振---获取城市热门商圈/区域")
     *
     *
     * @Rest\QueryParam(name="ename", description="查询标识 ename")
     * @Rest\QueryParam(name="city", description="查询标识 city")
     * @Rest\QueryParam(name="number", description="返回商圈数量", default=8)
     *
     */
    public function getHotAreaAction(Request $request)
    {
        return $this->returnSuccess([]);

        $form = $this->createFormBuilder([
            'ename'  => '',
            'city'   => '',
            'number' => 8,
        ], [
            'method'             => 'GET',
            'allow_extra_fields' => true,
            'csrf_protection'    => false
        ])
            ->add('ename', 'text')
            ->add('city', 'text')
            ->add('number', 'number')
            ->getForm();

        $form->submit($request->query->all(), false);
        if (!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();

        if (!$data['ename'] && !$data['city'])
            throw new BadRequestHttpException('ename, city 不能同时为空');

        if ($data['ename'] && $data['city'])
            $criteria = ['ename' => $data['ename'], 'city' => $data['city']];
        else
            $criteria = $data['ename'] ? ['ename' => $data['ename']] : ['city' => $data['city']];

        if (!$area = $this->getAuthGroupAreaService()->findOneBy($criteria))
            return $this->createNotFoundException('地区不存在');

        $circles = $this->getHouseService()->getHotCircle($area->getRulesarea(), $data['number']);

        $data = [];

        if (!$circles)
            return $this->returnSuccess($data);

        $house_url = $this->get('service_container')->getParameter('house_url');

        foreach ($circles as $circle) {
            if ($circle = $this->getCateCircleService()->findOneBy(['id' => $circle['c_id']])) {
                array_push($data, [
                    "id"         => $circle->getId(),
                    "name"       => $circle->getName(),
                    "pid"        => $circle->getPid(),
                    "area"       => $circle->getArea(),
                    "checked"    => $circle->getChecked(),
                    "oid"        => $circle->getOid(),
                    "attributes" => $circle->getAttributes(),
                    "sort"       => $circle->getSort(),
                    "issystem"   => $circle->getIssystem(),
                    "identifier" => $circle->getIdentifier(),
                    "createTime" => $circle->getCreateTime(),
                    "updateTime" => $circle->getUpdateTime(),
                    "isDelete"   => $circle->getIsDelete(),
                    "type"       => 'cate_circle',
                    'url'        => sprintf('%s/xinfang#region=%s&form=app', $house_url, $circle->getPid())
                ]);
            }
        }

        return $this->returnSuccess($data);
    }


    /**
     * @ApiDoc(description="陶麟-新版C端首页city信息接口")
     *
     * @return \FOS\RestBundle\View\View
     */
    public function postAreaOpencityAction()
    {
        $api_url  = $this->get('service_container')->getParameter('api_url');
        $url      = $api_url . "/API/City/getCity";
        $city     = $this->get('request.handler')->curl_get($url);
        $api_city = json_decode($city, true);
        if ($api_city['code'] == 200) {
            $api_city = $api_city['data'];
        } else {
            $api_city = array();
        }
        $cms_city     = $this->getAuthGroupAreaService()->getTableByAll(1, 1000);
        $cms_city     = $cms_city['data'];
        $api_city_jc  = array();
        $api_city_arr = array();
        foreach ($api_city as $key => $value) {
            $api_city_jc[]                = $value['city'];
            $api_city_arr[$value['city']] = $value;
        }
        $return_arr = array();
        $xin_city   = array();
        foreach ($cms_city as $key => $value) {
            if (in_array($value['city'], $api_city_jc)) {
                $api_city_value = $api_city_arr[$value['city']];
                $open_type      = $api_city_value['open_type'];
                $open_city_type = explode(",", $api_city_value['open_type']);
                if (!in_array(3, $open_city_type)) {
                    $open_type .= ",3";
                }
                if (!empty($api_city_value['quick'])) {
                    $quick = $api_city_value['quick'];
                } else {
                    $quick = "";
                }
                $return_arr[] = array(
                    "cityid"           => $api_city_value['cityid'],
                    "city"             => $api_city_value['city'],
                    "city_name"        => $api_city_value['city_name'],
                    "is_open"          => $api_city_value['is_open'],
                    "open_type"        => $open_type,
                    "open_agent_recom" => $api_city_value['open_agent_recom'],
                    "quick"            => $quick,
                    "is_api"           => 1
                );


            } else {
                $return_arr[] = array(
                    "cityid"           => $value['id'],
                    "city"             => $value['ename'],
                    "city_name"        => $value['name'],
                    "is_open"          => 1,
                    "open_type"        => 3,
                    "open_agent_recom" => 0,
                    "quick"            => "",
                    "is_api"           => 2
                );
            }

        }
        return $this->returnSuccess($return_arr);
    }

    /**
     * (description="获取城区id--王晓宇")
     *
     *
     * @Rest\QueryParam(name="city", description="城市简称")
     *
     */
    public function getAreaIdAction(Request $request)
    {
        $form = $this->createFormBuilder([
            'city' => '',
        ], [
            'method'             => 'GET',
            'allow_extra_fields' => true,
            'csrf_protection'    => false
        ])
            ->add('city', 'text')
            ->getForm();

        $form->submit($request->query->all(), false);
        if (!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();

        //查询条件
        $criteria = ['city' => $data['city']];
        //默认情况是北京
        if (!$area = $this->getAuthGroupAreaService()->findOneBy($criteria)) {
            $defaultArea = $this->getAuthGroupAreaService()->findOneBy(['city' => 'bj']);
            $areaId      = $defaultArea->getRulesarea();
        } else {
            $areaId = $area->getRulesarea();
        }

        return $this->returnSuccess($areaId);
    }

    /**
     * (description="获取全部已开通城市数据--王晓宇")
     *
     *
     * @Rest\QueryParam(name="city", description="城市简称")
     *
     */
    public function getAreaIdsAction(Request $request)
    {
        $area_list = [];
        $areas     = $this->getAuthGroupAreaService()->findBy(['is_delete' => 0]);
        if (!$areas['data'])
            return $this->returnSuccess([]);
        foreach ($areas['data'] as $k => $v) {
            $area_list[$k]['id']   = $v->getRulesarea();
            $area_list[$k]['city'] = $v->getCity();
        }
        return $this->returnSuccess($area_list);
    }

}
