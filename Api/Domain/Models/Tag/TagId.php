<?php
namespace AjaxSnippets\Api\Domain\Models\Tag;

class TagId
{
  public function __construct(private int $id = 0)
  {
    $this->id = $id;
  }

  public function getId()
  {
    return $this->id;
  }
}