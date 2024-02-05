<?php
namespace AjaxSnippets\Api\Domain\Models\Asp;

class AspId
{
  public function __construct(private int $id = 0)
  {}

  public function getId(): int
  {
    return $this->id;
  }
}

?>