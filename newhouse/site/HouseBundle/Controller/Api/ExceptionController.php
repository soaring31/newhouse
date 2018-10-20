<?php
/**
 * Created by PhpStorm.
 * User: zhangshuzhen
 * Date: 2018/4/14
 * Time: 11:16
 */

namespace HouseBundle\Controller\Api;


use FOS\RestBundle\View\View;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpFoundation\Request;

class ExceptionController
{
    public function showAction(Request $request, $exception)
    {
        if ($exception instanceof FlattenException)
            return $this->returnError($exception->getMessage(), $exception->getCode());
        else
            return $this->returnError('其他错误', 500);
    }

    private function returnError($message, $code)
    {
        return new View([
            'error' => 1,
            'data' =>'',
            'code' => $code,
            'message'=>$message
        ]);
    }
}