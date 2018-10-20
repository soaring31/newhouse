<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2015-05-05
 */

namespace CoreBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use CoreBundle\Security\Http\TokenAuthenticatedInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as SymfonyController;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * Controller重写
 */
class Controller extends SymfonyController implements TokenAuthenticatedInterface
{
    protected $token;
    protected $wxid;
    protected $agentid;
    protected $parameters = array();

    /**
     * 架构函数 取得模板对象实例
     * @access public
     */
    public function __construct()
    {
        //控制器初始化
        if (method_exists($this, '_initialize'))
            $this->_initialize();
    }

    protected function _empty($method, $args)
    {
        return self::error(sprintf('[ %s ]方法不存在', $method));
    }

    /**
     * 外调模版
     */
    public function fragmentstplAction()
    {
        $template = $this->get('request')->get('tpl', '');

        if (!preg_match('/^fragments/', $template))
            $template = "fragments/" . $template;

        if (!$this->get('templating')->exists($template))
            throw new NotFoundResourceException("没有发现模版文件:【" . $template . "】", 403);

        return self::setCache(parent::render($template));
    }

    /**
     * Renders a view.
     *
     * @param string $bundle The bundle name
     * @param array $parameters An array of parameters to pass to the view
     * @param Response $response A response instance
     * @param bool $returnParameter return Result Directly
     *
     * @return array|Response
     */
    public function render($bundle, array $parameters = array(), Response $response = null, $returnParameter = false)
    {
        $defaultThemes = $this->get('core.common')->C('default_themes');
        $_bundle       = $bundle;
        $usertplid     = "";
        $user          = parent::getUser();

        $locale = $this->get('request')->getLocale();

        if (method_exists($user, 'getUsertplid') && $user->getUsertplid() != '')
            $usertplid = $user->getUsertplid();
        elseif ($_bundle != 'ManageBundle')
            $usertplid = $defaultThemes;

        $lang = $this->get('db.language')->getData($locale, 'ename');

        //判断当前语言包是否绑定主题
        if (isset($lang['tplid']) && $lang['tplid'])
            $usertplid = is_object($user) && $user->getUsertplid() == 'MemberBundle' && isset($lang['membertpl']) && $lang['membertpl'] ? $lang['membertpl'] : $lang['tplid'];

        $usertplid  = $usertplid ? $usertplid : $bundle;
        $_usertplid = $usertplid;

        //加载一些初始化的参数(包括基本信息、ident、formview)
        //此模板仅作为获取参数的特征，将要渲染的模板与此无关，与主题无关，只与应用bundle有关系
        $template   = $bundle . ':' . ucfirst($this->getControllerName()) . ':' . $this->getActionName() . '.html.twig';
        $parameters = array_merge($this->getInitInfo($template), $parameters);

        if ($this->get('request')->get('datatype') === 'json')
            $this->showMessage('成功', 1, $parameters, '', true);

        //判断手机浏览器
        if ($this->get('core.common')->isMobileClient()) {
            //去掉bundle后缀
            if (preg_match('/Bundle$/', $bundle))
                $bundle = substr($bundle, 0, -6) . "mobileBundle";

            //去掉bundle后缀
            if (preg_match('/Bundle$/', $usertplid))
                $usertplid = substr($usertplid, 0, -6) . "mobileBundle";

            $parameters['themespath'] = $usertplid ? strtolower(substr($usertplid, 0, -6)) : strtolower(substr($this->getBundleName(), 0, -6));

            //如果手机模版不存在则使用模认的手机模版
            if (!$this->get('templating')->exists($usertplid . ':' . ucfirst($this->getControllerName()) . ':' . $this->getActionName() . '.html.twig')) {
                $usertplid = $bundle;

                //如果默认主题模版不存在则用PC主题
                if (!$this->get('templating')->exists($usertplid . ':' . ucfirst($this->getControllerName()) . ':' . $this->getActionName() . '.html.twig'))
                    $usertplid = $_usertplid;
            }
        } else {
            if ($this->has('db.auth_theme')) {
                $map               = array();
                $map['bundle']     = $this->getBundleName();
                $map['controller'] = $this->getControllerName();
                $map['action']     = $this->getActionName();
                $count             = $this->get('db.auth_theme')->getData($map);
                if ($count)
                    $usertplid = $this->getBundleName();
            }
        }

        //主题模板
        $template = $usertplid . ':' . ucfirst($this->getControllerName()) . ':' . $this->getActionName() . '.html.twig';

        //强制主题
        if (isset($parameters['ident']['tplcfg']['data']) && $parameters['ident']['tplcfg']['data']) {
            $tplcfg = end($parameters['ident']['tplcfg']['data']);
            if (isset($tplcfg['tplcfg']) && $tplcfg['tplcfg'])
                $template = $bundle . ':' . ucfirst($this->getControllerName()) . '/' . $tplcfg['tplcfg'] . ':' . $this->getActionName() . '.html.twig';
        }

        $template1 = $template;

        // 如果主题中没有该模板，则使用主题模板
        if (!$this->get('templating')->exists($template)) {
            $template = $defaultThemes . ':' . ucfirst($this->getControllerName()) . ':' . $this->getActionName() . '.html.twig';
        }

        // 如果主题中没有该模板，则使用应用bundle模板
        if (!$this->get('templating')->exists($template)) {
            $template = $_bundle . ':' . ucfirst($this->getControllerName()) . ':' . $this->getActionName() . '.html.twig';
        }

        //如果没有模版则使用核心模版
        if (!$this->get('templating')->exists($template)) {
            $template = 'CoreBundle:' . ucfirst($this->getControllerName()) . ':' . $this->getActionName() . '.html.twig';
        }

        if (!$this->get('templating')->exists($template))
            throw new NotFoundResourceException("没有发现模版文件:【" . $template1 . "】", 403);

        if ($returnParameter) {
            return $parameters;
        }

        return self::setCache(parent::render($template, $parameters, $response));
    }

