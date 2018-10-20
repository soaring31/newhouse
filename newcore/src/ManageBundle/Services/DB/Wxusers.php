<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年06月17日
*/
namespace ManageBundle\Services\DB;

use CoreBundle\Services\AbstractServiceManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
* 微信菜单项
*
*/
class Wxusers extends AbstractServiceManager
{
    protected $table = 'Wxusers';
    public function __construct(ContainerInterface $container)
    {
        parent::__construct($container);
        parent::setTables($this->table);
    }

    public function add(array $data,  $info=null, $isValid=true)
    {
        $user = parent::getUser();

        if(empty($user))
            throw new \InvalidArgumentException("请重新登入后再操作！");

        if('' == $_token = $this->get('core.common')->xorEncode($data['appid'])) {
            throw new \InvalidArgumentException("token生成失败！");
        }
        
        $data['uid'] = $user->getId();
        $data['token'] = $_token;
        
        return parent::add($data, $info, $isValid);
    }

    public function update($id, array $data, $info=null, $isValid=true)
    {
        if(!is_object($info))
            $info = parent::findOneBy(array('id'=>$id));

        //嵌入token
        if(method_exists($info, 'setToken'))
            $info->setToken($this->get('core.common')->xorEncode(isset($data['appid'])?$data['appid']:$info->getAppId()));

        if($info->getToken()=="")
            throw new \InvalidArgumentException("token生成失败！");

        return parent::update($id, $data, $info, $isValid);
    }

    /**
     * 获取id对应的微信配置
     * @param int $id     微信用户id/appid
     * @param string $rkey 返回键值,为空返回数组
    */
    public function getCfgByid($id=0,$field='id')
    {
        $fields = array('name','sid','wxaccount','id','appid','appsecret','actoken','acexp','token','uid','type','easkey');
        $drow = parent::findOneBy(array($field=>array('eq'=>$id)));
        $arr = array();
        if(empty($drow)) return $arr;
        $item = array();
        foreach ($fields as $ikey) {
            $mikey = "get".$this->get('core.common')->ucWords($ikey);
            $item[$ikey] = $drow->{$mikey}();
        }
        return $item;
    }

    /**
     * 获取微信配置列表
     * @param int $whrarr   搜索条件
     * @return array $items 返回键值,为空返回数组
    */
    public function getWxList($whrarr=array())
    {

        $whr = empty($whrarr) ? array() : $whrarr;
        $items = $this->get('db.wxusers')->findBy($whr,array('id'=>'DESC'),10);
        $res = empty($items['data']) ? array() : $items['data'];
        return $res;

    }
}