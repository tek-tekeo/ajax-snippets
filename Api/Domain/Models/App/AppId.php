<?php
namespace AjaxSnippets\Api\Domain\Models\App;

class AppId
{
  private int $id;

  public function __construct(int $id = null)
  {
    if($id === null){
      $this->id = 0;
    }else{
      $this->id = $id;
    }
  }

  public function getId(): int
  {
    return $this->id;
  }

}

?>