<?php

require_once __DIR__.'/AppKernel.php';

use Symfony\Bundle\FrameworkBundle\HttpCache\HttpCache;
use CoreBundle\Services\HttpCache\Store;


class AppCache extends HttpCache
{
    
    protected $store;
    
    protected function getOptions()
    {
        return array(
            'debug'                  => false,
            'default_ttl'            => 0,
            'private_headers'        => array('Authorization', 'Cookie'),
            'allow_reload'           => false,
            'allow_revalidate'       => false,
            'stale_while_revalidate' => 2,
            'stale_if_error'         => 60,
        );
    }
    
    /**
     * 设置需要进入监听器中处理的vary变量
     */    
    public function getIgnores()
    {
        return array(
        'x-user-language',
        'x-user-sessionarea',
        'x-user-ismobile',
        );
    }
    
    protected function createStore()
    {
        $this->store = new Store($this->cacheDir ?: $this->kernel->getCacheDir().'/http_cache',$this->getIgnores());
        return $this->store;
    }
    
    public function getStore()
    {
        return $this->store;
    }    
}
