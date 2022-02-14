<?php
namespace AjaxSnippets\Api\Domain\Models\Tags;

use AjaxSnippets\Api\Domain\Models\Tags\Tag;

interface ITagRepository
{
  public function getAllTags();
  public function get(int $id);
  public function save(Tag $tag) : bool;
  public function delete(int $id):bool;
}