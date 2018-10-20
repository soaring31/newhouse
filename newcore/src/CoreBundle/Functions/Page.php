<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2015年12月2日
*/
namespace CoreBundle\Functions;

use CoreBundle\Services\ServiceBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * 分页类
 *
 */
class Page extends ServiceBase
{
// 分页栏每页显示的页数
    public $rollPage = 5;
    // 页数跳转时要带的参数
    public $parameter;
    // 分页URL地址
    public $url = '';
    // 默认列表每页显示行数
    public $listRows = 20;
    // 起始行数
    public $firstRow;
    // 分页总页面数
    protected $totalPages;
    // 总行数
    protected $totalRows;
    // 当前页数
    protected $nowPage;
    // 分页的栏的总页数
    protected $coolPages;
    // 分页显示定制
    protected $config = array('header'=>'条记录'
        ,'prev'=>'上一页'
        ,'next'=>'下一页'
        ,'first'=>'第一页'
        ,'last'=>'最后一页'
        ,'theme'=>' %totalRow% %header% %nowPage%/%totalPage% 页 %upPage% %downPage% %first%  %prePage%  %linkPage%  %nextPage% %end%');
    // 默认分页变量名
    protected $varPage;

    protected $container;

    /**
     * 架构函数
     * @access public
     * @param array $totalRows
     * @param array $listRows
     * @param array $parameter
     */
    public function __construct(ContainerInterface $container)
    {
        $this->url = '';

        //总的记录数
        $this->totalRows = 1;

        //分页跳转的参数
        $this->parameter = '';

        //页数标签
        $this->varPage = 'p' ;

        //每页显示记录数
        $this->listRows = 20;

        //窗口服务指针
        $this->container = $container;

        //总页数
        $this->totalPages = ceil($this->totalRows/$this->listRows);

        $this->coolPages = ceil($this->totalPages/$this->rollPage);

        //当前页
        $this->nowPage = 1;

        if($this->nowPage<1)
            $this->nowPage = 1;
        elseif(!empty($this->totalPages) && $this->nowPage>$this->totalPages)
            $this->nowPage = $this->totalPages;

        $this->firstRow = $this->listRows*($this->nowPage-1);
    }

    public function setParam($arr)
    {
        //页数标签
        $this->varPage = isset($arr['varPage'])?$arr['varPage']:$this->varPage;

        //总的记录数
        $this->totalRows = isset($arr['totalRows'])?$arr['totalRows']:$this->totalRows;

        //分页跳转的参数
        $this->parameter = isset($arr['parameter'])?$arr['parameter']:$this->parameter;

        //每页显示记录数
        $this->listRows = isset($arr['pageSize'])?$arr['pageSize']:$this->listRows;

        //当前页
        $this->nowPage = isset($arr['pageIndex'])?$arr['pageIndex']:$this->nowPage;

        //总页数
        $this->totalPages = ceil($this->totalRows/$this->listRows);
        $this->coolPages = ceil($this->totalPages/$this->rollPage);

        if($this->nowPage<1)
            $this->nowPage = 1;
        elseif(!empty($this->totalPages) && $this->nowPage>$this->totalPages)
            $this->nowPage = $this->totalPages;

        $this->firstRow = $this->listRows*($this->nowPage-1);
    }

    public function setConfig($name,$value)
    {
        if(isset($this->config[$name]))
            $this->config[$name] = $value;
    }

