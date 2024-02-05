<?php
namespace AjaxSnippets\Api\Domain\Models\Asp;

use AjaxSnippets\Api\Domain\Models\Asp\AspId;

class Asp
{
  public function __construct(
    private readonly AspId $id,
    private string $aspName,
    private string $connectString
  ){}

  public function getAspId(): AspId
  {
    return $this->id;
  }

  public function getAspName()
  {
    return $this->aspName;
  }

  public function getConnectString()
  {
    return $this->connectString;
  }

  public function changeAspName(string $aspName)
  {
    if(mb_strlen($aspName) > 10){ 
      throw new \Exception('asp name has been exceeded 10 characters', 500);
    }
    $this->aspName = $aspName;
  }

  public function changeConnectString(string $connectString)
  {
    $this->connectString = $connectString;
  }

  public function entity(): array
  {
    return [
      'id' => $this->id->getId(),
      'asp_name' => $this->aspName,
      'connect_string' => $this->connectString
    ];
  }
}

?>