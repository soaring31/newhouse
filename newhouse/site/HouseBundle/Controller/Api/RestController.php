<?php
/**
 * Created by PhpStorm.
 * User: zhangshuzhen
 * Date: 2018/4/10
 * Time: 17:51
 */

namespace HouseBundle\Controller\Api;


use FOS\RestBundle\Controller\FOSRestController;
use HouseBundle\Entity\Houses;
use HouseBundle\Traits\ServiceTrait;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

abstract class RestController extends FOSRestController
{
    use ServiceTrait;

    private $beginTime;

    public function __construct()
    {
        $this->beginTime = microtime(true);
    }

    public function createFormBuilder($data = null, array $options = [], $enableName = true)
    {
        $options['allow_extra_fields'] = true;

        return $enableName
            ? parent::createFormBuilder($data, $options)
            : $this
                ->get('form.factory')
                ->createNamedBuilder('', 'Symfony\Component\Form\Extension\Core\Type\FormType', $data, $options);
    }

    /**
     * @param Houses $house
     *
     * @return string
     */
    public function getHouseDetailShareUrl($house)
    {
        $id = '';

        if (is_array($house)) {
            $id = $house['id'];
        }

        if ($house instanceof Houses) {
            $id = $house->getId();
        }

        if (!$id)
            return '';

        $house_url = $this->get('service_container')->getParameter('house_url');

        return sprintf('%s/xinfang/detail/%s.html', $house_url, $id);
    }

    /**
     * @param 获取月的起始和结束
     *
     * @param int $page
     *
     * @return \FOS\RestBundle\View\View
     */
    protected function getShiJianChuo($nian = 0, $yue = 0)
    {
        if (empty($nian) || empty($yue)) {
            $now  = time();
            $nian = date("Y", $now);
            $yue  = date("m", $now);
        }
        $time['begin'] = mktime(0, 0, 0, $yue, 1, $nian);
        $time['end']   = mktime(23, 59, 59, ($yue + 1), 0, $nian);
        return $time;
    }

    /**
     *  返回 app 接口数据
     *
     * @param $name
     * @param $value
     * @param $key
     *
     * @return array
     */
    public function toAppData($name, $value, $key)
    {
        return ['name' => $name, 'value' => $value, 'key' => $key];
    }

    /**
     * 获取有用的数据
     *
     * @param array|string $a
     * @param $key
     *
     * @return mixed|string
     */
    public function getValidData($a, $key = '', $isObj = false)
    {
        return $this->getHouseHandler()->getValidData($a, $key, $isObj);
    }

    /**
     * 获取查询排序规则
     *
     * 查询格式 'kpdate|desc,price|asc'
     *
     * @param $orderStr
     * @return array
     */
    public function getOrderQuery($orderStr)
    {
        $orders = $this->getHouseHandler()->getUniqueArray(explode(',', $orderStr));
        $orders = array_values($orders);
        $query  = [];
        foreach ($orders as $key => &$order) {
            $orderInfo                  = $this->getHouseHandler()->getUniqueArray(explode('|', $order));
            $query[current($orderInfo)] = next($orderInfo);
        }

        return $query;
    }

    /**
     * 获取查询排序规则
     *
     * 查询数据 ,隔开 使用 in 查询
     *
     * @param $queryStr
     * @return array
     */
    public function getQueryParams($queryStr)
    {
        $values = $this->getHouseHandler()->getUniqueArray(explode(',', $queryStr));

        if (count($values) == 1)
            return current($values);

        return ['in' => $values];
    }