    /**
     * 指定模板渲染，在 parent::render() 之前处理基本信息、标签、表单等。
     *
     * @param string $view The view name
     * @param array $parameters An array of parameters to pass to the view
     * @param Response $response A response instance
     *
     * @return Response A Response instance
     */
    public function render1($view, array $parameters = array(), Response $response = null)
    {
        //加载一些初始化的参数
        $parameters = array_merge($this->getInitInfo($view), $parameters);

        if ($this->get('request')->get('dataType') === 'json')
            $this->showMessage('成功', 1, $parameters, '', true);

        if (!$this->get('templating')->exists($view))
            throw new NotFoundResourceException("没有发现模版文件:【" . $view . "】", 404);

        return self::setCache(parent::render($view, $parameters, $response));
    }

    /**
     * 获得初始化信息，嵌入模版用
     * @return Ambigous <string, string>
     */
    final protected function getInitInfo($template)
    {
        return $this->get('core.common')->getViewInit($template);
    }

    protected function _formType($modelForm, &$initInfo, $preview = false, $muli = false)
    {
        $_copy = (int)$this->get('request')->get('_copy', 0);
        return $this->get('core.common')->getForm($modelForm, $initInfo, $preview, $_copy, 0, $muli);
    }

    /**
     * 获取表单字段属性
     * @param string $name 表单名称
     * @param array $map 表单提交参数(如id>0为编辑，id=0的为新增)
     * @param string $method 表单提交方法{POST,GET,PUT,DELETE}
     * @param array $info 表单值(编辑时用)
     * @param string $action 表单的ACTION地址
     * @param string $prefix 前缀
     * @param string $preview 预览模式
     */
    public function getFormFieldAttr($name, array $map, $method, $info = null, $action = 'save', $prefix = '', $preview = false)
    {
        return $this->get('core.common')->getFormFieldAttr($name, $map, $method, $info, $action, $prefix, $preview);
    }

    /**
     * 获得所有的已加载Bundle
     */
    public function getBundles()
    {
        return $this->get('core.common')->getBundles();
    }

    /**
     * 根据ID计算文件存放目录
     * @param int $id
     * @return string
     */
    public function getFileDir($id)
    {
        return $this->get('core.common')->getFileDir($id);
    }

    /**
     * 根据ID计算文件存放子目录
     * @param int $id
     * @return string
     */
    final protected function getFileSubDir($id)
    {
        return $this->get('core.common')->getFileSubDir($id);
    }

    /**
     * 获得Bundle路径
     * @param string $bundleName
     */
    final protected function getBundlePath($bundleName)
    {
        return $this->get('core.common')->getBundlePath($bundleName);
    }

    /**
     * 获得Bundle的命名空间
     */
    final protected function getBundleNamespace($bundleName)
    {
        return $this->get('core.common')->getBundleNamespace($bundleName);
    }

    /**
     * 获得当前路由的bundle名称
     */
    final protected function getBundleName($flag = false)
    {
        return $this->get('core.common')->getViewBundleName($flag);
    }

    /**
     * 获得当前路由的控制器名称
     */
    final protected function getControllerName()
    {
        return $this->get('core.common')->getControllerName();
    }

    /**
     * 获得当前路由的动作名称
     */
    final protected function getActionName()
    {
        return $this->get('core.common')->getActionName();
    }

    final protected function getRequestUrl()
    {
        return $this->get('request')->server->get('QUERY_STRING');
    }

    /**
     * 根据路由参数创建csrf
     * @return csrf
     */
    protected function createCsrfToken()
    {
        //返回csrf值
        return $this->get('core.common')->createCsrfToken();
    }

