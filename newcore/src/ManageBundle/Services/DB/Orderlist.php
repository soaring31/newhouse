<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年07月29日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
        
/**
* 交易记录表
* 
*/
class Orderlist extends AbstractServiceManager
{
    protected $table = 'Orderlist';
    
    protected $consume = array(1=>'置顶', 2=>'刷新', 3=>'付费升级', 4=>'充值');
    
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    
    /**
     * 添加
     * @see \CoreBundle\Services\AbstractServiceManager::add()
     */
    public function add(array $data,  $info=null, $isValid=true)
    {
        $data['checked'] = 1;
        //校验提交类型
        $data['number'] = isset($data['number'])?(int)$data['number']:0;
        
        //是否余额支付(1是，0否)
        $data['balance'] = isset($data['balance'])?(int)$data['balance']:0;
        
        //支付方法
        $data['paytype'] = isset($data['paytype'])?(int)$data['paytype']:0;
        
        //消费金额
        $data['total_fee'] = isset($data['total_fee'])?(float)$data['total_fee']:0;
        
        //订单号
        $data['ordersn'] = $this->get('core.common')->makeOrderId();
        
        //消费类型
        $data['consume'] = isset($data['consume'])?(int)$data['consume']:0;
        
        self::_handleCheck($data);
        
        $data['cmoney'] = 0;

        //获取用户余额,如为游客刚余额为0
        $user = $this->get('core.common')->getUser();
        $currency = is_object($user)?$user->getCurrency():array();
        
        //用户ID
        $data['uid'] = is_object($user)?$user->getId():0;
        
        $data['buyer_email'] = is_object($user)?$user->getEmail():'';
        
        $data['paying'] = $data['balance'];
        
//         $data['ctype'] = 'jifen';
        
        //余额差
        $remainder = $data['total_fee']*-1;
        
        //查询模型服务
        if(isset($data['models'])&&$data['models']&&isset($data['aid'])&&$data['aid'])
        {
            $modelInfo = $this->get('db.models')->findOneBy(array('name'=>trim($data['models'])));
            
            $data['models'] = is_object($modelInfo)?$modelInfo->getServiceName():"";
        }

        //判断是否余额支付，是则走余额支付流程
        if($data['balance']>0)
        {
            //货币配置
            $integralInfo = $this->get('db.integral')->findOneBy(array('id'=>(int)$data['balance']));
            
            if(!is_object($integralInfo))
                throw new \LogicException("货币类型不存在或已被删除", false);
            
            $data['ctype'] = trim($integralInfo->getEname());
            $remainder = isset($currency[$data['ctype']])?(doubleval($currency[$data['ctype']])-$data['total_fee']):(0-$data['total_fee']);
        }
        

        $data['cmoney'] = isset($currency[$data['ctype']])?doubleval($currency[$data['ctype']]):0;
        
        $data['bmoney'] = $remainder;

        /**
         * 在线支付模式
         * 1 微信
         * 2 支付宝
         * 3 其它
         *
         * 如果余额不足并且选择了支付模式，
         * 余额差进入在线支付模式,支付成功后写消费表
         */
        if($remainder<0&&$data['paytype']>0)
        {
            //总金额(未使用之前总数)
//             $data['cmoney'] = $data['bmoney'];
            //余额 = 
            $data['bmoney'] = $data['cmoney'] - $remainder;
            $data['checked'] = 0;
            $data['fee'] = abs($remainder);
            $data['conditions'] = json_encode($data);

            $result = '';
            switch($data['paytype'])
            {
                //微信
                case 1:
                    $result = self::_wxpay($data);
                    break;
                    
                //支付宝
                case 2:
                    $result = self::_alipay($data);
                    break;
                case 3:
                    break;
                default:
                    break;
            }
            
            // 支付过程可能出现重定向回当前请求的情况，所以订单入库放到支付处理之后。
            parent::add($data, null, false);
            return $result;
        }
        
        //判断是否余额不足
        if($remainder<0)
            throw new \LogicException("余额不足，请及时充值", false);
        
        //写入消费表
        $result = self::addSpend($data, $user);
         
        return $result;
    }
    
