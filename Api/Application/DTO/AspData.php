<?php
namespace AjaxSnippets\Api\Application\DTO;

use AjaxSnippets\Api\Domain\Models\Asp\Asp;

class AspData
{
  public int $id;
  public string $aspName;
  public string $connectString;
  
  public function __construct(Asp $asp)
  {
    $aspId = $asp->getAspId();
    $this->id = $aspId->getId();
    $this->aspName = $asp->getAspName();
    $this->connectString = $asp->getConnectString();
  }
}