<?php
/**
 * @copyright Copyright (c) 2008 – 2016 www.08cms.com
 * @author 08cms项目开发团队
 * @package 08cms
 * create date 2016年4月19日
 */
namespace OAuthBundle\Buzz\Listener;

use OAuthBundle\Buzz\Listener\History\Journal;
use OAuthBundle\Buzz\Message\MessageInterface;
use OAuthBundle\Buzz\Message\RequestInterface;

class HistoryListener implements ListenerInterface
{
    private $journal;
    private $startTime;

    public function __construct(Journal $journal)
    {
        $this->journal = $journal;
    }

    public function getJournal()
    {
        return $this->journal;
    }

    public function preSend(RequestInterface $request)
    {
        $this->startTime = microtime(true);
    }

    public function postSend(RequestInterface $request, MessageInterface $response)
    {
        $this->journal->record($request, $response, microtime(true) - $this->startTime);
    }
}
