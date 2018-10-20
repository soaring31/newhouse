<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年12月26日
*/
namespace HouseBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 会员认证列表
* 
*/
class UserCert extends AbstractServiceManager
{
    protected $table = 'UserCert';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }
    
    public function add(array $data,  $info=null, $isValid=true)
    {
        if (isset($data['value']) && $data['value'])
            self::_checkExist($data['value']);
        
        return parent::add($data, $info, $isValid);
    }
    
    public function update($id, array $data, $info=null, $isValid=true)
    {
        if (isset($data['value']) && $data['value'])
            self::_checkExist($data['value']);
        
        return parent::update($id, $data, $info, $isValid);
    }
    
    public function delete($id, $info=null)
    {
        $result = self::findOneBy(array('id'=>$id), array(), false);
        if ($result && method_exists($result, 'getValue') && '' != $result->getValue() && !self::_checkIsSelf())
            throw new \LogicException('请填写当前用户的手机（或邮箱）');
        
        return parent::delete($id, $info);
    }
    
    // 检查手机或者邮箱是否属于当前用户
    private function _checkIsSelf()
    {
        $user = parent::getUser();
        if(!is_object($user))
            throw new \LogicException('请登录后再操作');
        
        if ($user->getMid() == 1)
            return true;
        
        $value = $this->get('request')->get('value',0);
        
        if (empty($value))
            return false;
        
        if($this->get('core.common')->isMobile($value))
        {
            //值为手机号
            if ($user->getTel() != $value)
                return false;
        } elseif (filter_var($value, FILTER_VALIDATE_EMAIL)) {
            //值为邮箱
            if ($user->getEmail() != $value)
                return false;
        } else 
            return false;
        
        return true;
    }
    
    private function _checkExist($value)
    {
        $map = array();
        $map['value'] = $value;
        $result = self::findOneBy($map, array(), false);
        
        if (!empty($result))
            throw new \LogicException('该手机号码（或者邮箱）已经被认证过！');
    }
}