    /**
     * 价格检测
     * @param array $data
     * @throws \LogicException
     */
    private function _handleCheck(array $data)
    {
        /**
         * 根据充值类型触发相应的操作
         * 1:置顶、2:刷新、3:付费升级、4:充值、5:提现、6:转账
         */
        switch ($data['consume'])
        {
            case 1:
                if (isset($data['number']))
                    $config = $this->get('house.rtconfig')->findOneBy(array('id'=>$data['number'], 'type'=>1), array(), false);
                
                if (isset($config) && method_exists($config, 'getPrice'))
                {
                    if ($data['total_fee'] != $config->getPrice())
                        throw new \LogicException('价格不匹配，请重新确认');
                } else {
                    //基本单价(id=1)
                    $config = $this->get('house.rtconfig')->findOneBy(array('id'=>1, 'type'=>1), array(), false);
                    
                    if (is_object($config) && $config->getPrice()*$data['number']!=$data['total_fee'])
                        throw new \LogicException('价格不匹配，请重新确认');
                }
                break;

            case 2:
                switch ($data['type'])
                {
                    // 定时刷新
                    case 1:
                        //价格配置
                        $mconfig = $this->get('db.mconfig')->findOneBy(array('name'=>'timrefresh'));
                
                        //获取时间点数量
                        $arr = explode('-', $data['times']);
                        $count = count(array_flip($arr));
                
                        //应该付价格(每天次数*天数*单价)
                        $total = $count * $data['day'] * $mconfig->getValue();
                
                        if ($total != $data['total_fee'])
                            throw new \LogicException('价格不匹配，请重新确认(是否包含重复时间点，多余的英文逗号等)');
                        break;
                
                        // 智能刷新
                    case 2:
                        $config = $this->get('house.rtconfig')->findOneBy(array('id'=>$data['refconfig'], 'type'=>2), array(), false);
                        if (!is_object($config))
                            throw new \LogicException('该刷新方案不存在');
                
                        if ($config->getPrice() != $data['total_fee'])
                            throw new \LogicException('价格不匹配，请重新确认');
                        break;
                }
                break;

            case 3:
                if (!isset($data['memconfig']))
                    throw new \LogicException('该升级方案不存在');

                $config = $this->get('house.memconfig')->findOneBy(array('id'=>$data['memconfig']), array(), false);
                
                if (!is_object($config))
                    throw new \LogicException('该升级方案不存在');

                if ($data['total_fee'] != $config->getMoney())
                    throw new \LogicException('价格不匹配，请重新确认');
                break;
        }
    }
    
    /**
     * 写入消费表
     * @param array $data
     * @param object $user
     */
    public function addSpend(array $data, $user=null)
    {
        /**
         * 根据充值类型触发相应的操作
         * 1:置顶、2:付费定时刷新、3:付费升级、4:充值、5:提现、6:转账、8:付费手动刷新、10:优惠券
         */
        switch((int)$data['consume'])
        {
            case 1:
                self::consume1Op($data);
                break;
            case 2:
                self::consume2Op($data);
                break;
            case 3:
                self::consume3Op($data);
                break;
            case 4:
                self::consume4Op($data);
                break;
            case 8:
                self::consume8Op($data);
                break;
            case 10:
                self::consume10Op($data);
                break;
            default:
                break;
        }
        
        //消费表数据
        $spendInfo = array();
        
        $spendInfo['uid'] = isset($data['touid'])?(int)$data['touid']:(is_object($user)?$user->getId():0);
        
        $spendInfo['consume'] = isset($data['consume'])?$data['consume']:0;
        
        $spendInfo['amount'] = isset($data['total_fee'])?$data['total_fee']:0;
        
        $spendInfo['value'] = isset($data['number'])?$data['number']:0;
        
        //原始总额
        $spendInfo['cmoney'] = $data['cmoney'];
        
        //使用后的余额
        $spendInfo['bmoney'] = $data['bmoney'];
        
        //是否支付 1已支付，0未支付
        $spendInfo['checked'] = $data['checked'];

        //为谁支付
        $spendInfo['touid'] = is_object($user)?$user->getId():0;
        
        $spendInfo['services'] = isset($data['models'])?trim($data['models']):'';
        
        $spendInfo['aid'] = isset($data['aid'])?(int)$data['aid']:0;
        
        $spendInfo['name'] = isset($this->consume[$spendInfo['consume']])?$this->consume[$spendInfo['consume']]:'充值';
        
        $spendInfo['balance'] = isset($data['balance'])?$data['balance']:0;
        
        //余额足则直接写消费表,触发消费表时一并触发佣金计算服务        
        $info = $this->get('db.spend')->add($spendInfo, null, false);

        $userinfo = is_object($user)?$user->getUserinfo():array();

        //如果是游客支付则中止下一步
        if(!is_object($userinfo))
            return $info;

        //根据余额类型扣金额
        $map = array();
        $map['mid'] = $userinfo->getId();
        $map['name'] = $data['ctype'];

        $userAtt = $this->get('db.user_attr')->findOneBy($map);

        if(!is_object($userAtt))
            return $info;

        $userAtt->setValue($spendInfo['bmoney']);

        //更新
        $this->get('db.user_attr')->update($userAtt->getId(), array(), $userAtt, false);

        return $info;
    }
    