    /**
     * 操作成功跳转的快捷方法
     * @access protected
     * @param string $message 提示信息
     * @param string $jumpUrl 页面跳转地址
     * @param Boolean|array $ajax 是否为Ajax方式
     * @return void
     */
    final protected function success($message, $jumpUrl = '', $ajax = false, $paramet = array())
    {
        return $this->showMessage($message, 1, is_array($jumpUrl) ? $jumpUrl : array(), is_array($jumpUrl) ? '' : $jumpUrl, $ajax);
    }

    /**
     * 操作错误跳转的快捷方法
     * @access protected
     * @param string $message 错误信息
     * @param string $jumpUrl 页面跳转地址
     * @param Boolean|array $ajax 是否为Ajax方式
     * @return void
     */
    final protected function error($message, $jumpUrl = '', $ajax = false)
    {
        return $this->showMessage($message, 0, is_array($jumpUrl) ? $jumpUrl : array(), is_array($jumpUrl) ? '' : $jumpUrl, $ajax);
    }

    /**
     * 生成URL
     * @param string $url
     * @param string $vars
     * @param string $domain
     */
    final protected function U($url = '', $vars = '', $domain = false)
    {
        return $this->get('core.common')->U($url, $vars, $domain);
    }

    /**
     * 读取配置文件
     * @param string $str
     */
    final protected function C($str)
    {
        return $this->get('core.common')->C($str);
    }

    /**
     * 获取site绝对路径
     */
    final protected function getSiteRoot()
    {
        return $this->get('core.common')->getSiteRoot();
    }

    /**
     * 数据输出
     * ajax请求默认输出去json格式
     * 非ajax请求有跳转的跳转，无跳转的直接到错误模版输出
     * @param string $message
     * @param int $status
     * @param string $jumpUrl
     * @param bool $ajax
     * @return array()
     */
    final protected function showMessage($message, $status = 1, array $newdata = array(), $jumpUrl = '', $ajax = false)
    {
        return $this->get('core.common')->showMessage($message, $status, $newdata, $jumpUrl, $ajax);
    }

    /**
     * 获得拼音首字大写字母
     * @param string $str
     * @return string
     */
    final public function getCamelChars($str)
    {
        return $this->get('core.common')->getCamelChars($str);
    }

    /**
     * 清除缓存
     */
    public function cleanCace()
    {
        $this->get('core.common')->cleanCache();
    }

    /**
     * 删除目录文件
     */
    private function delDirAndFile($dir)
    {
        $this->get('core.common')->delDir($dir);
    }

