<?php
/**
 * Created by PhpStorm.
 * User: zhangshuzhen
 * Date: 2018/4/11
 * Time: 16:32
 */

namespace HouseBundle\Controller\Api\V1;


use FOS\RestBundle\Controller\Annotations as Rest;
use HouseBundle\Controller\Api\RestController;
use HouseBundle\Entity\Houses;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CityItemController extends RestController
{
    private $page = 1;
    private $limit = 10;

    /**
     * @ApiDoc(description="获取更多因子城区和商圈--云鹏")
     *
     * @Rest\QueryParam(name="city", description="城市简称 bj", requirements="\d+")
     *
     */
    public function getAreaAction(Request $request)
    {   
        $form = $this->createQueryForm();
        $form->submit($request->query->all(), false);
        if ($form->isSubmitted() && !$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
        
        $data = $form->getData();
        $criteria = [
            "city" => $data['city']
        ];

        if (!$authGroupArea = $this->getAuthGroupAreaService()->findOneBy($criteria))
            return $this->createNotFoundException('城市信息不存在');

        $rulesArea = $authGroupArea->getRulesarea();
        $whereArea = [
            "pid" => $rulesArea,
            'is_delete'=>0
        ];
        $areaList = $this->getAreaService()->findBy($whereArea);
//         dump($areaList['data'][0]->getId());die;
        $areaItem = $cateCircle = [];
        $whereCateCircle = [
            'area'=>$rulesArea,
            'is_delete'=>0
        ];
        $cateCirCleList = $this->getCateCircleService()->findBy($whereCateCircle);
        foreach ($areaList['data'] as $key =>$val){
            $areaItem[] = ['id'=>$val->getId(),'pid'=>0,'name'=>$val->getName(),'type'=>'cityarea'];
        }
        foreach ($cateCirCleList['data'] as $cateKey =>$cateVal){
            $cateCircle[] = ['id'=>$cateVal->getId(),'pid'=>$cateVal->getPid(),'name'=>$cateVal->getName(),'type'=>'cityarea2'];
        }
        $returnInfo = [];
        $returnInfo = array_merge($areaItem, $cateCircle);

        return $this->returnSuccess($returnInfo);
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    private function createQueryForm()
    {
        return $this->createFormBuilder([
            'city' => 'bj',
        ], [
            'method' => 'GET',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ])
            ->add('city')
            ->getForm();
    }
}