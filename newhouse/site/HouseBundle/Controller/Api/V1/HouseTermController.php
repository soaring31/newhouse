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

class HouseTermController extends RestController
{
    private $page = 1;
    private $limit = 10;

    /**
     * @ApiDoc(description="获取更多因子--云鹏")
     *
     * @Rest\QueryParam(name="city", description="城市简称 bj", requirements="\d+")
     *
     */
    public function getHouseTermAction(Request $request)
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

        $rulesarea = $authGroupArea->getRulesarea();
        $whereCateGoryDj = [
            "ename" => 'dj',
            'is_delete'=>0,
            "checked"=>1
        ];
        $cateGory = $this->getCategoryService()->findBy($whereCateGoryDj,["sort"=>'asc']);
        $djArr = $roomArr = $moreArr = $cateTypeArr = [];
        if($cateGory['data']){
            foreach ($cateGory['data'] as $djk =>$djv){
                $djArr[] = [
                    "id"=>$djv->getId(),
                    "title"=>$djv->getName(),
                    "pms"=>$djv->getSqlstr(),
                ];
            }
        }
        $whereRoom = [
            "ename"=>'shi',
            'is_delete'=>0,
            "checked"=>1
        ];
        $room = $this->getCategoryService()->findBy($whereRoom);
        if($room['data']){
            foreach ($room['data'] as $roomk =>$roomv){
                $roomArr[] = [
                    "pms"=>$roomv->getId(),
                    "title"=>$roomv->getName(),
                ];
            }
        }
        $whereCateType = [
            "ename"=>'cate_type',
            'is_delete'=>0,
            "checked"=>1
        ];
        $cateType = $this->getCategoryService()->findBy($whereCateType);
        if($cateType['data']){
            foreach ($cateType['data'] as $catek =>$catev){
                $cateTypeArr[] = [
                    "key"=>$catev->getId(),
                    "value"=>$catev->getName(),
                ];
            }
        }
        $teseLpArr = [
            ['key'=>1,"value"=>"低总价"],
            ['key'=>2,"value"=>"小户型"],
            ['key'=>3,"value"=>"品牌地产"],
            ['key'=>4,"value"=>"现房"],
            ['key'=>5,"value"=>"地铁沿线"]
        ];
        $returnInfo = [
            'price'=>$djArr,
            'room'=>$roomArr,
            "more"=>[
                ["name"=>"物业类型","key"=>'cate_type','is_checkbox'=>2,'data'=>$cateTypeArr],
                ["name"=>"特色","key"=>'tslp','is_checkbox'=>2,'data'=>$teseLpArr],
            ],
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


    /**
     * @ApiDoc(description="获取app新房底部图标--陶麟")
     *
     * @Rest\QueryParam(name="area", description="城市id", requirements="\d+")
     *
     */
    public function getHouseCityOperationAction(Request $request)
    {
        $data  = $request->query->all();
        $info = $this->getAreaService()->findOneBy(array('id'=>$data['area']));
        $returnInfo = [];
        if($info){
            $newComplex = [
                "name"=>"新开楼盘",
                "type"=>2,
                "icon"=>"http://file.zhugefang.com/image/png/xinkailoupan-weidianji.png",
                "icon_click"=>"http://file.zhugefang.com/image/png/xinkailoupan-dianji.png"
            ];
            $teamComplex = [
                "name"=>"看房团",
                "type"=>3,
                "icon"=>"http://file.zhugefang.com/image/png/kanfangtuan-weidianji.png",
                "icon_click"=>"http://file.zhugefang.com/image/png/kanfangtuan-dianzhong.png"
            ];
            $findComplex = [
                "name"=>"房价找房",
                "type"=>4,
                "icon"=>"http://file.zhugefang.com/image/png/fangjiazhaofang-weidianji.png",
                "icon_click"=>"http://file.zhugefang.com/image/png/fangjiazhaofang-dianzhong.png"
            ];
//            $brandComplex = ["name"=>"品牌专区","type"=>5,"icon"=>"http://file.zhugefang.com/image/png/pinpaizhuanqu-weidianzhong.png","icon_click"=>"http://file.zhugefang.com/image/png/pinpaizhuanqu-dianzhong.png"];
            $returnInfo = [
//                $newComplex,
//                $teamComplex,
//                $findComplex
            ];

        }
        return $this->returnSuccess($returnInfo);
    }
}