    /**
     * 文件上传
     */
    public function uploadAction()
    {
        //云上传
        if ($this->get('core.common')->C('upload_type') == 'upyun') {
            if (!empty($_FILES)) {
                $option                  = array();
                $option['bucket']        = $this->get('core.common')->C('up_bucket');
                $option['up_access_key'] = $this->get('core.common')->C('up_access_key');
                $option['up_access_id']  = $this->get('core.common')->C('up_access_id');
                $option['up_domainname'] = $this->get('core.common')->C('up_domainname');

                if (empty($_FILES)) {
                    echo "<script>alert('请选择文件！');</script>";
                    return $this->render1('CoreBundle:Upload:local.html.twig', $this->parameters);
                }

                $type = explode("/", $_FILES['photo']['type'][0]);

                switch ($type[0]) {
                    case 'image':
                        if ($_FILES['photo']['size'][0] > ($this->get('core.common')->C('up_image_size') * 1024 * 1024)) {
                            echo "<script>alert('图片文件超出限制大小！');</script>";
                            return $this->render1('CoreBundle:Upload:local.html.twig', $this->parameters);
                        }
                        break;
                    case 'video':
                        if ($_FILES['photo']['size'][0] > $this->get('core.common')->C('up_file_size') * 1024 * 1024) {
                            echo "<script>alert('视频文件超出限制大小');</script>";
                            return $this->render1('CoreBundle:Upload:local.html.twig', $this->parameters);
                        }
                        break;
                }

                $this->parameters = $this->get('db.files')->ossUpload($option, $this->token, $this->get('core.common')->getUserDb());
            }

            return $this->render1('CoreBundle:Upload:local.html.twig', $this->parameters);
        } //本地上传
        else if ($this->get('core.common')->C('upload_type') == 'local') {
            if ($this->get('request')->getMethod() == "POST") {
                //判断是否已登入，未登入不允许上传
                if (!is_object(parent::getUser())) {
                    echo "<script>alert('游客不允许上传');</script>";
                    return $this->render1('CoreBundle:Upload:local.html.twig', $this->parameters);
                }

                if (empty($_FILES)) {
                    echo "<script>alert('请选择文件！');</script>";
                    return $this->render1('CoreBundle:Upload:local.html.twig', $this->parameters);
                }

                $photo = $this->get('request')->files->get('photo');

                if (is_object($photo)) {
                    $type = explode("/", $_FILES['photo']['type']);

                    switch ($type) {
                        case 'image':
                            if ($_FILES['photo']['size'] > ($this->get('core.common')->C('up_image_size') * 1024 * 1024)) {
                                $this->parameters['status'] = 0;
                                $this->parameters['info']   = '图片文件超出限制大小！';
                                return $this->render1('CoreBundle:Upload:local.html.twig', $this->parameters);
                            }
                            break;
                        case 'video':
                            if ($_FILES['photo']['size'] > $this->get('core.common')->C('up_file_size') * 1024 * 1024) {
                                $this->parameters['status'] = 0;
                                $this->parameters['info']   = '视频文件超出限制大小！';
                                return $this->render1('CoreBundle:Upload:local.html.twig', $this->parameters);
                            }
                            break;
                    }

                    if (!empty($photo)) {
                        $this->parameters = $this->get('db.files')->localUpload($this->token, $this->get('core.common')->getUserDb());

                        //输出文件提交信息，构造返回信息
                        echo $this->parameters["error"] . "|";
                        echo $this->parameters["msg"] . "|";
                        echo $this->parameters["name"];
                    } else {
                        $this->parameters['status'] = 0;
                        $this->parameters['info']   = '请选择文件！';
                    }
                } elseif (is_array($photo)) {
                    $type = explode("/", $_FILES['photo']['type'][0]);

                    switch ($type[0]) {
                        case 'image':
                            if ($_FILES['photo']['size'][0] > ($this->get('core.common')->C('up_image_size') * 1024 * 1024)) {
                                //echo "<script>alert('图片文件超出限制大小！');</script>";
                                $this->parameters['status'] = 0;
                                $this->parameters['info']   = '图片文件超出限制大小！';
                                return $this->render1('CoreBundle:Upload:local.html.twig', $this->parameters);
                            }
                            break;
                        case 'video':
                            if ($_FILES['photo']['size'][0] > $this->get('core.common')->C('up_file_size') * 1024 * 1024) {
                                // echo "<script>alert('视频文件超出限制大小');</script>";
                                $this->parameters['status'] = 0;
                                $this->parameters['info']   = '视频文件超出限制大小！';
                                return $this->render1('CoreBundle:Upload:local.html.twig', $this->parameters);
                            }
                            break;
                    }

                    if (!empty($photo[0])) {
                        $this->parameters = $this->get('db.files')->localUpload($this->token, $this->get('core.common')->getUserDb());
                    } else {
                        // echo "<script>alert('请选择文件！');</script>";
                        $this->parameters['status'] = 0;
                        $this->parameters['info']   = '请选择文件！';
                    }
                } elseif (is_array($_FILES) && isset($_FILES['upfile'])) {
                    /**
                     * 目前用于百度编辑器里复制图片(ctrl+V)的上传操作
                     * @var unknown
                     */
                    $file = $_FILES['upfile'];
                    $type = explode('/', $file['type']);
                    if ($type[0] == 'image')
                        if ($file['size'] > ($this->get('core.common')->C('up_image_size') * 1024 * 1024))
                            return $this->error('图片文件超出限制大小！');

                    $result = $this->get('db.files')->localUpload($this->token, $this->get('core.common')->getUserDb());
                    if ($result['error'] == 0) {
                        $return             = array();
                        $return['original'] = $file['name'];
                        $return['size']     = $file['size'];
                        $return['state']    = 'SUCCESS';
                        $return['title']    = $result['name'];
                        $return['type']     = '.' . $type[1];
                        $return['url']      = '/' . $result['msg'];

                        return $this->get('cc')->ajaxReturn($return, 'json');
                    }
                }
            }

            return $this->render1('CoreBundle:Upload:local.html.twig', $this->parameters);
        } //远程上传
        else if ($this->get('core.common')->C('upload_type') == 'remote') {
            if ($this->get('request')->getMethod() == "POST") {
                //判断是否已登入，未登入不允许上传
                if (!is_object(parent::getUser())) {
                    echo "<script>alert('游客不允许上传');</script>";
                    return $this->render1('CoreBundle:Upload:local.html.twig', $this->parameters);
                }

                if (empty($_FILES)) {
                    echo "<script>alert('请选择文件！');</script>";
                    return $this->render1('CoreBundle:Upload:local.html.twig', $this->parameters);
                }

                $photo = $this->get('request')->files->get('photo');

                $type = explode("/", $_FILES['photo']['type'][0]);

                switch ($type[0]) {
                    case 'image':
                        if ($_FILES['photo']['size'][0] > ($this->get('core.common')->C('up_image_size') * 1024 * 1024)) {
                            $this->parameters['status'] = 0;
                            $this->parameters['info']   = '图片文件超出限制大小！';
                            return $this->render1('CoreBundle:Upload:local.html.twig', $this->parameters);
                        }
                        break;
                    case 'video':
                        if ($_FILES['photo']['size'][0] > $this->get('core.common')->C('up_file_size') * 1024 * 1024) {
                            $this->parameters['status'] = 0;
                            $this->parameters['info']   = '视频文件超出限制大小！';
                            return $this->render1('CoreBundle:Upload:local.html.twig', $this->parameters);
                        }
                        break;
                }

                //获取远程上传配置信息
                $option                         = array();
                $option['up_remote_domainname'] = $this->get('core.common')->C('up_remote_domainname');
                $option['up_remote_url']        = $this->get('core.common')->C('up_remote_url');
                $option['up_remote_token']      = $this->get('core.common')->C('up_remote_token');

                if (!empty($photo[0])) {
                    $this->parameters               = $this->get('db.files')->remoteUpload($option, $this->token, $this->get('core.common')->getUserDb());
                    $this->parameters["remote_msg"] = $option['up_remote_domainname'] . "/" . $this->parameters["msg"];
                } else {
                    $this->parameters['status'] = 0;
                    $this->parameters['info']   = '请选择文件！';
                }
            }

            return $this->render1('CoreBundle:Upload:local.html.twig', $this->parameters);
        }
    }

