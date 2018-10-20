<?php
    namespace CoreBundle\Services\Ossfiles;
    require_once __DIR__ . '/autoload.php';
    use OSS\OssClient;
    use OSS\Core\OssException;
    use CoreBundle\Services\ServiceBase;
    use Symfony\Component\DependencyInjection\ContainerInterface;


class MultipartUpload   extends ServiceBase
{

    function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->bucket = $this->get('core.common')->C('up_bucket');
        $this->up_access_key = $this->get('core.common')->C('up_access_key');
        $this->up_access_id = $this->get('core.common')->C('up_access_id');
        $this->up_domainname = $this->get('core.common')->C('up_domainname');
        $this->ossClient = new OssClient($this->up_access_id, $this->up_access_key,$this->up_domainname, true);
    }

    /**
     * 创建虚拟目录
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    function createObjectDir($ossClient, $bucket)
    {
        try {
            $ossClient->createObjectDir($bucket, "dir");
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": OK" . "\n");
    }

    /**
     * 把本地变量的内容到文件
     *
     * 简单上传,上传指定变量的内存值作为object的内容
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    function putObject($ossClient, $bucket)
    {
        $object = "oss-php-sdk-test/upload-test-object-name.txt";
        $content = file_get_contents(__FILE__);
        $options = array();
        try {
            $ossClient->putObject($bucket, $object, $content, $options);
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": OK" . "\n");
    }

    /**
     * 上传指定的本地文件内容
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    function uploadFile($option, $result)
    {

        $object   = $result['name'][0];
        $filePath = $result['tmp_name'][0];

        $options = array();
        try {
            $ossClient = new OssClient($option['up_access_id'], $option['up_access_key'],$option['up_domainname'], true);

            $ossClient->uploadFile($option['bucket'], $object, $filePath, $options);

        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        echo "<script>alert('图片上传成功！');</script>";
    }

    /**
     * 图片切糕
     * @param string $token
     * @param UserInterface $userinfo
     */
    public function cupImage($des,$object,$imgW,$imgH,$imgX,$imgY)
    {
        $options = array();

        $des = ltrim($des,'image');
        $style = 'image/crop,w_'.$imgW.',h_'.$imgH.',x_'.$imgX.',y_'.$imgY.$des;

        $options = array(
            OssClient::OSS_PROCESS => $style);

       return $this->ossClient->getUrlimage($this->bucket, $object, $options);

    }

    /**
     * 文字水印 我知道 你继续做 先不打搅你
     * @param string $token
     * @param UserInterface $userinfo
     */
    public function text($object,$style)
    {
        $options = array();

        $options = array(
            OssClient::OSS_PROCESS => $style);

        return $this->ossClient->getUrlimage($this->bucket, $object, $options);

    }

    /**
     * 列出Bucket内所有目录和文件, 注意如果符合条件的文件数目超过设置的max-keys， 用户需要使用返回的nextMarker作为入参，通过
     * 循环调用ListObjects得到所有的文件，具体操作见下面的 listAllObjects 示例
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    function listObjects($ossClient, $bucket)
    {
        $prefix = 'oss-php-sdk-test/';
        $delimiter = '/';
        $nextMarker = '';
        $maxkeys = 1000;
        $options = array(
            'delimiter' => $delimiter,
            'prefix' => $prefix,
            'max-keys' => $maxkeys,
            'marker' => $nextMarker,
        );
        try {
            $listObjectInfo = $ossClient->listObjects($bucket, $options);
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": OK" . "\n");
        $objectList = $listObjectInfo->getObjectList(); // 文件列表
        $prefixList = $listObjectInfo->getPrefixList(); // 目录列表
        if (!empty($objectList)) {
            print("objectList:\n");
            foreach ($objectList as $objectInfo) {
                print($objectInfo->getKey() . "\n");
            }
        }
        if (!empty($prefixList)) {
            print("prefixList: \n");
            foreach ($prefixList as $prefixInfo) {
                print($prefixInfo->getPrefix() . "\n");
            }
        }
    }

    /**
     * 列出Bucket内所有目录和文件， 根据返回的nextMarker循环得到所有Objects
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    function listAllObjects($ossClient, $bucket)
    {
        //构造dir下的文件和虚拟目录
        for ($i = 0; $i < 100; $i += 1) {
            $ossClient->putObject($bucket, "dir/obj" . strval($i), "hi");
            $ossClient->createObjectDir($bucket, "dir/obj" . strval($i));
        }
        $prefix = 'dir/';
        $delimiter = '/';
        $nextMarker = '';
        $maxkeys = 30;
        while (true) {
            $options = array(
                'delimiter' => $delimiter,
                'prefix' => $prefix,
                'max-keys' => $maxkeys,
                'marker' => $nextMarker,
            );

            try {
                $listObjectInfo = $ossClient->listObjects($bucket, $options);
            } catch (OssException $e) {
                printf(__FUNCTION__ . ": FAILED\n");
                printf($e->getMessage() . "\n");
                return;
            }
            // 得到nextMarker，从上一次listObjects读到的最后一个文件的下一个文件开始继续获取文件列表
            $nextMarker = $listObjectInfo->getNextMarker();
            $listObject = $listObjectInfo->getObjectList();
            $listPrefix = $listObjectInfo->getPrefixList();
            var_dump(count($listObject));
            var_dump(count($listPrefix));
            if ($nextMarker === '') {
                break;
            }
        }
    }

    /**
     * 获取object的内容
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    function getObject($ossClient, $bucket)
    {
        $object = "oss-php-sdk-test/Penguins.jpg";
        $options = array();
        try {
            $content = $ossClient->getObject($bucket, $object, $options);
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": OK" . "\n");
        if (file_get_contents(__FILE__) === $content) {
            print(__FUNCTION__ . ": FileContent checked OK" . "\n");
        } else {
            print(__FUNCTION__ . ": FileContent checked FAILED" . "\n");
        }
    }

    /**
     * get_object_to_local_file
     *
     * 获取object
     * 将object下载到指定的文件
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */


    function getObjectToLocalFile($ossClient, $bucket)
    {
        $object = "oss-php-sdk-test/Koala.jpg";
        $localfile = "Koala.jpg";
        $options = array(
            OssClient::OSS_FILE_DOWNLOAD => $localfile,
        );
        try {
            $ossClient->getObject($bucket, $object, $options);
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": OK, please check localfile: Koala.jpg'" . "\n");
    }

    /**
     * 拷贝object
     * 当目的object和源object完全相同时，表示修改object的meta信息
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    function copyObject($ossClient, $bucket)
    {
        $fromBucket = $bucket;
        $fromObject = "oss-php-sdk-test/upload-test-object-name.txt";
        $toBucket = $bucket;
        $toObject = $fromObject . '.copy';
        $options = array();
        try {
            $ossClient->copyObject($fromBucket, $fromObject, $toBucket, $toObject, $options);
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": OK" . "\n");
    }

    /**
     * 修改Object Meta
     * 利用copyObject接口的特性：当目的object和源object完全相同时，表示修改object的meta信息
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    function modifyMetaForObject($ossClient, $bucket)
    {
        $fromBucket = $bucket;
        $fromObject = "oss-php-sdk-test/upload-test-object-name.txt";
        $toBucket = $bucket;
        $toObject = $fromObject;
        $copyOptions = array(
            OssClient::OSS_HEADERS => array(
                'Cache-Control' => 'max-age=60',
                'Content-Disposition' => 'attachment; filename="xxxxxx"',
            ),
        );
        try {
            $ossClient->copyObject($fromBucket, $fromObject, $toBucket, $toObject, $copyOptions);
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": OK" . "\n");
    }

    /**
     * 获取object meta, 也就是getObjectMeta接口
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    function getObjectMeta($ossClient, $bucket)
    {
        $object = "oss-php-sdk-test/upload-test-object-name.txt";
        try {
            $objectMeta = $ossClient->getObjectMeta($bucket, $object);
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": OK" . "\n");
        if (isset($objectMeta[strtolower('Content-Disposition')]) &&
            'attachment; filename="xxxxxx"' === $objectMeta[strtolower('Content-Disposition')]
        ) {
            print(__FUNCTION__ . ": ObjectMeta checked OK" . "\n");
        } else {
            print(__FUNCTION__ . ": ObjectMeta checked FAILED" . "\n");
        }
    }

    /**
     * 删除object
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    function deleteObject($ossClient, $bucket)
    {
        $object = "oss-php-sdk-test/upload-test-object-name.txt";
        try {
            $ossClient->deleteObject($bucket, $object);
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": OK" . "\n");
    }

    /**
     * 批量删除object
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    function deleteObjects($ossClient, $bucket)
    {
        $objects = array();
        $objects[] = "oss-php-sdk-test/upload-test-object-name.txt";
        $objects[] = "oss-php-sdk-test/upload-test-object-name.txt.copy";
        try {
            $ossClient->deleteObjects($bucket, $objects);
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": OK" . "\n");
    }

    /**
     * 判断object是否存在
     *
     * @param OssClient $ossClient OssClient实例
     * @param string $bucket 存储空间名称
     * @return null
     */
    function doesObjectExist($ossClient, $bucket)
    {
        $object = "oss-php-sdk-test/upload-test-object-name.txt";
        try {
            $ossClient->doesObjectExist($bucket, $object);
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": OK" . "\n");
    }


}