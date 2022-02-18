<?php
namespace AjaxSnippets\Api\Domain\Models\Logs;

class Log
{
  private $id;
  private $itemId;
  private $date;
  private $time;
  private $postAddr;
  private $place;
  private $ip;

  public function __construct(
    ?int $id,
    int $itemId,
    string $date,
    string $time,
    ?string $place,
    string $ip,
    ?string $postAddr
  )
  {
    if($postAddr === null){
      $postAddr = 'none';
      // throw new \Exception('リファラがないやつ');
    }

    if($place === null){
      throw new \Exception('place情報の欠落');
    }
    $this->id = $id;
    $this->itemId = $itemId;
    $this->date = $date;
    $this->time = $time;
    $this->postAddr = $postAddr;
    $this->place = $place;
    $this->ip = $ip;
  }

  //以下、ドメインの知識のみ
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

}

?>