<?php
namespace AjaxSnippets\Api\Application\DTO\Log;

class DayPerClickData
{
  public string $date;
  public int $clicks;

  public function __construct($data)
  {
    $this->date = $data->date;
    $this->clicks = $data->clicks;
  }
}