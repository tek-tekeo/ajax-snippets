<?php
namespace AjaxSnippets\Api\Domain\Models\BaseEls;

use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;

interface IParentNodeRepository
{
  public function ParentFindById(int $parentId) : ParentNode;
  public function ParentFindByName(string $name): array;
  public function getAllParent();
  public function saveParent(ParentNode $parent);
}