    /**
     * @param $data
     *
     * @param int $page
     *
     * @return \FOS\RestBundle\View\View
     */
    protected function returnSuccess($data, $page = 1)
    {
        return $this->view([
            'error'        => 0,
            'data'         => $data,
            'page'         => $page,
            'code'         => 200,
            'totalRuntime' => microtime(true) - $this->beginTime
        ]);
    }
    /**
     * 插入楼盘浏览记录
     *
     * @param Houses $houses
     * @param int $token
     *
     * @return bool
     */
    protected function addBrowse(Houses $houses, $token = 0 ,$city)
    {
        //解密token
        $deToken = $this->tokenDecode($token);
        if (!$area = $this->getAuthGroupAreaService()->findOneBy(['city' => $city]))
            return false;
        //判断软用户还是强用户(查询是否有这条记录)
        $user = [];
        if($deToken['username']){
            $user = $this->getDbUserService()->findOneBy(['oid'=>$deToken['id']]);
            $findBehavior = $this->getUserBehaviorService()->findOneby(['uid'=>$user->getId(),'aid'=>$houses->getId(),'city'=>$area->getRulesarea(),'is_delete'=>0]);
        }else{
            $findBehavior = $this->getUserBehaviorService()->findOneby(['token'=>$deToken['id'],'aid'=>$houses->getId(),'city'=>$area->getRulesarea(),'is_delete'=>0]);
        }

        //判断是否有当前记录
        if($findBehavior){
            //更新时间
            $browseSave= $this->getUserBehaviorService()->update($findBehavior->getId(),['update_time'=>time()], null, false);
            return $browseSave;
        }else{
            //组合浏览记录数据
            $behaviorData = [
                'uid' => 0,
                'aid' => $houses->getId(),
                'city'=> $area->getRulesarea(),
                'type'=>3,
                'status'=> $deToken['username'] ? 1 : 2,
                'tel'=>'',
                'identifier'=>md5(uniqid(md5(microtime(true)),true)),
                'token'=> $deToken['username'] ? '' : $deToken['id'],
                'create_time'=>time(),
                'update_time'=>time(),
                'is_delete'=>0
            ];
            if($user){
                $behaviorData['uid'] = $user->getId();
                $behaviorData['tel'] = $user->getTel();
            }
            //插入浏览记录数据
            if ($browse= $this->getUserBehaviorService()->add($behaviorData, null, false)) {
                return $browse;
            }
        }
        return false;
    }

    /**
     * token 解码
     *@param  string token
     *@return array 解码之后的数据
     */
    protected function tokenDecode($data = '') {
//        if(!$data)
//            throw new NotFoundHttpException('token 不存在');
//
//        $secretKey = $this->get('service_container')->getParameter('tokenkey');
//        $data = str_replace(array('-', '_'), array('+', '/'), trim($data));
//        $data = base64_decode($data);
//        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_256,'',MCRYPT_MODE_CBC,'');
//        $iv = mb_substr($data,0,32,'latin1');
//        mcrypt_generic_init($td,$secretKey,$iv);
//        $data = mb_substr($data,32,mb_strlen($data,'latin1'),'latin1');
//        $data = mdecrypt_generic($td,$data);
//        mcrypt_generic_deinit($td);
//        mcrypt_module_close($td);
//        $aesStr = trim($data);
//        if(!$aesStr)
//            throw new BadRequestHttpException('token 无效');
//
//        $aesArr = explode('|', $aesStr);
//        return [
//            'username' => $aesArr[0],
//            'id' => $aesArr[1],
//            'createTime' => $aesArr[2],
//        ];
        return $this->getHouseHandler()->tokenDecode($data);
    }

    /**
     * 置业顾问请求老版
     *@param  string token
     *@return array 解码之后的数据
     */
    protected function interAdviser($city = '', $complexId = ''){
        //老版本的域名
        $apiUrl = $this->get('service_container')->getParameter('api_url');
        $sellHousePriceUrl = $apiUrl . "/API/NewHouseBorough/getNewBorkerTel";
        $data = array(
            "city" =>$city,
            "complex_id" =>$complexId,
        );
        $sellousePriceData = $this->get('request.handler')->curl_post($sellHousePriceUrl, $data);
        $sell_data = json_decode($sellousePriceData, true);
        if($sell_data['code'] == 200 && $sell_data['data']){
            foreach ($sell_data['data'] as $k => &$v){
                $v['name'] = $v['real_name'];
                $v['models'] = $v['company_name'];
                $v['thumb'] = $v['logo_url'];
                $v['tel'] = $v['username'];
            }
        }
        if(empty($sell_data['data'])){
            return [];
        }
        return $sell_data['data'];
    }
}
