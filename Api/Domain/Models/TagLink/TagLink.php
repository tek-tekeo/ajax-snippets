<?php
namespace AjaxSnippets\Api\Domain\Models\TagLink;

use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;

class TagLink
{

  public function __construct(
    private TagLinkId $id, 
    private AdDetailId $adDetailId, 
    private TagId $tagId
  ){}

  public function getId(): TagLinkId
  {
    return $this->id;
  }

  public function getAdDetailId(): AdDetailId
  {
    return $this->adDetailId;
  }

  public function getTagId(): TagId
  {
    return $this->tagId;
  }

  public function entity(): array
  {
    return array(
      'id' => $this->id->getId(),
      'ad_detail_id' => $this->adDetailId->getId(),
      'tag_id' => $this->tagId->getId()
    );
  }
}