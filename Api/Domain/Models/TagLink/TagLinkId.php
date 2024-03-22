<?php
namespace AjaxSnippets\Api\Domain\Models\TagLink;

class TagLinkId
{
  private $id;

  public function __construct(int $id = 0)
  {
    $this->id = $id;
  }

  public function getId(): int
  {
    return $this->id;
  }
}