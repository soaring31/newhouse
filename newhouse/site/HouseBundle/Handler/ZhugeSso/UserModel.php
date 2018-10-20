<?php
/**
 * Created by PhpStorm.
 * User: zhangshuzhen
 * Date: 2018/4/25
 * Time: 11:42
 */

namespace HouseBundle\Handler\ZhugeSso;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * 单点登录用户Model
 *
 * Class UserModel
 * @package HouseBundle\Security\ZhugeSso
 */
class UserModel
{
    public function __construct($info)
    {
        switch (true) {
            case $info instanceof UserInterface:

                break;
            case is_array($info):

                $username = isset($info['username']) ? $info['username'] : '';
                $this->setUsername($username);

                $password = isset($info['originPassword'])
                    ? $info['originPassword']
                    : (isset($info['password']) ? $info['password'] : 123456);
                $this->setPassword(md5($password));

                isset($info['email']) && $info['email'] && $this->setEmail($info['email']);
                isset($info['tel']) && $info['tel'] && $this->setMobile($info['tel']);
                break;
            default:
        }
    }

    /**
     * @var string
     */
    private $username;

    /**
     * @var int
     */
    private $sex;

    /**
     * @var string
     */
    private $nickname;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $originPassword;

    /**
     * @var string
     */
    private $mobile;

    /**
     * @var string
     */
    private $birthday;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $prcIdCard;

    /**
     * 生成单点登录用户信息
     *
     * @param $appKey
     *
     * @return array
     */
    public function toZhugeSsoUserInfo($appKey)
    {
        return [
            "account" => $this->getUsername(),       //用户账号    不能为空
            "appKey" => $appKey,                     //应用key
            "name" => $this->getNickname(),          //名字
            "phone" => $this->getMobile(),           //手机号
            "email" => $this->getEmail(),            //电子邮件
            "prcIdCard" => $this->getPrcIdCard(),    //身份证号
            "password" => $this->getPassword()  //密码      不能为空
        ];
    }


    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     * @return $this
     */
    public function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }

    /**
     * @return string
     */
    public function getNickname()
    {
        return $this->nickname;
    }

    /**
     * @param string $nickname
     * @return $this
     */
    public function setNickname($nickname)
    {
        $this->nickname = $nickname;
        return $this;
    }


    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOriginPassword()
    {
        return $this->originPassword;
    }

    /**
     * @param mixed $originPassword
     * @return $this
     */
    public function setOriginPassword($originPassword)
    {
        $this->originPassword = $originPassword;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @param mixed $mobile
     * @return $this
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBirthday()
    {
        return $this->birthday;
    }

    /**
     * @param mixed $birthday
     * @return $this
     */
    public function setBirthday($birthday)
    {
        $this->birthday = $birthday;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPrcIdCard()
    {
        return $this->prcIdCard;
    }

    /**
     * @param mixed $prcIdCard
     * @return $this
     */
    public function setPrcIdCard($prcIdCard)
    {
        $this->prcIdCard = $prcIdCard;
        return $this;
    }


}