<?php


namespace HouseBundle\Controller\Api\V1;
use FOS\RestBundle\Controller\Annotations as Rest;
use HouseBundle\Controller\Api\RestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SubscribeController extends RestController
{   

    private $page = 1;
    private $limit = 10;

    /**
     * @ApiDoc(description="新房订阅-订阅列表--王晓宇")
     * 
     * @Rest\QueryParam(name="city", description="地区 city unRequired", requirements="[a-zA-Z\d]")
     * @Rest\QueryParam(name="uid", description="用户ID Required")
     * @Rest\QueryParam(name="tel", description="手机号 Required")
     * @Rest\QueryParam(name="page", description="页数", requirements="\d+", default="1")
     * @Rest\QueryParam(name="limit", description="数量", requirements="\d+", default="10")
     */
    public function postSearchSubAction(Request $request)
    {
        //定义数组
        $subHouses = array();
        //定义请求参数默认值以及请求方式
        $form = $this->createFormBuilder([
            'city' => 'bj',
            'uid' => '',
            'tel' => '',
            'page' => $this->page,
            'limit' => $this->limit,
        ], [
            'method' => 'POST',
            'csrf_protection' => false
        ])
            ->add('city', 'text')
            ->add('uid', 'text')
            ->add('tel', 'text')
            ->add('page', 'text')
            ->add('limit', 'text')
            ->getForm();

        $form->submit($request->request->all(), false);//接收请求数据
        if (!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
        $data = $form->getData();//获取请求数据
        $page  = $data['page'];
        $limit = $data['limit'];
        if(empty($data['uid']) && empty($data['tel']))
            throw new BadRequestHttpException('UID 或者 手机号 必填');
        //查询城市ID
        $criteria = [
            "city" => $data['city']
        ];
        if (!$authGroupArea = $this->getAuthGroupAreaService()->findOneBy($criteria))
            throw new BadRequestHttpException('城市信息不存在');

        //查询订阅的楼盘ID
        $intentWhere = [];
        $intentWhere['area'] = $authGroupArea->getRulesarea();
        if($data['uid'])
            $intentWhere['uid'] = $data['uid'];
        if($data['tel'])
            $intentWhere['tel'] = $data['tel'];
        $findIntents = $this->getInterHousesintentionService()->findBy($intentWhere,['create_time','desc'],$limit,$page);
        if (!$findIntents['data'])
            return $this->returnSuccess([]);//返回空数据

        //查询楼盘信息
        $houseUrl  = $this->get('service_container')->getParameter('house_url');
        foreach ($findIntents['data'] as $k=>$v){
            $findSubHouse = $this->getHouseService()->findOneBy(['area'=>$authGroupArea->getRulesarea(),'id'=>$v->getAid()]);
            if($findSubHouse){
                $region = '';
                $cateCircle = '';
                $subHouses[$k]['id'] = $findSubHouse->getId();
                $subHouses[$k]['name'] = $findSubHouse->getName();
                $subHouses[$k]['thumb'] = $findSubHouse->getThumb();
                if (!preg_match('/(http:\/\/)|(https:\/\/)/i', $findSubHouse->getThumb()) && $findSubHouse->getThumb())
                    $value['thumb'] = $houseUrl . "/" . $findSubHouse->getThumb();
                $subHouses[$k]['dj'] = $findSubHouse->getDj() ? $findSubHouse->getDj()."元/平" : "待定";
                //查询城区
                $findRegion = $this->getAreaService()->findOneBy(['pid'=>$findSubHouse->getArea(),'id'=>$findSubHouse->getRegion()]);
                if($findRegion)
                    $region = $findRegion->getName();
                //查询商圈
                $findCateCircle = $this->getCateCircleService()->findOneBy(['id'=>$findSubHouse->getCateCircle(),'is_delete'=>0]);
                if($findCateCircle)
                    $cateCircle = $findCateCircle->getName();

                $subHouses[$k]['address'] = $findSubHouse->getAddress() ? "[".$region."-".$cateCircle."]".$findSubHouse->getAddress() : "暂无";
                //查询销售状态
                $findCates = $this->getCategoryService()->findOneBy(['id'=>$findSubHouse->getCateStatus()]);
                $subHouses[$k]['catestatus'] = $findCates->getName();
            }
        }

        return $this->returnSuccess($subHouses);//以json格式返回数据
	}


}