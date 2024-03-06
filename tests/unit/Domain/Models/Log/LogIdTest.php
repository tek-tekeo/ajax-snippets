<?php

use AjaxSnippets\Api\Domain\Models\Log\LogId;

class LogIdTest extends WP_UnitTestCase
{
  public function testLogId()
  {
    $logId = new LogId(1);
    $this->assertEquals(1, $logId->getId());
  }

  public function testLogIdNull()
  {
    $logId = new LogId();
    $this->assertEquals(0, $logId->getId());
  }
}