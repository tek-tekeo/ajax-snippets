<?php
namespace AjaxSnippets\Api\Domain\Models\TagLinks;

use AjaxSnippets\Api\Domain\Models\TagLinks\TagLink;

interface ITagLinkRepository
{
  public function get(int $itemId);
  public function save(TagLink $tagLink) : bool;
  public function delete(int $itemId):bool;
}