<?php
/**
 * Created by PhpStorm.
 * User: zhangshuzhen
 * Date: 2018/4/24
 * Time: 23:49
 */

namespace HouseBundle\Handler;


use CoreBundle\Functions\Common;
use HouseBundle\Handler\ZhugeSso\UserModel;
use HouseBundle\Utils\HttpUtils;
use ManageBundle\Services\DB\Users;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;

class ZhugeSsoHandler extends HandlerBase
{
    static $baseUrl = 'http://dev-api.rocket.zhuge.com';
    static $newUserUri = 'api/v1/auth/user/create';
    static $updateUserUri = 'api/v1/auth/user/update';
    static $deleteUserUri = 'api/v1/auth/user/delete';

    static $logoutUrl = '/logout';

    private $appKey = '76dc30348c194694a11c69c65d82d627';
    private $appSecret = 'z#1bgdSGd}#ns9$^';

    /**
     * 是否开启同步
     *
     * @var bool
     */
    private $sync = true;

    /**
     * @var HttpUtils
     */
    private $httpUtils;

    /**
     * @var Users
     */
    private $users;

    /**
     * @var Router
     */
    private $router;

    /**
     * @var Common
     */
    private $common;

    /**
     * @var array
     */
    private $sso;

    /**
     * ZhugeSsoHandler constructor.
     * @param HttpUtils $httpUtils
     * @param Users $users
     * @param Common $common
     * @param array $sso zhugeSso 配置参数
     */
    public function __construct(Router $router, HttpUtils $httpUtils, Users $users, Common $common, array $sso)
    {
        $this->router    = $router;
        $this->users     = $users;
        $this->common    = $common;
        $this->httpUtils = $httpUtils;
        $this->sso       = $sso;
    }


    // 通过 token 解析出 sso 返回的信息

    // 通过 session_id 获取存储的 sso token

    // 存储 memcache

    // 存储本地 cookie

    // 验证 memcache

    // 验证本地缓存

    /**
     * 新增用户
     *
     * @param UserModel $model
     * @throws \Exception
     *
     * @return boolean
     */
    public function addUser(UserModel $model)
    {
        try {
            $body = $model->toZhugeSsoUserInfo($this->appKey);
            $url  = sprintf('%s/%s', self::$baseUrl, self::$newUserUri);
            // 记录日志
            $this->logger->info(sprintf('Request sso Create User ; Request url %s', $url), $body);

            $res = $this->httpUtils->post($url, json_encode($body), 30, [
                'Content-Type: application/json'
            ]);

            // 记录返回日志
            $this->logger->info('Response zhugeSso Create User Result', [$res]);
            $res = json_decode($res, true);
            if (!strtolower($res['code']) == 'success') {
                $this->logger->error('Response zhugeSso Create User Failure', [$res]);

                return false;
            }

            return true;

        } catch (\Exception $e) {

            // 记录返回日志
            $this->logger->error('Response zhugeSso Create User System Error', [$e->getMessage()]);

            return false;
        }
    }

    /**
     * 更新用户信息
     *
     * @param UserModel $model
     * @return bool
     */
    public function updateUser(UserModel $model)
    {
        try {
            $body = $model->toZhugeSsoUserInfo($this->appKey);
            $url  = sprintf('%s/%s', self::$baseUrl, self::$updateUserUri);
            // 记录日志
            $this->logger->info(sprintf('Request sso Update User ; Request url %s', $url), $body);

            $res = $this->httpUtils->post($url, json_encode($body), 30, [
                'Content-Type: application/json'
            ]);

            // 记录返回日志
            $this->logger->info('Response zhugeSso Update User Result', [$res]);
            $res = json_decode($res, true);
            if (!strtolower($res['code']) == 'success') {
                $this->logger->error('Response zhugeSso Update User Failure', [$res]);

                return false;
            }

            return true;

        } catch (\Exception $e) {

            // 记录返回日志
            $this->logger->error('Response zhugeSso Update User System Error', [$e->getMessage()]);

            return false;
        }
    }

    /**
     * 删除用户
     *
     * @param UserModel $model
     * @return bool
     */
    public function delUser(UserModel $model)
    {
        try {
            $body = $model->toZhugeSsoUserInfo($this->appKey);
            $url  = sprintf('%s/%s', self::$baseUrl, self::$updateUserUri);
            // 记录日志
            $this->logger->info(sprintf('Request sso Delete User ; Request url %s', $url), $body);

            $res = $this->httpUtils->post($url, json_encode($body), 30, [
                'Content-Type: application/json'
            ]);

            // 记录返回日志
            $this->logger->info('Response zhugeSso Delete User Result', [$res]);
            $res = json_decode($res, true);
            if (!strtolower($res['code']) == 'success') {
                $this->logger->error('Response zhugeSso Delete User Failure', [$res]);

                return false;
            }

            return true;

        } catch (\Exception $e) {

            // 记录返回日志
            $this->logger->error('Response zhugeSso Delete User System Error', [$e->getMessage()]);

            return false;
        }
    }

    // 登录
    public function handleLogin(Request $request, $token)
    {
        $sso       = $this->sso;
        $decrypted = openssl_decrypt($token, 'AES-128-ECB', $sso['appSecret']);

        // sso 登录失败 重新跳转登录页面
        if (!$decrypted || !$loginInfo = json_decode($decrypted, true))
            return new RedirectResponse($this->router->generate('house_login'));

        $this->users->autoLogin($loginInfo['account']);

        // memcache 存储 sessionId 保存30分钟
        $key = sprintf('%s_%s', $sso['keyIndex'], $loginInfo['sessionId']);
        $this->common->S($key, $token, $sso['lifeTime']);
        $response = new RedirectResponse($loginInfo['redirect']);
        $response->headers->setCookie(new Cookie($sso['cookieName'], $loginInfo['sessionId']));

        return $response;
    }

    // passport登录
    public function passPortHandleLogin(Request $request, array $deTokens, $token)
    {
        $username = $deTokens['username'];

        $sso = $this->sso;
        $this->users->autoLogin($username);

        // memcache 存储 sessionId 保存30分钟
        $key = sprintf('%s_%s', $sso['keyIndex'], $deTokens['id']);
        $this->common->S($key, $token, $sso['lifeTime']);

        $response = new RedirectResponse("/");
        $response->headers->setCookie(new Cookie('passport', base64_encode(json_encode($deTokens))));

        return $response;
    }

    /**
     * 退出登录
     *
     * @param $redirectUrl
     * @return bool
     */
    public function handleLogout(Request $request, $redirectUrl)
    {
        $sso       = $this->sso;
        $parameter = sprintf('redirect=%s&appKey=%s', $redirectUrl, $this->appKey);
        $url       = sprintf('%s/%s?%s', self::$baseUrl, self::$logoutUrl, $parameter);

        // 记录日志
        $this->logger->info(sprintf('Request sso Logout User ; Request url %s', $url), [$parameter]);

        $res = $this->httpUtils->get($url);

        // 记录返回日志
        $this->logger->info('Response zhugeSso Logout User Result', [$res]);

        // sessionId
        if ($sessionId = $request->cookies->get($sso['cookieName'])) {
            // 清除缓存
            $key = sprintf('%s_%s', $sso['keyIndex'], $sessionId);
            $this->common->S($key);
            $request->cookies->remove($sessionId);
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isSync()
    {
        return $this->sync;
    }
}