    /**
     * 
     * @param string $serivce   服务名
     * @param array $map        查询条件
     * @throws \Exception
     */
    private function getServiceInfo($serivce, array $map)
    {
        $info = $this->get($serivce)->findOneBy($map);
        if(!is_object($info))
            throw new \Exception('该数据不存在');
        
        return $info;
    }
    
    private function setServiceInfo($serivce, $info)
    {
        $this->get($serivce)->update($info->getId(),array(),$info,false);
    }
    
    /**
     * 置顶操作
     * @param array $data
     */
    public function consume1Op(array $data)
    {
        if(!isset($data['models'])||!isset($data['aid']))
            throw new \Exception('未设置有效模型');
            
        $info = self::getServiceInfo(trim($data['models']), array('id'=>(int)$data['aid']));

        //1天=86400秒
        $number = isset($data['number'])?(int)$data['number']*86400:0;

        $info->setZjdate(($info->getZjdate()<=time())?(time()+$number):$info->getZjdate()+$number);

        self::setServiceInfo(trim($data['models']),$info);
    }
    
    /**
     * 付费定时刷新
     * @param array $data
     */
    public function consume2Op(array $data)
    {
        // 固定每间隔几个小时刷新一次
        if (isset($data['refconfig']))
        {
            $config = $this->get('house.rtconfig')->findOneBy(array('id'=>$data['refconfig']));
            if (!is_object($config))
                throw new \LogicException('该刷新方案不存在');
            
            // 如果已经存在刷新任务并且在有效期内，则进行累加，否则为新增
            // 判断是否为二次添加
            $map = array();
            $map['count']['gt'] = 0;
            $map['service_name'] = 'autoflush';
            $map['model'] = $data['models'];
            $map['mid'] = $data['aid'];
            $map['type'] = 2;
            $result = $this->get('db.cron')->findOneBy($map);
            if (is_object($result))
            {
                $addData = array();
                $addData['count'] = $result->getCount() + $config->getNumber();
                
                $this->get('db.cron')->update($result->getId(), $addData, null, false);
            } else {
            
                // 刷新一次
                self::consume8Op($data);
    
                $addData = array();
                $addData['hour'] = $config->getIntervals();
                $addData['name'] = '付费智能刷新';
                $addData['service_name'] = 'autoflush';
                $addData['count'] = $config->getNumber() - 1;
                $addData['model'] = $data['models'];
                $addData['mid'] = $data['aid'];
                $addData['type'] = 2;
                
                $addData['nextrun'] = time()+$config->getIntervals()*3600;

                $this->get('db.cron')->add($addData, null, false);
            }
        } else {
            // 固定一个或多个时间点，并且每天只刷新一次
    //         $data['stimes'] = "10，11，12";
            $data['stimes'] = str_replace('，', ',', $data['stimes']);
            $times = explode(',', $data['stimes']);
            sort($times);
            $nowHour = date('G.i');
            
            // 如果已经存在刷新任务并且在有效期内，则进行累加，否则为新增
            // 判断是否为二次添加
            $map = array();
            $map['count']['gt'] = 0;
            $map['service_name'] = 'autoflush';
            $map['model'] = $data['models'];
            $map['mid'] = $data['aid'];
            $map['type'] = 1;
            $result = $this->get('db.cron')->findOneBy($map);
            if (is_object($result))
            {
                $res_hour = explode(',', $result->getHour());
                
                //交集,表示有这个时间点，累加次数
                $intersect = array_intersect($res_hour, $times);
                //差集,表示没有这个时间点，则添加上去
                $diff = array_diff($times, $res_hour);
                
                $addData = array();
                $addData['hour'] = implode(',', array_merge($res_hour,$diff));
                $addData['count'] = $result->getCount() + $data['day']*count($times);
                
                $this->get('db.cron')->update($result->getId(), $addData, null, false);
            } else {
            
                $addData = array();
                $addData['hour'] = implode(',', $times);
                $addData['name'] = '付费定时刷新';
                $addData['service_name'] = 'autoflush';
                $addData['count'] = $data['day'] * count($times);
                $addData['model'] = $data['models'];
                $addData['mid'] = $data['aid'];
                $addData['type'] = 1;
                
                //当天下次执行的时间戳
                if (end($times) > $nowHour)
                {
                    foreach ($times as $v)
                    {
                        if ($v > $nowHour)
                        {
                            if (!(substr($v, -2) == '.0') || !(substr($v, -2) == '.00'))
                                $v = $v.'.0';
                            $addData['nextrun'] = strtotime($v);
                            break;
                        }
                    }
                } else {
                    // 次天执行第一次的时间戳
                    if (!(substr($times[0], -2) == '.0') || !(substr($times[0], -2) == '.00'))
                        $times[0] = $times[0].'.0';
                    $addData['nextrun'] = strtotime($times[0]) + 86400;
                }
                
                $this->get('db.cron')->add($addData, null, false);
            }
        }
    }
    
