<?php
namespace AjaxSnippets\Api\Domain\Models\AdDetail;

class AdDetailId
{
  public function __construct(private int $id = 0)
  {
    if($id !== 0){
      $this->id = $id;
    }
  }

  public function getId(): int
  {
    return $this->id;
  }

}

?>