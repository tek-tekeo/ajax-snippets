<?php
namespace AjaxSnippets\Api\Application\DTO;

use AjaxSnippets\Api\Domain\Models\Tag\Tag;

class TagData
{
  public $id;
  public $tagName;
  public $tagOrder;

  public function __construct(Tag $tag)
  {
    $this->id = $tag->getId()->getId(); // どうしたら良いでしょうか？
    $this->tagName = $tag->getName();
    $this->tagOrder = $tag->getOrder();
  }
}