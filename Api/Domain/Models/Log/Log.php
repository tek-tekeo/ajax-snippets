<?php
namespace AjaxSnippets\Api\Domain\Models\Log;

use AjaxSnippets\Api\Domain\Models\Log\LogId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;

class Log
{

  public function __construct(
    private LogId $id,
    private AdDetailId $adDetailId,
    private string $date,
    private string $time,
    private string $place,
    private string $ip,
    private string $postAddr = 'none'
  ){}

  public function getId()
  {
    return $this->id;
  }

  public function getAdDetailId()
  {
    return $this->adDetailId;
  }

  public function getDate()
  {
    return $this->date;
  }

  public function getTime()
  {
    return $this->time;
  }

  public function getPostAddr()
  {
    return $this->postAddr;
  }

  public function getPlace()
  {
    return $this->place;
  }

  public function getIp()
  {
    return $this->ip;
  }

  public function entity()
  {
    return array(
      'id' => $this->id->getId(),
      'ad_detail_id' => $this->getAdDetailId()->getId(),
      'date' => $this->date,
      'time' => $this->time,
      'place' => $this->place,
      'ip' => $this->ip,
      'post_addr' => $this->postAddr
    );
  }

}

?>