    /**
     * 付费升级操作
     * @param array $data
     */
    public function consume3Op(array $data)
    {
        $map = array();
        $map['id'] = isset($data['memconfig'])?(int)$data['memconfig']:0;
        $memConfig = $this->get('db.memconfig')->findOneBy($map);
        
        if(!is_object($memConfig))
            throw new \Exception('升级方案不存在或已被删除');
        
        if($memConfig->getMoney()>0&&$memConfig->getMoney()!=$data['total_fee'])
            throw new \Exception('金额数不一致');
        
        //读取高级经验人配置
        $map['id'] = 19;
        $memGroup = $this->get('db.mem_group')->findOneBy($map);

        
        if(!is_object($memGroup))
            throw new \Exception('会员组不存在或已被删除');
        
        $userInfo = $this->get('db.userinfo')->findOneBy(array('uid'=>$data['touid']));
        
        //设置会员分类
        $userInfo->setUsertype($memGroup->getAid());
        
        //设置会员组
        $userInfo->setGroupid($memGroup->getId());
        
        //设置刷新次数
        $userInfo->setRefreshs((int)$userInfo->getRefreshs()+(int)$memConfig->getRefreshs());
        
        //设置置顶次数
        $userInfo->setTops((int)$userInfo->getTops()+(int)$memConfig->getTops());
        
        //到期时间
        $userInfo->setDuedate(time()+((int)$memConfig->getDuedate()*86400));

        //更新
        $this->get('db.userinfo')->update($userInfo->getId(), array(), $userInfo, false);
        
        //刷新权限       
        $token = $this->get('security.token_storage')->getToken();
 
        $this->get('core.rbac')->resetToken($token);
    }
    
    /**
     * 充值操作
     * @param array $data
     */
    public function consume4Op(array $data)
    {
        if(!isset($data['uid']))
            return false;

        //获取人民币配置数据
        $map = array();
        $map['id'] = 1;
        $integral = $this->get('db.integral')->findOneBy($map);

        //根据uid查询userinfo
        $map = array();
        $map['uid'] = (int)$data['touid'];
        
        $userInfo = $this->get('db.userinfo')->findOneBy($map);
        
        if(!is_object($userInfo))
            return false;
        
        $map = array();
        $map['mid'] = $userInfo->getId();
        $map['name'] = is_object($integral)?$integral->getEname():'rmb';
        
        $userAttr = $this->get('db.user_attr')->createOneIfNone($map);
        
        //更新金额
        $this->get('db.user_attr')->update($userAttr->getId(), array('value'=>$userAttr->getValue()+$data['total_fee']), $userAttr, false);
    }
    
    /**
     * 提现操作
     * @param array $data
     */
    public function consume5Op(array $data)
    {
    
    }
    
    /**
     * 转帐操作
     * @param array $data
     */
    public function consume6Op(array $data)
    {
    
    }
    
