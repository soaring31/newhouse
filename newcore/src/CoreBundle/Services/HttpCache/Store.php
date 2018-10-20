<?php


namespace CoreBundle\Services\HttpCache;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpCache\Store as BaseStore;
/**
 * Store implements all the logic for storing cache metadata (Request and Response headers).
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class Store extends BaseStore
{          
    public function __construct($root,$ignores = array())
    {
        parent::__construct($root);
        $this->ignores = $ignores;
    }
    
    public function getIgnores()
    {
      return $this->ignores;
    }
      
    /**
     * Locates a cached Response for the Request provided.
     *
     * @param Request $request A Request instance
     *
     * @return Response|null A Response instance, or null if no cache entry was found
     */
    public function lookup(Request $request,$ignore = true,$ignorevalues = array())
    {
        $key = $this->getCacheKey($request);

        if (!$entries = $this->getMetadata($key)) {
            return;
        }
 
        // find a cached entry that matches the request.
        $match = null;

        foreach ($entries as $entry) {
            
            $nowheaders = $request->headers->all();
            $oldheaders = $entry[0]; 
                       
            $varyarr = is_array($entry[1]['vary']) ? $entry[1]['vary'] : array();
       
            if($ignore) {
                // 包含非request头的vary，forward 到 listener 中处理
                if($varyarr && array_intersect($this->ignores,$varyarr)) {
                    continue;
                }
           
            } else {
                //在 listener 中补全非request头的 vary 值
                foreach($ignorevalues as $k => $v) {
                    $nowheaders[$k] = $v;
                }
            }
          
            if ($this->requestsMatch(implode(', ',$varyarr), $nowheaders, $oldheaders)) {
                $match = $entry;
                break;
            }
        }
        

        if (null === $match) {
            return;
        }
   
        list($req, $headers) = $match;
        if (is_file($body = $this->getPath($headers['x-content-digest'][0]))) {
            return $this->restoreResponse($headers, $body);
        }
        
        return $req;
    
        // TODO the metaStore referenced an entity that doesn't exist in
        // the entityStore. We definitely want to return nil but we should
        // also purge the entry from the meta-store when this is detected.
    }
    
    /**
     * Writes a cache entry to the store for the given Request and Response.
     *
     * Existing entries are read and any that match the response are removed. This
     * method calls write with the new list of cache entries.
     *
     * @param Request  $request  A Request instance
     * @param Response $response A Response instance
     *
     * @return string The key under which the response is stored
     *
     * @throws \RuntimeException
     */
    public function write(Request $request, Response $response)
    {
        $key = $this->getCacheKey($request);
        $storedEnv = $this->persistRequest($request);
    
        // write the response body to the entity store if this is the original response
        if (!$response->headers->has('X-Content-Digest')) {
            $digest = $this->generateContentDigest($response);
    
            if (false === $this->save($digest, $response->getContent())) {
                throw new \RuntimeException('Unable to store the entity.');
            }
    
            $response->headers->set('X-Content-Digest', $digest);
    
            if (!$response->headers->has('Transfer-Encoding')) {
                $response->headers->set('Content-Length', strlen($response->getContent()));
            }
        }

        $headers = $this->persistResponse($response);
        unset($headers['age']);
        
        //转存响应头中的vary值到请求头中，方便匹配
        $vary = isset($headers['vary']) ? $headers['vary'] : array();
        foreach($vary as $v){
            if(isset($headers[$v]) && !isset($storedEnv[$v])) {
                $storedEnv[$v] = $headers[$v];
            }
        }

        //修正不完全验证vary
        $entries = array();
        $vary = implode(',',$vary);
        foreach ($this->getMetadata($key) as $entry) {
            $nvary = isset($entry[1]['vary'][0]) ? implode(',',$entry[1]['vary']) : '';
    
            if ($vary != $nvary || !$this->requestsMatch($vary, $entry[0], $storedEnv)) {
                $entries[] = $entry;
            }
        }
    
        array_unshift($entries, array($storedEnv, $headers));
    
        if (false === $this->save($key, serialize($entries))) {
            throw new \RuntimeException('Unable to store the metadata.');
        }
    
        return $key;
    }    
    
}
