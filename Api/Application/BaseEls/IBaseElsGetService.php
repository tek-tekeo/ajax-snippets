<?php
namespace AjaxSnippets\Api\Application\BaseEls;

use AjaxSnippets\Api\Domain\Models\BaseEls\ParentNode;
use AjaxSnippets\Api\Domain\Models\BaseEls\App;


interface IBaseElsGetService
{
  public function handle(BaseElsGetCommand $cmd);
  public function getAll();
}

class BaseElsGetCommand
{
  private int $parentId;

  public function __construct(int $id)
  {
    $this->parentId = $id;
  }

  public function parentId()
  {
    return $this->parentId;
  }
}