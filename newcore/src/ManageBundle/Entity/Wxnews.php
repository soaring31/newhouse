<?php

namespace ManageBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Wxnews
 *
 * @ORM\Table(name="wxnews")
 * @ORM\Entity
 */
class Wxnews
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=64, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="thumb_media_id", type="string", length=128, nullable=false)
     */
    private $thumb_media_id;

    /**
     * @var string
     *
     * @ORM\Column(name="author", type="string", length=64, nullable=false)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="digest", type="string", length=255, nullable=false)
     */
    private $digest;

    /**
     * @var boolean
     *
     * @ORM\Column(name="show_cover_pic", type="boolean", length=1, nullable=false)
     */
    private $show_cover_pic = '1';

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=0, nullable=false)
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="content_source_url", type="string", length=200, nullable=false)
     */
    private $content_source_url;

    /**
     * @var string
     *
     * @ORM\Column(name="media_id", type="string", length=200, nullable=false)
     */
    private $media_id;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=200, nullable=false)
     */
    private $token;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_sync", type="boolean", length=1, nullable=false)
     */
    private $is_sync;

    /**
     * @var integer
     *
     * @ORM\Column(name="manyindex", type="smallint", length=2, nullable=false)
     */
    private $manyindex;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=200, nullable=false)
     */
    private $url;

    /**
     * @var integer
     *
     * @ORM\Column(name="authtest", type="integer", length=10, nullable=false)
     */
    private $authtest;

    /**
     * @var boolean
     *
     * @ORM\Column(name="checked", type="boolean", nullable=false)
     */
    private $checked;

    /**
     * @var string
     *
     * @ORM\Column(name="attributes", type="string", length=10, nullable=false)
     */
    private $attributes;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", length=5, nullable=false)
     */
    private $sort;

    /**
     * @var boolean
     *
     * @ORM\Column(name="issystem", type="boolean", nullable=false)
     */
    private $issystem;

    /**
     * @var string
     *
     * @ORM\Column(name="identifier", type="string", length=100, nullable=false)
     */
    private $identifier;

    /**
     * @var integer
     *
     * @ORM\Column(name="create_time", type="integer", length=10, nullable=false)
     */
    private $create_time;

    /**
     * @var integer
     *
     * @ORM\Column(name="update_time", type="integer", length=10, nullable=false)
     */
    private $update_time;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_delete", type="boolean", length=1, nullable=false)
     */
    private $is_delete;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return Wxnews
     */
    public function setTitle($title)
    {
        $this->title = $title;
    
        return $this;
    }

    /**
     * Get title
     *
     * @return string 
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set thumb_media_id
     *
     * @param string $thumbMediaId
     * @return Wxnews
     */
    public function setThumbMediaId($thumbMediaId)
    {
        $this->thumb_media_id = $thumbMediaId;
    
        return $this;
    }

    /**
     * Get thumb_media_id
     *
     * @return string 
     */
    public function getThumbMediaId()
    {
        return $this->thumb_media_id;
    }

    /**
     * Set author
     *
     * @param string $author
     * @return Wxnews
     */
    public function setAuthor($author)
    {
        $this->author = $author;
    
        return $this;
    }

    /**
     * Get author
     *
     * @return string 
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Set digest
     *
     * @param string $digest
     * @return Wxnews
     */
    public function setDigest($digest)
    {
        $this->digest = $digest;
    
        return $this;
    }

    /**
     * Get digest
     *
     * @return string 
     */
    public function getDigest()
    {
        return $this->digest;
    }

    /**
     * Set show_cover_pic
     *
     * @param boolean $showCoverPic
     * @return Wxnews
     */
    public function setShowCoverPic($showCoverPic)
    {
        $this->show_cover_pic = $showCoverPic;
    
        return $this;
    }

    /**
     * Get show_cover_pic
     *
     * @return boolean 
     */
    public function getShowCoverPic()
    {
        return $this->show_cover_pic;
    }

    /**
     * Set content
     *
     * @param string $content
     * @return Wxnews
     */
    public function setContent($content)
    {
        $this->content = $content;
    
        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set content_source_url
     *
     * @param string $contentSourceUrl
     * @return Wxnews
     */
    public function setContentSourceUrl($contentSourceUrl)
    {
        $this->content_source_url = $contentSourceUrl;
    
        return $this;
    }

    /**
     * Get content_source_url
     *
     * @return string 
     */
    public function getContentSourceUrl()
    {
        return $this->content_source_url;
    }

    /**
     * Set media_id
     *
     * @param string $mediaId
     * @return Wxnews
     */
    public function setMediaId($mediaId)
    {
        $this->media_id = $mediaId;
    
        return $this;
    }

    /**
     * Get media_id
     *
     * @return string 
     */
    public function getMediaId()
    {
        return $this->media_id;
    }

    /**
     * Set token
     *
     * @param string $token
     * @return Wxnews
     */
    public function setToken($token)
    {
        $this->token = $token;
    
        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set is_sync
     *
     * @param boolean $isSync
     * @return Wxnews
     */
    public function setIsSync($isSync)
    {
        $this->is_sync = $isSync;
    
        return $this;
    }

    /**
     * Get is_sync
     *
     * @return boolean 
     */
    public function getIsSync()
    {
        return $this->is_sync;
    }

    /**
     * Set manyindex
     *
     * @param integer $manyindex
     * @return Wxnews
     */
    public function setManyindex($manyindex)
    {
        $this->manyindex = $manyindex;
    
        return $this;
    }

    /**
     * Get manyindex
     *
     * @return integer 
     */
    public function getManyindex()
    {
        return $this->manyindex;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Wxnews
     */
    public function setUrl($url)
    {
        $this->url = $url;
    
        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set authtest
     *
     * @param integer $authtest
     * @return Wxnews
     */
    public function setAuthtest($authtest)
    {
        $this->authtest = $authtest;
    
        return $this;
    }

    /**
     * Get authtest
     *
     * @return integer 
     */
    public function getAuthtest()
    {
        return $this->authtest;
    }

    /**
     * Set checked
     *
     * @param boolean $checked
     * @return Wxnews
     */
    public function setChecked($checked)
    {
        $this->checked = $checked;
    
        return $this;
    }

    /**
     * Get checked
     *
     * @return boolean 
     */
    public function getChecked()
    {
        return $this->checked;
    }

    /**
     * Set attributes
     *
     * @param string $attributes
     * @return Wxnews
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;
    
        return $this;
    }

    /**
     * Get attributes
     *
     * @return string 
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     * @return Wxnews
     */
    public function setSort($sort)
    {
        $this->sort = $sort;
    
        return $this;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set issystem
     *
     * @param boolean $issystem
     * @return Wxnews
     */
    public function setIssystem($issystem)
    {
        $this->issystem = $issystem;
    
        return $this;
    }

    /**
     * Get issystem
     *
     * @return boolean 
     */
    public function getIssystem()
    {
        return $this->issystem;
    }

    /**
     * Set identifier
     *
     * @param string $identifier
     * @return Wxnews
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;
    
        return $this;
    }

    /**
     * Get identifier
     *
     * @return string 
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Set create_time
     *
     * @param integer $createTime
     * @return Wxnews
     */
    public function setCreateTime($createTime)
    {
        $this->create_time = $createTime;
    
        return $this;
    }

    /**
     * Get create_time
     *
     * @return integer 
     */
    public function getCreateTime()
    {
        return $this->create_time;
    }

    /**
     * Set update_time
     *
     * @param integer $updateTime
     * @return Wxnews
     */
    public function setUpdateTime($updateTime)
    {
        $this->update_time = $updateTime;
    
        return $this;
    }

    /**
     * Get update_time
     *
     * @return integer 
     */
    public function getUpdateTime()
    {
        return $this->update_time;
    }

    /**
     * Set is_delete
     *
     * @param boolean $isDelete
     * @return Wxnews
     */
    public function setIsDelete($isDelete)
    {
        $this->is_delete = $isDelete;
    
        return $this;
    }

    /**
     * Get is_delete
     *
     * @return boolean 
     */
    public function getIsDelete()
    {
        return $this->is_delete;
    }
}
