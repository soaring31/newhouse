<?php
namespace HouseBundle\Controller\Api\V1;

use FOS\RestBundle\Controller\Annotations as Rest;
use HouseBundle\Controller\Api\RestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use HouseBundle\Services\DB\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ColletController extends RestController
{

    /**
     * @ApiDoc(description="武运晓--------------添加我的收藏")
     *
     * @Rest\RequestParam(name="aid", description="楼盘 ID" )
     * @Rest\RequestParam(name="uid", description="用户id" )
       @Rest\RequestParam(name="uid", description="用户id" )
     * @Rest\RequestParam(name="type", description="收藏类型 0 取消收藏 1 添加收藏" )
     *
     */
    public function postAddColletAction(Request $request)
    {
        $form = $this->createFormBuilder([
            'aid' => '',
            'uid' => '',
            'type' => '',
            'token'=>'',
            'platformType'=>'',
            'appName'=>'',
            'device_only_id'=>'',
            'city'=>'',
        ], [
            'method' => 'POST',
            'csrf_protection' => false
        ])
            ->add('aid', 'text',[
                'constraints'=>[
                    new NotBlank(['message'=>'楼盘ID不能为空']),
                ]
            ])
            ->add('uid', 'text',[
                'constraints'=>[
                    new NotBlank(['message'=>'用户ID不能为空']),
                ]
            ])
            ->add('type', 'text',[
                'constraints'=>[
                    new NotBlank(['message'=>'type不能为空']),
                ]
            ])
            ->add('token', 'text', array('label' => 'token'))
            ->add('platformType', 'text', array('label' => '应用类型'))
            ->add('appName', 'text', array('label' => '应用名称'))
            ->add('device_only_id', 'text', array('label' => '设备ID'))
            ->add('city', 'text', array('label' => '城市'))
            ->add('spread', 'text', array('label' => '客户来源渠道'))
            ->add('kwd', 'text', array('label' => 'sem关键词id'))
            ->add('pid', 'text', array('label' => 'sem计划id'))
            ->add('unitid', 'text', array('label' => 'sem单元id'))
            ->add('refe', 'text', array('label' => '搜索词'))
            ->getForm();
        $form->submit($request->request->all());
        if(!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
        $data = $form->getData();

        $user = $this->getDbUserService()->findOneBy(['oid'=>$data['uid']]);
        $uid=$user->getId();
        $whereInfo=['uid'=>$uid,'aid'=>$data['aid']];
        if($data['type']==1){
                //神策埋点---王晓宇
                $this->getHouseHandler()->getBurialPoint($data,'collect');

                if($this->getCollectsService()->findOneBy($whereInfo)){
                        throw new BadRequestHttpException('禁止重复收藏');
                }
                else{

                       
                        if($user){
                            $uid=$user->getId();
                        }else{
                            throw new BadRequestHttpException('用户信息不存在');
                        }


                        $info = $this->getCollectsService()->addCollects($uid,$data['aid']);

                        if($info){
                                return $this->returnSuccess($info);
                        }else{
                            throw new BadRequestHttpException('收藏失败');
                        }

                }
        }
        if($data['type']==0){
            //神策埋点---王晓宇
            $this->getHouseHandler()->getBurialPoint($data,'cancelCollect');

            $saveData['is_delete']=1;
            $saveInfo=$this->getCollectsService()->findOneBy($whereInfo);
            if($saveInfo){
                    $id= $saveInfo->getId();
            }else{
                throw new BadRequestHttpException('收藏数据不存在');
            }
            $save=$this->getCollectsService()->update($id,$saveData, null, false);
            if($save){
                return $this->returnSuccess($save);
            }
        }
    }
    /**
     * @ApiDoc(description="武运晓--------------展现用户我的收藏")
     * @Rest\RequestParam(name="uid", description="用户id" )
     * @Rest\RequestParam(name="city", description="城市id" )
     *
     */
    public function postShowColletAction(Request $request)
    {
        $form = $this->createFormBuilder([
            'uid' => '',
            'city' => '',
        ], [
            'method' => 'POST',
            'csrf_protection' => false
        ])

            ->add('uid', 'text',[
                'constraints'=>[
                    new NotBlank(['message'=>'用户ID不能为空']),
                ]
            ])
            ->add('city', 'text',[
                'constraints'=>[
                    new NotBlank(['message'=>'城市ID不能为空']),
                ]
            ])
            ->getForm();
        $form->submit($request->request->all());
        if(!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
        $data = $form->getData();
        $user = $this->getDbUserService()->findOneBy(['oid'=>$data['uid']]);
        if($user){
            $uid=$user->getId();
        }else{
            throw new BadRequestHttpException('用户信息不存在');
        }
        empty($uid)?$uid=$data['uid']:$uid=$uid;
        $whereInfo=['uid'=>$uid];
        $cityId=$this->getAuthGroupAreaService()->findOneBy(['city'=>$data['city']]);
        $data['city']=$cityId->getRulesArea();
        $colletInfo=$this->getCollectsService()->findBy($whereInfo);
        $allid=[];
        foreach ($colletInfo['data'] as &$all_house_id){
            $allid[]=['id' =>$all_house_id->getAid(),
            ];
        }

        $inid='';
        foreach($allid as $item=>$value){
            $inid.=$value['id'].",";

        }

        $idIn=rtrim($inid,',');
        $whereHouse = [
            'area'=>$data['city'],
            'id'=>[
                'in'=>$idIn
            ]
        ];
        ;
        $myColletsHouse=$this->getHouseService()->findBy($whereHouse);
        
       
        $api_url = $this->get('service_container')->getParameter('api_url');
        $sell_house_price_url = $api_url . "/API/NewHouseBorough/getusercollcomplist";
        $data = array(
            "city" =>$data['city'],
            "user_id" =>$uid,
            "pageStart" =>'300',
        );
        $cateColor=[
        '309' => '#FF8400',     // 在售
        '117' => '#FF5C36',     // 尾盘
        '114' => '#3F91F0',     // 待售
        '118' => '#AEAEAE',     // 售罄
        '115' => '#FF8400',     // 期房在售
        '116' => '#FF8400',     // 现房在售
        ];
        if(!empty($myColletsHouse['data'])){
                foreach ($myColletsHouse['data'] as $k => &$v){
                $region=$this->getAreaService()->findOneBy(['id'=>$v->getRegion()]);
                $circle =$this->getCateCircleService()->findOneBy(['id'=>$v->getCateCircle()]);    

                $status=['name'=>$this->getCategoryService()->findCateGory($v->getCateStatus()),'rgb'=>$cateColor[$v->getCateStatus()]];
                $v->setStatus($status);
                $v->setdj($v->getdj()?$v->getdj(). "/平起" : "待定");
                $v->setCateStatus($this->getCategoryService()->findCateGory($v->getCateStatus()));
                $house=$this->getHouseService()->findOneBy(['id'=>$v->getId()]);
                $tage=$this->getHouseHandler()->getTags($house);
                $v->setTag($v->getTag()?$v->getTag:[]);
                $v->setCateType($tage);
                $address="[".$region->getName()."-".$circle->getName()."]".$v->getAddress();
                $v->setAddress($address);

            }
        }
        $sell_house_price_data = $this->get('request.handler')->curl_post($sell_house_price_url, $data);
        $sell_data = json_decode($sell_house_price_data, true);
        return $this->returnSuccess($myColletsHouse);


    }
}
