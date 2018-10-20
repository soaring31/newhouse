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

class AdController extends RestController
{
    private $page = 1;
    private $limit = 10;

    /**
     * @ApiDoc(description="获取广告标示id--云鹏")
     *
     * @Rest\QueryParam(name="city", description="城市简称 bj", requirements="\d+")
     *
     */
    public function getAdListAction(Request $request)
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
        $id = $authGroupArea->getId();
        $returnInfo = [
            "type"=>1,
            "home"=>[
                "tanping"=>[($id+100)."11101101"],
                "kaiping"=>[($id+100)."11101102"],
                "hengfu"=>[($id+100)."11101103",($id+100)."11101104",($id+100)."11101105"],
                "wenzilian"=>[($id+100)."11101106",($id+100)."11101107",($id+100)."11101108"],
                "xinxiliu"=>[($id+100)."11101109"],
                "search_hengfu"=>[($id+100)."11102101",($id+100)."11102102"],
                "search_xinxiliu"=>[($id+100)."11102103"],
                "list_hengfu"=>[($id+100)."11103102",($id+100)."11103103"],
                "list_xinxiliu1"=>[($id+100)."11103107"],
                "list_xinxiliu2"=>[($id+100)."11103108"],
                "news_xinxiliu"=>[($id+100)."11104101"],
                "news_hengfu"=>[($id+100)."11104102",($id+100)."11104103",($id+100)."11104104"]
            ]
        ];
        return $this->returnSuccess($returnInfo);
    }
    /**
     * @ApiDoc(description="获取城市id+100--云鹏")
     *
     * @Rest\QueryParam(name="city", description="城市简称 bj", requirements="\d+")
     *
     */
    public function getAdCityAction(Request $request)
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
            $id = $authGroupArea->getId();
            $returnInfo = [
                "type"=>1,
                "home"=>$id+100
                
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