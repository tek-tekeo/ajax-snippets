<?php
namespace AjaxSnippets\Api\Application\DTO\Log;

class ClickData
{
  public string $key;
  public string $place;
  public int $clicks;

  public function __construct($key, $place, $clicks)
  {
    $this->key = $key;
    $this->place = $place;
    $this->clicks = $clicks;
  }

}