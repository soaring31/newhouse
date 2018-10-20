<?php
/**
 * Created by PhpStorm.
 * User: zhangshuzhen
 * Date: 2018/5/29
 * Time: 11:19
 */

namespace HouseBundle\Controller\Api\V1;


use Doctrine\ORM\EntityNotFoundException;
use FOS\RestBundle\Controller\Annotations as Rest;
use HouseBundle\Controller\Api\RestController;
use HouseBundle\Entity\HouseSem;
use HouseBundle\Form\Api\HouseSemType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class SemController extends RestController
{
    /**
     * @ApiDoc(description="获取Sem 400电话",
     *     views={"app"})
     *
     * @Rest\QueryParam(name="spread", requirements="\w+")
     * @Rest\QueryParam(name="houseIds", requirements="\w+", description="楼盘id集合 逗号隔开")
     *
     * @param Request $request
     *
     * @return \FOS\RestBundle\View\View
     *
     * @throws \Exception
     */
    public function getSemAction(Request $request)
    {
        if (!$spread = $request->get('spread'))
            throw new BadRequestHttpException('spread 必传');

        if (!$houseIds = $request->get('houseIds'))
            throw new BadRequestHttpException('楼盘id集合必传');

        $houseIds = array_unique(array_filter(explode(',', $houseIds)));

        if (!$houses = $this->getHouseService()->findBy(['id' => ['in' => $houseIds]]))
            throw new BadRequestHttpException('楼盘不存在');

        $spreads  = array_filter(explode('_', $spread));
        $type     = current($spreads);
        $platform = next($spreads);
        $channel  = next($spreads);

        if (!$type || !$platform || !$channel)
            throw new BadRequestHttpException('spread 参数不合法');

        $item = [];
        foreach ($houses['data'] as $house) {
            if (!$house->getTel() || !$semTel = $this->getHouseSemTelService()->findOneBy([
                    'house_id'     => $house->getId(),
                    'sem_type'     => strtolower($type),
                    'sem_platform' => strtolower($platform),
                    'sem_channel'  => strtolower($channel),
                    'status'       => HouseSemType::STATUS_OK
                ]))
                $item[$house->getId()] = '';
            else
                $item[$house->getId()] = $semTel->getBigcodetel() . ',' . $semTel->getExtcode();
        }

        return $this->returnSuccess($item);
    }

    /**
     * @ApiDoc(description="获取dsp 400电话",
     *     views={"app"})
     *
     * @Rest\QueryParam(name="spread", requirements="\w+")
     * @Rest\QueryParam(name="houseIds", requirements="\w+", description="楼盘id集合 逗号隔开")
     *
     * @param Request $request
     *
     * @return \FOS\RestBundle\View\View
     *
     * @throws \Exception
     */
    public function getDspAction(Request $request)
    {
        if (!$spread = $request->get('spread'))
            throw new BadRequestHttpException('spread 必传');

        if (!$houseIds = $request->get('houseIds'))
            throw new BadRequestHttpException('楼盘id集合必传');

        if (!$houses = $this->getHouseService()->findBy(['id' => ['in' => $houseIds]]))
            throw new BadRequestHttpException('楼盘不存在');

        // ia_baidu_xf_
        // ia_qtt_xf_
        // ia_ydzx_xf_
        // ia_wangyi_xf_
        // ia_gdt_xf_'

        $spreads = array_filter(explode('_', $spread));
        $ia      = current($spreads);
        $channel = next($spreads);

        if ($ia != 'ia') {
            throw new BadRequestHttpException('spread格式错误');
        }

        if (!$channel)
            throw new BadRequestHttpException('spread 参数不合法');

        $item = [];
        foreach ($houses['data'] as $house) {
            if (!$house->getTel() || !$semTel = $this->getHouseSemTelService()->findOneBy([
                    'house_id'    => $house->getId(),
                    'sem_type'    => 'dsp',
                    'sem_channel' => strtolower($channel),
                    'status'      => HouseSemType::STATUS_OK
                ]))
                $item[$house->getId()] = '';
            else
                $item[$house->getId()] = $semTel->getBigcodetel() . ',' . $semTel->getExtcode();
        }

        return $this->returnSuccess($item);
    }


    /**
     * @ApiDoc(description="为楼盘添加|解绑 Sem 电话",
     *     input="HouseBundle\Form\Api\HouseSemType",
     *     views={"app"})
     *
     * @param Request $request
     *
     * @return \FOS\RestBundle\View\View
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @throws \Exception
     */
    public function postSemAction(Request $request)
    {
        function filterArray(array $a)
        {
            return array_unique(array_filter($a));
        }

        $form = $this->createForm(new HouseSemType(), [
            'house_ids'   => '',
            'add_type'    => '',
            'channel_ids' => '',
            'type'        => '',
            'platform'    => '',
        ], [
            'method'             => 'POST',
            'allow_extra_fields' => true
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && !$form->isValid())
            throw new BadRequestHttpException($form->getErrors()->current()->getMessage());

        $data = $form->getData();

        $houseIds = filterArray(explode(',', $data['house_ids']));
        $houses   = $this->getHouseService()->findBy([
            'id' => [
                'in' => $houseIds
            ]
        ]);

        if (!$houses)
            return $this->returnSuccess('楼盘数据不存在');

        $addType    = $data['add_type'];
        $channelIds = $data['channel_ids'];
        $type       = $data['type'];
        $platform   = $data['platform'];
        $channelIds = array_unique(array_filter(explode(',', $channelIds)));

        switch ($addType) {
            // 添加
            case 1:
                return $this->bindSem($houses, $platform, $type, $channelIds);
                break;
            case 2:
                return $this->unbindSem($houses, $platform, $type, $channelIds);
                break;
            default:
                throw new BadRequestHttpException('添加类型错误');
        }
    }

    /**
     *
     *
     * @param array $houses [Houses, Houses]
     * @param $platform
     * @param $type
     * @param $channelIds
     *
     * @return \FOS\RestBundle\View\View
     *
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    private function bindSem(array $houses, $platform, $type, $channelIds)
    {
        $newBindSemKey = 'new_bind_sem';
        $houseSems     = [];

        foreach ($houses['data'] as $house) {
            $tel     = $house->getTel();
            $houseId = $house->getId();

            foreach ($channelIds as $channelId) {
                $hash = md5(json_encode([$tel, $channelId, $type, $platform, $houseId]));

                if ($exist = $this->getHouseSemService()->findOneBy([
                    'hash' => $hash
                ])) {
                    $houseSems[$exist->getId()] = $exist;
                    continue;
                } else {
                    $houseSem = new HouseSem();
                    $houseSem
                        ->setStatus(HouseSemType::STATUS_NOT)
                        ->setTel($tel)
                        ->setChannel($channelId)
                        ->setType($type)
                        ->setPlatform($platform)
                        ->setHouseId($houseId)
                        ->setChecked(1)
                        ->setAttributes('')
                        ->setIssystem(0)
                        ->setIsDelete(0)
                        ->setSort(0)
                        ->setCreateTime(time())
                        ->setUpdateTime(time())
                        ->setIdentifier($hash)
                        ->setHash($hash);

                    $entityManager = $this->getHouseSemService()->getEntityManager();

                    $entityManager->persist($houseSem);
                    $entityManager->flush($houseSem);

                    $houseSems[$houseSem->getId()] = $houseSem;
                }
            }
        }

        $this->get('core.memcache')->save($newBindSemKey, $houseSems);

        return $this->returnSuccess($houseSems);
    }

    /**
     * 标记下
     *
     * @param array $houses
     * @param $platform
     * @param $type
     * @param $channelIds
     *
     * @return \FOS\RestBundle\View\View
     */
    private function unbindSem(array $houses, $platform, $type, $channelIds)
    {
        $delBindSemKey = 'del_bind_sem';
        $houseSems     = [];

        foreach ($houses['data'] as $house) {
            $tel     = $house->getTel();
            $houseId = $house->getId();

            foreach ($channelIds as $channelId) {
                $hash = md5(json_encode([$tel, $channelId, $type, $platform, $houseId]));

                if ($exist = $this->getHouseSemService()->findOneBy([
                    'hash' => $hash
                ])) {
                    $houseSems[$exist->getId()] = $exist;
                } else {
                    continue;
                }
            }
        }

        $this->get('core.memcache')->save($delBindSemKey, $houseSems);

        return $this->returnSuccess($houseSems);
    }
}