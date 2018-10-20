<?php
/**
 * @copyright Copyright (c) 2008 – 2017 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2017年03月13日
 */

namespace HouseBundle\Controller;

/**
 * 前台保存
 * @author house
 */
class InteractionController extends Controller
{


    /**
     * 实现的save方法
     * house
     */
    public function saveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $ids = $this->get('request')->get('id', '');

            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            if ($ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $id) {
                    $info = $this->get('house.houses_arc')->update($id, $data);
                }
            } else
                $info = $this->get('house.houses_arc')->add($data);

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

        if ($ids) {
            $ids = explode(',', $ids);
            foreach ($ids as $id) {
                $this->get('house.houses_arc')->delete($id);
            }
        }
        return $this->success('操作成功');
    }


    /**
     * 团购报名
     * house
     */
    public function groupsaveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $ids = $this->get('request')->get('id', '');

            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            if ($ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $id) {
                    $info = $this->get('house.group_order')->update($id, $data);
                }
            } else
                $info = $this->get('house.group_order')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }

    /**
     * 看房团报名
     * house
     */
    public function kftsaveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $ids = $this->get('request')->get('id', '');

            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            if ($ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $id) {
                    $info = $this->get('house.showingsnews')->update($id, $data);
                }
            } else
                $info = $this->get('house.showingsnews')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }

    /**
     * 活动报名
     * house
     */
    public function promotionsaveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $ids = $this->get('request')->get('id', '');

            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            if ($ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $id) {
                    $info = $this->get('house.promotion_order')->update($id, $data);
                }
            } else
                $info = $this->get('house.promotion_order')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }

    /**
     * 二手房及出租的意向
     * house
     */
    public function salesaveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $ids = $this->get('request')->get('id', '');

            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            if ($ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $id) {
                    $info = $this->get('house.inter_intention')->update($id, $data);
                }
            } else
                $info = $this->get('house.inter_intention')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }

    /**
     * 二手房及出租的举报
     * house
     */
    public function reportsaveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $ids = $this->get('request')->get('id', '');

            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            if ($ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $id) {
                    $info = $this->get('house.inter_report')->update($id, $data);
                }
            } else
                $info = $this->get('house.inter_report')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }

    /**
     * 分销
     * house
     */
    public function fxearnsaveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $ids = $this->get('request')->get('id', '');

            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            if ($ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $id) {
                    $info = $this->get('house.recommend')->update($id, $data);
                }
            } else
                $info = $this->get('house.recommend')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }

    /**
     * 前台评论
     * house
     */
    public function commentsaveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $ids = $this->get('request')->get('id', '');

            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            if ($ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $id) {
                    $info = $this->get('house.inter_comment')->update($id, $data);
                }
            } else
                $info = $this->get('house.inter_comment')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }

    /**
     * 问答举报
     * house
     */
    public function askreportsaveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $ids = $this->get('request')->get('id', '');

            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            if ($ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $id) {
                    $info = $this->get('house.ask_report')->update($id, $data);
                }
            } else
                $info = $this->get('house.ask_report')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }

    /**
     * 问答
     * house
     */
    public function asksaveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $ids = $this->get('request')->get('id', '');

            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            if ($ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $id) {
                    $info = $this->get('house.ask')->update($id, $data);
                }
            } else
                $info = $this->get('house.ask')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }

    /**
     * 问答回答
     * house
     */
    public function answersaveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $ids = $this->get('request')->get('id', '');

            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            if ($ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $id) {
                    $info = $this->get('house.answer')->update($id, $data);
                }
            } else
                $info = $this->get('house.answer')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }

    /**
     * 楼盘评论
     * house
     */
    public function lpcommentsaveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $ids = $this->get('request')->get('id', '');

            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            if ($ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $id) {
                    $info = $this->get('house.inter_housescomment')->update($id, $data);
                }
            } else
                $info = $this->get('house.inter_housescomment')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }

    /**
     * 服务提问
     * house
     */
    public function askservicesaveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $ids = $this->get('request')->get('id', '');

            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            if ($ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $id) {
                    $info = $this->get('house.webtw')->update($id, $data);
                }
            } else
                $info = $this->get('house.webtw')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }

    /**
     * 店铺留言
     * house
     */
    public function lysaveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $ids = $this->get('request')->get('id', '');

            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            if ($ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $id) {
                    $info = $this->get('house.usermsg')->update($id, $data);
                }
            } else
                $info = $this->get('house.usermsg')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }

    /**
     * 红包
     * house
     */
    public function hongbaosaveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $ids = $this->get('request')->get('id', '');

            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            if ($ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $id) {
                    $info = $this->get('house.redenroll')->update($id, $data);
                }
            } else
                $info = $this->get('house.redenroll')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }

    /**
     * 印象
     * house
     */
    public function yinxiangsaveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $ids = $this->get('request')->get('id', '');

            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            if ($ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $id) {
                    $info = $this->get('house.inter_impress')->update($id, $data);
                }
            } else
                $info = $this->get('house.inter_impress')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }

    /**
     * 意向
     * house
     */
    public function yixiangsaveAction()
    {
        if ($this->get('request')->getMethod() == "POST") {
            $ids = $this->get('request')->get('id', '');

            $data = $this->get('request')->request->all();

            unset($data['csrf_token']);

            if ($ids) {
                $ids = explode(',', $ids);
                foreach ($ids as $id) {
                    $info = $this->get('house.inter_housesintention')->update($id, $data);
                }
            } else
                $info = $this->get('house.inter_housesintention')->add($data);

            return $this->success('操作成功', '', false, array('id' => $info->getId()));
        }

        return $this->error('操作失败');
    }

}