    /**
     * 付费手动刷新
     * @param array $data
     */
    public function consume8Op(array $data)
    {
        $model = $this->get($data['models']);
        if (method_exists($model, 'refresh'))
        {
            if($data['aid'])
            {
                $ids = explode(',', $data['aid']);
                foreach($ids as $id)
                {
                    $model->refresh(array('id'=>$id), true);
                }
            }
        }
    }
    
    /**
     * 写入优惠券购买表
     * @param array $info
     * @param string $which
     */
    public function consume10Op($info, $which='')
    {
        $data = array();
        $data['type'] = 1;
        $data['tel'] = $info['tel'];
        $data['aid'] = $info['aid'];
        $user = $this->get('core.common')->getUser();
        $data['uid'] = is_object($user)?$user->getId():$info['uid'];
        //生成16位的优惠券码
        $data['couponsn'] = $this->get('core.common')->generatePromotionCode(1,'',16);
        // 获取支付宝或者微信账号
        if ($which != '')
            $data[$which] = $this->get('request')->get($which, '');
    
        // 发送优惠券号码到手机
        if ($this->get('request')->get('msgtel',0) == 1)
        {
            $option = array();
            $option['name'] = $info['tel'];
            $option['tpl'] = $this->get('request')->get('tpl', 'couponcode');
            $option['code'] = $data['couponsn'][0];
            $option['title'] = 'title';
            $handle = $this->get('core.sms');
            $handle->setNoCheck(true);
            $handle->send($option);
        }
    
        $this->get('db.couponsorder')->add($data, null, false);
    }
    
    /**
     * 微信扫一扫支付
     * @param array $data
     */
    private function _wxpay(array $data)
    {
        $resourceOwner = $this->get('oauth.security.oauth_utils')->getResourceOwner('weixin');
        
        $data['body'] = isset($this->consume[$data['consume']])?$this->consume[$data['consume']]:'充值';
        $data['attach'] = isset($this->consume[$data['consume']])?$this->consume[$data['consume']]:'充值';
        $data['total_fee'] = $data['fee']*100;
        $data['goods_tag'] = isset($this->consume[$data['consume']])?$this->consume[$data['consume']]:'充值';

        //如果是在手机浏览器支付，则启用H5支付(h5支付不支持在微信浏览器使用，微信浏览器请使用jsapi支付
        if($this->get('core.common')->isMobileClient()){
            // 微信客户端内，使用公众号支付(JSAPI)
            if(strpos($this->get('Request')->server->get('HTTP_USER_AGENT',''),'MicroMessenger') !== false ) {
                return $resourceOwner->getJsApi($data);            
            }
            // 手机浏览器内使用 H5支付           
            return $resourceOwner->getH5Pay($data);       
        }

        return $resourceOwner->getNative($data);
    }
    
    /**
     * 支付宝支付
     * @param array $data
     */
    private function _alipay(array $data)
    {
        $resourceOwner = $this->get('oauth.security.oauth_utils')->getResourceOwner('alipay');
        
        //pc网站(AlipayTradePagePayRequest,API:alipay.trade.page.pay)
        //手机网站(AlipayTradeWapPayRequest,API:alipay.trade.wap.pay)
        $mode = 'page';
        if($this->get('core.common')->isMobileClient()){
            $mode = 'wap';
        }
        
        return new RedirectResponse($resourceOwner->getAlipaySubmit($data,$mode));
    }

    /**
     * 检查订单是否被系统处理过
     * @param  string  $orderid  商户订单ID
     * @return 检查结果
     */
    public function checkOrder($orderid)
    {
        $return = parent::findOneBy(array('ordersn' => $orderid));

        if(is_object($return)&&$return->getStatus() == 1)
            return true;

        return false;
    }

