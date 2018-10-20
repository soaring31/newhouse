<?php
/**
 * Created by PhpStorm.
 * User: zhangshuzhen
 * Date: 2018/5/9
 * Time: 15:50
 */

namespace HouseBundle\Entity;


class EmptyEntity
{
    private $isEmptyEntity = true;

    /**
     * @return bool
     */
    public function isEmptyEntity()
    {
        return $this->isEmptyEntity;
    }

    /**
     * @param bool $isEmptyEntity
     * @return $this
     */
    public function setIsEmptyEntity($isEmptyEntity)
    {
        $this->isEmptyEntity = $isEmptyEntity;
        return $this;
    }
}