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

class PromotionOrderController extends RestController
{

    /**
     * @ApiDoc(description="武运晓--------------活动报名")
     *
     * @Rest\RequestParam(name="is_login", description="是否登录 1 登陆 0 未登录 默认0"  , default="0")
     * @Rest\RequestParam(name="tel", description="手机号 tel" )
     * @Rest\RequestParam(name="code", description="验证码" )
     * @Rest\RequestParam(name="type", description="类型1 活动 2看房团 3订阅")
     * @Rest\RequestParam(name="promotion_id", description="活动 id", requirements="\d+")
     * @Rest\RequestParam(name="aid", description="看房团id", requirements="\d+")
     * @Rest\RequestParam(name="pid", description="看房团意向楼盘id", requirements="\d+")
     * @Rest\RequestParam(name="yid", description="订阅分类 1.变价通知2.优惠通知3.开盘通知4.最新动态", requirements="\d+")
     * @Rest\RequestParam(name="uid", description="订阅用户ID", requirements="\d+")
     * @Rest\RequestParam(name="did", description="订阅楼盘ID", requirements="\d+")
     * @Rest\RequestParam(name="area", description="所属地区", requirements="\d+")
     *
     */
    public function postAction(Request $request)
    {

        $form = $this->createFormBuilder([
            'is_login' => 0,
            'tel' => '',
            'type' => '',
            'promotion_id' => '',
            'code' => '',
            'aid' => '',
            'pid' => '',
            'yid' => '',
            'uid' => '',
            'did' => '',
            'area' => '',
            
        ], [
            'method' => 'POST',
            'csrf_protection' => false
        ])
            ->add('tel', 'text',[
                'constraints'=>[
                    new NotBlank(['message'=>'手机号不能为空']),
                    //new Regex(['pattern'=>'((13[0-9])|(14[5|7])|(15([0-3]|[5-9]))|(18[0,5-9]))\d{8}$', 'message'=>'手机号不正确'])
                ]
            ])
            ->add('is_login')
            ->add('type')
            ->add('promotion_id')
            ->add('code')
            ->add('aid')
            ->add('pid')
            ->add('yid')
            ->add('uid')
            ->add('did')
            ->add('area')
            ->getForm();
        $form->submit($request->request->all());


        if(!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();
        //判断验证码
        $tel=$data['tel'];
        $value=$data['code'];
       
        if(empty($tel)) 
                throw new BadRequestHttpException('手机号不能为空');

        if(!$this->get('core.common')->isMobile($tel))
            
            throw new BadRequestHttpException('手机号格式有误');

        if($data['is_login']==0){
            if(empty($value))
                 throw new BadRequestHttpException('验证码为空');

            if(!$this->get('core.sms')->checkSmscode($tel, $value))
                throw new BadRequestHttpException('验证码错误');
        }
        $tel_where=['tel'=>$data['tel']];
        /*
        if ($promotionOrder = $this->getPromotionOrderService()->findOneBy($tel_where)){
                     return $this->returnSuccess('手机号已存在');
        }
        */
        //1活动报名
        if($data['type']==1){
            $hcount=$this->getPromotionservice()->findOneBy(['id'=>$data['promotion_id']]);
            $endDate=$hcount->getEnddate();
            $activeEndDate=strtotime($endDate->format('Y-m-d'));       
            $nowDate=time();
            if($activeEndDate<$nowDate){
                    throw new BadRequestHttpException('活动已结束!');
            }
            $isActive= $hcount=$this->getPromotionOrderService()->findOneBy(['promotion_id'=>$data['promotion_id'],'tel'=>$data['tel']]);
            if($isActive)
                throw new BadRequestHttpException('您已参加本次活动!');
            $info = $this->getPromotionOrderService()->addPromotionOrder($data['tel'],$data['promotion_id']);
            if($info){
                $hdnum=$hcount->getHdnum();
                $saveData['hdnum']=$hdnum+1;
                $save=$this->getPromotionservice()->update($data['promotion_id'],$saveData, null, false);
                return $this->returnSuccess($info);
            }else{
                throw new BadRequestHttpException('报名失败');
            }
            
        }
        //2看房团报名
        if($data['type']==2){
            $info = $this->getShowingsewsService()->addShowingsnews($data['tel'],$data['aid'],$data['pid']);
            return $this->returnSuccess($info);
        }
        // 1.变价通知2.优惠通知3.开盘通知4.最新动态
        if($data['type']==3){
            empty($data['uid'])?$data['uid']=0:$data['uid']=$data['uid'];
            $info = $this->getInterHousesintentionService()->addInterHousesintention($data['tel'],$data['yid'],$data['did'],$data['area'],$data['uid']);
            return $this->returnSuccess($info);
        }else{
            throw new BadRequestHttpException('type类型不正确');
        }
    }
    /**
     * @ApiDoc(description="发送验证码")
     *
     * @Rest\RequestParam(name="tel", description="手机号 tel" )
     *
     */
    public  function postSendMobilecodeAction(Request $request){

        $form = $this->createFormBuilder([
            'tel' => '',

        ], [
            'method' => 'POST',
            'csrf_protection' => false
        ])
            ->add('tel', 'text',[
                'constraints'=>[
                    new NotBlank(['message'=>'手机号不能为空']),
                    new Regex(['pattern'=>'/^\d{11,13}$/i', 'message'=>'手机号不正确'])
                ]
            ])

            ->getForm();
        $form->submit($request->request->all());
        if(!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();
        $sendTo['name']=$data['tel'];
        $sendTo['code']=rand(100000,999999);
        $sendTo['tpl']='smscode';

        if($this->get('core.sms')->isClosed())
            throw new \InvalidArgumentException('手机短信功能已关闭，请联系管理人员！');
        return $this->returnSuccess($this->get('core.sms')->send($sendTo));
    }
    /**
     * @ApiDoc(description="武运晓------------验证验证码")
     * @Rest\RequestParam(name="code", description="验证码" )
     * @Rest\RequestParam(name="tel", description="手机号 tel" )
     *
     */
    public  function postVerifyCodeAction(Request $request){

        $form = $this->createFormBuilder([
            'tel' => '',
            'code' => '',

        ], [
            'method' => 'POST',
            'csrf_protection' => false
        ])
            ->add('tel', 'text',[
                'constraints'=>[
                    new NotBlank(['message'=>'手机号不能为空']),
                    new Regex(['pattern'=>'/^\d{11,13}$/i', 'message'=>'手机号不正确'])
                ]

            ])
            ->add('code')
            ->getForm();
        $form->submit($request->request->all());
        if(!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();
        $tel=$data['tel'];
        $code=$data['code'];
        if(empty($code))
             throw new BadRequestHttpException('验证码为空');

        if(!$this->get('core.sms')->checkSmscode($tel, $code))
            throw new BadRequestHttpException('验证码错误');  

        if($this->get('core.sms')->isClosed())
            throw new \InvalidArgumentException('手机短信功能已关闭，请联系管理人员！');
        return $this->returnSuccess('验证成功');
    }
}