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
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SubwayController extends RestController
{
    private $page = 1;
    private $limit = 10;

    /**
     * @ApiDoc(description="获取更多因子地铁线和地铁站--云鹏")
     *
     * @Rest\QueryParam(name="city", description="城市简称 bj", requirements="\d+")
     *
     */
    public function getSubwayLineAction(Request $request)
    {   
        $form = $this->createQueryForm();
        $form->submit($request->query->all(), false);
        if ($form->isSubmitted() && !$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
        
        $data = $form->getData();
        if(empty($data['city'])){
            throw new NotFoundHttpException('城市不能为空');
        }
        $criteria = [
            "city" => $data['city']
        ];

        if (!$authGroupArea = $this->getAuthGroupAreaService()->findOneBy($criteria))
            throw new BadRequestHttpException('城市数据不存在');

        $rulesArea = $authGroupArea->getRulesarea();
        $whereLine = [
            "area" => $rulesArea,
            'is_delete'=>0
        ];
        $cateLineList = $this->getCatelineService()->findBy($whereLine);
        $areaItem = $cateCircle = [];
        $whereCateMetro = [
            'area'=>$rulesArea,
            'is_delete'=>0
        ];
        $cateMetroList = $this->getCatemetroService()->findBy($whereCateMetro);
        foreach ($cateLineList['data'] as $key =>$val){
            $line[] = ['id'=>$val->getId(),'pid'=>0,'name'=>$val->getName(),'type'=>'line'];
        }
        foreach ($cateMetroList['data'] as $cateKey =>$cateVal){
            $station[] = ['id'=>$cateVal->getId(),'pid'=>$cateVal->getPid(),'name'=>$cateVal->getName(),'type'=>'station'];
        }
        $returnInfo = [
            'subwayline'=>$line,
            'station'=>$station
        ];
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