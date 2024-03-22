<?php
namespace AjaxSnippets\Api\Application\DTO\Log;

use AjaxSnippets\Api\Domain\Models\Log\Log;

class LogData
{
  public int $id;
  public string $dateTime;
  public string $name;
  public string $place;
  public string $ip;
  public string $postAddr;
  
  public function __construct(Log $log, string $name)
  {
    $logId = $log->getId();
    $this->id = $logId->getId();
    $this->name = $name;
    $this->dateTime = $log->getDate(). ' ' . $log->getTime();
    $this->postAddr = $log->getPostAddr();
    $this->place = $log->getPlace();
    $this->ip = $log->getIp();
  }
}