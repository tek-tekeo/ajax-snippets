<?php
namespace AjaxSnippets\Api\Domain\Models\Tag;

use AjaxSnippets\Api\Domain\Models\Tag\Tag;
use AjaxSnippets\Api\Domain\Models\Tag\TagId;

interface ITagRepository
{
  public function save(Tag $tag): TagId;
  public function findById(TagId $id);
  public function findByName(string $name=''): array;
  public function delete(TagId $id): bool;
}