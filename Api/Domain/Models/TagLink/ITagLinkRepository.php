<?php
namespace AjaxSnippets\Api\Domain\Models\TagLink;

use AjaxSnippets\Api\Domain\Models\TagLink\TagLink;
use AjaxSnippets\Api\Domain\Models\TagLink\TagLinkId;
use AjaxSnippets\Api\Domain\Models\AdDetail\AdDetailId;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;

interface ITagLinkRepository
{
  public function save(TagLink $tagLink) : TagLinkId;
  public function update(TagLink $tagLink) : TagLinkId;
  public function delete(AdDetailId $adDetailId):bool;
  public function getItemIdsByTag(TagId $tagId): array;
  public function findByName(string $name);
  public function findByAdDetailId(AdDetailId $adDetailId): array;
}