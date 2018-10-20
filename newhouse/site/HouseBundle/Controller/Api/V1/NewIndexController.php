<?php
namespace HouseBundle\Controller\Api\V1;

use FOS\RestBundle\Controller\Annotations as Rest;
use HouseBundle\Controller\Api\RestController;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use HouseBundle\Services\DB\Category;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;


class NewIndexController extends RestController
{
   
    /**
     *@ApiDoc(description="武运晓-------------- ")
     *@Rest\RequestParam(name="city", description="城市简称" )
     *@Rest\RequestParam(name="start_time", description="开始时间(格式2017-11)" )
     *@Rest\RequestParam(name="end_time", description="结束时间(格式如2017-11)" )
     */
    public function postSecondPriceAction(Request $request)
    { 
        
            $form = $this->createFormBuilder([
                    'city' => '',
                    'start_time' => '',
                    'end_time' => ''
                ], [
                    'method' => 'POST',
                    'csrf_protection' => false
                ])
         
                ->add('city', 'text',[
                    'constraints'=>[
                        new NotBlank(['message'=>'城市简称不能为空']),
                    ]])
                ->add('start_time', 'text',[
                    'constraints'=>[
                        new NotBlank(['message'=>'开始时间不能为空']),
                    ]])
                ->add('end_time', 'text',[
                    'constraints'=>[
                        new NotBlank(['message'=>'结束时间不能为空']),
                    ]])                              
            ->getForm();
            $form->submit($request->request->all());
            if(!$form->isValid())
               throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
            $postData = $form->getData();
            //设置memcache缓存前缀
            $memcacheKey = 'second_house_list_' . md5(json_encode($request->query->all()));

            if ($list = $this->getMemcacheService()->fetch($memcacheKey))
                return $this->returnSuccess($list);      

            $beginTime=strtotime($postData['start_time']);
            $endTime=strtotime($postData['end_time']);

            $data=json_encode([
                  "filter"=>[
                    "start_time"=>$beginTime,
                    "end_time"=>$endTime
                    ]
            ]);
            $api_url = $this->get('service_container')->getParameter('data_api_url');
            $sell_house_price_url = $api_url .$postData['city']."/analysis/detail/sellHousePriceTrends";
            $sell_house_price_data = $this->get('request.handler')->curl_post($sell_house_price_url,$data);
            
            $sell_data = json_decode($sell_house_price_data, true);
            if(!$sell_data){
                     throw new BadRequestHttpException('数据无效');
            }
            $this->getMemcacheService()->save($memcacheKey, $sell_data, 3600*24);
            return $this->returnSuccess($sell_data);
       

    }
    /**
     *@ApiDoc(description="武运晓--------------新版首页二手房成交趋势")
     *@Rest\RequestParam(name="city", description="城市简称" )
     *@Rest\RequestParam(name="time", description="结束时间(格式如201711)" )
     */
    public function postTransactionTrendAction(Request $request)
    { 
            $form = $this->createFormBuilder([
                    'city' => '',
                ], [
                    'method' => 'POST',
                    'csrf_protection' => false
                ])

         
                ->add('city', 'text',[
                    'constraints'=>[
                        new NotBlank(['message'=>'城市简称不能为空']),
                    ]])
                ->add('time', 'text',[
                    'constraints'=>[
                        new NotBlank(['message'=>'时间不能为空']),
                    ]])                
            ->getForm();
            $form->submit($request->request->all());
            if(!$form->isValid())
               throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
            $postData = $form->getData();        
            $memcacheKey = 'new_house_list_trend_' . md5(json_encode($request->query->all()));

            if ($list = $this->getMemcacheService()->fetch($memcacheKey))
                return $this->returnSuccess($list);        
            $data=json_encode([
                  "filter"=>[
                    "now_time"=>$postData['time']
                    ]
            ]);
            $api_url = $this->get('service_container')->getParameter('data_api_url');
            $sell_house_price_url = $api_url .$postData['city']."/analysis/detail/sellHouseFinishTrends";
            $sell_house_price_data = $this->get('request.handler')->curl_post($sell_house_price_url,$data);
            $sell_data = json_decode($sell_house_price_data, true);
            $this->getMemcacheService()->save($memcacheKey, $sell_data, 3600*24);
            if(!$sell_data){
                     throw new BadRequestHttpException('数据无效');
            }            
            return $this->returnSuccess($sell_data);
            


    }
    /**
     *@ApiDoc(description="武运晓--------------二手房挂牌均价")
     *@Rest\RequestParam(name="city", description="城市简称" )
     */
    public function getSecondIpopriceAction(Request $request)
    { 
            $form = $this->createFormBuilder([
                    'city' => '',
                ], [
                    'method' => 'get',
                    'csrf_protection' => false
                ])

                ->add('city', 'text',[
                    'constraints'=>[
                        new NotBlank(['message'=>'城市简称不能为空']),
                    ]
            ])
            ->getForm();
            $form->submit($request->query->all());
            $memcacheKey = 'ipo_house_list_' . md5(json_encode($request->query->all()));

            if ($list = $this->getMemcacheService()->fetch($memcacheKey))
                return $this->returnSuccess($list);            
            if(!$form->isValid())
               throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
            $getData = $form->getData();
            $api_url = $this->get('service_container')->getParameter('data_api_url');
            $sell_house_price_url = $api_url .$getData['city']."/analysis/detail/sellHouseListedPrice";
            $sell_house_price_data = $this->get('request.handler')->curl_get($sell_house_price_url);
            $sell_data = json_decode($sell_house_price_data, true);
            $this->getMemcacheService()->save($memcacheKey, $sell_data, 3600*24);
            if(!$sell_data){
                     throw new BadRequestHttpException('数据无效');
            }            
            return $this->returnSuccess($sell_data);
            

    }
    /**
     *@ApiDoc(description="武运晓--------------二手房面积百分比")
     *@Rest\RequestParam(name="city", description="城市简称" )
     */
    public function getPercentageAreaAction(Request $request)
    { 
            $form = $this->createFormBuilder([
                    'city' => '',
                ], [
                    'method' => 'GET',
                    'csrf_protection' => false
                ])

                ->add('city', 'text',[
                    'constraints'=>[
                        new NotBlank(['message'=>'城市简称不能为空']),
                    ]
            ])
            ->getForm();
            $form->submit($request->query->all());

            $memcacheKey = 'percentage_house_list_' . md5(json_encode($request->query->all()));

            if ($list = $this->getMemcacheService()->fetch($memcacheKey))
                return $this->returnSuccess($list); 

            if(!$form->isValid())
               throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
            $getData = $form->getData();
            $api_url = $this->get('service_container')->getParameter('data_api_url');
            $sell_house_price_url = $api_url .$getData['city']."/analysis/detail/sellHouseAreaPecent";
            $sell_house_price_data = $this->get('request.handler')->curl_get($sell_house_price_url);
            $sell_data = json_decode($sell_house_price_data, true);
            $this->getMemcacheService()->save($memcacheKey, $sell_data, 3600*24);
            if(!$sell_data){
                     throw new BadRequestHttpException('数据无效');
            }            
            return $this->returnSuccess($sell_data);
            

    }
    /**
     *@ApiDoc(description="武运晓--------------成交数据")
     *@Rest\RequestParam(name="city", description="城市简称" )
     *@Rest\RequestParam(name="start_time", description="开始时间(格式2017-11)" )
     *@Rest\RequestParam(name="end_time", description="结束时间(格式如2017-11)" )
     */
    public function postTransactionDataAction(Request $request)
    { 
            $form = $this->createFormBuilder([
                    'city' => '',
                    'start_time' => '',
                    'end_time' => ''
                ], [
                    'method' => 'POST',
                    'csrf_protection' => false
                ])
         
                ->add('city', 'text',[
                    'constraints'=>[
                        new NotBlank(['message'=>'城市简称不能为空']),
                    ]])
                ->add('start_time', 'text',[
                    'constraints'=>[
                        new NotBlank(['message'=>'开始时间不能为空']),
                    ]])
                ->add('end_time', 'text',[
                    'constraints'=>[
                        new NotBlank(['message'=>'结束时间不能为空']),
                    ]])                              
            ->getForm();
            $form->submit($request->request->all());
            if(!$form->isValid())
               throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
            $postData = $form->getData();

            $memcacheKey = 'transaction_house_list_' . md5(json_encode($request->query->all()));

            if ($list = $this->getMemcacheService()->fetch($memcacheKey))
                return $this->returnSuccess($list); 

            $beginTime=strtotime($postData['start_time']);
            $endTime=strtotime($postData['end_time']);

            $data=json_encode([
                  "filter"=>[
                    "start_time"=>$beginTime,
                    "end_time"=>$endTime
                    ]
            ]);
            $api_url = $this->get('service_container')->getParameter('data_api_url');

            $sell_house_price_url = $api_url .$postData['city']."/analysis/detail/finishData";
            $sell_house_price_data = $this->get('request.handler')->curl_post($sell_house_price_url,$data);
            $sell_data = json_decode($sell_house_price_data, true);
            if(!$sell_data){
                     throw new BadRequestHttpException('数据无效');
            }
            $this->getMemcacheService()->save($memcacheKey, $sell_data, 3600*24);
            return $this->returnSuccess($sell_data);
                 
            
    }
    /**
     *@ApiDoc(description="武运晓--------------新房价格趋势")
     *@Rest\RequestParam(name="city", description="城市简称" )
     *@Rest\RequestParam(name="start_time", description="开始时间(格式2017-11)" )
     *@Rest\RequestParam(name="end_time", description="结束时间(格式如2017-11)" )
     */
    public function postNewhousepriceTrendsAction(Request $request)
    { 
                $form = $this->createFormBuilder([
                    'city' => '',
                    'start_time' => '',
                    'end_time' => ''
                ], [
                    'method' => 'POST',
                    'csrf_protection' => false
                ])
         
                ->add('city', 'text',[
                    'constraints'=>[
                        new NotBlank(['message'=>'城市简称不能为空']),
                    ]])
                ->add('start_time', 'text',[
                    'constraints'=>[
                        new NotBlank(['message'=>'开始时间不能为空']),
                    ]])
                ->add('end_time', 'text',[
                    'constraints'=>[
                        new NotBlank(['message'=>'结束时间不能为空']),
                    ]])                              
            ->getForm();
            $form->submit($request->request->all());
            if(!$form->isValid())
               throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
            $postData = $form->getData();
            $memcacheKey = 'new_house_price_' . md5(json_encode($request->query->all()));

            if ($list = $this->getMemcacheService()->fetch($memcacheKey))
                return $this->returnSuccess($list);

            $beginTime=strtotime($postData['start_time']);
            $endTime=strtotime($postData['end_time']);

            $data=json_encode([
                  "filter"=>[
                    "start_time"=>$beginTime,
                    "end_time"=>$endTime
                    ]
            ]);
            $api_url = $this->get('service_container')->getParameter('data_api_url');
            $sell_house_price_url = $api_url .$postData['city']."/analysis/detail/newHousePriceTrends";
            $sell_house_price_data = $this->get('request.handler')->curl_post($sell_house_price_url,$data);
            $sell_data = json_decode($sell_house_price_data, true);
            if(!$sell_data){
                     throw new BadRequestHttpException('数据无效');
            }   
            $this->getMemcacheService()->save($memcacheKey, $sell_data, 3600*24);
            return $this->returnSuccess($sell_data);
            

    }                   
}