    /**
     * 素材库
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function materiallibraryAction(Request $request)
    {
        return $this->zhenMateriallibrary($request);

        $show         = "";
        $map          = array();
        $map['token'] = $this->token ? $this->token : parent::getUser()->getUsername();
        $p            = (int)$this->get('request')->get('p');
        $type         = trim($this->get('request')->get('type'));
        $type         = $type ? $type : 'icon';

        $pageIndex = $p > 0 ? $p : 1;
        $pageSize  = (int)$this->get('request')->get('pageSize');
        $pageSize  = $pageSize > 0 ? $pageSize : 5;

        $this->parameters['color']  = "";
        $this->parameters['folder'] = "";

        if ($type != 'my') {
            $i           = 0;
            $folders     = array();
            $attachments = $this->get('core.materiallibrary')->files();
            $folder      = $this->get('request')->get('folder', '');

            foreach ($attachments[$type] as $k => $a) {
                array_push($folders, array(
                    'name'   => $a['name'],
                    'folder' => $k
                ));

                if ($i == 0 && !$folder)
                    $folder = $k;

                $i++;
            }

            $files  = $attachments[$type][$folder]['files'];
            $height = isset($attachments[$type][$folder]['height']) ? $attachments[$type][$folder]['height'] : '25';

            // page
            $parMap              = array();
            $parMap['varPage']   = 'p';
            $parMap['totalRows'] = count($files);
            $parMap['pageSize']  = 32;
            $parMap['pageIndex'] = $pageIndex;
            $Page                = $this->get('core.page');
            $Page->setParam($parMap);
            $files = array_slice($files, $Page->firstRow, $Page->listRows);
            $show  = $Page->show();

            $map          = array();
            $map['ename'] = 'mwebset';
            $mconfig      = $this->get('db.mconfig')->getData($map);

            $this->parameters['folderArr'] = $this->get('core.materiallibrary')->getFolderArr();
            $this->parameters['color']     = $this->get('core.materiallibrary')->getColor();
            $this->parameters['files']     = $files;
            $this->parameters['height']    = $height;
            $this->parameters['folder']    = $folder;
            $this->parameters['folders']   = $folders;
            $this->parameters['siteUrl']   = isset($mconfig['hostname']['value']) ? $mconfig['hostname']['value'] : '';
        } else {
            $list = $this->get('db.files')->findBy($map, array('id' => 'DESC'), $pageSize, $pageIndex);

            // page
            $parMap              = array();
            $parMap['varPage']   = 'p';
            $parMap['totalRows'] = isset($list['pageCount']) ? $list['pageCount'] : 0;
            $parMap['pageSize']  = $pageSize;
            $parMap['pageIndex'] = $pageIndex;
            $Page                = $this->get('core.page');
            $Page->setParam($parMap);
            $show = $Page->show();

            $this->parameters['list'] = $list;
        }

        $this->parameters['type'] = $type;
        $this->parameters['show'] = $show;
        return $this->render1('CoreBundle:Upload:material.html.twig', $this->parameters);

        if (!isset($_GET['from']))
            return $this->render1('CoreBundle:Upload:material.html.twig', $this->parameters);
        else
            return $this->render1('WapBundle:Upload:material.html.twig', $this->parameters);
    }

    public function zhenMateriallibrary(Request $request)
    {
        $show         = "";
        $map          = array();
        $map['token'] = $this->token ? $this->token : parent::getUser()->getUsername();
        $p            = (int)$this->get('request')->get('p');
        $type         = trim($this->get('request')->get('type'));

        $houseId = trim($this->get('request')->get('house_id'));
        $type    = $type ? $type : 'icon';

        $pageIndex = $p > 0 ? $p : 1;
        $pageSize  = (int)$this->get('request')->get('pageSize');
        $pageSize  = $pageSize > 0 ? $pageSize : 5;

        $this->parameters['color']  = "";
        $this->parameters['folder'] = "";

        switch ($type) {
            case 'house_inter_album':
                $map['aid']     = $houseId ?: 0;
                $map['checked'] = 1;
                $albumList      = $this->get('house.inter_album')->findBy($map, array('id' => 'DESC'), $pageSize, $pageIndex);

            case 'my':
                $list = isset($albumList) ? $albumList : $this->get('db.files')->findBy($map, array('id' => 'DESC'), $pageSize, $pageIndex);

                // page
                $parMap              = array();
                $parMap['varPage']   = 'p';
                $parMap['totalRows'] = isset($list['pageCount']) ? $list['pageCount'] : 0;
                $parMap['pageSize']  = $pageSize;
                $parMap['pageIndex'] = $pageIndex;
                $Page                = $this->get('core.page');
                $Page->setParam($parMap);
                $show = $Page->show();

                $this->parameters['list'] = $list;
                break;
            default:
                $i           = 0;
                $folders     = array();
                $attachments = $this->get('core.materiallibrary')->files();
                $folder      = $this->get('request')->get('folder', '');

                foreach ($attachments[$type] as $k => $a) {
                    array_push($folders, array(
                        'name'   => $a['name'],
                        'folder' => $k
                    ));

                    if ($i == 0 && !$folder)
                        $folder = $k;

                    $i++;
                }

                $files  = $attachments[$type][$folder]['files'];
                $height = isset($attachments[$type][$folder]['height']) ? $attachments[$type][$folder]['height'] : '25';

                // page
                $parMap              = array();
                $parMap['varPage']   = 'p';
                $parMap['totalRows'] = count($files);
                $parMap['pageSize']  = 32;
                $parMap['pageIndex'] = $pageIndex;
                $Page                = $this->get('core.page');
                $Page->setParam($parMap);
                $files = array_slice($files, $Page->firstRow, $Page->listRows);
                $show  = $Page->show();

                $map          = array();
                $map['ename'] = 'mwebset';
                $mconfig      = $this->get('db.mconfig')->getData($map);

                $this->parameters['folderArr'] = $this->get('core.materiallibrary')->getFolderArr();
                $this->parameters['color']     = $this->get('core.materiallibrary')->getColor();
                $this->parameters['files']     = $files;
                $this->parameters['height']    = $height;
                $this->parameters['folder']    = $folder;
                $this->parameters['folders']   = $folders;
                $this->parameters['siteUrl']   = isset($mconfig['hostname']['value']) ? $mconfig['hostname']['value'] : '';
        }

        $this->parameters['type']    = $type;
        $this->parameters['show']    = $show;
        $this->parameters['houseId'] = $houseId;

        return $this->render1('CoreBundle:Upload:material.html.twig', $this->parameters);

        if (!isset($_GET['from']))
            return $this->render1('CoreBundle:Upload:material.html.twig', $this->parameters);
        else
            return $this->render1('WapBundle:Upload:material.html.twig', $this->parameters);
    }

    /**
     * 删除素材库
     */
    public function deletematerialAction()
    {
        $id = (int)$this->get('request')->get('id');
        if ($id <= 0) return $this->error('该图片不存在或已被删除');

        //判断是否已登入，未登入不允许上传
        if (!is_object(parent::getUser()))
            return $this->error("非法操作");

        $token = $this->token ? $this->token : parent::getUser()->getUsername();

        $this->get('db.files')->deletematerial($id, $token, $this->get('core.common')->getUserDb());

        return $this->success('删除成功');
    }