    /**
     * 对支付宝订单进行处理
     * @param  array  $data  订单数据数组
     * @return 检查结果
     */
    public function orderHandleByAlipay($data)
    {
        $data['status'] = 1;
        $data['paytype'] = 'alipay';

        //支付成功的金额
        $cashFee = isset($data['total_fee'])?doubleval($data['total_fee']):0;

        //订单号
        $orderSn = isset($data['ordersn'])?$data['ordersn']:'';

        //支付宝端订单号
        $tradeNo = isset($data['trade_no'])?$data['trade_no']:'';

        //有效时间
        $timeEnd = isset($data['time_end'])?date('Y-m-d H:i:s',strtotime($data['time_end'])):'';

        if(empty($orderSn))
            return false;

        //读取未支付成功的订单
        $map = array();
        $map['ordersn'] = $orderSn;
        $map['checked'] = 0;
        $orderInfo = $this->get('db.orderlist')->findOneBy($map);
        
        if(!is_object($orderInfo))
            return false;
        
        $conditions = $orderInfo->getConditions();
        
        $conditions = json_decode($conditions, true);
        
        //如果是充值，则为人民币
        if(!isset($conditions['consume'])||$conditions['consume']==4)
            $conditions['ctype'] = 'rmb';
        
        //总费用
        $totalFe = isset($conditions['total_fee'])?$conditions['total_fee']:0;
        
        //充值费用
        $fee = isset($conditions['fee'])?doubleval($conditions['fee']):0;
        
        $orderInfo->setTradeNo($tradeNo);
        $orderInfo->setTradeStatus(1);
        $orderInfo->setChecked(1);
        $orderInfo->setNotifyTime($timeEnd);
        
        //更新订单状态
        parent::update($orderInfo->getId(), array(), $orderInfo, false);

        //写入消费表
        $result = self::addSpend(array_merge($conditions, $data));
         
        return $result;
    }

    /**
     * 对支付宝订单修改（退款）
     * @param  string  $trade_no  订单号
     * @param  string  $status    退款状态
     * @return 检查结果
     */
    public function upOrderByTrade($trade_no, $status)
    {
        $order = parent::findOneBy(array('trade_no' => $trade_no, 'status' => 1, 'trade_status' => 'TRADE_SUCCESS'));

        $data = array('status' => 0, 'trade_status' => $status);
        if(!empty($order))
        {
            parent::update($order->getId(), $data, $order, false);
        }

        return false;
    }
    
    /**
     * 处理订单(微信)
     * @param string $orderSn
     */
    public function orderHandleByWeiXin($info,$check=false)
    {
        $info['paytype'] = 'wxpay';
        if(!isset($info['trade_status'])||$info['trade_status']!='SUCCESS')
            return false;

        //支付成功的金额
        $cashFee = isset($info['cash_fee'])?$info['cash_fee']/100:0;
        
        //有效时间
        $timeEnd = isset($info['notify_time'])?date('Y-m-d H:i:s',strtotime($info['notify_time'])):'';

        if(!isset($info['ordersn'])||!$info['ordersn'])
            return false;
        
        //读取未支付成功的订单
        $map = array();
        $map['ordersn'] = isset($info['ordersn'])?$info['ordersn']:'';
        $map['checked'] = 0;
        $orderInfo = $this->get('db.orderlist')->findOneBy($map);

        if(!is_object($orderInfo))
            return false;

        $conditions = $orderInfo->getConditions();
        
        $conditions = json_decode($conditions, true);
        
        //如果是充值，则为人民币
        if(!isset($conditions['consume'])||$conditions['consume']==4)
            $conditions['ctype'] = 'rmb';
        
        //总费用
        $totalFe = isset($conditions['total_fee'])?$conditions['total_fee']:0;
        
        //充值费用
        $fee = $conditions['fee'];
        
        $orderInfo->setTradeNo(isset($info['trade_no'])?$info['trade_no']:'');
        $orderInfo->setTradeStatus(1);
        $orderInfo->setChecked(1);
        $orderInfo->setNotifyTime($timeEnd);

        //更新订单状态
        parent::update($orderInfo->getId(), array(), $orderInfo, false);
        
        //写入消费表
        $result = self::addSpend(array_merge($conditions, $info));
         
        return $result;
    }
    
    /**
     * 重新支付
     * @param array $info
     */
    public function againPay($id)
    {
        $info = parent::findOneBy(array('id' => $id), array(), false);

        if (is_object($info))
            $info = json_decode($info->getConditions(), true);

        if($info['paytype']>0)
        {
            switch($info['paytype'])
            {
                //微信
                case 1:
                    return self::_wxpay($info);
                    break;
        
                    //支付宝
                case 2:
                    return self::_alipay($info);
                    break;
                case 3:
                    break;
                default:
                    break;
            }
        }
    }
}