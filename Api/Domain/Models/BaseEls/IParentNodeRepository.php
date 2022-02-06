<?php
namespace AjaxSnippets\Api\Domain\Models\BaseEls;

use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;

interface IParentNodeRepository
{
  public function ParentFindById(int $parentId) : ParentNode;
  public function getAllParent();
  public function saveParent(ParentNode $parent);
    // public function save(Asp $asp) : bool;
    // public function delete(Asp $asp) : bool;
    // public function getAll():array;
    // public function AspFindById(AspId $aspId);
    // public function AspFindByName(Asp $asp);
}