<?php
use AjaxSnippets\Api\Domain\Models\Log\Log;
use AjaxSnippets\Api\Domain\Models\Log\LogId;
class LogTest extends WP_UnitTestCase
{
  public function testLog()
  {
    $logId = new LogId();
    $log = new Log(
      $logId,
      3,
      '2024-08-01',
      '20:00:00',
      'place',
      'ip'
    );
    $this->assertEquals(new LogId(0), $log->getId());
    $this->assertEquals(3, $log->getItemId());
    $this->assertEquals('2024-08-01', $log->getDate());
    $this->assertEquals('20:00:00', $log->getTime());
    $this->assertEquals('none', $log->getPostAddr());
    $this->assertEquals('place', $log->getPlace());
    $this->assertEquals('ip', $log->getIp());
  }

  public function testLogEntity()
  {
    $logId = new LogId();
    $log = new Log(
      $logId,
      3,
      '2024-08-01',
      '20:00:00',
      'place',
      'ip',
      'post_addr'
    );
    $this->assertEquals(array(
      'id' => 0,
      'item_id' => 3,
      'date' => '2024-08-01',
      'time' => '20:00:00',
      'place' => 'place',
      'ip' => 'ip',
      'post_addr' => 'post_addr'
    ), $log->entity());
  }
}