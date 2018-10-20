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

class SearchController extends RestController
{
    private $page = 1;
    private $limit = 4;

    /**
     * @ApiDoc(description="武运晓-------------获取楼盘信息")
     *
     * @Rest\QueryParam(name="city", description="城市")
     * @Rest\QueryParam(name="type", description="1二手房2资讯", requirements="\d+", default="1")
     * @Rest\QueryParam(name="house_type", description="1二手房，3新房", requirements="\d+", default="15")
     */
    public function postGuesswordAction(Request $request)
    {
       

        $Chengqu=[];
        $shangquaninfo=[];
        $newLine=[];
        $newMetro=[];
        $loupaninfo=[];
        $form = $this->createQueryForm();
        $house_url = $this->get('service_container')->getParameter('house_url');
        $form->submit($request->request->all(), false);
        if ($form->isSubmitted() && !$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();

        $keyword = $data['word'];
        $criteria = [
            "city" => $data['city']
        ];

        if (!$authGroupArea = $this->getAuthGroupAreaService()->findOneBy($criteria))
            return $this->createNotFoundException('城市信息不存在');

        $rulesarea = $authGroupArea->getRulesarea();

        //对接传漾广告的楼盘
        $cyInfo=$this->getHouseHandler()->getChuanyangAdIds($rulesarea, ['item'=>$keyword]);
        $chuanYangId='';
        if($cyInfo){
               foreach ($cyInfo as $key => $value) {
                    $chuanYangId.=$value.",";
               }
            
              
        }
        $cyWhere=[
            "area" => $rulesarea,
            'id'=>
                [
                    'in'=>trim($chuanYangId,',')
                ]

            ];
        $cyHouses = $this->getHouseService()->findBy($cyWhere); 

        $where_area = [
            "checked" => 1,
            "area" => $rulesarea,
            'name'=>[
                'orX'=>[
                    ['name'=>['like'=>"%$keyword%"]],
                    ['ename'=>['like'=>"%$keyword%"]],
                    ['address'=>['like'=>"%$keyword%"]]
                ]
            ]

        ];

        $where_chengqu = [
            "pid" => $rulesarea,
            'name'=>[
                'orX'=>[
                    ['name'=>['like'=>"%$keyword%"]],
                ]
            ]
        ];

        $areainfos = $this->getAreaService()->findBy($where_chengqu,[],$this->limit,$this->page);
        $areainfoId = !empty($areainfos['data']) ?  (current($areainfos['data'])->getId()) : 0;

        $subway_where=[
            "area" => $rulesarea,
            'name'=>[
                'orX'=>[
                    ['name'=>['like'=>"%$keyword%"]],
                ]
            ]
        ];
        $shangquan_where=[
            "area" => $rulesarea,
            'name'=>[
                'orX'=>[
                    ['name'=>['like'=>"%$keyword%"]],
                ]
            ]
        ];

        // 商圈信息
        $cateCircle = $this->getCateCircleService()->findOneBy($shangquan_where);
        $houses = $this->getHouseService()->findBy($where_area, [
            'kpdate' => 'desc'
        ], $this->limit,$this->page);
        if($cyHouses['data']){
            
                $houses['data']=array_merge($cyHouses['data'],$houses['data']);
        };
        if($data['houseType']==3){

            if(!empty($houses['data'])){
                foreach ($houses['data'] as &$house) {
                    if (!$house instanceof Houses)
                        throw new \Exception('实体无效');

                    if($house){
                        $loupaninfo[]=['id' => $house->getId(),
                                       'name' => $house->getName(),
                                       'cateStatus' => $house->getCateStatus(),
                                       'dj' => $house->getDj() == 0 ? '待定' : $house->getDj(),
                                       'address' => $house->getAddress(),
                                       'area' => $house->getArea(),
                                       'cateCircle' => $house->getCateCircle(),
                                       'field' => 'borough_id',
                                       'type_name' => '新房',
                                       'type_id' => 6,
                                       'url' => sprintf('%s/xinfang/detail/%s.html',$house_url,$house->getId()),
                        ];
                    }
                }
            }

            if($cateCircle){
                    $shangquaninfo[]=['id' => $cateCircle->getId(),
                        'name' => $cateCircle->getName(),
                        'pid' => $cateCircle->getPid(),
                        'oid' => $cateCircle->getOid(),
                        'area' => $cateCircle->getArea(),
                        'createTime' => $cateCircle->getCreateTime(),
                        'type_name' => '商圈',
                        'field' => 'cate_circle',
                        'type_id' => 2,
                        'url' => sprintf('%s/xinfang/%s-%s?form=app',$house_url,$areainfoId,$cateCircle->getId()),
                    ];
            }

            $cateLineId = 0;

            $CateLines = $this->getCatelineService()->findBy($subway_where,[],$this->limit,$this->page);
            $CateMetros  = $this->getCatemetroService()->findBy($subway_where,[],$this->limit,$this->page);

            if(!empty($CateLines['data'])){
                foreach ($CateLines['data'] as $k=>&$CateLine){
                    if($k == 0)
                        $cateLineId = $CateLine->getId();

                    $newLine[]=['id' => $CateLine->getId(),
                        'name' => $CateLine->getName(),
                        'sort' => $CateLine->getsort(),
                        'oid' => $CateLine->getOid(),
                        'area' => $CateLine->getArea(),
                        'createTime' => $CateLine->getCreateTime(),
                        'type_name' => '地铁线',
                        'field' => 'cate_line',
                        'type_id' => 4,
                        'url' => sprintf('%s/xinfang/%s-no-%s?form=app',$house_url, $areainfoId,$CateLine->getId()),
                    ];

                }
            }

            if(!empty($CateMetros['data'])){
                foreach ($CateMetros['data'] as &$CateMetro){
                    $newMetro[]=['id' => $CateMetro->getId(),
                        'name' => $CateMetro->getName(),
                        'pid' => $CateMetro->getPid(),
                        'oid' => $CateMetro->getOid(),
                        'area' => $CateMetro->getArea(),
                        'createTime' => $CateMetro->getCreateTime(),
                        'type_name' => '地铁站',
                        'field' => 'cate_metro',
                        'type_id' => 5,
                        'url' => sprintf('%s/xinfang/%s-no-%s-%s?form=app',$house_url,$areainfoId,$cateLineId,$CateMetro->getId()),
                    ];

                }
            }


            if(!empty($areainfos['data'])){
                foreach($areainfos['data'] as &$areainfo){
                    $Chengqu[]=['id' => $areainfo->getId(),
                        'name' => $areainfo->getName(),
                        'sort' => $areainfo->getsort(),
                        'pid' => $areainfo->getPid(),
                        'type' => $areainfo->getType(),
                        'createTime' => $areainfo->getCreateTime(),
                        'type_name' => '城区',
                        'field' => 'area',
                        'type_id' => 1,
                        'url' => sprintf('%s/xinfang/%s?form=app',$house_url,$areainfo->getId()),
                    ];
                }
            }


            $returnInfo=array_slice(array_merge($Chengqu,$shangquaninfo,$newLine,$newMetro,$loupaninfo),0,10);
            return $this->returnSuccess($returnInfo);
        }

        if($data['houseType']==5){
            $zixunhere = [
                "area" => $rulesarea,
                'name'=>[
                    'orX'=>[
                        ['name'=>['like'=>"%$keyword%"]],
                        ['content'=>['like'=>"%$keyword%"]]
                    ]
                ]
            ];

            $newsinfo = $this->getNewsInfoService()->findBy($zixunhere,[],$this->limit,$this->page);
            return $this->returnSuccess($newsinfo);
        }
    }

    /**
     * @return \Symfony\Component\Form\Form
     */
    private function createQueryForm()
    {
        return $this->createFormBuilder([
            'city' => 'bj',
        ], [
            'method' => 'POST',
            'csrf_protection' => false,
            'allow_extra_fields' => true
        ])
            ->add('type')
            ->add('city')
            ->add('houseType')
            ->add('area_id')
            ->add('word')
            ->getForm();
    }
}