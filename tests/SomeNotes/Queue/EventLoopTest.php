<?php

namespace Queue;

use OtusDZ\Src\SomeNotes\Queue\src\Protocol;
use OtusDZ\Src\SomeNotes\Queue\src\QueueManager;
use PHPUnit\Framework\TestCase;

class EventLoopTest extends TestCase
{
    public function testRequestParser()
    {
        $server = new Protocol();

        $threadId = 1;
        [$headers, $body] = $server->getHeadersAndBody(
            QueueManager::getInstance($threadId)->getPullRequest($threadId)
        );
        $this->assertEmpty($body);
        $this->assertEquals(QueueManager::GET_ACTION, $headers['ActionType']);
        $this->assertEquals($threadId, $headers['ThreadId']);

        $data = ['some data'];
        [$headers, $body] = $server->getHeadersAndBody(
            QueueManager::getInstance($threadId)->getPushRequest($threadId, $data)
        );

        $this->assertEquals($body, json_encode($data));
        $this->assertNotEmpty($headers['ActionType'], QueueManager::GET_ACTION);
        $this->assertNotEmpty($headers['ThreadId'], $threadId);
    }
}
