<?php
namespace AjaxSnippets\Api\Application\DTO\Log;

use AjaxSnippets\Api\Domain\Models\Log\Log;

class LogData
{
  public int $id;
  public string $itemId;
  public string $date;
  public string $time;
  public string $place;
  public string $ip;
  public string $postAddr;
  
  public function __construct(Log $log)
  {
    $logId = $log->getId();
    $this->id = $logId->getId();
    $this->date = $log->getDate();
    $this->time = $log->getTime();
    $this->place = $log->getPlace();
    $this->ip = $log->getIp();
    $this->postAddr = $log->getPostAddr();
  }
}