    /**
     * 分页显示输出
     * @access public
     */
    public function show()
    {
        if(0 == $this->totalRows)
            return '';
        $p = $this->varPage;

        $nowCoolPage = ceil($this->nowPage/$this->rollPage);

        // 分析分页参数
        if($this->url){
            $depr = '/';
            $url = rtrim($this->get('core.common')->U('/'.$this->url),$depr).$depr.'__PAGE__';
        }else{
            $parameter = array();
            if($this->parameter && is_string($this->parameter)) {
                parse_str($this->parameter,$parameter);
            }elseif(is_array($this->parameter)&&$this->parameter){
                $parameter = $this->parameter;
            }else{
                $var = !empty($_POST)?$_POST:$_GET;
                if(empty($var))
                    $parameter = array();
                else
                    $parameter = $var;
            }
            $parameter[$p] = '__PAGE__';
            
            try {
                //匹配路由
                $router = $this->get('router')->match($this->get('request')->getPathInfo());
                
                //查询默认路由参数
                $attributes = $this->get('request')->attributes->all();
                
                if(!empty($attributes['_route_params']))
                    $parameter = array_merge($attributes['_route_params'],$parameter);

                $url = $this->get('router')->generate($router['_route'],$parameter);

            }catch (\Exception $e) {
                $url = $this->get('core.common')->U('',$parameter);
            }          
        }
        //上下翻页字符串
        $upRow = $this->nowPage-1;
        $downRow = $this->nowPage+1;
        $upPage = '';
        
        $patterns = array("?".$this->varPage.'=__PAGE__',"&".$this->varPage.'=__PAGE__',$this->varPage."=__PAGE__", "/__PAGE__");
        
        if ($upRow>0)
        {
            if($upRow==1)
                $upPage = "<a data-name=\"pageIndex\" data-value='1' class='prev' href='".str_replace($patterns,'',$url)."'>".$this->config['prev']."</a>";
            else
                $upPage = "<a data-name=\"pageIndex\" data-value='".$upRow."' class='prev' href='".str_replace('__PAGE__',$upRow,$url)."'>".$this->config['prev']."</a>";
        }

        if ($downRow <= $this->totalPages)
            $downPage = "<a data-name=\"pageIndex\" data-value='".$downRow."' class='next' href='".str_replace('__PAGE__',$downRow,$url)."'>".$this->config['next']."</a>";
        else
            $downPage = '';

        if($nowCoolPage == 1)
        {
            $theFirst = '';
            $prePage = '';
        }else{
            $preRow = $this->nowPage-$this->rollPage;
            $prePage = "<a data-name=\"pageIndex\" data-value='".$preRow."' class='prev1' href='".str_replace('__PAGE__',$preRow,$url)."' >上".$this->rollPage."页</a>";
            $theFirst = "<a data-name=\"pageIndex\" data-value='1' class='first' href='".str_replace($patterns,'',$url)."' >".$this->config['first']."</a>";
        }
        if($nowCoolPage == $this->coolPages)
        {
            $nextPage = '';
            $theEnd = '';
        }else{
            $nextRow = ceil($this->nowPage/$this->rollPage)*$this->rollPage+1;
            $theEndRow = $this->totalPages;
            $nextPage = "<a data-name=\"pageIndex\" data-value='".$nextRow."' class='next1' href='".str_replace('__PAGE__',$nextRow,$url)."' >下".$this->rollPage."页</a>";
            $theEnd = "<a data-name=\"pageIndex\" data-value='".$theEndRow."' class='last' href='".str_replace('__PAGE__',$theEndRow,$url)."' >".$this->config['last']."</a>";
        }

        // 1 2 3 4 5
        $linkPage = "";
        for($i=1;$i<=$this->rollPage;$i++)
        {
            $page = ($nowCoolPage-1)*$this->rollPage+$i;
            if($page!=$this->nowPage)
            {
                if($page<=$this->totalPages){
                    if($i==1&&$page<=1)
                    {
                        $linkPage .= "<a data-name=\"pageIndex\" data-value='1' class='item' href='".str_replace($patterns,'',$url)."'>".$page."</a>";
                    }else
                        $linkPage .= "<a data-name=\"pageIndex\" data-value='".$page."' class='item' href='".str_replace('__PAGE__',$page,$url)."'>".$page."</a>";
                }else{
                    break;
                }
            }else{
                if($this->totalPages != 1)
                    $linkPage .= "<span class='item current'>".$page."</span>";
            }
        }

        $pageStr = str_replace(
            array('%header%','%nowPage%','%totalRow%','%totalPage%','%upPage%','%downPage%','%first%','%prePage%','%linkPage%','%nextPage%','%end%'),
            array($this->config['header'],$this->nowPage,$this->totalRows,$this->totalPages,$upPage,$downPage,$theFirst,$prePage,$linkPage,$nextPage,$theEnd),$this->config['theme']);
        return $pageStr;
    }

    /**
     * 数组分页函数  核心函数  array_slice
     * 用此函数之前要先将数据库里面的所有数据按一定的顺序查询出来存入数组中
     * $array   查询出来的所有数组
     */
    public function data($array)
    {
        //计算每次分页的开始位置
		$start = ($this->nowPage-1)*$this->listRows;
		$pagedata = array_slice($array, $start, $this->listRows);
		return $pagedata;  #返回查询数据
    }
}