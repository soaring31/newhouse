<?php

namespace HouseBundle\Handler;


class RequestHandler extends HandlerBase
{
    /**
     *
     * @param unknown $url
     * @return unknown
     */
    public function curl_get($url)
    {
        $ch_curl = curl_init();
        curl_setopt($ch_curl, CURLOPT_TIMEOUT_MS, 3000);
        curl_setopt($ch_curl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch_curl, CURLOPT_HEADER, false);
        curl_setopt($ch_curl, CURLOPT_HTTPGET, 1);
        curl_setopt($ch_curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch_curl, CURLOPT_URL, $url);
        $str = curl_exec($ch_curl);
        curl_close($ch_curl);
        return $str;
    }

    /**
     *
     * @param string $url
     * @param array $fields
     * @return mixed
     */
    public function curl_post($url, $fields, $timeout = 3000)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.648; .NET CLR 3.5.21022)");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT_MS, $timeout);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function xml_curl_post($url, $fields, $timeout = 3000)
    {
        $ch = curl_init();
        $header[] = "Content-type: text/xml";//定义content-type为xml
        curl_setopt($ch, CURLOPT_URL, $url); //定义表单提交地址
        curl_setopt($ch, CURLOPT_POST, 1);   //定义提交类型 1：POST ；0：GET
        curl_setopt($ch, CURLOPT_HEADER, 0); //定义是否显示状态头 1：显示 ； 0：不显示
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);//定义请求类型
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//定义是否直接输出返回流
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); //定义提交的数据，这里是XML文件
        $response = curl_exec($ch);
        curl_close($ch);//关闭
        return $response;
    }

}