<?php
namespace AjaxSnippets\Api\Application\BaseEls;

interface IBaseElsGetService
{
  public function handle(BaseElsGetCommand $cmd);
  public function getBaseFindByName(string $name);
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