    /**
     * 选取百度地图
     */
    public function baidumapAction()
    {
        $pars = array();
        //$api = $gets->get('api');
        $pars['act'] = $this->get('request')->get('act', '');

        if (!in_array($pars['act'], array('pick', 'show')))
            $pars['act'] = 'pick';

        $pars['frmid'] = $this->get('request')->get('frmid', '');
        $pars['title'] = $this->get('request')->get('title', '');
        $pars['point'] = $this->get('request')->get('point', '');

        $pars['pa'] = explode(',', "{$pars['point']},,");

        if (empty($pars['pa'][0]))
            $pars['pa'][0] = 116.4040;
        if (empty($pars['pa'][1]))
            $pars['pa'][1] = 39.9151;

        $pars['zoom']  = empty($pars['pa'][2]) ? 12 : $pars['pa'][2];
        $pars['width'] = 6 + strlen($pars['point']) * 1.5;
        $pars['width'] < 36 && $pars['width'] = 36;
        $pars['point']    = "{$pars['pa'][0]},{$pars['pa'][1]}";
        $this->parameters = $pars;
        //dump($this->parameters);
        return $this->render1('CoreBundle:Dispatch:pickbaidumap.html.twig', $this->parameters);
    }

    /**
     * 获取菜单
     * @param int $menuid
     * @return array
     */
    public function getMenus($menuid = null)
    {
        //判断是否登陆
        if (!parent::getUser())
            return array();

        //判断是否为数字，非数字则是加密过的ID
        if (!empty($menuid) && !is_numeric($menuid))
            $menuid = $this->get('core.common')->decode($menuid);

        $pid = $menuid > 0 ? $menuid : 0;

        return $this->get('db.menus')->getMenuList($pid);
    }

