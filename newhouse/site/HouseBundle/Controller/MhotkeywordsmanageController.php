<?php
/**
* @copyright Copyright (c) 2008 – 2016 www.08cms.com
* @author 08cms项目开发团队
* @package 08cms
* create date 2016年11月28日
*/
namespace HouseBundle\Controller;

/**
* 管理关键词
* @author house
*/
class MhotkeywordsmanageController extends Controller
{



    /**
    * 编辑
    * house
    */
    public function showAction()
    {
        
        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
    * 实现的save方法
    * house
    */
    public function saveAction()
    {
        if($this->get('request')->getMethod() == "POST")
        {
            $ids = $this->get('request')->get('id', '');
            $data = $this->get('request')->request->all();
            if($ids)
            {
                $ids = explode(',', $ids);
                foreach($ids as $id)
                {
                    $info = $this->get('db.hotkeywords')->update($id, $data);
                }
            }else
                $info = $this->get('db.hotkeywords')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }
    /**
    * 实现的delete方法
    * house
    */
    public function deleteAction()
    {
        $ids = $this->get('request')->get('id', '');

        if($ids)
        {
            $ids = explode(',', $ids);
            foreach($ids as $id)
            {
                $this->get('db.hotkeywords')->delete($id);
            }
        }
        return $this->success('操作成功');
    }

    /**
    * 导入热门关键词
    * house
    */
    public function importAction()
    {

        return $this->render($this->getBundleName(), $this->parameters);
    }

    /**
     * 导入不良词
     * house
     */
    public function leadingAction()
    {
        if($this->get('request')->getMethod() == "POST") {

            if ($_FILES['upload_file0']['error'] == UPLOAD_ERR_OK && $_FILES['upload_file0']['size'] != 0 && !empty($_FILES['upload_file0']['tmp_name'])) {
                is_file($_FILES['upload_file0']['tmp_name']) || die("无法访问文件：{$_FILES['upload_file0']['tmp_name']}");

                if ($_FILES['upload_file0']['type'] != 'text/plain')
                    return $this->error('请导入文本格式', '', true);

                $data = array();
                $condition = array();
                $content = trim(file_get_contents($_FILES['upload_file0']['tmp_name']));
                // 浏览器编码表上的“GB2312”，通常都是指“EUC-CN”表示法。
                $encode = mb_detect_encoding($content, array('ASCII','UTF-8','GB2312','GBK'));
                if (false !== $encode && 'UTF-8' !== $encode)
                    $content = iconv($encode, "UTF-8", $content);
                $hotwords = addslashes($content);
                $hotwords = preg_split('/[\s]+/', $hotwords);

                foreach ($hotwords as $row)
                {
                    $i = explode('=', $row);
                    $condition['keyword'] = $data['keyword'] = $i[0];
                    $data['url'] = $i[1];

                    $result = $this->get('db.hotkeywords')->findBy($condition);

                    if(!$result['data']) {
                        $this->get('db.hotkeywords')->add($data, null, false);
                    }

                }
                return $this->success('操作成功','', true);
            }else{
                return $this->error('请导入文本格式', '', true);
            }
        }
        return $this->error('参数错误', '', true);
    }


    /**
     * 导出不良词
     * house
     */
    public function exportAction()
    {
        Header( "Content-type:   application/octet-stream ");
        Header( "Accept-Ranges:   bytes ");
        header( "Content-Disposition:   attachment;   filename=test.txt ");
        header( "Expires:   0 ");
        header( "Cache-Control:   must-revalidate,   post-check=0,   pre-check=0 ");
        header( "Pragma:   public ");

        //获得不良词
        $result = $this->get('db.hotkeywords')->findBy(array(),null,2000);

        foreach($result['data'] as $value ){

            echo $value->getkeyword().'='.$value->getUrl().PHP_EOL;
        }
        exit;

    }
}