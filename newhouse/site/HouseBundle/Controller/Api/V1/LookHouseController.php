<?php
namespace HouseBundle\Controller\Api\V1;

use FOS\RestBundle\Controller\Annotations as Rest;
use HouseBundle\Controller\Api\RestController;
use HouseBundle\Entity\Houses;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Exception\LogicException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class LookHouseController extends RestController
{

    /**
     * @ApiDoc(description="武运晓--------看房团活动")
     * @Rest\RequestParam(name="cid", description="城市id" )
     */
    public function postNeedHouseAction(Request $request)
    {
        error_reporting(E_ALL^E_NOTICE^E_WARNING);
        $form = $this->createFormBuilder([
            'cid' => '',
        ], [
            'method' => 'POST',
            'csrf_protection' => false
        ])
            ->add('cid', 'text',[
                'constraints'=>[
                    new NotBlank(['message'=>'城市id"不能为空']),
                    //new Regex(['pattern'=>'((13[0-9])|(14[5|7])|(15([0-3]|[5-9]))|(18[0,5-9]))\d{8}$', 'message'=>'手机号不正确'])
                ]
            ])
            ->getForm();
        $form->submit($request->request->all());
        if(!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();
        $where = [
            "area" => $data['cid']

        ];
        $result = $this->getTopicService()->findBy($where,[
              'time' => 'desc'
        ]);

        foreach ($result['data'] as $v=>$house){
                $housepeople=[
                        'topic'=>$house->getID(),
                         "area" => $data['cid'],
                ];
                $countpeople = $this->getShowingsewsService()->findBy($housepeople);
                $lookid=[
                        'aid'=>$house->getID(),
                        "area" => $data['cid'],
                ];

                $loupanid = $this->getTopicarcService()->findBy($lookid);
                $id='';
                if($loupanid){

                        foreach ($loupanid['data'] as $dsid){

                                $id.=$dsid->getFromid().',';

                        }

                        $inwhere=[
                            "area" => $data['cid'],
                            'id'=>['in'=>trim($id,',')]
                        ];
                     }

                 $cityid = $this->getHouseService()->findBy($inwhere);

                if($house){
                    $resultinfo[]=['id' => $house->getId(),
                    'name' => $house->getName(),
                    'category' => $house->getCategory(),
                    'thumb' => $house->getThumb(),
                    'content' => $house->getContent(),
                    'abstract' => $house->getAbstract(),
                    'keywords' => $house->getKeywords(),
                    'time' => $house->getTime()->format('Y-m-d'),
                    'address' => $house->getAddress(),
                    'enroll' => $house->getEnroll(),
                    'tplcfg' => $house->getTplcfg(),
                    'imagelist' => $house->getImagelist(),
                    'author' => $house->getAuthor(),
                    'source' => $house->getSource(),
                    'discount' => $house->getDiscount(),
                    'atlas' => $house->getAtlas(),
                    'subtime' => strtotime($house->getSubtime()->format('Y-m-d')),
                    'show_time' => $house->getShowtime()->format('Y-m-d'),
                    'venue' => $house->getVenue(),
                    'hotline' => $house->getHotline(),
                    'area' => $house->getArea(),
                    'count' =>empty($countpeople['data'])?$countpeople['data']=0:$countpeople['data']=count($countpeople['data']),
                    'albumnumItems' => $cityid['data'],

                ];
            }

        }

        return $this->returnSuccess($resultinfo);
    }
    /**
     * @ApiDoc(description="武运晓--------看房活动详情")
     * @Rest\RequestParam(name="cid", description="城市id" )
     * @Rest\RequestParam(name="kid", description="看房活动id" )
     */
    public function postActiveInfoAction(Request $request)
    {

        $form = $this->createFormBuilder([
            'cid' => '',
            'kid' => '',

        ], [
            'method' => 'POST',
            'csrf_protection' => false
        ])
            ->add('cid', 'text',[
                'constraints'=>[
                    new NotBlank(['message'=>'城市id不能为空']),
                ]
            ])
            ->add('kid')
            ->getForm();
        $form->submit($request->request->all());
        if(!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();
        $where = [
            "id" => $data['kid'],
            "area" => $data['cid']

        ];
        $result = $this->getTopicService()->findBy($where,[
            'time' => 'desc'
        ]);
        foreach ($result['data'] as $v=>$house){
            $housepeople=[
                'topic'=>$house->getId(),
            ];
            $countpeople = $this->getShowingsewsService()->findBy($housepeople);
            $lookid=[
                'aid'=>$house->getID(),
            ];
            $loupanid = $this->getTopicarcService()->findBy($lookid);
            $id='';
            if($loupanid){

                foreach ($loupanid['data'] as $dsid){

                    $id.=$dsid->getFromid().',';

                }

                $inwhere=[
                    "area" => $data['cid'],
                    'id'=>['in'=>trim($id,',')]
                ];
            }
            $cityid = $this->getHouseService()->findBy($inwhere);

            foreach ($cityid['data'] as $k => $v){
                $v->setCateStatus($this->getCategoryService()->findCateGory($v->getCateStatus()));
                $v->setCateType($this->getCategoryService()->findCateGory($v->getCateType()));
            }
            if($house){
                $resultinfo[]=['id' => $house->getId(),
                    'name' => $house->getName(),
                    'category' => $house->getCategory(),
                    'thumb' => $house->getThumb(),
                    'content' => $house->getContent(),
                    'abstract' => $house->getAbstract(),
                    'keywords' => $house->getKeywords(),
                    'time' => $house->getTime()->format('Y-m-d'),
                    'address' => $house->getAddress(),
                    'enroll' => $house->getEnroll(),
                    'tplcfg' => $house->getTplcfg(),
                    'imagelist' => $house->getImagelist(),
                    'author' => $house->getAuthor(),
                    'source' => $house->getSource(),
                    'discount' => $house->getDiscount(),
                    'atlas' => $house->getAtlas(),
                    'subtime' => strtotime($house->getSubtime()->format('Y-m-d')),
                    'show_time' =>$house->getShowtime()->format('Y-m-d'),
                    'venue' => $house->getVenue(),
                    'hotline' => $house->getHotline(),
                    'area' => $house->getArea(),
                    'count' =>empty($countpeople['data'])?$countpeople['data']=0:$countpeople['data']=count($countpeople['data']),
                    'albumnumItems' => $cityid['data'],

                ];
            }

        }
        return $this->returnSuccess($resultinfo);


    }
}