    /*
    public function checkqqAction(Request $request)
    {
        $connect = $this->container->getParameter('hwi_oauth.connect.confirmation');

        $hasUser = $this->isGranted('IS_AUTHENTICATED_REMEMBERED');

        $error = $this->getErrorForRequest($request);

        // if connecting is enabled and there is no user, redirect to the registration form
        if ($connect
            && !$hasUser
            && $error instanceof AccountNotLinkedException
        ) {
            $key = time();
            $session = $request->getSession();
            $session->set('hwi_oauth.registration_error.'.$key, $error);

            return $this->redirectToRoute('hwi_oauth_connect_registration', array('key' => $key));
        }

        if ($error)
            $error = $error->getMessage();

        return self::render($this->getBundleName(), array(
            'error'   => $error,
        ));
    }
    */
    /**
     * ajax通用方法
     */
    public function getjsonAction()
    {
        $pid  = $this->get('request')->get('pid', 0);
        $csrf = $this->get('request')->get('csrf', '');
        $csrf = $csrf ? $this->get('core.common')->decode($csrf) : "";
        $csrf = $csrf ? explode('|', $csrf) : array();
        if (count($csrf) >= 2 && (time() - $csrf[1] - 18000) < 0) {
            $map                                                         = array();
            $map[isset($csrf[2]) && $csrf[2] ? $csrf[2] : 'pid']['find'] = $pid;
            $info                                                        = $this->get($csrf[0])->findBy($map);

            return $this->showMessage('ok', 1, $info);
        }

        return $this->error('错误参数');
    }

    /**
     * 重写get方法
     */
    public function get($id)
    {
        /**
         * 兼容3.0之前的版本request服务
         */
        if ($id == 'request')
            return parent::get('request_stack')->getCurrentRequest();

        if (!$this->container->has($id))
            throw new \LogicException('The ' . $id . ' is not registered in your application.', 404);

        return parent::get($id);
    }

    /**
     * 魔术方法 有不存在的操作的时候执行
     * @access public
     * @param string $method 方法名
     * @param array $args 参数
     * @return mixed
     */
    public function __call($method, $args)
    {
        if (method_exists($this, '_empty')) {
            // 如果定义了_empty操作 则调用
            $this->_empty($method, $args);
        } else {
            switch (strtolower($method)) {
                // 判断提交方式
                case 'ispost'   :
                case 'isget'    :
                case 'ishead'   :
                case 'isdelete' :
                case 'isput'    :
                    return strtolower($_SERVER['REQUEST_METHOD']) == strtolower(substr($method, 2));
                // 获取变量 支持过滤和默认值 调用方式 $this->_post($key,$filter,$default);
                case '_get'     :
                    $input =& $_GET;
                    break;
                case '_post'    :
                    $input =& $_POST;
                    break;
                case '_put'     :
                    parse_str(file_get_contents('php://input'), $input);
                    break;
                case '_param'   :
                    switch ($_SERVER['REQUEST_METHOD']) {
                        case 'POST':
                            $input = $_POST;
                            break;
                        case 'PUT':
                            parse_str(file_get_contents('php://input'), $input);
                            break;
                        default:
                            $input = $_GET;
                    }
                    break;
                case '_request' :
                    $input =& $_REQUEST;
                    break;
                case '_session' :
                    $input =& $_SESSION;
                    break;
                case '_cookie'  :
                    $input =& $_COOKIE;
                    break;
                case '_server'  :
                    $input =& $_SERVER;
                    break;
                case '_globals' :
                    $input =& $GLOBALS;
                    break;
                default:
                    throw new \InvalidArgumentException(__CLASS__ . ':' . $method . '_METHOD_NOT_EXIST_', 404);
            }

            // 由VAR_FILTERS配置进行过滤
            if (!isset($args[0]))
                return $input;

            if (isset($input[$args[0]])) {
                $data    = $input[$args[0]];
                $filters = isset($args[1]) ? $args[1] : '';
                if ($filters) {
                    $filters = explode(',', $filters);
                    foreach ($filters as $filter) {
                        // 参数过滤
                        if (!function_exists($filter))
                            continue;

                        $data = is_array($data) ? array_map($filter, $data) : $filter($data);
                    }
                }

                return $data;
            }

            return isset($args[2]) ? $args[2] : NULL;
        }
    }

