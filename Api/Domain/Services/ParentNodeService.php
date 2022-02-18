<?php
namespace AjaxSnippets\Api\Domain\Services;

use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;
use AjaxSnippets\Api\Domain\Models\BaseEls\IParentNodeRepository;

class ParentNodeService
{
  private $parentNodeRepository;

  public function __construct(IParentNodeRepository $parentNodeRepository)
  {
    $this->parentNodeRepository = $parentNodeRepository;
  }

  public function exist(string $anken) : bool
  {
    //名前で重複チェック
    $findAnken = $this->parentNodeRepository->ParentFindByName($anken);
    return isset($findAnken);
  }
}