<?php
namespace AjaxSnippets\Api\Domain\Models\TagLinks;

use AjaxSnippets\Api\Domain\Models\Details\Detail;
use AjaxSnippets\Api\Domain\Models\Tags\Tag;

class TagLink
{
  private $id;
  private $detail;
  private $tag;

  public function __construct(
    int $id = null,
    Detail $detail,
    Tag $tag
  )
  {
    $this->id = $id;
    $this->detail = $detail;
    $this->tag = $tag;
  }

  //以下、ドメインの知識のみ
  public function getId()
  {
    return $this->id;
  }

  public function getItemId()
  {
    return $this->detail->id();
  }

  public function getTagId()
  {
    return $this->tag->getId();
  }
}

?>