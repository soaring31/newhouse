<?php
/**
 * Created by PhpStorm.
 * User: zhangshuzhen
 * Date: 2018/4/23
 * Time: 14:44
 */

namespace HouseBundle\Controller\Api;


use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class NotifyController extends RestController
{
    /**
     * @ApiDoc(description="单点登录通知接口", views={"notify"})
     *
     * @Rest\QueryParam(name="token", requirements="\w+", description="token")
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return RedirectResponse
     */
    public function getLoginAction(Request $request)
    {
        if (!$request->getMethod() == 'GET')
            throw new BadRequestHttpException('仅支持 get 请求');

        $token = $request->get('token', '');

        if (!$token)
            throw new BadRequestHttpException('token不存在');

        try {

            return $this->getZhugeSsoHandler()->handleLogin($request, $token);

        } catch (\Exception $e) {
            // sso 登录失败
            return new RedirectResponse($this->get('router')->generate('house_login'));
        }
    }

    /**
     * @ApiDoc(description="passport 单点登录通知接口", views={"notify"})
     *
     * @Rest\QueryParam(name="token", requirements="\w+", description="token")
     *
     * @param Request $request
     *
     * @throws \Exception
     *
     * @return RedirectResponse
     */
    public function getPassPortLoginAction(Request $request)
    {

        $this->get('monolog.logger.zhuge')->info("请求信息", [
            'uri' => $request->getRequestUri()
        ]);

        try {
            if (!$request->getMethod() == 'GET')
                throw new BadRequestHttpException('仅支持 get 请求');

            $token = $request->get('token', '');

            if (!$token)
                throw new BadRequestHttpException('token不存在');

            $deTokens = $this->tokenDecode($token);

            if (empty($deTokens['username']))
                throw new BadRequestHttpException('解析用户失败');

            return $this->getZhugeSsoHandler()->passPortHandleLogin($request, $deTokens, $token);

        } catch (\Exception $e) {
            //打印日志
            $this->get('monolog.logger.zhuge')->error("passport登录失败", [
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'message' => $e->getMessage()
            ]);
            return new RedirectResponse('http://id.zhuge.com/Passport/Login/login');
        }
    }
}