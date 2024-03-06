<?php
namespace AjaxSnippets\Api\Domain\Models\Log;

use AjaxSnippets\Api\Domain\Models\Log\LogId;

class Log
{

  public function __construct(
    private LogId $id,
    private int $itemId,
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

  public function getItemId()
  {
    return $this->itemId;
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
      'item_id' => $this->itemId,
      'date' => $this->date,
      'time' => $this->time,
      'place' => $this->place,
      'ip' => $this->ip,
      'post_addr' => $this->postAddr
    );
  }

}

?>