    /**
     * 投票
     */
    public function voteAction()
    {
        $model = trim($this->get('request')->get('models', ''));
        $field = $this->get('request')->get('field', '');

        //获取权限配置
        $modelslimit = $this->container->getParameter('modelfields');
        if (isset($modelslimit[$model])) {
            $fields = array_flip($modelslimit[$model]);

            //无权限配置时
            if (!isset($fields[$field]))
                throw new \LogicException('无操作权限!');
        } else {
            //无默认权限配置时
            if (!in_array($field, array('vote', 'votes', 'hdnum')))
                throw new \LogicException('无操作权限!');
        }

        $modelInfo = $this->get('db.models')->getData($model, 'name');

        if ($modelInfo && $field) {
            $ids = $this->get('request')->get('id', '');
            $ids = $ids ? explode(',', $ids) : array();
            $msg = array();

            foreach ($ids as $id) {
                $info = $this->get($modelInfo['service'])->findOneBy(array('id' => $id), array(), false);

                if (!is_object($info))
                    continue;

                $field = $this->get('core.common')->ucWords($field);

                if (method_exists($info, "get{$field}"))
                    $info->{"set" . $field}($info->{"get" . $field}() + 1);

                $msg[] = $this->get($modelInfo['service'])->update($id, array(), $info, false);
            }

            return $this->success('操作成功', '', true, $msg);
        }
        return $this->error('操作失败');
    }

    public function getuserinfoAction()
    {
        $user = parent::getUser();

        //未登录
        $ret = array("is_login" => 0);

        if (is_object($user)) {
            $ret = array(
                "is_login" => 1, //已登录，返回登录的用户信息
                "user"     => array(
                    "user_id"     => $user->getId(),
                    "nickname"    => $user->getUsername(),
                    "img_url"     => $this->getRequest()->getUriForPath("/" . $user->getImage()),
                    "profile_url" => "",
                    "sign"        => "a1gfwe67o&843ghlnkj" //注意这里的sign签名验证已弃用，任意赋值即可
                ));
        }

        echo (isset($_GET['callback']) ? $_GET['callback'] : 'echo') . '(' . json_encode($ret) . ')';
        die();
    }

    /**
     * 极验验证码
     * 初始化
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function geetestAction()
    {
        $this->get('request')->getSession()->set('gtserver', $this->get('core.geetest')->pre_process());
        return new Response($this->get('core.geetest')->get_response_str());
    }

    /**
     * 设置缓存
     */
    protected function setCache($response, $public = false)
    {
        //未开启代理缓存
        if (!$this->container->has('cache'))
            return $response;

        //读取缓存配置文件
        $cacheInfo = $this->get('db.cache')->getRule(array('status' => 1, '_multi' => false));

        if (isset($cacheInfo['data'])) {
            foreach ($cacheInfo['data'] as $cache) {
                $path   = is_object($cache) ? $cache->getPath() : $cache['path'];
                $maxAge = is_object($cache) ? (int)$cache->getDate() * 3600 : (int)$cache['date'] * 3600;
                $public = is_object($cache) ? (bool)$cache->getPublic() : (bool)$cache['public'];
                if (empty($path))
                    continue;

                $PathInfo = rawurldecode($this->get('request')->getPathInfo());
                $QueryStr = rawurldecode(http_build_query($this->get('request')->query->all()));
                $PathInfo .= ($QueryStr ? '?' : '') . $QueryStr;

                if (!preg_match('{' . $path . '}', $PathInfo))
                    continue;

                $response->setMaxAge($maxAge);
                $response->setSharedMaxAge($maxAge);

                //自定义的头用小写'Accept-Encoding',
                $response->setVary(array('x-user-language', 'x-user-sessionarea', 'x-user-ismobile', 'X-Requested-With',));
                $response->headers->set('x-user-language', $this->get('request')->getLocale());

                $response->headers->set('x-user-sessionarea', $this->get('core.area')->getArea());
                $response->headers->set('x-user-ismobile', $this->get('core.common')->isMobileClient() ? 1 : 0);
                $response->setETag(md5($response->getContent()));

                // 设置一个自定义的Cache-Control 指令,no-cache=>客户端不生成缓存，只在服务端生成缓存
                $response->headers->addCacheControlDirective('no-cache', true);

                if ($public)
                    $response->setPublic();
                else
                    $response->setPrivate();


                $response->isNotModified($this->getRequest());

                return $response;
            }
        }

        return $response;
    }

    /**
     * 语言切换
     */
    public function switchAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $locale = $this->get('request')->get('_locale', '');

            $user = parent::getUser();

            //判断是否已登陆，已登陆则更新到locale字段
            if (is_object($user))
                $this->get('db.users')->update($user->getId(), array('locale' => $locale), null, false);

            unset($user);
            unset($locale);
            return $this->success('语言切换成功');
        }
        return $this->error('参数错误');
    }


    public function __destruct()
    {
        //dump("Initial: ".ceil(memory_get_usage()/1024/1024)." mb");

        //dump("Peak: ".ceil(memory_get_peak_usage()/1024/1024)." mb");
    }
}
