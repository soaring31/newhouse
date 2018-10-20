<?php
/**
 * Created by PhpStorm.
 * User: wangxiaoyu
 * Date: 2018/05/15
 * Time: 16:32
 */

namespace HouseBundle\Controller\Api\V1;


use FOS\RestBundle\Controller\Annotations as Rest;
use HouseBundle\Controller\Api\RestController;
use HouseBundle\Entity\Houses;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use HouseBundle\Form\Api\HouseListQueryType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class UserController extends RestController
{
    private $page = 1;
    private $limit = 10;

    /**
     * @ApiDoc(description="用户-初始化用户数据--王晓宇")
     *
     * @Rest\QueryParam(name="userid", description="设备唯一标识")
     * @Rest\QueryParam(name="token", description="token")
     *
     */
    public function postUserInitializesAction(Request $request)
    {
        //定义请求参数默认值以及请求方式
        $form = $this->createFormBuilder([
            'token' => '',
            'userid' => '',
        ], [
            'method' => 'POST',
            'csrf_protection' => false
        ])
            ->add('token','text')
            ->add('userid','text')
            ->getForm();

        $form->submit($request->request->all(), false);//接收请求数据
        if (!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());

        $data = $form->getData();//获取请求数据
        //判断是否有手机号和老用户ID
        if (empty($data['userid']))
            throw new BadRequestHttpException('设备唯一标识不能为空');

        //要插入的数据
        $userData = [];
        $userData['sort'] = 0;
        $userData['mid'] = 0;
        $userData['username'] = 'u8eb9083'.rand(1,20).rand(10,20);
        $userData['status'] = 1;
        $userData['tel'] = 0;
        $userData['onlinetime'] = 0;
        $userData['uptotal'] = 0;
        $userData['downtotal'] = 0;
        $userData['logintime'] = time();
        $userData['loginip'] = 0;
        $userData['token'] = '';
        $userData['locale'] = 'zh_CN';
        $userData['userdb'] = "HouseBundle";
        $userData['identifier'] = md5(uniqid(md5(microtime(true)),true));
        $userData['create_time'] = time();
        $userData['update_time'] = time();
        $userData['is_delete'] = 0;
        $userData['oid'] = 0;
        //解密token
        $deToken = $this->tokenDecode($data['token']);
        //判断是软用户还是强用户
        if($deToken['username']){
            //插入用户数据
            //查询是否有当前用户
            if(!$findOiduser = $this->getDbUserService()->findOneBy(['oid'=>$deToken['id'],'is_delete'=>0])){
                //插入用户数据
                $userData['tel'] = $deToken['username'];
                $userData['token'] = $data['userid'] ? $data['userid'] : '';
                $userData['oid'] = $deToken['id'];
                //注册新的用户
                $updateUser = $this->getDbUserService()->add($userData, null, false);
            }else{
                $updateData['logintime'] = time();
                //更新登录时间和设备唯一标识
                $updateUser = $this->getDbUserService()->update($findOiduser->getId(),$updateData, null, false);
            }

            //更新浏览记录表中的用户数据
            $editBehavior = $this->getUserBehaviorService()->dbalUpdate(['uid'=>$updateUser->getId(),"tel"=>$updateUser->getTel()],['token'=>$updateUser->getToken()]);
            if($editBehavior){
                return $this->returnSuccess(['status'=>1]);
            }else{
                return $this->returnSuccess(['status'=>0]);
            }
        }else{
            //弱用户默认返回0
            return $this->returnSuccess(['status'=>0]);
        }

        //返回数据
        return $this->returnSuccess(['status'=>0]);

    }
    /**
     * @ApiDoc(description="用户-浏览记录列表--王晓宇")
     *
     * @Rest\QueryParam(name="city", description="城市简称")
     * @Rest\QueryParam(name="token", description="token")
     * @Rest\QueryParam(name="page", description="页数", requirements="\d+", default="1")
     * @Rest\QueryParam(name="limit", description="数量", requirements="\d+", default="15")
     *
     */
    public function postBrowseRecordsAction(Request $request){
        //定义数组
        $buildlist = array();
        //定义请求参数默认值以及请求方式
        $form = $this->createFormBuilder([
            'city'  => 'bj',
            'token'  => '',
            'page'  => $this->page,
            'limit' => $this->limit,
        ], [
            'method'          => 'POST',
            'csrf_protection' => false
        ])
            ->add('city', 'text')
            ->add('token', 'text')
            ->add('page', 'text')
            ->add('limit', 'text')
            ->getForm();
        $form->submit($request->request->all(), false);//接收请求数据s
        if (!$form->isValid())
            throw new BadRequestHttpException($form->getErrors(true)->current()->getMessage());
        $data  = $form->getData();//获取请求数据
        $query = ['checked' => 1];

        if (!$data['city'])
            return $this->returnSuccess([]);

        if (!$area = $this->getAuthGroupAreaService()->findOneBy(['city' => $data['city']]))
            return $this->returnSuccess([]);

        $query['area'] = $area->getRulesarea();
        //解密token
        $deToken = $this->tokenDecode($data['token']);
        //判断软用户还是强用户
        if($deToken['username']){
            $user = $this->getDbUserService()->findOneBy(['oid'=>$deToken['id']]);
            if (!$user || !$findBehavior = $this->getUserBehaviorService()->findBy(['uid'=>$user->getId(),'is_delete'=>0,'city'=>$area->getRulesarea()], ['update_time'=>'desc'], $data['limit'], $data['page']))
                return $this->returnSuccess([]);
        }else{
            if (!$findBehavior = $this->getUserBehaviorService()->findBy(['token'=>$deToken['id'],'is_delete'=>0,'city'=>$area->getRulesarea()], ['update_time'=>'desc'], $data['limit'], $data['page']))
                return $this->returnSuccess([]);
        }

        //查询浏览记录中的楼盘ID数据串
        if($findBehavior['data']){
            $result = [];
            foreach ($findBehavior['data'] as $k => $v){
                $query['id'] = $v->getAid();
                $findHouse = $this->getHouseService()->findOneBy($query);
                if($findHouse){
                    $findHouse->setUpdateTime($v->getUpdateTime());
                }
                if($findHouse){
                    array_push($result,$findHouse);
                }
            }
        }

        if (empty($result))
            return $this->returnSuccess([]);

        return $this->returnSuccess($this->getHouseHandler()->getHouseListInfo($result));

    }

}